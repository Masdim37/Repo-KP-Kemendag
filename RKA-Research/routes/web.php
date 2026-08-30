<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\usersController;
use App\Http\Controllers\masterDataController;
use App\Http\Controllers\rkaController;
use App\Http\Controllers\torrabController;
use App\Http\Controllers\penelitianController;
use App\Http\Controllers\referensiOrganisasiController;
use App\Http\Controllers\referensiPenganggaranController;
use App\Http\Controllers\lihatReferensiOrganisasiController;
use App\Http\Controllers\lihatReferensiPenganggaranController;

/*
|--------------------------------------------------------------------------
| ROUTE PUBLIK
|--------------------------------------------------------------------------
|
| Route pada bagian ini harus tetap bisa diakses tanpa login karena dipakai
| untuk proses autentikasi, registrasi, dan pemulihan password.
|
*/

Route::get('/', [usersController::class, 'ShowLogin'])
    ->name('login');

Route::get('/login', [usersController::class, 'ShowLogin']);

Route::post('/login-process', [usersController::class, 'login'])
    ->name('login.process');

Route::controller(usersController::class)
    ->prefix('register')
    ->group(function () {
        Route::get('/', 'ShowRegister')
            ->name('register');

        Route::post('/step-1', 'register_step1')
            ->name('register.step1');

        Route::match(['get', 'post'], '/step-2', 'register_step2')
            ->name('register.step2');

        Route::match(['get', 'post'], '/step-3', 'register_step3')
            ->name('register.step3');

        Route::post('/resend-otp', 'resend_register_otp')
            ->name('register.resend_otp');
    });

Route::controller(usersController::class)
    ->prefix('lupa-password')
    ->group(function () {
        Route::get('/', 'ShowForgotPassword')
            ->name('forgot.password');

        Route::post('/', 'sendForgotPasswordOtp')
            ->middleware('throttle:5,1')
            ->name('forgot.password.send');

        Route::get('/verifikasi-otp', 'showForgotPasswordOtpForm')
            ->name('forgot.password.otp');

        Route::post('/verifikasi-otp', 'verifyForgotPasswordOtp')
            ->middleware('throttle:10,1')
            ->name('forgot.password.verify');

        Route::post('/kirim-ulang-otp', 'resendForgotPasswordOtp')
            ->middleware('throttle:3,1')
            ->name('forgot.password.resend');

        Route::get('/password-baru', 'showResetPasswordForm')
            ->name('forgot.password.reset');

        Route::post('/password-baru', 'resetForgotPassword')
            ->middleware('throttle:5,1')
            ->name('forgot.password.update');

        Route::get('/berhasil', 'showForgotPasswordSuccess')
            ->name('forgot.password.success');
    });

/*
|--------------------------------------------------------------------------
| ROUTE TERPROTEKSI
|--------------------------------------------------------------------------
|
| Semua halaman dan aksi internal aplikasi wajib melewati middleware auth.
| User yang belum login akan diarahkan ke route bernama "login".
|
*/

Route::middleware('auth')->group(function () {

    Route::post('/logout', [usersController::class, 'logout'])
        ->name('logout');

    Route::get('/Dashboard', [usersController::class, 'ShowDashboard'])
        ->name('dashboard');

    Route::controller(usersController::class)
        ->prefix('Account')
        ->group(function () {
            Route::get('/', 'showAccount')
                ->name('account.show');

            Route::put('/', 'updateAccount')
                ->middleware('throttle:10,1')
                ->name('account.update');

            Route::delete('/', 'deleteAccount')
                ->middleware('throttle:3,1')
                ->name('account.destroy');
        });

    Route::controller(penelitianController::class)
        ->prefix('Penelitian')
        ->group(function () {
            Route::get('/', 'index')
                ->name('penelitian.index');

            Route::get('/Baru', 'create')
                ->name('penelitian.create');

            Route::post('/', 'store')
                ->name('penelitian.store');

            Route::get('/Dokumen', 'documentOptions')
                ->name('penelitian.documents');

            Route::get('/{penelitianID}/Edit', 'edit')
                ->whereNumber('penelitianID')
                ->name('penelitian.edit');

            Route::put('/{penelitianID}', 'update')
                ->whereNumber('penelitianID')
                ->name('penelitian.update');

            Route::post('/{penelitianID}/Jalankan-A', 'runPartA')
                ->whereNumber('penelitianID')
                ->name('penelitian.run-a');


            Route::post('/{penelitianID}/Jalankan-B', 'runPartB')
                ->whereNumber('penelitianID')
                ->name('penelitian.run-b');


            Route::post('/{penelitianID}/Jalankan-C', 'runPartC')
                ->whereNumber('penelitianID')
                ->name('penelitian.run-c');


            Route::post('/{penelitianID}/Jalankan-D', 'runPartD')
                ->whereNumber('penelitianID')
                ->name('penelitian.run-d');


            Route::post('/{penelitianID}/Jalankan-D1', 'runPartD1')
                ->whereNumber('penelitianID')
                ->name('penelitian.run-d1');


            Route::post('/{penelitianID}/Jalankan-D2', 'runPartD2')
                ->whereNumber('penelitianID')
                ->name('penelitian.run-d2');


            Route::post('/{penelitianID}/Jalankan-E', 'runPartE')
                ->whereNumber('penelitianID')
                ->name('penelitian.run-e');


            Route::post('/{penelitianID}/Jalankan-F', 'runPartF')
                ->whereNumber('penelitianID')
                ->name('penelitian.run-f');


            Route::get('/{penelitianID}/CHP', 'showChp')
                ->whereNumber('penelitianID')
                ->name('penelitian.chp');

            Route::get('/{penelitianID}/CHP/Cetak', 'printChp')
                ->whereNumber('penelitianID')
                ->name('penelitian.chp.print');

            Route::post('/{penelitianID}/CHP/Catat-Cetak', 'logPrintChp')
                ->whereNumber('penelitianID')
                ->name('penelitian.chp.print-log');
        });

    Route::controller(masterDataController::class)
        ->prefix('Upload-Dokumen/Master-Data')
        ->group(function () {
            Route::get('/', 'ShowUploadMasterData')
                ->name('upload.masterdata');

            Route::post('/Store', 'storeMasterData')
                ->name('upload.masterdata.store');
        });

    Route::controller(rkaController::class)
        ->prefix('Upload-Dokumen/RKA')
        ->group(function () {
            Route::get('/', 'ShowUploadRka')
                ->name('upload.rka');

            Route::post('/Store', 'storeRka')
                ->name('upload.rka.store');
        });

    Route::controller(torrabController::class)
        ->prefix('Upload-Dokumen/TOR-RAB')
        ->group(function () {
            Route::get('/', 'ShowUploadTorRab')
                ->name('upload.torrab');

            Route::post('/Store', 'storeTorRab')
                ->name('upload.torrab.store');
        });

    Route::controller(referensiOrganisasiController::class)
        ->prefix('Data-Referensi/Organisasi')
        ->group(function () {
            Route::get('/Unit-Eselon-I', 'showUnitEselon1')
                ->name('referensi.organisasi.unit1');

            Route::post('/Unit-Eselon-I/Store', 'storeUnitEselon1')
                ->name('referensi.organisasi.unit1.store');

            Route::get('/Unit-Eselon-II', 'showUnitEselon2')
                ->name('referensi.organisasi.unit2');

            Route::post('/Unit-Eselon-II/Store', 'storeUnitEselon2')
                ->name('referensi.organisasi.unit2.store');

            Route::get('/Satker', 'showSatker')
                ->name('referensi.organisasi.satker');

            Route::post('/Satker/Store', 'storeSatker')
                ->name('referensi.organisasi.satker.store');
        });

    Route::controller(referensiPenganggaranController::class)
        ->prefix('Data-Referensi/Penganggaran')
        ->group(function () {
            Route::get('/Program', 'showProgram')
                ->name('referensi.penganggaran.program');

            Route::post('/Program/Store', 'storeProgram')
                ->name('referensi.penganggaran.program.store');

            Route::get('/Kegiatan', 'showKegiatan')
                ->name('referensi.penganggaran.kegiatan');

            Route::post('/Kegiatan/Store', 'storeKegiatan')
                ->name('referensi.penganggaran.kegiatan.store');

            Route::get('/KRO', 'showKro')
                ->name('referensi.penganggaran.kro');

            Route::post('/KRO/Store', 'storeKro')
                ->name('referensi.penganggaran.kro.store');

            Route::get('/RO', 'showRo')
                ->name('referensi.penganggaran.ro');

            Route::post('/RO/Store', 'storeRo')
                ->name('referensi.penganggaran.ro.store');

            Route::get('/Komponen', 'showKomponen')
                ->name('referensi.penganggaran.komponen');

            Route::post('/Komponen/Store', 'storeKomponen')
                ->name('referensi.penganggaran.komponen.store');

            Route::get('/Subkomponen', 'showSubkomponen')
                ->name('referensi.penganggaran.subkomponen');

            Route::post('/Subkomponen/Store', 'storeSubkomponen')
                ->name('referensi.penganggaran.subkomponen.store');

            Route::get('/Akun', 'showAkun')
                ->name('referensi.penganggaran.akun');

            Route::post('/Akun/Store', 'storeAkun')
                ->name('referensi.penganggaran.akun.store');
        });

    Route::get(
        '/Lihat-Data-Referensi/Organisasi',
        [lihatReferensiOrganisasiController::class, 'index']
    )->name('referensi.lihat.organisasi');

    Route::get(
        '/Lihat-Data-Referensi/Penganggaran',
        [lihatReferensiPenganggaranController::class, 'index']
    )->name('referensi.lihat.penganggaran');

    /*
     * Route legacy/pendukung Satker juga harus dilindungi karena membaca
     * dan menulis data internal.
     */
    Route::get('/satker', [masterDataController::class, 'ShowSatker']);

    Route::post(
        '/satker-store',
        [masterDataController::class, 'importDataSatker']
    )->name('upload.satker.store');
});
