<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Group;
use App\Models\GroupTeam;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat User Ferrer (Admin)
        User::create([
            'nim' => '102022400008',
            'name' => 'Ferrer',
            'email' => 'admin@studyhub.com',
            'password' => Hash::make('ferrer123'),
            'email_verified_at' => now(),
            'role' => 'admin',
        ]);

        // 2. Buat Daftar Mata Kuliah (Master Group Directory)
        $subjects = [
            ['subject' => 'Pengembangan Aplikasi Website'],
            ['subject' => 'Analisis dan Perancangan Sistem Informasi'],
            ['subject' => 'Perancangan Interaksi'],
            ['subject' => 'Statistika Industri'],
            ['subject' => 'Pemodelan Proses Bisnis'],
        ];

        foreach ($subjects as $sub) {
            Group::create($sub);
        }

        // 3. Buat Contoh Kelompok (Agar muncul di Project Progress)
        GroupTeam::create([
            'group_id' => 1, 
            'name' => 'Kelompok 1',
            'leader_name' => 'Ferrer', 
            'members' => ['Ferrer', 'Dhimas', 'Zaidan', 'Mutiara'], // Disimpan sebagai array JSON
            'topic' => 'Sistem Manajemen StudyHub',
        ]);
    }
}