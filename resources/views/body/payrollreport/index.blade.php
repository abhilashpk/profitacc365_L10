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
                Payroll Report
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="fa fa-fw fa-money"></i> Payroll
                    </a>
                </li>
                <li>
                    <a href="#">Payroll Report</a>
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
                                <i class="fa fa-fw fa-columns"></i> Payroll Report
                            </h3>
                        </div>
                        <div class="panel-body">
							<form class="form-horizontal" role="form" method="POST" name="frmPayrollReport" id="frmPayrollReport" action="{{ url('payroll_report/search') }}" target="_blank">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								<div class="row">
									<div class="col-xs-6">
										<span>Month:</span>
										<select id="month" class="form-control select2" style="width:100%" name="month">
											<option value="1">January</option>
											<option value="2">February</option>
											<option value="3">March</option>
											<option value="4">April</option>
											<option value="5">May</option>
											<option value="6">June</option>
											<option value="7">July</option>
											<option value="8">Auguest</option>
											<option value="9">September</option>
											<option value="10">October</option>
											<option value="11">November</option>
											<option value="12">December</option>
										</select>
										<span>Year:</span>
										<select id="year" class="form-control select2" style="width:100%" name="year">
											<option value="{{date('Y',strtotime($settings->from_date))}}">{{date('Y',strtotime($settings->from_date))}}</option>
										</select>
										
										<span>Job:</span>
									<input type="text" name="jobname" id="jobname" class="form-control" autocomplete="off" data-toggle="modal" data-target="#job_modal" placeholder="Job Code">
									<input type="hidden" name="job_id" id="job_id">
									</div>
									<div class="col-xs-6">
										<span>Search By:</span>
										<select id="search_type" class="form-control select2" style="width:100%" name="search_type">
											<option value="pay_slip">Pay Slip</option>
											<option value="pay_slip_summery">Pay Slip(Summery)</option>
											<option value="attendance">Attendance</option>
											<option value="jobwise">Jobwise Report</option>
											<option value="payroll_summery">Payroll(Summery)</option>
											<!--<option value="jobwise_summery">Payroll Jobwise(Summery)</option>-->
										</select>
										
										<span>Employee:</span>
										<select id="employee_id" class="form-control select2" style="width:100%" name="employee_id">
											<option value="">Employee</option>
											<?php foreach($employees as $emp) { ?>
											<option value="{{$emp['id']}}">{{$emp['name']}}</option>
											<?php } ?>
											</select><br/>
										<button type="submit" class="btn btn-primary">Search</button>
									</div>
								</div>
								<?php if($reports!=null) { ?>
								<div class="table-responsive">
									<table class="table table-striped" id="tableItem">
										<thead>
										<tr>
											<th>Item Code</th>
											<th>Description</th>
											<th>Unit</th>
											<th>Cost Avg.</th>
											<!--<th>Unit Price</th>
											<th>Unit Price(WS)</th>-->
											<th>Opn.Cost</th>
											<th>Last P.Cost</th>
											<th>Opn.Qty.</th>
											<th>Qty.in Hand</th>
											<th>Rcvd. Qty.</th>
											<th>Issued Qty.</th>
										</tr>
										</thead>
										<tbody>
										@foreach($reports as $report)
										<tr>
											<td>{{ $report->item_code }}</td>
											<td>{{ $report->description }}</td>
											<td>{{ $report->packing }}</td>
											<td>{{ $report->cost_avg }}</td>
											<!--<td>{{ $report->sell_price }}</td>
											<td>{{ $report->wsale_price }}</td>-->
											<td>{{ $report->opn_cost }}</td>
											<td>{{ $report->last_purchase_cost }}</td>
											<td>{{ $report->opn_quantity }}</td>
											<td>{{ $report->cur_quantity }}</td>
											<td>{{ $report->received_qty }}</td>
											<td>{{ $report->issued_qty }}</td>
										</tr>
										@endforeach
										
										</tbody>
									</table>
									<button type="button" class="btn btn-primary statement" onclick="getSummary()">Print Report</button>
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
        
        
        <div id="job_modal" class="modal fade animated" role="dialog">
			                                 <div class="modal-dialog">
			                                    	<div class="modal-content">
					                                      <div class="modal-header">
						                                      <button type="button" class="close" data-dismiss="modal">&times;</button>
						                                     <h4 class="modal-title">Job Master</h4>
					                                        </div>
					                                  <div class="modal-body" id="jobData">
						
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
function getSummary() {	
	document.frmPayrollReport.action = "{{ url('quantity_report/print')}}";
	document.frmPayrollReport.submit();
}
$(function() {	
    var joburl = "{{ url('jobmaster/jobb_data/') }}";
	$('#jobname').click(function() {
		$('#jobData').load(joburl, function(result) {
			$('#myModal').modal({show:true});
		});
	});
	
	$(document).on('click', '.jobbRow', function(e) {
		$('#jobname').val($(this).attr("data-cod"));
		$('#job_id').val($(this).attr("data-id"));
		e.preventDefault();
	});
});
</script>
@stop
