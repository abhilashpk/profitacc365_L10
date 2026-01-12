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
                Machine
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="">
                       <i class="fa fa-fw fa-building-o"></i> Maintenance
                    </a>
                </li>
                <li>
                    <a href="#">Machine</a>
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
                                <i class="fa fa-fw fa-crosshairs"></i> New Machine 
                            </h3>
                           
                        </div>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" method="POST" name="frmMachine" id="frmMachine" action="{{url('machine/save')}}">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">Machine Name</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="name" name="name" autocomplete="off" placeholder="Machine Name">
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">Model No</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="model" name="model" autocomplete="off" placeholder="Model No">
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">Brand</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="brand" name="brand" autocomplete="off" placeholder="Brand">
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">Serial No.</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="serialno" name="serialno" autocomplete="off" placeholder="Serial No.">
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">Type</label>
										<div class="col-sm-10">
											<select name="type" id="type" class="form-control">
												<option value="">--Select--</option>
												<option value="Color">Color</option>
												<option value="B/W">B/W</option>
											</select>
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">Media</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="media" name="media" autocomplete="off" placeholder="Media">
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label"></label>
										<div class="col-sm-10">
											<button type="submit" class="btn btn-primary">Submit</button>
											 <a href="{{ url('machine') }}" class="btn btn-danger">Cancel</a>
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
			name: {
                validators: {
                    notEmpty: {
                        message: 'Machine name is required and cannot be empty!'
                    }
                }
            },
			model: {
                validators: {
                    notEmpty: {
                        message: 'Model no is required and cannot be empty!'
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
