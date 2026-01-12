<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\AccountMaster\AccountMasterInterface;
use App\Repositories\Acgroup\AcgroupInterface;

use App\Http\Requests;
use Notification;
use Input;
use Session;
use App;
use DB;
use Excel;
use Auth;

class TrialBalanceController extends Controller
{
   
	protected $accountmaster;
	protected $acgroup;

	public function __construct(AccountMasterInterface $accountmaster, AcgroupInterface $acgroup) {

		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );$this->accountmaster = $accountmaster;
		$this->acgroup = $acgroup;
		$this->middleware('auth');
	}
	
	public function index() {
		$data = array();
		$acmasters = [];//$this->accountmaster->accountMasterList();
		$groups = $this->acgroup->acgroupList();

		DB::statement('DELETE t1 FROM account_transaction t1, account_transaction t2 WHERE  t1.id > t2.id AND (t1.voucher_type = t2.voucher_type AND t1.voucher_type_id = t2.voucher_type_id AND t1.account_master_id = t2.account_master_id AND t1.transaction_type = t2.transaction_type AND t1.amount = t2.amount AND t1.reference = t2.reference AND t1.reference_from = t2.reference_from AND t1.reference_from = t2.reference_from AND t1.other_info = t2.other_info AND t1.status = t2.status AND t1.deleted_at = t2.deleted_at)');
			
		$this->AcUpdate($this->makeGroup( $this->accountmaster->updateUtility('CB')) );

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
		
		return view('body.trialbalance.index')
					->withAcmasters($acmasters)
					->withGroups($groups)
					->withDepartments($departments)
					->withIsdept($is_dept)
					->withSettings($this->acsettings)
					->withData($data);
	}
	

	protected function AcUpdate($results)
	{
		$arrSummarry = array();
		foreach($results as $result)
		{
			$arraccount = array(); 
			$dramount = $cramount = 0;
			foreach($result as $row) {
				$cl_balance = $row->cl_balance;
				$account_id = $row->id;
				if($row->transaction_type=='Dr') {
					$amountD = ($row->amount < 0)?(-1*$row->amount):$row->amount;
					$dramount += $amountD;
				} else {
					$amountC = ($row->amount < 0)?(-1*$row->amount):$row->amount;
					$cramount += $amountC;
				}
			}
			
			$amount = $dramount - $cramount;
			//$amount = ($amount < 0)?(-1*$amount):$amount;
			if($amount != $cl_balance) {
				//update the closing balance as amount.....
				$this->accountmaster->updateClosingBalance($account_id, $amount);
			}
				
		}
		return true;
	}


	protected function makeGroup($result)
	{
		$childs = array();
		foreach($result as $item)
			$childs[$item->id][] = $item;

		return $childs;
	}

	
	protected function makeTree($result)
	{
		$childs = array();
		foreach($result as $item)
			$childs[$item['account_group_id']][] = $item;

		/* foreach($result as $item) if (isset($childs[$item['id']]))
			$item['childs'] = $childs[$item['id']]; */
		/* foreach($result as $item)
			$childs[$item['id']][] = $item; */
		
		return $childs;
	}
	
	protected function makeTreeAc($result)
	{
		$childs = array();
		foreach($result as $item)
			$childs[$item['account_group_id']][$item['id']][] = $item;

		/* foreach($result as $item) if (isset($childs[$item['id']]))
			$item['childs'] = $childs[$item['id']]; */
		/* foreach($result as $item)
			$childs[$item['id']][] = $item; */
		
		return $childs;
	}
	
	protected function OBmakeSummary($results, $type=null)
	{
		$arrSummarry = array();
		
		foreach($results as $rows)
		{
			$cr_total = $dr_total = 0;
			foreach($rows as $row) {
			    
			    if($row['transaction_type']=='Dr') { 
					$dr_amount = $row['op_balance'];
					$cr_amount = 0;
				} else {
					$cr_amount = $row['op_balance'];
					$dr_amount = 0;
				}
				$cr_amount = ($cr_amount < 0)?$cr_amount*-1:$cr_amount;
				$cr_total += $cr_amount;
				$dr_total += $dr_amount;
			    $group_name = $row['group_name'];
																		

				
			}
			$arrSummarry[] = ['name' => $group_name, 'cr_amount' => $cr_total, 'dr_amount' => $dr_total];

		}
		return $arrSummarry;
	}
	
	protected function makeSummary($results, $type=null)
	{
		$arrSummarry = array();
		foreach($results as $rows)
		{
			$dramount = $cramount = 0;
			foreach($rows as $row) {
				$group_name = $row['group_name'];
				if($row['transaction_type']=='Dr')
					$dramount += ($type=='cl')?$row['cl_balance']:$row['op_balance'];
				else
					$cramount += ($type=='cl')?$row['cl_balance']:$row['op_balance'];
				
			}
			$arrSummarry[] = ['name' => $group_name, 'cr_amount' => $cramount, 'dr_amount' => $dramount];

		}
		return $arrSummarry;
	}
	
	protected function makeSummaryAc($results, $type=null)
	{
		$arrSummarry = array();
		foreach($results as $result)
		{
			$arraccount = array(); 
			foreach($result as $rows) {
				$dramount = $cramount = $amount = 0;
				$group_name = $rows[0]['group_name'];
				
				foreach($rows as $row) {
					$trtype = $row['type'];
					$account_name = $row['master_name'];
					
					if($row['transaction_type']=='Dr') {
						if($type=='cl') {
							$cl_balance = $row['cl_balance']; //($row['cl_balance'] < 0)?(-1*$row['cl_balance']):$row['cl_balance'];
							$dramount += $cl_balance;
						} else {
							$op_balance = $row['op_balance']; //($row['op_balance'] < 0)?(-1*$row['op_balance']):$row['op_balance'];
							$dramount += $op_balance; 
						}
					} else {
						if($type=='cl') {
							$cl_balance = ($row['cl_balance'] < 0)?(-1*$row['cl_balance']):$row['cl_balance']; //$row['cl_balance']; AUG 15 chnged
							$cramount += $cl_balance;
						} else {
							$op_balance = $row['op_balance']; //($row['op_balance'] < 0)?(-1*$row['op_balance']):$row['op_balance'];
							$cramount += $op_balance; 
						}
					} //$amount += $row['cl_balance'];
				} 
				
				$amount = $dramount - $cramount;
				$trtype = ($amount < 0)?'Cr':'Dr';
				$amount = ($amount < 0)?(-1*$amount):$amount;
				$arraccount[] = ['master_name' => $account_name, 'amount' => $amount, 'type' => $trtype];
			}
			
			$arrSummarry[] = ['group_name' => $group_name, 'accounts' => $arraccount];
		}
		return $arrSummarry;
	}
	
	protected function makeSummaryAcWithOb($results, $obresults, $type=null)
	{
		$arrSummarry = array();
		foreach($results as $k => $result)
		{
			$arraccount = array(); 
			foreach($result as $key => $rows) {
				$dramount = $cramount = $amount = $obdramount = $obcramount = $obamount = 0; $trtypeob = '';
				$group_name = $rows[0]['group_name'];
				
				foreach($rows as $row) {
					$trtype = $row['type'];
					$account_name = $row['master_name'];
					
					if($row['transaction_type']=='Dr') {
						if($type=='cl') {
							$cl_balance = $row['cl_balance']; //($row['cl_balance'] < 0)?(-1*$row['cl_balance']):$row['cl_balance'];
							$dramount += $cl_balance;
						} else {
							$op_balance = $row['op_balance']; //($row['op_balance'] < 0)?(-1*$row['op_balance']):$row['op_balance'];
							$dramount += $op_balance; 
						}
					} else {
						if($type=='cl') {
							$cl_balance = ($row['cl_balance'] < 0)?(-1*$row['cl_balance']):$row['cl_balance']; //$row['cl_balance']; AUG 15 chnged
							$cramount += $cl_balance;
						} else {
							$op_balance = $row['op_balance']; //($row['op_balance'] < 0)?(-1*$row['op_balance']):$row['op_balance'];
							$cramount += $op_balance; 
						}
					} //$amount += $row['cl_balance'];
				} 
				$amount = bcsub($dramount, $cramount, 2); //(int)($dramount - $cramount);
				$trtype = ($amount < 0)?'Cr':'Dr';
				$amount = ($amount < 0)?(-1*$amount):$amount;
				
				//GETTING OPENING BALANCE....
				if(isset($obresults[$k][$key])) {
					foreach($obresults[$k][$key] as $obrow) {
						if($obrow['transaction_type']=='Dr') {
							if($type=='cl') {
								$cl_balance = $obrow['cl_balance'];
								$obdramount += $cl_balance;
							} else {
								$op_balance = $obrow['op_balance']; 
								$obdramount += $op_balance; 
							}
						} else {
							if($type=='cl') {
								$cl_balance = ($obrow['cl_balance'] < 0)?(-1*$obrow['cl_balance']):$obrow['cl_balance']; 
								$obcramount += $cl_balance;
							} else {
								$op_balance = $obrow['op_balance'];
								$obcramount += $op_balance; 
							}
						}
					}
					$obamount = $obdramount - $obcramount;
					$trtypeob = ($obamount < 0)?'Cr':'Dr';
					$obamount = ($obamount < 0)?(-1*$obamount):$obamount;
				}
				if($amount > 0)
					$arraccount[] = ['master_name' => $account_name, 'amount' => $amount, 'type' => $trtype,'obamount' => $obamount, 'type_ob' => $trtypeob, 'balance' => ($amount + $obamount)];
			}
			
			$arrSummarry[] = ['group_name' => $group_name, 'accounts' => $arraccount];
		}
		return $arrSummarry;
	}
	
	protected function makeSummaryAc2($results)
	{
		$arrSummarry = array();
		foreach($results as $result)
		{
			$dramount = $cramount = $obamount = 0;
			$group_name = $result['group_name'];
			foreach($result['accounts'] as $row) {
				
				if($row['type']=='Dr')
					$dramount += $row['amount'];
				else
					$cramount += $row['amount'];
				
				$obamount = isset($row['obamount'])?$row['obamount']:0;
			}
				
			$arrSummarry[] = ['group_name' => $group_name, 'cr_amount' => $cramount, 'dr_amount' => $dramount, 'obamount' => $obamount];
		}
		return $arrSummarry;
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
	
	public function getSearch()
	{	//echo '<pre>';print_r(Input::all());exit;
		$data = array(); 
		Input::merge(['curr_from_date' => $this->acsettings->from_date]);
		$trresult = $this->accountmaster->getTrialBalance(Input::all());  //echo '<pre>';print_r($trresult);exit;
		$items = $trresult['tr'];  
		$voucher_head = $trresult['head']; 
		//$exclude = (==1)?true:false; 
		//echo '<pre>';print_r(Input::all());exit;
		if(Input::get('search_type')=='groupwise' || Input::get('search_type')=='groupwise_bal') {
			$results = $this->makeTree($items);
			
		} else if(Input::get('search_type')=='closing_groupwise' || Input::get('search_type')=='closing_groupwise_bal') {
			$trns = $this->makeTreeAc($items);
			$obtrns = $this->makeTreeAc($trresult['ob']); 
			$alltrns = $this->makeTreeAc($trresult['all']);  //echo '<pre>';print_r($alltrns);exit;	
			if(sizeof($alltrns) > 0)
			    $results =  $this->makeSummaryAcWithOb2($alltrns, $trns, $obtrns, 'cl');
			else
			    $results =  $this->makeSummaryAcWithOb($trns, $obtrns, 'cl');
	//	echo '<pre>';print_r($results);exit;
			//$results =  $this->makeTreeAc($items);
			
		} else if(Input::get('search_type')=='opening_summary') {
			$results = $this->OBmakeSummary($this->makeTree($items),'op');
			
		} else if(Input::get('search_type')=='closing_summary') {
			
			$trns = $this->makeTreeAc($items);
			$obtrns = $this->makeTreeAc($trresult['ob']); 
			$alltrns = $this->makeTreeAc($trresult['all']);
			//$results = $this->makeSummaryAc2($this->makeSummaryAcWithOb($alltrns, $trns, $obtrns, 'cl'));
			
			if(sizeof($alltrns) > 0)
			    $results =  $this->makeSummaryAcWithOb2($alltrns, $trns, $obtrns, 'cl');
			else
			   $results =  $this->makeSummaryAcWithOb($trns, $obtrns, 'cl');
			   
			$results = $this->makeSummaryAc2($results);
			//echo '<pre>';print_r($results);exit;
			
		} else if(Input::get('search_type')=='taged_summary') {
			$results = $this->makeTree($items);
			
		} else if(Input::get('search_type')=='group_taged') {
			
			$results = $this->makeTree($items);
			//$results = $items;
		
		
		} else if(Input::get('search_type')=='opening_group_taged') {
			
			$results = $this->makeTree($items);
		    
		} else if(Input::get('search_type')=='new_format') {
			
			$trns = $this->makeTreeAc($items);
			$obtrns = (Input::get('exclude')==1)?[]:$this->makeTreeAc($trresult['ob']); 
			$alltrns = $this->makeTreeAc($trresult['all']);  //echo '<pre>';print_r($trns);exit;	
			if(sizeof($alltrns) > 0)
			    $results =  $this->makeSummaryAcWithOb2($alltrns, $trns, $obtrns, 'cl');
			else if(sizeof($trns) > 0)
			    $results =  $this->makeSummaryAcWithOb3($trns, $obtrns, 'cl');
			else
				$results =  $this->makeSummaryAcWithOb4($obtrns, 'cl');
	        //	echo '<pre>';print_r($results);exit;
			//$results =  $this->makeTreeAc($items);
		}
		
		$titles = ['main_head' => 'Trial Balance YTD','subhead' => $voucher_head ];
		
		//echo '<pre>';print_r($results);exit;
		$crow = DB::table('parameter1')
		        ->join('currency','currency.id','=','parameter1.bcurrency_id')->select('currency.code')->first();
		//echo '<pre>';print_r($crow);exit;
		return view('body.trialbalance.print')
					->withResults($results)
					->withVoucherhead($voucher_head)
					->withTitles($titles)
					->withUrl('trial_balance')
					->withFromdate(Input::get('date_from'))
					->withTodate(Input::get('date_to'))
					->withSettings($this->acsettings)
					->withType(Input::get('search_type'))
					->withExl(Input::get('exclude'))
					->withGrpid(Input::get('group_id'))
					->withTrimzero(Input::get('trim_zero'))
					->withAccounts(Input::get('accounts_arr'))
					->withCurrency($crow->code)
					->withData($data);
	}
	
	
	public function dataExport() {
		
		$data = array(); 
		//echo '<pre>';print_r(Input::all());exit;
		Input::merge(['curr_from_date' => $this->acsettings->from_date]);
		$trresult = $this->accountmaster->getTrialBalance(Input::all()); 
		$items = $trresult['tr'];  //echo '<pre>';print_r($trresult);exit;
		$crow = DB::table('currency')->where('is_default',1)->select('code')->first();
		$currency=$crow->code;
		$voucher_head = $trresult['head'];
	//	echo '<pre>';print_r(Input::all());exit;
		$datareport[] = ['','',Session::get('company'),'',''];
		
		if(Input::get('search_type')=='groupwise' ||Input::get('search_type')=='opening_group_taged' ||Input::get('search_type')=='groupwise_bal') {
			$results = $this->makeTree($items);
			//echo '<pre>';print_r($results);exit;
			$balHd = (Input::get('search_type')=='groupwise_bal')?'Balance':'';
			$datareport[] = ['Account Group/Head','','Debit','Credit',$balHd];
			$cr_total = $dr_total = $crtotal = $drtotal = 0;
			foreach($results as $result) {
				
				$datareport[] = ['acnt' => $result[0]['group_name']];
				$grp_ctotal = $grp_dtotal = 0;
				foreach($result as $row) {
					
					if($row['transaction_type']=='Cr') {
						$amountc = $row['op_balance']; $amountd = ''; $crtotal += $amountc;
					} else {
						$amountd = $row['op_balance']; $amountc = ''; $drtotal += $amountd;
					}
					
					$datareport[] = ['blank' => '',
									 'master_name' => $row['master_name'],
									 'amountd'	=> ($amountd!='')?number_format($amountd,2):'',
									 'amountc'	=> ($amountc!='')?number_format($amountc,2):''
									];
									
					$amountc = ($amountc < 0)?$amountc*-1:$amountc;
					$cr_total += $amountc;
					$dr_total += $amountd;
					$grp_ctotal += $amountc;
					$grp_dtotal += $amountd;
				}
				
				if(Input::get('search_type')=='groupwise_bal') {
					$balance = $grp_dtotal - $grp_ctotal;
				} else 
					$balance = '';
				$datareport[] = ['blank' => '',
							 'Total' => '',
							 'totald'	=> number_format($grp_dtotal,2),
							 'totalc'	=> number_format($grp_ctotal,2),
							 'bal'		=> $balance
							];
			}
			
			$datareport[] = ['blank' => '',
							 'Total' => '',
							 'totald'	=> number_format($drtotal,2),
							 'totalc'	=> number_format($crtotal,2)
							];
							
		} else if(Input::get('search_type')=='closing_groupwise' || Input::get('search_type')=='closing_groupwise_bal') {
			
			$trns = $this->makeTreeAc($items);
			$obtrns = $this->makeTreeAc($trresult['ob']); 
			$results =  $this->makeSummaryAcWithOb($trns, $obtrns, 'cl');
			
			//$results =  $this->makeSummaryAc($this->makeTreeAc($items), 'cl');
			$balHd = (Input::get('search_type')=='closing_groupwise_bal')?'Balance':'';
			$datareport[] = ['Account Group/Head','','Debit','Credit',$balHd];
			
			$cr_total = $dr_total = $crtotal = $drtotal = 0;
			foreach($results as $result) {
				$datareport[] = ['acnt' => $result['group_name']];
				$grp_ctotal = $grp_dtotal = 0;
				foreach($result['accounts'] as $row) {
					if($row['type']=='Cr') {
						$amountc = $row['amount']; $amountd = ''; $crtotal += $amountc;
					} else {
						$amountd = $row['amount']; $amountc = ''; $drtotal += $amountd;
					}
					$datareport[] = ['blank' => '',
									 'master_name' => $row['master_name'],
									 'amountd'	=> ($amountd!='')?number_format($amountd,2):'',
									 'amountc'	=> ($amountc!='')?number_format($amountc,2):''
									];
									
					$cr_total += $amountc;
					$dr_total += $amountd;
					$grp_ctotal += $amountc;
					$grp_dtotal += $amountd;
				}
				
				if(Input::get('search_type')=='closing_groupwise_bal') {
					$balance = $grp_dtotal - $grp_ctotal;
					$balance = ($balance > 0)?number_format($balance,2):'('.number_format(($balance*-1),2).')';
				} else 
					$balance = '';
				
				$datareport[] = ['blank' => '',
								 'Total' => '',
								 'totald'	=> number_format($grp_dtotal,2),
								 'totalc'	=> number_format($grp_ctotal,2),
								 'bal'		=> $balance
								];
			}
			
			$datareport[] = ['blank' => '',
							 'Total' => '',
							 'totald'	=> number_format($drtotal,2),
							 'totalc'	=> number_format($crtotal,2)
							];
			
		} else if(Input::get('search_type')=='opening_summary') {
			
			$results = $this->makeSummary($this->makeTree($items),'op');
			//echo '<pre>';print_r($results);exit;
			$datareport[] = ['',$voucher_head];
			$datareport[] = ['',''];
			
			$datareport[] = ['Account Group','Debit','Credit'];
			$crtotal = $drtotal = 0;
				
			foreach($results as $row) {
				$datareport[] = ['group' => $row['name'],$row['dr_amount'],$row['cr_amount']];
				$crtotal += $row['cr_amount'];
				$drtotal += $row['dr_amount'];
			}
			
			$datareport[] = ['blank' => 'Total:'.$currency,
							 'totald'	=> number_format($drtotal,2),
							 'totalc'	=> number_format($crtotal,2)
							];
			
		} else if(Input::get('search_type')=='closing_summary') {
			
			$trns = $this->makeTreeAc($items);
			$obtrns = $this->makeTreeAc($trresult['ob']); 
			$results = $this->makeSummaryAc2($this->makeSummaryAcWithOb($trns, $obtrns, 'cl'));
			//echo '<pre>';print_r($results);exit;
			
			//$results = $this->makeSummaryAc2($this->makeSummaryAc($this->makeTreeAc($items),'cl'));
			
			//echo '<pre>';print_r($results);exit;
			$datareport[] = ['',$voucher_head];
			$datareport[] = ['',''];
			
			$datareport[] = ['Account Group','Debit','Credit'];
			$cr_total = $dr_total = $bl_total = $btotal = 0;
			/* foreach($results as $row) {
				$camt = ($row['cr_amount'] < 0)?$row['cr_amount']*-1:$row['cr_amount'];
				$cr_total += $camt;
				$dr_total += $row['dr_amount'];
				$damount = ($row['dr_amount']!=0)?number_format($row['dr_amount'],2):'';
				$camount = ($camt!=0)?number_format($camt,2):'';
				$datareport[] = ['group' => $row['group_name'],$damount,$camount];
			} */
			
			foreach($results as $transaction) {
				$camt = ($transaction['cr_amount'] < 0)?$transaction['cr_amount']*-1:$transaction['cr_amount'];
				$cr_total += $camt;
				$dr_total += $transaction['dr_amount'];
				$obamoundr = $obamouncr = '';
				if($transaction['obamount'] > 0)
					$obamoundr = ($transaction['obamount']!=0)?'OB. '.$transaction['obamount'].' Dr':'';
				else
					$obamouncr = ($transaction['obamount']!=0)?'OB. '.$transaction['obamount'].' Cr':'';
				
				$damount = ($transaction['dr_amount']!=0)?number_format($transaction['dr_amount'],2):'';
				$camount = ($camt!=0)?number_format($camt,2):'';
				
				$datareport[] = ['group' => $transaction['group_name'],$damount,$camount];
			}
			$datareport[] = ['blank' => 'Total',
							 'totald'	=> number_format($dr_total,2),
							 'totalc'	=> number_format($cr_total,2)
							];
			
		} else if(Input::get('search_type')=='taged_summary') {
			$results = $this->makeTree($items);
			
		
	           $datareport[] = ['','','Total',number_format($dr_total,2),number_format($cr_total,2),''];
		} else if(Input::get('search_type')=='group_taged') {
			$results = $this->makeTree($items);
			$cr_total = $dr_total = $bl_total = 0;
			foreach($results as $result) {
				
				$datareport[] = ['acnt' => $result[0]['group_name']];
			//$results = $items;
			$datareport[] = ['Account Name','Debit','Credit'];
			
			
			foreach($result as $row) {
			if($row['op_balance'] > 0) {
			$dr_amount = $row['op_balance'];
			$cr_amount = 0;
			} else {
			$cr_amount = $row['op_balance'];
			$dr_amount = 0;
			}
				
				$cr_amount = ($cr_amount < 0)?$cr_amount*-1:$cr_amount;
				$cr_total += $cr_amount;
			    $dr_total += $dr_amount;
				
				$damount = ($dr_amount!=0)?number_format($dr_amount,2):'';
				$camount = ($cr_amount!=0)?number_format($cr_amount,2):'';
				
				$datareport[] = ['name' => $row['master_name'],
								 'totald'	=> $damount,
								 'totalc'	=> $camount
								];
			}
			
			$datareport[] = ['blank' => 'Total',
							 'totald'	=> number_format($dr_total,2),
							 'totalc'	=> number_format($cr_total,2)
							];
			}					
		
		
		} else if(Input::get('search_type')=='new_format') {
			//....
			$trns = $this->makeTreeAc($items);
			$obtrns = (Input::get('exclude')==1)?[]:$this->makeTreeAc($trresult['ob']); 
			$alltrns = $this->makeTreeAc($trresult['all']);  //echo '<pre>';print_r($obtrns);exit;	
			if(sizeof($alltrns) > 0)
			    $results =  $this->makeSummaryAcWithOb2($alltrns, $trns, $obtrns, 'cl');
			else if(sizeof($trns) > 0)
			    $results =  $this->makeSummaryAcWithOb3($trns, $obtrns, 'cl');
			else
				$results =  $this->makeSummaryAcWithOb4($obtrns, 'cl');
			//...
			
			
			//	echo '<pre>';print_r($results);exit;
			//$results =  $this->makeTreeAc($items);

			$datareport[] = ['','',$voucher_head];
			$datareport[] = ['',''];
			
			$datareport[] = ['Account Group/Head','OB. Dr.','OB. Cr.','Debit','Credit','Balance'];
			//$cr_total = $dr_total = $bl_total = 0; 
			$gnd_dtotal_ob = $gnd_ctotal_ob = $cr_total = $dr_total = $gnd_balance = 0;
			foreach($results as $transaction) {
				$datareport[] = ['hd' => $transaction['group_name'],'bnk'=>'','spc'=>''];
				//$grp_ctotal = $grp_dtotal = 0;
				$grp_dtotal_ob = $grp_ctotal_ob = $grp_ctotal = $grp_dtotal = $grp_balance = 0;
				foreach($transaction['accounts'] as $row) {

					if($row['type']=='Dr') { 
						$dr_amount = $row['amount'];
						$cr_amount = 0;
					} else {
						$cr_amount = $row['amount'];
						$dr_amount = 0;
					}
					
					$cr_total += $cr_amount;
					$dr_total += $dr_amount;
					$grp_ctotal += $cr_amount;
					$grp_dtotal += $dr_amount; $clbalance = '';
					
					$damount = ($dr_amount!=0)?number_format($dr_amount,2):'';
					$camount = ($cr_amount!=0)?number_format($cr_amount,2):'';
					
					$obamountdr = $obamountcr = '';
					if($row['type_ob']=='Dr') { 
						$obamountdr = number_format($row['obamount'],2);
						$obamountcr = '';
						$grp_dtotal_ob += $row['obamount'];
					} else {
						$obamountcr = number_format($row['obamount'],2);
						$obamountdr = '';
						$grp_ctotal_ob += $row['obamount'];
					}
					if($row['balance'] > 0)
						$clbalance = number_format($row['balance'],2);
					else
						$clbalance = '('.number_format($row['balance']*-1,2).')';
					
					$grp_balance += $row['balance'];

					$datareport[] = ['name' => $row['master_name'],
									 'obdr' => $obamountdr,
									 'obcr' => $obamountcr,
									 'dramt' => $damount,
									 'cramt' => $camount,
									 'bal' => $clbalance];
					
				}
				
				$gbalance = ($grp_balance > 0)?number_format($grp_balance,2):'('.number_format($grp_balance*-1,2).')';
				
				$datareport[] = ['total' => 'Total',
								 'gdrtotl' => number_format($grp_dtotal_ob,2), 
								 'gcrtot' => number_format($grp_ctotal_ob,2), 
								 'gdr' => number_format($grp_dtotal,2),
								 'gcr' => number_format($grp_ctotal,2), 
								 'gbal' => $gbalance];
				
				$datareport[] = ['',''];
				$gnd_dtotal_ob += $grp_dtotal_ob;
				$gnd_ctotal_ob += $grp_ctotal_ob;
				$gnd_balance += $grp_balance;
			}
			$gdbalance = ($gnd_balance > 0)?number_format($gnd_balance,2):'('.number_format($gnd_balance*-1,2).')';
						$datareport[] = ['gnd' => 'Grand Total',
										 'totobdr' => number_format($gnd_dtotal_ob,2),
										 'totobcr' => number_format($gnd_ctotal_ob,2),
										 'drtt' => number_format($dr_total,2),
										 'crtt' => number_format($cr_total,2),
										 'gbal' => $gdbalance];
		}
		
		$titles = ['main_head' => 'Trial Balance','subhead' => $voucher_head ];
		
		//echo $voucher_head.'<pre>';print_r($datareport);exit;
		Excel::create($voucher_head.' on '.date('d-m-Y'), function($excel) use ($datareport,$voucher_head) {

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
    
    protected function makeSummaryAcWithOb2($alltrns, $results, $obresults, $type=null)
	{
		$arrSummarry = array();$acar=[]; $tknarr = [];
		foreach($alltrns as $k => $allresult)
		{
			$arraccount = array(); 
			foreach($allresult as $key => $rows) {
				$dramount = $cramount = $amount = $obdramount = $obcramount = $obamount = $tamt = $balamt = 0; $trtypeob = '';
				$group_name = $rows[0]['group_name'];
				
				foreach($rows as $row) {
					$trtype = $row['type'];
					$account_name = $row['master_name'];

				} 

				//GETTING OPENING BALANCE....
				if(isset($obresults[$k][$key])) {
				    $acar[] = $key;
					foreach($obresults[$k][$key] as $obrow) {
						
						if($obrow['transaction_type']=='Dr') {
							if($type=='cl') {
								$cl_balance = $obrow['cl_balance'];
								$obdramount += $cl_balance;
							} else {
								$op_balance = $obrow['op_balance']; 
								$obdramount += $op_balance; 
							}
						} else {
							if($type=='cl') {
								$cl_balance = ($obrow['cl_balance'] < 0)?(-1*$obrow['cl_balance']):$obrow['cl_balance']; 
								$obcramount += $cl_balance;
							} else {
								$op_balance = $obrow['op_balance'];
								$obcramount += $op_balance; 
							}
						}
					}
					$obamount = $obdramount - $obcramount;
					$trtypeob = ($obamount < 0)?'Cr':'Dr';
					$obamount = ($obamount < 0)?(-1*$obamount):$obamount;
				}
				
				if(isset($results[$k][$key])) {
				    //$acar=[] = $key;
					foreach($results[$k][$key] as $crow) {
						if($crow['transaction_type']=='Dr') {
        					if($type=='cl') {
        						$cl_balance = $crow['cl_balance']; //($row['cl_balance'] < 0)?(-1*$row['cl_balance']):$row['cl_balance'];
        						$dramount += $cl_balance;
        					} else {
        						$op_balance = $crow['op_balance']; //($row['op_balance'] < 0)?(-1*$row['op_balance']):$row['op_balance'];
        						$dramount += $op_balance; 
        					}
        				} else {
        					if($type=='cl') {
        						$cl_balance = ($crow['cl_balance'] < 0)?(-1*$crow['cl_balance']):$crow['cl_balance']; //$row['cl_balance']; AUG 15 chnged
        						$cramount += $cl_balance;
        					} else {
        						$op_balance = $crow['op_balance']; //($row['op_balance'] < 0)?(-1*$row['op_balance']):$row['op_balance'];
        						$cramount += $op_balance; 
        					}
        				} //$amount += $row['cl_balance'];
					}
					$amount = bcsub($dramount, $cramount, 2); //(int)($dramount - $cramount);
    				$trtype = ($amount < 0)?'Cr':'Dr';
    				$amount = ($amount < 0)?(-1*$amount):$amount;
				} else {
				    
				}
				$balamt = $obamount+$amount;
				if($amount > 0)
					$arraccount[] = ['master_name' => $account_name, 'amount' => $amount, 'type' => $trtype,'obamount' => $obamount,'type_ob' => $trtypeob, 'balance' => $balamt];
			}
			
			$arrSummarry[] = ['group_name' => $group_name, 'accounts' => $arraccount];
		}
		//echo '<pre>';print_r($acar);exit;
		return $arrSummarry;
	}
	
	protected function makeSummaryAcWithOb2Bkp($alltrns, $results, $obresults, $type=null)
	{
		$arrSummarry = array();$acar=[];
		foreach($alltrns as $k => $allresult)
		{
			$arraccount = array(); 
			foreach($allresult as $key => $rows) {
				$dramount = $cramount = $amount = $obdramount = $obcramount = $obamount = $tamt = $balamt = 0; $trtypeob = '';
				$group_name = $rows[0]['group_name'];
				
				foreach($rows as $row) {
					$trtype = $row['type'];
					$account_name = $row['master_name'];

				} 

				//GETTING OPENING BALANCE....
				if(isset($obresults[$k][$key])) {
				    $acar[] = $key;
					foreach($obresults[$k][$key] as $obrow) {
						if($obrow['transaction_type']=='Dr') {
							if($type=='cl') {
								$cl_balance = $obrow['cl_balance'];
								$obdramount += $cl_balance;
							} else {
								$op_balance = $obrow['op_balance']; 
								$obdramount += $op_balance; 
							}
						} else {
							if($type=='cl') {
								$cl_balance = ($obrow['cl_balance'] < 0)?(-1*$obrow['cl_balance']):$obrow['cl_balance']; 
								$obcramount += $cl_balance;
							} else {
								$op_balance = $obrow['op_balance'];
								$obcramount += $op_balance; 
							}
						}
					}
					$obamount = $obdramount - $obcramount;
					$trtypeob = ($obamount < 0)?'Cr':'Dr';
					$obamount = ($obamount < 0)?(-1*$obamount):$obamount;
				}
				
				if(isset($results[$k][$key])) {
				    //$acar=[] = $key;
					foreach($results[$k][$key] as $crow) {
						if($crow['transaction_type']=='Dr') {
        					if($type=='cl') {
        						$cl_balance = $crow['cl_balance']; //($row['cl_balance'] < 0)?(-1*$row['cl_balance']):$row['cl_balance'];
        						$dramount += $cl_balance;
        					} else {
        						$op_balance = $crow['op_balance']; //($row['op_balance'] < 0)?(-1*$row['op_balance']):$row['op_balance'];
        						$dramount += $op_balance; 
        					}
        				} else {
        					if($type=='cl') {
        						$cl_balance = ($crow['cl_balance'] < 0)?(-1*$crow['cl_balance']):$crow['cl_balance']; //$row['cl_balance']; AUG 15 chnged
        						$cramount += $cl_balance;
        					} else {
        						$op_balance = $crow['op_balance']; //($row['op_balance'] < 0)?(-1*$row['op_balance']):$row['op_balance'];
        						$cramount += $op_balance; 
        					}
        				} //$amount += $row['cl_balance'];
					}
					$amount = bcsub($dramount, $cramount, 2); //(int)($dramount - $cramount);
    				$trtype = ($amount < 0)?'Cr':'Dr';
    				$amount = ($amount < 0)?(-1*$amount):$amount;
				} else {
				    
				}
				$balamt = $obamount+$amount;
				if($amount > 0)
					$arraccount[] = ['master_name' => $account_name, 'amount' => $amount, 'type' => $trtype,'obamount' => $obamount,'type_ob' => $trtypeob, 'balance' => $balamt];
			}
			
			$arrSummarry[] = ['group_name' => $group_name, 'accounts' => $arraccount];
		}
		//echo '<pre>';print_r($acar);exit;
		return $arrSummarry;
	}
	
	
	protected function makeSummaryAcWithOb3($results, $obresults, $type=null)
	{	//echo '<pre>';print_r($results);exit;
		$arrSummarry1 = array(); $tknarr = []; $grparrtkn = [];
		foreach($results as $k => $result)
		{
			$arraccount = array(); 
			foreach($result as $key => $rows) {
				$dramount = $cramount = $amount = $obdramount = $obcramount = $obamount = $balance = 0; $trtypeob = '';
				$group_name = $rows[0]['group_name'];
				
				foreach($rows as $row) {
					$trtype = $row['type'];
					$account_name = $row['master_name'];
					array_push($grparrtkn, $row['account_group_id']);
					
					if($row['transaction_type']=='Dr') {
						if($type=='cl') {
							$cl_balance = $row['cl_balance']; //($row['cl_balance'] < 0)?(-1*$row['cl_balance']):$row['cl_balance'];
							$dramount += $cl_balance;
						} else {
							$op_balance = $row['op_balance']; //($row['op_balance'] < 0)?(-1*$row['op_balance']):$row['op_balance'];
							$dramount += $op_balance; 
						}
					} else {
						if($type=='cl') {
							$cl_balance = ($row['cl_balance'] < 0)?(-1*$row['cl_balance']):$row['cl_balance']; //$row['cl_balance']; AUG 15 chnged
							$cramount += $cl_balance;
						} else {
							$op_balance = $row['op_balance']; //($row['op_balance'] < 0)?(-1*$row['op_balance']):$row['op_balance'];
							$cramount += $op_balance; 
						}
					} //$amount += $row['cl_balance'];
				} 
				$amount = bcsub($dramount, $cramount, 2); //(int)($dramount - $cramount);
				$trtype = ($amount < 0)?'Cr':'Dr';
				$amount = ($amount < 0)?(-1*$amount):$amount;
				
				//GETTING OPENING BALANCE....
				if(isset($obresults[$k][$key])) {
					array_push($tknarr, $obresults[$k][$key][0]['id']);
					
					foreach($obresults[$k][$key] as $kj => $obrow) {
						
						if($obrow['transaction_type']=='Dr') {
							if($type=='cl') {
								$cl_balance = $obrow['cl_balance'];
								$obdramount += $cl_balance;
							} else {
								$op_balance = $obrow['op_balance']; 
								$obdramount += $op_balance; 
							}
						} else {
							if($type=='cl') {
								$cl_balance = ($obrow['cl_balance'] < 0)?(-1*$obrow['cl_balance']):$obrow['cl_balance']; 
								$obcramount += $cl_balance;
							} else {
								$op_balance = $obrow['op_balance'];
								$obcramount += $op_balance; 
							}
						}
					}
					$obamount = $obdramount - $obcramount;
					$trtypeob = ($obamount < 0)?'Cr':'Dr';
					$obamount = ($obamount < 0)?(-1*$obamount):$obamount;
				}

				if($trtype=='Dr' && $trtypeob=='Dr') {
					$balance = $amount + $obamount;
				} else if($trtype=='Cr' && $trtypeob=='Cr') {
					$balance = ($amount + $obamount)*-1;
				} else if($trtype=='Dr' && $trtypeob=='Cr') {
					$balance = ($amount > $obamount)?($amount - $obamount):($obamount - $amount)*-1;
				} else if($trtype=='Cr' && $trtypeob=='Dr') {
					$balance = ($amount > $obamount)?($amount - $obamount)*-1:($obamount - $amount);
				} else if($trtype=='Dr' && $trtypeob=='') {
					$balance = $amount;
				} else if($trtype=='Cr' && $trtypeob=='') {
					$balance = $amount*-1;
				} else if($trtype=='' && $trtypeob=='Dr') {
					$balance = $obamount;
				} else if($trtype=='' && $trtypeob=='Cr') {
					$balance = $obamount*-1;
				}
				
				//if($amount > 0)
					$arraccount[] = ['master_name' => $account_name, 'amount' => $amount, 'type' => $trtype,'obamount' => $obamount, 'type_ob' => $trtypeob, 'balance' => $balance];
			}
			
			if(isset($obresults[$k])) {
			foreach($obresults[$k] as $grky => $grprow) {
				if(!in_array($grky, $tknarr)) {

					$dramount = $cramount = $amount = $obdramount = $obcramount = $obamount = $balance = 0;
					$group_name = $grprow[0]['group_name'];
					foreach($grprow as $row) {
						$trtype = $row['type'];
						$account_name = $row['master_name'];
						
						if($row['transaction_type']=='Dr') {
							if($type=='cl') {
								$cl_balance = $row['cl_balance'];
								$obdramount += $cl_balance;
							} else {
								$op_balance = $row['op_balance']; 
								$obdramount += $op_balance; 
							}
						} else {
							if($type=='cl') {
								$cl_balance = ($row['cl_balance'] < 0)?(-1*$row['cl_balance']):$row['cl_balance']; 
								$obcramount += $cl_balance;
							} else {
								$op_balance = $row['op_balance'];
								$obcramount += $op_balance; 
							}
						}
					} 
					
					$obamount = $obdramount - $obcramount;
					$trtypeob = ($obamount < 0)?'Cr':'Dr';
					$obamount = ($obamount < 0)?(-1*$obamount):$obamount;
					
					if($trtype=='Dr' && $trtypeob=='Dr') {
						$balance = $amount + $obamount;
					} else if($trtype=='Cr' && $trtypeob=='Cr') {
						$balance = ($amount + $obamount)*-1;
					} else if($trtype=='Dr' && $trtypeob=='Cr') {
						$balance = ($amount > $obamount)?($amount - $obamount):($obamount - $amount)*-1;
					} else if($trtype=='Cr' && $trtypeob=='Dr') {
						$balance = ($amount > $obamount)?($amount - $obamount)*-1:($obamount - $amount);
					} else if($trtype=='Dr' && $trtypeob=='') {
						$balance = $amount;
					} else if($trtype=='Cr' && $trtypeob=='') {
						$balance = $amount*-1;
					} else if($trtype=='' && $trtypeob=='Dr') {
						$balance = $obamount;
					} else if($trtype=='' && $trtypeob=='Cr') {
						$balance = $obamount*-1;
					}	

					//if($obamount > 0)
					$arraccount[] = ['master_name' => $account_name, 'amount' => $amount, 'type' => $trtype,'obamount' => $obamount, 'type_ob' => $trtypeob, 'balance' => $balance];
				}
			}
			}
			
			
			$arrSummarry1[] = ['group_name' => $group_name, 'accounts' => $arraccount];
			
		
			//new....
			$arg = []; $arrSummarry2 = array();
			foreach($obresults as $k => $result) {
				if(!in_array($k, $grparrtkn)) {
					array_push($arg, $k);
					$arraccount = array();
					foreach($result as $key => $rows) {
						$dramount = $cramount = $amount = $obdramount = $obcramount = $obamount = $balance = 0; $trtypeob = '';
						$group_name = $rows[0]['group_name'];
						
							foreach($rows as $kj => $obrow) {
								$account_name = $obrow['master_name'];
								if($obrow['transaction_type']=='Dr') {
									if($type=='cl') {
										$cl_balance = $obrow['cl_balance'];
										$obdramount += $cl_balance;
									} else {
										$op_balance = $obrow['op_balance']; 
										$obdramount += $op_balance; 
									}
								} else {
									if($type=='cl') {
										$cl_balance = ($obrow['cl_balance'] < 0)?(-1*$obrow['cl_balance']):$obrow['cl_balance']; 
										$obcramount += $cl_balance;
									} else {
										$op_balance = $obrow['op_balance'];
										$obcramount += $op_balance; 
									}
								}
							}
						
							$obamount = $obdramount - $obcramount;
							$trtypeob = ($obamount < 0)?'Cr':'Dr';
							$obamount = ($obamount < 0)?(-1*$obamount):$obamount;

							if($trtype=='Dr' && $trtypeob=='Dr') {
								$balance = $amount + $obamount;
							} else if($trtype=='Cr' && $trtypeob=='Cr') {
								$balance = ($amount + $obamount)*-1;
							} else if($trtype=='Dr' && $trtypeob=='Cr') {
								$balance = ($amount > $obamount)?($amount - $obamount):($obamount - $amount)*-1;
							} else if($trtype=='Cr' && $trtypeob=='Dr') {
								$balance = ($amount > $obamount)?($amount - $obamount)*-1:($obamount - $amount);
							} else if($trtype=='Dr' && $trtypeob=='') {
								$balance = $amount;
							} else if($trtype=='Cr' && $trtypeob=='') {
								$balance = $amount*-1;
							} else if($trtype=='' && $trtypeob=='Dr') {
								$balance = $obamount;
							} else if($trtype=='' && $trtypeob=='Cr') {
								$balance = $obamount*-1;
							}
						
							//if($obamount > 0)
								$arraccount[] = ['master_name' => $account_name, 'amount' => $amount, 'type' => $trtype,'obamount' => $obamount, 'type_ob' => $trtypeob, 'balance' => $balance];
					}
					
					$arrSummarry2[] = ['group_name' => $group_name, 'accounts' => $arraccount];
					
				}
			}
			//new.... */
			$arrSummarry = array_merge($arrSummarry1, $arrSummarry2);
			
		} //echo '<pre>';print_r($grparrtkn);exit;
		return $arrSummarry;
	}
	
	
	protected function makeSummaryAcWithOb4($results, $type=null)
	{
		$arrSummarry = array(); $tknarr = [];
		foreach($results as $k => $result)
		{
			$arraccount = array(); 
			foreach($result as $key => $rows) {
				$dramount = $cramount = $amount = $obdramount = $obcramount = $obamount = $balance = 0; $trtypeob = '';
				$group_name = $rows[0]['group_name'];
				foreach($rows as $row) {
					$trtype = $row['type'];
					$account_name = $row['master_name'];

					if($row['transaction_type']=='Dr') {
						if($type=='cl') {
							$cl_balance = $row['cl_balance'];
							$obdramount += $cl_balance;
						} else {
							$op_balance = $row['op_balance']; 
							$obdramount += $op_balance; 
						}
					} else {
						if($type=='cl') {
							$cl_balance = ($row['cl_balance'] < 0)?(-1*$row['cl_balance']):$row['cl_balance']; 
							$obcramount += $cl_balance;
						} else {
							$op_balance = $row['op_balance'];
							$obcramount += $op_balance; 
						}
					}
				} 
				
				$obamount = $obdramount - $obcramount;
				$trtypeob = ($obamount < 0)?'Cr':'Dr';
				$obamount = ($obamount < 0)?(-1*$obamount):$obamount;

				if($trtype=='Dr' && $trtypeob=='Dr') {
					$balance = $amount + $obamount;
				} else if($trtype=='Cr' && $trtypeob=='Cr') {
					$balance = ($amount + $obamount)*-1;
				} else if($trtype=='Dr' && $trtypeob=='Cr') {
					$balance = ($amount > $obamount)?($amount - $obamount):($obamount - $amount)*-1;
				} else if($trtype=='Cr' && $trtypeob=='Dr') {
					$balance = ($amount > $obamount)?($amount - $obamount)*-1:($obamount - $amount);
				} else if($trtype=='Dr' && $trtypeob=='') {
					$balance = $amount;
				} else if($trtype=='Cr' && $trtypeob=='') {
					$balance = $amount*-1;
				} else if($trtype=='' && $trtypeob=='Dr') {
					$balance = $obamount;
				} else if($trtype=='' && $trtypeob=='Cr') {
					$balance = $obamount*-1;
				}	

				//if($amount > 0)
				$arraccount[] = ['master_name' => $account_name, 'amount' => $amount, 'type' => $trtype,'obamount' => $obamount, 'type_ob' => $trtypeob, 'balance' => $balance];
				
			}
			
			$arrSummarry[] = ['group_name' => $group_name, 'accounts' => $arraccount];
		}
		return $arrSummarry;
	}
			
}