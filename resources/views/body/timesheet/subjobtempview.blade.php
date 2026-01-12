<div class="col-xs-10">
	<table class="table table-bordered table-hover">
		<thead>
		<tr>
			<th>Sub JOB</th>
			<th>WORK HOUR</th>
			
		</tr>
		</thead>
		<tbody>
		@foreach($subjobdata as $data)
		<tr>
			<td><select id="subjob" class="form-control select2"   name="subjob[]">
                <option>Select Sub Job</option>
				@foreach($subjobs as $jrow)
				<option value="{{ $jrow->id }}" {{($data->subjob==$jrow->id)?'selected':''}} >{{ $jrow->name }}</option>
				 @endforeach
            </select>	
            </td>
			<td>{{ $data->workhr }}</td>
			
		</tr>
		@endforeach
		</tbody>
	</table>
</div>