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
                Sales Rental
            </h1>
            <ol class="breadcrumb">
                <li>
                      <i class="fa fa-fw fa-shield"></i> Inventory
                </li>
				<li>
                    <a href="#">Sales Rental</a>
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
                            <i class="fa fa-fw fa-list-alt"></i> Sales Rental &nbsp; 
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
							@can('srl-create')
                             <a href="{{ url('sales_rental/add') }}" class="btn btn-primary btn-sm">
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
							<table class="table horizontal_table table-striped" id="tableSrl">
								<thead>
								<tr>
									<th>SR. No</th>
									<th>Customer</th>
									<th>SR. Date</th>
									<th>Amount</th>
									<?php if($settings->doc_approve==1) { ?><th>Status</th><?php } ?>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<!-- @can('si-edit')<th></th>@endcan
									@can('si-print')<th></th>@endcan
									@can('si-delete')<th></th>@endcan
									@can('do-print')<th></th>@endcan -->
								</tr>
								</thead>
								
							</table>
						</div>
                    </div>
                </div>
            </div>
		</section>
		
		<section class="content">
			<form class="form-horizontal" role="form" method="POST" name="frmQReport" id="frmQReport" target="_blank" action="{{ url('sales_rental/search') }}">
			 <input type="hidden" name="_token" value="{{ csrf_token() }}">
			 <input type="hidden" name="department_id" id="department_id">
			 <div class="row">
			 <div class="col-lg-12">
					<div class="panel panel-info">
						<div class="panel-heading clearfix">
							<h3 class="panel-title pull-left m-t-6">
								<i class="fa fa-fw fa-list-alt"></i> Sales Rental Report
							</h3>
						</div>
						<div class="panel-body">
								<div class="row">
									<div class="col-xs-6">
										<span>Date From:</span>
										<input type="text" name="date_from" data-language='en' autocomplete="off" id="date_from" class="form-control">
										<span>Date To:</span>
										<input type="text" name="date_to" data-language='en' autocomplete="off" id="date_to" class="form-control">
									</div>
									<div class="col-xs-6">
										<span>Search By:</span>
										<select id="search_type" class="form-control select2" style="width:100%" name="search_type">
											<option value="summary">Summary</option>
											<option value="detail" <?php if($type=='detail') echo 'selected';?>>Detail</option>
											<!-- <option value="customer" <?php //if($type=='customer') echo 'selected';?>>Customerwise</option>
											<option value="item" <?php //if($type=='item') echo 'selected';?>>Itemwise</option> -->
											<!-- <option value="purchase_register">Purchase Register(Cash,Credit)</option>
											<option value="tax_code">Tax Code</option> -->
										</select>
										
										
										<br/>
										<div class="col-xs-4" style="border:0px solid red;">
										<span>Customers:</span> <br/>
                                        <select id="select1" multiple style="width:100%" class="form-control select2" name="customer_id[]">
										@foreach($customer as $row)
                                           <option value="{{$row->id}}">{{$row->master_name}}</option>
                                        @endforeach	                                       
                                     </select>
									 </div>
									 <div class="col-xs-4" style="border:0px solid red;">
										<span>Salesman</span>
										<select id="salesman" class="form-control select2" style="width:100%" name="salesman">
											<option value="">--Select Salesman--</option>
											@foreach($salesman as $row)
											<option value="{{$row->id}}">{{$row->name}}</option>
											@endforeach
										</select>
										</div>
										
										<div class="col-xs-4" id="item" style="border:0px solid red;">
											<span>Item:</span><br/>
											   <select id="select2" multiple style="width:100%" class="form-control select2" name="item_id[]">
											   @foreach($item as $row)
                                                 <option value="{{$row->id}}">{{$row->description}}</option>
                                               @endforeach	 											  
											</select><br/>
										</div> 

										<div class="col-xs-4" id="group" style="border:0px solid red;">
											<span>Group:</span><br/>											
											<select id="select3" multiple style="width:100%" class="form-control select2" name="group_id[]">
											   @foreach($group as $row)
											   <option value="{{$row->id}}">{{$row->group_name}}</option>
											   @endforeach													
											</select>
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
											</select><br/>
										</div>
										
										<div class="col-xs-4" id="subcategory" style="border:0px solid red;">
											<span>Subcategory:</span><br/>											
											<select id="select6" multiple style="width:100%" class="form-control select2" name="subcategory_id[]">
											    @foreach($subcategory as $row)
											      <option value="{{$row->id}}">{{$row->category_name}}</option>
											    @endforeach								
											</select>
										</div>
										
										<br>
										<span></span><br/>
										<!-- <input type="radio" name="isimport" value="1"> Import &nbsp; 
										<input type="radio" name="isimport" value="0"> Local &nbsp; 
										<input type="radio" name="isimport" value="2" checked> Both &nbsp;  -->
										<div class="col-xs-12" align="right"> <button type="submit" class="btn btn-primary">Search</button></div>
									
									</div>
								</div>
						</div>
					</div>
			</div>
			</div>
			</form>
		</section>
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


function openWin(id) {
  myWindow = window.open("{{url('sales_rental/print/')}}/"+id, "", "width=400, height=477");
}


function funDelete(id) {
	var con = confirm('Are you sure delete this rental invoice?');
	if(con==true) {
		var url = "{{ url('sales_rental/delete/') }}";
		location.href = url+'/'+id;
	}
}

$(function() {
		
		var dtInstance = $("#SalesInvoiceList").DataTable({
			"processing": true,
			"serverSide": true,
			"searching": true,
			"order": [[ 0, 'desc' ]],
			"ajax":{
					 "url": "{{ url('sales_rental/paging/') }}",
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
			{ "data": "customer" },
			{ "data": "net_total" },
			<?php if($settings->doc_approve==1) { ?>{ "data": "status" },<?php } ?>
			@can('si-edit'){ "data": "edit","bSortable": false },@endcan
			@can('si-print'){ "data": "print","bSortable": false },@endcan
			@can('si-delete'){ "data": "delete","bSortable": false },@endcan
			@can('do-print'){ "data": "printdo","bSortable": false }@endcan
		]	
		  
		});
		
		$(document).on('change', '#department', function(e) {  
			dtInstance.draw();
			$('#department_id').val( $('#department option:selected').val() );
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
        placeholder: "Item"
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
    
});

$(function() {
		
		var dtInstance = $("#tableSrl").DataTable({
			"processing": true,
			"serverSide": true,
			"searching": true,
			"ajax":{
					 "url": "{{ url('quotation_rental/paging/') }}",
					 "dataType": "json",
					 "type": "POST",
					 "data":{ _token: "{{csrf_token()}}"}
				   },
			"columns": [
			{ "data": "voucher_no" },
			{ "data": "customer" },
			{ "data": "voucher_date" },
			{ "data": "net_total" },
			<?php if($settings->doc_approve==1) { ?>{ "data": "status" },<?php } ?>
			@can('srl-edit'){ "data": "edit","bSortable": false },@endcan
			@can('srl-print'){ "data": "print","bSortable": false },@endcan
			@can('srl-delete'){ "data": "delete","bSortable": false },@endcan
		]	
		  
		});
});

</script>

@stop
