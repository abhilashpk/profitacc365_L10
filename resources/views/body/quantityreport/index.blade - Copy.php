@extends('layouts/default')

    {{-- Page title --}}
    @section('title')
         
        @parent
    @stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
	
	<link href="{{asset('assets/vendors/bootstrap-multiselect/css/bootstrap-multiselect.css')}}" rel="stylesheet" type="text/css">
	
	
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
                Quantity Report
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="glyphicon glyphicon-folder-close"></i> Reports
                    </a>
                </li>
                <li>
                    <a href="#">Quantity Report</a>
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
                                <i class="fa fa-fw fa-columns"></i> Quantity Report
                            </h3>
                        </div>
                        <div class="panel-body">
							<form class="form-horizontal" role="form" method="POST" name="frmQuantityReport" id="frmQuantityReport" action="{{ url('quantity_report/search') }}">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								<div class="row">
									<div class="col-xs-6">
										<span>As on Date:</span>
										<input type="hidden" name="date_from" value="{{$fromdate}}">
										<input type="text" name="date_to" data-language='en' id="date_to" value="<?php echo $todate; ?>" class="form-control input-sm">
										<span>Quantity with:</span>
										<select id="quantity_type" class="form-control select2" style="width:100%" name="quantity_type">
											<option value="all" <?php if($type=='all') echo 'selected';?>>All</option>
											<option value="minus" <?php if($type=='minus') echo 'selected';?>>Minus</option>
											<option value="positive" <?php if($type=='positive') echo 'selected';?>>Positive</option>
											<option value="zero" <?php if($type=='zero') echo 'selected';?>>Zero</option>
											<option value="nonzero" <?php if($type=='nonzero') echo 'selected';?>>NonZero</option>
										</select>
										<span></span><br/>
										<input type="radio" name="itemtype" value="1"> Stock &nbsp; 
										<input type="radio" name="itemtype" value="2"> Service &nbsp; 
										<input type="radio" name="itemtype" value="" checked> Both &nbsp; 
									</div>
									<div class="col-xs-6">
										<span>Search By:</span>
										<select id="search_type" class="form-control select2" style="width:100%" name="search_type">
											<option value="opening_quantity" <?php if($type=='opening_quantity') echo 'selected';?>>Opening Quantity</option>
											<option value="qtyhand_ason_date" <?php if($type=='qtyhand_ason_date') echo 'selected';?>>Quantity in Hand as on Date</option>
											<option value="qtyhand_ason_priordate" <?php if($type=='qtyhand_ason_priordate') echo 'selected';?>>Quantity in Hand as on Prior Date</option>
											<option value="qtyhand_ason_date_loc" <?php if($type=='qtyhand_ason_date_loc') echo 'selected';?>>Quantity in Hand as on Date Location</option>
										</select>
										
										<span>Location:</span><br/>
										<select id="multiselect2" multiple="multiple" class="form-control" name="location_id[]">
											<?php foreach($location as $row) { ?>
											<option value="<?php echo $row->id;?>" <?php //if($row->id==$locid) echo 'selected';?>><?php echo $row->name;?></option>
											<?php } ?>
										</select><br/>
										<!--<select id="location_id" class="form-control select2" style="width:100%" name="location_id">
											<option value="all" <?php //if($locid=='all') echo 'selected';?>>All</option>
											<?php //foreach($location as $row) { ?>
											<option value="<?php //echo $row->id;?>" <?php //if($row->id==$locid) echo 'selected';?>><?php //echo $row->name;?></option>
											<?php //} ?>
										</select>-->
										<span></span><br/><button type="submit" class="btn btn-primary">Search</button>
										
									</div>
									
								</div>
								<?php if($reports!=null) { ?>
								<div class="table-responsive">
									<table class="table table-striped" id="tableItem">
										<thead>
										<tr>
											<th>Item Code</th>
											<th>Description</th>
											<th>Unit</th>
											<th>Cost Avg.</th>
											<!--<th>Unit Price</th>
											<th>Unit Price(WS)</th>-->
											<th>Opn.Cost</th>
											<th>Last P.Cost</th>
											<th>Opn.Qty.</th>
											<th>Qty.in Hand</th>
											<th>Rcvd. Qty.</th>
											<th>Issued Qty.</th>
										</tr>
										</thead>
										<tbody>
										@foreach($reports as $report)
										<tr>
											<td>{{ $report->item_code }}</td>
											<td>{{ $report->description }}</td>
											<td>{{ $report->packing }}</td>
											<td>{{ $report->cost_avg }}</td>
											<!--<td>{{ $report->sell_price }}</td>
											<td>{{ $report->wsale_price }}</td>-->
											<td>{{ $report->opn_cost }}</td>
											<td>{{ $report->last_purchase_cost }}</td>
											<td>{{ $report->opn_quantity }}</td>
											<td>{{ $report->cur_quantity }}</td>
											<td>{{ $report->received_qty }}</td>
											<td>{{ $report->issued_qty }}</td>
										</tr>
										@endforeach
										
										</tbody>
									</table>
									<button type="button" class="btn btn-primary statement" onclick="getSummary()">Print Report</button>
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
<script src="{{asset('assets/vendors/bootstrap-multiselect/js/bootstrap-multiselect.js')}}" type="text/javascript"></script>


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
<script src="{{asset('assets/js/custom_js/custom_elements.js')}}" type="text/javascript"></script>
<!-- end of page level js -->

<script>
function getSummary() {	
	document.frmQuantityReport.action = "{{ url('quantity_report/print')}}";
	document.frmQuantityReport.submit();
}
</script>
@stop
