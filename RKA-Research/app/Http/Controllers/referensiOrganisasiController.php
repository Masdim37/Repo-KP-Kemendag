<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class referensiOrganisasiController extends Controller
{
    public function showUnitEselon1()
    {
        return view('menu.referensi.organisasi.unit-eselon-1');
    }

    public function showUnitEselon2()
    {
        $unitEselon1 = DB::table('unit_eselon_1')
            ->select('kode_unit_eselon1', 'nama_unit_eselon1')
            ->orderBy('kode_unit_eselon1')
            ->get();

        return view(
            'menu.referensi.organisasi.unit-eselon-2',
            compact('unitEselon1')
        );
    }

    public function showSatker()
    {
        $unitEselon1 = DB::table('unit_eselon_1')
            ->select('kode_unit_eselon1', 'nama_unit_eselon1')
            ->orderBy('kode_unit_eselon1')
            ->get();

        $unitEselon2 = DB::table('unit_eselon_2')
            ->select(
                'kode_unit_eselon2',
                'nama_unit_eselon2',
                'kode_unit_eselon1'
            )
            ->orderBy('kode_unit_eselon2')
            ->get();

        return view(
            'menu.referensi.organisasi.satker',
            compact('unitEselon1', 'unitEselon2')
        );
    }

    public function storeUnitEselon1(Request $request)
    {
        $this->normalizeInput($request, [
            'kode_unit_eselon1',
            'nama_unit_eselon1',
        ]);

        $request->validate([
            'kode_unit_eselon1' => 'required|string|max:50',
            'nama_unit_eselon1' => 'required|string|max:255',
        ], [
            'kode_unit_eselon1.required' => 'Kode Unit Eselon I wajib diisi.',
            'nama_unit_eselon1.required' => 'Nama Unit Eselon I wajib diisi.',
        ]);

        $kode = $request->input('kode_unit_eselon1');
        $nama = $request->input('nama_unit_eselon1');

        if (DB::table('unit_eselon_1')->where('kode_unit_eselon1', $kode)->exists()) {
            return $this->validationFailure(
                $request,
                'kode_unit_eselon1',
                "Kode Unit Eselon I {$kode} sudah tersedia dalam data referensi."
            );
        }

        try {
            DB::table('unit_eselon_1')->insert([
                'kode_unit_eselon1' => $kode,
                'nama_unit_eselon1' => $nama,
            ]);

            return $this->successResponse(
                $request,
                'Data Referensi Berhasil Ditambahkan',
                "Unit Eselon I {$kode} - {$nama} berhasil ditambahkan.",
                [
                    'kode_unit_eselon1' => $kode,
                    'nama_unit_eselon1' => $nama,
                ]
            );
        } catch (QueryException $e) {
            return $this->handleDatabaseException(
                $request,
                $e,
                'kode_unit_eselon1',
                "Kode Unit Eselon I {$kode} sudah tersedia dalam data referensi."
            );
        } catch (\Throwable $e) {
            return $this->handleUnexpectedException(
                $request,
                $e,
                'Unit Eselon I'
            );
        }
    }

    public function storeUnitEselon2(Request $request)
    {
        $this->normalizeInput($request, [
            'kode_unit_eselon1',
            'kode_unit_eselon2',
            'nama_unit_eselon2',
        ]);

        $request->validate([
            'kode_unit_eselon1' => 'required|string|max:50',
            'kode_unit_eselon2' => 'required|string|max:50',
            'nama_unit_eselon2' => 'required|string|max:255',
        ], [
            'kode_unit_eselon1.required' => 'Unit Eselon I wajib dipilih.',
            'kode_unit_eselon2.required' => 'Kode Unit Eselon II wajib diisi.',
            'nama_unit_eselon2.required' => 'Nama Unit Eselon II wajib diisi.',
        ]);

        $kodeUnit1 = $request->input('kode_unit_eselon1');
        $kodeUnit2 = $request->input('kode_unit_eselon2');
        $namaUnit2 = $request->input('nama_unit_eselon2');

        if (!DB::table('unit_eselon_1')->where('kode_unit_eselon1', $kodeUnit1)->exists()) {
            return $this->validationFailure(
                $request,
                'kode_unit_eselon1',
                'Unit Eselon I yang dipilih tidak ditemukan.'
            );
        }

        if (DB::table('unit_eselon_2')->where('kode_unit_eselon2', $kodeUnit2)->exists()) {
            return $this->validationFailure(
                $request,
                'kode_unit_eselon2',
                "Kode Unit Eselon II {$kodeUnit2} sudah tersedia dalam data referensi."
            );
        }

        try {
            DB::table('unit_eselon_2')->insert([
                'kode_unit_eselon2' => $kodeUnit2,
                'nama_unit_eselon2' => $namaUnit2,
                'kode_unit_eselon1' => $kodeUnit1,
            ]);

            return $this->successResponse(
                $request,
                'Data Referensi Berhasil Ditambahkan',
                "Unit Eselon II {$kodeUnit2} - {$namaUnit2} berhasil ditambahkan.",
                [
                    'kode_unit_eselon1' => $kodeUnit1,
                    'kode_unit_eselon2' => $kodeUnit2,
                    'nama_unit_eselon2' => $namaUnit2,
                ]
            );
        } catch (QueryException $e) {
            return $this->handleDatabaseException(
                $request,
                $e,
                'kode_unit_eselon2',
                "Kode Unit Eselon II {$kodeUnit2} sudah tersedia dalam data referensi."
            );
        } catch (\Throwable $e) {
            return $this->handleUnexpectedException(
                $request,
                $e,
                'Unit Eselon II'
            );
        }
    }

    public function storeSatker(Request $request)
    {
        $this->normalizeInput($request, [
            'kode_unit_eselon1',
            'kode_unit_eselon2',
            'kode_satker',
            'nama_satker',
        ]);

        $request->validate([
            'kode_unit_eselon1' => 'required|string|max:50',
            'kode_unit_eselon2' => 'required|string|max:50',
            'kode_satker' => 'required|string|max:50',
            'nama_satker' => 'required|string|max:255',
        ], [
            'kode_unit_eselon1.required' => 'Unit Eselon I wajib dipilih.',
            'kode_unit_eselon2.required' => 'Unit Eselon II wajib dipilih.',
            'kode_satker.required' => 'Kode Satker wajib diisi.',
            'nama_satker.required' => 'Nama Satker wajib diisi.',
        ]);

        $kodeUnit1 = $request->input('kode_unit_eselon1');
        $kodeUnit2 = $request->input('kode_unit_eselon2');
        $kodeSatker = $request->input('kode_satker');
        $namaSatker = $request->input('nama_satker');

        if (!DB::table('unit_eselon_1')->where('kode_unit_eselon1', $kodeUnit1)->exists()) {
            return $this->validationFailure(
                $request,
                'kode_unit_eselon1',
                'Unit Eselon I yang dipilih tidak ditemukan.'
            );
        }

        $unit2Valid = DB::table('unit_eselon_2')
            ->where('kode_unit_eselon2', $kodeUnit2)
            ->where('kode_unit_eselon1', $kodeUnit1)
            ->exists();

        if (!$unit2Valid) {
            return $this->validationFailure(
                $request,
                'kode_unit_eselon2',
                'Unit Eselon II yang dipilih tidak sesuai dengan Unit Eselon I.'
            );
        }

        if (DB::table('satker')->where('kode_satker', $kodeSatker)->exists()) {
            return $this->validationFailure(
                $request,
                'kode_satker',
                "Kode Satker {$kodeSatker} sudah tersedia dalam data referensi."
            );
        }

        try {
            DB::table('satker')->insert([
                'kode_satker' => $kodeSatker,
                'nama_satker' => $namaSatker,
                'kode_unit_eselon2' => $kodeUnit2,
            ]);

            return $this->successResponse(
                $request,
                'Data Referensi Berhasil Ditambahkan',
                "Satker {$kodeSatker} - {$namaSatker} berhasil ditambahkan.",
                [
                    'kode_unit_eselon1' => $kodeUnit1,
                    'kode_unit_eselon2' => $kodeUnit2,
                    'kode_satker' => $kodeSatker,
                    'nama_satker' => $namaSatker,
                ]
            );
        } catch (QueryException $e) {
            return $this->handleDatabaseException(
                $request,
                $e,
                'kode_satker',
                "Kode Satker {$kodeSatker} sudah tersedia dalam data referensi."
            );
        } catch (\Throwable $e) {
            return $this->handleUnexpectedException(
                $request,
                $e,
                'Satker'
            );
        }
    }

    private function normalizeInput(Request $request, array $fields): void
    {
        $normalized = [];

        foreach ($fields as $field) {
            if (!$request->has($field)) {
                continue;
            }

            $value = $request->input($field);

            if (is_string($value)) {
                $value = trim($value);

                if (str_starts_with($field, 'nama_')) {
                    $value = preg_replace('/\s+/u', ' ', $value) ?? $value;
                }
            }

            $normalized[$field] = $value;
        }

        $request->merge($normalized);
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

        return redirect()
            ->back()
            ->with('success', $message);
    }

    private function handleDatabaseException(
        Request $request,
        QueryException $e,
        string $field,
        string $duplicateMessage
    ) {
        Log::error('Gagal menyimpan data referensi organisasi', [
            'error' => $e->getMessage(),
            'sql_state' => $e->getCode(),
        ]);

        if ((string) $e->getCode() === '23000') {
            return $this->validationFailure(
                $request,
                $field,
                $duplicateMessage
            );
        }

        return $this->serverFailure(
            $request,
            'Terjadi kesalahan database saat menyimpan data referensi.'
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

        return redirect()
            ->back()
            ->with('error', $message)
            ->withInput();
    }
}
