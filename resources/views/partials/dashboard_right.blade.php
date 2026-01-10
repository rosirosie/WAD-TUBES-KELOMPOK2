@if(isset($cuaca))
    @php
        // Logika Peringatan Cerdas & Warna Dinamis
        $deskripsi = strtolower($cuaca['deskripsi']);
        $perluPayung = str_contains($deskripsi, 'hujan') || str_contains($deskripsi, 'mendung') || str_contains($deskripsi, 'gerimis');
        
        if(str_contains($deskripsi, 'hujan')) {
            $gradient = "from-blue-600 via-indigo-700 to-slate-800";
            $icon = "ph-fill ph-cloud-rain";
        } elseif(str_contains($deskripsi, 'mendung')) {
            $gradient = "from-slate-500 via-gray-600 to-slate-700";
            $icon = "ph-fill ph-cloud";
        } else {
            $gradient = "from-indigo-600 via-purple-600 to-pink-500";
            $icon = "ph-fill ph-cloud-sun";
        }
    @endphp

    {{-- Widget Cuaca Cerdas --}}
    <div class="relative overflow-hidden rounded-3xl shadow-2xl transition-all duration-500 hover:shadow-indigo-200/50 group bg-gradient-to-br {{ $gradient }} p-1 mb-6">
        <div class="relative z-10 bg-white/10 backdrop-blur-md rounded-[22px] p-6 text-white border border-white/20">
            {{-- Background Pattern Icon --}}
            <div class="absolute top-0 right-0 p-4 opacity-20 group-hover:scale-110 transition-transform duration-700">
                <i class="{{ $icon }} text-8xl"></i>
            </div>

            <div class="relative z-20">
                <div class="flex justify-between items-start">
                    <div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-white/20 text-white border border-white/30 uppercase tracking-widest mb-2">
                            Real-time Weather
                        </span>
                        <p class="text-indigo-100 text-sm font-semibold opacity-90">{{ $cuaca['tanggal'] }}</p>
                        <h3 class="text-3xl font-black mt-1 flex items-center gap-2">
                            <i class="ph-bold ph-map-pin text-pink-300"></i> {{ $cuaca['kota'] }}
                        </h3>
                    </div>
                </div>

                <div class="flex items-end justify-between mt-8">
                    <div class="flex items-center gap-4">
                        <span class="text-7xl font-black tracking-tighter drop-shadow-lg">{{ $cuaca['suhu'] }}°</span>
                        <div class="h-12 w-[2px] bg-white/30 rotate-12"></div>
                        <div class="flex flex-col">
                            <span class="text-xl font-bold leading-none capitalize">{{ $cuaca['deskripsi'] }}</span>
                            <span class="text-xs font-medium text-indigo-100/80 mt-1">Humidity: 65% | Wind: 12km/h</span>
                        </div>
                    </div>
                </div>

                {{-- Alert Box: Bawa Payung --}}
                <div class="mt-6 flex items-center gap-3 p-3 rounded-2xl {{ $perluPayung ? 'bg-red-500/40 border border-red-400/50' : 'bg-white/20' }}">
                    <div class="bg-white/90 p-2 rounded-xl shadow-sm text-indigo-600">
                        <i class="ph-fill {{ $perluPayung ? 'ph-umbrella text-red-600' : 'ph-sunglasses' }} text-xl"></i>
                    </div>
                    <p class="text-xs font-bold leading-tight">
                        {{ $perluPayung ? '⚠️ Cuaca kurang bersahabat, jangan lupa bawa payung ya!' : '✨ Cuaca bagus untuk beraktivitas di luar.' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Efek Animasi Cahaya --}}
        <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-yellow-400/30 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute -top-10 -right-10 w-32 h-32 bg-blue-400/30 rounded-full blur-3xl animate-pulse"></div>
    </div>
@endif

{{-- Widget Kelas Besok --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 mb-6">
    <h3 class="font-bold text-gray-800 mb-3 flex items-center gap-2">
        <i class="ph-bold ph-calendar text-indigo-500"></i> Kelas Besok <span class="text-gray-400 font-normal">({{ $hariBesokIndo ?? 'Besok' }})</span>
    </h3>
    @if(isset($tomorrowSchedules) && $tomorrowSchedules->count() > 0)
        <ul class="space-y-3">
            @foreach($tomorrowSchedules as $sch)
                <li class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg border border-gray-100 hover:bg-gray-100 transition">
                    <div class="w-1.5 h-10 bg-indigo-500 rounded-full"></div>
                    <div>
                        <p class="font-bold text-sm text-gray-800">{{ $sch->subject }}</p>
                        <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($sch->start_time)->format('H:i') }} - {{ $sch->room }}</p>
                    </div>
                </li>
            @endforeach
        </ul>
    @else
        <div class="p-4 text-center text-gray-400 text-sm bg-gray-50 rounded-lg border border-dashed border-gray-200">
            Besok libur / tidak ada kelas.
        </div>
    @endif
</div>

{{-- Widget Tugas Mendatang --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 mb-6">
    <h3 class="font-bold text-gray-800 mb-3 flex items-center gap-2">
        <i class="ph-bold ph-clock-countdown text-yellow-500"></i> Tugas Mendatang
    </h3>
    @if(isset($upcomingTasks) && $upcomingTasks->count() > 0)
        <div class="space-y-3">
            @foreach($upcomingTasks as $task)
            <div class="border-l-4 border-yellow-400 pl-3 py-1 group hover:bg-yellow-50 transition rounded-r-lg">
                <p class="font-bold text-sm text-gray-800 group-hover:text-yellow-700">{{ $task->title }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ \Carbon\Carbon::parse($task->deadline)->isoFormat('dddd, D MMM') }}</p>
            </div>
            @endforeach
        </div>
    @else
        <p class="text-sm text-gray-400 text-center py-2">Tidak ada tugas mendatang.</p>
    @endif
</div>

{{-- Widget Catatan Hari Ini --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
    <h3 class="font-bold text-gray-800 mb-3 flex items-center gap-2">
        <i class="ph-bold ph-note-pencil text-purple-500"></i> Catatan Hari Ini
    </h3>
    <div class="bg-gradient-to-br from-[#818cf8] to-[#6366f1] border-2 border-dashed border-white/40 rounded-xl p-6 min-h-[120px] flex items-center justify-center text-center shadow-inner group transition-all duration-300">
        @if(isset($todaysMaterial) && $todaysMaterial)
            <div class="text-white">
                <p class="font-bold text-xs uppercase opacity-70 mb-1">Material Baru:</p>
                <p class="font-medium text-sm leading-relaxed">{{ $todaysMaterial->title }}</p>
            </div>
        @else
            <p class="font-bold text-white text-sm opacity-90 leading-tight">Tak Ada Catatan<br>Hari Ini</p>
        @endif
    </div>
</div>