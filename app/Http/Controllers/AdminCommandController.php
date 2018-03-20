<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Library\MatchTimeCalc as MatchTimeCalc;
use App\Match;

class AdminCommandController extends Controller
{
    public function showAdminPanel(){
        $matchTime=new MatchTimeCalc();
        return view('match.admin.mygames')->with('data',[
            ['upcoming'=>$matchTime->upcomingMatches()],
            ['completed'=>''],
            ['running'=>'']
        ]);
    }
    public function addInnings()
    {
        return view('match.admin.matchpanel');
    }
    public function getMatchDataApi($id){
        return Match::with('teams.players')->find($id);
    }
}
