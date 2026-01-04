<div id="qrModal" class="fixed inset-0 bg-black bg-opacity-60 hidden z-50 flex items-center justify-center backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 relative">
        <button onclick="closeModal('qrModal')" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
            <i class="ph ph-x text-xl"></i>
        </button>
        
        <div class="text-center">
            <h3 class="text-lg font-bold text-gray-900 mb-1">Scan to Download</h3>
            <p class="text-xs text-gray-500 mb-4" id="qrSubtitle">Materi...</p>
            
            <div class="bg-white p-2 rounded-xl border-2 border-dashed border-indigo-200 inline-block mb-4">
                <img id="qrImage" src="" alt="Generating QR..." class="w-48 h-48 object-contain">
            </div>

            <p class="text-xs text-gray-400 mb-5 px-4">
                Arahkan kamera HP Anda ke kode di atas untuk mengunduh materi ini secara langsung.
            </p>

            <a id="downloadLink" href="#" target="_blank" class="block w-full bg-indigo-600 text-white py-2.5 rounded-lg text-sm font-medium hover:bg-indigo-700 transition">
                Download Manual (Link)
            </a>
        </div>
    </div>
</div>

<div id="uploadModal" class="fixed inset-0 bg-black bg-opacity-60 hidden z-50 flex items-center justify-center backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 relative">
        <h3 class="text-xl font-bold text-gray-900 mb-4">Upload Materi Baru</h3>
        
        <form action="{{ route('materials.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Mata Kuliah</label>
                <select name="course" class="w-full bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option>Statistika Industri</option>
                    <option>Perancangan Interaksi</option>
                    <option>Sistem Operasi</option>
                    <option>Pemrograman Web</option>
                </select>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Minggu Ke</label>
                    <input type="text" name="week" placeholder="Minggu 1..." class="w-full bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-sm" required>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Visibilitas</label>
                    <select name="visibility" class="w-full bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-sm">
                        <option value="public">Publik</option>
                        <option value="private">Pribadi</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Judul Materi</label>
                <input type="text" name="title" placeholder="Contoh: Bab 1 Pendahuluan" class="w-full bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-sm" required>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">File (PDF/PPT)</label>
                <input type="file" name="file" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" required>
            </div>
            
            <div class="pt-2 flex gap-3">
                <button type="button" onclick="closeModal('uploadModal')" class="flex-1 bg-gray-100 text-gray-700 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-200 transition">Batal</button>
                <button type="submit" class="flex-1 bg-indigo-600 text-white py-2.5 rounded-lg text-sm font-medium hover:bg-indigo-700 transition">Upload</button>
            </div>
        </form>
    </div>
</div>