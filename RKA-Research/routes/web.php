<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\usersController;
use App\Http\Controllers\masterDataController;


Route::get('/', [usersController::class, 'ShowLogin'])->name('login');

Route::get('/login', [usersController::class, 'ShowLogin']);

Route::post('/login-process', [usersController::class, 'login'])->name('login.process');

Route::post('/logout', [usersController::class, 'logout'])->name('logout');

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

Route::get('/Dashboard', [usersController::class, 'ShowDashboard'])->name('dashboard');

Route::controller(MasterDataController::class)
    ->prefix('Upload-Dokumen/Master-Data')
    ->group(function () {
        Route::get('/', 'ShowUploadMasterData')->name('upload.masterdata');
        Route::post('/Store', 'storeMasterData')->name('upload.masterdata.store');
    });
