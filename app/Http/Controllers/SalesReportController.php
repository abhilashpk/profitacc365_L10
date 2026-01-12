<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\SalesInvoice\SalesInvoiceInterface;
use App\Repositories\SalesOrder\SalesOrderInterface;
use App\Repositories\SalesReturn\SalesReturnInterface;
use App\Repositories\QuotationSales\QuotationSalesInterface;
use App\Repositories\CustomerDo\CustomerDoInterface;

use App\Http\Requests;
use Notification;
use Input;
use Session;
use App;

class SalesReportController extends Controller
{
   
	protected $sales_invoice;
	protected $sales_order;
	protected $sales_return;
	protected $sales_quotation;
	protected $customer_do;

	public function __construct(SalesInvoiceInterface $sales_invoice, SalesOrderInterface $sales_order, SalesReturnInterface $sales_return, QuotationSalesInterface $sales_quotation, CustomerDoInterface $customer_do) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->middleware('auth');
		$this->sales_invoice = $sales_invoice;
		$this->sales_order = $sales_order;
		$this->sales_return = $sales_return;
		$this->sales_quotation = $sales_quotation;
		$this->customer_do = $customer_do;
	}
	
	public function index() {
		$data = array(); $reports = null;
		return view('body.salesreport.index')
					->withReports($reports)
					->withType('')
					->withFromdate('')
					->withTodate('')
					->withData($data);
	}
	
		
	public function getSearch()
	{
		$data = array();
		
		if(Input::get('search_type')=='sales') {
			$voucher_head = 'Sales Invoice Summary';
			$reports = $this->sales_invoice->getInvoiceReport(Input::all()); 
			
		} else if(Input::get('search_type')=='sales_order') {
			$voucher_head = 'Sales Order Report';
			$reports = $this->sales_order->getOrderReport(Input::all());
			
		} else if(Input::get('search_type')=='sales_return') {
			$voucher_head = 'Sales Return Report';
			$reports = $this->sales_return->getReturnReport(Input::all());
			
		} else if(Input::get('search_type')=='quotation') {
			$voucher_head = 'Sales Quotation Report';
			$reports = $this->sales_quotation->getQuotationReport(Input::all());
			
		}  else if(Input::get('search_type')=='delivery_order') {
			$voucher_head = 'Customer Delivery Order Report';
			$reports = $this->customer_do->getOrderReport(Input::all());
			
		}
		
		//echo '<pre>';print_r($reports);exit;
		return view('body.salesreport.index')
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
		$cash_total = $credit_total = $cash_discount = $credit_discount = $cash_net = $credit_net = $cash_vat = $credit_vat = 0;
		foreach($reports as $report) {
			if($report->amount_transfer==1) {
				$cash_total += $report->total;
				$cash_discount += $report->discount;
				$cash_vat += $report->vat_amount;
				$cash_net += $report->net_total;
				$casharr[] = ['inv_no' => $report->voucher_no,'date' => $report->voucher_date, 'supplier' => $report->supplier,'total' => $report->total,'vat_no'=>$report->vat_no, 
							  'discount' => $report->discount, 'vat' => $report->vat_amount, 'net_amount' => $report->net_total, 'other_cost' => $report->other_cost ];
			} else if($report->amount_transfer==0) {
				$credit_total += $report->total;
				$credit_discount += $report->discount;
				$credit_vat += $report->vat_amount;
				$credit_net += $report->net_total;
				$creditarr[] = ['inv_no' => $report->voucher_no,'date' => $report->voucher_date, 'supplier' => $report->supplier,'total' => $report->total, 
							  'discount' => $report->discount, 'vat' => $report->vat_amount, 'net_amount' => $report->net_total,'vat_no'=>$report->vat_no,];
			} else {
				$credit_total += $report->total;
				$credit_discount += $report->discount;
				$credit_vat += $report->vat_amount;
				$credit_net += $report->net_total;
				$creditarr[] = ['inv_no' => $report->voucher_no,'date' => $report->voucher_date, 'supplier' => $report->supplier,'total' => $report->total, 
							  'discount' => $report->discount, 'vat' => $report->vat_amount, 'net_amount' => $report->net_total,'vat_no'=>$report->vat_no,];
			}
		}
		$cashitems = ['items' => $casharr, 'cash_total' => $cash_total, 'cash_discount' => $cash_discount, 'cash_vat' => $cash_vat, 'cash_net' => $cash_net];
		$credititems = ['items' => $creditarr, 'credit_total' => $credit_total, 'credit_discount' => $credit_discount, 'credit_vat' => $credit_vat, 'credit_net' => $credit_net];
		return $result = ['cash' => $cashitems, 'credit' => $credititems];
	}
	
	public function getSummary()
	{
		$data = array();
		
		if(Input::get('search_type')=='sales') {
			$voucher_head = 'Sales Invoice Summary';
			$reports = $this->makeSummary( $this->sales_invoice->getInvoiceReport(Input::all()) ); 
			
		} else if(Input::get('search_type')=='sales_order') {
			$voucher_head = 'Sales Order Summary';
			$reports = $this->makeSummary( $this->sales_order->getOrderReport(Input::all())); 
			
		} else if(Input::get('search_type')=='sales_return') {
			$voucher_head = 'Sales Return Summary';
			$reports = $this->makeSummary( $this->sales_return->getReturnReport(Input::all()));
			
		} else if(Input::get('search_type')=='quotation') {
			$voucher_head = 'Sales Quotation Summary';
			$reports = $this->makeSummary( $this->sales_quotation->getQuotationReport(Input::all()));
			
		} else if(Input::get('search_type')=='delivery_order') {
			$voucher_head = 'Customer Delivery Order Summary';
			$reports = $this->makeSummary( $this->customer_do->getOrderReport(Input::all())); 
			
		}
		$titles = ['main_head' => 'Sales Invoice Summary','subhead' => $voucher_head];
		//echo '<pre>';print_r($reports);exit;
		return view('body.salesreport.summary')
					->withCash($reports['cash'])
					->withCredit($reports['credit'])
					->withVoucherhead($voucher_head)
					->withType(Input::get('search_type'))
					->withFromdate(Input::get('date_from'))
					->withTodate(Input::get('date_to'))
					->withTitles($titles)
					->withUrl('sales_report')
					->withData($data);
	}

	public function getPrint()
	{
		$data = array();
		
		if(Input::get('search_type')=='sales') {
			$voucher_head = 'Sales Invoice';
			$results = $this->sales_invoice->getInvoiceById(Input::all()); 
			
		} else if(Input::get('search_type')=='sales_order') {
			$voucher_head = 'Sales Order';
			$results = $this->sales_order->getOrder(Input::all());
			
		} else if(Input::get('search_type')=='sales_return') {
			$voucher_head = 'Sales Return';
			$results = $this->sales_return->getReturn(Input::all());
			
		} else if(Input::get('search_type')=='quotation') {
			$voucher_head = 'Sales Quotation';
			$results = $this->sales_quotation->getQuotation(Input::all());
			
		}  else if(Input::get('search_type')=='delivery_order') {
			$voucher_head = 'Customer Delivery Order';
			$results = $this->customer_do->getOrder(Input::all());
		}
		
		//echo '<pre>';print_r($results);exit;
		return view('body.salesreport.print')
					->withDetails($results['details'])
					->withItems($results['items'])
					->withVoucherhead($voucher_head)
					->withData($data);
	}	
}
