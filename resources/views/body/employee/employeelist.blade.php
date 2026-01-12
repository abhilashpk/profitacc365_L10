<div class="panel panel-success filterable">
	<div class="panel-heading">
		<h3 class="panel-title">
			<i class="fa fa-fw fa-columns"></i> Employee List
		</h3>
	</div>
	
	<div class="panel-body">
		<div class="table-responsive m-t-10">
			<table class="table horizontal_table table-striped" id="tableCustList">
				<thead>
				<tr>
					<th>Employee ID</th>
					<th>Employee Name</th>
					<th>Designation</th>
					<th>Nationality</th>
				</tr>
				</thead>
				<tbody>
				@foreach($employees as $employee)
				<tr>
					<td><a href="" class="empRow" data-id="{{$employee->id}}" data-code="{{$employee->code}}" data-name="{{$employee->name}}" data-dismiss="modal">{{$employee->code}}</a></td>
					<td><a href="" class="empRow" data-id="{{$employee->id}}" data-code="{{$employee->code}}" data-name="{{$employee->name}}" data-dismiss="modal">{{$employee->name}}</a></td>
					<td>{{ $employee->designation }}</td>
					<td>{{ $employee->nationality }}</td>
				</tr>
			   @endforeach
				</tbody>
			</table>

		</div>
		
	</div>
</div>
					
					
<script>
$(function() {
		
	var inputMapper = {
		"name": 1,
		"position": 2,
		"office": 3,
		"age": 4
	};
	var dtInstance = $("#tableCustList").DataTable({
		"lengthMenu": [10, 25, 50, "ALL"],
		bLengthChange: false,
		mark: true
	});
	$("input").on("input", function() {
		var $this = $(this);
		var val = $this.val();
		var key = $this.attr("name");
		dtInstance.columns(inputMapper[key] - 1).search(val).draw();
	});
});
</script>