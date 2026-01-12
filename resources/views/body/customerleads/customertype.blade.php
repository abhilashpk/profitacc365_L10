@extends('layouts/default')

    {{-- Page title --}}
    @section('title')
         
        @parent
    @stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/iCheck/css/all.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrap-fileinput/css/fileinput.min.css')}}" media="all" />
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/formelements.css')}}">
        <!--end of page level css-->
		
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/buttons.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/colReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/dataTables.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/rowReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/scroller.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatablesmark.js/css/datatables.mark.min.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/responsive_datatables.css')}}">
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
       <section class="content-header">
            <!--section starts-->
            <h1>
                Customer Desk
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="fa fa-fw fa-money"></i>Sales CRM
                    </a>
                </li>
                <li>
                    <a href="#">Customer Desk</a>
                </li>
            </ol>
			
        </section>
		
        <!--section ends-->
		@if(Session::has('message'))
		<div class="alert alert-success">
			<p>{{ Session::get('message') }}</p>
		</div>
		@endif
		
        <section class="content">
            <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-info">
                    <div class="panel-heading clearfix">
                        <h3 class="panel-title pull-left m-t-6">
                            <i class="fa fa-fw fa-list-alt"></i> Customer List
                        </h3>
                        <div class="pull-right">
                            <select id="status" class="form-control select2" name="status">
								<option value="">All Status</option>
							<!--	<option value="1">Customer</option> -->
								<option value="2">Enquiry</option>
								<option value="3">Prospective</option>
								<!--	<option value="4">Archive</option>-->
							</select>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                                <table class="table table-striped" id="tableCustLeads">
                                    <thead>
                                    <tr>
                                        <th>#</th>
										<th>Customer</th>
										<th>Phone No</th>
										<th>Contact Name</th>
                                                                                <th>Email</th>
									<th>Status</th>
										
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
								
								<div id="customer_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Create Customer</h4>
                                        </div>
										
                                        <div class="modal-body" id="customerData">
											<div class="panel panel-success filterable" id="newCustomerFrm">
											<div class="panel-heading">
												<h3 class="panel-title">
													<i class="fa fa-fw fa-columns"></i> New Customer
												</h3>
											</div>
											
										</div>			
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                    </div>
					
                            </div>
                    </div>
                </div>
            </div>
        </div>
        
             
          <!--main content-->
            <!-- row -->
        @include('layouts.right_sidebar')
        <!-- right side bar end -->
        </section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <!-- begining of page level js -->
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/jquery.dataTables.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/dataTables.buttons.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/dataTables.colReorder.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/dataTables.responsive.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/dataTables.rowReorder.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/buttons.colVis.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/buttons.html5.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/buttons.bootstrap.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/buttons.print.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/dataTables.scroller.js')}}"></script>

<script type="text/javascript" src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/iCheck/js/icheck.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-fileinput/js/fileinput.min.js')}}"></script>
<!-- end of page level js -->
<script>

$(function() {
            
	var dtInstance = $("#tableCustLeads").DataTable({
			"processing": true,
			"serverSide": true,
			"searching": true,
			"ajax":{
					 "url": "{{ url('customerlead/paging/') }}",
					 "dataType": "json",
					 "type": "POST",
					 "data": function(data){
						  var status = $('#status option:selected').val();
						  data._token = "{{csrf_token()}}";
						  data.status = status;
					  }
				   },
			"columns": [
			{ "data": "id" },
			{ "data": "master_name" },
			{ "data": "phone" },
			{ "data": "customer" },
                        { "data": "email" },
			{ "data": "status" },
		//	{ "data": "enquiry","bSortable": false },
			//{ "data": "edit","bSortable": false },
			//{ "data": "delete","bSortable": false }
		],
		rowId: 'id'
		  
	});
	
	$(document).on('change', '#status', function(e) {  
		dtInstance.draw();
		$('#status').val( $('#status option:selected').val() );
	});
	
	$('#tableCustLeads tbody').on('click', 'tr td', function () {
        // var rid = dtInstance.row( this ).id();
        var rid = dtInstance.row( this.closest("tr") ).id();
        //console.log(rid);
        var url = "{{ url('customerleads/edit/') }}";
        var cell = $(this).closest('td');
        var cellIndex = cell[0].cellIndex;
        console.log(cellIndex);
        if(parseInt(cellIndex) == 4){
            return false;
        }
		location.href = url+'/'+rid;
    } );
	
});

function funDelete(id) {
	var con = confirm('Are you sure delete this customer?');
	if(con==true) {
		var url = "{{ url('customerleads/delete/') }}";
		location.href = url+'/'+id;
	}
}
</script>

@stop
