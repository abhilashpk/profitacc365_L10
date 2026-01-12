<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>
        @section('title')
            Profit ACC 365 | ERP Software
        @show
    </title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link rel="shortcut icon" href="{{asset('assets/img/favicon.ico')}}"/>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    <!-- global css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/app.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
   <!-- <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/invoice.css')}}">-->
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/bootstrap.css')}}">
@yield('header_styles')

<style>
#invoicing {
	font-size:8pt;
}

.tblstyle td,
  .tblstyle th {
    height:15px;
	padding:2px;
	border:1px solid #000 !important;
  }

/* @media print {
	html, body {
		
		height: 530px !important;        
	}
	.page {
		margin: 0;
		border: initial;
		border-radius: initial;
		width: initial;
		min-height: initial;
		box-shadow: initial;
		background: initial;
		page-break-after: always;
	}
} */
</style>
<style type="text/css" media="print">

/*body{ page-break-after: always !important; overflow: hidden !important; }*/

thead
{
	display: table-header-group;
}

#inv
{
	 display: table-footer-group;
	 /*position: fixed;*/
     bottom: 0;
	 margin: 0 auto 0 auto;
	 width:100%;
}

.t {
	 height:250px;
}

</style>
<!-- end of global css -->
</head>
<body >


<!-- For horizontal menu -->
@yield('horizontal_header')
<!-- horizontal menu ends -->
        <!-- Main content -->
        <section class="content p-l-r-15" id="cost-job">
            <div class="panel">
                
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
							<table border="0" style="width:100%;">
								<tr><td width="100%" align="center"><h3>{{Session::get('company')}}</h3></td>
									<td align="left"></td>
								</tr>
								<tr>
									<td width="100%" align="center"><h4><u>{{$voucherhead}}</u></h4>
									</td><td align="left"></td>
								</tr>
							</table><br/>
                        </div>
						
                        <div class="col-md-12">
							
									<table class="table horizontal_table table-striped" id="tableAcmaster">
										<thead>
											<tr>
												<th>SI.#</th>
												<th>Cheque Date</th>
												<th>Cheque No.</th>
												<th>Bank</th>
												<th>Voucher No.</th>
												<th>Voucher Date</th>
												<th>Party Name</th>
												<th class="text-right">Amount</th>
											</tr>
										</thead>
										<tbody>
										<?php $total = $i = 0; $date = ''; ?>
											@foreach($reports as $key => $report)
											<?php 
												$mtotal = 0;
												foreach($report as $row) {
													if($row->cheque_no!='' && $row->code!='') { 
													$i++; 
													$total += $row->amount;
													$date = $row->voucher_date;
													$mtotal += $row->amount;
											?>
											<tr>
												<td>{{$i}}</td>
												<td>{{date('d-m-Y', strtotime($row->cheque_date))}}</td>
												<td>{{ $row->cheque_no }}</td>
												
												<td>{{$row->code}}</td>
												<td>{{ $row->voucher_no }}</td>
												<td>{{ date('d-m-Y', strtotime($row->voucher_date)) }}</td>
												<td>{{$row->creditor}}</td>
												<td class="text-right">{{number_format($row->amount,2)}}</td>
											</tr>
											<?php } }  $date[$key] =  $mtotal; $yr = date('Y', strtotime($row->cheque_date)); ?>
											
											<tr>
											<td colspan="7" class="text-right" style="background-color:#d4eaf7 !important;"><b>Total {{date("F", mktime(0, 0, 0, $key, 10)).' '.$yr}}: </b></td>
												<td class="text-right" style="background-color:#d4eaf7 !important;"><b>{{number_format($mtotal,2)}}</b></td>
											</tr>
											@endforeach

											<tr>
												<td colspan="7" class="text-right" style="background-color:#d4eaf7 !important;"><b>Net Total: </b></td>
												<td class="text-right" style="background-color:#d4eaf7 !important;"><b>{{number_format($total,2)}}</b></td>
											</tr>
										</tbody>
									</table>
							
                        </div>
						
						
                    </div>
                    <div class="btn-section">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                                <span class="pull-right">
                                           
                                             <button type="button" onclick="javascript:window.print();"
                                                     class="btn btn-responsive button-alignment btn-primary"
                                                     data-toggle="button">
                                                <span style="color:#fff;">
                                                    <i class="fa fa-fw fa-print"></i>
                                                Print
                                            </span>
                                </button>
								
								<button type="button" onclick="javascript:window.history.back();"
                                                     class="btn btn-responsive button-alignment btn-primary"
                                                     data-toggle="button">
                                                <span style="color:#fff;">
                                                    <i class="fa fa-fw fa-times"></i>
                                                Back 
                                            </span>
                                </button>
                                </span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- row -->
    
        </section>


{{-- page level scripts --}}
@section('footer_scripts')
    <!-- begining of page level js -->
<script type="text/javascript" src="{{asset('assets/js/custom_js/invoice.js')}}"></script>
    <!-- end of page level js -->
@stop
