@extends('layouts.app')

@section('title', 'Materi & Referensi')
@section('subtitle', 'Bagikan catatan kuliah dan cari referensi buku tambahan.')

@section('content')

{{-- ALERT SUCCESS/ERROR --}}
@if(session('success'))
<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm relative">
    <span>{{ session('success') }}</span>
    <button onclick="this.parentElement.remove()" class="absolute top-0 bottom-0 right-0 px-4 py-3"><i class="ph ph-x"></i></button>
</div>
@endif
@if(session('error'))
<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm relative">
    <span>{{ session('error') }}</span>
    <button onclick="this.parentElement.remove()" class="absolute top-0 bottom-0 right-0 px-4 py-3"><i class="ph ph-x"></i></button>
</div>
@endif

{{-- BAGIAN 1: HEADER & TOMBOL UPLOAD --}}
<div class="flex justify-between items-end mb-6">
    <div>
        <h2 class="text-xl font-bold text-gray-800">Catatan Kuliah</h2>
        <p class="text-sm text-gray-500">Materi yang diunggah oleh teman sekelas.</p>
    </div>
    <button onclick="document.getElementById('uploadMaterialModal').classList.remove('hidden')"
        class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg text-sm font-medium shadow-lg flex items-center gap-2 transition hover:-translate-y-0.5">
        <i class="ph ph-upload-simple text-lg"></i> Upload Catatan
    </button>
</div>

{{-- TABEL MATERI --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-12">
    <div class="overflow-x-auto">
        <table class="w-full table-fixed text-left border-collapse">
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase font-semibold">
                <tr>
                    <th class="w-[8%] px-6 py-4">Minggu</th>
                    <th class="w-[15%] px-6 py-4">Mata Kuliah</th>
                    <th class="w-[32%] px-6 py-4">Judul Catatan</th>
                    <th class="w-[20%] px-6 py-4">Pengunggah</th>
                    <th class="w-[25%] px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse($materials as $material)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 font-medium text-gray-600 align-middle">{{ $material->week }}</td>
                    <td class="px-6 py-4 align-middle">{{ $material->course }}</td>
                    <td class="px-6 py-4 font-bold text-gray-800 align-middle">
                        <div class="truncate w-full" title="{{ $material->title }}">
                            {{-- Link Preview File --}}
                            <a href="{{ route('materials.view', $material->id) }}" target="_blank" class="hover:text-indigo-600 hover:underline">
                                {{ $material->title }}
                            </a>
                        </div>
                    </td>
                    <td class="px-6 py-4 align-middle">
                        <div class="flex items-center gap-2">
                            {{-- Cek User ada atau tidak (untuk mencegah error jika user dihapus) --}}
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($material->user->name ?? 'Unknown') }}&background=random&size=24" class="rounded-full flex-shrink-0">
                            <span class="text-xs text-gray-500 truncate">{{ $material->user->name ?? 'User Terhapus' }}</span>
                            
                            @if(isset($material->user) && $material->user->role == 'admin')
                                <span class="bg-red-100 text-red-600 text-[10px] px-1 rounded font-bold">ADMIN</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center align-middle">
                        <div class="flex items-center justify-center gap-2">
                            
                            {{-- 1. TOMBOL DOWNLOAD --}}
                            <a href="{{ route('materials.download', $material->id) }}"
                               class="bg-indigo-50 text-indigo-600 hover:bg-indigo-100 p-2 rounded-lg transition" title="Unduh">
                                <i class="ph ph-download-simple text-lg"></i>
                            </a>

                            {{-- 2. TOMBOL EDIT (Hanya Pemilik) --}}
                            @if(Auth::id() == $material->user_id)
                            <button onclick="openEditModal('{{ $material->id }}', '{{ addslashes($material->course) }}', '{{ $material->week }}', '{{ addslashes($material->title) }}')"
                                    class="bg-yellow-50 text-yellow-600 hover:bg-yellow-100 p-2 rounded-lg transition" title="Edit">
                                <i class="ph ph-pencil-simple text-lg"></i>
                            </button>
                            @endif

                            {{-- 3. TOMBOL HAPUS (Admin ATAU Pemilik) --}}
                            @if(Auth::user()->role == 'admin' || Auth::id() == $material->user_id)
                            <form action="{{ route('materials.destroy', $material->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus catatan ini?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="bg-red-50 text-red-600 hover:bg-red-100 p-2 rounded-lg transition" title="Hapus">
                                    <i class="ph ph-trash text-lg"></i>
                                </button>
                            </form>
                            @endif

                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-8 text-gray-400">Belum ada catatan yang diunggah.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- BAGIAN 2: PENCARIAN REFERENSI EKSTERNAL --}}
<div class="border-t-2 border-dashed border-gray-200 pt-8 pb-12">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-6">
        <div class="flex items-start gap-4">
            <div class="bg-indigo-50 text-indigo-600 p-3 rounded-xl shrink-0">
                <i class="ph ph-books text-2xl"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-800 leading-tight">Cari Referensi Buku</h2>
                <p class="text-sm text-gray-500 mt-1 leading-snug">Cari buku tambahan dari Google Books.</p>
            </div>
        </div>
        <div class="w-full md:w-auto flex flex-col items-end">
            <div class="flex gap-2 w-full md:w-auto">
                <input type="text" id="apiSearchInput" placeholder="Contoh: Algoritma, Laravel..." 
                       class="border border-gray-300 rounded-lg px-4 py-2 w-full md:w-72 focus:ring-2 focus:ring-indigo-500 outline-none shadow-sm transition">
                <button onclick="searchExternalBooks()" id="btnApiSearch"
                        class="bg-gray-900 text-white px-5 py-2 rounded-lg font-bold hover:bg-black transition flex items-center gap-2 shadow-md">
                    <i class="ph ph-magnifying-glass"></i> Cari
                </button>
            </div>
            <div id="searchHistoryTags" class="flex gap-2 mt-2 flex-wrap justify-end min-h-[24px]"></div>
        </div>
    </div>

    <div id="apiResults" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 min-h-[100px]">
        <div class="col-span-full text-center py-10 text-gray-400 bg-gray-50 rounded-xl border border-gray-100">
            <i class="ph ph-magnifying-glass text-4xl mb-2 opacity-30"></i>
            <p>Hasil pencarian buku akan muncul di sini.</p>
        </div>
    </div>

    <div id="viewedHistoryContainer" class="mt-12 hidden">
        <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4 border-b pb-2">Terakhir Dilihat</h3>
        <div id="viewedHistoryList" class="flex gap-4 overflow-x-auto pb-4"></div>
    </div>
</div>

{{-- MODAL UPLOAD --}}
<div id="uploadMaterialModal" class="hidden fixed inset-0 z-[999] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl w-full max-w-md p-6 shadow-2xl transform transition-all">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-bold text-xl text-gray-800">Upload Catatan Baru</h3>
            <button onclick="document.getElementById('uploadMaterialModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <i class="ph ph-x text-xl"></i>
            </button>
        </div>
        <form action="{{ route('materials.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Mata Kuliah</label>
                    <input type="text" name="course" placeholder="Contoh: Pemrograman Web" required 
                           class="w-full border border-gray-300 p-3 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Minggu Ke-</label>
                        <input type="number" name="week" placeholder="1" required 
                               class="w-full border border-gray-300 p-3 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Judul Materi</label>
                        <input type="text" name="title" placeholder="Judul..." required 
                               class="w-full border border-gray-300 p-3 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">File (PDF/PPT/DOC)</label>
                    <div class="border-2 border-dashed border-gray-300 p-4 rounded-xl text-center bg-gray-50 hover:bg-gray-100 transition">
                        <input type="file" name="file" required 
                               class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-100 file:text-indigo-700 hover:file:bg-indigo-200">
                    </div>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="document.getElementById('uploadMaterialModal').classList.add('hidden')" class="flex-1 py-3 text-gray-500 font-bold hover:bg-gray-100 rounded-xl transition">Batal</button>
                    <button type="submit" class="flex-1 bg-indigo-600 text-white py-3 rounded-xl font-bold shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition">Upload</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDIT --}}
<div id="editMaterialModal" class="hidden fixed inset-0 z-[999] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl w-full max-w-md p-6 shadow-2xl transform transition-all">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-bold text-xl text-gray-800">Edit Catatan</h3>
            <button onclick="document.getElementById('editMaterialModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <i class="ph ph-x text-xl"></i>
            </button>
        </div>
        {{-- Form Edit --}}
        <form id="editMaterialForm" method="POST" enctype="multipart/form-data">
            @csrf 
            @method('PUT') 
            
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Mata Kuliah</label>
                    <input type="text" id="edit_course" name="course" required class="w-full border border-gray-300 p-3 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Minggu Ke-</label>
                        <input type="number" id="edit_week" name="week" required class="w-full border border-gray-300 p-3 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Judul Materi</label>
                        <input type="text" id="edit_title" name="title" required class="w-full border border-gray-300 p-3 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Ganti File (Opsional)</label>
                    <div class="border-2 border-dashed border-gray-300 p-4 rounded-xl text-center bg-gray-50 hover:bg-gray-100 transition">
                        <input type="file" name="file" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-yellow-100 file:text-yellow-700 hover:file:bg-yellow-200">
                    </div>
                    <p class="text-[10px] text-gray-400 mt-1">*Biarkan kosong jika tidak ingin mengganti file.</p>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="document.getElementById('editMaterialModal').classList.add('hidden')" class="flex-1 py-3 text-gray-500 font-bold hover:bg-gray-100 rounded-xl transition">Batal</button>
                    <button type="submit" class="flex-1 bg-yellow-500 text-white py-3 rounded-xl font-bold shadow-lg shadow-yellow-200 hover:bg-yellow-600 transition">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>


{{-- JAVASCRIPT --}}
<script>
    const currentUserId = "{{ Auth::id() }}";
    const searchKey = `searchHistory_${currentUserId}`; 
    const viewedKey = `viewedBooks_${currentUserId}`; 

    function openEditModal(id, course, week, title) {
        document.getElementById('edit_course').value = course;
        document.getElementById('edit_week').value = week;
        document.getElementById('edit_title').value = title;
        
        let form = document.getElementById('editMaterialForm');
        let url = "{{ route('materials.update', ':id') }}";
        form.action = url.replace(':id', id);

        document.getElementById('editMaterialModal').classList.remove('hidden');
    }

    function searchExternalBooks(keyword = null) {
        let input = document.getElementById('apiSearchInput');
        let query = keyword || input.value;
        let container = document.getElementById('apiResults');
        let btn = document.getElementById('btnApiSearch');

        if (!query) return alert("Ketik kata kunci pencarian dulu!");

        if (keyword) input.value = keyword;
        saveSearchHistory(query);

        btn.innerHTML = '<i class="ph ph-spinner animate-spin"></i> ...';
        btn.disabled = true;
        container.innerHTML = '<p class="col-span-full text-center text-gray-500">Sedang mencari...</p>';

        fetch(`https://www.googleapis.com/books/v1/volumes?q=${encodeURIComponent(query)}&maxResults=4`)
            .then(response => response.json())
            .then(data => {
                container.innerHTML = ''; 
                if (data.items && data.items.length > 0) {
                    data.items.forEach(book => {
                        let info = book.volumeInfo;
                        let thumb = info.imageLinks ? info.imageLinks.thumbnail : 'https://via.placeholder.com/128x196?text=No+Cover';
                        let authors = info.authors ? info.authors.join(', ') : 'Unknown';
                        let link = info.previewLink || '#';
                        let title = info.title || 'No Title';
                        
                        let safeTitle = title.replace(/'/g, "").replace(/"/g, "");

                        let card = `
                            <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition flex flex-col h-full">
                                <div class="h-40 bg-gray-100 rounded-lg mb-4 flex items-center justify-center overflow-hidden">
                                    <img src="${thumb}" class="h-full object-contain">
                                </div>
                                <h4 class="font-bold text-gray-800 line-clamp-2 text-sm mb-1" title="${title}">${title}</h4>
                                <p class="text-xs text-gray-500 mb-4 line-clamp-1">${authors}</p>
                                <a href="${link}" target="_blank" 
                                   onclick="saveViewedBook('${safeTitle}', '${thumb}', '${link}')"
                                   class="mt-auto block text-center bg-blue-50 text-blue-600 text-xs font-bold py-2 rounded-lg hover:bg-blue-100 transition">
                                    Lihat di Google Books
                                </a>
                            </div>
                        `;
                        container.innerHTML += card;
                    });
                } else {
                    container.innerHTML = '<p class="col-span-full text-center text-red-400">Tidak ditemukan.</p>';
                }
            })
            .catch(err => {
                console.error(err);
                container.innerHTML = '<p class="col-span-full text-center text-red-400">Error mengambil data.</p>';
            })
            .finally(() => {
                btn.innerHTML = '<i class="ph ph-magnifying-glass"></i> Cari';
                btn.disabled = false;
            });
    }

    // === LOGIKA RIWAYAT PENCARIAN ===
    function saveSearchHistory(query) {
        let history = JSON.parse(localStorage.getItem(searchKey)) || [];
        history = history.filter(item => item.toLowerCase() !== query.toLowerCase());
        history.unshift(query);
        if (history.length > 5) history.pop();
        localStorage.setItem(searchKey, JSON.stringify(history));
        renderSearchHistory();
    }

    function renderSearchHistory() {
        let history = JSON.parse(localStorage.getItem(searchKey)) || [];
        let container = document.getElementById('searchHistoryTags');
        container.innerHTML = '';
        if (history.length > 0) {
            container.innerHTML = '<span class="text-xs text-gray-400 mr-2 self-center">Riwayat:</span>';
            history.forEach(tag => {
                container.innerHTML += `<button onclick="searchExternalBooks('${tag}')" class="bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs px-2 py-1 rounded-full transition mx-1">${tag}</button>`;
            });
        }
    }

    // === LOGIKA RIWAYAT DILIHAT ===
    function saveViewedBook(title, thumb, link) {
        let books = JSON.parse(localStorage.getItem(viewedKey)) || [];
        books = books.filter(b => b.title !== title);
        books.unshift({ title, thumb, link });
        if (books.length > 5) books.pop();
        localStorage.setItem(viewedKey, JSON.stringify(books));
        renderViewedBooks();
    }

    function renderViewedBooks() {
        let books = JSON.parse(localStorage.getItem(viewedKey)) || [];
        let container = document.getElementById('viewedHistoryList');
        let wrapper = document.getElementById('viewedHistoryContainer');
        
        if (books.length > 0) {
            wrapper.classList.remove('hidden');
            container.innerHTML = '';
            books.forEach(book => {
                container.innerHTML += `
                    <div class="min-w-[120px] w-[120px] bg-white p-2 rounded-lg border border-gray-100 flex flex-col items-center text-center shadow-sm">
                        <img src="${book.thumb}" class="h-20 object-contain mb-2 rounded">
                        <p class="text-[10px] font-bold text-gray-700 line-clamp-2 leading-tight mb-2">${book.title}</p>
                        <a href="${book.link}" target="_blank" class="text-[10px] text-blue-500 hover:underline">Buka Lagi</a>
                    </div>
                `;
            });
        } else {
            wrapper.classList.add('hidden');
        }
    }

    // === 4. INITIALIZE ===
    document.addEventListener('DOMContentLoaded', function() {
        renderSearchHistory();
        renderViewedBooks();
        document.getElementById('apiSearchInput').addEventListener('keypress', function (e) {
            if (e.key === 'Enter') searchExternalBooks();
        });
        
        window.addEventListener('click', function(e) {
            const upModal = document.getElementById('uploadMaterialModal');
            const editModal = document.getElementById('editMaterialModal');
            if (e.target == upModal) upModal.classList.add('hidden');
            if (e.target == editModal) editModal.classList.add('hidden');
        });
    });
</script>

@endsection