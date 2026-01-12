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

		<?php if($type=='daily') { ?>
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
	<?php if(count($result) > 0) { ?>
	
<b>Date: <?php echo date('d-m-Y',strtotime($result[0]->date));?></b><br/><br/>
	<b>Employee Name: <?php echo $result[0]->name;?></b><br/><br/>
		<table border="1" width="100%" class="pay">
			<tr style="height:50px;">
				<th >Job Code</th>
				<th >Job Name</th>
				<th>S.Time</th>
				<th>E.Time</th>
				<th >OT(G)</th>
				<th >OT(H)</th>
				<th >NWH</th>
				<th >Cost/Hr</th>
				<th >Total</th>
				
			</tr>
			<?php  foreach($result as $result) {  
			if($result->leave_type==0 &&  $result->leave_status=='UP'){
                    $per_hr_sal=0;
                    }
                    
            	else if($result->leave_type==0 &&  $result->leave_status==''){
                    $per_hr_sal=0;
                    }        
                    else{
					$per_hr_sal = ($employee->nwage==365)?(($base_sal / $div) / $result->nwh * 12):($base_sal / $div) / $result->nwh;
                    }
				    $otg_tot=number_format($per_hr_sal,3)*$parameter->ot_general*$parameter->ot_general;
					$oth_tot=number_format($per_hr_sal,3)*$parameter->ot_holiday;

					if($employee->nwage==365)
						
						$total_wage=$total_wg+$otg_tot+$oth_tot;
					else{
					if($result->leave_type==0 &&  $result->leave_status=='UP'  ){
                    $total_wage=0;
                    }
                     else if($result->leave_type==0 && $result->leave_status=='' ){
                    $total_wage=0;
                    }
                    else{
						$total_wage = ($employee->nwage==365)?(($base_sal / $div) * 12):($base_sal / $div);
                    }
					}
					

					$ot_per_hr_sal = ($employee->otwage==365)?(($ot_base_sal / $divot) / $employee->nwh * 12): ($ot_base_sal / $divot) / $employee->nwh;
					
					
					$otg_per =$ot_per_hr_sal*$parameter->ot_general*$result->otg;
                    $oth_per =$ot_per_hr_sal*$parameter->ot_holiday*$result->oth;	
			?>
			<tr style="height:50px;">
			   <td>{{$result->job_code}}</td>
				<td>{{$result->job_name}}</td>
				<td>{{$result->start_time}}</td>
				<td>{{$result->end_time}}</td>
				<td>{{$result->otg}}</td>
				<td>{{$result->oth}}</td>
				<td>{{$result->nwh}}</td>
				<td>{{number_format($per_hr_sal,3)}}</td>
				<td>{{number_format($total_wage+$otg_per+$oth_per,3)}}</td> 
			</tr>
			<?php } ?>
		</table>
		
		<?php } else { ?>
			<div class="alert alert-warning">
				<p>No records were found!</p>
			</div>
		<?php } ?>
		
	
	<?php } else if($type=='monthly') { ?>
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
	<?php if(count($result) > 0) { ?>
	<b>Month: <?php echo date('F', mktime(0, 0, 0, $result[0]->month, 10));?></b><br/><br/>

	<b>Employee Name: <?php echo $result[0]->name;?></b><br/><br/>
		<table border="1" width="100%" class="pay">
			<tr style="height:50px;">
			    <th>Date</th>
				<th >Job Code</th>
				<th >Job Name</th>
				<th>S.Time</th>
				<th>E.Time</th>
				<th >OT(G)</th>
				<th >OT(H)</th>
				<th >NWH</th>
				<th >Cost/Hr</th>
				<th >Total</th>
				
			</tr>
			<?php $per_hr=$total=0; foreach($result as $result) {  
			if($result->leave_type==0 &&  $result->leave_status=='UP'  ){
                    $per_hr_sal=0;
                    }
             else if($result->leave_type==0 && $result->leave_status=='' ){
                    $per_hr_sal=0;
                    }
                    else{
					$per_hr_sal = ($employee->nwage==365)?(($base_sal / $div) / $result->nwh * 12):($base_sal / $div) / $result->nwh;
                    }
				    $otg_tot=number_format($per_hr_sal,3)*$parameter->ot_general*$parameter->ot_general;
					$oth_tot=number_format($per_hr_sal,3)*$parameter->ot_holiday;

			if($employee->nwage==365){
						$total_wage=$total_wg+$otg_tot+$oth_tot;
					}
				else{
					if($result->leave_type==0 &&  $result->leave_status=='UP'  ){
                    $total_wage=0;
                    }
                     else if($result->leave_type==0 && $result->leave_status=='' ){
                    $total_wage=0;
                    }
                    else{
						$total_wage = ($employee->nwage==365)?(($base_sal / $div) * 12):($base_sal / $div);
                    }
					}
					$ot_per_hr_sal = ($employee->otwage==365)?(($ot_base_sal / $divot) / $employee->nwh * 12): ($ot_base_sal / $divot) / $employee->nwh;
					
					
					$otg_per =$ot_per_hr_sal*$parameter->ot_general*$result->otg;
                    $oth_per =$ot_per_hr_sal*$parameter->ot_holiday*$result->oth;	
                  
					$per_hr +=$per_hr_sal;
					$total +=$total_wage+$otg_per+$oth_per;
			?>
			<tr style="height:50px;">
			    <td><?php echo date('d-m-Y',strtotime($result->date));?></td>
			   <td>{{$result->job_code}}</td>
				<td>{{$result->job_name}}</td>
				<td>{{$result->start_time}}</td>
				<td>{{$result->end_time}}</td>
				<td>{{$result->otg}}</td>
				<td>{{$result->oth}}</td>
				<td>{{$result->nwh}}</td>
				<td>{{number_format($per_hr_sal,3)}}</td>
				<td>{{number_format($total_wage+$otg_per+$oth_per,3)}}</td> 
			</tr>
			<?php } ?>

			<tr>
		<td colspan="7"><b></b></td>
		<td ><b>Grand Total:</b></td>
		<td>{{number_format($per_hr,3)}}</td>
		<td>{{number_format($total,3)}}</td>
		<td ><b></b></td>
			</tr>
		</table>
		
		<?php } else { ?>
			<div class="alert alert-warning">
				<p>No records were found!</p>
			</div>
		<?php } ?>
		
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
                    

