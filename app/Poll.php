<?php

namespace App;
use DB;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Poll extends Model
{
    protected $table = 'audience_polls';
	protected $fillable = [
		'poll_name',
		'status',
		'start_date',
		'end_date',
		
	];
	use SoftDeletes;
    protected $hidden = ['_token'];
	
	public function PollQuestions(){
        return $this->hasMany('App\PollQuestions','audience_poll_id');
    }


    
}
