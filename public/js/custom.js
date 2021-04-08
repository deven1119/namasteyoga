//poll add more functionality
$(document).ready(function(){
	$('.submitPolls').removeAttr('disabled');
	$('.submitQuiz').removeAttr('disabled');
	$('.submitQuestions').removeAttr('disabled');
	
	$('body').on('click','#addMorebtn',function(){
		let len = $('.dynamicFields').length; 
		let ques_count = len+1;
		let key = parseInt($('#lastKey').val());
		let c = key+1;
		
		
		if(len<=4){
			let html = '<div class="dynamicFields"><div class="form-group"><label class="control-label col-md-3 col-sm-3 col-xs-12 qlabel">Question '+c+'</label><div class="col-md-8 col-sm-8 col-xs-12"><input type="text" name="questions['+key+']" id="question_'+key+'" class="form-control questions"></div><label class="col-md-1 col-sm-1 col-xs-12"><button type="button" id="" class="btn btn-danger deleteRow"><i class="fa fa-trash" aria-hidden="true"></i></button></label></div> <div class="form-group"><label class="control-label col-md-3 col-sm-3 col-xs-12"></label><div class="col-md-9 col-sm-9 col-xs-12"><div class="row"><div class="col-md-6 col-sm-6 col-xs-12"><input type="text" name="options['+key+'][]" class="form-control margin-bottom options_'+key+'" placeholder="Option 1"></div><div class="col-md-6 col-sm-6 col-xs-12"><input type="text" name="options['+key+'][]" class="form-control margin-bottom options_'+key+'" placeholder="Option 2"></div></div></div></div> <div class="form-group"><label class="control-label col-md-3 col-sm-3 col-xs-12"></label><div class="col-md-9 col-sm-9 col-xs-12"><div class="row"><div class="col-md-6 col-sm-6 col-xs-12"><input type="text" name="options['+key+'][]" class="form-control margin-bottom options_'+key+'" placeholder="Option 3"></div><div class="col-md-6 col-sm-6 col-xs-12"> <input type="text" name="options['+key+'][]" class="form-control margin-bottom options_'+key+'" placeholder="Option 4"></div> </div></div></div></div>';
			$('.dynamicFields:last').after(html);
			let k = key+1;
			$('#lastKey').val(k);
		}
		if(ques_count==5)
		$(this).hide();
		
		$('.qlabel').each(function(i,v){
			let j =i+1;
			$(this).text('Question '+j);
		});
	});
	
	$('body').on('click','#addMoreQuestions',function(){
		let len = $('.dynamicFields').length; 
		let ques_count = len+1;
		let key = parseInt($('#lastKey').val()); 
		let c = key+1;
		alert(c);
		if(len<=4){
			let html = '<div class="dynamicFields"><div class="form-group"><label class="control-label col-md-3 col-sm-3 col-xs-12 qlabel">Question '+c+'</label><div class="col-md-8 col-sm-8 col-xs-12"><input type="text" name="questions['+key+']" id="question_'+key+'" class="form-control questions"></div><label class="col-md-1 col-sm-1 col-xs-12"><button type="button" id="" class="btn btn-danger deleteRow"><i class="fa fa-trash" aria-hidden="true"></i></button></label></div> <div class="form-group"><label class="control-label col-md-3 col-sm-3 col-xs-12"></label><div class="col-md-9 col-sm-9 col-xs-12"><div class="row"><div class="col-md-6 col-sm-6 col-xs-12"><input type="text" name="options['+key+'][]" class="form-control margin-bottom options_'+key+'" placeholder="Option 1"></div><div class="col-md-6 col-sm-6 col-xs-12"><input type="text" name="options['+key+'][]" class="form-control margin-bottom options_'+key+'" placeholder="Option 2"></div></div></div></div> <div class="form-group"><label class="control-label col-md-3 col-sm-3 col-xs-12"></label><div class="col-md-9 col-sm-9 col-xs-12"><div class="row"><div class="col-md-6 col-sm-6 col-xs-12"><input type="text" name="options['+key+'][]" class="form-control margin-bottom options_'+key+'" placeholder="Option 3"></div><div class="col-md-6 col-sm-6 col-xs-12"> <input type="text" name="options['+key+'][]" class="form-control margin-bottom options_'+key+'" placeholder="Option 4"></div> </div></div></div></div>';
			$('.dynamicFields:last').after(html);
			let k = key+1;
			$('#lastKey').val(k);
		}
		if(ques_count==5)
		$(this).hide();
		
		$('.qlabel').each(function(i,v){
			let j =i+1;
			$(this).text('Question '+j);
		});
	});
	$('body').on('click','.deleteRow',function(){
		var $this = this;
		$('#confirm').modal({
				  backdrop: 'static',
				  keyboard: false
				}).on('click','#continue1',function(){
					$('#confirm').modal('hide');	
					$($this).closest(".dynamicFields").remove();
					let len = $('.dynamicFields').length;
					if(len<5){
					//$('#addMorebtn').show();
					var url_string = location.href;
						let arr = url_string.split('/');
						let el = arr[arr.length-1];
						if($.isNumeric(el))
						{
							let qCount = parseInt($('#qCount').val());
							if(qCount==5){
								$('.ln_solid').html('<div class="form-group"><a href="javascript:void(0)" id="addMorebtn" class="btn btn-primary pull-right">Add More</a></div><div class="ln_solid"></div>');
							}else
								$('#addMorebtn').show(); 
						}
						else
						$('#addMorebtn').show(); 
					}
					
					$('.qlabel').each(function(i,v){
						let j =i+1;
						$(this).text('Question '+j);
					});
		}).find('.modal-body').html('<p>Are you sure?</p>');
		
	});
	
	$('body').on('click','.submitPolls',function(){
		
		var cond = true;
		var cond1 = true;
		let poll_name = $("input[name=poll_name]").val();
		
		if(poll_name==''){
			$("input[name=poll_name]").css('border','red 1px solid').attr('placeholder','Please enter poll name');
			return false;
		}
		if(poll_name.length>255){
			$("input[name=poll_name]").css('border','red 1px solid').attr('title','Poll name should be greate than 255');
			return false;
		}
		$('.questions').each(function(i,v){
			var ids = $(this).attr('id'); 
			var idArr = ids.split('_');
			var id =  idArr[1];
			if($(this).val()==''){
				$(this).css('border','red 1px solid').attr('placeholder','Please enter question');
				cond = false;
			}
			if($(this).val().length>255){
				$(this).css('border','red 1px solid').attr('title','Question should be greate than 255');
				cond = false;
			}
			$('.options_'+id).each(function(){
				if($(this).val()==''){
					$(this).css('border','red 1px solid').attr('placeholder','Please enter option');
					cond1 = false;
				}
				if($(this).val().length>255){
				$(this).css('border','red 1px solid').attr('title','Option should be greate than 255');
				cond1 = false;
			}
			})
		});
		
		if(cond==false)
			return false;
		if(cond1==false)
			return false;

	});
	$("input[name=poll_name]").focus(function(){
		$(this).css('border','#ccc 1px solid');
	});
	$('.questions').each(function(i,v){
		$(this).focus(function(){
			$(this).css('border','#ccc 1px solid');
		});
		
		$('.options_'+i).each(function(){
			$('.options_'+i).focus(function(){
				$('.options_'+i).css('border','#ccc 1px solid');
			});
		});
	});
	
	
	$(document).on('click','.deletePolls',function(){ 
        let id = $(this).attr('id');
		$('#confirm').modal({
				  backdrop: 'static',
				  keyboard: false
				}).on('click','#continue1',function(){
					$.ajax({
						url:'/polls/destroy',
						type:'POST',
						headers:{
							'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
						},
						data:{id},
						success:(data)=>{
							$('#confirm').modal('hide');	
							table.draw();
							setTimeout(function(){
								$('#msgsection').html('<p id="msgsection" class="alert alert-success">'+data+'</p>');
							},1000);
						   //alert(data);
						   setTimeout(function(){ $('#msgsection').html(''); },10000);
						}
					});
				}).find('.modal-body').html('<p>Are you sure, you want to delete this poll?</p>');
        
    });
	//Poll Module ends here
	
	//Quis module start from here
	$('body').on('click','.submitQuiz',function(){
		let quiz_name = $("input[name=quiz_name]").val();
		let quiz_time = $("input[name=quiz_time]").val();
		let valid_for = $("input[name=valid_for]").val();
		
		if(quiz_name==''){
			$("input[name=quiz_name]").css('border','red 1px solid').attr('placeholder','Please enter quiz name');
			return false;
		}
		if(quiz_name.length>255){
			$("input[name=quiz_name]").css('border','red 1px solid').attr('title','Length of a quiz name should not be greater then 255 characters');
			return false;
		}
		if(quiz_time==''){
			$("input[name=quiz_time]").css('border','red 1px solid').attr('placeholder','Please enter quiz time');
			return false;
		}
		if(isNaN(quiz_time)){
			$("input[name=quiz_time]").css('border','red 1px solid').val('').attr('placeholder','Invlaid input');
			return false;
		}
		if(valid_for==''){
			$("input[name=valid_for]").css('border','red 1px solid').attr('placeholder','Please enter quiz valid for');
			return false;
		}
		if(isNaN(valid_for)){
			$("input[name=valid_for]").css('border','red 1px solid').val('').attr('placeholder','Invlaid input');
			return false;
		}
	});
	$("input[name=quiz_name],input[name=quiz_time],input[name=valid_for]").focus(function(){
		$(this).css('border','#ccc 1px solid').attr('placeholder','')
	});
	//add more questions
	$('body').on('click','#addMoreRows',function(){
		let len = $('.dynamicFields').length; 
		let ques_count = len+1;
		let key = parseInt($('#lastKey').val());
		let c = key+1;
		
		
		if(len<=4){
			let html = '<div class="dynamicFields"><div class="form-group"><label class="control-label col-md-3 col-sm-3 col-xs-12 qlabel">Question '+c+'</label><div class="col-md-8 col-sm-8 col-xs-12"><input type="text" name="questions['+key+']" id="question_'+key+'" class="form-control questions"></div><label class="col-md-1 col-sm-1 col-xs-12"><button type="button" id="" class="btn btn-danger deleteRow"><i class="fa fa-trash" aria-hidden="true"></i></button></label></div> <div class="form-group"><label class="control-label col-md-3 col-sm-3 col-xs-12"></label><div class="col-md-9 col-sm-9 col-xs-12"><div class="row"><div class="col-md-6 col-sm-6 col-xs-12"><input type="text" name="options['+key+'][]" class="form-control margin-bottom options_'+key+'" placeholder="Option 1"></div><div class="col-md-6 col-sm-6 col-xs-12"><input type="text" name="options['+key+'][]" class="form-control margin-bottom options_'+key+'" placeholder="Option 2"></div></div></div></div> <div class="form-group"><label class="control-label col-md-3 col-sm-3 col-xs-12"></label><div class="col-md-9 col-sm-9 col-xs-12"><div class="row"><div class="col-md-6 col-sm-6 col-xs-12"><input type="text" name="options['+key+'][]" class="form-control margin-bottom options_'+key+'" placeholder="Option 3"></div><div class="col-md-6 col-sm-6 col-xs-12"> <input type="text" name="options['+key+'][]" class="form-control margin-bottom options_'+key+'" placeholder="Option 4"></div> </div></div></div></div>';
			$('.dynamicFields:last').after(html);
			let k = key+1;
			$('#lastKey').val(k);
		}
		if(ques_count==5)
		$(this).hide();
		
		$('.qlabel').each(function(i,v){
			let j =i+1;
			$(this).text('Question '+j);
		});
	});
	
	//submit questions
	$('body').on('click','.submitQuestions',function(){
		var cond = true;
		var cond1 = true;
		var cond2 = true
		
		$('.questions').each(function(i,v){
			var ids = $(this).attr('id'); 
			var idArr = ids.split('_');
			var id =  idArr[1];
			if($(this).val()==''){
				$(this).css('border','red 1px solid').attr('placeholder','Please enter question');
				cond = false;
				return false;
			}
			if($(this).val().length>255){
				$(this).css('border','red 1px solid').attr('title','Question should be greate than 255');
				cond = false;
				return false;
			}
			$('.options_'+id).each(function(){
				if($(this).val()==''){
					$(this).css('border','red 1px solid').attr('placeholder','Please enter option');
					cond1 = false;
					return false;
				}
				if($(this).val().length>255){
					$(this).css('border','red 1px solid').attr('title','Option should be greate than 255');
					cond1 = false;
					return false;
				}
			});
			$('.answer_'+id).each(function(){
				if($('.answer_'+id+':checked').length==0){
						cond2 = false;
						return false;
				}
			});
		});		
			if(cond==false)
				return false;
			if(cond1==false)
				return false;
				
			if(cond2==false){
				$('#alertBox').modal({backdrop: 'static',keyboard: false}).find('.modal-body').html('<h5>Please choose correct answer</h5>');
				return false;
			}
	});

	
	setTimeout(function(){ 
		$('.alert-success').hide();
		$('.alert-danger').hide();
	},5000);
	
});
