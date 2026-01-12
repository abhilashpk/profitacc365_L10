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
                Users
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="">
                        <i class="fa fa-fw fa-group"></i> User Management
                    </a>
                </li>
                <li>
                    <a href="#">Users</a>
                </li>
                <li class="active">
                    Edit User
                </li>
            </ol>
        </section>
        <!--section ends-->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-crosshairs"></i> Edit User 
                            </h3>
                           
                        </div>
                        <div class="panel-body">
							{!! Form::model(['method' => 'PATCH','route' => ['users.update', $user->id]]) !!}
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-12">
									<div class="form-group">
										<strong>Name:</strong>
										{!! Form::text('name', $user->name, array('placeholder' => 'Name','class' => 'form-control')) !!}
									</div>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-12">
									<div class="form-group">
										<strong>Email:</strong>
										{!! Form::text('email',  $user->email, array('placeholder' => 'Email','class' => 'form-control')) !!}
									</div>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-12">
									<div class="form-group">
										<strong>Password:</strong>
										{!! Form::password('password', array('placeholder' => 'Password','class' => 'form-control')) !!}
									</div>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-12">
									<div class="form-group">
										<strong>Confirm Password:</strong>
										{!! Form::password('confirm-password', array('placeholder' => 'Confirm Password','class' => 'form-control')) !!}
									</div>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-12">
									<div class="form-group">
										<strong>Role:</strong>
										{!! Form::select('roles[]', $roles,$userRole, array('class' => 'form-control')) !!}
									</div>
								</div>
								
								<div class="col-xs-12 col-sm-12 col-md-12">
									<div class="form-group">
										<strong>Department:</strong>
										<select id="roles" class="form-control select2" style="width:100%" name="department_id">
                                            <option value="">Department None</option>
											@foreach ($depts as $row)
											<option value="{{$row->id}}" <?php echo ($user->department_id==$row->id)?'selected':'';?>>{{ $row->name }}</option>
											@endforeach
                                        </select>
									</div>
								</div>
								
								<div class="col-xs-12 col-sm-12 col-md-12">
									<div class="form-group">
										<strong>Location:</strong>
										<select id="roles" class="form-control select2" style="width:100%" name="location_id">
                                            <option value="">All Location</option>
											@foreach ($loc as $row)
											<option value="{{$row->id}}" <?php echo ($user->location_id==$row->id)?'selected':'';?>>{{ $row->name }}</option>
											@endforeach
                                        </select>
									</div>
								</div>
								
								<div class="col-xs-12 col-sm-12 col-md-12 text-center">
										<button type="submit" class="btn btn-primary">Submit</button>
								</div>
							</div>
							{!! Form::close() !!}
							<!--</form>-->
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
<script type="text/javascript" src="{{asset('assets/vendors/custom_js/form_elements.js')}}"></script>
        <!-- end of page level js -->

<script>
"use strict";

$(document).ready(function () {
	var url = "{{ url('unit/checkname/') }}";
    $('#frmUnit').bootstrapValidator({
        fields: {
            unit_name: {
                validators: {
                    notEmpty: {
                        message: 'The unit name is required and cannot be empty!'
                    },
					remote: {
                        url: url,
                        data: function(validator) {
                            return {
                                unit_name: validator.getFieldElements('unit_name').val()
                            };
                        },
                        message: 'The unit name is not available'
                    }
                }
            }
          
        }
        
    }).on('reset', function (event) {
        $('#frmUnit').data('bootstrapValidator').resetForm();
    });
});
</script>
@stop
