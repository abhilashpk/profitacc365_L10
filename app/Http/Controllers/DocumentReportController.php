<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\PurchaseOrder\PurchaseOrderInterface;
use App\Repositories\QuotationSales\QuotationSalesInterface;
use App\Repositories\SalesOrder\SalesOrderInterface;
use App\Repositories\CustomerDo\CustomerDoInterface;
use App\Repositories\Employee\EmployeeInterface;


use App\Http\Requests;
use Notification;
use Input;
use Session;
use App;
use Excel;

class DocumentReportController extends Controller
{
	protected $purchase_order;
	protected $quotation_sales;
	protected $sales_order;
	protected $customer_do;
	protected $employee;

	public function __construct(PurchaseOrderInterface $purchase_order,QuotationSalesInterface $quotation_sales,SalesOrderInterface $sales_order,CustomerDoInterface $customer_do, EmployeeInterface $employee) {

		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		
		$this->purchase_order = $purchase_order;
		$this->quotation_sales = $quotation_sales;
		$this->sales_order = $sales_order;
		$this->customer_do = $customer_do;
		$this->employee = $employee;
		$this->middleware('auth');
	}
	
	public function index() {
		$data = array(); $reports = null;
		$voucher_head = 'Document Report';
		return view('body.documentreport.index')
					->withReports($reports)
					->withVoucherhead($voucher_head)
					->withType('')
					->withFromdate('')
					->withTodate('')
					->withData($data);
	}
	
	
	public function getSearch()
	{
		$data = array();
		
		$voucher_head = 'Document Report';
		switch(Input::get('search_type')) {
			case 'PO':
				$voucher_head = 'Purchase Order Summary';
				$reports = $this->purchase_order->getPendingReport(Input::all()); 
			break;
			
			case 'QS':
				$voucher_head = 'Quotation Sales Summary';
				$reports = $this->quotation_sales->getPendingReport(Input::all()); 
			break;
			
			case 'SO':
				$voucher_head = 'Sales Order Summary';
				$reports = $this->sales_order->getPendingReport(Input::all()); 
			break;
			
			case 'SDO':
				$voucher_head = 'Sales Delivery Order Summary';
				$reports = $this->customer_do->getPendingReport(Input::all()); 
			break;
		}
		
		//echo '<pre>';print_r($reports);exit;
		return view('body.documentreport.preprint')
					->withReports($reports)
					->withVoucherhead($voucher_head)
					->withType(Input::get('search_type'))
					->withFromdate(Input::get('date_from'))
					->withTodate(Input::get('date_to'))
					->withSettings($this->acsettings)
					->withData($data);
	}
	
	
	public function getPrint()
	{
		$data = array();
		if(Input::get('search_type')=='summary') {
			$voucher_head = 'Vat Report Summary';
			$reports = $this->journal->getVatSummary(Input::all()); 
			$titles = ['main_head' => 'Vat Report Summary','subhead' => 'Vat Report Summary'];
			
		} else if(Input::get('search_type')=='detail') {
			$voucher_head = 'Vat Report Detail';
			$reports = $this->journal->getVatDetail(Input::all());
			$titles = ['main_head' => 'Vat Report Detail','subhead' => 'Vat Report Detail'];
		}
		
		//echo '<pre>';print_r($results);exit;
		return view('body.documentreport.print')
					->withVoucherhead($voucher_head)
					->withReports($reports)
					->withTitles($titles)
					->withUrl('vat_report')
					->withData($data);
	}	
	
	public function searchForm() {
		$data = array(); 
		return view('body.documentreport.searchform')
					->withData($data);
	}
	
	public function searchResult()
	{
		$data = array();
		
		$voucher_head = 'Document Expiry Report';
		$reports = $this->employee->getDocumentReport(Input::all()); 
		$titles = ['main_head' => 'Document Expiry Report','subhead' => 'Document Expiry Report'];	
		
		//echo '<pre>';print_r($reports);exit;
		return view('body.documentreport.searchresult')
					->withReports($reports)
					->withVoucherhead($voucher_head)
					->withType(Input::get('search_type'))
					->withFromdate(Input::get('date_from'))
					->withTodate(Input::get('date_to'))
					->withTitles($titles)
					->withUrl('document_report/search_result')
					->withData($data);
	}
	
	public function dataExport()
	{
		$data = array();
		
		$reports = $this->employee->getDocumentReport(Input::all()); 
		$voucher_head = 'Document Expiry Report';
				
		$datareport[] = ['','','',$voucher_head,'','','','','','','','','',''];	
		$datareport[] = ['Emp. Code','Emp. Name','Passport#','Exp.Date','Visa#','Exp.Date','Labour Card#','Exp.Date','Health Card#','Exp.Date','ID Card#','Exp.Date','Medical Exam#','Exp.Date'];	
		
		foreach($reports as $row) {
			
		  $datareport[] = [ 'empdata' => $row->code, 'empname' => $row->name,
						  'Passport_id' => $row->pp_id, 'Passport_date' => date('d-m-Y', strtotime($row->pp_expiry_date)),
						  'visa_id' => $row->v_id, 'visa_date' => date('d-m-Y', strtotime($row->v_expiry_date)),
						  'labour_id' => $row->lc_id, 'labour_date' => date('d-m-Y', strtotime($row->lc_expiry_date)),
						  'health_id' => $row->hc_id, 'health_date' => date('d-m-Y', strtotime($row->hc_expiry_date)),
						  'idcard_id' => $row->ic_id, 'idcard_date' => date('d-m-Y', strtotime($row->ic_expiry_date)),
						  'med_id' => $row->me_id, 'med_date' => date('d-m-Y', strtotime($row->me_expiry_date))
						];
		}
		
		Excel::create($voucher_head, function($excel) use ($datareport, $voucher_head) {

        // Set the spreadsheet title, creator, and description
        $excel->setTitle($voucher_head);
        $excel->setCreator('Profit ACC 365 - ERP')->setCompany(Session::get('company'));
        $excel->setDescription($voucher_head);

        // Build the spreadsheet, passing in the payments array
		$excel->sheet('sheet1', function($sheet) use ($datareport) {
			$sheet->fromArray($datareport, null, 'A1', false, false);
		});

		})->download('xlsx');
		
	} 
	
}


