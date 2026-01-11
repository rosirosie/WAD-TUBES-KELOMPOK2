@extends('layouts.app')

@section('title', 'Weekly Schedule - SI4807')

@section('content')
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Weekly Schedule</h1>
            
            {{-- NAVIGASI MINGGU --}}
            <div class="flex items-center gap-3 mt-2">
                {{-- Tombol Minggu Sebelumnya --}}
                <a href="{{ route('schedules.index', ['date' => $prevWeek]) }}" class="p-1.5 rounded-lg hover:bg-gray-100 text-gray-500 hover:text-indigo-600 transition border border-gray-200 bg-white shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>

                {{-- Keterangan Tanggal --}}
                <p class="text-sm font-medium text-gray-600">
                    {{ $startOfWeek->format('d M') }} - {{ $endOfWeek->format('d M Y') }}
                </p>

                {{-- Tombol Minggu Selanjutnya --}}
                <a href="{{ route('schedules.index', ['date' => $nextWeek]) }}" class="p-1.5 rounded-lg hover:bg-gray-100 text-gray-500 hover:text-indigo-600 transition border border-gray-200 bg-white shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
                
                {{-- Tombol Reset ke "Hari Ini" (Opsional, muncul jika bukan minggu ini) --}}
                @if(!$startOfWeek->isSameWeek(now()))
                    <a href="{{ route('schedules.index') }}" class="text-xs font-bold text-indigo-600 hover:underline ml-2">
                        Ke Hari Ini
                    </a>
                @endif
            </div>
        </div>
        
        <div class="flex gap-3">
            <button onclick="window.print()" class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Print
            </button>

            @if(strtolower(Auth::user()->role) == 'admin') 
                <a href="{{ route('schedules.create') }}" class="flex items-center gap-2 px-4 py-2 bg-[#6366f1] text-white rounded-lg hover:bg-indigo-700 font-bold transition shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    New Class
                </a>
            @endif
        </div>
    </div>

    @if(session('warning'))
        <div class="bg-amber-50 border-l-4 border-amber-500 p-4 mb-8 rounded-r-xl shadow-sm flex items-start gap-3 animate-in fade-in slide-in-from-top-4 duration-500">
            <div class="bg-amber-100 p-2 rounded-lg text-amber-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 256 256"><path d="M236.8,188.09,149.35,36.22a24.76,24.76,0,0,0-42.7,0L19.2,188.09a23.51,23.51,0,0,0,0,23.72A24.34,24.34,0,0,0,40.55,224h174.9a24.34,24.34,0,0,0,21.35-12.19A23.51,23.51,0,0,0,236.8,188.09ZM120,104a8,8,0,0,1,16,0v40a8,8,0,0,1-16,0Zm8,88a12,12,0,1,1,12-12A12,12,0,0,1,128,192Z"></path></svg>
            </div>
            <div>
                <h4 class="text-amber-800 font-bold text-sm">Sistem Validasi Akademik</h4>
                <p class="text-amber-700 text-xs mt-0.5">{{ session('warning') }}</p>
            </div>
            <button onclick="this.parentElement.remove()" class="ml-auto text-amber-400 hover:text-amber-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-7 gap-3">
        @foreach($days as $day)
        @php
            // PERBAIKAN: Gunakan $startOfWeek dari Controller, bukan now()
            $dateObj = $startOfWeek->copy()->addDays(array_search($day, $days));
            $formattedDate = $dateObj->format('Y-m-d');
            
            $allHolidays = collect($holidays ?? [])->push(['holiday_date' => '2026-01-01', 'holiday_name' => 'TAHUN BARU MASEHI']);
            $holidayData = $allHolidays->firstWhere('holiday_date', $formattedDate);
            
            $isSunday = ($day === 'Minggu');
            // Tandai hari ini (today) dengan border khusus jika sedang melihat minggu ini
            $isToday = $dateObj->isToday();

            $isHoliday = !empty($holidayData) || $isSunday;

            $holidayName = '';
            if (!empty($holidayData)) {
                $holidayName = $holidayData['holiday_name'];
            } elseif ($isSunday) {
                $holidayName = 'AKHIR PEKAN';
            }
        @endphp

        <div class="flex flex-col gap-4">
            {{-- Header Hari --}}
            {{-- Tambahkan border biru tebal jika Hari Ini --}}
            <div class="pb-2 border-b-2 {{ $isHoliday ? 'border-red-500' : ($isToday ? 'border-indigo-600' : 'border-gray-200') }}">
                <h3 class="font-bold {{ $isHoliday ? 'text-red-500' : ($isToday ? 'text-indigo-600' : 'text-gray-500') }} uppercase text-[10px] tracking-wider text-center mb-1">
                    {{ $day }} 
                    @if($isToday) <span class="ml-1 text-[8px] bg-indigo-100 text-indigo-600 px-1 rounded">HARI INI</span> @endif
                </h3>
                <h2 class="text-xl font-bold text-center {{ $isHoliday ? 'text-red-600' : ($isToday ? 'text-indigo-700' : 'text-gray-900') }}">
                    {{ $dateObj->format('d') }}
                </h2>
                
                @if($isHoliday)
                    <p class="text-[9px] text-red-500 font-black text-center mt-1 leading-tight uppercase animate-pulse">
                        {{ $holidayName }}
                    </p>
                @endif
            </div>

            @if(isset($schedules[$day]))
                @foreach($schedules[$day] as $schedule)
                <div class="bg-white p-4 rounded-xl border {{ $isHoliday ? 'border-red-200 bg-red-50/10' : 'border-gray-200' }} shadow-sm hover:shadow-md transition group relative overflow-hidden">
                    <div class="absolute left-0 top-0 bottom-0 w-1 {{ $isHoliday ? 'bg-red-500' : 'bg-indigo-500' }}"></div>

                    @if(strtolower(Auth::user()->role) == 'admin')
                        <div class="absolute top-2 right-2 flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity z-10">
                            <form action="{{ route('schedules.destroy', $schedule->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus jadwal ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1 bg-white border rounded shadow-sm text-gray-400 hover:text-red-600 transition" title="Hapus">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                            <a href="{{ route('schedules.edit', $schedule->id) }}" class="p-1 bg-white border rounded shadow-sm text-gray-400 hover:text-indigo-600"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg></a>
                        </div>
                    @endif

                    <div class="pl-2">
                        <div class="flex items-center gap-1 {{ $isHoliday ? 'text-red-600' : 'text-indigo-600' }} text-[10px] font-bold mb-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ substr($schedule->start_time, 0, 5) }} - {{ substr($schedule->end_time, 0, 5) }}
                        </div>
                        <h4 class="font-bold text-gray-900 text-xs mb-1">{{ $schedule->subject }}</h4>
                        <p class="text-[10px] text-gray-500 flex items-center gap-1">
                            <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            {{ $schedule->room }}
                        </p>
                        @if(!empty($schedule->lecturer))
                            <p class="text-[10px] text-gray-500 flex items-center gap-1 mt-0.5">
                                <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span class="truncate">{{ $schedule->lecturer }}</span>
                            </p>
                        @endif
                    </div>
                </div>
                @endforeach
            @else
                <div class="h-24 border-2 border-dashed {{ $isHoliday ? 'border-red-100 bg-red-50/50' : 'border-gray-100 bg-gray-50/50' }} rounded-xl flex items-center justify-center transition-colors">
                    <span class="{{ $isHoliday ? 'text-red-400 font-bold' : 'text-gray-300' }} text-[10px] uppercase tracking-tighter">
                        {{ $isHoliday ? 'LIBUR' : 'No Classes' }}
                    </span>
                </div>
            @endif
        </div>
        @endforeach
    </div>
@endsection