<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\PurchaseInvoice\PurchaseInvoiceInterface;
use App\Repositories\PurchaseOrder\PurchaseOrderInterface;
use App\Repositories\PurchaseReturn\PurchaseReturnInterface;

use App\Http\Requests;
use Notification;
use Input;
use Session;
use App;

class PurchaseReportController extends Controller
{
   
	protected $purchase_invoice;
	protected $purchase_order;
	protected $purchase_return;

	public function __construct(PurchaseInvoiceInterface $purchase_invoice, PurchaseOrderInterface $purchase_order, PurchaseReturnInterface $purchase_return) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->purchase_invoice = $purchase_invoice;
		$this->purchase_order = $purchase_order;
		$this->purchase_return = $purchase_return;
		$this->middleware('auth');

	}
	
	public function index() {
		$data = array(); $reports = null;
		return view('body.purchasereport.index')
					->withReports($reports)
					->withType('')
					->withFromdate('')
					->withTodate('')
					->withData($data);
	}
	
		
	public function getSearch()
	{
		$data = array();
		
		if(Input::get('search_type')=='purchase') {
			$voucher_head = 'Purchase Invoice Summary';
			$reports = $this->purchase_invoice->getInvoiceReport(Input::all()); 
			
		} else if(Input::get('search_type')=='purchase_order') {
			$voucher_head = 'Purchase Order Report';
			$reports = $this->purchase_order->getOrderReport(Input::all());
			
		} else if(Input::get('search_type')=='purchase_return') {
			$voucher_head = 'Purchase Return Report';
			$reports = $this->purchase_return->getReturnReport(Input::all());
			
		} 
		
		//echo '<pre>';print_r($reports);exit;
		return view('body.purchasereport.index')
					->withReports($reports)
					->withVoucherhead($voucher_head)
					->withType(Input::get('search_type'))
					->withFromdate(Input::get('date_from'))
					->withTodate(Input::get('date_to'))
					->withData($data);
	}
	
	protected function makeSummary($reports)
	{
		$result = $casharr = $creditarr = array(); 
		$cash_total = $credit_total = $cash_discount = $credit_discount = $cash_net = $credit_net = $cash_ocost = $credit_ocost = $cash_vat = $credit_vat = 0;
		foreach($reports as $report) {
			if($report->amount_transfer==1) {
				$cash_total += $report->total;
				$cash_discount += $report->discount;
				$cash_vat += $report->vat_amount;
				$cash_net += $report->net_amount;
				$cash_ocost += $report->other_cost;
				$casharr[] = ['inv_no' => $report->voucher_no,'date' => $report->voucher_date, 'supplier' => $report->supplier,'total' => $report->total,'vat_no'=>$report->vat_no, 
							  'discount' => $report->discount, 'vat' => $report->vat_amount, 'net_amount' => $report->net_amount, 'other_cost' => $report->other_cost ];
			} else if($report->amount_transfer==0) {
				$credit_total += $report->total;
				$credit_discount += $report->discount;
				$credit_vat += $report->vat_amount;
				$credit_net += $report->net_amount;
				$credit_ocost += $report->other_cost;
				$creditarr[] = ['inv_no' => $report->voucher_no,'date' => $report->voucher_date, 'supplier' => $report->supplier,'total' => $report->total,'vat_no'=>$report->vat_no,
							  'discount' => $report->discount, 'vat' => $report->vat_amount, 'net_amount' => $report->net_amount, 'other_cost' => $report->other_cost ];
			} else {
				$credit_total += $report->total;
				$credit_discount += $report->discount;
				$credit_vat += $report->vat_amount;
				$credit_net += $report->net_amount;
				$credit_ocost += $report->other_cost;
				$creditarr[] = ['inv_no' => $report->voucher_no,'date' => $report->voucher_date, 'supplier' => $report->supplier,'total' => $report->total,'vat_no'=>$report->vat_no, 
							  'discount' => $report->discount, 'vat' => $report->vat_amount, 'net_amount' => $report->net_amount, 'other_cost' => $report->other_cost ];
			}
		}
		$cashitems = ['items' => $casharr, 'cash_total' => $cash_total, 'cash_discount' => $cash_discount, 'cash_vat' => $cash_vat, 'cash_net' => $cash_net, 'cash_ocost' => $cash_ocost];
		$credititems = ['items' => $creditarr, 'credit_total' => $credit_total, 'credit_discount' => $credit_discount, 'credit_vat' => $credit_vat, 'credit_net' => $credit_net, 'credit_ocost' => $credit_ocost];
		return $result = ['cash' => $cashitems, 'credit' => $credititems];
	}
	
	public function getSummary()
	{
		$data = array();
		
		if(Input::get('search_type')=='purchase') {
			$voucher_head = 'Purchase Invoice Summary';
			$reports = $this->makeSummary( $this->purchase_invoice->getInvoiceReport(Input::all()) ); 
			
		} else if(Input::get('search_type')=='purchase_order') {
			$voucher_head = 'Purchase Order Summary';
			$reports = $this->makeSummary( $this->purchase_order->getOrderReport(Input::all())); 
			
		} else if(Input::get('search_type')=='purchase_return') {
			$voucher_head = 'Purchase Return Summary';
			$reports = $this->makeSummary( $this->purchase_return->getReturnReport(Input::all()));
			
		} 
		$titles = ['main_head' => 'Purchase Invoice Summary','subhead' => $voucher_head];
		//echo '<pre>';print_r($reports);exit;
		return view('body.purchasereport.summary')
					->withCash($reports['cash'])
					->withCredit($reports['credit'])
					->withVoucherhead($voucher_head)
					->withType(Input::get('search_type'))
					->withFromdate(Input::get('date_from'))
					->withTodate(Input::get('date_to'))
					->withTitles($titles)
					->withUrl('purchase_report')
					->withData($data);
	}

	public function getPrint()
	{
		$data = array();
		
		if(Input::get('search_type')=='purchase') {
			$voucher_head = 'Purchase Invoice';
			$results = $this->purchase_invoice->getInvoice(Input::all()); 
			
		} else if(Input::get('search_type')=='purchase_order') {
			$voucher_head = 'Purchase Order';
			$results = $this->purchase_order->getOrder(Input::all()); 
			
		} else if(Input::get('search_type')=='purchase_return') {
			$voucher_head = 'Purchase Return';
			$results = $this->purchase_return->getOrder(Input::all());
		} 
		
		//echo '<pre>';print_r($results);exit;
		return view('body.purchasereport.print')
					->withDetails($results['details'])
					->withItems($results['items'])
					->withVoucherhead($voucher_head)
					->withData($data);
	}	
}