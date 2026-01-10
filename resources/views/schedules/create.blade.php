@extends('layouts.app')

@section('title', 'Tambah Jadwal Baru')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Tambah Jadwal Baru</h1>
            <p class="text-gray-500 mt-2">Masukkan detail mata kuliah, waktu, dan ruangan.</p>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="h-1 bg-indigo-600 w-full"></div> <div class="p-8">
                <form action="{{ route('schedules.store') }}" method="POST">
                    @csrf 
                    
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            Mata Kuliah <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select name="subject" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 transition outline-none appearance-none bg-white" required>
                                <option value="" disabled selected>-- Pilih Mata Kuliah --</option>
                                @foreach($subjects as $subjectOption)
                                    <option value="{{ $subjectOption }}" {{ old('subject') == $subjectOption ? 'selected' : '' }}>
                                        {{ $subjectOption }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            Hari <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select name="day" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 transition outline-none appearance-none bg-white" required>
                                <option value="" disabled selected>-- Pilih Hari --</option>
                                <option value="Senin">Senin</option>
                                <option value="Selasa">Selasa</option>
                                <option value="Rabu">Rabu</option>
                                <option value="Kamis">Kamis</option>
                                <option value="Jumat">Jumat</option>
                                <option value="Sabtu">Sabtu</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">
                                Jam Mulai <span class="text-red-500">*</span>
                            </label>
                            <input type="time" name="start_time" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 transition outline-none" required>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">
                                Jam Selesai <span class="text-red-500">*</span>
                            </label>
                            <input type="time" name="end_time" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 transition outline-none" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">
                                Ruangan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="room" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 transition outline-none" placeholder="Contoh: TULT-0101" required>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">
                                Kode Dosen <span class="text-gray-400 font-normal">(Opsional)</span>
                            </label>
                            <input type="text" name="lecturer" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 transition outline-none" placeholder="Contoh: DTP">
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 border-t border-gray-100 pt-6">
                        <a href="{{ route('schedules.index') }}" class="px-6 py-2.5 rounded-lg text-gray-500 font-medium hover:bg-gray-50 transition">
                            Batal
                        </a>
                        <button type="submit" class="px-6 py-2.5 rounded-lg bg-indigo-600 text-white font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition transform hover:-translate-y-0.5">
                            Simpan Jadwal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection