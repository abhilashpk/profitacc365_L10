@extends('printgeneral')
@section('contentnew')

						
                            <div class="table-responsive">
                                <table class="table table-striped table-condensed">
                                    <thead>
                                    <tr class="bg-primary">
										<th>
                                            <strong>Account Name</strong>
                                        </th>
										<th>
                                            <strong>Description</strong>
                                        </th>
                                        <th>
                                            <strong>Reference</strong>
                                        </th>
                                        <th class="text-right">
                                            <strong>Debit</strong>
                                        </th>
										
                                        <th class="text-right">
                                            <strong>Credit</strong>
                                        </th>
										<th class="emptyrow" style="width:10px;"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
									<?php $cr_total = 0; $dr_total = 0; ?>
									@foreach($transactions as $transaction)
									<?php
										$cr_amount = ''; $dr_amount = '';
										if($transaction->type=='Cr') {
											$cr_amount = number_format($transaction->amount,2);
											$cr_total += $transaction->amount;
										} else if($transaction->type=='Dr') {
											$dr_amount = number_format($transaction->amount,2);
											$dr_total += $transaction->amount;
										}
									?>
                                    <tr>
										<td>{{$transaction->master_name}}</td>
										<td>{{$transaction->description}}</td>
                                        <td >{{$transaction->reference_no}}</td>
                                        <td class="emptyrow text-right">{{$dr_amount}}</td>
                                        <td class="emptyrow text-right">{{$cr_amount}}</td>
										<td class="emptyrow"></td>
                                    </tr>
									@endforeach
									<tr>
										<td></td>
										<td></td>
                                        <td class="highrow text-right"><strong>Total:</strong></td>
                                        <td class="emptyrow text-right"><strong>{{number_format($dr_total,2)}}</strong></td>
                                        <td class="emptyrow text-right"><strong>{{number_format($cr_total,2)}}</strong></td>
										<td class="emptyrow"></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                       
@stop
