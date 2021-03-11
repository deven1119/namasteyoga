<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;

class Pages extends Model
{
  public function getAllPages(){
    return Pages::all()->sortBy("title");
  }

  public function getCmsBySlug($slug){
    return $page = DB::table('pages')
                   //->select('id','phone')
                   ->where('slug', '=', $slug)
                   ->get();
  }


}
