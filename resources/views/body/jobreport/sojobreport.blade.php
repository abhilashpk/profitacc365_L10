			
<div class="panel-body" style="background-color:#d6eef8">
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="si">
                                    <div class="row" style="padding:10px;">
                                    <table class="table table-striped" id="tableBank">
	                    @if(!empty($results))
		                        <tr><th>SO No:</th>
                                         <td>{{$results[0]->voucher_no}}</td>
                                         <th></th>
                                         <th>Customer</th>
                                          <td>{{$results[0]->master_name}}</td>
                                          <th></th>
                                          <td>
					                          <p>
						                      <button type="button" class="btn btn-primary btn-job-details" data-id="{{$results[0]->so_id}}" >Work Order</button>
					                         </p>
				                        </td>
                                    </tr>

                                    <tr>
                                         
                                        <th>Item Code</th>
                                        <th>Item Name</th>
                                        <th>SO Qty.</th>
                                        <th></th>
                                        <th></th>
                                        
                                    </tr>
                                    
                                      @foreach($results as $row)
                                	<tr>
                                	    
                                	    
                                		<td>{{$row->itmcode}}</td>
                                		<td>{{$row->item_name}}</td>
                                		<td>{{$row->so_qty}}</td>
                                		<td></td>
                                		<td></td>
                                        
                                	</tr>
                                @endforeach 
	
			
		@else
			<tr><td colspan="11" align="center">No reports were found!</td></tr>
		@endif

</table>
</div>
</div>
</div>
</div>

<section class="content">
            <div class="row">
				<div class="col-lg-12">
				   <div class="panel">
							<div class="panel-body" id="jobReport">
							</div>
						</div>
				</div>
			</div>
</section>

<script>
$(document).on('click', '.btn-job-details', function(e)  { 
    var res = $(this).attr('data-id');
    console.log(res);
    $('#jobReport').load("{{ url('job_report/workjob_details/') }}/"+res);
});
</script>