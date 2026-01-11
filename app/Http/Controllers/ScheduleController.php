<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http; 
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    public function index(Request $request) // Tambahkan Request $request
    {
        // 1. Logika Navigasi Tanggal
        // Jika ada parameter 'date' di URL, pakai itu. Jika tidak, pakai hari ini.
        $currentDate = $request->has('date') 
            ? Carbon::parse($request->date) 
            : Carbon::now();

        $startOfWeek = $currentDate->copy()->startOfWeek();
        $endOfWeek   = $currentDate->copy()->endOfWeek();

        // Siapkan link untuk tombol Previous & Next
        $prevWeek = $startOfWeek->copy()->subWeek()->format('Y-m-d');
        $nextWeek = $startOfWeek->copy()->addWeek()->format('Y-m-d');

        // 2. Ambil Data Jadwal
        $schedules = Schedule::orderBy('start_time')->get()->groupBy('day');
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

        // 3. API Hari Libur (Berdasarkan tahun dari minggu yang dilihat)
        try {
            $year = $startOfWeek->year; // Ambil tahun sesuai jadwal yang dibuka
            $response = Http::get("https://dayoffapi.vercel.app/api?year={$year}");
            $holidays = $response->successful() ? $response->json() : [];
        } catch (\Exception $e) {
            $holidays = [];
        }

        return view('schedules.index', compact(
            'schedules', 'days', 'holidays', 
            'startOfWeek', 'endOfWeek', 'prevWeek', 'nextWeek'
        ));
    }

    public function create()
    {
        $subjects = [
            'Pemrograman Web',
            'Algoritma dan Pemrograman',
            'Basis Data',
            'Jaringan Komputer',
            'Sistem Operasi',
            'Manajemen Proyek TI',
            'Rekayasa Perangkat Lunak',
            'Statistika Industri'
        ];

        return view('schedules.create', compact('subjects'));
    }

    public function store(Request $request)
    {
        if (strtolower(Auth::user()->role) !== 'admin') {
        abort(403, 'Hanya Admin yang boleh menambah jadwal.');
        }
        $request->validate([
            'subject'    => 'required',
            'day'        => 'required',
            'start_time' => 'required',
            'end_time'   => 'required',
            'room'       => 'required',
            'date'       => 'nullable|date', 
        ]);

        if ($request->filled('date')) {
            $tahun = date('Y', strtotime($request->date));
            try {
                $response = Http::get("https://api-harilibur.vercel.app/api?year={$tahun}");
                if ($response->successful()) {
                    foreach ($response->json() as $holiday) {
                        if ($holiday['holiday_date'] === $request->date) {
                            return back()->withInput()->with('warning', "Warning: Tanggal ini adalah hari libur nasional ({$holiday['holiday_name']}).");
                        }
                    }
                }
            } catch (\Exception $e) {
                Log::error("Gagal terhubung ke API Hari Libur: " . $e->getMessage());
            }
        }

        Schedule::create([
            'user_id'    => Auth::id(),
            'subject'    => $request->subject,
            'day'        => $request->day,
            'start_time' => $request->start_time,
            'end_time'   => $request->end_time,
            'room'       => $request->room,
            'lecturer'   => $request->lecturer,
            'date'       => $request->date,
        ]);

        return redirect()->route('schedules.index')->with('success', 'Jadwal berhasil ditambahkan!');
    }

    public function edit(Schedule $schedule)
    {
        if (Auth::user()->role !== 'admin' && Auth::id() !== $schedule->user_id) {
            abort(403, 'Akses ditolak.');
        }
        $subjects = [
            'Pemrograman Web',
            'Algoritma dan Pemrograman',
            'Basis Data',
            'Jaringan Komputer',
            'Sistem Operasi',
            'Manajemen Proyek TI',
            'Rekayasa Perangkat Lunak',
            'Statistika Industri'
        ];
        return view('schedules.edit', compact('schedule', 'subjects'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        if (Auth::user()->role !== 'admin' && Auth::id() !== $schedule->user_id) {
            abort(403);
        }
        $request->validate([
            'subject'    => 'required',
            'day'        => 'required',
            'start_time' => 'required',
            'end_time'   => 'required',
            'room'       => 'required',
            'date'       => 'nullable|date',
        ]);

        if ($request->filled('date') && $request->date !== $schedule->date) {
            $tahun = date('Y', strtotime($request->date));
            try {
                $response = Http::get("https://api-harilibur.vercel.app/api?year={$tahun}");
                if ($response->successful()) {
                    foreach ($response->json() as $holiday) {
                        if ($holiday['holiday_date'] === $request->date) {
                            session()->flash('warning', "Peringatan: Anda menjadwalkan pada hari libur ({$holiday['holiday_name']}).");
                            break;
                        }
                    }
                }
            } catch (\Exception $e) {
                Log::error("API Holiday Error: " . $e->getMessage());
            }
        }

        $schedule->update([
            'subject'    => $request->subject,
            'day'        => $request->day,
            'start_time' => $request->start_time,
            'end_time'   => $request->end_time,
            'room'       => $request->room,
            'lecturer'   => $request->lecturer,
            'date'       => $request->date,
        ]);

        return redirect()->route('schedules.index')->with('success', 'Jadwal berhasil diperbarui!');
    }

    public function destroy(Schedule $schedule)
    {
        if (Auth::user()->role !== 'admin' && Auth::id() !== $schedule->user_id) {
            abort(403);
        }
        $schedule->delete();
        return redirect()->back()->with('success', 'Jadwal dihapus!');
    }
}