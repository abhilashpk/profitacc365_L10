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

table td { padding-left: 5px; }
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
										 <b style="font-size:20px;"><img src="{{asset('assets/img/agmtheader.jpg')}}" alt="Image"/></b><br/>
									
										
									</td>
								</tr>
								
								
								<tr>
									<td align="center" ><font color="#FF0000"><h6><b><u>DUBAI CIVIL DEFENCE APPROVED LPG CONTRACTOR</u></b></h6></font></td>
								</tr>
								<tr>
									<td align="center" ><h4><b><u>Operation and Maintenance Agreement</u></b></h4></td>
								</tr>
							</table>
							</div>
                        </div>
						
                        <p>
							 <div class="col-md-12">	
							<table class="table" border="1" >
								<tr>
									<td><b>Building Name </b></td>
									<td colspan="3"> {{$details->buildingname}} </td>
								</tr>
								<tr>
								<td><b>Flat No.</b> </td>
								<td colspan="3"> {{$details->flat_no}} </td>
								</tr>
								<tr>
								<td><b>Tenant Name</b> </td>
								<td colspan="3"> {{$details->master_name}} </td>
								</tr>
								<tr>
								<td><b>Emirates ID number</b> </td>
								<td colspan="3">  </td>
								</tr>
								<tr>
								<td><b>Mobile Number</b> </td>
								<td colspan="3"> {{$details->phone}} </td>
								</tr>
								<tr>
								<td><b>Emai ID</b> </td>
								<td colspan="3"> {{$details->email}} </td>
								</tr>
								<tr>
								<td><b>Initial Meter Reading</b> </td>
								<td> {{$details->new_reading}} </td>
								<td><b>Date</b> </td>
								<td> {{date('d-m-Y',strtotime($details->date))}} </td>
								</tr>
								tr>
								<td><b><font  color="#0000FF">Emergency/Maintenance</font></b> </td>
								<td><b><font  color="#0000FF">+971 50438 9723  +971 42644371</font></b> </td>
								<td><b><font  color="#0000FF">Connection/Disconnection/Bill</font></b> </td>
								<td><b><font color="#0000FF">+971 55992 1214, +971 52537 4256,+971 42644371</font></b> </td>
								
								</tr>
							</table>
                            </p>
                            <table class="table " border="0"  >
								<tr>		
						<td align="center">	<b ><u><h6>Schedule of Rates to Tenant</h6><u></b></td>
                                 </tr>
                            </table>     
                            <p>
                             
							<table class="table " border="1" >
								<tr>
									<td align="center"><b>Sl. No</b> </td>
									<td align="center"><b>Item</b> </td>
									<td align="center"><b>Price</b> </td>
								</tr>
								<tr>
								<td>1.</td>
								<td>L.P. Gas (cooking Gas), 37mbar Low pressure system</td>
								<td>AED 15/- per M3 charges will applicable on consumption of the Gas</td>
								</tr>
								<tr>
								<td>2.</td>
								<td>New Connection Charge</td>
								<td>AED 125/-</td>
								</tr>
								<tr>
								<td>3.</td>
								<td>Security Deposit (Refundable)</td>
								<td>AED 350/-</td>
								</tr>
                                <tr>
								<td>4.</td>
								<td>Admin Charges</td>
								<td>AED 20/-</td>
								</tr>
                                <tr>
								<td>5.</td>
								<td>Disconnection Charge</td>
								<td>AED 75/-</td>
								</tr>
                                <tr>
								<td>6.</td>
								<td>VAT charges</td>
								<td>5% from each invoice</td>
								</tr>
							</table>
                            </p>
							<p>
							<b ><h6>Note:</h6></b>
							    1. LPG Cylinders are not permitted to use inside the kitchens<br/>
							    2. The product cost is purely depended on the International market rates. Any revision of the rates shall be applicable to 
                                  the customer and prior intimation shall be provided to client / Tenant.<br/>
								3. Tenants will be responsible for any damage due to misuse of LPG systems installed in their Premises.  <br/>
								4. Customer should provide access to apartments/kitchens for Meter reading, Maintenance, Connection and disconnection services. <br/>
								5. The non-payment shall be deducted from the deposit, In case of bill amount is more than deposit value means the amount shall be recovered as 
								   per M/s. Berg Technical Services LLC Company's legal procedures.<br/>
								6. Security deposit amount shall be paid back to the tenant upon clearing all the outstanding; provide the 
								   original deposit slip and handover the system in a good condition.  <br/>
								7. Replacement of any spare part will be chargeable to the tenant.<br/>
								8. Refund of final settlement <b>(Security Deposit)</b> will be done only within 8 - 12 working days after disconnection. <br/>
								9. Please read the contract carefully before signing it.

							</p>
                            <p>
                            </div>
							<table class="table " border="0"  >
								<tr>
									<td align="left" ><b>First Party</b></td>
									<td align="right"  ><b>Second Party</b></td>
								</tr>
								<tr>
									<td align="left"  ><b>M/s. Berg Technical Services LLC </b></td>
									<td align="right"  ><b> M/s. …………………………………..</b></td>
								</tr>
								
								<tr>
								<td align="left" ><b>Authorised Signatory</b></td>
								<td align="right" ><b>Authorised Signatory</b></td>
								</tr>
                                </table>
                                <table class="table " border="0"  >
								<tr>								
									<td  >
										 <b style="font-size:20px;"><img src="{{asset('assets/img/agrmtfooter.jpg')}}" alt="Image"/></b><br/>
									
										
									</td>
								</tr>
							</table>
							 </p>
							
							
							
                        
						
						
							
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
