@extends('printgeneral')
@section('contentnew')
<style>
 table, th {
            
           text-align:center;
         }
 .txtdoc { text-align:left; padding: 10px; line-height: 20px; }
</style>
<div>
	
	<?php if(count($reports) > 0) { ?>
	<b>Date From: {{$fromdate}} To Date: {{$todate}}</b><br/><br/>
		<table border="1" width="100%" class="pay">
			<tr style="height:30px;">
				<th>Employee Details</th>
				<th>Passport Details</th>
				<th>Visa Details</th>
				<th>Labour Card Details</th>
				<th>Health Card Details</th>
				<th>ID Card Details</th>
				<th>Medical Exam Details</th>
			</tr>
			<?php 
			foreach($reports as $result) {
			?>
			<tr style="height:100px;">
				<td class="txtdoc">Code:<b>{{$result->code}}</b><br/> Name: <b>{{$result->name}}</b><br/>Designation: {{$result->designation}}
					<br/>Address: {{$result->address1}}<br/>{{$result->address2}}<br/>{{$result->address3}}
				</td>
				<td class="txtdoc"><?php 
						echo 'Passport ID: '.$result->pp_id.'<br/>';
						echo 'Issued: '.date('d-m-Y', strtotime($result->pp_issue_date)).'<br/>';
						echo '<b>Expiry: '.date('d-m-Y', strtotime($result->pp_expiry_date)).'</b><br/>';
						echo 'Issue Place: '.$result->pp_issue_place;
					?>
				</td>
				<td class="txtdoc"><?php echo 'Vise ID: '.$result->v_id.'<br/>';
						echo 'Designation: '.$result->v_designation.'<br/>';
						echo 'Join: '.date('d-m-Y', strtotime($result->join_date)).'<br/>';
						echo 'Issued: '.date('d-m-Y', strtotime($result->v_issue_date)).'<br/>';
						echo '<b>Expiry: '.date('d-m-Y', strtotime($result->v_expiry_date)).'</b><br/>'; 
					?>
				</td>
				<td class="txtdoc"><?php echo 'Labour Card ID: '.$result->lc_id.'<br/>';
						echo 'Issued: '.date('d-m-Y', strtotime($result->lc_issue_date)).'<br/>';
						echo '<b>Expiry: '.date('d-m-Y', strtotime($result->lc_expiry_date)).'</b><br/>';
					?>
				</td>
				<td class="txtdoc"><?php echo 'Health Card ID: '.$result->hc_id.'<br/>';
						echo 'Issued: '.date('d-m-Y', strtotime($result->hc_issue_date)).'<br/>';
						echo '<b>Expiry: '.date('d-m-Y', strtotime($result->hc_expiry_date)).'</b><br/>';
						echo 'Card Info: '.$result->hc_info.'<br/>'; ?>
				</td>
				<td class="txtdoc"><?php echo 'ID: '.$result->ic_id.'<br/>';
						echo 'Issued: '.date('d-m-Y', strtotime($result->ic_issue_date)).'<br/>';
						echo '<b>Expiry: '.date('d-m-Y', strtotime($result->ic_expiry_date)).'</b><br/>'; ?>
				</td>
				<td class="txtdoc"><?php echo 'ID: '.$result->me_id.'<br/>';
						echo 'Issued: '.date('d-m-Y', strtotime($result->me_issue_date)).'<br/>';
						echo '<b>Expiry: '.date('d-m-Y', strtotime($result->me_expiry_date)).'</b><br/>'; ?>
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
