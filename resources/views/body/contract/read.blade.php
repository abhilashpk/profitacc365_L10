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
	
	<link rel="stylesheet" href="{{asset('assets/vendors/datetime/css/jquery.datetimepicker.css')}}">
    <link href="{{asset('assets/vendors/airdatepicker/css/datepicker.min.css')}}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/datepicker.css')}}">
	
	<link href="{{asset('assets/vendors/bootstrap-multiselect/css/bootstrap-multiselect.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('assets/vendors/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('assets/vendors/select2/css/select2-bootstrap.css')}}" rel="stylesheet" type="text/css">
	 
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/buttons.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/colReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/dataTables.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/rowReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/scroller.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatablesmark.js/css/datatables.mark.min.css')}}"/>
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/responsive_datatables.css')}}">
	
        <!--end of page level css-->
@stop
<style>input {font-weight:bold !important;}</style>
{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <!--section starts-->
            <h1>
                Contract
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="fa fa-fw fa-briefcase"></i> Inventory
                    </a>
                </li>
                <li>
                    <a href="#">Contract</a>
                </li>
                <li class="active">
                    Machine Read
                </li>
            </ol>
        </section>
        @if(Session::has('message'))
        <div class="alert alert-success">
            <p>{{ Session::get('message') }}</p>
        </div>
        @endif
        <!--section ends-->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-crosshairs"></i> Contract Details
                            </h3>
                           
                        </div>
                        <div class="panel-body">
                                <form class="form-horizontal" role="form">
                                
								 <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Contract No</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" value="{{$contract->contract_no}}" readonly >
                                    </div>
                                    <label for="input-text" class="col-sm-2 control-label">Contract Date</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control pull-right" value="{{date('d-m-Y',strtotime($contract->contract_date))}}" readonly />
								   </div>
                                </div>                              
                               
							   <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Customer Name</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" value="{{$contract->master_name}}" readonly>
                                    </div>
                                    <label for="input-text" class="col-sm-2 control-label">Contract Type</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control pull-right" value="{{$typename}}" readonly />
								   </div>
                                </div>  
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Contract Start Date</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" value="{{date('d-m-Y',strtotime($contract->start_date))}}" readonly>
                                    </div>
                                    <label for="input-text" class="col-sm-2 control-label">Contract End Date</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control pull-right" value="{{date('d-m-Y',strtotime($contract->end_date))}}" readonly />
								   </div>
                                </div>  
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"> Duration</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" value="{{round($contract->duration/30)}} Months" readonly>
                                    </div>
                                    <label for="input-text" class="col-sm-2 control-label">Machine Name</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control pull-right" value="{{$contract->brand.'  -  '.$contract->machine.'  ('.$contract->model.')'}}" readonly />
								   </div>
                                </div>  
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Paper</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" value="{{$pname}}" readonly>
                                    </div>
                                    <label for="input-text" class="col-sm-2 control-label">Remarks</label>
                                    <div class="col-sm-4">
										<textarea class="form-control" id="remarks" rows="4" name="remarks" >{{$contract->remarks}}</textarea>
								   </div>
                                </div>  
							</form>	
                        </div>
                    </div>
					
					<div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-crosshairs"></i> Machine Reading Details
                            </h3>
                        </div>
                        <div class="panel-body">
							<form class="form-horizontal" role="form" method="POST" name="frmContract" id="frmContract" action="{{ url('contract/readSave/'.$contract->id) }}">
								<div class="form-group">
									<label for="input-text" class="col-sm-5 control-label">Reading Date</label>
									<div class="col-sm-5">
										<input type="text" class="form-control" readonly name="read_date" autocomplete="off" data-language='en' readonly id="read_date">
									</div>
								</div>
								@foreach($paper as $row)
								<div class="form-group">
									<label for="input-text" class="col-sm-5 control-label">Paper - {{$row->name}}</label>
									<div class="col-sm-5">
										<input type="hidden" name="contract_id" value="{{$contract->id}}">
										<input type="hidden" name="paper_id[]" value="{{$row->id}}">
										<input type="hidden" name="paper_name[]" value="{{$row->name}}">
										<input type="number" class="form-control" name="paper_qty[]" placeholder="Total used nos.">
									</div>
								</div>
								@endforeach
								<div class="form-group">
									<label for="input-text" class="col-sm-5 control-label"></label>
									<div class="col-sm-5">
										<button type="submit" class="btn btn-primary">Save</button>
										<a href="{{ url('contract') }}" class="btn btn-danger">Cancel</a>
									</div>
								</div>
						   </form>
                        </div>
						
						<div class="table-responsive">
							<label for="input-text" class="col-sm-2 control-label"></label>
							<div class="col-sm-8">
							<h4><b>Machine Reading History</b></h4>
								<table class="table" style="font-size:14px !important;">
									<tr>
										<th>Date</th>
										<th>Paper</th>
										<th>No. of Copy</th>
										<th></th>
										<th></th>
									</tr>
									@foreach($mdatas as $data)
									@php
										$pdatas = unserialize($data->paper_and_qty); $i = 0;
									@endphp
									@foreach($pdatas as $prow)
									@php $i++; $pid = $prow['id']; @endphp
									<tr>
										<td>{{date('d-m-Y',strtotime($data->read_date))}}</td>
										<td>{{$prow['name']}}</td>
										<td>{{$prow['qty']}}</td>
										@if($i==1)
										<td rowspan="{{count($pdatas)}}" align="center" style="vertical-align:middle;"><a href="{{url('contract/read-edit').'/'.$contract->id.'/'.$data->id}}"><span class="glyphicon glyphicon-edit"></span></a></td>
										<td rowspan="{{count($pdatas)}}" align="center" style="vertical-align:middle;"><a href="javascript:funDelete('{{$contract->id}}','{{$data->id}}')"><span class="glyphicon glyphicon-remove-sign"></span></a></td>
										@endif
									</tr>
									@endforeach
									<tr><td colspan="5"></td></tr>
									@endforeach
								</table>
							</div>
							<label for="input-text" class="col-sm-2 control-label"></label>
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

<script src="{{asset('assets/vendors/datetime/js/jquery.datetimepicker.full.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.en.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/custom_js/advanceddate_pickers.js')}}"></script>

<script type="text/javascript" src="{{asset('assets/vendors/custom_js/form_elements.js')}}"></script>
<script src="{{asset('assets/vendors/select2/js/select2.js')}}" type="text/javascript"></script>

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
<script src="{{asset('assets/vendors/bootstrap-multiselect/js/bootstrap-multiselect.js')}}" type="text/javascript"></script>
        <!-- end of page level js -->

<script>
"use strict";

$(document).ready(function () {
	
	$('#read_date').datepicker( { autoClose: true,dateFormat: 'dd-mm-yyyy' } );
	
    $('#frmContract').bootstrapValidator({
        fields: {
			read_date: {
                validators: {
                    notEmpty: {
                        message: 'Read date is required and cannot be empty!'
                    }
                }
            },
			'paper_qty[]': {
                validators: {
                    notEmpty: {
                        message: 'Paper quantity is required and cannot be empty!'
                    }
                }
            }
        }
        
    }).on('reset', function (event) {
        $('#frmContract').data('bootstrapValidator').resetForm();
    });
	
});

function funDelete(id,rid) {
	var con = confirm('Are you sure delete this entry?');
	if(con==true) {
		var url = "{{ url('contract/read-delete/') }}";
		location.href = url+'/'+id+'/'+rid;
	}
}
</script>
@stop
