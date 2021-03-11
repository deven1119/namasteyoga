<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\User;
use Config;
use Session;

class RedirectIfAuthenticated
{
     
     public function authenticate()
     {
               
         if (Auth::attempt(['email' => $email, 'password' => $password,'status'=>1])) {
             // Authentication passed...   
                     
             return redirect()->intended('dashboard');
         }
     }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {      
            
       $tokenKey = substr(Session::token(),0,16); 
       $request->email = openssl_decrypt(base64_decode($request->email),"AES-128-CBC",$tokenKey,OPENSSL_RAW_DATA,config('app.admin_enc_iv'));        
       if ($request->isMethod('post')) {
           //echo $request->email; die;
           $userData = User::where('email',$request->email)->first();
       
           if(isset($userData) && $userData->count()){ 
               if($userData->status=='0')
                 return redirect()->back()->withErrors(['activated'=>'This account is not activated.']);

                $dbHash = hash('sha256',$userData->password.session('random_salt'));            
                if($request->password==$dbHash){                   
                    //Auth::loginUsingId($userData->id);
                    Auth::login($userData);
                    return redirect('/home');
                }
           }           
       }else{
            Session::put('random_salt', rand(1111111111,(int)9999999999));        
       }
              
        if (Auth::guard($guard)->check()) {                    
            return redirect('/');
        }
        
        return $next($request);
    }
}
