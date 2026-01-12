@php $i=0; @endphp
@foreach($data as $row)
@php $i++; @endphp
<div class="col-xs-3" style="width:15%;">
	<span class="small">Debit A/c.</span> <input type="text" id="dracname_{{$i}}" value="{{$row->acname}}" autocomplete="off" name="drac_name[]" class="form-control" data-toggle="modal" data-target="#accounts_modal">
	<input type="hidden" id="dracid_{{$i}}" name="drac_id[]" value="{{$row->acid}}" >
	<input type="hidden" name="je_id[]" value="{{$row->reid}}">
</div>
<div class="col-xs-3" style="width:10%;">
	<span class="small">Ref.No.</span> 
	<input type="text" id="ref_{{$i}}" name="reference[]" value="{{$row->ref}}" autocomplete="off" class="form-control">
</div>
<div class="col-xs-3" style="width:15%;">
	<span class="small">Description</span> 
	<input type="text" id="desc_{{$i}}" name="description[]" value="{{$row->desc}}" autocomplete="off" class="form-control">
</div>

<div class="col-xs-1" style="width:7%;">
	<span class="small">P.Mode</span> 
	<select id="trtype_{{$i}}" class="form-control trtype" style="width:100%" name="tr_type[]">
		<option value="C">CASH</option>
		<option value="B">CDC</option>
		<option value="P">PDC</option>
	</select>
</div>
<div class="col-xs-2" style="width:10%;">
	<span class="small">Amount</span> <input type="number" id="amount_{{$i}}" value="{{$row->amount}}" autocomplete="off" step="any" name="amount[]" class="form-control rvAmt">
</div>

<div class="col-xs-3" style="width:10%;">
	<span class="small">Bank</span> 
	<select id="bankid_{{$i}}" class="form-control select2 line-bank" style="width:100%" name="bank_id[]">
		<option value="">Bank</option>
		@foreach($banks as $bank)
		<option value="{{$bank->id}}">{{$bank->code}}</option>
		@endforeach
	</select>
</div>

<div class="col-sm-2" style="width:10%;"> 
	<span class="small">Cheque No</span><input type="text" autocomplete="off" id="chkno_{{$i}}" name="cheque_no[]" class="form-control" >
</div>

<div class="col-xs-3" style="width:10%;">
	<span class="small">Cheque Date</span> <input type="text" autocomplete="off" id="chkdate_{{$i}}" name="cheque_date[]" class="form-control chqdate" data-language='en'>
</div>

<div class="col-xs-3" style="width:13%;">
	<span class="small">Remarks</span> <input type="text" id="rmrk_{{$i}}" name="remarks[]" value="{{$row->inst}}" class="form-control">
</div>
<br/>
<hr/>
@endforeach

<script>
$('.chqdate').datepicker({ autoClose: true, language: 'en', dateFormat: 'dd-mm-yyyy' });
			
$(document).on('change','.trtype', function(e) {
	var no = this.id.split('_'); 
	if(this.value=='C'){
		$('#dracname_'+no[1]).val( $('#cash_ac').val() );
		$('#dracid_'+no[1]).val( $('#cash_acid').val() );
	} else if(this.value=='P'){
		$('#dracname_'+no[1]).val( $('#pd_ac').val() );
		$('#dracid_'+no[1]).val( $('#pd_acid').val() );
	} else if(this.value=='B'){
		$('#dracname_'+no[1]).val( $('#bk_ac').val() );
		$('#dracid_'+no[1]).val( $('#bk_acid').val() );
	}
})
</script>