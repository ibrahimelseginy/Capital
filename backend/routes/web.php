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
    Route::post('/projects', [AdminController::class, 'storeProject'])->name('admin.projects.store');
    Route::get('/projects/{id}', [AdminController::class, 'showProject'])->name('admin.projects.show');
    Route::post('/projects/{id}/metrics', [AdminController::class, 'storeProjectMetric'])->name('admin.projects.metrics.store');
    Route::put('/projects/metrics/{id}', [AdminController::class, 'updateProjectMetric'])->name('admin.projects.metrics.update');
    Route::delete('/projects/metrics/{id}', [AdminController::class, 'destroyProjectMetric'])->name('admin.projects.metrics.destroy');

    Route::post('/projects/{id}/consultants', [AdminController::class, 'storeProjectConsultant'])->name('admin.projects.consultants.store');
    Route::put('/projects/consultants/{id}', [AdminController::class, 'updateProjectConsultant'])->name('admin.projects.consultants.update');
    Route::delete('/projects/consultants/{id}', [AdminController::class, 'destroyProjectConsultant'])->name('admin.projects.consultants.destroy');

    Route::post('/projects/{id}/exits', [AdminController::class, 'storeProjectExitRequest'])->name('admin.projects.exits.store');
    Route::put('/projects/exits/{id}', [AdminController::class, 'updateProjectExitRequest'])->name('admin.projects.exits.update');
    Route::delete('/projects/exits/{id}', [AdminController::class, 'destroyProjectExitRequest'])->name('admin.projects.exits.destroy');

    Route::post('/projects/{id}/documents', [AdminController::class, 'storeProjectDocument'])->name('admin.projects.documents.store');
    Route::delete('/projects/documents/{id}', [AdminController::class, 'destroyProjectDocument'])->name('admin.projects.documents.destroy');

    Route::post('/projects/{id}/reports', [AdminController::class, 'storeProjectReport'])->name('admin.projects.reports.store');
    Route::delete('/projects/reports/{id}', [AdminController::class, 'destroyProjectReport'])->name('admin.projects.reports.destroy');

    Route::post('/projects/{id}/team', [AdminController::class, 'storeProjectTeamMember'])->name('admin.projects.team.store');
    Route::delete('/projects/{id}/team/{user_id}', [AdminController::class, 'destroyProjectTeamMember'])->name('admin.projects.team.destroy');
    
    
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::post('/users/{id}', [AdminController::class, 'updateUser'])->name('admin.users.update');

    Route::get('/events', [AdminController::class, 'events'])->name('admin.events');
    Route::post('/events', [AdminController::class, 'storeEvent'])->name('admin.events.store');
    Route::post('/events/{id}', [AdminController::class, 'updateEvent'])->name('admin.events.update');
    Route::delete('/events/{id}', [AdminController::class, 'destroyEvent'])->name('admin.events.destroy');
    Route::post('/projects/{id}/status', [AdminController::class, 'updateProjectStatus'])->name('admin.projects.status');
    Route::post('/projects/{id}/update', [AdminController::class, 'updateProjectDetails'])->name('admin.projects.update');
    Route::delete('/projects/{id}', [AdminController::class, 'destroyProject'])->name('admin.projects.destroy');
    Route::get('/requests', [AdminController::class, 'requests'])->name('admin.requests');
    Route::post('/ndas/{id}/status', [AdminController::class, 'updateNdaStatus'])->name('admin.ndas.status');
    Route::post('/exit-requests/{id}/status', [AdminController::class, 'updateExitStatus'])->name('admin.exits.status');
    Route::get('/files', [AdminController::class, 'files'])->name('admin.files');
    Route::post('/documents', [AdminController::class, 'storeDocument'])->name('admin.documents.store');
    Route::get('/documents/{id}', [AdminController::class, 'showDocument'])->name('admin.documents.show');
    Route::delete('/documents/{id}', [AdminController::class, 'destroyDocument'])->name('admin.documents.destroy');
    Route::post('/reports', [AdminController::class, 'storeReport'])->name('admin.reports.store');
    Route::get('/reports/{id}', [AdminController::class, 'showReport'])->name('admin.reports.show');
    Route::delete('/reports/{id}', [AdminController::class, 'destroyReport'])->name('admin.reports.destroy');
    
    // CMS Routes
    Route::get('/content', [AdminContentController::class, 'index'])->name('admin.content');
    Route::post('/content', [AdminContentController::class, 'store'])->name('admin.content.store');
    Route::post('/content/{id}', [AdminContentController::class, 'update'])->name('admin.content.update');
    Route::delete('/content/{id}', [AdminContentController::class, 'destroy'])->name('admin.content.destroy');
    
    // Website Management Routes
    Route::get('/website', [\App\Http\Controllers\AdminWebsiteController::class, 'index'])->name('admin.website');
    Route::post('/website/articles', [\App\Http\Controllers\AdminWebsiteController::class, 'storeArticle'])->name('admin.website.articles.store');
    Route::post('/website/jobs', [\App\Http\Controllers\AdminWebsiteController::class, 'storeJob'])->name('admin.website.jobs.store');
    Route::post('/website/metrics', [\App\Http\Controllers\AdminWebsiteController::class, 'storeMetric'])->name('admin.website.metrics.store');
    Route::post('/website/testimonials', [\App\Http\Controllers\AdminWebsiteController::class, 'storeTestimonial'])->name('admin.website.testimonials.store');
    Route::delete('/website/{type}/{id}', [\App\Http\Controllers\AdminWebsiteController::class, 'destroy'])->name('admin.website.destroy');
});

// Entrepreneur Routes (Protected)
Route::middleware(['auth', 'role:entrepreneur'])->prefix('entrepreneur')->group(function () {
    Route::get('/', [EntrepreneurController::class, 'index'])->name('entrepreneur.dashboard');
    Route::get('/projects', [EntrepreneurController::class, 'myProjects'])->name('entrepreneur.projects');
    Route::get('/funding', [EntrepreneurController::class, 'funding'])->name('entrepreneur.funding');
});
