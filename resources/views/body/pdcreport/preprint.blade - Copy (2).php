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
								</tr>
								<tr>
									<td  width="100%" align="center"><h4><u>{{$voucherhead}}</u></h4></td>
								</tr>
							</table><br/>
                        </div>
						<p>Date From: <b><?php echo ($fromdate=='')?date('d-m-Y',strtotime($settings->from_date)):date('d-m-Y',strtotime($fromdate));?></b> &nbsp; To: <b><?php echo ($todate=='')?date('d-m-Y'):$todate;?></b></p>
                        <div class="col-md-12">
							<?php if(count($reports) > 0) { ?>
							<table class="table">
										<thead>
											<tr>
												<th style="width:50px;"><!--Type--></th>
												<th><strong>Voucher No</strong></th>
												<th><strong>Party Name</strong></th>
												<th><strong>Bank</strong></th>
												<th><strong>Trn.Date</strong></th>
												<th><strong>Cheque Dt.</strong></th>
												<th class="text-right"><strong>Amount</strong></th>
											</tr>
										</thead>
										<tbody>
										@foreach($reports as $key => $report)
											<?php 
												$total = 0;
												foreach($report as $row) {
												$total += $row->amount;
												$date = $row->voucher_date;
												?>
											<tr>
												<td></td>
												<td>{{ $row->voucher_no }}</td>
												<td>{{$row->customer}}</td>
												<td>{{ $row->code }}</td>
												<td>{{ date('d-m-Y', strtotime($row->voucher_date)) }}</td>
												<td>{{ date('d-m-Y', strtotime($row->cheque_date))}}</td>
												<td class="text-right">{{number_format($row->amount,2)}}</td>
											</tr>
											<?php } ?>
											<tr>
												<td></td><td></td><td></td><td></td><td></td>
												<td><b>Total in <?php echo date('M', strtotime($date));?>: </b></td>
												<td class="text-right"><b>{{number_format($total,2)}}</b></td>
											</tr>
										@endforeach
										</tbody>
									</table>
							<?php } else  echo 'No records were found.'; ?>
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
								
								<!--<button type="button" onclick="getExport()"
											 class="btn btn-responsive button-alignment btn-primary"
											 data-toggle="button">
										<span style="color:#fff;">
											<i class="fa fa-fw fa-upload"></i>
										Export Excel
									</span>
									</button>-->
									
								<button type="button" onclick="javascript:window.close();"
                                                     class="btn btn-responsive button-alignment btn-primary"
                                                     data-toggle="button">
                                                <span style="color:#fff;">
                                                    <i class="fa fa-fw fa-times"></i>
                                                Close 
                                            </span>
                                </button>
                                </span>
                        </div>
					<?php if(count($reports) > 0) { ?>	
					<form class="form-horizontal" role="form" method="POST" name="frmExport" id="frmExport" action="{{ url('pdc_report/export') }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="status" value="{{$status}}" >
						<input type="hidden" name="date_to" value="{{$todate}}" >
						<input type="hidden" name="date_from" value="{{$fromdate}}" >
						<input type="hidden" name="search_type" value="{{$type}}" >
						<input type="hidden" name="account_id" value="{{$account}}" >
						<input type="hidden" name="ob_only" value="{{$obonly}}" >
					</form>
					<?php } ?>
                    </div>
                </div>
            </div>
            <!-- row -->
       
        <!-- right side bar end -->
        </section>


{{-- page level scripts --}}
@section('footer_scripts')
    <!-- begining of page level js -->
<script type="text/javascript" src="{{asset('assets/js/custom_js/invoice.js')}}"></script>
    <!-- end of page level js -->
<script>
function getExport() {
	document.frmExport.submit();
}
</script>
@stop
