<?php 
namespace App\Http\Controllers\Traits; 
use App\Audit;
trait addAuditTrail { 
    protected function addAuditTrailData($data) {     
        try{
            $obj = new Audit();
            $obj->ip = $data->ip;
            $obj->source = $data->source;
            $obj->username = $data->username;
            $obj->session = $data->session;
            $obj->referer = $data->referer;
            $obj->process_id = $data->process_id;
            $obj->url = $data->url;
            $obj->user_agent = $data->user_agent;
            $obj->country = $data->country;    
            $obj->save();
            return true;
          }catch(Exception $e){
            return false;
          }  
    } 
}