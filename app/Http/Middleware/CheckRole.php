<?php

namespace App\Http\Middleware;

use Illuminate\Http\Response;
use Closure;
use Auth;

class CheckRole
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $role_id = Auth::user()->role_id;
        $role = strtolower(Auth::user()->role->role);
        $user_id = Auth::user()->id;

      $modules = array(
            'admin' => [
                    'events'=> ['view','create','updated','delete'],
                    'users' => ['view','create','updated','delete'],
                    'users/center' => ['view','create','updated','delete'],
                    'moderator_list' => ['view','create','updated','delete'],
                    'audittrails' => ['view','create','updated','delete']
            ],
            'moderator' => [
                'users' => ['view','create','updated','delete']
            ]
            );
        $page = $request->route()->uri;
        if(!array_key_exists($page,$modules[$role])){
            return new Response(view('unauthorized')->with('role', 'Admin'));
        }
        return $next($request);
    }

}
?>