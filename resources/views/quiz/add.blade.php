@extends('layout.app')

@section('content')
<div class="right_col clearfix" role="main">
  @include('layout/flash')
 
  <div class="col-md-12 col-xs-12">
  @if($errors->any())
    <span class="alert alert-danger">{{ implode('', $errors->all(' :message')) }}</span>
@endif

@if(Session::has('flash_message'))
<p class="alert {{ Session::get('flash_type') }}">{{ Session::get('flash_message') }}</p>
@endif
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Add Questions</h2>

                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br>
                    <form class="form-horizontal form-label-left" action="{{ url('/quiz/storeQuestions')}}" method="POST">
                      {{ csrf_field() }}
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Quiz Name</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input class="form-control"  type="text" name="quiz_name" value="{{$quiz->quiz_name}}" readonly/>
						  <input type="hidden" name="quiz_id" value="{{$quiz->id}}">
                        </div>
                      </div>
					  <div class="dynamicFields">
						  <div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12 qlabel">Question 1</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
							  <input type="text" name="questions[0]" id="question_0" class="form-control questions">
							</div>
						  </div>
						  <div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<div class="row">
									<div class="col-md-1 col-sm-1 col-xs-12">							
									  <input class="form-check-input answer_0" type="radio" name="answer[0][]">								
									</div>
									<div class="col-md-11 col-sm-11 col-xs-12">
									  <input type="text" name="options[0][]" class="form-control options_0" placeholder="Option 1">
									</div>
								</div>
							</div>
						  </div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<div class="row">
									<div class="col-md-1 col-sm-1 col-xs-12">							
									  <input class="form-check-input answer_0" type="radio" name="answer[0][]">								
									</div>
									<div class="col-md-11 col-sm-11 col-xs-12">
									  <input type="text" name="options[0][]" class="form-control options_0" placeholder="Option 2">
									</div>
								</div>
							</div>
						  </div>
						  <div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<div class="row">
									<div class="col-md-1 col-sm-1 col-xs-12">							
									  <input class="form-check-input answer_0" type="radio" name="answer[0][]">								
									</div>
									<div class="col-md-11 col-sm-11 col-xs-12">
									  <input type="text" name="options[0][]" class="form-control options_0" placeholder="Option 3">
									</div>
								</div>
							</div>
						  </div>
						  <div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<div class="row">
									<div class="col-md-1 col-sm-1 col-xs-12">							
									  <input class="form-check-input answer_0" type="radio" name="answer[0][]">								
									</div>
									<div class="col-md-11 col-sm-11 col-xs-12">
									  <input type="text" name="options[0][]" class="form-control options_0" placeholder="Option 4">
									</div>
								</div>
							</div>
						  </div>
					</div>
					<div class="form-group">
						<a href="javascript:void(0)" id="addMoreRows" class="btn btn-primary pull-right">Add More</a>
                      </div>
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                          <button type="button" class="btn btn-primary" onclick="location.href='{{ url('/') }}/quiz'">Cancel</button>
                          <button type="reset" class="btn btn-primary">Reset</button>
                          <button type="submit" class="btn btn-success submitQuestions" disabled="1">Submit</button>
                        </div>
                      </div>
					<input type="hidden" id="lastKey" value="1">
                    </form>
                  </div>
                </div>
              </div>
</div>

@endsection