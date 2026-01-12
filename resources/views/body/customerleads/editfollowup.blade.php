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
	
	<link href="{{asset('assets/vendors/bootstrap-multiselect/css/bootstrap-multiselect.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('assets/vendors/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('assets/vendors/select2/css/select2-bootstrap.css')}}" rel="stylesheet" type="text/css">
	
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/buttons.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/colReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/dataTables.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/rowReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/scroller.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatablesmark.js/css/datatables.mark.min.css')}}"/>
	
	<link rel="stylesheet" href="{{asset('assets/vendors/datetime/css/jquery.datetimepicker.css')}}">
    <link href="{{asset('assets/vendors/airdatepicker/css/datepicker.min.css')}}" rel="stylesheet" type="text/css">
	<style>
	 input.b {
             visibility: hidden;
         }
		.my-custom-scrollbar {
			position: relative;
			height: 150px;
			overflow: auto;
		}
		.table-wrapper-scroll-y {
			display: block;
		}
		dt{font-weight:normal !important;}
	</style>
        <!--end of page level css-->
@stop
  
{{-- Page content --}}
@section('content')
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
                                <i class="fa fa-fw fa-crosshairs"></i> Edit Customer
                                    <div class="pull-right">
						
                                    <?php if($date != ''){ ?>
					                    
                                        <a href="{{ url('customerleads/followups/'.$date) }}" class="btn btn-info btn-sm">
								<span >
									<i class="fa fa-fw fa-arrow-circle-left"></i>Back 
								</span>
							 </a>
						<?php }
						?>
                            </button>
						
                        </div>
                        </h3>   
                           
                        </div>


                        <div class="panel-body">
                            <form class="form-horizontal" role="form" method="POST" name="frmLeads" id="frmLeads" action="{{ url('customerleads/updatedatefollowup/'.$docrow->id)}}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="id" id="id" value="{{$docrow->id}}">
                                <input  class="b" type="text"  name="date_hidden" id="date_hidden"   value="{{date('d-m-Y',strtotime($date))}}"/>
								
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Company Name</label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control" id="company_name" name="company_name" value="{{$docrow->master_name}}"  placeholder="Company Name" >
                                    </div>
                                    <label for="input-text" class="col-sm-1 control-label">Reg. Date</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control pull-right" autocomplete="off" name="remark_date" data-language='en'  value="{{date('d-m-Y')}}" readonly />
								   </div>
                                </div>

								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Contact Person</label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control" id="customer_name" name="customer_name" value="{{$docrow->contact_name}}" placeholder="Contact Person">
                                    </div>

                                    <label for="input-text" class="col-sm-1 control-label">Address 1</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="address" id="address" class="form-control" autocomplete="off" value="{{$docrow->address}}"  placeholder="Address">
                                    </div>
                                </div>
                                
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Address 2</label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control" id="address2" name="address2" value="{{$docrow->city}}"  placeholder="Address 2">
                                    </div>

                                    <label for="input-text" class="col-sm-1 control-label">Address 3</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="address3" id="address3" class="form-control" value="{{$docrow->state}}" autocomplete="off"   placeholder="Address 3">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Phone 1</label>
									<div class="col-sm-1">
										<select id="code" class="form-control select2" name="code">
											@foreach($code as $cod)
											<option value="{{$cod->code}}" <?php echo ($cod->code==$docrow->vat_assign)?'selected':'';?>>{{$cod->code}}</option>
											@endforeach
										</select>
									</div>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" id="phone" name="phone" value="{{$docrow->phone}}"  placeholder="Phone 1">
                                    </div>
                                    <label for="input-text" class="col-sm-1 control-label">Phone 2</label>
									<div class="col-sm-1">
										<select id="code1" class="form-control select2" name="code1">
											@foreach($code as $cod)
											<option value="{{$cod->code}}" <?php echo ($cod->code==$docrow->vat_percentage)?'selected':'';?>>{{$cod->code}}</option>
											@endforeach
										</select>
									</div>
                                    <div class="col-sm-3">
                                         <input type="text" class="form-control" id="phone2" name="phone2" value="{{$docrow->fax}}"  placeholder="Phone 2">
                                    </div>
                                </div>
								
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Email 1</label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control" id="email" name="email" value="{{$docrow->email}}"  placeholder="Email 1">
                                    </div>
                                    <label for="input-text" class="col-sm-1 control-label">Email 2</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" id="email2" name="email2" value="{{$docrow->reference}}"   placeholder="Email 2">
                                    </div>

                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Area</label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control" id="area" name="area" value="{{$docrow->area_id}}"  placeholder="Area">
                                    </div>
                                    <label for="input-text" class="col-sm-1 control-label">Country</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" id="country" name="country" value="{{$docrow->country_id}}"  placeholder="Country">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Product</label>
                                    <div class="col-sm-5">
                                         <select id="select22" class="form-control select2" name="products[]" multiple>
											@foreach($items as $item)
											@php
											$values = explode(',', $rowfolo->product_id);
											$sel = (in_array($item->id, $values))?'selected':'';
											@endphp
											<option value="{{$item->id}}" {{$sel}}>{{$item->description}}</option>
											@endforeach
										</select>
                                    </div>
                                    <label for="input-text" class="col-sm-1 control-label">Website</label>
                                    <div class="col-sm-4">
                                         <input type="text" class="form-control" id="website" name="website" value="{{$docrow->pin}}" readonly placeholder="Website">
                                    </div>
                                </div>

                            <fieldset>
                            <!-- <legend style="margin-bottom:0px !important;"><h5><span class="itmDtls">Follow Up Details</span></h5></legend> -->
							
							<input type="hidden" value="{{$rowfolo->id}}" name="fid">
                            <br>
							
					<div class="form-group">
							<label for="input-text" class="col-sm-2 control-label"></label>
							<div class="col-sm-10">
								<div class="panel panel-default">
                                <div class="box-body my-custom-scrollbar">
                                     <?php if(!empty($remarks)){ ?>
                                 <table class="table" border="0" >
                                 
  
                            @foreach($remarks as $row)
                            <?php if(($row->remark_date != '0000-00-00') && ($row->remark != ''))   { ?>
                            <tr  >
                            
                             <td col width="300">{{date('d-m-Y',strtotime($row->remark_date))}}:</td>
                             <td  col width="10000">{{$row->remark}}</td>
                               </tr>
                               <?php }?>
                           @endforeach


                     </table>
                      <?php } ?>
                    </div>
                     </div>
							</div>
						</div>
								
								
                               <div class="form-group">
                                    <label for="input-text" id="remark_label" class="col-sm-2 control-label">Remarks</label>
                                    <div class="col-sm-10">
                                        <textarea id="remark" style="resize:none" class="form-control" name="remark"  ></textarea>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" id="next_date_label" class="col-sm-2 control-label">Next Followup</label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control pull-right" autocomplete="öff"  id="next_date" name="next_date"  data-language='en'  />
                                    </div>
								
							
                                    
                                    <label for="input-text" class="col-sm-1 control-label">Status</label>
                                    <div class="col-sm-4">
                                        <select id="status" class="form-control select2" style="width:100%" name="status">
                                            <option value="1" @if($rowfolo->status==1) {{'selected'}} @else {{''}} @endif>Customer</option>
											<option value="2" @if($rowfolo->status==2) {{'selected'}} @else {{''}} @endif>Enquiry</option>
											<option value="3" @if($rowfolo->status==3) {{'selected'}} @else {{''}} @endif>Prospective</option>
											<option value="4" @if($rowfolo->status==4) {{'selected'}} @else {{''}} @endif>Archive</option>
										</select>
                                    </div>
                                </div>
                               
                       </fieldset>
								
								
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary" id="saveIt" >Submit</button>
                                       
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

<script src="{{asset('assets/vendors/bootstrap-multiselect/js/bootstrap-multiselect.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/select2/js/select2.js')}}" type="text/javascript"></script>

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

	
<script src="{{asset('assets/vendors/datetime/js/jquery.datetimepicker.full.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.en.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/custom_js/advanceddate_pickers.js')}}"></script>
        <!-- end of page level js -->

<script>
"use strict";

$('#next_date').datepicker( { 
	dateFormat: 'dd-mm-yyyy',
	autoClose: true
});
$(function() {
   $('#saveIt').on('click', function(e){  
     
    if($('#status option:selected').val()==2 || $('#status option:selected').val()==3) {
        if($('#remark').val()=='') {
            alert('Remarks is required!');
            return false;
        } else if($('#next_date').val()=='') {
            alert('Next date is required!')
            return false;
        } else {
            console.log('submited');
           // frmLeads.submit();
        } 
    }
   });
 
});		
$(document).ready(function () {
	
	$("#select22").select2({
        theme: "bootstrap",
        placeholder: "Select Multiple Products"
    });
	
	var phone_url = "{{ url('customerleads/check_phone/') }}";
	var email_url = "{{ url('customerleads/check_email/') }}";
    $('#frmLeads').bootstrapValidator({
        fields: {
            company_name: { validators: { notEmpty: { message: 'The company name is required and cannot be empty!' } }},
			phone: { validators: { 
					notEmpty: { message: 'Phone is required and cannot be empty!' },
					remote: { url: phone_url,
							  data: function(validator) {
								return { phone: validator.getFieldElements('phone').val(),
										 code: validator.getFieldElements('code').val(),
										 id: validator.getFieldElements('id').val() };
							  },
							  message: 'Phone no is already registered with another customer!'
                    }
                }
            },
			phone2: { validators: { 
					remote: { url: phone_url,
							  data: function(validator) {
								return { phone2: validator.getFieldElements('phone2').val(),
										 code: validator.getFieldElements('code').val(),
										 id: validator.getFieldElements('id').val() };
							  },
							  message: 'Phone2 no is already registered with another customer!'
                    }
                }
            },
			email: { validators: { 
					remote: { url: email_url,
							  data: function(validator) {
								return { email: validator.getFieldElements('email').val(),
										 id: validator.getFieldElements('id').val() };
							  },
							  message: 'Email is already exist!'
                    }
                }
            },
			email2: { validators: { 
					remote: { url: email_url,
							  data: function(validator) {
								return { email2: validator.getFieldElements('email2').val(),
										 id: validator.getFieldElements('id').val() };
							  },
							  message: 'Email2 is already exist!'
                    }
                }
            }
		//	remark: { validators: { 
				//	notEmpty: {
                      //  message: 'Remark is required and cannot be empty!'
                 //   }
               // }
            //},
		//	next_date: { validators: { 
				//	notEmpty: {
                    //    message: 'Follow up date is required and cannot be empty!'
                   // }
               // }
           // }
        }
        
    }).on('reset', function (event) {
        $('#frmLeads').data('bootstrapValidator').resetForm();
    });
});
$(function() {
   
	$(document).on('change', '#status', function(e) { 
	  // if($('#status option:selected').val()=='2')
      if($('#status option:selected').val()==0)
       {
       $('#remark').hide();
       $('#remark_label').hide();
       $('#next_date_label').hide();
       $('#next_date').hide();
    }
      if($('#status option:selected').val()==2)
       {
       
       $('#frmLeads').bootstrapValidator({
        fields: {
            remark: { validators: { 
			 		notEmpty: {
                         message: 'Remark is required and cannot be empty!'
                  }
                 }
             },
            next_date: { validators: { 
			 		notEmpty: {
                         message: 'Follow up date is required and cannot be empty!'
                    }
                 }
             }
        }
        
    })
    $('#remark').show();
       $('#remark_label').show();
       $('#next_date_label').show();
       $('#next_date').show();
    }
    if($('#status option:selected').val()==3)
       {
           $('#frmLeads').bootstrapValidator({
        fields: {
            remark: { validators: { 
			 		notEmpty: {
                         message: 'Remark is required and cannot be empty!'
                  }
                 }
             },
            next_date: { validators: { 
			 		notEmpty: {
                         message: 'Follow up date is required and cannot be empty!'
                    }
                 }
             }
        }
        
    })
       $('#remark').show();
       $('#remark_label').show();
       $('#next_date_label').show();
       $('#next_date').show();
       
    }
    if($('#status option:selected').val()==1)
        {
        $('#remark').show(); 
        $('#remark_label').show();
       $('#next_date_label').hide();
          $('#next_date').hide(); 

       
        }
    if($('#status option:selected').val()==4)
        {
        $('#remark').show(); 
        $('#remark_label').show();
       $('#next_date_label').hide();
          $('#next_date').hide(); 
        }

    });
});
$(document).ready(function () { 
    <?php if($rowfolo->status==1) { ?>
        $('#remark').show();
       $('#remark_label').show();
       $('#next_date_label').hide();
       $('#next_date').hide();
	<?php } ?>
    <?php if($rowfolo->status==2) { ?>
       
       $('#remark').show();
       $('#remark_label').show();
       $('#next_date_label').show();
       $('#next_date').show();
	<?php } ?>
    <?php if($rowfolo->status==3) { ?>
       
       $('#remark').show();
       $('#remark_label').show();
       $('#next_date_label').show();
       $('#next_date').show();
	<?php } ?>
    <?php if($rowfolo->status==4) { ?>
        $('#remark').show();
       $('#remark_label').show();
       $('#next_date_label').hide();
       $('#next_date').hide();
	<?php } ?>

});
</script>
@stop
