<?php

namespace App\Http\Controllers;

use App\Imports\RenjaImport;
use App\Imports\RkbmnImport;
use App\Imports\JumlahPegawaiImport;
use App\Imports\SatkerImport;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class masterDataController extends Controller
{
    public function ShowUploadMasterData()
    {
        $currentYear = (int) now()->format('Y');
        $tahunAnggaran = range($currentYear + 5, $currentYear - 5);

        return view(
            'menu.upload-dokumen.master-data',
            compact('tahunAnggaran')
        );
    }

    /**
     * Upload Master Data:
     * - RENJA           : XLSX/XLS/CSV, parser RenjaImport.
     * - RKBMN           : XLSX/XLS, parser RkbmnImport (sheet Pengadaan & Pemeliharaan).
     * - JUMLAH PEGAWAI  : XLSX/XLS, parser JumlahPegawaiImport (satu sheet/Sheet1).
     *
     * Ketiga jenis master data boleh diunggah bersamaan atau salah satu saja.
     * Jika lebih dari satu file diunggah dalam satu request, seluruh proses memakai
     * satu transaksi sehingga database tidak tersimpan setengah-setengah bila salah
     * satu import gagal.
     */
    public function storeMasterData(Request $request)
    {
        /*
         * Jangan gunakan rule mimes:xlsx,xls untuk RENJA/RKBMN.
         *
         * Beberapa file XLSX hasil ekspor aplikasi lain dideteksi PHP Fileinfo
         * sebagai application/zip karena format XLSX pada dasarnya adalah paket ZIP
         * berisi file XML. Akibatnya file XLSX yang valid dapat ditolak oleh rule
         * mimes Laravel.
         *
         * Di sini Laravel hanya memvalidasi bahwa input benar-benar file dan ukuran
         * maksimalnya. Ekstensi yang diperbolehkan diperiksa secara manual setelah
         * validasi dasar.
         */
        $request->validate([
            'renja_file' => 'nullable|file|max:51200',
            'renja_name' => $request->hasFile('renja_file')
                ? 'required|string|max:255'
                : 'nullable|string|max:255',
            'renja_tahun_anggaran' => $request->hasFile('renja_file')
                ? 'required|integer|min:2000|max:2100'
                : 'nullable|integer|min:2000|max:2100',

            'rkbmn_file' => 'nullable|file|max:51200',
            'rkbmn_name' => $request->hasFile('rkbmn_file')
                ? 'required|string|max:255'
                : 'nullable|string|max:255',
            'rkbmn_tahun_anggaran' => $request->hasFile('rkbmn_file')
                ? 'required|integer|min:2000|max:2100'
                : 'nullable|integer|min:2000|max:2100',

            'jumlah_pegawai_file' => 'nullable|file|max:51200',
            'jumlah_pegawai_name' => $request->hasFile('jumlah_pegawai_file')
                ? 'required|string|max:255'
                : 'nullable|string|max:255',
        ]);

        if (
            !$request->hasFile('renja_file')
            && !$request->hasFile('rkbmn_file')
            && !$request->hasFile('jumlah_pegawai_file')
        ) {
            throw ValidationException::withMessages([
                'master_data' => 'Minimal salah satu file RENJA, RKBMN, atau Data Jumlah Pegawai wajib diunggah.',
            ]);
        }

        /*
         * Validasi ekstensi dilakukan manual.
         *
         * RENJA          : XLSX, XLS, CSV.
         * RKBMN          : XLSX, XLS saja karena RKBMN membutuhkan dua worksheet
         *                  (Pengadaan dan Pemeliharaan), sehingga CSV tidak sesuai struktur.
         * JUMLAH PEGAWAI : XLSX, XLS saja. Importer membaca workbook satu-sheet
         *                  (template menggunakan Sheet1).
         *
         * Validitas isi workbook tetap diverifikasi oleh PhpSpreadsheet ketika
         * Excel::import() dijalankan. Jadi file yang sekadar diganti ekstensinya
         * tetapi bukan spreadsheet yang dapat dibaca tetap akan gagal saat parsing
         * dan transaksi akan di-rollback.
         */
        if ($request->hasFile('renja_file')) {
            $this->validateManualExtension(
                $request->file('renja_file'),
                ['xlsx', 'xls', 'csv'],
                'renja_file',
                'RENJA'
            );
        }

        if ($request->hasFile('rkbmn_file')) {
            $this->validateManualExtension(
                $request->file('rkbmn_file'),
                ['xlsx', 'xls'],
                'rkbmn_file',
                'RKBMN'
            );
        }

        if ($request->hasFile('jumlah_pegawai_file')) {
            $this->validateManualExtension(
                $request->file('jumlah_pegawai_file'),
                ['xlsx', 'xls'],
                'jumlah_pegawai_file',
                'Data Jumlah Pegawai'
            );
        }

        $uploadedPaths = [];
        $messages = [];

        DB::beginTransaction();

        try {
            if ($request->hasFile('renja_file')) {
                $renjaPath = $this->processRenja(
                    $request->file('renja_file'),
                    (string) $request->input('renja_name'),
                    (int) $request->input('renja_tahun_anggaran')
                );

                $uploadedPaths[] = $renjaPath;
                $messages[] = 'Master data RENJA berhasil diproses dan disimpan.';
            }

            if ($request->hasFile('rkbmn_file')) {
                $rkbmnPath = $this->processRkbmn(
                    $request->file('rkbmn_file'),
                    (string) $request->input('rkbmn_name'),
                    (int) $request->input('rkbmn_tahun_anggaran')
                );

                $uploadedPaths[] = $rkbmnPath;
                $messages[] = 'Master data RKBMN berhasil diproses dan disimpan.';
            }

            if ($request->hasFile('jumlah_pegawai_file')) {
                $jumlahPegawaiPath = $this->processJumlahPegawai(
                    $request->file('jumlah_pegawai_file'),
                    (string) $request->input('jumlah_pegawai_name')
                );

                $uploadedPaths[] = $jumlahPegawaiPath;
                $messages[] = 'Data jumlah pegawai berhasil diproses dan disimpan.';
            }

            DB::commit();

            $message = implode(' ', $messages);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'title' => 'Master Data Berhasil Diproses',
                    'message' => $message,
                ]);
            }

            return redirect()
                ->back()
                ->with('success', $message);
        } catch (\Throwable $e) {
            DB::rollBack();

            foreach ($uploadedPaths as $path) {
                if ($path && Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }

            Log::error('GAGAL PROSES MASTER DATA', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString(),
            ]);

            $message = 'Master data gagal diproses: ' . $e->getMessage();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'title' => 'Master Data Gagal Diproses',
                    'message' => $message,
                ], 500);
            }

            return redirect()
                ->back()
                ->withInput()
                ->with('error', $message);
        }
    }

    /**
     * Validasi ekstensi berdasarkan nama file asli.
     *
     * Sengaja tidak menggunakan rule Laravel mimes:* karena XLSX tertentu dapat
     * dideteksi sebagai application/zip oleh PHP Fileinfo walaupun workbook valid.
     */
    private function validateManualExtension(
        UploadedFile $file,
        array $allowedExtensions,
        string $field,
        string $label
    ): void {
        $extension = strtolower((string) $file->getClientOriginalExtension());

        if ($extension === '' || !in_array($extension, $allowedExtensions, true)) {
            throw ValidationException::withMessages([
                $field => sprintf(
                    'File %s harus berformat: %s.',
                    $label,
                    strtoupper(implode(', ', $allowedExtensions))
                ),
            ]);
        }
    }

    private function processRenja(
        UploadedFile $file,
        string $documentName,
        int $tahunAnggaran
    ): string {
        $filePath = null;

        try {
            $nextDocId = $this->generateNextDocumentId();

            $cleanFileName = preg_replace(
                '/[^A-Za-z0-9\-_\.]/',
                '_',
                $file->getClientOriginalName()
            );

            $fileName = Str::uuid() . '_RENJA_' . $cleanFileName;
            $filePath = $file->storeAs('uploads/renja', $fileName, 'public');

            if (!$filePath) {
                throw new \RuntimeException('File RENJA gagal disimpan ke storage.');
            }

            DB::table('file_master')->insert([
                'documentID' => $nextDocId,
                'document_name' => $documentName,
                'document_type' => 'RENJA',
                'document_size' => $file->getSize(),
                'file_path' => $filePath,
                'uploaded_by' => session('user_id') ?? 'user_dummy',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            Excel::import(
                new RenjaImport($nextDocId, $tahunAnggaran),
                $file
            );

            return $filePath;
        } catch (\Throwable $e) {
            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            throw $e;
        }
    }

    private function processRkbmn(
        UploadedFile $file,
        string $documentName,
        int $tahunAnggaran
    ): string {
        $filePath = null;

        try {
            $nextDocId = $this->generateNextDocumentId();

            $cleanFileName = preg_replace(
                '/[^A-Za-z0-9\-_\.]/',
                '_',
                $file->getClientOriginalName()
            );

            $fileName = Str::uuid() . '_RKBMN_' . $cleanFileName;
            $filePath = $file->storeAs('uploads/rkbmn', $fileName, 'public');

            if (!$filePath) {
                throw new \RuntimeException('File RKBMN gagal disimpan ke storage.');
            }

            DB::table('file_master')->insert([
                'documentID' => $nextDocId,
                'document_name' => $documentName,
                'document_type' => 'RKBMN',
                'document_size' => $file->getSize(),
                'file_path' => $filePath,
                'uploaded_by' => session('user_id') ?? 'user_dummy',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            Excel::import(
                new RkbmnImport($nextDocId, $tahunAnggaran),
                $file
            );

            return $filePath;
        } catch (\Throwable $e) {
            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            throw $e;
        }
    }

    /**
     * Simpan file Data Jumlah Pegawai dan jalankan importer.
     *
     * Tanggal snapshot dan total pegawai tidak dikirim dari form. Keduanya dibaca
     * langsung oleh JumlahPegawaiImport dari workbook agar nilai yang tersimpan
     * tetap mengikuti dokumen sumber.
     */
    private function processJumlahPegawai(
        UploadedFile $file,
        string $documentName
    ): string {
        $filePath = null;

        try {
            $nextDocId = $this->generateNextDocumentId();

            $cleanFileName = preg_replace(
                '/[^A-Za-z0-9\-_\.]/',
                '_',
                $file->getClientOriginalName()
            );

            $fileName = Str::uuid() . '_JUMLAH_PEGAWAI_' . $cleanFileName;
            $filePath = $file->storeAs(
                'uploads/jumlah-pegawai',
                $fileName,
                'public'
            );

            if (!$filePath) {
                throw new \RuntimeException(
                    'File Data Jumlah Pegawai gagal disimpan ke storage.'
                );
            }

            DB::table('file_master')->insert([
                'documentID' => $nextDocId,
                'document_name' => $documentName,
                'document_type' => 'JUMLAH_PEGAWAI',
                'document_size' => $file->getSize(),
                'file_path' => $filePath,
                'uploaded_by' => session('user_id') ?? 'user_dummy',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            Excel::import(
                new JumlahPegawaiImport($nextDocId),
                $file
            );

            return $filePath;
        } catch (\Throwable $e) {
            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            throw $e;
        }
    }

    private function generateNextDocumentId(): string
    {
        $lastDoc = DB::table('file_master')
            ->lockForUpdate()
            ->orderBy('documentID', 'desc')
            ->first();

        if (!$lastDoc) {
            return 'doc00001';
        }

        $lastNumber = (int) substr((string) $lastDoc->documentID, 3);

        if ($lastNumber >= 99999) {
            throw new \RuntimeException('Kapasitas documentID sudah mencapai batas maksimum doc99999.');
        }

        return 'doc' . str_pad((string) ($lastNumber + 1), 5, '0', STR_PAD_LEFT);
    }

    // ========================================================================
    // FITUR MASTER SATKER - DIPERTAHANKAN
    // ========================================================================

    public function ShowSatker()
    {
        return view('menu.satker');
    }

    public function importDataSatker(Request $request)
    {
        $request->validate([
            'file_satker' => 'required|mimes:xlsx,xls,csv|max:51200',
        ]);

        $uploadedPaths = [];
        $messages = [];

        DB::beginTransaction();

        try {
            $satkerPath = $this->processSatker($request->file('file_satker'));
            $uploadedPaths[] = $satkerPath;
            $messages[] = 'File Master Satker berhasil diimpor dan disimpan ke database.';

            DB::commit();

            return redirect()->back()->with('success', implode(' ', $messages));
        } catch (\Throwable $e) {
            DB::rollBack();

            foreach ($uploadedPaths as $path) {
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }

            Log::error('GAGAL IMPORT MASTER SATKER', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Master Satker gagal diproses: ' . $e->getMessage());
        }
    }

    private function processSatker(UploadedFile $file): string
    {
        $filePath = null;

        try {
            $cleanFileName = preg_replace(
                '/[^A-Za-z0-9\-_\.]/',
                '_',
                $file->getClientOriginalName()
            );

            $fileName = Str::uuid() . '_SATKER_' . $cleanFileName;
            $filePath = $file->storeAs('uploads/satker', $fileName, 'public');

            if (!$filePath) {
                throw new \RuntimeException('File Master Satker gagal disimpan ke storage.');
            }

            Excel::import(new SatkerImport(), $file);

            return $filePath;
        } catch (\Throwable $e) {
            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            throw $e;
        }
    }
}
