<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Library\HomeMatchTimeCalc;

class HomepageController extends Controller
{


    public function __construct()
    {
    }

    public function index(){
    	$global_match=new HomeMatchTimeCalc();
    	$up_matches=$global_match->upcomingMatches();
        return view('basic.homepage',compact('up_matches',json_encode($up_matches)));
    }
}
