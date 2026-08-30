<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PenelitianResearchService
{
    private const PART_A_ROWS = [
        'A1' => 'Klasifikasi Rincian Output/ Rincian Output / Komponen',
        'A2' => 'Sasaran Program',
        'A3' => 'Indikator Kinerja Program (IKP)',
        'A4' => 'Sasaran Kegiatan',
        'A5' => 'Indikator Kinerja Kegiatan (IKK)',
    ];


    private const PART_B_ROWS = [
        'B1' => [
            'uraian' => 'Total Pagu RKA dengan RENJA',
            'urutan' => 1,
            'level' => 0,
            'parent' => null,
        ],
        'B2' => [
            'uraian' => 'Pagu Operasional',
            'urutan' => 2,
            'level' => 0,
            'parent' => null,
        ],
        'B2.1' => [
            'uraian' => 'Belanja Pegawai',
            'urutan' => 3,
            'level' => 1,
            'parent' => 'B2',
        ],
        'B2.2' => [
            'uraian' => 'Belanja Barang',
            'urutan' => 4,
            'level' => 1,
            'parent' => 'B2',
        ],
        'B3' => [
            'uraian' => 'Pagu PN',
            'urutan' => 5,
            'level' => 0,
            'parent' => null,
        ],
    ];


    /**
     * Mapping fixed D.1 sesuai FORMAT CHP.
     *
     * Setiap kategori mendukung pasangan KRO lama/baru yang telah ditetapkan
     * pada business rule. Mapping ini berlaku lintas Satker dan tidak
     * bergantung pada kode/nama Satker tertentu.
     */
    private const PART_D1_GROUPS = [
        'D1.A' => [
            'urutan' => 2,
            'level' => 1,
            'label' => 'a. Rekomendasi Kebijakan Bidang TIK (ABO/PBO)',
            'short_label' => 'Rekomendasi Kebijakan Bidang TIK',
            'kro_codes' => ['ABO', 'PBO'],
        ],
        'D1.B' => [
            'urutan' => 3,
            'level' => 1,
            'label' => 'b. Pengadaan Sarana Bidang TIK (CAN/RAN)',
            'short_label' => 'Pengadaan Sarana Bidang TIK',
            'kro_codes' => ['CAN', 'RAN'],
        ],
        'D1.C' => [
            'urutan' => 4,
            'level' => 1,
            'label' => 'c. Pemeliharaan Sarana Bidang TIK (CCL/RCL)',
            'short_label' => 'Pemeliharaan Sarana Bidang TIK',
            'kro_codes' => ['CCL', 'RCL'],
        ],
        'D1.D' => [
            'urutan' => 5,
            'level' => 1,
            'label' => 'd. Pengadaan Prasarana Bidang TIK (CBT/RBT)',
            'short_label' => 'Pengadaan Prasarana Bidang TIK',
            'kro_codes' => ['CBT', 'RBT'],
        ],
        'D1.E' => [
            'urutan' => 6,
            'level' => 1,
            'label' => 'e. Pemeliharaan Prasarana Bidang TIK (CDS/RDS)',
            'short_label' => 'Pemeliharaan Prasarana Bidang TIK',
            'kro_codes' => ['CDS', 'RDS'],
        ],
        'D1.F' => [
            'urutan' => 7,
            'level' => 1,
            'label' => 'f. Sistem Informasi Pemerintah (FAB/UAB)',
            'short_label' => 'Sistem Informasi Pemerintah',
            'kro_codes' => ['FAB', 'UAB'],
        ],
        'D1.G' => [
            'urutan' => 8,
            'level' => 1,
            'label' => 'g. Data dan Informasi Publik (BMA/QMA)',
            'short_label' => 'Data dan Informasi Publik',
            'kro_codes' => ['BMA', 'QMA'],
        ],
    ];


    /**
     * Fixed subkategori D.2 sesuai business rule CHP.
     *
     * Dynamic category akan ditambahkan runtime jika RKBMN/RKA memiliki aset
     * TIK yang tidak cocok ke salah satu fixed category.
     */
    private const PART_D2_FIXED_CATEGORIES = [
        'D2.PPD.PC' => [
            'group' => 'PPD',
            'label' => 'Komputer Personal (PC, Desktop PC, Mini PC)',
            'order' => 10,
        ],
        'D2.PPD.PORTABLE' => [
            'group' => 'PPD',
            'label' => 'Komputer Portabel (Laptop, Tablet PC, iPad, dll)',
            'order' => 20,
        ],
        'D2.PPD.PRINTER_SCANNER' => [
            'group' => 'PPD',
            'label' => 'Printer/Scanner',
            'order' => 30,
        ],
        'D2.PPD.AC_SPLIT' => [
            'group' => 'PPD',
            'label' => 'AC Split',
            'order' => 40,
        ],
        'D2.ATB.OFFICE_APP' => [
            'group' => 'ATB',
            'label' => 'Aplikasi Perkantoran',
            'order' => 10,
        ],
        'D2.ATB.SYSTEM' => [
            'group' => 'ATB',
            'label' => 'Sistem Informasi',
            'order' => 20,
        ],
        'D2.ATB.LICENSE' => [
            'group' => 'ATB',
            'label' => 'Lisensi',
            'order' => 30,
        ],
    ];


    /**
     * Bagian E - Kelengkapan Dokumen Pendukung.
     *
     * Lima baris fixed mengikuti FORMAT CHP.
     */
    private const PART_E_ROWS = [
        'E1' => [
            'urutan' => 1,
            'uraian' => 'Surat Pengantar',
        ],
        'E2' => [
            'urutan' => 2,
            'uraian' => 'Surat Tugas',
        ],
        'E3' => [
            'urutan' => 3,
            'uraian' => 'RKA Satker',
        ],
        'E4' => [
            'urutan' => 4,
            'uraian' => 'TOR dan RAB',
        ],
        'E5' => [
            'urutan' => 5,
            'uraian' => 'Data Dukung Lainnya',
        ],
    ];

    private const PART_E_STATUSES = [
        'LENGKAP',
        'BELUM_LENGKAP',
        'PERLU_KONFIRMASI',
    ];

    private const GENERAL_STATUSES = [
        'SESUAI',
        'TIDAK_SESUAI',
        'PERLU_KONFIRMASI',
    ];

    /**
     * Ambil hasil Bagian A untuk ditampilkan kembali pada workspace.
     */
    public function partAResults(int $penelitianID): Collection
    {
        return DB::table('penelitian_hasil_status')
            ->where('penelitianID', $penelitianID)
            ->where('bagian', 'A')
            ->orderBy('urutan')
            ->get()
            ->map(function ($row) {
                $row->status_efektif = $row->status_user !== null
                    ? $row->status_user
                    : $row->status_sistem;

                $row->penjelasan_efektif = $row->penjelasan_user !== null
                    ? $row->penjelasan_user
                    : $row->penjelasan_sistem;

                return $row;
            });
    }

    /**
     * Ambil hasil Bagian B untuk ditampilkan kembali pada workspace.
     */
    public function partBResults(int $penelitianID): Collection
    {
        return DB::table('penelitian_hasil_status')
            ->where('penelitianID', $penelitianID)
            ->where('bagian', 'B')
            ->orderBy('urutan')
            ->get()
            ->map(function ($row) {
                $row->status_efektif = $row->status_user !== null
                    ? $row->status_user
                    : $row->status_sistem;

                $row->penjelasan_efektif = $row->penjelasan_user !== null
                    ? $row->penjelasan_user
                    : $row->penjelasan_sistem;

                return $row;
            });
    }


    /**
     * Ambil hasil Bagian C untuk ditampilkan kembali pada workspace.
     *
     * Selama DRAFT, user boleh meng-override PAGU RENJA, PAGU RKA, dan CATATAN.
     * Nilai sistem tetap tersimpan pada kolom *_sistem untuk traceability.
     * SELISIH tidak mempunyai override user dan selalu dihitung ulang dari:
     *
     * PAGU RKA efektif - PAGU RENJA efektif.
     */
    public function partCResults(int $penelitianID): Collection
    {
        return DB::table('penelitian_hasil_nilai')
            ->where('penelitianID', $penelitianID)
            ->where('bagian', 'C')
            ->orderBy('urutan')
            ->get()
            ->map(function ($row) {
                $row->pagu_renja_efektif = $row->pagu_renja_user !== null
                    ? (int) $row->pagu_renja_user
                    : (int) $row->pagu_renja_sistem;

                $row->pagu_rka_efektif = $row->pagu_rka_user !== null
                    ? (int) $row->pagu_rka_user
                    : (int) $row->pagu_rka_sistem;

                $row->selisih_efektif = $row->pagu_rka_efektif - $row->pagu_renja_efektif;

                $row->penjelasan_efektif = $row->penjelasan_user !== null
                    ? $row->penjelasan_user
                    : $row->penjelasan_sistem;

                return $row;
            });
    }


    /**
     * Ambil hasil Bagian D - Budget Tagging.
     *
     * Sesuai baseline CHP MVP:
     * - 7 kategori fixed;
     * - hasil sistem awal PAGU RENJA = Rp0;
     * - hasil sistem awal PAGU RKA = Rp0;
     * - PENJELASAN sistem kosong;
     * - selama DRAFT user boleh meng-override kedua pagu dan PENJELASAN;
     * - SELISIH selalu otomatis = PAGU RKA efektif - PAGU RENJA efektif.
     */
    public function partDResults(int $penelitianID): Collection
    {
        return DB::table('penelitian_hasil_nilai')
            ->where('penelitianID', $penelitianID)
            ->where('bagian', 'D')
            ->orderBy('urutan')
            ->get()
            ->map(function ($row) {
                $row->pagu_renja_efektif = $row->pagu_renja_user !== null
                    ? (int) $row->pagu_renja_user
                    : (int) $row->pagu_renja_sistem;

                $row->pagu_rka_efektif = $row->pagu_rka_user !== null
                    ? (int) $row->pagu_rka_user
                    : (int) $row->pagu_rka_sistem;

                $row->selisih_efektif = $row->pagu_rka_efektif - $row->pagu_renja_efektif;

                $row->penjelasan_efektif = $row->penjelasan_user !== null
                    ? $row->penjelasan_user
                    : $row->penjelasan_sistem;

                return $row;
            });
    }


    /**
     * Ambil hasil D.1 Identifikasi KRO Belanja TIK.
     *
     * Angka D.1 bersifat hasil sistem/read-only.
     * User hanya dapat meng-override PENJELASAN selama DRAFT.
     */
    public function partD1Results(int $penelitianID): Collection
    {
        return DB::table('penelitian_hasil_nilai')
            ->where('penelitianID', $penelitianID)
            ->where('bagian', 'D1')
            ->orderBy('urutan')
            ->get()
            ->map(function ($row) {
                $row->pagu_renja_efektif = (int) $row->pagu_renja_sistem;
                $row->pagu_rka_efektif = (int) $row->pagu_rka_sistem;
                $row->selisih_efektif = $row->pagu_rka_efektif - $row->pagu_renja_efektif;

                $row->penjelasan_efektif = $row->penjelasan_user !== null
                    ? $row->penjelasan_user
                    : $row->penjelasan_sistem;

                return $row;
            });
    }


    /**
     * Ambil hasil D.2 Identifikasi Aset TIK.
     *
     * Semua volume/pagu read-only. Hanya PENJELASAN yang dapat di-override user.
     */
    public function partD2Results(int $penelitianID): Collection
    {
        return DB::table('penelitian_hasil_d2')
            ->where('penelitianID', $penelitianID)
            ->orderBy('urutan')
            ->get()
            ->map(function ($row) {
                $row->penjelasan_efektif = $row->penjelasan_user !== null
                    ? $row->penjelasan_user
                    : $row->penjelasan_sistem;

                return $row;
            });
    }


    /**
     * Ambil hasil Bagian E.
     *
     * STATUS dan PENJELASAN dapat di-override user selama DRAFT.
     * Nilai sistem tetap dipertahankan untuk traceability.
     */
    public function partEResults(int $penelitianID): Collection
    {
        return DB::table('penelitian_hasil_status')
            ->where('penelitianID', $penelitianID)
            ->where('bagian', 'E')
            ->orderBy('urutan')
            ->get()
            ->map(function ($row) {
                $row->status_efektif = $row->status_user !== null
                    ? $row->status_user
                    : $row->status_sistem;

                $row->penjelasan_efektif = $row->penjelasan_user !== null
                    ? $row->penjelasan_user
                    : $row->penjelasan_sistem;

                return $row;
            });
    }


    /**
     * Ambil Bagian F - CATATAN LAIN-LAIN.
     *
     * Catatan sistem:
     * - SYSTEM_RULE jika narasi deterministic;
     * - SYSTEM_AI jika redaksi berhasil dipoles Gemini.
     *
     * Catatan USER merupakan catatan manual peneliti.
     * Catatan sistem yang dihapus user tetap tersimpan tetapi tidak menjadi
     * catatan efektif.
     */
    public function partFResults(int $penelitianID): Collection
    {
        return DB::table('penelitian_catatan')
            ->where('penelitianID', $penelitianID)
            ->orderBy('urutan')
            ->orderBy('catatanID')
            ->get()
            ->map(function ($row) {
                $row->is_system = in_array(
                    $row->sumber_catatan,
                    ['SYSTEM_RULE', 'SYSTEM_AI'],
                    true
                );

                $row->catatan_efektif = $row->catatan_user !== null
                    ? $row->catatan_user
                    : $row->catatan_sistem;

                return $row;
            });
    }

    /**
     * Jalankan research engine khusus Bagian A.
     *
     * Tidak memakai Gemini. Seluruh pemeriksaan bersifat deterministic.
     * Rerun dengan sumber yang sama mempertahankan override user; perubahan sumber
     * sudah di-handle WorkspaceService dan menghapus hasil lama sebelum engine dijalankan.
     */
    public function runPartA(int $penelitianID, User $user): array
    {
        $penelitian = DB::table('penelitian')
            ->where('penelitianID', $penelitianID)
            ->first();

        if (!$penelitian) {
            throw new \RuntimeException('Penelitian tidak ditemukan.');
        }

        if ($penelitian->status !== 'DRAFT') {
            throw new \RuntimeException('Penelitian sudah FINAL dan tidak dapat dijalankan ulang.');
        }

        $this->writeLog(
            $penelitianID,
            'PEMERIKSAAN_DIMULAI',
            $user->userID,
            sprintf('%s menjalankan penelitian Bagian A pada "%s".', $user->name, $penelitian->nama_penelitian),
            ['bagian' => 'A']
        );

        try {
            $result = DB::transaction(function () use ($penelitianID, $user) {
                $penelitian = DB::table('penelitian')
                    ->where('penelitianID', $penelitianID)
                    ->lockForUpdate()
                    ->first();

                if (!$penelitian) {
                    throw new \RuntimeException('Penelitian tidak ditemukan.');
                }

                if ($penelitian->status !== 'DRAFT') {
                    throw new \RuntimeException('Penelitian sudah FINAL dan tidak dapat dijalankan ulang.');
                }

                $documents = $this->selectedDocuments($penelitianID);

                if (blank($documents['RKA'])) {
                    throw new \RuntimeException('Dokumen RKA belum dipilih pada workspace penelitian.');
                }

                // Temuan Bagian A selalu digenerate ulang. Hasil ringkas A1-A5
                // di-update tanpa menyentuh status_user/penjelasan_user agar override
                // user tetap terjaga ketika rerun memakai sumber yang sama.
                DB::table('penelitian_temuan')
                    ->where('penelitianID', $penelitianID)
                    ->where('bagian_sumber', 'A')
                    ->delete();

                $a1 = $this->evaluateA1($penelitian, $documents);
                $a2 = $this->evaluateA2($penelitian, $documents);
                $a3 = $this->manualConfirmationResult(
                    'Data pembanding Indikator Kinerja Program (IKP) belum tersedia dalam sistem sehingga IKP perlu dikonfirmasi di luar sistem.'
                );
                $a4 = $this->evaluateA4($penelitian, $documents);
                $a5 = $this->manualConfirmationResult(
                    'Data pembanding Indikator Kinerja Kegiatan (IKK) belum tersedia dalam sistem sehingga IKK perlu dikonfirmasi di luar sistem.'
                );

                $evaluations = [
                    'A1' => $a1,
                    'A2' => $a2,
                    'A3' => $a3,
                    'A4' => $a4,
                    'A5' => $a5,
                ];

                $statuses = [];

                $order = 0;
                foreach (self::PART_A_ROWS as $index => $uraian) {
                    $order++;
                    $evaluation = $evaluations[$index];
                    $hasilStatusID = $this->upsertPartAResult(
                        $penelitianID,
                        $index,
                        $order,
                        $uraian,
                        $evaluation
                    );

                    foreach ($evaluation['findings'] as $finding) {
                        $this->insertFinding(
                            $penelitianID,
                            $hasilStatusID,
                            $finding
                        );
                    }

                    $statuses[$index] = $evaluation['status'];
                }

                $this->writeLog(
                    $penelitianID,
                    'PEMERIKSAAN_BERHASIL',
                    $user->userID,
                    sprintf('%s berhasil menghasilkan penelitian Bagian A.', $user->name),
                    [
                        'bagian' => 'A',
                        'status' => $statuses,
                        'jumlah_temuan' => DB::table('penelitian_temuan')
                            ->where('penelitianID', $penelitianID)
                            ->where('bagian_sumber', 'A')
                            ->count(),
                    ]
                );

                return [
                    'statuses' => $statuses,
                    'jumlah_temuan' => DB::table('penelitian_temuan')
                        ->where('penelitianID', $penelitianID)
                        ->where('bagian_sumber', 'A')
                        ->count(),
                ];
            }, 3);

            return $result;
        } catch (\Throwable $e) {
            // Log gagal ditulis di luar transaksi agar tidak ikut rollback.
            $this->writeLog(
                $penelitianID,
                'PEMERIKSAAN_GAGAL',
                $user->userID,
                sprintf('Penelitian Bagian A gagal: %s', $e->getMessage()),
                [
                    'bagian' => 'A',
                    'exception' => get_class($e),
                ]
            );

            throw $e;
        }
    }

    /**
     * Simpan override STATUS/PENJELASAN user untuk Bagian A selama DRAFT.
     * Null = tidak ada override. String kosong pada penjelasan adalah override valid
     * untuk mengosongkan penjelasan sistem.
     */
    public function savePartAOverrides(
        int $penelitianID,
        array $payload,
        User $user
    ): void {
        if (empty($payload)) {
            return;
        }

        DB::transaction(function () use ($penelitianID, $payload, $user) {
            $penelitian = DB::table('penelitian')
                ->where('penelitianID', $penelitianID)
                ->lockForUpdate()
                ->first();

            if (!$penelitian) {
                throw new \RuntimeException('Penelitian tidak ditemukan.');
            }

            if ($penelitian->status !== 'DRAFT') {
                throw new \RuntimeException('Penelitian sudah FINAL dan hasil Bagian A tidak dapat diubah.');
            }

            $changed = 0;

            foreach (self::PART_A_ROWS as $kodeBaris => $uraian) {
                if (!array_key_exists($kodeBaris, $payload)) {
                    continue;
                }

                $row = DB::table('penelitian_hasil_status')
                    ->where('penelitianID', $penelitianID)
                    ->where('bagian', 'A')
                    ->where('kode_baris', $kodeBaris)
                    ->first();

                if (!$row) {
                    // Belum pernah menjalankan Bagian A; tidak membuat result hanya dari input browser.
                    continue;
                }

                $input = is_array($payload[$kodeBaris]) ? $payload[$kodeBaris] : [];
                $status = strtoupper(trim((string) ($input['status'] ?? '')));

                if (!in_array($status, self::GENERAL_STATUSES, true)) {
                    throw new \RuntimeException("Status {$kodeBaris} tidak valid.");
                }

                $statusUser = $status === (string) $row->status_sistem
                    ? null
                    : $status;

                $submittedExplanation = array_key_exists('penjelasan', $input)
                    ? trim((string) $input['penjelasan'])
                    : ($row->penjelasan_user !== null
                        ? (string) $row->penjelasan_user
                        : (string) $row->penjelasan_sistem);

                // Empty string harus tetap bisa menjadi explicit override.
                $systemExplanation = trim((string) ($row->penjelasan_sistem ?? ''));
                $penjelasanUser = $submittedExplanation === $systemExplanation
                    ? null
                    : $submittedExplanation;

                if ($statusUser !== $row->status_user || $penjelasanUser !== $row->penjelasan_user) {
                    DB::table('penelitian_hasil_status')
                        ->where('hasilStatusID', $row->hasilStatusID)
                        ->update([
                            'status_user' => $statusUser,
                            'penjelasan_user' => $penjelasanUser,
                            'updated_at' => now(),
                        ]);
                    $changed++;
                }
            }

            // Activity DRAFT_DISIMPAN sudah dicatat oleh PenelitianWorkspaceService
            // pada request Simpan DRAFT yang sama, sehingga tidak dibuat log duplikat di sini.
        }, 3);
    }

    /**
     * Jalankan research engine khusus Bagian B.
     *
     * Seluruh pemeriksaan numerik deterministic dan tidak menggunakan Gemini.
     * Rerun mempertahankan override user selama sumber dokumen tidak berubah.
     */
    public function runPartB(int $penelitianID, User $user): array
    {
        $penelitian = DB::table('penelitian')
            ->where('penelitianID', $penelitianID)
            ->first();

        if (!$penelitian) {
            throw new \RuntimeException('Penelitian tidak ditemukan.');
        }

        if ($penelitian->status !== 'DRAFT') {
            throw new \RuntimeException('Penelitian sudah FINAL dan tidak dapat dijalankan ulang.');
        }

        $this->writeLog(
            $penelitianID,
            'PEMERIKSAAN_DIMULAI',
            $user->userID,
            sprintf('%s menjalankan penelitian Bagian B pada "%s".', $user->name, $penelitian->nama_penelitian),
            ['bagian' => 'B']
        );

        try {
            return DB::transaction(function () use ($penelitianID, $user) {
                $penelitian = DB::table('penelitian')
                    ->where('penelitianID', $penelitianID)
                    ->lockForUpdate()
                    ->first();

                if (!$penelitian) {
                    throw new \RuntimeException('Penelitian tidak ditemukan.');
                }

                if ($penelitian->status !== 'DRAFT') {
                    throw new \RuntimeException('Penelitian sudah FINAL dan tidak dapat dijalankan ulang.');
                }

                $documents = $this->selectedDocuments($penelitianID);

                if (blank($documents['RKA'])) {
                    throw new \RuntimeException('Dokumen RKA belum dipilih pada workspace penelitian.');
                }

                DB::table('penelitian_temuan')
                    ->where('penelitianID', $penelitianID)
                    ->where('bagian_sumber', 'B')
                    ->delete();

                $b1 = $this->evaluateB1($penelitian, $documents);
                $b21 = $this->evaluateB2Category(
                    $penelitian,
                    $documents,
                    '51',
                    'Belanja Pegawai',
                    'B2_BELANJA_PEGAWAI'
                );
                $b22 = $this->evaluateB2Category(
                    $penelitian,
                    $documents,
                    '52',
                    'Belanja Barang',
                    'B2_BELANJA_BARANG'
                );
                $b2 = $this->aggregateB2($b21, $b22);
                $b3 = $this->evaluateB3();

                $evaluations = [
                    'B1' => $b1,
                    'B2' => $b2,
                    'B2.1' => $b21,
                    'B2.2' => $b22,
                    'B3' => $b3,
                ];

                $resultIDs = [];
                $statuses = [];

                foreach (self::PART_B_ROWS as $kodeBaris => $config) {
                    $parentID = null;
                    if ($config['parent'] !== null) {
                        $parentID = $resultIDs[$config['parent']] ?? null;
                    }

                    $hasilStatusID = $this->upsertPartBResult(
                        $penelitianID,
                        $kodeBaris,
                        (int) $config['urutan'],
                        (int) $config['level'],
                        $parentID,
                        (string) $config['uraian'],
                        $evaluations[$kodeBaris]
                    );

                    $resultIDs[$kodeBaris] = $hasilStatusID;
                    $statuses[$kodeBaris] = $evaluations[$kodeBaris]['status'];

                    foreach ($evaluations[$kodeBaris]['findings'] as $finding) {
                        $this->insertFinding(
                            $penelitianID,
                            $hasilStatusID,
                            $finding,
                            'B'
                        );
                    }
                }

                $jumlahTemuan = DB::table('penelitian_temuan')
                    ->where('penelitianID', $penelitianID)
                    ->where('bagian_sumber', 'B')
                    ->count();

                $this->writeLog(
                    $penelitianID,
                    'PEMERIKSAAN_BERHASIL',
                    $user->userID,
                    sprintf('%s berhasil menghasilkan penelitian Bagian B.', $user->name),
                    [
                        'bagian' => 'B',
                        'status' => $statuses,
                        'jumlah_temuan' => $jumlahTemuan,
                    ]
                );

                return [
                    'statuses' => $statuses,
                    'jumlah_temuan' => $jumlahTemuan,
                ];
            }, 3);
        } catch (\Throwable $e) {
            $this->writeLog(
                $penelitianID,
                'PEMERIKSAAN_GAGAL',
                $user->userID,
                sprintf('Penelitian Bagian B gagal: %s', $e->getMessage()),
                [
                    'bagian' => 'B',
                    'exception' => get_class($e),
                ]
            );

            throw $e;
        }
    }

    /**
     * Simpan override STATUS/PENJELASAN user untuk Bagian B selama DRAFT.
     */
    public function savePartBOverrides(
        int $penelitianID,
        array $payload,
        User $user
    ): void {
        if (empty($payload)) {
            return;
        }

        DB::transaction(function () use ($penelitianID, $payload, $user) {
            $penelitian = DB::table('penelitian')
                ->where('penelitianID', $penelitianID)
                ->lockForUpdate()
                ->first();

            if (!$penelitian) {
                throw new \RuntimeException('Penelitian tidak ditemukan.');
            }

            if ($penelitian->status !== 'DRAFT') {
                throw new \RuntimeException('Penelitian sudah FINAL dan hasil Bagian B tidak dapat diubah.');
            }

            $changed = 0;

            foreach (self::PART_B_ROWS as $kodeBaris => $config) {
                if (!array_key_exists($kodeBaris, $payload)) {
                    continue;
                }

                $row = DB::table('penelitian_hasil_status')
                    ->where('penelitianID', $penelitianID)
                    ->where('bagian', 'B')
                    ->where('kode_baris', $kodeBaris)
                    ->first();

                if (!$row) {
                    continue;
                }

                $input = is_array($payload[$kodeBaris]) ? $payload[$kodeBaris] : [];
                $status = strtoupper(trim((string) ($input['status'] ?? '')));

                if (!in_array($status, self::GENERAL_STATUSES, true)) {
                    throw new \RuntimeException("Status {$kodeBaris} tidak valid.");
                }

                $statusUser = $status === (string) $row->status_sistem
                    ? null
                    : $status;

                $submittedExplanation = array_key_exists('penjelasan', $input)
                    ? trim((string) $input['penjelasan'])
                    : ($row->penjelasan_user !== null
                        ? (string) $row->penjelasan_user
                        : (string) $row->penjelasan_sistem);

                $systemExplanation = trim((string) ($row->penjelasan_sistem ?? ''));
                $penjelasanUser = $submittedExplanation === $systemExplanation
                    ? null
                    : $submittedExplanation;

                if ($statusUser !== $row->status_user || $penjelasanUser !== $row->penjelasan_user) {
                    DB::table('penelitian_hasil_status')
                        ->where('hasilStatusID', $row->hasilStatusID)
                        ->update([
                            'status_user' => $statusUser,
                            'penjelasan_user' => $penjelasanUser,
                            'updated_at' => now(),
                        ]);
                    $changed++;
                }
            }

            if ($changed > 0) {
                $this->writeLog(
                    $penelitianID,
                    'DRAFT_DISIMPAN',
                    $user->userID,
                    sprintf('%s memperbarui hasil Bagian B pada DRAFT penelitian.', $user->name),
                    [
                        'bagian' => 'B',
                        'jumlah_baris_diubah' => $changed,
                    ]
                );
            }
        }, 3);
    }



    /**
     * Simpan override user Bagian C selama status penelitian masih DRAFT.
     *
     * User hanya dapat mengubah:
     * - PAGU RENJA  -> pagu_renja_user
     * - PAGU RKA    -> pagu_rka_user
     * - CATATAN     -> penjelasan_user
     *
     * SELISIH tidak pernah menerima input user. Nilai efektif selisih selalu
     * dihitung dari PAGU RKA efektif - PAGU RENJA efektif.
     *
     * Jika nilai yang dikirim sama dengan hasil sistem, kolom *_user
     * dikembalikan menjadi NULL agar override dianggap tidak aktif.
     */
    public function savePartCOverrides(
        int $penelitianID,
        array $payload,
        User $user
    ): void {
        if (empty($payload)) {
            return;
        }

        DB::transaction(function () use ($penelitianID, $payload, $user) {
            $penelitian = DB::table('penelitian')
                ->where('penelitianID', $penelitianID)
                ->lockForUpdate()
                ->first();

            if (!$penelitian) {
                throw new \RuntimeException('Penelitian tidak ditemukan.');
            }

            if ($penelitian->status !== 'DRAFT') {
                throw new \RuntimeException('Penelitian sudah FINAL dan hasil Bagian C tidak dapat diubah.');
            }

            $rows = DB::table('penelitian_hasil_nilai')
                ->where('penelitianID', $penelitianID)
                ->where('bagian', 'C')
                ->get()
                ->keyBy('kode_baris');

            $changed = 0;
            $changedRows = [];

            foreach ($payload as $kodeBaris => $input) {
                $kodeBaris = (string) $kodeBaris;

                if (!$rows->has($kodeBaris)) {
                    // Jangan pernah membuat baris hasil baru dari input browser.
                    continue;
                }

                $row = $rows->get($kodeBaris);
                $input = is_array($input) ? $input : [];

                // Blank amount berarti reset ke hasil sistem.
                $submittedRenja = array_key_exists('pagu_renja', $input)
                    && $input['pagu_renja'] !== null
                    && $input['pagu_renja'] !== ''
                        ? (int) $input['pagu_renja']
                        : (int) $row->pagu_renja_sistem;

                $submittedRka = array_key_exists('pagu_rka', $input)
                    && $input['pagu_rka'] !== null
                    && $input['pagu_rka'] !== ''
                        ? (int) $input['pagu_rka']
                        : (int) $row->pagu_rka_sistem;

                if ($submittedRenja < 0 || $submittedRka < 0) {
                    throw new \RuntimeException("Pagu pada {$kodeBaris} tidak boleh bernilai negatif.");
                }

                $renjaUser = $submittedRenja === (int) $row->pagu_renja_sistem
                    ? null
                    : $submittedRenja;

                $rkaUser = $submittedRka === (int) $row->pagu_rka_sistem
                    ? null
                    : $submittedRka;

                // Untuk CATATAN, string kosong adalah override yang valid:
                // user memang boleh menghapus CATATAN sistem selama masih DRAFT.
                if (array_key_exists('catatan', $input)) {
                    $submittedNote = trim((string) ($input['catatan'] ?? ''));
                } else {
                    $submittedNote = $row->penjelasan_user !== null
                        ? (string) $row->penjelasan_user
                        : (string) ($row->penjelasan_sistem ?? '');
                }

                $systemNote = trim((string) ($row->penjelasan_sistem ?? ''));
                $noteUser = $submittedNote === $systemNote
                    ? null
                    : $submittedNote;

                $currentRenjaUser = $row->pagu_renja_user !== null
                    ? (int) $row->pagu_renja_user
                    : null;

                $currentRkaUser = $row->pagu_rka_user !== null
                    ? (int) $row->pagu_rka_user
                    : null;

                $currentNoteUser = $row->penjelasan_user !== null
                    ? (string) $row->penjelasan_user
                    : null;

                if (
                    $renjaUser !== $currentRenjaUser
                    || $rkaUser !== $currentRkaUser
                    || $noteUser !== $currentNoteUser
                ) {
                    DB::table('penelitian_hasil_nilai')
                        ->where('hasilNilaiID', $row->hasilNilaiID)
                        ->update([
                            'pagu_renja_user' => $renjaUser,
                            'pagu_rka_user' => $rkaUser,
                            'penjelasan_user' => $noteUser,

                            // SELISIH tetap tidak dapat diinput user, tetapi
                            // disimpan sebagai nilai EFEKTIF yang dihitung otomatis.
                            'selisih' => $submittedRka - $submittedRenja,
                            'updated_at' => now(),
                        ]);

                    $changed++;
                    $changedRows[] = $kodeBaris;
                }
            }

            if ($changed > 0) {
                $this->writeLog(
                    $penelitianID,
                    'DRAFT_DISIMPAN',
                    $user->userID,
                    sprintf('%s memperbarui override Bagian C pada DRAFT penelitian.', $user->name),
                    [
                        'bagian' => 'C',
                        'jumlah_baris_diubah' => $changed,
                        'kode_baris_diubah' => $changedRows,
                        'field_override' => [
                            'pagu_renja_user',
                            'pagu_rka_user',
                            'penjelasan_user',
                        ],
                        'selisih_manual' => false,
                    ]
                );
            }
        }, 3);
    }


    /**
     * Jalankan research engine khusus Bagian C.
     *
     * Business Rule utama:
     * - RENJA TA aktif = alokasi_komponen_0 × 1.000.
     * - alokasi_komponen_1 s.d. _3 tidak dijumlahkan.
     * - Selisih selalu PAGU RKA - PAGU RENJA.
     * - C.1 Kegiatan dinamis dari union Kegiatan RKA dan RENJA.
     * - C.2 sumber dana dinormalisasi: RENJA Rupiah ↔ RKA RM,
     *   RENJA PNBP ↔ RKA PNP sebagai canonical RM/PNBP.
     * - C.3 s.d. C.5 RENJA ditampilkan Rp0 karena RENJA tidak tersedia
     *   sampai level Akun; nilai tersebut bukan berarti pagu RENJA sebenarnya nol.
     *
     * Seluruh perhitungan deterministic dan tidak menggunakan Gemini.
     */
    public function runPartC(int $penelitianID, User $user): array
    {
        $penelitian = DB::table('penelitian')
            ->where('penelitianID', $penelitianID)
            ->first();

        if (!$penelitian) {
            throw new \RuntimeException('Penelitian tidak ditemukan.');
        }

        if ($penelitian->status !== 'DRAFT') {
            throw new \RuntimeException('Penelitian sudah FINAL dan tidak dapat dijalankan ulang.');
        }

        $this->writeLog(
            $penelitianID,
            'PEMERIKSAAN_DIMULAI',
            $user->userID,
            sprintf('%s menjalankan penelitian Bagian C pada "%s".', $user->name, $penelitian->nama_penelitian),
            ['bagian' => 'C']
        );

        try {
            return DB::transaction(function () use ($penelitianID, $user) {
                $penelitian = DB::table('penelitian')
                    ->where('penelitianID', $penelitianID)
                    ->lockForUpdate()
                    ->first();

                if (!$penelitian) {
                    throw new \RuntimeException('Penelitian tidak ditemukan.');
                }

                if ($penelitian->status !== 'DRAFT') {
                    throw new \RuntimeException('Penelitian sudah FINAL dan tidak dapat dijalankan ulang.');
                }

                $documents = $this->selectedDocuments($penelitianID);

                if (blank($documents['RKA'])) {
                    throw new \RuntimeException('Dokumen RKA belum dipilih pada workspace penelitian.');
                }

                $rows = $this->buildPartCRows($penelitian, $documents);

                if (empty($rows)) {
                    throw new \RuntimeException('Bagian C tidak menghasilkan baris penelitian.');
                }

                $resultIDs = [];
                $generatedCodes = [];

                foreach ($rows as $row) {
                    $parentID = null;
                    if (!empty($row['parent'])) {
                        $parentID = $resultIDs[$row['parent']] ?? null;
                    }

                    $hasilNilaiID = $this->upsertPartCResult(
                        $penelitianID,
                        (string) $row['kode'],
                        (int) $row['urutan'],
                        (int) $row['level'],
                        $parentID,
                        (string) $row['uraian'],
                        (array) $row['evaluation']
                    );

                    $resultIDs[$row['kode']] = $hasilNilaiID;
                    $generatedCodes[] = (string) $row['kode'];
                }

                // Hapus baris dinamis lama yang tidak lagi menjadi bagian hasil saat rerun.
                // Fixed parent rows selalu ada di $generatedCodes sehingga tidak ikut terhapus.
                DB::table('penelitian_hasil_nilai')
                    ->where('penelitianID', $penelitianID)
                    ->where('bagian', 'C')
                    ->whereNotIn('kode_baris', $generatedCodes)
                    ->delete();

                $c1 = collect($rows)->firstWhere('kode', 'C1');
                $totalRenja = (int) ($c1['evaluation']['pagu_renja'] ?? 0);
                $totalRka = (int) ($c1['evaluation']['pagu_rka'] ?? 0);
                $selisih = $totalRka - $totalRenja;

                $this->writeLog(
                    $penelitianID,
                    'PEMERIKSAAN_BERHASIL',
                    $user->userID,
                    sprintf('%s berhasil menghasilkan penelitian Bagian C.', $user->name),
                    [
                        'bagian' => 'C',
                        'jumlah_baris' => count($rows),
                        'total_renja' => $totalRenja,
                        'total_rka' => $totalRka,
                        'selisih' => $selisih,
                    ]
                );

                return [
                    'jumlah_baris' => count($rows),
                    'total_renja' => $totalRenja,
                    'total_rka' => $totalRka,
                    'selisih' => $selisih,
                ];
            }, 3);
        } catch (\Throwable $e) {
            $this->writeLog(
                $penelitianID,
                'PEMERIKSAAN_GAGAL',
                $user->userID,
                sprintf('Penelitian Bagian C gagal: %s', $e->getMessage()),
                [
                    'bagian' => 'C',
                    'exception' => get_class($e),
                ]
            );

            throw $e;
        }
    }

    /**
     * Bangun seluruh baris Bagian C sesuai urutan format CHP.
     */
    private function buildPartCRows(object $penelitian, array $documents): array
    {
        $rows = [];
        $order = 0;

        // =========================================================
        // C.1 Total Anggaran Kegiatan + child kegiatan dinamis
        // =========================================================
        $c1 = $this->buildC1Rows($penelitian, $documents);

        $order++;
        $rows[] = [
            'kode' => 'C1',
            'parent' => null,
            'urutan' => $order,
            'level' => 0,
            'uraian' => 'Total Anggaran Kegiatan',
            'evaluation' => $c1['parent'],
        ];

        foreach ($c1['children'] as $child) {
            $order++;
            $rows[] = [
                'kode' => $child['kode'],
                'parent' => 'C1',
                'urutan' => $order,
                'level' => 1,
                'uraian' => $child['uraian'],
                'evaluation' => $child['evaluation'],
            ];
        }

        // =========================================================
        // C.2 Sumber Dana
        // =========================================================
        $c2 = $this->buildC2Rows($penelitian, $documents);

        $order++;
        $rows[] = [
            'kode' => 'C2',
            'parent' => null,
            'urutan' => $order,
            'level' => 0,
            'uraian' => 'Sumber Dana',
            'evaluation' => $c2['parent'],
        ];

        foreach ($c2['children'] as $child) {
            $order++;
            $rows[] = [
                'kode' => $child['kode'],
                'parent' => 'C2',
                'urutan' => $order,
                'level' => 1,
                'uraian' => $child['uraian'],
                'evaluation' => $child['evaluation'],
            ];
        }

        // =========================================================
        // C.3 Belanja Pegawai
        // =========================================================
        $c3 = $this->buildC3Rows($penelitian, $documents);

        $order++;
        $rows[] = [
            'kode' => 'C3',
            'parent' => null,
            'urutan' => $order,
            'level' => 0,
            'uraian' => 'Belanja Pegawai',
            'evaluation' => $c3['parent'],
        ];

        foreach ($c3['children'] as $child) {
            $order++;
            $rows[] = [
                'kode' => $child['kode'],
                'parent' => 'C3',
                'urutan' => $order,
                'level' => 1,
                'uraian' => $child['uraian'],
                'evaluation' => $child['evaluation'],
            ];
        }

        // =========================================================
        // C.4 Belanja Barang
        // =========================================================
        $c4 = $this->buildC4Rows($penelitian, $documents);

        $order++;
        $rows[] = [
            'kode' => 'C4',
            'parent' => null,
            'urutan' => $order,
            'level' => 0,
            'uraian' => 'Belanja Barang',
            'evaluation' => $c4['parent'],
        ];

        foreach ($c4['children'] as $child) {
            $order++;
            $rows[] = [
                'kode' => $child['kode'],
                'parent' => 'C4',
                'urutan' => $order,
                'level' => 1,
                'uraian' => $child['uraian'],
                'evaluation' => $child['evaluation'],
            ];
        }

        // =========================================================
        // C.5 Belanja Modal
        // =========================================================
        $c5 = $this->buildC5Row($penelitian, $documents);

        $order++;
        $rows[] = [
            'kode' => 'C5',
            'parent' => null,
            'urutan' => $order,
            'level' => 0,
            'uraian' => 'Belanja Modal',
            'evaluation' => $c5,
        ];

        return $rows;
    }

    private function buildC1Rows(object $penelitian, array $documents): array
    {
        $rkaID = $documents['RKA'];
        $renjaID = $documents['RENJA'];

        $rkaActivities = $this->rkaScopedQuery($penelitian, $rkaID)
            ->whereNotNull('kode_kegiatan')
            ->select([
                'kode_kegiatan',
                DB::raw('MAX(nama_kegiatan) AS nama_kegiatan'),
                DB::raw('SUM(COALESCE(jumlah_biaya, 0)) AS total'),
            ])
            ->groupBy('kode_kegiatan')
            ->get()
            ->mapWithKeys(function ($row) {
                $code = trim((string) $row->kode_kegiatan);
                return $code === '' ? [] : [$code => [
                    'kode_kegiatan' => $code,
                    'nama_kegiatan' => trim((string) ($row->nama_kegiatan ?? '')),
                    'total' => (int) ($row->total ?? 0),
                ]];
            });

        $renjaActivities = collect();

        if ($renjaID) {
            $renjaActivities = $this->renjaScopedQuery($penelitian, $renjaID)
                ->whereNotNull('kode_kegiatan')
                ->select([
                    'kode_kegiatan',
                    DB::raw('MAX(kegiatan) AS nama_kegiatan'),
                    DB::raw('SUM(COALESCE(alokasi_komponen_0, 0)) * 1000 AS total'),
                ])
                ->groupBy('kode_kegiatan')
                ->get()
                ->mapWithKeys(function ($row) {
                    $code = trim((string) $row->kode_kegiatan);
                    return $code === '' ? [] : [$code => [
                        'kode_kegiatan' => $code,
                        'nama_kegiatan' => trim((string) ($row->nama_kegiatan ?? '')),
                        'total' => (int) ($row->total ?? 0),
                    ]];
                });
        }

        $activityCodes = $rkaActivities->keys()
            ->merge($renjaActivities->keys())
            ->unique()
            ->sort(SORT_NATURAL)
            ->values();

        $children = [];
        $totalRenja = 0;
        $totalRka = 0;

        foreach ($activityCodes as $code) {
            $rkaRow = $rkaActivities->get($code);
            $renjaRow = $renjaActivities->get($code);

            $paguRka = (int) ($rkaRow['total'] ?? 0);
            $paguRenja = (int) ($renjaRow['total'] ?? 0);
            $selisih = $paguRka - $paguRenja;

            $totalRka += $paguRka;
            $totalRenja += $paguRenja;

            $nama = trim((string) ($rkaRow['nama_kegiatan'] ?? $renjaRow['nama_kegiatan'] ?? ''));
            $uraian = $nama !== '' ? "{$code} - {$nama}" : (string) $code;

            if (!$renjaID) {
                $note = 'Dokumen RENJA belum dipilih sehingga nilai RENJA ditampilkan Rp0 dan perbandingan kegiatan belum dapat disimpulkan';
            } elseif (!$renjaRow) {
                $note = 'Kegiatan terdapat pada RKA tetapi tidak ditemukan pada RENJA sehingga nilai RENJA ditampilkan Rp0';
            } elseif (!$rkaRow) {
                $note = 'Kegiatan terdapat pada RENJA tetapi tidak ditemukan pada RKA sehingga nilai RKA ditampilkan Rp0';
            } elseif ($selisih === 0) {
                $note = 'Pagu kegiatan telah sesuai antara RENJA dan RKA';
            } else {
                $note = sprintf(
                    'Terdapat selisih pagu kegiatan sebesar %s',
                    $this->formatRupiah($selisih)
                );
            }

            $children[] = [
                'kode' => 'C1.KGT.' . $code,
                'uraian' => $uraian,
                'evaluation' => $this->numericResult(
                    $paguRenja,
                    $paguRka,
                    $note,
                    [
                        'jenis' => 'KEGIATAN',
                        'kode_kegiatan' => $code,
                        'nama_kegiatan' => $nama,
                        'renja_allocation_source' => 'alokasi_komponen_0',
                        'renja_multiplier' => 1000,
                        'renja_found' => $renjaRow !== null,
                        'rka_found' => $rkaRow !== null,
                    ]
                ),
            ];
        }

        $parentNote = !$renjaID
            ? 'Dokumen RENJA belum dipilih sehingga nilai RENJA pada Total Anggaran Kegiatan ditampilkan Rp0 dan belum dapat dibandingkan'
            : ($totalRka === $totalRenja
                ? 'Total Anggaran Kegiatan telah sesuai antara RENJA dan RKA'
                : sprintf(
                    'Terdapat selisih Total Anggaran Kegiatan sebesar %s',
                    $this->formatRupiah($totalRka - $totalRenja)
                ));

        return [
            'parent' => $this->numericResult(
                $totalRenja,
                $totalRka,
                $parentNote,
                [
                    'jenis' => 'TOTAL_ANGGARAN_KEGIATAN',
                    'jumlah_kegiatan' => count($children),
                    'renja_allocation_source' => 'alokasi_komponen_0',
                    'renja_multiplier' => 1000,
                ]
            ),
            'children' => $children,
        ];
    }

    private function buildC2Rows(object $penelitian, array $documents): array
    {
        $rkaID = $documents['RKA'];
        $renjaID = $documents['RENJA'];

        $rkaTotals = $this->fundingSourceTotals(
            $this->rkaScopedQuery($penelitian, $rkaID)
                ->select([
                    'sumber_dana',
                    DB::raw('SUM(COALESCE(jumlah_biaya, 0)) AS total'),
                ])
                ->groupBy('sumber_dana')
                ->get(),
            'RKA'
        );

        $renjaTotals = [
            'RM' => 0,
            'PNBP' => 0,
            'unmapped_total' => 0,
            'unmapped_labels' => [],
        ];

        if ($renjaID) {
            $renjaTotals = $this->fundingSourceTotals(
                $this->renjaScopedQuery($penelitian, $renjaID)
                    ->select([
                        'sumber_dana',
                        DB::raw('SUM(COALESCE(alokasi_komponen_0, 0)) * 1000 AS total'),
                    ])
                    ->groupBy('sumber_dana')
                    ->get(),
                'RENJA'
            );
        }

        $children = [];
        $configs = [
            ['kode' => 'C2.RM', 'uraian' => 'Rupiah Murni (RM)', 'key' => 'RM'],
            ['kode' => 'C2.PNBP', 'uraian' => 'PNBP', 'key' => 'PNBP'],
        ];

        foreach ($configs as $config) {
            $paguRenja = (int) $renjaTotals[$config['key']];
            $paguRka = (int) $rkaTotals[$config['key']];
            $selisih = $paguRka - $paguRenja;

            if (!$renjaID) {
                $note = 'Dokumen RENJA belum dipilih sehingga nilai RENJA ditampilkan Rp0 dan sumber dana belum dapat dibandingkan';
            } elseif ($selisih === 0) {
                $note = "Pagu sumber dana {$config['uraian']} telah sesuai antara RENJA dan RKA";
            } else {
                $note = sprintf(
                    'Terdapat selisih sumber dana %s sebesar %s',
                    $config['uraian'],
                    $this->formatRupiah($selisih)
                );
            }

            $children[] = [
                'kode' => $config['kode'],
                'uraian' => $config['uraian'],
                'evaluation' => $this->numericResult(
                    $paguRenja,
                    $paguRka,
                    $note,
                    [
                        'jenis' => 'SUMBER_DANA',
                        'canonical_source' => $config['key'],
                        'renja_allocation_source' => 'alokasi_komponen_0',
                        'renja_multiplier' => 1000,
                    ]
                ),
            ];
        }

        $totalRenja = array_sum(array_map(
            fn ($child) => (int) $child['evaluation']['pagu_renja'],
            $children
        ));
        $totalRka = array_sum(array_map(
            fn ($child) => (int) $child['evaluation']['pagu_rka'],
            $children
        ));

        $parentDetails = [];

        if ((int) $renjaTotals['unmapped_total'] !== 0) {
            $parentDetails[] = sprintf(
                'RENJA mempunyai sumber dana di luar mapping RM/PNBP sebesar %s (%s)',
                $this->formatRupiah((int) $renjaTotals['unmapped_total']),
                implode(', ', $renjaTotals['unmapped_labels'])
            );
        }

        if ((int) $rkaTotals['unmapped_total'] !== 0) {
            $parentDetails[] = sprintf(
                'RKA mempunyai sumber dana di luar mapping RM/PNBP sebesar %s (%s)',
                $this->formatRupiah((int) $rkaTotals['unmapped_total']),
                implode(', ', $rkaTotals['unmapped_labels'])
            );
        }

        if (!$renjaID) {
            $parentSummary = 'Dokumen RENJA belum dipilih sehingga nilai RENJA pada Sumber Dana ditampilkan Rp0 dan belum dapat dibandingkan';
        } elseif ($totalRenja === $totalRka && empty($parentDetails)) {
            $parentSummary = 'Total Sumber Dana RM dan PNBP telah sesuai antara RENJA dan RKA';
        } else {
            $parentSummary = sprintf(
                'Terdapat selisih total Sumber Dana RM dan PNBP sebesar %s',
                $this->formatRupiah($totalRka - $totalRenja)
            );
        }

        return [
            'parent' => $this->numericResult(
                $totalRenja,
                $totalRka,
                $this->formatExplanation($parentSummary, $parentDetails),
                [
                    'jenis' => 'TOTAL_SUMBER_DANA',
                    'renja_unmapped_total' => (int) $renjaTotals['unmapped_total'],
                    'rka_unmapped_total' => (int) $rkaTotals['unmapped_total'],
                ],
                true
            ),
            'children' => $children,
        ];
    }

    private function buildC3Rows(object $penelitian, array $documents): array
    {
        $rkaID = $documents['RKA'];

        $categories = [
            'PNS' => 0,
            'PPPK' => 0,
            'HONORER' => 0,
        ];

        $prefixTotal = 0;
        $unclassifiedTotal = 0;
        $unclassifiedAccounts = [];

        $rows = $this->rkaAccountSummary($penelitian, $rkaID, '51');

        foreach ($rows as $row) {
            $amount = (int) ($row->total ?? 0);
            $accountCode = trim((string) ($row->kode_akun ?? ''));
            $accountName = trim((string) ($row->nama_akun ?? ''));

            $prefixTotal += $amount;

            $category = $this->employeeAccountCategoryByCode($accountCode);

            if ($category !== null) {
                $categories[$category] += $amount;
                continue;
            }

            $unclassifiedTotal += $amount;
            $unclassifiedAccounts[] = [
                'kode_akun' => $accountCode,
                'nama_akun' => $accountName,
                'jumlah' => $amount,
            ];
        }

        $note = 'Data RENJA tidak tersedia sampai level akun sehingga nilai RENJA pada rincian ini ditampilkan Rp0';

        $children = [
            [
                'kode' => 'C3.PNS',
                'uraian' => 'PNS',
                'evaluation' => $this->numericResult(
                    0,
                    $categories['PNS'],
                    $note,
                    [
                        'kategori' => 'PNS',
                        'account_mapping' => config('penelitian.c3_employee_account_mapping.PNS', []),
                    ]
                ),
            ],
            [
                'kode' => 'C3.PPPK',
                'uraian' => 'PPPK',
                'evaluation' => $this->numericResult(
                    0,
                    $categories['PPPK'],
                    $note,
                    [
                        'kategori' => 'PPPK',
                        'account_mapping' => config('penelitian.c3_employee_account_mapping.PPPK', []),
                    ]
                ),
            ],
            [
                'kode' => 'C3.HONORER',
                'uraian' => 'Honorer',
                'evaluation' => $this->numericResult(
                    0,
                    $categories['HONORER'],
                    $note,
                    [
                        'kategori' => 'HONORER',
                        'account_mapping' => config('penelitian.c3_employee_account_mapping.HONORER', []),
                    ]
                ),
            ],
        ];

        $parentDetails = [];

        foreach ($unclassifiedAccounts as $account) {
            $parentDetails[] = sprintf(
                'Akun %s - %s sebesar %s belum dipetakan ke kategori PNS, PPPK, atau Honorer',
                $account['kode_akun'] !== '' ? $account['kode_akun'] : '(kode kosong)',
                $account['nama_akun'] !== '' ? $account['nama_akun'] : '(nama akun kosong)',
                $this->formatRupiah((int) $account['jumlah'])
            );
        }

        // PENTING:
        // Parent Belanja Pegawai adalah SELURUH akun prefix 51, bukan hanya
        // child yang berhasil dipetakan. Dengan demikian total jenis belanja
        // tetap benar walaupun ada akun baru/ambigu yang belum masuk mapping.
        return [
            'parent' => $this->numericResult(
                0,
                $prefixTotal,
                $this->formatExplanation($note, $parentDetails),
                [
                    'jenis' => 'BELANJA_PEGAWAI',
                    'akun_prefix_51_total' => $prefixTotal,
                    'pns_total' => $categories['PNS'],
                    'pppk_total' => $categories['PPPK'],
                    'honorer_total' => $categories['HONORER'],
                    'unclassified_total' => $unclassifiedTotal,
                    'unclassified_accounts' => $unclassifiedAccounts,
                    'classification_method' => 'EXPLICIT_ACCOUNT_CODE_MAPPING',
                ],
                true
            ),
            'children' => $children,
        ];
    }

    private function buildC4Rows(object $penelitian, array $documents): array
    {
        $rkaID = $documents['RKA'];

        /*
         * Rule C.4 harus berlaku lintas Satker.
         *
         * Config dapat memuat lebih dari satu path operasional. Engine TIDAK
         * menganggap Satker tertentu wajib memiliki salah satu path tersebut.
         *
         * Jika minimal satu configured operational path ditemukan pada RKA:
         * - akun 52 pada path tersebut          => Operasional
         * - seluruh akun 52 di luar path itu   => Non Operasional
         *
         * Jika tidak ada configured path yang ditemukan:
         * - parent tetap = seluruh akun prefix 52;
         * - child Operasional dan Non Operasional TIDAK ditebak;
         * - keduanya ditampilkan Rp0 dengan CATATAN konfirmasi.
         *
         * Ini mencegah seluruh akun 52 sebuah Satker otomatis dianggap
         * Non Operasional hanya karena struktur EBA -> 994 -> 002 tidak ada.
         */
        $operationalPaths = $this->configuredC4OperationalPaths();

        $rows = $this->rkaAccountHierarchySummary($penelitian, $rkaID, '52');
        $prefixTotal = (int) $rows->sum(fn ($row) => (int) ($row->total ?? 0));

        $presentPaths = $this->presentC4OperationalPaths(
            $penelitian,
            $rkaID,
            $operationalPaths
        );

        $classificationAvailable = !empty($presentPaths);

        $operasional = 0;
        $nonOperasional = 0;

        if ($classificationAvailable) {
            foreach ($rows as $row) {
                $amount = (int) ($row->total ?? 0);

                if ($this->matchesAnyC4OperationalPath($row, $presentPaths)) {
                    $operasional += $amount;
                } else {
                    $nonOperasional += $amount;
                }
            }
        }

        $baseNote = 'Data RENJA tidak tersedia sampai level akun sehingga nilai RENJA pada rincian ini ditampilkan Rp0';

        if ($classificationAvailable) {
            $pathLabels = array_map(
                fn (array $path) => $this->formatC4OperationalPath($path),
                $presentPaths
            );

            $parentNote = $this->formatExplanation(
                $baseNote,
                [
                    sprintf(
                        'Klasifikasi Belanja Barang menggunakan struktur operasional yang ditemukan pada RKA: %s',
                        implode('; ', $pathLabels)
                    ),
                ]
            );

            $operationalNote = $baseNote;
            $nonOperationalNote = $baseNote;
        } elseif ($prefixTotal > 0) {
            $configuredLabels = array_map(
                fn (array $path) => $this->formatC4OperationalPath($path),
                $operationalPaths
            );

            $warning = sprintf(
                'RKA memiliki Belanja Barang akun prefix 52 sebesar %s, tetapi tidak ditemukan struktur operasional yang terdaftar pada konfigurasi (%s). Parent Belanja Barang tetap menggunakan seluruh akun 52, sedangkan nilai Operasional dan Non Operasional tidak ditentukan otomatis dan perlu dikonfirmasi',
                $this->formatRupiah($prefixTotal),
                !empty($configuredLabels)
                    ? implode('; ', $configuredLabels)
                    : 'belum ada path yang dikonfigurasi'
            );

            $parentNote = $this->formatExplanation($baseNote, [$warning]);

            $childWarning = 'Klasifikasi belum dapat ditentukan karena struktur operasional yang terdaftar pada konfigurasi tidak ditemukan pada RKA Satker ini. Nilai Rp0 pada baris ini bukan berarti pagu sebenarnya nol dan perlu dikonfirmasi';

            $operationalNote = $this->formatExplanation($baseNote, [$childWarning]);
            $nonOperationalNote = $this->formatExplanation($baseNote, [$childWarning]);
        } else {
            $parentNote = $this->formatExplanation(
                $baseNote,
                ['Tidak terdapat Belanja Barang dengan akun berawalan 52 pada RKA']
            );

            $operationalNote = $baseNote;
            $nonOperationalNote = $baseNote;
        }

        $children = [
            [
                'kode' => 'C4.OP',
                'uraian' => 'Belanja Barang Operasional',
                'evaluation' => $this->numericResult(
                    0,
                    $classificationAvailable ? $operasional : 0,
                    $operationalNote,
                    [
                        'kategori' => 'OPERASIONAL',
                        'classification_method' => $classificationAvailable
                            ? 'CONFIGURED_RKA_HIERARCHY'
                            : 'NOT_CLASSIFIED',
                        'classification_available' => $classificationAvailable,
                        'configured_operational_paths' => $operationalPaths,
                        'present_operational_paths' => $presentPaths,
                    ],
                    true
                ),
            ],
            [
                'kode' => 'C4.NONOP',
                'uraian' => 'Belanja Barang Non Operasional',
                'evaluation' => $this->numericResult(
                    0,
                    $classificationAvailable ? $nonOperasional : 0,
                    $nonOperationalNote,
                    [
                        'kategori' => 'NON_OPERASIONAL',
                        'classification_method' => $classificationAvailable
                            ? 'ALL_OTHER_52_ACCOUNTS'
                            : 'NOT_CLASSIFIED',
                        'classification_available' => $classificationAvailable,
                        'configured_operational_paths' => $operationalPaths,
                        'present_operational_paths' => $presentPaths,
                    ],
                    true
                ),
            ],
        ];

        return [
            'parent' => $this->numericResult(
                0,
                $prefixTotal,
                $parentNote,
                [
                    'jenis' => 'BELANJA_BARANG',
                    'akun_prefix_52_total' => $prefixTotal,
                    'operasional_total' => $classificationAvailable ? $operasional : null,
                    'non_operasional_total' => $classificationAvailable ? $nonOperasional : null,
                    'classification_method' => $classificationAvailable
                        ? 'CONFIGURED_RKA_HIERARCHY'
                        : 'PARENT_ONLY_NEEDS_CONFIRMATION',
                    'classification_available' => $classificationAvailable,
                    'configured_operational_paths' => $operationalPaths,
                    'present_operational_paths' => $presentPaths,
                ],
                true
            ),
            'children' => $children,
        ];
    }

    /**
     * Ambil daftar path operasional C.4 dari config.
     *
     * Mendukung fallback key lama `c4_operational_path` agar deployment yang
     * belum mengganti config tidak langsung gagal.
     */
    private function configuredC4OperationalPaths(): array
    {
        $paths = config('penelitian.c4_operational_paths');

        if (!is_array($paths) || empty($paths)) {
            $legacy = config('penelitian.c4_operational_path');

            if (is_array($legacy) && !empty($legacy)) {
                $paths = [$legacy];
            }
        }

        if (!is_array($paths)) {
            return [];
        }

        $normalized = [];

        foreach ($paths as $path) {
            if (!is_array($path)) {
                continue;
            }

            $kodeKro = trim((string) ($path['kode_kro'] ?? ''));
            $kodeRo = trim((string) ($path['kode_ro'] ?? ''));
            $kodeKomponen = trim((string) ($path['kode_komponen'] ?? ''));

            if ($kodeKro === '' || $kodeRo === '' || $kodeKomponen === '') {
                continue;
            }

            $key = $kodeKro . '|' . $kodeRo . '|' . $kodeKomponen;

            $normalized[$key] = [
                'kode_kro' => $kodeKro,
                'kode_ro' => $kodeRo,
                'kode_komponen' => $kodeKomponen,
            ];
        }

        return array_values($normalized);
    }

    /**
     * Tentukan path configured mana yang benar-benar terdapat pada RKA Satker
     * yang sedang diteliti. Pemeriksaan path dilakukan terhadap seluruh RKA,
     * bukan hanya akun 52, sehingga validasi struktur tidak bergantung pada
     * jenis akun yang kebetulan berada di Komponen tersebut.
     */
    private function presentC4OperationalPaths(
        object $penelitian,
        string $rkaID,
        array $configuredPaths
    ): array {
        if (empty($configuredPaths)) {
            return [];
        }

        $hierarchies = $this->rkaScopedQuery($penelitian, $rkaID)
            ->select([
                'kode_kro',
                'kode_ro',
                'kode_komponen',
            ])
            ->distinct()
            ->get();

        $present = [];

        foreach ($configuredPaths as $path) {
            $exists = $hierarchies->contains(function ($row) use ($path) {
                return
                    trim((string) ($row->kode_kro ?? '')) === $path['kode_kro']
                    && trim((string) ($row->kode_ro ?? '')) === $path['kode_ro']
                    && trim((string) ($row->kode_komponen ?? '')) === $path['kode_komponen'];
            });

            if ($exists) {
                $present[] = $path;
            }
        }

        return $present;
    }

    private function matchesAnyC4OperationalPath(object $row, array $paths): bool
    {
        foreach ($paths as $path) {
            if (
                trim((string) ($row->kode_kro ?? '')) === $path['kode_kro']
                && trim((string) ($row->kode_ro ?? '')) === $path['kode_ro']
                && trim((string) ($row->kode_komponen ?? '')) === $path['kode_komponen']
            ) {
                return true;
            }
        }

        return false;
    }

    private function formatC4OperationalPath(array $path): string
    {
        return sprintf(
            '%s → %s → %s',
            $path['kode_kro'] ?? '?',
            $path['kode_ro'] ?? '?',
            $path['kode_komponen'] ?? '?'
        );
    }

    private function buildC5Row(object $penelitian, array $documents): array
    {
        $rkaID = $documents['RKA'];

        // Seluruh akun kelas 53 adalah Belanja Modal.
        // Tidak menggunakan lagi keyword "MODAL" pada nama akun karena
        // nomenklatur seperti "Belanja Penambahan Nilai ..." tetap merupakan
        // akun kelas 53 walaupun nama akun tidak mengandung kata Modal.
        $modal = (int) $this->rkaScopedQuery($penelitian, $rkaID)
            ->where('kode_akun', 'like', '53%')
            ->sum('jumlah_biaya');

        $note = 'Data RENJA tidak tersedia sampai level akun sehingga nilai RENJA pada rincian ini ditampilkan Rp0';

        return $this->numericResult(
            0,
            $modal,
            $note,
            [
                'jenis' => 'BELANJA_MODAL',
                'akun_prefix' => '53',
                'classification_method' => 'ACCOUNT_PREFIX',
            ]
        );
    }

    private function numericResult(
        int $paguRenja,
        int $paguRka,
        string $explanation,
        array $metadata = [],
        bool $alreadyFormattedExplanation = false
    ): array {
        return [
            'pagu_renja' => $paguRenja,
            'pagu_rka' => $paguRka,
            'selisih' => $paguRka - $paguRenja,
            'explanation' => $alreadyFormattedExplanation
                ? $explanation
                : $this->formatExplanation($explanation),
            'metadata' => $metadata,
        ];
    }

    private function fundingSourceTotals(Collection $rows, string $origin): array
    {
        $totals = [
            'RM' => 0,
            'PNBP' => 0,
            'unmapped_total' => 0,
            'unmapped_labels' => [],
        ];

        foreach ($rows as $row) {
            $raw = trim((string) ($row->sumber_dana ?? ''));
            $amount = (int) ($row->total ?? 0);
            $canonical = $this->canonicalFundingSource($raw, $origin);

            if ($canonical === null) {
                if ($amount !== 0) {
                    $totals['unmapped_total'] += $amount;
                    $totals['unmapped_labels'][] = $raw !== '' ? $raw : '(kosong)';
                }
                continue;
            }

            $totals[$canonical] += $amount;
        }

        $totals['unmapped_labels'] = array_values(array_unique($totals['unmapped_labels']));

        return $totals;
    }

    private function canonicalFundingSource(string $value, string $origin): ?string
    {
        $normalized = $this->normalizeKeywordText($value);

        if ($origin === 'RENJA') {
            if (in_array($normalized, ['RUPIAH', 'RUPIAH MURNI', 'RM'], true)) {
                return 'RM';
            }

            if (in_array($normalized, ['PNBP', 'PNP'], true)) {
                return 'PNBP';
            }

            return null;
        }

        if (in_array($normalized, ['RM', 'RUPIAH', 'RUPIAH MURNI'], true)) {
            return 'RM';
        }

        if (in_array($normalized, ['PNP', 'PNBP'], true)) {
            return 'PNBP';
        }

        return null;
    }

    private function rkaAccountSummary(object $penelitian, string $rkaID, string $prefix): Collection
    {
        return $this->rkaScopedQuery($penelitian, $rkaID)
            ->where('kode_akun', 'like', $prefix . '%')
            ->select([
                'kode_akun',
                'nama_akun',
                DB::raw('SUM(COALESCE(jumlah_biaya, 0)) AS total'),
            ])
            ->groupBy('kode_akun', 'nama_akun')
            ->get();
    }

    /**
     * Klasifikasi C.3 berdasarkan mapping kode akun eksplisit/configurable.
     *
     * Tidak menggunakan Gemini dan tidak menebak dari nama akun.
     * Akun yang belum terdaftar akan dikembalikan NULL dan dicatat sebagai
     * akun belum terpetakan pada CATATAN parent Belanja Pegawai.
     */
    private function employeeAccountCategoryByCode(string $kodeAkun): ?string
    {
        $kodeAkun = trim($kodeAkun);

        if ($kodeAkun === '') {
            return null;
        }

        $mapping = config('penelitian.c3_employee_account_mapping', []);

        foreach (['PNS', 'PPPK', 'HONORER'] as $category) {
            $codes = array_map(
                static fn ($code) => trim((string) $code),
                (array) ($mapping[$category] ?? [])
            );

            if (in_array($kodeAkun, $codes, true)) {
                return $category;
            }
        }

        return null;
    }

    /**
     * Ringkasan akun RKA dengan mempertahankan konteks hierarki yang diperlukan
     * untuk klasifikasi C.4.
     */
    private function rkaAccountHierarchySummary(
        object $penelitian,
        string $rkaID,
        string $prefix
    ): Collection {
        return $this->rkaScopedQuery($penelitian, $rkaID)
            ->where('kode_akun', 'like', $prefix . '%')
            ->select([
                'kode_kegiatan',
                'kode_kro',
                'kode_ro',
                'kode_komponen',
                'kode_akun',
                'nama_akun',
                DB::raw('SUM(COALESCE(jumlah_biaya, 0)) AS total'),
            ])
            ->groupBy(
                'kode_kegiatan',
                'kode_kro',
                'kode_ro',
                'kode_komponen',
                'kode_akun',
                'nama_akun'
            )
            ->get();
    }

    private function normalizeKeywordText(string $value): string
    {
        $value = strtoupper(trim($value));
        return preg_replace('/\s+/u', ' ', $value) ?? $value;
    }

    private function upsertPartCResult(
        int $penelitianID,
        string $kodeBaris,
        int $urutan,
        int $level,
        ?int $parentHasilNilaiID,
        string $uraian,
        array $evaluation
    ): int {
        $existing = DB::table('penelitian_hasil_nilai')
            ->where('penelitianID', $penelitianID)
            ->where('bagian', 'C')
            ->where('kode_baris', $kodeBaris)
            ->first();

        $effectiveRenjaForDifference = $existing && $existing->pagu_renja_user !== null
            ? (int) $existing->pagu_renja_user
            : (int) $evaluation['pagu_renja'];

        $effectiveRkaForDifference = $existing && $existing->pagu_rka_user !== null
            ? (int) $existing->pagu_rka_user
            : (int) $evaluation['pagu_rka'];

        $payload = [
            'parentHasilNilaiID' => $parentHasilNilaiID,
            'urutan' => $urutan,
            'level_baris' => $level,
            'uraian' => $uraian,
            'pagu_renja_sistem' => (int) $evaluation['pagu_renja'],
            'pagu_rka_sistem' => (int) $evaluation['pagu_rka'],

            // Tidak ada selisih_user. Nilai ini selalu dihitung otomatis dari
            // nilai efektif, termasuk setelah rerun ketika override user tetap ada.
            'selisih' => $effectiveRkaForDifference - $effectiveRenjaForDifference,
            'penjelasan_sistem' => (string) $evaluation['explanation'],
            'metadata_json' => json_encode(
                $evaluation['metadata'] ?? [],
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            ),
            'updated_at' => now(),
        ];

        if ($existing) {
            DB::table('penelitian_hasil_nilai')
                ->where('hasilNilaiID', $existing->hasilNilaiID)
                ->update($payload);

            return (int) $existing->hasilNilaiID;
        }

        return (int) DB::table('penelitian_hasil_nilai')->insertGetId([
            'penelitianID' => $penelitianID,
            'bagian' => 'C',
            'kode_baris' => $kodeBaris,
            ...$payload,
            'pagu_renja_user' => null,
            'pagu_rka_user' => null,
            'penjelasan_user' => null,
            'created_at' => now(),
        ]);
    }


    /**
     * Simpan override user Bagian D selama status penelitian masih DRAFT.
     *
     * Field editable:
     * - PAGU RENJA
     * - PAGU RKA
     * - PENJELASAN
     *
     * SELISIH tidak pernah menerima input manual dan selalu dihitung otomatis.
     */
    public function savePartDOverrides(
        int $penelitianID,
        array $payload,
        User $user
    ): void {
        if (empty($payload)) {
            return;
        }

        DB::transaction(function () use ($penelitianID, $payload, $user) {
            $penelitian = DB::table('penelitian')
                ->where('penelitianID', $penelitianID)
                ->lockForUpdate()
                ->first();

            if (!$penelitian) {
                throw new \RuntimeException('Penelitian tidak ditemukan.');
            }

            if ($penelitian->status !== 'DRAFT') {
                throw new \RuntimeException('Penelitian sudah FINAL dan hasil Bagian D tidak dapat diubah.');
            }

            $rows = DB::table('penelitian_hasil_nilai')
                ->where('penelitianID', $penelitianID)
                ->where('bagian', 'D')
                ->get()
                ->keyBy('kode_baris');

            $changed = 0;
            $changedRows = [];

            foreach ($payload as $kodeBaris => $input) {
                $kodeBaris = (string) $kodeBaris;

                if (!$rows->has($kodeBaris)) {
                    // Input browser tidak boleh membuat baris baru.
                    continue;
                }

                $row = $rows->get($kodeBaris);
                $input = is_array($input) ? $input : [];

                // Blank amount berarti kembali ke hasil sistem.
                $submittedRenja = array_key_exists('pagu_renja', $input)
                    && $input['pagu_renja'] !== null
                    && $input['pagu_renja'] !== ''
                        ? (int) $input['pagu_renja']
                        : (int) $row->pagu_renja_sistem;

                $submittedRka = array_key_exists('pagu_rka', $input)
                    && $input['pagu_rka'] !== null
                    && $input['pagu_rka'] !== ''
                        ? (int) $input['pagu_rka']
                        : (int) $row->pagu_rka_sistem;

                if ($submittedRenja < 0 || $submittedRka < 0) {
                    throw new \RuntimeException("Pagu pada {$kodeBaris} tidak boleh bernilai negatif.");
                }

                $renjaUser = $submittedRenja === (int) $row->pagu_renja_sistem
                    ? null
                    : $submittedRenja;

                $rkaUser = $submittedRka === (int) $row->pagu_rka_sistem
                    ? null
                    : $submittedRka;

                if (array_key_exists('penjelasan', $input)) {
                    $submittedExplanation = trim((string) ($input['penjelasan'] ?? ''));
                } else {
                    $submittedExplanation = $row->penjelasan_user !== null
                        ? (string) $row->penjelasan_user
                        : (string) ($row->penjelasan_sistem ?? '');
                }

                $systemExplanation = trim((string) ($row->penjelasan_sistem ?? ''));

                $explanationUser = $submittedExplanation === $systemExplanation
                    ? null
                    : $submittedExplanation;

                $currentRenjaUser = $row->pagu_renja_user !== null
                    ? (int) $row->pagu_renja_user
                    : null;

                $currentRkaUser = $row->pagu_rka_user !== null
                    ? (int) $row->pagu_rka_user
                    : null;

                $currentExplanationUser = $row->penjelasan_user !== null
                    ? (string) $row->penjelasan_user
                    : null;

                if (
                    $renjaUser !== $currentRenjaUser
                    || $rkaUser !== $currentRkaUser
                    || $explanationUser !== $currentExplanationUser
                ) {
                    DB::table('penelitian_hasil_nilai')
                        ->where('hasilNilaiID', $row->hasilNilaiID)
                        ->update([
                            'pagu_renja_user' => $renjaUser,
                            'pagu_rka_user' => $rkaUser,
                            'penjelasan_user' => $explanationUser,

                            // Tidak ada selisih_user. Nilai disimpan secara otomatis
                            // dari nilai efektif yang sedang dipakai user.
                            'selisih' => $submittedRka - $submittedRenja,
                            'updated_at' => now(),
                        ]);

                    $changed++;
                    $changedRows[] = $kodeBaris;
                }
            }

            if ($changed > 0) {
                $this->writeLog(
                    $penelitianID,
                    'DRAFT_DISIMPAN',
                    $user->userID,
                    sprintf('%s memperbarui override Bagian D pada DRAFT penelitian.', $user->name),
                    [
                        'bagian' => 'D',
                        'jumlah_baris_diubah' => $changed,
                        'kode_baris_diubah' => $changedRows,
                        'field_override' => [
                            'pagu_renja_user',
                            'pagu_rka_user',
                            'penjelasan_user',
                        ],
                        'selisih_manual' => false,
                    ]
                );
            }
        }, 3);
    }

    /**
     * Jalankan Research Engine Bagian D.
     *
     * Baseline MVP CHP menetapkan 7 kategori Budget Tagging fixed dengan nilai
     * awal Rp0 dan PENJELASAN kosong. Engine tidak menggunakan Gemini dan tidak
     * menebak nilai tagging dari dokumen sumber.
     *
     * Tujuan run Bagian D pada fase ini adalah:
     * - membentuk 7 baris resmi sesuai format CHP;
     * - menyimpan nilai sistem awal secara auditable;
     * - menyediakan ruang override user selama DRAFT.
     */
    public function runPartD(int $penelitianID, User $user): array
    {
        $penelitian = DB::table('penelitian')
            ->where('penelitianID', $penelitianID)
            ->first();

        if (!$penelitian) {
            throw new \RuntimeException('Penelitian tidak ditemukan.');
        }

        if ($penelitian->status !== 'DRAFT') {
            throw new \RuntimeException('Penelitian sudah FINAL dan tidak dapat dijalankan ulang.');
        }

        $this->writeLog(
            $penelitianID,
            'PEMERIKSAAN_DIMULAI',
            $user->userID,
            sprintf('%s menjalankan penelitian Bagian D pada "%s".', $user->name, $penelitian->nama_penelitian),
            [
                'bagian' => 'D',
                'mode' => 'DEFAULT_MVP',
            ]
        );

        try {
            return DB::transaction(function () use ($penelitianID, $user) {
                $penelitian = DB::table('penelitian')
                    ->where('penelitianID', $penelitianID)
                    ->lockForUpdate()
                    ->first();

                if (!$penelitian) {
                    throw new \RuntimeException('Penelitian tidak ditemukan.');
                }

                if ($penelitian->status !== 'DRAFT') {
                    throw new \RuntimeException('Penelitian sudah FINAL dan tidak dapat dijalankan ulang.');
                }

                $documents = $this->selectedDocuments($penelitianID);

                if (blank($documents['RKA'])) {
                    throw new \RuntimeException('Dokumen RKA belum dipilih pada workspace penelitian.');
                }

                $rows = $this->partDRows();
                $generatedCodes = [];

                foreach ($rows as $row) {
                    $this->upsertPartDResult(
                        $penelitianID,
                        (string) $row['kode'],
                        (int) $row['urutan'],
                        (string) $row['uraian']
                    );

                    $generatedCodes[] = (string) $row['kode'];
                }

                // Defensive cleanup jika definisi fixed rows pernah berubah.
                DB::table('penelitian_hasil_nilai')
                    ->where('penelitianID', $penelitianID)
                    ->where('bagian', 'D')
                    ->whereNotIn('kode_baris', $generatedCodes)
                    ->delete();

                $this->writeLog(
                    $penelitianID,
                    'PEMERIKSAAN_BERHASIL',
                    $user->userID,
                    sprintf('%s berhasil menghasilkan penelitian Bagian D.', $user->name),
                    [
                        'bagian' => 'D',
                        'mode' => 'DEFAULT_MVP',
                        'jumlah_baris' => count($rows),
                        'default_pagu_renja' => 0,
                        'default_pagu_rka' => 0,
                        'automatic_tagging' => false,
                    ]
                );

                return [
                    'jumlah_baris' => count($rows),
                    'mode' => 'DEFAULT_MVP',
                    'automatic_tagging' => false,
                ];
            }, 3);
        } catch (\Throwable $e) {
            $this->writeLog(
                $penelitianID,
                'PEMERIKSAAN_GAGAL',
                $user->userID,
                sprintf('Penelitian Bagian D gagal: %s', $e->getMessage()),
                [
                    'bagian' => 'D',
                    'exception' => get_class($e),
                ]
            );

            throw $e;
        }
    }

    /**
     * Fixed rows sesuai FORMAT CHP.
     *
     * Ejaan "Infrastuktur" dipertahankan karena mengikuti template CHP
     * yang telah ditetapkan sebagai baseline.
     */
    private function partDRows(): array
    {
        return [
            [
                'kode' => 'D1',
                'urutan' => 1,
                'uraian' => 'Nawacita',
            ],
            [
                'kode' => 'D2',
                'urutan' => 2,
                'uraian' => 'Janji Presiden',
            ],
            [
                'kode' => 'D3',
                'urutan' => 3,
                'uraian' => 'Prioritas Nasional',
            ],
            [
                'kode' => 'D4',
                'urutan' => 4,
                'uraian' => 'Anggaran Infrastuktur',
            ],
            [
                'kode' => 'D5',
                'urutan' => 5,
                'uraian' => 'Anggaran Responsif Gender',
            ],
            [
                'kode' => 'D6',
                'urutan' => 6,
                'uraian' => 'Kerjasama Selatan-Selatan dan Triangular (KSST)',
            ],
            [
                'kode' => 'D7',
                'urutan' => 7,
                'uraian' => 'SDGs',
            ],
        ];
    }

    /**
     * Upsert hasil numeric Bagian D dengan tetap mempertahankan override user.
     */
    private function upsertPartDResult(
        int $penelitianID,
        string $kodeBaris,
        int $urutan,
        string $uraian
    ): int {
        $existing = DB::table('penelitian_hasil_nilai')
            ->where('penelitianID', $penelitianID)
            ->where('bagian', 'D')
            ->where('kode_baris', $kodeBaris)
            ->first();

        $systemRenja = 0;
        $systemRka = 0;
        $systemExplanation = '';

        $effectiveRenjaForDifference = $existing && $existing->pagu_renja_user !== null
            ? (int) $existing->pagu_renja_user
            : $systemRenja;

        $effectiveRkaForDifference = $existing && $existing->pagu_rka_user !== null
            ? (int) $existing->pagu_rka_user
            : $systemRka;

        $payload = [
            'parentHasilNilaiID' => null,
            'urutan' => $urutan,
            'level_baris' => 0,
            'uraian' => $uraian,
            'pagu_renja_sistem' => $systemRenja,
            'pagu_rka_sistem' => $systemRka,
            'selisih' => $effectiveRkaForDifference - $effectiveRenjaForDifference,
            'penjelasan_sistem' => $systemExplanation,
            'metadata_json' => json_encode([
                'automatic_check' => false,
                'default_status_mvp' => true,
                'budget_tagging_category' => $kodeBaris,
                'default_pagu_renja' => 0,
                'default_pagu_rka' => 0,
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'updated_at' => now(),
        ];

        if ($existing) {
            DB::table('penelitian_hasil_nilai')
                ->where('hasilNilaiID', $existing->hasilNilaiID)
                ->update($payload);

            return (int) $existing->hasilNilaiID;
        }

        return (int) DB::table('penelitian_hasil_nilai')->insertGetId([
            'penelitianID' => $penelitianID,
            'bagian' => 'D',
            'kode_baris' => $kodeBaris,
            ...$payload,
            'pagu_renja_user' => null,
            'pagu_rka_user' => null,
            'penjelasan_user' => null,
            'created_at' => now(),
        ]);
    }


    /**
     * Simpan override PENJELASAN D.1 selama DRAFT.
     *
     * PAGU RENJA, PAGU RKA, dan SELISIH tetap read-only karena merupakan hasil
     * kalkulasi deterministic dari RENJA/RKA.
     */
    public function savePartD1Overrides(
        int $penelitianID,
        array $payload,
        User $user
    ): void {
        if (empty($payload)) {
            return;
        }

        DB::transaction(function () use ($penelitianID, $payload, $user) {
            $penelitian = DB::table('penelitian')
                ->where('penelitianID', $penelitianID)
                ->lockForUpdate()
                ->first();

            if (!$penelitian) {
                throw new \RuntimeException('Penelitian tidak ditemukan.');
            }

            if ($penelitian->status !== 'DRAFT') {
                throw new \RuntimeException('Penelitian sudah FINAL dan hasil D.1 tidak dapat diubah.');
            }

            $rows = DB::table('penelitian_hasil_nilai')
                ->where('penelitianID', $penelitianID)
                ->where('bagian', 'D1')
                ->get()
                ->keyBy('kode_baris');

            $changed = 0;
            $changedRows = [];

            foreach ($payload as $kodeBaris => $input) {
                $kodeBaris = (string) $kodeBaris;

                if (!$rows->has($kodeBaris)) {
                    continue;
                }

                $row = $rows->get($kodeBaris);
                $input = is_array($input) ? $input : [];

                if (array_key_exists('penjelasan', $input)) {
                    $submitted = trim((string) ($input['penjelasan'] ?? ''));
                } else {
                    $submitted = $row->penjelasan_user !== null
                        ? (string) $row->penjelasan_user
                        : (string) ($row->penjelasan_sistem ?? '');
                }

                $systemExplanation = trim((string) ($row->penjelasan_sistem ?? ''));

                $userExplanation = $submitted === $systemExplanation
                    ? null
                    : $submitted;

                $currentUserExplanation = $row->penjelasan_user !== null
                    ? (string) $row->penjelasan_user
                    : null;

                if ($userExplanation !== $currentUserExplanation) {
                    DB::table('penelitian_hasil_nilai')
                        ->where('hasilNilaiID', $row->hasilNilaiID)
                        ->update([
                            'penjelasan_user' => $userExplanation,
                            'updated_at' => now(),
                        ]);

                    $changed++;
                    $changedRows[] = $kodeBaris;
                }
            }

            if ($changed > 0) {
                $this->writeLog(
                    $penelitianID,
                    'DRAFT_DISIMPAN',
                    $user->userID,
                    sprintf('%s memperbarui PENJELASAN D.1 pada DRAFT penelitian.', $user->name),
                    [
                        'bagian' => 'D1',
                        'jumlah_baris_diubah' => $changed,
                        'kode_baris_diubah' => $changedRows,
                        'field_override' => ['penjelasan_user'],
                        'numeric_override' => false,
                    ]
                );
            }
        }, 3);
    }

    /**
     * Jalankan D.1 Identifikasi KRO Belanja Bidang Teknologi Informasi dan Komunikasi.
     *
     * Business Rule:
     * - Rekomendasi Kebijakan Bidang TIK : ABO / PBO
     * - Pengadaan Sarana Bidang TIK      : CAN / RAN
     * - Pemeliharaan Sarana Bidang TIK   : CCL / RCL
     * - Pengadaan Prasarana Bidang TIK   : CBT / RBT
     * - Pemeliharaan Prasarana Bidang TIK: CDS / RDS
     * - Sistem Informasi Pemerintah      : FAB / UAB
     * - Data dan Informasi Publik        : BMA / QMA
     *
     * RENJA menggunakan rule terbaru TA aktif:
     * SUM(alokasi_komponen_0) × 1.000.
     *
     * alokasi_komponen_1 s.d. _3 tidak dijumlahkan.
     *
     * RKA:
     * SUM(jumlah_biaya) untuk KRO terkait pada dokumen RKA/Satker penelitian.
     *
     * Selisih:
     * PAGU RKA - PAGU RENJA.
     *
     * Tidak menggunakan Gemini.
     */
    public function runPartD1(int $penelitianID, User $user): array
    {
        $penelitian = DB::table('penelitian')
            ->where('penelitianID', $penelitianID)
            ->first();

        if (!$penelitian) {
            throw new \RuntimeException('Penelitian tidak ditemukan.');
        }

        if ($penelitian->status !== 'DRAFT') {
            throw new \RuntimeException('Penelitian sudah FINAL dan tidak dapat dijalankan ulang.');
        }

        $this->writeLog(
            $penelitianID,
            'PEMERIKSAAN_DIMULAI',
            $user->userID,
            sprintf('%s menjalankan penelitian Bagian D.1 pada "%s".', $user->name, $penelitian->nama_penelitian),
            ['bagian' => 'D1']
        );

        try {
            return DB::transaction(function () use ($penelitianID, $user) {
                $penelitian = DB::table('penelitian')
                    ->where('penelitianID', $penelitianID)
                    ->lockForUpdate()
                    ->first();

                if (!$penelitian) {
                    throw new \RuntimeException('Penelitian tidak ditemukan.');
                }

                if ($penelitian->status !== 'DRAFT') {
                    throw new \RuntimeException('Penelitian sudah FINAL dan tidak dapat dijalankan ulang.');
                }

                $documents = $this->selectedDocuments($penelitianID);

                if (blank($documents['RKA'])) {
                    throw new \RuntimeException('Dokumen RKA belum dipilih pada workspace penelitian.');
                }

                $evaluations = $this->evaluatePartD1($penelitian, $documents);

                $parentID = $this->upsertPartD1Result(
                    $penelitianID,
                    'D1.TOTAL',
                    1,
                    0,
                    null,
                    '1 Belanja TIK',
                    $evaluations['parent']
                );

                $generatedCodes = ['D1.TOTAL'];

                foreach (self::PART_D1_GROUPS as $kodeBaris => $definition) {
                    $this->upsertPartD1Result(
                        $penelitianID,
                        $kodeBaris,
                        (int) $definition['urutan'],
                        1,
                        $parentID,
                        (string) $definition['label'],
                        $evaluations['children'][$kodeBaris]
                    );

                    $generatedCodes[] = $kodeBaris;
                }

                DB::table('penelitian_hasil_nilai')
                    ->where('penelitianID', $penelitianID)
                    ->where('bagian', 'D1')
                    ->whereNotIn('kode_baris', $generatedCodes)
                    ->delete();

                $this->writeLog(
                    $penelitianID,
                    'PEMERIKSAAN_BERHASIL',
                    $user->userID,
                    sprintf('%s berhasil menghasilkan penelitian Bagian D.1.', $user->name),
                    [
                        'bagian' => 'D1',
                        'jumlah_baris' => count($generatedCodes),
                        'pagu_renja' => $evaluations['parent']['pagu_renja'],
                        'pagu_rka' => $evaluations['parent']['pagu_rka'],
                        'selisih' => $evaluations['parent']['selisih'],
                        'renja_allocation_source' => 'alokasi_komponen_0',
                        'renja_multiplier' => 1000,
                        'automatic_check' => true,
                        'gemini' => false,
                    ]
                );

                return [
                    'jumlah_baris' => count($generatedCodes),
                    'pagu_renja' => $evaluations['parent']['pagu_renja'],
                    'pagu_rka' => $evaluations['parent']['pagu_rka'],
                    'selisih' => $evaluations['parent']['selisih'],
                ];
            }, 3);
        } catch (\Throwable $e) {
            $this->writeLog(
                $penelitianID,
                'PEMERIKSAAN_GAGAL',
                $user->userID,
                sprintf('Penelitian Bagian D.1 gagal: %s', $e->getMessage()),
                [
                    'bagian' => 'D1',
                    'exception' => get_class($e),
                ]
            );

            throw $e;
        }
    }

    /**
     * Hitung D.1 secara deterministic.
     */
    private function evaluatePartD1(object $penelitian, array $documents): array
    {
        $allKroCodes = collect(self::PART_D1_GROUPS)
            ->flatMap(fn (array $group) => $group['kro_codes'])
            ->unique()
            ->values()
            ->all();

        $rkaByKro = $this->rkaScopedQuery($penelitian, $documents['RKA'])
            ->whereIn('kode_kro', $allKroCodes)
            ->select([
                'kode_kro',
                DB::raw('SUM(COALESCE(jumlah_biaya, 0)) AS total'),
            ])
            ->groupBy('kode_kro')
            ->get()
            ->mapWithKeys(function ($row) {
                return [
                    trim((string) $row->kode_kro) => (int) ($row->total ?? 0),
                ];
            });

        $renjaByKro = collect();

        if (!blank($documents['RENJA'])) {
            $renjaByKro = $this->renjaScopedQuery($penelitian, $documents['RENJA'])
                ->whereIn('kode_kro', $allKroCodes)
                ->select([
                    'kode_kro',
                    DB::raw('SUM(COALESCE(alokasi_komponen_0, 0)) * 1000 AS total'),
                ])
                ->groupBy('kode_kro')
                ->get()
                ->mapWithKeys(function ($row) {
                    return [
                        trim((string) $row->kode_kro) => (int) ($row->total ?? 0),
                    ];
                });
        }

        $children = [];
        $totalRenja = 0;
        $totalRka = 0;

        foreach (self::PART_D1_GROUPS as $kodeBaris => $definition) {
            $codes = (array) $definition['kro_codes'];

            $paguRenja = 0;
            $paguRka = 0;
            $renjaCodesFound = [];
            $rkaCodesFound = [];

            foreach ($codes as $code) {
                $renjaAmount = (int) $renjaByKro->get($code, 0);
                $rkaAmount = (int) $rkaByKro->get($code, 0);

                $paguRenja += $renjaAmount;
                $paguRka += $rkaAmount;

                if ($renjaByKro->has($code)) {
                    $renjaCodesFound[] = $code;
                }

                if ($rkaByKro->has($code)) {
                    $rkaCodesFound[] = $code;
                }
            }

            $totalRenja += $paguRenja;
            $totalRka += $paguRka;

            $children[$kodeBaris] = [
                'pagu_renja' => $paguRenja,
                'pagu_rka' => $paguRka,
                'selisih' => $paguRka - $paguRenja,
                'penjelasan' => $this->buildPartD1Explanation(
                    (string) $definition['short_label'],
                    $codes,
                    $paguRenja,
                    $paguRka,
                    !blank($documents['RENJA'])
                ),
                'metadata' => [
                    'kro_codes' => $codes,
                    'renja_codes_found' => $renjaCodesFound,
                    'rka_codes_found' => $rkaCodesFound,
                    'renja_allocation_source' => 'alokasi_komponen_0',
                    'renja_multiplier' => 1000,
                ],
            ];
        }

        return [
            'parent' => [
                'pagu_renja' => $totalRenja,
                'pagu_rka' => $totalRka,
                'selisih' => $totalRka - $totalRenja,
                'penjelasan' => $this->buildPartD1Explanation(
                    'Belanja TIK',
                    $allKroCodes,
                    $totalRenja,
                    $totalRka,
                    !blank($documents['RENJA'])
                ),
                'metadata' => [
                    'kro_codes' => $allKroCodes,
                    'aggregate' => true,
                    'renja_allocation_source' => 'alokasi_komponen_0',
                    'renja_multiplier' => 1000,
                ],
            ],
            'children' => $children,
        ];
    }

    /**
     * PENJELASAN D.1 hanya menerangkan perbedaan atau tidak adanya alokasi.
     */
    private function buildPartD1Explanation(
        string $label,
        array $kroCodes,
        int $paguRenja,
        int $paguRka,
        bool $renjaSelected
    ): string {
        $codes = implode('/', $kroCodes);

        if (!$renjaSelected) {
            return sprintf(
                'Dokumen RENJA belum dipilih sehingga Pagu RENJA untuk %s (%s) ditampilkan Rp0 dan belum dapat dibandingkan.',
                $label,
                $codes
            );
        }

        if ($paguRenja === 0 && $paguRka === 0) {
            return sprintf(
                'Tidak terdapat alokasi %s (%s) pada RENJA maupun RKA.',
                $label,
                $codes
            );
        }

        if ($paguRenja === 0 && $paguRka > 0) {
            return sprintf(
                'Terdapat alokasi %s (%s) pada RKA sebesar %s, tetapi tidak terdapat alokasi pada RENJA.',
                $label,
                $codes,
                $this->formatRupiah($paguRka)
            );
        }

        if ($paguRenja > 0 && $paguRka === 0) {
            return sprintf(
                'Terdapat alokasi %s (%s) pada RENJA sebesar %s, tetapi tidak terdapat alokasi pada RKA.',
                $label,
                $codes,
                $this->formatRupiah($paguRenja)
            );
        }

        if ($paguRenja === $paguRka) {
            // Sesuai rule: tidak perlu penjelasan jika tidak ada selisih.
            return '';
        }

        return sprintf(
            'Terdapat selisih %s (%s) sebesar %s (RKA - RENJA).',
            $label,
            $codes,
            $this->formatRupiah($paguRka - $paguRenja)
        );
    }

    /**
     * Upsert D.1. Angka user tidak dipakai; hanya PENJELASAN user yang
     * dipertahankan pada rerun.
     */
    private function upsertPartD1Result(
        int $penelitianID,
        string $kodeBaris,
        int $urutan,
        int $level,
        ?int $parentHasilNilaiID,
        string $uraian,
        array $evaluation
    ): int {
        $existing = DB::table('penelitian_hasil_nilai')
            ->where('penelitianID', $penelitianID)
            ->where('bagian', 'D1')
            ->where('kode_baris', $kodeBaris)
            ->first();

        $payload = [
            'parentHasilNilaiID' => $parentHasilNilaiID,
            'urutan' => $urutan,
            'level_baris' => $level,
            'uraian' => $uraian,
            'pagu_renja_sistem' => (int) $evaluation['pagu_renja'],
            'pagu_rka_sistem' => (int) $evaluation['pagu_rka'],
            'selisih' => (int) $evaluation['selisih'],
            'penjelasan_sistem' => (string) $evaluation['penjelasan'],
            'metadata_json' => json_encode(
                $evaluation['metadata'] ?? [],
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            ),
            'updated_at' => now(),
        ];

        if ($existing) {
            DB::table('penelitian_hasil_nilai')
                ->where('hasilNilaiID', $existing->hasilNilaiID)
                ->update($payload);

            return (int) $existing->hasilNilaiID;
        }

        return (int) DB::table('penelitian_hasil_nilai')->insertGetId([
            'penelitianID' => $penelitianID,
            'bagian' => 'D1',
            'kode_baris' => $kodeBaris,
            ...$payload,
            'pagu_renja_user' => null,
            'pagu_rka_user' => null,
            'penjelasan_user' => null,
            'created_at' => now(),
        ]);
    }


    /**
     * Simpan override PENJELASAN D.2 selama DRAFT.
     *
     * Angka hasil sistem tidak dapat diedit user.
     */
    public function savePartD2Overrides(
        int $penelitianID,
        array $payload,
        User $user
    ): void {
        if (empty($payload)) {
            return;
        }

        DB::transaction(function () use ($penelitianID, $payload, $user) {
            $penelitian = DB::table('penelitian')
                ->where('penelitianID', $penelitianID)
                ->lockForUpdate()
                ->first();

            if (!$penelitian) {
                throw new \RuntimeException('Penelitian tidak ditemukan.');
            }

            if ($penelitian->status !== 'DRAFT') {
                throw new \RuntimeException('Penelitian sudah FINAL dan hasil D.2 tidak dapat diubah.');
            }

            $rows = DB::table('penelitian_hasil_d2')
                ->where('penelitianID', $penelitianID)
                ->get()
                ->keyBy('kode_baris');

            $changed = 0;
            $changedRows = [];

            foreach ($payload as $kodeBaris => $input) {
                $kodeBaris = (string) $kodeBaris;

                if (!$rows->has($kodeBaris)) {
                    continue;
                }

                $row = $rows->get($kodeBaris);
                $input = is_array($input) ? $input : [];

                if (array_key_exists('penjelasan', $input)) {
                    $submitted = trim((string) ($input['penjelasan'] ?? ''));
                } else {
                    $submitted = $row->penjelasan_user !== null
                        ? (string) $row->penjelasan_user
                        : (string) ($row->penjelasan_sistem ?? '');
                }

                $system = trim((string) ($row->penjelasan_sistem ?? ''));
                $userExplanation = $submitted === $system ? null : $submitted;
                $current = $row->penjelasan_user !== null
                    ? (string) $row->penjelasan_user
                    : null;

                if ($userExplanation !== $current) {
                    DB::table('penelitian_hasil_d2')
                        ->where('hasilD2ID', $row->hasilD2ID)
                        ->update([
                            'penjelasan_user' => $userExplanation,
                            'updated_at' => now(),
                        ]);

                    $changed++;
                    $changedRows[] = $kodeBaris;
                }
            }

            if ($changed > 0) {
                $this->writeLog(
                    $penelitianID,
                    'DRAFT_DISIMPAN',
                    $user->userID,
                    sprintf('%s memperbarui PENJELASAN D.2 pada DRAFT penelitian.', $user->name),
                    [
                        'bagian' => 'D2',
                        'jumlah_baris_diubah' => $changed,
                        'kode_baris_diubah' => $changedRows,
                        'field_override' => ['penjelasan_user'],
                        'numeric_override' => false,
                    ]
                );
            }
        }, 3);
    }

    /**
     * Jalankan D.2 Identifikasi Aset Bidang Teknologi Informasi dan Komunikasi.
     *
     * Gemini HANYA melakukan klasifikasi semantik:
     * - nomenklatur RKBMN -> kategori aset;
     * - detail RKA -> kategori aset + jenis alokasi pemeliharaan/pengadaan.
     *
     * Seluruh volume dan pagu dihitung deterministic oleh aplikasi.
     * Jika Gemini gagal, engine tetap berjalan dengan fallback keyword.
     */
    public function runPartD2(int $penelitianID, User $user): array
    {
        $penelitian = DB::table('penelitian')
            ->where('penelitianID', $penelitianID)
            ->first();

        if (!$penelitian) {
            throw new \RuntimeException('Penelitian tidak ditemukan.');
        }

        if ($penelitian->status !== 'DRAFT') {
            throw new \RuntimeException('Penelitian sudah FINAL dan tidak dapat dijalankan ulang.');
        }

        $this->writeLog(
            $penelitianID,
            'PEMERIKSAAN_DIMULAI',
            $user->userID,
            sprintf('%s menjalankan penelitian Bagian D.2 pada "%s".', $user->name, $penelitian->nama_penelitian),
            ['bagian' => 'D2']
        );

        try {
            return DB::transaction(function () use ($penelitianID, $user) {
                $penelitian = DB::table('penelitian')
                    ->where('penelitianID', $penelitianID)
                    ->lockForUpdate()
                    ->first();

                if (!$penelitian) {
                    throw new \RuntimeException('Penelitian tidak ditemukan.');
                }

                if ($penelitian->status !== 'DRAFT') {
                    throw new \RuntimeException('Penelitian sudah FINAL dan tidak dapat dijalankan ulang.');
                }

                $documents = $this->selectedDocuments($penelitianID);

                if (blank($documents['RKA'])) {
                    throw new \RuntimeException('Dokumen RKA belum dipilih pada workspace penelitian.');
                }

                $classifier = app(GeminiD2ClassificationService::class);

                // =====================================================
                // 1. RKBMN: identifikasi kategori aset.
                // =====================================================
                $rkbmnPemeliharaan = collect();
                $rkbmnPengadaan = collect();

                if (!blank($documents['RKBMN'])) {
                    $rkbmnPemeliharaan = $this->rkbmnScopedRows(
                        'rkbmn_pemeliharaan',
                        $penelitian,
                        $documents['RKBMN']
                    );

                    $rkbmnPengadaan = $this->rkbmnScopedRows(
                        'rkbmn_pengadaan',
                        $penelitian,
                        $documents['RKBMN']
                    );
                }

                $rkbmnClassifierItems = [];

                foreach ($rkbmnPemeliharaan as $row) {
                    $rkbmnClassifierItems[] = [
                        'id' => 'PLH:' . $row->rkbmn_pemeliharaanID,
                        'source' => 'RKBMN_PEMELIHARAAN',
                        'text' => (string) $row->nama_barang,
                    ];
                }

                foreach ($rkbmnPengadaan as $row) {
                    $rkbmnClassifierItems[] = [
                        'id' => 'PGD:' . $row->rkbmn_pengadaanID,
                        'source' => 'RKBMN_PENGADAAN',
                        'text' => (string) $row->kategori_barang,
                    ];
                }

                $rkbmnClassification = $classifier->classifyRkbmnItems(
                    $rkbmnClassifierItems
                );

                $categoryDefinitions = $this->d2BaseCategoryDefinitions();
                $metrics = $this->d2EmptyMetrics($categoryDefinitions);

                // Pemeliharaan RKBMN adalah SATU-SATUNYA sumber angka
                // "PEMELIHARAAN RKBMN (Unit)".
                foreach ($rkbmnPemeliharaan as $row) {
                    $classification = $rkbmnClassification['items'][
                        'PLH:' . $row->rkbmn_pemeliharaanID
                    ] ?? null;

                    $categoryKey = $this->resolveD2Category(
                        $classification,
                        $categoryDefinitions
                    );

                    if ($categoryKey === null) {
                        continue;
                    }

                    if (!isset($metrics[$categoryKey])) {
                        $metrics[$categoryKey] = $this->d2MetricTemplate();
                    }

                    $metrics[$categoryKey]['rkbmn'] += (float) $row->jumlah;
                    $metrics[$categoryKey]['rkbmn_sources'][] = [
                        'id' => (string) $row->rkbmn_pemeliharaanID,
                        'nama_barang' => (string) $row->nama_barang,
                        'satuan' => (string) $row->satuan,
                    ];

                    $metrics[$categoryKey]['classification_sources'][] =
                        (string) ($classification['source'] ?? 'SYSTEM');
                }

                // rkbmn_pengadaan hanya membantu IDENTIFIKASI kategori/dynamic row.
                // Jumlah pengadaan RKBMN tidak dimasukkan ke kolom pemeliharaan.
                foreach ($rkbmnPengadaan as $row) {
                    $classification = $rkbmnClassification['items'][
                        'PGD:' . $row->rkbmn_pengadaanID
                    ] ?? null;

                    $categoryKey = $this->resolveD2Category(
                        $classification,
                        $categoryDefinitions
                    );

                    if ($categoryKey === null) {
                        continue;
                    }

                    if (!isset($metrics[$categoryKey])) {
                        $metrics[$categoryKey] = $this->d2MetricTemplate();
                    }

                    $metrics[$categoryKey]['rkbmn_pengadaan_sources'][] = [
                        'id' => (string) $row->rkbmn_pengadaanID,
                        'kategori_barang' => (string) $row->kategori_barang,
                        'metode_pengadaan' => (string) $row->metode_pengadaan,
                    ];

                    $metrics[$categoryKey]['classification_sources'][] =
                        (string) ($classification['source'] ?? 'SYSTEM');
                }

                // =====================================================
                // 2. RKA: klasifikasi kategori + pemeliharaan/pengadaan.
                // =====================================================
                $rkaRows = $this->rkaScopedQuery(
                    $penelitian,
                    $documents['RKA']
                )->get([
                    'rkaID',
                    'kode_kro',
                    'nama_kro',
                    'kode_ro',
                    'nama_ro',
                    'kode_komponen',
                    'nama_komponen',
                    'kode_subkomponen',
                    'nama_subkomponen',
                    'kode_akun',
                    'nama_akun',
                    'kelompok_detail',
                    'uraian_detail',
                    'volume',
                    'satuan_volume',
                    'jumlah_biaya',
                ]);

                $knownCategories = collect($categoryDefinitions)
                    ->map(function (array $definition, string $key) {
                        return [
                            'key' => $key,
                            'group' => $definition['group'],
                            'label' => $definition['label'],
                        ];
                    })
                    ->values()
                    ->all();

                $rkaClassifierItems = $rkaRows->map(function ($row) {
                    return [
                        'id' => (string) $row->rkaID,
                        'kode_kro' => (string) ($row->kode_kro ?? ''),
                        'nama_kro' => (string) ($row->nama_kro ?? ''),
                        'kode_ro' => (string) ($row->kode_ro ?? ''),
                        'nama_ro' => (string) ($row->nama_ro ?? ''),
                        'kode_komponen' => (string) ($row->kode_komponen ?? ''),
                        'nama_komponen' => (string) ($row->nama_komponen ?? ''),
                        'nama_subkomponen' => (string) ($row->nama_subkomponen ?? ''),
                        'kode_akun' => (string) ($row->kode_akun ?? ''),
                        'nama_akun' => (string) ($row->nama_akun ?? ''),
                        'kelompok_detail' => (string) ($row->kelompok_detail ?? ''),
                        'uraian_detail' => (string) ($row->uraian_detail ?? ''),
                    ];
                })->all();

                $rkaClassification = $classifier->classifyRkaItems(
                    $rkaClassifierItems,
                    $knownCategories
                );

                foreach ($rkaRows as $row) {
                    $classification = $rkaClassification['items'][
                        (string) $row->rkaID
                    ] ?? null;

                    $categoryKey = $this->resolveD2Category(
                        $classification,
                        $categoryDefinitions
                    );

                    if ($categoryKey === null) {
                        continue;
                    }

                    $allocationType = (string) (
                        $classification['allocation_type'] ?? 'NONE'
                    );

                    if (!in_array($allocationType, ['PEMELIHARAAN', 'PENGADAAN'], true)) {
                        continue;
                    }

                    if (!isset($metrics[$categoryKey])) {
                        $metrics[$categoryKey] = $this->d2MetricTemplate();
                    }

                    $volume = (float) ($row->volume ?? 0);
                    $pagu = (float) ($row->jumlah_biaya ?? 0);

                    if ($allocationType === 'PEMELIHARAAN') {
                        $metrics[$categoryKey]['maintenance_vol'] += $volume;
                        $metrics[$categoryKey]['maintenance_pagu'] += $pagu;
                    } else {
                        $metrics[$categoryKey]['procurement_vol'] += $volume;
                        $metrics[$categoryKey]['procurement_pagu'] += $pagu;
                    }

                    $metrics[$categoryKey]['rka_sources'][] = [
                        'rkaID' => (string) $row->rkaID,
                        'allocation_type' => $allocationType,
                        'volume' => $volume,
                        'satuan_volume' => (string) ($row->satuan_volume ?? ''),
                        'jumlah_biaya' => $pagu,
                        'uraian_detail' => (string) ($row->uraian_detail ?? ''),
                    ];

                    $metrics[$categoryKey]['classification_sources'][] =
                        (string) ($classification['source'] ?? 'SYSTEM');
                }

                // =====================================================
                // 3. Susun row sesuai format CHP dan hitung parent.
                // =====================================================
                $rows = $this->buildPartD2Rows(
                    $categoryDefinitions,
                    $metrics,
                    [
                        'rkbmn_warning' => $rkbmnClassification['warning'] ?? null,
                        'rka_warning' => $rkaClassification['warning'] ?? null,
                        'rkbmn_document_selected' => !blank($documents['RKBMN']),
                        'rkbmn_pemeliharaan_rows' => $rkbmnPemeliharaan->count(),
                        'rkbmn_pengadaan_rows' => $rkbmnPengadaan->count(),
                    ]
                );

                $resultIDs = [];
                $generatedCodes = [];

                foreach ($rows as $row) {
                    $parentID = null;

                    if (!empty($row['parent'])) {
                        $parentID = $resultIDs[$row['parent']] ?? null;
                    }

                    $hasilD2ID = $this->upsertPartD2Result(
                        $penelitianID,
                        $row,
                        $parentID
                    );

                    $resultIDs[$row['kode']] = $hasilD2ID;
                    $generatedCodes[] = $row['kode'];
                }

                DB::table('penelitian_hasil_d2')
                    ->where('penelitianID', $penelitianID)
                    ->whereNotIn('kode_baris', $generatedCodes)
                    ->delete();

                $parents = collect($rows)->where('level', 0);
                $totalMaintenancePagu = (float) $parents->sum(
                    fn ($row) => $row['metrics']['maintenance_pagu']
                );
                $totalProcurementPagu = (float) $parents->sum(
                    fn ($row) => $row['metrics']['procurement_pagu']
                );

                $this->writeLog(
                    $penelitianID,
                    'PEMERIKSAAN_BERHASIL',
                    $user->userID,
                    sprintf('%s berhasil menghasilkan penelitian Bagian D.2.', $user->name),
                    [
                        'bagian' => 'D2',
                        'jumlah_baris' => count($rows),
                        'jumlah_dynamic' => collect($rows)
                            ->where('is_dynamic', true)
                            ->count(),
                        'alokasi_pemeliharaan_pagu' => $totalMaintenancePagu,
                        'alokasi_pengadaan_pagu' => $totalProcurementPagu,
                        'gemini_rkbmn' => (bool) ($rkbmnClassification['used_gemini'] ?? false),
                        'gemini_rka' => (bool) ($rkaClassification['used_gemini'] ?? false),
                        'fallback_rkbmn' => (bool) ($rkbmnClassification['used_fallback'] ?? false),
                        'fallback_rka' => (bool) ($rkaClassification['used_fallback'] ?? false),
                    ]
                );

                return [
                    'jumlah_baris' => count($rows),
                    'jumlah_dynamic' => collect($rows)
                        ->where('is_dynamic', true)
                        ->count(),
                    'alokasi_pemeliharaan_pagu' => $totalMaintenancePagu,
                    'alokasi_pengadaan_pagu' => $totalProcurementPagu,
                    'gemini_digunakan' =>
                        (bool) ($rkbmnClassification['used_gemini'] ?? false)
                        || (bool) ($rkaClassification['used_gemini'] ?? false),
                    'fallback_digunakan' =>
                        (bool) ($rkbmnClassification['used_fallback'] ?? false)
                        || (bool) ($rkaClassification['used_fallback'] ?? false),
                ];
            }, 3);
        } catch (\Throwable $e) {
            $this->writeLog(
                $penelitianID,
                'PEMERIKSAAN_GAGAL',
                $user->userID,
                sprintf('Penelitian Bagian D.2 gagal: %s', $e->getMessage()),
                [
                    'bagian' => 'D2',
                    'exception' => get_class($e),
                ]
            );

            throw $e;
        }
    }

    private function d2BaseCategoryDefinitions(): array
    {
        $definitions = [];

        foreach (self::PART_D2_FIXED_CATEGORIES as $key => $definition) {
            $definitions[$key] = [
                'key' => $key,
                'group' => $definition['group'],
                'label' => $definition['label'],
                'order' => $definition['order'],
                'is_dynamic' => false,
            ];
        }

        return $definitions;
    }

    private function d2EmptyMetrics(array $definitions): array
    {
        $metrics = [];

        foreach (array_keys($definitions) as $key) {
            $metrics[$key] = $this->d2MetricTemplate();
        }

        return $metrics;
    }

    private function d2MetricTemplate(): array
    {
        return [
            'rkbmn' => 0.0,
            'maintenance_vol' => 0.0,
            'maintenance_pagu' => 0.0,
            'procurement_vol' => 0.0,
            'procurement_pagu' => 0.0,
            'rkbmn_sources' => [],
            'rkbmn_pengadaan_sources' => [],
            'rka_sources' => [],
            'classification_sources' => [],
        ];
    }

    /**
     * Resolve classification menjadi category key internal.
     *
     * Dynamic key dibuat stabil dari group + label normalized.
     */
    private function resolveD2Category(
        ?array $classification,
        array &$definitions
    ): ?string {
        if (
            !$classification
            || !in_array(($classification['group'] ?? null), ['PPD', 'ATB'], true)
        ) {
            return null;
        }

        $group = (string) $classification['group'];
        $rawKey = trim((string) ($classification['category_key'] ?? ''));
        $label = $this->normalizeD2DynamicLabel(
            (string) ($classification['category_label'] ?? '')
        );

        if (isset($definitions[$rawKey])) {
            return $rawKey;
        }

        if ($rawKey !== 'DYNAMIC' && isset(self::PART_D2_FIXED_CATEGORIES[$rawKey])) {
            if (!isset($definitions[$rawKey])) {
                $fixed = self::PART_D2_FIXED_CATEGORIES[$rawKey];
                $definitions[$rawKey] = [
                    'key' => $rawKey,
                    'group' => $fixed['group'],
                    'label' => $fixed['label'],
                    'order' => $fixed['order'],
                    'is_dynamic' => false,
                ];
            }

            return $rawKey;
        }

        if ($label === '') {
            return null;
        }

        $key = sprintf(
            'D2.%s.DYN.%s',
            $group,
            strtoupper(substr(sha1($group . '|' . mb_strtolower($label)), 0, 12))
        );

        if (!isset($definitions[$key])) {
            $definitions[$key] = [
                'key' => $key,
                'group' => $group,
                'label' => $label,
                'order' => 900,
                'is_dynamic' => true,
            ];
        }

        return $key;
    }

    private function normalizeD2DynamicLabel(string $label): string
    {
        $label = trim($label);
        $label = preg_replace('/\s+/u', ' ', $label) ?? $label;

        return mb_substr($label, 0, 450);
    }

    /**
     * Scope RKBMN:
     * 1. documentID + tahun;
     * 2. kode_satker;
     * 3. jika kode tidak ditemukan, fallback nama_satker exact setelah trim/case.
     */
    private function rkbmnScopedRows(
        string $table,
        object $penelitian,
        string $documentID
    ): Collection {
        if (!in_array($table, ['rkbmn_pemeliharaan', 'rkbmn_pengadaan'], true)) {
            throw new \InvalidArgumentException('Tabel RKBMN tidak valid.');
        }

        $base = DB::table($table)
            ->where('documentID', $documentID)
            ->where('tahun_anggaran', $penelitian->tahun_anggaran);

        $byCode = (clone $base)
            ->where('kode_satker', $penelitian->kode_satker)
            ->get();

        if ($byCode->isNotEmpty()) {
            return $byCode;
        }

        return (clone $base)
            ->whereRaw('LOWER(TRIM(nama_satker)) = LOWER(TRIM(?))', [
                $penelitian->nama_satker,
            ])
            ->get();
    }

    private function buildPartD2Rows(
        array $definitions,
        array $metrics,
        array $context
    ): array {
        $rows = [];
        $order = 0;

        foreach (['PPD', 'ATB'] as $group) {
            $children = collect($definitions)
                ->filter(function (array $definition, string $key) use ($group, $metrics) {
                    if ($definition['group'] !== $group) {
                        return false;
                    }

                    // Kategori FIXED selalu tampil agar struktur CHP konsisten.
                    if (!(bool) ($definition['is_dynamic'] ?? false)) {
                        return true;
                    }

                    // Kategori DYNAMIC hanya tampil jika minimal satu angka
                    // yang memang ditampilkan pada D.2 bernilai > 0.
                    //
                    // rkbmn_pengadaan_sources sengaja tidak menjadi syarat tampil
                    // karena jumlah RKBMN Pengadaan bukan kolom numerik D.2.
                    $metric = $metrics[$key] ?? $this->d2MetricTemplate();

                    return
                        (float) ($metric['rkbmn'] ?? 0) > 0
                        || (float) ($metric['maintenance_vol'] ?? 0) > 0
                        || (float) ($metric['maintenance_pagu'] ?? 0) > 0
                        || (float) ($metric['procurement_vol'] ?? 0) > 0
                        || (float) ($metric['procurement_pagu'] ?? 0) > 0;
                })
                ->sort(function (array $a, array $b) {
                    if ($a['order'] === $b['order']) {
                        return strcmp(
                            mb_strtolower($a['label']),
                            mb_strtolower($b['label'])
                        );
                    }

                    return $a['order'] <=> $b['order'];
                });

            $parentCode = $group === 'PPD' ? 'D2.PPD' : 'D2.ATB';
            $parentLabel = $group === 'PPD'
                ? 'Perangkat Pengolah Data'
                : 'Aset Tak Berwujud';

            $parentMetrics = $this->d2MetricTemplate();
            $childRows = [];

            foreach ($children as $key => $definition) {
                $metric = $metrics[$key] ?? $this->d2MetricTemplate();

                foreach ([
                    'rkbmn',
                    'maintenance_vol',
                    'maintenance_pagu',
                    'procurement_vol',
                    'procurement_pagu',
                ] as $field) {
                    $parentMetrics[$field] += (float) $metric[$field];
                }

                $order++;
                $explanation = $this->buildD2Explanation(
                    $definition['label'],
                    $metric,
                    $context
                );

                $childRows[] = [
                    'kode' => $key,
                    'parent' => $parentCode,
                    'urutan' => $order + 1, // parent akan ditempatkan sebelum children.
                    'level' => 1,
                    'kelompok' => $group,
                    'uraian' => $definition['label'],
                    'is_dynamic' => (bool) $definition['is_dynamic'],
                    'metrics' => $metric,
                    'penjelasan' => $explanation,
                    'classification_source' =>
                        $this->d2ClassificationSource($metric['classification_sources']),
                    'metadata' => [
                        'definition' => $definition,
                        'rkbmn_sources' => $metric['rkbmn_sources'],
                        'rkbmn_pengadaan_sources' => $metric['rkbmn_pengadaan_sources'],
                        'rka_sources' => $metric['rka_sources'],
                    ],
                ];
            }

            // Recalculate actual sequential order: parent first.
            $parentOrder = empty($rows) ? 1 : (max(array_column($rows, 'urutan')) + 1);

            foreach ($childRows as &$childRow) {
                $childRow['urutan'] = ++$parentOrder;
            }
            unset($childRow);

            $parentExplanation = $this->buildD2ParentExplanation(
                $parentLabel,
                $childRows,
                $context
            );

            $rows[] = [
                'kode' => $parentCode,
                'parent' => null,
                'urutan' => $parentOrder - count($childRows),
                'level' => 0,
                'kelompok' => $group,
                'uraian' => $parentLabel,
                'is_dynamic' => false,
                'metrics' => $parentMetrics,
                'penjelasan' => $parentExplanation,
                'classification_source' => 'AGGREGATE',
                'metadata' => [
                    'aggregate' => true,
                    'children' => array_column($childRows, 'kode'),
                    'dynamic_zero_rows_hidden' => true,
                    'dynamic_visibility_rule' => 'SHOW_IF_ANY_DISPLAYED_NUMERIC_VALUE_GT_0',
                    'context' => $context,
                ],
            ];

            foreach ($childRows as $childRow) {
                $rows[] = $childRow;
            }

            // Normalize order after each group to avoid gaps/duplicates.
            foreach ($rows as $idx => &$row) {
                $row['urutan'] = $idx + 1;
            }
            unset($row);
        }

        return $rows;
    }

    private function buildD2Explanation(
        string $label,
        array $metric,
        array $context
    ): string {
        $rkbmn = (float) $metric['rkbmn'];
        $maintenanceVol = (float) $metric['maintenance_vol'];
        $maintenancePagu = (float) $metric['maintenance_pagu'];
        $procurementVol = (float) $metric['procurement_vol'];
        $procurementPagu = (float) $metric['procurement_pagu'];

        $details = [];
        $warning = false;

        $rkbmnDocumentSelected = (bool) ($context['rkbmn_document_selected'] ?? false);
        $rkbmnScopedRows = (int) ($context['rkbmn_pemeliharaan_rows'] ?? 0);

        if (!$rkbmnDocumentSelected && ($maintenanceVol > 0 || $procurementVol > 0)) {
            $details[] = 'Dokumen RKBMN belum dipilih sehingga volume Pemeliharaan RKBMN ditampilkan 0 dan perlu dikonfirmasi.';
        } elseif (
            $rkbmnDocumentSelected
            && $rkbmnScopedRows === 0
            && ($maintenanceVol > 0 || $procurementVol > 0)
        ) {
            $details[] = 'Tidak ditemukan baris RKBMN Pemeliharaan untuk Satker ini pada dokumen RKBMN terpilih; volume RKBMN ditampilkan 0.';
        }

        if ($rkbmnDocumentSelected) {
            if ($rkbmn == 0.0 && $maintenanceVol > 0) {
                $warning = true;
                $details[] = sprintf(
                    'Terdapat alokasi pemeliharaan %.2f unit senilai %s, tetapi aset tidak ditemukan pada RKBMN Pemeliharaan.',
                    $maintenanceVol,
                    $this->formatRupiah((int) round($maintenancePagu))
                );
            } elseif ($maintenanceVol > $rkbmn) {
                $warning = true;
                $details[] = sprintf(
                    'Alokasi pemeliharaan RKA %.2f unit melebihi volume RKBMN %.2f unit sebesar %.2f unit.',
                    $maintenanceVol,
                    $rkbmn,
                    $maintenanceVol - $rkbmn
                );
            } elseif ($rkbmn > 0 && $maintenanceVol == 0.0) {
                // RKBMN memiliki aset, tetapi RKA sama sekali tidak menyediakan
                // alokasi pemeliharaan untuk kategori tersebut.
                $details[] = sprintf(
                    'RKBMN mencatat %.2f unit %s, tetapi tidak terdapat alokasi pemeliharaan aset tersebut pada RKA.',
                    $rkbmn,
                    $label
                );
            } elseif ($rkbmn > 0 && $maintenanceVol < $rkbmn) {
                $details[] = sprintf(
                    'Alokasi pemeliharaan RKA hanya mencakup %.2f dari %.2f unit %s yang tercatat pada RKBMN.',
                    $maintenanceVol,
                    $rkbmn,
                    $label
                );
            }
        }

        if ($rkbmnDocumentSelected && $rkbmn > 0 && $procurementVol > 0) {
            $details[] = sprintf(
                'Aset telah tercatat pada RKBMN Pemeliharaan %.2f unit dan Satker mengusulkan tambahan pengadaan %.2f unit senilai %s.',
                $rkbmn,
                $procurementVol,
                $this->formatRupiah((int) round($procurementPagu))
            );
        } elseif ($rkbmnDocumentSelected && $rkbmn == 0.0 && $procurementVol > 0) {
            $details[] = sprintf(
                'Terdapat usulan pengadaan baru %.2f unit senilai %s; kondisi ini tidak otomatis dianggap anomali.',
                $procurementVol,
                $this->formatRupiah((int) round($procurementPagu))
            );
        } elseif (!$rkbmnDocumentSelected && $procurementVol > 0) {
            $details[] = sprintf(
                'Terdapat usulan pengadaan %.2f unit senilai %s, tetapi status pengadaan baru/tambahan belum dapat dibandingkan karena dokumen RKBMN belum dipilih.',
                $procurementVol,
                $this->formatRupiah((int) round($procurementPagu))
            );
        }

        if (empty($details)) {
            return '';
        }

        $summary = $warning
            ? "Ditemukan catatan yang perlu diperhatikan untuk {$label}."
            : "Terdapat catatan perbandingan RKBMN dan RKA untuk {$label}.";

        return $this->formatExplanation($summary, $details);
    }

    private function buildD2ParentExplanation(
        string $parentLabel,
        array $childRows,
        array $context
    ): string {
        $childrenWithNotes = collect($childRows)
            ->filter(fn (array $row) => trim((string) $row['penjelasan']) !== '');

        $details = [];

        foreach ([
            $context['rkbmn_warning'] ?? null,
            $context['rka_warning'] ?? null,
        ] as $warning) {
            if (is_string($warning) && trim($warning) !== '') {
                $details[] = trim($warning);
            }
        }

        if ($childrenWithNotes->isNotEmpty()) {
            $details[] = sprintf(
                'Terdapat catatan pada %d subkategori; lihat rincian pada baris anak.',
                $childrenWithNotes->count()
            );
        }

        if (empty($details)) {
            return '';
        }

        return $this->formatExplanation(
            "Hasil identifikasi {$parentLabel} telah dihitung.",
            $details
        );
    }

    private function d2ClassificationSource(array $sources): string
    {
        $sources = array_values(array_unique(array_filter($sources)));

        if (empty($sources)) {
            return 'SYSTEM';
        }

        if (count($sources) === 1) {
            return mb_substr($sources[0], 0, 20);
        }

        return 'MIXED';
    }

    /**
     * Konversi group internal classifier ke nilai yang diizinkan CHECK database.
     */
    private function d2DatabaseGroup(string $group): string
    {
        return match ($group) {
            'PPD', 'PERANGKAT_PENGOLAH_DATA' => 'PERANGKAT_PENGOLAH_DATA',
            'ATB', 'ASET_TAK_BERWUJUD' => 'ASET_TAK_BERWUJUD',
            default => throw new \RuntimeException(
                "Kelompok D.2 tidak valid untuk database: {$group}"
            ),
        };
    }

    /**
     * `classification_source` database memang sejak desain awal hanya:
     * - FIXED  : kategori fixed CHP;
     * - GEMINI : kategori dinamis yang berasal dari Gemini;
     * - NULL   : kategori dinamis dari fallback/rule deterministic.
     *
     * Nilai internal seperti RULE/FALLBACK/MIXED/AGGREGATE tetap disimpan
     * dalam metadata_json sebagai raw_classification_source, bukan ke kolom
     * yang memiliki CHECK constraint.
     */
    private function d2DatabaseClassificationSource(array $row): ?string
    {
        $isDynamic = (bool) ($row['is_dynamic'] ?? false);

        if (!$isDynamic) {
            return 'FIXED';
        }

        $raw = mb_strtoupper(
            trim((string) ($row['classification_source'] ?? ''))
        );

        if ($raw === 'GEMINI' || str_contains($raw, 'GEMINI')) {
            return 'GEMINI';
        }

        return null;
    }

    private function upsertPartD2Result(
        int $penelitianID,
        array $row,
        ?int $parentHasilD2ID
    ): int {
        $existing = DB::table('penelitian_hasil_d2')
            ->where('penelitianID', $penelitianID)
            ->where('kode_baris', $row['kode'])
            ->first();

        $metric = $row['metrics'];

        $payload = [
            'parentHasilD2ID' => $parentHasilD2ID,
            'urutan' => (int) $row['urutan'],
            'level_baris' => (int) $row['level'],
            // Database CHECK hanya mengizinkan:
            // PERANGKAT_PENGOLAH_DATA / ASET_TAK_BERWUJUD.
            // Internal engine tetap boleh memakai PPD / ATB agar classifier ringkas.
            'kelompok' => $this->d2DatabaseGroup((string) $row['kelompok']),
            'uraian' => (string) $row['uraian'],
            'is_dynamic' => (bool) $row['is_dynamic'],
            'rkbmn_pemeliharaan_unit' => (float) $metric['rkbmn'],
            'alokasi_pemeliharaan_vol' => (float) $metric['maintenance_vol'],
            'alokasi_pemeliharaan_pagu' => (float) $metric['maintenance_pagu'],
            'alokasi_pengadaan_vol' => (float) $metric['procurement_vol'],
            'alokasi_pengadaan_pagu' => (float) $metric['procurement_pagu'],
            'penjelasan_sistem' => (string) $row['penjelasan'],
            // Database CHECK hanya mengizinkan NULL / FIXED / GEMINI.
            // FIXED = kategori fixed CHP, GEMINI = kategori dinamis yang memang
            // berasal dari semantic classification Gemini, NULL = kategori
            // dinamis yang hanya ditemukan melalui fallback/rule.
            'classification_source' => $this->d2DatabaseClassificationSource($row),
            'metadata_json' => json_encode(
                array_merge(
                    $row['metadata'] ?? [],
                    [
                        'internal_group' => (string) ($row['kelompok'] ?? ''),
                        'raw_classification_source' => (string) ($row['classification_source'] ?? ''),
                    ]
                ),
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            ),
            'updated_at' => now(),
        ];

        if ($existing) {
            DB::table('penelitian_hasil_d2')
                ->where('hasilD2ID', $existing->hasilD2ID)
                ->update($payload);

            return (int) $existing->hasilD2ID;
        }

        return (int) DB::table('penelitian_hasil_d2')->insertGetId([
            'penelitianID' => $penelitianID,
            'kode_baris' => (string) $row['kode'],
            ...$payload,
            'penjelasan_user' => null,
            'created_at' => now(),
        ]);
    }


    /**
     * Simpan override STATUS dan PENJELASAN Bagian E selama DRAFT.
     */
    public function savePartEOverrides(
        int $penelitianID,
        array $payload,
        User $user
    ): void {
        if (empty($payload)) {
            return;
        }

        DB::transaction(function () use ($penelitianID, $payload, $user) {
            $penelitian = DB::table('penelitian')
                ->where('penelitianID', $penelitianID)
                ->lockForUpdate()
                ->first();

            if (!$penelitian) {
                throw new \RuntimeException('Penelitian tidak ditemukan.');
            }

            if ($penelitian->status !== 'DRAFT') {
                throw new \RuntimeException('Penelitian sudah FINAL dan hasil Bagian E tidak dapat diubah.');
            }

            $rows = DB::table('penelitian_hasil_status')
                ->where('penelitianID', $penelitianID)
                ->where('bagian', 'E')
                ->get()
                ->keyBy('kode_baris');

            $changed = 0;
            $changedRows = [];

            foreach ($payload as $kodeBaris => $input) {
                $kodeBaris = (string) $kodeBaris;

                if (!$rows->has($kodeBaris)) {
                    // Input browser tidak boleh membuat baris E baru.
                    continue;
                }

                $row = $rows->get($kodeBaris);
                $input = is_array($input) ? $input : [];

                $submittedStatus = trim((string) (
                    $input['status']
                    ?? $row->status_user
                    ?? $row->status_sistem
                    ?? ''
                ));

                if (!in_array($submittedStatus, self::PART_E_STATUSES, true)) {
                    throw new \RuntimeException(
                        "Status Bagian E pada {$kodeBaris} tidak valid."
                    );
                }

                $statusUser = $submittedStatus === (string) $row->status_sistem
                    ? null
                    : $submittedStatus;

                if (array_key_exists('penjelasan', $input)) {
                    $submittedExplanation = trim((string) ($input['penjelasan'] ?? ''));
                } else {
                    $submittedExplanation = $row->penjelasan_user !== null
                        ? (string) $row->penjelasan_user
                        : (string) ($row->penjelasan_sistem ?? '');
                }

                $systemExplanation = trim((string) ($row->penjelasan_sistem ?? ''));

                $explanationUser = $submittedExplanation === $systemExplanation
                    ? null
                    : $submittedExplanation;

                $currentStatusUser = $row->status_user !== null
                    ? (string) $row->status_user
                    : null;

                $currentExplanationUser = $row->penjelasan_user !== null
                    ? (string) $row->penjelasan_user
                    : null;

                if (
                    $statusUser !== $currentStatusUser
                    || $explanationUser !== $currentExplanationUser
                ) {
                    DB::table('penelitian_hasil_status')
                        ->where('hasilStatusID', $row->hasilStatusID)
                        ->update([
                            'status_user' => $statusUser,
                            'penjelasan_user' => $explanationUser,
                            'updated_at' => now(),
                        ]);

                    $changed++;
                    $changedRows[] = $kodeBaris;
                }
            }

            if ($changed > 0) {
                $this->writeLog(
                    $penelitianID,
                    'DRAFT_DISIMPAN',
                    $user->userID,
                    sprintf('%s memperbarui override Bagian E pada DRAFT penelitian.', $user->name),
                    [
                        'bagian' => 'E',
                        'jumlah_baris_diubah' => $changed,
                        'kode_baris_diubah' => $changedRows,
                        'field_override' => [
                            'status_user',
                            'penjelasan_user',
                        ],
                    ]
                );
            }
        }, 3);
    }

    /**
     * Jalankan Research Engine Bagian E - Kelengkapan Dokumen Pendukung.
     *
     * Tidak menggunakan Gemini. Penilaian hanya berdasarkan dokumen yang telah
     * dipilih pada workspace penelitian.
     */
    public function runPartE(int $penelitianID, User $user): array
    {
        $penelitian = DB::table('penelitian')
            ->where('penelitianID', $penelitianID)
            ->first();

        if (!$penelitian) {
            throw new \RuntimeException('Penelitian tidak ditemukan.');
        }

        if ($penelitian->status !== 'DRAFT') {
            throw new \RuntimeException('Penelitian sudah FINAL dan tidak dapat dijalankan ulang.');
        }

        $this->writeLog(
            $penelitianID,
            'PEMERIKSAAN_DIMULAI',
            $user->userID,
            sprintf('%s menjalankan penelitian Bagian E pada "%s".', $user->name, $penelitian->nama_penelitian),
            ['bagian' => 'E']
        );

        try {
            return DB::transaction(function () use ($penelitianID, $user) {
                $penelitian = DB::table('penelitian')
                    ->where('penelitianID', $penelitianID)
                    ->lockForUpdate()
                    ->first();

                if (!$penelitian) {
                    throw new \RuntimeException('Penelitian tidak ditemukan.');
                }

                if ($penelitian->status !== 'DRAFT') {
                    throw new \RuntimeException('Penelitian sudah FINAL dan tidak dapat dijalankan ulang.');
                }

                $documents = $this->selectedDocuments($penelitianID);
                $snapshots = $this->selectedDocumentSnapshotsByRole($penelitianID);

                $evaluations = $this->evaluatePartE(
                    $documents,
                    $snapshots
                );

                $generatedCodes = [];

                foreach (self::PART_E_ROWS as $kodeBaris => $definition) {
                    $this->upsertPartEResult(
                        $penelitianID,
                        $kodeBaris,
                        (int) $definition['urutan'],
                        (string) $definition['uraian'],
                        $evaluations[$kodeBaris]
                    );

                    $generatedCodes[] = $kodeBaris;
                }

                DB::table('penelitian_hasil_status')
                    ->where('penelitianID', $penelitianID)
                    ->where('bagian', 'E')
                    ->whereNotIn('kode_baris', $generatedCodes)
                    ->delete();

                $statusSummary = collect($evaluations)
                    ->groupBy('status')
                    ->map(fn (Collection $rows) => $rows->count())
                    ->all();

                $this->writeLog(
                    $penelitianID,
                    'PEMERIKSAAN_BERHASIL',
                    $user->userID,
                    sprintf('%s berhasil menghasilkan penelitian Bagian E.', $user->name),
                    [
                        'bagian' => 'E',
                        'jumlah_baris' => count($generatedCodes),
                        'status_summary' => $statusSummary,
                        'automatic_check' => true,
                        'gemini' => false,
                    ]
                );

                return [
                    'jumlah_baris' => count($generatedCodes),
                    'status_summary' => $statusSummary,
                ];
            }, 3);
        } catch (\Throwable $e) {
            $this->writeLog(
                $penelitianID,
                'PEMERIKSAAN_GAGAL',
                $user->userID,
                sprintf('Penelitian Bagian E gagal: %s', $e->getMessage()),
                [
                    'bagian' => 'E',
                    'exception' => get_class($e),
                ]
            );

            throw $e;
        }
    }

    /**
     * Evaluasi lima baris fixed Bagian E.
     */
    private function evaluatePartE(
        array $documents,
        array $snapshots
    ): array {
        $rkaSelected = !blank($documents['RKA'] ?? null);
        $torDocuments = array_values((array) ($documents['TOR'] ?? []));
        $rabDocuments = array_values((array) ($documents['RAB'] ?? []));
        $rkbmnSelected = !blank($documents['RKBMN'] ?? null);
        $pegawaiSelected = !blank($documents['JUMLAH_PEGAWAI'] ?? null);

        $rkaNames = $snapshots['RKA'] ?? [];
        $torNames = $snapshots['TOR'] ?? [];
        $rabNames = $snapshots['RAB'] ?? [];
        $rkbmnNames = $snapshots['RKBMN'] ?? [];
        $pegawaiNames = $snapshots['JUMLAH_PEGAWAI'] ?? [];

        $result = [];

        // E1 Surat Pengantar:
        // Belum tersedia sebagai role dokumen pada menu upload/workspace.
        $result['E1'] = [
            'status' => 'PERLU_KONFIRMASI',
            'explanation' => 'Surat Pengantar perlu dikonfirmasi karena dokumen tersebut belum tersedia sebagai jenis dokumen yang dapat dipilih pada workspace penelitian.',
            'metadata' => [
                'automatic_check' => false,
                'upload_role_available' => false,
            ],
        ];

        // E2 Surat Tugas:
        // Belum tersedia sebagai role dokumen pada menu upload/workspace.
        $result['E2'] = [
            'status' => 'PERLU_KONFIRMASI',
            'explanation' => 'Surat Tugas perlu dikonfirmasi karena dokumen tersebut belum tersedia sebagai jenis dokumen yang dapat dipilih pada workspace penelitian.',
            'metadata' => [
                'automatic_check' => false,
                'upload_role_available' => false,
            ],
        ];

        // E3 RKA Satker.
        if ($rkaSelected) {
            $name = $this->firstDocumentName($rkaNames, 'RKA terpilih');

            $result['E3'] = [
                'status' => 'LENGKAP',
                'explanation' => "Dokumen RKA Satker tersedia: {$name}.",
                'metadata' => [
                    'automatic_check' => true,
                    'document_selected' => true,
                    'document_ids' => [(string) $documents['RKA']],
                    'document_names' => $rkaNames,
                ],
            ];
        } else {
            $result['E3'] = [
                'status' => 'BELUM_LENGKAP',
                'explanation' => 'Dokumen RKA Satker belum tersedia pada workspace penelitian.',
                'metadata' => [
                    'automatic_check' => true,
                    'document_selected' => false,
                ],
            ];
        }

        // E4 TOR dan RAB.
        $hasTor = count($torDocuments) > 0;
        $hasRab = count($rabDocuments) > 0;

        if ($hasTor && $hasRab) {
            $details = [
                'TOR: ' . $this->documentNameList($torNames, count($torDocuments) . ' dokumen TOR terpilih'),
                'RAB: ' . $this->documentNameList($rabNames, count($rabDocuments) . ' dokumen RAB terpilih'),
            ];

            $result['E4'] = [
                'status' => 'LENGKAP',
                'explanation' => $this->formatExplanation(
                    'Dokumen TOR dan RAB tersedia.',
                    $details
                ),
                'metadata' => [
                    'automatic_check' => true,
                    'tor_count' => count($torDocuments),
                    'rab_count' => count($rabDocuments),
                    'tor_document_ids' => $torDocuments,
                    'rab_document_ids' => $rabDocuments,
                    'tor_document_names' => $torNames,
                    'rab_document_names' => $rabNames,
                ],
            ];
        } else {
            $missing = [];

            if (!$hasTor) {
                $missing[] = 'Dokumen TOR belum dipilih.';
            }

            if (!$hasRab) {
                $missing[] = 'Dokumen RAB belum dipilih.';
            }

            $result['E4'] = [
                'status' => 'BELUM_LENGKAP',
                'explanation' => $this->formatExplanation(
                    'Dokumen TOR dan RAB belum lengkap.',
                    $missing
                ),
                'metadata' => [
                    'automatic_check' => true,
                    'tor_count' => count($torDocuments),
                    'rab_count' => count($rabDocuments),
                    'missing' => array_values(array_filter([
                        !$hasTor ? 'TOR' : null,
                        !$hasRab ? 'RAB' : null,
                    ])),
                ],
            ];
        }

        // E5 Data Dukung Lainnya: RKBMN + Data Jumlah Pegawai.
        $supportDetails = [];

        if ($rkbmnSelected) {
            $supportDetails[] = 'RKBMN: ' . $this->firstDocumentName(
                $rkbmnNames,
                'dokumen RKBMN terpilih'
            );
        } else {
            $supportDetails[] = 'Dokumen RKBMN belum dipilih.';
        }

        if ($pegawaiSelected) {
            $supportDetails[] = 'Data Jumlah Pegawai: ' . $this->firstDocumentName(
                $pegawaiNames,
                'dokumen Data Jumlah Pegawai terpilih'
            );
        } else {
            $supportDetails[] = 'Dokumen Data Jumlah Pegawai belum dipilih.';
        }

        $result['E5'] = [
            'status' => ($rkbmnSelected && $pegawaiSelected)
                ? 'LENGKAP'
                : 'BELUM_LENGKAP',
            'explanation' => $this->formatExplanation(
                ($rkbmnSelected && $pegawaiSelected)
                    ? 'Data Dukung Lainnya lengkap.'
                    : 'Data Dukung Lainnya belum lengkap.',
                $supportDetails
            ),
            'metadata' => [
                'automatic_check' => true,
                'rkbmn_selected' => $rkbmnSelected,
                'jumlah_pegawai_selected' => $pegawaiSelected,
                'rkbmn_document_id' => $documents['RKBMN'] ?? null,
                'jumlah_pegawai_document_id' => $documents['JUMLAH_PEGAWAI'] ?? null,
                'rkbmn_document_names' => $rkbmnNames,
                'jumlah_pegawai_document_names' => $pegawaiNames,
            ],
        ];

        return $result;
    }

    private function firstDocumentName(
        array $names,
        string $fallback
    ): string {
        $name = trim((string) ($names[0] ?? ''));

        return $name !== '' ? $name : $fallback;
    }

    private function documentNameList(
        array $names,
        string $fallback
    ): string {
        $names = array_values(array_unique(array_filter(
            array_map(
                static fn ($name) => trim((string) $name),
                $names
            ),
            static fn ($name) => $name !== ''
        )));

        return !empty($names)
            ? implode('; ', $names)
            : $fallback;
    }

    /**
     * Snapshot nama dokumen yang benar-benar terikat pada penelitian.
     * Menggunakan penelitian_dokumen, bukan membaca ulang nama file terbaru,
     * agar PENJELASAN tetap auditable.
     */
    private function selectedDocumentSnapshotsByRole(int $penelitianID): array
    {
        $rows = DB::table('penelitian_dokumen')
            ->where('penelitianID', $penelitianID)
            ->orderBy('peran_dokumen')
            ->orderBy('urutan')
            ->get([
                'documentID',
                'peran_dokumen',
                'document_name_snapshot',
            ]);

        $result = [
            'RENJA' => [],
            'RKBMN' => [],
            'JUMLAH_PEGAWAI' => [],
            'RKA' => [],
            'TOR' => [],
            'RAB' => [],
        ];

        foreach ($rows as $row) {
            $role = (string) $row->peran_dokumen;

            if (!array_key_exists($role, $result)) {
                continue;
            }

            $name = trim((string) ($row->document_name_snapshot ?? ''));

            if ($name !== '') {
                $result[$role][] = $name;
            }
        }

        foreach ($result as $role => $names) {
            $result[$role] = array_values(array_unique($names));
        }

        return $result;
    }

    private function upsertPartEResult(
        int $penelitianID,
        string $kodeBaris,
        int $urutan,
        string $uraian,
        array $evaluation
    ): int {
        $existing = DB::table('penelitian_hasil_status')
            ->where('penelitianID', $penelitianID)
            ->where('bagian', 'E')
            ->where('kode_baris', $kodeBaris)
            ->first();

        $status = (string) ($evaluation['status'] ?? '');

        if (!in_array($status, self::PART_E_STATUSES, true)) {
            throw new \RuntimeException(
                "Status sistem Bagian E pada {$kodeBaris} tidak valid."
            );
        }

        $payload = [
            'parentHasilStatusID' => null,
            'urutan' => $urutan,
            'level_baris' => 0,
            'uraian' => $uraian,
            'status_sistem' => $status,
            'penjelasan_sistem' => (string) ($evaluation['explanation'] ?? ''),
            'metadata_json' => json_encode(
                $evaluation['metadata'] ?? [],
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            ),
            'updated_at' => now(),
        ];

        if ($existing) {
            // Override user sengaja tidak disentuh saat rerun.
            DB::table('penelitian_hasil_status')
                ->where('hasilStatusID', $existing->hasilStatusID)
                ->update($payload);

            return (int) $existing->hasilStatusID;
        }

        return (int) DB::table('penelitian_hasil_status')->insertGetId([
            'penelitianID' => $penelitianID,
            'bagian' => 'E',
            'kode_baris' => $kodeBaris,
            ...$payload,
            'status_user' => null,
            'penjelasan_user' => null,
            'created_at' => now(),
        ]);
    }


    /**
     * Simpan perubahan user pada Bagian F.
     *
     * System note:
     * - boleh diedit melalui catatan_user;
     * - boleh disembunyikan melalui dihapus_user;
     * - catatan_sistem tidak pernah ditimpa.
     *
     * User note:
     * - dapat ditambah, diedit, atau dihapus selama DRAFT.
     */
    public function savePartFChanges(
        int $penelitianID,
        array $systemPayload,
        array $userPayload,
        User $user
    ): void {
        DB::transaction(function () use (
            $penelitianID,
            $systemPayload,
            $userPayload,
            $user
        ) {
            $penelitian = DB::table('penelitian')
                ->where('penelitianID', $penelitianID)
                ->lockForUpdate()
                ->first();

            if (!$penelitian) {
                throw new \RuntimeException('Penelitian tidak ditemukan.');
            }

            if ($penelitian->status !== 'DRAFT') {
                throw new \RuntimeException(
                    'Penelitian sudah FINAL dan Bagian F tidak dapat diubah.'
                );
            }

            $changed = 0;

            // ---------------------------------------------------------
            // 1. Edit / hide catatan sistem.
            // ---------------------------------------------------------
            foreach ($systemPayload as $catatanID => $input) {
                if (!ctype_digit((string) $catatanID)) {
                    continue;
                }

                $row = DB::table('penelitian_catatan')
                    ->where('catatanID', (int) $catatanID)
                    ->where('penelitianID', $penelitianID)
                    ->whereIn('sumber_catatan', [
                        'SYSTEM_RULE',
                        'SYSTEM_AI',
                    ])
                    ->first();

                if (!$row) {
                    // Catatan dapat hilang karena invalidasi sumber; skip.
                    continue;
                }

                $input = is_array($input) ? $input : [];

                $submitted = array_key_exists('catatan', $input)
                    ? trim((string) ($input['catatan'] ?? ''))
                    : (string) (
                        $row->catatan_user
                        ?? $row->catatan_sistem
                        ?? ''
                    );

                $systemText = trim((string) ($row->catatan_sistem ?? ''));

                $catatanUser = $submitted === $systemText
                    ? null
                    : $submitted;

                $deleted = filter_var(
                    $input['dihapus'] ?? false,
                    FILTER_VALIDATE_BOOL
                ) ? 1 : 0;

                $currentUser = $row->catatan_user !== null
                    ? (string) $row->catatan_user
                    : null;

                if (
                    $catatanUser !== $currentUser
                    || $deleted !== (int) $row->dihapus_user
                ) {
                    DB::table('penelitian_catatan')
                        ->where('catatanID', $row->catatanID)
                        ->update([
                            'catatan_user' => $catatanUser,
                            'dihapus_user' => $deleted,
                            'updated_by' => $user->userID,
                            'updated_at' => now(),
                        ]);

                    $changed++;
                }
            }

            // ---------------------------------------------------------
            // 2. Sinkron catatan manual USER.
            // ---------------------------------------------------------
            $existingUserRows = DB::table('penelitian_catatan')
                ->where('penelitianID', $penelitianID)
                ->where('sumber_catatan', 'USER')
                ->get()
                ->keyBy('catatanID');

            $submittedExistingIDs = [];
            $nextOrder = 1000;

            foreach (array_values($userPayload) as $input) {
                $input = is_array($input) ? $input : [];

                $text = trim((string) ($input['catatan'] ?? ''));
                $catatanID = isset($input['catatan_id'])
                    && ctype_digit((string) $input['catatan_id'])
                        ? (int) $input['catatan_id']
                        : null;

                if ($catatanID !== null && $existingUserRows->has($catatanID)) {
                    $submittedExistingIDs[] = $catatanID;

                    if ($text === '') {
                        DB::table('penelitian_catatan')
                            ->where('catatanID', $catatanID)
                            ->delete();

                        $changed++;
                        continue;
                    }

                    $row = $existingUserRows->get($catatanID);

                    if (
                        (string) ($row->catatan_user ?? '') !== $text
                        || (int) $row->urutan !== $nextOrder
                    ) {
                        DB::table('penelitian_catatan')
                            ->where('catatanID', $catatanID)
                            ->update([
                                'urutan' => $nextOrder,
                                'catatan_user' => $text,
                                'updated_by' => $user->userID,
                                'updated_at' => now(),
                            ]);

                        $changed++;
                    }

                    $nextOrder++;
                    continue;
                }

                if ($text === '') {
                    continue;
                }

                DB::table('penelitian_catatan')->insert([
                    'penelitianID' => $penelitianID,
                    'temuanID' => null,
                    'urutan' => $nextOrder++,
                    'sumber_catatan' => 'USER',
                    'catatan_sistem' => null,
                    'catatan_user' => $text,
                    'dihapus_user' => 0,
                    'created_by' => $user->userID,
                    'updated_by' => $user->userID,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $changed++;
            }

            // Existing USER rows yang tidak lagi dikirim dari form dianggap
            // telah dihapus user.
            $staleUserIDs = $existingUserRows
                ->keys()
                ->map(fn ($id) => (int) $id)
                ->diff($submittedExistingIDs)
                ->values()
                ->all();

            if (!empty($staleUserIDs)) {
                DB::table('penelitian_catatan')
                    ->where('penelitianID', $penelitianID)
                    ->where('sumber_catatan', 'USER')
                    ->whereIn('catatanID', $staleUserIDs)
                    ->delete();

                $changed += count($staleUserIDs);
            }

            if ($changed > 0) {
                $this->writeLog(
                    $penelitianID,
                    'DRAFT_DISIMPAN',
                    $user->userID,
                    sprintf(
                        '%s memperbarui Catatan Lain-Lain pada DRAFT penelitian.',
                        $user->name
                    ),
                    [
                        'bagian' => 'F',
                        'jumlah_perubahan' => $changed,
                    ]
                );
            }
        }, 3);
    }

    /**
     * Jalankan Research Engine Bagian F - CATATAN LAIN-LAIN.
     *
     * Sumber:
     * 1. ringkasan hasil A-E;
     * 2. warning Data Jumlah Pegawai;
     * 3. double-check RKA terhadap setiap RAB terpilih.
     *
     * Gemini hanya merapikan narasi note yang sudah selesai disusun sistem.
     * Gemini tidak menerima kewenangan membuat temuan/angka baru.
     */
    public function runPartF(int $penelitianID, User $user): array
    {
        $penelitian = DB::table('penelitian')
            ->where('penelitianID', $penelitianID)
            ->first();

        if (!$penelitian) {
            throw new \RuntimeException('Penelitian tidak ditemukan.');
        }

        if ($penelitian->status !== 'DRAFT') {
            throw new \RuntimeException(
                'Penelitian sudah FINAL dan tidak dapat dijalankan ulang.'
            );
        }

        $this->writeLog(
            $penelitianID,
            'PEMERIKSAAN_DIMULAI',
            $user->userID,
            sprintf(
                '%s menjalankan penelitian Bagian F pada "%s".',
                $user->name,
                $penelitian->nama_penelitian
            ),
            ['bagian' => 'F']
        );

        try {
            return DB::transaction(function () use ($penelitianID, $user) {
                $penelitian = DB::table('penelitian')
                    ->where('penelitianID', $penelitianID)
                    ->lockForUpdate()
                    ->first();

                if (!$penelitian) {
                    throw new \RuntimeException('Penelitian tidak ditemukan.');
                }

                if ($penelitian->status !== 'DRAFT') {
                    throw new \RuntimeException(
                        'Penelitian sudah FINAL dan tidak dapat dijalankan ulang.'
                    );
                }

                $documents = $this->selectedDocuments($penelitianID);

                if (blank($documents['RKA'])) {
                    throw new \RuntimeException(
                        'Dokumen RKA belum dipilih pada workspace penelitian.'
                    );
                }

                // Temuan F bersifat generated. Temuan A-E tidak disentuh.
                DB::table('penelitian_temuan')
                    ->where('penelitianID', $penelitianID)
                    ->where('bagian_sumber', 'F')
                    ->delete();

                $notes = [];

                // -----------------------------------------------------
                // 1. Ringkasan A-E.
                // -----------------------------------------------------
                $summaryAE = $this->buildPartFSectionSummary($penelitianID);

                if ($summaryAE !== null) {
                    $notes[] = [
                        'id' => 'F_AE',
                        'urutan' => 10,
                        'text' => $summaryAE,
                        'temuanID' => null,
                    ];
                }

                // -----------------------------------------------------
                // 2. Validasi Data Jumlah Pegawai.
                // -----------------------------------------------------
                $employee = $this->evaluatePartFEmployeeValidation(
                    $penelitian,
                    $documents
                );

                foreach ($employee['findings'] as $finding) {
                    $this->insertFinding(
                        $penelitianID,
                        null,
                        $finding,
                        'F'
                    );
                }

                if ($employee['note'] !== null) {
                    $notes[] = [
                        'id' => 'F_PEGAWAI',
                        'urutan' => 20,
                        'text' => $employee['note'],
                        'temuanID' => null,
                    ];
                }

                // -----------------------------------------------------
                // 3. Double-check RKA terhadap RAB.
                // -----------------------------------------------------
                $rabCheck = $this->evaluatePartFRabChecks(
                    $penelitian,
                    $documents
                );

                foreach ($rabCheck['findings'] as $finding) {
                    $this->insertFinding(
                        $penelitianID,
                        null,
                        $finding,
                        'F'
                    );
                }

                foreach ($rabCheck['notes'] as $index => $note) {
                    $notes[] = [
                        'id' => 'F_RAB_' . ($index + 1),
                        'urutan' => 100 + $index,
                        'text' => $note,
                        'temuanID' => null,
                    ];
                }

                // Jika seluruh pemeriksaan tidak menghasilkan catatan,
                // tetap berikan satu catatan positif yang terukur.
                if (empty($notes)) {
                    $notes[] = [
                        'id' => 'F_OK',
                        'urutan' => 10,
                        'text' =>
                            'Tidak terdapat catatan tambahan yang dihasilkan oleh Research Engine Bagian F.',
                        'temuanID' => null,
                    ];
                }

                // -----------------------------------------------------
                // 4. Optional Gemini narrative polish.
                //
                // Business rule FINAL Bagian F:
                // - Gemini hanya boleh merapikan RINGKASAN UMUM A-E.
                // - Validasi pegawai dan seluruh double-check RKA-RAB
                //   harus tetap deterministic/verbatim agar nomenklatur
                //   sumber (termasuk typo) tidak dikoreksi oleh AI.
                // -----------------------------------------------------
                $geminiEligibleNotes = array_values(
                    array_filter(
                        $notes,
                        static fn (array $note): bool =>
                            (string) ($note['id'] ?? '') === 'F_AE'
                    )
                );

                $gemini = app(GeminiFNarrativeService::class)
                    ->polish($geminiEligibleNotes);

                $polished = $gemini['notes'] ?? [];
                $generatedOrders = [];

                foreach ($notes as $note) {
                    $id = (string) $note['id'];
                    $hasAIText = isset($polished[$id])
                        && trim((string) $polished[$id]) !== '';

                    $text = $hasAIText
                        ? trim((string) $polished[$id])
                        : (string) $note['text'];

                    $source = $hasAIText
                        ? 'SYSTEM_AI'
                        : 'SYSTEM_RULE';

                    $this->upsertPartFSystemNote(
                        $penelitianID,
                        (int) $note['urutan'],
                        $source,
                        $text,
                        $note['temuanID'] ?? null,
                        $user
                    );

                    $generatedOrders[] = (int) $note['urutan'];
                }

                // Stale system notes dihapus; USER notes tidak disentuh.
                $staleQuery = DB::table('penelitian_catatan')
                    ->where('penelitianID', $penelitianID)
                    ->whereIn('sumber_catatan', [
                        'SYSTEM_RULE',
                        'SYSTEM_AI',
                    ]);

                if (!empty($generatedOrders)) {
                    $staleQuery->whereNotIn('urutan', $generatedOrders);
                }

                $staleQuery->delete();

                $this->writeLog(
                    $penelitianID,
                    'PEMERIKSAAN_BERHASIL',
                    $user->userID,
                    sprintf(
                        '%s berhasil menghasilkan penelitian Bagian F.',
                        $user->name
                    ),
                    [
                        'bagian' => 'F',
                        'jumlah_catatan_sistem' => count($notes),
                        'jumlah_temuan_pegawai' =>
                            count($employee['findings']),
                        'jumlah_temuan_rka_rab' =>
                            count($rabCheck['findings']),
                        'gemini_digunakan' =>
                            (bool) ($gemini['used_gemini'] ?? false),
                        'gemini_warning' =>
                            $gemini['warning'] ?? null,
                    ]
                );

                return [
                    'jumlah_catatan_sistem' => count($notes),
                    'jumlah_temuan_pegawai' =>
                        count($employee['findings']),
                    'jumlah_temuan_rka_rab' =>
                        count($rabCheck['findings']),
                    'gemini_digunakan' =>
                        (bool) ($gemini['used_gemini'] ?? false),
                    'gemini_warning' =>
                        $gemini['warning'] ?? null,
                ];
            }, 3);
        } catch (\Throwable $e) {
            $this->writeLog(
                $penelitianID,
                'PEMERIKSAAN_GAGAL',
                $user->userID,
                sprintf(
                    'Penelitian Bagian F gagal: %s',
                    $e->getMessage()
                ),
                [
                    'bagian' => 'F',
                    'exception' => get_class($e),
                ]
            );

            throw $e;
        }
    }

    /**
     * Ringkasan A-E menggunakan nilai EFEKTIF/current (override user bila ada),
     * bukan sekadar hasil sistem.
     */
    private function buildPartFSectionSummary(int $penelitianID): ?string
    {
        $details = [];

        foreach (['A', 'B'] as $bagian) {
            $rows = DB::table('penelitian_hasil_status')
                ->where('penelitianID', $penelitianID)
                ->where('bagian', $bagian)
                ->get();

            if ($rows->isEmpty()) {
                continue;
            }

            $counts = [
                'TIDAK_SESUAI' => 0,
                'PERLU_KONFIRMASI' => 0,
            ];

            foreach ($rows as $row) {
                $status = (string) (
                    $row->status_user
                    ?? $row->status_sistem
                    ?? ''
                );

                if (array_key_exists($status, $counts)) {
                    $counts[$status]++;
                }
            }

            if ($counts['TIDAK_SESUAI'] > 0) {
                $details[] = sprintf(
                    'Bagian %s memiliki %d pemeriksaan berstatus TIDAK SESUAI',
                    $bagian,
                    $counts['TIDAK_SESUAI']
                );
            }

            if ($counts['PERLU_KONFIRMASI'] > 0) {
                $details[] = sprintf(
                    'Bagian %s memiliki %d pemeriksaan berstatus PERLU KONFIRMASI',
                    $bagian,
                    $counts['PERLU_KONFIRMASI']
                );
            }
        }

        // C - hanya baris level 0 agar child tidak menggandakan summary.
        $cRows = DB::table('penelitian_hasil_nilai')
            ->where('penelitianID', $penelitianID)
            ->where('bagian', 'C')
            ->where('level_baris', 0)
            ->get();

        $cDiff = $cRows->filter(function ($row) {
            $renja = $row->pagu_renja_user !== null
                ? (float) $row->pagu_renja_user
                : (float) $row->pagu_renja_sistem;

            $rka = $row->pagu_rka_user !== null
                ? (float) $row->pagu_rka_user
                : (float) $row->pagu_rka_sistem;

            return abs($rka - $renja) > 0.00001;
        });

        if ($cDiff->isNotEmpty()) {
            $details[] = sprintf(
                'Bagian C memiliki selisih pada %d rincian utama',
                $cDiff->count()
            );
        }

        // D - fixed tagging.
        $dRows = DB::table('penelitian_hasil_nilai')
            ->where('penelitianID', $penelitianID)
            ->where('bagian', 'D')
            ->get();

        $dDiff = $dRows->filter(function ($row) {
            $renja = $row->pagu_renja_user !== null
                ? (float) $row->pagu_renja_user
                : (float) $row->pagu_renja_sistem;

            $rka = $row->pagu_rka_user !== null
                ? (float) $row->pagu_rka_user
                : (float) $row->pagu_rka_sistem;

            return abs($rka - $renja) > 0.00001;
        });

        if ($dDiff->isNotEmpty()) {
            $details[] = sprintf(
                'Bagian D memiliki selisih Budget Tagging pada %d kategori',
                $dDiff->count()
            );
        }

        // D.1 - gunakan child agar parent tidak duplicate.
        $d1Rows = DB::table('penelitian_hasil_nilai')
            ->where('penelitianID', $penelitianID)
            ->where('bagian', 'D1')
            ->where('level_baris', 1)
            ->get();

        $d1Diff = $d1Rows->filter(
            fn ($row) =>
                abs(
                    (float) $row->pagu_rka_sistem
                    - (float) $row->pagu_renja_sistem
                ) > 0.00001
        );

        if ($d1Diff->isNotEmpty()) {
            $details[] = sprintf(
                'Bagian D.1 memiliki selisih Belanja TIK pada %d kategori KRO',
                $d1Diff->count()
            );
        }

        // D.2 - child dengan penjelasan efektif.
        $d2Rows = DB::table('penelitian_hasil_d2')
            ->where('penelitianID', $penelitianID)
            ->where('level_baris', 1)
            ->get();

        $d2Notes = $d2Rows->filter(function ($row) {
            $text = trim((string) (
                $row->penjelasan_user
                ?? $row->penjelasan_sistem
                ?? ''
            ));

            return $text !== '';
        });

        if ($d2Notes->isNotEmpty()) {
            $details[] = sprintf(
                'Bagian D.2 memiliki catatan pada %d subkategori aset TIK',
                $d2Notes->count()
            );
        }

        // E - status efektif yang belum LENGKAP.
        $eRows = DB::table('penelitian_hasil_status')
            ->where('penelitianID', $penelitianID)
            ->where('bagian', 'E')
            ->get();

        $eIssues = [];

        foreach ($eRows as $row) {
            $status = (string) (
                $row->status_user
                ?? $row->status_sistem
                ?? ''
            );

            if ($status !== '' && $status !== 'LENGKAP') {
                $eIssues[] = sprintf(
                    '%s: %s',
                    $row->uraian,
                    str_replace('_', ' ', $status)
                );
            }
        }

        if (!empty($eIssues)) {
            $details[] =
                'Bagian E masih memerlukan tindak lanjut pada '
                . implode('; ', $eIssues);
        }

        // Bila section sudah ada namun tidak ada concern, note positif tetap
        // boleh ditampilkan agar F benar-benar merangkum A-E.
        $hasAnySection =
            DB::table('penelitian_hasil_status')
                ->where('penelitianID', $penelitianID)
                ->whereIn('bagian', ['A', 'B', 'E'])
                ->exists()
            || DB::table('penelitian_hasil_nilai')
                ->where('penelitianID', $penelitianID)
                ->whereIn('bagian', ['C', 'D', 'D1'])
                ->exists()
            || DB::table('penelitian_hasil_d2')
                ->where('penelitianID', $penelitianID)
                ->exists();

        if (!$hasAnySection) {
            return null;
        }

        if (empty($details)) {
            return
                'Ringkasan hasil Bagian A sampai dengan E tidak menunjukkan catatan tambahan yang perlu ditindaklanjuti.';
        }

        return $this->formatExplanation(
            'Ringkasan hasil penelitian Bagian A sampai dengan E masih menunjukkan beberapa hal yang perlu ditindaklanjuti.',
            $details
        );
    }

    /**
     * Data Jumlah Pegawai:
     * 1. exact kode_satker;
     * 2. fallback kode_unit_eselon2;
     * 3. jika tetap tidak ditemukan = unavailable, bukan 0.
     *
     * Overage dicek PER DETAIL agar pegawai yang sama pada beberapa honor
     * tidak dijumlahkan ganda.
     */
    private function evaluatePartFEmployeeValidation(
        object $penelitian,
        array $documents
    ): array {
        $result = [
            'note' => null,
            'findings' => [],
        ];

        $documentID = $documents['JUMLAH_PEGAWAI'] ?? null;

        if (blank($documentID)) {
            // E sudah menyatakan data dukung belum lengkap; tidak perlu duplicate.
            return $result;
        }

        $employeeRow = DB::table('jumlah_pegawai_detail')
            ->where('documentID', $documentID)
            ->where('kode_satker', $penelitian->kode_satker)
            ->whereNotNull('jumlah_pegawai')
            ->orderBy('urutan_sumber')
            ->first();

        $matchLevel = 'SATKER';

        if (!$employeeRow && !blank($penelitian->kode_unit_eselon2)) {
            $employeeRow = DB::table('jumlah_pegawai_detail')
                ->where('documentID', $documentID)
                ->where('kode_unit_eselon2', $penelitian->kode_unit_eselon2)
                ->where('level_organisasi', 'UNIT_ESELON_II')
                ->whereNotNull('jumlah_pegawai')
                ->orderBy('urutan_sumber')
                ->first();

            $matchLevel = 'UNIT_ESELON_II';
        }

        if (!$employeeRow) {
            $result['note'] =
                'Validasi kebutuhan gaji/honor terhadap Data Jumlah Pegawai belum dapat dilakukan karena jumlah pegawai tidak ditemukan untuk kode Satker maupun fallback Unit Eselon II pada dokumen terpilih.';

            $result['findings'][] = [
                'jenis_temuan' => 'F_DATA_PEGAWAI_TIDAK_DITEMUKAN',
                'status_sistem' => 'PERLU_KONFIRMASI',
                'documentID_sumber' => $documentID,
                'documentID_pembanding' => $documents['RKA'],
                'pesan_sistem' => $result['note'],
                'metadata_json' => [
                    'kode_satker' => $penelitian->kode_satker,
                    'kode_unit_eselon2' =>
                        $penelitian->kode_unit_eselon2,
                ],
            ];

            return $result;
        }

        $employeeCount = (int) $employeeRow->jumlah_pegawai;

        $config = (array) config(
            'penelitian.f_employee_validation',
            []
        );

        $keywords = array_values(array_filter(array_map(
            fn ($value) => $this->normalizeKeywordText(
                (string) $value
            ),
            (array) ($config['keywords'] ?? [])
        )));

        $headcountUnits = array_values(array_filter(array_map(
            fn ($value) => $this->normalizeKeywordText(
                (string) $value
            ),
            (array) ($config['headcount_units'] ?? [])
        )));

        if (empty($keywords)) {
            return $result;
        }

        $rkaRows = $this->rkaScopedQuery(
            $penelitian,
            $documents['RKA']
        )->get([
            'rkaID',
            'kode_program',
            'kode_kegiatan',
            'kode_kro',
            'kode_ro',
            'kode_komponen',
            'kode_subkomponen',
            'kode_akun',
            'nama_akun',
            'kelompok_detail',
            'uraian_detail',
            'volume',
            'satuan_volume',
            'jumlah_biaya',
        ]);

        $warnings = [];

        foreach ($rkaRows as $row) {
            $text = $this->normalizeKeywordText(implode(' ', [
                (string) ($row->nama_akun ?? ''),
                (string) ($row->kelompok_detail ?? ''),
                (string) ($row->uraian_detail ?? ''),
            ]));

            $matched = collect($keywords)
                ->contains(fn ($keyword) =>
                    $keyword !== ''
                    && str_contains($text, $keyword)
                );

            if (!$matched) {
                continue;
            }

            $persons = $this->extractPartFPersonCount(
                (string) ($row->uraian_detail ?? ''),
                $row->volume,
                (string) ($row->satuan_volume ?? ''),
                $headcountUnits
            );

            if ($persons === null || $persons <= $employeeCount) {
                continue;
            }

            $difference = $persons - $employeeCount;

            $message = sprintf(
                'Rincian "%s" mengusulkan %d orang, melebihi Data Jumlah Pegawai %d orang sebesar %d orang.',
                $this->normalizeComparableText(
                    $row->uraian_detail
                    ?? $row->kelompok_detail
                    ?? $row->nama_akun
                    ?? 'Rincian gaji/honor'
                ),
                $persons,
                $employeeCount,
                $difference
            );

            $warnings[] = $message;

            $result['findings'][] = [
                'jenis_temuan' =>
                    'F_VOLUME_GAJI_HONOR_MELEBIHI_PEGAWAI',
                'status_sistem' => 'TIDAK_SESUAI',
                'documentID_sumber' => $documents['RKA'],
                'documentID_pembanding' => $documentID,
                'kode_program' => $row->kode_program,
                'kode_kegiatan' => $row->kode_kegiatan,
                'kode_kro' => $row->kode_kro,
                'kode_ro' => $row->kode_ro,
                'kode_komponen' => $row->kode_komponen,
                'kode_subkomponen' => $row->kode_subkomponen,
                'kode_akun' => $row->kode_akun,
                'nilai_sumber_nominal' => $persons,
                'nilai_pembanding_nominal' => $employeeCount,
                'selisih_nominal' => $difference,
                'nilai_sumber_text' => $row->uraian_detail,
                'pesan_sistem' => $message,
                'metadata_json' => [
                    'rkaID' => $row->rkaID,
                    'match_level_pegawai' => $matchLevel,
                    'satuan_volume' => $row->satuan_volume,
                ],
            ];
        }

        if (!empty($warnings)) {
            $result['note'] = $this->formatExplanation(
                sprintf(
                    'Validasi Data Jumlah Pegawai menemukan rincian gaji/honor yang melebihi jumlah pegawai master %d orang.',
                    $employeeCount
                ),
                $warnings
            );
        }

        return $result;
    }

    private function extractPartFPersonCount(
        string $description,
        $volume,
        string $unit,
        array $headcountUnits
    ): ?int {
        $normalized = mb_strtoupper(
            $this->normalizeComparableText($description)
        );

        // Contoh:
        // [5 org x 6 bln]
        // 2 orang x 3 jam
        if (
            preg_match_all(
                '/(\d+(?:[.,]\d+)?)\s*(?:ORG|ORANG|PEGAWAI)\b/u',
                $normalized,
                $matches
            ) > 0
        ) {
            $values = array_map(function ($value) {
                return (int) round(
                    (float) str_replace(',', '.', $value)
                );
            }, $matches[1]);

            return !empty($values) ? max($values) : null;
        }

        $normalizedUnit = $this->normalizeKeywordText($unit);

        if (
            $normalizedUnit !== ''
            && in_array($normalizedUnit, $headcountUnits, true)
            && $volume !== null
        ) {
            return (int) round((float) $volume);
        }

        // Satuan OB/OJ/OH/OK tidak diperlakukan otomatis sebagai headcount.
        return null;
    }

    /**
     * Double-check RKA terhadap RAB.
     *
     * RKA authoritative. Setiap RAB hanya dibandingkan terhadap scope RO yang
     * memang terdapat di RAB tersebut.
     */
    private function evaluatePartFRabChecks(
        object $penelitian,
        array $documents
    ): array {
        $result = [
            'notes' => [],
            'findings' => [],
        ];

        $rabIDs = array_values((array) ($documents['RAB'] ?? []));

        if (empty($rabIDs)) {
            // Bagian E sudah menandai ketidaklengkapan RAB.
            return $result;
        }

        $rkaRows = $this->rkaScopedQuery(
            $penelitian,
            $documents['RKA']
        )->get([
            'rkaID',
            'kode_program',
            'kode_kegiatan',
            'kode_kro',
            'kode_ro',
            'kode_komponen',
            'kode_subkomponen',
            'nama_subkomponen',
            'kode_akun',
            'nama_akun',
            'uraian_detail',
            'jumlah_biaya',
        ]);

        foreach ($rabIDs as $rabDocumentID) {
            $rabRows = DB::table('rab')
                ->where('documentID', $rabDocumentID)
                ->where('tahun_anggaran', $penelitian->tahun_anggaran)
                ->where('kode_satker', $penelitian->kode_satker)
                ->get([
                    'rabID',
                    'kode_program',
                    'kode_kegiatan',
                    'kode_kro',
                    'kode_ro',
                    'kode_komponen',
                    'kode_subkomponen',
                    'nama_subkomponen',
                    'kode_akun',
                    'nama_akun',
                    'uraian_detail',
                    'jumlah_biaya',
                ]);

            $rabName = DB::table('penelitian_dokumen')
                ->where('penelitianID', $penelitian->penelitianID)
                ->where('documentID', $rabDocumentID)
                ->where('peran_dokumen', 'RAB')
                ->value('document_name_snapshot');

            $rabName = trim((string) $rabName) !== ''
                ? trim((string) $rabName)
                : $rabDocumentID;

            if ($rabRows->isEmpty()) {
                $message = sprintf(
                    'Data hasil ekstraksi RAB "%s" tidak ditemukan untuk Satker dan tahun penelitian sehingga double-check RKA-RAB perlu dikonfirmasi.',
                    $rabName
                );

                $result['notes'][] = $message;
                $result['findings'][] = [
                    'jenis_temuan' => 'F_RAB_DATA_TIDAK_DITEMUKAN',
                    'status_sistem' => 'PERLU_KONFIRMASI',
                    'documentID_sumber' => $documents['RKA'],
                    'documentID_pembanding' => $rabDocumentID,
                    'pesan_sistem' => $message,
                ];

                continue;
            }

            $comparison = $this->comparePartFRabDocument(
                $rkaRows,
                $rabRows,
                $documents['RKA'],
                $rabDocumentID
            );

            foreach ($comparison['findings'] as $finding) {
                $result['findings'][] = $finding;
            }

            if (empty($comparison['findings'])) {
                $result['notes'][] = sprintf(
                    'Double-check RKA terhadap RAB "%s" tidak menemukan perbedaan kode hierarki, uraian detail, maupun jumlah biaya pada scope RAB yang diperiksa.',
                    $rabName
                );

                continue;
            }

            $counts = $comparison['counts'];
            $details = [];

            if (($counts['hierarchy'] ?? 0) > 0) {
                $details[] = sprintf(
                    '%d perbedaan/missing pada kode hierarki',
                    $counts['hierarchy']
                );
            }

            if (($counts['detail'] ?? 0) > 0) {
                $details[] = sprintf(
                    '%d perbedaan/missing pada uraian detail',
                    $counts['detail']
                );
            }

            if (($counts['nominal'] ?? 0) > 0) {
                $details[] = sprintf(
                    '%d perbedaan jumlah biaya',
                    $counts['nominal']
                );
            }

            $examples = collect($comparison['findings'])
                ->take(5)
                ->pluck('pesan_sistem')
                ->filter()
                ->values()
                ->all();

            $result['notes'][] = $this->formatExplanation(
                sprintf(
                    'Double-check RKA terhadap RAB "%s" menemukan %d temuan unik pada scope yang diperiksa (%s).',
                    $rabName,
                    count($comparison['findings']),
                    implode(', ', $details)
                ),
                $examples
            );
        }

        return $result;
    }

    /**
     * Double-check satu RAB terhadap RKA dengan one-to-one pairing.
     *
     * Revised business rule:
     * - Program/Kegiatan/KRO/RO/Komponen harus exact.
     * - Subkomponen:
     *   * bila kode tersedia pada kedua sumber -> exact;
     *   * bila salah satu kode kosong -> nama_subkomponen menjadi fallback;
     *   * bila nama salah satu sisi juga kosong -> field tersebut dianggap
     *     unavailable dan tidak langsung menyebabkan mismatch.
     * - Akun:
     *   * bila kode tersedia pada kedua sumber -> exact;
     *   * bila salah satu kode kosong -> jangan langsung hierarchy mismatch.
     * - Uraian:
     *   * deterministic technical normalization;
     *   * case-insensitive;
     *   * D.K.I. = DKI;
     *   * formula kuantitas di akhir uraian dibuang dari comparison text;
     *   * tidak fuzzy dan tidak Gemini.
     * - Nominal:
     *   * jumlah_biaya saja;
     *   * volume/harga satuan tidak dibandingkan.
     * - Pairing:
     *   * satu row RAB hanya boleh berpasangan dengan satu row RKA;
     *   * satu pasangan tidak menghasilkan finding dua arah yang sama.
     */
    private function comparePartFRabDocument(
        Collection $allRkaRows,
        Collection $rabRows,
        string $rkaDocumentID,
        string $rabDocumentID
    ): array {
        $findings = [];
        $counts = [
            'hierarchy' => 0,
            'detail' => 0,
            'nominal' => 0,
        ];

        // Scope RAB tetap Program-Kegiatan-KRO-RO.
        $scopeKeys = $rabRows
            ->map(fn ($row) => $this->partFRoScopeKey($row))
            ->filter(fn ($key) => $key !== '')
            ->unique()
            ->values();

        $rkaRows = $allRkaRows
            ->filter(
                fn ($row) =>
                    $scopeKeys->contains(
                        $this->partFRoScopeKey($row)
                    )
            )
            ->values();

        $rabItems = $this->preparePartFComparisonItems(
            $rabRows,
            'RAB'
        );

        $rkaItems = $this->preparePartFComparisonItems(
            $rkaRows,
            'RKA'
        );

        $matchedRka = [];
        $matchedRab = [];

        // Agar satu hierarchy yang benar-benar hilang tidak menghasilkan
        // puluhan finding per detail.
        $reportedHierarchy = [];
        $ambiguousBaseHierarchy = [];

        $rabBaseKeys = collect($rabItems)
            ->pluck('base_hierarchy_key')
            ->filter()
            ->unique()
            ->values();

        // -----------------------------------------------------------------
        // RAB -> RKA: pairing utama.
        // -----------------------------------------------------------------
        foreach ($rabItems as $rabIndex => $rabItem) {
            $baseCandidates = [];

            foreach ($rkaItems as $rkaIndex => $rkaItem) {
                if (isset($matchedRka[$rkaIndex])) {
                    continue;
                }

                if (
                    $rkaItem['base_hierarchy_key']
                    === $rabItem['base_hierarchy_key']
                ) {
                    $baseCandidates[$rkaIndex] = $rkaItem;
                }
            }

            // Program-Kegiatan-KRO-RO-Komponen tidak ditemukan.
            if (empty($baseCandidates)) {
                $reportKey = 'BASE|' . $rabItem['base_hierarchy_key'];

                if (!isset($reportedHierarchy[$reportKey])) {
                    $message = sprintf(
                        'Hierarki utama RAB %s tidak ditemukan pada RKA.',
                        $rabItem['base_hierarchy_label']
                    );

                    $findings[] = $this->partFRabFinding(
                        'F_RAB_HIERARKI_UTAMA_TIDAK_DITEMUKAN_DI_RKA',
                        null,
                        $rabItem,
                        $rkaDocumentID,
                        $rabDocumentID,
                        $message
                    );

                    $counts['hierarchy']++;
                    $reportedHierarchy[$reportKey] = true;
                }

                continue;
            }

            // Subkomponen/Akun hanya memblokir pairing jika informasi yang
            // memang tersedia pada kedua sumber saling bertentangan.
            $compatibleCandidates = array_filter(
                $baseCandidates,
                fn (array $rkaItem) =>
                    $this->partFOptionalHierarchyCompatible(
                        $rabItem,
                        $rkaItem
                    )
            );

            if (empty($compatibleCandidates)) {
                $reportKey =
                    'OPTIONAL|' . $this->partFOptionalHierarchyReportKey(
                        $rabItem
                    );

                if (!isset($reportedHierarchy[$reportKey])) {
                    $message = sprintf(
                        'Subkomponen/Akun RAB pada %s tidak menemukan pasangan yang kompatibel pada RKA. Kode hanya diperlakukan sebagai mismatch jika tersedia pada kedua sumber; nama subkomponen digunakan sebagai fallback ketika salah satu kode subkomponen kosong.',
                        $rabItem['base_hierarchy_label']
                    );

                    $findings[] = $this->partFRabFinding(
                        'F_RAB_SUBKOMPONEN_AKUN_TIDAK_KOMPATIBEL',
                        null,
                        $rabItem,
                        $rkaDocumentID,
                        $rabDocumentID,
                        $message
                    );

                    $counts['hierarchy']++;
                    $reportedHierarchy[$reportKey] = true;
                }

                continue;
            }

            // -------------------------------------------------------------
            // A. Exact normalized description.
            // -------------------------------------------------------------
            $sameDetail = array_filter(
                $compatibleCandidates,
                fn (array $rkaItem) =>
                    $rkaItem['comparison_detail']
                    === $rabItem['comparison_detail']
            );

            if (!empty($sameDetail)) {
                $rkaIndex = $this->choosePartFBestCandidate(
                    $rabItem,
                    $sameDetail
                );

                $rkaItem = $rkaItems[$rkaIndex];

                $matchedRka[$rkaIndex] = true;
                $matchedRab[$rabIndex] = true;

                if (!$this->partFAmountEqual(
                    $rkaItem['amount'],
                    $rabItem['amount']
                )) {
                    $counts['nominal']++;

                    $findings[] = $this->partFRabFinding(
                        'F_RAB_NOMINAL_TIDAK_SESUAI',
                        $rkaItem,
                        $rabItem,
                        $rkaDocumentID,
                        $rabDocumentID,
                        sprintf(
                            'Jumlah biaya berbeda untuk "%s" pada %s: RKA %s, RAB %s, selisih RKA - RAB %s.',
                            $rabItem['display_detail'],
                            $rabItem['effective_hierarchy_label'],
                            $this->formatRupiah(
                                (int) round($rkaItem['amount'])
                            ),
                            $this->formatRupiah(
                                (int) round($rabItem['amount'])
                            ),
                            $this->formatRupiah(
                                (int) round(
                                    $rkaItem['amount']
                                    - $rabItem['amount']
                                )
                            )
                        )
                    );
                }

                continue;
            }

            // -------------------------------------------------------------
            // B. Uraian tidak exact, tetapi nominal sama.
            //
            // Ini adalah pairing deterministic yang penting untuk typo/
            // nomenklatur berbeda. Tidak menggunakan fuzzy similarity.
            // -------------------------------------------------------------
            $sameAmount = array_filter(
                $compatibleCandidates,
                fn (array $rkaItem) =>
                    $this->partFAmountEqual(
                        $rkaItem['amount'],
                        $rabItem['amount']
                    )
            );

            if (!empty($sameAmount)) {
                $rkaIndex = $this->choosePartFBestCandidate(
                    $rabItem,
                    $sameAmount,
                    false
                );

                $rkaItem = $rkaItems[$rkaIndex];

                $matchedRka[$rkaIndex] = true;
                $matchedRab[$rabIndex] = true;

                $counts['detail']++;

                $findings[] = $this->partFRabFinding(
                    'F_RAB_URAIAN_TIDAK_SESUAI',
                    $rkaItem,
                    $rabItem,
                    $rkaDocumentID,
                    $rabDocumentID,
                    sprintf(
                        'Uraian detail berbeda pada %s dengan jumlah biaya yang sama %s: RKA "%s"; RAB "%s".',
                        $rabItem['effective_hierarchy_label'],
                        $this->formatRupiah(
                            (int) round($rabItem['amount'])
                        ),
                        $rkaItem['display_detail'],
                        $rabItem['display_detail']
                    )
                );

                continue;
            }

            // -------------------------------------------------------------
            // C. Jika hanya ada satu kandidat kompatibel, pairing tetap aman
            // secara hierarchy. Laporkan satu finding gabungan, bukan dua.
            // -------------------------------------------------------------
            if (count($compatibleCandidates) === 1) {
                $rkaIndex = (int) array_key_first(
                    $compatibleCandidates
                );

                $rkaItem = $rkaItems[$rkaIndex];

                $matchedRka[$rkaIndex] = true;
                $matchedRab[$rabIndex] = true;

                $detailDifferent =
                    $rkaItem['comparison_detail']
                    !== $rabItem['comparison_detail'];

                $amountDifferent = !$this->partFAmountEqual(
                    $rkaItem['amount'],
                    $rabItem['amount']
                );

                if ($detailDifferent && $amountDifferent) {
                    $counts['detail']++;
                    $counts['nominal']++;

                    $findings[] = $this->partFRabFinding(
                        'F_RAB_URAIAN_DAN_NOMINAL_TIDAK_SESUAI',
                        $rkaItem,
                        $rabItem,
                        $rkaDocumentID,
                        $rabDocumentID,
                        sprintf(
                            'Uraian detail dan jumlah biaya berbeda pada %s: RKA "%s" (%s); RAB "%s" (%s); selisih RKA - RAB %s.',
                            $rabItem['effective_hierarchy_label'],
                            $rkaItem['display_detail'],
                            $this->formatRupiah(
                                (int) round($rkaItem['amount'])
                            ),
                            $rabItem['display_detail'],
                            $this->formatRupiah(
                                (int) round($rabItem['amount'])
                            ),
                            $this->formatRupiah(
                                (int) round(
                                    $rkaItem['amount']
                                    - $rabItem['amount']
                                )
                            )
                        )
                    );
                }

                continue;
            }

            // -------------------------------------------------------------
            // D. Kandidat > 1 dan tidak ada exact detail/nominal:
            // tidak ada dasar deterministic untuk memilih satu pasangan.
            // Buat satu finding RAB dan jangan membuat finding balik untuk
            // kandidat RKA pada hierarchy ini.
            // -------------------------------------------------------------
            $counts['detail']++;
            $ambiguousBaseHierarchy[
                $rabItem['base_hierarchy_key']
            ] = true;

            $findings[] = $this->partFRabFinding(
                'F_RAB_URAIAN_TIDAK_DITEMUKAN_DI_RKA',
                null,
                $rabItem,
                $rkaDocumentID,
                $rabDocumentID,
                sprintf(
                    'Uraian detail RAB "%s" tidak menemukan pasangan deterministic pada RKA di %s setelah normalisasi teknis. Tidak dilakukan fuzzy matching.',
                    $rabItem['display_detail'],
                    $rabItem['effective_hierarchy_label']
                )
            );
        }

        // -----------------------------------------------------------------
        // RKA -> RAB:
        // hanya true extra row yang belum pernah dipasangkan.
        // Hierarchy ambigu sengaja tidak dibalas agar satu perbedaan tidak
        // dihitung dua kali.
        // -----------------------------------------------------------------
        foreach ($rkaItems as $rkaIndex => $rkaItem) {
            if (isset($matchedRka[$rkaIndex])) {
                continue;
            }

            if (
                isset(
                    $ambiguousBaseHierarchy[
                        $rkaItem['base_hierarchy_key']
                    ]
                )
            ) {
                continue;
            }

            if (
                !$rabBaseKeys->contains(
                    $rkaItem['base_hierarchy_key']
                )
            ) {
                $reportKey =
                    'RKA_BASE|' . $rkaItem['base_hierarchy_key'];

                if (!isset($reportedHierarchy[$reportKey])) {
                    $counts['hierarchy']++;

                    $findings[] = $this->partFRabFinding(
                        'F_RKA_HIERARKI_UTAMA_TIDAK_DITEMUKAN_DI_RAB',
                        $rkaItem,
                        null,
                        $rkaDocumentID,
                        $rabDocumentID,
                        sprintf(
                            'Hierarki utama RKA %s berada di dalam scope RO RAB tetapi tidak ditemukan pada RAB.',
                            $rkaItem['base_hierarchy_label']
                        )
                    );

                    $reportedHierarchy[$reportKey] = true;
                }

                continue;
            }

            // Jika ada RAB pada hierarchy utama yang kompatibel dengan
            // subkomponen/akun tetapi row ini tidak terpasangkan, anggap
            // sebagai extra detail RKA.
            $compatibleRab = collect($rabItems)
                ->filter(
                    fn (array $rabItem) =>
                        $rabItem['base_hierarchy_key']
                            === $rkaItem['base_hierarchy_key']
                        && $this->partFOptionalHierarchyCompatible(
                            $rabItem,
                            $rkaItem
                        )
                );

            if ($compatibleRab->isEmpty()) {
                $reportKey =
                    'RKA_OPTIONAL|'
                    . $this->partFOptionalHierarchyReportKey(
                        $rkaItem
                    );

                if (!isset($reportedHierarchy[$reportKey])) {
                    $counts['hierarchy']++;

                    $findings[] = $this->partFRabFinding(
                        'F_RKA_SUBKOMPONEN_AKUN_TIDAK_KOMPATIBEL',
                        $rkaItem,
                        null,
                        $rkaDocumentID,
                        $rabDocumentID,
                        sprintf(
                            'Subkomponen/Akun RKA pada %s berada di dalam scope RAB tetapi tidak memiliki hierarchy yang kompatibel pada RAB.',
                            $rkaItem['base_hierarchy_label']
                        )
                    );

                    $reportedHierarchy[$reportKey] = true;
                }

                continue;
            }

            $counts['detail']++;

            $findings[] = $this->partFRabFinding(
                'F_RKA_DETAIL_TAMBAHAN_DI_SCOPE_RAB',
                $rkaItem,
                null,
                $rkaDocumentID,
                $rabDocumentID,
                sprintf(
                    'RKA memiliki detail tambahan "%s" pada %s yang tidak mempunyai pasangan pada RAB.',
                    $rkaItem['display_detail'],
                    $rkaItem['effective_hierarchy_label']
                )
            );
        }

        return [
            'findings' => $findings,
            'counts' => $counts,
            'matched_pairs' => count($matchedRab),
        ];
    }

    /**
     * Siapkan row comparison satu per satu. Tidak dilakukan aggregate/grouping
     * agar one-to-one pairing tetap dapat diaudit.
     */
    private function preparePartFComparisonItems(
        Collection $rows,
        string $source
    ): array {
        return $rows
            ->filter(
                fn ($row) =>
                    $this->partFRoScopeKey($row) !== ''
            )
            ->values()
            ->map(function ($row, int $index) use ($source) {
                $program = $this->canonicalProgramCode(
                    $row->kode_program ?? ''
                );

                $kegiatan = trim(
                    (string) ($row->kode_kegiatan ?? '')
                );

                $kro = trim(
                    (string) ($row->kode_kro ?? '')
                );

                $ro = trim(
                    (string) ($row->kode_ro ?? '')
                );

                $komponen = trim(
                    (string) ($row->kode_komponen ?? '')
                );

                $subCode = trim(
                    (string) ($row->kode_subkomponen ?? '')
                );

                $subNameRaw = trim(
                    (string) ($row->nama_subkomponen ?? '')
                );

                $accountCode = trim(
                    (string) ($row->kode_akun ?? '')
                );

                $rawDetail = $this->normalizeComparableText(
                    $row->uraian_detail ?? ''
                );

                $baseKey = implode('|', [
                    $program,
                    $kegiatan,
                    $kro,
                    $ro,
                    $komponen,
                ]);

                $baseLabel = sprintf(
                    'Program %s → Kegiatan %s → KRO %s → RO %s → Komponen %s',
                    $program !== '' ? $program : '-',
                    $kegiatan !== '' ? $kegiatan : '-',
                    $kro !== '' ? $kro : '-',
                    $ro !== '' ? $ro : '-',
                    $komponen !== '' ? $komponen : '-'
                );

                return [
                    'source' => $source,
                    'source_index' => $index,
                    'row_id' => (string) (
                        $row->rkaID
                        ?? $row->rabID
                        ?? $index
                    ),
                    'base_hierarchy_key' => $baseKey,
                    'base_hierarchy_label' => $baseLabel,
                    'effective_hierarchy_label' => sprintf(
                        '%s → Subkomponen %s → Akun %s',
                        $baseLabel,
                        $subCode !== ''
                            ? $subCode
                            : ($subNameRaw !== ''
                                ? $subNameRaw
                                : '-'),
                        $accountCode !== ''
                            ? $accountCode
                            : '-'
                    ),
                    'sub_code' => $subCode,
                    'sub_name' => $subNameRaw,
                    'sub_name_normalized' =>
                        $this->normalizePartFHierarchyName(
                            $subNameRaw
                        ),
                    'account_code' => $accountCode,
                    'display_detail' => $rawDetail,
                    'comparison_detail' =>
                        $this->normalizePartFRabDetailText(
                            $rawDetail
                        ),
                    'amount' => (float) (
                        $row->jumlah_biaya ?? 0
                    ),
                    'kode_program' => $program,
                    'kode_kegiatan' => $kegiatan,
                    'kode_kro' => $kro,
                    'kode_ro' => $ro,
                    'kode_komponen' => $komponen,
                    'kode_subkomponen' => $subCode,
                    'kode_akun' => $accountCode,
                ];
            })
            ->all();
    }

    /**
     * Subkomponen + Akun:
     * hanya exact pada field kode yang tersedia di kedua sisi.
     */
    private function partFOptionalHierarchyCompatible(
        array $rabItem,
        array $rkaItem
    ): bool {
        $rabSub = (string) $rabItem['sub_code'];
        $rkaSub = (string) $rkaItem['sub_code'];

        // Revised rule:
        // - jika KEDUA sumber memiliki kode subkomponen -> harus exact;
        // - jika salah satu kode kosong -> nama subkomponen hanya menjadi
        //   positive matching signal, BUKAN hard mismatch.
        //
        // Ini penting karena RAB/RKA tidak selalu menyimpan kode subkomponen
        // pada granularitas yang sama walaupun nomenklatur/detailnya sepadan.
        if (
            $rabSub !== ''
            && $rkaSub !== ''
            && $rabSub !== $rkaSub
        ) {
            return false;
        }

        $rabAccount = (string) $rabItem['account_code'];
        $rkaAccount = (string) $rkaItem['account_code'];

        // Akun tetap exact hanya jika tersedia pada kedua sumber.
        if (
            $rabAccount !== ''
            && $rkaAccount !== ''
            && $rabAccount !== $rkaAccount
        ) {
            return false;
        }

        return true;
    }

    private function partFOptionalHierarchyReportKey(
        array $item
    ): string {
        return implode('|', [
            $item['base_hierarchy_key'],
            $item['sub_code'] !== ''
                ? 'SUBCODE:' . $item['sub_code']
                : 'SUBNAME:' . $item['sub_name_normalized'],
            $item['account_code'] !== ''
                ? 'AKUN:' . $item['account_code']
                : 'AKUN:UNAVAILABLE',
        ]);
    }

    /**
     * Pilih kandidat deterministic tanpa fuzzy.
     *
     * Positive signals:
     * 1. uraian normalized exact;
     * 2. nominal sama (bila diaktifkan);
     * 3. nama subkomponen sama;
     * 4. kode akun sama jika tersedia;
     * 5. urutan sumber paling awal sebagai tie-breaker.
     *
     * Nama subkomponen TIDAK pernah menjadi hard mismatch ketika salah satu
     * kode subkomponen kosong.
     */
    private function choosePartFBestCandidate(
        array $rabItem,
        array $candidates,
        bool $preferSameAmount = true
    ): int {
        $bestIndex = null;
        $bestScore = null;

        foreach ($candidates as $index => $candidate) {
            $score = 0;

            if (
                (string) ($candidate['comparison_detail'] ?? '')
                === (string) ($rabItem['comparison_detail'] ?? '')
            ) {
                $score += 100;
            }

            if (
                $preferSameAmount
                && $this->partFAmountEqual(
                    (float) ($candidate['amount'] ?? 0),
                    (float) ($rabItem['amount'] ?? 0)
                )
            ) {
                $score += 50;
            }

            if ($this->partFSubcomponentPositiveMatch(
                $rabItem,
                $candidate
            )) {
                $score += 20;
            }

            $rabAccount = (string) ($rabItem['account_code'] ?? '');
            $candidateAccount =
                (string) ($candidate['account_code'] ?? '');

            if (
                $rabAccount !== ''
                && $candidateAccount !== ''
                && $rabAccount === $candidateAccount
            ) {
                $score += 10;
            }

            if ($bestScore === null || $score > $bestScore) {
                $bestScore = $score;
                $bestIndex = (int) $index;
            }
        }

        return $bestIndex ?? (int) array_key_first($candidates);
    }

    /**
     * Nama subkomponen hanya memberi bobot positif.
     *
     * Jika salah satu nama kosong atau keduanya berbeda, kandidat tetap boleh
     * dipasangkan selama hierarchy wajib dan akun masih compatible.
     */
    private function partFSubcomponentPositiveMatch(
        array $left,
        array $right
    ): bool {
        $leftName = (string) (
            $left['sub_name_normalized'] ?? ''
        );

        $rightName = (string) (
            $right['sub_name_normalized'] ?? ''
        );

        return
            $leftName !== ''
            && $rightName !== ''
            && $leftName === $rightName;
    }

    private function partFAmountEqual(
        float $left,
        float $right
    ): bool {
        return
            (int) round($left)
            === (int) round($right);
    }

    /**
     * Normalisasi hierarchy name hanya bersifat teknis.
     */
    private function normalizePartFHierarchyName(
        string $value
    ): string {
        $text = mb_strtoupper(
            $this->normalizeComparableText($value)
        );

        if ($text === '') {
            return '';
        }

        $text = preg_replace(
            '/\s*([\/,:;])\s*/u',
            '$1',
            $text
        ) ?? $text;

        return preg_replace('/\s+/u', ' ', trim($text))
            ?? trim($text);
    }

    /**
     * Normalisasi khusus Uraian Detail untuk RKA-RAB.
     *
     * Tidak melakukan typo correction / fuzzy matching.
     */
    private function normalizePartFRabDetailText(
        string $value
    ): string {
        $text = $this->normalizeComparableText($value);

        if ($text === '') {
            return '';
        }

        // Case-insensitive comparison.
        $text = mb_strtoupper($text);

        // Normalize smart quotes.
        $text = str_replace(
            ['’', '‘', '“', '”'],
            ["'", "'", '"', '"'],
            $text
        );

        // D.K.I. / D. K. I. / DKI -> DKI.
        $text = preg_replace(
            '/\bD\s*\.\s*K\s*\.\s*I\s*\.?\b/u',
            'DKI',
            $text
        ) ?? $text;

        /*
         * Revised quantity-suffix rule.
         *
         * Hanya formula kuantitas di AKHIR uraian yang dibuang.
         * Pola substantif seperti "(PP)", "(ISO)", "(JAWA BARAT)" tetap ada
         * karena tidak berbentuk angka + satuan.
         *
         * Contoh yang dibuang:
         * [2 PKT]
         * (2 PKT)
         * [10 ORG]
         * (10 org)
         * [100 BH x 1 KGT]
         * [7 org x 1 kgt]
         * (43 org x 1.0 thn)
         * (3 org x 2 pp x 8 kgt)
         * [45 org x 1 kgt x 1 hr x 1 kl   // bracket penutup hilang
         * (45 org x 1 kgt x 1 hr x 1 kl   // parenthesis penutup hilang
         */
        $quantityUnit =
            '(?:'
            . 'ORG|ORANG|PEGAWAI|SDM'
            . '|HR|HARI|OH'
            . '|JAM|OJ'
            . '|BLN|BULAN|OB'
            . '|THN|TAHUN'
            . '|KGT|KEG|KEGIATAN'
            . '|KL|KALI|OK'
            . '|UNIT'
            . '|BH|BUAH'
            . '|PKT|PAKET'
            . '|PLTH|PELATIHAN'
            . '|LBR|LEMBAR'
            . '|STKR'
            . '|SET'
            . '|PCS|PCE'
            . '|BOX|DOS'
            . '|RIM'
            . '|BOTOL'
            . '|PAX'
            . '|PP'
            . ')';

        $quantityTerm =
            '\d+(?:[.,]\d+)?\s*' . $quantityUnit;

        $quantityExpression =
            $quantityTerm
            . '(?:\s*(?:X|×)\s*'
            . $quantityTerm
            . ')*';

        // Maksimum beberapa suffix berurutan; contoh data hasil import dapat
        // meninggalkan quote/spasi teknis di ujung.
        for ($i = 0; $i < 4; $i++) {
            $before = $text;

            // Bracket/parenthesis:
            // [2 PKT], (10 ORG), [100 BH x 1 KGT], dst.
            //
            // Import RKA tertentu dapat kehilangan bracket penutup.
            // Karena pola hanya dibuang bila berada di AKHIR uraian dan
            // seluruh isinya valid sebagai quantity expression, bentuk:
            // [45 ORG x 1 KGT x 1 HR x 1 KL
            // (45 ORG x 1 KGT x 1 HR x 1 KL
            // tetap diperlakukan sebagai suffix teknis.
            $text = preg_replace(
                '/\s*[\'"]?\s*(?:'
                . '\[\s*' . $quantityExpression . '\s*\]?'
                . '|'
                . '\(\s*' . $quantityExpression . '\s*\)?'
                . ')\s*[\'"]?\s*$/u',
                '',
                $text
            ) ?? $text;

            // Formula tanpa bracket di akhir:
            // 2 PKT
            // 7 ORG x 1 KGT
            $text = preg_replace(
                '/\s+'
                . $quantityExpression
                . '\s*$/u',
                '',
                $text
            ) ?? $text;

            if ($text === $before) {
                break;
            }
        }

        // Normalisasi spacing tanda baca tanpa menghapus tanda baca substantif.
        $text = preg_replace(
            '/\(\s+/u',
            '(',
            $text
        ) ?? $text;

        $text = preg_replace(
            '/\s+\)/u',
            ')',
            $text
        ) ?? $text;

        $text = preg_replace(
            '/\s*\/\s*/u',
            '/',
            $text
        ) ?? $text;

        $text = preg_replace(
            '/\s*,\s*/u',
            ', ',
            $text
        ) ?? $text;

        $text = preg_replace(
            '/\s+/u',
            ' ',
            trim($text)
        ) ?? trim($text);

        return $text;
    }

    private function partFRoScopeKey(object $row): string
    {
        $parts = [
            $this->canonicalProgramCode(
                $row->kode_program ?? ''
            ),
            trim((string) ($row->kode_kegiatan ?? '')),
            trim((string) ($row->kode_kro ?? '')),
            trim((string) ($row->kode_ro ?? '')),
        ];

        if (in_array('', $parts, true)) {
            return '';
        }

        return implode('|', $parts);
    }

    /**
     * Finding RKA-RAB menyimpan RKA sebagai sumber authoritative dan RAB
     * sebagai pembanding. Salah satu item boleh null untuk missing row.
     */
    private function partFRabFinding(
        string $jenisTemuan,
        ?array $rkaItem,
        ?array $rabItem,
        string $rkaDocumentID,
        string $rabDocumentID,
        string $message
    ): array {
        $context = $rkaItem ?? $rabItem ?? [];

        $rkaAmount = $rkaItem !== null
            ? (float) ($rkaItem['amount'] ?? 0)
            : null;

        $rabAmount = $rabItem !== null
            ? (float) ($rabItem['amount'] ?? 0)
            : null;

        return [
            'jenis_temuan' => $jenisTemuan,
            'status_sistem' => 'TIDAK_SESUAI',
            'documentID_sumber' => $rkaDocumentID,
            'documentID_pembanding' => $rabDocumentID,
            'kode_program' =>
                ($context['kode_program'] ?? '') ?: null,
            'kode_kegiatan' =>
                ($context['kode_kegiatan'] ?? '') ?: null,
            'kode_kro' =>
                ($context['kode_kro'] ?? '') ?: null,
            'kode_ro' =>
                ($context['kode_ro'] ?? '') ?: null,
            'kode_komponen' =>
                ($context['kode_komponen'] ?? '') ?: null,
            'kode_subkomponen' =>
                ($context['kode_subkomponen'] ?? '') ?: null,
            'kode_akun' =>
                ($context['kode_akun'] ?? '') ?: null,
            'nilai_sumber_text' =>
                $rkaItem['display_detail'] ?? null,
            'nilai_pembanding_text' =>
                $rabItem['display_detail'] ?? null,
            'nilai_sumber_nominal' => $rkaAmount,
            'nilai_pembanding_nominal' => $rabAmount,
            'selisih_nominal' =>
                $rkaAmount !== null && $rabAmount !== null
                    ? $rkaAmount - $rabAmount
                    : null,
            'pesan_sistem' => $message,
            'metadata_json' => [
                'comparison' => 'RKA_RAB',
                'pairing_rule' => 'ONE_TO_ONE_DETERMINISTIC',
                'rka_hierarchy' =>
                    $rkaItem['effective_hierarchy_label']
                    ?? null,
                'rab_hierarchy' =>
                    $rabItem['effective_hierarchy_label']
                    ?? null,
                'rka_comparison_detail' =>
                    $rkaItem['comparison_detail']
                    ?? null,
                'rab_comparison_detail' =>
                    $rabItem['comparison_detail']
                    ?? null,
            ],
        ];
    }

    private function upsertPartFSystemNote(
        int $penelitianID,
        int $urutan,
        string $source,
        string $text,
        ?int $temuanID,
        User $user
    ): int {
        if (!in_array($source, ['SYSTEM_RULE', 'SYSTEM_AI'], true)) {
            throw new \RuntimeException(
                'Sumber catatan sistem Bagian F tidak valid.'
            );
        }

        // Urutan bertindak sebagai stable slot agar override user pada system
        // note dapat dipertahankan saat rerun.
        $existing = DB::table('penelitian_catatan')
            ->where('penelitianID', $penelitianID)
            ->whereIn('sumber_catatan', [
                'SYSTEM_RULE',
                'SYSTEM_AI',
            ])
            ->where('urutan', $urutan)
            ->first();

        $payload = [
            'temuanID' => $temuanID,
            'sumber_catatan' => $source,
            'catatan_sistem' => $text,
            'updated_by' => $user->userID,
            'updated_at' => now(),
        ];

        if ($existing) {
            // catatan_user + dihapus_user sengaja dipertahankan.
            DB::table('penelitian_catatan')
                ->where('catatanID', $existing->catatanID)
                ->update($payload);

            return (int) $existing->catatanID;
        }

        return (int) DB::table('penelitian_catatan')->insertGetId([
            'penelitianID' => $penelitianID,
            'urutan' => $urutan,
            ...$payload,
            'catatan_user' => null,
            'dihapus_user' => 0,
            'created_by' => $user->userID,
            'created_at' => now(),
        ]);
    }

    private function evaluateA1(object $penelitian, array $documents): array
    {
        $renjaID = $documents['RENJA'];
        $rkaID = $documents['RKA'];

        if (!$renjaID) {
            return $this->confirmationWithFinding(
                'Dokumen RENJA belum dipilih sehingga kesesuaian KRO, RO, dan Komponen belum dapat diperiksa.',
                [
                    'jenis_temuan' => 'A1_RENJA_BELUM_DIPILIH',
                    'status_sistem' => 'PERLU_KONFIRMASI',
                    'documentID_sumber' => null,
                    'documentID_pembanding' => $rkaID,
                    'pesan_sistem' => 'Dokumen RENJA belum dipilih pada workspace.',
                    'metadata_json' => ['reason' => 'RENJA_NOT_SELECTED'],
                ]
            );
        }

        $renjaRows = $this->renjaScopedQuery($penelitian, $renjaID)
            ->whereNotNull('kode_kegiatan')
            ->whereNotNull('kode_kro')
            ->whereNotNull('kode_ro')
            ->whereNotNull('kode_komponen')
            ->get([
                'kode_kegiatan',
                'kode_kro',
                'kode_ro',
                'kode_komponen',
            ])
            ->map(fn ($row) => $this->hierarchyTuple((array) $row))
            ->filter(fn ($row) => $row['key'] !== '')
            ->keyBy('key');

        $rkaRows = DB::table('rka')
            ->where('documentID', $rkaID)
            ->where('kode_satker', $penelitian->kode_satker)
            ->where('tahun_anggaran', $penelitian->tahun_anggaran)
            ->whereNotNull('kode_kegiatan')
            ->whereNotNull('kode_kro')
            ->whereNotNull('kode_ro')
            ->whereNotNull('kode_komponen')
            ->get([
                'kode_kegiatan',
                'kode_kro',
                'kode_ro',
                'kode_komponen',
            ])
            ->map(fn ($row) => $this->hierarchyTuple((array) $row))
            ->filter(fn ($row) => $row['key'] !== '')
            ->keyBy('key');

        if ($renjaRows->isEmpty()) {
            return $this->confirmationWithFinding(
                sprintf(
                    'Data RENJA untuk Satker %s pada TA %s tidak ditemukan sampai level Komponen sehingga pemeriksaan nomenklatur belum dapat dilakukan.',
                    $penelitian->nama_satker,
                    $penelitian->tahun_anggaran
                ),
                [
                    'jenis_temuan' => 'A1_DATA_RENJA_TIDAK_DITEMUKAN',
                    'status_sistem' => 'PERLU_KONFIRMASI',
                    'documentID_sumber' => $renjaID,
                    'documentID_pembanding' => $rkaID,
                    'pesan_sistem' => 'Data RENJA sesuai Satker/Tahun Anggaran tidak ditemukan sampai level Komponen.',
                    'metadata_json' => [
                        'kode_satker' => $penelitian->kode_satker,
                        'nama_satker' => $penelitian->nama_satker,
                        'tahun_anggaran' => (int) $penelitian->tahun_anggaran,
                    ],
                ]
            );
        }

        if ($rkaRows->isEmpty()) {
            return $this->confirmationWithFinding(
                'Data RKA tidak ditemukan sampai level Komponen. Pemeriksaan nomenklatur perlu dikonfirmasi.',
                [
                    'jenis_temuan' => 'A1_DATA_RKA_TIDAK_DITEMUKAN',
                    'status_sistem' => 'PERLU_KONFIRMASI',
                    'documentID_sumber' => $renjaID,
                    'documentID_pembanding' => $rkaID,
                    'pesan_sistem' => 'Data RKA tidak ditemukan sampai level Komponen.',
                ]
            );
        }

        $missingInRenja = $rkaRows->diffKeys($renjaRows);
        $missingInRka = $renjaRows->diffKeys($rkaRows);
        $findings = [];

        foreach ($missingInRenja as $tuple) {
            $findings[] = [
                'jenis_temuan' => 'A1_RKA_TIDAK_ADA_DI_RENJA',
                'status_sistem' => 'TIDAK_SESUAI',
                'documentID_sumber' => $renjaID,
                'documentID_pembanding' => $rkaID,
                'kode_kegiatan' => $tuple['kode_kegiatan'],
                'kode_kro' => $tuple['kode_kro'],
                'kode_ro' => $tuple['kode_ro'],
                'kode_komponen' => $tuple['kode_komponen'],
                'nilai_sumber_text' => null,
                'nilai_pembanding_text' => $tuple['label'],
                'pesan_sistem' => 'Hierarki Kegiatan/KRO/RO/Komponen terdapat pada RKA tetapi tidak ditemukan pada RENJA.',
                'metadata_json' => ['direction' => 'RKA_NOT_IN_RENJA'],
            ];
        }

        foreach ($missingInRka as $tuple) {
            $findings[] = [
                'jenis_temuan' => 'A1_RENJA_TIDAK_ADA_DI_RKA',
                'status_sistem' => 'TIDAK_SESUAI',
                'documentID_sumber' => $renjaID,
                'documentID_pembanding' => $rkaID,
                'kode_kegiatan' => $tuple['kode_kegiatan'],
                'kode_kro' => $tuple['kode_kro'],
                'kode_ro' => $tuple['kode_ro'],
                'kode_komponen' => $tuple['kode_komponen'],
                'nilai_sumber_text' => $tuple['label'],
                'nilai_pembanding_text' => null,
                'pesan_sistem' => 'Hierarki Kegiatan/KRO/RO/Komponen terdapat pada RENJA tetapi tidak ditemukan pada RKA.',
                'metadata_json' => ['direction' => 'RENJA_NOT_IN_RKA'],
            ];
        }

        if (empty($findings)) {
            return [
                'status' => 'SESUAI',
                'explanation' => $this->formatExplanation(
                    sprintf(
                        'Seluruh kode KRO, RO, dan Komponen beserta hierarki Kegiatan → KRO → RO → Komponen telah sesuai antara RENJA dan RKA. Diperiksa %d kombinasi hierarki',
                        $rkaRows->count()
                    )
                ),
                'metadata' => [
                    'jumlah_hierarki_renja' => $renjaRows->count(),
                    'jumlah_hierarki_rka' => $rkaRows->count(),
                    'tidak_ada_di_renja' => 0,
                    'tidak_ada_di_rka' => 0,
                ],
                'findings' => [],
            ];
        }

        $detailMessages = [];

        foreach ($missingInRenja as $tuple) {
            $detailMessages[] = $tuple['label']
                . ' terdapat pada RKA tetapi tidak ditemukan pada RENJA';
        }

        foreach ($missingInRka as $tuple) {
            $detailMessages[] = $tuple['label']
                . ' terdapat pada RENJA tetapi tidak ditemukan pada RKA';
        }

        return [
            'status' => 'TIDAK_SESUAI',
            'explanation' => $this->formatExplanation(
                'Ditemukan ketidaksesuaian kode/hierarki KRO, RO, dan Komponen',
                $detailMessages
            ),
            'metadata' => [
                'jumlah_hierarki_renja' => $renjaRows->count(),
                'jumlah_hierarki_rka' => $rkaRows->count(),
                'tidak_ada_di_renja' => $missingInRenja->count(),
                'tidak_ada_di_rka' => $missingInRka->count(),
            ],
            'findings' => $findings,
        ];
    }

    private function evaluateA2(object $penelitian, array $documents): array
    {
        $scopes = DB::table('penelitian_program_kegiatan')
            ->where('penelitianID', $penelitian->penelitianID)
            ->orderBy('urutan')
            ->get(['kode_program', 'nama_program'])
            ->map(fn ($row) => [
                'kode_program' => $this->canonicalProgramCode($row->kode_program),
                'nama_program' => trim((string) $row->nama_program),
            ])
            ->filter(fn ($row) => $row['kode_program'] !== '')
            ->unique('kode_program')
            ->values();

        if ($scopes->isEmpty()) {
            return $this->confirmationWithFinding(
                'Program pada RKA terpilih tidak ditemukan sehingga Sasaran Program belum dapat diperiksa.',
                [
                    'jenis_temuan' => 'A2_SCOPE_PROGRAM_RKA_TIDAK_DITEMUKAN',
                    'status_sistem' => 'PERLU_KONFIRMASI',
                    'documentID_pembanding' => $documents['RKA'],
                    'pesan_sistem' => 'Program pada RKA terpilih tidak ditemukan.',
                ]
            );
        }

        if (!$documents['RENJA']) {
            return $this->confirmationForScopes(
                $scopes,
                'A2_RENJA_BELUM_DIPILIH',
                'Dokumen RENJA belum dipilih sehingga Sasaran Program belum dapat dibandingkan dengan TOR.',
                $documents['RENJA'],
                null,
                'program'
            );
        }

        $renjaRows = $this->renjaScopedQuery($penelitian, $documents['RENJA'])
            ->get(['kode_program', 'sasaran_program'])
            ->map(fn ($row) => [
                'kode_program' => $this->canonicalProgramCode($row->kode_program),
                'sasaran_program' => $this->normalizeComparableText($row->sasaran_program),
                'sasaran_raw' => trim((string) ($row->sasaran_program ?? '')),
            ]);

        $torRows = $this->selectedTorRows($penelitian, $documents['TOR'], [
            't.documentID',
            'fm.document_name',
            't.kode_program',
            't.nama_program',
            't.sasaran_program',
        ])->map(fn ($row) => [
            'documentID' => (string) $row->documentID,
            'document_name' => (string) $row->document_name,
            'kode_program' => $this->canonicalProgramCode($row->kode_program),
            'nama_program' => trim((string) ($row->nama_program ?? '')),
            'sasaran_program' => $this->normalizeComparableText($row->sasaran_program),
            'sasaran_raw' => trim((string) ($row->sasaran_program ?? '')),
        ]);

        $scopeResults = [];
        $findings = [];

        foreach ($scopes as $scope) {
            $code = $scope['kode_program'];
            $renjaCandidates = $renjaRows
                ->where('kode_program', $code)
                ->filter(fn ($row) => $row['sasaran_program'] !== '')
                ->values();

            $renjaValues = $renjaCandidates
                ->pluck('sasaran_program')
                ->unique()
                ->values();

            $torCandidates = $torRows
                ->where('kode_program', $code)
                ->values();

            if ($renjaValues->isEmpty()) {
                $scopeResults[] = [
                    'status' => 'PERLU_KONFIRMASI',
                    'message' => "Program {$code} - {$scope['nama_program']}: Sasaran Program RENJA tidak ditemukan.",
                    'details' => [
                        "Program {$code} - {$scope['nama_program']}: Sasaran Program RENJA tidak ditemukan",
                    ],
                ];
                $findings[] = [
                    'jenis_temuan' => 'A2_SASARAN_PROGRAM_RENJA_TIDAK_TERSEDIA',
                    'status_sistem' => 'PERLU_KONFIRMASI',
                    'documentID_sumber' => $documents['RENJA'],
                    'kode_program' => $code,
                    'pesan_sistem' => 'Sasaran Program pada RENJA tidak ditemukan untuk Program yang terdapat pada RKA.',
                    'metadata_json' => ['nama_program' => $scope['nama_program']],
                ];
                continue;
            }

            if ($renjaValues->count() > 1) {
                $scopeResults[] = [
                    'status' => 'PERLU_KONFIRMASI',
                    'message' => "Program {$code} - {$scope['nama_program']}: RENJA memuat lebih dari satu Sasaran Program yang berbeda.",
                    'details' => [
                        "Program {$code} - {$scope['nama_program']}: RENJA memuat lebih dari satu Sasaran Program yang berbeda",
                    ],
                ];
                $findings[] = [
                    'jenis_temuan' => 'A2_SASARAN_PROGRAM_RENJA_AMBIGU',
                    'status_sistem' => 'PERLU_KONFIRMASI',
                    'documentID_sumber' => $documents['RENJA'],
                    'kode_program' => $code,
                    'nilai_sumber_text' => $renjaCandidates->pluck('sasaran_raw')->unique()->implode(' || '),
                    'pesan_sistem' => 'RENJA memuat lebih dari satu Sasaran Program berbeda dalam Program yang sama.',
                    'metadata_json' => ['nama_program' => $scope['nama_program']],
                ];
                continue;
            }

            $renjaValue = (string) $renjaValues->first();
            $renjaRaw = (string) ($renjaCandidates->first()['sasaran_raw'] ?? $renjaValue);

            if ($torCandidates->isEmpty()) {
                $scopeResults[] = [
                    'status' => 'PERLU_KONFIRMASI',
                    'message' => "Program {$code} - {$scope['nama_program']}: TOR untuk Program ini belum dipilih/tersedia.",
                    'details' => [
                        "Program {$code} - {$scope['nama_program']}: TOR untuk Program ini belum dipilih/tersedia",
                    ],
                ];
                $findings[] = [
                    'jenis_temuan' => 'A2_TOR_PROGRAM_TIDAK_TERSEDIA',
                    'status_sistem' => 'PERLU_KONFIRMASI',
                    'documentID_sumber' => $documents['RENJA'],
                    'kode_program' => $code,
                    'nilai_sumber_text' => $renjaRaw,
                    'pesan_sistem' => 'TOR yang dapat digunakan untuk membandingkan Sasaran Program belum dipilih/tersedia.',
                    'metadata_json' => ['nama_program' => $scope['nama_program']],
                ];
                continue;
            }

            $localStatuses = [];
            $localMessages = [];
            $localDetails = [];

            foreach ($torCandidates as $tor) {
                if ($tor['sasaran_program'] === '') {
                    $localStatuses[] = 'PERLU_KONFIRMASI';
                    $localMessages[] = "TOR {$tor['document_name']}: Sasaran Program tidak tersedia.";
                    $localDetails[] = "Program {$code} - {$scope['nama_program']}: TOR {$tor['document_name']}: Sasaran Program tidak tersedia";
                    $findings[] = [
                        'jenis_temuan' => 'A2_SASARAN_PROGRAM_TOR_KOSONG',
                        'status_sistem' => 'PERLU_KONFIRMASI',
                        'documentID_sumber' => $documents['RENJA'],
                        'documentID_pembanding' => $tor['documentID'],
                        'kode_program' => $code,
                        'nilai_sumber_text' => $renjaRaw,
                        'nilai_pembanding_text' => null,
                        'pesan_sistem' => 'Sasaran Program tidak tersedia pada TOR terpilih.',
                        'metadata_json' => [
                            'nama_program' => $scope['nama_program'],
                            'document_name' => $tor['document_name'],
                        ],
                    ];
                    continue;
                }

                if ($tor['sasaran_program'] !== $renjaValue) {
                    $localStatuses[] = 'TIDAK_SESUAI';
                    $localMessages[] = "TOR {$tor['document_name']}: teks Sasaran Program berbeda dengan RENJA.";
                    $localDetails[] = "Program {$code} - {$scope['nama_program']}: TOR {$tor['document_name']}: teks Sasaran Program berbeda dengan RENJA";
                    $findings[] = [
                        'jenis_temuan' => 'A2_SASARAN_PROGRAM_TIDAK_SESUAI',
                        'status_sistem' => 'TIDAK_SESUAI',
                        'documentID_sumber' => $documents['RENJA'],
                        'documentID_pembanding' => $tor['documentID'],
                        'kode_program' => $code,
                        'nilai_sumber_text' => $renjaRaw,
                        'nilai_pembanding_text' => $tor['sasaran_raw'],
                        'pesan_sistem' => 'Sasaran Program pada TOR tidak sama persis dengan Sasaran Program pada RENJA setelah normalisasi teknis spasi/line break.',
                        'metadata_json' => [
                            'nama_program' => $scope['nama_program'],
                            'document_name' => $tor['document_name'],
                        ],
                    ];
                    continue;
                }

                $localStatuses[] = 'SESUAI';
            }

            $scopeStatus = $this->aggregateStatuses($localStatuses);
            $scopeResults[] = [
                'status' => $scopeStatus,
                'message' => $scopeStatus === 'SESUAI'
                    ? "Program {$code} - {$scope['nama_program']}: Sasaran Program pada seluruh TOR terpilih sesuai dengan RENJA."
                    : "Program {$code} - {$scope['nama_program']}: " . implode(' ', $localMessages),
                'details' => $scopeStatus === 'SESUAI' ? [] : $localDetails,
            ];
        }

        return $this->aggregateScopeEvaluation($scopeResults, $findings, 'Sasaran Program');
    }

    private function evaluateA4(object $penelitian, array $documents): array
    {
        $scopes = DB::table('penelitian_program_kegiatan')
            ->where('penelitianID', $penelitian->penelitianID)
            ->orderBy('urutan')
            ->get([
                'kode_program',
                'nama_program',
                'kode_kegiatan',
                'nama_kegiatan',
            ])
            ->map(fn ($row) => [
                'kode_program' => $this->canonicalProgramCode($row->kode_program),
                'nama_program' => trim((string) $row->nama_program),
                'kode_kegiatan' => trim((string) $row->kode_kegiatan),
                'nama_kegiatan' => trim((string) $row->nama_kegiatan),
            ])
            ->filter(fn ($row) => $row['kode_kegiatan'] !== '')
            ->values();

        if ($scopes->isEmpty()) {
            return $this->confirmationWithFinding(
                'Kegiatan pada RKA terpilih tidak ditemukan sehingga Sasaran Kegiatan belum dapat diperiksa.',
                [
                    'jenis_temuan' => 'A4_SCOPE_KEGIATAN_RKA_TIDAK_DITEMUKAN',
                    'status_sistem' => 'PERLU_KONFIRMASI',
                    'documentID_pembanding' => $documents['RKA'],
                    'pesan_sistem' => 'Kegiatan pada RKA terpilih tidak ditemukan.',
                ]
            );
        }

        if (!$documents['RENJA']) {
            return $this->confirmationForScopes(
                $scopes,
                'A4_RENJA_BELUM_DIPILIH',
                'Dokumen RENJA belum dipilih sehingga Sasaran Kegiatan belum dapat dibandingkan dengan TOR.',
                null,
                null,
                'kegiatan'
            );
        }

        $renjaRows = $this->renjaScopedQuery($penelitian, $documents['RENJA'])
            ->get(['kode_program', 'kode_kegiatan', 'sasaran_kegiatan'])
            ->map(fn ($row) => [
                'kode_program' => $this->canonicalProgramCode($row->kode_program),
                'kode_kegiatan' => trim((string) $row->kode_kegiatan),
                'sasaran_kegiatan' => $this->normalizeComparableText($row->sasaran_kegiatan),
                'sasaran_raw' => trim((string) ($row->sasaran_kegiatan ?? '')),
            ]);

        $torRows = $this->selectedTorRows($penelitian, $documents['TOR'], [
            't.documentID',
            'fm.document_name',
            't.kode_program',
            't.kode_kegiatan',
            't.nama_kegiatan',
            't.sasaran_kegiatan',
        ])->map(fn ($row) => [
            'documentID' => (string) $row->documentID,
            'document_name' => (string) $row->document_name,
            'kode_program' => $this->canonicalProgramCode($row->kode_program),
            'kode_kegiatan' => trim((string) $row->kode_kegiatan),
            'nama_kegiatan' => trim((string) ($row->nama_kegiatan ?? '')),
            'sasaran_kegiatan' => $this->normalizeComparableText($row->sasaran_kegiatan),
            'sasaran_raw' => trim((string) ($row->sasaran_kegiatan ?? '')),
        ]);

        $scopeResults = [];
        $findings = [];

        foreach ($scopes as $scope) {
            $programCode = $scope['kode_program'];
            $kegiatanCode = $scope['kode_kegiatan'];

            $renjaCandidates = $renjaRows
                ->filter(fn ($row) => $row['kode_program'] === $programCode
                    && $row['kode_kegiatan'] === $kegiatanCode)
                ->filter(fn ($row) => $row['sasaran_kegiatan'] !== '')
                ->values();

            $renjaValues = $renjaCandidates
                ->pluck('sasaran_kegiatan')
                ->unique()
                ->values();

            $torCandidates = $torRows
                ->filter(fn ($row) => $row['kode_program'] === $programCode
                    && $row['kode_kegiatan'] === $kegiatanCode)
                ->values();

            $scopeLabel = "Kegiatan {$kegiatanCode} - {$scope['nama_kegiatan']}";

            if ($renjaValues->isEmpty()) {
                $scopeResults[] = [
                    'status' => 'PERLU_KONFIRMASI',
                    'message' => "{$scopeLabel}: Sasaran Kegiatan RENJA tidak ditemukan.",
                    'details' => [
                        "{$scopeLabel}: Sasaran Kegiatan RENJA tidak ditemukan",
                    ],
                ];
                $findings[] = [
                    'jenis_temuan' => 'A4_SASARAN_KEGIATAN_RENJA_TIDAK_TERSEDIA',
                    'status_sistem' => 'PERLU_KONFIRMASI',
                    'documentID_sumber' => $documents['RENJA'],
                    'kode_program' => $programCode,
                    'kode_kegiatan' => $kegiatanCode,
                    'pesan_sistem' => 'Sasaran Kegiatan pada RENJA tidak ditemukan untuk Kegiatan yang terdapat pada RKA.',
                    'metadata_json' => ['nama_kegiatan' => $scope['nama_kegiatan']],
                ];
                continue;
            }

            if ($renjaValues->count() > 1) {
                $scopeResults[] = [
                    'status' => 'PERLU_KONFIRMASI',
                    'message' => "{$scopeLabel}: RENJA memuat lebih dari satu Sasaran Kegiatan yang berbeda.",
                    'details' => [
                        "{$scopeLabel}: RENJA memuat lebih dari satu Sasaran Kegiatan yang berbeda",
                    ],
                ];
                $findings[] = [
                    'jenis_temuan' => 'A4_SASARAN_KEGIATAN_RENJA_AMBIGU',
                    'status_sistem' => 'PERLU_KONFIRMASI',
                    'documentID_sumber' => $documents['RENJA'],
                    'kode_program' => $programCode,
                    'kode_kegiatan' => $kegiatanCode,
                    'nilai_sumber_text' => $renjaCandidates->pluck('sasaran_raw')->unique()->implode(' || '),
                    'pesan_sistem' => 'RENJA memuat lebih dari satu Sasaran Kegiatan berbeda dalam Kegiatan yang sama.',
                    'metadata_json' => ['nama_kegiatan' => $scope['nama_kegiatan']],
                ];
                continue;
            }

            $renjaValue = (string) $renjaValues->first();
            $renjaRaw = (string) ($renjaCandidates->first()['sasaran_raw'] ?? $renjaValue);

            if ($torCandidates->isEmpty()) {
                $scopeResults[] = [
                    'status' => 'PERLU_KONFIRMASI',
                    'message' => "{$scopeLabel}: TOR untuk Kegiatan ini belum dipilih/tersedia.",
                    'details' => [
                        "{$scopeLabel}: TOR untuk Kegiatan ini belum dipilih/tersedia",
                    ],
                ];
                $findings[] = [
                    'jenis_temuan' => 'A4_TOR_KEGIATAN_TIDAK_TERSEDIA',
                    'status_sistem' => 'PERLU_KONFIRMASI',
                    'documentID_sumber' => $documents['RENJA'],
                    'kode_program' => $programCode,
                    'kode_kegiatan' => $kegiatanCode,
                    'nilai_sumber_text' => $renjaRaw,
                    'pesan_sistem' => 'TOR yang dapat digunakan untuk membandingkan Sasaran Kegiatan belum dipilih/tersedia.',
                    'metadata_json' => ['nama_kegiatan' => $scope['nama_kegiatan']],
                ];
                continue;
            }

            $localStatuses = [];
            $localMessages = [];
            $localDetails = [];

            foreach ($torCandidates as $tor) {
                if ($tor['sasaran_kegiatan'] === '') {
                    $localStatuses[] = 'PERLU_KONFIRMASI';
                    $localMessages[] = "TOR {$tor['document_name']}: Sasaran Kegiatan tidak tersedia.";
                    $localDetails[] = "{$scopeLabel}: TOR {$tor['document_name']}: Sasaran Kegiatan tidak tersedia";
                    $findings[] = [
                        'jenis_temuan' => 'A4_SASARAN_KEGIATAN_TOR_KOSONG',
                        'status_sistem' => 'PERLU_KONFIRMASI',
                        'documentID_sumber' => $documents['RENJA'],
                        'documentID_pembanding' => $tor['documentID'],
                        'kode_program' => $programCode,
                        'kode_kegiatan' => $kegiatanCode,
                        'nilai_sumber_text' => $renjaRaw,
                        'nilai_pembanding_text' => null,
                        'pesan_sistem' => 'Sasaran Kegiatan tidak tersedia pada TOR terpilih.',
                        'metadata_json' => [
                            'nama_kegiatan' => $scope['nama_kegiatan'],
                            'document_name' => $tor['document_name'],
                        ],
                    ];
                    continue;
                }

                if ($tor['sasaran_kegiatan'] !== $renjaValue) {
                    $localStatuses[] = 'TIDAK_SESUAI';
                    $localMessages[] = "TOR {$tor['document_name']}: teks Sasaran Kegiatan berbeda dengan RENJA.";
                    $localDetails[] = "{$scopeLabel}: TOR {$tor['document_name']}: teks Sasaran Kegiatan berbeda dengan RENJA";
                    $findings[] = [
                        'jenis_temuan' => 'A4_SASARAN_KEGIATAN_TIDAK_SESUAI',
                        'status_sistem' => 'TIDAK_SESUAI',
                        'documentID_sumber' => $documents['RENJA'],
                        'documentID_pembanding' => $tor['documentID'],
                        'kode_program' => $programCode,
                        'kode_kegiatan' => $kegiatanCode,
                        'nilai_sumber_text' => $renjaRaw,
                        'nilai_pembanding_text' => $tor['sasaran_raw'],
                        'pesan_sistem' => 'Sasaran Kegiatan pada TOR tidak sama persis dengan Sasaran Kegiatan pada RENJA setelah normalisasi teknis spasi/line break.',
                        'metadata_json' => [
                            'nama_kegiatan' => $scope['nama_kegiatan'],
                            'document_name' => $tor['document_name'],
                        ],
                    ];
                    continue;
                }

                $localStatuses[] = 'SESUAI';
            }

            $scopeStatus = $this->aggregateStatuses($localStatuses);
            $scopeResults[] = [
                'status' => $scopeStatus,
                'message' => $scopeStatus === 'SESUAI'
                    ? "{$scopeLabel}: Sasaran Kegiatan pada seluruh TOR terpilih sesuai dengan RENJA."
                    : "{$scopeLabel}: " . implode(' ', $localMessages),
                'details' => $scopeStatus === 'SESUAI' ? [] : $localDetails,
            ];
        }

        return $this->aggregateScopeEvaluation($scopeResults, $findings, 'Sasaran Kegiatan');
    }

    private function aggregateScopeEvaluation(
        array $scopeResults,
        array $findings,
        string $label
    ): array {
        $statuses = array_column($scopeResults, 'status');
        $status = $this->aggregateStatuses($statuses);
        $metadata = $this->statusCountMetadata($statuses);

        if ($status === 'SESUAI') {
            return [
                'status' => 'SESUAI',
                'explanation' => $this->formatExplanation(
                    sprintf(
                        'Seluruh %s yang dapat diperiksa telah sesuai antara RENJA dan TOR. Diperiksa %d ruang lingkup',
                        $label,
                        count($scopeResults)
                    )
                ),
                'metadata' => $metadata,
                'findings' => [],
            ];
        }

        $messages = collect($scopeResults)
            ->filter(fn ($row) => $row['status'] !== 'SESUAI')
            ->flatMap(function ($row) {
                $details = $row['details'] ?? [];

                if (!empty($details)) {
                    return $details;
                }

                return isset($row['message']) ? [$row['message']] : [];
            })
            ->filter(fn ($message) => trim((string) $message) !== '')
            ->values()
            ->all();

        $hasMismatch = ($metadata['tidak_sesuai'] ?? 0) > 0;
        $hasConfirmation = ($metadata['perlu_konfirmasi'] ?? 0) > 0;

        if ($hasMismatch && $hasConfirmation) {
            $summary = "Ditemukan ketidaksesuaian {$label} antara RENJA dan TOR serta terdapat data yang masih perlu dikonfirmasi";
        } elseif ($hasMismatch) {
            $summary = "Ditemukan ketidaksesuaian {$label} antara RENJA dan TOR";
        } else {
            $summary = "Terdapat {$label} yang belum dapat dipastikan dan perlu dikonfirmasi";
        }

        return [
            'status' => $status,
            'explanation' => $this->formatExplanation($summary, $messages),
            'metadata' => $metadata,
            'findings' => $findings,
        ];
    }

    private function aggregateStatuses(array $statuses): string
    {
        if (in_array('TIDAK_SESUAI', $statuses, true)) {
            return 'TIDAK_SESUAI';
        }

        if (in_array('PERLU_KONFIRMASI', $statuses, true)) {
            return 'PERLU_KONFIRMASI';
        }

        return 'SESUAI';
    }

    private function statusCountMetadata(array $statuses): array
    {
        return [
            'jumlah_scope' => count($statuses),
            'sesuai' => count(array_filter($statuses, fn ($status) => $status === 'SESUAI')),
            'tidak_sesuai' => count(array_filter($statuses, fn ($status) => $status === 'TIDAK_SESUAI')),
            'perlu_konfirmasi' => count(array_filter($statuses, fn ($status) => $status === 'PERLU_KONFIRMASI')),
        ];
    }

    private function evaluateB1(object $penelitian, array $documents): array
    {
        $rkaID = $documents['RKA'];
        $renjaID = $documents['RENJA'];

        $rkaQuery = $this->rkaScopedQuery($penelitian, $rkaID);
        $rkaCount = (clone $rkaQuery)->count();

        if ($rkaCount === 0) {
            return $this->confirmationWithFinding(
                'Data RKA tidak ditemukan untuk Satker dan Tahun Anggaran penelitian sehingga Total Pagu belum dapat diperiksa.',
                [
                    'jenis_temuan' => 'B1_DATA_RKA_TIDAK_DITEMUKAN',
                    'status_sistem' => 'PERLU_KONFIRMASI',
                    'documentID_pembanding' => $rkaID,
                    'pesan_sistem' => 'Data RKA sesuai Satker/Tahun Anggaran tidak ditemukan.',
                ]
            );
        }

        if (!$renjaID) {
            return $this->confirmationWithFinding(
                'Dokumen RENJA belum dipilih sehingga Total Pagu RKA belum dapat dibandingkan dengan RENJA.',
                [
                    'jenis_temuan' => 'B1_RENJA_BELUM_DIPILIH',
                    'status_sistem' => 'PERLU_KONFIRMASI',
                    'documentID_pembanding' => $rkaID,
                    'pesan_sistem' => 'Dokumen RENJA belum dipilih pada workspace.',
                ]
            );
        }

        $renjaQuery = $this->renjaScopedQuery($penelitian, $renjaID);
        $renjaCount = (clone $renjaQuery)->count();

        if ($renjaCount === 0) {
            return $this->confirmationWithFinding(
                sprintf(
                    'Data RENJA untuk Satker %s pada TA %s tidak ditemukan sehingga Total Pagu belum dapat dibandingkan.',
                    $penelitian->nama_satker,
                    $penelitian->tahun_anggaran
                ),
                [
                    'jenis_temuan' => 'B1_DATA_RENJA_TIDAK_DITEMUKAN',
                    'status_sistem' => 'PERLU_KONFIRMASI',
                    'documentID_sumber' => $renjaID,
                    'documentID_pembanding' => $rkaID,
                    'pesan_sistem' => 'Data RENJA sesuai Satker/Tahun Anggaran tidak ditemukan.',
                ]
            );
        }

        $rkaTotal = (int) ((clone $rkaQuery)->sum('jumlah_biaya') ?? 0);
        $renjaTotal = $this->renjaTotalAllocation($renjaQuery);
        $selisih = $rkaTotal - $renjaTotal;

        if ($rkaTotal === $renjaTotal) {
            return [
                'status' => 'SESUAI',
                'explanation' => $this->formatExplanation(
                    sprintf(
                        'Total Pagu RKA sebesar %s telah sesuai dengan Total Pagu RENJA sebesar %s',
                        $this->formatRupiah($rkaTotal),
                        $this->formatRupiah($renjaTotal)
                    )
                ),
                'metadata' => [
                    'pagu_renja' => $renjaTotal,
                    'pagu_rka' => $rkaTotal,
                    'selisih' => 0,
                    'jumlah_baris_renja' => $renjaCount,
                    'jumlah_baris_rka' => $rkaCount,
                    'renja_allocation_source' => 'alokasi_komponen_0',
                    'renja_multiplier' => 1000,
                ],
                'findings' => [],
            ];
        }

        $detail = sprintf(
            'Pagu RENJA = %s; Pagu RKA = %s; selisih RKA - RENJA = %s',
            $this->formatRupiah($renjaTotal),
            $this->formatRupiah($rkaTotal),
            $this->formatRupiah($selisih)
        );

        return [
            'status' => 'TIDAK_SESUAI',
            'explanation' => $this->formatExplanation(
                'Ditemukan perbedaan Total Pagu antara RENJA dan RKA',
                [$detail]
            ),
            'metadata' => [
                'pagu_renja' => $renjaTotal,
                'pagu_rka' => $rkaTotal,
                'selisih' => $selisih,
                'jumlah_baris_renja' => $renjaCount,
                'jumlah_baris_rka' => $rkaCount,
                'renja_allocation_source' => 'alokasi_komponen_0',
                'renja_multiplier' => 1000,
            ],
            'findings' => [[
                'jenis_temuan' => 'B1_TOTAL_PAGU_TIDAK_SESUAI',
                'status_sistem' => 'TIDAK_SESUAI',
                'documentID_sumber' => $renjaID,
                'documentID_pembanding' => $rkaID,
                'nilai_sumber_nominal' => $renjaTotal,
                'nilai_pembanding_nominal' => $rkaTotal,
                'selisih_nominal' => $selisih,
                'pesan_sistem' => 'Total Pagu RENJA dan Total Pagu RKA berbeda.',
            ]],
        ];
    }

    private function evaluateB2Category(
        object $penelitian,
        array $documents,
        string $accountPrefix,
        string $label,
        string $findingPrefix
    ): array {
        $rkaID = $documents['RKA'];
        $renjaID = $documents['RENJA'];

        if (!$renjaID) {
            return $this->confirmationWithFinding(
                "Dokumen RENJA belum dipilih sehingga Pagu {$label} belum dapat diperiksa pada level Komponen.",
                [
                    'jenis_temuan' => $findingPrefix . '_RENJA_BELUM_DIPILIH',
                    'status_sistem' => 'PERLU_KONFIRMASI',
                    'documentID_pembanding' => $rkaID,
                    'pesan_sistem' => "Dokumen RENJA belum dipilih untuk pemeriksaan {$label}.",
                ]
            );
        }

        $renjaBase = $this->renjaScopedQuery($penelitian, $renjaID);
        if ((clone $renjaBase)->count() === 0) {
            return $this->confirmationWithFinding(
                "Data RENJA sesuai Satker/Tahun Anggaran tidak ditemukan sehingga Pagu {$label} belum dapat diperiksa.",
                [
                    'jenis_temuan' => $findingPrefix . '_DATA_RENJA_TIDAK_DITEMUKAN',
                    'status_sistem' => 'PERLU_KONFIRMASI',
                    'documentID_sumber' => $renjaID,
                    'documentID_pembanding' => $rkaID,
                    'pesan_sistem' => "Data RENJA tidak tersedia untuk pemeriksaan {$label}.",
                ]
            );
        }

        /*
         * Business Rule B (revisi):
         * RENJA hanya memiliki pagu sampai level Komponen, sedangkan RKA sampai Akun.
         * Karena itu pagu kategori akun 51/52 hanya boleh dibandingkan langsung terhadap
         * RENJA bila Komponen RKA tersebut hanya memuat kelompok akun kategori yang sama.
         * Jika satu Komponen memuat campuran kelompok akun (mis. 52 + 53), nilai RENJA
         * tidak boleh dianggap sebagai pagu akun 52 saja dan hasilnya PERLU_KONFIRMASI.
         */
        $rkaComponents = $this->rkaComponentAllocationsWithComposition(
            $penelitian,
            $rkaID,
            $accountPrefix
        );

        if ($rkaComponents->isEmpty()) {
            return [
                'status' => 'SESUAI',
                'explanation' => $this->formatExplanation(
                    "Tidak terdapat alokasi {$label} dengan akun berawalan {$accountPrefix} pada RKA sehingga tidak ada Komponen kategori ini yang perlu dibandingkan dengan RENJA"
                ),
                'metadata' => [
                    'account_prefix' => $accountPrefix,
                    'jumlah_komponen_rka' => 0,
                    'total_rka_kategori' => 0,
                    'jumlah_tidak_sesuai' => 0,
                    'jumlah_perlu_konfirmasi' => 0,
                ],
                'findings' => [],
            ];
        }

        $renjaComponents = $this->renjaComponentAllocations($penelitian, $renjaID);
        $findings = [];
        $details = [];
        $componentStatuses = [];
        $matchedRenjaTotal = 0;
        $rkaCategoryTotal = 0;
        $mismatchCount = 0;
        $confirmationCount = 0;
        $pureComparedCount = 0;

        foreach ($rkaComponents as $key => $rkaComponent) {
            $rkaCategoryAmount = (int) $rkaComponent['category_total'];
            $rkaCategoryTotal += $rkaCategoryAmount;
            $renjaComponent = $renjaComponents->get($key);

            /*
             * Jika Komponen RKA sama sekali tidak ada pada RENJA, ini tetap merupakan
             * ketidaksesuaian yang deterministik. Tidak perlu diturunkan menjadi
             * PERLU_KONFIRMASI hanya karena Komponen tersebut juga memiliki akun campuran.
             */
            if (!$renjaComponent) {
                $componentStatuses[] = 'TIDAK_SESUAI';
                $mismatchCount++;

                $details[] = sprintf(
                    '%s: alokasi RKA %s sebesar %s tidak memiliki Komponen bersesuaian pada RENJA',
                    $rkaComponent['label'],
                    $label,
                    $this->formatRupiah($rkaCategoryAmount)
                );

                $findings[] = [
                    'jenis_temuan' => $findingPrefix . '_KOMPONEN_TIDAK_ADA_DI_RENJA',
                    'status_sistem' => 'TIDAK_SESUAI',
                    'documentID_sumber' => $renjaID,
                    'documentID_pembanding' => $rkaID,
                    'kode_kegiatan' => $rkaComponent['kode_kegiatan'],
                    'kode_kro' => $rkaComponent['kode_kro'],
                    'kode_ro' => $rkaComponent['kode_ro'],
                    'kode_komponen' => $rkaComponent['kode_komponen'],
                    'nilai_sumber_nominal' => null,
                    'nilai_pembanding_nominal' => $rkaCategoryAmount,
                    'selisih_nominal' => null,
                    'pesan_sistem' => "Komponen {$label} pada RKA tidak ditemukan pada RENJA.",
                    'metadata_json' => [
                        'account_prefix' => $accountPrefix,
                        'kategori' => $label,
                        'account_prefixes_in_component' => $rkaComponent['account_prefixes'],
                        'component_total_rka' => $rkaComponent['component_total'],
                    ],
                ];
                continue;
            }

            $renjaAmount = (int) $renjaComponent['total'];

            /*
             * Komponen campuran akun: RENJA hanya menyediakan total Komponen sehingga
             * tidak ada dasar untuk mengatribusikan total RENJA ke akun 51/52 tertentu.
             */
            if ($rkaComponent['is_mixed']) {
                $componentStatuses[] = 'PERLU_KONFIRMASI';
                $confirmationCount++;

                $prefixList = implode(', ', $rkaComponent['account_prefixes']);
                $details[] = sprintf(
                    '%s: Komponen RKA memuat campuran kelompok akun %s. Pagu %s pada RKA = %s, total Komponen RKA = %s, sedangkan RENJA hanya menyediakan total Komponen = %s sehingga nilai kategori ini tidak dapat dibandingkan langsung',
                    $rkaComponent['label'],
                    $prefixList,
                    $label,
                    $this->formatRupiah($rkaCategoryAmount),
                    $this->formatRupiah((int) $rkaComponent['component_total']),
                    $this->formatRupiah($renjaAmount)
                );

                $findings[] = [
                    'jenis_temuan' => $findingPrefix . '_KOMPONEN_CAMPURAN_AKUN',
                    'status_sistem' => 'PERLU_KONFIRMASI',
                    'documentID_sumber' => $renjaID,
                    'documentID_pembanding' => $rkaID,
                    'kode_kegiatan' => $rkaComponent['kode_kegiatan'],
                    'kode_kro' => $rkaComponent['kode_kro'],
                    'kode_ro' => $rkaComponent['kode_ro'],
                    'kode_komponen' => $rkaComponent['kode_komponen'],
                    'nilai_sumber_nominal' => $renjaAmount,
                    'nilai_pembanding_nominal' => $rkaCategoryAmount,
                    'selisih_nominal' => null,
                    'pesan_sistem' => "Komponen RKA memuat campuran kelompok akun sehingga pagu {$label} tidak dapat dibandingkan langsung dengan total Komponen RENJA.",
                    'metadata_json' => [
                        'account_prefix' => $accountPrefix,
                        'kategori' => $label,
                        'account_prefixes_in_component' => $rkaComponent['account_prefixes'],
                        'category_total_rka' => $rkaCategoryAmount,
                        'component_total_rka' => $rkaComponent['component_total'],
                        'component_total_renja' => $renjaAmount,
                    ],
                ];
                continue;
            }

            $pureComparedCount++;
            $matchedRenjaTotal += $renjaAmount;
            $selisih = $rkaCategoryAmount - $renjaAmount;

            if ($selisih === 0) {
                $componentStatuses[] = 'SESUAI';
                continue;
            }

            $componentStatuses[] = 'TIDAK_SESUAI';
            $mismatchCount++;

            $details[] = sprintf(
                '%s: RENJA = %s; RKA %s = %s; selisih RKA - RENJA = %s',
                $rkaComponent['label'],
                $this->formatRupiah($renjaAmount),
                $label,
                $this->formatRupiah($rkaCategoryAmount),
                $this->formatRupiah($selisih)
            );

            $findings[] = [
                'jenis_temuan' => $findingPrefix . '_PAGU_KOMPONEN_TIDAK_SESUAI',
                'status_sistem' => 'TIDAK_SESUAI',
                'documentID_sumber' => $renjaID,
                'documentID_pembanding' => $rkaID,
                'kode_kegiatan' => $rkaComponent['kode_kegiatan'],
                'kode_kro' => $rkaComponent['kode_kro'],
                'kode_ro' => $rkaComponent['kode_ro'],
                'kode_komponen' => $rkaComponent['kode_komponen'],
                'nilai_sumber_nominal' => $renjaAmount,
                'nilai_pembanding_nominal' => $rkaCategoryAmount,
                'selisih_nominal' => $selisih,
                'pesan_sistem' => "Pagu {$label} pada Komponen RKA berbeda dengan alokasi Komponen bersesuaian pada RENJA.",
                'metadata_json' => [
                    'account_prefix' => $accountPrefix,
                    'kategori' => $label,
                    'account_prefixes_in_component' => $rkaComponent['account_prefixes'],
                ],
            ];
        }

        $status = $this->aggregateStatuses($componentStatuses);

        if ($status === 'SESUAI') {
            $summary = sprintf(
                'Pagu %s pada %d Komponen RKA yang hanya memuat kelompok akun %s telah sesuai dengan alokasi Komponen bersesuaian pada RENJA',
                $label,
                $pureComparedCount,
                $accountPrefix
            );
        } elseif ($status === 'TIDAK_SESUAI' && $confirmationCount > 0) {
            $summary = "Ditemukan ketidaksesuaian Pagu {$label} antara RKA dan RENJA serta terdapat Komponen campuran akun yang masih perlu dikonfirmasi";
        } elseif ($status === 'TIDAK_SESUAI') {
            $summary = "Ditemukan ketidaksesuaian Pagu {$label} antara RKA dan alokasi Komponen bersesuaian pada RENJA";
        } else {
            $summary = "Pagu {$label} masih memerlukan konfirmasi karena terdapat Komponen RKA dengan campuran kelompok akun yang tidak dapat dibandingkan langsung dengan total Komponen RENJA";
        }

        return [
            'status' => $status,
            'explanation' => $this->formatExplanation($summary, $details),
            'metadata' => [
                'account_prefix' => $accountPrefix,
                'jumlah_komponen_rka' => $rkaComponents->count(),
                'jumlah_komponen_dibandingkan_murni' => $pureComparedCount,
                'total_rka_kategori' => $rkaCategoryTotal,
                'total_renja_komponen_terbanding' => $matchedRenjaTotal,
                'jumlah_tidak_sesuai' => $mismatchCount,
                'jumlah_perlu_konfirmasi' => $confirmationCount,
                'renja_allocation_source' => 'alokasi_komponen_0',
                'renja_multiplier' => 1000,
            ],
            'findings' => $findings,
        ];
    }

    private function aggregateB2(array $belanjaPegawai, array $belanjaBarang): array
    {
        $statuses = [
            'Belanja Pegawai' => $belanjaPegawai['status'],
            'Belanja Barang' => $belanjaBarang['status'],
        ];

        $status = $this->aggregateStatuses(array_values($statuses));

        if ($status === 'SESUAI') {
            $summary = 'Pagu Operasional telah sesuai berdasarkan hasil pemeriksaan Belanja Pegawai dan Belanja Barang';
            $details = [];
        } elseif ($status === 'TIDAK_SESUAI') {
            $summary = 'Pagu Operasional belum sesuai karena terdapat ketidaksesuaian pada pemeriksaan turunannya';
            $details = collect($statuses)
                ->filter(fn ($childStatus) => $childStatus !== 'SESUAI')
                ->map(fn ($childStatus, $label) => sprintf(
                    '%s berstatus %s; lihat rincian pada subbaris %s',
                    $label,
                    str_replace('_', ' ', $childStatus),
                    $label
                ))
                ->values()
                ->all();
        } else {
            $summary = 'Pagu Operasional masih memerlukan konfirmasi karena terdapat pemeriksaan turunan yang belum dapat dievaluasi';
            $details = collect($statuses)
                ->filter(fn ($childStatus) => $childStatus !== 'SESUAI')
                ->map(fn ($childStatus, $label) => sprintf(
                    '%s berstatus %s; lihat rincian pada subbaris %s',
                    $label,
                    str_replace('_', ' ', $childStatus),
                    $label
                ))
                ->values()
                ->all();
        }

        return [
            'status' => $status,
            'explanation' => $this->formatExplanation($summary, $details),
            'metadata' => [
                'status_belanja_pegawai' => $belanjaPegawai['status'],
                'status_belanja_barang' => $belanjaBarang['status'],
            ],
            'findings' => [],
        ];
    }

    private function evaluateB3(): array
    {
        return [
            'status' => 'SESUAI',
            'explanation' => $this->formatExplanation(
                'Pagu PN ditetapkan SESUAI sebagai default MVP karena RENJA memiliki informasi/tag Prioritas Nasional (PN), sedangkan RKA belum memiliki atribut pembanding yang ekuivalen'
            ),
            'metadata' => [
                'automatic_check' => false,
                'default_status_mvp' => 'SESUAI',
                'rka_comparator_available' => false,
            ],
            'findings' => [],
        ];
    }

    private function rkaComponentAllocationsWithComposition(
        object $penelitian,
        string $rkaID,
        string $targetAccountPrefix
    ): Collection {
        $rows = $this->rkaScopedQuery($penelitian, $rkaID)
            ->whereNotNull('kode_kegiatan')
            ->whereNotNull('kode_kro')
            ->whereNotNull('kode_ro')
            ->whereNotNull('kode_komponen')
            ->select([
                'kode_kegiatan',
                'kode_kro',
                'kode_ro',
                'kode_komponen',
                'kode_akun',
                DB::raw('SUM(COALESCE(jumlah_biaya, 0)) AS total'),
            ])
            ->groupBy(
                'kode_kegiatan',
                'kode_kro',
                'kode_ro',
                'kode_komponen',
                'kode_akun'
            )
            ->get();

        return $rows
            ->groupBy(function ($row) {
                return $this->hierarchyTuple((array) $row)['key'];
            })
            ->filter(fn ($group, $key) => $key !== '')
            ->map(function (Collection $group) use ($targetAccountPrefix) {
                $first = (array) $group->first();
                $tuple = $this->hierarchyTuple($first);

                $accountPrefixes = $group
                    ->map(function ($row) {
                        $kodeAkun = trim((string) ($row->kode_akun ?? ''));
                        return $kodeAkun !== '' ? substr($kodeAkun, 0, 2) : 'TANPA_KODE';
                    })
                    ->unique()
                    ->sort()
                    ->values()
                    ->all();

                $categoryTotal = (int) $group
                    ->filter(function ($row) use ($targetAccountPrefix) {
                        $kodeAkun = trim((string) ($row->kode_akun ?? ''));
                        return str_starts_with($kodeAkun, $targetAccountPrefix);
                    })
                    ->sum(fn ($row) => (int) ($row->total ?? 0));

                $hasTargetAccount = $group->contains(function ($row) use ($targetAccountPrefix) {
                    $kodeAkun = trim((string) ($row->kode_akun ?? ''));
                    return str_starts_with($kodeAkun, $targetAccountPrefix);
                });

                $tuple['category_total'] = $categoryTotal;
                $tuple['component_total'] = (int) $group->sum(fn ($row) => (int) ($row->total ?? 0));
                $tuple['account_prefixes'] = $accountPrefixes;
                $tuple['is_mixed'] = count($accountPrefixes) > 1;
                $tuple['has_target_account'] = $hasTargetAccount;

                return $tuple;
            })
            ->filter(fn ($row) => $row['has_target_account'])
            ->keyBy('key');
    }

    private function renjaComponentAllocations(
        object $penelitian,
        string $renjaID
    ): Collection {
        return $this->renjaScopedQuery($penelitian, $renjaID)
            ->whereNotNull('kode_kegiatan')
            ->whereNotNull('kode_kro')
            ->whereNotNull('kode_ro')
            ->whereNotNull('kode_komponen')
            ->select([
                'kode_kegiatan',
                'kode_kro',
                'kode_ro',
                'kode_komponen',
                DB::raw(
                    'SUM(COALESCE(alokasi_komponen_0, 0)) * 1000 AS total'
                ),
            ])
            ->groupBy('kode_kegiatan', 'kode_kro', 'kode_ro', 'kode_komponen')
            ->get()
            ->map(function ($row) {
                $tuple = $this->hierarchyTuple((array) $row);
                $tuple['total'] = (int) $row->total;
                return $tuple;
            })
            ->filter(fn ($row) => $row['key'] !== '')
            ->keyBy('key');
    }

    private function renjaTotalAllocation($query): int
    {
        /*
         * Business Rule B (revisi): alokasi_komponen_0 adalah alokasi TA aktif
         * pada file RENJA dan nilainya disimpan dalam satuan ribu rupiah.
         * alokasi_komponen_1 s.d. _3 tidak dijumlahkan ke TA aktif.
         */
        $row = (clone $query)
            ->selectRaw(
                'COALESCE(SUM(COALESCE(alokasi_komponen_0, 0)), 0) * 1000 AS total'
            )
            ->first();

        return (int) ($row->total ?? 0);
    }

    private function rkaScopedQuery(object $penelitian, string $rkaID)
    {
        return DB::table('rka')
            ->where('documentID', $rkaID)
            ->where('kode_satker', $penelitian->kode_satker)
            ->where('tahun_anggaran', $penelitian->tahun_anggaran);
    }

    private function formatRupiah(int $value): string
    {
        if ($value < 0) {
            return '-Rp' . number_format(abs($value), 0, ',', '.');
        }

        return 'Rp' . number_format($value, 0, ',', '.');
    }

    private function manualConfirmationResult(string $explanation): array
    {
        return [
            'status' => 'PERLU_KONFIRMASI',
            'explanation' => $this->formatExplanation($explanation),
            'metadata' => ['automatic_check' => false],
            'findings' => [],
        ];
    }

    private function confirmationWithFinding(string $explanation, array $finding): array
    {
        return [
            'status' => 'PERLU_KONFIRMASI',
            'explanation' => $this->formatExplanation($explanation),
            'metadata' => ['automatic_check' => false],
            'findings' => [$finding],
        ];
    }

    private function confirmationForScopes(
        Collection $scopes,
        string $jenisTemuan,
        string $explanation,
        ?string $documentIDSource,
        ?string $documentIDCompare,
        string $scopeType
    ): array {
        $findings = [];

        foreach ($scopes as $scope) {
            $finding = [
                'jenis_temuan' => $jenisTemuan,
                'status_sistem' => 'PERLU_KONFIRMASI',
                'documentID_sumber' => $documentIDSource,
                'documentID_pembanding' => $documentIDCompare,
                'kode_program' => $scope['kode_program'] ?? null,
                'kode_kegiatan' => $scope['kode_kegiatan'] ?? null,
                'pesan_sistem' => $explanation,
                'metadata_json' => $scope,
            ];

            $findings[] = $finding;
        }

        return [
            'status' => 'PERLU_KONFIRMASI',
            'explanation' => $this->formatExplanation($explanation),
            'metadata' => [
                'automatic_check' => false,
                'scope_type' => $scopeType,
                'jumlah_scope' => $scopes->count(),
            ],
            'findings' => $findings,
        ];
    }

    private function upsertPartAResult(
        int $penelitianID,
        string $kodeBaris,
        int $urutan,
        string $uraian,
        array $evaluation
    ): int {
        $existing = DB::table('penelitian_hasil_status')
            ->where('penelitianID', $penelitianID)
            ->where('bagian', 'A')
            ->where('kode_baris', $kodeBaris)
            ->first();

        $payload = [
            'parentHasilStatusID' => null,
            'urutan' => $urutan,
            'level_baris' => 0,
            'uraian' => $uraian,
            'status_sistem' => $evaluation['status'],
            'penjelasan_sistem' => $evaluation['explanation'],
            'metadata_json' => json_encode(
                $evaluation['metadata'] ?? [],
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            ),
            'updated_at' => now(),
        ];

        if ($existing) {
            DB::table('penelitian_hasil_status')
                ->where('hasilStatusID', $existing->hasilStatusID)
                ->update($payload);

            return (int) $existing->hasilStatusID;
        }

        return (int) DB::table('penelitian_hasil_status')->insertGetId([
            'penelitianID' => $penelitianID,
            'bagian' => 'A',
            'kode_baris' => $kodeBaris,
            ...$payload,
            'status_user' => null,
            'penjelasan_user' => null,
            'created_at' => now(),
        ]);
    }

    private function upsertPartBResult(
        int $penelitianID,
        string $kodeBaris,
        int $urutan,
        int $level,
        ?int $parentHasilStatusID,
        string $uraian,
        array $evaluation
    ): int {
        $existing = DB::table('penelitian_hasil_status')
            ->where('penelitianID', $penelitianID)
            ->where('bagian', 'B')
            ->where('kode_baris', $kodeBaris)
            ->first();

        $payload = [
            'parentHasilStatusID' => $parentHasilStatusID,
            'urutan' => $urutan,
            'level_baris' => $level,
            'uraian' => $uraian,
            'status_sistem' => $evaluation['status'],
            'penjelasan_sistem' => $evaluation['explanation'],
            'metadata_json' => json_encode(
                $evaluation['metadata'] ?? [],
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            ),
            'updated_at' => now(),
        ];

        if ($existing) {
            DB::table('penelitian_hasil_status')
                ->where('hasilStatusID', $existing->hasilStatusID)
                ->update($payload);

            return (int) $existing->hasilStatusID;
        }

        return (int) DB::table('penelitian_hasil_status')->insertGetId([
            'penelitianID' => $penelitianID,
            'bagian' => 'B',
            'kode_baris' => $kodeBaris,
            ...$payload,
            'status_user' => null,
            'penjelasan_user' => null,
            'created_at' => now(),
        ]);
    }

    private function insertFinding(
        int $penelitianID,
        ?int $hasilStatusID,
        array $finding,
        string $bagian = 'A'
    ): void {
        // hasilStatusID nullable karena temuan Bagian F (validasi pegawai
        // dan double-check RKA-RAB) merupakan structured finding mandiri,
        // bukan child dari satu baris penelitian_hasil_status tertentu.
        DB::table('penelitian_temuan')->insert([
            'penelitianID' => $penelitianID,
            'hasilStatusID' => $hasilStatusID,
            'bagian_sumber' => $bagian,
            'jenis_temuan' => (string) ($finding['jenis_temuan'] ?? ($bagian . '_TEMUAN')),
            'status_sistem' => $finding['status_sistem'] ?? null,
            'documentID_sumber' => $finding['documentID_sumber'] ?? null,
            'documentID_pembanding' => $finding['documentID_pembanding'] ?? null,
            'kode_program' => $finding['kode_program'] ?? null,
            'kode_kegiatan' => $finding['kode_kegiatan'] ?? null,
            'kode_kro' => $finding['kode_kro'] ?? null,
            'kode_ro' => $finding['kode_ro'] ?? null,
            'kode_komponen' => $finding['kode_komponen'] ?? null,
            'kode_subkomponen' => $finding['kode_subkomponen'] ?? null,
            'kode_akun' => $finding['kode_akun'] ?? null,
            'nilai_sumber_text' => $finding['nilai_sumber_text'] ?? null,
            'nilai_pembanding_text' => $finding['nilai_pembanding_text'] ?? null,
            'nilai_sumber_nominal' => $finding['nilai_sumber_nominal'] ?? null,
            'nilai_pembanding_nominal' => $finding['nilai_pembanding_nominal'] ?? null,
            'selisih_nominal' => $finding['selisih_nominal'] ?? null,
            'pesan_sistem' => (string) ($finding['pesan_sistem'] ?? ('Temuan penelitian Bagian ' . $bagian . '.')),
            'metadata_json' => isset($finding['metadata_json'])
                ? json_encode($finding['metadata_json'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
                : null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function selectedDocuments(int $penelitianID): array
    {
        $rows = DB::table('penelitian_dokumen')
            ->where('penelitianID', $penelitianID)
            ->orderBy('peran_dokumen')
            ->orderBy('urutan')
            ->get(['documentID', 'peran_dokumen']);

        $result = [
            'RENJA' => null,
            'RKBMN' => null,
            'JUMLAH_PEGAWAI' => null,
            'RKA' => null,
            'TOR' => [],
            'RAB' => [],
        ];

        foreach ($rows as $row) {
            $role = (string) $row->peran_dokumen;

            if (in_array(
                $role,
                ['RENJA', 'RKBMN', 'JUMLAH_PEGAWAI', 'RKA'],
                true
            )) {
                $result[$role] = (string) $row->documentID;
            } elseif (in_array($role, ['TOR', 'RAB'], true)) {
                $result[$role][] = (string) $row->documentID;
            }
        }

        $result['TOR'] = array_values(array_unique($result['TOR']));
        $result['RAB'] = array_values(array_unique($result['RAB']));

        return $result;
    }

    private function renjaScopedQuery(object $penelitian, string $renjaID)
    {
        return DB::table('renja')
            ->where('documentID', $renjaID)
            ->where('tahun_anggaran', $penelitian->tahun_anggaran)
            ->whereRaw('LOWER(TRIM(koordinator_kegiatan)) = LOWER(TRIM(?))', [
                $penelitian->nama_satker,
            ]);
    }

    private function selectedTorRows(
        object $penelitian,
        array $torDocumentIDs,
        array $columns
    ): Collection {
        if (empty($torDocumentIDs)) {
            return collect();
        }

        return DB::table('tor as t')
            ->join('file_master as fm', 'fm.documentID', '=', 't.documentID')
            ->whereIn('t.documentID', $torDocumentIDs)
            ->where('t.kode_satker', $penelitian->kode_satker)
            ->get($columns);
    }

    private function hierarchyTuple(array $row): array
    {
        $kegiatan = trim((string) ($row['kode_kegiatan'] ?? ''));
        $kro = trim((string) ($row['kode_kro'] ?? ''));
        $ro = trim((string) ($row['kode_ro'] ?? ''));
        $komponen = trim((string) ($row['kode_komponen'] ?? ''));

        $values = [$kegiatan, $kro, $ro, $komponen];

        if (in_array('', $values, true)) {
            return [
                'key' => '',
                'label' => '',
                'kode_kegiatan' => $kegiatan ?: null,
                'kode_kro' => $kro ?: null,
                'kode_ro' => $ro ?: null,
                'kode_komponen' => $komponen ?: null,
            ];
        }

        return [
            'key' => implode('|', $values),
            'label' => "Kegiatan {$kegiatan} → KRO {$kro} → RO {$ro} → Komponen {$komponen}",
            'kode_kegiatan' => $kegiatan,
            'kode_kro' => $kro,
            'kode_ro' => $ro,
            'kode_komponen' => $komponen,
        ];
    }

    private function canonicalProgramCode($value): string
    {
        $code = trim((string) $value);
        if ($code === '') {
            return '';
        }

        $parts = array_values(array_filter(explode('.', $code), fn ($part) => trim($part) !== ''));
        return trim((string) end($parts));
    }

    /**
     * Exact text comparison hanya menormalisasi formatting teknis:
     * trim + line break/tab/repeated whitespace menjadi satu spasi.
     * Tidak mengubah huruf, tanda baca, atau kata.
     */
    private function normalizeComparableText($value): string
    {
        $text = trim((string) ($value ?? ''));
        if ($text === '') {
            return '';
        }

        return preg_replace('/\s+/u', ' ', $text) ?? $text;
    }

    /**
     * Format umum PENJELASAN untuk seluruh research engine:
     * - kalimat ringkasan di baris pertama;
     * - jika ada temuan, satu temuan = satu bullet pada baris tersendiri;
     * - setiap kalimat/bullet diakhiri titik;
     * - disimpan sebagai plain text agar tetap mudah diedit pada textarea dan aman untuk PDF.
     */
    private function formatExplanation(string $summary, array $details = []): string
    {
        $summary = $this->ensureSentencePeriod($summary);

        $bullets = collect($details)
            ->map(fn ($detail) => trim((string) $detail))
            ->filter(fn ($detail) => $detail !== '')
            ->map(fn ($detail) => '• ' . $this->ensureSentencePeriod($detail))
            ->values()
            ->all();

        if (empty($bullets)) {
            return $summary;
        }

        return $summary . "\n\n" . implode("\n", $bullets);
    }

    private function ensureSentencePeriod(string $sentence): string
    {
        $sentence = trim($sentence);

        if ($sentence === '') {
            return '';
        }

        return rtrim($sentence, " \t\n\r\0\x0B.!?;:") . '.';
    }

    private function writeLog(
        int $penelitianID,
        string $event,
        ?string $userID,
        string $message,
        ?array $metadata = null
    ): void {
        DB::table('penelitian_log')->insert([
            'penelitianID' => $penelitianID,
            'event' => $event,
            'userID' => $userID,
            'message' => $message,
            'metadata_json' => $metadata
                ? json_encode($metadata, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
                : null,
            'created_at' => now(),
        ]);
    }
}
