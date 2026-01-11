<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http; 
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

        // Render view Carousel/Slider
        $announcementHtml = view('partials.announcement_carousel', ['announcements' => $data['announcements']])->render();
        
        $leftHtml = view('partials.dashboard_left', $data)->render();
        $rightHtml = view('partials.dashboard_right', $data)->render();

        return response()->json([
            'announcement_html' => $announcementHtml,
            'left_html' => $leftHtml,
            'right_html' => $rightHtml
        ]);
    }

    public function exportSummary()
    {
        $fileName = 'Ringkasan_Dashboard_' . date('Y-m-d') . '.csv';
        $stats = [
            'Pending Tasks' => Task::where('status', '!=', 'done')->count(),
            'Completed Tasks' => Task::where('status', 'done')->count(),
            'Total Materials' => Material::count(),
        ];
        $pendingTasks = Task::where('status', '!=', 'done')->orderBy('deadline', 'asc')->get();

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() use ($stats, $pendingTasks) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['--- STATISTIK BELAJAR ---']);
            foreach ($stats as $label => $value) fputcsv($file, [$label, $value]);
            fputcsv($file, []); 
            fputcsv($file, ['--- DAFTAR TUGAS ---']);
            fputcsv($file, ['No', 'Mata Kuliah', 'Judul Tugas', 'Deadline', 'Status']);
            foreach ($pendingTasks as $index => $task) {
                fputcsv($file, [$index + 1, $task->course, $task->title, $task->deadline ? $task->deadline->format('d-m-Y') : '-', ucfirst($task->status)]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function getAllDashboardData()
    {
        $user = Auth::user();
        $today = Carbon::now();

        Carbon::setLocale('id');
        $hariIniIndo = $today->translatedFormat('l'); 
        $hariBesokIndo = $today->copy()->addDay()->translatedFormat('l');

        // ========= 2. LOGIKA CUACA (FIX BANDUNG via OPEN-METEO) =========
        $lat = "-6.9175";
        $long = "107.6191";
        $namaKota = "Kota Bandung";

        try {
            $url = "https://api.open-meteo.com/v1/forecast?latitude={$lat}&longitude={$long}&current_weather=true&timezone=Asia%2FBangkok";
            
            // Timeout 3 detik agar loading dashboard tidak lama
            $response = Http::timeout(3)->get($url);

            if ($response->successful()) {
                $data = $response->json();
                $current = $data['current_weather'];

                $cuaca = [
                    'tanggal' => $today->translatedFormat('l, d F Y'),
                    'kota' => $namaKota, 
                    'suhu' => round($current['temperature']), 
                    'deskripsi' => $this->getWmoDescription($current['weathercode'])
                ];
            } else {
                throw new \Exception("Gagal ambil data");
            }

        } catch (\Exception $e) {
            // Fallback
            $cuaca = [
                'tanggal' => $today->translatedFormat('l, d F Y'),
                'kota' => 'Bandung',
                'suhu' => '--',
                'deskripsi' => 'Data tidak tersedia'
            ];
        }
        // ==========================================================

        // 3. AMBIL PENGUMUMAN TERBARU (SLIDER MODE)
        // Ambil 5 data terbaru untuk ditampilkan di carousel
        $announcements = Announcement::latest()->take(5)->get();

        // 4. DATA STATISTIK & JADWAL
        $stats = [
            'pending_tasks' => Task::where('status', '!=', 'done')->count(),
            'completed_tasks' => Task::where('status', 'done')->count(),
            'total_materials' => Material::count(),
        ];
        $todaysSchedules = Schedule::where('day', $hariIniIndo)->orderBy('start_time', 'asc')->get();
        $tomorrowSchedules = Schedule::where('day', $hariBesokIndo)->orderBy('start_time', 'asc')->get();
        $todaysTasks = Task::whereDate('deadline', $today->format('Y-m-d'))->where('status', '!=', 'done')->get();
        $upcomingTasks = Task::whereDate('deadline', '>', $today->format('Y-m-d'))->where('status', '!=', 'done')->orderBy('deadline', 'asc')->take(3)->get();
        $todaysMaterial = Material::with('user')->whereDate('created_at', $today->format('Y-m-d'))->latest()->first();

        return [
            'announcements' => $announcements, 
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

    private function getWmoDescription($code)
    {
        $codes = [
            0 => 'Cerah', 1 => 'Cerah Berawan', 2 => 'Berawan', 3 => 'Mendung',
            45 => 'Kabut', 48 => 'Kabut Tebal', 51 => 'Gerimis Ringan', 53 => 'Gerimis Sedang',
            55 => 'Gerimis Lebat', 61 => 'Hujan Ringan', 63 => 'Hujan Sedang', 65 => 'Hujan Lebat',
            80 => 'Hujan Ringan', 81 => 'Hujan Sedang', 82 => 'Hujan Lebat', 95 => 'Hujan Petir',
            96 => 'Hujan Petir Ringan', 99 => 'Hujan Petir Lebat',
        ];
        return $codes[$code] ?? 'Cerah Berawan';
    }
}