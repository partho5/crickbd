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
    	$matches=$global_match->matches;
        return view('basic.homepage',compact('matches',json_encode($matches)));
    }
}
