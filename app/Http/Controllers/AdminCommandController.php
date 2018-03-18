<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Library\MatchTimeCalc as MatchTimeCalc;

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
    public function addInnings($id)
    {
        return view('match.admin.matchpanel')->with('id',$id);
    }
}
