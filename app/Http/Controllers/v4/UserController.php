<?php

namespace App\Http\Controllers\v4;

use App\User;
use App\Country;
use App\Event;
use App\State;
use App\City;
use App\NotifyMe;
use App\OldpasswordHistory;
use App\OtpHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Controllers\Traits\SendMail;
use App\Common\Utility;
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

        if(!$this->verifyChecksum($request)){
          return response()->json(["status"=>0,
         "message"=>'checksum not verified',
         "data"=>json_decode('{}')]);
        }

        $request->email = $this->string_replace("__","/",$request->email);
        $request->password = $this->encdesc($request->password,'decrypt');
        $request->merge([
          'email' => $request->email,
          'password' => $request->password
        ]);

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|max:255',
            'password' => 'required|string',
        ]);
        //echo $request->password; die;
        //$validator->errors()
        if($validator->fails()){
          return response()->json(["status"=>$status,"message"=>"invalid input details","data"=>json_decode("{}")]);
        }
        //echo $pwd = Hash::make($request->password).'      ='.$request->email; die;
        $validationChk = User::where('email',$request->email)->get();


        if($validationChk->count()==0){
          return response()->json(["status"=>$status,"message"=>"invalid credentials","data"=>json_decode("{}")]);
        }else if($validationChk[0]->status != '1'){
          return response()->json(["status"=>2,"message"=>"User not verified","data"=>json_decode("{}")]);
        }else if(!Hash::check($request->password, $validationChk[0]->password)){
            return response()->json(["status"=>$status,"message"=>"invalid credentials","data"=>json_decode("{}")]);
        }



        $credentials = $request->only('email', 'password');
        try {
          $myTTL = 43200; //minutes
          JWTAuth::factory()->setTTL($myTTL);
            if (! $token = JWTAuth::attempt($credentials, ['status'=>'1'])) {
                $message = 'invalid_credentials';
                return response()->json(['status'=>$status,'message'=>$message,'data'=>json_decode("{}")]);
            }
        } catch (JWTException $e) {
            $message = 'could_not_create_token';
            return response()->json(['status'=>$status,'message'=>$message,'data'=>json_decode("{}")]);
        }
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
        return response()->json(['status'=>$status,'message'=>$message,'data'=>$user]);
    }

    public function apilogout(Request $request){

      try{
        JWTAuth::invalidate(JWTAuth::parseToken());
        //JWTAuth::setToken($token)->invalidate();
        return response()->json(['status'=>1,'message'=>'','data'=>json_decode("{}")]);
      }catch(Exception $e){
        return response()->json(['status'=>0,'message'=>'Not able to logout','data'=>json_decode("{}")]);
      }

    }

    public function register(Request $request)
    {
      $status = 0;
      $message = "";
      Utility::stripXSS();
      DB::beginTransaction();
      try{

          if(!$this->verifyChecksum($request)){
            return response()->json(["status"=>0,
           "message"=>'checksum not verified',
           "data"=>json_decode('{}')]);
          }

          $request->email = $this->string_replace("__","/",$request->email);
          $request->password = $this->string_replace("__","/",$request->password);
          $request->phone = $this->string_replace("__","/",$request->phone);
          $request->address = $this->string_replace("__","/",$request->address);
          //$request->name = $this->string_replace("__","/",$request->name);

          $request->merge([
            'email' => $request->email,
            'password' => $request->password,
            'phone' => $request->phone,
            'address' => $request->address
          ]);

           $validator = Validator::make($request->all(), [
               'name' => 'required|string|max:255',
               'email' => 'required|string|max:255|unique:users',
               'password' => 'required|string|min:6',
           ]);



           $data = [];
           $data['email'] = $this->encdesc($request->get('email'),'decrypt');
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


              $otpHistory = new OtpHistory();
              $otpHistory->user_id = $userData->id;
              $otpHistory->type = 'RG';
              $otpHistory->save();

              if(!$this->checkOtpHistory('RG',$userData->id)){
                  return response()->json(['status'=>$status,'message'=>'Your OTP limit over now. Please try after 24 hour','data'=>json_decode("{}")]);
              }

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

           $userType = (isset($request->user_type)) ? $request->user_type : 'Public';

           $user = User::create([
             'name' => $request->get('name'),
             'email' => $request->get('email'),
             'phone' => $request->get('phone'),
             'password' => Hash::make($this->encdesc($request->get('password'),'decrypt')),
             'city_id' => $city_id,
             'user_type' => $userType,
             'role_id' => $request->get('role_id'),
             'state_id' => $state_id,
             'country_id' => $country_id,
             'address' => $request->get('address'),
             'status' => '0',
             'verified_otp' => 0,
             'zip' => $request->get('zip'),
             'lat' => $request->get('lat'),
             'lng' => $request->get('lng'),
             'nearest' => $request->get('nearest'),
             'nearest_distance' => $request->get('nearest_distance'),
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

    /**
     * Show the form for get user lsit.
     *
     * @return \Illuminate\Http\Response
     */
    public function getUserList(Request $request){
        $status = 0;
        $message = "";
        try{

          if(!$this->verifyChecksum($request)){
            return response()->json(["status"=>0,
            "message"=>'checksum not verified',
            "data"=>json_decode('{}')]);
          }


          $cond[] = ['status','1'];
          $cond[] = ['suspended','0'];
          if($request->role_id > 1){
            $cond[] = ['role_id',$request->role_id];
          }
          if($request->role_id == 3){
            $cond[] = ['ycb_approved','1'];
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

          if($request->city != ""){
            $cond[] = ['city_id',$city_id];
          }
          if($request->state !=""){
            $cond[] = ['state_id',$state_id];
          }
          if($request->country !=""){
            $cond[] = ['country_id',$country_id];
          }
         // print_r($cond); die;
          //$cond = ($request->role_id > 1 ) ? ['role_id',$request->role_id] : ['role_id','<>',1];
          //$users = User::where($cond)->paginate(Config::get('app.record_per_page'));

          $users = User::where($cond)->orderBy('name','asc')->paginate(Config::get('app.record_per_page'));
          if($users->count()==0){
            $users = User::where('role_id',$request->role_id)->count();
            return response()->json(['status'=>1,
            'message'=>'No record found',
            'data'=>[],
            'count'=>$users,
            'cond'  => $cond
            ]);
          }else{
            foreach($users as $k=>$v){
              $users[$k]['country_name'] = $v->Country->name;
              $users[$k]['state_name'] = $v->State->name;
              $users[$k]['city_name'] = $v->City->name;
              unset($v->City);
              unset($v->Country);
              unset($v->State);
              //echo $v->Country->name; die;
            }
          }

          //print_r($users); die;
          //$users->total()  //$users->lastPage() //perPage() //currentPage() //
          //echo '<pre>';print_r($users->items()); die;
          return response()->json(['status'=>1,
          'message'=>$message,
          'total_record'=>$users->total(),
          'last_page'=>$users->lastPage(),
          'current_page'=>$users->currentPage(),
          'data'=>$users->items(),
          'cond'  => $cond
          ]);
        }catch(Exception $e){
          $message = "Technical error";
          return response()->json(['status'=>$status,'message'=>$message,'data'=>json_decode("{}")]);
        }

    }

    /**
     * Show the form for get user lsit.
     *
     * @return \Illuminate\Http\Response
     */
    public function getUserByCity(Request $request){
      $status = 0;
      $message = "";
      try{
        if(!$this->verifyChecksum($request)){
          return response()->json(["status"=>0,
          "message"=>'checksum not verified',
          "data"=>json_decode('{}')]);
        }
        //$cond = ($request->role_id && $request->role_id >1 ) ? ['role_id',$request->role_id] : ['role_id','<>',1];
        //$users = User::where([['status', '1'],$cond])->paginate(Config::get('app.record_per_page'));
        $users = User::with(['country','state','city'])->where([['status', '1'],['role_id','<>',1]])->groupBy('city_id')->get();
        $cityArr = [];
        if($users->count()>0){
          foreach($users as $k=>$v){
            $cityArr[$k]['city_id'] = $v->city->id;
            $cityArr[$k]['city_name'] = $v->city->name;
            $cityArr[$k]['state_id'] = $v->state->id;
            $cityArr[$k]['state_name'] = $v->state->name;
            $cityArr[$k]['country_id'] = $v->country->id;
            $cityArr[$k]['country_name'] = $v->country->name;
          }
        }
        //echo '<pre>'; print_r($users[0]->city->name); die;
        return response()->json(['status'=>1,
        'message'=>$message,
        'data'=>$cityArr
        ]);
      }catch(Exception $e){
        $message = "Technical error";
        return response()->json(['status'=>$status,'message'=>$message,'data'=>json_decode("{}")]);
      }
  }

  /**
     * Show the form for get user lsit.
     *
     * @return \Illuminate\Http\Response
     */
    public function otpVerification(Request $request){
      $status = 0;
      $message = "";
      Utility::stripXSS();
      $this->encdesc($request->get('email'),'decrypt');
      if(!$this->verifyChecksum($request)){
        return response()->json(["status"=>0,
       "message"=>'checksum not verified',
       "data"=>json_decode('{}')]);
      }


      try{
        $validator = Validator::make($request->all(), [
          'otp' => 'required|string',
          'email' => 'required|string|max:255'
        ]);

        //$validator->errors()
        if($validator->fails()){
          return response()->json(["status"=>$status,"message"=>"Invalid input data.","data"=>json_decode("{}")]);
        }


          $request->email = $this->string_replace("__","/",$request->email);

          $request->otp = $this->encdesc($request->otp,'decrypt');

          $request->merge([
            'email' => $request->email,
            'otp' => $request->otp
          ]);
        //echo $request->otp; die;
        if(isset($request->email) && isset($request->otp)){
          $cond = [['email',$request->email],['otp',$request->otp]];
        }
        $user = User::where($cond)->get();
        //print_r($user[0]->status); die;
        if($user->count()>0){
          if($user[0]->verified_otp != 1){
            $status = 1;
            //User::where('email', $user[0]->email)->update(['verified_otp'=>1,'status'=>'1']);
            User::where('email', $user[0]->email)->update(['verified_otp'=>1, 'status'=>'1']);

            $data = [];
            //$request->email = $this->string_replace("_","/",$request->email);
            $data['email'] = $this->encdesc($user[0]->email,'decrypt');
            $data['name'] = $user[0]->name;
            $data['supportEmail'] = config('mail.supportEmail');
            $data['website'] = config('app.site_url');
            $data['site_name'] = config('app.site_name');
            $data['subject'] = 'Registration OTP verification Success From '.config('app.site_name');
            //$data['password'] = $request->get('password');
            $data['email_msg_1'] = 'Now you can login to the app with your credentials.';
            //$data['password'] = $request->get('password');
            if($user[0]->role_id == 3){
              $data['email_msg_1'] = 'Your registration details have been sent to the administrator for approval. Once approved, your listing will be visible on the Yoga Locator app.';
            }

            if($this->SendMail($data,'verified_registration')){

              //notify code starts
              $user[0]->type = $user[0]->Role->role;
              $this->sendNotificationMail($user[0]);
              //notify code ends
              return response()->json(['status'=>$status,'message'=>"Successfully verified OTP",'data'=>json_decode("{}")]);
            }else{
              return response()->json(['status'=>0,'message'=>"Mail not sent",'data'=>json_decode("{}")]);
            }
          }else{
            return response()->json(['status'=>$status,'message'=>"OTP Already verified",'data'=>json_decode("{}")]);
          }
        }else{
          return response()->json(['status'=>$status,'message'=>"Incorrect OTP",'data'=>json_decode("{}")]);
        }
      }catch(Exception $e){
        $message = "Technical error";
        return response()->json(['status'=>$status,'message'=>$message,'data'=>json_decode("{}")]);
      }

  }


  /**
     * Show the form for get user lsit.
     *
     * @return \Illuminate\Http\Response
     */
    public function changePassword(Request $request){
      $status = 0;
      $message = "";
      Utility::stripXSS();
      //$this->encdesc($request->get('email'),'decrypt');
      if(!$this->verifyChecksum($request)){
        return response()->json(["status"=>0,
       "message"=>'checksum not verified',
       "data"=>json_decode('{}')]);
      }
      try{
        $user  = JWTAuth::user();
        if($user->count()>0){

          if($request->new_password != $request->confirm_password){
            return response()->json(['status'=>$status,'message'=>'confirm password is not same','data'=>json_decode("{}")]);
          }
          $request->old_password = $this->encdesc($request->old_password,'decrypt');
          $request->new_password = $this->encdesc($request->new_password,'decrypt');
          $request->merge([
            'old_password' => $request->old_password,
            'new_password' => $request->new_password
          ]);


          $validator = Validator::make($request->all(), [
            'new_password' => [
              'required',
              'string',
              'min:6',
              'regex:/[a-z]/',
              'regex:/[A-Z]/',
              'regex:/[0-9]/',
              'regex:/[@$!%*#?&]/',
            ]
          ]);

          if($validator->fails()){
            return response()->json(['status'=>$status,'message'=>'Password should be min 6 char, alphanumeric,atleast one char capital and one special char','data'=>json_decode("{}")]);
          }

          if($request->old_password == $request->new_password){
            return response()->json(['status'=>$status,'message'=>'old password and new password should not be same','data'=>json_decode("{}")]);
          }

          //echo $request->old_password."                ".$user->password; die;
          if(!Hash::check($request->old_password, $user->password)){
            return response()->json(['status'=>$status,'message'=>'old password incorrect','data'=>json_decode("{}")]);
          }

          if(!$this->checkPasswordHistory($request->new_password,$user->id)){
            return response()->json(['status'=>$status,'message'=>'Password should not same as last 3 password','data'=>json_decode("{}")]);
          }else{
            $pwdAdd = Hash::make($request->new_password);
            User::where('email', $user->email)->update(['password'=>Hash::make($request->new_password)]);
            $data = [];
            $data['email'] = $this->encdesc($user->email,'decrypt');
            $data['name'] = $user->name;
            $data['supportEmail'] = config('mail.supportEmail');
            $data['website'] = config('app.site_url');
            $data['site_name'] = config('app.site_name');
            $data['password'] = $request->new_password;

            $oldPwdHistoryObj = new OldpasswordHistory();
            $oldPwdHistoryObj->user_id = $user->id;
            $oldPwdHistoryObj->password = $pwdAdd;
            $oldPwdHistoryObj->save();

            $data['subject'] = 'Change Password Notification From '.config('app.site_name');
            if($this->SendMail($data,'password_change')){
              return response()->json(['status'=>1,'message'=>$message, 'data'=>json_decode("{}")]);
            }else{
              return response()->json(['status'=>1,'message'=>'unable to send email', 'data'=>json_decode("{}")]);
            }
          }

        }else{
          return response()->json(['status'=>$status,'message'=>'not found','data'=>json_decode("{}")]);
        }
      }catch(Exception $e){
        return response()->json(['status'=>$status,'message'=>'exception error','data'=>json_decode("{}")]);
      }
  }

    /**
     * Show the form for get user lsit.
     *
     * @return \Illuminate\Http\Response
     */
    public function forgotPassword(Request $request){
      $status = 0;
      $message = "";

      if(!$this->verifyChecksum($request)){
        return response()->json(["status"=>0,
       "message"=>'checksum not verified',
       "data"=>json_decode('{}')]);
      }

      Utility::stripXSS();
      try{
        $validator = Validator::make($request->all(), [
          'email' => 'required|string|max:255',
        ]);

        //$validator->errors()
        if($validator->fails()){
          return response()->json(["status"=>$status,"message"=>"invalid input.","data"=>json_decode("{}")]);
        }
        $request->email = $this->string_replace("__","/",$request->email);
        $request->merge([
          'email'=>$request->email
        ]);
        $users = User::where('email',$request->email)->get();


        if($users->count() > 0){


          if(!$this->checkOtpHistory('FP',$users[0]->id)){
            return response()->json(['status'=>$status,'message'=>'Your OTP limit over now. Please try after 24 hour','data'=>json_decode("{}")]);
          }

          $userObj = new User();
          $newpassOrig = $userObj->generateVarchar();
          $data = array();

          $data['email'] = $this->encdesc($request->email,'decrypt');
          $data['name'] = $this->encdesc($users[0]->name,'decrypt');
          $data['supportEmail'] = config('mail.supportEmail');
          $data['website'] = config('app.site_url');
          $data['site_name'] = config('app.site_name');
          //$data['newpass'] = $newpassOrig;
          //$newpassOrigEnc = Hash::make($newpassOrig);
          $otp = rand(111111,999999);
          $data['otp'] = $otp;


          $data['subject'] = 'Password change OTP from '.config('app.site_name');
          $this->SendMail($data,'change_pass_otp');

          $users[0]->otp = $otp;
          $users[0]->verified_otp = 0;
          if($users[0]->save()){

            $otpHistory = new OtpHistory();
            $otpHistory->user_id = $users[0]->id;
            $otpHistory->type = 'FP';
            $otpHistory->save();

            return response()->json(['status'=>1,'message'=>'Otp sent','data'=>json_decode("{}")]);
          }else{
            return response()->json(['status'=>$status,'message'=>'mail not sent','data'=>json_decode("{}")]);
          }
        }else{
          return response()->json(['status'=>$status,'message'=>'If the email address is known to us, we will send a OTP in a few minutes.','data'=>json_decode("{}")]);
        }

      }catch(Exception $e){
        return response()->json(['status'=>$status,'message'=>'exception error','data'=>json_decode("{}")]);
      }
  }

  /**
     * Show the form for get user lsit.
     *
     * @return \Illuminate\Http\Response
     */
    public function verifyForgotPassword(Request $request){
      $status = 0;
      $message = "";
      Utility::stripXSS();
      try{

        if(!$this->verifyChecksum($request)){
          return response()->json(["status"=>0,
         "message"=>'checksum not verified',
         "data"=>json_decode('{}')]);
        }

        $validator = Validator::make($request->all(), [
          'email' => 'required|string|max:255',
          'otp' => 'required|string',
          'newpass' => 'required|string',
        ]);

        //$validator->errors()
        if($validator->fails()){
          return response()->json(["status"=>$status,"message"=>"invalid credential.","data"=>json_decode("{}")]);
        }

        $request->email = $this->string_replace("__","/",$request->email);
        $request->otp =  $this->encdesc($request->otp,'decrypt');
        $request->newpass =  $this->encdesc($request->newpass,'decrypt');
        $request->merge([
          'email'=>$request->email,
          'otp'=>$request->otp,
          'newpass'=>$request->newpass
        ]);
        $users = User::where([
          ['email',$request->email],
          ['otp',$request->otp]
          ])->get();


        if($users->count() > 0){

          if($users[0]->verified_otp==1){
            return response()->json(['status'=>0,'message'=>'otp already verified','data'=>json_decode("{}")]);
          }
          $userObj = new User();
          $data = array();

          if(!$this->checkOtpHistory('OV',$users[0]->id)){
            return response()->json(['status'=>$status,'message'=>'Your OTP limit over now. Please try after 24 hour','data'=>json_decode("{}")]);
          }

          $data['email'] = $this->encdesc($request->email,'decrypt');
          $data['name'] = $users[0]->name;
          $data['newpass'] = $request->newpass;
          $data['supportEmail'] = config('mail.supportEmail');
          $data['website'] = config('app.site_url');
          $data['site_name'] = config('app.site_name');
          $newpassOrigEnc = Hash::make($request->newpass);

          $data['subject'] = 'Password change from '.config('app.site_name');
          $this->SendMail($data,'newpassword');
          $users[0]->password = $newpassOrigEnc;
          $users[0]->verified_otp = 1;
          if($users[0]->save()){
            $otpHistory = new OtpHistory();
            $otpHistory->user_id = $users[0]->id;
            $otpHistory->type = 'OV';
            $otpHistory->save();
            return response()->json(['status'=>1,'message'=>'','data'=>json_decode("{}")]);
          }else{
            return response()->json(['status'=>$status,'message'=>'mail not sent','data'=>json_decode("{}")]);
          }
        }else{
          return response()->json(['status'=>$status,'message'=>'invalid input data','data'=>json_decode("{}")]);
        }

      }catch(Exception $e){
        return response()->json(['status'=>$status,'message'=>'exception error','data'=>json_decode("{}")]);
      }
  }

  public function pushNotification(){


    //echo $this->checkOtpHistory('CP',1); die;
    $otp = rand(111111,999999);
    $data['otp'] = $otp;
    Mail::send('testmail',$data, function($newObj) use($data)
    {
        $emailTestList = ['ravindra2806@gmail.com','shubham.gupta@netprophetsglobal.com'];
        // $emailTestList = ["puffthemagicdragon@gmail.com",
        //   "gauravarora@rediffmail.com",
        //   "generalnuisance@live.com",
        //   "sandeepsharma3530@yahoo.co.in",
        //   "drleenachhatre@gmail.com","itconsultant-ayush@nic.in",
        //   "srconsultant-ayush@nic.in"];
        $newObj->from(config('mail.from.address'), config('app.site_name'));
        $newObj->subject("Test Template Sample ".config('app.site_name'));
        $newObj->to($emailTestList);
    });
    echo 'mail sent';
    die;

  }

  public function notifyMe(Request $request){
    try{
        $status = 0;
        $message = "";

        if(!$this->verifyChecksum($request)){
          return response()->json(["status"=>0,
          "message"=>'checksum not verified',
          "data"=>json_decode('{}')]);
        }

        $validator = Validator::make($request->all(), [
          'name' => 'required|string|max:255',
          'email' => 'required|string|max:255',
          'phone' => 'required|string',
          'state' => 'required|string',
          'city' => 'required|string',
          'country' => 'required|string',
          'type' => 'required|string',
        ]);
        if($validator->fails()){
          return response()->json(["status"=>$status,"message"=>"Invalid data","data"=>json_decode("{}")]);
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


        $data = NotifyMe::where([
          ['city_id',$city_id],
          ['state_id',$state_id],
          ['country_id',$country_id],
          ['email',$request->email],
          ['type',$request->type]
        ])->get();

        if($data->count() > 0){
          return response()->json(["status"=>0,"message"=>"You have already requested for this location","data"=>json_decode("{}")]);
        }
        $notifyObj = new NotifyMe();
        $notifyObj->name = $this->string_replace("__","/",$request->name);
        $notifyObj->email = $this->string_replace("__","/",$request->email);
        $notifyObj->phone = $this->string_replace("__","/",$request->phone);

        $notifyObj->state_id = $state_id;
        $notifyObj->city_id = $city_id;
        $notifyObj->country_id = $country_id;
        $notifyObj->type = $request->type;

        if($notifyObj->save()){
          $data1['email'] = $this->encdesc($request->email,'decrypt');
          $data1['name'] = $this->encdesc($request->name,'decrypt');
          $data1['type'] = $request->type;
          $data1['city_name'] = $request->city;
          $data1['supportEmail'] = config('mail.supportEmail');
          $data1['website'] = config('app.site_url');
          $data1['site_name'] = config('app.site_name');

          $data1['subject'] = 'Notification Request from '.config('app.site_name');
          if($this->SendMail($data1,'notify')){
            return response()->json(["status"=>1,"message"=>"save successfully","data"=>json_decode("{}")]);
          }
        }else{
          return response()->json(["status"=>$status,"message"=>"error on saving data","data"=>json_decode("{}")]);
        }
        //$validator->errors()
    }catch(Exception $e){
      return response()->json(['status'=>$status,'message'=>'exception error','data'=>json_decode("{}")]);
    }
  }

  public function resentRegisterOtp(Request $request){
    try{
        $status = 0;
        $message = "";

        if(!$this->verifyChecksum($request)){
          return response()->json(["status"=>0,
         "message"=>'checksum not verified',
         "data"=>json_decode('{}')]);
        }

        $request->email = $this->string_replace("__","/",$request->email);
        $request->merge([
          'email'=>$request->email
        ]);

        $validator = Validator::make($request->all(), [
          'email' => 'required|string|max:255'
        ]);
        if($validator->fails()){
          return response()->json(["status"=>$status,"message"=>"Please send email","data"=>json_decode("{}")]);
        }
        $user  = User::where('email',$request->email)->first();
        if($user->count()==0){
          return response()->json(["status"=>0,"message"=>"User does not exist","data"=>json_decode("{}")]);
        }else if($user->count()>0 && $user->status=='0' && $user->verified_otp==0){

          $otpHistory = new OtpHistory();
          $otpHistory->user_id = $user->id;
          $otpHistory->type = 'RG';
          $otpHistory->save();

          if(!$this->checkOtpHistory('RG',$user->id)){
              return response()->json(['status'=>$status,'message'=>'Your OTP limit over now. Please try after 24 hour','data'=>json_decode("{}")]);
          }

          $otp = rand(111111,999999);
          $data['name'] = $this->encdesc($user->name,'decrypt');
          $data['otp'] = $otp;
          $data['email'] = $this->encdesc($user->email,'decrypt');
          $data['supportEmail'] = config('mail.supportEmail');
          $data['website'] = config('app.site_url');
          $data['site_name'] = config('app.site_name');
          $data['subject'] = 'New OTP on Registration';

          if($this->SendMail($data,'registration_otp')){
            $user->otp = $otp;
            $user->save();
            return response()->json(["status"=>1,"message"=>"Otp sent successfully","data"=>json_decode("{}")]);
          }
        }else{
          return response()->json(["status"=>$status,"message"=>"Technical error","data"=>json_decode("{}")]);
        }
        //$validator->errors()
    }catch(Exception $e){
      return response()->json(['status'=>$status,'message'=>'exception error','data'=>json_decode("{}")]);
    }
  }


  /**
   * Edit event method
   * @return success or error
   *
   * */
  public function editMyProfile(Request $request){

    try{
      $status = 0;
      $message = "";
      Utility::stripXSS();
      if(!$this->verifyChecksum($request)){
        return response()->json(["status"=>0,
       "message"=>'checksum not verified',
       "data"=>json_decode('{}')]);
      }

      $user  = JWTAuth::user();

      if($user->count()==0){
        return response()->json(['status'=>$status,'message'=>'User not found','data'=>json_decode("{}")]);
      }


      if(isset($request->country) && isset($request->state) && isset($request->city)){
          $cityObj = new City();
          $returnData  = $cityObj->getCountryStateCityByName($request);
          if($returnData['error']==0 && $returnData['success']==1){
              $country_id = $returnData['data']['country_id'];
              $state_id = $returnData['data']['state_id'];
              $city_id = $returnData['data']['city_id'];

              $user->country_id = $country_id;
              $user->state_id = $state_id;
              $user->city_id = $city_id;
          }else{
              return response()->json(['status'=>$status,'message'=>$returnData['message'],'data'=>[]]);
          }
      }

      $request->name = $this->string_replace("__","/",$request->name);
      $request->phone = $this->string_replace("__","/",$request->phone);

      $request->merge([
        'name'=>$request->name,
        'email'=>$request->email
      ]);

      if(isset($request->address)){
        $request->address = $this->string_replace("__","/",$request->address);
        $request->merge([
          'address'=>$request->address
        ]);
      }


      $user->id = $user->id;
      $user->name = ($request->name) ? $request->name : $user->name;
      $user->role_id = ($request->role_id) ? $request->role_id : $user->role_id;
      $user->phone = ($request->phone) ? $request->phone : $user->phone;
      $user->address = ($request->address) ? $request->address: $user->address;
      $user->zip = ($request->zip) ? $request->zip: $user->zip;
      $user->lat = ($request->lat) ? $request->lat: $user->lat;
      $user->lng = ($request->lng) ? $request->lng: $user->lng;
      $user->user_type = ($request->user_type) ? $request->user_type : $user->user_type;
      $user->nearest = $request->nearest;
      $user->nearest_distance = $request->nearest_distance;

      if(!$user->save()){

          return response()->json(['status'=>$status,'message'=>'Unable to save','data'=>json_decode("{}")]);
      }else{
          $user->city_name = $request->city;
          $user->state_name = $request->state;
          $user->country_name = $request->country;
          return response()->json(['status'=>1,'message'=>'Profile updated successfully','data'=>$user]);
      }
    }catch(Exception $e){
      return response()->json(['status'=>$status,'message'=>'User update Error','data'=>json_decode("{}")]);
    }

  }

  public function deletetest(){
    User::where("name","LIKE","Test%")->delete();
    Event::where("event_name","LIKE","Test%")->delete();
    echo 'Deleted Data'; die;
  }

  public function clearData(){
    DB::table('duplicate_request')->delete();
    DB::table('otp_history')->delete();
  }

}
