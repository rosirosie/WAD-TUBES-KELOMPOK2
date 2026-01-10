<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk tabel progres kelompok.
     */
    public function up(): void
    {
        Schema::create('group_progres', function (Blueprint $table) {
            $table->id();
            // Gunakan sintaks ini untuk keamanan relasi yang lebih baik
            $table->foreignId('group_team_id')
                ->constrained('group_teams') // Ini otomatis merujuk ke tabel group_teams kolom id
                ->onDelete('cascade');
            
            $table->string('title');
            $table->string('assigned_to');
            $table->boolean('is_completed')->default(false);
            $table->timestamps();
        });
    }
    /**
     * Batalkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_progres');
    }
};