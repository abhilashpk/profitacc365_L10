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
    <link rel="stylesheet" href="{{asset('assets/css/custom_css/skins/skin-coreplus.css')}}" type="text/css" id="skin"/>
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
                Employee
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="fa fa-fw fa-money"></i> Payroll
                    </a>
                </li>
                <li>
                    <a href="#">Document Report</a>
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
						<p><?php echo ($fromdate!='')?'Date From:':'';?> <b><?php echo ($fromdate=='')?'':$fromdate;?></b> &nbsp; Date To: <b><?php echo ($todate=='')?date('d-m-Y'):$todate;?></b></p>
                        <div class="col-md-12">
							<?php if($dept!="") { ?>
							<p>Department Name: <b>{{$reports[0]->department_name}}</b></p>
								<table class="table" border="0">
									<thead>
										<th>Si.No.</th>
										<th>Document Code</th>
										<th>Document Name</th>
										<th>Issue Date</th>
										<th>Expiry Date</th>
										<th class="text-right">Amount</th>
									</thead>
									<body><?php $total = $i = 0;?>
										@foreach($reports as $row)
										<?php $i++;
										  $total += $row->amount;
										?>
										<tr>
										<td>{{$i}}</td>
										<td>{{$row->code}}</td>
										<td>{{ $row->name }}</td>
										<td>{{($row->issue_date=='0000-00-00')?'':date('d-m-Y', strtotime($row->issue_date))}}</td>
										<td>{{($row->expiry_date=='0000-00-00')?'':date('d-m-Y', strtotime($row->expiry_date))}}</td>
										<td class="text-right">{{($row->amount > 0)?number_format($row->amount,2):''}}</td>
									</tr>
									@endforeach
									<tr>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td class="text-right"><b>Total:</b></td>
										<td class="text-right"><b>{{($total > 0)?number_format($total,2):''}}</b></td>
									</tr>
									</body>
								</table>
							<?php } else { ?>
							<table class="table" border="0">
								<thead>
									<th>Si.No.</th>
									<th>Document Code</th>
									<th>Document Name</th>
									<th>Department</th>
									<th>Issue Date</th>
									<th>Expiry Date</th>
									<th class="text-right">Amount</th>
								</thead>
								<body>
									<?php $total = $i = 0;?>
									@foreach($reports as $row)
									<?php $i++;
										  $total += $row->amount;
									?>
									<tr>
										<td>{{$i}}</td>
										<td>{{$row->code}}</td>
										<td>{{ $row->name }}</td>
										<td>{{$row->department_name}}</td>
										<td>{{($row->issue_date=='0000-00-00')?'':date('d-m-Y', strtotime($row->issue_date))}}</td>
										<td>{{($row->expiry_date=='0000-00-00')?'':date('d-m-Y', strtotime($row->expiry_date))}}</td>
										<td class="text-right">{{($row->amount > 0)?number_format($row->amount,2):''}}</td>
									</tr>
									@endforeach
									<tr>
										<td></td>
										<td></td>
										<td></td>
										<td></td><td></td>
										<td class="text-right"><b>Total:</b></td>
										<td class="text-right"><b>{{($total > 0)?number_format($total,2):''}}</b></td>
									</tr>
								</body>
							</table>
							<?php } ?>
                        </div>
						
                    </div>
                    <div class="btn-section">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                                <span class="pull-left">
									<button type="button" onclick="javascript:window.history.back();"
                                                     class="btn btn-responsive button-alignment btn-primary"
                                                     data-toggle="button">
                                                <span style="color:#fff;">
                                                    <i class="fa fa-fw fa-arrow-circle-left"></i>
                                                Back 
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
									
									<!--<button type="button" onclick="getExport()"
											 class="btn btn-responsive button-alignment btn-primary"
											 data-toggle="button">
										<span style="color:#fff;">
											<i class="fa fa-fw fa-upload"></i>
										Export Excel
									</span>
									</button>-->
								
                                </span>
                        </div>
                    </div>
					<form class="form-horizontal" role="form" method="POST" name="frmExport" id="frmExport" action="{{ url('sales_order/export') }}">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="hidden" name="date_from" value="{{$fromdate}}" >
					<input type="hidden" name="date_to" value="{{$todate}}" >
					<input type="hidden" name="dept" value="{{$dept}}" >
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
<script type="text/javascript" src="{{asset('assets/js/custom_js/invoice.js')}}"></script>
    <!-- end of page level js -->
<script>
function getExport() {
	document.frmExport.submit();
}
</script>
@stop
