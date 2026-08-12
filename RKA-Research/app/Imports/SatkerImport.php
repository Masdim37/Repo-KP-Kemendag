<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
// Hapus `WithHeadingRow` dari use statement

class SatkerImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        $dataToInsert = [];

        // Setup Pembuat ID (Menarik ID terakhir dari DB)
        $lastRecord = DB::table('satker')->orderBy('satkerID', 'desc')->first();
        $counter = $lastRecord ? (int) substr($lastRecord->satkerID, 3) : 0;

        foreach ($rows as $index => $row) {
            // 1. Lewati baris PERTAMA (Index 0) karena itu adalah teks Header
            if ($index === 0) continue;

            // 2. Baca berdasarkan urutan kolom (Kolom ke-5 adalah Kode Satker = index 4)
            // Ini sangat aman dari typo nama header di Excel
            if (!isset($row[4]) || trim($row[4]) === '') continue;

            // Generate satkerID berurutan
            $counter++; 
            $satkerID = 'stk' . str_pad($counter, 5, '0', STR_PAD_LEFT);

            // 3. Mapping data menggunakan Index Angka Array
            $dataToInsert[] = [
                'satkerID'          => $satkerID,
                'kode_unit_eselon1' => $row[0] ?? null,
                'nama_unit_eselon1' => $row[1] ?? null,
                'kode_unit_eselon2' => $row[2] ?? null,
                'nama_unit_eselon2' => $row[3] ?? null,
                'kode_satker'       => $row[4] ?? null,
                'nama_satker'       => $row[5] ?? null,
                'kode_program'      => $row[6] ?? null,
                'nama_program'      => $row[7] ?? null,
                'kode_kegiatan'     => $row[8] ?? null,
                'nama_kegiatan'     => $row[9] ?? null,
                'created_at'        => now(),
                'updated_at'        => now(),
            ];
        }

        // 4. PENGAMANAN TAMBAHAN: Jika kosong, lempar error agar tidak dianggap "Berhasil"
        if (empty($dataToInsert)) {
            throw new \Exception("Data tidak terbaca! Pastikan format kolom benar dan data berada di Sheet pertama.");
        }

        // 5. Insert ke database secara batch (massal) per 500 baris
        $chunks = array_chunk($dataToInsert, 500);
        foreach ($chunks as $chunk) {
            DB::table('satker')->insert($chunk);
        }
    }
}