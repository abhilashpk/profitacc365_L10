@extends('printdo')
@section('contentnew')
                        <div class="col-md-12">
								<div class="table-responsive">
									<table class="table table-striped table-condensed">
										<thead>
											<tr>
												<th><strong>SI.#</strong></th>
												<th><strong>Description</strong></th>
												<th class="text-right"><strong>Unit</strong></th>
												<th class="text-right"><strong>Quantity</strong></th>
												<th></th>
											</tr>
										</thead>
										<tbody>
										<?php $i = 0; ?>
										@foreach($items as $item)
										<?php $i++; ?>
										<tr>
											<td>{{$i}}</td>
											<td>{{$item->item_name}}</td>
											<td class="text-right">{{$item->unit_name}}</td>
											<td class="text-right">{{$item->quantity}}</td>
											<td></td>
										</tr>
										@endforeach
										</tbody>
										
									</table>
								</div>
                        </div>
						
<div class="col-md-12">
	I received the above mentioned materials in good condition as per our order.<br/>
	تلقيت المواد المذكورة أعلاه في حالة جيدة وفقا لدينا النظام<br/><br/>
	<table border="0" style="width:100%;">
		<tr>
			<td><b><u>Received by:</u></b><br/>
				Name     : ___________________<br/><br/>
				Signature: ___________________
			</td>
			<td>For AL SHAHEQ</td>
		</tr>
	</table>
</div> 
						
						
                    
@stop
