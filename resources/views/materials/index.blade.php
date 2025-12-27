@extends('layouts.app')

@section('content')
    <div class="mb-6 border-b border-gray-200 pb-4">
        <h1 class="text-3xl font-bold text-gray-800">Notes</h1>
        <p class="text-gray-500 mt-1">Kelola Catatan Mata Kuliah Harian</p>
    </div>

    <div class="mb-8">
        <button onclick="document.getElementById('uploadModal').classList.remove('hidden')" class="inline-flex items-center px-6 py-2 bg-[#7B86F5] hover:bg-[#6A75E0] text-white font-bold rounded-lg shadow-md transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add new notes
        </button>
    </div>

    <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 mb-8">
        <div class="flex items-center mb-6 text-[#5C6AC4]">
            <svg class="w-6 h-6 mr-2 text-black" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path></svg>
            <h2 class="text-lg font-bold">Recent Notes</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($recentNotes as $note)
                <div class="bg-[#9EA7FC] p-6 rounded-2xl shadow-md flex items-center hover:bg-[#8E99F8] transition cursor-pointer">
                    <svg class="w-6 h-6 mr-4 text-black" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"></path></svg>
                    <span class="font-bold text-gray-900 text-lg">{{ $note->title }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-800">All Notes</h2>
            <select class="border border-gray-300 rounded-lg px-4 py-1 text-sm text-gray-600 focus:outline-none">
                <option>All Notes</option>
            </select>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-gray-400 text-sm uppercase border-b border-gray-100">
                        <th class="pb-4 font-semibold">Minggu Ke</th>
                        <th class="pb-4 font-semibold">Mata Kuliah</th>
                        <th class="pb-4 font-semibold text-center">Materi</th>
                        <th class="pb-4 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @foreach($allNotes as $note)
                        <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                            <td class="py-4 font-bold">Minggu {{ $note->week ?? '-' }}</td>
                            <td class="py-4 text-gray-500">{{ $note->course_name ?? '-' }}</td>
                            <td class="py-4 text-center">
                                <span class="bg-[#E6FFFA] text-[#38B2AC] px-6 py-1 rounded-full font-bold text-sm">
                                    {{ $note->title }}
                                </span>
                            </td>
                            <td class="py-4 text-right">
                                <a href="{{ asset('storage/' . $note->file_path) }}" target="_blank" class="text-blue-600 font-bold hover:underline">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div id="uploadModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white p-8 rounded-3xl w-full max-w-md">
            <h2 class="text-2xl font-bold mb-6">Upload Catatan Baru</h2>
            <form action="{{ route('materials.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2">Mata Kuliah</label>
                    <input type="text" name="course_name" class="w-full border p-2 rounded-lg" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2">Minggu Ke</label>
                    <input type="number" name="week" class="w-full border p-2 rounded-lg" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2">Nama Materi</label>
                    <input type="text" name="title" class="w-full border p-2 rounded-lg" required placeholder="Contoh: Activity Diagram">
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-bold mb-2">File (PDF/Image)</label>
                    <input type="file" name="file" class="w-full border p-2 rounded-lg" required>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('uploadModal').classList.add('hidden')" class="px-4 py-2 bg-gray-200 rounded-lg">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-[#7B86F5] text-white rounded-lg font-bold">Upload</button>
                </div>
            </form>
        </div>
    </div>
@endsection