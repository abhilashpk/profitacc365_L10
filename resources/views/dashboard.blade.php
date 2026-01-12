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
                <div class="col-md-4 sortable">
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
					<?php if( Auth::user()->roles[0]->name == "Admin") { ?>
					 <div class=" portlet box">
						<div class="portlet-title bg-warning">
                            <div class="caption">
                               <i class="fa fa-fw fa-book"></i> Item Master
                            </div>
                        </div>
                        <div class="portlet-body bg-wrn">
                            <div class="portlet-info"><br/>
								<p>Total Items: {{$items}} </p>
                               <p><a href="{{ URL::to('itemmaster/add') }}"><b><i class="fa fa-fw fa-plus"></i> Create New</></a></p>
							   <p><a href="{{ URL::to('itemenquiry') }}"><b><i class="fa fa-fw fa-eye"></i> View All</></a></p>
							   <p> <br/></p>
                            </div>
                        </div>
                    </div>
					
					<?php } ?>
					
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
                        <div class="portlet-title bg-primary">
                            <div class="caption">
                                <i class="fa fa-fw fa-shopping-cart"></i> Purchase Order
                            </div>
                        </div>
                        <div class="portlet-body bg-pr">
                            <div class="portlet-info"><br/>
								<p>Total Orders: {{$order}} </p>
                               <p><a href="{{ URL::to('purchase_order/add') }}"><b><i class="fa fa-fw fa-plus"></i> Create New</></a></p>
							   <p><a href="{{ URL::to('purchase_order') }}"><b><i class="fa fa-fw fa-eye"></i> View All</></a></p>
							   <p><br/></p>
                            </div>
                        </div>
                    </div>
					<?php if( Auth::user()->roles[0]->name == "Admin") { ?>
					 <div class=" portlet box">
                        <div class="portlet-title bg-success">
                            <div class="caption">
                                <i class="fa fa-fw fa-bookmark"></i> Customer Receipt
                            </div>
                        </div>
                        <div class="portlet-body bg-suc">
                            <div class="portlet-info"><br/>
								<p> Total Receipt: {{$receipt}}</p>
                               <p><a href="{{ URL::to('customer_receipt/add') }}"><b><i class="fa fa-fw fa-plus"></i> Create New</></a></p>
							   <p><a href="{{ URL::to('customer_receipt') }}"><b><i class="fa fa-fw fa-eye"></i> View All</></a></p>
							   <p><br/></p>
                            </div>
                        </div>
                    </div>
					<?php } ?>
					
					 <div class=" portlet box">
                        <div class="portlet-title bg-info">
                            <div class="caption">
                                <i class="fa fa-fw fa-caret-square-o-down"></i> Sales Return
                            </div>
                        </div>
                        <div class="portlet-body bg-inf">
                            <div class="portlet-info"><br/>
								<p>Total Returns: {{$salesret}} </p>
                               <p><a href="{{ URL::to('sales_return/add') }}"><b><i class="fa fa-fw fa-plus"></i> Create New</></a></p>
							   <p><a href="{{ URL::to('sales_return') }}"><b><i class="fa fa-fw fa-eye"></i> View All</></a></p>
							   <p><br/></p>
                            </div>
                        </div>
                    </div>
					
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
                    <!-- END Portlet PORTLET-->
                    <!-- BEGIN Portlet PORTLET-->
					<?php if( Auth::user()->roles[0]->name == "Admin") { ?>
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
					
					<div class=" portlet box">
                        <?php if( Auth::user()->roles[0]->name == "Admin") { ?>
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
					
					<?php } ?>
                    </div>
					<?php } else { ?>
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
                    </div>
					<?php } ?>
					<div class=" portlet box">
                        <div class="portlet-title bg-warning">
                            <div class="caption">
                                <i class="fa fa-fw fa-caret-square-o-up"></i>  Purchase Return
                            </div>
                        </div>
                        <div class="portlet-body bg-wrn">
                            <div class="portlet-info"><br/>
								<p> Total Returns: {{$purchaseret}}</p>
                               <p><a href="{{ URL::to('purchase_return/add') }}"><b><i class="fa fa-fw fa-plus"></i> Create New</></a></p>
							   <p><a href="{{ URL::to('purchase_return') }}"><b><i class="fa fa-fw fa-eye"></i> View All</></a></p>
							   <p><br/></p>
                            </div>
                        </div>
                    </div>
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
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title"><i class="fa fa-fw fa-outdent"></i> PDC(Issued) Validity Details</h4>
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
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Document Expiry Details</h4>
					</div>
					<div class="modal-body" id="docexpiryData">
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
		
		<div id="docaprv_modal" class="modal fade animated" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Documents Pending for Approval</h4>
					</div>
					<div class="modal-body">
						<table class="table table-striped" id="tableBank">
							<thead>
							@can('qs-aprv')
								<tr>
									<th>No of Quotations Pending Approval:</th><th>{{$qtno}}</th><th><a href="{{url('quotation_sales')}}">View</a></th>
								</tr>
							@endcan
							@can('so-aprv')
								<tr>
									<th>No of Salaes Orders Pending Approval:</th><th>{{$sono}}</th><th><a href="{{url('sales_order')}}">View</a></th>
								</tr>
							@endcan
							</thead>
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
	<?php if($othrdoccount > 0) { ?>
	   $('#docexpiry_modal').modal('show');
	   var docUrl = "{{ url('document_master/get_expinfo/') }}"; 
	   $('#docexpiryData').load(docUrl, function(result) {
		  $('#myModal').modal({show:true});
	   });
   <?php } ?>
   
	 <?php if($doccount > 0) { ?>
	   $('#expiry_modal').modal('show');
	   var infoUrl = "{{ url('employee/get_expinfo/') }}"; 
	   $('#expiryData').load(infoUrl, function(result) {
		  $('#myModal').modal({show:true});
	   });
   <?php } ?>
    
    <?php if($qtno > 0 || $sono > 0) { ?>
	@can('qs-aprv')
	   $('#docaprv_modal').modal('show');
	 @endcan
   <?php } ?>
   
});   
</script>
		
    <!-- end of page level js -->
@stop