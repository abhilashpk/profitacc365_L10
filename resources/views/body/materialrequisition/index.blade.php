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
	
	<!--page level css -->
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
            @foreach($mod_purchase_enquiry as $mod_purchase_enquiry)
            <h1>
            	
               <?php  if($mod_purchase_enquiry==1) {
				$heading="Purchase Enquiry";
			}
			else 
				$heading="Material Requisition"; echo "$heading"; ?>

            </h1>
            
            <ol class="breadcrumb">
                <li>
                      <i class="fa fa-fw fa-shield"></i> Inventory
                </li>
				<li>
                    <a href="#"><?php echo "$heading"; ?></a>
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
                            <i class="fa fa-fw fa-list-alt"></i> <?php echo "$heading"; ?>
                        </h3>
                        <div class="pull-right">
                             <a href="{{ url('material_requisition/add') }}" class="btn btn-primary btn-sm">
									<span class="btn-label">
									<i class="glyphicon glyphicon-plus"></i>
								</span> Add New
							</a>
                        </div>
                    </div>
                    <div class="panel-body">
						
                        <div class="table-responsive m-t-10">
                                <table class="table horizontal_table table-striped" id="tablePorders">
                                    <thead>
                                    <tr>
										<th>MR. No</th>
										<th>MR. Date</th>
										<th>Supplier</th>
										<th>Job No</th>
										<th>Amount</th>
									@if($mod_purchase_enquiry==0)	<th>Approved User</th>@endif
										<th></th><th></th><th></th><th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
										@foreach($matrec as $order)
										<tr>
											<td>{{ $order->voucher_no }}</td>
											<td>{{ date('d-m-Y', strtotime($order->voucher_date)) }}</td>
											<td>{{ $order->supplier }}</td>
											<td>{{ $order->code }}</td>
											<td>{{ number_format($order->net_amount,2) }}</td>
											<td>
												<p><button class="btn btn-primary btn-xs" onClick="location.href='{{ url('material_requisition/edit/'.$order->id)}}' target=_blank"><span class="glyphicon glyphicon-pencil"></span></button></p>
											</td>
											<td>
												<p><a href="{{url('material_requisition/print/'.$order->id)}}" class="btn btn-primary btn-xs" target="_blank"><span class="fa fa-fw fa-print"></span></a></p>
											</td>
											<td>
												<p><button class="btn btn-danger btn-xs delete" onClick="funDelete('{{ $order->id }}')"><span class="glyphicon glyphicon-trash"></span></button></p>
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
			<form class="form-horizontal" role="form" method="POST" name="frmReport" id="frmReport" target="_blank" action="{{ url('material_requisition/search') }}">
			 <input type="hidden" name="_token" value="{{ csrf_token() }}">
			 <div class="row">
			 <div class="col-lg-12">
					<div class="panel panel-info">
						<div class="panel-heading clearfix">
							<h3 class="panel-title pull-left m-t-6">
								<i class="fa fa-fw fa-list-alt"></i> <?php echo "$heading"; ?> Report
							</h3>
						</div>
						<div class="panel-body">
								<div class="row">
									<div class="col-xs-6">
										<span>Date From:</span>
										<input type="text" name="date_from" data-language='en' autocomplete="off" id="date_from" class="form-control">
										<span>Date To:</span>
										<input type="text" name="date_to" data-language='en' autocomplete="off" id="date_to" class="form-control">
										<span>Engineer</span>
										<select id="salesman" class="form-control select2" style="width:100%" name="salesman">
											<option value="">Select Engineer</option>
											@foreach($salesman as $row)
											<option value="{{$row->id}}">{{$row->name}}</option>
											@endforeach
										</select>
									</div>
									<div class="col-xs-6">
										<span>Search By:</span>
										<select id="search_type" class="form-control select2" style="width:100%" name="search_type">
											<option value="summary">Summary</option>
											<option value="summary_pending">Summary(Pending Order)</option>
											<option value="detail">Detail</option>
											<option value="detail_pending">Detail(Pending Order)</option>
											<option value="qty_report">Quantity Report</option>
										</select>
										<span>Job No</span>
										<select id="select21" class="form-control select2" multiple style="width:100%" name="job_id[]">
											<option value="">Job No...</option>
											@foreach($jobs as $job)
												<option value="{{$job['id']}}">{{$job['code']}}</option>
											@endforeach
										</select>
										
										
										<span>Itemwise</span>
										<select id="select22" class="form-control select2" style="width:100%" name="item_id">
											<option value="">Select Item...</option>
											@foreach($items as $item)
												<option value="{{$item['id']}}">{{$item['description']}}</option>
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
		@endforeach
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

   $('#date_from').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy' } );
   $('#date_to').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy'} );

$(document).ready(function () {
	$("#select21").select2({
        theme: "bootstrap",
        placeholder: "Job No"
    });
	
	$("#select22").select2({
        theme: "bootstrap",
      //  placeholder: "Select Item"
    });

});

function funDelete(id) {
	var con = confirm('Are you sure delete this record?');
	if(con==true) {
		var url = "{{ url('material_requisition/delete/') }}";
		location.href = url+'/'+id;
	}
}

$(function() {
		
		var dtInstance = $("#tablePorders").DataTable({
			"processing": true,
			"serverSide": true,
			"searching": true,
			"ajax":{
					 "url": "{{ url('material_requisition/paging/') }}",
					 "dataType": "json",
					 "type": "POST",
					 "data":{ _token: "{{csrf_token()}}"}
				   },
			"columns": [
			{ "data": "voucher_no" },
			{ "data": "voucher_date" },
			{ "data": "supplier" },
			{ "data": "jobname" },
			{ "data": "net_amount" },
		@if($modpurenq==0)	{ "data": "approved_user" },@endif
			{ "data": "edit","bSortable": false },
			{ "data": "print","bSortable": false },
		@if($modpurenq==0)	{ "data": "view","bSortable": false },@endif
			{ "data": "delete","bSortable": false }
		]	
		  
		});
 });
 
</script>

@stop
