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
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/datepicker.css')}}">
	
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
                Transaction List
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="glyphicon glyphicon-folder-close"></i> Reports
                    </a>
                </li>
                <li>
                    <a href="#">Transaction List</a>
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
                                <i class="fa fa-fw fa-columns"></i> Transaction List
                            </h3>
                        </div>
                        <div class="panel-body">
							<form class="form-horizontal" role="form" method="POST" name="frmTransaction" id="frmTransaction" target="_blank" action="{{ url('transaction_list/search') }}">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								<div class="row">
									<div class="col-xs-6">
										<span>Date From:</span>
										<input type="text" name="date_from" data-language='en' id="date_from" class="form-control input-sm" autocomplete="off">
										<span>Date To:</span>
										<input type="text" name="date_to" data-language='en' id="date_to" class="form-control input-sm" autocomplete="off">
										
									</div>
									<div class="col-xs-6">
										<span>Search By:</span>
										<select id="search_type" class="form-control select2" style="width:100%" name="search_type">
                                            <option value="">Select Search By</option>
                                            @if($modsdo==1)<option value="SupplierDO">Supplier DO</option>@endif
                                            <option value="Purchase">Purchase</option>
                                            <option value="PurchaseReturn">Purchase Return</option>
                                            @if($moddo==1)<option value="CustomerDO">Customer DO</option>@endif
                                            <option value="Sales">Sales</option>
                                            <option value="SalesReturn">Sales Return</option>
                                            <option value="GoodsIssued">Goods Issued</option>
                                            <option value="GoodsReturn">Goods Return</option>
                                            <option value="TransferIn">Stock Transfer In</option>
                                            <option value="TransferOut">Stock Transfer Out</option>
                                            <option value="Manufacture">Manufacture Voucher</option>
										</select>
										<br/>
                                      <!--    <span>Search By:</span>
										<select id="search_by" class="form-control select2" style="width:100%" name="search_by">
                                            <option value="">--Select-- </option>
                                            <option value="Group">Group</option>
                                            <option value="Subgroup">Sub Group</option>
                                            <option value="Category">Category</option>
                                            <option value="Subcategory">Sub Category</option>
										</select>
										
                                       <span>Sort By:</span>
                                        <select id="sort" class="form-control select2" style="width:100%" name="sort_type">
                                            <option value="">Select Sort By</option>
                                            
                                        </select>
                                        <br/>-->
                                         <div class="col-xs-12" align="right"> <button type="submit" class="btn btn-primary">Search</button></div>

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

<script src="{{asset('assets/vendors/datetime/js/jquery.datetimepicker.full.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.en.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/custom_js/advanceddate_pickers.js')}}"></script>
<!-- end of page level js -->

<script>

$('#date_from').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy' } );
$('#date_to').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy' } );

$(document).ready(function () {
    $("#search_type").change(function () {
        var val = $(this).val();
        if (val == "Purchase") {
            $("#sort").html("<option value='salesman'>Salesman</option><option value='area'>Area</option><option value='item'>item</option><option value='group'>Group</option><option value='subgroup'>Subgroup</option><option value='category'>Category</option><option value='subcategory'>Subcategory</option><option value='supplier'>Supplier</option>");
        } else if (val == "Sales") {
            $("#sort").html("<option value='salesman' >Salesman</option><option value='area'>Area</option><option value='item'>item</option><option value='group'>Group</option><option value='subgroup'>Subgroup</option><option value='category'>Category</option><option value='subcategory'>Subcategory</option><option value='customer'>Customer</option>");
        } else if (val == "PurchaseReturn") {
            $("#sort").html("<option value='salesman'>Salesman</option><option value='area'>Area</option><option value='item'>item</option><option value='group'>Group</option><option value='subgroup'>Subgroup</option><option value='category'>Category</option><option value='subcategory'>Subcategory</option><option value='supplier'>Supplier</option>");
        } else if (val == "SalesReturn") {
            $("#sort").html("<option value='salesman'>Salesman</option><option value='area'>Area</option><option value='item'>item</option><option value='group'>Group</option><option value='subgroup'>Subgroup</option><option value='category'>Category</option><option value='subcategory'>Subcategory</option><option value='customer'>Customer</option>");
        }
    });
});


</script>
@stop
