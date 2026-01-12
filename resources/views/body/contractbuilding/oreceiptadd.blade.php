@php $i=0; @endphp
@foreach($data as $row)
@php $i++; @endphp
<div class="col-xs-3" style="width:15%;" id="oreceipt_add">
	<span class="small">Debit A/c.</span> <input type="text" id="Idracname_{{$i}}" value="{{$row->acname}}" autocomplete="off" name="drac_name2[]" class="form-control" data-toggle="modal" data-target="#ORV2accounts_modal" >
	<input type="hidden" id="Idracid_{{$i}}" name="drac_id[]" value="{{$row->acid}}" >
	<input type="hidden" name="je_id[]" value="{{$row->reid}}">
</div>
<div class="col-xs-3" style="width:10%;">
	<span class="small">Ref.No.</span> 
	<input type="text" id="reff_{{$i}}" name="Dreference[]" value="{{$row->ref}}" autocomplete="off" class="form-control">
</div>
<div class="col-xs-3" style="width:15%;">
	<span class="small">Description</span> 
	<input type="text" id="desc_{{$i}}" name="Ddescription[]" value="{{$row->desc}}" autocomplete="off" class="form-control">
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
	<span class="small">Amount</span> <input type="number" id="amount_{{$i}}" value="{{$row->amount}}" autocomplete="off" step="any" name="Damount[]" class="form-control drAmt">
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



<fieldset>
	<legend><h5>Transactions(Cr)</h5></legend>
	<div class="col-xs-15 Crcontrols">
		<div class="itemdivPrnt">
		 @php $i=$orvtotal=0; @endphp
			@foreach($payacnts as $k => $row)
				@if($k>1 && $row->amount > 0 && !(in_array($row->account_id,$orv)) ) 
					@php $i++; $orvtotal += $row->amount; @endphp
			<div class="itemdivChld">	
					<div class="form-group" style="margin-bottom: 1px;" >
					<div class="col-sm-2" style="width:25%;"> <span class="small">Tenant A/c</span>
					<input type="text" id="craccount_{{$i}}" name="account_name[]" class="form-control acname" value="{{$desce}}" autocomplete="off" data-toggle="modal" data-target="#account_modal">
					<input type="hidden" name="crac_id[]" id="craccountid_{{$i}}" value="{{$tnid}}">
					<input type="hidden" name="group_id[]" id="groupid_{{$i}}" value="CUSTOMER">
					<input type="hidden" name="vatamt[]" id="vatamt_{{$i}}">
					<input type="hidden" id="invoiceid_{{$i}}" name="sales_invoice_id[]">
					<input type="hidden" name="bill_type[]" id="biltyp_{{$i}}">
					<input type="hidden" name="expacid[]" id="eacid_{{$i}}" value="{{$row->account_id}}">
					<input type="hidden" name="isfc[]" id="isfc_{{$i}}" >
					<input type="hidden" name="je_id[]" id="cjeid_{{$i}}" value="{{$row->id}}"> <!-- NOV24 -->
					</div>
					<div class="col-xs-15 divchq">
						<div class="col-xs-2" style="width:25%;">
							<span class="small">Description/Reference</span> <input type="text" id="crdescr_{{$i}}" value="{{$oracarr[$k].' - '.$row->acname1}}" autocomplete="off" name="description[]" class="form-control desc-bill" data-toggle="modal" data-target="#reference_modal">
						</div>
						<div class="col-xs-2" style="width:25%;">
							<span class="small">Contract No</span> 
							<div id="refdata_{{$i}}" class="refdata">
							<input type="text" id="crref_{{$i}}" name="reference[]" value="{{$refe}}" autocomplete="off" class="form-control">
							</div>
							<input type="hidden" name="inv_id[]" id="invid_{{$i}}">
							<input type="hidden" name="actual_amount[]" id="actamt_1">
						</div>
						
						<div class="col-xs-2" style="width:20%;">
							<span class="small">Amount</span> <input type="text" id="ramount_{{$i}}" value="{{$row->amount}}" autocomplete="off" step="any" name="line_amount[]" class="form-control orvAmt">
						</div>
					</div>	
				</div>
				@endif
			@endforeach
			</div>

			@foreach($payacnts as $k => $row)
				@if($row->tax_amount > 0 && !(in_array($row->account_id,$txrv)))
					@php $i++; $orvtotal += $row->tax_amount; @endphp
					<div class="itemdivChld">	
					<div class="form-group" style="margin-bottom: 1px;">
					<div class="col-sm-2" style="width:25%;"> <span class="small">Tenant A/c</span>
					<input type="text" id="craccount_{{$i}}" name="account_name[]" class="form-control acname" value="{{$desce}}" autocomplete="off" data-toggle="modal" data-target="#account_modal">
					<input type="hidden" name="crac_id[]" id="craccountid_{{$i}}" value="{{$tnid}}">
					<input type="hidden" name="group_id[]" id="groupid_{{$i}}" value="CUSTOMER">
					<input type="hidden" name="vatamt[]" id="vatamt_{{$i}}">
					<input type="hidden" id="invoiceid_{{$i}}" name="sales_invoice_id[]">
					<input type="hidden" name="bill_type[]" id="biltyp_{{$i}}">
					<input type="hidden" name="expacid[]" id="eacid_{{$i}}" value="{{$row->account_id}}">
					<input type="hidden" name="isfc[]" id="isfc_{{$i}}" value="1">
					<input type="hidden" name="je_id[]" id="cjeid_{{$i}}" value="{{$row->id}}"> <!-- NOV24 -->
					</div>
					<div class="col-xs-15 divchq">
						<div class="col-xs-2" style="width:25%;">
							<span class="small">Description/Reference</span> <input type="text" id="crdescr_{{$i}}" value="{{$oractxarr[$k].' - '.$row->acname1 }}" autocomplete="off" name="description[]" class="form-control desc-bill" data-toggle="modal" data-target="#reference_modal">
						</div>
						<div class="col-xs-2" style="width:25%;">
							<span class="small">Contract No</span> 
							<div id="refdata_{{$i}}" class="refdata">
							<input type="text" id="crref_{{$i}}" name="reference[]" value="{{$refe}}" autocomplete="off" class="form-control">
							</div>
							<input type="hidden" name="inv_id[]" id="invid_{{$i}}">
							<input type="hidden" name="actual_amount[]" id="actamt_1">
						</div>
						
						<div class="col-xs-2" style="width:20%;">
							<span class="small">Amount</span> <input type="text" id="ramount_{{$i}}" value="{{$row->tax_amount}}" autocomplete="off" step="any" name="line_amount[]" class="form-control orvAmt">
						</div>
					</div>	
				</div>
				
				@endif
			@endforeach
												
												
				</div>								
				</div>								
				</div>
							
<div id="accounts_modal" class="modal fade animated" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">Select Account</h4>
						</div>
						<div class="modal-body" id="accounts_data">
						</div>
					</div>
				</div>
			</div>
			
			
			
<script>
$('.chqdate').datepicker({ autoClose: true, language: 'en', dateFormat: 'dd-mm-yyyy' });
 
$(document).on('change','.trtype', function(e) { console.log('dsdg');
	var no = this.id.split('_'); 
	if(this.value=='C'){
		$('#Idracname_'+no[1]).val( $('#cash_ac').val() );
		$('#Idracid_'+no[1]).val( $('#cash_acid').val() );
	} else if(this.value=='P'){
		$('#Idracname_'+no[1]).val( $('#pd_ac').val() );
		$('#Idracid_'+no[1]).val( $('#pd_acid').val() );
	} else if(this.value=='B'){
		$('#Idracname_'+no[1]).val( $('#bk_ac').val() );
		$('#Idracid_'+no[1]).val( $('#bk_acid').val() );
	}
})
</script>