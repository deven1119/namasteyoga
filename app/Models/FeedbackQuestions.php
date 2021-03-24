<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Config;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeedbackQuestions extends Model
{
    use SoftDeletes;    
    //protected $table ='aasana_categories';
    protected $fillable = ['question','updated_by'];
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
	public static function feedbackquestionsData()
	{
		$feedback_questions=FeedbackQuestions::where('status','1')->paginate(Config::get('app.feedback_questions_record_per_page'));
		return $feedback_questions;
	}

}
