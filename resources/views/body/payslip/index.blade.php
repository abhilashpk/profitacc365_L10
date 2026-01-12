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
                Pay Slip
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="fa fa-fw fa-money"></i> Payroll
                    </a>
                </li>
                <li>
                    <a href="#">Pay Slip</a>
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
                                <i class="fa fa-fw fa-columns"></i> Employee Pay Slip
                            </h3>
							<div class="pull-right">
                             
                        </div>
                        </div>
                        <div class="panel-body">
                            
                            <form class="form-horizontal" role="form" method="POST" name="frmPayslip" id="frmPayslip" action="{{ url('pay_slip/search') }}">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								<div class="row">
									<div class="col-xs-4">
										<span>Month:</span>
										<select id="month" class="form-control select2" style="width:100%" name="month">
											<option value="1" <?php if($month==1) echo 'selected';?>>January</option>
											<option value="2" <?php if($month==2) echo 'selected';?>>February</option>
											<option value="3" <?php if($month==3) echo 'selected';?>>March</option>
											<option value="4" <?php if($month==4) echo 'selected';?>>April</option>
											<option value="5" <?php if($month==5) echo 'selected';?>>May</option>
											<option value="6" <?php if($month==6) echo 'selected';?>>June</option>
											<option value="7" <?php if($month==7) echo 'selected';?>>July</option>
											<option value="8" <?php if($month==8) echo 'selected';?>>Auguest</option>
											<option value="9" <?php if($month==9) echo 'selected';?>>September</option>
											<option value="10" <?php if($month==10) echo 'selected';?>>October</option>
											<option value="11" <?php if($month==11) echo 'selected';?>>November</option>
											<option value="12" <?php if($month==12) echo 'selected';?>>December</option>
										</select>
									</div>
									<div class="col-xs-4">
										<span>Year:</span>
										<select id="year" class="form-control select2" style="width:100%" name="year">
											
											<option value="2022" <?php if($year==2022) echo 'selected';?>>2022</option>
											<option value="2023" <?php if($year==2023) echo 'selected';?>>2023</option>
											<option value="2024" <?php if($year==2024) echo 'selected';?>>2024</option>
											<option value="2025" <?php if($year==2025) echo 'selected';?>>2025</option>
											<option value="2026" <?php if($year==2026) echo 'selected';?>>2026</option>
											<option value="2027" <?php if($year==2027) echo 'selected';?>>2027</option>
											<option value="2028" <?php if($year==2028) echo 'selected';?>>2028</option>
											<option value="2029" <?php if($year==2029) echo 'selected';?>>2029</option>
											<option value="2030" <?php if($year==2030) echo 'selected';?>>2030</option>
											
										</select>
									</div>
									<div class="col-xs-4"><br/>
										<button type="submit" class="btn btn-primary">Search</button>
									</div>
								</div>
								
							</form>
							<?php if($employees) { ?>
							<div class="table-responsive m-t-10">
                                <table class="table horizontal_table table-striped" id="tableEmployee">
                                    <thead>
                                    <tr>
                                        <th>Employee Code</th>
                                        <th>Name</th>
										<th>Designation</th>
										<th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
									@foreach($employees as $employee)
                                    <tr>
                                        <td>{{ $employee->code }}</td>
                                        <td>{{ $employee->name }}</td>
										<td>{{ $employee->designation }}</td>
										<td>
                                        <p><button class="btn btn-primary btn-xs" onClick="window.open('{{ url('pay_slip/employee/'.$employee->id.'/'.$month.'/'.$year)}}','_blank')">Pay Slip</button></p></p>
											<!-- <p><button class="btn btn-primary btn-xs" onClick="location.href='{{ url('pay_slip/employee/'.$employee->id.'/'.$month)}}'">Pay Slip</button></p></p> -->
										</td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
							<?php } ?>
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
<!-- end of page level js -->

<script>

function funDelete(id) {
	var con = confirm('Are you sure delete this entry?');
	if(con==true) {
		var url = "{{ url('wage_entry/delete/') }}";
	 location.href = url+'/'+id;
	}
}
</script>
@stop
