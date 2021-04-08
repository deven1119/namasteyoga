<?php

namespace App\Http\Controllers\v6;



use Illuminate\Http\Request;

//use App\Http\Controllers\Controller;
//use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\DB;

use App\Models\AayushCategory;
use App\Models\AayushProducts;

use App\Common\Utility;

use Config;

//use App\User;
//use Carbon;
//use App\Mail\WelcomeMail;
//use Illuminate\Support\Facades\Mail;

class Aayushmerchandise extends Controller
{
	
	public function getAllCategories(Request $request) {
		
		Utility::stripXSS();
		DB::beginTransaction();
		
		try {
			if(!$this->verifyChecksum($request)) {
				return response()->json(["status"=> Config::get('app.status_codes.NP_INVALID_REQUEST'), "message"=>'Checksum not verified', "data"=>json_decode('{}')]);
			}
            
            $where[] = ['status', 1];
			$categories_arr = AayushCategory::where($where)->get();
			
			return response()->json(["status"=> Config::get('app.status_codes.NP_STATUS_SUCCESS'), "message"=>"Request processed successfully.", "data"=> $categories_arr]);
			
		} catch(Exception $e){
			return response()->json(['status'=>Config::get('app.status_codes.NP_STATUS_NOT_KNOWN'), 'message'=>'Something went wrong. Please try again.', 'data'=>json_decode("{}")]);
		}
	}
	
	
	public function getProductByCategory(Request $request) {
		
		Utility::stripXSS();
		DB::beginTransaction();
		
		try {
			if(!$this->verifyChecksum($request)) {
				return response()->json(["status"=> Config::get('app.status_codes.NP_INVALID_REQUEST'), "message"=>'Checksum not verified', "data"=>json_decode('{}')]);
			}
            
            $category_id = $request->category_id;
            
            if($category_id) {
                $where = array('ayush_categories_id' => $category_id, 'status' => 1);
                $categories_arr = AayushProducts::where($where)
                                                    //->rightjoin('ayush_product_images', 'ayush_products.id', '=', 'ayush_product_images.ayush_products_id')
                                                    ->orderBy('product_name','asc')
                                                    ->paginate(Config::get('app.record_per_page'));
                
                $data_arr = array();
                $cat_arr = $categories_arr->items();
                foreach ($cat_arr as $value) {
                    
                    $value['images'] = DB::table('ayush_product_images')->where(array('ayush_products_id' => $value['id']))->get();
                    
                    $data_arr[] = $value;
                }
                
                return response()->json([
                        "status"=> Config::get('app.status_codes.NP_STATUS_SUCCESS'), 
                        "message"=> "Request processed successfully.",
                        
                        'total_record'=> $categories_arr->total(),
                        'last_page'=> $categories_arr->lastPage(),
                        'current_page'=> $categories_arr->currentPage(),
                        
                        "data"=> $data_arr//$categories_arr->items()
                    ]);
            } else {
                return response()->json(["status"=> Config::get('app.status_codes.NP_STATUS_FAIL'), "message"=> "Category id missing in request.", 'data'=> json_decode("{}")]);
            }
			
		} catch(Exception $e){
			return response()->json(['status'=>Config::get('app.status_codes.NP_STATUS_NOT_KNOWN'), 'message'=>'Something went wrong. Please try again.', 'data'=>json_decode("{}")]);
		}
	}
	
	
	public function clearData(){
		DB::table('duplicate_request')->delete();
		DB::table('otp_history')->delete();
	}

}