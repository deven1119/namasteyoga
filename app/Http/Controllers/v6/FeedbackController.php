<?php

namespace App\Http\Controllers\v6;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\FeedbackQuestions;
use App\Models\FeedbackQuestionsOptions;
use App\Models\FeedbackResponses;
use App\Models\FeedbackResponsesRatings;
use Exception;
use Illuminate\Support\Facades\Validator;
use Config;
use ValidationException;
use DB;



class FeedbackController extends Controller
{
    public function getFeedbackQuestionList(Request $request)
    {
	  $status=Config::get('app.status_codes');
      try
      {     
               $rules = [           
                     'page' => 'numeric',
               ];
               $validation = Validator::make($request->all(), $rules);
               if ($validation->fails())
               {
                     $data=[
                        'StatusCode'=>$status['NP_STATUS_FAIL'], 
                        'error'=>'Something went wrong', 
                     ];
                     return response()->json($data,403);
               }
               else
               {
                  $feedbackquestion_list=FeedbackQuestions::feedbackquestionsData(); 
				  $feedback_questions_count=FeedbackQuestions::where('status','1')->count();
                  $feedback_questions=[];       
                  foreach($feedbackquestion_list as $feedbackquestion)
                  {                               
                     $feedback_questions[]=
                     [
                           "id"=>$feedbackquestion->id,
                           "question"=>$feedbackquestion->question,                                                            
                           "feedback_questions_options"=>FeedbackQuestionsOptions::where('feedback_questions_id',$feedbackquestion->id)->select('id','feedback_questions_id','options')->get()
                     ];
                  }  
				     $data['feedback_questions']=$feedback_questions;
                     $alldata=[
                        'StatusCode'=>$status['NP_STATUS_SUCCESS'],
                        'message'=>'Request processed successfully',
						'total_count'=>$feedback_questions_count,
                        'data'=>$data       
         
                     ];
                     return response()->json($alldata); 
                  
                  
               }
                  
      }     
      catch (Exception $e) {
               return response()->json([
                  'StatusCode'=>Config::get('app.status_codes.NP_STATUS_NOT_KNOWN'),
                  ],403);
                  }

    }
	public function submitFeedback(Request $request)
	{

		$status=Config::get('app.status_codes');
		try
		{
				$feedback_response=$request->all();
				$users_id=$feedback_response['users_id'];
				$rating=$feedback_response['rating'];
				$rating_description=$feedback_response['rating_description'];
				$questions=$feedback_response['questions'];
				 
				$vaildate_user_id=DB::table('feedback_responses')
				->join('feedback_responses_ratings', 'feedback_responses_ratings.users_id', '=', 'feedback_responses.users_id')	
				->where('feedback_responses.users_id', $users_id)->count()>0;
				
				//$users_id=$vaildate_user_id->users_id;
				
				if($vaildate_user_id)
				{
					$alldata=[
						//'StatusCode'=>$status['NP_STATUS_FAIL'],
						'message'=>'User Id already exists',						        
						];
						return response()->json($alldata); 
				}
				else
				{
					foreach($questions as $question)
					{
					 
						$feedback_response=
						[
								'users_id'=>trim($users_id),
								'feedback_questions_id'=>trim($question['feedback_questions_id']),
								'feedback_questions_options_id'=>trim($question['feedback_questions_options_id']),                                   
						];
						 $feedback_response_result=FeedbackResponses::create($feedback_response);
						
					}
					$feedback_response_rating=
					[
						'users_id'=>trim($users_id),
						'rating'=>trim($rating),
						'rating_description'=>trim($rating_description),                        
					];
					$feedback_response_rating_result=FeedbackResponsesRatings::create($feedback_response_rating);
					
					if($feedback_response_result && $feedback_response_rating_result)
					{
						$alldata=[
						'StatusCode'=>$status['NP_STATUS_SUCCESS'],
						'message'=>'Request processed successfully',						        
						];
						return response()->json($alldata); 
					}
					else
					{
						$alldata=[
						'StatusCode'=>$status['NP_STATUS_FAIL'],
						'message'=>'Something went wrong',						        
						];
						return response()->json($alldata); 
					} 
				}

		}
		catch(Exception $e)
		{
				return response()->json([
                  'StatusCode'=>Config::get('app.status_codes.NP_STATUS_NOT_KNOWN'),
                  ],403);
				
		}


	}
}
