<link rel="stylesheet" type="text/css" href="{{asset('assets/css/app.css')}}"/>
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
<!--page level css -->
<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/buttons.bootstrap.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/colReorder.bootstrap.css')}}"/>
<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/dataTables.bootstrap.css')}}"/>
<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/rowReorder.bootstrap.css')}}"/>
<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/scroller.bootstrap.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatablesmark.js/css/datatables.mark.min.css')}}"/>
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/responsive_datatables.css')}}">

<div class="panel panel-success filterable">
	<div class="panel-heading">
		<h3 class="panel-title">
			<i class="fa fa-fw fa-columns"></i> Bin List
		</h3>
	</div>
	 <div class="panel-body">
	<input type="hidden" name="num" id="num" value="{{$num}}">
	<div class="table-responsive m-t-10"> <button type="button" class="btn btn-primary createItm">Create Bin</button>
		<table class="table horizontal_table table-striped" id="table5">
			<thead>
			<tr>
				<th>Code</th>
				<th>Name</th>
			</tr>
			</thead>
			@foreach($binloc as $row)
			<tr>
				<td><a href="" calss="binRow" data-code="{{$row->code}}" data-dismiss="modal">{{$row->code}}</a></td>
				<td><a href="" calss="binRow" data-code="{{$row->code}}" data-dismiss="modal">{{$row->name}}</a></td>
			</tr>
			@endforeach
		</table>

	</div>
	</div>
</div>
<script src="{{asset('assets/js/app.js')}}" type="text/javascript"></script>
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

<script>
$(function() {
	
	
	
	$('#table5').DataTable({
		filter: true,
		deferRender: true,
		info: false,
		ordering: false,
		//paging: false,
		"searching": true,
	});
           

		
});
</script>