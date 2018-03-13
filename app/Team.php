<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
	public $primaryKey='team_id';
	protected $fillable=['team_name','match_id'];
	public $timestamps=false;

    public function match()	
    {
    	return $this->belongsTo('App\Match','team_id');
    }

    public function players()
    {
    	return $this->hasMany('App\Player','team_id');
    }
}
