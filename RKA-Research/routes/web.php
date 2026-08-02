<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\usersController;

// Route::get('/', function () {
//     return view('login');
// });

// Route::get('/login', function () {
//     return view('login');
// })->name('login');

// Route::get('/forgot-password', function () {
//     return view('forgot-password');
// })->name('forgot-password');

// Route::get('/verifikasi-otp', function () {
//     return view('verify-otp');
// })->name('otp.verify');

// Route::get('/reset-password', function () {
//     return view('reset-password');
// })->name('reset-password');

// Route::view('/password-success', 'password-success')
//     ->name('password.success');


Route::get('/', [usersController::class, 'ShowLogin'])->name('login');

Route::get('/login', [usersController::class, 'ShowLogin']);

Route::post('/login-process', [usersController::class, 'login'])->name('login.process');

// Route::get('/register', [usersController::class, 'ShowRegister'])->name('register');

// Route::match(['get', 'post'], '/register/step-1', [usersController::class, 'register_step1'])
//     ->name('register.step1');

// Tampilkan Step 1
Route::get('/register', [
    usersController::class,
    'ShowRegister',
])->name('register');

Route::post('/register/step-1', [usersController::class, 'register_step1'])
    ->name('register.step1');

// Route::view('/register', 'register-step1')
// ->name('register');

Route::match(['get', 'post'], '/register/step-2', [usersController::class, 'register_step2'])
    ->name('register.step2');

Route::match(['get', 'post'], '/register/step-3', [usersController::class, 'register_step3'])
    ->name('register.step3');

Route::post('/register/resend-otp', [usersController::class, 'resend_register_otp'])
    ->name('register.resend_otp');

// Route::get('/register', function () {
//     return redirect()->route('register.step1');
// })->name('register');

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
    ->prefix('account')
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

Route::post('/logout', [usersController::class, 'logout'])->name('logout');
