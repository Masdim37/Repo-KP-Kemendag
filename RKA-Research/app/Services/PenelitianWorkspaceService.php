<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PenelitianWorkspaceService
{
    public const TEMPAT_DEFAULT = 'Ruang Rapat Biro Perencanaan';

    private const SINGLE_DOCUMENT_ROLES = [
        'RENJA',
        'RKBMN',
        'JUMLAH_PEGAWAI',
        'RKA',
    ];

    private const MULTI_DOCUMENT_ROLES = [
        'TOR',
        'RAB',
    ];

    /**
     * Master organisasi untuk cascading dropdown.
     */
    public function organizationReferences(): array
    {
        return [
            'unitEselon1' => DB::table('unit_eselon_1')
                ->select('kode_unit_eselon1', 'nama_unit_eselon1')
                ->orderBy('kode_unit_eselon1')
                ->get(),

            'unitEselon2' => DB::table('unit_eselon_2')
                ->select(
                    'kode_unit_eselon2',
                    'nama_unit_eselon2',
                    'kode_unit_eselon1'
                )
                ->orderBy('kode_unit_eselon2')
                ->get(),

            'satker' => DB::table('satker')
                ->select(
                    'kode_satker',
                    'nama_satker',
                    'kode_unit_eselon2'
                )
                ->orderBy('kode_satker')
                ->get(),
        ];
    }

    /**
     * Validasi hierarchy organisasi terhadap master database.
     */
    public function resolveOrganization(
        string $kodeUnitEselon1,
        string $kodeUnitEselon2,
        string $kodeSatker
    ): array {
        $unit1 = DB::table('unit_eselon_1')
            ->where('kode_unit_eselon1', $kodeUnitEselon1)
            ->first();

        if (!$unit1) {
            throw new \RuntimeException('Unit Eselon I yang dipilih tidak ditemukan.');
        }

        $unit2 = DB::table('unit_eselon_2')
            ->where('kode_unit_eselon2', $kodeUnitEselon2)
            ->where('kode_unit_eselon1', $kodeUnitEselon1)
            ->first();

        if (!$unit2) {
            throw new \RuntimeException(
                'Unit Eselon II tidak sesuai dengan Unit Eselon I yang dipilih.'
            );
        }

        $satker = DB::table('satker')
            ->where('kode_satker', $kodeSatker)
            ->where('kode_unit_eselon2', $kodeUnitEselon2)
            ->first();

        if (!$satker) {
            throw new \RuntimeException(
                'Satuan Kerja tidak sesuai dengan Unit Eselon II yang dipilih.'
            );
        }

        return [
            'kode_unit_eselon1' => (string) $unit1->kode_unit_eselon1,
            'nama_unit_eselon1' => (string) $unit1->nama_unit_eselon1,
            'kode_unit_eselon2' => (string) $unit2->kode_unit_eselon2,
            'nama_unit_eselon2' => (string) $unit2->nama_unit_eselon2,
            'kode_satker' => (string) $satker->kode_satker,
            'nama_satker' => (string) $satker->nama_satker,
        ];
    }

    /**
     * Kandidat dokumen yang relevan untuk Satker terpilih.
     * Setiap query tetap divalidasi ulang ketika DRAFT disimpan.
     */
    public function documentOptions(array $organization): array
    {
        return [
            'RENJA' => $this->renjaOptions($organization),
            'RKBMN' => $this->rkbmnOptions($organization),
            'JUMLAH_PEGAWAI' => $this->jumlahPegawaiOptions($organization),
            'RKA' => $this->rkaOptions($organization),
            'TOR' => $this->torOptions($organization),
            'RAB' => $this->rabOptions($organization),
        ];
    }

    public function normalizeSelection(array $data): array
    {
        $single = static fn ($value): ?string => filled($value)
            ? trim((string) $value)
            : null;

        $multi = static function ($values): array {
            if (!is_array($values)) {
                return [];
            }

            return collect($values)
                ->map(fn ($value) => trim((string) $value))
                ->filter()
                ->unique()
                ->sort()
                ->values()
                ->all();
        };

        return [
            'RENJA' => $single($data['renja_document_id'] ?? null),
            'RKBMN' => $single($data['rkbmn_document_id'] ?? null),
            'JUMLAH_PEGAWAI' => $single($data['jumlah_pegawai_document_id'] ?? null),
            'RKA' => $single($data['rka_document_id'] ?? null),
            'TOR' => $multi($data['tor_document_ids'] ?? []),
            'RAB' => $multi($data['rab_document_ids'] ?? []),
        ];
    }

    /**
     * Membuat satu workspace DRAFT.
     */
    public function createWorkspace(array $data, User $user): int
    {
        $organization = $this->resolveOrganization(
            $data['kode_unit_eselon1'],
            $data['kode_unit_eselon2'],
            $data['kode_satker']
        );

        $selection = $this->normalizeSelection($data);
        $documents = $this->validateAndResolveDocuments($organization, $selection);
        $rkaSnapshot = $this->resolveRkaSnapshot(
            (string) $selection['RKA'],
            $organization
        );

        return DB::transaction(function () use (
            $data,
            $user,
            $organization,
            $selection,
            $documents,
            $rkaSnapshot
        ) {
            $penelitianID = (int) DB::table('penelitian')->insertGetId([
                'nama_penelitian' => trim($data['nama_penelitian']),
                'status' => 'DRAFT',

                'kode_unit_eselon1' => $organization['kode_unit_eselon1'],
                'nama_unit_eselon1' => $organization['nama_unit_eselon1'],
                'kode_unit_eselon2' => $organization['kode_unit_eselon2'],
                'nama_unit_eselon2' => $organization['nama_unit_eselon2'],
                'kode_satker' => $organization['kode_satker'],
                'nama_satker' => $organization['nama_satker'],

                'tahun_anggaran' => $rkaSnapshot['tahun_anggaran'],
                'tanggal_penelitian' => now(),
                'tempat' => self::TEMPAT_DEFAULT,
                'total_anggaran' => $rkaSnapshot['total_anggaran'],

                'created_by' => $user->userID,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $this->replaceDocuments($penelitianID, $documents);
            $this->replaceProgramKegiatan(
                $penelitianID,
                (string) $selection['RKA'],
                $organization
            );
            $this->syncParties($penelitianID, $data, $user, true);

            $this->writeLog(
                $penelitianID,
                'PENELITIAN_DIBUAT',
                $user->userID,
                sprintf(
                    '%s membuat penelitian "%s" untuk Satker %s.',
                    $user->name,
                    trim($data['nama_penelitian']),
                    $organization['nama_satker']
                ),
                [
                    'status' => 'DRAFT',
                    'kode_satker' => $organization['kode_satker'],
                    'rka_document_id' => $selection['RKA'],
                ]
            );

            return $penelitianID;
        }, 3);
    }

    /**
     * Update DRAFT. Jika selection dokumen berubah setelah research engine
     * menghasilkan data, hasil sistem di-invalidasi dan wajib di-generate ulang.
     */
    public function updateWorkspace(int $penelitianID, array $data, User $user): array
    {
        $organization = $this->resolveOrganization(
            $data['kode_unit_eselon1'],
            $data['kode_unit_eselon2'],
            $data['kode_satker']
        );

        $newSelection = $this->normalizeSelection($data);
        $newDocuments = $this->validateAndResolveDocuments($organization, $newSelection);
        $rkaSnapshot = $this->resolveRkaSnapshot(
            (string) $newSelection['RKA'],
            $organization
        );

        return DB::transaction(function () use (
            $penelitianID,
            $data,
            $user,
            $organization,
            $newSelection,
            $newDocuments,
            $rkaSnapshot
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
                    'Penelitian sudah FINAL dan tidak dapat diubah.'
                );
            }

            $oldSelection = $this->currentSelection($penelitianID);
            $contextChanged = (string) $penelitian->kode_unit_eselon1 !== $organization['kode_unit_eselon1']
                || (string) $penelitian->kode_unit_eselon2 !== $organization['kode_unit_eselon2']
                || (string) $penelitian->kode_satker !== $organization['kode_satker'];

            $sourceChanged = $contextChanged
                || $this->selectionSignature($oldSelection)
                    !== $this->selectionSignature($newSelection);

            $hadGeneratedResults = $sourceChanged
                ? $this->hasGeneratedResults($penelitianID)
                : false;

            DB::table('penelitian')
                ->where('penelitianID', $penelitianID)
                ->update([
                    'nama_penelitian' => trim($data['nama_penelitian']),

                    'kode_unit_eselon1' => $organization['kode_unit_eselon1'],
                    'nama_unit_eselon1' => $organization['nama_unit_eselon1'],
                    'kode_unit_eselon2' => $organization['kode_unit_eselon2'],
                    'nama_unit_eselon2' => $organization['nama_unit_eselon2'],
                    'kode_satker' => $organization['kode_satker'],
                    'nama_satker' => $organization['nama_satker'],

                    'tahun_anggaran' => $rkaSnapshot['tahun_anggaran'],
                    'total_anggaran' => $rkaSnapshot['total_anggaran'],
                    'updated_at' => now(),
                ]);

            if ($sourceChanged) {
                if ($hadGeneratedResults) {
                    $this->invalidateGeneratedResults($penelitianID);
                }

                $this->replaceDocuments($penelitianID, $newDocuments);
                $this->replaceProgramKegiatan(
                    $penelitianID,
                    (string) $newSelection['RKA'],
                    $organization
                );

                $this->writeLog(
                    $penelitianID,
                    'SUMBER_DOKUMEN_DIUBAH',
                    $user->userID,
                    sprintf(
                        '%s mengubah dokumen sumber penelitian "%s".',
                        $user->name,
                        trim($data['nama_penelitian'])
                    ),
                    [
                        'context_changed' => $contextChanged,
                        'old_context' => [
                            'kode_unit_eselon1' => $penelitian->kode_unit_eselon1,
                            'kode_unit_eselon2' => $penelitian->kode_unit_eselon2,
                            'kode_satker' => $penelitian->kode_satker,
                        ],
                        'new_context' => [
                            'kode_unit_eselon1' => $organization['kode_unit_eselon1'],
                            'kode_unit_eselon2' => $organization['kode_unit_eselon2'],
                            'kode_satker' => $organization['kode_satker'],
                        ],
                        'old_selection' => $oldSelection,
                        'new_selection' => $newSelection,
                    ]
                );

                if ($hadGeneratedResults) {
                    $this->writeLog(
                        $penelitianID,
                        'HASIL_DIINVALIDASI',
                        $user->userID,
                        'Hasil penelitian sistem dihapus karena dokumen sumber berubah. Penelitian wajib dijalankan kembali.',
                        ['reason' => 'SOURCE_DOCUMENT_CHANGED']
                    );
                }
            }

            $this->syncParties($penelitianID, $data, $user, false);

            $this->writeLog(
                $penelitianID,
                'DRAFT_DISIMPAN',
                $user->userID,
                sprintf(
                    '%s menyimpan DRAFT penelitian "%s".',
                    $user->name,
                    trim($data['nama_penelitian'])
                ),
                [
                    'source_changed' => $sourceChanged,
                    'context_changed' => $contextChanged,
                    'results_invalidated' => $hadGeneratedResults,
                ]
            );

            return [
                'source_changed' => $sourceChanged,
                'results_invalidated' => $hadGeneratedResults,
            ];
        }, 3);
    }

    public function currentSelection(int $penelitianID): array
    {
        $rows = DB::table('penelitian_dokumen')
            ->where('penelitianID', $penelitianID)
            ->orderBy('peran_dokumen')
            ->orderBy('urutan')
            ->get();

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

            if (in_array($role, self::SINGLE_DOCUMENT_ROLES, true)) {
                $result[$role] = (string) $row->documentID;
            } elseif (in_array($role, self::MULTI_DOCUMENT_ROLES, true)) {
                $result[$role][] = (string) $row->documentID;
            }
        }

        $result['TOR'] = collect($result['TOR'])->unique()->sort()->values()->all();
        $result['RAB'] = collect($result['RAB'])->unique()->sort()->values()->all();

        return $result;
    }

    public function selectedDocumentDetails(int $penelitianID): Collection
    {
        return DB::table('penelitian_dokumen')
            ->where('penelitianID', $penelitianID)
            ->orderBy('peran_dokumen')
            ->orderBy('urutan')
            ->get();
    }

    public function parties(int $penelitianID): Collection
    {
        return DB::table('penelitian_pihak')
            ->where('penelitianID', $penelitianID)
            ->orderBy('jenis_pihak')
            ->orderBy('urutan')
            ->get();
    }

    public function hasGeneratedResults(int $penelitianID): bool
    {
        foreach ([
            'penelitian_hasil_status',
            'penelitian_hasil_nilai',
            'penelitian_hasil_d2',
            'penelitian_temuan',
        ] as $table) {
            if (DB::table($table)->where('penelitianID', $penelitianID)->exists()) {
                return true;
            }
        }

        return DB::table('penelitian_catatan')
            ->where('penelitianID', $penelitianID)
            ->whereIn('sumber_catatan', ['SYSTEM_RULE', 'SYSTEM_AI'])
            ->exists();
    }

    private function validateAndResolveDocuments(
        array $organization,
        array $selection
    ): array {
        if (blank($selection['RKA'])) {
            throw new \RuntimeException(
                'Dokumen RKA wajib dipilih karena RKA merupakan objek utama penelitian.'
            );
        }

        $resolved = [];

        foreach (self::SINGLE_DOCUMENT_ROLES as $role) {
            $documentID = $selection[$role] ?? null;

            if (!$documentID) {
                continue;
            }

            $resolved[] = $this->resolveDocument(
                $documentID,
                $role,
                $organization,
                1
            );
        }

        foreach (self::MULTI_DOCUMENT_ROLES as $role) {
            foreach (($selection[$role] ?? []) as $index => $documentID) {
                $resolved[] = $this->resolveDocument(
                    $documentID,
                    $role,
                    $organization,
                    $index + 1
                );
            }
        }

        return $resolved;
    }

    private function resolveDocument(
        string $documentID,
        string $role,
        array $organization,
        int $order
    ): array {
        $file = DB::table('file_master')
            ->where('documentID', $documentID)
            ->first();

        if (!$file) {
            throw new \RuntimeException(
                "Dokumen {$documentID} tidak ditemukan pada file_master."
            );
        }

        $expectedType = $role;
        $actualType = strtoupper(trim((string) $file->document_type));

        if ($actualType !== $expectedType) {
            throw new \RuntimeException(
                sprintf(
                    'Dokumen "%s" bertipe %s dan tidak dapat digunakan sebagai %s.',
                    $file->document_name,
                    $actualType ?: '-',
                    $role
                )
            );
        }

        if (!$this->documentMatchesOrganization($documentID, $role, $organization)) {
            throw new \RuntimeException(
                sprintf(
                    'Dokumen "%s" tidak sesuai dengan Satker %s.',
                    $file->document_name,
                    $organization['nama_satker']
                )
            );
        }

        return [
            'documentID' => (string) $file->documentID,
            'peran_dokumen' => $role,
            'urutan' => $order,
            'document_name_snapshot' => (string) $file->document_name,
            'document_type_snapshot' => (string) $file->document_type,
            'scope_json' => $this->documentScopeJson($documentID, $role),
        ];
    }

    private function documentMatchesOrganization(
        string $documentID,
        string $role,
        array $organization
    ): bool {
        return match ($role) {
            'RKA' => DB::table('rka')
                ->where('documentID', $documentID)
                ->where('kode_satker', $organization['kode_satker'])
                ->exists(),

            'TOR' => DB::table('tor')
                ->where('documentID', $documentID)
                ->where('kode_satker', $organization['kode_satker'])
                ->exists(),

            'RAB' => DB::table('rab')
                ->where('documentID', $documentID)
                ->where('kode_satker', $organization['kode_satker'])
                ->exists(),

            // RENJA, RKBMN, dan Data Jumlah Pegawai adalah master/acuan
            // yang dapat berisi banyak unit/Satker sekaligus. Workspace memilih
            // VERSI dokumennya secara eksplisit; kecocokan data Satker di dalamnya
            // baru dinilai research engine. Karena itu selection tidak dibatasi
            // hanya pada dokumen yang sudah memiliki row Satker terpilih.
            'RKBMN' => DB::table('rkbmn_pengadaan')
                ->where('documentID', $documentID)
                ->exists()
                || DB::table('rkbmn_pemeliharaan')
                    ->where('documentID', $documentID)
                    ->exists(),

            'JUMLAH_PEGAWAI' => DB::table('jumlah_pegawai_snapshot')
                ->where('documentID', $documentID)
                ->exists()
                || DB::table('jumlah_pegawai_detail')
                    ->where('documentID', $documentID)
                    ->exists(),

            'RENJA' => DB::table('renja')
                ->where('documentID', $documentID)
                ->exists(),

            default => false,
        };
    }

    private function resolveRkaSnapshot(
        string $documentID,
        array $organization
    ): array {
        $base = DB::table('rka')
            ->where('documentID', $documentID)
            ->where('kode_satker', $organization['kode_satker']);

        if (!$base->exists()) {
            throw new \RuntimeException(
                'RKA terpilih tidak memiliki data untuk Satker yang dipilih.'
            );
        }

        $years = (clone $base)
            ->whereNotNull('tahun_anggaran')
            ->distinct()
            ->orderBy('tahun_anggaran')
            ->pluck('tahun_anggaran')
            ->map(fn ($year) => (int) $year)
            ->values();

        if ($years->count() !== 1) {
            throw new \RuntimeException(
                'Satu dokumen RKA harus memiliki tepat satu Tahun Anggaran.'
            );
        }

        $total = (clone $base)->sum('jumlah_biaya');

        return [
            'tahun_anggaran' => $years->first(),
            'total_anggaran' => $total ?? 0,
        ];
    }

    private function replaceDocuments(int $penelitianID, array $documents): void
    {
        DB::table('penelitian_dokumen')
            ->where('penelitianID', $penelitianID)
            ->delete();

        if (empty($documents)) {
            return;
        }

        $now = now();

        DB::table('penelitian_dokumen')->insert(
            collect($documents)->map(function ($document) use ($penelitianID, $now) {
                return [
                    'penelitianID' => $penelitianID,
                    'documentID' => $document['documentID'],
                    'peran_dokumen' => $document['peran_dokumen'],
                    'urutan' => $document['urutan'],
                    'document_name_snapshot' => $document['document_name_snapshot'],
                    'document_type_snapshot' => $document['document_type_snapshot'],
                    'scope_json' => $document['scope_json'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            })->all()
        );
    }

    private function replaceProgramKegiatan(
        int $penelitianID,
        string $rkaDocumentID,
        array $organization
    ): void {
        DB::table('penelitian_program_kegiatan')
            ->where('penelitianID', $penelitianID)
            ->delete();

        $rows = DB::table('rka')
            ->where('documentID', $rkaDocumentID)
            ->where('kode_satker', $organization['kode_satker'])
            ->whereNotNull('kode_program')
            ->whereNotNull('nama_program')
            ->whereNotNull('kode_kegiatan')
            ->whereNotNull('nama_kegiatan')
            ->groupBy('kode_program', 'kode_kegiatan')
            ->select(
                'kode_program',
                DB::raw('MIN(nama_program) AS nama_program'),
                'kode_kegiatan',
                DB::raw('MIN(nama_kegiatan) AS nama_kegiatan')
            )
            ->orderBy('kode_program')
            ->orderBy('kode_kegiatan')
            ->get();

        if ($rows->isEmpty()) {
            return;
        }

        $now = now();
        $payload = [];

        foreach ($rows as $index => $row) {
            $payload[] = [
                'penelitianID' => $penelitianID,
                'urutan' => $index + 1,
                'kode_program' => (string) $row->kode_program,
                'nama_program' => (string) $row->nama_program,
                'kode_kegiatan' => (string) $row->kode_kegiatan,
                'nama_kegiatan' => (string) $row->nama_kegiatan,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('penelitian_program_kegiatan')->insert($payload);
    }

    private function syncParties(
        int $penelitianID,
        array $data,
        User $user,
        bool $isCreate
    ): void {
        if ($isCreate) {
            DB::table('penelitian_pihak')->insert([
                'penelitianID' => $penelitianID,
                'jenis_pihak' => 'PENELITI',
                'urutan' => 1,
                'userID' => $user->userID,
                'nama_snapshot' => $user->name,
                'is_session_user' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $peneliti1 = DB::table('penelitian_pihak')
                ->where('penelitianID', $penelitianID)
                ->where('jenis_pihak', 'PENELITI')
                ->where('urutan', 1)
                ->first();

            if (!$peneliti1) {
                $creator = DB::table('penelitian as p')
                    ->join('users as u', 'u.userID', '=', 'p.created_by')
                    ->where('p.penelitianID', $penelitianID)
                    ->select('u.userID', 'u.name')
                    ->first();

                if ($creator) {
                    DB::table('penelitian_pihak')->insert([
                        'penelitianID' => $penelitianID,
                        'jenis_pihak' => 'PENELITI',
                        'urutan' => 1,
                        'userID' => $creator->userID,
                        'nama_snapshot' => $creator->name,
                        'is_session_user' => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        DB::table('penelitian_pihak')
            ->where('penelitianID', $penelitianID)
            ->where(function ($query) {
                $query->where(function ($peneliti) {
                    $peneliti->where('jenis_pihak', 'PENELITI')
                        ->whereIn('urutan', [2, 3]);
                })->orWhere('jenis_pihak', 'PERWAKILAN');
            })
            ->delete();

        $manualParties = [
            ['PENELITI', 2, $data['peneliti_2'] ?? null],
            ['PENELITI', 3, $data['peneliti_3'] ?? null],
            ['PERWAKILAN', 1, $data['perwakilan_1'] ?? null],
            ['PERWAKILAN', 2, $data['perwakilan_2'] ?? null],
            ['PERWAKILAN', 3, $data['perwakilan_3'] ?? null],
        ];

        $now = now();
        $payload = [];

        foreach ($manualParties as [$type, $order, $name]) {
            $name = trim((string) $name);

            if ($name === '') {
                continue;
            }

            $payload[] = [
                'penelitianID' => $penelitianID,
                'jenis_pihak' => $type,
                'urutan' => $order,
                'userID' => null,
                'nama_snapshot' => $name,
                'is_session_user' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if (!empty($payload)) {
            DB::table('penelitian_pihak')->insert($payload);
        }
    }

    private function invalidateGeneratedResults(int $penelitianID): void
    {
        // Catatan manual user dipertahankan. Hanya catatan hasil sistem/AI
        // yang dihapus sesuai rule invalidasi sumber dokumen.
        DB::table('penelitian_catatan')
            ->where('penelitianID', $penelitianID)
            ->whereIn('sumber_catatan', ['SYSTEM_RULE', 'SYSTEM_AI'])
            ->delete();

        DB::table('penelitian_temuan')
            ->where('penelitianID', $penelitianID)
            ->delete();

        DB::table('penelitian_hasil_status')
            ->where('penelitianID', $penelitianID)
            ->delete();

        DB::table('penelitian_hasil_nilai')
            ->where('penelitianID', $penelitianID)
            ->delete();

        DB::table('penelitian_hasil_d2')
            ->where('penelitianID', $penelitianID)
            ->delete();
    }

    private function documentScopeJson(string $documentID, string $role): ?string
    {
        if (!in_array($role, ['TOR', 'RAB'], true)) {
            return null;
        }

        $table = strtolower($role);

        $scope = DB::table($table)
            ->where('documentID', $documentID)
            ->select(
                'kode_program',
                'nama_program',
                'kode_kegiatan',
                'nama_kegiatan',
                'kode_kro',
                'nama_kro',
                'kode_ro',
                'nama_ro'
            )
            ->distinct()
            ->orderBy('kode_program')
            ->orderBy('kode_kegiatan')
            ->orderBy('kode_kro')
            ->orderBy('kode_ro')
            ->get()
            ->map(fn ($row) => (array) $row)
            ->values();

        return $scope->isEmpty()
            ? null
            : json_encode($scope, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    private function selectionSignature(array $selection): string
    {
        $normalized = [
            'RENJA' => $selection['RENJA'] ?? null,
            'RKBMN' => $selection['RKBMN'] ?? null,
            'JUMLAH_PEGAWAI' => $selection['JUMLAH_PEGAWAI'] ?? null,
            'RKA' => $selection['RKA'] ?? null,
            'TOR' => collect($selection['TOR'] ?? [])->sort()->values()->all(),
            'RAB' => collect($selection['RAB'] ?? [])->sort()->values()->all(),
        ];

        return hash('sha256', json_encode($normalized));
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

    private function renjaOptions(array $organization): Collection
    {
        return DB::table('file_master as fm')
            ->join('renja as r', 'r.documentID', '=', 'fm.documentID')
            ->where('fm.document_type', 'RENJA')
            ->groupBy('fm.documentID', 'fm.document_name', 'fm.created_at')
            ->select(
                'fm.documentID',
                'fm.document_name',
                'fm.created_at',
                DB::raw('MIN(r.tahun_anggaran) AS tahun_anggaran'),
                DB::raw('COUNT(DISTINCT r.tahun_anggaran) AS jumlah_tahun')
            )
            ->orderByDesc('fm.created_at')
            ->get()
            ->map(fn ($row) => $this->optionRow($row, [
                'tahun_anggaran' => $row->tahun_anggaran,
                'valid_single_year' => (int) $row->jumlah_tahun === 1,
            ]));
    }

    private function rkbmnOptions(array $organization): Collection
    {
        $documentIDs = DB::table('rkbmn_pengadaan')
            ->distinct()
            ->pluck('documentID')
            ->merge(
                DB::table('rkbmn_pemeliharaan')
                    ->distinct()
                    ->pluck('documentID')
            )
            ->unique()
            ->values();

        if ($documentIDs->isEmpty()) {
            return collect();
        }

        return DB::table('file_master as fm')
            ->where('fm.document_type', 'RKBMN')
            ->whereIn('fm.documentID', $documentIDs)
            ->orderByDesc('fm.created_at')
            ->get(['fm.documentID', 'fm.document_name', 'fm.created_at'])
            ->map(function ($row) {
                $year = DB::table('rkbmn_pemeliharaan')
                    ->where('documentID', $row->documentID)
                    ->value('tahun_anggaran')
                    ?? DB::table('rkbmn_pengadaan')
                        ->where('documentID', $row->documentID)
                        ->value('tahun_anggaran');

                return $this->optionRow($row, ['tahun_anggaran' => $year]);
            });
    }

    private function jumlahPegawaiOptions(array $organization): Collection
    {
        $documentIDs = DB::table('jumlah_pegawai_snapshot')
            ->distinct()
            ->pluck('documentID')
            ->merge(
                DB::table('jumlah_pegawai_detail')
                    ->distinct()
                    ->pluck('documentID')
            )
            ->unique()
            ->values();

        if ($documentIDs->isEmpty()) {
            return collect();
        }

        return DB::table('file_master as fm')
            ->leftJoin('jumlah_pegawai_snapshot as jps', 'jps.documentID', '=', 'fm.documentID')
            ->where('fm.document_type', 'JUMLAH_PEGAWAI')
            ->whereIn('fm.documentID', $documentIDs)
            ->orderByDesc('fm.created_at')
            ->get([
                'fm.documentID',
                'fm.document_name',
                'fm.created_at',
                'jps.tanggal_data',
            ])
            ->map(fn ($row) => $this->optionRow($row, [
                'tanggal_data' => $row->tanggal_data,
            ]));
    }

    private function rkaOptions(array $organization): Collection
    {
        return DB::table('file_master as fm')
            ->join('rka as r', 'r.documentID', '=', 'fm.documentID')
            ->where('fm.document_type', 'RKA')
            ->where('r.kode_satker', $organization['kode_satker'])
            ->groupBy('fm.documentID', 'fm.document_name', 'fm.created_at')
            ->select(
                'fm.documentID',
                'fm.document_name',
                'fm.created_at',
                DB::raw('MIN(r.tahun_anggaran) AS tahun_anggaran'),
                DB::raw('COUNT(DISTINCT r.tahun_anggaran) AS jumlah_tahun')
            )
            ->orderByDesc('fm.created_at')
            ->get()
            ->map(fn ($row) => $this->optionRow($row, [
                'tahun_anggaran' => $row->tahun_anggaran,
                'valid_single_year' => (int) $row->jumlah_tahun === 1,
            ]));
    }

    private function torOptions(array $organization): Collection
    {
        return DB::table('file_master as fm')
            ->join('tor as t', 't.documentID', '=', 'fm.documentID')
            ->where('fm.document_type', 'TOR')
            ->where('t.kode_satker', $organization['kode_satker'])
            ->groupBy('fm.documentID', 'fm.document_name', 'fm.created_at')
            ->select(
                'fm.documentID',
                'fm.document_name',
                'fm.created_at',
                DB::raw("GROUP_CONCAT(DISTINCT CONCAT_WS('.', t.kode_kegiatan, t.kode_kro, t.kode_ro) ORDER BY t.kode_kegiatan, t.kode_kro, t.kode_ro SEPARATOR ', ') AS scope")
            )
            ->orderByDesc('fm.created_at')
            ->get()
            ->map(fn ($row) => $this->optionRow($row, [
                'scope' => $row->scope,
            ]));
    }

    private function rabOptions(array $organization): Collection
    {
        return DB::table('file_master as fm')
            ->join('rab as r', 'r.documentID', '=', 'fm.documentID')
            ->where('fm.document_type', 'RAB')
            ->where('r.kode_satker', $organization['kode_satker'])
            ->groupBy('fm.documentID', 'fm.document_name', 'fm.created_at')
            ->select(
                'fm.documentID',
                'fm.document_name',
                'fm.created_at',
                DB::raw('MIN(r.tahun_anggaran) AS tahun_anggaran'),
                DB::raw("GROUP_CONCAT(DISTINCT CONCAT_WS('.', r.kode_kegiatan, r.kode_kro, r.kode_ro) ORDER BY r.kode_kegiatan, r.kode_kro, r.kode_ro SEPARATOR ', ') AS scope")
            )
            ->orderByDesc('fm.created_at')
            ->get()
            ->map(fn ($row) => $this->optionRow($row, [
                'tahun_anggaran' => $row->tahun_anggaran,
                'scope' => $row->scope,
            ]));
    }

    private function optionRow(object $row, array $meta = []): array
    {
        return [
            'documentID' => (string) $row->documentID,
            'document_name' => (string) $row->document_name,
            'created_at' => $row->created_at,
            'meta' => $meta,
        ];
    }
}
