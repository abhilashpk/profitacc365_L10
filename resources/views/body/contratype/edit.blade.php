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
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <!--section starts-->
            <h1>
              Contract Type
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="">
                       <i class="fa fa-fw fa-building-o"></i> RealEstate
                    </a>
                </li>
                <li>
                    <a href="#">Contract Type</a>
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
                                <i class="fa fa-fw fa-crosshairs"></i> Edit Contract Type 
                            </h3>
                           
                        </div>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" method="POST" name="frmContratype" id="frmContratype" action="{{url('contra_type/update/'.$crow->id)}}">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" name="id" id="id" value="{{ $crow->id }}">
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label">Building</label>
										<div class="col-sm-6">
											<select class="form-control" name="buildingid" id="buildingid"/>
											@foreach($buildingmaster as $row)
											@if($crow->buildingid==$row->id)
											<option value="{{$row->id}}" {{($crow->buildingid==$row->id)?'selected':''}}>{{$row->buildingcode}}</option>
											@endif
											@endforeach
											</select>
										</div>
									</div>
								
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label">Contract Type</label>
										<div class="col-sm-6">
											<input type="text" class="form-control" id="type" name="type" autocomplete="off" value="{{$crow->type}}" placeholder="Contract Type">
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label">Increment No.</label>
										<div class="col-sm-6">
											<input type="text" class="form-control" id="increment_no" name="increment_no" autocomplete="off" value="{{$crow->increment_no}}" placeholder="Increment No.">
										</div>
									</div>
									
									<hr/>
									<h5><b>Income Accounts</b></h5>
									<div class="form-group">
										<div class="col-sm-3"><input type="text" name="txt_prepaid_income" class="form-control" value="{{($heads['prepaid_income']!='')?$heads['prepaid_income']:'Prepaid Income A/c.'}}"></div>
										<div class="col-sm-6">
											<input type="text" class="form-control other-account" id="acname_1" name="acname_1" autocomplete="off" value="{{$crow->acname1}}" placeholder="Prepaid Income A/c." data-toggle="modal" data-target="#account_modal">
											<input type="hidden" id="acid_1" name="prepaid_income" value="{{$crow->prepaid_income}}">
										</div>
										<div class="col-sm-2"><label for="input-text">Tax Include</label>
											<input type="checkbox" class="form-control" name="pi_tax" value="1" {{($crow->pi_tax==1)?'checked':''}}>
										</div>
									</div>
									
									<div class="form-group">
										<div class="col-sm-3"><input type="text" name="txt_rental_income" class="form-control" value="{{($heads['rental_income']!='')?$heads['rental_income']:'Rental Income A/c.'}}"></div>
										<div class="col-sm-6">
											<input type="text" class="form-control other-account" id="acname_2" name="acname_2" value="{{$crow->acname2}}" autocomplete="off" placeholder="Rental Income A/c." data-toggle="modal" data-target="#account_modal">
											<input type="hidden" id="acid_2" name="rental_income" value="{{$crow->rental_income}}">
										</div>
										<div class="col-sm-2"><label for="input-text">Tax Include</label>
											<input type="checkbox" class="form-control" name="ri_tax" value="1" {{($crow->ri_tax==1)?'checked':''}}>
										</div>
									</div>
									
									
									<div class="form-group">
										<div class="col-sm-3"><input type="text" name="txt_commission" class="form-control" value="{{($heads['commission']!='')?$heads['commission']:'Commission'}}"></div>
										<div class="col-sm-6">
											<input type="text" class="form-control other-account" id="acname_6" name="acname_6" value="{{$crow->acname6}}" autocomplete="off" placeholder="Commission A/c." data-toggle="modal" data-target="#account_modal">
											<input type="hidden" id="acid_6" name="commission" value="{{$crow->commission}}">
										</div>
										<div class="col-sm-2"><label for="input-text">Tax Include</label>
											<input type="checkbox" class="form-control" name="c_tax" value="1" {{($crow->c_tax==1)?'checked':''}}>
										</div>
									</div>
									
									<div class="form-group">
										<div class="col-sm-3"><input type="text" name="txt_parking" class="form-control" value="{{($heads['parking']!='')?$heads['parking']:'Parking Amount A/c.'}}"></div>
										<div class="col-sm-6">
											<input type="text" class="form-control other-account" id="acname_7" name="acname_7" value="{{$crow->acname7}}" autocomplete="off" placeholder="Parking Amount A/c." data-toggle="modal" data-target="#account_modal">
											<input type="hidden" id="acid_7" name="parking" value="{{$crow->parking}}">
										</div>
										<div class="col-sm-2"><label for="input-text">Tax Include</label>
											<input type="checkbox" class="form-control" name="p_tax" value="1" {{($crow->p_tax==1)?'checked':''}}>
										</div>
									</div>
									
									
									
									<div class="form-group">
										<div class="col-sm-3"><input type="text" name="txt_cancellation" class="form-control" value="{{($heads['cancellation']!='')?$heads['cancellation']:'Cancellation Fee A/c'}}"></div>
										<div class="col-sm-6">
											<input type="text" class="form-control other-account" id="acname_8" name="acname_8" value="{{$crow->acname8}}" autocomplete="off" placeholder="Cancellation Fee A/c." data-toggle="modal" data-target="#account_modal">
											<input type="hidden" id="acid_8" name="cancellation" value="{{$crow->cancellation}}">
										</div>
										<div class="col-sm-2"><label for="input-text">Tax Include</label>
											<input type="checkbox" class="form-control" name="cl_tax" value="1" {{($crow->cl_tax==1)?'checked':''}}>
										</div>
									</div>
									
																		
									<div class="form-group">
										<div class="col-sm-3"><input type="text" name="txt_repair" class="form-control" value="{{($heads['repair']!='')?$heads['repair']:'Repair and Maintenance A/c.'}}"></div>
										<div class="col-sm-6">
											<input type="text" class="form-control other-account" id="acname_9" name="acname_9" value="{{$crow->acname9}}" autocomplete="off" placeholder="Repair and Maintenance A/c." data-toggle="modal" data-target="#account_modal">
											<input type="hidden" id="acid_9" name="repair" value="{{$crow->repair}}">
										</div>
										<div class="col-sm-2"><label for="input-text">Tax Include</label>
											<input type="checkbox" class="form-control" name="r_tax" value="1" {{($crow->r_tax==1)?'checked':''}}>
										</div>
									</div>
									
									<div class="form-group">
										<div class="col-sm-3"><input type="text" name="txt_water_ecty_bill" class="form-control" value="{{($heads['water_ecty_bill']!='')?$heads['water_ecty_bill']:'Electricity and Water Bill A/c.'}}"></div>
										<div class="col-sm-6">
											<input type="text" class="form-control other-account" id="acname_10" name="acname_10" value="{{$crow->acname10}}" autocomplete="off" placeholder="Electricity and Water Bill A/c." data-toggle="modal" data-target="#account_modal">
											<input type="hidden" id="acid_10" name="water_ecty_bill" value="{{$crow->water_ecty_bill}}">
										</div>
										<div class="col-sm-2"><label for="input-text">Tax Include</label>
											<input type="checkbox" class="form-control" name="web_tax" value="1" {{($crow->web_tax==1)?'checked':''}}>
										</div>
									</div>
									
									<div class="form-group">
										<div class="col-sm-3"><input type="text" name="txt_closing_oth" class="form-control" value="{{($heads['closing_oth']!='')?$heads['closing_oth']:'Other Closing Charges A/c.'}}"></div>
										<div class="col-sm-6">
											<input type="text" class="form-control other-account" id="acname_11" name="acname_11" value="{{$crow->acname11}}" autocomplete="off" placeholder="Other Closing Charges A/c." data-toggle="modal" data-target="#account_modal">
											<input type="hidden" id="acid_11" name="closing_oth" value="{{$crow->closing_oth}}">
										</div>
										<div class="col-sm-2"><label for="input-text">Tax Include</label>
											<input type="checkbox" class="form-control" name="co_tax" value="1" {{($crow->co_tax==1)?'checked':''}}>
										</div>
									</div>
									
									<div class="form-group">
										<div class="col-sm-3"><input type="text" name="txt_booking_oth" class="form-control" value="{{($heads['booking_oth']!='')?$heads['booking_oth']:'Other Booking Charges A/c.'}}"></div>
										<div class="col-sm-6">
											<input type="text" class="form-control other-account" id="acname_12" name="acname_12" value="{{$crow->acname12}}" autocomplete="off" placeholder="Other Booking Charges A/c." data-toggle="modal" data-target="#account_modal">
											<input type="hidden" id="acid_12" name="booking_oth" value="{{$crow->booking_oth}}">
										</div>
										<div class="col-sm-2"><label for="input-text">Tax Include</label>
											<input type="checkbox" class="form-control" name="bo_tax" value="1" {{($crow->bo_tax==1)?'checked':''}}>
										</div>
									</div>
									
									<div class="form-group">
										<div class="col-sm-3"><input type="text" name="txt_chq_charge" class="form-control" value="{{($heads['chq_charge']!='')?$heads['chq_charge']:'Cheque Bounced Charges A/c.'}}"></div>
										<div class="col-sm-6">
											<input type="text" class="form-control other-account" id="acname_13" name="acname_13" value="{{$crow->acname13}}" autocomplete="off" placeholder="Cheque Bounced Charges A/c." data-toggle="modal" data-target="#account_modal">
											<input type="hidden" id="acid_13" name="chq_charge" value="{{$crow->chq_charge}}">
										</div>
										<div class="col-sm-2"><label for="input-text">Tax Include</label>
											<input type="checkbox" class="form-control" name="cc_tax" value="1" {{($crow->cc_tax==1)?'checked':''}}>
										</div>
									</div>
									
									<div class="form-group">
										<div class="col-sm-3"><input type="text" name="txt_ejarie_fee" class="form-control" value="{{($heads['ejarie_fee']!='')?$heads['ejarie_fee']:'Ejarie Fee A/c'}}"></div>
										<div class="col-sm-6">
											<input type="text" class="form-control other-account" id="acname_14" name="acname_14" value="{{$crow->acname14}}" autocomplete="off" placeholder="Ejarie Fee A/c." data-toggle="modal" data-target="#account_modal">
											<input type="hidden" id="acid_14" name="ejarie_fee" value="{{$crow->ejarie_fee}}">
										</div>
										<div class="col-sm-2"><label for="input-text">Tax Include</label>
											<input type="checkbox" class="form-control" name="ef_tax" value="1" {{($crow->ef_tax==1)?'checked':''}}>
										</div>
									</div>
									<hr/>

								<h5><b>Deposit Accounts</b></h5>
									<div class="form-group">
										<div class="col-sm-3"><input type="text" name="txt_deposit" class="form-control" value="{{($heads['deposit']!='')?$heads['deposit']:'>Deposit A/c'}}"></div>
										<div class="col-sm-6">
											<input type="text" class="form-control other-account" id="acname_3" name="acname_3" value="{{$crow->acname3}}" autocomplete="off" placeholder="Deposit A/c." data-toggle="modal" data-target="#account_modal">
											<input type="hidden" id="acid_3" name="deposit" value="{{$crow->deposit}}">
										</div>
										<div class="col-sm-2"><label for="input-text">Tax Include</label>
											<input type="checkbox" class="form-control" name="d_tax" value="1" {{($crow->d_tax==1)?'checked':''}}>
										</div>
									</div>
									
									<div class="form-group">
										<div class="col-sm-3"><input type="text" name="txt_water_ecty" class="form-control" value="{{($heads['water_ecty']!='')?$heads['water_ecty']:'>Security Deposit A/c'}}"></div>
										<div class="col-sm-6">
											<input type="text" class="form-control other-account" id="acname_4" name="acname_4" value="{{$crow->acname4}}" autocomplete="off" placeholder="Security Deposit A/c." data-toggle="modal" data-target="#account_modal">
											<input type="hidden" id="acid_4" name="water_ecty" value="{{$crow->water_ecty}}">
										</div>
										<div class="col-sm-2"><label for="input-text">Tax Include</label>
											<input type="checkbox" class="form-control" name="we_tax" value="1" {{($crow->we_tax==1)?'checked':''}}>
										</div>
									</div>
									
									<div class="form-group">
										<div class="col-sm-3"><input type="text" name="txt_other_deposit" class="form-control" value="{{($heads['other_deposit']!='')?$heads['other_deposit']:'>Other Deposit A/c'}}"></div>
										<div class="col-sm-6">
											<input type="text" class="form-control other-account" id="acname_5" name="acname_5" value="{{$crow->acname5}}" autocomplete="off" placeholder="Other Deposit A/c." data-toggle="modal" data-target="#account_modal">
											<input type="hidden" id="acid_5" name="other_deposit" value="{{$crow->other_deposit}}">
										</div>
										<div class="col-sm-2"><label for="input-text">Tax Include</label>
											<input type="checkbox" class="form-control" name="od_tax" value="1" {{($crow->od_tax==1)?'checked':''}}>
										</div>
									</div>
									
									
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label"></label>
										<div class="col-sm-6">
											<button type="submit" class="btn btn-primary">Submit</button>
											 <a href="{{ url('contra_type') }}" class="btn btn-danger">Cancel</a>
										</div>
									</div>
									
								 </form>
								 
								 <div id="account_modal" class="modal fade animated" role="dialog">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal">&times;</button>
												<h4 class="modal-title">Select Account</h4>
											</div>
											<div class="modal-body" id="account_data">
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
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
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
        <!-- end of page level js -->

<script>
"use strict";

$(document).ready(function () {

     $('#frmContratype').validate({
        rules: {
            buildingid: {
                required: true,
                remote: {
                    url: "{{ url('contra_type/check_type') }}",
                    type: "get",
                    data: {
                        buildingid: function () {
                            return $('#buildingid').val();
                        },
						id: function () {
                            return $('#id').val(); // optional (for edit page)
                        }
                    }
                }
            },
            type: {
                required: true
            }
        },

        messages: {
            buildingid: {
                required: "Building is required and cannot be empty!",
                remote: "Contract type of this building is already created."
            },
            type: {
                required: "Type is required and cannot be empty!"
            }
        },

        errorElement: 'span',
        errorClass: 'help-block text-danger',

        highlight: function (element) {
            $(element).closest('.form-group').addClass('has-error');
        },
        unhighlight: function (element) {
            $(element).closest('.form-group').removeClass('has-error');
        }
    });
});

$(function(){
	var acurl = "{{ url('account_master/get_accounts/') }}";
	$(document).on('click', '.other-account', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		$('#account_data').load(acurl+'/'+curNum, function(result){ 
			$('#myModal').modal({show:true});
		});
	});
	
	//accounts select
	$(document).on('click', '.custRow, .accountRow', function(e) { 
		
		var num = $('#num').val();
		if($('#acname_'+num).length)
			$('#acname_'+num).val( $(this).attr("data-name") );
		
		if($('#acid_'+num).length)
			$('#acid_'+num).val( $(this).attr("data-id") );
		
		
		
	});
});
</script>
@stop
