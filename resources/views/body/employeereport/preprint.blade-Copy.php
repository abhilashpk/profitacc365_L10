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
	<b><?php if($fromdate!='' && $todate!='') { echo 'Date From: '.$fromdate.' To Date: '.$todate; } ?></b><br/><br/>
		<table border="1" width="100%" class="pay">
			<tr style="height:30px;">
				<th>Code</th>
				<th>Name</th>
				<th>Designation</th>
				<th>Nationality</th>
				<th>Gender</th>
				<th>Address</th>
				<th>Phone</th>
				<th>Date of Birth</th>
				<th>Join Date</th>
			</tr>
			<?php 
			foreach($reports as $result) {
			?>
			<tr>
				<td class="txtdoc">{{$result->code}}</td>
				<td class="txtdoc">{{$result->name}}</td>
				<td class="txtdoc">{{$result->designation}}</td>
				<td class="txtdoc">{{$result->nationality}}</td>
				<td class="txtdoc">{{ ($result->gender==1)?'Male':'Female' }}</td>
				<td class="txtdoc">{{$result->address1}} {{$result->address2}} {{$result->address3}}</td>
				<td class="txtdoc">{{$result->phone}}</td>
				<td class="txtdoc">{{($result->dob=='0000-00-00')?'':date('d-m-Y', strtotime($result->dob))}}</td>
				<td class="txtdoc">{{($result->join_date=='0000-00-00')?'':date('d-m-Y', strtotime($result->join_date))}}</td>
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
					<button type="button" onclick="getExport()"
											 class="btn btn-responsive button-alignment btn-primary"
											 data-toggle="button">
										<span style="color:#fff;">
											<i class="fa fa-fw fa-upload"></i>
										Export Excel
									</span>
									</button>
									
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
			
			<form class="form-horizontal" role="form" method="POST" name="frmExport" id="frmExport" action="{{url('employee_report/export')}}">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<input type="hidden" name="date_from" value="{{$fromdate}}" >
				<input type="hidden" name="date_to" value="{{$todate}}" >
				<input type="hidden" name="designation" value="{{$designation}}" >
				<input type="hidden" name="nationality" value="{{$nationality}}" >
			</form>
						
		</div>
 <script>
	function getExport() { document.frmExport.submit(); }
</script>                   
@stop
