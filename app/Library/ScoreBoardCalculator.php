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
use Illuminate\Support\Facades\DB;

class ScoreBoardCalculator
{

    public $match_id;
    public $match_data;
    public $inns = [];
    public $scores = [
        'basic' => [
            'toss_winner_team_name'=>null,
            'batting_team'=>null,
            'bowling_team'=>null
        ],
        'first' => [
            "runs" => null,
            "overs" => null,
            "wickets" => null,
            "bats" => null,
            "bowls" => null,
            "extras" => 0
        ],
        'second' => [
            "runs" => null,
            "overs" => null,
            "scores" => null,
            "bats" => null,
            "bowls" => null,
            "extras" => 0
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
        $this->getInnings();
        $this->decideBatBowlTeam();
    }

    public function getInnings()
    {
        $innings = Innings::where([['match_id', '=', $this->match_id], ['is_ended', '=', 1]])->orderBy('created_at', 'asc')->get();
        foreach ($innings as $inn) {
            array_push(
                $this->inns,
                $inn->innings_id
            );
        }
    }

    public function inningsData()
    {
        $this->scores['first']['runs'] = $this->getRuns($this->inns[0]);
        $this->scores['first']['overs'] = $this->getOvers($this->inns[0]);
        $this->scores['first']['wickets'] = $this->getWickets($this->inns[0]);
        $this->scores['first']['bats'] = $this->getBatsMan($this->inns[0]);
        $this->scores['first']['bowls'] = $this->getBowlers($this->inns[0]);
        $this->scores['first']['extras'] = $this->getExtras($this->inns[0]);
        if (count($this->inns) > 1) {
            $this->scores['second']['runs'] = $this->getRuns($this->inns[1]);
            $this->scores['second']['overs'] = $this->getOvers($this->inns[1]);
            $this->scores['second']['wickets'] = $this->getWickets($this->inns[1]);
            $this->scores['second']['bats'] = $this->getBatsMan($this->inns[1]);
            $this->scores['second']['bowls'] = $this->getBowlers($this->inns[1]);
            $this->scores['second']['extras'] = $this->getExtras($this->inns[1]);
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

    public function getBatsMan($inn_id)
    {
        /*for ($i = 0; $i < count($this->inns); $i++) {
            if ($this->inns[$i] == $inn_id) {
                if ($i == 0) {
                    $team_id = $this->first_bat_bowl['bat'];
                } else if ($i == 1) {
                    $team_id = $this->first_bat_bowl['bowl'];
                }
            }
        }*/
        return DB::select('SELECT * from (SELECT sum(total_run) AS cum_run,player_bat FROM(SELECT sum(run) AS total_run, player_bat FROM balls WHERE
                      extra_type IS NULL AND innings_id=? GROUP BY player_bat UNION SELECT sum(run - 1) AS total_run,player_bat
                       FROM balls WHERE extra_type IS NOT NULL AND run > 1 AND extra_type = \'nb\' AND innings_id=? GROUP
                       BY player_bat)batting GROUP BY player_bat)example RIGHT JOIN players on player_id=player_bat inner join (SELECT count(*) AS total_ball, player_bat FROM balls WHERE innings_id=? AND (extra_type = "nb" or extra_type
                      is null or extra_type =\'by\' )  GROUP BY player_bat)example1 using(player_bat)', [$inn_id, $inn_id, $inn_id]);
    }

    public function getBowlers($inn_id)
    {
        return DB::select('select * from (select sum(run) as total_run,player_bowl from balls where innings_id=? group by player_bowl)example 
                    left JOIN players on player_id=player_bowl left join (select count(*) as wickets,player_bowl from balls where innings_id=? and 
                    incident is not null group by player_bowl)example2 using(player_bowl) left join (select count(*) as total_ball,player_bowl 
                    from balls where innings_id=? and extra_type is not null group by player_bowl)example3 using(player_bowl)', [$inn_id, $inn_id, $inn_id]);
    }

    public function genMatchInfo()
    {
        $this->match_data = Match::where('match_id', '=', 6)->with('teams')->get();
        foreach ($this->match_data[0]['teams'] as $team){
            if($this->match_data[0]['toss_winner']==$team['team_id']){
                $this->scores['basic']['toss_winner_team_name']=$team['team_name'];
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
                    $this->scores['basic']['bowling_team']=$team->team_name;
                }
                else{
                    $this->scores['basic']['batting_team']=$team->team_name;
                }
            }
        } else if ($this->match_data[0]->first_innings == 'bowl') {
            $this->first_bat_bowl['bowl'] = $this->match_data[0]->toss_winner;
            foreach ($this->match_data[0]->teams as $team) {
                if ($team->team_id != $this->match_data[0]->toss_winner) {
                    $this->first_bat_bowl['bat'] = $team->team_id;
                    $this->scores['basic']['batting_team']=$team->team_name;
                }
                else{
                    $this->scores['basic']['bowling_team']=$team->team_name;
                }
            }
        }
    }
}