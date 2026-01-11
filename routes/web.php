<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\{RegisterController, LoginController};
use App\Http\Controllers\{
    DashboardController, TaskController, ScheduleController, 
    MaterialController, GroupController, AnnouncementController
};

// --- A. GUEST ACCESS ---
Route::middleware('guest')->group(function () {
    Route::get('/', fn() => redirect()->route('login'));
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// --- B. AUTHENTICATED ACCESS ---
Route::middleware('auth')->group(function () {
    
    // 1. Dashboard & Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/check-updates', [DashboardController::class, 'checkUpdates'])->name('dashboard.updates');
    Route::get('/dashboard/export', [DashboardController::class, 'exportSummary'])->name('dashboard.export');

    // 2. Fitur Utama (Resource)
    Route::resource('tasks', TaskController::class);
    Route::resource('schedules', ScheduleController::class);
    Route::resource('announcements', AnnouncementController::class);
    Route::resource('groups', GroupController::class);

    // 3. Materials (Khusus Download & View harus di atas Resource jika memakai parameter yang mirip)
    Route::get('/materials/download/{id}', [MaterialController::class, 'download'])->name('materials.download');
    Route::get('/materials/view/{id}', [MaterialController::class, 'viewFile'])->name('materials.view');
    Route::get('/materials-export', [MaterialController::class, 'exportMaterials'])->name('materials.export');
    Route::resource('materials', MaterialController::class);

    // 4. Fitur Groups (Custom Routes)
    Route::prefix('groups-feature')->group(function () {
        // Directory & Export
        Route::get('/directory', [GroupController::class, 'directory'])->name('groups.directory');
        Route::get('/directory/{subject}', [GroupController::class, 'directoryDetail'])->name('groups.directory.detail');
        Route::get('/export', [GroupController::class, 'exportGroups'])->name('groups.export');

        // Progress Management
        Route::get('/progress/{id}', [GroupController::class, 'showProgress'])->name('groups.progress');
        Route::post('/progress/store', [GroupController::class, 'storeProgress'])->name('groups.progress.store');
        Route::put('/progress/update/{id}', [GroupController::class, 'updateProgress'])->name('groups.progress.update');
        Route::delete('/progress/delete/{id}', [GroupController::class, 'destroyProgress'])->name('groups.progress.destroy');

        // Links & Tasks Internal Group
        Route::put('/{group}/links', [GroupController::class, 'updateLinks'])->name('groups.updateLinks');
        Route::post('/{group}/tasks', [GroupController::class, 'storeTask'])->name('groups.tasks.store');
        Route::patch('/tasks/{task}/status', [GroupController::class, 'updateTaskStatus'])->name('groups.tasks.update');
        Route::delete('/tasks/{task}', [GroupController::class, 'destroyTask'])->name('groups.tasks.destroy');
    });

    // Fitur Export tambahan
    Route::get('/tasks-export', [TaskController::class, 'exportTasks'])->name('tasks.export');
});