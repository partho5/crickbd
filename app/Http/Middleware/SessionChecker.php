<?php

namespace App\Http\Middleware;

use Closure;
use App\Innings;

class SessionChecker
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
        $match_id=$request->route('id');
        $total_innings=Innings::where('match_id','=',$request->route('id'))->count();
        $unended=Innings::where('is_ended','=',0)->where('match_id','=',$match_id)->count();
        if($total_innings>=2 && $unended!=0){
            return redirect('/');
        }
        return $next($request);
    }
}
