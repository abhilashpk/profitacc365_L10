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
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/bootstrap.css')}}">
    <!--<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/invoice.css')}}">-->
    <!--end of page level css-->
<style type="text/css" media="print">

thead { display: table-header-group; }

#inv
{
	display: table-footer-group;
	/* position: fixed;*/
     bottom: 0;
	 margin: 10 auto 0 auto;
}


</style>
@stop

{{-- Page content --}}
@section('content')
		<section class="content-header">
            <h1>{{$titles['main_head']}}</h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="glyphicon glyphicon-list-alt"></i> Inventory
                    </a>
                </li>
				<li> {{$titles['main_head']}}</li>
                <li class="active">
                   <a href="#">Print</a>
                </li>
            </ol>
        </section>
		<section class="content p-l-r-15" id="invoice-stmt">
            <div class="panel">
                
                <div class="panel-body">
                    <div class="print" id="invoicing">
						<div class="col-md-12">
						
						<?php if(count($transactions) > 0) { ?>
						@if($type=='statement')
						
                            <div class="table-responsive">
                                <table class="table table-bordered" width="100%" border="0">
                                    <thead>
									<tr>
										<td colspan="8" align="center" colspan="2"><img src="{{asset('assets/'.Session::get('logo').'')}}" width="80%" /></td>
									</tr>
									<tr>
										<td colspan="8">
											<h4>{{$resultrow->master_name.' ('.$resultrow->account_id.')'}}</h4>
											<p>From: {{$fromdate}} </p><p> To: {{$todate}}</p>
										</td>
									</tr>
                                    </thead>
                                    <tbody>
									 <tr class="bg-primary">
										<th style="width:5% !important;">Type</th>
										<th style="width:10% !important;">No</th>
										<th style="width:15% !important;">Date</th>
										<th style="width:15% !important;">
                                            <strong>Description</strong>
                                        </th>
										<th style="width:10% !important;">
                                            <strong>Ref.No</strong>
                                        </th>
                                        <th style="width:15% !important;" class="text-right">
                                            <strong>Debit</strong>
                                        </th>
                                        <th style="width:15% !important;" class="text-right">
                                            <strong>Credit</strong>
                                        </th>
                                        <th style="width:15% !important;" class="text-right">
                                            <strong>Balance</strong>
                                        </th>
                                    </tr>
									<?php $cr_total = 0; $dr_total = 0; $balance = 0;//$resultrow->op_balance;?>
									@foreach($transactions as $transaction)
									<?php
										$cr_amount = ''; $dr_amount = '';
										if($transaction->transaction_type=='Cr') {
											$cr_amount = number_format($transaction->amount,2);
											if($transaction->amount >= 0) {
												$cr_total += $transaction->amount;
												$balance = bcsub($balance, $transaction->amount, 2);
											} else {
												$cr_total -= $transaction->amount;
												$balance += $transaction->amount;
											}
										} else if($transaction->transaction_type=='Dr') {
											$dr_amount = number_format($transaction->amount,2);
											$dr_total += $transaction->amount;
											$balance += $transaction->amount;
										}
										
										if($balance < 0) {
											$arr = explode('-', $balance);
											$balance_prnt = '('.number_format($arr[1],2).')';
										} else 
											$balance_prnt = number_format($balance,2);
									?>
                                    <tr>
										<td>{{$transaction->voucher_type}}</td>
										<td><?php  echo $transaction->reference; ?></td>
										<td><?php echo ($transaction->invoice_date=='0000-00-00' || $transaction->invoice_date=='01-01-1970')?date('d-m-Y', strtotime($settings->from_date)):date('d-m-Y', strtotime($transaction->invoice_date)); ?></td>
										<td><?php echo ($transaction->voucher_type=="OB")?'Opening Balance':$transaction->description;?></td>
										<td>{{($transaction->reference_from=="")?$transaction->reference:$transaction->reference_from}}</td>
                                        <td class="emptyrow text-right"><?php echo $dr_amount;//echo ($transaction->voucher_type=="OB")?'':$dr_amount;?></td>
                                        <td class="emptyrow text-right">{{$cr_amount}}</td>
                                        <td class="emptyrow text-right">{{$balance_prnt}}</td>
										<!--<td class="emptyrow"></td>-->
                                    </tr>
									@endforeach
									<tr>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
                                        <td class="highrow text-right"><strong><?php echo ($ispdc && count($pdcs) > 0)?'Total with PDC':'Total';?>:</strong></td>
                                        <td class="emptyrow text-right"><strong>{{number_format($dr_total,2)}}</strong></td>
                                        <td class="emptyrow text-right"><strong>{{number_format($cr_total,2)}}</strong></td>
										<?php ?>
										<td class="emptyrow text-right"><strong>{{$balance_prnt}}</strong></td>
                                    </tr>
                                    </tbody>
									<tfoot>
										<tr>
											<td colspan="8"> Sign</td>
										</tr>
									</tfoot>
                                </table>
								<?php
									if($ispdc) {
										if(count($pdcs) > 0) { ?>
									<b>Statement with PDC Details</b>
									<table class="table" border="0">
                                    <thead>
                                    <tr class="bg-primary">
										<th >Type</th><th >No</th><th>Date</th>
										<th >
                                            <strong>Description</strong>
                                        </th>
										<th>
                                            <strong>Reference</strong>
                                        </th>
                                        <th class="text-right">
                                            <strong>PDC Issued</strong>
                                        </th>
                                        <th class="text-right">
                                            <strong>PDC Received</strong>
                                        </th>
										
                                        <th class="text-right">
                                            <strong>Balance</strong>
                                        </th>
										<th class="emptyrow" style="width:10px;"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
									
									<?php $cr_total = 0; $dr_total = 0; $balance = 0;?>
									@foreach($pdcs as $transaction)
									<?php
										$received = ''; $issued = '';
										if($transaction->voucher_type=='PDCR') {
											$received = number_format($transaction->amount,2);
											if($transaction->amount >= 0) {
												$cr_total += $transaction->amount;
												$balance = bcsub($balance, $transaction->amount, 2);
											} else {
												$cr_total -= $transaction->amount;
												$balance += $transaction->amount;
											}
										} else if($transaction->voucher_type=='PDCI') {
											$issued = number_format($transaction->amount,2);
											$dr_total += $transaction->amount;
											$balance += $transaction->amount;
										}
										
										if($balance < 0) {
											$arr = explode('-', $balance);
											$balance_prnt = '('.number_format($arr[1],2).')';
										} else 
											$balance_prnt = number_format($balance,2);
									?>
                                    <tr>
										<td>{{($transaction->voucher_type=='PDCR')?'RV':'PV'}}</td>
										<td>{{$transaction->voucher_no}}</td>
										<td><?php echo ($transaction->voucher_date=='0000-00-00' || $transaction->voucher_date=='01-01-1970')?date('d-m-Y', strtotime($settings->from_date)):date('d-m-Y', strtotime($transaction->voucher_date)); ?></td>
										<td><?php echo $transaction->description.' '.$transaction->master_name;?></td>
										<td>{{$transaction->reference}}</td>
                                        <td class="emptyrow text-right">{{$issued}}</td>
                                        <td class="emptyrow text-right">{{$received}}</td>
                                        <td class="emptyrow text-right">{{$balance_prnt}}</td>
										<td class="emptyrow"></td>
                                    </tr>
									@endforeach
									<tr>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
                                        <td class="highrow text-right"><strong>Total:</strong></td>
                                        <td class="emptyrow text-right"><strong>{{number_format($dr_total,2)}}</strong></td>
                                        <td class="emptyrow text-right"><strong>{{number_format($cr_total,2)}}</strong></td>
										<td class="emptyrow text-right"><strong>{{$balance_prnt}}</strong></td>
										<td class="emptyrow"></td>
                                    </tr>
                                    </tbody>
                                </table>
									<?php } } ?>
								
                            </div>
						@elseif($type=='outstanding')
							<p>As on: {{$todate}}</p>
							<div class="table-responsive">
                                <table class="table" border="0">
                                    <thead>
                                    <tr class="bg-primary">
										<th >Inv.Date</th>
										<th>
                                            <strong>Reference</strong>
                                        </th>
										<th>
                                            <strong>Due Date</strong>
                                        </th>
                                        <th class="text-right">
                                            <strong>Debit</strong>
                                        </th>
                                        <th class="text-right">
                                            <strong>Credit</strong>
                                        </th>
                                        <th class="text-right">
                                            <strong>Balance</strong>
                                        </th>
										<th class="emptyrow" style="width:10px;"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
									<?php $cr_total = 0; $dr_total = 0; $balance = 0;//$resultrow->op_balance; 
										  $amtdate1 = $amtdate2 = $amtdate3 = $amtdate4 = $amtdate5 = 0;
									?>
									@foreach($transactions as $transaction)
									<?php
										$balance_prnt = $transaction['dr_amount'] - $transaction['cr_amount'];
										if($resultrow->category=='CUSTOMER' && $transaction['dr_amount'] > 0 && $transaction['cr_amount'] < $transaction['dr_amount']) {
											
											$dr_total += $transaction['dr_amount'];
											$cr_total += $transaction['cr_amount'];
											
											$balance += $balance_prnt;
											
											if($transaction['dr_amount'] > 0)
												$dr_amount = number_format($transaction['dr_amount'],2);
											else if($transaction['dr_amount'] < 0)
												$dr_amount = '('.number_format($transaction['dr_amount']*-1,2).')';
											else $dr_amount = '';
											
											if($transaction['cr_amount'] > 0)
												$cr_amount = number_format($transaction['cr_amount'],2);
											else if($transaction['dr_amount'] < 0)
												$cr_amount = '('.number_format($transaction['cr_amount']*-1,2).')';
											else $cr_amount = '';
											
											if($balance_prnt > 0)
												$balance_prnt = number_format($balance_prnt,2);
											else if($balance_prnt < 0)
												$balance_prnt = '('.number_format(($balance_prnt*-1),2).')';
											else $balance_prnt = '';
											
											$nodays = date_diff(date_create($transaction['invoice_date']), date_create(date('Y-m-d')));
											
											if($nodays->format("%a%") < 30)
												$amtdate1 += $balance_prnt;
											else if($nodays->format("%a%") > 30 && $nodays->format("%a%") < 60)
												$amtdate2 += $balance_prnt;
											else if($nodays->format("%a%") > 60 && $nodays->format("%a%") < 90)
												$amtdate3 += $balance_prnt;
											else if($nodays->format("%a%") > 90 && $nodays->format("%a%") < 120)
												$amtdate4 += $balance_prnt;
											else if($nodays->format("%a%") > 120)
												$amtdate5 += $balance_prnt; 
											
											//if($balance_prnt!=0) {
												
											if($transaction['dr_amount'] > 0)
												$dr_amount = number_format($transaction['dr_amount'],2);
											else if($transaction['dr_amount'] < 0)
												$dr_amount = '('.number_format($transaction['dr_amount']*-1,2).')';
											else $dr_amount = '';
											
											if($transaction['cr_amount'] > 0)
												$cr_amount = number_format($transaction['cr_amount'],2);
											else if($transaction['dr_amount'] < 0)
												$cr_amount = '('.number_format($transaction['cr_amount']*-1,2).')';
											else $cr_amount = '';
											
											/* if($balance_prnt > 0)
												$balance_prnt = number_format($balance_prnt,2);
											else if($balance_prnt < 0)
												$balance_prnt = '('.number_format(($balance_prnt*-1),2).')';
											else $balance_prnt = ''; */
									?>
                                    <tr>
										<td><?php echo date('d-m-Y', strtotime($transaction['invoice_date'])); ?></td>
										<td>{{$transaction['reference_from']}}</td>
										<td><?php echo date('d-m-Y', strtotime($transaction['invoice_date'])); ?></td>
                                        <td class="emptyrow text-right">{{$dr_amount}}</td>
                                        <td class="emptyrow text-right">{{$cr_amount}}</td>
                                        <td class="emptyrow text-right">{{$balance_prnt}}</td>
										<td class="emptyrow"></td>
                                    </tr>
									<?php  } else if($resultrow->category=='SUPPLIER' && $transaction['cr_amount'] > 0 && $transaction['dr_amount'] < $transaction['cr_amount']) { 
												
												$dr_total += $transaction['dr_amount'];
												$cr_total += $transaction['cr_amount'];
												
												$balance += $balance_prnt;
												
												if($transaction['dr_amount'] > 0)
													$dr_amount = number_format($transaction['dr_amount'],2);
												else if($transaction['dr_amount'] < 0)
													$dr_amount = '('.number_format($transaction['dr_amount']*-1,2).')';
												else $dr_amount = '';
												
												if($transaction['cr_amount'] > 0)
													$cr_amount = number_format($transaction['cr_amount'],2);
												else if($transaction['dr_amount'] < 0)
													$cr_amount = '('.number_format($transaction['cr_amount']*-1,2).')';
												else $cr_amount = '';
												
												if($balance_prnt > 0)
													$balance_prnt = number_format($balance_prnt,2);
												else if($balance_prnt < 0)
													$balance_prnt = '('.number_format(($balance_prnt*-1),2).')';
												else $balance_prnt = '';
												
												$nodays = date_diff(date_create($transaction['invoice_date']), date_create(date('Y-m-d')));
											
											if($nodays->format("%a%") < 30)
												$amtdate1 += $balance_prnt;
											else if($nodays->format("%a%") > 30 && $nodays->format("%a%") < 60)
												$amtdate2 += $balance_prnt;
											else if($nodays->format("%a%") > 60 && $nodays->format("%a%") < 90)
												$amtdate3 += $balance_prnt;
											else if($nodays->format("%a%") > 90 && $nodays->format("%a%") < 120)
												$amtdate4 += $balance_prnt;
											else if($nodays->format("%a%") > 120)
												$amtdate5 += $balance_prnt; 
											
											//if($balance_prnt!=0) {
												
											if($transaction['dr_amount'] > 0)
												$dr_amount = number_format($transaction['dr_amount'],2);
											else if($transaction['dr_amount'] < 0)
												$dr_amount = '('.number_format($transaction['dr_amount']*-1,2).')';
											else $dr_amount = '';
											
											if($transaction['cr_amount'] > 0)
												$cr_amount = number_format($transaction['cr_amount'],2);
											else if($transaction['dr_amount'] < 0)
												$cr_amount = '('.number_format($transaction['cr_amount']*-1,2).')';
											else $cr_amount = '';
											
											if($balance_prnt > 0)
												$balance_prnt = number_format($balance_prnt,2);
											else if($balance_prnt < 0)
												$balance_prnt = '('.number_format(($balance_prnt*-1),2).')';
											else $balance_prnt = '';
									?>
                                    <tr>
										<td><?php echo date('d-m-Y', strtotime($transaction['invoice_date'])); ?></td>
										<td>{{$transaction['reference_from']}}</td>
										<td><?php echo date('d-m-Y', strtotime($transaction['invoice_date'])); ?></td>
                                        <td class="emptyrow text-right">{{$dr_amount}}</td>
                                        <td class="emptyrow text-right">{{$cr_amount}}</td>
                                        <td class="emptyrow text-right">{{$balance_prnt}}</td>
										<td class="emptyrow"></td>
                                    </tr>
									<?php } //} ?>
									@endforeach
									<tr>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td class="highrow text-right"><strong><?php echo ($ispdc && count($pdcs) > 0)?'Total with PDC':'Total';?>:</strong></td>
											<td class="emptyrow text-right"><strong>{{number_format($dr_total,2)}}</strong></td>
											<td class="emptyrow text-right"><strong>{{number_format($cr_total,2)}}</strong></td>
											<td class="emptyrow text-right"><strong>{{$balance_prnt}}</strong></td>
										</tr>
										
									<?php
										if($dr_total > 0)
											$dr_total = number_format($dr_total,2);
										else if($dr_total < 0)
											$dr_total = '('.number_format(($dr_total*-1),2).')';
											
										if($cr_total > 0)
											$cr_total = number_format($cr_total,2);
										else if($cr_total < 0)
											$cr_total = '('.number_format(($cr_total*-1),2).')';
										
										if($balance > 0)
											$balance = number_format($balance,2);
										else if($balance < 0)
											$balance = '('.number_format(($balance*-1),2).')';
									?>
									<tr>
										<td></td>
										<td></td>
                                        <td class="highrow text-right"><strong>Total:</strong></td>
                                        <td class="emptyrow text-right"><strong>{{$dr_total}}</strong></td>
                                        <td class="emptyrow text-right"><strong>{{$cr_total}}</strong></td>
										<td class="emptyrow text-right"><strong>{{$balance}}</strong></td>
										<td class="emptyrow"></td>
                                    </tr>
                                    </tbody>
                                </table>
								<table class="table" border="0">
                                    <thead>
                                    <tr class="bg-primary">
										<th >0-30</th>
										<th >
                                            <strong>31-60</strong>
                                        </th>
										<th>
                                            <strong>61-90</strong>
                                        </th>
										<th>
                                            <strong>91-120</strong>
                                        </th>
                                        <th>
                                            <strong>Above 121</strong>
                                        </th>
                                        <th class="text-right">
                                            <strong>Total</strong>
                                        </th>
										<th class="emptyrow" style="width:10px;"></th>
                                    </tr>
                                    </thead>
										<?php
										if($amtdate1 > 0)
											$amtdate1 = number_format($amtdate1,2);
										else if($amtdate1 < 0)
											$amtdate1 = '('.number_format(($amtdate1*-1),2).')';
										else $amtdate1 = '';
										
										if($amtdate2 > 0)
											$amtdate2= number_format($amtdate2,2);
										else if($amtdate2 < 0)
											$amtdate2 = '('.number_format(($amtdate2*-1),2).')';
										else $amtdate2 = '';
										
										if($amtdate3 > 0)
											$amtdate3= number_format($amtdate3,2);
										else if($amtdate3 < 0)
											$amtdate3 = '('.number_format(($amtdate3*-1),2).')';
										else $amtdate3 = '';
										
										if($amtdate4 > 0)
											$amtdate4= number_format($amtdate4,2);
										else if($amtdate4 < 0)
											$amtdate4 = '('.number_format(($amtdate4*-1),2).')';
										else $amtdate4 = '';
										
										if($amtdate5 > 0)
											$amtdate5= number_format($amtdate5,2);
										else if($amtdate5 < 0)
											$amtdate5 = '('.number_format(($amtdate5*-1),2).')';
										else $amtdate5 = '';
										?>
                                    <tbody>
										<td>{{$amtdate1}}</td><!-- temparory -->
										<td>{{$amtdate2}}</td>
										<td>{{$amtdate3}}</td>
										<td>{{$amtdate4}}</td>
										<td>{{$amtdate5}}</td>
										<td class="emptyrow text-right">{{$balance}}</td>
										<td class="emptyrow"></td>
									</tbody>
								</table>
                            </div>
							@elseif($type=='osmonthly')
							
							<div class="table-responsive">
                                <table class="table" border="0">
                                    <thead>
                                    <tr class="bg-primary">
										<th >Inv.Date</th>
										<th>
                                            <strong>Reference</strong>
                                        </th>
										<th>
                                            <strong>Due Date</strong>
                                        </th>
                                        <th class="text-right">
                                            <strong>Debit</strong>
                                        </th>
                                        <th class="text-right">
                                            <strong>Credit</strong>
                                        </th>
                                       <th class="text-right">
                                            Balance
                                        </th>
										<th class="emptyrow" style="width:10px;"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
									<?php 
										foreach($transactions as $key => $trans) {
										  $cr_total = 0; $dr_total = 0; $balance = 0; $osbaltot = 0;
										 
									?>
									<tr>
										<td colspan="7"><b>
										<?php //echo $key; 
										$dateObj   = DateTime::createFromFormat('!m', $key);
										echo $monthName = $dateObj->format('F'); 
										?></b></td>
									</tr>
									@foreach($trans as $transaction)
                                    <tr>
										<td><?php echo date('d-m-Y', strtotime($transaction['invoice_date'])); 
												$dr_total += $transaction['dr_amount'];
												$cr_total += $transaction['cr_amount'];
												$balance += $transaction['balance'];
												$osbalance = $transaction['dr_amount'] - $transaction['cr_amount'];
												$osbaltot += $osbalance;
												
												if($osbalance > 0)
													$osbalance = number_format($osbalance,2);
												else if($osbalance < 0)
													$osbalance = '('.number_format(($osbalance*-1),2).')';
												
										?></td>
										<td>{{$transaction['reference_from']}}</td>
										<td><?php echo date('d-m-Y', strtotime($transaction['invoice_date'])); ?></td>
                                        <td class="emptyrow text-right">{{$transaction['dr_amount']}}</td>
                                        <td class="emptyrow text-right">{{$transaction['cr_amount']}}</td>
                                       <td class="emptyrow text-right">{{$osbalance}}</td>
										<td class="emptyrow"></td>
                                    </tr>
										
									@endforeach
									<?php
										if($dr_total > 0)
											$dr_total = number_format($dr_total,2);
										else if($dr_total < 0)
											$dr_total = '('.number_format(($dr_total*-1),2).')';
											
										if($cr_total > 0)
											$cr_total = number_format($cr_total,2);
										else if($cr_total < 0)
											$cr_total = '('.number_format(($cr_total*-1),2).')';
										
										if($balance > 0)
											$balance = number_format($balance,2);
										else if($balance < 0)
											$balance = '('.number_format(($balance*-1),2).')';
										
										if($osbaltot > 0)
											$osbaltot = number_format($osbaltot,2);
										else if($osbaltot < 0)
											$osbaltot = '('.number_format(($osbaltot*-1),2).')';
										
									?>
									<tr>
										<td></td>
										<td></td>
                                        <td class="highrow text-right"><strong></strong></td>
                                        <td class="emptyrow text-right"><strong></strong></td>
                                        <td class="emptyrow text-right"><strong>Total:</strong></td>
										<td class="emptyrow text-right"><strong>{{$osbaltot}}</strong></td>
										<td class="emptyrow"></td>
                                    </tr>
										<?php } ?>
                                    </tbody>
                                </table>
                            </div>
							@elseif($type=='item-statement2')
								<p>From: {{$fromdate}} </p><p> To: {{$todate}}</p>
								<div class="table-responsive">
								
									<table class="table" border="0">
										<thead>
										<tr class="bg-primary">
											<th >Delivery Dt.</th>
											<th >Voucher No</th>
											<th>Item</th>
											<th class="text-right">
												<strong>Qty.</strong>
											</th>
											<th class="text-right">
												<strong>Rate</strong>
											</th>
											<th class="text-right">
												<strong>Amount</strong>
											</th>
											
											<th class="emptyrow" style="width:10px;"></th>
										</tr>
										</thead>
										<tbody>
										
										<?php $total = $qty = $rate = 0; ?>
										@foreach($transactions as $transaction)
										
										<tr>
											<td>{{date('d-m-Y', strtotime($transaction->voucher_date))}}</td>
											<td>{{$transaction->voucher_no}}</td>
											<td>{{$transaction->description}}</td>
											<td class="text-right">{{$transaction->quantity}}</td>
											<td class="emptyrow text-right">{{number_format($transaction->unit_price,2)}}</td>
											<td class="emptyrow text-right">{{$transaction->line_total}}</td>
											<td class="emptyrow"></td>
										</tr>
										<?php $total += $transaction->line_total;
												$qty += $transaction->quantity;
												$rate += $transaction->unit_price;
										?>
										@endforeach
										<tr>
											<td></td>
											<td></td>
											<td></td>
											<td class="text-right">{{$qty}}</td>
											<td class="highrow text-right">{{number_format($rate,2)}}</td>
											<td class="emptyrow text-right"><strong>{{number_format($total,2)}}</strong></td>
											<td class="emptyrow"></td>
										</tr>
										</tbody>
									</table>
									
								@elseif($type=='item-statement')	
									<p>From: {{$fromdate}} </p><p> To: {{$todate}}</p>
									<table class="table" border="0">
									<?php 
									$netinvqty = $netinvrate = $netinvtotal = 0;
									foreach($transactions as $report) { ?>
										<thead>
											<th style="width:37%;" colspan="2">Delivery Date: {{date('d-m-Y',strtotime($report[0]->voucher_date))}}</th>
											<th style="width:40%;" colspan="2">Voucher No: {{$report[0]->voucher_no}}</th>
										</thead>
										
										<thead>
											<th style="width:10%;">Item Name</th>
											<th style="width:7%;" class="text-right" >Quantity</th>
											<th style="width:10%;" class="text-right">Rate</th>
											<th style="width:5%;" class="text-right">Amount</th>
										</thead>
										<tbody>
											<?php 
											$invqty = $invrate = $invtotal = 0;
											foreach($report as $row) { 
												$invqty += $row->quantity;
												$invrate += $row->unit_price;
												$invtotal += $row->line_total;
											?>
											<tr>
												<td style="width:10%;">{{$row->description}}</td>
												<td style="width:10%;" class="text-right">{{$row->quantity}}</td>
												<td style="width:17%;" class="text-right">{{number_format($row->unit_price,2)}}</td>
												<td style="width:7%;" class="text-right">{{number_format($row->line_total,2)}}</td>
												
											</tr>
												<?php } 
												
												$netinvqty += $invqty;
												$netinvrate += $invrate;
												$invtotal += $invtotal;
												?>
											<tr>
												<td align="right"><b>Sub Total:</b></td>
												<td style="width:10%;" class="text-right"><b>{{$invqty}}</b></td>
												<td style="width:5%;" class="text-right"><b>{{number_format($invrate,2)}}</b></td>
												<td style="width:18%;" class="text-right"><b>{{number_format($invtotal,2)}}</b></td>
												
											</tr>
											
											<tr>
												<td colspan="4" align="right"><br/></td>
											</tr>
										</tbody>
									<?php } ?>
										<tr>
												<td colspan="0" align="right"><b>Net Total:</b></td>
												<td style="width:10%;" class="text-right"><b>{{$netinvqty}}</b></td>
												<td style="width:5%;" class="text-right"><b>{{number_format($netinvrate,2)}}</b></td>
												<td style="width:18%;" class="text-right"><b>{{number_format($invtotal,2)}}</b></td>
												
											</tr>
											
									</table>
									
								@else
							<p>As on: {{$todate}}</p>
							<div class="table-responsive">
                                <table class="table" border="0">
                                    <thead>
                                    <tr class="bg-primary">
										<th >Inv.Date</th>
										<th >
                                            <strong>Reference</strong>
                                        </th>
										<th>
                                            <strong>Due Date</strong>
                                        </th>
										<th>
                                            <strong>Due Amount</strong>
                                        </th>
                                        <th class="text-right">
                                            <strong>0-30</strong>
                                        </th>
                                        <th class="text-right">
                                            <strong>31-60</strong>
                                        </th>
                                        <th class="text-right">
                                            <strong>61-90</strong>
                                        </th>
										<th class="text-right">
                                            <strong>91-120</strong>
                                        </th>
										<th class="text-right">
                                            <strong>Above 121</strong>
                                        </th>
										<th class="emptyrow" style="width:10px;"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
									<?php $cr_total = 0; $dr_total = 0; $balance = 0; $balance_prnt = 0;
											$amt1T = $amt2T = $amt3T = $amt4T = $amt5T = 0;
									//$resultrow->op_balance;?>
									@foreach($transactions as $transaction)
									<?php
									//if($transaction->voucher_type == 'OBD') {
											$cr_amount = ''; $dr_amount = '';
											$balance_prnt = $transaction['dr_amount'] - $transaction['cr_amount'];	
											$balance += $balance_prnt;
										
											$nodays = date_diff(date_create($transaction['invoice_date']),date_create(date('Y-m-d')));
											$amt1 = $amt2 = $amt3 = $amt4 = $amt5 = '';
											if($nodays->format("%a%") < 30) {
												$amt1 = $balance_prnt;
												$amt1T += $amt1;
											} else if($nodays->format("%a%") > 30 && $nodays->format("%a%") < 60) {
												$amt2 = $balance_prnt;
												$amt2T += $amt2;
											} else if($nodays->format("%a%") > 60 && $nodays->format("%a%") < 90) {
												$amt3 = $balance_prnt;
												$amt3T += $amt3;
											} else if($nodays->format("%a%") > 90 && $nodays->format("%a%") < 120) {
												$amt4 = $balance_prnt;
												$amt4T += $amt4;
											} else if($nodays->format("%a%") > 120) {
												$amt5 = $balance_prnt;
												$amt5T += $amt5;
											}
											
											if($balance_prnt != 0) { 
											
											if($balance_prnt > 0)
												$balance_prnt = number_format($balance_prnt,2);
											else if($balance_prnt < 0)
												$balance_prnt = '('.number_format(($balance_prnt*-1),2).')';
											else $balance_prnt = '';
											
										if($amt1 > 0)
											$amt1 = number_format($amt1,2);
										else if($amt1 < 0)
											$amt1 = '('.number_format(($amt1*-1),2).')';
										else $amt1 = '';
										
										if($amt2 > 0)
											$amt2= number_format($amt2,2);
										else if($amt2 < 0)
											$amt2 = '('.number_format(($amt2*-1),2).')';
										else $amt2 = '';
										
										if($amt3 > 0)
											$amt3= number_format($amt3,2);
										else if($amt3 < 0)
											$amt3 = '('.number_format(($amt3*-1),2).')';
										else $amt3 = '';
										
										if($amt4 > 0)
											$amt4= number_format($amt4,2);
										else if($amt4 < 0)
											$amt4 = '('.number_format(($amt4*-1),2).')';
										else $amt4 = '';
										
										if($amt5 > 0)
											$amt5= number_format($amt5,2);
										else if($amt5 < 0)
											$amt5 = '('.number_format(($amt5*-1),2).')';
										else $amt5 = '';
									?>
										<tr>
											<td><?php echo date('d-m-Y', strtotime($transaction['invoice_date'])); ?></td>
											<td>{{$transaction['reference_from']}}</td>
											<td><?php echo date('d-m-Y', strtotime($transaction['invoice_date'])); ?></td>
											<td class="emptyrow text-right">{{$balance_prnt}}</td>
											<td class="emptyrow text-right">{{$amt1}}</td>
											<td class="emptyrow text-right">{{$amt2}}</td>
											<td class="emptyrow text-right">{{$amt3}}</td>
											<td class="emptyrow text-right">{{$amt4}}</td>
											<td class="emptyrow text-right">{{$amt5}}</td>
											<td class="emptyrow"></td>
										</tr>
									<?php } ?>
									@endforeach
									<?php
										if($balance > 0)
											$balance = number_format($balance,2);
										else if($balance < 0)
											$balance = '('.number_format(($balance*-1),2).')';
										
										if($amt1T > 0)
											$amt1T = number_format($amt1T,2);
										else if($amt1T < 0)
											$amt1T = '('.number_format(($amt1T*-1),2).')';
										
										if($amt2T > 0)
											$amt2T = number_format($amt2T,2);
										else if($amt2T < 0)
											$amt2T = '('.number_format(($amt2T*-1),2).')';
										else $amt2T = '';
										
										if($amt3T > 0)
											$amt3T = number_format($amt3T,2);
										else if($amt3T < 0)
											$amt3T = '('.number_format(($amt3T*-1),2).')';
										else $amt3T = '';
										
										if($amt4T > 0)
											$amt4T = number_format($amt4T,2);
										else if($amt4T < 0)
											$amt4T = '('.number_format(($amt4T*-1),2).')';
										else $amt4T = '';
										
										if($amt5T > 0)
											$amt5T = number_format($amt5T,2);
										else if($amt5T < 0)
											$amt5T = '('.number_format(($amt5T*-1),2).')';
										else $amt5T = '';
										
										
									?>
									<tr>
										<td></td>
										<td></td>
										<td><strong>Total:</strong></td>
                                        <td class="highrow text-right"><strong>{{$balance}}</strong></td>
                                        <td class="emptyrow text-right"><strong>{{$amt1T}}</strong></td>
                                        <td class="emptyrow text-right"><strong>{{$amt2T}}</strong></td>
										<td class="emptyrow text-right"><strong>{{$amt3T}}</strong></td>
										<td class="emptyrow text-right"><strong>{{$amt4T}}</strong></td>
										<td class="emptyrow text-right"><strong>{{$amt5T}}</strong></td>
										<td class="emptyrow"></td>
                                    </tr>
									<!--<tr>
										<td></td>
										<td></td>
										<td><strong>Grand Total:</strong></td>
                                        <td class="highrow text-right"><strong>{{$balance_prnt}}</strong></td>
                                        <td class="emptyrow text-right"><strong>{{$balance_prnt}}</strong></td>
                                        <td class="emptyrow text-right"><strong></strong></td>
										<td class="emptyrow text-right"><strong></strong></td>
										<td class="emptyrow text-right"><strong></strong></td>
										<td class="emptyrow text-right"><strong></strong></td>
										<td class="emptyrow"></td>
                                    </tr>-->
									</tbody>
								</table>
							</div>
						@endif
						
						<div class="btn-section">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                                <span class="pull-left">
									<button type="button" onclick="javascript:window.close();"
                                                     class="btn btn-responsive button-alignment btn-primary"
                                                     data-toggle="button">
                                                <span style="color:#fff;">
                                                    <i class="fa fa-fw fa-arrow-circle-left"></i>
                                                Close 
                                            </span>
                                </button>
								</span>
								<span class="pull-right">
                                           
									 <button type="button" onclick="javascript:window.print();"
											 class="btn btn-responsive button-alignment btn-primary"
											 data-toggle="button">
										<span style="color:#fff;">
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
								
                                </span>
                        </div>
                    </div>
					<form class="form-horizontal" role="form" method="POST" name="frmExport" id="frmExport" action="{{ url('account_enquiry/export') }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="date_from" value="{{$fromdate}}" >
						<input type="hidden" name="date_to" value="{{$todate}}" >
						<input type="hidden" name="type" value="{{$type}}" >
						<input type="hidden" name="account_id" value="{{$id}}" >
					</form>
                </div>
				
						<?php } else { ?>
						<div class="alert alert-danger">
							<p>There were no transactions found!</p>
						</div>
						<?php } ?>
				</div>
			</div>
		</div>
	</section>

@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <!-- begining of page level js -->
<script type="text/javascript" src="{{asset('assets/js/custom_js/invoice.js')}}"></script>

<script>
$(document).ready(function () {
	$('html').attr({style: 'min-height: inherit'});
	$('body').attr({style: 'min-height: inherit'});
});
</script>

    <!-- end of page level js -->
@stop
