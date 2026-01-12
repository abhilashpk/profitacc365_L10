<div class="form-group">
	<label for="input-text" class="col-sm-3 control-label">Date</label>
	<div class="col-sm-9">
		<input type="hidden" name="id" id="id" value="{{$row->id}}">
		<input type="hidden" name="lead_id" id="lead_id" value="{{$row->lead_id}}">
		<input type="text" class="form-control pull-right" autocomplete="off" name="voucher_date" data-language='en' id="voucher_date" value="{{date('d-m-Y', strtotime($row->date))}}">
	</div>
</div>

<div class="form-group">
	<label for="input-text" class="col-sm-3 control-label">Title</label>
	<div class="col-sm-9">
		<input type="text" class="form-control" id="title" name="title" value="{{$row->title}}">
	</div>
</div>

<div class="form-group">
	<label for="input-text" class="col-sm-3 control-label">Description</label>
	<div class="col-sm-9">
		<textarea class="form-control" id="description" name="description">{{$row->description}}</textarea>
	</div>
</div>


</div>