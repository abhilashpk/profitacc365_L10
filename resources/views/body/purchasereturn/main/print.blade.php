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
<style>
#invoicing {
	font-size:8pt;
}

</style>
<style type="text/css" media="print">

thead { display: table-header-group; }

#inv
{
	display: table-footer-group;
	 position: fixed;
     bottom: 0;
	 margin: 0 auto 0 auto;
}


</style>

@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>Purchase Return</h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="fa fa-fw fa-building-o"></i> Inventory
                    </a>
                </li>
				<li> Purchase Return</li>
                <li class="active">
                   <a href="#">Print</a>
                </li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content p-l-r-15" id="invoice-stmt">
            <div class="panel">
                
                <div class="panel-body">
                    <div class="print" id="invoicing">
						<div class="col-md-12">
						<table border="0" style="width:100%;height:100%;">
							<thead>
								<tr>
									<td colspan="2" align="center">
									<img src="{{asset('assets/'.Session::get('logo').'')}}" width="900px;" /></td>
								</tr>
								<tr>
									<td colspan="2" align="center"><h5><b><u>PURCHASE RETURN</u></b></h5></td>
								</tr>
								<tr>
									<td align="left" valign="top" style="width:50%;padding-left:20px; padding-bottom:5px;">
										<div>
											<strong><?php echo ($details->supplier=='CASH CUSTOMER' || $details->supplier=='Cash Customer')?'CASH CUSTOMER:<br/>'.$details->customer_name:$details->supplier;?></strong>
											<?php echo ($details->address!='')?'<br/>'.$details->address:'';?>
											<?php echo ($details->state!='')?' '.$details->state:'';?>
											<?php echo ($details->pin!='')?'<br/>'.$details->pin:'';?>
											<?php echo ($details->phone!='')?'Phone:'.$details->phone:'';?>
											<?php $vat_no = ($details->vat_no=='')?$details->customer_phone:$details->vat_no; ?>
											<?php echo ($vat_no!='')?'<br/>TRN No: <strong>'.$vat_no.'</strong>':'';?><br/>
										</div>
									</td>
									<td align="left" style="width:50%;padding-left:200px;">
										<div>
											<b>Invoice No: {{$details->voucher_no}}</b><br/>
											Date:  {{date('d-m-Y',strtotime($details->voucher_date))}}<br/>
											LPO No: {{$details->reference_no}}
										</div>
									</td>
								</tr>
							</thead>
							<tbody>
								<tr style="border:1px solid black;">
									<td colspan="2" align="center" valign="top" style="height:510px;">
									<table border="0" style="width:100%;" class="table table-bordered">
										<tr>
											<td width="5%"><b>PI.#</b></td>
											<td width="15%"><b>Item Code</b></td>
											<td width="43%"><b>Description</b></td>
											<td width="5%"><b>Unit</b></td>
											<td width="5%" class="text-right"><b>Qty.</b></td>
											<td width="12%" class="text-right"><b>Unit Price</b></td>
											<td width="10%" class="text-right"><b>VAT</b></td>
											<td width="15%" class="text-right"><b>Total</b></td>
										</tr>
										<?php $i = 0;?>
										@foreach($items as $item)
										<?php $i++; ?>
										<tr>
											<td>{{$i}}</td>
											<td>{{$item->item_code}}</td>
											<td>{{$item->item_name}}</td>
											<td>{{$item->unit_name}}</td>
											<td class="text-right">{{$item->quantity}}</td>
											<td class="text-right">{{number_format($item->unit_price,2)}}</td>
											<td class="text-right">{{number_format($item->vat_amount,2)}}</td>
											<td class="text-right">{{number_format($item->total_price,2)}}</td>
										</tr>
										@endforeach
									</table>
									</td>
								</tr>
								<tr style="border:1px solid black;">
									<td colspan="2" align="center">
										<table border="0" style="width:98%;">
										<tr>
											<td colspan="4"></td>
											<td colspan="2" align="right"><b>
											<p>Gross Total: </p>
											<!--<p>Discount: </p>-->
											<p>Vat Total: </p>
											<p>Net Total: </p></b>
											</td>
											<td colspan="2" align="right">
											<b>
												<p>{{number_format($details->total,2)}}</p>
												<!--<p>{{number_format($details->discount,2)}}</p>-->
												<p>{{number_format($details->vat_amount,2)}}</p>
												<p>{{number_format($details->net_amount,2)}}</p>
											</b>
											</td>
										</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td colspan="2">
										<table border="0" style="width:100%;">
											<tr>
												<td>
													
												</td>
												<td align="right"><br/>
													For {{Session::get('company')}}.<br/><br/>
													
												</td>
											</tr>
										</table>
									</td>
								</tr>
							</tbody>
							
						</table>
						</div>
                    </div>
                    <div class="btn-section">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                                <span class="pull-right">
                                             <button type="button" onclick="javascript:window.print();" 
                                                     class="btn btn-responsive button-alignment btn-primary"
                                                     data-toggle="button">
                                                <span style="color:#fff;" >
                                                    <i class="fa fa-fw fa-print"></i>
                                                Print
                                            </span>
                                </button>
								
								<button type="button" onclick="javascript:window.history.back();"
                                                     class="btn btn-responsive button-alignment btn-primary"
                                                     data-toggle="button">
                                                <span style="color:#fff;" >
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
<script>
$(document).ready(function () {
	$('html').attr({style: 'min-height: inherit'});
	$('body').attr({style: 'min-height: inherit'});
});
</script>
    <!-- end of page level js -->
@stop
