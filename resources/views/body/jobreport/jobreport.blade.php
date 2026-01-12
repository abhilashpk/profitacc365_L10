                  <div class="col-md-12">
                     <div class="panel panel-default1">
                        <div class="panel-heading" style="background-color:#86C5FF">
                            <h3 class="panel-title" style="padding-bottom:10px; color:#428BCA !important;"><i class="fa fa-fw fa-list-alt"></i><font color="white"> Job Details</font></h3>
                            <ul class="nav nav-tabs nav-float" role="tablist">
                                <li class="active">
                                    <a href="#SI" role="tab" data-toggle="tab">Sales</a>
                                </li>
                                <li >
                                    <a href="#PI" role="tab" data-toggle="tab">Purchase</a>
                                </li>
                                <li>
                                    <a href="#GI" role="tab" data-toggle="tab">Goods Issued</a>
                                </li>
                                <li>
                                    <a href="#GR" role="tab" data-toggle="tab">Goods Return</a>
                                </li>
                                <li>
                                    <a href="#JV" role="tab" data-toggle="tab">Journal</a>
                                </li>
                                <li>
                                    <a href="#PV" role="tab" data-toggle="tab">Payment Voucher</a>
                                </li>
                                <li>
                                    <a href="#RV" role="tab" data-toggle="tab">Receipt Voucher</a>
                                </li>
                                <li>
                                    <a href="#PC" role="tab" data-toggle="tab">Petty Cash</a>
                                </li>
                                <li>
                                    <a href="#PS" role="tab" data-toggle="tab">Purchase Split</a>
                                </li>
                                <li>
                                    <a href="#SS" role="tab" data-toggle="tab">Sales Split</a>
                                </li>
                            </ul>
                        </div>
                        
                        <div class="panel-body" style="background-color:#d6eef8">
                            <div class="tab-content">
                                
                                <div class="tab-pane fade in active" id="SI">
                                    <div class="row" style="padding:10px;">
                                    <table class="table table-striped" id="tableBank">
                                        <thead>
                                            <tr>
                                                <th>Invoice No:</th>
                                                <th>Date</th>
                                                <th>Item Desc</th>
                                                <th>Qty</th>
                                                <th>Rate</th>
                                                <th>Income</th>
                                                <th>Cost</th>
                                                <th>Profit</th>
                                            </tr>
                                        </thead>
                                        @if(!empty($reports))
                                            <tbody>
                                            @foreach($reports as $key=> $report)
                                            <?php if($key=='SI') {  ?>
                                            @php $qty_total = $total_income = $total_expense = $total_netincome = 0; @endphp
                                            @foreach($report as $row)
                                            <tr>
                                                <td>{{ $row->voucher_no}}</td>
                                                <td>{{ date('d-m-Y', strtotime($row->voucher_date)) }}</td>
                                                <td>{{ ($row->description=='')?$row->jdesc:$row->description}}</td>
                                                <td>{{ ($row->quantity!=0)?$row->quantity:'' }}</td>
                                                <td>{{($row->unit_price!=0)?number_format($row->unit_price,2):'' }}</td>
                                                <td>{{($row->income!=0)?number_format($row->income,2):''}}</td>
                                                <td>{{ ($row->amount!=0)?number_format($row->amount,2):'' }}</td>
                                                <td>{{ number_format($row->income - $row->amount,2) }}</td>
                                            </tr>
                                    @php
										$qty_total += $row->quantity;
										$net_income = $row->income - $row->amount;
										$total_income += $row->income;
										$total_expense += $row->amount;
										$total_netincome += $net_income;
									@endphp
                                            @endforeach
                                    <tr>
										<th class="text-right" colspan="3">Grand Total</th>
										<th >{{$qty_total}}</th>
										<th ></th>
										<th >{{number_format($total_income,2)}}</th>
										<th >{{number_format($total_expense,2)}}</th>
										<th >{{number_format($total_netincome,2)}}</th>
									</tr>    
                                            
                                            <?php } ?>
                                            @endforeach
                                            
                                    
                                        </tbody>
                                        	@else
			                                 <tr><td colspan="11" align="center">No reports were found!</td></tr>
		                                    @endif
                                    </table>
                                    </div>
                                    </div>
                                    
                                    
                                    <div class="tab-pane fade" id="PI">
                                    <div class="row" style="padding:10px;">
                                        <table class="table table-striped" id="tableBank">
                                        <thead>
                                            <tr>
                                                 <th>Invoice No:</th>
                                                <th>Date</th>
                                                <th>Item Desc</th>
                                                <th>Qty</th>
                                                <th>Rate</th>
                                               <!-- <th>Income</th>-->
                                                <th>Cost</th>
                                                <th>Profit</th>
                                            </tr>
                                        </thead>
                                         @if(!empty($reports))
                                            <tbody>
                                            @foreach($reports as $key=> $report)
                                            <?php if($key=='PI') {  ?>
                                            @php $qty_total = $total_income = $total_expense = $total_netincome = 0; @endphp
                                            @foreach($report as $row)
                                            <tr>
                                                <td>{{ $row->voucher_no}}</td>
                                                <td>{{ date('d-m-Y', strtotime($row->voucher_date)) }}</td>
                                                <td>{{ ($row->description=='')?$row->jdesc:$row->description}}</td>
                                                <td>{{ ($row->quantity!=0)?$row->quantity:'' }}</td>
                                                <td>{{($row->unit_price!=0)?number_format($row->unit_price,2):'' }}</td>
                                                <!--<td>{{($row->income!=0)?number_format($row->income,2):''}}</td>-->
                                                <td>{{ ($row->amount!=0)?number_format($row->amount,2):'' }}</td>
                                                <td>{{ number_format($row->income - $row->amount,2) }}</td>
                                            </tr>
                                    @php
										$qty_total += $row->quantity;
										$net_income = $row->income - $row->amount;
										$total_income += $row->income;
										$total_expense += $row->amount;
										$total_netincome += $net_income;
									@endphp
                                            @endforeach
                                     <tr>
										<th class="text-right" colspan="3">Grand Total</th>
										<th >{{$qty_total}}</th>
										<th ></th>
										<!--<th >{{number_format($total_income,2)}}</th>-->
										<th >{{number_format($total_expense,2)}}</th>
										<th >{{number_format($total_netincome,2)}}</th>
									</tr>
                                            <?php } ?>
                                            @endforeach
                                           
                                            
                                        </tbody>
                                        	@else
			                                 <tr><td colspan="11" align="center">No reports were found!</td></tr>
		                                    @endif
                                    </table>
                                </div>
                                </div>
                                    
                                     
                                     <div class="tab-pane fade" id="GI">
                                    <div class="row" style="padding:10px;">
                                        <table class="table table-striped" id="tableBank">
                                        <thead>
                                            <tr>
                                                 <th>Invoice No:</th>
                                                <th>Date</th>
                                                <th>Item Desc</th>
                                                <th>Qty</th>
                                                <th>Rate</th>
                                                <th>Cost</th>
                                                <th>Profit</th>
                                            </tr>
                                        </thead>
                                         @if(!empty($reports))
                                            <tbody>
                                            @foreach($reports as $key=> $report)
                                            <?php if($key=='GI') {  ?>
                                            @php $qty_total = $total_income = $total_expense = $total_netincome = 0; @endphp
                                            @foreach($report as $row)
                                            <tr>
                                                <td>{{ $row->voucher_no}}</td>
                                                <td>{{ date('d-m-Y', strtotime($row->voucher_date)) }}</td>
                                                <td>{{ ($row->description=='')?$row->jdesc:$row->description}}</td>
                                                <td>{{ ($row->quantity!=0)?$row->quantity:'' }}</td>
                                                <td>{{($row->unit_price!=0)?number_format($row->unit_price,2):'' }}</td>
                                                <td>{{ ($row->amount!=0)?number_format($row->amount,2):'' }}</td>
                                                <td>{{ number_format($row->income - $row->amount,2) }}</td>
                                            </tr>
                                    @php
										$qty_total += $row->quantity;
										$net_income = $row->income - $row->amount;
										$total_income += $row->income;
										$total_expense += $row->amount;
										$total_netincome += $net_income;
									@endphp
                                            @endforeach
                                     <tr>
										<th class="text-right" colspan="3">Grand Total</th>
										<th >{{$qty_total}}</th>
										<th ></th>
										<th >{{number_format($total_expense,2)}}</th>
										<th >{{number_format($total_netincome,2)}}</th>
									</tr>
                                            <?php } ?>
                                            @endforeach
                                           
                                            
                                        </tbody>
                                        	@else
			                                 <tr><td colspan="11" align="center">No reports were found!</td></tr>
		                                    @endif
                                    </table>
                                </div>
                                </div>
                                
                                
                                <div class="tab-pane fade" id="GR">
                                    <div class="row" style="padding:10px;">
                                        <table class="table table-striped" id="tableBank">
                                        <thead>
                                            <tr>
                                                 <th>Invoice No:</th>
                                                <th>Date</th>
                                                <th>Item Desc</th>
                                                <th>Qty</th>
                                                <th>Rate</th>
                                                <th>Income</th>
                                                <th>Cost</th>
                                                <th>Profit</th>
                                            </tr>
                                        </thead>
                                         @if(!empty($reports))
                                            <tbody>
                                            @foreach($reports as $key=> $report)
                                            <?php if($key=='GR') {  ?>
                                            @php $qty_total = $total_income = $total_expense = $total_netincome = 0; @endphp
                                            @foreach($report as $row)
                                            <tr>
                                                <td>{{ $row->voucher_no}}</td>
                                                <td>{{ date('d-m-Y', strtotime($row->voucher_date)) }}</td>
                                                <td>{{ ($row->description=='')?$row->jdesc:$row->description}}</td>
                                                <td>{{ ($row->quantity!=0)?$row->quantity:'' }}</td>
                                                <td>{{($row->unit_price!=0)?number_format($row->unit_price,2):'' }}</td>
                                                <td>{{($row->income!=0)?number_format($row->income,2):''}}</td>
                                                <td>{{ ($row->amount!=0)?number_format($row->amount,2):'' }}</td>
                                                <td>{{ number_format($row->income - $row->amount,2) }}</td>
                                            </tr>
                                    @php
										$qty_total += $row->quantity;
										$net_income = $row->income - $row->amount;
										$total_income += $row->income;
										$total_expense += $row->amount;
										$total_netincome += $net_income;
									@endphp
                                            @endforeach
                                     <tr>
										<th class="text-right" colspan="3">Grand Total</th>
										<th >{{$qty_total}}</th>
										<th ></th>
										<th >{{number_format($total_income,2)}}</th>
										<th >{{number_format($total_expense,2)}}</th>
										<th >{{number_format($total_netincome,2)}}</th>
									</tr>
                                            <?php } ?>
                                            @endforeach
                                           
                                            
                                        </tbody>
                                        	@else
			                                 <tr><td colspan="11" align="center">No reports were found!</td></tr>
		                                    @endif
                                    </table>
                                </div>
                                </div>
                                
                                
                                <div class="tab-pane fade" id="JV">
                                    <div class="row" style="padding:10px;">
                                        <table class="table table-striped" id="tableBank">
                                        <thead>
                                            <tr>
                                                 <th>Invoice No:</th>
                                                <th>Date</th>
                                                <th>Item Desc</th>
                                                <th>Qty</th>
                                                <th>Rate</th>
                                                <th>Income</th>
                                                <th>Cost</th>
                                                <th>Profit</th>
                                            </tr>
                                        </thead>
                                         @if(!empty($reports))
                                            <tbody>
                                            @foreach($reports as $key=> $report)
                                            <?php if($key=='JV') {  ?>
                                            @php $qty_total = $total_income = $total_expense = $total_netincome = 0; @endphp
                                            @foreach($report as $row)
                                            <tr>
                                                <td>{{ $row->voucher_no}}</td>
                                                <td>{{ date('d-m-Y', strtotime($row->voucher_date)) }}</td>
                                                <td>{{ ($row->description=='')?$row->jdesc:$row->description}}</td>
                                                <td>{{ ($row->quantity!=0)?$row->quantity:'' }}</td>
                                                <td>{{($row->unit_price!=0)?number_format($row->unit_price,2):'' }}</td>
                                                <td>{{($row->income!=0)?number_format($row->income,2):''}}</td>
                                                <td>{{ ($row->amount!=0)?number_format($row->amount,2):'' }}</td>
                                                <td>{{ number_format($row->income - $row->amount,2) }}</td>
                                            </tr>
                                    @php
										$qty_total += $row->quantity;
										$net_income = $row->income - $row->amount;
										$total_income += $row->income;
										$total_expense += $row->amount;
										$total_netincome += $net_income;
									@endphp
                                            @endforeach
                                     <tr>
										<th class="text-right" colspan="3">Grand Total</th>
										<th >{{$qty_total}}</th>
										<th ></th>
										<th >{{number_format($total_income,2)}}</th>
										<th >{{number_format($total_expense,2)}}</th>
										<th >{{number_format($total_netincome,2)}}</th>
									</tr>
                                            <?php } ?>
                                            @endforeach
                                           
                                            
                                        </tbody>
                                        	@else
			                                 <tr><td colspan="11" align="center">No reports were found!</td></tr>
		                                    @endif
                                    </table>
                                </div>
                                </div>
                                
                                
                                <div class="tab-pane fade" id="PV">
                                    <div class="row" style="padding:10px;">
                                        <table class="table table-striped" id="tableBank">
                                        <thead>
                                            <tr>
                                                 <th>Invoice No:</th>
                                                <th>Date</th>
                                                <th>Item Desc</th>
                                                <th>Qty</th>
                                                <th>Rate</th>
                                                <!--<th>Income</th>-->
                                                <th>Cost</th>
                                                <th>Profit</th>
                                            </tr>
                                        </thead>
                                         @if(!empty($reports))
                                            <tbody>
                                            @foreach($reports as $key=> $report)
                                            <?php if($key=='PV') {  ?>
                                            @php $qty_total = $total_income = $total_expense = $total_netincome = 0; @endphp
                                            @foreach($report as $row)
                                            <tr>
                                                <td>{{ $row->voucher_no}}</td>
                                                <td>{{ date('d-m-Y', strtotime($row->voucher_date)) }}</td>
                                                <td>{{ ($row->description=='')?$row->jdesc:$row->description}}</td>
                                                <td>{{ ($row->quantity!=0)?$row->quantity:'' }}</td>
                                                <td>{{($row->unit_price!=0)?number_format($row->unit_price,2):'' }}</td>
                                               <!-- <td>{{($row->income!=0)?number_format($row->income,2):''}}</td>-->
                                                <td>{{ ($row->amount!=0)?number_format($row->amount,2):'' }}</td>
                                                <td>{{ number_format($row->income - $row->amount,2) }}</td>
                                            </tr>
                                    @php
										$qty_total += $row->quantity;
										$net_income = $row->income - $row->amount;
										$total_income += $row->income;
										$total_expense += $row->amount;
										$total_netincome += $net_income;
									@endphp
                                            @endforeach
                                     <tr>
										<th class="text-right" colspan="3">Grand Total</th>
										<th >{{$qty_total}}</th>
										<th ></th>
										<!--<th >{{number_format($total_income,2)}}</th>-->
										<th >{{number_format($total_expense,2)}}</th>
										<th >{{number_format($total_netincome,2)}}</th>
									</tr>
                                            <?php } ?>
                                            @endforeach
                                           
                                            
                                        </tbody>
                                        	@else
			                                 <tr><td colspan="11" align="center">No reports were found!</td></tr>
		                                    @endif
                                    </table>
                                </div>
                                </div>
                                
                                
                                <div class="tab-pane fade" id="RV">
                                    <div class="row" style="padding:10px;">
                                        <table class="table table-striped" id="tableBank">
                                        <thead>
                                            <tr>
                                                 <th>Invoice No:</th>
                                                <th>Date</th>
                                                <th>Item Desc</th>
                                                <th>Qty</th>
                                                <th>Rate</th>
                                                <th>Income</th>
                                                <th>Cost</th>
                                                <th>Profit</th>
                                            </tr>
                                        </thead>
                                         @if(!empty($reports))
                                            <tbody>
                                            @foreach($reports as $key=> $report)
                                            <?php if($key=='RV') {  ?>
                                            @php $qty_total = $total_income = $total_expense = $total_netincome = 0; @endphp
                                            @foreach($report as $row)
                                            <tr>
                                                <td>{{ $row->voucher_no}}</td>
                                                <td>{{ date('d-m-Y', strtotime($row->voucher_date)) }}</td>
                                                <td>{{ ($row->description=='')?$row->jdesc:$row->description}}</td>
                                                <td>{{ ($row->quantity!=0)?$row->quantity:'' }}</td>
                                                <td>{{($row->unit_price!=0)?number_format($row->unit_price,2):'' }}</td>
                                                <td>{{($row->income!=0)?number_format($row->income,2):''}}</td>
                                                <td>{{ ($row->amount!=0)?number_format($row->amount,2):'' }}</td>
                                                <td>{{ number_format($row->income - $row->amount,2) }}</td>
                                            </tr>
                                    @php
										$qty_total += $row->quantity;
										$net_income = $row->income - $row->amount;
										$total_income += $row->income;
										$total_expense += $row->amount;
										$total_netincome += $net_income;
									@endphp
                                            @endforeach
                                     <tr>
										<th class="text-right" colspan="3">Grand Total</th>
										<th >{{$qty_total}}</th>
										<th ></th>
										<th >{{number_format($total_income,2)}}</th>
										<th >{{number_format($total_expense,2)}}</th>
										<th >{{number_format($total_netincome,2)}}</th>
									</tr>
                                            <?php } ?>
                                            @endforeach
                                           
                                            
                                        </tbody>
                                        	@else
			                                 <tr><td colspan="11" align="center">No reports were found!</td></tr>
		                                    @endif
                                    </table>
                                </div>
                                </div>
                                
                                <div class="tab-pane fade" id="PC">
                                    <div class="row" style="padding:10px;">
                                        <table class="table table-striped" id="tableBank">
                                        <thead>
                                            <tr>
                                                 <th>Invoice No:</th>
                                                <th>Date</th>
                                                <th>Item Desc</th>
                                                <th>Qty</th>
                                                <th>Rate</th>
                                                <!--<th>Income</th>-->
                                                <th>Cost</th>
                                                <th>Profit</th>
                                            </tr>
                                        </thead>
                                         @if(!empty($reports))
                                            <tbody>
                                            @foreach($reports as $key=> $report)
                                            <?php if($key=='PC') {  ?>
                                            @php $qty_total = $total_income = $total_expense = $total_netincome = 0; @endphp
                                            @foreach($report as $row)
                                            <tr>
                                                <td>{{ $row->voucher_no}}</td>
                                                <td>{{ date('d-m-Y', strtotime($row->voucher_date)) }}</td>
                                                <td>{{ ($row->description=='')?$row->jdesc:$row->description}}</td>
                                                <td>{{ ($row->quantity!=0)?$row->quantity:'' }}</td>
                                                <td>{{($row->unit_price!=0)?number_format($row->unit_price,2):'' }}</td>
                                                <!--<td>{{($row->income!=0)?number_format($row->income,2):''}}</td>-->
                                                <td>{{ ($row->amount!=0)?number_format($row->amount,2):'' }}</td>
                                                <td>{{ number_format($row->income - $row->amount,2) }}</td>
                                            </tr>
                                    @php
										$qty_total += $row->quantity;
										$net_income = $row->income - $row->amount;
										$total_income += $row->income;
										$total_expense += $row->amount;
										$total_netincome += $net_income;
									@endphp
                                            @endforeach
                                     <tr>
										<th class="text-right" colspan="3">Grand Total</th>
										<th >{{$qty_total}}</th>
										<th ></th>
										<!--<th >{{number_format($total_income,2)}}</th>-->
										<th >{{number_format($total_expense,2)}}</th>
										<th >{{number_format($total_netincome,2)}}</th>
									</tr>
                                            <?php } ?>
                                            @endforeach
                                           
                                            
                                        </tbody>
                                        	@else
			                                 <tr><td colspan="11" align="center">No reports were found!</td></tr>
		                                    @endif
                                    </table>
                                </div>
                                </div>
                                
                                <div class="tab-pane fade" id="PS">
                                    <div class="row" style="padding:10px;">
                                        <table class="table table-striped" id="tableBank">
                                        <thead>
                                            <tr>
                                                 <th>Invoice No:</th>
                                                <th>Date</th>
                                                <th>Item Desc</th>
                                                <th>Qty</th>
                                                <th>Rate</th>
                                                <!--<th>Income</th>-->
                                                <th>Cost</th>
                                                <th>Profit</th>
                                            </tr>
                                        </thead>
                                         @if(!empty($reports))
                                            <tbody>
                                            @foreach($reports as $key=> $report)
                                            <?php if($key=='PS') {  ?>
                                            @php $qty_total = $total_income = $total_expense = $total_netincome = 0; @endphp
                                            @foreach($report as $row)
                                            <tr>
                                                <td>{{ $row->voucher_no}}</td>
                                                <td>{{ date('d-m-Y', strtotime($row->voucher_date)) }}</td>
                                                <td>{{ ($row->description=='')?$row->jdesc:$row->description}}</td>
                                                <td>{{ ($row->quantity!=0)?$row->quantity:'' }}</td>
                                                <td>{{($row->unit_price!=0)?number_format($row->unit_price,2):'' }}</td>
                                               <!-- <td>{{($row->income!=0)?number_format($row->income,2):''}}</td>-->
                                                <td>{{ ($row->amount!=0)?number_format($row->amount,2):'' }}</td>
                                                <td>{{ number_format($row->income - $row->amount,2) }}</td>
                                            </tr>
                                    @php
										$qty_total += $row->quantity;
										$net_income = $row->income - $row->amount;
										$total_income += $row->income;
										$total_expense += $row->amount;
										$total_netincome += $net_income;
									@endphp
                                            @endforeach
                                     <tr>
										<th class="text-right" colspan="3">Grand Total</th>
										<th >{{$qty_total}}</th>
										<th ></th>
										<!--<th >{{number_format($total_income,2)}}</th>-->
										<th >{{number_format($total_expense,2)}}</th>
										<th >{{number_format($total_netincome,2)}}</th>
									</tr>
                                            <?php } ?>
                                            @endforeach
                                           
                                            
                                        </tbody>
                                        	@else
			                                 <tr><td colspan="11" align="center">No reports were found!</td></tr>
		                                    @endif
                                    </table>
                                </div>
                                </div>
                                
                                <div class="tab-pane fade" id="SS">
                                    <div class="row" style="padding:10px;">
                                        <table class="table table-striped" id="tableBank">
                                        <thead>
                                            <tr>
                                                 <th>Invoice No:</th>
                                                <th>Date</th>
                                                <th>Item Desc</th>
                                                <th>Qty</th>
                                                <th>Rate</th>
                                                <th>Income</th>
                                                <th>Cost</th>
                                                <th>Profit</th>
                                            </tr>
                                        </thead>
                                         @if(!empty($reports))
                                            <tbody>
                                            @foreach($reports as $key=> $report)
                                            <?php if($key=='SS') {  ?>
                                            @php $qty_total = $total_income = $total_expense = $total_netincome = 0; @endphp
                                            @foreach($report as $row)
                                            <tr>
                                                <td>{{ $row->voucher_no}}</td>
                                                <td>{{ date('d-m-Y', strtotime($row->voucher_date)) }}</td>
                                                <td>{{ ($row->description=='')?$row->jdesc:$row->description}}</td>
                                                <td>{{ ($row->quantity!=0)?$row->quantity:'' }}</td>
                                                <td>{{($row->unit_price!=0)?number_format($row->unit_price,2):'' }}</td>
                                                <td>{{($row->income!=0)?number_format($row->income,2):''}}</td>
                                                <td>{{ ($row->amount!=0)?number_format($row->amount,2):'' }}</td>
                                                <td>{{ number_format($row->income - $row->amount,2) }}</td>
                                            </tr>
                                    @php
										$qty_total += $row->quantity;
										$net_income = $row->income - $row->amount;
										$total_income += $row->income;
										$total_expense += $row->amount;
										$total_netincome += $net_income;
									@endphp
                                            @endforeach
                                     <tr>
										<th class="text-right" colspan="3">Grand Total</th>
										<th >{{$qty_total}}</th>
										<th ></th>
										<th >{{number_format($total_income,2)}}</th>
										<th >{{number_format($total_expense,2)}}</th>
										<th >{{number_format($total_netincome,2)}}</th>
									</tr>
                                            <?php } ?>
                                            @endforeach
                                           
                                            
                                        </tbody>
                                        	@else
			                                 <tr><td colspan="11" align="center">No reports were found!</td></tr>
		                                    @endif
                                    </table>
                                </div>
                                </div>
                                
                                </div>
                            </div>
                        </div>
                    </div>