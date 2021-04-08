<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use App\Event;
use App\User;
use App\Models\AasanaCategory;
use App\Models\AasanaSubCategory;
use App\Models\Aasana;
use App\Poll;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {        
        $this->middleware('auth');
        $this->middleware('App\Http\Middleware\ModeratorType');
       // $this->middleware('App\Http\Middleware\checkRole');
        
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        
       
		if(Auth::user()->moderator_id==1)
        {
            $total_aasana_category=AasanaCategory::where('status','1')->count();
            $total_aasana_sub_category=AasanaSubCategory::where('status','1')->count();
            $total_aasana=Aasana::where('status','1')->count();
            return view('aasana_home', compact('total_aasana_category','total_aasana_sub_category','total_aasana')); 
        }
		elseif(Auth::user()->moderator_id==5)
        {
            $total_polls = Poll::count();
            $total_activated_polls = Poll::where('status',1)->count();
			$total_responded_poll = DB::table('audience_poll_responses')->count(DB::raw('DISTINCT user_id'));
            return view('polls.home', compact('total_polls','total_activated_polls','total_responded_poll')); 
        }
		elseif(Auth::user()->moderator_id==6)
        {
            $total_polls = Poll::count();
            $total_activated_polls = Poll::where('status',1)->count();
			$total_responded_poll = DB::table('audience_poll_responses')->count(DB::raw('DISTINCT user_id'));
            return view('polls.home', compact('total_polls','total_activated_polls','total_responded_poll')); 
        }
		elseif(Auth::user()->moderator_id==7){
			$event = Event::all()->count(); 
			$approvedEvent = Event::where('status','1')->count(); 
			$trainer = User::where('role_id',3)->count(); 
			$approvedTrainer = User::where([['role_id',3],['status','1']])->count(); 
			
			return view('yoga_home', compact('trainer','event','approvedEvent','approvedTrainer')); 
		}
		else
		{
			 $event = Event::all()->count(); 
			$trainer = User::where('role_id',3)->count(); 
			$center = User::where('role_id',2)->count(); 
			return view('home', compact('trainer','event','center')); 
        //return view(['home']);
			
		}
	}
}
