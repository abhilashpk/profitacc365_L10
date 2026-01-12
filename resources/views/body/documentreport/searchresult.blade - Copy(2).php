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
				<th>Emp. Code</th>
				<th>Emp. Name</th>
				<th>Passport#</th>
				<th>Exp.Date</th>
				<th>Visa#</th>
				<th>Exp.Date</th>
				<th>Labour Card#</th>
				<th>Exp.Date</th>
				<th>Health Card#</th>
				<th>Exp.Date</th>
				<th>ID Card#</th>
				<th>Exp.Date</th>
				<th>Medical Exam#</th>
				<th>Exp.Date</th>
			</tr>
			<?php 
			foreach($reports as $result) {
			?>
			<tr style="height:100px;">
				<td class="txtdoc">{{$result->code}}</td>
				<td class="txtdoc">{{$result->name}}</td>
				<td class="txtdoc">{{$result->pp_id}}</td>
				<td class="txtdoc">{{date('d-m-Y', strtotime($result->pp_expiry_date))}}</td>
				<td class="txtdoc">{{$result->v_id}}
				<td class="txtdoc">{{date('d-m-Y', strtotime($result->v_expiry_date))}}</td>
				<td class="txtdoc">{{$result->lc_id}}
				<td class="txtdoc">{{date('d-m-Y', strtotime($result->lc_expiry_date))}}</td>
				<td class="txtdoc">{{$result->hc_id}}
				<td class="txtdoc">{{date('d-m-Y', strtotime($result->hc_expiry_date))}}</td>
				<td class="txtdoc">{{$result->ic_id}}
				<td class="txtdoc">{{date('d-m-Y', strtotime($result->ic_expiry_date))}}</td>
				<td class="txtdoc">{{$result->me_id}}
				<td class="txtdoc">{{date('d-m-Y', strtotime($result->me_expiry_date))}}</td>
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
		</div>
		
		<form class="form-horizontal" role="form" method="POST" name="frmExport" id="frmExport" action="{{ url('document_report/export') }}">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
		<input type="hidden" name="date_from" value="{{$fromdate}}" >
		<input type="hidden" name="date_to" value="{{$todate}}" >
		</form>
						
<script>
	function getExport() { document.frmExport.submit(); }
</script>                  
@stop
