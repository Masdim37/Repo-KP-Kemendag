<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;

class RenjaImport implements ToCollection, WithHeadingRow
{
    protected $documentID;

    // Menangkap DocumentID dari Controller
    public function __construct($documentID)
    {
        $this->documentID = $documentID;
    }

    public function collection(Collection $rows)
    {
        $dataToInsert = [];

        $counter = 1;

        foreach ($rows as $index => $row) {
            // Lewati baris jika data kementerian_nama atau program kosong (mencegah baris kosong di Excel terinput)
            if (!isset($row['program'])) continue;

            // 1. Eksekusi Pemisahan String (Kode dan Uraian)
            // Hierarki Organisasi
            $unitEselon1 = $this->splitKodeUraian($row['unit_eselon1'] ?? '');
            $unitEselon2 = $this->splitKodeUraian($row['unit_eselon2'] ?? '');
            
            // Hierarki Pekerjaan
            $program = $this->splitKodeUraian($row['program'] ?? '');
            $koordinatorProgram = $this->splitKodeUraian($row['koordinator_program'] ?? '');
            $kegiatan = $this->splitKodeUraian($row['kegiatan'] ?? '');
            $koordinatorKegiatan = $this->splitKodeUraian($row['koordinator_kegiatan'] ?? '');
            $kro = $this->splitKodeUraian($row['kro'] ?? '');
            $ro = $this->splitKodeUraian($row['ro'] ?? '');
            $komponen = $this->splitKodeUraian($row['komponen'] ?? '');

            // Tematik & Prioritas Nasional
            $tematiks = $this->splitKodeUraian($row['tematiks'] ?? '');
            $pn = $this->splitKodeUraian($row['pn'] ?? '');
            $pp = $this->splitKodeUraian($row['pp'] ?? '');
            $kp = $this->splitKodeUraian($row['kp'] ?? '');
            $proPn = $this->splitKodeUraian($row['pro_pn'] ?? '');

            // Sasaran
            $sasaranStrategis = $this->splitKodeUraian($row['sasaran_strategis'] ?? '');
            $sasaranProgram = $this->splitKodeUraian($row['sasaran_program'] ?? '');
            $sasaranKegiatan = $this->splitKodeUraian($row['sasaran_kegiatan'] ?? '');

            // Lokasi
            $lokasiRo = $this->splitKodeUraian($row['lokasi_ro'] ?? '');
            $propinsi = $this->splitKodeUraian($row['propinsi'] ?? '');
            $kabupaten = $this->splitKodeUraian($row['kabupaten'] ?? '');

            // 2. Generate renjaID berurutan (Contoh: rwrnj0000001, rwrnj0000002, dst)
            $renjaID = 'rwrnj' . str_pad($counter, 7, '0', STR_PAD_LEFT);
            $counter++; // Tambah 1 untuk baris berikutnya

            // 3. Mapping data untuk di-insert
            $dataToInsert[] = [
                'renjaID' => $renjaID,
                'documentID' => $this->documentID,
                'kementerian_nama' => $row['kementerian_nama'] ?? null,
                
                // Organisasi
                'kode_unit_eselon1' => $unitEselon1['kode'],
                'unit_eselon1' => $unitEselon1['uraian'],
                'kode_unit_eselon2' => $unitEselon2['kode'],
                'unit_eselon2' => $unitEselon2['uraian'],
                
                // Pekerjaan
                'kode_program' => $program['kode'],
                'program' => $program['uraian'],
                'kode_koordinator_program' => $koordinatorProgram['kode'],
                'koordinator_program' => $koordinatorProgram['uraian'],
                'kode_kegiatan' => $kegiatan['kode'],
                'kegiatan' => $kegiatan['uraian'],
                'kode_koordinator_kegiatan' => $koordinatorKegiatan['kode'],
                'koordinator_kegiatan' => $koordinatorKegiatan['uraian'],
                'kode_kro' => $kro['kode'],
                'kro' => $kro['uraian'],
                'kode_ro' => $ro['kode'],
                'ro' => $ro['uraian'],
                'kode_komponen' => $komponen['kode'],
                'komponen' => $komponen['uraian'],

                // Kategori & Fungsi
                'fungsi' => $row['fungsi'] ?? null,
                'subfungsi' => $row['subfungsi'] ?? null,
                'prioritas_check' => $row['prioritas_check'] ?? null,
                'janpres' => $row['janpres'] ?? null,
                'kode_tags' => $row['kode_tags'] ?? null,
                'nawacita' => $row['nawacita'] ?? null,
                'mp' => $row['mp'] ?? null,

                // Split Kategori
                'kode_tematiks' => $tematiks['kode'],
                'tematiks' => $tematiks['uraian'],
                'kode_pn' => $pn['kode'],
                'pn' => $pn['uraian'],
                'kode_pp' => $pp['kode'],
                'pp' => $pp['uraian'],
                'kode_kp' => $kp['kode'],
                'kp' => $kp['uraian'],
                'kode_pro_pn' => $proPn['kode'],
                'pro_pn' => $proPn['uraian'],

                // Split Sasaran
                'kode_sasaran_strategis' => $sasaranStrategis['kode'],
                'sasaran_strategis' => $sasaranStrategis['uraian'],
                'kode_sasaran_program' => $sasaranProgram['kode'],
                'sasaran_program' => $sasaranProgram['uraian'],
                'kode_sasaran_kegiatan' => $sasaranKegiatan['kode'],
                'sasaran_kegiatan' => $sasaranKegiatan['uraian'],

                // Split Lokasi & Geografis (Pulau tidak di-split berdasarkan struktur tabel Anda)
                'kode_lokasi_ro' => $lokasiRo['kode'],
                'lokasi_ro' => $lokasiRo['uraian'],
                'pulau' => $row['pulau'] ?? null,
                'kode_propinsi' => $propinsi['kode'],
                'propinsi' => $propinsi['uraian'],
                'kode_kabupaten' => $kabupaten['kode'],
                'kabupaten' => $kabupaten['uraian'],

                // Target (Numerik & Desimal)
                'satuan' => $row['satuan'] ?? null,
                'target_0' => is_numeric($row['target_0'] ?? null) ? $row['target_0'] : 0,
                'target_1' => is_numeric($row['target_1'] ?? null) ? $row['target_1'] : 0,
                'target_2' => is_numeric($row['target_2'] ?? null) ? $row['target_2'] : 0,
                'target_3' => is_numeric($row['target_3'] ?? null) ? $row['target_3'] : 0,
                'satuan_target' => $row['satuan_target'] ?? null,
                
                // Komponen Atribut
                'satuan_komponen' => $row['satuan_komponen'] ?? null,
                'kewenangan' => $row['kewenangan'] ?? null,
                'type_komponen' => $row['type_komponen'] ?? null,
                'jenis_komponen' => $row['jenis_komponen'] ?? null,
                'sumber_dana' => $row['sumber_dana'] ?? null,
                
                // Target Komponen
                'target_komponen_0' => is_numeric($row['target_komponen_0'] ?? null) ? $row['target_komponen_0'] : 0,
                'target_komponen_1' => is_numeric($row['target_komponen_1'] ?? null) ? $row['target_komponen_1'] : 0,
                'target_komponen_2' => is_numeric($row['target_komponen_2'] ?? null) ? $row['target_komponen_2'] : 0,
                'target_komponen_3' => is_numeric($row['target_komponen_3'] ?? null) ? $row['target_komponen_3'] : 0,

                // Alokasi Anggaran (Penting untuk default 0)
                'alokasi_komponen_0' => is_numeric($row['alokasi_komponen_0'] ?? null) ? $row['alokasi_komponen_0'] : 0,
                'alokasi_komponen_1' => is_numeric($row['alokasi_komponen_1'] ?? null) ? $row['alokasi_komponen_1'] : 0,
                'alokasi_komponen_2' => is_numeric($row['alokasi_komponen_2'] ?? null) ? $row['alokasi_komponen_2'] : 0,
                'alokasi_komponen_3' => is_numeric($row['alokasi_komponen_3'] ?? null) ? $row['alokasi_komponen_3'] : 0,

                // Atribut Tambahan
                'dijumlahkan' => $row['dijumlahkan'] ?? null,
                'multiyears' => $row['multiyears'] ?? null,
                'jns_suboutput' => $row['jns_suboutput'] ?? null,
                'rab' => $row['rab'] ?? null,
                'tor' => $row['tor'] ?? null,
                'tag_ro' => $row['tag_ro'] ?? null,
                
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insert ke database secara batch (massal) per 500 baris
        $chunks = array_chunk($dataToInsert, 500);
        foreach ($chunks as $chunk) {
            DB::table('renja')->insert($chunk);
        }
    }

    /**
     * Fungsi Helper untuk memisahkan Format "KODE - URAIAN" (atau "KODE-URAIAN")
     */
    private function splitKodeUraian($string)
    {
        if (empty(trim($string))) {
            return ['kode' => null, 'uraian' => null];
        }

        // Pisahkan string pada tanda '-' PERTAMA saja
        // Jika teks berisi "000 - Bukan Tematik", trim() akan membuang spasi berlebih
        $parts = explode('-', $string, 2);
        
        return [
            'kode' => trim($parts[0]),
            'uraian' => isset($parts[1]) ? trim($parts[1]) : null,
        ];
    }
}