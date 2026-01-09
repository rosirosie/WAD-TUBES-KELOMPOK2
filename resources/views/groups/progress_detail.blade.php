@extends('layouts.app')

@section('title', 'Project Progress - ' . $team->name)

@section('content')
<div class="p-6">
    {{-- Header & Breadcrumb --}}
    <div class="mb-8">
        <div class="flex items-center gap-2 text-sm text-gray-500 mb-1">
            <span>Groups</span>
            <span>▶</span>
            <a href="{{ route('groups.directory.detail', $team->group->subject) }}" class="hover:text-indigo-600">
                {{ $team->group->subject }}
            </a>
            <span>▶</span>
            <span class="text-indigo-600 font-bold">Progress Detail</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-800">{{ $team->name }}</h1>
        <p class="text-gray-500">Topik Proyek: <span class="font-semibold text-indigo-600">{{ $team->topic }}</span></p>
    </div>

    {{-- Action Bar (Kembali) --}}
    <div class="mb-6">
        <a href="{{ route('groups.directory.detail', $team->group->subject) }}" class="inline-flex items-center gap-2 text-sm font-medium text-gray-600 bg-white border border-gray-200 hover:bg-gray-50 px-4 py-2 rounded-lg shadow-sm transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Directory
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- KOLOM KIRI: Info Kelompok & Anggota --}}
        <div class="space-y-6">
            {{-- Card Anggota --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-gray-800 text-lg mb-4 flex items-center gap-2">
                    👥 Anggota Tim
                </h3>
                <ul class="space-y-3">
                    {{-- Ketua --}}
                    <li class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-xs">
                            {{ substr($team->leader_name, 0, 2) }}
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-800">{{ $team->leader_name }}</p>
                            <span class="text-xs bg-indigo-600 text-white px-2 py-0.5 rounded-full">Ketua</span>
                        </div>
                    </li>
                    
                    {{-- Anggota (Looping) --}}
                    @foreach($team->members as $member)
                        @if($member !== $team->leader_name) {{-- Hindari duplikasi jika ketua masuk array --}}
                        <li class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-gray-100 text-gray-500 flex items-center justify-center font-bold text-xs">
                                {{ substr($member, 0, 2) }}
                            </div>
                            <p class="text-sm font-medium text-gray-700">{{ $member }}</p>
                        </li>
                        @endif
                    @endforeach
                </ul>
            </div>

            {{-- Card Link Proyek --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-gray-800 text-lg mb-4 flex items-center gap-2">
                    🔗 Link Submission
                </h3>
                <div class="space-y-3">
                    @if($team->report_link)
                        <a href="{{ $team->report_link }}" target="_blank" class="block w-full text-center px-4 py-2 border border-blue-200 text-blue-600 rounded-lg hover:bg-blue-50 text-sm font-semibold transition">
                            📄 Lihat Laporan
                        </a>
                    @else
                        <div class="text-center px-4 py-2 border border-dashed border-gray-300 text-gray-400 rounded-lg text-sm italic">
                            Belum ada link laporan
                        </div>
                    @endif

                    @if($team->ppt_link)
                        <a href="{{ $team->ppt_link }}" target="_blank" class="block w-full text-center px-4 py-2 border border-orange-200 text-orange-600 rounded-lg hover:bg-orange-50 text-sm font-semibold transition">
                            📊 Lihat Presentasi (PPT)
                        </a>
                    @else
                        <div class="text-center px-4 py-2 border border-dashed border-gray-300 text-gray-400 rounded-lg text-sm italic">
                            Belum ada link PPT
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: Progress / Tasks --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="font-bold text-gray-800 text-lg">📝 Progress Mingguan</h3>
                    <button class="text-sm text-indigo-600 font-medium hover:underline">+ Update Progress</button>
                </div>
                
                {{-- Tabel Progress (Simulasi Static Data dulu, nanti bisa disambung database) --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 text-gray-600 font-semibold">
                            <tr>
                                <th class="p-4">Minggu Ke</th>
                                <th class="p-4">Deskripsi Aktivitas</th>
                                <th class="p-4">Status</th>
                                <th class="p-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            {{-- Contoh Data Statis --}}
                            <tr class="hover:bg-gray-50">
                                <td class="p-4 font-medium">Minggu 1</td>
                                <td class="p-4">Pembentukan kelompok dan penentuan topik</td>
                                <td class="p-4"><span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-bold">Selesai</span></td>
                                <td class="p-4 text-center text-gray-400 hover:text-indigo-600 cursor-pointer">✏️</td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="p-4 font-medium">Minggu 2</td>
                                <td class="p-4">Penyusunan proposal dan pembagian tugas</td>
                                <td class="p-4"><span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-xs font-bold">On Progress</span></td>
                                <td class="p-4 text-center text-gray-400 hover:text-indigo-600 cursor-pointer">✏️</td>
                            </tr>
                            
                            {{-- State Kosong (Jika nanti pakai DB) --}}
                            {{-- 
                            <tr>
                                <td colspan="4" class="p-8 text-center text-gray-400">Belum ada progress yang dicatat.</td>
                            </tr>
                            --}}
                        </tbody>
                    </table>
                </div>
            </div>
            
            {{-- Bagian Komentar Dosen (Optional) --}}
            <div class="mt-6 bg-blue-50 rounded-xl border border-blue-100 p-6">
                <h3 class="font-bold text-blue-800 text-sm mb-2">💬 Catatan Dosen</h3>
                <p class="text-blue-700 text-sm">
                    "Topik sudah bagus, tolong segera lengkapi Link PPT untuk presentasi minggu depan ya. Semangat!"
                </p>
            </div>
        </div>

    </div>
</div>
@endsection