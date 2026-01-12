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
	
        <!--end of page level css-->
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->

   
		 <!-- @if(Session::has('message'))
		<div class="alert alert-success">
			<p>{{ Session::get('message') }}</p>
		</div>
		@endif-->
       
        <!--section ends-->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-crosshairs"></i> {{($customer)?'':'New'}} Customer Details
                            </h3>
                           
                        </div>
          

                        <div class="panel-body">
                            <form class="form-horizontal" role="form" method="POST" enctype="multipart/form-data"  name="frmLeads" id="frmLeads" action="{{ url('customerleads/save') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="customer_id" value="{{($customer)?$customer->id:''}}">

                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Company Name</label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control" id="company_name" name="company_name" autocomplete="off" value="{{($customer)?$customer->master_name:''}}" placeholder="Company Name" >
                                    </div>
                                    <label for="input-text" class="col-sm-1 control-label">Reg. Date</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control pull-right" autocomplete="off" name="remark_date" data-language='en'  value="{{date('d-m-Y')}}" readonly />
								   </div>
                                </div>
                                <div class="form-group">
                                <div class="col-sm-4 control-label" >
                                 <button class='btn btn-primary btn-xs getSts' data-toggle='modal' data-target='#status_modal'>View Details</button>
                                 <input type="button" id="details" name="details"> View Details </input>
								</div>
                                </div>
                                @if(!$customer)
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Contact Person</label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control" id="customer_name" name="customer_name" autocomplete="off" value="{{($customer)?$customer->master_name:''}}" placeholder="Contact Person">
                                    </div>

                                    <label for="input-text" class="col-sm-1 control-label">Address 1</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="address" id="address" class="form-control" autocomplete="off"  value="{{($customer)?$customer->address:''}}" placeholder="Address">
                                    </div>
                                </div>
                                
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Address 2</label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control" id="address2" name="address2" autocomplete="off" value="{{($customer)?$customer->city:''}}" placeholder="Address 2">
                                    </div>

                                    <label for="input-text" class="col-sm-1 control-label">Address 3</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="address3" id="address3" class="form-control" value="{{($customer)?$customer->state:''}}" autocomplete="off"  placeholder="Address 3">
                                    </div>
                                </div>
								@endif
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Phone 1</label>
                                    <div class="col-sm-1">
										<select id="code" class="form-control select2" name="code">
											@foreach($code as $cod)
											<option value="{{$cod->code}}">{{$cod->code}}</option>
											@endforeach
										</select>
									</div>
									<div class="col-sm-4">
                                        <input type="text" class="form-control" id="phone" name="phone" value="{{($customer)?$customer->phone:''}}" autocomplete="off" placeholder="Phone 1">
                                    </div>
                                    <label for="input-text" class="col-sm-1 control-label">Phone 2</label>
                                    <div class="col-sm-1">
										<select id="code1" class="form-control select2" name="code1">
											@foreach($code as $cod)
											<option value="{{$cod->code}}">{{$cod->code}}</option>
											@endforeach
										</select>
									</div>
									<div class="col-sm-3">
                                         <input type="text" class="form-control" id="phone2" name="phone2" value="{{($customer)?$customer->fax:''}}" autocomplete="off" placeholder="Phone 2">
                                    </div>
                                </div>
								
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Email 1</label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control" id="email" name="email" autocomplete="off" value="{{($customer)?$customer->email:''}}" placeholder="Email 1">
                                    </div>
                                    <label for="input-text" class="col-sm-1 control-label">Email 2</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" id="email2" name="email2" autocomplete="off" value="{{($customer)?$customer->reference:''}}" placeholder="Email 2">
                                    </div>

                                </div>
								
								@if(!$customer)
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Area</label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control" id="area" name="area" autocomplete="off" value="{{($customer)?$customer->area_id:''}}" placeholder="Area">
                                    </div>
                                    <label for="input-text" class="col-sm-1 control-label">Country</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" id="country" name="country" autocomplete="off" value="{{($customer)?$customer->country_id:''}}" placeholder="Country">
                                    </div>
                                </div>
								@endif
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Product</label>
                                    <div class="col-sm-5">
                                         <select id="select22" class="form-control select2" name="products[]" multiple>
											@foreach($items as $item)
											<option value="{{$item->description}}">{{$item->description}}</option>
											@endforeach
										</select>
                                    </div>
                                    <label for="input-text" class="col-sm-1 control-label">Website</label>
                                    <div class="col-sm-4">
                                         <input type="text" class="form-control" id="website" name="website" autocomplete="off" value="{{($customer)?$customer->pin:''}}" placeholder="Website">
                                    </div>
                                </div>
                           
							
                            <fieldset>
                            <legend style="margin-bottom:0px !important;"><h5><span class="itmDtls">Follow Up Details</span></h5></legend>
							
                            <br>
                            <br>

                              <div class="form-group">
                                    <label for="input-text"  id="remark_label" class="col-sm-2 control-label">Remarks</label>
                                    <div class="col-sm-10">
                                        <textarea id="remark" style="resize:none" class="form-control" name="remark" ></textarea>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" id="next_date_label" class="col-sm-2 control-label">Next Followup</label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control pull-right" autocomplete="öff"   id="next_date" name="next_date"  data-language='en' />
                                    </div>
                                    <label for="input-text" class="col-sm-1 control-label">Status</label>
                                    <div class="col-sm-4">
                                        <select id="status" class="form-control select2" style="width:100%" name="status">
                                            	<option value="0">----Select Status--------</option>
											<option value="1">Customer</option>
											<option value="2">Enquiry</option>
											<option value="3">Prospective</option>
											<option value="4">Archive</option>
										</select>
                                    </div>
                                </div>

                       </fieldset>

                       <div class="form-group" id="enq" >
                       <label for="input-text" id="next_date_label" class="col-sm-2 control-label"></label>
                                    <div class="col-sm-5">
                                        <input type="hidden" class="form-control pull-right" autocomplete="öff"    />
                                    </div>
                                    <label for="input-text" class="col-sm-1 control-label"></label>
                                    <div class="col-sm-4">
                                    <a href="{{ url('customer_enquiry') }}" target="_blank" class="btn btn-primary">View Customer Enquiry</a>
                                  
                                        <a href="{{ url('customer_enquiry/add') }}" target="_blank" class="btn btn-primary">Add Enquiry </a>
                                    </div>
                                </div>
                      
                                <div class="form-group" id="enq1" >
                       <label for="input-text" id="next_date_label" class="col-sm-2 control-label"></label>
                                    <div class="col-sm-5">
                                        <input type="hidden" class="form-control pull-right" autocomplete="öff"    />
                                    </div>
                                    <label for="input-text" class="col-sm-1 control-label"></label>
                                    <div class="col-sm-4">
                                    <a href="{{ url('quotation_sales') }}" target="_blank" class="btn btn-primary">View Quotation</a>
                                    
                                        <a href="{{ url('quotation_sales/add') }}" target="_blank" class="btn btn-primary">Quotation</a>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary"  id="addIt">Submit</button>
                                        <a href="{{ url('customerleads') }}" class="btn btn-danger">Cancel</a>
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

        <div id="status_modal" class="modal fade animated" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Customer Details</h4>
					</div>
					<div class="modal-body" id="statusForm">
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
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
$(function() {

     $('#company_name').on('click', function(e){  
      
    //    var ac = $('#frmLeads #customer_id').val();
		var name = $('#frmLeads #company_name').val();
        console.log(name);
		if(name == ''){
     //$('#frmLeads #getCust').show();
     $('#frmLeads #details').hide();
    }
}); 
      var name = $('#frmLeads #company_name').val();
	var stsurl = "{{ url('customerleads/get_details/') }}";
	$(document).on('click','.getSts', function() { 
		$('#statusForm').load(stsurl+'/'+$(this).attr("name"), function(result) {
			$('#myModal').modal({show:true});
		});
	});	    
		   
			
	// 		$.ajax({
	// 			url: "{{ url('customerleads/check_name') }}",
	// 		type: 'get',
	// 			data: 'account_id='+ac+'&master_name='+name,
	// 			success: function(data) { 
	// 			    console.log(data);
	// 			  //  console.log(datagcgf);
	// 			 if(data != 0){
						
	// 					alert('Customer name already exist!');
	// 					return false;
	// 				}
				
	// 			}
	// 		})
	  
    
    
   $('#addIt').on('click', function(e){  
     
    if($('#status option:selected').val()==2 || $('#status option:selected').val()==3) {
        if($('#remark').val()=='') {
            alert('Remarks is required!');
            return false;
        } else if($('#next_date').val()=='') {
            alert('Next date is required!')
            return false;
        } else {
            //console.log('submited');
            frmLeads.submit();
        } 
    } else {
        //console.log('submited');
        frmLeads.submit();
    }
    
   });
 
});
$('#next_date').datepicker( { 
	dateFormat: 'dd-mm-yyyy',
	autoClose: true
});
$(function() {
   $('#enq').on('click', function(e){  
     
    if($('#status option:selected').val()==1 || $('#status option:selected').val()==2) {
        if($('#company_name').val()=='') {
            alert('company name is required!');
            return false;
        }  else {
            //console.log('submited');
           // frmLeads.submit();
        } 
    } else {
        //console.log('submited');
      //  frmLeads.submit();
    }
    
   });
 
});	
$(document).ready(function () {
	
	$("#select22").select2({
        theme: "bootstrap",
        placeholder: "Select Multiple Products"
    });
	
	$(document).on('change', '#code', function(e) {  
		$('#code1').val($(this).val()).attr("selected", "selected");
	});
	
	@if($customer==null)
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
										 code: validator.getFieldElements('code').val() };
							  },
							  message: 'Phone no is already registered with another customer!'
                    }
                }
            },
			phone2: { validators: { 
					remote: { url: phone_url,
							  data: function(validator) {
								return { phone2: validator.getFieldElements('phone2').val(),
										 code: validator.getFieldElements('code').val() };
							  },
							  message: 'Phone2 no is already registered with another customer!'
                    }
                }
            },
			email: { validators: { 
					remote: { url: email_url,
							  data: function(validator) {
								return { email: validator.getFieldElements('email').val() };
							  },
							  message: 'Email is already exist!'
                    }
                }
            },
			email2: { validators: { 
					remote: { url: email_url,
							  data: function(validator) {
								return { email2: validator.getFieldElements('email2').val() };
							  },
							  message: 'Email2 is already exist!'
                    }
                }
            }
		//	remark: { validators: { 
				//	notEmpty: {
                       // message: 'Remark is required and cannot be empty!'
                   // }
                //}
           // },
			//next_date: { validators: { 
				//	notEmpty: {
                       // message: 'Follow up date is required and cannot be empty!'
                   // }
                //}
            //}
        }
        
    }).on('reset', function (event) {
        $('#frmLeads').data('bootstrapValidator').resetForm();
    });
	
	@endif
});

$(document).on('blur', '#phone,#phone2', function(e)  { 
	
	if($('#phone').val()!='' || $('#phone2').val()!='') {
		$.ajax({
			url: "{{ url('customerleads/dophone/') }}",
			type: 'get',
			data: 'code='+encodeURIComponent($('#code option:selected').val())+'&phone='+$('#phone').val()+'&phone2='+$('#phone2').val(),
			success: function(data) { 
				if(data!='')
					alert('Phone no already registered under '+data);
				//console.log(data);
			}
		});
	}
});
$(function () { 
     $("#phone").on("keydown", function (e) 
     { 
        
         console.log('clicked');
          -1 !== $.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) || (/65|67|86|88/.test(e.keyCode) && (e.ctrlKey === true || e.metaKey === true)) && (!0 === e.ctrlKey || !0 === e.metaKey) || 35 <= e.keyCode && 40 >= e.keyCode || (e.shiftKey || 48 > e.keyCode || 57 < e.keyCode) && (96 > e.keyCode || 105 < e.keyCode) && e.preventDefault() }); })

     $(function () { 
    $("#phone2").on("keydown", function (e) 
     { 
         console.log('clicked'); 
        
         -1 !== $.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) || (/65|67|86|88/.test(e.keyCode) && (e.ctrlKey === true || e.metaKey === true)) && (!0 === e.ctrlKey || !0 === e.metaKey) || 35 <= e.keyCode && 40 >= e.keyCode || (e.shiftKey || 48 > e.keyCode || 57 < e.keyCode) && (96 > e.keyCode || 105 < e.keyCode) && e.preventDefault() }); })


$(function() {
    $('#remark_label').hide();	
	$('#remark').hide(); 
    $('#enq').hide();
    $('#enq1').hide();
    $('#next_date_label').hide(); 
	$('#next_date').hide();
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
              
       $('#remark').show();
       $('#remark_label').show();
       $('#next_date_label').show();
       $('#next_date').show();
       $('#enq').show();
       $('#enq1').show();
    
    }
    if($('#status option:selected').val()==3)
       {
            
       $('#remark').show();
       $('#remark_label').show();
       $('#next_date_label').show();
       $('#next_date').show();
       $('#enq').hide();
       $('#enq1').hide();
      
    }
    if($('#status option:selected').val()==1)
        {
        $('#remark').show(); 
        $('#remark_label').show();
       $('#next_date_label').hide();
          $('#next_date').hide(); 
          $('#enq').show();
         
        }
    if($('#status option:selected').val()==4)
        {
        $('#remark').show(); 
        $('#remark_label').show();
       $('#next_date_label').hide();
          $('#next_date').hide(); 
          $('#enq').hide();
          $('#enq1').hide();
        }

    });
});
</script>
@stop
