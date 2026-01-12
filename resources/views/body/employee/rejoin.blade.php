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
	<link rel="stylesheet" href="{{asset('assets/vendors/datetime/css/jquery.datetimepicker.css')}}">
    <link href="{{asset('assets/vendors/airdatepicker/css/datepicker.min.css')}}" rel="stylesheet" type="text/css">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/buttons.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/colReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/dataTables.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/rowReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/scroller.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatablesmark.js/css/datatables.mark.min.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/responsive_datatables.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/datepicker.css')}}">
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <!--section starts-->
            <h1>
                Employee
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="glyphicon glyphicon-tower"></i>  HR Management
                    </a>
                </li>
                <li>
                    <a href="#">Employee</a>
                </li>
                <li class="active">
                   Rejoin
                </li>
            </ol>
        </section>
		@if (count($errors) > 0)
			<div class="alert alert-danger">
				<ul>
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif
		
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
                                <i class="fa fa-fw fa-crosshairs"></i> Rejoin
                            </h3>
                           
                        </div>
                        <div class="panel-body">
						<?php if($masterrow) { ?>
                            <form class="form-horizontal" role="form" method="POST" name="frmRejoin" id="frmRejoin" action="{{ url('employee/save_rejoin') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" name="id" value="{{$masterrow->id}}">
								<input type="hidden" name="employee_id" value="{{$id}}">
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Employee Code</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="code" readonly value="{{ $masterrow->code }}">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Employee Name</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="jobname" readonly value="{{ $masterrow->name }}">
                                    </div>
                                </div>
                                
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Leave Start Date</label>
                                    <div class="col-sm-9">
										<input type="text" class="form-control" name="lev_start_date" value="{{date('d-m-Y',strtotime($masterrow->start_date))}}"/>
                                    </div>
									
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Rejoin Date</label>
                                    <div class="col-sm-9">
										<input type="text" class="form-control pull-right" name="start_date" data-language='en' readonly id="start_date" placeholder="Rejoin Date"/>
                                    </div>
									
                                </div>
								
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label"></label>
                                    <div class="col-sm-9">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                        <a href="{{ url('employee/view/'.$id) }}" class="btn btn-danger">Cancel</a>
                                    </div>
                                </div>
								
                            </form>
						<?php } else { ?>
						<div class="alert alert-warning">
							<p>This employee has not any active leaves!</p>
						</div>
						<a href="{{ url('employee/view/'.$id) }}" class="btn btn-danger">Back</a>
						<?php } ?>
						
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
<script>
"use strict";

$(document).ready(function () {
	
	$('input[type=number]').on('mousewheel',function(e){ $(this).blur(); });
    $('#frmRejoin').bootstrapValidator({
        fields: {
			start_date: { validators: { notEmpty: { message: 'Leave start date is required and cannot be empty!' } }},
			alo_leave_days: { validators: { notEmpty: { message: 'Alloted leave days is required and cannot be empty!' } }},
			cal_leave_days: { validators: { notEmpty: { message: 'Calculated leave days is required and cannot be empty!' } }}
          
        }
        
    }).on('reset', function (event) {
        $('#frmRejoin').data('bootstrapValidator').resetForm();
    });
	
});

function monthDiff(d1, d2) {
    var months;
    months = (d2.getFullYear() - d1.getFullYear()) * 12;
    months -= d1.getMonth();
    months += d2.getMonth();
    return months <= 0 ? 0 : months;
}

$(function() {	
	
	$(document).on('click', '.apply', function(e) {
		
		if($('#start_date').val()=='') {
			alert('Please enter leave start date!');
		} else if($('#alo_leave_days').val()=='') {
			alert('Please enter alloted leave days!');
			$('#alo_leave_days').focus();
		} else {
			var d1 = new Date($( "#rejoin_date" ).val());
			var d2 = new Date($( "#start_date").val());
			var mth = parseFloat(monthDiff(d1, d2));
			var lev_days = mth * $('#lev_per_mth').val();
			var sal = $('#basic').val() / 30;
			var lev_sal = lev_days * sal;
			
			$('#months_worked').val(mth);
			$('#cal_leave_days').val(lev_days);
			$('#cal_leave_salary').val(lev_sal);
			
			$('#frmRejoin').bootstrapValidator('revalidateField', 'start_date');
			$('#frmRejoin').bootstrapValidator('revalidateField', 'cal_leave_days');
			//console.log(mth);
		}
		
		e.preventDefault();
	});
	
	$('#start_date').datepicker({
		language: 'en',
		dateFormat: 'dd-mm-yyyy'
	});
	
});
</script>
@stop
