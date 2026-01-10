@extends('layouts.app')

@section('title', 'Course Materials')
@section('subtitle', 'Repositori materi kuliah dan bahan ajar')

@section('content')

<div class="flex justify-end mb-6">
    <button onclick="document.getElementById('uploadMaterialModal').classList.remove('hidden')"
        class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg text-sm font-medium shadow-lg flex items-center gap-2 transition hover:-translate-y-0.5">
        <i class="ph ph-plus-circle text-lg"></i> + Bagikan Catatan
    </button>
</div>

{{-- Table Materials --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse whitespace-nowrap">
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase font-semibold">
                <tr>
                    <th class="px-6 py-4">Minggu</th>
                    <th class="px-6 py-4">Mata Kuliah</th>
                    <th class="px-6 py-4">Materi</th>
                    <th class="px-6 py-4">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse($materials as $material)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">{{ $material->week }}</td>
                    <td class="px-6 py-4">{{ $material->course }}</td>
                    <td class="px-6 py-4">
                        <span class="bg-blue-50 text-blue-700 px-2 py-1 rounded text-xs font-bold">{{ $material->title }}</span>
                    </td>
                    <td class="px-6 py-4">
                        
                        {{-- TOMBOL UTAMA (VERSI CLEAN / DATA ATTRIBUTES) --}}
                        <button type="button"
                            {{-- Simpan data di atribut HTML --}}
                            data-title="{{ $material->title }}"
                            data-course="{{ $material->course }}"
                            data-url="{{ url('storage/' . $material->file_path) }}"
                            
                            {{-- Panggil fungsi JS dengan 'this' --}}
                            onclick="openQrModal(this)"
                            
                            class="text-indigo-600 hover:text-indigo-800 font-bold flex items-center gap-1">
                            <i class="ph ph-qr-code"></i> Get File
                        </button>

                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-10">Belum ada materi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- MODAL QR CODE --}}
<div id="qrModal" class="hidden fixed inset-0 z-[999] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-xs p-6 text-center">
        <h3 class="font-bold text-gray-800 text-lg mb-1">Scan QR Code</h3>
        <p id="qrSubtitle" class="text-xs text-gray-400 mb-4"></p>

        <div class="bg-gray-50 p-4 rounded-2xl mb-4 border-2 border-dashed border-gray-200 flex justify-center">
            <img id="qrImage" src="" alt="QR Code" class="w-48 h-48 rounded-lg">
        </div>

        <div class="flex flex-col gap-2">
            <a id="downloadLink" href="#" download class="bg-indigo-600 text-white py-2.5 rounded-xl text-sm font-bold shadow-indigo-200 shadow-lg">Download File</a>
            <button type="button" onclick="document.getElementById('qrModal').classList.add('hidden')" class="text-gray-400 text-xs py-2">Tutup</button>
        </div>
    </div>
</div>

{{-- MODAL UPLOAD --}}
<div id="uploadMaterialModal" class="hidden fixed inset-0 z-[999] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl w-full max-w-md p-6">
        <form action="{{ route('materials.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="space-y-4">
                <input type="text" name="course" placeholder="Mata Kuliah" required class="w-full border p-3 rounded-xl">
                <input type="number" name="week" placeholder="Minggu" required class="w-full border p-3 rounded-xl">
                <input type="text" name="title" placeholder="Judul" required class="w-full border p-3 rounded-xl">
                <input type="file" name="file" required class="w-full">
                <input type="hidden" name="visibility" value="public">
                <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-xl font-bold">Upload</button>
                <button type="button" onclick="document.getElementById('uploadMaterialModal').classList.add('hidden')" class="w-full text-gray-400">Batal</button>
            </div>
        </form>
    </div>
</div>

{{-- SCRIPT JAVASCRIPT DIPERBARUI --}}
<script>
    function openQrModal(button) {
        // 1. Ambil data dari atribut tombol
        const title = button.getAttribute('data-title');
        const course = button.getAttribute('data-course');
        const fileUrl = button.getAttribute('data-url');

        // Debugging (Opsional)
        console.log("Membuka modal untuk:", title);

        const modal = document.getElementById('qrModal');
        const qrImg = document.getElementById('qrImage');
        const subtitle = document.getElementById('qrSubtitle');
        const link = document.getElementById('downloadLink');

        // 2. Set Data ke Modal
        subtitle.innerText = course + " - " + title;
        link.href = fileUrl;

        // 3. Generate QR via API
        qrImg.src = `https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=${encodeURIComponent(fileUrl)}&color=4338ca&margin=10`;

        // 4. Tampilkan Modal
        modal.classList.remove('hidden');
    }

    // Klik luar modal untuk tutup
    window.addEventListener('click', function(e) {
        const qrModal = document.getElementById('qrModal');
        const upModal = document.getElementById('uploadMaterialModal');
        if (e.target == qrModal) qrModal.classList.add('hidden');
        if (e.target == upModal) upModal.classList.add('hidden');
    });
</script>

@endsection