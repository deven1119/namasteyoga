<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use Image;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Models\AasanaCategory as AasanaCategoryModel;
use App\Models\AasanaSubCategory;
use App\Models\Aasana as AasanaModel;
use Auth;


class Aasana extends Controller
{
    #bind AasanaCategoryModel
	protected $sub_category;

    public function __construct()
    {
         //$this->middleware('App\Http\Middleware\ModeratorType');
    }
   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function AddAasana()
    {
        $categorylist=AasanaCategoryModel::get();
        return view('aasanas/add_aasana',['categorylist'=>$categorylist]);
    }
    public function getSubCategory(Request $request)
    {
        
        $data['subcategory'] = AasanaSubCategory::where("aasana_categories_id",$request->aasana_categories_id)
                    ->get(["subcategory_name","id"]);
        // echo "<pre>";
        // print_r($data['subcategory']);
        // die;
        return response()->json($data);
    }
    public function SaveAasana(Request $request)
    {
        $user  = Auth::user()->id;
               $rules = [  
                    'aasana_categories_id'=>'required', 
                    'aasana_sub_categories_id' =>'required', 
                    'aasana_name' => 'required',
                    'aasana_description' => 'required',
                    //'aasana_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:1500',
                    'assana_tag'=>'required', 
                    'assana_video_id' =>'required', 
                    'assana_video_duration'=>'required',
                    'assana_benifits' => 'required',
                    'assana_instruction' => 'required',
            ];
            
            $validation = Validator::make($request->all(), $rules);
            if ($validation->fails())
            {
            
                    $errors=$validation->errors();
                    return back()->withErrors($errors);
                
            }
            else
            {
                
                // $image = $request->file('aasana_image');
                // $input['imagename'] = time().'.'.$image->extension();             
                // $destinationPath = public_path('images\aasana');
                // $img = Image::make($image->path());
                // $img->resize(150, 150, function ($constraint) {
                //     $constraint->aspectRatio();
                // })->save($destinationPath.'/'.$input['imagename']);
           
                // $destinationPath = public_path('/images');
                // $image->move($destinationPath, $input['imagename']);

                // $aasana_image=$input['imagename'];               
                
                $aasana_data=
                [
                'aasana_categories_id'=>trim($request->aasana_categories_id),
                'aasana_sub_categories_id'=>ucfirst(trim($request->aasana_sub_categories_id)),
                'aasana_name'=>ucfirst(trim($request->aasana_name)),
                'aasana_description'=>trim($request->aasana_description),
                //'aasana_image'=>trim($aasana_image),
                'assana_tag'=>trim($request->assana_tag),
                'assana_video_id'=>trim($request->assana_video_id),
                'assana_video_duration'=>trim($request->assana_video_duration),
                'assana_benifits'=>trim($request->assana_benifits),
                'assana_instruction'=>trim($request->assana_instruction),
                'status'=>'1',
                'updated_by'=> $user,
                ];
                // echo "<pre>";
                // print_r($aasana_data);
                // die;

                $result=AasanaModel::create($aasana_data);
                if($result)
                {
                    return back()->with('success', 'Aasana Added successfully');
                }
                if($result)
                {
                    return back()->with('success', 'Something Went Wrong');
                }


            }

    }
    public function ListAasana()
    {
        return view('aasanas/aasana_index');
    }
    public function AasanaIndexAjax(Request $request)
    {       
       
        //$draw = ($request->data["draw"]) ? ($request->data["draw"]) : "1";
        
        $response = [
          "recordsTotal" => "",
          "recordsFiltered" => "",
          "data" => "",
          "success" => 0,
          "msg" => ""
        ];
        try
         {
                      
            $start = ($request->start) ? $request->start : 0;
            $end = ($request->length) ? $request->length : 10;
            $search = ($request->search['value']) ? $request->search['value'] : '';
           
            //$cond[] = ['status','<>',''];
             $aasana = AasanaModel::orderBy('id','desc');      
            
             if ($request->search['value'] != "") {            
              $aasana = $aasana->where('aasana_name','LIKE',"%".$search."%");                                      
            }             
            $total = $aasana->count();
            if($end==-1){
              $aasana = $aasana->get();
            }else{
              $aasana = $aasana->skip($start)->take($end)->get();
            }  
            if($aasana->count() > 0)
            {
                 

                $i = 1;
               $linkUrl='https://www.youtube.com/watch?v=';
                foreach($aasana as $k=>$v)
                { 
                    
                     $category_name =AasanaCategoryModel::where('id',$aasana[$k]->aasana_categories_id)->select('category_name')->first();
                     $subcategory_name =AasanaSubCategory::where('id',$aasana[$k]->aasana_sub_categories_id)->select('subcategory_name')->first();

                      $aasana[$k]->sr_no = $i; 
                      $aasana[$k]->id = $aasana[$k]->id; 
                      $aasana[$k]->category_name =$category_name->category_name;
                      $aasana[$k]->subcategory_name =$subcategory_name->subcategory_name;  
                      $aasana[$k]->aasana_name = $aasana[$k]->aasana_name;  
                      $aasana[$k]->aasana_description = $aasana[$k]->aasana_description; 
                      $aasana[$k]->assana_tag = $aasana[$k]->assana_tag;  
                      $aasana[$k]->assana_video_id = $linkUrl.''.$aasana[$k]->assana_video_id; 
                      $aasana[$k]->assana_video_duration = $aasana[$k]->assana_video_duration; 
                      $aasana[$k]->assana_benifits = $aasana[$k]->assana_benifits;  
                      $aasana[$k]->assana_instruction = $aasana[$k]->assana_instruction;                       
                      //$aasana[$k]->subcategory_image = $imageUrl.'/'.$subcategory[$k]->subcategory_image;                        
                      $aasana[$k]->status = $aasana[$k]->status;                       
					  $i++;
               
                }     
            }
            $response["recordsFiltered"] = $total;
            $response["recordsTotal"] = $total;
            //response["draw"] = draw;
           $response["success"] = 1;
           $response["data"] = $aasana;
           
            
        }
        catch (Exception $e)
        {    
  
        }
        
  
        return response($response);
    }

    public function ChangeAasanaStatus(Request $request){
        try{
          ini_set('memory_limit', '256M');
          if(!$this->checkAuth(4)){
             return abort(404);; 
          }
          $aasana = new AasanaModel();
          $status = $request->status;
          $id = $request->aasana_id;
          $aasanaData = $aasana->findOrFail($id);
          
          //$userData = User::whereIn('role_id',[2,3,5])->select('email','name')->get();
          
          //dd($userData);
           if($aasanaData->count()>0){
            $aasanaData->status = $status;
            if($aasanaData->save()){
              if($status==1){
                $msg = "Record Activated Successfully";

                $data[] = [];
                 $data['aasana_name'] = $aasanaData->aasana_name;
                 $data['supportEmail'] = config('mail.supportEmail');
                 $data['website'] = config('app.site_url');  
                 $data['site_name'] = config('app.site_name');
                 $data['subject'] = 'New Aasana Category created '.config('app.site_name');
                 $userData = array(array('name'=>'Devendra Singh','email'=>'devendra.singh@netprophetsglobal.com'),array('name'=>'Tapeshwar Das','email'=>'tapeshwar.das@netprophetsglobal.com '),array('name'=>'Abhilasha Shukla','email'=>'abhilasha.shukla@netprophetsglobal.com '));
              foreach($userData as $ukey=>$user){
                $data['email'] = $user['email'];//$this->encdesc($user->email,'decrypt');
                $data['name'] = $user['name'];//$user->name;            
                //$this->SendMail($data,'admin_event_approve');
              }
              // dd($data);
              }else{
                $msg = "Record De-activated Successfully";
              }            
              return response()->json(["status"=>1,"message"=>$msg,"data"=>json_decode("{}")]);            
            }else{
              return response()->json(["status"=>0,"message"=>"Technical ERROR","data"=>json_decode("{}")]);            
            }
          }else{
            return response()->json(["status"=>0,"message"=>"Technical error","data"=>json_decode("{}")]);          
          }
          
        }catch(Exception $e){
          abort(500, $e->message());
        }

      } 

      
      public function DeleteAasana(Request $request, $id)
      {
         
            try{
            $aasana = new AasanaModel();
            $aasanaData = $aasana->findOrFail($id);
            //print_r($userData); die;
            if($aasanaData->count()>0){
              $aasanaData->delete();
              $request->session()->flash('message.level', 'success');
              $request->session()->flash('message.content', 'Aasana deleted successfully');
              return redirect()->action('Aasana@ListAasana');
            }else{
              $request->session()->flash('message.level', 'error');
              $request->session()->flash('message.content', 'User not found');
            }
    
          }catch(Exception $e){
            abort(500, $e->message());
          }
    
          //return view('users.index', ['users' => $users->getAllUser()]);
        
          
      }
      public function ViewAasana(Request $request)
      {
  
            $id = $request->id;
            $current_user = Auth::user()->id;
            $viewaasana =DB::table('aasanas')
			->join('aasana_categories', 'aasana_categories.id', '=', 'aasanas.aasana_categories_id')	
            ->join('aasana_sub_categories', 'aasana_sub_categories.aasana_categories_id', '=', 'aasana_categories.id')	  
            ->where('aasanas.id', $id)
            ->select('aasanas.*','aasana_categories.category_name as category_name','aasana_sub_categories.subcategory_name as subcategory_name')			
			->orderBy('id','desc')
			->first();
            // echo "<pre>";
            // print_r($viewaasana);
            // die;
            return view('aasanas.view_aasana', compact('viewaasana','viewaasana'));
 

      }
      public function EditAasana(Request $request)
      {
         $id = $request->id;
        $current_user = Auth::user()->id;
        //$poll = Poll::find($id);
        $editaasana = AasanaModel::where('id',$id)->first();
        // echo "<pre>";
        // print_r($editaasana);
        //die;
        $categorylist = AasanaCategoryModel::get(); 
        $subcategorylist = AasanaSubCategory::get(); 
        // print_r($category);
        // die;
       
        return view('aasanas.edit_aasana')->with(
            [
                'editaasana'=>$editaasana,
                'categorylist'=>$categorylist,
                'subcategorylist'=>$subcategorylist
            ]
        );
     }
     public function getEditSubCategory(Request $request)
     {
        // print_r($request->aasana_categories_id);
         $data['subcategory'] = AasanaSubCategory::where("aasana_categories_id",$request->aasana_categories_id)
                     ->get(["subcategory_name","id"])->toArray();
        //  echo "<pre>";
        //  print_r($data['subcategory']);
        //  die;
         return response()->json($data);
     }
     public function updateaasana(Request $request)
     {
        //  echo "<pre>";
        //  print_r($request->category_image);
        //  die;
         
            $validator = Validator::make($request->all(),[
                'aasana_categories_id'=>'required', 
                'aasana_sub_categories_id' =>'required', 
                'aasana_name' => 'required',
                'aasana_description' => 'required',
                //'aasana_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:1500',
                'assana_tag'=>'required', 
                'assana_video_id' =>'required',
                'assana_video_duration'=>'required',
                'assana_benifits' => 'required',
                'assana_instruction' => 'required',          
                ],
                
                [
                  'aasana_categories_id.required'=>'Please enter category name',
                  'subcategory_name.required'=>'Please enter sub category name',
                  'aasana_name.required'=>'Please enter aasana name',
                  'aasana_description.required'=>'Please enter aasana description',
                  'assana_tag.required'=>'Please enter tag name',
                  'assana_video_id.required'=>'Please enter key',
                  'assana_video_duration.required'=>'Please enter video duration',
                  'assana_benifits.required'=>'Please enter benifits',
                  'assana_instruction.required'=>'Please enter instruction',
                  //'subcategory_image.required'=>'Please jpeg,png,jpg,gif,svg|max:1500 sub category Image',
                  
                ]
           );
            if ($validator->fails()) {    
                $errors=$validator->messages();
                return back()->withErrors($errors);
            }
            
                $id = $request->id;
                $current_user = Auth::user()->id;                    
                // $subcategory = AasanaSubCategory::find($id);     
                // if($request->subcategory_image != '')
                // { 
                    
                                    
                //     $path = public_path('images\aasana');
                //     if($subcategory->subcategory_image != ''  && $subcategory->subcategory_image != null)
                //     {                            
                //         $file_old = $path."/".$subcategory->subcategory_image;                         
                //         unlink($file_old);
                //     }
                //     $image = $request->file('subcategory_image');

                //     $input['imagename'] = time().'.'.$image->extension();             
                //     $destinationPath = public_path('images/aasana');
                //     $img = Image::make($image->path());
                //     $img->resize(150, 150, function ($constraint) {
                //         $constraint->aspectRatio();
                //     })->save($destinationPath.'/'.$input['imagename']);                
                //     $destinationPath = public_path('/images');
                //     $image->move($destinationPath, $input['imagename']);
                //     $subcategory_image=$input['imagename'];
                // }
                // else
                // {
                //         $image =$subcategory->subcategory_image;                           
                //         $subcategory_image=$image;
                // }
                    $AsasanaArr=[
                        'aasana_categories_id' =>trim($request->aasana_categories_id),
                        'aasana_sub_categories_id' => trim($request->aasana_sub_categories_id),
                        'aasana_name' =>trim(ucfirst($request->aasana_name)),
                        'aasana_description'=>trim(ucfirst($request->aasana_description)),
                        'assana_tag' =>trim(ucfirst($request->assana_tag)),
                        'assana_video_id'=>trim($request->assana_video_id),
                        'assana_video_duration'=>trim($request->assana_video_duration),
                        'assana_benifits' =>trim(ucfirst($request->assana_benifits)),
                        'assana_instruction'=>trim(ucfirst($request->assana_instruction)),
                        'updated_by' => $current_user,
                        //'subcategory_image'=>$subcategory_image ? $subcategory_image : ''
                    ];
                    // echo "<pre>";
                    // print_r($AsasanaArr);
                    // die;
              
                    if(AasanaModel::where('id',$id)->update($AsasanaArr))
                    {  
                                           
                        return redirect()->action('Aasana@ListAasana')->with('success', 'Aasana Updated successfully');                
                    }
                    else{
                        return back()->withErrors(['errors'=>'Failed to update Aasana']);
                        
                    }
                                   
                     
    }
     
     
}
