@extends('layouts.app')

@section('title', 'Dashboard - SI4807 StudyHub')

@section('content')
    <header class="mb-4 flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">Overview</h1>
    </header>

    <div class="flex flex-col gap-6">
        
        {{-- WIDGET PENGUMUMAN (CAROUSEL SLIDER) --}}
        <div id="announcement-container">
            {{-- Pastikan controller mengirim 'announcements' (jamak) --}}
            @if(isset($announcements))
                @include('partials.announcement_carousel', ['announcements' => $announcements])
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <div class="lg:col-span-2" id="left-container">
                @include('partials.dashboard_left')
            </div>

            <div class="lg:col-span-1" id="right-container">
                @include('partials.dashboard_right')
            </div>

        </div>
    </div>

    {{-- JQUERY --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        // ==========================================
        // 1. LOGIKA SLIDER (Global Scope)
        // ==========================================
        // Ditaruh di luar $(document).ready agar fungsi bisa dipanggil oleh tombol onclick HTML
        
        let currentSlide = 0;
    
        function updateSlides() {
            const slides = document.querySelectorAll('.announcement-slide');
            const dots = document.querySelectorAll('.indicator-dot');
            const totalSlides = slides.length;

            if (totalSlides === 0) return;

            // Sembunyikan semua slide
            slides.forEach(slide => { 
                slide.classList.add('hidden'); 
                slide.classList.remove('block'); 
            });
            
            // Reset warna dots
            dots.forEach(dot => { 
                dot.classList.remove('bg-indigo-600', 'w-6'); 
                dot.classList.add('bg-gray-300', 'w-2'); 
            });

            // Logika Looping (Circular)
            if (currentSlide >= totalSlides) currentSlide = 0;
            if (currentSlide < 0) currentSlide = totalSlides - 1;

            // Tampilkan slide yang aktif
            if(slides[currentSlide]) {
                slides[currentSlide].classList.remove('hidden');
                slides[currentSlide].classList.add('block');
            }
            
            // Highlight dot yang aktif
            if(dots.length > 0 && dots[currentSlide]) {
                dots[currentSlide].classList.remove('bg-gray-300', 'w-2');
                dots[currentSlide].classList.add('bg-indigo-600', 'w-6');
            }
        }

        function changeSlide(n) {
            currentSlide += n;
            updateSlides();
        }

        function goToSlide(n) {
            currentSlide = n;
            updateSlides();
        }

        // ==========================================
        // 2. LOGIKA AJAX AUTO-UPDATE
        // ==========================================
        $(document).ready(function() {
            
            // Inisialisasi slider saat halaman pertama dimuat
            updateSlides();

            function fetchUpdates() {
                $.ajax({
                    url: "{{ route('dashboard.updates') }}", 
                    method: "GET",
                    success: function(response) {
                        
                        // A. UPDATE PENGUMUMAN (Cerdas)
                        // Hanya update jika ID data berubah, supaya slider tidak reset saat digeser
                        if(response.announcement_html) {
                            // Ambil signature dari HTML baru
                            var $newContent = $(response.announcement_html);
                            var newSig = $newContent.attr('data-signature'); // ID Unik dari partials
                            
                            // Ambil signature yang sekarang tampil
                            var currentSig = $('.carousel-wrapper').attr('data-signature');

                            // Cek: Apakah datanya beda?
                            if (newSig !== undefined && newSig !== currentSig) {
                                $('#announcement-container').html(response.announcement_html);
                                currentSlide = 0; // Reset ke awal jika ada pengumuman baru
                                updateSlides();   // Re-init slider
                            }
                        }

                        // B. Update Kolom Kiri
                        if(response.left_html) {
                            var currentL = $('#left-container').html().trim();
                            var nextL = response.left_html.trim();
                            if (currentL !== nextL) $('#left-container').html(nextL);
                        }

                        // C. Update Kolom Kanan
                        if(response.right_html) {
                            var currentR = $('#right-container').html().trim();
                            var nextR = response.right_html.trim();
                            if (currentR !== nextR) $('#right-container').html(nextR);
                        }
                    },
                    error: function(xhr) {
                        console.log("Gagal update dashboard: " + xhr.statusText);
                    }
                });
            }

            // Jalankan pengecekan setiap 3 detik
            setInterval(fetchUpdates, 3000);
        });
    </script>
@endsection