<div class="filedivChld">
	<div class="form-group">
		<label for="input-text" class="col-sm-2 control-label">Attachment</label>
		<div class="col-sm-8">
			<input type="file" id="input-5{{$no}}" name="attachment" class="file-loading" data-show-preview="true" data-url="{{url('cargo_receipt/upload-attachment/')}}">
			<div id="files_list_{{$no}}"></div>
			<p id="loading_{{$no}}"></p>
			<input type="text" name="attachments[]" id="attachment_{{$no}}">
		</div>
		<div class="col-sm-1">
			<button type="button" class="btn-success btn-add-file" id="btn_{{$no}}">
				<i class="fa fa-fw fa-plus-square"></i>
			</button>
			<button type="button" class="btn-danger btn-remove-file">
				<i class="fa fa-fw fa-minus-square"></i>
			 </button>
		</div>
	</div>
</div>

<script>
$("#input-5{{$no}}").fileinput({
	browseClass: "btn btn-default",
	showUpload: false,
	mainTemplate: "{preview}\n" +
		"<div class='input-group {class}'>\n" +"   <div class='input-group-btn'>\n" +"       {browse}\n" +"       {upload}\n" +"       {remove}\n" +"   </div>\n" +"   {caption}\n" +"</div>"
});
	
$('#input-5{{$no}}').fileupload({
	dataType: 'json',
	add: function (e, data) {
		$('#loading_{{$no}}').text('Uploading...');
		data.submit();
	},
	done: function (e, data) {
		var pn = $('#attachment_{{$no}}').val();
		$('#attachment_{{$no}}').val( (pn=='')?data.result.file_name:pn+','+data.result.file_name );
		$('#loading_{{$no}}').text('Completed.');
	}
});
</script>