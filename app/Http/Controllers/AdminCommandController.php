<?php

namespace App\Http\Controllers;

use App\Ball;
use App\Events\MatchUpdated;
use App\Http\Requests\NewBallData;
use App\Innings;
use App\Library\MatchApiGenerator;
use App\Library\DecideMatchDataApi;
use App\Library\MatchTimeCalc;
use App\Match;
use Illuminate\Http\Request;
use App\Team;

class AdminCommandController extends Controller
{

    public function showAdminPanel()
    {

        $admin_match = new MatchTimeCalc();
        $matches = $admin_match->matches;
        return view('match.admin.mygames', compact('matches', json_encode($matches)));

    }

    public function addInnings($id)
    {
        $match = Match::find($id);
        return view('match.admin.matchpanel', compact('match', $match));
    }

    public function getMatchDataApi($id)
    {
        return Match::with('teams.players')->find($id);
    }

    public function getResumeDataApi($id)
    {
        $match_decider = new DecideMatchDataApi($id);
        return $match_decider->match_data;
    }

    public function insertTossData(Request $request, $id)
    {
        return Match::where('match_id', '=', $id)->update(['toss_winner' => $request->toss_winner, 'first_innings' => $request->first_team]);
    }

    public function initializeInnings(Request $request, $id)
    {
        if (Innings::where('match_id', '=', $id)->count() <= 1) {
            $match = Match::find($id);
            $innings = new Innings;
            $match->innings()->save($innings);
            return 'Innings Created';
        } else {
            return 'Maximum innings already created';
        }
    }

    public function endInnings(Request $request, $id)
    {
        $latest_inn = Innings::where('match_id', '=', $id)->where('is_ended', '=', 0)->first();
        $balls_inn = Ball::where('innings_id', '=', $latest_inn->innings_id)->count();
        if ($balls_inn > 0) {
            $match = Match::find($id);
            $old_innings = Innings::where('match_id', '=', $id)->where('is_ended', '=', 0)->update(['is_ended' => 1]);
            event(new MatchUpdated($id));
            return 'Innings Ended';
        } else {
            return 'add some data first';
        }
    }

    public function addNewBall(NewBallData $request, $id)
    {
        $match = Match::find($id);
        if ($request->ball_number <= $match->over) {
            $innings = Innings::where('match_id', '=', $id)->where('is_ended', '=', 0)->first();
            $ball = new Ball([
                'player_bat' => $request->player_bat,
                'player_bowl' => $request->player_bowl,
                'ball_number' => $request->ball_number,
                'incident' => $request->incident,
                'run' => $request->run,
                'non_strike' => $request->non_strike,
                'who_out' => $request->who_out,
                'extra_type' => $request->extra_type,
            ]);
            $innings->ball()->save($ball);

            event(new MatchUpdated($id));

            return $request->ball_number;
        } else {
            return 'ball not added';
        }
    }

    public function showEditPage($id)
    {
        $old_match_data = Match::where('match_id', '=', $id)->with('teams')->get();
        return view('match.admin.edit_match', compact('old_match_data', $old_match_data));
    }

    public function editMatchData(Request $request, $id)
    {
        Match::where('match_id','=',$id)->update(['start_time'=>$request->match_time,'location'=>$request->location,'over'=>$request->total_over,'player_total'=>$request->total_player]);
        $teams=Match::find($id)->teams()->orderBy('team_id','asc')->get();
        $team_ids=[$teams[0]->team_id,$teams[1]->team_id];
        Team::find($team_ids[0])->update(['team_name'=>$request->team1]);
        Team::find($team_ids[1])->update(['team_name'=>$request->team2]);
	    return view('match.admin.edit_match')->with('edit_msg','success');
    }

    public function editMatchPlayers($id)
    {
        return view('match.admin.edit_match_players');
    }

    public function deleteLastBall($id)
    {
        $old_inn = Innings::where('match_id', '=', $id)->where('is_ended', '=', 0)->first();
        $x = Ball::where('innings_id', '=', $old_inn->innings_id)->orderBy('ball_id', 'desc')->first()->delete();
        event(new MatchUpdated($id));
        return 'last insert deleted';
    }

}
