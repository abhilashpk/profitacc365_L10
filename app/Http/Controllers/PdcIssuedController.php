<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\AccountMaster\AccountMasterInterface;
use App\Repositories\SupplierPayment\SupplierPaymentInterface;
use App\Repositories\PaymentVoucher\PaymentVoucherInterface;
use App\Repositories\Journal\JournalInterface;

use App\Http\Requests;
use Session;
use Response;
use App;
use DB;

class PdcIssuedController extends Controller
{
	protected $account_master;
	protected $supplier_payment;
	protected $journal;
	
	public function __construct(JournalInterface $journal,SupplierPaymentInterface $supplier_payment, AccountMasterInterface $account_master, PaymentVoucherInterface $payment_voucher) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->account_master = $account_master;
		$this->supplier_payment = $supplier_payment;
		$this->payment_voucher = $payment_voucher;
		$this->journal = $journal;
		$this->middleware('auth');
	}
	
	public function index() {
		$data = array();
		$pdcs = $this->payment_voucher->PDCIssuedList();
		$banks = $this->account_master->getAccountByGroup('BANK'); //echo '<pre>';print_r($pdcs);exit;
		
		$bacnts = DB::table('account_setting')->where('account_setting.voucher_type_id',19)
						->join('account_master','account_master.id','=','account_setting.cr_account_master_id')
						->where('account_setting.status',1)->where('account_setting.deleted_at','0000-00-00 00:00:00')
						->select('account_setting.cr_account_master_id','account_master.master_name')
						->first();
						
		return view('body.pdcissued.index')
					->withPdcs($pdcs)
					->withBanks($banks)
					->withBaccount($bacnts)
					->withData($data);
	}
	
		
	public function save(Request $request) {
		try { //echo '<pre>';print_r($request->all());exit;
			$this->payment_voucher->PdcIssuedSubmit($request->all());
			Session::flash('message', 'PDC issued updated successfully.');
			return redirect('pdc_issued');
		} catch(ValidationException $e) { 
			return Redirect::to('supplier_payment/add')->withErrors($e->getErrors());
		}
	}
	
	public function undo(Request $request) {
		try { //echo '<pre>';print_r($request->all());exit;
			$this->payment_voucher->PdcIssuedUndo($request->all());
			Session::flash('message', 'Issued Cheque undo successfully.');
			return redirect('pdc_issued');
		} catch(ValidationException $e) { 
			return Redirect::to('supplier_payment/add')->withErrors($e->getErrors());
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
		$voucher_head = 'PDC Issued Report';
		$attributes['search_type']="issued";
		//$reports = $this->makeTree($this->journal->getPDCreport($attributes)); 
		$reports = $this->makeTree($this->payment_voucher->PDCIssuedList());
		$titles = ['main_head' => 'PDC Report','subhead' => 'PDC Issued Report'];
			
		
		//echo '<pre>';print_r($reports);exit;
		return view('body.pdcissued.preprint')
					->withReports($reports)
					->withVoucherhead($voucher_head)
					->withTitles($titles)
					->withData($data);
	}
	
	public function UndoList() {
		
		$data = array();
		$undos = $this->payment_voucher->PDCIundoList(); //echo '<pre>';print_r($undos);exit;
		return view('body.pdcissued.undolist')
					->withUndos($undos)
					->withData($data);
					
	}
	
	public function delete($id) {
		try { //echo '<pre>';print_r($request->all());exit;
			$this->payment_voucher->PdcIssuedDelete($id);
			Session::flash('message', 'PDC issued deleted successfully.');
			return redirect('pdc_issued');
		} catch(ValidationException $e) { 
			return Redirect::to('supplier_payment/add')->withErrors($e->getErrors());
		}
	}
}

