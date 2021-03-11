<?php

namespace App;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';
    protected $fillable = ['name'];
    protected $hidden = ['_token'];


    public function user()
    {
        return $this->hasOne('App\User');
    }

    public function getSelect($is_select=false){
        $select = Role::orderBy('created_at','DESC');
        if($is_select){
            return $select;
        }
        return $select->get();
    }
}
