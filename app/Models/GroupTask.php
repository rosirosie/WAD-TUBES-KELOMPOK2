<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupTask extends Model
{
    use HasFactory;

    // Pastikan model ini merujuk ke nama tabel yang benar di database
    protected $table = 'group_progres';

    protected $fillable = [
        'group_team_id', // Foreign key ke tabel group_teams
        'title',         // Judul tugas
        'assigned_to',   // Nama anggota pelaksana
        'is_completed',  // Status (boolean: true/false)
    ];

    /**
     * Relasi ke Tabel GroupTeam
     */
    public function team()
    {
        return $this->belongsTo(GroupTeam::class, 'group_team_id');
    }
}