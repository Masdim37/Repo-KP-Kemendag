<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;

class RKAImport implements ToCollection
{
    protected $documentID;
    protected $dataOrganisasi;

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
            
            // 1. CEK BARIS KOSONG (REVISI: Pengecekan Aman)
            // Memastikan baris dilewati HANYA JIKA kolom A sampai L benar-benar kosong semua
            $isEmpty = true;
            for ($i = 0; $i <= 11; $i++) {
                if (isset($row[$i]) && trim($row[$i]) !== '') {
                    $isEmpty = false;
                    break;
                }
            }
            if ($isEmpty) continue;

            // 2. MAPPING INDEX KOLOM EXCEL SAKTI
            $colKode = trim($row[0] ?? ''); // Kolom A: Kode Hierarki
            
            // Mengambil Header Uraian (Bisa di Kolom D atau bergeser ke Kolom E)
            $namaHeader = trim($row[3] ?? '');
            if (empty($namaHeader) || $namaHeader === '-') {
                $namaHeader = trim($row[4] ?? '');
            }

            // REVISI: Sapu teks rincian belanja dari Kolom D (3), E (4), dan F (5)
            $teksUraian = '';
            foreach ([3, 4, 5] as $idx) {
                $val = trim($row[$idx] ?? '');
                if (!empty($val) && $val !== '-') {
                    $teksUraian .= $val . ' ';
                }
            }
            // Bersihkan tanda strip (-) atau panah (>) di awal teks
            $teksUraian = trim(preg_replace('/^[\-\>]+/', '', trim($teksUraian)));

            $colVolume = trim((string) ($row[6] ?? '')); // Kolom G: Volume
            $colHarga  = $row[7] ?? '';                          // Kolom H: Harga Satuan

            // Jumlah Biaya pada export SAKTI umumnya berada di kolom J,
            // tetapi pada beberapa blok dapat bergeser ke kolom K.
            $jumlahJ = $this->parseNumber($row[9] ?? null);
            $jumlahK = $this->parseNumber($row[10] ?? null);
            $jumlahBiaya = $jumlahJ > 0 ? $jumlahJ : $jumlahK;

            // Sumber dana umumnya di kolom L, tetapi pada beberapa baris akun
            // dapat bergeser sampai kolom N. Cari di rentang L:N.
            $colSDCP = '';
            foreach ([11, 12, 13] as $sdIndex) {
                $candidate = trim((string) ($row[$sdIndex] ?? ''));
                if ($candidate !== '') {
                    $colSDCP .= ' ' . $candidate;
                }
            }
            $colSDCP = trim($colSDCP);

            // ====================================================================
            // DETEKSI HIERARKI (Dengan Rules Penyesuaian)
            // ====================================================================

            // Deteksi PROGRAM
            if (preg_match('/^[0-9]{3}\.[0-9]{2}\.([A-Z]{2})$/', $colKode, $match)) {
                $state['program'] = ['kode' => $match[1], 'nama' => $this->cleanName($namaHeader)];
                $this->resetState($state, ['kegiatan', 'kro', 'ro', 'komponen', 'subkomponen', 'akun']);
                continue;
            }
            // Deteksi KEGIATAN
            elseif (preg_match('/^\d{4}$/', $colKode)) {
                $state['kegiatan'] = ['kode' => $colKode, 'nama' => $this->cleanName($namaHeader)];
                $this->resetState($state, ['kro', 'ro', 'komponen', 'subkomponen', 'akun']);
                continue;
            }
            // Deteksi KRO -> (RULE 1: Simpan Hurufnya Saja misal "EBA")
            elseif (preg_match('/^\d{4}\.([A-Z]{3})$/', $colKode, $match)) {
                $state['kro']['kode']   = $match[1]; 
                $state['kro']['nama']   = $this->cleanName($namaHeader);
                $state['kro']['volume'] = $colVolume;
                $this->resetState($state, ['ro', 'komponen', 'subkomponen', 'akun']);
                continue;
            }
            // Deteksi LOKASI KRO
            elseif (strpos($namaHeader, 'Lokasi :') === 0) {
                $state['kro']['lokasi'] = trim(str_replace('Lokasi :', '', $namaHeader));
                continue;
            }
            // Deteksi RO -> (RULE 2: Simpan sebagai String murni 3 digit misal "021")
            elseif (preg_match('/^\d{4}\.[A-Z]{3}\.(\d{1,3})$/', $colKode, $match)) {
                $roCode = str_pad($match[1], 3, '0', STR_PAD_LEFT); 
                $state['ro'] = ['kode' => $roCode, 'nama' => $this->cleanName($namaHeader)];
                $this->resetState($state, ['komponen', 'subkomponen', 'akun']);
                continue;
            }
            // Deteksi KOMPONEN -> (RULE 3: Simpan sebagai String murni 3 digit misal "051")
            // Mengabaikan huruf di depannya jika ada (misal "U051" jadi "051")
            elseif (preg_match('/^[a-zA-Z]?(\d{1,3})$/', $colKode, $match)) {
                $kompCode = str_pad($match[1], 3, '0', STR_PAD_LEFT);
                $state['komponen'] = ['kode' => $kompCode, 'nama' => $this->cleanName($namaHeader)];
                $this->resetState($state, ['subkomponen', 'akun']);
                continue;
            }
            // Deteksi SUB KOMPONEN -> (RULE 4: Jadikan null jika "TANPA SUB KOMPONEN")
            elseif (preg_match('/^[A-Z]$/i', $colKode)) {
                $colKode = strtoupper($colKode);
                $namaSub = $this->cleanName($namaHeader);
                if (stripos($namaSub, 'TANPA SUB KOMPONEN') !== false) {
                    $state['subkomponen'] = ['kode' => null, 'nama' => null];
                } else {
                    $state['subkomponen'] = ['kode' => $colKode, 'nama' => $namaSub];
                }
                $this->resetState($state, ['akun']);
                continue;
            }
            // Deteksi AKUN -> (RULE 6: Ambil hanya Sumber Dana tertentu)
            elseif (preg_match('/^\d{6}$/', $colKode)) {
                $sumberDana = null;
                if (preg_match('/\b(RMP|PLN|PNP|BLU|HIBAH|PDN|SBSN|RM)\b/i', $colSDCP, $matchSD)) {
                    $sumberDana = strtoupper($matchSD[1]);
                }
                $state['akun'] = ['kode' => $colKode, 'nama' => $this->cleanName($namaHeader), 'sdcp' => $sumberDana];
                continue;
            }

            // ====================================================================
            // DETEKSI DETAIL BELANJA (Item Terbawah)
            // ====================================================================
            
            // Normalisasi angka agar aman bila cell terbaca sebagai numeric maupun string.
            $hargaSatuan = $this->parseNumber($colHarga);

            /* SYARAT BARIS MASUK DETAIL BELANJA:
             * 1. Kolom Kode Kosong
             * 2. Ada Teks Uraiannya (yang sudah disapu dari Kolom D, E, F)
             * 3. Jumlah Biaya > 0
             * 4. Memiliki Harga Satuan ATAU Volume */
            if (empty($colKode) && !empty($teksUraian) && $jumlahBiaya > 0 && ($hargaSatuan > 0 || !empty($colVolume))) {
                
                // RULE 5: Memisah Angka Volume dan String Satuan (contoh: "16.0 OH")
                $volVal = null;
                $volSatuan = null;
                
                if (!empty($colVolume)) {
                    // Cari pola angka yg dipisahkan spasi dengan huruf
                    if (preg_match('/^([\d\.,]+)\s+(.+)$/', trim($colVolume), $vMatch)) {
                        $volVal = str_replace(',', '', $vMatch[1]);
                        $volSatuan = trim(preg_replace('/[\-\_]+$/', '', $vMatch[2])); // Bersihkan strip di akhir satuan jika ada
                    } else {
                        // Fallback jika diketik tanpa spasi (misal "16.0OH")
                        $volVal = preg_replace('/[^\d\.,]/', '', $colVolume);
                        $volSatuan = trim(preg_replace('/[\d\.,]/', '', $colVolume)); 
                        $volSatuan = trim(preg_replace('/[\-\_]+$/', '', $volSatuan));
                        
                        if (empty($volVal)) $volVal = null;
                        if (empty($volSatuan)) $volSatuan = null;
                    }
                }

                $idCounter++;
                $newId = 'rka' . str_pad($idCounter, 8, '0', STR_PAD_LEFT);

                $dataToInsert[] = [
                    'rkaID'             => $newId,
                    'documentID'        => $this->documentID,
                    
                    'kode_unit_eselon1' => $this->dataOrganisasi['kode_unit_eselon1'] ?? null,
                    'nama_unit_eselon1' => $this->dataOrganisasi['nama_unit_eselon1'] ?? null,
                    'kode_unit_eselon2' => $this->dataOrganisasi['kode_unit_eselon2'] ?? null,
                    'nama_unit_eselon2' => $this->dataOrganisasi['nama_unit_eselon2'] ?? null,
                    'kode_satker'       => $this->dataOrganisasi['kode_satker'] ?? null,
                    'nama_satker'       => $this->dataOrganisasi['nama_satker'] ?? null,
                    
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
                    
                    'kode_subkomponen'  => $state['subkomponen']['kode'],
                    'nama_subkomponen'  => $state['subkomponen']['nama'],
                    'kode_akun'         => $state['akun']['kode'],
                    'nama_akun'         => $state['akun']['nama'],
                    
                    'uraian_detail'     => $teksUraian,
                    'volume'            => $volVal,          // Disimpan sbg angka
                    'satuan_volume'     => $volSatuan,       // Disimpan sbg huruf
                    'harga_satuan'      => $hargaSatuan,
                    'jumlah_biaya'      => $jumlahBiaya,
                    'sumber_dana'       => $state['akun']['sdcp'], 
                    
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
     * Mengubah nilai Excel menjadi angka secara aman.
     * Mendukung numeric native, format ribuan Indonesia (31.000.000),
     * dan format ribuan internasional (31,000,000).
     */
    private function parseNumber($value): float
    {
        if ($value === null || $value === '') {
            return 0.0;
        }

        if (is_numeric($value)) {
            return (float) $value;
        }

        $value = trim((string) $value);
        $value = preg_replace('/[^0-9,\.\-]/', '', $value);

        if ($value === '' || $value === '-') {
            return 0.0;
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
        // Desimal dengan koma, misalnya 1,5
        elseif (substr_count($value, ',') === 1 && substr_count($value, '.') === 0) {
            $value = str_replace(',', '.', $value);
        }

        return is_numeric($value) ? (float) $value : 0.0;
    }

    private function cleanName($name)
    {
        return trim(preg_replace('/\[.*?\]/i', '', $name));
    }

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