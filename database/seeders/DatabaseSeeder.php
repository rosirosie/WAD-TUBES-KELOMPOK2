<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
       User::create([
            'nim' => '102022400008',
            'name' => 'Ferrer',
            'email' => 'admin@studyhub.com',
            'password' => Hash::make('ferrer123'), // Password Login
            'email_verified_at' => now(),
            'role' => 'Admin',
        ]);
    }
}
