<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    protected $table = 'audit';
    protected $fillable = ['ip','source','username','session','referer','process_id','url','user_agent','country'];
    //protected $hidden = ['_token'];
}
