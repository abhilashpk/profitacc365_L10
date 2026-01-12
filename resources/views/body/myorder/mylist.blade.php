@extends('layouts/myorder')

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
                Orders <div class="pull-right"> <a href="index"><h4><b>{{Session::get('driver')}}</b></h4></a></div>
            </h1>
            <ol class="breadcrumb">
                <li>
					<b><div class="pull-right"> <a href="{{ url('myorder/logout') }}">Logout</a></div></b>
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
			{{--<div class="col-xs-10">
				<input type="text" class="form-control" id="search" name="search" placeholder="Search"></div>
				<div class="col-xs-2">
				<button type="button" class="btn btn-success btn" id="btnSearch">
					<span class="glyphicon fa fa-fw fa-search" aria-hidden="true"></span>
			</button></div> --}}
											
				<hr/>
				<div class="col-md-4">
				<div id="resList">
				@foreach($orders as $row)
                    <div class="panel panel-danger">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-align-justify"></i> Order No: {{$row->voucher_no}}
                            </h3>
                            <span class="pull-right">
                                   
                                </span>
                        </div>
                        <div class="panel-body">
                            <div class="box-body">
                                <dl class="dl-vertical">
									<dd>
									   Customer Name: <b>{{$row->master_name}}</b>
									</dd>
									
									<dd>
									   Delivery details: <b>{{$row->remarks}}</b>
									</dd>
									
									<dd><br/>
						Status: <select id="sts_{{$row->id}}" class="form-control select2 ordStatus" name="status">
									<option value="">Select</option>
									<option value="1">Delivered</option>
								</select>
					</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
				@endforeach	
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
$(document).ready(function () { 
	$('.ordResn').hide();
});

$(document).on('change', '.ordStatus', function(e) {  
	
	var res = this.id.split('_');
	var id = res[1];
	var sts = $('#sts_'+id).val();
	
	if(sts==5 || sts==3 || sts==1.5) {
		$('#rsn_'+id).show();
	} else {
		$('#rsn_'+id).hide();
		$.ajax({
			url: "{{ url('myorder/set_status/') }}",
			type: 'get',
			data: 'sts='+sts+'&id='+id+'&rsn=',
			success: function(data) {
				alert('Status updated successfully.')
				
				return true;
			}
		})
	}
});

$(document).on('change', '.ordReason', function(e) {  
	
	var res = this.id.split('_');
	var id = res[1];
	var sts = $('#sts_'+id).val();
	var rsn = $('#rsn_'+id+' option:selected').val();
	
	$.ajax({
		url: "{{ url('myorder/set_status/') }}",
		type: 'get',
		data: 'sts='+sts+'&id='+id+'&rsn='+rsn,
		success: function(data) {
			alert('Status updated successfully.')
			
			return true;
		}
	})
	
});

$(document).on('keyup', '#search', function(e) {   console.log(this.value);

	$.ajax({
		url: "{{ url('myorder/ajax_search/') }}",
		type: 'get',
		data: 'key='+this.value,
		success: function(data) {
			$('#resList').html(data);
		}
	})
});

$(document).on('click', '#btnSearch', function(e) {   
	var key = $('#search').val();
	$.ajax({
		url: "{{ url('myorder/ajax_search/') }}",
		type: 'get',
		data: 'key='+key,
		success: function(data) {
			$('#resList').html(data);
		}
	})
});

function funDelete(id) {
	var con = confirm('Are you sure delete this driver?');
	if(con==true) {
		var url = "{{ url('driver/delete/') }}";
	 location.href = url+'/'+id;
	}
}
</script>
@stop
