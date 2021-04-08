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
          <table id="usersData" class="table-responsive table table-striped table-bordered" style="font-size:12px;width:100% !important">
              <thead>
                  <tr>
                      <th>Name</th>
                      <th>Phone</th>
                      <th>User Type</th>
                      <th>Email</th>
                      <th>Approved By</th>
                      <th>YCB Number</th>
					  <th>YCB Status</th>
					  <th>Approved Date</th>
                      <th class="noExport">Status</th>
                  </tr>
              </thead>
              <tbody>
                            
              </tbody>
              <tfoot>
                    <tr>                              
                      <th>Name</th>
                      <th>Phone</th>
                      <th>User Type</th>
                      <th>Email</th>
                      <th>Approved By</th>
                      <th>YCB Number</th>
					  <th>YCB Status</th>
					  <th>Approved Date</th>
                      <th class="noExport">Status</th>
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
            { extend: 'pdfHtml5',
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
                        }else{ colCount.push(parseFloat(100 / length)+'%'); }
                    });
              }
            },
            'pageLength'
            ],
            'sPaginationType': "simple_numbers",
            'searching': true,
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
              'url': '{{ url("/") }}/userIndexAjax',
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
                  'className': 'col-md-1',
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
                  'data': 'role_id',
                  'className': 'col-md-1',
                  'render': function(data,type,row){
                    return row.role.role;
                  }
              },
              {
                'data': 'email',
                'className': 'col-md-1'
              },
              {
                'data': 'approved_by',
                'className': 'col-md-2',
                'render': function(data,type,row){
                    var jsonData = JSON.parse(row.approved_by);
					                   
                      return jsonData.name;
                  }              
              },
              {
                'data': 'ycb_number',
                'className': 'col-md-2',
                'render': function(data,type,row){
                    return row.ycb_number;
                  }              
              },
              {
                'data': 'ycb_approved',
                'className': 'col-md-1',
                'render': function(data,type,row){
					
                    if(row.ycb_approved=='1'){
						return 'Approved';
					}else if(row.ycb_approved=='0'){
						return 'Pending';
					}else{
						return 'Rejected';
					}
                  }              
              },
              {
                'data': 'ApproveDate',
                'className': 'col-md-2',
                'render': function(data,type,row){
					var jsonData = JSON.parse(row.approved_by);

                    //return (jsonData.approvedDate=='0000-00-00 00:00:00') ? '' : jsonData.approvedDate;
                    if(jsonData.hasOwnProperty('approvedDate')){
                        return (jsonData.approvedDate=='0000-00-00 00:00:00') ? '' : jsonData.approvedDate;
                    } else {
                        return '';
                    }
                }
              },
              {
                'data': 'Status',
                'className': 'col-md-1',
                'render': function(data,type,row){
                    var html = '';       
                    @if(Auth::user()->role_id==1)             
                    if(row.status=='1'){
                      html = '<i onclick="changeStatus('+row.id+',0)" class="fa fa-toggle-on" style="color:green;font-size:20px"></i>';
                    }else{
                      html = '<i onclick="changeStatus('+row.id+',1)" class="fa fa-toggle-off" style="color:red;font-size:20px"></i>';
                    }   
                    @else
                        html = (row.status=='1') ? 'Active' : 'Deactive';      
                    @endIf              
                    return html;
                  }  
              }            
              // {
              //   'data': 'Action',
              //   'className': 'col-md-2',
              //   'render': function(data, type, row) {
              //     var buttonHtml = '<button type="button" data-id="' + row.id + '" class="btn btn-success update_user roleActionHTML user_addupdateuser" data-toggle="modal" data-target="#userModel" data-whatever="@mdo"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button> <button type="button" id="' + row.id + '" class="btn btn-danger delete_user roleActionHTML user_deleteUser"><i class="fa fa-trash" aria-hidden="true"></i></button>';
              //     return buttonHtml;
              //   }
              // }
            ]
          });   
              
          
        });

        

        jQuery("body").on("click", ".delete_user", function() {
          var id = jQuery(this).attr("id");

          $.confirm({
              title: '',
              content: 'Are you sure want to delete this user?',
              buttons: {
                  confirm: function () {
                    jQuery.ajax({
                      url: '/users/deleteUser/',
                      type: "POST",
                      data: {
                        "id": id
                      },
                      success: function(response) {
                        if (response["affectedRows"] == 1) {
                          jQuery("#userFilter").trigger("change");
                          table.draw();
                        } else {
                          jQuery.alert({
                            title: "",
                            content: 'Problem in deleted',
                          });
                        }
                        return false;
                      },
                      error: function() {
                        jQuery.alert({
                          title: "",
                          content: 'Technical error',
                        });
                      }
                    });
                  },
                  cancel: function () {
                      return true;
                  }
              }
          });
        });

        function changeStatus(userid,val){
          jQuery.ajax({
              url: '/users/changestatus',
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
    </script>
      
<style>
    .dataTables_paginate a {
        background-color:#fff !important;
    }
    .dataTables_paginate .pagination>.active>a {
        color: #fff !important;
        background-color: #337ab7 !important;
    }
</style>

@endsection