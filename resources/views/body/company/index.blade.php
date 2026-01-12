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
                Company
            </h1>
            <ol class="breadcrumb">
                <li>
                      <i class="fa fa-fw fa-shield"></i> Administration
                </li>
				<li>
                    <a href="#">Company</a>
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
                <div class="col-md-12">
                    <div class="panel panel-primary">
                         <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-crosshairs"></i>Company Profile
                            </h3>
                          
                        </div>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" method="POST" name="frmCompany" enctype="multipart/form-data" id="frmCompany" action="{{ url('company/update/'.$company->id) }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" name="id" value="{{ $company->id }}">
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Company Name</label>
                                    <div class="col-sm-10">
                                       <input type="text" class="form-control" id="company_name" name="company_name" value="{{ $company->company_name }}" placeholder="Category Name">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Email</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="email" name="email" value="{{ $company->email }}" placeholder="Email">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Phone</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone" value="{{ $company->phone }}">
                                    </div>
                                </div>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Address</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="address" name="address" placeholder="Address" value="{{ $company->address }}">
                                    </div>
                                </div>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">City</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="city" name="city" placeholder="City" value="{{ $company->city }}">
                                    </div>
                                </div>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">State</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="state" name="state" placeholder="State" value="{{ $company->state }}">
                                    </div>
                                </div>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Country</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="country" name="country" placeholder="Country" value="{{ $company->country }}">
                                    </div>
                                </div>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Pin</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="pin" name="pin" placeholder="Pin" value="{{ $company->pin }}">
                                    </div>
                                </div>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Website</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="website" name="website" placeholder="Website" value="{{ $company->website }}">
                                    </div>
                                </div>
   
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">TRN No</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="vat_no" name="vat_no" placeholder="TRN No" value="{{ $company->vat_no }}">
                                    </div>
                                </div>
                                 @if($company->logo != "")
                                 <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Current Logo</label>
                                    <div class="col-sm-10">
										<img src="{{ url('assets/'.$company->logo) }}">
										<input type="checkbox" name="delete_logo" class="removelogo" value="1"> Remove Logo
                                    </div>
                                    
                                    
                                </div>
                                
                                @endif
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Company Logo</label>
                                    <div class="col-sm-10">
                                    <input id="input-23" name="image" type="file" class="file-loading" data-show-preview="true" >
                                    <input type="hidden" name="image_logo" value="{{ $company->logo }}">
									 
                                    </div>
                                </div>

                                
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                         
                                    </div>
                                </div>
                            </form>
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

    $('#frmCompany').bootstrapValidator({
        fields: {
            company_name: {
                validators: {
                    notEmpty: {
                        message: 'The company name is required and cannot be empty!'
                    }
                }
            },
			email: {
                validators: {
                    notEmpty: {
                        message: 'The company name is required and cannot be empty!'
                    },
					regexp: {
                        regexp: /^\S+@\S{1,}\.\S{1,}$/,
                        message: 'Please enter valid email format'
                    }
                }
            },
			phone: {
                validators: {
                    notEmpty: {
                        message: 'The phone no is required and cannot be empty!'
                    }
                }
            },
			address: {
                validators: {
                    notEmpty: {
                        message: 'The address is required and cannot be empty!'
                    }
                }
            },
			city: {
                validators: {
                    notEmpty: {
                        message: 'The city is required and cannot be empty!'
                    }
                }
            },
			state: {
                validators: {
                    notEmpty: {
                        message: 'The state is required and cannot be empty!'
                    }
                }
            },
			country: {
                validators: {
                    notEmpty: {
                        message: 'The country is required and cannot be empty!'
                    }
                }
            },
            image: { validators: {
						file: {
							  extension: 'jpg,jpeg,png,gif',
							  type: 'image/jpg,image/jpeg,image/png,image/gif',
							  maxSize: 5*1024*1024,   // 5 MB
							  message: 'The selected file is not valid, it should be (jpg,jpeg,png,gif) and 5 MB at maximum.'
						}
					}
			},
			pin: {
                validators: {
                    notEmpty: {
                        message: 'The pin code is required and cannot be empty!'
                    }
                }
            }
          
        },
        submitHandler: function (validator, form, submitButton) {
            var fullName = [validator.getFieldElements('company_name').val(),
                validator.getFieldElements('company_name').val()
            ].join(' ');
            $('#helloModal')
                .find('.modal-title').html('Hello ' + fullName).end()
                .modal();
        }
    }).on('reset', function (event) {
        $('#frmCompany').data('bootstrapValidator').resetForm();
    });
});

$(function() {
    $('.removelogo').on('ifChecked', function(event){ 
		 if(this.value==1)
			 alert('Are you sure delete this logo?');
     });
});
</script>
@stop
