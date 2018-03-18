<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ball extends Model
{
    protected $primaryKey=ball_id;
    protected $guarded=['ball_id'];
    protected $imestamps=false;

    public function innings(){
        return $this->belongsTo('App\Innings');
    }
}
