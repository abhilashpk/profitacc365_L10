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
								<tr>								
									<td align="center" colspan="3">
										<b style="font-size:15px;">@include('main.print_head')</b><br/>
										<!--<b style="font-size:30px;">{{Session::get('company')}}</b><br/>
										<b style="font-size:15px;">Ph: {{Session::get('phone')}}, {{Session::get('address')}}</b><br/> -->
										<b style="font-size:15px;">Email:{{Session::get('email')}}</b><br/>
										<b style="font-size:15px;">TRN No: {{Session::get('vatno')}}</b>
										
									</td>
								</tr>
								<tr>
									<td width="40%">
									</td><td align="center"><h5><u><b>{{$voucherhead}}</b></u></h5></td>
									<td width="40%" align="left"></td>
								</tr>
								<tr>
									<td width="40%" align="center">
									</td><td align="center"></td>
									<td width="40%" align="right"><p>JV. No: {{$details->voucher_no}}</p><p>Date: {{date('d-m-Y',strtotime($details->voucher_date))}}</p></td>
								</tr>
							</table><br/>
                        </div>
						
                        <div class="col-md-12">
							
							<br/>
							<table class="table" border="1">
								<thead>
									<th>Account Name</th>
									<th>Description</th>
									<th>Reference</th>
									<th class="text-right">Debit</th>
									<th class="text-right">Credit</th>
								</thead>
								<body>
								<?php $dr_total = $cr_total = 0;?>
								@foreach($jerow as $row)
									<tr>
										<td>{{$row->master_name}}</td>
										<td>{{$row->description}}</td>
										<td>{{$row->reference}}</td>
										<td class="text-right"><?php if($row->entry_type=="Dr") { $dr_total += $row->amount; echo number_format($row->amount,2); }?></td>
										<td class="text-right"><?php if($row->entry_type=="Cr") { $cr_total += $row->amount; echo number_format($row->amount,2); }?></td>
									</tr>
								@endforeach
								
									<tr>
										<td></td>
										<td></td>
										<td></td>
										<td class="text-right"><b><?php echo number_format($dr_total,2);?></b></td>
										<td class="text-right"><b><?php echo number_format($cr_total,2);?></b></td>
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
                                                <span style="color:#fff;">
                                                    <i class="fa fa-fw fa-print"></i>
                                                Print
                                            </span>
                                </button>
								
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
                    </div>
                </div>
            </div>
        
        </section>

{{-- page level scripts --}}
@section('footer_scripts')
    <!-- begining of page level js -->
<script type="text/javascript" src="{{asset('assets/js/custom_js/invoice.js')}}"></script>
    <!-- end of page level js -->
@stop
