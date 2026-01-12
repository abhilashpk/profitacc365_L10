@extends('layouts/default')

{{-- Page title --}}
@section('title')

    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <link href="{{ asset('assets/vendors/bootstrap3-wysihtml5-bower/css/bootstrap3-wysihtml5.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link rel="stylesheet" media="screen" type="text/css" href="{{ asset('assets/vendors/summernote/summernote.css') }}">
    <link href="{{ asset('assets/vendors/trumbowyg/css/trumbowyg.min.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/custom.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/form_editors.css') }}">

    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/iCheck/css/all.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/bootstrap-fileinput/css/fileinput.min.css') }}"
        media="all" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/formelements.css') }}">
    <!--end of page level css-->
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <!--section starts-->
        <h1>
            Driver
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="">
                    <i class="fa fa-fw fa-wrench"></i> Rental Entry
                </a>
            </li>
            <li>
                <a href="#">Driver</a>
            </li>
            <li class="active">
                Add New
            </li>
        </ol>
    </section>
    @if (Session::has('message'))
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
                            <i class="fa fa-fw fa-crosshairs"></i> New Driver
                        </h3>

                    </div>
                    <div class="panel-body">
                        <form class="form-horizontal" role="form" method="POST" name="frmRentalVehicle"
                            id="frmRentalVehicle" action="{{ url('rental_driver/save') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                           <div class="form-group">
                                <label for="input-text" class="col-sm-2 control-label">Driver Code</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" autocomplete="off" id="code" name="code"
                                        placeholder="Driver Code" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="input-text" class="col-sm-2 control-label">Driver Name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" autocomplete="off" id="dname" name="dname"
                                        placeholder="Driver Name">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="input-text" class="col-sm-2 control-label">Mobile No:1</label>
                                <div class="col-sm-10">
                                    <input type="tel" class="form-control" autocomplete="off" id="mb1" name="mb1"
                                        placeholder="Enter Mobile No:">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="input-text" class="col-sm-2 control-label">Mobile No:2</label>
                                <div class="col-sm-10">
                                    <input type="tel" class="form-control" autocomplete="off" id="mb2" name="mb2"
                                        placeholder="Enter Mobile No:">
                                </div>
                            </div>
                            <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Driver Type</label>
                                    <div class="col-sm-10">
                                     <select id="driver_type" class="form-control select" required style="width:100%" name="driver_type">
									<option value="" selected>Select </option>
									<option value="supplier">SUPPLIER</option>
									<option value="customer">CUSTOMER</option>
								</select>
                                     </div>
                            </div>
							
							<div class="form-group" id="supTxt">
                                <label for="input-text" class="col-sm-2 control-label">Supplier</label>
                                <div class="col-sm-10">
                                    <input type="text" autocomplete="off" readonly class="form-control" id="supplier_name" name="supplier_name" placeholder="Supplier" data-toggle="modal" data-target="#supplier_modal">
									<input type="hidden" name="supplier_id" id="supplier_id">
                                </div>
                            </div>
							
							<div class="form-group" id="custTxt">
                                <label for="input-text" class="col-sm-2 control-label">Customer</label>
                                <div class="col-sm-10">
                                   <input type="text" name="customer_name" id="customer_name" class="form-control" autocomplete="off" data-toggle="modal" data-target="#customer_modal" placeholder="Customer" readonly>
								   <input type="hidden" name="customer_id" id="customer_id">
								</div>
                            </div>

                            <div class="form-group">
                                <label for="input-text" class="col-sm-2 control-label"></label>
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                    <a href="{{ url('rental_driver') }}" class="btn btn-danger">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
	
	 <div id="supplier_modal" class="modal fade animated" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Select Supplier</h4>
				</div>
				<div class="modal-body" id="supplierData">
					
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
							
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <script src="{{ asset('assets/vendors/bootstrap3-wysihtml5-bower/js/bootstrap3-wysihtml5.all.min.js') }}"
        type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/trumbowyg/js/trumbowyg.js') }}" type="text/javascript"></script>
    <script type="text/javascript"
        src="{{ asset('assets/vendors/bootstrap3-wysihtml5-bower/js/bootstrap3-wysihtml5.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/summernote/summernote.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom_js/form_editors.js') }}" type="text/javascript"></script>
    <!-- begining of page level js -->
    <script type="text/javascript" src="{{ asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js') }}">
    </script>
    <script type="text/javascript" src="{{ asset('assets/vendors/iCheck/js/icheck.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/bootstrap-fileinput/js/fileinput.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/custom_js/form_elements.js') }}"></script>
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

        $(document).ready(function() {
			
			$('#custTxt').hide();
			$('#supTxt').hide();
        var url = "{{ url('rental_driver/checknumber/') }}";
        var urlmb1 = "{{ url('rental_driver/checkmobnumber1/') }}";
        var urlmb2 = "{{ url('rental_driver/checkmobnumber2/') }}";
            $('#frmRentalVehicle').bootstrapValidator({
                fields: {
                    dname: {
                        validators: {
                            notEmpty: {
                                message: 'The driver name is required and cannot be empty!'
                            }
                        }
                    },
                    mb1: {
                        validators: {
                            notEmpty: {
                                message: 'The mobile number 1 is required and cannot be empty!'
                            },
                        remote: {
                                url: urlmb1,
                                data: function(validator) {
                                    return {
                                        vnumber: validator.getFieldElements('mb1').val()
                                    };
                                },
                                message: 'The Mobile number 1 is not available'
                        }    
                        }
                    },

                    mb2: {
                        validators: {
                           
                        remote: {
                                url: urlmb2,
                                data: function(validator) {
                                    return {
                                        vnumber: validator.getFieldElements('mb2').val()
                                    };
                                },
                                message: 'The Mobile number 2 is not available'
                        }    
                        }
                    },
                    
                }

            }).on('reset', function(event) {
                $('#frmRentalVehicle').data('bootstrapValidator').resetForm();
            });
        });

$(function() {		
	
	$(document).on('change', '#driver_type', function(e) { 
		if(this.value=='supplier') {
			$('#supTxt').show();
			$('#custTxt').hide();
		} else if(this.value=='customer'){
			$('#supTxt').hide();
			$('#custTxt').show();
		} else {
			$('#supTxt').hide();
			$('#custTxt').hide();
		}
	});
	
	var supurl = "{{ url('purchase_order/supplier_data/') }}";
	$('#supplier_name').click(function() {
		$('#supplierData').load(supurl, function(result) {
			$('#myModal').modal({show:true}); $('.input-sm').focus();
		});
	});
	$(document).on('click', '.supp', function(e) {
		$('#supplier_name').val($(this).attr("data-name"));
		$('#supplier_id').val($(this).attr("data-id"));
		e.preventDefault();
	});
	
	var custurl = "{{ url('sales_order/customer_data/') }}";
	$('#customer_name').click(function() { 
		$('#customerData').load(custurl, function(result) {
			$('#myModal').modal({show:true});$('.input-sm').focus()
		});
	});
	$(document).on('click', '.custRow', function(e) {
		$('#customer_name').val($(this).attr("data-name"));
		$('#customer_id').val($(this).attr("data-id"));
		e.preventDefault();
	});
});	
    </script>
@stop
