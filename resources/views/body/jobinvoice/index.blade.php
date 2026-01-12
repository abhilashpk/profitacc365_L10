@extends('layouts/default')

    {{-- Page title --}}
    @section('title')
         
        @parent
    @stop

{{-- page level styles --}}
@section('header_styles')
    
	<!--page level css -->
	<link rel="stylesheet" href="{{asset('assets/vendors/datetime/css/jquery.datetimepicker.css')}}">
    <link href="{{asset('assets/vendors/airdatepicker/css/datepicker.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('assets/vendors/bootstrap-multiselect/css/bootstrap-multiselect.css')}}" rel="stylesheet" type="text/css">
	
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/buttons.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/colReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/dataTables.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/rowReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/scroller.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatablesmark.js/css/datatables.mark.min.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/responsive_datatables.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/datepicker.css')}}">

	<link href="{{asset('assets/vendors/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('assets/vendors/select2/css/select2-bootstrap.css')}}" rel="stylesheet" type="text/css">
    <!--end of page level css-->
		
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <!--section starts-->
            <h1>
                Job Invoice
            </h1>
            <ol class="breadcrumb">
                <li>
                      <i class="fa fa-fw fa-shield"></i> Inventory
                </li>
				<li>
                    <a href="#">Job Invoice</a>
                </li>
            </ol>
        </section>
        <!--section ends-->
		@if(Session::has('message'))
		<div class="alert alert-success">
			<p>{{ Session::get('message') }}</p>
		</div>
		@endif
        
		@if(Session::has('error'))
		<div class="alert alert-danger">
			<p>{{ Session::get('error') }}</p>
		</div>
		@endif
		
		<section class="content">
            <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-info">
                    <div class="panel-heading clearfix">
                        <h3 class="panel-title pull-left m-t-6">
                            <i class="fa fa-fw fa-list-alt"></i> Job Invoice
                        </h3>
                        <div class="pull-right">
							@can('job-invoice-create')
                             <a href="{{ url('job_invoice/add') }}" class="btn btn-primary btn-sm">
									<span class="btn-label">
									<i class="glyphicon glyphicon-plus"></i>
								</span> Add New
							</a>
							@endcan
                        </div>
                    </div>
                    <div class="panel-body">
						 <div class="row">
							<!--<div class="col-xs-6">
								<a href="{{ url('sales_report') }}" class="btn btn-primary btn-sm">
										<span class="btn-label">
										<i class="fa fa-fw fa-hdd-o"></i>
									</span>View Report
								</a>
								
								<a href="{{ url('vat_report') }}" class="btn btn-primary btn-sm">
										<span class="btn-label">
										<i class="fa fa-fw fa-magic"></i>
									</span> Vat Report
								</a>
							</div>-->
						</div>
                        <div class="table-responsive m-t-10">
							<table class="table horizontal_table table-striped" id="SalesInvoiceList">
								<thead>
								<tr>
									<th>JI. No</th>
									<th>JI. Date</th>
									<th>JO. No</th>
									<th>Customer</th>
									<th>Vehicle</th>
									<th>Reg.No.</th>
									<th>Chasis No.</th>
									<th>Amount</th>
									@can('job-invoice-edit')<th></th>@endcan
									@can('job-invoice-view')<th></th>@endcan
									@can('job-invoice-print')<th></th>@endcan
									@can('job-invoice-delete')<th></th>@endcan
									<th></th>
								</tr>
								</thead>
								
							</table>
						</div>
                    </div>
                </div>
            </div>
		</section>
		
		<section class="content">
			<form class="form-horizontal" role="form" method="POST" name="frmQReport" id="frmQReport" target="_blank" action="{{ url('job_invoice/search') }}">
			 <input type="hidden" name="_token" value="{{ csrf_token() }}">
			 <div class="row">
			 <div class="col-lg-12">
					<div class="panel panel-info">
						<div class="panel-heading clearfix">
							<h3 class="panel-title pull-left m-t-6">
								<i class="fa fa-fw fa-list-alt"></i> Job Invoice Report
							</h3>
						</div>
						<div class="panel-body">
								<div class="row">
									<div class="col-xs-6">
										<span>Date From:</span>
										<input type="text" name="date_from" data-language='en' autocomplete="off" id="date_from" class="form-control input-sm">
										<span>Date To:</span>
										<input type="text" name="date_to" data-language='en' autocomplete="off" id="date_to" class="form-control input-sm">																												
									</div>
									<div class="col-xs-6">
										<span>Search By:</span>
										<select id="search_type" class="form-control select2" style="width:100%" name="search_type">
											
											<option value="summary">Summary</option>
											<option value="detail">Detail</option>
											<!--	<option value="job_orderclosed">Job Order(Closed)</option> 
											<option value="job_order">Job Order(Pending)</option>
										 <option value="customer">CustomerWise</option> 
										 <option value="vehicle">Vehicle</option> -->
										</select>
										<span>Vehicle No:</span>
										<input type="text" name="vehicle_no" autocomplete="off" id="vehicle_no" class="form-control" data-toggle="modal" data-target="#vehicle_modal">
										<div class="col-xs-4" style="border:0px solid red;">
										<span>Customers:</span> <br/>

                                        <select id="select1" multiple style="width:100%" class="form-control select2" name="customer_id[]">
										   @foreach($customer as $row)
										     <option value="{{$row->id}}">{{$row->master_name}}</option>
										   @endforeach									
										
                                        </select>
									 </div>

									 <div class="col-xs-4" style="border:0px solid red;">
										<span>Salesman:</span>
										<select id="select2" multiple style="width:100%" class="form-control select2" name="salesman_id[]">
										    @foreach($salesman as $row)
										     <option value="{{$row->id}}">{{$row->name}}</option>
										    @endforeach							
										</select>									
										</div>
									<div class="col-xs-4" style="border:0px solid red;">	
										<span>Job No:</span>
										<select id="select7" class="form-control select2" multiple style="width:100%" name="job_id[]">
											<option value="">Select Job No...</option>
											@foreach($jobs as $job)
												<option value="{{$job['id']}}">{{$job['code']}}</option>
											@endforeach
										</select>
									</div>	
										<div class="col-xs-4" id="group" style="border:0px solid red;">
											<span>Group:</span><br/>
											<select id="select3" multiple style="width:100%" class="form-control select2" name="group_id[]">
											    @foreach($group as $row)
										          <option value="{{$row->id}}">{{$row->group_name}}</option>
										        @endforeach											z											</select>
										</div>
										
										<div class="col-xs-3" id="subgroup" style="border:0px solid red;">
											<span>Subgroup:</span><br/>
											<select id="select4" multiple style="width:100%" class="form-control select2" name="subgroup_id[]">
											    @foreach($subgroup as $row)
										          <option value="{{$row->id}}">{{$row->group_name}}</option>
										        @endforeach									
											</select>
										</div>

										<div class="col-xs-4" id="category" style="border:0px solid red;">
											<span>Category:</span><br/>
											<select id="select5" multiple style="width:100%" class="form-control select2" name="category_id[]">
											    @foreach($category as $row)
										          <option value="{{$row->id}}">{{$row->category_name}}</option>
										        @endforeach												
											</select>
										</div>
										
										<div class="col-xs-4" id="subcategory" style="border:0px solid red;">
											<span>Subcategory:</span><br/>
											<select id="select6" multiple style="width:100%" class="form-control select2" name="subcategory_id[]">
											   @foreach($subcategory as $row)
										          <option value="{{$row->id}}">{{$row->category_name}}</option>
										        @endforeach												
											</select><br/>
										</div><br/>		
										<div class="col-xs-12" align="right"> <button type="submit" class="btn btn-primary">Search</button></div>
									</div>
								</div>
						</div>
					</div>
			</div>
			</div>
			</form>
			
			<div id="vehicle_modal" class="modal fade animated" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">Select Vehicle</h4>
						</div> <input type="hidden" id="cust_id">
						<div class="modal-body" id="vehicleData">
							Please select a Customer first!
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						</div>
					</div>
				</div>
			</div>
			
		</section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')

    <!-- begining of page level js -->
<script src="{{asset('assets/vendors/bootstrap-multiselect/js/bootstrap-multiselect.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/custom_js/custom_elements.js')}}" type="text/javascript"></script>


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

<script src="{{asset('assets/vendors/mark.js/jquery.mark.js')}}" charset="UTF-8"></script>
<script src="{{asset('assets/vendors/datatablesmark.js/js/datatables.mark.min.js')}}" charset="UTF-8"></script>
<script src="{{asset('assets/js/custom_js/responsive_datatables.js')}}" type="text/javascript"></script>

<script src="{{asset('assets/vendors/datetime/js/jquery.datetimepicker.full.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.en.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/custom_js/advanceddate_pickers.js')}}"></script>

<script src="{{asset('assets/vendors/select2/js/select2.js')}}" type="text/javascript"></script>

<!-- end of page level js -->

<script>

$('#date_from').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy' } );
$('#date_to').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy' } );

function openWin(id) {
  myWindow = window.open("{{url('job_invoice/print/')}}/"+id, "", "width=400, height=477");
}
function funDelete(id) {
	var con = confirm('Are you sure delete this sales invoice?');
	if(con==true) {
		var url = "{{ url('job_invoice/delete/') }}";
		location.href = url+'/'+id;
	}
}

$(function() {
		
		var dtInstance = $("#SalesInvoiceList").DataTable({
			"processing": true,
			"serverSide": true,
			"searching": true,
			"ajax":{
					 "url": "{{ url('job_invoice/paging/') }}",
					 "dataType": "json",
					 "type": "POST",
					 "data":{ _token: "{{csrf_token()}}"}
				   },
			"columns": [
			{ "data": "voucher_no" },
			{ "data": "voucher_date" },
			{ "data": "jo_no" },
			{ "data": "customer" },
			{ "data": "vehicle" },
			{ "data": "reg_no" },
			{ "data": "chasis_no" },
			{ "data": "net_total" },
			@can('job-invoice-edit'){ "data": "edit","bSortable": false },@endcan
			@can('job-invoice-view'){ "data": "viewonly","bSortable": false },@endcan
			@can('job-invoice-print'){ "data": "print","bSortable": false },@endcan
			@can('job-invoice-delete'){ "data": "delete","bSortable": false },@endcan
			{ "data": "item_info","bSortable": false },
		]	
		  
		});
		
		var vclurl = "{{ url('job_order/all_vehicle/') }}"
		$('#vehicle_no').click(function() { 
				$('#vehicleData').load(vclurl, function(result) {
					$('#myModal').modal({show:true});
				});
		});
		
		$(document).on('click', '.vclRow', function(e) {
			$('#vehicle_no').val($(this).attr("data-regno"));
			e.preventDefault();
		});
		
		$('#select1').on('change', function(e){
			    var cust_id = $('#select1').val();
			    console.log(cust_id);
			     if(cust_id !=''){
			    $.get("{{ url('sales_invoice/getjob/') }}/" + cust_id, function(data) {
				$('#select7').find('option').remove().end();
				$.each(data, function(key, value) {   
				$('#select7').find('option').end()
				 .append($("<option></option>")
							.attr("value",value.id)
							.text(value.code)); 
							
				});
			});
			    }
		});
	
});
$(function() {	
	$('#group').hide(); 
	$('#subgroup').hide(); 
	$('#category').hide(); 
	$('#subcategory').hide(); 
	$('#item').hide(); 
	$(document).on('change', '#search_type', function(e) { 
	   if($('#search_type option:selected').val()=='detail')
			{
			$('#group').show(); 
	$('#subgroup').show(); 
	$('#category').show(); 
	$('#subcategory').show();
	$('#item').show(); 
}
		else
		{
		$('#group').hide(); 
	     $('#subgroup').hide(); 
	$('#category').hide(); 
	$('#subcategory').hide();
	$('#item').hide(); 
} 
    });
});
$(document).ready(function () {
    ///$('#selcust').toggle();
    //$('#selitm').toggle();

	$("#select1").select2({
        theme: "bootstrap",
        placeholder: "Customers"
    });

	$("#select2").select2({
        theme: "bootstrap",
        placeholder: "Salesman"
    });

	$("#select3").select2({
        theme: "bootstrap",
        placeholder: "Group"
    });

	$("#select4").select2({
        theme: "bootstrap",
        placeholder: "Subgroup"
    });

	$("#select5").select2({
        theme: "bootstrap",
        placeholder: "Category"
    });

	$("#select6").select2({
        theme: "bootstrap",
        placeholder: "Subcategory"
    });
    
		$("#select7").select2({
        theme: "bootstrap",
        placeholder: "Job No:"
    });
    
});
</script>

@stop
