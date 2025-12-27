@extends('layouts.app')

@section('content')
    <h1 class="text-3xl font-bold text-gray-800 mb-8">Overview</h1>

    <div class="flex flex-col lg:flex-row gap-8">

        <div class="flex-1 flex flex-col gap-8">
            
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                @if($announcement)
                    <h2 class="font-bold text-xl mb-2 text-gray-800">{{ $announcement->title }}</h2>
                    <p class="text-gray-600">{{ $announcement->content }}</p>
                @else
                    <h2 class="font-bold text-xl mb-2 text-gray-400">Tidak ada pengumuman baru</h2>
                    <p class="text-gray-500">Tetap pantau terus ya!</p>
                @endif
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h2 class="font-bold text-xl mb-4 text-gray-800">Jadwal Hari Ini!</h2>
                
                @if($todaySchedule)
                    <div class="bg-[#5C6AC4] p-6 rounded-lg text-white shadow-md relative overflow-hidden">
                        <div class="flex items-center mb-4 relative z-10">
                            <h3 class="font-bold text-lg mr-3">
                                {{ $todaySchedule->course->course_name ?? 'Nama Matkul Error' }}
                            </h3>
                            
                            <span class="flex items-center text-sm bg-red-500 px-2 py-1 rounded-full">
                                <span class="h-2 w-2 bg-white rounded-full mr-2 animate-pulse"></span> 
                                Sedang Berlangsung
                            </span>
                        </div>
                        
                        <div class="flex justify-between text-sm pr-10 relative z-10">
                            <p>Waktu : {{ $todaySchedule->start_time }} - {{ $todaySchedule->end_time }}</p>
                            <p>Ruangan : {{ $todaySchedule->room_location }}</p>
                            <p>Dosen : {{ $todaySchedule->course->lecturer_name ?? '-' }}</p>
                        </div>
                        
                        <div class="mt-6 text-2xl font-bold text-white/50 relative z-10">...</div>
                    </div>
                @else
                    <div class="bg-gray-50 p-6 rounded-lg text-gray-500 text-center border-dashed border-2 border-gray-300">
                        <p class="font-semibold">Hore! Tidak ada kelas hari ini.</p>
                        <p class="text-sm">Gunakan waktu untuk istirahat atau belajar mandiri.</p>
                    </div>
                @endif
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h2 class="font-bold text-xl mb-4 text-gray-800">Tugas Hari Ini!</h2>
                
                @if($todayTask)
                    <div class="bg-[#6B72E1] p-6 rounded-lg text-white h-32 flex flex-col justify-center shadow-md">
                        <p class="font-bold text-lg">{{ $todayTask->title }}</p>
                        <p class="text-sm opacity-90 mt-2">Deadline: {{ $todayTask->deadline_date }}</p>
                    </div>
                @else
                    <div class="bg-green-50 p-6 rounded-lg text-green-600 text-center border border-green-200 h-32 flex items-center justify-center">
                        ✅ Aman! Tidak ada deadline hari ini.
                    </div>
                @endif
            </div>
        </div>

        <div class="w-full lg:w-80 flex flex-col gap-8">

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 h-fit">
                <h2 class="font-bold text-gray-800 mb-4">Kelas Besok! <span class="text-gray-500 font-normal">({{ $dayNameTomorrow }})</span></h2>
                
                @if($tomorrowSchedule)
                    <div class="bg-blue-50 p-4 rounded-lg border-l-4 border-blue-500">
                        <p class="font-bold text-blue-900">{{ $tomorrowSchedule->course->course_name ?? 'Matkul' }}</p>
                        <p class="text-sm text-blue-700 mt-1">
                            ⏰ {{ $tomorrowSchedule->start_time }} <br> 
                            📍 {{ $tomorrowSchedule->room_location }}
                        </p>
                    </div>
                @else
                    <div class="bg-gray-50 p-4 rounded-lg text-center font-bold text-gray-400 border border-gray-200 h-32 flex items-center justify-center">
                        Tidak ada kelas
                    </div>
                @endif
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 h-fit">
                <h2 class="font-bold text-gray-800 mb-4">Tugas Mendatang!</h2>
                
                @if($upcomingTask)
                    <div class="border-l-4 border-gray-400 pl-4 py-2">
                        <p class="font-bold text-gray-800">{{ $upcomingTask->title }}</p>
                        <p class="text-sm text-gray-500 mt-1">
                            {{ \Carbon\Carbon::parse($upcomingTask->deadline_date)->translatedFormat('l, d F Y') }}
                        </p>
                    </div>
                @else
                    <p class="text-gray-400 italic text-sm text-center py-4">Tidak ada tugas dalam waktu dekat.</p>
                @endif
            </div>

             <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 h-fit">
                <h2 class="font-bold text-gray-800 mb-4">Catatan Hari Ini!</h2>
                
                @if($todayMaterial)
                    <div class="bg-[#848AE3] p-6 rounded-lg text-white text-center h-40 flex flex-col items-center justify-center shadow-md">
                        <span class="font-bold text-lg block mb-2">{{ $todayMaterial->title }}</span>
                        <button class="text-xs bg-white text-[#848AE3] px-4 py-2 rounded-full font-bold shadow-sm hover:bg-gray-100 transition">
                            Download PDF
                        </button>
                    </div>
                @else
                    <div class="bg-[#848AE3] p-6 rounded-lg text-white text-center h-40 flex items-center justify-center font-bold shadow-md opacity-80">
                        Tak Ada Catatan<br>Hari Ini
                    </div>
                @endif
            </div>

        </div>
    </div>
@endsection