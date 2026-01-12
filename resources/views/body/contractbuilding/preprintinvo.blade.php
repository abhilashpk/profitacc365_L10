@extends('layouts/default')

    {{-- Page title --}}
    @section('title')
        Invoice
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
   <!-- <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/invoice.css')}}">-->
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/bootstrap.css')}}">
    <!--end of page level css-->
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <!--section starts-->
            <h1>
            JOURNAL VOUCHER
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="">
                        <i class="fa fa-fw fa-retweet"></i> Transaction
                    </a>
                </li>
                <li>
                    <a href="">Vouchers Entry</a>
                </li>
				<li>
                    <a href="">JOURNAL VOUCHER</a>
                </li>
                <li class="active">
                   Print
                </li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content p-l-r-15" id="cost-job">
            <div class="panel">
                
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
							<table border="0" style="width:100%;">
								<tr>
									<td align="center" colspan="3">
										<b style="font-size:30px;">{{Session::get('company')}}</b><br/>
										<b style="font-size:15px;">Ph: {{Session::get('phone')}}, {{Session::get('address')}}</b><br/>
										<b style="font-size:15px;">TRN No: {{Session::get('vatno')}}</b>
									</td>
								</tr>
								<tr>
									<td width="30%">
									</td><td align="center"><h4><u><b>{{$voucherhead}}</b></u></h4></td>
									<td width="30%" align="left"></td>
								</tr>
								<tr>
									<td width="30%" align="center">
									</td><td align="center"></td>
									<td width="30%" align="right"></td>
								</tr>
							</table><br/>
                        </div>
						
                        <div class="col-md-12">
							 <table border="0" style="width:100%;">
                                     
								<tr>

										<td width="30%" align="left">
                                    <p> <b>Tenant Name :</b> {{$crow->tenant}}</p> 
                                   
                                 </td>
                               
									<td width="30%" align="right">
                                    
                                    
                                    

                                    </td>
								</tr>
							</table>
							<br/>
							<table class="table" border="1">
								<thead>
                               
                               
                                
									<th>Descripton</th>
									<th>Qty</th>
									<th>Rate</th>
									
								  
								   	<th>	VAT Amt.  </th>
								   	<th>Line Total </th>
								     
								</thead>
								<body>
								<?php $dr_total = $fr_total=$br_total=$er_total=$ar_total =$cr_total = 0; $i=1; 
								
								$dr_total +=$acamt[$acrow->prepaid_income]['amount'] +$acamt[$acrow->prepaid_income]['tax'];
								$cr_total +=$acamt[$acrow->deposit]['amount']+$acamt[$acrow->deposit]['tax'];
								$ar_total += $acamt[$acrow->commission]['amount'] +$acamt[$acrow->commission]['tax'];
								$br_total +=$acamt[$acrow->other_deposit]['amount']  +$acamt[$acrow->other_deposit]['tax'];
								$er_total += $acamt[$acrow->parking]['amount'] + $acamt[$acrow->parking]['tax'];
								$fr_total +=$acamt[$acrow->ejarie_fee]['amount'] + $acamt[$acrow->ejarie_fee]['tax'];
								?>
								
                               
									<tr>
                                    
                                   
                                  <td>{{($acrow)?$acrow->acname1:''}}</td>
                                  <td>1</td>
                                    <td>{{($acrow)?$acamt[$acrow->prepaid_income]['amount']:''}}</td>
                                   
										<td>{{($acrow)?$acamt[$acrow->prepaid_income]['tax']:''}}</td>
											<td> {{$dr_total}}</td>
											</tr>
								      
								      
								      
								       	<tr>
								       	     <td>{{($acrow)?$acrow->acname2:''}}</td>
                                   <td>1</td>
                                    <td>{{($acrow)?$acamt[$acrow->deposit]['amount']:''}}</td>
                                    
										<td>{{($acrow)?$acamt[$acrow->deposit]['tax']:''}}</td>
										<td>{{$cr_total}}</td>
											</tr>
								    <tr>
								   <td>{{($acrow)?$acrow->acname4:''}}</td>
                                   <td>1</td>
                                    <td>{{($acrow)?$acamt[$acrow->commission]['amount']:''}}</td>
                                    
										<td>{{($acrow)?$acamt[$acrow->commission]['tax']:''}}</td>
										<td>{{$ar_total}}</td>
											</tr>
											
												<tr>
								   <td>{{($acrow)?$acrow->acname5:''}}</td>
                                   <td>1</td>
                                    <td>{{($acrow)?$acamt[$acrow->other_deposit]['amount']:''}}</td>
                                    
										<td>{{($acrow)?$acamt[$acrow->other_deposit]['tax']:''}}</td>
										<td>{{$br_total}}</td>
											</tr>
												<tr>
								   <td>{{($acrow)?$acrow->acname6:''}}</td>
                                   <td>1</td>
                                    <td>{{($acrow)?$acamt[$acrow->parking]['amount']:''}}</td>
                                   
										<td>{{($acrow)?$acamt[$acrow->parking]['tax']:''}}</td>
										<td>{{$er_total}}</td>
											</tr>
												<tr>
								   <td>{{($acrow)?$acrow->acname7:''}}</td>
                                   <td>1</td>
                                    <td>{{($acrow)?$acamt[$acrow->ejarie_fee]['amount']:''}}</td>
                                    
										<td>{{($acrow)?$acamt[$acrow->ejarie_fee]['tax']:''}}</td>
										<td>{{$fr_total}}</td>
											</tr>
							
								</body>
							</table>
							<table border="0" style="width:100%;">
                                  

										<td width="100%" align="right">
                                    <p> <b>Total :</b> {{number_format($total,2)}}</p> 
                                   
                                    <p><b>Tax Total:</b> {{number_format($txtotal,2)}}</p>
                               
                                    <p><b>Grand Total:</b> {{($crow)?$crow->grand_total:''}}</p> 
                                   
                                
                                
                                
                                </td>
                               
									<td width="30%" align="right">
                                    
                                    
                                    

                                    </td>
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
                                                <span style="color:#fff;" >
                                                    <i class="fa fa-fw fa-times"></i>
                                                Close 
                                            </span>
                                </button>
                                </span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- row -->
        @include('layouts.right_sidebar')
        <!-- right side bar end -->
        </section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <!-- begining of page level js -->
<script type="text/javascript" src="{{asset('assets/js/custom_js/invoice.js')}}"></script>
    <!-- end of page level js -->
@stop
