<fieldset>
<legend><h5><b>Recurring JV Details</b></h5></legend>
	@for ($i = 1; $i <= $data['per']; $i++)
	<div class="form-group">
		<label for="input-text" class="col-sm-2 control-label"><b>JV. No.</b></label>
		<div class="col-sm-10">
			<div class="input-group">
			<input type="text" class="form-control" id="voucher_no" value="{{$data['vno']+$i}}" readonly name="voucher_no_rc[]">
			<span class="input-group-addon"><i class="fa fa-fw fa-edit"></i></span>
			</div>
		</div>
	</div>
	<?php
		$vdate = ($data['vdate']=='')?date('d-m-Y'):$data['vdate'];
		$dt = date('d', strtotime($vdate));
		$m = date('m', strtotime($vdate));
		$y = date('Y', strtotime($vdate));
		if($m+$i > 12) {
			$vdt = $dt.'-'.(($m+$i)-12).'-'.($y+1);
		} else {
			$vdt = $dt.'-'.($m+$i).'-'.$y;
		}
	?>
	<div class="form-group">
		<label for="input-text" class="col-sm-2 control-label"><b>JV. Date</b></label>
		<div class="col-sm-10">
			<input type="text" class="form-control" value="{{date('d-m-Y', strtotime($vdt))}}" name="voucher_date_rc[]" id="voucher_date" autocomplete="off"/>
		</div>
		<input type="hidden" name="chktype" id="chktype">
		<input type="hidden" name="is_onaccount" id="is_onaccount" value="1">
	</div>
	
	@if($ispdc)
	<div class="itemdivPrnt">
		@foreach($data['account_name'] as $key => $val)
		<div class="itemdivChld">
			<div class="form-group classtrn" style="margin-bottom:1px;" id="trns_1">
			<div class="col-xs-1" style="width:12%;"> <span class="small">Account Name</span>
				<input type="text" id="draccount_1" name="account_name_rc[{{$data['vno']+$i}}][]" value="{{$data['account_name'][$key]}}" class="form-control acname" autocomplete="off" data-toggle="modal" data-target="#account_modal">
				<input type="hidden" name="account_id_rc[{{$data['vno']+$i}}][]" id="draccountid_1" value="{{$data['account_id'][$key]}}">
				<input type="hidden" name="group_id_rc[{{$data['vno']+$i}}][]" id="groupid_1" value="{{$data['group_id'][$key]}}">
				<input type="hidden" name="vatamt_rc[{{$data['vno']+$i}}][]" id="vatamt_1" value="{{$data['vatamt'][$key]}}">
				<input type="hidden" id="invoiceid_1" name="sales_invoice_id_rc[{{$data['vno']+$i}}][]" value="{{$data['sales_invoice_id'][$key]}}">
				<input type="hidden" name="bill_type_rc[{{$data['vno']+$i}}][]" id="biltyp_1" value="{{$data['bill_type'][$key]}}">
			</div>
				
			<div class="col-xs-2" style="width:12%;">
				<span class="small">Description</span> <input type="text" id="descr_1" autocomplete="off" name="description_rc[{{$data['vno']+$i}}][]" class="form-control" value="{{$data['description'][$key]}}">
			</div>
			<div class="col-xs-2" style="width:10%;">
				<span class="small">Reference</span> 
				<div id="refdata_1" class="refdata">
				<input type="text" id="ref_1" name="reference_rc[{{$data['vno']+$i}}][]" autocomplete="off" class="form-control" value="{{$data['reference'][$key]}}">
				</div>
				<input type="hidden" name="inv_id_rc[{{$data['vno']+$i}}][]" id="invid_1" value="{{$data['inv_id'][$key]}}">
				<input type="hidden" name="actual_amount_rc[{{$data['vno']+$i}}][]" id="actamt_1" value="{{$data['actual_amount'][$key]}}">
			</div>
			<div class="col-xs-1" style="width:7%;">
				<span class="small">Type</span> 
				<select id="acnttype_1" class="form-control select2 line-type" style="width:100%;padding-left:5px;" name="account_type_rc[{{$data['vno']+$i}}][]">
					@if($data['account_type'][$key]=='Dr')
					<option value="Dr">Dr</option>
					@elseif($data['account_type'][$key]=='Cr')
					<option value="Cr">Cr</option>
					@endif
				</select>
			</div>
			<div class="col-xs-1" style="width:10%;">
				<span class="small">Amount</span> <input type="number" id="amount_1" autocomplete="off" step="any" name="line_amount_rc[{{$data['vno']+$i}}][]" class="form-control jvline-amount" value="{{$data['line_amount'][$key]}}">
			</div>
			
			<div class="col-xs-1" style="width:8%;">
				<span class="small">Chq. No</span><input type="text" autocomplete="off" id="chkno_1" name="cheque_no_rc[{{$data['vno']+$i}}][]" required class="form-control">
			</div>
			<div class="col-xs-1" style="width:9%;">
				<span class="small">Chq. Date</span> <input type="text" autocomplete="off" id="chkdate_1" name="cheque_date_rc[{{$data['vno']+$i}}][]" required class="form-control chqdate" data-language='en'>
			</div>
			
			<div class="col-xs-1" style="width:9%;">
				<span class="small">Bank</span> 
					<select id="bankid_1" class="form-control select2 line-bank" style="width:100%" required name="bank_id_rc[{{$data['vno']+$i}}][]">
					<option value="">Bank...</option>
					@foreach($banks as $bank)
					<option value="{{$bank['id']}}">{{$bank['code']}}</option>
					@endforeach
				</select>
			</div>
			
			<div class="col-xs-1" style="width:9%;">
				<input type="hidden" name="partyac_id_rc[{{$data['vno']+$i}}][]" id="partyac_1" value="{{$data['partyac_id'][$key]}}">
					<span class="small">Pty. Name</span> <input type="text" id="party_1" autocomplete="off" name="party_name_rc[{{$data['vno']+$i}}][]" value="{{$data['party_name'][$key]}}" class="form-control">
			</div>
			
			<div class="col-xs-1" style="width:9%;">
				<span class="small">Job</span> 
				<select id="jobid_1" class="form-control select2 line-job" style="width:100%" name="job_id_rc[{{$data['vno']+$i}}][]">
					<option value="">Job...</option>
					@foreach($jobs as $job)
					<option value="{{ $job['id'] }}">{{ $job['name'] }}</option>
					@endforeach
				</select>
			</div>
		</div>
		</div>
		@endforeach
	</div>
	<hr/>
	@else
		<div class="itemdivPrnt">
		@foreach($data['account_name'] as $key => $val)
		<div class="itemdivChld">
			<div class="form-group classtrn" style="margin-bottom:1px;" id="trns_1">
				<div class="col-sm-2"> <span class="small">Account Name</span>
					<input type="text" id="draccountrc_1" name="account_name_rc[{{$data['vno']+$i}}][]" value="{{$data['account_name'][$key]}}" class="form-control acnamerc" autocomplete="off">
					<input type="hidden" name="account_id_rc[{{$data['vno']+$i}}][]" id="draccountidrc_1" value="{{$data['account_id'][$key]}}">
					<input type="hidden" name="group_id_rc[{{$data['vno']+$i}}][]" id="groupidrc_1" value="{{$data['group_id'][$key]}}">
					<input type="hidden" name="vatamt_rc[{{$data['vno']+$i}}][]" id="vatamtrc_1" value="{{$data['vatamt'][$key]}}">
					<input type="hidden" id="invoiceidrc_1" name="sales_invoice_id_rc[{{$data['vno']+$i}}][]" value="{{$data['sales_invoice_id'][$key]}}">
					<input type="hidden" name="bill_type_rc[{{$data['vno']+$i}}][]" id="biltyprc_1" value="{{$data['bill_type'][$key]}}">
				</div>
					<div class="col-xs-3" style="width:25%;">
						<span class="small">Description</span> <input type="text" id="descrrc_1" autocomplete="off" name="description_rc[{{$data['vno']+$i}}][]" class="form-control" value="{{$data['description'][$key]}}">
					</div>
					<div class="col-xs-2" style="width:15%;">
						<span class="small">Reference</span> 
						<div id="refdata_1" class="refdata">
						<input type="text" id="refrc_1" name="reference_rc[{{$data['vno']+$i}}][]" autocomplete="off" class="form-control" value="{{$data['reference'][$key]}}">
						</div>
						<input type="hidden" name="inv_id_rc[{{$data['vno']+$i}}][]" id="invidrc_1" value="{{$data['inv_id'][$key]}}">
						<input type="hidden" name="actual_amount_rc[{{$data['vno']+$i}}][]" id="actamtrc_1" value="{{$data['actual_amount'][$key]}}">
					</div>
					<div class="col-xs-1" style="width:8%;">
						<span class="small">Type</span> 
						<select id="acnttyperc_1" class="form-control select2 line-type-rc" style="width:100%;padding-left:5px;" name="account_type_rc[{{$data['vno']+$i}}][]">
							@if($data['account_type'][$key]=='Dr')
							<option value="Dr">Dr</option>
							@elseif($data['account_type'][$key]=='Cr')
							<option value="Cr">Cr</option>
							@endif
						</select>
					</div>
					<div class="col-xs-2" style="width:13%;">
						<span class="small">Amount</span> <input type="number" id="amountrc_1" autocomplete="off" step="any" name="line_amount_rc[{{$data['vno']+$i}}][]" class="form-control jvline-amount" value="{{$data['line_amount'][$key]}}">
					</div>
					
					<div class="col-sm-2" style="width:17%;"> 
						<span class="small">Job</span> 
						<select id="jobidrc_1" class="form-control select2 line-job-rc" style="width:100%" name="job_id_rc[{{$data['vno']+$i}}][]">
							<option value="">Select Job...</option>
							@foreach($jobs as $job)
							<option value="{{ $job['id'] }}" {{($data['job_id'][$key]==$job['id'])?"selected":""}} >{{ $job['name'] }}</option>
							@endforeach
						</select>
					</div>
					@if($isdept)
					<div class="col-xs-3" style="width:13%;">
						<span class="small">Department</span> 
						<select id="deptrc_1" class="form-control select2 line-dept-rc" style="width:100%" name="department_rc[{{$data['vno']+$i}}][]">
							<option value="">Department...</option>
							@foreach($departments as $department)
							<option value="{{ $department->id }}" {{($data['department'][$key]==$department->id)?"selected":""}}>{{ $department->name }}</option>
							@endforeach
						</select>
					</div>
					@endif
										
					<div id="chqdtrcl_1" class="divchq" style="display:none;">
						
						<div class="col-sm-2"> 
							<span class="small">Cheque No</span><input type="text" autocomplete="off" id="chknorc_1" name="cheque_no_rc[{{$data['vno']+$i}}][]" class="form-control">
						</div>
						
						<div class="col-xs-2">
							<span class="small">Cheque Date</span> <input type="text" autocomplete="off" id="chkdaterc_1" name="cheque_date_rc[{{$data['vno']+$i}}][]" class="form-control chqdate" data-language='en'>
						</div>
						
						<div class="col-xs-2">
							<span class="small">Bank</span> 
							<select id="bankidrc_1" class="form-control select2 line-bank" style="width:100%" name="bank_id_rc[{{$data['vno']+$i}}][]">
								<option value="">Select Bank...</option>
								@foreach($banks as $bank)
								<option value="{{$bank['id']}}">{{$bank['code'].' - '.$bank['name']}}</option>
								@endforeach
							</select>
						</div>
						
						<div class="col-xs-2">
							<input type="hidden" name="partyac_id_rc[{{$data['vno']+$i}}][]" id="partyac_1" value="{{$data['partyac_id'][$key]}}">
							<span class="small">Party Name</span> <input type="text" id="partyrc_1" name="party_name_rc[{{$data['vno']+$i}}][]" autocomplete="off" class="form-control" value="{{$data['party_name'][$key]}}">
						</div>
						
					</div>
			</div>
			
		</div>
		@endforeach
	  </div>
	  <hr/>
		@endif
	
	@endfor
</fieldset>
<script>
$('.chqdate').datepicker({
	language: 'en',
	dateFormat: 'dd-mm-yyyy',
	autoClose: 1
});

$(document).on('click', '.submitBtn', function() {	
	var urlvchr = "{{ url('journal/checkvchrno/') }}"; //CHNG
     $('#frmJournal').bootstrapValidator({
         fields: {
            
			"account_name[]": {
                validators: {
                    notEmpty: {
                        message: 'The account name is required and cannot be empty!'
                    }
                }
            },
			"description[]": {
                validators: {
                    notEmpty: {
                        message: 'The description is required and cannot be empty!'
                    }
                }
            },
			"line_amount[]": {
                validators: {
                    notEmpty: {
                        message: 'The amount is required and cannot be empty!'
                    }
                }
            },
			"cheque_no[]": {
                validators: {
                    notEmpty: {
                        message: 'The cheque no is required and cannot be empty!'
                    }
                }
            },
			"cheque_date[]": {
                validators: {
                    notEmpty: {
                        message: 'The cheque date is required and cannot be empty!'
                    }
                }
            },
			"bank_id[]": {
                validators: {
                    notEmpty: {
                        message: 'The bank is required and cannot be empty!'
                    }
                }
            },
			debit: {
                validators: {
                    identical: {
                        field: 'credit',
                        message: 'The Debit and Credit amount should be equal!'
                    }
                }
            },
            credit: {
                validators: {
                    identical: {
                        field: 'debit',
                        message: 'The Debit and Credit amount should be equal!'
                    }
                }
            }
			
        }
        
    }).on('reset', function (event) {
        $('#frmJournal').data('bootstrapValidator').resetForm();
    }); 
	
});
</script>