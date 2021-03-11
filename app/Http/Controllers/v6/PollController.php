<?php

    namespace App\Http\Controllers\v6;
   
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

    class PollController extends Controller
    {
         public function getPoll(Request $request){
			return '{
   "statusCode":"NP001",
   "description":"Request processed successfully",
   "data":[
      {
         "poll_cat_id":1,
         "poll_cat_name":"Yog Assana",
         "question_data":[
            {
               "que_id":1,
               "que_name":"What would be the most important factor for you in choosing Yoga?",
               "ans_data":[
                  {
                     "ans_id":1,
                     "ans_text":"A Yoga that helps me get results"
                  },
                  {
                     "ans_id":2,
                     "ans_text":"Time/Punctuality"
                  },
                  {
                     "ans_id":3,
                     "ans_text":"Convinent"
                  },
                  {
                     "ans_id":4,
                     "ans_text":"Fee Package"
                  }
               ]
            },
            {
               "que_id":2,
               "que_name":"What motivate you to join the Yoga?",
               "ans_data":[
                  {
                     "ans_id":1,
                     "ans_text":"Look good"
                  },
                  {
                     "ans_id":2,
                     "ans_text":"To improve health"
                  },
                  {
                     "ans_id":3,
                     "ans_text":"Just want to Feel relax"
                  },
                  {
                     "ans_id":4,
                     "ans_text":"Get in shape"
                  }
               ]
            },
			{
               "que_id":3,
               "que_name":"What are the main reasons that stop you to Join Yoga?",
               "ans_data":[
                  {
                     "ans_id":1,
                     "ans_text":"Time Pressure"
                  },
                  {
                     "ans_id":2,
                     "ans_text":"Financial issue"
                  },
                  {
                     "ans_id":3,
                     "ans_text":"Prefer gym"
                  },
                  {
                     "ans_id":4,
                     "ans_text":"Exercising is boring"
                  }
               ]
            }
         ]
      },
	  {
         "poll_cat_id":2,
         "poll_cat_name":"Poll 2",
         "question_data":[
            {
               "que_id":1,
               "que_name":"Hello, how good are you?",
               "ans_data":[
                  {
                     "ans_id":1,
                     "ans_text":"Poor"
                  },
                  {
                     "ans_id":2,
                     "ans_text":"good"
                  },
                  {
                     "ans_id":3,
                     "ans_text":"well"
                  },
                  {
                     "ans_id":4,
                     "ans_text":"V BAD"
                  }
               ]
            },
            {
               "que_id":2,
               "que_name":"Hello, how good are you?",
               "ans_data":[
                  {
                     "ans_id":1,
                     "ans_text":"Poor"
                  },
                  {
                     "ans_id":2,
                     "ans_text":"good"
                  },
                  {
                     "ans_id":3,
                     "ans_text":"well"
                  },
                  {
                     "ans_id":4,
                     "ans_text":"V BAD"
                  }
               ]
            }
         ]
      },
	  {
         "poll_cat_id":3,
         "poll_cat_name":"Poll 3",
         "question_data":[
            {
               "que_id":1,
               "que_name":"Hello, how good are you?",
               "ans_data":[
                  {
                     "ans_id":1,
                     "ans_text":"Poor"
                  },
                  {
                     "ans_id":2,
                     "ans_text":"good"
                  },
                  {
                     "ans_id":3,
                     "ans_text":"well"
                  },
                  {
                     "ans_id":4,
                     "ans_text":"V BAD"
                  }
               ]
            },
            {
               "que_id":2,
               "que_name":"Hello, how good are you?",
               "ans_data":[
                  {
                     "ans_id":1,
                     "ans_text":"Poor"
                  },
                  {
                     "ans_id":2,
                     "ans_text":"good"
                  },
                  {
                     "ans_id":3,
                     "ans_text":"well"
                  },
                  {
                     "ans_id":4,
                     "ans_text":"V BAD"
                  }
               ]
            }
         ]
      },
	  {
         "poll_cat_id":4,
         "poll_cat_name":"Poll 2",
         "question_data":[
            {
               "que_id":1,
               "que_name":"Hello, how good are you?",
               "ans_data":[
                  {
                     "ans_id":1,
                     "ans_text":"Poor"
                  },
                  {
                     "ans_id":2,
                     "ans_text":"good"
                  },
                  {
                     "ans_id":3,
                     "ans_text":"well"
                  },
                  {
                     "ans_id":4,
                     "ans_text":"V BAD"
                  }
               ]
            },
            {
               "que_id":2,
               "que_name":"Hello, how good are you?",
               "ans_data":[
                  {
                     "ans_id":1,
                     "ans_text":"Poor"
                  },
                  {
                     "ans_id":2,
                     "ans_text":"good"
                  },
                  {
                     "ans_id":3,
                     "ans_text":"well"
                  },
                  {
                     "ans_id":4,
                     "ans_text":"V BAD"
                  }
               ]
            }
         ]
      }
   ]
}
';
		 }
    }