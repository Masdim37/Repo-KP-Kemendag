<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;

class RKAImport implements ToCollection
{
    protected $documentID;
    protected $dataOrganisasi;

    // Menangkap ID Dokumen dan Data Dropdown UI dari Controller
    public function __construct($documentID, $dataOrganisasi)
    {
        $this->documentID = $documentID;
        $this->dataOrganisasi = $dataOrganisasi;
    }

    public function collection(Collection $rows)
    {
        // State (Ingatan) untuk menyimpan hierarki data dari atas ke bawah
        $state = [
            'program'      => ['kode' => null, 'nama' => null],
            'kegiatan'     => ['kode' => null, 'nama' => null],
            'kro'          => ['kode' => null, 'nama' => null, 'volume' => null, 'lokasi' => null],
            'ro'           => ['kode' => null, 'nama' => null],
            'komponen'     => ['kode' => null, 'nama' => null],
            'subkomponen'  => ['kode' => null, 'nama' => null],
            'akun'         => ['kode' => null, 'nama' => null, 'sdcp' => null],
        ];

        $dataToInsert = [];

        // Ambil ID terakhir untuk generator RKA ID
        $lastRecord = DB::table('rka')->orderBy('rkaID', 'desc')->first();
        $idCounter = $lastRecord ? (int) substr($lastRecord->rkaID, 3) : 0;

        foreach ($rows as $index => $row) {
            // Lewati baris yang sepenuhnya kosong (biasanya ada di awal/akhir file SAKTI)
            if (!isset($row[0]) && !isset($row[3]) && !isset($row[4])) continue;

            // Mapping Index Kolom Excel SAKTI
            $colKode   = trim($row[0] ?? ''); // Kolom A: Kode Hierarki
            $colUraian = trim($row[3] ?? ''); // Kolom D: Uraian Utama
            $colDetail = trim($row[4] ?? ''); // Kolom E: Uraian Rincian Belanja
            $colVolume = trim($row[6] ?? ''); // Kolom G: Volume (Contoh: 10.0 OP)
            $colHarga  = trim($row[7] ?? ''); // Kolom H: Harga Satuan
            $colBiaya  = trim($row[9] ?? ''); // Kolom J: Jumlah Biaya
            $colSDCP   = trim($row[11] ?? ''); // Kolom L: Sumber Dana (Contoh: RM, PNP)

            // 1. Deteksi PROGRAM (Contoh Kode: 090.09.EF)
            if (preg_match('/^[0-9]{3}\.[0-9]{2}\.([A-Z]{2})$/', $colKode, $match)) {
                $state['program'] = ['kode' => $match[1], 'nama' => $this->cleanName($colUraian)];
                $this->resetState($state, ['kegiatan', 'kro', 'ro', 'komponen', 'subkomponen', 'akun']);
                continue;
            }
            // 2. Deteksi KEGIATAN (Contoh Kode: 3734)
            elseif (preg_match('/^\d{4}$/', $colKode)) {
                $state['kegiatan'] = ['kode' => $colKode, 'nama' => $this->cleanName($colUraian)];
                $this->resetState($state, ['kro', 'ro', 'komponen', 'subkomponen', 'akun']);
                continue;
            }
            // 3. Deteksi KRO (Contoh Kode: 3734.CCH)
            elseif (preg_match('/^\d{4}\.[A-Z]{3}$/', $colKode)) {
                $state['kro']['kode']   = $colKode;
                $state['kro']['nama']   = $this->cleanName($colUraian);
                $state['kro']['volume'] = $colVolume;
                $this->resetState($state, ['ro', 'komponen', 'subkomponen', 'akun']);
                continue;
            }
            // 3b. Deteksi LOKASI KRO (Biasanya tidak ada kode, tapi Uraian diawali "Lokasi :")
            elseif (strpos($colUraian, 'Lokasi :') === 0) {
                $state['kro']['lokasi'] = trim(str_replace('Lokasi :', '', $colUraian));
                continue;
            }
            // 4. Deteksi RO (Contoh Kode: 3734.CCH.021)
            elseif (preg_match('/^\d{4}\.[A-Z]{3}\.(\d{3})$/', $colKode, $match)) {
                $state['ro'] = ['kode' => $match[1], 'nama' => $this->cleanName($colUraian)];
                $this->resetState($state, ['komponen', 'subkomponen', 'akun']);
                continue;
            }
            // 5. Deteksi KOMPONEN (Contoh Kode: 051)
            elseif (preg_match('/^\d{3}$/', $colKode)) {
                $state['komponen'] = ['kode' => $colKode, 'nama' => $this->cleanName($colUraian)];
                $this->resetState($state, ['subkomponen', 'akun']);
                continue;
            }
            // 6. Deteksi SUB KOMPONEN (Contoh Kode: A)
            elseif (preg_match('/^[A-Z]$/', $colKode)) {
                $state['subkomponen'] = ['kode' => $colKode, 'nama' => $this->cleanName($colUraian)];
                $this->resetState($state, ['akun']);
                continue;
            }
            // 7. Deteksi AKUN (Contoh Kode: 522191)
            elseif (preg_match('/^\d{6}$/', $colKode)) {
                $state['akun'] = ['kode' => $colKode, 'nama' => $this->cleanName($colUraian), 'sdcp' => $colSDCP];
                continue;
            }

            // ====================================================================
            // 8. DETEKSI DETAIL BELANJA (Item Paling Bawah)
            // ====================================================================
            
            // Detail belanja biasanya kodenya kosong. Teksnya bisa ada di $colUraian atau $colDetail
            $teksUraian = !empty($colDetail) ? $colDetail : $colUraian;
            
            // Bersihkan tanda strip "-" atau panah ">" di awal kalimat
            $teksUraian = trim(preg_replace('/^[\-\>]+/', '', $teksUraian));

            $hargaSatuan = is_numeric($colHarga) ? (float) $colHarga : 0;
            $jumlahBiaya = is_numeric($colBiaya) ? (float) $colBiaya : 0;

            /* * SYARAT BARIS DISIMPAN SEBAGAI DETAIL BELANJA:
             * 1. Kolom Kode Kosong
             * 2. Ada Teks Uraiannya
             * 3. Jumlah Biayanya lebih dari 0
             * 4. Memiliki Harga Satuan ATAU Memiliki Volume (Ini mencegah Header Grup/Sub-Total ikut masuk)
             */
            if (empty($colKode) && !empty($teksUraian) && $jumlahBiaya > 0 && ($hargaSatuan > 0 || !empty($colVolume))) {
                
                $idCounter++;
                $newId = 'rka' . str_pad($idCounter, 8, '0', STR_PAD_LEFT);

                $dataToInsert[] = [
                    'rkaID'             => $newId,
                    'documentID'        => $this->documentID,
                    
                    // Data Organisasi (Dari Dropdown UI + Hasil Query DB di Controller)
                    'kode_unit_eselon1' => $this->dataOrganisasi['kode_unit_eselon1'] ?? null,
                    'nama_unit_eselon1' => $this->dataOrganisasi['nama_unit_eselon1'] ?? null,
                    'kode_unit_eselon2' => $this->dataOrganisasi['kode_unit_eselon2'] ?? null,
                    'nama_unit_eselon2' => $this->dataOrganisasi['nama_unit_eselon2'] ?? null,
                    'kode_satker'       => $this->dataOrganisasi['kode_satker'] ?? null,
                    'nama_satker'       => $this->dataOrganisasi['nama_satker'] ?? null,
                    
                    // Hierarki Kinerja / Pekerjaan (Diambil dari Ingatan / State)
                    'kode_program'      => $state['program']['kode'],
                    'nama_program'      => $state['program']['nama'],
                    'kode_kegiatan'     => $state['kegiatan']['kode'],
                    'nama_kegiatan'     => $state['kegiatan']['nama'],
                    'kode_kro'          => $state['kro']['kode'],
                    'nama_kro'          => $state['kro']['nama'],
                    'volume_kro'        => $state['kro']['volume'],
                    'lokasi_kro'        => $state['kro']['lokasi'],
                    'kode_ro'           => $state['ro']['kode'],
                    'nama_ro'           => $state['ro']['nama'],
                    'kode_komponen'     => $state['komponen']['kode'],
                    'nama_komponen'     => $state['komponen']['nama'],
                    
                    // Hierarki Keuangan
                    'kode_subkomponen'  => $state['subkomponen']['kode'],
                    'nama_subkomponen'  => $state['subkomponen']['nama'],
                    'kode_akun'         => $state['akun']['kode'],
                    'nama_akun'         => $state['akun']['nama'],
                    
                    // Detail Transaksional (Diambil dari baris ini)
                    'uraian_detail'     => $teksUraian,
                    'volume_detail'     => $colVolume,
                    'harga_satuan'      => $hargaSatuan,
                    'jumlah_biaya'      => $jumlahBiaya,
                    'sumber_dana'       => $state['akun']['sdcp'], // Sumber dana nempel di baris Akun
                    
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ];
            }
        }

        // Insert massal per 500 baris agar ringan ke Database
        if (!empty($dataToInsert)) {
            foreach (array_chunk($dataToInsert, 500) as $chunk) {
                DB::table('rka')->insert($chunk);
            }
        } else {
            throw new \Exception("Data gagal diekstrak. Pastikan file Excel yang diunggah adalah format murni Rincian Kertas Kerja SAKTI.");
        }
    }

    /**
     * Fungsi helper untuk membersihkan teks tambahan seperti "[Base Line]"
     */
    private function cleanName($name)
    {
        // Menghapus tulisan [Base Line] atau kurung siku lainnya yang mengganggu
        return trim(preg_replace('/\[.*?\]/i', '', $name));
    }

    /**
     * Fungsi helper untuk mereset variabel state yang ada di bawahnya
     * Contoh: Jika pindah Kegiatan, maka KRO dan RO lama harus dihapus
     */
    private function resetState(&$state, $keys)
    {
        foreach ($keys as $key) {
            if ($key === 'kro') {
                $state[$key] = ['kode' => null, 'nama' => null, 'volume' => null, 'lokasi' => null];
            } elseif ($key === 'akun') {
                $state[$key] = ['kode' => null, 'nama' => null, 'sdcp' => null];
            } else {
                $state[$key] = ['kode' => null, 'nama' => null];
            }
        }
    }
}