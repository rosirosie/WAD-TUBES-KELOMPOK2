<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Menggunakan nama group_teams agar sinkron dengan Model dan Controller
        Schema::create('group_teams', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke tabel groups (Mata Kuliah)
            $table->foreignId('group_id')->constrained('groups')->onDelete('cascade');
            
            // Kolom utama sesuai mockup progres kelompok
            $table->string('name'); // Nama Kelompok (Misal: Kelompok 1)
            $table->string('leader_name'); // Nama Ketua (Untuk pencarian 'Ferrer')
            $table->json('members'); // Daftar Nama Anggota dalam format JSON
            
            // Kolom tambahan untuk link laporan/ppt
            $table->string('topic')->nullable();
            $table->string('report_link')->nullable();
            $table->string('ppt_link')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('group_teams');
    }
};