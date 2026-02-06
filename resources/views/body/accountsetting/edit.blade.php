@extends('layouts/default')

    {{-- Page title --}}
    @section('title')
         
        @parent
    @stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/iCheck/css/all.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/formelements.css')}}">
        <!--end of page level css-->
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <!--section starts-->
            <h1>
                Account Setting
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="fa fa-fw fa-briefcase"></i> Accounts
                    </a>
                </li>
                <li>
                    <a href="#">Account Setting</a>
                </li>
                <li class="active">
                    Edit
                </li>
            </ol>
        </section>
        <!--section ends-->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-crosshairs"></i> Edit 
                            </h3>
                           
                        </div>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" method="POST" name="frmVoucher" id="frmVoucher" action="{{ url('account_setting/update/'.$settingrow->id) }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="setting_id" value="{{ $settingrow->id }}">
								<input type="hidden" name="voucher_type_id" value="{{ $settingrow->voucher_type_id }}">
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Voucher Type</label>
                                    <div class="col-sm-8">
                                        <select id="voucher_type_id" class="form-control select2" disabled style="width:100%" name="voucher_type_id">
											@foreach ($vouchertype as $type)
											@if($settingrow->voucher_type_id==$type['id'])
											@php $sel = "selected" @endphp
											@else
											@php $sel = "" @endphp	
											@endif
											<option value="{{ $type['id'] }}" {{ $sel }}>{{ $type['name'] }}</option>
											@endforeach
                                        </select>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Department </label>
                                    <div class="col-sm-8">
                                        <select id="department_id" class="form-control select2" style="width:100%" name="department_id">
                                            <option value="">Select Department...</option>
											@foreach ($department as $dept)
											@if($settingrow->department_id==$dept['id'])
											@php $sel = "selected" @endphp
											@else
											@php $sel = "" @endphp	
											@endif
											<option value="{{ $dept['id'] }}" {{$sel}}>{{ $dept['name'] }}</option>
											@endforeach
                                        </select>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Voucher Name</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="voucher_name" name="voucher_name" value="{{$settingrow->voucher_name}}">
                                    </div>
                                </div>
                                
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Prefix</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="prefix" name="prefix" value="{{$settingrow->prefix}}">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Prefix Enable</label>
									@if($settingrow->is_prefix==0)
									@php $chk1 = "checked";
											$chk2 = "";
									@endphp
									@else
									@php $chk2 = "checked";
											$chk1 = "";
									@endphp	
									@endif
									<div class="col-sm-8">
                                        <label class="radio-inline iradio">
                                            <input type="radio" id="inlineradio1" name="is_prefix" value="0" {{$chk1}}>
                                            No
                                        </label>
                                        <label class="radio-inline iradio">
                                            <input type="radio" id="inlineradio2" name="is_prefix" value="1" {{$chk2}}>
                                            Yes
                                        </label>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Voucher Number</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="voucher_no" name="voucher_no" value="{{$settingrow->voucher_no}}">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Description</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="description" name="description" value="{{$settingrow->description}}" placeholder="Description">
                                    </div>
                                </div>
								
								<?php if($settingrow->voucher_type_id==9 || $settingrow->voucher_type_id==10) { ?>
								<div id="dracnts">
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label"><span class="dc">Debit</span> Account(Cash)</label>
										<div class="col-sm-8">
											<select id="drcash_account_master_id" class="form-control select2" style="width:100%" name="drcash_account_master_id">
												@foreach ($cashacs as $acc)
													@if($settingrow->cash_account_id==$acc['id'])
													@php $sel = "selected" @endphp
													@else
													@php $sel = "" @endphp	
													@endif
													<option value="{{ $acc['id'] }}" {{$sel}}>{{ $acc['master_name'] }}</option>
												@endforeach
											</select>
											<input type="hidden" name="drcash_account_master_id_old" value="{{$settingrow->cash_account_id}}">
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label"><span class="dc">Debit</span> Account(Bank)</label>
										<div class="col-sm-8">
											<select id="drbank_account_master_id" class="form-control select2" style="width:100%" name="drbank_account_master_id">
												@foreach ($bankacs as $acc)
													@if($settingrow->bank_account_id==$acc['id'])
													@php $sel = "selected" @endphp
													@else
													@php $sel = "" @endphp	
													@endif
													<option value="{{ $acc['id'] }}" {{$sel}}>{{ $acc['master_name'] }}</option>
												@endforeach
											</select>
											<input type="hidden" name="drbank_account_master_id_old" value="{{$settingrow->bank_account_id}}">
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label"><span class="dc">Debit</span> Account(PDC)</label>
										<div class="col-sm-8">
											<select id="drpdc_account_master_id" class="form-control select2" style="width:100%" name="drpdc_account_master_id">
												@foreach ($pdcs as $acc)
												@if($settingrow->pdc_account_id==$acc['id'])
													@php $sel = "selected" @endphp
													@else
													@php $sel = "" @endphp	
													@endif
												<option value="{{ $acc['id'] }}" {{$sel}}>{{ $acc['master_name'] }}</option>
												@endforeach
											</select>
											<input type="hidden" name="drpdc_account_master_id_old" value="{{$settingrow->pdc_account_id}}">
										</div>
									</div>
								</div>
								<?php } else if($settingrow->voucher_type_id==27) { ?>
								<div id="dracnts">
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label"><span class="dc"></span> Account(Cash)</label>
										<div class="col-sm-8">
											<select id="drcash_account_master_id" class="form-control select2" style="width:100%" name="drcash_account_master_id">
												@foreach ($cashacs as $acc)
													@if($settingrow->cash_account_id==$acc['id'])
													@php $sel = "selected" @endphp
													@else
													@php $sel = "" @endphp	
													@endif
													<option value="{{ $acc['id'] }}" {{$sel}}>{{ $acc['master_name'] }}</option>
												@endforeach
											</select>
											<input type="hidden" name="drcash_account_master_id_old" value="{{$settingrow->cash_account_id}}">
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label"><span class="dc"></span> Account(Bank)</label>
										<div class="col-sm-8">
											<select id="drbank_account_master_id" class="form-control select2" style="width:100%" name="drbank_account_master_id">
												@foreach ($bankacs as $acc)
													@if($settingrow->bank_account_id==$acc['id'])
													@php $sel = "selected" @endphp
													@else
													@php $sel = "" @endphp	
													@endif
													<option value="{{ $acc['id'] }}" {{$sel}}>{{ $acc['master_name'] }}</option>
												@endforeach
											</select>
											<input type="hidden" name="drbank_account_master_id_old" value="{{$settingrow->bank_account_id}}">
										</div>
									</div>
									
									
								</div>
								<?php } else if($settingrow->voucher_type_id==15) { ?>
									<div id="drcracnts">
									<spn id="trin"><b>Transfer In Accounts</b><hr/></span>
										<div class="form-group">
											<label for="input-text" class="col-sm-3 control-label">Debit Account</label>
											<div class="col-sm-8">
												<select id="dr_account_master_id" class="form-control select2" style="width:100%" name="dr_account_master_id">
													<option value="">Select Debit Account...</option>
													@foreach ($accounts as $acc)
													@if($settingrow->dr_account_master_id==$acc['id'])
													@php $sel = "selected" @endphp
													@else
													@php $sel = "" @endphp	
													@endif
													<option value="{{ $acc['id'] }}" {{$sel}}>{{ $acc['master_name'] }}</option>
													@endforeach
												</select>
												<input type="hidden" name="dr_account_master_id_old" value="{{$settingrow->dr_account_master_id}}">
											</div>
										</div>
										
										<div class="form-group">
											<label for="input-text" class="col-sm-3 control-label">Credit Account</label>
											<div class="col-sm-8">
												<select id="cr_account_master_id" class="form-control select2" style="width:100%" name="cr_account_master_id">
													<option value="">Select Credit Account...</option>
													@foreach ($accounts as $acc)
													@if($settingrow->cr_account_master_id==$acc['id'])
													@php $sel = "selected" @endphp
													@else
													@php $sel = "" @endphp	
													@endif
													<option value="{{ $acc['id'] }}" {{$sel}}>{{ $acc['master_name'] }}</option>
													@endforeach
												</select>
												<input type="hidden" name="cr_account_master_id_old" value="{{$settingrow->cr_account_master_id}}">
											</div>
										</div>
									</div>

									<div id="trout">
										<b>Transfer Out Accounts</b><hr/>
										<div class="form-group">
											<label for="input-text" class="col-sm-3 control-label">Debit Account</label>
											<div class="col-sm-8">
												<select id="dr_account_master_id_TO" class="form-control select2" style="width:100%" name="dr_account_master_id_TO">
													<option value="">Select Debit Account...</option>
													@foreach ($accounts as $acc)
													@if($settingrow->dr_account_master_id_to==$acc['id'])
													@php $sel = "selected" @endphp
													@else
													@php $sel = "" @endphp	
													@endif
													<option value="{{ $acc['id'] }}" {{$sel}}>{{ $acc['master_name'] }}</option>
													@endforeach
												</select>
												<input type="hidden" name="dr_account_master_id_to_old" value="{{$settingrow->dr_account_master_id_to}}">
											</div>
										</div>
										
										<div class="form-group">
											<label for="input-text" class="col-sm-3 control-label">Credit Account</label>
											<div class="col-sm-8">
												<select id="cr_account_master_id_TO" class="form-control select2" style="width:100%" name="cr_account_master_id_TO">
													<option value="">Select Credit Account...</option>
													@foreach ($accounts as $acc)
													@if($settingrow->cr_account_master_id_to==$acc['id'])
													@php $sel = "selected" @endphp
													@else
													@php $sel = "" @endphp	
													@endif
													<option value="{{ $acc['id'] }}" {{$sel}}>{{ $acc['master_name'] }}</option>
													@endforeach
												</select>
												<input type="hidden" name="cr_account_master_id_to_old" value="{{$settingrow->cr_account_master_id_to}}">
											</div>
										</div>
									</div>
								<?php } else { ?>
									<div id="dftac">
										<div class="form-group">
											<label for="input-text" class="col-sm-3 control-label">Debit Account</label>
											<div class="col-sm-8">
												<select id="dr_account_master_id" class="form-control select2" style="width:100%" name="dr_account_master_id">
													<option value="">Select Debit Account...</option>
													@foreach ($accounts as $acc)
													@if($settingrow->dr_account_master_id==$acc['id'])
													@php $sel = "selected" @endphp
													@else
													@php $sel = "" @endphp	
													@endif
													<option value="{{ $acc['id'] }}" {{$sel}}>{{ $acc['master_name'] }}</option>
													@endforeach
												</select>
												<input type="hidden" name="dr_account_master_id_old" value="{{$settingrow->dr_account_master_id}}">
											</div>
										</div>
										
										<div class="form-group">
											<label for="input-text" class="col-sm-3 control-label">Credit Account</label>
											<div class="col-sm-8">
												<select id="cr_account_master_id" class="form-control select2" style="width:100%" name="cr_account_master_id">
													<option value="">Select Credit Account...</option>
													@foreach ($accounts as $acc)
													@if($settingrow->cr_account_master_id==$acc['id'])
													@php $sel = "selected" @endphp
													@else
													@php $sel = "" @endphp	
													@endif
													<option value="{{ $acc['id'] }}" {{$sel}}>{{ $acc['master_name'] }}</option>
													@endforeach
												</select>
												<input type="hidden" name="cr_account_master_id_old" value="{{$settingrow->cr_account_master_id}}">
											</div>
										</div>
									</div>
								<?php } ?>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Cash Voucher</label>
									@if($settingrow->is_cash_voucher==0)
									@php $chk1 = "checked";
											$chk2 = "";
									@endphp
									@else
									@php $chk2 = "checked";
											$chk1 = "";
									@endphp	
									@endif
									<div class="col-sm-8">
                                        <label class="radio-inline iradio">
                                            <input type="radio" id="inlineradio3" name="is_cash_voucher" value="0" {{$chk1}}>
                                            No
                                        </label>
                                        <label class="radio-inline iradio">
                                            <input type="radio" id="inlineradio4" name="is_cash_voucher" value="1" {{$chk2}}>
                                            Yes
                                        </label>
                                    </div>
                                </div>
								
								<div class="form-group">
									<label for="input-text" class="col-sm-3 control-label">Cash Account(Default)</label>
									<div class="col-sm-8">
										<select id="cash_account_id" class="form-control select2" style="width:100%" name="cash_account_id">
											<option value="">Select Cash Account...</option>
											@foreach ($accounts as $acc)
											@if($settingrow->default_account_id==$acc['id'])
											@php $sel = "selected" @endphp
											@else
											@php $sel = "" @endphp	
											@endif
											<option value="{{ $acc['id'] }}" {{$sel}}>{{ $acc['master_name'] }}</option>
											@endforeach
										</select>
										<input type="hidden" name="cash_account_id_old" value="{{$settingrow->default_account_id}}">
									</div>
								</div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Voucher Status</label>
									@if($settingrow->status==0)
									@php $act1 = "checked";
											$act2 = "";
									@endphp
									@else
									@php $act2 = "checked";
											$act1 = "";
									@endphp	
									@endif
									<div class="col-sm-8">
                                        <label class="radio-inline iradio">
                                            <input type="radio" id="inlineradio1" name="status" value="0" {{$act1}}>
                                            Disable
                                        </label>
                                        <label class="radio-inline iradio">
                                            <input type="radio" id="inlineradio2" name="status" value="1" {{$act2}}>
                                            Enable
                                        </label>
                                    </div>
                                </div>
								
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label"></label>
                                    <div class="col-sm-8">
                                        <button type="submit" class="btn btn-primary">Submit</button>
										<a href="{{ url('account_setting') }}" class="btn btn-danger">Cancel</a>
                                    </div>
                                </div>
                                                            
                                
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
<script type="text/javascript" src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/iCheck/js/icheck.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-fileinput/js/fileinput.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/custom_js/form_elements.js')}}"></script>
        <!-- end of page level js -->

<script>
"use strict";

$(document).ready(function () {
	
	<?php if($settingrow->is_cash_voucher==1) { ?>
		$("#cash_account_id").prop('disabled', false);
	<?php } else { ?>
		$("#cash_account_id").prop('disabled', true);
	<?php } ?>
		
	$('#inlineradio4').on('ifChecked', function(event){ 
		$("#cash_account_id").prop('disabled', false);
	});
	$('#inlineradio3').on('ifChecked', function(event){ 
		$("#cash_account_id").prop('disabled', true);
	});
	
	var url = "{{ url('account_setting/checkname/') }}";
    $('#frmVoucher').bootstrapValidator({
        fields: {
			voucher_type_id: { validators: { notEmpty: { message: 'The voucher type is required and cannot be empty!' } }},
			voucher_name: {
                validators: {
                    notEmpty: {
                        message: 'The voucher name is required and cannot be empty!'
                    },
					remote: {
                        url: url,
                        data: function(validator) {
                            return {
                                voucher_name: validator.getFieldElements('voucher_name').val(),
								id: validator.getFieldElements('setting_id').val()
                            };
                        },
                        message: 'The voucher name is not available'
                    }
                }
            },
			voucher_no: { validators: { notEmpty: { message: 'The voucher no is required and cannot be empty!' } }},
			drcash_account_master_id: { validators: { notEmpty: { message: 'The cash account is required and cannot be empty!' } }},
			drbank_account_master_id: { validators: { notEmpty: { message: 'The bank account is required and cannot be empty!' } }},
			drpdc_account_master_id: { validators: { notEmpty: { message: 'The pdc account is required and cannot be empty!' } }},
			//dr_account_master_id: { validators: { notEmpty: { message: 'The debit account is required and cannot be empty!' } }},
        }
        
    }).on('reset', function (event) {
        $('#frmVoucher').data('bootstrapValidator').resetForm();
    });
});

$(function(){
	
	$('#voucher_type_id').on('change', function(e){
		var vchr_id = e.target.value;
		if(vchr_id==9 || vchr_id==10) {
			if( $('#dracnts').is(":hidden") ) 
				$('#dracnts').toggle(); 
			if( $('#drcracnts').is(":visible") ) 
				$('#drcracnts').toggle();
			if(vchr_id==9)
				$('.dc').text('Debit');
			else if(vchr_id==10)
				$('.dc').text('Credit');
			var cat = (vchr_id==9)?'PDCR':'PDCI';
			$.get("{{ url('account_master/get_account/') }}/" + cat, function(data) {
				$('#drpdc_account_master_id').empty();
				$('#drpdc_account_master_id').append('<option value="">Select Account...</option>');
				$.each(data, function(value, display){
					 $('#drpdc_account_master_id').append('<option value="' + display.id + '">' + display.master_name + '</option>');
				});
			});
			$('#trout').hide();$('#trin').hide();
		} else if(vchr_id==15) {
			if($('#trout').is(':hidden')) {
				$('#trout').show();
				$('#trin').show();
			}
		} else {
			$('#trout').hide();$('#trin').hide();
			if( $('#drcracnts').is(":hidden") ) 
				$('#drcracnts').toggle(); 
			if( $('#dracnts').is(":visible") ) 
				$('#dracnts').toggle(); 
		}
	});
	
	
});
</script>
@stop
