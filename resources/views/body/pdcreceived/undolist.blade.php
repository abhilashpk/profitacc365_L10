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
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/scroller.bootstrap.css')}}"/>
        <!--end of page level css-->
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
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/datepicker.css')}}">
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <!--section starts-->
            <h1>
                 PDC Received
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="">
                        <i class="fa fa-fw fa-retweet"></i> Transaction
                    </a>
                </li>
                <li>
                    <a href="">Undo PDC Received</a>
                </li>
				<li class="active">
                    PDC Received
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
                            <i class="fa fa-fw fa-list-alt"></i> PDC Received Submitted
                        </h3>
                        
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                                <table class="table horizontal_table table-striped" id="table1" border="0">
                                    <thead>
                                    <tr>
										<th></th>
                                        <th>Vchr.No</th>
										<th>Amount</th>
										<th>Chq.No</th>
										<th>Chq.Date</th>
										<th>Bank</th>
										<!--<th>Reference</th>-->
										<th>Bank A/c. Dr.</th>
										<th class="no_wrap">PDC A/c</th>
										<th>Customer</th>
										<th>Vchr.Date</th>
										<!--<th>Description</th>-->
                                    </tr>
                                    </thead>
                                    <tbody>
									<form method="POST" name="frmPdcReceived" id="frmPdcReceived" action="{{ url('pdc_received/save') }}">
									<input type="hidden" name="_token" value="{{ csrf_token() }}">
									@php $i = 0; @endphp
									@foreach($undos as $pdc)
									<?php  $i++; ?>
                                    <tr>
                                        <td><input type="hidden" name="id[]" value="{{$pdc->id}}">
											<input type="hidden" name="voucher_type[]" value="{{$pdc->vtype}}">
											<input type="checkbox" name="tag[]" class="tag-line" value="{{$i-1}}">
											<input type="hidden" name="rv_id[]" value="{{$pdc->voucher_id}}">
											</td>
										<td>{{ $pdc->voucher_no }}</td>
										<td>{{ number_format($pdc->amount,2) }} <input type="hidden" name="amount[]" value="{{$pdc->amount}}"></td>
										<td>{{ $pdc->cheque_no }}</td>
										<td class="no_wrap">{{ ($pdc->cheque_date=='0000-00-00')?'':date('d-m-Y',strtotime($pdc->cheque_date)) }}</td>
										<td>{{ $pdc->code }}</td>
										<td>{{ $pdc->bname }}</td>
										<td>{{ $pdc->master_name }}</td>
										<td class="no_wrap">{{ $pdc->customer }}</td>
										<td class="no_wrap">{{  date('d-m-Y',strtotime($pdc->voucher_date)) }}<input type="hidden" name="cr_account_id[]" value="{{$pdc->dr_account_id}}" ></td>
										<td><p><a href="" class="btn btn-danger btn-xs delete" data-id="{{$pdc->id}}"><span class="glyphicon glyphicon-trash"></span></a></p></td>
                                    </tr>
									
									@endforeach
									 
									<button type="button" class="btn btn-primary" onClick="setUndo()">Undo</button>
									</form>
                                    @if (count($undos) === 0)
									</tbody>
									<tbody><tr><td valign="top" colspan="12" class="dataTables_empty" align="center">No matching records found</td></tr></tbody>
									@endif
                                    </tbody>
                                </table>
								<button type="button" class="btn btn-primary outstanding" onclick="getDetail()">Print</button>
                            </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="cdata" id="customerData"></div>

        </section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <!-- begining of page level js -->
<script type="text/javascript" src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/iCheck/js/icheck.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-fileinput/js/fileinput.min.js')}}"></script>

<script src="{{asset('assets/vendors/moment/js/moment.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/datetime/js/jquery.datetimepicker.full.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.en.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/custom_js/advanceddate_pickers.js')}}"></script>

<!-- end of page level js -->
<script>
$(function(){
	$('.cdata').toggle();
	var itmurl = "{{ url('account_master/custom_account/') }}";
		$(document).on('click', 'input[name="dr_account[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		$('#customerData').load(itmurl+'/'+curNum, function(result){
			$('#myModal').modal({show:true});
		});

	});
	
	$(document).on('click', '.delete', function(e) {  
	    var id= $(this).data('id');
	    var con = confirm('Are you sure delete this PDC?');
	    e.preventDefault();
    	if(con==true) {
    		var url = "{{ url('pdc_received/delete/') }}";
    	    document.location.href = url+'/'+id;
    	}
	});
});
	
var popup;
function getAccount(e) { 
	
	var ht = $(window).height();
	var wt = $(window).width();
	var res = e.id.split('_');
	var curNum = res[1]; 
	var itmurl = "{{ url('account_master/custom_account/') }}/"+curNum;
	popup = window.open(itmurl, "Popup", "width=900,height=500,top=100,left=200");
	popup.focus();
	return false
}

function setSubmit() {
	
	var checked=false;
	var elements = document.getElementsByName("tag[]");
	for(var i=0; i < elements.length; i++){
		if(elements[i].checked) {
			checked = true;
		}
	}
	if (!checked) {
		alert('Please select atleast one received Cheque!');
		return checked;
	} else {
		document.frmPdcReceived.submit();
	}
}

function getDetail() {	
	document.frmPdcReceived.action = "{{ url('pdc_received/print')}}";
	document.frmPdcReceived.submit();
}

function setUndo() {
	
	var checked=false;
	var elements = document.getElementsByName("tag[]");
	for(var i=0; i < elements.length; i++){
		if(elements[i].checked) {
			checked = true;
		}
	}
	if (!checked) {
		alert('Please select atleast one received Cheque!');
		return checked;
	} else {
		document.frmPdcReceived.action="{{ url('pdc_received/undo')}}";
		document.frmPdcReceived.submit();
	}
}


function funDelete(id) {
	var con = confirm('Are you sure delete this PDC?');
	if(con==true) {
		var url = "{{ url('bank/delete/') }}";
	    location.href = url+'/'+id;
	}
}

</script>

@stop
