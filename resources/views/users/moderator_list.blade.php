@extends('layout.app')
<meta name="csrf-token" content="{{ csrf_token() }}">
@section('content')
<div class="right_col" role="main">
  @include('layout/flash')
  <div class="x_panel">
      <div class="x_title">
        <h2>Moderator List</h2>

        <div class="clearfix"></div>
      </div>
      <div class="x_content">  
      {{ csrf_field() }} 
      <div class="pull-right">
            <form class="form-horizontal form-label-left">
                <select class="form-control" id="filterByModerator">
                    <option value="">Moderator</option>
                    @foreach($moderators as $moderator)
                    <option value="{{ $moderator->id}}">{{ $moderator->moderator}}</option>
                    @endforeach
                </select>
                
                
            </form>
        </div> 
                     
          <table id="usersData" class="table-responsive table table-striped table-bordered" style="font-size:12px;width:100% !important">
              <thead>
                  <tr>
                      <th>Name</th>
                      <th>Phone</th>                                          
                      <th>Email</th>                                          
                      <th>User Type</th>                                          
                      <th>Reset password & notify</th>
                      <th>Status</th>
                  </tr>
              </thead>
              <tbody>
            
              </tbody>
              <tfoot>
                    <tr>                              
                      <th>Name</th>
                      <th>Phone</th>                                          
                      <th>Email</th>    
                      <th>User Type</th>                                         
                      <th>Reset password & notify</th>
                      <th>Status</th>
                  </tr>
              </tfoot>
          </table>                              
        </div>
</div>

          
<script>
        
        var table = '';

        jQuery(document).ready(function() {
          
					//var permissonObj = '<%-JSON.stringify(permission)%>';
					//permissonObj = JSON.parse(permissonObj);
                    
                    //[10, 25, 50, -1], [10, 25, 50, "All"]

          table = jQuery('#usersData').DataTable({
            'processing': true,
            'serverSide': true,                        
            'lengthMenu': [
              [10, 25, 50], [10, 25, 50]
            ],
            dom: 'Bfrtip',
            buttons: [
            'csvHtml5',
            /* hide pdf download button
             * {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: "thead th:not(.noExport)"
                },
                customize : function(doc){
                    var colCount = new Array();
                    var length = $('#reports_show tbody tr:first-child td').length;
                    //console.log('length / number of td in report one record = '+length);
                    $('#reports_show').find('tbody tr:first-child td').each(function(){
                        if($(this).attr('colspan')){
                            for(var i=1;i<=$(this).attr('colspan');$i++){
                                colCount.push('*');
                            }
                        } else {
                            colCount.push(parseFloat(100 / length)+'%');
                        }
                    });
                }
            },*/
            'pageLength'
            ],
            'sPaginationType': "simple_numbers",
            //'searching': true,
            "bSort": false,
            "fnDrawCallback": function (oSettings) {
              jQuery('.popoverData').popover();
              // if(jQuery("#userTabButton").parent('li').hasClass('active')){
              //   jQuery("#userTabButton").trigger("click");
              // }
              // jQuery("#userListTable_wrapper").removeClass( "form-inline" );
            },
            'fnRowCallback': function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
              //if (aData["status"] == "1") {
                //jQuery('td', nRow).css('background-color', '#6fdc6f');
              //} else if (aData["status"] == "0") {
                //jQuery('td', nRow).css('background-color', '#ff7f7f');
              //}
              //jQuery('.popoverData').popover();
            },
						"initComplete": function(settings, json) {						
              //jQuery('.popoverData').popover();
					  },
            'ajax': {
              'url': '{{ url("/") }}/users/moderatorIndexAjax',
              'headers': {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
              },
              'type': 'post',
              'data': function(d) {
                //d.userFilter = jQuery('#userFilter option:selected').text();
                //d.search = jQuery("#userListTable_filter input").val();
              },
            },          

            'columns': [
                
              {
                  'data': 'name',
                  'className': 'col-md-3',
                  'render': function(data,type,row){
                    var name = (row.name.length > 30) ? row.name.substring(0,30)+'...' : row.name;
                    return '<a class="popoverData" data-content="'+row.name+'" rel="popover" data-placement="bottom" data-original-title="Name" data-trigger="hover">'+name+'</a>';
                  }
              },
              {
                  'data': 'phone',
                  'className': 'col-md-1'
              },
              {
                'data': 'email',
                'className': 'col-md-1'
              },
              {
                'data': 'usertype',
                'className': 'col-md-2'
              },
              {
                'data': 'ResetPassword',
                'className': 'col-md-1',
                render: function(data,type,row){
                  return '<button type="button" class="btn btn-primary" onclick="resetPassword('+row.id+')">Reset Password</button>';  
                }
                
              },
              {
                'data': 'Status',
                'className': 'col-md-1',
                'render': function(data,type,row){
                    var html = '';                    
                    if(row.status=='1'){
                      html = '<i onclick="changeStatus('+row.id+',0)" class="fa fa-toggle-on" style="color:green;font-size:20px"></i>';
                    }else{
                      html = '<i onclick="changeStatus('+row.id+',1)" class="fa fa-toggle-off" style="color:red;font-size:20px"></i>';
                    }                    
                    return html;
                  }  
              }            
              
            ]
          });   
          $('#filterByModerator').on('change',function() {  
            table.columns(3).search( this.value).draw(); 
           });
           //$('#usersData_filter').hide();
          
        });

        
        
        function changeStatus(userid,val){
          jQuery.ajax({
              url: '{{ url("/") }}/users/changemodratorstatus',
              type: "POST",
              headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
              },
              data: {
                "status": val,"userid": userid
              },
              success: function(response) {
                //response = JSON.parse(response);                
                if(response.status){
                  alert(response.message);
                  table.draw();
                }else{
                  alert('Technical Error!!');
                }
              },
              error: function() {
                alert('Error!');
              }
            });
        }

        function resetPassword(userid){
          if(confirm('Are you sure want to change password for this user?')){
            jQuery.ajax({
              url: '{{ url("/") }}/users/resetModeratorPassword',
              type: "POST",
              headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
              },
              data: {
               "userid": userid
              },
              success: function(response) {
                //response = JSON.parse(response);                
                if(response.status){
                  alert(response.message);
                  table.draw();
                }else{
                  alert('Technical Error!!');
                }
              },
              error: function() {
                alert('Error!');
              }
            });  
          }else{
            return false;
          }
          
        }

        function ValidateEmail(email){
          if(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email)) {
            return true;
          }
          return false;
        }

        //buttons-pdf
        
        
    </script>
      
<style>
    .dataTables_paginate a {
        background-color:#fff !important;
    }
    .dataTables_paginate .pagination>.active>a{
        color: #fff !important;
        background-color: #337ab7 !important;
    }
</style>

@endsection
