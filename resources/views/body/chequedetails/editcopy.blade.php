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
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <!--section starts-->
            <h1>
            cheque
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="">
                        <i class="fa fa-fw fa-building-o"></i> Maintenance
                    </a>
                </li>
                <li>
                    <a href="#">cheque</a>
                </li>
                <li class="active">
             Edit  cheque
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
                                <i class="fa fa-fw fa-crosshairs"></i> Edit  cheque
                            </h3>
                            <span class="pull-right">
                                    <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                                </span>
                        </div>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" method="POST" name="frmMachine" id="frmMachine" action="{{ url('cheque_details/update/'.$mrow->id) }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" name="cheque_id" id="cheque_id" value="{{ $mrow->id }}">

									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">cheque no</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="cheque_no" name="cheque_no" value="{{$mrow->cheque_no}}" autocomplete="off" >
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">Cheq.Date</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="cheque_date" name="cheque_date" value="{{date('d-m-Y',strtotime($mrow->cheque_date))}}" autocomplete="off" placeholder="cheque date">
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">Customer</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="customer_id" name="customer_id" value="{{$mrow->customer}}" autocomplete="off">
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">amount words</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="amount_words" name="amount_words" value="{{$mrow->amount_words}}" autocomplete="off" placeholder="Serial No.">
										</div>
									</div>
									

									
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">amount number</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="amount_number" name="amount_number" value="{{$mrow->amount_number}}" autocomplete="off" placeholder="Media">
										</div>
									</div>
									
									<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                         <a href="{{ url('cheque_details') }}" class="btn btn-danger">Cancel</a>
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

<script>
"use strict";

$(document).ready(function () {
	var urlcode = "{{ url('machine/checkregno/') }}";
    $('#frmMachine').bootstrapValidator({
        fields: {
			cheque_no: {
                validators: {
                    notEmpty: {
                        message: 'Machine name is required and cannot be empty!'
                    }
                }
            },
			amount_number: {
                validators: {
                    notEmpty: {
                        message: 'Amount  is required and cannot be empty!'
                    }
                }
            },
			type: {
                validators: {
                    notEmpty: {
                        message: 'Type is required and cannot be empty!'
                    }
                }
            }
			/* chasis_no: {
                validators: {
					remote: {
                        url: urlcode,
                        data: function(validator) {
                            return {
                                code: validator.getFieldElements('chasis_no').val()
                            };
                        },
                        message: 'The chasis no already exist!'
                    } 
                }
            }  */
          
        }
        
    }).on('reset', function (event) {
        $('#frmMachine').data('bootstrapValidator').resetForm();
    });
	
    var custurl = "{{ url('sales_order/customer_data/') }}";
	$('#customer_name').click(function() { 
		$('#customerData').load(custurl, function(result) {
			$('#myModal').modal({show:true});
		});
	});
	
	$(document).on('click', '.custRow', function(e) { //console.log($(this).attr("data-trnno"));
		$('#customer_name').val($(this).attr("data-name"));
		$('#customer_id').val($(this).attr("data-id"));
	});
});
</script>
@stop
