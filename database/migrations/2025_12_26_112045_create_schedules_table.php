<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Agar tiap user punya jadwal sendiri
            $table->string('subject'); // Nama Matkul (misal: Matkul A)
            $table->string('day'); // Senin, Selasa, Rabu, dst
            $table->time('start_time'); // 09:00
            $table->time('end_time'); // 12:30
            $table->string('room'); // Ruangan (Gedung B)
            $table->string('lecturer')->nullable(); // Kode Dosen (opsional)
            $table->timestamps();
        });
    }
};
