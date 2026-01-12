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
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/invoice.css')}}">
    <!--end of page level css-->
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>Quotation</h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="fa fa-fw fa-shield"></i> Inventory
                    </a>
                </li>
				<li> Quotation</li>
                <li class="active">
                   <a href="#">Print</a>
                </li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content p-l-r-15" id="invoice-stmt">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <i class="fa fa-fw fa-credit-card"></i> Quotation
                    </h3>
                    <span class="pull-right">
                                    <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                                </span>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-6 col-sm-12 col-xs-12 invoice_bg">
                                <h3>NumakPro ERP</h3>
                                <h4><strong>Billing Details:</strong></h4>
                                <address>
                                    Lewis Doe
                                    <br/> 6889 Lunette Street
                                    <br/> Melbourne,Austria
                                    <br/> <strong>Phone:</strong>12-345-678
                                    <br/> <strong>Mail Id:</strong> Adelle_Champlin@yahoo.com
                                </address>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12 invoice_bg text-right">
                                <div class="pull-right">
                                    <h4><strong>#678956 / 25 Sep 2016</strong></h4>
                                    <h4><strong>Invoice Info:</strong></h4>
                                    <address>
                                        Tom Percy
                                        <br/> 3946 Penn Street
                                        <br/> Ohio,USA
                                        <br/> <strong>Phone:</strong> 32-666-756
                                        <br/> <strong>Mail Id:</strong> Lucy_Maggio16@yahoo.com
                                    </address>
                                    <span></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
						<div class="pull-center"><h3>Quotation</h3></div>
						Dear sir,
						<br/>
						Please find the quotation details.<br/><br/>
                            <div class="table-responsive">
                                <table class="table table-striped table-condensed">
                                    <thead>
                                    <tr class="bg-primary">
										<th>
                                            <strong>SI.#</strong>
                                        </th>
										<th>
                                            <strong>Item Code</strong>
                                        </th>
                                        <th>
                                            <strong>Description</strong>
                                        </th>
                                        <th>
                                            <strong>Unit</strong>
                                        </th>
										
                                        <th class="text-center">
                                            <strong>
                                                Quantity
                                            </strong>
                                        </th>
                                        <th>Cost/Unit</th>
										<th>Vat %</th>
                                        <th class="text-right">
                                            <strong>Line Total</strong>
                                        </th>
										<th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
										<td>12GH</td>
										<td>ITKJ 79879</td>
                                        <td >
                                            Samsung Galaxy Grand
                                        </td>
                                        <td >$700</td>
                                        <td class="text-center" >1</td>
                                        <td></td>
                                        <td class="text-right" >$700</td>
                                        <td class="text-right">$600</td>
										<td></td>
                                    </tr>
                                    
                                    </tbody>
                                    <tfoot>
                                    <tr>
										 <td class="highrow"></td>
										  <td class="highrow"></td>
                                        <td class="highrow"></td>
                                        <td class="highrow"></td>
                                        <td class="highrow text-center"></td>
                                        <td class="highrow text-right">
                                            <strong>
                                                Sub Total: &nbsp;
                                            </strong>
                                        </td>
                                        <td class="highrow text-right">
                                            <strong>$3638</strong>
                                        </td>
                                        <td class="highrow text-right">
                                            <strong>$3638</strong>
                                        </td>
										<td></td>
                                    </tr>
                                    <tr>
										 <td class="emptyrow"></td>
										 <td class="emptyrow"></td>
                                        <td class="emptyrow"></td>
                                        <td class="emptyrow"></td>
                                        <td class="emptyrow text-center"></td>
                                        <td class="emptyrow text-right">
                                            <strong>
                                                Vat: &nbsp;
                                            </strong>
                                        </td>
                                        <td class="highrow text-right">
                                            <strong>$30</strong>
                                        </td>
                                        <td class="highrow text-right">
                                            <strong>$30</strong>
                                        </td>
										<td></td>
                                    </tr>
                                    <tr>
										 <td class="emptyrow"></td>
										 <td class="emptyrow"></td>
                                        <td class="emptyrow">
                                            <i class="livicon" data-name="barcode" data-size="60" data-loop="true"></i>
                                        </td>
                                        <td class="emptyrow"></td>
                                        <td class="emptyrow text-center"></td>
                                        <td class="emptyrow text-right">
                                            <strong>
                                                Total: &nbsp;
                                            </strong>
                                        </td>
                                        <td class="highrow text-right">
                                            <strong>$3668</strong>
                                        </td>
                                        <td class="highrow text-right">
                                            <strong>$3668</strong>
                                        </td>
										<td></td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                       <div class="col-md-12">
                            <h4><Strong>Terms and conditions:</Strong></h4>
                            <ul>
                                <li>An invoice must accompany products returned for warantty</li>
                                <li>Balance due within 10 days of invoice date,1.5% interest/month thereafter.</li>
                                <li>All goods returned for replacement/credit must be saleable condition with original
                                    packaging.
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="btn-section">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                                <span class="pull-right">
                                           
                                             <button type="button"
                                                     class="btn btn-responsive button-alignment btn-primary"
                                                     data-toggle="button">
                                                <span style="color:#fff;" onclick="javascript:window.print();">
                                                    <i class="fa fa-fw fa-print"></i>
                                                Print
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
