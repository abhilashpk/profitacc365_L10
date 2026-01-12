<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\AccountMaster\AccountMasterInterface;


use App\Http\Requests;
use Notification;
use Input;
use Session;
use App;
use Excel;

class LedgerMomentsController extends Controller
{
   
	protected $accountmaster;


	public function __construct(AccountmasterInterface $accountmaster) {

		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->accountmaster = $accountmaster;
		$this->middleware('auth');
	}
	
	public function index() {
		$data = array(); $reports = null;
		$voucher_head = 'Ledger Moments';
		return view('body.ledgermoments.index')
					->withReports($reports)
					->withVoucherhead($voucher_head)
					->withType('')
					->withFromdate('')
					->withTodate('')
					->withSettings($this->acsettings)
					->withData($data);
	}
	
	/* protected function makeTree($result)
	{
		$childs = array();
		foreach($result as $item)
			$childs[$item->id][] = $item;
		
		return $childs;
	}
	
	protected function makeTreeAr($result)
	{
		$childs = array();
		foreach($result as $item)
			$childs[$item->area_id][] = $item;
		
		return $childs;
	}
	
	protected function makeTreeEx($result)
	{
		$childs = array();
		foreach($result as $item)
			$childs[$item->vat_no][] = $item;
		
		return $childs;
	} */
	
	private function calculateAmount($result)
	{
		$newArr = array();
		foreach($result as $row)
		{
			$Arr['account_id'] = $row->account_id;
			$Arr['master_name'] = $row->master_name;
			if(property_exists($row,'dr_amount') && property_exists($row,'cr_amount'))
				$Arr['cl_balance'] = $row->dr_amount - $row->cr_amount;
			else if(property_exists($row,'cl_balance'))
				$Arr['cl_balance'] = $row->cl_balance;
			
			$pdcr_amount = $row->pdcr_amount_rv + $row->pdcr_amount_jv + $row->pdcr_amount_obt - $row->balance_amount;
			$pdci_amount = $row->pdci_amount_pv + $row->pdci_amount_jv + $row->pdci_amount_obi + $row->pdci_amount_pc - $row->balance_amount_i;
			$Arr['pdcr_amount'] = $pdcr_amount;
			$Arr['pdci_amount'] = $pdci_amount;
			
			$newArr[] = $Arr;
		}
		
		return $newArr;
	}
	
	public function getSearch()
	{
		$data = array();
		$voucher_head = 'Account Balance with PDC';
		//$reports = $this->calculateAmount($this->accountmaster->getLedgerMoments(Input::all()));
		$reports = $this->accountmaster->getLedgerMoments(Input::all());
				
		//echo '<pre>';print_r($reports);exit;
		return view('body.ledgermoments.preprint')
					->withReports($reports)
					->withVoucherhead($voucher_head)
					->withType(Input::get('search_type'))
					->withFromdate(Input::get('date_from'))
					->withTodate(Input::get('date_to'))
					->withSettings($this->acsettings)
					->withData($data);
	}
	
	public function dataExport()
	{
		$data = array(); //echo '<pre>';print_r(Input::all());exit;
		$datareport[] = [strtoupper(Session::get('company')),'','',''];
		$datareport[] = ['','','','','','',''];
		$voucher_head = 'Account Balance with PDC';
		$reports = $this->accountmaster->getLedgerMoments(Input::all());
		$datareport[] = ['','',strtoupper($voucher_head),'','','',''];
		$datareport[] = ['Account Code','Account Name','Balance','PDC Received','PDC Issued','Net Balance'];
		$total_bal = $total_pdcr = $total_pdci = $net_bal = $total_cash = 0;
		foreach($reports as $report)
		{
			$total_bal += $report->cl_balance;
			$total_pdcr += $report->pdcr_amount; 
			$total_pdci += $report->pdci_amount; 
			
			if(Input::get('search_type')=='CUSTOMER') {
				$net_balance = $report->cl_balance + $report->pdcr_amount;
				$pdi = 0;
				if($report->pdcr_amount < 0) {
					$pdr = '('.number_format(( $report->pdcr_amount *-1),2).')';
			    } else 
				   $pdr = number_format($report->pdcr_amount,2);
			   
			} else {
				$net_balance = $report->cl_balance + $report->pdci_amount;
				$pdr = 0;
				if($report->pdci_amount < 0) {
					$pdi = '('.number_format(( $report->pdci_amount *-1),2).')';
			    } else 
					$pdi = number_format($report->pdci_amount,2); 
			}
			
			$net_bal += $net_balance;
			
			if($report->cl_balance < 0)
				$bal = '('.number_format(( $report->cl_balance *-1),2).')';
			else 
				$bal = number_format($report->cl_balance,2);
			
			
			if($net_balance < 0) {
				$nbal = '('.number_format(( $net_balance *-1),2).')';
		    } else 
				$nbal = number_format($net_balance,2); 
			
			$datareport[] = [$report->account_id,$report->master_name,$bal,$pdr,$pdi,$nbal];
		}
		
		if($total_bal < 0) {
			$bal1 = '('.number_format(( $total_bal *-1),2).')';
	   } else $bal1 = number_format($total_bal,2); 
	   
	   if($total_pdcr < 0) {
			$bal2 = '('.number_format(( $total_pdcr *-1),2).')';
	  } else $bal2 = number_format($total_pdcr,2); 
	   
	    if($total_pdci < 0) {
			$bal3 =  '('.number_format(( $total_pdci *-1),2).')';
	  } else $bal3 = number_format($total_pdci,2); 
												
		if($net_bal < 0) {
			$bal4 = '('.number_format(( $net_bal *-1),2).')';
	  } else $bal4 = number_format($net_bal,2);
	  
		$datareport[] = ['','Grand Total:',$bal1,$bal2,$bal3,$bal4];
		
		Excel::create($voucher_head, function($excel) use ($datareport,$voucher_head) {

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

