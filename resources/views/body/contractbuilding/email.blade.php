@extends('layouts/default')

    {{-- Page title --}}
    @section('title')
         
        @parent
    @stop

{{-- page level styles --}}
@section('header_styles')
	<link href="{{asset('assets/vendors/bootstrap3-wysihtml5-bower/css/bootstrap3-wysihtml5.min.css')}}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" media="screen" type="text/css" href="{{asset('assets/vendors/summernote/summernote.css')}}">
    <link href="{{asset('assets/vendors/trumbowyg/css/trumbowyg.min.css')}}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/form_editors.css')}}">
	
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
                Email
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="">
                        <i class="fa fa-fw fa-wrench"></i> Email
                    </a>
                </li>
                <li>
                    <a href="#">Email</a>
                </li>
                <li class="active">
                   Send Mail
                </li>
            </ol>
        </section>
        @if(Session::has('message'))
        <div class="alert alert-success">
            <p>{{ Session::get('message') }}</p>
        </div>
        @endif
        <!--section ends-->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-crosshairs"></i> Renewal Letter
                            </h3>
                           
                        </div>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" method="POST" name="frmemail" id="frmemail" action="{{ url('contractbuilding/sendingmail') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            
                                <div class="form-group">
                                <label for="input-text" class="col-sm-2 control-label">Flat No:</label>
                                    <div class="col-sm-4">
                                    <input type="text" class="form-control" id="flat" name="flat" value="{{($mid)?$mid[0]->flat:''}}" readonly >
                                      </div>
                                    <label for="input-text" class="col-sm-2 control-label">Tenant</label>
                                    <div class="col-sm-4">
                                    <input type="text" class="form-control" id="tenant" name="tenant" value="{{($mid)?$mid[0]->master_name:''}}" readonly >
                                    </div>
                                </div>

                                     <!-- <div class="col-sm-10">
                                        <textarea name="description" id="description" class="form-control editor-cls" placeholder="Description"></textarea>
                                    </div> -->
                              
                               
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Expiry Date</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="expiry_date" name="expiry_date" value="{{($mid)?date('d-m-Y',strtotime($mid[0]->end_date)):''}}" readonly >
                                    </div>
                                 </div>
                                 <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Current Period Amount:</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="rent_amount" name="rent_amount" value="{{($mid)?$mid[0]->rent_amount:''}}" readonly >
                                    </div>
                                 </div>
                                 <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Increased Amount</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="incre_amount" name="incre_amount"  >
                                    </div>
                                 </div>
                                 <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Description</label>
                                  
                                    <div class="col-sm-10">
                                        <textarea name="description" id="description" class="form-control " ></textarea>
                                    </div> 
                                </div>
                                
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                        <a href="{{ url('contractbuilding') }}" class="btn btn-danger">Cancel</a>
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
<script src="{{asset('assets/vendors/bootstrap3-wysihtml5-bower/js/bootstrap3-wysihtml5.all.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/trumbowyg/js/trumbowyg.js')}}" type="text/javascript"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap3-wysihtml5-bower/js/bootstrap3-wysihtml5.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/summernote/summernote.min.js')}}"></script>
<script src="{{asset('assets/js/custom_js/form_editors.js')}}" type="text/javascript"></script>
    <!-- begining of page level js -->
<script type="text/javascript" src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/iCheck/js/icheck.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-fileinput/js/fileinput.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/custom_js/form_elements.js')}}"></script>
        <!-- end of page level js -->

<script>
"use strict";

$(document).ready(function () {
    $('#frmHeaderFooter').bootstrapValidator({
        fields: {
            is_header: {
                validators: {
                    notEmpty: {
                        message: 'The Header/footer is required and cannot be empty!'
                    }
                }
            },
			title: {
                validators: {
                    notEmpty: {
                        message: 'The title is required and cannot be empty!'
                    }
                }
            },
			description: {
                validators: {
                    notEmpty: {
                        message: 'The description is required and cannot be empty!'
                    }
                }
            }
          
        }
        
    }).on('reset', function (event) {
        $('#frmHeaderFooter').data('bootstrapValidator').resetForm();
    });
});
</script>
@stop
