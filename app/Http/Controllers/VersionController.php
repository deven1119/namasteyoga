<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Version;
class VersionController extends Controller
{
    
    /**
     * Show the form for get user lsit.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLatestVersion(Request $request){
        try{
            
            $status = 0;
            $message = "";                      
            $type = $request->type;            
            $latestVersion = Version::where('type',  $type)->orderBy('id', 'Desc')->first();                        
            if($latestVersion->count() >0){
                $status = 1;
                return response()->json(['status'=>$status,'message'=>$message,'data'=>$latestVersion]); 
            }else{
                return response()->json(['status'=>$status,'message'=>"unable to get version",'data'=>[]]); 
            }                        
        }catch(Exception $e){
            return response()->json(['status'=>$status,'message'=>'version exception','data'=>json_decode('{}')]); 
        }               
    }

    /**
     * Show the form for get user lsit.
     *
     * @return \Illuminate\Http\Response
     */
    public function setLatestVersion(Request $request){
        try{
            $status = 0;
            $message = "";                      
            $version = $request->version;      
            $type = $request->type;
            $description = $request->description;
            
            $validator = Validator::make($request->all(), [
                'type' => 'required|string|max:1',
                'version' => 'required|string|max:10|unique:version',                
            ]);
            
            //$validator->errors()
            if($validator->fails()){
              return response()->json(["status"=>$status,"message"=>"invalid Data.","data"=>json_decode("{}")]);
            }

            $version = Version::create([
                'version' => $request->get('version'),
                'type' => $request->get('type'),
                'description' => $request->get('description')                
            ]);            
            $status = 1;
            return response()->json(['status'=>$status,'message'=>$message,'data'=>$version]); 
        }catch(Exception $e){
            return response()->json(['status'=>$status,'message'=>'unable to set version','data'=>json_decode('{}')]); 
        }
               
    }


    /**
     * Show the form for get user lsit.
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteVersion(Request $request){
        try{
            $status = 0;
            $message = "";                      
            if(isset($request->id) && $request->id > 0){
                $id = $request->id;                        
                $delete = Version::where('id',  $id)->delete();                       
                if($delete){
                    $status = 1;
                    return response()->json(['status'=>$status,'message'=>$message,'data'=>[]]); 
                }else{
                    $message = "Not deleted";  
                    return response()->json(['status'=>$status,'message'=>$message,'data'=>[]]); 
                }
            }else{
                return response()->json(['status'=>$status,'message'=>"invalid id",'data'=>[]]); 
            }
                                    
        }catch(Exception $e){
            return response()->json(['status'=>$status,'message'=>'delete exception','data'=>json_decode('{}')]); 
        }               
    }
}
