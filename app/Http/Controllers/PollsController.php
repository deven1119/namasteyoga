<?php

    namespace App\Http\Controllers;
    use App\Http\Controllers\Traits\NotifyMail;
    use App\Poll;
	use App\User;
    use App\State;
    use App\City;
    use App\Country;
    use App\NotifyMe;
    use Illuminate\Support\Facades\Validator;
    use Illuminate\Http\Request;
    use App\Http\Controllers\Traits\SendMail;
    use JWTAuth;
    use Tymon\JWTAuth\Exceptions\JWTException;
    use Illuminate\Support\Facades\Log;
    use Config;
    use DateTime;
    use Mail;
	use Auth;
	use DB;

    class PollsController extends Controller
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
                               
                return view('polls.index');
            }catch(Exception $e){
                abort(500, $e->message());
            }
        }
		/**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
  
    public function create(Request $request){
      $polls = '';
	    return view('polls.create',compact(['polls']));
    }
	public function store(Request $request){
		
		$validator = Validator::make($request->all(),[
                'poll_name'=>'required|max:255',
                'questions.*'=>'required|max:255',
                'options[0].*' => 'required|max:255'
            ],
            [
              'poll_name.required'=>'Please enter poll name',
              'questions.required'=>'Please enter questions',
              'options.required'=>'Please enter option'
            ]
       );
        if ($validator->fails()) {   
			return back()->withErrors($validator);	
            //return response()->json(['failed'=>1,'messages'=>$validator->messages()]);
        }
		
		$date = date('Y-m-d H:i:s');
		
		$poll = new poll;
		$poll->poll_name = trim($request->poll_name);
		//$poll->status = 0;
		//$poll->start_date =  $date;
		//$date = strtotime($date);
		//$poll->end_date = date('Y-m-d H:i:s',strtotime("+7 day", $date));
		$poll->created_by = Auth::user()->id;
		if($poll->save()){
			
			$poll_id = $poll->id;
			foreach($request->questions as $qid=>$question){
				if($question!=''){
					if(DB::table('audience_poll_questions')->insert(
						  ['audience_poll_id' => $poll_id, 'question'=>trim($question),'created_by'=>Auth::user()->id]
						)){
							$audience_poll_question_id = DB::getPdo()->lastInsertId();
							foreach($request->options[$qid] as $option){
								if($option!=''){
									DB::table('audience_poll_question_options')->insert(
									  ['audience_poll_question_id' => $audience_poll_question_id, 'options'=>trim($option)]
									);
								}
							}
						}
				}
			}
			 return redirect()->action('PollsController@index')->with('flash_message', 'Poll created successfully')
				->with('flash_type', 'alert-success');
			
        }else{
            return response()->json(['error'=>1,'msg'=>'Failed to create Poll']);
        }
	}
	
	/**
     * Display the specified resource.
     *
     * @param  \Poll  $poll
     * @return \Illuminate\Http\Response
     */
	public function view(Request $request){
		$id = $request->id;
		$current_user = Auth::user()->id;
		$poll = Poll::find($id);
		
		if(!$poll){
			return redirect()->action('PollsController@index')->with('flash_message', 'Poll not available')->with('flash_type', 'alert-danger');
		}
		
		$questions = DB::table('audience_poll_questions')->where('audience_poll_id',$id)->get();
		$questionData = [];
		foreach($questions as $key=>$question){
			$questionData[$key]['id'] = $question->id;
			$questionData[$key]['question'] = $question->question;
						
			$options = DB::table('audience_poll_question_options')->where('audience_poll_question_id',$question->id)->get();
			
			$questionData[$key]['options'] = $options;
		}
        return view('polls.view', compact('poll','questionData'));
	}
	/**
     * Display the specified resource.
     *
     * @param  \App\Poll  $poll
     * @return \Illuminate\Http\Response
     */
	public function show(Request $request){
		$id = $request->id;
		$current_user = Auth::user()->id;
		$poll = Poll::find($id);
		
		if(!$poll){
			return redirect()->action('PollsController@index')->with('flash_message', 'Invalid Poll')
				->with('flash_type', 'alert-danger');
		}
		if($poll->is_editable==0)
			return redirect()->action('PollsController@index')->with('flash_message', 'Can not edit poll')
				->with('flash_type', 'alert-danger');
			
		$questions = DB::table('audience_poll_questions')->where('audience_poll_id',$id)->get();
		$questionData = [];
		foreach($questions as $key=>$question){
			$questionData[$key]['id'] = $question->id;
			$questionData[$key]['question'] = $question->question;
						
			$options = DB::table('audience_poll_question_options')->where('audience_poll_question_id',$question->id)->get();
			
			$questionData[$key]['options'] = $options;
		}
        return view('polls.edit', compact('poll','questionData'));
	}
	
	/**
     * Display indivisual Poll result.
     *
     * @param  \App\poll  $poll
     * @return \Illuminate\Http\Response
     */
	public function viewResult(Request $request){
		$id = $request->id;
		
		$poll = Poll::find($id);
		
		if(!$poll){
			return redirect()->action('PollsController@index')->with('flash_message', 'Invalid Poll')
				->with('flash_type', 'alert-danger');
		}
		if($poll->status==0)
			return redirect()->action('PollsController@index')->with('flash_message', 'Poll not pulbish yet')
				->with('flash_type', 'alert-danger');
			
		$questions = DB::table('audience_poll_questions')->where('audience_poll_id',$id)->get();
		$questionData = [];
		foreach($questions as $key=>$question){
			$questionData[$key]['id'] = $question->id;
			$questionData[$key]['question'] = $question->question;
						
			$options = DB::table('audience_poll_question_options')->where('audience_poll_question_id',$question->id)->get();
			
			$optionsData =[];
			foreach($options as $okey=>$oData){
				
				$no_submition = DB::table('audience_poll_responses')->where('audience_poll_option_id',$oData->id)->count();
				$optionsData[] = array(
				'Option'=>$oData->options,
				'no_submistion' => $no_submition
				);
			}
			
			$questionData[$key]['options'] = $optionsData;
		}
		//dd($questionData);
        return view('polls.result', compact('poll','questionData'));
	}
	
	/**
     * Update the specified resource.
     *
     * @param  \App\Poll  $poll
     * @return \Illuminate\Http\Response
     */
	public function update(Request $request){
		$validator = Validator::make($request->all(),[
                'poll_name'=>'required|max:255',
                'questions.*'=>'required|max:255',
                'options[0].*' => 'required|max:255'
            ],
            [
              'poll_name.required'=>'Please enter poll name',
              'questions.required'=>'Please enter questions',
              'options.required'=>'Please enter option'
            ]
       );
        if ($validator->fails()) {    
            return response()->json(['failed'=>1,'messages'=>$validator->messages()]);
        }
		
		$id = $request->id;
		$current_user = Auth::user()->id;
		$pollArr['poll_name'] = trim($request->poll_name);
		$pollArr['updated_by'] = $current_user;
		
		 if(Poll::where('id',$id)->update($pollArr)){
			 
			 $questions = DB::table('audience_poll_questions')->where('audience_poll_id',$id)->get();
			 foreach($questions as $question){
				  DB::table('audience_poll_question_options')->where('audience_poll_question_id',$question->id)->delete();
			 }
			 DB::table('audience_poll_questions')->where('audience_poll_id',$id)->delete();
			 
			 foreach($request->questions as $qid=>$postQuestion){
				 if($postQuestion!=''){
					if(DB::table('audience_poll_questions')->insert(
						  ['audience_poll_id' => $id, 'question'=>trim($postQuestion),'created_by'=>$current_user]
						)){
							$audience_poll_question_id = DB::getPdo()->lastInsertId();
							foreach($request->options[$qid] as $option){
								if($option!=''){
									DB::table('audience_poll_question_options')->insert(
									  ['audience_poll_question_id' => $audience_poll_question_id, 'options'=>trim($option)]
									);
								}
							}
						}
				 }
			}
			
		 return redirect()->action('PollsController@index')->with('flash_message', 'Poll updated successfully')->with('flash_type', 'alert-success');
			
        }else{
            return back()->with('flash_message', 'Could not update Poll')->with('flash_type', 'alert-danger');;
        }
	}
		/**
         * Polls list ajax data tables
         */
		public function pollsIndexAjax(Request $request){ 
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
               
                //$cond[] = ['status','<>',''];
                $polls = Poll::orderBy('id','desc');
                
                
                if ($request->search['value'] != "") {            
                  $polls = $polls->where('poll_name','LIKE',"%".$search."%");
                } 
     // echo $polls->toSql();die;
                $total = $polls->count();
                if($end==-1){
                  $polls = $polls->get();
                }else{
                  $polls = $polls->skip($start)->take($end)->get();
                }
                
                if($polls->count() > 0){
					$i = 1;

                    foreach($polls as $k=>$v){
					$total_no_of_submission = DB::table('audience_poll_responses')->where([
					['audience_poll_id',$polls[$k]->id]
					])->count(DB::raw('DISTINCT user_id'));
					
                      $polls[$k]->sr_no = $i; 
                      $polls[$k]->id = $polls[$k]->id; 
                      $polls[$k]->poll_name = $polls[$k]->poll_name; 
                      $polls[$k]->total_no_of_submission = $total_no_of_submission;
                      $polls[$k]->start_date = ($polls[$k]->start_date) ?date('d-M-Y h:i A',strtotime($polls[$k]->start_date)) : 'Not descied yet'; 
                      $polls[$k]->end_date = ($polls[$k]->end_date) ? date('d-M-Y h:i A',strtotime($polls[$k]->end_date)) : 'Not descied yet'; 
                      $polls[$k]->status = $polls[$k]->status; 
                      $polls[$k]->is_editable = $polls[$k]->is_editable; 
					  $i++;
                    }
                  }     
                
                $response["recordsFiltered"] = $total;
                $response["recordsTotal"] = $total;
                //response["draw"] = draw;
                $response["success"] = 1;
                $response["data"] = $polls;
                
              } catch (Exception $e) {    
      
              }
            
      
            return response($response);
		}
		
		//change status of polls
		public function changestatus(Request $request){
          try{
			ini_set('memory_limit', '256M');
            if(!$this->checkAuth(4)){
               return abort(404);; 
            }
			$date = date('Y-m-d H:i:s');
            $poll = new Poll();
			
            $id = $request->poll_id;
            $pollData = $poll->findOrFail($id);
			
			//if the poll has been submited by any user then poll status can not changed.
			/* $total_no_of_submission = DB::table('audience_poll_responses')->where([
					['audience_poll_id',$id]
					])->count(); */
					
		/* if($total_no_of_submission>0){
			$msg = "This poll already submited by user(s), now status can not be changed for this poll.";
					
			return response()->json(["status"=>1,"message"=>$msg,"data"=>json_decode("{}")]); 
		} */
			
			if(!$pollData){
				return redirect()->action('PollsController@index')->with('flash_message', 'Poll not available')->with('flash_type', 'alert-danger');
			}
			
			if($request->status==1){
				//only 4 polls should be activated.
				$pollCount = $poll::where('status',1)->count();
				//echo $pollCount;die;
				if($pollCount==4){
					$msg = "Already four polls are activated,if you want to activate this polll then you will have to  decativate anyone of the activated polls.";
					
					return response()->json(["status"=>1,"message"=>$msg,"data"=>json_decode("{}")]);  				
				}
			}
             if($pollData->count()>0){
              $pollData->status = $request->status;
              $pollData->is_editable = 0;
			  $pollData->start_date =  $date;
		      $date = strtotime($date);
		      $pollData->end_date = date('Y-m-d H:i:s',strtotime("+7 day", $date));
			  
              if($pollData->save()){
                if($request->status==1){
                  $msg = "Poll Activated Successfully";
				  
				  $userData = User::where([['device_id','!=',null],['device_id','!=',''],['device_id','!=','null']])->whereIn('role_id',[2,3,5])->select('device_id')->get();
            
				//dd($userData);

                  $data[] = [];
				  $msgBody = 'Congratulations, we have added a new poll, please be the first one to submit it.';
				  $msgBody .= 'Poll Start Date: '.date('d-M-Y h:i A',strtotime($pollData->start_date));
				   $msgBody .=', Poll End Date: '.date('d-M-Y h:i A',strtotime($pollData->end_date));
				   
				   $data['subject'] = $pollData->poll_name.' is ready';
				   $data['messageBody'] = $msgBody;
				   
				$arr_deviceId = array();
				foreach($userData as $ukey=>$user){  
					if($user['device_id']!=null && $user['device_id']!='null')			
						$arr_deviceId[] = $user['device_id'];			  
				}
				//dd($arr_deviceId);
				$this->sendPushNotification($arr_deviceId,$data);
				
                }else{
                  $msg = "Poll De-activated Successfully";
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
		/*
			Delete polls
		*/		
		public function destroy(Request $request){
			
			$msg = '';
		  $id = $request->id;
		  $checkPoll = Poll::where('id',$id)->first();
		  if($checkPoll->status==0){
			  /* 
			  $questions = DB::table('audience_poll_questions')->where('audience_poll_id',$id)->get();
			 foreach($questions as $question){
				  DB::table('audience_poll_question_options')->where('audience_poll_question_id',$question->id)->delete();
			 }
			 DB::table('audience_poll_questions')->where('audience_poll_id',$id)->delete(); */
			 
			  $deleted = Poll::find($id)->delete();
			  
			  if($deleted)
				 $msg = 'Record deleted successfully';
			  else
			   $msg = 'Failed to delete poll';
		  }else{
			  $msg = "Can not delete this poll";
		  }
		  echo $msg;
		}
		
		/* Get all Polls which are about to expire
			Send notification to user 
		*/
		public function PollsAboutToExpire(Request $request){
			
			try{
				 $pollCount = Poll::where('status',1)->whereRaw('DATEDIFF(end_date,CURRENT_DATE())=1')->count();
				 if($pollCount>0){
					 
					 $userData = User::where([['device_id','!=',null],['device_id','!=',''],['device_id','!=','null']])->whereIn('role_id',[2,3,5])->select('device_id')->get();
					 
					 $polls = Poll::where('status',1)->whereRaw('DATEDIFF(end_date,CURRENT_DATE())=1')->get();
					 foreach($polls as $poll){
						 
						 
						 
						  $data[] = [];
						  $msgBody = 'Poll Start Date: '.date('d-M-Y h:i A',strtotime($poll->start_date));
						  $msgBody .=', Poll End Date: '.date('d-M-Y h:i A',strtotime($poll->end_date));
						   
						   $data['subject'] = 'New Poll('.$poll->poll_name.') Created';
						   $data['messageBody'] = $msgBody;
						   
						$arr_deviceId = array();
						foreach($userData as $ukey=>$user){  
							if($user['device_id']!=null && $user['device_id']!='null')			
								$arr_deviceId[] = $user['device_id'];			  
						}
						//dd($arr_deviceId);
						$this->sendPushNotification($arr_deviceId,$data); 
					 }
				 }
				
			}catch(Exception $e){
				abort(500, $e->message());
			  } 
		}
	}
?>