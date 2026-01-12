@extends('layouts/default')

    {{-- Page title --}}
    @section('title')
         
        @parent
    @stop

{{-- page level styles --}}
@section('header_styles')
   	    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/iCheck/css/all.css')}}" />
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrap-fileinput/css/fileinput.min.css')}}" media="all" />
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/formelements.css')}}">
        <!--end of page level css-->
	
	<link rel="stylesheet" href="{{asset('assets/vendors/datetime/css/jquery.datetimepicker.css')}}">
    <link href="{{asset('assets/vendors/airdatepicker/css/datepicker.min.css')}}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/datepicker.css')}}">
	<!--page level css -->
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/buttons.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/colReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/dataTables.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/rowReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/scroller.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatablesmark.js/css/datatables.mark.min.css')}}"/>
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
            Consignment Receipt
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="">
                        <i class="fa fa-fw fa-building-o"></i> Cargo Entry
                    </a>
                </li>
                <li>
                    <a href="#">Consignment Receipt</a>
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
                <div class="panel panel-primary">
                    <div class="panel-heading clearfix">
                        <h3 class="panel-title pull-left m-t-6">
                            <i class="fa fa-fw fa-list-alt"></i> Consignment Receipt
                        </h3>
                        <div class="pull-right">
						<a href="{{ url('cargo_receipt/add') }}" class="btn btn-info btn-sm">
									<span class="btn-label">
									<i class="glyphicon glyphicon-plus"></i>
								</span> Add New
							</a>
							
                        </div>
                    </div>
                    <div class="panel-body">
						<div class="row">
							<div class="col-sm-3">
								<select class="form-control" id="searchBy">
									<option value="">All</option>
									<option value="Closed">Closed</option>
									<option value="Open">Open</option>
									<option value="Return">Return</option>
								</select>
							</div>
                        </div>
                        <div class="table-responsive">
                                <table class="table table-striped" id="tblCargoEntry">
                                    <thead>
                                    <tr>
                                        <th>Cons.No: </th>
                                        <th>Cons.Date </th>
										<th>Time</th>
										<th>Consignee</th>
										<th>Shipper</th>
										<th>Pack. Qty.</th>
										<th>Dspched. Qty.</th>
										<th>Balance Qty.</th>
										<th></th>
										<th></th>
										<th></th>
										<th></th>
										<th></th>
										<th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                    </div>
                </div>
            </div>
        </div>
        </section>
		
		<section class="content">
            <div class="row">
				<div class="col-lg-12">
				   <div class="panel panel-info">
							<div class="panel-heading clearfix">
								<h3 class="panel-title pull-left m-t-6">
									<i class="fa fa-fw fa-columns"></i> Cargo Receipt Report
								</h3>
							</div>
							<div class="panel-body">
								<form class="form-horizontal" role="form" method="POST" name="frmReport" id="frmReport" target="_blank" action="{{ url('cargo_receipt/report') }}">
									<input type="hidden" name="_token" value="{{ csrf_token() }}">
									<div class="row">
									<div class="col-xs-6">
										<span>Date From:</span>
										<input type="text" name="date_from" data-language='en' autocomplete="off" id="date_from" class="form-control">
										<span>Date To:</span>
										<input type="text" name="date_to" data-language='en' autocomplete="off" id="date_to" class="form-control">
										
									</div>
									<div class="col-xs-6">
										<span>Status:</span>
										<select id="search_type" class="form-control select2" style="width:100%" name="search_type">
											<option value="">All</option>
											<option value="1">Closed</option>
											<option value="0">Open</option>
											<option value="-1">Return</option>
										</select>
										<span></span><br/>
										<div class="col-xs-12" align="right"> <button type="submit" class="btn btn-primary">Search</button></div>
									</div>
								</div>
								</form>
								
							</div>
						</div>
				</div>
			</div>
        </section>
		
		<div id="status_modal" class="modal fade animated" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Cargo Receipt Status</h4>
					</div>
					<div class="modal-body" id="statusForm">
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
<script type="text/javascript" src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/iCheck/js/icheck.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-fileinput/js/fileinput.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/custom_js/form_elements.js')}}"></script>
        <!-- end of page level js -->

<script src="{{asset('assets/vendors/moment/js/moment.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/datetime/js/jquery.datetimepicker.full.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.en.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/custom_js/advanceddate_pickers.js')}}"></script>

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
<script type="text/javascript" src="{{asset('assets/vendors/toolbar/js/jquery.toolbar.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/tabs_accordions.js')}}"></script>
<!-- end of page level js -->
<script>

$(function() {
            
	var dtInstance = $("#tblCargoEntry").DataTable({
			"processing": true,
			"serverSide": true,
			"searching": true,
			"order": [[ 0, 'desc' ]],
			"ajax":{
					 "url": "{{ url('cargo_receipt/paging/') }}",
					 "dataType": "json",
					 "type": "POST",
					 "data":{ _token: "{{csrf_token()}}"}
				   },
			"columns": [
			{ "data": "jobcode" },
			{ "data": "jobdate" },
			{ "data": "time" },
			{ "data": "consignee" },
			{ "data": "shipper" },
			{ "data": "packqty" },
			{ "data": "dspedqty" },
			{ "data": "balanceqty" },
			{ "data": "status","bSortable": false },
			{ "data": "return","bSortable": false },
			{ "data": "edit","bSortable": false },
			{ "data": "delete","bSortable": false },
			{ "data": "print","bSortable": false },
			{ "data": "preprint","bSortable": false }
		]	
	});
	
	$(document).on('change','#searchBy', function() {
		var table = $('#tblCargoEntry').DataTable();
		table.search( this.value ).draw();
	}) 
	
	var stsurl = "{{ url('cargo_receipt/get_status/') }}";
	$(document).on('click','.getSts', function() { 
		$('#statusForm').load(stsurl+'/'+$(this).attr("data-id"), function(result) {
			$('#myModal').modal({show:true});
		});
	});
		  
});
function funReturn(id) {
	var con = confirm('Are you sure retrun this ?');
	if(con==true) {
		var url = "{{ url('cargo_receipt/return/') }}";
		location.href = url+'/'+id;
	}
}
	
function funDelete(id) {
	var con = confirm('Are you sure delete this entry?');
	if(con==true) {
		var url = "{{ url('cargo_receipt/delete/') }}";
		location.href = url+'/'+id;
	}
}


</script>

@stop
