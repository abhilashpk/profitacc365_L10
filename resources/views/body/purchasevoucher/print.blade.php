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
            <!--section starts-->
            <h1>
                 Purchase Voucher
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="">
                        <i class="fa fa-fw fa-retweet"></i> Transaction
                    </a>
                </li>
                <li>
                    <a href="">Vouchers Entry</a>
                </li>
				<li>
                    <a href="">Purchase Voucher</a>
                </li>
                <li class="active">
                   Print
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
								<tr>
									<td align="center" colspan="3">
                                       <b style="font-size:15px;">@include('main.print_head')</b><br/>
									<!--	<b style="font-size:30px;">{{Session::get('company')}}</b><br/>
										<b style="font-size:15px;">Ph: {{Session::get('phone')}}, {{Session::get('address')}}</b><br/>-->
                                        <b style="font-size:15px;">Email:{{Session::get('email')}}</b><br/>
										<b style="font-size:15px;">TRN No: {{Session::get('vatno')}}</b>
									</td>
								</tr>
								<tr>
									<td width="30%">
									</td><td align="center"><h5><u><b>{{$voucherhead}}</b></u></h5></td>
									<td width="30%" align="left"></td>
								</tr>
								<tr>
									<td width="30%" align="center">
									</td><td align="center"></td>
									<td width="30%" align="right"><p>PIN. No: {{$details->voucher_no}}</p><p>Date: {{date('d-m-Y',strtotime($details->voucher_date))}}</p></td>
								</tr>
							</table><br/>
                        </div>
						
                        <div class="col-md-12">
							
							<br/>
							<table class="table" border="1">
								<thead>
									<th>Account Name</th>
									<th>Description</th>
									<th>Reference</th>
									<th class="text-right">Debit</th>
									<th class="text-right">Credit</th>
								</thead>
								<body>
								<?php $dr_total = $cr_total = 0;?>
								@foreach($jerow as $row)
									<tr>
										<td>{{$row->master_name}}</td>
										<td>{{$row->description}}</td>
										<td>{{$row->reference}}</td>
										<td class="text-right"><?php if($row->entry_type=="Dr") { $dr_total += $row->amount; echo number_format($row->amount,2); }?></td>
										<td class="text-right"><?php if($row->entry_type=="Cr") { $cr_total += $row->amount; echo number_format($row->amount,2); }?></td>
									</tr>
								@endforeach
								
									<tr>
										<td></td>
										<td></td>
										<td></td>
										<td class="text-right"><b><?php echo number_format($dr_total,2);?></b></td>
										<td class="text-right"><b><?php echo number_format($cr_total,2);?></b></td>
									</tr>
								</body>
							</table>
							
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
