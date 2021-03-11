<?php
namespace App\Http\Middleware;
use Illuminate\Http\Response;
use Closure;
use Auth;
use DB;
use Session;

Class ModeratorType 
{
    public function handle($request, Closure $next)
    {
        $moderator = array();
        if(Auth::User()){
            $user_id = Auth::User()->id;
        
            $moderatorTypes = DB::table('user_moderator_type')
                                    ->where('user_id',$user_id)
                                    ->select('moderator_id')
                                    ->get();
            foreach($moderatorTypes as $mode){
                $moderator[] = $mode->moderator_id;
            }
            $request->session()->put('moderatorTypes',$moderator);
        }

        return $next($request);
    }
}
?>