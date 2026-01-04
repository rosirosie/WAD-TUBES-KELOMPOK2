<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupTeam extends Model
{
    use HasFactory;

    protected $table = 'group_teams';

    protected $casts = [
        'members' => 'array',
    ];

    protected $fillable = ['group_id', 'name', 'leader_name', 'members', 'topic'];

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }
}