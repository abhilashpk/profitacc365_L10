@extends('layouts/default')

    {{-- Page title --}}
    @section('title')
         
        @parent
    @stop

{{-- page level styles --}}
@section('header_styles')
    
	<!--page level css -->
	<link rel="stylesheet" href="{{asset('assets/vendors/datetime/css/jquery.datetimepicker.css')}}">
    <link href="{{asset('assets/vendors/airdatepicker/css/datepicker.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('assets/vendors/bootstrap-multiselect/css/bootstrap-multiselect.css')}}" rel="stylesheet" type="text/css">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/buttons.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/colReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/dataTables.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/rowReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/scroller.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatablesmark.js/css/datatables.mark.min.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/responsive_datatables.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/datepicker.css')}}">

	<link href="{{asset('assets/vendors/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('assets/vendors/select2/css/select2-bootstrap.css')}}" rel="stylesheet" type="text/css">
    <!--end of page level css-->
		
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <!--section starts-->
            <h1>
                Sales Split Return
            </h1>
            <ol class="breadcrumb">
                <li>
                      <i class="fa fa-fw fa-shield"></i> Inventory
                </li>
				<li>
                    <a href="#">Sales Split Return</a>
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
                            <i class="fa fa-fw fa-list-alt"></i> Sales Split Return &nbsp;  
                        </h3>
						@if($isdept)
							<select id="department" class="form-control select2" name="department" style="width:20%;">
								<option value="">All Department</option>
								@foreach($departments as $row)
								<option value="{{$row->id}}">{{$row->name}}</option>
								@endforeach
							</select>
							@endif
							
                        <div class="pull-right">
							@can('ss-create')
                             <a href="{{ url('sales_split_return/add') }}" class="btn btn-primary btn-sm">
									<span class="btn-label">
									<i class="glyphicon glyphicon-plus"></i>
								</span> Add New
							</a>
							@endcan
                        </div>
                    </div>
                    <div class="panel-body">
						 
                        <div class="table-responsive m-t-10">
                                <table class="table horizontal_table table-striped" id="tablePurSplit">
                                    <thead>
                                    <tr>
										<th>SSR. No</th>
										<th>SSR. Date</th>
										<th>Job No.</th>
										<th>Customer</th>
										<th>Amount</th>
										<th></th>
										<th></th>
										<th></th>
                                    </tr>
                                    </thead>
                                    
                                </table>
                            </div>
                    </div>
                </div>
            </div>
		</section>
		
		
		<section class="content">
			<form class="form-horizontal" role="form" method="POST" name="frmPOReport" id="frmPOReport" action="{{ url('sales_split_return/search') }}" target="_blank">
			 <input type="hidden" name="_token" value="{{ csrf_token() }}">
			 <input type="hidden" name="department_id" id="department_id">
			 <div class="row">
			 <div class="col-lg-12">
					<div class="panel panel-info">
						<div class="panel-heading clearfix">
							<h3 class="panel-title pull-left m-t-6">
								<i class="fa fa-fw fa-list-alt"></i> Sales Split Report
							</h3>
						</div>
						<div class="panel-body">
								<div class="row">
									<div class="col-xs-6">
										<span>Date From:</span>
										<input type="text" name="date_from" data-language='en' autocomplete="off" id="date_from" class="form-control input-sm">
										<span>Date To:</span>
										<input type="text" name="date_to" data-language='en' autocomplete="off" id="date_to" class="form-control input-sm">
									</div>
									<div class="col-xs-6">
										<span>Search By:</span>
										<select id="search_type" class="form-control select2" style="width:100%" name="search_type">
											<option value="summary" <?php if($type=='summary') echo 'selected';?>>Summary  </option>
											<!-- <option value="detail" <?php //if($type=='detail') echo 'selected';?>></option> -->
											<option value="customer" <?php if($type=='customer') echo 'selected';?>>Customerwise</option>
											<!-- <option value="item" <?php //if($type=='item') echo 'selected';?>></option>
											<option value="summarysalesman" <?php //if($type=='summarysalesman') echo 'selected';?>></option>
											<option value="salesman" ></option>
											<option value="group" <?php //if($type=='group') echo 'selected';?>></option>
											<option value="invoice" <?php //if($type=='invoice') echo 'selected';?>></option> -->
										</select>
																				
										<div class="col-xs-10" style="border:0px solid red;" id="selcust">
										</div>
										
										<div class="col-xs-10" style="border:0px solid red;" id="selitm">
										</div>
										<div class="col-xs-10" style="border:0px solid red;" id="selsale">
										</div>
										<div class="col-xs-10" style="border:0px solid red;" id="selsalesummary">
										</div>
										<div class="col-xs-10" style="border:0px solid red;" id="selarea">
										</div>
										<div class="col-xs-10" style="border:0px solid red;" id="selgroup">
										</div>
										<div class="col-xs-10" style="border:0px solid red;" id="selsubgroup">
										</div><br>
										<div class="col-xs-10" style="border:0px solid red;" id="invoice"></div>
							                    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
                                                <script type="text/javascript">
                                                    //  $(document).ready(function () {
                                                    //     $("#search_type").change(function () {
                                                    //         var value = $(this).val(); var toAppend = '';
                                                    //            if (value == "invoice") {
                                                    //                 toAppend = "From :<input type='text' name='invoice_from' data-language='en' id='invoice_from' autocomplete='off'>&nbsp;To :<input type='textbox' name='invoice_to' data-language='en' id='invoice_to' autocomplete='off' >"; $("#invoice").html(toAppend); return;
                                                    //                }
                                                    //                else{
                                                    //                	return;
                                                    //                }
                                                    //          });
                                                    //   });
                                                 </script>
									 <div class="col-xs-12" align="right"> <button type="submit" class="btn btn-primary">Search</button></div>
										
									</div>
									
										
								</div>
								
										
								
								</div>
								
								</div>
								</div>
								</div>
						</div>
					</div>
			</div>
			</div>
			</form>
		</section>
		
@stop

{{-- page level scripts --}}
@section('footer_scripts')

    <!-- begining of page level js -->
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
<!-- end of page level js -->

<script src="{{asset('assets/vendors/datetime/js/jquery.datetimepicker.full.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.en.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/custom_js/advanceddate_pickers.js')}}"></script>

<script src="{{asset('assets/vendors/select2/js/select2.js')}}" type="text/javascript"></script>

<script>

$('#date_from').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy' } );
$('#date_to').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy' } );
function funDelete(id) {
	var con = confirm('Are you sure delete this purchase split?');
	if(con==true) {
		var url = "{{ url('sales_split_return/delete/') }}";
		location.href = url+'/'+id;
	}
}

$(function() {
		
		var dtInstance = $("#tablePurSplit").DataTable({
			"processing": true,
			"serverSide": true,
			"searching": true,
			"order": [[ 0, 'desc' ]],
			"ajax":{
					 "url": "{{ url('sales_split_return/paging/') }}",
					 "dataType": "json",
					 "type": "POST",
					 "data": function(data){
						  var dept = $('#department option:selected').val();
						  data._token = "{{csrf_token()}}";
						  data.dept = dept;
					  }
				   },
			"columns": [
			{ "data": "voucher_no" },
			{ "data": "voucher_date" },
			{ "data": "jobno" },
			{ "data": "supplier" },
			{ "data": "net_total" },
			@can('ss-edit'){ "data": "edit","bSortable": false },@endcan
			@can('ss-print'){ "data": "print","bSortable": false },@endcan
			@can('ss-delete'){ "data": "delete","bSortable": false }@endcan
		]	
		  
		});

		$(document).on('change', '#department', function(e) {  
			
			dtInstance.draw();
			$('#department_id').val( $('#department option:selected').val() );
		});
 });
 $(document).ready(function () {
	$('#selcust').toggle();
	$('#selitm').toggle();
	$('#selsale').toggle();
	$('#selarea').toggle();
	$('#selgroup').toggle();
	$('#selsubgroup').toggle();
	

   /*  $("#multiselect2").multiselect({
        enableFiltering: true,
        includeSelectAllOption: true,
        maxHeight: 300,
        dropUp: true
    }); */
	
	/* $("#multiselect3").multiselect({
        enableFiltering: true,
        includeSelectAllOption: true,
        maxHeight: 300,
        dropUp: true
    }); */
	
	$(document).on('change', '#search_type', function(e) { //$('#search_type').on('change', function(e){
		var vchr = e.target.value; 
		if(vchr=='customer') {
			
			if( $("#selcust").is(":hidden") )
				$("#selcust").toggle();
			
			// if( $("#selitm").is(":visible") )
			// 	$("#selitm").toggle();
			/* $.get("{{ url('profit_analysis/getcustomer/') }}", function(data) {
				//$('#multiselect2').find('option').remove().end();
				$.each(data, function(key, value) {   
				$('#multiselect2').append($("<option></option>")
							.attr("value",key)
							.text(value)); 
				});
			}); */
			
			$.ajax({
				url: "{{ url('sales_split_return/getcustomer/') }}",
				//type: 'get',
				//data: 'item_id='+item_id+'&unit_id='+unit_id,
				success: function(data) {
					$('#selcust').html(data);
					return true;
				}
			}); 
		}
		else if(vchr=='salesman') {
			
			if( $("#selsale").is(":hidden") )
				$("#selsale").toggle();
			
			if( $("#selitm").is(":visible") )
				$("#selitm").toggle();
			/* $.get("{{ url('profit_analysis/getcustomer/') }}", function(data) {
				//$('#multiselect2').find('option').remove().end();
				$.each(data, function(key, value) {   
				$('#multiselect2').append($("<option></option>")
							.attr("value",key)
							.text(value)); 
				});
			}); */
			
			$.ajax({
				url: "{{ url('profit_analysis/getsalesman/') }}",
				//type: 'get',
				//data: 'item_id='+item_id+'&unit_id='+unit_id,
				success: function(data) {
					$('#selsale').html(data);
					return true;
				}
			}); 
		}
		// else if(vchr=='summarysalesman') {
			
		// 	if( $("#selsalesummary").is(":visible") )
		// 		$("#selsalesummary").toggle();
			
		// 	if( $("#selitm").is(":hidden") )
		// 		$("#selitm").toggle();
		// 	/* $.get("{{ url('profit_analysis/getcustomer/') }}", function(data) {
		// 		//$('#multiselect2').find('option').remove().end();
		// 		$.each(data, function(key, value) {   
		// 		$('#multiselect2').append($("<option></option>")
		// 					.attr("value",key)
		// 					.text(value)); 
		// 		});
		// 	}); */
			
		// 	$.ajax({
		// 		url: "{{ url('profit_analysis/getsalesman/') }}",
		// 		//type: 'get',
		// 		//data: 'item_id='+item_id+'&unit_id='+unit_id,
		// 		success: function(data) {
		// 			$('#selsalesummary').html(data);
		// 			return true;
		// 		}
		// 	}); 
		// }
		// else if(vchr=='area') {
			
		// 	if( $("#selarea").is(":hidden") )
		// 		$("#selarea").toggle();
			
		// 	if( $("#selitm").is(":visible") )
		// 		$("#selitm").toggle();
		// 	/* $.get("{{ url('profit_analysis/getcustomer/') }}", function(data) {
		// 		//$('#multiselect2').find('option').remove().end();
		// 		$.each(data, function(key, value) {   
		// 		$('#multiselect2').append($("<option></option>")
		// 					.attr("value",key)
		// 					.text(value)); 
		// 		});
		// 	}); */
			
		// 	$.ajax({
		// 		url: "{{ url('profit_analysis/getArea/') }}",
		// 		//type: 'get',
		// 		//data: 'item_id='+item_id+'&unit_id='+unit_id,
		// 		success: function(data) {
		// 			$('#selarea').html(data);
		// 			return true;
		// 		}
		// 	}); 
		// }
		//  else if(vchr=='item') {
		// 	if( $("#selcust").is(":visible") )
		// 		$("#selcust").toggle();
			
		// 	if( $("#selitm").is(":hidden") )
		// 		$("#selitm").toggle();
			
		// 	$.ajax({
		// 		url: "{{ url('profit_analysis/getitems/') }}",
		// 		success: function(data) {
		// 			$('#selitm').html(data);
		// 			return true;
		// 		}
		// 	}); 

		// }  else if(vchr=='group') {
		// 	if( $("#selgroup").is(":hidden") )
		// 		$("#selgroup").toggle();
			
		// 	if( $("#selitm").is(":visible") )
		// 		$("#selitm").toggle();
			
		// 	$.ajax({
		// 		url: "{{ url('profit_analysis/getgroup/') }}",
		// 		success: function(data) {
		// 			$('#selgroup').html(data);
		// 			return true;
		// 		}
		// 	}); 

		// } 
		// else if(vchr=='subgroup') {
		// 	if( $("#selsubgroup").is(":hidden") )
		// 		$("#selsubgroup").toggle();
			
		// 	if( $("#selitm").is(":visible") )
		// 		$("#selitm").toggle();
			
		// 	$.ajax({
		// 		url: "{{ url('profit_analysis/getSubGroup/') }}",
		// 		success: function(data) {
		// 			$('#selsubgroup').html(data);
		// 			return true;
		// 		}
		// 	}); 

		// } 

		 else {
			if( $("#selcust").is(":visible") )
				$("#selcust").toggle();
			// if( $("#selitm").is(":visible") )
			// 	$("#selitm").toggle();
			// if( $("#selsale").is(":visible") )
			// 	$("#selsale").toggle();
			// if( $("#selgroup").is(":visible") )
			// 	$("#selgroup").toggle();
			// if( $("#selsubgroup").is(":visible") )
			// 	$("#selsubgroup").toggle();
		
		}
	});
	
});
</script>

@stop
