@extends('layouts/default')

    {{-- Page title --}}
    @section('title')
         
        @parent
    @stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrap-fileinput/css/fileinput.min.css')}}" media="all" />
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/awesomebootstrapcheckbox/css/awesome-bootstrap-checkbox.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/formelements.css')}}">
        <!--end of page level css-->
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/radio_checkbox.css')}}">	
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <!--section starts-->
            <h1>
                Permission
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="">
                       <i class="fa fa-fw fa-key"></i> Role Management
                    </a>
                </li>
                <li>
                    <a href="#">Permission</a>
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
                            <i class="fa fa-fw fa-list-alt"></i> {{$role->display_name}} Permissions List
                        </h3>
                        
                    </div>
                    <div class="panel-body">
					  <form class="form-horizontal" role="form" method="POST" name="frmPermission" id="frmPermission" action="{{ url('permission/update') }}">
					   <input type="hidden" name="_token" value="{{ csrf_token() }}">
					   <input type="hidden" name="role_id" id="role_id" value="{{ $role->id }}">
					   
                        <div id="accordion" class="panel-group">
							
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion" href="#collapseLd">Leads</a>
										<input type="hidden" name="section[]" value="LD">
									</h4>
								</div>
								<div id="collapseLd" class="panel-collapse collapse in">
									<div class="panel-body" style="padding-left:80px !important;">
									  <div class="box-body">
										<div class="row">
                                            <div class="col-md-12">
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxld1" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['LD'][0]->id;?>" <?php if(in_array($permissions['LD'][0]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxld1">
                                                            &nbsp;List
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxld2" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['LD'][1]->id;?>" <?php if(in_array($permissions['LD'][1]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxld2">
                                                            &nbsp;Create
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxld3" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['LD'][2]->id;?>" <?php if(in_array($permissions['LD'][2]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxld3">
                                                            &nbsp;Modify
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxld4" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['LD'][3]->id;?>" <?php if(in_array($permissions['LD'][3]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxld4">
                                                            &nbsp;Delete
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
									  </div>
									</div>
								</div>
							</div>
							
							
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion" href="#collapseAccat">Account Category</a>
										<input type="hidden" name="section[]" value="ACCAT">
									</h4>
								</div>
								<div id="collapseAccat" class="panel-collapse collapse">
									<div class="panel-body" style="padding-left:80px !important;">
									  <div class="box-body">
										<div class="row">
                                            <div class="col-md-12">
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxAccat1" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['ACCAT'][0]->id;?>" <?php if(in_array($permissions['ACCAT'][0]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxAccat1">
                                                            &nbsp;List
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxAccat2" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['ACCAT'][1]->id;?>" <?php if(in_array($permissions['ACCAT'][1]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxAccat2">
                                                            &nbsp;Create
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxAccat3" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['ACCAT'][2]->id;?>" <?php if(in_array($permissions['ACCAT'][2]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxAccat3">
                                                            &nbsp;Modify
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxAccat4" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['ACCAT'][3]->id;?>" <?php if(in_array($permissions['ACCAT'][3]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxAccat4">
                                                            &nbsp;Delete
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
									  </div>
									</div>
								</div>
							</div>
							
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion" href="#collapseAcgrp">Account Group</a>
										<input type="hidden" name="section[]" value="ACGP">
									</h4>
								</div>
								<div id="collapseAcgrp" class="panel-collapse collapse">
									<div class="panel-body" style="padding-left:80px !important;">
									  <div class="box-body">
										<div class="row">
                                            <div class="col-md-12">
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxAcgp1" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['ACGP'][0]->id;?>" <?php if(in_array($permissions['ACGP'][0]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxAcgp1">
                                                            &nbsp;List
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxAcgp2" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['ACGP'][1]->id;?>" <?php if(in_array($permissions['ACGP'][1]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxAcgp2">
                                                            &nbsp;Create
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxAcgp3" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['ACGP'][2]->id;?>" <?php if(in_array($permissions['ACGP'][2]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxAcgp3">
                                                            &nbsp;Modify
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxAcgp4" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['ACGP'][3]->id;?>" <?php if(in_array($permissions['ACGP'][3]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxAcgp4">
                                                            &nbsp;Delete
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
									  </div>
									</div>
								</div>
							</div>
							
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion" href="#collapseAcm">Account Master</a>
										<input type="hidden" name="section[]" value="ACM">
									</h4>
								</div>
								<div id="collapseAcm" class="panel-collapse collapse">
									<div class="panel-body" style="padding-left:80px !important;">
									  <div class="box-body">
										<div class="row">
                                            <div class="col-md-12">
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxAcm1" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['ACM'][0]->id;?>" <?php if(in_array($permissions['ACM'][0]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxAcm1">
                                                            &nbsp;List
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxAcm2" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['ACM'][1]->id;?>" <?php if(in_array($permissions['ACM'][1]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxAcm2">
                                                            &nbsp;Create
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxAcm2" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['ACM'][2]->id;?>" <?php if(in_array($permissions['ACM'][2]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxAcm2">
                                                            &nbsp;Modify
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxAcm3" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['ACM'][3]->id;?>" <?php if(in_array($permissions['ACM'][3]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxAcm3">
                                                            &nbsp;Delete
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxAcm4" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['ACM'][4]->id;?>" <?php if(in_array($permissions['ACM'][4]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxAcm4">
                                                            &nbsp;View
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxAcm5" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['ACM'][5]->id;?>" <?php if(in_array($permissions['ACM'][5]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxAcm5">
                                                            &nbsp;Address List
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
									  </div>
									</div>
								</div>
							</div>
							
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion" href="#collapseAce">Account Enquiry</a>
										<input type="hidden" name="section[]" value="ACE">
									</h4>
								</div>
								<div id="collapseAce" class="panel-collapse collapse">
									<div class="panel-body" style="padding-left:80px !important;">
									  <div class="box-body">
										<div class="row">
                                            <div class="col-md-12">
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxAce1" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['ACE'][0]->id;?>" <?php if(in_array($permissions['ACE'][0]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxAce1">
                                                            &nbsp;List
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxAce2" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['ACE'][1]->id;?>" <?php if(in_array($permissions['ACE'][1]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxAce2">
                                                            &nbsp;Statement
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxAce3" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['ACE'][2]->id;?>" <?php if(in_array($permissions['ACE'][2]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxAce3">
                                                            &nbsp;Outstanding
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxAce4" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['ACE'][3]->id;?>" <?php if(in_array($permissions['ACE'][3]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxAce4">
                                                            &nbsp;Ageing
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxAce5" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['ACM'][4]->id;?>" <?php if(in_array($permissions['ACM'][4]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxAce5">
                                                            &nbsp;View
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
									  </div>
									</div>
								</div>
							</div>
							
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion" href="#collapseJm">Job Master</a>
										<input type="hidden" name="section[]" value="JMSTR">
									</h4>
								</div>
								<div id="collapseJm" class="panel-collapse collapse">
									<div class="panel-body" style="padding-left:80px !important;">
									  <div class="box-body">
										<div class="row">
                                            <div class="col-md-12">
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxJm1" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['JMSTR'][0]->id;?>" <?php if(in_array($permissions['JMSTR'][0]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxJm1">
                                                            &nbsp;List
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxJm2" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['JMSTR'][1]->id;?>" <?php if(in_array($permissions['JMSTR'][1]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxJm2">
                                                            &nbsp;Create
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxJm3" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['JMSTR'][2]->id;?>" <?php if(in_array($permissions['JMSTR'][2]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxJm3">
                                                            &nbsp;Modify
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxJm4" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['JMSTR'][3]->id;?>" <?php if(in_array($permissions['JMSTR'][3]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxJm4">
                                                            &nbsp;Delete
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
									  </div>
									</div>
								</div>
							</div>
							
							
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion" href="#collapseJt">Job Type</a>
										<input type="hidden" name="section[]" value="JT">
									</h4>
								</div>
								<div id="collapseJt" class="panel-collapse collapse">
									<div class="panel-body" style="padding-left:80px !important;">
									  <div class="box-body">
										<div class="row">
                                            <div class="col-md-12">
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxJt1" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['JT'][0]->id;?>" <?php if(in_array($permissions['JT'][0]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxJt1">
                                                            &nbsp;List
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxJt2" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['JT'][1]->id;?>" <?php if(in_array($permissions['JT'][1]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxJt2">
                                                            &nbsp;Create
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxJt3" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['JT'][2]->id;?>" <?php if(in_array($permissions['JT'][2]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxJt3">
                                                            &nbsp;Modify
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxJt4" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['JT'][3]->id;?>" <?php if(in_array($permissions['JT'][3]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxJt4">
                                                            &nbsp;Delete
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
									  </div>
									</div>
								</div>
							</div>
							
							
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion" href="#collapseSm">Sales Man</a>
										<input type="hidden" name="section[]" value="SMN">
									</h4>
								</div>
								<div id="collapseSm" class="panel-collapse collapse">
									<div class="panel-body" style="padding-left:80px !important;">
									  <div class="box-body">
										<div class="row">
                                            <div class="col-md-12">
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxSm1" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['SMN'][0]->id;?>" <?php if(in_array($permissions['SMN'][0]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxSm1">
                                                            &nbsp;List
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxSm2" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['SMN'][1]->id;?>" <?php if(in_array($permissions['SMN'][1]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxSm2">
                                                            &nbsp;Create
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxSm3" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['SMN'][2]->id;?>" <?php if(in_array($permissions['SMN'][2]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxSm3">
                                                            &nbsp;Modify
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxSm4" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['SMN'][3]->id;?>" <?php if(in_array($permissions['SMN'][3]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxSm4">
                                                            &nbsp;Delete
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
									  </div>
									</div>
								</div>
							</div>
							
							
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion" href="#collapseDp">Department</a>
										<input type="hidden" name="section[]" value="DEPT">
									</h4>
								</div>
								<div id="collapseDp" class="panel-collapse collapse">
									<div class="panel-body" style="padding-left:80px !important;">
									  <div class="box-body">
										<div class="row">
                                            <div class="col-md-12">
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxDp1" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['DEPT'][0]->id;?>" <?php if(in_array($permissions['DEPT'][0]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxDp1">
                                                            &nbsp;List
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxDp2" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['DEPT'][1]->id;?>" <?php if(in_array($permissions['DEPT'][1]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxDp2">
                                                            &nbsp;Create
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxDp3" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['DEPT'][2]->id;?>" <?php if(in_array($permissions['DEPT'][2]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxDp3">
                                                            &nbsp;Modify
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxDp4" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['DEPT'][3]->id;?>" <?php if(in_array($permissions['DEPT'][3]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxDp4">
                                                            &nbsp;Delete
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
									  </div>
									</div>
								</div>
							</div>
							
							
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion" href="#collapseDdp">Document Department</a>
										<input type="hidden" name="section[]" value="DD">
									</h4>
								</div>
								<div id="collapseDdp" class="panel-collapse collapse">
									<div class="panel-body" style="padding-left:80px !important;">
									  <div class="box-body">
										<div class="row">
                                            <div class="col-md-12">
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxDdp1" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['DD'][0]->id;?>" <?php if(in_array($permissions['DD'][0]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxDdp1">
                                                            &nbsp;List
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxDdp2" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['DD'][1]->id;?>" <?php if(in_array($permissions['DD'][1]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxDdp2">
                                                            &nbsp;Create
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxDdp3" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['DD'][2]->id;?>" <?php if(in_array($permissions['DD'][2]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxDdp3">
                                                            &nbsp;Modify
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxDdp4" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['DD'][3]->id;?>" <?php if(in_array($permissions['DD'][3]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxDdp4">
                                                            &nbsp;Delete
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
									  </div>
									</div>
								</div>
							</div>
							
							
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion" href="#collapseBk">Bank</a>
										<input type="hidden" name="section[]" value="BANK">
									</h4>
								</div>
								<div id="collapseBk" class="panel-collapse collapse">
									<div class="panel-body" style="padding-left:80px !important;">
									  <div class="box-body">
										<div class="row">
                                            <div class="col-md-12">
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxBk1" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['BANK'][0]->id;?>" <?php if(in_array($permissions['BANK'][0]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxBk1">
                                                            &nbsp;List
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxBk2" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['BANK'][1]->id;?>" <?php if(in_array($permissions['BANK'][1]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxBk2">
                                                            &nbsp;Create
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxBk3" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['BANK'][2]->id;?>" <?php if(in_array($permissions['BANK'][2]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxBk3">
                                                            &nbsp;Modify
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxBk4" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['BANK'][3]->id;?>" <?php if(in_array($permissions['BANK'][3]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxBk4">
                                                            &nbsp;Delete
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
									  </div>
									</div>
								</div>
							</div>
							
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion" href="#collapseCy">Currency</a>
										<input type="hidden" name="section[]" value="CRNCY">
									</h4>
								</div>
								<div id="collapseCy" class="panel-collapse collapse">
									<div class="panel-body" style="padding-left:80px !important;">
									  <div class="box-body">
										<div class="row">
                                            <div class="col-md-12">
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxCy1" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['CRNCY'][0]->id;?>" <?php if(in_array($permissions['CRNCY'][0]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxCy1">
                                                            &nbsp;List
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxCy2" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['CRNCY'][1]->id;?>" <?php if(in_array($permissions['CRNCY'][1]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxCy2">
                                                            &nbsp;Create
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxCy3" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['CRNCY'][2]->id;?>" <?php if(in_array($permissions['CRNCY'][2]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxCy3">
                                                            &nbsp;Modify
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxCy4" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['CRNCY'][3]->id;?>" <?php if(in_array($permissions['CRNCY'][3]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxCy4">
                                                            &nbsp;Delete
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
									  </div>
									</div>
								</div>
							</div>
							
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion" href="#collapseAr">Area</a>
										<input type="hidden" name="section[]" value="AREA">
									</h4>
								</div>
								<div id="collapseAr" class="panel-collapse collapse">
									<div class="panel-body" style="padding-left:80px !important;">
									  <div class="box-body">
										<div class="row">
                                            <div class="col-md-12">
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxAr1" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['AREA'][0]->id;?>" <?php if(in_array($permissions['AREA'][0]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxAr1">
                                                            &nbsp;List
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxAr2" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['AREA'][1]->id;?>" <?php if(in_array($permissions['AREA'][1]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxAr2">
                                                            &nbsp;Create
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxAr3" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['AREA'][2]->id;?>" <?php if(in_array($permissions['AREA'][2]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxAr3">
                                                            &nbsp;Modify
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxAr4" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['AREA'][3]->id;?>" <?php if(in_array($permissions['AREA'][3]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxAr4">
                                                            &nbsp;Delete
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
									  </div>
									</div>
								</div>
							</div>
							
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion" href="#collapseLc">Location</a>
										<input type="hidden" name="section[]" value="LOC">
									</h4>
								</div>
								<div id="collapseLc" class="panel-collapse collapse">
									<div class="panel-body" style="padding-left:80px !important;">
									  <div class="box-body">
										<div class="row">
                                            <div class="col-md-12">
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxLc1" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['LOC'][0]->id;?>" <?php if(in_array($permissions['LOC'][0]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxLc1">
                                                            &nbsp;List
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxLc2" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['LOC'][1]->id;?>" <?php if(in_array($permissions['LOC'][1]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxLc2">
                                                            &nbsp;Create
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxLc3" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['LOC'][2]->id;?>" <?php if(in_array($permissions['LOC'][2]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxLc3">
                                                            &nbsp;Modify
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxLc4" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['LOC'][3]->id;?>" <?php if(in_array($permissions['LOC'][3]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxLc4">
                                                            &nbsp;Delete
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
									  </div>
									</div>
								</div>
							</div>
							
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion" href="#collapseCn">Country</a>
										<input type="hidden" name="section[]" value="CON">
									</h4>
								</div>
								<div id="collapseCn" class="panel-collapse collapse">
									<div class="panel-body" style="padding-left:80px !important;">
									  <div class="box-body">
										<div class="row">
                                            <div class="col-md-12">
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxCn1" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['CON'][0]->id;?>" <?php if(in_array($permissions['CON'][0]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxCn1">
                                                            &nbsp;List
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxCn2" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['CON'][1]->id;?>" <?php if(in_array($permissions['CON'][1]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxCn2">
                                                            &nbsp;Create
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxCn3" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['CON'][2]->id;?>" <?php if(in_array($permissions['CON'][2]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxCn3">
                                                            &nbsp;Modify
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxCn4" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['CON'][3]->id;?>" <?php if(in_array($permissions['CON'][3]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxCn4">
                                                            &nbsp;Delete
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
									  </div>
									</div>
								</div>
							</div>
							
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion" href="#collapseTm">Terms</a>
										<input type="hidden" name="section[]" value="TERM">
									</h4>
								</div>
								<div id="collapseTm" class="panel-collapse collapse">
									<div class="panel-body" style="padding-left:80px !important;">
									  <div class="box-body">
										<div class="row">
                                            <div class="col-md-12">
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxTm1" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['TERM'][0]->id;?>" <?php if(in_array($permissions['TERM'][0]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxTm1">
                                                            &nbsp;List
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxTm2" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['TERM'][1]->id;?>" <?php if(in_array($permissions['TERM'][1]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxTm2">
                                                            &nbsp;Create
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxTm3" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['TERM'][2]->id;?>" <?php if(in_array($permissions['TERM'][2]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxTm3">
                                                            &nbsp;Modify
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxTm4" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['TERM'][3]->id;?>" <?php if(in_array($permissions['TERM'][3]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxTm4">
                                                            &nbsp;Delete
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
									  </div>
									</div>
								</div>
							</div>
							
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion" href="#collapseHf">Header/Footer</a>
										<input type="hidden" name="section[]" value="HF">
									</h4>
								</div>
								<div id="collapseHf" class="panel-collapse collapse">
									<div class="panel-body" style="padding-left:80px !important;">
									  <div class="box-body">
										<div class="row">
                                            <div class="col-md-12">
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxHf1" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['HF'][0]->id;?>" <?php if(in_array($permissions['HF'][0]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxHf1">
                                                            &nbsp;List
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxHf2" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['HF'][1]->id;?>" <?php if(in_array($permissions['HF'][1]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxHf2">
                                                            &nbsp;Create
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxHf3" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['HF'][2]->id;?>" <?php if(in_array($permissions['HF'][2]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxHf3">
                                                            &nbsp;Modify
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxHf4" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['HF'][3]->id;?>" <?php if(in_array($permissions['HF'][3]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxHf4">
                                                            &nbsp;Delete
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
									  </div>
									</div>
								</div>
							</div>
							
							
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion" href="#collapseVle">Vehicle Master</a>
										<input type="hidden" name="section[]" value="VLE">
									</h4>
								</div>
								<div id="collapseVle" class="panel-collapse collapse">
									<div class="panel-body" style="padding-left:80px !important;">
									  <div class="box-body">
										<div class="row">
                                            <div class="col-md-12">
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxVle1" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['VLE'][0]->id;?>" <?php if(in_array($permissions['VLE'][0]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxVle1">
                                                            &nbsp;List
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxVle2" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['VLE'][1]->id;?>" <?php if(in_array($permissions['VLE'][1]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxVle2">
                                                            &nbsp;Create
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxVle3" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['VLE'][2]->id;?>" <?php if(in_array($permissions['VLE'][2]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxVle3">
                                                            &nbsp;Modify
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxVle4" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['VLE'][3]->id;?>" <?php if(in_array($permissions['VLE'][3]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxVle4">
                                                            &nbsp;Delete
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
									  </div>
									</div>
								</div>
							</div>
							
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion" href="#collapseVt">VAT Master</a>
										<input type="hidden" name="section[]" value="VAT">
									</h4>
								</div>
								<div id="collapseVt" class="panel-collapse collapse">
									<div class="panel-body" style="padding-left:80px !important;">
									  <div class="box-body">
										<div class="row">
                                            <div class="col-md-12">
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxVt1" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['VAT'][0]->id;?>" <?php if(in_array($permissions['VAT'][0]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxVt1">
                                                            &nbsp;List
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxVt2" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['VAT'][1]->id;?>" <?php if(in_array($permissions['VAT'][1]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxVt2">
                                                            &nbsp;Create
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxVt3" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['VAT'][2]->id;?>" <?php if(in_array($permissions['VAT'][2]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxVt3">
                                                            &nbsp;Modify
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxVt4" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['VAT'][3]->id;?>" <?php if(in_array($permissions['VAT'][3]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxVt4">
                                                            &nbsp;Delete
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
									  </div>
									</div>
								</div>
							</div>
							
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion" href="#collapseGpr">Group</a>
										<input type="hidden" name="section[]" value="IGP">
									</h4>
								</div>
								<div id="collapseGpr" class="panel-collapse collapse">
									<div class="panel-body" style="padding-left:80px !important;">
									  <div class="box-body">
										<div class="row">
                                            <div class="col-md-12">
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxGpr1" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['IGP'][0]->id;?>" <?php if(in_array($permissions['IGP'][0]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxGpr1">
                                                            &nbsp;List
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxGpr2" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['IGP'][1]->id;?>" <?php if(in_array($permissions['IGP'][1]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxGpr2">
                                                            &nbsp;Create
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxGpr3" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['IGP'][2]->id;?>" <?php if(in_array($permissions['IGP'][2]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxGpr3">
                                                            &nbsp;Modify
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxGpr4" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['IGP'][3]->id;?>" <?php if(in_array($permissions['IGP'][3]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxGpr4">
                                                            &nbsp;Delete
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
									  </div>
									</div>
								</div>
							</div>
							
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion" href="#collapsesGpr">Sub Group</a>
										<input type="hidden" name="section[]" value="ISGP">
									</h4>
								</div>
								<div id="collapsesGpr" class="panel-collapse collapse">
									<div class="panel-body" style="padding-left:80px !important;">
									  <div class="box-body">
										<div class="row">
                                            <div class="col-md-12">
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxsGpr1" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['ISGP'][0]->id;?>" <?php if(in_array($permissions['ISGP'][0]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxsGpr1">
                                                            &nbsp;List
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxsGpr2" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['ISGP'][1]->id;?>" <?php if(in_array($permissions['ISGP'][1]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxsGpr2">
                                                            &nbsp;Create
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxsGpr3" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['ISGP'][2]->id;?>" <?php if(in_array($permissions['ISGP'][2]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxsGpr3">
                                                            &nbsp;Modify
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxsGpr4" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['ISGP'][3]->id;?>" <?php if(in_array($permissions['ISGP'][3]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxsGpr4">
                                                            &nbsp;Delete
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
									  </div>
									</div>
								</div>
							</div>
							
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion" href="#collapseCat">Category</a>
										<input type="hidden" name="section[]" value="ICAT">
									</h4>
								</div>
								<div id="collapseCat" class="panel-collapse collapse">
									<div class="panel-body" style="padding-left:80px !important;">
									  <div class="box-body">
										<div class="row">
                                            <div class="col-md-12">
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxCat1" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['ICAT'][0]->id;?>" <?php if(in_array($permissions['ICAT'][0]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxCat1">
                                                            &nbsp;List
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxCat2" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['ICAT'][1]->id;?>" <?php if(in_array($permissions['ICAT'][1]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxCat2">
                                                            &nbsp;Create
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxCat3" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['ICAT'][2]->id;?>" <?php if(in_array($permissions['ICAT'][2]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxCat3">
                                                            &nbsp;Modify
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxCat4" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['ICAT'][3]->id;?>" <?php if(in_array($permissions['ICAT'][3]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxCat4">
                                                            &nbsp;Delete
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
									  </div>
									</div>
								</div>
							</div>
							
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion" href="#collapsesCat">Sub Category</a>
										<input type="hidden" name="section[]" value="ISCAT">
									</h4>
								</div>
								<div id="collapsesCat" class="panel-collapse collapse">
									<div class="panel-body" style="padding-left:80px !important;">
									  <div class="box-body">
										<div class="row">
                                            <div class="col-md-12">
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxsCat1" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['ISCAT'][0]->id;?>" <?php if(in_array($permissions['ISCAT'][0]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxsCat1">
                                                            &nbsp;List
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxsCat2" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['ISCAT'][1]->id;?>" <?php if(in_array($permissions['ISCAT'][1]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxsCat2">
                                                            &nbsp;Create
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxsCat3" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['ISCAT'][2]->id;?>" <?php if(in_array($permissions['ISCAT'][2]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxsCat3">
                                                            &nbsp;Modify
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxsCat4" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['ISCAT'][3]->id;?>" <?php if(in_array($permissions['ISCAT'][3]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxsCat4">
                                                            &nbsp;Delete
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
									  </div>
									</div>
								</div>
							</div>
							
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion" href="#collapseUnt">Unit</a>
										<input type="hidden" name="section[]" value="UNIT">
									</h4>
								</div>
								<div id="collapseUnt" class="panel-collapse collapse">
									<div class="panel-body" style="padding-left:80px !important;">
									  <div class="box-body">
										<div class="row">
                                            <div class="col-md-12">
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxUnt1" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['UNIT'][0]->id;?>" <?php if(in_array($permissions['UNIT'][0]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxUnt1">
                                                            &nbsp;List
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxUnt2" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['UNIT'][1]->id;?>" <?php if(in_array($permissions['UNIT'][1]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxUnt2">
                                                            &nbsp;Create
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxUnt3" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['UNIT'][2]->id;?>" <?php if(in_array($permissions['UNIT'][2]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxUnt3">
                                                            &nbsp;Modify
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxUnt4" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['UNIT'][3]->id;?>" <?php if(in_array($permissions['UNIT'][3]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxUnt4">
                                                            &nbsp;Delete
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
									  </div>
									</div>
								</div>
							</div>
							
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion" href="#collapseItm">Item Master</a>
										<input type="hidden" name="section[]" value="ITM">
									</h4>
								</div>
								<div id="collapseItm" class="panel-collapse collapse">
									<div class="panel-body" style="padding-left:80px !important;">
									  <div class="box-body">
										<div class="row">
                                            <div class="col-md-12">
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxItm1" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['ITM'][0]->id;?>" <?php if(in_array($permissions['ITM'][0]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxItm1">
                                                            &nbsp;List
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxItm2" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['ITM'][1]->id;?>" <?php if(in_array($permissions['ITM'][1]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxItm2">
                                                            &nbsp;Create
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxItm3" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['ITM'][2]->id;?>" <?php if(in_array($permissions['ITM'][2]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxItm3">
                                                            &nbsp;Modify
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxItm4" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['ITM'][3]->id;?>" <?php if(in_array($permissions['ITM'][3]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxItm4">
                                                            &nbsp;Delete
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                     <div class="checkbox checkbox-primary">
                                                        <input id="checkboxItm5" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['ITM'][4]->id;?>" <?php if(in_array($permissions['ITM'][4]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxItm5">
                                                            &nbsp;Item Enquiry
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxItm6" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['ITM'][6]->id;?>" <?php if(in_array($permissions['ITM'][6]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxItm6">
                                                            &nbsp;Show Cost 
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
									  </div>
									</div>
								</div>
							</div>
							
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">Purchase Order</a>
										<input type="hidden" name="section[]" value="PO">
									</h4>
								</div>
								<div id="collapseOne" class="panel-collapse collapse">
									<div class="panel-body" style="padding-left:80px !important;">
									  <div class="box-body">
										<div class="row">
                                            <div class="col-md-12">
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox1" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['PO'][0]->id;?>" <?php if(in_array($permissions['PO'][0]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox1">
                                                            &nbsp;List
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox2" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['PO'][1]->id;?>" <?php if(in_array($permissions['PO'][1]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox2">
                                                            &nbsp;Create
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox3" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['PO'][2]->id;?>" <?php if(in_array($permissions['PO'][2]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox3">
                                                            &nbsp;Modify
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox4" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['PO'][3]->id;?>" <?php if(in_array($permissions['PO'][3]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox4">
                                                            &nbsp;Delete
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox5" type="checkbox" class="styled" name="permission_id[]" value="<?php echo $permissions['PO'][4]->id;?>" <?php if(in_array($permissions['PO'][4]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox5">
                                                            &nbsp;Print
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox6" type="checkbox" class="styled" name="permission_id[]" value="<?php echo $permissions['PO'][5]->id;?>" <?php if(in_array($permissions['PO'][5]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox6">
                                                            &nbsp;Edit Invoice No.
                                                        </label>
                                                    </div>
                                                </div>
												<div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox6" type="checkbox" class="styled" name="permission_id[]" value="<?php echo $permissions['PO'][6]->id;?>" <?php if(in_array($permissions['PO'][6]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox6">
                                                            &nbsp;Order History
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
									  </div>
									</div>
								</div>
							</div>
							
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">Purchase Invoice</a>
									</h4>
								</div>
								<div id="collapseTwo" class="panel-collapse collapse">
									<div class="panel-body" style="padding-left:80px !important;">
									  <div class="box-body">
										<div class="row">
                                            <div class="col-md-12">
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox7" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['PI'][0]->id;?>" <?php if(in_array($permissions['PI'][0]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox7">
                                                            &nbsp;List
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox8" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['PI'][1]->id;?>" <?php if(in_array($permissions['PI'][1]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox8">
                                                            &nbsp;Create
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox9" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['PI'][2]->id;?>" <?php if(in_array($permissions['PI'][2]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox9">
                                                            &nbsp;Modify
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox10" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['PI'][3]->id;?>" <?php if(in_array($permissions['PI'][3]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox10">
                                                            &nbsp;Delete
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox11" type="checkbox" class="styled" name="permission_id[]" value="<?php echo $permissions['PI'][4]->id;?>" <?php if(in_array($permissions['PI'][4]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox11">
                                                            &nbsp;Print
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox12" type="checkbox" class="styled" name="permission_id[]" value="<?php echo $permissions['PI'][5]->id;?>" <?php if(in_array($permissions['PI'][5]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox12">
                                                            &nbsp;Edit Invoice No.
                                                        </label>
                                                    </div>
                                                </div>
												
												<div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox6" type="checkbox" class="styled" name="permission_id[]" value="<?php echo $permissions['PI'][6]->id;?>" <?php if(in_array($permissions['PI'][6]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox6">
                                                            &nbsp;Order History
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
									  </div>
									</div>
								</div>
							</div>
							
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion" href="#collapsePR">Purchase Return</a>
									</h4>
								</div>
								<div id="collapsePR" class="panel-collapse collapse">
									<div class="panel-body" style="padding-left:80px !important;">
									  <div class="box-body">
										<div class="row">
                                            <div class="col-md-12">
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxPR1" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['PR'][0]->id;?>" <?php if(in_array($permissions['PR'][0]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxPR1">
                                                            &nbsp;List
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxPR2" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['PR'][1]->id;?>" <?php if(in_array($permissions['PR'][1]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxPR2">
                                                            &nbsp;Create
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxPR3" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['PR'][2]->id;?>" <?php if(in_array($permissions['PR'][2]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxPR3">
                                                            &nbsp;Modify
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxPR4" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['PR'][3]->id;?>" <?php if(in_array($permissions['PR'][3]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxPR4">
                                                            &nbsp;Delete
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxPR5" type="checkbox" class="styled" name="permission_id[]" value="<?php echo $permissions['PR'][4]->id;?>" <?php if(in_array($permissions['PR'][4]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxPR5">
                                                            &nbsp;Print
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxPR6" type="checkbox" class="styled" name="permission_id[]" value="<?php echo $permissions['PR'][5]->id;?>" <?php if(in_array($permissions['PR'][5]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxPR6">
                                                            &nbsp;Edit Invoice No.
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
									  </div>
									</div>
								</div>
							</div>
							
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion" href="#collapseCe">Customer Enquiry</a>
									</h4>
								</div>
								<div id="collapseCe" class="panel-collapse collapse">
									<div class="panel-body" style="padding-left:80px !important;">
									  <div class="box-body">
										<div class="row">
                                            <div class="col-md-12">
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxCe1" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['CE'][0]->id;?>" <?php if(in_array($permissions['QS'][0]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxCe1">
                                                            &nbsp;List
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxCe2" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['CE'][1]->id;?>" <?php if(in_array($permissions['QS'][1]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxCe2">
                                                            &nbsp;Create
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxCe3" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['CE'][2]->id;?>" <?php if(in_array($permissions['QS'][2]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxCe3">
                                                            &nbsp;Modify
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxCe4" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['CE'][3]->id;?>" <?php if(in_array($permissions['QS'][3]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxCe4">
                                                            &nbsp;Delete
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxCe5" type="checkbox" class="styled" name="permission_id[]" value="<?php echo $permissions['CE'][4]->id;?>" <?php if(in_array($permissions['QS'][4]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxCe5">
                                                            &nbsp;Print
                                                        </label>
                                                    </div>
                                                </div>
												<div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxCe6" type="checkbox" class="styled" name="permission_id[]" value="<?php echo $permissions['CE'][5]->id;?>" <?php if(in_array($permissions['QS'][5]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxCe6">
                                                            &nbsp;Order History
                                                        </label>
                                                    </div>
                                                </div>
												<div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxCe7" type="checkbox" class="styled" name="permission_id[]" value="<?php echo $permissions['CE'][6]->id;?>" <?php if(in_array($permissions['QS'][6]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxCe7">
                                                            &nbsp;Approve
                                                        </label>
                                                    </div>
                                                </div>
                                           
                                            </div>
                                        </div>
									  </div>
									</div>
								</div>
							</div>
							
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">Quotation Sales</a>
									</h4>
								</div>
								<div id="collapseThree" class="panel-collapse collapse">
									<div class="panel-body" style="padding-left:80px !important;">
									  <div class="box-body">
										<div class="row">
                                            <div class="col-md-12">
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox13" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['QS'][0]->id;?>" <?php if(in_array($permissions['QS'][0]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox13">
                                                            &nbsp;List
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox14" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['QS'][1]->id;?>" <?php if(in_array($permissions['QS'][1]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox14">
                                                            &nbsp;Create
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox15" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['QS'][2]->id;?>" <?php if(in_array($permissions['QS'][2]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox15">
                                                            &nbsp;Modify
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox16" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['QS'][3]->id;?>" <?php if(in_array($permissions['QS'][3]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox16">
                                                            &nbsp;Delete
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox17" type="checkbox" class="styled" name="permission_id[]" value="<?php echo $permissions['QS'][4]->id;?>" <?php if(in_array($permissions['QS'][4]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox17">
                                                            &nbsp;Print
                                                        </label>
                                                    </div>
                                                </div>
												<div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox6" type="checkbox" class="styled" name="permission_id[]" value="<?php echo $permissions['QS'][5]->id;?>" <?php if(in_array($permissions['QS'][5]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox6">
                                                            &nbsp;Order History
                                                        </label>
                                                    </div>
                                                </div>
												<div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxApr" type="checkbox" class="styled" name="permission_id[]" value="<?php echo $permissions['QS'][6]->id;?>" <?php if(in_array($permissions['QS'][6]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxApr">
                                                            &nbsp;Approve
                                                        </label>
                                                    </div>
                                                </div>
                                                <!--<div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox6" type="checkbox" class="styled" name="permission_id[]" value="<?php //echo $permissions['QS'][5]->id;?>" <?php //if(in_array($permissions['QS'][5]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox6">
                                                            &nbsp;Edit Invoice No.
                                                        </label>
                                                    </div>
                                                </div>-->
                                            </div>
                                        </div>
									  </div>
									</div>
								</div>
							</div>
							
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion" href="#collapseFour">Sales Order</a>
									</h4>
								</div>
								<div id="collapseFour" class="panel-collapse collapse">
									<div class="panel-body" style="padding-left:80px !important;">
									  <div class="box-body">
										<div class="row">
                                            <div class="col-md-12">
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox18" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['SO'][0]->id;?>" <?php if(in_array($permissions['SO'][0]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox18">
                                                            &nbsp;List
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox19" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['SO'][1]->id;?>" <?php if(in_array($permissions['SO'][1]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox19">
                                                            &nbsp;Create
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox19" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['SO'][2]->id;?>" <?php if(in_array($permissions['SO'][2]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox19">
                                                            &nbsp;Modify
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox20" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['SO'][3]->id;?>" <?php if(in_array($permissions['SO'][3]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox20">
                                                            &nbsp;Delete
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox21" type="checkbox" class="styled" name="permission_id[]" value="<?php echo $permissions['SO'][4]->id;?>" <?php if(in_array($permissions['SO'][4]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox21">
                                                            &nbsp;Print
                                                        </label>
                                                    </div>
                                                </div>
												<div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox6" type="checkbox" class="styled" name="permission_id[]" value="<?php echo $permissions['SO'][5]->id;?>" <?php if(in_array($permissions['SO'][5]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox6">
                                                            &nbsp;Order History
                                                        </label>
                                                    </div>
                                                </div>
												<div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxSoapr" type="checkbox" class="styled" name="permission_id[]" value="<?php echo $permissions['SO'][6]->id;?>" <?php if(in_array($permissions['SO'][6]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxSoapr">
                                                            &nbsp;Approve
                                                        </label>
                                                    </div>
                                                </div>
                                                <!--<div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox6" type="checkbox" class="styled" name="permission_id[]" value="<?php //echo $permissions['SO'][5]->id;?>" <?php //if(in_array($permissions['SO'][5]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox6">
                                                            &nbsp;Edit Invoice No.
                                                        </label>
                                                    </div>
                                                </div>-->
                                            </div>
                                        </div>
									  </div>
									</div>
								</div>
							</div>
							
							
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion" href="#collapseFive">Production Order</a>
									</h4>
								</div>
								<div id="collapseFive" class="panel-collapse collapse">
									<div class="panel-body" style="padding-left:80px !important;">
									  <div class="box-body">
										<div class="row">
                                            <div class="col-md-12">
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxprod1" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['PROD'][0]->id;?>" <?php if(in_array($permissions['PROD'][0]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxprod1">
                                                            &nbsp;List
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxprod2" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['PROD'][1]->id;?>" <?php if(in_array($permissions['PROD'][1]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxprod2">
                                                            &nbsp;Create
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxprod3" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['PROD'][2]->id;?>" <?php if(in_array($permissions['PROD'][2]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxprod3">
                                                            &nbsp;Modify
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxprod4" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['PROD'][3]->id;?>" <?php if(in_array($permissions['PROD'][3]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxprod4">
                                                            &nbsp;Delete
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxprod5" type="checkbox" class="styled" name="permission_id[]" value="<?php echo $permissions['PROD'][4]->id;?>" <?php if(in_array($permissions['PROD'][4]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxprod5">
                                                            &nbsp;Print
                                                        </label>
                                                    </div>
                                                </div>
												<div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxprod6" type="checkbox" class="styled" name="permission_id[]" value="<?php echo $permissions['PROD'][5]->id;?>" <?php if(in_array($permissions['PROD'][5]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxprod6">
                                                            &nbsp;Order History
                                                        </label>
                                                    </div>
                                                </div>
                                                <!--<div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox6" type="checkbox" class="styled" name="permission_id[]" value="<?php //echo $permissions['PROD'][5]->id;?>" <?php //if(in_array($permissions['PROD'][5]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox6">
                                                            &nbsp;Edit Invoice No.
                                                        </label>
                                                    </div>
                                                </div>-->
                                            </div>
                                        </div>
									  </div>
									</div>
								</div>
							</div>
							
							
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion" href="#collapseDO">Delivery Order</a>
									</h4>
								</div>
								<div id="collapseDO" class="panel-collapse collapse">
									<div class="panel-body" style="padding-left:80px !important;">
									  <div class="box-body">
										<div class="row">
                                            <div class="col-md-12">
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox22" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['DO'][0]->id;?>" <?php if(in_array($permissions['DO'][0]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox22">
                                                            &nbsp;List
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox23" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['DO'][1]->id;?>" <?php if(in_array($permissions['DO'][1]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox23">
                                                            &nbsp;Create
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox24" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['DO'][2]->id;?>" <?php if(in_array($permissions['DO'][2]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox24">
                                                            &nbsp;Modify
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox25" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['DO'][3]->id;?>" <?php if(in_array($permissions['DO'][3]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox25">
                                                            &nbsp;Delete
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox26" type="checkbox" class="styled" name="permission_id[]" value="<?php echo $permissions['DO'][4]->id;?>" <?php if(in_array($permissions['DO'][4]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox26">
                                                            &nbsp;Print
                                                        </label>
                                                    </div>
                                                </div>
												<div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox6" type="checkbox" class="styled" name="permission_id[]" value="<?php echo $permissions['DO'][5]->id;?>" <?php if(in_array($permissions['DO'][5]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox6">
                                                            &nbsp;Order History
                                                        </label>
                                                    </div>
                                                </div>
                                                <!--<div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox6" type="checkbox" class="styled" name="permission_id[]" value="<?php //echo $permissions['DO'][5]->id;?>" <?php //if(in_array($permissions['DO'][5]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox6">
                                                            &nbsp;Edit Invoice No.
                                                        </label>
                                                    </div>
                                                </div>-->
                                            </div>
                                        </div>
									  </div>
									</div>
								</div>
							</div>
							
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion" href="#collapseSix">Sales Invoice</a>
									</h4>
								</div>
								<div id="collapseSix" class="panel-collapse collapse">
								<div class="panel-body" style="padding-left:80px !important;">
									  <div class="box-body">
										<div class="row">
                                            <div class="col-md-12">
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox27" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['SI'][0]->id;?>" <?php if(in_array($permissions['SI'][0]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox27">
                                                            &nbsp;List
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox28" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['SI'][1]->id;?>" <?php if(in_array($permissions['SI'][1]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox28">
                                                            &nbsp;Create
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox29" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['SI'][2]->id;?>" <?php if(in_array($permissions['SI'][2]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox29">
                                                            &nbsp;Modify
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox30" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['SI'][3]->id;?>" <?php if(in_array($permissions['SI'][3]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox30">
                                                            &nbsp;Delete
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox31" type="checkbox" class="styled" name="permission_id[]" value="<?php echo $permissions['SI'][4]->id;?>" <?php if(in_array($permissions['SI'][4]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox31">
                                                            &nbsp;Print
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox32" type="checkbox" class="styled" name="permission_id[]" value="<?php echo $permissions['SI'][5]->id;?>" <?php if(in_array($permissions['SI'][5]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox32">
                                                            &nbsp;Edit Invoice No.
                                                        </label>
                                                    </div>
                                                </div>
												<div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxsi6" type="checkbox" class="styled" name="permission_id[]" value="<?php echo $permissions['SI'][6]->id;?>" <?php if(in_array($permissions['SI'][6]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxsi6">
                                                            &nbsp;Order History
                                                        </label>
                                                    </div>
                                                </div>
												<div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxsi7" type="checkbox" class="styled" name="permission_id[]" value="<?php echo $permissions['SI'][7]->id;?>" <?php if(in_array($permissions['SI'][7]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxsi7">
                                                            &nbsp;Order History by Phone
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
									  </div>
									</div>
									</div>
							</div>
							
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion" href="#collapseSR">Sales Return</a>
									</h4>
								</div>
								<div id="collapseSR" class="panel-collapse collapse">
								<div class="panel-body" style="padding-left:80px !important;">
									  <div class="box-body">
										<div class="row">
                                            <div class="col-md-12">
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxSR1" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['SR'][0]->id;?>" <?php if(in_array($permissions['SR'][0]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxSR1">
                                                            &nbsp;List
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxSR2" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['SR'][1]->id;?>" <?php if(in_array($permissions['SR'][1]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxSR2">
                                                            &nbsp;Create
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxSR3" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['SR'][2]->id;?>" <?php if(in_array($permissions['SR'][2]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxSR3">
                                                            &nbsp;Modify
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxSR4" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['SR'][3]->id;?>" <?php if(in_array($permissions['SR'][3]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxSR4">
                                                            &nbsp;Delete
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxSR5" type="checkbox" class="styled" name="permission_id[]" value="<?php echo $permissions['SR'][4]->id;?>" <?php if(in_array($permissions['SR'][4]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxSR5">
                                                            &nbsp;Print
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxSR6" type="checkbox" class="styled" name="permission_id[]" value="<?php echo $permissions['SR'][5]->id;?>" <?php if(in_array($permissions['SR'][5]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxSR6">
                                                            &nbsp;Edit Invoice No.
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
									  </div>
									</div>
									</div>
							</div>
							
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion" href="#collapseGin">Goods Issued Note</a>
										<input type="hidden" name="section[]" value="GIN">
									</h4>
								</div>
								<div id="collapseGin" class="panel-collapse collapse">
									<div class="panel-body" style="padding-left:80px !important;">
									  <div class="box-body">
										<div class="row">
                                            <div class="col-md-12">
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxGin1" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['GIN'][0]->id;?>" <?php if(in_array($permissions['GIN'][0]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxGin1">
                                                            &nbsp;List
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxGin2" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['GIN'][1]->id;?>" <?php if(in_array($permissions['GIN'][1]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxGin2">
                                                            &nbsp;Create
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxGin3" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['GIN'][2]->id;?>" <?php if(in_array($permissions['GIN'][2]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxGin3">
                                                            &nbsp;Modify
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxGin4" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['GIN'][3]->id;?>" <?php if(in_array($permissions['GIN'][3]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxGin4">
                                                            &nbsp;Delete
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxGin5" type="checkbox" class="styled" name="permission_id[]" value="<?php echo $permissions['GIN'][4]->id;?>" <?php if(in_array($permissions['GIN'][4]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxGin5">
                                                            &nbsp;Print
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxGin6" type="checkbox" class="styled" name="permission_id[]" value="<?php echo $permissions['GIN'][5]->id;?>" <?php if(in_array($permissions['GIN'][5]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxGin6">
                                                            &nbsp;Edit Invoice No.
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
									  </div>
									</div>
								</div>
							</div>
							
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion" href="#collapseGr">Goods Return</a>
										<input type="hidden" name="section[]" value="GR">
									</h4>
								</div>
								<div id="collapseGr" class="panel-collapse collapse">
									<div class="panel-body" style="padding-left:80px !important;">
									  <div class="box-body">
										<div class="row">
                                            <div class="col-md-12">
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxGr1" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['GR'][0]->id;?>" <?php if(in_array($permissions['GR'][0]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxGr1">
                                                            &nbsp;List
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxGr2" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['GR'][1]->id;?>" <?php if(in_array($permissions['GR'][1]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxGr2">
                                                            &nbsp;Create
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxGr3" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['GR'][2]->id;?>" <?php if(in_array($permissions['GR'][2]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxGr3">
                                                            &nbsp;Modify
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxGr4" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['GR'][3]->id;?>" <?php if(in_array($permissions['GR'][3]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxGr4">
                                                            &nbsp;Delete
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxGr5" type="checkbox" class="styled" name="permission_id[]" value="<?php echo $permissions['GR'][4]->id;?>" <?php if(in_array($permissions['GR'][4]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxGr5">
                                                            &nbsp;Print
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxGr6" type="checkbox" class="styled" name="permission_id[]" value="<?php echo $permissions['GR'][5]->id;?>" <?php if(in_array($permissions['GR'][5]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxGr6">
                                                            &nbsp;Edit Invoice No.
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
									  </div>
									</div>
								</div>
							</div>
							
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion" href="#collapseJbe">Job Estimate</a>
										<input type="hidden" name="section[]" value="JBE">
									</h4>
								</div>
								<div id="collapseJbe" class="panel-collapse collapse">
									<div class="panel-body" style="padding-left:80px !important;">
									  <div class="box-body">
										<div class="row">
                                            <div class="col-md-12">
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxJbe1" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['JBE'][0]->id;?>" <?php if(in_array($permissions['JBE'][0]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxJbe1">
                                                            &nbsp;List
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxJbe2" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['JBE'][1]->id;?>" <?php if(in_array($permissions['JBE'][1]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxJbe2">
                                                            &nbsp;Create
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxJbe3" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['JBE'][2]->id;?>" <?php if(in_array($permissions['JBE'][2]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxJbe3">
                                                            &nbsp;Modify
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxJbe4" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['JBE'][3]->id;?>" <?php if(in_array($permissions['JBE'][3]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxJbe4">
                                                            &nbsp;Delete
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxJbe5" type="checkbox" class="styled" name="permission_id[]" value="<?php echo $permissions['JBE'][4]->id;?>" <?php if(in_array($permissions['JBE'][4]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxJbe5">
                                                            &nbsp;Print
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxJbe6" type="checkbox" class="styled" name="permission_id[]" value="<?php echo $permissions['JBE'][5]->id;?>" <?php if(in_array($permissions['JBE'][5]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxJbe6">
                                                            &nbsp;Edit Invoice No.
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
									  </div>
									</div>
								</div>
							</div>
							
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion" href="#collapseJbo">Job Order</a>
										<input type="hidden" name="section[]" value="JBO">
									</h4>
								</div>
								<div id="collapseJbo" class="panel-collapse collapse">
									<div class="panel-body" style="padding-left:80px !important;">
									  <div class="box-body">
										<div class="row">
                                            <div class="col-md-12">
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxJbo1" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['JBO'][0]->id;?>" <?php if(in_array($permissions['JBO'][0]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxJbo1">
                                                            &nbsp;List
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxJbo2" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['JBO'][1]->id;?>" <?php if(in_array($permissions['JBO'][1]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxJbo2">
                                                            &nbsp;Create
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxJbo3" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['JBO'][2]->id;?>" <?php if(in_array($permissions['JBO'][2]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxJbo3">
                                                            &nbsp;Modify
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxJbo4" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['JBO'][3]->id;?>" <?php if(in_array($permissions['JBO'][3]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxJbo4">
                                                            &nbsp;Delete
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxJbo5" type="checkbox" class="styled" name="permission_id[]" value="<?php echo $permissions['JBO'][4]->id;?>" <?php if(in_array($permissions['JBO'][4]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxJbo5">
                                                            &nbsp;Print
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxJbo6" type="checkbox" class="styled" name="permission_id[]" value="<?php echo $permissions['JBO'][5]->id;?>" <?php if(in_array($permissions['JBO'][5]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxJbo6">
                                                            &nbsp;Edit Invoice No.
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
									  </div>
									</div>
								</div>
							</div>
							
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion" href="#collapseJbi">Job Invoice</a>
										<input type="hidden" name="section[]" value="JBI">
									</h4>
								</div>
								<div id="collapseJbi" class="panel-collapse collapse">
									<div class="panel-body" style="padding-left:80px !important;">
									  <div class="box-body">
										<div class="row">
                                            <div class="col-md-12">
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxJbi1" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['JBI'][0]->id;?>" <?php if(in_array($permissions['JBI'][0]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxJbi1">
                                                            &nbsp;List
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxJbi2" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['JBI'][1]->id;?>" <?php if(in_array($permissions['JBI'][1]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxJbi2">
                                                            &nbsp;Create
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxJbi3" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['JBI'][2]->id;?>" <?php if(in_array($permissions['JBI'][2]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxJbi3">
                                                            &nbsp;Modify
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxJbi4" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['JBI'][3]->id;?>" <?php if(in_array($permissions['JBI'][3]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxJbi4">
                                                            &nbsp;Delete
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxJbi5" type="checkbox" class="styled" name="permission_id[]" value="<?php echo $permissions['JBI'][4]->id;?>" <?php if(in_array($permissions['JBI'][4]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxJbi5">
                                                            &nbsp;Print
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxJbi6" type="checkbox" class="styled" name="permission_id[]" value="<?php echo $permissions['JBI'][5]->id;?>" <?php if(in_array($permissions['JBI'][5]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxJbi6">
                                                            &nbsp;Edit Invoice No.
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
									  </div>
									</div>
								</div>
							</div>
							
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion" href="#collapseLtr">Location Transfer</a>
										<input type="hidden" name="section[]" value="LTR">
									</h4>
								</div>
								<div id="collapseLtr" class="panel-collapse collapse">
									<div class="panel-body" style="padding-left:80px !important;">
									  <div class="box-body">
										<div class="row">
                                            <div class="col-md-12">
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxLtr1" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['LTR'][0]->id;?>" <?php if(in_array($permissions['LTR'][0]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxLtr1">
                                                            &nbsp;List
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxLtr2" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['LTR'][1]->id;?>" <?php if(in_array($permissions['LTR'][1]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxLtr2">
                                                            &nbsp;Create
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxLtr3" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['LTR'][2]->id;?>" <?php if(in_array($permissions['LTR'][2]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxLtr3">
                                                            &nbsp;Modify
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxLtr4" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['LTR'][3]->id;?>" <?php if(in_array($permissions['LTR'][3]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxLtr4">
                                                            &nbsp;Delete
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxLtr5" type="checkbox" class="styled" name="permission_id[]" value="<?php echo $permissions['LTR'][4]->id;?>" <?php if(in_array($permissions['LTR'][4]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxLtr5">
                                                            &nbsp;Print
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    
                                                </div>
                                            </div>
                                        </div>
									  </div>
									</div>
								</div>
							</div>
							
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion" href="#collapseSti">Stock Transfer in</a>
										<input type="hidden" name="section[]" value="STI">
									</h4>
								</div>
								<div id="collapseSti" class="panel-collapse collapse">
									<div class="panel-body" style="padding-left:80px !important;">
									  <div class="box-body">
										<div class="row">
                                            <div class="col-md-12">
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxSti1" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['STI'][0]->id;?>" <?php if(in_array($permissions['STI'][0]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxSti1">
                                                            &nbsp;List
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxSti2" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['STI'][1]->id;?>" <?php if(in_array($permissions['STI'][1]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxSti2">
                                                            &nbsp;Create
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxSti3" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['STI'][2]->id;?>" <?php if(in_array($permissions['STI'][2]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxSti3">
                                                            &nbsp;Modify
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxSti4" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['STI'][3]->id;?>" <?php if(in_array($permissions['STI'][3]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxSti4">
                                                            &nbsp;Delete
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxSti5" type="checkbox" class="styled" name="permission_id[]" value="<?php echo $permissions['STI'][4]->id;?>" <?php if(in_array($permissions['STI'][4]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxSti5">
                                                            &nbsp;Print
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxSti6" type="checkbox" class="styled" name="permission_id[]" value="<?php echo $permissions['STI'][5]->id;?>" <?php if(in_array($permissions['STI'][5]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxSti6">
                                                            &nbsp;Edit Invoice No.
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
									  </div>
									</div>
								</div>
							</div>
							
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion" href="#collapseSto">Stock Transfer Out</a>
										<input type="hidden" name="section[]" value="STO">
									</h4>
								</div>
								<div id="collapseSto" class="panel-collapse collapse">
									<div class="panel-body" style="padding-left:80px !important;">
									  <div class="box-body">
										<div class="row">
                                            <div class="col-md-12">
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxSto1" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['STO'][0]->id;?>" <?php if(in_array($permissions['STO'][0]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxSto1">
                                                            &nbsp;List
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxSto2" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['STO'][1]->id;?>" <?php if(in_array($permissions['STO'][1]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxSto2">
                                                            &nbsp;Create
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxSto3" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['STO'][2]->id;?>" <?php if(in_array($permissions['STO'][2]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxSto3">
                                                            &nbsp;Modify
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxSto4" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['STO'][3]->id;?>" <?php if(in_array($permissions['STO'][3]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxSto4">
                                                            &nbsp;Delete
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxSto5" type="checkbox" class="styled" name="permission_id[]" value="<?php echo $permissions['STO'][4]->id;?>" <?php if(in_array($permissions['STO'][4]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxSto5">
                                                            &nbsp;Print
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxSto6" type="checkbox" class="styled" name="permission_id[]" value="<?php echo $permissions['STO'][5]->id;?>" <?php if(in_array($permissions['STO'][5]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxSto6">
                                                            &nbsp;Edit Invoice No.
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
									  </div>
									</div>
								</div>
							</div>
							
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion" href="#collapseSeven">Journal Voucher</a>
									</h4>
								</div>
								<div id="collapseSeven" class="panel-collapse collapse">
								<div class="panel-body" style="padding-left:80px !important;">
									  <div class="box-body">
										<div class="row">
                                            <div class="col-md-12">
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox33" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['JV'][0]->id;?>" <?php if(in_array($permissions['JV'][0]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox33">
                                                            &nbsp;List
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox34" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['JV'][1]->id;?>" <?php if(in_array($permissions['JV'][1]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox34">
                                                            &nbsp;Create
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox35" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['JV'][2]->id;?>" <?php if(in_array($permissions['JV'][2]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox35">
                                                            &nbsp;Modify
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox36" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['JV'][3]->id;?>" <?php if(in_array($permissions['JV'][3]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox36">
                                                            &nbsp;Delete
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox37" type="checkbox" class="styled" name="permission_id[]" value="<?php echo $permissions['JV'][4]->id;?>" <?php if(in_array($permissions['JV'][4]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox37">
                                                            &nbsp;Print
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox38" type="checkbox" class="styled" name="permission_id[]" value="<?php echo $permissions['JV'][5]->id;?>" <?php if(in_array($permissions['JV'][5]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox38">
                                                            &nbsp;Edit Invoice No.
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
									  </div>
									</div>
									</div>
							</div>
							
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion" href="#collapseEight">Receipt Voucher</a>
									</h4>
								</div>
								<div id="collapseEight" class="panel-collapse collapse">
								<div class="panel-body" style="padding-left:80px !important;">
									  <div class="box-body">
										<div class="row">
                                            <div class="col-md-12">
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox39" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['RV'][0]->id;?>" <?php if(in_array($permissions['RV'][0]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox39">
                                                            &nbsp;List
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox40" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['RV'][1]->id;?>" <?php if(in_array($permissions['RV'][1]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox40">
                                                            &nbsp;Create
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox41" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['RV'][2]->id;?>" <?php if(in_array($permissions['RV'][2]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox41">
                                                            &nbsp;Modify
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox42" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['RV'][3]->id;?>" <?php if(in_array($permissions['RV'][3]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox42">
                                                            &nbsp;Delete
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox43" type="checkbox" class="styled" name="permission_id[]" value="<?php echo $permissions['RV'][4]->id;?>" <?php if(in_array($permissions['RV'][4]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox43">
                                                            &nbsp;Print
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox44" type="checkbox" class="styled" name="permission_id[]" value="<?php echo $permissions['RV'][5]->id;?>" <?php if(in_array($permissions['RV'][5]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox44">
                                                            &nbsp;Edit Invoice No.
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
									  </div>
									</div>
								</div>
							</div>
							
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion" href="#collapseNine">Payment Voucher</a>
									</h4>
								</div>
								<div id="collapseNine" class="panel-collapse collapse">
								<div class="panel-body" style="padding-left:80px !important;">
									  <div class="box-body">
										<div class="row">
                                            <div class="col-md-12">
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox45" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['PV'][0]->id;?>" <?php if(in_array($permissions['PV'][0]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox45">
                                                            &nbsp;List
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox46" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['PV'][1]->id;?>" <?php if(in_array($permissions['PV'][1]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox46">
                                                            &nbsp;Create
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox47" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['PV'][2]->id;?>" <?php if(in_array($permissions['PV'][2]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox47">
                                                            &nbsp;Modify
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox48" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['PV'][3]->id;?>" <?php if(in_array($permissions['PV'][3]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox48">
                                                            &nbsp;Delete
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox49" type="checkbox" class="styled" name="permission_id[]" value="<?php echo $permissions['PV'][4]->id;?>" <?php if(in_array($permissions['PV'][4]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox49">
                                                            &nbsp;Print
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox50" type="checkbox" class="styled" name="permission_id[]" value="<?php echo $permissions['PV'][5]->id;?>" <?php if(in_array($permissions['PV'][5]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox50">
                                                            &nbsp;Edit Invoice No.
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
									  </div>
									</div>
								</div>
							</div>
							
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion" href="#collapseTen">Purchase Voucher</a>
									</h4>
								</div>
								<div id="collapseTen" class="panel-collapse collapse">
								<div class="panel-body" style="padding-left:80px !important;">
									  <div class="box-body">
										<div class="row">
                                            <div class="col-md-12">
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox51" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['VP'][0]->id;?>" <?php if(in_array($permissions['VP'][0]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox51">
                                                            &nbsp;List
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox52" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['VP'][1]->id;?>" <?php if(in_array($permissions['VP'][1]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox52">
                                                            &nbsp;Create
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox53" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['VP'][2]->id;?>" <?php if(in_array($permissions['VP'][2]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox53">
                                                            &nbsp;Modify
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox54" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['VP'][3]->id;?>" <?php if(in_array($permissions['VP'][3]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox54">
                                                            &nbsp;Delete
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox55" type="checkbox" class="styled" name="permission_id[]" value="<?php echo $permissions['VP'][4]->id;?>" <?php if(in_array($permissions['VP'][4]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox55">
                                                            &nbsp;Print
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox56" type="checkbox" class="styled" name="permission_id[]" value="<?php echo $permissions['VP'][5]->id;?>" <?php if(in_array($permissions['VP'][5]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox56">
                                                            &nbsp;Edit Invoice No.
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
									  </div>
									</div>
								</div>
							</div>
							
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion" href="#collapseEleven">Sales Voucher</a>
									</h4>
								</div>
								<div id="collapseEleven" class="panel-collapse collapse">
								<div class="panel-body" style="padding-left:80px !important;">
									  <div class="box-body">
										<div class="row">
                                            <div class="col-md-12">
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox57" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['VS'][0]->id;?>" <?php if(in_array($permissions['VS'][0]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox57">
                                                            &nbsp;List
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox58" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['VS'][1]->id;?>" <?php if(in_array($permissions['VS'][1]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox58">
                                                            &nbsp;Create
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox59" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['VS'][2]->id;?>" <?php if(in_array($permissions['VS'][2]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox59">
                                                            &nbsp;Modify
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox60" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['VS'][3]->id;?>" <?php if(in_array($permissions['VS'][3]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox60">
                                                            &nbsp;Delete
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox61" type="checkbox" class="styled" name="permission_id[]" value="<?php echo $permissions['VS'][4]->id;?>" <?php if(in_array($permissions['VS'][4]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox61">
                                                            &nbsp;Print
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox62" type="checkbox" class="styled" name="permission_id[]" value="<?php echo $permissions['VS'][5]->id;?>" <?php if(in_array($permissions['VS'][5]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox62">
                                                            &nbsp;Edit Invoice No.
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
									  </div>
									</div>
								</div>
							</div>
							
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion" href="#collapseTwelve">Petty Cash Voucher</a>
									</h4>
								</div>
								<div id="collapseTwelve" class="panel-collapse collapse">
								<div class="panel-body" style="padding-left:80px !important;">
									  <div class="box-body">
										<div class="row">
                                            <div class="col-md-12">
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox63" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['PC'][0]->id;?>" <?php if(in_array($permissions['PC'][0]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox63">
                                                            &nbsp;List
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox64" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['PC'][1]->id;?>" <?php if(in_array($permissions['PC'][1]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox64">
                                                            &nbsp;Create
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox65" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['PC'][2]->id;?>" <?php if(in_array($permissions['PC'][2]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox65">
                                                            &nbsp;Modify
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox66" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['PC'][3]->id;?>" <?php if(in_array($permissions['PC'][3]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox66">
                                                            &nbsp;Delete
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox67" type="checkbox" class="styled" name="permission_id[]" value="<?php echo $permissions['PC'][4]->id;?>" <?php if(in_array($permissions['PC'][4]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox67">
                                                            &nbsp;Print
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox68" type="checkbox" class="styled" name="permission_id[]" value="<?php echo $permissions['PC'][5]->id;?>" <?php if(in_array($permissions['PC'][5]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox68">
                                                            &nbsp;Edit Invoice No.
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
									  </div>
									</div>
								</div>
							</div>
							
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion" href="#collapseThirteen">Advance Set Off</a>
									</h4>
								</div>
								<div id="collapseThirteen" class="panel-collapse collapse">
								<div class="panel-body" style="padding-left:80px !important;">
									  <div class="box-body">
										<div class="row">
                                            <div class="col-md-12">
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox69" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['AS'][0]->id;?>" <?php if(in_array($permissions['AS'][0]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox69">
                                                            &nbsp;List
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox70" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['AS'][1]->id;?>" <?php if(in_array($permissions['AS'][1]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox70">
                                                            &nbsp;Create
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
									  </div>
									</div>
								</div>
							</div>
							
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion" href="#collapseCrn">Credit Note</a>
									</h4>
								</div>
								<div id="collapseCrn" class="panel-collapse collapse">
								<div class="panel-body" style="padding-left:80px !important;">
									  <div class="box-body">
										<div class="row">
                                            <div class="col-md-12">
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxCrn1" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['CN'][0]->id;?>" <?php if(in_array($permissions['CN'][0]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxCrn1">
                                                            &nbsp;List
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxCrn2" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['CN'][1]->id;?>" <?php if(in_array($permissions['CN'][1]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxCrn2">
                                                            &nbsp;Create
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxCrn3" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['CN'][2]->id;?>" <?php if(in_array($permissions['CN'][2]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxCrn3">
                                                            &nbsp;Modify
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxCrn4" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['CN'][3]->id;?>" <?php if(in_array($permissions['CN'][3]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxCrn4">
                                                            &nbsp;Delete
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxCrn4" type="checkbox" class="styled" name="permission_id[]" value="<?php echo $permissions['CN'][4]->id;?>" <?php if(in_array($permissions['CN'][4]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxCrn5">
                                                            &nbsp;Print
                                                        </label>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </div>
									  </div>
									</div>
								</div>
							</div>
							
							
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion" href="#collapseDrn">Debit Note</a>
									</h4>
								</div>
								<div id="collapseDrn" class="panel-collapse collapse">
								<div class="panel-body" style="padding-left:80px !important;">
									  <div class="box-body">
										<div class="row">
                                            <div class="col-md-12">
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxDrn1" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['DN'][0]->id;?>" <?php if(in_array($permissions['DN'][0]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxDrn1">
                                                            &nbsp;List
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxDrn2" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['DN'][1]->id;?>" <?php if(in_array($permissions['DN'][1]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxDrn2">
                                                            &nbsp;Create
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxDrn3" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['DN'][2]->id;?>" <?php if(in_array($permissions['DN'][2]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxDrn3">
                                                            &nbsp;Modify
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxDrn4" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['DN'][3]->id;?>" <?php if(in_array($permissions['DN'][3]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxDrn4">
                                                            &nbsp;Delete
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxDrn4" type="checkbox" class="styled" name="permission_id[]" value="<?php echo $permissions['DN'][4]->id;?>" <?php if(in_array($permissions['DN'][4]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxDrn5">
                                                            &nbsp;Print
                                                        </label>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </div>
									  </div>
									</div>
								</div>
							</div>
							
							
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion" href="#collapseFourteen">PDC Received</a>
									</h4>
								</div>
								<div id="collapseFourteen" class="panel-collapse collapse">
								<div class="panel-body" style="padding-left:80px !important;">
									  <div class="box-body">
										<div class="row">
                                            <div class="col-md-12">
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox71" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['PDR'][0]->id;?>" <?php if(in_array($permissions['PDR'][0]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox71">
                                                            &nbsp;List
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox72" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['PDR'][1]->id;?>" <?php if(in_array($permissions['PDR'][1]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox72">
                                                            &nbsp;Submit
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox73" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['PDR'][2]->id;?>" <?php if(in_array($permissions['PDR'][2]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox73">
                                                            &nbsp;Undo
                                                        </label>
                                                    </div>
                                                </div>
                                               
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox74" type="checkbox" class="styled" name="permission_id[]" value="<?php echo $permissions['PDR'][3]->id;?>" <?php if(in_array($permissions['PDR'][3]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox74">
                                                            &nbsp;Print
                                                        </label>
                                                    </div>
                                                </div>
                                               
                                            </div>
                                        </div>
									  </div>
									</div>
								</div>
							</div>
							
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion" href="#collapseFifteen">PRC Issued</a>
									</h4>
								</div>
								<div id="collapseFifteen" class="panel-collapse collapse">
								<div class="panel-body" style="padding-left:80px !important;">
									  <div class="box-body">
										<div class="row">
                                            <div class="col-md-12">
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox75" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['PDI'][0]->id;?>" <?php if(in_array($permissions['PDI'][0]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox75">
                                                            &nbsp;List
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox76" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['PDI'][1]->id;?>" <?php if(in_array($permissions['PDI'][1]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox76">
                                                            &nbsp;Submit
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox77" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['PDI'][2]->id;?>" <?php if(in_array($permissions['PDI'][2]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox77">
                                                            &nbsp;Undo
                                                        </label>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox78" type="checkbox" class="styled" name="permission_id[]" value="<?php echo $permissions['PDI'][3]->id;?>" <?php if(in_array($permissions['PDI'][3]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox78">
                                                            &nbsp;Print
                                                        </label>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </div>
									  </div>
									</div>
								</div>
							</div>
							
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion" href="#collapseRpt">Reports</a>
									</h4>
								</div>
								<div id="collapseRpt" class="panel-collapse collapse">
								<div class="panel-body" style="padding-left:80px !important;">
									  <div class="box-body">
										<div class="row">
                                            <div class="col-md-12">
												
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxRpt1" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['RPT'][0]->id;?>" <?php if(in_array($permissions['RPT'][0]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxRpt1">
                                                            &nbsp;Trial Balance
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxRpt2" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['RPT'][1]->id;?>" <?php if(in_array($permissions['RPT'][1]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxRpt2">
                                                            &nbsp;Profit & Loss
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxRpt3" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['RPT'][2]->id;?>" <?php if(in_array($permissions['RPT'][2]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxRpt3">
                                                            &nbsp;Balance Sheet
                                                        </label>
                                                    </div>
                                                </div>
                                                
												<div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxRptvs" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['RPT'][3]->id;?>" <?php if(in_array($permissions['RPT'][3]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxRptvs">
                                                            &nbsp;Voucherwise Report
                                                        </label>
                                                    </div>
                                                </div>
												
												
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxRpt4" type="checkbox" class="styled" name="permission_id[]" value="<?php echo $permissions['RPT'][4]->id;?>" <?php if(in_array($permissions['RPT'][4]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxRpt4">
                                                            &nbsp;Statement of Accounts
                                                        </label>
                                                    </div>
                                                </div>
												
												<div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxRpt5" type="checkbox" class="styled" name="permission_id[]" value="<?php echo $permissions['RPT'][5]->id;?>" <?php if(in_array($permissions['RPT'][5]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxRpt5">
                                                            &nbsp;Outstanding Statement
                                                        </label>
                                                    </div>
                                                </div>
												
												<div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxRpt6" type="checkbox" class="styled" name="permission_id[]" value="<?php echo $permissions['RPT'][6]->id;?>" <?php if(in_array($permissions['RPT'][6]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxRpt6">
                                                            &nbsp;Ageing Statement
                                                        </label>
                                                    </div>
                                                </div>
                                                
												<div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxRptqs" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['RPT'][7]->id;?>" <?php if(in_array($permissions['RPT'][7]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxRptqs">
                                                            &nbsp;Quantity Report
                                                        </label>
                                                    </div>
                                                </div>
												
												<div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxRptsl" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['RPT'][8]->id;?>" <?php if(in_array($permissions['RPT'][8]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxRptsl">
                                                            &nbsp;Stock Ledger
                                                        </label>
                                                    </div>
                                                </div>
												
												<div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxRptpa" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['RPT'][9]->id;?>" <?php if(in_array($permissions['RPT'][9]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxRptpa">
                                                            &nbsp;Profit Analysis
                                                        </label>
                                                    </div>
                                                </div>
												
												<div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxRptvr" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['RPT'][10]->id;?>" <?php if(in_array($permissions['RPT'][10]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxRptvr">
                                                            &nbsp;VAT Report
                                                        </label>
                                                    </div>
                                                </div>
												
												<div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxRptjr" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['RPT'][11]->id;?>" <?php if(in_array($permissions['RPT'][11]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxRptjr">
                                                            &nbsp;Job Report
                                                        </label>
                                                    </div>
                                                </div>
												
												<div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxRptlm" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['RPT'][12]->id;?>" <?php if(in_array($permissions['RPT'][12]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxRptlm">
                                                            &nbsp;Ledger Moments
                                                        </label>
                                                    </div>
                                                </div>
												
												<div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxRptpur" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['RPT'][13]->id;?>" <?php if(in_array($permissions['RPT'][13]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxRptpur">
                                                            &nbsp;Purchase Report
                                                        </label>
                                                    </div>
                                                </div>
												
												<div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxRptSls" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['RPT'][14]->id;?>" <?php if(in_array($permissions['RPT'][14]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxRptSls">
                                                            &nbsp;Sales Report
                                                        </label>
                                                    </div>
                                                </div>
												
												<div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxRptPdc" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['RPT'][15]->id;?>" <?php if(in_array($permissions['RPT'][15]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxRptPdc">
                                                            &nbsp;PDC Report
                                                        </label>
                                                    </div>
                                                </div>
												
												<div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxRptCS" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['RPT'][16]->id;?>" <?php if(in_array($permissions['RPT'][16]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxRptCS">
                                                            &nbsp;Customer/Supplier Report
                                                        </label>
                                                    </div>
                                                </div>
												
												<div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxRptTLs" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['RPT'][17]->id;?>" <?php if(in_array($permissions['RPT'][17]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxRptTLs">
                                                            &nbsp;Transaction List
                                                        </label>
                                                    </div>
                                                </div>
												
                                            </div>
                                        </div>
									  </div>
									</div>
								</div>
							</div>							
							
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion" href="#collapseUsrm">User Management</a>
										<input type="hidden" name="section[]" value="UM">
									</h4>
								</div>
								<div id="collapseUsrm" class="panel-collapse collapse">
									<div class="panel-body" style="padding-left:80px !important;">
									  <div class="box-body">
										<div class="row">
                                            <div class="col-md-12">
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxUsrm1" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['UM'][0]->id;?>" <?php if(in_array($permissions['UM'][0]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxUsrm1">
                                                            &nbsp;List
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxUsrm2" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['UM'][1]->id;?>" <?php if(in_array($permissions['UM'][1]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxUsrm2">
                                                            &nbsp;Create
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxUsrm3" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['UM'][2]->id;?>" <?php if(in_array($permissions['UM'][2]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxUsrm3">
                                                            &nbsp;Modify
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxUsrm4" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['UM'][3]->id;?>" <?php if(in_array($permissions['UM'][3]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxUsrm4">
                                                            &nbsp;Delete
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxUsrm5" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['UM'][4]->id;?>" <?php if(in_array($permissions['UM'][4]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxUsrm5">
                                                            &nbsp;Minus Quantity Sale
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxUsrm6" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['UM'][5]->id;?>" <?php if(in_array($permissions['UM'][5]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxUsrm6">
                                                            &nbsp;Below Cost Sale
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
									  </div>
									</div>
								</div>
							</div>
							
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion" href="#collapseRolm">Role Management</a>
										<input type="hidden" name="section[]" value="PO">
									</h4>
								</div>
								<div id="collapseRolm" class="panel-collapse collapse">
									<div class="panel-body" style="padding-left:80px !important;">
									  <div class="box-body">
										<div class="row">
                                            <div class="col-md-12">
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxRollm1" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['RM'][0]->id;?>" <?php if(in_array($permissions['RM'][0]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxRollm1">
                                                            &nbsp;List
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxRollm2" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['RM'][1]->id;?>" <?php if(in_array($permissions['RM'][1]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxRollm2">
                                                            &nbsp;Edit
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    
                                                </div>
                                            </div>
                                        </div>
									  </div>
									</div>
								</div>
							</div>
							
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion" href="#collapseAdm"> Administaration</a>
										<input type="hidden" name="section[]" value="ADM">
									</h4>
								</div>
								<div id="collapseAdm" class="panel-collapse collapse">
									<div class="panel-body" style="padding-left:80px !important;">
									  <div class="box-body">
										<div class="row">
                                            <div class="col-md-12">
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxAdm1" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['ADM'][0]->id;?>" <?php if(in_array($permissions['ADM'][0]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxAdm1">
                                                            &nbsp;Company
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxAdm2" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['ADM'][1]->id;?>" <?php if(in_array($permissions['ADM'][1]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxAdm2">
                                                            &nbsp;Other Account Settings
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxAdm3" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['ADM'][2]->id;?>" <?php if(in_array($permissions['ADM'][2]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxAdm3">
                                                            &nbsp;Voucher Numbers
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxAdm4" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['ADM'][3]->id;?>" <?php if(in_array($permissions['ADM'][3]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxAdm4">
                                                            &nbsp;System Parameters
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxAdm5" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['ADM'][4]->id;?>" <?php if(in_array($permissions['ADM'][4]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxAdm5">
                                                            &nbsp;Utilities
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxAdm6" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['ADM'][5]->id;?>" <?php if(in_array($permissions['ADM'][5]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxAdm6">
                                                            &nbsp;Log Details
                                                        </label>
                                                    </div>
                                                </div>
												
												<div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxAdm7" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['ADM'][6]->id;?>" <?php if(in_array($permissions['ADM'][6]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxAdm7">
                                                            &nbsp;Backup
                                                        </label>
                                                    </div>
                                                </div>
												
												<div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxAdm8" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['ADM'][7]->id;?>" <?php if(in_array($permissions['ADM'][7]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxAdm8">
                                                            &nbsp;Year Ending Wizard
                                                        </label>
                                                    </div>
                                                </div>
												
												<div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxAdm8" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['ADM'][8]->id;?>" <?php if(in_array($permissions['ADM'][8]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxAdm9">
                                                            &nbsp;Entry Form
                                                        </label>
                                                    </div>
                                                </div>
												
                                            </div>
                                        </div>
									  </div>
									</div>
								</div>
							</div>
							
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion" href="#collapseAcst">Account Settings</a>
										<input type="hidden" name="section[]" value="ACST">
									</h4>
								</div>
								<div id="collapseAcst" class="panel-collapse collapse">
									<div class="panel-body" style="padding-left:80px !important;">
									  <div class="box-body">
										<div class="row">
                                            <div class="col-md-12">
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxacst1" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['ACST'][0]->id;?>" <?php if(in_array($permissions['ACST'][0]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxacst1">
                                                            &nbsp;List
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxacst2" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['ACST'][1]->id;?>" <?php if(in_array($permissions['ACST'][1]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxacst2">
                                                            &nbsp;Create
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxacst3" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['ACST'][2]->id;?>" <?php if(in_array($permissions['ACST'][2]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxacst3">
                                                            &nbsp;Modify
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxacst4" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['ACST'][3]->id;?>" <?php if(in_array($permissions['ACST'][3]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxacst4">
                                                            &nbsp;Delete
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    
                                                </div>
                                            </div>
                                        </div>
									  </div>
									</div>
								</div>
							</div>
							
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion" href="#collapseEmp">Employee</a>
										<input type="hidden" name="section[]" value="EMP">
									</h4>
								</div>
								<div id="collapseEmp" class="panel-collapse collapse">
									<div class="panel-body" style="padding-left:80px !important;">
									  <div class="box-body">
										<div class="row">
                                            <div class="col-md-12">
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxEmp1" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['EMP'][0]->id;?>" <?php if(in_array($permissions['EMP'][0]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxEmp1">
                                                            &nbsp;List
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxEmp2" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['EMP'][1]->id;?>" <?php if(in_array($permissions['EMP'][1]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxEmp2">
                                                            &nbsp;Create
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxEmp3" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['EMP'][2]->id;?>" <?php if(in_array($permissions['EMP'][2]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxEmp3">
                                                            &nbsp;Modify
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxEmp4" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['EMP'][3]->id;?>" <?php if(in_array($permissions['EMP'][3]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxEmp4">
                                                            &nbsp;Delete
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxEmp5" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['EMP'][4]->id;?>" <?php if(in_array($permissions['EMP'][4]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxEmp5">
                                                            &nbsp;View
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                     <div class="checkbox checkbox-primary">
                                                        <input id="checkboxEmp6" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['EMP'][5]->id;?>" <?php if(in_array($permissions['EMP'][5]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxEmp6">
                                                            &nbsp;Leave Process
                                                        </label>
                                                    </div>
                                                </div>
												
												<div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxEmp7" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['EMP'][6]->id;?>" <?php if(in_array($permissions['EMP'][6]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxEmp7">
                                                            &nbsp;Rejoin
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxEmp8" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['EMP'][7]->id;?>" <?php if(in_array($permissions['EMP'][7]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxEmp8">
                                                            &nbsp;Undo Rejoin
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                     <div class="checkbox checkbox-primary">
                                                        <input id="checkboxEmp9" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['EMP'][8]->id;?>" <?php if(in_array($permissions['EMP'][8]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxEmp9">
                                                            &nbsp;Termination/Resign
                                                        </label>
                                                    </div>
                                                </div>
												
												 <div class="col-lg-4 col-sm-6">
                                                     <div class="checkbox checkbox-primary">
                                                        <input id="checkboxEmp10" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['EMP'][9]->id;?>" <?php if(in_array($permissions['EMP'][9]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxEmp10">
                                                            &nbsp;Employee Report
                                                        </label>
                                                    </div>
                                                </div>
												
                                            </div>
                                        </div>
									  </div>
									</div>
								</div>
							</div>
							
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion" href="#collapseED">Employee Document</a>
										<input type="hidden" name="section[]" value="ED">
									</h4>
								</div>
								<div id="collapseED" class="panel-collapse collapse">
									<div class="panel-body" style="padding-left:80px !important;">
									  <div class="box-body">
										<div class="row">
                                            <div class="col-md-12">
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxed1" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['ED'][0]->id;?>" <?php if(in_array($permissions['ED'][0]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxed1">
                                                            &nbsp;List
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxed2" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['ED'][1]->id;?>" <?php if(in_array($permissions['ED'][1]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxed2">
                                                            &nbsp;Create
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxed3" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['ED'][2]->id;?>" <?php if(in_array($permissions['ED'][2]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxed3">
                                                            &nbsp;Modify
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxed4" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['ED'][3]->id;?>" <?php if(in_array($permissions['ED'][3]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxed4">
                                                            &nbsp;Delete
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
									  </div>
									</div>
								</div>
							</div>
							
							
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion" href="#collapseDM">Document Master</a>
										<input type="hidden" name="section[]" value="DM">
									</h4>
								</div>
								<div id="collapseDM" class="panel-collapse collapse">
									<div class="panel-body" style="padding-left:80px !important;">
									  <div class="box-body">
										<div class="row">
                                            <div class="col-md-12">
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxDm1" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['DM'][0]->id;?>" <?php if(in_array($permissions['DM'][0]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxDm1">
                                                            &nbsp;List
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxDm2" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['DM'][1]->id;?>" <?php if(in_array($permissions['DM'][1]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxDm2">
                                                            &nbsp;Create
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxDm3" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['DM'][2]->id;?>" <?php if(in_array($permissions['DM'][2]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxDm3">
                                                            &nbsp;Modify
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxDm4" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['DM'][3]->id;?>" <?php if(in_array($permissions['DM'][3]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxDm4">
                                                            &nbsp;Delete
                                                        </label>
                                                    </div>
                                                </div>
												
												<div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxDm5" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['DM'][4]->id;?>" <?php if(in_array($permissions['DM'][4]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxDm5">
                                                            &nbsp;Report
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
									  </div>
									</div>
								</div>
							</div>
							
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion" href="#collapseAst">Assets Issued</a>
										<input type="hidden" name="section[]" value="AST">
									</h4>
								</div>
								<div id="collapseAst" class="panel-collapse collapse">
									<div class="panel-body" style="padding-left:80px !important;">
									  <div class="box-body">
										<div class="row">
                                            <div class="col-md-12">
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxAst1" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['AST'][0]->id;?>" <?php if(in_array($permissions['AST'][0]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxAst1">
                                                            &nbsp;List
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxAst2" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['AST'][1]->id;?>" <?php if(in_array($permissions['AST'][1]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxAst2">
                                                            &nbsp;Create
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxAst3" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['AST'][2]->id;?>" <?php if(in_array($permissions['AST'][2]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxAst3">
                                                            &nbsp;Modify
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxAst4" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['AST'][3]->id;?>" <?php if(in_array($permissions['AST'][3]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxAst4">
                                                            &nbsp;Delete
                                                        </label>
                                                    </div>
                                                </div>
												
                                            </div>
                                        </div>
									  </div>
									</div>
								</div>
							</div>
							
							
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion" href="#collapseProl">Payroll</a>
										<input type="hidden" name="section[]" value="PROL">
									</h4>
								</div>
								<div id="collapseProl" class="panel-collapse collapse">
									<div class="panel-body" style="padding-left:80px !important;">
									  <div class="box-body">
										<div class="row">
                                            <div class="col-md-12">
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxPr1" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['PROL'][0]->id;?>" <?php if(in_array($permissions['PROL'][0]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxPr1">
                                                            &nbsp;Wage List
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxPr2" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['PROL'][1]->id;?>" <?php if(in_array($permissions['PROL'][1]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxPr2">
                                                            &nbsp;Wage Create
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxPr3" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['PROL'][2]->id;?>" <?php if(in_array($permissions['PROL'][2]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxPr3">
                                                            &nbsp;Wage Modify
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxPr4" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['PROL'][3]->id;?>" <?php if(in_array($permissions['PROL'][3]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxPr4">
                                                            &nbsp;Wage Delete
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxPr5" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['PROL'][4]->id;?>" <?php if(in_array($permissions['PROL'][4]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxPr5">
                                                            &nbsp;Pay Slip
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                     <div class="checkbox checkbox-primary">
                                                        <input id="checkboxPr6" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['PROL'][5]->id;?>" <?php if(in_array($permissions['PROL'][5]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxPr6">
                                                            &nbsp;Job Report
                                                        </label>
                                                    </div>
                                                </div>
												
												<div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxPr7" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['PROL'][6]->id;?>" <?php if(in_array($permissions['PROL'][6]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxPr7">
                                                            &nbsp;Payroll Report
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
									  </div>
									</div>
								</div>
							</div>
							
							
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion" href="#collapseDimprt">Data Management</a>
										<input type="hidden" name="section[]" value="IMPORT">
									</h4>
								</div>
								<div id="collapseDimprt" class="panel-collapse collapse">
									<div class="panel-body" style="padding-left:80px !important;">
									  <div class="box-body">
										<div class="row">
                                            <div class="col-md-12">
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxImp1" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['IMPORT'][0]->id;?>" <?php if(in_array($permissions['IMPORT'][0]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxImp1">
                                                            &nbsp;Items
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxImp2" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['IMPORT'][1]->id;?>" <?php if(in_array($permissions['IMPORT'][1]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxImp2">
                                                            &nbsp;Customers/Suppliers
                                                        </label>
                                                    </div>
                                                </div>
												
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkboxImp3" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['IMPORT'][2]->id;?>" <?php if(in_array($permissions['IMPORT'][2]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxImp3">
                                                            &nbsp;Data Backup
                                                        </label>
                                                    </div>
                                                </div>
												
                                                <div class="col-lg-4 col-sm-6">
                                                     <div class="checkbox checkbox-primary">
                                                        <input id="checkboxImp4" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['IMPORT'][3]->id;?>" <?php if(in_array($permissions['IMPORT'][3]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkboxImp4">
                                                            &nbsp;Data Remove
                                                        </label>
                                                    </div>
                                                </div>
												
                                                <div class="col-lg-4 col-sm-6">
                                                    
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    
                                                </div>
                                            </div>
                                        </div>
									  </div>
									</div>
								</div>
							</div>
							
							<div class="panel panel-default" style="display:none;">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion" href="#collapseFivex">Role Management</a>
										<input type="hidden" name="section[]" value="R">
									</h4>
								</div>
								<div id="collapseFivex" class="panel-collapse collapse">
									<div class="panel-body" style="padding-left:80px !important;">
									  <div class="box-body">
										<div class="row">
                                            <div class="col-md-12">
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox1" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['R'][0]->id;?>" <?php if(in_array($permissions['R'][0]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox1">
                                                            &nbsp;List
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox2" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['R'][1]->id;?>" <?php if(in_array($permissions['R'][1]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox2">
                                                            &nbsp;Create
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox3" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['R'][2]->id;?>" <?php if(in_array($permissions['R'][2]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox3">
                                                            &nbsp;Modify
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox4" class="styled" type="checkbox" name="permission_id[]" value="<?php echo $permissions['R'][3]->id;?>" <?php if(in_array($permissions['R'][3]->id, $permissionrole)) echo 'checked';?>>
                                                        <label for="checkbox4">
                                                            &nbsp;Delete
                                                        </label>
                                                    </div>
                                                </div>
                                                
                                                
                                            </div>
                                        </div>
									  </div>
									</div>
								</div>
							</div>
							
							
						</div>
						 <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary submit">Submit</button>
                                        <a href="{{ url('roles') }}" class="btn btn-danger">Cancel</a>
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
<!-- end of page level js -->
<script>

</script>

@stop
