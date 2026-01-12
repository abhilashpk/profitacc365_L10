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
                            <form class="form-horizontal" role="form" method="POST" name="frmWageEntry" id="frmWageEntry" action="{{ url('wage_entry/update/'.$wagerow->weid) }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" name="id" value="{{$wagerow->weid}}">
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Wage Entry Type</label>
                                    <div class="col-sm-10">
                                        <select id="entry_type" class="form-control select2" style="width:100%" name="entry_type">
											<?php if($wagerow->entry_type=='daily') { ?>
											<option value="daily">Daily</option>
											<?php } elseif($wagerow->entry_type=='monthly') { ?>
											<option value="monthly">Monthly</option>
											<?php } ?>
                                        </select>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Year</label>
                                    <div class="col-sm-10">
                                        <select id="year" class="form-control select2" style="width:100%" name="year">
											<option value="<?php echo $wagerow->year;?>"><?php echo $wagerow->year;?></option>
                                        </select>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Month</label>
                                    <div class="col-sm-10">
                                        <select id="month" class="form-control select2" style="width:100%" name="month">
											<option value="1" <?php if($wagerow->month==1) echo 'selected';?>>JAN</option>
											<option value="2" <?php if($wagerow->month==2) echo 'selected';?>>FEB</option>
											<option value="3" <?php if($wagerow->month==3) echo 'selected';?>>MAR</option>
											<option value="4" <?php if($wagerow->month==4) echo 'selected';?>>APR</option>
											<option value="5" <?php if($wagerow->month==5) echo 'selected';?>>MAY</option>
											<option value="6" <?php if($wagerow->month==6) echo 'selected';?>>JUN</option>
											<option value="7" <?php if($wagerow->month==7) echo 'selected';?>>JUL</option>
											<option value="8" <?php if($wagerow->month==8) echo 'selected';?>>AUG</option>
											<option value="9" <?php if($wagerow->month==9) echo 'selected';?>>SEP</option>
											<option value="10" <?php if($wagerow->month==10) echo 'selected';?>>OCT</option>
											<option value="11" <?php if($wagerow->month==11) echo 'selected';?>>NOV</option>
											<option value="12" <?php if($wagerow->month==12) echo 'selected';?>>DEC</option>
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
                                        <input type="text" name="employee_name" id="employee_name" class="form-control" autocomplete="off" data-toggle="modal" data-target="#employee_modal" value="{{$wagerow->name}}">
										<input type="hidden" name="employee_id" id="employee_id" value="{{$wagerow->employee_id}}">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Employee No.</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="employee_no" autocomplete="off" name="employee_no" value="{{$wagerow->code}}">
                                    </div>
                                </div>
								
						<div id="getempData"> 
						
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">Desigantion</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="designation" value="{{$wagerow->designation}}" autocomplete="off" name="designation" readonly>
										</div>
									</div>

									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">Department</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="department" value="{{$wagerow->department}}" autocomplete="off" name="department" readonly>
										</div>
									</div>
									
							
							
							<fieldset>
								<legend><h5>Job Details</h5></legend>
								<div class="itemdivPrnt">
									<div class="itemdivChld">
									<?php 
										$basic = ($employee->basic_pay_nw==1)?$employee->basic_pay:0;
										$hra = ($employee->hra_nw==1)?$employee->hra:0;
										$transport = ($employee->transport_nw==1)?$employee->transport:0;
										$allowance1 = ($employee->allowance1_nw==1)?$employee->allowance:0;
										$allowance2 = ($employee->allowance2_nw==1)?$employee->allowance2:0;
										$base_sal = $basic + $hra + $transport + $allowance1 + $allowance2;
										
										$ot_basic = ($employee->basic_pay_otw==1)?$employee->basic_pay:0;
										$ot_hra = ($employee->hra_otw==1)?$employee->hra:0;
										$ot_transport = ($employee->transport_otw==1)?$employee->transport:0;
										$ot_allowance1 = ($employee->allowance1_otw==1)?$employee->allowance:0;
										$ot_allowance2 = ($employee->allowance2_otw==1)?$employee->allowance2:0;
										$ot_base_sal = $ot_basic + $ot_hra + $ot_transport + $ot_allowance1 + $ot_allowance2;
			
										
										
										
											$month = $wagerow->month;
											$year = $wagerow->year;
											if($employee->nwage==30) {
												$div = 30;
											} else if($employee->nwage==365) {
												$div = 365;
											} else if($employee->nwage=='monthly') {
												$div = date('t', mktime(0, 0, 0, $month, 1, $year));//cal_days_in_month(CAL_GREGORIAN, $month, $year);
											}
											
											if($employee->otwage==30) {
												$divot = 30;
											} else if($employee->otwage==365) {
												$divot = 365;
											} else if($employee->otwage=='monthly') {
												$divot =date('t', mktime(0, 0, 0, $month, 1, $year)); //cal_days_in_month(CAL_GREGORIAN, $month, $year);
											}
											
											$mth = date('t', mktime(0, 0, 0, $month, 1, $year));//cal_days_in_month(CAL_GREGORIAN, $month, $year);
											$per_hr_sal = ($employee->nwage==365)?(($base_sal / $div) / $employee->nwh * 12):($base_sal / $div) / $employee->nwh;
											
											if($employee->nwage==365) {
												$alwnc_per_day = number_format(($employee->hra + $employee->transport + $employee->allowance + $employee->allowance2) / $div,3) * 12;
												$alwnctrans_per_day = number_format(( $employee->transport+ $employee->allowance + $employee->allowance2) / $div,3) * 12;
						                         $alwnchra_per_day = number_format($employee->hra / $div,3) * 12;
												$alwnc_per_hr = (($employee->hra + $employee->transport + $employee->allowance + $employee->allowance2) / $div) / $employee->nwh * 12;
											} else {
												$alwnc_per_day = number_format(($employee->hra + $employee->transport + $employee->allowance + $employee->allowance2) / $div,3);
												$alwnctrans_per_day = number_format(( $employee->transport + $employee->allowance + $employee->allowance2) / $div,3);
						                         $alwnchra_per_day = number_format($employee->hra / $div,3);
												$alwnc_per_hr = (($employee->hra + $employee->transport + $employee->allowance + $employee->allowance2) / $div / $employee->nwh);
											}
											
											if($employee->nwage==365)
												$total_wage = number_format($per_hr_sal,3) * $employee->nwh;
											else
												$total_wage = ($employee->nwage==365)?(($base_sal / $div) * 12):($base_sal / $div);
											
											
											$ot_per_hr_sal = ($employee->otwage==365)?(($ot_base_sal / $divot) / $employee->nwh * 12): ($ot_base_sal / $divot) / $employee->nwh;
										
											$i=0;
											$mth = date('t', mktime(0, 0, 0, $month, 1, $year));//cal_days_in_month(CAL_GREGORIAN, $wagerow->month, $wagerow->year);
											$alwnc_per_day = ($wagerow->net_allowance + $wagerow->net_hra) / $mth;
											//$ot_per_hr_sal = ($ot_base_sal / $mth) / $parameter->nwh;
											
											if($wagerow->entry_type=="daily") { 
											
											foreach($items as $item) { 
											$wph = $item->wage;
											$nwh = $item->nwh;
											$wge = $per_hr_sal;
											$i++;
										?>
										<table border="0" class="table-dy-row">
										<tr>
											<td width="10%">
												<input type="hidden" name="wei_id[]" id="weiid_{{$i}}" value="{{$item->wei_id}}">
												<span class="small">Day</span><input type="text" value="{{$item->day}}" name="day[]"  id="day_{{$i}}" step="any" autocomplete="off" class="form-control" readonly>
											</td>
											<td width="15%">
												<span class="small">Job Code</span>
												<input type="hidden" name="job_id[]" id="jobid_{{$i}}" value="{{$item->job_id}}">
												<input type="hidden" name="job_data[]" value="{{$item->job_data}}" id="jobdata_{{$i}}">
												<input type="text" id="jobcod_{{$i}}" name="job_code[]" class="form-control" autocomplete="off" value="{{$item->j2code.'-'.$item->j2name}}" autocomplete="off" data-toggle="modal" data-target="#job_modal" placeholder="Job Code">
											</td>
											<td width="6%">
												<span class="small">Status</span>
												<select id="leave_{{$i}}" name="leave[]" class="form-control leave">
													<option value="0" <?php if($item->leave_status==0) echo 'selected';?>>P</option><option value="1" <?php if($item->leave_status==1) echo 'selected';?>>Off</option><option value="2" <?php if($item->leave_status==2) echo 'selected';?>>A</option><option value="3" <?php if($item->leave_status==3) echo 'selected';?>>R</option><option value="4" <?php if($item->leave_status==4) echo 'selected';?>>H/D</option>
												</select>
											</td>
											<td width="6%">
												<span class="small">Lv.Type</span>
												<select id="pstatus_{{$i}}" name="pstatus[]" class="form-control pstatus">
													<option value=""></option><option value="2" <?php if($item->leave_type==2) echo 'selected';?>>U</option><option value="1" <?php if($item->leave_type==1) echo 'selected';?>>P</option>
												</select>
											</td>
											<td width="10%">
												<span class="small">Wage/Hr.</span><input type="number" value="{{$item->wage}}" name="wage[]"  id="wage_{{$i}}" step="any" autocomplete="off" class="form-control wph" readonly>
											</td>
											<td width="8%">
												<span class="small">Days</span><input type="number" id="nodays_{{$i}}" step="any" value="{{$item->nodays}}" name="nodays[]" autocomplete="off" class="form-control line-nod" readonly>
											</td>
											<td width="8%">
												<span class="small">NWH</span> <input type="number" id="nwh_{{$i}}" step="any" name="nwh[]" autocomplete="off" class="form-control line-nwh" value="{{$item->nwh}}" readonly>
											</td>
											<td width="8%">
												<span class="small">OTHr(G)</span> <input type="number" id="otg_{{$i}}" step="any" name="otg[]" value="{{$item->otg}}" autocomplete="off" class="form-control line-otg" >
												<input type="hidden" id="otgst_{{$i}}" name="otgst[]" class="otgst">
											</td>
											<td width="8%">
												<span class="small">OTHr(H)</span><input type="number" id="oth_{{$i}}" step="any" name="oth[]" value="{{$item->oth}}" autocomplete="off" class="form-control line-oth" >
												<input type="hidden" id="othst_{{$i}}" name="othst[]" class="othst">
											</td>
											<td width="12%">
												<span class="small">Total Wage</span> 
												<input type="number" id="itmttl_{{$i}}" step="any" name="line_total[]" value="{{$item->total_wage}}" class="form-control line-total" readonly>
												<input type="hidden" id="otgttl_{{$i}}" name="otg_total[]" class="otgtotal">
												<input type="hidden" id="othttl_{{$i}}" name="oth_total[]" class="othtotal">
											</td>
											<td width="12%">
												<span class="small">Allowance</span><input type="number" id="alw_{{$i}}" step="any" name="alw[]" autocomplete="off" value="{{$item->allowance}}" class="form-control line-alwnc" readonly>
											</td>
										</tr>
										</table>
										<?php if($item->leave_reason!="") { ?>
										<div class="infodivLevItmEdt" id="infodivLevItm_{{$i}}">
											<div class="col-xs-6">	
												<input type="text" id="levrsn_{{$i}}" name="leave_reason[]" class="form-control" value="{{$item->leave_reason}}">
											</div>
										</div>
										<?php } else { ?>
										<div class="infodivLevItm" id="infodivLevItm_{{$i}}">
											<div class="col-xs-6">							
												<input type="text" id="levrsn_{{$i}}" name="leave_reason[]" class="form-control" placeholder="Leave Reason">
											</div>
										</div>
										<?php } ?>
										
									<?php } ?>
										
									<?php } else { 
											$wge = $per_hr_sal;
											foreach($items as $item) { 
											$wph = $item->wage;
											$nwh = $item->nwh;
											
											$i++; ?>
											<table border="0" class="table-dy-row">
												<tr>
													<td width="15%">
														<span class="small">Job Code</span>
														<input type="hidden" name="day[]">
														<input type="hidden" name="job_id[]" id="jobid_{{$i}}" value="{{$item->job_id}}">
														<input type="hidden" name="job_data[]" value="{{$item->job_data}}" id="jobdata_{{$i}}">
														<input type="text" id="jobcod_{{$i}}" name="job_code[]" class="form-control" autocomplete="off" value="{{$item->j2code.'-'.$item->j2name}}" autocomplete="off" data-toggle="modal" data-target="#job_modal" placeholder="Job Code">
														<input type="hidden" name="wei_id[]" id="weiid_{{$i}}" value="{{$item->wei_id}}">
														<input type="hidden" name="leave[]">
														<input type="hidden" name="leave_reason[]">
													</td>
													<td width="10%">
														<span class="small">Wage/Hr.</span><input type="number" value="{{$item->wage}}" name="wage[]"  id="wage_{{$i}}" step="any" autocomplete="off" class="form-control wph" readonly>
													</td>
													<td width="8%">
														<span class="small">Days</span><input type="number" id="nodays_{{$i}}" step="any" value="{{$item->nodays}}" name="nodays[]" autocomplete="off" class="form-control line-nod" readonly>
													</td>
													<td width="5%">
														<span class="small">NWH</span> <input type="number" id="nwh_{{$i}}" step="any" name="nwh[]" autocomplete="off" class="form-control line-nwh" value="{{$item->nwh}}" readonly>
													</td>
													<td width="5%">
														<span class="small">OTHr(G)</span> <input type="number" id="otg_{{$i}}" step="any" name="otg[]" value="{{$item->otg}}" autocomplete="off" class="form-control line-otg" >
														<input type="hidden" id="otgst_{{$i}}" name="otgst[]" class="otgst">
													</td>
													<td width="8%">
														<span class="small">OTHr(H)</span><input type="number" id="oth_{{$i}}" step="any" name="oth[]" value="{{$item->oth}}" autocomplete="off" class="form-control line-oth" >
														<input type="hidden" id="othst_{{$i}}" name="othst[]" class="othst">
													</td>
													<td width="8%">
														<span class="small">Paid Leave</span><input type="number" id="plevd_{{$i}}" step="any" name="paid_leave[]" value="{{$item->paid_leave}}" autocomplete="off" class="form-control line-levd" >
													</td>
													<td width="10%">
														<span class="small">Unpaid Leave</span><input type="number" id="uplevd_{{$i}}" step="any" name="unpaid_leave[]" value="{{$item->unpaid_leave}}" autocomplete="off" class="form-control line-uplevd" >
													</td>
													<td width="12%">
														<span class="small">Total Wage</span> 
														<input type="number" id="itmttl_{{$i}}" step="any" name="line_total[]" value="{{$item->total_wage}}" class="form-control line-total" readonly>
														<input type="hidden" id="otgttl_{{$i}}" name="otg_total[]" class="otgtotal">
														<input type="hidden" id="othttl_{{$i}}" name="oth_total[]" class="othtotal">
													</td>
													<td width="12%">
														<span class="small">Allowance</span><input type="number" id="alw_{{$i}}" step="any" name="alw[]" autocomplete="off" value="{{$item->allowance}}" class="form-control line-alwnc" readonly>
													</td>
												</tr>
												</table>
												
											<?php } } ?>
									</div>
								</div>
							</fieldset>
							
							<div id="moreinfo">
								<button type="button" id="moreinfoItm_1" class="btn btn-primary btn-xs more-info">More Info</button>
							</div>
							
							<div id="empinfo">
								<input type="hidden" name="weo_id" value="{{$wagerow->weo_id}}">
								<div class="form-group">
									<label for="input-text" class="col-sm-2 control-label">Other Allowance1</label>
									<div class="col-sm-5">
										<input type="text" class="form-control allw-ded" id="oth_allowance1" autocomplete="off" value="{{$wagerow->oth_allowance1}}" name="oth_allowance1">
									</div>
									<div class="col-sm-5">
										<input type="text" class="form-control" id="desc_allowance1" autocomplete="off" name="desc_allowance1"  value="{{$wagerow->desc_allowance1}}" placeholder="Description">
									</div>
								</div>

								<div class="form-group">
									<label for="input-text" class="col-sm-2 control-label">Other Allowance2</label>
									<div class="col-sm-5">
										<input type="text" class="form-control allw-ded" id="oth_allowance2" autocomplete="off" value="{{$wagerow->oth_allowance2}}" name="oth_allowance2">
									</div>
									<div class="col-sm-5">
										<input type="text" class="form-control" id="desc_allowance2" autocomplete="off" name="desc_allowance2" value="{{$wagerow->desc_allowance2}}" placeholder="Description">
									</div>
								</div>
								
								<div class="form-group">
									<label for="input-text" class="col-sm-2 control-label">Other Allowance3</label>
									<div class="col-sm-5">
										<input type="text" class="form-control allw-ded" id="oth_allowance3" autocomplete="off" value="{{$wagerow->oth_allowance3}}" name="oth_allowance3">
									</div>
									<div class="col-sm-5">
										<input type="text" class="form-control" id="desc_allowance3" autocomplete="off" name="desc_allowance3" value="{{$wagerow->desc_allowance3}}" placeholder="Description">
									</div>
								</div>
								
								<div class="form-group">
									<label for="input-text" class="col-sm-2 control-label">Other Allowance4</label>
									<div class="col-sm-5">
										<input type="text" class="form-control allw-ded" id="oth_allowance4" autocomplete="off" value="{{$wagerow->oth_allowance4}}" name="oth_allowance4">
									</div>
									<div class="col-sm-5">
										<input type="text" class="form-control" id="desc_allowance4" autocomplete="off" name="desc_allowance4" value="{{$wagerow->desc_allowance4}}" placeholder="Description">
									</div>
								</div>
								<hr/>
								<div class="form-group">
									<label for="input-text" class="col-sm-2 control-label">Other Deduction1</label>
									<div class="col-sm-5">
										<input type="text" class="form-control allw-ded" id="oth_deduction1" autocomplete="off" name="oth_deduction1" value="{{$wagerow->oth_deduction1}}">
									</div>
									<div class="col-sm-5">
										<input type="text" class="form-control" id="desc_deduction1" autocomplete="off" name="desc_deduction1" value="{{$wagerow->desc_deduction1}}" placeholder="Description">
									</div>
								</div>
								
								<div class="form-group">
									<label for="input-text" class="col-sm-2 control-label">Other Deduction2</label>
									<div class="col-sm-5">
										<input type="text" class="form-control allw-ded" id="oth_deduction2" autocomplete="off" name="oth_deduction2" value="{{$wagerow->oth_deduction2}}">
									</div>
									<div class="col-sm-5">
										<input type="text" class="form-control" id="desc_deduction2" autocomplete="off" name="desc_deduction2" value="{{$wagerow->desc_deduction2}}" placeholder="Description">
									</div>
								</div>
								
								<div class="form-group">
									<label for="input-text" class="col-sm-2 control-label">Other Deduction3</label>
									<div class="col-sm-5">
										<input type="text" class="form-control allw-ded" id="oth_deduction3" autocomplete="off" name="oth_deduction3" value="{{$wagerow->oth_deduction3}}">
									</div>
									<div class="col-sm-5">
										<input type="text" class="form-control" id="desc_deduction3" autocomplete="off" name="desc_deduction3" value="{{$wagerow->desc_deduction3}}" placeholder="Description">
									</div>
								</div>
								
								<div class="form-group">
									<label for="input-text" class="col-sm-2 control-label">Other Deduction4</label>
									<div class="col-sm-5">
										<input type="text" class="form-control allw-ded" id="oth_deduction4" autocomplete="off" name="oth_deduction4" value="{{$wagerow->oth_deduction4}}">
									</div>
									<div class="col-sm-5">
										<input type="text" class="form-control" id="desc_deduction4" autocomplete="off" name="desc_deduction4" value="{{$wagerow->desc_deduction4}}" placeholder="Description">
									</div>
								</div>
							</div>
							
						</div>			
						<?php	if($wagerow->entry_type=="daily") {  ?>		
								<hr/>
								<table border="0" class="table-dy-row">
								    <tr>
								        <td></td>
								        <td></td>
								        <td></td>
								        <td></td>
								        <td></td>
								        <td></td>
								        <td><input type="hidden" id="total_wdays" step="any" name="total_wdays" value="{{$wagerow->wdays_total}}" class="form-control" readonly placeholder="0">
</td>
								        <td width="8%">
						<span class="small">OTHr Total(G)</span> 	<input type="number" id="otgs_tot" step="any" name="otgs_tot" value="{{$wagerow->otgs_total}}" class="form-control" readonly placeholder="0">
					</td>
								        <td width="8%">
						<span class="small">OTHr Total(H)</span> 	<input type="number" id="oths_tot" step="any" name="oths_tot" class="form-control" value="{{$wagerow->oths_total}}" readonly placeholder="0">
					</td>
								        <td></td>
								        <td></td>
								        
								    </tr>
								    </table>
								<?php    } ?>
								<hr/>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Basic</label>
                                    <div class="col-xs-10">
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-4">
										<input type="number" id="basic_net" step="any" name="basic_net" class="form-control" readonly value="{{$wagerow->net_basic}}">
										<input type="hidden" id="bas_net" step="any" name="bas_net" class="form-control"  value="{{$employee->basic_pay}}">
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
										<input type="number" id="hra_net" step="any" name="hra_net" class="form-control" readonly value="{{$wagerow->net_hra}}">
										<input type="hidden" id="hraa_net" step="any" name="hraa_net" class="form-control"  value="{{$employee->hra}}">
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
										<input type="number" id="allowance_net" step="any" name="allowance_net" class="form-control" readonly value="{{$wagerow->net_allowance}}">
										<input type="hidden" id="allow_net" step="any" name="allow_net" class="form-control" readonly value="{{($employee->transport + $employee->allowance + $employee->allowance2)}}">
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
										<input type="number" id="otg_net" step="any" name="otg_net" class="form-control" readonly value="{{$wagerow->net_otg}}">
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
										<input type="number" id="oth_net" step="any" name="oth_net" class="form-control" readonly value="{{$wagerow->net_oth}}">
										</div>
									</div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Other Allowance</label>
                                    <div class="col-xs-10">
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-4">
										<input type="number" id="othr_allowance" step="any" name="othr_allowance" value="<?php echo $wagerow->oth_allowance1 +$wagerow->oth_allowance2+$wagerow->oth_allowance3+$wagerow->oth_allowance4 ?>" class="form-control" readonly placeholder="0">
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
										<input type="number" id="deductions" step="any" name="deductions" class="form-control" readonly value="{{$wagerow->deductions}}">
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
										<input type="number" id="net_total" step="any" name="net_total" class="form-control" readonly value="{{$wagerow->net_total}}">
										</div>
									</div>
                                </div>
								
								<br/>
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                         <a href="{{ url('wage_entry') }}" class="btn btn-danger">Cancel</a>
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
var nwh = parseFloat({{$employee->nwh}}); 
var nwhr = parseFloat({{$employee->nwh}});
var wg = parseFloat({{$wge}});
var ot_hr_sal = parseFloat(<?php echo $wph;?>);//console.log(ot_hr_sal);
var al = parseFloat({{number_format($alwnc_per_day,3)}});
var altrans = parseFloat({{number_format($alwnctrans_per_day,3)}});
var alhra = parseFloat({{number_format($alwnchra_per_day,3)}});
var itl = parseFloat(nwhr * wg);
var otper = parseFloat({{$parameter->ot_general}});
var othper = parseFloat({{$parameter->ot_holiday}});
var alhr = parseFloat({{$alwnc_per_hr}});
$('.infodivLevItm').toggle();
$(document).ready(function () {
	
	$('#empinfo').toggle();
	$(document).on('click', '.more-info', function(e)  { 
	
		if( $('#empinfo').is(":hidden") )
			$('#empinfo').toggle();
		else if( $('#empinfo').is(":visible") )
			$('#empinfo').toggle();
	});
	
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
	var dayhr = nwh / nod;
	var nor_wage = wage * nwh; //console.log(wage);
	
	var otg = parseFloat( ($('#otg_'+n).val()=='') ? 0 : $('#otg_'+n).val() );
	var otg_tot = ot_hr_sal * otg * otper;
	
	var oth = parseFloat( ($('#oth_'+n).val()=='') ? 0 : $('#oth_'+n).val() );
	var oth_tot = ot_hr_sal * oth
	//var oth_tot = ot_hr_sal * oth* othper;
	
	var alw = parseFloat( ($('#alw_'+n).val()=='') ? 0 : $('#alw_'+n).val() );
	
	//leave...
	var uplev = parseFloat( ($('#uplevd_'+n).val()=='') ? 0 : $('#uplevd_'+n).val() );
	var uplevwg = wage * dayhr * uplev; console.log('lv'+uplevwg);
	
	var aln = alhr * nwh;
	
	var lineTotal = nor_wage + otg_tot + oth_tot;
	//console.log('alw'+aln);
	//$('#nwh_'+n).val(nwh);
	$('#itmttl_'+n).val(lineTotal.toFixed(3));
	$('#alw_'+n).val(aln.toFixed(3));
	$('#otgttl_'+n).val(otg_tot);
	$('#othttl_'+n).val(oth_tot);
	$('#deductions').val(uplevwg);
	$('#otgst_'+n).val(otg.toFixed(3));
	$('#othst_'+n).val(oth.toFixed(3));	
	
	return lineTotal;
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
	
	var wdays_net = 0;
	$( '.line-nod' ).each(function() { 
		wdays_net = wdays_net + parseFloat( (this.value=='')?0:this.value );
	});
	
	var otgst_net = 0;
	$( '.otgst' ).each(function() { 
		otgst_net = otgst_net + parseFloat( (this.value=='')?0:this.value );
	});
	
	var otg_net = 0;
	$( '.otgtotal' ).each(function() { 
		otg_net = otg_net + parseFloat( (this.value=='')?0:this.value );
	});
	
		var othst_net = 0;
	$( '.othst' ).each(function() { 
		othst_net = othst_net + parseFloat( (this.value=='')?0:this.value );
	});
	
	var oth_net = 0;
	$( '.othtotal' ).each(function() {
		oth_net = oth_net + parseFloat((this.value=='')?0:this.value);
	});
	
	var uplev = 0;
	$( '.line-uplevd' ).each(function() { 
		uplev = this.value * wg * nwhr;
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
	//console.log(lev);
	var now_net=wdays_net-lev;
	$('#otg_net').val(otg_net.toFixed(3));
	$('#oth_net').val(oth_net.toFixed(3));
	$('#otgs_tot').val(otgst_net.toFixed(3));
	$('#oths_tot').val(othst_net.toFixed(3));
	$('#total_wdays').val(now_net.toFixed(3));
	//$('#basic_net').val(Math.round(wage_total)); //console.log(wage_total.toFixed(3));
	
	
	var ded = (lev * itl) + (lev * al) + hd_de;//(basic_net > wage_total)?basic_net - wage_total: 0;
//	var ded = (lev * al)+ hd_de;
	var alt =lev*altrans;
	var alh=lev*alhra;
		var bnet=lev * itl;
		
		var basic_net = parseFloat( ($('#basic_net').val()=='') ? 0 : $('#basic_net').val() );
		
	//var basic_nett = parseFloat( ($('#basic_net').val()=='') ? 0 : $('#bas_net').val());
//	var basic_net=parseFloat(basic_nett-bnet);
//	$('#basic_net').val(basic_net.toFixed(3));
	//$('#hra_net').val(ehra.toFixed(3));
	var hra_net = parseFloat( ($('#hra_net').val()=='') ? 0 : $('#hra_net').val() );
	//hra_net / div
	//var hra_nett = parseFloat( ($('#hra_net').val()=='') ? 0 : $('#hraa_net').val());
	//var hra_net=parseFloat(hra_nett-alh);
	//$('#allowance_net').val(ealw.toFixed(3));
	var allowance_net = parseFloat( ($('#allowance_net').val()=='') ? 0 : $('#allowance_net').val() );
	//var allowance_nett = parseFloat( ($('#allowance_net').val()=='') ? 0 : $('#allow_net').val() );
	//var allowance_net= parseFloat(allowance_nett -alt);
//	$('#hra_net').val(hra_net.toFixed(3));
//	$('#allowance_net').val(allowance_net.toFixed(3));
	var deduction = uplev;//parseFloat( ($('#deductions').val()=='') ? 0 : $('#deductions').val() );
	deduction = parseFloat(deduction + ded);
	//$('#deductions').val(deduction.toFixed(3));
	
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
	$('#othr_allowance').val((oth_allw).toFixed(3));
	$('#deductions').val( (deduction + oth_ded).toFixed(3));
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
	   });
	   
	   $.get("{{ url('employee/get_empdata/') }}/" + $(this).attr("data-id"), function(data) { //console.log(data);
		   var alw = parseFloat(data.transport) + parseFloat(data.allowance);
		   $('#basic_net').val(data.basic);
		   $('#hra_net').val(data.hra);
		   $('#allowance_net').val(alw);
		   $('#net_total').val(data.net_total);
		   
		  /*  var totnwh = parseFloat($('#wage_1').val()) * parseFloat($('#nodays_1').val());
			$('#nwh_1').val(totnwh);
			$('#alw_1').val(alw + data.hra);
			$('#itmttl_1').val(data.basic); */// + alw + data.hra
	   });
	   
	   
	   
	});
	
	$(document).on('click', '.jobRow', function(e) {
		var num = $('#num').val(); 
		$('#jobcod_'+num).val( $(this).attr("data-code") );
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
		var curNum = res[1]; console.log(curNum);
		
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
	
	$(document).on('blur', '.line-uplevd', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		
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
	
	var joburl = "{{ url('jobmaster/job_data/') }}";
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
