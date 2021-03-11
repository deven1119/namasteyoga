<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="{{ url('/') }}/vendors/jquery/dist/jquery.min.js"></script>
    <script src="{{ url('/') }}/js/crypto-js.js"></script>
    
    <title>{{ config('app.name', 'Laravel') }}</title>

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
            <form class="form-horizontal" autocomplete="off" id="loginForm" role="form" method="POST" action="{{ url('/') }}/forgotpassword">
              
              {{ csrf_field() }}     
			  @include('layout/flash')	
              <h1>Forgot password</h1>
              @if ($errors->has('email'))
                  <span class="help-block text-left">
                      <strong>{{ $errors->first('email') }}</strong>
                  </span>
              @endif
              
              <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                <input id="email" placeholder="Email" type="email" class="form-control" name="email" value="" required>
              </div>
                                         
              <div class="form-group">
                  <div class="text-left">
                      <button type="submit" id="loginFormBtn" class="btn btn-primary">
                          Submit
                      </button>                      
                  </div>
              </div>

              <div class="clearfix"></div>


            </form>
          </section>
        </div>
        

      </div>
    </div>
  </body>
<style>

</style>

</html>
