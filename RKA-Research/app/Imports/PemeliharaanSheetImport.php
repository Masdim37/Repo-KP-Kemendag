<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;

class PemeliharaanSheetImport implements ToCollection
{
    protected $documentID;

    public function __construct($documentID)
    {
        $this->documentID = $documentID;
    }

    // public function collection(Collection $rows)
    // {
    //     $headers = $rows[0]; // Baris 1 sebagai nama-nama barang

    //     // 1. Setup Pembuat ID plh00001
    //     $lastRecord = DB::table('rkbmn_pemeliharaan')->orderBy('rkbmn_pemeliharaanID', 'desc')->first();
    //     $idCounter = $lastRecord ? (int) substr($lastRecord->rkbmn_pemeliharaanID, 3) : 0;

    //     $dataToInsert = [];

    //     foreach ($rows as $index => $row) {
    //         if ($index == 0) continue; // Lewati header
    //         if (empty($row[0]) || trim($row[0]) == 'Jumlah') continue; // Lewati baris kosong / total

    //         $unit = $this->splitUnit($row[0]);
    //         $satker = $this->splitSatker($row[1]);

    //         // Unpivoting ke samping
    //         for ($c = 2; $c < count($headers); $c++) {
    //             $rawHeader = trim($headers[$c] ?? '');

    //             if (empty($rawHeader)) continue; // Abaikan jika nama kolom excelnya kosong

    //             // LOGIKA CERDAS IDENTIFIKASI SATUAN (Misal: Selasar M2 atau Selasar Unit)
    //             $satuan = 'Unit'; // Default
    //             $namaBarang = $rawHeader;

    //             if (preg_match('/ M2$/i', $rawHeader)) {
    //                 $satuan = 'M2';
    //                 $namaBarang = trim(preg_replace('/ M2$/i', '', $rawHeader));
    //             } elseif (preg_match('/ Unit$/i', $rawHeader)) {
    //                 $satuan = 'Unit';
    //                 $namaBarang = trim(preg_replace('/ Unit$/i', '', $rawHeader));
    //             }

    //             $idCounter++;
    //             $newId = 'plh' . str_pad($idCounter, 5, '0', STR_PAD_LEFT); // *Tingkatkan '5' jika data > 99.999

    //             $dataToInsert[] = [
    //                 'rkbmn_pemeliharaanID' => $newId,
    //                 'documentID'           => $this->documentID,
    //                 'kode_unit'            => $unit['kode'],
    //                 'nama_unit'            => $unit['nama'],
    //                 'kode_satker'          => $satker['kode'],
    //                 'nama_satker'          => $satker['nama'],
    //                 'nama_barang'          => $namaBarang,
    //                 'satuan'               => $satuan,
    //                 'jumlah'               => is_numeric($row[$c]) ? $row[$c] : 0, // 0 Tetap Masuk
    //                 'created_at'           => now(),
    //                 'updated_at'           => now(),
    //             ];
    //         }
    //     }

    //     foreach (array_chunk($dataToInsert, 500) as $chunk) {
    //         DB::table('rkbmn_pemeliharaan')->insert($chunk);
    //     }
    // }

    public function collection(Collection $rows)
    {
        // 1. Ambil Baris 1 (Index 0) sebagai Header Utama (Nama Barang)
        $headerBarang = $rows[0];

        // 2. Ambil Baris 2 (Index 1) sebagai Sub-Header (Satuan: Unit / M2)
        $headerSatuan = $rows[1];

        // 3. Rangkai header barang untuk mengatasi 'Merge Cell' di Excel
        // Jika kolom NF kosong di baris 1 karena di-merge dari NE, maka kita isi dengan nama barang dari NE
        $namaBarangArray = [];
        $currentBarang = '';
        for ($c = 2; $c < count($headerBarang); $c++) {
            if (!empty(trim($headerBarang[$c]))) {
                $currentBarang = trim($headerBarang[$c]);
            }
            $namaBarangArray[$c] = $currentBarang;
        }

        // Setup Pembuat ID plh00001
        $lastRecord = DB::table('rkbmn_pemeliharaan')->orderBy('rkbmn_pemeliharaanID', 'desc')->first();
        $idCounter = $lastRecord ? (int) substr($lastRecord->rkbmn_pemeliharaanID, 3) : 0;

        $dataToInsert = [];

        foreach ($rows as $index => $row) {
            // PENTING: Lewati 2 baris header pertama (Index 0 dan 1) agar tidak dianggap sebagai data
            if ($index < 2) continue;

            if (empty($row[0]) || trim($row[0]) == 'Jumlah') continue; // Lewati baris kosong / total

            $unit = $this->splitUnit($row[0]);
            $satker = $this->splitSatker($row[1]);

            // Unpivoting ke samping
            for ($c = 2; $c < count($headerBarang); $c++) {
                $namaBarang = $namaBarangArray[$c] ?? '';

                // Abaikan jika tidak ada nama barang sama sekali di kolom ini
                if (empty($namaBarang)) continue;

                // 4. LOGIKA CERDAS AMBIL SATUAN DARI SUB-HEADER
                $rawSatuan = trim($headerSatuan[$c] ?? '');

                // Jika sub-header ada isinya (M2/Unit), gunakan itu. 
                // Jika kosong (karena barang biasa tidak punya sub-header), otomatis set jadi 'Unit'
                $satuan = !empty($rawSatuan) ? $rawSatuan : 'Unit';

                $idCounter++;
                $newId = 'plh' . str_pad($idCounter, 5, '0', STR_PAD_LEFT); // *Tingkatkan '5' jika data > 99.999

                $dataToInsert[] = [
                    'rkbmn_pemeliharaanID' => $newId,
                    'documentID'           => $this->documentID,
                    'kode_unit'            => $unit['kode'],
                    'nama_unit'            => $unit['nama'],
                    'kode_satker'          => $satker['kode'],
                    'nama_satker'          => $satker['nama'],
                    'nama_barang'          => $namaBarang,
                    'satuan'               => $satuan,
                    'jumlah'               => is_numeric($row[$c]) ? $row[$c] : 0, // 0 Tetap Masuk
                    'created_at'           => now(),
                    'updated_at'           => now(),
                ];
            }
        }

        // Insert ke database
        foreach (array_chunk($dataToInsert, 500) as $chunk) {
            DB::table('rkbmn_pemeliharaan')->insert($chunk);
        }
    }

    private function splitUnit($string)
    {
        $clean = trim(str_replace('[-]', '', $string));
        $parts = explode(' ', $clean, 2);
        return ['kode' => trim($parts[0] ?? ''), 'nama' => trim($parts[1] ?? '')];
    }

    private function splitSatker($string)
    {
        $parts = explode(' ', $string, 2);
        return ['kode' => trim($parts[0] ?? ''), 'nama' => trim($parts[1] ?? '')];
    }
}
