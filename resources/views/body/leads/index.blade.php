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
                Leads
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="fa fa-fw fa-money"></i> Leads
                    </a>
                </li>
                <li>
                    <a href="#">Leads</a>
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
                            <i class="fa fa-fw fa-list-alt"></i> Leads List
                        </h3>
                        <div class="pull-right">
                             <a href="{{ url('leads/add') }}" class="btn btn-primary btn-sm">
									<span class="btn-label">
									<i class="glyphicon glyphicon-plus"></i>
								</span> Add New
							</a>
							
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                                <table class="table table-striped" id="tableLeads">
                                    <thead>
                                    <tr>
                                        <th>Lead #</th>
										<th>Lead Date</th>
										<th>Customer</th>
										<th>Salesman</th>
										<th>Lead Status</th>
										<th></th>
										<th></th>
										<th></th>
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
											<div class="panel-body"> 
											
												<div class="col-xs-10">
													<div id="addressDtls">
													<form class="form-horizontal" role="form" method="POST" name="frmCustomer" id="frmCustomer">
													<input type="hidden" name="_token" value="{{ csrf_token() }}">
													
														<hr/>
														<div class="form-group">
															<label for="input-text" class="col-sm-5 control-label">Customer ID</label>
															<div class="col-sm-7">
																<input type="hidden" name="category" value="{{$ccategory}}">
																<input type="text" class="form-control" id="account_id" name="account_id" value="{{$cusid}}">
															</div>
														</div>
														
														<div class="form-group">
															<label for="input-text" class="col-sm-5 control-label">Customer Name</label>
															<div class="col-sm-7">
																<input type="text" class="form-control" id="customer_name" name="customer_name" placeholder="Customer Name">
															</div>
														</div>
														
														<div class="form-group">
															<label for="input-text" class="col-sm-5 control-label">Address</label>
															<div class="col-sm-7">
																<input type="text" class="form-control" id="address" name="address" placeholder="Address1, Address2">
															</div>
														</div>
														
														<div class="form-group">
															<label for="input-text" class="col-sm-5 control-label">Phone</label>
															<div class="col-sm-7">
																<input type="text" class="form-control" id="phone" name="phone" placeholder="Phone">
															</div>
														</div>
														
														<div class="form-group">
															<label for="input-text" class="col-sm-5 control-label">Fax</label>
															<div class="col-sm-7">
																<input type="text" class="form-control" id="fax" name="fax" placeholder="Fax">
															</div>
														</div>
														
														<div class="form-group">
															<label for="input-text" class="col-sm-5 control-label">Email</label>
															<div class="col-sm-7">
																<input type="text" class="form-control" id="email" name="email" placeholder="Email">
															</div>
														</div>
														
														<div class="form-group">
															<label for="input-text" class="col-sm-5 control-label">Contact Person</label>
															<div class="col-sm-7">
																<input type="text" class="form-control" id="contact_name" name="contact_name" placeholder="Contact Person">
															</div>
														</div>
														
														<div class="form-group">
															<label for="input-text" class="col-sm-5 control-label">Remarks</label>
															<div class="col-sm-7">
																<input type="text" class="form-control" id="remarks" name="remarks" placeholder="Remarks">
															</div>
														</div>
														
														<div class="form-group">
															<label for="input-text" class="col-sm-5 control-label">Status</label>
															<div class="col-sm-7">
																<select id="status" class="form-control select2" style="width:100%" name="status">
																	<option value="Suspect">Suspect</option>
																	<option value="Prospective">Prospective</option>
																	<option value="Enquiry">Enquiry</option>
																</select>
															</div>
														</div>
														
														<div class="form-group">
															<label for="input-text" class="col-sm-5 control-label"></label>
															<div class="col-sm-7">
																<button type="button" class="btn btn-primary" id="create">Create</button>
															</div>
														</div>
													 </form>
													</div>
													
													<div id="sucessmsg"><br/>
														<div class="alert alert-success">
															<p>
																Customer created successfully.
															</p>
														</div>
													</div>
												</div>
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
            
	var dtInstance = $("#tableLeads").DataTable({
			"processing": true,
			"serverSide": true,
			"searching": true,
			"ajax":{
					 "url": "{{ url('leads/paging/') }}",
					 "dataType": "json",
					 "type": "POST",
					 "data":{ _token: "{{csrf_token()}}"}
				   },
			"columns": [
			{ "data": "lead_no" },
			{ "data": "lead_date" },
			{ "data": "customer" },
			{ "data": "salesman" },
			{ "data": "status" },
			@can('qs-edit'){ "data": "followup","bSortable": false },@endcan
			@can('qs-edit'){ "data": "edit","bSortable": false },@endcan
			@can('qs-delete'){ "data": "delete","bSortable": false },@endcan
		]	
		  
	});
	
});
		
function funDelete(id) {
	var con = confirm('Are you sure delete this lead enquiry?');
	if(con==true) {
		var url = "{{ url('leads/delete/') }}";
		location.href = url+'/'+id;
	}
}
</script>

@stop
