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
                    <h2>Add Quiz</h2>

                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br>
                    <form class="form-horizontal form-label-left" action="{{ route('quiz.store')}}" method="POST">
                      {{ csrf_field() }}
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Quiz Name <span class="red">*</span></label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input class="form-control" placeholder="Quiz Name" type="text" name="quiz_name">
                        </div>
                      </div>
					  
					<div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Quiz Time (In Seconds) <span class="red">*</span></label>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                          <input class="form-control" placeholder="Quiz Time" type="text" name="quiz_time">
                        </div>
                      </div>
					<div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Valid For (In Days) <span class="red">*</span></label>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                          <input class="form-control" placeholder="Valid For" type="text" name="valid_for">
                        </div>
                      </div>
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                          <button type="button" class="btn btn-primary" onclick="location.href='{{ url('/') }}/quiz'">Cancel</button>
                          <button type="reset" class="btn btn-primary">Reset</button>
                          <button type="submit" class="btn btn-success submitQuiz" disabled>Submit</button>
                        </div>
                      </div>
					
                    </form>
                  </div>
                </div>
              </div>
</div>

<script>
/* $(document).ready(function(){
	$('body').on('click','#addMorebtn',function(){
		let len = $('.dynamicFields').length; 
		let ques_count = len+1;
		let key = parseInt($('#lastKey').val());
		if(len<=4){
			let html = '<div class="dynamicFields"><div class="form-group"><label class="control-label col-md-3 col-sm-3 col-xs-12">Question '+ques_count+'</label><div class="col-md-8 col-sm-8 col-xs-12"><input type="text" name="questions['+key+']" id="question_'+ques_count+'" class="form-control"></div><label class="control-label col-md-1 col-sm-1 col-xs-12"><button type="button" id="" class="btn btn-danger deleteRow"><i class="fa fa-trash" aria-hidden="true"></i></button></label></div> <div class="form-group"><label class="control-label col-md-3 col-sm-3 col-xs-12"></label><div class="col-md-4 col-sm-4 col-xs-12"><input type="text" name="options['+key+'][]" class="form-control" placeholder="Option 1"></div><div class="col-md-4 col-sm-4 col-xs-12"><input type="text" name="options['+key+'][]" class="form-control" placeholder="Option 2"></div></div> <div class="form-group"><label class="control-label col-md-3 col-sm-3 col-xs-12"></label><div class="col-md-4 col-sm-4 col-xs-12"><input type="text" name="options['+key+'][]" class="form-control" placeholder="Option 3"></div><div class="col-md-4 col-sm-4 col-xs-12"> <input type="text" name="options['+key+'][]" class="form-control" placeholder="Option 4"></div></div></div>';
			$('.dynamicFields:last').after(html);
			let k = key+1;
			$('#lastKey').val(k);
		}
		if(ques_count==5)
		$(this).hide();
	});
	
	$('body').on('click','.deleteRow',function(){
		if(confirm('Are you sure?')==true){
			$(this).closest(".dynamicFields").remove();
				
			
			let len = $('.dynamicFields').length;
			if(len<5)
			$('#addMorebtn').show();
		}
		
	});
	
}); */
</script>
@endsection