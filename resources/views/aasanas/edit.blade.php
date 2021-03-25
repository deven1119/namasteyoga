@extends('layout.app')

@section('content')
<div class="right_col" role="main">
  <div class="col-md-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>{{$users->first_name}} {{$users->last_name}} <small>{{$users->email}}</small></h2>

                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br>
                    <form class="form-horizontal form-label-left" action="{{ url('/') }}/users/update/{{$users->id}}" method="POST">
                      {{ csrf_field() }}
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">First name</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input class="form-control" placeholder="First name" type="text" name="first_name" value="{{$users->first_name}}">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Last name</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input class="form-control" placeholder="Last name" type="text" name="last_name" value="{{$users->last_name}}">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Email</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input class="form-control" placeholder="Email" readonly type="text" name="email" value="{{$users->email}}">
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Status</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <select class="form-control" name="is_blocked">
                            <option value="0">--Status--</option>
                            <option value="0" @if ($users->is_blocked==1) selected @endif>Active</option>
                            <option value="1" @if ($users->is_blocked==0) selected  @endif>De-active</option>
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Select Country</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <select class="select2_single form-control" tabindex="-1" name="country_id">
                            @foreach ($country as $k=>$v)
                            <option value="{{$v->id}}" @if ($users->country_id==$v->id) selected @endif>{{$v->name}}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>

                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                          <button type="button" class="btn btn-primary" onclick="location.href='{{ url('/') }}/users'">Cancel</button>
                          <button type="reset" class="btn btn-primary">Reset</button>
                          <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                      </div>

                    </form>
                  </div>
                </div>
              </div>
</div>
@endsection
