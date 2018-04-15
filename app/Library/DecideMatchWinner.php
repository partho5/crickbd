<?php
/**
 * Created by PhpStorm.
 * User: nrriaz
 * Date: 4/2/18
 * Time: 7:30 PM
 */

namespace App\Library;

use App\Match;
use App\Innings;
use App\Ball;
use Illuminate\Support\Facades\DB;
use App\Library\MatchApiGenerator;


class DecideMatchWinner
{
    public $match_id;
    public $first_inn;
    public $sec_inn;
    public $full_sec;
    public $ball_consumed;
    private $first_details = [
        "run" => '',
        "over" => '',
        "wicket" => ''
    ];
    private $second_details = [
        "run" => '',
        "over" => '',
        "wicket" => ''
    ];
    public $full_match = [];

    public function __construct($id, $first, $sec)
    {
        $this->match_id = $id;
        $this->first_inn = $first->innings_id;
        $this->sec_inn = $sec->innings_id;
        $this->full_sec=$sec;
        $this->getFirstInnings();
        $this->getSecondInnings();
        $this->getBallConsumedArray();
        array_push(
            $this->full_match,
            $this->first_details,
            $this->second_details,
            $this->ball_consumed
        );
    }

    public function getFirstInnings()
    {
        $this->first_details['run'] = DB::select('SELECT sum(run) AS total_run FROM balls WHERE innings_id=?', [$this->first_inn])[0]->total_run;
        $this->first_details['wicket'] = DB::select('SELECT count(*) as wicket FROM balls WHERE innings_id=? AND incident IS NOT NULL', [$this->first_inn])[0]->wicket;
        $this->first_details['over'] = DB::select('SELECT max(cast(ball_number as decimal(4,1))) AS overs FROM balls WHERE innings_id=?', [$this->first_inn])[0]->overs;
    }

    public function getSecondInnings()
    {
        $this->second_details['run'] = DB::select('SELECT sum(run) AS total_run FROM balls WHERE innings_id=?', [$this->sec_inn])[0]->total_run;
        $this->second_details['wicket'] = DB::select('SELECT count(*) as wicket FROM balls WHERE innings_id=? AND incident IS NOT NULL', [$this->sec_inn])[0]->wicket;
        $this->second_details['over'] = DB::select('SELECT max(cast(ball_number as decimal(4,1))) AS overs FROM balls WHERE innings_id=?', [$this->sec_inn])[0]->overs;
    }

    public function getBallConsumedArray()
    {
        $ball_consumed_ob=new MatchApiGenerator($this->match_id,$this->full_sec);
        $ball_consumed_ob->getResumeData();
        $this->ball_consumed= $ball_consumed_ob->ball_consumed;
    }

}