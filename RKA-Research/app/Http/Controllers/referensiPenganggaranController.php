<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class referensiPenganggaranController extends Controller
{
    public function showProgram()
    {
        return view('menu.referensi.penganggaran.program');
    }

    public function showKegiatan()
    {
        $program = $this->programData();

        $satker = DB::table('satker as s')
            ->leftJoin('unit_eselon_2 as u2', 'u2.kode_unit_eselon2', '=', 's.kode_unit_eselon2')
            ->leftJoin('unit_eselon_1 as u1', 'u1.kode_unit_eselon1', '=', 'u2.kode_unit_eselon1')
            ->select(
                's.kode_satker',
                's.nama_satker',
                's.kode_unit_eselon2',
                'u2.nama_unit_eselon2',
                'u2.kode_unit_eselon1',
                'u1.nama_unit_eselon1'
            )
            ->orderBy('u2.kode_unit_eselon1')
            ->orderBy('s.kode_unit_eselon2')
            ->orderBy('s.kode_satker')
            ->get();

        return view('menu.referensi.penganggaran.kegiatan', compact('program', 'satker'));
    }

    public function showKro()
    {
        $program = $this->programData();
        $kegiatan = $this->kegiatanData();

        return view('menu.referensi.penganggaran.kro', compact('program', 'kegiatan'));
    }

    public function showRo()
    {
        $program = $this->programData();
        $kegiatan = $this->kegiatanData();
        $kro = $this->kroByKegiatanData();

        return view('menu.referensi.penganggaran.ro', compact('program', 'kegiatan', 'kro'));
    }

    public function showKomponen()
    {
        $program = $this->programData();
        $kegiatan = $this->kegiatanData();
        $kro = $this->kroByKegiatanData();
        $ro = $this->roData();

        return view('menu.referensi.penganggaran.komponen', compact('program', 'kegiatan', 'kro', 'ro'));
    }

    public function showSubkomponen()
    {
        $unitEselon1 = $this->unitEselon1Data();
        $unitEselon2 = $this->unitEselon2Data();
        $satker = $this->satkerData();
        $satkerKegiatan = $this->satkerKegiatanData();
        $program = $this->programData();
        $kegiatan = $this->kegiatanData();
        $kro = $this->kroByKegiatanData();
        $ro = $this->roData();
        $komponen = $this->komponenData();

        return view('menu.referensi.penganggaran.subkomponen', compact(
            'unitEselon1',
            'unitEselon2',
            'satker',
            'satkerKegiatan',
            'program',
            'kegiatan',
            'kro',
            'ro',
            'komponen'
        ));
    }

    public function showAkun()
    {
        $unitEselon1 = $this->unitEselon1Data();
        $unitEselon2 = $this->unitEselon2Data();
        $satker = $this->satkerData();
        $satkerKegiatan = $this->satkerKegiatanData();
        $program = $this->programData();
        $kegiatan = $this->kegiatanData();
        $kro = $this->kroByKegiatanData();
        $ro = $this->roData();
        $komponen = $this->komponenData();
        $subkomponen = $this->subkomponenData();

        return view('menu.referensi.penganggaran.akun', compact(
            'unitEselon1',
            'unitEselon2',
            'satker',
            'satkerKegiatan',
            'program',
            'kegiatan',
            'kro',
            'ro',
            'komponen',
            'subkomponen'
        ));
    }

    public function storeProgram(Request $request)
    {
        $this->normalizeInput($request, ['kode_program', 'nama_program']);

        $request->validate([
            'kode_program' => 'required|string|max:50',
            'nama_program' => 'required|string|max:255',
        ], [
            'kode_program.required' => 'Kode Program wajib diisi.',
            'nama_program.required' => 'Nama Program wajib diisi.',
        ]);

        $kode = $request->input('kode_program');
        $nama = $request->input('nama_program');

        if (DB::table('program')->where('kode_program', $kode)->exists()) {
            return $this->validationFailure(
                $request,
                'kode_program',
                "Kode Program {$kode} sudah tersedia dalam data referensi."
            );
        }

        try {
            DB::table('program')->insert([
                'kode_program' => $kode,
                'nama_program' => $nama,
            ]);

            return $this->successResponse(
                $request,
                'Data Referensi Berhasil Ditambahkan',
                "Program {$kode} - {$nama} berhasil ditambahkan."
            );
        } catch (QueryException $e) {
            return $this->handleDatabaseException(
                $request,
                $e,
                'kode_program',
                "Kode Program {$kode} sudah tersedia dalam data referensi."
            );
        } catch (\Throwable $e) {
            return $this->handleUnexpectedException($request, $e, 'Program');
        }
    }

    public function storeKegiatan(Request $request)
    {
        $this->normalizeInput($request, [
            'kode_program',
            'kode_kegiatan',
            'nama_kegiatan',
        ]);
        $this->normalizeStringArray($request, 'kode_satker');

        $request->validate([
            'kode_program' => 'required|string|max:50',
            'kode_kegiatan' => 'required|string|max:50',
            'nama_kegiatan' => 'required|string|max:255',
            'kode_satker' => 'required|array|min:1',
            'kode_satker.*' => 'required|string|max:50|distinct',
        ], [
            'kode_program.required' => 'Program wajib dipilih.',
            'kode_kegiatan.required' => 'Kode Kegiatan wajib diisi.',
            'nama_kegiatan.required' => 'Nama Kegiatan wajib diisi.',
            'kode_satker.required' => 'Minimal satu Satker Pelaksana wajib dipilih.',
            'kode_satker.min' => 'Minimal satu Satker Pelaksana wajib dipilih.',
        ]);

        $kodeProgram = $request->input('kode_program');
        $kodeKegiatan = $request->input('kode_kegiatan');
        $namaKegiatan = $request->input('nama_kegiatan');
        $kodeSatker = array_values(array_unique($request->input('kode_satker', [])));

        if (!DB::table('program')->where('kode_program', $kodeProgram)->exists()) {
            return $this->validationFailure($request, 'kode_program', 'Program yang dipilih tidak ditemukan.');
        }

        if (DB::table('kegiatan')->where('kode_kegiatan', $kodeKegiatan)->exists()) {
            return $this->validationFailure(
                $request,
                'kode_kegiatan',
                "Kode Kegiatan {$kodeKegiatan} sudah tersedia dalam data referensi."
            );
        }

        $jumlahSatkerValid = DB::table('satker')
            ->whereIn('kode_satker', $kodeSatker)
            ->distinct()
            ->count('kode_satker');

        if ($jumlahSatkerValid !== count($kodeSatker)) {
            return $this->validationFailure(
                $request,
                'kode_satker',
                'Terdapat Satker Pelaksana yang tidak ditemukan dalam data referensi.'
            );
        }

        try {
            DB::transaction(function () use ($kodeProgram, $kodeKegiatan, $namaKegiatan, $kodeSatker) {
                DB::table('kegiatan')->insert([
                    'kode_kegiatan' => $kodeKegiatan,
                    'nama_kegiatan' => $namaKegiatan,
                    'kode_program' => $kodeProgram,
                ]);

                DB::table('satker_kegiatan')->insert(
                    array_map(
                        fn ($satker) => [
                            'kode_satker' => $satker,
                            'kode_kegiatan' => $kodeKegiatan,
                        ],
                        $kodeSatker
                    )
                );
            }, 3);

            return $this->successResponse(
                $request,
                'Data Referensi Berhasil Ditambahkan',
                "Kegiatan {$kodeKegiatan} - {$namaKegiatan} berhasil ditambahkan dan dipetakan ke "
                    . count($kodeSatker)
                    . ' Satker.'
            );
        } catch (QueryException $e) {
            return $this->handleDatabaseException(
                $request,
                $e,
                'kode_kegiatan',
                "Kode Kegiatan {$kodeKegiatan} sudah tersedia dalam data referensi."
            );
        } catch (\Throwable $e) {
            return $this->handleUnexpectedException($request, $e, 'Kegiatan');
        }
    }

    public function storeKro(Request $request)
    {
        $this->normalizeInput($request, [
            'kode_program',
            'kode_kegiatan',
            'kode_kro',
            'nama_kro',
        ]);

        $request->validate([
            'kode_program' => 'required|string|max:50',
            'kode_kegiatan' => 'required|string|max:50',
            'kode_kro' => 'required|string|max:50',
            'nama_kro' => 'required|string|max:255',
        ], [
            'kode_program.required' => 'Program wajib dipilih.',
            'kode_kegiatan.required' => 'Kegiatan wajib dipilih.',
            'kode_kro.required' => 'Kode KRO wajib diisi.',
            'nama_kro.required' => 'Nama KRO wajib diisi.',
        ]);

        $kodeProgram = $request->input('kode_program');
        $kodeKegiatan = $request->input('kode_kegiatan');
        $kodeKro = $request->input('kode_kro');
        $namaKro = $request->input('nama_kro');

        if (!$this->kegiatanBelongsProgram($kodeKegiatan, $kodeProgram)) {
            return $this->validationFailure(
                $request,
                'kode_kegiatan',
                'Kegiatan yang dipilih tidak sesuai dengan Program.'
            );
        }

        $existingKro = DB::table('kro')->where('kode_kro', $kodeKro)->first();

        if ($existingKro && $this->normalizeComparableName($existingKro->nama_kro) !== $this->normalizeComparableName($namaKro)) {
            return $this->validationFailure(
                $request,
                'nama_kro',
                "Kode KRO {$kodeKro} sudah terdapat pada master KRO dengan nama \"{$existingKro->nama_kro}\". "
                    . 'Gunakan nomenklatur yang sama.'
            );
        }

        if (DB::table('kegiatan_kro')
            ->where('kode_kegiatan', $kodeKegiatan)
            ->where('kode_kro', $kodeKro)
            ->exists()) {
            return $this->validationFailure(
                $request,
                'kode_kro',
                "KRO {$kodeKro} sudah terdaftar pada Kegiatan {$kodeKegiatan}."
            );
        }

        try {
            DB::transaction(function () use ($existingKro, $kodeKegiatan, $kodeKro, $namaKro) {
                if (!$existingKro) {
                    DB::table('kro')->insert([
                        'kode_kro' => $kodeKro,
                        'nama_kro' => $namaKro,
                    ]);
                }

                DB::table('kegiatan_kro')->insert([
                    'kode_kegiatan' => $kodeKegiatan,
                    'kode_kro' => $kodeKro,
                ]);
            }, 3);

            $message = $existingKro
                ? "KRO {$kodeKro} - {$existingKro->nama_kro} berhasil dipetakan ke Kegiatan {$kodeKegiatan}."
                : "KRO {$kodeKro} - {$namaKro} berhasil ditambahkan dan dipetakan ke Kegiatan {$kodeKegiatan}.";

            return $this->successResponse(
                $request,
                'Data Referensi Berhasil Ditambahkan',
                $message
            );
        } catch (QueryException $e) {
            return $this->handleDatabaseException(
                $request,
                $e,
                'kode_kro',
                "KRO {$kodeKro} sudah terdaftar pada Kegiatan {$kodeKegiatan}."
            );
        } catch (\Throwable $e) {
            return $this->handleUnexpectedException($request, $e, 'KRO');
        }
    }

    public function storeRo(Request $request)
    {
        $this->normalizeInput($request, [
            'kode_program',
            'kode_kegiatan',
            'kode_kro',
            'kode_ro',
            'nama_ro',
        ]);

        $request->validate([
            'kode_program' => 'required|string|max:50',
            'kode_kegiatan' => 'required|string|max:50',
            'kode_kro' => 'required|string|max:50',
            'kode_ro' => 'required|string|max:50',
            'nama_ro' => 'required|string|max:255',
        ]);

        $kodeProgram = $request->input('kode_program');
        $kodeKegiatan = $request->input('kode_kegiatan');
        $kodeKro = $request->input('kode_kro');
        $kodeRo = $request->input('kode_ro');
        $namaRo = $request->input('nama_ro');

        if (!$this->kegiatanBelongsProgram($kodeKegiatan, $kodeProgram)) {
            return $this->validationFailure($request, 'kode_kegiatan', 'Kegiatan tidak sesuai dengan Program.');
        }

        if (!$this->kroBelongsKegiatan($kodeKegiatan, $kodeKro)) {
            return $this->validationFailure($request, 'kode_kro', 'KRO tidak terdaftar pada Kegiatan yang dipilih.');
        }

        if (DB::table('ro')
            ->where('kode_kegiatan', $kodeKegiatan)
            ->where('kode_kro', $kodeKro)
            ->where('kode_ro', $kodeRo)
            ->exists()) {
            return $this->validationFailure(
                $request,
                'kode_ro',
                "RO {$kodeRo} sudah tersedia pada kombinasi Kegiatan {$kodeKegiatan} dan KRO {$kodeKro}."
            );
        }

        try {
            DB::table('ro')->insert([
                'kode_kegiatan' => $kodeKegiatan,
                'kode_kro' => $kodeKro,
                'kode_ro' => $kodeRo,
                'nama_ro' => $namaRo,
            ]);

            return $this->successResponse(
                $request,
                'Data Referensi Berhasil Ditambahkan',
                "RO {$kodeRo} - {$namaRo} berhasil ditambahkan."
            );
        } catch (QueryException $e) {
            return $this->handleDatabaseException(
                $request,
                $e,
                'kode_ro',
                "RO {$kodeRo} sudah tersedia pada kombinasi Kegiatan dan KRO tersebut."
            );
        } catch (\Throwable $e) {
            return $this->handleUnexpectedException($request, $e, 'RO');
        }
    }

    public function storeKomponen(Request $request)
    {
        $this->normalizeInput($request, [
            'kode_program',
            'kode_kegiatan',
            'kode_kro',
            'kode_ro',
            'kode_komponen',
            'nama_komponen',
            'jenis_komponen',
        ]);

        $request->merge([
            'jenis_komponen' => strtoupper((string) $request->input('jenis_komponen')),
        ]);

        $request->validate([
            'kode_program' => 'required|string|max:50',
            'kode_kegiatan' => 'required|string|max:50',
            'kode_kro' => 'required|string|max:50',
            'kode_ro' => 'required|string|max:50',
            'kode_komponen' => 'required|string|max:50',
            'nama_komponen' => 'required|string|max:255',
            'jenis_komponen' => 'required|in:U,P',
        ], [
            'jenis_komponen.in' => 'Jenis Komponen hanya dapat diisi U (Utama) atau P (Pendukung).',
        ]);

        $kodeProgram = $request->input('kode_program');
        $kodeKegiatan = $request->input('kode_kegiatan');
        $kodeKro = $request->input('kode_kro');
        $kodeRo = $request->input('kode_ro');
        $kodeKomponen = $request->input('kode_komponen');
        $namaKomponen = $request->input('nama_komponen');
        $jenisKomponen = $request->input('jenis_komponen');

        if (!$this->kegiatanBelongsProgram($kodeKegiatan, $kodeProgram)) {
            return $this->validationFailure($request, 'kode_kegiatan', 'Kegiatan tidak sesuai dengan Program.');
        }

        if (!$this->kroBelongsKegiatan($kodeKegiatan, $kodeKro)) {
            return $this->validationFailure($request, 'kode_kro', 'KRO tidak terdaftar pada Kegiatan yang dipilih.');
        }

        if (!$this->roBelongsKro($kodeKegiatan, $kodeKro, $kodeRo)) {
            return $this->validationFailure($request, 'kode_ro', 'RO tidak sesuai dengan Kegiatan dan KRO yang dipilih.');
        }

        if (DB::table('komponen')
            ->where('kode_kegiatan', $kodeKegiatan)
            ->where('kode_kro', $kodeKro)
            ->where('kode_ro', $kodeRo)
            ->where('kode_komponen', $kodeKomponen)
            ->exists()) {
            return $this->validationFailure(
                $request,
                'kode_komponen',
                "Komponen {$kodeKomponen} sudah tersedia pada RO yang dipilih."
            );
        }

        try {
            DB::table('komponen')->insert([
                'kode_kegiatan' => $kodeKegiatan,
                'kode_kro' => $kodeKro,
                'kode_ro' => $kodeRo,
                'kode_komponen' => $kodeKomponen,
                'nama_komponen' => $namaKomponen,
                'jenis_komponen' => $jenisKomponen,
            ]);

            return $this->successResponse(
                $request,
                'Data Referensi Berhasil Ditambahkan',
                "Komponen {$kodeKomponen} - {$namaKomponen} berhasil ditambahkan."
            );
        } catch (QueryException $e) {
            return $this->handleDatabaseException(
                $request,
                $e,
                'kode_komponen',
                "Komponen {$kodeKomponen} sudah tersedia pada RO yang dipilih."
            );
        } catch (\Throwable $e) {
            return $this->handleUnexpectedException($request, $e, 'Komponen');
        }
    }

    public function storeSubkomponen(Request $request)
    {
        $this->normalizeInput($request, [
            'kode_unit_eselon1',
            'kode_unit_eselon2',
            'kode_satker',
            'kode_program',
            'kode_kegiatan',
            'kode_kro',
            'kode_ro',
            'kode_komponen',
            'kode_subkomponen',
            'nama_subkomponen',
            'deskripsi',
        ], ['deskripsi']);

        $request->validate([
            'kode_unit_eselon1' => 'required|string|max:50',
            'kode_unit_eselon2' => 'required|string|max:50',
            'kode_satker' => 'required|string|max:50',
            'kode_program' => 'required|string|max:50',
            'kode_kegiatan' => 'required|string|max:50',
            'kode_kro' => 'required|string|max:50',
            'kode_ro' => 'required|string|max:50',
            'kode_komponen' => 'required|string|max:50',
            'kode_subkomponen' => 'required|string|max:10',
            'nama_subkomponen' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $kodeSatker = $request->input('kode_satker');
        $kodeKegiatan = $request->input('kode_kegiatan');
        $kodeKro = $request->input('kode_kro');
        $kodeRo = $request->input('kode_ro');
        $kodeKomponen = $request->input('kode_komponen');
        $kodeSubkomponen = $request->input('kode_subkomponen');
        $namaSubkomponen = $request->input('nama_subkomponen');

        if (!$this->organizationHierarchyIsValid(
            $request->input('kode_unit_eselon1'),
            $request->input('kode_unit_eselon2'),
            $kodeSatker
        )) {
            return $this->validationFailure(
                $request,
                'kode_satker',
                'Kombinasi Unit Eselon I, Unit Eselon II, dan Satker tidak valid.'
            );
        }

        if (!$this->kegiatanBelongsProgram($kodeKegiatan, $request->input('kode_program'))) {
            return $this->validationFailure($request, 'kode_kegiatan', 'Kegiatan tidak sesuai dengan Program.');
        }

        if (!$this->satkerRunsKegiatan($kodeSatker, $kodeKegiatan)) {
            return $this->validationFailure($request, 'kode_kegiatan', 'Kegiatan tidak terdaftar untuk Satker yang dipilih.');
        }

        if (!$this->kroBelongsKegiatan($kodeKegiatan, $kodeKro)) {
            return $this->validationFailure($request, 'kode_kro', 'KRO tidak terdaftar pada Kegiatan yang dipilih.');
        }

        if (!$this->roBelongsKro($kodeKegiatan, $kodeKro, $kodeRo)) {
            return $this->validationFailure($request, 'kode_ro', 'RO tidak sesuai dengan Kegiatan dan KRO yang dipilih.');
        }

        if (!$this->komponenBelongsRo($kodeKegiatan, $kodeKro, $kodeRo, $kodeKomponen)) {
            return $this->validationFailure($request, 'kode_komponen', 'Komponen tidak sesuai dengan RO yang dipilih.');
        }

        if (DB::table('subkomponen')
            ->where('kode_satker', $kodeSatker)
            ->where('kode_kegiatan', $kodeKegiatan)
            ->where('kode_kro', $kodeKro)
            ->where('kode_ro', $kodeRo)
            ->where('kode_komponen', $kodeKomponen)
            ->where('kode_subkomponen', $kodeSubkomponen)
            ->exists()) {
            return $this->validationFailure(
                $request,
                'kode_subkomponen',
                "Subkomponen {$kodeSubkomponen} sudah tersedia pada Satker dan Komponen yang dipilih."
            );
        }

        try {
            DB::table('subkomponen')->insert([
                'kode_satker' => $kodeSatker,
                'kode_kegiatan' => $kodeKegiatan,
                'kode_kro' => $kodeKro,
                'kode_ro' => $kodeRo,
                'kode_komponen' => $kodeKomponen,
                'kode_subkomponen' => $kodeSubkomponen,
                'nama_subkomponen' => $namaSubkomponen,
                'deskripsi' => $request->filled('deskripsi') ? $request->input('deskripsi') : null,
            ]);

            return $this->successResponse(
                $request,
                'Data Referensi Berhasil Ditambahkan',
                "Subkomponen {$kodeSubkomponen} - {$namaSubkomponen} berhasil ditambahkan untuk Satker {$kodeSatker}."
            );
        } catch (QueryException $e) {
            return $this->handleDatabaseException(
                $request,
                $e,
                'kode_subkomponen',
                "Subkomponen {$kodeSubkomponen} sudah tersedia pada Satker dan Komponen yang dipilih."
            );
        } catch (\Throwable $e) {
            return $this->handleUnexpectedException($request, $e, 'Subkomponen');
        }
    }

    public function storeAkun(Request $request)
    {
        $this->normalizeInput($request, [
            'mode_penempatan',
            'kode_akun',
            'nama_akun',
            'kode_unit_eselon1',
            'kode_unit_eselon2',
            'kode_satker',
            'kode_program',
            'kode_kegiatan',
            'kode_kro',
            'kode_ro',
            'kode_komponen',
            'kode_subkomponen',
        ]);

        $request->merge([
            'mode_penempatan' => strtoupper((string) $request->input('mode_penempatan', 'KOMPONEN')),
        ]);

        $request->validate([
            'mode_penempatan' => 'required|in:KOMPONEN,SUBKOMPONEN',
            'kode_akun' => 'required|string|max:10',
            'nama_akun' => 'required|string|max:255',
        ], [
            'mode_penempatan.in' => 'Jenis penempatan Akun tidak valid.',
            'kode_akun.required' => 'Kode Akun wajib diisi.',
            'nama_akun.required' => 'Nama Akun wajib diisi.',
        ]);

        $mode = $request->input('mode_penempatan');
        $kodeAkun = $request->input('kode_akun');
        $namaAkun = $request->input('nama_akun');
        $existingAkun = DB::table('akun')->where('kode_akun', $kodeAkun)->first();

        if ($existingAkun && $this->normalizeComparableName($existingAkun->nama_akun) !== $this->normalizeComparableName($namaAkun)) {
            return $this->validationFailure(
                $request,
                'nama_akun',
                "Kode Akun {$kodeAkun} sudah terdapat pada master Akun dengan nama \"{$existingAkun->nama_akun}\". "
                    . 'Gunakan nomenklatur yang sama.'
            );
        }

        $request->validate([
            'kode_program' => 'required|string|max:50',
            'kode_kegiatan' => 'required|string|max:50',
            'kode_kro' => 'required|string|max:50',
            'kode_ro' => 'required|string|max:50',
            'kode_komponen' => 'required|string|max:50',
        ]);

        $kodeProgram = $request->input('kode_program');
        $kodeKegiatan = $request->input('kode_kegiatan');
        $kodeKro = $request->input('kode_kro');
        $kodeRo = $request->input('kode_ro');
        $kodeKomponen = $request->input('kode_komponen');

        if (!$this->kegiatanBelongsProgram($kodeKegiatan, $kodeProgram)) {
            return $this->validationFailure($request, 'kode_kegiatan', 'Kegiatan tidak sesuai dengan Program.');
        }

        if (!$this->kroBelongsKegiatan($kodeKegiatan, $kodeKro)) {
            return $this->validationFailure($request, 'kode_kro', 'KRO tidak terdaftar pada Kegiatan yang dipilih.');
        }

        if (!$this->roBelongsKro($kodeKegiatan, $kodeKro, $kodeRo)) {
            return $this->validationFailure($request, 'kode_ro', 'RO tidak sesuai dengan Kegiatan dan KRO yang dipilih.');
        }

        if (!$this->komponenBelongsRo($kodeKegiatan, $kodeKro, $kodeRo, $kodeKomponen)) {
            return $this->validationFailure($request, 'kode_komponen', 'Komponen tidak sesuai dengan RO yang dipilih.');
        }

        if ($mode === 'KOMPONEN') {
            $mappingExists = DB::table('komponen_akun')
                ->where('kode_kegiatan', $kodeKegiatan)
                ->where('kode_kro', $kodeKro)
                ->where('kode_ro', $kodeRo)
                ->where('kode_komponen', $kodeKomponen)
                ->where('kode_akun', $kodeAkun)
                ->exists();

            if ($mappingExists) {
                return $this->validationFailure(
                    $request,
                    'kode_akun',
                    "Akun {$kodeAkun} sudah terdaftar langsung pada Komponen yang dipilih."
                );
            }

            try {
                DB::transaction(function () use (
                    $existingAkun,
                    $kodeAkun,
                    $namaAkun,
                    $kodeKegiatan,
                    $kodeKro,
                    $kodeRo,
                    $kodeKomponen
                ) {
                    if (!$existingAkun) {
                        DB::table('akun')->insert([
                            'kode_akun' => $kodeAkun,
                            'nama_akun' => $namaAkun,
                        ]);
                    }

                    DB::table('komponen_akun')->insert([
                        'kode_kegiatan' => $kodeKegiatan,
                        'kode_kro' => $kodeKro,
                        'kode_ro' => $kodeRo,
                        'kode_komponen' => $kodeKomponen,
                        'kode_akun' => $kodeAkun,
                    ]);
                }, 3);

                return $this->successResponse(
                    $request,
                    'Data Referensi Berhasil Ditambahkan',
                    "Akun {$kodeAkun} - {$namaAkun} berhasil dipetakan langsung ke Komponen yang dipilih."
                );
            } catch (QueryException $e) {
                return $this->handleDatabaseException(
                    $request,
                    $e,
                    'kode_akun',
                    "Akun {$kodeAkun} sudah terdaftar langsung pada Komponen yang dipilih."
                );
            } catch (\Throwable $e) {
                return $this->handleUnexpectedException($request, $e, 'Akun');
            }
        }

        $request->validate([
            'kode_unit_eselon1' => 'required|string|max:50',
            'kode_unit_eselon2' => 'required|string|max:50',
            'kode_satker' => 'required|string|max:50',
            'kode_subkomponen' => 'required|string|max:10',
        ]);

        $kodeSatker = $request->input('kode_satker');
        $kodeSubkomponen = $request->input('kode_subkomponen');

        if (!$this->organizationHierarchyIsValid(
            $request->input('kode_unit_eselon1'),
            $request->input('kode_unit_eselon2'),
            $kodeSatker
        )) {
            return $this->validationFailure(
                $request,
                'kode_satker',
                'Kombinasi Unit Eselon I, Unit Eselon II, dan Satker tidak valid.'
            );
        }

        if (!$this->satkerRunsKegiatan($kodeSatker, $kodeKegiatan)) {
            return $this->validationFailure($request, 'kode_kegiatan', 'Kegiatan tidak terdaftar untuk Satker yang dipilih.');
        }

        if (!$this->subkomponenExists(
            $kodeSatker,
            $kodeKegiatan,
            $kodeKro,
            $kodeRo,
            $kodeKomponen,
            $kodeSubkomponen
        )) {
            return $this->validationFailure($request, 'kode_subkomponen', 'Subkomponen tidak sesuai dengan Satker dan Komponen yang dipilih.');
        }

        $mappingExists = DB::table('subkomponen_akun')
            ->where('kode_satker', $kodeSatker)
            ->where('kode_kegiatan', $kodeKegiatan)
            ->where('kode_kro', $kodeKro)
            ->where('kode_ro', $kodeRo)
            ->where('kode_komponen', $kodeKomponen)
            ->where('kode_subkomponen', $kodeSubkomponen)
            ->where('kode_akun', $kodeAkun)
            ->exists();

        if ($mappingExists) {
            return $this->validationFailure(
                $request,
                'kode_akun',
                "Akun {$kodeAkun} sudah terdaftar pada Subkomponen yang dipilih."
            );
        }

        try {
            DB::transaction(function () use (
                $existingAkun,
                $kodeAkun,
                $namaAkun,
                $kodeSatker,
                $kodeKegiatan,
                $kodeKro,
                $kodeRo,
                $kodeKomponen,
                $kodeSubkomponen
            ) {
                if (!$existingAkun) {
                    DB::table('akun')->insert([
                        'kode_akun' => $kodeAkun,
                        'nama_akun' => $namaAkun,
                    ]);
                }

                DB::table('subkomponen_akun')->insert([
                    'kode_satker' => $kodeSatker,
                    'kode_kegiatan' => $kodeKegiatan,
                    'kode_kro' => $kodeKro,
                    'kode_ro' => $kodeRo,
                    'kode_komponen' => $kodeKomponen,
                    'kode_subkomponen' => $kodeSubkomponen,
                    'kode_akun' => $kodeAkun,
                ]);
            }, 3);

            return $this->successResponse(
                $request,
                'Data Referensi Berhasil Ditambahkan',
                "Akun {$kodeAkun} - {$namaAkun} berhasil dipetakan ke Subkomponen {$kodeSubkomponen}."
            );
        } catch (QueryException $e) {
            return $this->handleDatabaseException(
                $request,
                $e,
                'kode_akun',
                "Akun {$kodeAkun} sudah terdaftar pada Subkomponen yang dipilih."
            );
        } catch (\Throwable $e) {
            return $this->handleUnexpectedException($request, $e, 'Akun');
        }
    }

    private function programData()
    {
        return DB::table('program')
            ->select('kode_program', 'nama_program')
            ->orderBy('kode_program')
            ->get();
    }

    private function kegiatanData()
    {
        return DB::table('kegiatan')
            ->select('kode_kegiatan', 'nama_kegiatan', 'kode_program')
            ->orderBy('kode_program')
            ->orderBy('kode_kegiatan')
            ->get();
    }

    private function kroByKegiatanData()
    {
        return DB::table('kegiatan_kro as kk')
            ->join('kro as k', 'k.kode_kro', '=', 'kk.kode_kro')
            ->select('kk.kode_kegiatan', 'k.kode_kro', 'k.nama_kro')
            ->orderBy('kk.kode_kegiatan')
            ->orderBy('k.kode_kro')
            ->get();
    }

    private function roData()
    {
        return DB::table('ro')
            ->select('kode_kegiatan', 'kode_kro', 'kode_ro', 'nama_ro')
            ->orderBy('kode_kegiatan')
            ->orderBy('kode_kro')
            ->orderBy('kode_ro')
            ->get();
    }

    private function komponenData()
    {
        return DB::table('komponen')
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
    }

    private function subkomponenData()
    {
        return DB::table('subkomponen')
            ->select(
                'kode_satker',
                'kode_kegiatan',
                'kode_kro',
                'kode_ro',
                'kode_komponen',
                'kode_subkomponen',
                'nama_subkomponen'
            )
            ->orderBy('kode_satker')
            ->orderBy('kode_kegiatan')
            ->orderBy('kode_kro')
            ->orderBy('kode_ro')
            ->orderBy('kode_komponen')
            ->orderBy('kode_subkomponen')
            ->get();
    }

    private function unitEselon1Data()
    {
        return DB::table('unit_eselon_1')
            ->select('kode_unit_eselon1', 'nama_unit_eselon1')
            ->orderBy('kode_unit_eselon1')
            ->get();
    }

    private function unitEselon2Data()
    {
        return DB::table('unit_eselon_2')
            ->select('kode_unit_eselon2', 'nama_unit_eselon2', 'kode_unit_eselon1')
            ->orderBy('kode_unit_eselon1')
            ->orderBy('kode_unit_eselon2')
            ->get();
    }

    private function satkerData()
    {
        return DB::table('satker')
            ->select('kode_satker', 'nama_satker', 'kode_unit_eselon2')
            ->orderBy('kode_unit_eselon2')
            ->orderBy('kode_satker')
            ->get();
    }

    private function satkerKegiatanData()
    {
        return DB::table('satker_kegiatan')
            ->select('kode_satker', 'kode_kegiatan')
            ->orderBy('kode_satker')
            ->orderBy('kode_kegiatan')
            ->get();
    }

    private function kegiatanBelongsProgram(string $kodeKegiatan, string $kodeProgram): bool
    {
        return DB::table('kegiatan')
            ->where('kode_kegiatan', $kodeKegiatan)
            ->where('kode_program', $kodeProgram)
            ->exists();
    }

    private function kroBelongsKegiatan(string $kodeKegiatan, string $kodeKro): bool
    {
        return DB::table('kegiatan_kro')
            ->where('kode_kegiatan', $kodeKegiatan)
            ->where('kode_kro', $kodeKro)
            ->exists();
    }

    private function roBelongsKro(string $kodeKegiatan, string $kodeKro, string $kodeRo): bool
    {
        return DB::table('ro')
            ->where('kode_kegiatan', $kodeKegiatan)
            ->where('kode_kro', $kodeKro)
            ->where('kode_ro', $kodeRo)
            ->exists();
    }

    private function komponenBelongsRo(
        string $kodeKegiatan,
        string $kodeKro,
        string $kodeRo,
        string $kodeKomponen
    ): bool {
        return DB::table('komponen')
            ->where('kode_kegiatan', $kodeKegiatan)
            ->where('kode_kro', $kodeKro)
            ->where('kode_ro', $kodeRo)
            ->where('kode_komponen', $kodeKomponen)
            ->exists();
    }

    private function satkerRunsKegiatan(string $kodeSatker, string $kodeKegiatan): bool
    {
        return DB::table('satker_kegiatan')
            ->where('kode_satker', $kodeSatker)
            ->where('kode_kegiatan', $kodeKegiatan)
            ->exists();
    }

    private function organizationHierarchyIsValid(
        string $kodeUnit1,
        string $kodeUnit2,
        string $kodeSatker
    ): bool {
        return DB::table('satker as s')
            ->join('unit_eselon_2 as u2', 'u2.kode_unit_eselon2', '=', 's.kode_unit_eselon2')
            ->where('s.kode_satker', $kodeSatker)
            ->where('s.kode_unit_eselon2', $kodeUnit2)
            ->where('u2.kode_unit_eselon1', $kodeUnit1)
            ->exists();
    }

    private function subkomponenExists(
        string $kodeSatker,
        string $kodeKegiatan,
        string $kodeKro,
        string $kodeRo,
        string $kodeKomponen,
        string $kodeSubkomponen
    ): bool {
        return DB::table('subkomponen')
            ->where('kode_satker', $kodeSatker)
            ->where('kode_kegiatan', $kodeKegiatan)
            ->where('kode_kro', $kodeKro)
            ->where('kode_ro', $kodeRo)
            ->where('kode_komponen', $kodeKomponen)
            ->where('kode_subkomponen', $kodeSubkomponen)
            ->exists();
    }

    private function normalizeComparableName(?string $value): string
    {
        $value = trim((string) $value);
        $value = preg_replace('/\s+/u', ' ', $value) ?? $value;
        return mb_strtolower($value, 'UTF-8');
    }

    private function normalizeInput(
        Request $request,
        array $fields,
        array $doNotCollapseWhitespace = []
    ): void {
        $normalized = [];

        foreach ($fields as $field) {
            if (!$request->has($field)) {
                continue;
            }

            $value = $request->input($field);

            if (is_string($value)) {
                $value = trim($value);

                if (
                    str_starts_with($field, 'nama_')
                    && !in_array($field, $doNotCollapseWhitespace, true)
                ) {
                    $value = preg_replace('/\s+/u', ' ', $value) ?? $value;
                }
            }

            $normalized[$field] = $value;
        }

        $request->merge($normalized);
    }

    private function normalizeStringArray(Request $request, string $field): void
    {
        if (!$request->has($field)) {
            return;
        }

        $values = $request->input($field);

        if (!is_array($values)) {
            return;
        }

        $values = array_values(array_unique(array_filter(array_map(
            fn ($value) => is_string($value) ? trim($value) : $value,
            $values
        ), fn ($value) => $value !== null && $value !== '')));

        $request->merge([$field => $values]);
    }

    private function validationFailure(
        Request $request,
        string $field,
        string $message
    ) {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'title' => 'Data Referensi Tidak Dapat Disimpan',
                'message' => $message,
                'errors' => [
                    $field => [$message],
                ],
            ], 422);
        }

        return redirect()
            ->back()
            ->withErrors([$field => $message])
            ->withInput();
    }

    private function successResponse(
        Request $request,
        string $title,
        string $message,
        array $data = []
    ) {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'title' => $title,
                'message' => $message,
                'data' => $data,
            ]);
        }

        return redirect()->back()->with('success', $message);
    }

    private function handleDatabaseException(
        Request $request,
        QueryException $e,
        string $field,
        string $duplicateMessage
    ) {
        $driverCode = (int) ($e->errorInfo[1] ?? 0);

        Log::error('Gagal menyimpan data referensi penganggaran', [
            'message' => $e->getMessage(),
            'sql_state' => $e->errorInfo[0] ?? $e->getCode(),
            'driver_code' => $driverCode,
        ]);

        if ($driverCode === 1062) {
            return $this->validationFailure($request, $field, $duplicateMessage);
        }

        if (in_array($driverCode, [1451, 1452], true)) {
            return $this->validationFailure(
                $request,
                $field,
                'Relasi data referensi tidak valid. Pastikan seluruh parent telah tersedia dan sesuai.'
            );
        }

        return $this->serverFailure(
            $request,
            'Terjadi kesalahan database saat menyimpan data referensi penganggaran.'
        );
    }

    private function handleUnexpectedException(
        Request $request,
        \Throwable $e,
        string $referenceName
    ) {
        Log::error("Gagal menyimpan referensi {$referenceName}", [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ]);

        return $this->serverFailure(
            $request,
            "Terjadi kesalahan internal saat menyimpan referensi {$referenceName}."
        );
    }

    private function serverFailure(Request $request, string $message)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'title' => 'Data Referensi Gagal Disimpan',
                'message' => $message,
            ], 500);
        }

        return redirect()->back()->with('error', $message)->withInput();
    }
}
