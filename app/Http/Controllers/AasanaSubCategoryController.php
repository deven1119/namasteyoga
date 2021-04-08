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


use Auth;


class AasanaSubCategoryController extends Controller
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
    public function AddsubCategory()
    {
        $categorylist=AasanaCategoryModel::get();
        return view('aasanas/add_sub_category',['categorylist'=>$categorylist]);
    }
    public function SaveSubCategory(Request $request)
    {
        $user  = Auth::user()->id;
               $rules = [  
                    'aasana_categories_id'=>'required'  ,      
                    'subcategory_name' => 'required',
                    'subcategory_description' => 'required',
                    'subcategory_image' => 'image|mimes:jpeg,png,jpg|dimensions:max_width=1500,max_height=1500',
            ];
            
            $validation = Validator::make($request->all(), $rules);
            if ($validation->fails())
            {
            
                    $errors=$validation->errors();
                    return back()->withErrors($errors);
                
            }
            else
            {
                
                $image = $request->file('subcategory_image');
                $input['imagename'] = time().'.'.$image->extension();             
                $destinationPath = public_path('images/aasana');
                $img = Image::make($image->path());
                $img->resize(150, 150, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath.'/'.$input['imagename']);
           
                $destinationPath = public_path('images/aasana/subcategory');
                $image->move($destinationPath, $input['imagename']);

                $subcategory_image=$input['imagename'];                
               
                $aasana_sub_category_data=
                [
                'aasana_categories_id'=>trim($request->aasana_categories_id),
                'subcategory_name'=>ucfirst(trim($request->subcategory_name)),
                'subcategory_description'=>trim($request->subcategory_description),
                'subcategory_image'=>trim($subcategory_image),
                'status'=>'1',
                'updated_by'=> $user,
                ];

                $result=AasanaSubCategory::create($aasana_sub_category_data);
                if($result)
                {
                    return back()->with('success', 'Aasana Sub Category Added successfully');
                }
                if($result)
                {
                    return back()->with('success', 'Something Went Wrong');
                }


            }

    }
    public function ListSubCategory()
    {
        return view('aasanas/sub_category_index');
    }
    public function SubCategoryIndexAjax(Request $request)
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
             $subcategory = AasanaSubCategory::orderBy('id','desc');      
            
            if ($request->search['value'] != "") {            
              $subcategory = $subcategory->where('subcategory_name','LIKE',"%".$search."%");                                      
            }             
            $total = $subcategory->count();
            if($end==-1){
              $subcategory = $subcategory->get();
            }else{
              $subcategory = $subcategory->skip($start)->take($end)->get();
            } 
            if($subcategory->count() > 0)
            {
                 

                $i = 1;
                $imageUrl=asset('images/aasana/');
                foreach($subcategory as $k=>$v)
                { 
                     $category_name =AasanaCategoryModel::where('id',$subcategory[$k]->aasana_categories_id)->select('category_name')->first();
                      $subcategory[$k]->sr_no = $i; 
                      $subcategory[$k]->id = $subcategory[$k]->id; 
                      $subcategory[$k]->subcategory_name = $subcategory[$k]->subcategory_name;  
                      $subcategory[$k]->subcategory_description = $subcategory[$k]->subcategory_description; 
                      $subcategory[$k]->category_name =$category_name->category_name; 
                      $subcategory[$k]->subcategory_image = $imageUrl.'/'.$subcategory[$k]->subcategory_image;                        
                      $subcategory[$k]->status = $subcategory[$k]->status;                       
					  $i++;
               
                }     
            }
            $response["recordsFiltered"] = $total;
            $response["recordsTotal"] = $total;
            //response["draw"] = draw;
           $response["success"] = 1;
           $response["data"] = $subcategory;
           
            
        }
        catch (Exception $e)
        {    
  
        }
        
  
        return response($response);
    }

    public function ChangeSubCategoryStatus(Request $request){
        try{
          ini_set('memory_limit', '256M');
          if(!$this->checkAuth(4)){
             return abort(404);; 
          }
          $subcategory = new AasanaSubCategory();
          $status = $request->status;
          $id = $request->subcategory_id;
          $subcategoryData = $subcategory->findOrFail($id);
          
          //$userData = User::whereIn('role_id',[2,3,5])->select('email','name')->get();
          
          //dd($userData);
           if($subcategoryData->count()>0){
            $subcategoryData->status = $status;
            if($subcategoryData->save()){
              if($status==1){
                $msg = "Record Activated Successfully";

                $data[] = [];
                 $data['subcategory_name'] = $subcategoryData->subcategory_name;
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

      
      public function DeleteSubCategory(Request $request, $id)
      {
         
            try{
            $subcategory = new AasanaSubCategory();
            $subcategoryData = $subcategory->findOrFail($id);
            //print_r($userData); die;
            if($subcategoryData->count()>0){
              $subcategoryData->delete();
              $request->session()->flash('message.level', 'success');
              $request->session()->flash('message.content', 'Sub Category deleted successfully');
              return redirect()->action('AasanaSubCategoryController@ListSubCategory');
            }else{
              $request->session()->flash('message.level', 'error');
              $request->session()->flash('message.content', 'User not found');
            }
    
          }catch(Exception $e){
            abort(500, $e->message());
          }
    
          //return view('users.index', ['users' => $users->getAllUser()]);
        
          
      }
      public function ViewSubCategory(Request $request)
      {
  
            $id = $request->id;
            $current_user = Auth::user()->id;
            $viewsubcategory =DB::table('aasana_categories')
			->join('aasana_sub_categories', 'aasana_sub_categories.aasana_categories_id', '=', 'aasana_categories.id')	 
            ->where('aasana_sub_categories.id', $id)
            ->select('aasana_sub_categories.*','aasana_categories.category_name as category_name')			
			->orderBy('id','desc')
			->first();
            return view('aasanas.view_sub_category', compact('viewsubcategory','viewsubcategory'));
 

      }
      public function EditSubCategory(Request $request)
      {
         $id = $request->id;
        $current_user = Auth::user()->id;
        //$poll = Poll::find($id);
        $editsubcategory = AasanaSubCategory::where('id',$id)->first();
        $category = AasanaCategoryModel::get(); 
       
        return view('aasanas.edit_sub_category')->with(
            [
                'editsubcategory'=>$editsubcategory,
                'category'=>$category
            ]
        );
     }
     public function UpdateSubCategory(Request $request)
     {
        //  echo "<pre>";
        //  print_r($request->category_image);
        //  die;
         
            $validator = Validator::make($request->all(),[
                    'aasana_categories_id'=>'required',
                    'subcategory_name'=>'required',
                    'subcategory_description'=>'required', 
                    'subcategory_image' => 'image|mimes:jpeg,png,jpg|dimensions:max_width=1500,max_height=1500',            
                ],
                 [
                  'aasana_categories_id.required'=>'Please enter category name',
                  'subcategory_name.required'=>'Please enter sub category name',
                   'subcategory_description.required'=>'Please enter sub category description',
                   'subcategory_image.required'=>'Please jpeg,png,jpg,gif,svg|max:1500 sub category Image',
                  
                 ]
           );
            if ($validator->fails()) {    
                $errors=$validator->messages();
                return back()->withErrors($errors);
            }
            
                $id = $request->id;
                $current_user = Auth::user()->id;                    
                $subcategory = AasanaSubCategory::find($id);     
                if($request->subcategory_image != '')
                { 
                    
                                    
                    $path = public_path('images\aasana');
                    if($subcategory->subcategory_image != ''  && $subcategory->subcategory_image != null)
                    {                            
                        $file_old = $path."/".$subcategory->subcategory_image;                         
                        unlink($file_old);
                    }
                    $image = $request->file('subcategory_image');

                    $input['imagename'] = time().'.'.$image->extension();             
                    $destinationPath = public_path('images/aasana');
                    $img = Image::make($image->path());
                    $img->resize(150, 150, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($destinationPath.'/'.$input['imagename']);                
                    $destinationPath = public_path('images/aasana/subcategory');
                    $image->move($destinationPath, $input['imagename']);
                    $subcategory_image=$input['imagename'];
                }
                else
                {
                        $image =$subcategory->subcategory_image;                           
                        $subcategory_image=$image;
                }
                    $SubCategoryArr=[
                        'aasana_categories_id' => $request->aasana_categories_id,
                        'subcategory_name' => $request->subcategory_name,
                        'subcategory_description' => $request->subcategory_description,
                        'updated_by' => $current_user,
                        'subcategory_image'=>$subcategory_image ? $subcategory_image : ''
                    ];
                    // echo "<pre>";
                    // print_r($SubCategoryArr);
                    // die;
                   
                    if(AasanaSubCategory::where('id',$id)->update($SubCategoryArr))
                    {  
                                           
                        return redirect()->action('AasanaSubCategoryController@ListSubCategory')->with('success', 'Aasana Sub Category Updated successfully');                
                    }
                    else{
                        return back()->withErrors(['errors'=>'Failed to update Aasana Sub Category']);
                        
                    }
                                   
                     
    }
     
     
}
