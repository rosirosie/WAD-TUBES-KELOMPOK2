<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            // Menghubungkan tugas ke user yang sedang login
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('course');
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('deadline');
            // Status menggunakan string 'done' atau lainnya sesuai DashboardController
            $table->string('status')->default('pending'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};