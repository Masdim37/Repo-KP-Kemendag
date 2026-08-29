<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

/**
 * Import Data Jumlah Pegawai.
 *
 * Template hanya menggunakan satu worksheet (default: Sheet1). Karena hanya ada
 * satu worksheet, importer tidak mengikat proses ke nama sheet tertentu; Maatwebsite
 * Excel akan memproses worksheet pertama. Dengan demikian template Sheet1 tetap
 * sederhana dan file lama satu-sheet juga tetap dapat dibaca.
 */
class JumlahPegawaiImport implements ToCollection, WithCalculatedFormulas
{
    private const LEVEL_E1 = 'UNIT_ESELON_I';
    private const LEVEL_E2 = 'UNIT_ESELON_II';
    private const LEVEL_SATKER = 'SATKER';

    public function __construct(private readonly string $documentID)
    {
    }

    public function collection(Collection $rows)
    {
        $this->validateHeader($rows);

        $tanggalData = $this->extractTanggalData($rows);

        $currentE1 = null;
        $currentE2 = null;
        $currentE1RunningTotal = 0;
        $grandRunningTotal = 0;
        $sourceGrandTotal = null;
        $waitingForSubtotal = false;
        $validatedSubtotalCount = 0;
        $detailRows = [];

        foreach ($rows as $index => $row) {
            $excelRow = $index + 1;

            // Baris 1-4 adalah judul, tanggal, legenda, dan header.
            if ($excelRow <= 4) {
                continue;
            }

            $noRaw = $this->cell($row, 0);
            $e1Raw = $this->text($this->cell($row, 1));
            $e2Raw = $this->text($this->cell($row, 2));
            $satkerRaw = $this->text($this->cell($row, 3));
            $jumlahRaw = $this->cell($row, 4);

            // Abaikan baris kosong di bawah data.
            if (
                $this->isBlank($noRaw)
                && $e1Raw === null
                && $e2Raw === null
                && $satkerRaw === null
                && $this->isBlank($jumlahRaw)
            ) {
                continue;
            }

            if ($this->isGrandTotalRow($satkerRaw)) {
                $sourceGrandTotal = $this->requiredInteger(
                    $jumlahRaw,
                    "TOTAL PEGAWAI pada baris {$excelRow}"
                );
                continue;
            }

            if ($this->isSubtotalRow($satkerRaw)) {
                if (!$currentE1) {
                    throw new \RuntimeException(
                        "Subtotal pada baris {$excelRow} tidak memiliki Unit Eselon I induk."
                    );
                }

                $subtotalSumber = $this->requiredInteger(
                    $jumlahRaw,
                    "subtotal {$currentE1['nama']} pada baris {$excelRow}"
                );

                if ($subtotalSumber !== $currentE1RunningTotal) {
                    throw new \RuntimeException(
                        "Total pegawai {$currentE1['nama']} tidak sesuai pada baris {$excelRow}. "
                        . "Sumber Excel = {$subtotalSumber}, hasil penjumlahan detail = {$currentE1RunningTotal}."
                    );
                }

                $validatedSubtotalCount++;
                $currentE1RunningTotal = 0;
                $waitingForSubtotal = false;
                continue;
            }

            $level = null;

            if ($e1Raw !== null) {
                if ($waitingForSubtotal) {
                    throw new \RuntimeException(
                        "Subtotal Unit Eselon I sebelumnya belum ditemukan sebelum baris {$excelRow}."
                    );
                }

                $currentE1 = $this->splitKodeNama($e1Raw);
                $currentE2 = null;
                $level = self::LEVEL_E1;
                $waitingForSubtotal = true;
            } elseif ($satkerRaw !== null) {
                if (!$currentE1) {
                    throw new \RuntimeException(
                        "Satker/UPT pada baris {$excelRow} tidak memiliki Unit Eselon I induk."
                    );
                }

                if ($e2Raw !== null) {
                    $currentE2 = $this->splitKodeNama($e2Raw);
                }

                if (!$currentE2) {
                    throw new \RuntimeException(
                        "Satker/UPT pada baris {$excelRow} tidak memiliki Unit Eselon II induk."
                    );
                }

                $level = self::LEVEL_SATKER;
            } elseif ($e2Raw !== null) {
                if (!$currentE1) {
                    throw new \RuntimeException(
                        "Unit Eselon II pada baris {$excelRow} tidak memiliki Unit Eselon I induk."
                    );
                }

                $currentE2 = $this->splitKodeNama($e2Raw);
                $level = self::LEVEL_E2;
            } else {
                // Baris selain data organisasi/subtotal/total tidak perlu disimpan.
                continue;
            }

            [$jumlahPegawai, $jumlahPegawaiRaw] = $this->parseJumlahPegawai(
                $jumlahRaw,
                $excelRow
            );

            if ($jumlahPegawai !== null) {
                $currentE1RunningTotal += $jumlahPegawai;
                $grandRunningTotal += $jumlahPegawai;
            }

            $satker = $level === self::LEVEL_SATKER
                ? $this->splitKodeNama($satkerRaw)
                : ['kode' => null, 'nama' => null];

            $detailRows[] = [
                'documentID' => $this->documentID,
                // Simpan nomor baris Excel asli agar mudah ditelusuri jika ada temuan/error.
                'urutan_sumber' => $excelRow,
                'no_sumber' => $this->nullableString($noRaw),
                'level_organisasi' => $level,

                'kode_unit_eselon1' => $currentE1['kode'],
                'nama_unit_eselon1' => $currentE1['nama'],

                'kode_unit_eselon2' => $currentE2['kode'] ?? null,
                'nama_unit_eselon2' => $currentE2['nama'] ?? null,

                'kode_satker' => $satker['kode'],
                'nama_satker' => $satker['nama'],

                'jumlah_pegawai' => $jumlahPegawai,
                'jumlah_pegawai_raw' => $jumlahPegawaiRaw,

                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if (empty($detailRows)) {
            throw new \RuntimeException('File tidak memiliki data jumlah pegawai yang dapat disimpan.');
        }

        if ($waitingForSubtotal) {
            throw new \RuntimeException(
                'Subtotal untuk Unit Eselon I terakhir tidak ditemukan pada file Excel.'
            );
        }

        if ($validatedSubtotalCount === 0) {
            throw new \RuntimeException('Baris subtotal per Unit Eselon I tidak ditemukan.');
        }

        if ($sourceGrandTotal === null) {
            throw new \RuntimeException('TOTAL PEGAWAI KEMENTERIAN PERDAGANGAN tidak ditemukan.');
        }

        if ($sourceGrandTotal !== $grandRunningTotal) {
            throw new \RuntimeException(
                "Total pegawai tidak sesuai. Sumber Excel = {$sourceGrandTotal}, "
                . "hasil penjumlahan detail = {$grandRunningTotal}."
            );
        }

        DB::table('jumlah_pegawai_snapshot')->insert([
            'documentID' => $this->documentID,
            'tanggal_data' => $tanggalData,
            'total_pegawai_sumber' => $sourceGrandTotal,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        foreach (array_chunk($detailRows, 500) as $chunk) {
            DB::table('jumlah_pegawai_detail')->insert($chunk);
        }
    }

    private function validateHeader(Collection $rows): void
    {
        $header = $rows->get(3);

        if (!$header) {
            throw new \RuntimeException('Header Data Jumlah Pegawai tidak ditemukan pada baris 4.');
        }

        $expected = [
            0 => 'No',
            1 => 'Unit Eselon I',
            2 => 'Unit Eselon II',
            3 => 'Satker / UPT',
            4 => 'Jumlah Pegawai',
        ];

        foreach ($expected as $column => $label) {
            $actual = $this->text($this->cell($header, $column));

            if ($this->normalizeLabel($actual) !== $this->normalizeLabel($label)) {
                throw new \RuntimeException(
                    "Format Excel Data Jumlah Pegawai tidak sesuai. "
                    . "Kolom {$label} harus berada pada header baris 4."
                );
            }
        }
    }

    private function extractTanggalData(Collection $rows): string
    {
        // Cari label "Per-tanggal" pada 10 baris pertama lalu baca sel tepat di bawahnya.
        $scanLimit = min(10, $rows->count());

        for ($r = 0; $r < $scanLimit; $r++) {
            $row = $rows->get($r);
            if (!$row) {
                continue;
            }

            for ($c = 0; $c < min(8, count($row)); $c++) {
                $value = $this->text($this->cell($row, $c));

                if ($value && preg_match('/^per\s*-?\s*tanggal\s*:?$/iu', $value)) {
                    $dateRow = $rows->get($r + 1);
                    $dateValue = $dateRow ? $this->cell($dateRow, $c) : null;

                    return $this->parseTanggal($dateValue);
                }
            }
        }

        // Fallback: cari pola tanggal Indonesia pada 10 baris pertama.
        for ($r = 0; $r < $scanLimit; $r++) {
            $row = $rows->get($r);
            if (!$row) {
                continue;
            }

            foreach ($row as $value) {
                if ($this->looksLikeIndonesianDate($value)) {
                    return $this->parseTanggal($value);
                }
            }
        }

        throw new \RuntimeException('Tanggal data (Per-tanggal) tidak ditemukan pada file Excel.');
    }

    private function parseTanggal($value): string
    {
        if ($value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d');
        }

        if (is_numeric($value)) {
            try {
                return ExcelDate::excelToDateTimeObject((float) $value)->format('Y-m-d');
            } catch (\Throwable $e) {
                // Lanjut ke parser teks di bawah.
            }
        }

        $text = trim((string) $value);

        if (!preg_match('/^(\d{1,2})\s+([\p{L}]+)\s+(\d{4})$/u', $text, $matches)) {
            throw new \RuntimeException("Format tanggal '{$text}' tidak dikenali.");
        }

        $months = [
            'januari' => 1,
            'februari' => 2,
            'maret' => 3,
            'april' => 4,
            'mei' => 5,
            'juni' => 6,
            'juli' => 7,
            'agustus' => 8,
            'september' => 9,
            'oktober' => 10,
            'november' => 11,
            'desember' => 12,
        ];

        $monthName = mb_strtolower($matches[2]);

        if (!isset($months[$monthName])) {
            throw new \RuntimeException("Nama bulan '{$matches[2]}' tidak dikenali.");
        }

        $day = (int) $matches[1];
        $month = $months[$monthName];
        $year = (int) $matches[3];

        if (!checkdate($month, $day, $year)) {
            throw new \RuntimeException("Tanggal '{$text}' tidak valid.");
        }

        return sprintf('%04d-%02d-%02d', $year, $month, $day);
    }

    private function looksLikeIndonesianDate($value): bool
    {
        if (!is_string($value)) {
            return false;
        }

        return (bool) preg_match('/^\d{1,2}\s+[\p{L}]+\s+\d{4}$/u', trim($value));
    }

    private function splitKodeNama(?string $value): array
    {
        $value = trim((string) $value);

        if ($value === '') {
            return ['kode' => null, 'nama' => null];
        }

        // Nomenklatur tanpa kode pada template: "- Nama Organisasi".
        if (preg_match('/^\-\s*(.+)$/u', $value, $matches)) {
            return [
                'kode' => null,
                'nama' => trim($matches[1]),
            ];
        }

        // Nomenklatur berkode: "09.20 - Direktorat ..." atau "647927 - Balai ...".
        if (preg_match('/^([A-Za-z0-9.]+)\s*\-\s*(.+)$/u', $value, $matches)) {
            return [
                'kode' => trim($matches[1]),
                'nama' => trim($matches[2]),
            ];
        }

        // Tetap simpan nama jika user menambah organisasi baru tanpa prefix "-".
        return [
            'kode' => null,
            'nama' => $value,
        ];
    }

    private function parseJumlahPegawai($value, int $excelRow): array
    {
        if ($this->isBlank($value)) {
            return [null, null];
        }

        if (is_numeric($value)) {
            $number = (float) $value;

            if ($number < 0 || floor($number) !== $number) {
                throw new \RuntimeException(
                    "Jumlah pegawai pada baris {$excelRow} harus berupa bilangan bulat >= 0."
                );
            }

            return [(int) $number, null];
        }

        $raw = trim((string) $value);

        if ($raw === '-') {
            return [null, '-'];
        }

        // Toleransi angka yang tersimpan sebagai teks dengan pemisah ribuan.
        $normalized = preg_replace('/[\s.,]/', '', $raw);

        if ($normalized !== '' && ctype_digit($normalized)) {
            return [(int) $normalized, null];
        }

        throw new \RuntimeException(
            "Nilai jumlah pegawai '{$raw}' pada baris {$excelRow} tidak valid."
        );
    }

    private function requiredInteger($value, string $context): int
    {
        [$number] = $this->parseJumlahPegawai($value, 0);

        if ($number === null) {
            throw new \RuntimeException("Nilai {$context} harus berupa angka.");
        }

        return $number;
    }

    private function isSubtotalRow(?string $satker): bool
    {
        return $satker !== null
            && str_starts_with(
                mb_strtoupper(trim($satker)),
                'TOTAL PEGAWAI '
            )
            && mb_strtoupper(trim($satker)) !== 'TOTAL PEGAWAI KEMENTERIAN PERDAGANGAN';
    }

    private function isGrandTotalRow(?string $satker): bool
    {
        return $satker !== null
            && mb_strtoupper(trim($satker)) === 'TOTAL PEGAWAI KEMENTERIAN PERDAGANGAN';
    }

    private function cell($row, int $index)
    {
        if ($row instanceof Collection) {
            return $row->get($index);
        }

        return $row[$index] ?? null;
    }

    private function text($value): ?string
    {
        if ($this->isBlank($value)) {
            return null;
        }

        return trim((string) $value);
    }

    private function nullableString($value): ?string
    {
        return $this->isBlank($value) ? null : trim((string) $value);
    }

    private function isBlank($value): bool
    {
        return $value === null || (is_string($value) && trim($value) === '');
    }

    private function normalizeLabel(?string $value): string
    {
        $value = mb_strtolower(trim((string) $value));
        return preg_replace('/\s+/', ' ', $value);
    }
}
