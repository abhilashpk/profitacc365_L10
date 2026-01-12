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
                Report <div class="pull-right"> <a href="index"><h4><b>{{Session::get('driver')}}</b></h4></a></div>
            </h1>
            <ol class="breadcrumb">
                <li>
					<b><div class="pull-right"> <a href="{{ url('myorder/dashboard') }}">Dashboard</a> | <a href="{{ url('myorder/list') }}">Orders</a> | <a href="{{ url('myorder/pickup') }}">Pickup</a> | Report | <a href="{{ url('myorder/logout') }}">Logout</a></div></b>
                </li>
            </ol>
			
        </section>
		

        <section class="content">
            <div class="row">
				<div class="col-lg-6">
				<h4>Delivered</h4>
				<div class="table-responsive">
					
                    <table class="table table-bordred table-striped">
						<thead>
						<tr>
							<th>Si.No</th>
							<th>Order No</th>
							<th>Ref. No</th>
							<th>Supplier</th>
							<th>Customer Details</th>
							<th>Location</th>
							<th>Delivery Date</th>
							<th class="text-right">Amount</th>
						</tr>
						</thead>
						<tbody>
						<?php $total = $i = 0; ?>
						@foreach($report as $res)
						<tr>
							<td>{{++$i}}</td>
							<td>{{$res->order_no}}</td>
							<td>{{$res->invoice_no}}</td>
							<td>{{$res->name}}</td>
							<td><?php echo '<b>'.$res->recipient_name.'</b>';// Ph:'.$res->r_phone.'<br/> Adrs:'.$res->r_address.' Cty:'.$res->r_city;?></td>
							<td>{{$res->r_location}}</td>
							<td>{{date('d-m-Y h:i a', strtotime($res->delivered_date))}}</td>
							<td class="text-right">{{$res->amount}}</td>
							<?php $total += $res->amount; ?>
						</tr>
						@endforeach
						<tr>
							<td colspan="7" align="right"><b>Total Amount</b></td>
							<td class="text-right"><b>{{number_format($total,2)}}</b></td>
						</tr>
						</tbody>
					</table>
				</div>
				
				
				<h4>Cancelled</h4>
				<div class="table-responsive">
					
                    <table class="table table-bordred table-striped">
						<thead>
						<tr>
							<th>Si.No</th>
							<th>Order No</th>
							<th>Ref. No</th>
							<th>Supplier</th>
							<th>Customer Details</th>
							<th>Location</th>
							<th>Delivery Date</th>
							<th class="text-right">Amount</th>
						</tr>
						</thead>
						<tbody>
						<?php $total = $i = 0; ?>
						@foreach($cancelled as $res)
						<tr>
							<td>{{++$i}}</td>
							<td>{{$res->order_no}}</td>
							<td>{{$res->invoice_no}}</td>
							<td>{{$res->name}}</td>
							<td><?php echo '<b>'.$res->recipient_name.'</b>';// Ph:'.$res->r_phone.'<br/> Adrs:'.$res->r_address.' Cty:'.$res->r_city;?></td>
							<td>{{$res->r_location}}</td>
							<td>{{date('d-m-Y h:i a', strtotime($res->delivered_date))}}</td>
							<td class="text-right">{{$res->amount}}</td>
							<?php $total += $res->amount; ?>
						</tr>
						@endforeach
						<tr>
							<td colspan="7" align="right"><b>Total Amount</b></td>
							<td class="text-right"><b>{{number_format($total,2)}}</b></td>
						</tr>
						</tbody>
					</table>
				</div>
				
				
				<h4>Picked-up</h4>
				<div class="table-responsive">
					
                    <table class="table table-bordred table-striped">
						<thead>
						<tr>
							<th>Si.No</th>
							<th>Order No</th>
							<th>Ref. No</th>
							<th>Supplier</th>
							<th>Customer Details</th>
							<th>Location</th>
							<th>Pickup Date</th>
							<th class="text-right">Amount</th>
						</tr>
						</thead>
						<tbody>
						<?php $total = $i = 0; ?>
						@foreach($pickedup as $res)
						<tr>
							<td>{{++$i}}</td>
							<td>{{$res->order_no}}</td>
							<td>{{$res->invoice_no}}</td>
							<td>{{$res->name}}</td>
							<td><?php echo '<b>'.$res->recipient_name.'</b>';?></td>
							<td>{{$res->d_location}}</td>
							<td>{{date('d-m-Y h:i a', strtotime($res->pickup_datetime))}}</td>
							<td class="text-right">{{$res->pickup_amount}}</td>
							<?php $total += $res->pickup_amount; ?>
						</tr>
						@endforeach
						<tr>
							<td colspan="7" align="right"><b>Total Amount</b></td>
							<td class="text-right"><b>{{number_format($total,2)}}</b></td>
						</tr>
						</tbody>
					</table>
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

</script>
@stop
