<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\NewBallData;
use App\Library\MatchTimeCalc as MatchTimeCalc;
use App\Match;
use App\Innings;
use App\Ball;

class AdminCommandController extends Controller
{
    public function showAdminPanel()
    {
        $matchTime = new MatchTimeCalc();
        return view('match.admin.mygames')->with('data', [
            ['upcoming' => $matchTime->upcomingMatches()],
            ['completed' => ''],
            ['running' => '']
        ]);
    }

    public function addInnings()
    {
        return view('match.admin.matchpanel');
    }

    public function getMatchDataApi($id)
    {
        return Match::with('teams.players')->find($id);
    }

    public function getResumeDataApi($id)
    {

    }

    public function insertTossData(Request $request, $id)
    {
        return Match::where('match_id', '=', $id)->update(['toss_winner' => $request->toss_winner, 'first_innings' => $request->first_team]);
    }

    public function initializeInnings(Request $request, $id)
    {
        if (Innings::where('match_id', '=', $id)->count() <= 2) {
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
        $match = Match::find($id);
        $old_innings = Innings::where('match_id', '=', $id)->where('is_ended', '=', 0)->update(['is_ended' => 1]);
        return 'Innings Ended';
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
                'extra_type' => $request->extra_type
            ]);
            $innings->ball()->save($ball);
            return $request->ball_number;
        } else {
            return 'ball not added';
        }
    }

    public function editMatchData($id)
    {
        return view('match.admin.edit_match');
    }
    public function editMatchPlayers($id)
    {
        return view('match.admin.edit_match_players');
    }


}
