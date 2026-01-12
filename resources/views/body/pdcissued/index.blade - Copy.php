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
                 PDC Issued
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="">
                        <i class="fa fa-fw fa-retweet"></i> Transaction
                    </a>
                </li>
                <li>
                    <a href="">Post Dated Cheque</a>
                </li>
				<li class="active">
                    PDC Issued
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
                            <i class="fa fa-fw fa-list-alt"></i> PDC Issued List
                        </h3>
                        <div class="pull-right">
                             
                        </div>
                    </div>
                    <div class="panel-body">
						
                        <div class="table-responsive">
                                <table class="table table-striped" id="table1">
                                    <thead>
                                    <tr>
										<th></th>
                                        <th>Vchr.No</th>
										<th>Amount</th>
										<th>Chq.No</th>
										<th>Chq.Date</th>
										<th>Bank</th>
										<th>Reference</th>
										<th>Bank A/c. Cr.</th>
										<th class="no_wrap">PDC A/c. Dr.</th>
										<th>Supplier</th>
										<th>Vchr.Date</th>
										<th>Description</th>
                                    </tr>
                                    </thead>
                                    <tbody>
									<form method="POST" name="frmPdcIssued" id="frmPdcIssued" action="{{ url('pdc_issued/save') }}">
									<input type="hidden" name="_token" value="{{ csrf_token() }}">
									{{--*/ $i = 0; /*--}}
									@foreach($pdcs as $pdc)
									<?php if($pdc->cheque_no!='' && $pdc->code!='') { $i++; ?>
                                    <tr>
                                        <td><input type="hidden" name="id[]" value="{{$pdc->id}}">
											<input type="hidden" name="voucher_type[]" value="{{$pdc->voucher_type}}">
											<input type="checkbox" name="tag[]" class="tag-line" value="{{$i-1}}"></td>
										<td>{{ $pdc->voucher_no }}</td>
										<?php $amount = ($pdc->amount < 0)?$pdc->amount*-1:$pdc->amount;?>
										<td>{{ number_format($amount,2) }} <input type="hidden" name="amount[]" value="{{$amount}}"></td>
										<td>{{ $pdc->cheque_no }}</td>
										<td class="no_wrap">{{ ($pdc->cheque_date=='0000-00-00')?'':date('d-m-Y',strtotime($pdc->cheque_date)) }}</td>
										<td>{{ $pdc->code }}</td>
										<td><input type="text" name="reference[]" autocomplete="off" class="form-control" style="width:8em;"></td>
										<td><input type="text" name="cr_account[]" id="draccount_{{$i}}" class="form-control" value="{{$banks[0]->master_name}}" onClick="getAccount(this)" style="width:8em;">
										<input type="hidden" name="cr_account_id[]" id="draccountid_{{$i}}" value="{{$banks[0]->id}}">
										</td>
										<td class="no_wrap">{{ $pdc->debitor }}</td>
										<td class="no_wrap">{{ $pdc->creditor }}</td><input type="hidden" name="supplier_id[]" id="supplierid_{{$i}}" value="{{$pdc->creditor_id}}">
										<td class="no_wrap">{{ date('d-m-Y',strtotime($pdc->voucher_date)) }} <input type="hidden" name="dr_account_id[]" value="{{$pdc->cr_account_id}}" ></td>
										<td>{{ $pdc->description }}</td>
                                    </tr>
									<?php } ?>
									@endforeach
									<div class="col-sm-3">
										<input type="text" class="form-control pull-right" name="voucher_date" data-language='en' readonly id="voucher_date" placeholder="Transfer Date"/>
                                    </div>
									<button type="button" class="btn btn-primary" onClick="setSubmit()">Submit</button> &nbsp; 
									<a href="{{ url('pdc_issued/undo_list') }}" class="btn btn-primary">Undo PDC</a>
									</form>
                                    @if (count($pdcs) === 0)
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
<script src="{{asset('assets/vendors/moment/js/moment.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/datetime/js/jquery.datetimepicker.full.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.en.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/custom_js/advanceddate_pickers.js')}}"></script>

<script>
$(function(){
	$('.cdata').toggle();
	var itmurl = "{{ url('account_master/custom_account/') }}";
		$(document).on('click', 'input[name="cr_account[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		$('#customerData').load(itmurl+'/'+curNum, function(result){
			$('#myModal').modal({show:true});
		});

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
		alert('Please select atleast one issued Cheque!');
		return checked;
	} else {
		document.frmPdcIssued.submit();
	}
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
		alert('Please select atleast one issued Cheque!');
		return checked;
	} else if($('#voucher_date').val()=='') {
		alert('Please enter transfer date!');
		return false;
	} else {
		document.frmPdcIssued.action="{{ url('pdc_issued/undo')}}";
		document.frmPdcIssued.submit();
	}
}

function getDetail() {	
	document.frmPdcIssued.action = "{{ url('pdc_issued/print')}}";
	document.frmPdcIssued.submit();
}

</script>

@stop
