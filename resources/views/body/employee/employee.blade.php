<div class="form-group">
	<label for="input-text" class="col-sm-2 control-label">Desigantion</label>
	<div class="col-sm-10">
		<input type="text" class="form-control" id="designation" value="{{$employee->designation}}" autocomplete="off" name="designation" readonly>
	</div>
</div>

<div class="form-group">
	<label for="input-text" class="col-sm-2 control-label">Department</label>
	<div class="col-sm-10">
		<input type="text" class="form-control" id="department" value="{{$employee->department}}" autocomplete="off" name="department" readonly>
	</div>
</div>


<fieldset>
	<legend><h5>Job Details</h5></legend>
	<?php if($count > 0) { ?>
		<div class="alert alert-warning">
			<p>Wages already created!</p>
		</div>
	<?php } else { ?>
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
			
			//$ehra = $employee->hra;
			//$alw =  $employee->transport + $employee->allowance + $employee->allowance2;
		?>
			
		<?php if($type=="daily") { ?>
			
				<?php 
					
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
					
					$mth = date('t', mktime(0, 0, 0, $month, 1, $year)); //cal_days_in_month(CAL_GREGORIAN, $month, $year);
					$per_hr_sal = ($employee->nwage==365)?(($base_sal / $div) / $employee->nwh * 12):($base_sal / $div) / $employee->nwh;
					$otg_tot=number_format($per_hr_sal,3)*$parameter->ot_general*$parameter->ot_general;
					$oth_tot=number_format($per_hr_sal,3)*$parameter->ot_holiday;
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
					$total_wg = number_format($per_hr_sal,3) * $employee->nwh;
					if($employee->nwage==365)
						
						$total_wage=$total_wg+$otg_tot+$oth_tot;
					else
						$total_wage = ($employee->nwage==365)?(($base_sal / $div) * 12):($base_sal / $div);
					
					
					$ot_per_hr_sal = ($employee->otwage==365)?(($ot_base_sal / $divot) / $employee->nwh * 12): ($ot_base_sal / $divot) / $employee->nwh;
					$oth_per_hr_sal = 0;
					
					for($i=1;$i<=$mth;$i++) { 
						$timestamp = strtotime($year.'-'.$month.'-'.$i);
						$d = date('D', $timestamp);
						$nwh = $employee->nwh;
						$holiday = $parameter->holiday;
						$wge = $per_hr_sal;
				?>
				<table border="0" class="table-dy-row">
				<tr>
					<td width="10%">
						<span class="small">Day</span><input type="text" value="{{$i.' '.$d}}" name="day[]"  id="day_{{$i}}" step="any" autocomplete="off" class="form-control" readonly>
						<input type="hidden" name="job_date[]" id="jobdate_{{$i}}" value="{{$year.'-'.$month.'-'.$i}}">
					</td>
					<td width="15%">
						<span class="small">Job Code</span>
						<input type="hidden" name="job_id[]" id="jobid_{{$i}}" value="{{$job->id}}">
						<input type="hidden" name="job_data[]" id="jobdata_{{$i}}">
						<input type="text" id="jobcod_{{$i}}" name="job_code[]" class="form-control" autocomplete="off" value="{{($holiday==$d)?'':$job->code.'-'.$job->name}}" autocomplete="off" data-toggle="modal" data-target="#job_modal" placeholder="Job Code">
					</td>
					<td width="6%">
						<span class="small">Status</span>
						<select id="leave_{{$i}}" name="leave[]" class="form-control leave">
							<option value="0">P</option><option value="1">Off</option><option value="2">A</option><option value="3">R</option><option value="4">H/D</option>
						</select>
					</td>
					<td width="6%">
						<span class="small">Lv.Type</span>
						<select id="pstatus_{{$i}}" name="pstatus[]" class="form-control pstatus">
							<option value=""></option><option value="2">U</option><option value="1">P</option>
						</select>
					</td>
					<td width="10%">
						<span class="small">Wage/Hr.</span><input type="number" value="{{number_format($per_hr_sal,3)}}" name="wage[]"  id="wage_{{$i}}" step="any" autocomplete="off" class="form-control wph" readonly>
					</td>
					<td width="8%">
						<span class="small">Days</span><input type="number" id="nodays_{{$i}}" step="any" value="1" name="nodays[]" autocomplete="off" class="form-control line-nod" readonly>
					</td>
					<td width="8%">
						<span class="small">NWH</span> <input type="number" id="nwh_{{$i}}" step="any" name="nwh[]" autocomplete="off" class="form-control line-nwh" value="<?php echo $nwh;?>" readonly>
					</td>
					<td width="8%">
						<span class="small">OTHr(G)</span> <input type="number" id="otg_{{$i}}" step="any" name="otg[]" autocomplete="off" class="form-control line-otg" >
						<input type="hidden" value="{{number_format($ot_per_hr_sal,3)}}" name="otg_wage[]"  id="otgwage_{{$i}}">
							<input type="hidden" id="otgst_{{$i}}" name="otgst[]" class="otgst">
					</td>
					<td width="8%">
						<span class="small">OTHr(H)</span><input type="number" id="oth_{{$i}}" step="any" name="oth[]" autocomplete="off" class="form-control line-oth" >
						<input type="hidden" value="{{number_format($oth_per_hr_sal,3)}}" name="oth_wage[]"  id="othwage_{{$i}}">
						<input type="hidden" id="othst_{{$i}}" name="othst[]" class="othst">
					</td>
					<td width="12%">
						<span class="small">Total Wage</span> 
						<input type="number" id="itmttl_{{$i}}" step="any" name="line_total[]" value="{{number_format($total_wage,3)}}" class="form-control line-total" readonly>
						<input type="hidden" id="otgttl_{{$i}}" name="otg_total[]" class="otgtotal" > 
						<input type="hidden" id="othttl_{{$i}}" name="oth_total[]" class="othtotal">
					</td>
					<td width="12%">
						<span class="small">Allowance</span><input type="number" id="alw_{{$i}}" step="any" name="alw[]" autocomplete="off" value="{{number_format($alwnc_per_day,3)}}" class="form-control line-alwnc" readonly>
				                                             <input type="hidden" id="alwtrans_{{$i}}" name="alwtrans[]" value="{{number_format($alwnctrans_per_day,3)}}" class="alwtrans"> 
				                                             <input type="hidden" id="alwhra_{{$i}}" name="alwhra[]"  value="{{number_format($alwnchra_per_day,3)}}" class="alwhra">
					</td>
					<!--<td width="6%" valign="middle">
						<i class="fa fa-fw fa-info-circle"></i>
					</td>-->
				</tr>
				</table>
				<div class="infodivLevItm" id="infodivLevItm_{{$i}}">
					<div class="col-xs-6">							
						<input type="text" id="levrsn_{{$i}}" name="leave_reason[]" class="form-control" placeholder="Leave Reason">
					</div>
				</div>
				<?php } ?>
		
		<?php } ?>
		</div>
	</div>
	<?php } ?>
</fieldset>

<div id="moreinfo">
	<button type="button" id="moreinfoItm_1" class="btn btn-primary btn-xs more-info">More Info</button>
</div>
<div id="empinfo">
	<!--<div class="form-group">
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
	-->
	<div class="form-group">
		<label for="input-text" class="col-sm-2 control-label">Other Allowance1</label>
		<div class="col-sm-5">
			<input type="number" step="any" class="form-control allw-ded" id="oth_allowance1" autocomplete="off" name="oth_allowance1">
		</div>
		<div class="col-sm-5">
			<input type="text" class="form-control" id="desc_allowance1" autocomplete="off" name="desc_allowance1" placeholder="Description">
		</div>
	</div>

	<div class="form-group">
		<label for="input-text" class="col-sm-2 control-label">Other Allowance2</label>
		<div class="col-sm-5">
			<input type="number" step="any" class="form-control allw-ded" id="oth_allowance2" autocomplete="off" name="oth_allowance2">
		</div>
		<div class="col-sm-5">
			<input type="text" class="form-control" id="desc_allowance2" autocomplete="off" name="desc_allowance2" placeholder="Description">
		</div>
	</div>
	
	<div class="form-group">
		<label for="input-text" class="col-sm-2 control-label">Other Allowance3</label>
		<div class="col-sm-5">
			<input type="number" step="any" class="form-control allw-ded" id="oth_allowance3" autocomplete="off" name="oth_allowance3">
		</div>
		<div class="col-sm-5">
			<input type="text" class="form-control" id="desc_allowance3" autocomplete="off" name="desc_allowance3" placeholder="Description">
		</div>
	</div>
	
	<div class="form-group">
		<label for="input-text" class="col-sm-2 control-label">Other Allowance4</label>
		<div class="col-sm-5">
			<input type="number" step="any" class="form-control allw-ded" id="oth_allowance4" autocomplete="off" name="oth_allowance4">
		</div>
		<div class="col-sm-5">
			<input type="text" class="form-control" id="desc_allowance4" autocomplete="off" name="desc_allowance4" placeholder="Description">
		</div>
	</div>
	<hr/>
	<div class="form-group">
		<label for="input-text" class="col-sm-2 control-label">Other Deduction1</label>
		<div class="col-sm-5">
			<input type="number" step="any" class="form-control allw-ded" id="oth_deduction1" autocomplete="off" name="oth_deduction1">
		</div>
		<div class="col-sm-5">
			<input type="text" class="form-control" id="desc_deduction1" autocomplete="off" name="desc_deduction1" placeholder="Description">
		</div>
	</div>
	
	<div class="form-group">
		<label for="input-text" class="col-sm-2 control-label">Other Deduction2</label>
		<div class="col-sm-5">
			<input type="number" step="any" class="form-control allw-ded" id="oth_deduction2" autocomplete="off" name="oth_deduction2">
		</div>
		<div class="col-sm-5">
			<input type="text" class="form-control" id="desc_deduction2" autocomplete="off" name="desc_deduction2" placeholder="Description">
		</div>
	</div>
	
	<div class="form-group">
		<label for="input-text" class="col-sm-2 control-label">Other Deduction3</label>
		<div class="col-sm-5">
			<input type="number" step="any" class="form-control allw-ded" id="oth_deduction3" autocomplete="off" name="oth_deduction3">
		</div>
		<div class="col-sm-5">
			<input type="text" class="form-control" id="desc_deduction3" autocomplete="off" name="desc_deduction3" placeholder="Description">
		</div>
	</div>
	
	<div class="form-group">
		<label for="input-text" class="col-sm-2 control-label">Other Deduction4</label>
		<div class="col-sm-5">
			<input type="number" step="any" class="form-control allw-ded" id="oth_deduction4" autocomplete="off" name="oth_deduction4">
		</div>
		<div class="col-sm-5">
			<input type="text" class="form-control" id="desc_deduction4" autocomplete="off" name="desc_deduction4" placeholder="Description">
		</div>
	</div>
	<!--<div class="form-group">
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

	<div class="form-group">
		<label for="input-text" class="col-sm-2 control-label">Loan</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" id="loan" autocomplete="off" name="loan" placeholder="Loan">
		</div>
	</div>-->
</div>

<script>
var itl;
$(':input[type=number]').on('mousewheel',function(e){ $(this).blur(); });
<?php if($count == 0) { ?>
	var ot_hr_sal = {{number_format($ot_per_hr_sal,3)}};
	var nwh = parseFloat({{$nwh}}); 
	var wg = parseFloat({{$per_hr_sal}});
	var otper = parseFloat({{$parameter->ot_general}});
	var alhr = parseFloat({{$alwnc_per_hr}});
	<?php if($type=="daily") { ?>
	var al = parseFloat({{number_format($alwnc_per_day,3)}});
	var altrans = parseFloat({{number_format($alwnctrans_per_day,3)}});
	var alhra = parseFloat({{number_format($alwnchra_per_day,3)}});
	itl = parseFloat({{number_format($total_wage,3)}});
	<?php } else { ?>
		var al = parseFloat({{number_format($allowance,3)}});
		itl = parseFloat({{number_format($total_wage,3)}});
	<?php } ?>
	var nwhr = parseFloat({{$employee->nwh}});
	 
	//var nwhr = parseFloat({{$parameter->nwh}});
	var basicpay = {{$employee->basic_pay}};
	
<?php } ?> 
$('#empinfo').toggle(); $('.infodivLevItm').toggle();
 $(document).on('click', '.more-info', function(e) { 
	
	if( $('#empinfo').is(":hidden") )
		$('#empinfo').toggle();
	else if( $('#empinfo').is(":visible") )
		$('#empinfo').toggle();
 });

 function getLineTotal(n) { 
     <?php if($count == 0) { ?>
var nwh = parseFloat({{$nwh}}); 
var wg = parseFloat({{$per_hr_sal}});
var otper = parseFloat({{$parameter->ot_general}});
var othper = parseFloat({{$parameter->ot_holiday}});
var nor_wage = wg * nwh; 

var ot_hr_sal = parseFloat( ($('#wage_'+n).val()=='') ? 0 : $('#wage_'+n).val() );
var otg_hr_sal = parseFloat( ($('#otgwage_'+n).val()=='') ? 0 : $('#otgwage_'+n).val() );
var otg = parseFloat( ($('#otg_'+n).val()=='') ? 0 : $('#otg_'+n).val() );
	var otg_tot = otg_hr_sal * otg * otper;
	var oth = parseFloat( ($('#oth_'+n).val()=='') ? 0 : $('#oth_'+n).val() );
	var oth_tot = otg_hr_sal * oth* othper;
	//var oth_tot = ot_hr_sal * oth* othper;

	var lineTotal = nor_wage + otg_tot + oth_tot;
	$('#itmttl_'+n).val(lineTotal.toFixed(3));
	$('#otgttl_'+n).val(otg_tot.toFixed(3));
	$('#othttl_'+n).val(oth_tot.toFixed(3));  
	$('#otgst_'+n).val(otg.toFixed(3));
	$('#othst_'+n).val(oth.toFixed(3));	
	<?php } ?> 
 }


 $(document).on('blur', '.line-otg', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; console.log(curNum);
		
		getLineTotal(curNum);
		getNetTotal();
	});
	
	$(document).on('blur', '.line-oth', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; //console.log(curNum);
		
		 getLineTotal(curNum);
		getNetTotal();
	});
</script>