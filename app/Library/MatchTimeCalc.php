<?php
/**
 * Created by PhpStorm.
 * User: nrriaz
 * Date: 3/15/18
 * Time: 10:21 PM
 */

namespace App\Library;

use App\Match;
use Illuminate\Support\Facades\Auth;



class MatchTimeCalc
{
    public function runningMatches(){

    }
    public function upcomingMatches(){
        return Match::where([
            ['start_time','>',date("Y-m-d H:i:s")],
            ['user_id','=',Auth::user()->user_id]
        ])->with('teams')->get();
    }
    public function completedMatches(){

    }
}