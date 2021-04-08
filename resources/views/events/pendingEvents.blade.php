@extends('layout.app')
<meta name="csrf-token" content="{{ csrf_token() }}">
@section('content')
<div class="right_col" role="main">
  @include('layout/flash')
  <div class="x_panel">
      <div class="x_title">
        <h2>Pending Event List</h2>

        <div class="clearfix"></div>
      </div>
      <div class="x_content">     
          {{ csrf_field() }}             
          <table id="eventsData" class="table-responsive table table-striped table-bordered" style="font-size:12px;width:100% !important">
              <thead>
                  <tr>
                      
                      <th>Event Name</th>
                      <th>Phone</th>
                      <th>Contact Person</th>
                      <th>City/State/Country</th>
                      <th>Email</th>
                      <th>Address</th>                      
                      <th>Start Date</th>
                      <th>End Date</th>
                      <th>Created Date</th> 
                      <th class="noExport">Status</th>
                      <th>Sitting</th> 
                  </tr>
              </thead>
              <tbody>
                            
              </tbody>
              <tfoot>
                    <tr>                              
                    <th>Event Name</th>
                      <th>Phone</th>
                      <th>Contact Person</th>
                      <th>City/State/Country</th>
                      <th>Email</th>
                      <th>Address</th>      
                      <th>Start Date</th>
                      <th>End Date</th>              
                      <th>Created Date</th>   
                      <th class="noExport">Status</th>
                      <th>Sitting</th> 
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


          table = jQuery('#eventsData').DataTable({
            'processing': true,
            'serverSide': true,                        
            'lengthMenu': [
              [10, 25, 50, -1], [10, 25, 50]
            ],
            dom: 'Bfrtip',
            buttons: [                        
            {extend:'csvHtml5',
              exportOptions: {
                columns: [0, 1, 2, 3,4,5,6,7,8,9]//"thead th:not(.noExport)"
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
              'url': '{{ url("/") }}/events/pendingEventIndexAjax',
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
                  'data': 'event_name',
                  'className': 'col-md-2',
                  'render': function(data,type,row){
                    var event_name = (row.event_name.length > 30) ? row.event_name.substring(0,30)+'...' : row.event_name;
                    return '<a class="popoverData" data-content="'+row.event_name+'" rel="popover" data-placement="bottom" data-original-title="Name" data-trigger="hover">'+event_name+'</a>';
                  }
              },
              {
                  'data': 'contact_no',
                  'className': 'col-md-1'
              },
              {
                  'data': 'contact_person',
                  'className': 'col-md-1',
                  'render': function(data,type,row){
                    return row.event_name;
                  }
              },
              {
                'data': 'City/State/Country',
                'className': 'col-md-2',
                'render': function(data,type,row){
                    return row.city.name+'/'+row.state.name+'/'+row.country.name;
                  }
              },
              {
                'data': 'email',
                'className': 'col-md-1'
              },
              {
                'data': 'Address',
                'className': 'col-md-2',
                'render': function(data,type,row){
                    var address = (row.address.length > 30) ? row.address.substring(0,30)+'...' : row.address;
                    
                    return '<a class="popoverData" data-content="'+row.address+'" rel="popover" data-placement="bottom" data-original-title="Address" data-trigger="hover">'+address+'</a>';
                  }              
              },
              {
                'data': 'start_time',
                'className': 'col-md-1'           
              },
              {
                'data': 'end_time',
                'className': 'col-md-1'           
              },
              {
                'data': 'created_at',
                'className': 'col-md-1'           
              },
              {
                'data': 'Status',
                'className': 'col-md-1',
                'render': function(data,type,row){
                    var html = '';
                    @if(Auth::user()->role_id==4)
                      if(row.status==1){
                        html = '<i class="fa fa-toggle-on" onclick="changeStatus('+row.id+',0)" style="color:green;font-size:20px;"></i>';
                      }else{
                        html = '<i class="fa fa-toggle-off" onclick="changeStatus('+row.id+',1)" style="color:red;font-size:20px;"></i>';
                      }  
                    @else
                        html = (row.status=='1') ? 'Active' : 'Deactive';   
                    @endIf             
                    return html
                  }  
              },
              {
                'data': 'sitting_capacity',                
                "bVisible":false                
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

        
        function ValidateEmail(email){
          if(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email)) {
            return true;
          }
          return false;
        }

        function changeStatus(eventid,val){
          jQuery.ajax({
              url: '/events/changestatus',
              type: "POST",
              headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
              },
              data: {
                "status": val,"eventid": eventid
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
        .dataTables_paginate .pagination>.active>a{
          color: #fff !important;
          background-color: #337ab7 !important;
        }
      </style>

@endsection
