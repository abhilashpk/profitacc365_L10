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
            <h1> PC & SI Report</h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="glyphicon glyphicon-folder-close"></i> Reports
                    </a>
                </li>
				<li> PC & SI Report</li>
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
									<td align="center"><h4><b><u>Submission of Payment Report & Sales Invoice Report</u></b></h4>
									</td>
								</tr>
							</table><br/>
                        </div>
						
                        <div class="col-md-12">
						<?php $pitotal_net = $total_net = 0;
						if(count($results['pi_res']) > 0)	{ ?>
						<p><b>SUBMISSION OF PAYMENT CERTIFICATES</b></p>
							<table class="table" border="0">
								<thead>
									<th>SI.No</th>
									<th>SO.#</th>
									<th>SO.Ref.#</th>
									<th>Customer</th>
									<th>Job.No</th>
									<th>Jobname</th>
									<th class="text-right">Gross Amt.</th>
									<th class="text-right">Retn. Amt.</th>
									<th class="text-right">Discount</th>
									<th class="text-right">VAT Amt.</th>
									<th class="text-right">Net Total</th>
								</thead>
								<body>
									<?php $total_net = $discount = $total_vat = $total_gross = $pitotal_net = $rtamount = 0;?>
									@foreach($results['pi_res'] as $row)
									<?php 
										  $total_gross += $row->total;
										  $total_vat += $row->vat_amount;
										  $total_net += $row->net_total;
										  $discount += $row->discount;
										  $pitotal_net = $total_net;
										  $rtamount += $row->less_amount;
									?>
									<tr>
										<td>{{$row->voucher_no}}</td>
										<td>{{ $row->voucher_no }}</td>
										<td>{{$row->reference_no}}</td>
										<td>{{$row->master_name}}</td>
										<td>{{$row->code}}</td>
										<td>{{$row->jobname}}</td>
										<td class="text-right">{{number_format($row->total,2)}}</td>
										<td class="text-right">{{number_format($row->less_amount,2)}}</td>
										<td class="text-right">{{number_format($row->discount,2)}}</td>
										<td class="text-right">{{number_format($row->vat_amount,2)}}</td>
										<td class="text-right">{{number_format($row->net_total,2)}}</td>
									</tr>
									@endforeach
									<tr>
										<td></td>
										<td></td>
										<td></td><td></td><td></td>
										<td class="text-right"><b>Total:</b></td>
										<td class="text-right"><b>{{number_format($total_gross,2)}}</b></td>
										<td class="text-right"><b>{{number_format($rtamount,2)}}</b></td>
										<td class="text-right"><b>{{number_format($row->discount,2)}}</b></td>
										<td class="text-right"><b>{{number_format($total_vat,2)}}</b></td>
										<td class="text-right"><b>{{number_format($total_net,2)}}</b></td>
									</tr>
								</body>
							</table>
						<?php } ?>		
						
						<?php if(count($results['si_res']) > 0)	{ ?>
						<p><b>SALES INVOICE</b></p>
							<table class="table" border="0">
								<thead>
									<th>SI.No</th>
									<th>SO.#</th>
									<th>SO.Ref.#</th>
									<th>Customer</th>
									<th>Job.No</th>
									<th>Jobname</th>
									<th class="text-right">Gross Amt.</th>
									<th class="text-right">Retn. Amt.</th>
									<th class="text-right">Discount</th>
									<th class="text-right">VAT Amt.</th>
									<th class="text-right">Net Total</th>
								</thead>
								<body>
									<?php $total_net = $discount = $total_vat = $total_gross = $rtamount = 0;?>
									@foreach($results['si_res'] as $row)
									<?php 
										  $total_gross += $row->total;
										  $total_vat += $row->vat_amount;
										  $total_net += $row->net_total;
										  $discount += $row->discount;
										  $rtamount += $row->less_amount;
									?>
									<tr>
										<td>{{$row->voucher_no}}</td>
										<td>{{ $row->voucher_no }}</td>
										<td>{{$row->reference_no}}</td>
										<td>{{$row->master_name}}</td>
										<td>{{$row->code}}</td>
										<td>{{$row->jobname}}</td>
										<td class="text-right">{{number_format($row->total,2)}}</td>
										<td class="text-right">{{number_format($row->less_amount,2)}}</td>
										<td class="text-right">{{number_format($row->discount,2)}}</td>
										<td class="text-right">{{number_format($row->vat_amount,2)}}</td>
										<td class="text-right">{{number_format($row->net_total,2)}}</td>
									</tr>
									@endforeach
									<tr>
										<td></td>
										<td></td>
										<td></td><td></td><td></td>
										<td class="text-right"><b>Total:</b></td>
										<td class="text-right"><b>{{number_format($total_gross,2)}}</b></td>
										<td class="text-right"><b>{{number_format($rtamount,2)}}</b></td>
										<td class="text-right"><b>{{number_format($row->discount,2)}}</b></td>
										<td class="text-right"><b>{{number_format($total_vat,2)}}</b></td>
										<td class="text-right"><b>{{number_format($total_net,2)}}</b></td>
									</tr>
									
								</body>
							</table>
							<?php if($pitotal_net <= $total_net) { ?>
							<p><b>Balance Amount: 0.00</b></p>
							<?php } else { $bal = $pitotal_net - $total_net; ?>
							<p><b>Balance Amount: {{number_format($bal,2)}}</b></p>
							<?php } ?>
						<?php } ?>	
                        </div>
						
						
                    </div>
                    <div class="btn-section">
						<?php if(count($results['pi_res']) == 0 && count($results['si_res']) == 0)	{ ?>
						<div class="alert alert-warning">
							<p>No records were found!</p>
						</div>
						<?php } ?>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                                <span class="pull-right">
                                           <?php if(count($results['pi_res']) > 0 || count($results['si_res']) > 0)	{ ?>
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
										   <?php } ?>
						
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
						
						<form class="form-horizontal" role="form" method="POST" name="frmExport" id="frmExport" action="{{ url('voucherwise_report/pisiexport') }}">
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
