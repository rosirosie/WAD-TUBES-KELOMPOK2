@extends('layouts.app')

@section('title', 'Project Progress - ' . $team->name)

@section('content')
<div class="p-6">
    
    {{-- TOMBOL KEMBALI --}}
    <div class="mb-4">
        {{-- Mengarah ke route 'groups.index' (Halaman Dashboard Kelompok) --}}
        <a href="{{ route('groups.index') }}" class="inline-flex items-center gap-2 text-sm text-indigo-600 font-medium hover:text-indigo-800 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Halaman Groups
        </a>
    </div>

    {{-- Header & Breadcrumb --}}
    <div class="mb-8 border-b border-gray-100 pb-6">
        <div class="flex items-center gap-2 text-sm text-gray-400 mb-2">
            <span>Groups</span>
            <span>/</span>
            <span>{{ $team->group->subject }}</span>
            <span>/</span>
            <span class="text-indigo-600 font-semibold">Progress Detail</span>
        </div>
        <h1 class="text-3xl font-bold text-gray-800">{{ $team->name }}</h1>
        <p class="text-gray-500 mt-1">Topik: <span class="font-semibold text-indigo-600">{{ $team->topic }}</span></p>
    </div>

    {{-- Alert Success --}}
    @if(session('success'))
    <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 text-green-700 rounded-r-lg shadow-sm flex justify-between items-center">
        <p>{{ session('success') }}</p>
        <button onclick="this.parentElement.remove()" class="text-green-500 hover:text-green-700">&times;</button>
    </div>
    @endif

    {{-- Alert Error Validation --}}
    @if ($errors->any())
    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 text-red-700 rounded-r-lg shadow-sm">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- KOLOM KIRI: Info Anggota --}}
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-gray-800 text-lg mb-4 flex items-center gap-2">
                    <span>👥</span> Anggota Tim
                </h3>
                <ul class="space-y-4">
                    {{-- Ketua --}}
                    <li class="flex items-center gap-3 pb-3 border-b border-gray-50">
                        <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-sm shadow-sm">
                            {{ substr($team->leader_name, 0, 2) }}
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-800">{{ $team->leader_name }}</p>
                            <span class="text-[10px] uppercase tracking-wider bg-indigo-600 text-white px-2 py-0.5 rounded-full font-bold">Ketua</span>
                        </div>
                    </li>

                    {{-- Anggota Lain --}}
                    @if(is_array($team->members) || is_object($team->members))
                        @foreach($team->members as $member)
                            @if($member !== $team->leader_name)
                            <li class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gray-50 text-gray-500 flex items-center justify-center font-bold text-sm border border-gray-100">
                                    {{ substr($member, 0, 2) }}
                                </div>
                                <p class="text-sm font-medium text-gray-700">{{ $member }}</p>
                            </li>
                            @endif
                        @endforeach
                    @endif
                </ul>
            </div>
        </div>

        {{-- KOLOM KANAN: Tabel Progress --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <h3 class="font-bold text-gray-800 text-lg flex items-center gap-2">
                        <span>📝</span> Progress Mingguan
                    </h3>
                    <button onclick="document.getElementById('progressModal').classList.remove('hidden')" 
                            class="text-sm bg-indigo-600 text-white px-5 py-2.5 rounded-xl hover:bg-indigo-700 transition shadow-lg shadow-indigo-200 font-semibold flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        Update Progress
                    </button>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 text-gray-600 font-semibold uppercase text-xs tracking-wider">
                            <tr>
                                <th class="p-4 border-b border-gray-100">Minggu</th>
                                <th class="p-4 border-b border-gray-100 w-1/3">Aktivitas</th>
                                <th class="p-4 border-b border-gray-100">PJ</th>
                                <th class="p-4 border-b border-gray-100">Status</th>
                                <th class="p-4 border-b border-gray-100 text-center">Aksi</th>
                            </tr>
                        </thead>

                        {{-- TBODY BARU DENGAN TOMBOL EDIT (DATA ATTRIBUTES) --}}
                        <tbody class="divide-y divide-gray-100">
                            @forelse($progress_data as $item)
                            <tr class="hover:bg-gray-50 transition-colors group">
                                <td class="p-4 font-bold text-indigo-900">Minggu {{ $item->week ?? '-' }}</td>
                                <td class="p-4 text-gray-700 leading-relaxed">{{ $item->title }}</td>
                                <td class="p-4 text-gray-500">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center text-xs font-bold text-gray-600">
                                            {{ substr($item->assigned_to, 0, 1) }}
                                        </div>
                                        <span class="text-xs">{{ $item->assigned_to }}</span>
                                    </div>
                                </td>
                                <td class="p-4">
                                    @if($item->is_completed)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-green-400" fill="currentColor" viewBox="0 0 8 8">
                                                <circle cx="4" cy="4" r="3" />
                                            </svg>
                                            Selesai
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-yellow-400" fill="currentColor" viewBox="0 0 8 8">
                                                <circle cx="4" cy="4" r="3" />
                                            </svg>
                                            On Progress
                                        </span>
                                    @endif
                                </td>
                                
                                {{-- KOLOM AKSI (EDIT & HAPUS) --}}
                                <td class="p-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        
                                        {{-- TOMBOL EDIT (BEST PRACTICE: DATA ATTRIBUTES) --}}
                                        <button type="button" 
                                                data-id="{{ $item->id }}"
                                                data-week="{{ $item->week }}"
                                                data-title="{{ $item->title }}"
                                                data-assigned="{{ $item->assigned_to }}"
                                                data-completed="{{ $item->is_completed ? '1' : '0' }}"
                                                onclick="openEditModal(this)"
                                                class="text-yellow-500 hover:text-yellow-600 hover:bg-yellow-50 p-2 rounded-lg transition" 
                                                title="Edit Data">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>

                                        {{-- TOMBOL HAPUS (MERAH) --}}
                                        <form action="{{ route('groups.progress.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus progress ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-400 hover:text-red-600 hover:bg-red-50 p-2 rounded-lg transition" title="Hapus Item">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="p-12 text-center text-gray-400 italic">
                                    Belum ada progress tercatat.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL CREATE (Asli) --}}
<div id="progressModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50 backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 relative transform transition-all scale-100">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold text-gray-800">Tambah Progress Baru</h3>
            <button onclick="document.getElementById('progressModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <form action="{{ route('groups.progress.store') }}" method="POST">
            @csrf
            {{-- Hidden Input ID Tim --}}
            <input type="hidden" name="group_team_id" value="{{ $team->id }}">

            <div class="space-y-5">
                {{-- Input Minggu --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Minggu Ke</label>
                    <div class="relative">
                        <input type="number" name="week" min="1" max="14" required
                               class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm pl-4 pr-4 py-2.5 transition"
                               placeholder="Contoh: 1">
                    </div>
                </div>

                {{-- Input Aktivitas --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi Aktivitas</label>
                    <textarea name="title" rows="3" required
                              class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm px-4 py-2.5 transition resize-none"
                              placeholder="Apa yang dikerjakan minggu ini?"></textarea>
                </div>

                {{-- Input Penanggung Jawab --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Penanggung Jawab (PJ)</label>
                    <select name="assigned_to" class="w-full rounded-xl border-gray-200 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm px-4 py-2.5 bg-white">
                        <option value="{{ $team->leader_name }}">{{ $team->leader_name }}</option>
                        @if(is_array($team->members) || is_object($team->members))
                            @foreach($team->members as $member)
                                @if($member !== $team->leader_name)
                                    <option value="{{ $member }}">{{ $member }}</option>
                                @endif
                            @endforeach
                        @endif
                    </select>
                </div>

                {{-- Input Status --}}
                <div class="flex items-center gap-3 bg-gray-50 p-3 rounded-xl border border-gray-100">
                    <input type="checkbox" name="is_completed" value="1" id="statusCheck" class="w-5 h-5 rounded text-indigo-600 focus:ring-indigo-500 border-gray-300">
                    <label for="statusCheck" class="text-sm font-medium text-gray-700 cursor-pointer select-none">Tandai sebagai Selesai</label>
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="mt-8 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('progressModal').classList.add('hidden')" 
                        class="px-5 py-2.5 bg-white border border-gray-200 text-gray-600 rounded-xl hover:bg-gray-50 font-medium transition">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 font-semibold shadow-lg shadow-indigo-200 transition">
                    Simpan Progress
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDIT PROGRESS (Baru) --}}
<div id="editModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 relative">
        <h3 class="text-xl font-bold text-gray-800 mb-6">Edit Progress</h3>
        
        <form id="editForm" method="POST">
            @csrf
            @method('PUT') {{-- PENTING: Method PUT untuk Update --}}

            <div class="space-y-5">
                {{-- Input Minggu --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Minggu Ke</label>
                    <input type="number" name="week" id="edit_week" min="1" max="14" required
                           class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm px-4 py-2.5">
                </div>

                {{-- Input Aktivitas --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi Aktivitas</label>
                    <textarea name="title" id="edit_title" rows="3" required
                              class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm px-4 py-2.5 resize-none"></textarea>
                </div>

                {{-- Input PJ --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Penanggung Jawab (PJ)</label>
                    <select name="assigned_to" id="edit_assigned_to" class="w-full rounded-xl border-gray-200 text-sm px-4 py-2.5">
                        <option value="{{ $team->leader_name }}">{{ $team->leader_name }}</option>
                        @if(is_array($team->members) || is_object($team->members))
                            @foreach($team->members as $member)
                                @if($member !== $team->leader_name)
                                    <option value="{{ $member }}">{{ $member }}</option>
                                @endif
                            @endforeach
                        @endif
                    </select>
                </div>

                {{-- Input Status --}}
                <div class="flex items-center gap-3 bg-gray-50 p-3 rounded-xl border border-gray-100">
                    <input type="checkbox" name="is_completed" value="1" id="edit_is_completed" class="w-5 h-5 rounded text-indigo-600 focus:ring-indigo-500 border-gray-300">
                    <label for="edit_is_completed" class="text-sm font-medium text-gray-700">Tandai sebagai Selesai</label>
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" 
                        class="px-5 py-2.5 bg-white border border-gray-200 text-gray-600 rounded-xl hover:bg-gray-50 font-medium">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 bg-yellow-500 text-white rounded-xl hover:bg-yellow-600 font-semibold shadow-lg shadow-yellow-200">
                    Update Data
                </button>
            </div>
        </form>
    </div>
</div>

{{-- SCRIPT JAVASCRIPT UNTUK BUKA MODAL EDIT --}}
<script>
    function openEditModal(button) {

        const id = button.getAttribute('data-id');
        const week = button.getAttribute('data-week');
        const title = button.getAttribute('data-title');
        const assignedTo = button.getAttribute('data-assigned');
        const isCompleted = button.getAttribute('data-completed') === '1';

        document.getElementById('edit_week').value = week;
        document.getElementById('edit_title').value = title;
        document.getElementById('edit_assigned_to').value = assignedTo;
        document.getElementById('edit_is_completed').checked = isCompleted;

        let url = "{{ route('groups.progress.update', ':id') }}";
        url = url.replace(':id', id);
        document.getElementById('editForm').action = url;

        document.getElementById('editModal').classList.remove('hidden');
    }
</script>
@endsection