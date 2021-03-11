<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OldpasswordHistory extends Model
{
    protected $table = 'oldpassword_history';
    protected $fillable = ['password','user_id'];
    //protected $hidden = ['_token'];
  
    public function user(){
      return $this->belongsTo('App\User');
    }      
}
