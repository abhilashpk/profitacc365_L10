@extends('layouts/default')

    {{-- Page title --}}
    @section('title')
         
        @parent
    @stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/iCheck/css/all.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/formelements.css')}}">
        <!--end of page level css-->
	<link rel="stylesheet" href="{{asset('assets/vendors/datetime/css/jquery.datetimepicker.css')}}">
    <link href="{{asset('assets/vendors/airdatepicker/css/datepicker.min.css')}}" rel="stylesheet" type="text/css">
    
    <link href="{{asset('assets/vendors/bootstrap-multiselect/css/bootstrap-multiselect.css')}}" rel="stylesheet" type="text/css">
	
	<link href="{{asset('assets/vendors/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('assets/vendors/select2/css/select2-bootstrap.css')}}" rel="stylesheet" type="text/css">
    
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/buttons.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/colReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/dataTables.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/rowReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/scroller.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatablesmark.js/css/datatables.mark.min.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/responsive_datatables.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/datepicker.css')}}">
        <!--end of page level css-->
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <!--section starts-->
             <h1>
                Time Sheet Entry
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="fa fa-fw fa-money"></i> Daily
                    </a>
                </li>
                <li>
                    <a href="#">Time Sheet Entry</a>
                </li>
                <li class="active">
                    Add
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
                                <i class="fa fa-fw fa-crosshairs"></i> Add
                            </h3>
                           
                        </div>
                        <div class="panel-body">
							<div class="controls"> 
                            <form class="form-horizontal" role="form" method="POST" name="frmWageEntry" id="frmWageEntry" action="{{ url('wage_entry/timesheet/save/') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
							<div class="row">	
							<div class="col-sm-2">
									<input type="text" class="form-control pull-right" name="date" data-language='en' id="date" autocomplete="off" value="{{date('d-m-Y')}}" placeholder="Date"/>
							</div>	

                                <div class="col-sm-2">
									<select class="form-control" id="day_type" name="day_type">
									
									<option value="wday">Working Day</option>
									<option value="hday">Holiday</option>
									</select>
								</div>
							
								
							<div class="col-sm-2">
									<select class="form-control" class="form-control select2" style="width:100%" name="emply_id" id="emply_id">
									<option value="0">Select Employee</option>
									@foreach($emply as $row)
									<option value="{{$row->id}}" {{($eid==$row->id)?'selected':''}}>{{$row->name}}</option>
									@endforeach
									</select>
								</div>	

							<div class="col-sm-2">
                                <select  class="form-control"  id="category_id">
                                    <option value="0">Select Category</option>
										@foreach($category as $crow)
											<option value="{{ $crow->id }}" {{($cid==$crow->id)?'selected':''}} >{{ $crow->category_name }}</option>
										@endforeach
                                </select>
                            </div>	

                           <div class="col-sm-2">
							<button type="button" id="search" class="btn btn-primary">Load</button></div>
												<a href="{{ url('wage_entry/timesheet') }}" class="btn btn-danger">Clear</a>
						</div>
						</div>	
									
									
							<div id="getempData"> 
							
							<fieldset>
								<legend><h5>Job Details</h5></legend>
								<div class="itemdivPrnt">
									<div class="itemdivChld">
									<?php 
										
										$i=0;	 
										foreach($employee as $emp) { 	
											$i++;
										?>
										<table border="0" class="table-dy-row">
										<tr>
											
											<td width="12%">
												<span class="small">Employee Name</span>
												<input type="hidden" name="employee_id[]" id="empid_{{$i}}" value="{{$emp->id}}">
												
												<input type="text" id="empname_{{$i}}" name="employee_name[]" class="form-control" autocomplete="off" value="{{$emp->name}}" autocomplete="off"  placeholder="Employee Name">
											</td>
											
											<td width="6%">
												<span class="small">Start Time</span><input type="time" value="" name="start_time[]"  id="start_{{$i}}" step="any" autocomplete="off" class="form-control start-time" >
											</td>
											<td width="6%">
												<span class="small">End Time</span><input type="time" id="end_{{$i}}" step="any" value="" name="end_time[]" autocomplete="off" class="form-control end-time" >
											</td>
											<td width="6%">
												<span class="small">Break</span><input type="text" id="break_{{$i}}" step="any" value="" name="break_time[]" autocomplete="off" class="form-control break-time" >
											</td>
											<td width="4%">
												<span class="small">TWH</span> <input type="number" id="twh_{{$i}}" step="any" name="twh[]" autocomplete="off" class="form-control line-twh" value="" readonly>
											</td>
											<td width="4%">
												<span class="small">NWH</span> <input type="number" id="nwh_{{$i}}" step="any" name="nwh[]" autocomplete="off" class="form-control line-nwh" value="{{$parameter->nwh}}" readonly>
											</td>
											<td width="4%">
												<span class="small">OTHr(G)</span> <input type="number" id="otg_{{$i}}" step="any" name="otg[]" value="" autocomplete="off" class="form-control line-otg" >
												<input type="hidden" id="otgst_{{$i}}" name="otgst[]" class="otgst">
											</td>
											<td width="4%">
												<span class="small">OTHr(H)</span><input type="number" id="oth_{{$i}}" step="any" name="oth[]" value="" autocomplete="off" class="form-control line-oth" >
												<input type="hidden" id="othst_{{$i}}" name="othst[]" class="othst">
											</td>
											<td width="8%">
						                         <span class="small">Job Code</span>
						                        <input type="hidden" name="job_id[]" id="jobid_{{$i}}" value="">
						                         <input type="hidden" name="job_data[]" id="jobdata_{{$i}}">
						                           <input type="text" id="jobcod_{{$i}}" name="job_code[]" class="form-control"  value="" autocomplete="off" data-toggle="modal" data-target="#job_modal" placeholder="Job Code">
					                        </td>
											<td width="4%">
											<input type="hidden" id="wid_{{$i}}" name="work_id[]">
											<a href="" class="btn btn-primary btn-sm work-order" data-toggle="modal" data-target="#work_modal" id="wrkcod_{{$i}}">Job Split</a>
											</td>
											<td width="6%">
						                               <span class="small">Status</span>
						                              <select id="leaves_{{$i}}" name="leaves[]" class="form-control leaves">
							                           <option value="1">Present</option>
													   <option value="0">Absent</option>
						                                </select>
					                       </td >
										   <td width="6%">
										   <div class="levtyp" id="levtyp_{{$i}}" >
                                           <span class="small">Type</span>
										   <select id="levstus_{{$i}}" name="leave_status[]" class="form-control ">
										               <option value="">Select</option>
							                           <option value="UP">Unpaid</option>
													   <option value="P">Paid</option>
						                                </select>
											</div>			
										   </td >
										</tr>
										</table>
										
										
									<?php } ?>
										
									
									</div>
								</div>
							</fieldset>
							</div>
							
								
								<br/>
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                         <a href="{{ url('wage_entry/timesheet') }}" class="btn btn-danger">Cancel</a>
                                    </div>
                                </div>
                                
                            </form>
							</div>
                        </div>
						
						<div id="employee_modal" class="modal fade animated" role="dialog">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal">&times;</button>
										<h4 class="modal-title">Select Employee</h4>
									</div>
									<div class="modal-body" id="employeeData">
													
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
									</div>
								</div>
							</div>
						</div>
						
						<div id="job_modal" class="modal fade animated" role="dialog">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal">&times;</button>
										<h4 class="modal-title">Select Job</h4>
									</div>
									<div class="modal-body" id="job_data">
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
									</div>
								</div>
							</div>
						</div> 


						<div id="work_modal" class="modal fade animated" role="dialog">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal">&times;</button>
										<h4 class="modal-title">Select Sub Jobs</h4>
									</div>
									<div class="modal-body" id="work_data">
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
									</div>
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
        <!-- end of page level js -->

<script src="{{asset('assets/vendors/moment/js/moment.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/datetime/js/jquery.datetimepicker.full.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.en.js')}}" type="text/javascript"></script>
<!--<script src="{{asset('assets/js/custom_js/advanceddate_pickers.js')}}"></script>-->

<script src="{{asset('assets/vendors/select2/js/select2.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/bootstrap-multiselect/js/bootstrap-multiselect.js')}}" type="text/javascript"></script>

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

var nwhr={{$parameter->nwh}};

$(document).ready(function () {
	$('#date').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy' } );
	//$('#date').datepicker( { dateFormat: 'dd-mm-yyyy',minDate: new Date('{{$settings->from_date}}'),maxDate: new Date('{{$settings->to_date}}') } );

$('.levtyp').toggle();


	$("#emply_id").select2({
        theme: "bootstrap",
        placeholder: "Select Employee"
    });

});




$(function() {	
	
	


	var joburl = "{{ url('jobmaster/job_data/') }}";
	$(document).on('click', 'input[name="job_code[]"]', function(e) { 
		var res = this.id.split('_');
		var curNum = res[1]; console.log(curNum);
		$('#job_data').load(joburl+'/'+curNum, function(result){ //.modal-body item
			$('#myModal').modal({show:true});
		});
	});
	
	$(document).on('click', '.jobRow', function(e) {
		var num = $('#num').val(); 
		$('#jobcod_'+num).val( $(this).attr("data-name") );
		$('#jobid_'+num).val( $(this).attr("data-id") );
	});

	
	$(document).on('click', '.work-order', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; console.log(curNum);
		var jid=$('#jobid_'+curNum).val();
        var wid=$('#wid_'+curNum).val();
		if(jid=='') {
		alert('Please select a Job Code first!');
		return false
	      }
		  else{
		  if(wid==''){
		  var wrkurl = "{{ url('wage_entry/subjob_template/') }}";
			$('#work_data').load(wrkurl+'/'+jid+'/'+curNum, function(result){ 
				$('#myModal').modal({show:true}); $('.input-sm').focus()
			});
			}
			else{
			var wrkurl = "{{ url('wage_entry/subjob_template/edit/') }}";
			$('#work_data').load(wrkurl+'/'+jid+'/'+curNum+'/'+wid, function(result){ 
				$('#myModal').modal({show:true}); $('.input-sm').focus()
			});
			}
			}
			});

$(document).on('click', '.subjobRow', function(e) {
		var no = $('#num').val();
		$('#wid_'+no).val($(this).attr("data-id"));
		
		e.preventDefault();
	});
	
	document.getElementById("search").onclick = function () {
        var emp=$('#emply_id').val();
       var cat=$('#category_id').val();
        location.href = "{{ url('wage_entry/timesheet/') }}/"+emp+"/"+cat;
    };
	
	
	$(document).on('blur', '.end-time', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; console.log(curNum);
		var start = parseFloat( ($('#strat_'+curNum).val()=='') ? 0 : $('#start_'+curNum).val() );
		var end = parseFloat( ($('#end_'+curNum).val()=='') ? 0 : $('#end_'+curNum).val() );
		var nwh = parseFloat( ($('#nwh_'+curNum).val()=='') ? 0 : $('#nwh_'+curNum).val() );
		
       var total=parseFloat(end-start);
		$('#twh_'+curNum).val(total);
		if(total>=nwh){
		var otgh= parseFloat(total-nwh);
		var dtype=$('#day_type').val();
		if(dtype=='wday'){
		$('#otg_'+curNum).val(otgh);
		}
		else{
		$('#oth_'+curNum).val(otgh);
		}
		}
		if(total<nwh){
		$('#nwh_'+curNum).val(total);
		}
		else{
		$('#nwh_'+curNum).val(nwhr);
		}
	});

	$(document).on('blur', '.break-time', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; console.log(curNum);
		var start = parseFloat( ($('#strat_'+curNum).val()=='') ? 0 : $('#start_'+curNum).val() );
		var end = parseFloat( ($('#end_'+curNum).val()=='') ? 0 : $('#end_'+curNum).val() );
		var brk = parseFloat( ($('#break_'+curNum).val()=='') ? 0 : $('#break_'+curNum).val() );
		var nwh = parseFloat( ($('#nwh_'+curNum).val()=='') ? 0 : $('#nwh_'+curNum).val() );
		
		        var total=parseFloat(end-start);
		var tot=parseFloat(total-brk);
		$('#twh_'+curNum).val(tot);
		var twh = parseFloat( ($('#twh_'+curNum).val()=='') ? 0 : $('#twh_'+curNum).val() );
		if(twh>=nwh){
		var otgh= parseFloat(tot-nwh);
		var dtype=$('#day_type').val();
		if(dtype=='wday'){
		$('#otg_'+curNum).val(otgh);
		}
		else{
		$('#oth_'+curNum).val(otgh);
		}
		}
		if(tot<nwh){
		$('#nwh_'+curNum).val(tot);
		}
		else{$('#nwh_'+curNum).val(nwhr);}
	});

	$(document).on('change', '.leaves', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		var leve = this.value; 
	if(leve==0)	{
	$('#nwh_'+curNum).val(0);
	if( $('#levtyp_'+curNum).is(":hidden") )
				$('#levtyp_'+curNum).toggle();
	else if( $('#levtyp_'+curNum).is(":visible") )
			$('#levtyp_'+curNum).toggle();
	}
	else{
	if( $('#levtyp_'+curNum).is(":visible") )
			$('#levtyp_'+curNum).toggle();
	
	}

		});
	
});	

</script>
@stop
