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
                Item Master
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="fa fa-fw fa-home"></i> Inventory
                    </a>
                </li>
                <li>
                    <a href="#">Item Master</a>
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
                        <div class="panel-heading clearfix  ">
                            <div class="panel-title pull-left">
                                <i class="fa fa-fw fa-list-alt"></i> Item List
                            </div>
                            <div class="pull-right">
							@can('item-create')
                             <a href="{{ url('itemmaster/add') }}" class="btn btn-primary btn-sm">
									<span class="btn-label">
									<i class="glyphicon glyphicon-plus"></i>
								</span> Add New
							</a>
							@endcan
							 &nbsp;
							 @can('item-create')
							 <a href="" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#item_modal">
									<span class="btn-label">
									<i class="glyphicon glyphicon-plus"></i>
								</span> Quick Add
							</a>
							@endcan
							&nbsp;
							
                        </div>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="posts">
                                    <thead>
                                    <tr>
                                  <!--<th>Item Code</th>
										<th>Description</th>
										<th>Qty. in Hand</th>
										@can('item-cost-view')<th>Cost Avg.</th>@endcan
										@can('item-cost-view')<th>Last P. Cost</th>@endcan
										<th>Net Cost</th>
										<th>Rcvd. Qty.</th>
										<th>Issued Qty.</th>
										<th></th>
										<th></th>-->
										<th></th>
										<?php foreach($cols as $col) { ?>
											<th><?php echo $formdata[$col.'_fn'];?></th>
										<?php } ?>
										<th></th>
										<th></th>
                                    </tr>
                                    </thead>
                                    
                                </table>
                            </div>
                        </div>
                    </div>
            </div>
			
			<div id="item_modal" class="modal fade animated" role="dialog">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal">&times;</button>
									<h4 class="modal-title">Item Quick Add</h4>
								</div>
								<div class="modal-body" id="supplierData">
									<div class="panel panel-success filterable" id="newSupplierFrm">
									<div class="panel-heading">
										<h3 class="panel-title">
											<i class="fa fa-fw fa-columns"></i> New Item
										</h3>
										
									</div>
									<div class="panel-body">
						
										<div class="col-xs-10">
											<div id="itemDtls">
											<form class="form-horizontal" role="form" method="POST" name="frmItem" id="frmItem">
											<input type="hidden" name="_token" value="{{ csrf_token() }}">
												
												<div class="form-group">
													<label for="input-text" class="col-sm-5 control-label">Item Code</label>
													<div class="col-sm-7">
														<input type="text" class="form-control" id="item_code" name="item_code" placeholder="Item Code">
													</div>
												</div>
												
												<div class="form-group">
													<label for="input-text" class="col-sm-5 control-label">Item Decription</label>
													<div class="col-sm-7">
														<input type="text" class="form-control" id="description" name="description" placeholder="Item Description">
													</div>
												</div>
												
												<div class="form-group">
													<label for="input-text" class="col-sm-5 control-label">Item Class</label>
													<div class="col-sm-7">
														<select id="item_class" class="form-control select2" style="width:100%" name="item_class">
															<option value="1">Stock</option>
															<option value="2">Service</option>
														</select>
													</div>
												</div>
												
												<div class="form-group">
													<label for="input-text" class="col-sm-5 control-label">Unit</label>
													<div class="col-sm-7">
														<select id="unit" class="form-control select2 itemunit" style="width:100%" name="unit">
															@foreach ($units as $unit)
															<option value="{{ $unit['id'] }}">{{ $unit['unit_name'] }}</option>
															@endforeach
														</select>
													</div>
												</div>
												
												<div class="form-group">
													<label for="input-text" class="col-sm-5 control-label">VAT%</label>
													<div class="col-sm-7">
														<select id="vat" class="form-control select2 itemunit" style="width:100%" name="vat">
															@foreach ($vats as $vat)
															<option value="{{ $vat['percentage'] }}">{{ $vat['name'] }}</option>
															@endforeach
														</select>
													</div>
												</div>
												
												<div class="form-group">
													<label for="input-text" class="col-sm-5 control-label"></label>
													<div class="col-sm-7">
														<button type="button" class="btn btn-primary" id="createItm">Create</button>
													</div>
												</div>
											 </form>
											</div>
											
											<div id="sucessmsgItm"><br/>
												<div class="alert alert-success">
													<p>
														Item created successfully. 
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
	$('#sucessmsgItm').toggle();
	
	$(document).on('click', '.btn-default', function(e) { 
		location.href = "{{ url('itemmaster') }}";
	});
	
	$(document).on('click', '.close', function(e) { 
		location.href = "{{ url('itemmaster') }}";
	});
	
	$('#createItm').on('click', function(e){
		
		var ic = $('#frmItem #item_code').val();
		var dc = $('#frmItem #description').val();
		var cl = $('#frmItem #item_class option:selected').val();
		var ut = $('#frmItem #unit option:selected').val();
		var vt = $('#frmItem #vat option:selected').val();
		var un = $("#frmItem #unit option:selected").text();
		if(ic=="") {
			alert('Item code is required!');
			return false;
		} else if(dc=="") {
			alert('Item description is required!');
		} else {		
			$('#itemDtls').toggle();
			
			$.ajax({
				url: "{{ url('itemmaster/ajax_create/') }}",
				type: 'get',
				data: 'item_code='+ic+'&description='+dc+'&class_id='+cl+'&unit='+ut+'&vat='+vt+'&uname='+un,
				success: function(data) { console.log(data);
					if(data > 0) {
						$('#sucessmsgItm').toggle();
					} else if(data == 0) {
						$('#itemDtls').toggle();
						alert('Item code/name already exist!');
						return false;
					} else {
						$('#itemDtls').toggle();
						alert('Something went wrong, Item failed to add!');
						return false;
					}
				}
			})
		}
	});
	
	$(function() {
            
            var dtInstance = $("#posts").DataTable({
				"processing": true,
				"serverSide": true,
				"ajax":{
						 "url": "{{ url('itemmaster/paging/') }}",
						 "dataType": "json",
						 "type": "POST",
						 "data":{ _token: "{{csrf_token()}}"}
					   },
				/* "scrollY":        500,
				"deferRender":    true,
				"scroller":       true, 
				"columns": [
                { "data": "item_code" },
                { "data": "description" },
                { "data": "quantity" },
                @can('item-cost-view'){ "data": "cost_avg" },@endcan
                @can('item-cost-view'){ "data": "last_purchase_cost" },@endcan
				{ "data": "other_cost" },
				{ "data": "issued_qty" },
				{ "data": "edit","bSortable": false },
				{ "data": "delete","bSortable": false }
            ]	*/
			"columns": [
				{ "data": "opt","bSortable": false },
				<?php foreach($cols as $col) { ?>
                { "data": "{{$col}}" },
                <?php } ?>
				{ "data": "edit","bSortable": false },
				{ "data": "delete","bSortable": false }
				]
              
            });
     });
		
});	

function funDelete(id) {
	var con = confirm('Are you sure delete this item?');
	if(con==true) {
		var url = "{{ url('itemmaster/delete/') }}";
	 location.href = url+'/'+id;
	}
}
</script>
@stop
