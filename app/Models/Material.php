<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course',
        'week',
        'title',
        'description',
        'file_path',
        'visibility',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}