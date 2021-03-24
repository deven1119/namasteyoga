<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Config;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeedbackResponses extends Model
{
    use SoftDeletes;    
    protected $fillable = ['users_id','feedback_questions_id','feedback_questions_options_id'];
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
}
