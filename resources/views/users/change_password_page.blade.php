@extends('layout.app')
<script src="{{ url('/') }}/js/crypto-js.js"></script>
<script src="{{ url('/') }}/js/sha256.js"></script>
@section('content')

<div class="right_col" role="main">
    @include('layout/flash') 
    
    <div class="col-md-12 col-xs-12">
   
        <div class="x_panel">
          <div class="x_title">
            <h2>{{$users->name}} <small>{{$users->email}}</small></h2>

            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <br>
            <form class="form-horizontal form-label-left" autocomplete="off"  action="{{ url('/') }}/users/changepass" id="changePass" method="POST">
              <input autocomplete="off" name="hidden" type="text" style="display:none;">
              {{ csrf_field() }}
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">&nbsp;</label>
                <div class="col-md-9 col-sm-9 col-xs-12 errorMSG" style="color:red">

                </div>
              </div> 
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Old Password</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <input class="form-control" autocomplete="off" id="old_password" placeholder="Old Password" type="password" name="old_password" required>
                </div>
              </div>                      
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">New Password</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <input class="form-control" autocomplete="off" placeholder="New Password" id="new_password" type="password" name="new_password" required>
                </div>
              </div>                      
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Confirm Password</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <input class="form-control" autocomplete="off" placeholder="Confirm Password" id="confirm_password" type="password" name="confirm_password" required>
                </div>
              </div>                      
              <div class="ln_solid"></div>
              <div class="form-group">
                <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                  <button type="button" class="btn btn-primary" onclick="location.href='{{ url('/') }}/home'">Cancel</button>
                  <button type="reset" class="btn btn-primary">Reset</button>
                  <button type="button" id="changePassBtn" class="btn btn-success">Submit</button>
                </div>
              </div>

            </form>
          </div>
        </div>
    </div>


<script>
        // var IV = '{{config("app.admin_enc_iv")}}';
        // //var KEY = '{{config("app.admin_enc_key")}}';
        // function encrypt(str) {
        //     var KEY = $("[name='_token']").val();
        //     KEY = KEY.substring(0,16);
        //     key = CryptoJS.enc.Utf8.parse(KEY);// Secret key
        //     var iv= CryptoJS.enc.Utf8.parse(IV);//Vector iv
        //     var encrypted = CryptoJS.AES.encrypt(str, key, { iv: iv, mode: CryptoJS.mode.CBC, padding: CryptoJS.pad.Pkcs7});
        //     return encrypted.toString();
        // }

        jQuery(document).ready(function(){
          jQuery("#changePassBtn").click(function(){        
            jQuery('.errorMSG').html('');                        
              var pwdval = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,})");
              var old_password = jQuery('#old_password').val();                                          
              encryptedOldPassword = sha256(old_password);              
              var new_password = jQuery('#new_password').val();   
              //alert(pwdval.test(new_password));
              //return false;
              if(!pwdval.test(new_password)){
                jQuery('.errorMSG').html('New password should be min 6 char, one capital, one special char');                
                return false;
              }                         
              encryptedNewPassword = sha256(new_password);    
              var confirm_password = jQuery('#confirm_password').val();                            
              encryptedConfirmPassword = sha256(confirm_password);    
              if(new_password != confirm_password){
                jQuery('.errorMSG').html('New password and confirm password should be same');                
                return false;
              }
              jQuery('#old_password').val(encryptedOldPassword);
              jQuery('#new_password').val(encryptedNewPassword);
              jQuery('#confirm_password').val(encryptedConfirmPassword);
              jQuery("#changePass").submit(); // Submit the form
            });
        });
              
       
</script>
@endsection
