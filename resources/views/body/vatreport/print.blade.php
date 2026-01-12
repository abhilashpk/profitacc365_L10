@extends('printgeneral')
@section('contentnew')

						<?php if($voucherhead=='Vat Report Summary') { ?>
									<table class="table horizontal_table table-striped" id="tableAcmaster">
										<thead>
											<tr>
												<th>SI.No</th>
												<th>Group Name</th>
												<th>Account ID</th>
												<th>Account Name</th>
												<th class="text-right">Vat Amount</th>
												<th></th>
												<th></th>
												<th></th>
											</tr>
										</thead>
										<tbody>
										<?php $i=0;  ?>
											@foreach($reports as $report)
											<?php $i++; 
												if($report->master_name=='VAT OUTPUT')
													$out = $report->cl_balance;
												else
													$in = $report->cl_balance;
											?>
											<tr>
												<td>{{$i}}</td>
												<td>{{ $report->group_name }}</td>
												<td>{{ $report->account_id }}</td>
												<td>{{ $report->master_name }}</td>
												<td class="text-right">{{ number_format($report->cl_balance,2) }}</td>
												<td></td>
												<td></td>
												<td></td>
											</tr>
											@endforeach
											<tr>
												<td></td><td></td><td></td><td><b>Total Vat Payable:</b></td>
												<td class="text-right"><b>{{$out-$in}}</b></td>
												<td></td>
												<td></td><td></td>
											</tr>
										</tbody>
									</table>
									
									<?php } else if($voucherhead=='Vat Report Detail'){ ?>
								
					
								<div class="table-responsive">
									<table class="table table-striped table-condensed">
										<thead>
										<tr class="bg-primary">
											<th style="width:70px;">
												<strong>Invoice No</strong>
											</th>
											<th>
												<strong>Date</strong>
											</th>
											<th>
												<strong>Supplier</strong>
											</th>
											<th class="text-right">
												<strong>Amount</strong>
											</th>
											<th class="text-right">
												<strong>Discount</strong>
											</th>
											<th class="text-right">
												<strong>Vat</strong>
											</th>
											<th class="text-right">
												<strong>Net Amount</strong>
											</th>
											
											<th class="emptyrow" style="width:20px;"></th>
										</tr>
										</thead>
										<tbody>
										<tr><td colspan="8"><strong>Purchase</strong></td></tr>
										<?php $purvat = 0;?>
										@foreach($reports['purchase'] as $row)
										<tr> 
											<td>{{$row->voucher_no}}</td>
											<td>{{$row->voucher_date}}</td>
											<td>{{$row->master_name}}<br/>TRN:{{$row->vat_no}}</td>
											<td class="emptyrow text-right">{{number_format($row->total,2)}}</td>
											<td class="emptyrow text-right">{{number_format($row->discount,2)}}</td>
											<td class="emptyrow text-right">{{number_format($row->vat_amount,2)}}</td>
											<td class="emptyrow text-right">{{number_format($row->net_amount,2)}}</td>
											<td class="emptyrow"></td>
											<?php $purvat += $row->vat_amount; ?>
										</tr>
										@endforeach
										<tr>
											<td></td>
											<td class="highrow text-right"></td>
											<td class="highrow text-right"><strong></strong></td>
											<td class="emptyrow text-right"></td>
											<td class="emptyrow text-right"><strong>Vat Input:</strong></td>
											<td class="emptyrow text-right"><strong>{{number_format($purvat,2)}}</strong></td>
											<td class="emptyrow text-right"></td>
											<td class="emptyrow"></td>
										</tr>
										
										<tr><td colspan="8"><strong>Sales</strong></td></tr>
										<?php $salevat = 0;?>
										@foreach($reports['sales'] as $row)
										<tr> 
											<td>{{$row->voucher_no}}</td>
											<td>{{$row->voucher_date}}</td>
											<td>{{$row->master_name}}<br/>TRN:{{$row->vat_no}}</td>
											<td class="emptyrow text-right">{{number_format($row->total,2)}}</td>
											<td class="emptyrow text-right">{{number_format($row->discount,2)}}</td>
											<td class="emptyrow text-right">{{number_format($row->vat_amount,2)}}</td>
											<td class="emptyrow text-right">{{number_format($row->net_total,2)}}</td>
											<td class="emptyrow"></td>
											<?php $salevat += $row->vat_amount; ?>
										</tr>
										@endforeach
										<tr>
											<td></td>
											<td class="highrow text-right"></td>
											<td class="highrow text-right"><strong></strong></td>
											<td class="emptyrow text-right"></td>
											<td class="emptyrow text-right"><strong>Vat Output:</strong></td>
											<td class="emptyrow text-right"><strong>{{number_format($salevat,2)}}</strong></td>
											<td class="emptyrow text-right"></td>
											<td class="emptyrow"></td>
										</tr>
										</tbody>
									</table>
								</div>
						<?php } ?>

@stop
