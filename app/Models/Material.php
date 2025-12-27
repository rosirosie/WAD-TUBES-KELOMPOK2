<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $fillable = [
        'user_id', 
        'course_id', 
        'title', 
        'file_path', 
        'file_type', 
        'week_number', 
        'description'
    ];

    public function user() {
        return $this->belongsTo(User::class); // Siapa yang upload
    }

    public function course() {
        return $this->belongsTo(Course::class);
    }
}
