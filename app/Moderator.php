<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;
class Moderator extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'moderator', 
        'description', 
        'status'
    ];

}

?>