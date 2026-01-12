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
									<td align="center" >
										 <b style="font-size:25px;"><img src="{{asset('assets/img/agmtheader.jpg')}}" alt="Image"/></b>
									
										
									</td>
								</tr>
								<tr>
									
									<td align="center"><h5><u><b>Receipt Voucher</b></u></h5></td>
									
								</tr>
								<tr>
									
									<td align="right"><p>RV. No: {{$rvs[0]->voucher_no}}</p><p>Date:{{date('d-m-Y',strtotime($rvs[0]->voucher_date))}} </p></td>
								</tr>
							</table><br/>
                        </div>
						@foreach($rvs as $row)
							@if($row->entry_type=='Cr')
						        @php $name = $row->master_name; @endphp
						    @endif
						@endforeach
						
						
                        <div class="col-md-12">
							<div style="text-align:center;"><h5><p>Received with thanks from M/s. <b>{{$name}}</b> the sum of <b>Dhs.{{$words}} </b></p></h5></div>
							<br/><b>Mode of Receipt:</b>
							<table class="table" border="1">
								<tr>
									<td><b>Debit Account</b></td>
									<td><b>Amount</b></td>
									<td><b>Cheque No.</b></td>
									<td><b>Cheque Date</b></td>
									<td><b>Drawn on<b></td>
								</tr>
									<tr>
										<td>{{$rvs[0]->master_name}}</td>
										<td >{{number_format($rvs[0]->amount,2)}}</td>
										<td >{{$rvs[0]->cheque_no}}</td>
										<td ><?php if($rvs[0]->cheque_no!='')
												echo date('d-m-Y',strtotime($rvs[0]->cheque_date)); ?>
										</td>
										<td>{{$rvs[0]->name}}</td>
									</tr>
									
								
							</table>
							
							<br/><b>Invoice Details:</b>
							<table class="table" border="1">
								<tr>
									<td><b> Name</b></td>
									<td><b>Invoice No</b></td>
									<td><b>Invoice Date</b></td>
									<td><b>Invoice Amount</b></td>
								</tr>
								@php $amount = 0; @endphp
								@foreach($rvs as $row)
								@if($row->entry_type=='Cr')
								
									<tr>
										<td>{{$row->master_name}}</td>
										<td >{{$row->reference}}</td>
										<td>{{($row->reference!='')?date('d-m-Y',strtotime($row->voucher_date)):''}}</td>
										<td >{{number_format($row->amount,2)}}</td>
									</tr>
									@php $amount += $row->amount; @endphp
								@endif
								@endforeach
								
									<tr>
										<td colspan="3" align="right"><b>Total:</b></td>
										<td class="text-right"><b>{{number_format($amount,2)}}</b></td>
									</tr>
								
							</table>
							
							<br/><br/><br/>
							
							<table border="0" style="width:100%;">
								<tr><td width="40%" align="left"><b>Received by:</b></td>
									<td align="left"><b>Accountant:</b></td>
									<td width="20%" align="left"><b>Approved by:</b></td>
								</tr>
							</table><br/>
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
