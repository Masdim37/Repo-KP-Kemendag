<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class RenjaImport implements ToCollection, WithHeadingRow
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
        $dataToInsert = [];

        // Karena import dijalankan di dalam transaction controller, lock ini
        // menjaga generator renjaID aman jika ada upload bersamaan.
        $lastRenjaId = DB::table('renja')
            ->lockForUpdate()
            ->orderByDesc('renjaID')
            ->value('renjaID');

        $counter = $lastRenjaId
            ? (int) substr((string) $lastRenjaId, 5)
            : 0;

        foreach ($rows as $row) {
            // Baris master RENJA dianggap valid jika field Program terisi.
            // Ini mencegah baris kosong/artefak di bagian bawah Excel ikut masuk.
            if (trim((string) ($row['program'] ?? '')) === '') {
                continue;
            }

            // Hierarki organisasi
            $unitEselon1 = $this->splitKodeUraian($row['unit_eselon1'] ?? '');
            $unitEselon2 = $this->splitKodeUraian($row['unit_eselon2'] ?? '');

            // Hierarki pekerjaan
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

            $counter++;
            $renjaID = 'rwrnj' . str_pad((string) $counter, 7, '0', STR_PAD_LEFT);

            $dataToInsert[] = [
                'renjaID' => $renjaID,
                'documentID' => $this->documentID,
                'tahun_anggaran' => $this->tahunAnggaran,
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

                // Kategori & fungsi
                'fungsi' => $row['fungsi'] ?? null,
                'subfungsi' => $row['subfungsi'] ?? null,
                'prioritas_check' => $row['prioritas_check'] ?? null,
                'janpres' => $row['janpres'] ?? null,
                'kode_tags' => $row['kode_tags'] ?? null,
                'nawacita' => $row['nawacita'] ?? null,
                'mp' => $row['mp'] ?? null,

                // Tematik / Prioritas Nasional
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

                // Sasaran
                'kode_sasaran_strategis' => $sasaranStrategis['kode'],
                'sasaran_strategis' => $sasaranStrategis['uraian'],
                'kode_sasaran_program' => $sasaranProgram['kode'],
                'sasaran_program' => $sasaranProgram['uraian'],
                'kode_sasaran_kegiatan' => $sasaranKegiatan['kode'],
                'sasaran_kegiatan' => $sasaranKegiatan['uraian'],

                // Lokasi & geografis
                'kode_lokasi_ro' => $lokasiRo['kode'],
                'lokasi_ro' => $lokasiRo['uraian'],
                'pulau' => $row['pulau'] ?? null,
                'kode_propinsi' => $propinsi['kode'],
                'propinsi' => $propinsi['uraian'],
                'kode_kabupaten' => $kabupaten['kode'],
                'kabupaten' => $kabupaten['uraian'],

                // Target
                'satuan' => $row['satuan'] ?? null,
                'target_0' => $this->numericOrZero($row['target_0'] ?? null),
                'target_1' => $this->numericOrZero($row['target_1'] ?? null),
                'target_2' => $this->numericOrZero($row['target_2'] ?? null),
                'target_3' => $this->numericOrZero($row['target_3'] ?? null),
                'satuan_target' => $row['satuan_target'] ?? null,

                // Atribut komponen
                'satuan_komponen' => $row['satuan_komponen'] ?? null,
                'kewenangan' => $row['kewenangan'] ?? null,
                'type_komponen' => $row['type_komponen'] ?? null,
                'jenis_komponen' => $row['jenis_komponen'] ?? null,
                'sumber_dana' => $row['sumber_dana'] ?? null,

                // Target komponen
                'target_komponen_0' => $this->numericOrZero($row['target_komponen_0'] ?? null),
                'target_komponen_1' => $this->numericOrZero($row['target_komponen_1'] ?? null),
                'target_komponen_2' => $this->numericOrZero($row['target_komponen_2'] ?? null),
                'target_komponen_3' => $this->numericOrZero($row['target_komponen_3'] ?? null),

                // Alokasi anggaran
                'alokasi_komponen_0' => $this->numericOrZero($row['alokasi_komponen_0'] ?? null),
                'alokasi_komponen_1' => $this->numericOrZero($row['alokasi_komponen_1'] ?? null),
                'alokasi_komponen_2' => $this->numericOrZero($row['alokasi_komponen_2'] ?? null),
                'alokasi_komponen_3' => $this->numericOrZero($row['alokasi_komponen_3'] ?? null),

                // Atribut tambahan
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

        if (empty($dataToInsert)) {
            throw new \RuntimeException(
                'Data RENJA gagal diekstrak. Pastikan file menggunakan format laporan RENJA yang sesuai.'
            );
        }

        foreach (array_chunk($dataToInsert, 500) as $chunk) {
            DB::table('renja')->insert($chunk);
        }
    }

    /**
     * Memisahkan format "KODE - URAIAN" / "KODE-URAIAN" pada tanda '-' pertama.
     */
    private function splitKodeUraian($value): array
    {
        $string = trim((string) $value);

        if ($string === '') {
            return ['kode' => null, 'uraian' => null];
        }

        $parts = explode('-', $string, 2);

        return [
            'kode' => trim($parts[0]) !== '' ? trim($parts[0]) : null,
            'uraian' => isset($parts[1]) && trim($parts[1]) !== ''
                ? trim($parts[1])
                : null,
        ];
    }

    private function numericOrZero($value)
    {
        return is_numeric($value) ? $value : 0;
    }
}
