<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;

class PengadaanSheetImport implements ToCollection
{
    protected string $documentID;
    protected int $tahunAnggaran;

    public function __construct(string $documentID, int $tahunAnggaran)
    {
        $this->documentID = $documentID;
        $this->tahunAnggaran = $tahunAnggaran;
    }

    public function collection(Collection $rows)
    {
        if ($rows->count() < 3) {
            throw new \RuntimeException(
                'Sheet Pengadaan RKBMN tidak mempunyai struktur data yang dapat diproses.'
            );
        }

        $headerKategori = $rows[0];
        $headerMetode = $rows[1];
        $maxColumns = max(count($headerKategori), count($headerMetode));

        // Forward-fill kategori untuk mengatasi merged cell pada baris header.
        $categories = [];
        $currentCategory = '';

        for ($c = 2; $c < $maxColumns; $c++) {
            $rawCategory = trim((string) ($headerKategori[$c] ?? ''));

            if ($rawCategory !== '') {
                $currentCategory = $rawCategory;
            }

            $categories[$c] = $currentCategory;
        }

        // Tentukan kolom yang benar-benar aktif. Ini penting karena file sumber
        // memiliki kolom trailing kosong setelah Gedung Kantor; tanpa filter,
        // forward-fill kategori dapat menghasilkan baris duplikat palsu.
        $activeColumns = [];

        for ($c = 2; $c < $maxColumns; $c++) {
            $category = trim((string) ($categories[$c] ?? ''));
            $method = trim((string) ($headerMetode[$c] ?? ''));
            $rawCategory = trim((string) ($headerKategori[$c] ?? ''));

            if ($category === '' || strtolower($category) === 'jumlah') {
                continue;
            }

            $hasRealData = false;

            foreach ($rows as $rowIndex => $row) {
                if ($rowIndex < 2) {
                    continue;
                }

                $rawUnit = trim((string) ($row[0] ?? ''));

                if (strcasecmp($rawUnit, 'Jumlah') === 0) {
                    continue;
                }

                $value = $row[$c] ?? null;

                if ($value !== null && trim((string) $value) !== '') {
                    $hasRealData = true;
                    break;
                }
            }

            if ($rawCategory === '' && $method === '' && !$hasRealData) {
                continue;
            }

            $activeColumns[] = $c;
        }

        if (empty($activeColumns)) {
            throw new \RuntimeException(
                'Sheet Pengadaan RKBMN tidak mempunyai kolom kategori pengadaan yang valid.'
            );
        }

        $lastRecordId = DB::table('rkbmn_pengadaan')
            ->lockForUpdate()
            ->orderByDesc('rkbmn_pengadaanID')
            ->value('rkbmn_pengadaanID');

        $idCounter = $lastRecordId
            ? (int) substr((string) $lastRecordId, 3)
            : 0;

        $dataToInsert = [];
        $currentUnit = null;

        foreach ($rows as $index => $row) {
            if ($index < 2) {
                continue;
            }

            $rawUnit = trim((string) ($row[0] ?? ''));
            $rawSatker = trim((string) ($row[1] ?? ''));

            // Baris total akhir tidak boleh dianggap sebagai unit/satker.
            if (strcasecmp($rawUnit, 'Jumlah') === 0) {
                continue;
            }

            // Unit pada sumber dapat di-merge vertikal. Ketika cell unit kosong
            // tetapi satker terisi, gunakan unit terakhir yang terbaca.
            if ($rawUnit !== '') {
                $currentUnit = $this->splitUnit($rawUnit);
            }

            if ($rawSatker === '') {
                continue;
            }

            if ($currentUnit === null || empty($currentUnit['kode'])) {
                throw new \RuntimeException(
                    'Sheet Pengadaan RKBMN memiliki Satker tanpa konteks Unit pada baris ' . ($index + 1) . '.'
                );
            }

            $satker = $this->splitSatker($rawSatker);

            if (empty($satker['kode'])) {
                continue;
            }

            foreach ($activeColumns as $c) {
                $category = trim((string) ($categories[$c] ?? ''));
                $method = trim((string) ($headerMetode[$c] ?? ''));

                if ($method === '') {
                    $method = '-';
                }

                $jumlahVal = $row[$c] ?? 0;

                $idCounter++;
                $newId = 'pgd' . str_pad((string) $idCounter, 5, '0', STR_PAD_LEFT);

                $dataToInsert[] = [
                    'rkbmn_pengadaanID' => $newId,
                    'documentID' => $this->documentID,
                    'tahun_anggaran' => $this->tahunAnggaran,
                    'kode_unit' => $currentUnit['kode'],
                    'nama_unit' => $currentUnit['nama'],
                    'kode_satker' => $satker['kode'],
                    'nama_satker' => $satker['nama'],
                    'kategori_barang' => $category,
                    'metode_pengadaan' => $method,
                    'jumlah' => is_numeric($jumlahVal) ? $jumlahVal : 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        if (empty($dataToInsert)) {
            throw new \RuntimeException(
                'Data Pengadaan RKBMN gagal diekstrak dari file Excel.'
            );
        }

        foreach (array_chunk($dataToInsert, 500) as $chunk) {
            DB::table('rkbmn_pengadaan')->insert($chunk);
        }
    }

    private function splitUnit($value): array
    {
        $clean = trim(str_replace('[-]', '', (string) $value));
        $parts = preg_split('/\s+/', $clean, 2);

        return [
            'kode' => trim((string) ($parts[0] ?? '')),
            'nama' => trim((string) ($parts[1] ?? '')),
        ];
    }

    private function splitSatker($value): array
    {
        $parts = preg_split('/\s+/', trim((string) $value), 2);

        return [
            'kode' => trim((string) ($parts[0] ?? '')),
            'nama' => trim((string) ($parts[1] ?? '')),
        ];
    }
}
