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
	
	<link rel="stylesheet" href="{{asset('assets/vendors/datetime/css/jquery.datetimepicker.css')}}">
    <link href="{{asset('assets/vendors/airdatepicker/css/datepicker.min.css')}}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/datepicker.css')}}">
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
                    <a href="index">
                        <i class="fa fa-fw fa-briefcase"></i> Inventory
                    </a>
                </li>
                <li>
                    <a href="#">Purchase Order</a>
                </li>
                <li class="active">
                    Add
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
                                <i class="fa fa-fw fa-crosshairs"></i> New 
                            </h3>
                           
                        </div>
                        <div class="panel-body">
							<div class="controls"> 
                            <form class="form-horizontal" role="form" method="POST" name="frmVoucher" id="frmVoucher" action="{{ url('account_setting/save') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Voucher No.</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="voucher_no" name="voucher_no" placeholder="Voucher No.">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Reference No.</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="reference_no" name="reference_no" placeholder="Reference No.">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Voucher Date</label>
                                    <div class="col-sm-10">
										<input type="text" class="form-control pull-right" name="voucher_date" data-language='en' id="voucher_date" placeholder="Voucher Date"/>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">LPO Date</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="lpo_date" name="lpo_date" data-language='en' placeholder="LPO Date">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"> Description</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="description" name="description" placeholder="Description">
                                    </div>
                                </div>
                                
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Terms</label>
                                    <div class="col-sm-10">
                                        <select id="terms_id" class="form-control select2" style="width:100%" name="terms_id">
                                            <option value="">Select Terms...</option>
											@foreach ($terms as $term)
											<option value="{{ $term['id'] }}">{{ $term['description'] }}</option>
											@endforeach
                                        </select>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Job</label>
                                    <div class="col-sm-10">
                                        <select id="job_id" class="form-control select2" style="width:100%" name="job_id">
                                            <option value="">Select Job...</option>
											@foreach($jobs as $job)
											<option value="{{ $job['id'] }}">{{ $job['name'] }}</option>
											@endforeach
                                        </select>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Supplier</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="supplier_name" id="supplier_name" class="form-control" autocomplete="off" data-toggle="modal" data-target="#supplier_modal" placeholder="Supplier">
										<input type="hidden" name="supplier_id" id="supplier_id">
									</div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"> Foreign Currency</label>
									<div class="col-xs-10">
										<div class="col-xs-1">
											<label class="radio-inline iradio">
											<input type="checkbox" class="custom_icheck" id="is_fc" name="is_fc" value="1">
										</label>
										</div>
										<div class="col-xs-5">
											<select id="currency_id" class="form-control select2" style="width:100%" name="currency_id">
												<option value="">Select Foreign Currency...</option>
												@foreach($currency as $curr)
												<option value="{{$curr['id']}}">{{$curr['name']}}</option>
												@endforeach
											</select>
										</div>
										<div class="col-xs-5">
											<input type="text" name="currency_rate" id="currency_rate" class="form-control" placeholder="Currency Rate">
										</div>
									</div>

                                </div>
								<br/>
								<fieldset>
								<legend><h5>Item Details</h5></legend>
								<div class="itemdivPrnt">
									<div class="itemdivChld">							
										<div class="form-group">
											<div class="col-sm-2">
												<input type="text" name="item_code[]" class="form-control" autocomplete="off" data-toggle="modal" data-target="#grid_modal" placeholder="Item Code">
											</div>
											<div class="col-xs-10">
												
												<div class="col-xs-2">
													<input type="text" name="title[]" class="form-control" placeholder="Item Description">
												</div>
												<div class="col-xs-2">
													<input type="text" name="title[]" class="form-control" placeholder="Unit">
												</div>
												<div class="col-xs-2">
													<input type="text" name="desc[]" class="form-control" placeholder="Qty.">
												</div>
												<div class="col-xs-2">
													<input type="text" name="desc[]" class="form-control" placeholder="Cost/Unit">
												</div>
												<div class="col-xs-2">
													<input type="text" name="desc[]" class="form-control" placeholder="Discount">
												</div>
												<div class="col-xs-2">
													<input type="text" name="desc[]" class="form-control" placeholder="Total">
												</div>
												<div class="col-xs-1">
													 <button type="button" class="btn btn-success btn-add-item" >
														<span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span>
													 </button>
												</div>
											</div>
										</div>
									</div>
								</div>
								</fieldset>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Total Amount</label>
                                    <div class="col-xs-10">
										<div class="col-xs-3"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2">
											<input type="text" name="desc[]" class="form-control" placeholder="Total">
										</div>
										<div class="col-xs-1"></div>
									</div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Discount</label>
                                    <div class="col-xs-10">
										<div class="col-xs-3"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2">
											<input type="text" name="desc[]" class="form-control" placeholder="Discount">
										</div>
										<div class="col-xs-1"></div>
									</div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Net Amount</label>
                                    <div class="col-xs-10">
										<div class="col-xs-3"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2">
											<input type="text" name="desc[]" class="form-control" placeholder="Net Amount">
										</div>
										<div class="col-xs-1"></div>
									</div>
                                </div>
								
								<hr/>
								<div class="infodivPrnt">
									<div class="infodivChld">							
										<div class="form-group">
											<label for="input-text" class="col-sm-2 control-label">Additional Info</label>
										   
											<div class="col-xs-10">
												<div class="col-xs-5">
													<input type="text" name="title[]" class="form-control" placeholder="Title">
												</div>
												<div class="col-xs-5">
													<input type="text" name="desc[]" class="form-control" placeholder="Description">
												</div>
												<div class="col-xs-1">
													 <button type="button" class="btn btn-success btn-add-info" >
														<span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> Add more
													 </button>
												</div>
											</div>
										</div>
									</div>
								</div>
								<br/>
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary">Submit</button>
										<a href="{{ url('account_setting') }}" class="btn btn-danger">Cancel</a>
                                    </div>
                                </div>
                                                            
                            <div id="supplier_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Select Supplier</h4>
                                        </div>
                                        <div class="modal-body">
                                            
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>   
								
                            <div id="grid_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Modal with grid arrangement</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6">col-md-6</div>
                                                <div class="col-md-6">col-md-6</div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">col-md-12
                                                    <div class="row">
                                                        <div class="col-md-6">col-md-6</div>
                                                        <div class="col-md-6">col-md-6</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">col-md-6</div>
                                                <div class="col-md-6">col-md-6</div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
							
                            </form>
							</div>
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

<script src="{{asset('assets/vendors/moment/js/moment.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/datetime/js/jquery.datetimepicker.full.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.en.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/custom_js/advanceddate_pickers.js')}}"></script>


<script>
"use strict";

$(document).ready(function () {
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
                                voucher_name: validator.getFieldElements('voucher_name').val()
                            };
                        },
                        message: 'The voucher name is not available'
                    }
                }
            },
			voucher_no: { validators: { notEmpty: { message: 'The voucher no is required and cannot be empty!' } }},
			dr_account_master_id: { validators: { notEmpty: { message: 'The debit account is required and cannot be empty!' } }},
        }
        
    }).on('reset', function (event) {
        $('#frmVoucher').data('bootstrapValidator').resetForm();
    });
	
	
});

$(function()
{
  
	
	$(document).on('click', '.btn-add-item', function(e) 
    { 
        e.preventDefault();
        var controlForm = $('.controls .itemdivPrnt'),
            currentEntry = $(this).parents('.itemdivChld:first'),
            newEntry = $(currentEntry.clone()).appendTo(controlForm);
//console.log(controlForm);
        newEntry.find('input').val('');
        controlForm.find('.btn-add-item:not(:last)')
            .removeClass('btn-default').addClass('btn-danger')
            .removeClass('btn-add-item').addClass('btn-remove-item')
            .html('<span class="glyphicon glyphicon-minus" aria-hidden="true"></span> ');
    }).on('click', '.btn-remove-item', function(e)
    { 
		$(this).parents('.itemdivChld:first').remove();

		e.preventDefault();
		return false;
	});
	
	$(document).on('click', '.btn-add-info', function(e) 
    { 
        e.preventDefault();

        var controlForm = $('.controls .infodivPrnt'),
            currentEntry = $(this).parents('.infodivChld:first'),
            newEntry = $(currentEntry.clone()).appendTo(controlForm);
			newEntry.find('input').val('');
			controlForm.find('.btn-add-info:not(:last)')
            .removeClass('btn-default').addClass('btn-danger')
            .removeClass('btn-add-info').addClass('btn-remove-info')
            .html('<span class="glyphicon glyphicon-minus" aria-hidden="true"></span> Remove ');
    }).on('click', '.btn-remove-info', function(e)
    { 
		$(this).parents('.infodivChld:first').remove();

		e.preventDefault();
		return false;
	});
	
	var supurl = "{{ url('purchase_order/supplier_data/') }}";
	$('#supplier_name').click(function(){
		$('.modal-body').load(supurl, function(result){
			$('#myModal').modal({show:true});
		});
	});
	
	$(document).on('click', '.supp', function(e) 
    {
		$('#supplier_name').val($(this).attr("data-name"));
		$('#supplier_id').val($(this).attr("data-id"));
		e.preventDefault();
	});
	/* var modurl = "{{ url('purchase_order/testdata/') }}";
	$('.form-control').click(function(){
		$('.modal-body').load(modurl, function(result){
			$('#myModal').modal({show:true});
		});
	}); */
	
	$(document).on('click', '.nam', function(e) 
    {
		//$('#voucher_no').val('clara');
		e.preventDefault();
	});
	
	$('#currency_id').on('change', function(e){
		var curr_id = e.target.value; 
		$.get("{{ url('currency/getrate/') }}/" + curr_id, function(data) {
			$('#currency_rate').val(data);
			//console.log(data);
		});
	});
	
		
	
	
});


</script>
@stop
