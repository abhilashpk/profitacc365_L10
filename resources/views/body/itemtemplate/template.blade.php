<link href="{{asset('assets/vendors/bootstrap-multiselect/css/bootstrap-multiselect.css')}}" rel="stylesheet" type="text/css">
<link href="{{asset('assets/vendors/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css">
<link href="{{asset('assets/vendors/select2/css/select2-bootstrap.css')}}" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrap-fileinput/css/fileinput.min.css')}}" media="all" />
<link href="{{asset('assets/vendors/bootstrap-multiselect/css/bootstrap-multiselect.css')}}" rel="stylesheet" type="text/css">
<link type="text/css" href="{{asset('assets/css/tab.css')}}" rel="stylesheet">
<style>
/*.control-label .tiny{
    width: 7.5% !important;
}*/

.col-sm-1 {
	width: 8% !important;
}

.col-sm-0 {
	width: 0% !important;
}
</style>
<div id="joForm" class="controlsP">
@if(count($template) > 0)
<input type="hidden" id="jnum" value="{{$no}}">
<form class="form-horizontal" role="form" method="POST" name="frmJob" id="frmJob" >
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	<input type="hidden" name="item_id" value="{{$itemid}}">
	<input type="hidden" name="jid">
	<input type="hidden" id="no_1" value="{{count($template)}}">
	<div id="formTemplate">
	<div id="accordion" class="panel-group">
	@php $i = 0; @endphp
	@foreach($template as $key => $rows)
		@php $row = $template[$key]; @endphp

		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">
					<a data-toggle="collapse" data-parent="#accordion" href="#collapse{{$i}}">{{$row->type_name}}</a>
					<input type="hidden" name="section[]" value="LD">
				</h4>
			</div>
			<div id="collapse{{$i}}" class="panel-collapse collapse {{($i==0)?'in':''}}">
				<div class="panel-body" style="padding-left:80px !important;">
					<div class="box-body">
					<div class="row">

					<div id="itemdivPrnt_{{$i+1}}">
						<div class="itemdivChldP">

						<input type="hidden" name="input_type[]" value="{{$row->input_type}}">
						<input type="hidden" name="type_name[]" value="{{$row->type_name}}">
						<input type="hidden" name="is_dimension[]" value="{{$row->is_dimension}}">
						<input type="hidden" name="is_stockitm[]" value="{{$row->is_stock}}">
						<input type="hidden" name="required[]" value="{{$row->is_required}}">
						<input type="hidden" name="tplid[]" value="">

						<div class="col-md-12">
						@if($row->input_type=="item")
						<input type="hidden" name="unit[]" id="unt_{{$i+1}}" value="{{$row->unit_id}}">
						<input type="hidden" name="parent[]" id="prnt_{{$i+1}}" value="1">
						<div class="form-group" id="{{str_replace(' ', '_',$row->type_name)}}">
							<label for="input-text" class="col-sm-1 control-label">{{$row->type_name}} {{$i+1}}</label>
							<div class="col-sm-2 selt" id="sl_{{$i+1}}">
								<select class="form-control select22" name="input_item[]" id="initm_{{$i+1}}" data-id="{{$i+1}}" {{($row->is_required==1)?"required": ""}} >
									<option value="">--Select--</option>
								</select>
							</div>
							<label for="input-text" class="col-sm-1 control-label">Remarks</label>
							<div class="col-sm-1">
								<input type="text" id="rmrk_{{$i+1}}" name="remarks[]" class="form-control">
							</div>
							<input type="hidden" id="grp_{{$i+1}}" name="grp[]" value="{{$row->group_id}}">
							<span style="float:left !important; font-size: 12px; margin:8px;">{{$row->unit_name}}</span>
							
							
							@if($row->is_dimension==1)
							<label for="input-text" class="col-sm-1 control-label tiny" style="width:3% !important;">L</label>
							<div class="col-sm-1" style="width:6% !important;">
								<input type="text" class="form-control ht" name="height[]" id="ht_{{$i+1}}" autocomplete="off" >
							</div>
							<label for="input-text" class="col-sm-1 control-label tiny" style="width:3% !important;">W</label>
							<div class="col-sm-1" style="width:6% !important;">
								<input type="text" class="form-control wt" name="width[]" id="wt_{{$i+1}}" autocomplete="off" >
							</div>
							<label for="input-text" class="col-sm-1 control-label tiny" style="width:4% !important;">Qty.</label>
							<div class="col-sm-1" style="width:8% !important;">
								<input type="text" class="form-control qty" name="quantity[]" id="qty_{{$i+1}}" readonly>
							</div> 
							@else
							<label for="input-text" class="col-sm-1 control-label tiny" >Qty.</label>
							<div class="col-sm-1" style="width:8% !important;">
								<input type="text" class="form-control qty" name="quantity[]" id="qty_{{$i+1}}">
								<input type="hidden" name="height[]">
								<input type="hidden" name="width[]">
							</div>
							@endif
							<div class="col-sm-1">
								<button type="button" class="btn btn-success btn-xs btn-add-row" data-id="{{$i+1}}"><i class="fa fa-fw fa-plus-square"></i></button>
								<button type="button" class="btn btn-danger btn-xs btn-rem-row" data-id="{{$i+1}}"><i class="fa fa-fw fa-minus-square"></i></button>
							</div><br/>
						</div>
						@elseif($row->input_type=="text")
						<div class="form-group" id="{{str_replace(' ', '_',$row->type_name)}}">
							<label for="input-text" class="col-sm-2 control-label">{{$row->type_name}}</label>
							<div class="col-sm-6">
								<input type="text" class="form-control" name="input_item[]" autocomplete="on" {{($row->is_required==1)?"required": ""}} >
								<input type="hidden" name="height[]">
								<input type="hidden" name="width[]">
								<input type="hidden" name="quantity[]">
							</div>
							<div class="col-sm-1">
								{{--<button type="button" class="btn btn-danger btn-xs btn-hide-row" data-id="{{str_replace(' ', '_',$row->type_name)}}">Delete</button>--}}
							</div>
							<br/>
							
						</div>
						@elseif($row->input_type=="file")
						<div class="form-group" id="{{str_replace(' ', '_',$row->type_name)}}">
							<label for="input-text" class="col-sm-2 control-label">{{$row->type_name}}</label>
							<div class="col-sm-6">
								<input type="file" id="input-20" name="attachment" accept="image/png, image/gif, image/jpeg" class="file-loading" data-show-preview="true" data-url="{{url('item_template/upload-attachment/')}}">
									<div id="files_list_1"></div>
									<p id="loading"></p>
									<input type="hidden" name="input_item[]" id="attachment">
							</div>
							<div class="col-sm-1">
								<button type="button" class="btn btn-danger btn-xs btn-hide-row" data-id="{{str_replace(' ', '_',$row->type_name)}}"><i class="fa fa-fw fa-minus-square"></i></button>
							</div>
							<input type="hidden" name="height[]">
								<input type="hidden" name="width[]">
								<input type="hidden" name="quantity[]">
						</div>
						@endif
							<br/>
						</div>
						</div>
						</div>
					</div>
					</div>
				</div>
			</div>
		</div><br/>
		
		@php $i++; @endphp
	@endforeach
	</div>       
	</div>
	<div class="form-group">
		<label for="input-text" class="col-sm-4 control-label"></label>
		<div class="col-sm-1">
		{{--<input type="text" id="form_tmplt" name="form_tmplt">--}}
			<button type="submit" id="jobSubmit" class="btn btn-primary">Save</button>
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
			Job Order created successfully. Click 'Select Job'.
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
var cnt = [3,2,1];
$(document).ready(function(){ $('.dm1').hide();$('.dm2').hide();$('.dm3').hide(); 
	$('#joSucessmsg').hide();
	$('.btn-rem-row').hide();
    $(document).on("submit", "#frmJob", function(event){ 
    //$("#frmJob").on("submit", function(event){ 
        event.preventDefault();
		//$('#form_tmplt').val( $.trim($('#formTemplate').html()) );	
        var formValues= $(this).serialize();
 
        $.post("{{ url('item_template/save_joborder/') }}", formValues, function(data){
            // Display the returned data in browser
            //$("#result").html(data);
			$('#joForm').hide();
			$('#joSucessmsg').show();
			$('#joResult').attr("data-id",data);
			$('#joResult').attr("data-item", $('.select22 option:selected').text()); //JN22
			$('#joResult').attr("data-size", $('input[name="input_item[]"]:nth-child(1)').val());//JN22
        });
    });
	
	initailizeSelect2();
});

function changeRowNo() {
	let rn = 1; 
	$( '.itemdivChldP' ).each(function() { 
		$(this).find($('.selt')).attr('id', 'sl_' + rn);
		$(this).find($('.select22')).attr('id', 'initm_' + rn);
		$(this).find($('input[name="unit[]"]')).attr('id', 'unt_' + rn);
		$(this).find($('.ht')).attr('id', 'ht_' + rn);
		$(this).find($('.wt')).attr('id', 'wt_' + rn);
		$(this).find($('.qty')).attr('id', 'qty_' + rn);
		$(this).find($('input[name="grp[]"]')).attr('id', 'grp_' + rn);
		$(this).find($('#initm_'+rn)).attr("data-id",rn); 
		$(this).find($('input[name="remarks[]"]')).attr('id', 'rmrk_' + rn);
		
		rn++;
	});
	return true;
}

function initailizeSelect2() { 
	$(".select22").select2({
		theme: "bootstrap",
		placeholder: "Select Item",
		ajax: { 
			url:  function() {  
				console.log('ff='+this.id);
				//var rn = res[1]; 
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
}
	
$(function() {	
	
	var rn;
	$(document).on('click', '.btn-add-row', function(e)  { 
		e.preventDefault();
		rn = $('#no_1').val(); 
		//var rn = parseInt($(this).attr("data-id")) + 1;
		//var ix = parseInt($(this).attr("data-id")) - 1;
        var controlForm = $('.controlsP #itemdivPrnt_'+$(this).attr("data-id")),
            currentEntry = $(this).parents('.itemdivChldP:first'),
            newEntry = $(currentEntry.clone()).appendTo(controlForm);
			//newEntry.find($('.select22')).attr('id', 'initm_' + rn);
			newEntry.find($('.selt')).attr('id', 'sl_' + rn);
			newEntry.find($('input[name="unit[]"]')).attr('id', 'unt_' + rn);
			newEntry.find($('input[name="grp[]"]')).attr('id', 'grp_' + rn);
			newEntry.find($('input[name="remarks[]"]')).attr('id', 'rmrk_' + rn);
			newEntry.find($('.ht')).attr('id', 'ht_' + rn);
			newEntry.find($('.wt')).attr('id', 'wt_' + rn);
			newEntry.find($('.qty')).attr('id', 'qty_' + rn);
			newEntry.find($('#prnt_'+rn)).val('');
			//newEntry.find($('.btn-add-row')).hide();
			newEntry.find('#rmrk_'+rn).val(''); 
			newEntry.find('#ht_'+rn).val(''); 
			newEntry.find('#wt_'+rn).val(''); 
			newEntry.find('#qty_'+rn).val(''); 
			newEntry.find($('.btn-rem-row')).show();
			newEntry.find($('#sl_'+rn)).html('<select class="form-control select22" name="input_item[]" id="initm_'+rn+'" data-id="'+rn+'" "required"><option value="">--Select--</option></select>');
			//initailizeSelect2();
			controlForm.find('.btn-add-item:not(:last)').hide();
			rn++;
			$('#no_1').val(rn); 
			var tr = changeRowNo();  
			if(tr) { 
				initailizeSelect2();
				
			}
			
			
	}).on('click', '.btn-rem-row', function(e) { 
		//new change..
		$(this).parents('.itemdivChldP:first').remove();
		
		getNetTotal();
		
		//ROWCHNG
		$('.itemdivPrnt_'+$(this).attr("data-id")).find('.itemdivChldP:last').find('.btn-add-row').show();
		if ( $('.itemdivPrnt_'+$(this).attr("data-id")).children().length == 1 ) {
			$('.itemdivPrnt_'+$(this).attr("data-id")).find('.btn-rem-row').hide();
		}
		
		e.preventDefault();
		return false;
	});		
			
	
	$(document).on('click', '.view', function(e)  { 
		$('#'+$(this).value).toggle();
	});
	
	$(document).on('change', '.view', function(e)  {  console.log('dd '+$(this).val());
		if($(this).value!='')
			$('#'+$(this).val()).toggle();
	});
	
	$(document).on('click', '.btn-hide-row', function(e)  { 
		$('#'+$(this).attr("data-id")).toggle();
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
				qty = ht * wt; 
			else
				qty = '';
		} 
		$('#qty_'+curNum).val(qty);
	});
	
	/* $(document).on('click', '.btn-add-row', function(e) 
	{ 	e.preventDefault(); console.log('fffff');
		$('.dm'+cnt.pop()).show();
	}); 
	
	$(document).on('click', '.btn-rem-row', function(e) 
	{ 	e.preventDefault(); 
		$('.dm'+$(this).attr("data-id")).hide();
		cnt.push($(this).attr("data-id"));
	});*/
	
});
</script>