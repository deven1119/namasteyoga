<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventRating extends Model
{
    protected $table = 'event_rating';
    protected $fillable = ['name','email','rating','comment','user_id','event_id'];
    protected $hidden = ['_token'];


    public function event()
    {
        return $this->belongsTo('App\Event');
    }    
    
}
