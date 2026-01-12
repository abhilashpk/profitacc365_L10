<h4><b>Pending List</b></h4>
<table class="table horizontal_table table-striped" id="tablePDCR" border="0">
	<thead>
	<tr>
		<th>Flat No</th>
		<th>Tenant Name</th>
		<th>Prv.Reading</th>
		<th>Cur.Reading</th>
		<th>Cons.Unit</th>
		<th>Amount</th>
		<th>Oth.Charge</th>
		<th>Net Total</th>
		<th>RV</th>
		<th>RV Amount</th>
		<th></th>
	</tr>
	</thead>
	<tbody>
	@if(sizeof($result > 0))
	@php $i = 0; @endphp
	@foreach($result as $row)
	@php $i++; @endphp
	<tr>
		<td>{{$row->flat_no}} <input type="hidden" name="readId[]" id="readId_{{$i}}" value="{{$row->readid}}"> <input type="hidden" name="conId[]" id="conId_{{$i}}" value="{{$row->id}}"><input type="hidden" value="{{$row->flat_id}}" name="flatId[]" id="flatId_{{$i}}"></td>
		<td>{{$row->master_name}} <input type="hidden" name="tenantName[]" id="tenantName_{{$i}}" value="{{$row->master_name}}"><input type="hidden" name="tenantId[]" id="tenantId_{{$i}}" value="{{$row->customer_id}}"></td>
		<td><b>{{($row->current!='')?$row->current:$row->new_reading}}</b> <input type="hidden" name="prvRead[]" value="{{($row->current!='')?$row->current:$row->new_reading}}" id="prvRead_{{$i}}"></td>
		<td><input type="number" name="curReading[]" id="curReading_{{$i}}" autocomplete="off" step="any" class="form-control" style="width:5em;"></td>
		<td><b><span id="cunt_{{$i}}"></span></b> <input type="hidden" name="conUnit[]" id="conUnit_{{$i}}"></td>
		<td><b><span id="camt_{{$i}}"></span></b> <input type="hidden" name="amt[]" id="amt_{{$i}}"><input type="hidden" name="bRate[]" id="bRate_{{$i}}" value="{{$row->unit_price}}"></td>
		<td><input type="text" name="othCharge[]" id="othCharge_{{$i}}" value="{{$row->other_charge}}" step="any" class="form-control" required style="width:5em;"></td>
		<td><input type="number" name="netTotal[]" id="netTotal_{{$i}}" step="any" class="form-control" required style="width:6em;"></td>
		<td><input type="checkbox" name="isRv[]" id="isRv_{{$i}}" class="is-rv" value="1"></td>
		<td><input type="hidden" name="vat[]" id="vat_{{$i}}"><input type="number" name="rvAmount[]" id="rvAmount_{{$i}}" step="any" class="form-control" required style="width:6em;"></td>
		<td><button type="button" class="btn btn-primary btnSave" data-id="{{$i}}">Save</button></td>
	</tr>
	@endforeach
	@else
	<tr>
		<td colspan="11">No records were found!</td>
	</tr>
	@endif
	</tbody>
</table>
<hr/><br/>

<h4><b>Completed List</b></h4>
<table class="table horizontal_table table-striped" id="tablePDCR" border="0">
	<thead>
	<tr>
		<th>Flat No</th>
		<th>Tenant Name</th>
		<th>Prv.Reading</th>
		<th>Cur.Reading</th>
		<th>Cons.Unit</th>
		<th>Net Total</th>
		<th></th>
	</tr>
	</thead>
	<tbody>
	@if(sizeof($resdone > 0))
	@php $i = 0; @endphp
	@foreach($resdone as $row)
	@php $i++; @endphp
	<tr>
		<td>{{$row->flat_no}}</td>
		<td>{{$row->master_name}}</td>
		<td>{{$row->previous}}</td>
		<td>{{$row->current}}</td>
		<td>{{$row->cons_unit}}</td>
		<td>{{$row->grand_total}}</td>
		<td><a href="{{url('contract-connection/print-read/'.$row->mid.'/32/')}}" target='_blank'  role='menuitem' class='btn btn-primary btn-xs'><span class='fa fa-fw fa-print'></span>Print</a></td>
	</tr>
	@endforeach
	@else
		
	<tr>
		<td colspan="11">No records were found!</td>
	</tr>
	@endif
	</tbody>
</table>

<form class="form-horizontal" role="form" method="POST" name="frmRead" id="frmRead" action="{{url('contract-connection/readsave')}}">
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	<input type="hidden" name="connection_id" id="connection_id">
	<input type="hidden" name="buildingId" id="buildingId">
	<input type="hidden" name="current" id="current">
	<input type="hidden" name="previous" id="previous">
	<input type="hidden" name="frmDate" id="frmDate">
	<input type="hidden" name="toDate" id="toDate">
	<input type="hidden" name="flat_id" id="flat_id">
	<input type="hidden" name="customer_id" id="customer_id">
	<input type="hidden" name="customer_account" id="customer_account">
	<input type="hidden" name="rate" id="rate">
	<input type="hidden" name="con_unit" id="con_unit">
	<input type="hidden" name="vat_amount" id="vat_amount">
	<input type="hidden" name="amount" id="amount">
	<input type="hidden" name="total_amount" id="total_amount">
	<input type="hidden" name="grand_total" id="gtotal">
	<input type="hidden" name="date" value="{{date('Y-m-d')}}">
	<input type="hidden" name="rdid" id="rdid">
	
	@php $i=0; @endphp
	@foreach($accounts as $k => $acrow)
	@php $i++; @endphp
	<div class="form-group" style="display:none;">
		<label for="input-text" class="col-sm-2 control-label">{{$acrow->title}}</label>
		<div class="col-sm-6">
			<input type="text" class="form-control preincome" id="acname_1" name="acname[]" value="{{$acrow->master_name}}" readonly>
			<input type="hidden" name="acid[]" value="{{$acrow->account_id}}">
			<input type="hidden" name="is_tax[]" value="{{$acrow->is_tax}}">
		</div>
		<div class="col-sm-2">
			<input type="number" class="form-control {{($acrow->is_tax==1)?'txamount':'acamount'}}" id="acamt_{{$i}}" {{($acrow->is_tax==1)?"readonly":""}} step="any" name="acamount[]" placeholder="Amount">
		</div>
	</div>
	@endforeach
	
	<div class="form-group" style="display:none;">
		<label for="input-text" class="col-sm-2 control-label">Receipt Voucher Entry</label>
		<div class="col-sm-1">
			<label class="radio-inline iradio">
				<input type="hidden" id="is_rv" name="is_rv">
				<input type="hidden" name="rv_voucher[]" value=""/>
				<input type="hidden" name="voucher_type[]" value="CASH"/>
				<input type="hidden" name="rv_voucher_no[]" value="{{$rvvoucher['voucher_no']}}">
				<input type="hidden" name="rv_dr_account[]" value="{{$rvvoucher['account_name']}}">
				<input type="hidden" name="rv_dr_account_id[]" value="{{$rvvoucher['id']}}">
				
				<input type="hidden" name="rowid[]" value="">
				<input type="hidden" name="rowidcr[]" value="">
				
		</div>
		<div class="col-sm-9">
			<label for="input-text" class="col-sm-2 control-label">RV Amount</label>
			<div class="col-sm-4">
			<input type="number" class="form-control" name="rv_amount" step="any" id="rvamt" />
			</div>
		</div>
	</div>
</form>
<script>
function setForm(no) {
	$('#rdid').val( $('#readId_'+no).val() );
	$('#buildingId').val( $('#building_id option:selected').val() );
	$('#connection_id').val( $('#conId_'+no).val() );
	$('#current').val( $('#curReading_'+no).val() );
	$('#previous').val( $('#prvRead_'+no).val() );
	$('#frmDate').val( $('#con_date_frm').val() );
	$('#toDate').val( $('#con_date_to').val() );
	$('#flat_id').val( $('#flatId_'+no).val() );
	$('#customer_id').val( $('#tenantId_'+no).val() );
	$('#customer_account').val( $('#tenantName_'+no).val() );
	$('#rate').val( $('#bRate_'+no).val() );
	//arrId.push($('#rdid').val());
	
	grandTotal(no);
	//$('#netTotal_'+no).val($('#total_amount').val());
	$('#conUnit_'+no).val( $('#con_unit').val() );
	$('#cunt_'+no).html( $('#con_unit').val() );
	$('#camt_'+no).html( $('#amount').val() );
	$('#netTotal_'+no).val( $('#gtotal').val() );
	
	
	//$('#camt_'+no).html( $('#gtotal').val() );
	
}

function grandTotal(no) { 
	var prev = parseFloat($('#previous').val());
	var curr = parseFloat( ($('#current').val()=='')?0:$('#current').val());
	var rate = parseFloat($('#rate').val());
	var conunit = curr - prev;
	var amount = conunit * rate;
	var vat = (amount * 5)/100;
	$('#amount').val(amount);
	
	$( '.txamount' ).each(function() { 
		  var res = this.id.split('_');
		  var cno = res[1];
		  $('#acamt_'+cno).val(amount);
	});
	
	amount = amount + vat;
	$('#con_unit').val(conunit.toFixed(2));
	$('#vat_amount').val(vat.toFixed(2));
	$('#total_amount').val(amount.toFixed(2));
	
	var acamount = parseFloat(($('#othCharge_'+no).val()=='')?0:$('#othCharge_'+no).val());
	var gtotal = amount + acamount;
	$('#gtotal').val(gtotal.toFixed(2));
	
	
}

$(document).on('click', '.btnSave', function(e) { 
	var no = $(this).attr("data-id");
	$('#acamt_2').val( $('#othCharge_'+no).val() );
	if($('#curReading_'+no).val()=='') {
		alert('Current reading is required!');
		return false;
	} else {
		setForm(no);
		//localStorage.setItem('ids', arrId)
		$('#frmRead').submit();
	}
});

$(document).on('blur', 'input[name="curReading[]"]', function(e) { 
	var res = this.id.split('_');
	var no = res[1]; 
	
	setForm(no);
	
});

$(document).on('blur', 'input[name="othCharge[]"]', function(e) { 
	var res = this.id.split('_');
	var no = res[1]; 
	$('#acamt_2').val(this.value);
	setForm(no);
});

$(document).on('click', '.is-rv', function(e) { 
	var res = this.id.split('_');
	var no = res[1]; console.log('hi '+no);
	if($('#isRv_'+no).is(':checked')) {
		$('#rvAmount_'+no).val( $('#netTotal_'+no).val() );
		$('#rvamt').val( $('#rvAmount_'+no).val() );
		$('#is_rv').val(1);
	} else {
		$('#rvamt').val('');
		$('#rvAmount_'+no).val('');
		$('#is_rv').val('');
	}
});
</script>