@extends('layouts/default')

    {{-- Page title --}}
    @section('title')
         
        @parent
    @stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/iCheck/css/all.css')}}" />
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
		
	<style>
		#vehicle_modal { z-index:0; }
	</style>
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
                    <a href="#">Cargo Way Bill</a>
                </li>
                <li class="active">
                    Add New
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
                        <div class="panel-heading clearfix">
                            <h3 class="panel-title pull-left m-t-6">
                                <i class="fa fa-fw fa-crosshairs"></i> New Way Bill
                            </h3>
                            <div class=" pull-right">
						   <?php if($printid) { ?>
								 <a href="{{ url('cargo_waybill/print/'.$printid->id) }}" target="_blank" class="btn btn-info ">
								 Print Waybill
								
								 </a>
								 <?php } ?>
								 
								 </div>
                        </div>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" method="POST" name="frmCargoWaybill" id="frmCargoWaybill" action="{{url('cargo_waybill/save')}}" onsubmit="return validateForm()">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
									
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Bill No</label>
                                    <div class="col-sm-4">
									<div class="input-group">
                                        <input type="text" class="form-control" id="billno" name="billno" value="{{$voucher->no}}" readonly autocomplete="off">
                                        <span class="input-group-addon inputvn"><i class="fa fa-edit" style="font-size:22px;color:#ff9f2c"></i></span>
								    </div>
									 </div>
                                    <label for="input-text" class="col-sm-2 control-label">Bill Date</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control pull-right" autocomplete="off" name="bill_date" id="bill_date" data-language='en' value="{{date('d-m-Y')}}" readonly />
								   </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Consignee Name</label>
                                    <div class="col-sm-4">
                                        
									    <select class="form-control select2" name="consignee_name" id="select25" style="width:100%" required />
											<option value="">--Select--</option>
											@foreach($consignee as $crow)
											<option value="{{$crow->id}}">{{$crow->consignee_name}}</option>
											@endforeach
										</select>
										<input type="hidden" name="consignee_id" id="consignee_id">
										<div class="btn-label pull-right">
										    <a href="" class="btn btn-info create-consignee" data-toggle="modal" data-target="#createconsignee_modal">
									         <span class="btn-label">
									        <i class="glyphicon glyphicon-plus" ></i>
								             </span> 
							                </a>
						             </div>
                                    </div>
                                    <label for="input-text" class="col-sm-2 control-label">Address</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" autocomplete="off" name="address" required id="address">
								   </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Phone No</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" autocomplete="off" name="phone_no" id="phone_no"/>
                                    </div>
                                    <label for="input-text" class="col-sm-2 control-label">Mobile</label>
                                    <div class="col-sm-4">
                                       <input type="text" class="form-control" autocomplete="off" name="mobile" id="mobile"/>
								   </div>
                                </div>
								<hr/>
								
								<div id="jobData"></div>
								
								<hr/>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Vehicle No</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" id="vehicle_no" name="vehicle_no" required autocomplete="off" data-toggle="modal" data-target="#vehicle_modal">
                                    </div>
                                    <label for="input-text" class="col-sm-2 control-label">Driver</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" autocomplete="off" name="driver" required id="driver" />
								   </div>
                                </div>
								<div class="form-group">
									<label for="input-text" class="col-sm-2 control-label">Mobile(UAE)</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" autocomplete="off" name="mob_uae" id="mob_uae"/>
								   </div>
								   <label for="input-text" class="col-sm-2 control-label">Mobile(KSA)</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" autocomplete="off" name="mob_ksa" id="mob_ksa"/>
								   </div>
								  </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Special Instructions</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="instructions" name="instructions" autocomplete="off">
                                    </div>
								</div>
								
								<hr/>
								<br/>
									<div class="form-group">
										<label for="input-text" class="col-sm-4 control-label"></label>
										<div class="col-sm-8">
											<button type="submit" class="btn btn-primary">Submit</button>
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
<script type="text/javascript" src="{{asset('assets/vendors/iCheck/js/icheck.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-fileinput/js/fileinput.min.js')}}"></script>

<script src="{{asset('assets/vendors/datetime/js/jquery.datetimepicker.full.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.en.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/custom_js/advanceddate_pickers.js')}}"></script>

<script type="text/javascript" src="{{asset('assets/vendors/custom_js/form_elements.js')}}"></script>
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
<script type="text/javascript" src="{{asset('assets/vendors/iCheck/js/icheck.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-fileinput/js/fileinput.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/custom_js/form_elements.js')}}"></script>
<script src="{{asset('assets/js/jquery.fileupload.js')}}"></script>
        <!-- end of page level js -->

<script>
"use strict";
$('#bill_date').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy' } );

$(function() {	
$("#select25").select2({
        theme: "bootstrap",
        placeholder: "Select Consignee"
    });
$(document).on('change', '#select25', function(e) {
	var $id=$('#select25').val();
	var $phn=$('#phone_no').val();
	var $add=$('#address').val();
	var $mob=$('#mobile').val();
	$('#consignee_id').val($id);
	var jurl = "{{ url('cargo_waybill/get_conjobs/') }}/"+$id;
	    $('#jobData').load(jurl, function(result) {
			$('#myModal').modal({show:true});
	    });
	$.get("{{ url('cargo_waybill/get_con/') }}/" +$id, function(data) { 
			console.log(data);
			var parsedData = JSON.parse(data);
			  console.log (parsedData.id);
			  
           $('#phone_no').val(parsedData.phone);
           $('#address').val(parsedData.address);
            $('#mobile').val(parsedData.alter_phone);
			

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
			$('#myModal').modal({show:true}); $('.input-sm').focus()
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
$('.inputvn').on('click', function(e) {
		$('#billno').attr("readonly", false);
	});
function getLoadedqty(){

 $('.loadedpack-qty').each(function() { 
 var res = this.id.split('_');
var curNum = res[3];
console.log(curNum);
 var load= parseFloat($('#loaded_pack_qty_'+curNum).val()); 
 var recvd =parseFloat( $('#received_qty_'+curNum).val()); 
 // console.log(load);
 //console.log(recvd);
 if(load > recvd || load==0 || load=="") {
		alert('Loaded pack quantity must be less than pack quantity!');
		$('#loaded_pack_qty_'+curNum).val('');
		$('#loaded_qty_'+curNum).focus();
		e.preventDefault(e);
		return false
	}
	
});
 }
 function validateForm(){
 var flg = false;
	$('.clschk:checkbox').each(function() { 
        if ($(this).is(":checked")) {
 var curNum = this.value;
console.log(curNum);
 var load= $('#loaded_pack_qty_'+curNum).val(); 
 
 if(load=="") {
 
		alert('Loaded pack quantity is required!');
		$('#loaded_pack_qty_'+curNum).val('');
		
		e.preventDefault(e);
		return false
	}
	else
	    flg = true;
		}
});
return flg;
 }
$(document).on('blur', '.loadedpack-qty', function(e) { 

getLoadedqty();
  });


 /*  $('#frmCargoWaybill').on('submit',function(e){ 
		
		e.preventDefault(e);
		var name = $('#frmCustomer #customer_name').val();
		if(name=="") {
			alert('Customer name is required!');
			return false;
		}*/
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
});


</script>
@stop
