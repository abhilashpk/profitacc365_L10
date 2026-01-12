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
									 <b style="font-size:30px;"><img src="{{asset('assets/img/logo.jpg')}}" alt="Image"/></b><br/>
								<!--		<img src="{{asset('assets/'.Session::get('logo').'')}}" width="10%" /> 		
							<font color="00008B">	<b style="font-size:25px;">{{Session::get('company')}}</b> </font> -->
									</td>
								</tr>
								<tr>								
									<td align="center" colspan="5">
									
										<b style="font-size:15px;">Tel: {{Session::get('phone')}},  Fax:{{Session::get('vatno')}}, Email:{{Session::get('email')}}, P.O.BOX:{{Session::get('pin')}}, {{Session::get('address')}}</b><br/>
										
									</td>
								</tr>
								</table>
								<table align="center" border="1" style="width:10%;">
								<tr>
									<td align="center" bgcolor="#D2FBA4"><h4><b>{{$voucherhead}} </b></h4></td>
								</tr>
								</table><br/>
							
                        </div>
						
                        <div class="col-md-12">
							<table class="table "  border="1">
							   <tr bgcolor="#D2FBA4">
									<td colspan="2" align="center"><font size="2.5" color="#59981A">CONSIGEEE (المستلم)	</font></td>
									<td colspan="2"align="center"><font size="2.5" color="#59981A">SHIPPER(  ِشاحن)</font></td>
								</tr>
								<tr>
									<td ><font size="2" color="#59981A">Name: {{$row->consignee_name}}</font> </td>
									<td ><font size="2" color="#59981A">City: {{$row->consignee_city}}</font> </td>
									<td ><font size="2" color="#59981A">Name: {{$row->shipper_name}}</font> </td>
									<td ><font size="2" color="#59981A">City: {{$row->shipper_city}}</font> </td>
								</tr>
								
								<tr>
									<td ><font size="2" color="#59981A">Mobile: {{$row->consignee_mobile}}</font> </td>
									<td ><font size="2" color="#59981A">Tele: {{$row->consignee_tele}}</font> </td>
									<td ><font size="2" color="#59981A">Mobile: {{$row->shipper_mobile}}</font> </td>
									<td ><font size="2" color="#59981A">Tele:</font> </td>
								</tr>
							</table>
							<p>
							<table class="table " border="2">
							 <tr bgcolor="#D2FBA4">
									<td colspan="4"align="center"><font size="2.5" color="#59981A">CONSIGNMENT NOTE  مذكرة استلام بضائخ</font></td>
								</tr>
									<tr>
									<td ><font size="2" color="#59981A">No: {{$row->job_code}}</font> </td>
									<td ><font size="2" color="#59981A">Weight: {{$row->weight}}</font> </td>
									<td ><font size="2" color="#59981A">Invoice No: {{$row->invoice_nos}}</font> </td>
									<td ><font size="2" color="#59981A">Desp/WB:</font> </td>	
									</tr>
									<tr>
									    <td ><font size="2" color="#59981A">Date: {{date('d-m-Y',strtotime($row->job_date))}}</font> </td>
										<td ><font size="2" color="#59981A">Volume: {{$row->volume}}</font> </td>
										<td ><font size="2" color="#59981A">Payment: {{$row->collection_type}}</font> </td>
										<td ><font size="2" color="#59981A">Delivery Vehicle No: {{$row->shippers_vehno}}</font> </td>
									</tr>
									<tr>
									   <td ><font size="2" color="#59981A">Quantity: {{$row->packing_qty}}</font> </td>
									   <td ><font size="2" color="#59981A">Destination: {{$row->destination}}</font> </td>
										<td ><font size="2" color="#59981A">Receipt No: </font> </td>
										<td ><font size="2" color="#59981A">Driver Mobile No:{{$row->shippers_mob }} </font> </td>
									</tr>
									<tr>
									    <td ><font size="2" color="#59981A">Pack.Type: {{ $ptype}}</font> </td>
										<td ><font size="2" color="#59981A">Service: {{ $row->delivery_type}}</font> </td>
										<td ><font size="2" color="#59981A">Sales Man:{{$salescode->name}} </font> </td>
										<td ><font size="2" color="#59981A">Sign: </font> </td>
									</tr>
									</table>
							</p>
							
							<p>
								<table class="table " border="1">
								<tr bgcolor="#D2FBA4">
									<td colspan="2" align="center"><font size="2.5" color="#59981A">Terms & Conditions( البنود  والشروط  )</font></td>
								</tr>
								<tr>
									<td colspan="2"><font size="2" color="#59981A">1. Citylink is not repsonsible for any dealy , damage or loss arising due to action taken by  Government Authorities <br/> نحن لسنا مسؤلين عن اي تأخير او ضرر او خصارة ناتجه عن الاجراءات المتخذه من قبل الجمارك او غيرها من  </font></td>
								</tr>
								<tr>
									<td colspan="2"><font size="2" color="#59981A">2. Proper & adequte packing is Shipper's responsiblity.<br/> التعبئة والتغليف المناسب هي مسؤلية الشاحن </font></td>
								</tr>
								<tr>
									<td colspan="2"><font size="2" color="#59981A">3. If the shipment is send back by the customs from broder , Shipper/Consignee will be liable for the expesnes.<br/>اذا رجعت الشحنه من الجمارك يكون مسؤل الشاحن او المرسل اليه عن اي مصارف</font></td>
								</tr>
								<tr>
									<td colspan="2"><font size="2" color="#59981A">4. Incase of loss or damage, without a declared value, Citylink will pay AED: 50.00 per carton/piece.<br/>لشروط في حالة فقدان الشحنه او تلفها   في حالة عدم وجود قيمه مصرح بها يتم دفع مبلغ 50 درهم عن كل كرتون
                               </font></td>
								</tr>
								
							
							
						</p>
						<p>
								
									<tr>
										<td colspan="2" align="center"><font size="2" color="#59981A">Any discrepancies in this note, should be informed on same working day in writing.  <br/>
										اي اختلافات في هذه الشروط يجب اعلام الشركة في نفس يوم التسليم 
										    </font></td>
										
										</tr>
								
							</p>
							<p>
								<tr>
										<td align="left"><font size="2" color="#59981A"> Carrier of this is subject to above condition <br/> (نقل هذه البضائع تخضع لشروط المذكورة أعلاه)</font></td>
										<td><font size="2" color="#59981A"> Sign(التوقيع):   </font></td>
								</tr>		
									<tr>	
										<td colspan="2" align="left"><font size="2" color="#59981A">Remarks(ملاحظلت): </font></td>
									</tr>

								
							</p>
                           
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
