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


class DecideMatchWinner
{
    public $match_id;
    public $first_inn;
    public $sec_inn;
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
    public $full_match=[];

    public function __construct($id, $first, $sec)
    {
        $this->match_id = $id;
        $this->first_inn=$first;
        $this->sec_inn=$sec;
        $this->getFirstInnings();
        $this->getSecondInnings();
        array_push(
          $this->full_match,
          $this->first_details,
          $this->second_details
        );
    }

    public function getFirstInnings()
    {
        $this->first_details['run'] = DB::select('SELECT sum(run) AS total_run FROM balls WHERE innings_id=?', [$this->first_inn])[0]->total_run;
        $this->first_details['wicket'] = DB::select('SELECT count(*) as wicket FROM balls WHERE innings_id=? AND incident IS NOT NULL', [$this->first_inn])[0]->wicket;
        $this->first_details['over'] = DB::select('SELECT max(ball_number) AS overs FROM balls WHERE innings_id=?', [$this->first_inn])[0]->overs;
    }

    public function getSecondInnings()
    {
        $this->second_details['run'] = DB::select('SELECT sum(run) AS total_run FROM balls WHERE innings_id=?', [$this->sec_inn])[0]->total_run;
        $this->second_details['wicket'] = DB::select('SELECT count(*) as wicket FROM balls WHERE innings_id=? AND incident IS NOT NULL', [$this->sec_inn])[0]->wicket;
        $this->second_details['over'] = DB::select('SELECT max(ball_number) AS overs FROM balls WHERE innings_id=?', [$this->sec_inn])[0]->overs;
    }

}