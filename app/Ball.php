<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ball extends Model
{
    protected $primaryKey='ball_id';
    protected $guarded=['ball_id'];
    public $timestamps=false;

    public function innings(){
        return $this->belongsTo('App\Innings','innings_id');
    }
    public function players(){
        return $this->belongsToMany('App\Player','player_id');
    }
}
