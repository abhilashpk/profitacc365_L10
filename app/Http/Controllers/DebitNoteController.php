<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Bank\BankInterface;
use App\Repositories\Currency\CurrencyInterface;
use App\Repositories\VoucherNo\VoucherNoInterface;
use App\Repositories\Jobmaster\JobmasterInterface;
use App\Repositories\Department\DepartmentInterface;
use App\Repositories\DebitNote\DebitNoteInterface;
use App\Repositories\AccountSetting\AccountSettingInterface;
use App\Repositories\ReceiptVoucher\ReceiptVoucherInterface;
use App\Repositories\PaymentVoucher\PaymentVoucherInterface;

use App\Http\Requests;
use Input;
use Session;
use Response;
use Validator;
use Auth;
use DB;
use App;

class DebitNoteController extends Controller
{
    protected $bank;
	protected $currency;
	protected $voucherno;
	protected $jobmaster;
	protected $department;
	protected $debit_note;
	protected $accountsetting;
	protected $receipt_voucher;
	protected $payment_voucher;
	
	public function __construct(AccountSettingInterface $accountsetting, DebitNoteInterface $debit_note, ReceiptVoucherInterface $receipt_voucher, PaymentVoucherInterface $payment_voucher, BankInterface $bank, CurrencyInterface $currency, VoucherNoInterface $voucherno, JobmasterInterface $jobmaster, DepartmentInterface $department) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		
		$this->middleware('auth');
		$this->bank = $bank;
		$this->currency = $currency;
		$this->voucherno = $voucherno;
		$this->jobmaster = $jobmaster;
		$this->department = $department;
		$this->debit_note = $debit_note;
		$this->accountsetting = $accountsetting;
		$this->receipt_voucher = $receipt_voucher;
		$this->payment_voucher = $payment_voucher;
	}
	
	public function index() {
		$data = array();
		$debit_notes = $this->debit_note->DebitNoteList();//echo '<pre>';print_r($debit_notes);exit;
		$prints = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','DNE')
							->select('report_view_detail.name','report_view_detail.id')
							->get();
		return view('body.debitnote.index')
					->withCredits($debit_notes)
					->withPrints($prints)
					->withData($data);
	}
	
	public function add() {

		$data = array();
		//$vouchers = $this->accountsetting->getAccountSettingsDefault2($vid=8); //echo '<pre>';print_r($vouchers);exit;
		$currency = $this->currency->activeCurrencyList();
		$banks = $this->bank->activeBankList();
		$jobs = $this->jobmaster->activeJobmasterList();
		$departments = $this->department->activeDepartmentList();
		$account = $this->accountsetting->getExpenseAccount();
		
		//CHECK DEPARTMENT.......
		if(Session::get('department')==1) { //if active...
			$deptid = Auth::user()->department_id;
			if($deptid!=0)
				$departments = DB::table('department')->where('id',$deptid)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
			else {
				$departments = DB::table('department')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
				$deptid = $departments[0]->id;
			}
			$is_dept = true;
		} else {
			$is_dept = false;
			$departments = [];
			$deptid = '';
		}
		$vouchers = $this->accountsetting->getAccountSettingsDefault2($vid=8,$is_dept,$deptid);
		
		return view('body.debitnote.add')
					->withCurrency($currency)
					->withBanks($banks)
					->withJobs($jobs)
					->withDepartments($departments)
					->withAccount($account)
					->withVouchers($vouchers)
					->withIsdept($is_dept)
					->withDepartments($departments)
					->withDeptid($deptid)
					->withSettings($this->acsettings)
					->withData($data);
	}
	
	public function save(Request $request) {
		//echo '<pre>';print_r($request->all());exit;
		$validator = Validator::make($request->all(), [
            'voucher_no' => 'required|max:255',
			'debit' => 'required|same:credit'
        ]);
		
		if ($validator->fails()) {
            return redirect('debit_note/add')
                        ->withErrors($validator)
                        ->withInput();
        }
			
		if( $this->debit_note->create(Input::all() ))
			Session::flash('message', 'Debit note voucher added successfully.');
		else 
			Session::flash('error', 'Something went wrong, debit_note voucher failed to add!');
		
		return redirect('debit_note/add');
		
	}
	

	public function edit($id) { 

		$data = array();
		$currency = $this->currency->activeCurrencyList();
		$banks = $this->bank->activeBankList();
		$jobs = $this->jobmaster->activeJobmasterList();
		$departments = $this->department->activeDepartmentList();
						
		$cnrow = $this->debit_note->findCN($id);
		$cnitems = $this->debit_note->findCNdata($id);
		
		//CHECK DEPARTMENT.......
		if(Session::get('department')==1) { //if active...
			$deptid = Auth::user()->department_id;
			if($deptid!=0)
				$departments = DB::table('department')->where('id',$deptid)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
			else {
				$departments = DB::table('department')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
				$deptid = $cnrow->department_id;
			}
			$is_dept = true;
		} else {
			$is_dept = false;
			$departments = [];
			$deptid = '';
		}
		$vouchers = $this->accountsetting->getAccountSettingsDefault2($vid=8,$is_dept,$deptid);
		return view('body.debitnote.edit')
					->withCnrow($cnrow)
					->withCurrency($currency)
					->withBanks($banks)
					->withJobs($jobs)
					->withCnitems($cnitems)
					->withDepartments($departments)
					->withVouchers($vouchers)
					->withIsdept($is_dept)
					->withDepartments($departments)
					->withDeptid($deptid)
					->withSettings($this->acsettings)
					->withData($data);
	}
	
	
	public function update(Request $request,$id)
	{
		$validator = Validator::make($request->all(), [
            'voucher_no' => 'required|max:255',
			'debit' => 'required|same:credit'
        ]);
		
		if ($validator->fails()) {
            return redirect('debit_note/edit/'.$id)
                        ->withErrors($validator)
                        ->withInput();
        }
		
					
		$this->debit_note->update($id, Input::all());
		Session::flash('message', 'Debit note updated successfully');
		return redirect('debit_note');
	}
	
	public function destroy($id)
	{
		if( $this->debit_note->delete($id) ) { 
			Session::flash('message', 'Debit note deleted successfully.');
			return redirect('debit_note');
		}
	}
	
	public function getVoucher($id) {
		
		 $row = $this->accountsetting->getDrVoucherByID($id);
		 if($row->voucher_no != '' || $row->voucher_no != null) {
			 if($row->is_prefix==0)
				 $voucher = $row->voucher_no;
			 else {
				 $no = (int)$row->voucher_no;
				 $voucher = $row->prefix.''.$no;
			 }
			 echo $voucher;
		 }
		 
		/*  $row = $this->voucherno->getVoucherNo($id);
		 if($row['no'] != '' || $row['no'] != null) {
			echo $no = $row['no']+1;
		 } else if($row['no'] == 0) {
			echo $no = 1;
		 } */

	}
	
	public function getVoucherType($id) {
		
		return $row = $this->accountsetting->getAccountSettings($id);
		 
	}
	
	public function checkVchrNo() {

		$check = $this->debit_note->check_voucher_no(Input::get('voucher_no'), Input::get('vtype'), Input::get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
	
	public function getPrint($id,$rid=null)
	{ 
		
        $viewfile = DB::table('report_view_detail')->where('id', $rid)->select('print_name')->first(); 
			
		if($viewfile->print_name=='') {
			$fc='';
			$attributes['document_id'] = $id; //echo "892 : ".$this->number_to_word(12495);exit;
			$attributes['is_fc'] = ($fc)?1:'';
		$voucherhead = 'Debit Note Voucher';
		$cnrow = $this->debit_note->findCN($id);
		$cnitems = $this->debit_note->findCNdata($id); //echo '<pre>';print_r($jerow);exit;

		return view('body.debitnote.print')
					->withVoucherhead($voucherhead)
					->withDetails($cnrow)
					->withInvoicerow($cnitems);
		}else {
					
			$path = app_path() . '/stimulsoft/helper.php';
			return view('body.debitnote.viewer')->withPath($path)->withView($viewfile->print_name);
		}
	}
	
	public function getDeptVoucher($id) {
		
		$depts = $this->accountsetting->getVoucherByDeptDN($vid=8, $id); 
		
		foreach($depts as $row) {
			 if($row->voucher_no != '' || $row->voucher_no != null) {
				 if($row->is_prefix==0)
					 $voucher = $row->voucher_no;
				 else {
					 $no = (int)$row->voucher_no;
					 $voucher = $row->prefix.''.$no;
				 }
			 }
			 
		  $result[] = array('voucher_no' => $voucher, 
							'cr_account_name' => ($row->master_name!='')?$row->master_name:'', 
							'cr_id' => ($row->cr_account_master_id!='')?$row->cr_account_master_id:'',
							'voucher_name' => $row->voucher_name,
							'voucher_id' => $row->voucher_id
						);

		}
		
		return $result;
	}
}

