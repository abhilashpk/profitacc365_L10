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
									
									<td align="center"><h5><u><b>Tax Invoice</b></u></h5></td>
									
								</tr>
								
							</table><br/>
                        </div>
						
						<div class="col-md-12">
						<p>
						<table class="table" border="0">
						<tr>
						<td align="left">M/S {{$details->master_name}}</td>
						<td align="right">Invoice No: {{$details->connection_no}} </td>
						</tr>
						<tr>
						<td align="left"></td>
						<td align="right">Invoice Date: {{date('d-m-Y',strtotime($details->date))}} </td>
						</tr>
                          </table>
							</p>
							<table class="table" border="1">
								<tr>
									<td colspan="2"><b>Description</b></td>
									
									<td ><b>Account</b></td>
									</tr>
								
								@foreach($accounts as $k=> $row)
									<tr>
										<td colspan="2" ><?php echo $row->title; ?></td>
										<td ><?php echo $transactions[$k]->amount; ?></td>
										
									</tr>
									@endforeach
									<tr>
									<td ><b>Amount In Words:{{$words}}</b></td>
										<td align="right"><b>Grand Total:</b></td>
										<td ><b>{{number_format($details->grand_total,2)}}</b></td>
									</tr>
								
							</table>
							
							
							
							<br/><br/><br/>
							
							<table border="0" style="width:100%;">
								<tr><td width="40%" align="left"><b>Received by:</b></td>
									
									<td width="20%" align="left"><b>Approved by:</b></td>
								</tr>
							</table><br/>
							<table class="table " border="0"  >
								<tr>								
									<td  align="center">
										 <b style="font-size:20px;"><img src="{{asset('assets/img/agrmtfooter.jpg')}}" alt="Image"/></b><br/>
									
										
									</td>
								</tr>
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
