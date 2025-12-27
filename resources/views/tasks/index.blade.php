@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Board Tugas</h1>
            <p class="text-gray-500">Geser status tugasmu agar tetap terorganisir.</p>
        </div>
        
        <a href="{{ route('tasks.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-lg shadow flex items-center transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Tugas Baru
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 h-full pb-4">

        <div class="flex flex-col">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-gray-700 flex items-center">
                    <span class="w-3 h-3 bg-gray-400 rounded-full mr-2"></span> To Do
                </h3>
                <span class="bg-gray-200 text-gray-600 text-xs px-2 py-1 rounded-full font-bold">{{ count($tasks['todo']) }}</span>
            </div>
            
            <div class="flex-1 bg-gray-50 rounded-xl p-4 border border-gray-200 min-h-[400px] space-y-4">
                @forelse($tasks['todo'] as $task)
                    <div class="bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition cursor-pointer border-l-4 border-gray-400 group">
                        <h4 class="font-bold text-gray-800 mb-1 text-lg">{{ $task->title }}</h4>
                        <p class="text-sm text-gray-500 mb-3 line-clamp-2">{{ $task->description }}</p>
                        
                        <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-100">
                            <div class="flex items-center text-xs text-gray-400">
                                📅 {{ \Carbon\Carbon::parse($task->deadline_date)->format('d M') }}
                            </div>
                            <span class="text-[10px] font-bold px-2 py-1 rounded-full {{ $task->priority == 'High' ? 'bg-red-100 text-red-600' : 'bg-blue-100 text-blue-600' }}">
                                {{ $task->priority }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-400 text-sm mt-10">Belum ada tugas.</p>
                @endforelse
            </div>
        </div>

        <div class="flex flex-col">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-blue-600 flex items-center">
                    <span class="w-3 h-3 bg-blue-500 rounded-full mr-2 animate-pulse"></span> In Progress
                </h3>
                <span class="bg-blue-100 text-blue-600 text-xs px-2 py-1 rounded-full font-bold">{{ count($tasks['in_progress']) }}</span>
            </div>
            
            <div class="flex-1 bg-blue-50 rounded-xl p-4 border border-blue-100 min-h-[400px] space-y-4">
                @forelse($tasks['in_progress'] as $task)
                    <div class="bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition cursor-pointer border-l-4 border-blue-500 group">
                        <h4 class="font-bold text-gray-800 mb-1 text-lg">{{ $task->title }}</h4>
                        <p class="text-sm text-gray-500 mb-3 line-clamp-2">{{ $task->description }}</p>
                        
                        <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-100">
                            <div class="flex items-center text-xs text-gray-400">
                                📅 {{ \Carbon\Carbon::parse($task->deadline_date)->format('d M') }}
                            </div>
                            <span class="text-[10px] font-bold px-2 py-1 rounded-full {{ $task->priority == 'High' ? 'bg-red-100 text-red-600' : 'bg-blue-100 text-blue-600' }}">
                                {{ $task->priority }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-blue-300 text-sm mt-10">Sedang kosong.</p>
                @endforelse
            </div>
        </div>

        <div class="flex flex-col">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-green-600 flex items-center">
                    <span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span> Done
                </h3>
                <span class="bg-green-100 text-green-600 text-xs px-2 py-1 rounded-full font-bold">{{ count($tasks['done']) }}</span>
            </div>
            
            <div class="flex-1 bg-green-50 rounded-xl p-4 border border-green-100 min-h-[400px] space-y-4">
                @forelse($tasks['done'] as $task)
                    <div class="bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition cursor-pointer border-l-4 border-green-500 opacity-60 group">
                        <h4 class="font-bold text-gray-800 mb-1 text-lg decoration-slice">{{ $task->title }}</h4>
                        <p class="text-sm text-gray-500 mb-3 line-clamp-2">{{ $task->description }}</p>
                        
                        <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-100">
                            <div class="flex items-center text-xs text-gray-400">
                                ✅ Selesai
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-green-400 text-sm mt-10">Ayo selesaikan!</p>
                @endforelse
            </div>
        </div>

    </div>
@endsection