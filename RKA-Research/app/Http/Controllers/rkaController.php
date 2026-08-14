<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class rkaController extends Controller
{
    public function ShowUploadRka()
    {
        // 1. Ambil semua data Eselon 1
        $unitEselon1 = DB::table('unit_eselon_1')->get();

        // 2. Ambil semua data Eselon 2
        $unitEselon2 = DB::table('unit_eselon_2')->get();

        // 3. Ambil data Satker unik beserta relasi eselon 2
        $satker = DB::table('satker')
            ->select('kode_satker', 'nama_satker', 'kode_unit_eselon2')
            ->distinct()
            ->get();

        return view('menu.upload-dokumen.rka', compact('unitEselon1', 'unitEselon2', 'satker'));
    }

    private function generateNextDocumentId()
    {
        // Fitur keamanan lockForUpdate (sesuai kode Anda)
        $lastDoc = DB::table('file_master')->lockForUpdate()->orderBy('documentID', 'desc')->first();

        if (!$lastDoc) {
            return 'doc00001';
        }

        $lastNumber = (int) substr($lastDoc->documentID, 3);
        return 'doc' . str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
    }

    public function storeRKA(Request $request)
    {
        // 1. Validasi Input (Ubah mimes kembali menjadi format Excel)
        $request->validate([
            'rka_file'          => 'required|file|mimes:xlsx,xls,csv|max:51200', // Max 50MB
            'rka_name'          => 'required|string',
            'kode_unit_eselon1' => 'required|string',
            'kode_unit_eselon2' => 'required|string',
            'kode_satker'       => 'required|string',
        ]);

        // 2. Ambil NAMA Unit & Satker dari database berdasarkan KODE yang dipilih user
        $namaEselon1 = DB::table('unit_eselon_1')
            ->where('kode_unit_eselon1', $request->kode_unit_eselon1)
            ->value('nama_unit_eselon1') ?? '-';

        $namaEselon2 = DB::table('unit_eselon_2')
            ->where('kode_unit_eselon2', $request->kode_unit_eselon2)
            ->value('nama_unit_eselon2') ?? '-';

        $namaSatker = DB::table('satker')
            ->where('kode_satker', $request->kode_satker)
            ->value('nama_satker') ?? '-';

        // 3. Siapkan Array Data Organisasi lengkap dengan Namanya
        $dataOrganisasi = [
            'kode_unit_eselon1' => $request->kode_unit_eselon1,
            'nama_unit_eselon1' => $namaEselon1,
            'kode_unit_eselon2' => $request->kode_unit_eselon2,
            'nama_unit_eselon2' => $namaEselon2,
            'kode_satker'       => $request->kode_satker,
            'nama_satker'       => $namaSatker,
        ];

        $uploadedPaths = []; 
        $messages = [];      

        // 4. Mulai Transaksi Database
        DB::beginTransaction();
        try {
            // Proses Upload dan Excel Parsing
            $rkaPath = $this->processRKA($request->file('rka_file'), $request->input('rka_name'), $dataOrganisasi);
            
            $uploadedPaths[] = $rkaPath;
            $messages[] = 'Dokumen RKA Excel berhasil diunggah dan diproses ke dalam sistem.';

            // Jika semua lancar, Commit
            DB::commit();
            return redirect()->back()->with('success', implode(' ', $messages));

        } catch (\Exception $e) {
            DB::rollBack();

            // Tampilkan error mentah saat fase development (Hapus `dd` ini saat production nanti)
            dd([
                'STATUS'      => 'GAGAL IMPORT EXCEL RKA',
                'PESAN_ERROR' => $e->getMessage(),
                'BARIS_ERROR' => $e->getLine(),
                'FILE_ERROR'  => $e->getFile()
            ]);

            // Hapus file Excel fisik yang terlanjur terupload jika gagal import
            foreach ($uploadedPaths as $path) {
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }

            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


        // } catch (\Exception $e) {
        //     DB::rollBack();

        //     // Hapus file yang terlanjur terupload jika ada error di database/parser PDF
        //     foreach ($uploadedPaths as $path) {
        //         if (Storage::disk('public')->exists($path)) {
        //             Storage::disk('public')->delete($path);
        //         }
        //     }

        //     return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        // }


    // ========================================================================
    // PRIVATE HELPER FUNCTION UNTUK RKA
    // ========================================================================


    private function processRKA($file, $documentName, $dataOrganisasi)
    {
        // 1. Generate ID (Pastikan function generateNextDocumentId sudah ada di Controller Anda)
        $nextDocId = $this->generateNextDocumentId();

        // 2. Sanitasi nama file dan simpan ke Storage (diubah penamaannya agar aman)
        $cleanFileName = preg_replace('/[^A-Za-z0-9\-\_\.]/', '_', $file->getClientOriginalName());
        $fileName = time() . '_RKA_' . $cleanFileName;
        $filePath = $file->storeAs('uploads/rka', $fileName, 'public');

        // 3. Catat di tabel file_master
        DB::table('file_master')->insert([
            'documentID'    => $nextDocId,
            'document_name' => $documentName,
            'document_type' => 'RKA',
            'document_size' => $file->getSize(),
            'file_path'     => $filePath,
            'uploaded_by'   => session('user_id') ?? 'user_dummy',
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        // 4. Panggil library Maatwebsite Laravel-Excel 
        // Mengirimkan documentID dan $dataOrganisasi ke class Import
        Excel::import(new \App\Imports\RKAImport($nextDocId, $dataOrganisasi), $file);

        return $filePath;
    }
}
