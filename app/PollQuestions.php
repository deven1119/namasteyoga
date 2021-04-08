<?php

namespace App;
use DB;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;

class PollQuestions extends Model
{
	protected $table = 'audience_poll_questions';
    protected $fillable = ['audience_poll_id','question'];
	
	public function poll()
    {
        return $this->belongsTo('App\Poll','id');
    } 
}
?>