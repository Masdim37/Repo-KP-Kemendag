<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class lihatReferensiPenganggaranController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'jenis' => [
                'nullable',
                Rule::in([
                    'program',
                    'kegiatan',
                    'kro',
                    'ro',
                    'komponen',
                    'subkomponen',
                    'akun',
                ]),
            ],
            'q' => 'nullable|string|max:100',
            'kode_program' => 'nullable|string|max:50',
            'kode_kegiatan' => 'nullable|string|max:50',
            'kode_kro' => 'nullable|string|max:50',
            'kode_ro' => 'nullable|string|max:50',
            'kode_komponen' => 'nullable|string|max:50',
            'kode_satker' => 'nullable|string|max:50',
            'jenis_komponen' => [
                'nullable',
                Rule::in(['U', 'P']),
            ],
            'per_page' => [
                'nullable',
                'integer',
                Rule::in([10, 20, 50, 100]),
            ],
        ]);

        $jenis = $validated['jenis'] ?? 'program';
        $search = $this->normalizeSearch($validated['q'] ?? null);
        $kodeProgram = trim((string) ($validated['kode_program'] ?? ''));
        $kodeKegiatan = trim((string) ($validated['kode_kegiatan'] ?? ''));
        $kodeKro = trim((string) ($validated['kode_kro'] ?? ''));
        $kodeRo = trim((string) ($validated['kode_ro'] ?? ''));
        $kodeKomponen = trim((string) ($validated['kode_komponen'] ?? ''));
        $kodeSatker = trim((string) ($validated['kode_satker'] ?? ''));
        $jenisKomponen = trim((string) ($validated['jenis_komponen'] ?? ''));
        $perPage = (int) ($validated['per_page'] ?? 20);

        $stats = [
            'program' => DB::table('program')->count(),
            'kegiatan' => DB::table('kegiatan')->count(),
            'kro' => DB::table('kro')->count(),
            'ro' => DB::table('ro')->count(),
            'komponen' => DB::table('komponen')->count(),
            'subkomponen' => DB::table('subkomponen')->count(),
            'akun' => DB::table('akun')->count(),
        ];

        /*
         * Data opsi dikirim sekaligus agar filter cascading pada Blade
         * tetap responsif tanpa membuat endpoint AJAX tambahan.
         */
        $programOptions = DB::table('program')
            ->select('kode_program', 'nama_program')
            ->orderBy('kode_program')
            ->get();

        $kegiatanOptions = DB::table('kegiatan')
            ->select(
                'kode_kegiatan',
                'nama_kegiatan',
                'kode_program'
            )
            ->orderBy('kode_program')
            ->orderBy('kode_kegiatan')
            ->get();

        $kroOptions = DB::table('kegiatan_kro as kk')
            ->join('kro as k', 'k.kode_kro', '=', 'kk.kode_kro')
            ->select(
                'kk.kode_kegiatan',
                'k.kode_kro',
                'k.nama_kro'
            )
            ->orderBy('kk.kode_kegiatan')
            ->orderBy('k.kode_kro')
            ->get();

        $roOptions = DB::table('ro')
            ->select(
                'kode_kegiatan',
                'kode_kro',
                'kode_ro',
                'nama_ro'
            )
            ->orderBy('kode_kegiatan')
            ->orderBy('kode_kro')
            ->orderBy('kode_ro')
            ->get();

        $komponenOptions = DB::table('komponen')
            ->select(
                'kode_kegiatan',
                'kode_kro',
                'kode_ro',
                'kode_komponen',
                'nama_komponen',
                'jenis_komponen'
            )
            ->orderBy('kode_kegiatan')
            ->orderBy('kode_kro')
            ->orderBy('kode_ro')
            ->orderBy('kode_komponen')
            ->get();

        $satkerOptions = DB::table('satker')
            ->select('kode_satker', 'nama_satker')
            ->orderBy('kode_satker')
            ->get();

        $satkerKegiatanOptions = DB::table('satker_kegiatan')
            ->select('kode_satker', 'kode_kegiatan')
            ->get();

        $records = match ($jenis) {
            'kegiatan' => $this->queryKegiatan(
                $search,
                $kodeProgram,
                $perPage
            ),

            'kro' => $this->queryKro(
                $search,
                $kodeProgram,
                $kodeKegiatan,
                $perPage
            ),

            'ro' => $this->queryRo(
                $search,
                $kodeProgram,
                $kodeKegiatan,
                $kodeKro,
                $perPage
            ),

            'komponen' => $this->queryKomponen(
                $search,
                $kodeProgram,
                $kodeKegiatan,
                $kodeKro,
                $kodeRo,
                $jenisKomponen,
                $perPage
            ),

            'subkomponen' => $this->querySubkomponen(
                $search,
                $kodeSatker,
                $kodeProgram,
                $kodeKegiatan,
                $kodeKro,
                $kodeRo,
                $kodeKomponen,
                $perPage
            ),

            'akun' => $this->queryAkun(
                $search,
                $perPage
            ),

            default => $this->queryProgram(
                $search,
                $perPage
            ),
        };

        return view(
            'menu.referensi.lihat.penganggaran',
            [
                'jenis' => $jenis,
                'records' => $records,
                'stats' => $stats,

                'programOptions' => $programOptions,
                'kegiatanOptions' => $kegiatanOptions,
                'kroOptions' => $kroOptions,
                'roOptions' => $roOptions,
                'komponenOptions' => $komponenOptions,
                'satkerOptions' => $satkerOptions,
                'satkerKegiatanOptions' => $satkerKegiatanOptions,

                'filters' => [
                    'q' => $search,
                    'kode_program' => $kodeProgram,
                    'kode_kegiatan' => $kodeKegiatan,
                    'kode_kro' => $kodeKro,
                    'kode_ro' => $kodeRo,
                    'kode_komponen' => $kodeKomponen,
                    'kode_satker' => $kodeSatker,
                    'jenis_komponen' => $jenisKomponen,
                    'per_page' => $perPage,
                ],
            ]
        );
    }

    private function queryProgram(
        string $search,
        int $perPage
    ) {
        return DB::table('program as p')
            ->leftJoin(
                'kegiatan as kg',
                'kg.kode_program',
                '=',
                'p.kode_program'
            )
            ->leftJoin(
                'satker_kegiatan as sk',
                'sk.kode_kegiatan',
                '=',
                'kg.kode_kegiatan'
            )
            ->select(
                'p.kode_program',
                'p.nama_program'
            )
            ->selectRaw(
                'COUNT(DISTINCT kg.kode_kegiatan) AS jumlah_kegiatan'
            )
            ->selectRaw(
                'COUNT(DISTINCT sk.kode_satker) AS jumlah_satker'
            )
            ->when(
                $search !== '',
                function ($query) use ($search) {
                    $like = '%' . $search . '%';

                    $query->where(function ($subQuery) use ($like) {
                        $subQuery
                            ->where('p.kode_program', 'like', $like)
                            ->orWhere('p.nama_program', 'like', $like);
                    });
                }
            )
            ->groupBy(
                'p.kode_program',
                'p.nama_program'
            )
            ->orderBy('p.kode_program')
            ->paginate($perPage)
            ->withQueryString();
    }

    private function queryKegiatan(
        string $search,
        string $kodeProgram,
        int $perPage
    ) {
        return DB::table('kegiatan as kg')
            ->join(
                'program as p',
                'p.kode_program',
                '=',
                'kg.kode_program'
            )
            ->leftJoin(
                'satker_kegiatan as sk',
                'sk.kode_kegiatan',
                '=',
                'kg.kode_kegiatan'
            )
            ->leftJoin(
                'kegiatan_kro as kk',
                'kk.kode_kegiatan',
                '=',
                'kg.kode_kegiatan'
            )
            ->select(
                'kg.kode_kegiatan',
                'kg.nama_kegiatan',
                'p.kode_program',
                'p.nama_program'
            )
            ->selectRaw(
                'COUNT(DISTINCT sk.kode_satker) AS jumlah_satker'
            )
            ->selectRaw(
                'COUNT(DISTINCT kk.kode_kro) AS jumlah_kro'
            )
            ->when(
                $kodeProgram !== '',
                fn ($query) => $query->where(
                    'kg.kode_program',
                    $kodeProgram
                )
            )
            ->when(
                $search !== '',
                function ($query) use ($search) {
                    $like = '%' . $search . '%';

                    $query->where(function ($subQuery) use ($like) {
                        $subQuery
                            ->where('kg.kode_kegiatan', 'like', $like)
                            ->orWhere('kg.nama_kegiatan', 'like', $like)
                            ->orWhere('p.kode_program', 'like', $like)
                            ->orWhere('p.nama_program', 'like', $like);
                    });
                }
            )
            ->groupBy(
                'kg.kode_kegiatan',
                'kg.nama_kegiatan',
                'p.kode_program',
                'p.nama_program'
            )
            ->orderBy('p.kode_program')
            ->orderBy('kg.kode_kegiatan')
            ->paginate($perPage)
            ->withQueryString();
    }

    private function queryKro(
        string $search,
        string $kodeProgram,
        string $kodeKegiatan,
        int $perPage
    ) {
        return DB::table('kro as k')
            ->leftJoin(
                'kegiatan_kro as kk',
                'kk.kode_kro',
                '=',
                'k.kode_kro'
            )
            ->leftJoin(
                'kegiatan as kg',
                'kg.kode_kegiatan',
                '=',
                'kk.kode_kegiatan'
            )
            ->leftJoin(
                'program as p',
                'p.kode_program',
                '=',
                'kg.kode_program'
            )
            ->leftJoin('ro as r', function ($join) {
                $join
                    ->on(
                        'r.kode_kegiatan',
                        '=',
                        'kk.kode_kegiatan'
                    )
                    ->on(
                        'r.kode_kro',
                        '=',
                        'kk.kode_kro'
                    );
            })
            ->select(
                'k.kode_kro',
                'k.nama_kro'
            )
            ->selectRaw(
                'COUNT(DISTINCT kk.kode_kegiatan) AS jumlah_kegiatan'
            )
            ->selectRaw(
                "COUNT(DISTINCT CONCAT_WS('|', r.kode_kegiatan, r.kode_kro, r.kode_ro)) AS jumlah_ro"
            )
            ->when(
                $kodeProgram !== '',
                fn ($query) => $query->where(
                    'kg.kode_program',
                    $kodeProgram
                )
            )
            ->when(
                $kodeKegiatan !== '',
                fn ($query) => $query->where(
                    'kk.kode_kegiatan',
                    $kodeKegiatan
                )
            )
            ->when(
                $search !== '',
                function ($query) use ($search) {
                    $like = '%' . $search . '%';

                    $query->where(function ($subQuery) use ($like) {
                        $subQuery
                            ->where('k.kode_kro', 'like', $like)
                            ->orWhere('k.nama_kro', 'like', $like)
                            ->orWhere('kg.kode_kegiatan', 'like', $like)
                            ->orWhere('kg.nama_kegiatan', 'like', $like)
                            ->orWhere('p.nama_program', 'like', $like);
                    });
                }
            )
            ->groupBy(
                'k.kode_kro',
                'k.nama_kro'
            )
            ->orderBy('k.kode_kro')
            ->paginate($perPage)
            ->withQueryString();
    }

    private function queryRo(
        string $search,
        string $kodeProgram,
        string $kodeKegiatan,
        string $kodeKro,
        int $perPage
    ) {
        return DB::table('ro as r')
            ->join(
                'kegiatan as kg',
                'kg.kode_kegiatan',
                '=',
                'r.kode_kegiatan'
            )
            ->join(
                'program as p',
                'p.kode_program',
                '=',
                'kg.kode_program'
            )
            ->join(
                'kro as k',
                'k.kode_kro',
                '=',
                'r.kode_kro'
            )
            ->select(
                'r.kode_ro',
                'r.nama_ro',
                'r.kode_kegiatan',
                'kg.nama_kegiatan',
                'r.kode_kro',
                'k.nama_kro',
                'p.kode_program',
                'p.nama_program'
            )
            ->when(
                $kodeProgram !== '',
                fn ($query) => $query->where(
                    'p.kode_program',
                    $kodeProgram
                )
            )
            ->when(
                $kodeKegiatan !== '',
                fn ($query) => $query->where(
                    'r.kode_kegiatan',
                    $kodeKegiatan
                )
            )
            ->when(
                $kodeKro !== '',
                fn ($query) => $query->where(
                    'r.kode_kro',
                    $kodeKro
                )
            )
            ->when(
                $search !== '',
                function ($query) use ($search) {
                    $like = '%' . $search . '%';

                    $query->where(function ($subQuery) use ($like) {
                        $subQuery
                            ->where('r.kode_ro', 'like', $like)
                            ->orWhere('r.nama_ro', 'like', $like)
                            ->orWhere('r.kode_kegiatan', 'like', $like)
                            ->orWhere('kg.nama_kegiatan', 'like', $like)
                            ->orWhere('r.kode_kro', 'like', $like)
                            ->orWhere('k.nama_kro', 'like', $like);
                    });
                }
            )
            ->orderBy('p.kode_program')
            ->orderBy('r.kode_kegiatan')
            ->orderBy('r.kode_kro')
            ->orderBy('r.kode_ro')
            ->paginate($perPage)
            ->withQueryString();
    }

    private function queryKomponen(
        string $search,
        string $kodeProgram,
        string $kodeKegiatan,
        string $kodeKro,
        string $kodeRo,
        string $jenisKomponen,
        int $perPage
    ) {
        return DB::table('komponen as c')
            ->join('ro as r', function ($join) {
                $join
                    ->on(
                        'r.kode_kegiatan',
                        '=',
                        'c.kode_kegiatan'
                    )
                    ->on(
                        'r.kode_kro',
                        '=',
                        'c.kode_kro'
                    )
                    ->on(
                        'r.kode_ro',
                        '=',
                        'c.kode_ro'
                    );
            })
            ->join(
                'kegiatan as kg',
                'kg.kode_kegiatan',
                '=',
                'c.kode_kegiatan'
            )
            ->join(
                'program as p',
                'p.kode_program',
                '=',
                'kg.kode_program'
            )
            ->join(
                'kro as k',
                'k.kode_kro',
                '=',
                'c.kode_kro'
            )
            ->select(
                'c.kode_komponen',
                'c.nama_komponen',
                'c.jenis_komponen',
                'c.kode_kegiatan',
                'kg.nama_kegiatan',
                'c.kode_kro',
                'k.nama_kro',
                'c.kode_ro',
                'r.nama_ro',
                'p.kode_program',
                'p.nama_program'
            )
            ->when(
                $kodeProgram !== '',
                fn ($query) => $query->where(
                    'p.kode_program',
                    $kodeProgram
                )
            )
            ->when(
                $kodeKegiatan !== '',
                fn ($query) => $query->where(
                    'c.kode_kegiatan',
                    $kodeKegiatan
                )
            )
            ->when(
                $kodeKro !== '',
                fn ($query) => $query->where(
                    'c.kode_kro',
                    $kodeKro
                )
            )
            ->when(
                $kodeRo !== '',
                fn ($query) => $query->where(
                    'c.kode_ro',
                    $kodeRo
                )
            )
            ->when(
                $jenisKomponen !== '',
                fn ($query) => $query->where(
                    'c.jenis_komponen',
                    $jenisKomponen
                )
            )
            ->when(
                $search !== '',
                function ($query) use ($search) {
                    $like = '%' . $search . '%';

                    $query->where(function ($subQuery) use ($like) {
                        $subQuery
                            ->where('c.kode_komponen', 'like', $like)
                            ->orWhere('c.nama_komponen', 'like', $like)
                            ->orWhere('c.kode_ro', 'like', $like)
                            ->orWhere('r.nama_ro', 'like', $like)
                            ->orWhere('c.kode_kro', 'like', $like)
                            ->orWhere('k.nama_kro', 'like', $like)
                            ->orWhere('c.kode_kegiatan', 'like', $like)
                            ->orWhere('kg.nama_kegiatan', 'like', $like);
                    });
                }
            )
            ->orderBy('p.kode_program')
            ->orderBy('c.kode_kegiatan')
            ->orderBy('c.kode_kro')
            ->orderBy('c.kode_ro')
            ->orderBy('c.kode_komponen')
            ->paginate($perPage)
            ->withQueryString();
    }

    private function querySubkomponen(
        string $search,
        string $kodeSatker,
        string $kodeProgram,
        string $kodeKegiatan,
        string $kodeKro,
        string $kodeRo,
        string $kodeKomponen,
        int $perPage
    ) {
        return DB::table('subkomponen as sc')
            ->join(
                'satker as s',
                's.kode_satker',
                '=',
                'sc.kode_satker'
            )
            ->join(
                'kegiatan as kg',
                'kg.kode_kegiatan',
                '=',
                'sc.kode_kegiatan'
            )
            ->join(
                'program as p',
                'p.kode_program',
                '=',
                'kg.kode_program'
            )
            ->join(
                'kro as k',
                'k.kode_kro',
                '=',
                'sc.kode_kro'
            )
            ->join('ro as r', function ($join) {
                $join
                    ->on(
                        'r.kode_kegiatan',
                        '=',
                        'sc.kode_kegiatan'
                    )
                    ->on(
                        'r.kode_kro',
                        '=',
                        'sc.kode_kro'
                    )
                    ->on(
                        'r.kode_ro',
                        '=',
                        'sc.kode_ro'
                    );
            })
            ->join('komponen as c', function ($join) {
                $join
                    ->on(
                        'c.kode_kegiatan',
                        '=',
                        'sc.kode_kegiatan'
                    )
                    ->on(
                        'c.kode_kro',
                        '=',
                        'sc.kode_kro'
                    )
                    ->on(
                        'c.kode_ro',
                        '=',
                        'sc.kode_ro'
                    )
                    ->on(
                        'c.kode_komponen',
                        '=',
                        'sc.kode_komponen'
                    );
            })
            ->leftJoin('subkomponen_akun as sa', function ($join) {
                $join
                    ->on(
                        'sa.kode_satker',
                        '=',
                        'sc.kode_satker'
                    )
                    ->on(
                        'sa.kode_kegiatan',
                        '=',
                        'sc.kode_kegiatan'
                    )
                    ->on(
                        'sa.kode_kro',
                        '=',
                        'sc.kode_kro'
                    )
                    ->on(
                        'sa.kode_ro',
                        '=',
                        'sc.kode_ro'
                    )
                    ->on(
                        'sa.kode_komponen',
                        '=',
                        'sc.kode_komponen'
                    )
                    ->on(
                        'sa.kode_subkomponen',
                        '=',
                        'sc.kode_subkomponen'
                    );
            })
            ->select(
                'sc.kode_satker',
                's.nama_satker',
                'sc.kode_subkomponen',
                'sc.nama_subkomponen',
                'sc.deskripsi',
                'sc.kode_kegiatan',
                'kg.nama_kegiatan',
                'sc.kode_kro',
                'k.nama_kro',
                'sc.kode_ro',
                'r.nama_ro',
                'sc.kode_komponen',
                'c.nama_komponen',
                'p.kode_program',
                'p.nama_program'
            )
            ->selectRaw(
                'COUNT(DISTINCT sa.kode_akun) AS jumlah_akun'
            )
            ->when(
                $kodeSatker !== '',
                fn ($query) => $query->where(
                    'sc.kode_satker',
                    $kodeSatker
                )
            )
            ->when(
                $kodeProgram !== '',
                fn ($query) => $query->where(
                    'p.kode_program',
                    $kodeProgram
                )
            )
            ->when(
                $kodeKegiatan !== '',
                fn ($query) => $query->where(
                    'sc.kode_kegiatan',
                    $kodeKegiatan
                )
            )
            ->when(
                $kodeKro !== '',
                fn ($query) => $query->where(
                    'sc.kode_kro',
                    $kodeKro
                )
            )
            ->when(
                $kodeRo !== '',
                fn ($query) => $query->where(
                    'sc.kode_ro',
                    $kodeRo
                )
            )
            ->when(
                $kodeKomponen !== '',
                fn ($query) => $query->where(
                    'sc.kode_komponen',
                    $kodeKomponen
                )
            )
            ->when(
                $search !== '',
                function ($query) use ($search) {
                    $like = '%' . $search . '%';

                    $query->where(function ($subQuery) use ($like) {
                        $subQuery
                            ->where('sc.kode_subkomponen', 'like', $like)
                            ->orWhere('sc.nama_subkomponen', 'like', $like)
                            ->orWhere('s.kode_satker', 'like', $like)
                            ->orWhere('s.nama_satker', 'like', $like)
                            ->orWhere('c.nama_komponen', 'like', $like)
                            ->orWhere('r.nama_ro', 'like', $like)
                            ->orWhere('kg.nama_kegiatan', 'like', $like);
                    });
                }
            )
            ->groupBy(
                'sc.kode_satker',
                's.nama_satker',
                'sc.kode_subkomponen',
                'sc.nama_subkomponen',
                'sc.deskripsi',
                'sc.kode_kegiatan',
                'kg.nama_kegiatan',
                'sc.kode_kro',
                'k.nama_kro',
                'sc.kode_ro',
                'r.nama_ro',
                'sc.kode_komponen',
                'c.nama_komponen',
                'p.kode_program',
                'p.nama_program'
            )
            ->orderBy('sc.kode_satker')
            ->orderBy('sc.kode_kegiatan')
            ->orderBy('sc.kode_kro')
            ->orderBy('sc.kode_ro')
            ->orderBy('sc.kode_komponen')
            ->orderBy('sc.kode_subkomponen')
            ->paginate($perPage)
            ->withQueryString();
    }

    private function queryAkun(
        string $search,
        int $perPage
    ) {
        $komponenCount = DB::table('komponen_akun')
            ->selectRaw(
                'kode_akun, COUNT(*) AS jumlah_komponen'
            )
            ->groupBy('kode_akun');

        $subkomponenCount = DB::table('subkomponen_akun')
            ->selectRaw(
                'kode_akun, COUNT(*) AS jumlah_subkomponen'
            )
            ->groupBy('kode_akun');

        return DB::table('akun as a')
            ->leftJoinSub(
                $komponenCount,
                'ca_count',
                'ca_count.kode_akun',
                '=',
                'a.kode_akun'
            )
            ->leftJoinSub(
                $subkomponenCount,
                'sa_count',
                'sa_count.kode_akun',
                '=',
                'a.kode_akun'
            )
            ->select(
                'a.kode_akun',
                'a.nama_akun'
            )
            ->selectRaw(
                'COALESCE(ca_count.jumlah_komponen, 0) AS jumlah_komponen'
            )
            ->selectRaw(
                'COALESCE(sa_count.jumlah_subkomponen, 0) AS jumlah_subkomponen'
            )
            ->when(
                $search !== '',
                function ($query) use ($search) {
                    $like = '%' . $search . '%';

                    $query->where(function ($subQuery) use ($like) {
                        $subQuery
                            ->where('a.kode_akun', 'like', $like)
                            ->orWhere('a.nama_akun', 'like', $like);
                    });
                }
            )
            ->orderBy('a.kode_akun')
            ->paginate($perPage)
            ->withQueryString();
    }

    private function normalizeSearch(?string $value): string
    {
        $value = trim((string) $value);

        return preg_replace('/\s+/u', ' ', $value) ?? '';
    }
}
