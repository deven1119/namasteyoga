<?php

namespace App\Http\Controllers;


use App\User;
use App\Visitor;
use App\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Exceptions\Handler;
use Illuminate\Support\Facades\DB;
//use Carbon\Carbon;

class PagesController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      try{

        //select role_id, count(role_id) AS count FROM users GROUP BY role_id HAVING role_id > 1


        $ip = \Request::ip();
        $visit_date = date('Y-m-d');

        $visitor = Visitor::firstOrCreate(['ip'=>$ip,'visit_date'=>$visit_date]);
        
        if($visitor->visit_date != date('Y-m-d')){
          $visitor->hits = $visitor->hits+1;
          $visitor->visit_date = $visit_date;
          $visitor->save();
        }
               

        $visitorObj = new Visitor();
        $visitorCount = $visitorObj->getVisitorCount(); 
        $data = User::select('role_id',DB::raw('count(role_id) as role_count'))
        ->groupBy('role_id')
        ->havingRaw('role_id > 1')->get();

        $event = Event::select(DB::raw('count(*) as event_count'))
        ->where('status','1')
        ->get();
        $trainer = isset($data[1]->role_count) ? $data[1]->role_count : 0;
        $center = isset($data[0]->role_count) ? $data[0]->role_count : 0;
        $event = isset($event[0]->event_count) ? $event[0]->event_count : 0;
        //echo '<pre>';print_r($data[1]->role_count); die;        
        return view('pages.index',["center"=>$center,
        "trainer"=>$trainer,
        "events"=>$event,
        'visitor_count'=>$visitorCount
        ]);
      }catch(Exception $e){
        abort(500, $e->message());
      }
    }

    
}
