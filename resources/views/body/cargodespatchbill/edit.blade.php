@extends('layouts/default')

    {{-- Page title --}}
    @section('title')
         
        @parent
    @stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrap-fileinput/css/fileinput.min.css')}}" media="all" />
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/formelements.css')}}">
	
	<link rel="stylesheet" href="{{asset('assets/vendors/datetime/css/jquery.datetimepicker.css')}}">
    <link href="{{asset('assets/vendors/airdatepicker/css/datepicker.min.css')}}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/datepicker.css')}}">
	
	<link href="{{asset('assets/vendors/bootstrap-multiselect/css/bootstrap-multiselect.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('assets/vendors/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('assets/vendors/select2/css/select2-bootstrap.css')}}" rel="stylesheet" type="text/css">
	 
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/buttons.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/colReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/dataTables.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/rowReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/scroller.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatablesmark.js/css/datatables.mark.min.css')}}"/>
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/responsive_datatables.css')}}">
        <!--end of page level css-->
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <!--section starts-->
            <h1>
               Cargo Despatch Report
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="">
                       <i class="fa fa-fw fa-building-o"></i> Cargo Entry
                    </a>
                </li>
                <li>
                    <a href="#"> Cargo Despatch Report</a>
                </li>
                <li class="active">
                    Edit
                </li>
            </ol>
        </section>
		
		@if(Session::has('message'))
		<div class="alert alert-success">
			<p>{{ Session::get('message') }}</p>
		</div>
		@endif
		
        <!--section ends-->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-crosshairs"></i> Edit Despatch Report
                            </h3>
                           
                        </div>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" method="POST" name="frmCargoDespatchbill" id="frmCargoDespatchbill" action="{{url('cargo_despatchbill/update/'.$row->id)}}">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Despatch No</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" id="despatch_no" name="despatch_no" value="{{$row->despatch_no}}" readonly autocomplete="off">
                                    </div>
                                    <label for="input-text" class="col-sm-2 control-label">Despatch Date</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control pull-right" autocomplete="off" name="despatch_date" id="despatch_date" data-language='en' value="{{date('d-m-Y')}}" readonly />
								   </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Clearing Agent Sila</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" autocomplete="off" name="clear_agent_sila" required id="clear_agent_sila" value="{{$row->clear_agent_sila}}" >
								   </div>
                                    <label for="input-text" class="col-sm-2 control-label">Clearing Agent</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" autocomplete="off" name="clear_agent" required id="clear_agent" value="{{$row->clear_agent}}" />
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Vehicle No</label>
                                    <div class="col-sm-4">
                                       <input type="text" class="form-control" id="vehicle_no" name="vehicle_no" value="{{$row->vehicle_no}}" required autocomplete="off" data-toggle="modal" data-target="#vehicle_modal">
								   </div>
								   <label for="input-text" class="col-sm-2 control-label">Driver</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" autocomplete="off" name="driver" required value="{{$row->driver}}" id="driver" />
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">UAE Mob</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" autocomplete="off" name="mob_uae" value="{{$row->mob_uae}}" id="mob_uae"/>
                                    </div>
									<label for="input-text" class="col-sm-2 control-label">KSA Mob</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" autocomplete="off" name="mob_ksa" value="{{$row->mob_ksa}}" id="mob_ksa"/>
                                    </div>
                                    
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Place of Loading</label>
                                    <div class="col-sm-4">
                                       <input type="text" class="form-control" autocomplete="off" name="loading_place" value="{{$row->loading_place}}" id="loading_place"/>
								   </div>
                                    <label for="input-text" class="col-sm-2 control-label">Place of Offloading</label>
                                    <div class="col-sm-4">
                                       <input type="text" class="form-control" autocomplete="off" name="offloading_place" value="{{$row->offloading_place}}" id="offloading_place"/>
								   </div>
                                </div>
								
								<div class="form-group">
                                    
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-sm-4">
                                       
								   </div>
                                </div>
								
								<hr/>
								<input type="hidden" name="cur_waybill_id" value="{{$row->cargo_waybill_ids}}">
								<div id="waybillData">
									<div class="col-xs-15">
										<table class="table table-bordered table-hover" id="tableRV">
											<thead>
											<tr>
												<th></th>
												<th>Way Bill</th>
												<th>WBill Date</th>
												<th>Consignee</th>
												<th>Cons.No</th>
												<th>Vehicle No</th>
												<th>Driver</th>
												<th>Amount</th>
											</tr>
											</thead>
											<tbody>
											@php $weight = 0; $volume=0; @endphp
												@foreach($bills ??[] as $jrow)
												<tr>
													<td><input type="checkbox" id="tag_{{$jrow->id}}" name="waybill_id[]" class="tag-line-nw clschk" value="{{$jrow->id}}" {{(in_array($jrow->id, unserialize($row->cargo_waybill_ids)))?"checked":""}} ></td>
													<td>{{$jrow->bill_no }}</td>
													<td>{{ date('d-m-Y', strtotime($jrow->bill_date)) }}</td>
													<td>{{$jrow->consignee_name}}</td>
													<td>{{$jrow->jobs}}</td>
													<td>{{$jrow->vehicle_no}}</td>
													<td>{{$jrow->driver}}</td>
													<td>{{$jrow->total_amount}}<input type="hidden" id="amt_{{$jrow->id}}" value="{{$jrow->total_amount}}"></td>
													@php $weight +=(int)$jrow->weight; $volume += (int)$jrow->volume;@endphp
												</tr>
												@endforeach
											</tbody>
										</table>
									</div>
								
								<hr/>
								
								
								<div class="form-group">
								
                                    <label for="input-text" class="col-sm-2 control-label">Weight</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" autocomplete="off" value="{{$row->weight}}" name="weight" id="weight" required/>
                                    </div>
                                    <label for="input-text" class="col-sm-2 control-label">Volume</label>
                                    <div class="col-sm-4">
                                       <input type="text" class="form-control" autocomplete="off" value="{{$row->volume}}" name="volume" id="volume" required/>
								   </div>
                                </div>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Despatch Type</label>
                                    <div class="col-sm-4">
                                       
                                       <select class="form-control" name="container_type" id="container_type" />
										@php $sel1=$sel2=$sel3='';@endphp
											@if($row->container_type=="Full Truck")
                                            $sel1 = "selected";
													$sel2="";
													$sel3="";
													
                                            @elseif($row->container_type=="Consolidation")
											$sel1 = "";
											$sel2="selected";
											$sel3="";
											 @elseif($row->container_type=="Transportation")
											$sel1 = "";
											$sel2="";
											$sel3="selected";
											@endif
											<option value="$row->container_type">{{$row->container_type}}</option>
											<option value="Full Truck"{{$sel1}}>Full Truck</option>
											
											<option value="Consolidation"{{ $sel2 }}>Consolidation</option>
											<option value="Transportation"{{ $sel3}}>Transportation</option>
											
										</select>
									</div>
                                    <label for="input-text" class="col-sm-2 control-label">Duty Amt.</label>
                                    <div class="col-sm-4">
                                       <input type="number" class="form-control" autocomplete="off" name="duty_amt" id="duty_amt" value="{{$row->duty_amt}}"/>
								   </div>
                                </div>
								
								<div class="form-group">
									<input type="text" class="col-sm-2 control-label" autocomplete="off"  placeholder="Agreed Amount" value="{{$row->agreed_amt}}" name="agreed_amt" id="agreed_amt"/>
                                    <div class="col-sm-4">
                                        <input type="number" class="form-control" autocomplete="off" value="{{$row->agreed_transport}}" name="agree_transport" id="agree_transport"  required/>
                                    </div>
                                    <input type="text" class="col-sm-2 control-label" autocomplete="off"  placeholder="Additional Description 1" value="{{$row->add1_col}}" name="add1_col" id="add1_col"/>
                                    <div class="col-sm-4">
                                       <input type="number" class="form-control" autocomplete="off" name="add_col1" value="{{$row->add_col1}}" id="add_col1"  required/>
								   </div>
                                </div>
								
								<div class="form-group">
                                   <input type="text" class="col-sm-2 control-label" autocomplete="off"  placeholder="Additional Description 2" value="{{$row->add2_col}}" name="add2_col" id="add2_col"/>
                                    <div class="col-sm-4">
                                        <input type="number" class="form-control" autocomplete="off" name="add_col2" value="{{$row->add_col2}}" id="add_col2"  required/>
                                    </div>
                                    <input type="text" class="col-sm-2 control-label" autocomplete="off"  placeholder="Additional Description 3" value="{{$row->add3_col}}" name="add3_col" id="add3_col"/>
                                    <div class="col-sm-4">
                                       <input type="number" class="form-control" autocomplete="off" name="add_col3" value="{{$row->add_col3}}" id="add_col3"  required/>
								   </div>
                                </div>
								<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">Other Charges</label>
										<div class="col-sm-4">
											<input type="number" class="form-control" id="other_charge" name="other_charge" autocomplete="off" value="{{$row->other_charge}}"  required>
										</div>
										<label for="input-text" class="col-sm-2 control-label">Total Amount</label>
										<div class="col-sm-4">
											<input type="number" class="form-control" id="total_amount" name="total_amount" autocomplete="off" readonly value="{{$row->total_amount}}" required >
											<input type="hidden" id="total">
										</div>
									</div>
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">Advance</label>
										<div class="col-sm-4">
											<input type="number" class="form-control" id="advance" name="advance" autocomplete="off" value="{{$row->advance}}"  required>
										</div>
										<label for="input-text" class="col-sm-2 control-label">Balance</label>
										<div class="col-sm-4">
											<input type="number" class="form-control" id="balance" name="balance" autocomplete="off" readonly value="{{$row->balance}}" required>
										</div>
									</div>
								</div>
								
								<div class="form-group">
	                                   <label for="input-text" class="col-sm-2 control-label">Remarks</label>
	                                <div class="col-sm-4">
		                             <input type="text" class="form-control" id="remarks" name="remarks" autocomplete="off" value="{{$row->remarks}}">
	                                </div>
	                                <label for="input-text" class="col-sm-2 control-label">Payment at</label>
	                                <div class="col-sm-4">
		                           <input type="text" class="form-control" id="payment_at" name="payment_at" autocomplete="off" value="{{$row->payment_at}}">
	                               </div>
                                 </div>
								<div class="form-group">
	                                   <label for="input-text" class="col-sm-2 control-label">Exporter</label>
	                                <div class="col-sm-4">
		                             <input type="text" class="form-control" id="exporter" name="exporter" autocomplete="off" value="{{$row->exporter}}">
	                                </div>
	                                <label for="input-text" class="col-sm-2 control-label">Importer</label>
	                                <div class="col-sm-4">
		                           <input type="text" class="form-control" id="importer" name="importer" autocomplete="off" value="{{$row->importer}}" >
	                               </div>
                                 </div>
								<br/>
									<div class="form-group">
										<label for="input-text" class="col-sm-4 control-label"></label>
										<div class="col-sm-8">
											<button type="submit" class="btn btn-primary">Submit</button>
											 <a href="{{ url('cargo_despatchbill') }}" class="btn btn-danger">Cancel</a>
										</div>
									</div>
								 </form>
								 
								<div id="consignee_modal" class="modal fade animated" role="dialog">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal">&times;</button>
												<h4 class="modal-title">Consignee</h4>
											</div>
											<div class="modal-body" id="consigneeData">
												
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
											</div>
										</div>
									</div>
								</div>
								
								<div id="vehicle_modal" class="modal fade animated" role="dialog">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal">&times;</button>
												<h4 class="modal-title">Vehicle</h4>
											</div>
											<div class="modal-body" id="vehicleData">
												
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
											</div>
										</div>
									</div>
								</div>
                        </div>
                    </div>
                </div>
            </div>

        </section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <!-- begining of page level js -->
<script type="text/javascript" src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-fileinput/js/fileinput.min.js')}}"></script>

<script src="{{asset('assets/vendors/datetime/js/jquery.datetimepicker.full.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.en.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/custom_js/advanceddate_pickers.js')}}"></script>

<script src="{{asset('assets/vendors/select2/js/select2.js')}}" type="text/javascript"></script>

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
<script src="{{asset('assets/vendors/bootstrap-multiselect/js/bootstrap-multiselect.js')}}" type="text/javascript"></script>
  
<script type="text/javascript" src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-fileinput/js/fileinput.min.js')}}"></script>
<script src="{{asset('assets/js/jquery.fileupload.js')}}"></script>
        <!-- end of page level js -->

<script>
"use strict";
$('#despatch_date').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy' } );

$(document).ready(function () {
	//getAmount(); getTotal();
});

function getTotal() {
	let total = parseFloat( ($('#total').val()=='')?0:$('#total').val() ); 
	let agreed = parseFloat( ($('#agree_transport').val()=='')?0:$('#agree_transport').val() ); 
    let add1= parseFloat( ($('#add_col1').val()=='')?0:$('#add_col1').val() ); 
	let add2= parseFloat( ($('#add_col2').val()=='')?0:$('#add_col2').val() ); 
	let add3= parseFloat( ($('#add_col3').val()=='')?0:$('#add_col3').val() ); 
	let otrChrg = parseFloat( ($('#other_charge').val()=='')?0:$('#other_charge').val() ); 
	let amtRcvd = parseFloat( ($('#advance').val()=='')?0:$('#advance').val() ); 
	let totalChrg =agreed + add1 + add2 + add3 + otrChrg
	let balance = totalChrg - amtRcvd
	$('#balance').val(balance.toFixed(2));
	$('#total_amount').val(totalChrg.toFixed(2) );
}

$(document).on('keyup', '#agree_transport,#add_col1,#add_col2,#add_col3,#other_charge,#advance', function(e) {
	getTotal();
});

$(function() {	
	
	var conurl = "{{ url('cargo_receipt/get_consignee/') }}";
	$('#consignee_name').click(function() {
		$('#consigneeData').load(conurl, function(result) {
			$('#myModal').modal({show:true});
		});
	});
	$(document).on('click', '.consigneeRow', function(e) {
		$('#consignee_name').val($(this).attr("data-name"));
		$('#consignee_id').val($(this).attr("data-id"));
		$('#address').val($(this).attr("data-address"));
		$('#phone_no').val($(this).attr("data-phone"));
		e.preventDefault();
		
		var jurl = "{{ url('cargo_despatchbill/get_waybills/') }}/"+$(this).attr("data-id");
	    $('#waybillData').load(jurl, function(result) {
			$('#myModal').modal({show:true});
	    });
	});
	
	var vehurl = "{{ url('cargo_waybill/get_vehicle/') }}";
	$('#vehicle_no').click(function() {
		$('#vehicleData').load(vehurl, function(result) {
			$('#myModal').modal({show:true});
		});
	});
	$(document).on('click', '.vehicleRow', function(e) {
		$('#vehicle_no').val($(this).attr("data-vehno"));
		$('#driver').val($(this).attr("data-driver"));
		$('#mob_uae').val($(this).attr("data-mobuae"));
		$('#mob_ksa').val($(this).attr("data-mobksa"));
		e.preventDefault();
		
	});
	
});

function getAmount() { 
	var amt = 0;
	$('.clschk:checkbox').each(function() { 
		//console.log($('#amt_'+this.value).val());
		if ($(this).is(":checked")) {
			amt = parseFloat(amt) + parseFloat( $('#amt_'+this.value).val() );
		}
	});
	
	$('#total').val( amt.toFixed(2) );
}

$(document).on('click', '.clschk', function(e) {  
	getAmount(); 
	getTotal();
});


$(document).ready(function () {
	//$('div.checkbox-group.required :checkbox:checked').length > 0
});
</script>
@stop
