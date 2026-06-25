<?php

use App\Http\Controllers\ProfileController;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\Users\UserIndex;
use App\Livewire\Admin\Users\UserCreate;
use App\Livewire\Admin\Users\UserEdit;
use App\Livewire\Admin\HealthChecks\HealthCheckIndex;
use App\Livewire\Admin\HealthChecks\HealthCheckCreate;
use App\Livewire\Admin\HealthChecks\HealthCheckEdit;
use App\Livewire\Admin\Reports\ReportIndex;
use App\Livewire\Admin\Thresholds\ThresholdIndex;
use App\Livewire\User\Dashboard as UserDashboard;
use App\Livewire\User\HealthCheckHistory;
use App\Livewire\User\HealthCheckDetail;
use Illuminate\Support\Facades\Route;

// Root redirect based on auth state
Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('user.dashboard');
    }
    return redirect()->route('login');
});

// Admin routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', AdminDashboard::class)->name('dashboard');

    // User management
    Route::get('/users', UserIndex::class)->name('users.index');
    Route::get('/users/create', UserCreate::class)->name('users.create');
    Route::get('/users/{user}/edit', UserEdit::class)->name('users.edit');

    // Health checks
    Route::get('/health-checks', HealthCheckIndex::class)->name('health-checks.index');
    Route::get('/health-checks/create', HealthCheckCreate::class)->name('health-checks.create');
    Route::get('/health-checks/{healthCheck}/edit', HealthCheckEdit::class)->name('health-checks.edit');

    // Reports
    Route::get('/reports', ReportIndex::class)->name('reports.index');

    // Thresholds
    Route::get('/thresholds', ThresholdIndex::class)->name('thresholds.index');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// User routes
Route::middleware(['auth', 'role:user'])->prefix('my')->name('user.')->group(function () {
    Route::get('/dashboard', UserDashboard::class)->name('dashboard');
    Route::get('/history', HealthCheckHistory::class)->name('history');
    Route::get('/history/{healthCheck}', HealthCheckDetail::class)->name('health-check.detail');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

require __DIR__.'/auth.php';
