<?php

namespace App;
use DB;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = 'events';
    protected $fillable = ['event_name',
    'user_id',
    'city_id',
    'state_id',
    'country_id',
    'contact_person',
    'contact_no',
    'address',
    'email',
    'sitting_capacity',
    'zip',
    'lat',
    'lng',
    'nearest',
    'nearest_distance',
    'mode',
    'start_time',
    'end_time'
];
    protected $hidden = ['_token'];


    public static function validate($request,$id=null)
    {
        $rule = 'required|unique:event_name|max:255';
        if($id>0){
             $rule = 'required|unique:event_name|max:255,name,'.$id.',id';
        }
        return Validator::make($request->all(), ['name' => $rule]);
    }

    public function getSelect($is_select=false){
        $select = Event::orderBy('created_at','DESC');
        if($is_select){
            return $select;
        }
        return $select->get();
    }
    
    public function user(){
        return $this->belongsTo('App\User');
    }

    public function city(){
        return $this->belongsTo('App\City');
    }
    public function state(){
        return $this->belongsTo('App\State');
    }
    public function country(){
        return $this->belongsTo('App\Country');
    }
    
    public function EventRating(){
        return $this->hasMany('App\EventRating');
    }

    public function saveData($request,$data){
        // $data->name = $request->name;
        // $data->parent_id = $request->parent_id;
        // $data->banner = $request->banner;
        // $data->description = $request->description;
        // $data->image = $request->image;
        // $data->image = $request->image;
        // $data->status = $request->status;
        $data->save();
    }
}
