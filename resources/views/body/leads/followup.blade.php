@extends('layouts/default')

    {{-- Page title --}}
    @section('title')
         
        @parent
    @stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/formelements.css')}}">
	
	<link rel="stylesheet" href="{{asset('assets/vendors/datetime/css/jquery.datetimepicker.css')}}">
    <link href="{{asset('assets/vendors/airdatepicker/css/datepicker.min.css')}}" rel="stylesheet" type="text/css">
	
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/animate/animate.min.css')}}">
	<link href="{{asset('assets/css/timeline.css')}}" rel="stylesheet"/>
    <link href="{{asset('assets/css/timeline2.css')}}" rel="stylesheet"/>
	
        <!--end of page level css-->
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
	<section class="content-header">
            <!--section starts-->
            <h1>
                Leads
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="fa fa-fw fa-money"></i> Leads
                    </a>
                </li>
                
				<li class="active">
                    Edit
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
                <div class="col-md-12 timeline_panel">
                    <div class="panel panel-info">
                        <div class="panel-heading clearfix">
                            <h3 class="panel-title pull-left m-t-6">
                                <i class="fa fa-fw fa-clock-o"></i> Follow Up
                            </h3>
                            <span class="pull-right" style="padding-top:19px;">
                                    <a href="" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#followup_modal">
											<span class="btn-label">
											<i class="glyphicon glyphicon-plus"></i>
										</span> Add New
									</a>
                                </span>
                        </div>
                        <div class="panel-body">
                            <!--timeline-->
                            <div>
							<p><h4>Customer: {{$docrow->master_name}} &nbsp; &nbsp &nbsp; Phone: {{$docrow->phone}}</h4></p>
							<address>Address: {{$docrow->address}}<br/>
							Fax: {{$docrow->fax}}<br/>Email: {{$docrow->email}}</address>
                                <ul class="timeline">
								<?php $i=0;
									 foreach($follos as $row) {
										 $i++;
										 $v = ($i%2);
								?>
                                    <?php if($v==1) { ?>
									<li>
                                        <div class="timeline-badge info wow lightSpeedIn center">
                                            <i class="fa fa-fw fa-indent"></i>
                                        </div>
                                        <div class="timeline-panel wow slideInLeft" style="display:inline-block;">
                                            <div class="timeline-heading">
                                                <h4 class="timeline-title">{{$row->title}}</h4>
                                                <p>
                                                    <small class="text-info">{{date('d-m-Y', strtotime($row->date))}}</small>
                                                </p>
                                            </div>
                                            <div class="timeline-body">
                                                <p>
												{{$row->description}}
                                                </p>
                                            </div>
											<div class="pull-right"><button class="btn btn-primary btn-xs editfl" id="{{$row->id}}" data-toggle="modal" data-target="#editfl_modal">
												<span class="glyphicon glyphicon-pencil"></span></button>
												
												<button class="btn btn-danger btn-xs delete" onClick="funDelete('{{$row->id}}','{{$docrow->id}}')">
												<span class="glyphicon glyphicon-trash"></span>
											</div>
                                        </div>
                                    </li>
									<?php } else { ?>
                                    <li class="timeline-inverted">
                                        <div class="timeline-badge warning wow lightSpeedIn center">
                                            <i class="fa fa-fw fa-indent"></i>
                                        </div>
                                        <div class="timeline-panel wow slideInRight">
                                            <div class="timeline-heading">
                                                <h4 class="timeline-title">{{$row->title}}</h4>
                                                <p>
                                                    <small class="text-warning">{{date('d-m-Y', strtotime($row->date))}}</small>
                                                </p>
                                            </div>
                                            <div class="timeline-body">
                                                <p>{{$row->description}}</p>
                                            </div>
											<div class="pull-right"><button class="btn btn-primary btn-xs editfl" id="{{$row->id}}" data-toggle="modal" data-target="#editfl_modal">
												<span class="glyphicon glyphicon-pencil"></span></button>
												
												<button class="btn btn-danger btn-xs delete" onClick="funDelete('{{$row->id}}','{{$docrow->id}}')">
												<span class="glyphicon glyphicon-trash"></span>
											</div>
                                        </div>
                                    </li>
									<?php }
									 }
									 ?>
                                </ul>
                            </div>
                            <!--timeline ends-->
							<div id="followup_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Customer Follow up</h4>
                                        </div>
										
                                        <div class="modal-body" id="customerData">
											<div class="panel panel-success filterable" id="newCustomerFrm">
											<div class="panel-heading">
												<h3 class="panel-title">
													<i class="fa fa-fw fa-columns"></i> New Entry
												</h3>
											</div>
											<div class="panel-body"> 
												<div class="col-xs-10">
													<div id="addressDtls">
													<form class="form-horizontal" role="form" method="POST" name="frmFollows" id="frmFollows">
													<input type="hidden" name="_token" value="{{ csrf_token() }}">
														<div class="form-group">
															<label for="input-text" class="col-sm-3 control-label">Date</label>
															<div class="col-sm-9">
																<input type="hidden" name="lead_id" id="lead_id" value="{{$docrow->id}}">
																<input type="text" class="form-control pull-right" autocomplete="off" name="voucher_date" data-language='en' id="voucher_date" placeholder="{{date('d-m-Y')}}"/>
															</div>
														</div>
														
														<div class="form-group">
															<label for="input-text" class="col-sm-3 control-label">Title</label>
															<div class="col-sm-9">
																<input type="text" class="form-control" id="title" name="title" placeholder="Title">
															</div>
														</div>
														
														<div class="form-group">
															<label for="input-text" class="col-sm-3 control-label">Description</label>
															<div class="col-sm-9">
																<textarea class="form-control" id="description" name="description"></textarea>
															</div>
														</div>
														
														<div class="form-group">
															<label for="input-text" class="col-sm-3 control-label"></label>
															<div class="col-sm-9">
																<button type="button" class="btn btn-primary" id="create">Create</button>
															</div>
														</div>
													 </form>
													</div>
												</div>
											</div>
										</div>			
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                    </div>
					
						<div id="editfl_modal" class="modal fade animated" role="dialog">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal">&times;</button>
												<h4 class="modal-title">Customer Follow up</h4>
											</div>
											
											<div class="modal-body" id="customerData">
												<div class="panel panel-success filterable" id="newCustomerFrm">
												<div class="panel-heading">
													<h3 class="panel-title">
														<i class="fa fa-fw fa-columns"></i> Edit Entry
													</h3>
												</div>
												<div class="panel-body"> 
													<div class="col-xs-10">
														<div id="addressDtls">
														<form class="form-horizontal" role="form" method="POST" name="frmFollowsed" id="frmFollowsed">
														<input type="hidden" name="_token" value="{{ csrf_token() }}">
															<div id="entry_data"></div>
															<div class="form-group">
																<label for="input-text" class="col-sm-3 control-label"></label>
																<div class="col-sm-9">
																	<button type="button" class="btn btn-primary" id="update">Update</button>
															</div>
														 </form>
														</div>
													</div>
												</div>
											</div>			
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
											</div>
										</div>
									</div>
						</div>
					
                        </div>
						
						<div class="btn-section">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                                <span class="pull-left">
									<button type="button" onclick="javascript:window.close();"
                                                     class="btn btn-responsive button-alignment btn-primary"
                                                     data-toggle="button">
                                                <span style="color:#fff;">
                                                    <i class="fa fa-fw fa-arrow-circle-left"></i>
                                                Close 
                                            </span>
                                </button></span>
								
								<span class="pull-right">
									<button type="button" onclick="getEnquiry('{{$docrow->id}}')"
                                                     class="btn btn-responsive button-alignment btn-primary"
                                                     data-toggle="button">
                                                <span style="color:#fff;">
                                                    <i class="fa fa-fw fa-check-circle"></i>
                                                Set Enquiry 
                                            </span>
                                </button></span>
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
        <!-- end of page level js -->
<script type="text/javascript" src="{{asset('assets/vendors/wow/js/wow.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/time_line.js')}}"></script>

<script>
"use strict";
function getEnquiry(id) { location.href="{{url('leads/set_enquiry')}}/"+id; }

$('#issue_date').datepicker( { 
			dateFormat: 'dd-mm-yyyy',
			autoclose: 1
	});
	
$(document).ready(function () {
	<?php if($docrow->customer_type==0) { ?>
	$('#old_cust').toggle();
	<?php } else { ?>
	$('#new_cust').toggle();
	<?php } ?>
	
    $('#frmLeads').bootstrapValidator({
        fields: {
            customer_name: { validators: { notEmpty: { message: 'The customer name is required and cannot be empty!' } }},
            title: { validators: { notEmpty: { message: 'Title is required and cannot be empty!' } }},
            remarks: { validators: { notEmpty: { message: 'Remarks is required and cannot be empty!' } }}
        }
        
    }).on('reset', function (event) {
        $('#frmLeads').data('bootstrapValidator').resetForm();
    });
});

$(function() {	

	$('#create').on('click', function(e) { 
		
		var id = $('#frmFollows #lead_id').val();
		var dt = $('#frmFollows #voucher_date').val();
		var tl = $('#frmFollows #title').val();
		var ds = $('#frmFollows #description').val();
		
		if(tl=="") {
			alert('Title is required!');
			return false;
		} else if(ds=="") {
			alert('Description is required!');
			return false;
		} else {
				$.ajax({
				url: "{{ url('leads/new_followup/') }}",
				type: 'get',
				data: 'lead_id='+id+'&date='+dt+'&title='+tl+'&description='+ds,
				success: function(data) { 
					console.log('h');
					location.href="{{ url('leads/followup') }}/"+id;
				}
			})
			
		}
	});
	
	
	$('#customer_type').on('change', function(e){
		if(e.target.value=='0') {
			if( $('#new_cust').is(":hidden") )
				$('#new_cust').toggle();
			
			if( $('#old_cust').is(":visible") )
				$('#old_cust').toggle();
			
		} else {
			if( $('#new_cust').is(":visible") )
				$('#new_cust').toggle();
			
			if( $('#old_cust').is(":hidden") )
				$('#old_cust').toggle();
		}
	});
	
	var custurl = "{{ url('sales_order/customer_data/') }}";
	$('#customer_name').click(function() { 
		$('#customerData').load(custurl, function(result) {
			$('#myModal').modal({show:true});
		});
	});
	
	
	$(document).on('click', '.custRow', function(e) { //console.log($(this).attr("data-trnno"));
	
		$('#customer_name').val($(this).attr("data-name"));
		$('#customer_id').val($(this).attr("data-id"));
	});
	
	$(document).on('click', '.editfl', function(e) {
		var id = this.id;
		var flUrl = "{{ url('leads/load_followup/') }}/"+id; 
		$('#entry_data').load(flUrl, function(result){ 
			$('#myModal').modal({show:true}); 
		});
	});
	
	
	//$('#update').on('click', function(e) { 
	$(document).on('click', '#update', function(e) {
		//console.log('g:'+$('#frmFollowsed #title').val());
		var id = $('#frmFollowsed #id').val();
		var ld = $('#frmFollowsed #lead_id').val();
		var dt = $('#frmFollowsed #voucher_date').val();
		var tl = $('#frmFollowsed #title').val();
		var ds = $('#frmFollowsed #description').val();
		
		if(tl=="") {
			alert('Title is required!');
			return false;
		} else if(ds=="") {
			alert('Description is required!');
			return false;
		} else {
				$.ajax({
				url: "{{ url('leads/edit_followup/') }}",
				type: 'get',
				data: 'id='+id+'&lead_id='+ld+'&date='+dt+'&title='+tl+'&description='+ds,
				success: function(data) { //console.log(data);
					location.href="{{ url('leads/followup') }}/"+ld;
				}
			})
			
		}
	});
});

function funDelete(id,lid) {
	var con = confirm('Are you sure delete this entry?');
	if(con==true) {
		var url = "{{ url('leads/delete_folo/') }}";
		location.href = url+'/'+id+'/'+lid;
	}
}

</script>
@stop
