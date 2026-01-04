@extends('layouts.app')

@section('title', 'Groups - SI4807 StudyHub')

@section('content')
<div class="p-2">
    {{-- Header Section --}}
    <div class="mb-6">
        <h2 class="text-2xl font-bold mb-1 text-gray-800">Groups and Group Tasks</h2>
        <p class="text-gray-500 text-sm">Kelola kelompok dan tugas besar kelas SI4807</p>
    </div>

    {{-- Create Button --}}
    <div class="mb-8">
        <a href="{{ route('groups.create') }}" class="inline-flex bg-indigo-600 text-white px-6 py-2.5 rounded-lg font-semibold shadow-md hover:bg-indigo-700 transition transform hover:scale-105 items-center gap-2">
            <span>+</span> Create new group
        </a>
    </div>

    {{-- SECTION 1: MASTER GROUP DIRECTORY --}}
    <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 mb-8">
        <h3 class="font-bold text-gray-800 text-lg mb-6 flex items-center gap-2">
            <span class="text-indigo-600">👥</span> Master Group Directory
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- Loop data mata kuliah dari database --}}
            @forelse($subjects as $sub)
                <a href="{{ route('groups.directory.detail', $sub->subject) }}" class="bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-semibold py-4 px-6 rounded-xl flex items-center shadow-sm transition group">
                    <span class="mr-3 transition-transform group-hover:scale-120">👤</span>
                    {{ $sub->subject }}
                </a>
            @empty
                <div class="col-span-full py-4 text-center text-gray-400 italic bg-gray-50 rounded-xl">
                    Belum ada folder mata kuliah tersedia.
                </div>
            @endforelse
        </div>
    </div>

    {{-- SECTION 2: PROJECT PROGRESS --}}
    <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
        <h3 class="font-bold text-gray-800 text-lg mb-6 flex items-center gap-2">
            <span class="text-indigo-600">📈</span> Project Progress
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- Loop kelompok di mana user terlibat --}}
            @forelse($myProjectProgress as $team)
                <a href="{{ route('groups.progress.detail', $team->id) }}" class="bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-semibold py-4 px-6 rounded-xl flex items-center shadow-sm transition group">
                    <span class="mr-3 transition-transform group-hover:scale-120">👤</span>
                    {{ $team->group->subject ?? 'Mata Kuliah Kelompok' }}
                </a>
            @empty
                <div class="col-span-full py-10 text-center bg-gray-50 rounded-2xl border-2 border-dashed border-gray-100">
                    <p class="text-gray-400 text-sm italic">Anda belum memiliki kelompok aktif untuk dipantau progresnya.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection