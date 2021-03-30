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
use Auth;


class AasanaCategory extends Controller
{
   // use SendMail, AddAuditTrail;

    public function __construct()
    {
         //$this->middleware('App\Http\Middleware\ModeratorType');
    }
   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function AddCategory()
    {
        return view('aasanas/add_category');
    }
    public function SaveCategory(Request $request)
    {
        $user  = Auth::user()->id;
               $rules = [           
                    'category_name' => 'required',
                    'category_description' => 'required',
                    'category_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:1500',
            ];
            
            $validation = Validator::make($request->all(), $rules);
            if ($validation->fails())
            {
            
                    $errors=$validation->errors();
                    return back()->withErrors($errors);
                
            }
            else
            {
                
                $image = $request->file('category_image');
                $input['imagename'] = time().'.'.$image->extension();             
                $destinationPath = public_path('images\aasana');
                $img = Image::make($image->path());
                $img->resize(150, 150, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath.'/'.$input['imagename']);
           
                $destinationPath = public_path('/images');
                $image->move($destinationPath, $input['imagename']);

                $category_image=$input['imagename'];                
               
                $aasana_category_data=
                ['category_name'=>ucfirst(trim($request->category_name)),
                'category_description'=>trim($request->category_description),
                'category_image'=>trim($category_image),
                'status'=>'1',
                'updated_by'=> $user,
                ];
                $result=AasanaCategoryModel::create($aasana_category_data);
                if($result)
                {
                    return back()->with('success', 'Aasana Category Added successfully');
                }
                if($result)
                {
                    return back()->with('success', 'Something Went Wrong');
                }


            }

    }
    public function ListCategory()
    {
        return view('aasanas/index');
    }
    public function CategoryIndexAjax(Request $request)
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
            $category = AasanaCategoryModel::orderBy('id','desc');
            
            if ($request->search['value'] != "") {            
              $category = $category->where('category_name','LIKE',"%".$search."%");
            }             
            $total = $category->count();
            if($end==-1){
              $category = $category->get();
            }else{
              $category = $category->skip($start)->take($end)->get();
            }
            
            if($category->count() > 0)
            {
                $i = 1;
                $imageUrl=asset('images/aasana/');
                foreach($category as $k=>$v)
                {
                
                      $category[$k]->sr_no = $i; 
                      $category[$k]->id = $category[$k]->id; 
                      $category[$k]->category_name = $category[$k]->category_name;  
                      $category[$k]->category_description = $category[$k]->category_description; 
                      $category[$k]->category_image = $imageUrl.'/'.$category[$k]->category_image;                        
                      $category[$k]->status = $category[$k]->status;                       
					  $i++;
                }     
            }
            $response["recordsFiltered"] = $total;
            $response["recordsTotal"] = $total;
            //response["draw"] = draw;
            $response["success"] = 1;
            $response["data"] = $category;

           // $response["imageUrl"] = $imageUrl;
            
            
        }
        catch (Exception $e)
        {    
  
        }
        
  
        return response($response);
    }

    public function ChangeCategoryStatus(Request $request){
        try{
          ini_set('memory_limit', '256M');
          if(!$this->checkAuth(4)){
             return abort(404);; 
          }
          $category = new AasanaCategoryModel();
          $status = $request->status;
          $id = $request->category_id;
          $categoryData = $category->findOrFail($id);
          
          //$userData = User::whereIn('role_id',[2,3,5])->select('email','name')->get();
          
          //dd($userData);
           if($categoryData->count()>0){
            $categoryData->status = $status;
            if($categoryData->save()){
              if($status==1){
                $msg = "Record Activated Successfully";

                $data[] = [];
                 $data['category_name'] = $categoryData->category_name;
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

      
      public function DeleteCategory(Request $request, $id)
      {
            try{
            $category = new AasanaCategoryModel();
            $categoryData = $category->findOrFail($id);
            //print_r($userData); die;
            if($categoryData->count()>0){
              $categoryData->delete();
              $request->session()->flash('message.level', 'success');
              $request->session()->flash('message.content', 'User deleted successfully');
              return redirect()->action('AasanaCategory@ListCategory');
            }else{
              $request->session()->flash('message.level', 'error');
              $request->session()->flash('message.content', 'User not found');
            }
    
          }catch(Exception $e){
            abort(500, $e->message());
          }
    
          //return view('users.index', ['users' => $users->getAllUser()]);
        
          
      }
      public function ViewCategory(Request $request)
      {
  
            $id = $request->id;
            $current_user = Auth::user()->id;
            //$poll = Poll::find($id);
            $viewcategory = AasanaCategoryModel::where('id',$id)->first();
            return view('aasanas.view_category', compact('viewcategory','viewcategory'));
 

      }
      public function EditCategory(Request $request)
      {
        $id = $request->id;
        $current_user = Auth::user()->id;
        //$poll = Poll::find($id);
        $editcategory = AasanaCategoryModel::where('id',$id)->first();
        return view('aasanas.edit_category', compact('editcategory','editcategory'));
     }
     public function UpdateCategory(Request $request)
     {
        //  echo "<pre>";
        //  print_r($request->category_image);
        //  die;
         
            $validator = Validator::make($request->all(),[
                    'category_name'=>'required',
                    'category_description'=>'required', 
                    'category_image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:1500',            
                ],
                [
                  'category_name.required'=>'Please enter category name',
                  'category_description.required'=>'Please enter category description',
                  'category_image.required'=>'Please jpeg,png,jpg,gif,svg|max:1500 category Image',
                  
                ]
           );
            if ($validator->fails()) {    
                $errors=$validator->messages();
                return back()->withErrors($errors);
            }
            
                $id = $request->id;
                $current_user = Auth::user()->id;                    
                $category = AasanaCategoryModel::find($id);     
                if($request->category_image != '')
                { 
                    
                                    
                    $path = public_path('images\aasana');
                    if($category->category_image != ''  && $category->category_image != null)
                    {                            
                        $file_old = $path."/".$category->category_image;                         
                        unlink($file_old);
                    }
                    $image = $request->file('category_image');

                    $input['imagename'] = time().'.'.$image->extension();             
                    $destinationPath = public_path('images/aasana');
                    $img = Image::make($image->path());
                    $img->resize(150, 150, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($destinationPath.'/'.$input['imagename']);                
                    $destinationPath = public_path('/images');
                    $image->move($destinationPath, $input['imagename']);
                    $category_image=$input['imagename'];
                }
                else
                {
                   
                                    
                            $image =$category->category_image;  ;
                            
                            $category_image=$image;
                }
                    $CategoryArr=[
                        'category_name' => $request->category_name,
                        'category_description' => $request->category_description,
                         'updated_by' => $current_user,
                         'category_image'=>$category_image ? $category_image : ''
                    ];
                   
                    if(AasanaCategoryModel::where('id',$id)->update($CategoryArr))
                    {  
                                           
                        return redirect()->action('AasanaCategory@ListCategory')->with('success', 'Aasana Category Updated successfully');                
                    }
                    else{
                        return back()->withErrors(['errors'=>'Failed to update Aasana Category']);
                        
                    }
                                   
                     
    }
     
     
}
