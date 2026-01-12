<div class="filedivChld">
	<div class="form-group">
		<!--<label for="input-text" class="col-sm-2 control-label filelbl" id="lblif_{{$no}}">Upload Document {{$no}}</label>-->
		<div class="col-sm-3">
		<input type="text" class="form-control" name="name[]" placeholder="Document Name" autocomplete="off">
		</div>
		<div class="col-sm-8">
			<input type="file" id="input-5{{$no}}" name="photos" class="file-loading" data-show-preview="true" data-url="{{url('contractbuilding/upload-contract/')}}">
			<div id="files_list_{{$no}}"></div>
			<p id="loading_{{$no}}"></p>
			<input type="hidden" name="photo_name[]" id="photo_name_{{$no}}">
			<input type="hidden" name="photo_id[]">
		</div>
		<div class="col-sm-1">
			<button type="button" class="btn-success btn-add-file" id="bt_{{$no}}">
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
		var pn = $('#photo_name_{{$no}}').val();
		$('#photo_name_{{$no}}').val( (pn=='')?data.result.file_name:pn+','+data.result.file_name );
		$('#loading_{{$no}}').text('Completed.');
	}
});
</script>