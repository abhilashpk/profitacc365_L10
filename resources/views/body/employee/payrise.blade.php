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
    
    <link rel="stylesheet" href="{{asset('assets/vendors/datetime/css/jquery.datetimepicker.css')}}">
    <link href="{{asset('assets/vendors/airdatepicker/css/datepicker.min.css')}}" rel="stylesheet" type="text/css">
	
	
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/buttons.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/colReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/dataTables.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/rowReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/scroller.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatablesmark.js/css/datatables.mark.min.css')}}"/>
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/responsive_datatables.css')}}">
        <!--end of page level css-->
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <!--section starts-->
            <h1>
                Pay Rise
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="">
                       <i class="fa fa-fw fa-building-o"></i> HR Managemant
                    </a>
                </li>
                <li>
                    <a href="#">Employee</a>
                </li>
                <li class="active">
                    Pay Rise
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
                                <i class="fa fa-fw fa-crosshairs"></i> Employee Pay Rise
                            </h3>
                           
                        </div>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" method="POST" name="frmEmployeepay" id="frmEmployeepay" action="{{url('employee/payrise/update')}}">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" name="employee_id" value="{{$erow->id}}">	
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">Previous Basic Pay </label>
										<div class="col-sm-4">
											<input type="text" name="basicpay_old" id="basicpay_old" readonly value="{{$erow->basic_pay}}" class="form-control"  placeholder="Previous Basic Pay">
											
										</div>
										<label for="input-text" class="col-sm-2 control-label">Current Basic Pay </label>
											<div class="col-sm-4">
											<input type="text" name="basicpay_new" id="basicpay_new"  value="" class="form-control salcal"  placeholder="Current Basic Pay">
											
										</div>
									</div>
								
									<div class="form-group">
											<label for="input-text" class="col-sm-2 control-label">Previous HRA </label>
										<div class="col-sm-4">
											<input type="text" name="hra_old" id="hra_old" readonly value="{{$erow->hra}}" class="form-control"  placeholder="Previous HRA">
											
										</div>
										<label for="input-text" class="col-sm-2 control-label">Current HRA </label>
											<div class="col-sm-4">
											<input type="text" name="hra_new" id="hra_new"  value="" class="form-control salcal"  placeholder="Current HRA">
											
										</div>
									</div>
									
									<div class="form-group">
											<label for="input-text" class="col-sm-2 control-label">Previous Transport </label>
										<div class="col-sm-4">
											<input type="text" name="transport_old" id="transport_old" readonly value="{{$erow->transport}}" class="form-control"  placeholder="Previous Transport">
											
										</div>
										<label for="input-text" class="col-sm-2 control-label">Current Transport </label>
											<div class="col-sm-4">
											<input type="text" name="transport_new" id="transport_new"  value="" class="form-control salcal"  placeholder="Current Transport">
											
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">Previous Allowance1</label>
										<div class="col-sm-4">
											<input type="text" name="allowance1_old" id="allowance1_old" readonly value="{{$erow->allowance}}" class="form-control"  placeholder="Previous Allowance1">
											
										</div>
										<label for="input-text" class="col-sm-2 control-label">Current Allowance1 </label>
											<div class="col-sm-4">
											<input type="text" name="allowance1_new" id="allowance1_new"  value="" class="form-control salcal"  placeholder="Current Allowance1">
											
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">Previous Allowance2</label>
										<div class="col-sm-4">
											<input type="text" name="allowance2_old" id="allowance2_old" readonly value="{{$erow->allowance2}}" class="form-control"  placeholder="Previous Allowance2">
											
										</div>
										<label for="input-text" class="col-sm-2 control-label">Current Allowance2 </label>
											<div class="col-sm-4">
											<input type="text" name="allowance2_new" id="allowance2_new"  value="" class="form-control salcal"  placeholder="Current Allowance2">
											
										</div>
									</div>
									
									<div class="form-group">
											<label for="input-text" class="col-sm-2 control-label">Previous NetSalary</label>
										<div class="col-sm-4">
											<input type="text" name="netsalary_old" id="netsalary_old" readonly value="{{$erow->net_salary}}" class="form-control"  placeholder="Previous NetSalary">
											
										</div>
										<label for="input-text" class="col-sm-2 control-label">Current NetSalary </label>
											<div class="col-sm-4">
											<input type="text" name="netsalary_new" id="netsalary_new"  value="" class="form-control"  placeholder="Current Netsalary">
											
										</div>
									</div>
									<div class="form-group">
											<label for="input-text" class="col-sm-2 control-label">Updating Date</label>
										<div class="col-sm-10">
											<input type="text" class="form-control pull-right" name="update_date" value="" autocomplete="off" required data-language='en' id="update_date" placeholder="{{date('d-m-Y')}}"/>
										</div>
									</div>	
									
									<div class="form-group">
											<label for="input-text" class="col-sm-2 control-label">Remarks</label>
										<div class="col-sm-10">
											<input type="text" name="remarks" id="remarks" value="" class="form-control"  placeholder="Remarks">
											
										</div>
									</div>	
								
									<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                         <a href="{{ url('employee') }}" class="btn btn-danger">Cancel</a>
                                    </div>
                                </div>
									
								 </form>
								 
								 
                                </div>
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
<script type="text/javascript" src="{{asset('assets/vendors/custom_js/form_elements.js')}}"></script>

<script src="{{asset('assets/vendors/datetime/js/jquery.datetimepicker.full.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.en.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/custom_js/advanceddate_pickers.js')}}"></script>


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
"use strict";
$('#update_date').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy' } );
$(document).ready(function () {

});

$(function() {
    
    $(document).on('blur', '.salcal', function(e) {
		var net_sal = 0;
		$( '.salcal' ).each(function() {
			net_sal = net_sal + parseFloat((this.value=='') ? 0 : this.value);
		});
		
		$('#netsalary_new').val(net_sal);
	});
    
});
</script>
@stop
