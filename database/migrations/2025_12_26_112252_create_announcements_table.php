<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Judul Pengumuman
            $table->text('content'); // Isi Pesan
            $table->enum('type', ['info', 'urgent', 'warning'])->default('info'); // Warna (Merah/Biru)
            $table->boolean('is_active')->default(true); // Status Tampil/Sembunyi
            $table->timestamps();
        });
    }
};
