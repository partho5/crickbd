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
    private $batsman_run;
    private $batsman_ball;
    private $bowler_run;
    private $bowler_ball;
    private $wickets;
    public $overs;
    public $total_runs;
    public $ball_consumed = [];
    public $resume_data=[];

    public function __construct($id)
    {
        $this->match_id = $id;
        $this->getTableData();
        $this->createEmptyBallConsumed();
        $this->setBowlRun();
        $this->setBatBall();
        $this->setBatRun();
        $this->setBowlBall();
        $this->setWicket();
        array_push($this->resume_data,$this->total_runs,$this->overs,$this->ball_consumed);
    }

    public function getTableData()
    {
        $old_innings = Innings::where('match_id', '=', $this->match_id)->where('is_ended', '=', 0)->first();
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
        foreach($this->wickets as $wicket){
            $this->ball_consumed[$this->getIndex($wicket->player_bat)]->w_taker=$wicket->player_bowl;
            $this->ball_consumed[$this->getIndex($wicket->player_bat)]->out=$wicket->incident;
        }
    }
}