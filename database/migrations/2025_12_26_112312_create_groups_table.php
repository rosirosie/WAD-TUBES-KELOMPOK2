<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            // Gunakan 'subject' agar sinkron dengan Controller dan Seeder
            $table->string('subject'); 
            
            // Kolom tambahan untuk detail mata kuliah (opsional)
            $table->text('description')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('groups');
    }
};