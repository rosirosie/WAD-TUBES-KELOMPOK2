<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $fillable = [
        'course',
        'title', 
        'status', 
        'deadline',
        'user_id'
    ];

    protected $casts = [
        'deadline' => 'date',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}