<?php

namespace App\Http\Controllers\v6;
//namespace App\Models;
use App\Http\Controllers\Controller;
//use Illuminate\Http\Request;
use App\Models\SocialMedia as SocialMediaModel;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Config;


class SocialMedia extends Controller
{   
   public function getAllSocialLinks(Request $request)
   {
    $status=Config::get('app.status_codes');
    try
    {     
             $rules = [           
                   'page' => 'numeric',
             ];
             $validation = Validator::make($request->all(), $rules);
             if ($validation->fails())
             {
                   $data=[
                      'status'=>$status['NP_INVALID_REQUEST'], 
                      'error'=>'Something went wrong', 
                   ];
                   return response()->json($data,403);
             }
             else
             {
                $social_media_list=SocialMediaModel::GetSocialMediaData();
                $storage_path=asset('/public/images/social_media');
                
                $social_links_data=[];       
                foreach($social_media_list as $social_media)
                {
                              
                   $social_links_data[]=
                   [
                         "id"=>$social_media->id,
                         "social_media_name"=>$social_media->social_media_name,
                         "social_media_link"=>$social_media->social_media_name,
                         "social_media_image_path"=>$storage_path,                                 
                        
                   ];
                }            
                if($social_links_data)
                {       
                   $alldata=[
                      'status'=>$status['NP_STATUS_SUCCESS'],
                      'message'=>'Request processed successfully',
                      'data'=>$social_links_data       
       
                   ];
                   return response()->json($alldata); 
                }
                else
                {
                   $data=[];
                   $alldata=[
                      'status'=>$status['NP_NO_RESULT'],
                      'message'=>'Social media links not found',
                      'data'=>$data      
       
                   ];
                   return response()->json($alldata); 

                } 
                
                
             }
                
    }     
    catch (Exception $e) {
             return response()->json([
                'status'=>Config::get('app.status_codes.NP_STATUS_NOT_KNOWN'),
                ],403);
                }
    }

}
