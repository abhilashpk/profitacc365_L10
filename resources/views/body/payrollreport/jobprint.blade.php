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
    <link rel="stylesheet" href="{{asset('assets/css/custom_css/skins/skin-coreplus.css')}}" type="text/css" id="skin"/>
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
<div>
    <!-- Left side column. contains the logo and sidebar -->
    
    <aside class="right">
        <section class="content p-l-r-15" id="invoice-stmt">
            <div class="panel">
                
                <div class="panel-body" style="width:100%; !important; border:0px solid red;">
                    <div class="print" id="invoicing">
						<div class="col-md-12">
						<?php if(count($reports) > 0) { ?>
						@if($type=='summary')
							<table border="0" style="width:100%;height:100%;">
								<thead>
									<tr>
										<td colspan="2" align="center">@include('main.print_head')</td>
									</tr>
									<tr>
										<td colspan="2" align="center"><b style="font-size:16px;"><b><u>{{$titles['subhead']}}</u></b></b></td>
									</tr>
									
									<tr>
										<td align="left" valign="top" style="padding-left:0px;"><br/>
										</td>
										<td align="right" style="padding-left:0px;">
											<?php if($fromdate!='' && $todate!='') { ?><p>From: {{$fromdate}} - To: {{$todate}}</p><?php } ?>
										</td>
									</tr>
								</thead>
								
								<tbody id="bod">
									<tr style="border:0px solid black;">
										<td colspan="2" align="center">
										<table border="0" style="width:100%;" class="tblstyle table-bordered">
											<tr>
												<td width="15%"><b>Job Code</b></td>
												<td width="10%"><b>Job Name</b></td>
												<td width="10%" class="text-right"><b>N/Hrs</b></td>
												<td width="10%" class="text-right"><b>OT(G)/Hrs</b></td>
												<td width="10%" class="text-right"><b>OT(H)/Hrs</b></td>
												<td width="10%" class="text-right"><b>Job Wage</b></td>
												<td width="10%" class="text-right"><b>Ot.Wage(G)</b></td>
												<td width="10%" class="text-right"><b>Ot.Wage(H)</b></td>
												<td width="15%" class="text-right"><b>Total</b></td>
											</tr>
											@foreach($reports as $report)
											<tr>
												<td>{{$report['job_code']}}</td>
												<td>{{$report['job_name']}}</td>
												<td class="emptyrow text-right">{{$report['job_hr']}}</td>
												<td class="emptyrow text-right">{{$report['job_ot_hr']}}</td>
												<td class="emptyrow text-right">{{$report['job_oth_hr']}}</td>
												<td class="emptyrow text-right">{{$report['job_wg']}}</td>
												<td class="emptyrow text-right">{{$report['job_ot_wg']}}</td>
												<td class="emptyrow text-right">{{$report['job_oth_wg']}}</td>
												<td class="emptyrow text-right">{{$report['total']}}</td>
											</tr>
											@endforeach	
										</table>
										</td>
									</tr>
								</tbody>
								<tfoot id="inv">
									<tr>
										<td colspan="2" class="footer"><br/>@include('main.print_foot')</td>
									</tr>
								</tfoot>
							</table>
						@elseif($type=='detail')
						<table border="0" style="width:100%;height:100%;">
							<thead>
								<tr>
									<td colspan="2" align="center">@include('main.print_head')</td>
								</tr>
								<tr>
									<td colspan="2" align="center"><b style="font-size:16px;"><b><u>{{$titles['subhead']}}</u></b></b></td>
								</tr>
								
								<tr>
									<td align="left" valign="top" style="padding-left:0px;">
										<?php //if($jobid!='') { ?><h6><b>Job No: {{$reports[0]->job_code}} Job Name: {{$reports[0]->job_name}}</b></h6><?php //} ?>
									</td>
									<td align="right" style="padding-left:0px;">
										<?php if($fromdate!='' && $todate!='') { ?><p>From: {{$fromdate}} - To: {{$todate}}</p><?php } ?>
									</td>
								</tr>
							</thead>
							
							<tbody id="bod">
								<tr style="border:0px solid black;">
									<td colspan="2" align="center">
									<table border="0" style="width:100%;" class="tblstyle table-bordered">
										<tr>
											<td width="15%"><b>Date</b></td>
											<td width="10%"><b>Emp.No</b></td>
											<td width="10%" class="text-right"><b>N/Hrs</b></td>
											<td width="10%" class="text-right"><b>OT(G)/Hrs</b></td>
											<td width="10%" class="text-right"><b>OT(H)/Hrs</b></td>
											<td width="10%" class="text-right"><b>Nr.Wage</b></td>
											<td width="10%" class="text-right"><b>Ot.Wage(G)</b></td>
											<td width="10%" class="text-right"><b>Ot.Wage(H)</b></td>
											<td width="15%" class="text-right"><b>Total</b></td>
										</tr>
										
										<?php $totnwh = $tototg = $tototh = $totline = 0;?>
										@foreach($reports as $report)
										<?php
											$linetotal = ($report->nwh * $report->wage) + ($report->otg * $report->otg_wage) + ($report->oth * $report->oth_wage);
											$totline += $linetotal;
											$totnwh += $report->nwh;
											$tototg += $report->otg;
											$tototh += $report->oth;
										?>
										<tr>
											<td>{{date('d-m-Y', strtotime($report->job_date))}}</td>
											<td>{{$report->code}}</td>
											<td class="emptyrow text-right">{{$report->nwh}}</td>
											<td class="emptyrow text-right">{{$report->otg}}</td>
											<td class="emptyrow text-right">{{$report->oth}}</td>
											<td class="emptyrow text-right">{{$report->wage}}</td>
											<td class="emptyrow text-right">{{$report->otg_wage}}</td>
											<td class="emptyrow text-right">{{$report->oth_wage}}</td>
											<td class="emptyrow text-right">{{$linetotal}}</td>
										</tr>
										@endforeach	
										
										<tr>
											<td></td>
											<td><b>Total</b></td>
											<td class="highrow text-right"><strong>{{$totnwh}}</strong></td>
											<td class="highrow text-right"><strong>{{$tototg}}</strong></td>
											<td class="highrow text-right"><strong></strong></td>
											<td class="emptyrow text-right"><strong></strong></td>
											<td class="emptyrow text-right"><strong></strong></td>
											<td class="emptyrow text-right"><strong></strong></td>
											<td class="emptyrow text-right"><strong>{{$totline}}</strong></td>
										</tr>
									</table>
									</td>
								</tr>
							</tbody>
							<tfoot id="inv">
								<tr>
									<td colspan="2" class="footer"><br/>@include('main.print_foot')</td>
								</tr>
							</tfoot>
						</table>
						@endif
						<?php } else { ?>
						<table border="0" style="width:100%;height:100%;">
							<thead>
								<tr>
									<td colspan="2" align="center"><b style="font-size:20px;">{{Session::get('company')}}</b>
									<!--<img src="{{asset('assets/'.Session::get('logo').'')}}" width="900px;" />-->
									</td>
								</tr>
								<tr>
									<td colspan="2" align="center"><b style="font-size:16px;"><b><u>{{$titles['subhead']}}</u></b></b></td>
								</tr>
							</thead>
						</table>
						<br/>
						<div class="alert alert-danger">
							<ul>No records were found!</ul>
						</div>
						<?php } ?>
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
								
								<button type="button" onclick="javascript:window.close();"
                                                     class="btn btn-responsive button-alignment btn-primary"
                                                     data-toggle="button">
													 <i class="fa fa-fw fa-times"></i>
                                                Close 
                                            </span>
                                </button>
                                </span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- row -->
        
        <!-- right side bar end -->
        </section>

    </aside>
    <!-- page wrapper-->
</div>
<!-- wrapper-->
<!-- global js -->
<script src="{{asset('assets/js/app.js')}}" type="text/javascript"></script>
<!-- end of global js -->
@yield('footer_scripts')
<!-- end page level js -->
</body>
<script>
$(document).ready(function () {
	$('html').attr({style: 'min-height: inherit'});
	$('body').attr({style: 'min-height: inherit'});
});
</script>
</html>
