<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class lihatReferensiOrganisasiController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'jenis' => [
                'nullable',
                Rule::in(['unit1', 'unit2', 'satker']),
            ],
            'q' => 'nullable|string|max:100',
            'kode_unit_eselon1' => 'nullable|string|max:50',
            'kode_unit_eselon2' => 'nullable|string|max:50',
            'per_page' => [
                'nullable',
                'integer',
                Rule::in([10, 20, 50, 100]),
            ],
        ]);

        $jenis = $validated['jenis'] ?? 'unit1';
        $search = $this->normalizeSearch($validated['q'] ?? null);
        $kodeUnit1 = trim((string) ($validated['kode_unit_eselon1'] ?? ''));
        $kodeUnit2 = trim((string) ($validated['kode_unit_eselon2'] ?? ''));
        $perPage = (int) ($validated['per_page'] ?? 20);

        $stats = [
            'unit1' => DB::table('unit_eselon_1')->count(),
            'unit2' => DB::table('unit_eselon_2')->count(),
            'satker' => DB::table('satker')->count(),
        ];

        $unit1Options = DB::table('unit_eselon_1')
            ->select('kode_unit_eselon1', 'nama_unit_eselon1')
            ->orderBy('kode_unit_eselon1')
            ->get();

        $unit2Options = DB::table('unit_eselon_2')
            ->select(
                'kode_unit_eselon2',
                'nama_unit_eselon2',
                'kode_unit_eselon1'
            )
            ->orderBy('kode_unit_eselon1')
            ->orderBy('kode_unit_eselon2')
            ->get();

        $records = match ($jenis) {
            'unit2' => $this->queryUnit2(
                $search,
                $kodeUnit1,
                $perPage
            ),

            'satker' => $this->querySatker(
                $search,
                $kodeUnit1,
                $kodeUnit2,
                $perPage
            ),

            default => $this->queryUnit1(
                $search,
                $perPage
            ),
        };

        return view(
            'menu.referensi.lihat.organisasi',
            [
                'jenis' => $jenis,
                'records' => $records,
                'stats' => $stats,
                'unit1Options' => $unit1Options,
                'unit2Options' => $unit2Options,
                'filters' => [
                    'q' => $search,
                    'kode_unit_eselon1' => $kodeUnit1,
                    'kode_unit_eselon2' => $kodeUnit2,
                    'per_page' => $perPage,
                ],
            ]
        );
    }

    private function queryUnit1(
        string $search,
        int $perPage
    ) {
        return DB::table('unit_eselon_1 as u1')
            ->leftJoin(
                'unit_eselon_2 as u2',
                'u2.kode_unit_eselon1',
                '=',
                'u1.kode_unit_eselon1'
            )
            ->leftJoin(
                'satker as s',
                's.kode_unit_eselon2',
                '=',
                'u2.kode_unit_eselon2'
            )
            ->select(
                'u1.kode_unit_eselon1',
                'u1.nama_unit_eselon1'
            )
            ->selectRaw(
                'COUNT(DISTINCT u2.kode_unit_eselon2) AS jumlah_unit_eselon2'
            )
            ->selectRaw(
                'COUNT(DISTINCT s.kode_satker) AS jumlah_satker'
            )
            ->when(
                $search !== '',
                function ($query) use ($search) {
                    $like = '%' . $search . '%';

                    $query->where(function ($subQuery) use ($like) {
                        $subQuery
                            ->where('u1.kode_unit_eselon1', 'like', $like)
                            ->orWhere('u1.nama_unit_eselon1', 'like', $like);
                    });
                }
            )
            ->groupBy(
                'u1.kode_unit_eselon1',
                'u1.nama_unit_eselon1'
            )
            ->orderBy('u1.kode_unit_eselon1')
            ->paginate($perPage)
            ->withQueryString();
    }

    private function queryUnit2(
        string $search,
        string $kodeUnit1,
        int $perPage
    ) {
        return DB::table('unit_eselon_2 as u2')
            ->join(
                'unit_eselon_1 as u1',
                'u1.kode_unit_eselon1',
                '=',
                'u2.kode_unit_eselon1'
            )
            ->leftJoin(
                'satker as s',
                's.kode_unit_eselon2',
                '=',
                'u2.kode_unit_eselon2'
            )
            ->select(
                'u2.kode_unit_eselon2',
                'u2.nama_unit_eselon2',
                'u1.kode_unit_eselon1',
                'u1.nama_unit_eselon1'
            )
            ->selectRaw(
                'COUNT(DISTINCT s.kode_satker) AS jumlah_satker'
            )
            ->when(
                $kodeUnit1 !== '',
                fn ($query) => $query->where(
                    'u2.kode_unit_eselon1',
                    $kodeUnit1
                )
            )
            ->when(
                $search !== '',
                function ($query) use ($search) {
                    $like = '%' . $search . '%';

                    $query->where(function ($subQuery) use ($like) {
                        $subQuery
                            ->where('u2.kode_unit_eselon2', 'like', $like)
                            ->orWhere('u2.nama_unit_eselon2', 'like', $like)
                            ->orWhere('u1.kode_unit_eselon1', 'like', $like)
                            ->orWhere('u1.nama_unit_eselon1', 'like', $like);
                    });
                }
            )
            ->groupBy(
                'u2.kode_unit_eselon2',
                'u2.nama_unit_eselon2',
                'u1.kode_unit_eselon1',
                'u1.nama_unit_eselon1'
            )
            ->orderBy('u1.kode_unit_eselon1')
            ->orderBy('u2.kode_unit_eselon2')
            ->paginate($perPage)
            ->withQueryString();
    }

    private function querySatker(
        string $search,
        string $kodeUnit1,
        string $kodeUnit2,
        int $perPage
    ) {
        return DB::table('satker as s')
            ->join(
                'unit_eselon_2 as u2',
                'u2.kode_unit_eselon2',
                '=',
                's.kode_unit_eselon2'
            )
            ->join(
                'unit_eselon_1 as u1',
                'u1.kode_unit_eselon1',
                '=',
                'u2.kode_unit_eselon1'
            )
            ->select(
                's.kode_satker',
                's.nama_satker',
                'u2.kode_unit_eselon2',
                'u2.nama_unit_eselon2',
                'u1.kode_unit_eselon1',
                'u1.nama_unit_eselon1'
            )
            ->when(
                $kodeUnit1 !== '',
                fn ($query) => $query->where(
                    'u1.kode_unit_eselon1',
                    $kodeUnit1
                )
            )
            ->when(
                $kodeUnit2 !== '',
                fn ($query) => $query->where(
                    'u2.kode_unit_eselon2',
                    $kodeUnit2
                )
            )
            ->when(
                $search !== '',
                function ($query) use ($search) {
                    $like = '%' . $search . '%';

                    $query->where(function ($subQuery) use ($like) {
                        $subQuery
                            ->where('s.kode_satker', 'like', $like)
                            ->orWhere('s.nama_satker', 'like', $like)
                            ->orWhere('u2.kode_unit_eselon2', 'like', $like)
                            ->orWhere('u2.nama_unit_eselon2', 'like', $like)
                            ->orWhere('u1.kode_unit_eselon1', 'like', $like)
                            ->orWhere('u1.nama_unit_eselon1', 'like', $like);
                    });
                }
            )
            ->orderBy('u1.kode_unit_eselon1')
            ->orderBy('u2.kode_unit_eselon2')
            ->orderBy('s.kode_satker')
            ->paginate($perPage)
            ->withQueryString();
    }

    private function normalizeSearch(?string $value): string
    {
        $value = trim((string) $value);

        return preg_replace('/\s+/u', ' ', $value) ?? '';
    }
}
