<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\AccountMaster\AccountMasterInterface;
use App\Repositories\CustomerReceipt\CustomerReceiptInterface;
use App\Repositories\ReceiptVoucher\ReceiptVoucherInterface;
use App\Repositories\Journal\JournalInterface;

use App\Http\Requests;
use Input;
use Session;
use Response;
use App;
use DB;

class PdcReceivedController extends Controller
{
	protected $account_master;
	protected $customer_receipt;
	protected $receipt_voucher;
	protected $journal;
	
	public function __construct(JournalInterface $journal,CustomerReceiptInterface $customer_receipt, AccountMasterInterface $account_master, ReceiptVoucherInterface $receipt_voucher) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->account_master = $account_master;
		$this->customer_receipt = $customer_receipt;
		$this->receipt_voucher = $receipt_voucher;
		$this->journal = $journal;
		$this->middleware('auth');
	}
	
	public function index() {
		$data = array();
		$pdcs = $this->receipt_voucher->PDCReceivedList();
		$banks = $this->account_master->getAccountByGroup('BANK'); //echo '<pre>';print_r($pdcs);exit;
		$bacnts = DB::table('account_setting')->where('account_setting.voucher_type_id',18)
						->join('account_master','account_master.id','=','account_setting.dr_account_master_id')
						->where('account_setting.status',1)->where('account_setting.deleted_at','0000-00-00 00:00:00')
						->select('account_setting.dr_account_master_id','account_master.master_name')
						->first();
		//$banks = DB::table('bank')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get(); //echo '<pre>';print_r($pdcs);exit;
		$pdcsacs = DB::table('account_master')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->where('category','PDCR')->select('id','master_name')->get();
		//echo '<pre>';print_r($pdcsacs);exit;
		return view('body.pdcreceived.index')
					->withPdcs($pdcs)
					->withBanks($banks)
					->withPdcrs($pdcsacs)
					->withBaccount($bacnts)
					->withData($data);
	}
	
		
	public function save() {
		try { //echo '<pre>';print_r(Input::all());exit;
			$this->receipt_voucher->PdcReceivedSubmit(Input::all());
			Session::flash('message', 'PDC received submitted successfully.');
			return redirect('pdc_received');
		} catch(ValidationException $e) { 
			return Redirect::to('customer_receipt/add')->withErrors($e->getErrors());
		}
	}
	
	public function chequeSubmit() {
		//echo '<pre>';print_r(Input::all());exit;
		try { //echo '<pre>';print_r(Input::all());exit;
			$this->receipt_voucher->PdcReceivedSubmit(Input::all());
			Session::flash('message', 'PDC received submitted successfully.');
			echo 'true';
		} catch(ValidationException $e) { 
			Session::flash('error', 'PDC received submitted failed.');
			echo 'false';
		}
	}
	
	public function UndoList() {
		
		$data = array();
		$undos = $this->receipt_voucher->PDCRundoList(); //echo '<pre>';print_r($undos);exit;
		return view('body.pdcreceived.undolist')
					->withUndos($undos)
					->withData($data);
					
	}
	
	public function undo() {
		try { //echo '<pre>';print_r(Input::all());exit;
			$this->receipt_voucher->PdcReceivedUndo(Input::all());
			Session::flash('message', 'Received Cheque undo successfully.');
			return redirect('pdc_received');
		} catch(ValidationException $e) { 
			return Redirect::to('customer_receipt/add')->withErrors($e->getErrors());
		}
	}
	
	protected function makeTree($result)
	{
		$childs = array();
		foreach($result as $item)
			$childs[$item->month][] = $item;
		
		return $childs;
	}
	
	public function getPrint()
	{
		$data = array();
		$voucher_head = 'PDC Received Report';
		$attributes['search_type']="received";
		//$reports = $this->makeTree($this->journal->getPDCreport($attributes)); 
		$reports = $this->makeTree($this->receipt_voucher->PDCReceivedList());
		//$reports = $this->receipt_voucher->PDCReceivedList();
		$titles = ['main_head' => 'PDC Report','subhead' => 'PDC Received Report'];
		
		//echo '<pre>';print_r($reports);exit;
		return view('body.pdcreceived.preprint')
					->withReports($reports)
					->withVoucherhead($voucher_head)
					->withTitles($titles)
					->withData($data);
	}	
	
	
	public function delete($id) {
		try { //echo '<pre>';print_r(Input::all());exit;
			$this->receipt_voucher->PdcReceivedDelete($id);
			Session::flash('message', 'Received deleted successfully.');
			return redirect('pdc_received');
		} catch(ValidationException $e) { 
			return Redirect::to('customer_receipt/add')->withErrors($e->getErrors());
		}
	}
	
}
