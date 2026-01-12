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
						
							<!--<table border="0" style="width:100%;">
							    
								<tr><td width="100%" align="center">
								  <b style="font-size:10px;"><img src="{{asset('assets/img/logo.jpg')}}" alt="Image" width="20%"/></b><br/>
								 <img src="{{asset('assets/'.Session::get('logo').'')}}" width="10%" /> 		
							<font color="00008B">	<b style="font-size:25px;">{{Session::get('company')}}</b> </font></td> 
									
								</tr>
								</table><br/> -->
								<table border="1"  class="table">
								<tr >
								   <td ><font  color="#59981A"><b>Despatch No: </b> </font> </td>
								    <td> <font  color="#59981A"><?php echo $fromno;?> </font></td>
								   <td bgcolor="#D2FBA4"align="center"><font color="#59981A"><b><h4>CITYLINK EXPRESS CARGO <h4></b> </font> </td>
								   <td > <font color="#59981A"><b> TYPE:</b> </font></td>
								   <td> <font  color="#59981A"><?php echo $results[0]->container_type;?> </font></td>
									</tr>

								<tr >	
								    <td ><font  color="#59981A"> <b>Date:</b></font> </td>
									<td> <font  color="#59981A"><?php echo date('d-m-Y', strtotime($results[0]->despatch_date))?></font></td>
                                    <td bgcolor="#D2FBA4"   align="center"><font color="#59981A"><h4>{{$voucherhead}}</font></h4></td>
									<td ><font color="#59981A"> <b>Destination:</b> </font> </td>
									<td ><font color="#59981A"><?php echo $results[0]->offloading_place;?> </font> </td>

								</tr>
							</table><br/>
							
                    

						<p>
									<table class="table" border="1">
										<thead>
											<tr bgcolor="#D2FBA4">
												<th>NO</th>
								
												<th>CON NO</th>
												<th>CONSIGNEE</th>
												<th>TEL</th>
												<th>W/B</th>
												<th>CITY</th>
												<th>QNTY</th>
												<th>PCK</th>
												<th >AMOUNT</th>
												<th>CLCT</th>
												<th>DLRY</th>
												<th>REMARKS</th>
											</tr>
										</thead>
										<tbody>
										@if(!empty($results))
										@php $i=1; $total = 0; $tot = 0;$total_amt=0; $tot_amt=0; @endphp
											@foreach($waybills as $row)
											
											<tr>
											@php $total_amt = $row->total_amount;
											$tot_amt=round($total_amt,2); @endphp

												<td width="2%">{{$i++}}</td>
												
												<td width="6%">{{ $row->jobs }}</td>
												<td width="6%">{{ $row->consignee_name }}</td>
												<td width="6%">{{ $row->phone }}</td>
												<td width="2%">{{ $row->wbill_no }}</td>
												<td width="4%">{{ $row->destination }}</td>
												<td width="2%">{{ $row->packing_qty  }}</td>
												<td width="4%">{{ $row->pack}}</td>
												<td width="4%">{{number_format($tot_amt,2)}}</td>
												<td width="2%">{{ $row->coll_type }}</td>
												<td width="2%">{{ $row->del_type}}</td>
												<td width="6%">{{ $row->remarks }}</td>
												@php $total += $row->total_amount;
												$tot=round($total,2,PHP_ROUND_HALF_UP); @endphp
											</tr>
											@endforeach
											<tr>
											    
												<td colspan="8" align="right"><b>Total:</b></td>
												<td ><b>{{number_format($tot,2)}}</b></td>
												<td colspan="2" align="left" bgcolor="#D2FBA4"><b>DUTY AMNT</b></td>
												<td ><b>{{$results[0]->duty_amt}}</b></td>
											</tr>
										@else
											<tr><td colspan="11" align="center">No reports were found!</td></tr>
										@endif
										</tbody>

									</table>
									</p>
									<br/>	
								<p>
								
							<table class="table " border="1">
							 <tr bgcolor="#D2FBA4">
							        <td colspan="2 "align="center"><font size="2.5" color="#59981A">DRIVER DETAILS</font></td>
									<td colspan="4 "align="center"><font size="2.5" color="#59981A">DRIVER PAYMENT</font></td>
									<td colspan="2 "align="center"><font size="2.5" color="#59981A">DOCUMENTATION DETAILS</font></td>
								</tr>
							     <tr>
								 <td width="15%">DRIVER NAME: </td>
								 <td width="15%">{{ $results[0]->driver }} </td>

								 <td width="15%">{{$results[0]->agreed_amt}}:  </td>
								 <td width="8%">{{ $results[0]->agreed_transport }}  </td>

								 <td width="15%">LABOUR CHARGE: </td>
								 <td width="8%">{{$results[0]->other_charge }} </td>
								  
								  <td width="10%">EXPORTER: </td>
								  <td width="10%">{{$results[0]->exporter }}</td>
								 </tr>

							<tr>
							 <td>REG NO:  </td>
							 <td>{{ $results[0]->reg }} </td>

							  <td>{{$results[0]->add1_col}}:  </td>
							  <td>{{ $results[0]->add_col1 }}  </td>

							  <td>TOTAL:  </td> 
							  <td>{{$results[0]->total_amount }}  </td>

							 <td>IMPORTER: </td>
							 <td>{{$results[0]->importer}} </td>
							</tr>

								<tr>
								<td>MOBILE-UAE:  </td>
								<td>{{ $results[0]->mob_uae }}  </td> 

								<td>{{$results[0]->add2_col}}: </td>
                                 <td>{{ $results[0]->add_col2 }}  </td>

							    <td>Advance: </td>
								<td> {{$results[0]->advance }}</td>

							   <td>SILA AGENT: </td>
							   <td>{{$results[0]->clear_agent_sila}} </td>
							<tr>
                             <td>MOBILE-KSA:  </td>
							 <td>{{ $results[0]->mob_ksa }}  </td>

							<td>{{$results[0]->add3_col}}:  </td>
							<td>{{ $results[0]->add_col3 }}  </td>

							<td>BALANCE: </td>
							<td>{{$results[0]->balance }} </td>
							<td>BATHA AGENT: </td>
                            <td>{{$results[0]->clear_agent}} </td>
							</tr>
							<tr>
							<td>VEHICLE TYPE:  </td>
							<td>{{ $results[0]->veh_type }}  </td>
							<td colspan="2 ">Remarks</td>
							<td colspan="2 "> {{ $results[0]->remarks }}</td>
							
							<td>CLEARED ON </td>
							<td>{{ date('d-m-Y', strtotime($results[0]->despatch_date))}} </td>
							</tr>	
								
							</table>
							</p>
							
								
                        </div>
						
                    </div>
                    <div class="btn-section">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                                <span class="pull-right">
                                           
                                             <button type="button"
                                                     class="btn btn-responsive button-alignment btn-primary"
                                                     data-toggle="button">
                                                <span style="color:#fff;" onclick="javascript:window.print();">
                                                    <i class="fa fa-fw fa-print"></i>
                                                Print
                                            </span>
                                </button>
								
									<button type="button" onclick="getExport()"
											 class="btn btn-responsive button-alignment btn-primary"
											 data-toggle="button">
										<span style="color:#fff;">
											<i class="fa fa-fw fa-upload"></i>
										Export Excel
									</span>
									</button>
									
								<button type="button" onclick="javascript:window.close();"
                                                     class="btn btn-responsive button-alignment btn-primary"
                                                     data-toggle="button">
                                                <span style="color:#fff;" >
                                                    <i class="fa fa-fw fa-times"></i>
                                                Close 
                                            </span>
                                </button>
                                </span>
                        </div>
					<form class="form-horizontal" role="form" method="POST" name="frmExport" id="frmExport" action="{{ url('cargo_despatchbill/export') }}">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="hidden" name="from_no" value="{{$fromno}}" >
					
					</form>
                    </div>
                </div>
            </div>
            <!-- row -->
        
        <!-- right side bar end -->
        </section>


{{-- page level scripts --}}
@section('footer_scripts')
    <!-- begining of page level js -->
<script>
function getExport() {
	document.frmExport.submit();
}
</script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/invoice.js')}}"></script>
    <!-- end of page level js -->
