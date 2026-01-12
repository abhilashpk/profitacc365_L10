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
    <!--end of page level css-->
		
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <!--section starts-->
            <h1>
                Sales Order Booking  List
            </h1>
            <ol class="breadcrumb">
                <li>
                      <i class="fa fa-fw fa-shield"></i> Sales Order Job
                </li>
				<li>
                    <a href="#">Sales Order Booking List</a>
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
                            <i class="fa fa-fw fa-list-alt"></i> Sales Order Booking List
                        </h3>
                        <div class="pull-right">
						
                        </div>
                    </div>
                    <div class="panel-body">
						 <div class="row">
                               <div class="col-sm-3">
									<select class="form-control" id="searchBy">
									<option value="">Select Service</option>
									@foreach($servitem as $row)
									<option value="{{$row->id}}">{{$row->description}}</option>
									@endforeach
									</select>
								</div>
                            </div>
							<div class="table-responsive m-t-10">
                                <table class="table horizontal_table table-striped" id="tableSOBList">
                                    <thead>
                                    <tr>
										<th>SO. No</th>
										<th>Customer</th>
										<th>SO. Date</th>
										<th>Due Date</th>
										<th>View</th>
                                    </tr>
                                    </thead>
                                    <tbody>
										
                                    </tbody>
                                </table>
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
                            <i class="fa fa-fw fa-list-alt"></i> Sales Order Completed List - Ready to Deliver
                        </h3>
                        <div class="pull-right">
						
                        </div>
                    </div>
                    <div class="panel-body">
						 <div class="row">
                               <div class="col-sm-3">
									<select class="form-control" id="searchByCom">
									<option value="">Select Service</option>
									@foreach($servitem as $row)
									<option value="{{$row->id}}">{{$row->description}}</option>
									@endforeach
									</select>
								</div>
                            </div>
							<div class="table-responsive m-t-10">
                                <table class="table horizontal_table table-striped" id="tableSOBListCom">
                                    <thead>
                                    <tr>
										<th>SO. No</th>
										<th>Customer</th>
										<th>SO. Date</th>
										<th>Due Date</th>
										<th>Delivery</th>
										<th>View</th>
                                    </tr>
                                    </thead>
                                    <tbody>
										
                                    </tbody>
                                </table>
                            </div>
                    </div>
                </div>
            </div>
		</section>
	
@stop

{{-- page level scripts --}}
@section('footer_scripts')

    <!-- begining of page level js -->
<script type="text/javascript" src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/iCheck/js/icheck.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-fileinput/js/fileinput.min.js')}}"></script>

<script src="{{asset('assets/vendors/mark.js/jquery.mark.js')}}" charset="UTF-8"></script>
<script src="{{asset('assets/vendors/datatablesmark.js/js/datatables.mark.min.js')}}" charset="UTF-8"></script>
<script src="{{asset('assets/js/custom_js/responsive_datatables.js')}}" type="text/javascript"></script>

<script type="text/javascript" src="{{asset('assets/vendors/custom_js/form_elements.js')}}"></script>
<script src="{{asset('assets/vendors/select2/js/select2.js')}}" type="text/javascript"></script>

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
<!-- end of page level js -->

<script src="{{asset('assets/vendors/datetime/js/jquery.datetimepicker.full.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.en.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/custom_js/advanceddate_pickers.js')}}"></script>

<script>
$(function() {
		
		var dtInstance = $("#tableSOBList").DataTable({
			"processing": true,
			"serverSide": true,
			"searching": true,
			"ajax":{
					 "url": "{{ url('sales_order_booking/paging_list/') }}",
					 "dataType": "json",
					 "type": "POST",
					 "data":{ _token: "{{csrf_token()}}"}
				   },
			"columns": [
				{ "data": "voucher_no" },
				{ "data": "customer" },
				{ "data": "voucher_date" },
				{ "data": "due_date" },
				{ "data": "view","bSortable": false }
			]	
		});
		
		var dtInstance = $("#tableSOBListCom").DataTable({
			"processing": true,
			"serverSide": true,
			"searching": true,
			"ajax":{
					 "url": "{{ url('sales_order_booking/paging_list_com/') }}",
					 "dataType": "json",
					 "type": "POST",
					 "data":{ _token: "{{csrf_token()}}"}
				   },
			"columns": [
				{ "data": "voucher_no" },
				{ "data": "customer" },
				{ "data": "voucher_date" },
				{ "data": "due_date" },
				{ "data": "delivery" },
				{ "data": "view","bSortable": false }
			]	
		});
		
		$(document).on('change','#searchBy', function() {
			var table = $('#tableSOBList').DataTable();
			table.search( this.value ).draw();
		}) 
		
		$(document).on('change','#searchByCom', function() {
			var table = $('#tableSOBListCom').DataTable();
			table.search( this.value ).draw();
		})
});

</script>

@stop
