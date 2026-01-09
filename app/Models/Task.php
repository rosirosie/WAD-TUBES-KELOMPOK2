<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    /**
     * Kolom yang dapat diisi secara massal.
     * Kita hapus user_id karena tidak perlu mencatat admin pembuat.
     */
    protected $fillable = [
        'course',
        'title', 
        'status', 
        'deadline',
        'user_id'
    ];

    /**
     * Casting tipe data kolom.
     * Mengubah 'deadline' menjadi objek Carbon agar fungsi tanggal di View tidak error.
     */
    protected $casts = [
        'deadline' => 'date',
    ];

    /**
     * Relasi ke model Group.
     * Tetap dipertahankan jika Anda ingin membagi tugas per kelompok kelas.
     */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}