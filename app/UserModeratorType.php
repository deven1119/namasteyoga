<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;
class UserModeratorType extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $fillable = [
        'user_id',
        'moderator_id'
    ];

}

?>