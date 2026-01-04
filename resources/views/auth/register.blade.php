<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - SI4807 StudyHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-white h-screen flex overflow-hidden">

    <div class="w-full lg:w-1/2 flex flex-col justify-center px-8 md:px-24 lg:px-32 bg-white z-10 overflow-y-auto">
        
        <div class="py-8"> <div class="mb-8">
                <h1 class="text-3xl font-bold text-[#1e1b4b] leading-tight">
                    Buat Akun Baru
                </h1>
                <p class="text-gray-500 mt-2 text-sm">Bergabung dengan teman sekelas di SI4807 StudyHub.</p>
            </div>

            <form action="{{ route('register') }}" method="POST" class="space-y-5">
                @csrf
                
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                    <input type="text" name="name" id="name" placeholder="Contoh: Dhimas Mahardika" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-[#6366f1] focus:ring focus:ring-[#6366f1]/20 transition outline-none text-gray-800 placeholder-gray-400">
                </div>

                <div>
                    <label for="nim" class="block text-sm font-medium text-gray-700 mb-2">NIM (Nomor Induk Mahasiswa)</label>
                    <input type="number" name="nim" id="nim" placeholder="102022xxxxxx" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-[#6366f1] focus:ring focus:ring-[#6366f1]/20 transition outline-none text-gray-800 placeholder-gray-400">
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Mahasiswa</label>
                    <input type="email" name="email" id="email" placeholder="nama@student.telkomuniversity.ac.id" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-[#6366f1] focus:ring focus:ring-[#6366f1]/20 transition outline-none text-gray-800 placeholder-gray-400">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Kata Sandi</label>
                    <input type="password" name="password" id="password" placeholder="Min. 8 karakter" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-[#6366f1] focus:ring focus:ring-[#6366f1]/20 transition outline-none text-gray-800 placeholder-gray-400">
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Kata Sandi</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Ulangi kata sandi" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-[#6366f1] focus:ring focus:ring-[#6366f1]/20 transition outline-none text-gray-800 placeholder-gray-400">
                </div>

                <button type="submit" 
                    class="w-full bg-[#6366f1] hover:bg-[#4338ca] text-white font-semibold py-3 rounded-xl transition shadow-lg shadow-indigo-500/30 mt-2">
                    Daftar Akun
                </button>

                <p class="text-center text-sm text-gray-600">
                    Sudah punya akun? 
                    <a href="{{ route('login') }}" class="font-semibold text-[#6366f1] hover:text-[#4338ca] transition">Masuk disini</a>
                </p>
            </form>
        </div>
    </div>

    <div class="hidden lg:flex w-1/2 bg-[#6366f1] flex-col items-center justify-center relative overflow-hidden text-center px-16">
        <div class="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-indigo-900/20 rounded-full blur-2xl translate-y-1/2 -translate-x-1/2"></div>

        <h2 class="text-3xl font-bold text-white mb-6 relative z-10">Tingkatkan IPK Bersama!</h2>
        <div class="space-y-4 text-indigo-100 text-lg relative z-10">
            <div class="flex items-center gap-4 bg-white/10 p-4 rounded-xl backdrop-blur-sm border border-white/10">
                <div class="bg-white/20 p-2 rounded-lg"><svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
                <div class="text-left">
                    <p class="font-bold text-white text-sm">Anti Lupa Jadwal</p>
                    <p class="text-xs opacity-80">Notifikasi jadwal kuliah real-time</p>
                </div>
            </div>

            <div class="flex items-center gap-4 bg-white/10 p-4 rounded-xl backdrop-blur-sm border border-white/10">
                <div class="bg-white/20 p-2 rounded-lg"><svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg></div>
                <div class="text-left">
                    <p class="font-bold text-white text-sm">Tugas Terpantau</p>
                    <p class="text-xs opacity-80">List tugas prioritas otomatis</p>
                </div>
            </div>
        </div>
    </div>

</body>
</html>