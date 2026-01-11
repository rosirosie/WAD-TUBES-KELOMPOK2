<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupProgres extends Model
{
    use HasFactory;

    protected $table = 'group_progres';

    protected $guarded = [];
  
    public function team()
    {
        return $this->belongsTo(GroupTeam::class, 'group_team_id');
    }
}