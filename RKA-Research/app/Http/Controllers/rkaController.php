<?php

namespace App\Http\Controllers;

use App\Imports\RKAImport;
use App\Services\RKAService;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class rkaController extends Controller
{
    public function ShowUploadRka()
    {
        $unitEselon1 = DB::table('unit_eselon_1')
            ->select('kode_unit_eselon1', 'nama_unit_eselon1')
            ->orderBy('kode_unit_eselon1')
            ->get();

        $unitEselon2 = DB::table('unit_eselon_2')
            ->select(
                'kode_unit_eselon2',
                'nama_unit_eselon2',
                'kode_unit_eselon1'
            )
            ->orderBy('kode_unit_eselon2')
            ->get();

        $satker = DB::table('satker')
            ->select(
                'kode_satker',
                'nama_satker',
                'kode_unit_eselon2'
            )
            ->distinct()
            ->orderBy('kode_satker')
            ->get();

        $currentYear = (int) now()->format('Y');
        $tahunAnggaran = range($currentYear + 5, $currentYear - 5);

        return view(
            'menu.upload-dokumen.rka',
            compact(
                'unitEselon1',
                'unitEselon2',
                'satker',
                'tahunAnggaran'
            )
        );
    }

    /**
     * Upload RKA:
     * - PDF  -> Gemini AI melalui RKAService.
     * - XLSX/XLS -> parser manual RKAImport.
     */
    public function storeRka(Request $request, RKAService $rkaService)
    {
        set_time_limit(300);

        $request->validate([
            'rka_file' => 'required|file|mimes:pdf,xlsx,xls|max:51200',
            'rka_name' => 'required|string|max:255',
            'tahun_anggaran' => 'required|integer|min:2000|max:2100',
            'kode_unit_eselon1' => 'required|string|max:50',
            'kode_unit_eselon2' => 'required|string|max:50',
            'kode_satker' => 'required|string|max:50',
        ]);

        try {
            $dataOrganisasi = $this->resolveOrganization($request);

            $extension = strtolower(
                $request->file('rka_file')->getClientOriginalExtension()
            );

            $this->processRKA(
                $request->file('rka_file'),
                $request->input('rka_name'),
                (int) $request->input('tahun_anggaran'),
                $dataOrganisasi,
                $rkaService
            );

            $message = $extension === 'pdf'
                ? 'Dokumen RKA PDF berhasil diproses Gemini AI dan disimpan.'
                : 'Dokumen RKA Excel berhasil diproses dan disimpan.';

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'title' => 'Dokumen RKA Berhasil Diproses',
                    'message' => $message,
                ]);
            }

            return redirect()->back()->with('success', $message);
        } catch (\Throwable $e) {
            Log::error('GAGAL PROSES RKA', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString(),
            ]);

            $status = $this->exceptionHttpStatus($e);
            $message = $this->clientErrorMessage($e);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'title' => 'RKA Gagal Diproses',
                    'message' => $message,
                ], $status);
            }

            return redirect()->back()->withInput()->with('error', $message);
        }
    }

    private function clientErrorMessage(\Throwable $e): string
    {
        $status = $this->exceptionHttpStatus($e);
        $message = trim((string) $e->getMessage());

        if ($status === 500 && !$this->hasHttpExceptionCode($e) && !config('app.debug')) {
            return 'RKA gagal diproses karena terjadi kesalahan internal server.';
        }

        if ($message === '') {
            $message = 'Terjadi kesalahan saat memproses dokumen.';
        }

        return 'RKA gagal diproses: ' . $message;
    }

    private function exceptionHttpStatus(\Throwable $e): int
    {
        return $this->hasHttpExceptionCode($e) ? (int) $e->getCode() : 500;
    }

    private function hasHttpExceptionCode(\Throwable $e): bool
    {
        $code = (int) $e->getCode();
        return $code >= 400 && $code <= 599;
    }

    /**
     * Pastikan hierarchy organisasi yang dikirim form benar-benar konsisten
     * dengan master database, bukan hanya percaya pada value dari browser.
     */
    private function resolveOrganization(Request $request): array
    {
        $unit1 = DB::table('unit_eselon_1')
            ->where('kode_unit_eselon1', $request->kode_unit_eselon1)
            ->first();

        if (!$unit1) {
            throw new \RuntimeException('Unit Eselon I yang dipilih tidak ditemukan.');
        }

        $unit2 = DB::table('unit_eselon_2')
            ->where('kode_unit_eselon2', $request->kode_unit_eselon2)
            ->where('kode_unit_eselon1', $request->kode_unit_eselon1)
            ->first();

        if (!$unit2) {
            throw new \RuntimeException(
                'Unit Eselon II tidak sesuai dengan Unit Eselon I yang dipilih.'
            );
        }

        $satker = DB::table('satker')
            ->where('kode_satker', $request->kode_satker)
            ->where('kode_unit_eselon2', $request->kode_unit_eselon2)
            ->first();

        if (!$satker) {
            throw new \RuntimeException(
                'Satuan Kerja tidak sesuai dengan Unit Eselon II yang dipilih.'
            );
        }

        return [
            'kode_unit_eselon1' => (string) $unit1->kode_unit_eselon1,
            'nama_unit_eselon1' => (string) $unit1->nama_unit_eselon1,
            'kode_unit_eselon2' => (string) $unit2->kode_unit_eselon2,
            'nama_unit_eselon2' => (string) $unit2->nama_unit_eselon2,
            'kode_satker' => (string) $satker->kode_satker,
            'nama_satker' => (string) $satker->nama_satker,
        ];
    }

    /**
     * PDF: AI dijalankan di luar transaction.
     * Excel: parser manual dijalankan di dalam transaction bersama file_master.
     */
    private function processRKA(
        UploadedFile $file,
        string $documentName,
        int $tahunAnggaran,
        array $dataOrganisasi,
        RKAService $rkaService
    ): string {
        $filePath = null;

        try {
            $extension = strtolower($file->getClientOriginalExtension());

            if (!in_array($extension, ['pdf', 'xlsx', 'xls'], true)) {
                throw new \RuntimeException(
                    'Format RKA tidak didukung. Gunakan PDF, XLSX, atau XLS.'
                );
            }

            $cleanFileName = preg_replace(
                '/[^A-Za-z0-9\-_\.]/',
                '_',
                $file->getClientOriginalName()
            );

            $fileName = Str::uuid()
                . '_RKA_'
                . $cleanFileName;

            $filePath = $file->storeAs(
                'uploads/rka',
                $fileName,
                'public'
            );

            if (!$filePath) {
                throw new \RuntimeException('File RKA gagal disimpan ke storage.');
            }

            $absolutePath = Storage::disk('public')->path($filePath);

            if (!is_file($absolutePath)) {
                throw new \RuntimeException(
                    'File RKA tidak ditemukan setelah proses upload.'
                );
            }

            // Gemini dipanggil sebelum transaksi database agar koneksi DB tidak
            // tertahan selama request AI yang dapat berlangsung cukup lama.
            $pdfPayload = null;

            if ($extension === 'pdf') {
                $pdfPayload = $rkaService->extractFromPdf($absolutePath);
            }

            DB::transaction(function () use (
                $file,
                $filePath,
                $absolutePath,
                $extension,
                $documentName,
                $tahunAnggaran,
                $dataOrganisasi,
                $rkaService,
                $pdfPayload
            ) {
                $nextDocId = $this->generateNextDocumentId();

                DB::table('file_master')->insert([
                    'documentID' => $nextDocId,
                    'document_name' => $documentName,
                    'document_type' => 'RKA',
                    'document_size' => $file->getSize(),
                    'file_path' => $filePath,
                    'uploaded_by' => session('user_id') ?? 'user_dummy',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                if ($extension === 'pdf') {
                    $rkaService->insertRows(
                        $nextDocId,
                        $tahunAnggaran,
                        $dataOrganisasi,
                        $pdfPayload ?? []
                    );
                } else {
                    Excel::import(
                        new RKAImport(
                            $nextDocId,
                            $dataOrganisasi,
                            $tahunAnggaran
                        ),
                        $absolutePath
                    );
                }

                $hasRows = DB::table('rka')
                    ->where('documentID', $nextDocId)
                    ->exists();

                if (!$hasRows) {
                    throw new \RuntimeException(
                        'Proses RKA selesai tetapi tidak ada data yang masuk ke tabel rka.'
                    );
                }
            }, 3);

            return $filePath;
        } catch (\Throwable $e) {
            if (
                $filePath
                && Storage::disk('public')->exists($filePath)
            ) {
                Storage::disk('public')->delete($filePath);
            }

            throw $e;
        }
    }

    /**
     * Harus dipanggil di dalam transaction supaya lockForUpdate efektif.
     */
    private function generateNextDocumentId(): string
    {
        $lastDocumentId = DB::table('file_master')
            ->lockForUpdate()
            ->orderByDesc('documentID')
            ->value('documentID');

        if (!$lastDocumentId) {
            return 'doc00001';
        }

        $lastNumber = (int) substr((string) $lastDocumentId, 3);

        return 'doc' . str_pad((string) ($lastNumber + 1), 5, '0', STR_PAD_LEFT);
    }
}
