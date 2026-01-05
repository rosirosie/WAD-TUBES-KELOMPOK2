@extends('layouts.app')

@section('title', 'Directory - SI4807 StudyHub')

@section('content')
<div class="p-2">
    {{-- Breadcrumb & Header --}}
    <div class="mb-8">
        <h1 class="text-xl font-bold text-gray-800 flex items-center gap-2">
            Groups <span class="text-gray-400 text-sm">▶</span> Master Group Directory
        </h1>
        <p class="text-sm text-gray-400 mt-1">Daftar semua kelompok untuk mata kuliah: <span class="font-bold text-indigo-600">{{ $subject }}</span></p>
    </div>

    {{-- Action Bar --}}
    <div class="mb-8 flex justify-between items-center">
        <a href="{{ route('groups.directory') }}" class="text-sm text-indigo-600 hover:underline flex items-center gap-1">
            <span>&larr;</span> Kembali ke Folder Directory
        </a>
        
        <div class="flex gap-3">
            {{-- TOMBOL EXPORT EXCEL --}}
            <a href="{{ route('groups.export') }}" class="bg-white border border-gray-200 text-gray-700 px-6 py-2.5 rounded-lg font-bold shadow-sm text-sm flex items-center gap-2 transition hover:bg-gray-50 border-b-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Export Excel
            </a>

            <button onclick="document.getElementById('createModal').classList.remove('hidden')" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-lg font-semibold shadow-lg shadow-indigo-200 text-sm flex items-center gap-2 transition transform hover:scale-105">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Create new group
            </button>
        </div>
    </div>

    {{-- Table Section --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-white">
            <h2 class="font-bold text-[#5C6AC4] text-lg">Master Group Directory: {{ $subject }}</h2>
            <div class="relative">
                <input type="text" id="tableSearch" placeholder="Cari Kelompok..." class="pl-9 pr-4 py-2 border border-gray-200 rounded-full text-sm bg-gray-50 focus:outline-none focus:border-indigo-500 w-64 transition">
                <span class="absolute left-3 top-2.5 text-gray-400 text-xs">🔍</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-sm text-left">
                <thead class="bg-gray-100 text-gray-600 font-bold border-b border-gray-200">
                    <tr>
                        <th class="p-4 w-16 text-center border-r border-gray-200">No</th>
                        <th class="p-4 border-r border-gray-200">Kelompok</th>
                        <th class="p-4 border-r border-gray-200">Ketua Kelompok</th>
                        <th class="p-4 border-r border-gray-200">Anggota Kelompok</th>
                        <th class="p-4">Tema</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @forelse($teams as $index => $team)
                    <tr class="bg-white border-b border-gray-200 hover:bg-indigo-50/30 transition-colors">
                        <td class="p-4 text-center font-medium border-r border-gray-200 align-top">{{ $index + 1 }}</td>
                        
                        {{-- KOLOM KELOMPOK (DENGAN API IDENTICON) --}}
                        <td class="p-4 border-r border-gray-200 align-top">
                            <div class="flex items-center gap-3">
                                <img 
                                    src="https://api.dicebear.com/7.x/identicon/svg?seed={{ urlencode($team->name) }}&backgroundColor=b6e3f4,c0aede,d1d4f9" 
                                    alt="Icon Group"
                                    class="w-10 h-10 rounded-lg shadow-sm bg-gray-50"
                                >
                                <span class="font-bold text-gray-800">{{ $team->name }}</span>
                            </div>
                        </td>

                        <td class="p-4 font-medium border-r border-gray-200 align-top text-gray-900">
                            {{ $team->leader_name }}
                        </td>

                        {{-- KOLOM ANGGOTA (DENGAN API INITIALS) --}}
                        <td class="p-4 text-gray-600 border-r border-gray-200 align-top">
                            <ul class="list-none space-y-2">
                                @foreach($team->members as $member)
                                    <li class="flex items-center gap-2">                                     
                                        <span>{{ $member }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </td>

                        <td class="p-4 align-top">
                            <span class="bg-gray-100 border border-gray-200 text-gray-600 px-3 py-1 rounded text-xs font-medium">
                                {{ $team->topic ?? 'Manajemen Kelas' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-10 text-center text-gray-400 italic">Belum ada data kelompok untuk mata kuliah ini.</td>
                    </tr>
                    @endforelse

                    {{-- Spacer Rows --}}
                    @php $remaining = max(0, 5 - count($teams)); @endphp
                    @for($i = 0; $i < $remaining; $i++)
                    <tr class="bg-white border-b border-gray-200 h-16">
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

{{-- MODAL CREATE --}}
<div id="createModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-lg p-6 max-h-[90vh] overflow-y-auto">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Buat Kelompok Baru</h2>
        
        <form action="{{ route('groups.store') }}" method="POST">
            @csrf
            
            {{-- INPUT HIDDEN --}}
            <input type="hidden" name="group_id" value="{{ $group_id ?? 1 }}">
            <input type="hidden" name="leader_name" value="{{ Auth::user()->name ?? 'User' }}">

            <div class="space-y-4">
                {{-- Nama Kelompok --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Kelompok</label>
                    <input type="text" name="name" placeholder="Contoh: Kelompok 1" required class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>

                {{-- Anggota Kelompok --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Anggota Kelompok</label>
                    <div class="space-y-2">
                        <input type="text" value="{{ Auth::user()->name ?? 'User' }} (Ketua)" readonly class="w-full border bg-gray-100 text-gray-500 rounded-lg px-4 py-2 text-sm">
                        <input type="hidden" name="members[]" value="{{ Auth::user()->name ?? 'User' }}">
                        
                        <input type="text" name="members[]" placeholder="Nama Anggota 2" required class="w-full border rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-indigo-500">
                        <input type="text" name="members[]" placeholder="Nama Anggota 3" class="w-full border rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-indigo-500">
                        <input type="text" name="members[]" placeholder="Nama Anggota 4" class="w-full border rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>

                {{-- Topik --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Topik Proyek</label>
                    <input type="text" name="topic" placeholder="Judul Proyek..." required class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>

                {{-- Links --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Link Laporan</label>
                        <input type="url" name="report_link" placeholder="https://..." class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Link PPT</label>
                        <input type="url" name="ppt_link" placeholder="https://..." class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('createModal').classList.add('hidden')" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg">Batal</button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-semibold shadow-md">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection