<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use App\Event;
use App\User;

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
        
        $event = Event::all()->count(); 
        $trainer = User::where('role_id',3)->count(); 
        $center = User::where('role_id',2)->count(); 
        return view('home', compact('trainer','event','center'));
        //return view(['home']);
    }
}
