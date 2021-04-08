<?php

namespace App\Http\Controllers;

use App\User;
use App\Users;
use App\Country;
use App\Event;
use App\State;
use App\City;
use App\NotifyMe;
use App\Moderator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Common\Utility;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\OldpasswordHistory;
use App\Http\Controllers\Traits\SendMail;
use App\Http\Controllers\Traits\AddAuditTrail;
use Config;
use Mail;
use Session;
use App\Audit;
use Auth;

//use Carbon;
//use App\Mail\WelcomeMail;
//use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    use SendMail, AddAuditTrail;

    public function __construct()
    {
         $this->middleware('App\Http\Middleware\ModeratorType');
    }
   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
          $uesrs = User::with(['country','state','city'])->where([['status', '1'],['role_id',3]])->paginate(config('app.paging'));
          //echo '<pre>'; print_r($uesrs); die;
          return view('users.index', ['users' => $uesrs]);
        }catch(Exception $e){
          abort(500, $e->message());
        }
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function center()
    {
        try{

          //echo '<pre>'; print_r($uesrs); die;
          return view('users.center');
        }catch(Exception $e){
          abort(500, $e->message());
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function pendings()
    {
        try{

          //echo '<pre>'; print_r($uesrs); die;
          return view('users.pendings');
        }catch(Exception $e){
          abort(500, $e->message());
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function rejected()
    {
        try{

          //echo '<pre>'; print_r($uesrs); die;
          return view('users.rejected');
        }catch(Exception $e){
          abort(500, $e->message());
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function changepass(Request $request)
    {
        try{
          Utility::stripXSS();


          $user  = JWTAuth::user();
          if ($request->isMethod('post')) {

            if($user->count()>0){

              if(!$this->checkPasswordHistory($request->new_password,$user->id)){
                $request->session()->flash('message.level', 'error');
                $request->session()->flash('message.content', 'Password should not same as last 3 password');
                return redirect()->action('UserController@changepass');
              }

              if($request->new_password != $request->confirm_password){
                $request->session()->flash('message.level', 'error');
                $request->session()->flash('message.content', 'Password and confirm password is not same');
                return redirect()->action('UserController@changepass');
              }
              if($request->old_password != $user->password){
                $request->session()->flash('message.level', 'error');
                $request->session()->flash('message.content', 'Old password is incorrect');
                return redirect()->action('UserController@changepass');
              }else{
                $pwdAdd = $request->new_password;
                User::where('email', $user->email)->update(['password'=>$pwdAdd]);
                $data = new Audit();
                $data->source = 'Change password';
                $data->referer = '';
                $data->process_id = '';
                $data->country = 'India';
                $data->username = 'Admin';
                $data->ip = request()->ip();
                $data->url = url()->current();
                $data->user_agent = $request->server('HTTP_USER_AGENT');
                $data->session = session()->getId();
                //print_r($data); die;
                $this->addAuditTrailData($data);

                $request->session()->flash('message.level', 'success');
                $request->session()->flash('message.content', 'Password changed successfully');

                $oldPwdHistoryObj = new OldpasswordHistory();
                $oldPwdHistoryObj->user_id = $user->id;
                $oldPwdHistoryObj->password = $pwdAdd;
                $oldPwdHistoryObj->save();

                return redirect()->action('UserController@index');
              }

            }else{
              $request->session()->flash('message.level', 'error');
              $request->session()->flash('message.content', 'user not found');
              return redirect()->action('UserController@changepass');
            }

          }
          return view('users.change_password_page',['users'=>$user]);
        }catch(Exception $e){
          abort(500, $e->message());
        }
    }

    public function userIndexAjax(Request $request){

      Utility::stripXSS();
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

          if(Auth::user()->role_id==1){
            $cond = [['role_id',3],['suspended','0'],['verified_otp','1']];
          }else{
            $cond = [['role_id',3],['verified_otp','1'],['suspended','0'],['ycb_approved','1']];
          }



          $users = User::with(['Country','State','City','Role'])->where($cond);
          //echo '<pre>'; print_r($users); die;

          if ($request->search['value'] != "") {
            $users = $users->where('name','LIKE',"%".$search."%");
          }

          $total = $users->count();
          if($end==-1){
            $users = $users->get();
          }else{
            $users = $users->skip($start)->take($end)->get();
          }

          if($users->count() > 0){
            foreach($users as $k=>$v){
              $users[$k]->email = $this->encdesc($users[$k]->email,'decrypt');
              $users[$k]->phone = $this->encdesc($users[$k]->phone,'decrypt');
            }
          }

          $response["recordsFiltered"] = $total;
          $response["recordsTotal"] = $total;
          //response["draw"] = draw;
          $response["success"] = 1;
          $response["data"] = $users;

        } catch (Exception $e) {

        }


      return response($response);
    }


    public function userCenterIndexAjax(Request $request){
      //ini_set('memory_limit','2048M');
      Utility::stripXSS();
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
          $cond = [['role_id',2],['suspended','0']];
          $users = User::with(['Country','State','City','Role'])->where($cond);
          //echo '<pre>'; print_r($users); die;

          if ($request->search['value'] != "") {
            $users = $users->where('name','LIKE',"%".$search."%");
          }

          $total = $users->count();
          if($end==-1){
            $users = $users->get();
          }else{
            $users = $users->skip($start)->take($end)->get();
          }

          if($users->count() > 0){
            foreach($users as $k=>$v){
              $users[$k]->email = $this->encdesc($users[$k]->email,'decrypt');
              $users[$k]->phone = $this->encdesc($users[$k]->phone,'decrypt');
            }
          }
        //print_r($users);die;
          $response["recordsFiltered"] = $total;
          $response["recordsTotal"] = $total;
          //response["draw"] = draw;
          $response["success"] = 1;
          $response["data"] = $users;

        } catch (Exception $e) {

        }


      return response($response);
    }


    public function userPendingIndexAjax(Request $request){

      Utility::stripXSS();
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
          $cond = [['role_id',3],['suspended','0'],['verified_otp','1'],['ycb_approved','0']];
          $users = User::with(['Country','State','City','Role'])->where($cond);
          //echo '<pre>'; print_r($users); die;

          if ($request->search['value'] != "") {
            $users = $users->where('name','LIKE',"%".$search."%");

          }

          $total = $users->count();
          if($end==-1){
            $users = $users->get();
          }else{
            $users = $users->skip($start)->take($end)->get();
          }

          if($users->count() > 0){
            foreach($users as $k=>$v){
              $users[$k]->email = $this->encdesc($users[$k]->email,'decrypt');
              $users[$k]->phone = $this->encdesc($users[$k]->phone,'decrypt');
            }
          }

          $response["recordsFiltered"] = $total;
          $response["recordsTotal"] = $total;
          //response["draw"] = draw;
          $response["success"] = 1;
          $response["data"] = $users;

        } catch (Exception $e) {

        }


      return response($response);
    }

    public function userRejectedIndexAjax(Request $request){

      Utility::stripXSS();
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
          $cond = [['role_id',3],['suspended','0'],['verified_otp','1'],['ycb_approved','2']];
          $users = User::with(['Country','State','City','Role'])->where($cond);
          //echo '<pre>'; print_r($users); die;

          if ($request->search['value'] != "") {
            $users = $users->where('name','LIKE',"%".$search."%");

          }

          $total = $users->count();
          if($end==-1){
            $users = $users->get();
          }else{
            $users = $users->skip($start)->take($end)->get();
          }

          if($users->count() > 0){
            foreach($users as $k=>$v){
              $users[$k]->email = $this->encdesc($users[$k]->email,'decrypt');
              $users[$k]->phone = $this->encdesc($users[$k]->phone,'decrypt');
            }
          }

          $response["recordsFiltered"] = $total;
          $response["recordsTotal"] = $total;
          //response["draw"] = draw;
          $response["success"] = 1;
          $response["data"] = $users;

        } catch (Exception $e) {

        }


      return response($response);
    }



   /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

      try{
        Utility::stripXSS();
        Log::info('create new user profile for user:');

        $users = new Users();
        //$users = $users->findOrFail($id);
        //echo $id;
        $users->first_name = $request->input('first_name');
        $users->username = $request->input('username');
        $users->phone = $request->input('phone');
        $users->password = bcrypt($request->input('password'));
        $users->last_name  = $request->input('last_name');
        $users->email  = $request->input('email');
        $users->is_blocked = $request->input('is_blocked');
        $users->verification_code = '';
        $users->country_id = $request->input('country_id');
        $users->save();
        $request->session()->flash('message.level', 'success');
        $request->session()->flash('message.content', 'User created successfully');
        return redirect()->action('UserController@index');
      }catch(Exception $e){
        abort(500, $e->message());
      }
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add(Request $request)
    {

      try{
        Utility::stripXSS();
        Log::info('create new user profile for user:');

         if(!$this->checkAuth(1)){
           return abort(404);;
         }
         
         $request->session()->forget('message.level');
         $request->session()->forget('message.content');
         
        if ($request->isMethod('post')) {
          $users = new User();
          //$users = $users->findOrFail($id);
          
          if(trim($request->email) == '') {
              $request->session()->flash('message.level', 'error');
              $request->session()->flash('message.content', 'Invalid email.');
              $moderators = $this->getModerators();
              
              return view('users.add',['moderators'=> $moderators]);
          }
        
            if(User::where('email', $request->email)->count()){
              $request->session()->flash('message.level', 'error');
              $request->session()->flash('message.content', 'Moderator email already in use.');
              $moderators = $this->getModerators();
              return view('users.add',['moderators'=> $moderators]);
            }
          //echo $id;
          $users->name = $request->input('name');
          $users->phone = $request->input('phone');
          $users->password = hash('sha256', $request->input('password'));
          $users->email  = $request->input('email');
          $users->role_id = 4;
          $users->moderator_id = $request->moderator_id;
          $users->organization_name = $request->organization_name;
          $users->designation = $request->designation;
          $users->status = $request->is_blocked;

          $moderatorData = array();
		  $users->save();
		  $getModerator = Moderator::find($request->moderator_id);
          /*if($users->save()){
             foreach($request->moderator_id as $moderator_id){
              DB::table('user_moderator_type')->insert(['user_id'=>$users->id,'moderator_id'=> $moderator_id]);
              $getModerator = Moderator::find($moderator_id);
              $moderatorData[] = $getModerator->moderator;
            }
            $mod = implode(',',$moderatorData); 
          }*/
         
          $data = [];
          $data['email'] = $request->input('email');
          $data['name'] = $request->input('name');
          $data['password'] = $request->input('password');
          $data['supportEmail'] = config('mail.supportEmail');
          $data['moderators'] =  $getModerator->moderator;
          $data['website'] = config('app.site_url');
          $data['site_name'] = config('app.site_name');
          $data['subject'] = 'Moderator Account Created '.config('app.site_name');

          $this->SendMail($data,'add_moderator');


          $request->session()->flash('message.level', 'success');
          $request->session()->flash('message.content', 'Moderator created successfully');
          return redirect()->action('UserController@moderator_list');
        }else{

          $moderators = $this->getModerators();
          return view('users.add',['moderators'=> $moderators]);
        }

      }catch(Exception $e){
        abort(500, $e->message());
      }
    }


    public function moderator_list()
    {

        try{

          if(!$this->checkAuth(1)){
           return abort(404);;
         }

          $uesrs = User::where('role_id',1)->paginate(config('app.paging'));
          //echo '<pre>'; print_r($uesrs); die;
          $moderators = $this->getModerators();
          return view('users.moderator_list', ['users' => $uesrs,'moderators'=>$moderators]);
        }catch(Exception $e){
          abort(500, $e->message());
        }
    }


    public function moderatorIndexAjax(Request $request){

      Utility::stripXSS();

      if(!$this->checkAuth(1)){
           return abort(404);;
      }

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
          $cond[] = ['role_id',4];
          $users = User::where($cond)->groupBy('users.id');
          //echo '<pre>'; print_r($users); die;
          $users = $users->leftJoin('moderators as m','users.moderator_id','m.id')
						->select('users.*','m.moderator as usertype');
          if ($request->search['value'] != "") {
            $users = $users->where('email','LIKE',"%".$search."%")
            ->orWhere('name','LIKE',"%".$search."%")
            ->orWhere('phone','LIKE',"%".$search."%");
          }
          //echo $request->columns[3]['search']['value'];die;
          if($request->columns[3]['search']['value']!='' && $request->columns[3]['search']['value']!=null){
            $users->where('m.id',"".$request->columns[3]['search']['value']."");
          }
         // $response["sql"] = $users->toSql();
          $total = $users->get()->count();
          if($end==-1){
            $users = $users->get();
          }else{
            $users = $users->skip($start)->take($end)->get();
          }

          if($users->count() > 0){
            foreach($users as $k=>$v){
              //$users[$k]->email = $this->encdesc($users[$k]->email,'decrypt');
              //$users[$k]->phone = $this->encdesc($users[$k]->phone,'decrypt');
            }
          }
         
          $response["recordsFiltered"] = $total;
          $response["recordsTotal"] = $total;
          //response["draw"] = draw;
          $response["success"] = 1;

          $response["data"] = $users;

        } catch (Exception $e) {

        }


      return response($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
      try{
        $users = new Users();
        $userData = $users->findOrFail($id);
        //print_r($userData); die;
        if($userData->count()>0){
          $userData->delete();
          $request->session()->flash('message.level', 'success');
          $request->session()->flash('message.content', 'User deleted successfully');
          return redirect()->action('UserController@index');
        }else{
          $request->session()->flash('message.level', 'error');
          $request->session()->flash('message.content', 'User not found');
        }

      }catch(Exception $e){
        abort(500, $e->message());
      }

      //return view('users.index', ['users' => $users->getAllUser()]);
    }



    public function changeycbstatus(Request $request){
      try{



        if(!$this->checkAuth(4)){
           return abort(404);;
        }
        $users = new User();
        $status = $request->status;
        $id = $request->userid;
        $userData = $users->findOrFail($id);
        $ycb_subject = "";
        //print_r($userData); die;
        if($userData->count()>0){

          if($status=='1'){
            $userData->ycb_approved = '1';
            $userData->status = '1';
            $userData->approved_by = '{"user_id": "'.Auth::user()->id.'", "name": "'.Auth::user()->name.'","email": "'.Auth::user()->email.'","approvedDate":"'.date('Y-m-d H:i:s').'"}';

            $ycb_subject = "Approved";

            $msg = "Your registration has been approved by the Yoga Locator moderator. Your listing is now available on the Yoga Locator app.";
          }

          if($status=='2'){
            $userData->ycb_approved = '2';
            $userData->status = '0';
            $userData->approved_by = '{"user_id": "'.Auth::user()->id.'", "name": "'.Auth::user()->name.'","email": "'.Auth::user()->email.'"}';
            $ycb_subject = "Rejected";
            $msg = "Your registration has been rejected by the Yoga Locator moderator as your YCB certificate number was not found in the database.";
          }


          if($userData->save()){
            $data = [];
            if($status==1){
              $msg = "Record Activated Successfully";

            }else{
              $msg = "Record De-activated Successfully";
            }
            $data['email'] = $this->encdesc($userData->email,'decrypt');
            $data['name'] = $userData->name;
            $data['supportEmail'] = config('mail.supportEmail');
            $data['website'] = config('app.site_url');
            $data['site_name'] = config('app.site_name');
            $data['msg'] = $msg;
            $data['subject'] = 'YCB '.$ycb_subject.' Mail '.config('app.site_name');

            $this->SendMail($data,'admin_ycb_change');
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

     public function resetModeratorPassword(Request $request){

              /* $data = [];
              $msg = "Record Activated Successfully7777";
              $data['email'] = "swapnilshukla201@hotmail.com";
              $data['name'] = "ravindra";
              $data['supportEmail'] = config('mail.supportEmail');
              $data['website'] = config('app.site_url');
              $data['site_name'] = config('app.site_name');
              $data['msg'] = $msg;
              $data['subject'] = 'YCB test Mail '.config('app.site_name');
              if($this->SendMail($data,'admin_ycb_change')){
                return response()->json(["status"=>1,"message"=>$msg,"data"=>json_decode("{}")]);
              }else{
                return response()->json(["status"=>1,"message"=>"mial not sent","data"=>json_decode("{}")]);
              }
              die;
              */
      try{

        if(!$this->checkAuth(1)){
           return abort(404);;
        }
        $users = new User();
        $id = $request->userid;
        $userData = $users->findOrFail($id);

        if($userData->count()>0){

            $pass = uniqid();
            $msg = "Password reset email sent successfully.";

            $data = [];
            $data['pass'] = $pass;
            $userData->password = hash('sha256', $pass);
            $data['email'] =  $userData->email; // "awnish.shrivastava@netprophetsglobal.com";
            $data['name'] = $userData->name;
            $data['supportEmail'] = config('mail.supportEmail');
            $data['website'] = config('app.site_url');
            $data['site_name'] = config('app.site_name');
            $data['subject'] = 'Moderator password reset successfully.';
            $userData->save();
            $this->SendMail($data,'moderator_password_reset');
            return response()->json(["status"=>1,"message"=>$msg,"data"=>json_decode("{}")]);

        }else{
          return response()->json(["status"=>0,"message"=>"Technical error","data"=>json_decode("{}")]);
        }

      }catch(Exception $e){
        abort(500, $e->message());
      }
    }




    public function changestatus(Request $request){
      try{

        if(!$this->checkAuth(1)){
           return abort(404);;
        }
        $users = new User();
        $status = $request->status;
        $id = $request->userid;
        $userData = $users->findOrFail($id);

        //print_r($userData); die;
        if($userData->count()>0){
          $userData->status = $status;
          if($userData->save()){
            if($status==1){
              $msg = "Record Activated Successfully";

              $data = [];
              $data['email'] = $this->encdesc($userData->email,'decrypt');
              $data['name'] = $userData->name;
              $data['supportEmail'] = config('mail.supportEmail');
              $data['website'] = config('app.site_url');
              $data['site_name'] = config('app.site_name');
              $data['subject'] = 'Account Activation Mail '.config('app.site_name');

              $this->SendMail($data,'admin_user_approve');
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

    public function changemodratorstatus(Request $request){
      try{

        if(!$this->checkAuth(1)){
           return abort(404);;
        }
        $users = new User();
        $status = $request->status;
        $id = $request->userid;
        $userData = $users->findOrFail($id);

        //print_r($userData); die;
        if($userData->count()>0){
          $userData->status = "$status";
          if($userData->save()){
            $data = [];
            if($status==1){
              $msg = "Record Activated Successfully";

            
             // $data['email'] = $this->encdesc($userData->email,'decrypt');
             $data['email'] = $userData->email;
              $data['name'] = $userData->name;
              $data['supportEmail'] = config('mail.supportEmail');
              $data['website'] = config('app.site_url');
              $data['site_name'] = config('app.site_name');
              $data['subject'] = 'Account Activation Mail '.config('app.site_name');
             
              $this->SendMail($data,'admin_user_approve');
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


    public function forgotpassword(Request $request){

      Utility::stripXSS();

      if ($request->isMethod('post')) {

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|max:255'
        ]);

        if($validator->fails()){
          $request->session()->flash('message.level', 'error');
          $request->session()->flash('message.content', 'Invalid Email');
          return redirect()->action('UserController@forgotpassword');
        }

        $user = User::where([
          ['email',$request->email],
          ['suspended','0'],
          ['status','1']
        ])->first();

        if(isset($user->id)){
          if($user->role_id != 4){
            $request->session()->flash('message.level', 'error');
            $request->session()->flash('message.content', 'Only moderator can change password');
            return redirect()->action('UserController@forgotpassword');
          }

          $user->cp_code = uniqid();
          $mail = $user->email;

          $urlData = base64_encode($user->cp_code."__".$mail);


          $data = [];
          $data['email'] = $mail;
          $data['name'] = $user->name;
          $data['supportEmail'] = config('mail.supportEmail');
          $data['website'] = config('app.site_url');
          $data['site_name'] = config('app.site_name');
          $data['url'] = url('/')."/changepassword_second?params=$urlData";
          $data['subject'] = 'Moderator password reset Mail '.config('app.site_name');
          $user->save();
          $this->SendMail($data,'moderator_pass_reset_front');

          $request->session()->flash('message.level', 'success');
          $request->session()->flash('message.content', 'Password change link sent to your email.');
          return redirect()->action('UserController@forgotpassword');

        }else{
          $request->session()->flash('message.level', 'error');
          $request->session()->flash('message.content', 'Invalid User');
          return redirect()->action('UserController@forgotpassword');
        }

      }

      return view('users.forgotpassword',[]);

    }

    public function changepassword_second(Request $request){
      try{
        Utility::stripXSS();

        if ($request->isMethod('post')) {

           $pasword = $request->password;
           $cp_code = $request->cp_code;
           $user_id = $request->user_id;



           $userData = User::where([
            ['id',$user_id],
            ['cp_code',$cp_code]
          ])->first();


           if(isset($userData->id)){

             $urlData = base64_encode($cp_code."__".$this->encdesc($userData->email,'decrypt'));

             if($request->password != $request->confirm_password){
              $request->session()->flash('message.level', 'error');
              $request->session()->flash('message.content', 'Password and confirm password is not same.');
              return redirect(url('/')."/changepassword_second?params=$urlData");
             }

             if($userData->role_id != 4){
              $request->session()->flash('message.level', 'error');
              $request->session()->flash('message.content', 'You are  not authorized to change password.');
              return redirect(url('/')."/changepassword_second?params=$urlData");
             }

             $userData->password = bcrypt($request->input('password'));
             $userData->save();
             $data = [];
             $data['email'] = $this->encdesc($user->email,'decrypt');
             $data['name'] = $user->name;
             $data['supportEmail'] = config('mail.supportEmail');
             $data['website'] = config('app.site_url');
             $data['site_name'] = config('app.site_name');
             $data['subject'] = 'Account Activation from '.config('app.site_name');
             $data['otp'] = $otp;

              $this->SendMail($data,'password_change');
              $request->session()->flash('message.level', 'success');
              $request->session()->flash('message.content', 'Account Activated successfully.');
              return view('errors.success', ['msg' => "Account Activated successfully"]);
           }else{
              $request->session()->flash('message.level', 'error');
              $request->session()->flash('message.content', 'Techinical error.');
              return view('errors.success', ['msg' => "error"]);
           }

        }

        $params = $request->input('params');
        $arr = explode("__",base64_decode($params));
        $cp_code = $arr[0];
        $email = $arr[1];
        $email = $this->encdesc($email,'encrypt');
        $user = User::where([
            ['email',$email],
            ['cp_code',$cp_code]
        ])->first();

          if(isset($user->id)){
               return view('users.changepassword_second',['user_id'=>$user->id,"cp_code"=>$cp_code]);
          }else{
              $request->session()->flash('message.level', 'error');
              $request->session()->flash('message.content', 'Technical error.');
              return view('errors.error', ['msg' => "Not found error"]);
          }

        }catch(Exception $e){
          $request->session()->flash('message.level', 'error');
          $request->session()->flash('message.content', 'Technical Exception.');
          return view('errors.error', ['msg' => "error Exception"]);
        }
    }


    public function activate_account(Request $request){
      try{

        $params = $request->input('params');

        $arr = explode("__",base64_decode($params));
        $suspend_otp = $arr[0];
        $email = $arr[1];

        //$email = $this->encdesc($email,'encrypt');

        $user = User::where([
          ['email',$email],
          ['suspend_otp',$suspend_otp]
        ])->first();



        if(isset($user->id)){
           $user->suspended = '0';
           $otp = rand(1000000,999999);
           $user->save();
           $data = [];
           $data['email'] = $this->encdesc($user->email,'decrypt');
           $data['name'] = $user->name;
           $data['supportEmail'] = config('mail.supportEmail');
           $data['website'] = config('app.site_url');
           $data['site_name'] = config('app.site_name');
           $data['subject'] = 'Account Activation from '.config('app.site_name');
           $data['otp'] = $otp;
           $user->suspend_otp = $otp;

            $this->SendMail($data,'account_activation');
            $request->session()->flash('message.level', 'success');
            $request->session()->flash('message.content', 'Account Activated successfully.');
            return view('errors.success', ['msg' => "Account Activated successfully"]);
        }else{
            $request->session()->flash('message.level', 'error');
            $request->session()->flash('message.content', 'Technical error.');
            return view('errors.error', ['msg' => "Account Activation error"]);
        }
        //JWTAuth::setToken($token)->invalidate();
      }catch(Exception $e){
          $request->session()->flash('message.level', 'error');
          $request->session()->flash('message.content', 'Technical Exception.');
          return view('errors.error', ['msg' => "Account Activation Exception"]);
      }
    }
    public function getModerators(){

      return Moderator::where('status',1)->get();
    }

}
