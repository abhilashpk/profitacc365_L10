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
                   Edit
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
                                <i class="fa fa-fw fa-crosshairs"></i> Edit Employee
                            </h3>
                           
                        </div>
                        <div class="panel-body">
                            <form class="form-horizontal" enctype="multipart/form-data" role="form" method="POST" name="frmEmployee" id="frmEmployee" action="{{url('employee/update/'.$row->id)}}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
								<div id="rootwizard">
                                    <ul>
                                        <li>
                                            <a href="#tab1" data-toggle="tab">Personal Details</a>
                                        </li>
                                        <li>
                                            <a href="#tab2" data-toggle="tab">Employment Details</a>
                                        </li>
                                        <li>
                                            <a href="#tab3" data-toggle="tab">Salary Details</a>
                                        </li>
										<li>
                                            <a href="#tab4" data-toggle="tab">Other Details</a>
                                        </li>
                                    </ul>
									
                                    <div class="tab-content">
                                        <div class="tab-pane" id="tab1">
                                            <h2 class="hidden">&nbsp;</h2>
                                           <input type="hidden" name="id" id="id" value="{{ $row->id }}">

                                          <div class="form-group">
                                    			<label for="input-text" class="col-sm-2 control-label"><b>Department</b></label>
                                    				<div class="col-sm-10">
                                       					<select id="department_id" class="form-control select2" style="width:100%; background-color:#85d3ef;" name="department_id">
																@foreach($departments as $drow)
															<option value="{{ $drow->id }}" {{($row->department_id==$drow->id)?'selected':''}} >{{ $drow->name }}</option>
																@endforeach
                                        				</select>
                                    				</div>
                                		   </div>

											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Employee Code </label>
												<div class="col-sm-10">
													<input type="text" class="form-control required" id="code2" name="code2" value="{{$row->code}}">
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Employee Name </label>
												<div class="col-sm-10">
													<input type="text" class="form-control" id="name" name="name"  value="{{$row->name}}">
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Designation </label>
												<div class="col-sm-10">
													<input type="text" class="form-control" id="designation" name="designation" value="{{$row->designation}}">
												</div>
											</div>

											<div class="form-group">
                                    			<label for="input-text" class="col-sm-2 control-label"><b>Division</b></label>
                                                <div class="col-sm-10">
                                                   <select id="division_id" class="form-control select2" style="width:100%; background-color:#85d3ef;" name="division_id">
											            
												           @foreach($divisions as $drow)
															<option value="{{ $drow->id }}" {{($row->division_id==$drow->id)?'selected':''}} >{{ $drow->div_name }}</option>
															@endforeach
											        
                                                   </select>
                                               </div>
                                            </div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Nationality </label>
												<div class="col-sm-10">
													<input type="text" class="form-control" id="nationality" name="nationality" value="{{$row->nationality}}">
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Date of Birth</label>
												<div class="col-sm-10">
													 <input type="text" class="form-control" name="dob" id="dob" readonly value="<?php echo ($row->dob!='0000-00-00')?date('d-m-Y',strtotime($row->dob)):'';?>" data-language='en' autocomplete="off"/>
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Gender</label>
												<div class="col-sm-10">
													<label class="radio-inline iradio">
														<input type="radio" id="inlineradio1" name="gender" <?php if($row->gender==1) echo 'checked';?> value="1" checked>
														Male
													</label>
													<label class="radio-inline iradio">
														<input type="radio" id="inlineradio2" name="gender" <?php if($row->gender==0) echo 'checked';?> value="0">
														Female
													</label>
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Residance Address</label>
												<div class="col-sm-10">
													<input type="text" class="form-control" id="address1" name="address1" value="{{$row->address1}}">
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Residance Phone No</label>
												<div class="col-sm-10">
													<input type="text" class="form-control" id="phone" name="phone" value="{{$row->phone}}">
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Home Address</label>
												<div class="col-sm-10">
													<input type="text" class="form-control" id="address2" name="address2" value="{{$row->address2}}">
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Home Phone No</label>
												<div class="col-sm-10">
													<input type="text" class="form-control" id="phone2" name="phone2" value="{{$row->phone2}}">
												</div>
											</div>
											
											<!--<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Address3</label>
												<div class="col-sm-10">
													<input type="text" class="form-control" id="address3" name="address3" value="{{$row->address3}}">
												</div>
											</div>-->
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Email</label>
												<div class="col-sm-10">
													<input type="email" class="form-control" id="email" name="email" value="{{$row->email}}">
												</div>
											</div>
											
											
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Join Date</label>
												<div class="col-sm-10">
													<input type="text" class="form-control pull-right" id="join_date" name="join_date" value="<?php echo ($row->join_date=='0000-00-00')?'':date('d-m-Y',strtotime($row->join_date));?>" data-language='en' readonly>
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Rejoin Date</label>
												<div class="col-sm-10">
													<input type="text" class="form-control pull-right" id="rejoin_date" name="rejoin_date" value="<?php echo ($row->rejoin_date=='0000-00-00')?'':date('d-m-Y',strtotime($row->rejoin_date));?>" data-language='en' readonly>
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Photo</label>
												<div class="col-sm-10">
												<?php //if($row->photo!='') { ?>
												<?php if(isset($photos['PF'])) { $arrp = explode(',',$photos['PF']); $i=1; ?>
													@foreach($arrp as $prow)
													<div class="file-preview">
														<div class="close fileinput-remove removeP" data-val="{{$prow}}">×</div>
														<div class="file-drop-disabled">
															<div class="file-preview-thumbnails">
																<div class="file-live-thumbs">
																	<a href="{{asset('uploads/employee/'.$prow)}}" target="_blank">View Photo {{$i}}</a>
																</div>
															</div>
															<div class="clearfix"></div>    <div class="file-preview-status text-center text-success"></div>
															<div class="kv-fileinput-error file-error-message" style="display: none;"></div>
														</div>
													</div>
													<?php $i++; ?>
													@endforeach
												<?php } ?>
													<input type="file" id="input-23" name="photos" class="file-loading" data-show-preview="true" data-url="{{url('employee/upload/')}}" multiple="">
													<div id="files_list"></div>
													<p id="loading"></p>
													<input type="hidden" name="photo_name" id="photo_name" value="{{isset($photos['PF'])?$photos['PF']:''}}">
													<input type="hidden" name="old_photo_name" id="old_photo_name" value="{{isset($photos['PF'])?$photos['PF']:''}}">
													<input type="hidden" name="rem_photo_name" id="rem_photo_name">
												</div>
											</div>
								
                                        </div>
                                        <div class="tab-pane" id="tab2">
											<input type="hidden" id="emp_id" name="emp_id" />
											 
                                            <h2 class="hidden">&nbsp;</h2>
											<?php if($formdata['passport']==1) { ?>
                                            <div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Passport ID</label>
												<div class="col-sm-10">
													 <input type="text" class="form-control" id="pp_id" name="pp_id" value="{{$row->pp_id}}" placeholder="Passport ID">
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Issue Date</label>
												<div class="col-sm-10">
													 <input type="text" class="form-control" id="pp_issue_date" name="pp_issue_date" value="<?php echo ($row->pp_issue_date=='0000-00-00')?'':date('d-m-Y',strtotime($row->pp_issue_date));?>" data-language='en' readonly>
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Expiry Date</label>
												<div class="col-sm-10">
													 <input type="text" class="form-control" id="pp_expiry_date" name="pp_expiry_date" value="<?php echo ($row->pp_expiry_date=='0000-00-00')?'':date('d-m-Y',strtotime($row->pp_expiry_date));?>" data-language='en' readonly>
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Issued Place</label>
												<div class="col-sm-10">
													 <input type="text" class="form-control" id="pp_issue_place" name="pp_issue_place" value="<?php echo $row->pp_issue_place;?>" placeholder="Issued Place">
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Passport Image</label>
												<div class="col-sm-10">
												<?php if(isset($photos['PSP'])) { $arrp = explode(',',$photos['PSP']); $i=1; ?>
												@foreach($arrp as $prow)
													<div class="file-preview">
														<div class="close fileinput-remove removePP" data-val="{{$prow}}">×</div>
														<div class="file-drop-disabled">
														<div class="file-preview-thumbnails">
															<div class="file-live-thumbs">
																<a href="{{asset('uploads/passport/'.$prow)}}" target="_blank">View Passport Image {{$i}}</a>
															</div>
														</div>
														
														<div class="clearfix"></div> <div class="file-preview-status text-center text-success"></div>
														<div class="kv-fileinput-error file-error-message" style="display: none;"></div>
														</div>
													</div>
												<?php $i++; ?>
												@endforeach
												<?php } ?>
													<input type="file" id="input-23p" name="pimage" class="file-loading" data-show-preview="true" data-url="{{url('employee/pupload/')}}" multiple="">
													<div id="files_list"></div>
													<p id="ploading"></p>
													<input type="hidden" name="pimage_name" id="pimage_name" value="{{isset($photos['PSP'])?$photos['PSP']:''}}">
													<input type="hidden" name="old_pimage_name" id="old_pimage_name" value="{{isset($photos['PSP'])?$photos['PSP']:''}}">
													<input type="hidden" name="rem_pimage_name" id="rem_pimage_name">
												</div>
											</div>
											<hr/>
											<?php } else { ?>
                                                     <input type="hidden" name="pp_id" id="pp_id">
													 <input type="hidden" name="pp_issue_date" id="pp_issue_date">
													 <input type="hidden" name="pp_expiry_date" id="pp_expiry_date">
													 <input type="hidden" name="pp_issue_place" id="pp_issue_place">
									                <input type="hidden" name="pimage_name" id="pimage_name">
								                <?php } ?>
											
											
											<?php if($formdata['visa']==1) { ?>
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Visa Designation</label>
												<div class="col-sm-10">
													 <input type="text" class="form-control" id="v_designation" name="v_designation" value="<?php echo $row->v_designation;?>" value="{{$row->v_designation}}" placeholder="Visa Designation">
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Visa ID</label>
												<div class="col-sm-10">
													 <input type="text" class="form-control" id="v_id" name="v_id" value="{{$row->v_id}}" placeholder="Visa ID">
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Issue Date</label>
												<div class="col-sm-10">
													 <input type="text" class="form-control" id="v_issue_date" name="v_issue_date" value="<?php echo ($row->v_issue_date!='0000-00-00')?date('d-m-Y',strtotime($row->v_issue_date)):''?>" data-language='en' readonly>
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Expiry Date</label>
												<div class="col-sm-10">
													 <input type="text" class="form-control" id="v_expiry_date" name="v_expiry_date" value="<?php echo ($row->v_expiry_date!='0000-00-00')?date('d-m-Y',strtotime($row->v_expiry_date)):''?>" data-language='en' readonly>
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Visa Image</label>
												<div class="col-sm-10">
												<?php if(isset($photos['VS'])) { $arrp = explode(',',$photos['VS']); $i=1; ?>
												@foreach($arrp as $prow)
													<div class="file-preview">
														<div class="close fileinput-remove removeV" data-val="{{$prow}}">×</div>
														<div class="file-drop-disabled">
														<div class="file-preview-thumbnails">
															<div class="file-live-thumbs"> 
																<a href="{{asset('uploads/visa/'.$prow)}}" target="_blank">View Visa Image {{$i}}</a>
															</div>
														</div>
														<div class="clearfix"></div><div class="file-preview-status text-center text-success"></div>
														<div class="kv-fileinput-error file-error-message" style="display: none;"></div>
														</div>
													</div>
												<?php $i++; ?>
												@endforeach
												<?php } ?>
													<input type="file" id="input-23v" name="vimage" class="file-loading" data-show-preview="true" data-url="{{url('employee/vupload/')}}" multiple="">
													<div id="files_list"></div>
													<p id="vloading"></p>
													<input type="hidden" name="vimage_name" id="vimage_name" value="{{isset($photos['VS'])?$photos['VS']:''}}">
													<input type="hidden" name="old_vimage_name" id="old_vimage_name" value="{{isset($photos['VS'])?$photos['VS']:''}}">
													<input type="hidden" name="rem_vimage_name" id="rem_vimage_name">
												</div>
											</div>
											<hr/>
											<?php } else { ?>
                                                     <input type="hidden" name="v_id" id="v_id">
													 <input type="hidden" name="v_issue_date" id="v_issue_date">
													 <input type="hidden" name="v_expiry_date" id="v_expiry_date">
													<input type="hidden" name="vimage_name" id="vimage_name">
								                <?php } ?>
											
											<?php if($formdata['labour_card']==1) { ?>
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Labour Card ID</label>
												<div class="col-sm-10">
													 <input type="text" class="form-control" id="lc_id" name="lc_id" value="{{$row->lc_id}}" placeholder="Labour Card ID">
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Issue Date</label>
												<div class="col-sm-10">
													 <input type="text" class="form-control" id="lc_issue_date" name="lc_issue_date" value="<?php echo ($row->lc_issue_date!='0000-00-00')?date('d-m-Y',strtotime($row->lc_issue_date)):''?>" data-language='en' readonly>
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Expiry Date</label>
												<div class="col-sm-10">
													 <input type="text" class="form-control" id="lc_expiry_date" name="lc_expiry_date" data-language='en' value="<?php echo ($row->lc_expiry_date!='0000-00-00')?date('d-m-Y',strtotime($row->lc_expiry_date)):''?>" readonly>
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Labour Card Image</label>
												<div class="col-sm-10">
												<?php if(isset($photos['LB'])) { $arrp = explode(',',$photos['LB']); $i=1; ?>
												@foreach($arrp as $prow)
													<div class="file-preview">
														<div class="close fileinput-remove removeL" data-val="{{$prow}}">×</div>
														<div class="file-drop-disabled">
														<div class="file-preview-thumbnails">
															<div class="file-live-thumbs">
																<a href="{{asset('uploads/labour/'.$prow)}}" target="_blank">View Labour Card Image {{$i}}</a>
															</div>
														</div>
														<div class="clearfix"></div> <div class="file-preview-status text-center text-success"></div>
														<div class="kv-fileinput-error file-error-message" style="display: none;"></div>
														</div>
													</div>
												<?php $i++; ?>
												@endforeach
												<?php } ?>
													<input type="file" id="input-23l" name="limage" class="file-loading" data-show-preview="true" data-url="{{url('employee/lupload/')}}" multiple="">
													<div id="files_list"></div>
													<p id="lloading"></p>
													<input type="hidden" name="limage_name" id="limage_name" value="{{isset($photos['LB'])?$photos['LB']:''}}">
													<input type="hidden" name="old_limage_name" id="old_limage_name" value="{{isset($photos['LB'])?$photos['LB']:''}}">
													<input type="hidden" name="rem_limage_name" id="rem_limage_name">
												</div>
											</div>
											<hr/>
											<?php } else { ?>
                                                     <input type="hidden" name="lc_id" id="lc_id">
													 <input type="hidden" name="lc_issue_date" id="lc_issue_date">
													 <input type="hidden" name="lc_expiry_date" id="lc_expiry_date">
													<input type="hidden" name="limage_name" id="limage_name">
								                <?php } ?>
											
											<?php if($formdata['health_card']==1) { ?>
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Health Card ID</label>
												<div class="col-sm-10">
													 <input type="text" class="form-control" id="hc_id" name="hc_id" placeholder="Health Card ID" value="{{$row->hc_id}}">
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Issue Date</label>
												<div class="col-sm-10">
													 <input type="text" class="form-control" id="hc_issue_date" name="hc_issue_date" data-language='en' value="<?php echo ($row->hc_issue_date!='0000-00-00')?date('d-m-Y',strtotime($row->hc_issue_date)):''?>" readonly>
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Expiry Date</label>
												<div class="col-sm-10">
													 <input type="text" class="form-control" id="hc_expiry_date" name="hc_expiry_date" data-language='en' value="<?php echo ($row->hc_expiry_date!='0000-00-00')?date('d-m-Y',strtotime($row->hc_expiry_date)):''?>" readonly>
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Health Card Info </label>
												<div class="col-sm-10">
													 <input type="text" class="form-control" id="hc_info" name="hc_info" value="{{$row->hc_info}}" placeholder="Health Card Info">
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Health Card Image</label>
												<div class="col-sm-10">
												<?php if(isset($photos['HL'])) { $arrp = explode(',',$photos['HL']); $i=1; ?>
												@foreach($arrp as $prow)
													<div class="file-preview">
														<div class="close fileinput-remove removeH" data-val="{{$prow}}">×</div>
														<div class="file-drop-disabled">
														<div class="file-preview-thumbnails">
															<div class="file-live-thumbs">
																<a href="{{asset('uploads/health/'.$prow)}}" target="_blank">View Health Card  Image {{$i}}</a>
															</div>
														</div>
														<div class="clearfix"></div>    <div class="file-preview-status text-center text-success"></div>
														<div class="kv-fileinput-error file-error-message" style="display: none;"></div>
														</div>
													</div>
												<?php $i++; ?>
												@endforeach
												<?php } ?>
													<input type="file" id="input-23h" name="himage" class="file-loading" data-show-preview="true" data-url="{{url('employee/hupload/')}}" multiple="">
													<div id="files_list"></div>
													<p id="hloading"></p>
													<input type="hidden" name="himage_name" id="himage_name" value="{{isset($photos['HL'])?$photos['HL']:''}}">
													<input type="hidden" name="old_himage_name" id="old_himage_name" value="{{isset($photos['HL'])?$photos['HL']:''}}">
													<input type="hidden" name="rem_himage_name" id="rem_himage_name">
												</div>
											</div>
											   <hr/>
											<?php } else { ?>
                                                     <input type="hidden" name="hc_id" id="hc_id">
													 <input type="hidden" name="hc_issue_date" id="hc_issue_date">
													 <input type="hidden" name="hc_expiry_date" id="hc_expiry_date">
													 <input type="hidden" name="hc_info" id="hc_info">
													<input type="hidden" name="himage_name" id="himage_name">
								                <?php } ?>
											
											<?php if($formdata['id_card']==1) { ?>
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">ID Card ID</label>
												<div class="col-sm-10">
													 <input type="text" class="form-control" id="ic_id" name="ic_id" value="{{$row->ic_id}}" placeholder="ID Card ID">
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Issue Date</label>
												<div class="col-sm-10">
													 <input type="text" class="form-control" id="ic_issue_date" name="ic_issue_date" data-language='en' value="<?php echo ($row->ic_issue_date!='0000-00-00')?date('d-m-Y',strtotime($row->ic_issue_date)):''?>" readonly>
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Expiry Date</label>
												<div class="col-sm-10">
													 <input type="text" class="form-control" id="ic_expiry_date" name="ic_expiry_date" data-language='en' value="<?php echo ($row->ic_expiry_date!='0000-00-00')?date('d-m-Y',strtotime($row->ic_expiry_date)):''?>" readonly>
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">ID Card Image</label>
												<div class="col-sm-10">
												<?php if(isset($photos['IDC'])) { $arrp = explode(',',$photos['IDC']); $i=1; ?>
												@foreach($arrp as $prow)
													<div class="file-preview">
														<div class="close fileinput-remove removeI" data-val="{{$prow}}">×</div>
														<div class="file-drop-disabled">
														<div class="file-preview-thumbnails">
															<div class="file-live-thumbs">
																<a href="{{asset('uploads/idcard/'.$prow)}}" target="_blank">View ID Card Image {{$i}}</a>
															</div>
														</div>
														<div class="clearfix"></div> <div class="file-preview-status text-center text-success"></div>
														<div class="kv-fileinput-error file-error-message" style="display: none;"></div>
														</div>
													</div>
													<?php $i++; ?>
												@endforeach
												<?php } ?>
													<input type="file" id="input-23i" name="iimage" class="file-loading" data-show-preview="true" data-url="{{url('employee/iupload/')}}" multiple="">
													<div id="files_list"></div>
													<p id="iloading"></p>
													<input type="hidden" name="iimage_name" id="iimage_name" value="{{isset($photos['IDC'])?$photos['IDC']:''}}">
													<input type="hidden" name="old_iimage_name" id="old_iimage_name" value="{{isset($photos['IDC'])?$photos['IDC']:''}}">
													<input type="hidden" name="rem_iimage_name" id="rem_iimage_name">
												</div>
											</div>
											<hr/>
											<?php } else { ?>
                                                     <input type="hidden" name="ic_id" id="ic_id">
													 <input type="hidden" name="ic_issue_date" id="ic_issue_date">
													 <input type="hidden" name="ic_expiry_date" id="ic_expiry_date">
													<input type="hidden" name="iimage_name" id="iimage_name">
								                <?php } ?>
											
											<?php if($formdata['medical_exam']==1) { ?>
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Medical Exam ID</label>
												<div class="col-sm-10">
													 <input type="text" class="form-control" id="me_id" name="me_id" value="{{$row->me_id}}" placeholder="Exam ID">
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Issue Date</label>
												<div class="col-sm-10">
													 <input type="text" class="form-control" id="me_issue_date" name="me_issue_date" data-language='en' value="<?php echo ($row->me_issue_date!='0000-00-00')?date('d-m-Y',strtotime($row->me_issue_date)):''?>" readonly>
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Expiry Date</label>
												<div class="col-sm-10">
													 <input type="text" class="form-control" id="me_expiry_date" name="me_expiry_date" data-language='en' value="<?php echo ($row->me_expiry_date!='0000-00-00')?date('d-m-Y',strtotime($row->me_expiry_date)):''?>" readonly>
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Image</label>
												<div class="col-sm-10">
												<?php if(isset($photos['MD'])) { $arrp = explode(',',$photos['MD']); $i=1; ?>
												@foreach($arrp as $prow)
													<div class="file-preview">
														<div class="close fileinput-remove removeM" data-val="{{$prow}}">×</div>
														<div class="file-drop-disabled">
														<div class="file-preview-thumbnails">
															<div class="file-live-thumbs">
																<a href="{{asset('uploads/medical/'.$prow)}}" target="_blank">View Medical Exam Image {{$i}}</a>
															</div>
														</div>
														<div class="clearfix"></div>    <div class="file-preview-status text-center text-success"></div>
														<div class="kv-fileinput-error file-error-message" style="display: none;"></div>
														</div>
													</div>
													<?php $i++; ?>
												@endforeach
												<?php } ?>
													<input type="file" id="input-23me" name="meimage" class="file-loading" data-show-preview="true" data-url="{{url('employee/meupload/')}}" multiple="">
													<div id="files_list"></div>
													<p id="meloading"></p>
													<input type="hidden" name="meimage_name" id="meimage_name" value="{{isset($photos['MD'])?$photos['MD']:''}}">
													<input type="hidden" name="old_meimage_name" id="old_meimage_name" value="{{isset($photos['MD'])?$photos['MD']:''}}">
													<input type="hidden" name="rem_meimage_name" id="rem_meimage_name">
												</div>
											</div>
											<?php } else { ?>
                                                     <input type="hidden" name="me_id" id="me_id">
													 <input type="hidden" name="me_issue_date" id="me_issue_date">
													 <input type="hidden" name="me_expiry_date" id="me_expiry_date">
													<input type="hidden" name="meimage_name" id="meimage_name">
								                <?php } ?>
                                        </div>
                                        <div class="tab-pane" id="tab3">
											<input type="hidden" id="frm" name="frm" value="1" />
                                            <div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Contract Status</label>
												<div class="col-sm-10">
													<label class="radio-inline iradio">
														 <input type="radio" id="inlineradio2" name="contract_status" value="1" <?php if($row->contract_status==1) echo 'checked';?>> Limited 
													</label>
													<label class="radio-inline iradio">
														<input type="radio" id="inlineradio3" checked name="contract_status" <?php if($row->contract_status==2) echo 'checked';?> value="2">
														Unlimited
													</label>
												</div>
											</div>
											
											<!--<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Contract Salary</label>
												<div class="col-sm-6">
													 <input type="number" step="any" class="form-control" id="contract_salary" name="contract_salary" value="{{$row->contract_salary}}" placeholder="Contract Salary">
												</div>
											</div>-->
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Basic Pay</label>
												<div class="col-sm-6">
													 <input type="number" step="any" class="form-control salcal" id="basic_pay" name="basic_pay" value="{{$row->basic_pay}}" placeholder="Basic Pay"> 
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
													 <input type="number" step="any" class="form-control salcal" id="hra" name="hra" value="{{$row->hra}}" placeholder="HRA">
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
													 <input type="number" step="any" class="form-control salcal" id="transport" name="transport" value="{{$row->transport}}" placeholder="Transport">
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
													 <input type="number" step="any" class="form-control salcal" id="allowance" name="allowance" value="{{$row->allowance}}" placeholder="Allowance1">
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
													 <input type="number" step="any" class="form-control salcal" id="allowance2" name="allowance2" value="{{$row->allowance2}}" placeholder="Allowance2">
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
													 <input type="number" step="any" class="form-control" id="net_salary" name="net_salary" value="{{$row->net_salary}}" >
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Normal Workign Hr.</label>
												<div class="col-sm-6">
													 <input type="number" step="any" class="form-control" id="nwh" name="nwh" value="{{$row->nwh}}">
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Normal Wage by</label>
												<div class="col-sm-10">
													<label class="radio-inline iradio">
														 <input type="radio" id="inlineradio3" name="nwc" <?php if($row->nwage==30) echo 'checked'; ?> value="30"> 30 Days 
													</label>
													<label class="radio-inline iradio">
														<input type="radio" id="inlineradio4" name="nwc" value="365" <?php if($row->nwage==365) echo 'checked'; ?>>
														365 Days
													</label>
													<label class="radio-inline iradio">
														<input type="radio" id="inlineradio5" name="nwc" value="monthly" <?php if($row->nwage=='monthly') echo 'checked'; ?>>
														Monthly
													</label>
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">OT Wage by</label>
												<div class="col-sm-10">
													<label class="radio-inline iradio">
														 <input type="radio" id="inlineradio3" name="otwc" <?php if($row->otwage==30) echo 'checked'; ?> value="30"> 30 Days 
													</label>
													<label class="radio-inline iradio">
														<input type="radio" id="inlineradio4" name="otwc" <?php if($row->otwage==365) echo 'checked'; ?> value="365">
														365 Days
													</label>
													<label class="radio-inline iradio">
														<input type="radio" id="inlineradio5" name="otwc" <?php if($row->otwage=='monthly') echo 'checked'; ?> value="monthly">
														Monthly
													</label>
												</div>
											</div>
											
											<hr/>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Leave/Month for AL</label>
												<div class="col-sm-2">
													 <input type="number" step="any" class="form-control" id="lpm" name="lev_per_mth" value="{{$row->lev_per_mth}}">
												</div>
												<label for="input-text" class="col-sm-3 control-label">Air Ticket Allotment after</label>
												<div class="col-sm-2">
													 <input type="number" step="any" class="form-control" id="air_tkt" name="air_tkt" value="{{$row->air_tkt}}">
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Alloted Anual ML</label>
												<div class="col-sm-2">
													 <input type="number" step="any" class="form-control" id="anual_ml" name="anual_ml" value="{{$row->anual_ml}}">
												</div>
												<label for="input-text" class="col-sm-3 control-label">Alloted Anual CL</label>
												<div class="col-sm-2">
													 <input type="number" step="any" class="form-control" id="anual_cl" name="anual_cl" value="{{$row->anual_cl}}">
												</div>
											</div>
											
                                        </div>
										
										<div class="tab-pane" id="tab4">
										 <h2 class="hidden">&nbsp;</h2>
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Remarks</label>
												<div class="col-sm-10">
													 <input type="text" class="form-control" id="remarks" name="remarks" value="{{$row->remarks}}" placeholder="Remarks">
												</div>
											</div>
											
											<!--<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Duty Status</label>
												<div class="col-sm-10">
													 <input type="text" class="form-control" id="duty_status" name="duty_status" placeholder="Duty Status">
												</div>
											</div>-->
											<input type="hidden" class="form-control" id="duty_status" name="duty_status" value="{{$row->duty_status}}">
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Other Info</label>
												<div class="col-sm-10">
													 <input type="text" class="form-control" id="other_info" name="other_info" value="{{$row->other_info}}" placeholder="Other Info">
												</div>
											</div>
                                               
											   <div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Other File</label>
												<div class="col-sm-10">
												<?php if(isset($photos['OF'])) { $arrp = explode(',',$photos['OF']); $i=1; ?>
												@foreach($arrp as $prow)
													<div class="file-preview">
														<div class="close fileinput-remove removeOF" data-val="{{$prow}}">×</div>
														<div class="file-drop-disabled">
														<div class="file-preview-thumbnails">
															<div class="file-live-thumbs">
																<a href="{{asset('uploads/medical/'.$prow)}}" target="_blank">View Other Files {{$i}}</a>
															</div>
														</div>
														
														<div class="clearfix"></div> <div class="file-preview-status text-center text-success"></div>
														<div class="kv-fileinput-error file-error-message" style="display: none;"></div>
														</div>
													</div>
												<?php $i++; ?>
												@endforeach
												<?php } ?>
													<input type="file" id="input-23f" name="fimage" class="file-loading" data-show-preview="true" data-url="{{url('employee/fupload/')}}" multiple="">
													<div id="files_list"></div>
													<p id="floading"></p>
													<input type="hidden" name="fimage_name" id="fimage_name" value="{{isset($photos['OF'])?$photos['OF']:''}}">
													<input type="hidden" name="old_fimage_name" id="fld_fimage_name" value="{{isset($photos['OF'])?$photos['OF']:''}}">
													<input type="hidden" name="rem_fimage_name" id="rem_fimage_name">
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
                                          <li class="next finish" id="finished">
										<button type="submit" class="btn btn-primary" id="prm">Submit</button>
                                                
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
"use strict";
//var urlcode = "{{ url('employee/') }}"; //checkcode
var saveurl = "{{ url('employee/save') }}";
var tkn = "{{csrf_token()}}";
var returl = "{{ url('employee') }}";
$('#dob').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy' } );
$(document).ready(function () {	
		
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
			autoclose: true
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
			autoclose: 1
	});
	
	$('#me_expiry_date').datepicker( { 
			dateFormat: 'dd-mm-yyyy',
			autoclose: 1
	});
	
	$(document).on('blur', '.salcal', function(e) {
		var net_sal = 0;
		$( '.salcal' ).each(function() {
			net_sal = net_sal + parseFloat((this.value=='') ? 0 : this.value);
		});
		
		$('#net_salary').val(net_sal);
	});
	
});	


	




   $(document).ready(function (){
		
		$(document).on('click', '.removeP', function(e) {  
			var con = confirm('Are you sure to remove image?');
			if(con) {
				var fnames = $('#photo_name').val().replace($(this).attr("data-val"),'');
				$('#photo_name').val(fnames);
				
				var rp = $('#rem_photo_name').val();
				$('#rem_photo_name').val( (rp=='')?$(this).attr("data-val"):rp+','+$(this).attr("data-val") );
				
				$(this).parents('.file-preview').remove();
			}
		});
		
		$(document).on('click', '.removePP', function(e) {  
			var con = confirm('Are you sure to remove image?');
			if(con) {
				
				var fnames = $('#pimage_name').val().replace($(this).attr("data-val"),'');
				$('#pimage_name').val(fnames);
				
				var rp = $('#rem_pimage_name').val();
				$('#rem_pimage_name').val( (rp=='')?$(this).attr("data-val"):rp+','+$(this).attr("data-val") );
				
				$(this).parents('.file-preview').remove();
			}
		});
		
		$(document).on('click', '.removeV', function(e) {  
			var con = confirm('Are you sure to remove image?');
			if(con) {
				var fnames = $('#vimage_name').val().replace($(this).attr("data-val"),'');
				$('#vimage_name').val(fnames);
				
				var rp = $('#rem_vimage_name').val();
				$('#rem_vimage_name').val( (rp=='')?$(this).attr("data-val"):rp+','+$(this).attr("data-val") );
				
				$(this).parents('.file-preview').remove();
			}
		});
		
		$(document).on('click', '.removeL', function(e) {  
			var con = confirm('Are you sure to remove image?');
			if(con) {
				var fnames = $('#limage_name').val().replace($(this).attr("data-val"),'');
				$('#limage_name').val(fnames);
				
				var rp = $('#rem_limage_name').val();
				$('#rem_limage_name').val( (rp=='')?$(this).attr("data-val"):rp+','+$(this).attr("data-val") );
				
				$(this).parents('.file-preview').remove();
			}
		});
		
		$(document).on('click', '.removeH', function(e) {  
			var con = confirm('Are you sure to remove image?');
			if(con) {
				var fnames = $('#himage_name').val().replace($(this).attr("data-val"),'');
				$('#himage_name').val(fnames);
				
				var rp = $('#rem_himage_name').val();
				$('#rem_himage_name').val( (rp=='')?$(this).attr("data-val"):rp+','+$(this).attr("data-val") );
				
				$(this).parents('.file-preview').remove();
			}
		});
		
		$(document).on('click', '.removeI', function(e) {  
			var con = confirm('Are you sure to remove image?');
			if(con) {
				var fnames = $('#iimage_name').val().replace($(this).attr("data-val"),'');
				$('#iimage_name').val(fnames);
				
				var rp = $('#rem_iimage_name').val();
				$('#rem_iimage_name').val( (rp=='')?$(this).attr("data-val"):rp+','+$(this).attr("data-val") );
				
				$(this).parents('.file-preview').remove();
			}
		});
		$(document).on('click', '.removeOF', function(e) {  
			var con = confirm('Are you sure to remove image?');
			if(con) {
				
				var fnames = $('#fimage_name').val().replace($(this).attr("data-val"),'');
				$('#fimage_name').val(fnames);
				
				var rp = $('#rem_fimage_name').val();
				$('#rem_fimage_name').val( (rp=='')?$(this).attr("data-val"):rp+','+$(this).attr("data-val") );
				
				$(this).parents('.file-preview').remove();
			}
		});
		
		$(document).on('click', '.removeM', function(e) {  
			var con = confirm('Are you sure to remove image?');
			if(con) {
				var fnames = $('#meimage_name').val().replace($(this).attr("data-val"),'');
				$('#meimage_name').val(fnames);
				
				var rp = $('#rem_meimage_name').val();
				$('#rem_meimage_name').val( (rp=='')?$(this).attr("data-val"):rp+','+$(this).attr("data-val") );
				
				$(this).parents('.file-preview').remove();
			}
		});
		
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
				var pn = $('#pimage_name').val();
				$('#pimage_name').val( (pn=='')?data.result.file_name:pn+','+data.result.file_name );
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
				var pn = $('#vimage_name').val();
				$('#vimage_name').val( (pn=='')?data.result.file_name:pn+','+data.result.file_name );
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
				var pn = $('#limage_name').val();
				$('#limage_name').val( (pn=='')?data.result.file_name:pn+','+data.result.file_name );
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
				var pn = $('#himage_name').val();
				$('#himage_name').val( (pn=='')?data.result.file_name:pn+','+data.result.file_name );
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
				var pn = $('#iimage_name').val();
				$('#iimage_name').val( (pn=='')?data.result.file_name:pn+','+data.result.file_name );
                $('#iloading').text('Completed.');
            }
        });
		$('#input-23f').fileupload({
            dataType: 'json',
            add: function (e, data) {
                $('#floading').text('Uploading...');
                data.submit();
            },
            done: function (e, data) {
				var pn = $('#fimage_name').val();
				$('#fimage_name').val( (pn=='')?data.result.file_name:pn+','+data.result.file_name );
                $('#floading').text('Completed.');
            }
        });
		
		$('#input-23me').fileupload({
            dataType: 'json',
            add: function (e, data) {
                $('#meloading').text('Uploading...');
                data.submit();
            },
            done: function (e, data) {
				var pn = $('#meimage_name').val();
				$('#meimage_name').val( (pn=='')?data.result.file_name:pn+','+data.result.file_name );
                $('#meloading').text('Completed.');
            }
        });
	
</script>

@stop
