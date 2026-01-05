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

        $announcementHtml = view('partials.announcement_widget', ['announcement' => $data['announcement']])->render();
        $leftHtml = view('partials.dashboard_left', $data)->render();
        $rightHtml = view('partials.dashboard_right', $data)->render();

        return response()->json([
            'announcement_html' => $announcementHtml,
            'left_html' => $leftHtml,
            'right_html' => $rightHtml
        ]);
    }

    /**
     * FUNGSI EXPORT (TAMBAHAN BARU)
     * Menghasilkan ringkasan Dashboard dalam format CSV
     */
    public function exportSummary()
    {
        $fileName = 'Ringkasan_Dashboard_' . date('Y-m-d') . '.csv';
        
        // Ambil data untuk ringkasan
        $stats = [
            'Pending Tasks' => Task::where('status', '!=', 'done')->count(),
            'Completed Tasks' => Task::where('status', 'done')->count(),
            'Total Materials' => Material::count(),
        ];
        
        $pendingTasks = Task::where('status', '!=', 'done')->orderBy('deadline', 'asc')->get();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($stats, $pendingTasks) {
            $file = fopen('php://output', 'w');

            // 1. Bagian Statistik
            fputcsv($file, ['--- STATISTIK BELAJAR ---']);
            foreach ($stats as $label => $value) {
                fputcsv($file, [$label, $value]);
            }

            fputcsv($file, []); // Baris Kosong

            // 2. Bagian Daftar Tugas Pending
            fputcsv($file, ['--- DAFTAR TUGAS (PENDING/PROGRESS) ---']);
            fputcsv($file, ['No', 'Mata Kuliah', 'Judul Tugas', 'Deadline', 'Status']);

            foreach ($pendingTasks as $index => $task) {
                fputcsv($file, [
                    $index + 1,
                    $task->course,
                    $task->title,
                    $task->deadline ? $task->deadline->format('d-m-Y') : '-',
                    ucfirst($task->status)
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function getAllDashboardData()
    {
        $user = Auth::user();
        $today = Carbon::now();

        // 1. SET LOCALE & TANGGAL
        Carbon::setLocale('id');
        $hariIniIndo = $today->translatedFormat('l'); 
        $hariBesokIndo = $today->copy()->addDay()->translatedFormat('l');

        // 2. LOGIKA CUACA (API EKSTERNAL DENGAN KEAMANAN)
        $apiKey = "36ef06dab6afab28a1a456df2bf886f7"; 
        $kota = "Bandung";

        try {
            $response = Http::timeout(2)->get("https://api.openweathermap.org/data/2.5/weather", [
                'q' => $kota,
                'appid' => $apiKey,
                'units' => 'metric',
                'lang' => 'id'
            ]);

            if ($response->successful()) {
                $rawCuaca = $response->json();
                $cuaca = [
                    'tanggal' => $today->translatedFormat('l, d F Y'),
                    'kota' => $rawCuaca['name'],
                    'suhu' => round($rawCuaca['main']['temp']),
                    'deskripsi' => $rawCuaca['weather'][0]['description']
                ];
            } else {
                throw new \Exception("Gagal mengambil data");
            }
        } catch (\Exception $e) {
            $cuaca = [
                'tanggal' => $today->translatedFormat('l, d F Y'),
                'kota' => 'Bandung',
                'suhu' => '--',
                'deskripsi' => 'Data tidak tersedia (Cek Internet/API Key)'
            ];
        }

        // 3. AMBIL PENGUMUMAN TERBARU
        $announcement = Announcement::latest()->first();

        // A. Statistik Ringkas
        $stats = [
            'pending_tasks' => Task::where('status', '!=', 'done')->count(),
            'completed_tasks' => Task::where('status', 'done')->count(),
            'total_materials' => Material::count(),
        ];

        // B. Jadwal Hari Ini
        $todaysSchedules = Schedule::where('day', $hariIniIndo)
                                ->orderBy('start_time', 'asc')
                                ->get();

        // C. Jadwal Besok
        $tomorrowSchedules = Schedule::where('day', $hariBesokIndo)
                                    ->orderBy('start_time', 'asc')
                                    ->get();

        // D. Tugas (Deadline Hari Ini)
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