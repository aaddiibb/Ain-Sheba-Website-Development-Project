<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Citizen;
use App\Http\Controllers\Lawyer;
use App\Http\Controllers\Admin;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Public\PublicController;
use Illuminate\Support\Facades\Route;

//--- Certificate Public Verification ---//
Route::get('/verify/{code}', [PublicController::class, 'publicVerify'])->name('certificate.verify');

//--- Notification Routes (auth only) ---//
Route::middleware('auth')->group(function () {
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');
});

//--- Public Routes ---//
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/about', [PublicController::class, 'about'])->name('about');
Route::get('/contact', [PublicController::class, 'contact'])->name('contact');
Route::post('/contact', [PublicController::class, 'contactSubmit'])->name('contact.submit');
Route::get('/programs', [PublicController::class, 'programs'])->name('programs.index');
Route::get('/programs/{slug}', [PublicController::class, 'programShow'])->name('programs.show');
Route::get('/lawyers', [PublicController::class, 'lawyers'])->name('lawyers.index');
Route::get('/lawyers/{id}', [PublicController::class, 'lawyerProfile'])->name('lawyers.show');
Route::get('/api/search-suggest', [PublicController::class, 'searchSuggest'])->name('search.suggest');

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

    Route::post('/register-program/{program}', [Citizen\RegistrationController::class, 'store'])->name('register.program');
    Route::get('/learn/{programSlug}/modules/{module}', [Citizen\ModuleController::class, 'show'])->name('module.show');
    Route::post('/modules/{module}/complete', [Citizen\ModuleController::class, 'markComplete'])->name('module.complete');
    Route::get('/assessments/{assessment}', [Citizen\AssessmentController::class, 'show'])->name('assessment.show');
    Route::get('/assessments/{assessment}/take', [Citizen\AssessmentController::class, 'take'])->name('assessment.take');
    Route::post('/assessments/{assessment}/submit', [Citizen\AssessmentController::class, 'submit'])->name('assessment.submit');
    Route::get('/attempts/{attempt}', [Citizen\AssessmentController::class, 'result'])->name('assessment.result');

    Route::get('/consultations', [Citizen\ConsultationController::class, 'index'])->name('consultations.index');
    Route::get('/book-consultation/{lawyer}', [Citizen\ConsultationController::class, 'showBooking'])->name('consultation.book');
    Route::post('/book-consultation/{lawyer}', [Citizen\ConsultationController::class, 'store'])->name('consultation.store');
    Route::patch('/consultations/{consultation}/cancel', [Citizen\ConsultationController::class, 'cancel'])->name('consultation.cancel');

    Route::get('/certificates/{code}', [Citizen\CertificateController::class, 'show'])->name('certificate.show');
    Route::get('/certificates/{code}/download', [Citizen\CertificateController::class, 'download'])->name('certificate.download');
    Route::post('/programs/{program}/feedback', [Citizen\FeedbackController::class, 'store'])->name('feedback.store');
    Route::delete('/feedback/{feedback}', [Citizen\FeedbackController::class, 'destroy'])->name('feedback.destroy');
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

    Route::get('/modules/{module}/assessment/create', [Lawyer\AssessmentController::class, 'create'])->name('assessment.create');
    Route::post('/modules/{module}/assessment', [Lawyer\AssessmentController::class, 'store'])->name('assessment.store');
    Route::get('/modules/{module}/assessment/{assessment}/edit', [Lawyer\AssessmentController::class, 'edit'])->name('assessment.edit');
    Route::patch('/modules/{module}/assessment/{assessment}', [Lawyer\AssessmentController::class, 'update'])->name('assessment.update');
    Route::delete('/modules/{module}/assessment/{assessment}', [Lawyer\AssessmentController::class, 'destroy'])->name('assessment.destroy');

    Route::get('/consultations', [Lawyer\ConsultationController::class, 'index'])->name('consultations.index');
    Route::patch('/consultations/{consultation}/status', [Lawyer\ConsultationController::class, 'updateStatus'])->name('consultations.status');
    Route::get('/availability', [Lawyer\AvailabilityController::class, 'index'])->name('availability.index');
    Route::post('/availability', [Lawyer\AvailabilityController::class, 'store'])->name('availability.store');
    Route::delete('/availability/{availability}', [Lawyer\AvailabilityController::class, 'destroy'])->name('availability.destroy');
});

//--- Admin Routes ---//
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/users', [Admin\UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [Admin\UserController::class, 'show'])->name('users.show');
    Route::patch('/users/{user}/toggle', [Admin\UserController::class, 'toggleActive'])->name('users.toggle');
    Route::delete('/users/{user}', [Admin\UserController::class, 'destroy'])->name('users.destroy');

    Route::get('/programs', [Admin\ProgramController::class, 'index'])->name('programs.index');
    Route::get('/programs/{program}', [Admin\ProgramController::class, 'show'])->name('programs.show');
    Route::patch('/programs/{program}/status', [Admin\ProgramController::class, 'updateStatus'])->name('programs.status');
    Route::delete('/programs/{program}', [Admin\ProgramController::class, 'destroy'])->name('programs.destroy');

    Route::resource('legal-areas', Admin\LegalAreaController::class)->names('legal-areas');

    Route::get('/consultations', [Admin\ConsultationController::class, 'index'])->name('consultations.index');

    Route::get('/messages', [Admin\ContactMessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{message}', [Admin\ContactMessageController::class, 'show'])->name('messages.show');
});
