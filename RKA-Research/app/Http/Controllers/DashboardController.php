<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Dashboard utama Sistem Informasi Penelitian RKA-K/L.
     *
     * Seluruh query dashboard berada di controller. Blade hanya bertugas
     * menampilkan data yang sudah disiapkan.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $penelitianSummary = DB::table('penelitian')
            ->selectRaw('COUNT(*) AS total')
            ->selectRaw("SUM(CASE WHEN status = 'DRAFT' THEN 1 ELSE 0 END) AS draft")
            ->selectRaw("SUM(CASE WHEN status = 'FINAL' THEN 1 ELSE 0 END) AS final")
            ->first();

        /*
         * Statistik referensi organisasi mengikuti logika pada
         * lihatReferensiOrganisasiController: jumlah record dihitung langsung
         * dari masing-masing tabel master organisasi.
         */
        $referensiOrganisasi = [
            'unit_eselon_1' => DB::table('unit_eselon_1')->count(),
            'unit_eselon_2' => DB::table('unit_eselon_2')->count(),
            'satker' => DB::table('satker')->count(),
        ];

        /*
         * Statistik referensi penganggaran mengikuti logika pada
         * lihatReferensiPenganggaranController: jumlah record dihitung langsung
         * dari tabel master referensi, BUKAN dari kode unik yang muncul di RKA.
         */
        $referensiPenganggaran = [
            'program' => DB::table('program')->count(),
            'kegiatan' => DB::table('kegiatan')->count(),
            'kro' => DB::table('kro')->count(),
            'ro' => DB::table('ro')->count(),
            'komponen' => DB::table('komponen')->count(),
            'subkomponen' => DB::table('subkomponen')->count(),
            'akun' => DB::table('akun')->count(),
        ];

        $dashboardStats = [
            'penelitian' => [
                'total' => (int) ($penelitianSummary->total ?? 0),
                'draft' => (int) ($penelitianSummary->draft ?? 0),
                'final' => (int) ($penelitianSummary->final ?? 0),
            ],

            'referensi' => $referensiOrganisasi,
            'penganggaran' => $referensiPenganggaran,
        ];

        /*
         * penelitian_log menggunakan nama kolom:
         * event, message, userID, created_at.
         *
         * Alias dibuat di controller agar Blade menerima struktur yang mudah
         * dibaca dan tidak perlu mengetahui detail schema database.
         */
        $activityLogs = DB::table('penelitian_log as pl')
            ->leftJoin(
                'penelitian as p',
                'p.penelitianID',
                '=',
                'pl.penelitianID'
            )
            ->leftJoin(
                'users as u',
                'u.userID',
                '=',
                'pl.userID'
            )
            ->select(
                'pl.logID',
                'pl.penelitianID',
                'pl.event as action',
                'pl.message as description',
                'pl.userID',
                'pl.created_at',
                'p.nama_penelitian',
                DB::raw("COALESCE(u.name, pl.userID, '-') AS user_name")
            )
            ->orderByDesc('pl.created_at')
            ->orderByDesc('pl.logID')
            ->limit(10)
            ->get();

        return view('menu.dashboard.dashboard', [
            'user' => $user,
            'dashboardStats' => $dashboardStats,
            'activityLogs' => $activityLogs,
            'generatedAt' => now(),
        ]);
    }

}
