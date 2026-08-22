<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class RKAImport implements ToCollection, WithCalculatedFormulas
{
    protected string $documentID;
    protected array $dataOrganisasi;
    protected int $tahunAnggaran;

    public function __construct(
        string $documentID,
        array $dataOrganisasi,
        int $tahunAnggaran
    ) {
        $this->documentID = $documentID;
        $this->dataOrganisasi = $dataOrganisasi;
        $this->tahunAnggaran = $tahunAnggaran;
    }

    public function collection(Collection $rows)
    {
        // State hierarchy aktif. State ini dipertahankan sampai kode yang lebih
        // tinggi berubah, sama seperti struktur RKK SAKTI.
        $state = [
            'program' => ['kode' => null, 'nama' => null],
            'kegiatan' => ['kode' => null, 'nama' => null],
            'kro' => ['kode' => null, 'nama' => null, 'volume' => null, 'lokasi' => null],
            'ro' => ['kode' => null, 'nama' => null, 'volume' => null],
            'komponen' => ['kode' => null, 'nama' => null, 'jenis' => null],
            'subkomponen' => ['kode' => null, 'nama' => null],
            'akun' => ['kode' => null, 'nama' => null, 'sumber_dana' => null],
            'kelompok' => ['level1' => null, 'level2' => null],
        ];

        $dataToInsert = [];

        // Controller memanggil import di dalam transaction, sehingga lock ini
        // mencegah benturan rkaID ketika ada upload bersamaan.
        $lastRkaId = DB::table('rka')
            ->lockForUpdate()
            ->orderByDesc('rkaID')
            ->value('rkaID');

        $idCounter = $lastRkaId
            ? (int) substr((string) $lastRkaId, 3)
            : 0;

        foreach ($rows as $row) {
            if ($this->isEmptyRow($row)) {
                continue;
            }

            $colKode = trim((string) ($row[0] ?? ''));
            $namaHeader = $this->extractHeaderName($row);
            $teksUraian = $this->extractDescription($row);

            $colVolume = trim((string) ($row[6] ?? ''));
            $hargaSatuan = $this->parseNullableNumber($row[7] ?? null);

            // Jumlah biaya pada RKK SAKTI umumnya di J, namun pada beberapa
            // blok bergeser ke K.
            $jumlahBiaya = $this->firstNumber([
                $row[9] ?? null,
                $row[10] ?? null,
            ]);

            // SD/CP dapat bergeser antara K s.d. O pada export SAKTI.
            $sdcpText = $this->collectSdCpText($row);

            // ================================================================
            // HIERARKI
            // ================================================================

            // PROGRAM: 090.09.EF -> EF
            if (preg_match('/^[0-9]{3}\.[0-9]{2}\.([A-Z]{2})$/i', $colKode, $match)) {
                $state['program'] = [
                    'kode' => strtoupper($match[1]),
                    'nama' => $this->cleanName($namaHeader),
                ];
                $this->resetState($state, [
                    'kegiatan', 'kro', 'ro', 'komponen', 'subkomponen', 'akun', 'kelompok'
                ]);
                continue;
            }

            // KEGIATAN: 3734
            if (preg_match('/^\d{4}$/', $colKode)) {
                $state['kegiatan'] = [
                    'kode' => $colKode,
                    'nama' => $this->cleanName($namaHeader),
                ];
                $this->resetState($state, [
                    'kro', 'ro', 'komponen', 'subkomponen', 'akun', 'kelompok'
                ]);
                continue;
            }

            // KRO: 3734.CCH -> CCH
            if (preg_match('/^\d{4}\.([A-Z]{3})$/i', $colKode, $match)) {
                $state['kro'] = [
                    'kode' => strtoupper($match[1]),
                    'nama' => $this->cleanName($namaHeader),
                    'volume' => $this->nullableString($colVolume),
                    'lokasi' => null,
                ];
                $this->resetState($state, [
                    'ro', 'komponen', 'subkomponen', 'akun', 'kelompok'
                ]);
                continue;
            }

            // LOKASI KRO
            if (stripos($namaHeader, 'Lokasi :') === 0) {
                $state['kro']['lokasi'] = trim(
                    preg_replace('/^Lokasi\s*:\s*/i', '', $namaHeader) ?? $namaHeader
                );
                continue;
            }

            // RO: 3734.CCH.021 -> 021
            if (preg_match('/^\d{4}\.[A-Z]{3}\.([A-Z0-9]{1,4})$/i', $colKode, $match)) {
                $roCode = strtoupper($match[1]);
                if (preg_match('/^\d{1,3}$/', $roCode)) {
                    $roCode = str_pad($roCode, 3, '0', STR_PAD_LEFT);
                }

                $state['ro'] = [
                    'kode' => $roCode,
                    'nama' => $this->cleanName($namaHeader),
                    'volume' => $this->nullableString($colVolume),
                ];
                $this->resetState($state, [
                    'komponen', 'subkomponen', 'akun', 'kelompok'
                ]);
                continue;
            }

            // KOMPONEN: 051 / U051 -> 051
            if (preg_match('/^[a-zA-Z]?(\d{1,3})$/', $colKode, $match)) {
                $state['komponen'] = [
                    'kode' => str_pad($match[1], 3, '0', STR_PAD_LEFT),
                    'nama' => $this->cleanName($namaHeader),
                    'jenis' => $this->extractJenisKomponen($row),
                ];
                $this->resetState($state, ['subkomponen', 'akun', 'kelompok']);
                continue;
            }

            // SUBKOMPONEN: A, B, C, dst. "TANPA SUB KOMPONEN" tetap disimpan.
            if (preg_match('/^[A-Z]$/i', $colKode)) {
                $state['subkomponen'] = [
                    'kode' => strtoupper($colKode),
                    'nama' => $this->cleanName($namaHeader),
                ];
                $this->resetState($state, ['akun', 'kelompok']);
                continue;
            }

            // AKUN: 6 digit. Sumber dana diambil dari konteks SD/CP baris akun.
            if (preg_match('/^\d{6}$/', $colKode)) {
                $state['akun'] = [
                    'kode' => $colKode,
                    'nama' => $this->cleanName($namaHeader),
                    'sumber_dana' => $this->extractSumberDana($sdcpText),
                ];
                $this->resetState($state, ['kelompok']);
                continue;
            }

            // ================================================================
            // KELOMPOK DETAIL (> / >>)
            // ================================================================
            $groupLevel = $this->extractGroupLevel($row);

            if ($groupLevel !== null && $teksUraian !== '') {
                if ($groupLevel === 1) {
                    $state['kelompok']['level1'] = $teksUraian;
                    $state['kelompok']['level2'] = null;
                } else {
                    // Jika template langsung memakai >> tanpa parent >,
                    // simpan sebagai group level utama agar konteks tidak hilang.
                    if (empty($state['kelompok']['level1'])) {
                        $state['kelompok']['level1'] = $teksUraian;
                        $state['kelompok']['level2'] = null;
                    } else {
                        $state['kelompok']['level2'] = $teksUraian;
                    }
                }
                continue;
            }

            // KPPN, judul/catatan, subtotal non-leaf dan baris kosong tidak masuk DB.
            if ($colKode !== '' || $teksUraian === '') {
                continue;
            }

            $hasVolume = $colVolume !== '' && trim($colVolume) !== '-';

            // Detail leaf harus mempunyai jumlah biaya dan minimal mempunyai
            // volume atau harga satuan.
            if ($jumlahBiaya === null || (!$hasVolume && $hargaSatuan === null)) {
                continue;
            }

            [$volume, $satuanVolume] = $this->parseVolume($colVolume);

            $idCounter++;
            $newId = 'rka' . str_pad((string) $idCounter, 8, '0', STR_PAD_LEFT);

            $dataToInsert[] = [
                'rkaID' => $newId,
                'documentID' => $this->documentID,
                'tahun_anggaran' => $this->tahunAnggaran,

                'kode_unit_eselon1' => $this->dataOrganisasi['kode_unit_eselon1'] ?? null,
                'nama_unit_eselon1' => $this->dataOrganisasi['nama_unit_eselon1'] ?? null,
                'kode_unit_eselon2' => $this->dataOrganisasi['kode_unit_eselon2'] ?? null,
                'nama_unit_eselon2' => $this->dataOrganisasi['nama_unit_eselon2'] ?? null,
                'kode_satker' => $this->dataOrganisasi['kode_satker'] ?? null,
                'nama_satker' => $this->dataOrganisasi['nama_satker'] ?? null,

                'kode_program' => $state['program']['kode'],
                'nama_program' => $state['program']['nama'],
                'kode_kegiatan' => $state['kegiatan']['kode'],
                'nama_kegiatan' => $state['kegiatan']['nama'],
                'kode_kro' => $state['kro']['kode'],
                'nama_kro' => $state['kro']['nama'],
                'volume_kro' => $state['kro']['volume'],
                'lokasi_kro' => $state['kro']['lokasi'],
                'kode_ro' => $state['ro']['kode'],
                'nama_ro' => $state['ro']['nama'],
                'volume_ro' => $state['ro']['volume'],

                'kode_komponen' => $state['komponen']['kode'],
                'nama_komponen' => $state['komponen']['nama'],
                'jenis_komponen' => $state['komponen']['jenis'],
                'kode_subkomponen' => $state['subkomponen']['kode'],
                'nama_subkomponen' => $state['subkomponen']['nama'],
                'kode_akun' => $state['akun']['kode'],
                'nama_akun' => $state['akun']['nama'],

                'kelompok_detail' => $this->currentGroup($state['kelompok']),
                'uraian_detail' => $teksUraian,
                'volume' => $volume,
                'satuan_volume' => $satuanVolume,
                'harga_satuan' => $hargaSatuan,
                'jumlah_biaya' => $jumlahBiaya,
                'sumber_dana' => $state['akun']['sumber_dana'],
                'standar_biaya' => $this->extractStandarBiaya($sdcpText),

                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if (empty($dataToInsert)) {
            throw new \RuntimeException(
                'Data RKA gagal diekstrak. Pastikan file Excel merupakan Rincian Kertas Kerja Satker SAKTI.'
            );
        }

        foreach (array_chunk($dataToInsert, 500) as $chunk) {
            DB::table('rka')->insert($chunk);
        }
    }

    private function isEmptyRow(Collection|array $row): bool
    {
        for ($i = 0; $i <= 14; $i++) {
            $value = $row[$i] ?? null;
            if ($value !== null && trim((string) $value) !== '') {
                return false;
            }
        }

        return true;
    }

    private function extractHeaderName(Collection|array $row): string
    {
        foreach ([3, 4, 5] as $index) {
            $value = trim((string) ($row[$index] ?? ''));
            if ($value !== '' && !in_array($value, ['-', '>', '>>'], true)) {
                return $value;
            }
        }

        return '';
    }

    private function extractDescription(Collection|array $row): string
    {
        $parts = [];

        foreach ([3, 4, 5] as $index) {
            $value = trim((string) ($row[$index] ?? ''));

            if ($value === '' || in_array($value, ['-', '>', '>>'], true)) {
                continue;
            }

            $parts[] = $value;
        }

        $text = trim(implode(' ', $parts));
        $text = preg_replace('/^[\-–—•>]+\s*/u', '', $text) ?? $text;
        $text = preg_replace('/\s+/u', ' ', $text) ?? $text;

        return trim($text);
    }

    private function extractGroupLevel(Collection|array $row): ?int
    {
        foreach ([3, 4, 5] as $index) {
            $value = trim((string) ($row[$index] ?? ''));

            if ($value === '>') {
                return 1;
            }

            if ($value === '>>') {
                return 2;
            }
        }

        return null;
    }

    private function collectSdCpText(Collection|array $row): string
    {
        $parts = [];

        foreach ([10, 11, 12, 13, 14] as $index) {
            $value = trim((string) ($row[$index] ?? ''));
            if ($value !== '') {
                $parts[] = $value;
            }
        }

        return implode(' ', $parts);
    }

    private function extractJenisKomponen(Collection|array $row): ?string
    {
        foreach ([10, 11, 12, 13, 14] as $index) {
            $value = strtoupper(trim((string) ($row[$index] ?? '')));

            if ($value === 'U') {
                return 'U';
            }

            if ($value === 'P') {
                return 'P';
            }
        }

        return null;
    }

    private function extractSumberDana(string $text): ?string
    {
        $text = strtoupper($text);

        if (preg_match('/\bPNBP\b/', $text) || preg_match('/\bPNP\b/', $text)) {
            return 'PNP';
        }

        foreach (['RMP', 'PLN', 'BLU', 'HIBAH', 'PDN', 'SBSN', 'RM'] as $code) {
            if (preg_match('/\b' . preg_quote($code, '/') . '\b/', $text)) {
                return $code;
            }
        }

        return null;
    }

    private function extractStandarBiaya(string $text): ?string
    {
        $text = strtoupper($text);

        foreach (['SBKU', 'SBM', 'SBU', 'SBK'] as $code) {
            if (preg_match('/\b' . $code . '\b/', $text)) {
                return $code;
            }
        }

        return null;
    }

    private function currentGroup(array $group): ?string
    {
        $parts = array_values(array_filter([
            $group['level1'] ?? null,
            $group['level2'] ?? null,
        ], static fn ($value) => $value !== null && trim((string) $value) !== ''));

        return empty($parts) ? null : implode(' > ', $parts);
    }

    private function parseVolume(string $value): array
    {
        $value = trim($value);

        if ($value === '' || $value === '-') {
            return [null, null];
        }

        if (preg_match('/^(-?[\d\.,]+)\s*(.*)$/u', $value, $match)) {
            $volume = $this->parseNullableNumber($match[1]);
            $unit = trim($match[2] ?? '');
            $unit = preg_replace('/[\-_]+$/u', '', $unit) ?? $unit;
            $unit = trim($unit);

            return [
                $volume,
                $unit !== '' ? $this->normalizeUnit($unit) : null,
            ];
        }

        return [null, null];
    }

    private function normalizeUnit(string $unit): string
    {
        $unit = strtoupper(trim($unit));

        $commonOcr = [
            '0K' => 'OK',
            '0H' => 'OH',
            '0B' => 'OB',
            '0T' => 'OT',
            '0J' => 'OJ',
        ];

        return $commonOcr[$unit] ?? $unit;
    }

    private function firstNumber(array $values): ?float
    {
        foreach ($values as $value) {
            $number = $this->parseNullableNumber($value);
            if ($number !== null) {
                return $number;
            }
        }

        return null;
    }

    private function parseNullableNumber(mixed $value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_int($value) || is_float($value)) {
            return (float) $value;
        }

        $value = trim((string) $value);
        $value = preg_replace('/[^0-9,.\-]/', '', $value) ?? '';

        if ($value === '' || $value === '-') {
            return null;
        }

        // 31.000.000 atau 31.000.000,50
        if (preg_match('/^-?\d{1,3}(?:\.\d{3})+(?:,\d+)?$/', $value)) {
            $value = str_replace('.', '', $value);
            $value = str_replace(',', '.', $value);
        }
        // 31,000,000 atau 31,000,000.50
        elseif (preg_match('/^-?\d{1,3}(?:,\d{3})+(?:\.\d+)?$/', $value)) {
            $value = str_replace(',', '', $value);
        }
        // Desimal dengan koma, mis. 1,5
        elseif (substr_count($value, ',') === 1 && substr_count($value, '.') === 0) {
            $value = str_replace(',', '.', $value);
        }

        return is_numeric($value) ? (float) $value : null;
    }

    private function cleanName(string $name): ?string
    {
        $name = trim($name);
        if ($name === '') {
            return null;
        }

        // Hanya hapus penanda [Base Line], bukan seluruh teks dalam kurung siku.
        $name = preg_replace('/\s*\[(?:Base\s*Line|BaseLine)\]\s*/iu', ' ', $name) ?? $name;
        $name = preg_replace('/\s+/u', ' ', $name) ?? $name;

        return trim($name) ?: null;
    }

    private function nullableString(string $value): ?string
    {
        $value = trim($value);
        return $value === '' ? null : $value;
    }

    private function resetState(array &$state, array $keys): void
    {
        foreach ($keys as $key) {
            switch ($key) {
                case 'kro':
                    $state[$key] = ['kode' => null, 'nama' => null, 'volume' => null, 'lokasi' => null];
                    break;
                case 'ro':
                    $state[$key] = ['kode' => null, 'nama' => null, 'volume' => null];
                    break;
                case 'komponen':
                    $state[$key] = ['kode' => null, 'nama' => null, 'jenis' => null];
                    break;
                case 'akun':
                    $state[$key] = ['kode' => null, 'nama' => null, 'sumber_dana' => null];
                    break;
                case 'kelompok':
                    $state[$key] = ['level1' => null, 'level2' => null];
                    break;
                default:
                    $state[$key] = ['kode' => null, 'nama' => null];
                    break;
            }
        }
    }
}
