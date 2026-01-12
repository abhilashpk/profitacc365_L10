@extends('layouts/default')

   
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
                Log Details
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="glyphicon glyphicon-folder-close"></i> Administration
                    </a>
                </li>
                <li>
                    <a href="#">Log Details</a>
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
                                <i class="fa fa-fw fa-columns"></i> Log Details
                            </h3>
                        </div>
                        <div class="panel-body">
							<form class="form-horizontal" role="form" method="POST" name="frmLogDetails" id="frmLogDetails" action="{{ url('logdetails/search') }}">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								<div class="row">
									<div class="col-xs-6">
										<span>Log Module:</span>
										<select id="log_module" class="form-control select2" style="width:100%" name="log_module">
											<option value="account_tr" <?php if($logtype=='account_tr') echo 'selected';?>>Account Transaction</option>
											<option value="inventory_tr" <?php if($logtype=='inventory_tr') echo 'selected';?>>Inventory Transaction</option>
											<option value="account_master" <?php if($logtype=='account_master') echo 'selected';?>>Account Master</option>
											<option value="item_master" <?php if($logtype=='item_master') echo 'selected';?>>Item Master</option>
											<option value="document_tr" <?php if($logtype=='document_tr') echo 'selected';?>>Document Transaction</option>
										</select>
										<span>Date From:</span>
										<input type="text" name="date_from" data-language='en' id="date_from" value="<?php echo $fromdate; ?>" class="form-control input-sm" autocomplete="off">
									</div>
									<div class="col-xs-6">
										<span>Transaction Type:</span>
										<select id="tr_type" class="form-control select2" style="width:100%" name="tr_type">
											<option value="creation" <?php if($trtype=='creation') echo 'selected';?>>Creation</option>
											<option value="modification" <?php if($trtype=='modification') echo 'selected';?>>Modification</option>
											<option value="deletion" <?php if($trtype=='deletion') echo 'selected';?>>Deletion</option>
										</select>
										<span>Date To:</span>
										<input type="text" name="date_to" data-language='en' id="date_to" value="<?php echo $todate; ?>" class="form-control input-sm" autocomplete="off"><br/>
										<div class="col-xs-12" align="right"> <button type="submit" class="btn btn-primary">Search</button></div>

									</div>
								</div>
								<?php if($reports!=null) { ?>
								
								<div class="table-responsive m-t-10">
								<?php if($logtype=='account_tr') { ?>
									<h3>Transaction List</h3>
									<table class="table horizontal_table table-striped" id="tableAcmaster">
										<thead>
											<tr>
												<th>Tr.Date</th>
												<th>JV Type</th>
												<th>JV No.</th>
												<th>Reference</th>
												<th>Account Name</th>
												<?php if($trtype=='creation' || $trtype=='modification' || $trtype=='deletion') { ?>
												<th>User</th>
												<th>Created Dt.</th>
												<?php } 
												if($trtype=='modification') { ?>
												<th>User</th>
												<th>Modify Dt.</th>
												<?php } 
												if($trtype=='deletion') { ?>
												<th>User</th>
												<th>Delete Dt.</th>
												<?php } ?>
											</tr>
										</thead>
										<tbody>
											@foreach($reports as $report)
											<tr>
												<td>{{date('d-m-Y', strtotime($report->invoice_date))}}</td>
												<td>{{ $report->voucher_type }}</td>
												<td>{{ $report->voucher_type_id }}</td>
												<td>{{ $report->reference }}</td>
												<td>{{ $report->master_name }}</td>
												<?php if($trtype=='creation' || $trtype=='modification' || $trtype=='deletion') { ?>
												<td>{{ $report->name }}</td>
												<td>{{date('d-m-Y h:i A', strtotime($report->created_at))}}</td>
												<?php } 
												if($trtype=='modification') { ?>
												<td>{{ $report->name }}</td>
												<td>{{date('d-m-Y h:i A', strtotime($report->modify_at))}}</td>
												<?php } 
												if($trtype=='deletion') { ?>
												<td>{{ $report->name }}</td>
												<td>{{date('d-m-Y h:i A', strtotime($report->deleted_at))}}</td>
												<?php } ?>
											</tr>
											@endforeach
										</tbody>
									</table>
								<?php } else if($logtype=='inventory_tr') { ?>
									<h3>Stock Inventory List</h3>
									<table class="table horizontal_table table-striped" id="tableAcmaster">
										<thead>
											<tr>
												<th>Tr.Date</th>
												<th>JV Type</th>
												<th>JV No.</th>
												<th>Reference</th>
												<th>Account Name</th>
												<?php if($trtype=='creation' || $trtype=='modification' || $trtype=='deletion') { ?>
												<th>User</th>
												<th>Created Dt.</th>
												<?php } 
												if($trtype=='modification') { ?>
												<th>User</th>
												<th>Modify Dt.</th>
												<?php } 
												if($trtype=='deletion') { ?>
												<th>User</th>
												<th>Delete Dt.</th>
												<?php } ?>
											</tr>
										</thead>
										<tbody>
											@foreach($reports as $report)
											<tr>
												<td>{{date('d-m-Y', strtotime($report->invoice_date))}}</td>
												<td>{{ $report->voucher_type }}</td>
												<td>{{ $report->voucher_type_id }}</td>
												<td>{{ $report->reference }}</td>
												<td>{{ $report->master_name }}</td>
												<?php if($trtype=='creation' || $trtype=='modification' || $trtype=='deletion') { ?>
												<td>{{ $report->name }}</td>
												<td>{{date('d-m-Y h:i A', strtotime($report->created_at))}}</td>
												<?php } 
												if($trtype=='modification') { ?>
												<td>{{ $report->uname }}</td>
												<td>{{date('d-m-Y h:i A', strtotime($report->modify_at))}}</td>
												<?php } 
												if($trtype=='deletion') { ?>
												<td>{{ $report->uname }}</td>
												<td>{{date('d-m-Y h:i A', strtotime($report->deleted_at))}}</td>
												<?php } ?>
											</tr>
											@endforeach
										</tbody>
									</table>
								<?php } else if($logtype=='account_master') { ?>
									<h3>Account Master List</h3>
									<table class="table horizontal_table table-striped" id="tableAcmaster">
										<thead>
											<tr>
												<th>Account ID</th>
												<th>Account Name</th>
												<?php if($trtype=='creation' || $trtype=='modification' || $trtype=='deletion') { ?>
												<th>User</th>
												<th>Created Dt.</th>
												<?php } 
												if($trtype=='modification') { ?>
												<th>User</th>
												<th>Modify Dt.</th>
												<?php } 
												if($trtype=='deletion') { ?>
												<th>User</th>
												<th>Delete Dt.</th>
												<?php } ?>
											</tr>
										</thead>
										<tbody>
											@foreach($reports as $report)
											<tr>
												<td>{{$report->account_id}}</td>
												<td>{{ $report->master_name }}</td>
												<?php if($trtype=='creation' || $trtype=='modification' || $trtype=='deletion') { ?>
												<td>{{ $report->name }}</td>
												<td>{{date('d-m-Y h:i A', strtotime($report->created_at))}}</td>
												<?php } 
												if($trtype=='modification') { ?>
												<td>{{ $report->uname }}</td>
												<td>{{date('d-m-Y h:i A', strtotime($report->modified_at))}}</td>
												<?php } 
												if($trtype=='deletion') { ?>
												<td>{{ $report->uname }}</td>
												<td>{{date('d-m-Y h:i A', strtotime($report->deleted_at))}}</td>
												<?php } ?>
											</tr>
											@endforeach
										</tbody>
									</table>
								<?php } else if($logtype=='item_master') { ?>
									<h3>Item Master List</h3>
									<table class="table horizontal_table table-striped" id="tableAcmaster">
										<thead>
											<tr>
												<th>Item Code</th>
												<th>Item Name</th>
												<?php if($trtype=='creation' || $trtype=='modification' || $trtype=='deletion') { ?>
												<th>User</th>
												<th>Created Dt.</th>
												<?php } 
												if($trtype=='modification') { ?>
												<th>User</th>
												<th>Modify Dt.</th>
												<?php } 
												if($trtype=='deletion') { ?>
												<th>User</th>
												<th>Delete Dt.</th>
												<?php } ?>
											</tr>
										</thead>
										<tbody>
											@foreach($reports as $report)
											<tr>
												<td>{{$report->item_code}}</td>
												<td>{{ $report->description }}</td>
												<?php if($trtype=='creation' || $trtype=='modification' || $trtype=='deletion') { ?>
												<td>{{ $report->name }}</td>
												<td>{{date('d-m-Y h:i A', strtotime($report->created_at))}}</td>
												<?php } 
												if($trtype=='modification') { ?>
												<td>{{ $report->uname }}</td>
												<td>{{date('d-m-Y h:i A', strtotime($report->modified_at))}}</td>
												<?php } 
												if($trtype=='deletion') { ?>
												<td>{{ $report->uname }}</td>
												<td>{{date('d-m-Y h:i A', strtotime($report->deleted_at))}}</td>
												<?php } ?>
											</tr>
											@endforeach
										</tbody>
									</table>
								<?php } ?>
									<!--<button type="button" class="btn btn-primary outstanding" onclick="getDetail()">Print</button>-->
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
$('#date_to').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy' } );

function getDetail() {	
	document.frmVatReport.action = "{{ url('vat_report/print')}}";
	document.frmVatReport.submit();
}
</script>
@stop
