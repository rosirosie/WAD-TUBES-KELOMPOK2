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

    // --- FITUR EXPORT ---
    Route::get('/dashboard/export', [DashboardController::class, 'exportSummary'])->name('dashboard.export');
    Route::get('/tasks-export', [TaskController::class, 'exportTasks'])->name('tasks.export');
    Route::get('/materials-export', [MaterialController::class, 'exportMaterials'])->name('materials.export');
    Route::get('/groups/export', [GroupController::class, 'exportGroups'])->name('groups.export');

    // --- FITUR UTAMA ---
    Route::resource('tasks', TaskController::class);
    Route::resource('schedules', ScheduleController::class);
    Route::resource('materials', MaterialController::class);
    Route::resource('announcements', AnnouncementController::class);
    Route::resource('groups', GroupController::class);
    
    // --- FITUR GROUPS  ---
    
    // a. Master Directory 
    Route::get('/groups-directory', [GroupController::class, 'directory'])
         ->name('groups.directory');

    // b. Master Directory Detail (Folder Mata Kuliah)
    Route::get('/groups-directory/{subject}', [GroupController::class, 'directoryDetail'])
         ->name('groups.directory.detail');
         Route::delete('/groups/delete/{id}', [GroupController::class, 'destroyGroup'])->name('groups.destroy');
    
    // Route untuk Update Data Kelompok
Route::put('/groups/update/{id}', [GroupController::class, 'updateGroup'])->name('groups.update');


    // c. Project Progress Detail

// 1. Route untuk melihat Progress
Route::get('/groups/progress/{id}', [GroupController::class, 'showProgress'])->name('groups.progress');

// 2. Route untuk menyimpan Progress 
Route::post('/groups/progress/store', [GroupController::class, 'storeProgress'])->name('groups.progress.store');

// 3. Route untuk HAPUS (Delete)
Route::delete('/groups/delete/{id}', [GroupController::class, 'destroyGroup'])->name('groups.destroy');
Route::delete('/groups/progress/delete/{id}', [GroupController::class, 'destroyProgress'])->name('groups.progress.destroy');

// Route untuk Update/Edit Progress
Route::put('/groups/progress/update/{id}', [GroupController::class, 'updateProgress'])->name('groups.progress.update');

    // d. Fitur Kelompok Lainnya
    Route::put('/groups/{group}/links', [GroupController::class, 'updateLinks'])->name('groups.updateLinks');
    Route::post('/groups/{group}/tasks', [GroupController::class, 'storeTask'])->name('groups.tasks.store');
    Route::patch('/groups/tasks/{task}/status', [GroupController::class, 'updateTaskStatus'])->name('groups.tasks.update');
    Route::delete('/groups/tasks/{task}', [GroupController::class, 'destroyTask'])->name('groups.tasks.destroy');

    
});