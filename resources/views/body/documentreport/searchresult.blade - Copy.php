@extends('printgeneral')
@section('contentnew')
<style>
 table, td, th {
            
           text-align:center;
         }
</style>
<div>
	
	<?php if(count($reports) > 0) { ?>
	<b>Date From: {{$fromdate}} To Date: {{$todate}}<br/> Document: <?php if($type=='passport') echo 'Passport'; else if($type=='visa') echo 'Visa'; else if($type=='labour') echo 'Labour Card';
	else if($type=='health') echo 'Health Card'; else if($type=='idcard') echo 'ID Card'; 
	?></b><br/><br/>
		<table border="1" width="100%" class="pay">
			<tr style="height:30px;">
				<th>Emp.Code</th>
				<th>Emp.Name</th>
				<th>Designation</th>
				<th>Address</th>
				<th>Document Details</th>
			</tr>
			<?php 
			foreach($reports as $result) {
			?>
			<tr style="height:100px;">
				<td>{{$result->code}}</td>
				<td>{{$result->name}}</td>
				<td>{{$result->designation}}</td>
				<td>{{$result->address1.', '.$result->address2.' '.$result->address3}}</td>
				<td><?php 
					if($type=='passport') {
						echo 'Passport ID: '.$result->pp_id.'<br/>';
						echo 'Issue Date: '.date('d-m-Y', strtotime($result->pp_issue_date)).'<br/>';
						echo '<b>Expiry Date: '.date('d-m-Y', strtotime($result->pp_expiry_date)).'</b><br/>';
						echo 'Issue Place: '.$result->pp_issue_place;
						
					} else if($type=='visa') {
						echo 'Vise ID: '.$result->v_id.'<br/>';
						echo 'Designation: '.$result->v_designation.'<br/>';
						echo 'Join Date: '.date('d-m-Y', strtotime($result->join_date)).'<br/>';
						echo 'Issue Date: '.date('d-m-Y', strtotime($result->v_issue_date)).'<br/>';
						echo '<b>Expiry Date: '.date('d-m-Y', strtotime($result->v_expiry_date)).'</b><br/>';
						
					} else if($type=='labour') {
						echo 'Labour Card ID: '.$result->lc_id.'<br/>';
						echo 'Issue Date: '.date('d-m-Y', strtotime($result->lc_issue_date)).'<br/>';
						echo '<b>Expiry Date: '.date('d-m-Y', strtotime($result->lc_expiry_date)).'</b><br/>';
						
					} else if($type=='health') {
						echo 'Health Card ID: '.$result->hc_id.'<br/>';
						echo 'Issue Date: '.date('d-m-Y', strtotime($result->hc_issue_date)).'<br/>';
						echo '<b>Expiry Date: '.date('d-m-Y', strtotime($result->hc_expiry_date)).'</b><br/>';
						echo 'Card Info: '.$result->hc_info.'<br/>';
						
					} else if($type=='idcard') {
						echo 'ID: '.$result->ic_id.'<br/>';
						echo 'Issue Date: '.date('d-m-Y', strtotime($result->ic_issue_date)).'<br/>';
						echo '<b>Expiry Date: '.date('d-m-Y', strtotime($result->ic_expiry_date)).'</b><br/>';
					}
						
					?>
				</td>
			</tr>
			<?php } ?>
		</table>
		<?php } else { ?>
			<div class="alert alert-warning">
				<p>No records were found!</p>
			</div>
		<?php } ?>
	
	<br/><br/><br/><br/>
	</div>
			
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
					
					<button type="button" onclick="javascript:window.history.back();"
										 class="btn btn-responsive button-alignment btn-primary"
										 data-toggle="button">
									<span style="color:#fff;" >
										<i class="fa fa-fw fa-times"></i>
									Back 
								</span>
					</button>
					</span>
			</div>
		</div>
                    
@stop
