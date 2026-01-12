<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>
        @section('title')
            Profit ACC 365 | ERP Software
        @show
    </title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link rel="shortcut icon" href="{{asset('assets/img/favicon.ico')}}"/>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    <!-- global css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/app.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
   <!-- <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/invoice.css')}}">-->
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/bootstrap.css')}}">
@yield('header_styles')

<style>
#invoicing {
	font-size:8pt;
}

.tblstyle td,
  .tblstyle th {
    height:15px;
	padding:2px;
	border:1px solid #000 !important;
  }

/* @media print {
	html, body {
		
		height: 530px !important;        
	}
	.page {
		margin: 0;
		border: initial;
		border-radius: initial;
		width: initial;
		min-height: initial;
		box-shadow: initial;
		background: initial;
		page-break-after: always;
	}
} */
</style>
<style type="text/css" media="print">

/*body{ page-break-after: always !important; overflow: hidden !important; }*/

thead
{
	display: table-header-group;
}

#inv
{
	 display: table-footer-group;
	 /*position: fixed;*/
     bottom: 0;
	 margin: 0 auto 0 auto;
	 width:100%;
}

.t {
	 height:250px;
}

</style>
<!-- end of global css -->
</head>
<body >


<!-- For horizontal menu -->
@yield('horizontal_header')
<!-- horizontal menu ends -->
<table border="0" style="width:100%;">

								<tr><td width="100%" align="center"><h3>{{Session::get('company')}}</h3></td>
									<td align="left"></td>
								</tr>
								<tr>
									<td width="100%" align="center"><h4><u>{{$voucherhead}}</u></h4>
									</td><td align="left"></td>
								</tr>
							</table><br/>


		<?php if($type=='pay_slip') { ?>
			@if($employee)
			<table width="100%" border="1">
				<tr>
					<td height="35" class="text-right" style="padding:8px;" width="50%">Employee Code: </td><td style="padding:8px;">{{$employee->code}}</td>
				</tr>
				<tr>
					<td height="35" class="text-right" style="padding:8px;">Employee Name:</td><td style="padding:8px;">{{$employee->name}}</td>
				</tr>
				<tr>
					<td height="35" class="text-right" style="padding:8px;">Nationality: </td><td style="padding:8px;">{{$employee->nationality}}</td>
				</tr>
				<tr>
					<td height="35" class="text-right" style="padding:8px;">Visa No: </td><td style="padding:8px;">{{$employee->v_id}}</td>
				</tr>
				<tr>
					<td height="35" class="text-right" style="padding:8px;">Designation: </td><td style="padding:8px;">{{$employee->designation}}</td>
				</tr>
				<tr>
					<td height="35" class="text-right" style="padding:8px;">Phone: </td><td style="padding:8px;">{{$employee->phone}}</td>
				</tr>
				<tr>
					<td height="35" class="text-right" style="padding:8px;">Month: </td><td style="padding:8px;"><?php echo date('F', mktime(0, 0, 0, $month, 10));?></td>
				</tr>
				</table><br/>
					<table width="100%" border="1">
						<thead>
							<tr><?php $deductions = $result->oth_deduction1+$result->oth_deduction2+$result->oth_deduction3+$result->oth_deduction4;
									  $allowances = $result->oth_allowance1+$result->oth_allowance2+$result->oth_allowance3+$result->oth_allowance4;
									  $ots = $result->net_otg + $result->net_oth;
							?>
								<td class="text-right" style="height:20px;padding:8px;"><strong>Basic Salary:</strong></td>
								<td class="text-right" style="height:20px;padding:8px;"><strong>{{number_format($employee->basic_pay,2)}} &nbsp; </strong></td>
								<td class="text-right" style="height:20px;padding:8px;"><strong>Deduction:</strong></td>
								<td class="text-right" style="height:20px;padding:8px;"><strong>{{number_format($result->deductions,2)}} &nbsp; </strong></td>
							</tr>
							<tr>
								<th class="text-right" style="height:20px;padding:8px;"><strong>Salary:</strong></th>
								<th class="text-right" style="height:20px;padding:8px;"><strong>{{number_format($result->net_basic,2)}} &nbsp;  </strong></th>
								<th class="text-right" style="height:20px;padding:8px;"><strong>Advance:</strong></th>
								<th class="text-right" style="height:20px;padding:8px;"><strong>-- &nbsp;  </strong></th>
							</tr>
							<tr>
								<th class="text-right" style="height:20px;padding:8px;"><strong>HRA:</strong></th>
								<th class="text-right" style="height:20px;padding:8px;"><strong>{{number_format($result->net_hra,2)}} &nbsp;  </strong></th>
								<th class="text-right" style="height:20px;padding:8px;"><strong>Loan:</strong></th>
								<th class="text-right" style="height:20px;padding:8px;"><strong>{{number_format($result->loan,2)}} &nbsp;  </strong></th>
							</tr>
							<tr>
								<th class="text-right" style="height:20px;padding:8px;"><strong>Transpotation:</strong></th>
								<th class="text-right" style="height:20px;padding:8px;"><strong>{{number_format($employee->transport,2)}} &nbsp;  </strong></th>
								<th class="text-right" style="height:20px;padding:8px;"><strong>Loan:</strong></th>
								<th class="text-right" style="height:20px;padding:8px;"><strong>{{number_format($result->loan,2)}} &nbsp;  </strong></th>
							</tr>
							<tr>
								<th class="text-right" style="height:20px;padding:8px;"><strong>Allowance:</strong></th>
								<th class="text-right" style="height:20px;padding:8px;"><strong>{{number_format($allowances,2)}} &nbsp;  </strong></th>
								<th class="text-right" style="height:20px;padding:8px;"><strong>Other Deductions:</strong></th>
								<th class="text-right" style="height:20px;padding:8px;"><strong>{{number_format($deductions,2)}} &nbsp;  </strong></th>
							</tr>
							<?php if(($result->oth_allowance1 > 0) || ($result->oth_deduction1 > 0)) { ?>
							<tr>
								<th class="text-right" style="height:20px;padding:8px;"><strong>{{$result->desc_allowance1}}</strong></th>
								<th class="text-right" style="height:20px;padding:8px;"><strong>{{number_format($result->oth_allowance1,2)}} &nbsp;  </strong></th>
								<th class="text-right" style="height:20px;padding:8px;"><strong>{{$result->desc_deduction1}}:</strong></th>
								<th class="text-right" style="height:20px;padding:8px;"><strong>{{number_format($result->oth_deduction1,2)}} &nbsp;  </strong></th>
							</tr>
							<?php } ?>
							
							<?php if(($result->oth_allowance2 > 0) || ($result->oth_deduction2 > 0)) { ?>
							<tr>
								<th class="text-right" style="height:20px;padding:8px;"><strong>{{$result->desc_allowance2}}</strong></th>
								<th class="text-right" style="height:20px;padding:8px;"><strong>{{number_format($result->oth_allowance2,2)}} &nbsp;  </strong></th>
								<th class="text-right" style="height:20px;padding:8px;"><strong>{{$result->desc_deduction2}}:</strong></th>
								<th class="text-right" style="height:20px;padding:8px;"><strong>{{number_format($result->oth_deduction2,2)}} &nbsp;  </strong></th>
							</tr>
							<?php } ?>
							
							<?php if(($result->oth_allowance3 > 0) || ($result->oth_deduction3 > 0)) { ?>
							<tr>
								<th class="text-right" style="height:20px;padding:8px;"><strong>{{$result->desc_allowance3}}</strong></th>
								<th class="text-right" style="height:20px;padding:8px;"><strong>{{number_format($result->oth_allowance3,2)}} &nbsp;  </strong></th>
								<th class="text-right" style="height:20px;padding:8px;"><strong>{{$result->desc_deduction3}}:</strong></th>
								<th class="text-right" style="height:20px;padding:8px;"><strong>{{number_format($result->oth_deduction3,2)}} &nbsp;  </strong></th>
							</tr>
							<?php } ?>
							
							<?php if(($result->oth_allowance4 > 0) || ($result->oth_deduction4 > 0)) { ?>
							<tr>
								<th class="text-right" style="height:20px;padding:8px;"><strong>{{$result->desc_allowance4}}</strong></th>
								<th class="text-right" style="height:20px;padding:8px;"><strong>{{number_format($result->oth_allowance4,2)}} &nbsp;  </strong></th>
								<th class="text-right" style="height:20px;padding:8px;"><strong>{{$result->desc_deduction4}}:</strong></th>
								<th class="text-right" style="height:20px;padding:8px;"><strong>{{number_format($result->oth_deduction4,2)}} &nbsp;  </strong></th>
							</tr>
							<?php } ?>
							<tr>
								<th class="text-right" style="height:20px;padding:8px;"><strong>OT Payment:</strong></th>
								<th class="text-right" style="height:20px;padding:8px;"><strong>{{number_format($ots,2)}} &nbsp;  </strong></th>
								<th class="text-right" style="height:20px;padding:8px;"><strong></strong></th>
								<th class="text-right" style="height:20px;padding:8px;"><strong> &nbsp;  </strong></th>
							</tr>
							<?php $Ltotal = $result->net_basic + $result->net_hra + $allowances + $ots + $employee->transport; 
								  $Rtotal = $result->deductions + $result->loan + $deductions;
							?>
							<tr>
								<th class="text-right" style="height:20px;padding:8px;"><strong>Total:</strong></th>
								<th class="text-right" style="height:20px;padding:8px;"><strong>{{number_format($Ltotal,2)}} &nbsp;  </strong></th>
								<th class="text-right" style="height:20px;padding:8px;"><strong>Total:</strong></th>
								<th class="text-right" style="height:20px;padding:8px;"><strong>{{number_format($Rtotal,2)}} &nbsp;  </strong></th>
							</tr>
							<tr>
								<th class="text-right" style="height:20px;padding:8px;"><strong>Net Salary Paid:</strong></th>
								<th class="text-right" style="height:20px;padding:8px;"><strong>{{number_format($result->net_total,2)}} &nbsp;  </strong></th>
								<th class="text-right" style="height:20px;padding:8px;"><strong></strong></th>
								<th class="text-right" style="height:20px;padding:8px;"><strong>  </strong></th>
							</tr>
						</thead>
						
					</table><br/><br/><br/><br/>
					<table width="100%" >
							<tr>
								<th style="width:50px;height:20px;padding:8px;"><strong>Accountant</strong></th>
								<th class="text-right"><strong>Receiver Signature </strong></th>
							</tr>
						</table><br/><br/><br/><br/>
				@else
				<div class="alert alert-warning">
					<p>No records were found!</p>
				</div>
				@endif
	<?php } else if($type=='pay_slip_summery') { ?>
	
	<?php if(count($wages) > 0) { ?>
	<b>Month: <?php echo date('F', mktime(0, 0, 0, $wages['month'], 10));?></b>
		<table border="1" width="100%" class="pay">
			<tr>
				<th rowspan="2">Emp.Code</th>
				<th rowspan="2">Emp.Name</th>
				<th rowspan="2">Designation</th>
				<th colspan="6">Hours Worked and Wage</th>
				<th rowspan="2">Salary</th>
				<th rowspan="2">HRA</th>
				<th rowspan="2">Ttl.Allw.</th>
				<th rowspan="2">Adv.</th>
				<th rowspan="2">Loan</th>
				<th rowspan="2">Ttl.Ded.</th>
				<th rowspan="2">Net Salary</th>
				<th rowspan="2">Sign</th>
			</tr>
			<tr>
				<th>NHr</th>
				<th>Wage</th>
				<th>OT(G)</th>
				<th>Wage</th>
				<th>OT(H)</th>
				<th>Wage</th>
			</tr>
			<tr style="height:50px;">
			<?php 
					$tot_alwnc = $tot_ded = 0;
					if(count($weothers) > 0) {
						$tot_alwnc = $weothers->oth_allowance1 + $weothers->oth_allowance2 + $weothers->oth_allowance3 + $weothers->oth_allowance4;
						$tot_ded = $weothers->oth_deduction1 + $weothers->oth_deduction2 + $weothers->oth_deduction3 + $weothers->oth_deduction4;
					} 
				?>
				<td>{{$result->code}}</td>
				<td >{{$result->name}}</td>
				<td >{{$result->designation}}</td>
				<td>{{$wages['nhr']}}</td>
				<td>{{$wages['nhr_wg']}}</td>
				<td>{{$wages['otg_hr']}}</td>
				<td>{{$wages['otg_wg']}}</td>
				<td>{{$wages['oth_hr']}}</td>
				<td>{{$wages['oth_wg']}}</td>
				<td>{{number_format($wages['salary'],2)}}</td>
				<td>{{number_format($wages['hra'],2)}}</td>
				<td>{{$tot_alwnc}}</td>
				<td>--</td>
				<td>--</td>
				<td>{{$tot_ded}}</td>
				<td>{{number_format($wages['net_total'],2)}}</td>
				<td></td>
			</tr>
			</table>
				<br/><br/><br/><br/>
				<table width="100%" >
						<tr>
							<th align="center"><br/><br/><strong>Accountant</strong></th>
							<th align="center"><br/><br/><strong>Receiver Signature </strong></th>
						</tr>
					</table>
			<?php } else { ?>
				<div class="alert alert-warning">
					<p>No records were found!</p>
				</div>
			<?php } ?>
			
	<?php } else if($type=='jobwise_summery') { ?>
		<?php if(count($result) > 0) { ?>
			<table border="1" width="100%" class="pay">
			<tr>
				<th rowspan="2">SI#.</th>
				<th rowspan="2">Job Code</th>
				<th rowspan="2">Job Name</th>
				<th colspan="2">Normal</th>
				<th colspan="2">OT General</th>
				<th colspan="2">OT Holiday</th>
				<th colspan="2">Bonus</th>
				<th colspan="2">Allowance</th>
				<th rowspan="2">Total Amount</th>
			</tr>
			<tr>
				<th>Hour</th>
				<th>Wage</th>
				<th>Hour</th>
				<th>Wage</th>
				<th>Hour</th>
				<th>Wage</th>
				<th>Hour</th>
				<th>Wage</th>
				<th>HRA</th>
				<th>Others</th>
			</tr>
			<?php $i=0; foreach($result as $result) { $i++; ?>
			<tr style="height:50px;">
				<td>{{$i}}</td>
				<td>{{$result['job_code']}}</td>
				<td >{{$result['job_name']}}</td>
				<td>{{$result['job_hr']}}</td>
				<td>{{$result['job_wg']}}</td>
				<td>{{$result['job_ot_hr']}}</td>
				<td>{{$result['job_ot_wg']}}</td>
				<td>{{$result['job_oth_hr']}}</td>
				<td>{{$result['job_oth_wg']}}</td>
				<td>--</td>
				<td>--</td>
				<td></td>
				<td>{{$result['job_allw']}}</td>
				<td>{{number_format($result['total'],2)}}</td>
			</tr>
			<?php } ?>
			</table>
			<br/><br/><br/><br/>
			<?php } else { ?>
				<div class="alert alert-warning">
					<p>No records were found!</p>
				</div>
			<?php } ?>
			
	<?php } else if($type=='payroll_summery') { ?>
	<?php if(count($result) > 0) { ?>
	<b>Month: <?php echo date('F', mktime(0, 0, 0, $result[0]->month, 10));?></b><br/><br/>
		<table border="1" width="100%" class="pay">
			<tr style="height:50px;">
				<th >SI#.</th>
				<th >Emp.Code</th>
				<th >Emp.Name</th>
				<th >Basic</th>
				<th >OT(G)</th>
				<th >OT(H)</th>
				<th >HRA</th>
				<th >Allowances</th>
				<th >Otr.Allowances</th>
				<th >Deductions</th>
				<th >Otr.Deductions</th>
				<th >Net Salary</th>
			</tr>
			<?php $i=0; foreach($result as $result) { $i++; 
				$net_allw = $result->oth_allowance1+$result->oth_allowance2+$result->oth_allowance3+$result->oth_allowance4;
				$net_ded = $result->oth_deduction1+$result->oth_deduction2+$result->oth_deduction3+$result->oth_deduction4;
			?>
			<tr style="height:50px;">
				<td>{{$i}}</td>
				<td>{{$result->code}}</td>
				<td>{{$result->name}}</td>
				<td>{{number_format($result->net_basic,2)}}</td>
				<td>{{number_format($result->net_otg,2)}}</td>
				<td>{{number_format($result->net_oth,2)}}</td>
				<td>{{number_format($result->net_hra,2)}}</td>
				<td>{{number_format($result->net_allowance,2)}}</td>
				<td>{{number_format($net_allw,2)}}</td>
				<td>{{number_format($result->deductions,2)}}</td>
				<td>{{number_format($net_ded,2)}}</td>
				<td>{{number_format($result->net_total,2)}}</td>
			</tr>
			<?php } ?>
		</table>
		<br/><br/>
		<table width="100%" >
				<tr>
					<th align="center"><br/><br/><strong>Prepared by:</strong></th>
					<th align="center"><br/><br/><strong>Approved by: </strong></th>
				</tr>
			</table>
			<br/><br/>
		<?php } else { ?>
			<div class="alert alert-warning">
				<p>No records were found!</p>
			</div>
		<?php } ?>
		
	<?php } else if($type=='attendance') { ?>
		<?php if(count($result) > 0) { ?>
		<b>Month: <?php $curr = current($result); echo date('F', mktime(0, 0, 0, $curr[0]->month, 10)); $mth = cal_days_in_month(CAL_GREGORIAN, $curr[0]->month, $curr[0]->year);?></b><br/><br/>
		<table border="1" width="100%" class="pay">
			<tr style="height:50px;">
				<th>Emp.Code</th>
				<th>Emp.Name</th>
				<th>Duty</th>
				<?php for($i=1;$i<=$mth;$i++) { ?>
				<th>{{$i}}</th>
				<?php } ?>
			</tr>
			<?php foreach($result as $result) { ?>
				<tr style="height:25px;">
				<th rowspan="2">{{$result[0]->code}}</th>
				<th rowspan="2">{{$result[0]->name}}</th>
				<th>Duty</th>
				<?php for($i=1;$i<=$mth;$i++) { ?>
				<th><?php if($result[$i-1]->leave_status==0) echo 'P'; else if($result[$i-1]->leave_status==1) echo 'O'; else if($result[$i-1]->leave_status==2 || $result[$i-1]->leave_status==3) echo 'A'; else if($result[$i-1]->leave_status==4) echo 'H/D';?></th>
				<?php } ?>
			</tr>
			<tr style="height:25px;">
				<th>OT</th>
				<?php for($i=1;$i<=$mth;$i++) { ?>
				<th>{{$result[$i-1]->otg}}</th>
				<?php } ?>
			</tr>
			<?php } ?>
		</table>
		<?php } else { ?>
			<div class="alert alert-warning">
				<p>No records were found!</p>
			</div>
		<?php } ?>
	<?php } else if($type=='jobwise') { ?>
	<?php if(count($result) > 0) { ?>
	<b>Job Code: {{$result[0]->job_code}} Job Name: {{$result[0]->job_name}}</b><br/><br/>
		<table border="1" width="100%" class="pay">
			<tr style="height:25px;">
				<th>Date</th>
				<th>Emp.Code</th>
				<th>NWHr</th>
				<th>OTHr</th>
				<th>NW Wage</th>
				<th>OT Wage</th>
				<th>Total</th>
			</tr>
			<?php 
			$otwg = $nwg = $netnwg = $netotwg = $net_total = 0;
			foreach($result as $result) {
				if($result->is_salary_job==0)				
					$nwg = $result->hour * $result->wage;
				if($result->is_salary_job==1 || $result->is_salary_job==21)	
					$otwg = $result->hour * $result->wage;
				
				$total = $nwg + $otwg;
				$netnwg += $nwg;
				$netotwg += $otwg;
				$net_total += $total;
			?>
			<tr style="height:25px;">
				<td><?php $dr = explode(' ',$result->day); echo $dr[0].'/'.$result->month.'/'.$result->year;?></td>
				<td>{{$result->code}}</td>
				<td>{{($result->is_salary_job==0)?$result->hour:0}}</td>
				<td>{{($result->is_salary_job==1 || $result->is_salary_job==2)?$result->hour:0}}</td>
				<td>{{number_format($nwg,2)}}</td>
				<td>{{number_format($otwg,2)}}</td>
				<td>{{number_format($total,2)}}</td>
			</tr>
			<?php } ?>
			<tr style="height:25px;">
				<td colspan="4"><b>Total</b></td>
				<td>{{number_format($netnwg,2)}}</td>
				<td>{{number_format($netotwg,2)}}</td>
				<td>{{number_format($net_total,2)}}</td>
			</tr>
		</table>
		<?php } else { ?>
			<div class="alert alert-warning">
				<p>No records were found!</p>
			</div>
		<?php } ?>
	
	<?php } ?>
				
	<div class="btn-section">
			<div class="col-md-12 col-sm-12 col-xs-12">
			
					<span class="pull-right">
								 <button type="button" onclick="javascript:window.print();" 
										 class="btn btn-responsive button-alignment btn-primary"
										 data-toggle="button">
									<span style="color:#fff;" >
										<i class="fa fa-fw fa-print"></i>
									Print
								</span>
					</button>
					
					<button type="button" onclick="javascript:window.close();"
										 class="btn btn-responsive button-alignment btn-primary"
										 data-toggle="button">
									<span style="color:#fff;" >
										<i class="fa fa-fw fa-times"></i>
									Close 
								</span>
					</button>
					</span>
			</div>
		</div>
                    

