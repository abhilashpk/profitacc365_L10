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
            <h1>
                Profit Analysis
            </h1>
            <ol class="breadcrumb">
                <li>
                      <i class="fa fa-fw fa-shield"></i> Reports
                </li>
				<li>
                    <a href="#">Profit Analysis</a>
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
								<tr><td width="60%" align="left"><h3>{{Session::get('company')}}</h3></td>
									<td align="left"></td>
								</tr>
								<tr>
									<td width="60%" align="right"><h4><u>{{$voucherhead}}</u></h4>
									</td><td align="left"></td>
								</tr>
							</table><br/>
                        </div>
						<!--<p>Date From: <b><?php //echo ($fromdate=='')?'01-01-2018':$fromdate;?></b> &nbsp; To: <b><?php //echo ($todate=='')?date('d-m-Y'):$todate;?></b></p>-->
                        <div class="col-md-12">
							
							<?php if($reports!=null) { ?>
								<div class="table-responsive m-t-10">
								<?php if($type=="summary") { ?>
									<table class="table horizontal_table table-striped" id="tableAcmaster" >
										<thead>
											<tr>
												<th>Inv.No</th>
												<th>Inv.Date</th>
												<th>Customer</th>
												<th>Total Sale</th>
												<th>Discount</th>
												<th>Net Sale</th>
												<th>Cost</th>
												<th>Profit</th>
												<th>Pft.%</th>
											</tr>
										</thead>
										<tbody>
											@foreach($reports as $report)
											<tr>
												<td>{{ $report['voucher_no'] }}</td>
												<td>{{ date('d-m-Y', strtotime($report['voucher_date'])) }}</td>
												<td>{{ $report['supplier'] }}</td>
												<td>{{ number_format($report['sprice'],2) }}</td>
												<td>{{ number_format($report['discount'],2) }}</td>
												<td>{{ number_format($report['sprice'], 2) }}</td>
												<td>{{ number_format($report['cost'],2) }}</td>
												<td>{{ number_format($report['profit'], 2) }}</td>
												<td>{{number_format($report['percentage'],2)}}</td>
											</tr>
											@endforeach
										</tbody>
									</table>
								<?php } else if($type=="detail") { ?>
								
									<table class="table" border="0">
									<?php foreach($reports as $report) { ?>
										<thead>
											<th style="width:37%;" colspan="3">Inv.#: {{$report[0]['voucher_no']}}</th>
											<th style="width:40%;" colspan="4">Cust.Name: {{$report[0]['supplier']}}</th>
											<th style="width:23%;" colspan="2">Date: {{date('d-m-Y',strtotime($report[0]['voucher_date']))}}</th>
										</thead>
										
										<thead>
											<th style="width:5%;">SI.#</th>
											<th style="width:12%;">Item Code</th>
											<th style="width:20%;">Description</th>
											<th style="width:7%;">Qty.</th>
											<th style="width:10%;" class="text-right">Sale Price</th>
											<th style="width:5%;" class="text-right">Discount</th>
											<th style="width:18%;" class="text-right">Cost</th>
											<th style="width:16%;" class="text-right">Profit</th>
											<th style="width:7%;" class="text-right">Profit %</th>
										</thead>
										<tbody>
											<?php 
												$sptotal = $dtotal = $ctotal = $ptotal = $pertotal = $peravg = 0; $i=1;
												foreach($report as $row) { 
													$sprice = $row['squantity'] * $row['sunit_price'];
													if($row['class_id']==1)
														$cost = $row['sale_cost'];
													else
														$cost = 0;
													$pprice = $row['squantity'] * $cost;
													$profit = $sprice - $pprice - $row['discount'];
													$percentage = ($profit > 0 && $sprice > 0)?($profit / $sprice * 100):0;
													$sptotal += $sprice;
													$dtotal += $row['discount'];
													$ctotal += $cost;
													$ptotal += $profit;
													$pertotal += $percentage; $n = $i;
											?>
														<tr>
															<td style="width:5%;">{{$i++}}</td>
															<td style="width:12%;">{{$row['item_code']}}</td>
															<td style="width:20%;">{{$row['description']}}</td>
															<td style="width:7%;">{{$row['squantity']}}</td>
															<td style="width:10%;" class="text-right">{{number_format($row['sunit_price'],2)}}</td>
															<td style="width:5%;" class="text-right">{{number_format($row['discount'],2)}}</td>
															<td style="width:18%;" class="text-right">{{number_format($cost,2)}}</td>
															<td style="width:16%;" class="text-right">{{number_format($profit,2)}}</td>
															<td style="width:7%;" class="text-right">{{number_format($percentage,2)}}</td>
														</tr>
												<?php } $peravg = $pertotal / $n; ?>
											<tr>
												<td colspan="4" align="right"><b>Sub Total:</b></td>
												<td style="width:10%;" class="text-right"><b>{{number_format($sptotal,2)}}</b></td>
												<td style="width:5%;" class="text-right"><b>{{number_format($dtotal,2)}}</b></td>
												<td style="width:18%;" class="text-right"><b>{{number_format($ctotal,2)}}</b></td>
												<td style="width:16%;" class="text-right"><b>{{number_format($ptotal,2)}}</b></td>
												<td style="width:7%;" class="text-right"><b>{{number_format($peravg,2)}}</b></td>
											</tr>
											
											<tr>
												<td colspan="9" align="right"><br/></td>
											</tr>
										</tbody>
									<?php } ?>
									</table>
								<?php } else if($type=="customer") { ?>
									<table class="table" border="0">
									<?php foreach($reports as $report) { ?>
										<thead>
											<th style="width:37%;" colspan="3">Cust.#: {{$report[0]['account_id']}}</th>
											<th style="width:40%;" colspan="3">Cust.Name: {{$report[0]['supplier']}}</th>
											<th style="width:23%;" colspan="1"></th>
										</thead>
										
										<thead>
											<th style="width:12%;">Inv.#</th>
											<th style="width:14%;">Inv. Date</th>
											<th style="width:16%;" class="text-right">Sale Price</th>
											<th style="width:10%;" class="text-right">Discount</th>
											<th style="width:18%;" class="text-right">Cost</th>
											<th style="width:17%;" class="text-right">Profit</th>
											<th style="width:8%;" class="text-right">Profit %</th>
										</thead>
										<tbody>
											<?php 
												$sptotal = $dtotal = $ctotal = $ptotal = $pertotal = $peravg = 0; $i=1;
												foreach($report as $row) { 
												
												$sptotal += $row['sprice'];
												$dtotal += $row['discount'];
												$ctotal += $row['cost'];
												$ptotal += $row['profit'];
												$pertotal += $row['percentage'];
												$n = $i; $i++;
											?>
												<tr>
													<td style="width:12%;">{{$row['voucher_no']}}</td>
													<td style="width:14%;">{{date('d-m-Y',strtotime($row['voucher_date']))}}</td>
													<td style="width:16%;" class="text-right">{{number_format($row['sprice'],2)}}</td>
													<td style="width:10%;" class="text-right">{{number_format($row['discount'],2)}}</td>
													<td style="width:18%;" class="text-right">{{number_format($row['cost'],2)}}</td>
													<td style="width:17%;" class="text-right">{{number_format($row['profit'],2)}}</td>
													<td style="width:8%;" class="text-right">{{number_format($row['percentage'],2)}}</td>
												</tr>
												<?php } $peravg = $pertotal / $n; ?>
											<tr>
												<td colspan="2" align="right"><b>Sub Total:</b></td>
												<td style="width:10%;" class="text-right"><b>{{number_format($sptotal,2)}}</b></td>
												<td style="width:5%;" class="text-right"><b>{{number_format($dtotal,2)}}</b></td>
												<td style="width:18%;" class="text-right"><b>{{number_format($ctotal,2)}}</b></td>
												<td style="width:16%;" class="text-right"><b>{{number_format($ptotal,2)}}</b></td>
												<td style="width:7%;" class="text-right"><b>{{number_format($peravg,2)}}</b></td>
											</tr>
											
											<tr>
												<td colspan="7" align="right"><br/></td>
											</tr>
										</tbody>
									<?php } ?>
									</table>
								<?php } else if($type=="item") { ?>
								
									<table class="table" border="0">
									<?php foreach($reports as $report) { ?>
										<thead>
											<th style="width:37%;" colspan="3">Item Code: {{$report[0]['item_code']}}</th>
											<th style="width:40%;" colspan="4">Item Name: {{$report[0]['description']}}</th>
											<th style="width:23%;" colspan="2"></th>
										</thead>
										
										<thead>
											<th style="width:10%;">Inv.#</th>
											<th style="width:10%;">Inv.Date</th>
											<th style="width:17%;">Cust.Name</th>
											<th style="width:7%;">Qty.</th>
											<th style="width:10%;" class="text-right">Sale Price</th>
											<th style="width:5%;" class="text-right">Discount</th>
											<th style="width:18%;" class="text-right">Cost</th>
											<th style="width:16%;" class="text-right">Profit</th>
											<th style="width:7%;" class="text-right">Profit %</th>
										</thead>
										<tbody>
											<?php 
												$sptotal = $dtotal = $ctotal = $ptotal = $pertotal = $peravg = 0; $i=1;
												foreach($report as $row) { 
												$sprice = $row['squantity'] * $row['sunit_price'];
												if($row['class_id']==1)
													$cost = $row['sale_cost'];
												else
													$cost = 0;
												$pprice = $row['squantity'] * $cost;
												$profit = $sprice - $pprice - $row['discount'];
												$percentage = ($profit > 0 && $sprice > 0)?($profit / $sprice * 100):0;
												$sptotal += $sprice;
												$dtotal += $row['discount'];
												$ctotal += $cost;
												$ptotal += $profit;
												$pertotal += $percentage; $n = $i;$i++;
											?>
														<tr>
															<td style="width:10%;">{{$row['voucher_no']}}</td>
															<td style="width:10%;">{{date('d-m-Y',strtotime($row['voucher_date']))}}</td>
															<td style="width:17%;">{{$row['supplier']}}</td>
															<td style="width:7%;">{{$row['squantity']}}</td>
															<td style="width:10%;" class="text-right">{{number_format($row['sunit_price'],2)}}</td>
															<td style="width:5%;" class="text-right">{{number_format($row['discount'],2)}}</td>
															<td style="width:18%;" class="text-right">{{number_format($cost,2)}}</td>
															<td style="width:16%;" class="text-right">{{number_format($profit,2)}}</td>
															<td style="width:7%;" class="text-right">{{number_format($percentage,2)}}</td>
														</tr>
												<?php } $peravg = $pertotal / $n; ?>
											<tr>
												<td colspan="4" align="right"><b>Sub Total:</b></td>
												<td style="width:10%;" class="text-right"><b>{{number_format($sptotal,2)}}</b></td>
												<td style="width:5%;" class="text-right"><b>{{number_format($dtotal,2)}}</b></td>
												<td style="width:18%;" class="text-right"><b>{{number_format($ctotal,2)}}</b></td>
												<td style="width:16%;" class="text-right"><b>{{number_format($ptotal,2)}}</b></td>
												<td style="width:7%;" class="text-right"><b>{{number_format($peravg,2)}}</b></td>
											</tr>
											
											<tr>
												<td colspan="9" align="right"><br/></td>
											</tr>
										</tbody>
									<?php } ?>
									</table>
								<?php } ?>
									<!--<button type="button" class="btn btn-primary outstanding" onclick="printInvoice()">Print Invoice</button>-->
								</div>
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
								
								<button type="button" onclick="javascript:window.history.back();"
                                                     class="btn btn-responsive button-alignment btn-primary"
                                                     data-toggle="button">
                                                <span style="color:#fff;">
                                                    <i class="fa fa-fw fa-times"></i>
                                                Back 
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
