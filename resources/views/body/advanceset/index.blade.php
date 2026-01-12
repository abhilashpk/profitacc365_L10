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
                 Advance Set
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="">
                        <i class="fa fa-fw fa-retweet"></i> Transaction
                    </a>
                </li>
                <li>
                    <a href="">Advance Set</a>
                </li>
				<li class="active">
                    Advance Set
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
                            <i class="fa fa-fw fa-list-alt"></i> Voucher Entry List
                        </h3>
                        <div class="pull-right">
                             <a href="{{ url('customer_receipt/add') }}" class="btn btn-primary btn-sm">
									<span class="btn-label">
									<i class="glyphicon glyphicon-plus"></i>
								</span> Add New
							</a>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                                <table class="table table-striped" id="tableVouchers">
                                    <thead>
                                    <tr>
                                        <th>Voucher No</th>
										<th>Voucher Type</th>
										<th>Date</th>
										<th>Debit Account</th>
										<th>Description</th>
										<th>Customer Account</th>
										<th>Amount</th>
										<th></th><th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
									@foreach($receipts as $receipts)
                                    <tr>
                                        <td>{{ $receipts->voucher_no }}</td>
										<td><?php echo ($receipts->voucher_type==9)?'CASH':$receipts->voucher_type;?></td>
										<td><?php echo date('d-m-Y', strtotime($receipts->voucher_date)); ?></td>
										<td>{{ $receipts->debiter }}</td>
										<td>{{ $receipts->tr_description }}</td>
										<td>{{ $receipts->creditor }}</td>
										<td>{{ number_format($receipts->amount,2) }}</td>
										<td>
											<p><button class="btn btn-primary btn-xs" onClick="location.href='{{ url('customer_receipt/edit/'.$receipts->id)}}'"><span class="glyphicon glyphicon-pencil"></span></button></p>
										</td>
										<td>
											<p><button class="btn btn-danger btn-xs delete" onClick="funDelete('{{ $receipts->id }}')"><span class="glyphicon glyphicon-trash"></span></button></p>
										</td>
                                    </tr>
									@endforeach
                                    @if (count($receipts) === 0)
									</tbody>
									<tbody><tr class="odd danger"><td valign="top" colspan="7" class="dataTables_empty">No matching records found</td></tr></tbody>
									@endif
                                    </tbody>
                                </table>
                            </div>
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

function funDelete(id) {
	var con = confirm('Are you sure delete this receipt?');
	if(con==true) {
		var url = "{{ url('customer_receipt/delete/') }}";
		location.href = url+'/'+id;
	}
}
</script>

@stop
