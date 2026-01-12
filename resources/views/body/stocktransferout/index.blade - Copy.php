@extends('layouts/default')

    {{-- Page title --}}
    @section('title')
         
        @parent
    @stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
	<link href="{{asset('assets/vendors/bootstrap-multiselect/css/bootstrap-multiselect.css')}}" rel="stylesheet" type="text/css">
	
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/iCheck/css/all.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrap-fileinput/css/fileinput.min.css')}}" media="all" />
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/formelements.css')}}">

	<link rel="stylesheet" href="{{asset('assets/vendors/datetime/css/jquery.datetimepicker.css')}}">
    <link href="{{asset('assets/vendors/airdatepicker/css/datepicker.min.css')}}" rel="stylesheet" type="text/css">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/datepicker.css')}}">  
        <!--end of page level css-->
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <!--section starts-->
            <h1>
                Stock Transfer out
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="">
                        <i class="fa fa-fw fa-briefcase"></i> Inventory
                    </a>
                </li>
                <li>
                    <a href="#">Stock Transfer</a>
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
                            <i class="fa fa-fw fa-list-alt"></i> Stock Transfer List
                        </h3>
                        <div class="pull-right">
                             <a href="{{ url('stock_transferout/add') }}" class="btn btn-primary btn-sm">
									<span class="btn-label">
									<i class="glyphicon glyphicon-plus"></i>
								</span> Add New
							</a>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                                <table class="table table-striped" id="tableLocation">
                                    <thead>
                                    <tr>
										<th>STO. No</th>
										<th>Date</th>
										<th>Quantity</th>
										<th>Amount</th>
										<th></th>
										<th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
									@foreach($stocktrans as $stock)
                                    <tr>
                                        <td>{{ $stock->voucher_no }}</td>
										<td>{{ date('d-m-Y',strtotime($stock->voucher_date)) }}</td>
										<td>{{ $stock->total_qty }}</td>
										<td>{{ $stock->net_total }}</td>
										<td>
											<p>
												<button class="btn btn-primary btn-xs" onClick="location.href='{{ url('stock_transferout/edit/'.$stock->id) }}'">
												<span class="glyphicon glyphicon-pencil"></span></button>
											</p>
										</td>
										<td>
											<p>
												<button class="btn btn-danger btn-xs delete" onClick="funDelete('{{ $stock->id }}','{{ $stock->is_mfg }}')"><span
															class="glyphicon glyphicon-trash"></span></button>
											</p>
										</td>
										<td>
											<p><a href="{{url('stock_transferout/print/'.$stock->id)}}" class="btn btn-primary btn-xs"><span class="fa fa-fw fa-print"></span></a></p>
										</td>
                                    </tr>
									@endforeach
                                   
                                    </tbody>
                                </table>
                            </div>
                    </div>
                </div>
            </div>
        </div>
        <section class="content">
			<form class="form-horizontal" role="form" method="POST" name="frmQReport" id="frmQReport" target="_blank" action="{{ url('stock_transferout/search') }}">
			 <input type="hidden" name="_token" value="{{ csrf_token() }}">
			 <input type="hidden" name="department_id" id="department_id">
			 <div class="row">
			 <div class="col-lg-12">
					<div class="panel panel-info">
						<div class="panel-heading clearfix">
							<h3 class="panel-title pull-left m-t-6">
								<i class="fa fa-fw fa-list-alt"></i> Sales TransferOut Report
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
										
										
									
										
									 
									
									
										<div class="col-xs-4" id="item" style="border:0px solid red;">
												<span>Item:</span><br/>
											<select id="multiselect7" multiple="multiple" class="form-control" name="item_id[]">
												<?php foreach($item as $row) { ?>
												<option value="<?php echo $row->id;?>"><?php echo $row->description;?></option>
												<?php } ?>
											</select>
										</div> 
										<div class="col-xs-4" id="group" style="border:0px solid red;">
											<span>Group:</span><br/>
											<select id="multiselect3" multiple="multiple" class="form-control" name="group_id[]">
												<?php foreach($group as $row) { ?>
												<option value="<?php echo $row->id;?>"><?php echo $row->group_name;?></option>
												<?php } ?>
											</select>
										</div>
										
										<div class="col-xs-3" id="subgroup" style="border:0px solid red;">
											<span>Subgroup:</span><br/>
											<select id="multiselect4" multiple="multiple" class="form-control" name="subgroup_id[]">
												<?php foreach($subgroup as $row) { ?>
												<option value="<?php echo $row->id;?>"><?php echo $row->group_name;?></option>
												<?php } ?>
											</select>
										</div>
										
								
										<div class="col-xs-4" id="category" style="border:0px solid red;">
											<span>Category:</span><br/>
											<select id="multiselect5" multiple="multiple" class="form-control" name="category_id[]">
												<?php foreach($category as $row) { ?>
												<option value="<?php echo $row->id;?>"><?php echo $row->category_name;?></option>
												<?php } ?>
											</select>
										</div>
										
										<div class="col-xs-4" id="subcategory" style="border:0px solid red;">
											<span>Subcategory:</span><br/>
											<select id="multiselect6" multiple="multiple" class="form-control" name="subcategory_id[]">
												<?php foreach($subcategory as $row) { ?>
												<option value="<?php echo $row->id;?>"><?php echo $row->category_name;?></option>
												<?php } ?>
											</select>
										</div>
										
										
										<!-- <input type="radio" name="isimport" value="1"> Import &nbsp; 
										<input type="radio" name="isimport" value="0"> Local &nbsp; 
										<input type="radio" name="isimport" value="2" checked> Both &nbsp;  -->
										<button type="submit" class="btn btn-primary">Search</button>
									</div>
								</div>
						</div>
					</div>
			</div>
			</div>
			</form>
		</section>
        <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title custom_align" id="Heading5">Delete Group</h4>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <span class="glyphicon glyphicon-info-sign"></span>&nbsp; Are you sure delete this group?
                        </div>
                    </div>
                    <div class="modal-footer ">
                        <!--<button type="button" class="btn btn-danger" data-dismiss="modal" value="Yes">-->
						<button class="btn btn-danger" value="Yes" id="btndelete">
                            <span class="glyphicon glyphicon-ok-sign"></span> Yes
                        </button>
						
                        <button type="button" class="btn btn-success" data-dismiss="modal" value="No">
                            <span class="glyphicon glyphicon-remove"></span> No
                        </button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
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
	<script src="{{asset('assets/vendors/bootstrap-multiselect/js/bootstrap-multiselect.js')}}" type="text/javascript"></script>

<script type="text/javascript" src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/iCheck/js/icheck.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-fileinput/js/fileinput.min.js')}}"></script>

<script src="{{asset('assets/vendors/moment/js/moment.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/datetime/js/jquery.datetimepicker.full.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.en.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/custom_js/advanceddate_pickers.js')}}"></script>

<!-- end of page level js -->
<script>

$('#date_from').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy' } );
$('#date_to').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy' } );


function funDelete(id,m) {
	if(m=='0') {
		var con = confirm('Are you sure delete this stock transfer?');
		if(con==true) {
			var url = "{{ url('stock_transferout/delete/') }}";
			location.href = url+'/'+id;
		}
	} else 
		alert('This transaction is generated from Manufacture, you cannot edit or delete.');
	
}
$(function() {	
	$('#group').hide(); 
	$('#subgroup').hide(); 
	$('#category').hide(); 
	$('#subcategory').hide(); 
	$('#item').hide(); 
	$(document).on('change', '#search_type', function(e) { 
	   if($('#search_type option:selected').val()=='detail')
			{
			$('#group').show(); 
	$('#subgroup').show(); 
	$('#category').show(); 
	$('#subcategory').show();
	$('#item').show(); 
}
		else
		{
		$('#group').hide(); 
	     $('#subgroup').hide(); 
	$('#category').hide(); 
	$('#subcategory').hide();
	$('#item').hide(); 
} 
    });
});
$(document).ready(function () {
    ///$('#selcust').toggle();
    //$('#selitm').toggle();
    
    $("#multiselect2").multiselect({
        enableFiltering: true,
        includeSelectAllOption: true,
        maxHeight: 300,
        dropUp: true
    });
	$("#multiselect3").multiselect({
        enableFiltering: true,
        includeSelectAllOption: true,
        maxHeight: 300,
        dropUp: true
    });

    $("#multiselect4").multiselect({
        enableFiltering: true,
        includeSelectAllOption: true,
        maxHeight: 300,
        dropUp: true
    });
    $("#multiselect5").multiselect({
        enableFiltering: true,
        includeSelectAllOption: true,
        maxHeight: 300,
        dropUp: true
    });
    $("#multiselect6").multiselect({
        enableFiltering: true,
        includeSelectAllOption: true,
        maxHeight: 300,
        dropUp: true
    });
	$("#multiselect7").multiselect({
        enableFiltering: true,
        includeSelectAllOption: true,
        maxHeight: 300,
        dropUp: true
    });
});
</script>

@stop
