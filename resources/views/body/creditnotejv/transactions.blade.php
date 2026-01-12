@if($type=='PDC')
		@if($isdept)
		  <div class="col-sm-1 " style="width:1.55%;">
	      <input type="checkbox" id="tag_{{$num}}" name="tag[]" class="form-control tagchk" unchecked >
	      </div>
			<div class="col-xs-1" style="width:11%;"> <span class="small">Account Name</span>
			<input type="text" id="draccount_{{$num}}" name="account_name[]" value="{{$acdata->master_name}}" class="form-control acname" autocomplete="off" data-toggle="modal" data-target="#account_modal">
			<input type="hidden" name="account_id[]" id="draccountid_{{$num}}" value="{{$acdata->id}}">
			<input type="hidden" name="group_id[]" id="groupid_{{$num}}" value="{{($acdata->vat_assign==0)?$acdata->category:$acdata->vat_assign}}">
			<input type="hidden" name="vatamt[]" id="vatamt_{{$num}}" value="{{$acdata->vat_percentage}}">
			
			<input type="hidden" id="invoiceid_{{$num}}" name="sales_invoice_id[]">
			<input type="hidden" name="bill_type[]" id="biltyp_{{$num}}">
			</div>
			
				<div class="col-xs-2" style="width:11%;">
					<span class="small">Description</span> <input type="text" id="descr_{{$num}}" autocomplete="off" name="description[]" class="form-control">
				</div>
				<div class="col-xs-2" style="width:9%;">
					<span class="small">Reference</span> 
					<div id="refdata_1" class="refdata">
					<input type="text" id="ref_{{$num}}" name="reference[]" autocomplete="off" class="form-control">
					</div>
					<input type="hidden" name="inv_id[]" id="invid_{{$num}}">
					<input type="hidden" name="actual_amount[]" id="actamt_{{$num}}">
				</div>
				<div class="col-xs-1" style="width:6%;">
					<span class="small">Type</span> 
					<select id="acnttype_{{$num}}" class="form-control select2 line-type" style="width:100%;padding-left:5px;" name="account_type[]">
						@if($acdata->category=='PDCR')
						<option value="Dr">Dr</option>
						@else
						<option value="Cr">Cr</option>
						@endif
					</select>
				</div>
				<div class="col-xs-1" style="width:10%;">
					<span class="small">Amount</span> <input type="number" id="amount_{{$num}}" autocomplete="off" step="any" name="line_amount[]" class="form-control jvline-amount">
				</div>
				
				
				<div class="col-xs-1" style="width:8%;">
					<span class="small">Chq. No</span><input type="text" autocomplete="off" id="chkno_{{$num}}" name="cheque_no[]" class="form-control" >
				</div>
				<div class="col-xs-1" style="width:8%;">
					<span class="small">Chq. Date</span> <input type="text" autocomplete="off" id="chkdate_{{$num}}" name="cheque_date[]" class="form-control chqdate" data-language='en'>
				</div>
				<div class="col-xs-1" style="width:8%;">
					<span class="small">Bank</span> 
						<select id="bankid_{{$num}}" class="form-control select2 line-bank" style="width:100%" name="bank_id[]">
						<option value="">Bank...</option>
						@foreach($banks as $bank)
						<option value="{{$bank['id']}}">{{$bank['code']}}</option>
						@endforeach
					</select>
				</div>
				
				<div class="col-xs-1" style="width:8%;">
					<input type="hidden" name="partyac_id[]" id="partyac_{{$num}}">
						<span class="small">Pty. Name</span> <input type="text" id="party_{{$num}}" name="party_name[]" autocomplete="off" class="form-control" data-toggle="modal" data-target="#paccount_modal">
				</div>
				
				<div class="col-xs-1" style="width:8%;">
					<span class="small">Job</span> 
					<select id="jobid_{{$num}}" class="form-control select2 line-job" style="width:100%" name="job_id[]">
						<option value="">Job...</option>
						@foreach($jobs as $job)
						<option value="{{ $job['id'] }}">{{ $job['name'] }}</option>
						@endforeach
					</select>
				</div>
				
				<div class="col-xs-1" style="width:8%;">
					<span class="small">Dept.</span> 
					<select id="dept_{{$num}}" class="form-control select2 line-dept" style="width:100%" name="department[]">
						<option value="">Department...</option>
						@foreach($departments as $department)
						<option value="{{ $department->id }}">{{ $department->name }}</option>
						@endforeach
					</select>
				</div>
				<div class="col-xs-1 abc" style="width:5%;">
					<button type="button" class="btn-danger btn-remove-item" >
						<i class="fa fa-fw fa-minus-square"></i>
					 </button><br/>
					 <button type="button" class="btn-success btn-add-item" >
						<i class="fa fa-fw fa-plus-square"></i>
					 </button>
				</div>
		@else <!-- PDC DEPARTMENT NOT -->
		    <div class="col-sm-1 " style="width:1.55%;">
	        <input type="checkbox" id="tag_{{$num}}" name="tag[]" class="form-control tagchk" unchecked >
	        </div>
			<div class="col-xs-1" style="width:12%;"> <span class="small">Account Name</span>
			<input type="text" id="draccount_{{$num}}" name="account_name[]" value="{{$acdata->master_name}}" class="form-control acname" autocomplete="off" data-toggle="modal" data-target="#account_modal">
			<input type="hidden" name="account_id[]" id="draccountid_{{$num}}" value="{{$acdata->id}}">
			<input type="hidden" name="group_id[]" id="groupid_{{$num}}" value="{{($acdata->vat_assign==0)?$acdata->category:$acdata->vat_assign}}">
			<input type="hidden" name="vatamt[]" id="vatamt_{{$num}}" value="{{$acdata->vat_percentage}}">
			<input type="hidden" id="invoiceid_{{$num}}" name="sales_invoice_id[]">
			<input type="hidden" name="bill_type[]" id="biltyp_{{$num}}">
			</div>
			
				<div class="col-xs-2" style="width:12%;">
					<span class="small">Description</span> <input type="text" id="descr_{{$num}}" autocomplete="off" name="description[]" class="form-control">
				</div>
				<div class="col-xs-2" style="width:10%;">
					<span class="small">Reference</span> 
					<div id="refdata_1" class="refdata">
					<input type="text" id="ref_{{$num}}" name="reference[]" autocomplete="off" class="form-control">
					</div>
					<input type="hidden" name="inv_id[]" id="invid_{{$num}}">
					<input type="hidden" name="actual_amount[]" id="actamt_{{$num}}">
				</div>
				<div class="col-xs-1" style="width:7%;">
					<span class="small">Type</span> 
					<select id="acnttype_{{$num}}" class="form-control select2 line-type" style="width:100%;padding-left:5px;" name="account_type[]">
						@if($acdata->category=='PDCR')
						<option value="Dr">Dr</option>
						@else
						<option value="Cr">Cr</option>
						@endif
					</select>
				</div>
				<div class="col-xs-1" style="width:10%;">
					<span class="small">Amount</span> <input type="number" id="amount_{{$num}}" autocomplete="off" step="any" name="line_amount[]" class="form-control jvline-amount">
				</div>
				
				
				<div class="col-xs-1" style="width:8%;">
					<span class="small">Chq. No</span><input type="text" autocomplete="off" id="chkno_{{$num}}" name="cheque_no[]" class="form-control" >
				</div>
				<div class="col-xs-1" style="width:9%;">
					<span class="small">Chq. Date</span> <input type="text" autocomplete="off" id="chkdate_{{$num}}" name="cheque_date[]" class="form-control chqdate" data-language='en'>
				</div>
				
				<div class="col-xs-1" style="width:9%;">
					<span class="small">Bank</span> 
						<select id="bankid_{{$num}}" class="form-control select2 line-bank" style="width:100%" name="bank_id[]">
						<option value="">Bank...</option>
						@foreach($banks as $bank)
						<option value="{{$bank['id']}}">{{$bank['code']}}</option>
						@endforeach
					</select>
				</div>
				
				<div class="col-xs-1" style="width:9%;">
					<input type="hidden" name="partyac_id[]" id="partyac_{{$num}}">
						<span class="small">Pty. Name</span> <input type="text" id="party_{{$num}}" autocomplete="off" name="party_name[]" class="form-control" data-toggle="modal" data-target="#paccount_modal">
				</div>
				
				<div class="col-xs-1" style="width:9%;">
					<span class="small">Job</span> 
					<select id="jobid_{{$num}}" class="form-control select2 line-job" style="width:100%" name="job_id[]">
						<option value="">Job...</option>
						@foreach($jobs as $job)
						<option value="{{ $job['id'] }}">{{ $job['name'] }}</option>
						@endforeach
					</select>
				</div>
				
				<div class="col-xs-1 abc" style="width:5%;">
					<button type="button" class="btn-danger btn-remove-item" >
						<i class="fa fa-fw fa-minus-square"></i>
					 </button><br/>
					 <button type="button" class="btn-success btn-add-item" >
						<i class="fa fa-fw fa-plus-square"></i>
					 </button>
				</div>
		@endif
@else 
	@if($isdept)
	      <div class="col-sm-1 " style="width:1.55%;">
	      <input type="checkbox" id="tag_{{$num}}" name="tag[]" class="form-control tagchk" unchecked >
	      </div>
	<div class="col-sm-2"> <span class="small">Account Name</span>
		<input type="text" id="draccount_{{$num}}" name="account_name[]" value="{{$acdata->master_name}}" class="form-control acname" autocomplete="off" data-toggle="modal" data-target="#account_modal">
		<input type="hidden" name="account_id[]" id="draccountid_{{$num}}" value="{{$acdata->id}}">
		<input type="hidden" name="group_id[]" id="groupid_{{$num}}" value="{{($acdata->vat_assign==0)?$acdata->category:$acdata->vat_assign}}">
		<input type="hidden" name="vatamt[]" id="vatamt_{{$num}}" value="{{$acdata->vat_percentage}}">
		
		<input type="hidden" id="invoiceid_{{$num}}" name="sales_invoice_id[]">
		<input type="hidden" name="bill_type[]" id="biltyp_{{$num}}">
		</div>
			<div class="col-xs-3" style="width:20%;">
				<span class="small">Description</span> <input type="text" id="descr_{{$num}}" autocomplete="off" name="description[]" class="form-control">
			</div>
			<div class="col-xs-2" style="width:12%;">
				<span class="small">Reference</span> 
				<div id="refdata_1" class="refdata">
				<input type="text" id="ref_{{$num}}" name="reference[]" autocomplete="off" class="form-control">
				</div>
				<input type="hidden" name="inv_id[]" id="invid_{{$num}}">
				<input type="hidden" name="actual_amount[]" id="actamt_{{$num}}">
			</div>
			<div class="col-xs-1" style="width:7%;">
				<span class="small">Type</span> 
				<select id="acnttype_{{$num}}" class="form-control select2 line-type" style="width:100%;padding-left:5px;" name="account_type[]">
					<option value="Dr">Dr</option>
					<option value="Cr">Cr</option>
				</select>
			</div>
			<div class="col-xs-2" style="width:12%;">
				<span class="small">Amount</span> <input type="number" id="amount_{{$num}}" autocomplete="off" step="any" name="line_amount[]" class="form-control jvline-amount">
			</div>
			
			
			
			<div class="col-xs-3" style="width:13%;">
				<span class="small">Department</span> 
				<select id="dept_{{$num}}" class="form-control select2 line-dept" style="width:100%" name="department[]">
					<option value="">Department...</option>
					@foreach($departments as $department)
					<option value="{{ $department->id }}">{{ $department->name }}</option>
					@endforeach
				</select>
			</div>
			
			<div class="col-xs-1 abc" style="width:3%;"><br/>
				<button type="button" class="btn-danger btn-remove-item" >
					<i class="fa fa-fw fa-minus-square"></i>
				 </button>
				 <button type="button" class="btn-success btn-add-item" >
					<i class="fa fa-fw fa-plus-square"></i>
				 </button>
			</div>
			
			<div id="chqdtl_{{$num}}" class="divchq" style="display:none;">
				<div class="col-sm-2"> 
					<span class="small">Cheque No</span><input type="text" autocomplete="off" id="chkno_{{$num}}" name="cheque_no[]" class="form-control" >
				</div>
				
				<div class="col-xs-2">
					<span class="small">Cheque Date</span> <input type="text" autocomplete="off" id="chkdate_{{$num}}" name="cheque_date[]" class="form-control chqdate" data-language='en'>
				</div>
				
				<div class="col-xs-2">
					<span class="small">Bank</span> 
					<select id="bankid_{{$num}}" class="form-control select2 line-bank" style="width:100%" name="bank_id[]">
						<option value="">Select Bank...</option>
						@foreach($banks as $bank)
						<option value="{{$bank['id']}}">{{$bank['code'].' - '.$bank['name']}}</option>
						@endforeach
					</select>
				</div>
				
				<div class="col-xs-2">
					<input type="hidden" name="partyac_id[]" id="partyac_{{$num}}">
					<span class="small">Party Name</span> <input type="text" id="party_1" name="party_name[]" autocomplete="off" class="form-control" data-toggle="modal" data-target="#paccount_modal">
				</div>
				
	</div>
	
		<div class="col-sm-2" style="width:15%;"> 
				<span class="small">Job</span> 
				<select id="jobid_{{$num}}" class="form-control select2 line-job" style="width:100%" name="job_id[]">
					<option value="">Select Job...</option>
					@foreach($jobs as $job)
					<option value="{{ $job['id'] }}">{{ $job['name'] }}</option>
					@endforeach
				</select>
			</div>
			
	@else
	<div class="col-sm-1 " style="width:1.55%;">
	<input type="checkbox" id="tag_{{$num}}" name="tag[]" class="form-control tagchk" unchecked >
	</div>
	<div class="col-sm-2"> <span class="small">Account Name</span>
		<input type="text" id="draccount_{{$num}}" name="account_name[]" value="" class="form-control acname" autocomplete="off" data-toggle="modal" data-target="#account_modal">
		<input type="hidden" name="account_id[]" id="draccountid_{{$num}}" value="">
		<input type="hidden" name="group_id[]" id="groupid_{{$num}}" value="">
		<input type="hidden" name="vatamt[]" id="vatamt_{{$num}}" value="">
		
		<input type="hidden" id="invoiceid_{{$num}}" name="sales_invoice_id[]">
		<input type="hidden" name="bill_type[]" id="biltyp_{{$num}}">
		</div>
			<div class="col-xs-3" style="width:25%;">
				<span class="small">Description</span> <input type="text" id="descr_{{$num}}" autocomplete="off" name="description[]" class="form-control">
			</div>
			<div class="col-xs-2" style="width:15%;">
				<span class="small">Reference</span> 
				<div id="refdata_{{$num}}" class="refdata">
				<input type="text" id="ref_{{$num}}" name="reference[]" autocomplete="off" class="form-control">
				</div>
				<input type="hidden" name="inv_id[]" id="invid_{{$num}}">
				<input type="hidden" name="actual_amount[]" id="actamt_{{$num}}">
			</div>
			<div class="col-xs-1" style="width:8%;">
				<span class="small">Type</span> 
				<select id="acnttype_{{$num}}" class="form-control select2 line-type" style="width:100%;padding-left:5px;" name="account_type[]">
					<option value="Dr">Dr</option>
					<option value="Cr">Cr</option>
				</select>
			</div>
			<div class="col-xs-2" style="width:13%;">
				<span class="small">Amount</span> <input type="number" id="amount_{{$num}}" autocomplete="off" step="any" name="line_amount[]" class="form-control jvline-amount">
			</div>
			
			
			
			<div id="chqdtl_{{$num}}" class="divchq" style="display:none;">
				
				<div class="col-sm-2"> 
					<span class="small">Cheque No</span><input type="text" autocomplete="off" id="chkno_1" name="cheque_no[]" class="form-control" >
				</div>
				
				<div class="col-xs-2">
					<span class="small">Cheque Date</span> <input type="text" autocomplete="off" id="chkdate_1" name="cheque_date[]" class="form-control chqdate" data-language='en'>
				</div>
				
				<div class="col-xs-2">
					<span class="small">Bank</span> 
					<select id="bankid_1" class="form-control select2 line-bank" style="width:100%" name="bank_id[]">
						<option value="">Select Bank...</option>
						@foreach($banks as $bank)
						<option value="{{$bank['id']}}">{{$bank['code'].' - '.$bank['name']}}</option>
						@endforeach
					</select>
				</div>
				
				<div class="col-xs-2">
					<input type="hidden" name="partyac_id[]" id="partyac_1">
					<span class="small">Party Name</span> <input type="text" id="party_1" autocomplete="off" name="party_name[]" class="form-control" data-toggle="modal" data-target="#paccount_modal">
				</div>
				
		</div>
		
		<div class="col-sm-2" style="width:17%;"> 
				<span class="small">Job</span> 
				<select id="jobid_{{$num}}" class="form-control select2 line-job" style="width:100%" name="job_id[]">
					<option value="">Select Job...</option>
					@foreach($jobs as $job)
					<option value="{{ $job['id'] }}">{{ $job['name'] }}</option>
					@endforeach
				</select>
			</div>
			
						
			<div class="col-xs-1 abc" style="width:3%;"><br/>
				<button type="button" class="btn-danger btn-remove-item" >
					<i class="fa fa-fw fa-minus-square"></i>
				 </button>
				 <button type="button" class="btn-success btn-add-item" >
					<i class="fa fa-fw fa-plus-square"></i>
				 </button>
			</div>
	@endif		
@endif
<script>
$('.chqdate').datepicker({
	language: 'en',
	dateFormat: 'dd-mm-yyyy',
	autoClose: 1
});
</script>