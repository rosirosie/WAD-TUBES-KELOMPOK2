<h3 class="font-bold text-lg text-gray-800 mb-4">Jadwal Hari Ini!</h3>

@if($todaysClass)
    <div class="bg-[#5c6ac4] text-white rounded-xl p-6 shadow-md relative overflow-hidden animate-fade-in">
        <div class="flex items-center gap-3 mb-4">
            <h4 class="text-xl font-bold">{{ $todaysClass->subject }}</h4>
            
            @php
                $now = \Carbon\Carbon::now();
                $start = \Carbon\Carbon::parse($todaysClass->start_time);
                $end = \Carbon\Carbon::parse($todaysClass->end_time);
                $isOngoing = $now->between($start, $end);
            @endphp

            @if($isOngoing)
                <span class="text-xs font-bold text-red-500 bg-white px-2 py-0.5 rounded-full flex items-center gap-1">
                    <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></span> Sedang Berlangsung
                </span>
            @else
                <span class="text-xs font-bold text-indigo-500 bg-white px-2 py-0.5 rounded-full">
                    {{ $start->format('H:i') }}
                </span>
            @endif
        </div>
        
        <div class="grid grid-cols-3 gap-4 text-sm font-medium opacity-90">
            <div>
                <p class="text-indigo-200 text-xs mb-1">Waktu :</p>
                <p>{{ \Carbon\Carbon::parse($todaysClass->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($todaysClass->end_time)->format('H:i') }}</p>
            </div>
            <div>
                <p class="text-indigo-200 text-xs mb-1">Ruangan :</p>
                <p>{{ $todaysClass->room }}</p>
            </div>
            <div>
                <p class="text-indigo-200 text-xs mb-1">Dosen :</p>
                <p>{{ $todaysClass->lecturer ?? '-' }}</p>
            </div>
        </div>
        <div class="absolute bottom-2 left-4 text-indigo-300 opacity-50 text-xl tracking-widest">...</div>
    </div>
@else
    <div class="bg-white border-2 border-dashed border-gray-200 rounded-xl p-6 flex flex-col items-center justify-center text-center h-40">
        <svg class="w-10 h-10 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <p class="text-gray-500 font-medium">Tidak ada kelas lagi hari ini.</p>
        <p class="text-xs text-gray-400">Selamat beristirahat!</p>
    </div>
@endif