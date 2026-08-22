<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RKAService
{
    private string $apiKey;
    private string $model;
    private string $baseUrl;

    public function __construct()
    {
        // Menggunakan API key yang sama dengan fitur RAB.
        $this->apiKey = (string) env('GEMINI_API_KEY', '');
        $this->model = (string) env('GEMINI_RKA_MODEL', 'gemini-3.5-flash-lite');
        $this->baseUrl = rtrim(
            (string) env('GEMINI_API_BASE_URL', 'https://generativelanguage.googleapis.com/v1beta'),
            '/'
        );
    }

    /**
     * Ekstrak seluruh RKA PDF dengan Gemini.
     *
     * PENTING:
     * - Method ini tidak melakukan INSERT database.
     * - Request AI dilakukan sebelum transaksi database dibuka.
     * - Satu PDF boleh berisi lebih dari satu Program/Kegiatan/KRO/RO.
     */
    public function extractFromPdf(string $absolutePdfPath): array
    {

        if ($this->apiKey === '') {
            throw new \RuntimeException('GEMINI_API_KEY belum dikonfigurasi.');
        }

        if (!is_file($absolutePdfPath)) {
            throw new \RuntimeException('File PDF RKA tidak ditemukan.');
        }

        $mimeType = mime_content_type($absolutePdfPath) ?: 'application/pdf';

        if ($mimeType !== 'application/pdf') {
            throw new \RuntimeException('File yang dikirim ke Gemini harus berupa PDF.');
        }

        $fileSize = filesize($absolutePdfPath);

        if ($fileSize === false || $fileSize <= 0) {
            throw new \RuntimeException('File PDF RKA kosong atau tidak dapat dibaca.');
        }

        if ($fileSize > 50 * 1024 * 1024) {
            throw new \RuntimeException('Ukuran PDF RKA melebihi batas 50 MB.');
        }

        $pdfBytes = file_get_contents($absolutePdfPath);

        if ($pdfBytes === false) {
            throw new \RuntimeException('File PDF RKA gagal dibaca.');
        }

        $payload = [
            'contents' => [
                [
                    'role' => 'user',
                    'parts' => [
                        [
                            'text' => $this->buildPrompt(),
                        ],
                        [
                            'inlineData' => [
                                'mimeType' => 'application/pdf',
                                'data' => base64_encode($pdfBytes),
                            ],
                        ],
                    ],
                ],
            ],
            'generationConfig' => [
                // Format ini sama dengan service RAB yang sudah digunakan.
                'responseMimeType' => 'application/json',
                'responseJsonSchema' => $this->responseSchema(),
            ],
        ];

        $url = $this->baseUrl
            . '/models/'
            . rawurlencode($this->model)
            . ':generateContent';

        set_time_limit(300);

        try {
            $response = Http::withHeaders([
                'x-goog-api-key' => $this->apiKey,
                'Accept' => 'application/json',
            ])
                ->asJson()
                ->timeout(240)
                ->connectTimeout(30)
                // Retry sengaja tidak digunakan untuk RKA PDF karena satu request
                // dapat berlangsung lama dan menghasilkan output JSON besar.
                ->post($url, $payload);
        } catch (RequestException $e) {
            $status = $e->response?->status() ?? 502;
            $apiMessage = $e->response
                ? data_get($e->response->json(), 'error.message')
                : null;

            throw new \RuntimeException(
                $this->geminiHttpMessage($status, $apiMessage, 'RKA'),
                $status,
                $e
            );
        } catch (ConnectionException $e) {
            $status = $this->connectionExceptionStatus($e);

            throw new \RuntimeException(
                $status === 504
                    ? 'Waktu pemrosesan Gemini untuk RKA habis. Silakan coba kembali.'
                    : 'Tidak dapat terhubung ke layanan Gemini saat memproses RKA. Silakan coba kembali.',
                $status,
                $e
            );
        }

        if (!$response->successful()) {
            $status = $response->status();
            $apiMessage = data_get($response->json(), 'error.message');

            Log::error('Gemini RKA API gagal', [
                'status' => $status,
                'body' => $response->body(),
                'model' => $this->model,
            ]);

            throw new \RuntimeException(
                $this->geminiHttpMessage($status, $apiMessage, 'RKA'),
                $status
            );
        }

        $text = $this->extractResponseText($response->json());

        try {
            $decoded = json_decode($text, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            Log::error('JSON Gemini RKA tidak valid', [
                'response_text' => $text,
                'message' => $e->getMessage(),
            ]);

            throw new \RuntimeException(
                'Gemini menghasilkan JSON RKA yang tidak valid: ' . $e->getMessage()
            );
        }

        return $this->normalizePayload($decoded);
    }

    /**
     * Simpan hasil ekstraksi PDF ke tabel rka.
     * Harus dipanggil di dalam DB::transaction() agar generator rkaID aman.
     */
    public function insertRows(
        string $documentID,
        int $tahunAnggaran,
        array $dataOrganisasi,
        array $payload
    ): int {
        $payload = $this->normalizePayload($payload);

        $this->validateDocumentContext(
            $payload,
            $tahunAnggaran,
            $dataOrganisasi
        );

        $rows = $payload['rows'] ?? [];

        if (empty($rows)) {
            throw new \RuntimeException('Gemini tidak menemukan detail belanja RKA yang dapat disimpan.');
        }

        // Validasi jumlah leaf terhadap ALOKASI header jika angka header berhasil dibaca.
        $this->validateAllocation($payload);

        $lastRkaId = DB::table('rka')
            ->lockForUpdate()
            ->orderByDesc('rkaID')
            ->value('rkaID');

        $nextNumber = $lastRkaId
            ? ((int) substr((string) $lastRkaId, 3)) + 1
            : 1;

        $now = now();
        $insertRows = [];

        foreach ($rows as $row) {
            // Tabel RKA hanya menyimpan leaf/detail alokasi.
            if (
                empty($row['uraian_detail'])
                || empty($row['kode_akun'])
                || $row['jumlah_biaya'] === null
            ) {
                continue;
            }

            $insertRows[] = [
                'rkaID' => 'rka' . str_pad((string) $nextNumber, 8, '0', STR_PAD_LEFT),
                'documentID' => $documentID,
                'tahun_anggaran' => $tahunAnggaran,

                'kode_unit_eselon1' => $dataOrganisasi['kode_unit_eselon1'] ?? null,
                'nama_unit_eselon1' => $dataOrganisasi['nama_unit_eselon1'] ?? null,
                'kode_unit_eselon2' => $dataOrganisasi['kode_unit_eselon2'] ?? null,
                'nama_unit_eselon2' => $dataOrganisasi['nama_unit_eselon2'] ?? null,
                'kode_satker' => $dataOrganisasi['kode_satker'] ?? null,
                'nama_satker' => $dataOrganisasi['nama_satker'] ?? null,

                'kode_program' => $row['kode_program'],
                'nama_program' => $row['nama_program'],
                'kode_kegiatan' => $row['kode_kegiatan'],
                'nama_kegiatan' => $row['nama_kegiatan'],
                'kode_kro' => $row['kode_kro'],
                'nama_kro' => $row['nama_kro'],
                'volume_kro' => $row['volume_kro'],
                'lokasi_kro' => $row['lokasi_kro'],
                'kode_ro' => $row['kode_ro'],
                'nama_ro' => $row['nama_ro'],
                'volume_ro' => $row['volume_ro'],

                'kode_komponen' => $row['kode_komponen'],
                'nama_komponen' => $row['nama_komponen'],
                'jenis_komponen' => $row['jenis_komponen'],
                'kode_subkomponen' => $row['kode_subkomponen'],
                'nama_subkomponen' => $row['nama_subkomponen'],
                'kode_akun' => $row['kode_akun'],
                'nama_akun' => $row['nama_akun'],

                'kelompok_detail' => $row['kelompok_detail'],
                'uraian_detail' => $row['uraian_detail'],
                'volume' => $row['volume'],
                'satuan_volume' => $row['satuan_volume'],
                'harga_satuan' => $row['harga_satuan'],
                'jumlah_biaya' => $row['jumlah_biaya'],
                'sumber_dana' => $row['sumber_dana'],
                'standar_biaya' => $row['standar_biaya'],

                'created_at' => $now,
                'updated_at' => $now,
            ];

            $nextNumber++;
        }

        if (empty($insertRows)) {
            throw new \RuntimeException(
                'Hasil Gemini tidak mempunyai baris detail RKA yang memenuhi struktur tabel.'
            );
        }

        foreach (array_chunk($insertRows, 200) as $chunk) {
            DB::table('rka')->insert($chunk);
        }

        return count($insertRows);
    }

    /**
     * Normalisasi kontrak data Gemini sebelum validasi/insert.
     */
    public function normalizePayload(array $payload): array
    {
        $normalizedRows = [];

        foreach (($payload['rows'] ?? []) as $row) {
            if (!is_array($row)) {
                continue;
            }

            $normalizedRows[] = [
                'kode_program' => $this->normalizeProgramCode($row['kode_program'] ?? null),
                'nama_program' => $this->cleanString($row['nama_program'] ?? null),
                'kode_kegiatan' => $this->normalizeKegiatanCode($row['kode_kegiatan'] ?? null),
                'nama_kegiatan' => $this->cleanString($row['nama_kegiatan'] ?? null),
                'kode_kro' => $this->normalizeKroCode($row['kode_kro'] ?? null),
                'nama_kro' => $this->cleanName($row['nama_kro'] ?? null),
                'volume_kro' => $this->cleanString($row['volume_kro'] ?? null),
                'lokasi_kro' => $this->normalizeLocation($row['lokasi_kro'] ?? null),
                'kode_ro' => $this->normalizeRoCode($row['kode_ro'] ?? null),
                'nama_ro' => $this->cleanString($row['nama_ro'] ?? null),
                'volume_ro' => $this->cleanString($row['volume_ro'] ?? null),

                'kode_komponen' => $this->normalizeComponentCode($row['kode_komponen'] ?? null),
                'nama_komponen' => $this->cleanString($row['nama_komponen'] ?? null),
                'jenis_komponen' => $this->normalizeJenisKomponen($row['jenis_komponen'] ?? null),
                'kode_subkomponen' => $this->normalizeSubcomponentCode($row['kode_subkomponen'] ?? null),
                'nama_subkomponen' => $this->cleanString($row['nama_subkomponen'] ?? null),
                'kode_akun' => $this->normalizeAccountCode($row['kode_akun'] ?? null),
                'nama_akun' => $this->cleanString($row['nama_akun'] ?? null),

                'kelompok_detail' => $this->normalizeGroup($row['kelompok_detail'] ?? null),
                'uraian_detail' => $this->cleanDetail($row['uraian_detail'] ?? null),
                'volume' => $this->toNumber($row['volume'] ?? null),
                'satuan_volume' => $this->normalizeUnit($row['satuan_volume'] ?? null),
                'harga_satuan' => $this->toNumber($row['harga_satuan'] ?? null),
                'jumlah_biaya' => $this->toNumber($row['jumlah_biaya'] ?? null),
                'sumber_dana' => $this->normalizeSumberDana($row['sumber_dana'] ?? null),
                'standar_biaya' => $this->normalizeStandarBiaya($row['standar_biaya'] ?? null),
            ];
        }

        return [
            'detected_tahun_anggaran' => $this->toInteger($payload['detected_tahun_anggaran'] ?? null),
            'detected_kode_satker' => $this->cleanString($payload['detected_kode_satker'] ?? null),
            'detected_nama_satker' => $this->cleanString($payload['detected_nama_satker'] ?? null),
            'detected_alokasi_satker' => $this->toNumber($payload['detected_alokasi_satker'] ?? null),
            'rows' => $normalizedRows,
        ];
    }

    private function validateDocumentContext(
        array $payload,
        int $tahunAnggaran,
        array $dataOrganisasi
    ): void {
        $detectedYear = $payload['detected_tahun_anggaran'] ?? null;

        if ($detectedYear !== null && $detectedYear !== $tahunAnggaran) {
            throw new \RuntimeException(
                "Tahun Anggaran pada PDF ({$detectedYear}) tidak sesuai dengan pilihan ({$tahunAnggaran})."
            );
        }

        $detectedSatker = $this->normalizeCodeDigits($payload['detected_kode_satker'] ?? null);
        $selectedSatker = $this->normalizeCodeDigits($dataOrganisasi['kode_satker'] ?? null);

        if (
            $detectedSatker !== null
            && $selectedSatker !== null
            && $detectedSatker !== $selectedSatker
        ) {
            throw new \RuntimeException(
                'Kode Satker pada PDF (' . $detectedSatker
                    . ') tidak sesuai dengan Satker yang dipilih (' . $selectedSatker . ').'
            );
        }
    }

    private function validateAllocation(array $payload): void
    {
        $headerAllocation = $payload['detected_alokasi_satker'] ?? null;

        if ($headerAllocation === null || $headerAllocation <= 0) {
            return;
        }

        $detailTotal = 0.0;

        foreach (($payload['rows'] ?? []) as $row) {
            if (
                is_array($row)
                && !empty($row['uraian_detail'])
                && !empty($row['kode_akun'])
                && $row['jumlah_biaya'] !== null
            ) {
                $detailTotal += (float) $row['jumlah_biaya'];
            }
        }

        if (abs($detailTotal - $headerAllocation) > 1.0) {
            throw new \RuntimeException(
                'Validasi total RKA PDF gagal. Total detail hasil AI Rp'
                    . number_format($detailTotal, 0, ',', '.')
                    . ' tidak sama dengan ALOKASI dokumen Rp'
                    . number_format($headerAllocation, 0, ',', '.')
                    . '. Data tidak disimpan agar tidak terjadi selisih.'
            );
        }
    }

    private function buildPrompt(): string
    {
        return <<<'PROMPT'
Anda adalah parser RINCIAN KERTAS KERJA SATKER / RKA Kementerian Perdagangan RI.
Baca SELURUH PDF secara visual, termasuk PDF digital, hasil scan, tabel yang terpotong antarhalaman, halaman miring, dan header tabel yang berulang.

TUJUAN:
Keluarkan satu object untuk SETIAP DETAIL BELANJA PALING BAWAH/LEAF yang mempunyai jumlah biaya. Jangan membuat baris subtotal/hierarki sebagai detail.

KONTEKS DOKUMEN:
1. Ambil tahun dari judul "RINCIAN KERTAS KERJA SATKER T.A. ...." sebagai detected_tahun_anggaran.
2. Ambil kode UNIT KERJA/Satker dalam tanda kurung pada header sebagai detected_kode_satker.
3. Ambil nama UNIT KERJA sebagai detected_nama_satker.
4. Ambil nilai ALOKASI pada header sebagai detected_alokasi_satker.

HIERARKI RKA:
Program -> Kegiatan -> KRO -> RO -> Komponen -> Subkomponen -> Akun -> Kelompok Detail (opsional) -> Detail Belanja.

ATURAN KODE — KELUARKAN KODE ATOMIK, BUKAN COMPOSITE:
- Program "090.09.EF" => kode_program="EF".
- Kegiatan "3734" => kode_kegiatan="3734".
- KRO "3734.CCH" => kode_kro="CCH".
- RO "3734.CCH.021" => kode_ro="021".
- Komponen contoh "051", "052", "002".
- Subkomponen contoh "A", "B", "K". Jika tertulis "A TANPA SUB KOMPONEN", tetap simpan kode_subkomponen="A" dan nama_subkomponen="TANPA SUB KOMPONEN".
- Akun selalu kode belanja 6 digit seperti 521111, 522191, 524111, 532111.

ATURAN STATE/HIERARKI:
1. SATU PDF dapat berisi banyak Program, Kegiatan, KRO dan RO. Jangan menganggap satu file hanya satu RO.
2. Pertahankan hierarchy aktif saat halaman berganti. Header halaman yang berulang TIDAK mereset state.
3. Ketika menemukan Program/Kegiatan/KRO/RO/Komponen/Subkomponen/Akun baru, gunakan nilai baru itu untuk detail-detail setelahnya sampai hierarchy berubah lagi.
4. Pada SETIAP row detail, ulangi seluruh hierarchy aktif dari Program sampai Akun.
5. Abaikan KPPN, tanda tangan, NIP, tempat/tanggal penandatanganan, catatan kaki, nomor halaman, dan judul tabel yang berulang.

KRO DAN RO:
- nama_kro bersihkan penanda seperti [Base Line] dari nama.
- volume_kro simpan sebagai string sesuai yang tercetak, misalnya "2.0 Unit" atau "1.0 Layanan, Laporan, Dokumen, Rekomendasi, Unit".
- lokasi_kro ambil teks sesudah "Lokasi :" tanpa kata "Lokasi :".
- volume_ro simpan sebagai string sesuai yang tercetak, misalnya "2.0 Unit", "4000.0 produk", "1.0 Layanan".

KOMPONEN:
- jenis_komponen: "U" untuk Komponen Utama, "P" untuk Komponen Penunjang. Jika tidak terbaca, null.

SUMBER DANA DAN STANDAR BIAYA:
- sumber_dana adalah konteks akun seperti RM, PNP, RMP, PLN, BLU, HIBAH, PDN, SBSN. Ulangi pada semua detail di bawah akun tersebut.
- standar_biaya adalah penanda detail seperti SBM, SBU, SBK, SBKU.
- JANGAN memasukkan SBM/SBU/SBK ke sumber_dana.
- JANGAN memasukkan RM/PNP ke standar_biaya.

KELOMPOK DETAIL:
- Baris bertanda ">" atau ">>" adalah group/parent, bukan leaf.
- Simpan konteks group ke kelompok_detail pada setiap leaf di bawahnya.
- Jika bertingkat, gabungkan dengan " > ".
  Contoh:
  > Honorarium Pengelola Keuangan Satuan Kerja
  >> Honorarium Pengelola Sistem Akuntansi Instansi (SAI)
  maka kelompok_detail="Honorarium Pengelola Keuangan Satuan Kerja > Honorarium Pengelola Sistem Akuntansi Instansi (SAI)".
- Jangan keluarkan group sebagai row detail walaupun group mempunyai subtotal/jumlah.

DETAIL BELANJA:
- uraian_detail harus mempertahankan teks detail lengkap, termasuk ekspresi dalam [] atau () seperti "[2 ORG x 3 HR x 4 KGT]".
- volume dan satuan_volume ambil dari kolom VOLUME yang tercetak. Jangan menghitung sendiri dari ekspresi uraian jika kolom volume tidak ada.
- harga_satuan ambil dari kolom HARGA SATUAN.
- jumlah_biaya ambil dari kolom JUMLAH BIAYA.
- Angka uang dikembalikan sebagai number tanpa Rp dan tanpa pemisah ribuan.
- Nilai yang tidak tersedia/tidak terbaca = null, bukan 0.
- Nilai 0 hanya digunakan jika dokumen benar-benar menampilkan angka nol.

PENTING:
- Baris Program, Kegiatan, KRO, RO, Komponen, Subkomponen, Akun, KPPN, group, subtotal, catatan dan tanda tangan BUKAN row detail.
- Hanya keluarkan leaf/detail anggaran.
- Jangan mengarang kode, uraian, volume, harga, sumber dana, atau standar biaya yang tidak tertulis.
PROMPT;
    }

    private function responseSchema(): array
    {
        $nullableString = ['type' => ['string', 'null']];
        $nullableNumber = ['type' => ['number', 'null']];
        $nullableInteger = ['type' => ['integer', 'null']];

        $rowProperties = [
            'kode_program' => $nullableString,
            'nama_program' => $nullableString,
            'kode_kegiatan' => $nullableString,
            'nama_kegiatan' => $nullableString,
            'kode_kro' => $nullableString,
            'nama_kro' => $nullableString,
            'volume_kro' => $nullableString,
            'lokasi_kro' => $nullableString,
            'kode_ro' => $nullableString,
            'nama_ro' => $nullableString,
            'volume_ro' => $nullableString,
            'kode_komponen' => $nullableString,
            'nama_komponen' => $nullableString,
            'jenis_komponen' => $nullableString,
            'kode_subkomponen' => $nullableString,
            'nama_subkomponen' => $nullableString,
            'kode_akun' => $nullableString,
            'nama_akun' => $nullableString,
            'kelompok_detail' => $nullableString,
            'uraian_detail' => $nullableString,
            'volume' => $nullableNumber,
            'satuan_volume' => $nullableString,
            'harga_satuan' => $nullableNumber,
            'jumlah_biaya' => $nullableNumber,
            'sumber_dana' => $nullableString,
            'standar_biaya' => $nullableString,
        ];

        return [
            'type' => 'object',
            'additionalProperties' => false,
            'properties' => [
                'detected_tahun_anggaran' => $nullableInteger,
                'detected_kode_satker' => $nullableString,
                'detected_nama_satker' => $nullableString,
                'detected_alokasi_satker' => $nullableNumber,
                'rows' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'object',
                        'additionalProperties' => false,
                        'properties' => $rowProperties,
                        'required' => array_keys($rowProperties),
                    ],
                ],
            ],
            'required' => [
                'detected_tahun_anggaran',
                'detected_kode_satker',
                'detected_nama_satker',
                'detected_alokasi_satker',
                'rows',
            ],
        ];
    }

    private function extractResponseText(array $response): string
    {
        $parts = data_get($response, 'candidates.0.content.parts', []);

        if (!is_array($parts)) {
            throw new \RuntimeException('Respons Gemini RKA tidak memiliki content parts.');
        }

        $texts = [];

        foreach ($parts as $part) {
            if (is_array($part) && isset($part['text']) && is_string($part['text'])) {
                $texts[] = $part['text'];
            }
        }

        $text = trim(implode('', $texts));

        if ($text === '') {
            $finishReason = data_get($response, 'candidates.0.finishReason');

            throw new \RuntimeException(
                'Gemini tidak menghasilkan data RKA.'
                    . ($finishReason ? ' Finish reason: ' . $finishReason : '')
            );
        }

        $text = preg_replace('/^```(?:json)?\s*/i', '', $text) ?? $text;
        $text = preg_replace('/\s*```$/', '', $text) ?? $text;

        return trim($text);
    }

    private function cleanString(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim((string) $value);
        $value = preg_replace('/\s+/u', ' ', $value) ?? $value;

        return $value === '' ? null : $value;
    }

    private function cleanName(mixed $value): ?string
    {
        $value = $this->cleanString($value);

        if ($value === null) {
            return null;
        }

        $value = preg_replace('/\s*\[(?:Base\s*Line|BaseLine)\]\s*/iu', ' ', $value) ?? $value;
        $value = preg_replace('/\s+/u', ' ', $value) ?? $value;

        return trim($value) ?: null;
    }

    private function cleanDetail(mixed $value): ?string
    {
        $value = $this->cleanString($value);

        if ($value === null) {
            return null;
        }

        $value = preg_replace('/^[\-–—•]+\s*/u', '', $value) ?? $value;

        return trim($value) !== '' ? trim($value) : null;
    }

    private function normalizeLocation(mixed $value): ?string
    {
        $value = $this->cleanString($value);

        if ($value === null) {
            return null;
        }

        $value = preg_replace('/^Lokasi\s*:\s*/iu', '', $value) ?? $value;

        return trim($value) ?: null;
    }

    private function normalizeProgramCode(mixed $value): ?string
    {
        $value = strtoupper((string) ($this->cleanString($value) ?? ''));

        if ($value === '') {
            return null;
        }

        if (preg_match('/(?:^|\.)([A-Z]{2})$/', $value, $match)) {
            return $match[1];
        }

        return preg_match('/^[A-Z]{2}$/', $value) ? $value : null;
    }

    private function normalizeKegiatanCode(mixed $value): ?string
    {
        $value = (string) ($this->cleanString($value) ?? '');

        if (preg_match('/\b(\d{4})\b/', $value, $match)) {
            return $match[1];
        }

        return null;
    }

    private function normalizeKroCode(mixed $value): ?string
    {
        $value = strtoupper((string) ($this->cleanString($value) ?? ''));

        if ($value === '') {
            return null;
        }

        if (preg_match('/(?:^|\.)([A-Z]{3})$/', $value, $match)) {
            return $match[1];
        }

        return preg_match('/^[A-Z]{3}$/', $value) ? $value : null;
    }

    private function normalizeRoCode(mixed $value): ?string
    {
        $value = strtoupper((string) ($this->cleanString($value) ?? ''));

        if ($value === '') {
            return null;
        }

        if (preg_match('/(?:^|\.)([A-Z0-9]{1,4})$/', $value, $match)) {
            $value = $match[1];
        }

        if (preg_match('/^\d{1,3}$/', $value)) {
            return str_pad($value, 3, '0', STR_PAD_LEFT);
        }

        return preg_match('/^[A-Z0-9]{1,4}$/', $value) ? $value : null;
    }

    private function normalizeComponentCode(mixed $value): ?string
    {
        $value = strtoupper((string) ($this->cleanString($value) ?? ''));

        if (preg_match('/^[A-Z]?(\d{1,3})$/', $value, $match)) {
            return str_pad($match[1], 3, '0', STR_PAD_LEFT);
        }

        return null;
    }

    private function normalizeSubcomponentCode(mixed $value): ?string
    {
        $value = strtoupper((string) ($this->cleanString($value) ?? ''));

        return preg_match('/^[A-Z0-9]{1,4}$/', $value) ? $value : null;
    }

    private function normalizeAccountCode(mixed $value): ?string
    {
        $value = preg_replace('/\D/', '', (string) ($this->cleanString($value) ?? '')) ?? '';

        return preg_match('/^\d{6}$/', $value) ? $value : null;
    }

    private function normalizeJenisKomponen(mixed $value): ?string
    {
        $value = strtoupper((string) ($this->cleanString($value) ?? ''));

        if (in_array($value, ['U', 'UTAMA', 'KOMPONEN UTAMA'], true)) {
            return 'U';
        }

        if (in_array($value, ['P', 'PENUNJANG', 'KOMPONEN PENUNJANG'], true)) {
            return 'P';
        }

        return null;
    }

    private function normalizeGroup(mixed $value): ?string
    {
        $value = $this->cleanString($value);

        if ($value === null) {
            return null;
        }

        $value = preg_replace('/^>+\s*/u', '', $value) ?? $value;
        $value = preg_replace('/\s*>\s*/u', ' > ', $value) ?? $value;

        return trim($value) ?: null;
    }

    private function normalizeUnit(mixed $value): ?string
    {
        $value = strtoupper((string) ($this->cleanString($value) ?? ''));

        if ($value === '') {
            return null;
        }

        // Koreksi OCR umum: 0K/0H/0B/0T/0J seharusnya OK/OH/OB/OT/OJ.
        if (preg_match('/^0([KHBJT])$/', $value, $match)) {
            return 'O' . $match[1];
        }

        return $value;
    }

    private function normalizeSumberDana(mixed $value): ?string
    {
        $value = strtoupper((string) ($this->cleanString($value) ?? ''));

        if ($value === '') {
            return null;
        }

        if (str_contains($value, 'PNBP') || preg_match('/\bPNP\b/', $value)) {
            return 'PNP';
        }

        foreach (['RMP', 'PLN', 'BLU', 'HIBAH', 'PDN', 'SBSN', 'RM'] as $code) {
            if (preg_match('/\b' . preg_quote($code, '/') . '\b/i', $value)) {
                return $code;
            }
        }

        return null;
    }

    private function normalizeStandarBiaya(mixed $value): ?string
    {
        $value = strtoupper((string) ($this->cleanString($value) ?? ''));

        foreach (['SBKU', 'SBM', 'SBU', 'SBK'] as $code) {
            if (preg_match('/\b' . $code . '\b/', $value)) {
                return $code;
            }
        }

        return null;
    }

    private function normalizeCodeDigits(mixed $value): ?string
    {
        $value = preg_replace('/\D/', '', (string) ($this->cleanString($value) ?? '')) ?? '';
        return $value === '' ? null : $value;
    }

    private function toInteger(mixed $value): ?int
    {
        $number = $this->toNumber($value);
        return $number === null ? null : (int) round($number);
    }

    private function toNumber(mixed $value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_int($value) || is_float($value)) {
            return (float) $value;
        }

        $string = trim((string) $value);

        if ($string === '' || $string === '-') {
            return null;
        }

        $string = preg_replace('/[^0-9,.\-]/', '', $string) ?? '';

        if ($string === '' || $string === '-') {
            return null;
        }

        if (str_contains($string, ',') && str_contains($string, '.')) {
            if (strrpos($string, ',') > strrpos($string, '.')) {
                $string = str_replace('.', '', $string);
                $string = str_replace(',', '.', $string);
            } else {
                $string = str_replace(',', '', $string);
            }
        } elseif (substr_count($string, ',') > 1) {
            $string = str_replace(',', '', $string);
        } elseif (substr_count($string, '.') > 1) {
            $string = str_replace('.', '', $string);
        } elseif (str_contains($string, ',')) {
            $parts = explode(',', $string);
            $right = end($parts);
            if ($right !== false && strlen($right) <= 4) {
                $string = str_replace(',', '.', $string);
            } else {
                $string = str_replace(',', '', $string);
            }
        } elseif (substr_count($string, '.') === 1) {
            [$left, $right] = explode('.', $string, 2);
            if (strlen($right) === 3 && strlen($left) >= 1) {
                $string = $left . $right;
            }
        }

        return is_numeric($string) ? (float) $string : null;
    }

    private function geminiHttpMessage(int $status, ?string $apiMessage, string $documentType): string
    {
        return match ($status) {
            400 => 'Permintaan ke Gemini tidak valid saat memproses ' . $documentType
                . ($apiMessage ? ': ' . $apiMessage : '.'),
            401 => 'API key Gemini tidak valid atau tidak terautentikasi.',
            403 => 'Akses ke layanan Gemini ditolak untuk API key/project ini.',
            413 => 'Dokumen terlalu besar untuk diproses Gemini.',
            429 => 'Layanan Gemini sedang menerima terlalu banyak permintaan (429 Too Many Requests). Silakan tunggu beberapa saat lalu coba kembali.',
            500, 502, 503 => 'Layanan Gemini sedang tidak tersedia atau mengalami gangguan (HTTP ' . $status . '). Silakan coba kembali beberapa saat lagi.',
            504 => 'Layanan Gemini tidak merespons dalam batas waktu (504 Gateway Timeout). Silakan coba kembali.',
            default => $apiMessage
                ? 'Gemini gagal memproses ' . $documentType . ' (HTTP ' . $status . '): ' . $apiMessage
                : 'Gemini gagal memproses ' . $documentType . ' (HTTP ' . $status . ').',
        };
    }

    private function connectionExceptionStatus(ConnectionException $e): int
    {
        $message = strtolower($e->getMessage());

        return str_contains($message, 'timed out')
            || str_contains($message, 'curl error 28')
            ? 504
            : 503;
    }


}
