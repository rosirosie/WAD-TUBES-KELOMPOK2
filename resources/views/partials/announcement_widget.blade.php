<<<<<<< HEAD
<div class="bg-red-50 rounded-xl shadow-sm border-l-8 border-red-600 p-6 relative overflow-hidden transition-all duration-500">
            
    <div class="absolute -right-6 -top-6 opacity-10">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-24 h-24 text-red-600">
            <path fill-rule="evenodd" d="M5.25 9a6.75 6.75 0 0113.5 0v.75c0 2.123.8 4.057 2.118 5.52a.75.75 0 01-.297 1.206c-1.544.57-3.16.99-4.831 1.243a3.75 3.75 0 11-7.48 0 24.585 24.585 0 01-4.831-1.244.75.75 0 01-.298-1.205A8.217 8.217 0 005.25 9.75V9zm4.502 8.9a2.25 2.25 0 104.496 0 25.057 25.057 0 01-4.496 0z" clip-rule="evenodd" />
            </svg>
    </div>

    <div class="flex items-start relative z-10">
        <div class="flex-shrink-0 bg-red-100 p-3 rounded-full border-2 border-red-200">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-8 h-8 text-red-600">
=======
@php
    $type = $announcement->type ?? 'info';

    // 1. DEFINISI STYLE LENGKAP (Agar Tailwind tidak menghapus warna teks)
    $styles = [
        'urgent' => [
            'wrapper'     => 'bg-red-50 border-red-600',
            'icon_bg'     => 'bg-red-100 border-red-200',
            'icon_color'  => 'text-red-600',
            'badge'       => 'bg-red-600 text-white',
            'title'       => 'text-red-900',
            'content'     => 'text-red-800',
            'hover_btn'   => 'hover:text-red-600 hover:bg-red-100'
        ],
        'warning' => [
            'wrapper'     => 'bg-yellow-50 border-yellow-500',
            'icon_bg'     => 'bg-yellow-100 border-yellow-200',
            'icon_color'  => 'text-yellow-600',
            'badge'       => 'bg-yellow-500 text-white',
            'title'       => 'text-yellow-900',
            'content'     => 'text-yellow-800',
            'hover_btn'   => 'hover:text-yellow-600 hover:bg-yellow-100'
        ],
        'info' => [
            'wrapper'     => 'bg-blue-50 border-blue-500',
            'icon_bg'     => 'bg-blue-100 border-blue-200',
            'icon_color'  => 'text-blue-600',
            'badge'       => 'bg-blue-600 text-white',
            'title'       => 'text-blue-900',
            'content'     => 'text-blue-800',
            'hover_btn'   => 'hover:text-blue-600 hover:bg-blue-100'
        ],
    ];

    $style = $styles[$type] ?? $styles['info'];
@endphp

<div class="{{ $style['wrapper'] }} rounded-xl shadow-sm border-l-8 p-6 relative overflow-hidden transition-all duration-500 group">
            
    {{-- 2. TOMBOL AKSI (EDIT & DELETE) - Hanya Muncul untuk Admin --}}
    @if(auth()->check() && auth()->user()->role === 'Admin')
        <div class="absolute top-4 right-4 z-20 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center gap-2">
            
            {{-- Tombol Edit --}}
            <a href="{{ route('announcements.edit', $announcement->id) }}" 
               class="bg-white p-2 rounded-full shadow-sm text-gray-400 {{ $style['hover_btn'] }} transition-colors" 
               title="Edit Pengumuman">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </a>

            {{-- Tombol Hapus --}}
            <form action="{{ route('announcements.destroy', $announcement->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pengumuman ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-white p-2 rounded-full shadow-sm text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors cursor-pointer" title="Hapus Pengumuman">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </form>
        </div>
    @endif

    {{-- 3. BACKGROUND DEKORASI --}}
    <div class="absolute -right-6 -top-6 opacity-10">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-24 h-24 {{ $style['icon_color'] }}">
            <path fill-rule="evenodd" d="M5.25 9a6.75 6.75 0 0113.5 0v.75c0 2.123.8 4.057 2.118 5.52a.75.75 0 01-.297 1.206c-1.544.57-3.16.99-4.831 1.243a3.75 3.75 0 11-7.48 0 24.585 24.585 0 01-4.831-1.244.75.75 0 01-.298-1.205A8.217 8.217 0 005.25 9.75V9zm4.502 8.9a2.25 2.25 0 104.496 0 25.057 25.057 0 01-4.496 0z" clip-rule="evenodd" />
        </svg>
    </div>

    {{-- 4. KONTEN UTAMA --}}
    <div class="flex items-start relative z-10">
        <div class="flex-shrink-0 {{ $style['icon_bg'] }} p-3 rounded-full border-2">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-8 h-8 {{ $style['icon_color'] }}">
>>>>>>> 05c2f0e988029abc36fa4855e19abd8e1300a192
                <path fill-rule="evenodd" d="M12 2.25a.75.75 0 01.75.75v.756a49.106 49.106 0 019.152 1 .75.75 0 01-.152 1.485h-1.918l2.474 10.124a.75.75 0 01-.375.84A6.723 6.723 0 0112 18a6.723 6.723 0 01-9.931-1.795.75.75 0 01-.375-.84l2.474-10.124H2.25a.75.75 0 01-.152-1.485 49.106 49.106 0 019.152-1V3a.75.75 0 01.75-.75zm4.878 13.5a5.25 5.25 0 00-9.756 0L4.5 4.5h15l-2.622 11.25zm-6.628 2.75a.75.75 0 01.75.75V21a.75.75 0 01-1.5 0v-1.75a.75.75 0 01.75-.75z" clip-rule="evenodd" />
            </svg>
        </div>
        
<<<<<<< HEAD
        <div class="ml-5 w-full">
            <div class="flex items-center gap-2 mb-2">
                <span class="bg-red-600 text-white text-xs font-bold uppercase px-3 py-1 rounded-full tracking-wide">
                    {{ $announcement->type ?? 'PENTING' }}
                </span>
                <h3 class="font-bold text-xl text-red-900 tracking-tight">
                    {{ $announcement->title }}
                </h3>
            </div>
            <p class="text-red-800 text-base font-medium leading-relaxed">
                {{ $announcement->content }}
            </p>
            <p class="text-red-400 text-xs mt-3 text-right italic">
=======
        <div class="ml-5 w-full pr-14"> {{-- pr-14 memberi jarak agar teks tidak tertutup tombol --}}
            <div class="flex items-center gap-2 mb-2">
                <span class="{{ $style['badge'] }} text-xs font-bold uppercase px-3 py-1 rounded-full tracking-wide">
                    {{ $type }}
                </span>
                
                <h3 class="font-bold text-xl {{ $style['title'] }} tracking-tight">
                    {{ $announcement->title }}
                </h3>
            </div>
            
            <p class="{{ $style['content'] }} text-base font-medium leading-relaxed">
                {{ $announcement->content }}
            </p>
            
            <p class="{{ $style['icon_color'] }} text-xs mt-3 text-right italic opacity-80">
>>>>>>> 05c2f0e988029abc36fa4855e19abd8e1300a192
                Diposting: {{ $announcement->created_at->diffForHumans() }}
            </p>
        </div>
    </div>
</div>