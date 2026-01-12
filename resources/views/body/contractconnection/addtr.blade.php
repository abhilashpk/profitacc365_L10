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
              Contract 
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="">
                       <i class="fa fa-fw fa-building-o"></i> RealEstate
                    </a>
                </li>
                <li>
                    <a href="#">Contract </a>
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
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-crosshairs"></i> New Connection 
                            </h3>
                           
                        </div>
						
						<div class="panel-body">
                             
							<div class="bs-example">
                                <ul class="nav nav-tabs" style="margin-bottom: 15px;">
                                    <li class="active">
                                        <a href="#home" data-toggle="tab">Connection Entry</a>
                                    </li>
                                </ul>
								
                                <div id="myTabContent" class="tab-content">
									<div class="tab-pane fade active in" id="home">
										<form class="form-horizontal" role="form" method="POST" name="frmContract" id="frmContract" action="{{url('contract-connection/save')}}">
											<input type="hidden" name="_token" value="{{ csrf_token() }}">
											<input type="hidden" name="enqid" value="{{$enqid}}">
											<br/>
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Building</label>
												<div class="col-sm-4">
													<select class="form-control" name="building_id" id="building_id"/>
													@foreach($buildingmaster as $row)
													<option value="{{$row->id}}"{{($enquiry->building_id==$row->id)?"selected":""}}>{{ $row->buildingcode }}</option>
													@endforeach
													</select>
												</div>
												
												<label for="input-text" class="col-sm-2 control-label">Date</label>
												<div class="col-sm-4">
													<input type="text" class="form-control pull-right" id="con_date" name="date" value="{{date('d-m-Y')}}" autocomplete="off" data-language='en' readonly />
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Flat</label>
												<div class="col-sm-4">
													<select class="form-control" name="flat_id" id="flat_id" />
													@foreach($flat as $row)
													<option value="{{ $row->id }}"{{($enquiry->flat_no==$row->id)?"selected":""}}>{{ $row->flat_no }}</option>
													@endforeach
													</select>
												</div>
												
												<label for="input-text" class="col-sm-2 control-label"><b>Tenant</b></label>
												<div class="col-sm-4">
													<input type="text" class="form-control" id="customer_account" name="customer_account" value="{{$enquiry->tenant}}" autocomplete="off" data-toggle="modal" data-target="#customer_modal" required>
													<input type="hidden" id="customer_id" name="customer_id" value="{{$enquiry->tenant_id}}">
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">New Reading</label>
												<div class="col-sm-4">
													<input type="text" class="form-control pull-right" id="new_reading" name="new_reading" autocomplete="off" />
												</div>
												
												<label for="input-text" class="col-sm-2 control-label"><b></b></label>
												<div class="col-sm-4">
													
												</div>
											</div>
											
											@php $i=0; @endphp
											@foreach($accounts as $acrow)
											@php $i++; @endphp
											@if($i==1)
												@php $amount = $enquiry->connection_charge; @endphp
											@elseif($i==2)
												@php $amount = $enquiry->security_deposit; @endphp
											@elseif($i==3)
												@php $amount = $enquiry->other_charge_con; @endphp
											@endif
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">{{$acrow->title}}</label>
												<div class="col-sm-6">
													<input type="text" class="form-control preincome" id="acname_1" name="acname[]" value="{{$acrow->master_name}}" readonly>
													<input type="hidden" id="acid_1" name="acid[]" value="{{$acrow->account_id}}">
													<input type="hidden" name="isvat[]" id="isvat_{{$i}}" value="{{$acrow->is_tax}}">
												</div>
												<div class="col-sm-2">
													<input type="number" class="form-control amount" id="acamt_{{$i}}" step="any" name="acamount[]" value="{{$amount}}" placeholder="Amount">
												</div>
											</div>
											@endforeach
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label"><b>Balance</b></label>
												<div class="col-sm-4">
													<input type="number" class="form-control" id="balance" step="any" value="{{$enquiry->cl_balance}}" name="balance"readonly placeholder="Balance">
												</div>
												
												<label for="input-text" class="col-sm-2 control-label"><b>Grand Total</b></label>
												<div class="col-sm-2">
													<input type="number" class="form-control" id="gtotal" step="any" name="grand_total" value="" readonly placeholder="Grand Total">
												</div>
											</div>
											
											<hr/>
											
											<div class="filedivPrnt" >
												<div class="filedivChld">
													<div class="form-group">
														<label for="input-text" class="col-sm-2 control-label filelbl" id="lblif_1">Upload Image 1</label>
														<div class="col-sm-9">
															<input type="file" id="input-50" name="photos" class="file-loading" data-show-preview="true" data-url="{{url('sales_invoice/upload/')}}">
															<div id="files_list_1"></div>
															<p id="loading_1"></p>
															<input type="hidden" name="photo_name[]" id="photo_name_1">
														</div>
														<div class="col-sm-1">
															<button type="button" class="btn-success btn-add-file" id="btn_1">
																<i class="fa fa-fw fa-plus-square"></i>
															</button>
															<button type="button" class="btn-danger btn-remove-file">
																<i class="fa fa-fw fa-minus-square"></i>
															 </button>
														</div>
														<label for="input-text" class="col-sm-2 control-label filedslbl" id="lblifd_1">Description</label>
														<div class="col-sm-10">
															<input type="text" class="form-control" id="imgdesc_1" name="imgdesc[]" placeholder="Description" autocomplete="off">
														</div>
													</div>
												</div>
											</div>
											
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Receipt Voucher Entry</label>
												<div class="col-sm-1">
													<label class="radio-inline iradio">
														<input type="checkbox" class="rv_icheck" id="is_rv" name="is_rv" value="1" checked>
														<input type="hidden" name="rv_voucher[]" value="{{$rvid}}"/>
														<input type="hidden" name="voucher_type[]" value="CASH"/>
														<input type="hidden" name="rv_voucher_no[]" value="{{$rvvoucher['voucher_no']}}">
														<input type="hidden" name="rv_dr_account[]" value="{{$rvvoucher['account_name']}}">
														<input type="hidden" name="rv_dr_account_id[]" value="{{$rvvoucher['id']}}">
														 
												</div>
												<div class="col-sm-9">
													<label for="input-text" class="col-sm-2 control-label">RV Amount</label>
													<div class="col-sm-4">
													<input type="number" class="form-control" name="rv_amount" step="any" id="rvamt" />
													</div>
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label"></label>
												<div class="col-sm-10">
													<button type="submit" class="btn btn-primary">Submit</button>
													 <a href="{{ url('contract-connection') }}" class="btn btn-danger">Cancel</a>
												</div>
											</div>
											
											</form>
                                    </div>
								
                                </div>
                            </div>
                        </div>
						
                        
                    </div>
                </div>
            </div>
			
			<div id="accounts_modal" class="modal fade animated" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">Select Account</h4>
						</div>
						<div class="modal-body" id="accounts_data">
						</div>
					</div>
				</div>
			</div>
			<div id="account_modal" class="modal fade animated" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">Select Account</h4>
						</div>
						<div class="modal-body" id="account_data">
						</div>
					</div>
				</div>
			</div>
								
			<div id="customer_modal" class="modal fade animated" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">Select Customer</h4>
						</div>
						<div class="modal-body" id="customerData">
							
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						</div>
					</div>
				</div>
			</div> 
			
			<div id="ac_modal" class="modal fade animated" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">Select Account</h4>
						</div>
						<div class="modal-body" id="ac_data">
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						</div>
					</div>
				</div>
			</div> 
								
        </section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')

<script type="text/javascript" src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-fileinput/js/fileinput.min.js')}}"></script>
<script src="{{asset('assets/vendors/moment/js/moment.min.js')}}" type="text/javascript"></script>
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
<script src="{{asset('assets/js/jquery.fileupload.js')}}"></script>

<script>
"use strict";
$('#con_date').datepicker( { autoClose: true,dateFormat: 'dd-mm-yyyy' } );

//JN17
$('.inputvn').on('click', function(e) {
	$('#contract_no').attr("readonly", false);
});

$(document).ready(function () {
	grandTotal();
	var urltype = "{{ url('contra_type/check_type/') }}";
     $('#frmContract').bootstrapValidator({
         fields: {
 			building_id: {
                 validators: {
                     notEmpty: {
                         message: 'Building is required and cannot be empty!'
                     }
                 }
             },
 			/* customer_account: {
                 validators: {
                     notEmpty: {
                         message: 'Tenant is required and cannot be empty!'
                     }
                 }
             }, */
			 flat_id: {
                 validators: {
                     notEmpty: {
                         message: 'Flat no is required and cannot be empty!'
                     }
                 }
             },
			 'acamount[]': {
                 validators: {
                     notEmpty: {
                         message: 'Rent amount is required and cannot be empty!'
                     }
                 }
             }
         }
        
     }).on('reset', function (event) {
         $('#frmContract').data('bootstrapValidator').resetForm();
     });
});

$(document).on('keyup', '.amount', function(e) {
	grandTotal();
});

$(document).on('change', '#building_id', function(e) {
	var bid = $(this).val();
	$.get("{{ url('buildingmaster/get_flat/') }}/" + bid, function(data) {
		var dat = $.parseJSON(data); 
		$('#flat_id').find('option').remove().end();
		$.each(dat, function(key, value) {   
			$('#flat_id').find('option').end()
			 .append($("<option>Select</option>")
						.attr("value",value.id)
						.text(value.flat_no)); 
		});
	});
	
	$.get("{{ url('buildingmaster/getvals/') }}/" + bid, function(data) {
		var det = $.parseJSON(data); 
		 
		 $('#acamt_1').val(det.connection_charge); 
		 $('#acamt_2').val(det.security_deposit); 
		 $('#acamt_3').val(det.other_charge_con); 
	}).done(function() {
		grandTotal();
		
	});
});

$('#customer_account').click(function() {
	let custurl = "{{ url('sales_invoice/customer_data/') }}";
	$('#customerData').load(custurl, function(result) {
		$('#myModal').modal({show:true});
		$('.input-sm').focus()
	});
});

$(document).on('click', '.custRow', function(e) {
	$('#customer_account').val($(this).attr("data-name"));
	$('#customer_id').val($(this).attr("data-id"));
	e.preventDefault();
});


function grandTotal() { 
	let amount=0; let total=0; 
	$( '.amount' ).each(function() { 
		  let vat = 0;
		  var res = this.id.split('_');
		  var no = res[1];
		  let amt = (this.value=='')?0:parseFloat(this.value);
		  if($('#isvat_'+no).val()==1) {
			 vat = (amt * 5)/100;
		  }
		  amount = amount + amt + vat;
	});
	$('#gtotal').val(amount.toFixed(2));
	$('#rvamt').val(amount.toFixed(2));
}

$(function() {	
	$("#input-50").fileinput({
		browseClass: "btn btn-default",
		showUpload: false,
		mainTemplate: "{preview}\n" +
			"<div class='input-group {class}'>\n" +"   <div class='input-group-btn'>\n" +"       {browse}\n" +"       {upload}\n" +"       {remove}\n" +"   </div>\n" +"   {caption}\n" +"</div>"
	});

	$('#input-50').fileupload({
		dataType: 'json',
		add: function (e, data) {
			$('#loading_1').text('Uploading...');
			data.submit();
		},
		done: function (e, data) {
			var pn = $('#photo_name_1').val();
			$('#photo_name_1').val( (pn=='')?data.result.file_name:pn+','+data.result.file_name );
			$('#loading_1').text('Completed.');
		}
	});
	
	var fNum;
	$(document).on('click', '.btn-add-file', function(e)  { 
		var res = this.id.split('_');
		fNum = res[1];
	    fNum++;
		$.ajax({
			url: "{{ url('job_order/get_fileform/SI') }}",
			type: 'post',
			data: {'no':fNum},
			success: function(data) {
				$('.filedivPrnt').append(data);
				return true;
			}
		}) 
		
	}).on('click', '.btn-remove-file', function(e) { 
		$(this).parents('.filedivChld:first').remove();
		
		$('.filedivPrnt').find('.filedivChld:last').find('.btn-add-file').show();
		if ( $('.filedivPrnt').children().length == 1 ) {
			$('.filedivPrnt').find('.btn-remove-file').hide();
		}
		
		e.preventDefault();
		return false;
	});
});
</script>
@stop


