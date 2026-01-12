<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\AccountMaster\AccountMasterInterface;
use App\Repositories\Itemmaster\ItemmasterInterface;

use App\Http\Requests;
use Notification;
use Input;
use Session;
use App;
use DB;
use Excel;
use Auth;

class ProfitLossController extends Controller
{
   
	protected $accountmaster;
	protected $itemmaster;
	protected $option;

	public function __construct(AccountMasterInterface $accountmaster, ItemmasterInterface $itemmaster) {

		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->accountmaster = $accountmaster;
		$this->itemmaster = $itemmaster;
		$this->middleware('auth');
		$this->option = DB::table('parameter2')->where('keyname', 'mod_opcl')->where('status',1)->select('is_active')->first();

	}
	
	public function index() {
		$data = array();
		$acmasters = [];//$this->accountmaster->accountMasterList();
		
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
		
		return view('body.profitloss.index')
					->withAcmasters($acmasters)
					->withDepartments($departments)
					->withIsdept($is_dept)
					->withOptionsrch(($this->option->is_active==1)?true:false)
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
	{ 
		$data = array(); $stockvalue = 0;
		Input::merge(['curr_from_date' => $this->acsettings->from_date]);
		$result = $this->accountmaster->getProfitLoss(Input::all()); //
		//echo '<pre>';print_r($result);exit;
		
		if(Input::get('search_type')=='summary')
			$voucher_head = 'Profit & Loss - Summary';
		else if(Input::get('search_type')=='detail')
			$voucher_head = 'Profit & Loss - Detail';
		else {
			$voucher_head = 'Profit & Loss - Summary as on Date';
		}
		//echo count($result['income'][0]);//exit;
		//echo '<pre>';print_r($result);exit;
		$subtotal = 0;
		if(count($result['income'][0]) > 0 && count($result['expense'][0]) > 0) {
			$subtotal = ($result['income'][0]['total'] > $result['expense'][0]['total'])?$result['income'][0]['total']:$result['expense'][0]['total'];
			
			$grossprofit = $result['income'][0]['total'] - $result['expense'][0]['total'];
			$directexp = $result['expense'][0];
			$directinc = $result['income'][0];
			
		} else if(count($result['income'][0]) > 0 && count($result['expense'][0]) == 0) {
			
			$subtotal = $result['income'][0]['total'];
			
			$grossprofit = $result['income'][0]['total'];
			$directexp = $result['expense'][0];
			$directinc = $result['income'][0];
			
		} else if(count($result['income'][0]) == 0 && count($result['expense'][0]) > 0) {
			
			$subtotal = $result['expense'][0]['total'];
			
			$grossprofit = 0 - $result['expense'][0]['total'];
			$directexp = $result['expense'][0];
			$directinc = $result['income'][0];
			
		} else {
			$directexp = $directinc = $grossprofit = null;
		}	
		
		//if indirect expense is there...
		if(count($result['expense'][1]) > 0) {  //sizeof
			if($grossprofit > 0) {
				$netprofit = $grossprofit - $result['expense'][1]['total'];
				$total = $netprofit;
			} else {
				$netprofit = $grossprofit - $result['expense'][1]['total'];
				
				if($grossprofit < $result['expense'][1]) {
					$total = $netprofit;
				} else {
					$total = $grossprofit + $netprofit;
				}
			}
			
			$indirectexp = $result['expense'][1];
		} else {
			$netprofit = $grossprofit;
			$indirectexp = null;
			$total = $grossprofit;
		}
		
		//if indirect income is there...
		if(count($result['income'][1]) > 0) {  //sizeof
			$indirectinc = $result['income'][1];
			$subtotal = $subtotal + $result['income'][1]['total'];
			$netprofit = $total = $netprofit + $result['income'][1]['total'];//$netprofit + $subtotal;
		} else {
			$indirectinc = null;
		}
		
		//$total = $grossprofit;
		//$total = $netprofit;
		//echo $grossprofit.' '.$netprofit.'<pre>';print_r($result);exit;//echo $grossprofit;exit;
		
		$crow = DB::table('parameter1')
		        ->join('currency','currency.id','=','parameter1.bcurrency_id')->select('currency.code')->first();
		return view('body.profitloss.print')
					->withResult($result)
					->withVoucherhead($voucher_head)
					->withDirectexp($directexp)
					->withIndirectexp($indirectexp)
					->withDirectinc($directinc)
					->withIndirectinc($indirectinc)
					->withSubtotal($subtotal)
					->withGrossprofit($grossprofit)
					->withNetprofit($netprofit)
					->withTotal($total)
					->withStockvalue($stockvalue)
					->withSettings($this->acsettings)
					->withFromdate(Input::get('date_from'))
					->withTodate(Input::get('date_to'))
					->withClstock(Input::get('cl_stock'))
					->withOpstock(Input::get('op_stock'))
					->withType(Input::get('search_type'))
					->withCurrency($crow->code)
					->withData($data);

	}
	
	public function dataExport()
	{
		$data = array();
		$datareport[] = [strtoupper(Session::get('company')),'','',''];
		$datareport[] = ['','','','','','',''];
		
		Input::merge(['curr_from_date' => $this->acsettings->from_date]);
		$result = $this->accountmaster->getProfitLoss(Input::all()); //echo '<pre>';print_r(Input::all());exit;
		$crow = DB::table('currency')->where('is_default',1)->select('code')->first();
		$currency=$crow->code;
		if(Input::get('search_type')=='summary')
			$voucher_head = 'Profit & Loss - Summary';
		else if(Input::get('search_type')=='detail')
			$voucher_head = 'Profit & Loss - Detail';
		else {
			$voucher_head = 'Profit & Loss - Summary as on Date';
		}
		
		$datareport[] = ['','','',strtoupper($voucher_head), '','',''];
		$datareport[] = ['','','','','','',''];
		
		$datareport[] = ['EXPENSE','','','','INCOME','',''];
		$datareport[] = ['','','','','','',''];
		
		$datareport[] = ['Description','','Amount','','Description','','Amount'];
		
		$subtotal = 0;
		if(count($result['income'][0]) > 0 && count($result['expense'][0]) > 0) {
			$subtotal = ($result['income'][0]['total'] > $result['expense'][0]['total'])?$result['income'][0]['total']:$result['expense'][0]['total'];
			
			$grossprofit = $result['income'][0]['total'] - $result['expense'][0]['total'];
			$directexp = $result['expense'][0];
			$directinc = $result['income'][0];
			
		} else if(count($result['income'][0]) > 0 && count($result['expense'][0]) == 0) {
			
			$subtotal = $result['income'][0]['total'];
			
			$grossprofit = $result['income'][0]['total'];
			$directexp = $result['expense'][0];
			$directinc = $result['income'][0];
			
		} else if(count($result['income'][0]) == 0 && count($result['expense'][0]) > 0) {
			
			$subtotal = $result['expense'][0]['total'];
			
			$grossprofit = 0 - $result['expense'][0]['total'];
			$directexp = $result['expense'][0];
			$directinc = $result['income'][0];
			
		} else {
			$directexp = $directinc = $grossprofit = null;
		}	
		
		//if indirect expense is there...
		if(sizeof($result['expense'][1]) > 0) {
			if($grossprofit > 0) {
				$netprofit = $grossprofit - $result['expense'][1]['total'];
				$total = $netprofit;
			} else {
				$netprofit = $grossprofit - $result['expense'][1]['total'];
				
				if($grossprofit < $result['expense'][1]) {
					$total = $netprofit;
				} else {
					$total = $grossprofit + $netprofit;
				}
			}
			
			$indirectexp = $result['expense'][1];
		} else {
			$netprofit = $grossprofit;
			$indirectexp = null;
			$total = $grossprofit;
		}
		
		if(sizeof($result['income'][1]) > 0) {
			$indirectinc = $result['income'][1];
		} else {
			$indirectinc = null;
		}
		
		if($directexp['total'] < 0) {
			$arr = explode('-', $directexp['total']);
			$balance_exp = '('.number_format($arr[1],2).')';
		} else 
			$balance_exp = number_format($directexp['total'],2);
			$balance_inc='';
		if($directinc){
		if($directinc['total'] < 0) {
			$arr = explode('-', $directinc['total']);
			$balance_inc = '('.number_format($arr[1],2).')';
		} else 
			$balance_inc = number_format($directinc['total'],2);
		}
		
		//$datareport[] = ['Description','','Amount','','Description','','Amount'];
		$datareport[] = [$directexp['name'],'',$balance_exp,'',isset($directinc['name'])?$directinc['name']:'INCOME','',$balance_inc];
	//	echo '<pre>';print_r($directinc['items']);exit;
		foreach($directexp['items'] as $key => $row) {
			
			if($row->cl_balance < 0) {
				$arr = explode('-', $row->cl_balance);
				$clbalanceL1 = '('.number_format($arr[1],2).')';
			} else 
				$clbalanceL1 = number_format($row->cl_balance,2);
			
			$cl_balanceR1 = '';
			if(Input::get('search_type')=='summary')
				$datareport[] = [$row->group_name,'',$clbalanceL1,'','','',''];
			else {
				if($row->cl_balance!=0) {
					$datareport[] = [$row->master_name,'',$clbalanceL1,'','','',''];
				}
			}
		}
			if(isset($directinc['items'][$key])) {
			 foreach($directinc['items'] as $key => $row) {   
				if($row->cl_balance < 0) {
					$arr = explode('-', $row->cl_balance);
					$cl_balanceR1 = '('.number_format($arr[1],2).')';
				} else 
					$cl_balanceR1 = number_format($row->cl_balance,2);
			
			if(Input::get('search_type')=='summary'){
				
				$datareport[] = ['','','','',$row->group_name,'',$cl_balanceR1];
			}else {
				if($row->cl_balance!=0) {
					$datareport[] = ['','','','',$row->master_name,'',$cl_balanceR1];
				}
			}
			 }
		}
		$datareport[] = ['','','','','','',''];
		
		$gpTitle = $glTitle = $gpAmt = $glAmt = '';
		if($grossprofit > 0) { 
			$gpTitle = 'Gross Profit C/F';
			$gpAmt = number_format($grossprofit,2);
			
		}
		
		if($grossprofit < 0) { 
			$glTitle = 'Gross Loss C/F';
			$arr = explode('-', $grossprofit);
			$gross_loss_cf = '('.number_format($arr[1],2).')';
			$glAmt = $gross_loss_cf;
		}
		
		$datareport[] = [$gpTitle,'',$gpAmt,'',$glTitle,'',$glAmt];
		$datareport[] = ['','','','','','',''];
		
		if($subtotal < 0) {
			$arr = explode('-', $subtotal);
			$subtotal = '('.number_format($arr[1],2).')';
		} else 
			$subtotal = number_format($subtotal,2);
															
		$datareport[] = ['Sub Total','',$subtotal,'','Sub Total','',$subtotal];
		
		$grlTitle = $gross_loss_bf = $grpTitle = $grpAmt = '';
		if($grossprofit < 0) { 
			$grlTitle = 'Gross Loss B/F';
			$arr = explode('-', $grossprofit);
			$gross_loss_bf = '('.number_format($arr[1],2).')';
		}
		
		if($grossprofit > 0) {
			$grpTitle = 'Gross Profit B/F';
			$grpAmt = number_format($grossprofit,2);
		} elseif($grossprofit < 0) {
			$arr = explode('-', $netprofit);
			$grpAmt = '('.number_format($arr[1],2).')';
			$grpTitle = 'Net Loss';
		}
		
		$datareport[] = [$grlTitle,'',$gross_loss_bf,'',$grpTitle,'',$grpAmt];
		
		$datareport[] = ['','','','','','',''];
		
		if($indirectexp) {
			if($indirectexp['total'] < 0) {
				$arr = explode('-', $indirectexp['total']);
				$balance_indexp = '('.number_format($arr[1],2).')';
			} else 
				$balance_indexp = number_format($indirectexp['total'],2);
			
			if($indirectinc['total'] < 0) {
				$arr = explode('-', $indirectinc['total']);
				$balance_indinc = '('.number_format($arr[1],2).')';
			} else 
				$balance_indinc = number_format($indirectinc['total'],2);
															
			$datareport[] = [$indirectexp['name'],'',$balance_indexp,'',$indirectinc['name'],'',$balance_indinc];
			
			
			foreach($indirectexp['items'] as $key => $row) {
			
				if($row->cl_balance < 0) {
					$arr = explode('-', $row->cl_balance);
					$clbalanceL1 = '('.number_format($arr[1],2).')';
				} else 
					$clbalanceL1 = number_format($row->cl_balance,2);
			if(Input::get('search_type')=='summary')
					$datareport[] = [$row->group_name,'',$clbalanceL1,'','','',''];
				else {
					if($row->cl_balance!=0) {
						$datareport[] = [$row->master_name,'',$clbalanceL1,'','','',''];
					}
				}
			}
				$cl_balanceR1 = '';
				if(isset($indirectinc['items'][$key])) {
				    foreach($indirectinc['items'] as $key => $row) {
					if($row->cl_balance < 0) {
						$arr = explode('-', $row->cl_balance);
						$cl_balanceR1 = '('.number_format($arr[1],2).')';
					} else 
						$cl_balanceR1 = number_format($row->cl_balance,2);
				
				if(Input::get('search_type')=='summary')
					$datareport[] = ['','','','',$row->group_name,'',$cl_balanceR1];
				else {
					if($row->cl_balance!=0) {
						$datareport[] = ['','','','',$row->master_name,'',$cl_balanceR1];
					}
				    }
			      }
			    }
			$datareport[] = ['','','','','','',''];
			//.............
		}
		
		$npTitle= $npAmt = '';
		if($netprofit > 0) {
			$npTitle = 'Net Profit';
			if($netprofit < 0) {
				$arr = explode('-', $netprofit);
				$netprofit = '('.number_format($arr[1],2).')';
			} else 
				$netprofit = number_format($netprofit,2);
			
			$datareport[] = [$npTitle,'',$netprofit,'',''];
		}
		
		
				
		
		if($total < 0) {
			$arr = explode('-', $total);
			$total = '('.number_format($arr[1],2).')';
		} else 
			$total = number_format($total,2);
			
		$datareport[] = ['Total:'.$currency,'',$total,'','Total:'.$currency,'',$total];
		
		
		//echo '<pre>';print_r($result);exit;//echo $grossprofit;exit;
		//echo $voucher_head.'<pre>';print_r($datareport);exit;
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