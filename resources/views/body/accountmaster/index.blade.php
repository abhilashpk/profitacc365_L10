@extends('layouts/default')

    {{-- Page title --}}
    @section('title')
         
        @parent
    @stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->

	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/buttons.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/colReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/dataTables.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/rowReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/scroller.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatablesmark.js/css/datatables.mark.min.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/responsive_datatables.css')}}">
        <!--end of page level css-->
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <!--section starts-->
            <h1>
                Account Master
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="fa fa-fw fa-briefcase"></i> Accounts
                    </a>
                </li>
                <li>
                    <a href="#">Account Master</a>
                </li>
            </ol>
			
        </section>
		
        <!--section ends-->
		@if(Session::has('message'))
		<div class="alert alert-success">
			<p>{{ Session::get('message') }}</p>
		</div>
		@endif
		
		@if(Session::has('error'))
		<div class="alert alert-danger">
			<p>{{ Session::get('error') }}</p>
		</div>
		@endif
		
        <section class="content">
            <div class="row">
            <div class="col-lg-12">
               <div class="panel panel-info">
                        <div class="panel-heading clearfix">
                            <h3 class="panel-title pull-left m-t-6">
                                <i class="fa fa-fw fa-columns"></i> Account Master List &nbsp; 
                            </h3>
							
							@if($isdept)
							<select id="department" class="form-control select2" name="department" style="width:20%;">
								<option value="">All Department</option>
								@foreach($department as $row)
								<option value="{{$row->id}}">{{$row->name}}</option>
								@endforeach
							</select>
							@endif
							
							<div class="pull-right">
								@can('ac-master-create')
								 <a href="{{ url('account_master/add') }}" class="btn btn-primary btn-sm">
										<span class="btn-label">
										<i class="glyphicon glyphicon-plus"></i>
									</span> Add New
								</a>
								@endcan
								&nbsp; 
								@can('ac-master-create')
								<?php if($cusid!=''&& $ccategory!='') { ?>
									<a href="" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#customer_modal">
											<span class="btn-label">
											<i class="glyphicon glyphicon-plus"></i>
										</span> Create Customer
									</a>
							    <?php } ?>
								@endcan
							   &nbsp; 
							   @can('ac-master-create')
								<?php if($supid!=''&& $scategory!='') { ?>
									<a href="" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#supplier_modal">
											<span class="btn-label">
											<i class="glyphicon glyphicon-plus"></i>
										</span> Create Supplier
									</a>
								<?php } ?>
								@endcan
								<button type="button" class="btn btn-primary btn-sm" onClick="btnDelete()" data-toggle="dropdown" aria-expanded="false">
                                <span class='glyphicon glyphicon-trash'></span> Remove </button> 

								 @can('ac-hide-show')
								<button type="button" class="btn btn-primary btn-sm" onClick="btnShow()" data-toggle="dropdown" aria-expanded="false">
                                <span class='glyphicon glyphicon-ok'></span> Show </button> 
								
								<button type="button" class="btn btn-primary btn-sm" onClick="btnHide()" data-toggle="dropdown" aria-expanded="false">
                                <span class='glyphicon glyphicon-remove'></span> Hide </button> 
								@endcan
							  </div>
								
							</div>
                        </div>
                        <div class="panel-body">
                            
                            <div class="table-responsive m-t-10">
                                <table class="table horizontal_table table-striped" id="Acmaster">
                                    <thead>
                                    <tr>
										<th>Select</th>
                                        <th>Account ID</th>
                                        <th>Account Master</th>
										<th>Group</th>
										<th>Category</th>
                                        <th>Closing Balance</th>
                                        <th>Open Balance</th>
										<th></th>
										<th></th><th></th>
                                    </tr>
                                    </thead>
                                    
                                </table>
								
                            </div>
                        </div>
						<form class="form-horizontal" role="form" method="POST" name="frmUnits" id="frmUnits">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="ids" id="chkids">
                            </form>
                    </div>
					
					<div id="customer_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Create Customer</h4>
                                        </div>
										
                                        <div class="modal-body" id="customerData">
											<div class="panel panel-success filterable" id="newCustomerFrm">
											<div class="panel-heading">
												<h3 class="panel-title">
													<i class="fa fa-fw fa-columns"></i> New Customer
												</h3>
											</div>
											<div class="panel-body"> 
											
												<div class="col-xs-10">
													<div id="addressDtls">
													<form class="form-horizontal" role="form" method="POST" name="frmCustomer" id="frmCustomer">
													<input type="hidden" name="_token" value="{{ csrf_token() }}">
													<input type="hidden" name="category" value="{{$ccategory}}">
													<input type="hidden" class="form-control" id="account_id" name="account_id">
														<hr/>
														
														<div class="form-group">
															<label for="input-text" class="col-sm-5 control-label">Customer Name</label>
															<div class="col-sm-7">
																<input type="text" class="form-control" id="customer_name" name="customer_name" placeholder="Customer Name">
															</div>
														</div>
														
														<div class="form-group">
															<label for="input-text" class="col-sm-5 control-label">Address</label>
															<div class="col-sm-7">
																<input type="text" class="form-control" id="address" name="address" placeholder="Address1, Address2">
															</div>
														</div>

														<?php if($formdata['customer_country']==1) { ?>
														<div class="form-group">
															<label for="input-text" class="col-sm-5 control-label">Country</label>
															<div class="col-sm-7">
																<select id="country_id" class="form-control select2" style="width:100%" name="country_id">
																	<option value="">Select Country...</option>
																	@foreach ($country as $con)
																	<option value="{{ $con['id'] }}">{{ $con['name'] }}</option>
																	@endforeach
																</select>
															</div>
														</div>
														<?php } else { ?>
								                            <input type="hidden" name="country_id" id="country_id">
								                        <?php } ?>
														
														<?php if($formdata['customer_area']==1) { ?>
														<div class="form-group">
															<label for="input-text" class="col-sm-5 control-label">Area</label>
															<div class="col-sm-7">
																<select id="area_id" class="form-control select2" style="width:100%" name="area_id">
																	<option value="">Select Area...</option>
																	@foreach ($area as $ar)
																	<option value="{{ $ar['id'] }}">{{ $ar['name'] }}</option>
																	@endforeach
																</select>
															</div>
														</div>
														<?php } else { ?>
								                            <input type="hidden" name="area_id" id="area_id">
								                        <?php } ?>

														@if($isdept)
														<div class="form-group">
															<label for="input-text" class="col-sm-5 control-label">Department</label>
															<div class="col-sm-7">
																<select id="department_id" class="form-control select2" style="width:100%" name="department_id">
																	@if($deptid=='')
																	<option value="">Select Department...</option>
																	@endif
																	@foreach($department as $dep)
																	<option value="{{ $dep->id }}">{{ $dep->name }}</option>
																	@endforeach
																</select>
															</div>
														</div>
														@endif
														
														<div class="form-group">
															<label for="input-text" class="col-sm-5 control-label">Phone</label>
															<div class="col-sm-7">
																<input type="text" class="form-control" id="phone" name="phone" placeholder="Phone">
															</div>
														</div>
														
														<?php if($formdata['customer_email']==1) { ?>
														<div class="form-group">
															<label for="input-text" class="col-sm-5 control-label">Email</label>
															<div class="col-sm-7">
																<input type="text" class="form-control" id="email" name="email" placeholder="Email">
															</div>
														</div>
														<?php } else { ?>
								                            <input type="hidden" name="email" id="email">
								                         <?php } ?>
                                                         
														 <?php if($formdata['customer_contact_person']==1) { ?>
														<div class="form-group">
															<label for="input-text" class="col-sm-5 control-label">Contact Person</label>
															<div class="col-sm-7">
																<input type="text" class="form-control" id="contact_name" name="contact_name" placeholder="Contact Person">
															</div>
														</div>
														<?php } else { ?>
								                            <input type="hidden" name="contact_name" id="contact_name">
								                        <?php } ?>
														
														<?php if($formdata['customer_trn']==1) { ?>
														<div class="form-group">
															<label for="input-text" class="col-sm-5 control-label">TRN No</label>
															<div class="col-sm-7">
																<input type="text" class="form-control" id="vat_no" name="vat_no" placeholder="TRN No.">
															</div>
														</div>
                                                        <?php } else { ?>
								                             <input type="hidden" name="vat_no" id="vat_no">
								                        <?php } ?>

														
														<div class="form-group">
															<label for="input-text" class="col-sm-5 control-label"></label>
															<div class="col-sm-7">
																<button type="button" class="btn btn-primary" id="create">Create</button>
															</div>
														</div>
													 </form>
													</div>
													
													<div id="sucessmsg"><br/>
														<div class="alert alert-success">
															<p>
																Customer created successfully.
															</p>
														</div>
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
					
					<div id="supplier_modal" class="modal fade animated" role="dialog">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal">&times;</button>
									<h4 class="modal-title">Create Supplier</h4>
								</div>
								<div class="modal-body" id="supplierData">
									<div class="panel panel-success filterable" id="newSupplierFrm">
									<div class="panel-heading">
										<h3 class="panel-title">
											<i class="fa fa-fw fa-columns"></i> New Supplier
										</h3>
										
									</div>
									<div class="panel-body">
						
										<div class="col-xs-10">
											<div id="addressDtlsSupp">
											<form class="form-horizontal" role="form" method="POST" name="frmSupplier" id="frmSupplier">
											<input type="hidden" name="_token" value="{{ csrf_token() }}">
											<input type="hidden" name="category" value="{{$scategory}}">
											<input type="hidden" class="form-control" id="account_id" name="account_id">
											
												<hr/>
												<div class="form-group">
													<label for="input-text" class="col-sm-5 control-label">Supplier Name</label>
													<div class="col-sm-7">
														<input type="text" class="form-control" id="supplier_name" name="supplier_name" placeholder="Customer Name">
													</div>
												</div>
												
												<div class="form-group">
													<label for="input-text" class="col-sm-5 control-label">Address</label>
													<div class="col-sm-7">
														<input type="text" class="form-control" id="address" name="address" placeholder="Address1, Address2">
													</div>
												</div>
												
												<?php if($formdata['supplier_country']==1) { ?>
												<div class="form-group">
													<label for="input-text" class="col-sm-5 control-label">Country</label>
													<div class="col-sm-7">
														<select id="country_id" class="form-control select2" style="width:100%" name="country_id">
															<option value="">Select Country...</option>
															@foreach ($country as $con)
															<option value="{{ $con['id'] }}">{{ $con['name'] }}</option>
															@endforeach
														</select>
													</div>
												</div>
												<?php } else { ?>
								                    <input type="hidden" name="country_id" id="country_id">
								                <?php } ?>
												
												<?php if($formdata['supplier_area']==1) { ?>
                                                    <div class="form-group">
													<label for="input-text" class="col-sm-5 control-label">Area</label>
													<div class="col-sm-7">
														<select id="area_id" class="form-control select2" style="width:100%" name="area_id">
															<option value="">Select Area...</option>
															@foreach ($area as $ar)
															<option value="{{ $ar['id'] }}">{{ $ar['name'] }}</option>
															@endforeach
														</select>
													</div>
												</div>
												<?php } else { ?>
								                    <input type="hidden" name="area_id" id="area_id">
								                <?php } ?>
												
												@if($isdept)
												<div class="form-group">
													<label for="input-text" class="col-sm-5 control-label">Department</label>
													<div class="col-sm-7">
														<select id="department_id" class="form-control select2" style="width:100%" name="department_id">
															@if($deptid=='')
															<option value="">Select Department...</option>
															@endif
															@foreach($department as $dep)
															<option value="{{ $dep->id }}">{{ $dep->name }}</option>
															@endforeach
														</select>
													</div>
												</div>
												@endif
														
												<div class="form-group">
													<label for="input-text" class="col-sm-5 control-label">Phone</label>
													<div class="col-sm-7">
														<input type="text" class="form-control" id="phone" name="phone" placeholder="Phone">
													</div>
												</div>
												
												<?php if($formdata['supplier_email']==1) { ?>
												<div class="form-group">
													<label for="input-text" class="col-sm-5 control-label">Email</label>
													<div class="col-sm-7">
														<input type="text" class="form-control" id="email" name="email" placeholder="Email">
													</div>
												</div>
												<?php } else { ?>
								                    <input type="hidden" name="email" id="email">
								                <?php } ?>

												<?php if($formdata['supplier_contact_person']==1) { ?>		
												<div class="form-group">
													<label for="input-text" class="col-sm-5 control-label">Contact Person</label>
													<div class="col-sm-7">
														<input type="text" class="form-control" id="contact_name" name="contact_name" placeholder="Contact Person">
													</div>
												</div>
											    <?php } else { ?>
								                    <input type="hidden" name="contact_name" id="contact_name">
								                <?php } ?>

                                                <?php if($formdata['supplier_trn']==1) { ?>
												<div class="form-group">
													<label for="input-text" class="col-sm-5 control-label">TRN No</label>
													<div class="col-sm-7">
														<input type="text" class="form-control" id="vat_no" name="vat_no" placeholder="TRN No.">
													</div>
												</div>
												<?php } else { ?>
								                    <input type="hidden" name="vat_no" id="vat_no">
								                <?php } ?>

												<div class="form-group">
													<label for="input-text" class="col-sm-5 control-label"></label>
													<div class="col-sm-7">
														<button type="button" class="btn btn-primary" id="createSupp">Create</button>
													</div>
												</div>
											 </form>
											</div>
											
											<div id="sucessmsgSupp"><br/>
												<div class="alert alert-success">
													<p>
														Supplier created successfully. 
													</p>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								</div>
							</div></div>
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

$(function() {
	//$('#newCustomerFrm').toggle();
	$('#sucessmsg').toggle();
	$('#sucessmsgSupp').toggle();
	
	$(function() {
          
            var dtInstance = $("#Acmaster").DataTable({
				"bStateSave": true,
				"fnStateSave": function (oSettings, oData) {
					localStorage.setItem( 'DataTables_'+window.location.pathname, JSON.stringify(oData) );
				},
				"fnStateLoad": function (oSettings) {
					return JSON.parse( localStorage.getItem('DataTables_'+window.location.pathname) );
				},
                "processing": true,
				"serverSide": true,
				"ajax":{
						 "url": "{{ url('account_master/paging/') }}",
						 "dataType": "json",
						 "type": "POST",
						 "data": function(data){
							  var dept = $('#department option:selected').val();
							  data._token = "{{csrf_token()}}";
							  data.dept = dept;
						  }
						 //"data":{ _token: "{{csrf_token()}}"}
					   },
				/* "scrollY":        500,
				"deferRender":    true,
				"scroller":       true, */
				"columns": [
				{ "data": "select","bSortable": false },
                { "data": "account_id" },
				{ "data": "master_name" },
                { "data": "group_name" },
                { "data": "category_name" },
                { "data": "cl_balance" },
				{ "data": "op_balance" },
				@can('ac-hide-show'){ "data": "ishide","bSortable": false }, @endcan
				{ "data": "view","bSortable": false },
				{ "data": "edit","bSortable": false },
				//{ "data": "delete","bSortable": false }
             ]
            });
			
			$(document).on('change', '#department', function(e) {  
				dtInstance.draw();
			});
        });
		
		
	
	/* $(document).on('click', '.btn-default', function(e) { 
		location.href = "{{ url('account_master') }}";
	});
	
	$(document).on('click', '.close', function(e) { 
		location.href = "{{ url('account_master') }}";
	}); */
	
	$('#create').on('click', function(e) { 
		
		var ac = $('#frmCustomer #account_id').val();
		var name = $('#frmCustomer #customer_name').val();
		var adrs = $('#frmCustomer #address').val();
		var ar = $('#frmCustomer #area_id option:selected').val();
		var cn = $('#frmCustomer #country_id option:selected').val();
		var ph = $('#frmCustomer #phone').val();
		var vt = $('#frmCustomer #vat_no').val();
		var pn = $('#frmCustomer #contact_name').val();
		var em = $('#frmCustomer #email').val();
		var dpt = $('#frmCustomer #department_id option:selected').val();
		
		if(name=="") {
			alert('Customer name is required!');
			return false;
		} else {		
			$('#addressDtls').toggle();
			
			$.ajax({
				url: "{{ url('account_master/ajax_create/') }}",
				type: 'get',
				data: 'account_id='+ac+'&master_name='+name+'&address='+adrs+'&area_id='+ar+'&country_id='+cn+'&phone='+ph+'&vat_no='+vt+'&category=CUSTOMER&contact_name='+pn+'&email='+em+'&department_id='+dpt,
				success: function(data) { 
					if(data > 0) {
						$('#sucessmsg').toggle();
					} else if(data == 0){
						$('#addressDtls').toggle();
						alert('Customer name already exist!');
						return false;
					} else {
						$('#addressDtls').toggle();
						alert('Something went wrong, Account failed to add!');
						return false;
					}
				}
			})
		}
	});
	
	
			
	$('#createSupp').on('click', function(e){
		
		var ac = $('#frmSupplier #account_id').val();
		var name = $('#frmSupplier #supplier_name').val();
		var adrs = $('#frmSupplier #address').val();
		var ar = $('#frmSupplier #area_id option:selected').val();
		var cn = $('#frmSupplier #country_id option:selected').val();
		var ph = $('#frmSupplier #phone').val();
		var vt = $('#frmSupplier #vat_no').val();
		var pn = $('#frmSupplier #contact_name').val();
		var em = $('#frmSupplier #email').val();
		var dpt = $('#frmSupplier #department_id option:selected').val();
		
		if(name=="") {
			alert('Supplier name is required!');
			return false;
		} else {		
			$('#addressDtlsSupp').toggle();
			
			$.ajax({
				url: "{{ url('account_master/ajax_create/') }}",
				type: 'get',
				data: 'account_id='+ac+'&master_name='+name+'&address='+adrs+'&area_id='+ar+'&country_id='+cn+'&phone='+ph+'&vat_no='+vt+'&category=SUPPLIER&contact_name='+pn+'&email='+em+'&department_id='+dpt,
				success: function(data) { console.log(data);
					if(data > 0) {
						$('#sucessmsgSupp').toggle();
					} else if(data == 0) {
						$('#addressDtlsSupp').toggle();
						//$('#sucessmsg').toggle();
						alert('Supplier name already exist!');
						return false;
					} else {
						$('#addressDtlsSupp').toggle();
						alert('Something went wrong, Account failed to add!');
						return false;
					}
				}
			})
		}
	});
		
});
	
function btnDelete() {  
    
    var checked=false;
    var elements = document.getElementsByName("categoryid[]");
    console.log(elements);
    for(var i=0; i < elements.length; i++){
        if(elements[i].checked) {
            checked = true;
        }
    }
    console.log(checked);
    if (!checked) {
        alert('Please select atleast one Account!');
        return checked;
    } else {
        var con = confirm('Are you sure delete these Accounts ?');
        if(con==true) {
            var id = [];
            $("input[name='categoryid[]']:checked").each(function(){
                id.push($(this).val());
            }); 
           $('#chkids').val(id);
		  // console.log(val(id));
            document.frmUnits.action="{{ url('account_master/destroy') }}"
            document.frmUnits.submit();
        }
    }
}


function btnShow() {  
    
    var checked=false;
    var elements = document.getElementsByName("categoryid[]");
    console.log(elements);
    for(var i=0; i < elements.length; i++){
        if(elements[i].checked) {
            checked = true;
        }
    }
    console.log(checked);
    if (!checked) {
        alert('Please select atleast one Account!');
        return checked;
    } else {
        var con = confirm('Are you sure to show these Accounts ?');
        if(con==true) {
            var id = [];
            $("input[name='categoryid[]']:checked").each(function(){
                id.push($(this).val());
            }); 
           $('#chkids').val(id);
		  // console.log(val(id));
            document.frmUnits.action="{{ url('account_master/show_account') }}"
            document.frmUnits.submit();
        }
    }
}

function btnHide() {  
    
    var checked=false;
    var elements = document.getElementsByName("categoryid[]");
    console.log(elements);
    for(var i=0; i < elements.length; i++){
        if(elements[i].checked) {
            checked = true;
        }
    }
    console.log(checked);
    if (!checked) {
        alert('Please select atleast one Account!');
        return checked;
    } else {
        var con = confirm('Are you sure to hide these Accounts ?');
        if(con==true) {
            var id = [];
            $("input[name='categoryid[]']:checked").each(function(){
                id.push($(this).val());
            }); 
           $('#chkids').val(id);
		  // console.log(val(id));
            document.frmUnits.action="{{ url('account_master/hide_account') }}"
            document.frmUnits.submit();
        }
    }
}
</script>
@stop
