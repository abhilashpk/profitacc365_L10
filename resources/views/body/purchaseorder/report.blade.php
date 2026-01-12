@extends('layouts/default')

   
{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
	<link rel="stylesheet" href="{{asset('assets/vendors/datetime/css/jquery.datetimepicker.css')}}">
    <link href="{{asset('assets/vendors/airdatepicker/css/datepicker.min.css')}}" rel="stylesheet" type="text/css">
	
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/buttons.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/colReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/dataTables.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/rowReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/scroller.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatablesmark.js/css/datatables.mark.min.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/responsive_datatables.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/datepicker.css')}}">
        <!--end of page level css-->
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <!--section starts-->
            <h1>
                Purchase Order
            </h1>
            <ol class="breadcrumb">
                <li>
                      <i class="fa fa-fw fa-shield"></i> Inventory
                </li>
				<li>
                    <a href="#">Purchase Order Report</a>
                </li>
            </ol>
			
        </section>
		
        <!--section ends-->
		@if(Session::has('message'))
		<div class="alert alert-success">
			<p>{{ Session::get('message') }}</p>
		</div>
		@endif
		
        <section class="content">
            <div class="row">
            <div class="col-lg-12">
               <div class="panel panel-info">
						
                        <div class="panel-heading clearfix">
                            <h3 class="panel-title pull-left m-t-6">
                                <i class="fa fa-fw fa-columns"></i> {{$voucherhead}}
                            </h3>
                        </div>
                        <div class="panel-body">
							<form class="form-horizontal" role="form" method="POST" name="frmPOReport" id="frmPOReport" action="{{ url('purchase_order/search') }}">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								<div class="row">
									<div class="col-xs-6">
										<span>Date From:</span>
										<input type="text" name="date_from" data-language='en' autocomplete="off" id="date_from" value="<?php echo $fromdate; ?>" class="form-control input-sm">
										<span>Date To:</span>
										<input type="text" name="date_to" data-language='en' autocomplete="off" id="date_to" value="<?php echo $todate; ?>" class="form-control input-sm">
									</div>
									<div class="col-xs-6">
										<span>Search By:</span>
										<select id="search_type" class="form-control select2" style="width:100%" name="search_type">
											<option value="summary" <?php if($type=='summary') echo 'selected';?>>Summary</option>
											<option value="summary_pending" <?php if($type=='summary_pending') echo 'selected';?>>Summary(Pending Order)</option>
											<option value="detail" <?php if($type=='detail') echo 'selected';?>>Detail</option>
											<option value="detail_pending" <?php if($type=='detail_pending') echo 'selected';?>>Detail(Pending Order)</option>
										</select>
										<span></span><br/>
										<button type="submit" class="btn btn-primary">Search</button>
									</div>
								</div>
						<?php if($reports!=null) { ?>
								
								<div class="table-responsive m-t-10">
								
									<table class="table horizontal_table table-striped" id="tableAcmaster">
										<thead>
											<tr>
												<th>Vchr.#</th>
												<th>Description</th>
												<th>Vchr.Date</th>
												<th class="text-right">Ord.Qty.</th>
												<th class="text-right">Inv.Qty.</th>
												<th class="text-right">Bal.Qty.</th>
												<th class="text-right">Value</th>
												<th></th>
											</tr>
										</thead>
										<tbody>
										
											@foreach($reports as $row)
											<?php 
												$invqty = $row->quantity - $row->balance_quantity;
												$value = $row->balance_quantity * $row->unit_price;
											?>
											<tr>
												<td>{{$row->voucher_no}}</td>
												<td>{{ $row->description }}</td>
												<td>{{ date('d-m-Y',strtotime($row->voucher_date)) }}</td>
												<td class="text-right">{{ $row->quantity }}</td>
												<td class="text-right">{{$invqty}}</td>
												<td class="text-right">{{$row->balance_quantity}}</td>
												<td class="text-right">{{number_format($value,2)}}</td>
												<td></td>
											</tr>
											
											@endforeach
										</tbody>
									</table>
						
									<!--<button type="button" class="btn btn-primary outstanding" onclick="getDetail()">Print</button>-->
								</div>
						<?php } ?>
								
							</form>
							
                        </div>
						
						
                       
                        
                      
						
                    </div>
            </div>
        </div>
       
       
            <!--main content-->
            <!-- row -->
        @include('layouts.right_sidebar')
        <!-- right side bar end -->
        </section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <!-- begining of page level js -->

<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/jquery.dataTables.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/dataTables.buttons.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/dataTables.colReorder.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/dataTables.responsive.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/dataTables.rowReorder.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/buttons.colVis.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/buttons.html5.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/buttons.bootstrap.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/buttons.print.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/dataTables.scroller.js')}}"></script>

<script src="{{asset('assets/vendors/mark.js/jquery.mark.js')}}" charset="UTF-8"></script>
<script src="{{asset('assets/vendors/datatablesmark.js/js/datatables.mark.min.js')}}" charset="UTF-8"></script>
<script src="{{asset('assets/js/custom_js/responsive_datatables.js')}}" type="text/javascript"></script>

<script src="{{asset('assets/vendors/datetime/js/jquery.datetimepicker.full.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.en.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/custom_js/advanceddate_pickers.js')}}"></script>
<!-- end of page level js -->


<script>
function getDetail() {	
	//document.frmPOReport.action = "{{ url('document_report/print')}}";
	//document.frmPOReport.submit();
}
</script>
@stop
