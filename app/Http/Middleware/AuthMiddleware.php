<?php

namespace App\Http\Middleware;
use Illuminate\Http\Response;
use Closure;

class AuthMiddleware
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
       // echo '<pre>';echo print_r($request->user()->role->role); die;        
        if ($request->user() && $request->user()->role->role != 'Admin' && $request->user()->role->role != 'Moderator')
        {
            return new Response(view('unauthorized')->with('role', 'Admin'));
        }else if(!$request->user()){
            return redirect('/');
        }
       
        return $next($request);
    }
}
