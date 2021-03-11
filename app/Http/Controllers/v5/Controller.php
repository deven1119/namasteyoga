<?php

namespace App\Http\Controllers\v5;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Controllers\Traits\SendMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\NotifyMe;
use App\EventRating;
use App\OldpasswordHistory;
use App\OtpHistory;
use App\duplicateRequest;
use Config;
use Mail;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    /**
   * 
   * Notify mail sent */
  public function sendNotificationMail($userData){
    return true;
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
                array_push($mailArr,$this->encdesc($v->email,'decrypt'));
                array_push($ids,$v->id);
                //$ids[] = $v->id;
            }                                                   
        }                              
        $maildata['email'] = $mailArr;
        $maildata['name'] = $userData->name;
        $maildata['city_name'] = $data->city_name;
        $maildata['type'] = ucfirst($data->type);
        $maildata['subject'] = ucfirst($data->type). ' Add Notification From '.config('app.site_name');
        $maildata['supportEmail'] = config('mail.supportEmail');
        $maildata['website'] = config('app.site_url');  
        $maildata['site_name'] = config('app.site_name');  
      
        if($this->SendMail($maildata,'notify_while_add')){
            NotifyMe::whereIn('id', $ids)->update(['notified' => 1]);
            Log::info(['add user notify mail sent']);
            return true;
        } 
    }
    return true;
    
  }

  public function encdesc($stringVal,$type='encrypt'){
      
    $stringVal = str_replace("__","/",$stringVal);  
    if($type=='encrypt'){
        return openssl_encrypt($stringVal,"AES-128-ECB",config('app.SECRET_SALT'));
    }else{
        return openssl_decrypt($stringVal,"AES-128-ECB",config('app.SECRET_SALT'));
    }        
  }

  public function verifyChecksum($request){    
    return true;
    $checksum = "";  
    $json = json_encode($request->all());
    $requestJson = md5($json); 
    $checksum = $request->header("checksum");
    
    //echo $requestJson.'  '.$checksum; die;
    if($requestJson != $checksum){
        return false;
    }
    return true;
  }

  public function string_replace($repaceFrom,$replaceTo,$string){
    return str_replace($repaceFrom,$replaceTo,$string);
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


  public function checkPasswordHistory($new_password,$user_id){
    try{
      $result = OldpasswordHistory::where('user_id',$user_id)->orderBy('id','DESC')->take(3)->get();
      
      if($result->count()>0){        
        foreach($result as $k=>$v){                    
          if(Hash::check($new_password, $v->password)){                        
            return false;
          }
        }
        return true;
      }else{
        return true;
      }
    }catch(Exception $e){
      return true;
    }
    
  }


  public function checkOtpHistory($type,$user_id){
    try{
      $result = OtpHistory::where([['user_id',$user_id],['type',$type]])
      ->where(DB::raw('DATEDIFF(NOW(),created_at)') , '<', 1);
      //SELECT COUNT(*) AS count FROM otp_history WHERE user_id = 1 AND DATEDIFF(NOW(),created_at) < 1      
      if($result->count() >= 3){
        return false;
      }else{
        return true;
      }
    }catch(Exception $e){
      return true;
    }
    
  }

  public function checkDuplicateRequest($request){
    
    $checksum = $request->header("checksum");                    
    $duplicate = duplicateRequest::where('md5val',$checksum);
    if($duplicate->count() > 0){
        return true; 
    }else{
      return false;
    }      
  }
  
}