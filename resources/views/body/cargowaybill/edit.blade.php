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
               Cargo Way Bill
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="">
                       <i class="fa fa-fw fa-building-o"></i> Cargo Entry
                    </a>
                </li>
                <li>
                    <a href="#"> Cargo Way Bill</a>
                </li>
                <li class="active">
                    Edit
                </li>
            </ol>
        </section>
		@if ( $row->status== 1)
			<div class="alert alert-danger">
				<p> Already Trasfered </p>
			</div>
		@endif
		
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
                                <i class="fa fa-fw fa-crosshairs"></i> Edit Cargo Way Bill
                            </h3>
                           
                        </div>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" method="POST" name="frmCargoWaybill" id="frmCargoWaybill" action="{{url('cargo_waybill/update/'.$row->id)}}">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" id="id" name="id" value="{{$row->id}}">
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Bill No</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" id="billno" name="billno" value="{{$row->bill_no}}" readonly autocomplete="off">
                                    </div>
                                    <label for="input-text" class="col-sm-2 control-label">Bill Date</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control pull-right" autocomplete="off" name="bill_date" id="bill_date" data-language='en' value="{{date('d-m-Y',strtotime($row->bill_date))}}" readonly />
								   </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Consignee Name</label>
                                    <div class="col-sm-4">
                                        <select class="form-control select2" name="consignee_name" id="select25" style="width:100%" required />
											<option value="">--Select--</option>
											@foreach($consignee as $crow)
											<option value="{{$crow->id}}" {{$crow->id==$row->consignee_id?"selected":""}}>{{$crow->consignee_name}}</option>
											@endforeach
										</select>
										<input type="hidden" name="consignee_id" id="consignee_id"value="{{$row->consignee_id}}">
										<div class="btn-label pull-right">
										    <a href="" class="btn btn-info create-consignee" data-toggle="modal" data-target="#createconsignee_modal">
									         <span class="btn-label">
									        <i class="glyphicon glyphicon-plus" ></i>
								             </span> 
							                </a>
						                </div>                                    </div>
                                    <label for="input-text" class="col-sm-2 control-label">Address</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" autocomplete="off" name="address" required id="address" value="{{$row->address}}">
								   </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Phone No</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" autocomplete="off" name="phone_no" id="phone_no" value="{{$row->phone}}"/>
                                    </div>
                                    <label for="input-text" class="col-sm-2 control-label">Mobile</label>
                                    <div class="col-sm-4">
                                       <input type="text" class="form-control" autocomplete="off" name="mobile" id="mobile" value="{{$row->mobile}}"/>
								   </div>
                                </div>
								<hr/>
								<input type="hidden" name="cur_jobids" value="{{$row->cargo_receipt_ids}}">
								<div id="jobData">
									<div class="col-xs-15">
										<table class="table table-bordered table-hover" id="tableRV">
											<thead>
											
											<tr>
												<th></th>
												<th>Cons.No</th>
												<th>Cons.Date</th>
												<th>Shipper</th>
												<th>Pack Qty</th>
												<th>Loaded Qty</th>
												<th>Loaded Pack Qty</th>
												<th>Col.Type</th>
												<th>Delivery Type</th>
											</tr>
											</thead>
											<tbody>
												@foreach($jobs as $jrow)
												<tr>
													<td><input type="checkbox" id="tag_{{$jrow->id}}" name="jobid[]" class="tag-line-nw clschk" value="{{$jrow->id}}" {{(in_array($jrow->id, unserialize($row->cargo_receipt_ids)))?"checked":""}} ></td>
													<td>{{$jrow->job_code }}</td>
													<td>{{ date('d-m-Y', strtotime($jrow->job_date)) }}</td>
													<td>{{$jrow->shipper_name}}</td>
													<td>{{$jrow->packing_qty}}
													<input type="hidden" id="received_qty_{{$jrow->id}}" name="received_qty" value="{{$jrow->packing_qty}}">
													</td>
													<td><input type="number" id="loaded_qty_{{$jrow->id}}" name="loaded_qty[]" class="form-control loaded-qty" value="{{isset($qtyarr[$jrow->id])?$qtyarr[$jrow->id]->loaded_qty:''}}" style="width:55%">
														<input type="hidden" id="amt_{{$jrow->id}}" value="{{($qtyarr[$jrow->id]->loaded_qty*$jrow->rate)+$jrow->coll_charge+$jrow->other_charge}}">
											            <input type="hidden" id="rate_{{$jrow->id}}" name="rate" value="{{$jrow->rate}}">
											           <input type="hidden" id="coll_charge_{{$jrow->id}}" name="coll_charge" value="{{$jrow->coll_charge}}">
											            <input type="hidden" id="other_charge_{{$jrow->id}}" name="other_charge" value="{{$jrow->other_charge}}">
													</td>
													<td><input type="number" id="loaded_pack_qty_{{$jrow->id}}" name="loaded_pack_qty[]" class="form-control loadedpack-qty" value="{{isset($qtyarr[$jrow->id])?$qtyarr[$jrow->id]->loaded_pack_qty:''}}" style="width:55%">
													</td>
													<td>{{$jrow->collection_type}}</td>
													<td>{{$jrow->delivery_type}}</td>
												</tr>
												@endforeach
											</tbody>
										</table>
									</div>
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">Total Amount</label>
										
										<div class="col-sm-4">
											<input type="number" class="form-control" id="total_amount" name="total_amount" autocomplete="off" readonly value="" required >
										    
										</div>
										
									</div>
								</div>
								
								<hr/>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Vehicle No</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" id="vehicle_no" name="vehicle_no" value="{{$row->vehicle_no}}" required autocomplete="off" data-toggle="modal" data-target="#vehicle_modal">
                                    </div>
                                    <label for="input-text" class="col-sm-2 control-label">Driver</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" autocomplete="off" name="driver" value="{{$row->driver}}" required id="driver" />
								   </div>
                                </div>
								<div class="form-group">
									<label for="input-text" class="col-sm-2 control-label">Mobile(UAE)</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" autocomplete="off" name="mob_uae" id="mob_uae" value="{{$row->mob_uae}}" />
								   </div>
								   <label for="input-text" class="col-sm-2 control-label">Mobile(KSA)</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" autocomplete="off" name="mob_ksa" id="mob_ksa" value="{{$row->mob_ksa}}" />
								   </div>
								  </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Special Instructions</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="instructions" name="instructions" value="{{$row->instructions}}" autocomplete="off">
                                    </div>
								</div>
								
								<hr/>
								<br/>
									<div class="form-group">
										<label for="input-text" class="col-sm-4 control-label"></label>
										<div class="col-sm-8">
										
											<button type="submit" class="btn btn-primary" >Submit</button> 
										
											 <a href="{{ url('cargo_waybill') }}" class="btn btn-danger">Cancel</a>
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
								<div id="createconsignee_modal" class="modal fade animated" role="dialog">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal">&times;</button>
												<h4 class="modal-title">Create Consignee</h4>
											</div>
											<div class="modal-body" id="newconsigneeData">
												
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
$('#bill_date').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy' } ); //<!-- <?php if($row->status==1) echo 'disabled';?> -->

$(function() {	
	$("#select25").select2({
        theme: "bootstrap",
        placeholder: "Select Consignee"
    });
	$(document).on('change', '#select25', function(e) {
	var $id=$('#select25').val();
	$('#consignee_id').val($id);
	$.get("{{ url('cargo_waybill/get_con/') }}/" +$id, function(data) { 
			console.log(data);
			var parsedData = JSON.parse(data);
			  console.log (parsedData.id);
			  
           $('#phone_no').val(parsedData.phone);
           $('#address').val(parsedData.address);
            $('#mobile').val(parsedData.alter_phone);
			

		});
		
	});	
	
	});
	var newconurl = "{{ url('cargo_receipt/create_consignee/') }}";
	 $('.create-consignee').click(function() {
	   $('#newconsigneeData').load(newconurl, function(result) {
			$('#myModal').modal({show:true});
		});
	});
	var conurl = "{{ url('cargo_receipt/get_consignee/') }}";
	$('#consignee_name').click(function() {
		$('#consigneeData').load(conurl, function(result) {
			$('#myModal').modal({show:true});
		});
	});
	$(document).on('click', '.consigneeRow', function(e) {
		//$('#consignee_name').val($(this).attr("data-name"));
		$("#select25").append ('<option selected="selected" value="' + $(this).attr("data-name")+ '">' + $(this).attr("data-name")+ '</option>');
		$('#consignee_id').val($(this).attr("data-id"));
		$('#address').val($(this).attr("data-address"));
		$('#phone_no').val($(this).attr("data-phone"));
		e.preventDefault();
		
		var jurl = "{{ url('cargo_waybill/get_conjobs/') }}/"+$(this).attr("data-id");
	    $('#jobData').load(jurl, function(result) {
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
 
 function getLoadedqty(){

 $('.loadedpack-qty').each(function() { 
 var res = this.id.split('_');
var curNum = res[3];
 var load= $('#loaded_pack_qty_'+curNum).val(); 
 var recvd = $('#received_qty_'+curNum).val(); 
  console.log(load);
 //console.log(id);
 if(load > recvd || load==0) {
		alert('Loaded pack quantity must be less than pack quantity!');
		$('#loaded_pack_qty_'+curNum).val('');
		//$('#loaded_qty_'+curNum).focus();
		return false
	}
});
 }
$(document).on('blur', '.loadedpack-qty', function(e) { 

getLoadedqty();
  });

function getTotal() {
 var tot=0;
$('.loaded-qty').each(function() {

var res = this.id.split('_');
var curNum = res[2];
//console.log($('#loaded_qty_'+curNum).val() );
var load= parseFloat(($('#loaded_qty_'+curNum).val()=='') ? 0 : $('#loaded_qty_'+curNum).val() ); 
var rate= parseFloat(($('#rate_'+curNum).val()=='') ? 0 : $('#rate_'+curNum).val() );
var coll= parseFloat(($('#coll_charge_'+curNum).val()=='') ? 0 : $('#coll_charge_'+curNum).val() );
var other= parseFloat(($('#other_charge_'+curNum).val()=='') ? 0 : $('#other_charge_'+curNum).val() );
var total = parseFloat((load*rate)+coll+other);
tot=total+tot;

});

console.log(tot)

$('#total_amount').val(tot.toFixed(2) );
}

$(document).on('keyup', '.loaded-qty', function(e) {
	getTotal() ;
	
	
});

function getAmount() { 
	var amt = 0;
	$('.clschk:checkbox').each(function() { 
		//console.log($('#amt_'+this.value).val());
		if ($(this).is(":checked")) {
			amt = parseFloat(amt) + parseFloat( $('#amt_'+this.value).val() );
		}
	});
	
	$('#total_amount').val( amt.toFixed(2) );
}


$(document).on('click', '.clschk', function(e) {  
	getAmount();
});

$(document).ready(function () {
	//$('div.checkbox-group.required :checkbox:checked').length > 0
	getAmount();
});
</script>
@stop
