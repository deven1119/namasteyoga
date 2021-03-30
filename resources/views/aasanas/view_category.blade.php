@extends('layout.app')

@section('content')
<div class="right_col" role="main">
  @include('layout/flash')
  <div class="col-md-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>View Aasana Category</h2>

                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br>
                    <form class="form-horizontal form-label-left"  method="POST">
                     
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Category Name</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input class="form-control"  type="text" name="category_name" value="{{$viewcategory->category_name}}" readonly>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Category Description</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">                         
                          <textarea class="form-control" rows="3" name="category_description" readonly>{{$viewcategory->category_description}}</textarea>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Category Image</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                        <a href="{{ asset('images/aasana/' . $viewcategory->category_image) }}" target="_blank"><img src="{{ asset('images/aasana'.'/'.$viewcategory->category_image ?? '') }}" alt=""/></a>
                        </div>
                      </div>
                     <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                          <button type="button" class="btn btn-primary" onclick="location.href='{{ url('/') }}/aasana/listcategory'">Back</button>
                    
                        </div>
                      </div>

                    </form>
                  </div>
                </div>
              </div>
</div>


@endsection