<div class="panel panel-success filterable" id="newBinList">
	<div class="panel-heading">
		<h3 class="panel-title">
			<i class="fa fa-fw fa-columns"></i> Bin List
		</h3>
	</div>
	 <div class="panel-body">
	<input type="hidden" name="num" id="num" value="{{$num}}">
	<div class="table-responsive m-t-10"> <button type="button" class="btn btn-primary createBin">Create Bin</button>
		<table class="table horizontal_table table-striped" id="table51">
			<thead>
			<tr>
				<th>Code</th>
				<th>Name</th>
			</tr>
			</thead>
			@foreach($binloc as $row)
			<tr>
				<td><a href="" class="binRow" data-code="{{$row->code}}" data-id="{{$row->id}}" data-dismiss="modal">{{$row->code}}</a></td>
				<td><a href="" class="binRow" data-code="{{$row->code}}" data-id="{{$row->id}}" data-dismiss="modal">{{$row->name}}</a></td>
			</tr>
			@endforeach
		</table>

	</div>
	</div>
</div>


<div class="panel panel-success filterable" id="newBinFrm">
		<div class="panel-heading">
			<h3 class="panel-title">
				<i class="fa fa-fw fa-columns"></i> New Bin 
			</h3>
		</div>
		<div class="panel-body">  <button type="button" class="btn btn-primary listItm">List Bin</button>
		<hr/>
			<div class="col-xs-10">
				<div id="binDtls">
				<form class="form-horizontal" role="form" method="POST" name="frmBin" id="frmBin">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<div class="form-group">
						<label for="input-text" class="col-sm-5 control-label">Code</label>
						<div class="col-sm-7">
							<input type="text" class="form-control" id="bin_code" name="bin_code" placeholder="Code">
						</div>
					</div>
					
					<div class="form-group">
						<label for="input-text" class="col-sm-5 control-label">Name</label>
						<div class="col-sm-7">
							<input type="text" class="form-control" id="name" name="name" placeholder="Name">
						</div>
					</div>
					
					<div class="form-group">
						<label for="input-text" class="col-sm-5 control-label"></label>
						<div class="col-sm-7">
							<button type="button" class="btn btn-primary" id="createI">Create</button>
						</div>
					</div>
				 </form>
				</div>
				
				<div id="sucessmsgItm"><br/>
					<div class="alert alert-success">
						<p>
							Bin created successfully. Click 'Select Bin'.
						</p>
					</div>
					
					<a href="" class="btn btn-primary binRow" id="itmuse" data-dismiss="modal">
							<span class="btn-label">
						</span> Select Bin
					</a>
				</div>
			</div>
		</div>
	</div>

<script>
$(function() {
	

	$('#newBinFrm').toggle();
	
	$('.createBin').on('click', function() {
		$('#newBinFrm').toggle();
		$('#newBinList').toggle();
		$('#sucessmsgItm').toggle();
	});
	
	$('.listItm').on('click', function() {
		$('#newBinFrm').toggle();
		$('#newBinList').toggle();
	});

	$('#createI').on('click', function(e){
		
		var ic = $('#frmBin #bin_code').val();
		var dc = $('#frmBin #name').val();
		if(ic=="") {
			alert('Bin code is required!');
			return false;
		} else if(dc=="") {
			alert('Name is required!');
		} else {				
			$('#binDtls').toggle();
			
			$.ajax({
				url: "{{ url('location/ajax_create/') }}",
				type: 'get',
				data: 'bin_code='+ic+'&name='+dc,
				success: function(data) { console.log(data);
					
					if(data > 0) {
						$('#sucessmsgItm').toggle( function() {
							$('#itmuse').attr("data-id",data);
							$('#itmuse').attr("data-name",dc);
							$('#itmuse').attr("data-code",ic);
							
						});
					} else if(data == 0) {
						$('#binDtls').toggle();
						alert('Bin code/name already exist!');
						return false;
					} else {
						$('#binDtls').toggle();
						alert('Something went wrong, Bin failed to add!');
						return false;
					}
				}
			})
		}
	});


	$('#table51').DataTable({
		filter: true,
		deferRender: true,
		info: false,
		ordering: false,
		//paging: false,
		"searching": true,
	});
     
});
</script>