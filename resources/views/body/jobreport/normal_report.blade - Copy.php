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
            <h1> Job Report</h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="glyphicon glyphicon-folder-close"></i> Reports
                    </a>
                </li>
				<li> Job Report</li>
                <li class="active">
                   <a href="#">Print</a>
                </li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content p-l-r-15" id="cost-job">
            <div class="panel">
                
                <div class="panel-body">
                    <div class="row">
					<table border="0" style="width:100%;">
								<tr><td  align="center"><h3>{{Session::get('company')}}</h3></td>
								</tr>
								<tr>
									<td  align="center"><h4><u>{{$voucherhead}}</u></h4>
									</td>
								</tr>
							</table><br/>
                        </div>
						<p>Date From: <b><?php echo ($fromdate=='')?date('d-m-Y',strtotime($settings->from_date)):$fromdate;?></b> &nbsp; To: <b><?php echo ($todate=='')?date('d-m-Y'):$todate;?></b></p>
                       
                        <div class="col-md-12">
							<?php if($stype=='summary') { ?>
									
									<?php $total_income = $total_expense = $total_netincome = 0; 
											if(count($reports) > 0) {
										?>
									
										
										<table class="table" border="0">					
										<thead >
											<th>SI#</th>
											<th>Job Code</th>
											<th>Job Name</th>
											<th class="text-right">Income</th>
											<th class="text-right">Cost</th>
											<th class="text-right">Net Income</th>
										</thead>
										<body>
										@php $i=0;@endphp
										@foreach($reports as $report)
											<tr>
												<?php $i++;
													$net_income = $report->income - $report->amount;
													$total_income += $report->income;
													$total_expense += $report->amount;
													$total_netincome += $net_income;
												?>
												<td>{{ $i }}</td>
												<td>{{ $report->code }}</td>
												<td>{{ $report->name }}</td>
												<td class="text-right">{{ number_format($report->income,2) }}</td>
												<td class="text-right">{{ number_format($report->amount,2) }}</td>
												<td class="text-right">
												<?php if($net_income < 0) {
													echo '('.number_format(($net_income*-1),2).')';
												} else echo number_format($net_income,2); ?></td>
											</tr>
										@endforeach
											<tr>
												<th class="text-right" colspan="3">Grand Total</th>
												<th class="text-right">{{number_format($total_income,2)}}</th>
												<th class="text-right">{{number_format($total_expense,2)}}</th>
												<th class="text-right">{{number_format($total_netincome,2)}}</th>
											</tr>
										</body>
									</table><br/><br/>
									
									<?php } else { ?>
									
										<div class="well well-sm">No records were found!</div>
										
									<?php } ?>
									
							<?php } else if($stype=='summary_ac' || $stype=='detail1') { ?>	
							<?php if($jobid!=''){ ?><h6><b>Job Code: {{$report->code}}</b>, <b>Job Name: {{$report->name}}</b></h6><?php } ?>
									<?php if(count($reports) > 0) { ?>
									<table class="table" border="0">
										<?php $ginctotal = $gexptotal = $gbalance_total = 0;?>
										@foreach($reports as $report)
										<thead>
											<th colspan="2">Account Name: {{$report[0]->master_name}} </th>
											<th >Tr.No: {{$report[0]->voucher_no}}</type>
											<th></th>
											<th >Type: {{$report[0]->type}}</th>
										</thead>
										<body>
											<?php $inctotal = $exptotal = $balance_total = 0; ?>
											
											<tr>
												<th style="width:35%;">Item Code</th>
												<th style="width:50%;">Description</th>
												<th style="width:15%;" class="text-right">Income</th>
												<th style="width:15%;" class="text-right">Cost</th>
												<th style="width:15%;" class="text-right">Balance</th>
											</tr>
											@foreach($report as $row)
											<?php 
													$inctotal += $row->income; 
													$exptotal += ($row->unit_price * $row->quantity);//$row->amount; 
													$balance = $row->income - ($row->unit_price * $row->quantity);
													$balance_total += $balance;
											?>
											
											<tr>
												<td style="width:20%;">{{$row->item_code}}</td>
												<td style="width:30%;" align="left">{{$row->description}}</td>
												<td style="width:15%;" class="text-right"><?php echo ($row->income > 0)?number_format($row->income,2):'';?></td>
												<td style="width:15%;" class="text-right"><?php echo ($row->amount > 0)?number_format(($row->unit_price * $row->quantity),2):'';?></td>
												<td style="width:15%;" class="text-right">
												<?php if($balance < 0) {
													echo '('.number_format(($balance*-1),2).')';
												} else echo number_format($balance,2); ?></td>
											</tr>
											
											@endforeach
											<?php 
													$ginctotal += $inctotal; 
													$gexptotal += $exptotal; 
													$gbalance_total += $balance_total;
											?>
											<tr>
												<td style="width:1%;"></td>
												<td style="width:4%;" align="right"><b>Total:</b></td>
												<td style="width:15%;" class="text-right"><b>{{number_format($inctotal,2)}}</b></td>
												<td style="width:15%;" class="text-right"><b>{{number_format($exptotal,2)}}</b></td>
												<td style="width:15%;" class="text-right"><b>
												<?php if($balance_total < 0) {
													echo '('.number_format(($balance_total*-1),2).')';
												} else echo number_format($balance_total,2); ?>
												</b></td>
											</tr>
											<tr>
												<td style="width:1%;"></td>
												<td style="width:4%;" align="left"></td>
												<td style="width:15%;" class="text-right"></td>
												<td style="width:15%;" class="text-right"><br/></td>
												<td style="width:15%;" class="text-right"></td>
											</tr>
										
										</body>
										@endforeach
									</table>
									
									<table border="0" style="width:100%;">
										<tr>
											<td style="width:55%;" colspan="3" align="right"><b>Grand Total:</b></td>
											<td style="width:16%;" class="text-right"><b>{{number_format($ginctotal,2)}}</b></td>
											<td style="width:15%;" class="text-right"><b>{{number_format($gexptotal,2)}}</b></td>
											<td style="width:14%;" class="text-right"><b>
											<?php if($gbalance_total < 0) {
													echo '('.number_format(($gbalance_total*-1),2).')';
												} else echo number_format($gbalance_total,2); ?>
											</b></td>
										</tr>
									</table><br/>
									<?php } ?>
							<?php } else if($stype=='detail') { ?>	
									
							<?php $ginctotal = $gexptotal = $gbalance_total = 0; 
											if(count($reports) > 0) {
										?>
									<?php $inctotal = $exptotal = $balance_total = 0; ?>
							
							<tbody id="bod">
								<?php 
								$nsptotal = $ndtotal = $nctotal = $nptotal = $npertotal = $nqty =$profit= 0;
								foreach($reports as $report) { ?>
								
                                				
								@if($workshopsplit==1)
									<table border="0" style="width:100%;">
										<tr>
											<td align="left" valign="top" width="50%" style="padding-left:0px;">
												<div style="border:1px solid #000; padding: 10px;">
												
												<?php if(isset($report->vehicleno)?$report->vehicleno:''!=''){ ?>
												
												<b>Vehicle No:{{$report->vehicleno}}</b><br/>
												<?php }?>
												
												<?php if(isset($report->vehiclemodel)?$report->vehiclemodel:''!=''){ ?>
												<b>Vehicle Model:{{$report->vehiclemodel}} </b><br/>
												<?php }?>
												
												<?php if(isset($report->vehiclemake)?$report->vehiclemake:''!=''){ ?>
												<b>Vehicle Make:{{$report->vehiclemake}} </b><br/>
												<?php }?>
												</div>
											</td>
											<td align="left" width="50%" style="padding-left:10px;">
												<div style="border:1px solid #000; padding: 10px;">
												
												<?php if(isset($report->nextservice)?$report->nextservice:''!=''){ ?>
												<b>Next Service Due.:{{$report->nextservice}} </b><br/>
												<?php }?>
												
												<?php if(isset($report->servicedby)?$report->servicedby:''!=''){ ?>
												<b>Serviced By.:{{$report->servicedby}} </b><br/>
												<?php }?>
												
												<?php if(isset($report->kilometer)?$report->kilometer:''!=''){ ?>
												<b>Kilometer.: {{$report->kilometer}}</b><br/>
												<?php }?>
											
											</div>
											</td>
										</tr>
									</table>
									@endif



											
											
								@if($jobsplit==1)
									<table border="0" style="width:100%;">
										<tr>
											<td align="left" valign="top" width="50%" style="padding-left:0px;">
												<div style="border:1px solid #000; padding: 10px;">
												<b>MAWB: {{$report[0]->mbl}} </b><br/>
												<b>Origin: {{$report[0]->origin}}</b><br/>
												<b>No.of Pcs.: {{$report[0]->no_of_pieces}}</b><br/>
												<b>Gross Wt.: {{$report[0]->gross_weight}}</b><br/>
												<b>Flight: {{$report[0]->flight_no}}</b>
												</div>
											</td>
											<td align="left" width="50%" style="padding-left:10px;">
												<div style="border:1px solid #000; padding: 10px;">
												<b>HAWB No: {{$report[0]->hbl}}</b><br/>
												<b>Destination: {{$report[0]->destination}}</b><br/>
												<b>Volume: {{$report[0]->volume}}</b><br/>
												<b>Charge Wt.: {{$report[0]->chargeable_weight}}</b><br/>
												<b>Packing: {{$report[0]->packing}}</b>
												</div>
											</td>
										</tr>
									</table>
									@endif	
							
									<table class="table" border="0" >
										<tr>
										    
											<td width="7%"><b>Type</b></td>
											<td width="13%"><b>Tr.No</b></td>
											<td width="18%"><b>JobCode</b></td>
											
											<td width="8%"><b>Date</b></td>
											
											<td width="18%"><b>Description</b></td>
											<td width="12%" class="text-right"><b>Income.</b></td>
											<td width="8%" class="text-right"><b>Cost</b></td>
											<td width="10%" class="text-right"><b>Profit</b></td>
										
											
										</tr>
										<tr>
												@if($jobsplit==1)
												<td style="width:20%;">Job Code: <b>{{$report[0]->code}}</b></td>
												<td style="width:30%;" align="left">Customer: <b>{{$report[0]->customer}}</b></td>
												<td style="width:15%;" align="left"></td>
												<!-- <td style="width:30%;" align="left">Job Name: <b>{{$report[0]->name}}</b></td>
												 -->
												
												@else
												<td style="width:20%;" colspan="3">Account Name: <b>{{$report[0]->master_name}}</b></td>
												@endif
												<td style="width:15%;" align="left"></td>
												<td style="width:15%;" align="left"></td>
												<td style="width:15%;"></td>
												<td style="width:15%;"></td>
												<td style="width:15%;"></td>
											</tr>
										<?php 
											$sptotal = $dtotal = $ctotal = $ptotal = $pertotal = $peravg = $sqty = $tcst = 0; $i=1;
											foreach($report as $row) { 
												
												$sprices=$row->income;
												//$sprice = $row['total'] ;
												$pprices = $row->amount; 
											
												
												$sptotal += $sprices;
											
											
												 $n = $i;
												//$sqty += $row['squantity'];
												$tcst += $pprices;
												$profit =  $tcst -$sptotal;
												$desc = '';
												if($row->type=='GI')
													$vdesc = 'Goods Issued Note';
												elseif($row->type=='GR')
													$vdesc = 'Goods Return';
												elseif($row->type=='PI')
													$vdesc = 'Purchase Invoice';
												elseif($row->type=='SI')
													$vdesc = 'Sales Invoice';
												elseif($row->type=='JV')
													$vdesc = 'Journal Entry';
												elseif($row->type=='SP')
													$vdesc = 'Payment Voucher';
												elseif($row->type=='CR')
													$vdesc = 'Receipt Voucher';
												elseif($row->type=='PS')
													$vdesc = $row->description;
												elseif($row->type=='SS')
													$vdesc = $row->description;
													
													$desc = ($row->jdesc=='')?$vdesc:$row->jdesc.'/'.$vdesc;
													/* if($row->jdesc==''){
														
													} else $desc = $row->jdesc; */
										?>
										
										
									<tr>
												<td style="width:1%;">{{$row->type}}</td>
												<td style="width:4%;">{{$row->voucher_no}}</td>
												<td style="width:4%;">{{$row->code}}</td>
												<td style="width:20%;">{{date('d-m-Y',strtotime($row->voucher_date))}}</td>
												<td style="width:30%;" align="left">{{$desc}}</td>
												<td style="width:15%;" class="text-right"><?php echo ($row->income > 0)?number_format($row->income,2):'';?></td>
												<td style="width:15%;" class="text-right"><?php echo ($row->amount > 0)?number_format($row->amount,2):'';?></td>
												<td style="width:15%;" class="text-right"></td>
											</tr>
									<?php } $peravg = $pertotal / $n; 
										$nsptotal += $sptotal;
										$ndtotal += $dtotal;
										$nctotal += $tcst;
										$nptotal += $profit;
										$nqty += $sqty;
										
									?>
									
									<tr>
									<!-- <td></td><td></td> -->
										<td colspan="5" align="right"><b>Sub Total:</b></td>
										<td class="text-right"><b>{{number_format($sptotal,2)}}</b></td>
									
										<td class="text-right"><b>{{number_format($tcst,2)}}</b></td>
										<td width="5%"  class="text-right"><b>{{number_format($profit,2)}}</b></td>
									
									</tr>
									</table>
									
							  <?php } ?>
								<tr style="border:0px solid black;">
									<td colspan="2" align="center"><br/>
									<table border="0" style="width:100%;" class="table">
									
										<tr>
									
										<td></td>
											<td width="50%"  colspan="13" align="right"><b>Net Total:</b></td>
											
											<td style="width:7%;"class="text-right"><b>{{number_format($nsptotal,2)}}</b></td>
									
											<td style="width:7%;" class="text-right"><b>{{number_format($nctotal,2)}}</b></td>
										
																
											<td  style="width:7%;" class="text-right"><b>{{number_format($nptotal,2)}}</b></td>
											
										</tr>
									</table>
									</td>
								</tr>
							</tbody>
						
						</table>
						<?php } else { ?>
										<tr>
											<td colspan="7">
											<div class="well well-sm">No records were found!</div>
											</td>
										</tr>
										<?php } ?>
							<?php } else if($stype=='stockin' || $stype=='stockout') { 
									if(count($reports['invoice']) > 0) {
							?>
									<?php if($reports['invoice']) { ?>
									
									<table class="table" border="0">
										<thead>
											<th style="width:10%;">Type</th>
											<th style="width:10%;">Date</th>
											<!--<th style="width:10%;">Ref.No.</th>-->
											<th style="width:35%;">Item Description</th>
											<th style="width:10%;" class="text-right">Qty.</th>
											<th style="width:10%;" class="text-right">Rate</th>
											<th style="width:15%;" class="text-right">Value</th>
										</thead>
										<body>
										
										<?php foreach($reports['invoice'] as $report) { ?>
											<tr><?php if($report[0]->type=='SI')
													$type = 'SALES INVOICE(STOCK)';
												  else if($report[0]->type=='GI')
													  $type = 'GOODS ISSUED';
												  else if($report[0]->type=='PI')
													  $type = 'PURCHASE INVOICE(STOCK)';
												  else if($report[0]->type=='GR')
													  $type = 'GOODS RETURN';
												?>
												<!-- <td colspan="7"><b><?php // echo $type;?><b></td> -->
											</tr>
											<tr>
												<td colspan="2">Voucher Name: <b>{{$report[0]->master_name}}</b> </td>
												<td>Inv. No: <b>{{$report[0]->voucher_no}}</b></td>
											
												<td style="width:20%;">Job Code: <b>{{$report[0]->code}}</b></td>
											</tr>
											<?php 
											$qty_total = $value_total = 0;
											foreach($report as $row) { ?>
												<tr>
													<!--<td>{{date('d-m-Y',strtotime($row->voucher_date))}}</td>
													<td>{{$row->voucher_no}}</td>
													<td>{{$row->reference_no}}</td>-->
													<td>{{$row->type}}</td>
													<td>{{date('d-m-Y',strtotime($report[0]->voucher_date))}}</td>
													<td>{{$row->item_code.' - '.$row->description}}</td>
													
													<td class="text-right">{{$row->quantity}}</td>
													<td class="text-right">{{number_format($row->unit_price,2)}}</td>
													<?php $value = $row->unit_price * $row->quantity;
														$qty_total += $row->quantity;
														$value_total += $value;
													?>
													<td class="text-right">{{number_format($value,2)}}</td>
												</tr>
											<?php } ?>
											<tr>
											<td class="text-right"></td><td class="text-right"></td>
												<td align="right"><b>Total:</b></td>
												<td class="text-right"><b>{{$qty_total}}</b></td>
												<td class="text-right"></td>
												<td class="text-right"><b>{{number_format($value_total,2)}}</b></td>
											</tr>
										<?php } ?>
										</body>
									</table>
									<?php } ?>
									<?php } else { ?>
										<div class="well well-sm">No records were found!</div>
									<?php } ?>
							<?php } ?>
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
									
									<!--<button type="button" onclick="getExport()"
											 class="btn btn-responsive button-alignment btn-primary"
											 data-toggle="button">
										<span style="color:#fff;">
											<i class="fa fa-fw fa-upload"></i>
										Export Excel
									</span>
									</button> -->
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

        </section>
@stop

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
@stop
