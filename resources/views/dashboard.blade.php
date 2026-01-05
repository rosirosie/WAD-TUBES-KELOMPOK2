@extends('layouts.app')

@section('title', 'Dashboard - SI4807 StudyHub')

@section('content')
    <header class="mb-4 flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">Overview</h1>

        {{-- TOMBOL EXPORT DASHBOARD --}}
        <a href="{{ route('dashboard.export') }}" class="bg-white border border-gray-200 text-gray-700 px-5 py-2.5 rounded-lg font-bold shadow-sm text-sm flex items-center gap-2 transition hover:bg-gray-50 border-b-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Download Overview
        </a>
    </header>

    <div class="flex flex-col gap-6">
        
        <div id="announcement-container">
            @if(isset($announcement) && $announcement)
                @include('partials.announcement_widget', ['announcement' => $announcement])
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            function fetchUpdates() {
                $.ajax({
                    url: "{{ route('dashboard.updates') }}", 
                    method: "GET",
                    success: function(response) {
                        
                        // 1. Update Pengumuman
                        if(response.announcement_html) {
                            var current = $('#announcement-container').html().trim();
                            var next = response.announcement_html.trim();
                            // Timpa jika ada perubahan
                            if (current !== next) $('#announcement-container').html(next);
                        }

                        // 2. Update Kolom Kiri (Jadwal & Tugas Hari Ini)
                        if(response.left_html) {
                            var currentL = $('#left-container').html().trim();
                            var nextL = response.left_html.trim();
                            // Timpa jika ada perubahan
                            if (currentL !== nextL) $('#left-container').html(nextL);
                        }

                        // 3. Update Kolom Kanan (Cuaca, Besok, Notes)
                        if(response.right_html) {
                            var currentR = $('#right-container').html().trim();
                            var nextR = response.right_html.trim();
                            // Timpa jika ada perubahan
                            if (currentR !== nextR) $('#right-container').html(nextR);
                        }
                    },
                    error: function(xhr) {
                        console.log("Gagal update dashboard: " + xhr.statusText);
                    }
                });
            }

            // Jalankan setiap 3 detik
            setInterval(fetchUpdates, 3000);
        });
    </script>
@endsection