@section('header_styles')
   		
	<!--page level css -->
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/buttons.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/colReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/dataTables.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/rowReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/scroller.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatablesmark.js/css/datatables.mark.min.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/responsive_datatables.css')}}">
    <!--end of page level css-->
@stop

<div class="table-responsive">
<table class="table " id="tableFlat" border="0">
	<thead>
		<tr>
			 <th>Building</th>
			 <th>Flat No</th>
			<th>Flat Name</th>
			<th></th>
			<th></th>
		</tr>
	</thead>
		@if(!empty($flatmaster))
		
			@foreach($flatmaster as $row)
			<tr>
				
              <td >{{$row->buildingcode.' '.$row->buildingname}}</td>
			  <td>{{$row->flat_no}}</td>
			  <td>{{$row->flat_name}}</td>
			<td>
				<p>
					<button class="btn btn-primary btn-xs" onClick="location.href='{{ url('flatmaster/edit/'.$row->id) }}'">
					<span class="glyphicon glyphicon-pencil"></span></button>
				</p>
			</td>
			<td>
				<p>
					<button class="btn btn-danger btn-xs delete" onClick="funDelete('{{ $row->id }}')">
					<span class="glyphicon glyphicon-trash"></span></button>
				</p>
			</td>
			</tr>
			@endforeach
			
		@else
			<tr><td colspan="5" align="center">No reports were found!</td></tr>
		@endif
	<tbody>
	</tbody>
</table>
</div>

@section('footer_scripts')

<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/jquery.dataTables.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/dataTables.buttons.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/dataTables.colReorder.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/dataTables.responsive.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/dataTables.rowReorder.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/buttons.colVis.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/buttons.html5.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/buttons.bootstrap.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/buttons.print.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/dataTables.scroller.js')}}"></script>

<script src="{{asset('assets/vendors/mark.js/jquery.mark.js')}}" charset="UTF-8"></script>
<script src="{{asset('assets/vendors/datatablesmark.js/js/datatables.mark.min.js')}}" charset="UTF-8"></script>
<script src="{{asset('assets/js/custom_js/responsive_datatables.js')}}" type="text/javascript"></script>

<!-- end of page level js -->

<script>
$(function() {

	var dtInstance = $("#tableFlat").DataTable({
		"lengthMenu": [10, 25, 50, "ALL"],
		bLengthChange: false,
		mark: true,
		"aoColumns": [null,null,null,{ "bSortable": false },{ "bSortable": false } ],
		"aaSorting": [],
		//"order": [[ 1, "desc" ]]
		//"scrollX": true,
	});
	
});
function funDelete(id) {
	var con = confirm('Are you sure delete this flat master?');
	if(con==true) {
		var url = "{{ url('flatmaster/delete/') }}";
	 location.href = url+'/'+id;
	}
}

</script>
@stop