<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRating extends Model
{
    protected $table = 'user_rating';
    protected $fillable = ['name','email','rating','comment','event_id','user_id'];
    protected $hidden = ['_token'];


    public function user()
    {
        return $this->belongsTo('App\User');
    }
        
}
