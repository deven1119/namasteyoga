@extends('layout.app')

@section('content')
<div class="right_col clearfix" role="main">
  @include('layout/flash')
  <div class="col-md-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>View Poll</h2>

                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br>
                    <form class="form-horizontal form-label-left"  method="POST">
                     
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Poll Name</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input class="form-control"  type="text" name="poll_name" value="{{$poll->poll_name}}" readonly>
                        </div>
                      </div>
					  @if($questionData)
					  @foreach($questionData as $k=>$question)
					  <div class="dynamicFields">
						  <div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Question {{$k+1}}</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
							  <input type="text" name="questions[]" class="form-control" value="{{$question['question']}}" readonly>
							</div>
						  </div>
						   <div class="form-group">
						 
						 
							<label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<div class="row">
								 @foreach($question['options'] as $okey=>$option)
									<div class="col-md-6 col-sm-6 col-xs-12">
									  <input type="text" name="options[{{$okey}}][]" class="form-control margin-bottom" value="{{$option->options}}" readonly>
									</div>
								 @endforeach
								</div>
							</div>
						
						  </div>
	                  
						  
					</div>
					@endforeach
					@endif
					
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                          <button type="button" class="btn btn-primary" onclick="location.href='{{ url('/') }}/polls'">Back</button>
                    
                        </div>
                      </div>

                    </form>
                  </div>
                </div>
              </div>
</div>


@endsection