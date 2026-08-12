<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\RenjaImport;
use App\Imports\RkbmnImport;
use App\Imports\SatkerImport;

class masterDataController extends Controller
{
    public function ShowUploadMasterData()
    {
        return view('menu.upload-dokumen.master-data');
    }

    public function storeMasterData(Request $request)
    {
        // 1. Validasi Dasar (Nullable karena bisa pilih salah satu atau dua-duanya)
        $request->validate([
            'renja_file' => 'nullable|file|max:51200', // Max 50MB
            'renja_name' => 'nullable|string',
            'rkbmn_file' => 'nullable|file|max:51200', // Max 50MB
            'rkbmn_name' => 'nullable|string',
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

    // ========================================================================
    // PRIVATE HELPER FUNCTIONS
    // ========================================================================

    private function processRenja($file, $documentName)
    {
        $nextDocId = $this->generateNextDocumentId();

        // Sanitasi nama file (sesuai kode Anda)
        $cleanFileName = preg_replace('/[^A-Za-z0-9\-\_\.]/', '_', $file->getClientOriginalName());
        $fileName = time() . '_' . $cleanFileName;
        $filePath = $file->storeAs('uploads/renja', $fileName, 'public');

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
        Excel::import(new RenjaImport($nextDocId), $file);

        return $filePath;
    }

    private function processRkbmn($file, $documentName)
    {
        $nextDocId = $this->generateNextDocumentId();

        // Sanitasi nama file (sesuai kode Anda)
        $cleanFileName = preg_replace('/[^A-Za-z0-9\-\_\.]/', '_', $file->getClientOriginalName());
        $fileName = time() . '_' . $cleanFileName;
        $filePath = $file->storeAs('uploads/rkbmn', $fileName, 'public');

        DB::table('file_master')->insert([
            'documentID'    => $nextDocId,
            'document_name' => $documentName,
            'document_type' => 'RKBMN',
            'document_size' => $file->getSize(),
            'file_path'     => $filePath,
            'uploaded_by'   => session('user_id') ?? 'user_dummy',
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        // Menggunakan $file langsung (sesuai kode Anda)
        Excel::import(new RkbmnImport($nextDocId), $file);

        return $filePath;
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




    public function ShowSatker(){
        return view('menu.satker');
    }

    public function importDataSatker(Request $request)
    {
        // Validasi 'required' sudah memastikan file pasti ada
        $request->validate([
            'file_satker' => 'required|mimes:xlsx,xls,csv|max:51200' // Tambahkan max size biar aman (misal 50MB)
        ]);

        $allowedExtensions = ['xlsx', 'xls', 'csv'];

        $extSatker = strtolower($request->file('file_satker')->getClientOriginalExtension());
        if (!in_array($extSatker, $allowedExtensions)) {
            return redirect()->back()
                ->withErrors(['file_satker' => 'The SATKER file field must be a file of type: xlsx, xls, csv.'])
                ->withInput();
        }
        
        $uploadedPaths = []; // Menyimpan path untuk rollback jika terjadi error
        $messages = [];      // Menyimpan pesan sukses
        
        DB::beginTransaction();
        try {
            // Langsung proses karena file pasti ada (berkat validasi 'required' di atas)
            $satkerPath = $this->processSatker($request->file('file_satker'));
            $uploadedPaths[] = $satkerPath;
            $messages[] = 'File Master Satker berhasil diimpor dan disimpan ke database.';
            
            DB::commit();
            return redirect()->back()->with('success', implode(' ', $messages));

        } catch (\Exception $e) {
            DB::rollBack();

            // Hapus file yang terlanjur terupload
            foreach ($uploadedPaths as $path) {
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }

            // TAMPILKAN ERROR MENTAH KE LAYAR (DEBUGGING)
            dd([
                'Pesan_Error' => $e->getMessage(),
                'Baris_Error' => $e->getLine(),
                'File_Error'  => $e->getFile()
            ]);

            // return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    private function processSatker($file)
    {
        // HAPUS generateNextDocumentId() jika tidak dimasukkan ke tabel file_master
        
        // Sanitasi nama file
        $cleanFileName = preg_replace('/[^A-Za-z0-9\-\_\.]/', '_', $file->getClientOriginalName());
        $fileName = time() . '_SATKER_' . $cleanFileName;
        $filePath = $file->storeAs('uploads/satker', $fileName, 'public');

        // Import Excel (Tidak perlu mengirim $nextDocId karena di SatkerImport.php constructornya punya default = null)
        Excel::import(new SatkerImport(), $file);

        return $filePath;
    }


    // public function storeRenja(Request $request)
    // {
    //     // 1. Validasi file dan nama dokumen
    //     $request->validate([
    //         'renja_file' => 'required|file|max:51200', // Max 50MB
    //         'renja_name' => 'required|string',
    //     ]);

    //     $file = $request->file('renja_file');
    //     $renja_name = $request->input('renja_name');

    //     // 2. Validasi Ekstensi Manual
    //     // Mengambil ekstensi file asli dari user dan mencocokkannya
    //     $allowedExtensions = ['xlsx', 'xls', 'csv'];
    //     $extension = strtolower($file->getClientOriginalExtension());

    //     if (!in_array($extension, $allowedExtensions)) {
    //         return redirect()->back()
    //             ->withErrors(['renja_file' => 'The RENJA file field must be a file of type: xlsx, xls, csv.'])
    //             ->withInput();
    //     }

    //     DB::beginTransaction();
    //     try {
    //         // 2. Generate documentID dengan Lock untuk mencegah Race Condition
    //         // lockForUpdate() memastikan proses upload bersamaan akan antre
    //         $lastDoc = DB::table('file_master')->lockForUpdate()->orderBy('documentID', 'desc')->first();

    //         if (!$lastDoc) {
    //             $nextDocId = 'doc00001';
    //         } else {
    //             $lastNumber = (int) substr($lastDoc->documentID, 3);
    //             $nextDocId = 'doc' . str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
    //         }

    //         // 3. Sanitasi nama file dan Simpan ke server
    //         // Menghapus karakter aneh dari nama file asli
    //         $cleanFileName = preg_replace('/[^A-Za-z0-9\-\_\.]/', '_', $file->getClientOriginalName());
    //         $fileName = time() . '_' . $cleanFileName;
    //         $filePath = $file->storeAs('uploads/renja', $fileName, 'public');

    //         // 4. A. Simpan ke tabel file_master
    //         DB::table('file_master')->insert([
    //             'documentID'    => $nextDocId,
    //             'document_name' => $renja_name,
    //             'document_type' => 'RENJA',
    //             'document_size' => $file->getSize(),
    //             'file_path'     => $filePath,
    //             'uploaded_by'   => session('user_id') ?? 'user_dummy',
    //             'created_at'    => now(),
    //             'updated_at'    => now(),
    //         ]);

    //         // 4. B. Proses baca Excel langsung dari object $file bawaan Request
    //         // Ini jauh lebih aman daripada mengandalkan public_path / symlink
    //         Excel::import(new RenjaImport($nextDocId), $file);

    //         DB::commit();
    //         return redirect()->back()->with('success', 'File Renja berhasil diunggah dan disimpan!');
    //     } catch (\Exception $e) {
    //         DB::rollBack();

    //         // Hapus file yang terlanjur terupload jika ada error di database/excel
    //         if (isset($filePath) && Storage::disk('public')->exists($filePath)) {
    //             Storage::disk('public')->delete($filePath);
    //         }

    //         return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    //     }
    // }

    // public function storeRkbmn(Request $request)
    // {
    //     $request->validate([
    //         'rkbmn_file' => 'required|max:51200', // Max 50MB
    //         'rkbmn_name' => 'required|string',
    //     ]);

    //     $file = $request->file('rkbmn_file');
    //     $rkbmn_name = $request->input('rkbmn_name');

    //     // 2. Validasi Ekstensi Manual
    //     // Mengambil ekstensi file asli dari user dan mencocokkannya
    //     $allowedExtensions = ['xlsx', 'xls', 'csv'];
    //     $extension = strtolower($file->getClientOriginalExtension());

    //     if (!in_array($extension, $allowedExtensions)) {
    //         return redirect()->back()
    //             ->withErrors(['rkbmn_file' => 'The RKBMN file field must be a file of type: xlsx, xls, csv.'])
    //             ->withInput();
    //     }

    //     DB::beginTransaction();
    //     try {
    //         // 2. Generate documentID dengan Lock untuk mencegah Race Condition
    //         // lockForUpdate() memastikan proses upload bersamaan akan antre
    //         $lastDoc = DB::table('file_master')->lockForUpdate()->orderBy('documentID', 'desc')->first();

    //         if (!$lastDoc) {
    //             $nextDocId = 'doc00001';
    //         } else {
    //             $lastNumber = (int) substr($lastDoc->documentID, 3);
    //             $nextDocId = 'doc' . str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
    //         }

    //         // 3. Sanitasi nama file dan Simpan ke server
    //         // Menghapus karakter aneh dari nama file asli
    //         $cleanFileName = preg_replace('/[^A-Za-z0-9\-\_\.]/', '_', $file->getClientOriginalName());
    //         $fileName = time() . '_' . $cleanFileName;
    //         $filePath = $file->storeAs('uploads/rkbmn', $fileName, 'public');

    //          // 4. A. Simpan ke tabel file_master
    //         DB::table('file_master')->insert([
    //             'documentID'    => $nextDocId,
    //             'document_name' => $rkbmn_name,
    //             'document_type' => 'RKBMN',
    //             'document_size' => $file->getSize(),
    //             'file_path'     => $filePath,
    //             'uploaded_by'   => session('user_id') ?? 'user_dummy',
    //             'created_at'    => now(),
    //             'updated_at'    => now(),
    //         ]);

    //         // 4. B. Proses baca Excel langsung dari object $file bawaan Request
    //         // Ini jauh lebih aman daripada mengandalkan public_path / symlink
    //         Excel::import(new RkbmnImport($nextDocId), $file);

    //         DB::commit();
    //         return redirect()->back()->with('success', 'File RKBMN berhasil diunggah dan disimpan!');
    //     } catch (\Exception $e) {
    //         DB::rollBack();

    //         // Hapus file yang terlanjur terupload jika ada error di database/excel
    //         if (isset($filePath) && Storage::disk('public')->exists($filePath)) {
    //             Storage::disk('public')->delete($filePath);
    //         }

    //         return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    //     }
    //     // catch (\Exception $e) {
    //     //     DB::rollBack();
    //     //     if (\Illuminate\Support\Facades\Storage::disk('public')->exists($filePath)) {
    //     //         \Illuminate\Support\Facades\Storage::disk('public')->delete($filePath);
    //     //     }
    //     //     return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
    //     // }
    // }
}
