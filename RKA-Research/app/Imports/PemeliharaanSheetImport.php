<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;

class PemeliharaanSheetImport implements ToCollection
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
                'Sheet Pemeliharaan RKBMN tidak mempunyai struktur data yang dapat diproses.'
            );
        }

        $headerBarang = $rows[0];
        $headerSatuan = $rows[1];
        $maxColumns = max(count($headerBarang), count($headerSatuan));

        // Forward-fill nama barang untuk header yang menggunakan merged cell.
        $namaBarangArray = [];
        $currentBarang = '';

        for ($c = 2; $c < $maxColumns; $c++) {
            $rawBarang = trim((string) ($headerBarang[$c] ?? ''));

            if ($rawBarang !== '') {
                $currentBarang = $rawBarang;
            }

            $namaBarangArray[$c] = $currentBarang;
        }

        $lastRecordId = DB::table('rkbmn_pemeliharaan')
            ->lockForUpdate()
            ->orderByDesc('rkbmn_pemeliharaanID')
            ->value('rkbmn_pemeliharaanID');

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

            if (strcasecmp($rawUnit, 'Jumlah') === 0) {
                continue;
            }

            // Sama seperti sheet Pengadaan: unit dapat di-merge vertikal sehingga
            // beberapa baris satker mempunyai cell unit kosong.
            if ($rawUnit !== '') {
                $currentUnit = $this->splitUnit($rawUnit);
            }

            if ($rawSatker === '') {
                continue;
            }

            if ($currentUnit === null || empty($currentUnit['kode'])) {
                throw new \RuntimeException(
                    'Sheet Pemeliharaan RKBMN memiliki Satker tanpa konteks Unit pada baris ' . ($index + 1) . '.'
                );
            }

            $satker = $this->splitSatker($rawSatker);

            if (empty($satker['kode'])) {
                continue;
            }

            for ($c = 2; $c < $maxColumns; $c++) {
                $namaBarang = trim((string) ($namaBarangArray[$c] ?? ''));

                if ($namaBarang === '' || strcasecmp($namaBarang, 'Jumlah') === 0) {
                    continue;
                }

                $rawSatuan = trim((string) ($headerSatuan[$c] ?? ''));
                $satuan = $rawSatuan !== '' ? $rawSatuan : 'Unit';
                $jumlahVal = $row[$c] ?? 0;

                $idCounter++;
                $newId = 'plh' . str_pad((string) $idCounter, 5, '0', STR_PAD_LEFT);

                $dataToInsert[] = [
                    'rkbmn_pemeliharaanID' => $newId,
                    'documentID' => $this->documentID,
                    'tahun_anggaran' => $this->tahunAnggaran,
                    'kode_unit' => $currentUnit['kode'],
                    'nama_unit' => $currentUnit['nama'],
                    'kode_satker' => $satker['kode'],
                    'nama_satker' => $satker['nama'],
                    'nama_barang' => $namaBarang,
                    'satuan' => $satuan,
                    'jumlah' => is_numeric($jumlahVal) ? $jumlahVal : 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        if (empty($dataToInsert)) {
            throw new \RuntimeException(
                'Data Pemeliharaan RKBMN gagal diekstrak dari file Excel.'
            );
        }

        foreach (array_chunk($dataToInsert, 500) as $chunk) {
            DB::table('rkbmn_pemeliharaan')->insert($chunk);
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
