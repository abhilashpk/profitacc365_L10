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
                   Termination/Resignation
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
                                <i class="fa fa-fw fa-crosshairs"></i> Termination/Resignation
                            </h3>
                           
                        </div>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" method="POST" name="frmResign" id="frmResign" action="{{ url('employee/save_resign') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" name="id" value="{{$id}}">
								<input type="hidden" name="salary" id="salary" value="{{$masterrow->net_salary}}">
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
                                    <label for="input-text" class="col-sm-3 control-label">Basic Salary</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="basic" name="basic" readonly value="{{ $masterrow->basic_pay }}">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Day per Month for AL</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="lev_per_mth" id="lev_per_mth" readonly class="form-control" value="{{ $masterrow->lev_per_mth }}">
									</div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Air Ticket Allotment Period</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="air_tkt" readonly class="form-control" value="{{ $masterrow->air_trk }}">
									</div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Joining Date</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" readonly id="join_date" name="join_date" value="{{($masterrow->join_date=='0000-00-00')?'':date('d-m-Y', strtotime($masterrow->join_date))}}">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Last Rejoining Date</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" readonly id="rejoin_date" name="rejoin_date" value="{{($masterrow->rejoin_date=='0000-00-00')?'':date('d-m-Y', strtotime($masterrow->rejoin_date))}}">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Date </label>
                                    <div class="col-sm-9">
										<input type="text" class="form-control pull-right" name="start_date" data-language='en' readonly id="start_date" placeholder="Date"/>
                                    </div>
									
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Air Ticket Amount</label>
                                    <div class="col-sm-9">
                                        <input type="number" class="form-control" step="any" id="airtkt_amount" name="airtkt_amount">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Type</label>
                                    <div class="col-sm-6">
                                        <select id="type" class="form-control select2" style="width:100%" name="type">
											<option value="1">Resignation</option>
											<option value="2">Termination</option>
										</select>
                                    </div>
									<div class="col-sm-3"><button type="button" class="btn btn-primary apply">Apply</button></div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Calculated Months Worked</label>
                                    <div class="col-sm-9">
                                        <input type="number" class="form-control" step="any" id="months_worked" readonly name="months_worked">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Calculated Leave Days</label>
                                    <div class="col-sm-9">
                                        <input type="number" class="form-control" step="any" id="cal_leave_days" readonly name="cal_leave_days">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Calculated Leave Salary</label>
                                    <div class="col-sm-9">
                                        <input type="number" class="form-control" step="any" id="cal_leave_salary" readonly name="cal_leave_salary">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Calculated Years Worked</label>
                                    <div class="col-sm-9">
                                        <input type="number" class="form-control" step="any" id="years_worked" readonly name="years_worked">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Gratuity</label>
                                    <div class="col-sm-9">
                                        <input type="number" class="form-control" step="any" id="gratuity" name="gratuity">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Leave Advance</label>
                                    <div class="col-sm-9">
                                        <input type="number" class="form-control" step="any" id="leave_advance" name="leave_advance">
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
    $('#frmResign').bootstrapValidator({
        fields: {
			start_date: { validators: { notEmpty: { message: 'Leave start date is required and cannot be empty!' } }},
			alo_leave_days: { validators: { notEmpty: { message: 'Alloted leave days is required and cannot be empty!' } }},
			cal_leave_days: { validators: { notEmpty: { message: 'Calculated leave days is required and cannot be empty!' } }}
          
        }
        
    }).on('reset', function (event) {
        $('#frmResign').data('bootstrapValidator').resetForm();
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
			alert('Please enter date!');
		} else {
			var d1 = moment($("#join_date").val(), "dd-MM-YYYY");
			var d2 = moment($('#start_date').val(), "dd-MM-YYYY");
			var d3 = moment($("#rejoin_date").val(), "dd-MM-YYYY");
			var mth = parseFloat( moment.duration(d2.diff(d1)).asMonths()).toFixed();
			
			/* var d1 = new Date($( "#join_date" ).val());
			var d2 = new Date($( "#start_date").val());
			var mth = parseFloat(monthDiff(d1, d2)); */
			
			//var d3 = new Date($( "#rejoin_date" ).val());
			var lvmth = parseFloat( moment.duration(d2.diff(d3)).asMonths()).toFixed(); //parseFloat(monthDiff(d3, d2));
			
			var lev_days = lvmth * $('#lev_per_mth').val();
			var sal = $('#basic').val() / 30;
			var lev_sal = lev_days * sal;
			var yrs = (mth / 12).toFixed(2);
			var grt;
			var salary = parseFloat($('#salary').val()) / 30;
			
			if(yrs < 5) {
				grt = 21 * sal * yrs;
			} else {
				grt = 30 * sal * yrs;
			}
			
			$('#months_worked').val(mth);
			$('#cal_leave_days').val(lev_days);
			$('#cal_leave_salary').val(lev_sal.toFixed(2));
			$('#years_worked').val(yrs);
			$('#gratuity').val(grt.toFixed(2));
			
			$('#frmResign').bootstrapValidator('revalidateField', 'start_date');
			$('#frmResign').bootstrapValidator('revalidateField', 'cal_leave_days');
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
