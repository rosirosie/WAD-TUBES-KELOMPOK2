<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\AnnouncementController;

// A. Redirect Awal
Route::get('/', function () {
    return redirect()->route('login');
});

// B. GRUP GUEST
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// C. GRUP AUTH
Route::middleware('auth')->group(function () {
    
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/check-updates', [DashboardController::class, 'checkUpdates'])->name('dashboard.updates');

    // Fitur Utama
    Route::resource('tasks', TaskController::class);
    Route::resource('schedules', ScheduleController::class);
    Route::resource('materials', MaterialController::class);
    Route::resource('announcements', AnnouncementController::class);
    
    
    // --- FITUR GROUPS (SINKRONISASI TOTAL) ---
    
    Route::resource('groups', GroupController::class);

    // a. Master Directory 
    // PERBAIKAN: Menambahkan rute 'groups.directory' agar tidak error di Blade
    Route::get('/groups-directory', [GroupController::class, 'directory'])
         ->name('groups.directory');

    // b. Master Directory Detail (Folder Mata Kuliah)
    Route::get('/groups-directory/{subject}', [GroupController::class, 'directoryDetail'])
         ->name('groups.directory.detail');

    // c. Project Progress Detail
    Route::get('/groups/progress/{id}', [GroupController::class, 'showProgress'])
         ->name('groups.progress.detail');

    // d. Fitur Kelompok Lainnya
    Route::put('/groups/{group}/links', [GroupController::class, 'updateLinks'])->name('groups.updateLinks');
    Route::post('/groups/{group}/tasks', [GroupController::class, 'storeTask'])->name('groups.tasks.store');
    Route::patch('/groups/tasks/{task}/status', [GroupController::class, 'updateTaskStatus'])->name('groups.tasks.update');
    Route::delete('/groups/tasks/{task}', [GroupController::class, 'destroyTask'])->name('groups.tasks.destroy');
});