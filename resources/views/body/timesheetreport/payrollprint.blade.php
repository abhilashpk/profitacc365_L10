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
	<?php if(count($witems) > 0) { ?>

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

		
		<?php	if($employee->nwage==30) {
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
		?>				
	
    <table border="0" style="width:100%;">

								<tr><td align="left">Employee No:<b>{{$employee->code}}</b></td>
									<td align="right">Month:<b><?php echo date('F', mktime(0, 0, 0, $month, 10));?></b></td>
                                    
								</tr>
								<tr>
                                    <td align="left">Employee Name:<b>{{$employee->name}}</b></td>
									
                                    <td align="right">Year:<b>{{$wageentry->year}}</b></td>
								</tr>
                                <tr>
                                <td align="left">Profession:<b>{{$employee->designation}}</b></td>
                                </tr>
	</table><br/>
		<table border="1" width="100%" class="pay">
			<tr >
               <th align="center" width="10%">Date</th>
				<th align="center"  width="10%">Job Code</th>
				<th align="center" width="4%">Normal Hours</th>
				<th align="center" width="4%">OT(G)</th>
				<th align="center" width="4%">OT(H)</th>
				<th align="center" width="6%">Cost/Hr</th>
                <th align="center" width="6%">OT(G)/Hr</th>
                <th align="center" width="6%">OT(H)/Hr</th>
				<th align="center" width="6%">Total</th>
                <th align="center" width="10%">Leave Status</th>
				
			</tr>
			<?php $i[]=0;$per_hr=$total=0; foreach($witems as $item) {  $i++;
			if($item->leave_status==2 &&  $item->leave_type=='2'){
                    $per_hr_sal=0;
                    }
                    else{
					$per_hr_sal = ($employee->nwage==365)?(($base_sal / $div) / $item->nwh * 12):($base_sal / $div) / $item->nwh;
                    }
				    $otg_tot=number_format($per_hr_sal,3)*$parameter->ot_general*$parameter->ot_general;
					$oth_tot=number_format($per_hr_sal,3)*$parameter->ot_holiday;

					if($employee->nwage==365)
						
						$total_wage=$total_wg+$otg_tot+$oth_tot;
					else
                    if($item->leave_status==2 &&  $item->leave_type=='2'){
                    $total_wage=0;}
						else{
                        $total_wage = ($employee->nwage==365)?(($base_sal / $div) * 12):($base_sal / $div);
                        }

					$ot_per_hr_sal = ($employee->otwage==365)?(($ot_base_sal / $divot) / $employee->nwh * 12): ($ot_base_sal / $divot) / $employee->nwh;
					
					
					$otg_per =$ot_per_hr_sal*$parameter->ot_general*$item->otg;
                    $oth_per =$ot_per_hr_sal*$parameter->ot_holiday*$item->oth;	

					$per_hr +=$per_hr_sal;
					$total +=$total_wage+$otg_per+$oth_per;
                    
			?>
			<tr style="height:50px;">
			   <td>{{date('d-m-Y',strtotime($item->job_date))}}</td>
				<td>{{$item->j2code}}</td>
				<td align="center">{{$item->nwh}}</td>
				<td align="center">{{$item->otg}}</td>
				<td align="center">{{$item->oth}}</td>
				<td align="center">{{number_format($per_hr_sal,3)}}</td>
                <td align="center">{{number_format($otg_per,3)}}</td> 
                <td align="center">{{number_format($oth_per,3)}}</td> 
				<td align="center">{{number_format($total_wage+$otg_per+$oth_per,3)}}</td> 
                <td><?php if($item->leave_status==0)echo 'Present';
                      else if($item->leave_status==2)echo'Absent' ;
                      else if($item->leave_status==1)echo'Holiday'; 
               ?></td>
			</tr>
			<?php } ?>

			<tr>
            
		<td colspan="4"><b></b></td>
		<td ><b>No: of Days Worked:{{$wageentry->wdays_total}}</b></td>
		
		
		<td colspan="5" ><b></b></td>
			</tr>
		</table>

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
								<th class="text-right" style="height:20px;padding:8px;"><strong>Other Allowance:</strong></th>
								<th class="text-right" style="height:20px;padding:8px;"><strong>{{number_format($result->net_allowance,2)}} &nbsp;  </strong></th>
								<th class="text-right" style="height:20px;padding:8px;"><strong>Loan:</strong></th>
								<th class="text-right" style="height:20px;padding:8px;"><strong>{{number_format($result->loan,2)}} &nbsp;  </strong></th>
							</tr>
							<tr>
								<th class="text-right" style="height:20px;padding:8px;"><strong>OT Payment:</strong></th>
								<th class="text-right" style="height:20px;padding:8px;"><strong>{{number_format($result->net_otg,2)}} &nbsp; <!--$allowances-->
									</strong></th>
								<th class="text-right" style="height:20px;padding:8px;"><strong>Other Deductions:</strong></th>
								<th class="text-right" style="height:20px;padding:8px;"><strong> &nbsp;  </strong></th><!-- {{number_format($deductions,2)}} -->
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
								<th class="text-right" style="height:20px;padding:8px;"><strong>Special OT Payment:</strong></th>
								<th class="text-right" style="height:20px;padding:8px;"><strong>{{number_format($result->net_oth,2)}} &nbsp;  </strong></th>
								<th class="text-right" style="height:20px;padding:8px;"><strong></strong></th>
								<th class="text-right" style="height:20px;padding:8px;"><strong> &nbsp;  </strong></th>
							</tr>
							<?php $Ltotal = $result->net_basic + $result->net_hra + $allowances + $ots + $result->net_allowance; 
								  $Rtotal = $result->deductions + $result->loan; //+ $deductions;
							?>
							<tr>
								<th class="text-right" style="height:20px;padding:8px;"><strong>Total Salary:</strong></th>
								<th class="text-right" style="height:20px;padding:8px;"><strong>{{number_format($Ltotal,2)}} &nbsp;  </strong></th>
								<th class="text-right" style="height:20px;padding:8px;"><strong>Total Deduction:</strong></th>
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
				
					<table width="100%">
						<tr>
							<th style="height:100px;padding:8px;"><strong>Accounts Assistant</strong></th>
							<th style="height:100px;padding:8px;"><strong>Accountant</strong></th>
							<th class="text-center"><strong>M.D.'s Approval</strong></th>
							<th class="text-right"><strong>Employee's Signature </strong></th>
						</tr>
					</table>
		
		<?php } else { ?>
			<div class="alert alert-warning">
				<p>No records were found!</p>
			</div>
		<?php } ?>
		
	
		
		
		<br/><br/><br/>		
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
                    

