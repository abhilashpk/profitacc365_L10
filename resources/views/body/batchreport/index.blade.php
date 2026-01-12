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
                Item Batch Report
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="glyphicon glyphicon-folder-close"></i> Reports
                    </a>
                </li>
                <li>
                    <a href="#">Item Batch Report</a>
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
                                <i class="fa fa-fw fa-columns"></i> Item Batch Report
                            </h3>
                        </div>
                        <div class="panel-body">
							<form class="form-horizontal" role="form" method="POST" target="_blank" name="frmBatchReport" id="frmBatchReport" action="{{ url('batch_report/search') }}">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								<div class="row">
									<div class="col-xs-6">
										<span>Expiry Date From:</span>
										<input type="text" name="date_from" data-language='en' id="date_from" autocomplete="off" class="form-control input-sm">
										<span>Expiry Date To:</span>
										<input type="text" name="date_to" data-language='en' id="date_to" autocomplete="off" class="form-control input-sm">
									</div>
									
									<div class="col-xs-6">
											<span>Search By:</span>
											<select id="search_type" class="form-control select2" style="width:100%" name="search_type">
												<option value="batch_expiry">Batch Expiry</option>
											</select>
                                        {{--
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
                                        --}}
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
	
$('#date_from').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy' } );
$('#date_to').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy' } );

function getSummary() {	
	document.frmQuantityReport.action = "{{ url('batch_report/print')}}";
	document.frmQuantityReport.submit();
}

$(document).ready(function () {

	
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
