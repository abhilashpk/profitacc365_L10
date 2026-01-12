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
    <!--end of page level css-->
		
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <!--section starts-->
            <h1>
                Purchase Invoice
            </h1>
            <ol class="breadcrumb">
                <li>
                      <i class="fa fa-fw fa-shield"></i> Inventory
                </li>
				<li>
                    <a href="#">Purchase Invoice</a>
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
                            <i class="fa fa-fw fa-list-alt"></i> Purchase Invoice &nbsp;  
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
							@can('pi-create')
                             <a href="{{ url('purchase_invoice/add') }}" class="btn btn-primary btn-sm">
									<span class="btn-label">
									<i class="glyphicon glyphicon-plus"></i>
								</span> Add New
							</a>
							@endcan
                        </div>
                    </div>
                    <div class="panel-body">
						 
                        <div class="table-responsive m-t-10">
                                <table class="table horizontal_table table-striped" id="tablePurInvoice">
                                    <thead>
                                    <tr>
										<th>PI. No</th>
										<th>Sup.Inv.No</th>
										<th>PI. Date</th>
										<!--<th>Description</th>-->
										<th>Supplier</th>
										<th>Amount</th>
										<th></th>
										<th></th>
										<th></th>
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
			<form class="form-horizontal" role="form" method="POST" name="frmPOReport" target="_blank" id="frmPOReport" action="{{ url('purchase_invoice/search') }}">
			 <input type="hidden" name="_token" value="{{ csrf_token() }}">
			 <input type="hidden" name="department_id" id="department_id">
			 <div class="row">
			 <div class="col-lg-12">
					<div class="panel panel-info">
						<div class="panel-heading clearfix">
							<h3 class="panel-title pull-left m-t-6">
								<i class="fa fa-fw fa-list-alt"></i> Purchase Invoice Report
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
											<option value="detail" <?php if($type=='detail') echo 'selected';?>>Detail</option>
										
										</select>
										</div>
										<div class="col-xs-4" style="border:0px solid red;">
										<span>Suppliers:</span> <br/>
                                        <select id="multiselect2" multiple="multiple" class="form-control" name="supplier_id[]">
                                         <?php foreach($supplier as $row) { ?>
                                     <option value="<?php echo $row->id;?>" <?php //if($row->id==$locid) echo 'selected';?>><?php echo $row->master_name;?></option>
                                        <?php } ?>
                                     </select>
										</div>
										<div class="col-xs-4" id="item" style="border:0px solid red;">
												<span>Item:</span><br/>
											<select id="multiselect7" multiple="multiple" class="form-control" name="item_id[]">
												<?php foreach($item as $row) { ?>
												<option value="<?php echo $row->id;?>"><?php echo $row->description;?></option>
												<?php } ?>
											</select>
										</div> 
										<div class="col-xs-4" id="group" style="border:0px solid red;">
											<span>Group:</span><br/>
											<select id="multiselect3" multiple="multiple" class="form-control" name="group_id[]">
												<?php foreach($group as $row) { ?>
												<option value="<?php echo $row->id;?>"><?php echo $row->group_name;?></option>
												<?php } ?>
											</select>
										</div>
										
										<div class="col-xs-3" id="subgroup" style="border:0px solid red;">
											<span>Subgroup:</span><br/>
											<select id="multiselect4" multiple="multiple" class="form-control" name="subgroup_id[]">
												<?php foreach($subgroup as $row) { ?>
												<option value="<?php echo $row->id;?>"><?php echo $row->group_name;?></option>
												<?php } ?>
											</select>
										</div>
										
								
										<div class="col-xs-4" id="category" style="border:0px solid red;">
											<span>Category:</span><br/>
											<select id="multiselect5" multiple="multiple" class="form-control" name="category_id[]">
												<?php foreach($category as $row) { ?>
												<option value="<?php echo $row->id;?>"><?php echo $row->category_name;?></option>
												<?php } ?>
											</select>
										</div>
										
										<div class="col-xs-4" id="subcategory" style="border:0px solid red;">
											<span>Subcategory:</span><br/>
											<select id="multiselect6" multiple="multiple" class="form-control" name="subcategory_id[]">
												<?php foreach($subcategory as $row) { ?>
												<option value="<?php echo $row->id;?>"><?php echo $row->category_name;?></option>
												<?php } ?>
											</select>
										</div>
										
										<br>
										<span></span><br/>
										
										
										
									
										
										
										<!-- <input type="radio" name="isimport" value="1"> Import &nbsp; 
										<input type="radio" name="isimport" value="0"> Local &nbsp; 
										<input type="radio" name="isimport" value="2" checked> Both &nbsp;  -->
										<button type="submit" class="btn btn-primary">Search</button>
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
<script src="{{asset('assets/vendors/bootstrap-multiselect/js/bootstrap-multiselect.js')}}" type="text/javascript"></script>

<script src="{{asset('assets/vendors/mark.js/jquery.mark.js')}}" charset="UTF-8"></script>
<script src="{{asset('assets/vendors/datatablesmark.js/js/datatables.mark.min.js')}}" charset="UTF-8"></script>
<script src="{{asset('assets/js/custom_js/responsive_datatables.js')}}" type="text/javascript"></script>
<!-- end of page level js -->

<script src="{{asset('assets/vendors/datetime/js/jquery.datetimepicker.full.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.en.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/custom_js/advanceddate_pickers.js')}}"></script>

<script>

function funDelete(id) {
	var con = confirm('Are you sure delete this purchase invoice?');
	if(con==true) {
		var url = "{{ url('purchase_invoice/delete/') }}";
		location.href = url+'/'+id;
	}
}

$(function() {
		
		var dtInstance = $("#tablePurInvoice").DataTable({
			"processing": true,
			"serverSide": true,
			"searching": true,
			"order": [[ 0, 'desc' ]],
			"ajax":{
					 "url": "{{ url('purchase_invoice/paging/') }}",
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
			{ "data": "reference_no" },
			{ "data": "voucher_date" },
			{ "data": "supplier" },
			{ "data": "net_total" },
			@can('si-edit'){ "data": "edit","bSortable": false },@endcan
			@can('si-print'){ "data": "print","bSortable": false },@endcan
			@can('si-delete'){ "data": "delete","bSortable": false }@endcan
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
