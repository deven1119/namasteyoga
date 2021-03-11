<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Log;
use App\EventRating;
use App\UserRating;
use Config;

class RatingController extends Controller
{
    //

    /**
     * Show the form for get user lsit.
     *
     * @return \Illuminate\Http\Response
     */
    public function addRating(Request $request){
        try{
            $status = 0;
            $message = "";
            $type = ($request->type) ? $request->type : 'event';
            //echo $request->rating; die;            
            $flag = 0;
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255',
                'rating' => 'required|integer|max:5|min:1',
                'type' => 'in:event,trainer,center',
            ]);            

            if($validator->fails()){                
                return response()->json(['status'=>$status,'message'=>'invalid data set','data'=>json_decode("{}")]);               
            }   

            if($type == 'event'){

                $eventRating = EventRating::updateOrCreate(
                    ['event_id' => $request->event_id, 'email' => $request->email],
                    [
                        'name' => $request->get('name'),
                        'rating' => $request->get('rating'),
                        'email' => $request->get('email')
                        ]
                ); 
                $flag = 1;                   
                $rating = $this->getRating('event',$eventRating->event_id);                             
            }else{

                $userRating = UserRating::updateOrCreate(
                    ['email' => $request->email, 'user_id' => $request->user_id],
                    [
                        'name' => $request->get('name'),
                        'rating' => $request->get('rating'),
                        'email' => $request->get('email'),
                        'type' => $request->get('type')
                        ]
                );
                $rating = $this->getRating($request->get('type'),$eventRating->user_id);
                $flag = 1;
            }      
            if($flag){
                return response()->json(['status'=>1,'message'=>"",'data'=>$rating]);        
            }else{
                return response()->json(['status'=>1,'message'=>$type.' rating not recorded','data'=>[]]);        
            }
                                                        
        }catch(Exception $e){   
            $message = "Data exception";
            return response()->json(['status'=>$status,'message'=>$message,'data'=>json_decode("{}")]);
        }            
        //$users[0]->role    
    }
}
