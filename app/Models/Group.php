<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'subject', 'description'];

    // Relasi ke Ketua
    public function chairman()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke Anggota (Selain Ketua)
    public function members()
    {
        return $this->belongsToMany(User::class, 'group_user');
    }

    // Relasi ke Tugas
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}