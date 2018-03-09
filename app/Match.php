<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    protected $primaryKey='match_id';
    protected $fillable=['user_id','over','location','start_time','player_total']; 
}
