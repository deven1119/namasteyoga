<?php

namespace App\Http\Controllers\v3;

use App\User;
use App\Country;
use App\Event;
use App\State;
use App\City;
use App\NotifyMe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Controllers\Traits\SendMail;
use Config;
use Mail;
//use Carbon;
//use App\Mail\WelcomeMail;
//use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    use SendMail;
    public function authenticate(Request $request)
    {

        $status = 0;
        $message = "";
        $credentials = $request->only('email', 'password');
        //echo '<pre>'; print_r($credentials); die;
        
        try {
          $myTTL = 43200; //minutes
          JWTAuth::factory()->setTTL($myTTL);
            //$token = JWTAuth::attempt($credentials);
            if (! $token = JWTAuth::attempt($credentials)) {
            //if (! $token = JWTAuth::attempt($credentials, ['exp' => Carbon\Carbon::now()->addDays(7)->timestamp])) {
              //$token = JWTAuth::attempt($credentials, ['exp' => Carbon\Carbon::now()->addDays(7)->timestamp]);  
                $message = 'invalid_credentials';
                //return response()->json(['error' => 'invalid_credentials'], 400);
                return response()->json(['status'=>$status,'message'=>$message,'data'=>json_decode("{}")]);
            }
        } catch (JWTException $e) {
            $message = 'could_not_create_token';
            return response()->json(['status'=>$status,'message'=>$message,'data'=>json_decode("{}")]);
            //return response()->json(['error' => 'could_not_create_token'], 500);
        }
        //print_r(JWTAuth::user());
        $user  = JWTAuth::user();
        $user->city_name = $user->city->name; 
        $user->country_name = $user->country->name; 
        $user->state_name = $user->state->name; 
        unset($user->city);
        unset($user->state);
        unset($user->country);
        unset($user->otp);
        unset($user->verified_otp);
        $user->token = $token;
        $status = 1;
        //return response()->json(compact('token'));
        return response()->json(['status'=>$status,'message'=>$message,'data'=>$user]);
    }

    public function register(Request $request)
    {
      
       $status = 0;
       $message = "";
       DB::beginTransaction();
       try{
            
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6|confirmed',
            ]);

            $data = [];          
            $data['email'] = $request->get('email');
            $data['name'] = $request->get('name');
            $data['supportEmail'] = config('mail.supportEmail');
            $data['website'] = config('app.site_url');  
            $data['site_name'] = config('app.site_name');                     
                   
            $data['subject'] = 'Registration OTP from '.config('app.site_name'); 
            $otp = rand(111111,999999);  
            $data['otp'] = $otp;   
            
            $userData  = User::where('email',$request->email)->first(); 
            //print_r($userData); die;
            if(isset($userData->email) && $userData->status=='0' && $userData->verified_otp==0){                            
              if($this->SendMail($data,'registration_otp')){
                $userData->otp = $otp;
                $userData->save();
                DB::commit();
                //$userData = $userData;
                return response()->json(["status"=>1,"message"=>"","data"=>$userData]);  
                //die;
              }                
            }             
                        
            //$validator->errors()
            if($validator->fails()){
              $error = json_decode(json_encode($validator->errors()));
              if(isset($error->name[0])){
                $message = $error->name[0];
              }else if(isset($error->email)){
                $message = $error->email[0];
              }else if(isset($error->password)){
                $message = $error->password[0];
              }
              return response()->json(["status"=>$status,"message"=>$message,"data"=>json_decode("{}")]);
            }
            
            $cityObj = new City();
            $returnData  = $cityObj->getCountryStateCityByName($request);
            if($returnData['error']==0 && $returnData['success']==1){
              $country_id = $returnData['data']['country_id'];
              $state_id = $returnData['data']['state_id'];
              $city_id = $returnData['data']['city_id'];
            }else{
              return response()->json(['status'=>$status,'message'=>$returnData['message'],'data'=>[]]);
            }
            
            $user = User::create([
              'name' => $request->get('name'),
              'email' => $request->get('email'),
              'phone' => $request->get('phone'),            
              'password' => Hash::make($request->get('password')),
              'city_id' => $city_id,
              'role_id' => $request->get('role_id'),
              'state_id' => $state_id,
              'country_id' => $country_id,
              'address' => $request->get('address'),
              'status' => '0',
              'verified_otp' => 0,
              'zip' => $request->get('zip'),
              'lat' => $request->get('lat'),
              'lng' => $request->get('lng'),
              'otp' => $otp            
          ]);

          $token = JWTAuth::fromUser($user); 
          //$user->token = $token;
          //$user->save();
          //JWTAuth::setToken($token);
           
          if($this->SendMail($data,'registration_otp')){
            DB::commit();
            return response()->json(["status"=>1,"message"=>$message,"data"=>compact('user','token')]);        
          }else{
            return response()->json(["status"=>0,"message"=>'Unable to send email',"data"=>json_decode("{}")]);        
          }                              
        } catch(Exception $e){
          DB::rollBack();
          return response()->json(['status'=>$status,'message'=>$e,'data'=>json_decode("{}")]);
        }              
    }
    
    public function getAuthenticatedUser() { 
         $status = 0;   
        try {

                if (! $user = JWTAuth::parseToken()->authenticate()) {
                  //return response()->json(['user_not_found'], 404);
                  return response()->json(['status'=>$status,'message'=>'user_not_found','data'=>json_decode("{}")]);
                }

        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            //return response()->json(['token_expired'], $e->getStatusCode());
            return response()->json(['status'=>$status,'message'=>'token_expired','data'=>json_decode("{}")]);

        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

          //return response()->json(['token_invalid'], $e->getStatusCode());
          return response()->json(['status'=>$status,'message'=>'token_invalid','data'=>json_decode("{}")]);

        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
          return response()->json(['status'=>$status,'message'=>'token_absent','data'=>json_decode("{}")]);
          //return response()->json(['token_absent'], $e->getStatusCode());
        }
        $status = 1;
        return response()->json(compact('user'));
   }   
   

}