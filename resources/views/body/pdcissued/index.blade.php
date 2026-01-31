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
						<form method="POST" name="frmPdcIssued" id="frmPdcIssued" action="{{ url('pdc_issued/save') }}">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<input type="hidden" name="id" id="pdcid">
							<input type="hidden" name="voucher_type" id="vtype">
							<input type="hidden" name="reference" id="ref">
							<input type="hidden" name="dr_account_id" id="drac">
							<input type="hidden" name="cr_account_id" id="crac">
							<input type="hidden" name="customer_id" id="cid">
							<input type="hidden" name="amount" id="amt">
							<input type="hidden" name="vdate" id="vdate">
							<input type="hidden" name="cname" id="cname">
						</form>
						
						@if(!$baccount)
						<div class="alert alert-warning">
            			    <p>PDC issued credit account is not set in PDC voucher! For set this account, go to Account Settings in Administration menu and create a PDCI voucher with your credit account.</p>
            		    </div>
						@endif
						
                        <div class="table-responsive">
                                <table class="table horizontal_table table-striped" id="tablePDCI" border="0">
                                    <thead>
                                    <tr>
										<th></th>
										<th>Chq.Date</th>
										<th>Chq.No</th>
										<th>Amount</th>
										<th>Bank</th>
										<th>Vchr.No</th>
										<th>Type</th>
										<th>Reference</th>
										<th>Bank A/c.Cr.</th>
										<th class="no_wrap">PDC A/c.Dr.</th>
										<th>Supplier</th>
										<th>Vchr.Date</th>
                                    </tr>
                                    </thead>
                                    <tbody>
									@php $i = 0; @endphp
									@foreach($pdcs as $pdc)
									@php $i++; @endphp
                                    <tr>
                                        <td>
											<input type="checkbox" name="tag[]" class="tag-line" value="{{$i}}">
											<input type="hidden" name="ids[]" id="id_{{$i}}" value="{{$pdc->id}}">
											<input type="hidden" name="voucher_type[]" id="vtype_{{$i}}" value="{{$pdc->voucher_type}}">
										</td>
										<td class="no_wrap">{{ ($pdc->cheque_date=='0000-00-00')?'':date('d-m-Y',strtotime($pdc->cheque_date)) }}</td>
										<td>{{ $pdc->cheque_no }}</td>
										<?php //$amount = ($pdc->amount < 0)?$pdc->amount*-1:$pdc->amount;?>
										<td>{{ number_format($pdc->amount,2) }} <input type="hidden" name="amount[]" id="amt_{{$i}}" value="{{$pdc->amount}}"></td>
										<td>{{ $pdc->code }}</td>
										<td>{{ $pdc->voucher_no }}</td>
										<td>{{$pdc->entry_type}}</td>
										<td><input type="text" name="reference[]" id="ref_{{$i}}" autocomplete="off" class="form-control" style="width:8em;"></td>
										<td><input type="text" name="cr_account[]" id="draccount_{{$i}}" class="form-control" value="{{isset($baccount)?$baccount->master_name:''}}" data-toggle="modal" data-target="#account_modal" style="width:8em;">
										<input type="hidden" name="cr_account_id[]" id="craccountid_{{$i}}" value="{{isset($baccount)?$baccount->cr_account_master_id:''}}">
										</td>
										<td class="no_wrap">{{ $pdc->creditor }}</td>
										<td class="no_wrap">{{ $pdc->supplier }}
										<input type="hidden" name="supplier_id[]" id="supplierid_{{$i}}" value="{{$pdc->supplier_id}}">
										<input type="hidden" name="supplier[]" id="supplier_{{$i}}" value="{{$pdc->supplier}}"></td>
										<td class="no_wrap">{{ date('d-m-Y',strtotime($pdc->voucher_date)) }} 
										<input type="hidden" name="dr_account_id[]" id="draccountid_{{$i}}" value="{{$pdc->dr_account_id}}" ></td>
                                    </tr>
									@endforeach
									<div class="col-sm-3">
										<input type="text" class="form-control pull-right" name="voucher_date" data-language='en' readonly id="voucher_date" placeholder="Cheque Transfer Date" autocomplete="off"/>
                                    </div>
									<button type="button" class="btn btn-primary" id="btnsubmit" onClick="setSubmit()" @if(!$baccount) disabled @endif>Submit</button> &nbsp; 
									<a href="{{ url('pdc_issued/undo_list') }}" class="btn btn-primary">View PDC Submitted List</a>
									&nbsp; <button type="button" class="btn btn-primary outstanding" onclick="getDetail()">Print PDC Report</button>
                                    </tbody>
                                </table>
								
                            </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="cdata" id="customerData"></div>

        </section>
		
		<div id="account_modal" class="modal fade animated" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Select Account</h4>
					</div>
					<div class="modal-body" id="account_data">
					</div>
				</div>
			</div>
		</div> 
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

<script>

$('#voucher_date').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy' } );

$(document).ready(function() {
    var table = $('#tablePDCI').DataTable({
        "scrollX": true,
		'order': [[0, 'asc']]
    });
	
//	var acurl = "{{ url('account_master/get_account_list/BANK') }}";
    var acurl = "{{ url('account_master/get_accounts') }}";
	$(document).on('click', 'input[name="cr_account[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		$('#account_data').load(acurl+'/'+curNum, function(result){ 
			$('#myModal').modal({show:true}); $('.input-sm').focus();
		});
	});
	
	$(document).on('click', '.custRow', function(e) { //accountRow
		var num = $('#num').val();
		$('#draccount_'+num).val( $(this).attr("data-name") );
		$('#craccountid_'+num).val( $(this).attr("data-id") );
	});
});

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

function setSubmit2() {
	
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

function setSubmit() {
	
	var checked=false; var is_ac=false;
	var elements = document.getElementsByName("tag[]");
	for(var i=0; i < elements.length; i++){
	    
	    if(elements[i].checked) {
			checked = true;
			is_ac=true;
		}
		
	}
	
	$("input[type=checkbox]:checked").each(function() { 
          var curNum = this.value;
	      
	      if($('#craccountid_'+curNum).val()=='') { 
	        is_ac=false;
	      }
    });
	
	if(!checked) {
		alert('Please select atleast one received Cheque!');
		return checked;
	} else if($('#voucher_date').val()=='') {
		alert('Please enter transfer date!');
		return false;
	} else if(!is_ac) {
	    alert('Credit account is required!');
	} else {
		
		var id = []; var drac = []; var crac = []; var amt = []; var ref = []; var vtype = []; var cid = []; var cname = []; var i;
		$("input[name='tag[]']:checked").each(function(){ 
			i =  $(this).val(); console.log(i);
			id.push($('#id_'+i).val());
			drac.push($('#draccountid_'+i).val());
			crac.push($('#craccountid_'+i).val());
			amt.push($('#amt_'+i).val());
			ref.push($('#ref_'+i).val());
			cid.push($('#supplierid_'+i).val());
			cname.push($('#customer_'+i).val());
			vtype.push($('#vtype_'+i).val());
		}); 
		$('#pdcid').val(id);
		$('#drac').val(drac);
		$('#crac').val(crac);
		$('#amt').val(amt);
		$('#ref').val(ref);
		$('#vtype').val(vtype);
		$('#cid').val(cid);
		$('#cname').val(cname);
		$('#vdate').val($('#voucher_date').val());
		
		$("#frmPdcIssued").submit();	
		//document.frmPdcIssued.submit();
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
