<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>
        @section('title')
            Profit ACC 365 | ERP Software
        @show
    </title>
    
    
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/app.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
   
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/bootstrap.css')}}">






</style>
<!-- end of global css -->
</head>
<body >


<!-- For horizontal menu -->

<!-- horizontal menu ends -->



        <!-- Main content -->
        <section class="content" >
            <div class="panel">
                
                <div class="panel-body" style="background-color:#d6eef8">
                    <div class="row">
                        <div class="col-md-12">
							<table border="0" style="width:100%;">
								<tr><td width="100%" align="center"><h3>{{Session::get('company')}}</h3></td>
									<td align="left"></td>
								</tr>
								<tr>
									<td width="100%" align="center"><h4><u>{{$voucherhead}}</u></h4>
									</td><td align="left"></td>
								</tr>
							</table><br/>
                        </div>
						<p>Date From: <b><?php echo ($fromdate=='')?date('d-m-Y',strtotime($settings->from_date)):$fromdate;?></b> &nbsp; To: <b><?php echo ($todate=='')?date('d-m-Y'):$todate;?></b></p>
                        <div class="col-md-12" >
                            <?php if($searchtype!='detail'){ ?>
							<table class="table" border="0">
								<thead><tr><th>Account Name</th><th class="emptyrow text-right">Amount</th></thead>
								<?php 
								
								if(count($transaction) > 0) { $total = 0; 
								?>
									@foreach($reports as $key => $report)
									<body>
									@php $dramount=$cramount=0; @endphp
									@foreach($report as $row)
										@if($row->transaction_type=='Dr')
											@php $dramount += $row->amount; @endphp
										@else
											@php $cramount += $row->amount; @endphp
										@endif
										@php $type = $row->transaction_type; @endphp
									@endforeach
									@php $amount = $dramount - $cramount;  @endphp
									@if($amount != 0)  @php $total += $amount; @endphp
									<tr>
										<td>{{$report[0]->master_name}}</td>
										@if($amount < 0)
											@php $arr = explode('-',$amount); @endphp
											<td class="emptyrow text-right">{{'('.number_format($arr[1],2).') '.$type}}</td>
										@else
											<td class="emptyrow text-right">{{number_format($amount,2).' '.$type}}</td>
										@endif
									</tr>
									@endif
									</body>
									@endforeach
									<tr>
										<td><b>Total</b></td>
										<td class="emptyrow text-right"><b>{{number_format($total,2)}}</b></td>
									</tr>
							</table>
						
							<?php } else { ?>
							<br/>
							<div class="alert alert-danger">
								<ul>No records were found!</ul>
							</div> 
							<?php  }} else{ ?>
							
					<div class="col-md-12">
                     <div class="panel panel-default1">
                        <div class="panel-heading" style="background-color:#86C5FF">
                            <h3 class="panel-title" style="padding-bottom:10px; color:#428BCA !important;"><i class="fa fa-fw fa-list-alt"></i><font color="white"> Daily Report Details</font></h3>
                            <ul class="nav nav-tabs nav-float" role="tablist">
                                <li class="active">
                                    <a href="#SI" role="tab" data-toggle="tab">Sales</a>
                                </li>
                                 <li >
                                    <a href="#SR" role="tab" data-toggle="tab">Sales Return</a>
                                </li>
                               
                                <li >
                                    <a href="#PI" role="tab" data-toggle="tab">Purchase</a>
                                </li>
                               <li >
                                    <a href="#PR" role="tab" data-toggle="tab">Purchase Return</a>
                                </li>
                               
                                <li>
                                    <a href="#RV" role="tab" data-toggle="tab">Receipt Voucher</a>
                                </li>
                                <li>
                                    <a href="#PV" role="tab" data-toggle="tab">Payment Voucher</a>
                                </li>
                                
                               
                            </ul>
                        </div>
                        
                        <div class="panel-body" style="background-color:#d6eef8">
                            <div class="tab-content">
                                
                                <div class="tab-pane fade in active" id="SI" style="background-color:#d6f8f4">
                                    <div class="row" style="padding:10px;">
                                    <table class="table table-striped" id="tableBank">
                                        <thead>
                                            <tr>
                                            <td><b>Sales Invoice</b></td>
                                            </tr>
                                            <tr>
                                                <th>Invoice No:</th>
                                                <th>Date</th>
                                                <th>Customer Name</th>
                                                <th>Net Amount</th>
                                            </tr>
                                        </thead>
                                        @if(!empty($sales))
                                            <tbody>
                                            @foreach($sales as $key=> $report)
                                            <?php if($report[0]->type=='SI') {  ?>
                                            @php  $total_income= 0; @endphp
                                            <tr>
                                                <td><b>{{$key}}</b></td>
                                            </tr>
                                            @foreach($report as $row)
                                            <tr>
                                                <td>{{ $row->voucher_no}}</td>
                                                <td>{{ date('d-m-Y', strtotime($row->voucher_date)) }}</td>
                                                
                                                <td>{{ $row->master_name}}</td>
                                                
                                                <td>{{ number_format($row->net_total,2) }}</td>
                                                
                                            </tr>
                                    @php
										
										
										$total_income += $row->net_total;
									
									@endphp
                                            @endforeach
                                    <tr>
										<th class="text-right" colspan="3">Grand Total</th>
										
										<th >{{number_format($total_income,2)}}</th>
										
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
                                    
                                    <div class="tab-pane fade in active" id="SR" style="background-color:#e2d6f8">
                                    <div class="row" style="padding:10px;">
                                    <table class="table table-striped" id="tableBank">
                                        <thead>
                                            <tr>
                                            <td><b>Sales Return</b></td>
                                            </tr>
                                            <tr>
                                                <th>SR No:</th>
                                                <th>Date</th>
                                                <th>Customer Name</th>
                                                <th>Net Amount</th>
                                            </tr>
                                        </thead>
                                        @if(!empty($salesr))
                                            <tbody>
                                            @foreach($salesr as $key=> $report)
                                            <?php if($report[0]->type=='SR') {  ?>
                                            @php  $total_income= 0; @endphp
                                            <tr>
                                                <td><b>{{$key}}</b></td>
                                            </tr>
                                            @foreach($report as $row)
                                            <tr>
                                                <td>{{ $row->voucher_no}}</td>
                                                <td>{{ date('d-m-Y', strtotime($row->voucher_date)) }}</td>
                                                
                                                <td>{{ $row->master_name}}</td>
                                                
                                                <td>{{ number_format($row->net_amount,2) }}</td>
                                                
                                            </tr>
                                    @php
										
										
										$total_income += $row->net_amount;
									
									@endphp
                                            @endforeach
                                    <tr>
										<th class="text-right" colspan="3">Grand Total</th>
										
										<th >{{number_format($total_income,2)}}</th>
										
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
                                    
                                    
                                    
                                   <div class="tab-pane fade in active" id="PI" style="background-color:#f8d6eb">
                                    <div class="row" style="padding:10px;">
                                    <table class="table table-striped" id="tableBank">
                                        <thead>
                                            <tr>
                                            <td><b>Purchase Invoice</b></td>
                                            </tr>
                                            <tr>
                                                <th>Invoice No:</th>
                                                <th>Date</th>
                                                <th>Supplier Name</th>
                                                <th>Net Amount</th>
                                            </tr>
                                        </thead>
                                        @if(!empty($purchase))
                                            <tbody>
                                            @foreach($purchase as $key=> $report)
                                            <?php if($report[0]->type=='PI') {  ?>
                                            @php  $total_income= 0; @endphp
                                            <tr>
                                                <td><b>{{$key}}</b></td>
                                            </tr>
                                            @foreach($report as $row)
                                            <tr>
                                                <td>{{ $row->voucher_no}}</td>
                                                <td>{{ date('d-m-Y', strtotime($row->voucher_date)) }}</td>
                                                
                                                <td>{{ $row->master_name}}</td>
                                                
                                                <td>{{ number_format($row->net_amount,2) }}</td>
                                                
                                            </tr>
                                    @php
										
										
										$total_income += $row->net_amount;
									
									@endphp
                                            @endforeach
                                    <tr>
										<th class="text-right" colspan="3">Grand Total</th>
										
										<th >{{number_format($total_income,2)}}</th>
										
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
                                    
                                    <div class="tab-pane fade in active" id="PR" style="background-color:#f8e4d6">
                                    <div class="row" style="padding:10px;">
                                    <table class="table table-striped" id="tableBank">
                                        <thead>
                                            <tr>
                                            <td><b>Purchase Return</b></td>
                                            </tr>
                                            <tr>
                                                <th>PR No:</th>
                                                <th>Date</th>
                                                <th>Supplier Name</th>
                                                <th>Net Amount</th>
                                            </tr>
                                        </thead>
                                        @if(!empty($purchaser))
                                            <tbody>
                                            @foreach($purchaser as $key=> $report)
                                            <?php if($report[0]->type=='PR') {  ?>
                                            @php  $total_income= 0; @endphp
                                            <tr>
                                                <td><b>{{$key}}</b></td>
                                            </tr>
                                            @foreach($report as $row)
                                            <tr>
                                                <td>{{ $row->voucher_no}}</td>
                                                <td>{{ date('d-m-Y', strtotime($row->voucher_date)) }}</td>
                                                
                                                <td>{{ $row->master_name}}</td>
                                                
                                                <td>{{ number_format($row->net_amount,2) }}</td>
                                                
                                            </tr>
                                    @php
										
										
										$total_income += $row->net_amount;
									
									@endphp
                                            @endforeach
                                    <tr>
										<th class="text-right" colspan="3">Grand Total</th>
										
										<th >{{number_format($total_income,2)}}</th>
										
									</tr>    
                                            
                                            <?php } ?>
                                            @endforeach
                                            
                                    
                                        </tbody>
                                        	@else
			                                 <tr><td colspan="11" align="center">No purchase return reports were found!</td></tr>
		                                    @endif
                                    </table>
                                    </div>
                                    </div>
                                    
                                    
                                    
                                    
                                
                                
                                <div class="tab-pane fade in active" id="RV" style="background-color:#07e0f0">
                                    <div class="row" style="padding:10px;">
                                       <table class="table table-striped" id="tableBank">
                                        <thead><tr>
                                            <td><b>Receipt Voucher</b></td>
                                            </tr>
                                            <tr>
                                                <th>RV No:</th>
                                                <th>Date</th>
                                                <th>Account Name</th>
                                                <th>Net Amount</th>
                                            </tr>
                                        </thead>
                                        @if(!empty($rv))
                                            <tbody>
                                            @foreach($rv as $key=> $report)
                                            <?php if($report[0]->type=='RV') {  ?>
                                            @php  $total_income= 0; @endphp
                                            <tr>
                                                
                                                <td><b>{{$key}}</b></td>
                                            </tr>
                                            @foreach($report as $row)
                                            <tr>
                                                <td>{{ $row->voucher_no}}</td>
                                                <td>{{ date('d-m-Y', strtotime($row->voucher_date)) }}</td>
                                                
                                                <td>{{ $row->master_name}}</td>
                                                
                                                <td>{{ number_format($row->amount,2) }}</td>
                                                
                                            </tr>
                                    @php
										
										
										$total_income += $row->amount;
									
									@endphp
                                            @endforeach
                                    <tr>
										<th class="text-right" colspan="3">Grand Total</th>
										
										<th >{{number_format($total_income,2)}}</th>
										
									</tr>    
                                            
                                            <?php } ?>
                                            @endforeach
                                            
                                    
                                        </tbody>
                                        	@else
			                                 <tr><td colspan="11" align="center">No purchase return reports were found!</td></tr>
		                                    @endif
                                    </table>
                                </div>
                                </div>
                                
                                 <div class="tab-pane fade in active" id="PV" style="background-color:#e407f0">
                                    <div class="row" style="padding:10px;">
                                       <table class="table table-striped" id="tableBank">
                                        <thead><tr>
                                            <td><b>Payment Voucher</b></td>
                                            </tr>
                                            <tr>
                                                <th>PV No:</th>
                                                <th>Date</th>
                                                <th>Account Name</th>
                                                <th>Net Amount</th>
                                            </tr>
                                        </thead>
                                        @if(!empty($pv))
                                            <tbody>
                                            @foreach($pv as $key=> $report)
                                            <?php if($report[0]->type=='PV') {  ?>
                                            @php  $total_income= 0; @endphp
                                            <tr>
                                                
                                                <td><b>{{$key}}</b></td>
                                            </tr>
                                            @foreach($report as $row)
                                            <tr>
                                                <td>{{ $row->voucher_no}}</td>
                                                <td>{{ date('d-m-Y', strtotime($row->voucher_date)) }}</td>
                                                
                                                <td>{{ $row->master_name}}</td>
                                                
                                                <td>{{ number_format($row->amount,2) }}</td>
                                                
                                            </tr>
                                    @php
										
										
										$total_income += $row->amount;
									
									@endphp
                                            @endforeach
                                    <tr>
										<th class="text-right" colspan="3">Grand Total</th>
										
										<th >{{number_format($total_income,2)}}</th>
										
									</tr>    
                                            
                                            <?php } ?>
                                            @endforeach
                                            
                                    
                                        </tbody>
                                        	@else
			                                 <tr><td colspan="11" align="center">No purchase return reports were found!</td></tr>
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
                    
                    </div>
                        </div>
                    </div>
                    <?php  } ?>
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
					<form class="form-horizontal" role="form" method="POST" name="frmExport" id="frmExport" action="{{ url('daily_report/export') }}">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="hidden" name="date_from" value="{{$fromdate}}" >
					<input type="hidden" name="date_to" value="{{$todate}}" >
					</form>
                </div>
            </div>
        </section>



<script>
function getExport() {
	document.frmExport.submit();
}
</script>

