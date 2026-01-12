@extends('layouts/default')

    {{-- Page title --}}
    @section('title')
         
        @parent
    @stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
	<link rel="stylesheet" href="{{asset('assets/vendors/datetime/css/jquery.datetimepicker.css')}}">
   <link rel="stylesheet" href="{{asset('assets/vendors/datetime/css/jquery.datetimepicker.css')}}">
    <link href="{{asset('assets/vendors/airdatepicker/css/datepicker.min.css')}}" rel="stylesheet" type="text/css">
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
                Cash In Hand
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="glyphicon glyphicon-folder-close"></i> Reports
                    </a>
                </li>
                <li>
                    <a href="#">Cash In Hand</a>
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
                                <i class="fa fa-fw fa-columns"></i> Cash In Hand
                            </h3>
                        </div>
                        <div class="panel-body">
							<form class="form-horizontal" role="form" method="POST" name="frmCashInhand" id="frmCashInhand" target="_blank" action="{{ url('cash_inhand/search') }}">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								
								<div class="row">
									<div class="col-xs-6">
										<span>Date From:</span>
										<input type="text" name="from_date" id="from_date" required class="form-control" value="{{date('d-m-Y')}}" data-language='en' autocomplete="off">
												
										<span>Date To:</span>
										<input type="text" name="to_date" data-language='en' id="to_date" class="form-control" autocomplete="off">
										
									
										
									</div>
									<div class="col-xs-6">
										<span>Search By:</span>
										<select id="search_type" class="form-control select2" style="width:100%" name="search_type">
											<option value="Summary">Summary</option>
											<option value="Details">Details</option>
										</select>
										<span></span><br/>
										<input type="checkbox" checked class="bank" name="bank" value="1"> Bank
										<input type="checkbox" checked class="cash" name="cash" value="1"> Cash
										&nbsp; <input type="checkbox" checked name="PDCR" value="1"> PDCR
										&nbsp; <input type="checkbox" checked name="PDCI" value="1"> PDCI
										
										&nbsp; <button type="submit" class="btn btn-primary">Search</button>
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

//$('#from_date').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy' } );
$('#to_date').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy' } );

$(function() {	
	$('#accounts').hide(); $('#groups').hide(); 
	
	$(document).on('change', '#search_type', function(e) { 
	
	  if($('#search_type option:selected').val()=='taged_summary') {
			//$('#accounts').show(); $('#groups').hide(); //MY24
			$('#groups').show(); $('#accounts').hide();
		} else if($('#search_type option:selected').val()=='groupwise' || $('#search_type option:selected').val()=='groupwise_bal' || $('#search_type option:selected').val()=='closing_groupwise' || $('#search_type option:selected').val()=='new_format' || $('#search_type option:selected').val()=='closing_groupwise_bal' || $('#search_type option:selected').val()=='opening_group_taged') {
			//} else if($('#search_type option:selected').val()=='group_taged' || $('#search_type option:selected').val()=='opening_group_taged') {
			$('#groups').show(); $('#accounts').hide();
	   } else {
			$('#accounts').hide(); $('#groups').hide(); 
	   }
	   
    });
	
	//var items = [];
	$(document).on('click', '.chk-account', function(e) { 
		var items = [];
		$("input[name='account[]']:checked").each(function(){items.push($(this).val());});
		//console.log(items);
		$('#accounts_arr').val(items);
	});
	
	$(document).on('click', '.opt-group', function(e) { 
	  // $('#group_id').val(this.value);
	   
	   /*  let table=$('#tableAcgroup').DataTable();
		let arr= [];
		let checkedvalues = table.$('input:checked').each(function () {
			arr.push($(this).val())
		});
		arr=arr.toString();
		console.log('add '+arr); */
		
		var id = [];
		let table = $('#tableAcgroup').DataTable();
		$('#tableAcgroup').find("input[name='group[]']:checked").each(function(){
		//table.$("input:checked").each(function(){
			id.push($(this).val());
		}); 
		$('#group_id').val(id);
			
    });
});
</script>
@stop
