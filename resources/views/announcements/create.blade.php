@extends('layouts.app')

@section('title', 'Buat Pengumuman - SI4807 StudyHub')

@section('content')
    <div class="max-w-3xl mx-auto">
        <header class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Buat Pengumuman Baru</h1>
            <p class="text-gray-500 mt-2">
                Informasi yang Anda buat akan langsung muncul di Dashboard seluruh mahasiswa.
            </p>
        </header>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            
            <div class="h-2 bg-indigo-600"></div>

            <div class="p-8">
                <form action="{{ route('announcements.store') }}" method="POST">
                    @csrf <div class="mb-6">
                        <label for="title" class="block text-sm font-bold text-gray-700 mb-2">
                            Judul Pengumuman
                        </label>
                        <input type="text" 
                               name="title" 
                               id="title" 
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition outline-none"
                               placeholder="Contoh: Perubahan Jadwal Kuliah STATIN"
                               required>
                    </div>

                    <div class="mb-6">
                        <label for="type" class="block text-sm font-bold text-gray-700 mb-2">
                            Tingkat Urgensi / Warna Widget
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <label class="cursor-pointer">
                                <input type="radio" name="type" value="urgent" class="peer sr-only" checked>
                                <div class="p-4 rounded-lg border-2 border-gray-200 peer-checked:border-red-500 peer-checked:bg-red-50 hover:bg-gray-50 transition flex items-center gap-3">
                                    <div class="w-4 h-4 rounded-full bg-red-500"></div>
                                    <span class="font-medium text-gray-700">Penting (Merah)</span>
                                </div>
                            </label>

                            <label class="cursor-pointer">
                                <input type="radio" name="type" value="warning" class="peer sr-only">
                                <div class="p-4 rounded-lg border-2 border-gray-200 peer-checked:border-yellow-500 peer-checked:bg-yellow-50 hover:bg-gray-50 transition flex items-center gap-3">
                                    <div class="w-4 h-4 rounded-full bg-yellow-500"></div>
                                    <span class="font-medium text-gray-700">Peringatan (Kuning)</span>
                                </div>
                            </label>

                            <label class="cursor-pointer">
                                <input type="radio" name="type" value="info" class="peer sr-only">
                                <div class="p-4 rounded-lg border-2 border-gray-200 peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:bg-gray-50 transition flex items-center gap-3">
                                    <div class="w-4 h-4 rounded-full bg-blue-500"></div>
                                    <span class="font-medium text-gray-700">Info Biasa (Biru)</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="mb-8">
                        <label for="content" class="block text-sm font-bold text-gray-700 mb-2">
                            Isi Pesan Detail
                        </label>
                        <textarea name="content" 
                                  id="content" 
                                  rows="5" 
                                  class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition outline-none"
                                  placeholder="Tuliskan detail lengkap pengumuman di sini..."
                                  required></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-4 border-t border-gray-100 pt-6">
                        <a href="{{ route('dashboard') }}" class="px-6 py-2.5 rounded-lg text-gray-500 font-medium hover:bg-gray-100 transition">
                            Batal
                        </a>
                        <button type="submit" class="px-6 py-2.5 rounded-lg bg-indigo-600 text-white font-bold hover:bg-indigo-700 shadow-md transition transform hover:-translate-y-0.5">
                            Terbitkan Pengumuman
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection