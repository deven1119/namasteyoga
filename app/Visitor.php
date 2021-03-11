<?php

namespace App;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    protected $table = 'visitor';
    protected $fillable = ['ip','hits'];
    protected $hidden = ['_token'];

    public function getVisitorCount(){
        return Visitor::sum('hits');        
        
    }
}
