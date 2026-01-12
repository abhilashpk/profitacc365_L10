<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\AccountMaster\AccountMasterInterface;

use App\Http\Requests;
use Notification;
use Input;
use Session;
use App;
use DB;
use Excel;
use Auth;

class BalanceSheetController extends Controller
{
   
	protected $accountmaster;
	protected $option;

	public function __construct(AccountMasterInterface $accountmaster) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->accountmaster = $accountmaster;
		$this->middleware('auth');
		$this->option = DB::table('parameter2')->where('keyname', 'mod_opcl')->where('status',1)->select('is_active')->first();
	}
	
	public function index() {
		
		$data = array();
		$acmasters = [];
		
		DB::statement('DELETE t1 FROM account_transaction t1, account_transaction t2 WHERE  t1.id > t2.id AND (t1.voucher_type = t2.voucher_type AND t1.voucher_type_id = t2.voucher_type_id AND t1.account_master_id = t2.account_master_id AND t1.transaction_type = t2.transaction_type AND t1.amount = t2.amount AND t1.amount = t2.amount AND t1.reference = t2.reference AND t1.reference_from = t2.reference_from AND t1.reference_from = t2.reference_from AND t1.other_info = t2.other_info AND t1.status = t2.status AND t1.deleted_at = t2.deleted_at)');
			
		$this->makeSummaryAc($this->makeTree( $this->accountmaster->updateUtility('CB')) );
		
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
		
		return view('body.balancesheet.index')
					->withAcmasters($acmasters)
					->withDepartments($departments)
					->withIsdept($is_dept)
					->withSettings($this->acsettings)
					->withData($data);
	}


	protected function makeSummaryAc($results)
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


	protected function makeTree($result)
	{
		$childs = array();
		foreach($result as $item)
			$childs[$item->id][] = $item;

		return $childs;
	}
	
	public function getSearch()
	{	//echo '<pre>';print_r(Input::all());exit;
		$data = array();
				
		if(Input::get('search_type')=='summary')
			$voucher_head = 'Balance Sheet - Summary';
		else if(Input::get('search_type')=='detail')
			$voucher_head = 'Balance Sheet - Detail';
		else if(Input::get('search_type')=='ason_date')
			$voucher_head = 'Balance Sheet - Summary as on Date';
		
		Input::merge(['opclbal_option' => ($this->option->is_active==1)?true:false]);
		$result = $this->accountmaster->getBalanceSheet(Input::all());  //echo '<pre>';print_r($result);exit;
		if($result) {
			
			$total_lib = $total_ast = 0;
			foreach($result['liability'] as $lib) {
				//$total_lib += $lib['total'];
				$total_lib += ($lib['total'] > 0)?(-1*$lib['total']):$lib['total']; //MAR25
			}
			
			foreach($result['asset'] as $ast) {
				//$total_ast += $ast['total'];
				$total_ast += ($ast['total'] > 0)?$ast['total']:(-1*$ast['total']); //MAR25
			}
			
			//CALCULATE NET PROFIT..... from P&L A/c.....
			$attributes['date_from'] = (Input::get('date_from')!='')?date('Y-m-d', strtotime(Input::get('date_from'))):'';
			$attributes['date_to'] = (Input::get('date_to')!='')?date('Y-m-d', strtotime(Input::get('date_to'))):''; 
			$attributes['search_type']='summary';
			$attributes['curr_from_date'] = $this->acsettings->from_date;
			$attributes['cl_stock'] = (Input::get('cl_stock',false))?1:null; //1
			/* comented on 2021 FEB 23 */ //$attributes['op_stock'] = 1;//(Input::get('op_stock',false))?1:null;
			/* comented on 2021 FEB 23 */  //$attributes['department_id'] = (Input::get('department_id',false))?Input::get('department_id'):null;
			$result_pl = $this->accountmaster->getProfitLoss($attributes);  //echo '<pre>';print_r($result_pl);exit;
			
			if(count($result_pl['income'][0]) > 0 && count($result_pl['expense'][0]) > 0) {
				$grossprofit = $result_pl['income'][0]['total'] - $result_pl['expense'][0]['total'];
			} else if(count($result_pl['income'][0]) > 0 && count($result_pl['expense'][0]) == 0) {
				$grossprofit = $result_pl['income'][0]['total'];
			} else if(count($result_pl['income'][0]) == 0 && count($result_pl['expense'][0]) > 0) {
				$grossprofit = 0 - $result_pl['expense'][0]['total'];
			} else {
				$grossprofit = null;
			}
			
			
		
			if(sizeof($result_pl['expense'][1]) > 0) {
				if($grossprofit > 0) {
					$netprofit = $grossprofit - $result_pl['expense'][1]['total'];
				} else {
					$netprofit = $grossprofit - $result_pl['expense'][1]['total'];
				}
			} else {
				$netprofit = $grossprofit;
			}
			
			//if indirect income is there...
			if(count($result_pl['income'][1]) > 0) {  //sizeof
				$netprofit = $netprofit + $result_pl['income'][1]['total'];
			} 
			
			//echo $netprofit.'  '.$total_ast;exit;
			//......................
			//echo $total_lib;
			if($netprofit > 0) {
				$total_lib -= $netprofit;
				
			} else {
				
				/* if($total_ast > 0)
					$total_ast = $total_ast + $netprofit; 
				else {  */ //###COMMENTED ON MAR 13 20
				
					//edited on FEB 25 2020
					if( ($netprofit < 0) && ($total_ast < 0) ) {
						$total_ast = ($netprofit*-1) - $total_ast;
					} else if( ($netprofit < 0) && ($total_ast > 0) ) {
						$total_ast = ($netprofit*-1) + $total_ast;
					} else 
						$total_ast = $netprofit + $total_ast; //pblm....
					//-------
				//}
				
			}
			
			$difference_r = $difference_l = '';
			$total_l = ($total_lib < 0)?$total_lib*-1:$total_lib;
			$total_a= ($total_ast < 0)?$total_ast*-1:$total_ast;
			//echo $total_l.'  '.$total_a;exit;
			
			if($total_l > $total_a) {
				$difference_r = (int)($total_l - $total_a);
				$total = $total_lib;
			} else if($total_l < $total_a) { 
				$difference_l = (int)($total_a - $total_l);
				$total = $total_ast; 
			} else
				$total = $total_ast;
			
			$report = true;
			
		} else {
			$report = false;
			$difference_r = $difference_l = $total = $netprofit = '';
		}
		
		//echo '<pre>';print_r($result);exit;
		//$crow = DB::table('currency')->where('is_default',1)->select('code')->first();
		$crow = DB::table('parameter1')
		        ->join('currency','currency.id','=','parameter1.bcurrency_id')->select('currency.code')->first();
		return view('body.balancesheet.print')
					->withResult($result)
					->withVoucherhead($voucher_head)
					->withLiability($result['liability'])
					->withAssets($result['asset'])
					->withDifferencel($difference_l)
					->withNetprofit($netprofit)
					->withTotal($total)
					->withReport($report)
					->withDifferencer($difference_r)
					->withType(Input::get('search_type'))
					->withchkob(Input::get('chkob'))
					->withSettings($this->acsettings)
					->withFromdate(Input::get('date_from'))
					->withTodate(Input::get('date_to'))
					->withCurrency($crow->code)
					->withData($data);
	}
	
	public function dataExport()
	{
	    //echo '<pre>';print_r(Input::all());exit;
		$data = array();
		$crow = DB::table('currency')->where('is_default',1)->select('code')->first();
		$currency=$crow->code;
		$datareport[] = [strtoupper(Session::get('company')),'','',''];
		$datareport[] = ['','','','','','',''];
		
		if(Input::get('search_type')=='summary')
			$voucher_head = 'Balance Sheet - Summary';
		else if(Input::get('search_type')=='detail')
			$voucher_head = 'Balance Sheet - Detail';
		else if(Input::get('search_type')=='ason_date')
			$voucher_head = 'Balance Sheet - Summary as on Date';
		
		$datareport[] = ['','','',strtoupper($voucher_head), '','',''];
		$datareport[] = ['','','','','','',''];
		
		$datareport[] = ['LIABILITIES','','','','ASSETS','',''];
		$datareport[] = ['','','','','','',''];
		$datareport[] = ['Description','','Amount','','Description','','Amount'];
		
		$result = $this->accountmaster->getBalanceSheet(Input::all());  //echo '<pre>';print_r($result);exit;
		if($result) {
			
			$total_lib = $total_ast = 0;
			foreach($result['liability'] as $lib) {
				$total_lib += $lib['total'];
			}
			
			foreach($result['asset'] as $ast) {
				$total_ast += $ast['total'];
			}
			
			//CALCULATE NET PROFIT..... from P&L A/c.....
			$attributes['date_from'] = (Input::get('date_from')!='')?date('Y-m-d', strtotime(Input::get('date_from'))):'';
			$attributes['date_to'] = (Input::get('date_to')!='')?date('Y-m-d', strtotime(Input::get('date_to'))):''; 
			$attributes['search_type']='summary';
			$attributes['curr_from_date'] = $this->acsettings->from_date;
			$attributes['cl_stock'] = null;//(Input::get('cl_stock',false))?1:null;
			//$attributes['op_stock'] = 1;//(Input::get('op_stock',false))?1:null;
			$result_pl = $this->accountmaster->getProfitLoss($attributes);  //echo '<pre>';print_r($result_pl);exit;
			
			if(count($result_pl['income'][0]) > 0 && count($result_pl['expense'][0]) > 0) {
				$grossprofit = $result_pl['income'][0]['total'] - $result_pl['expense'][0]['total'];
			} else if(count($result_pl['income'][0]) > 0 && count($result_pl['expense'][0]) == 0) {
				$grossprofit = $result_pl['income'][0]['total'];
			} else if(count($result_pl['income'][0]) == 0 && count($result_pl['expense'][0]) > 0) {
				$grossprofit = 0 - $result_pl['expense'][0]['total'];
			} else {
				$grossprofit = null;
			}
			
			/* if(count($result_pl['expense'][0]) > 0) 
				$grossprofit = $result_pl['income'][0]['total'] - $result_pl['expense'][0]['total'];
			else
				$grossprofit = $result_pl['income'][0]['total']; */
			
			if(sizeof($result_pl['expense'][1]) > 0) {
				if($grossprofit > 0) {
					$netprofit = $grossprofit - $result_pl['expense'][1]['total'];
				} else {
					$netprofit = $grossprofit - $result_pl['expense'][1]['total'];
				}
			} else {
				$netprofit = $grossprofit;
			}
			
			//echo $netprofit.'  '.$total_ast;exit;
			//......................
			//echo $total_lib;
			if($netprofit > 0) {
				$total_lib -= $netprofit;
				
			} else {
				
				/* if($total_ast > 0)
					$total_ast = $total_ast + $netprofit; 
				else {  */ //###COMMENTED ON MAR 13 20
				
					//edited on FEB 25 2020
					if( ($netprofit < 0) && ($total_ast < 0) ) {
						$total_ast = ($netprofit*-1) - $total_ast;
					} else if( ($netprofit < 0) && ($total_ast > 0) ) {
						$total_ast = ($netprofit*-1) + $total_ast;
					} else 
						$total_ast = $netprofit + $total_ast; //pblm....
					//-------
				//}
				
			}
			
			$difference_r = $difference_l = '';
			$total_l = ($total_lib < 0)?$total_lib*-1:$total_lib;
			$total_a= ($total_ast < 0)?$total_ast*-1:$total_ast;
			//echo $total_l.'  '.$total_a;exit;
			
			if($total_l > $total_a) {
				$difference_r = $total_l - $total_a;
				$total = $total_lib;
			} else if($total_l < $total_a) { 
				$difference_l = $total_a - $total_l;
				$total = $total_ast; 
			} else
				$total = $total_ast;
			
			$report = true;
			
		} else {
			$report = false;
			$difference_r = $difference_l = $total = $netprofit = '';
		}
		
	//	echo '<pre>';print_r($result);exit; 
		
		if(Input::get('search_type')=='summary') {
			
			foreach($result['liability'] as $key => $rows) {
				
				if($rows['total'] > 0) {
					$cl_amount = number_format($rows['total'],2);
				} else 
					$cl_amount = number_format(($rows['total']*-1),2);
				
				$astotal = '';
				if(isset($result['asset'][$key])) {
					if($result['asset'][$key]['total'] > 0) {
						$astotal = number_format($result['asset'][$key]['total'],2);
					} else 
						$astotal = '('.number_format( ($result['asset'][$key]['total']*-1),2).')';
				}
																				
				$datareport[] = [$rows['name'],'',$cl_amount,'',isset($result['asset'][$key])?$result['asset'][$key]['name']:'','',$astotal];
				
				foreach($rows['items'] as $ky => $row) {
				//echo '<pre>';print_r($rows['items']);exit;	
					if($row['amount'] > 0) {
						$amount = number_format($row['amount'],2);
					} else 
						$amount = number_format(($row['amount']*-1),2);
					$datareport[] = [$row['group_name'],'',$amount,'','','',''];
				}
					$asamount = '';
					//echo '<pre>';print_r($result['asset']);exit; 
				/*	if(isset($result['asset'][$key]['items'][$ky])) {
						if($result['asset'][$key]['items'][$ky]['amount'] > 0) {
							$asamount = number_format($result['asset'][$key]['items'][$ky]['amount'],2);
						} else 
							$asamount = '('.number_format( ($result['asset'][$key]['items'][$ky]['amount']*-1),2).')';
					}*/
					foreach($result['asset'][$key]['items'] as $rowas){
					    if($rowas['amount'] > 0) {
							$asamount = number_format($rowas['amount'],2);
						} else 
							$asamount = '('.number_format( ($rowas['amount']*-1),2).')';
				//	echo '<pre>';print_r($result['asset'][$key]['items']);exit; 																
					//$datareport[] = [$row['group_name'],'',$amount,'',isset($result['asset'][$key]['items'][$ky])?$result['asset'][$key]['items'][$ky]['group_name']:'','',$asamount];
					$datareport[] = ['','','','',$rowas['group_name'],'',$asamount];
				
				}
			}
			$datareport[] = ['','','','','','',''];
			$lsTitle = $pfTitle = $npAmt = $lsAmt = '';
			if($netprofit > 0) {
				$pfTitle = 'Net Profit';
				if($netprofit < 0) {
					$arr = explode('-', $netprofit);
					$npAmt = $netprofit;//number_format($arr[1],2);
				} else 
					$npAmt = number_format($netprofit,2);
			}
			
			if($netprofit < 0) {
				$lsTitle = 'Net Loss';
				if($netprofit < 0) {
					$arr = explode('-', $netprofit);
					$lsAmt = '('.number_format($arr[1],2).')';
				} else 
					$lsAmt = number_format($netprofit,2);
				
			}
			
			$datareport[] = [$pfTitle,'',$npAmt,'',$lsTitle,'',$lsAmt];
			
			$df1Title = $df2Title = $df1Amt = $df2Amt = '';
			if($difference_l) { 
				$df1Title = 'Difference';
				if($difference_l < 0) {
					$arr = explode('-', $difference_l);
					$df1Amt = number_format($arr[1],2);
				} else 
					$df1Amt = number_format($difference_l,2);
			}
			
			if($difference_r) {
				$df2Title = 'Difference';
				if($difference_r < 0) {
					$arr = explode('-', $difference_r);
					$df2Amt = '('.number_format($arr[1],2).')';
				} else 
					$df2Amt = number_format($difference_r,2);
			}
			$datareport[] = [$df1Title,'',$df1Amt,'',$df2Title,'',$df2Amt];
			
		} else {
			
			foreach($result['liability'] as $key => $rows) {
				
				if($rows['total'] > 0) {
					$cl_amount = number_format($rows['total'],2);
				} else 
					$cl_amount = number_format(($rows['total']*-1),2);
				
				if(isset($result['asset'][$key])) {
					if($result['asset'][$key]['total'] > 0) {
						$amount1 = number_format($result['asset'][$key]['total'],2);
					} else 
						$amount1 = '('.number_format( ($result['asset'][$key]['total']*-1),2).')';	
				}
				
				$datareport[] = [$rows['catname'],'',$cl_amount,'',isset($result['asset'][$key])?$result['asset'][$key]['catname']:'','',$amount1];
			//	echo '<pre>';print_r($datareport);exit;
				foreach($rows['items'] as $ky => $row) {
				//	echo '<pre>';print_r($rows['items']);exit;
					if($row['total'] > 0) {
						$gamount = number_format($row['total'],2);
					} else 
						$gamount = number_format(($row['total']*-1),2);
					
				/*	if(isset($result['asset'][$key]['items'][$ky])) {
						if($result['asset'][$key]['items'][$ky]['total'] > 0) {
							$amount2 = number_format($result['asset'][$key]['items'][$ky]['total'],2);
						} else 
							$amount2 = '('.number_format( ($result['asset'][$key]['items'][$ky]['total']*-1),2).')';
					}*/
																				
				//	$datareport[] = [$row['name'],'',$gamount,'',isset($result['asset'][$key]['items'][$ky])?$result['asset'][$key]['items'][$ky]['name']:'','',$amount2];
					//	echo '<pre>';print_r($result['asset'][$key]['items'][$key]['gitems']);exit;
					$datareport[] = [$row['name'],'',$gamount,'','','',''];
					foreach($row['gitems'] as $k => $item) {
						//echo '<pre>';print_r($row['gitems']);exit;
						if($item['amount'] > 0) {
							$amount = number_format($item['amount'],2);
						} else 
							$amount = number_format(($item['amount']*-1),2);
						
						if($amount!=0) {
							$group_name = $item['group_name'];
						} else {
							$group_name = $amount = '';
						}
						$datareport[] = [$group_name,'',$amount,'','','',''];
					}
				}
						$group_name_ast = $amount_ast = '';
						/*if(isset($result['asset'][$key]['items'][$ky]['gitems'][$k])) {
							if($result['asset'][$key]['items'][$ky]['gitems'][$k]['amount'] > 0) {
								$amount_ast = number_format($result['asset'][$key]['items'][$ky]['gitems'][$k]['amount'],2);
							} else 
								$amount_ast = '('.number_format( ($result['asset'][$key]['items'][$ky]['gitems'][$k]['amount']*-1),2).')';
							
							if($result['asset'][$key]['items'][$ky]['gitems'][$k]['amount']!=0) {
								$group_name_ast = $result['asset'][$key]['items'][$ky]['gitems'][$k]['group_name'];
							} else {
								$group_name_ast = $amount_ast = '';
							}
						}
						
						$datareport[] = [$group_name,'',$amount,'',isset($result['asset'][$key]['items'][$ky]['gitems'][$k])?$group_name_ast:'','',$amount_ast];*/
						foreach($result['asset'] as $keya => $rowasi){
						foreach($rowasi['items'] as $kyi => $rowas){
						 	//echo '<pre>';print_r($rowasi['items']);exit;   
					    if($rowas['total'] > 0) {
							$amount2 = number_format($rowas['total'],2);
						} else 
							$amount2 = '('.number_format( ($rowas['total']*-1),2).')';
				    
					$datareport[] = ['','','','',$rowas['name'],'',$amount2];
				//	echo '<pre>';print_r($datareport);exit; 
					foreach($rowas['gitems'] as $kyget=> $rowget){
					     if($rowget['amount'] > 0) {
							$amount_ast = number_format($rowget['amount'],2);
						} else 
							$amount_ast = '('.number_format( ($rowget['amount']*-1),2).')';
							
							if($rowget['amount'] != 0) {
								$group_name_ast = $rowget['group_name'];
						} else 
							$group_name_ast = $amount_ast = '';
					   //echo '<pre>';print_r($rowas['gitems']);exit; 
					   	
					   	$datareport[] = ['','','','','','',''];
					   $datareport[] = ['','','','',$group_name_ast,'',$amount_ast];
					}
						}
					
				}
			}
			
			
			$datareport[] = ['','','','','','',''];
			$lsTitle = $pfTitle = $npAmt = $lsAmt = '';

			if($netprofit > 0) {
				$pfTitle = 'Net Profit';
				if($netprofit < 0) {
					$arr = explode('-', $netprofit);
					$npAmt = number_format($arr[1],2);
				} else 
					$npAmt = number_format($netprofit,2);
			}
			
			if($netprofit < 0) {
				$lsTitle = 'Net Loss';
				if($netprofit < 0) {
					$arr = explode('-', $netprofit);
					$lsAmt = '('.number_format($arr[1],2).')';
				} else 
					$lsAmt = number_format($netprofit,2);
																			
			}
			
			$datareport[] = [$pfTitle,'',$npAmt,'',$lsTitle,'',$lsAmt];
			
			$df1Title = $df2Title = $df1Amt = $df2Amt = '';
			if($difference_l) { 
				$df1Title = 'Difference';
				if($difference_l < 0) {
					$arr = explode('-', $difference_l);
					$df1Amt = number_format($arr[1],2);
				} else 
					$df1Amt = number_format($difference_l,2);
			}
			
			if($difference_r) {
				$df2Title = 'Difference';
				if($difference_r < 0) {
					$arr = explode('-', $difference_r);
					$df2Amt = '('.number_format($arr[1],2).')';
				} else 
					$df2Amt = number_format($difference_r,2);
			}
			$datareport[] = [$df1Title,'',$df1Amt,'',$df2Title,'',$df2Amt];
			
		}
		
	
		if($total < 0) {
			$arr = explode('-', $total);
			$total = '('.number_format($arr[1],2).')';
		} else 
			$total = number_format($total,2);
		
		$datareport[] = ['','','','','','',''];
		$datareport[] = ['Total:'.$currency,'',$total,'','Total:'.$currency,'',$total];
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
	
			
}
