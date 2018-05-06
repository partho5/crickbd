<?php
/**
 * Created by PhpStorm.
 * User: nrriaz
 * Date: 4/3/18
 * Time: 4:30 PM
 */

namespace App\Library;


use App\Innings;
use App\Match;
use App\Library\DecideMatchWinner;
use App\Library\MatchApiGenerator;
use App\Player;
use Illuminate\Support\Facades\DB;

class ScoreBoardCalculator
{

    public $match_id;
    public $match_data;
    public $inns = [];
    public $full_inns=[];
    public $scores = [
        'basic' => [
            'toss_winner_team_name' => null,
            'batting_team' => null,
            'bowling_team' => null,
            'winning_details' => null,
            'winner_team' => null,
            'win_by' => null,
            'win_digit' => null,
            'isDrawn' => false,
            'first_bats' => null,
            'first_bowls' => null
        ],
        'first' => [
            "runs" => null,
            "overs" => null,
            "wickets" => null,
            "extras" => 0,
            "consumed"=>null
        ],
        'second' => [
            "runs" => null,
            "overs" => null,
            "wickets" => null,
            "extras" => 0,
            "consumed"=>null
        ]
    ];
    public $first_bat_bowl = [
        'bat' => '',
        'bowl' => ''
    ];

    public function __construct($id)
    {
        $this->match_id = $id;
        $this->genMatchInfo();
        $this->decideBatBowlTeam();
        $this->getInnings();
    }

    public function getInnings()
    {
        $innings = Innings::where([['match_id', '=', $this->match_id], ['is_ended', '=', 1]])->orderBy('created_at', 'asc')->get();
        if(count($innings)==0){
            abort(404);
        }
        foreach ($innings as $inn) {
            array_push(
                $this->inns,
                $inn->innings_id
            );
            array_push(
              $this->full_inns,
              $inn
            );
        }
        if (count($innings) == 2) {
            $this->scores['basic']['winning_details'] = $this->getWinningDetails($innings[0], $innings[1]);
            $this->matchWinningBasic();
        }
    }

    public function inningsData()
    {
        $this->scores['basic']['first_bats'] = $this->getBatsMan();
        $this->scores['basic']['first_bowls'] = $this->getBowlers();

        $this->scores['first']['runs'] = $this->getRuns($this->inns[0]);
        $this->scores['first']['overs'] = $this->getOvers($this->inns[0]);
        $this->scores['first']['wickets'] = $this->getWickets($this->inns[0]);
        $this->scores['first']['extras'] = $this->getExtras($this->inns[0]);
        $this->scores['first']['consumed']=$this->getBallConsumed($this->full_inns[0]);
        if (count($this->inns) > 1) {
            $this->scores['second']['runs'] = $this->getRuns($this->inns[1]);
            $this->scores['second']['overs'] = $this->getOvers($this->inns[1]);
            $this->scores['second']['wickets'] = $this->getWickets($this->inns[1]);
            $this->scores['second']['extras'] = $this->getExtras($this->inns[1]);
            $this->scores['second']['consumed']=$this->getBallConsumed($this->full_inns[1]);
        }
    }

    public function getOvers($inn_id)
    {
        return DB::select('SELECT max(cast(ball_number as decimal(4,1))) AS overs FROM balls WHERE innings_id=?', [$inn_id])[0]->overs;
    }

    public function getRuns($inn_id)
    {
        return DB::select('SELECT sum(run) AS total_run FROM balls WHERE innings_id=?', [$inn_id])[0]->total_run;
    }

    public function getWickets($inn_id)
    {
        return DB::select('select count(*) as wickets from balls where innings_id=? and incident is not null', [$inn_id])[0]->wickets;
    }

    public function getExtras($inn_id)
    {
        return DB::select('select sum(run) as extra from balls where innings_id=? and extra_type is not null', [$inn_id])[0]->extra;
    }

    public function getBatsMan()
    {
        return Player::where('team_id', '=', $this->first_bat_bowl['bat'])->get();
    }

    public function getBowlers()
    {
        return Player::where('team_id', '=', $this->first_bat_bowl['bowl'])->get();
    }

    public function genMatchInfo()
    {
        $this->match_data = Match::where('match_id', '=', $this->match_id)->with('teams')->get();
        foreach ($this->match_data[0]['teams'] as $team) {
            if ($this->match_data[0]['toss_winner'] == $team['team_id']) {
                $this->scores['basic']['toss_winner_team_name'] = $team['team_name'];
            }
        }
    }

    public function decideBatBowlTeam()
    {
        if ($this->match_data[0]->first_innings == 'bat') {
            $this->first_bat_bowl['bat'] = $this->match_data[0]->toss_winner;
            foreach ($this->match_data[0]->teams as $team) {
                if ($team->team_id != $this->match_data[0]->toss_winner) {
                    $this->first_bat_bowl['bowl'] = $team->team_id;
                    $this->scores['basic']['bowling_team'] = $team->team_name;
                } else {
                    $this->scores['basic']['batting_team'] = $team->team_name;
                }
            }
        } else if ($this->match_data[0]->first_innings == 'bowl') {
            $this->first_bat_bowl['bowl'] = $this->match_data[0]->toss_winner;
            foreach ($this->match_data[0]->teams as $team) {
                if ($team->team_id != $this->match_data[0]->toss_winner) {
                    $this->first_bat_bowl['bat'] = $team->team_id;
                    $this->scores['basic']['batting_team'] = $team->team_name;
                } else {
                    $this->scores['basic']['bowling_team'] = $team->team_name;
                }
            }
        }
    }

    public function getWinningDetails($first_innings, $second_innings)
    {
        $ended_api = new DecideMatchWinner($this->match_id, $first_innings, $second_innings);
        $details_array = [];
        array_push(
            $details_array,
            $ended_api->first_details,
            $ended_api->second_details
        );
        return $details_array;
    }

    public function matchWinningBasic()
    {
        if ($this->scores['basic']['winning_details']['0']['run'] == $this->scores['basic']['winning_details'][1]['run']) {
            $this->scores['basic']['isDrawn'] = true;
        } else if ($this->scores['basic']['winning_details'][0]['run'] > $this->scores['basic']['winning_details'][1]['run']) {
            $this->scores['basic']['winner_team'] = $this->scores['basic']['batting_team'];
            $this->scores['basic']['win_by'] = "run";
            $this->scores['basic']['win_digit'] = $this->scores['basic']['winning_details']['0']->run - $this->scores['basic']['winning_details']['1']->run;
        } else {
            $this->scores['basic']['win_by'] = "wicket";
            $this->scores['basic']['win_digit'] = $this->match_data[0]->player_total - 1 - $this->scores['basic']['winning_details'][1]['wicket'];
            $this->scores['basic']['winner_team'] = $this->scores['basic']['bowling_team'];
        }

    }

    public function getBallConsumed($innings)
    {
        $ball_consumed_ob=new MatchApiGenerator($this->match_id,$innings);
        $ball_consumed_ob->getResumeData();
        return $ball_consumed_ob->ball_consumed;
    }
}