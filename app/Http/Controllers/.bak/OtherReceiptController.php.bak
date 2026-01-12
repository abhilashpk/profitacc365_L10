<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Bank\BankInterface;
use App\Repositories\Currency\CurrencyInterface;
use App\Repositories\VoucherNo\VoucherNoInterface;
use App\Repositories\Jobmaster\JobmasterInterface;
use App\Repositories\Department\DepartmentInterface;
use App\Repositories\OtherReceipt\OtherReceiptInterface;
use App\Repositories\AccountSetting\AccountSettingInterface;

use App\Http\Requests;
use Input;
use Session;
use Response;
use App;

class OtherReceiptController extends Controller
{
    protected $bank;
	protected $currency;
	protected $voucherno;
	protected $jobmaster;
	protected $department;
	protected $other_receipt;
	protected $accountsetting;
	
	public function __construct(OtherReceiptInterface $other_receipt, BankInterface $bank, CurrencyInterface $currency, VoucherNoInterface $voucherno, JobmasterInterface $jobmaster, DepartmentInterface $department,AccountSettingInterface $accountsetting) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->middleware('auth');
		$this->bank = $bank;
		$this->currency = $currency;
		$this->voucherno = $voucherno;
		$this->jobmaster = $jobmaster;
		$this->department = $department;
		$this->other_receipt = $other_receipt;
		$this->accountsetting = $accountsetting;
	}
	
	public function index() {
		$data = array();
		$receipts = $this->other_receipt->OtherReceiptList();
		return view('body.otherreceipt.index')
					->withReceipts($receipts)
					->withData($data);
	}
	
	public function add() {

		$data = array();
		$currency = $this->currency->activeCurrencyList();
		$banks = $this->bank->activeBankList();
		$res = $this->voucherno->getVoucherNo('CR');
		$jobs = $this->jobmaster->activeJobmasterList();
		$departments = $this->department->activeDepartmentList();
		$vno = $res->no+1;
		$vouchers = $this->accountsetting->getAccountSettingsById($vid=9);
		return view('body.otherreceipt.add')
					->withCurrency($currency)
					->withVoucherno($vno)
					->withBanks($banks)
					->withJobs($jobs)
					->withVouchers($vouchers)
					->withDepartments($departments)
					->withData($data);
	}
	
	public function save() {
		try { //echo '<pre>';print_r(Input::all());exit;
			$this->other_receipt->create(Input::all());
			Session::flash('message', 'Other receipt added successfully.');
			return redirect('other_receipt');
		} catch(ValidationException $e) { 
			return Redirect::to('other_receipt/add')->withErrors($e->getErrors());
		}
	}
	
	public function edit($id) { 

		$data = array();
		$bankrow = $this->bank->find($id);
		return view('body.bank.edit')
					->withBankrow($bankrow)
					->withData($data);
	}
	
	public function update($id)
	{
		$this->bank->update($id, Input::all());//print_r(Input::all());exit;
		Session::flash('message', 'Bank updated successfully');
		return redirect('bank');
	}
	
	public function destroy($id)
	{
		$this->bank->delete($id);
		//check bank name is already in use.........
		// code here ********************************
		Session::flash('message', 'Bank deleted successfully.');
		return redirect('bank');
	}
	
	
}
