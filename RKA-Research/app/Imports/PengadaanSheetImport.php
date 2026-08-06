<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;

class PengadaanSheetImport implements ToCollection
{
    protected $documentID;

    public function __construct($documentID)
    {
        $this->documentID = $documentID;
    }

    // public function collection(Collection $rows)
    // {
    //     // 1. Ambil Baris 1 & 2 sebagai Header (Kategori dan Metode)
    //     $headerKategori = $rows[0]; 
    //     $headerMetode = $rows[1];

    //     // Rangkai header kategori yang ada kolom 'merge'-nya di excel (kosong di array php)
    //     $categories = [];
    //     $currentCat = '';
    //     for ($c = 2; $c < count($headerKategori); $c++) {
    //         if (!empty($headerKategori[$c])) {
    //             $currentCat = trim($headerKategori[$c]);
    //         }
    //         $categories[$c] = $currentCat;
    //     }

    //     // 2. Setup Pembuat ID pgd00001
    //     $lastRecord = DB::table('rkbmn_pengadaan')->orderBy('rkbmn_pengadaanID', 'desc')->first();
    //     $idCounter = $lastRecord ? (int) substr($lastRecord->rkbmn_pengadaanID, 3) : 0;

    //     $dataToInsert = [];

    //     // 3. Looping Data
    //     foreach ($rows as $index => $row) {
    //         if ($index < 2) continue; // Lewati 2 baris header awal
    //         if (empty($row[0])) continue; // Lewati baris kosong

    //         // Pisahkan Unit dan Satker
    //         $unit = $this->splitUnit($row[0]);
    //         $satker = $this->splitSatker($row[1]);

    //         // 4. Looping Kesamping (Unpivoting)
    //         for ($c = 2; $c < count($headerMetode); $c++) {
    //             $metode = trim($headerMetode[$c] ?? '');
    //             $kategori = $categories[$c] ?? '';

    //             // Lewati kolom Jumlah (karena database otomatis bisa sum nanti)
    //             if (empty($metode) || $kategori == 'Jumlah' || empty($kategori)) continue;

    //             $idCounter++;
    //             $newId = 'pgd' . str_pad($idCounter, 5, '0', STR_PAD_LEFT);

    //             $dataToInsert[] = [
    //                 'rkbmn_pengadaanID' => $newId,
    //                 'documentID'        => $this->documentID,
    //                 'kode_unit'         => $unit['kode'],
    //                 'nama_unit'         => $unit['nama'],
    //                 'kode_satker'       => $satker['kode'],
    //                 'nama_satker'       => $satker['nama'],
    //                 'kategori_barang'   => $kategori,
    //                 'metode_pengadaan'  => $metode,
    //                 'jumlah'            => is_numeric($row[$c]) ? $row[$c] : 0, // 0 Tetap Masuk
    //                 'created_at'        => now(),
    //                 'updated_at'        => now(),
    //             ];
    //         }
    //     }

    //     // Insert massal per 500 baris agar tidak berat
    //     foreach (array_chunk($dataToInsert, 500) as $chunk) {
    //         DB::table('rkbmn_pengadaan')->insert($chunk);
    //     }
    // }

    public function collection(Collection $rows)
    {
        // 1. Ambil Baris 1 & 2 sebagai Header (Kategori dan Metode)
        $headerKategori = $rows[0];
        $headerMetode = $rows[1];

        // FIX BUG 1: Gunakan kolom terpanjang antara header baris 1 dan baris 2
        // Mencegah kolom terujung (seperti Gedung Kantor) terpotong jika array baris 2 lebih pendek
        $maxColumns = max(count($headerKategori), count($headerMetode));

        // Rangkai header kategori yang ada kolom 'merge'-nya di excel (kosong di array php)
        $categories = [];
        $currentCat = '';
        for ($c = 2; $c < $maxColumns; $c++) {
            $catVal = $headerKategori[$c] ?? '';
            if (!empty(trim($catVal))) {
                $currentCat = trim($catVal);
            }
            $categories[$c] = $currentCat;
        }

        // 2. Setup Pembuat ID pgd00001
        $lastRecord = DB::table('rkbmn_pengadaan')->orderBy('rkbmn_pengadaanID', 'desc')->first();
        $idCounter = $lastRecord ? (int) substr($lastRecord->rkbmn_pengadaanID, 3) : 0;

        $dataToInsert = [];

        // 3. Looping Data
        foreach ($rows as $index => $row) {
            if ($index < 2) continue; // Lewati 2 baris header awal
            if (empty($row[0])) continue; // Lewati baris kosong

            // Pisahkan Unit dan Satker
            $unit = $this->splitUnit($row[0]);
            $satker = $this->splitSatker($row[1]);

            // 4. Looping Kesamping (Unpivoting)
            for ($c = 2; $c < $maxColumns; $c++) {
                $metode = trim($headerMetode[$c] ?? '');
                $kategori = $categories[$c] ?? '';

                // Lewati kolom Jumlah atau jika kategori kosong
                if (strtolower($kategori) === 'jumlah' || empty($kategori)) continue;

                // FIX BUG 2: Jika metode kosong (misal Gedung Kantor yg di-merge baris 1 & 2),
                // Jangan di-skip, tapi berikan nilai default agar tetap masuk database.
                if (empty($metode)) {
                    $metode = '-'; // Tanda strip untuk barang yang tidak memiliki sub-header Sewa/Beli
                }

                $idCounter++;
                $newId = 'pgd' . str_pad($idCounter, 5, '0', STR_PAD_LEFT);

                // Keamanan tambahan: Pastikan index array row ada (mencegah error offset excel)
                $jumlahVal = $row[$c] ?? 0;

                $dataToInsert[] = [
                    'rkbmn_pengadaanID' => $newId,
                    'documentID'        => $this->documentID,
                    'kode_unit'         => $unit['kode'],
                    'nama_unit'         => $unit['nama'],
                    'kode_satker'       => $satker['kode'],
                    'nama_satker'       => $satker['nama'],
                    'kategori_barang'   => $kategori,
                    'metode_pengadaan'  => $metode,
                    'jumlah'            => is_numeric($jumlahVal) ? $jumlahVal : 0, // 0 Tetap Masuk
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ];
            }
        }

        // Insert massal per 500 baris agar tidak berat
        foreach (array_chunk($dataToInsert, 500) as $chunk) {
            DB::table('rkbmn_pengadaan')->insert($chunk);
        }
    }

    private function splitUnit($string)
    {
        $clean = trim(str_replace('[-]', '', $string)); // Hilangkan [-]
        $parts = explode(' ', $clean, 2);
        return ['kode' => trim($parts[0] ?? ''), 'nama' => trim($parts[1] ?? '')];
    }

    private function splitSatker($string)
    {
        $parts = explode(' ', $string, 2);
        return ['kode' => trim($parts[0] ?? ''), 'nama' => trim($parts[1] ?? '')];
    }
}
