<div id="uploadMaterialModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all">
        <div class="bg-indigo-600 p-4 flex justify-between items-center text-white">
            <h3 class="font-bold flex items-center gap-2">
                <i class="ph ph-upload-simple text-xl"></i> Unggah Materi Baru
            </h3>
            <button onclick="document.getElementById('uploadMaterialModal').classList.add('hidden')" class="hover:bg-indigo-500 rounded-full p-1">
                <i class="ph ph-x text-xl"></i>
            </button>
        </div>

        <form action="{{ route('materials.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Mata Kuliah</label>
                <input type="text" name="course" required placeholder="Contoh: Statistika Industri" 
                       class="w-full border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 text-sm">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Minggu Ke-</label>
                    <input type="number" name="week" required min="1" max="16" placeholder="1-16"
                           class="w-full border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Visibilitas</label>
                    <select name="visibility" class="w-full border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                        <option value="public">Publik</option>
                        <option value="private">Privat (Hanya Saya)</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Judul Materi</label>
                <input type="text" name="title" required placeholder="Contoh: Modul Pertemuan 1" 
                       class="w-full border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 text-sm">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">File (PDF/PPT/DOC)</label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl hover:border-indigo-400 transition-colors">
                    <div class="space-y-1 text-center">
                        <i class="ph ph-file-arrow-up text-3xl text-gray-400"></i>
                        <div class="flex text-sm text-gray-600">
                            <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500">
                                <span>Pilih file</span>
                                <input id="file-upload" name="file" type="file" class="sr-only" required>
                            </label>
                        </div>
                        <p class="text-xs text-gray-500">Maksimal 10MB</p>
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-xl shadow-lg transition duration-200 flex items-center justify-center gap-2">
                <i class="ph ph-paper-plane-tilt"></i> Simpan Materi
            </button>
        </form>
    </div>
</div>

<div id="qrModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden p-6 text-center">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-bold text-gray-800 text-lg">Scan untuk Download</h3>
            <button onclick="document.getElementById('qrModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <i class="ph ph-x text-2xl"></i>
            </button>
        </div>

        <div id="qrCodeContainer" class="bg-gray-50 p-4 rounded-xl flex justify-center mb-4 border border-gray-100">
            <div id="qrcode"></div>
        </div>

        <p id="qrSubtitle" class="text-sm font-semibold text-indigo-600 truncate"></p>
        <p class="text-xs text-gray-400 mt-1">Arahkan kamera ponsel Anda ke kode QR di atas.</p>
    </div>
</div>