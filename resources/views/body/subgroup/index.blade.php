@extends('layouts/default')

    {{-- Page title --}}
    @section('title')
         
        @parent
    @stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/iCheck/css/all.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrap-fileinput/css/fileinput.min.css')}}" media="all" />
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/formelements.css')}}">
        <!--end of page level css-->
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <!--section starts-->
            <h1>
                Sub Group
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="">
                        <i class="fa fa-fw fa-home"></i> Inventory
                    </a>
                </li>
                <li>
                    <a href="#">Sub Group</a>
                </li>
                
            </ol>
			
        </section>
		
        <!--section ends-->
		@if(Session::has('message'))
		<div class="alert alert-success">
			<p>{{ Session::get('message') }}</p>
		</div>
		@endif
		
        <section class="content">
            <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-info">
                    <div class="panel-heading clearfix">
                        <h3 class="panel-title pull-left m-t-6">
                            <i class="fa fa-fw fa-list-alt"></i> Sub Group List
                        </h3>
                        <div class="pull-right">
                             <a href="{{ url('subgroup/add') }}" class="btn btn-primary btn-sm">
									<span class="btn-label">
									<i class="glyphicon glyphicon-plus"></i>
								</span> Add New
							</a>
                            <button type="button" class="btn btn-primary btn-sm" onClick="btnDelete()" data-toggle="dropdown" aria-expanded="false">
                                <span class='glyphicon glyphicon-trash'></span> Remove
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table id="mytable" class="table table-bordred table-striped">
                                <thead>
                                <tr>
                                    <th>Select</th>
                                    <th>Sub Group Name</th>
									<th>Description</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                </tr>
                                </thead>
                                <tbody>
								@foreach($subgroups as $subgroup)
                                <tr>
                                    <td><input type='checkbox' name='subgroupid[]' class='chk-subgroupid' value='{{$subgroup->id}}'/></td>
                                    <td>{{ $subgroup->group_name }}</td>
									<td>{{ $subgroup->description }}</td>
                                    <td>
                                        <p>
                                            <button class="btn btn-primary btn-xs" onClick="location.href='{{ url('subgroup/edit/'.$subgroup->id) }}'"><span
                                                        class="glyphicon glyphicon-pencil"></span></button>
                                        </p>
                                    </td>
                                    <td>
                                        <p>
													
											<button class="btn btn-danger btn-xs delete" onClick="funDelete('{{ $subgroup->id }}')"><span
                                                        class="glyphicon glyphicon-trash"></span></button>
                                        </p>
                                    </td>
                                </tr>
                                @endforeach
								@if (count($subgroups) === 0)
                                </tbody>
								<tbody><tr class="odd danger"><td valign="top" colspan="4" class="dataTables_empty">No matching records found</td></tr></tbody>
								@endif
                            </table>
                            <form class="form-horizontal" role="form" method="POST" name="frmUnits" id="frmUnits">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="ids" id="chkids">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title custom_align" id="Heading5">Delete Group</h4>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <span class="glyphicon glyphicon-info-sign"></span>&nbsp; Are you sure delete this group?
                        </div>
                    </div>
                    <div class="modal-footer ">
                        <!--<button type="button" class="btn btn-danger" data-dismiss="modal" value="Yes">-->
						<button class="btn btn-danger" value="Yes" id="btndelete">
                            <span class="glyphicon glyphicon-ok-sign"></span> Yes
                        </button>
						
                        <button type="button" class="btn btn-success" data-dismiss="modal" value="No">
                            <span class="glyphicon glyphicon-remove"></span> No
                        </button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
       
            <!--main content-->
            <!-- row -->
        @include('layouts.right_sidebar')
        <!-- right side bar end -->
        </section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <!-- begining of page level js -->
<script type="text/javascript" src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/iCheck/js/icheck.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-fileinput/js/fileinput.min.js')}}"></script>
<!-- end of page level js -->
<script>
function funDelete(id) {
	var con = confirm('Are you sure delete this sub group?');
	if(con==true) {
		var url = "{{ url('subgroup/delete/') }}";
	 location.href = url+'/'+id;
	}
}
/* $("#delete").click(function(){ console.log($(this).data("id")); //
	if(this.value=='Yes') { 
		
	}
}); 
$('your-button').click(function(){
    var ID = $(this).data('id');
});*/
/* $( ".delete" ).click(function() { 
	var ID = $(this).data('id');
	$('#btndelete').data('id', ID);
}); */
function btnDelete() {  
    
    var checked=false;
    var elements = document.getElementsByName("subgroupid[]");
    for(var i=0; i < elements.length; i++){
        if(elements[i].checked) {
            checked = true;
        }
    }
    if (!checked) {
        alert('Please select atleast one subgroup!');
        return checked;
    } else {
        var con = confirm('Are you sure delete these subgroups?');
        if(con==true) {
            var id = [];
            $("input[name='subgroupid[]']:checked").each(function(){
                id.push($(this).val());
            }); 
           $('#chkids').val(id);

            document.frmUnits.action="{{ url('subgroup/group_delete') }}"
            document.frmUnits.submit();
        }
    }
}
</script>

@stop
