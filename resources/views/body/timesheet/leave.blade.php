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
                Leave
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="fa fa-fw fa-money"></i>Time Sheet
                    </a>
                </li>
                <li>
                    <a href="#">Leave</a>
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
                                <i class="fa fa-fw fa-columns"></i> Employee Leave
                            </h3>
							<div class="pull-right">
                             
                        </div>
                        </div>
                        <div class="panel-body">
                            
                            <form class="form-horizontal" role="form" method="POST" name="frmPayslip" id="frmPayslip" action="{{ url('wage_entry/timesheet/leave_search') }}">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								<div class="row">
									<div class="col-sm-2">
										<span>Month:</span>
										<select id="month" class="form-control select2" style="width:100%" name="month">
                                            <option value="0" >Select Month</option>
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

                                    <div class="col-sm-2">
                                    <span>Employee </span>
									<select class="form-control" id="emply_id" name="emply_id">
									<option value="0">Select Employee</option>
									@foreach($emply as $row)
									<option value="{{$row->id}}" {{($eid==$row->id)?'selected':''}} >{{$row->name}}</option>
									@endforeach
									</select>
								</div>	

							<div class="col-sm-2">
                            <span>Category </span>
                                <select  class="form-control"  id="category_id" name="category_id">
                                    <option value="0">Select Category</option>
										@foreach($category as $crow)
											<option value="{{ $crow->id }}" {{($cid==$crow->id)?'selected':''}} >{{ $crow->category_name }}</option>
										@endforeach
                                </select>
                            </div>	
									<div class="col-sm-2"><br/>
										<button type="submit" class="btn btn-primary">Search</button>
                                        <a href="{{ url('wage_entry/timesheet/leave') }}" class="btn btn-danger">Clear</a>
									</div>
								</div>
								
							</form>
							<?php if(count($employees)>0) { ?>
							<div class="table-responsive m-t-10">
                                <table class="table horizontal_table table-striped" id="tableEmployee">
                                    <thead>
                                    <tr>
                                       
                                        <th>Employee Code</th>
                                        <th>Name</th>
                                        <th>Leave Date</th>
										<th>Designation</th>
                                        <th>Category</th>
										<th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
									@foreach($employees as $employee)
                                    <tr>
                                        <td>{{ $employee->code }}</td>
                                        <td>{{ $employee->name }}</td>
                                        <td>{{date('d-m-Y',strtotime($employee->date))}}</td>
										<td>{{ $employee->designation }}</td>
                                        <td>{{ $employee->category_name }}</td>
										<td>
                                        <p><button class="btn btn-primary btn-xs" onClick="window.open('{{ url('wage_entry/time/leave_edit/'.$employee->id)}}','_blank')">Edit</button></p></p>
											
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


</script>
@stop
