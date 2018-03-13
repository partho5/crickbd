<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Http\Request;
use App\Http\Requests\StoreMatch;
use App\Match;
use App\Team;

class MatchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth',['only'=>['create','store']]);
    }

    public function index()
    {


        return view('match.details');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('match.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMatch $request)
    {
        $match=Match::create([
           'user_id'=>Auth::user()->user_id,
           'over'=>$request->total_over,
           'location'=>$request->location,
           'start_time'=>$request->match_time,
           'player_total'=>$request->total_player
        ]);
        $teams=[
            new Team(['team_name'=>$request->team1]),
            new Team(['team_name'=>$request->team2])
        ];
        $match_add=Match::find($match->match_id);
        $teams=$match_add->teams()->saveMany($teams);
        return redirect('/match/'.$match->match_id.'/addplayer');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function addPlayers($id){

        $match=Match::where('match_id','=',$id)->with('teams')->first();
        return view('match.add_players',compact('match',$match));
        
    }

    public function showAdminPanel(){


        return view('match.admin.panel');
    }
}
