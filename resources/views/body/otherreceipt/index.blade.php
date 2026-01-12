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
        <!--end of page level css-->
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <!--section starts-->
            <h1>
                 Other Receipt
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="">
                        <i class="fa fa-fw fa-retweet"></i> Transaction
                    </a>
                </li>
                <li>
                    <a href="">Vouchers Entry</a>
                </li>
				<li class="active">
                    Other Receipt
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
                             <a href="{{ url('other_receipt/add') }}" class="btn btn-primary btn-sm">
									<span class="btn-label">
									<i class="glyphicon glyphicon-plus"></i>
								</span> Add New
							</a>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                                <table class="table table-striped" id="tableBank">
                                    <thead>
                                    <tr>
                                        <th>Voucher No</th>
										<th>Voucher Type</th>
										<th>Date</th>
										<th>Debit Account</th>
										<th>Description</th>
										<th>Customer Account</th>
										<th>Amount</th>
                                    </tr>
                                    </thead>
                                    <tbody>
									@foreach($receipts as $receipts)
                                    <tr>
                                        <td>{{ $receipts->voucher_no }}</td>
										<td>{{ $receipts->voucher_type }}</td>
										<td>{{ date('d-m-Y',strtotime($receipts->voucher_date)) }}</td>
										<td>{{ $receipts->debiter }}</td>
										<td>{{ $receipts->description }}</td>
										<td>{{ $receipts->creditor }}</td>
										<td>{{ number_format($receipts->amount,2) }}</td>
										
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
    <!-- begining of page level js -->
<script type="text/javascript" src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/iCheck/js/icheck.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-fileinput/js/fileinput.min.js')}}"></script>
<!-- end of page level js -->
<script>

function funDelete(id) {
	var con = confirm('Are you sure delete this bank?');
	if(con==true) {
		var url = "{{ url('bank/delete/') }}";
	 location.href = url+'/'+id;
	}
}
</script>

@stop
