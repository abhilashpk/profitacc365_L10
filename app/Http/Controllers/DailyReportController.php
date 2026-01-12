<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\AccountMaster\AccountMasterInterface;

use App\Repositories\Acgroup\AcgroupInterface; 
use App\Repositories\Accategory\AccategoryInterface;


use App\Http\Requests;
use Notification;
use Input;
use Session;
use App;
use Excel;
use DB;
class DailyReportController extends Controller
{
   
	protected $accountmaster;
    protected $acgroup;

	public function __construct(AccountmasterInterface $accountmaster,AccategoryInterface $accategory, AcgroupInterface $acgroup) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->accountmaster = $accountmaster;
		$this->acgroup = $acgroup;
		$this->accategory = $accategory;
		$this->middleware('auth');
	}
	
	public function index() {
		$data = array(); $reports = null;
		$voucher_head = 'Daily Report';
		$groupdata = DB::table('daily_report_setting')->first();
		$groupid = ($groupdata->group_ids!='')?unserialize($groupdata->group_ids):null;
		$acntid = ($groupdata->account_ids!='')?unserialize($groupdata->account_ids):null;
		$groups = $accounts = [];
		if($groupid) {
			$groups = DB::table('account_group')->whereIn('id', $groupid)->select('id','name')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
			$accounts = $this->sortByGroupId( DB::table('account_master')->whereIn('account_group_id', $groupid)->select('id','master_name','account_group_id')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get() );
		}
		
		//echo '<pre>';print_r($accounts);exit;
		return view('body.dailyreport.index')
					->withReports($reports)
					->withVoucherhead($voucher_head)
					->withType('')
					->withGroups($groups)
					->withAccounts($accounts)
					->withAccountids($acntid)
					->withFromdate('')
					->withTodate('')
					->withSettings($this->acsettings)
					->withData($data);
	}
	
	private function sortByGroupId($result) {
		$childs = array();
		foreach($result as $item)
			$childs[$item->account_group_id][] = $item;
		
		return $childs;
	}
	
	protected function makeTree($result)
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
			$childs[$item->code][$item->tax_code][] = $item;
		
		return $childs;
	}
	
	protected function makeTreeEx($result)
	{
		$childs = array();
		foreach($result as $item)
			$childs[$item->vat_no][] = $item;
		
		return $childs;
	}
	
	protected function makeTreeTc($result)
	{
		$childs = array();
		foreach($result as $item)
			$childs[$item->tax_code][] = $item;
		
		return $childs;
	}
	
	protected function makeTreeTyp($result)
	{
		$childs = array();
		foreach($result as $item)
			$childs[$item->transaction_type][] = $item;
		
		return $childs;
	}
	
	protected function makeTreeParty($result)
	{
		$childs = array();
		foreach($result as $item)
			$childs[$item->tax_code][$item->vat_name][] = $item;
		
		return $childs;
	}
	
	
	private function correction($transactions, $resultrow) {
		
		$cr_total = 0; $dr_total = 0; $balance = 0; $res = [];
		foreach($transactions as $transaction) {
			$balance_prnt = $transaction['dr_amount'] - $transaction['cr_amount'];
			
			if($resultrow->category=='CUSTOMER') {
				//$balance_prnt = $transaction['dr_amount'] - $transaction['cr_amount'];	
				$dr_total += $transaction['dr_amount'];
				$cr_total += $transaction['cr_amount'];
				
				$balance += $balance_prnt;
				
				if($transaction['dr_amount'] > 0)
					$dr_amount = number_format($transaction['dr_amount'],2);
				else if($transaction['dr_amount'] < 0)
					$dr_amount = '('.number_format($transaction['dr_amount']*-1,2).')';
				else $dr_amount = '';
				
				if($transaction['cr_amount'] > 0)
					$cr_amount = number_format($transaction['cr_amount'],2);
				else if($transaction['dr_amount'] < 0)
					$cr_amount = '('.number_format($transaction['cr_amount']*-1,2).')';
				else $cr_amount = '';
				
				if($balance_prnt > 0)
					$balance_prnt = number_format($balance_prnt,2);
				else if($balance_prnt < 0)
					$balance_prnt = '('.number_format(($balance_prnt*-1),2).')';
				else $balance_prnt = '';
			
				$res[] = ['invoice_date' => $transaction['invoice_date'],
						 'reference_from' => $transaction['reference_from'],
						 'dr_amount' => $transaction['dr_amount'],
						 'cr_amount' => $transaction['cr_amount'],
						 'balance' => $balance,
						 'inv_month' => date('m', strtotime($transaction['invoice_date'])),
						 'due_date' => $transaction['due_date']
						 ];
						 
			} else if($resultrow->category=='SUPPLIER') {
				
				$dr_total += $transaction['dr_amount'];
				$cr_total += $transaction['cr_amount'];
				
				$balance += $balance_prnt;
				
				if($transaction['dr_amount'] > 0)
					$dr_amount = number_format($transaction['dr_amount'],2);
				else if($transaction['dr_amount'] < 0)
					$dr_amount = '('.number_format($transaction['dr_amount']*-1,2).')';
				else $dr_amount = '';
				
				if($transaction['cr_amount'] > 0)
					$cr_amount = number_format($transaction['cr_amount'],2);
				else if($transaction['dr_amount'] < 0)
					$cr_amount = '('.number_format($transaction['cr_amount']*-1,2).')';
				else $cr_amount = '';
				
				if($balance_prnt > 0)
					$balance_prnt = number_format($balance_prnt,2);
				else if($balance_prnt < 0)
					$balance_prnt = '('.number_format(($balance_prnt*-1),2).')';
				else $balance_prnt = '';
			
				$res[] = ['invoice_date' => $transaction['invoice_date'],
						 'reference_from' => $transaction['reference_from'],
						 'dr_amount' => $transaction['dr_amount'],
						 'cr_amount' => $transaction['cr_amount'],
						 'balance' => $balance,
						 'inv_month' => date('m', strtotime($transaction['invoice_date'])),
						 'due_date' => $transaction['due_date'] ];
			} 
			
		} 
		
		return $res;
	}
	

	private function getOpeningBalance($results) {
		
		$dramount = $cramount = 0;
		foreach($results as $row) {
			
			if($row->transaction_type=='Dr')
				$dramount += $row->amount;
			else
				$cramount += $row->amount;
			
		}
		
		$balance = $dramount - $cramount;
		$type = ($balance > 0)?'Dr':'Cr';
		
		$arrSummarry = ['type' => 'OB', 
						  'amount' => $balance,
						  'transaction_type' => $type
						 ];

		return $arrSummarry;
		
	}
	
	private function SortByAccount($results) {
		$childs = array();
		foreach($results as $item)
			$childs[$item->account_master_id][] = $item;
		return $childs;
	}
	private function SortByVoucher($results) {
		$childs = array();
		foreach($results as $item)
			$childs[$item->voucher_name][] = $item;
		return $childs;
	}
	
	private function SortByAccountOS($results) {
		$childs = array();
		foreach($results as $item)
			$childs[$item['account_master_id']][] = $item;
		return $childs;
	}
	
	private function SortByCategory($results) {
		
		$childs = array();
		foreach($results as $items)
		  foreach($items as $item)
			$childs[$item->account_category_id][$item->account_master_id][] = $item;
			
		return $childs;
	}
	
	private function SortByGroup($results) {
		
		$childs = array();
		foreach($results as $items)
		  foreach($items as $item)
			$childs[$item->account_group_id][$item->account_master_id][] = $item;
			
		return $childs;
	}
	
	private function SortByType($results) {
		
		$childs = array();
		foreach($results as $items)
		  foreach($items as $item)
			$childs[$item->category][$item->account_master_id][] = $item;
			
		return $childs;
		
	}

	public function dataExport()
	{
		$data = array();	//echo '<pre>';print_r(Input::all());exit;
		$datareport[] = [strtoupper(Session::get('company')),'','',''];
		$datareport[] = ['','','','','','',''];
		
		if(Input::get('search_type')=='summary') {
			$voucher_head = 'VAT Report Summary';
			$reports = $this->accountmaster->getVatSummary(Input::all()); 
			$datareport[] = ['','',strtoupper($voucher_head),'',''];
			$datareport[] = ['','','','','','',''];
			
			$datareport[] = ['SI.No','Group Name','Account ID','Account Name','VAT Amount'];
			$datareport[] = ['','','','','','',''];
			$i = $vat = 0;
			foreach($reports as $report) {
				$i++; 
				$vat = ($report->transaction_type == 'Cr')?($vat - $report->cl_balance):($report->cl_balance + $vat);
				//$vat += $report->cl_balance;
				$datareport[] = [$i,$report->group_name,$report->account_id,$report->master_name,($report->cl_balance < 0)?'('.number_format($report->cl_balance,2).')':number_format($report->cl_balance,2)];
			}
			$datareport[] = ['','','','Total VAT Payable',($vat < 0)?'('.number_format($vat*-1,2).')':number_format($vat,2)];
			
		} else if(Input::get('search_type')=='detail') {
			$voucher_head = 'VAT Report Detail';
			$reports = $this->makeTreeTyp($this->accountmaster->getVatDetail(Input::all()));//
			$datareport[] = ['','','','',$voucher_head,'','','',''];
			$datareport[] = ['','','','','','',''];
			
			$datareport[] = ['SI.No','Voucher No.','Voucher Date','Type','Acc.Desc','TRN No','Gross Amt','Net Amt.','VAT Amt.'];
			$datareport[] = ['','','','','','',''];
			
			$vatinput = $vatoutput = 0;
			foreach($reports as $key => $report) { 
				if($key=='Dr')
					$datareport[] = ['VAT INPUT','','','','','','','',''];
				else
					$datareport[] = ['VAT OUTPUT','','','','','','','',''];
				$i=0;
				foreach($report as $row) {
					$i++;
					if($row->transaction_type=='Dr') {
						if($row->trtype=='Cr') {
							$vat_amount = '('.number_format($row->vat_amount,2).')';
							$vatinput += ($row->vat_amount*-1);
						} else {
							$vatinput += $row->vat_amount;
							$vat_amount = number_format($row->vat_amount,2);
						}
						 
					} else {
						if($row->trtype=='Dr') {
							$vat_amount = '('.number_format($row->vat_amount,2).')';
							$vatoutput += ($row->vat_amount*-1);
						} else {
							$vatoutput += $row->vat_amount;
							$vat_amount = number_format($row->vat_amount,2);
						}
					}
					
					$gross_total = ($row->voucher_type=='PC' || $row->voucher_type=='SIN')?($row->net_total - $vat_amount):$row->gross_total;
					$datareport[] = [$i,$row->voucher_no,date('d-m-Y',strtotime($row->voucher_date)),
									$row->voucher_type,$row->master_name,$row->trn_no,number_format($gross_total,2),
									number_format($row->net_total,2),$vat_amount];
				}
				
				if($key=='Dr')
					$datareport[] = ['','','','','','VAT INPUT TOTAL:','','',number_format($vatinput,2)];
				else
					$datareport[] = ['','','','','','VAT OUTPUT TOTAL:','','',number_format($vatoutput,2)];
			}
			
			$payable = $vatoutput - $vatinput; 
			$payable = ($payable < 0)?$payable*-1:$payable;
			
			$datareport[] = ['','','','','','TOTAL INPUT:','','',number_format($vatoutput,2)];
			$datareport[] = ['','','','','','TOTAL OUTPUT:','','',number_format($vatinput,2)];
			$datareport[] = ['','','','','','VAT PAYABLE:','','',number_format($payable,2)];
			
		} else if(Input::get('search_type')=='partywise') {
			$voucher_head = 'VAT Payable Report(Partywise)';
			$reports = $this->makeTreeParty($this->accountmaster->getVatDetail(Input::all()));//DB::raw('"GI" AS type')
			
			$datareport[] = ['','','','',strtoupper($voucher_head),'','','',''];
			$datareport[] = ['','','','','','',''];
			$datareport[] = ['SI.','Voucher No.','Voucher Date','Type','Acc.Desc.','TRN No','Gross Amt.','Net Amt.','VAT Amt.'];
			foreach($reports as $key => $report) {
				foreach($report as $code => $rows) {
					$datareport[] = [$code.' - '.$rows[0]->tax_code];
					$i = $total = $nettotal = $vattotal = 0;
					foreach($rows as $row) { 
						$i++;
						$total += $row->gross_total;
						$nettotal += $row->net_total;
						$vattotal += $row->vat_amount;
						
						$datareport[] = ['i' => $i,
										 'voucher_no'	=> $row->voucher_no,
										 'voucher_date'	=> date('d-m-Y', strtotime($row->voucher_date)),
										 'voucher_type' => $row->voucher_type,
										 'master_name'	=> $row->master_name,
										 'trn_no'		=> $row->trn_no,
										 'gross_total'	=> number_format($row->gross_total,2),
										 'net_total'	=> number_format($row->net_total,2),
										 'vat_amount'	=> number_format($row->vat_amount,2)
										];
					}
					$datareport[] = ['','','','','','','','Total',number_format($vattotal,2)];
				}
				$datareport[] = ['','','','','','',''];
			}

		} else if(Input::get('search_type')=='areawise') {
			$voucher_head = 'VAT Payable Report(Areawise)';
			$result = $this->accountmaster->getVatDetail(Input::all());
			$reports = $this->makeTreeAr($result['sales']);
			
			$datareport[] = ['','','','',strtoupper($voucher_head),'','','',''];
			$datareport[] = ['','','','','','',''];
			$datareport[] = ['SI.','Inv No.','Tr.Date','Tr.Type','Acc.Desc.','TRN No','Gross Amt.','Net Amt.','VAT Amt.'];
			$i = $net_total = $net_nettotal = $net_vattotal = 0;
			foreach($reports as $area => $report) { 
				$area_total = $area_nettotal = $area_vattotal = 0;
				$sr_total = $sr_vattotal = $zr_total = $zr_vattotal = $ex_total = $ex_vattotal = 0;
				$datareport[] = ['Area', $area];
				foreach($report as $code => $rows) {
					$datareport[] = ['Tax Code', $code];
					$total = $nettotal = $vattotal = 0;
					foreach($rows as $row) { 
						$i++;
						$total += $row->total;
						$nettotal += $row->net_total;
						$vattotal += $row->vat_amount;
						
						$datareport[] = ['i' => $i,
										 'voucher_no'	=> $row->voucher_no,
										 'voucher_date'	=> date('d-m-Y', strtotime($row->voucher_date)),
										 'voucher_type' => $row->type,
										 'master_name'	=> $row->master_name,
										 'trn_no'		=> $row->vat_no,
										 'gross_total'	=> number_format($row->total,2),
										 'net_total'	=> number_format($row->net_total,2),
										 'vat_amount'	=> number_format($row->vat_amount,2)
										];
					}
					$datareport[] = ['','','','','',$code.' Total',number_format($total,2),number_format($nettotal,2),number_format($vattotal,2)];
					$area_total += $total;
					$area_nettotal += $nettotal;
					$area_vattotal += $vattotal;
					
					if($code=='SR') {
						$sr_total = $total; $sr_vattotal = $vattotal;
					} elseif($code=='ZR') {
						$zr_total = $total; $zr_vattotal = $vattotal;
					} elseif($code=='EX') {
						$ex_total = $total; $ex_vattotal = $vattotal;
					}
				}
				$datareport[] = ['','','','','','Total SR Sales',number_format($sr_total,2),'Total SR:',number_format($sr_vattotal,2)];
				$datareport[] = ['','','','','','Total EX Sales',number_format($ex_total,2),'Total EX:',number_format($ex_vattotal,2)];
				$datareport[] = ['','','','','','Total ZR Sales',number_format($zr_total,2),'Total ZR:',number_format($zr_vattotal,2)];
				$datareport[] = ['','','','','',$area.' Total',number_format($area_total,2),number_format($area_nettotal,2),number_format($area_vattotal,2)];
			}
			
			$net_total += $area_total;
			$net_nettotal += $area_nettotal;
			$net_vattotal += $area_vattotal;
			$datareport[] = ['','','','','','Net Total',number_format($net_total,2),number_format($net_nettotal,2),number_format($net_vattotal,2)];
			
		} else if(Input::get('search_type')=='summary_taxcode') {
			$voucher_head = 'VAT Report(Tax Code Summary)';
			$reports = $this->makeTreeParty($this->accountmaster->getVatDetail(Input::all()));
			
			$datareport[] = ['',strtoupper($voucher_head)];
			$datareport[] = ['','','','','','',''];
			$datareport[] = ['VAT Account','VAT Amt'];
		}
		// echo '<pre>';print_r($results);exit;
							
		//echo $voucher_head.'<pre>';print_r($datareport);exit;
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
	
	 public function getPrint(Request $request)
	 {
	 	$data = array(); //echo '<pre>';print_r($request->all());exit;
	 	
	 	$data = $pdctransactions = array(); $opn_balnce = null;
		$frmdate = Input::get('date_from');
		$todate = Input::get('date_to');
		Input::merge(['curr_from_date' => $this->acsettings->from_date]); 
		if(Input::get('search_type')=='detail') {
		    $voucher_head = 'Daily Report Detail';
		$transaction = $this->accountmaster->getDailyReport(Input::all()); 
			//echo '<pre>';print_r($transaction);exit;
			$sales = $this->SortByVoucher($transaction['sales']);
			$sales_return=$this->SortByVoucher($transaction['salesr']);
			$purchase=$this->SortByVoucher($transaction['purchase']);
			$purchaser=$this->SortByVoucher($transaction['purchaser']);
			$rv=$this->SortByVoucher($transaction['rv']);
			$pv=$this->SortByVoucher($transaction['pv']);
			//echo '<pre>';print_r($rv);exit;
			/*foreach($reports as $key => $report){
			    echo '<pre>';print_r($report[0]->type);exit;
			}*/
			
		   $titles = ['main_head' => 'Daily Report Detail','subhead' => 'Daily Report Detail']; 
		}
		else {
		$voucher_head = 'Daily Report Summary';
		$transaction = $this->accountmaster->getDailyReport(Input::all()); 
			//echo '<pre>';print_r($transaction);exit;
		$reports = $this->SortByAccount($transaction);
	//	echo '<pre>';print_r($reports);exit;
		// $opn_balnce = $this->getOpeningBalance($this->accountmaster->getdailySummary(Input::all()));
	
		$titles = ['main_head' => 'Daily Report Summary','subhead' => 'Daily Report Summary'];
			
		}
		
	 	//echo '<pre>';print_r($transaction);exit;
	 	return view('body.dailyreport.preprint')
	 				->withVoucherhead($voucher_head)
					->withReports(isset($reports)?$reports:'')
					->withSales(isset($sales)?$sales:'')
					->withSalesr(isset($sales_return)?$sales_return:'')
					->withPurchase(isset($purchase)?$purchase:'')
					->withPurchaser(isset($purchaser)?$purchaser:'')
					->withrv(isset($rv)?$rv:'')
					->withpv(isset($pv)?$pv:'')
					 ->withFromdate($frmdate)
					 ->withTodate($todate)
					 ->withOpenbalance($opn_balnce)
					 ->withTransaction($transaction)
	 				->withTitles($titles)
					->withSettings($this->acsettings)
					->withUrl('daily_report')
					->withSearchtype(Input::get('search_type'))
	 				->withData($data);
	 }	
}


