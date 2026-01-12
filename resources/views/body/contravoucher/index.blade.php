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
                 Contra Voucher
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="">
                        <i class="fa fa-fw fa-retweet"></i> Transaction
                    </a>
                </li>
                <li>
                    <a href="">Contra Voucher</a>
                </li>
				<li class="active">
                    Contra Voucher
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
                            <i class="fa fa-fw fa-list-alt"></i> Contra Voucher
                        </h3>
                        <div class="pull-right">
                             <a href="{{ url('contra_voucher/add') }}" class="btn btn-primary btn-sm">
									<span class="btn-label">
									<i class="glyphicon glyphicon-plus"></i>
								</span> Add New
							</a>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                                <table class="table table-striped" id="tableArea">
                                    <thead>
                                    <tr>
                                        <th>Voucher No</th>
										<th>Voucher Date</th>
                                        <th>Voucher Type</th>
                                        <th>Description</th>
                                        <th>Reference</th>
                                        <th>Amount</th>
										<th></th>
										<th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
									@foreach($datas as $data)
                                    <tr>
                                        <td>{{ $data->voucher_no }}</td>
										<td>{{ date('d-m-Y', strtotime($data->voucher_date)) }}</td>
                                        <td>{{ ($data->voucher_type==0)?'Withdraw':'Deposit' }}</td>
                                        <td>{{$data->description}}</td>
                                        <td>{{$data->reference}}</td>
                                        <td>{{ $data->amount }}</td>
										<td>
											<p>
												<button class="btn btn-primary btn-xs" onClick="location.href='{{ url('contra_voucher/edit/'.$data->id) }}'">
												<span class="glyphicon glyphicon-pencil"></span></button>
											</p>
										</td>
										<td>
											<p>
												<button class="btn btn-danger btn-xs delete" onClick="funDelete('{{ $data->id }}')"><span
															class="glyphicon glyphicon-trash"></span></button>
											</p>
										</td>
										<td>
										<p><a href="{{url('contra_voucher/printgrp/'.$data->id.'/'.$prints[0]->id)}}" target='_blank'   class="btn btn-primary btn-xs"><span class="fa fa-fw fa-print"></span></a></p>
										</td>
                                    </tr>
									@endforeach
                                    @if (count($datas) === 0)
									</tbody>
									<tbody><tr class="odd danger"><td valign="top" colspan="4" class="dataTables_empty">No matching records found</td></tr></tbody>
									@endif
                                    </tbody>
                                </table>
                            </div>
                    </div>
                </div>
            </div>
        </div>
        
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
	var con = confirm('Are you sure delete this voucher?');
	if(con==true) {
		var url = "{{ url('contra_voucher/delete/') }}";
	 location.href = url+'/'+id;
	}
}
</script>

@stop
