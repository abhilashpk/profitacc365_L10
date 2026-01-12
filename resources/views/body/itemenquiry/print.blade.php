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
            <h1>Item Enquiry</h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="glyphicon glyphicon-folder-close"></i> Inventory
                    </a>
                </li>
				<li> Item Enquiry</li>
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
                        <i class="fa fa-fw fa-credit-card"></i> {{$heading}} History
                    </h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-6 col-sm-12 col-xs-12 invoice_bg">
                                <h3>{{Session::get('company')}}</h3>
                                
                            </div>
                            
                        </div>
                        <div class="col-md-12">
						<div class="pull-center"><h4>Item Code: {{$details->item_code}}<br/>
						Item Name: {{$details->description}}</h4>
						</div>
						
                            <div class="table-responsive">
                                <table class="table table-striped table-condensed">
                                    <thead>
                                    <tr class="bg-primary">
										<th style="width:50px;">
                                            <strong>Inv.No.</strong>
                                        </th>
										<th>
                                            <strong>Ref.No.</strong>
                                        </th>
										<th>
                                            <strong>Cust/Supp Name</strong>
                                        </th>
										<th>
                                            <strong>Inv.Date</strong>
                                        </th>
                                        <th>
                                            <strong>Qty.</strong>
                                        </th>
										<th class="emptyrow text-right">
                                            <strong>Price</strong>
                                        </th>
										
										<th class="emptyrow text-right">
                                            <strong>Unit Desc.</strong>
                                        </th>
										<th class="emptyrow text-right">
                                            <strong>Cost Avg.</strong>
                                        </th>
										<th class="emptyrow" style="width:10px;"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
									@foreach($items as $item)
                                    <tr>
										<td>{{$item->voucher_no}}</td>
										<td>{{$item->reference_no}}</td>
                                        <td>{{$item->master_name}}</td>
										<td>{{date('d-m-Y',strtotime($item->voucher_date))}}</td>
										<td>{{$item->quantity}}</td>
                                        <td class="emptyrow text-right">{{ number_format($item->unit_price,2) }}</td>
                                        <td class="emptyrow text-right">{{ number_format($item->total_price,2) }}</td>
										<td class="emptyrow text-right">{{ number_format($item->total_price,2) }}</td>
										<td class="emptyrow"></td>
                                    </tr>
									@endforeach
									
                                    </tbody>
                                </table>
                            </div>
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
