@extends('layouts.app')

@section('title', 'Dashboard - SI4807 StudyHub')

@section('content')
    <header class="mb-4 flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">Overview</h1>
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