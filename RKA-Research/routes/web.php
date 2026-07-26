<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login');
});

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/forgot-password', function () {
    return view('forgot-password');
})->name('forgot-password');

Route::get('/verifikasi-otp', function () {
    return view('verify-otp');
})->name('otp.verify');

Route::get('/reset-password', function () {
    return view('reset-password');
})->name('reset-password');

Route::view('/password-success', 'password-success')
    ->name('password.success');