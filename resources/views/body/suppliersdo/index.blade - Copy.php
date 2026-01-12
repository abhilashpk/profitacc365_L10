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
	<link href="{{asset('assets/vendors/bootstrap-multiselect/css/bootstrap-multiselect.css')}}" rel="stylesheet" type="text/css">
		
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <!--section starts-->
            <h1>
                Goods Receipt Note
            </h1>
            <ol class="breadcrumb">
                <li>
                      <i class="fa fa-fw fa-shield"></i> Inventory
                </li>
				<li>
                    <a href="#">Goods Receipt Note</a>
                </li>
            </ol>
        </section>
        <!--section ends-->
		@if(Session::has('message'))
		<div class="alert alert-success">
			<p>{{ Session::get('message') }}</p>
		</div>
		@endif
        
		<section class="content">
            <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-info">
                    <div class="panel-heading clearfix">
                        <h3 class="panel-title pull-left m-t-6">
                            <i class="fa fa-fw fa-list-alt"></i> Goods Receipt Note
                        </h3>
                        <div class="pull-right">
                             <a href="{{ url('suppliers_do/add') }}" class="btn btn-primary btn-sm">
									<span class="btn-label">
									<i class="glyphicon glyphicon-plus"></i>
								</span> Add New
							</a>
                        </div>
                    </div>
                    <div class="panel-body">
						 
                        <div class="table-responsive m-t-10">
                                <table class="table horizontal_table table-striped" id="tablePorders">
                                    <thead>
                                    <tr>
										<th>GRN. No</th>
										<th>GNR. Date</th>
										<th>Supplier</th>
										<th>Amount</th>
										<th></th><th></th><th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
										@foreach($orders as $order)
										<tr>
											<td>{{ $order->voucher_no }}</td>
											<td>{{ date('d-m-Y', strtotime($order->voucher_date)) }}</td>
											<td>{{ $order->supplier }}</td>
											<td>{{ number_format($order->net_total,2) }}</td>
											<td>
												<p><button class="btn btn-primary btn-xs" onClick="location.href='{{ url('suppliers_do/edit/'.$order->id)}}'"><span class="glyphicon glyphicon-pencil"></span></button></p>
											</td>
											<td>
												<p><button class="btn btn-danger btn-xs delete" onClick="funDelete('{{ $order->id }}')"><span class="glyphicon glyphicon-trash"></span></button></p>
											</td>
											<td>
												<div class='btn-group drop_btn' role='group'>
														<button type='button' class='btn btn-primary btn-xs dropdown-toggle m-r-50'
																id='exampleIconDropdown1' data-toggle='dropdown' aria-expanded='false'>
															<i class='fa fa-fw fa-print' aria-hidden='true'></i><span class='caret'></span>
														</button>
														<ul style='min-width:100px !important;' class='dropdown-menu' aria-labelledby='exampleIconDropdown1' role='menu'>
															<?php foreach($prints as $doc) { ?>
															<li role='presentation'><a href="{{ url('suppliers_do/print/'.$order->id.'/'.$doc->id)}}" target='_blank' role='menuitem'>{{$doc->name}}</a></li>
															<?php } ?>
														</ul>
													</div>
												
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
			<form class="form-horizontal" role="form" method="POST" name="frmQReport" id="frmQReport" target="_blank" action="{{ url('suppliers_do/search') }}">
			 <input type="hidden" name="_token" value="{{ csrf_token() }}">
			 <input type="hidden" name="department_id" id="department_id">
			 <div class="row">
			 <div class="col-lg-12">
					<div class="panel panel-info">
						<div class="panel-heading clearfix">
							<h3 class="panel-title pull-left m-t-6">
								<i class="fa fa-fw fa-list-alt"></i> Goods Receipt Note Report
							</h3>
						</div>
						<div class="panel-body">
								<div class="row">
									<div class="col-xs-6">
										<span>Date From:</span>
										<input type="text" name="date_from" data-language='en' autocomplete="off" id="date_from" class="form-control">
										<span>Date To:</span>
										<input type="text" name="date_to" data-language='en' autocomplete="off" id="date_to" class="form-control">
									</div>
									<div class="col-xs-6">
										<span>Search By:</span>
										<select id="search_type" class="form-control select2" style="width:100%" name="search_type">
											<option value="summary">Summary</option>
											<option value="detail" <?php if($type=='detail') echo 'selected';?>>Detail</option>
											<!-- <option value="customer" <?php //if($type=='customer') echo 'selected';?>>Customerwise</option>
											<option value="item" <?php //if($type=='item') echo 'selected';?>>Itemwise</option> -->
											<!-- <option value="purchase_register">Purchase Register(Cash,Credit)</option>
											<option value="tax_code">Tax Code</option> -->
										</select>
										
										
										<br/>
										<div class="col-xs-4" style="border:0px solid red;">
										<span>Supplier:</span> <br/>
                                        <select id="multiselect2" multiple="multiple" class="form-control" name="supplier_id[]">
                                         <?php foreach($supplier as $row) { ?>
                                     <option value="<?php echo $row->id;?>" <?php //if($row->id==$locid) echo 'selected';?>><?php echo $row->master_name;?></option>
                                        <?php } ?>
                                     </select>
									 </div>
									
										<br>
										<span></span><br/>
								
										<button type="submit" class="btn btn-primary">Search</button>
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

<script>

function funDelete(id) {
	var con = confirm('Are you sure delete this goods receipt note?');
	if(con==true) {
		var url = "{{ url('suppliers_do/delete/') }}";
		location.href = url+'/'+id;
	}
}
$(document).ready(function () {
    ///$('#selcust').toggle();
    //$('#selitm').toggle();
    
    $("#multiselect2").multiselect({
        enableFiltering: true,
        includeSelectAllOption: true,
        maxHeight: 300,
        dropUp: true
    });

});

$(function() {
            
	var dtInstance = $("#tablePorders").DataTable({
		"lengthMenu": [10, 25, 50, "ALL"],
		bLengthChange: false,
		mark: true,
		"bSort" : false,
		"aoColumns": [null,null,null,null,{ "bSortable": false },{ "bSortable": false },{ "bSortable": false } ],
		//"scrollX": true,
	});
	
});
</script>

@stop
