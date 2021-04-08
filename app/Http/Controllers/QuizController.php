<?php

    namespace App\Http\Controllers;
    use App\Http\Controllers\Traits\NotifyMail;
    use App\Quiz;
	use App\User;
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

    class QuizController extends Controller
    {
		/**
         * Display a listing of the resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function index()
        {
            try{                           
                return view('quiz.index');
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
		  $quizes = '';
		   return view('quiz.create',compact(['quizes']));
		}
		
		/**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
		public function store(Request $request){
		
		$validator = Validator::make($request->all(),[
                'quiz_name'=>'required',
                'quiz_time' => 'required',
                'valid_for' => 'required'
            ],
            [
              'quiz_name.required'=>'Please enter Quiz name',
              'quiz_time.required'=>'Please enter quiz time',
              'valid_for.required'=>'Please enter quiz valid for'
            ]
		);
			if ($validator->fails()) {   
				return back()->withErrors($validator);	
				//return response()->json(['failed'=>1,'messages'=>$validator->messages()]);
			}
			
			$quiz = new Quiz;
			$quiz->quiz_name = $request->quiz_name;
			$quiz->valid_for = $request->valid_for;
			$quiz->quiz_time = $request->quiz_time;
			$quiz->created_by = Auth::user()->id;
			if($quiz->save()){
				
				$quiz_id = $quiz->id;
				 return redirect('/quiz/add/'.$quiz_id)
				 ->with('flash_message', 'Quiz added successfully, add questions here')
				->with('flash_type', 'alert-success');
				
			}else{
				return response()->json(['error'=>1,'msg'=>'Failed to create Quiz']);
			}
		}
		
		/**
     * Display the specified resource.
     *
     * @param  \Quiz  $quiz
     * @return \Illuminate\Http\Response
     */
	public function view(Request $request){
		$id = $request->id;
		$current_user = Auth::user()->id;
		$quiz = Quiz::find($id);
		
		if(!$quiz){
			return redirect()->action('QuizController@index')->with('flash_message', 'Quiz not available')->with('flash_type', 'alert-danger');
		}
		
		$questions = DB::table('audience_poll_questions')->where('audience_poll_id',$id)->get();
		$questionData = [];
		foreach($questions as $key=>$question){
			$questionData[$key]['id'] = $question->id;
			$questionData[$key]['question'] = $question->question;
						
			$options = DB::table('audience_poll_question_options')->where('audience_poll_question_id',$question->id)->get();
			
			$questionData[$key]['options'] = $options;
		}
        return view('quiz.view', compact('quiz','questionData'));
	}
	
		/**
		 * Add questions of a quiz.
		 *
		 * @param  \Illuminate\Http\Request  $request
		 * @return \Illuminate\Http\Response
		*/
		public function AddQuestions(Request $request){
			$id = $request->quiz_id;
			$quiz = Quiz::find($id);
			if(!$quiz){
				return redirect('/quiz')
				 ->with('flash_message', 'Invalid quiz')
				->with('flash_type', 'alert-danger');
			}
			if($quiz->is_editable==0){
				return redirect('/quiz')
				 ->with('flash_message', 'Quiz not editable')
				->with('flash_type', 'alert-danger');
			}
		   return view('quiz.add',compact(['quiz']));
		}
		
		/**
		 * Store questions of a quiz.
		 *
		 * @param  \Illuminate\Http\Request  $request
		 * @return \Illuminate\Http\Response
		*/
		public function storeQuestions(Request $request){
			$validator = Validator::make($request->all(),[
                'questions*'=>'required',
                'answer[0].*' => 'required',
                'options[0].*' => 'required'
            ],
            [
              'quiz_name.required'=>'Please enter Quiz name',
              'quiz_time.required'=>'Please enter quiz time',
              'valid_for.required'=>'Please enter quiz valid for'
            ]
		);
			if ($validator->fails()) {   
				return back()->withErrors($validator);	
				//return response()->json(['failed'=>1,'messages'=>$validator->messages()]);
			}
			

			$id = $request->quiz_id;
			$quiz = Quiz::find($id);
			if(!$quiz){
				return redirect('/quiz')
				 ->with('flash_message', 'Invalid quiz')
				->with('flash_type', 'alert-danger');
			}
			if($quiz->is_editable==0){
				return redirect('/quiz')
				 ->with('flash_message', 'Quiz not editable')
				->with('flash_type', 'alert-danger');
			}
			
			if($id){
				foreach($request->questions as $qid=>$question){
					if($question!=''){
						
					}
				}
			}
			else{
				return redirect('/quiz')
				 ->with('flash_message', 'Invalid data set')
				->with('flash_type', 'alert-danger');
			}
		}
		
		/*get Data by ajax to show quizes*/
		public function quizIndexAjax(Request $request){
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
                $quizes = Quiz::orderBy('id','desc');
                
                
                if ($request->search['value'] != "") {            
                  $quizes = $quizes->where('quiz_name','LIKE',"%".$search."%");
                } 
     // echo $quizes->toSql();die;
                $total = $quizes->count();
                if($end==-1){
                  $quizes = $quizes->get();
                }else{
                  $quizes = $quizes->skip($start)->take($end)->get();
                }
                
                if($quizes->count() > 0){
					$i = 1;

                    foreach($quizes as $k=>$v){
					 $total_no_of_submission = 0;/*DB::table('audience_poll_responses')->where([
					['audience_poll_id',$quizes[$k]->id]
					])->count(DB::raw('DISTINCT user_id')); */
					
                      $quizes[$k]->sr_no = $i; 
                      $quizes[$k]->id = $quizes[$k]->id; 
                      $quizes[$k]->quiz_name = $quizes[$k]->quiz_name;  
                      $quizes[$k]->quiz_time = bcadd(0,$quizes[$k]->quiz_time/60,2);  
                      $quizes[$k]->total_no_of_submission = $total_no_of_submission;
                      $quizes[$k]->start_date = ($quizes[$k]->start_date) ?date('d-M-Y h:i A',strtotime($quizes[$k]->start_date)) : 'Not descied yet'; 
                      $quizes[$k]->end_date = ($quizes[$k]->end_date) ? date('d-M-Y h:i A',strtotime($quizes[$k]->end_date)) : 'Not descied yet'; 
                      $quizes[$k]->status = $quizes[$k]->status; 
                      $quizes[$k]->is_editable = $quizes[$k]->is_editable; 
					  $i++;
                    }
                  }     
                
                $response["recordsFiltered"] = $total;
                $response["recordsTotal"] = $total;
                //response["draw"] = draw;
                $response["success"] = 1;
                $response["data"] = $quizes;
                
              } catch (Exception $e) {    
      
              }
            
      
            return response($response);
		}
		//change status of quiz
		public function changestatus(Request $request){
          try{
			ini_set('memory_limit', '256M');
            if(!$this->checkAuth(4)){
               return abort(404);; 
            }
			$date = date('Y-m-d H:i:s');
            $quiz = new Quiz();
			
            $id = $request->quiz_id;
            $quizData = $quiz->findOrFail($id);
			
			
			if(!$quizData){
				return redirect()->action('QuizController@index')->with('flash_message', 'Quiz not available')->with('flash_type', 'alert-danger');
			}
			
			if($request->status==1){
				//only 4 polls should be activated.
				$quizCount = $quiz::where('status',1)->count();
				//echo $quizCount;die;
				if($quizCount==4){
					$msg = "Already four quiz are activated,if you want to activate this quiz then you will have to  decativate anyone of the activated quizes.";
					
					return response()->json(["status"=>1,"message"=>$msg,"data"=>json_decode("{}")]);  				
				}
			}
             if($quizData->count()>0){
              $quizData->status = $request->status;
              $quizData->is_editable = 0;
			  $quizData->start_date =  $date;
		      $date = strtotime($date);
		      $quizData->end_date = date('Y-m-d H:i:s',strtotime("+7 day", $date));
			  
              if($quizData->save()){
                if($request->status==1){
                  $msg = "Quiz Activated Successfully";
				  
				  $userData = User::where([['device_id','!=',null],['device_id','!=',''],['device_id','!=','null']])->whereIn('role_id',[2,3,5])->select('device_id')->get();
            
				//dd($userData);

                  $data[] = [];
				  $msgBody = 'Congratulations, We have added a new quiz, Please be the first to submit it.';
				  $msgBody .= 'Quiz Start Date: '.date('d-M-Y h:i A',strtotime($quizData->start_date));
				   $msgBody .=', Quiz End Date: '.date('d-M-Y h:i A',strtotime($quizData->end_date));
				   
				   $data['subject'] = $quizData->quiz_name.' is ready';
				   $data['messageBody'] = $msgBody;
				   
				$arr_deviceId = array();
				foreach($userData as $ukey=>$user){  
					if($user['device_id']!=null && $user['device_id']!='null')			
						$arr_deviceId[] = $user['device_id'];			  
				}
				//dd($arr_deviceId);
				$this->sendPushNotification($arr_deviceId,$data);
				
                }else{
                  $msg = "Quiz De-activated Successfully";
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
?>