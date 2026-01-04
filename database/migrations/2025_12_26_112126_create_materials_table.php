<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('course');      // Mata Kuliah
            $table->string('week');        // Minggu ke
            $table->string('title');       // Judul Materi
            $table->string('file_path');   // Lokasi file
            $table->enum('visibility', ['public', 'private'])->default('public');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('materials');
    }
};