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
use App\Innings;
use App\Library\ScoreBoardCalculator;
use App\Library\BasicInningsInfo;


class MatchTimeCalc
{
    public $matches = [
        "today" => [],
        "upcoming" => [],
        "complete" => [],
        "delayed" => []
    ];

    public function __construct()
    {
        $this->runningMatches();
        $this->upcomingMatches();
        $this->completedMatches();
        $this->delayedMatches();
    }

    public function runningMatches()
    {
        array_push(
            $this->matches['today'],
            Match::where('user_id','=',Auth::user()->user_id)->whereNotNull('toss_winner')->whereNotNull('first_innings')->withCount(['innings' => function ($query) {
                $query->where('is_ended', '=', 1);
            }])->having('innings_count', '!=', 2)->orderBy('start_time', 'asc')->get()
        );
        foreach ($this->matches['today'][0] as $today_match) {
            $inns=Innings::where('match_id','=',$today_match->match_id)->orderBy('created_at','asc')->get();
            $today_match['second']=false;

            $basic_data_object=new ScoreBoardCalculator($today_match->match_id);
            $today_match['toss_winner']=$basic_data_object->scores['basic']['toss_winner_team_name'];
            $today_match['f_runs']=$basic_data_object->getRuns($inns[0]->innings_id);
            $today_match['f_overs']=$basic_data_object->getOvers($inns[0]->innings_id);
            $today_match['f_wickets']=$basic_data_object->getWickets($inns[0]->innings_id);
            $today_match['f_team']=$basic_data_object->scores['basic']['batting_team'];

            if(count($inns)>1){
                $today_match['second']=true;
                $basic_data_object=new ScoreBoardCalculator($today_match->match_id);
                $today_match['s_runs']=$basic_data_object->getRuns($inns[1]->innings_id);
                $today_match['s_overs']=$basic_data_object->getOvers($inns[1]->innings_id);
                $today_match['s_wickets']=$basic_data_object->getWickets($inns[1]->innings_id);
                $today_match['s_team']=$basic_data_object->scores['basic']['bowling_team'];
            }
        }

    }

    public function upcomingMatches()
    {
        array_push(
            $this->matches['upcoming'],
            Match::where([
                ['start_time', '>=', date("Y-m-d H:i:s")],
                ['user_id', '=', Auth::user()->user_id]
            ])->with('teams')->whereNull('toss_winner')->orderBy('start_time', 'asc')->get()
        );
    }

    public function completedMatches()
    {

        array_push(
            $this->matches['complete'],
            Match::where('user_id', '=', Auth::user()->user_id)->withCount(['innings' => function ($query) {
                $query->where('is_ended', '=', 1);
            }])->having('innings_count', '=', 2)->orderByDesc('updated_at')->get()
        );
        foreach ($this->matches['complete'][0] as $com_match) {
            $score_object = new ScoreBoardCalculator($com_match['match_id']);
            $score_object->getInnings();
            $score_object->inningsData();
            $com_match['winner_team'] = $score_object->scores['basic']['winner_team'];
            $com_match['win_by'] = $score_object->scores['basic']['win_by'];
            $com_match['win_digit'] = $score_object->scores['basic']['win_digit'];
        }

    }

    public function delayedMatches()
    {
        array_push(
            $this->matches['delayed'],
            Match::where([
                ['start_time', '<', date("Y-m-d H:i:s")],
                ['user_id','=',Auth::user()->user_id]
            ])->with('teams')->whereNull('toss_winner')->orderBy('start_time', 'desc')->get()
        );
    }
}