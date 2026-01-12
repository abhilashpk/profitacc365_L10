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
	
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/datepicker.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/wizard.css')}}" rel="stylesheet">
	 
        <!--end of page level css-->
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <!--section starts-->
            <h1>
                Employee
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                       <i class="glyphicon glyphicon-tower"></i>  HR Management
                    </a>
                </li>
                <li>
                    <a href="#">Employee</a>
                </li>
                <li class="active">
                    Add New
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
                                <i class="fa fa-fw fa-crosshairs"></i> New Employee
                            </h3>
                           
                        </div>
                        <div class="panel-body">
                            <form class="form-horizontal" enctype="multipart/form-data" role="form" method="POST" name="frmEmployee" id="frmEmployee" action="#">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
								<div id="rootwizard">
                                    <ul>
                                        <li>
                                            <a href="#tab1" data-toggle="tab">Personal Details</a>
                                        </li>
                                         <li>
                                            <a href="#tab2" data-toggle="tab">WPS Details</a>
                                        </li>
                                        <li>
                                            <a href="#tab3" data-toggle="tab">Employment Details</a>
                                        </li>
                                        <li>
                                            <a href="#tab4" data-toggle="tab">Salary Details</a>
                                        </li>
										<li>
                                            <a href="#tab5" data-toggle="tab">Other Details</a>
                                        </li>
                                       
                                    </ul>
									
                                    <div class="tab-content">
                                        <div class="tab-pane" id="tab1">
                                            <h2 class="hidden">&nbsp;</h2>

                                            <div class="form-group">
                                    			<label for="input-text" class="col-sm-2 control-label"><b>Department</b></label>
                                                <div class="col-sm-10">
                                                   <select id="department_id" class="form-control select2" style="width:100%; background-color:#85d3ef;" name="department_id">
                                                     	<option>Select Department</option>
											            @foreach($departments as $drow)
												           <option value="{{ $drow->id }}" >{{ $drow->name }}</option>
											          @endforeach
                                                   </select>
                                               </div>
                                            </div>

                                   
                                           
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Employee Code </label>
												<div class="col-sm-10">
													<input type="text" class="form-control required" id="code" name="code" placeholder="Employee Code">
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Employee Name </label>
												<div class="col-sm-10">
													<input type="text" class="form-control" id="name" name="name"  placeholder="Employee Name">
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Designation </label>
												<div class="col-sm-10">
													<input type="text" class="form-control" id="designation" name="designation" placeholder="Designation">
												</div>
											</div>
											
											<div class="form-group">
                                    			<label for="input-text" class="col-sm-2 control-label"><b>Division</b></label>
                                                <div class="col-sm-10">
                                                   <select id="division_id" class="form-control select2" style="width:100%; background-color:#85d3ef;" name="division_id">
                                                     	<option>Select Division</option>
											            @foreach($divisions as $drow)
												           <option value="{{ $drow->id }}" >{{ $drow->div_name }}</option>
											          @endforeach
                                                   </select>
                                               </div>
                                            </div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Nationality </label>
												<div class="col-sm-10">
													<input type="text" class="form-control" id="nationality" name="nationality" placeholder="Nationality">
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Date of Birth</label>
												<div class="col-sm-10">
													 <input type="text" class="form-control" name="dob" id="dob" readonly data-language='en' />
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Gender</label>
												<div class="col-sm-10">
													<label class="radio-inline iradio">
														<input type="radio" id="inlineradio1" name="gender" value="1" checked>
														Male
													</label>
													<label class="radio-inline iradio">
														<input type="radio" id="inlineradio2" name="gender" value="0">
														Female
													</label>
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Residance Address</label>
												<div class="col-sm-10">
													<input type="text" class="form-control" id="address1" name="address1" placeholder="Residance Address">
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Residance Phone No</label>
												<div class="col-sm-10">
													<input type="text" class="form-control" id="phone" name="phone" placeholder="Residance Phone No">
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Home Address</label>
												<div class="col-sm-10">
													<input type="text" class="form-control" id="address2" name="address2"  placeholder="Home Address">
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Home Phone No</label>
												<div class="col-sm-10">
													<input type="text" class="form-control" id="phone2" name="phone2" placeholder="Home Phone No">
												</div>
											</div>
									
							
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Email</label>
												<div class="col-sm-10">
													<input type="email" class="form-control" id="email" name="email"  placeholder="Email">
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Join Date</label>
												<div class="col-sm-10">
													<input type="text" class="form-control pull-right" id="join_date" name="join_date" data-language='en' readonly>
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Rejoin Date</label>
												<div class="col-sm-10">
													<input type="text" class="form-control pull-right" id="rejoin_date" name="rejoin_date" data-language='en' readonly>
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Photo</label>
												<div class="col-sm-10">
													<!--<input id="input-23" name="photo" type="file" class="file-loading" data-show-preview="true">-->
													<input type="file" id="input-23" name="photos" class="file-loading" data-show-preview="true" data-url="{{url('employee/upload/')}}" multiple="">
													<div id="files_list"></div>
													<p id="loading"></p>
													<input type="hidden" name="photo_name" id="photo_name">
												</div>
											</div>
								
                                        </div>
                                        
                                        
                                        
                                        
                                        
                                        
                                        
                                        
                                <div class="tab-pane" id="tab2">
                                    	<input type="hidden" id="emp_id" name="emp_id" />
                                            <h2 class="hidden">&nbsp;</h2>

                                            
                                   
                                           
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Routing Code </label>
												<div class="col-sm-10">
													<input type="text" class="form-control" id="routing_code" name="routing_code" placeholder="Routing Code">
												</div>
											</div>
											
												<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Account Number </label>
												<div class="col-sm-10">
													<input type="text" class="form-control" id="account_number" name="account_number" placeholder="Account Number">
												</div>
											</div>
											
												<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">MOL-File Card No.</label>
												<div class="col-sm-10">
													<input type="text" class="form-control" id="mol_no" name="mol_no" placeholder="MOL-File Card No.">
												</div>
											</div>
											
											
									</div>
											
										
								
                                       
                                        
                                        
                                        
                                        
                                        <div class="tab-pane" id="tab3">
										
											 
                                            <h2 class="hidden">&nbsp;</h2>
                                            <div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Passport ID</label>
												<div class="col-sm-10">
													 <input type="text" class="form-control" id="pp_id" name="pp_id" placeholder="Passport ID">
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Issue Date</label>
												<div class="col-sm-10">
													 <input type="text" class="form-control" id="pp_issue_date" name="pp_issue_date" data-language='en' readonly>
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Expiry Date</label>
												<div class="col-sm-10">
													 <input type="text" class="form-control" id="pp_expiry_date" name="pp_expiry_date" data-language='en' readonly>
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Issued Place</label>
												<div class="col-sm-10">
													 <input type="text" class="form-control" id="pp_issue_place" name="pp_issue_place" placeholder="Issued Place">
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Passport Image</label>
												<div class="col-sm-10">
													<input type="file" id="input-23p" name="pimage" class="file-loading" data-show-preview="true" data-url="{{url('employee/pupload/')}}" multiple="">
													<div id="files_list"></div>
													<p id="ploading"></p>
													<input type="hidden" name="pimage_name" id="pimage_name">
												</div>
											</div>
											
											<hr/>
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Visa Designation</label>
												<div class="col-sm-10">
													 <input type="text" class="form-control" id="v_designation" name="v_designation" placeholder="Visa Designation">
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Visa ID</label>
												<div class="col-sm-10">
													 <input type="text" class="form-control" id="v_id" name="v_id" placeholder="Visa ID">
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Issue Date</label>
												<div class="col-sm-10">
													 <input type="text" class="form-control" id="v_issue_date" name="v_issue_date" data-language='en' readonly>
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Expiry Date</label>
												<div class="col-sm-10">
													 <input type="text" class="form-control" id="v_expiry_date" name="v_expiry_date" data-language='en' readonly>
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Visa Image</label>
												<div class="col-sm-10">
													<input type="file" id="input-23v" name="vimage" class="file-loading" data-show-preview="true" data-url="{{url('employee/vupload/')}}" multiple="">
													<div id="files_list"></div>
													<p id="vloading"></p>
													<input type="hidden" name="vimage_name" id="vimage_name">
												</div>
											</div>
											
											<hr/>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Labour Card ID</label>
												<div class="col-sm-10">
													 <input type="text" class="form-control" id="lc_id" name="lc_id" placeholder="Labour Card ID">
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Issue Date</label>
												<div class="col-sm-10">
													 <input type="text" class="form-control" id="lc_issue_date" name="lc_issue_date" data-language='en' readonly>
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Expiry Date</label>
												<div class="col-sm-10">
													 <input type="text" class="form-control" id="lc_expiry_date" name="lc_expiry_date" data-language='en' readonly>
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Labour Card Image</label>
												<div class="col-sm-10">
													<input type="file" id="input-23l" name="limage" class="file-loading" data-show-preview="true" data-url="{{url('employee/lupload/')}}" multiple="">
													<div id="files_list"></div>
													<p id="lloading"></p>
													<input type="hidden" name="limage_name" id="limage_name">
												</div>
											</div>
											
											<hr/>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Health Card ID</label>
												<div class="col-sm-10">
													 <input type="text" class="form-control" id="hc_id" name="hc_id" placeholder="Health Card ID">
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Issue Date</label>
												<div class="col-sm-10">
													 <input type="text" class="form-control" id="hc_issue_date" name="hc_issue_date" data-language='en' readonly>
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Expiry Date</label>
												<div class="col-sm-10">
													 <input type="text" class="form-control" id="hc_expiry_date" name="hc_expiry_date" data-language='en' readonly>
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Health Card Info </label>
												<div class="col-sm-10">
													 <input type="text" class="form-control" id="hc_info" name="hc_info" placeholder="Health Card Info">
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Health Card Image</label>
												<div class="col-sm-10">
													<input type="file" id="input-23h" name="himage" class="file-loading" data-show-preview="true" data-url="{{url('employee/hupload/')}}" multiple="">
													<div id="files_list"></div>
													<p id="hloading"></p>
													<input type="hidden" name="himage_name" id="himage_name">
												</div>
											</div>
											
											<hr/>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">ID Card ID</label>
												<div class="col-sm-10">
													 <input type="text" class="form-control" id="ic_id" name="ic_id" placeholder="ID Card ID">
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Issue Date</label>
												<div class="col-sm-10">
													 <input type="text" class="form-control" id="ic_issue_date" name="ic_issue_date" data-language='en' readonly>
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Expiry Date</label>
												<div class="col-sm-10">
													 <input type="text" class="form-control" id="ic_expiry_date" name="ic_expiry_date" data-language='en' readonly>
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">ID Card Image</label>
												<div class="col-sm-10">
													<input type="file" id="input-23i" name="iimage" class="file-loading" data-show-preview="true" data-url="{{url('employee/iupload/')}}" multiple="">
													<div id="files_list"></div>
													<p id="iloading"></p>
													<input type="hidden" name="iimage_name" id="iimage_name">
												</div>
											</div>
											
											<hr/>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Medical Exam ID</label>
												<div class="col-sm-10">
													 <input type="text" class="form-control" id="me_id" name="me_id" placeholder="Medical Exam ID">
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Issue Date</label>
												<div class="col-sm-10">
													 <input type="text" class="form-control" id="me_issue_date" name="me_issue_date" data-language='en' readonly>
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Expiry Date</label>
												<div class="col-sm-10">
													 <input type="text" class="form-control" id="me_expiry_date" name="me_expiry_date" data-language='en' readonly>
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Exam Image</label>
												<div class="col-sm-10">
													<input type="file" id="input-23me" name="meimage" class="file-loading" data-show-preview="true" data-url="{{url('employee/meupload/')}}" multiple="">
													<div id="files_list"></div>
													<p id="meloading"></p>
													<input type="hidden" name="meimage_name" id="meimage_name">
												</div>
											</div>
											
											
                                        </div>
                                        <div class="tab-pane" id="tab4">
											<input type="hidden" id="frm" name="frm" value="1" />
                                            <div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Contract Status</label>
												<div class="col-sm-10">
													<label class="radio-inline iradio">
														 <input type="radio" id="inlineradio2" name="contract_status" value="1"> Limited 
													</label>
													<label class="radio-inline iradio">
														<input type="radio" id="inlineradio3" checked name="contract_status" value="2">
														Unlimited
													</label>
												</div>
											</div>
											
											

											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Basic Pay</label>
												<div class="col-sm-6">
													 <input type="number" step="any" class="form-control salcal" id="basic_pay" name="basic_pay" placeholder="Basic Pay"> 
												</div>
												<div class="col-sm-2">
													<input type="checkbox" id="basic_pay_nw" name="basic_pay_nw" checked value="1">Normal Wage
												</div>
												<div class="col-sm-2">
													<input type="checkbox" id="basic_pay_otw" name="basic_pay_otw" checked value="1">OT Wage
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">HRA</label>
												<div class="col-sm-6">
													 <input type="number" step="any" class="form-control salcal" id="hra" name="hra" placeholder="HRA">
												</div>
												<div class="col-sm-2">
													<input type="checkbox" id="hra_nw" name="hra_nw" value="1">Normal Wage
												</div>
												<div class="col-sm-2">
													<input type="checkbox" id="hra_otw" name="hra_otw" value="1">OT Wage
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Transport</label>
												<div class="col-sm-6">
													 <input type="number" step="any" class="form-control salcal" id="transport" name="transport" placeholder="Transport">
												</div>
												<div class="col-sm-2">
													<input type="checkbox" id="transport_nw" name="transport_nw" value="1">Normal Wage
												</div>
												<div class="col-sm-2">
													<input type="checkbox" id="transport_otw" name="transport_otw" value="1">OT Wage
												</div>
											</div>
										

										
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Allowance1</label>
												<div class="col-sm-6">
													 <input type="number" step="any" class="form-control salcal" id="allowance" name="allowance" placeholder="Allowance1">
												</div>
												<div class="col-sm-2">
													<input type="checkbox" id="allowance1_nw" name="allowance1_nw" value="1">Normal Wage
												</div>
												<div class="col-sm-2">
													<input type="checkbox" id="allowance1_otw" name="allowance1_otw" value="1">OT Wage
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Allowance2</label>
												<div class="col-sm-6">
													 <input type="number" step="any" class="form-control salcal" id="allowance2" name="allowance2" placeholder="Allowance2">
												</div>
												<div class="col-sm-2">
													<input type="checkbox" id="allowance2_nw" name="allowance2_nw" value="1">Normal Wage
												</div>
												<div class="col-sm-2">
													<input type="checkbox" id="allowance2_otw" name="allowance2_otw" value="1">OT Wage
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Net Salary</label>
												<div class="col-sm-6">
													 <input type="number" step="any" class="form-control" id="net_salary" name="net_salary" readonly>
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Normal Workign Hr.</label>
												<div class="col-sm-6">
													 <input type="number" step="any" class="form-control" id="nwh" name="nwh" value="8">
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Normal Wage by</label>
												<div class="col-sm-10">
													<label class="radio-inline iradio">
														 <input type="radio" id="inlineradio3" checked name="nwc" value="30"> 30 Days 
													</label>
													<label class="radio-inline iradio">
														<input type="radio" id="inlineradio4" name="nwc" value="365">
														365 Days
													</label>
													<label class="radio-inline iradio">
														<input type="radio" id="inlineradio5" name="nwc" value="monthly">
														Monthly
													</label>
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">OT Wage by</label>
												<div class="col-sm-10">
													<label class="radio-inline iradio">
														 <input type="radio" id="inlineradio3" checked name="otwc" value="30"> 30 Days 
													</label>
													<label class="radio-inline iradio">
														<input type="radio" id="inlineradio4" name="otwc" value="365">
														365 Days
													</label>
													<label class="radio-inline iradio">
														<input type="radio" id="inlineradio5" name="otwc" value="monthly">
														Monthly
													</label>
												</div>
											</div>
											<hr/>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Leave/Month for AL</label>
												<div class="col-sm-2">
													 <input type="number" step="any" class="form-control" id="lpm" name="lev_per_mth" value="2.5">
												</div>
												<label for="input-text" class="col-sm-3 control-label">Air Ticket Allotment after</label>
												<div class="col-sm-2">
													 <input type="number" step="any" class="form-control" id="air_tkt" name="air_tkt" value="2">
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Alloted Anual ML</label>
												<div class="col-sm-2">
													 <input type="number" step="any" class="form-control" id="anual_ml" name="anual_ml" value="2.5">
												</div>
												<label for="input-text" class="col-sm-3 control-label">Alloted Anual CL</label>
												<div class="col-sm-2">
													 <input type="number" step="any" class="form-control" id="anual_cl" name="anual_cl" value="2.5">
												</div>
											</div>
											
                                        </div>
										
										<div class="tab-pane" id="tab5">
										    	<input type="hidden" id="frm" name="frm" value="1" />
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Remarks</label>
												<div class="col-sm-10">
													 <input type="text" class="form-control" id="remarks" name="remarks" placeholder="Remarks">
												</div>
											</div>
											
										
											<input type="hidden" class="form-control" id="duty_status" name="duty_status" value="1">
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Other Info</label>
												<div class="col-sm-10">
													 <input type="text" class="form-control" id="other_info" name="other_info" placeholder="Other Info">
												</div>
											</div>
										</div>
										
										
									
                                        <ul class="pager wizard">
                                            <li class="previous">
                                                <a>Previous</a>
                                            </li>
                                            <li class="next">
                                                <a>Next</a>
                                            </li>
                                            <li class="next finish" style="display:none;">
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </li>
                                        </ul>
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
<script>
var urlcode = "{{ url('employee/') }}"; //checkcode
var saveurl = "{{ url('employee/save') }}";
var tkn = "{{csrf_token()}}";
var returl = "{{ url('employee') }}";

$(function() {	
		
	$('#dob').datepicker( { 
			dateFormat: 'dd-mm-yyyy',
			autoclose: 1
	});
	
	$('#join_date').datepicker( { 
			dateFormat: 'dd-mm-yyyy',
			autoclose: 1
	});
	
	$('#rejoin_date').datepicker( { 
			dateFormat: 'dd-mm-yyyy',
			autoclose: 1
	});
	
	$('#pp_issue_date').datepicker( { 
			dateFormat: 'dd-mm-yyyy',
			autoclose: 1
	});
	
	$('#pp_expiry_date').datepicker( { 
			dateFormat: 'dd-mm-yyyy',
			autoclose: 1
	});
	
	$('#v_issue_date').datepicker( { 
			dateFormat: 'dd-mm-yyyy',
			autoclose: 1
	});
	
	$('#v_expiry_date').datepicker( { 
			dateFormat: 'dd-mm-yyyy',
			autoclose: 1
	});
	
	$('#lc_issue_date').datepicker( { 
			dateFormat: 'dd-mm-yyyy',
			autoclose: 1
	});
	
	$('#lc_expiry_date').datepicker( { 
			dateFormat: 'dd-mm-yyyy',
			autoclose: 1
	});
	
	$('#hc_issue_date').datepicker( { 
			dateFormat: 'dd-mm-yyyy',
			autoclose: 1
	});
	
	$('#hc_expiry_date').datepicker( { 
			dateFormat: 'dd-mm-yyyy',
			autoclose: 1
	});
	
	$('#ic_issue_date').datepicker( { 
			dateFormat: 'dd-mm-yyyy',
			autoclose: 1
	});
	
	$('#ic_expiry_date').datepicker( { 
			dateFormat: 'dd-mm-yyyy',
			autoclose: 1
	});
	
	$('#me_issue_date').datepicker( { 
			dateFormat: 'dd-mm-yyyy',
			autoclose: true
	});
	
	$('#me_issue_date').datepicker( { 
			dateFormat: 'dd-mm-yyyy',
			autoclose: true
	});

	$('#salary_start_date').datepicker( { 
			dateFormat: 'dd-mm-yyyy',
			autoclose: true
	});
	$('#salary_end_date').datepicker( { 
			dateFormat: 'dd-mm-yyyy',
			autoclose: true
	});
	
	$(document).on('blur', '.salcal', function(e) {
		var net_sal = 0;
		$( '.salcal' ).each(function() {
			net_sal = net_sal + parseFloat((this.value=='') ? 0 : this.value);
		});
		
		$('#net_salary').val(net_sal);
	});
	
});	


	
</script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/iCheck/js/icheck.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-fileinput/js/fileinput.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/custom_js/form_elements.js')}}"></script>

<script src="{{asset('assets/vendors/moment/js/moment.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/datetime/js/jquery.datetimepicker.full.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.en.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/custom_js/advanceddate_pickers.js')}}"></script>

<script src="{{asset('assets/vendors/bootstrapwizard/js/jquery.bootstrap.wizard.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/custom_js/form_wizards.js')}}" type="text/javascript"></script>

        <!-- end of page level js -->
<script src="{{asset('assets/vendors/inputmask/inputmask/inputmask.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/inputmask/inputmask/jquery.inputmask.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/inputmask/inputmask/inputmask.date.extensions.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/inputmask/inputmask/inputmask.extensions.js')}}" type="text/javascript"></script>

<script src="{{asset('assets/js/vendor/jquery.ui.widget.js')}}"></script>
<script src="{{asset('assets/js/jquery.iframe-transport.js')}}"></script>
<script src="{{asset('assets/js/jquery.fileupload.js')}}"></script>

<script>
    $(function () {
        $('#input-23').fileupload({
            dataType: 'json',
            add: function (e, data) {
                $('#loading').text('Uploading...');
                data.submit();
            },
            done: function (e, data) {
				var pn = $('#photo_name').val();
				$('#photo_name').val( (pn=='')?data.result.file_name:pn+','+data.result.file_name );
                $('#loading').text('Completed.');
            }
        });
		
		$('#input-23p').fileupload({
            dataType: 'json',
            add: function (e, data) {
                $('#ploading').text('Uploading...');
                data.submit();
            },
            done: function (e, data) {
				var pim = $('#pimage_name').val();
				$('#pimage_name').val( (pim=='')?data.result.file_name:pim+','+data.result.file_name );
                $('#ploading').text('Completed.');
            }
        });
		
		$('#input-23v').fileupload({
            dataType: 'json',
            add: function (e, data) {
                $('#vloading').text('Uploading...');
                data.submit();
            },
            done: function (e, data) {
				var vim = $('#vimage_name').val();
				$('#vimage_name').val( (vim=='')?data.result.file_name:vim+','+data.result.file_name );
                $('#vloading').text('Completed.');
            }
        });
		
		$('#input-23l').fileupload({
            dataType: 'json',
            add: function (e, data) {
                $('#lloading').text('Uploading...');
                data.submit();
            },
            done: function (e, data) {
				var lim = $('#limage_name').val();
				$('#limage_name').val( (lim=='')?data.result.file_name:lim+','+data.result.file_name );
                $('#lloading').text('Completed.');
            }
        });
		
		$('#input-23h').fileupload({
            dataType: 'json',
            add: function (e, data) {
                $('#hloading').text('Uploading...');
                data.submit();
            },
            done: function (e, data) {
				var him = $('#himage_name').val();
				$('#himage_name').val( (him=='')?data.result.file_name:him+','+data.result.file_name );
                $('#hloading').text('Completed.');
            }
        });
		
		$('#input-23i').fileupload({
            dataType: 'json',
            add: function (e, data) {
                $('#iloading').text('Uploading...');
                data.submit();
            },
            done: function (e, data) {
				var iim = $('#iimage_name').val();
				$('#iimage_name').val( (iim=='')?data.result.file_name:iim+','+data.result.file_name );
                $('#iloading').text('Completed.');
            }
        });
		
		$('#input-23me').fileupload({
            dataType: 'json',
            add: function (e, data) {
                $('#meloading').text('Uploading...');
                data.submit();
            },
            done: function (e, data) {
				var meim = $('#meimage_name').val();
				$('#meimage_name').val( (meim=='')?data.result.file_name:meim+','+data.result.file_name );
                $('#meloading').text('Completed.');
            }
        });
		
    });
</script>

@stop
