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
										 <b style="font-size:30px;"><img src="{{asset('assets/img/logo.jpg')}}" alt="Image"/></b><br/>
									<!--	 <img src="{{asset('assets/'.Session::get('logo').'')}}" width="10%" /> 
										
										
										<font color="00008B"><b style="font-size:25px;">{{Session::get('company')}}</b></font><br/> -->
										
									</td>
								</tr>
								<tr>								
									<td align="center" colspan="5">
									
										<b style="font-size:15px;">Tel: {{Session::get('phone')}},  Fax:{{Session::get('vatno')}}, Email:{{Session::get('email')}}, P.O.BOX:{{Session::get('pin')}}, {{Session::get('address')}}</b><br/>
										
									</td>
								</tr>
								</table>
								<table align="center" border="2" style="width:25%;">
								<tr>
									<td align="center" bgcolor="#D2FBA4"><h4><b>{{$voucherhead}} </b></h4></td>
								</tr>
								</table>
							
                        </div>
						
                        <div class="col-md-12">
							
							<table class="table" border="0">
								<tr>
									<td><b><font size="2" color="#59981A">WB No: {{$row->bill_no}}</font></b></td>
									<td><b><font size="2" color="#59981A">Desp.No: {{$row->despatch_no}}</font></b> </td>
									<td><b><font size="2" color="#59981A">Type:<?php if($items[0]->trans_type==1) echo "Full Truck"; else if($items[0]->trans_type==2) echo "Consolidation"; else if($items[0]->trans_type==3) echo "Transportation"; ?> </font></b> </td>
									<td><b><font size="2" color="#59981A">Date: {{date('d-m-Y',strtotime($row->bill_date))}}</font></b></td>
									
								</tr>
							</table>
							
							<table class="table " border="1">
								<tr>
									<td colspan="2"><font size="2" >CONSIGNEE: {{$row->consignee_name}} </td>
									<td>City: {{$row->address}}  </td>
									<td>Phone:{{$row->phone}} </td>
									<td>Mobile: {{$row->alter_phone}} </td>
								</tr>
								
							</table>
							<p>
							<table class="table " border="1">
								<thead>
								<tr bgcolor="#D2FBA4">
									<th>Cons No:رقم شحنة</th>
									<th>Shipper(s)  ِشاحن</th>
									<th>Received:مستلم</th>
									<th>Despatched الإرسل</th>
									<th>Packing Type نوع التعبئة</th>
									<th>Remarks الملاحظات</th>
								</tr>
								</thead>
								<tbody>
								@php $total = 0; @endphp
								@foreach($itm as $item)
					           <tr>
									
										<td>{{$item->job_code}}</td>
										<td>{{$item->shipper}}</td>
										<td>{{$item->received}}</td>
										<td>{{$item->despatched}}</td>
										<td>{{$item->ptypes}}</td>
										<td>{{$item->remarks}}</td>
										@php $total += $item->despatched; @endphp
									</tr>
								@endforeach
								</tbody>
								<tfoot>
									<tr bgcolor="#D2FBA4">
										<td colspan="2">Delivery Type: {{$items[0]->delivery_type}}</td>
										<td>Total</td>
										<td>{{$total}}</td>
										<td colspan="2">Shipping Mark: {{$items[0]->shipper_city}}</td>
									</tr>
								</tfoot>
							</table>
							
							</p>
							 <p>
							<table class="table " border="1">
								<tr bgcolor="#D2FBA4">
									<td align="center"><b>Terms & Conditions( البنود  والشروط  )</b></td>
								</tr>
								<tr>
									<td><font size="1">1. Citylink is not repsonsible for any dealy , damage or loss arising due to action taken by  Government Authorities  نحن لسنا مسؤلين عن اي تأخير او ضرر او خصارة ناتجه عن الاجراءات المتخذه من قبل الجمارك او غيرها من </font> </td>
								</tr>
								<tr>
									<td><font size="1">2. Proper & adequte packing is Shipper's responsiblity.التعبئة والتغليف المناسب هي مسؤلية الشاحن </font></td>
								</tr>
								<tr>
									<td><font size="1">3. If the shipment is send back by the customs from broder , Shipper/Consignee will be liable for the expesnes.اذا رجعت الشحنه من الجمارك يكون مسؤل الشاحن او المرسل اليه عن اي مصارف  </font></td>
								</tr>
								<tr>
									<td><font size="1">4. Incase of loss or damage, without a declared value, Citylink will pay AED: 50.00 per carton/piece.<br/>لشروط في حالة فقدان الشحنه او تلفها   في حالة عدم وجود قيمه مصرح بها يتم دفع مبلغ 50 درهم عن كل كرتون
                               </font></td>
								</tr>
								
							</table>
							</p>
							
							
							<table class="table " border="1">
								<tr bgcolor="#D2FBA4">
									<td align="center" colspan="1"> <b>  Despatcher(الرا سل )</b></td>
									<td align="center" colspan="4"> <b> Carrier/Driver Details (تفاصيل الناقل / السائق)</b> </td>
									<td align="center" colspan="3"><b> Payment Details (بيانات الدفع)	</b></td>
									

								</tr>
								<tr>
									<td>Name(اسم):  </td>

									<td colspan="2">Name(.اسم): {{$row->driver}} </td>
									<td >ID No(رقم الهوية):{{$row->driver_id}} </td>
                                    <td >PP No(رقم الهوية):{{$row->passport_no}} </td>


									<td>Payment Type: {{$items[0]->collection_type}} </td>
									<td colspan="2">Freight Charges:	 </td>

									
								</tr>
								<tr>
								    <td>Designation(تعيين): </td>

									<td colspan="2">Vehicle No(رقم  المركبة): {{$row->vehicle_no}}</td> 
								    <td colspan="2">Mobile No: {{$row->mob_uae}}</td>

									<td>Collected by : </td>
									<td colspan="2"> Other Charges:	</td>

									
								</tr>
								<tr>
									<td>Sign(التوقيع): </td>

									<td>Vehicle Type(المركبةنوع): {{$row->vehicle_name}}</td>
								     <td colspan="2">Mobile No: {{$row->mob_ksa}}</td> 
								  <td>Sign(التوقيع):  </td>

								  <td>Receipt No: </td>
									<td colspan="2">Total:	</td>

									
								</tr>
								<tr bgcolor="#D2FBA4">
									<td align="center" colspan="8"> <b>Receiver Details (تفاصيل المستلم)</b></td>
								</tr>
								<tr>
									<td>Received Goods in good  and  order and condtion.<br/>البضائعالمستلمة بحالة جيدة	</td>
									<td>Sign(التوقيع:): </td>
									<td>Name(اسم):  </td>
									<td>ID No(رقم الهوية): </font> </td>
									 <td>Vehicle No(رقم  المركبة:):  </font></td>
									 <td>Date:  </td>
									<td>Stamp(ختم):  </td>
									<td>Remarks: </td>
								</tr>
								<tr bgcolor="#D2FBA4">
										<td colspan="2" >Prepared by: {{Auth::user()->name}} </td>
										<td colspan="3">Date: {{date('d-m-Y',strtotime($row->bill_date))}}</td>
										<td colspan="3" >Sign: </td>
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
        
        </section>

{{-- page level scripts --}}
@section('footer_scripts')
    <!-- begining of page level js -->
<script type="text/javascript" src="{{asset('assets/js/custom_js/invoice.js')}}"></script>
    <!-- end of page level js -->
@stop
