@extends('layouts/default')

    {{-- Page title --}}
    @section('title')
         
        @parent
    @stop

{{-- page level styles --}}
@section('header_styles')
    
	<link rel="stylesheet" href="{{asset('assets/vendors/datetime/css/jquery.datetimepicker.css')}}">
    <link href="{{asset('assets/vendors/airdatepicker/css/datepicker.min.css')}}" rel="stylesheet" type="text/css">
	
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
                Purchase Order
            </h1>
            <ol class="breadcrumb">
                <li>
                      <i class="fa fa-fw fa-shield"></i> Inventory
                </li>
				<li>
                    <a href="#">Purchase Order</a>
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
                            <i class="fa fa-fw fa-list-alt"></i> Purchase Order
                        </h3>
                        <div class="pull-right">
							@can('po-create')
                             <a href="{{ url('purchase_order/add') }}" class="btn btn-primary btn-sm">
									<span class="btn-label">
									<i class="glyphicon glyphicon-plus"></i>
								</span> Add New
							</a>
							@endcan
                        </div>
                    </div>
                    <div class="panel-body">
					
						
                        <div class="table-responsive m-t-10">
                                <table class="table horizontal_table table-striped" id="tablePorders">
                                    <thead>
                                    <tr>
										<th>PO. No</th>
										<!--<th>Reference No</th>-->
										<!--<th>Description</th>-->
										<th>PO. Date</th>
										<th>Supplier</th>
										<th>Amount</th>
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
		</section>
		
		<section class="content">
			<form class="form-horizontal" role="form" method="POST" name="frmPOReport" target="_blank" id="frmPOReport" action="{{ url('purchase_order/search') }}">
			 <input type="hidden" name="_token" value="{{ csrf_token() }}">
			 <div class="row">
			 <div class="col-lg-12">
					<div class="panel panel-info">
						<div class="panel-heading clearfix">
							<h3 class="panel-title pull-left m-t-6">
								<i class="fa fa-fw fa-list-alt"></i> Purchase Order Report
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
											<option value="summary_pending">Summary(Pending Order)</option>
											<option value="detail">Detail</option>
											<option value="detail_pending">Detail(Pending Order)</option>
										</select>
										
										<span>Foreign Currency:</span>
										<select id="currency_id" class="form-control select2" style="width:100%" name="currency_id">
											<option value="">Select Foreign Currency...</option>
											@foreach($currency as $curr)
												<option value="{{$curr->id}}">{{$curr->code}}</option>
											@endforeach
										</select>
										<span></span><br/>
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

<script src="{{asset('assets/vendors/mark.js/jquery.mark.js')}}" charset="UTF-8"></script>
<script src="{{asset('assets/vendors/datatablesmark.js/js/datatables.mark.min.js')}}" charset="UTF-8"></script>
<script src="{{asset('assets/js/custom_js/responsive_datatables.js')}}" type="text/javascript"></script>

<script src="{{asset('assets/vendors/datetime/js/jquery.datetimepicker.full.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.en.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/custom_js/advanceddate_pickers.js')}}"></script>
<!-- end of page level js -->

<script>

function funDelete(id,no) {
	if(no=='0') {
		var con = confirm('Are you sure delete this purchase order?');
		if(con==true) {
			var url = "{{ url('purchase_order/delete/') }}";
		 location.href = url+'/'+id;
		}
	} else 
		alert('This order is processed, you cannot edit or delete');
}

$(function() {
		
		var dtInstance = $("#tablePorders").DataTable({
			"processing": true,
			"serverSide": true,
			"searching": true,
			"ajax":{
					 "url": "{{ url('purchase_order/paging/') }}",
					 "dataType": "json",
					 "type": "POST",
					 "data":{ _token: "{{csrf_token()}}"}
				   },
			"columns": [
			{ "data": "voucher_no" },
			{ "data": "voucher_date" },
			{ "data": "supplier" },
			{ "data": "net_total" },
			@can('si-edit'){ "data": "edit","bSortable": false },@endcan
			@can('si-print'){ "data": "print","bSortable": false },@endcan
			@can('si-delete'){ "data": "delete","bSortable": false }@endcan
		]	
		  
		});
 });
 
</script>

@stop
