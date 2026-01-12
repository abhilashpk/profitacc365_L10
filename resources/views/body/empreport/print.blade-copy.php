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
             WPS Report  
            </h1>
            <ol class="breadcrumb">
                <li>
                      <i class="fa fa-fw fa-shield"></i> WPS Report  
                </li>
				<li>
                    <a href="#"> WPS Report  </a>
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
								<tr><td width="60%" align="center"><h3>{{Session::get('company')}}</h3></td>
									<td align="left"></td>
								</tr>
								<tr>
									<td width="60%" align="right"><h4><u>{{$voucherhead}}</u></h4>
									</td><td align="left"></td>
								</tr>
							</table><br/>
                        </div>
						<p>Date From: <b><?php //echo ($fromdate=='')?date('d-m-Y',strtotime($settings->from_date)):$fromdate;?></b> &nbsp; To: <b><?php // echo ($todate=='')?date('d-m-Y'):$todate;?></b></p>
                        <div class="col-md-12">
						<?php //if(count($reports) > 0) {  ?>
					
								<table class="table" border="0">
									<thead>
										<th>Edr</th>
										<th >Labour No.</th>
										<th >Routing Code</th>
									<th >Account No.</th>
										<th >Salary Start Date</th>
										<th >Salary end Date</th>
										<th >No.of days</th>
											<th >Fixed Salary</th>
											<th >Net Payable</th>
											<th >No.of days leave</th>
											<th >Emp.Name</th>
											<th >Mol card No.</th>
											<th >Company Name</th>
											<th >Nationality</th>
									</thead>
									<body> 
								    @foreach($reports as $row)
								    
										<?php
									
								 
									?>
									   <tr>
											<td>{{$row->emp_detail_type}}</td>
											<td >{{$row->lc_id}}</td>
											<td>{{$row->routing_code}}</td>
											<td>{{$row->account_number}}</td>
												<td >{{date('d-m-Y',strtotime($row->j_start_date))}}</td>
										<td >{{date('d-m-Y',strtotime($row->j_end_date))}}</td>
										<td>{{$row->nwage}}</td>
										<td>{{$row->basic_pay}}</td>

										<td>{{$row->net_salary}}</td>
										<td>{{$row->lev_per_mth}}</td>
										<td>{{$row->name}}</td>
										<td>{{$row->mol_no}}</td>
										<td>{{$row->e_company}}</td>
										<td>{{$row->nationality}}</td>
										
										</tr>
										
									@endforeach
								
									</body>
								</table>
							
								
								
							
							
						<!-- <?php //} else { ?>
						<table border="0" style="width:100%;height:100%;">
							<thead>
								<tr>
									<td colspan="2" align="center"><b style="font-size:20px;">{{Session::get('company')}}</b>
									<img src="{{asset('assets/'.Session::get('logo').'')}}" width="900px;" />
									</td>
								</tr>
								<tr>
									
								</tr>
							</thead>
						</table>
						<br/>
						<div class="alert alert-danger">
							<ul>No records were found!</ul>
						</div>
						<?php // } ?> -->
                        </div>
						
                    </div>
                    <div class="btn-section">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                               <!-- <span class="pull-left">
									<button type="button" onclick="javascript:window.history.back();"
                                                     class="btn btn-responsive button-alignment btn-primary"
                                                     data-toggle="button">
                                                <span style="color:#fff;">
                                                    <i class="fa fa-fw fa-arrow-circle-left"></i>
                                                Back 
                                            </span>
									</button>
								</span> -->
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
                                                <span style="color:#fff;" >
                                                    <i class="fa fa-fw fa-times"></i>
                                                Close 
                                            </span>
                                </button>
								
                                </span>
                        </div>
                    </div>
					<form class="form-horizontal" role="form" method="POST" name="frmExport" id="frmExport" action="{{ url('customerleads/export') }}">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
				
					
				
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
             WPS Report  
            </h1>
            <ol class="breadcrumb">
                <li>
                      <i class="fa fa-fw fa-shield"></i> WPS Report  
                </li>
				<li>
                    <a href="#"> WPS Report  </a>
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
								<tr><td width="60%" align="center"><h3>{{Session::get('company')}}</h3></td>
									<td align="left"></td>
								</tr>
								<tr>
									<td width="60%" align="right"><h4><u>{{$voucherhead}}</u></h4>
									</td><td align="left"></td>
								</tr>
							</table><br/>
                        </div>
						<p>Date From: <b><?php //echo ($fromdate=='')?date('d-m-Y',strtotime($settings->from_date)):$fromdate;?></b> &nbsp; To: <b><?php // echo ($todate=='')?date('d-m-Y'):$todate;?></b></p>
                        <div class="col-md-12">
						<?php //if(count($reports) > 0) {  ?>
					
								<table class="table" border="0">
									<thead>
										<th>Edr</th>
										<th >Labour No.</th>
										<th >Routing Code</th>
									<th >Account No.</th>
										<th >Salary Start Date</th>
										<th >Salary end Date</th>
										<th >No.of days</th>
											<th >Fixed Salary</th>
											<th >Net Payable</th>
											<th >No.of days leave</th>
											<th >Emp.Name</th>
											<th >Mol card No.</th>
											<th >Company Name</th>
											<th >Nationality</th>
									</thead>
									<body> 
								    @foreach($reports as $row)
								    
										<?php
									
								 
									?>
									   <tr>
											<td>{{$row->emp_detail_type}}</td>
											<td >{{$row->lc_id}}</td>
											<td>{{$row->routing_code}}</td>
											<td>{{$row->account_number}}</td>
												<td >{{date('d-m-Y',strtotime($row->j_start_date))}}</td>
										<td >{{date('d-m-Y',strtotime($row->j_end_date))}}</td>
										<td>{{$row->nwage}}</td>
										<td>{{$row->basic_pay}}</td>

										<td>{{$row->net_salary}}</td>
										<td>{{$row->lev_per_mth}}</td>
										<td>{{$row->name}}</td>
										<td>{{$row->mol_no}}</td>
										<td>{{$row->e_company}}</td>
										<td>{{$row->nationality}}</td>
										
										</tr>
										
									@endforeach
								
									</body>
								</table>
							
								
								
							
							
						<!-- <?php //} else { ?>
						<table border="0" style="width:100%;height:100%;">
							<thead>
								<tr>
									<td colspan="2" align="center"><b style="font-size:20px;">{{Session::get('company')}}</b>
									<img src="{{asset('assets/'.Session::get('logo').'')}}" width="900px;" />
									</td>
								</tr>
								<tr>
									
								</tr>
							</thead>
						</table>
						<br/>
						<div class="alert alert-danger">
							<ul>No records were found!</ul>
						</div>
						<?php // } ?> -->
                        </div>
						
                    </div>
                    <div class="btn-section">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                               <!-- <span class="pull-left">
									<button type="button" onclick="javascript:window.history.back();"
                                                     class="btn btn-responsive button-alignment btn-primary"
                                                     data-toggle="button">
                                                <span style="color:#fff;">
                                                    <i class="fa fa-fw fa-arrow-circle-left"></i>
                                                Back 
                                            </span>
									</button>
								</span> -->
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
                                                <span style="color:#fff;" >
                                                    <i class="fa fa-fw fa-times"></i>
                                                Close 
                                            </span>
                                </button>
								
                                </span>
                        </div>
                    </div>
					<form class="form-horizontal" role="form" method="POST" name="frmExport" id="frmExport" action="{{ url('customerleads/export') }}">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
				
					
				
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
