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
                Role
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="">
                       <i class="fa fa-fw fa-key"></i> Role Management
                    </a>
                </li>
                <li>
                    <a href="#">Role</a>
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
                            <i class="fa fa-fw fa-list-alt"></i> Role List
                        </h3>
                        <div class="pull-right">
                            <a href="{{ url('user/add') }}" class="btn btn-primary btn-sm">
									<span class="btn-label">
									<i class="glyphicon glyphicon-plus"></i>
								</span> Add New
							</a>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                                <table class="table table-striped" id="tableJobmaster">
                                    <thead>
                                    <tr>
                                        <th>No</th>
										<th>Name</th>
										<th>Description</th>
										<th></th>
										<th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
									@foreach($roles as $role)
                                    <tr>
                                        <td>{{ ++$i }}</td>
										<td>{{ $role->display_name  }}</td>
										<td>{{ $role->description }}</td>
										<td>
											<p>
												<button class="btn btn-primary btn-xs" onClick="location.href='{{ url('user/edit/'.$role->id) }}'">
												<span class="glyphicon glyphicon-pencil"></span></button>
											</p>
										</td>
										<td>
											<p>
												<button class="btn btn-danger btn-xs delete" onClick="funDelete('{{ $role->id }}')"><span
															class="glyphicon glyphicon-trash"></span></button>
											</p>
										</td>
                                    </tr>
									@endforeach
                                    </tbody>
                                </table>
                            </div>
                    </div>
                </div>
            </div>
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
	var con = confirm('Are you sure delete this user?');
	if(con==true) {
		var url = "{{ url('user/delete/') }}";
	 location.href = url+'/'+id;
	}
}
</script>

@stop
