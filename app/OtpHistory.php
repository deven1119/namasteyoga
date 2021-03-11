<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OtpHistory extends Model
{
    protected $table = 'otp_history';
    protected $fillable = ['type','user_id'];
    //protected $hidden = ['_token'];
  
    // public function user(){
    //   return $this->belongsTo('App\User');
    // }    
}
