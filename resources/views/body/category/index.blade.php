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
                Category
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="fa fa-fw fa-home"></i> Inventory
                    </a>
                </li>
                <li>
                    <a href="#">Category</a>
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
                            <i class="fa fa-fw fa-list-alt"></i> Category List
                        </h3>
                        <div class="pull-right">
							<a href="{{ url('category/add') }}" class="btn btn-primary btn-sm">
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
                                    <th>Category Name</th>
									<th>Description</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                </tr>
                                </thead>
                                <tbody>
								@foreach($categories as $category)
                                <tr>
                                    <td><input type='checkbox' name='categoryid[]' class='chk-categoryid' value='{{$category->id}}'/></td>
                                    <td>{{ $category->category_name }}</td>
									<td>{{ $category->description }}</td>
                                    <td>
                                        <p>
											<button class="btn btn-primary btn-xs" onClick="location.href='{{ url('category/edit/'.$category->id) }}'"><span
                                                        class="glyphicon glyphicon-pencil"></span></button>
                                            
                                        </p>
                                    </td>
                                    <td>
                                        <p>
                                            <button class="btn btn-danger btn-xs delete" onClick="funDelete('{{ $category->id }}')"><span
                                                        class="glyphicon glyphicon-trash"></span></button>
                                        </p>
                                    </td>
                                </tr>
                                @endforeach
                                </tbody>
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
                        <h4 class="modal-title custom_align" id="Heading5">Delete this entry</h4>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <span class="glyphicon glyphicon-info-sign"></span>&nbsp; Are you sure you want to
                            delete this category?
                        </div>
                    </div>
                    <div class="modal-footer ">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">
                            <span class="glyphicon glyphicon-ok-sign"></span> Yes
                        </button>
                        <button type="button" class="btn btn-success" data-dismiss="modal">
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
	var con = confirm('Are you sure delete this category?');
	if(con==true) {
		var url = "{{ url('category/delete/') }}";
	 location.href = url+'/'+id;
	}
}
function btnDelete() {  
    
    var checked=false;
    var elements = document.getElementsByName("categoryid[]");
    for(var i=0; i < elements.length; i++){
        if(elements[i].checked) {
            checked = true;
        }
    }
    if (!checked) {
        alert('Please select atleast one category!');
        return checked;
    } else {
        var con = confirm('Are you sure delete these categories?');
        if(con==true) {
            var id = [];
            $("input[name='categoryid[]']:checked").each(function(){
                id.push($(this).val());
            }); 
           $('#chkids').val(id);

            document.frmUnits.action="{{url('category/group_delete') }}"
            document.frmUnits.submit();
        }
    }
}
</script>
@stop
