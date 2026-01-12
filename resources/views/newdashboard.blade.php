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
								 <div class="col-md-3" ><a href="{{ URL::to('customers_do/add') }}"><img src="{{asset('assets/icons/add-do.png')}}"><span class="info-hd"> New @php echo (Session::get('trip_entry')==1)?'DE':'DO'; @endphp</span></a></div>
								 <div class="col-md-3" ><a href="{{ URL::to('customers_do') }}"><img src="{{asset('assets/icons/view-do.png')}}"><span class="info-hd"> View @php echo (Session::get('trip_entry')==1)?'DE':'DO'; @endphp</span></a></div>
                            </div>
							<p></br></br></p><p></br></p>
							<div class="portlet">
								 <div class="col-md-3" ><a href="{{ URL::to('sales_order/add') }}"><img src="{{asset('assets/icons/add-order.png')}}"><span class="info-hd"> New Order</span></a></div>
								 <div class="col-md-3" ><a href="{{ URL::to('sales_order') }}"><img src="{{asset('assets/icons/view-order.png')}}"><span class="info-hd"> View Order</span></a> </div>
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
								 <div class="col-md-3" ><a href="{{ URL::to('purchase_order/add') }}"><img src="{{asset('assets/icons/add-proforma.png')}}"><span class="info-hd"> New Order</span></a></div>
								 <div class="col-md-3" ><a href="{{ URL::to('purchase_order') }}"><img src="{{asset('assets/icons/view-proforma.png')}}"><span class="info-hd"> View Order</span></a></div>
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
								 <div class="col-md-6"><a href="{{ URL::to('account_master/add') }}"><img src="{{asset('assets/icons/add-customer.png')}}"><span class="info-hd"> New Customer</span></a></div>
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
					
					
					<div class=" portlet box">
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
                        
                    </div>
					
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
								 <div class="col-md-6"><a href="{{ URL::to('account_master/add') }}"><img src="{{asset('assets/icons/add-supplier.png')}}"><span class="info-hd"> New Supplier</span></a></div>
								 <div class="col-md-6" ><a href="{{ URL::to('account_enquiry/sup') }}"><img src="{{asset('assets/icons/view-supplier.png')}}"><span class="info-hd"> View Supplier</span></a> </div>
                            </div>
							<p></br></br></p>
                        </div>
                        
                    </div>
					
                    <div class=" portlet box">
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
                    </div>
					
                </div>
            </div>
				
			</div>
			
        </section>
		<?php } else { ?>
		<section class="content">
            <div class="row ui-sortable" id="sortable_portlets">
                <div class="col-md-12 sortable">
                    <!-- BEGIN Portlet PORTLET-->
					<div class="col-md-6 sortable">
                    <!-- BEGIN Portlet PORTLET-->
					
					<div class=" portlet box">
						<div class="portlet-title bg-info">
                            <div class="caption">
                                <i class="fa fa-fw fa-bars"></i> Job Invoice 
                            </div>
                        </div>
                        <div class="portlet-body bg-inf-new">
							<div class="portlet">
								 <div class="col-md-6" ><a href="{{ URL::to('job_invoice/add') }}"><img src="{{asset('assets/icons/add-invoice.png')}}"><span class="info-hd"> New Invoice</span></a></div>
								 <div class="col-md-6" ><a href="{{ URL::to('job_invoice') }}"><img src="{{asset('assets/icons/view-invoice.png')}}"><span class="info-hd"> View Invoice</span></a> </div>
                            </div>
							<p></br></br></p>
                        </div>
                        
                    </div>
					
					<div class=" portlet box">
						<div class="portlet-title bg-warning">
                            <div class="caption">
                                <i class="fa fa-fw fa-bars"></i> Vehicle Enquiry
                            </div>
                        </div>
                        <div class="portlet-body bg-wrn-new">
                            <div class="portlet">
								 <div class="col-md-6"><a href="{{ URL::to('vehicle/add') }}"><img src="{{asset('assets/icons/new-job.png')}}"><span class="info-hd"> New Vehicle</span></a></div>
								 <div class="col-md-6" ><a href="{{ URL::to('vehicle/getenquiry') }}"><img src="{{asset('assets/icons/view-job.png')}}"><span class="info-hd"> Enquiry</span></a> </div>
                            </div>
							<p></br></br></p>
                        </div>
                    </div>
					<div class=" portlet box">
						<div class="portlet-title bg-danger">
                            <div class="caption">
                                <i class="fa fa-fw fa-bars"></i> Purchase 
                            </div>
                        </div>
                        <div class="portlet-body bg-dng-new">
							<div class="portlet">
								 <div class="col-md-6" ><a href="{{ URL::to('purchase_invoice/add') }}"><img src="{{asset('assets/icons/add-prchs.png')}}"><span class="info-hd"> New Purchase</span></a></div>
								 <div class="col-md-6" ><a href="{{ URL::to('purchase_invoice') }}"><img src="{{asset('assets/icons/view-prchs.png')}}"><span class="info-hd"> View Purchase</span></a> </div>
                            </div>
							<p></br></br></p>
                        </div>
                        
                    </div>
					
					
                    <div class=" portlet box">
						<div class="portlet-title bg-warning">
                            <div class="caption">
                                <i class="fa fa-fw fa-bars"></i> Customers
                            </div>
                        </div>
                        <div class="portlet-body bg-wrn-new">
                            <div class="portlet">
								 <div class="col-md-6"><a href="{{ URL::to('account_master/add') }}"><img src="{{asset('assets/icons/add-cust.png')}}"><span class="info-hd"> New Customer</span></a></div>
								 <div class="col-md-6" ><a href="{{ URL::to('account_master') }}"><img src="{{asset('assets/icons/view-cust.png')}}"><span class="info-hd"> View Customer</span></a> </div>
                            </div>
							<p></br></br></p>
                        </div>
                    </div>
					
                    <div class=" portlet box">
                        <div class="portlet-title bg-primary">
                            <div class="caption">
                                <i class="fa fa-fw fa-bars"></i> Item Master
                            </div>
                        </div>
                        <div class="portlet-body bg-pr-new">
                            
                            <div class="portlet">
								 <div class="col-md-6"><a href="{{ URL::to('itemmaster/add') }}"><img src="{{asset('assets/icons/new-item.png')}}"><span class="info-hd"> New Item</span></a></div>
								 <div class="col-md-6" ><a href="{{ URL::to('itemenquiry') }}"><img src="{{asset('assets/icons/view-items.png')}}"><span class="info-hd"> View Item</span></a> </div>
                            </div>
							<p></br></br></p>
                      
                        </div>
                    </div>
					
					
					<div class=" portlet box">
						 <div class="portlet-title bg-success">
                            <div class="caption">
                                <i class="fa fa-fw fa-bars"></i> Employees
                            </div>
                        </div>
                        <div class="portlet-body bg-suc-new">
                           <div class="portlet">
								 <div class="col-md-6"><a href="{{ URL::to('employee/add') }}"><img src="{{asset('assets/icons/add-emp.png')}}"><span class="info-hd"> New Employee</span></a></div>
								 <div class="col-md-6" ><a href="{{ URL::to('employee') }}"><img src="{{asset('assets/icons/view-employee.png')}}"><span class="info-hd"> View Employee</span></a> </div>
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
                                <i class="fa fa-fw fa-bars"></i> Job Order
                            </div>
                        </div>
                        <div class="portlet-body bg-wrn-new">
                            <div class="portlet">
								 <div class="col-md-6"><a href="{{ URL::to('job_order/add') }}"><img src="{{asset('assets/icons/new-job.png')}}"><span class="info-hd"> New Job</span></a></div>
								 <div class="col-md-6" ><a href="{{ URL::to('job_order') }}"><img src="{{asset('assets/icons/view-job.png')}}"><span class="info-hd"> View Job</span></a> </div>
                            </div>
							<p></br></br></p>
                        </div>
                    </div>
				
					<div class=" portlet box">
                        <div class="portlet-title bg-primary">
                            <div class="caption">
                                <i class="fa fa-fw fa-bars"></i> Job Estimate
                            </div>
                        </div>
                        <div class="portlet-body bg-pr-new">
                            
                            <div class="portlet">
								 <div class="col-md-6"><a href="{{ URL::to('job_estimate/add') }}"><img src="{{asset('assets/icons/add-estimate.png')}}"><span class="info-hd"> New Estimate</span></a></div>
								 <div class="col-md-6" ><a href="{{ URL::to('job_estimate') }}"><img src="{{asset('assets/icons/view-estimate.png')}}"><span class="info-hd"> View Estimate</span></a> </div>
                            </div>
							<p></br></br></p>
                      
                        </div>
                    </div>
					
                    <div class=" portlet box">
						 <div class="portlet-title bg-success">
                            <div class="caption">
                                <i class="fa fa-fw fa-bars"></i> Suppliers
                            </div>
                        </div>
                        <div class="portlet-body bg-suc-new">
                           <div class="portlet">
								 <div class="col-md-6"><a href="{{ URL::to('account_master/add') }}"><img src="{{asset('assets/icons/add-supplier.png')}}"><span class="info-hd"> New Supplier</span></a></div>
								 <div class="col-md-6" ><a href="{{ URL::to('account_master') }}"><img src="{{asset('assets/icons/view-supplier.png')}}"><span class="info-hd"> View Supplier</span></a> </div>
                            </div>
							<p></br></br></p>
                        </div>
                        
                    </div>
					
                    <div class=" portlet box">
                        <div class="portlet-title bg-dng">
                            <div class="caption">
                               <i class="fa fa-fw fa-bars"></i> Reports
                            </div>
                        </div>
                        <div class="portlet-body bg-dng-new">
                            <div class="portlet">
								 <div class="col-md-6"><a href="{{ URL::to('sales_report') }}"><img src="{{asset('assets/icons/sales.png')}}"><span class="info-hd"> Sales</span></a></div>
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
                    </div>
					
                </div>
                   
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
		<?php if(isset($parameter1->vehicle_dashboard) && $parameter1->vehicle_dashboard==1)  { ?>
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
		<?php } ?>	
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
		
		<div id="depending_modal" class="modal fade animated" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Daily Entry Pending Details</h4>
					</div>
					<div class="modal-body" id="dePendingData">No pending details were found!
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
		
		
		<div id="servicedue_modal" class="modal fade animated" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Vehicle Sevice Due Details</h4>
					</div>
					<div class="modal-body" id="customerData">
						<table class="table table-striped" id="tableBank">
							<thead>
								<tr>
									<th>SI#</th>
									<th>Vehicle No</th>
									<th>Vehicle Name</th>
									<th>Modal</th>
									<th>Service Due</th>
								</tr>
							</thead>
								<tbody>
								<tr>
									<td>1</td>
									<td>DB-2076</td>
									<td>Vista</td>
									<td>SUV</td>
									<td>12-10-2022</td>
								</tr>
								<tr>
									<td>2</td>
									<td>DB-8742</td>
									<td>Innova</td>
									<td>MPV</td>
									<td>22-10-2018</td>
								</tr>
								<tr>
									<td>3</td>
									<td>DB-2209</td>
									<td>Accord</td>
									<td>Sedan</td>
									<td>21-10-2018</td>
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
   
    
    $('#vehicle_modal').modal('show');
    var pdcrUrl = "{{ url('dashboard/get_vehicle_alert/') }}"; 
   $('#vehiData').load(pdcrUrl, function(result) {
      $('#myModal').modal({show:true});
   });
});   
$(document).ready(function () { //alert('hi');
	//$('#pdci_modal').modal('show');
	
	//$('#pdcr_modal').modal('show');
	
	//$('#servicedue_modal').modal('show');
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
   
   /* $('#depending_modal').modal('show');
   var deUrl = "{{ url('customers_do/get_pending/') }}"; 
   $('#dePendingData').load(deUrl, function(result) {
	  $('#myModal').modal({show:true});
   }); */
   
});   
</script>
		
    <!-- end of page level js -->
@stop