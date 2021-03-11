<?php

    namespace App\Http\Controllers\v4;
    
    use App\Event;
    use App\State;
    use App\City;
    use App\Country;
    use App\User;
    use App\NotifyMe;
    use Illuminate\Support\Facades\Validator;
    use Illuminate\Http\Request;
    use JWTAuth;
    use Tymon\JWTAuth\Exceptions\JWTException;
    use App\Http\Controllers\Traits\SendMail;
    use Config;
    use App\Common\Utility;
    use Illuminate\Support\Facades\Log;
    use Illuminate\Support\Facades\DB;
    use App\duplicateRequest;

    class EventController extends Controller
    {
        use SendMail;
        public function addEvent(Request $request){            
            try{                    
                    if(!$this->verifyChecksum($request)){
                        return response()->json(["status"=>0,
                        "message"=>'checksum not verified',
                        "data"=>json_decode('{}')]);
                    }
                    Utility::stripXSS();
                    
                    Log::info('entered in add event API');
                   
                    $status = 0;
                    $message = "";
                    
                    $validator = Validator::make($request->all(), [
                        'event_name' => 'required|string|max:255',
                        'email' => 'required|string|max:255',
                        'contact_person' => 'required|string|max:255',
                        'contact_no' => 'required|string',
                        'start_time'  => 'date_format:Y-m-d H:i:s|after:yesterday',
                        'end_time'  => 'date_format:Y-m-d H:i:s|after:yesterday'
                    ]);

                    if($this->checkDuplicateRequest($request)){
                        return response()->json(['status'=>$status,'message'=>'Duplicate request','data'=>[]]);
                    }


                    $user  = JWTAuth::user();
                    if($user->count()==0){
                        return response()->json(['status'=>$status,'message'=>'User not found','data'=>[]]);
                    }
                    //if($user)
                    if($validator->fails()){
                            Log::debug(['add event validation failed',$request->all()]);
                            return response()->json(['status'=>$status,'message'=>'invalid data set','data'=>json_decode("{}")]);
                            //return response()->json($validator->errors()->toJson(), 400);
                    }          

                    //$request->event_name = $this->string_replace("__","/",$request->event_name);
                    $request->email = $this->string_replace("__","/",$request->email);
                    $request->contact_person = $this->string_replace("__","/",$request->contact_person);
                    $request->contact_no = $this->string_replace("__","/",$request->contact_no);
                    $request->address = $this->string_replace("__","/",$request->address);                     

                    $request->merge([
                        'email' => $request->email,
                        'contact_person' => $request->contact_person,
                        'contact_no' => $request->contact_no,
                        'address' => $request->address,
                    ]);

                    $cityObj = new City();
                    $returnData  = $cityObj->getCountryStateCityByName($request);
                    if($returnData['error']==0 && $returnData['success']==1){
                        $country_id = $returnData['data']['country_id'];
                        $state_id = $returnData['data']['state_id'];
                        $city_id = $returnData['data']['city_id'];
                    }else{
                        Log::debug(['add event country state not found',$returnData]);
                        return response()->json(['status'=>$status,'message'=>$returnData['message'],'data'=>[]]);
                    }                       
                    
                    //$start_time = date('Y-m-d H:i:s', $request->get('start_time')); 
                    //$end_time = date('Y-m-d H:i:s', $request->get('end_time')); 

                    $event = Event::create([
                    'event_name' => $request->get('event_name'),
                    'user_id' => $user->id,
                    'contact_person' => $request->get('contact_person'),            
                    'contact_no' => $request->get('contact_no'),
                    'address' => $request->get('address'),
                    'email' => $request->get('email'),
                    'state_id' => $state_id, 
                    'city_id' => $city_id, 
                    'country_id' => $country_id,
                    'sitting_capacity' => $request->get('sitting_capacity'),
                    'zip' => $request->get('zip'),
                    'lat' => $request->get('lat'),
                    'lng' => $request->get('lng'),
                    'nearest' => $request->get('nearest'),
                    'nearest_distance' => $request->get('nearest_distance'),
                    'mode' => $request->get('mode'),     

                    'start_time' => $request->get('start_time'),            
                    'end_time' => $request->get('end_time')
                ]);
                        
                $data['state'] = $request->state;
                $data['name'] = $user->name;
                $data['event_name'] = $request->event_name;    
                $data['address'] = $request->get('address');
                $data['city'] = $request->city;
                $data['country'] = $request->country;
                $data['start_date'] = $request->get('start_time');
                $data['end_date'] = $request->get('end_time');                    
                $data['type'] = 'Event';                                
                $data['supportEmail'] = config('mail.supportEmail');
                $data['website'] = config('app.site_url');  
                $data['site_name'] = config('app.site_name');
                $data['email'] = $this->encdesc($user->email,'decrypt');
                $data['subject'] = 'Event Add Success Mail From '.config('app.site_name');
                if($this->SendMail($data,'add_event')){                                        
                    $dupObj = new duplicateRequest();
                    $dupObj->md5val = $request->header("checksum");
                    $dupObj->save();
                    Log::info(['add event mail sent']);
                    $user->type = 'event';
                    $this->sendNotificationMail($user);
                    $status = 1;
                    Log::info('response add event',['status'=>$status,'message'=>"",'data'=>$event]); 
                    return response()->json(['status'=>$status,'message'=>"",'data'=>$event]);
                } 
                                
            }catch(Exception $e){
                return response()->json(['status'=>$status,'message'=>'data exception','data'=>json_decode("{}")]);
            }
            
        }

        
        /**
         * Show the form for get user lsit.
         *
         * @return \Illuminate\Http\Response
         */
        public function getEventList(Request $request){
            try{
                

                $status = 0;
                $message = "";      
                $cond[] = ['status','1'];  
               
                             
                if(!$this->verifyChecksum($request)){
                    return response()->json(["status"=>0,
                    "message"=>'checksum not verified',
                    "data"=>json_decode('{}')]);
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
                $cond[] = ['mode',"FREE"];
                
                //$events = Event::with(['country','state','city'])->where($cond)->orderBy('start_time','asc')->paginate(Config::get('app.record_per_page'));                                       
                
                $eventsCount = Event::with(['country','state','city'])->where($cond)->count();

                if(isset($request->event_type) && $request->event_type !=""){
                    if($request->event_type=="past"){
                        $cond[] = [DB::raw('DATE(end_time)'),'<',date('Y-m-d 00:00:00')];        
                    }else if($request->event_type=="upcoming"){
                        $cond[] = [DB::raw('DATE(start_time)'),'>',date('Y-m-d 00:00:00')];        
                    }else if($request->event_type=="current"){
                        $cond[] = [DB::raw('DATE(start_time)'),'<=',date('Y-m-d')];
                        $cond[] = [DB::raw('DATE(end_time)'),'>=',date('Y-m-d')];
                                               
                    }
                }
                            

                if($eventsCount==0){   
                    $eventCount = Event::where('status','1')->count();                                    
                    return response()->json(['status'=>1,
                    'message'=>$message,                    
                    'data'=>[],
                    'count'=>$eventCount
                    ]);
                }else{
                    $events1 = Event::with(['country','state','city','EventRating'])                    
                    ->where($cond)
                    ->orderBy('start_time','asc')
                    ->paginate(Config::get('app.record_per_page'));
                    $eventData = [];   
                    $avgRating = 0;                 
                    foreach($events1 as $k=>$v){
                        if(isset($v->EventRating)){
                            $ratingData = $this->getRating('event',$v->id);
                            if(count($ratingData)>0){
                                $v->rating = $ratingData['rating'];
                                $v->out_of = $ratingData['out_of'];
                            }
                        }
                        $v->country_name = $v->country->name;
                        $v->state_name = $v->state->name;
                        $v->city_name = $v->city->name;
                        //$v->rating = $avgRating;
                        unset($v->country);
                        unset($v->state);
                        unset($v->city);
                        unset($v->EventRating);
                        $eventData[$k] = $v;
                    }
                    $status = 1;                    
                    // return response()->json(['status'=>1,
                    // 'message'=>$message,                    
                    // 'data'=>$eventData
                    // ]);
                    if($events1->total() == 0){
                        $currentPage = 0;
                    }else{
                        $currentPage = $events1->currentPage();
                    }

                    return response()->json(['status'=>1,
                        'message'=>$message,
                        'total_record'=>$events1->total(),
                        'last_page'=>$events1->lastPage(),
                        'current_page'=>$currentPage,
                        'data'=>$eventData
                    ]);
                }

                
                
            }catch(Exception $e){   
                $message = "Data exception";
                return response()->json(['status'=>$status,'message'=>$message,'data'=>array()]);
            }            
            //$users[0]->role            
        }


        /**
         * Show the form for get user lsit.
         *
         * @return \Illuminate\Http\Response
         */
        public function getMyEventList(Request $request){
            try{
                $status = 0;
                $message = "";      
                if(!$this->verifyChecksum($request)){
                    return response()->json(["status"=>0,
                    "message"=>'checksum not verified',
                    "data"=>json_decode('{}')]);
                }
                $user  = JWTAuth::user();
                if($user->count()==0){
                    return response()->json(['status'=>$status,'message'=>'User not found','data'=>[]]);
                }
                $cond = [['status','1'],['user_id',$user->id],['mode','FREE']]; 
                if(isset($request->event_type) && $request->event_type !=""){
                    if($request->event_type=="past"){
                        $cond[] = [DB::raw('DATE(end_time)'),'<',date('Y-m-d 00:00:00')];        
                    }else if($request->event_type=="upcoming"){
                        $cond[] = [DB::raw('DATE(start_time)'),'>',date('Y-m-d 00:00:00')];        
                    }else if($request->event_type=="current"){
                        $cond[] = [DB::raw('DATE(start_time)'),'<=',date('Y-m-d')];
                        $cond[] = [DB::raw('DATE(end_time)'),'>=',date('Y-m-d')];
                                               
                    }
                }
                $events = Event::with(['country','state','city'])->where($cond)->orderBy('id')->paginate(Config::get('app.record_per_page'));
                           
                if($events->count() > 0){
                    $eventData = [];
                    foreach($events as $k=>$v){
                        $v->country_name = $v->country->name;
                        $v->state_name = $v->state->name;
                        $v->city_name = $v->city->name;
                        $ratingData = $this->getRating('event',$v->id);
                        if(count($ratingData)>0){
                            $v->rating = $ratingData['rating'];
                            $v->out_of = $ratingData['out_of'];
                        }
                        unset($v->country);
                        unset($v->state);
                        unset($v->city);                        
                        $eventData[$k] = $v;
                    }
                    $status = 1;
                    return response()->json([
                        'status'=>$status,
                        'message'=>$message,
                        'total_record'=>$events->total(),
                        'last_page'=>$events->lastPage(),
                        'current_page'=>$events->currentPage(),
                        'data'=>$eventData]);        
                }else{
                    $status = 1;
                    $message = "no data found";
                    return response()->json([
                        'status'=>$status,
                        'total_record'=>0,
                        'last_page'=>0,
                        'current_page'=>0,
                        'message'=>$message,'data'=>[]]);        
                }
            }catch(Exception $e){   
                $message = "Data exception";
                return response()->json(['status'=>$status,'message'=>$message,'data'=>[]]);
            }            
            //$users[0]->role            
        }


        /**
         * Edit event method
         * @return success or error
         * 
         * */
        public function editMyEvent(Request $request){
            
            $status = 0;
            $message = "";
            
            if(!$this->verifyChecksum($request)){
                return response()->json(["status"=>0,
                "message"=>'checksum not verified',
                "data"=>json_decode('{}')]);
            }

            if($this->checkDuplicateRequest($request)){
                return response()->json(['status'=>$status,'message'=>'Duplicate request','data'=>[]]);
            }
            
            Utility::stripXSS();
            $event_id = ($request->event_id) ? $request->event_id : 0;
            if(!$event_id){
                return response()->json(['status'=>$status,'message'=>'Please provide event ID','data'=>json_decode("{}")]);
            }

            $user  = JWTAuth::user();
                if($user->count()==0){
                    return response()->json(['status'=>$status,'message'=>'User not found','data'=>json_decode("{}")]);
            }

            $eventObj = Event::where([
                ['user_id',$user->id],
                ['id',$event_id]
            ])->first();
            if ($eventObj == null) {
                return response()->json(['status'=>$status,'message'=>'no event data for this ID','data'=>json_decode("{}")]);
            } else {
                if(isset($request->country) && isset($request->state) && isset($request->city)){
                    $cityObj = new City();
                    $returnData  = $cityObj->getCountryStateCityByName($request);
                    if($returnData['error']==0 && $returnData['success']==1){
                        $country_id = $returnData['data']['country_id'];
                        $state_id = $returnData['data']['state_id'];
                        $city_id = $returnData['data']['city_id'];

                        $eventObj->country_id = $country_id;
                        $eventObj->state_id = $state_id;
                        $eventObj->city_id = $city_id;

                    }else{
                        return response()->json(['status'=>$status,'message'=>$returnData['message'],'data'=>[]]);
                    }
                }         
                
                if(isset($request->address)){
                    $request->address = $this->string_replace("__","/",$request->address);
                    $request->merge([
                      'address'=>$request->address
                    ]);
                }            

                if((isset($request->start_time) && $request->start_time < date('Y-m-d')) && (isset($request->end_time) && $request->end_time < date('Y-m-d'))){
                    return response()->json(['status'=>0,'message'=>"Invalid Date",'data'=>[]]);

                }  

                //print_r($eventObj); die;
                $eventObj->event_name = ($request->event_name) ? $request->event_name : $eventObj->event_name;
                $eventObj->contact_person = ($request->contact_person) ? $this->string_replace("__","/",$request->contact_person) : $eventObj->contact_person;
                $eventObj->contact_no = ($request->contact_no) ? $this->string_replace("__","/",$request->contact_no): $eventObj->contact_no;
                $eventObj->address = ($request->address) ? $request->address: $eventObj->address;
                $eventObj->email = ($request->email) ? $this->string_replace("__","/",$request->email): $eventObj->email;
                $eventObj->sitting_capacity = ($request->sitting_capacity) ? $request->sitting_capacity: $eventObj->sitting_capacity;
                $eventObj->zip = ($request->zip) ? $request->zip : $eventObj->zip;
                $eventObj->lat = ($request->lat) ? $request->lat: $eventObj->lat;
                $eventObj->lng = ($request->lng) ?  $request->lng : $eventObj->lng;
                $eventObj->nearest = $request->nearest;
                $eventObj->nearest_distance = $request->nearest_distance;                
                $eventObj->mode = ($request->mode) ?  $request->mode : $eventObj->mode;
                $eventObj->start_time = ($request->start_time) ?  $request->start_time : $eventObj->start_time;
                $eventObj->end_time = ($request->end_time) ? $request->end_time : $eventObj->end_time;
                //$eventObj->status = '0';
                
                if(!$eventObj->save()){
                    return response()->json(['status'=>$status,'message'=>'Unable to save','data'=>json_decode("{}")]);                    
                }else{
                    return response()->json(['status'=>1,'message'=>'event updated successfully','data'=>json_decode("{}")]);                    
                }                
            }
         }   
    }