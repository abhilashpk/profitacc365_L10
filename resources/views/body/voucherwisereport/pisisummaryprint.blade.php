@extends('layouts/default')

    {{-- Page title --}}
    @section('title')
        Invoice
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/custom_css/skins/skin-coreplus.css')}}" type="text/css" id="skin"/>
   <!-- <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/invoice.css')}}">-->
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/bootstrap.css')}}">
    <!--end of page level css-->
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1> PC & SI Peding Summary Report</h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="glyphicon glyphicon-folder-close"></i> Reports
                    </a>
                </li>
				<li> PC & SI Peding Summary Report</li>
                <li class="active">
                   <a href="#">Print</a>
                </li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content p-l-r-15" id="cost-job">
            <div class="panel">
                
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
							<table border="0" style="width:100%;">
								<tr><td align="center"><h3>{{Session::get('company')}}</h3></td>
								</tr>
								<tr>
									<td align="center"><h4><b><u>Submission of Payment Report & Sales Invoice Peding Summary Report</u></b></h4>
									</td>
								</tr>
							</table><br/>
                        </div>
                        <div class="col-md-12">
						
							<table class="table" border="0">
								<thead>
									<th>Customer</th>
									<th>Job.No</th>
									<th>Jobname</th>
									<th class="text-right">PC Amount</th>
									<th class="text-right">Inv. Amount</th>
									<th class="text-right">Balance Amount</th>
								</thead>
								<body>
									<?php $totalbalance = $totalpi = $totalsi = 0; ?>
									@foreach($results as $row)
									<tr>
										<td>{{$row->master_name}}</td>
										<td>{{$row->code}}</td>
										<td>{{$row->jobname}}</td>
										<td class="text-right">{{number_format($row->piamount,2)}}</td>
										<td class="text-right">{{number_format($row->siamount,2)}}</td>
										<td class="text-right">{{number_format($row->balance,2)}}</td>
										<?php $totalpi += $row->piamount; 
											$totalsi += $row->siamount; 
											$totalbalance += $row->balance; 
										?>
									</tr>
									@endforeach
									<tr>
										<td></td>
										<td></td>
										<td class="text-right"><b>Total:</b></td>
										<td class="text-right"><b>{{number_format($totalpi,2)}}</b></td>
										<td class="text-right"><b>{{number_format($totalsi,2)}}</b></td>
										<td class="text-right"><b>{{number_format($totalbalance,2)}}</b></td>
									</tr>
								</body>
							</table>
							
                        </div>
						
                    </div>
                    <div class="btn-section">
						
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <span class="pull-right">
                                           
							   <button type="button" onclick="javascript:window.print();"
											 class="btn btn-responsive button-alignment btn-primary"
											 data-toggle="button">
										<span style="color:#fff;" >
											<i class="fa fa-fw fa-print"></i>
										Print
									</span>
								   </button>
								   
								<button type="button" onclick="getExport()"
										 class="btn btn-responsive button-alignment btn-primary"
										 data-toggle="button">
									<span style="color:#fff;">
										<i class="fa fa-fw fa-upload"></i>
									Export Excel
								</span>
								</button>
										  
						
								<button type="button" onclick="javascript:window.history.back();"
										 class="btn btn-responsive button-alignment btn-primary"
										 data-toggle="button">
									<span style="color:#fff;" >
										<i class="fa fa-fw fa-times"></i>
									Back 
								</span>
                                </button>
                                </span>
                        </div>
						
						<form class="form-horizontal" role="form" method="POST" name="frmExport" id="frmExport" action="{{ url('voucherwise_report/pisisummary_export') }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="date_from" value="{{$datefrom}}" >
						<input type="hidden" name="date_to" value="{{$dateto}}" >
						<input type="hidden" name="customer_id" value="{{$customer}}" >
						<input type="hidden" name="job_id" value="{{$job}}" >
						</form>
						
                    </div>
					
                </div>
            </div>
            <!-- row -->
        @include('layouts.right_sidebar')
        <!-- right side bar end -->
        </section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <!-- begining of page level js -->
<script type="text/javascript" src="{{asset('assets/js/custom_js/invoice.js')}}"></script>
    <!-- end of page level js -->
<script>
function getExport() { document.frmExport.submit(); }
</script>
@stop
