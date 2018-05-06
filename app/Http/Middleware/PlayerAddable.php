<?php

namespace App\Http\Middleware;

use Closure;
use App\Team;

class PlayerAddable
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $team_data=Team::where('match_id','=',$request->route('id'))->with('players')->get();
        if(count($team_data[0]->players)>0 || count($team_data[1]->players)>0){
            return redirect('/mygames');
        }
        return $next($request);
    }
}
