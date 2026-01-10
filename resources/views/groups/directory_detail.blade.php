@extends('layouts.app')

@section('title', 'Directory - SI4807 StudyHub')

@section('content')
<div class="p-2">
    {{-- Breadcrumb & Header --}}
    <div class="mb-8">
        <h1 class="text-xl font-bold text-gray-800 flex items-center gap-2">
            Groups <span class="text-gray-400 text-sm">▶</span> Master Group Directory
        </h1>
        <p class="text-sm text-gray-400 mt-1">Daftar semua kelompok untuk mata kuliah: <span class="font-bold text-indigo-600">{{ $subject }}</span></p>
    </div>

    {{-- Action Bar --}}
    <div class="mb-8 flex justify-between items-center">
        <a href="{{ route('groups.directory') }}" class="text-sm text-indigo-600 hover:underline flex items-center gap-1">
            <span>&larr;</span> Kembali ke Halaman Groups
        </a>
        
        <div class="flex gap-3">
            {{-- TOMBOL EXPORT EXCEL --}}
            <a href="{{ route('groups.export') }}" class="bg-white border border-gray-200 text-gray-700 px-6 py-2.5 rounded-lg font-bold shadow-sm text-sm flex items-center gap-2 transition hover:bg-gray-50 border-b-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Export Excel
            </a>

            <button onclick="document.getElementById('createModal').classList.remove('hidden')" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-lg font-semibold shadow-lg shadow-indigo-200 text-sm flex items-center gap-2 transition transform hover:scale-105">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Create new group
            </button>
        </div>
    </div>

    {{-- Alert Success --}}
    @if(session('success'))
    <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 text-green-700 rounded-r-lg shadow-sm flex justify-between items-center">
        <p>{{ session('success') }}</p>
        <button onclick="this.parentElement.remove()" class="text-green-500 hover:text-green-700">&times;</button>
    </div>
    @endif

    {{-- Table Section --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-white">
            <h2 class="font-bold text-[#5C6AC4] text-lg">Master Group Directory: {{ $subject }}</h2>
            <div class="relative">
                <input type="text" id="tableSearch" placeholder="Cari Kelompok..." class="pl-9 pr-4 py-2 border border-gray-200 rounded-full text-sm bg-gray-50 focus:outline-none focus:border-indigo-500 w-64 transition">
                <span class="absolute left-3 top-2.5 text-gray-400 text-xs">🔍</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-sm text-left">
                <thead class="bg-gray-100 text-gray-600 font-bold border-b border-gray-200">
                    <tr>
                        <th class="p-4 w-16 text-center border-r border-gray-200">No</th>
                        <th class="p-4 border-r border-gray-200">Kelompok</th>
                        <th class="p-4 border-r border-gray-200">Ketua Kelompok</th>
                        <th class="p-4 border-r border-gray-200">Anggota Kelompok</th>
                        <th class="p-4 border-r border-gray-200">Tema</th>
                        <th class="p-4 text-center w-32">Aksi</th> {{-- Lebar kolom aksi disesuaikan --}}
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @forelse($teams as $index => $team)
                    <tr class="bg-white border-b border-gray-200 hover:bg-indigo-50/30 transition-colors group">
                        <td class="p-4 text-center font-medium border-r border-gray-200 align-top">{{ $index + 1 }}</td>
                        
                        {{-- KOLOM KELOMPOK --}}
                        <td class="p-4 border-r border-gray-200 align-top">
                            <div class="flex items-center gap-3">
                                <img 
                                    src="https://api.dicebear.com/7.x/identicon/svg?seed={{ urlencode($team->name) }}&backgroundColor=b6e3f4,c0aede,d1d4f9" 
                                    alt="Icon Group"
                                    class="w-10 h-10 rounded-lg shadow-sm bg-gray-50"
                                >
                                <a href="{{ route('groups.progress', $team->id) }}" class="font-bold text-gray-800 hover:text-indigo-600 hover:underline">
                                    {{ $team->name }}
                                </a>
                            </div>
                        </td>

                        <td class="p-4 font-medium border-r border-gray-200 align-top text-gray-900">
                            {{ $team->leader_name }}
                        </td>

                        {{-- KOLOM ANGGOTA --}}
                        <td class="p-4 text-gray-600 border-r border-gray-200 align-top">
                            <ul class="list-none space-y-2">
                                @if(is_array($team->members) || is_object($team->members))
                                    @foreach($team->members as $member)
                                        <li class="flex items-center gap-2">                                     
                                            <span>{{ $member }}</span>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </td>

                        <td class="p-4 align-top border-r border-gray-200">
                            <span class="bg-gray-100 border border-gray-200 text-gray-600 px-3 py-1 rounded text-xs font-medium">
                                {{ $team->topic ?? 'Manajemen Kelas' }}
                            </span>
                        </td>

                        {{-- KOLOM AKSI (EDIT & HAPUS) --}}
                        <td class="p-4 align-top text-center">
                            <div class="flex items-center justify-center gap-2">
                                
                                {{-- TOMBOL EDIT --}}
                                <button type="button" 
                                        data-id="{{ $team->id }}"
                                        data-name="{{ $team->name }}"
                                        data-topic="{{ $team->topic }}"
                                        data-members="{{ json_encode($team->members) }}" {{-- Kirim array members --}}
                                        onclick="openGroupEditModal(this)"
                                        class="group text-yellow-500 hover:text-yellow-600 hover:bg-yellow-50 p-2 rounded-lg transition-all duration-200" 
                                        title="Edit Kelompok">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>

                                {{-- TOMBOL HAPUS --}}
                            @if(strtolower(Auth::user()->role) === 'admin' || Auth::user()->name === $team->leader_name)
    
                            <form action="{{ route('groups.destroy', $team->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kelompok ini? Data progress juga akan hilang.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="group text-red-500 hover:text-red-700 hover:bg-red-50 p-2 rounded-lg transition-all duration-200" title="Hapus Kelompok">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 group-hover:scale-110 transition-transform">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>
                              </button>
                            </form>

@endif
                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-10 text-center text-gray-400 italic">Belum ada data kelompok untuk mata kuliah ini.</td>
                    </tr>
                    @endforelse

                    {{-- Spacer Rows --}}
                    @php $remaining = max(0, 5 - count($teams)); @endphp
                    @for($i = 0; $i < $remaining; $i++)
                    <tr class="bg-white border-b border-gray-200 h-16 spacer-row">
                        <td class="border-r border-gray-200"></td>
                        <td class="border-r border-gray-200"></td>
                        <td class="border-r border-gray-200"></td>
                        <td class="border-r border-gray-200"></td>
                        <td class="border-r border-gray-200"></td>
                        <td></td>
                    </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL CREATE (Tetap Ada) --}}
<div id="createModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-lg p-6 max-h-[90vh] overflow-y-auto">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Buat Kelompok Baru</h2>
        
        <form action="{{ route('groups.store') }}" method="POST">
            @csrf
            
            <input type="hidden" name="group_id" value="{{ $group_id ?? 1 }}">
            <input type="hidden" name="leader_name" value="{{ Auth::user()->name ?? 'User' }}">

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Kelompok</label>
                    <input type="text" name="name" placeholder="Contoh: Kelompok 1" required class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Anggota Kelompok</label>
                    <div class="space-y-2">
                        <input type="text" value="{{ Auth::user()->name ?? 'User' }} (Ketua)" readonly class="w-full border bg-gray-100 text-gray-500 rounded-lg px-4 py-2 text-sm">
                        <input type="hidden" name="members[]" value="{{ Auth::user()->name ?? 'User' }}">
                        
                        <input type="text" name="members[]" placeholder="Nama Anggota 2" required class="w-full border rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-indigo-500">
                        <input type="text" name="members[]" placeholder="Nama Anggota 3" class="w-full border rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-indigo-500">
                        <input type="text" name="members[]" placeholder="Nama Anggota 4" class="w-full border rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Topik Proyek</label>
                    <input type="text" name="topic" placeholder="Judul Proyek..." required class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('createModal').classList.add('hidden')" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg">Batal</button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-semibold shadow-md">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDIT (Baru) --}}
<div id="editGroupModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-lg p-6 max-h-[90vh] overflow-y-auto">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Edit Data Kelompok</h2>
        
        <form id="editGroupForm" method="POST">
            @csrf
            @method('PUT') {{-- Method PUT untuk Update --}}

            <div class="space-y-4">
                {{-- Nama Kelompok --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Kelompok</label>
                    <input type="text" name="name" id="edit_name" required class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-yellow-500 outline-none">
                </div>

                {{-- Anggota Kelompok --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Anggota Kelompok</label>
                    <div class="space-y-2">
                        {{-- Ketua (Readonly) --}}
                        <input type="text" id="edit_leader_display" readonly class="w-full border bg-gray-100 text-gray-500 rounded-lg px-4 py-2 text-sm">
                        <input type="hidden" name="members[]" id="edit_leader_value">
                        
                        {{-- Anggota 2-4 (Editable) --}}
                        <input type="text" name="members[]" id="edit_member_2" placeholder="Nama Anggota 2" required class="w-full border rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-yellow-500">
                        <input type="text" name="members[]" id="edit_member_3" placeholder="Nama Anggota 3" class="w-full border rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-yellow-500">
                        <input type="text" name="members[]" id="edit_member_4" placeholder="Nama Anggota 4" class="w-full border rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-yellow-500">
                    </div>
                </div>

                {{-- Topik --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Topik Proyek</label>
                    <input type="text" name="topic" id="edit_topic" required class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-yellow-500 outline-none">
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('editGroupModal').classList.add('hidden')" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg">Batal</button>
                <button type="submit" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 font-semibold shadow-md">Update Data</button>
            </div>
        </form>
    </div>
</div>

{{-- SCRIPTS (Search & Edit) --}}
<script>
  
    document.getElementById('tableSearch').addEventListener('keyup', function() {
        let input = this.value.toLowerCase();
        let rows = document.querySelectorAll('tbody tr');

        rows.forEach(function(row) {
            let text = row.innerText.toLowerCase();
            if(row.querySelector('td').innerHTML.trim() === '' || row.classList.contains('spacer-row')) { return; }

            if (text.indexOf(input) > -1) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

 
    function openGroupEditModal(button) {
       
        const id = button.getAttribute('data-id');
        const name = button.getAttribute('data-name');
        const topic = button.getAttribute('data-topic');
        const members = JSON.parse(button.getAttribute('data-members')); 

        document.getElementById('edit_name').value = name;
        document.getElementById('edit_topic').value = topic;

        if(members.length > 0) {
            document.getElementById('edit_leader_display').value = members[0] + ' (Ketua)';
            document.getElementById('edit_leader_value').value = members[0];
        }
        document.getElementById('edit_member_2').value = members[1] || '';
        document.getElementById('edit_member_3').value = members[2] || '';
        document.getElementById('edit_member_4').value = members[3] || '';

       
        let url = "{{ route('groups.update', ':id') }}";
        url = url.replace(':id', id);
        document.getElementById('editGroupForm').action = url;

       
        document.getElementById('editGroupModal').classList.remove('hidden');
    }
</script>
@endsection