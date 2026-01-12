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
                Other Account Settings
            </h1>
            <ol class="breadcrumb">
                <li>
                      <i class="fa fa-fw fa-shield"></i> Administration
                </li>
				<li>
                    <a href="#">Other Account Settings</a>
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
                <div class="col-md-12">
                    <div class="panel panel-primary">
                         <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-crosshairs"></i>Other Account Settings
                            </h3>
                        </div>
                        <div class="panel-body">
								<form class="form-horizontal" role="form" method="POST" name="frmOtherAc" id="frmOtherAc" action="{{ url('other_account_setting/update/') }}">
									<input type="hidden" name="_token" value="{{ csrf_token() }}">
									<?php $i=0;?>
									@foreach($accounts as $row)
									<?php $i++; ?>
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label">{{$row->account_setting_name}}</label>
										<div class="col-sm-6">
											<input type="text" name="account_name" id="otherac_{{$i}}" value="{{$row->code.' - '.$row->master_name}}" class="form-control other-account" autocomplete="off" data-toggle="modal" data-target="#account_modal">
											<input type="hidden" name="account_id[]" id="otheracid_{{$i}}" value="{{$row->account_id}}">
											<input type="hidden" name="id[]" value="{{$row->id}}">
											<input type="hidden" name="old_account_id[]" value="{{$row->account_id}}">
											<input type="hidden" name="actype[]" value="{{$row->account_setting_name}}">
										</div>
										{{--<div class="col-sm-3">Account Live</div>--}}
									</div>
									@endforeach
									<br/>
									
									@if($isdept)
									<fieldset>
									<legend><h5><b>Cost Accounts in Departments</b></h5></legend>
									@foreach($departments as $drow)
									@php $i++; @endphp
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label">Department</label>
										<div class="col-sm-9">
											<select class="form-control select2" style="width:100%" name="department_id[]">
												<option value="{{ $drow->id }}" >{{ $drow->name }}</option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label">Stock Account</label>
										<div class="col-sm-9">
											<input type="text" name="account_name[]" id="sacname_{{$i+$n}}" value="{{(isset($deptac[$j]))?$deptac[$j]->stock_acid.' - '.$deptac[$j]->stock_acname:''}}" class="form-control other-account" autocomplete="off" data-toggle="modal" data-target="#account_modal">
											<input type="hidden" name="stock_acid[]" id="sacid_{{$i+$n}}" value="{{(isset($deptac[$j]))?$deptac[$j]->stock_acid:''}}">
											<input type="hidden" name="stock_acid_old[]" value="{{(isset($deptac[$j]))?$deptac[$j]->stock_acid:''}}">
										</div>
									</div>
									@php $n++; @endphp
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label">Cost Account</label>
										<div class="col-sm-9">
											<input type="text" name="account_name[]" id="cacname_{{$i+$n}}" value="{{(isset($deptac[$j]))?$deptac[$j]->cost_acname:''}}" class="form-control other-account" autocomplete="off" data-toggle="modal" data-target="#account_modal">
											<input type="hidden" name="cost_acid[]" id="cacid_{{$i+$n}}" value="{{(isset($deptac[$j]))?$deptac[$j]->cost_acid:''}}">
											<input type="hidden" name="cost_acid_old[]" value="{{(isset($deptac[$j]))?$deptac[$j]->cost_acid:''}}">
										</div>
									</div> 
									@php $n++; @endphp
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label">Cost Difference Account</label>
										<div class="col-sm-9">
											<input type="text" name="account_name[]" id="cacname_{{$i+$n}}" value="{{(isset($deptac[$j]))?$deptac[$j]->costdif_acname:''}}" class="form-control other-account" autocomplete="off" data-toggle="modal" data-target="#account_modal">
											<input type="hidden" name="costdif_acid[]" id="cacid_{{$i+$n}}" value="{{(isset($deptac[$j]))?$deptac[$j]->costdif_acid:''}}">
											<input type="hidden" name="costdif_acid_old[]" value="{{(isset($deptac[$j]))?$deptac[$j]->costdif_acid:''}}">
										</div>
									</div> 
									@php $n++; @endphp
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label">Discount in Purchase</label>
										<div class="col-sm-9">
											<input type="text" name="account_name[]" id="cacname_{{$i+$n}}" value="{{(isset($deptac[$j]))?$deptac[$j]->purdis_acname:''}}" class="form-control other-account" autocomplete="off" data-toggle="modal" data-target="#account_modal">
											<input type="hidden" name="purdis_acid[]" id="cacid_{{$i+$n}}" value="{{(isset($deptac[$j]))?$deptac[$j]->purdis_acid:''}}">
											<input type="hidden" name="purdis_acid_old[]" value="{{(isset($deptac[$j]))?$deptac[$j]->purdis_acid:''}}">
										</div>
									</div> 
									@php $n++; @endphp
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label">Discount in Sales</label>
										<div class="col-sm-9">
											<input type="text" name="account_name[]" id="cacname_{{$i+$n}}" value="{{(isset($deptac[$j]))?$deptac[$j]->saledis_acname:''}}" class="form-control other-account" autocomplete="off" data-toggle="modal" data-target="#account_modal">
											<input type="hidden" name="saledis_acid[]" id="cacid_{{$i+$n}}" value="{{(isset($deptac[$j]))?$deptac[$j]->saledis_acid:''}}">
											<input type="hidden" name="saledis_acid_old[]" value="{{(isset($deptac[$j]))?$deptac[$j]->saledis_acid:''}}">
										</div>
									</div> 
									@php $n++; @endphp
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label">Stock Excess</label>
										<div class="col-sm-9">
											<input type="text" name="account_name[]" id="cacname_{{$i+$n}}" value="{{(isset($deptac[$j]))?$deptac[$j]->stockexcs_acname:''}}" class="form-control other-account" autocomplete="off" data-toggle="modal" data-target="#account_modal">
											<input type="hidden" name="stockexcs_acid[]" id="cacid_{{$i+$n}}" value="{{(isset($deptac[$j]))?$deptac[$j]->stock_excess_acid:''}}">
											<input type="hidden" name="stockexcs_acid_old[]" value="{{(isset($deptac[$j]))?$deptac[$j]->stock_excess_acid:''}}">
										</div>
									</div> 
									@php $n++; @endphp
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label">Stock Shortage</label>
										<div class="col-sm-9">
											<input type="text" name="account_name[]" id="cacname_{{$i+$n}}" value="{{(isset($deptac[$j]))?$deptac[$j]->stockshrtg_acname:''}}" class="form-control other-account" autocomplete="off" data-toggle="modal" data-target="#account_modal">
											<input type="hidden" name="stockshrtg_acid[]" id="cacid_{{$i+$n}}" value="{{(isset($deptac[$j]))?$deptac[$j]->stock_shortage_acid:''}}">
											<input type="hidden" name="stockshrtg_acid_old[]" value="{{(isset($deptac[$j]))?$deptac[$j]->stock_shortage_acid:''}}">
										</div>
									</div> 
									
									<br/>
									<input type="hidden" name="did[]" value="{{(isset($deptac[$j]))?$deptac[$j]->id:''}}">
									@php $j++; $n++; @endphp
									@endforeach
									</fieldset>
									
									@else
									<fieldset>
									<legend><h5><b>Cost Accounts</b></h5></legend>
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label">Stock Account</label>
										<div class="col-sm-9">
											<input type="text" name="account_name" id="otherac_{{$i+1}}" value="{{(isset($cas[0]))?$cas[0]->code.' - '.$cas[0]->master_name:''}}" class="form-control other-account" autocomplete="off" data-toggle="modal" data-target="#account_modal">
											<input type="hidden" name="caccount_id[]" id="otheracid_{{$i+1}}" value="{{(isset($cas[0]))?$cas[0]->account_id:''}}">
											<input type="hidden" name="caccount_id_old[]" value="{{(isset($cas[0]))?$cas[0]->account_id:''}}">
											<input type="hidden" name="cid[]" value="{{(isset($cas[0]))?$cas[0]->id:''}}">
										</div>
									</div>
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label">Cost Account</label>
										<div class="col-sm-9">
											<input type="text" name="account_name" id="otherac_{{$i+2}}" value="{{(isset($cas[1]))?$cas[1]->code.' - '.$cas[1]->master_name:''}}" class="form-control other-account" autocomplete="off" data-toggle="modal" data-target="#account_modal">
											<input type="hidden" name="caccount_id[]" id="otheracid_{{$i+2}}" value="{{(isset($cas[1]))?$cas[1]->account_id:''}}">
											<input type="hidden" name="caccount_id_old[]" value="{{(isset($cas[1]))?$cas[1]->account_id:''}}">
											<input type="hidden" name="cid[]" value="{{(isset($cas[1]))?$cas[1]->id:''}}">
										</div>
									</div>
									
									</fieldset>

									@endif
									
									 <div class="form-group">
										<label for="input-text" class="col-sm-5 control-label"></label>
										<div class="col-sm-4">
											<button type="submit" class="btn btn-primary">Submit</button>
											 <a href="{{ url('other_account_setting') }}" class="btn btn-danger">Cancel</a>
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

    $('#frmCompany').bootstrapValidator({
        fields: {
            company_name: {
                validators: {
                    notEmpty: {
                        message: 'The company name is required and cannot be empty!'
                    }
                }
            },
			email: {
                validators: {
                    notEmpty: {
                        message: 'The company name is required and cannot be empty!'
                    },
					regexp: {
                        regexp: /^\S+@\S{1,}\.\S{1,}$/,
                        message: 'Please enter valid email format'
                    }
                }
            },
			phone: {
                validators: {
                    notEmpty: {
                        message: 'The phone no is required and cannot be empty!'
                    }
                }
            },
			address: {
                validators: {
                    notEmpty: {
                        message: 'The address is required and cannot be empty!'
                    }
                }
            },
			city: {
                validators: {
                    notEmpty: {
                        message: 'The city is required and cannot be empty!'
                    }
                }
            },
			state: {
                validators: {
                    notEmpty: {
                        message: 'The state is required and cannot be empty!'
                    }
                }
            },
			country: {
                validators: {
                    notEmpty: {
                        message: 'The country is required and cannot be empty!'
                    }
                }
            },
			pin: {
                validators: {
                    notEmpty: {
                        message: 'The pin code is required and cannot be empty!'
                    }
                }
            }
          
        },
        submitHandler: function (validator, form, submitButton) {
            var fullName = [validator.getFieldElements('company_name').val(),
                validator.getFieldElements('company_name').val()
            ].join(' ');
            $('#helloModal')
                .find('.modal-title').html('Hello ' + fullName).end()
                .modal();
        }
    }).on('reset', function (event) {
        $('#frmCompany').data('bootstrapValidator').resetForm();
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
	$(document).on('click', '.custRow', function(e) { 
		var num = $('#num').val();
		if($('#otherac_'+num).length)
			$('#otherac_'+num).val( $(this).attr("data-name") );
		
		if($('#otheracid_'+num).length)
			$('#otheracid_'+num).val( $(this).attr("data-id") );
		
		if($('#sacname_'+num).length) {
			$('#sacname_'+num).val( $(this).attr("data-name") );
			$('#sacid_'+num).val( $(this).attr("data-id") );
		}
		
		if($('#cacname_'+num).length) {
			$('#cacname_'+num).val( $(this).attr("data-name") );
			$('#cacid_'+num).val( $(this).attr("data-id") );
		}
	});
});

</script>
@stop

