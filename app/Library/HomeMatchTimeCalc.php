<?php

namespace App\Library;

use App\Match;
use App\Innings;

class HomeMatchTimeCalc
{

    public $matches = [
        "today" => [],
        "upcoming" => [],
        "complete" => []
    ];

    public function __construct()
    {
        $this->todayMatches();
        $this->upcomingMatches();
        $this->completedMatches();
    }

    public function todayMatches()
    {
        array_push(
            $this->matches['today'],
            Match::whereNotNull('toss_winner')->whereNotNull('first_innings')->get()
        );
    }

    public function upcomingMatches()
    {
        array_push(
            $this->matches['upcoming'],
            Match::where('start_time', '>', date("Y-m-d H:i:s"))->with('teams')->whereNull('toss_winner')->get()
        );
    }

    public function completedMatches()
    {
        array_push(
          $this->matches['complete'],
            Match::withCount('innings')->has('innings', '=', 2)->orderByDesc('updated_at')->get()
        );
    }
}