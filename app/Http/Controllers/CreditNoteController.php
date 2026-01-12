<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Bank\BankInterface;
use App\Repositories\Currency\CurrencyInterface;
use App\Repositories\VoucherNo\VoucherNoInterface;
use App\Repositories\Jobmaster\JobmasterInterface;
use App\Repositories\Department\DepartmentInterface;
use App\Repositories\CreditNote\CreditNoteInterface;
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

class CreditNoteController extends Controller
{
    protected $bank;
	protected $currency;
	protected $voucherno;
	protected $jobmaster;
	protected $department;
	protected $credit_note;
	protected $accountsetting;
	protected $receipt_voucher;
	protected $payment_voucher;
	
	public function __construct(AccountSettingInterface $accountsetting, CreditNoteInterface $credit_note, ReceiptVoucherInterface $receipt_voucher, PaymentVoucherInterface $payment_voucher, BankInterface $bank, CurrencyInterface $currency, VoucherNoInterface $voucherno, JobmasterInterface $jobmaster, DepartmentInterface $department) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->middleware('auth');
		$this->bank = $bank;
		$this->currency = $currency;
		$this->voucherno = $voucherno;
		$this->jobmaster = $jobmaster;
		$this->department = $department;
		$this->credit_note = $credit_note;
		$this->accountsetting = $accountsetting;
		$this->receipt_voucher = $receipt_voucher;
		$this->payment_voucher = $payment_voucher;
	}
	
	public function index() {
		$data = array();
		$credit_notes = $this->credit_note->CreditNoteList();//echo '<pre>';print_r($credit_notes);exit;
		$prints = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','CNE')
							->select('report_view_detail.name','report_view_detail.id')
							->get();
		return view('body.creditnote.index')
					->withCredits($credit_notes)
					->withPrints($prints)
					->withData($data);
	}
	
	public function add() {

		$data = array();
		$vouchers = $this->accountsetting->getAccountSettingsDefault2($vid=7); //echo '<pre>';print_r($vouchers);exit;
		$currency = $this->currency->activeCurrencyList();
		$banks = $this->bank->activeBankList();
		$jobs = $this->jobmaster->activeJobmasterList();
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
		$vouchers = $this->accountsetting->getAccountSettingsDefault2($vid=7,$is_dept,$deptid);
		//echo '<pre>';print_r($vouchers);exit;
		return view('body.creditnote.add')
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
            return redirect('credit_note/add')
                        ->withErrors($validator)
                        ->withInput();
        }
			
		if( $this->credit_note->create(Input::all() ))
			Session::flash('message', 'Credit note voucher added successfully.');
		else 
			Session::flash('error', 'Something went wrong, credit_note voucher failed to add!');
		
		return redirect('credit_note/add');
		
	}
	

	public function edit($id) { 

		$data = array();
		$currency = $this->currency->activeCurrencyList();
		$banks = $this->bank->activeBankList();
		$jobs = $this->jobmaster->activeJobmasterList();
		$departments = $this->department->activeDepartmentList();
						
		$cnrow = $this->credit_note->findCN($id);
		$cnitems = $this->credit_note->findCNdata($id);
		//echo '<pre>';print_r($cnitems);exit;
		
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
		$vouchers = $this->accountsetting->getAccountSettingsDefault2($vid=7,$is_dept,$deptid);
		
		return view('body.creditnote.edit')
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
	{	//echo '<pre>';print_r($request->all());exit;
		$validator = Validator::make($request->all(), [
            'voucher_no' => 'required|max:255',
			'debit' => 'required|same:credit'
        ]);
		
		if ($validator->fails()) {
            return redirect('credit_note/edit/'.$id)
                        ->withErrors($validator)
                        ->withInput();
        }
		
					
		$this->credit_note->update($id, Input::all());
		Session::flash('message', 'Credit note updated successfully');
		return redirect('credit_note');
	}
	
	public function destroy($id)
	{
		if( $this->credit_note->delete($id) ) { 
			Session::flash('message', 'Credit note deleted successfully.');
			return redirect('credit_note');
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

		$check = $this->credit_note->check_voucher_no(Input::get('voucher_no'), Input::get('vtype'), Input::get('id'));
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
		$voucherhead = 'Credit Note Voucher';
		$cnrow = $this->credit_note->findCN($id);
		$cnitems = $this->credit_note->findCNdata($id); //echo '<pre>';print_r($jerow);exit;

		return view('body.creditnote.print')
					->withVoucherhead($voucherhead)
					->withDetails($cnrow)
					->withInvoicerow($cnitems);
				}
					else {
					
						$path = app_path() . '/stimulsoft/helper.php';
						if(env('STIMULSOFT_VER')==2)
        			        return view('body.reports')->withPath($path)->withView($viewfile->print_name);
        			   else
        			        return view('body.debitnote.viewer')->withPath($path)->withView($viewfile->print_name);
						
					}
	}
	
	public function getDeptVoucher($id) {
		
		$depts = $this->accountsetting->getVoucherByDeptCN($vid=7, $id); 
		
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
							'dr_account_name' => ($row->master_name!='')?$row->master_name:'', 
							'dr_id' => ($row->dr_account_master_id!='')?$row->dr_account_master_id:'',
							'voucher_name' => $row->voucher_name,
							'voucher_id' => $row->voucher_id
						);

		}
		
		return $result;
	}
	
}

