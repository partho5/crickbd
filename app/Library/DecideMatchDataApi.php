<?php
/**
 * Created by PhpStorm.
 * User: nrriaz
 * Date: 3/31/18
 * Time: 3:30 PM
 */

namespace App\Library;

use App\Match;
use App\Innings;
use App\Ball;
use App\Library\MatchApiGenerator;
use App\Library\DecideMatchWinner;

class DecideMatchDataApi
{
    public $match_id;
    public $match_data=[];

    public function __construct($id)
    {
        $this->match_id = $id;
        $this->getInnings();
    }

    public function getInnings()
    {
        $innings = Match::find($this->match_id)->innings()->get();
        if (count($innings) == 0) {
            $this->beforeStart();
        } else if (count($innings) == 1) {
            if ($innings[0]->is_ended == 0) {
                if (Innings::find($innings[0]->innings_id)->ball()->count() > 0) {
                    $this->running($innings[0]);
                } else {
                    $this->beforeStart();
                }
            } else if ($innings[0]->is_ended == 1) {
                $this->firstInnEnded($innings[0]);
            }
        } else if (count($innings) == 2) {
            if ($innings[0]->is_ended == 1 && $innings[1]->is_ended == 0) {
                if (Innings::find($innings[1]->innings_id)->ball()->count() > 0) {
                    $this->running($innings[1]);
                } else {
                    $this->firstInnEnded($innings[0]);
                }
            } else if ($innings[0]->is_ended == 1 && $innings[1]->is_ended == 1) {
                $this->matchEnded($innings[0],$innings[1]);
            }
        } else {
            $this->match_data = ['More than two innings!'];
        }
    }

    public function beforeStart()
    {
        $beforeArray = [
            "stage" => "before_match_start"
        ];
        array_push(
            $this->match_data,
            $beforeArray,
            Match::find($this->match_id)
        );
    }

    public function running($innings)
    {
        $running_array = [
            "stage" => "innings_already_started"
        ];
        $running_api = new MatchApiGenerator($this->match_id, $innings);
        array_push(
            $this->match_data,
            $running_array,
            $running_api->resume_data
        );
    }

    public function firstInnEnded($innings)
    {
        $first_ended = [
            "stage" => "first_innings_ended"
        ];
        $running_api = new MatchApiGenerator($this->match_id, $innings);
        array_push(
            $this->match_data,
            $first_ended,
            $running_api->resume_data
        );
    }

    public function matchEnded($innings1,$innings2)
    {
        $match_ended = [
            "stage" => "match_ended"
        ];
        $ended_api = new DecideMatchWinner($this->match_id,$innings1->innings_id, $innings2->innings_id);
        array_push(
            $this->match_data,
            $match_ended,
            $ended_api->full_match
        );
    }
}