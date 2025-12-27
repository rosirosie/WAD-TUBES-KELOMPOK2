@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Jadwal Kuliah</h1>
            <p class="text-gray-500">Atur agenda mingguanmu disini.</p>
        </div>
        
        <a href="{{ route('schedules.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow-lg flex items-center transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Jadwal
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        @foreach($days as $day)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden flex flex-col h-full">
                
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h2 class="font-bold text-lg text-gray-800">{{ $day }}</h2>
                    @if(\Carbon\Carbon::now()->isoFormat('dddd') == $day)
                        <span class="text-xs bg-blue-100 text-blue-600 px-2 py-1 rounded-full font-bold">Hari Ini</span>
                    @endif
                </div>

                <div class="p-6 flex-1">
                    @if(isset($schedules[$day]) && count($schedules[$day]) > 0)
                        
                        <div class="space-y-6">
                            @foreach($schedules[$day] as $schedule)
                                <div class="relative pl-4 border-l-2 border-blue-400 group">
                                    <div class="absolute -left-[5px] top-1 h-2 w-2 rounded-full bg-blue-400"></div>

                                    <p class="text-xs font-bold text-blue-600 mb-1">
                                        {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - 
                                        {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                    </p>

                                    <h3 class="font-bold text-gray-800 text-md leading-tight">
                                        {{ $schedule->course->course_name ?? 'Matkul' }}
                                    </h3>

                                    <div class="text-sm text-gray-500 mt-1 space-y-1">
                                        <div class="flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                            {{ $schedule->room_location }}
                                        </div>
                                        <div class="flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                            {{ $schedule->course->lecturer_name ?? '-' }}
                                        </div>
                                    </div>
                                    
                                    <div class="mt-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <a href="{{ route('schedules.edit', $schedule->id) }}" class="text-xs text-blue-500 hover:underline">Edit</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    @else
                        <div class="h-full flex flex-col items-center justify-center text-gray-400 py-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mb-2 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-sm">Tidak ada kelas</span>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach

    </div>
@endsection