<?php

namespace App\Library;

use App\Match;

class HomeMatchTimeCalc {
	public function todayMatches() {

	}
	public function upcomingMatches() {
		return Match::where('start_time', '>', date("Y-m-d H:i:s"))->with('teams')->get();
	}
	public function completedMatches() {

	}
}