<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <link rel="icon" href="{{ asset('images/yoga_logo.png') }}" type="image/gif" sizes="16x16">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="expires" content="0" />
    <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
    <meta http-equiv="pragma" content="no-cache" />

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>
    <!--<link href="{{ asset('css/app.css') }}" rel="stylesheet">-->
    <!-- Bootstrap -->
    
    <link href="{{ asset('css/dataTables.bootstrap.min.css') }}" rel="stylesheet"> 
    <link href="{{ asset('vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/buttons.dataTables.min.css') }}" rel="stylesheet"> 

       

    <!-- Font Awesome -->
    <link href="{{ asset('vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">

    <!-- NProgress -->
    <link href="{{ asset('vendors/nprogress/nprogress.css') }}" rel="stylesheet">

    <!-- iCheck -->
    <link href="{{ asset('vendors/iCheck/skins/flat/green.css') }}" rel="stylesheet">

    <!-- bootstrap-progressbar -->
    <link href="{{ asset('vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css') }}" rel="stylesheet">

    <!-- JQVMap -->
    <link href="{{ asset('vendors/jqvmap/dist/jqvmap.min.css') }}" rel="stylesheet">
    <!-- bootstrap-daterangepicker -->
    <link href="{{ asset('vendors/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="{{ asset('build/css/custom.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/jquery-confirm.min.css') }}" rel="stylesheet">
    <script src="{{ url('/') }}/vendors/jquery/dist/jquery.min.js"></script>
  <script>
  var SITEURL = "{{url('/')}}";
  var FRONTURL = "{{config('app.front_url')}}"
  </script>
    <script>
    
    if(!navigator.onLine) {
      window.location.href = "{{ url('/') }}/login";
    } 
    
    window.onhashchange = function() {
       window.location.href = "{{ url('/') }}/login";
    }
    </script>
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
              <a href="{{ url('/') }}" class="site_title"><i class="fa fa-paw"></i> <span>{{ config('app.name') }}</span></a>
            </div>

            <div class="clearfix"></div>
            
            <!-- sidebar menu -->
            @include('layout/left')
            <!-- /sidebar menu -->

            <!-- /menu footer buttons -->
            <div class="sidebar-footer hidden-small hide">
              <a data-toggle="tooltip" data-placement="top" title="Settings">
                <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="FullScreen">
                <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="Lock">
                <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="Logout" href="login.html">
                <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
              </a>
            </div>
            <!-- /menu footer buttons -->
          </div>
        </div>

        <!-- top navigation -->
        @include('layout/top')
        <!-- /top navigation -->
        <!-- page content -->
        @yield('content')
        <!-- /page content -->

        <!-- footer content -->
        <footer style="margin-left:0px !important">
          <div class="pull-right">
            Copyright &copy; {{date('Y')}} <a href="http://yogalocator.ayush.gov.in">yogalocator.ayush.gov.in</a>
          </div>
          <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
      </div>
    </div>
    <!-- <script src="{{ asset('js/app.js') }}"></script> -->
    <!-- jQuery -->
    
    <!-- Bootstrap -->
    <script src="{{ url('/') }}/vendors/bootstrap/dist/js/bootstrap.min.js"></script>

    <script src="{{ url('/') }}/js/jquery.dataTables.min.js"></script>
    <script src="{{ url('/') }}/js/dataTables.bootstrap.min.js"></script>


    <script src="{{ url('/') }}/js/dataTables.buttons.min.js"></script>
    <script src="{{ url('/') }}/js/buttons.flash.min.js"></script>
    <script src="{{ url('/') }}/js/jszip.min.js"></script>
    <script src="{{ url('/') }}/js/pdfmake.min.js"></script>
    <script src="{{ url('/') }}/js/vfs_fonts.js"></script>    
    <script src="{{ url('/') }}/js/buttons.html5.min.js"></script>
  
    <!-- FastClick -->
    <script src="{{ url('/') }}/vendors/fastclick/lib/fastclick.js"></script>

    <!-- NProgress -->
    <script src="{{ url('/') }}/vendors/nprogress/nprogress.js"></script>
    <!-- Chart.js -->
    <script src="{{ url('/') }}/vendors/Chart.js/dist/Chart.min.js"></script>
    <!-- gauge.js -->
    <script src="{{ url('/') }}/vendors/gauge.js/dist/gauge.min.js"></script>
    <!-- bootstrap-progressbar -->
    <script src="{{ url('/') }}/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
    <!-- iCheck -->
    <script src="{{ url('/') }}/vendors/iCheck/icheck.min.js"></script>
    <!-- Skycons -->
    <script src="{{ url('/') }}/vendors/skycons/skycons.js"></script>
    <!-- Flot -->
    <script src="{{ url('/') }}/vendors/Flot/jquery.flot.js"></script>
    <script src="{{ url('/') }}/vendors/Flot/jquery.flot.pie.js"></script>
    <script src="{{ url('/') }}/vendors/Flot/jquery.flot.time.js"></script>
    <script src="{{ url('/') }}/vendors/Flot/jquery.flot.stack.js"></script>
    <script src="{{ url('/') }}/vendors/Flot/jquery.flot.resize.js"></script>
    <!-- Flot plugins -->
    <script src="{{ url('/') }}/vendors/flot.orderbars/js/jquery.flot.orderBars.js"></script>
    <script src="{{ url('/') }}/vendors/flot-spline/js/jquery.flot.spline.min.js"></script>
    <script src="{{ url('/') }}/vendors/flot.curvedlines/curvedLines.js"></script>
    <!-- DateJS -->
    <script src="{{ url('/') }}/vendors/DateJS/build/date.js"></script>
    <!-- JQVMap -->
    <script src="{{ url('/') }}/vendors/jqvmap/dist/jquery.vmap.js"></script>
    <script src="{{ url('/') }}/vendors/jqvmap/dist/maps/jquery.vmap.world.js"></script>
    <script src="{{ url('/') }}/vendors/jqvmap/examples/js/jquery.vmap.sampledata.js"></script>
    <!-- bootstrap-daterangepicker -->
    <script src="{{ url('/') }}/vendors/moment/min/moment.min.js"></script>
    <script src="{{ url('/') }}/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
    <!-- Custom Theme Scripts -->
    <script src="{{ url('/') }}/build/js/custom.min.js"></script>
    <script src="{{ url('/') }}/js/jquery-confirm.min.js"></script>
    <script src="{{ url('/') }}/js/bootstrap-datetimepicker.js"></script>
    <script src="{{ url('/') }}/js/jquery.common.js"></script>

    <script src="{{url('/')}}/js/ckeditor/ckeditor.js"></script>

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


      /* jQuery(document).keydown(function (event) {
          if (event.keyCode == 123) { // Prevent F12 
              return false;
          } else if (event.ctrlKey && event.shiftKey && event.keyCode == 73) { // Prevent Ctrl+Shift+I        
              return false;
          }
      }); */

    </script>

  </body>
</html>
