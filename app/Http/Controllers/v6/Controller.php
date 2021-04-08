<?php

namespace App\Http\Controllers\v6;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use App\OtpHistory;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
	public function encdesc($stringVal,$type='encrypt'){

		$stringVal = str_replace("__","/",$stringVal);
		if($type=='encrypt'){
			return openssl_encrypt($stringVal,"AES-128-ECB",config('app.SECRET_SALT'));
		} else {
			return openssl_decrypt($stringVal,"AES-128-ECB",config('app.SECRET_SALT'));
        }
	}

	public function verifyChecksum($request){    
		return true;

		$checksum = "";
		$json = json_encode($request->all());
		$requestJson = md5($json); 
		$checksum = $request->header("checksum");

		//echo $requestJson.'  '.$checksum; die;
		if($requestJson != $checksum){
			return false;
		}
		return true;
	}


	public function string_replace($repaceFrom,$replaceTo,$string){
		return str_replace($repaceFrom,$replaceTo,$string);
	}


    public function checkOtpHistory($type,$user_id){
        try {
            $result = OtpHistory::where([['user_id',$user_id],['type',$type]])->where(DB::raw('DATEDIFF(NOW(),created_at)') , '<', 1);
        //SELECT COUNT(*) AS count FROM otp_history WHERE user_id = 1 AND DATEDIFF(NOW(),created_at) < 1      
            
            if($result->count() >= 3){
                return false;
            } else {
                return true;
            }
            
        } catch(Exception $e){
            return true;
        }

    }
}