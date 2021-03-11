<?php

namespace App;

use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;
use App\Country;
use App\NotifyMe;
use App\State;
//use App\City;


class NotifyMe extends Model
{
    public function country(){
        return $this->belongsTo('App\Country');
    }
    public function state(){
        return $this->belongsTo('App\State');
    }
    public function city(){
        return $this->belongsTo('App\City');
    }

    protected $table = 'notify_me';
    protected $fillable = ['name','city_id','country_id','state_id','email','phone','type'];
    protected $hidden = ['_token'];
    
    public function getNotifiedDataByType($data){
        
        $notifyList = NotifyMe::where([
            ['notified',0],
            ['type',$data->type],
            ['city_id',$data->city_id],
            ['state_id',$data->state_id],
            ['country_id',$data->country_id],
            ])->get();   
                  
        return $notifyList;
    }
    
}
