<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RABService
{
    private string $apiKey;
    private string $model;
    private string $baseUrl;

    public function __construct()
    {
        $this->apiKey = (string) env('GEMINI_API_KEY', '');
        $this->model = (string) env('GEMINI_RAB_MODEL', 'gemini-3.5-flash-lite');
        $this->baseUrl = rtrim(
            (string) env('GEMINI_API_BASE_URL', 'https://generativelanguage.googleapis.com/v1beta'),
            '/'
        );
    }

    /**
     * Ekstraksi RAB PDF menggunakan Gemini.
     *
     * Method ini HANYA melakukan ekstraksi dan normalisasi data.
     * Penyimpanan DB dilakukan terpisah melalui insertRows(), sehingga
     * request Gemini tidak menahan transaksi database.
     */
    public function extractFromPdf(string $absolutePdfPath): array
    {
        if ($this->apiKey === '') {
            throw new \RuntimeException('GEMINI_API_KEY belum dikonfigurasi.');
        }

        if (!is_file($absolutePdfPath)) {
            throw new \RuntimeException('File PDF RAB tidak ditemukan.');
        }

        $mimeType = mime_content_type($absolutePdfPath) ?: 'application/pdf';

        if ($mimeType !== 'application/pdf') {
            throw new \RuntimeException('File RAB yang dikirim ke Gemini harus berupa PDF.');
        }

        $fileSize = filesize($absolutePdfPath);

        if ($fileSize === false || $fileSize <= 0) {
            throw new \RuntimeException('File PDF RAB kosong atau tidak dapat dibaca.');
        }

        // Gemini mendukung PDF sampai 50 MB. Validasi controller juga dibatasi 50 MB.
        if ($fileSize > 50 * 1024 * 1024) {
            throw new \RuntimeException('Ukuran PDF RAB melebihi batas 50 MB.');
        }

        $pdfBytes = file_get_contents($absolutePdfPath);

        if ($pdfBytes === false) {
            throw new \RuntimeException('File PDF RAB gagal dibaca.');
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
                'responseMimeType' => 'application/json',
                'responseJsonSchema' => $this->responseSchema(),
            ],
        ];

        $url = $this->baseUrl
            . '/models/'
            . rawurlencode($this->model)
            . ':generateContent';

        $response = Http::withHeaders([
            'x-goog-api-key' => $this->apiKey,
            'Accept' => 'application/json',
        ])
            ->asJson()
            ->timeout(240)
            ->connectTimeout(30)
            ->retry(3, 1500)
            ->post($url, $payload);

        if (!$response->successful()) {
            Log::error('Gemini RAB API gagal', [
                'status' => $response->status(),
                'body' => $response->body(),
                'model' => $this->model,
            ]);

            $message = data_get($response->json(), 'error.message')
                ?: 'Gemini gagal memproses PDF RAB.';

            throw new \RuntimeException($message);
        }

        $text = $this->extractResponseText($response->json());

        try {
            $decoded = json_decode($text, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            Log::error('JSON Gemini RAB tidak valid', [
                'response_text' => $text,
                'message' => $e->getMessage(),
            ]);

            throw new \RuntimeException(
                'Gemini menghasilkan JSON RAB yang tidak valid: ' . $e->getMessage()
            );
        }

        return $this->normalizePayload($decoded);
    }

    /**
     * Menyimpan hasil ekstraksi PDF maupun hasil parser Excel ke tabel rab.
     *
     * Format $payload:
     * [
     *   'volume_ro' => ..., 'satuan_ro' => ..., 'alokasi_dana' => ...,
     *   'rows' => [[...detail...], ...]
     * ]
     *
     * Method ini harus dipanggil di dalam DB::transaction() agar pembangkitan
     * rabID dengan lockForUpdate konsisten.
     */
    public function insertRows(
        string $documentID,
        int $tahunAnggaran,
        array $dataOrganisasi,
        array $payload
    ): int {
        $payload = $this->normalizePayload($payload);
        $rows = $payload['rows'];

        if (empty($rows)) {
            throw new \RuntimeException('Tidak ada baris detail RAB yang dapat disimpan.');
        }

        $lastRabId = DB::table('rab')
            ->lockForUpdate()
            ->orderByDesc('rabID')
            ->value('rabID');

        $nextNumber = $lastRabId
            ? ((int) substr((string) $lastRabId, 3)) + 1
            : 1;

        $now = now();
        $insertRows = [];

        foreach ($rows as $row) {
            // Fokus tabel RAB adalah alokasi anggaran. Simpan hanya leaf detail
            // yang mempunyai uraian dan jumlah biaya akhir.
            if (
                empty($row['uraian_detail'])
                || $row['jumlah_biaya'] === null
            ) {
                continue;
            }

            $insertRows[] = [
                'rabID' => 'rab' . str_pad((string) $nextNumber, 8, '0', STR_PAD_LEFT),
                'documentID' => $documentID,
                'tahun_anggaran' => $tahunAnggaran,

                'kode_unit_eselon1' => $dataOrganisasi['kode_unit_eselon1'] ?? null,
                'nama_unit_eselon1' => $dataOrganisasi['nama_unit_eselon1'] ?? null,
                'kode_unit_eselon2' => $dataOrganisasi['kode_unit_eselon2'] ?? null,
                'nama_unit_eselon2' => $dataOrganisasi['nama_unit_eselon2'] ?? null,
                'kode_satker' => $dataOrganisasi['kode_satker'] ?? null,
                'nama_satker' => $dataOrganisasi['nama_satker'] ?? null,

                // Kode Program/Kegiatan/KRO/RO diambil dari dropdown/master,
                // bukan ditebak dari dokumen.
                'kode_program' => $dataOrganisasi['kode_program'] ?? null,
                'nama_program' => $dataOrganisasi['nama_program'] ?? null,
                'kode_kegiatan' => $dataOrganisasi['kode_kegiatan'] ?? null,
                'nama_kegiatan' => $dataOrganisasi['nama_kegiatan'] ?? null,
                'kode_kro' => $dataOrganisasi['kode_kro'] ?? null,
                'nama_kro' => $dataOrganisasi['nama_kro'] ?? null,
                'kode_ro' => $dataOrganisasi['kode_ro'] ?? null,
                'nama_ro' => $dataOrganisasi['nama_ro'] ?? null,

                'volume_ro' => $payload['volume_ro'],
                'satuan_ro' => $payload['satuan_ro'],
                'alokasi_dana' => $payload['alokasi_dana'],

                'kode_komponen' => $row['kode_komponen'],
                'nama_komponen' => $row['nama_komponen'],
                'jenis_komponen' => $row['jenis_komponen'],
                'jumlah_komponen' => $row['jumlah_komponen'],

                'kode_subkomponen' => $row['kode_subkomponen'],
                'nama_subkomponen' => $row['nama_subkomponen'],
                'jumlah_subkomponen' => $row['jumlah_subkomponen'],

                'kode_akun' => $row['kode_akun'],
                'nama_akun' => $row['nama_akun'],
                'jumlah_akun' => $row['jumlah_akun'],

                'kelompok_detail' => $row['kelompok_detail'],
                'uraian_detail' => $row['uraian_detail'],

                'volume_1' => $row['volume_1'],
                'satuan_1' => $row['satuan_1'],
                'volume_2' => $row['volume_2'],
                'satuan_2' => $row['satuan_2'],
                'volume_3' => $row['volume_3'],
                'satuan_3' => $row['satuan_3'],
                'volume_4' => $row['volume_4'],
                'satuan_4' => $row['satuan_4'],
                'volume_5' => $row['volume_5'],
                'satuan_5' => $row['satuan_5'],
                'volume_6' => $row['volume_6'],
                'satuan_6' => $row['satuan_6'],

                'volume_detail' => $row['volume_detail'],
                'satuan_detail' => $row['satuan_detail'],
                'harga_satuan' => $row['harga_satuan'],
                'jumlah_biaya' => $row['jumlah_biaya'],
                'sumber_dana' => $row['sumber_dana'],

                'created_at' => $now,
                'updated_at' => $now,
            ];

            $nextNumber++;
        }

        if (empty($insertRows)) {
            throw new \RuntimeException('Hasil ekstraksi RAB tidak memiliki baris detail yang valid.');
        }

        foreach (array_chunk($insertRows, 200) as $chunk) {
            DB::table('rab')->insert($chunk);
        }

        return count($insertRows);
    }

    /**
     * Normalisasi payload agar PDF dan Excel mempunyai kontrak data yang sama.
     */
    public function normalizePayload(array $payload): array
    {
        $normalizedRows = [];

        foreach (($payload['rows'] ?? []) as $row) {
            if (!is_array($row)) {
                continue;
            }

            $normalizedRows[] = [
                'kode_komponen' => $this->cleanString($row['kode_komponen'] ?? null),
                'nama_komponen' => $this->cleanString($row['nama_komponen'] ?? null),
                'jenis_komponen' => $this->normalizeJenisKomponen($row['jenis_komponen'] ?? null),
                'jumlah_komponen' => $this->toNumber($row['jumlah_komponen'] ?? null),

                'kode_subkomponen' => $this->cleanString($row['kode_subkomponen'] ?? null),
                'nama_subkomponen' => $this->cleanString($row['nama_subkomponen'] ?? null),
                'jumlah_subkomponen' => $this->toNumber($row['jumlah_subkomponen'] ?? null),

                'kode_akun' => $this->cleanString($row['kode_akun'] ?? null),
                'nama_akun' => $this->cleanString($row['nama_akun'] ?? null),
                'jumlah_akun' => $this->toNumber($row['jumlah_akun'] ?? null),

                'kelompok_detail' => $this->cleanString($row['kelompok_detail'] ?? null),
                'uraian_detail' => $this->cleanDetail($row['uraian_detail'] ?? null),

                'volume_1' => $this->toNumber($row['volume_1'] ?? null),
                'satuan_1' => $this->cleanUnit($row['satuan_1'] ?? null),
                'volume_2' => $this->toNumber($row['volume_2'] ?? null),
                'satuan_2' => $this->cleanUnit($row['satuan_2'] ?? null),
                'volume_3' => $this->toNumber($row['volume_3'] ?? null),
                'satuan_3' => $this->cleanUnit($row['satuan_3'] ?? null),
                'volume_4' => $this->toNumber($row['volume_4'] ?? null),
                'satuan_4' => $this->cleanUnit($row['satuan_4'] ?? null),
                'volume_5' => $this->toNumber($row['volume_5'] ?? null),
                'satuan_5' => $this->cleanUnit($row['satuan_5'] ?? null),
                'volume_6' => $this->toNumber($row['volume_6'] ?? null),
                'satuan_6' => $this->cleanUnit($row['satuan_6'] ?? null),

                'volume_detail' => $this->toNumber($row['volume_detail'] ?? null),
                'satuan_detail' => $this->cleanUnit($row['satuan_detail'] ?? null),
                'harga_satuan' => $this->toNumber($row['harga_satuan'] ?? null),
                'jumlah_biaya' => $this->toNumber($row['jumlah_biaya'] ?? null),
                'sumber_dana' => $this->normalizeSumberDana($row['sumber_dana'] ?? null),
            ];
        }

        return [
            'volume_ro' => $this->toNumber($payload['volume_ro'] ?? null),
            'satuan_ro' => $this->cleanUnit($payload['satuan_ro'] ?? null),
            'alokasi_dana' => $this->toNumber($payload['alokasi_dana'] ?? null),
            'rows' => $normalizedRows,
        ];
    }

    private function buildPrompt(): string
    {
        return <<<'PROMPT'
Anda adalah parser Rincian Anggaran Biaya/Belanja (RAB) Kementerian Perdagangan RI.
Baca SELURUH PDF, termasuk tabel yang terpotong antarhalaman, hasil scan, halaman landscape/rotated, dan lanjutan tabel.

TUJUAN:
Ekstrak HANYA data alokasi anggaran dan baris DETAIL BELANJA yang paling bawah/leaf. Jangan membuat data yang tidak tertulis.

ATURAN HIERARKI:
1. Komponen: umumnya kode 3 digit, contoh 002, 051, 052, 053.
2. Subkomponen: umumnya kode huruf, contoh A, B, C, K.
3. Akun: umumnya kode akun belanja 6 digit, contoh 521111, 522191, 524111, 532111.
4. Detail: rincian biaya paling bawah yang mempunyai uraian dan biasanya volume/harga/jumlah.
5. Baris Program, Kegiatan, KRO, RO, Komponen, Subkomponen, Akun, judul grup, dan subtotal BUKAN row detail dan tidak boleh dibuat sebagai row tersendiri.
6. Jika ada judul/konteks di bawah akun seperti "BSML Medan", "BANTEN", "Jawa Timur", "Kimia", "Pengiriman Artefak sebagai Provider", atau detail induk yang mempunyai anak-anak lebih rinci, simpan konteks tersebut pada kelompok_detail untuk setiap leaf di bawahnya.
7. Pada setiap row detail, ULANGI kode/nama Komponen, Subkomponen, dan Akun yang menaunginya.
8. jumlah_komponen, jumlah_subkomponen, dan jumlah_akun adalah subtotal pada hierarki tersebut jika memang tercantum. Jika tidak terbaca/tersedia, null.
9. jenis_komponen hanya "Utama" atau "Pendukung" bila tertulis; selain itu null.

ATURAN RINCIAN PERHITUNGAN:
Pisahkan ekspresi perkalian berurutan ke pasangan volume_N dan satuan_N, maksimal 6 faktor.
Contoh "2 ORG x 3 HR x 4 KGT" menjadi:
volume_1=2, satuan_1="ORG";
volume_2=3, satuan_2="HR";
volume_3=4, satuan_3="KGT".
Jangan masukkan tanda x/× sebagai satuan.
Jika hanya ada "12 BLN", isi volume_1=12 dan satuan_1="BLN".
Kolom volume_detail adalah volume/jumlah akhir yang dicetak pada kolom hasil/Jml/Volume detail. Jangan menghitung sendiri apabila angka akhirnya tidak tercantum.
Kolom satuan_detail hanya diisi jika satuan hasil akhir memang tercantum secara eksplisit. Jangan menebak.

ATURAN NILAI:
- Semua angka uang dikembalikan sebagai number tanpa Rp dan tanpa pemisah ribuan.
- Angka tidak terbaca/tidak tersedia = null, bukan 0.
- Jangan menggandakan subtotal sebagai detail.
- Jika suatu detail induk mempunyai child yang lebih rinci, hanya keluarkan child/leaf.
- sumber_dana hanya "RM" atau "PNBP" jika tertulis jelas pada item/konteks; jika tidak ada, null.
- volume_ro, satuan_ro, dan alokasi_dana ambil dari header RAB. Jika tidak ditemukan, null.
- Jangan mengembalikan Program/Kegiatan/KRO/RO organisasi; data tersebut sudah ditentukan aplikasi dari dropdown.
PROMPT;
    }

    private function responseSchema(): array
    {
        $nullableString = ['type' => ['string', 'null']];
        $nullableNumber = ['type' => ['number', 'null']];

        $rowProperties = [
            'kode_komponen' => $nullableString,
            'nama_komponen' => $nullableString,
            // Nilai dibatasi lewat prompt + normalizer agar skema tetap sederhana.
            'jenis_komponen' => $nullableString,
            'jumlah_komponen' => $nullableNumber,
            'kode_subkomponen' => $nullableString,
            'nama_subkomponen' => $nullableString,
            'jumlah_subkomponen' => $nullableNumber,
            'kode_akun' => $nullableString,
            'nama_akun' => $nullableString,
            'jumlah_akun' => $nullableNumber,
            'kelompok_detail' => $nullableString,
            'uraian_detail' => $nullableString,
            'volume_1' => $nullableNumber,
            'satuan_1' => $nullableString,
            'volume_2' => $nullableNumber,
            'satuan_2' => $nullableString,
            'volume_3' => $nullableNumber,
            'satuan_3' => $nullableString,
            'volume_4' => $nullableNumber,
            'satuan_4' => $nullableString,
            'volume_5' => $nullableNumber,
            'satuan_5' => $nullableString,
            'volume_6' => $nullableNumber,
            'satuan_6' => $nullableString,
            'volume_detail' => $nullableNumber,
            'satuan_detail' => $nullableString,
            'harga_satuan' => $nullableNumber,
            'jumlah_biaya' => $nullableNumber,
            // Nilai dibatasi lewat prompt + normalizer agar skema tetap sederhana.
            'sumber_dana' => $nullableString,
        ];

        return [
            'type' => 'object',
            'additionalProperties' => false,
            'properties' => [
                'volume_ro' => $nullableNumber,
                'satuan_ro' => $nullableString,
                'alokasi_dana' => $nullableNumber,
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
                'volume_ro',
                'satuan_ro',
                'alokasi_dana',
                'rows',
            ],
        ];
    }

    private function extractResponseText(array $response): string
    {
        $parts = data_get($response, 'candidates.0.content.parts', []);

        if (!is_array($parts)) {
            throw new \RuntimeException('Respons Gemini RAB tidak memiliki content parts.');
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
                'Gemini tidak menghasilkan data RAB.'
                    . ($finishReason ? ' Finish reason: ' . $finishReason : '')
            );
        }

        // Defensive fallback bila API masih membungkus output dalam markdown fence.
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

    private function cleanDetail(mixed $value): ?string
    {
        $value = $this->cleanString($value);

        if ($value === null) {
            return null;
        }

        $value = preg_replace('/^[-–—•]\s*/u', '', $value) ?? $value;

        return trim($value) !== '' ? trim($value) : null;
    }

    private function cleanUnit(mixed $value): ?string
    {
        $value = $this->cleanString($value);

        if ($value === null) {
            return null;
        }

        if (in_array(strtolower($value), ['x', '×', '*', '-'], true)) {
            return null;
        }

        return strtoupper($value);
    }

    private function normalizeJenisKomponen(mixed $value): ?string
    {
        $value = strtolower((string) ($this->cleanString($value) ?? ''));

        if ($value === 'utama') {
            return 'Utama';
        }

        if ($value === 'pendukung') {
            return 'Pendukung';
        }

        return null;
    }

    private function normalizeSumberDana(mixed $value): ?string
    {
        $value = strtoupper((string) ($this->cleanString($value) ?? ''));

        if (str_contains($value, 'PNBP')) {
            return 'PNBP';
        }

        if ($value === 'RM' || str_contains($value, '(RM)')) {
            return 'RM';
        }

        return null;
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

        $string = preg_replace('/[^0-9,\.\-]/', '', $string) ?? '';

        if ($string === '' || $string === '-') {
            return null;
        }

        // Format Indonesia: 1.234.567,89
        if (str_contains($string, ',') && str_contains($string, '.')) {
            if (strrpos($string, ',') > strrpos($string, '.')) {
                $string = str_replace('.', '', $string);
                $string = str_replace(',', '.', $string);
            } else {
                $string = str_replace(',', '', $string);
            }
        } elseif (str_contains($string, ',')) {
            $parts = explode(',', $string);
            $last = end($parts);

            if ($last !== false && strlen($last) <= 4) {
                $string = str_replace('.', '', $string);
                $string = str_replace(',', '.', $string);
            } else {
                $string = str_replace(',', '', $string);
            }
        } elseif (substr_count($string, '.') > 1) {
            $string = str_replace('.', '', $string);
        } elseif (substr_count($string, '.') === 1) {
            [$left, $right] = explode('.', $string, 2);

            // Untuk angka uang/volume Indonesia, satu titik dengan 3 digit kanan
            // lebih sering merupakan pemisah ribuan.
            if (strlen($right) === 3 && strlen($left) >= 1) {
                $string = $left . $right;
            }
        }

        return is_numeric($string) ? (float) $string : null;
    }
}
