<?php

namespace App\Imports;

use App\Services\RABService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class RABExcelImport implements ToCollection, WithCalculatedFormulas
{
    private string $documentID;
    private array $dataOrganisasi;
    private int $tahunAnggaran;
    private RABService $rabService;
    private bool $processedSheet = false;

    public function __construct(
        string $documentID,
        array $dataOrganisasi,
        int $tahunAnggaran,
        RABService $rabService
    ) {
        $this->documentID = $documentID;
        $this->dataOrganisasi = $dataOrganisasi;
        $this->tahunAnggaran = $tahunAnggaran;
        $this->rabService = $rabService;
    }

    /**
     * Laravel Excel memanggil collection() untuk setiap worksheet.
     * Untuk MVP, hanya worksheet RAB pertama yang valid yang diproses.
     */
    public function collection(Collection $rows): void
    {
        if ($this->processedSheet) {
            return;
        }

        $arrayRows = $rows
            ->map(function ($row) {
                if ($row instanceof Collection) {
                    return $row->values()->all();
                }

                return is_array($row) ? array_values($row) : [];
            })
            ->values()
            ->all();

        if (!$this->looksLikeRabSheet($arrayRows)) {
            return;
        }

        $this->processedSheet = true;

        $payload = $this->parseRows($arrayRows);

        $this->rabService->insertRows(
            $this->documentID,
            $this->tahunAnggaran,
            $this->dataOrganisasi,
            $payload
        );
    }

    private function parseRows(array $rows): array
    {
        $layout = $this->detectLayout($rows);

        $payload = [
            'volume_ro' => $this->findHeaderValue($rows, ['volume']),
            'satuan_ro' => $this->findHeaderValue($rows, ['satuan ukur', 'satuan']),
            'alokasi_dana' => $this->findHeaderValue($rows, ['alokasi dana']),
            'rows' => [],
        ];

        $current = [
            'kode_komponen' => null,
            'nama_komponen' => null,
            'jenis_komponen' => null,
            'jumlah_komponen' => null,

            'kode_subkomponen' => null,
            'nama_subkomponen' => null,
            'jumlah_subkomponen' => null,

            'kode_akun' => null,
            'nama_akun' => null,
            'jumlah_akun' => null,

            'kelompok_detail' => null,
            'sumber_dana' => null,
        ];

        $startedBody = false;
        $rowCount = count($rows);

        for ($i = 0; $i < $rowCount; $i++) {
            $row = $rows[$i];

            if ($this->rowIsEmpty($row)) {
                continue;
            }

            if ($this->isTableHeaderRow($row)) {
                $startedBody = true;
                continue;
            }

            if (!$startedBody) {
                continue;
            }

            $rawCode = $this->cleanString($row[$layout['kode_col']] ?? null);
            $code = $rawCode !== null
                ? strtoupper(preg_replace('/\s+/u', '', $rawCode) ?? $rawCode)
                : null;

            $description = $this->extractDescription(
                $row,
                $layout['factor_start_col']
            );

            if ($this->isAdministrativeFooter($description)) {
                break;
            }

            // Program / Kegiatan / KRO / RO pada body tidak disimpan dari Excel.
            // Identitas ini sudah berasal dari dropdown/master aplikasi.
            if ($code !== null && $this->isOutputHierarchyCode($code)) {
                continue;
            }

            $rowTotal = $this->toNumber($row[$layout['jumlah_col']] ?? null);
            $rowPrice = $this->toNumber($row[$layout['harga_col']] ?? null);
            $jenisKomponen = $this->findJenisKomponen($row);

            if ($code !== null && preg_match('/^\d{3}$/', $code)) {
                $current['kode_komponen'] = $code;
                $current['nama_komponen'] = $description;
                $current['jenis_komponen'] = $jenisKomponen;
                $current['jumlah_komponen'] = $rowTotal;

                $current['kode_subkomponen'] = null;
                $current['nama_subkomponen'] = null;
                $current['jumlah_subkomponen'] = null;
                $current['kode_akun'] = null;
                $current['nama_akun'] = null;
                $current['jumlah_akun'] = null;
                $current['kelompok_detail'] = null;
                $current['sumber_dana'] = null;
                continue;
            }

            if ($code !== null && preg_match('/^[A-Z]$/', $code)) {
                $current['kode_subkomponen'] = $code;
                $current['nama_subkomponen'] = $description;
                $current['jumlah_subkomponen'] = $rowTotal;

                if ($jenisKomponen !== null && $current['jenis_komponen'] === null) {
                    $current['jenis_komponen'] = $jenisKomponen;
                }

                $current['kode_akun'] = null;
                $current['nama_akun'] = null;
                $current['jumlah_akun'] = null;
                $current['kelompok_detail'] = null;
                $current['sumber_dana'] = null;
                continue;
            }

            if ($code !== null && preg_match('/^\d{6}$/', $code)) {
                $current['kode_akun'] = $code;
                $current['nama_akun'] = $description;
                $current['jumlah_akun'] = $rowTotal;
                $current['kelompok_detail'] = null;
                $current['sumber_dana'] = null;
                continue;
            }

            // Detail baru boleh dibaca setelah akun teridentifikasi.
            if ($current['kode_akun'] === null || $description === null) {
                continue;
            }

            // Jika subtotal akun secara eksplisit 0, baris di bawahnya dianggap
            // referensi/alternatif dan bukan alokasi yang disimpan pada MVP.
            if (
                $current['jumlah_akun'] !== null
                && abs((float) $current['jumlah_akun']) < 0.000001
            ) {
                continue;
            }

            $sourceFromDescription = $this->extractSumberDana($description);

            // Baris tanpa nilai biaya diperlakukan sebagai konteks/grup detail.
            if ($rowTotal === null && $rowPrice === null) {
                if (!$this->looksLikeNoise($description)) {
                    $current['kelompok_detail'] = $this->cleanDetail($description);

                    if ($sourceFromDescription !== null) {
                        $current['sumber_dana'] = $sourceFromDescription;
                    }
                }

                continue;
            }

            // Baris yang mempunyai subtotal/jumlah tetapi tidak mempunyai harga
            // satuan umumnya adalah grup/subtotal, bukan leaf detail.
            if ($rowTotal !== null && $rowPrice === null) {
                $current['kelompok_detail'] = $this->cleanDetail($description);

                if ($sourceFromDescription !== null) {
                    $current['sumber_dana'] = $sourceFromDescription;
                }

                continue;
            }

            // Baris yang hanya mempunyai harga tanpa jumlah akhir tidak disimpan
            // sebagai alokasi detail pada MVP.
            if ($rowTotal === null) {
                continue;
            }

            // Beberapa template memakai detail induk sebelum child/leaf.
            // Contoh: kode pendek 18/20, atau baris berjumlah yang langsung
            // diikuti heading kelompok seperti "Kimia" lalu rincian item.
            $isDetailParent = (
                $code !== null
                && preg_match('/^\d{1,2}$/', $code)
                && $rowTotal !== null
                && $this->childTotalsMatchParent($rows, $i, $layout, $rowTotal)
            ) || (
                $code === null
                && $this->nextMeaningfulRowIsGroupHeading($rows, $i, $layout)
            );

            if ($isDetailParent) {
                $current['kelompok_detail'] = $this->cleanDetail($description);

                if ($sourceFromDescription !== null) {
                    $current['sumber_dana'] = $sourceFromDescription;
                }

                continue;
            }

            $factors = $this->extractFactors(
                $row,
                $layout['factor_start_col'],
                $layout['volume_detail_col'] - 1
            );

            $detail = [
                'kode_komponen' => $current['kode_komponen'],
                'nama_komponen' => $current['nama_komponen'],
                'jenis_komponen' => $current['jenis_komponen'],
                'jumlah_komponen' => $current['jumlah_komponen'],

                'kode_subkomponen' => $current['kode_subkomponen'],
                'nama_subkomponen' => $current['nama_subkomponen'],
                'jumlah_subkomponen' => $current['jumlah_subkomponen'],

                'kode_akun' => $current['kode_akun'],
                'nama_akun' => $current['nama_akun'],
                'jumlah_akun' => $current['jumlah_akun'],

                'kelompok_detail' => $current['kelompok_detail'],
                'uraian_detail' => $this->cleanDetail($description),

                'volume_1' => $factors[0]['volume'] ?? null,
                'satuan_1' => $factors[0]['satuan'] ?? null,
                'volume_2' => $factors[1]['volume'] ?? null,
                'satuan_2' => $factors[1]['satuan'] ?? null,
                'volume_3' => $factors[2]['volume'] ?? null,
                'satuan_3' => $factors[2]['satuan'] ?? null,
                'volume_4' => $factors[3]['volume'] ?? null,
                'satuan_4' => $factors[3]['satuan'] ?? null,
                'volume_5' => $factors[4]['volume'] ?? null,
                'satuan_5' => $factors[4]['satuan'] ?? null,
                'volume_6' => $factors[5]['volume'] ?? null,
                'satuan_6' => $factors[5]['satuan'] ?? null,

                'volume_detail' => $this->toNumber(
                    $row[$layout['volume_detail_col']] ?? null
                ),
                // Pada contoh Excel yang dianalisis, satuan hasil akhir tidak
                // mempunyai kolom tersendiri. Jangan ditebak dari faktor.
                'satuan_detail' => null,
                'harga_satuan' => $rowPrice,
                'jumlah_biaya' => $rowTotal,
                'sumber_dana' => $sourceFromDescription
                    ?? $current['sumber_dana'],
            ];

            $payload['rows'][] = $detail;
        }

        if (empty($payload['rows'])) {
            throw new \RuntimeException(
                'Worksheet RAB ditemukan, tetapi parser Excel tidak menemukan detail anggaran.'
            );
        }

        return $payload;
    }

    private function detectLayout(array $rows): array
    {
        $kodeCol = 0;
        $factorStartCol = 5;
        $hargaCol = null;
        $jumlahCol = null;

        foreach (array_slice($rows, 0, 40) as $row) {
            foreach ($row as $index => $value) {
                $text = strtolower(trim((string) ($value ?? '')));

                if ($text === 'kode') {
                    $kodeCol = (int) $index;
                }

                if (str_contains($text, 'rincian perhitungan')) {
                    $factorStartCol = (int) $index;
                }

                if (str_contains($text, 'harga')) {
                    $hargaCol = (int) $index;
                }

                if (str_contains($text, 'jumlah')) {
                    $jumlahCol = max((int) $index, $jumlahCol ?? 0);
                }
            }
        }

        if ($jumlahCol === null) {
            $maxCols = 0;
            foreach ($rows as $row) {
                $maxCols = max($maxCols, count($row));
            }
            $jumlahCol = max(2, $maxCols - 1);
        }

        if ($hargaCol === null) {
            $hargaCol = max(1, $jumlahCol - 1);
        }

        $volumeDetailCol = max($factorStartCol, $hargaCol - 1);

        return [
            'kode_col' => $kodeCol,
            'factor_start_col' => $factorStartCol,
            'volume_detail_col' => $volumeDetailCol,
            'harga_col' => $hargaCol,
            'jumlah_col' => $jumlahCol,
        ];
    }

    private function looksLikeRabSheet(array $rows): bool
    {
        $hasTitle = false;
        $hasKode = false;
        $hasJumlah = false;

        foreach (array_slice($rows, 0, 40) as $row) {
            foreach ($row as $value) {
                $text = strtoupper(trim((string) ($value ?? '')));

                if (str_contains($text, 'RINCIAN ANGGARAN')) {
                    $hasTitle = true;
                }

                if ($text === 'KODE') {
                    $hasKode = true;
                }

                if (str_contains($text, 'JUMLAH')) {
                    $hasJumlah = true;
                }
            }
        }

        return $hasTitle && $hasKode && $hasJumlah;
    }

    private function isTableHeaderRow(array $row): bool
    {
        foreach ($row as $value) {
            if (strtolower(trim((string) ($value ?? ''))) === 'kode') {
                return true;
            }
        }

        return false;
    }

    private function isOutputHierarchyCode(string $code): bool
    {
        return (bool) (
            preg_match('/^\d{3}(?:\.\d{2})?\.[A-Z]{2}$/', $code)
            || preg_match('/^\d{4}$/', $code)
            || preg_match('/^\d{4}\.[A-Z]{3}$/', $code)
            || preg_match('/^\d{4}\.[A-Z]{3}\.[A-Z0-9]{2,4}$/', $code)
        );
    }

    private function extractDescription(array $row, int $factorStartCol): ?string
    {
        $candidates = [];
        $lastDescriptionCol = max(1, $factorStartCol - 1);

        for ($i = 1; $i <= $lastDescriptionCol; $i++) {
            $value = $this->cleanString($row[$i] ?? null);

            if ($value === null) {
                continue;
            }

            if (in_array(strtolower($value), ['-', ':', 'utama', 'pendukung'], true)) {
                continue;
            }

            if (is_numeric(str_replace(['.', ','], '', $value))) {
                continue;
            }

            $candidates[] = $value;
        }

        if (empty($candidates)) {
            return null;
        }

        usort($candidates, static fn (string $a, string $b) => mb_strlen($b) <=> mb_strlen($a));

        return $candidates[0];
    }

    private function findHeaderValue(array $rows, array $labels): mixed
    {
        foreach (array_slice($rows, 0, 35) as $row) {
            foreach ($row as $index => $value) {
                $text = strtolower(trim((string) ($value ?? '')));

                foreach ($labels as $label) {
                    if ($text !== strtolower($label)) {
                        continue;
                    }

                    for ($i = $index + 1, $count = count($row); $i < $count; $i++) {
                        $candidate = $row[$i] ?? null;

                        if ($candidate === null || trim((string) $candidate) === '' || trim((string) $candidate) === ':') {
                            continue;
                        }

                        return $candidate;
                    }
                }
            }
        }

        return null;
    }

    private function findJenisKomponen(array $row): ?string
    {
        foreach ($row as $value) {
            $text = strtolower(trim((string) ($value ?? '')));

            if ($text === 'utama') {
                return 'Utama';
            }

            if ($text === 'pendukung') {
                return 'Pendukung';
            }
        }

        return null;
    }

    private function extractFactors(array $row, int $startCol, int $endCol): array
    {
        if ($endCol < $startCol) {
            return [];
        }

        $pieces = [];

        for ($i = $startCol; $i <= $endCol; $i++) {
            $value = $row[$i] ?? null;

            if ($value === null || trim((string) $value) === '') {
                continue;
            }

            $pieces[] = trim((string) $value);
        }

        $text = implode(' ', $pieces);
        $text = str_replace(['×', '*'], ' x ', $text);

        preg_match_all(
            '/(-?\d+(?:[\.,]\d+)?)\s*([A-Za-z][A-Za-z0-9\.\/-]*)/u',
            $text,
            $matches,
            PREG_SET_ORDER
        );

        $factors = [];

        foreach ($matches as $match) {
            $unit = strtoupper(trim($match[2]));

            if (in_array(strtolower($unit), ['x'], true)) {
                continue;
            }

            $number = $this->toNumber($match[1]);

            if ($number === null) {
                continue;
            }

            $factors[] = [
                'volume' => $number,
                'satuan' => $unit,
            ];

            if (count($factors) >= 6) {
                break;
            }
        }

        return $factors;
    }

    private function childTotalsMatchParent(
        array $rows,
        int $currentIndex,
        array $layout,
        float $parentTotal
    ): bool {
        $limit = min(count($rows) - 1, $currentIndex + 80);
        $childTotal = 0.0;
        $childCount = 0;

        for ($i = $currentIndex + 1; $i <= $limit; $i++) {
            $row = $rows[$i];

            if ($this->rowIsEmpty($row)) {
                continue;
            }

            $rawCode = $this->cleanString($row[$layout['kode_col']] ?? null);
            $code = $rawCode !== null
                ? strtoupper(preg_replace('/\s+/u', '', $rawCode) ?? $rawCode)
                : null;

            if (
                $code !== null
                && (
                    preg_match('/^\d{3}$/', $code)
                    || preg_match('/^[A-Z]$/', $code)
                    || preg_match('/^\d{6}$/', $code)
                    || $this->isOutputHierarchyCode($code)
                )
            ) {
                break;
            }

            $description = $this->extractDescription($row, $layout['factor_start_col']);

            if ($this->isAdministrativeFooter($description)) {
                break;
            }

            $total = $this->toNumber($row[$layout['jumlah_col']] ?? null);
            $price = $this->toNumber($row[$layout['harga_col']] ?? null);

            if (
                $code === null
                && $description !== null
                && ($total !== null || $price !== null)
                && $total !== null
            ) {
                $childTotal += $total;
                $childCount++;
            }
        }

        if ($childCount === 0) {
            return false;
        }

        $tolerance = max(1.0, abs($parentTotal) * 0.0001);

        return abs($childTotal - $parentTotal) <= $tolerance;
    }

    private function nextMeaningfulRowIsGroupHeading(
        array $rows,
        int $currentIndex,
        array $layout
    ): bool {
        $limit = min(count($rows) - 1, $currentIndex + 12);
        $headingFound = false;

        for ($i = $currentIndex + 1; $i <= $limit; $i++) {
            $row = $rows[$i];

            if ($this->rowIsEmpty($row)) {
                continue;
            }

            $rawCode = $this->cleanString($row[$layout['kode_col']] ?? null);
            $code = $rawCode !== null
                ? strtoupper(preg_replace('/\s+/u', '', $rawCode) ?? $rawCode)
                : null;

            if (
                $code !== null
                && (
                    preg_match('/^\d{3}$/', $code)
                    || preg_match('/^[A-Z]$/', $code)
                    || preg_match('/^\d{6}$/', $code)
                    || $this->isOutputHierarchyCode($code)
                )
            ) {
                return false;
            }

            $description = $this->extractDescription($row, $layout['factor_start_col']);

            if ($this->isAdministrativeFooter($description)) {
                return false;
            }

            $total = $this->toNumber($row[$layout['jumlah_col']] ?? null);
            $price = $this->toNumber($row[$layout['harga_col']] ?? null);

            if (!$headingFound) {
                // Tepat sesudah parent harus berupa heading murni.
                if (
                    $code === null
                    && $description !== null
                    && $total === null
                    && $price === null
                    && !$this->looksLikeNoise($description)
                ) {
                    $headingFound = true;
                    continue;
                }

                return false;
            }

            // Tepat sesudah heading harus ada child yang mempunyai jumlah akhir.
            return $code === null
                && $description !== null
                && $total !== null;
        }

        return false;
    }

    private function extractSumberDana(?string $text): ?string
    {
        if ($text === null) {
            return null;
        }

        $upper = strtoupper($text);

        if (str_contains($upper, 'PNBP')) {
            return 'PNBP';
        }

        if (preg_match('/\(\s*RM\s*\)/i', $text) || preg_match('/\bRM\b/i', $text)) {
            return 'RM';
        }

        return null;
    }

    private function isAdministrativeFooter(?string $description): bool
    {
        if ($description === null) {
            return false;
        }

        $text = strtolower($description);

        return str_contains($text, 'penanggung jawab kegiatan')
            || str_contains($text, 'kuasa pengguna anggaran')
            || preg_match('/^nip\.?\s/i', $description) === 1;
    }

    private function looksLikeNoise(string $description): bool
    {
        $text = strtolower(trim($description));

        return $text === 'kode'
            || str_contains($text, 'rincian perhitungan')
            || str_contains($text, 'harga satuan')
            || str_contains($text, 'jumlah (rp)')
            || str_contains($text, 'sub output');
    }

    private function rowIsEmpty(array $row): bool
    {
        foreach ($row as $value) {
            if ($value !== null && trim((string) $value) !== '') {
                return false;
            }
        }

        return true;
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

    private function cleanDetail(?string $value): ?string
    {
        $value = $this->cleanString($value);

        if ($value === null) {
            return null;
        }

        $value = preg_replace('/^[-–—•]\s*/u', '', $value) ?? $value;
        $value = trim($value);

        return $value === '' ? null : $value;
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

        // Jika cell berisi misalnya "5 Unit", ambil angka pertamanya.
        if (preg_match('/-?\d+(?:[\.,]\d+)?/', $string, $match)) {
            $string = $match[0];
        } else {
            return null;
        }

        if (str_contains($string, ',') && str_contains($string, '.')) {
            if (strrpos($string, ',') > strrpos($string, '.')) {
                $string = str_replace('.', '', $string);
                $string = str_replace(',', '.', $string);
            } else {
                $string = str_replace(',', '', $string);
            }
        } elseif (str_contains($string, ',')) {
            $string = str_replace(',', '.', $string);
        } elseif (substr_count($string, '.') > 1) {
            $string = str_replace('.', '', $string);
        } elseif (substr_count($string, '.') === 1) {
            [$left, $right] = explode('.', $string, 2);
            if (strlen($right) === 3) {
                $string = $left . $right;
            }
        }

        return is_numeric($string) ? (float) $string : null;
    }
}
