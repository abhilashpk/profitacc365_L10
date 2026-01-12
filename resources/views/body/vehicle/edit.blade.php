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
                Vehicle
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="">
                        <i class="fa fa-fw fa-building-o"></i> Inventory
                    </a>
                </li>
                <li>
                    <a href="#">Vehicle</a>
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
                                <i class="fa fa-fw fa-crosshairs"></i> Edit Vehicle 
                            </h3>
                            <span class="pull-right">
                                    <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                                </span>
                        </div>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" method="POST" name="frmVehicle" id="frmVehicle" action="{{ url('vehicle/update/'.$vehrow->id) }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" name="veh_id" value="<?php echo $vehrow->id; ?>">
								
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">Customer</label>
										<div class="col-sm-10">
											<input type="text" name="customer_name" id="customer_name" class="form-control" autocomplete="off" data-toggle="modal" data-target="#customer_modal" value="<?php echo $vehrow->customer; ?>">
											<input type="hidden" name="customer_id" id="customer_id" value="<?php echo $vehrow->customer_id; ?>">
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">Reg. No.</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="reg_no" name="reg_no" autocomplete="off" value="{{$vehrow->reg_no}}">
										</div>
									</div>
									
									
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">Issue Plate</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="issue_plate" name="issue_plate" autocomplete="off" value="{{$vehrow->issue_plate}}">
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">Code Plate</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="code_plate" name="code_plate" autocomplete="off" value="{{$vehrow->code_plate}}">
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">Color Code</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="color_code" name="color_code" autocomplete="off" value="{{$vehrow->color_code}}">
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">Vehicle Name</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="vehicle_name" name="vehicle_name" autocomplete="off" value="{{$vehrow->name}}">
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">Make</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="make" name="make" autocomplete="off" value="{{$vehrow->make}}">
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">Model</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="model" name="model" autocomplete="off" value="{{$vehrow->model}}">
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">Type</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="plate_type" name="plate_type" autocomplete="off" value="{{$vehrow->plate_type}}">
										</div>
									</div>
									
									<!--<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">Color</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="color" name="color" autocomplete="off" value="{{$vehrow->color}}">
										</div>
									</div>-->
									<input type="hidden" name="color">
									
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">Engine No.</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="engine_no" name="engine_no" autocomplete="off" value="{{$vehrow->engine_no}}">
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">Chasis No.</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="chasis_no" name="chasis_no" autocomplete="off" value="{{$vehrow->chasis_no}}">
										</div>
									</div>
									
									<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                         <a href="{{ url('vehicle') }}" class="btn btn-danger">Cancel</a>
                                    </div>
                                </div>
                                                            
                                
                            </form>
							
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
        <!-- end of page level js -->
        <!-- end of page level js -->

<script>
"use strict";

$(document).ready(function () {
	var urlcode = "{{ url('vehicle/checkregno/') }}";
    $('#frmVehicle').bootstrapValidator({
        fields: {
            reg_no: {
                validators: {
                    notEmpty: {
                        message: 'The reg. no required and cannot be empty!'
                    }/* ,
					remote: {
                        url: urlcode,
                        data: function(validator) {
                            return {
                                code: validator.getFieldElements('reg_no').val(),
								id: validator.getFieldElements('veh_id').val()
                            };
                        },
                        message: 'The reg. no already exist!'
                    } */
                }
            },
			vehicle_name: {
                validators: {
                    notEmpty: {
                        message: 'The vehicle name is required and cannot be empty!'
                    }
                }
            },
			chasis_no: {
                validators: {
                     notEmpty: {
                        message: 'The Chasis No: is required and cannot be empty!'
                    },
					remote: {
                        url: urlcode,
                        data: function(validator) {
                            return {
                                code: validator.getFieldElements('chasis_no').val(),
								id: validator.getFieldElements('veh_id').val()
                            };
                        },
                        message: 'The chasis no already exist!'
                    } 
                }
            } 
          
        }
        
    }).on('reset', function (event) {
        $('#frmVehicle').data('bootstrapValidator').resetForm();
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
