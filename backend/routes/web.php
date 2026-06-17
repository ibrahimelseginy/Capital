<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EntrepreneurController;
use App\Http\Controllers\AdminContentController;
Route::get('/', function () {
    return redirect('/login');
});

Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ar'])) {
        session()->put('locale', $locale);
    }
    return redirect()->back();
})->name('lang.switch');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// API Routes
Route::get('/api/content', [AdminContentController::class, 'apiIndex'])->name('api.content');
Route::get('/api/v1/website/home', [\App\Http\Controllers\Api\WebsiteApiController::class, 'home'])->name('api.website.home');

// Investor Routes (Protected)
Route::middleware(['auth', 'role:investor'])->prefix('dashboard')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/projects', [DashboardController::class, 'projects'])->name('dashboard.projects');
    Route::get('/reports', [DashboardController::class, 'reports'])->name('dashboard.reports');
    Route::get('/documents', [DashboardController::class, 'documents'])->name('dashboard.documents');
    Route::get('/ndas', [DashboardController::class, 'ndas'])->name('dashboard.ndas');
    Route::get('/exit-requests', [DashboardController::class, 'exitRequests'])->name('dashboard.exit-requests');
    Route::get('/exit-records', [DashboardController::class, 'exitRecords'])->name('dashboard.exit-records');
    Route::get('/consultations', [DashboardController::class, 'consultations'])->name('dashboard.consultations');
    Route::get('/events', [DashboardController::class, 'events'])->name('dashboard.events');
    Route::get('/profile', [DashboardController::class, 'profile'])->name('dashboard.profile');
});

// Project Detail Routes (Shared, but usually Investor needs it)
Route::middleware(['auth'])->group(function() {
    Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
    Route::post('/projects/{project}/sign-nda', [ProjectController::class, 'signNda'])->name('projects.sign_nda');
});

// Admin Routes (Protected)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/projects', [AdminController::class, 'projects'])->name('admin.projects');
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::post('/users/{id}', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::post('/projects/{id}/status', [AdminController::class, 'updateProjectStatus'])->name('admin.projects.status');
    Route::post('/projects/{id}/update', [AdminController::class, 'updateProjectDetails'])->name('admin.projects.update');
    Route::delete('/projects/{id}', [AdminController::class, 'destroyProject'])->name('admin.projects.destroy');
    Route::get('/requests', [AdminController::class, 'requests'])->name('admin.requests');
    Route::post('/ndas/{id}/status', [AdminController::class, 'updateNdaStatus'])->name('admin.ndas.status');
    Route::post('/exit-requests/{id}/status', [AdminController::class, 'updateExitStatus'])->name('admin.exits.status');
    Route::get('/files', [AdminController::class, 'files'])->name('admin.files');
    Route::post('/documents', [AdminController::class, 'storeDocument'])->name('admin.documents.store');
    Route::get('/documents/{id}', [AdminController::class, 'showDocument'])->name('admin.documents.show');
    Route::post('/reports', [AdminController::class, 'storeReport'])->name('admin.reports.store');
    Route::get('/reports/{id}', [AdminController::class, 'showReport'])->name('admin.reports.show');
    
    // CMS Routes
    Route::get('/content', [AdminContentController::class, 'index'])->name('admin.content');
    Route::post('/content', [AdminContentController::class, 'store'])->name('admin.content.store');
    Route::post('/content/{id}', [AdminContentController::class, 'update'])->name('admin.content.update');
    Route::delete('/content/{id}', [AdminContentController::class, 'destroy'])->name('admin.content.destroy');
});

// Entrepreneur Routes (Protected)
Route::middleware(['auth', 'role:entrepreneur'])->prefix('entrepreneur')->group(function () {
    Route::get('/', [EntrepreneurController::class, 'index'])->name('entrepreneur.dashboard');
    Route::get('/projects', [EntrepreneurController::class, 'myProjects'])->name('entrepreneur.projects');
    Route::get('/funding', [EntrepreneurController::class, 'funding'])->name('entrepreneur.funding');
});
