@php
    $signature = isset($announcements) ? $announcements->pluck('id')->join('-') : '';
@endphp

{{-- Tambahkan class 'carousel-wrapper' dan atribut 'data-signature' --}}
<div class="relative group carousel-wrapper" data-signature="{{ $signature }}">
    @if(isset($announcements) && count($announcements) > 0)
        
        <div class="relative overflow-hidden rounded-xl">
            @foreach($announcements as $index => $announcement)
                <div class="announcement-slide transition-opacity duration-500 ease-in-out {{ $index === 0 ? 'block' : 'hidden' }}" 
                     data-index="{{ $index }}">
                     @include('partials.announcement_widget', ['announcement' => $announcement])
                </div>
            @endforeach
        </div>

        @if(count($announcements) > 1)
            {{-- Tombol Kiri --}}
            <button onclick="changeSlide(-1)" class="absolute left-0 top-1/2 -translate-y-1/2 -ml-3 lg:-ml-4 z-20 bg-white text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 shadow-md border border-gray-100 p-2 rounded-full opacity-0 group-hover:opacity-100 transition-all duration-300 transform hover:scale-110 cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
            </button>
            {{-- Tombol Kanan --}}
            <button onclick="changeSlide(1)" class="absolute right-0 top-1/2 -translate-y-1/2 -mr-3 lg:-mr-4 z-20 bg-white text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 shadow-md border border-gray-100 p-2 rounded-full opacity-0 group-hover:opacity-100 transition-all duration-300 transform hover:scale-110 cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
            </button>
            
            <div class="flex justify-center gap-2 mt-2">
                @foreach($announcements as $index => $announcement)
                    <button 
                        data-index="{{ $index }}" 
                        onclick="goToSlide(Number(this.getAttribute('data-index')))" 
                        class="indicator-dot h-1.5 rounded-full transition-all duration-300 {{ $index === 0 ? 'bg-indigo-600 w-6' : 'bg-gray-300 w-2 hover:bg-gray-400' }}">
                    </button>
                @endforeach
            </div>
        @endif

    @else
        <div class="p-6 bg-white rounded-xl border border-dashed border-gray-300 text-center text-gray-500 italic text-sm">
            Tidak ada pengumuman terbaru.
        </div>
    @endif
</div>