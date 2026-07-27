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


Route::get('/login', [usersController::class, 'ShowLogin'])->name('login');
Route::post('/login', [usersController::class, 'login'])->name('login.process');

Route::match(['get', 'post'], '/register/step-1', [usersController::class, 'register_step1'])
    ->name('register.step1');

Route::match(['get', 'post'], '/register/step-2', [usersController::class, 'register_step2'])
    ->name('register.step2');

Route::match(['get', 'post'], '/register/step-3', [usersController::class, 'register_step3'])
    ->name('register.step3');

Route::post('/register/resend-otp', [usersController::class, 'resend_register_otp'])
    ->name('register.resend_otp');

Route::get('/register', function () {
    return redirect()->route('register.step1');
})->name('register');

Route::get('/forgot-password', [usersController::class, 'showForgotPasswordForm'])
    ->name('forgot.password');

Route::post('/forgot-password', [usersController::class, 'sendForgotPasswordOtp'])
    ->name('forgot.password.send');

Route::get('/forgot-password/otp', [usersController::class, 'showForgotPasswordOtpForm'])
    ->name('forgot.password.otp');

Route::post('/forgot-password/otp', [usersController::class, 'verifyForgotPasswordOtp'])
    ->name('forgot.password.otp.verify');

Route::post('/forgot-password/resend-otp', [usersController::class, 'resendForgotPasswordOtp'])
    ->name('forgot.password.otp.resend');

Route::get('/forgot-password/reset', [usersController::class, 'showResetPasswordForm'])
    ->name('forgot.password.reset');

Route::post('/forgot-password/reset', [usersController::class, 'resetForgotPassword'])
    ->name('forgot.password.update');

Route::post('/logout', [usersController::class, 'logout'])->name('logout');
