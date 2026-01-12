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
    <link rel="stylesheet" href="{{asset('assets/vendors/datetime/css/jquery.datetimepicker.css')}}">
    <link href="{{asset('assets/vendors/airdatepicker/css/datepicker.min.css')}}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/buttons.bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/colReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/dataTables.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/rowReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/scroller.bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatablesmark.js/css/datatables.mark.min.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/responsive_datatables.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/datepicker.css')}}">
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <!--section starts-->
            <h1>
                Job Master
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="">
                        <i class="fa fa-fw fa-wrench"></i> Maintenance
                    </a>
                </li>
                <li>
                    <a href="#">Job Master</a>
                </li>
                <li class="active">
                    Add New
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
                                <i class="fa fa-fw fa-crosshairs"></i> New Job 
                            </h3>
                           
                        </div>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" method="POST" name="frmJobmaster" id="frmJobmaster" action="{{ url('jobmaster/save') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                  <?php if($formdata['transport_type']==1) { ?>
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Job Type</label>
                                    <div class="col-sm-10">
                                        <select id="transport_type" class="form-control select2" style="width:100%; background-color:#85d3ef;" name="transport_type">
                                            <option value="">Select Job Type...</option>
                                            @foreach($jobtype as $job)
                                            <option value="{{ $job->id}}">{{ $job->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>


                                <?php } else { ?>
                                <input type="hidden" name="transport_type" id="transport_type">
                                <?php } ?>

                                <?php if($formdata['code']==1) { ?>
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label <?php if($errors->has('voucher_no')) echo 'form-error';?>">Job Code</label>
                                    <div class="col-sm-10">
                                        <?php if($voucherno->prefix!='') { ?>
                                        
                                            <input type="text" class="form-control" id="code" name="code">
                                       
                                        <?php } else { ?>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="code" name="code" <?php if($voucherno->autoincrement==1) { ?> value="{{$voucherno->no}}" <?php } else { ?> value="{{old('voucher_no')}}" <?php } ?>>
                                            <input type="hidden" value="{{$voucherno->prefix}}" name="prefix">
                                            <input type="hidden"  name="voucher_no" id="voucher_no">
                                            <input type="hidden" value="{{$voucherno->voucher_type}}" name="voucher_type">
                                            <input type="hidden" value="{{$voucherno->autoincrement}}" name="autoincrement">
                                            <span class="input-group-addon inputvn"><i class="fa fa-fw fa-edit"></i></span>
                                        </div>
                                        <?php } ?>
                                    </div>
                                    <input type="hidden" name="curno" id="curno" value="{{(old('curno'))?old('curno'):$voucherno->no}}">
                                </div>
                                <?php } else { ?>
                                <input type="hidden" name="code" id="code">
                                <?php } ?>

                               
                                <?php if($formdata['jobname']==1) { ?>
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Job Name</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="jobname" name="jobname" placeholder="Job Name">
                                    </div>
                                </div>
                                <?php } else { ?>
                                <input type="hidden" name="jobname" id="jobname">
                                <?php } ?>

                                 <?php if($formdata['customer_name']==1) { ?>
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Customer</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="customer_name" id="customer_name" class="form-control" autocomplete="off" data-toggle="modal" data-target="#customer_modal" placeholder="Customer">
                                        <input type="hidden" name="customer_id" id="customer_id">
                                    </div>
                                </div>
                                <?php } else { ?>
                                <input type="hidden" name="customer_name" id="customer_name">
                                <?php } ?>


                                
                                <div class="form-group">
                                <?php if($formdata['date']==1) { ?>
                                    <label for="input-text" class="col-sm-2 control-label">Date</label>
                                    <div class="col-sm-5">
                                    <input type="text" class="form-control pull-right" autocomplete="öff"  id="date" name="date"  data-language='en' value="{{date('d-m-Y')}}" />
                                  
                                    </div>
                                    <?php } else { ?>
                                <input type="hidden" name="date" id="date">
                                <?php } ?>
                                <?php if($formdata['address']==1) { ?>
                                    <label for="input-text" class="col-sm-2 control-label">Address</label>
                                    <div class="col-sm-5">
                                        <input type="text" name="address" id="address" class="form-control" autocomplete="off"  placeholder="Address">
                                    </div>
                                <?php } else { ?>
                                <input type="hidden" name="address" id="address">
                                <?php } ?>
                                </div>
                                


                                
                                <div class="form-group">
                                <?php if($formdata['mbl']==1) { ?>
                                    <label for="input-text" class="col-sm-2 control-label">MBL/MAWB</label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control" step="any" id="mbl" name="mbl" placeholder="MBL/MAWB">
                                    </div>
                                <?php } else { ?>
                                 <input type="hidden" name="mbl" id="mbl">
                                 <?php } ?>
                                  <?php if($formdata['hbl']==1) { ?>
                                    <label for="input-text" class="col-sm-1 control-label">HBL/MAWB</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="hbl" id="hbl" class="form-control" autocomplete="off"  placeholder="HBL/MAWB">
                                    </div>
                                <?php } else { ?>
                                 <input type="hidden" name="hbl" id="hbl">
                                 <?php } ?>
                                
                                </div>
                                

                               
                                <div class="form-group">
                                <?php if($formdata['origin']==1) { ?>
                                    <label for="input-text" class="col-sm-2 control-label">Load Port</label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control" step="any" id="origin" name="origin" placeholder="Load Port">
                                    </div>
                                <?php } else { ?>
                                 <input type="hidden" name="origin" id="origin">
                                 <?php } ?>
                                 
                                  <?php if($formdata['destination']==1) { ?>
                                     <label for="input-text" class="col-sm-1 control-label">Discharge Port</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="destination" id="destination" class="form-control" autocomplete="off"  placeholder="Discharge Port">
                                        
                                    </div>
                                <?php } else { ?>
                                 <input type="hidden" name="destination" id="destination">
                                 <?php } ?>
                               
                                </div>
                                

                                <div class="form-group">
                                <?php if($formdata['por']==1) { ?>
                                    <label for="input-text" class="col-sm-2 control-label">POR</label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control" step="any" id="por" name="por" placeholder="Palce Of Receipt">
                                    </div>
                                <?php } else { ?>
                                 <input type="hidden" name="por" id="por">
                                 <?php } ?>
                                <?php if($formdata['fnd']==1) { ?>
                                    <label for="input-text" class="col-sm-1 control-label">POD</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="fnd" id="fnd" class="form-control" autocomplete="off"  placeholder="Place Of Delivery">
                                    </div>
                                <?php } else { ?>
                                 <input type="hidden" name="fnd" id="fnd">
                                 <?php } ?>
                                </div>
                                
                                <div class="form-group">
                                <?php if($formdata['no_of_pieces']==1) { ?>
                                    <label for="input-text" class="col-sm-2 control-label">No Of Pieces</label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control" step="any" id="no_of_pieces" name="no_of_pieces" placeholder="No Of Pieces">
                                    </div>
                                <?php } else { ?>
                                 <input type="hidden" name="no_of_pieces" id="no_of_pieces">
                                 <?php } ?>
                                  <?php if($formdata['gross_weight']==1) { ?>
                                    <label for="input-text" class="col-sm-1 control-label">Gross Weight</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" step="any" id="gross_weight" name="gross_weight" placeholder="Gross Weight">
                                    </div>
                                <?php } else { ?>
                                 <input type="hidden" name="gross_weight" id="gross_weight">
                                 <?php } ?>
                                
                                </div>
                                
                                <div class="form-group">
                               <?php if($formdata['volume']==1) { ?>
                                    <label for="input-text" class="col-sm-2 control-label">Volume</label>
                                    <div class="col-sm-5">
                                        <input type="text" name="volume" id="volume" class="form-control" autocomplete="off"  placeholder="Volume">
                                    </div>
                                <?php } else { ?>
                                 <input type="hidden" name="volume" id="volume">
                                 <?php } ?>
                               <?php if($formdata['house_bl_no']==1) { ?>
                                    <label for="input-text" class="col-sm-1 control-label">House BL No</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="house_bl_no" id="house_bl_no" class="form-control" autocomplete="off"  placeholder="House BL No">
                                    </div>
                                <?php } else { ?>
                                 <input type="hidden" name="house_bl_no" id="house_bl_no">
                                 <?php } ?>
                                </div>
                                
                                <div class="form-group">
                                <?php if($formdata['flight_no']==1) { ?>
                                    <label for="input-text" class="col-sm-2 control-label">Flight No/Vessel/Voyage</label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control" step="any" id="flight_no" name="flight_no" placeholder="Flight No/Vessel/Voyaget">
                                    </div>
                                <?php } else { ?>
                                 <input type="hidden" name="flight_no" id="flight_no">
                                 <?php } ?>
                                <?php if($formdata['chargeable_weight']==1) { ?>
                                    <label for="input-text" class="col-sm-1 control-label">Chargeable Weight</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="chargeable_weight" id="chargeable_weight" class="form-control" autocomplete="off" placeholder="Chargeable Weight">
                                    </div>
                                <?php } else { ?>
                                 <input type="hidden" name="chargeable_weight" id="chargeable_weight">
                                 <?php } ?>
                                </div>
                           
                               
                                <div class="form-group">
                                <?php if($formdata['be_no']==1) { ?>
                                    <label for="input-text" class="col-sm-2 control-label">B/E NO.</label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control" step="any" id="be_no" name="be_no" placeholder="B/E NO.">
                                    </div>
                                <?php } else { ?>
                                 <input type="hidden" name="be_no" id="be_no">
                                 <?php } ?>
                                  <?php if($formdata['container_no']==1) { ?>
                                    <label for="input-text" class="col-sm-1 control-label">Container No.</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="container_no" id="container_no" class="form-control" autocomplete="off" placeholder="Container No.">
                                    </div>
                                 <?php } else { ?>
                                 <input type="hidden" name="container_no" id="container_no">
                                 <?php } ?>
                               
                                </div>
                            


                                
                                <div class="form-group">
                                 <?php if($formdata['packing']==1) { ?>
                                    <label for="input-text" class="col-sm-2 control-label">Packing</label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control" id="packing" name="packing" placeholder="Packing">
                                    </div>
                                 <?php } else { ?>
                                 <input type="hidden" name="packing" id="packing">
                                 <?php } ?>
                                 <?php if($formdata['flight_date']==1) { ?>
                                    <label for="input-text" class="col-sm-1 control-label">Flight Date</label>
                                    <div class="col-sm-4">
                                        <input type="date" name="flight_date" id="flight_date" class="form-control" autocomplete="off" placeholder="Flight Date">
                                    </div>
                                <?php } else { ?>
                                 <input type="hidden" name="flight_date" id="flight_date">
                                 <?php } ?>
                                </div>
                                <div class="form-group">
                                <?php if($formdata['shipper']==1) { ?>
                                    <label for="input-text" class="col-sm-2 control-label">Shipper</label>
                                    <div class="col-sm-5">
                                        <input type="text" name="shipper" id="shipper" class="form-control" autocomplete="off" placeholder="Shipper">
                                    </div>
                                 <?php } else { ?>
                                 <input type="hidden" name="shipper" id="shipper">
                                 <?php } ?>
                                
                               <?php if($formdata['consignee']==1) { ?>
                                    <label for="input-text" class="col-sm-1 control-label">Consignee</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="consignee" id="consignee" class="form-control" autocomplete="off" placeholder="Consignee">
                                    </div>
                                 <?php } else { ?>
                                 <input type="hidden" name="consignee" id="consignee">
                                 <?php } ?>
                                </div>
                                <!--<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Workshop Job</label>
                                    <div class="col-sm-10">
                                        <label class="radio-inline iradio">
                                            <input type="checkbox" class="workshop_icheck" id="is_workshop" name="is_workshop" value="1">
                                    </div>
                                </div>
                                
                                <div id="workshop">
                                    <div class="form-group">
                                        <label for="input-text" class="col-sm-2 control-label">Vehicle Name</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="vehicle_name" name="vehicle_name" autocomplete="off" >
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="input-text" class="col-sm-2 control-label">Reg. No.</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="reg_no" name="reg_no" autocomplete="off">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="input-text" class="col-sm-2 control-label">Make</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="make" name="make" autocomplete="off">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="input-text" class="col-sm-2 control-label">Color</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="color" name="color" autocomplete="off">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="input-text" class="col-sm-2 control-label">Engine No.</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="engine_no" name="engine_no" autocomplete="off">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="input-text" class="col-sm-2 control-label">Chasis No.</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="chasis_no" name="chasis_no" autocomplete="off">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="input-text" class="col-sm-2 control-label">Owner</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="owner" name="owner" autocomplete="off">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="input-text" class="col-sm-2 control-label">Km. Done</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="km_done" name="km_done" autocomplete="off">
                                        </div>
                                    </div>
                                    
                                </div>-->
                                <?php if($formdata['open_cost']==1) { ?>
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Open Cost</label>
                                    <div class="col-sm-10">
                                        <input type="number" class="form-control" step="any" id="open_cost" name="open_cost" placeholder="Open Cost">
                                    </div>
                                </div>
                                <?php } else { ?>
                                <input type="hidden" name="open_cost" id="open_cost">
                                <?php } ?>


                                
                                
                                <!--<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Vehicle</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="vehicle_name" id="vehicle_name" class="form-control" autocomplete="off" data-toggle="modal" data-target="#vehicle_modal" placeholder="Vehicle">
                                        <input type="hidden" name="vehicle_id" id="vehicle_id">
                                    </div>
                                </div>-->
                                <?php if($formdata['department_id']==1) { ?>
                                <input type="hidden" name="vehicle_id" id="vehicle_id">
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Department</label>
                                    <div class="col-sm-10">
                                        <select id="department_id" class="form-control select2" style="width:100%" name="department_id">
                                            <option value="">Select Department...</option>
                                            @foreach ($department as $dep)
                                            <option value="{{ $dep['id'] }}">{{ $dep['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <?php } else { ?>
                                <input type="hidden" name="department_id" id="department_id">
                                <?php } ?>

                                
                                <?php if($formdata['salesman']==1) { ?>
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Salesman</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="salesman" id="salesman" class="form-control" autocomplete="off" data-toggle="modal" data-target="#salesman_modal" placeholder="Salesman">
                                        <input type="hidden" name="salesman_id" id="salesman_id">
                                    </div>
                                </div>
                                <?php } else { ?>
                                <input type="hidden" name="salesman_id" id="salesman_id">
                                <?php } ?>

                                
                                <?php if($formdata['open_income']==1) { ?>
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Open Income</label>
                                    <div class="col-sm-10">
                                        <input type="number" class="form-control" step="any" id="open_income" name="open_income" placeholder="Open Income">
                                    </div>
                                </div>
                                <?php } else { ?>
                                <input type="hidden" name="open_income" id="open_income">
                                <?php } ?>

                                
                                <?php if($formdata['contract_amount']==1) { ?>
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Contract Amount</label>
                                    <div class="col-sm-10">
                                        <input type="number" class="form-control" step="any" id="contract_amount" name="contract_amount" placeholder="Contract Amount">
                                    </div>
                                </div>
                                <?php } else { ?>
                                <input type="hidden" name="contract_amount" id="contract_amount">
                                <?php } ?>

                                <?php if($formdata['start_date']==1) { ?>
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Start Date</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control pull-right" name="start_date" data-language='en' readonly id="start_date" placeholder="Start Date"/>
                                    </div>
                                </div>
                                <?php } else { ?>
                                <input type="hidden" name="start_date" id="start_date">
                                <?php } ?>

                                
                                <?php if($formdata['end_date']==1) { ?>
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">End Date</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="end_date" name="end_date" data-language='en' readonly placeholder="End Date">
                                    </div>
                                </div>
                                <?php } else { ?>
                                <input type="hidden" name="end_date" id="end_date">
                                <?php } ?>

                                
                                
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Job Close?</label>
                                    <div class="col-sm-10">
                                        <label class="radio-inline iradio">
                                            <input type="radio" id="inlineradio1" name="job_close" value="0" checked>
                                            No
                                        </label>
                                        <label class="radio-inline iradio">
                                            <input type="radio" id="inlineradio2" name="job_close" value="1">
                                            Yes
                                        </label>
                                    </div>
                                </div>
								
								<?php if($formdata['document_type']==1) { ?>
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Document Type?</label>
                                    <div class="col-sm-10">
                                        <label class="radio-inline iradio">
                                            <input type="radio" id="inlineradio01" name="document_type" value="0" checked>
                                            None
                                        </label>
                                        <label class="radio-inline iradio">
                                            <input type="radio" id="inlineradio02" name="document_type" value="1">
                                            Purchase Split
                                        </label>
                                        <label class="radio-inline iradio">
                                            <input type="radio" id="inlineradio03" name="document_type" value="2">
                                            Sales Split
                                        </label>
                                    </div>
                                </div>
								<?php } else { ?>
								 <input type="hidden" name="document_type" id="document_type">
                                <?php } ?>
                                
                                
                                
                                

                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                        <a href="{{ url('jobmaster') }}" class="btn btn-danger">Cancel</a>
                                        <a href="{{ url('jobmaster/budget') }}" target="_blank" class="btn btn-info">Project Budgeting</a>
                                        <a href="{{ url('jobmaster/viewbudget') }}" target="_blank" class="btn btn-primary">View Project Budgeting</a>
                                    </div>
                                </div>
                                
                              <div id="customer_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Select Customer</h4>
                                        </div>
                                        <div class="modal-body" id="customerData">
                                                        
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>                               
                            
                            <div id="vehicle_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Select Vehicle</h4>
                                        </div> <input type="hidden" id="cust_id">
                                        <div class="modal-body" id="vehicleData">
                                            
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                            
                            <div id="salesman_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Select Salesman</h4>
                                        </div>
                                        <div class="modal-body" id="salesmanData">
                                            
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
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
<script src="{{asset('assets/vendors/moment/js/moment.min.js')}}" type="text/javascript"></script>
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
<script>
"use strict";
$('#date').datepicker( { 
	dateFormat: 'dd-mm-yyyy',
	autoClose: true
});
$(document).ready(function () {
    $('#workshop').toggle();
    $('input[type=number]').on('mousewheel',function(e){ $(this).blur(); });
    var urlcode = "{{ url('jobmaster/checkcode/') }}";
    var urlname = "{{ url('jobmaster/checkname/') }}";
    $('#frmJobmaster').bootstrapValidator({
        fields: {
            code: {
                validators: {
                    notEmpty: {
                        message: 'The job code is required and cannot be empty!'
                    },
                    remote: {
                        url: urlcode,
                        data: function(validator) {
                            return {
                                code: validator.getFieldElements('code').val()
                            };
                        },
                        message: 'The job code is not available'
                    }
                }
            },
            jobname: {
                validators: {
                    notEmpty: {
                        message: 'The job name is required and cannot be empty!'
                    },
                    remote: {
                        url: urlname,
                        data: function(validator) {
                            return {
                                code: validator.getFieldElements('jobname').val()
                            };
                        },
                        message: 'The job name is not available'
                    }
                }
            },
            //open_cost: { validators: { notEmpty: { message: 'The open cost is required and cannot be empty!' } }},
            customer_name: { validators: { notEmpty: { message: 'The customer name is required and cannot be empty!' } }}
            //open_income: { validators: { notEmpty: { message: 'The open income is required and cannot be empty!' } }}
          
        }
        
    }).on('reset', function (event) {
        $('#frmJobmaster').data('bootstrapValidator').resetForm();
    });
    
    $('.workshop_icheck').on('ifChecked', function(event){ 
        $('#workshop').toggle();
    });
    
    $('.workshop_icheck').on('ifUnchecked', function(event){ 
        $('#workshop').toggle();
    });
    
});


$(function() {  
	
	
	$(document).on('change', '#transport_type', function(e) { 
		console.log($(this).val());
		
		$.ajax({
			url: "{{ url('jobtype/getjobno') }}/"+$(this).val(), 
			type: 'get',
			success: function(data) { data = $.parseJSON(data);
				//$('#prefix').val(data.name);
				$('#code').val(data.name+{{date('Y')}}+'-'+data.job_no);
				return true;
			}
		}); 
			
	});
	
	
    var custurl = "{{ url('sales_order/customer_data/') }}";
    
    $('#customer_name').click(function() { 
        $('#customerData').load(custurl, function(result) {
            console.log(result);
            $('#myModal').modal({show:true});
            if( $('#customerInfo').is(":hidden") ) 
                $('#customerInfo').toggle();
        });
    });
    
    $(document).on('click', '.custRow', function(e) { 
        $('#customer_name').val($(this).attr("data-name"));
        $('#customer_id').val($(this).attr("data-id")); $('#frmJobmaster').bootstrapValidator('revalidateField', 'customer_name');
        e.preventDefault();
    });
    
    var vclurl = "{{ url('job_order/vehicle_data/') }}";
    $('#vehicle_name').click(function() { $('#cust_id').val( $('#customer_id').val() );
        $('#vehicleData').load(vclurl+'/'+$('#customer_id').val(), function(result) {
            $('#myModal').modal({show:true});
        });
    });
    
    $(document).on('click', '.vclRow', function(e) {
        $('#vehicle_name').val($(this).attr("data-name"));
        $('#vehicle_id').val($(this).attr("data-id"));
        e.preventDefault();
    });
    
    var supurl = "{{ url('quotation_sales/salesman_data/') }}";
    
    $('#salesman').click(function() {
        $('#salesmanData').load(supurl, function(result) {
            $('#myModal').modal({show:true});
        });
    });
    
    $(document).on('click', '.salesmanRow', function(e) {
        $('#salesman').val($(this).attr("data-name"));
        $('#salesman_id').val($(this).attr("data-id"));
        e.preventDefault();
    });
    
    $('#start_date').datepicker({
        language: 'en',
        dateFormat: 'dd-mm-yyyy',
        autoClose : true
    });
    
    $('#end_date').datepicker({
        language: 'en',
        dateFormat: 'dd-mm-yyyy',
        autoClose : true
    });
});
</script>
@stop
