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
            <i class="ph ph-arrow-left"></i> Kembali ke Folder Directory
        </a>
        <button onclick="document.getElementById('createModal').classList.remove('hidden')" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-lg font-semibold shadow-lg shadow-indigo-200 text-sm flex items-center gap-2 transition transform hover:scale-105">
            <i class="ph ph-plus-circle"></i> Create new group
        </button>
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
                        <td class="p-4 font-bold text-gray-800 border-r border-gray-200 align-top">{{ $team->name }}</td>
                        <td class="p-4 font-medium border-r border-gray-200 align-top text-gray-900">
                            {{ $team->leader_name }}
                        </td>
                        <td class="p-4 text-gray-600 border-r border-gray-200 align-top">
                            <ul class="list-none space-y-1">
                                {{-- Loop members dari JSON/Array --}}
                                @foreach($team->members as $member)
                                    <li>• {{ $member }}</li>
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

                    {{-- Baris Kosong untuk Estetika Sesuai Mockup --}}
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

{{-- MODAL CREATE (Tetap menggunakan style Anda) --}}
<div id="createModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-lg p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Buat Kelompok Baru</h2>
        <form action="{{ route('groups.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Kelompok</label>
                    <input type="text" name="name" required class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Topik Proyek</label>
                    <input type="text" name="topic" class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Link Laporan</label>
                        <input type="url" name="report_link" class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Link PPT</label>
                        <input type="url" name="ppt_link" class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('createModal').classList.add('hidden')" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg">Batal</button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection