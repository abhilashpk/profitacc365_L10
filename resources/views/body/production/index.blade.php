@extends('layouts/default')

    {{-- Page title --}}
    @section('title')
         
        @parent
    @stop

{{-- page level styles --}}
@section('header_styles')
    
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
                Production Order
            </h1>
            <ol class="breadcrumb">
                <li>
                      <i class="fa fa-fw fa-shield"></i> Inventory
                </li>
				<li>
                    <a href="#">Production Order</a>
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
                            <i class="fa fa-fw fa-list-alt"></i> Production Order
                        </h3>
                        <div class="pull-right">
                             @can('do-create')
							 <a href="{{ url('production/add') }}" class="btn btn-primary btn-sm">
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
                                <table class="table horizontal_table table-striped" id="tablePordersList">
                                    <thead>
                                    <tr>
										<th>PRO. No</th>
										<!--<th>Reference No</th>-->
										<!--<th>Description</th>-->
										<th>Customer</th>
										<th>PRO. Date</th>
										<th>Item Name</th>
										<th>Qty.</th>
										<?php if($settings->doc_approve==1) { ?><th>Status</th><?php } ?>
										<th></th><th></th><th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
										@foreach($orders as $order)
										<tr>
											<td>{{ $order->voucher_no }}</td>
											<!--<td>{{ $order->reference_no }}</td>-->
											<!--<td>{{ $order->description }}</td>-->
											<td>{{ $order->customer }}</td>
											<td>{{ date('d-m-Y', strtotime($order->voucher_date)) }}</td>
											<td>{{$order->net_total}}</td>
											<td>
												@can('do-edit')<p><button class="btn btn-primary btn-xs" onClick="location.href='{{ url('production/edit/'.$order->id)}}'"><span class="glyphicon glyphicon-pencil"></span></button></p>@endcan
											</td>
											<td>
												@can('do-print')<p><a href="{{url('production/print/'.$order->id)}}" target="_blank" class="btn btn-primary btn-xs"><span class="fa fa-fw fa-print"></span></a></p>@endcan
											</td>
											<?php if($order->is_fc==1) { ?><td>
												@can('do-print')<p><a href="{{url('production/print/'.$order->id.'/FC')}}" target="_blank" class="btn btn-primary btn-xs"><span class="fa fa-fw fa-print"></span> FC</a></p>@endcan
											</td><?php } ?>
											
											<td>
												@can('do-delete')<p><button class="btn btn-danger btn-xs delete" onClick="funDelete('{{ $order->id }}')"><span class="glyphicon glyphicon-trash"></span></button></p>@endcan
											</td>
										</tr>
										@endforeach
                                    </tbody>
                                </table>
                            </div>
                    </div>
                </div>
            </div>
		</section>
		
		<section class="content">
			<form class="form-horizontal" role="form" method="POST" name="frmQReport" id="frmQReport" target="_blank" action="{{ url('production/search') }}" target="_blank">
			 <input type="hidden" name="_token" value="{{ csrf_token() }}">
			 <div class="row">
			 <div class="col-lg-12">
					<div class="panel panel-info">
						<div class="panel-heading clearfix">
							<h3 class="panel-title pull-left m-t-6">
								<i class="fa fa-fw fa-list-alt"></i> Production Order Report
							</h3>
						</div>
						<div class="panel-body">
								<div class="row">
									<div class="col-xs-6">
										<span>Date From:</span>
										<input type="text" name="date_from" data-language='en' autocomplete="off" id="date_from" class="form-control input-sm">
										<span>Date To:</span>
										<input type="text" name="date_to" data-language='en' autocomplete="off" id="date_to" class="form-control input-sm">
										<span>Customers:</span> 
                                        <select id="select21" multiple style="width:100%" class="form-control select2" name="customer_id[]">
                                        <option value="">Select Customers...</option> 
										@foreach($customer as $row)
												<option value="{{$row['id']}}">{{$row['master_name']}}</option>
											@endforeach				
										
                                     </select>
									</div>
									<div class="col-xs-6">
										<span>Search By:</span>
										<select id="search_type" class="form-control select2" style="width:100%" name="search_type">
											<option value="summary">Summary</option>
											<!-- <option value="summary_pending">Summary(Pending PRO)</option> -->
											<option value="detail">Detail</option>
											<!-- <option value="detail_pending">Detail(Pending PRO)</option> -->
										</select>
					
										<span>Salesman</span>
										<select id="select27" class="form-control select2" style="width:100%" name="salesman">
											<option value="">--Select Salesman--</option>
											@foreach($salesman as $row)
											<option value="{{$row->id}}">{{$row->name}}</option>
											@endforeach
										</select>
										<span>Job No:</span>
										<select id="select28" class="form-control select2" multiple style="width:100%" name="job_id[]">
											<option value="">Select Job No...</option>
											@foreach($jobs as $job)
												<option value="{{$job['id']}}">{{$job['code']}}</option>
											@endforeach
										</select>
										<br/>
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
<!-- end of page level js -->

<script>

$('#date_from').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy' } );
$('#date_to').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy' } );

function funDelete(id) {
	var con = confirm('Are you sure delete this order?');
	if(con==true) {
		var url = "{{ url('production/delete/') }}";
		location.href = url+'/'+id;
	}
}

$(document).ready(function () {
	$("#select21").select2({
        theme: "bootstrap",
        placeholder: "Customers"
    });
     $("#select27").select2({
        theme: "bootstrap",
        placeholder: "Salesman"
    });
     $("#select28").select2({
        theme: "bootstrap",
        placeholder: "Job No:"
    });
});
$(function() {
		
		var dtInstance = $("#tablePordersList").DataTable({
			"processing": true,
			"serverSide": true,
			"searching": true,
			"ajax":{
					 "url": "{{ url('production/paging/') }}",
					 "dataType": "json",
					 "type": "POST",
					 "data":{ _token: "{{csrf_token()}}"}
				   },
			"columns": [
			{ "data": "voucher_no" },
			{ "data": "customer" },
			{ "data": "voucher_date" },
			{ "data": "item_name" },
			{ "data": "net_total" },
		
			<?php if($settings->doc_approve==1) { ?>{ "data": "status" },<?php } ?>
			@can('do-edit'){ "data": "edit","bSortable": false },@endcan
			@can('do-print'){ "data": "print","bSortable": false },@endcan
			@can('do-delete'){ "data": "delete","bSortable": false },@endcan
		]	
		  
		});
		
			$('#select21').on('change', function(e){
			    var cust_id = $('#select21').val();
			    console.log(cust_id);
			     if(cust_id !=''){
			    $.get("{{ url('production/getjob/') }}/" + cust_id, function(data) {
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

</script>

@stop
