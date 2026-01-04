<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon; 
use Illuminate\Support\Facades\Auth;
use App\Models\Announcement;
use App\Models\Schedule;
use App\Models\Task;
use App\Models\Material; 

class DashboardController extends Controller
{
    public function index()
    {
        $data = $this->getAllDashboardData();
        return view('dashboard', $data);
    }

    public function checkUpdates()
    {
        $data = $this->getAllDashboardData();

        $announcementHtml = view('partials.announcement_widget', ['announcement' => $data['announcement']])->render();
        $leftHtml = view('partials.dashboard_left', $data)->render();
        $rightHtml = view('partials.dashboard_right', $data)->render();

        return response()->json([
            'announcement_html' => $announcementHtml,
            'left_html' => $leftHtml,
            'right_html' => $rightHtml
        ]);
    }

    private function getAllDashboardData()
    {
        $user = Auth::user();
        $today = Carbon::now();

        // 1. SET LOCALE & TANGGAL
        Carbon::setLocale('id');
        $hariIniIndo = $today->translatedFormat('l'); 
        $hariBesokIndo = $today->copy()->addDay()->translatedFormat('l');

        // 2. LOGIKA CUACA
        $cuaca = [
            'tanggal' => $today->translatedFormat('l, d F Y'),
            'kota' => 'Bandung',
            'suhu' => '24',
            'deskripsi' => 'Cerah Berawan'
        ];

        // 3. AMBIL PENGUMUMAN TERBARU
        $announcement = Announcement::latest()->first();

        // A. Statistik Ringkas
        // PERBAIKAN: Hapus filter user_id karena tugas sekarang bersifat sekelas
        $stats = [
            'pending_tasks' => Task::where('status', '!=', 'done')->count(),
            'completed_tasks' => Task::where('status', 'done')->count(),
            'total_materials' => Material::count(),
        ];

        // B. Jadwal Hari Ini
        // PERBAIKAN: Jadwal diambil secara umum untuk satu kelas
        $todaysSchedules = Schedule::where('day', $hariIniIndo)
                                ->orderBy('start_time', 'asc')
                                ->get();

        // C. Jadwal Besok
        $tomorrowSchedules = Schedule::where('day', $hariBesokIndo)
                                    ->orderBy('start_time', 'asc')
                                    ->get();

        // D. Tugas (Deadline Hari Ini)
        // PERBAIKAN: Hapus filter user_id
        $todaysTasks = Task::whereDate('deadline', $today->format('Y-m-d'))
                        ->where('status', '!=', 'done') 
                        ->get();

        // E. Tugas Mendatang (3 item)
        $upcomingTasks = Task::whereDate('deadline', '>', $today->format('Y-m-d'))
                            ->where('status', '!=', 'done')
                            ->orderBy('deadline', 'asc')
                            ->take(3)
                            ->get();

        // F. Material Terbaru
        $todaysMaterial = Material::with('user')
                                ->whereDate('created_at', $today->format('Y-m-d'))
                                ->latest()
                                ->first();

        return [
            'announcement' => $announcement,
            'cuaca' => $cuaca,
            'stats' => $stats,
            'todaysSchedules' => $todaysSchedules,
            'tomorrowSchedules' => $tomorrowSchedules,
            'todaysTasks' => $todaysTasks,
            'upcomingTasks' => $upcomingTasks,
            'todaysMaterial' => $todaysMaterial,
            'hariBesokIndo' => $hariBesokIndo,
            'user' => $user,
            'today' => $today,
        ];
    }
}