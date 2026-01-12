@extends('layouts/default')

    {{-- Page title --}}
    @section('title')
         
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
            <!--section starts-->
            <h1>
                Item Enquiry
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="fa fa-fw fa-home"></i> Inventory
                    </a>
                </li>
                <li>
                    <a href="#">Item Enquiry</a>
                </li>
            </ol>
			
        </section>
		
       		
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
									<td colspan="2" align="center"><h4><u>{{$heading}} History</u></h4>
								</tr>
							</table><br/>
                        </div>
						<?php if(count($items) > 0) { ?>
						<p>Item Code: <b>{{$items[0]->item_code}}</b> &nbsp; Item Name: <b>{{$items[0]->item_name}}</b> &nbsp; <?php if($cussupid!='') { ?>Customer/Supplier: <b>{{$items[0]->master_name}}</b><?php } ?></p>
                        <div class="col-md-12">
							<table class="table" border="0">
								<thead>
									<th>Inv.No.</th>
									<th>Date</th>
									<th>Customer/Supplier</th>
									<th class="text-right">Quantity</th>
									<th class="text-right">Rate</th>
									<th class="text-right">Total</th>
									<?php if($heading=='Purchase Invoice') {?>
									<th class="text-right">Other Cost</th>
									<?php } ?>
								</thead>
								<body><?php $netqty = $net_uprice = $netotal = $net_octotal = 0; ?>
									@foreach($items as $item)
									<?php 
										$netqty += $item->quantity;
										$net_uprice += $item->unit_price+$item->othercost_unit;//MY27
										$netotal += ($item->unit_price+$item->othercost_unit)*$item->quantity;
										if($heading=='Purchase Invoice')
											$net_octotal += $item->othercost_unit;
									?>
                                    <tr>
										<td>{{ $item->voucher_no }}</td>
										<td>{{ date('d-m-Y', strtotime($item->voucher_date)) }}</td>
										<td>{{ $item->master_name }}</td>
										<td class="text-right">{{ $item->quantity }}</td>
										<td class="text-right">{{ number_format($item->unit_price+$item->othercost_unit,2) }}</td>
										<td class="text-right">{{ number_format(($item->unit_price+$item->othercost_unit)*$item->quantity,2) }}</td>
										<?php if($heading=='Purchase Invoice') {?>
										<td class="text-right">{{ number_format($item->othercost_unit,2) }}</td>
										<?php } ?>
                                    </tr>
									@endforeach
									
									<tr>
										<td></td>
										<td></td>
										<td><b>Total: </b></td>
										<td class="text-right"><b>{{$netqty}}</b></td>
										<td class="text-right"><b>{{number_format($net_uprice,2)}}</b></td>
										<td class="text-right"><b>{{number_format($netotal,2)}}</b></td>
										<?php if($heading=='Purchase Invoice') {?>
										<td class="text-right"><b>{{number_format($net_octotal,2)}}</b></td>
										<?php } ?>
									</tr>
								
								</body>
							</table>
                        </div>
						<?php } else { ?><br/>
							<div class="alert alert-warning">
								<h4>No records were found!</h4>
							</div>
						<?php } ?>
                    </div>
                    <div class="btn-section">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                                <span class="pull-left">
									<button type="button" onclick="javascript:window.close();"
                                                     class="btn btn-responsive button-alignment btn-primary"
                                                     data-toggle="button">
                                                <span style="color:#fff;">
                                                    <i class="fa fa-fw fa-times"></i>
                                                Close 
                                            </span>
                                </button>
								</span>
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
								
                                </span>
                        </div>
                    </div>
					
					<form class="form-horizontal" role="form" method="POST" name="frmExport" id="frmExport" action="{{ url('itemenquiry/export') }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="custsupp_id" value="{{$cussupid}}" >
						<input type="hidden" name="item_id" value="{{$itemid}}" >
						<input type="hidden" name="search_type" value="{{$type}}" >
						<input type="hidden" name="date_from" value="{{$fromdate}}" >
						<input type="hidden" name="date_to" value="{{$todate}}" >
					</form>
					
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
<!-- end of page level js -->
<script>
function getExport() {
	document.frmExport.submit();
}
</script>
@stop
