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

                $category_image=time().$image->getClientOriginalName();                
               
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
}
