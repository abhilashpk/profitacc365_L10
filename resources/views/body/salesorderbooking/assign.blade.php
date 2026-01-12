@extends('layouts/default')

    {{-- Page title --}}
    @section('title')
         
        @parent
    @stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
	<link rel="stylesheet" href="{{asset('assets/vendors/datetime/css/jquery.datetimepicker.css')}}">
    <link href="{{asset('assets/vendors/airdatepicker/css/datepicker.min.css')}}" rel="stylesheet" type="text/css">
	
	<link href="{{asset('assets/vendors/bootstrap-multiselect/css/bootstrap-multiselect.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('assets/vendors/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('assets/vendors/select2/css/select2-bootstrap.css')}}" rel="stylesheet" type="text/css">
	
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/buttons.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/colReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/dataTables.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/rowReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/scroller.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatablesmark.js/css/datatables.mark.min.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/responsive_datatables.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/datepicker.css')}}">
	<style>
		input[type=number]::-webkit-inner-spin-button, 
		input[type=number]::-webkit-outer-spin-button { 
		  -webkit-appearance: none; 
		  margin: 0; 
		}
	</style>
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <!--section starts-->
            <h1>
                Sales Order 
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="fa fa-fw fa-briefcase"></i> Sales Order Job
                    </a>
                </li>
                <li>
                    <a href="#">Sales Order </a>
                </li>
                <li class="active">
                    Add
                </li>
            </ol>
        </section>
        <!--section ends-->
		
		<!--section ends-->
		@if(Session::has('message'))
		<div class="alert alert-success">
			<p>{{ Session::get('message') }}</p>
		</div>
		@endif
        
		@if(Session::has('error'))
		<div class="alert alert-danger">
			<p>{{ Session::get('error') }}</p>
		</div>
		@endif
		
		@if (count($errors) > 0)
			<div class="alert alert-danger">
				<ul>
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif
		
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-crosshairs"></i> Assign Driver  <span id="recCount"></span>
                            </h3>
                           
                        </div>
                        <div class="panel-body">
							<div class="controls"> 
                            <form class="form-horizontal" role="form" method="POST" name="frmAsgnDriver" id="frmAsgnDriver" action="{{ url('sales_order_booking/driver_assign') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
																
								<div class="form-group">
									<label for="input-text" class="col-sm-2 control-label">Driver</label>
									<div class="col-sm-7">
										<select id="driver_id" class="form-control select2" style="width:100%" name="driver_id" required>
											<option value="">Select Driver...</option>
											@foreach($driver as $drv)
											<option value="{{$drv->id}}">{{$drv->driver_name}}</option>
											@endforeach
										</select>
									</div>
								</div>
								
								<div class="form-group">
									<label for="input-text" class="col-sm-2 control-label">Assign Date</label>
									<div class="col-sm-7">
										<input type="text" class="form-control" data-language='en' id="assign_date" value="{{date('d-m-Y')}}" name="assign_date" placeholder="Assign Date" required>
									</div>
								</div>
								
								<hr/>
								
								<table id="OrderList" class="table table-bordred table-striped">
									<thead>
									<tr>
										<th><input type="checkbox" name="checkall" id="checkall" class="clschkall" onClick="check_uncheck_checkbox(this.checked);"/></th>
										<th>SO. No</th>
										<th>SO. Date</th>
										<th>Customer</th>
										<th>Delivery Details</th>
									</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
								
								 <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                           <a href="{{ url('order') }}" class="btn btn-danger">Cancel</a>
                                    </div>
                                </div>
							
								
                            </form>
							</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
		
		
@stop

{{-- page level scripts --}}
@section('footer_scripts')
<script type="text/javascript" src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/iCheck/js/icheck.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-fileinput/js/fileinput.min.js')}}"></script>

<script src="{{asset('assets/vendors/mark.js/jquery.mark.js')}}" charset="UTF-8"></script>
<script src="{{asset('assets/vendors/datatablesmark.js/js/datatables.mark.min.js')}}" charset="UTF-8"></script>
<script src="{{asset('assets/js/custom_js/responsive_datatables.js')}}" type="text/javascript"></script>

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
<!-- end of page level js -->

<script src="{{asset('assets/vendors/datetime/js/jquery.datetimepicker.full.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.en.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/custom_js/advanceddate_pickers.js')}}"></script>
<script>
"use strict"; 
$('#assign_date').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy' } );

$(document).ready(function () {
	
    $('#frmAsgnDriver').bootstrapValidator({
        fields: {
           
			driver_id: {
                validators: {
                    notEmpty: {
                        message: 'Driver name is required and cannot be empty!'
                    }
                }
            },
			assign_date: {
                validators: {
                    notEmpty: {
                        message: 'Assign date is required and cannot be empty!'
                    }
                }
            },
			'ordid[]': {
                validators: {
                    minlength: {
                        message: 'Order no is required and cannot be empty!'
                    }
                }
            }
			
        }
        
    }).on('reset', function (event) {
        $('#frmAsgnDriver').data('bootstrapValidator').resetForm();
    });
});

$(function() {
	var dtInstance = $("#OrderList").DataTable({
		"processing": true,
		"serverSide": true,
		"searching": true,
		"ajax":{
				 "url": "{{ url('sales_order_booking/paging_ordlist/') }}",
				 "dataType": "json",
				 "type": "POST",
				 "data":{ _token: "{{csrf_token()}}"}
			   },
		"columns": [
			{ "data": "opt" },
			{ "data": "voucher_no" },
			{ "data": "voucher_date" },
			{ "data": "customer" },
			{ "data": "delivery" }
		]	
	});
	
	$(document).on('click', '.clschk,.clschkall', function(e) {  
		var cnt = $('.clschk:checkbox:checked').length;
		if(cnt > 0)
			$('#recCount').html( ' - '+ cnt +' selected' );
		else
			$('#recCount').html('');
	});
});

function check_uncheck_checkbox(isChecked) {
	if(isChecked) {
		$('input:checkbox').each(function() { 
			this.checked = true; 
		});
	} else { 
		$('input:checkbox').each(function() {
			this.checked = false;
		});
	}
    if(('input[type=checkbox]:checked').length == 0)
    {
        alert('Please select atleast one checkbox');
    }
} 
</script>
@stop
