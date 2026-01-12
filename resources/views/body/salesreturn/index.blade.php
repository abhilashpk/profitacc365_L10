@extends('layouts/default')

    {{-- Page title --}}
    @section('title')
         
        @parent
    @stop

{{-- page level styles --}}
@section('header_styles')
    <link rel="stylesheet" href="{{asset('assets/vendors/datetime/css/jquery.datetimepicker.css')}}">
    <link href="{{asset('assets/vendors/airdatepicker/css/datepicker.min.css')}}" rel="stylesheet" type="text/css">
	<!--page level css -->
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
                Sales Return
            </h1>
            <ol class="breadcrumb">
                <li>
                      <i class="fa fa-fw fa-shield"></i> Inventory
                </li>
				<li>
                    <a href="#">Sales Return</a>
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
                            <i class="fa fa-fw fa-list-alt"></i> Sales Return
                        </h3>
                        <div class="pull-right">
						@can('sr-create')
                             <a href="{{ url('sales_return/add') }}" class="btn btn-primary btn-sm">
									<span class="btn-label">
									<i class="glyphicon glyphicon-plus"></i>
								</span> Add New
							</a>
						@endcan
                        </div>
                    </div>
                    <div class="panel-body">
						 <div class="row">
                                <div class="col-xs-6">
                                    
                                </div>
                            </div>
                        <div class="table-responsive m-t-10">
                                <table class="table horizontal_table table-striped" id="SalesReturnList">
                                    <thead>
                                    <tr>
										<th>SR. No</th>
										<th>SR. Date</th>
										<th>Customer</th>
										<th>Amount</th>
										<th></th>
										<th></th>
										<th></th><th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
										@foreach($orders as $order)
										<tr>
											<td>{{ $order->voucher_no }}</td>
											<!--<td>{{ $order->reference_no }}</td>
											<td>{{ $order->description }}</td>-->
											<td>{{ $order->supplier }}</td>
											<td>{{ date('d-m-Y', strtotime($order->voucher_date)) }}</td>
											<td>{{$order->net_amount}}</td>
											<td>
												@can('sr-create')<p><button class="btn btn-primary btn-xs" onClick="location.href='{{ url('sales_return/edit/'.$order->id)}}'"><span class="glyphicon glyphicon-pencil"></span></button></p>@endcan
											</td>
											<td>
												@can('sr-create')<p><button class="btn btn-danger btn-xs delete" onClick="funDelete('{{ $order->id }}')"><span class="glyphicon glyphicon-trash"></span></button></p>@endcan
											</td>
											<td>
												@can('sr-create')<p><a href="{{url('sales_return/print/'.$order->id)}}" class="btn btn-primary btn-xs"><span class="fa fa-fw fa-print"></span></a></p>@endcan
											</td>
										</tr>
										@endforeach
                                    </tbody>
                                </table>
                            </div>
                    </div>
                </div>
            </div>
		</section>
		
		<section class="content">
			<form class="form-horizontal" role="form" method="POST" name="frmQReport" id="frmQReport" action="{{ url('sales_return/search') }}" target="_blank">
			 <input type="hidden" name="_token" value="{{ csrf_token() }}">
			 <div class="row">
			 <div class="col-lg-12">
					<div class="panel panel-info">
						<div class="panel-heading clearfix">
							<h3 class="panel-title pull-left m-t-6">
								<i class="fa fa-fw fa-list-alt"></i> Sales Return Report
							</h3>
						</div>
						<div class="panel-body">
								<div class="row">
									<div class="col-xs-6">
										<span>Date From:</span>
										<input type="text" name="date_from" data-language='en' autocomplete="off" id="date_from" class="form-control input-sm">
										<span>Date To:</span>
										<input type="text" name="date_to" data-language='en' autocomplete="off" id="date_to" class="form-control input-sm">
										<span>Customer</span>
										<select id="select23" class="form-control select2" multiple style="width:100%" name="customer_iid">
											<option value="">Select customer...</option>
											@foreach($cus as $row)
												<option value="{{$row->id}}">{{$row->master_name}}</option>
											@endforeach
										</select>
									</div>
									<div class="col-xs-6">
										<span>Search By:</span>
										<select id="search_type" class="form-control select2" style="width:100%" name="search_type">
											<option value="summary">Summary</option>
											<option value="detail" <?php if($type=='detail') echo 'selected';?>>Detail</option>
										<!--	<option value="customer" <?php if($type=='customer') echo 'selected';?>>Customerwise</option>
											<option value="item" <//?php if($type=='item') echo 'selected';?>>Itemwise</option>
											 <option value="purchase_register">Purchase Register(Cash,Credit)</option>
											<option value="tax_code">Tax Code</option> -->
										</select>
										<span>Job No:</span>
										<select id="select21" class="form-control select2" multiple style="width:100%" name="job_id[]">
											<option value="">Select Job No...</option>
											@foreach($jobs as $job)
												<option value="{{$job['id']}}">{{$job['code']}}</option>
											@endforeach
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
										</div>
										<div class="col-xs-10" style="border:0px solid red;" id="selcate">
										</div>
										<div class="col-xs-10" style="border:0px solid red;" id="selsubcate">
										</div>
										
										<br/>
									    
										<br/>
										
										<!-- <input type="radio" name="isimport" value="1"> Import &nbsp; 
										<input type="radio" name="isimport" value="0"> Local &nbsp; 
										<input type="radio" name="isimport" value="2" checked> Both &nbsp;  -->
										<div class="col-xs-12" align="right"> <button type="submit" class="btn btn-primary">Search</button></div>
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
<script src="{{asset('assets/vendors/bootstrap-multiselect/js/bootstrap-multiselect.js')}}" type="text/javascript"></script>

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
	var con = confirm('Are you sure delete this sales return order?');
	if(con==true) {
		var url = "{{ url('sales_return/delete/') }}";
		location.href = url+'/'+id;
	}
}

$(function() {
		
	var dtInstance = $("#SalesReturnList").DataTable({
		"processing": true,
		"serverSide": true,
		"searching": true,
		"ajax":{
				 "url": "{{ url('sales_return/paging/') }}",
				 "dataType": "json",
				 "type": "POST",
				 "data":{ _token: "{{csrf_token()}}"}
			   },
		"columns": [
		{ "data": "voucher_no" },
		{ "data": "voucher_date" },
		{ "data": "customer" },
		{ "data": "net_amount" },
		@can('sr-edit'){ "data": "edit","bSortable": false },@endcan
		@can('sr-view'){ "data": "viewonly","bSortable": false },@endcan
		@can('sr-print'){ "data": "print","bSortable": false },@endcan
		@can('sr-delete'){ "data": "delete","bSortable": false },@endcan
	]	
	  
	});
	
	$('#select23').on('change', function(e){
			    
			    var cust_id = $('#select23').val();
			    console.log(cust_id);
			     if(cust_id !=''){
			    $.get("{{ url('sales_return/getjob/') }}/" + cust_id, function(data) {
				$('#select21').find('option').remove().end();
				$.each(data, function(key, value) {   
				$('#select21').find('option').end()
				 .append($("<option></option>")
							.attr("value",value.id)
							.text(value.code)); 
							
				});
			});
			    }
			    
			   
			});
});
$(document).ready(function () {
	$('#selcust').toggle();
	$('#selitm').toggle();
	$('#selsubcate').toggle();
	$('#selcate').toggle();
	$('#selgroup').toggle();
	$('#selsubgroup').toggle();
	$("#select23").select2({
        theme: "bootstrap",
        placeholder: "Customer"
    });
    $("#select21").select2({
        theme: "bootstrap",
        placeholder: "Job No:"
    });

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
				url: "{{ url('sales_invoice/getcustomerselect/') }}",
				//type: 'get',
				//data: 'item_id='+item_id+'&unit_id='+unit_id,
				success: function(data) {
				
					$('#selcust').html(data);
					return true;
				}
			}); 
		}else if(vchr=='salesman') {
			
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
				url: "{{ url('sales_invoice/getsalesman/') }}",
				//type: 'get',
				//data: 'item_id='+item_id+'&unit_id='+unit_id,
				success: function(data) {
					$('#selsale').html(data);
					return true;
				}
			}); 
		}else if(vchr=='summarysalesman') {
			
			if( $("#selsalesummary").is(":visible") )
				$("#selsalesummary").toggle();
			
			if( $("#selitm").is(":hidden") )
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
				url: "{{ url('sales_invoice/getsalesman/') }}",
				//type: 'get',
				//data: 'item_id='+item_id+'&unit_id='+unit_id,
				success: function(data) {
					$('#selsalesummary').html(data);
					return true;
				}
			}); 
		}else if(vchr=='area') {
			
			if( $("#selarea").is(":hidden") )
				$("#selarea").toggle();
			
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
				url: "{{ url('sales_invoice/getArea/') }}",
				//type: 'get',
				//data: 'item_id='+item_id+'&unit_id='+unit_id,
				success: function(data) {
					$('#selarea').html(data);
					return true;
				}
			}); 
		}
		 else if(vchr=='item') {
			if( $("#selcust").is(":visible") )
				$("#selcust").toggle();
			
			if( $("#selitm").is(":hidden") )
				$("#selitm").toggle();
			// if( $("#selgroup").is(":hidden") )
			// 	$("#selgroup").toggle();
			// if( $("#selsubgroup").is(":hidden") )
			// 	$("#selsubgroup").toggle();
			// if( $("#selcate").is(":hidden") )
			// 	$("#selcate").toggle();
			// if( $("#selsubcate").is(":hidden") )
			// 	$("#selsubcate").toggle();
				
			$.ajax({
				url: "{{ url('sales_invoice/getitems/') }}",
				success: function(data) {
					$('#selitm').html(data);
				// 	$('#selgroup').html(data);
	            //    $('#selsubgroup').html(data);
				//    $('#selcate').html(data);
	            //    $('#selsubcate').html(data);
					return true;
				}
			}); 

		}  else if(vchr=='group') {
			if( $("#selgroup").is(":hidden") )
				$("#selgroup").toggle();
			
			if( $("#selitm").is(":visible") )
				$("#selitm").toggle();
			
			$.ajax({
				url: "{{ url('purchase_invoice/getgroup/') }}",
				success: function(data) {
					$('#selgroup').html(data);
					return true;
				}
			}); 

		} 
		else if(vchr=='subgroup') {
			if( $("#selsubgroup").is(":hidden") )
				$("#selsubgroup").toggle();
			
			if( $("#selitm").is(":visible") )
				$("#selitm").toggle();
			
			$.ajax({
				url: "{{ url('purchase_invoice/getSubGroup/') }}",
				success: function(data) {
					$('#selsubgroup').html(data);
					return true;
				}
			}); 

		} 

		 else {
			if( $("#selcust").is(":visible") )
				$("#selcust").toggle();
			if( $("#selitm").is(":visible") )
				$("#selitm").toggle();
			if( $("#selsale").is(":visible") )
				$("#selsale").toggle();
			if( $("#selgroup").is(":visible") )
				$("#selgroup").toggle();
			if( $("#selsubgroup").is(":visible") )
				$("#selsubgroup").toggle();
				if( $("#selcate").is(":hidden") )
				$("#selcate").toggle();
			if( $("#selsubcate").is(":hidden") )
				$("#selsubcate").toggle();
		
		}
	});
	
});
 
</script>

@stop
