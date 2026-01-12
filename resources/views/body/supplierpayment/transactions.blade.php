@if($type=='PDC')
		@if($isdept)
			<div class="col-xs-1" style="width:11%;"> <span class="small">Account Name</span>
			<input type="text" id="draccount_{{$num}}" name="account_name[]" value="{{($accounts)?$accounts->master_name:''}}" class="form-control acname" {{--autocomplete="off" data-toggle="modal" data-target="#account_modal"--}}>
			<input type="hidden" name="account_id[]" id="draccountid_{{$num}}" value="{{($accounts)?$accounts->id:''}}">
			<input type="hidden" name="group_id[]" id="groupid_{{$num}}" value="{{($accounts)?$accounts->category:''}}">
			<input type="hidden" name="vatamt[]" id="vatamt_{{$num}}" value="">
			<input type="hidden" name="je_id[]" id="jeid_{{$num}}" value="{{$jeid}}">
			<input type="hidden" id="invoiceid_{{$num}}" name="purchase_invoice_id[]">
			<input type="hidden" name="bill_type[]" id="biltyp_{{$num}}">
			</div>
			
				<div class="col-xs-2" style="width:11%;">
					<span class="small">Description</span> <input type="text" id="descr_{{$num}}" autocomplete="off" name="description[]" class="form-control">
				</div>
				<div class="col-xs-2" style="width:9%;">
					<span class="small">Reference</span> 
					<div id="refdata_{{$num}}" class="refdata">
					<input type="text" id="ref_{{$num}}" name="reference[]" autocomplete="off" class="form-control">
					</div>
					<input type="hidden" name="inv_id[]" id="invid_{{$num}}">
					<input type="hidden" name="actual_amount[]" id="actamt_{{$num}}">
				</div>
				<div class="col-sm-1" style="width:6%;">
					<span class="small">Type</span> 
					<select id="acnttype_{{$num}}" class="form-control select2 line-type" style="width:100%;padding-left:5px;" name="account_type[]">
						<option value="Cr">Cr</option>
					</select>
				</div>
				<div class="col-xs-1" style="width:10%;">
					<span class="small">Amount</span> <input type="number" id="amount_{{$num}}" autocomplete="off" step="any" name="line_amount[]" class="form-control jvline-amount">
				</div>
				
				<div class="col-xs-1 pdcfm" style="width:8%;">
					<span class="small">Bank</span> 
						<select id="bankid_{{$num}}" class="form-control select2 line-bank" style="width:100%" required name="bank_id[]">
						<option value="">Bank...</option>
						@foreach($banks as $bank)
						<option value="{{$bank['id']}}">{{$bank['code']}}</option>
						@endforeach
					</select>
				</div>

				<div class="col-xs-1 pdcfm" style="width:8%;">
					<span class="small">Chq. No</span><input type="text" autocomplete="off" id="chkno_{{$num}}" required name="cheque_no[]" class="form-control" >
				</div>
				<div class="col-xs-1 pdcfm" style="width:8%;">
					<span class="small">Chq. Date</span> <input type="text" autocomplete="off" id="chkdate_{{$num}}" required name="cheque_date[]" class="form-control chqdate" data-language='en'>
				</div>
				
				
				<div class="col-xs-1 pdcfm" style="width:8%;">
					<input type="hidden" name="partyac_id[]" id="partyac_{{$num}}">
						<span class="small">Pty. Name</span> <input type="text" id="party_{{$num}}" name="party_name[]" autocomplete="off" class="form-control" required data-toggle="modal" data-target="#paccount_modal">
				</div>

				<div class="col-xs-1" style="width:8%;">
					<span class="small">Job</span> 
					<input type="hidden" name="job_id[]" id="jobid_{{$num}}" >
					<input type="text" id="jobcod_{{$num}}" autocomplete="off" name="jobcod[]" class="form-control"  autocomplete="off" data-toggle="modal" data-target="#job_modal" placeholder="Jobcode">
				</div>
				
				<div class="col-sm-1" style="width:8%;">
					<span class="small">Dept.</span> 
					<select id="dept_{{$num}}" class="form-control select2 line-dept" style="width:100%" name="department[]">
						<option value="">Department...</option>
						@foreach($departments as $department)
						<option value="{{ $department->id }}">{{ $department->name }}</option>
						@endforeach
					</select>
				</div>
				<div class="col-xm-1 abc" style="width:5%;">
					<button type="button" class="btn-danger btn-remove-item" >
						<i class="fa fa-fw fa-minus-square"></i>
					 </button><br/>
					 <button type="button" class="btn-success btn-add-item" >
						<i class="fa fa-fw fa-plus-square"></i>
					 </button>
				</div>
		@else <!-- PDC DEPARTMENT NOT -->
		<div class="col-xs-1 nopdc1" style="width:12%;"> <span class="small">Account Name</span>
			<input type="text" id="draccount_{{$num}}" name="account_name[]" value="{{($accounts)?$accounts->master_name:''}}" class="form-control acname" readonly {{--autocomplete="off" data-toggle="modal" data-target="#account_modal"--}}>
			<input type="hidden" name="account_id[]" id="draccountid_{{$num}}" value="{{($accounts)?$accounts->id:''}}">
			<input type="hidden" name="group_id[]" id="groupid_{{$num}}" value="{{($accounts)?$accounts->category:''}}">
			<input type="hidden" name="vatamt[]" id="vatamt_{{$num}}" value="">
			<input type="hidden" id="invoiceid_{{$num}}" name="purchase_invoice_id[]">
			<input type="hidden" name="bill_type[]" id="biltyp_{{$num}}">
			<input type="hidden" name="je_id[]" id="jeid_{{$num}}" value="{{$jeid}}">
			</div>
			
				<div class="col-xs-2 nopdc2" style="width:12%;">
					<span class="small">Description</span> <input type="text" id="descr_{{$num}}" placeholder="Description" autocomplete="off" name="description[]" class="form-control">
				</div>
				<div class="col-xs-2 nopdc3" style="width:10%;">
					<span class="small">Reference</span> 
					<div id="refdata_{{$num}}" class="refdata">
					<input type="text" id="ref_{{$num}}" name="reference[]" autocomplete="off" class="form-control">
					</div>
					<input type="hidden" name="inv_id[]" id="invid_{{$num}}">
					<input type="hidden" name="actual_amount[]" id="actamt_{{$num}}">
				</div>
				
				<div class="col-xs-1 nopdc5" style="width:10%;">
					<span class="small">Amount</span> <input type="number" id="amount_{{$num}}" autocomplete="off" step="any" name="line_amount[]" class="form-control jvline-amount">
				</div>
				
				<div class="col-sm-1 nopdc4" style="width:7%;">
					<span class="small">Type</span> 
					<select id="acnttype_{{$num}}" class="form-control select2 line-type" style="width:100%;padding-left:5px;" name="account_type[]">
						<option value="Cr">Cr</option>
					</select>
				</div>

				<div class="col-xs-1 pdcfm" style="width:9%;">
					<span class="small">Bank</span> 
						<select id="bankid_{{$num}}" class="form-control select2 line-bank" style="width:100%" required name="bank_id[]">
						<option value="">Bank...</option>
						@foreach($banks as $bank)
						<option value="{{$bank['id']}}">{{$bank['code']}}</option>
						@endforeach
					</select>
				</div>
				
				<div class="col-xs-1 pdcfm" style="width:8%;">
					<span class="small">Chq. No</span><input type="text" autocomplete="off" id="chkno_{{$num}}" name="cheque_no[]" required class="form-control" >
				</div>
				<div class="col-xs-1 pdcfm" style="width:9%;">
					<span class="small">Chq. Date</span> <input type="text" autocomplete="off" id="chkdate_{{$num}}" name="cheque_date[]" required class="form-control chqdate" data-language='en'>
				</div>
				
				<div class="col-xs-1 pdcfm" style="width:9%;">
					<input type="hidden" name="partyac_id[]" id="partyac_{{$num}}">
						<span class="small">Pty. Name</span> <input type="text" id="party_{{$num}}" autocomplete="off" required name="party_name[]" class="form-control" data-toggle="modal" data-target="#paccount_modal">
				</div>
				
				<div class="col-xs-1 nopdc6" style="width:9%;">
					<span class="small">Job</span> 
					<input type="hidden" name="job_id[]" id="jobid_{{$num}}" >
					<input type="text" id="jobcod_{{$num}}" autocomplete="off" name="jobcod[]" class="form-control"  autocomplete="off" data-toggle="modal" data-target="#job_modal" placeholder="Jobcode">
				</div>
				
				<div class="col-sm-1 abc" style="width:5%;"><br/>
					<button type="button" class="btn-danger btn-remove-item" >
						<i class="fa fa-fw fa-minus-square"></i>
					 </button><br/>
				</div>
		@endif
@elseif($type=='Bank')
	@if($isdept)
			<div class="col-xs-1" style="width:11%;"> <span class="small">Account Name</span>
			<input type="text" id="draccount_{{$num}}" name="account_name[]" value="{{($accounts)?$accounts->master_name:''}}" class="form-control acname" {{--autocomplete="off" data-toggle="modal" data-target="#account_modal"--}}>
			<input type="hidden" name="account_id[]" id="draccountid_{{$num}}" value="{{($accounts)?$accounts->id:''}}">
			<input type="hidden" name="group_id[]" id="groupid_{{$num}}" value="{{($accounts)?$accounts->category:''}}">
			<input type="hidden" name="vatamt[]" id="vatamt_{{$num}}" value="">
			<input type="hidden" name="je_id[]" id="jeid_{{$num}}" value="{{$jeid}}">
			<input type="hidden" id="invoiceid_{{$num}}" name="purchase_invoice_id[]">
			<input type="hidden" name="bill_type[]" id="biltyp_{{$num}}">
			<input type="hidden" name="party_name[]"><input type="hidden" name="partyac_id[]">
			</div>
			
				<div class="col-xs-2" style="width:16%;">
					<span class="small">Description</span> <input type="text" id="descr_{{$num}}" placeholder="Description" autocomplete="off" name="description[]" class="form-control">
				</div>
				<div class="col-xs-2" style="width:11%;">
					<span class="small">Reference</span> 
					<div id="refdata_{{$num}}" class="refdata">
					<input type="text" id="ref_{{$num}}" name="reference[]" autocomplete="off" class="form-control">
					</div>
					<input type="hidden" name="inv_id[]" id="invid_{{$num}}">
					<input type="hidden" name="actual_amount[]" id="actamt_{{$num}}">
				</div>
				
				<div class="col-xs-1" style="width:10%;">
					<span class="small">Amount</span> <input type="number" id="amount_{{$num}}" autocomplete="off" step="any" name="line_amount[]" class="form-control jvline-amount">
				</div>
				
				<div class="col-sm-1" style="width:6%;">
					<span class="small">Type</span> 
					<select id="acnttype_{{$num}}" class="form-control select2 line-type" style="width:100%;padding-left:5px;" name="account_type[]">
						<option value="Cr">Cr</option>
					</select>
				</div>

			{{--	<div class="col-xs-1 pdcfm" style="width:8%;">
					<span class="small">Bank</span> 
						<select id="bankid_{{$num}}" class="form-control select2 line-bank" style="width:100%" required name="bank_id[]">
						<option value="">Bank...</option>
						@foreach($banks as $bank)
						<option value="{{$bank['id']}}">{{$bank['code']}}</option>
						@endforeach
					</select>
				</div> --}}

				<div class="col-xs-1 pdcfm" style="width:8%;">
					<span class="small">Chq. No</span><input type="text" autocomplete="off" id="chkno_{{$num}}" required name="cheque_no[]" class="form-control" >
				</div>
				<div class="col-xs-1 pdcfm" style="width:8%;">
					<span class="small">Chq. Date</span> <input type="text" autocomplete="off" id="chkdate_{{$num}}" required name="cheque_date[]" class="form-control chqdate" data-language='en'>
				</div>

				<div class="col-xs-1" style="width:9%;">
					<span class="small">Job</span> 
					<input type="hidden" name="job_id[]" id="jobid_{{$num}}" >
					<input type="text" id="jobcod_{{$num}}" autocomplete="off" name="jobcod[]" class="form-control"  autocomplete="off" data-toggle="modal" data-target="#job_modal" placeholder="Jobcode">
				</div>
				
				<div class="col-sm-1" style="width:8%;">
					<span class="small">Dept.</span> 
					<select id="dept_{{$num}}" class="form-control select2 line-dept" style="width:100%" name="department[]">
						<option value="">Department...</option>
						@foreach($departments as $department)
						<option value="{{ $department->id }}">{{ $department->name }}</option>
						@endforeach
					</select>
				</div>
				<div class="col-xm-1 abc" style="width:5%;">
					<button type="button" class="btn-danger btn-remove-item" >
						<i class="fa fa-fw fa-minus-square"></i>
					 </button><br/>
					 <button type="button" class="btn-success btn-add-item" >
						<i class="fa fa-fw fa-plus-square"></i>
					 </button>
				</div>
		@else <!-- PDC DEPARTMENT NOT -->
		<div class="col-sm-2 nopdc1"> <span class="small">Account Name</span>
			<input type="text" id="draccount_{{$num}}" name="account_name[]" value="{{($accounts)?$accounts->master_name:''}}" class="form-control acname" readonly {{--autocomplete="off" data-toggle="modal" data-target="#account_modal" --}}>
			<input type="hidden" name="account_id[]" id="draccountid_{{$num}}" value="{{($accounts)?$accounts->id:''}}">
			<input type="hidden" name="group_id[]" id="groupid_{{$num}}" value="{{($accounts)?$accounts->category:''}}">
			<input type="hidden" name="vatamt[]" id="vatamt_{{$num}}" value="">
			<input type="hidden" id="invoiceid_{{$num}}" name="purchase_invoice_id[]">
			<input type="hidden" name="bill_type[]" id="biltyp_{{$num}}">
			<input type="hidden" name="je_id[]" id="jeid_{{$num}}" value="{{$jeid}}">
			<input type="hidden" name="party_name[]"><input type="hidden" name="partyac_id[]">
			</div>
			
				<div class="col-sm-2 nopdc2" style="width:21%;">
					<span class="small">Description</span> <input type="text" id="descr_{{$num}}" autocomplete="off" name="description[]" placeholder="Description" class="form-control">
				</div>
				<div class="col-xs-2 nopdc3" style="width:13%;">
					<span class="small">Reference</span> 
					<div id="refdata_{{$num}}" class="refdata">
					<input type="text" id="ref_{{$num}}" name="reference[]" autocomplete="off" class="form-control">
					</div>
					<input type="hidden" name="inv_id[]" id="invid_{{$num}}">
					<input type="hidden" name="actual_amount[]" id="actamt_{{$num}}">
				</div>
				
				<div class="col-xs-1 nopdc5" style="width:10%;">
					<span class="small">Amount</span> <input type="number" id="amount_{{$num}}" autocomplete="off" step="any" name="line_amount[]" class="form-control jvline-amount">
				</div>
				
				<div class="col-sm-1 nopdc4" style="width:7%;">
					<span class="small">Type</span> 
					<select id="acnttype_{{$num}}" class="form-control select2 line-type" style="width:100%;padding-left:5px;" name="account_type[]">
						<option value="Cr">Cr</option>
					</select>
				</div>
				
			{{--	<div class="col-xs-1 pdcfm" style="width:9%;">
					<span class="small">Bank</span> 
						<select id="bankid_{{$num}}" class="form-control select2 line-bank" style="width:100%" required name="bank_id[]">
						<option value="">Bank...</option>
						@foreach($banks as $bank)
						<option value="{{$bank['id']}}">{{$bank['code']}}</option>
						@endforeach
					</select>
				</div> --}}
				
				<div class="col-xs-1 pdcfm" style="width:8%;">
					<span class="small">Chq. No</span><input type="text" autocomplete="off" id="chkno_{{$num}}" name="cheque_no[]" required class="form-control" >
				</div>
				<div class="col-xs-1 pdcfm" style="width:9%;">
					<span class="small">Chq. Date</span> <input type="text" autocomplete="off" id="chkdate_{{$num}}" name="cheque_date[]" required class="form-control chqdate" data-language='en'>
				</div>
				
				<div class="col-xs-1 nopdc6" style="width:10%;">
					<span class="small">Job</span> 
					<input type="hidden" name="job_id[]" id="jobid_{{$num}}" >
					<input type="text" id="jobcod_{{$num}}" autocomplete="off" name="jobcod[]" class="form-control"  autocomplete="off" data-toggle="modal" data-target="#job_modal" placeholder="Jobcode">
				</div>
				
				<div class="col-sm-1 abc" style="width:5%;"><br/>
					<button type="button" class="btn-danger btn-remove-item" >
						<i class="fa fa-fw fa-minus-square"></i>
					 </button><br/>
				</div>
		@endif
@endif
<script>
$(document).ready(function () {
	
	$('#frmSupPayment').bootstrapValidator('addField',"bank_id[]");
	$('#frmSupPayment').bootstrapValidator('addField',"cheque_no[]"); 
	$('#frmSupPayment').bootstrapValidator('addField',"cheque_date[]");
	$('#frmSupPayment').bootstrapValidator('addField',"party_name[]");
	$('#frmSupPayment').bootstrapValidator('addField',"account_name[]");
	
	$('.chqdate').datepicker({
		language: 'en',
		dateFormat: 'dd-mm-yyyy',
		autoClose: 1
	});
	
});

</script>