@extends('layouts.app')

@section('title', 'Daftar Tugas')
@section('subtitle', Auth::user()->role == 'admin' ? 'Kelola deadline & tugas mahasiswa.' : 'Prioritaskan tugas dengan deadline terdekat.')

@section('content')

    {{-- WIDGET ZENQUOTES API --}}
    @if(isset($quote))
    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-2xl p-6 mb-8 text-white shadow-lg relative overflow-hidden transition-all hover:shadow-indigo-200/50">
        <div class="absolute top-0 right-0 p-4 opacity-10">
            <i class="ph-fill ph-quotes text-7xl"></i>
        </div>
        <div class="relative z-10">
            <p class="text-lg font-medium italic">"{{ $quote['q'] }}"</p>
            <p class="text-sm mt-2 opacity-80">— {{ $quote['a'] }}</p>
        </div>
    </div>
    @endif

    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div class="flex gap-2 overflow-x-auto pb-2">
            @php
                $statusList = ['all' => 'Semua', 'pending' => 'Pending', 'progress' => 'In Progress', 'done' => 'Selesai'];
                $currentStatus = request('status', 'all');
            @endphp
            @foreach($statusList as $key => $label)
                <a href="{{ route('tasks.index', ['status' => $key]) }}" 
                   class="px-4 py-2 rounded-full text-sm font-medium border transition
                   {{ $currentStatus == $key ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-600 hover:bg-gray-50' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        @if(strtolower(Auth::user()->role) == 'admin')
            <button onclick="document.getElementById('createTaskModal').classList.remove('hidden')" 
                    class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-bold shadow hover:bg-indigo-700 flex items-center gap-2">
                <i class="ph ph-plus-circle text-lg"></i> Buat Tugas Baru
            </button>
        @endif
    </div>

    <div class="space-y-4">
        @forelse($tasks as $task)
            <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition flex flex-col md:flex-row gap-4 items-start md:items-center justify-between">
                
                <div class="flex items-start gap-4 flex-1">
                    <div class="p-3 rounded-lg flex-shrink-0 {{ $task->status == 'done' ? 'bg-green-100 text-green-600' : 'bg-orange-50 text-orange-600' }}">
                        <i class="ph {{ $task->status == 'done' ? 'ph-check-circle' : 'ph-warning-circle' }} text-2xl"></i>
                    </div>

                    <div class="w-full">
                        <h3 class="font-bold text-gray-800 text-lg {{ $task->status == 'done' ? 'line-through text-gray-400' : '' }}">
                            {{ $task->title }}
                        </h3>

                        <div class="flex flex-wrap items-center gap-3 mt-1 text-sm">
                            <span class="bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded text-xs font-bold flex items-center gap-1">
                                <i class="ph ph-book-open"></i> {{ $task->course }}
                            </span>

                            <span class="bg-gray-100 text-gray-600 px-2 py-0.5 rounded text-xs font-semibold">
                                Tugas Kelas
                            </span>

                            @if(strtolower(Auth::user()->role) == 'admin')
                                <form action="{{ route('tasks.update', $task->id) }}" method="POST" class="inline-block">
                                    @csrf @method('PUT')
                                    <input type="date" name="deadline" 
                                           value="{{ $task->deadline ? $task->deadline->format('Y-m-d') : '' }}" 
                                           onchange="this.form.submit()"
                                           class="text-xs border border-gray-300 rounded px-2 py-1 focus:ring-indigo-500 focus:border-indigo-500 cursor-pointer">
                                </form>
                            @else
                                @if($task->deadline)
                                    @php $isUrgent = $task->deadline->isPast() || $task->deadline->isToday(); @endphp
                                    <span class="flex items-center gap-1 {{ ($isUrgent && $task->status != 'done') ? 'text-red-600 font-bold bg-red-50 px-2 py-0.5 rounded' : 'text-gray-500' }}">
                                        <i class="ph ph-calendar-blank"></i>
                                        {{ $task->deadline->translatedFormat('d M Y') }}
                                        @if($isUrgent && $task->status != 'done') <span class="text-xs">(Segera!)</span> @endif
                                    </span>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-2 w-full md:w-auto mt-2 md:mt-0">
                    <form action="{{ route('tasks.update', $task->id) }}" method="POST" class="w-full">
                        @csrf @method('PUT')
                        <select name="status" onchange="this.form.submit()" 
                                class="w-full md:w-40 text-sm rounded-lg border-gray-200 cursor-pointer py-2 px-3 font-medium
                                {{ $task->status == 'pending' ? 'bg-yellow-50 text-yellow-700' : '' }}
                                {{ $task->status == 'progress' ? 'bg-blue-50 text-blue-700' : '' }}
                                {{ $task->status == 'done' ? 'bg-green-50 text-green-700' : '' }}">
                            <option value="pending" {{ $task->status == 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                            <option value="progress" {{ $task->status == 'progress' ? 'selected' : '' }}>🚀 Progress</option>
                            <option value="done" {{ $task->status == 'done' ? 'selected' : '' }}>✅ Selesai</option>
                        </select>
                    </form>

                    @if(strtolower(Auth::user()->role) == 'admin')
                        <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" onsubmit="return confirm('Hapus tugas ini?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition">
                                <i class="ph ph-trash text-xl"></i>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-12 border-2 border-dashed border-gray-200 rounded-xl bg-gray-50">
                <p class="text-gray-500">Tidak ada tugas dalam daftar ini.</p>
            </div>
        @endforelse
    </div>

    {{-- MODAL TAMBAH TUGAS --}}
    @if(strtolower(Auth::user()->role) == 'admin')
    <div id="createTaskModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all">
            <div class="bg-indigo-600 p-4 flex justify-between items-center text-white">
                <h3 class="font-bold flex items-center gap-2 text-lg">
                    <i class="ph ph-note-pencil"></i> Buat Tugas Kelas
                </h3>
                <button onclick="document.getElementById('createTaskModal').classList.add('hidden')" class="hover:bg-indigo-500 rounded-full p-1 transition">
                    <i class="ph ph-x text-xl"></i>
                </button>
            </div>

            <form action="{{ route('tasks.store') }}" method="POST" class="p-6 space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Mata Kuliah</label>
                    <input type="text" name="course" required placeholder="Contoh: Pemrograman Web" 
                           class="w-full border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 p-3 bg-gray-50 text-sm">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Judul Tugas</label>
                    <input type="text" name="title" required placeholder="Contoh: Tugas Mandiri Pertemuan 5" 
                           class="w-full border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 p-3 bg-gray-50 text-sm">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Batas Waktu (Deadline)</label>
                    <input type="date" name="deadline" required 
                           class="w-full border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 p-3 bg-gray-50 text-sm">
                </div>

                <div class="flex flex-col gap-2 pt-2 text-center">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-xl shadow-lg transition flex items-center justify-center gap-2">
                        <i class="ph ph-paper-plane-tilt"></i> Kirim ke Seluruh Kelas
                    </button>
                    <button type="button" onclick="document.getElementById('createTaskModal').classList.add('hidden')" 
                            class="text-gray-400 text-sm font-medium hover:text-gray-600 py-2">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

@endsection