@extends('layout.app')

@section('content')
<div class="right_col clearfix" role="main">
  @include('layout/flash')
  <div class="col-md-12 col-xs-12">
   @if($errors->any())
    <span class="alert alert-danger">{{ implode('', $errors->all(' :message')) }}</span>
@endif
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Edit Poll</h2>

                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br>
                    <form class="form-horizontal form-label-left" action="{{ url('/') }}/polls/update/{{$poll->id}}" method="POST">
                      {{ csrf_field() }}
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Poll Name</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input class="form-control"  type="text" name="poll_name" value="{{$poll->poll_name}}">
                        </div>
                      </div>
					  @if($questionData)
					  @foreach($questionData as $k=>$question)
					  <div class="dynamicFields">
						  <div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12 qlabel">Question {{$k+1}}</label>
							<div class="col-md-8 col-sm-8 col-xs-12">
							  <input type="text" name="questions[{{$k}}]" id="question_{{$k+1}}" class="form-control questions" value="{{$question['question']}}">
							</div>
							<label class="col-md-1 col-sm-1 col-xs-12"><button type="button"  class="btn btn-danger deleteRow"><i class="fa fa-trash" aria-hidden="true"></i></button></label>
						  </div>
						  
						  <div class="form-group">
							 
									<label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
									 
									<div class="col-md-9 col-sm-9 col-xs-12">
									<div class="row">
									@foreach($question['options'] as $okey=>$option)
										<div class="col-md-6 col-sm-6 col-xs-12">
										  <input type="text" name="options[{{$k}}][]" class="form-control margin-bottom options_{{$k+1}}" value="{{$option->options}}">
										</div>
									@endforeach
									</div>									
								</div>								  
									
						  </div>
						  
						  
					</div>
					@endforeach
					@endif
					
					@if(count($questionData)<5)
					<div class="form-group">
						<a href="javascript:void(0)" id="addMorebtn" class="btn btn-primary pull-right">Add More</a>
                      </div>
					  @endif
					 
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                          <button type="button" class="btn btn-primary" onclick="location.href='{{ url('/') }}/polls'">Cancel</button>
                    
                          <button type="submit" class="btn btn-success submitPolls" disabled>Update</button>
                        </div>
                      </div>
					<input type="hidden" id="lastKey" value="{{count($questionData)}}">
					<input type="hidden" id="qCount" value="{{count($questionData)}}">
                    </form>
                  </div>
                </div>
              </div>
</div>

@endsection