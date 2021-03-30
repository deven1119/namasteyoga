@extends('layout.app')

@section('content')
<div class="right_col" role="main">
@include('layout/errors')
  <div class="col-md-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Edit Aasana Category</h2>

                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br>
                    <form class="form-horizontal form-label-left" action="{{ url('/') }}/aasana/updatecategory/{{$editcategory->id}}" method="POST" enctype="multipart/form-data">
                      {{ csrf_field() }}
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Category Name</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input class="form-control"  type="text" name="category_name" value="{{$editcategory->category_name}}">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Category Description</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <!--<input class="form-control"  type="text" name="category_description" value="{{$editcategory->category_description}}">-->
                          <textarea class="form-control" rows="3" name="category_description">{{$editcategory->category_description}}</textarea>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Category Image</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input class="form-control"  type="file" name="category_image" value="{{$editcategory->category_image}}">
                          <a href="{{ asset('images/aasana/' . $editcategory->category_image) }}" target="_blank"><img src="{{ asset('images/aasana/' . $editcategory->category_image) }}"/></a></br>                         
                        </div>
                      </div>

					 
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                          <button type="button" class="btn btn-primary" onclick="location.href='{{ url('/') }}/aasana/listcategory'">Cancel</button>                    
                          <button type="submit" class="btn btn-success">Update</button>
                        </div>
                      </div>				
                    </form>
                  </div>
                </div>
              </div>
</div>

@endsection