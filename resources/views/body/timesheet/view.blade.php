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
                    View
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
                                <i class="fa fa-fw fa-crosshairs"></i> View
                            </h3>
                           
                        </div>
                        <div class="panel-body">
							<div class="controls"> 
                            <form class="form-horizontal" role="form" method="POST" name="frmWageEntry" id="frmWageEntry" action="{{ url('wage_entry/timesheet/approve/') }}" >
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
							<div class="row">	
							<div class="col-sm-2">
								<input type="text" class="form-control pull-right" name="date" data-language='en' id="date" autocomplete="off" value="<?php echo($timesheet==[])?date('d-m-Y'):date('d-m-Y',strtotime($timesheet[0]->date));?>" placeholder="Date"/>
		                     </div>	

                                
							
								
							<div class="col-sm-2">
									<select class="form-control select2" style="width:100%" name="emply_id" id="emply_id">
									<option value="0">Select Employee</option>
									@foreach($emply as $row)
									<option value="{{$row->id}}" {{($eid==$row->id)?'selected':''}} >{{$row->name}}</option>
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
							<button type="button" id="search" class="btn btn-primary">Search</button></div>
												<a href="{{ url('wage_entry/timesheet/view') }}" class="btn btn-danger">Clear</a>
						</div>
						</div>	
									
							<?php if(count($timesheet)>0) {?>		
							<div id="getempData"> 
							
							<fieldset>
								<legend><h5>Job Details</h5></legend>
								<div class="itemdivPrnt">
									<div class="itemdivChld">
									<?php 
										
										$i=0;	 
										foreach($timesheet as $row) { 	
											$i++;
										?>
										<table border="0" class="table-dy-row">
										<tr>
											
											<td width="10%">
												<span class="small">Employee Name</span>
                                                <input type="hidden" name="id[]" id="id_{{$i}}" value="{{$row->id}}">
												<input type="hidden" name="employee_id[]" id="empid_{{$i}}" value="{{$row->employee_id}}">
												
												<input type="text" id="empname_{{$i}}" name="employee_name[]" class="form-control" autocomplete="off" value="{{$row->name}}" autocomplete="off" readonly  placeholder="Employee Name">
											</td>
                                            <td width="8%">
                                            <span class="small">Date</span>
                                            <input type="text" class="form-control " name="date[]" value="{{date('d-m-Y',strtotime($row->date))}}" id="date" data-language='en' autocomplete="off"/>
                                            </td>
                                            <td width="6%">
												<span class="small">Day Type</span><input type="text"  name="day_type[]" value="<?php echo ($row->day_type=='wday')?'Working':'Holiday'; ?>" id="daytype_{{$i}}" readonly step="any" autocomplete="off" class="form-control" >
											</td>
											
											<td width="5%">
												<span class="small">Start Time</span><input type="time"  name="start_time[]" value="<?php echo ($row->start_time=='00:00:00')?'':$row->start_time; ?>" id="start_{{$i}}" readonly step="any" autocomplete="off" class="form-control start-time" >
											</td>
											<td width="5%">
												<span class="small">End Time</span><input type="time" id="end_{{$i}}" step="any" value="<?php echo ($row->end_time=='00:00:00')?'':$row->end_time; ?>" name="end_time[]" readonly autocomplete="off" class="form-control end-time" >
											</td>
											<td width="5%">
												<span class="small">Break</span><input type="text" id="break_{{$i}}" step="any" value="{{$row->break_time}}" name="break_time[]" readonly autocomplete="off" class="form-control break-time" >
											</td>
											<td width="4%">
												<span class="small">TWH</span> <input type="number" id="twh_{{$i}}" step="any" name="twh[]" autocomplete="off" class="form-control line-twh" value="{{$row->twh}}" readonly>
											</td>
											<td width="4%">
												<span class="small">NWH</span> <input type="number" id="nwh_{{$i}}" step="any" name="nwh[]" autocomplete="off" class="form-control line-nwh" value="{{$row->nwh}}" readonly>
											</td>
											<td width="4%">
												<span class="small">OTHr(G)</span> <input type="number" id="otg_{{$i}}" step="any" name="otg[]" value="{{$row->otg}}" autocomplete="off" class="form-control line-otg" readonly>
												
											</td>
											<td width="4%">
												<span class="small">OTHr(H)</span><input type="number" id="oth_{{$i}}" step="any" name="oth[]" value="{{$row->oth}}" autocomplete="off" class="form-control line-oth" readonly>
												
											</td>
											<td width="8%">
						                         <span class="small">Job Code</span>
						                        <input type="hidden" name="job_id[]" id="jobid_{{$i}}" value="{{$row->job_id}}">
						                         <input type="hidden" name="job_data[]" id="jobdata_{{$i}}">
						                           <input type="text" id="jobcod_{{$i}}" name="job_code[]" class="form-control"  value="{{$row->job_name}}" autocomplete="off"  placeholder="Job Code">
					                        </td>
											<td width="5%">
											<input type="hidden" id="wid_{{$i}}" value="{{$row->subjob_id}}"name="work_id[]">
											<a href="" class="btn btn-primary btn-sm work-order" data-toggle="modal" data-target="#work_modal" id="wrkcod_{{$i}}">Job Split</a>
											</td>
											<td width="6%">
						                               <span class="small">Status</span>
						                              <select id="leaves_{{$i}}" name="leaves[]" class="form-control leaves">
							                           <option value="1" <?php if($row->leave_type==1) echo 'selected';?>>Present</option>
													   <option value="0" <?php if($row->leave_type==0) echo 'selected';?>>Absent</option>
						                                </select>
					                       </td>
                                            <td width="6%">
										   <div class="levtyp" id="levtyp_{{$i}}" >
                                           <span class="small">Type</span>
										   <select id="levstus_{{$i}}" name="leave_status[]" class="form-control ">
										               <option value="">Select</option>
							                           <option value="UP" <?php if($row->leave_status=='UP') echo 'selected';?>>Unpaid</option>
													   <option value="P" if($row->leave_status=='P') echo 'selected';?>>Paid</option>
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
                                        <button type="submit" class="btn btn-primary">Approve</button>
                                         <a href="{{ url('wage_entry/timesheet') }}" class="btn btn-danger">Cancel</a>
                                    </div>
                                </div>
                                <?php }  ?>
                                
                               <!-- <div class="alert alert-danger">
							        <ul>No records were found!</ul>
						         </div>
                                 -->
                                
                            </form>
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
										<h4 class="modal-title">View Sub Jobs</h4>
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

$(document).ready(function () {
    
$('#date').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy' } );
	
	$("#emply_id").select2({
        theme: "bootstrap",
        placeholder: "Select Employee"
    });
});




$(function() {	
	
	document.getElementById("search").onclick = function () {
        var emp=$('#emply_id').val();
       var cat=$('#category_id').val();
       var mon=$('#date').val();
        location.href = "{{ url('wage_entry/timesheet/view/') }}/"+emp+"/"+cat+"/"+mon;
    };


    $(document).on('click', '.work-order', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; //console.log(curNum);
		var jid=$('#jobid_'+curNum).val();
        var wid=$('#wid_'+curNum).val();
        //console.log(wid);
		if(wid!=0) {
		var wrkurl = "{{ url('wage_entry/subjob_template/view/') }}";
			$('#work_data').load(wrkurl+'/'+curNum+'/'+wid+'/'+jid, function(result){ 
				$('#myModal').modal({show:true}); $('.input-sm').focus()
			});
			
			}else{
            alert('There is no records!');
		
	      }
		  
	});


	});	

</script>
@stop
