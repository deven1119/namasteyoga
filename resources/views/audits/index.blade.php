@extends('layout.app')
<meta name="csrf-token" content="{{ csrf_token() }}">
@section('content')
<div class="right_col" role="main">
  @include('layout/flash')
  <div class="x_panel">
      <div class="x_title">
        <h2>Activity List</h2>

        <div class="clearfix"></div>
      </div>
      <div class="x_content">     
          {{ csrf_field() }}             
          <table id="auditsData" class="table-responsive table table-striped table-bordered" style="font-size:12px;width:100% !important">
              <thead>
                  <tr>
                      
                      <th>IP</th>
                      <th>Source</th>
                      <th>Username</th>
                      <th>Session</th>
                      <th>Referer</th>
                      <th>ProcessID</th>                      
                      <th>URL</th>
                      <th>User Agent</th> 
                      <th>Country</th> 
                      <th>DateTime</th> 
                  </tr>
              </thead>
              <tbody>
                            
              </tbody>
              <tfoot>
                    <tr>                              
                      <th>IP</th>
                      <th>Source</th>
                      <th>Username</th>
                      <th>Session</th>
                      <th>Referer</th>
                      <th>ProcessID</th>                      
                      <th>URL</th>
                      <th>User Agent</th> 
                      <th>Country</th> 
                      <th>DateTime</th> 
                  </tr>
              </tfoot>
          </table>                              
        </div>
</div>

          
<script>
        
        var table = '';

        jQuery(document).ready(function() {          
          table = jQuery('#auditsData').DataTable({
            'processing': true,
            'serverSide': true,                        
            'lengthMenu': [
              [10, 25, 50, -1], [10, 25, 50, "All"]
            ],
            dom: 'Bfrtip',
            buttons: [                        
            {extend:'csvHtml5',
              exportOptions: {
                columns: [0, 1, 2, 3,4,5,7]//"thead th:not(.noExport)"
              }
            },
            {extend: 'pdfHtml5',
              exportOptions: {
                columns: [0, 1, 2, 3,4,5,7] //"thead th:not(.noExport)"
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
            },
            'fnRowCallback': function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {             
            },
            "initComplete": function(settings, json) {						
              //jQuery('.popoverData').popover();
			},
            'ajax': {
              'url': '{{ url("/") }}/auditIndexAjax',
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
                  'data': 'ip',
                  'className': 'col-md-1',
                  'render': function(data,type,row){                    
                    return row.ip;
                  }
              },
              {
                  'data': 'source',
                  'className': 'col-md-1'
              },
              {
                  'data': 'username',
                  'className': 'col-md-1'
              },
              {
                'data': 'session',
                'className': 'col-md-2'
              },
              {
                'data': 'referer',
                'className': 'col-md-1'
              },
              {
                'data': 'process_id',
                'className': 'col-md-1',                           
              },
              {
                'data': 'url',
                'className': 'col-md-1',                           
              },
              {
                'data': 'user_agent',
                'className': 'col-md-1',                
              },
              {
                'data': 'country',                
                "className":'col-md-1'              
              },
              {
                'data': 'created_at',                
                "className":'col-md-1'                
              }                           
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
