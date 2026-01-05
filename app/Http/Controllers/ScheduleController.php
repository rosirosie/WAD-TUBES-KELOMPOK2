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
    public function index()
    {
        // 1. Ambil jadwal user yang login
        $schedules = Schedule::orderBy('start_time')
                    ->get()
                    ->groupBy('day');

        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        
        // 2. Tentukan rentang tahun untuk API (Penting karena minggu ini melewati pergantian tahun)
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $years = array_unique([$startOfWeek->year, $endOfWeek->year]);

        $holidays = [];

        // --- AMBIL DATA LIBUR DARI API ---
        foreach ($years as $year) {
            try {
                $response = Http::get("https://api-harilibur.vercel.app/api?year={$year}");
                if ($response->successful()) {
                    // Gabungkan data libur jika minggu melewati dua tahun berbeda
                    $holidays = array_merge($holidays, $response->json());
                }
            } catch (\Exception $e) {
                Log::error("Gagal memuat API libur tahun {$year}: " . $e->getMessage());
            }
        }

        return view('schedules.index', compact('schedules', 'days', 'holidays'));
    }

    public function create()
    {
        return view('schedules.create');
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
        return view('schedules.edit', compact('schedule'));
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