<?php

namespace App\Library;

use App\Match;
use App\Innings;

class HomeMatchTimeCalc
{

    public $matches = [
        "today" => [],
        "upcoming" => [],
        "complete" => [],
        "delayed" => []
    ];

    public function __construct()
    {
        $this->todayMatches();
        $this->upcomingMatches();
        $this->completedMatches();
        $this->delayedMatches();
    }

    public function todayMatches()
    {
        array_push(
            $this->matches['today'],
            Match::whereNotNull('toss_winner')->whereNotNull('first_innings')->withCount(['innings' => function ($query) {
                $query->where('is_ended', '=', 1);
            }])->having('innings_count', '!=', 2)->orderBy('start_time', 'asc')->get()
        );
    }

    public function upcomingMatches()
    {
        array_push(
            $this->matches['upcoming'],
            Match::where('start_time', '>=', date("Y-m-d H:i:s"))->with('teams')->whereNull('toss_winner')->orderBy('start_time', 'asc')->get()
        );
    }

    public function completedMatches()
    {
        array_push(
            $this->matches['complete'],
            Match::withCount(['innings' => function ($query) {
                $query->where('is_ended', '=', 1);
            }])->having('innings_count', '=', 2)->orderByDesc('updated_at')->get()
        );
    }

    public function delayedMatches()
    {
        array_push(
          $this->matches['delayed'],
            Match::where('start_time', '<', date("Y-m-d H:i:s"))->with('teams')->whereNull('toss_winner')->orderBy('start_time', 'desc')->get()
        );
    }
}