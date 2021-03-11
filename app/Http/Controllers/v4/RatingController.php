<?php

namespace App\Http\Controllers\v4;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Log;
use App\EventRating;
use App\UserRating;
use App\Common\Utility;
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

            if(!$this->verifyChecksum($request)){
                return response()->json(["status"=>0,
                "message"=>'checksum not verified',
                "data"=>json_decode('{}')]);
            }
            Utility::stripXSS();
            $type = ($request->type) ? $request->type : 'event';
            //echo $request->rating; die;            
            $flag = 0;
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|max:255',
                'rating' => 'required|integer|max:5|min:1',
                'type' => 'in:event,trainer,center',
            ]);            

            if($validator->fails()){                
                return response()->json(['status'=>$status,'message'=>'invalid data set','data'=>json_decode("{}")]);               
            }   

            if($type == 'event'){

                $eventRating = EventRating::updateOrCreate(
                    ['event_id' => $request->event_id, 'email' => $this->string_replace("__","/",$request->email)],
                    [
                        'name' => $this->string_replace("__","/",$request->get('name')),
                        'rating' => $request->get('rating'),
                        'email' => $this->string_replace("__","/",$request->get('email'))
                        ]
                ); 
                $flag = 1;                   
                $rating = $this->getRating('event',$eventRating->event_id);                             
            }else{

                $userRating = UserRating::updateOrCreate(
                    ['email' => $this->string_replace("__","/",$request->email), 'user_id' => $request->user_id],
                    [
                        'name' => $this->string_replace("__","/",$request->get('name')),
                        'rating' => $request->get('rating'),
                        'email' => $this->string_replace("__","/",$request->get('email')),
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
