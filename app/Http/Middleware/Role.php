<?php

namespace App\Http\Middleware;
use Closure;
use Auth;
use App\User; 
class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
       $user = Auth::user(); 
       if(($user->role==1 &&  $role=='provider') || ($user->role==2 &&  $role=='customer')){
        return $next($request);
        
       }else{
        return response()->json(['error'=>'Unauthorized access!'], 401);
       }    


        
    }

}
