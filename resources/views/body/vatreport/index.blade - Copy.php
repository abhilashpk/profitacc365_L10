@extends('layouts/default')

    {{-- Page title --}}
    @section('title')
         
        @parent
    @stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
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
        <!--end of page level css-->
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <!--section starts-->
            <h1>
                Vat Report
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="glyphicon glyphicon-folder-close"></i> Reports
                    </a>
                </li>
                <li>
                    <a href="#">Vat Report</a>
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
						<?php if($voucherhead=='Vat Report Summary') { ?>
                        <div class="panel-heading clearfix">
                            <h3 class="panel-title pull-left m-t-6">
                                <i class="fa fa-fw fa-columns"></i> {{$voucherhead}}
                            </h3>
                        </div>
                        <div class="panel-body">
							<form class="form-horizontal" role="form" method="POST" name="frmVatReport" id="frmVatReport" action="{{ url('vat_report/search') }}">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								<div class="row">
									<div class="col-xs-6">
										<span>Date From:</span>
										<input type="text" name="date_from" data-language='en' id="date_from" value="<?php echo $fromdate; ?>" class="form-control input-sm">
										<span>Date To:</span>
										<input type="text" name="date_to" data-language='en' id="date_to" value="<?php echo $todate; ?>" class="form-control input-sm">
									</div>
									<div class="col-xs-6">
										<span>Search By:</span>
										<select id="search_type" class="form-control select2" style="width:100%" name="search_type">
											<option value="summary" <?php if($type=='summary') echo 'selected';?>>Summary</option>
											<option value="detail" <?php if($type=='detail') echo 'selected';?>>Detail</option>
										</select>
										<span></span><br/>
										<button type="submit" class="btn btn-primary">Search</button>
									</div>
								</div>
								<?php if($reports!=null) { ?>
								<div class="table-responsive m-t-10">
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
									<button type="button" class="btn btn-primary outstanding" onclick="printInvoice()">Print</button>
								</div>
								<?php } ?>
							</form>
							
                        </div>
						<?php } else if($voucherhead=='detail'){ ?>
						
                       
                        <div class="col-md-12">
						<div class="pull-center"><h3>{{$voucherhead}}</h3></div>
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
										<tr><td colspan="9"><strong>Purchase</strong></td></tr>
										<?php $purvat = 0;?>
										@foreach($reports['purchase'] as $row)
										<tr> 
											<td>{{$row->voucher_no}}</td>
											<td>{{$row->voucher_date}}</td>
											<td>{{$row->master_name}}</td>
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
										
										<tr><td colspan="9"><strong>Sales</strong></td></tr>
										<?php $salevat = 0;?>
										@foreach($reports['sales'] as $row)
										<tr> 
											<td>{{$row->voucher_no}}</td>
											<td>{{$row->voucher_date}}</td>
											<td>{{$row->master_name}}</td>
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
                        </div>
                      
						<?php } ?>
                    </div>
            </div>
        </div>
       
       
            <!--main content-->
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

<script src="{{asset('assets/vendors/datetime/js/jquery.datetimepicker.full.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.en.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/custom_js/advanceddate_pickers.js')}}"></script>
<!-- end of page level js -->

<script>
function printInvoice() {	
	document.frmProfitAnalysis.action = "{{ url('profit_analysis/print')}}";
	document.frmProfitAnalysis.submit();
}
</script>
@stop

