<?php

namespace App\Http\Controllers;

use App\Imports\RABExcelImport;
use App\Services\GeminiTorService;
use App\Services\RABService;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class torrabController extends Controller
{
    public function ShowUploadTorRab()
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
            ->orderBy('kode_satker')
            ->get();

        $program = DB::table('program')
            ->select('kode_program', 'nama_program')
            ->orderBy('kode_program')
            ->get();

        $kegiatan = DB::table('kegiatan')
            ->select(
                'kode_kegiatan',
                'nama_kegiatan',
                'kode_program'
            )
            ->orderBy('kode_kegiatan')
            ->get();

        $satkerKegiatan = DB::table('satker_kegiatan')
            ->select('kode_satker', 'kode_kegiatan')
            ->get();

        $kro = DB::table('kro')
            ->select('kode_kro', 'nama_kro', 'kode_kegiatan')
            ->orderBy('kode_kegiatan')
            ->orderBy('kode_kro')
            ->get();

        $ro = DB::table('ro')
            ->select(
                'kode_ro',
                'nama_ro',
                'kode_kro',
                'kode_kegiatan'
            )
            ->orderBy('kode_kegiatan')
            ->orderBy('kode_kro')
            ->orderBy('kode_ro')
            ->get();

        // Khusus kebutuhan RAB. Tidak mengubah proses TOR.
        $currentYear = (int) now()->format('Y');
        $tahunAnggaran = range($currentYear + 5, $currentYear - 5);

        return view(
            'menu.upload-dokumen.torrab',
            compact(
                'unitEselon1',
                'unitEselon2',
                'satker',
                'program',
                'kegiatan',
                'satkerKegiatan',
                'kro',
                'ro',
                'tahunAnggaran'
            )
        );
    }

    /**
     * Satu route/form untuk TOR dan RAB.
     *
     * PENTING:
     * TOR dan RAB diproses dalam transaksi TERPISAH supaya kegagalan Gemini
     * tidak membatalkan RAB, dan kegagalan parser RAB tidak membatalkan TOR.
     */

    public function storeTorRab(
        Request $request,
        GeminiTorService $geminiService,
        RABService $rabService
    ) {
        $request->validate([
            'kode_unit_eselon1' => 'required|string|max:50',
            'kode_unit_eselon2' => 'required|string|max:50',
            'kode_satker'       => 'required|string|max:50',
            'kode_program'      => 'required|string|max:50',
            'kode_kegiatan'     => 'required|string|max:50',
            'kode_kro'          => 'required|string|max:50',
            'kode_ro'           => 'required|string|max:50',

            // Hanya wajib bila RAB diunggah. TOR-only tidak terpengaruh.
            'tahun_anggaran' => $request->hasFile('rab_file')
                ? 'required|integer|min:2000|max:2100'
                : 'nullable|integer|min:2000|max:2100',

            'tor_file' => 'nullable|file|mimes:pdf|max:20480',
            'rab_file' => 'nullable|file|mimes:pdf,xlsx,xls|max:51200',

            'tor_name' => $request->hasFile('tor_file')
                ? 'required|string|max:255'
                : 'nullable|string|max:255',

            'rab_name' => $request->hasFile('rab_file')
                ? 'required|string|max:255'
                : 'nullable|string|max:255',
        ]);

        /*
    |--------------------------------------------------------------------------
    | MINIMAL SATU FILE
    |--------------------------------------------------------------------------
    */

        if (
            !$request->hasFile('tor_file')
            &&
            !$request->hasFile('rab_file')
        ) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'Minimal salah satu file TOR atau RAB wajib diunggah.'
                );
        }

        /*
    |--------------------------------------------------------------------------
    | VALIDASI REFERENSI
    |--------------------------------------------------------------------------
    */

        try {

            $dataOrganisasi =
                $this->resolveReferenceData($request);
        } catch (\Throwable $e) {

            Log::warning(
                'Referensi TOR/RAB tidak valid',
                [
                    'message' => $e->getMessage(),
                ]
            );

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }


        $successMessages = [];
        $errorMessages = [];


        /*
    |--------------------------------------------------------------------------
    | TOR
    |--------------------------------------------------------------------------
    */

        if ($request->hasFile('tor_file')) {

            try {

                $this->processTOR(
                    $request->file('tor_file'),
                    $dataOrganisasi,
                    $geminiService,
                    $request->tor_name
                );

                $successMessages[] =
                    'Dokumen TOR berhasil diproses AI dan disimpan.';
            } catch (\Throwable $e) {

                Log::error(
                    'GAGAL PROSES TOR',
                    [
                        'message' => $e->getMessage(),
                        'line'    => $e->getLine(),
                        'file'    => $e->getFile(),
                        'trace'   => $e->getTraceAsString(),
                    ]
                );

                $errorMessages[] =
                    'TOR gagal diproses: '
                    . $e->getMessage();
            }
        }


        /*
    |--------------------------------------------------------------------------
    | RAB
    |--------------------------------------------------------------------------
    */

        if ($request->hasFile('rab_file')) {

            try {

                $this->processRAB(
                    $request->file('rab_file'),
                    $dataOrganisasi,
                    $rabService,
                    (int) $request->tahun_anggaran,
                    $request->rab_name
                );

                $successMessages[] =
                    'Dokumen RAB berhasil diekstrak dan disimpan.';
            } catch (\Throwable $e) {

                Log::error(
                    'GAGAL PROSES RAB',
                    [
                        'message' => $e->getMessage(),
                        'line'    => $e->getLine(),
                        'file'    => $e->getFile(),
                        'trace'   => $e->getTraceAsString(),
                    ]
                );

                $errorMessages[] =
                    'RAB gagal diproses: '
                    . $e->getMessage();
            }
        }


        /*
    |--------------------------------------------------------------------------
    | RESPONSE
    |--------------------------------------------------------------------------
    */

        if (
            !empty($successMessages)
            &&
            empty($errorMessages)
        ) {

            /*
         * Semuanya berhasil.
         * Tidak pakai withInput() supaya form kembali bersih.
         */

            return redirect()
                ->back()
                ->with(
                    'success',
                    implode(
                        ' ',
                        $successMessages
                    )
                );
        }


        /*
     * Ada kegagalan sebagian / seluruhnya.
     * Pertahankan dropdown yang sudah dipilih.
     */

        $redirect =
            redirect()
            ->back()
            ->withInput();


        if (!empty($successMessages)) {

            $redirect->with(
                'success',
                implode(
                    ' ',
                    $successMessages
                )
            );
        }


        if (!empty($errorMessages)) {

            $redirect->with(
                'error',
                implode(
                    ' ',
                    $errorMessages
                )
            );
        }


        return $redirect;
    }
    // public function storeTorRab(
    //     Request $request,
    //     GeminiTorService $geminiService
    // ) {
    //     $request->validate([
    //         'kode_unit_eselon1' => 'required|string|max:50',
    //         'kode_unit_eselon2' => 'required|string|max:50',
    //         'kode_satker'       => 'required|string|max:50',
    //         'kode_program'      => 'required|string|max:50',
    //         'kode_kegiatan'     => 'required|string|max:50',
    //         'kode_kro'          => 'required|string|max:50',
    //         'kode_ro'           => 'required|string|max:50',

    //         'tor_file' => 'nullable|file|mimes:pdf|max:20480',
    //         'rab_file' => 'nullable|file|mimes:pdf,xlsx,xls|max:51200',

    //         'tor_name' => $request->hasFile('tor_file')
    //             ? 'required|string|max:255'
    //             : 'nullable|string|max:255',

    //         'rab_name' => $request->hasFile('rab_file')
    //             ? 'required|string|max:255'
    //             : 'nullable|string|max:255',
    //     ]);

    //     if (!$request->hasFile('tor_file') && !$request->hasFile('rab_file')) {
    //         return redirect()
    //             ->back()
    //             ->withInput()
    //             ->with(
    //                 'error',
    //                 'Minimal salah satu file TOR atau RAB wajib diunggah.'
    //             );
    //     }

    //     try {
    //         $dataOrganisasi = $this->resolveReferenceData($request);
    //     } catch (\Throwable $e) {
    //         Log::warning('Referensi TOR/RAB tidak valid', [
    //             'message' => $e->getMessage(),
    //         ]);

    //         return redirect()
    //             ->back()
    //             ->withInput()
    //             ->with('error', $e->getMessage());
    //     }

    //     $successMessages = [];
    //     $errorMessages = [];

    //     /*
    //     |--------------------------------------------------------------------------
    //     | PROSES TOR
    //     |--------------------------------------------------------------------------
    //     */
    //     if ($request->hasFile('tor_file')) {
    //         try {
    //             $this->processTOR(
    //                 $request->file('tor_file'),
    //                 $dataOrganisasi,
    //                 $geminiService,
    //                 $request->tor_name
    //             );

    //             $successMessages[] =
    //                 'Dokumen TOR berhasil diproses AI dan disimpan.';
    //         } catch (\Throwable $e) {
    //             Log::error('GAGAL PROSES TOR', [
    //                 'message' => $e->getMessage(),
    //                 'line'    => $e->getLine(),
    //                 'file'    => $e->getFile(),
    //                 'trace'   => $e->getTraceAsString(),
    //             ]);

    //             $errorMessages[] =
    //                 'TOR gagal diproses: ' . $e->getMessage();
    //         }
    //     }

    //     /*
    //     |--------------------------------------------------------------------------
    //     | PROSES RAB
    //     |--------------------------------------------------------------------------
    //     |
    //     | Tetap dijalankan walaupun TOR gagal.
    //     */
    //     if ($request->hasFile('rab_file')) {
    //         try {
    //             $this->processRAB(
    //                 $request->file('rab_file'),
    //                 $dataOrganisasi,
    //                 $request->rab_name
    //             );

    //             $successMessages[] =
    //                 'Dokumen RAB berhasil diekstrak dan disimpan.';
    //         } catch (\Throwable $e) {
    //             Log::error('GAGAL PROSES RAB', [
    //                 'message' => $e->getMessage(),
    //                 'line'    => $e->getLine(),
    //                 'file'    => $e->getFile(),
    //                 'trace'   => $e->getTraceAsString(),
    //             ]);

    //             $errorMessages[] =
    //                 'RAB gagal diproses: ' . $e->getMessage();
    //         }
    //     }

    //     $redirect = redirect()->back()->withInput();

    //     if (!empty($successMessages)) {
    //         $redirect->with(
    //             'success',
    //             implode(' ', $successMessages)
    //         );
    //     }

    //     if (!empty($errorMessages)) {
    //         $redirect->with(
    //             'error',
    //             implode(' ', $errorMessages)
    //         );
    //     }

    //     return $redirect;
    // }

    /**
     * Validasi sekaligus resolve nama master berdasarkan hierarki dropdown.
     */
    private function resolveReferenceData(Request $request): array
    {
        $unit1 = DB::table('unit_eselon_1')
            ->where('kode_unit_eselon1', $request->kode_unit_eselon1)
            ->first();

        if (!$unit1) {
            throw new \RuntimeException('Unit Eselon I tidak ditemukan.');
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
                'Satker tidak sesuai dengan Unit Eselon II yang dipilih.'
            );
        }

        $program = DB::table('program')
            ->where('kode_program', $request->kode_program)
            ->first();

        if (!$program) {
            throw new \RuntimeException('Program tidak ditemukan.');
        }

        $kegiatan = DB::table('kegiatan')
            ->where('kode_kegiatan', $request->kode_kegiatan)
            ->where('kode_program', $request->kode_program)
            ->first();

        if (!$kegiatan) {
            throw new \RuntimeException(
                'Kegiatan tidak sesuai dengan Program yang dipilih.'
            );
        }

        $satkerKegiatanExists = DB::table('satker_kegiatan')
            ->where('kode_satker', $request->kode_satker)
            ->where('kode_kegiatan', $request->kode_kegiatan)
            ->exists();

        if (!$satkerKegiatanExists) {
            throw new \RuntimeException(
                'Kegiatan tidak terdaftar untuk Satker yang dipilih.'
            );
        }

        $kro = DB::table('kro')
            ->where('kode_kegiatan', $request->kode_kegiatan)
            ->where('kode_kro', $request->kode_kro)
            ->first();

        if (!$kro) {
            throw new \RuntimeException(
                'KRO tidak sesuai dengan Kegiatan yang dipilih.'
            );
        }

        $ro = DB::table('ro')
            ->where('kode_kegiatan', $request->kode_kegiatan)
            ->where('kode_kro', $request->kode_kro)
            ->where('kode_ro', $request->kode_ro)
            ->first();

        if (!$ro) {
            throw new \RuntimeException(
                'RO tidak sesuai dengan Kegiatan dan KRO yang dipilih.'
            );
        }

        return [
            'kode_unit_eselon1' => $unit1->kode_unit_eselon1,
            'nama_unit_eselon1' => $unit1->nama_unit_eselon1,

            'kode_unit_eselon2' => $unit2->kode_unit_eselon2,
            'nama_unit_eselon2' => $unit2->nama_unit_eselon2,

            'kode_satker' => $satker->kode_satker,
            'nama_satker' => $satker->nama_satker,

            'kode_program' => $program->kode_program,
            'nama_program' => $program->nama_program,

            'kode_kegiatan' => $kegiatan->kode_kegiatan,
            'nama_kegiatan' => $kegiatan->nama_kegiatan,

            'kode_kro' => $kro->kode_kro,
            'nama_kro' => $kro->nama_kro,

            'kode_ro' => $ro->kode_ro,
            'nama_ro' => $ro->nama_ro,
        ];
    }

    /**
     * Proses TOR:
     * 1. Simpan file fisik.
     * 2. Gemini membaca PDF asli di LUAR transaksi DB.
     * 3. Setelah AI sukses, simpan file_master + tor dalam satu transaksi singkat.
     */
    private function processTOR(
        UploadedFile $file,
        array $dataOrganisasi,
        GeminiTorService $geminiService,
        ?string $documentName = null
    ): string {
        $filePath = null;

        try {
            $extension = strtolower($file->getClientOriginalExtension());

            if ($extension !== 'pdf') {
                throw new \RuntimeException(
                    'TOR hanya dapat diproses dalam format PDF.'
                );
            }

            $cleanFileName = preg_replace(
                '/[^A-Za-z0-9\-\_\.]/',
                '_',
                $file->getClientOriginalName()
            );

            $fileName = Str::uuid()
                . '_TOR_'
                . $cleanFileName;

            $filePath = $file->storeAs(
                'uploads/tor',
                $fileName,
                'public'
            );

            if (!$filePath) {
                throw new \RuntimeException(
                    'File TOR gagal disimpan ke storage.'
                );
            }

            $absolutePdfPath = Storage::disk('public')->path($filePath);

            if (!is_file($absolutePdfPath)) {
                throw new \RuntimeException(
                    'File TOR tidak ditemukan setelah proses upload.'
                );
            }

            // Tidak membuka transaction DB selama menunggu Gemini.
            $extractedData = $geminiService->extractTorDataFromPdf(
                $absolutePdfPath
            );

            DB::transaction(function () use (
                $file,
                $filePath,
                $dataOrganisasi,
                $extractedData,
                $documentName
            ) {
                $nextDocId = $this->generateNextDocumentId();
                $torID = $this->generateNextTorId();

                DB::table('file_master')->insert([
                    'documentID'    => $nextDocId,
                    'document_name' => $documentName ?: $file->getClientOriginalName(),
                    'document_type' => 'TOR',
                    'document_size' => $file->getSize(),
                    'file_path'     => $filePath,
                    'uploaded_by'   => session('user_id') ?? 'user_dummy',
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);

                DB::table('tor')->insert([
                    'torID'      => $torID,
                    'documentID' => $nextDocId,

                    'kode_unit_eselon1' => $dataOrganisasi['kode_unit_eselon1'],
                    'nama_unit_eselon1' => $dataOrganisasi['nama_unit_eselon1'],
                    'kode_unit_eselon2' => $dataOrganisasi['kode_unit_eselon2'],
                    'nama_unit_eselon2' => $dataOrganisasi['nama_unit_eselon2'],
                    'kode_satker'       => $dataOrganisasi['kode_satker'],
                    'nama_satker'       => $dataOrganisasi['nama_satker'],
                    'kode_program'      => $dataOrganisasi['kode_program'],
                    'nama_program'      => $dataOrganisasi['nama_program'],
                    'kode_kegiatan'     => $dataOrganisasi['kode_kegiatan'],
                    'nama_kegiatan'     => $dataOrganisasi['nama_kegiatan'],
                    'kode_kro'          => $dataOrganisasi['kode_kro'],
                    'nama_kro'          => $dataOrganisasi['nama_kro'],
                    'kode_ro'           => $dataOrganisasi['kode_ro'],
                    'nama_ro'           => $dataOrganisasi['nama_ro'],

                    'rincian_output' =>
                    $extractedData['rincian_output'] ?? null,

                    'volume_ro' =>
                    $extractedData['volume_ro'] ?? null,

                    'satuan_ro' =>
                    $extractedData['satuan_ro'] ?? null,

                    'gambaran_umum' =>
                    $extractedData['gambaran_umum'] ?? null,

                    'metode_pelaksanaan' =>
                    $extractedData['metode_pelaksanaan'] ?? null,

                    'kurun_waktu_pencapaian_keluaran' =>
                    $extractedData['kurun_waktu_pencapaian_keluaran'] ?? null,

                    'total_biaya' =>
                    $extractedData['total_biaya'] ?? null,

                    'dasar_hukum' => $this->encodeJsonArray(
                        $extractedData['dasar_hukum'] ?? []
                    ),

                    'penerima_manfaat' => $this->encodeJsonArray(
                        $extractedData['penerima_manfaat'] ?? []
                    ),

                    'tahapan_pelaksanaan' => $this->encodeJsonArray(
                        $extractedData['tahapan_pelaksanaan'] ?? []
                    ),

                    'jadwal_pelaksanaan' => $this->encodeJsonArray(
                        $extractedData['jadwal_pelaksanaan'] ?? []
                    ),

                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
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
     * Proses RAB PDF / Excel.
     *
     * PDF   : diekstrak Gemini melalui RABService di LUAR transaksi DB.
     * Excel : diproses deterministik/manual oleh RABExcelImport.
     *
     * file_master + baris rab tetap disimpan dalam satu transaksi singkat.
     */
    private function processRAB(
        UploadedFile $file,
        array $dataOrganisasi,
        RABService $rabService,
        int $tahunAnggaran,
        ?string $documentName = null
    ): string {
        $filePath = null;

        try {
            $extension = strtolower(
                $file->getClientOriginalExtension()
            );

            if (!in_array($extension, ['pdf', 'xlsx', 'xls'], true)) {
                throw new \RuntimeException(
                    'Format RAB tidak didukung. Gunakan PDF, XLSX, atau XLS.'
                );
            }

            $cleanFileName = preg_replace(
                '/[^A-Za-z0-9\-\_\.]/',
                '_',
                $file->getClientOriginalName()
            );

            $fileName = Str::uuid()
                . '_RAB_'
                . $cleanFileName;

            $filePath = $file->storeAs(
                'uploads/rab',
                $fileName,
                'public'
            );

            if (!$filePath) {
                throw new \RuntimeException(
                    'File RAB gagal disimpan ke storage.'
                );
            }

            $absolutePath = Storage::disk('public')->path($filePath);

            if (!is_file($absolutePath)) {
                throw new \RuntimeException(
                    'File RAB tidak ditemukan setelah proses upload.'
                );
            }

            /*
             * PDF dianalisis Gemini sebelum DB transaction dibuka.
             * Excel tidak menggunakan AI.
             */
            $pdfPayload = null;

            if ($extension === 'pdf') {
                $pdfPayload = $rabService->extractFromPdf($absolutePath);
            }

            DB::transaction(function () use (
                $file,
                $filePath,
                $absolutePath,
                $extension,
                $dataOrganisasi,
                $documentName,
                $rabService,
                $tahunAnggaran,
                $pdfPayload
            ) {
                $nextDocId = $this->generateNextDocumentId();

                DB::table('file_master')->insert([
                    'documentID'    => $nextDocId,
                    'document_name' => $documentName ?: $file->getClientOriginalName(),
                    'document_type' => 'RAB',
                    'document_size' => $file->getSize(),
                    'file_path'     => $filePath,
                    'uploaded_by'   => session('user_id') ?? 'user_dummy',
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);

                if ($extension === 'pdf') {
                    $rabService->insertRows(
                        $nextDocId,
                        $tahunAnggaran,
                        $dataOrganisasi,
                        $pdfPayload ?? []
                    );
                } else {
                    Excel::import(
                        new RABExcelImport(
                            $nextDocId,
                            $dataOrganisasi,
                            $tahunAnggaran,
                            $rabService
                        ),
                        $absolutePath
                    );
                }

                // Jangan anggap berhasil jika ekstraktor/import tidak membuat row RAB.
                $hasRabRows = DB::table('rab')
                    ->where('documentID', $nextDocId)
                    ->exists();

                if (!$hasRabRows) {
                    throw new \RuntimeException(
                        'Proses RAB selesai tetapi tidak ada data yang masuk ke tabel rab. '
                            . 'Periksa RABService/RABExcelImport.'
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
     * Harus dipanggil di dalam DB transaction agar lockForUpdate efektif.
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

        $lastNumber = (int) substr($lastDocumentId, 3);

        return 'doc'
            . str_pad(
                $lastNumber + 1,
                5,
                '0',
                STR_PAD_LEFT
            );
    }

    /**
     * Harus dipanggil di dalam DB transaction.
     */
    private function generateNextTorId(): string
    {
        $lastTorId = DB::table('tor')
            ->lockForUpdate()
            ->orderByDesc('torID')
            ->value('torID');

        if (!$lastTorId) {
            return 'tor00000001';
        }

        $lastNumber = (int) substr($lastTorId, 3);

        return 'tor'
            . str_pad(
                $lastNumber + 1,
                8,
                '0',
                STR_PAD_LEFT
            );
    }

    private function encodeJsonArray(mixed $value): string
    {
        $array = is_array($value) ? $value : [];

        $json = json_encode(
            $array,
            JSON_UNESCAPED_UNICODE
                | JSON_UNESCAPED_SLASHES
                | JSON_THROW_ON_ERROR
        );

        return $json;
    }
}
