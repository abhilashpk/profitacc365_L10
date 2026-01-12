@extends('printstatement')
@section('contentnew')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/bootstrap.css')}}">
                        <div class="col-md-12">
						<p>Date From: <b><?php echo ($fromdate=='')?date('d-m-Y',strtotime($settings->from_date)):$fromdate;?></b> &nbsp; To: <b><?php echo ($todate=='')?date('d-m-Y'):$todate;?></b></p>
						<br/><?php echo $voucherhead;?>
							<?php if($voucherhead == 'Opening Trial Balance - Summary' || $voucherhead == 'Opening Trial Balance - Summary as on Date') { ?>
								
								<div class="table-responsive">
									
									<table class="table table">
										<thead>
										<tr>
											<th>
												<strong>Account Group</strong>
											</th>
											<th class="text-right">
												<strong>Debit</strong>
											</th>
											
											<th class="text-right">
												<strong>Credit</strong>
											</th>
											
											<th class="text-right">
												<strong></strong>
											</th>
										</tr>
										</thead>
										<tbody>
										<?php $cr_total = $dr_total = $bl_total = 0; ?>
										@foreach($results as $transaction)
										<tr> <?php  $camt = ($transaction['cr_amount'] < 0)?$transaction['cr_amount']*-1:$transaction['cr_amount'];
													$cr_total += $camt;
													$dr_total += $transaction['dr_amount'];
											?>
											<td>{{$transaction['name']}}</td>
											<td class="emptyrow text-right"><?php echo $damount = ($transaction['dr_amount']!=0)?number_format($transaction['dr_amount'],2):'';?></td>
											<td class="emptyrow text-right"><?php echo $camount = ($camt!=0)?number_format($camt,2):'';?></td>
											<td class="emptyrow"></td>
										</tr>
										@endforeach
										<tr>
											<td class="highrow text-right"><strong>Total:</strong></td>
											<td class="highrow text-right"><strong>{{number_format($dr_total,2)}}</strong></td>
											<td class="emptyrow text-right"><strong>{{number_format($cr_total,2)}}</strong></td>
											
											<td class="emptyrow text-right"></td>
										</tr>
										</tbody>
									</table>
								</div>
							<?php } else if($voucherhead == 'Opening Trial Balance - Groupwise' || $voucherhead == 'Trial Balance - Groupwise As on Date' || $voucherhead == 'Opening Trial Balance - Detail by Account Taged' || $voucherhead == 'Opening Trial Balance - Detail by Account Taged as on Date') { ?>
								<div class="table-responsive">
									<table class="table table">
										<thead>
										<tr>
											<th>
												<strong>Account Group/Head</strong>
											</th>
											<th class="text-right">
												<strong>Debit</strong>
											</th>
											
											<th class="text-right">
												<strong>Credit</strong>
											</th>
											<th class="text-right">
												<strong></strong>
											</th>
										</tr>
										</thead>
										<tbody>
										<?php $cr_total = $dr_total = $bl_total = 0; ?>
										@foreach($results as $transaction)
										<tr>
											<td><b>{{$transaction[0]['group_name']}}</b></td>
											<td></td>
											<td></td>
											<td></td>
										</tr>
											<?php $grp_ctotal = $grp_dtotal = 0; ?>
											@foreach($transaction as $row)
											<tr> <?php 
														if($row['transaction_type']=='Dr') { 
															$dr_amount = $row['op_balance'];
															$cr_amount = 0;
														} else {
															$cr_amount = $row['op_balance'];
															$dr_amount = 0;
														}
														$cr_amount = ($cr_amount < 0)?$cr_amount*-1:$cr_amount;
														$cr_total += $cr_amount;
														$dr_total += $dr_amount;
														$grp_ctotal += $cr_amount;
														$grp_dtotal += $dr_amount;
														
												?>
												<td style="padding-left:100px;">{{$row['master_name']}}</td>
												<td class="emptyrow text-right"><?php echo $damount = ($dr_amount!=0)?number_format($dr_amount,2):'';?></td>
												<td class="emptyrow text-right"><?php echo $camount = ($cr_amount!=0)?number_format($cr_amount,2):'';?></td>
												<td class="emptyrow"></td>
											</tr>
											@endforeach
											<tr>
												<td></td>
												<td class="emptyrow text-right"><b>Group Total: &nbsp; {{number_format($grp_dtotal,2)}}</b></td>
												<td class="emptyrow text-right"><b>{{number_format($grp_ctotal,2)}}</b></td>
												<td></td>
											</tr>
										@endforeach
										<tr>
											<td class="highrow text-right"><strong>Total:</strong></td>
											<td class="highrow text-right"><strong>{{number_format($dr_total,2)}}</strong></td>
											<td class="emptyrow text-right"><strong>{{number_format($cr_total,2)}}</strong></td>
											<td class="emptyrow"></td>
										</tr>
										</tbody>
									</table>
								</div>
							<?php } else if($voucherhead == 'Trial Balance - Detail by Group Taged' || $voucherhead == 'Trial Balance - Detail by Group Taged as on Date') { ?>
									{{$results[0]['group_name']}}
									<div class="table-responsive">
									<table class="table table">
										<thead>
										<tr>
											<th>
												<strong>Account Name</strong>
											</th>
											<th class="text-right">
												<strong>Debit</strong>
											</th>
											
											<th class="text-right">
												<strong>Credit</strong>
											</th>
											<th class="text-right">
												<strong></strong>
											</th>
										</tr>
										</thead>
										<tbody>
											<?php $cr_total = $dr_total = $bl_total = 0; ?>
											@foreach($results as $row)
											<tr> <?php 
														if($row['transaction_type']=='Dr') { 
															$dr_amount = $row['op_balance'];
															$cr_amount = 0;
														} else {
															$cr_amount = $row['op_balance'];
															$dr_amount = 0;
														}
														
														$cr_amount = ($cr_amount < 0)?$cr_amount*-1:$cr_amount;
														$cr_total += $cr_amount;
														$dr_total += $dr_amount;
												?>
												<td>{{$row['master_name']}}</td>
												<td class="emptyrow text-right"><?php echo $damount = ($dr_amount!=0)?number_format($dr_amount,2):'';?></td>
												<td class="emptyrow text-right"><?php echo $camount = ($cr_amount!=0)?number_format($cr_amount,2):'';?></td>
												<td class="emptyrow"></td>
											</tr>
											@endforeach
											
										<tr>
											<td class="highrow text-right"><strong>Total:</strong></td>
											<td class="highrow text-right"><strong>{{number_format($dr_total,2)}}</strong></td>
											<td class="emptyrow text-right"><strong>{{number_format($cr_total,2)}}</strong></td>
											<td class="emptyrow"></td>
										</tr>
										</tbody>
									</table>
								</div>
							<?php } else if($voucherhead == 'Closing Trial Balance - Groupwise') { ?>
								<div class="table-responsive">
									<table class="table table">
										<thead>
										<tr>
											<th>
												<strong>Account Group/Head</strong>
											</th>
											<th class="text-right">
												<strong>Debit</strong>
											</th>
											
											<th class="text-right">
												<strong>Credit</strong>
											</th>
											<th class="text-right">
												<strong></strong>
											</th>
										</tr>
										</thead>
										<tbody>
										<?php $cr_total = $dr_total = $bl_total = 0; ?>
										@foreach($results as $transaction)
										<tr>
											<td><b>{{$transaction['group_name']}}</b></td>
											<td></td>
											<td></td>
											<td></td>
										</tr>
											<?php $grp_ctotal = $grp_dtotal = 0; ?>
											@foreach($transaction['accounts'] as $row)
											<tr> <?php 
														if($row['type']=='Dr') { 
															$dr_amount = $row['amount'];
															$cr_amount = 0;
														} else {
															$cr_amount = $row['amount'];
															$dr_amount = 0;
														}
														
														$cr_total += $cr_amount;
														$dr_total += $dr_amount;
														$grp_ctotal += $cr_amount;
														$grp_dtotal += $dr_amount;
														
												?>
												<td style="padding-left:100px;">{{$row['master_name']}}</td>
												<td class="emptyrow text-right"><?php echo $damount = ($dr_amount!=0)?number_format($dr_amount,2):'';?></td>
												<td class="emptyrow text-right"><?php echo $camount = ($cr_amount!=0)?number_format($cr_amount,2):'';?></td>
												
												<td class="emptyrow"></td>
											</tr>
											@endforeach
											<tr>
												<td></td>
												<td class="emptyrow text-right"><b>Group Total: &nbsp; {{number_format($grp_dtotal,2)}}</b></td>
												<td class="emptyrow text-right"><b>{{number_format($grp_ctotal,2)}}</b></td>
												
												<td class="emptyrow text-right"></td>
											</tr>
										@endforeach
										<tr>
											<td class="highrow text-right"><strong>Total:</strong></td>
											<td class="highrow text-right"><strong>{{number_format($dr_total,2)}}</strong></td>
											<td class="emptyrow text-right"><strong>{{number_format($cr_total,2)}}</strong></td>
											
											<td class="emptyrow text-right"></td>
										</tr>
										</tbody>
									</table>
								</div>
							<?php } else if($voucherhead == 'Closing Trial Balance - with Balance') { ?>
								<div class="table-responsive">
									<table class="table table">
										<thead>
										<tr>
											<th>
												<strong>Account Group/Head</strong>
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
										</tr>
										</thead>
										<tbody>
										<?php $cr_total = $dr_total = $bl_total = 0; ?>
										@foreach($results as $transaction)
										<tr>
											<td><b>{{$transaction['group_name']}}</b></td>
											<td></td>
											<td></td>
											<td></td>
										</tr>
											<?php $grp_ctotal = $grp_dtotal = 0; ?>
											@foreach($transaction['accounts'] as $row)
											<tr> <?php 
														if($row['type']=='Dr') { 
															$dr_amount = $row['amount'];
															$cr_amount = 0;
														} else {
															$cr_amount = $row['amount'];
															$dr_amount = 0;
														}
														
														$cr_total += $cr_amount;
														$dr_total += $dr_amount;
														$grp_ctotal += $cr_amount;
														$grp_dtotal += $dr_amount;
														
												?>
												<td style="padding-left:100px;">{{$row['master_name']}}</td>
												<td class="emptyrow text-right"><?php echo $damount = ($dr_amount!=0)?number_format($dr_amount,2):'';?></td>
												<td class="emptyrow text-right"><?php echo $camount = ($cr_amount!=0)?number_format($cr_amount,2):'';?></td>
												
												<td class="emptyrow"></td>
											</tr>
											@endforeach
											<tr>
												<td></td>
												<td class="emptyrow text-right"><b>Group Total: &nbsp; {{number_format($grp_dtotal,2)}}</b></td>
												<td class="emptyrow text-right"><b>{{number_format($grp_ctotal,2)}}</b></td>
												<?php $balance = $grp_dtotal - $grp_ctotal; ?>
												<td class="emptyrow text-right"><b>
													<?php
														echo ($balance > 0)?number_format($balance,2):'('.number_format(($balance*-1),2).')';
													?></b>
												</td>
											</tr>
										@endforeach
										<tr>
											<td class="highrow text-right"><strong>Total:</strong></td>
											<td class="highrow text-right"><strong>{{number_format($dr_total,2)}}</strong></td>
											<td class="emptyrow text-right"><strong>{{number_format($cr_total,2)}}</strong></td>
											
											<td class="emptyrow text-right">
												
											</td>
										</tr>
										</tbody>
									</table>
								</div>
							<?php } else if($voucherhead == 'Closing Trial Balance - Summary') { ?>
								<div class="table-responsive">
									<table class="table table">
										<thead>
										<tr>
											<th>
												<strong>Account Group</strong>
											</th>
											<th class="text-right">
												<strong>Debit</strong>
											</th>
											
											<th class="text-right">
												<strong>Credit</strong>
											</th>
											
											<th class="text-right">
												<strong></strong>
											</th>
										</tr>
										</thead>
										<tbody>
										<?php $cr_total = $dr_total = $bl_total = $btotal = 0; ?>
										@foreach($results as $transaction)
										<tr> <?php  $camt = ($transaction['cr_amount'] < 0)?$transaction['cr_amount']*-1:$transaction['cr_amount'];
													$cr_total += $camt;
													$dr_total += $transaction['dr_amount'];
											?>
											<td>{{$transaction['group_name']}}</td>
											<td class="emptyrow text-right"><?php echo $damount = ($transaction['dr_amount']!=0)?number_format($transaction['dr_amount'],2):'';?></td>
											<td class="emptyrow text-right"><?php echo $camount = ($camt!=0)?number_format($camt,2):'';?></td>
											
											<td class="emptyrow text-right"></td>
										</tr>
										@endforeach
										<tr>
											<td class="highrow text-right"><strong>Total:</strong></td>
											<td class="highrow text-right"><strong>{{number_format($dr_total,2)}}</strong></td>
											<td class="emptyrow text-right"><strong>{{number_format($cr_total,2)}}</strong></td>
											
											<td class="emptyrow text-right"></td>
										</tr>
										</tbody>
									</table>
								</div>
								<?php } ?>
                        </div>

@stop
