@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
    <h2 class="text-2xl font-bold mb-6 text-indigo-900">Create New Group</h2>

    <form action="{{ route('groups.store') }}" method="POST">
        @csrf
        <div class="space-y-4">
            {{-- Pilih Mata Kuliah --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Mata Kuliah</label>
                <select name="group_id" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none">
                    @foreach($subjects as $sub)
                        <option value="{{ $sub->id }}">{{ $sub->subject }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Nama Kelompok --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kelompok</label>
                <input type="text" name="name" placeholder="Contoh: Kelompok 1" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none" required>
            </div>

            {{-- Nama Ketua --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Ketua</label>
                <input type="text" name="leader_name" value="{{ auth()->user()->name }}" class="w-full p-3 bg-gray-100 border border-gray-200 rounded-xl outline-none" readonly>
            </div>

            {{-- Nama Anggota --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Anggota (Pisahkan dengan koma)</label>
                <textarea name="members" placeholder="Nama Anggota 1, Nama Anggota 2..." class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none" rows="3" required></textarea>
            </div>

            {{-- Judul Proyek --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Topik/Judul Proyek</label>
                <input type="text" name="topic" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>

            <div class="pt-4 flex gap-3">
                <button type="submit" class="flex-1 bg-indigo-600 text-white font-bold py-3 rounded-xl hover:bg-indigo-700 transition">Simpan Kelompok</button>
                <a href="{{ route('groups.index') }}" class="flex-1 bg-gray-100 text-gray-600 text-center font-bold py-3 rounded-xl hover:bg-gray-200 transition">Batal</a>
            </div>
        </div>
    </form>
</div>
@endsection