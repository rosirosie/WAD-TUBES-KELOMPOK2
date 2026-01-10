<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'subject', 'description'];

    public function chairman()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'group_user');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}