<?php

namespace App\Http\Controllers;

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
use App\OldpasswordHistory;

use Config;
use Mail;
use Auth;


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

    public function encdesc($stringVal,$type='encrypt'){

        $stringVal = str_replace("__","/",$stringVal);  
        if($type=='encrypt'){
            return openssl_encrypt($stringVal,"AES-128-ECB",config('app.SECRET_SALT'));
        }else{
            return openssl_decrypt($stringVal,"AES-128-ECB",config('app.SECRET_SALT'));
        }        
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
              if($new_password == $v->password){                        
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

    public function checkAuth($allowed_id){
        if(Auth::user()->role_id != $allowed_id){
            return false;
        }
        return true;
    }
  
    
        
        
    /**
     * This method is use for send push-notification to android device.
     * @param type $arr_deviceId        array('deviceId1', 'deviceId2' )
     * @param type $arr_messageInfo     array('subject' => 'msg subject text', 'messageBody' => 'msg body text')
     */
    public function sendPushNotification($arr_deviceId, $arr_messageInfo) {

        $url = Config::get('app.FCM_URL');

        $serverKey = Config::get('app.FCM_API_KEY');

        $notification = array('title' => $arr_messageInfo['subject'] , 'body' => $arr_messageInfo['messageBody'], 'sound' => 'default', 'badge' => '1');

        if(count($arr_deviceId)) {
            $arrayToSend = array('registration_ids' => $arr_deviceId, 'notification' => $notification, 'priority' => 'high');//for multiple device

            $json = json_encode($arrayToSend);

            $headers = array();
            $headers[] = 'Content-Type: application/json';
            $headers[] = 'Authorization: key='. $serverKey;

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
            curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
            
            curl_setopt($ch, CURLOPT_VERBOSE, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            
            curl_setopt($ch, CURLOPT_FAILONERROR, false); // Required for HTTP error codes to be reported via our call to curl_error($ch)
            //
            //Send the request
            $response = curl_exec($ch);

            //Close request
            if ($response === FALSE) {
                //die('FCM Send Error: ' . curl_error($ch));
            }
            curl_close($ch);
        }
    }

}