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
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/invoice.css')}}">
    <!--end of page level css-->
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>{{$titles['main_head']}}</h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="glyphicon glyphicon-list-alt"></i> Inventory
                    </a>
                </li>
				<li> {{$titles['main_head']}}</li>
                <li class="active">
                   <a href="#">Print</a>
                </li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content p-l-r-15" id="invoice-stmt">
            <div class="panel">
               
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table border="0" style="width:100%;">
							<tr>
								<td colspan="2"><img src="{{asset('assets/alameenlogo.jpg')}}" width="100%" />
								<h6>TRN No: <strong>100243385000003</strong></h6></td>
							</tr>
							<tr>
								<td colspan="2" align="center"><h4><b><u>{{$titles['subhead']}}</u></b></h4></td>
							</tr>
							<tr>
							
							<td>
								<div class="pull-left">
										Customer Details:
										<?php if($titles['subhead']=='Delivery Order') { ?>
											<h4><strong><?php if($details->customer_name!='') { 
												echo $details->customer_name; 
												$phone = $details->customer_phone;
											} else { 
												echo $details->supplier; 
												$phone = $details->phone;
												}
											?>
											</strong></h4>
										<?php } else { ?>
											<h4><strong>{{$details->supplier}}</strong></h4>
										<?php } ?>
										<address>
										{{$details->address}}
										<?php if($details->city!='') echo '<br/>'. $details->city.', '.$details->state.'<br/>'; ?>
											<strong>Phone: </strong> {{$phone}}
											<br/> <strong>TRN No: </strong> {{$details->vat_no}}
										</address>
										<!--<h4><strong>{{$details->supplier}}</strong></h4>
										<address>
										{{$details->address}}
											<br/> {{$details->city}}, {{$details->state}}
											<br/> <strong>Phone: </strong> {{$details->phone}}
											<br/> <strong>TRN No: </strong> {{$details->vat_no}}
										</address>
										<span></span>-->
								</div>
							</td>

							<td  style="width:28%">
									<div class="pull-left">
									Invoice Details:
										<h6>Invoice No: <strong>{{$details->voucher_no}}</strong></h6> 
										<h6>Date: {{date('d-m-Y',strtotime($details->voucher_date))}}</h6>
										<h6>DO No: {{$details->voucher_no}}</h6>
										<h6>DO Date: <?php echo ($details->lpo_date=='0000-00-00')?'':date('d-m-Y',strtotime($details->lpo_date));?></h6>
										<h6>LPO No: {{$details->lpo_no}}</h6>
									</div>
							</td>
							</tr>
							
							</table>
                        </div>
								@yield('contentnew')
								
								<table border="0" style="width:100%;">
							<tr><td><br/></td></tr>
							<tr>
								<td align="center" style="border-top:2px solid #d1312b; padding-top:5px;"><img src="{{asset('assets/footer.jpg')}}" width="70%" /></td>
							</tr>
						</table>
						
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
								
								<button type="button"
                                                     class="btn btn-responsive button-alignment btn-primary"
                                                     data-toggle="button">
                                                <span style="color:#fff;" onclick="javascript:self.close();">
                                                    <i class="fa fa-fw fa-times"></i>
                                                Back 
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
