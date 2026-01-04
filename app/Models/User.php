<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nim',
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // --- BAGIAN YANG DITAMBAHKAN/DIUBAH ---

    // 1. Relasi ke Groups (Sebagai Anggota)
    // Menghubungkan user ke banyak group lewat tabel pivot 'group_user'
    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_user');
    }

    // 2. Relasi ke Groups (Sebagai Ketua)
    // Menghubungkan user ke group yang dia buat sendiri
    public function chairedGroups()
    {
        return $this->hasMany(Group::class, 'user_id');
    }

    // 3. Relasi ke Tasks
    // Menghubungkan user ke tugas-tugas yang diberikan kepadanya
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}