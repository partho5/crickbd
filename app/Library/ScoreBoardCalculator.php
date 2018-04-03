<?php
/**
 * Created by PhpStorm.
 * User: nrriaz
 * Date: 4/3/18
 * Time: 4:30 PM
 */

namespace App\Library;


use App\Innings;

class ScoreBoardCalculator
{

    public $match_id;
    public $inns = [];
    public $scores;

    public function __construct($id)
    {
        $this->match_id = $id;
    }

    public function getInnings()
    {
        $inngins = Innings::where([['match_id', '=', $this->match_id], ['is_ended', '=', 1]])->orderBy('created_at', 'asc')->get();
        foreach ($inngins as $inn) {
            array_push(
                $this->inns,
                $inn->innings_id
            );
        }
    }

    public function getBatsMan($inn_id)
    {

    }

    public function getBowlers($inn_id)
    {

    }

    public function getScores()
    {

    }

}