<?php

    namespace App\Http\Controllers;
    use App\Http\Controllers\Traits\NotifyMail;
    use App\Event;
    use App\State;
    use App\City;
    use App\Country;
    use App\NotifyMe;
    use App\EventRating;
    use Illuminate\Support\Facades\Validator;
    use Illuminate\Http\Request;
    use App\Http\Controllers\Traits\SendMail;
    use JWTAuth;
    use Tymon\JWTAuth\Exceptions\JWTException;
    use Illuminate\Support\Facades\Log;
    use Config;
    use DateTime;
    use Mail;

    class EventController extends Controller
    {
        use SendMail;

        /**
         * Display a listing of the resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function index()
        {
       
            try{            
                               
                return view('events.index');
            }catch(Exception $e){
                abort(500, $e->message());
            }
        }
		/**
         * Display a listing of the pending events.
         *
         * @return \Illuminate\Http\Response
         */
        public function pendingEvents()
        {
            try{            
                               
                return view('events.pendingEvents');
            }catch(Exception $e){
                abort(500, $e->message());
            }
        }

        /**
         * Approved event list ajax data tables
         */

        public function eventIndexAjax(Request $request){
     
            $draw = ($request->data["draw"]) ? ($request->data["draw"]) : "1";
            $response = [
              "recordsTotal" => "",
              "recordsFiltered" => "",
              "data" => "",
              "success" => 0,
              "msg" => ""
            ];
            try {
                
                $start = ($request->start) ? $request->start : 0;
                $end = ($request->length) ? $request->length : 10;
                $search = ($request->search['value']) ? $request->search['value'] : '';
                //echo 'ddd';die;
                $cond[] = ['status','1'];
                $events = Event::with(['Country','State','City'])->where($cond);
                //echo '<pre>'; print_r($users); die;
                
                if ($request->search['value'] != "") {            
                   $events->where(function($result) use($search){
                    $result->where('email','LIKE',"%".$search."%")
                        ->orWhere('event_name','LIKE',"%".$search."%")
					  ->orWhere('contact_no','LIKE',"%".$search."%")
					  ->orWhere('contact_person','LIKE',"%".$search."%")
					  ->orWhere('address','LIKE',"%".$search."%");
                   });
                } 
      
                $total = $events->count();
                if($end==-1){
                  $events = $events->get();
                }else{
                  $events = $events->skip($start)->take($end)->get();
                }
                
                if($events->count() > 0){
                    foreach($events as $k=>$v){
                      $events[$k]->email = $this->encdesc($events[$k]->email,'decrypt'); 
                      $events[$k]->contact_person = $this->encdesc($events[$k]->contact_person,'decrypt'); 
                      $events[$k]->contact_no = $this->encdesc($events[$k]->contact_no,'decrypt'); 
                    }
                  }     
                
                $response["recordsFiltered"] = $total;
                $response["recordsTotal"] = $total;
                //response["draw"] = draw;
                $response["success"] = 1;
                $response["data"] = $events;
                
              } catch (Exception $e) {    
      
              }
            
      
            return response($response);
        }   
			/**
         * Pending event list ajax data tables
         */

        public function pendingEventIndexAjax(Request $request){
     
            $draw = ($request->data["draw"]) ? ($request->data["draw"]) : "1";
            $response = [
              "recordsTotal" => "",
              "recordsFiltered" => "",
              "data" => "",
              "success" => 0,
              "msg" => ""
            ];
            try {
                
                $start = ($request->start) ? $request->start : 0;
                $end = ($request->length) ? $request->length : 10;
                $search = ($request->search['value']) ? $request->search['value'] : '';
                //echo 'ddd';die;
                $cond[] = ['status','0'];
                $events = Event::with(['Country','State','City'])->where($cond);
                //echo '<pre>'; print_r($users); die;
                
                if ($request->search['value'] != "") {            
                  /*  $events->where('email','LIKE',"%".$search."%")
                  ->orWhere('event_name','LIKE',"%".$search."%")
                  ->orWhere('contact_no','LIKE',"%".$search."%")
                  ->orWhere('contact_person','LIKE',"%".$search."%")
                  ->orWhere('address','LIKE',"%".$search."%");
				   */
				  $events->where(function($result) use($search){
                    $result->where('email','LIKE',"%".$search."%")
                        ->orWhere('event_name','LIKE',"%".$search."%")
					  ->orWhere('contact_no','LIKE',"%".$search."%")
					  ->orWhere('contact_person','LIKE',"%".$search."%")
					  ->orWhere('address','LIKE',"%".$search."%");
                   });
                } 
      
                $total = $events->count();
                if($end==-1){
                  $events = $events->get();
                }else{
                  $events = $events->skip($start)->take($end)->get();
                }
                
                if($events->count() > 0){
                    foreach($events as $k=>$v){
                      $events[$k]->email = $this->encdesc($events[$k]->email,'decrypt'); 
                      $events[$k]->contact_person = $this->encdesc($events[$k]->contact_person,'decrypt'); 
                      $events[$k]->contact_no = $this->encdesc($events[$k]->contact_no,'decrypt'); 
                    }
                  }     
                
                $response["recordsFiltered"] = $total;
                $response["recordsTotal"] = $total;
                //response["draw"] = draw;
                $response["success"] = 1;
                $response["data"] = $events;
                
              } catch (Exception $e) {    
      
              }
            
      
            return response($response);
        }   		
        //use NotifyMail;
       
        function validateDateTime($dateStr, $format)
        {
            date_default_timezone_set('UTC');
            $date = DateTime::createFromFormat($format, $dateStr);
            return $date && ($date->format($format) === $dateStr);
        }


        public function changestatus(Request $request){
          try{

            if(!$this->checkAuth(4)){
               return abort(404);; 
            }
            $events = new Event();
            $status = $request->status;
            $id = $request->eventid;
            $eventData = $events->findOrFail($id);
            
            //print_r($userData); die;
            if($eventData->count()>0){
              $eventData->status = $status;
              if($eventData->save()){
                if($status==1){
                  $msg = "Record Activated Successfully";

                  $data = [];          
                  $data['email'] = $this->encdesc($eventData->email,'decrypt');
                  $data['event_name'] = $eventData->event_name;
                  $data['name'] = $eventData->contact_person;
                  $data['supportEmail'] = config('mail.supportEmail');
                  $data['website'] = config('app.site_url');  
                  $data['site_name'] = config('app.site_name');                                           
                  $data['subject'] = 'Event Approval Mail '.config('app.site_name'); 
                  
                  $this->SendMail($data,'admin_event_approve');
                }else{
                  $msg = "Record De-activated Successfully";
                }            
                return response()->json(["status"=>1,"message"=>$msg,"data"=>json_decode("{}")]);            
              }else{
                return response()->json(["status"=>0,"message"=>"Technical ERROR","data"=>json_decode("{}")]);            
              }
            }else{
              return response()->json(["status"=>0,"message"=>"Technical error","data"=>json_decode("{}")]);          
            }
            
          }catch(Exception $e){
            abort(500, $e->message());
          }
        }
                     
    }