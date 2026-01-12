<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrap-fileinput/css/fileinput.min.css')}}" media="all" />
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/formelements.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendors/datetime/css/jquery.datetimepicker.css')}}">
<link href="{{asset('assets/vendors/airdatepicker/css/datepicker.min.css')}}" rel="stylesheet" type="text/css">
 <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
 <link rel="stylesheet" type="text/css" href="{{asset('assets/css/datepicker.css')}}">

<form class="form-horizontal">
<input type="hidden" name="_token" value="{{ csrf_token() }}">
<input type="hidden" name="id" id="id" value="{{$id}}">
<input type="hidden" name="type" id="type" value="{{$type}}">
<div class="form-group">
	<label for="input-text" class="col-sm-3 control-label">Status</label>
	<div class="col-sm-6">
		<select class="form-control" name="status" id="status">
		@foreach($statuslist as $list)
			<option value="{{$list->id}}" {{($status==$list->id)?"selected": ''}}>{{$list->name}}</option>
		@endforeach
		</select>
	</div>
	
</div>
   <div class="form-group">
   <label for="input-text" class="col-sm-3 control-label">Date</label>
    <div class="col-sm-6">
    <input type="text" class="form-control" autocomplete="off" name="status_date" id="status_date"  data-language='en' value="{{date('d-m-Y')}}" readonly />
	</div>
	</div>
<div class="form-group" id="fileUp">
	<label for="input-text" class="col-sm-3 control-label">Upload Doc</label>
	<div class="col-sm-6">
		<input type="file" id="input-51" name="attachment" class="file-loading" data-show-preview="true" data-url="{{url('cargo_despatchbill/upload-attachment/')}}">
		<div id="files_list_1"></div>
		<p id="loading_1"></p>
		<input type="hidden" name="attachment" id="attachment">
	</div>
</div>
<div class="form-group">
	<label for="input-text" class="col-sm-4 control-label"></label>
	<div class="col-sm-4">
		<button type="submit" class="btn btn-primary saveStatus">Save Status</button>
	</div>
</div>
</form>


<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-fileinput/js/fileinput.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/custom_js/form_elements.js')}}"></script>
<script src="{{asset('assets/js/jquery.fileupload.js')}}"></script>
<script src="{{asset('assets/vendors/datetime/js/jquery.datetimepicker.full.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.en.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/custom_js/advanceddate_pickers.js')}}"></script>
<script>
$('#status_date').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy' ,zIndex:1050} )
$(function() {
	$('#input-51').fileupload({
		dataType: 'json',
		add: function (e, data) {
			$('#loading_1').text('Uploading...');
			data.submit();
		},
		done: function (e, data) {
			var pn = $('#attachment').val();
			$('#attachment').val( (pn=='')?data.result.file_name:pn+','+data.result.file_name );
			$('#loading_1').text('Completed.');
		}
	});
	
	/* $(document).on('change', '#status', function(e) {
		if(this.value < 2) {
			if($('#fileUp').is(":visible"))
				$('#fileUp').hide();
		} else {
			if($('#fileUp').not(":visible"))
				$('#fileUp').show();
		}
	}); */
	
});

$(document).ready(function(){
	$('#fileUp').hide();
    $("form").on("submit", function(event){
        event.preventDefault();
 
        var formValues= $(this).serialize();
		console.log(formValues);
        $.post("{{ url('cargo_despatchbill/save-status/') }}", formValues, function(data){
            //$("#result").html(data);
			 $('#status_modal').modal('toggle');
        });
		var table = $('#tblCargoDsphbill').DataTable().clear().draw();;
    });
});
</script>