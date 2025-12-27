<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - StudyHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Kustomisasi warna sesuai desain */
        .bg-sidebar-active { background-color: #818CF8; /* Warna ungu/biru untuk menu aktif */ }
        .text-sidebar-active { color: #ffffff; }
    </style>
</head>
<body class="bg-gray-100 font-sans antialiased">

    <div class="flex h-screen overflow-hidden">

        <aside class="w-64 bg-white border-r border-gray-200 flex-shrink-0 fixed h-full flex flex-col justify-between">
            <div>
                <div class="p-6">
                    <h1 class="text-2xl font-bold text-blue-600">SI4807 <span class="block text-blue-900">StudyHub</span></h1>
                </div>

                <div class="px-6 mb-6 text-center">
                    <img class="h-20 w-20 rounded-full mx-auto mb-2 border-4 border-gray-200" src="https://i.pravatar.cc/150?img=12" alt="User Avatar">
                    <h2 class="font-bold text-lg">{{ Auth::user()->name ?? 'Nama Mahasiswa' }}</h2>
                    <p class="text-sm text-gray-500">{{ Auth::user()->role ?? 'mahasiswa' }}</p>
                    <p class="text-sm text-gray-500">{{ Auth::user()->nim ?? 'NIM' }}</p>
                </div>

                <nav class="mt-6">
                    <a href="{{ route('dashboard') }}" class="flex items-center px-6 py-3 bg-sidebar-active text-sidebar-active font-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h12a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6z" /></svg>
                        Dashboard
                    </a>
                    <a href="#" class="flex items-center px-6 py-3 hover:bg-gray-100 text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.747 0-3.332.477-4.5 1.253" /></svg>
                        Class
                    </a>
                    <a href="#" class="flex items-center px-6 py-3 hover:bg-gray-100 text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                        Courses
                    </a>
                    <a href="#" class="flex items-center px-6 py-3 hover:bg-gray-100 text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                        Groups
                    </a>
                    <a href="#" class="flex items-center px-6 py-3 hover:bg-gray-100 text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                        Tasks
                    </a>
                    <a href="#" class="flex items-center px-6 py-3 hover:bg-gray-100 text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                        Notes
                    </a>
                </nav>
            </div>

            <div class="mb-6">
                 <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center w-full px-6 py-3 hover:bg-red-50 text-red-600 font-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <main class="flex-1 ml-64 p-8 overflow-y-auto h-screen">
            @yield('content')
        </main>

    </div>
</body>
</html>