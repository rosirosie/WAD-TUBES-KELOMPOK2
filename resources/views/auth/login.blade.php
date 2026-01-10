<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SI4807 StudyHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-white h-screen flex overflow-hidden">

    <div class="w-full lg:w-1/2 flex flex-col justify-center px-8 md:px-24 lg:px-32 bg-white z-10">
        
        <div class="mb-10">
            <h1 class="text-3xl font-bold text-[#1e1b4b] leading-tight">
                SI4807 <span class="text-[#6366f1]">StudyHub</span>
            </h1>
            <p class="text-gray-500 mt-2 text-sm">Masuk untuk mengelola jadwal dan tugas Anda.</p>
        </div>

        <form action="{{ route('login') }}" method="POST" class="space-y-6">
            @csrf
            
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Mahasiswa</label>
                <input type="email" name="email" id="email" placeholder="nama@student.telkomuniversity.ac.id" required
                    class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-[#6366f1] focus:ring focus:ring-[#6366f1]/20 transition outline-none text-gray-800 placeholder-gray-400">
            </div>

            <div>
                <div class="flex items-center justify-between mb-2">
                    <label for="password" class="block text-sm font-medium text-gray-700">Kata Sandi</label>
                    <a href="#" class="text-sm font-medium text-[#6366f1] hover:text-[#4338ca]">Lupa sandi?</a>
                </div>
                <input type="password" name="password" id="password" placeholder="••••••••" required
                    class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-[#6366f1] focus:ring focus:ring-[#6366f1]/20 transition outline-none text-gray-800 placeholder-gray-400">
            </div>

            <div class="flex items-center">
                <input id="remember-me" name="remember-me" type="checkbox" 
                    class="h-4 w-4 text-[#6366f1] focus:ring-[#6366f1] border-gray-300 rounded">
                <label for="remember-me" class="ml-2 block text-sm text-gray-600">
                    Ingat saya
                </label>
            </div>

            <button type="submit" 
                class="w-full bg-[#6366f1] hover:bg-[#4338ca] text-white font-semibold py-3 rounded-xl transition shadow-lg shadow-indigo-500/30">
                Masuk Sekarang
            </button>

            <p class="text-center text-sm text-gray-600">
                Belum punya akun? 
                <a href="{{ route('register') }}" class="font-semibold text-[#6366f1] hover:text-[#4338ca] transition">Daftar disini</a>
            </p>
        </form>
    </div>

    <div class="hidden lg:flex w-1/2 bg-[#6366f1] items-center justify-center relative overflow-hidden">
        <div class="absolute top-10 left-10 w-20 h-20 bg-white/10 rounded-full blur-xl"></div>
        <div class="absolute bottom-20 right-20 w-64 h-64 bg-indigo-500/30 rounded-full blur-3xl"></div>

        <div class="text-center text-white z-10 px-16">
            <h2 class="text-4xl font-bold mb-4">Productivity Booster</h2>
            <p class="text-indigo-100 text-lg leading-relaxed">
                Satu platform untuk sinkronisasi jadwal, tugas, dan kelompok studi kelas SI4807.
            </p>
            
            <div class="mt-10 bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl p-6 shadow-2xl transform rotate-3 hover:rotate-0 transition duration-500">
                <div class="flex items-center gap-3 mb-4 border-b border-white/10 pb-4">
                    <div class="w-3 h-3 rounded-full bg-red-400"></div>
                    <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                    <div class="w-3 h-3 rounded-full bg-green-400"></div>
                </div>
                <div class="space-y-3">
                    <div class="h-4 bg-white/20 rounded w-3/4"></div>
                    <div class="h-4 bg-white/20 rounded w-1/2"></div>
                    <div class="h-20 bg-white/10 rounded w-full mt-4"></div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>