@extends('layouts/default')

    {{-- Page title --}}
    @section('title')
         
        @parent
    @stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
	<link href="{{asset('assets/vendors/bootstrap-multiselect/css/bootstrap-multiselect.css')}}" rel="stylesheet" type="text/css">
	
	<link rel="stylesheet" href="{{asset('assets/vendors/datetime/css/jquery.datetimepicker.css')}}">
    <link href="{{asset('assets/vendors/airdatepicker/css/datepicker.min.css')}}" rel="stylesheet" type="text/css">
	
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/buttons.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/colReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/dataTables.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/rowReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/scroller.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatablesmark.js/css/datatables.mark.min.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/responsive_datatables.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/datepicker.css')}}">

	<link href="{{asset('assets/vendors/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('assets/vendors/select2/css/select2-bootstrap.css')}}" rel="stylesheet" type="text/css">
        <!--end of page level css-->
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <!--section starts-->
            <h1>
                Profit Analysis
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="glyphicon glyphicon-folder-close"></i> Reports
                    </a>
                </li>
                <li>
                    <a href="#">Profit Analysis</a>
                </li>
            </ol>
			
        </section>
		
        <!--section ends-->
		@if(Session::has('message'))
		<div class="alert alert-success">
			<p>{{ Session::get('message') }}</p>
		</div>
		@endif
		
        <section class="content">
            <div class="row">
            <div class="col-lg-12">
               <div class="panel panel-info">
                        <div class="panel-heading clearfix">
                            <h3 class="panel-title pull-left m-t-6">
                                <i class="fa fa-fw fa-columns"></i> Profit Analysis
                            </h3>
                        </div>
                        <div class="panel-body">
							<form class="form-horizontal" role="form" target="_blank" method="POST" name="frmProfitAnalysis" id="frmProfitAnalysis" action="{{ url('profit_analysis/search') }}">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								<div class="row">
									<div class="col-xs-6">
										<span>Date From:</span>
										<input type="text" name="date_from" data-language='en' id="date_from" autocomplete="off" value="<?php echo $fromdate; ?>" class="form-control">
										<span>Date To:</span>
										<input type="text" name="date_to" data-language='en' id="date_to" autocomplete="off" value="<?php echo $todate; ?>" class="form-control">
										
										@if($isdept)
										<span>Department:</span>
										<select id="department_id" class="form-control select2" style="width:100%" name="department_id">
											<option value="">Select Department...</option>
											@foreach($departments as $dept)
											<option value="{{$dept->id}}">{{$dept->name}}</option>
											@endforeach
										</select>
										@endif
									</div>
									<div class="col-xs-6">
										<span>Search By:</span>
										<select id="search_type" class="form-control select2" style="width:100%" name="search_type">
											<option value="summary" <?php if($type=='summary') echo 'selected';?>>Summary - Invoicewise </option>
											<option value="detail" <?php if($type=='detail') echo 'selected';?>>Detail - Invoicewise</option>
											<!-- <option value="levelwise" <?//php if($type=='levelwise') echo 'selected';?>>POS Levelwise</option>
											<option value="pos_itemwise" <?//phpif($type=='pos_itemwise') echo 'selected';?>>POS Itemwise</option>
											<option value="customer" <?php //if($type=='customer') echo 'selected';?>>Customerwise</option>
											<option value="item" <?php //if($type=='item') echo 'selected';?>>Itemwise</option>
											<option value="summarysalesman" <?php //if($type=='summarysalesman') echo 'selected';?>>Salesmanwise(Summary)</option>
											<option value="salesman" >Salesmanwise(Detail)</option>
											<option value="group" <?php //if($type=='group') echo 'selected';?>>Groupwise</option>
											<option value="invoice" <?php //if($type=='invoice') echo 'selected';?>>Invoice Numberwise</option> -->
										</select>
										<div class="col-xs-4" style="border:0px solid red;">
										<span>Customers:</span> <br/>
                                        <select id="select1" multiple style="width:100%" class="form-control select2" name="customer_id[]">
                                        @foreach($customer as $row)
										   <option value="{{$row->id}}">{{$row->master_name}}</option>
										@endforeach	 										
                                     </select>
									 </div>
									 <div class="col-xs-4" style="border:0px solid red;">
										<span>Salesman</span>
										<select id="select2" multiple style="width:100%" class="form-control select2" name="salesman_id[]">
                                        @foreach($salesman as $row)
										   <option value="{{$row->id}}">{{$row->name}}</option>
										@endforeach	 										
                                     </select>
									
										</div>
										<br/>
										<div class="col-xs-4" id="item" style="border:0px solid red;">
												<span>Item:</span><br/>
											<select id="select7" multiple="multiple" class="form-control select2" name="item_id[]">
												<?php foreach($item as $row) { ?>
												<option value="<?php echo $row->id;?>"><?php echo $row->description;?></option>
												<?php } ?>
											</select>
										</div> 
										<div class="col-xs-4" id="group" style="border:0px solid red;">
											<span>Group:</span><br/>
											<select id="select3" multiple="multiple" class="form-control select2" name="group_id[]">
												<?php foreach($group as $row) { ?>
												<option value="<?php echo $row->id;?>"><?php echo $row->group_name;?></option>
												<?php } ?>
											</select>
										</div>
										
										<div class="col-xs-3" id="subgroup" style="border:0px solid red;">
											<span>Subgroup:</span><br/>
											<select id="select4" multiple="multiple" class="form-control select2" name="subgroup_id[]">
												<?php foreach($subgroup as $row) { ?>
												<option value="<?php echo $row->id;?>"><?php echo $row->group_name;?></option>
												<?php } ?>
											</select>
										</div>
										
								
										<div class="col-xs-4" id="category" style="border:0px solid red;">
											<span>Category:</span><br/>
											<select id="select5" multiple="multiple" class="form-control select2" name="category_id[]">
												<?php foreach($category as $row) { ?>
												<option value="<?php echo $row->id;?>"><?php echo $row->category_name;?></option>
												<?php } ?>
											</select>
										</div>
										
										<div class="col-xs-4" id="subcategory" style="border:0px solid red;">
											<span>Subcategory:</span><br/>
											<select id="select6" multiple="multiple" class="form-control select2" name="subcategory_id[]">
												<?php foreach($subcategory as $row) { ?>
												<option value="<?php echo $row->id;?>"><?php echo $row->category_name;?></option>
												<?php } ?>
											</select>
										</div>
																			
										<div class="col-xs-10" style="border:0px solid red;" id="selcust">
										</div>
										
										<div class="col-xs-10" style="border:0px solid red;" id="selitm">
										</div>
										<div class="col-xs-10" style="border:0px solid red;" id="selsale">
										</div>
										<div class="col-xs-10" style="border:0px solid red;" id="selsalesummary">
										</div>
										<div class="col-xs-10" style="border:0px solid red;" id="selarea">
										</div>
										<div class="col-xs-10" style="border:0px solid red;" id="selgroup">
										</div>
										<div class="col-xs-10" style="border:0px solid red;" id="selsubgroup">
										</div><br>
										<div class="col-xs-10" style="border:0px solid red;" id="invoice"></div>
							
											<div class="col-xs-12" align="right"> <button type="submit" class="btn btn-primary">Search</button></div>
																
									</div>
								</div>
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
										<?php $i=0;$sptotal = $dtotal = $ctotal = $ptotal = $pertotal = $peravg = 0;?>
											@foreach($reports as $report)
											<?php $i++; 
												$sprice = $report['squantity'] * $report['sunit_price'];
												//$cost = $report['pquantity'] * $report['punit_price'];
												$cost = $report['squantity'] * $report['punit_price'];
												$profit = $sprice - $cost - $report['discount'];
												$percentage = $profit / $sprice * 100;
												$sptotal += $sprice;
												$dtotal += $report['discount'];
												$ctotal += $cost;
												$ptotal += $profit;
											?>
											<tr>
												<td>{{ $report['voucher_no'] }}</td>
												<td>{{ date('d-m-Y', strtotime($report['voucher_date'])) }}</td>
												<td>{{ $report['supplier'] }}</td>
												<td>{{ number_format($sprice,2) }}</td>
												<td>{{ number_format($report['discount'],2) }}</td>
												<td>{{ number_format($sprice, 2) }}</td>
												<td>{{ number_format($cost,2) }}</td>
												<td>{{ number_format($profit, 2) }}</td>
												<td>{{number_format($percentage,2)}}</td>
											</tr>
											@endforeach
										</tbody>
									</table>
								<?php } 
								else if($type=="detail") { ?>
								
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
												$cost = $row['squantity'] * $row['punit_price'];
												$profit = $sprice - $cost - $row['discount'];
												$percentage = $profit / $sprice * 100;
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
															<td style="width:18%;" class="text-right">{{number_format($row['punit_price'],2)}}</td>
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
											<th style="width:40%;" colspan="4">Cust.Name: {{$report[0]['supplier']}}</th>
											<th style="width:23%;" colspan="2"></th>
										</thead>
										
										<thead>
											<th style="width:10%;">Inv.#</th>
											<th style="width:12%;">Inv. Date</th>
											<th style="width:15%;">Item Code</th>
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
												$cost = $row['squantity'] * $row['punit_price'];
												$profit = $sprice - $cost - $row['discount'];
												$percentage = $profit / $sprice * 100;
												$sptotal += $sprice;
												$dtotal += $row['discount'];
												$ctotal += $cost;
												$ptotal += $profit;
												$pertotal += $percentage; $n = $i; $i++;
											?>
														<tr>
															<td style="width:10%;">{{$row['voucher_no']}}</td>
															<td style="width:12%;">{{date('d-m-Y',strtotime($row['voucher_date']))}}</td>
															<td style="width:15%;">{{$row['item_code']}}</td>
															<td style="width:7%;">{{$row['squantity']}}</td>
															<td style="width:10%;" class="text-right">{{number_format($row['sunit_price'],2)}}</td>
															<td style="width:5%;" class="text-right">{{number_format($row['discount'],2)}}</td>
															<td style="width:18%;" class="text-right">{{number_format($row['punit_price'],2)}}</td>
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
									<?php } else if($type=="area") { ?>
									<table class="table" border="0">
									<?php foreach($reports as $report) { ?>
										<thead>
											<th style="width:37%;" colspan="3">Cust.#: {{$report[0]['account_id']}}</th>
											<th style="width:40%;" colspan="4">Cust.Name: {{$report[0]['supplier']}}</th>
											<th style="width:23%;" colspan="2"></th>
										</thead>
										
										<thead>
											<th style="width:10%;">Inv.#</th>
											<th style="width:12%;">Inv. Date</th>
											<th style="width:15%;">Item Code</th>
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
												$cost = $row['squantity'] * $row['punit_price'];
												$profit = $sprice - $cost - $row['discount'];
												$percentage = $profit / $sprice * 100;
												$sptotal += $sprice;
												$dtotal += $row['discount'];
												$ctotal += $cost;
												$ptotal += $profit;
												$pertotal += $percentage; $n = $i; $i++;
											?>
														<tr>
															<td style="width:10%;">{{$row['voucher_no']}}</td>
															<td style="width:12%;">{{date('d-m-Y',strtotime($row['voucher_date']))}}</td>
															<td style="width:15%;">{{$row['item_code']}}</td>
															<td style="width:7%;">{{$row['squantity']}}</td>
															<td style="width:10%;" class="text-right">{{number_format($row['sunit_price'],2)}}</td>
															<td style="width:5%;" class="text-right">{{number_format($row['discount'],2)}}</td>
															<td style="width:18%;" class="text-right">{{number_format($row['punit_price'],2)}}</td>
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
												$cost = $row['squantity'] * $row['punit_price'];
												$profit = $sprice - $cost - $row['discount'];
												$percentage = $profit / $sprice * 100;
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
															<td style="width:18%;" class="text-right">{{number_format($row['punit_price'],2)}}</td>
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
								<?php } else if($type=="group") { ?>
								
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
												$cost = $row['squantity'] * $row['punit_price'];
												$profit = $sprice - $cost - $row['discount'];
												$percentage = $profit / $sprice * 100;
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
															<td style="width:18%;" class="text-right">{{number_format($row['punit_price'],2)}}</td>
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
									<?php } else if($type=="invoice") { ?>
								
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
												$cost = $row['squantity'] * $row['punit_price'];
												$profit = $sprice - $cost - $row['discount'];
												$percentage = $profit / $sprice * 100;
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
															<td style="width:18%;" class="text-right">{{number_format($row['punit_price'],2)}}</td>
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
									<?php } else if($type=="subgroup") { ?>
								
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
												$cost = $row['squantity'] * $row['punit_price'];
												$profit = $sprice - $cost - $row['discount'];
												$percentage = $profit / $sprice * 100;
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
															<td style="width:18%;" class="text-right">{{number_format($row['punit_price'],2)}}</td>
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
								<?php } else if($type=="salesman") { ?>
									<table class="table" border="0">
									<?php foreach($reports as $report) { ?>
										<thead>
											<th style="width:37%;" colspan="3">Cust.#: {{$report[0]['account_id']}}</th>
											<th style="width:40%;" colspan="4">Cust.Name: {{$report[0]['supplier']}}</th>
											<th style="width:23%;" colspan="2"></th>
										</thead>
										
										<thead>
											<th style="width:10%;">Inv.#</th>
											<th style="width:12%;">Inv. Date</th>
											<th style="width:15%;">Item Code</th>
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
												$cost = $row['squantity'] * $row['punit_price'];
												$profit = $sprice - $cost - $row['discount'];
												$percentage = $profit / $sprice * 100;
												$sptotal += $sprice;
												$dtotal += $row['discount'];
												$ctotal += $cost;
												$ptotal += $profit;
												$pertotal += $percentage; $n = $i; $i++;
											?>
														<tr>
															<td style="width:10%;">{{$row['voucher_no']}}</td>
															<td style="width:12%;">{{date('d-m-Y',strtotime($row['voucher_date']))}}</td>
															<td style="width:15%;">{{$row['item_code']}}</td>
															<td style="width:7%;">{{$row['squantity']}}</td>
															<td style="width:10%;" class="text-right">{{number_format($row['sunit_price'],2)}}</td>
															<td style="width:5%;" class="text-right">{{number_format($row['discount'],2)}}</td>
															<td style="width:18%;" class="text-right">{{number_format($row['punit_price'],2)}}</td>
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
									<?php } else if($type=="summarysalesman") { ?>
									<table class="table" border="0">
									<?php foreach($reports as $report) { ?>
										<thead>
											<th style="width:37%;" colspan="3">Cust.#: {{$report[0]['account_id']}}</th>
											<th style="width:40%;" colspan="4">Cust.Name: {{$report[0]['supplier']}}</th>
											<th style="width:23%;" colspan="2"></th>
										</thead>
										
										<thead>
											<th style="width:10%;">Inv.#</th>
											<th style="width:12%;">Inv. Date</th>
											<th style="width:15%;">Item Code</th>
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
												$cost = $row['squantity'] * $row['punit_price'];
												$profit = $sprice - $cost - $row['discount'];
												$percentage = $profit / $sprice * 100;
												$sptotal += $sprice;
												$dtotal += $row['discount'];
												$ctotal += $cost;
												$ptotal += $profit;
												$pertotal += $percentage; $n = $i; $i++;
											?>
														<tr>
															<td style="width:10%;">{{$row['voucher_no']}}</td>
															<td style="width:12%;">{{date('d-m-Y',strtotime($row['voucher_date']))}}</td>
															<td style="width:15%;">{{$row['item_code']}}</td>
															<td style="width:7%;">{{$row['squantity']}}</td>
															<td style="width:10%;" class="text-right">{{number_format($row['sunit_price'],2)}}</td>
															<td style="width:5%;" class="text-right">{{number_format($row['discount'],2)}}</td>
															<td style="width:18%;" class="text-right">{{number_format($row['punit_price'],2)}}</td>
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
							</form>
							
                        </div>
                    </div>
            </div>
        </div>
       

        </section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <!-- begining of page level js -->
<script src="{{asset('assets/vendors/bootstrap-multiselect/js/bootstrap-multiselect.js')}}" type="text/javascript"></script>

<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/jquery.dataTables.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/dataTables.buttons.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/dataTables.colReorder.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/dataTables.responsive.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/dataTables.rowReorder.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/buttons.colVis.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/buttons.html5.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/buttons.bootstrap.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/buttons.print.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/dataTables.scroller.js')}}"></script>

<script src="{{asset('assets/vendors/mark.js/jquery.mark.js')}}" charset="UTF-8"></script>
<script src="{{asset('assets/vendors/datatablesmark.js/js/datatables.mark.min.js')}}" charset="UTF-8"></script>
<script src="{{asset('assets/js/custom_js/responsive_datatables.js')}}" type="text/javascript"></script>

<script src="{{asset('assets/vendors/datetime/js/jquery.datetimepicker.full.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.en.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/custom_js/advanceddate_pickers.js')}}"></script>

<script src="{{asset('assets/vendors/select2/js/select2.js')}}" type="text/javascript"></script>

<!-- end of page level js -->

<script>

$(document).ready(function () {
/*	$("#search_type").change(function () {
		var value = $(this).val();*/ 
		var toAppend = '';
		//	if (value == "detail") {
				toAppend = "(Invoice No:)From:<input type='text' name='invoice_from' data-language='en' id='invoice_from' autocomplete='off'>&nbsp;To :<input type='textbox' name='invoice_to' data-language='en' id='invoice_to' autocomplete='off' >"; $("#invoice").html(toAppend); return;
			//	}
			//	else{
			//	return;
			//	}
		//	});
	});

$('#date_from').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy' } );
$('#date_to').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy' } );

"use strict";
$(document).ready(function () {
	$('#selcust').toggle();
	$('#selitm').toggle();
	$('#selsale').toggle();
	$('#selarea').toggle();
	$('#selgroup').toggle();
	$('#selsubgroup').toggle();
	

   /*  $("#multiselect2").multiselect({
        enableFiltering: true,
        includeSelectAllOption: true,
        maxHeight: 300,
        dropUp: true
    }); */
	
	/* $("#multiselect3").multiselect({
        enableFiltering: true,
        includeSelectAllOption: true,
        maxHeight: 300,
        dropUp: true
    }); */
	
	$(document).on('change', '#search_type', function(e) { //$('#search_type').on('change', function(e){
		var vchr = e.target.value; 
		if(vchr=='customer') {
			
			if( $("#selcust").is(":hidden") )
				$("#selcust").toggle();
			
			if( $("#selitm").is(":visible") )
				$("#selitm").toggle();
			/* $.get("{{ url('profit_analysis/getcustomer/') }}", function(data) {
				//$('#multiselect2').find('option').remove().end();
				$.each(data, function(key, value) {   
				$('#multiselect2').append($("<option></option>")
							.attr("value",key)
							.text(value)); 
				});
			}); */
			
			$.ajax({
				url: "{{ url('profit_analysis/getcustomer/') }}",
				//type: 'get',
				//data: 'item_id='+item_id+'&unit_id='+unit_id,
				success: function(data) {
					$('#selcust').html(data);
					return true;
				}
			}); 
		}else if(vchr=='salesman') {
			
			if( $("#selsale").is(":hidden") )
				$("#selsale").toggle();
			
			if( $("#selitm").is(":visible") )
				$("#selitm").toggle();
			/* $.get("{{ url('profit_analysis/getcustomer/') }}", function(data) {
				//$('#multiselect2').find('option').remove().end();
				$.each(data, function(key, value) {   
				$('#multiselect2').append($("<option></option>")
							.attr("value",key)
							.text(value)); 
				});
			}); */
			
			$.ajax({
				url: "{{ url('profit_analysis/getsalesman/') }}",
				//type: 'get',
				//data: 'item_id='+item_id+'&unit_id='+unit_id,
				success: function(data) {
					$('#selsale').html(data);
					return true;
				}
			}); 
		}else if(vchr=='summarysalesman') {
			
			if( $("#selsalesummary").is(":visible") )
				$("#selsalesummary").toggle();
			
			if( $("#selitm").is(":hidden") )
				$("#selitm").toggle();
			/* $.get("{{ url('profit_analysis/getcustomer/') }}", function(data) {
				//$('#multiselect2').find('option').remove().end();
				$.each(data, function(key, value) {   
				$('#multiselect2').append($("<option></option>")
							.attr("value",key)
							.text(value)); 
				});
			}); */
			
			$.ajax({
				url: "{{ url('profit_analysis/getsalesman/') }}",
				//type: 'get',
				//data: 'item_id='+item_id+'&unit_id='+unit_id,
				success: function(data) {
					$('#selsalesummary').html(data);
					return true;
				}
			}); 
		}else if(vchr=='area') {
			
			if( $("#selarea").is(":hidden") )
				$("#selarea").toggle();
			
			if( $("#selitm").is(":visible") )
				$("#selitm").toggle();
			/* $.get("{{ url('profit_analysis/getcustomer/') }}", function(data) {
				//$('#multiselect2').find('option').remove().end();
				$.each(data, function(key, value) {   
				$('#multiselect2').append($("<option></option>")
							.attr("value",key)
							.text(value)); 
				});
			}); */
			
			$.ajax({
				url: "{{ url('profit_analysis/getArea/') }}",
				//type: 'get',
				//data: 'item_id='+item_id+'&unit_id='+unit_id,
				success: function(data) {
					$('#selarea').html(data);
					return true;
				}
			}); 
		}
		 else if(vchr=='item') {
			if( $("#selcust").is(":visible") )
				$("#selcust").toggle();
			
			if( $("#selitm").is(":hidden") )
				$("#selitm").toggle();
			
			$.ajax({
				url: "{{ url('profit_analysis/getitems/') }}",
				success: function(data) {
					$('#selitm').html(data);
					return true;
				}
			}); 

		}  else if(vchr=='group') {
			if( $("#selgroup").is(":hidden") )
				$("#selgroup").toggle();
			
			if( $("#selitm").is(":visible") )
				$("#selitm").toggle();
			
			$.ajax({
				url: "{{ url('profit_analysis/getgroup/') }}",
				success: function(data) {
					$('#selgroup').html(data);
					return true;
				}
			}); 

		} 
		else if(vchr=='subgroup') {
			if( $("#selsubgroup").is(":hidden") )
				$("#selsubgroup").toggle();
			
			if( $("#selitm").is(":visible") )
				$("#selitm").toggle();
			
			$.ajax({
				url: "{{ url('profit_analysis/getSubGroup/') }}",
				success: function(data) {
					$('#selsubgroup').html(data);
					return true;
				}
			}); 

		} 

		 else {
			if( $("#selcust").is(":visible") )
				$("#selcust").toggle();
			if( $("#selitm").is(":visible") )
				$("#selitm").toggle();
			if( $("#selsale").is(":visible") )
				$("#selsale").toggle();
			if( $("#selgroup").is(":visible") )
				$("#selgroup").toggle();
			if( $("#selsubgroup").is(":visible") )
				$("#selsubgroup").toggle();
		
		}
	});
	
});
$(document).ready(function () {
    ///$('#selcust').toggle();
    //$('#selitm').toggle();

	$("#select1").select2({
        theme: "bootstrap",
        placeholder: "Customers"
    });

	$("#select2").select2({
        theme: "bootstrap",
        placeholder: "Salesman"
    });
    $("#select7").select2({
        theme: "bootstrap",
        placeholder: "Item"
    });
    $("#select3").select2({
        theme: "bootstrap",
        placeholder: "Group"
    });
     $("#select4").select2({
        theme: "bootstrap",
        placeholder: "Sub Group"
    });
    $("#select5").select2({
        theme: "bootstrap",
        placeholder: "Category"
    });

   $("#select6").select2({
        theme: "bootstrap",
        placeholder: "Sub Category"
    });
});
$(function() {	
	$('#group').hide(); 
	$('#subgroup').hide(); 
	$('#category').hide(); 
	$('#subcategory').hide(); 
	$('#item').hide(); 
	$(document).on('change', '#search_type', function(e) { 
	   if($('#search_type option:selected').val()=='detail')
			{
			$('#group').show(); 
	$('#subgroup').show(); 
	$('#category').show(); 
	$('#subcategory').show();
	$('#item').show(); 
}
		else
		{
		$('#group').hide(); 
	     $('#subgroup').hide(); 
	$('#category').hide(); 
	$('#subcategory').hide();
	$('#item').hide(); 
} 
    });
});
function printInvoice() {	
	document.frmProfitAnalysis.action = "{{ url('profit_analysis/print')}}";
	document.frmProfitAnalysis.submit();
}
</script>
@stop
