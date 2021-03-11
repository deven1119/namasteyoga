@extends('layout.app')
<meta name="csrf-token" content="{{ csrf_token() }}">
@section('content')
<div class="right_col" role="main">
  @include('layout/flash')
    <div class="x_panel">
    <div class="x_content"> 
        <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">            
                  <div class="x_content">
                    <div class="dashboard-widget-content">
                      <div class="col-md-12 hidden-small" style="text-align: center;font-size: 30px; min-height: 400px;">
                        <h2 class="line_30"></h2>
                          401 Access Denied
                      </div>                    
                    </div>
                  </div>
                </div>
              </div>

            </div>
          </div>
          </div>
    
@endsection