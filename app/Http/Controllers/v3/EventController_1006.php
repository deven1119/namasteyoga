<?php

    namespace App\Http\Controllers\v3;
    
    use App\Event;
    use App\State;
    use App\City;
    use App\Country;
    use App\NotifyMe;
    use Illuminate\Support\Facades\Validator;
    use Illuminate\Http\Request;
    use JWTAuth;
    use Tymon\JWTAuth\Exceptions\JWTException;
    use App\Http\Controllers\Traits\NotifyMail;
    use Config;

    class EventController extends Controller
    {
        use NotifyMail;
        public function addEvent(Request $request){
            
            try{
                    $success = 0;
                    $message = "";
                    $validator = Validator::make($request->all(), [
                        'event_name' => 'required|string|max:255',
                        'email' => 'required|string|email|max:255',
                        'contact_person' => 'required|string|max:100',
                        'contact_no' => 'required|string|max:15',
                    ]);

                    $user  = JWTAuth::user();

                    if($validator->fails()){
                            return response()->json(['status'=>$status,'message'=>'invalid data set','data'=>json_decode("{}")]);
                            //return response()->json($validator->errors()->toJson(), 400);
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
                    'mode' => $request->get('mode'),     

                    'start_time' => $request->get('start_time'),            
                    'end_time' => $request->get('end_time')
                ]);
               
                //$token = JWTAuth::fromUser($user); 
                //return response()->json(compact('event'),201);

                //email sent code for event creator
                $notifyList = NotifyMe::with(['Country.State.City'])->where([
                    ['notified',0],
                    ['type','event'],
                    ['city_id',$city_id],
                    ['state_id',$state_id],
                    ['country_id',$country_id],
                    ])->get();                                

                
                if($notifyList->count()){
                    $mailArr = [];
                    $ids = [];
                    foreach($notifyList as $k=>$v){
                        if(!in_array($v->mail,$mailArr)){                            
                            array_push($mailArr,$v->email);
                            array_push($ids,$v->id);
                            //$ids[] = $v->id;
                        }                                                   
                    }      
                    //print_r($mailArr); die;              
                    $data['state'] = $request->state;
                    $data['name'] = $user->name;
                    $data['event_name'] = $request->get('event_name');    
                    $data['address'] = $request->get('address');
                    $data['city'] = $request->city;
                    $data['country'] = $request->country;
                    $data['email'] = $mailArr;
                    $data['type'] = 'Event';                                
                    $data['supportEmail'] = config('mail.supportEmail');
                    $data['website'] = config('app.site_url');                                    
                    // if($this->sendNotifyEmail($data)){
                    //     NotifyMe::whereIn('id', $ids)->update(['notified' => 1]);
                    // } 
                }

                $status = 1;
                return response()->json(['status'=>$status,'message'=>"",'data'=>$event]);
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
                
                $cond[] = ['mode','FREE'];

                if($eventsCount==0){   
                    $eventCount = Event::where('status','1')->count();                                    
                    return response()->json(['status'=>1,
                    'message'=>$message,                    
                    'data'=>[],
                    'count'=>$eventCount
                    ]);
                }else{
                    $events1 = Event::with(['country','state','city'])->where($cond)->orderBy('start_time','asc')->paginate(Config::get('app.record_per_page'));
                    $eventData = [];                    
                    foreach($events1 as $k=>$v){
                        $v->country_name = $v->country->name;
                        $v->state_name = $v->state->name;
                        $v->city_name = $v->city->name;
                        unset($v->country);
                        unset($v->state);
                        unset($v->city);
                        $eventData[$k] = $v;
                    }
                    $status = 1;                    
                    // return response()->json(['status'=>1,
                    // 'message'=>$message,                    
                    // 'data'=>$eventData
                    // ]);
                    
                    return response()->json(['status'=>1,
                        'message'=>$message,
                        'total_record'=>$events1->total(),
                        'last_page'=>$events1->lastPage(),
                        'current_page'=>$events1->currentPage(),
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
                         
                $user  = JWTAuth::user();
                if(count($user)==0){
                    return response()->json(['status'=>$status,'message'=>'User not found','data'=>json_decode("{}")]);
                }
                $cond = [['status','1'],['user_id',$user->id]]; 
                $events = Event::with(['country','state','city'])->where($cond)->get();
                $eventData = [];
                if(count($events) > 0){
                    foreach($events as $k=>$v){
                        $v->country_name = $v->country->name;
                        $v->state_name = $v->state->name;
                        $v->city_name = $v->city->name;
                        unset($v->country);
                        unset($v->state);
                        unset($v->city);
                        $eventData[$k] = $v;
                    }
                    $status = 1;
                    return response()->json(['status'=>$status,'message'=>$message,'data'=>$eventData]);        
                }else{
                    //$status = 1;
                    $message = "no data found";
                    return response()->json(['status'=>$status,'message'=>$message,'data'=>json_decode("{}")]);        
                }
            }catch(Exception $e){   
                $message = "Data exception";
                return response()->json(['status'=>$status,'message'=>$message,'data'=>json_decode("{}")]);
            }            
            //$users[0]->role            
        }
    }