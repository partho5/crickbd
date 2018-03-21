<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Library\MatchTimeCalc as MatchTimeCalc;
use App\Match;
use App\Innings;
use App\Ball;

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
    public function insertTossData(Request $request,$id){
        return Match::where('match_id','=',$id)->update(['toss_winner'=>$request->toss_winner,'first_innings'=>$request->first_team]);
    }
    public function initializeInnings(Request $request,$id){
        $match=Match::find($id);
        $innings=new Innings;
        $match->innings()->save($innings);
    }
    public function addNewBall(NewBallData $request,$id){
        $match=Match::find($id);
        if($request->ball_number<=$match->over){
            $inngings=Innings::where('match_id','=',$id)->where('is_ended','=',0);
            $ball=new Ball(['player_bat'=>$request->player_bat,'player_bowl'=>$request->player_bowl,'ball_number'=>$request->ball,'incident'=>$request->incident,'run'=>$request->run]);
            $innings->ball()->save($ball);
            return 'New Ball Added';
        }
        else{
            return 'ball not added';
        }
    }
}
