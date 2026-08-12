<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class rkaController extends Controller
{
    public function ShowUploadRka()
    {
        return view('menu.upload-dokumen.rka');
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
        // 1. Validasi Dasar (Nullable karena bisa pilih salah satu atau dua-duanya)
        $request->validate([
            'rka_file' => 'nullable|file|max:51200', // Max 50MB
            'rka_name' => 'nullable|string',
            ''
        ]);

        // Pastikan minimal ada 1 file yang diupload
        if (!$request->hasFile('renja_file') && !$request->hasFile('rkbmn_file')) {
            return redirect()->back()->with('error', 'Silakan pilih setidaknya satu file untuk diunggah (Renja atau RKBMN).');
        }

        // 2. Validasi Ekstensi Manual & Ketersediaan Nama Dokumen
        $allowedExtensions = ['xlsx', 'xls', 'csv'];

        if ($request->hasFile('renja_file')) {
            $extRenja = strtolower($request->file('renja_file')->getClientOriginalExtension());
            if (!in_array($extRenja, $allowedExtensions)) {
                return redirect()->back()->withErrors(['renja_file' => 'The RENJA file field must be a file of type: xlsx, xls, csv.'])->withInput();
            }
            if (!$request->filled('renja_name')) {
                return redirect()->back()->withErrors(['renja_name' => 'The RENJA name field is required when file is uploaded.'])->withInput();
            }
        }

        if ($request->hasFile('rkbmn_file')) {
            $extRkbmn = strtolower($request->file('rkbmn_file')->getClientOriginalExtension());
            if (!in_array($extRkbmn, $allowedExtensions)) {
                return redirect()->back()->withErrors(['rkbmn_file' => 'The RKBMN file field must be a file of type: xlsx, xls, csv.'])->withInput();
            }
            if (!$request->filled('rkbmn_name')) {
                return redirect()->back()->withErrors(['rkbmn_name' => 'The RKBMN name field is required when file is uploaded.'])->withInput();
            }
        }

        $uploadedPaths = []; // Menyimpan path untuk rollback jika terjadi error
        $messages = [];      // Menyimpan pesan sukses

        // 3. Mulai Transaksi Database
        DB::beginTransaction();
        try {
            // A. Proses Renja jika ada
            if ($request->hasFile('renja_file')) {
                $renjaPath = $this->processRenja($request->file('renja_file'), $request->input('renja_name'));
                $uploadedPaths[] = $renjaPath;
                $messages[] = 'File Renja berhasil diunggah.';
            }

            // B. Proses RKBMN jika ada
            if ($request->hasFile('rkbmn_file')) {
                $rkbmnPath = $this->processRkbmn($request->file('rkbmn_file'), $request->input('rkbmn_name'));
                $uploadedPaths[] = $rkbmnPath;
                $messages[] = 'File RKBMN berhasil diunggah.';
            }

            // Commit dan kembalikan pesan sukses
            DB::commit();
            return redirect()->back()->with('success', implode(' ', $messages));
        } catch (\Exception $e) {
            DB::rollBack();

            // Hapus file yang terlanjur terupload jika ada error di database/excel
            foreach ($uploadedPaths as $path) {
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }

            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    private function processRenja($file, $documentName)
    {
        $nextDocId = $this->generateNextDocumentId();

        // Sanitasi nama file (sesuai kode Anda)
        $cleanFileName = preg_replace('/[^A-Za-z0-9\-\_\.]/', '_', $file->getClientOriginalName());
        $fileName = time() . '_' . $cleanFileName;
        $filePath = $file->storeAs('uploads/rka', $fileName, 'public');

        DB::table('file_master')->insert([
            'documentID'    => $nextDocId,
            'document_name' => $documentName,
            'document_type' => 'RENJA',
            'document_size' => $file->getSize(),
            'file_path'     => $filePath,
            'uploaded_by'   => session('user_id') ?? 'user_dummy',
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        // Menggunakan $file langsung (sesuai kode Anda)
        // Excel::import(new RenjaImport($nextDocId), $file);

        return $filePath;
    }
}
