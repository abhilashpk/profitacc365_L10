<fieldset>
	<legend><h5>Transaction(Dr)</h5></legend>
	<input type="hidden" id="dater" value="{{date('d-m-Y',strtotime($orvs[0]->voucher_date))}}">
	<div class="itemdivPrntDr">
	@php $i=1; @endphp
	@foreach($orvs as $orow)
		@if($orow->entry_type=='Dr')
			@php $drjeid=$orow->id; @endphp <!-- NOV24 -->
	  <div class="itemdivChldDr">
		<div class="col-xs-15">
			<div class="col-xs-3" style="width:15%;">
				<span class="small">Debit A/c.</span> <input type="text" id="rvOdracname_{{$i}}" value="{{$orow->master_name}}" autocomplete="off" name="drac_name[]" class="form-control">
				<input type="hidden" id="rvOdracid_{{$i}}" name="drac_id[]" value="{{$orow->account_id}}" >
			</div>
			<div class="col-xs-3" style="width:10%;">
				<span class="small">Ref.No.</span> 
				<input type="text" id="dref_{{$i}}" name="Dreference[]" value="{{$orow->reference}}" autocomplete="off" class="form-control">
			</div>
			<div class="col-xs-3" style="width:15%;">
				<span class="small">Description</span> 
				<input type="text" id="ddesc_{{$i}}" name="Ddescription[]" value="{{$orow->description}}" autocomplete="off" class="form-control">
			</div>

			<div class="col-xs-1" style="width:7%;">
				<span class="small">P.Mode</span> 
				<select id="drtrtype_{{$i}}" class="form-control drtrtype" style="width:100%" name="tr_type[]">
					<option value="C" {{($orow->currency_id==0)?'selected':''}}>CASH</option>
					<option value="B" {{($orow->currency_id==1)?'selected':''}}>CDC</option>
					<option value="P" {{($orow->currency_id==2)?'selected':''}}>PDC</option>
				</select>
			</div>
			<div class="col-xs-2" style="width:10%;">
				<span class="small">Amount</span> <input type="number" id="drdamount_{{$i}}" placeholder="Amount" value="{{$orow->amount}}" autocomplete="off" step="any" name="Damount[]" class="form-control">
			</div>
			
			<div class="col-xs-3" style="width:10%;">
				<span class="small">Bank</span> 
				<select id="dbankid_{{$i}}" class="form-control dr-bank" style="width:100%" name="Dbank_id[]">
					<option value="">Bank</option>
					@foreach($banks as $bank)
					<option value="{{$bank->id}}" {{($orow->bank_id==$bank->id)?"selected":""}}>{{$bank->code}}</option>
					@endforeach
				</select>
			</div>
			
			<div class="col-sm-2" style="width:10%;"> 
				<span class="small">Cheque No</span><input type="text" autocomplete="off" id="dchkno_{{$i}}" value="{{$orow->cheque_no}}" name="Dcheque_no[]" class="form-control" >
			</div>

			<div class="col-xs-3" style="width:10%;">
				<span class="small">Cheque Date</span> <input type="text" autocomplete="off" id="dchkdate_{{$i}}" value="{{($orow->cheque_date=='0000-00-00')?'':date('d-m-Y',strtotime($orow->cheque_date))}}" name="Dcheque_date[]" class="form-control chqdate" data-language='en'>
			</div>

			

			<div class="col-xs-3" style="width:10%;">
				<span class="small">Remarks</span> <input type="text" id="drmrk_{{$i}}" name="Dremarks[]" class="form-control">
			</div>
		</div>
	  </div>
	  @php $i++; @endphp
	  @endif
	  @endforeach
	</div>
</fieldset>	
<br/><br/>

<fieldset>
	<legend><h5>Transactions(Cr)</h5></legend>
	
	<div class="col-xs-15 Crcontrols">
		<div class="itemdivPrnt">
		@php $i=1; @endphp <!-- NOV24 -->
		@foreach($orvs as $orow)
			@if($orow->entry_type=='Cr')
			<div class="itemdivChld">							
				<div class="form-group" style="margin-bottom: 1px;">
					<div class="col-sm-2" style="width:25%;"> <span class="small">Tenant A/c</span>
					<input type="text" id="craccount_{{$i}}" name="account_name[]" class="form-control acname" value="{{$orow->master_name}}" autocomplete="off" data-toggle="modal" data-target="#account_modal">
					<input type="hidden" name="account_id[]" id="craccountid_{{$i}}" value="{{$orow->account_id}}">
					<input type="hidden" name="group_id[]" id="groupid_{{$i}}" value="CUSTOMER">
					<input type="hidden" name="vatamt[]" id="vatamt_{{$i}}">
					<input type="hidden" id="invoiceid_{{$i}}" name="sales_invoice_id[]">
					<input type="hidden" name="bill_type[]" id="biltyp_{{$i}}">
					<input type="hidden" name="expacid[]" id="eacid_{{$i}}">
					<input type="hidden" name="isfc[]" id="isfc_{{$i}}">
					<input type="hidden" name="je_id[]" id="cjeid_{{$i}}" value="{{$orow->id}}"> <!-- NOV24 -->
					</div>
					<div class="col-xs-15 divchq">
						<div class="col-xs-2" style="width:25%;">
							<span class="small">Description/Reference</span> <input type="text" id="crdescr_{{$i}}" value="{{$orow->description}}" autocomplete="off" name="description[]" class="form-control desc-bill" data-toggle="modal" data-target="#reference_modal">
						</div>
						<div class="col-xs-2" style="width:25%;">
							<span class="small">Contract No</span> 
							<div id="refdata_{{$i}}" class="refdata">
							<input type="text" id="crref_{{$i}}" name="reference[]" value="{{$orow->reference}}" autocomplete="off" class="form-control">
							</div>
							<input type="hidden" name="inv_id[]" id="invid_{{$i}}">
							<input type="hidden" name="actual_amount[]" id="actamt_1">
						</div>
						
						<div class="col-xs-2" style="width:20%;">
							<span class="small">Amount</span> <input type="number" id="ramount_{{$i}}" value="{{$orow->amount}}" autocomplete="off" step="any" name="line_amount[]" class="form-control">
						</div>
					</div>	
				</div>
			</div>
			@php $i++; @endphp
			@endif
		@endforeach
		<input type="hidden" value="{{$i-1}}" id="orvNo"> <!-- NOV24 -->
		</div>
		<input type="hidden" name="je_id[]" value="{{$drjeid}}"> <!-- NOV24 -->
	</div>
</fieldset>
