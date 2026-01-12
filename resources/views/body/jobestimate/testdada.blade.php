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
                                <i class="glyphicon glyphicon-list-alt"></i> Account Enquiry 
                            </div>
                        </div>
                        <div class="portlet-body bg-dng">
                            <div class="portlet-info"><br/>
							   <p>Total Invoice: {{$account}} </p>
                               <p><a href="{{ URL::to('purchase_invoice/add') }}"><b><i class="fa fa-fw fa-plus"></i> Create New</></a></p>
							   <p><a href="{{ URL::to('purchase_invoice') }}"><b><i class="fa fa-fw fa-eye"></i> View All</></a></p>
							   <p> <br/></p>
                            </div>
                        </div>
                        
                    </div>
					
                  
                </div>
                <div class="col-md-4 sortable">
                    <!-- BEGIN Portlet PORTLET-->
                    <div class=" portlet box">
						<div class="portlet-title bg-warning">
                            <div class="caption">
                                <i class="fa fa-fw fa-question-circle"></i> Sales Order
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
                                <i class="fa fa-fw fa-shopping-cart"></i> Item Master
                            </div>
                        </div>
                        <div class="portlet-body bg-pr">
                            <div class="portlet-info"><br/>
								<p>Total Orders: {{$items}} </p>
                               <p><a href="{{ URL::to('purchase_order/add') }}"><b><i class="fa fa-fw fa-plus"></i> Create New</></a></p>
							   <p><a href="{{ URL::to('purchase_order') }}"><b><i class="fa fa-fw fa-eye"></i> View All</></a></p>
							   <p><br/></p>
                            </div>
                        </div>
                    </div>
                   
                </div>
                <div class="col-md-4 sortable">
                    <!-- BEGIN Portlet PORTLET-->
                    <div class=" portlet box">
						 <div class="portlet-title bg-success">
                            <div class="caption">
                                <i class="fa fa-fw fa-stack-exchange"></i> Customer Receipt
                            </div>
                        </div>
                        <div class="portlet-body bg-suc">
                           <div class="portlet-info"><br/>
								<p> Total Quotation: {{$receipt}}</p>
                               <p><a href="{{ URL::to('quotation_sales/add') }}"><b><i class="fa fa-fw fa-plus"></i> Create New</></a></p>
							   <p><a href="{{ URL::to('quotation_sales') }}"><b><i class="fa fa-fw fa-eye"></i> View All</></a></p>
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
            </div>
			
        </section>

		<?php } ?>
		
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
	
});		
</script>
		
    <!-- end of page level js -->
@stop