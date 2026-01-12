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
                                <i class="fa fa-fw fa-calendar"></i> Sales Invoice
                            </div>
                        </div>
                        <div class="portlet-body bg-inf">
                            <div class="portlet-info"><br/>
								<p> Total Invoice: {{$sales}}</p>
                               <p><a href="{{ URL::to('sales_invoice/add') }}"><b><i class="fa fa-fw fa-plus"></i> Create New</></a></p>
							   <p><a href="{{ URL::to('sales_invoice') }}"><b><i class="fa fa-fw fa-eye"></i> View All</></a></p>
							   <p><br/></p>
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
							   <p> <br/></p>
                            </div>
                        </div>
                        
                    </div>
					
                </div>
                <div class="col-md-3 sortable">
                    <!-- BEGIN Portlet PORTLET-->
                    <div class=" portlet box">
						<div class="portlet-title bg-warning">
                            <div class="caption">
                                <i class="fa fa-fw fa-question-circle"></i> Delivery Order
                            </div>
                        </div>
                        <div class="portlet-body bg-wrn">
                            <div class="portlet-info"><br/>
								<p> Total Orders: {{$dorder}}</p>
                               <p><a href="{{ URL::to('customers_do/add') }}"><b><i class="fa fa-fw fa-plus"></i> Create New</></a></p>
							   <p><a href="{{ URL::to('customers_do') }}"><b><i class="fa fa-fw fa-eye"></i> View All</></a></p>
							   <p><br/></p>
                            </div>
                        </div>
                    </div>
                    <!-- END Portlet PORTLET-->
                    <!-- BEGIN Portlet PORTLET-->
                     <div class=" portlet box">
						 <div class="portlet-title bg-success">
                            <div class="caption">
                                <i class="fa fa-fw fa-stack-exchange"></i> Quotation Sales
                            </div>
                        </div>
                        <div class="portlet-body bg-suc">
                           <div class="portlet-info"><br/>
								<p> Total Quotation: {{$quotation}}</p>
                               <p><a href="{{ URL::to('quotation_sales/add') }}"><b><i class="fa fa-fw fa-plus"></i> Create New</></a></p>
							   <p><a href="{{ URL::to('quotation_sales') }}"><b><i class="fa fa-fw fa-eye"></i> View All</></a></p>
							   <p><br/></p>
                            </div>
                        </div>
                        
                    </div>
					
                </div>
                
				<div class="col-md-3 sortable">
                    <!-- BEGIN Portlet PORTLET-->
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
							   <p> <br/></p>
                            </div>
                        </div>
                    </div>
					
                </div>
				
				<div class="col-md-3 sortable">
                    <!-- BEGIN Portlet PORTLET-->
                    <div class=" portlet box">
						<div class="portlet-title bg-danger">
                            <div class="caption"> <i class="fa fa-fw fa-bookmark-o"></i>
                                 Supplier Payment 
                            </div>
                        </div>
                        <div class="portlet-body bg-dng">
                            <div class="portlet-info"><br/>
							   <p>Total Payment: {{$payment}} </p>
                               <p><a href="{{ URL::to('supplier_payment/add') }}"><b><i class="fa fa-fw fa-plus"></i> Create New</></a></p>
							   <p><a href="{{ URL::to('supplier_payment') }}"><b><i class="fa fa-fw fa-eye"></i> View All</></a></p>
							   <p> <br/></p>
                            </div>
                        </div>
                        
                    </div>
                    <!-- END Portlet PORTLET-->
                    <!-- BEGIN Portlet PORTLET-->
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
					
                </div>
				
            </div>
			<div class="row">
				<div class="col-md-12">
                    <div class="panel panel-default1">
                        <div class="panel-heading">
                            <h3 class="panel-title">Site Activity</h3>
                            <ul class="nav nav-tabs nav-float pull-right" role="tablist">
                                <li class="active">
                                    <a href="#home" role="tab" data-toggle="tab">Stats</a>
                                </li>
                                <li>
                                    <a href="#profile" role="tab" data-toggle="tab">Sales</a>
                                </li>
                            </ul>
                        </div>
                        <div class="panel-body">
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="home">
                                    <div class="row">
                                        <div class="col-md-8 col-xs-12 stat-chart">
                                            <div id="chart6" class='with-3d-shadow with-transitions'>
                                                <svg></svg>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-xs-12">
                                            <h4>Stats</h4>
                                            <div class="task-item">
                                                Total Sold
                                                <small class="pull-right text-muted">40%</small>
                                                <div class="progress progress-sm">
                                                    <div role="progressbar" aria-valuenow="40" aria-valuemin="0"
                                                         aria-valuemax="100" style="width: 40%;"
                                                         class="progress-bar progress-bar-primary">
                                                        <span class="sr-only">40% Complete (success)</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="task-item">
                                                Product Delivered
                                                <small class="pull-right text-muted">60%</small>
                                                <div class="progress progress-sm">
                                                    <div role="progressbar" aria-valuenow="60" aria-valuemin="0"
                                                         aria-valuemax="100" style="width: 60%;"
                                                         class="progress-bar progress-bar-success">
                                                        <span class="sr-only">60% Complete (success)</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="task-item">
                                                Sale Reports
                                                <small class="pull-right text-muted">55%</small>
                                                <div class="progress progress-sm">
                                                    <div role="progressbar" aria-valuenow="55" aria-valuemin="0"
                                                         aria-valuemax="100" style="width: 55%;"
                                                         class="progress-bar progress-bar-info">
                                                        <span class="sr-only">55% Complete (success)</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="task-item">
                                                New Projects
                                                <small class="pull-right text-muted">66%</small>
                                                <div class="progress progress-sm">
                                                    <div role="progressbar" aria-valuenow="66" aria-valuemin="0"
                                                         aria-valuemax="100" style="width: 66%;"
                                                         class="progress-bar progress-bar-warning">
                                                        <span class="sr-only">66% Complete (success)</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="task-item">
                                                New Users
                                                <small class="pull-right text-muted">90%</small>
                                                <div class="progress progress-sm">
                                                    <div role="progressbar" aria-valuenow="90" aria-valuemin="0"
                                                         aria-valuemax="100" style="width: 90%;"
                                                         class="progress-bar progress-bar-danger">
                                                        <span class="sr-only">90% Complete (success)</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="task-item">
                                                Total Income
                                                <small class="pull-right text-muted">50%</small>
                                                <div class="progress progress-sm">
                                                    <div role="progressbar" aria-valuenow="50" aria-valuemin="0"
                                                         aria-valuemax="100" style="width: 50%;"
                                                         class="progress-bar progress-bar-primary">
                                                        <span class="sr-only">50% Complete (success)</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="profile">
                                    <div class="row">
                                        <div class="col-lg-8 col-md-12 col-xs-12 sales-tab">
                                            <div id="basicFlotLegend"></div>
                                            <div id="placeholder" style="width:100%; height: 291px"></div>
                                        </div>
                                        <div class="col-lg-4 col-md-12 col-xs-12">
                                            <div id="donut" style="width:94%; height: 300px"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
			</div>
			
			<div class="row">
                <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="heading_text panel-title">Sales</h3>
                            <span class="pull-right line-bar-charts">
                                <button class="btn btn-sm btn-link chart_switch" data-chart="bar">Bar Chart</button>
                                <button class="btn btn-sm btn-default chart_switch" data-chart="line">Line Chart
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
                            <h3 class="panel-title">Top Customers</h3>
                        </div>
                        <div class="panel-body">
                            <ul class="basic-list">
                                <li> Google
                                    <span class="right label label-success pull-right">42.8%</span></li>
                                <li>IBM
                                    <span class="right label label-danger pull-right">16.9%</span></li>
                                <li>Microsoft
                                    <span class="right label label-primary pull-right">15.5%</span></li>
                                <li>Samsung
                                    <span class="right label label-info pull-right">11.8%</span></li>
                                <li>Apple
                                    <span class="right label label-danger pull-right">3.2%</span></li>
								<li>HCL
                                    <span class="right label label-danger pull-right">2.2%</span></li>
                                <li>Redhat
                                    <span class="right label label-warning pull-right">1%</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
				
			</div>
			
			<div class="row">
				 
				 <div class="col-lg-4 col-sm-6 col-xs-12">
                    <div class="panel panel-widget">
                        <div class="panel-heading">
                            <h3 class="panel-title">Top Products</h3>
                        </div>
                        <div class="panel-body">
                            <ul class="basic-list">
                                <li> 1GB Samsung HDD
                                    <span class="right label label-success pull-right">42.8%</span></li>
                                <li>16GB Sony USB
                                    <span class="right label label-danger pull-right">16.9%</span></li>
                                <li>20" HD Display
                                    <span class="right label label-primary pull-right">15.5%</span></li>
                                <li>Samsung Galaxy 7
                                    <span class="right label label-info pull-right">11.8%</span></li>
                                <li>Apple iPhone X
                                    <span class="right label label-danger pull-right">3.2%</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
				
				<div class="col-lg-4 col-sm-6 col-xs-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">Outstanding</h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="load-measure">
                                    <h5>Purchase:</h5>
                                    <canvas id="cpu-load" height="300" width="300"></canvas>
                                </div>
                                <div class="load-measure">
                                    <h5>Sales:</h5>
                                    <canvas id="space-used" height="300" width="300"></canvas>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
				
				<div class="col-lg-4 col-sm-6 col-xs-12">
                    <div class="panel panel-widget">
                        <div class="panel-heading">
                            <h3 class="panel-title">Top Salesman</h3>
                        </div>
                        <div class="panel-body">
                            <ul class="basic-list">
                                <li> Toms
                                    <span class="right label label-success pull-right">42.8%</span></li>
                                <li>Ram
                                    <span class="right label label-danger pull-right">16.9%</span></li>
                                <li>Binoy
                                    <span class="right label label-primary pull-right">15.5%</span></li>
                                <li>Raj
                                    <span class="right label label-info pull-right">11.8%</span></li>
                                <li>Kas
                                    <span class="right label label-danger pull-right">3.2%</span></li>
                            </ul>
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
								<p> Total Order: {{$order}}</p>
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
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">PDC(Issued) Validity Details</h4>
					</div>
					<div class="modal-body" id="customerData">
						<table class="table table-striped" id="tableBank">
							<thead>
								<tr>
									<th>Supplier A/c</th>
									<th>Amount</th>
									<th>Cheque No</th>
									<th>Cheque Date</th>
									<th>Bank</th>
									<th>Validity</th>
								</tr>
							</thead>
								<tbody>
								<tr>
									<td>SUP001</td>
									<td>1200.00</td>
									<td>123654654865</td>
									<td>12-02-2018</td>
									<td>INB</td>
									<td>12</td>
								</tr>
								<tr>
									<td>SUP045</td>
									<td>5600.00</td>
									<td>78343489</td>
									<td>12-06-2018</td>
									<td>ICI</td>
									<td>30</td>
								</tr>
								</tbody>
							</tbody>
						</table>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>  
		
		<div id="pdcr_modal" class="modal fade animated" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">PDC(Received) Validity Details</h4>
					</div>
					<div class="modal-body" id="customerData">
						<table class="table table-striped" id="tableBank">
							<thead>
								<tr>
									<th>Customer A/c</th>
									<th>Amount</th>
									<th>Cheque No</th>
									<th>Cheque Date</th>
									<th>Bank</th>
									<th>Validity</th>
								</tr>
							</thead>
								<tbody>
								<tr>
									<td>CUS008</td>
									<td>18000.00</td>
									<td>123654654865</td>
									<td>12-02-2018</td>
									<td>NBD</td>
									<td>1</td>
								</tr>
								<tr>
									<td>CUS045</td>
									<td>5000.00</td>
									<td>7878767655777</td>
									<td>12-06-2018</td>
									<td>ICI</td>
									<td>30</td>
								</tr>
								</tbody>
							</tbody>
						</table>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>  
		
		<div id="expiry_modal" class="modal fade animated" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Expiry Details</h4>
					</div>
					<div class="modal-body" id="customerData">
						<table class="table table-striped" id="tableBank">
							<thead>
								<tr>
									<th>Emp.ID</th>
									<th>Name</th>
									<th>Document</th>
									<th>Expiry Date</th>
									<th>Validity</th>
								</tr>
							</thead>
								<tbody>
								<tr>
									<td>EMP08</td>
									<td>Thomas</td>
									<td>VISA</td>
									<td>12-02-2018</td>
									<td>10</td>
								</tr>
								<tr>
									<td>EMP02</td>
									<td>Jom</td>
									<td>PASSPORT</td>
									<td>12-06-2018</td>
									<td>30</td>
								</tr>
								</tbody>
							</tbody>
						</table>
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

<script>
$(document).ready(function () { //alert('hi');
	//$('#pdci_modal').modal('show');
	
	//$('#pdcr_modal').modal('show');
	
	//$('#expiry_modal').modal('show');
	
	var core_chart1 = {
        main_dashboard: function () {
            if ($('#sales-line-bar').length) {
                var sales_line_bar_chart = c3.generate({
                    bindto: '#sales-line-bar',
                    data: {
                        x: 'x',
                        columns: [
                            ['x', '2013-01-01', '2013-02-01', '2013-03-01', '2013-04-01', '2013-05-01', '2013-06-01', '2013-07-01', '2013-08-01', '2013-09-01', '2013-10-01', '2013-11-01', '2013-12-01'],
                            ['Cash', 72, 53, 91, 72, 81, 114, 94, 109, 118, 112, 124, 143],
                            ['Credit', 118, 114, 118, 134, 163, 152, 158, 178, 183, 194, 212, 188]
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

                $('.chart_switch').on('click', function () {

                    if ($(this).data('chart') == 'line') {
                        sales_line_bar_chart.transform('area', 'Cash');
                        sales_line_bar_chart.transform('line', 'Credit');
                    } else if ($(this).data('chart') == 'bar') {
                        sales_line_bar_chart.transform('bar');
                    }
                    if (!$(this).hasClass("btn-default")) {
                        $('.chart_switch').toggleClass('btn-default btn-link');
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
	
	
//Outstanding.........	
	
    //memory usage chart

    var server_loadchart2 = {
        memory_used: function () {
            //canvas initialization
            var usedmemory = document.getElementById("space-used");
            var view = usedmemory.getContext("2d");
            //dimensions
            var width = usedmemory.width;
            var height = usedmemory.height;
            //Variables
            var angle = 190;
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

    
});		
</script>
		
    <!-- end of page level js -->
@stop