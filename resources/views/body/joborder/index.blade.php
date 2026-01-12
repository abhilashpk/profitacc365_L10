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
                Job Order
            </h1>
            <ol class="breadcrumb">
                <li>
                      <i class="fa fa-fw fa-shield"></i> Inventory
                </li>
				<li>
                    <a href="#">Job Order</a>
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
                            <i class="fa fa-fw fa-list-alt"></i> Job Order
                        </h3>
                        <div class="pull-right">
                             <a href="{{ url('job_order/add') }}" class="btn btn-primary btn-sm">
									<span class="btn-label">
									<i class="glyphicon glyphicon-plus"></i>
								</span> Add New
							</a>
                        </div>
                    </div>
                    <div class="panel-body">
						 <div class="row">
                               
                            </div>
                        <div class="table-responsive m-t-10">
                                <table class="table horizontal_table table-striped" id="tablePorders1">
                                    <thead>
                                    <tr>
										<th>JO.No</th>
										<th>JO.Date</th>
										<th>Customer</th>
										<th>Reg.No.</th>
										<th>Chasis No.</th>
										<th>Technician</th>
										<th></th><th></th><th></th><th></th>
                                    </tr>
                                    </thead>
                                   <!-- <tbody>
										@foreach($quotations as $quotation)
										<tr>
											<td>{{ $quotation->prefix.$quotation->voucher_no }}</td>
											<td>{{ date('d-m-Y', strtotime($quotation->voucher_date)) }}</td>
											<td>{{ $quotation->customer }}</td>
											<td>{{ $quotation->vehicle }}</td>
											<td>{{ $quotation->reg_no }}</td>
											<td>
												<p><button class="btn btn-primary btn-xs" onClick="location.href='{{ url('job_order/edit/'.$quotation->id)}}'"><span class="glyphicon glyphicon-pencil"></span></button></p>
											</td>
											<td>
												<p><a href="{{url('job_order/print/'.$quotation->id)}}" target="_blank" class="btn btn-primary btn-xs"><span class="fa fa-fw fa-print"></span></a></p>
											</td>
											<td>
												<p><button class="btn btn-danger btn-xs delete" onClick="funDelete('{{ $quotation->id }}','{{ $quotation->is_editable }}')"><span class="glyphicon glyphicon-trash"></span></button></p>
											</td>
										</tr>
										@endforeach
                                    </tbody>-->
                                </table>
                            </div>
                    </div>
                </div>
            </div>
		</section>
		
		<section class="content">
			<form class="form-horizontal" role="form" method="POST" name="frmQReport" id="frmQReport" action="{{ url('job_order/search') }}" target="_blank">
			 <input type="hidden" name="_token" value="{{ csrf_token() }}">
			 <div class="row">
			 <div class="col-lg-12">
					<div class="panel panel-info">
						<div class="panel-heading clearfix">
							<h3 class="panel-title pull-left m-t-6">
								<i class="fa fa-fw fa-list-alt"></i> Job Order Report
							</h3>
						</div>
						<div class="panel-body">
								<div class="row">
									<div class="col-xs-6">
										<span>Date From:</span>
										<input type="text" name="date_from" data-language='en' autocomplete="off" id="date_from" class="form-control input-sm">
										<span>Date To:</span>
										<input type="text" name="date_to" data-language='en' autocomplete="off" id="date_to" class="form-control input-sm">
										<span>Customer</span>
										<select id="select23" class="form-control select2" multiple style="width:100%" name="customer_id">
											<option value="">Select customer...</option>
											@foreach($cus as $row)
												<option value="{{$row->id}}">{{$row->master_name}}</option>
											@endforeach
										</select>
									</div>
									<div class="col-xs-6">
										<span>Search By:</span>
										<select id="search_type" class="form-control select2" style="width:100%" name="search_type">
											<option value="summary">Summary</option>
											
											<option value="detail">Detail</option>
											<!--<option value="summary_pending">Summary(Pending Order)</option>
											<option value="detail_pending">Detail(Pending Order)</option>
											<option value="qty_report">Quantity Report</option>-->
										</select>
										
											<span>Job:</span><br/>
											<select id="select21" multiple style="width:100%" class="form-control select2" name="job_id[]">
											@foreach($jobmasters as $row)
											   <option value="{{$row['id']}}">{{$row['code']}}</option> 
											@endforeach												
											</select>
										

										<span>Technician</span>
										<select id="select22" class="form-control select2" style="width:100%" name="salesman">
											<option value="">--Select Technician--</option>
											@foreach($salesman as $row)
											<option value="{{$row->id}}">{{$row->name}}</option>
											@endforeach
										</select><br/>
										<input type="checkbox" name="pending" value="1"> Pending
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
<script src="{{asset('assets/vendors/bootstrap-multiselect/js/bootstrap-multiselect.js')}}" type="text/javascript"></script>

<script src="{{asset('assets/vendors/mark.js/jquery.mark.js')}}" charset="UTF-8"></script>
<script src="{{asset('assets/vendors/datatablesmark.js/js/datatables.mark.min.js')}}" charset="UTF-8"></script>
<script src="{{asset('assets/js/custom_js/responsive_datatables.js')}}" type="text/javascript"></script>
<!-- end of page level js -->

<script src="{{asset('assets/vendors/datetime/js/jquery.datetimepicker.full.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.en.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/custom_js/advanceddate_pickers.js')}}"></script>

<script src="{{asset('assets/vendors/select2/js/select2.js')}}" type="text/javascript"></script>

<script>

$('#date_from').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy' } );
$('#date_to').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy'} );

function funDelete(id,no) {
	if(no=='0') {
		var con = confirm('Are you sure delete this job order?');
		if(con==true) {
			var url = "{{ url('job_order/delete/') }}";
			location.href = url+'/'+id;
		}
	} else 
		alert('This order is processed, you cannot edit or delete');
}

$(function() {
		
		var dtInstance = $("#tablePorders1").DataTable({
			"processing": true,
			"serverSide": true,
			"searching": true,
			"ajax":{
					 "url": "{{ url('job_order/paging/') }}",
					 "dataType": "json",
					 "type": "POST",
					 "data":{ _token: "{{csrf_token()}}"}
				   },
			"columns": [
			{ "data": "voucher_no" },
			{ "data": "voucher_date" },
			{ "data": "customer" },
			{ "data": "reg_no" },
			{ "data": "chasis_no" },
			{ "data": "technician" },
			@can('job-order-edit'){ "data": "edit","bSortable": false },@endcan
			@can('job-order-view'){ "data": "viewonly","bSortable": false },@endcan
			@can('job-order-print'){ "data": "print","bSortable": false },@endcan
			@can('job-order-delete'){ "data": "delete","bSortable": false },@endcan
		]	
		  
		});
		
			$('#select23').on('change', function(e){
			    var cust_id = $('#select23').val();
			    console.log(cust_id);
			     if(cust_id !=''){
			    $.get("{{ url('sales_order/getjob/') }}/" + cust_id, function(data) {
				$('#select21').find('option').remove().end();
				$.each(data, function(key, value) {   
				$('#select21').find('option').end()
				 .append($("<option></option>")
							.attr("value",value.id)
							.text(value.code)); 
				});
			});
			    }
		});
});

$(document).on('change', '.techn', function(e) {  
	
	var res = this.id.split('_');
	console.log(res);
	var id = res[1];
	console.log(id);
	var tech = $('#tech_'+id).val();
	console.log(tech);
	$.ajax({
		url: "{{ url('job_order/set_technician/') }}",
		type: 'get',
		data: 'tech='+tech+'&id='+id,
		success: function(data) {
			console.log(data);
			alert('Technician has assigned successfully.')
			return true;
		}
	})
	
});
$(document).ready(function () {

	$("#select21").select2({
        theme: "bootstrap",
        placeholder: "Job"
    });
    $("#select22").select2({
        theme: "bootstrap",
        placeholder: "Technician"
    });
$("#select23").select2({
        theme: "bootstrap",
        placeholder: "Customer"
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
	
    $("#multiselect8").multiselect({
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
