<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Citizen;
use App\Http\Controllers\Lawyer;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Public\PublicController;
use Illuminate\Support\Facades\Route;

//--- Public Routes ---//
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/about', [PublicController::class, 'about'])->name('about');
Route::get('/contact', [PublicController::class, 'contact'])->name('contact');

//--- Auth Routes ---//
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'register'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

//--- Citizen Routes ---//
Route::middleware(['auth', 'role:citizen'])->prefix('citizen')->name('citizen.')->group(function () {
    Route::get('/dashboard', [Citizen\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/my-programs', [Citizen\DashboardController::class, 'myPrograms'])->name('programs');
    Route::get('/profile', [Citizen\DashboardController::class, 'profile'])->name('profile');
    Route::patch('/profile', [Citizen\DashboardController::class, 'updateProfile'])->name('profile.update');
    Route::get('/password', [Citizen\DashboardController::class, 'showChangePassword'])->name('password');
    Route::patch('/password', [Citizen\DashboardController::class, 'updatePassword'])->name('password.update');
});

//--- Lawyer Routes ---//
Route::middleware(['auth', 'role:lawyer'])->prefix('lawyer')->name('lawyer.')->group(function () {
    Route::get('/dashboard', [Lawyer\DashboardController::class, 'index'])->name('dashboard');

    // Module routes — MUST be before Route::resource('programs') to prevent
    // 'reorder' and 'create' being matched as program ID parameters.
    Route::get('/programs/{program}/modules/create', [Lawyer\ModuleController::class, 'create'])->name('modules.create');
    Route::post('/programs/{program}/modules', [Lawyer\ModuleController::class, 'store'])->name('modules.store');
    Route::post('/programs/{program}/modules/reorder', [Lawyer\ModuleController::class, 'reorder'])->name('modules.reorder');
    Route::get('/programs/{program}/modules/{module}/edit', [Lawyer\ModuleController::class, 'edit'])->name('modules.edit');
    Route::patch('/programs/{program}/modules/{module}', [Lawyer\ModuleController::class, 'update'])->name('modules.update');
    Route::delete('/programs/{program}/modules/{module}', [Lawyer\ModuleController::class, 'destroy'])->name('modules.destroy');

    Route::resource('programs', Lawyer\ProgramController::class);
    Route::get('/profile', [Lawyer\ProfileController::class, 'show'])->name('profile');
    Route::patch('/profile', [Lawyer\ProfileController::class, 'update'])->name('profile.update');
    Route::get('/password', [Lawyer\ProfileController::class, 'showChangePassword'])->name('password');
    Route::patch('/password', [Lawyer\ProfileController::class, 'updatePassword'])->name('password.update');
});

//--- Admin Routes ---//
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');
});
