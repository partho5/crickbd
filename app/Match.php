<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    public $primaryKey='match_id';
    protected $fillable=['user_id','over','location','start_time','player_total'];

    public function teams()
    {
    	return $this->hasMany('App\Team','match_id');
    }
    public function innings()
    {
        return $this->hasMany('App\Innings','match_id');
    }
}
