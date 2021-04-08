<?php

namespace App\Http\Controllers\v6;
//namespace App\Models;
use App\Http\Controllers\Controller;
//use Illuminate\Http\Request;
use App\Models\Aasana as aasanaModel;
use App\Models\AasanaCategory;
use App\Models\AasanaSubCategory;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Config;


class AasanController extends Controller
{   
   public function getSubCategoryList(Request $request)
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
                  $category_list=AasanaCategory::GetAasanCategory();
                  $storage_path=asset('/public/images/aasana');
                  
                  $category_data=[];       
                  foreach($category_list as $category)
                  {
                     $sub_category_data=[];
                     $sub_category_list=AasanaSubCategory::where('aasana_categories_id',$category->id)->where('status','1')->select('id','aasana_categories_id','subcategory_name','subcategory_description','subcategory_image')->get(); 
                     foreach($sub_category_list as $sub_category)
                     {         
                        $sub_category_array=
                        [
                           "id"=>$sub_category->id,
                           "aasana_categories_id"=>$sub_category->aasana_categories_id,
                           "subcategory_name"=>$sub_category->subcategory_name,
                           "subcategory_description"=>$sub_category->subcategory_description,
                           "sub_category_image_path"=>$storage_path.'/'.$sub_category->subcategory_image
                        ]; 
                        array_push($sub_category_data,$sub_category_array);                  
                     }            
                     $category_data[]=
                     [
                           "id"=>$category->id,
                           "category_name"=>$category->category_name,
                           "category_description"=>$category->category_description,
                           "category_image_path"=>$storage_path.'/'.$category->category_image,                                 
                           "sub_category_data"=>$sub_category_data,
                     ];
                  }            
                  if($category_data)
                  {       
                     $alldata=[
                        'status'=>$status['NP_STATUS_SUCCESS'],
                        'message'=>'Request processed successfully',
                        'data'=>$category_data       
         
                     ];
                     return response()->json($alldata); 
                  }
                  else
                  {
                     $data=[];
                     $alldata=[
                        'status'=>$status['NP_NO_RESULT'],
                        'message'=>'Category not found',
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
   public function getAasanaList(Request $request)
   {    
      $status=Config::get('app.status_codes');
      try
      {
               $rules = [
                  'aasana_sub_categories_id'    => 'required|numeric',
                  'page' => 'numeric',
              ];
              $validation = Validator::make($request->all(), $rules);
              if ($validation->fails()) {				 
                  $data=[
                     'status'=>$status['NP_INVALID_REQUEST'], 
                     'error'=>'Something went wrong', 
                  ];				  
                  return response()->json($data,403);
              }
              else
              {
                   $aasana_data=aasanaModel::where('aasana_sub_categories_id',$request->aasana_sub_categories_id)->where('status','1')->select('id','aasana_sub_categories_id','aasana_name','aasana_description','assana_tag','assana_video_id','assana_video_duration','assana_benifits','assana_instruction')->paginate(Config::get('app.aasana_record_per_page'));
                   $aasana_list=[];
                   foreach($aasana_data as $aasana)
                   {  
                       $aasana_list[]= [
                                            "id"=>$aasana->id,
                                             "aasana_sub_categories_id"=>$aasana->aasana_sub_categories_id,
                                             "aasana_name"=>$aasana->aasana_name,
                                             "aasana_description"=>$aasana->aasana_description,
                                             "assana_tag"=>$aasana->assana_tag,
                                             "assana_video_id"=>$aasana->assana_video_id,
                                             "assana_video_duration"=>$aasana->assana_video_duration,
                                             "assana_benifits"=>$aasana->assana_benifits, 
                                             "assana_instruction"=>$aasana->assana_instruction,    
                                          ];
                   }
                  /* $alldata=[
                     'StatusCode'=>$status['NP_STATUS_SUCCESS'],
                     'message'=>'Request processed successfully',
                     'data'=>$aasana_list       
                   
                               ];
                  return response()->json($alldata);  */
				  
				   if($aasana_list)
                  {       
                     $alldata=[
                        'status'=>$status['NP_STATUS_SUCCESS'],
                        'message'=>'Request processed successfully',
                        'data'=>$aasana_list       
         
                     ];
                     return response()->json($alldata); 
                  }
                  else
                  {
                     $data=[];
                     $alldata=[
                        'status'=>$status['NP_NO_RESULT'],
                        'message'=>'Aasana not found',
                        'data'=>$data      
         
                     ];
                     return response()->json($alldata); 

                  }

            }
  
      }
      catch (Exception $e)
      {
              return response()->json([
               'status'=>$status['NP_STATUS_NOT_KNOWN'],                                 
           ]);
      }
    


   }
}
