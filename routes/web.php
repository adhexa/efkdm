<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GisController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MonevController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - e-FKDM & e-Monev Pemda Application
|--------------------------------------------------------------------------
*/

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/gis', [GisController::class, 'index'])->name('gis');

// Auth routes (Guests only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/register-kabupaten', [AuthController::class, 'showRegisterKabupaten'])->name('register.kabupaten');
    Route::post('/register-kabupaten', [AuthController::class, 'registerKabupaten']);
});

// Protected routes (Authenticated users)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Laporan Deteksi Dini & Detail (Auth Only - Sensitive Data)
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/{id}', [ReportController::class, 'show'])->name('reports.show');
    Route::get('/reports/{id}/print', [ReportController::class, 'print'])->name('reports.print');

    // Pengaturan Akun & Password
    Route::get('/password/change', [\App\Http\Controllers\PasswordController::class, 'edit'])->name('password.edit');
    Route::post('/password/change', [\App\Http\Controllers\PasswordController::class, 'update'])->name('password.update');

    // e-Monev Pemda & Fasilitator Swasta (Petugas Auth Only)
    Route::get('/monev', [MonevController::class, 'index'])->name('monev.index');
    Route::get('/monev/{id}/print-executive', [MonevController::class, 'printExecutive'])->name('monev.print_executive');

    // Laporan Deteksi Dini Officer Operations
    Route::get('/reports-create', [ReportController::class, 'create'])->name('reports.create');
    Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');

    // Vendor Consultant & Admin e-Monev Builder
    Route::middleware('role:vendor_admin,admin')->group(function () {
        Route::get('/monev-builder', [MonevController::class, 'createBuilder'])->name('monev.builder');
        Route::post('/monev-builder', [MonevController::class, 'storeBuilder'])->name('monev.store_builder');
        
        Route::post('/reports/{id}/update-status', [ReportController::class, 'updateStatus'])
            ->name('reports.update_status');
    });

    // Member Management & Approval (Admin Pemda, Vendor, FKDM Kabupaten)
    Route::middleware('role:vendor_admin,admin,fkdm_kabupaten')->group(function () {
        Route::get('/members', [\App\Http\Controllers\MemberManagementController::class, 'index'])->name('members.index');
        Route::post('/members/{id}/approve', [\App\Http\Controllers\MemberManagementController::class, 'approve'])->name('members.approve');
        Route::post('/members/{id}/reject', [\App\Http\Controllers\MemberManagementController::class, 'reject'])->name('members.reject');
    });

    // FKDM Field Officers status update
    Route::post('/reports/{id}/update-status-fkdm', [ReportController::class, 'updateStatus'])
        ->name('reports.update_status_fkdm')
        ->middleware('role:fkdm_member,fkdm_kabupaten');
});
