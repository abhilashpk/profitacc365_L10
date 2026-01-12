@extends('layouts/default')

    {{-- Page title --}}
    @section('title')
         
        @parent
    @stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
	<link href="{{asset('assets/vendors/bootstrap-multiselect/css/bootstrap-multiselect.css')}}" rel="stylesheet" type="text/css">
	
	<link rel="stylesheet" href="{{asset('assets/vendors/datetime/css/jquery.datetimepicker.css')}}">
    <link href="{{asset('assets/vendors/airdatepicker/css/datepicker.min.css')}}" rel="stylesheet" type="text/css">
	
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
                Quantity Report
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="glyphicon glyphicon-folder-close"></i> Reports
                    </a>
                </li>
                <li>
                    <a href="#">Quantity Report</a>
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
                                <i class="fa fa-fw fa-columns"></i> Quantity Report
                            </h3>
                        </div>
                        <div class="panel-body">
							<form class="form-horizontal" role="form" method="POST" target="_blank" name="frmQuantityReport" id="frmQuantityReport" action="{{ url('quantity_report/search') }}">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								<div class="row">
									<div class="col-xs-6">
										<span>As on Date:</span>
										<input type="hidden" name="date_from" value="{{$fromdate}}">
										<input type="text" name="date_to" data-language='en' autocomplete="off" id="date_to" value="<?php echo $todate; ?>" class="form-control input-sm">
										<span>Quantity with:</span>
										<select id="quantity_type" class="form-control select2" style="width:100%" name="quantity_type">
											<option value="all" <?php if($type=='all') echo 'selected';?>>All</option>
											<option value="minus" <?php if($type=='minus') echo 'selected';?>>Minus</option>
											<option value="positive" <?php if($type=='positive') echo 'selected';?>>Positive</option>
											<option value="zero" <?php if($type=='zero') echo 'selected';?>>Zero</option>
											<option value="nonzero" <?php if($type=='nonzero') echo 'selected';?>>NonZero</option>
										</select>
										
										<span></span><br/> &nbsp; <input type="checkbox" name="is_binloc" value="1"> Bin Location
										
											<input type="radio" name="itemtype" value="1" checked> Stock &nbsp; 
											<input type="radio" name="itemtype" value="2"> Service &nbsp; 
											<input type="radio" name="itemtype" value=""> Both &nbsp; 
											
										
										
									</div>
									<div class="col-xs-6">
										
											<span>Search By:</span>
											<select id="search_type" class="form-control select2" style="width:100%" name="search_type">
												<option value="opening_quantity" <?php if($type=='opening_quantity') echo 'selected';?>>Opening Quantity</option>
												<option value="qtyhand_ason_date" <?php if($type=='qtyhand_ason_date') echo 'selected';?>>Quantity in Hand as on Date</option>
												<option value="price_list_qty" <?php if($type=='price_list_qty') echo 'selected';?>>Price List Quantity</option>
												<option value="qtyhand_ason_priordate" <?php if($type=='qtyhand_ason_priordate') echo 'selected';?>>Quantity in Hand as on Prior Date</option>
												<option value="opening_quantity_loc" <?php if($type=='opening_quantity_loc') echo 'selected';?>>Opening Quantity Location</option>
												<option value="qtyhand_ason_date_loc" <?php if($type=='qtyhand_ason_date_loc') echo 'selected';?>>Quantity in Hand as on Date Location</option>
												<option value="qtyhand_ason_priordate_loc" <?php if($type=='qtyhand_ason_priordate_loc') echo 'selected';?>>Quantity in Hand as on Prior Date Location</option>
											</select>
											<div class="col-xs-4" style="border:0px solid red;">
										
											<span>Customer:</span>
											<select id="select6" multiple style="width:100%" class="form-control select2" name="account_id[]">
												<?php foreach($customers as $row) { ?>
											<option value="{{$row->id}}"><?php echo $row->master_name;?></option>
											<?php } ?>
											</select>
											</div>
										
										<div class="col-xs-4" style="border:0px solid red;">
											<span>Location:</span> <br/>
											<select id="select1" multiple style="width:100%" class="form-control select2" name="location_id[]">
											@foreach($location as $row)
											   <option value="{{$row->id}}">{{$row->name}}</option>
											@endforeach												
											</select>
										</div>
										
										
										<div class="col-xs-4" style="border:0px solid red;">
											<span>Group:</span><br/>
											<select id="select2" multiple style="width:100%" class="form-control select2" name="group_id[]">
											@foreach($group as $row)
											   <option value="{{$row->id}}">{{$row->group_name}}</option>
											@endforeach	
											</select>
										</div>
										
										<div class="col-xs-3" style="border:0px solid red;">
											<span>Sub Group:</span><br/>
											<select id="select3" multiple style="width:100%" class="form-control select2" name="subgroup_id[]">
											@foreach($subgroup as $row)
											   <option value="{{$row->id}}">{{$row->group_name}}</option>
											@endforeach												
											</select>
										</div>
										
										<br/><br/>
										<div class="col-xs-4" style="border:0px solid red;">
											<span>Category:</span><br/>
											<select id="select4" multiple style="width:100%" class="form-control select2" name="category_id[]">
											@foreach($category as $row)
											   <option value="{{$row->id}}">{{$row->category_name}}</option>
											@endforeach		
											<</select>
										</div>
										
										<div class="col-xs-4" style="border:0px solid red;">
											<span>Sub Category:</span><br/>
											<select id="select5" multiple style="width:100%" class="form-control select2" name="subcategory_id[]">
											@foreach($subcategory as $row)
											   <option value="{{$row->id}}">{{$row->category_name}}</option>
											@endforeach												
											</select>
										</div>
										
										<div class="col-xs-4" style="border:0px solid red;">
											<input type="checkbox" name="mpqty" value="1"> MP Qty
										</div>	
										<div class="col-xs-4" style="border:0px solid red;">
											<input type="checkbox" name="pqty" value="1"> P Qty
										</div>	
										
										<div class="col-xs-4" style="border:0px solid red;"><br/>
										<button type="submit" class="btn btn-primary">Search</button>
										</div>
										
									</div>
									
								</div>
								<?php if($reports!=null) { ?><br/>
								<div class="table-responsive">
									<table class="table table-striped" id="stockLedger">
										<thead>
										<tr>
											<th></th>
											<th>Item Code</th>
											<th>Description</th>
											<th>Unit</th>
											<th class="text-right">Cost Avg.</th>
											<th class="text-right">Qty.in Hand</th>
										</tr>
										</thead>
										<tbody>
										<?php $i=0;?>
										@foreach($reports as $report)
										<?php $i++; ?>
										<tr>
											<td><input type="checkbox" name="document_id[]" value="{{$report->id}}" /></td>
											<td>{{ $report->item_code }}</td>
											<td>{{ $report->description }}</td>
											<td>{{ $report->packing }}</td>
											<td class="text-right">{{ number_format($report->cost_avg,2) }}</td>
											<td class="text-right">{{ $report->cur_quantity }}</td>
										</tr>
										@endforeach
										
										</tbody>
									</table>
									
								</div>
								<?php } ?>
							</form>
							
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

<script src="{{asset('assets/vendors/mark.js/jquery.mark.js')}}" charset="UTF-8"></script>
<script src="{{asset('assets/vendors/datatablesmark.js/js/datatables.mark.min.js')}}" charset="UTF-8"></script>
<script src="{{asset('assets/js/custom_js/responsive_datatables.js')}}" type="text/javascript"></script>

<script src="{{asset('assets/vendors/datetime/js/jquery.datetimepicker.full.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.en.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/custom_js/advanceddate_pickers.js')}}"></script>
<script src="{{asset('assets/js/custom_js/custom_elements.js')}}" type="text/javascript"></script>

<script src="{{asset('assets/vendors/select2/js/select2.js')}}" type="text/javascript"></script>
<!-- end of page level js -->

<script>
	
$('#date_to').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy' } );

function getSummary() {	
	document.frmQuantityReport.action = "{{ url('quantity_report/print')}}";
	document.frmQuantityReport.submit();
}

$(document).ready(function () {

	$("#select1").select2({
        theme: "bootstrap",
        placeholder: "Location"
    });

	$("#select2").select2({
        theme: "bootstrap",
        placeholder: "Group"
    });

	$("#select3").select2({
        theme: "bootstrap",
        placeholder: "Subgroup"
    });

	$("#select4").select2({
        theme: "bootstrap",
        placeholder: "Category"
    });

	$("#select5").select2({
        theme: "bootstrap",
        placeholder: "Subcategory"
    });

	$("#select6").select2({
        theme: "bootstrap",
        placeholder: "Customer"
    });

});


$(function() {
	var inputMapper = {
		"account_id": 1,
		"master_name": 2,
		"group": 3,
		"category": 4
	};
	var dtInstance = $("#stockLedger").DataTable({
		"lengthMenu": [10, 25, 50, "ALL"],
		bLengthChange: false,
		mark: true,
		"aoColumns": [{ "bSortable": false },null,null,null,null,null ],
		//"scrollX": true,
	});
	$("input").on("input", function() {
		var $this = $(this);
		var val = $this.val();
		var key = $this.attr("name");
		dtInstance.columns(inputMapper[key] - 1).search(val).draw();
	});
});
</script>
@stop
