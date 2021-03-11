<?php

namespace App\Http\Controllers\v2;

use App\User;
use App\Users;
use App\Country;
use App\Event;
use App\State;
use App\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Config;
use Mail;

//use Carbon;
//use App\Mail\WelcomeMail;
//use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    
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
            
            //$validator->errors()
            if($validator->fails()){
              return response()->json(["status"=>$status,"message"=>"The email has already been taken.","data"=>json_decode("{}")]);
            }else{
              $otp = rand(111111,999999);
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
              'zip' => $request->get('zip'),
              'lat' => $request->get('lat'),
              'lng' => $request->get('lng'),
              'otp' => $otp            
          ]);

          $token = JWTAuth::fromUser($user); 

          $data = array(
            'name' => $user->name,                   
            'email' => $user->email,                   
            'otp' => $otp
          );
        
          // Mail::send('emails.welcome',$data, function($newObj) use($data)
          // {
          //     $newObj->from('ravindra2806@gmail.com', config('app.site_name'));
          //     $newObj->subject("Welcome to ".config('app.site_name'));
          //     $newObj->to($data['email']);            
          // });
          DB::commit();
          return response()->json(["status"=>1,"message"=>$message,"data"=>compact('user','token')]);
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
          $cond[] = ['status','1'];
          if($request->role_id > 1){
            $cond[] = ['role_id',$request->role_id];
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
           
          $users = User::where($cond)->orderBy('name','asc')->paginate(Config::get('app.record_per_page'));          
          if($users->count()==0){
            $users = User::where('role_id',$request->role_id)->count();
            return response()->json(['status'=>1,
            'message'=>'No record found',            
            'data'=>[],
            'count'=>$users
            ]);
          }
          //$users->total()  //$users->lastPage() //perPage() //currentPage() //
          //echo '<pre>';print_r($users->items()); die;
          return response()->json(['status'=>1,
          'message'=>$message,
          'total_record'=>$users->total(),
          'last_page'=>$users->lastPage(),
          'current_page'=>$users->currentPage(),
          'data'=>$users->items()
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
        //$cond = ($request->role_id && $request->role_id >1 ) ? ['role_id',$request->role_id] : ['role_id','<>',1];           
        //$users = User::where([['status', '1'],$cond])->paginate(Config::get('app.record_per_page'));                  
        $users = User::with(['country','state','city'])->where([['status', '1'],['role_id','<>',1]])->groupBy('city_id')->get();
        $cityArr = [];
        if(count($users)>0){
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
    
}