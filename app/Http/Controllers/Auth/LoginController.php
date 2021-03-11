<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Http\Controllers\Traits\AddAuditTrail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;



use App\User;
use Session;
use App\Audit;


//use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    use AddAuditTrail;   
    use AuthenticatesUsers;
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */
    
    use AuthenticatesUsers {
        logout as performLogout;
    }
    

    public function logout(Request $request)
    {        
        $data = new Audit();
        $data->source = 'Logout';
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

        $this->performLogout($request);
        return redirect('/login');
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    //protected $redirectTo = '/home';

    public function redirecTo()
    {
        //echo $role = auth()->user()->role_id;    die;
        return '/';
        //$path = $role->getPathForRole();
        //return redirect($path);
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {   
        //$request->headers->set('random_salt', rand(1111111111,9999999999));     
                               
        $this->middleware('guest')->except('logout');
    }

    protected function validateLogin(Request $request) { 
      
      $this->validate($request, [                 
        'captcha' => 'required|captcha', 
      ]); 
    } 


    
    protected function authenticated(Request $request, $user) {
                
        //$url = "http://api.ipinfodb.com/v3/ip-country/?key=b2d0d8462435ca347d82d9bcde336749e6950f61ee334b18fd1401b0c9fce4d6&ip=".request()->ip();        
        $data = new Audit();
        $data->source = 'Login';
        $data->referer = '';
        $data->process_id = '';
        $data->country = "India";
        $data->username = 'Admin';
        $data->ip = request()->ip();
        $data->url = url()->current();
        $data->user_agent = $request->server('HTTP_USER_AGENT');
        $data->session = session()->getId();
        //print_r($data); die;
        $this->addAuditTrailData($data);
        Session::forget('random_salt');
    }    
}
