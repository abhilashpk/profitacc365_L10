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
									<td width="50%">
									</td><td align="center"><h5><u><b>Receipt Voucher New</b></u></h5></td>
									<td width="40%" align="left"></td>
								</tr>
								<tr>
									<td align="left" valign="top" width="35%" style="padding-left:0px; padding-bottom:5px;">
										<div style="border:1px solid #000; padding: 10px;"><br/>
											RV No: {{$crrow->voucher_no}}<br/>
											Created by: <?php echo $crrow->name;?><br/>
											Created at: {{date('d-m-Y',strtotime($crrow->created_at))}}<br/>

										</div>
									</td>
						
								</tr>
							</table><br/>
                        </div>
						<?php
							$name = '';
							if(sizeof($invoicerow) > 1) {
								//$name = $invoicerow[1]->master_name;
								
								foreach($invoicerow as $invrow) {
									$refnos = '';
									if($invrow->entry_type=='Dr') {
										$refnos = $invrow->reference;
										break;
									}
								}
							}
						?>
						
						
                        <div class="col-md-12">
							
							<br/><b>Mode of Receipt:</b>
							<table class="table" border="1">
								<thead>
									<th>Account Name</th>
									<th >Description</th>
									<th >Reference</th>
									<th class="text-right">Debit</th>
									<th class="text-right">Credit</th>
								</thead>
								<body>
								@php $dramount = $cramount = 0; @endphp
								@foreach($invoicerow as $row)
									<tr>
										<td>{{$row->master_name}}</td>
										<td >{{$row->description}}</td>
										<td>{{$row->reference}}</td>
										<td class="text-right">@if($row->entry_type=='Dr'){{number_format($row->amount,2)}} @php $dramount += $row->amount; @endphp @endif</td>
										<td class="text-right">@if($row->entry_type=='Cr'){{number_format($row->amount,2)}} @php $cramount += $row->amount; @endphp @endif</td>
									</tr>
								@endforeach
									<tr>
										<td colspan="3"><b>Total:</b></td>
										<td class="text-right"><b>{{number_format($dramount,2)}}</b></td>
										<td class="text-right"><b>{{number_format($cramount,2)}}</b></td>
									</tr>
								</body>
							</table>
								
							
							
                        </div>
						
						
							
                    </div>
                    
                </div>
            </div>
   
{{-- page level scripts --}}
@section('footer_scripts')
    <!-- begining of page level js -->
<script type="text/javascript" src="{{asset('assets/js/custom_js/invoice.js')}}"></script>
    <!-- end of page level js -->
@stop
