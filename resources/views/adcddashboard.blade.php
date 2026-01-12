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

        @if(Auth::user()->id==0)
        <div class="row">
                                
                <div class="col-md-12">
                     <div class="panel panel-default1">
                        <div class="panel-heading" style="background-color:#86C5FF">
                            <h3 class="panel-title" style="padding-bottom:10px; color:#428BCA !important;"><i class="fa fa-fw fa-list-alt"></i><font color="white">Approval Pending Details</font></h3>
                            <ul class="nav nav-tabs nav-float" role="tablist">
                                <li class="active">
                                    <a href="#pdcr" role="tab" data-toggle="tab">Payment Voucher</a>
                                </li>
                            </ul>
                        </div>
                        <div class="panel-body" style="background-color:#d6eef8">
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="pdcr">
                                    <div class="row" style="padding:10px;">
                                    <table class="table table-striped" id="tableBank">
                                        <thead>
                                            <tr>
                                                <th>Supplier</th>
                                                <th>Amount</th>
                                                <th>Voucher No</th>
                                                <th>Voucher Date</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                            <tbody>
                                            @foreach($pvlist as $pv)
                                            <tr>
                                                <td>{{ $pv->debitor }}</td>
                                                <td>{{ number_format($pv->amount,2) }}</td>
                                                <td>{{ $pv->voucher_no }}</td>
                                                <td>{{ date('d-m-Y',strtotime($pv->voucher_date)) }}</td>
                                                <td><a href="{{ url('dashboard/pv_approve').'/'.$pv->id }}" class="btn btn-info">Approve</a></td>
                                            </tr>
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

            <div class="row">
                                
                <div class="col-md-12">
                     <div class="panel panel-default1">
                        <div class="panel-heading" style="background-color:#86C5FF">
                            <h3 class="panel-title" style="padding-bottom:10px; color:#428BCA !important;"><i class="fa fa-fw fa-list-alt"></i><font color="white">Modified Log Details</font></h3>
                            <ul class="nav nav-tabs nav-float" role="tablist">
                                <li class="active">
                                    <a href="#PV" role="tab" data-toggle="tab">Payment Voucher</a>
                                </li>
                                <li>
                                    <a href="#RV" role="tab" data-toggle="tab">Receipt Voucher</a>
                                </li>
                                <li>
                                    <a href="#PI" role="tab" data-toggle="tab">Purchase Invoice</a>
                                </li>
                                <li>
                                    <a href="#SI" role="tab" data-toggle="tab">Sales Invoice</a>
                                </li>
                            </ul>
                            
                        </div>
                        <div class="panel-body" style="background-color:#d6eef8">
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="PV">
                                    <div class="row" style="padding:10px;">
                                    <table class="table table-striped" id="tableBank">
                                        <thead>
                                            <tr>
                                                <th>Voucher No</th>
                                                <th>Voucher Date</th>
                                                <th>Modified on</th>
                                                <th>Modified by</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                            <tbody>
                                            @foreach($pvlog as $pvl)
                                            <tr>
                                                <td>{{ $pvl->voucher_no }}</td>
                                                <td>{{ date('d-m-Y',strtotime($pvl->voucher_date)) }}</td>
                                                <td>{{ date('d-m-Y',strtotime($pvl->modify_at)) }}</td>
                                                <td>{{ $pvl->name }}</td>
                                                <td>{{--<a href="{{ url('dashboard/pv_approve').'/'.$pv->id }}" class="btn btn-info">Approve</a>--}}</td>
                                            </tr>
                                            @endforeach
                                            </tbody>
                                        </tbody>
                                    </table>
                                    </div>
                                    </div>

                                <div class="tab-pane fade in" id="RV">
                                    <div class="row" style="padding:10px;">
                                    <table class="table table-striped" id="tableBank">
                                        <thead>
                                            <tr>
                                                <th>Voucher No</th>
                                                <th>Voucher Date</th>
                                                <th>Modified on</th>
                                                <th>Modified by</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                            <tbody>
                                            @foreach($rvlog as $pvl)
                                            <tr>
                                                <td>{{ $pvl->voucher_no }}</td>
                                                <td>{{ date('d-m-Y',strtotime($pvl->voucher_date)) }}</td>
                                                <td>{{ date('d-m-Y',strtotime($pvl->modify_at)) }}</td>
                                                <td>{{ $pvl->name }}</td>
                                                <td>{{--<a href="{{ url('dashboard/pv_approve').'/'.$pv->id }}" class="btn btn-info">Approve</a>--}}</td>
                                            </tr>
                                            @endforeach
                                            </tbody>
                                        </tbody>
                                    </table>
                                    </div>
                                    </div>

                                    <div class="tab-pane fade in" id="PI">
                                    <div class="row" style="padding:10px;">
                                    <table class="table table-striped" id="tableBank">
                                        <thead>
                                            <tr>
                                                <th>Voucher No</th>
                                                <th>Voucher Date</th>
                                                <th>Modified on</th>
                                                <th>Modified by</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                            <tbody>
                                            @foreach($pilog as $pvl)
                                            <tr>
                                                <td>{{ $pvl->voucher_no }}</td>
                                                <td>{{ date('d-m-Y',strtotime($pvl->voucher_date)) }}</td>
                                                <td>{{ date('d-m-Y',strtotime($pvl->modify_at)) }}</td>
                                                <td>{{ $pvl->name }}</td>
                                                <td>{{--<a href="{{ url('dashboard/pv_approve').'/'.$pv->id }}" class="btn btn-info">Approve</a>--}}</td>
                                            </tr>
                                            @endforeach
                                            </tbody>
                                        </tbody>
                                    </table>
                                    </div>
                                    </div>

                                    <div class="tab-pane fade in" id="SI">
                                    <div class="row" style="padding:10px;">
                                    <table class="table table-striped" id="tableBank">
                                        <thead>
                                            <tr>
                                                <th>Voucher No</th>
                                                <th>Voucher Date</th>
                                                <th>Modified on</th>
                                                <th>Modified by</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                            <tbody>
                                            @foreach($silog as $pvl)
                                            <tr>
                                                <td>{{ $pvl->voucher_no }}</td>
                                                <td>{{ date('d-m-Y',strtotime($pvl->voucher_date)) }}</td>
                                                <td>{{ date('d-m-Y',strtotime($pvl->modify_at)) }}</td>
                                                <td>{{ $pvl->name }}</td>
                                                <td>{{--<a href="{{ url('dashboard/pv_approve').'/'.$pv->id }}" class="btn btn-info">Approve</a>--}}</td>
                                            </tr>
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
    @else
    

            <div class="row ui-sortable" id="sortable_portlets">
                <div class="col-md-12 sortable">
                    <!-- BEGIN Portlet PORTLET-->
                    <div class=" portlet box">
                        <div class="portlet-title bg-info">
                            <div class="caption">
                               <i class="fa fa-fw fa-bars"></i> Sales
                            </div>
                        </div>
                        <div class="portlet-body bg-inf-new">
                            <div class="portlet">
                                 <div class="col-md-3" ><a href="{{ URL::to('sales_invoice/add') }}"><img src="{{asset('assets/icons/add-sale.png')}}"><span class="info-hd"> New Sale</span></a></div>
                                 <div class="col-md-3" ><a href="{{ URL::to('sales_invoice') }}"><img src="{{asset('assets/icons/view-sale.png')}}"><span class="info-hd"> View Sale</span></a></div>
                                 <div class="col-md-3" ><a href="{{ URL::to('customers_do/add') }}"><img src="{{asset('assets/icons/add-do.png')}}"><span class="info-hd"> New DO</span></a></div>
                                 <div class="col-md-3" ><a href="{{ URL::to('customers_do') }}"><img src="{{asset('assets/icons/view-do.png')}}"><span class="info-hd"> View DO</span></a></div>
                            </div>
                            <p></br></br></p><p></br></p>
                            <div class="portlet">
                                 <div class="col-md-3" ><a href="{{ URL::to('sales_order/add') }}"><img src="{{asset('assets/icons/add-order.png')}}"><span class="info-hd"> New Sales Order</span></a></div>
                                 <div class="col-md-3" ><a href="{{ URL::to('sales_order') }}"><img src="{{asset('assets/icons/view-order.png')}}"><span class="info-hd"> View Sales Order</span></a> </div>
                                 <div class="col-md-3" ><a href="{{ URL::to('quotation_sales/add') }}"><img src="{{asset('assets/icons/add-quote.png')}}"><span class="info-hd"> New Quote</span></a></div>
                                 <div class="col-md-3" ><a href="{{ URL::to('quotation_sales') }}"><img src="{{asset('assets/icons/view-quote.png')}}"><span class="info-hd"> View Quote</span></a></div>
                            </div>
                            <p></br></br></p>
                        </div>
                        
                    </div>
                    <!-- END Portlet PORTLET-->
                    <!-- BEGIN Portlet PORTLET-->
                    <div class=" portlet box">
                        <div class="portlet-title bg-danger">
                            <div class="caption">
                                <i class="fa fa-fw fa-bars"></i> Purchase 
                            </div>
                        </div>
                        <div class="portlet-body bg-dng-new">
                            <div class="portlet">
                                 <div class="col-md-3" ><a href="{{ URL::to('purchase_invoice/add') }}"><img src="{{asset('assets/icons/add-purchase.png')}}"><span class="info-hd"> New Purchase</span></a></div>
                                 <div class="col-md-3" ><a href="{{ URL::to('purchase_invoice') }}"><img src="{{asset('assets/icons/view-purchase.png')}}"><span class="info-hd"> View Purchase</span></a> </div>
                                 <div class="col-md-3" ><a href="{{ URL::to('purchase_order/add') }}"><img src="{{asset('assets/icons/add-proforma.png')}}"><span class="info-hd"> New Purchase Order</span></a></div>
                                 <div class="col-md-3" ><a href="{{ URL::to('purchase_order') }}"><img src="{{asset('assets/icons/view-proforma.png')}}"><span class="info-hd"> View Purchase Order</span></a></div>
                            </div>
                            <p></br></br></p>
                        </div>
                        
                    </div>
                    
                    
                </div>
                <div class="col-md-6 sortable">
                    <!-- BEGIN Portlet PORTLET-->
                    <div class=" portlet box">
                        <div class="portlet-title bg-warning">
                            <div class="caption">
                                <i class="fa fa-fw fa-bars"></i> Customers
                            </div>
                        </div>
                        <div class="portlet-body bg-wrn-new">
                            <div class="portlet">
                                 <div class="col-md-6"><a href="" data-toggle="modal" data-target="#customer_modal"><img src="{{asset('assets/icons/add-customer.png')}}"><span class="info-hd"> New Customer</span></a></div>
                                 <div class="col-md-6" ><a href="{{ URL::to('account_enquiry/cus') }}"><img src="{{asset('assets/icons/view-customer.png')}}"><span class="info-hd"> View Customer</span></a> </div>
                            </div>
                            <p></br></br></p>
                        </div>
                    </div>
                    
                    <div class=" portlet box">
                        <div class="portlet-title bg-primary">
                            <div class="caption">
                                <i class="fa fa-fw fa-bars"></i> Inventory
                            </div>
                        </div>
                        <div class="portlet-body bg-pr-new">
                            
                            <div class="portlet">
                                 <div class="col-md-6"><a href="{{ URL::to('itemmaster/add') }}"><img src="{{asset('assets/icons/add-item.png')}}"><span class="info-hd"> New Item</span></a></div>
                                 <div class="col-md-6" ><a href="{{ URL::to('itemenquiry') }}"><img src="{{asset('assets/icons/view-item.png')}}"><span class="info-hd"> View Item</span></a> </div>
                            </div>
                            <p></br></br></p>
                      
                        </div>
                    </div>
                    
                    
                   <!--<div class=" portlet box">
                         <div class="portlet-title bg-success">
                            <div class="caption">
                                <i class="fa fa-fw fa-bars"></i> Employees
                            </div>
                        </div>
                        <div class="portlet-body bg-suc-new">
                           <div class="portlet">
                                 <div class="col-md-6"><a href="{{ URL::to('employee/add') }}"><img src="{{asset('assets/icons/add-employee.png')}}"><span class="info-hd"> New Employee</span></a></div>
                                 <div class="col-md-6" ><a href="{{ URL::to('employee') }}"><img src="{{asset('assets/icons/view-employee.png')}}"><span class="info-hd"> View Employee</span></a> </div>
                            </div>
                            <p></br></br></p>
                        </div>
                        
                    </div>-->
                    
                </div>
                <div class="col-md-6 sortable">
                    <!-- BEGIN Portlet PORTLET-->
                    <div class=" portlet box">
                         <div class="portlet-title bg-success">
                            <div class="caption">
                                <i class="fa fa-fw fa-bars"></i> Suppliers
                            </div>
                        </div>
                        <div class="portlet-body bg-suc-new">
                           <div class="portlet">
                                 <div class="col-md-6"><a href="" data-toggle="modal" data-target="#supplier_modal"><img src="{{asset('assets/icons/add-supplier.png')}}"><span class="info-hd"> New Supplier</span></a></div>
                                 <div class="col-md-6" ><a href="{{ URL::to('account_enquiry/sup') }}"><img src="{{asset('assets/icons/view-supplier.png')}}"><span class="info-hd"> View Supplier</span></a> </div>
                            </div>
                            <p></br></br></p>
                        </div>
                        
                    </div>

                    <div class=" portlet box">
                         <div class="portlet-title bg-dng">
                            <div class="caption">
                                <i class="fa fa-fw fa-bars"></i> Employees
                            </div>
                        </div>
                        <div class="portlet-body bg-suc-new">
                           <div class="portlet">
                                 <div class="col-md-6"><a href="{{ URL::to('employee/add') }}"><img src="{{asset('assets/icons/add-employee.png')}}"><span class="info-hd"> New Employee</span></a></div>
                                 <div class="col-md-6" ><a href="{{ URL::to('employee') }}"><img src="{{asset('assets/icons/view-employee.png')}}"><span class="info-hd"> View Employee</span></a> </div>
                            </div>
                            <p></br></br></p>
                        </div>
                        
                    </div>
                    
                    <!--<div class=" portlet box">
                        <div class="portlet-title bg-dng">
                            <div class="caption">
                               <i class="fa fa-fw fa-bars"></i> Reports
                            </div>
                        </div>
                        <div class="portlet-body bg-dng-new">
                            <div class="portlet">
                                 <div class="col-md-6"><a href="{{ URL::to('sales_invoice') }}"><img src="{{asset('assets/icons/sales.png')}}"><span class="info-hd"> Sales</span></a></div>
                                 <div class="col-md-6" ><a href="{{ URL::to('stock_ledger') }}"><img src="{{asset('assets/icons/stock.png')}}"><span class="info-hd"> Stock</span></a> </div>
                            </div>
                            <p></br></br></p>
                        </div>
                    </div>
                    
                    <div class=" portlet box">
                        <div class="portlet-title bg-warning">
                            <div class="caption">
                                <i class="fa fa-fw fa-bars"></i> Payroll
                            </div>
                        </div>
                        <div class="portlet-body bg-wrn-new">
                            <div class="portlet">
                                 <div class="col-md-6"><a href="{{ URL::to('wage_entry') }}"><img src="{{asset('assets/icons/wage-entry.png')}}"><span class="info-hd"> Wage Entry</span></a></div>
                                 <div class="col-md-6" ><a href="{{ URL::to('payroll_report') }}"><img src="{{asset('assets/icons/reports.png')}}"><span class="info-hd"> Reports</span></a> </div>
                            </div>
                            <p></br></br></p>
                        </div>
                    </div>-->
                    
                </div>

                  <div class=" portlet box">
                        <div class="portlet-title bg-warning">
                            <div class="caption">
                                <i class="fa fa-fw fa-bars"></i> Bank
                            </div>
                        </div>
                        <div class="portlet-body bg-dng-new">
                            <div class="portlet">
                                 <div class="col-md-3" ><a href="{{ URL::to('account_enquiry/bank') }}"><img src="{{asset('assets/icons/bank.png')}}"><span class="info-hd">Bank Accounts</span></a></div>
                                 <div class="col-md-3" ><a href="{{ URL::to('customer_receipt') }}"><img src="{{asset('assets/icons/customer-receipt.png')}}"><span class="info-hd">Customer Receipt</span></a> </div>
                                 <div class="col-md-3" ><a href="{{ URL::to('supplier_payment') }}"><img src="{{asset('assets/icons/supplier-payment.png')}}"><span class="info-hd">Supplier Payment</span></a></div>
                                 <div class="col-md-3" ><a href="{{ URL::to('account_enquiry') }}"><img src="{{asset('assets/icons/bank-statement.png')}}"><span class="info-hd">Bank Statement </span></a></div>
                            </div>
                            <p></br></br></p>
                        </div>
                        
                </div>

                <div class=" portlet box">
                        <div class="portlet-title bg-success">
                            <div class="caption">
                                <i class="fa fa-fw fa-bars"></i> Cash
                            </div>
                        </div>
                        <div class="portlet-body bg-dng-new">
                            <div class="portlet">
                                 <div class="col-md-6" ><a href="{{ URL::to('account_enquiry/cash') }}"><img src="{{asset('assets/icons/cash-statement.png')}}"><span class="info-hd">Cash Accounts</span></a></div>
                                 <div class="col-md-6" ><a href="{{ URL::to('pettycash') }}"><img src="{{asset('assets/icons/petty-cash.png')}}"><span class="info-hd">Petty Cash</span></a> </div>
                                 
                            </div>
                            <p></br></br></p>
                        </div>
                        
                </div>

                <div class=" portlet box">
                        <div class="portlet-title bg-primary">
                            <div class="caption">
                                <i class="fa fa-fw fa-bars"></i> Financial Report 
                            </div>
                        </div>
                        <div class="portlet-body bg-dng-new">
                            <div class="portlet">
                                 @can('stmnt')
                                 <div class="col-md-3" ><a href="{{ URL::to('account_enquiry') }}"><img src="{{asset('assets/icons/reports.png')}}"><span class="info-hd"> Account Statement </span></a></div>
                                @endcan
                                 @can('trial-bal')
                                 <div class="col-md-3" ><a href="{{ URL::to('trial_balance') }}"><img src="{{asset('assets/icons/trial.png')}}"><span class="info-hd">Trial Balance</span></a> </div>
                                 @endcan
                                  @can('b-sheet')
                                 <div class="col-md-3" ><a href="{{ URL::to('balance_sheet') }}"><img src="{{asset('assets/icons/balance.png')}}"><span class="info-hd"> Balance Sheet </span></a></div>
                                @endcan
                                 @can('p-and-l')
                                 <div class="col-md-3" ><a href="{{ URL::to('profit_loss') }}"><img src="{{asset('assets/icons/profit-loss.png')}}"><span class="info-hd">Profit & Loss</span></a></div>
                                 @endcan
                                <!-- @can('ledger-mnt')
                                 <div class="col-md-3" ><a href="{{ URL::to('ledger_moments') }}"><img src="{{asset('assets/icons/reports.png')}}"><span class="info-hd">Ledger Moments</span></a></div>
                                 @endcan-->
                            </div>
                            <p></br></br></p>
                        </div>
                        
                </div>

                 <div class=" portlet box">
                        <div class="portlet-title bg-dng">
                            <div class="caption">
                                <i class="fa fa-fw fa-bars"></i> Inventory Report 
                            </div>
                        </div>
                        <div class="portlet-body bg-dng-new">
                            <div class="portlet">
                                 @can('qty-report')
                                 <div class="col-md-3" ><a href="{{ URL::to('quantity_report') }}"><img src="{{asset('assets/icons/sales.png')}}"><span class="info-hd">Quantity Report</span></a></div>
                                 @endcan
                                 @can('stock-ldgr')
                                 <div class="col-md-3" ><a href="{{ URL::to('stock_ledger') }}"><img src="{{asset('assets/icons/stock.png')}}"><span class="info-hd">Stock Ledger</span></a> </div>
                                 @endcan
                                 @can('profit-anl')
                                 <div class="col-md-3" ><a href="{{ URL::to('profit_analysis') }}"><img src="{{asset('assets/icons/profit.png')}}"><span class="info-hd">Profit Analysis</span></a></div>
                                 @endcan
                                 @can('trans-report')
                                 <div class="col-md-3" ><a href="{{ URL::to('transaction_list') }}"><img src="{{asset('assets/icons/trans.png')}}"><span class="info-hd">Transaction List</span></a></div>
                                  @endcan
                            </div>
                            <p></br></br></p>
                        </div>
                        
                </div>

            

                

            </div>
            <br/>
    @endif
	@can('advanced-dashboard')	
    
    <div  class="portlet-title" id="advanced-dashboard">
            <div class="row">
                <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                    <div class="panel">
                        <div class="panel-heading"  style="background-color:#86C5FF">
                            <h3 class="heading_text panel-title" style="color:#428BCA !important;"> <i class="fa fa-fw fa-bar-chart-o"></i> <font color="white"> Sales</font></h3>
                            <span class="pull-right line-bar-charts">
                                <button class="btn btn-sm btn-link chart_switch_sales" style="background-color:#f2f9ff" data-chart="bar">Bar Chart</button>
                                <button class="btn btn-sm btn-default chart_switch_sales" style="background-color:#f2f9ff" data-chart="line">Line Chart
                                </button>
                            </span>
                        </div>
                        <div class="panel-body" style="background-color:#d6eef8">
                            <div id="sales-line-bar" style="height:280px"></div>
                        </div>
                    </div>
                </div>
                
				<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                    <div class="panel panel-widget">
                        <div class="panel-heading" style="background-color:#86C5FF">
                            <h3 class="panel-title" style="color:#428BCA"><i class="fa fa-fw fa-users"></i><font color="white"> Customers</font></h3>
                        </div>
                        <div class="panel-body" style="background-color:#d6eef8" style="padding:0px !important;">
						<div class="slimScrollDiv" style="position: relative; overflow-y: scroll; width: auto; height: 280px;">
                            <ul class="basic-list">
                            <?php foreach($custdat as $row) { ?>
                                <li> {{$row->master_name}}
                                    <span class="right label label-primary pull-right">{{$row->cl_balance}}</span>
                                </li>
                            <?php } ?>
                            </ul>
						</div>
                        </div>
                    </div>
                </div>
				
				<div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                    <div class="panel">
                        <div class="panel-heading"  style="background-color:#86C5FF">
                            <h3 class="heading_text panel-title" style="color:#428BCA !important;"> <i class="fa fa-fw fa-bar-chart-o"></i> <font color="white"> Purchase</font></h3>
                            <span class="pull-right line-bar-charts">
                                <button class="btn btn-sm btn-link chart_switch_pur" style="background-color:#f2f9ff" data-chart="bar">Bar Chart</button>
                                <button class="btn btn-sm btn-default chart_switch_pur" style="background-color:#f2f9ff" data-chart="line">Line Chart
                                </button>
                            </span>
                        </div>
                        <div class="panel-body" style="background-color:#d6eef8">
							<div id="purchase-line-bar" style="height:280px"></div>
                        </div>
                    </div>
                </div>
                
				
				<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                    <div class="panel panel-widget">
                        <div class="panel-heading" style="background-color:#86C5FF">
                            <h3 class="panel-title" style="color:#428BCA"><i class="fa fa-fw fa-users"></i><font color="white"> Suppliers</font></h3>
                        </div>
                        <div class="panel-body" style="background-color:#d6eef8" style="padding:0px !important;">
						<div class="slimScrollDiv" style="position: relative; overflow-y: scroll; width: auto; height: 280px;">
                            <ul class="basic-list">
                            <?php foreach($supdata as $row) { ?>
                                <li> {{$row->master_name}}
                                    <span class="right label label-warning pull-right">{{$row->cl_balance}}</span>
                                </li>
                            <?php } ?>
                            </ul>
                        </div>
                        </div>
                    </div>
                </div>
                
            </div>
            
                       
            <div class="row">
                                
                <div class="col-md-12">
                     <div class="panel panel-default1">
                        <div class="panel-heading" style="background-color:#86C5FF">
                            <h3 class="panel-title" style="padding-bottom:10px; color:#428BCA !important;"><i class="fa fa-fw fa-list-alt"></i><font color="white"> PDC Details</font></h3>
                            <ul class="nav nav-tabs nav-float" role="tablist">
                                <li class="active">
                                    <a href="#pdcr" role="tab" data-toggle="tab">PDCR</a>
                                </li>
                                <li>
                                    <a href="#pdci" role="tab" data-toggle="tab">PDCI</a>
                                </li>
                            </ul>
                        </div>
                        <div class="panel-body" style="background-color:#d6eef8">
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

            

		</div>
		@endcan
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
                        <h4 class="modal-title danger"><i class="fa fa-fw fa-outdent"></i> PDC(Issued) Details</h4>
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
                        <h4 class="modal-title"><i class="fa fa-fw fa-indent"></i> PDC(Received) Details</h4>
                    </div>
                    <div class="modal-body" id="pdcrData">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>  
        <div id="vehicle_modal" class="modal fade animated" role="dialog">
            <div class="modal-dialog">
                <div class="panel panel-danger">
                    <div class="panel-heading">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title"><i class="fa fa-fw fa-indent"></i> Vehicle service Validity Details</h4>
                    </div>
                    <div class="modal-body" id="vehiData">
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
        
		<div id="customer_modal" class="modal fade animated" role="dialog">
			 <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Create Customer</h4>
                                        </div>
										
                                        <div class="modal-body" id="customerData">
											<div class="panel panel-success filterable" id="newCustomerFrm">
											<div class="panel-heading">
												<h3 class="panel-title">
													<i class="fa fa-fw fa-columns"></i> New Customer
												</h3>
											</div>
											<div class="panel-body"> 
											
												<div class="col-xs-10">
													<div id="addressDtls">
													<form class="form-horizontal" role="form" method="POST" name="frmCustomer" id="frmCustomer">
													<input type="hidden" name="_token" value="{{ csrf_token() }}">
													<input type="hidden" name="category" value="CUSTOMER">
													<input type="hidden" class="form-control" id="account_id" name="account_id">
														<hr/>
														
														<div class="form-group">
															<label for="input-text" class="col-sm-5 control-label">Customer Name</label>
															<div class="col-sm-7">
																<input type="text" class="form-control" id="customer_name" name="customer_name" placeholder="Customer Name">
															</div>
														</div>
														
														<div class="form-group">
															<label for="input-text" class="col-sm-5 control-label">Address</label>
															<div class="col-sm-7">
																<input type="text" class="form-control" id="address" name="address" placeholder="Address1, Address2">
															</div>
														</div>

														<input type="hidden" name="country_id" id="country_id">
														<input type="hidden" name="area_id" id="area_id">
														

														
														
														<div class="form-group">
															<label for="input-text" class="col-sm-5 control-label">Phone</label>
															<div class="col-sm-7">
																<input type="text" class="form-control" id="phone" name="phone" placeholder="Phone">
															</div>
														</div>
														
														
														<div class="form-group">
															<label for="input-text" class="col-sm-5 control-label">Email</label>
															<div class="col-sm-7">
																<input type="text" class="form-control" id="email" name="email" placeholder="Email">
															</div>
														</div>
														
                                                         
														
								                            <input type="hidden" name="contact_name" id="contact_name">
								                        
														
														
														<div class="form-group">
															<label for="input-text" class="col-sm-5 control-label">TRN No</label>
															<div class="col-sm-7">
																<input type="text" class="form-control" id="vat_no" name="vat_no" placeholder="TRN No.">
															</div>
														</div>
                                                        
														
														<div class="form-group">
															<label for="input-text" class="col-sm-5 control-label"></label>
															<div class="col-sm-7">
																<button type="button" class="btn btn-primary" id="create">Create</button>
															</div>
														</div>
													 </form>
													</div>
													
													<div id="sucessmsg"><br/>
														<div class="alert alert-success">
															<p>
																Customer created successfully.
															</p>
														</div>
													</div>
												</div>
											</div>
										</div>			
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" id="closeCus" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
		</div>
		
		<div id="supplier_modal" class="modal fade animated" role="dialog">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal">&times;</button>
									<h4 class="modal-title">Create Supplier</h4>
								</div>
								<div class="modal-body" id="supplierData">
									<div class="panel panel-success filterable" id="newSupplierFrm">
									<div class="panel-heading">
										<h3 class="panel-title">
											<i class="fa fa-fw fa-columns"></i> New Supplier
										</h3>
										
									</div>
									<div class="panel-body">
						
										<div class="col-xs-10">
											<div id="addressDtlsSupp">
											<form class="form-horizontal" role="form" method="POST" name="frmSupplier" id="frmSupplier">
											<input type="hidden" name="_token" value="{{ csrf_token() }}">
											<input type="hidden" name="category" value="SUPPLIER">
											<input type="hidden" class="form-control" id="account_id" name="account_id">
											
												<hr/>
												<div class="form-group">
													<label for="input-text" class="col-sm-5 control-label">Supplier Name</label>
													<div class="col-sm-7">
														<input type="text" class="form-control" id="supplier_name" name="supplier_name" placeholder="Customer Name">
													</div>
												</div>
												
												<div class="form-group">
													<label for="input-text" class="col-sm-5 control-label">Address</label>
													<div class="col-sm-7">
														<input type="text" class="form-control" id="address" name="address" placeholder="Address1, Address2">
													</div>
												</div>
												
												
								                    <input type="hidden" name="country_id" id="country_id">
								                
												
												
								                    <input type="hidden" name="area_id" id="area_id">
								               
												
														
												<div class="form-group">
													<label for="input-text" class="col-sm-5 control-label">Phone</label>
													<div class="col-sm-7">
														<input type="text" class="form-control" id="phone" name="phone" placeholder="Phone">
													</div>
												</div>
												
												
												<div class="form-group">
													<label for="input-text" class="col-sm-5 control-label">Email</label>
													<div class="col-sm-7">
														<input type="text" class="form-control" id="email" name="email" placeholder="Email">
													</div>
												</div>
												

												
								                    <input type="hidden" name="contact_name" id="contact_name">
								                

                                               
												<div class="form-group">
													<label for="input-text" class="col-sm-5 control-label">TRN No</label>
													<div class="col-sm-7">
														<input type="text" class="form-control" id="vat_no" name="vat_no" placeholder="TRN No.">
													</div>
												</div>
												

												<div class="form-group">
													<label for="input-text" class="col-sm-5 control-label"></label>
													<div class="col-sm-7">
														<button type="button" class="btn btn-primary" id="createSupp">Create</button>
													</div>
												</div>
											 </form>
											</div>
											
											<div id="sucessmsgSupp"><br/>
												<div class="alert alert-success">
													<p>
														Supplier created successfully. 
													</p>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-default" id="closeSup" data-dismiss="modal">Close</button>
								</div>
							</div></div>
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
<?php if($parameter1->pdc_alert==1){ ?>
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

  <?php } ?>
    
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
				
				sales_line_bar_chart.transform('bar');
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
                        pattern: ['#ffb65f', '#2ca02c', '#d62728', '#9467bd', '#8c564b', '#e377c2', '#7f7f7f', '#bcbd22', '#17becf', '#428bca']
                    }
                });
				
				purchase_line_bar_chart.transform('bar');

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
            var colour = "#d9534f";
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
<script>

	$('#closeCus').on('click', function(e) {
     window.location.href="{{ url('dashboard') }}";
    });
  $('#closeSup').on('click', function(e) {
     window.location.href="{{ url('dashboard') }}";
    });
$('#sucessmsg').toggle(); $('#sucessmsgSupp').toggle();
$('#create').on('click', function(e) { 
		
		var ac = $('#frmCustomer #account_id').val();
		var name = $('#frmCustomer #customer_name').val();
		var adrs = $('#frmCustomer #address').val();
		var ar = $('#frmCustomer #area_id option:selected').val();
		var cn = $('#frmCustomer #country_id option:selected').val();
		var ph = $('#frmCustomer #phone').val();
		var vt = $('#frmCustomer #vat_no').val();
		var pn = $('#frmCustomer #contact_name').val();
		var em = $('#frmCustomer #email').val();
		var dpt = $('#frmCustomer #department_id option:selected').val();
		
		if(name=="") {
			alert('Customer name is required!');
			return false;
		} else {		
			$('#addressDtls').toggle();
			
			$.ajax({
				url: "{{ url('account_master/ajax_create/') }}",
				type: 'get',
				data: 'account_id='+ac+'&master_name='+name+'&address='+adrs+'&area_id='+ar+'&country_id='+cn+'&phone='+ph+'&vat_no='+vt+'&category=CUSTOMER&contact_name='+pn+'&email='+em+'&department_id='+dpt,
				success: function(data) { 
					if(data > 0) {
						$('#sucessmsg').toggle();
					} else if(data == 0){
						$('#addressDtls').toggle();
						alert('Customer name already exist!');
						return false;
					} else {
						$('#addressDtls').toggle();
						alert('Something went wrong, Account failed to add!');
						return false;
					}
				}
			})
		}
	});
	
	$('#createSupp').on('click', function(e){
		
		var ac = $('#frmSupplier #account_id').val();
		var name = $('#frmSupplier #supplier_name').val();
		var adrs = $('#frmSupplier #address').val();
		var ar = $('#frmSupplier #area_id option:selected').val();
		var cn = $('#frmSupplier #country_id option:selected').val();
		var ph = $('#frmSupplier #phone').val();
		var vt = $('#frmSupplier #vat_no').val();
		var pn = $('#frmSupplier #contact_name').val();
		var em = $('#frmSupplier #email').val();
		var dpt = $('#frmSupplier #department_id option:selected').val();
		
		if(name=="") {
			alert('Supplier name is required!');
			return false;
		} else {		
			$('#addressDtlsSupp').toggle();
			
			$.ajax({
				url: "{{ url('account_master/ajax_create/') }}",
				type: 'get',
				data: 'account_id='+ac+'&master_name='+name+'&address='+adrs+'&area_id='+ar+'&country_id='+cn+'&phone='+ph+'&vat_no='+vt+'&category=SUPPLIER&contact_name='+pn+'&email='+em+'&department_id='+dpt,
				success: function(data) { console.log(data);
					if(data > 0) {
						$('#sucessmsgSupp').toggle();
					} else if(data == 0) {
						$('#addressDtlsSupp').toggle();
						//$('#sucessmsg').toggle();
						alert('Supplier name already exist!');
						return false;
					} else {
						$('#addressDtlsSupp').toggle();
						alert('Something went wrong, Account failed to add!');
						return false;
					}
				}
			})
		}
	});
	
</script>
        
    <!-- end of page level js -->
@stop

