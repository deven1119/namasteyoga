@extends('layout.app')

@section('content')
<div class="right_col" role="main">
  <!-- top tiles -->
  <div class="row tile_count">
    <div class="col-md-3 col-sm-3 col-xs-9 tile_stats_count">
      <span class="count_top"><i class="fa fa-user"></i> Total Users</span>
      <div class="count">{{$trainer+$center}}</div>
      <!-- <span class="count_bottom"><i class="green">4% </i> From last Week</span> -->
    </div>    
    <div class="col-md-3 col-sm-3 col-xs-9 tile_stats_count">
      <span class="count_top"><i class="fa fa-user"></i> Total trainer</span>
      <div class="count green">{{$trainer}}</div>
      <!-- <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>34% </i> From last Year</span> -->
    </div>
    <div class="col-md-3 col-sm-3 col-xs-9 tile_stats_count">
      <span class="count_top"><i class="fa fa-user"></i> Total Events</span>
      <div class="count">{{$event}}</div>
      <!-- <span class="count_bottom"></span> -->
    </div>
    <div class="col-md-3 col-sm-3 col-xs-9 tile_stats_count">
      <span class="count_top"><i class="fa fa-user"></i> Total Yoga Center</span>
      <div class="count green">{{$center}}</div>
      <!-- <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>34% </i> From last Year</span> -->
    </div>

  </div>
  <!-- /top tiles -->
  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="x_panel">
            <div class="x_title">
              <h2>Welcome</h2>
              <div class="clearfix"></div>
            </div>
            <div class="x_content">
              <div class="dashboard-widget-content">
                <div class="col-md-12 hidden-small" style="text-align: center;font-size: 30px; min-height: 400px;">
                  <h2 class="line_30"></h2>
                    Welcome to admin panel of yoga app                  
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
