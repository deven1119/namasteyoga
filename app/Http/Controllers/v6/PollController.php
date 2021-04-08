<?php

    namespace App\Http\Controllers\v6;
   
     use App\Poll;
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

    class PollController extends Controller
    {
         public function getPoll(Request $request){
			try{
                
                $status = Config::get('app.status_codes');
                $message = "Request processed successfully";      
                $cond[] = ['status',1];   
				
				if(Poll::where($cond)->count()==0){
					return response()->json(['status'=>$status['NP_NO_RESULT'],'message'=>'No result found','data'=>array()]);
				}
				$polls = Poll::where($cond)->get();
				
				
				
				$pollsData = [];                    
				foreach($polls as $k=>$v){
					$questions = DB::table('audience_poll_questions as q')
						->select('q.id as que_id','q.question')
						->where('q.audience_poll_id',$v->id)
						->get();
					$questionData = [];
					
					foreach($questions as $qkey=>$question){
						$ansData = [];
						$questionData[$qkey]['que_id'] = $question->que_id;
						$questionData[$qkey]['question'] = $question->question;
						
						$options = DB::table('audience_poll_question_options as ans')
						->where('ans.audience_poll_question_id',$question->que_id)
						->get();
						
						foreach($options as $okey=>$option){
							$ansData[$okey]['ans_id'] = $option->id;
							$ansData[$okey]['ans_text'] = $option->options;
						}
						$questionData[$qkey]['ans_data'] = $ansData;
					}
					$pollsData[$k]['poll_id'] = $v->id;
					$pollsData[$k]['poll_name'] = $v->poll_name;
					$pollsData[$k]['question_data'] = $questionData;
				}
				//$status = 1;                    
				return response()->json([  
				"status"=>$status['NP_STATUS_SUCCESS'],
				"message"=>$message,				
				'data'=>$pollsData
				]);
               
            }catch(Exception $e){   
                $message = "Data exception";
                return response()->json(['status'=>$status['NP_STATUS_NOT_KNOWN'],'message'=>$message,'data'=>array()]);
            }
		 }
		 public function validatePoll(Request $request){
			 
			 try{
				 $user  = JWTAuth::user();
				 
				 $status=Config::get('app.status_codes');
				 $poll_valid = false;
				 
				$validator = Validator::make($request->all(), [                 
                        'poll_id' => 'required'
				]);
				
				if($validator->fails()){
						Log::debug(['Validation failed',$request->all()]);
						
						return response()->json(['status'=>$status['NP_INVALID_REQUEST'],
						'message'=>'invalid Request','data'=>json_decode("{}")]);
				}  

				 $user_id = $user->id;
				 $poll_id = $request->poll_id;
				 
				 $poll = Poll::find($poll_id);
		
					if(!$poll){
						return response()->json(['status'=>$status['NP_NO_RESULT'],'message'=>'No result found','data'=>array()]);
					}
					if($poll->status==0){
						return response()->json(['status'=>$status['NP_NO_RESULT'],'message'=>'No result found','data'=>array()]);
					}
				 
				 //check if user already given this poll
				 $count = DB::table('audience_poll_responses')->where(['user_id'=>$user_id,'audience_poll_id'=>$poll_id])->count();
				 //echo $count;die;
				 if($count==0){
					 $poll_valid = true;
					 $message = "Request processed successfully"; 
					 $s = $status['NP_STATUS_SUCCESS'];
				 }else{
					 $poll_valid = false;
					 $message = 'You have already attended the poll'; 
					 $s = $status['NP_DUPLICATE_REQUEST'];
					 
				 }
				
                   
				return response()->json([
				'status'=>$s,  
				"message"=> $message,				
				'poll_valid'=>$poll_valid
				]); 
               
            }catch(Exception $e){   
                $message = "Data exception";
                return response()->json(['status'=>$status['NP_STATUS_NOT_KNOWN'],'message'=>$message,'data'=>array()]);
            }
		 } 
		 public function submitPoll(Request $request){
			try{
				$user  = JWTAuth::user();
				
				$status = Config::get('app.status_codes');
				//print_r($request->data['poll_id']);die;
                $validator = Validator::make($request->all(), [                 
                        'data.poll_id' => 'required'
				]);
				
				if($validator->fails()){
						Log::debug(['Validation failed',$request->all()]);
						return response()->json(['status'=>$status['NP_INVALID_REQUEST'],'message'=>'invalid Request','data'=>json_decode("{}")]);
				} 
				
				$user_id = $user->id;
				$poll_id = $request->data['poll_id'];
				
				$poll = Poll::find($poll_id);
				//check poll exist or poll is activated or not

					if(!$poll){
						return response()->json(['status'=>$status['NP_NO_RESULT'],'message'=>'No result found','data'=>array()]);
					}
					if($poll->status==0){
						return response()->json(['status'=>$status['NP_NO_RESULT'],'message'=>'No result found','data'=>array()]);
					}
				
				//duplicate request
				$checkAlreayAttenedPoll =  DB::table('audience_poll_responses')->where(['user_id'=>$user_id,'audience_poll_id'=>$poll_id])->count();
				
				 if($checkAlreayAttenedPoll>0){
					 Log::debug(['Validation failed',$request->all()]);
					return response()->json(['status'=>$status['NP_DUPLICATE_REQUEST'],'message'=>'You have already attended the poll','data'=>json_decode("{}")]);
				 }
				 
				 //Check if the no of questions attended by user is qualt to the questions are in the poll.
				 //get count of questions in poll
				 $totalQuestionInPoll = DB::table('audience_poll_questions')->where('audience_poll_id',$poll_id)->count();
				 
				 //count the questions submited by user
				 $questionSubmitedByUser = count($request->data['question_data']);
				 
				 if($totalQuestionInPoll!=$questionSubmitedByUser){
					 return response()->json(['status'=>$status['NP_DUPLICATE_REQUEST'],'message'=>'Please attempt all the questions','data'=>json_decode("{}")]);
				 }
				 
				 $data['audience_poll_id'] = $poll_id;
				 $data['user_id'] = $user_id;
				 //print_r($request->data['question_data']);die;
				  foreach($request->data['question_data'] as $value){
					  $data['audience_poll_question_id'] = $value['que_id'];
					  $data['audience_poll_option_id'] = $value['ans_id'];
					  
					   $insert = DB::table('audience_poll_responses')->insert($data);
				  }
				 
				 if($insert==1){
					 return response()->json(['status'=>$status['NP_STATUS_SUCCESS'],'message'=>'Poll has been submitted successfully','data'=>json_decode("{}")]);
				 }else{
					 return response()->json(['status'=>$status['NP_DB_ERROR'],'message'=>'Something went wrong','data'=>json_decode("{}")]);
				 }
				
			
			}catch(Exception $e){   
                $message = "Data exception";
                return response()->json(['status'=>$status['NP_STATUS_NOT_KNOWN'],'message'=>$message,'data'=>array()]);
            }
		 }
    }