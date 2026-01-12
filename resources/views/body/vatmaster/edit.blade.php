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
        <!--end of page level css-->
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/buttons.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/colReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/dataTables.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/rowReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/scroller.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatablesmark.js/css/datatables.mark.min.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/responsive_datatables.css')}}">
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <!--section starts-->
            <h1>
                Vat Master
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="">
                        <i class="fa fa-fw fa-wrench"></i> Maintenance
                    </a>
                </li>
                <li>
                    <a href="#">Vat Master</a>
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
                                <i class="fa fa-fw fa-crosshairs"></i> Edit Vat Master 
                            </h3>
                           
                        </div>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" method="POST" name="frmVatMaster" id="frmVatMaster" action="{{ url('vat_master/update/'.$vatrow->id) }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" name="vat_id" value="<?php echo $vatrow->id; ?>">
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Vat Code</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="code" name="code"  value="{{ $vatrow->code }}">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Vat Name</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="name" name="name" value="{{ $vatrow->name }}">
                                    </div>
                                </div>
                                
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Vat Percentage</label>
                                    <div class="col-sm-10">
                                        <input type="number" step="any" class="form-control" id="percentage" name="percentage" value="{{ $vatrow->percentage }}">
                                    </div>
                                </div>
								
								@if($isdept)
								<fieldset>
									<legend><h5><b>VAT Accounts in Departments</b></h5></legend>
									<?php $i=0;?>
									@foreach($departments as $drow)
										@php $i++; @endphp
										<div class="form-group">
											<label for="input-text" class="col-sm-2 control-label">Department</label>
											<div class="col-sm-10">
												<select class="form-control select2" style="width:100%" name="department_id[]">
													<option value="{{ $drow->id }}" >{{ $drow->name }}</option>
												</select>
											</div>
										</div>
										<div class="form-group">
											<label for="input-text" class="col-sm-2 control-label">VAT Input Account</label>
											<div class="col-sm-10">
												<input type="hidden" name="collection_account_old[]" value="{{(isset($deptac[$j]))?$deptac[$j]->collection_account:''}}">
												<input type="hidden" name="collection_account[]" id="vatacid_{{$i+$n}}" value="{{(isset($deptac[$j]))?$deptac[$j]->collection_account:''}}">
												<input type="text" name="vat_inputac[]" id="vatac_{{$i+$n}}" value="{{(isset($deptac[$j]))?$deptac[$j]->coll_acc_name:''}}" class="form-control vat-account" autocomplete="off" data-toggle="modal" data-target="#account_modal">
											</div>
										</div>
										
										@php $n++; @endphp
										<div class="form-group">
											<label for="input-text" class="col-sm-2 control-label">VAT Output Account</label>
											<div class="col-sm-10">
												<input type="text" name="vat_outputac[]" id="vatac_{{$i+$n}}" value="{{(isset($deptac[$j]))?$deptac[$j]->pymt_acc_name:''}}" class="form-control vat-account" autocomplete="off" data-toggle="modal" data-target="#account_modal">
												<input type="hidden" name="payment_account[]" id="vatacid_{{$i+$n}}" value="{{(isset($deptac[$j]))?$deptac[$j]->payment_account:''}}">
												<input type="hidden" name="payment_account_old[]" value="{{(isset($deptac[$j]))?$deptac[$j]->payment_account:''}}">
											</div>
										</div>
										@php $n++; @endphp
										<div class="form-group">
											<label for="input-text" class="col-sm-2 control-label">VAT Input on Import</label>
											<div class="col-sm-10">
												<input type="hidden" name="vatinput_import[]" id="vatacid_{{$i+$n}}" value="{{(isset($deptac[$j]))?$deptac[$j]->vatinput_import:''}}">
												<input type="hidden" name="vatinput_import_old[]" value="{{(isset($deptac[$j]))?$deptac[$j]->vatinput_import:''}}">
												<input type="text" name="vat_input_import[]" id="vatac_{{$i+$n}}" value="{{(isset($deptac[$j]))?$deptac[$j]->vatip_imprt_name:''}}" class="form-control vat-account" autocomplete="off" data-toggle="modal" data-target="#account_modal">
											</div>
										</div>
										@php $n++; @endphp
										<div class="form-group">
											<label for="input-text" class="col-sm-2 control-label">VAT Output on Import</label>
											<div class="col-sm-10">
												<input type="text" name="vat_output_import[]" id="vatac_{{$i+$n}}" value="{{(isset($deptac[$j]))?$deptac[$j]->vatop_imprt_name:''}}" class="form-control vat-account" autocomplete="off" data-toggle="modal" data-target="#account_modal">
												<input type="hidden" name="vatoutput_import[]" id="vatacid_{{$i+$n}}" value="{{(isset($deptac[$j]))?$deptac[$j]->vatoutput_import:''}}">
												<input type="hidden" name="vatoutput_import_old[]" value="{{(isset($deptac[$j]))?$deptac[$j]->vatoutput_import:''}}">
											</div>
										</div>
										@php $n++; @endphp
										<div class="form-group">
											<label for="input-text" class="col-sm-2 control-label">VAT Expense Account</label>
											<div class="col-sm-10">
												<input type="text" name="expense_ac[]" id="vatac_{{$i+$n}}" value="{{(isset($deptac[$j]))?$deptac[$j]->exp_acc_name:''}}" class="form-control vat-account" autocomplete="off" data-toggle="modal" data-target="#account_modal">
												<input type="hidden" name="expense_account[]" id="vatacid_{{$i+$n}}" value="{{(isset($deptac[$j]))?$deptac[$j]->expense_account:''}}">
												<input type="hidden" name="expense_account_old[]" value="{{(isset($deptac[$j]))?$deptac[$j]->expense_account:''}}">
											</div>
										</div>
										
										<input type="hidden" name="did[]" value="{{(isset($deptac[$j]))?$deptac[$j]->department_id:''}}">
										<input type="hidden" name="id[]" value="{{(isset($deptac[$j]))?$deptac[$j]->id:''}}">
										@php $j++; $n++; @endphp
										<hr/>
									@endforeach
								</fieldset>
								@else
								<div class="form-group">
									<label for="input-text" class="col-sm-2 control-label">VAT Input Account</label>
									<div class="col-sm-10">
										<input type="hidden" name="collection_account" id="vatacid_1" value="{{$vatrow->collection_account}}">
										<input type="hidden" name="collection_account_old" value="{{$vatrow->collection_account}}">
										<input type="text" name="vat_inputac" id="vatac_1" class="form-control vat-account" value="{{$vatrow->coll_acc_name}}" autocomplete="off" data-toggle="modal" data-target="#account_modal">
									</div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">VAT Output Account</label>
                                    <div class="col-sm-10">
										<input type="text" name="vat_outputac" id="vatac_2" class="form-control vat-account" value="{{$vatrow->pymt_acc_name}}" autocomplete="off" data-toggle="modal" data-target="#account_modal">
										<input type="hidden" name="payment_account" id="vatacid_2" value="{{$vatrow->payment_account}}">
										<input type="hidden" name="payment_account_old" value="{{$vatrow->payment_account}}">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">VAT Input on Import</label>
                                    <div class="col-sm-10">
										<input type="hidden" name="vatinput_import" id="vatacid_3" value="{{$vatrow->vatinput_import}}">
										<input type="hidden" name="vatinput_import_old" value="{{$vatrow->vatinput_import}}">
										<input type="text" name="vat_input_import" id="vatac_3" class="form-control vat-account" value="{{$vatrow->vatip_imprt_name}}" autocomplete="off" data-toggle="modal" data-target="#account_modal">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">VAT Output on Import</label>
                                    <div class="col-sm-10">
										<input type="text" name="vat_output_import" id="vatac_4" class="form-control vat-account" value="{{$vatrow->vatop_imprt_name}}" autocomplete="off" data-toggle="modal" data-target="#account_modal">
										<input type="hidden" name="vatoutput_import" id="vatacid_4" value="{{$vatrow->vatoutput_import}}">
										<input type="hidden" name="vatoutput_import_old" value="{{$vatrow->vatoutput_import}}">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">VAT Expense Account</label>
                                    <div class="col-sm-10">
										<input type="text" name="expense_ac" id="vatac_5" class="form-control vat-account" value="{{$vatrow->exp_acc_name}}" autocomplete="off" data-toggle="modal" data-target="#account_modal">
										<input type="hidden" name="expense_account" id="vatacid_5" value="{{$vatrow->expense_account}}">
										<input type="hidden" name="expense_account_old" value="{{$vatrow->expense_account}}">
                                    </div>
                                </div>
								@endif
								
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                        <a href="{{ url('vat_master') }}" class="btn btn-danger">Cancel</a>
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
											<div class="modal-footer">
												<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
											</div>
										</div>
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

<script>
"use strict";

$(document).ready(function () {
	var urlcode = "{{ url('vat_master/checkcode/') }}";
	var urlname = "{{ url('vat_master/checkname/') }}";
    $('#frmVatMaster').bootstrapValidator({
        fields: {
            code: {
                validators: {
                    notEmpty: {
                        message: 'The vat code is required and cannot be empty!'
                    },
					remote: {
                        url: urlcode,
                        data: function(validator) {
                            return {
                                code: validator.getFieldElements('code').val(),
								id: validator.getFieldElements('vat_id').val()
                            };
                        },
                        message: 'The vat code is not available'
                    }
                }
            },
			name: {
                validators: {
                    notEmpty: {
                        message: 'The vat name is required and cannot be empty!'
                    },
					remote: {
                        url: urlname,
                        data: function(validator) {
                            return {
                                code: validator.getFieldElements('name').val(),
								id: validator.getFieldElements('vat_id').val(),
                            };
                        },
                        message: 'The vat name is not available'
                    }
                }
            },
			percentage: {
                validators: {
                    notEmpty: {
                        message: 'The percentage is required and cannot be empty!'
                    }
                }
            },
			collection_account: {
                validators: {
                    notEmpty: {
                        message: 'The collection account is required and cannot be empty!'
                    }
                }
            },
			payment_account: {
                validators: {
                    notEmpty: {
                        message: 'The payment account is required and cannot be empty!'
                    }
                }
            }
          
        }
        
    }).on('reset', function (event) {
        $('#frmVatMaster').data('bootstrapValidator').resetForm();
    });
	
	$(function(){
	var acurl = "{{ url('account_master/get_accounts/') }}";
	$(document).on('click', '.vat-account', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		$('#account_data').load(acurl+'/'+curNum, function(result){ 
			$('#myModal').modal({show:true});
		});
	});
	
	$(document).on('click', '.custRow', function(e) { 
		var num = $('#num').val();
		$('#vatac_'+num).val( $(this).attr("data-name") );
		$('#vatacid_'+num).val( $(this).attr("data-id") );
	});
});
});
</script>
@stop
