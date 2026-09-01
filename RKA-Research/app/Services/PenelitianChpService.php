<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PenelitianChpService
{
    private const REQUIRED_STATUS_ROWS = [
        'A' => ['A1', 'A2', 'A3', 'A4', 'A5'],
        'B' => ['B1', 'B2', 'B2.1', 'B2.2', 'B3'],
        'E' => ['E1', 'E2', 'E3', 'E4', 'E5'],
    ];

    private const REQUIRED_VALUE_ROWS = [
        'C' => ['C1', 'C2', 'C3', 'C4', 'C5'],
        'D' => ['D1', 'D2', 'D3', 'D4', 'D5', 'D6', 'D7'],
        'D1' => [
            'D1.TOTAL',
            'D1.A',
            'D1.B',
            'D1.C',
            'D1.D',
            'D1.E',
            'D1.F',
            'D1.G',
        ],
    ];

    private const REQUIRED_D2_ROWS = [
        'D2.PPD',
        'D2.PPD.PC',
        'D2.PPD.PORTABLE',
        'D2.PPD.PRINTER_SCANNER',
        'D2.PPD.AC_SPLIT',
        'D2.ATB',
        'D2.ATB.OFFICE_APP',
        'D2.ATB.SYSTEM',
        'D2.ATB.LICENSE',
    ];

    public function readiness(int $penelitianID): array
    {
        $penelitian = DB::table('penelitian')
            ->where('penelitianID', $penelitianID)
            ->first();

        if (!$penelitian) {
            throw new \RuntimeException('Penelitian tidak ditemukan.');
        }

        $checks = [];

        foreach (self::REQUIRED_STATUS_ROWS as $bagian => $requiredCodes) {
            $actual = DB::table('penelitian_hasil_status')
                ->where('penelitianID', $penelitianID)
                ->where('bagian', $bagian)
                ->whereIn('kode_baris', $requiredCodes)
                ->pluck('kode_baris')
                ->all();

            $missing = array_values(array_diff($requiredCodes, $actual));

            $checks[] = [
                'key' => $bagian,
                'label' => "Bagian {$bagian}",
                'ready' => empty($missing),
                'detail' => empty($missing)
                    ? 'Sudah tersedia.'
                    : 'Belum lengkap: ' . implode(', ', $missing) . '.',
            ];
        }

        foreach (self::REQUIRED_VALUE_ROWS as $bagian => $requiredCodes) {
            $actual = DB::table('penelitian_hasil_nilai')
                ->where('penelitianID', $penelitianID)
                ->where('bagian', $bagian)
                ->whereIn('kode_baris', $requiredCodes)
                ->pluck('kode_baris')
                ->all();

            $missing = array_values(array_diff($requiredCodes, $actual));

            $checks[] = [
                'key' => $bagian,
                'label' => $bagian === 'D1' ? 'Bagian D.1' : "Bagian {$bagian}",
                'ready' => empty($missing),
                'detail' => empty($missing)
                    ? 'Sudah tersedia.'
                    : 'Belum lengkap: ' . implode(', ', $missing) . '.',
            ];
        }

        $actualD2 = DB::table('penelitian_hasil_d2')
            ->where('penelitianID', $penelitianID)
            ->whereIn('kode_baris', self::REQUIRED_D2_ROWS)
            ->pluck('kode_baris')
            ->all();

        $missingD2 = array_values(array_diff(
            self::REQUIRED_D2_ROWS,
            $actualD2
        ));

        $checks[] = [
            'key' => 'D2',
            'label' => 'Bagian D.2',
            'ready' => empty($missingD2),
            'detail' => empty($missingD2)
                ? 'Sudah tersedia.'
                : 'Belum lengkap: ' . implode(', ', $missingD2) . '.',
        ];

        $hasF = DB::table('penelitian_catatan')
            ->where('penelitianID', $penelitianID)
            ->whereIn('sumber_catatan', ['SYSTEM_RULE', 'SYSTEM_AI'])
            ->exists();

        $checks[] = [
            'key' => 'F',
            'label' => 'Bagian F',
            'ready' => $hasF,
            'detail' => $hasF
                ? 'Sudah tersedia.'
                : 'Bagian F belum dijalankan.',
        ];

        $hasPeneliti1 = DB::table('penelitian_pihak')
            ->where('penelitianID', $penelitianID)
            ->where('jenis_pihak', 'PENELITI')
            ->where('urutan', 1)
            ->exists();

        $checks[] = [
            'key' => 'PIHAK',
            'label' => 'Peneliti RKA-K/L',
            'ready' => $hasPeneliti1,
            'detail' => $hasPeneliti1
                ? 'Peneliti 1 tersedia dari user pembuat penelitian.'
                : 'Peneliti 1 belum tersedia.',
        ];

        return [
            'status' => (string) $penelitian->status,
            'all_ready' => collect($checks)
                ->every(fn (array $check) => $check['ready']),
            'checks' => $checks,
        ];
    }

    public function finalize(
        int $penelitianID,
        User $user
    ): array {
        return DB::transaction(function () use (
            $penelitianID,
            $user
        ) {
            $penelitian = DB::table('penelitian')
                ->where('penelitianID', $penelitianID)
                ->lockForUpdate()
                ->first();

            if (!$penelitian) {
                throw new \RuntimeException('Penelitian tidak ditemukan.');
            }

            if ($penelitian->status === 'FINAL') {
                throw new \RuntimeException(
                    'CHP sudah FINAL dan tidak dapat difinalisasi ulang.'
                );
            }

            if ($penelitian->status !== 'DRAFT') {
                throw new \RuntimeException(
                    'Status penelitian tidak valid untuk finalisasi.'
                );
            }

            $readiness = $this->readiness($penelitianID);

            if (!$readiness['all_ready']) {
                $missing = collect($readiness['checks'])
                    ->filter(fn (array $check) => !$check['ready'])
                    ->pluck('label')
                    ->values()
                    ->all();

                throw new \RuntimeException(
                    'CHP belum dapat difinalisasi. Lengkapi/jalankan terlebih dahulu: '
                    . implode(', ', $missing)
                    . '.'
                );
            }

            $now = now();

            DB::table('penelitian')
                ->where('penelitianID', $penelitianID)
                ->update([
                    'status' => 'FINAL',
                    'finalized_by' => $user->userID,
                    'finalized_at' => $now,
                    'updated_at' => $now,
                ]);

            $this->writeLog(
                $penelitianID,
                'FINALISASI',
                $user->userID,
                sprintf(
                    '%s memfinalisasi CHP "%s". Setelah finalisasi seluruh data penelitian menjadi read-only.',
                    $user->name,
                    $penelitian->nama_penelitian
                ),
                [
                    'status_from' => 'DRAFT',
                    'status_to' => 'FINAL',
                    'finalized_at' => $now->toDateTimeString(),
                    'perlu_konfirmasi_allowed' => true,
                ]
            );

            return [
                'penelitianID' => $penelitianID,
                'status' => 'FINAL',
                'finalized_at' => $now,
            ];
        }, 3);
    }

    public function snapshot(int $penelitianID): array
    {
        $penelitian = DB::table('penelitian as p')
            ->leftJoin('users as creator', 'creator.userID', '=', 'p.created_by')
            ->leftJoin('users as finalizer', 'finalizer.userID', '=', 'p.finalized_by')
            ->where('p.penelitianID', $penelitianID)
            ->select([
                'p.*',
                'creator.name as creator_name',
                'finalizer.name as finalizer_name',
            ])
            ->first();

        if (!$penelitian) {
            throw new \RuntimeException('Penelitian tidak ditemukan.');
        }

        $programKegiatan = DB::table('penelitian_program_kegiatan')
            ->where('penelitianID', $penelitianID)
            ->orderBy('urutan')
            ->get();

        $statusRows = DB::table('penelitian_hasil_status')
            ->where('penelitianID', $penelitianID)
            ->orderBy('bagian')
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
            })
            ->groupBy('bagian');

        $nilaiRows = DB::table('penelitian_hasil_nilai')
            ->where('penelitianID', $penelitianID)
            ->orderBy('bagian')
            ->orderBy('urutan')
            ->get()
            ->map(function ($row) {
                $row->pagu_renja_efektif =
                    $row->pagu_renja_user !== null
                        ? (float) $row->pagu_renja_user
                        : (float) $row->pagu_renja_sistem;

                $row->pagu_rka_efektif =
                    $row->pagu_rka_user !== null
                        ? (float) $row->pagu_rka_user
                        : (float) $row->pagu_rka_sistem;

                $row->selisih_efektif =
                    $row->pagu_rka_efektif
                    - $row->pagu_renja_efektif;

                $row->penjelasan_efektif =
                    $row->penjelasan_user !== null
                        ? $row->penjelasan_user
                        : $row->penjelasan_sistem;

                return $row;
            })
            ->groupBy('bagian');

        $d2Rows = DB::table('penelitian_hasil_d2')
            ->where('penelitianID', $penelitianID)
            ->orderBy('urutan')
            ->get()
            ->map(function ($row) {
                foreach ([
                    'rkbmn_pemeliharaan_unit',
                    'alokasi_pemeliharaan_vol',
                    'alokasi_pemeliharaan_pagu',
                    'alokasi_pengadaan_vol',
                    'alokasi_pengadaan_pagu',
                ] as $field) {
                    $userField = $field . '_user';
                    $row->{$field . '_efektif'} = $row->{$userField} !== null
                        ? (float) $row->{$userField}
                        : (float) $row->{$field};
                }

                $row->penjelasan_efektif =
                    $row->penjelasan_user !== null
                        ? $row->penjelasan_user
                        : $row->penjelasan_sistem;

                return $row;
            });

        $fRows = DB::table('penelitian_catatan')
            ->where('penelitianID', $penelitianID)
            ->orderBy('urutan')
            ->orderBy('catatanID')
            ->get()
            ->filter(function ($row) {
                if (
                    in_array(
                        $row->sumber_catatan,
                        ['SYSTEM_RULE', 'SYSTEM_AI'],
                        true
                    )
                    && (int) $row->dihapus_user === 1
                ) {
                    return false;
                }

                return true;
            })
            ->map(function ($row) {
                $row->catatan_efektif =
                    $row->catatan_user !== null
                        ? $row->catatan_user
                        : $row->catatan_sistem;

                return $row;
            })
            ->filter(
                fn ($row) =>
                    trim((string) ($row->catatan_efektif ?? '')) !== ''
            )
            ->values();

        $parties = DB::table('penelitian_pihak')
            ->where('penelitianID', $penelitianID)
            ->orderBy('jenis_pihak')
            ->orderBy('urutan')
            ->get();

        return [
            'penelitian' => $penelitian,
            'programKegiatan' => $programKegiatan,
            'A' => $statusRows->get('A', collect()),
            'B' => $statusRows->get('B', collect()),
            'C' => $nilaiRows->get('C', collect()),
            'D' => $nilaiRows->get('D', collect()),
            'D1' => $nilaiRows->get('D1', collect()),
            'D2' => $d2Rows,
            'E' => $statusRows->get('E', collect()),
            'F' => $fRows,
            'peneliti' => $this->partySlots($parties, 'PENELITI'),
            'perwakilan' => $this->partySlots($parties, 'PERWAKILAN'),
        ];
    }

    public function recordPrint(
        int $penelitianID,
        User $user
    ): void {
        $penelitian = DB::table('penelitian')
            ->where('penelitianID', $penelitianID)
            ->first();

        if (!$penelitian) {
            throw new \RuntimeException('Penelitian tidak ditemukan.');
        }

        if ($penelitian->status !== 'FINAL') {
            throw new \RuntimeException(
                'CHP hanya dapat dicetak setelah status FINAL.'
            );
        }

        $this->writeLog(
            $penelitianID,
            'PDF_DICETAK',
            $user->userID,
            sprintf(
                '%s membuka proses cetak/Save as PDF untuk CHP "%s".',
                $user->name,
                $penelitian->nama_penelitian
            ),
            [
                'mode' => 'BROWSER_PRINT_SAVE_AS_PDF',
            ]
        );
    }

    private function partySlots(
        Collection $parties,
        string $type
    ): array {
        $slots = [
            1 => null,
            2 => null,
            3 => null,
        ];

        foreach (
            $parties->where('jenis_pihak', $type)
            as $party
        ) {
            $order = (int) $party->urutan;

            if ($order >= 1 && $order <= 3) {
                $slots[$order] = trim(
                    (string) $party->nama_snapshot
                );
            }
        }

        return $slots;
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
                ? json_encode(
                    $metadata,
                    JSON_UNESCAPED_UNICODE
                    | JSON_UNESCAPED_SLASHES
                )
                : null,
            'created_at' => now(),
        ]);
    }
}
