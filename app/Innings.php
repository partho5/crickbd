<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Innings extends Model
{
    protected $primaryKey='innings_id';
    protected $fillable=['match_id','is_ended'];

    public function match(){
        return $this->belongsTo('App\Match');
    }
    public function ball(){
        return $this->hasMany('App\Ball','innings_id');
    }
}
