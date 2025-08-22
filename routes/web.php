<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth/login');
})->name('login');

Route::get('/register', function () {
    return view('auth/register');
})->name('register');

Route::get('/forgot', function () {
    return view('auth/password/forgot');
})->name('forgot');

Route::get('/reset', function () {
    return view('auth/password/reset');
})->name('reset');

Route::get('/app', function () {
    return view('layouts/app');
})->name('app');

Route::get('/dashboard', function () {
    return view('pages/dashboard');
})->name('dashboard');

Route::get('/task', function () {
    return view('pages/task');
})->name('task');

Route::get('/activiti', function () {
    return view('pages/activiti');
})->name('activiti');
