<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
    <h3 class="font-bold text-lg text-gray-800 mb-4">Jadwal Hari Ini!</h3>
    
    @if(isset($todaysSchedules) && $todaysSchedules->count() > 0)
        <div class="space-y-4">
            @foreach($todaysSchedules as $schedule)
                <div class="bg-[#5c6ac4] text-white rounded-xl p-6 shadow-md relative overflow-hidden transition hover:scale-[1.01]">
                    <div class="flex items-center gap-3 mb-4">
                        <h4 class="text-xl font-bold">{{ $schedule->subject }}</h4>
                        
                        @php
                            $now = \Carbon\Carbon::now();
                            $start = \Carbon\Carbon::parse($schedule->start_time);
                            $end = \Carbon\Carbon::parse($schedule->end_time);
                            $isOngoing = $now->between($start, $end);
                        @endphp

                        @if($isOngoing)
                            <span class="text-xs font-bold text-red-500 bg-white px-2 py-0.5 rounded-full flex items-center gap-1">
                                <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></span> Live
                            </span>
                        @else
                            <span class="text-xs font-bold text-indigo-500 bg-white px-2 py-0.5 rounded-full">
                                {{ $start->format('H:i') }}
                            </span>
                        @endif
                    </div>
                    
                    <div class="grid grid-cols-3 gap-4 text-sm font-medium opacity-90">
                        <div>
                            <p class="text-indigo-200 text-xs mb-1">Waktu</p>
                            <p>{{ $start->format('H:i') }} - {{ $end->format('H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-indigo-200 text-xs mb-1">Ruangan</p>
                            <p>{{ $schedule->room }}</p>
                        </div>
                        <div>
                            <p class="text-indigo-200 text-xs mb-1">Dosen</p>
                            <p>{{ $schedule->lecturer ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white border-2 border-dashed border-gray-200 rounded-xl p-8 flex flex-col items-center justify-center text-center h-48">
            <p class="text-gray-500 font-medium">Tidak ada kelas hari ini.</p>
            <p class="text-xs text-gray-400">Silakan istirahat!</p>
        </div>
    @endif
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <h3 class="font-bold text-lg text-gray-800 mb-4">Deadline Hari Ini!</h3>
    @if(isset($todaysTasks) && $todaysTasks->count() > 0)
        @foreach($todaysTasks as $task)
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl mb-3 flex justify-between items-center">
                <div>
                    <h4 class="font-bold text-red-700">{{ $task->title }}</h4>
                    <p class="text-xs text-red-500">Berakhir hari ini!</p>
                </div>
            </div>
        @endforeach
    @else
        <div class="p-4 bg-green-50 text-green-700 rounded-xl border border-green-100 text-sm font-medium">
            Tidak ada deadline tugas hari ini. Aman!
        </div>
    @endif
</div>