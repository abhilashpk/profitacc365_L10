<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>
        @section('title')
            Profit ACC 365 | ERP Software
        @show
    </title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link rel="shortcut icon" href="{{asset('assets/img/favicon.ico')}}"/>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    <!-- global css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/app.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
   <!-- <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/invoice.css')}}">-->
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/bootstrap.css')}}">
@yield('header_styles')

<style>
#invoicing {
	font-size:8pt;
}

.tblstyle td,
  .tblstyle th {
    height:15px;
	padding:2px;
	border:1px solid #000 !important;
  }

/* @media print {
	html, body {
		
		height: 530px !important;        
	}
	.page {
		margin: 0;
		border: initial;
		border-radius: initial;
		width: initial;
		min-height: initial;
		box-shadow: initial;
		background: initial;
		page-break-after: always;
	}
} */
</style>
<style type="text/css" media="print">

/*body{ page-break-after: always !important; overflow: hidden !important; }*/

thead
{
	display: table-header-group;
}

#inv
{
	 display: table-footer-group;
	 /*position: fixed;*/
     bottom: 0;
	 margin: 0 auto 0 auto;
	 width:100%;
}

.t {
	 height:250px;
}

</style>
<!-- end of global css -->
</head>
<body >


<!-- For horizontal menu -->
@yield('horizontal_header')
<!-- horizontal menu ends -->
        <!-- Main content -->
        <section class="content p-l-r-15" id="cost-job">
            <div class="panel">
                
                <div class="panel-body">
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
						<p>Date From: <b><?php echo ($fromdate=='')?date('d-m-Y',strtotime($settings->from_date)):date('d-m-Y',strtotime($fromdate));?></b> &nbsp; To: <b><?php echo ($todate=='')?date('d-m-Y'):$todate;?></b></p>
                        <div class="col-md-12">
							<?php if(count($results) > 0) { ?>
							<?php if($type=='qtyhand_ason_date_loc') { ?>
							<table class="table">
										<thead>
											<tr>
												<th style="width:50px;"><strong>SI.No.</strong></th>
												<th><strong>Item Code</strong></th>
												<th><strong>Description</strong></th>
												<th><strong>Unit</strong></th>
												<th class="text-right"><strong>Quantity</strong></th>
												<th class="text-right"><strong><?php echo ($type=='opening_quantity')?'Cost Open':'Cost Avg.';?></strong></th>
												<th class="text-right"><strong>Total</strong></th>
												<th class="emptyrow" style="width:10px;"></th>
											</tr>
										</thead>
										<tbody>
										<?php $gtotal = $gqtytotal = 0;
										foreach($results as $items) { ?>
										<tr>
											<td colspan="2"><b>Location Code: {{$items[0]['code']}}</b></td>
											<td colspan="3"><b>Location Name: {{$items[0]['name']}}</b></td>
											<td class="text-right"></td>
											<td class="text-right"></td>
											<td></td> 
										</tr>
										
										<?php $i = $total = $qtytotal = 0;?>
										@foreach($items as $item)
										<?php $i++; 
											if($type=='opening_quantity') {
												$qty = $item['opn_quantity'];
												$cost = $item['opn_cost'];
												$subtotal = $cost * $qty;
												$total += $subtotal;
												$qtytotal += $qty;
											} else {
												$qty = $item['quantity'];
												$cost = $item['cost_avg'];
												$subtotal = $cost * $qty;
												$total += $subtotal;
												$qtytotal += $qty;
											}
										?>
										<tr>
											<td>{{$i}}</td>
											<td>{{$item['itemcode']}}</td>
											<td>{{$item['description']}}</td>
											<td>{{$item['unit']}}</td>
											<td class="text-right">{{$qty}}</td>
											<td class="text-right">{{number_format($cost,2)}}</td>
											<td class="text-right">{{number_format($subtotal,2)}}</td>
											<td></td> 
										</tr>
										@endforeach
										<tr>
											<td colspan="3"></td>
											<td><b>Sub Total:</b></td>
											<td class="text-right"><b>{{$qtytotal}}</b></td>
											<td></td>
											<td class="text-right"><b>{{number_format($total,2)}}</b></td>
											<td></td>
										</tr>
										<?php 
											$gtotal += $total; $gqtytotal += $qtytotal;
										
										} ?>
										<?php //if($locid=='all') { ?>
										<tr>
											<td colspan="3"></td>
											<td><b>Grand Total:</b></td>
											<td class="text-right"><b>{{$gqtytotal}}</b></td>
											<td></td>
											<td class="text-right"><b>{{number_format($gtotal,2)}}</b></td>
											<td></td>
										</tr>
										<?php //} ?>
										</tbody>
									</table>
							<?php } else if($type=='qtyhand_ason_priordate_loc') { ?>
							<table class="table">
										<thead>
											<tr>
												<th style="width:50px;"><strong>SI.No.</strong></th>
												<th><strong>Item Code</strong></th>
												<th><strong>Description</strong></th>
												<th><strong>Unit</strong></th>
												<th class="text-right"><strong>Quantity</strong></th>
												<th class="text-right"><strong><?php echo ($type=='opening_quantity')?'Cost Open':'Cost Avg.';?></strong></th>
												<th class="text-right"><strong>Total</strong></th>
												<th class="emptyrow" style="width:10px;"></th>
											</tr>
										</thead>
										<tbody>
										<?php $gtotal = $gqtytotal = 0;
										foreach($results as $items) { ?>
										<tr>
											<td colspan="2"><b>Location Code: {{$items[0]['code']}}</b></td>
											<td colspan="3"><b>Location Name: {{$items[0]['name']}}</b></td>
											<td class="text-right"></td>
											<td class="text-right"></td>
											<td></td> 
										</tr>
										
										<?php $i = $total = $qtytotal = 0;?>
										@foreach($items as $item)
										<?php $i++; 
											if($type=='opening_quantity') {
												$qty = $item['opn_quantity'];
												$cost = $item['opn_cost'];
												$subtotal = $cost * $qty;
												$total += $subtotal;
												$qtytotal += $qty;
											} else {
												$qty = $item['quantity'];
												$cost = $item['cost_avg'];
												$subtotal = $cost * $qty;
												$total += $subtotal;
												$qtytotal += $qty;
											}
										?>
										<tr>
											<td>{{$i}}</td>
											<td>{{$item['itemcode']}}</td>
											<td>{{$item['description']}}</td>
											<td>{{$item['unit']}}</td>
											<td class="text-right">{{$qty}}</td>
											<td class="text-right">{{number_format($cost,2)}}</td>
											<td class="text-right">{{number_format($subtotal,2)}}</td>
											<td></td> 
										</tr>
										@endforeach
										<tr>
											<td colspan="3"></td>
											<td><b>Sub Total:</b></td>
											<td class="text-right"><b>{{$qtytotal}}</b></td>
											<td></td>
											<td class="text-right"><b>{{number_format($total,2)}}</b></td>
											<td></td>
										</tr>
										<?php 
											$gtotal += $total; $gqtytotal += $qtytotal;
										
										} ?>
										<?php if($locid=='all') { ?>
										<tr>
											<td colspan="3"></td>
											<td><b>Grand Total:</b></td>
											<td class="text-right"><b>{{$gqtytotal}}</b></td>
											<td></td>
											<td class="text-right"><b>{{number_format($gtotal,2)}}</b></td>
											<td></td>
										</tr>
										<?php } ?>
										</tbody>
									</table>
							<?php } else if($type=='opening_quantity_loc') { ?>
									<table class="table">
										@foreach($results as $result)
										
											<tr>
												<td colspan="8"><strong>Location: {{$result[0]['code']}}({{$result[0]['name']}})</strong></td>
											</tr>
											<tr>
												<th style="width:50px;"><strong>SI.No.</strong></th>
												<th><strong>Item Code</strong></th>
												<th><strong>Description</strong></th>
												<th><strong>Unit</strong></th>
												<th class="text-right"><strong>Quantity</strong></th>
												<th class="text-right"><strong><?php echo ($type=='opening_quantity')?'Cost Open':'Cost Avg.';?></strong></th>
												<th class="text-right"><strong>Total</strong></th>
												<th class="emptyrow" style="width:10px;"></th>
											</tr>
										
										<tbody>
										<?php $i = $total = $qtytotal = 0;?>
										@foreach($result as $item)
										<?php $i++; 
											
												$quantity = $item['opn_qty'];
												$cost = $item['opn_cost'];
												$subtotal = $quantity * $item['opn_cost'];
												$total += $subtotal;
												$qtytotal += $item['opn_qty'];
											
										?>
										<tr>
											<td>{{$i}}</td>
											<td>{{$item['item_code']}}</td>
											<td>{{$item['description']}}</td>
											<td>{{$item['unit']}}</td>
											<td class="text-right">{{$quantity}}</td>
											<td class="text-right">{{($quantity > 0)?number_format($cost,2):0}}</td>
											<td class="text-right">{{number_format($subtotal,2)}}</td>
											<td></td> 
										</tr>
										@endforeach
										<tr>
											<td colspan="4"><b>Grand Total:</b></td>
											<td class="text-right"><b>{{$qtytotal}}</b></td>
											<td></td>
											<td class="text-right"><b>{{number_format($total,2)}}</b></td>
											<td></td>
										</tr>
										</tbody>
										@endforeach
									</table>
										<?php } else if($type=='price_list_qty') { ?>
							        <table class="table">
										<thead>
											<tr>
												<th style="width:50px;"><strong>SI.No.</strong></th>
												<th><strong>Item Code</strong></th>
												<th><strong>Description</strong></th>
												<th><strong>Unit</strong></th>
												@if($binloc)<th><strong>Bin Loc.</strong></th>@endif
												<th class="text-right"><strong>Quantity</strong></th>
												<th class="text-right"><strong><?php echo ($type=='opening_quantity')?'Cost Open':'Cost Avg.';?></strong></th>
												<th class="text-right"><strong>Total</strong></th>
												<th class="text-right"><strong>Selling Price</strong></th>
											</tr>
										</thead>
										<tbody>
										<?php $i = $total = $qtytotal = 0;?>
										@foreach($results as $item)
										<?php $i++; 
											if($type=='opening_quantity') {
												$quantity = $item['opn_quantity'];
												$cost = $item['opn_cost'];
												$subtotal = $quantity * $item['opn_cost'];
												$total += $subtotal;
												$qtytotal += $item['quantity'];
											} else {
												$quantity = $item['quantity'];
												$cost = $item['cost_avg'];
												$subtotal = $item['quantity'] * $item['cost_avg'];
												$total += $subtotal;
												$qtytotal += $quantity;
												
											}
										?>
										<tr>
											<td>{{$i}}</td>
											<td>{{$item['itemcode']}}</td>
											<td>{{$item['description']}}</td>
											<td>{{$item['unit']}}</td>
											@if($binloc)<td>{{$item['bin_loc']}}</td>@endif
											<td class="text-right">{{$quantity}}</td>
											<td class="text-right">{{number_format($cost,2)}}</td>
											<td class="text-right">{{number_format($subtotal,2)}}</td>
											<td class="text-right">{{number_format($item['sell_price'],2)}}</td> 
										</tr>
										@endforeach
										<tr>
											<td colspan="4"><b>Grand Total:</b></td>
											<td class="text-right"><b>{{$qtytotal}}</b></td>
											<td></td>
											<td class="text-right"><b>{{number_format($total,2)}}</b></td>
											<td></td>
										</tr>
										</tbody>
									</table>
									
							<?php } else { ?>
								<table class="table">
										<thead>
											<tr>
												<th style="width:50px;"><strong>SI.No.</strong></th>
												<th><strong>Item Code</strong></th>
												<th><strong>Description</strong></th>
												<th><strong>Unit</strong></th>
												@if($binloc)<th><strong>Bin Loc.</strong></th>@endif
												<th class="text-right"><strong>Quantity</strong></th>
												<th class="text-right"><strong><?php echo ($type=='opening_quantity')?'Cost Open':'Cost Avg.';?></strong></th>
												<th class="text-right"><strong>Total</strong></th>
												@if($mpqty==1)<th class="text-right"><strong>Mqty</strong></th>@endif
												@if($pqty==1)<th class="text-right"><strong>P1qty</strong></th>@endif
												@if($pqty==1)<th class="text-right"><strong>P2qty</strong></th>@endif
												<th class="emptyrow" style="width:10px;"></th>
											</tr>
										</thead>
										<tbody>
										<?php $i = $total = $qtytotal = $mtotal=$p1total=$p2total=0;?>
										@foreach($results as $item)
										<?php $i++; 
											if($type=='opening_quantity') {
												$quantity = $item['opn_quantity'];
												$cost = $item['opn_cost'];
												$subtotal = $quantity * $item['opn_cost'];
												$total += $subtotal;
												$qtytotal += $item['quantity'];
												$mtotal += $item['mpqty'];
												$p1total += $item['p1qty'];
												$p2total += $item['p2qty'];
											} else {
												$quantity = $item['quantity'];
												$cost = $item['cost_avg'];
												$subtotal = $item['quantity'] * $item['cost_avg'];
												$total += $subtotal;
												$qtytotal += $quantity;
												$mtotal += $item['mpqty'];
												$p1total += $item['p1qty'];
												$p2total += $item['p2qty'];
											}
										?>
										<tr>
											<td>{{$i}}</td>
											<td>{{$item['itemcode']}}</td>
											<td>{{$item['description']}}</td>
											<td>{{$item['unit']}}</td>
											@if($binloc)<td>{{$item['bin_loc']}}</td>@endif
											<td class="text-right">{{$quantity}}</td>
											<td class="text-right">{{number_format($cost,2)}}</td>
											<td class="text-right">{{number_format($subtotal,2)}}</td>
											@if($mpqty==1)<td class="text-right">{{$item['mpqty']}}</td>@endif
											@if($pqty==1)<td class="text-right">{{$item['p1qty']}}</td>@endif
											@if($pqty==1)<td class="text-right">{{$item['p2qty']}}</td>@endif
											<td></td> 
										</tr>
										@endforeach
										<tr>
											<td colspan="4"><b>Grand Total:</b></td>
											<td class="text-right"><b>{{$qtytotal}}</b></td>
											<td></td>
											<td class="text-right"><b>{{number_format($total,2)}}</b></td>
											@if($mpqty==1)<td class="text-right"><b>{{$mtotal}}</b></td>@endif
											@if($pqty==1)<td class="text-right"><b>{{$p1total}}</b></td>@endif
											@if($pqty==1)<td class="text-right"><b>{{$p2total}}</b></td>@endif
											<td></td>
										</tr>
										</tbody>
									</table>
							<?php } ?>
							<?php } else  echo 'No records were found.'; ?>
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
								
								<button type="button" onclick="getExport()"
											 class="btn btn-responsive button-alignment btn-primary"
											 data-toggle="button">
										<span style="color:#fff;">
											<i class="fa fa-fw fa-upload"></i>
										Export Excel
									</span>
									</button>
									
								<button type="button" onclick="javascript:window.close();"
                                                     class="btn btn-responsive button-alignment btn-primary"
                                                     data-toggle="button">
                                                <span style="color:#fff;">
                                                    <i class="fa fa-fw fa-times"></i>
                                                Close 
                                            </span>
                                </button>
                                </span>
                        </div>
					<?php if(count($results) > 0) { ?>	
					<form class="form-horizontal" role="form" method="POST" name="frmExport" id="frmExport" action="{{ url('quantity_report/export') }}">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="hidden" name="itemtype" value="{{$itemtype}}" >
					<input type="hidden" name="date_to" value="{{$todate}}" >
					<input type="hidden" name="date_from" value="{{$fromdate}}" >
					<input type="hidden" name="search_type" value="{{$type}}" >
					<input type="hidden" name="quantity_type" value="{{$qtytype}}" >
					<input type="hidden" name="search_val" value="{{$searchval}}" >
					<input type="hidden" name="mpqty" value="{{$mpqty}}" >
					<input type="hidden" name="pqty" value="{{$pqty}}" >
					<!--<input type="hidden" name="group_id" value="" >
					<input type="hidden" name="subgroup_id" value="" >
					<input type="hidden" name="category_id" value="" >
					<input type="hidden" name="subcategory_id" value="" >
					<input type="hidden" name="location_id" value="" >-->
					</form>
					<?php } ?>
                    </div>
                </div>
            </div>
            <!-- row -->
      
        <!-- right side bar end -->
        </section>


{{-- page level scripts --}}
@section('footer_scripts')
    <!-- begining of page level js -->
<script type="text/javascript" src="{{asset('assets/js/custom_js/invoice.js')}}"></script>
    <!-- end of page level js -->
<script>
function getExport() {
	document.frmExport.submit();
}
</script>

