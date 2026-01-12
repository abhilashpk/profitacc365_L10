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
                    Add New
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
                                <i class="fa fa-fw fa-crosshairs"></i> New Entry
                            </h3>
                           
                        </div>
                        <div class="panel-body">
							<div class="controls"> 
                            <form class="form-horizontal" role="form" method="POST" name="frmWageEntry" id="frmWageEntry" action="{{ url('wage_entry/save') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Wage Entry Type</label>
                                    <div class="col-sm-10">
                                        <select id="entry_type" class="form-control select2" style="width:100%" name="entry_type">
											<option value="daily">Daily</option>
											<option value="monthly">Monthly</option>
                                        </select>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Year</label>
                                    <div class="col-sm-10">
                                        <select id="year" class="form-control select2" style="width:100%" name="year">
											<option value="<?php echo date('Y');?>"><?php echo date('Y');?></option>
											<option value="<?php echo date('Y',strtotime('-1 year'));?>"><?php echo date('Y',strtotime('-1 year'));?></option>
                                        </select>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Month</label>
                                    <div class="col-sm-10">
                                        <select id="month" class="form-control select2" style="width:100%" name="month">
											<option value="1">JAN</option>
											<option value="2">FEB</option>
											<option value="3">MAR</option>
											<option value="4">APR</option>
											<option value="5">MAY</option>
											<option value="6">JUN</option>
											<option value="7">JUL</option>
											<option value="8">AUG</option>
											<option value="9">SEP</option>
											<option value="10">OCT</option>
											<option value="11">NOV</option>
											<option value="12">DEC</option>
                                        </select>
                                    </div>
                                </div>
								
								<!--<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Payment Date</label>
                                    <div class="col-sm-10">
										<input type="text" class="form-control pull-right" name="payment_date" data-language='en' readonly id="payment_date" placeholder="{{date('d-m-Y')}}"/>
                                    </div>
                                </div>-->
								<input type="hidden" name="payment_date">
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Employee Name</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="employee_name" id="employee_name" class="form-control" autocomplete="off" data-toggle="modal" data-target="#employee_modal" placeholder="Employee Name">
										<input type="hidden" name="employee_id" id="employee_id">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Employee No.</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="employee_no" autocomplete="off" name="employee_no" placeholder="Employee No.">
                                    </div>
                                </div>
								
								<div id="getempData"> </div>
								
								<hr/>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Basic</label>
                                    <div class="col-xs-10">
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-4">
										<input type="number" id="basic_net" step="any" name="basic_net" class="form-control" readonly placeholder="0">
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
										<input type="number" id="hra_net" step="any" name="hra_net" class="form-control" readonly placeholder="0">
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
										<input type="number" id="allowance_net" step="any" name="allowance_net" class="form-control" readonly placeholder="0">
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
										<input type="number" id="otg_net" step="any" name="otg_net" class="form-control" readonly placeholder="0">
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
										<input type="number" id="oth_net" step="any" name="oth_net" class="form-control" readonly placeholder="0">
										</div>
									</div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Deductions</label>
                                    <div class="col-xs-10">
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-4">
										<input type="number" id="deductions" step="any" name="deductions" class="form-control" readonly placeholder="0">
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
										<input type="number" id="net_total" step="any" name="net_total" class="form-control" readonly placeholder="0">
										</div>
									</div>
                                </div>
								
								<br/>
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                         <a href="{{ url('wage_entry') }}" class="btn btn-danger">Cancel</a>
										 <a href="{{ url('wage_entry/add') }}" class="btn btn-warning">Clear</a>
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
	//console.log(wg);
	var wage = wg;//parseFloat( ($('#wage_'+n).val()=='') ? 0 : $('#wage_'+n).val() );
	var nwh	 = parseFloat( ($('#nwh_'+n).val()=='') ? 0 : $('#nwh_'+n).val() );
	var nod	 = parseFloat( ($('#nodays_'+n).val()=='') ? 0 : $('#nodays_'+n).val() );
	//var nwh = nwhr * nod;
	var nor_wage = wage * nwh; //console.log(wage);
	
	var otg = parseFloat( ($('#otg_'+n).val()=='') ? 0 : $('#otg_'+n).val() );
	var otg_tot = ot_hr_sal * otg * otper;
	
	var oth = parseFloat( ($('#oth_'+n).val()=='') ? 0 : $('#oth_'+n).val() );
	var oth_tot = ot_hr_sal * oth;
	
	var alw = parseFloat( ($('#alw_'+n).val()=='') ? 0 : $('#alw_'+n).val() );
	
	var aln = alhr * nwh;
	
	var lineTotal = nor_wage + otg_tot + oth_tot;
	//console.log('alw'+aln);
	//$('#nwh_'+n).val(nwh);
	$('#itmttl_'+n).val(lineTotal.toFixed(3));
	$('#alw_'+n).val(aln.toFixed(3));
	$('#otgttl_'+n).val(otg_tot);
	$('#othttl_'+n).val(oth_tot);
	
	return lineTotal;
}


function getNetTotalDefault() {  
	
	var wage_total = 0;
	$( '.line-total' ).each(function() { 
		wage_total = wage_total + parseFloat( (this.value=='')?0:this.value );
	});
	
	var alw_total = 0;
	$( '.line-alwnc' ).each(function() { 
		alw_total = alw_total + parseFloat( (this.value=='')?0:this.value );
	});
	
	var otg_net = 0;
	$( '.otgtotal' ).each(function() { 
		otg_net = otg_net + parseFloat( (this.value=='')?0:this.value );
	});
	
	var oth_net = 0;
	$( '.othtotal' ).each(function() {
		oth_net = oth_net + parseFloat((this.value=='')?0:this.value);
	});
	
	var lev = 0;
	$( '.wph' ).each(function() { 
		lev = (this.value==0)?lev+1:lev;
	});
	
	$('#otg_net').val(otg_net.toFixed(3));
	$('#oth_net').val(oth_net.toFixed(3));
	
	$('#basic_net').val(Math.round(wage_total)); //console.log(wage_total.toFixed(3));
	var basic_net = parseFloat( ($('#basic_net').val()=='') ? 0 : $('#basic_net').val() );
	
	var ded = (lev * itl) + (lev * al);
	
	//$('#hra_net').val(ehra.toFixed(3));
	var hra_net = parseFloat( ($('#hra_net').val()=='') ? 0 : $('#hra_net').val() );
	//hra_net / div
	
	//$('#allowance_net').val(ealw.toFixed(3));
	var allowance_net = parseFloat( ($('#allowance_net').val()=='') ? 0 : $('#allowance_net').val() );
	
	var deduction = 0;//parseFloat( ($('#deductions').val()=='') ? 0 : $('#deductions').val() );
	deduction = parseFloat(deduction + ded);
	$('#deductions').val(deduction.toFixed(3));
	
	var oth_allw1 = parseFloat( ($('#oth_allowance1').val()=='') ? 0 : $('#oth_allowance1').val() );
	var oth_allw2 = parseFloat( ($('#oth_allowance2').val()=='') ? 0 : $('#oth_allowance2').val() );
	var oth_allw3 = parseFloat( ($('#oth_allowance3').val()=='') ? 0 : $('#oth_allowance3').val() );
	var oth_allw4 = parseFloat( ($('#oth_allowance4').val()=='') ? 0 : $('#oth_allowance4').val() );
	
	var oth_ded1 = parseFloat( ($('#oth_deduction1').val()=='') ? 0 : $('#oth_deduction1').val() );
	var oth_ded2 = parseFloat( ($('#oth_deduction2').val()=='') ? 0 : $('#oth_deduction2').val() );
	var oth_ded3 = parseFloat( ($('#oth_deduction3').val()=='') ? 0 : $('#oth_deduction3').val() );
	var oth_ded4 = parseFloat( ($('#oth_deduction4').val()=='') ? 0 : $('#oth_deduction4').val() );
	
	var oth_allw = oth_allw1 + oth_allw2 + oth_allw3 + oth_allw4;
	var oth_ded = oth_ded1 + oth_ded2 + oth_ded3 + oth_ded4;
	
	var netTotal = basic_net + otg_net + oth_net + hra_net + allowance_net + oth_allw - deduction - oth_ded;
	$('#net_total').val(netTotal.toFixed(3));
	
}

function getNetTotal() {  
	
	var wage_total = 0;
	$( '.line-total' ).each(function() { 
		wage_total = wage_total + parseFloat( (this.value=='')?0:this.value );
	});
	
	var alw_total = 0;
	$( '.line-alwnc' ).each(function() { 
		alw_total = alw_total + parseFloat( (this.value=='')?0:this.value );
	});
	
	var otg_net = 0;
	$( '.otgtotal' ).each(function() { 
		otg_net = otg_net + parseFloat( (this.value=='')?0:this.value );
	});
	
	var oth_net = 0;
	$( '.othtotal' ).each(function() {
		oth_net = oth_net + parseFloat((this.value=='')?0:this.value);
	});
	
	var hd = 0;
	$( '.leave' ).each(function() { 
		hd = (this.value==4)?hd+1:hd;
	});
	
	var hd_de = 0;
	if(hd > 0){
		hd_de = (nwhr / 2) * hd * wg;
	}
	
	var lev = 0;
	$( '.wph' ).each(function() { 
		lev = (this.value==0)?lev+1:lev;
	});
	
	$('#otg_net').val(otg_net.toFixed(3));
	$('#oth_net').val(oth_net.toFixed(3));
	
	//$('#basic_net').val(Math.round(wage_total)); //console.log(wage_total.toFixed(3));
	var basic_net = parseFloat( ($('#basic_net').val()=='') ? 0 : $('#basic_net').val() );
	
	var ded = (lev * itl) + (lev * al) + hd_de;//(basic_net > wage_total)?basic_net - wage_total: 0;
	
	//$('#hra_net').val(ehra.toFixed(3));
	var hra_net = parseFloat( ($('#hra_net').val()=='') ? 0 : $('#hra_net').val() );
	//hra_net / div
	
	//$('#allowance_net').val(ealw.toFixed(3));
	var allowance_net = parseFloat( ($('#allowance_net').val()=='') ? 0 : $('#allowance_net').val() );
	
	var deduction = 0;//parseFloat( ($('#deductions').val()=='') ? 0 : $('#deductions').val() );
	deduction = parseFloat(deduction + ded);
	$('#deductions').val(deduction.toFixed(3));
	
	var oth_allw1 = parseFloat( ($('#oth_allowance1').val()=='') ? 0 : $('#oth_allowance1').val() );
	var oth_allw2 = parseFloat( ($('#oth_allowance2').val()=='') ? 0 : $('#oth_allowance2').val() );
	var oth_allw3 = parseFloat( ($('#oth_allowance3').val()=='') ? 0 : $('#oth_allowance3').val() );
	var oth_allw4 = parseFloat( ($('#oth_allowance4').val()=='') ? 0 : $('#oth_allowance4').val() );
	
	var oth_ded1 = parseFloat( ($('#oth_deduction1').val()=='') ? 0 : $('#oth_deduction1').val() );
	var oth_ded2 = parseFloat( ($('#oth_deduction2').val()=='') ? 0 : $('#oth_deduction2').val() );
	var oth_ded3 = parseFloat( ($('#oth_deduction3').val()=='') ? 0 : $('#oth_deduction3').val() );
	var oth_ded4 = parseFloat( ($('#oth_deduction4').val()=='') ? 0 : $('#oth_deduction4').val() );
	
	var oth_allw = oth_allw1 + oth_allw2 + oth_allw3 + oth_allw4;
	var oth_ded = oth_ded1 + oth_ded2 + oth_ded3 + oth_ded4;
	
	var netTotal = basic_net + otg_net + oth_net + hra_net + allowance_net + oth_allw - deduction - oth_ded;
	$('#net_total').val(netTotal.toFixed(3));
	
}

$(function() {	
	
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
		
	var et = $('#entry_type option:selected').val();
	   var yr = $('#year').val();
	   var mh = $('#month').val();
	   
	   var url = "{{ url('employee/get_employee/') }}/"+$(this).attr("data-id")+"/"+et+"/"+yr+"/"+mh;
	   $('#getempData').load(url, function(result) {
		  $('#myModal').modal({show:true});
		  if(et=='daily')
			getNetTotalDefault();
	   });
	   
	   $.get("{{ url('employee/get_empdata/') }}/" + $(this).attr("data-id"), function(data) { //console.log(data);
		   var alw = parseFloat(data.transport) + parseFloat(data.allowance);
		   if(et=='monthly')
				$('#basic_net').val(data.basic);
			
		   $('#hra_net').val(data.hra);
		   $('#allowance_net').val(alw);
		   $('#net_total').val(data.net_total);
		
	   });
	    
	  
	});
	
	$(document).on('click', '.jobRow', function(e) {
		var num = $('#num').val(); 
		$('#jobcod_'+num).val( $(this).attr("data-cod")+'-'+$(this).attr("data-name") ); 
		$('#jobid_'+num).val( $(this).attr("data-id") );
	});
	
	
	/* $(document).on('blur', '.line-nod', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; //console.log(curNum);
		
		var res = getLineTotal(curNum);
		if(res) 
			getNetTotal();
	}); */
	
	/* $(document).on('blur', '.line-nwh', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; //console.log(curNum);
		
		var res = getLineTotal(curNum);
		if(res) 
			getNetTotal();
	}); */
	
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
	
	$(document).on('blur', '.line-nod', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; //console.log(curNum);
		
		var res = getLineTotal(curNum);
		if(res) 
			getNetTotal();
	});
	
	$(document).on('blur', '#deductions', function(e) {
		getNetTotal();
	});
	
	$(document).on('blur', '.allw-ded', function(e) {
		getNetTotal();
	});
	
	var joburl = "{{ url('jobmaster/job_assign/') }}";
	$(document).on('click', 'input[name="job_code[]"]', function(e) { 
		var res = this.id.split('_');
		var curNum = res[1]; console.log(curNum);
		$('#job_data').load(joburl+'/'+curNum, function(result){ //.modal-body item
			$('#myModal').modal({show:true});
		});
	});
	
	$(document).on('click', '.add-job', function(e)  { 
	
		var jcode = []; var jhours = []; var ids = []; var jtype = [];
		$("input[name='tag[]']:checked").each(function() { 
			var res = this.id.split('_');
			var curNum = res[1];
			ids.push($(this).val());
			jcode.push( $('#code_'+curNum).val() );
			jhours.push( $('#hour_'+curNum).val() );
			jtype.push( $('#jtype_'+curNum+' option:selected').val() )
		});
		
		var no = $('#num').val();
		rowNum = parseInt(no);
		var cod = ''; var jid = ''; var jdata = '';
		var hr = 0; var othr; var othh;
		$.each(jcode,function(i) {
			cod += (cod=='')?jcode[i]:','+jcode[i];
			jid += (jid=='')?ids[i]:','+ids[i];
			hr += parseFloat(jhours[i]); 
			
			if(hr > nwh) {
				var nhr = nwh - parseFloat(hr - jhours[i]);
				othr = parseFloat(hr - nwh);
				
				if(jtype[i]==0 && nhr > 0) {
					jdata += (jdata=='') ? jtype[i]+':'+ids[i]+','+nhr : '|'+jtype[i]+':'+ids[i]+','+nhr;
					if(othr > 0) {
						jdata += (jdata=='') ? '1:'+ids[i]+','+othr : '|'+'1:'+ids[i]+','+othr;
						$('#otg_'+no).val(othr);
					}
				} else if(jtype[i]==1) {//OTG
					jdata += (jdata=='') ? '1:'+ids[i]+','+othr : '|1:'+ids[i]+','+othr;
					$('#otg_'+no).val(othr);
				} else if(jtype[i]==2) {//OTH
					jdata += (jdata=='') ? '2:'+ids[i]+','+othr : '|2:'+ids[i]+','+othr;
					$('#oth_'+no).val(othr);
				}
			} else {
				jdata += (jdata=='') ? jtype[i]+':'+ids[i]+','+jhours[i] : '|'+jtype[i]+':'+ids[i]+','+jhours[i];
				//$('#oth_'+no).val(jhours[i]);
			}
			//othh = (jtype[i]==2)?jhours[i]:'';
			
		});
		
		$('#jobcod_'+no).val( cod );
		$('#jobid_'+no).val( jid );
		$('#jobdata_'+no).val( jdata );
		//$('#otg_'+no).val(othr);
		//$('#oth_'+no).val(othh);
		var res = getLineTotal(no);
		if(res) 
			getNetTotal();
	});
	
	$(document).on('change', '.leave', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		var leave = this.value; //console.log(leave);
		if(leave==1) { //status Off
			$('#nwh_'+curNum).val(nwhr);
			$('#jobcod_'+curNum).val('');
			$('#jobdata_'+curNum).val('');
			$('#jobid_'+curNum).val('');
			if( $('#infodivLevItm_'+curNum).is(":visible") )
				$('#infodivLevItm_'+curNum).toggle();
		} else if(leave==2){ //status Absent
			//$("#pstatus_"+curNum).val('P').change();
			$("#pstatus_"+curNum+" option[value=2]").attr('selected', 'selected');
			$('#wage_'+curNum).val(0);
			$('#alw_'+curNum).val(0);
			$('#nwh_'+curNum).val(0);
			if( $('#infodivLevItm_'+curNum).is(":visible") )
				$('#infodivLevItm_'+curNum).toggle();
		} else if(leave==3){ //status Absent
			
			$("#pstatus_"+curNum+" option[value=2]").attr('selected', 'selected');
			$('#wage_'+curNum).val(0);
			$('#alw_'+curNum).val(0);
			$('#nwh_'+curNum).val(0);
			
			if( $('#infodivLevItm_'+curNum).is(":hidden") )
				$('#infodivLevItm_'+curNum).toggle();
			else if( $('#infodivLevItm_'+curNum).is(":visible") )
				$('#infodivLevItm_'+curNum).toggle();
			
		} else if(leave==4){ //status half day
			nwh = nwhr/2;
			$('#nwh_'+curNum).val(nwh);
			
		} else {
			$('#nwh_'+curNum).val(nwhr);
			$('#wage_'+curNum).val(wg.toFixed(3));
			$('#alw_'+curNum).val(al);
			$('#itmttl_'+curNum).val(itl);
			if( $('#infodivLevItm_'+curNum).is(":visible") )
				$('#infodivLevItm_'+curNum).toggle();
		}
		getLineTotal(curNum); //console.log(res);
		getNetTotal();
	});
	
	$(document).on('change', '.pstatus', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		var status = this.value;
		if(status==2) {
			$('#wage_'+curNum).val(0);
			$('#alw_'+curNum).val(0);
			
		} else {
			//$(".pstatus").removeAttr("disabled");
			$('#wage_'+curNum).val(wg.toFixed(3));
			$('#alw_'+curNum).val(al);
			$('#itmttl_'+curNum).val(itl);
		}
		getLineTotal(curNum); //console.log(res);
		getNetTotal();
	});
});	

</script>
@stop
