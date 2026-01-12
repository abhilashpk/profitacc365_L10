<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Bank\BankInterface;
use App\Repositories\Currency\CurrencyInterface;
use App\Repositories\VoucherNo\VoucherNoInterface;
use App\Repositories\Jobmaster\JobmasterInterface;
use App\Repositories\Department\DepartmentInterface;
use App\Repositories\OtherPayment\OtherPaymentInterface;
use App\Repositories\AccountSetting\AccountSettingInterface;

use App\Http\Requests;
use Input;
use Session;
use Response;
use App;

class OtherPaymentController extends Controller
{
    protected $bank;
	protected $currency;
	protected $voucherno;
	protected $jobmaster;
	protected $department;
	protected $other_payment;
	protected $accountsetting;
	
	public function __construct(OtherPaymentInterface $other_payment, BankInterface $bank, CurrencyInterface $currency, VoucherNoInterface $voucherno, JobmasterInterface $jobmaster, DepartmentInterface $department,AccountSettingInterface $accountsetting) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->middleware('auth');
		$this->bank = $bank;
		$this->currency = $currency;
		$this->voucherno = $voucherno;
		$this->jobmaster = $jobmaster;
		$this->department = $department;
		$this->accountsetting = $accountsetting;
		$this->other_payment = $other_payment;
	}
	
	public function index() {
		$data = array();
		$payments = $this->other_payment->OtherPaymentList();
		return view('body.otherpayment.index')
					->withPayments($payments)
					->withData($data);
	}
	
	public function add() {

		$data = array();
		$currency = $this->currency->activeCurrencyList();
		$banks = $this->bank->activeBankList();
		$res = $this->voucherno->getVoucherNo('SP');
		$jobs = $this->jobmaster->activeJobmasterList();
		$departments = $this->department->activeDepartmentList();
		$vouchers = $this->accountsetting->getAccountSettingsById($vid=10);
		$vno = $res->no+1;
		return view('body.otherpayment.add')
					->withCurrency($currency)
					->withVoucherno($vno)
					->withBanks($banks)
					->withJobs($jobs)
					->withDepartments($departments)
					->withVouchers($vouchers)
					->withData($data);
	}
	
	public function save() {
		try { //echo '<pre>';print_r(Input::all());//exit;
			$this->other_payment->create(Input::all());
			Session::flash('message', 'Other payment added successfully.');
			return redirect('other_payment');
		} catch(ValidationException $e) { 
			return Redirect::to('other_payment/add')->withErrors($e->getErrors());
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

