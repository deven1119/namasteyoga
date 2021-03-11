<?php

namespace App\Http\Controllers\v4;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Version;
use App\Common\Utility;
class VersionController extends Controller
{

    /**
     * Show the form for get user lsit.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLatestVersion(Request $request){
        try{
            if(!$this->verifyChecksum($request)){
                return response()->json(["status"=>0,
               "message"=>'checksum not verified',
               "data"=>json_decode('{}')]);
            }


            $status = 0;
            $message = "";
            $type = $request->type;
            $latestVersion = Version::where('type',  $type)->orderBy('id', 'Desc')->first();
            if(!empty($latestVersion) && $latestVersion->count() >0){
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
            if(!$this->verifyChecksum($request)){
                return response()->json(["status"=>0,
               "message"=>'checksum not verified',
               "data"=>json_decode('{}')]);
            }
            Utility::stripXSS();
            $version = $request->version;
            $type = $request->type;
            $description = $request->description;

            $validator = Validator::make($request->all(), [
                'type' => 'required|string',
                'version' => 'required|string|unique:version',
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
