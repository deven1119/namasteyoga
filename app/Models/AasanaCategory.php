<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Config;
use Illuminate\Database\Eloquent\SoftDeletes;

class AasanaCategory extends Model
{
    use SoftDeletes;    
    //protected $table ='aasana_categories';
    protected $fillable = ['category_name','category_description','status','category_image','updated_by'];
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public static function GetAasanCategory()
    {
        $aasancategory=AasanaCategory::where('status','1')->paginate(Config::get('app.category_record_per_page'));
        return $aasancategory;
        
    }
}
