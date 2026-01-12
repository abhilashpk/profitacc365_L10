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
                Item Enquiry
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="fa fa-fw fa-home"></i> Inventory
                    </a>
                </li>
                <li>
                    <a href="#">Item Enquiry</a>
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
                        <div class="panel-heading clearfix  ">
                            <div class="panel-title pull-left">
                                <i class="fa fa-fw fa-list-alt"></i> Item List
                            </div>
                            <div class="pull-right">
                            	<form role="form" method="POST" name="frmItemMasterUtility" id="frmItemMasterUtility">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" name="pono" id="pono">
								<input type="hidden" name="sono" id="sono">
							    <div class="row">
									<button type="button" class="btn btn-primary btn-sm float-end" onClick="updateStock()">
										<i class="glyphicon glyphicon-refresh"></i>
										Update Item Stock
									</button>
								</div>
							  </form>
							</div>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="ItemEnq">
                                    <thead>
                                    <tr>
										<th></th>
										<?php foreach($cols as $col) { ?>
											<th><?php echo $formdata[$col.'_fn'];?></th>
										<?php } ?>
										<th></th>
										<th></th>
                                    </tr>
                                    </thead>
                                   
                                </table>
                            </div>
							<br/>
							<form class="form-horizontal" role="form" method="POST" name="frmItemSearch" id="frmItemSearch" target="_blank" action="{{ url('itemenquiry/details') }}">
									<input type="hidden" name="_token" value="{{ csrf_token() }}">
									<input type="hidden" name="item_id" id="item_id">
									<div class="row">
										
										<div class="col-xs-1" style="padding-left:13px;padding-top:5px;"><span>Search By:</span></div>
										<div class="col-xs-2">
												<select id="search_type" class="form-control select2" name="search_type">
													<option value="">Select...</option>
													<option value="SI">Sales Invoice</option>
													<option value="PI">Purchase Invoice</option>
													<option value="SO">Sales Order</option>
													<option value="PO">Purchase Order</option>
													<option value="SR">Sales Return</option>
													<option value="PR">Purchase Return</option>
												</select>
										</div>
										<div class="col-xs-2">
												<select id="custsupp_id" class="form-control select2" name="custsupp_id">
													<option value="">Customer/Supplier</option>
												</select>
										</div>
										<div class="col-xs-1">
											<input type="text" name="date_from" data-language='en' autocomplete="off" placeholder="Date From" id="date_from" class="form-control">
										</div>
										
										<div class="col-xs-1">
											<input type="text" name="date_to" data-language='en' autocomplete="off" id="date_to" placeholder="Date To" class="form-control">
										</div>
										
										<div class="col-xs-1">
											<button type="button" class="btn btn-primary" onClick="setSearch()">Search</button>
										</div>
										<div class="col-xs-1">
											<a href="" class="btn btn-primary getform">Barcode</a> 
											<!--<a href="" class="btn btn-primary barcode" data-toggle="modal" data-target="#barcode_modal">Barcode</a>-->
											<!--@can('item-location')<a href="" class="btn btn-primary location" data-toggle="modal" data-target="#location_modal">Location</a>@endcan-->
										</div>
										<div class="col-xs-1">
											<a href="" class="btn btn-primary supersede" data-toggle="modal" data-target="#supersede_modal">Supersede</a>
										</div>
										<div class="col-xs-1" style="padding-left:22px;">
											<a href="" class="btn btn-primary locQty" data-toggle="modal" data-target="#locqty_modal">Location</a>
										</div>
										<div class="col-xs-1" style="padding-left:22px;">
											<a href="" class="btn btn-primary viewBatch" data-toggle="modal" data-target="#viewbatch_modal">Batch</a>
										</div>
									</div>
								</form>
								
								<div id="location_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Stock Location</h4>
                                        </div>
                                        <div class="modal-body" id="historyData">Please select an Item first!
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
							
							<div id="barcode_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Item Barcode</h4>
                                        </div>
                                        <div class="modal-body" id="barcodeData">Please select an Item first!
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
							
							<div id="supersede_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Supersede Items</h4>
                                        </div>
                                        <div id="supersedeData">Please select an Item first!
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
							
							<div id="locqty_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Location Quantity</h4>
                                        </div>
                                        <div id="locqtyData">Please select an Item first!
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div id="viewbatch_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Item Batch</h4>
                                        </div>
                                        <div id="batchData">Please select an Item first!
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
							
                        </div>
                    </div>
            </div>
        </div>
       
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

<script src="{{asset('assets/vendors/mark.js/jquery.mark.js')}}" charset="UTF-8"></script>
<script src="{{asset('assets/vendors/datatablesmark.js/js/datatables.mark.min.js')}}" charset="UTF-8"></script>
<script src="{{asset('assets/js/custom_js/responsive_datatables.js')}}" type="text/javascript"></script>

<script src="{{asset('assets/vendors/datetime/js/jquery.datetimepicker.full.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.en.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/custom_js/advanceddate_pickers.js')}}"></script>

<!-- end of page level js -->

<script>

$('#date_from').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy' } );
$('#date_to').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy'} );

$(function() {	
	var hisurl = "{{ url('itemmaster/stock_location/') }}";
	$('.location').click(function() {
		var item_id = $("input[name='item']:checked").val();
		if(item_id!='') {
			$('#historyData').load(hisurl+'/'+item_id, function(result) {
				$('#myModal').modal({show:true});
			});
		}
	});
	
	var barurl = "{{ url('itemmaster/barcode/') }}";
	$('.barcode').click(function() {
		var item_id = $("input[name='item']:checked").val();
		if(item_id!='') {
			$('#barcodeData').load(barurl+'/'+item_id, function(result) {
				$('#myModal').modal({show:true});
			});
		}
	});
	
	var scurl = "{{url('itemenquiry/get_custsupp/')}}";
	$('#search_type').on('change', function(e) {
		$.get(scurl, function(data) { 
			$('#custsupp_id').find('option').remove().end();
			$('#custsupp_id').append('<option value="">Customer/Supplier</option>');
			$.each(JSON.parse(data), function(key, value) { 
				 $('#custsupp_id').append($('<option>', { 
					value: value.id,
					text : value.master_name 
				})); 
			});
		});
	});
	
	
	var sdurl = "{{ url('itemmaster/get_sedeinfo/') }}";
	$('.supersede').click(function() {
		var item_id = $("input[name='item']:checked").val();
		if(item_id!='') {
			$('#supersedeData').load(sdurl+'/'+item_id+"/1", function(result) {
				$('#myModal').modal({show:true});
			});
		}
	});
	
	
	var lqurl = "{{ url('itemmaster/get_locqty/') }}";
	$('.locQty').click(function() {
		var item_id = $("input[name='item']:checked").val();
		if(item_id!='') {
			$('#locqtyData').load(lqurl+'/'+item_id, function(result) {
				$('#myModal').modal({show:true});
			});
		}
	});
	
	var bthurl = "{{ url('itemmaster/get_batch/') }}";
	$('.viewBatch').click(function() {
		var item_id = $("input[name='item']:checked").val();
		if(item_id!='') {
			$('#batchData').load(bthurl+'/'+item_id, function(result) {
				$('#myModal').modal({show:true});
			});
		}
	});
	
	$(function() {
            
            var dtInstance = $("#ItemEnq").DataTable({
				"processing": true,
				"serverSide": true,
				"ajax":{
						 "url": "{{ url('itemenquiry/paging/') }}",
						 "dataType": "json",
						 "type": "POST",
						 "data":{ _token: "{{csrf_token()}}"}
					   },
				/* "scrollY":        500,
				"deferRender":    true,
				"scroller":       true, */
				"columns": [
				{ "data": "opt","bSortable": false },
				<?php foreach($cols as $col) { ?>
                { "data": "{{$col}}" },
                <?php } ?>
				{ "data": "edit","bSortable": false },
				{ "data": "delete","bSortable": false }
				
				/* { "data": "opt","bSortable": false },
                { "data": "item_code" },
                { "data": "description" },
				{ "data": "unit" },
				{ "data": "group" },
                { "data": "modelno" },
                { "data": "cost_avg" },
				{ "data": "sell_price" },
                { "data": "category" },
				{ "data": "subcategory" },
				{ "data": "model" },
				{ "data": "edit","bSortable": false },
				{ "data": "delete","bSortable": false } */
				
				/* { "data": "opt","bSortable": false },
                { "data": "item_code" },
                { "data": "description" },
				{ "data": "unit" },
				{ "data": "group" },
                { "data": "modelno" },
                { "data": "cost_avg" },
				{ "data": "sell_price" },
                { "data": "last_purchase_cost" },
				{ "data": "received_qty" },
				{ "data": "issued_qty" },
				{ "data": "edit","bSortable": false },
				{ "data": "delete","bSortable": false } */
            ]	
              
            });
     });
	
	$('.getform').click(function() {
		var item_id = $("input[name='item']:checked").val();
		if(item_id!='') {
			getForm(item_id);
		}
	});
	
});
	
function setSearch() {
	var checked=false;
	var elements = document.getElementsByName("item");
	for(var i=0; i < elements.length; i++) {
		if(elements[i].checked) {
			checked = true;
		}
	}
	if (!checked) {
		alert('Please select an Item!');
		return checked;
	} else {
		if( $('#search_type option:selected').val()=='') {
			alert('Please select search type');
		} else {
			$('#item_id').val( $("input[name='item']:checked").val() );
			document.frmItemSearch.action = "{{ url('itemenquiry/details') }}";
			document.frmItemSearch.submit();
		}
	}
}
function updateStock() {
	document.frmItemMasterUtility.action="{{ url('utilities/updateItemMasterStock/stock') }}";
	document.frmItemMasterUtility.submit();
}

function getForm(id) { 
	var itmurl = "{{ url('itemenquiry/openform/') }}/"+id;
	popup = window.open(itmurl, "Popup", "width=900,height=500,top=100,left=200");
	popup.focus();
	return false
}

</script>
@stop
