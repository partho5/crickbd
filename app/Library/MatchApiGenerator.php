<?php
/**
 * Created by PhpStorm.
 * User: nrriaz
 * Date: 3/27/18
 * Time: 9:17 PM
 */

namespace App\Library;

use App\Innings;
use App\Ball;
use App\Player;
use App\Match;
use Illuminate\Support\Facades\DB;


class MatchApiGenerator
{
    private $match_id;
    public $innings;
    private $batsman_run;
    private $batsman_ball;
    private $bowler_run;
    private $bowler_ball;
    private $wickets;
    public $overs;
    public $total_runs;
    public $ball_consumed = [];
    public $resume_data = [];
    private $isSecInn;
    public $last_ten = [];
    public $extras = [];
    public $first_innings = [
        "first_inn_over" => 0,
        "first_inn_wicket" => 0,
        "total_first" => 0
    ];
    public $on_strike = [];
    public $non_strike = [];
    public $bowler;
    public $old_bowler;
    public $partnership = [
        "ball" => 0,
        "run" => 0
    ];

    public function __construct($id)
    {
        $this->match_id = $id;
        $this->getOldInnings();
        $this->getTableData();
        $this->createEmptyBallConsumed();
        $this->setBowlRun();
        $this->setBatBall();
        $this->setBatRun();
        $this->setBowlBall();
        $this->getInnings();
        $this->setWicket();
        $this->getBatBowlMans();
        $this->getPartnerShip();
        array_push(
            $this->resume_data,
            $this->total_runs,
            $this->overs,
            $this->ball_consumed,
            $this->isSecInn,
            $this->last_ten,
            $this->extras,
            $this->first_innings,
            $this->on_strike,
            $this->non_strike,
            $this->bowler,
            $this->partnership
        );
    }

    public function getOldInnings()
    {
        $this->innings = Innings::where('match_id', '=', $this->match_id)->where('is_ended', '=', 0)->first();
    }

    public function getTableData()
    {
        $old_innings = $this->innings;
        $this->batsman_run = DB::select('SELECT sum(total_run) AS cum_run,player_bat FROM(SELECT sum(run) AS total_run, player_bat FROM balls WHERE 
                      extra_type IS NULL AND innings_id=? GROUP BY player_bat UNION SELECT sum(run - 1) AS total_run,player_bat
                       FROM balls WHERE extra_type IS NOT NULL AND run > 1 AND extra_type = \'nb\' AND innings_id=? GROUP 
                       BY player_bat)batting GROUP BY player_bat', [$old_innings->innings_id, $old_innings->innings_id]);
        $this->batsman_ball = DB::select('SELECT count(*) AS total_ball, player_bat FROM balls WHERE (extra_type IS NULL OR extra_type=
                        \'by\' OR (extra_type=\'nb\' AND run >1)) AND innings_id=?  GROUP BY player_bat', [$old_innings->innings_id]);
        $this->bowler_run = DB::select('SELECT sum(total_run) AS cum_run, player_bowl FROM (SELECT sum(run) AS total_run,player_bowl
                      FROM balls WHERE innings_id=?  GROUP BY player_bowl
                      UNION SELECT sum(-run) AS ex_run,player_bowl FROM balls WHERE innings_id=? AND extra_type= 
                       \'by\' GROUP BY player_bowl)sample GROUP BY player_bowl', [$old_innings->innings_id, $old_innings->innings_id]);
        $this->bowler_ball = DB::select('SELECT count(DISTINCT ball_number) AS total_ball,player_bowl FROM balls WHERE innings_id=? 
                      GROUP BY player_bowl', [$old_innings->innings_id]);
        $this->wickets = DB::select('SELECT * FROM balls WHERE innings_id=? AND incident IS NOT NULL', [$old_innings->innings_id]);
        $this->overs = DB::select('SELECT max(ball_number) AS overs FROM balls WHERE innings_id=?', [$old_innings->innings_id]);
        $this->total_runs = DB::select('SELECT sum(run) AS total_run FROM balls WHERE innings_id=?', [$old_innings->innings_id]);
        $last_ten = DB::select('SELECT if(concat(if(run=0,\'\',run),ifnull(incident,\'\'),ifnull(extra_type, 
                        \'\'))=\'\',0,concat(if(run=0,\'\',run),ifnull(incident,\'\'),ifnull(extra_type, 
                        \'\'))) AS for_ball FROM (SELECT ball_id,run,extra_type,ball_number,incident FROM balls 
                         WHERE innings_id=? AND (extra_type=\'by\' OR extra_type IS NULL) UNION SELECT ball_id, 
                        run-1 AS run,extra_type,ball_number,incident FROM balls WHERE innings_id=? AND 
                        (extra_type=\'nb\' OR extra_type=\'wd\'))t1 ORDER BY ball_number DESC, ball_id DESC 
                        LIMIT 10', [$old_innings->innings_id, $old_innings->innings_id]);
        $extra_runs = DB::select('SELECT concat(if(run=0,\'\',run),extra_type) AS for_ex,ball_id,ball_number 
                    FROM (SELECT ball_id,run-1 AS run,ball_number,extra_type FROM balls WHERE innings_id=24
                    AND (extra_type=\'nb\' OR extra_type=\'wd\') UNION SELECT ball_id,run, ball_number,
                    extra_type FROM balls WHERE innings_id=24 AND extra_type=\'by\')sample',
            [$old_innings->innings_id, $old_innings->innings_id]);
        foreach ($last_ten as $last_ball) {
            array_push($this->last_ten, $last_ball->for_ball);
        }
        foreach ($extra_runs as $extra) {
            array_push($this->extras, $extra->for_ex);
        }
    }

    public function createEmptyBallConsumed()
    {
        $full_match = Match::with('teams.players')->find($this->match_id);
        foreach ($full_match->teams as $team) {
            foreach ($team->players as $player) {
                $player->w_taker = '';
                $player->ball = 0;
                $player->id = $player->player_id;
                $player->out = null;
                $player->run = 0;
                unset($player->player_name, $player->jersey, $player->team_id, $player->player_id);
                array_push($this->ball_consumed, $player);
            }
        }
    }

    public function getIndex($player_id)
    {
        for ($i = 0; $i < count($this->ball_consumed); $i++) {
            if ($this->ball_consumed[$i]->id == $player_id) {
                return $i;
                break;
            }
        }
    }

    public function setBatRun()
    {
        foreach ($this->batsman_run as $bat) {
            $this->ball_consumed[$this->getIndex($bat->player_bat)]->run = $bat->cum_run;
        }
    }

    public function setBatBall()
    {
        foreach ($this->batsman_ball as $ball) {
            $this->ball_consumed[$this->getIndex($ball->player_bat)]->ball = $ball->total_ball;
        }
    }

    public function setBowlRun()
    {
        foreach ($this->bowler_run as $run) {
            $this->ball_consumed[$this->getIndex($run->player_bowl)]->run = $run->cum_run;
        }
    }

    public function setBowlBall()
    {
        foreach ($this->bowler_ball as $ball) {
            $this->ball_consumed[$this->getIndex($ball->player_bowl)]->ball = $ball->total_ball;
        }
    }

    public function setWicket()
    {
        foreach ($this->wickets as $wicket) {
            if ($wicket->who_out == 0) {
                $this->ball_consumed[$this->getIndex($wicket->non_strike)]->w_taker = $wicket->player_bowl;
            } else {
                $this->ball_consumed[$this->getIndex($wicket->player_bat)]->w_taker = $wicket->player_bowl;
            }
            $this->ball_consumed[$this->getIndex($wicket->player_bat)]->out = $wicket->incident;
        }
    }

    public function getInnings()
    {
        if (Innings::where('match_id', '=', $this->match_id)->count() > 1) {
            $this->isSecInn = true;
            $first_inn = Innings::where('match_id', '=', $this->match_id)->where('is_ended', '=', 1)->first();
            $first_inn_id = $first_inn->innings_id;
            $this->first_innings['first_inn_over'] = Ball::where('innings_id', '=', $first_inn_id)->max('ball_number');
            $this->first_innings['total_first'] = Ball::where('innings_id', '=', $first_inn_id)->sum('run');
            $this->first_innings['first_inn_wicket'] = Ball::where('innings_id', '=', $first_inn_id)->whereNotNull('incident')->count();
        } else {
            $this->isSecInn = false;
        }
    }

    public function getBatBowlMans()
    {
        $bowl_bat_man = DB::select('SELECT max(ball_number),player_bat,player_bowl,non_strike FROM balls WHERE innings_id=?'
            , [$this->innings->innings_id]);
        $this->on_strike['id'] = $bowl_bat_man->player_bat;
        $this->non_strike['id'] = $bowl_bat_man->non_strike;
        $this->bowler = $bowl_bat_man->player_bowl;
    }

    public function getPartnerShip()
    {
        $this->partnership['run'] = Ball::where('innings_id', '=', $this->innings->innings_id)->where('player_bat', '=', $this->on_strike->id)->
        where('non_strike', '=', $this->non_strike->id)->sum('run');
        $this->partnership['ball'] = Ball::where('innings_id', '=', $this->innings->innings_id)->where('player_bat', '=', $this->on_strike->id)->
        where('non_strike', '=', $this->non_strike->id)->distinct('ball_number')->count();
    }

}