<?php

namespace App\Http\Controllers\v3;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Controllers\Traits\SendMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\NotifyMe;
use App\EventRating;
use App\UserRating;
use Config;
use Mail;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    /**
   * 
   * Notify mail sent */
  public function sendNotificationMail($userData){
    
    //email sent code for event creator
    //$userData = User::where('email',$email)->first();
    $app = app();
    $data = $app->make('stdClass');
    
    $data->type = $userData->type;//$userData->type;
    $data->city_id = $userData->city_id;
    $data->state_id = $userData->state_id;
    $data->country_id = $userData->country_id;
    $data->country_name =  $userData->Country->name; 
    $data->state_name =  $userData->State->name; 
    $data->city_name =  $userData->City->name; 
    $notifyObj = new NotifyMe();    
    $notifyList = $notifyObj->getNotifiedDataByType($data);
    
    if($notifyList->count() > 0){
        Log::info(['add '.$data->type.' notify data found',$notifyList]);
        $mailArr = [];
        $ids = [];
        foreach($notifyList as $k=>$v){
            if(!in_array($v->mail,$mailArr)){                            
                array_push($mailArr,$v->email);
                array_push($ids,$v->id);
                //$ids[] = $v->id;
            }                                                   
        }                              
        $maildata['email'] = $mailArr;
        $maildata['city_name'] = $data->city_name;
        $maildata['type'] = ucfirst($data->type);
        $maildata['subject'] = ucfirst($data->type). ' Add Notification From '.config('app.site_name');
      
        if($this->SendMail($maildata,'notify_while_add')){
            NotifyMe::whereIn('id', $ids)->update(['notified' => 1]);
            Log::info(['add user notify mail sent']);
            return true;
        } 
    }
    return true;
    
  }

  public function getRating($type,$id){
    $rating = ['rating'=>0,'out_of'=>0]; 
    $ratingData = [];
    if($type=="event"){
       $ratingData =  EventRating::select(DB::raw('AVG(rating) as rating'),DB::raw('count(rating) as out_of'))
        ->where('event_id',$id)->get();
    }else if($type=="trainer"){
        $ratingData = UserRating::select(DB::raw('AVG(rating) as rating'),DB::raw('count(rating) as out_of'))
        ->where([
            ['user_id',$id],
            ['role_id',3]
            ])->get();
    }else if($type=="center"){
        $ratingData =  UserRating::select(DB::raw('AVG(rating) as rating'),DB::raw('count(rating) as out_of'))
        ->where([
            ['user_id',$id],
            ['role_id',2]
            ])->get();
    }else{
        return  $rating;
    }
    $rating['rating'] = number_format($ratingData[0]->rating,1);
    $rating['out_of'] = $ratingData[0]->out_of;
    
    return $rating;
  }

}