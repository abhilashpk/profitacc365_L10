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
use DB;

class VatReportController extends Controller
{
   
	protected $accountmaster;


	public function __construct(AccountmasterInterface $accountmaster) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->accountmaster = $accountmaster;
		$this->middleware('auth');
	}
	
	public function index() {
		$data = array(); $reports = null;
		$voucher_head = 'Vat Report';
		
		//CHECK DEPARTMENT.......
		if(Session::get('department')==1) { //if active...
			$deptid = Auth::user()->department_id;
			if($deptid!=0)
				$departments = DB::table('department')->where('id',$deptid)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
			else {
				$departments = DB::table('department')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
				//$deptid = $departments[0]->id;
			}
			$is_dept = true;
		} else {
			$is_dept = false;
			$departments = [];
			//$deptid = '';
		}
		
		return view('body.vatreport.index')
					->withReports($reports)
					->withVoucherhead($voucher_head)
					->withType('')
					->withDepartments($departments)
					->withFromdate('')
					->withTodate('')
					->withSettings($this->acsettings)
					->withData($data);
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
	
	private function GroupByAccount($result) {
		
		$childs = array();
		foreach($result as $item)
			$childs[$item->acid][] = $item;
		
		return $childs;
		
	}
	
	private function CalculateVatSummary($result) {
		
		$childs = array();
		foreach($result as $item)
			$childs[$item->acid][] = $item;
			
		$arrSummarry = array(); $vat = 0;
		foreach($childs as $child) {
			$dramount = $cramount = 0;
			foreach($child as $row) {
				$group_name = $row->group_name;
				$account_id = $row->account_id;
				$master_name = $row->master_name;
				
				if($row->transaction_type=='Dr')
					$dramount += $row->amount;
				else
					$cramount += $row->amount;
			}
			$amount = $dramount - $cramount;
			$arrSummarry[] = (object) ['group_name' => $group_name, 
									  'account_id' => $account_id, 
									  'master_name' => $master_name,
									  'amount' => $amount 
									  ];
									  
			//$vat += $amount; //($amount > 0)?($vat-$amount):($amount+$vat);
									  
		}	
		return $arrSummarry; //$result = ['summary' => $arrSummarry, 'vatpayable' => $vat];
	}
	
	private function CalculateVatDetail($result) {
		
		$childs = array();
		foreach($result as $item)
			$childs[$item->acid][] = $item;
		return $childs;	
		/* $arrSummarry = array(); $vat = 0;
		foreach($childs as $child) {
			$dramount = $cramount = 0;
			foreach($child as $row) {
				$group_name = $row->group_name;
				$account_id = $row->account_id;
				$master_name = $row->master_name;
				
				if($row->transaction_type=='Dr')
					$dramount += $row->amount;
				else
					$cramount += $row->amount;
			}
			$amount = $dramount - $cramount;
			$arrSummarry[] = (object) ['group_name' => $group_name, 
									  'account_id' => $account_id, 
									  'master_name' => $master_name,
									  'amount' => $amount 
									  ];
									  
									  
		}	
		return $arrSummarry; */
	}
	
	private function getVatSummary($reports) {
		$payable = 0;
		foreach($reports as $key => $report) {
			$grossAmt = $discount = $taxable = $vatAmt = $netAmt = $dramount = $cramount = 0;
			foreach($report as $row) {
				//if($row->trtype==$row->transaction_type) {
					$name = $row->vat_name;
					$grossAmt += $row->gross_total;
					$discount += $row->discount;
					$taxable += ($row->gross_total - $row->discount);
					$vatAmt += $row->vat_amount;
					$netAmt += $row->net_total;
					
					if($row->trtype=='Dr')
						$dramount += $row->vat_amount;
					else
						$cramount += $row->vat_amount;
				//}
			}
			$amount = $dramount - $cramount; $payable += $amount;
			$vatArr['vat'][] = (object)['name' => $name, 'gross_amt' => $grossAmt, 'discount' => $discount, 'taxable' => $taxable, 'vat_amt' => $vatAmt, 'net_amt' => $netAmt];
		}
		$vatArr['payable'] = $payable;
		return $vatArr;
	}
	
	public function getSearch()
	{
		//echo '<pre>';print_r(Input::all());exit;

		$data = array();
		Input::merge(['curr_from_date' => $this->acsettings->from_date]);
		$payable = 0;
		$code_type=Input::get('code_type');
		
		if(Input::get('search_type')=='summary') {
			$voucher_head = 'VAT Report Summary'; //echo '<pre>';print_r($this->accountmaster->getVatSummary(Input::all()));exit;
			$type = Input::get('search_type');
			//$reports = $this->CalculateVatSummary( $this->accountmaster->getVatSummary(Input::all()) ); 
			Input::merge(['search_type' => 'detail']);
			$result = $this->getVatSummary( $this->GroupByAccount($this->accountmaster->getVatDetail(Input::all())) ); //echo '<pre>';print_r($reports);exit;
			$reports = $result['vat'];
			$payable = $result['payable'];
			$view = 'preprint';
			
		} else if(Input::get('search_type')=='detail') {
			
			$date_from = '01-01-2021';
			$date_to = date('Y-m-d');
		
			$voucher_head = 'VAT Report Detail';
			$type = Input::get('search_type');
			//$reports = $this->makeTreeTyp($this->accountmaster->getVatDetail(Input::all()));
			$reports = $this->GroupByAccount($this->accountmaster->getVatDetail(Input::all()));//echo '<pre>';print_r($reports);exit;
			$view = 'preprint';
			
		} else if(Input::get('search_type')=='partywise') {
			$voucher_head = 'VAT Payable Report(Partywise)';
			$type = Input::get('search_type');
			$reports = $this->makeTreeParty($this->accountmaster->getVatDetail(Input::all()));//DB::raw('"GI" AS type')
			//echo '<pre>';print_r($reports);exit;
			$view = 'preprint';

		} else if(Input::get('search_type')=='areawise') {
			$voucher_head = 'VAT Payable Report(Areawise)';
			$type = Input::get('search_type');
			$result = $this->accountmaster->getVatDetail(Input::all());
			/*$inputexp = $this->makeTreeAr($result['inputexp']);
			 $purchase = $this->makeTreeAr($result['purchase']);
			$purchase_ret = $this->makeTreeAr($result['purchase_ret']); 
			$sales_ret = $this->makeTreeAr($result['sales_ret']);*/
			
			$reports = $this->makeTreeAr($result['sales']);
			
			//$reports = ['inputexp' => $inputexp, 'purchase' => array_merge($purchase,$sales_ret), 'sales' => array_merge($sales,$purchase_ret)];
			//echo '<pre>';print_r($reports);exit;
			$view = 'preprint';
			
		} else if(Input::get('search_type')=='summary_taxcode') {
			$voucher_head = 'VAT Report(Tax Code Summary)';
			$type = Input::get('search_type');
			$reports = $this->makeTreeParty($this->accountmaster->getVatDetail(Input::all()));
			//echo '<pre>';print_r($reports);exit;
			$view = 'preprint';
		}
		else if(Input::get('search_type')=='tax_code') {
			$voucher_head = 'VAT Report(Tax Code )';
			$type = Input::get('search_type');
			$result = $this->accountmaster->getVatDetail(Input::all());
			$sales = $this->makeTreeTc($result['sales']);
			$purchase = $this->makeTreeTc($result['purchase']);
			$reports = ['purchase' => $purchase, 'sales' => $sales];
			//echo '<pre>';print_r($reports);exit;
			$view = 'preprint';
		}
		
		
		//echo '<pre>';print_r($reports);exit;
		return view('body.vatreport.'.$view)
					->withReports($reports)
					->withVoucherhead($voucher_head)
					->withCodetype($code_type)
					->withType($type)
					->withPayable($payable)
					->withFromdate(Input::get('date_from'))
					->withTodate(Input::get('date_to'))
					->withSettings($this->acsettings)
					->withData($data);
	}
	
	public function dataExport()
	{
		$data = array();	//echo '<pre>';print_r(Input::all());exit;
		$datareport[] = ['','','','',strtoupper(Session::get('company')),'','',''];
		$datareport[] = ['','','','','','',''];
		
		if(Input::get('search_type')=='summary') {
			$voucher_head = 'VAT Report Summary';
			$type = Input::get('search_type');
			//$reports = $this->CalculateVatSummary( $this->accountmaster->getVatSummary(Input::all()) ); 
			Input::merge(['search_type' => 'detail']);
			//$reports = $this->accountmaster->getVatSummary(Input::all()); 
			$result = $this->getVatSummary( $this->GroupByAccount($this->accountmaster->getVatDetail(Input::all())) );
			$reports = $result['vat'];
			$payable = $result['payable'];
			$datareport[] = ['','',strtoupper($voucher_head),'',''];
			$datareport[] = ['','','','','','',''];
			//echo '<pre>';print_r($reports);exit;
			$datareport[] = ['VAT Account Name','Gross Amount','Discount','Taxable Amount','VAT Amount','Net Amount'];
			$datareport[] = ['','','','','','',''];
			$i = $vat = 0;
			foreach($reports as $report) {
				$i++; 
				$vat = ($report->vat_amt < 0)?'('.number_format(($report->vat_amt*-1),2).')':number_format($report->vat_amt,2);
				//$vat += $report->cl_balance;
				$datareport[] = [$report->name,number_format($report->gross_amt,2),number_format($report->discount,2),number_format($report->taxable,2),$vat,number_format($report->net_amt,2)];
			}
			if($payable < 0)
				$vatsum = '('.number_format($payable * -1, 2).')';
			else
				$vatsum = number_format($payable, 2);
			
			$datareport[] = ['','','','Total VAT Payable',$vatsum];
			
		} else if(Input::get('search_type')=='detail') {
			$voucher_head = 'VAT Report Detail';
			$reports = $this->makeTreeTyp($this->accountmaster->getVatDetail(Input::all()));//
			$datareport[] = ['','','','',$voucher_head,'','','',''];
			$datareport[] = ['','','','','','',''];
			
			$datareport[] = ['Voucher No.','Voucher Date','Type','Account Description','TRN No','Gross Amount','Discount','Taxable Amount','VAT Amount', 'Net Amount'];
			$datareport[] = ['','','','','','',''];
			
			$payable = $vatinput = $vatoutput = 0;
			foreach($reports as $key => $report) { 
				if($key=='Dr')
					$datareport[] = ['VAT INPUT','','','','','','','',''];
				else
					$datareport[] = ['VAT OUTPUT','','','','','','','',''];
				
				$dramount = $cramount = $vatamount = 0;
				
				foreach($report as $row) {
					if($row->trtype==$row->transaction_type) {
						$vatname = $row->vat_name;
						if($row->trtype=='Dr')
							$dramount += $row->vat_amount; 
						else
							$cramount += $row->vat_amount;
													
						/* if($row->transaction_type=='Dr') {
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
						} */
						
						$gross_total = ($row->voucher_type=='PC' || $row->voucher_type=='SIN')?($row->net_total - $row->vat_amount):$row->gross_total;
						$vatamount += ($row->trtype=='Dr')?$row->vat_amount:($row->vat_amount*-1);
														
						//$gross_total = ($row->voucher_type=='PC' || $row->voucher_type=='SIN')?($row->net_total - $vat_amount):$row->gross_total;
						
						$datareport[] = [$row->voucher_no,date('d-m-Y',strtotime($row->voucher_date)),
										$row->voucher_type,$row->master_name,$row->trn_no,number_format($gross_total,2),number_format($row->discount,2),
										number_format(($row->gross_total - $row->discount),2),($row->trtype=='Dr')?$row->vat_amount:'('.$row->vat_amount.')',
										number_format($row->net_total,2)];
					}
				}
				$amount = $dramount - $cramount; $payable += $amount; $vatar[$key]['name'] = $vatname; $vatar[$key]['amt'] = $amount;
				
				if($key=='Dr')
					$datareport[] = ['','','','','','VAT INPUT TOTAL:','','',number_format($vatamount,2)];
				else
					$datareport[] = ['','','','','','VAT OUTPUT TOTAL:','','',number_format($vatamount,2)];
			}
			
			foreach($vatar as $val) {
				$datareport[] = ['','','','','',$val['name'].' Total',number_format($val['amt'],2)];
			}
			
			$datareport[] = ['','','','','','VAT PAYABLE:','','',number_format($payable,2)];
			
			/* $payable = $vatoutput - $vatinput; 
			$payable = ($payable < 0)?$payable*-1:$payable;
			
			$datareport[] = ['','','','','','TOTAL INPUT:','','',number_format($vatoutput,2)];
			$datareport[] = ['','','','','','TOTAL OUTPUT:','','',number_format($vatinput,2)];
			$datareport[] = ['','','','','','VAT PAYABLE:','','',number_format($payable,2)]; */
			
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
		
		else if(Input::get('search_type')=='tax_code') {
			$voucher_head = 'VAT Report(Tax Code )';
			$type = Input::get('search_type');
			$result = $this->accountmaster->getVatDetail(Input::all());
			$reports = $this->makeTreeTc($result['sales']);
			$reportp = $this->makeTreeTc($result['purchase']);

			$datareport[] = ['','','','',strtoupper($voucher_head),'','','',''];
			$datareport[] = ['','','','','','',''];
			$datareport[] = ['SI.','Inv No.','Tr.Date','Tr.Type','Acc.Desc.','TRN No','Inv.Gross Amt.','Inv.Net Amt.','VAT Amt.'];

			$datareport[] = ['VAT INPUT','','','','','','',''];
			$i=$totalin=0; 
			foreach($reportp as $key => $report){
				$datareport[] = ['Tax Code', $key];
				$total = $nettotal = $vattotal = 0;
				foreach($report as $row) { 
				$i++;
			    $total += $row->total;
				$nettotal += $row->net_amount;
				$vattotal += $row->vat_amount;
				$datareport[] = ['i' => $i,
										 'voucher_no'	=> $row->voucher_no,
										 'voucher_date'	=> date('d-m-Y', strtotime($row->voucher_date)),
										 'voucher_type' => 'PI',
										 'master_name'	=> $row->master_name,
										 'trn_no'		=> $row->vat_no,
										 'gross_total'	=> number_format($row->total,2),
										 'net_total'	=> number_format($row->net_amount,2),
										 'vat_amount'	=> number_format($row->vat_amount,2)
										];
    
			}
			$datareport[] = ['','','','','',' Total',number_format($total,2),number_format($nettotal,2),number_format($vattotal,2)];
			$totalin += $vattotal;
		}
		$datareport[] = ['','','','','','','','',number_format($totalin,2)];

		$datareport[] = ['','','','','','',''];
		$datareport[] = ['','','','','','',''];
		$datareport[] = ['VAT OUTPUT','','','','','','',''];
		$i=$totalout=0; 
		foreach($reports as $key => $report){
			$datareport[] = ['Tax Code', $key];
			$total = $nettotal = $vattotal = 0;
			foreach($report as $row) { 
			$i++;
			$total += $row->total;
			$nettotal += $row->net_total;
			$vattotal += $row->vat_amount;
			$datareport[] = ['i' => $i,
									 'voucher_no'	=> $row->voucher_no,
									 'voucher_date'	=> date('d-m-Y', strtotime($row->voucher_date)),
									 'voucher_type' => 'SI',
									 'master_name'	=> $row->master_name,
									 'trn_no'		=> $row->vat_no,
									 'gross_total'	=> number_format($row->total,2),
									 'net_total'	=> number_format($row->net_total,2),
									 'vat_amount'	=> number_format($row->vat_amount,2)
									];

		}
		$datareport[] = ['','','','','',' Total',number_format($total,2),number_format($nettotal,2),number_format($vattotal,2)];
		$totalout += $vattotal;
	}
	$datareport[] = ['','','','','','','','',number_format($totalout,2)];
	$payable=0;
    $payable = $totalout - $totalin;
	$datareport[] = ['','','','','','',''];
	$datareport[] = ['','','','','','',''];
	$datareport[] = ['','','','','','','','Total Output',number_format($totalout,2)];
	$datareport[] = ['','','','','','','','Total Input',number_format($totalin,2)];
    $datareport[] = ['','','','','','','','Vat Payable',number_format($payable,2)];
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
	
	public function getPrint()
	{
		$data = array();
		if(Input::get('search_type')=='summary') {
			$voucher_head = 'Vat Report Summary';
			$reports = $this->accountmaster->getVatSummary(Input::all()); 
			$titles = ['main_head' => 'Vat Report Summary','subhead' => 'Vat Report Summary'];
			
		} else if(Input::get('search_type')=='detail') {
			$voucher_head = 'Vat Report Detail';
			$reports = $this->accountmaster->getVatDetail(Input::all());
			$titles = ['main_head' => 'Vat Report Detail','subhead' => 'Vat Report Detail'];
		}
		
		//echo '<pre>';print_r($results);exit;
		return view('body.vatreport.print')
					->withVoucherhead($voucher_head)
					->withReports($reports)
					->withTitles($titles)
					->withUrl('vat_report')
					->withData($data);
	}	
}


