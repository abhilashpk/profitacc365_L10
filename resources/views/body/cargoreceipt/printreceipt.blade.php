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
								<tr><td align="center" colspan="3">								
									 <b style="font-size:30px;"><img src="{{asset('assets/img/logo.jpg')}}" alt="Image"/></b><br/>
									<!--	<img src="{{asset('assets/'.Session::get('logo').'')}}" width="10%" /> 		
							<font color="00008B">	<b style="font-size:25px;">{{Session::get('company')}}</b> </font> -->
									</td>
								</tr>
								<tr>								
									<td align="center" colspan="5">
									
										<b style="font-size:15px;">Tel: {{Session::get('phone')}},  Fax:{{Session::get('vatno')}}, Email:{{Session::get('email')}}, P.O.BOX:{{Session::get('pin')}}, {{Session::get('address')}}</b><br/>
										
									</td>
								</tr>
								<tr>
									<td width="30%">
									</td><td align="center"  bgcolor="#D2FBA4"><h5><u><b>{{$voucherhead}}</b></u></h5></td>
									<td width="30%" align="left"></td>
								</tr>
								
							</table><br/>
                        </div>
						<div class="col-md-12">
							
						<table class="table" border="0">
						            <tr>
									<td colspan="2" align="center"><font size="2" color="#59981A">No: <br/>الرقم </font> </td>
									
								   </tr>
								   <tr>
								   <td ><font size="2" color="#59981A">Dhs:  <br/> درهم </font> </td>
									<td ><font size="2" color="#59981A">Date: {{date('d-m-Y', strtotime($row->job_date))}} <br/>التاريخ</font> </td>

								   </tr>
								   <tr>
									<td colspan="2"><font size="2" color="#59981A">Received From:  <br/>الستلمنا من</font> </td>
									
								   </tr>
								   <tr>
									<td colspan="2"><font size="2" color="#59981A">The Sum of Dirhams:  <br/>مبلخ وقدره درهم</font> </td>
									
								   </tr>
								    <tr>
									<td colspan="2"><font size="2" color="#59981A"> Against Consignment No: {{$row->job_code}} <br/> ضد شحنة لا</font> </td>
									
								   </tr>
								    <tr>
									<td colspan="2"><font size="2" color="#59981A">Consignee:{{$row->consignee_name}} <br/> مستلم الشحنة</font> </td>
									
								   </tr>
									<tr>
										<td ><font size="2" color="#59981A">Cashier   امين الصندق	:  </font> </td>
									<td ><font size="2" color="#59981A">Accountant    المحاسب  		:  </font> </td>

									</tr>
									
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
   
{{-- page level scripts --}}
@section('footer_scripts')
    <!-- begining of page level js -->
<script type="text/javascript" src="{{asset('assets/js/custom_js/invoice.js')}}"></script>
    <!-- end of page level js -->
@stop
