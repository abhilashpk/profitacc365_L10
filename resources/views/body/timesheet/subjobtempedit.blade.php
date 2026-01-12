<link href="{{asset('assets/vendors/bootstrap-multiselect/css/bootstrap-multiselect.css')}}" rel="stylesheet" type="text/css">
<link href="{{asset('assets/vendors/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css">
<link href="{{asset('assets/vendors/select2/css/select2-bootstrap.css')}}" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrap-fileinput/css/fileinput.min.css')}}" media="all" />
<link href="{{asset('assets/vendors/bootstrap-multiselect/css/bootstrap-multiselect.css')}}" rel="stylesheet" type="text/css">

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
<div id="joForm" class="controls">
<input type="hidden" id="num" value="{{$num}}">

<form class="form-horizontal" role="form" method="POST" name="frmsubJob" id="frmsubJob" >
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	<input type="hidden" name="job_id" value="{{$jid}}">
	<input type="hidden" name="wid" value="{{$wid}}">
	
	
    <div class="panel panel-default">
    <div class="panel-body" style="padding-left:80px !important;">
	<div class="box-body">
    <div class="row">
    <input type="hidden" id="reminfo" name="remove_info">
          <div class="infodivPrnt">
                                @foreach($subjobdata as $sub)
									<div class="infodivChld">							
										<div class="form-group">
											<label for="input-text" class="col-sm-2 control-label">Sub JOb</label>
										   
											<div class="col-xs-10">
												<div class="col-xs-5">
													<select id="subjob" class="form-control select2"   name="subjob[]">
                                                     	<option>Select Sub Job</option>
											            @foreach($subjob as $jrow)
												           <option value="{{ $jrow->id }}" {{($sub->subjob==$jrow->id)?'selected':''}} >{{ $jrow->name }}</option>
											          @endforeach
                                                   </select>												
                                                </div>
												<div class="col-xs-4">
													<input type="number" name="workhr[]" value="{{$sub->workhr}}"class="form-control" placeholder="No: of Hours">
												</div>
												<div class="col-xs-1">
													 <button type="button" class="btn btn-success btn-add-info" >
														<span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span>
													 </button>
													 <button type="button" class="btn btn-danger btn-remove-info" data-id="{{$id}}">
														<span class="glyphicon glyphicon-minus" aria-hidden="true"></span>
													  </button>
												</div>
											</div>
										</div>
									</div>
                                    @endforeach
								</div>
								

    
    </div>
    </div>
    </div>
    </div>

    <div class="form-group">
		<label for="input-text" class="col-sm-4 control-label"></label>
		<div class="col-sm-1">
		
			<button type="submit" id="jobSubmit" class="btn btn-primary">Save</button>
		</div>
	</div>


    </form>
    </div>


    <div id="joSucessmsg"><br/>
	<div class="alert alert-success">
		<p>
			Job Order created successfully. Click 'Select Job'.
		</p>
	</div>
	<a href="" class="btn btn-primary subjobRow" id="joResult" data-dismiss="modal">
		<span class="btn-label">
	</span> Select Job</a>
</div>

<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-fileinput/js/fileinput.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-fileinput/js/fileinput.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/custom_js/form_elements.js')}}"></script>
<script src="{{asset('assets/js/jquery.fileupload.js')}}"></script>

<script src="{{asset('assets/vendors/select2/js/select2.js')}}" type="text/javascript"></script>
<script>
$(document).ready(function(){
	$('#joSucessmsg').hide();
 $('.infodivPrnt').find('.btn-add-info:not(:last)').hide();
$(document).on("submit", "#frmsubJob", function(event){ 

 event.preventDefault();
 var formValues= $(this).serialize();
 $.post("{{ url('wage_entry/subjob_template/save/') }}", formValues, function(data){

 $('#joForm').hide();
$('#joSucessmsg').show();
$('#joResult').attr("data-id",data);
 });
 });
 });

 
$(function() {

$(document).on('click', '.btn-add-info', function(e) 
    { 
        e.preventDefault();

        var controlForm = $('.controls .infodivPrnt'),
            currentEntry = $(this).parents('.infodivChld:first'),
            newEntry = $(currentEntry.clone()).appendTo(controlForm);
			newEntry.find('input').val('');
			$('.infodivPrnt').find('.btn-add-info:not(:last)').hide();
           // .removeClass('btn-default').addClass('btn-danger')
           // .removeClass('btn-add-info').addClass('btn-remove-info')
            //.html('<span class="glyphicon glyphicon-minus" aria-hidden="true"></span> Remove ');
    }).on('click', '.btn-remove-info', function(e)

    { 
	var reminfo = $('#reminfo').val();
		var ids = (reminfo=='')?$(this).attr('data-id'):reminfo+','+$(this).attr('data-id');
		$('#reminfo').val(ids);
		$(this).parents('.infodivChld:first').remove();

		$('.infodivPrnt').find('.infodivChld:last').find('.btn-add-info').show();
		if ( $('.infodivPrnt').children().length == 1 ) {
			$('.infodivPrnt').find('.btn-remove-info').hide();
		}

		e.preventDefault();
		return false;
	});
    });
	
</script>