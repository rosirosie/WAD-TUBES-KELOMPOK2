<?php

use Illuminate\Support\Facades\Route;

// --- 1. Import Controller Auth (Login/Register) ---
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;

// --- 2. Import Controller Fitur Utama ---
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\AnnouncementController;

/*
|--------------------------------------------------------------------------
| Web Routes (Jalur URL Website)
|--------------------------------------------------------------------------
*/

// A. Redirect Halaman Awal
// Jika buka 'localhost:8000', cek dulu: kalau belum login lempar ke Login page.
Route::get('/', function () {
    return redirect()->route('login');
});

// B. GRUP GUEST (Hanya bisa diakses jika BELUM Login)
Route::middleware('guest')->group(function () {
    // Register
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    // Login
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// C. GRUP AUTH (Hanya bisa diakses jika SUDAH Login)
// Kita bungkus pakai middleware 'auth' supaya aman.
Route::middleware('auth')->group(function () {
    
    // 1. Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // 2. Dashboard
    // Di controller ini nanti kita pasang logika tarik data API Cuaca & Quotes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 3. Fitur Utama (Resource)
    // Otomatis membuat 7 jalur (Index, Create, Store, Show, Edit, Update, Destroy)
    Route::resource('tasks', TaskController::class);
    Route::resource('schedules', ScheduleController::class);
    Route::resource('materials', MaterialController::class);
    Route::resource('groups', GroupController::class);
    Route::resource('announcements', AnnouncementController::class);

});