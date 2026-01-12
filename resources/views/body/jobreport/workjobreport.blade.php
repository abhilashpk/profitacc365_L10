			
<div class="panel-body" style="background-color:#d6eef8">
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="si">
                                    <div class="row" style="padding:10px;">
                                    <table class="table table-striped" id="tableBank">
	                    @if(!empty($reports))
		                        @foreach($reports as $report)        
                                    <tr><th>WO No:</th>
                                         <td>{{$report[0]->wvoucher_no}}</td>
                                         <th></th>
                                        <th></th>
                                          </tr>
                                    <tr>
                                         
                                        <th>Item Code</th>
                                        <th>Item Name</th>
                                        <th>WO Qty.</th>
                                        <th>Delivered Qty.</th>
                                        <th>Balance Qty.</th>
                                        <th></th>
                                    </tr>
                                @foreach($report as $rw)
                                	<tr>
                                	    
                                	    
                                		<td>{{$rw->item_code}}</td>
                                		<td>{{$rw->description}}</td>
                                		<td>{{$rw->quantity}}</td>
                                		<td>{{($rw->balance_quantity==0 && $rw->is_transfer==0)?0:$rw->quantity-$rw->balance_quantity}}</td>
                                		<td>{{($rw->balance_quantity==0 && $rw->is_transfer==0)?$rw->quantity:$rw->balance_quantity}}</td>
                                	</tr>
                                @endforeach
                                 @endforeach
	
			
		@else
			<tr><td colspan="11" align="center">No reports were found!</td></tr>
		@endif

</table>
</div>
</div>
</div>
</div>



<script>

</script>