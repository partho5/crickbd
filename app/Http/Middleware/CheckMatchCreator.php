<?php

namespace App\Http\Middleware;

use Closure;
use App\Match;
use Illuminate\Support\Facades\Auth;

class CheckMatchCreator
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
        if((Match::where('match_id','=',$request->route('id'))->first()->user_id)!=Auth::id()){
            return redirect('/');
        }
        return $next($request);
    }
}
