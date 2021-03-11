<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <link rel="icon" href="{{ asset('images/yoga_logo.png') }}" type="image/gif" sizes="16x16">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="{{ url('/') }}/vendors/jquery/dist/jquery.min.js"></script>
    <script src="{{ url('/') }}/js/crypto-js.js"></script>
    
    <title>{{ config('app.name') }}</title>

    <!-- Bootstrap -->
    <link href="vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- Animate.css -->
    <link href="vendors/animate.css/animate.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="build/css/custom.min.css" rel="stylesheet">
    <script src="{{ url('/') }}/js/sha256.js"></script>
  </head>

  <body class="login">
    <div>
      <a class="hiddenanchor" id="signup"></a>
      <a class="hiddenanchor" id="signin"></a>
      <div id="random_salt" style="visibility:hidden">{{ Session::get('random_salt')}}</div>
      <div class="login_wrapper">
        <div class="animate form login_form">
          <section class="login_content">
            <input type="password" name="password" style="visibility: hidden">
            <input type="email" name="email" style="visibility: hidden">
            <form class="form-horizontal" autocomplete="off" id="loginForm" role="form" method="POST" action="{{ route('login') }}">
              
              {{ csrf_field() }}              
              <h1>Login Form</h1>
              @if ($errors->has('email'))
                  <span class="help-block text-left">
                      <strong>{{ $errors->first('email') }}</strong>
                  </span>
              @endif
              @if ($errors->has('password'))
                  <span class="help-block text-left">
                      <strong>
                      {{ $errors->first('password') }}                      
                      </strong>
                  </span>
              @endif

              @if ($errors->has('captcha'))
                <span class="help-block text-left">
                  <strong>
                        {{ $errors->first('captcha') }}
                  </strong>                        
                </span>        
              @endif
              @if ($errors->has('activated'))
                <span class="help-block text-left">
                  <strong>
                        {{ $errors->first('activated') }}
                  </strong>                        
                </span>        
              @endif

              <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                <input id="email" autocomplete="off" tabindex="-1" placeholder="Email" type="email" class="form-control" name="email" value="" required readonly 
    onfocus="removeAttr('email')">
              </div>
              <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                <input id="password" autocomplete="off" tabindex="-1" placeholder="password" type="password" class="form-control" name="password" value="" required readonly 
    onfocus="removeAttr('password')">
              </div>
              <div class="form-group captacha">
                  <div class="col-sm-5">
                  {{ Captcha::img('math') }}
                  </div>
                  <div class="col-sm-3">
                  <input id="captcha" autocomplete="off" tabindex="-3" class="form-control" name="captcha" value="" required>
                  </div>
              </div>
              
              <div class="form-group">
                  <div class="text-left">
                      <button type="button" id="loginFormBtn" class="btn btn-primary">
                          Login
                      </button>
                      <a href="{{ url('/') }}/forgotpassword">Forgot password</a>
                  </div>
              </div>

              <div class="clearfix"></div>


            </form>
          </section>
        </div>
        <script>
        var IV = '{{config("app.admin_enc_iv")}}';
        //var KEY = 'SHsjD5HQ1YRAFkZecO86sUQiwNzb3km8CiJmh9Ty';        
        function encrypt(str) {
            var KEY = $("[name='_token']").val();
            KEY = KEY.substring(0,16);
            key = CryptoJS.enc.Utf8.parse(KEY);// Secret key
            var iv= CryptoJS.enc.Utf8.parse(IV);//Vector iv
            var encrypted = CryptoJS.AES.encrypt(str, key, { iv: iv, mode: CryptoJS.mode.CBC, padding: CryptoJS.pad.Pkcs7});
            return encrypted.toString();
        }

        function removeAttr(val){
          jQuery('#'+val).removeAttr('readonly');
        }
                        
        jQuery(document).ready(function(){
          jQuery("#loginFormBtn").click(function(){                                                          
              var email = jQuery('#email').val();                            
              encryptedEmail = encrypt(email);              
              var password = jQuery('#password').val();                  
              var random_salt = jQuery('#random_salt').html();                                                                  
              var sha1pass = sha256(password);              
              var encryptedPassword = sha256(sha1pass+random_salt);   
              setTimeout(function(){
               jQuery('#email').val(encryptedEmail);                
                jQuery('#password').val(encryptedPassword);              
                jQuery("#loginForm").submit(); // Submit the form
              },500)               
              
            });
        });

                     
</script>        

    <script>
      history.pushState(null, null, '');
      window.addEventListener('popstate', function(event) {
      history.pushState(null, null, '');
      });

      jQuery(function() {
        jQuery(this).bind("contextmenu", function(e) {
            e.preventDefault();
        });
      }); 


      jQuery(document).keydown(function (event) {
          if (event.keyCode == 123) { // Prevent F12
              return false;
          } else if (event.ctrlKey && event.shiftKey && event.keyCode == 73) { // Prevent Ctrl+Shift+I        
              return false;
          }
      });

    </script>

      </div>
    </div>
  </body>
<style>
.captacha {}
.captacha .col-sm-5 { padding-left:0;    text-align: left;}
.captacha .col-sm-7 { padding-right:0;}

input {
    text-security:disc;
    -webkit-text-security:disc;
    -mox-text-security:disc;
}
</style>

</html>
