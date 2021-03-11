<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Audit;

class AuditController extends Controller
{
    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{            
            $audit = Audit::paginate(config('app.paging'));                
            return view('audits.index', ['audit' => $audit]);
        }catch(Exception $e){
            abort(500, $e->message());
        }
    }

    /**
         * event list ajax data tables
         */

        public function auditIndexAjax(Request $request){
            
            $draw = ($request->data["draw"]) ? ($request->data["draw"]) : "1";
            $response = [
              "recordsTotal" => "",
              "recordsFiltered" => "",
              "data" => "",
              "success" => 0,
              "msg" => ""
            ];
            try {
                
                $start = ($request->start) ? $request->start : 0;
                $end = ($request->length) ? $request->length : 10;
                $search = ($request->search['value']) ? $request->search['value'] : '';
                //echo 'ddd';die;           

                $audits = Audit::where('id', '>=', 1);
                                
                if ($request->search['value'] != "") {            
                  $audits = $audits->where('email','LIKE',"%".$search."%")
                  ->orWhere('username','LIKE',"%".$search."%")                  
                  ->orWhere('source','LIKE',"%".$search."%");
                } 
      
                $total = $audits->count();                 
                if($end==-1){
                  $audits = $audits->get();
                }else{
                  $audits = $audits->skip($start)->take($end)->get();
                }              
              
                $response["recordsFiltered"] = $total;
                $response["recordsTotal"] = $total;
                //response["draw"] = draw;
                $response["success"] = 1;
                $response["data"] = $audits;
                
              } catch (Exception $e) {    
      
              }
            
      
            return response($response);
        }          
        //use NotifyMail;
}
