<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrap-fileinput/css/fileinput.min.css')}}" media="all" />
<link href="{{asset('assets/vendors/bootstrap-multiselect/css/bootstrap-multiselect.css')}}" rel="stylesheet" type="text/css">
<form class="form-horizontal" role="form" method="POST" name="frmJob" id="frmJob" action="">
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	@php $i = 1; @endphp
	@foreach($template as $row)
	@if($i%2==1)
	<div class="form-group">
		<label for="input-text" class="col-sm-2 control-label">{{$row->type_name}}</label>
		<div class="col-sm-4">
			@if($row->input_type=="text")
			<input type="text" class="form-control" id="{{strtolower($row->label_name)}}" name="{{strtolower($row->label_name)}}" autocomplete="off" {{($row->is_required==1)?"required": ""}} >
			@elseif($row->input_type=="textarea")
			<textarea class="form-control" id="{{strtolower($row->label_name)}}" name="{{strtolower($row->label_name)}}" autocomplete="off" {{($row->is_required==1)?"required": ""}} ></textarea>
			@elseif($row->input_type=="select")
			<select id="{{strtolower($row->label_name)}}" class="form-control" name="{{strtolower($row->label_name)}}" {{($row->is_required==1)?"required": ""}}>
				<option value="">Select...</option>
				@php $vals = explode(',', $row->input_values); @endphp
				@foreach($vals as $val)
				<option value="{{$val}}">{{$val}}</option>
				@endforeach
			</select>
			@elseif($row->input_type=="file")
			<div class="col-sm-8">
				<input type="file" id="input-51" name="attachment" class="file-loading" data-show-preview="true" data-url="{{url('cargo_receipt/upload-attachment/')}}">
				<div id="files_list_1"></div>
				<p id="loading_1"></p>
				<input type="hidden" name="attachments[]" id="attachment_1">
			</div>
			@endif
		</div>
	@else
		<label for="input-text" class="col-sm-2 control-label">{{$row->label_name}}</label>
		<div class="col-sm-4">
			@if($row->input_type=="text")
			<input type="text" class="form-control" id="{{strtolower($row->label_name)}}" name="{{strtolower($row->label_name)}}" autocomplete="off" {{($row->is_required==1)?"required": ""}} >
			@elseif($row->input_type=="textarea")
			<textarea class="form-control" id="{{strtolower($row->label_name)}}" name="{{strtolower($row->label_name)}}" autocomplete="off" {{($row->is_required==1)?"required": ""}} ></textarea>
			@elseif($row->input_type=="select")
			<select id="{{strtolower($row->label_name)}}" class="form-control" name="{{strtolower($row->label_name)}}" {{($row->is_required==1)?"required": ""}}>
				<option value="">Select...</option>
				@php $vals = explode(',', $row->input_values); @endphp
				@foreach($vals as $val)
				<option value="{{$val}}">{{$val}}</option>
				@endforeach
			</select>
			@elseif($row->input_type=="file")
			<div class="col-sm-8">
				<input type="file" id="input-51" name="attachment" class="file-loading" data-show-preview="true" data-url="{{url('cargo_receipt/upload-attachment/')}}">
				<div id="files_list_1"></div>
				<p id="loading_1"></p>
				<input type="hidden" name="attachments[]" id="attachment_1">
			</div>
			@endif
	   </div>
	</div>
	@endif
	@php $i++; @endphp
	@endforeach
	
	<div class="form-group">
		<label for="input-text" class="col-sm-4 control-label"></label>
		<div class="col-sm-1">
			<button type="submit" class="btn btn-primary">Submit</button>
		</div>
	</div>
</form>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-fileinput/js/fileinput.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-fileinput/js/fileinput.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/custom_js/form_elements.js')}}"></script>
<script src="{{asset('assets/js/jquery.fileupload.js')}}"></script>
<script>
$(function() {	
	
	$('#input-51').fileupload({
		dataType: 'json',
		add: function (e, data) {
			$('#loading_1').text('Uploading...');
			data.submit();
		},
		done: function (e, data) {
			var pn = $('#attachment_1').val();
			$('#attachment_1').val( (pn=='')?data.result.file_name:pn+','+data.result.file_name );
			$('#loading_1').text('Completed.');
		}
	});
});
</script>