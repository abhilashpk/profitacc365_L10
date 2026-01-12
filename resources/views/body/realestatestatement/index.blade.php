@extends('layouts/default')

    {{-- Page title --}}
    @section('title')
         
        @parent
    @stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
	
	<link href="{{asset('assets/vendors/bootstrap-multiselect/css/bootstrap-multiselect.css')}}" rel="stylesheet" type="text/css">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/buttons.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/colReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/dataTables.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/rowReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/scroller.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatablesmark.js/css/datatables.mark.min.css')}}"/>
	<link rel="stylesheet" href="{{asset('assets/vendors/datetime/css/jquery.datetimepicker.css')}}">
	<link href="{{asset('assets/vendors/airdatepicker/css/datepicker.min.css')}}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/datepicker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/responsive_datatables.css')}}">
	
	
        <!--end of page level css-->
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <!--section starts-->
            <h1>
                Statement
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="fa fa-fw fa-briefcase"></i> Statement
                    </a>
                </li>
                <li>
                    <a href="#">Statement</a>
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
                                <i class="fa fa-fw fa-columns"></i> Statement &nbsp; 
                            </h3>
							
							@if($isdept)
							<select id="department" class="form-control select2" name="department" style="width:20%;">
								<option value="">All Department</option>
								@foreach($department as $row)
								<option value="{{$row->id}}">{{$row->name}}</option>
								@endforeach
							</select>
							@endif
							
							<div class="pull-right">
							
							<br>
								<br>
							
                        </div>
                        </div>
                        <div class="panel-body">
							
                            <div class="table-responsive m-t-10">
                                <table class="table horizontal_table table-striped" id="Acenquiry">
                                    <thead>
                                    <tr>
                                        <th></th>
										<th>ID</th>
                                        <th>Account Name</th>
										<th>Group</th>
										<th>Category</th>
										@if($isdept)<th>Department</th>@endif
                                        <th>Closing Balance</th>
                                        <th>Open Balance</th>
										<th></th>
                                    </tr>
                                    </thead>
                                   
                                </table>
								<div class="col-lg-4">
									<div class="checkbox checkbox-primary" style="margin-right:10px;">
										<input type='radio' id="checkboxcst" name='account' class='opt-account' value='CUSTOM'/> 
										<label for="checkboxcst">Custom</label>
									</div>
									<!--<div class="checkbox checkbox-primary" style="margin-right:10px;">
										<input type='radio' id="checkboxcst1" name='account' class='opt-account' value='CUSTOMER'/> 
										<label for="checkboxcst1">All Customers</label>
									</div>
									<div class="checkbox checkbox-primary">
										<input type='radio' id="checkboxsup" name='account' class='opt-account' value='SUPPLIER'/>
										<label for="checkboxsup">All Suppliers</label>
									</div>-->
								</div>
								
								@can('stmnt')
								<button type="button" class="btn btn-primary statement" data-toggle="modal" data-target="#date_modal">Statement</button>
									<!-- @endcan
								@can('out-stnd')
								<button type="button" class="btn btn-primary outstanding" data-toggle="modal" data-target="#date_modal">Outstanding</button>
								@endcan
								@can('ageing')
								<button type="button" class="btn btn-primary ageing" data-toggle="modal" data-target="#date_modal">Ageing</button>
								@endcan
								
								@can('out-stnd')
								<button type="button" class="btn btn-primary osmonthly" data-toggle="modal" data-target="#date_modal">Outstanding Monthly</button>
								@endcan
								
							@can('stmnt')
								<button type="button" class="btn btn-primary item-statement" data-toggle="modal" data-target="#date_modal">Statement with Stock</button>
								@endcan-->
								
                            </div>
                        </div>
                    </div>
					
					<div id="date_modal" class="modal fade animated" role="dialog">
					
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal">&times;</button>
									<h4 class="modal-title">Search by</h4>
								</div>
								<div class="modal-body" id="messageData">
									<div class="row"><div class="col-xs-6">Please select an account first!</div></div>
								</div>
								<div class="modal-body" id="accountData">
								<form class="form-horizontal" role="form" method="POST" target="_blank" name="frmSearchAccount" id="frmSearchAccount" action="{{ url('realestate/search_account') }}">
									<input type="hidden" name="_token" value="{{ csrf_token() }}">
									<input type="hidden" id="account_id" name="account_id">
									<input type="hidden" id="type" name="type">
									<input type="hidden" id="is_custom" name="is_custom" value="0">
											
									<div class="form-body">
									<div id="custom">
										<div class="form-group">
											<label for="inputUsername" class="col-md-4 control-label">Account Category</label>
											<div class="col-md-7">
												<select id="multiselect3" multiple="multiple" class="form-control" name="category_id[]">
												<?php foreach($category as $row) { ?>
												<option value="<?php echo $row->id;?>"><?php echo $row->name;?></option>
												<?php } ?>
											</select>
											</div>
										</div>
										
										<div class="form-group">
											<label for="inputUsername" class="col-md-4 control-label">Account Group</label>
											<div class="col-md-7">
												<select id="multiselect4" multiple="multiple" class="form-control" name="group_id[]">
												<?php foreach($groups as $row) { ?>
												<option value="<?php echo $row->id;?>"><?php echo $row->name;?></option>
												<?php } ?>
											</select>
											</div>
										</div>
										
										<div class="form-group">
											<label for="inputUsername" class="col-md-4 control-label">Account Type</label>
											<div class="col-md-7">
												<select id="multiselect5" multiple="multiple" class="form-control" name="type_id[]">
												<option value="BANK">BANK</option>
												<option value="CASH">CASH</option>
												<option value="CUSTOMER">CUSTOMER</option>
												<option value="PDCI">PDCI</option>
												<option value="PDCR">PDCR</option>
												<option value="SUPPLIER">SUPPLIER</option>
											</select>
											</div>
										</div>
									</div>
									
										<div class="form-group">
											<label for="inputUsername" class="col-md-4 control-label">Date From</label>
											<div class="col-md-7">
												<input type="text" name="date_from" value="{{date('d-m-Y',strtotime($settings->from_date))}}" class="form-control input-sm">
											</div>
										</div>
										
										<div class="form-group">
											<label for="inputUsername" class="col-md-4 control-label">Date To</label>
											<div class="col-md-7">
												<input type="text" name="date_to" class="form-control input-sm" value="{{date('d-m-Y')}}">
											</div>
										</div>
										
										<div class="form-group">
											<label for="inputUsername" class="col-md-4 control-label">Job</label>
											<div class="col-md-7">
												<select id="job_id" class="form-control select2" style="width:100%" name="job_id">
												<option value="">Select Job...</option>
												@foreach($jobs as $job)
												<option value="{{$job->id}}">{{$job->code}}</option>
												@endforeach
											</select>
											</div>
										</div>
										
										<div class="form-group">
											<div class="col-md-offset-4 col-md-7">
												<label class="checkbox-inline">
													<input type="checkbox" class="square-blue onacnt_icheck" id="inFC" name="inFC" value="1"> Statement in FC
												</label>
											</div>
										</div>
															
										<div class="form-group">
											<label for="inputUsername" class="col-md-4 control-label">Currency</label>
											<div class="col-md-7">
												<select id="currency_id" class="form-control select2" style="width:100%" name="currency_id">
												<option value="">Select Foreign Currency...</option>
												@foreach($currency as $curr)
												<option value="{{$curr->id}}">{{$curr->code}}</option>
												@endforeach
											</select>
											</div>
										</div>
										
										<div class="form-group">
											<div class="col-md-offset-4 col-md-7">
												<label class="checkbox-inline">
													<input type="checkbox" class="onacnt_icheck" id="is_pdc" name="is_pdc" value="1"> Statement With PDC
												</label>
											</div>
										</div>
										
										<div class="form-group">
											<div class="col-md-offset-4 col-md-7">
												<label class="checkbox-inline">
													<input type="checkbox" class="onacnt_icheck" id="is_con" name="is_con" value="1"> Statement Consolidated
												</label>
											</div>
										</div>
										
										<div class="form-group">
											<div class="col-md-offset-4 col-md-7">
												<label class="checkbox-inline">
													<input type="checkbox" class="onacnt_icheck" id="exclude_ob" name="exclude_ob" value="1"> Exclude Opening Balance
												</label>
											</div>
										</div>
										
										<div class="form-actions">
											<div class="row">
												<div class="col-md-offset-4 col-md-9">
													<button type="submit" class="btn btn-primary">Search</button>
													&nbsp;
													<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
												</div>
											</div>
										</div>
										
									</div>
									</form>
								</div>
								<div class="modal-footer">
									
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

<script src="{{asset('assets/vendors/bootstrap-multiselect/js/bootstrap-multiselect.js')}}" type="text/javascript"></script>
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

<script src="{{asset('assets/vendors/datetime/js/jquery.datetimepicker.full.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.en.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/custom_js/advanceddate_pickers.js')}}"></script>

<script src="{{asset('assets/vendors/mark.js/jquery.mark.js')}}" charset="UTF-8"></script>
<script src="{{asset('assets/vendors/datatablesmark.js/js/datatables.mark.min.js')}}" charset="UTF-8"></script>
<script src="{{asset('assets/js/custom_js/responsive_datatables.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/custom_js/custom_elements.js')}}" type="text/javascript"></script>

<!-- end of page level js -->

<script>
$(document).ready(function () {  
	$('#is_custom').val(0);
});

$(function() {	
	$('#accountData').hide();
	$(document).on('click', '.opt-account', function(e) { 
	   $('#messageData').hide(); $('#accountData').show(); $('#custom').hide();
	   $('#account_id').val(this.value);
		if($(this).val()=='CUSTOM')
			$('#is_custom').val(1);
		else {
			$('#is_custom').val(0);
			$("#multiselect3").multiselect("clearSelection");
			$("#multiselect4").multiselect("clearSelection");
			$("#multiselect5").multiselect("clearSelection");
		}
    });
	
	$(document).on('click', '.statement', function(e) { 
	   $('#type').val('statement');
	   if($('#is_custom').val()==1)
		   $('#custom').show();
	   else {
		   $('#custom').hide();
		   $("#multiselect3").multiselect("clearSelection");
		   $("#multiselect4").multiselect("clearSelection");
		   $("#multiselect5").multiselect("clearSelection");
	   }
    });
	
	$(document).on('click', '.outstanding', function(e) { 
	   $('#type').val('outstanding');
	   if($('#is_custom').val()==1)
		   $('#custom').show();
	   else {
		   $('#custom').hide();
		   $("#multiselect3").multiselect("clearSelection");
			$("#multiselect4").multiselect("clearSelection");
			$("#multiselect5").multiselect("clearSelection");
	   }
    });
	
	$(document).on('click', '.ageing', function(e) { 
	   $('#type').val('ageing');
	   if($('#is_custom').val()==1)
		   $('#custom').show();
	   else {
		   $('#custom').hide();
		   $("#multiselect3").multiselect("clearSelection");
			$("#multiselect4").multiselect("clearSelection");
			$("#multiselect5").multiselect("clearSelection");
	   }
    });
	
	$(document).on('click', '.osmonthly', function(e) { 
	   $('#type').val('osmonthly');
    });
	
	$(document).on('click', '.item-statement', function(e) { 
	   $('#type').val('item-statement');
    });
	
	$(function() {
          
		var dtInstance = $("#Acenquiry").DataTable({
			"processing": true,
			"serverSide": true,
			"ajax":{
					 "url": "{{ url('realestate/paging/') }}",
					 "dataType": "json",
					 "type": "POST",
					 "data": function(data){
						  var dept = $('#department option:selected').val();
						  data._token = "{{csrf_token()}}";
						  data.dept = dept;
					  }
					 //"data":{ _token: "{{csrf_token()}}"}
				   },
			/* "scrollY":        500,
			"deferRender":    true,
			"scroller":       true, */
			"columns": [
			{ "data": "opt","bSortable": false },
			{ "data": "account_id" },
			{ "data": "master_name" },
			{ "data": "group_name" },
			{ "data": "category_name" },
			@if($isdept){ "data": "department" },@endif
			{ "data": "cl_balance" },
			{ "data": "op_balance" }
		//	{ "data": "view","bSortable": false }
		 ]
		});
		
		$(document).on('change', '#department', function(e) {  
			dtInstance.draw();
		});
	});
});
function updateCB() {
	document.frmAccUtility.action="{{ url('utilities/updateAccMaster/CB') }}";
	document.frmAccUtility.submit();
}
</script>
@stop
