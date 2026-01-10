<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('group_progres', function (Blueprint $table) {
            $table->integer('week')->after('group_team_id')->default(1);
        });
    }

    public function down(): void
    {
        Schema::table('group_progres', function (Blueprint $table) {
            //
        });
    }
};
