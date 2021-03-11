<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class duplicateRequest extends Model
{
    protected $table = 'duplicate_request';
    protected $fillable = ['md5val'];
    //protected $hidden = ['_token'];
  
    // public function user(){
    //   return $this->belongsTo('App\User');
    // } 
}
