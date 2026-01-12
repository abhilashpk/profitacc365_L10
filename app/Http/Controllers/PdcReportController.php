<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Journal\JournalInterface;


use App\Http\Requests;
use Notification;
use Session;
use App;
use DB;
use Excel;
class PdcReportController extends Controller
{
	protected $journal;

	public function __construct(JournalInterface $journal) {

		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->journal = $journal;
		$this->middleware('auth');
	}
	
	public function index() {
		$data = array(); $reports = null;
		$voucher_head = 'PDC Report';
		$custsup = DB::table('account_master')->whereIn('category',['CUSTOMER','SUPPLIER'])->where('status',1)
						->where('deleted_at','0000-00-00 00:00:00')
						->select('id','master_name')
						->orderBy('master_name','ASC')->get();
		return view('body.pdcreport.index')
					->withReports($reports)
					->withVoucherhead($voucher_head)
					->withType('')
					->withFromdate('')
					->withTodate('')
					->withCustsup($custsup)
					->withData($data);
	}
	
	protected function makeTree($result)
	{
		$childs = array();
		foreach($result as $item)
			$childs[$item->month][] = $item;
		
		return $childs;
	}
	
	public function getSearch(Request $request)
	{
		$data = array();
		
		$voucher_head = 'PDC Report';
		$reports = $this->makeTree($this->journal->getPDCreport($request->all())); 
		$custsup = DB::table('account_master')->whereIn('category',['CUSTOMER','SUPPLIER'])->where('status',1)
						->where('deleted_at','0000-00-00 00:00:00')
						->select('id','master_name')
						->orderBy('master_name','ASC')->get();
		
		//echo '<pre>';print_r($reports);exit;
		return view('body.pdcreport.preprint')
					->withReports($reports)
					->withVoucherhead($voucher_head)
					->withType($request->get('search_type'))
					->withFromdate($request->get('date_from'))
					->withTodate($request->get('date_to'))
					->withCustsup($custsup)
					->withSettings($this->acsettings)
					->withStatus($request->get('status'))
					->withAccount($request->get('account_id'))
					->withObonly($request->get('ob_only'))
					->withData($data);
	}
	
	public function dataExport(Request $request)
	{ 
	    
	   // echo '<pre>';print_r($request->all());exit; 
	
		$data = array();
		$from=$request->get('date_from');
		$to=$request->get('date_to');
		$datareport[] = ['','',strtoupper(Session::get('company')),'','',''];
		$datareport[] = ['','','','','','',''];
		
		$voucher_head = 'PDC Report';
		$reports = $this->makeTree($this->journal->getPDCreport($request->all())); 
	//echo '<pre>';print_r($reports);exit; 
			$datareport[] = ['','',strtoupper($voucher_head), '','',''];
		     $datareport[] = ['','','','','','',''];
		      $datareport[] = ['Date From:'.$from,'To:'.$to,'','','','',''];
			$datareport[] = [ 'SI#','Party Name','Cheque No','Bank','Cheque Date','Amount'];
			
			$gtotal = 0;
			foreach ($reports as $key => $report) {
			    $total = 0;$i = 0;
			foreach($report as $row) { 
					$total += $row->amount;
					$gtotal += $row->amount;
					$date = $row->month;  
				     $i++;		
				
						
				$datareport[] = [ 'si' => $i,
				                'pname' => $row->customer,
				                 'chqno'=>$row->cheque_no,
				                  'bank' => $row->code,
								 'chq_dt' => date('d-m-Y',strtotime($row->cheque_date)),
								 'amt' => number_format($row->amount,2)
								];
			}	
			$yr = date('Y', strtotime($row->cheque_date));
			$time=date("F", mktime(0, 0, 0, $date, 10)).' '.$yr;
			 $datareport[] = ['','','','','Total in '.$time,number_format($total,2)];
			}
			$datareport[] = ['','','','','','',''];
			$datareport[] = ['','','','','Net Total:',number_format($gtotal,2)];
	//	echo $voucher_head.'<pre>';print_r($datareport);exit;
		Excel::create($voucher_head, function($excel) use ($datareport,$voucher_head) {

			// Set the spreadsheet title, creator, and description
			$excel->setTitle($voucher_head);
			$excel->setCreator('NumakPro ERP')->setCompany(Session::get('company'));
			$excel->setDescription($voucher_head);

			// Build the spreadsheet, passing in the payments array
			$excel->sheet('sheet1', function($sheet) use ($datareport) {
				$sheet->fromArray($datareport, null, 'A1', false, false);
			});

		})->download('xlsx');
	}
	public function getPrint()
	{
		$data = array();
		if($request->get('search_type')=='summary') {
			$voucher_head = 'Vat Report Summary';
			$reports = $this->journal->getVatSummary($request->all()); 
			$titles = ['main_head' => 'Vat Report Summary','subhead' => 'Vat Report Summary'];
			
		} else if($request->get('search_type')=='detail') {
			$voucher_head = 'Vat Report Detail';
			$reports = $this->journal->getVatDetail($request->all());
			$titles = ['main_head' => 'Vat Report Detail','subhead' => 'Vat Report Detail'];
		}
		
		//echo '<pre>';print_r($results);exit;
		return view('body.pdcreport.print')
					->withVoucherhead($voucher_head)
					->withReports($reports)
					->withTitles($titles)
					->withUrl('vat_report')
					->withSettings($this->acsettings)
					->withData($data);
	}	
}


