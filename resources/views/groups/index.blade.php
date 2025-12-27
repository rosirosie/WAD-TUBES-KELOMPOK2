@extends('layouts.app')

@section('content')
    <div class="mb-8 border-b border-gray-200 pb-4">
        <h1 class="text-3xl font-bold text-gray-800">Groups and Group Tasks</h1>
    </div>

    <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 mb-8">
        <div class="flex items-center mb-6 text-[#5C6AC4]">
            <h2 class="text-lg font-bold">Master Group Directory</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($groups as $group)
                <a href="{{ route('groups.show', $group->id) }}" class="bg-[#9EA7FC] p-4 rounded-lg shadow-md flex items-center hover:bg-[#8E99F8] transition">
                    <span class="font-bold text-gray-900">{{ $group->name }}</span>
                </a>
            @endforeach
        </div>
    </div>

    <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
        <div class="flex items-center mb-6 text-[#5C6AC4]">
            <h2 class="text-lg font-bold italic">Project Progress</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <a href="{{ route('groups.progress') }}" class="bg-[#9EA7FC] p-4 rounded-lg shadow-md flex items-center hover:bg-[#8E99F8] transition text-center justify-center font-bold text-gray-900">
                Lihat Semua Progress Kelompok
            </a>
        </div>
    </div>
@endsection