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
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/buttons.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/colReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/dataTables.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/rowReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/scroller.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatablesmark.js/css/datatables.mark.min.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/responsive_datatables.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/datepicker.css')}}">
        <!--end of page level css-->
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <!--section starts-->
            <h1>
                Wage Entry
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="fa fa-fw fa-money"></i> Payroll
                    </a>
                </li>
                <li>
                    <a href="#">Wage Entry</a>
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
                                <i class="fa fa-fw fa-crosshairs"></i> Edit
                            </h3>
                           
                        </div>
                        <div class="panel-body">
							<div class="controls"> 
                            <form class="form-horizontal" role="form" method="POST" name="frmWageEntry" id="frmWageEntry" action="{{ url('wage_entry/update') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Wage Entry Type</label>
                                    <div class="col-sm-10">
                                        <select id="entry_type" class="form-control select2" style="width:100%" name="entry_type">
											<option value="monthly">Monthly</option>
											<option value="30days">30 Days</option>
                                        </select>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Month</label>
                                    <div class="col-sm-10">
                                        <select id="month" class="form-control select2" style="width:100%" name="month">
											<option value="JAN">JAN</option>
											<option value="FEB">FEB</option>
											<option value="MAR">MAR</option>
											<option value="APR">APR</option>
											<option value="MAY">MAY</option>
											<option value="JUN">JUN</option>
											<option value="JUL">JUL</option>
											<option value="AUG">AUG</option>
											<option value="SEP">SEP</option>
											<option value="OCT">OCT</option>
											<option value="NOV">NOV</option>
											<option value="DEC">DEC</option>
                                        </select>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Payment Date</label>
                                    <div class="col-sm-10">
										<input type="text" class="form-control pull-right" name="payment_date" data-language='en' readonly id="payment_date" placeholder="{{date('d-m-Y')}}"/>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Employee Name</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="employee_name" id="employee_name" value="{{$employee->name}}" class="form-control" autocomplete="off" data-toggle="modal" data-target="#employee_modal" placeholder="Employee Name">
										<input type="hidden" name="employee_id" id="employee_id">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Employee No.</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="employee_no" autocomplete="off" value="{{$employee->code}}" name="employee_no" placeholder="Employee No.">
                                    </div>
                                </div>
								
								<div class="form-group">
									<label for="input-text" class="col-sm-2 control-label">Desigantion</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" id="designation" value="{{$employee->designation}}" autocomplete="off" name="designation" readonly>
									</div>
								</div>

								<div class="form-group">
									<label for="input-text" class="col-sm-2 control-label">Department</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" id="department" value="" autocomplete="off" name="department" readonly>
									</div>
								</div>

								<div class="form-group">
									<label for="input-text" class="col-sm-2 control-label">Salary</label>
									<div class="col-sm-10">
										<input type="text" name="salary" id="salary" class="form-control" value="{{$employee->basic_pay}}" autocomplete="off" readonly>
									</div>
								</div>

								<div class="form-group">
									<label for="input-text" class="col-sm-2 control-label">NWH/Day</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" id="empnwh" value="{{$parameter->nwh}}" autocomplete="off" name="empnwh" >
										<input type="hidden" id="paydays" value="{{$parameter->payroll_by}}" name="paydays" >
									</div>
								</div>

								<div class="form-group">
									<label for="input-text" class="col-sm-2 control-label">OT General</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" id="ot_general" value="{{$parameter->ot_general}}" autocomplete="off" name="ot_general">
									</div>
								</div>

								<div class="form-group">
									<label for="input-text" class="col-sm-2 control-label">OT Holiday</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" id="ot_holiday" value="{{$parameter->ot_holiday}}" autocomplete="off" name="ot_holiday" >
									</div>
								</div>

								<div class="form-group">
									<label for="input-text" class="col-sm-2 control-label">HRA</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" id="emphra" value="{{$employee->hra}}" autocomplete="off" name="emphra">
									</div>
								</div>

								<div class="form-group">
									<label for="input-text" class="col-sm-2 control-label">Transport</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" value="{{$employee->transport}}" id="emptransport" autocomplete="off" name="emptransport">
									</div>
								</div>

								<div class="form-group">
									<label for="input-text" class="col-sm-2 control-label">Allowance1</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" id="empallowance" value="{{$employee->allowance}}" autocomplete="off" name="empallowance">
									</div>
								</div>

								<div class="form-group">
									<label for="input-text" class="col-sm-2 control-label">Allowance2</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" id="empallowance2" value="{{$employee->allowance2}}" autocomplete="off" name="empallowance2">
									</div>
								</div>

								<div class="form-group">
									<label for="input-text" class="col-sm-2 control-label">Absent Hrs.</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" id="absent_hrs" autocomplete="off" name="absent_hrs" readonly>
									</div>
								</div>

								<div class="form-group">
									<label for="input-text" class="col-sm-2 control-label">Sick Leave</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" id="sick_leave" autocomplete="off" name="sick_leave" placeholder="Sick Leave">
									</div>
								</div>

								<div class="form-group">
									<label for="input-text" class="col-sm-2 control-label">Paid Leave</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" id="paid_leave" autocomplete="off" name="paid_leave" placeholder="Paid Leave">
									</div>
								</div>
								
								<fieldset>
								<legend><h5>Job Details</h5></legend>
								
								<div class="itemdivPrnt">
									<div class="itemdivChld">
										<table border="0" class="table-dy-row">
											<tr>
												<td width="15%">
													<span class="small">Job Code</span>
													<input type="hidden" name="job_id[]" id="jobid_1">
													<input type="text" id="jobcod_1" name="job_code[]" class="form-control" autocomplete="off" value="SALARY" autocomplete="off" data-toggle="modal" data-target="#job_modal" placeholder="Job Code">
												</td>
												<td width="10%">
													<span class="small">Wage/Hr.</span><input type="number" value="{{$parameter->nwh}}" name="wage[]"  id="wage_1" step="any" autocomplete="off" class="form-control" readonly>
												</td>
												<td width="10%">
													<span class="small">No.of Days</span><input type="number" id="nodays_1" step="any" value="{{$parameter->payroll_by}}" name="nodays[]" autocomplete="off" class="form-control line-nod" >
												</td>
												<td width="10%">
													<span class="small">NWH</span> <input type="number" id="nwh_1" step="any" name="nwh[]" autocomplete="off" class="form-control line-nwh" >
												</td>
												<td width="10%">
													<span class="small">OTHr(G)</span> <input type="number" id="otg_1" step="any" name="otg[]" autocomplete="off" class="form-control line-otg" >
												</td>
												<td width="10%">
													<span class="small">OTHr(H)</span><input type="number" id="oth_1" step="any" name="oth[]" autocomplete="off" class="form-control line-oth" >
												</td>
												<td width="10%">
													<span class="small">Allowance</span><input type="number" id="alw_1" step="any" name="alw[]" autocomplete="off" class="form-control line-alwnc" readonly>
												</td>
												<td width="10%">
													<span class="small">Total Wage</span> 
													<input type="number" id="itmttl_1" step="any" name="line_total[]" class="form-control line-total" readonly>
												</td>
												<td width="1%">
													<br/><button type="button" class="btn btn-success btn-add-item" >
															<span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span>
														 </button>
												</td>
											</tr>
										</table>
											
									</div>
								</div>
								</fieldset>
								<hr/>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Basic</label>
                                    <div class="col-xs-10">
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-4">
										<input type="number" id="basic_net" step="any" value="{{$employee->net_basic}}" name="basic_net" class="form-control" readonly placeholder="0">
										</div>
									</div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">HRA</label>
                                    <div class="col-xs-10">
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-4">
										<input type="number" id="hra_net" step="any" name="hra_net" value="{{$employee->net_hra}}" class="form-control" readonly placeholder="0">
										</div>
									</div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Transport + Allowance</label>
                                    <div class="col-xs-10">
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-4">
										<input type="number" id="allowance_net" step="any" name="allowance_net" class="form-control" value="{{$employee->net_allowance}}" readonly placeholder="0">
										</div>
									</div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">OT(General)</label>
                                    <div class="col-xs-10">
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-4">
										<input type="number" id="otg_net" step="any" name="otg_net" value="{{$employee->net_otg}}" class="form-control" readonly placeholder="0">
										</div>
									</div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">OT(Holiday)</label>
                                    <div class="col-xs-10">
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-4">
										<input type="number" id="oth_net" step="any" name="oth_net" value="{{$employee->net_oth}}" class="form-control" readonly placeholder="0">
										</div>
									</div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Net Total</label>
                                    <div class="col-xs-10">
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-4">
										<input type="number" id="net_total" step="any" name="net_total" value="{{$employee->net_total}}" class="form-control" readonly placeholder="0">
										</div>
									</div>
                                </div>
								
								<br/>
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                         <a href="{{ url('employee') }}" class="btn btn-danger">Cancel</a>
                                    </div>
                                </div>
                                
                            </form>
							</div>
                        </div>
						
						<div id="employee_modal" class="modal fade animated" role="dialog">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal">&times;</button>
										<h4 class="modal-title">Select Employee</h4>
									</div>
									<div class="modal-body" id="employeeData">
													
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
									</div>
								</div>
							</div>
						</div>
						
						<div id="job_modal" class="modal fade animated" role="dialog">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal">&times;</button>
										<h4 class="modal-title">Select Job</h4>
									</div>
									<div class="modal-body" id="job_data">
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
<!--<script src="{{asset('assets/js/custom_js/advanceddate_pickers.js')}}"></script>-->


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

$(document).ready(function () {
	var urlcode = "{{ url('employee/checkcode/') }}";
	var urldesc = "{{ url('employee/checkdesc/') }}";
    $('#frmWageEntry').bootstrapValidator({
        fields: {
			code: { validators: { 
					notEmpty: { message: 'The employee code is required and cannot be empty!' },
					remote: { url: urlcode,
							  data: function(validator) {
								return { code: validator.getFieldElements('code').val() };
							  },
							  message: 'The employee code is not available'
                    }
                }
            },
			name: { validators: { notEmpty: { message: 'The employee name is required and cannot be empty!' } } },
			designation: { validators: { notEmpty: { message: 'The designation is required and cannot be empty!' } } },
			nationality: { validators: { notEmpty: { message: 'The nationality is required and cannot be empty!' } } },
			dob: { validators: { notEmpty: { message: 'The date of birth is required and cannot be empty!' } } },
			gender: { validators: { notEmpty: { message: 'The gender is required and cannot be empty!' } } },
			address1: { validators: { notEmpty: { message: 'The address1 is required and cannot be empty!' } } },
			address2: { validators: { notEmpty: { message: 'The address2 is required and cannot be empty!' } } },
			email: { validators: { 
					notEmpty: { message: 'The email is required and cannot be empty!' },
					remote: { url: urlcode,
							  data: function(validator) {
								return { email: validator.getFieldElements('email').val() };
							  },
							  message: 'The email is not available'
                    }
                }
            },
			phone: { validators: { notEmpty: { message: 'The phone is required and cannot be empty!' } } },
			photo: { validators: {
						file: {
							  extension: 'jpg,jpeg,png,gif',
							  type: 'image/jpg,image/jpeg,image/png,image/gif',
							  maxSize: 5*1024*1024,   // 5 MB
							  message: 'The selected file is not valid, it should be (jpg,jpeg,png,gif) and 5 MB at maximum.'
						}
					}
			},
			pp_id: { validators: { 
					notEmpty: { message: 'The passport id is required and cannot be empty!' },
					remote: { url: urlcode,
							  data: function(validator) {
								return { pp_id: validator.getFieldElements('pp_id').val() };
							  },
							  message: 'The passport id is not available'
                    }
                }
            },
			pp_issue_date: { validators: { notEmpty: { message: 'The passport issue date is required and cannot be empty!' } } },
			pp_expiry_date: { validators: { notEmpty: { message: 'The passport expiry date is required and cannot be empty!' } } },
			v_id: { validators: { 
					notEmpty: { message: 'The visa id is required and cannot be empty!' },
					remote: { url: urlcode,
							  data: function(validator) {
								return { v_id: validator.getFieldElements('v_id').val() };
							  },
							  message: 'The visa id is not available'
                    }
                }
            },
			v_issue_date: { validators: { notEmpty: { message: 'The passport issue date is required and cannot be empty!' } } },
			v_expiry_date: { validators: { notEmpty: { message: 'The passport expiry date is required and cannot be empty!' } } },
			image: { validators: {
						file: {
							  extension: 'jpg,jpeg,png,gif',
							  type: 'image/jpg,image/jpeg,image/png,image/gif',
							  maxSize: 5*1024*1024,   // 5 MB
							  message: 'The selected file is not valid, it should be (jpg,jpeg,png,gif) and 5 MB at maximum.'
						}
					}
			},
          
        }
        
    }).on('reset', function (event) {
        $('#frmWageEntry').data('bootstrapValidator').resetForm();
    });
	
	$('#payment_date').datepicker( { dateFormat: 'dd-mm-yyyy',minDate: new Date('{{$settings->from_date}}'),maxDate: new Date('{{$settings->to_date}}') } );
});


function getLineTotal(n) {
	
	var basic = parseFloat( ($('#salary').val()=='') ? 0 : $('#salary').val() );
	var nwh_hr = parseFloat( ($('#empnwh').val()=='') ? 0 : $('#empnwh').val() );
	var paydays = parseFloat( ($('#paydays').val()=='') ? 0 : $('#paydays').val() );
	var ot_gen = parseFloat( ($('#ot_general').val()=='') ? 0 : $('#ot_general').val() );
	var ot_hol = parseFloat( ($('#ot_holiday').val()=='') ? 0 : $('#ot_holiday').val() );
	
	var wage 	 = parseFloat( ($('#wage_'+n).val()=='') ? 0 : $('#wage_'+n).val() );
	var nodays 	 = parseFloat( ($('#nodays_'+n).val()=='') ? 0 : $('#nodays_'+n).val() );
	var nwh	 = wage * nodays; $('#nwh_'+n).val(nwh);
	
	var wage_hr = basic / paydays / nwh_hr; 
	
	var otg = parseFloat( ($('#otg_'+n).val()=='') ? 0 : $('#otg_'+n).val() );
	var oth = parseFloat( ($('#oth_'+n).val()=='') ? 0 : $('#oth_'+n).val() );
	var alw = parseFloat( ($('#alw_'+n).val()=='') ? 0 : $('#alw_'+n).val() );
	
	var otg_tot = wage_hr * ot_gen * otg;
	var oth_tot = wage_hr * ot_hol * oth;
	
	var lineTotal = (wage_hr * nwh) + otg_tot + oth_tot;
	//console.log(lineTotal);	
	$('#itmttl_'+n).val(lineTotal.toFixed(2));
	
	return lineTotal;
}


function getNetTotal() {
		
	var lineTotal = 0;
	$( '.line-total' ).each(function() { 
	  var res = this.id.split('_');
	  var n = res[1];
	  var amt = getLineTotal(n);
	  lineTotal = lineTotal + parseFloat(amt.toFixed(2));
	});
	
	var otg_net = 0;
	$( '.line-otg' ).each(function() {
		otg_net = otg_net + parseFloat(this.value);
	});
	
	var oth_net = 0;
	$( '.line-oth' ).each(function() {
		oth_net = oth_net + parseFloat(this.value);
	});
	
	$('#otg_net').val(otg_net.toFixed(2));
	$('#oth_net').val(oth_net.toFixed(2));
	
	var hra_net = parseFloat( ($('#hra_net').val()=='') ? 0 : $('#hra_net').val() );
	var allowance_net = parseFloat( ($('#allowance_net').val()=='') ? 0 : $('#allowance_net').val() );

	var netTotal = lineTotal + otg_net + oth_net + hra_net + allowance_net;
	$('#net_total').val(netTotal.toFixed(2));
	
}
	
$(function() {	
	var rowNum = 1;
	
	$(document).on('click', '.btn-add-item', function(e)  {
		var wage = $('#wage_'+rowNum).val();
        rowNum++; 
		e.preventDefault();
        var controlForm = $('.controls .itemdivPrnt'),
            currentEntry = $(this).parents('.itemdivChld:first'),
            newEntry = $(currentEntry.clone()).appendTo(controlForm);
			newEntry.find($('input[name="job_id[]"]')).attr('id', 'jobid_' + rowNum);
			newEntry.find($('input[name="job_code[]"]')).attr('id', 'jobcod_' + rowNum);
			newEntry.find($('input[name="wage[]"]')).attr('id', 'wage_' + rowNum);
			newEntry.find($('input[name="nodays[]"]')).attr('id', 'nodays_' + rowNum);
			newEntry.find($('input[name="nwh[]"]')).attr('id', 'nwh_' + rowNum);
			newEntry.find($('input[name="otg[]"]')).attr('id', 'otg_' + rowNum);
			newEntry.find($('input[name="oth[]"]')).attr('id', 'oth_' + rowNum);
			newEntry.find($('input[name="line_total[]"]')).attr('id', 'itmttl_' + rowNum);
			newEntry.find($('.infodivPrntItm')).attr('id', 'infodivPrntItm_' + rowNum);
			newEntry.find($('.more-info')).attr('id', 'moreinfoItm_' + rowNum);
			newEntry.find($('.item-data')).attr('id', 'itemData_' + rowNum);
			newEntry.find($('.locPrntItm')).attr('id', 'locPrntItm_' + rowNum);
			newEntry.find($('.loc-info')).attr('id', 'loc_' + rowNum);
			newEntry.find($('.loc-data')).attr('id', 'locData_' + rowNum);
			newEntry.find('input').val(''); 
			$('#wage_'+rowNum).val(wage);
			controlForm.find('.btn-add-item:not(:last)')
            .removeClass('btn-default').addClass('btn-danger')
            .removeClass('btn-add-item').addClass('btn-remove-item')
            .html('<span class="glyphicon glyphicon-minus" aria-hidden="true"></span> ');
    }).on('click', '.btn-remove-item', function(e)
    { 
		//new change..
		$(this).parents('.itemdivChld:first').remove();
		
		//getNetTotal();
		
		e.preventDefault();
		return false;
	});
	
	var joburl = "{{ url('jobmaster/job_data/') }}";
	$(document).on('click', 'input[name="job_code[]"]', function(e) { 
		var res = this.id.split('_');
		var curNum = res[1]; console.log(curNum);
		$('#job_data').load(joburl+'/'+curNum, function(result){ //.modal-body item
			$('#myModal').modal({show:true});
		});
	});
	
	var empurl = "{{ url('employee/employee_data/') }}";
	$('#employee_name').click(function() { 
		$('#employeeData').load(empurl, function(result) {
			$('#myModal').modal({show:true});
		}); 
	});
	
	$(document).on('click', '.empRow', function(e) { 
		$('#employee_name').val($(this).attr("data-name"));
		$('#employee_id').val($(this).attr("data-id"));
		$('#employee_no').val($(this).attr("data-code"));
		e.preventDefault();
		
	   var url = "{{ url('employee/get_employee/') }}/"+$(this).attr("data-id");
	   $('#getempData').load(url, function(result) {
		  $('#myModal').modal({show:true});
	   });
	   
	   $.get("{{ url('employee/get_empdata/') }}/" + $(this).attr("data-id"), function(data) { console.log(data);
		   var alw = parseFloat(data.transport) + parseFloat(data.allowance);
		   $('#basic_net').val(data.basic);
		   $('#hra_net').val(data.hra);
		   $('#allowance_net').val(alw);
		   $('#net_total').val(data.salary);
		   
		   var totnwh = parseFloat($('#wage_1').val()) * parseFloat($('#nodays_1').val());
			$('#nwh_1').val(totnwh);
			$('#alw_1').val(alw + data.hra);
			$('#itmttl_1').val(data.basic);// + alw + data.hra
	   });
	   
	   
	   
	});
	
	$(document).on('click', '.jobRow', function(e) {
		var num = $('#num').val(); 
		$('#jobcod_'+num).val( $(this).attr("data-code") );
		$('#jobid_'+num).val( $(this).attr("data-id") );
	});
	
	
	$(document).on('blur', '.line-nod', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; //console.log(curNum);
		
		var res = getLineTotal(curNum);
		if(res) 
			getNetTotal();
	});
	
	$(document).on('blur', '.line-nwh', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; //console.log(curNum);
		
		var res = getLineTotal(curNum);
		if(res) 
			getNetTotal();
	});
	
	$(document).on('blur', '.line-otg', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; //console.log(curNum);
		
		var res = getLineTotal(curNum);
		if(res) 
			getNetTotal();
	});
	
	$(document).on('blur', '.line-oth', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; //console.log(curNum);
		
		var res = getLineTotal(curNum);
		if(res) 
			getNetTotal();
	});
	
	
});	

</script>
@stop
