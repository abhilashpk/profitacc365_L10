@extends('layouts/default')

{{-- Page title --}}
@section('title')
    
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <!--weathericons-->
	

    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
	
    <link rel="stylesheet" href="{{asset('assets/css/portlet.css')}}"/>
	
	<link href="{{asset('assets/vendors/c3/c3.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/vendors/nvd3/css/nv.d3.min.css')}}" rel="stylesheet" type="text/css"/>
	
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
	
	 <link href="{{asset('assets/css/custom_css/dashboard2.css')}}" rel="stylesheet" type="text/css"/>
    <!--end of page level css-->
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Dashboard
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index.html">
                        <i class="fa fa-fw fa-home"></i> Dashboard
                    </a>
                </li>
               
            </ol>
        </section>
		<?php if($workshop==0) { ?>
        <!-- Main content -->
        <section class="content">
            <div class="row ui-sortable" id="sortable_portlets">

                <div class="col-md-3 sortable">
                    <!-- BEGIN Portlet PORTLET-->
                    <div class=" portlet box">
						<div class="portlet-title bg-info">
                            <div class="caption">
                                <i class="fa fa-fw fa-calendar"></i> Customer Enquiry
                            </div>
                        </div>
                        <div class="portlet-body bg-inf">
                            <div class="portlet-info">
								<p> Enquiry: {{$details['ce_net']}}
								<span style="padding-left:25px;">Pending: {{$details['ce_pending']}}</span></p>
								<p> Approved: {{$details['ce_aprvd']}}
								<span style="padding-left:10px;">Rejected: {{$details['ce_rjctd']}}</span></p>
                               <p><a href="{{ URL::to('customer_enquiry/add') }}"><i class="fa fa-fw fa-plus"></i> Create New</></a></p>
							   <p><a href="{{ URL::to('customer_enquiry') }}"><i class="fa fa-fw fa-eye"></i> View All</></a></p>
                            </div>
                        </div>
                        
                    </div>
                    <!-- END Portlet PORTLET-->
                    <!-- BEGIN Portlet PORTLET-->
                    <div class=" portlet box">
						<div class="portlet-title bg-danger">
                            <div class="caption">
                                <i class="glyphicon glyphicon-list-alt"></i> Purchase Invoice 
                            </div>
                        </div>
                        <div class="portlet-body bg-dng">
                            <div class="portlet-info"><br/>
							   <p>Total Invoice: {{$purchase}} </p>
                               <p><a href="{{ URL::to('purchase_invoice/add') }}"><b><i class="fa fa-fw fa-plus"></i> Create New</></a></p>
							   <p><a href="{{ URL::to('purchase_invoice') }}"><b><i class="fa fa-fw fa-eye"></i> View All</></a></p>
							   <br/>
                            </div>
                        </div>
                        
                    </div>
					
                </div>
                <div class="col-md-3 sortable">
                    <!-- BEGIN Portlet PORTLET-->
                    <div class=" portlet box">
						<div class="portlet-title bg-warning">
                            <div class="caption">
                                <i class="fa fa-fw fa-question-circle"></i> Quotation
                            </div>
                        </div>
                        <div class="portlet-body bg-wrn">
                            <div class="portlet-info">
								<p> Quotation: {{$details['qs_net']}}
								<span style="padding-left:8px;">Pending: {{$details['qs_pending']}}</span></p>
								<p> Approved: {{$details['qs_aprvd']}}
								<span style="padding-left:20px;">Rejected: {{$details['qs_rjctd']}}</span></p>
                               <p><a href="{{ URL::to('quotation_sales/add') }}"><i class="fa fa-fw fa-plus"></i> Create New</></a></p>
							   <p><a href="{{ URL::to('quotation_sales') }}"><i class="fa fa-fw fa-eye"></i> View All</></a></p>
                            </div>
                        </div>
                    </div>
                    <!-- END Portlet PORTLET-->
                    <!-- BEGIN Portlet PORTLET-->
                     <div class=" portlet box">
						 <div class="portlet-title bg-success">
                            <div class="caption">
                                <i class="fa fa-fw fa-stack-exchange"></i> Sales Invoice
                            </div>
                        </div>
                        <div class="portlet-body bg-suc">
                           <div class="portlet-info"><br/>
								<p> Total Invoice: {{$sales}}</p>
                               <p><a href="{{ URL::to('sales_invoice/add') }}"><b><i class="fa fa-fw fa-plus"></i> Create New</></a></p>
							   <p><a href="{{ URL::to('sales_invoice') }}"><b><i class="fa fa-fw fa-eye"></i> View All</></a></p>
							   <br/>
                            </div>
                        </div>
                        
                    </div>
					
                </div>
                
				<div class="col-md-3 sortable">
                    <!-- BEGIN Portlet PORTLET-->
                    <div class=" portlet box">
						<div class="portlet-title bg-primary">
                            <div class="caption">
                                <i class="fa fa-fw fa-question-circle"></i> Sales Order
                            </div>
                        </div>
                        <div class="portlet-body bg-pr">
                            <div class="portlet-info">
								<p> Orders: {{$details['so_net']}}
								<span style="padding-left:10px;"> Pending: {{$details['so_pending']}}</span></p>
								<p> Approved: {{$details['so_aprvd']}}
								<span style="padding-left:8px;">Rejected: {{$details['so_rjctd']}}</span></p>
                               <p><a href="{{ URL::to('sales_order/add') }}"><b><i class="fa fa-fw fa-plus"></i> Create New</></a></p>
							   <p><a href="{{ URL::to('sales_order') }}"><b><i class="fa fa-fw fa-eye"></i> View All</></a></p>
                            </div>
                        </div>
                        
                    </div>
                    <!-- END Portlet PORTLET-->
                    <!-- BEGIN Portlet PORTLET-->
                    <div class=" portlet box">
                        <div class="portlet-title bg-info">
                            <div class="caption">
                               <i class="fa fa-fw fa-book"></i> Item Enquiry
                            </div>
                        </div>
                        <div class="portlet-body bg-inf">
                            <div class="portlet-info"><br/>
								<p>Total Items: {{$items}} </p>
                               <p><a href="{{ URL::to('itemmaster/add') }}"><b><i class="fa fa-fw fa-plus"></i> Create New</></a></p>
							   <p><a href="{{ URL::to('itemenquiry') }}"><b><i class="fa fa-fw fa-eye"></i> View All</></a></p>
							   <br/>
                            </div>
                        </div>
                    </div>
					
                </div>
				
				<div class="col-md-3 sortable">
                    <!-- BEGIN Portlet PORTLET-->
                    <div class=" portlet box">
						<div class="portlet-title bg-danger">
                            <div class="caption"> <i class="fa fa-fw fa-bookmark-o"></i>
                                 Leads
                            </div>
                        </div>
                        <div class="portlet-body bg-dng">
                            <div class="portlet-info">
							   <p>Total Leads: {{$details['ld_net']}}
								<span style="padding-left:10px;"> Suspect: {{$details['sespect']}}</span></p>
								<p> Prospective: {{$details['propect']}}
								<span style="padding-left:8px;"> Enquiry: {{$details['enqry']}}</span></p>
                               <p><a href="{{ URL::to('leads/add') }}"><b><i class="fa fa-fw fa-plus"></i> Create New</></a></p>
							   <p><a href="{{ URL::to('leads') }}"><b><i class="fa fa-fw fa-eye"></i> View All</></a></p>
                            </div>
                        </div>
                        
                    </div>
                    <!-- END Portlet PORTLET-->
                    <!-- BEGIN Portlet PORTLET-->
                   <div class=" portlet box">
                        <div class="portlet-title bg-warning">
                            <div class="caption">
                                <i class="fa fa-fw fa-bookmark"></i> Account Enquiry
                            </div>
                        </div>
                        <div class="portlet-body bg-wrn">
                            <div class="portlet-info"><br/>
								<p> Total Accounts: {{$account}}</p>
                               <p><a href="{{ URL::to('account_master/add') }}"><b><i class="fa fa-fw fa-plus"></i> Create New</></a></p>
							   <p><a href="{{ URL::to('account_master') }}"><b><i class="fa fa-fw fa-eye"></i> View All</></a></p>
							   <br/>
                            </div>
                        </div>
                    </div>
					
                </div>
				
            </div>
			<br/>
			<div class="row">
                <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="heading_text panel-title" style="color:#428BCA !important;"> <i class="fa fa-fw fa-bar-chart-o"></i> Sales</h3>
                            <span class="pull-right line-bar-charts">
                                <button class="btn btn-sm btn-link chart_switch_sales" data-chart="bar">Bar Chart</button>
                                <button class="btn btn-sm btn-default chart_switch_sales" data-chart="line">Line Chart
                                </button>
                            </span>
                        </div>
                        <div class="panel-body">
                            <div id="sales-line-bar" style="height:280px"></div>
                        </div>
                    </div>
                </div>
				
				<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                    <div class="panel panel-widget">
                        <div class="panel-heading">
                            <h3 class="panel-title" style="color:#428BCA"><i class="fa fa-fw fa-users"></i> Top Customers</h3>
                        </div>
                        <div class="panel-body" style="padding:0px !important;">
                            <ul class="basic-list">
							<?php foreach($custdat as $row) { ?>
                                <li> {{$row['name']}}
                                    <span class="right label label-success pull-right">{{$row['per']}}%</span>
								</li>
							<?php } ?>
                                
                            </ul>
                        </div>
                    </div>
                </div>
				
			</div>
			
			<div class="row">
				<div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="heading_text panel-title" style="color:#428BCA !important;"> <i class="fa fa-fw fa-bar-chart-o"></i> Purchase</h3>
                            <span class="pull-right line-bar-charts">
                                <button class="btn btn-sm btn-link chart_switch_pur" data-chart="bar">Bar Chart</button>
                                <button class="btn btn-sm btn-default chart_switch_pur" data-chart="line">Line Chart
                                </button>
                            </span>
                        </div>
                        <div class="panel-body">
                            <div id="purchase-line-bar" style="height:280px"></div>
                        </div>
                    </div>
                </div>
			</div>
			
			
			<div class="row">
				 
				 <div class="col-lg-4 col-sm-6 col-xs-12">
                    <div class="panel panel-widget">
                        <div class="panel-heading">
                            <h3 class="panel-title" style="color:#428BCA"><i class="fa fa-fw fa-shopping-cart"></i> Top Products</h3>
                        </div>
                        <div class="panel-body" style="padding:0px !important;">
                            <ul class="basic-list">
							<?php foreach($products as $row) { ?>
                                <li> {{$row['name']}}
                                    <span class="right label label-success pull-right">{{$row['per']}}%</span>
								</li>
							<?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>
				
				<div class="col-lg-4 col-sm-6 col-xs-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title" style="color:#428BCA"><i class="fa fa-fw fa-money"></i> Outstanding</h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
								<div class="load-measure"><br/>
                                    <h5>Sales: <b>{{number_format($salesos->unpaid+$salesos->balance,2)}}</b></h5>
                                    <canvas id="space-used" height="300" width="300"></canvas>
                                </div>
                                <div class="load-measure"><br/>
                                    <h5>Purchase: <b>{{number_format($purchaseos->unpaid+$purchaseos->balance,2)}}</b></h5>
                                    <canvas id="cpu-load" height="300" width="300"></canvas>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
				
				<div class="col-lg-4 col-sm-6 col-xs-12">
                    <div class="panel panel-widget">
                        <div class="panel-heading">
                            <h3 class="panel-title" style="color:#428BCA !important;"> <i class="fa fa-fw fa-user"></i> Top Salesman</h3>
                        </div>
                        <div class="panel-body" style="padding:0px !important;">
                            <ul class="basic-list">
							@foreach($salesman as $row)
                                <li> {{$row['name']}}
                                    <span class="right label label-success pull-right">{{$row['per']}}%</span>
								</li>
                           @endforeach     
                            </ul>
                        </div>
                    </div>
                </div>
				
			</div>
			
			<div class="row">
				<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title" style="color:#428BCA !important;"><i class="fa fa-fw fa-calendar-o"></i> Expiry Details</h3>
                        </div>
                        <div class="panel-body" style="padding:0px !important;">
                            <ul class="auto-update">
							@foreach($expdocs as $row)
                                <li>
                                    <div class="activity">
                                        <div class="activity-image pull-left">
                                            <img src="{{asset('assets/img/customer-icon.jpg')}}" alt="profile-image"
                                                 class="img-circle media-image">
                                        </div>
                                        <div class="activity-content pull-left">
                                            <p><h5 class="heading text-primary">{{$row['name']}}</h5></p>
                                            
                                            <p>{{$row['doc']}} expiry on {{date('d-m-Y',strtotime($row['expiry_date']))}}.</p>
											<p class="text-muted">
                                                <small>{{$row['days']}} days more</small>
                                            </p>
                                        </div>
                                    </div>
                                </li>
							@endforeach
                                
                            </ul>
                        </div>
                    </div>
                </div>
				
				<div class="col-md-8">
					 <div class="panel panel-default1">
						 <div class="panel-heading">
                            <h3 class="panel-title" style="padding-bottom:10px; color:#428BCA !important;"><i class="fa fa-fw fa-list-alt"></i> PDC Validity Details</h3>
                            <ul class="nav nav-tabs nav-float" role="tablist">
                                <li class="active">
                                    <a href="#pdcr" role="tab" data-toggle="tab">PDCR</a>
                                </li>
                                <li>
                                    <a href="#pdci" role="tab" data-toggle="tab">PDCI</a>
                                </li>
                            </ul>
                        </div>
						<div class="panel-body">
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="pdcr">
									<div class="row" style="padding:10px;">
									<table class="table table-striped" id="tableBank">
										<thead>
											<tr>
												<th>Customer</th>
												<th>Amount</th>
												<th>Cheque No</th>
												<th>Cheque Date</th>
												<th>Bank</th>
											</tr>
										</thead>
											<tbody>
											@foreach($pdclist as $pd)
											<?php if($pd->cheque_no!='' && $pd->code!='') {  ?>
											<tr>
												<td>{{ $pd->customer }}</td>
												<td>{{ number_format($pd->amount,2) }}</td>
												<td>{{ $pd->cheque_no }}</td>
												<td>{{ ($pd->cheque_date=='0000-00-00')?'':date('d-m-Y',strtotime($pd->cheque_date)) }}</td>
												<td>{{ $pd->code }}</td>
											</tr>
											<?php } ?>
											@endforeach
											</tbody>
										</tbody>
									</table>
									</div>
									</div>
									 <div class="tab-pane fade" id="pdci">
                                    <div class="row" style="padding:10px;">
                                        <table class="table table-striped" id="tableBank">
										<thead>
											<tr>
												<th>Supplier</th>
												<th>Amount</th>
												<th>Cheque No</th>
												<th>Cheque Date</th>
												<th>Bank</th>
											</tr>
										</thead>
											<tbody>
											@foreach($pdcis as $pd)
											<?php if($pd->cheque_no!='' && $pd->code!='') {  ?>
											<tr>
												<td>{{ $pd->creditor }}</td>
												<td>{{ number_format($pd->amount,2) }}</td>
												<td>{{ $pd->cheque_no }}</td>
												<td>{{ ($pd->cheque_date=='0000-00-00')?'':date('d-m-Y',strtotime($pd->cheque_date)) }}</td>
												<td>{{ $pd->code }}</td>
											</tr>
											<?php } ?>
											@endforeach
											</tbody>
										</tbody>
									</table>
								</div>
                                </div>
								</div>
							</div>
						</div>
					</div>
					
			</div>
        </section>
		<?php } else { ?>
		<section class="content">
            <div class="row ui-sortable" id="sortable_portlets">
                <div class="col-md-4 sortable">
                    <!-- BEGIN Portlet PORTLET-->
                    <div class=" portlet box">
                        <div class="portlet-title bg-primary">
                            <div class="caption">
                                <i class="fa fa-fw fa-shopping-cart"></i> Job Estimate
                            </div>
                        </div>
                        <div class="portlet-body bg-pr">
                            <div class="portlet-info"><br/>
								<p>Total Estimate: {{$quotation}} </p>
                               <p><a href="{{ URL::to('job_estimate/add') }}"><b><i class="fa fa-fw fa-plus"></i> Create New</></a></p>
							   <p><a href="{{ URL::to('job_estimate') }}"><b><i class="fa fa-fw fa-eye"></i> View All</></a></p>
							   <p><br/></p>
                            </div>
                        </div>
                    </div>
                    <!-- END Portlet PORTLET-->
                    <!-- BEGIN Portlet PORTLET-->
					<?php if( Auth::user()->roles[0]->name == "Admin") { ?>
                    <div class=" portlet box">
						<div class="portlet-title bg-danger">
                            <div class="caption">
                                <i class="glyphicon glyphicon-list-alt"></i> Purchase Invoice 
                            </div>
                        </div>
                        <div class="portlet-body bg-dng">
                            <div class="portlet-info"><br/>
							   <p>Total Invoice: {{$purchase}} </p>
                               <p><a href="{{ URL::to('purchase_invoice/add') }}"><b><i class="fa fa-fw fa-plus"></i> Create New</></a></p>
							   <p><a href="{{ URL::to('purchase_invoice') }}"><b><i class="fa fa-fw fa-eye"></i> View All</></a></p>
							   <p> <br/></p>
                            </div>
                        </div>
                        
                    </div>
					<?php } ?>
					<?php if( Auth::user()->roles[0]->name == "Admin") { ?>
					 <div class=" portlet box">
						<div class="portlet-title bg-success">
                            <div class="caption"> <i class="fa fa-fw fa-bookmark-o"></i>
                                 Supplier Payment 
                            </div>
                        </div>
                        <div class="portlet-body bg-suc">
                            <div class="portlet-info"><br/>
							   <p>Total Payment: {{$payment}} </p>
                               <p><a href="{{ URL::to('supplier_payment/add') }}"><b><i class="fa fa-fw fa-plus"></i> Create New</></a></p>
							   <p><a href="{{ URL::to('supplier_payment') }}"><b><i class="fa fa-fw fa-eye"></i> View All</></a></p>
							   <p> <br/></p>
                            </div>
                        </div>
                        
                    </div>
					<?php } ?>
                    <!-- END Portlet PORTLET-->
                    <!-- BEGIN Portlet PORTLET-->
                    <!--<div class=" portlet box">
                        <div class="portlet-title bg-info">
                            <div class="caption">
                                <i class="fa fa-fw fa-bars"></i> Portlet
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div>
                                Tom loves Canada. Angela and Tom met. Angela and Tom want to play. Angela and Tom want
                                to jump. Angela and Tom want to yell. Angela and Tom play, jump and yell.
                            </div>
                        </div>
                    </div>-->
                    <!-- END Portlet PORTLET-->
                </div>
                <div class="col-md-4 sortable">
                    <!-- BEGIN Portlet PORTLET-->
                    <div class=" portlet box">
                        <div class="portlet-title bg-success">
                            <div class="caption">
                                <i class="fa fa-fw fa-stack-exchange"></i> Job Order
                            </div>
                        </div>
                        <div class="portlet-body bg-suc">
                           <div class="portlet-info"><br/>
								<p> Total Order: {{$sorder}}</p>
                               <p><a href="{{ URL::to('job_order/add') }}"><b><i class="fa fa-fw fa-plus"></i> Create New</></a></p>
							   <p><a href="{{ URL::to('job_order') }}"><b><i class="fa fa-fw fa-eye"></i> View All</></a></p>
							   <p><br/></p>
                            </div>
                        </div>
                    </div>
                    <!-- END Portlet PORTLET-->
                    <!-- BEGIN Portlet PORTLET-->
					<?php if( Auth::user()->roles[0]->name == "Admin") { ?>
                    <div class=" portlet box">
                        <div class="portlet-title bg-info">
                            <div class="caption">
                                <i class="fa fa-fw fa-calendar"></i> Item Enquiry
                            </div>
                        </div>
                        <div class="portlet-body bg-inf">
                            <div class="portlet-info"><br/>
								<p> Total Items: {{$items}}</p>
                               <p><a href="{{ URL::to('itemmaster/add') }}"><b><i class="fa fa-fw fa-plus"></i> Create New</></a></p>
							   <p><a href="{{ URL::to('itemmaster') }}"><b><i class="fa fa-fw fa-eye"></i> View All</></a></p>
							   <p><br/></p>
                            </div>
                        </div>
                    </div>
					<?php } ?>
					<?php if( Auth::user()->roles[0]->name == "Admin") { ?>
					 <div class=" portlet box">
                        <div class="portlet-title bg-warning">
                            <div class="caption">
                                <i class="fa fa-fw fa-bookmark"></i> Customer Receipt
                            </div>
                        </div>
                        <div class="portlet-body bg-wrn">
                            <div class="portlet-info"><br/>
								<p> Total Receipt: {{$receipt}}</p>
                               <p><a href="{{ URL::to('customer_receipt/add') }}"><b><i class="fa fa-fw fa-plus"></i> Create New</></a></p>
							   <p><a href="{{ URL::to('customer_receipt') }}"><b><i class="fa fa-fw fa-eye"></i> View All</></a></p>
							   <p><br/></p>
                            </div>
                        </div>
                    </div>
					<?php } ?>
                    <!-- END Portlet PORTLET-->
                    <!-- BEGIN Portlet PORTLET-->
                    <!--<div class=" portlet box">
                        <div class="portlet-title bg-warning">
                            <div class="caption">
                                <i class="fa fa-fw fa-bars"></i> Portlet
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div>
                                Tom loves Canada. Angela and Tom met. Angela and Tom want to play. Angela and Tom want
                                to jump. Angela and Tom want to yell. Angela and Tom play, jump and yell.
                            </div>
                        </div>
                    </div>-->
                    <!-- END Portlet PORTLET-->
                </div>
                <div class="col-md-4 sortable">
                    <!-- BEGIN Portlet PORTLET-->
                    <div class=" portlet box">
                        <div class="portlet-title bg-warning">
                            <div class="caption">
                               <i class="fa fa-fw fa-book"></i> Job Invoice
                            </div>
                        </div>
                        <div class="portlet-body bg-wrn">
                            <div class="portlet-info"><br/>
								<p>Total Invoice: {{$sales}} </p>
                               <p><a href="{{ URL::to('job_invoice/add') }}"><b><i class="fa fa-fw fa-plus"></i> Create New</></a></p>
							   <p><a href="{{ URL::to('job_invoice') }}"><b><i class="fa fa-fw fa-eye"></i> View All</></a></p>
							   <p> <br/></p>
                            </div>
                        </div>
                    </div>
                    <!-- END Portlet PORTLET-->
                    <!-- BEGIN Portlet PORTLET-->
					<?php if( Auth::user()->roles[0]->name == "Admin") { ?>
                    <div class=" portlet box">
                        <div class="portlet-title bg-primary">
                            <div class="caption">
                                <i class="fa fa-fw fa-question-circle"></i> Account Enquiry
                            </div>
                        </div>
                        <div class="portlet-body bg-pr">
                            <div class="portlet-info"><br/>
								<p> Total Accounts: {{$account}}</p>
                               <p><a href="{{ URL::to('account_master/add') }}"><b><i class="fa fa-fw fa-plus"></i> Create New</></a></p>
							   <p><a href="{{ URL::to('account_master') }}"><b><i class="fa fa-fw fa-eye"></i> View All</></a></p>
							   <p><br/></p>
                            </div>
                        </div>
                    </div>
					
					<div class=" portlet box">
                        <div class="portlet-title bg-danger">
                            <div class="caption">
                                <i class="fa fa-fw fa-file-text-o"></i> HR
                            </div>
                        </div>
                        <div class="portlet-body bg-dng">
                            <div class="portlet-info"><br/>
								<p> Total Employee: {{$employee}}</p>
                               <p><a href="{{ URL::to('employee/add') }}"><b><i class="fa fa-fw fa-plus"></i> Create New</></a></p>
							   <p><a href="{{ URL::to('employee') }}"><b><i class="fa fa-fw fa-eye"></i> View All</></a></p>
							   <p><br/></p>
                            </div>
                        </div>
                    </div>
					<?php } else { ?>
					<?php if( Auth::user()->roles[0]->name == "Admin") { ?>
						<div class=" portlet box">
                        <div class="portlet-title bg-primary">
                            <div class="caption">
                                <i class="fa fa-fw fa-question-circle"></i> Sales Order
                            </div>
                        </div>
                        <div class="portlet-body bg-pr">
                            <div class="portlet-info"><br/>
								<p> Total Orders: {{$sorder}}</p>
                               <p><a href="{{ URL::to('sales_order/add') }}"><b><i class="fa fa-fw fa-plus"></i> Create New</></a></p>
							   <p><a href="{{ URL::to('sales_order') }}"><b><i class="fa fa-fw fa-eye"></i> View All</></a></p>
							   <p><br/></p>
                            </div>
                        </div>
                    </div><?php } ?>
					<?php } ?>
                    <!-- END Portlet PORTLET-->
                    <!-- BEGIN Portlet PORTLET-->
                    <!--<div class=" portlet box notsort">
                        <div class="portlet-title bg-danger">
                            <div class="caption">
                                <i class="fa fa-fw fa-exclamation"></i> Non Draggable Portlet
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div>
                                Tom loves Canada. Angela and Tom met. Angela and Tom want to play. Angela and Tom want
                                to jump. Angela and Tom want to yell. Angela and Tom play, jump and yell.
                            </div>
                        </div>
                    </div>-->
                    <!-- END Portlet PORTLET-->
                </div>
            </div>
        </section>
		<?php } ?>
		
		<div id="pdci_modal" class="modal fade animated" role="dialog">
			<div class="modal-dialog">
				<div class="panel panel-danger">
					<div class="panel-heading">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title danger"><i class="fa fa-fw fa-outdent"></i> PDC(Issued) Validity Details</h4>
					</div>
					<div class="modal-body" id="pdciData">
						
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>  
		
		<div id="pdcr_modal" class="modal fade animated" role="dialog">
			<div class="modal-dialog">
				<div class="panel panel-danger">
					<div class="panel-heading">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title"><i class="fa fa-fw fa-indent"></i> PDC(Received) Validity Details</h4>
					</div>
					<div class="modal-body" id="pdcrData">
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>  
		
		<div id="expiry_modal" class="modal fade animated" role="dialog">
			<div class="modal-dialog">
				<div class="panel panel-warning">
					<div class="panel-heading">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title"><i class="fa fa-fw fa-users"></i> Employee's Expiry Details</h4>
					</div>
					<div class="modal-body" id="expiryData">
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
		
		<div id="docexpiry_modal" class="modal fade animated" role="dialog">
			<div class="modal-dialog">
				<div class="panel panel-info">
					<div class="panel-heading">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title"><i class="fa fa-fw fa-file-text-o"></i> Document Expiry Details</h4>
					</div>
					<div class="modal-body" id="docexpiryData">
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
		
        <!-- /.content -->
        
@stop

{{-- page level scripts --}}
@section('footer_scripts')
<script type="text/javascript" src="{{asset('assets/vendors/c3/c3.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/d3/d3.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/advanced_newsTicker/js/newsTicker.js')}}"></script>
<script>
$(document).ready(function () { //alert('hi');
	$('#pdci_modal').modal('show');
	var pdciUrl = "{{ url('dashboard/get_pdci_alert/') }}"; 
   $('#pdciData').load(pdciUrl, function(result) {
	  $('#myModal').modal({show:true});
   });
	
	$('#pdcr_modal').modal('show');
	var pdcrUrl = "{{ url('dashboard/get_pdcr_alert/') }}"; 
   $('#pdcrData').load(pdcrUrl, function(result) {
	  $('#myModal').modal({show:true});
   });
	
   <?php if($doccount > 0) { ?>
	   $('#expiry_modal').modal('show');
	   var infoUrl = "{{ url('employee/get_expinfo/') }}"; 
	   $('#expiryData').load(infoUrl, function(result) {
		  $('#myModal').modal({show:true});
	   });
   <?php } ?>
	
	<?php if($othrdoccount > 0) { ?>
	$('#docexpiry_modal').modal('show');
	   var docUrl = "{{ url('dashboard/get_docexpinfo/') }}"; 
	   $('#docexpiryData').load(docUrl, function(result) {
		  $('#myModal').modal({show:true});
	 });
	 <?php } ?>
	   
	var core_chart1 = {
        main_dashboard: function () {
            if ($('#sales-line-bar').length) {
                var sales_line_bar_chart = c3.generate({
                    bindto: '#sales-line-bar',
                    data: {
                        x: 'x',
                        columns: [
                            {!!$xdata['x']!!},
							{!!isset($linebar[0])?$linebar[0]:''!!},
							{!!isset($linebar[1])?$linebar[1]:''!!}
							{!!isset($linebar[2])?$linebar[2]:''!!}
                        ],
                        types: {
                            'Cash': 'area',
                            'Credit': 'line'
                        }
                    },
                    axis: {
                        x: {
                            type: 'timeseries',
                            tick: {
                                culling: false,
                                fit: true,
                                format: "%b"
                            }
                        },
                        y: {
                            tick: {
                                format: d3.format("")
                            }
                        }
                    },
                    point: {
                        r: '4',
                        focus: {
                            expand: {
                                r: '5'
                            }
                        }
                    },
                    bar: {
                        width: {
                            ratio: 0.5 // this makes bar width 50% of length between ticks
                        }
                    },
                    grid: {
                        x: {
                            show: true
                        },
                        y: {
                            show: true
                        }
                    },
                    color: {
                        pattern: ['#428bca', '#ffb65f', '#2ca02c', '#d62728', '#9467bd', '#8c564b', '#e377c2', '#7f7f7f', '#bcbd22', '#17becf']
                    }
                });

                $('.chart_switch_sales').on('click', function () {

                    if ($(this).data('chart') == 'line') {
                        //sales_line_bar_chart.transform('area', 'Cash');
                        //sales_line_bar_chart.transform('line', 'Credit');
						sales_line_bar_chart.transform('line');
                    } else if ($(this).data('chart') == 'bar') {
                        sales_line_bar_chart.transform('bar');
                    }
                    if (!$(this).hasClass("btn-default")) {
                        $('.chart_switch_sales').toggleClass('btn-default btn-link');
                    }

                });

                $(window).on("debouncedresize", function () {
                    sales_line_bar_chart.resize();
                });


                //To resize the charts width on clicking left-menu collapse button
                $("[data-toggle='offcanvas']").click(function (e) {

                    sales_line_bar_chart.resize();

                });
            }

        }

    };
    core_chart1.main_dashboard();
	
	
	var core_chart2 = {
        main_dashboard: function () {
            if ($('#purchase-line-bar').length) {
                var purchase_line_bar_chart = c3.generate({
                    bindto: '#purchase-line-bar',
                    data: {
                        x: 'x',
                        columns: [
                            {!!$xdata['x']!!},
							{!!isset($linebarpur[0])?$linebarpur[0]:''!!},
							{!!isset($linebarpur[1])?$linebarpur[1]:''!!}
							{!!isset($linebarpur[2])?$linebarpur[2]:''!!}
                        ],
                        types: {
                            'Cash': 'area',
                            'Credit': 'line'
                        }
                    },
                    axis: {
                        x: {
                            type: 'timeseries',
                            tick: {
                                culling: false,
                                fit: true,
                                format: "%b"
                            }
                        },
                        y: {
                            tick: {
                                format: d3.format("")
                            }
                        }
                    },
                    point: {
                        r: '4',
                        focus: {
                            expand: {
                                r: '5'
                            }
                        }
                    },
                    bar: {
                        width: {
                            ratio: 0.5 // this makes bar width 50% of length between ticks
                        }
                    },
                    grid: {
                        x: {
                            show: true
                        },
                        y: {
                            show: true
                        }
                    },
                    color: {
                        pattern: ['#428bca', '#ffb65f', '#2ca02c', '#d62728', '#9467bd', '#8c564b', '#e377c2', '#7f7f7f', '#bcbd22', '#17becf']
                    }
                });

                $('.chart_switch_pur').on('click', function () {

                    if ($(this).data('chart') == 'line') {
                        purchase_line_bar_chart.transform('line');
                    } else if ($(this).data('chart') == 'bar') {
                        purchase_line_bar_chart.transform('bar');
                    }
                    if (!$(this).hasClass("btn-default")) {
                        $('.chart_switch_pur').toggleClass('btn-default btn-link');
                    }

                });

                $(window).on("debouncedresize", function () {
                    purchase_line_bar_chart.resize();
                });


                //To resize the charts width on clicking left-menu collapse button
                $("[data-toggle='offcanvas']").click(function (e) {

                    purchase_line_bar_chart.resize();

                });
            }

        }

    };
    core_chart2.main_dashboard();
	
	
//Outstanding.........	
	
    //memory usage chart

    var server_loadchart2 = { //52
        memory_used: function () {
            //canvas initialization
            var usedmemory = document.getElementById("space-used");
            var view = usedmemory.getContext("2d");
            //dimensions
            var width = usedmemory.width;
            var height = usedmemory.height;
            //Variables
            var angle = 100;
            var colour = "#428bca";
            var background = "#ccc";
            var value;


            view.clearRect(0, 0, width, height);

            //Background 360 degree arc
            view.beginPath();
            view.strokeStyle = background;
            view.lineWidth = 25;
            view.arc(width / 2, height / 2, 100, 0, Math.PI * 2, false);
            view.stroke();

            //Angle in radians = angle in degrees * PI / 180
            var rad = angle * Math.PI / 180;
            view.beginPath();
            view.strokeStyle = colour;
            view.lineWidth = 30;

            view.arc(width / 2, height / 2, 100, 0 - 90 * Math.PI / 180, rad - 90 * Math.PI / 180, false);
            view.stroke();

            //Lets add the text
            view.fillStyle = colour;
            view.font = "50px bebas";
            value = Math.floor(angle / 360 * 100) + "%";
            //center the text
            var text_widtheight = view.measureText(value).width;
            //adding manual value to position y since the height of the text cannot
            view.fillText(value, width / 2 - text_widtheight / 2, height / 2 + 15);


        }
    };

    server_loadchart2.memory_used();
	
	var server_loadchart3 = {
        memory_used: function () {
            //canvas initialization
            var usedmemory = document.getElementById("cpu-load");
            var view = usedmemory.getContext("2d");
            //dimensions
            var width = usedmemory.width;
            var height = usedmemory.height;
            //Variables
            var angle = 50;
            var colour = "#428bca";
            var background = "#ccc";
            var value;


            view.clearRect(0, 0, width, height);

            //Background 360 degree arc
            view.beginPath();
            view.strokeStyle = background;
            view.lineWidth = 25;
            view.arc(width / 2, height / 2, 100, 0, Math.PI * 2, false);
            view.stroke();

            //Angle in radians = angle in degrees * PI / 180
            var rad = angle * Math.PI / 180;
            view.beginPath();
            view.strokeStyle = colour;
            view.lineWidth = 30;

            view.arc(width / 2, height / 2, 100, 0 - 90 * Math.PI / 180, rad - 90 * Math.PI / 180, false);
            view.stroke();

            //Lets add the text
            view.fillStyle = colour;
            view.font = "50px bebas";
            value = Math.floor(angle / 360 * 100) + "%";
            //center the text
            var text_widtheight = view.measureText(value).width;
            //adding manual value to position y since the height of the text cannot
            view.fillText(value, width / 2 - text_widtheight / 2, height / 2 + 15);


        }
    };

    server_loadchart3.memory_used();

    // server load js ends

     //auto activity update panel

    if ($('.auto-update').length > 0) {
        $('.auto-update').newsTicker({
            row_height: 100,
            max_rows: 2,
            speed: 1500,
            direction: 'down',
            duration: 3500,
            autostart: 1,
            pauseOnHover: 1
        });
    }

    //auto activity update panel ends
	
});		
</script>
		
    <!-- end of page level js -->
@stop