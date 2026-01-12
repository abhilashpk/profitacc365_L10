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

.boxtable {padding:5px !important;}​
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
            <h1> Location Transfer</h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="fa fa-fw fa-building-o"></i> Inventory
                    </a>
                </li>
				<li> Location Transfer</li>
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
								
								<tr><td colspan="2"  align="center"><h5><b><u>Location to Location Transfer</u></b></h5></td></tr>
								<tr style="border:0px solid black;">
									<td align="left" valign="top" style="width:50%; padding-left:20px; padding-bottom:5px;">
										<div>
											<strong>
											<?php echo 'From: '.$details->cr_account;?><br/>
											<?php echo 'To: '.$details->dr_account;?>
											
											</strong>
											
										</div>
									</td>
									<td align="left" style="padding-left:200px; width:50%;">
										<div>
											<b>LT. No: {{$details->voucher_no}}</b><br/>
											Date:  {{date('d-m-Y',strtotime($details->voucher_date))}}<br/>
										</div>
										
									</td>
								</tr>
							</thead>
							<tbody>
								<tr style="border:1px solid black;">
									<td colspan="2" align="center" valign="top" style="height:450px;">
									<table border="0" style="width:100%;" class="table table-bordered">
										<tr>
											<td width="5%"><b>SI.#</b></td>
											<td width="15%"><b>Item Code</b></td>
											<td width="40%"><b>Description</b></td>
											<td width="8%"><b>Unit</b></td>
											<td width="7%" class="text-right"><b>Qty.</b></td>
										</tr>
										<?php $i = $total = 0;?>
										@foreach($items as $item)
										<?php $i++; 
												$total += $item->quantity;
										?>
										<tr>
											<td>{{$i}}</td>
											<td>{{$item->item_code}}</td>
											<td>{{$item->item_name}}</td>
											<td>{{$item->unit_name}}</td>
											<td class="text-right">{{$item->quantity}}</td>
										</tr>
										@endforeach
									</table>
									</td>
								</tr>
								<tr style="border:1px solid black;">
									<td colspan="2" align="center">
										<table border="0" style="width:98%;">
										<tr>
											<td></td>
											<td colspan="2" align="right"><b>
											<p>Total Quantity: </p>
											</td>
											<td colspan="2" align="right">
											
											<b>
												<p>{{number_format($total,2)}}</p>
											</b>
											</td>
										</tr>
										</table>
									</td>
								</tr>
								<tr style="border:0px solid black;">
									<td colspan="2">
										<table border="0" style="width:100%;">
											<tr>
												<td align="center"> <br/>
													<b>Received by:</b>
												</td>
												<td align="center"> <br/>
													<b>Approved by:</b>
												</td>
												<td align="center"><br/>
												<b>Prepared by:</b>
												</td>
											</tr>
											
										</table>
									</td>
								</tr>
							</tbody>
							<tfoot id="inv">
								<tr style="border-top:0px solid red;">
									<td colspan="2" class="footer"></td>
								</tr>
							</tfoot>
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
