<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SI4807 StudyHub')</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #c7c7c7; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #a8a8a8; }
    </style>
</head>
<body class="bg-[#f8fafc] flex h-screen overflow-hidden">

    <aside class="w-72 bg-white border-r border-gray-200 flex-shrink-0 flex flex-col justify-between h-full z-10 font-inter">
        
        <div>
            <div class="flex justify-center items-center py-8">
                <h1 class="text-2xl font-bold text-[#1e1b4b] text-center leading-tight">
                    SI4807 <br>
                    <span class="text-[#6366f1]">StudyHub</span>
                </h1>
            </div>

            <div class="flex flex-col items-center mt-2 mb-8 px-6">
                <div class="w-20 h-20 rounded-full overflow-hidden border-2 border-gray-100 mb-2">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random" alt="Profile" class="w-full h-full object-cover">
                </div>
                
                <h2 class="font-bold text-lg text-gray-800 text-center">{{ Auth::user()->name }}</h2>
                
                <p class="text-xs text-gray-500 uppercase tracking-wide mt-1">{{ Auth::user()->role }}</p>
                
                <p class="text-xs font-semibold text-gray-700 mt-1">{{ Auth::user()->nim }}</p>
            </div>

            <nav class="px-6 space-y-2">
                
                <a href="{{ route('dashboard') }}" 
                    class="flex items-center space-x-3 px-4 py-3 rounded-xl transition {{ Request::routeIs('dashboard') ? 'bg-[#6366f1] text-white shadow-md font-semibold' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700 font-medium' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    <span>Dashboard</span>
                </a>

                @if(strtolower(Auth::user()->role) == 'admin')
                <a href="{{ route('announcements.create') }}" 
                    class="flex items-center space-x-3 px-4 py-3 rounded-xl transition text-gray-500 hover:bg-red-50 hover:text-red-600 font-medium group {{ Request::routeIs('announcements*') ? 'bg-red-50 text-red-600 font-semibold' : '' }}">
                    <svg class="w-5 h-5 group-hover:text-red-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                    </svg>
                    <span class="flex-1">Buat Pengumuman</span>
                    <span class="bg-red-100 text-red-600 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase">Admin</span>
                </a>
                @endif
                
                <a href="{{ url('/schedules') }}" 
                    class="flex items-center space-x-3 px-4 py-3 rounded-xl transition {{ Request::is('schedules*') ? 'bg-[#6366f1] text-white shadow-md font-semibold' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700 font-medium' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    <span>Schedule</span>
                </a>

                <a href="{{ url('/groups') }}" 
                    class="flex items-center space-x-3 px-4 py-3 rounded-xl transition {{ Request::is('groups*') ? 'bg-[#6366f1] text-white shadow-md font-semibold' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700 font-medium' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <span>Groups</span>
                </a>

                <a href="{{ url('/tasks') }}" 
                    class="flex items-center space-x-3 px-4 py-3 rounded-xl transition {{ Request::is('tasks*') ? 'bg-[#6366f1] text-white shadow-md font-semibold' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700 font-medium' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    <span>Tasks</span>
                </a>

                <a href="{{ url('/materials') }}" 
                    class="flex items-center space-x-3 px-4 py-3 rounded-xl transition {{ Request::is('materials*') ? 'bg-[#6366f1] text-white shadow-md font-semibold' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700 font-medium' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    <span>Materials</span>
                </a>
            </nav>
        </div>

        <div class="p-4 border-t border-gray-100 px-6">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="flex items-center space-x-3 px-4 py-2 text-gray-500 hover:text-red-500 transition font-medium w-full">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 overflow-y-auto p-8">
        @yield('content')
    </main>
</body>
</html>