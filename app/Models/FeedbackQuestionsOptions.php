<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Config;
use Illuminate\Database\Eloquent\SoftDeletes;
class FeedbackQuestionsOptions extends Model
{
    use SoftDeletes;    
    protected $fillable = ['feedback_questions_id','options'];
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
}
