@extends('layout.app')
<meta name="csrf-token" content="{{ csrf_token() }}">
@section('content')
<div class="right_col" role="main">
@include('layout/errors')
  <div class="x_panel">
      <div class="x_title">
        <h2>Aasana Category List</h2>

        <div class="clearfix"></div>
			
      </div>
      <div class="x_content"> 
		<div class="search-area pull-right width-auto">
			<a href="/aasana/addcategory" class="pull-right btn btn-success">Add Category</a>
		</div>
          {{ csrf_field() }}             
          <table id="aasanaCategoryDataList" class="table-responsive table table-striped table-bordered" style="font-size:12px;width:100% !important">
              <thead>
                  <tr>
                      <th>Sr. No.</th>                      
                      <th>Category Name</th>                      
                      <th>Category Description</th>                      
                      <th>Image</th>
					           <th> Status </th>	
                      <th>Action</th> 
                  </tr>
              </thead>
              <tbody>
                            
              </tbody>
              <tfoot>
                 <tr>                              
                     <th>Sr. No.</th>
                     <th>Category Name</th>                      
                      <th>Category Description</th>                      
                      <th>Image</th>
					            <th> Status </th>	
                      <th>Action</th>
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


          table = jQuery('#aasanaCategoryDataList').DataTable({
            'processing': true,
            'serverSide': true,                        
            'lengthMenu': [
              [10, 25, 50, -1], [10, 25, 50, "All"]
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
              'url': '{{ url("/") }}/aasana/categoryIndexAjax',
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
				'data': 'Sr.No.',
				 'className': 'col-md-1',
				"sortable": false,
				'render': function(data,type,row){
						return row.sr_no;
				}
			  }, 
              {
                  'data': 'category_name',
                  'className': 'col-md-2',
                  'render': function(data,type,row){
                    var category_name = (row.category_name.length > 30) ? row.category_name.substring(0,30)+'...' : row.category_name;
                    return '<a class="popoverData" data-content="'+row.category_name+'" rel="popover" data-placement="bottom" data-original-title="Category Name" data-trigger="hover">'+category_name+'</a>';
                  }
              },
              {
                  'data': 'category_description',
                  'className': 'col-md-1',
                  'render': function(data,type,row){
                    return row.category_description;
                  }
              },
              
              {
                  'data': 'category_image',
                  'className': 'col-md-2',
                  'render': function(data,type,row){
                    
                    return '<a href="'+row.category_image+'" target="_blank"><img src="'+row.category_image+'"></a>';
                  }
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
                 'data': 'Action',
                 'className': 'col-md-2',
                 'render': function(data, type, row) {
                  
				   var buttonHtml = '<a href="/aasana/viewcategory/' + row.id + '" class="btn btn-success" ><i class="fa fa-eye" aria-hidden="true"></i></a>';
				   if(row.status==0){
				   buttonHtml += '<a href="/aasana/editcategory/' + row.id + '" class="btn btn-success" ><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> <button type="button" id="' + row.id + '" class="btn btn-danger aasana_deleteCategory"><i class="fa fa-trash" aria-hidden="true"></i></button>';
				 }
				  
                  return buttonHtml;
                }
              }
            ]
          });   
              
          
        });

        

 $(document).on('click','.aasana_deleteCategory',function(){ 
        let id = $(this).attr('id');
        if(confirm('Are you sure, you want to delete this category?')==true){
            $.ajax({
                url:'deletecategory/'+id,
                type:'POST',
                headers:{
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                },
                data:{id},
                success:(data)=>{
					table.draw();
                   //alert(data);
				   
                }
            })
        }
    });
 

        
        function ValidateEmail(email){
          if(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email)) {
            return true;
          }
          return false;
        }

        function changeStatus(category_id,val){ 
          jQuery.ajax({
              url: '/aasana/changestatus',
              type: "POST",
              headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
              },
              data: {
                "status": val,"category_id": category_id
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
