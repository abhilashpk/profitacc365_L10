<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Bank\BankInterface;
use App\Repositories\Currency\CurrencyInterface;
use App\Repositories\VoucherNo\VoucherNoInterface;
use App\Repositories\Jobmaster\JobmasterInterface;
use App\Repositories\Department\DepartmentInterface;
use App\Repositories\AccountSetting\AccountSettingInterface;
use App\Repositories\SalesInvoice\SalesInvoiceInterface;

use App\Repositories\CustomerReceipt\CustomerReceiptInterface;
use App\Repositories\ReceiptVoucher\ReceiptVoucherInterface;
use App\Repositories\PaymentVoucher\PaymentVoucherInterface;

use App\Http\Requests;
use Session;
use Response;
use App;

class AdvanceSetController extends Controller
{
    protected $bank;
	protected $currency;
	protected $voucherno;
	protected $jobmaster;
	protected $department;
	protected $accountsetting;
	protected $sales_invoice;
	
	protected $customer_receipt;
	protected $receipt_voucher;
	protected $payment_voucher;
	
	public function __construct(CustomerReceiptInterface $customer_receipt, ReceiptVoucherInterface $receipt_voucher, PaymentVoucherInterface $payment_voucher, BankInterface $bank, CurrencyInterface $currency, VoucherNoInterface $voucherno, JobmasterInterface $jobmaster, DepartmentInterface $department,AccountSettingInterface $accountsetting,SalesInvoiceInterface $sales_invoice) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		
		$this->middleware('auth');
		$this->bank = $bank;
		$this->currency = $currency;
		$this->voucherno = $voucherno;
		$this->jobmaster = $jobmaster;
		$this->department = $department;
		$this->accountsetting = $accountsetting;
		$this->sales_invoice = $sales_invoice;
		
		$this->customer_receipt = $customer_receipt;
		$this->receipt_voucher = $receipt_voucher;
		$this->payment_voucher = $payment_voucher;
	}
	
	public function index() {
		$data = array();
		//$receipts = $this->customer_receipt->CustomerReceiptList();
		$receipts = $this->receipt_voucher->CustomerReceiptList();//echo '<pre>';print_r($receipts);exit;
		return view('body.advanceset.index')
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
		return view('body.advanceset.add')
					->withCurrency($currency)
					->withVoucherno($vno)
					->withBanks($banks)
					->withJobs($jobs)
					->withDepartments($departments)
					->withVouchers($vouchers)
					->withData($data);
	}
	
	public function save(Request $request) {
		try { 
			if($request->get('account_for') == 1)
				$this->receipt_voucher->advance_set($request->all());
			else
				$this->payment_voucher->advance_set($request->all());
			
			Session::flash('message', 'Bill has been set off successfully.');
			return redirect('advance_set/add');
		} catch(ValidationException $e) { 
			return Redirect::to('advance_set/add')->withErrors($e->getErrors());
		}
	}
	
	public function edit($id) { 

		$data = array();
		$currency = $this->currency->activeCurrencyList();
		$banks = $this->bank->activeBankList();
		$jobs = $this->jobmaster->activeJobmasterList();
		$departments = $this->department->activeDepartmentList();
		$vouchers = $this->accountsetting->getAccountSettingsById($vid=9);
	
		$crrow = $this->receipt_voucher->find($id);
		$invoicerow = $this->receipt_voucher->findJEdata($id); 
		$invoicetrrow = $this->receipt_voucher->findRVTrdata($id); 
		
		$invoices = $this->sales_invoice->getCustomerInvoice($invoicerow[1]->account_id);
		$openbalances = $this->sales_invoice->getOpenBalances($invoicerow[1]->account_id); //echo '<pre>';print_r($invoicerow);exit;
		
		$vouchertype = $this->accountsetting->getAccountSettings(9);
		$view = ($crrow->from_jv==0)?'edit':'editjv';
		return view('body.advanceset.'.$view)
					->withCurrency($currency)
					->withBanks($banks)
					->withJobs($jobs)
					->withCrrow($crrow)
					->withDepartments($departments)
					->withVouchers($vouchers)
					->withInvoices($invoices)
					->withOpenbalances($openbalances)
					->withInvoicerow($invoicerow)
					->withInvoicetrrow($invoicetrrow)
					->withVouchertype($vouchertype)
					->withData($data);
	}
	
	public function update(Request $request,$id)
	{
		
		//echo '<pre>';print_r($request->all());exit;
		$this->receipt_voucher->update($id, $request->all());
		Session::flash('message', 'Customer receipt updated successfully');
		return redirect('customer_receipt');
	}
	
	public function destroy($id)
	{
		$this->receipt_voucher->delete($id);
		
		Session::flash('message', 'Customer receipt deleted successfully.');
		return redirect('customer_receipt');
	}
	
	public function getVoucher($id,$type) {
		
		 $row = $this->accountsetting->getDrVoucherByID($id);//print_r($row);
		 if($row->voucher_no != '' || $row->voucher_no != null) {
			 if($row->is_prefix==0)
				 $voucher = $row->voucher_no+1;
			 else {
				 $no = (int)$row->voucher_no+1;
				 $voucher = $row->prefix.''.$no;
			 }
		 }
		 
		 if($type=='CASH') {
			 $master_name = $row->cashaccount;
			 $id = $row->cash_account_id;
		 } else if($type=='BANK') {
			 $master_name = $row->bankaccount;
			 $id = $row->bank_account_id;
		 } else if($type=='PDCR') {
			 $master_name = $row->pdcaccount;
			 $id = $row->pdc_account_id;
		} else if($type=='PDCI') {
			 $master_name = $row->pdcaccount;
			 $id = $row->pdc_account_id;
		 }
		 
		 return $result = array('voucher_no' => $voucher,
								'account_name' => $master_name, 
								'id' => $id);
		
	}
	
}

