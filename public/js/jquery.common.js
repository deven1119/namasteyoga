var deleteCountry = function(id){
  $.confirm({
      title: 'Confirm!',
      content: 'Are you sure want to permanently delete this country?',
      buttons: {
          confirm: function () {
              location.href=SITEURL+'/country/destroy/'+id;
              return true;
          },
          cancel: function () {
              return true;
          }
      }
  });
};
var deleteCategory = function(id){
  $.confirm({
      title: 'Confirm!',
      content: 'Are you sure want to permanently delete this category?',
      buttons: {
          confirm: function () {
              location.href=SITEURL+'/category/destroy/'+id;
              return true;
          },
          cancel: function () {
              return true;
          }
      }
  });
};
var deleteFeature = function(id){
  $.confirm({
      title: 'Confirm!',
      content: 'Are you sure want to permanently delete this package features?',
      buttons: {
          confirm: function () {
              location.href=SITEURL+'/features/destroy/'+id;
              return true;
          },
          cancel: function () {
              return true;
          }
      }
  });
};
var deleteDiscountType = function(id){
  $.confirm({
      title: 'Confirm!',
      content: 'Are you sure want to permanently delete this discount type?',
      buttons: {
          confirm: function () {
              location.href=SITEURL+'/discounttypes/destroy/'+id;
              return true;
          },
          cancel: function () {
              return true;
          }
      }
  });
};
var deletePlan = function(id){
  $.confirm({
      title: 'Confirm!',
      content: 'Are you sure want to delete this plan?',
      buttons: {
          confirm: function () {
              location.href=SITEURL+'/plan/destroy/'+id;
              return true;
          },
          cancel: function () {
              return true;
          }
      }
  });
};
var deletePressrelease = function(id){
  $.confirm({
      title: 'Confirm!',
      content: 'Are you sure want to delete this pressrelease?',
      buttons: {
          confirm: function () {
              location.href=SITEURL+'/pressrelease/destroy/'+id;
              return true;
          },
          cancel: function () {
              return true;
          }
      }
  });
};
var deleteUser = function(id){
  $.confirm({
      title: 'Confirm!',
      content: 'Are you sure want to delete this user?',
      buttons: {
          confirm: function () {
              location.href=SITEURL+'/users/destroy/'+id;
              return true;
          },
          cancel: function () {
              return true;
          }
      }
  });
};

var deleteCompany = function(id){
  $.confirm({
      title: 'Confirm!',
      content: 'Are you sure want to delete this company?',
      buttons: {
          confirm: function () {
              location.href=SITEURL+'/company/destroy/'+id;
              return true;
          },
          cancel: function () {
              return true;
          }
      }
  });
};
var deleteCoupon = function(id){
  $.confirm({
      title: 'Confirm!',
      content: 'Are you sure want to delete this Coupon?',
      buttons: {
          confirm: function () {
              location.href=SITEURL+'/coupons/destroy/'+id;
              return true;
          },
          cancel: function () {
              return true;
          }
      }
  });
};

var deletePage = function(id){
  $.confirm({
      title: 'Confirm!',
      content: 'Are you sure want to delete this Page?',
      buttons: {
          confirm: function () {
              location.href=SITEURL+'/pages/destroy/'+id;
              return true;
          },
          cancel: function () {
              return true;
          }
      }
  });
};

var deleteTestimonial = function(id){
  $.confirm({
      title: 'Confirm!',
      content: 'Are you sure want to delete this Testimonial?',
      buttons: {
          confirm: function () {
              location.href=SITEURL+'/testimonials/destroy/'+id;
              return true;
          },
          cancel: function () {
              return true;
          }
      }
  });
};

var deleteBanner = function(id){
  $.confirm({
      title: 'Confirm!',
      content: 'Are you sure want to delete this Banner?',
      buttons: {
          confirm: function () {
              location.href=SITEURL+'/banners/destroy/'+id;
              return true;
          },
          cancel: function () {
              return true;
          }
      }
  });
};


var deleteAmbulance = function(id){
  $.confirm({
      title: 'Confirm!',
      content: 'Are you sure want to delete this Ambulance?',
      buttons: {
          confirm: function () {
              location.href=SITEURL+'/ambulance/destroy/'+id;
              return true;
          },
          cancel: function () {
              return true;
          }
      }
  });
};


/* pressrelease search filter */
$('#status').change(function(){
    if($(this).val() == '-1'){
        location.href=SITEURL+'/pressrelease';
    }else{
        $('#pr_form').submit();
    }
});
$('#filter').change(function(){
    if($(this).val() == '-1'){
        location.href=SITEURL+'/discount';
    }else{
        $('#discount_form').submit();
    }
});

/* pressrelease restrict category selection */
$('.pr_category_id').click(function(){
    var limit = $(this).attr('max-selected');
    var check = $("input[class=pr_category_id]:checkbox:checked").length;
    $('#remaining_cat').html(parseInt(limit) - parseInt(check));
    if(check == 5){
        $('.remaining_cat').css('color','red');
    }else{
        $('.remaining_cat').css('color','black');
    }
    if(check >= 5){
        $('.pr_category_id').not(':checked').each(function(){
            $(this).attr('disabled', 'disabled');
        });
    }else{

        $('.pr_category_id').not(':checked').each(function(){
            $(this).attr('disabled', false);
        });
    };
});

/* pressrelease plan type chane option */
$('#pr_type_id').change(function(){
    var type = $(this).val();
    if(type == 'local_distribution'){
        $('#pr_country, #pr_state').removeClass('hide').show();
        $('#pr_country_id').trigger('change');
    }else if(type == 'regional_distribution'){
        $('#pr_country, #pr_state').removeClass('hide').show();
        $('#pr_country_id').trigger('change');
    }else{
        $('#pr_country, #pr_state, .remaining_state').addClass('hide').hide();
        $('#remaining_state').empty();
    }
});

/* pressrelease country change option */
$('#pr_country_id').change(function(){
    console.log(SITEURL);
    var innerHTML = stateData = checked = '';
    var stateList = new Array();
    var cid = $(this).val();
    var st = $(this).attr('state-list');
    var table = '';
    if(st!=''){
        stateList = st.split(',');
    }
    var type = $('#pr_type_id').val();
    if(type == 'local_distribution' || type == 'regional_distribution'){
        var pt = '&type='+type+'&auth='+$('#user_id').val();
    }
    if(type == 'local_distribution'){
        table = 'states';
    }else if(type == 'regional_distribution'){
        table = 'regions';
    }
    var alreadyFilled = maxallow = 0;
    $.ajax({
            type: 'GET',
            url: FRONTURL+'ajax.php?act='+table+'&id='+cid+pt,
            data: { format: 'json'},
            error: function() { $('#pr_state').html('<p>An error has occurred</p>');},
            success: function(data) {
                stateData = JSON.parse(data);
                if(stateData.states.length > 0){
                    $.each(stateData.states, function(i, item) {
                        checked = '';
                        if($.inArray(item.id,stateList)!=-1){
                            checked = 'checked = "checked"';
                            alreadyFilled++;
                        }
                        maxallow = parseInt(stateData.maxallow) + alreadyFilled;
                        if(table == 'states'){
                            innerHTML += '<div class="col-md-4"><input type="checkbox" name="pr_state_id[]" class="pr_state_id" max-selected="'+maxallow+'" value="'+item.id+'"' +checked+' /> <label>'+item.name+'</label></div>';
                        }else{
                            innerHTML += '<div class="col-md-12"><input type="checkbox" name="pr_region_id[]" class="pr_region_id" max-selected="'+maxallow+'" value="'+item.id+'"' +checked+' /> <label>'+item.name+'</label></div>';
                        }

                     });
                }else{
                    innerHTML = '<div class="col-md-12 text-center" style="color:red;"><b>No states available with selected country..!!</b></div>';
                }
                $('.remaining_state').removeClass('hide').show();
                $('#remaining_state').html(stateData.maxallow);
                $('#states').empty().html(innerHTML);
            },
        });
});

/* pressrelease company change option */
$('#company').change(function(){
    if($('#pr_company_id').val() == -1){
        var cname = $(this).val();
        $.ajax({
                type: 'GET',
                async: false,
                url: FRONTURL+'ajax.php?act=cexists&name='+cname,
                data: { format: 'json'},
                beforeSend: function(){ $('#company_name_loading').removeClass('hide').show(); },
                error: function() { $('#company_name_loading').html('<p>An error has occurred</p>');},
                success: function(data) {
                    var res = JSON.parse(data);
                    if(res.count == 1){
                        errExists = true;
                        $('#company_name_exits_msg').removeClass('hide').show();
                    }else{
                        errExists = false;
                        $('#company_name_exits_msg').addClass('hide').hide();
                    }
                },
                complete : function(){ $("#company_name_loading").addClass('hide').hide(); },
            });
    }
});

/* pressrelease get company details option */
$('#pr_company_id').change(function(){
    var cid = $(this).val();
    var companyData =null;
    if(cid > 0){
        $.ajax({
                type: 'GET',
                async: false,
                url: FRONTURL+'ajax.php?act=company&id='+cid,
                data: { format: 'json'},
                beforeSend: function(){ $('#company_loading').removeClass('hide').show(); },
                error: function() { $('#pr_state').html('<p>An error has occurred</p>');},
                success: function(data) {
                    companyData = JSON.parse(data);
                    $('#company').attr('disabled',true).val(companyData.company);
                    $('#name').attr('disabled','disabled').val(companyData.name);
                    $('#address1').attr('disabled','disabled').val(companyData.address1);
                    $('#address2').attr('disabled','disabled').val(companyData.address2);
                    $('#phone').attr('disabled','disabled').val(companyData.phone);
                    $('#email').attr('disabled','disabled').val(companyData.email);
                    $('#city').attr('disabled','disabled').val(companyData.city);
                    $('#state').attr('disabled','disabled').val(companyData.state);
                    $('#country_id').attr('disabled','disabled').val(companyData.country_id);
                    $('#website_url').attr('disabled','disabled').val(companyData.website_url);
                },
                complete : function(){ $("#company_loading").addClass('hide').hide(); },
            });
    }else{
        console.log(cid);
        $('#company, #name, #address1, #address2, #phone, #email, #city, #state, #website_url').attr('disabled',false).val('');
        $('#country_id').attr('disabled',false).val('-1');
    }
});

/* pressrelease verify_site url */
$('#verify_site').click(function(){
    var surl = null;
    var url = $('#website_url').val()
    var http = 'http://';
    var https = 'https://';
    if ((url.substr(0, http.length) !== http) && (url.substr(0, https.length) !== https)){
        surl = https + url;
    }else{
        surl = url;
    }
    window.open(surl, '_blank');
});

/* pressrelease auto-triggering events */
$('#pr_type_id').trigger('change');
var editCompany = $('#edit_company_id').val();
if(editCompany >0){
    $('#pr_company_id').val(editCompany).trigger('change');
}

/* pressrelease restrict states selection */
jQuery(document).on('click', '.pr_state_id', function () {
    var limit = $(this).attr('max-selected');
    var check = $("input[class=pr_state_id]:checkbox:checked").length;
    $('#remaining_state').html(parseInt(limit) - parseInt(check));
    if(check == limit){
        $('.remaining_state').css('color','red');
    }else{
        $('.remaining_state').css('color','black');
    }
    if(check >= limit){
        $('.pr_state_id').not(':checked').each(function(){
            $(this).attr('disabled', 'disabled');
        });
    }else{
        $('.pr_state_id').not(':checked').each(function(){
            $(this).attr('disabled', false);
        });
    };
});
/* pressrelease restrict regions selection */
jQuery(document).on('click', '.pr_region_id', function () {
    var limit = $(this).attr('max-selected');
    var check = $("input[class=pr_region_id]:checkbox:checked").length;
    $('#remaining_state').html(parseInt(limit) - parseInt(check));
    if(check == limit){
        $('.remaining_state').css('color','red');
    }else{
        $('.remaining_state').css('color','black');
    }
    if(check >= limit){
        $('.pr_region_id').not(':checked').each(function(){
            $(this).attr('disabled', 'disabled');
        });
    }else{
        $('.pr_region_id').not(':checked').each(function(){
            $(this).attr('disabled', false);
        });
    };
});

/* pressrelease characters limitation */
$('.character_limit').keyup(function(){
    var attribute = $(this).attr('allow-characters');
    var config = attribute.split('-');
    var allow_characters = config[0];
    var input_val = $(this).val();
    var input_elem = parseInt(input_val.length);
    var remaining = allow_characters - input_elem;
    if(remaining <= 0){
        remaining = 0;
    }
    if(input_elem > allow_characters){
        jQuery(this).val(input_val.substring(0, allow_characters));
    }
    jQuery('#character-'+config[1]).html('<i>Remaining Character - '+ remaining + ' [Allowed Character Limit: '+allow_characters+']</i>');
});


/* pressrelease list preview */
var $modal = $('#preview_modal');
$('.preview').on('click', function(){
    var id = $(this).attr('rel');
    $modal.load(FRONTURL+'pressrelease.php?act=preview',{'id': id},
    function(){
        $modal.modal('show');
    });
});

var $modal_add = $('#preview_modal_add');
$('.preview_add').on('click', function(){
    var disabledData = '';
    var bodyData = CKEDITOR.instances['body'].getData();
    $('[disabled]').each(function(i) {
        disabledData += '&'+$(this).attr("name")+'='+$(this).val();
    });
    var formData = $('#prForm :input:not(:hidden)').serialize() +'&body='+bodyData+disabledData;
    $.ajax({
        type: 'POST',
        url: FRONTURL+'ajax.php?act=pr_preview',
        data: formData,
        success: function(data) {
            console.log(data);
        },
    });
    $modal_add.load(FRONTURL+'pressrelease.php?act=preview_add',null,
    function(){
        $modal_add.modal('show');
    });
});

$('#datetimepicker4').datetimepicker();
/*
$.alert({
    title: 'Alert!',
    content: 'Please provide valid email address!',
});
*/

var deleteCompany = function(id){
  $.confirm({
      title: 'Confirm!',
      content: 'Are you sure want to permanently delete this company?',
      buttons: {
          confirm: function () {
              location.href=SITEURL+'/company/destroy/'+id;
              return true;
          },
          cancel: function () {
              return true;
          }
      }
  });
};

function changeSlug(obj, targetId){
  //console.log(obj.value);
  var str = obj.value;
  var target = str.toLowerCase().replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '-');
  $('#'+targetId).val(target);
}
