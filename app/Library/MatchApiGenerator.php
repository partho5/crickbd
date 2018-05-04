<?php
/**
 * Created by PhpStorm.
 * User: nrriaz
 * Date: 3/27/18
 * Time: 9:17 PM
 */

namespace App\Library;

use App\Ball;
use App\Innings;
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
    public $wickets;
    public $overs;
    public $total_runs;
    public $ball_consumed = [];
    public $resume_data = [];
    private $isSecInn;
    public $last_ten = [];
    public $extras = [];
    public $balldata = [];
    public $first_innings = [
        "first_inn_over" => 0,
        "first_inn_wicket" => 0,
        "total_first" => 0,
    ];
    public $on_strike = [
        "id" => '',
    ];
    public $non_strike = [
        "id" => '',
    ];
    public $bowler;
    public $old_bowler = null;
    public $partnership = [
        "ball" => 0,
        "run" => 0,
    ];

    public function __construct($id, $innings)
    {
        $this->match_id = $id;
        $this->innings = $innings;
    }

    public function getResumeData()
    {
        $this->getTableData();
        $this->createEmptyBallConsumed();
        $this->setBowlRun();
        $this->setBatBall();
        $this->setBatRun();
        $this->getBallData();
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
            $this->partnership,
            $this->balldata,
            $this->old_bowler
        );
    }

    public function checkInnings()
    {
        $inn_number = Innings::where('match_id', '=', $this->match_id)->count();
        $is_running = Innings::where('match_id', '=', $this->match_id)->where('is_ended', '=', 1)->count();
        if ($inn_number == 1 && $is_running == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function getTableData()
    {
        $old_innings = $this->innings;
        $this->overs = DB::select('SELECT max(cast(ball_number as decimal(4,1))) AS overs FROM balls WHERE innings_id=?', [$old_innings->innings_id]);
        $this->total_runs = DB::select('SELECT sum(run) AS total_run FROM balls WHERE innings_id=?', [$old_innings->innings_id]);
        $last_ten = DB::select('SELECT if(concat(if(run=0,\'\',run),if(incident IS NULL,\'\',"W"),ifnull(extra_type,
                        \'\'))=\'\',0,concat(if(run=0,\'\',run),if(incident IS NULL,\'\',"W"),ifnull(extra_type,
                        \'\'))) AS for_ball FROM (SELECT ball_id,run,extra_type,ball_number,incident FROM balls
                         WHERE innings_id=? AND (extra_type=\'by\' OR extra_type IS NULL) UNION SELECT ball_id,
                        run-1 AS run,extra_type,ball_number,incident FROM balls WHERE innings_id=? AND
                        (extra_type=\'nb\' OR extra_type=\'wd\'))t1 ORDER BY cast(ball_number as decimal(4,1)) DESC, ball_id DESC
                        LIMIT 10', [$old_innings->innings_id, $old_innings->innings_id]);
        $extra_runs = DB::select('SELECT * FROM (SELECT ball_id,0 AS run,ball_number,extra_type FROM balls
                    WHERE innings_id=? AND extra_type=\'nb\' UNION SELECT ball_id,run,
                    ball_number, extra_type FROM balls WHERE innings_id=? AND extra_type=\'by\' UNION
                    SELECT ball_id,run-1 AS run,ball_number,extra_type FROM balls
                    WHERE innings_id=? AND  extra_type=\'wd\')sample ORDER BY
                    ball_id', [$old_innings->innings_id, $old_innings->innings_id, $old_innings->innings_id]);

        foreach ($last_ten as $last_ball) {
            array_push($this->last_ten, $last_ball->for_ball);
        }
        foreach ($extra_runs as $extra) {
            $extra_array = [
                "extra" => $extra->run,
                "type" => $extra->extra_type,
            ];
            array_push($this->extras, $extra_array);
        }
    }

    public function createEmptyBallConsumed()
    {
        $full_match = Match::with('teams.players')->find($this->match_id);
        foreach ($full_match->teams as $team) {
            foreach ($team->players as $player) {
                $player->w_taker = '';
                $player->ball = 0;
                $player->id = (int)$player->player_id;
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
        $this->batsman_run = DB::select('SELECT sum(total_run) AS cum_run,player_bat FROM(SELECT sum(run) AS total_run, player_bat FROM balls WHERE
                      extra_type IS NULL AND innings_id=? GROUP BY player_bat UNION SELECT sum(run - 1) AS total_run,player_bat
                       FROM balls WHERE extra_type IS NOT NULL AND run > 1 AND extra_type = \'nb\' AND innings_id=? GROUP
                       BY player_bat)batting GROUP BY player_bat', [$this->innings->innings_id, $this->innings->innings_id]);
        foreach ($this->batsman_run as $bat) {
            $this->ball_consumed[$this->getIndex($bat->player_bat)]->run = (int)$bat->cum_run;
        }
    }

    public function setBatBall()
    {
        $this->batsman_ball = DB::select('SELECT count(*) AS total_ball, player_bat FROM balls WHERE innings_id=? AND (extra_type = "nb" or extra_type
                      is null or extra_type =\'by\' )  GROUP BY player_bat', [$this->innings->innings_id]);
        foreach ($this->batsman_ball as $ball) {
            $this->ball_consumed[$this->getIndex($ball->player_bat)]->ball = (int)$ball->total_ball;
        }
    }

    public function setBowlRun()
    {
        $this->bowler_run = DB::select('SELECT sum(total_run) AS cum_run, player_bowl FROM (SELECT sum(run) AS total_run,player_bowl
                      FROM balls WHERE innings_id=?  GROUP BY player_bowl
                      UNION SELECT sum(-run) AS ex_run,player_bowl FROM balls WHERE innings_id=? AND extra_type=
                       \'by\' GROUP BY player_bowl)sample GROUP BY player_bowl', [$this->innings->innings_id, $this->innings->innings_id]);
        foreach ($this->bowler_run as $run) {
            $this->ball_consumed[$this->getIndex($run->player_bowl)]->run = (int)$run->cum_run;
        }
    }

    public function setBowlBall()
    {
        $this->bowler_ball = DB::select('SELECT count(DISTINCT ball_number) AS total_ball,player_bowl FROM balls WHERE innings_id=?
                      AND (extra_type is null or extra_type = "by") GROUP BY player_bowl', [$this->innings->innings_id]);
        foreach ($this->bowler_ball as $ball) {
            $this->ball_consumed[$this->getIndex($ball->player_bowl)]->ball = (int)$ball->total_ball;
        }
    }

    public function setWicket()
    {
        $this->wickets = DB::select('SELECT * FROM balls WHERE innings_id=? AND incident IS NOT NULL', [$this->innings->innings_id]);
        foreach ($this->wickets as $wicket) {
            if ($wicket->who_out == 0) {
                $this->ball_consumed[$this->getIndex($wicket->non_strike)]->w_taker = (int)$wicket->player_bowl;
                $this->ball_consumed[$this->getIndex($wicket->non_strike)]->out = $wicket->incident;
            } else {
                $this->ball_consumed[$this->getIndex($wicket->player_bat)]->w_taker = (int)$wicket->player_bowl;
                $this->ball_consumed[$this->getIndex($wicket->player_bat)]->out = $wicket->incident;
            }
        }
    }

    public function getInnings()
    {
        if (Innings::where('match_id', '=', $this->match_id)->count() > 1 || $this->checkInnings()) {
            $this->isSecInn = true;
            $first_inn = Innings::where('match_id', '=', $this->match_id)->where('is_ended', '=', 1)->first();
            $first_inn_id = (int)$first_inn->innings_id;
            $this->first_innings['first_inn_over'] = DB::select('SELECT max(cast(ball_number as decimal(4,1))) AS overs FROM balls WHERE innings_id=?', [$this->innings->innings_id])[0]->overs;
            $this->first_innings['total_first'] = Ball::where('innings_id', '=', $first_inn_id)->sum('run');
            $this->first_innings['first_inn_wicket'] = Ball::where('innings_id', '=', $first_inn_id)->whereNotNull('incident')->count();
        } else {
            $this->isSecInn = false;
        }
    }

    public function getBatBowlMans()
    {
        $bowl_bat_man = DB::select('SELECT * FROM balls WHERE ball_id=(SELECT max(ball_id) FROM balls WHERE
                        innings_id=? GROUP BY innings_id)', [$this->innings->innings_id]);
        $this->bowler = (int)$bowl_bat_man[0]->player_bowl;
        $run = $bowl_bat_man[0]->run;
        $extra_type = $bowl_bat_man[0]->extra_type;
        $incident = $bowl_bat_man[0]->incident;
        if ($incident != null && $extra_type == null) {
            if ($bowl_bat_man[0]->who_out == 1) {
                $this->on_strike['id'] = null;
                $this->non_strike['id'] = $bowl_bat_man[0]->non_strike;
            } else {
                $this->non_strike['id'] = null;
                $this->on_strike['id'] = $bowl_bat_man[0]->player_bat;
            }
        } else if ($extra_type == null || $extra_type == 'by') {
            if ($run % 2 == 0) {
                $this->on_strike['id'] = $bowl_bat_man[0]->player_bat;
                $this->non_strike['id'] = $bowl_bat_man[0]->non_strike;
            } else {
                $this->on_strike['id'] = $bowl_bat_man[0]->non_strike;
                $this->non_strike['id'] = $bowl_bat_man[0]->player_bat;
            }
        } else if (($extra_type == 'nb' || $extra_type == 'wd') && $run > 1) {
            if (($run - 1) % 2 == 0) {
                $this->on_strike['id'] = $bowl_bat_man[0]->player_bat;
                $this->non_strike['id'] = $bowl_bat_man[0]->non_strike;
            } else {
                $this->on_strike['id'] = $bowl_bat_man[0]->non_strike;
                $this->non_strike['id'] = $bowl_bat_man[0]->player_bat;
            }
        } else {
            $this->on_strike['id'] = $bowl_bat_man[0]->player_bat;
            $this->non_strike['id'] = $bowl_bat_man[0]->non_strike;
        }
        if (($this->balldata['extra_type'] == null || $this->balldata['extra_type'] == 'by') && substr($this->overs[0]->overs, -1) == 0) {
            $this->swapStrike();
            $this->old_bowler = $this->bowler;
            $this->bowler = null;
        }
    }

    public function swapStrike()
    {
        $x = $this->on_strike['id'];
        $this->on_strike['id'] = $this->non_strike['id'];
        $this->non_strike['id'] = $x;
    }

    public function getPartnerShip()
    {
        $this->partnership['run'] = Ball::where('innings_id', '=', $this->innings->innings_id)
            ->where([['player_bat', '=', $this->on_strike['id']], ['non_strike', '=', $this->non_strike['id']]])->
            orWhere([['player_bat', '=', $this->non_strike['id']], ['non_strike', '=', $this->on_strike['id']]])->sum('run');
        $this->partnership['ball'] = DB::select('SELECT count(*) as balls FROM balls where ((player_bat=? AND non_strike=?) or (player_bat=? AND non_strike=?)) AND
            innings_id=? AND (extra_type is null or extra_type="nb" or extra_type="by")', [$this->on_strike['id'], $this->non_strike['id'],
            $this->non_strike['id'], $this->on_strike['id'], $this->innings->innings_id])[0]->balls;
    }

    public function getBallData()
    {
        $last_ball_data = DB::select('SELECT * from balls where ball_id=(SELECT max(ball_id) FROM balls WHERE innings_id=? GROUP BY
                          innings_id)', [$this->innings->innings_id]);
        $this->balldata = [
            "ball_run" => $last_ball_data[0]->run,
            "incident" => $last_ball_data[0]->incident,
            "extra_type" => $last_ball_data[0]->extra_type,
            "who_out" => $last_ball_data[0]->who_out,
        ];
    }
}