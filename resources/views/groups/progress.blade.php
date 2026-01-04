@extends('layouts.app')

@section('title', 'Project Progress - SI4807 StudyHub')

@section('content')
<div class="p-2">
    {{-- Header Section --}}
    <div class="mb-6">
        <h1 class="text-xl font-bold text-gray-800 flex items-center gap-2">
            Group Tasks <span class="text-gray-400 text-sm">▶</span> Project Progress
        </h1>
        <p class="text-sm text-gray-400 mt-1">Manajemen tugas besar kelompok Anda</p>
    </div>

    {{-- Action Bar --}}
    <div class="mb-8 flex justify-between items-center">
        <a href="{{ route('groups.index') }}" class="text-sm text-indigo-600 hover:underline flex items-center gap-1">
            <i class="ph ph-arrow-left"></i> Kembali ke Menu Groups
        </a>
        <button onclick="document.getElementById('createModal').classList.remove('hidden')" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-lg font-semibold shadow-lg shadow-indigo-200 text-sm flex items-center gap-2 transition transform hover:scale-105">
            <i class="ph ph-plus-circle"></i> Create new group
        </button>
    </div>

    {{-- Project Table Card --}}
    <div class="border border-gray-200 rounded-2xl shadow-sm overflow-hidden bg-white">
        <div class="p-5 border-b border-gray-200 bg-white flex justify-between items-center">
            <div class="flex items-center gap-2 font-bold text-gray-800">
                <span class="text-indigo-600">📂</span>
                <span class="text-gray-800 text-base">{{ $team->group->subject ?? 'Pengembangan Aplikasi Website' }}</span>
            </div>
            <div class="relative">
                <input type="text" id="groupSearch" placeholder="Cari Kelompok" class="pl-9 pr-4 py-2 border border-gray-200 rounded-full text-sm bg-gray-50 focus:outline-none focus:border-indigo-500 w-64 transition">
                <span class="absolute left-3 top-2.5 text-gray-400">🔍</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-700 border-collapse">
                <thead class="bg-gray-100 text-gray-600 font-bold border-b border-gray-200">
                    <tr>
                        <th class="p-4 w-16 text-center border-r border-gray-200">No</th>
                        <th class="p-4 border-r border-gray-200">Kelompok</th>
                        <th class="p-4 border-r border-gray-200">Ketua Kelompok</th>
                        <th class="p-4 text-center border-r border-gray-200">Link Laporan</th>
                        <th class="p-4 text-center">Link PPT</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Row Data Dinamis --}}
                    <tr class="bg-white border-b border-gray-200 hover:bg-indigo-50/50 transition-colors">
                        <td class="p-4 text-center font-medium border-r border-gray-200">1</td>
                        <td class="p-4 font-medium border-r border-gray-200 text-indigo-600 italic">
                            {{ $team->name ?? 'Kelompok 1' }}
                        </td>
                        <td class="p-4 font-medium border-r border-gray-200">
                            {{ $team->leader_name ?? 'Rosita Jian Syaahirah' }}
                        </td>
                        
                        {{-- Link Laporan --}}
                        <td class="p-4 text-center border-r border-gray-200">
                            @if($team->report_link)
                                <a href="{{ $team->report_link }}" target="_blank" class="inline-flex items-center justify-center text-blue-500 hover:text-blue-700 text-lg transform -rotate-45 transition-transform hover:scale-125">
                                    🔗
                                </a>
                            @else
                                <span class="text-gray-300">➖</span>
                            @endif
                        </td>

                        {{-- Link PPT --}}
                        <td class="p-4 text-center">
                            @if($team->ppt_link)
                                <a href="{{ $team->ppt_link }}" target="_blank" class="inline-flex items-center justify-center text-blue-500 hover:text-blue-700 text-lg transform -rotate-45 transition-transform hover:scale-125">
                                    🔗
                                </a>
                            @else
                                <span class="text-gray-300">➖</span>
                            @endif
                        </td>
                    </tr>

                    {{-- Baris Kosong (Estetika Zebra Sesuai Mockup) --}}
                    @for($i = 0; $i < 7; $i++)
                    <tr class="{{ $i % 2 == 0 ? 'bg-gray-50' : 'bg-white' }} border-b border-gray-200 h-12">
                        <td class="border-r border-gray-200"></td>
                        <td class="border-r border-gray-200"></td>
                        <td class="border-r border-gray-200"></td>
                        <td class="border-r border-gray-200"></td>
                        <td></td>
                    </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL CREATE (Sudah disesuaikan dengan field model GroupTeam) --}}
<div id="createModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden">
        <div class="bg-indigo-600 p-4 text-white flex justify-between items-center">
            <h2 class="text-lg font-bold">Buat Kelompok Baru</h2>
            <button onclick="document.getElementById('createModal').classList.add('hidden')" class="hover:bg-indigo-700 rounded-lg p-1">
                <i class="ph ph-x text-xl"></i>
            </button>
        </div>
        
        <form action="{{ route('groups.store') }}" method="POST" class="p-6">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Kelompok</label>
                    <input type="text" name="name" required class="w-full border-gray-200 rounded-xl px-4 py-2 mt-1 focus:ring-2 focus:ring-indigo-500 outline-none bg-gray-50">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Topik Proyek</label>
                    <input type="text" name="topic" placeholder="Contoh: Manajemen Kelas" class="w-full border-gray-200 rounded-xl px-4 py-2 mt-1 focus:ring-2 focus:ring-indigo-500 outline-none bg-gray-50">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Link Laporan (URL)</label>
                        <input type="url" name="report_link" class="w-full border-gray-200 rounded-xl px-4 py-2 mt-1 focus:ring-2 focus:ring-indigo-500 outline-none bg-gray-50">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Link PPT (URL)</label>
                        <input type="url" name="ppt_link" class="w-full border-gray-200 rounded-xl px-4 py-2 mt-1 focus:ring-2 focus:ring-indigo-500 outline-none bg-gray-50">
                    </div>
                </div>
            </div>
            <div class="mt-8 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('createModal').classList.add('hidden')" class="px-5 py-2 text-gray-500 font-semibold hover:bg-gray-100 rounded-xl transition">Batal</button>
                <button type="submit" class="px-8 py-2 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-100 transition">Simpan Kelompok</button>
            </div>
        </form>
    </div>
</div>
@endsection