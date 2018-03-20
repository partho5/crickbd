<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $primaryKey='player_id';
    protected $guarded=['player_id'];
    public $timestamps=false;

    public function team(){
    	return $this->belongsTo('App\Team');
    }

    public function batsman_run(){
        return $this->hasMany('App\Ball','player_bat');
    }

    public function bowler_run(){
        return $this->hasMany('App\Ball','player_bowl');
    }
}
