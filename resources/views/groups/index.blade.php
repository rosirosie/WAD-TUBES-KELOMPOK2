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

    {{-- SECTION 2: PROJECT PROGRESS --}}
    <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
        <h3 class="font-bold text-gray-800 text-lg mb-6 flex items-center gap-2">
            <span class="text-indigo-600">📈</span> Project Progress
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- Loop kelompok di mana user terlibat --}}
            @forelse($myProjectProgress as $team)
                <a href="{{ route('groups.progress', $team->id) }}" class="bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-semibold py-4 px-6 rounded-xl flex items-center shadow-sm transition group">
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
@endsection