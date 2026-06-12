<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Citizen;
use App\Http\Controllers\Lawyer;
use App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('public.home_placeholder');
})->name('home');

//--- Auth Routes ---//
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'register'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

//--- Citizen Routes ---//
Route::middleware(['auth', 'role:citizen'])->prefix('citizen')->name('citizen.')->group(function () {
    Route::get('/dashboard', [Citizen\DashboardController::class, 'index'])->name('dashboard');
});

//--- Lawyer Routes ---//
Route::middleware(['auth', 'role:lawyer'])->prefix('lawyer')->name('lawyer.')->group(function () {
    Route::get('/dashboard', [Lawyer\DashboardController::class, 'index'])->name('dashboard');
});

//--- Admin Routes ---//
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');
});
