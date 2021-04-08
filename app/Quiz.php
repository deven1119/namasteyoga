<?php

namespace App;
use DB;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quiz extends Model
{
   // protected $table = 'qui';
    protected $fillable = [
	'quiz_name',
	'quiz_description',
    'status',
    'quiz_time',
    'valid_for',
	'start_date',
    'end_date',
    
];
	use SoftDeletes;
    protected $hidden = ['_token'];

}
