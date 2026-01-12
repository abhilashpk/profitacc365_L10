	<link href="{{asset('assets/vendors/bootstrap-multiselect/css/bootstrap-multiselect.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('assets/vendors/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('assets/vendors/select2/css/select2-bootstrap.css')}}" rel="stylesheet" type="text/css">
	
<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrap-fileinput/css/fileinput.min.css')}}" media="all" />
<link href="{{asset('assets/vendors/bootstrap-multiselect/css/bootstrap-multiselect.css')}}" rel="stylesheet" type="text/css">
<div id="joForm">
@if(count($template) > 0)
<input type="hidden" id="jnum" value="{{$no}}">
<form class="form-horizontal" role="form" method="POST" name="frmJob" id="frmJob" >
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	<input type="hidden" name="jid" value="{{$jid}}">
	@php $i = 0; @endphp
	@foreach($template as $k => $row)
	<input type="hidden" name="input_type[]" value="{{$row->input_type}}">
	<input type="hidden" name="is_dimension[]" value="{{$row->is_dimension}}">
	@if($row->input_type=="item")
	<input type="hidden" name="unit[]" id="unt_{{$i+1}}" value="{{$row->unit_id}}">
	<div class="form-group">
		<label for="input-text" class="col-sm-2 control-label">{{$row->type_name}}</label>
		<div class="col-sm-3">
			<select class="form-control select22" name="input_item[]" id="initm_{{$i+1}}" data-id="{{$i+1}}" {{($row->is_required==1)?"required": ""}} >
				@foreach($items[$k] as $item)
				<option value="{{$item->id}}" {{($item->id==$jobdata[$k]->input_value)?'selected':''}}>{{$item->text}}</option>
				@endforeach
			</select>
		</div>
		<input type="hidden" id="grp_{{$i+1}}" value="{{$row->group_id}}">
		<span style="float:left !important; font-size: 14px; margin:10px;">{{$row->unit_name}}</span>
		@if($row->is_dimension==1)
		<label for="input-text" class="col-sm-1 control-label">L</label>
		<div class="col-sm-1">
			<input type="text" class="form-control ht" name="height[{{$i}}]" id="ht_{{$i+1}}" value="{{$jobdata[$k]->other_value->height}}" autocomplete="off" required>
		</div>
		<label for="input-text" class="col-sm-1 control-label">W</label>
		<div class="col-sm-1">
			<input type="text" class="form-control wt" name="width[{{$i}}]" id="wt_{{$i+1}}" autocomplete="off" value="{{$jobdata[$k]->other_value->width}}" required>
		</div>
		<label for="input-text" class="col-sm-1 control-label">Qty.</label>
		<div class="col-sm-1">
			<input type="text" class="form-control" name="quantity[{{$i}}]" value="{{$jobdata[$k]->other_value->quantity}}" id="qty_{{$i+1}}" readonly>
		</div>
		@else
		<label for="input-text" class="col-sm-1 control-label">Qty.</label>
		<div class="col-sm-1">
			<input type="text" class="form-control" name="quantity[{{$i}}]" value="{{$jobdata[$k]->other_value->quantity}}" autocomplete="off" required>
		</div>
		@endif
	</div>
	
	@if($row->frame_no==1)
	@foreach($jobdata[$k]->dmond as $drow)
	<div class="form-group dm1">
		<label for="input-text" class="col-sm-2 control-label">Frame</label>
		<div class="col-sm-3">
			<select class="form-control select22" name="input_itemsub[{{$i}}][]" data-id="{{$i+1}}">
				@foreach($items[$k] as $item)
				<option value="{{$item->id}}" {{($item->id==$drow->dm_tem)?'selected':''}}>{{$item->text}}</option>
				@endforeach
			</select>
		</div>
		<label for="input-text" class="col-sm-1 control-label tiny">Qty.</label>
		<div class="col-sm-1">
			<input type="text" class="form-control" name="quantity_sub[{{$i}}][]" value="{{$drow->qty}}" autocomplete="off">
		</div>
		<div class="col-sm-1">
			<button type="button" class="btn btn-danger btn-xs btn-rem-row" data-id="1"><i class="fa fa-fw fa-minus-square"></i></button>
		</div>
	</div>
	@endforeach
	@endif
	
	@elseif($row->input_type=="text")
	<div class="form-group">
		<label for="input-text" class="col-sm-2 control-label">{{$row->type_name}}</label>
		<div class="col-sm-8">
			<input type="text" class="form-control" name="input_item[]" value="{{$jobdata[$k]->input_value}}" autocomplete="on" {{($row->is_required==1)?"required": ""}} >
		</div>
	</div>
	@elseif($row->input_type=="file")
	<div class="form-group">
		<label for="input-text" class="col-sm-2 control-label">{{$row->type_name}}</label>
		<div class="col-sm-8">
			<input type="file" id="input-20" name="attachment" class="file-loading" data-show-preview="true" data-url="{{url('item_template/upload-attachment/')}}">
				<div id="files_list_1"></div>
				<p id="loading"></p>
				<input type="hidden" name="input_item[]" value="{{$jobdata[$k]->input_value}}" id="attachment">
				@if($jobdata[$k]->input_value!='')
				<img src="{{ asset('uploads/sojob/'.$jobdata[$k]->input_value) }}" height="150px">
				@endif
		</div>
	</div>
	@endif
	@php $i++; @endphp
	@endforeach
	
	<div class="form-group">
		<label for="input-text" class="col-sm-4 control-label"></label>
		<div class="col-sm-1">
			<button type="submit" id="jobSubmit" class="btn btn-primary">Update</button>
		</div>
	</div>
</form>
@else
<div class="alert alert-danger">
	<p>No templates found!</p>
</div>
@endif
</div>

<div id="joSucessmsg"><br/>
	<div class="alert alert-success">
		<p>
			Job Order updated successfully. Click 'Select Job'.
		</p>
	</div>
	<a href="" class="btn btn-primary jobRow" id="joResult" data-dismiss="modal">
		<span class="btn-label">
	</span> Select Job</a>
</div>

		
<div id="itemModal" class="modal fade animated" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Select Item</h4>
			</div>
			<div class="modal-body" id="itemData">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

			
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-fileinput/js/fileinput.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-fileinput/js/fileinput.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/custom_js/form_elements.js')}}"></script>
<script src="{{asset('assets/js/jquery.fileupload.js')}}"></script>

<script src="{{asset('assets/vendors/select2/js/select2.js')}}" type="text/javascript"></script>
<script>
$(document).ready(function(){
	$('#joSucessmsg').hide();
	
    $("#frmJob").on("submit", function(event){
        event.preventDefault();
 
        var formValues= $(this).serialize();
 
        $.post("{{ url('item_template/save_joborder/') }}", formValues, function(data){
            // Display the returned data in browser
            //$("#result").html(data);
			$('#joForm').hide();
			$('#joSucessmsg').show();
			$('#joResult').attr("data-id",data);
        });
    });
});
$(function() {	
	
	$(".select22").select2({
        theme: "bootstrap",
        placeholder: "Select Item",
		ajax: { 
			url:  function() { 
				var grpid = $('#grp_'+$(this).attr("data-id")).val();
				return "{{ url('item_template/get_items/') }}/"+grpid;
			},
			type: "get",
			dataType: 'json',
			delay: 250,
			/* data: function (params) {
				return {
				  searchTerm: params.term 
				};
			}, */
			processResults: function (response) {
				 return {
					results: response
				 };
			},
			cache: true
		}
    });
	
	$('#input-20').fileupload({
		dataType: 'json',
		add: function (e, data) {
			$('#loading').text('Uploading...');
			data.submit();
		},
		done: function (e, data) {
			var pn = $('#attachment').val();
			$('#attachment').val( (pn=='')?data.result.file_name:pn+','+data.result.file_name );
			$('#loading').text('Completed.');
		}
	});
	
	$(document).on('blur', '.ht,.wt', function(e) {
		var res = this.id.split('_');
		var curNum = res[1];
		var ht = parseFloat( ($('#ht_'+curNum).val()=='') ? 0 : $('#ht_'+curNum).val());
		var wt = parseFloat( ($('#wt_'+curNum).val()=='') ? 0 : $('#wt_'+curNum).val());
		var qty = '';
		if($('#unt_'+curNum).val()==26) { 
			qty = (ht + wt) * 2 / 100; 
		} else if($('#unt_'+curNum).val()==32){
			if(wt > 0)
				qty = ht / wt; 
			else
				qty = '';
		} 
		$('#qty_'+curNum).val(qty);
	});
});
</script>