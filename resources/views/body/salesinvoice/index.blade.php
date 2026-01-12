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

	<link href="{{asset('assets/vendors/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('assets/vendors/select2/css/select2-bootstrap.css')}}" rel="stylesheet" type="text/css">
	
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
                Sales Invoice
            </h1>
            <ol class="breadcrumb">
                <li>
                      <i class="fa fa-fw fa-shield"></i> Inventory
                </li>
				<li>
                    <a href="#">Sales Invoice</a>
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
                            <i class="fa fa-fw fa-list-alt"></i> Sales Invoice &nbsp; 
                        </h3>
							@if($isdept)
							<select id="department" class="form-control select2" name="department" style="width:20%;">
								<option value="">All Department</option>
								@foreach($departments as $row)
								<option value="{{$row->id}}">{{$row->name}}</option>
								@endforeach
							</select>
							@endif
							
                        <div class="pull-right">
							@can('si-create')
                             <a href="{{ url('sales_invoice/add') }}" class="btn btn-primary btn-sm">
									<span class="btn-label">
									<i class="glyphicon glyphicon-plus"></i>
								</span> Add New
							</a>
							@endcan
                        </div>
                    </div>
                    <div class="panel-body">
						 <div class="row">
							
						</div>
                        <div class="table-responsive m-t-10">
							<table class="table horizontal_table table-striped" id="SalesInvoiceList">
								<thead>
								<tr>
									<th>SI. No</th>
									<th>SI. Date</th>
									<th>Job No</th>
									<th>Customer</th>
									<th>Amount</th>
									<?php if($settings->doc_approve==1) { ?><th>Status</th><?php } ?>
									@can('si-edit')<th></th>@endcan
									@can('si-view')<th></th>@endcan
									@can('si-print')<th></th>@endcan
									@can('si-delete')<th></th>@endcan
									@can('do-print')<th></th>@endcan
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
			<form class="form-horizontal" role="form" method="POST" name="frmQReport" id="frmQReport" target="_blank" action="{{ url('sales_invoice/search') }}">
			 <input type="hidden" name="_token" value="{{ csrf_token() }}">
			 <input type="hidden" name="department_id" id="department_id">
			 <div class="row">
			 <div class="col-lg-12">
					<div class="panel panel-info">
						<div class="panel-heading clearfix">
							<h3 class="panel-title pull-left m-t-6">
								<i class="fa fa-fw fa-list-alt"></i> Sales Invoice Report
							</h3>
						</div>
						<div class="panel-body">
								<div class="row">
									<div class="col-xs-6">
										<span>Date From:</span>
										<input type="text" name="date_from" data-language='en' autocomplete="off" id="date_from" class="form-control">
										<span>Date To:</span>
										<input type="text" name="date_to" data-language='en' autocomplete="off" id="date_to" class="form-control">
										
										<span>Department:</span>
										<select id="select29" class="form-control select2" multiple style="width:100%" name="dept_id[]">
											<option value="">Select Department...</option>
											@foreach($departments as $row)
												<option value="{{$row->id}}">{{$row->name}}</option>
											@endforeach
										</select>
									</div>
									<div class="col-xs-6">
										<span>Search By:</span>
										<select id="search_type" class="form-control select2" style="width:100%" name="search_type">
											<option value="summary">Summary</option>
										
											<option value="detail" <?php if($type=='detail') echo 'selected';?>>Detail</option>
											<!-- 	<option value="summary_pmode">Summary with Payment Mode</option>
											<option value="customer" <?php //if($type=='customer') echo 'selected';?>>Customerwise</option>
											<option value="item" <?php //if($type=='item') echo 'selected';?>>Itemwise</option> -->
											<!-- <option value="purchase_register">Purchase Register(Cash,Credit)</option>
											<option value="tax_code">Tax Code</option> -->
										</select>
										
										
										<br/>
										<div class="col-xs-4" style="border:0px solid red;">
										<span>Customers:</span> 
                                        <select id="select21" multiple style="width:100%" class="form-control select2" name="customer_id[]">
                                        <option value="">Select Customers...</option> 
										@foreach($customer as $row)
												<option value="{{$row['id']}}">{{$row['master_name']}}</option>
											@endforeach				
										
                                     </select>
									 </div>
									 <div class="col-xs-4" style="border:0px solid red;">
											<span>Salesman</span>
										<select id="select27" class="form-control select2" style="width:100%" name="salesman">
											<option value="">--Select Salesman--</option>
											@foreach($salesman as $row)
											<option value="{{$row->id}}">{{$row->name}}</option>
											@endforeach
										</select>
										</div>
										<div class="col-xs-4" style="border:0px solid red;">
										<span>Job No:</span>
										<select id="select28" class="form-control select2" multiple style="width:100%" name="job_id[]">
											<option value="">Select Job No...</option>
											@foreach($jobs as $job)
												<option value="{{$job['id']}}">{{$job['code']}}</option>
											@endforeach
										</select>
									   </div>
										<div class="col-xs-4" id="item" style="border:0px solid red;">
											<span >Item:</span>
											<select id="select22" multiple style="width:100%" class="form-control select2" name="item_id[]">
											   <option value="">Select Items...</option> 	
											   @foreach($item as $row)
											   <option value="{{$row->id}}">{{$row->description}}</option>
											   @endforeach											
											</select><br/>
										</div>

										<div class="col-xs-4" id="group" style="border:0px solid red;">
											<span>Group:</span><br/>											
											<select id="select23" multiple style="width:100%" class="form-control select2" name="group_id[]">
											   @foreach($group as $row)
											   <option value="{{$row->id}}">{{$row->group_name}}</option>
											   @endforeach													
											</select>
										</div>
										
										<div class="col-xs-3" id="subgroup" style="border:0px solid red;">
											<span>Subgroup:</span><br/>											
											<select id="select24" multiple style="width:100%" class="form-control select2" name="subgroup_id[]">										
											   @foreach($subgroup as $row)
											      <option value="{{$row->id}}">{{$row->group_name}}</option>
											   @endforeach											
											</select>
										</div>
										
								
										<div class="col-xs-4" id="category" style="border:0px solid red;">
											<span>Category:</span><br/>											
											<select id="select25" multiple style="width:100%" class="form-control select2" name="category_id[]">
											   @foreach($category as $row)
											      <option value="{{$row->id}}">{{$row->category_name}}</option>
											   @endforeach												
											</select><br/>
										</div>
										
										<div class="col-xs-4" id="subcategory" style="border:0px solid red;">
											<span>Subcategory:</span><br/>											
											<select id="select26" multiple style="width:100%" class="form-control select2" name="subcategory_id[]">
											    @foreach($subcategory as $row)
											      <option value="{{$row->id}}">{{$row->category_name}}</option>
											    @endforeach								
											</select>
										</div>
										
										
										<span></span><br/>
										<!-- <input type="radio" name="isimport" value="1"> Import &nbsp; 
										<input type="radio" name="isimport" value="0"> Local &nbsp; 
										<input type="radio" name="isimport" value="2" checked> Both &nbsp;  -->
										
										
										</div>    
										<div class="col-xs-12" align="right"> <button type="submit" class="btn btn-primary">Search</button></div>
								</div>
						</div>
					</div>
			</div>
			</div>
			</form>
		</section>
		
<div id="amtview_modal" class="modal fade animated" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Bill Transaction</h4>
			</div>
			<div class="modal-body" id="bill_data">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div> 


<div id="history_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Recent Order History</h4>
                                        </div>
                                        <div class="modal-body" id="historyData">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close
                                            </button>
                                        </div>
                                    </div>
                                </div>
</div>

@stop

{{-- page level scripts --}}
@section('footer_scripts')

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

<script src="{{asset('assets/vendors/select2/js/select2.js')}}" type="text/javascript"></script>

<script src="{{asset('assets/vendors/mark.js/jquery.mark.js')}}" charset="UTF-8"></script>
<script src="{{asset('assets/vendors/datatablesmark.js/js/datatables.mark.min.js')}}" charset="UTF-8"></script>
<script src="{{asset('assets/js/custom_js/responsive_datatables.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/bootstrap-multiselect/js/bootstrap-multiselect.js')}}" type="text/javascript"></script>

<script src="{{asset('assets/vendors/datetime/js/jquery.datetimepicker.full.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.en.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/custom_js/advanceddate_pickers.js')}}"></script>

<script src="{{asset('assets/vendors/select2/js/select2.js')}}" type="text/javascript"></script>

<script>

$('#date_from').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy' } );
$('#date_to').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy' } );

$(document).ready(function () {
	$("#select21").select2({
        theme: "bootstrap",
        placeholder: "Customers"
    });

	$("#select22").select2({
        theme: "bootstrap",
        placeholder: "Items"
    });

	$("#select23").select2({
        theme: "bootstrap",
        placeholder: "Group"
    });

	$("#select24").select2({
        theme: "bootstrap",
        placeholder: "Subgroup"
    });

	$("#select25").select2({
        theme: "bootstrap",
        placeholder: "Category"
    });

	$("#select26").select2({
        theme: "bootstrap",
        placeholder: "Subcategory"
    });

    $("#select27").select2({
        theme: "bootstrap",
        placeholder: "Salesman"
    });
     $("#select28").select2({
        theme: "bootstrap",
        placeholder: "Job No:"
    });
    $("#select29").select2({
        theme: "bootstrap",
        placeholder: "Department"
    });
});


function openWin(id) {
  myWindow = window.open("{{url('sales_invoice/print/')}}/"+id, "", "width=400, height=477");
}

function funDelete(id,no) {
    @if(auth()->user()->can('delete-transfered-doc'))
        var con = confirm('Are you sure delete this sales invoice?');
    	if(con==true) {
    		var url = "{{ url('sales_invoice/delete/') }}";
    		location.href = url+'/'+id;
    	}
    @else
        if(no==0) {
        	var con = confirm('Are you sure delete this sales invoice?');
        	if(con==true) {
        		var url = "{{ url('sales_invoice/delete/') }}";
        		location.href = url+'/'+id;
        	}
        } else {
            alert('This invoice is transfered from Delivery order, you cannot delete.');
        }
    @endif
}

$(function() {
		
		var dtInstance = $("#SalesInvoiceList").DataTable({
			"processing": true,
			"serverSide": true,
			"searching": true,
			"order": [[ 0, 'desc' ]],
			"ajax":{
					 "url": "{{ url('sales_invoice/paging/') }}",
					 "dataType": "json",
					 "type": "POST",
					 "data": function(data){
						  var dept = $('#department option:selected').val();
						  data._token = "{{csrf_token()}}";
						  data.dept = dept;
					  }
				   },
			"columns": [
			{ "data": "voucher_no" },
			{ "data": "voucher_date" },
			{ "data": "job" },
			{ "data": "customer" },
			{ "data": "net_total" },
			{ "data": "history","bSortable": false },
			<?php if($settings->doc_approve==1) { ?>{ "data": "status" },<?php } ?>
			@can('si-edit'){ "data": "edit","bSortable": false },@endcan
			@can('si-view'){ "data": "viewonly","bSortable": false },@endcan
			@can('si-print'){ "data": "print","bSortable": false },@endcan
			@can('si-delete'){ "data": "delete","bSortable": false }@endcan
		]	
		  
		});
		
		$(document).on('change', '#department', function(e) {  
			dtInstance.draw();
			$('#department_id').val( $('#department option:selected').val() );
		});
		
		$('#select21').on('change', function(e){
			    var cust_id = $('#select21').val();
			    console.log(cust_id);
			     if(cust_id !=''){
			    $.get("{{ url('sales_invoice/getjob/') }}/" + cust_id, function(data) {
				$('#select28').find('option').remove().end();
				$.each(data, function(key, value) {   
				$('#select28').find('option').end()
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
	
	   if($('#search_type option:selected').val()=='detail') {
		$('#group').show(); 
			$('#subgroup').show(); 
			$('#category').show(); 
			$('#subcategory').show();
			$('#item').show(); 
		} else {
			$('#group').hide(); 
			$('#subgroup').hide(); 
			$('#category').hide(); 
			$('#subcategory').hide();
			$('#item').hide(); 
		} 
    });
	
	var stsurl = "{{ url('sales_invoice/get_billdata/') }}";
	$(document).on('click','.getBill', function() { 
		$('#bill_data').load(stsurl+'/'+$(this).attr("data-id"), function(result) {
			$('#myModal').modal({show:true});
		});
	});

		var ordhisurl = "{{ url('sales_invoice/index_history/') }}";
//	$('.order-history').click(function() {
	    	$(document).on('click','.order-history', function() { 
		//var cus_id = $('#customer_id').val();
		console.log($(this).attr("data-id"));
		$('#historyData').load(ordhisurl+'/'+$(this).attr("data-id"), function(result) {
			$('#myModal').modal({show:true});
		});
	});
});
$(document).ready(function () {
    ///$('#selcust').toggle();
    //$('#selitm').toggle();
    
    $("#multiselect2").multiselect({
        enableFiltering: true,
        includeSelectAllOption: true,
        maxHeight: 300,
        dropUp: true
    });
	$("#multiselect3").multiselect({
        enableFiltering: true,
        includeSelectAllOption: true,
        maxHeight: 300,
        dropUp: true
    });

    $("#multiselect4").multiselect({
        enableFiltering: true,
        includeSelectAllOption: true,
        maxHeight: 300,
        dropUp: true
    });
    $("#multiselect5").multiselect({
        enableFiltering: true,
        includeSelectAllOption: true,
        maxHeight: 300,
        dropUp: true
    });
    $("#multiselect6").multiselect({
        enableFiltering: true,
        includeSelectAllOption: true,
        maxHeight: 300,
        dropUp: true
    });
	$("#multiselect7").multiselect({
        enableFiltering: true,
        includeSelectAllOption: true,
        maxHeight: 300,
        dropUp: true
    });
});
</script>

@stop
