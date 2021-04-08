@extends('layout.app')

@section('content')
<div class="right_col" role="main">
  @include('layout/flash')
  <div class="col-md-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>View Poll Result</h2>

                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br>
					 <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Poll Name</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input class="form-control"  type="text" name="poll_name" value="{{$poll->poll_name}}" readonly>
                        </div>
                      </div>
					   <br>
					<table class="table-responsive table table-striped table-bordered" style="font-size:12px;width:100% !important">
						<thead>
							<tr>
							<th>Q.No.</th>
							<th>Qestion</th>
							<th colspan="2" width="40%" style="text-align:center">Options</th>
							</tr>
							<tr>
							<th colspan="2" width="60%"></th>
							<th width="30%">Option</th>
							<th width="10%">Result</th>
							</tr>
						</thead>
						<tbody>
						 @if($questionData)
					  @foreach($questionData as $k=>$question)
						<tr>
						<td>{{$k+1}}</td>
						<td>{{$question['question']}}</td>
						<td colspan="2" >
							<table class="table-responsive table table-striped table-bordered">
								<tbody>
									@foreach($question['options'] as $okey=>$option)
										<tr>
											<td width="30%">{{$option['Option']}}</td>
											<td width="10%">{{$option['no_submistion']}}</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</td>
						</tr>
						</tbody>
						@endforeach
						@endif
					</table>
					<div class="form-group">
                        <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                          <button type="button" class="btn btn-primary" onclick="location.href='{{ url('/') }}/polls'">Back</button>
                        </div>
                      </div>
                  </div>
                </div>
              </div>
</div>


@endsection