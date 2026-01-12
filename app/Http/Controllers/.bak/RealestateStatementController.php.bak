<?php

namespace App\Http\Controllers;
use App\Repositories\AccountMaster\AccountMasterInterface;

use Illuminate\Http\Request;

use App\Http\Requests;
use Session;
use Response;
use Input;
use App;
use Excel;
use DB;
use DateTime;
use Auth;

class RealestateStatementController extends Controller
{

	protected $accountmaster;
	
	public function __construct(AccountMasterInterface $accountmaster) {

		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		
		$this->accountmaster = $accountmaster;
		$this->middleware('auth');
	}
	
    public function index() {
		
		/* $actransactions = $this->accountmaster->getSelectedAccountsCbUlitily('get', 0, 10, 0, 'ASC', '', '');
		$this->makeSummaryAcTrans($this->makeTreeTrans($actransactions));
		echo '<pre>';print_r($actransactions);exit; */
		
		$data = array();
		$acmasters = [];//$this->accountmaster->accountMasterList();
		$jobs = DB::table('jobmaster')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->where('is_salary_job',0)->get();
		$currency = DB::table('currency')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();//echo '<pre>';print_r($currency);exit;
		
		//CHECK DEPARTMENT.......
		if(Session::get('department')==1) { //if active...
			$deptid = Auth::user()->department_id;
			if($deptid!=0)
				$department = DB::table('department')->where('id',$deptid)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
			else {
				$department = DB::table('department')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
				$deptid = '';
			}
			$is_dept = true;
		} else {
			$is_dept = false;
			$department = [];
			$deptid = '';
		}
		
		$category = DB::table('account_category')->where('parent_id','!=',0)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		$groups = DB::table('account_group')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		
		return view('body.realestatestatement.index')
					->withAcmasters($acmasters)
					->withSettings($this->acsettings)
					->withJobs($jobs)
					->withCurrency($currency)
					->withDepartment($department)
					->withDeptid($deptid)
					->withIsdept($is_dept)
					->withGroups($groups)
					->withCategory($category)
					->withData($data);
	}
	
	public function ajaxPaging(Request $request)
	{
		if(Session::get('department')==1) {
			$columns = array( 
								0 =>'account_master.id',
								1 => 'account_id', 
								2 => 'master_name',
								3 => 'group_name',
								4 => 'category_name',
								5 => 'department',
								6 => 'cl_balance',
								7 => 'op_balance'
							);
		} else {
			$columns = array( 
								0 =>'account_master.id',
								1 => 'account_id', 
								2 => 'master_name',
								3 => 'group_name',
								4 => 'category_name',
								5 => 'cl_balance',
								6 => 'op_balance'
							);
		}
						
		$totalData = $this->accountmaster->accountMasterListCount();
            
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
		$search = (empty($request->input('search.value')))?null:$request->input('search.value');
		$dept = $request->input('dept');
		
		//RUNING CLOSING BALANCE UTILITY....
		$this->makeSummaryAcTrans($this->makeTreeTrans( $this->accountmaster->updateUtility('CB')) );
		//$actransactions = $this->accountmaster->getSelectedAccountsCbUlitily('get', $start, $limit, $order, $dir, $search, $dept);
		//$this->makeSummaryAcTrans($this->makeTreeTrans($actransactions));
		//echo '<pre>';print_r($actransactions);exit;
		
		$acmasters = $this->accountmaster->accountMasterList('get', $start, $limit, $order, $dir, $search, $dept);
		
		if($search)
			$totalFiltered =  $this->accountmaster->accountMasterList('count', $start, $limit, $order, $dir, $search, $dept);
		
        $data = array();
        if(!empty($acmasters))
        {
            foreach ($acmasters as $row)
            {
                $view =  '"'.url('account_master/view/'.$row->id).'"';
				$opt =  $row->id;
				
				$nestedData['opt'] = "<input type='radio' name='account' class='opt-account' value='{$opt}'/>";
                
				$nestedData['id'] = $row->id;
                $nestedData['account_id'] = $row->account_id;
				$nestedData['master_name'] = $row->master_name;
				$nestedData['group_name'] = $row->group_name;
				$nestedData['category_name'] = $row->category_name;
				$nestedData['department'] = $row->department;
				$nestedData['cl_balance'] = $row->cl_balance;
				$nestedData['op_balance'] = $row->op_balance;
				$nestedData['issued_qty'] = $row->issued_qty;
				$nestedData['view'] = "<p><button class='btn btn-primary btn-xs' onClick='location.href={$view}'>
												<span class='glyphicon glyphicon-eye-open'></span></button></p>";
												
                $data[] = $nestedData;

            }
        }
          
        $json_data = array(
                    "draw"            => intval($request->input('draw')),  
                    "recordsTotal"    => intval($totalData),  
                    "recordsFiltered" => intval($totalFiltered), 
                    "data"            => $data   
                    );
            
        echo json_encode($json_data);
	}
	
	protected function makeTreeTrans($result)
	{
		$childs = array();
		foreach($result as $item)
			$childs[$item->id][] = $item;

		return $childs;
	}
	
	protected function makeSummaryAcTrans($results)
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
			if($item->reference_from=='')
				$childs[$item->reference][] = $item;
			else
				$childs[$item->reference_from][] = $item;

		foreach($result as $item) if (isset($childs[$item->id]))
			$item->childs = $childs[$item->id];
		
		return $childs;
	}
	
	protected function makeSummary($results)
	{
		$arrSummarry = array();
		foreach($results as $rows)
		{
			$dramount = $cramount = 0; $invoice_date = $voucher_typeid = $voucher_type = $edit = '';
			$vtype = ['SI','PI','OBD','PIN','SIN'];
			foreach($rows as $row) {
				$reference = $row->reference;
				$due_date = $row->invoice_date;
				$description = $row->description; 
				$jobno = isset($row->jobno)?$row->jobno:''; 
				$reference_frm = isset($row->reference_from)?$row->reference_from:'';
				$cheque_date = isset($row->cheque_date)?$row->cheque_date:'';
				$cheque_no = isset($row->cheque_no)?$row->cheque_no:'';
				$account_id = isset($row->account_master_id)?$row->account_master_id:'';
				
				if(!isset($row->is_edit)) {
					if($row->transaction_type=='Dr') {
						$dramount += (isset($row->is_fc))?(($row->is_fc==1)?$row->fc_amount:$row->amount):$row->amount;
					} else {
						$cramount += (isset($row->is_fc))?(($row->is_fc==1)?$row->fc_amount:$row->amount):$row->amount;
					}
				}
				if($row->voucher_type=='PI' || $row->voucher_type=='SI' || $row->voucher_type=='OBD' || $row->voucher_type=='PIN' || $row->voucher_type=='SIN' || $row->voucher_type=='SR' || $row->voucher_type=='PR' || $row->voucher_type=='PS' || $row->voucher_type=='SS') {
					$voucher_typeid = $row->voucher_type_id;
					$voucher_type = $row->voucher_type;
				}
					
				if(in_array($row->voucher_type, $vtype)) {
					$invoice_date = $row->invoice_date;
				}
				
				if($edit=='')
					$edit = (isset($row->is_edit))?$row->is_edit:'';
				
				//if($row->voucher_type=='PI' || $row->voucher_type=='SI' || $row->voucher_type=='OBD' || $row->voucher_type=='CN' || $row->voucher_type=='PIN' || $row->voucher_type=='SR' || $row->voucher_type=='PR' || $row->voucher_type=='JV' || $row->voucher_type=='RV' || $row->voucher_type=='PV')
				/* if($row->voucher_type=='PI' || $row->voucher_type=='SI' || $row->voucher_type=='OBD' || $row->voucher_type=='CN' || $row->voucher_type=='PIN' || $row->voucher_type=='SR' || $row->voucher_type=='PR' || $row->voucher_type=='JV' || $row->voucher_type=='RV' || $row->voucher_type=='PV' || $row->voucher_type=='DB' || $row->voucher_type=='CB')
					$invoice_date = $row->invoice_date; */
				/*  IF RV NOT IN IF CONDITION TAKING THE INVOICE DATE CORRECTLY IN THE OUTSTANDIN BILL OTHERWISE INVOICE DATE IS 01-01-1970 ??? */
			}
			
			$invoice_date = ($invoice_date=='')?$row->invoice_date:$invoice_date; /* ABOVE SOLUTION HERE ON 5 APR 2021 */
			$arrSummarry[] = ['name' => $reference, 
							  'cr_amount' => $cramount, 
							  'dr_amount' => $dramount,
							  'invoice_date' => $invoice_date,
							  'description' => $description,
							  'jobno' => $jobno,
							  'reference_from'	=> ($reference_frm=='')?$reference:$reference_frm,
							  'inv_month' => date('m', strtotime($invoice_date)),
							  'due_date' => $due_date,
							  'cheque_date' => $cheque_date,
							  'cheque_no' => $cheque_no,
							  'account_master_id' => $account_id,
							  'voucher_type'	=> $voucher_type,
							  'voucher_type_id'	=> $voucher_typeid,
							  'is_edit'		=> $edit
							 ];

		}
		return $arrSummarry;
	}
	
	//JUN3....
	protected function makeAgeingSummary($results)
	{
		$arrSummarry = array();
		foreach($results as $rows)
		{
			$dramount = $cramount = 0; $invoice_date = '';
			foreach($rows as $row) {
				$reference = $row->reference;
				$due_date = $row->invoice_date;//$invoice_date = $row->invoice_date;
				$description = $row->description; 
				$reference_frm = $row->reference_from;
				$cheque_date = isset($row->cheque_date)?$row->cheque_date:'';
				$cheque_no = isset($row->cheque_no)?$row->cheque_no:'';
				$master_name = $row->master_name;
				$acid = $row->account_id;
				
				if($row->transaction_type=='Dr')
					$dramount += ($row->is_fc==1)?$row->fc_amount:$row->amount;
				else
					$cramount += ($row->is_fc==1)?$row->fc_amount:$row->amount;
				
				if($row->voucher_type=='PI' || $row->voucher_type=='SI' || $row->voucher_type=='OBD')
					$invoice_date = $row->invoice_date;
				
			}
			$arrSummarry[] = ['name' => $reference, 
							  'acname' => $master_name,
							  'acid'	=> $acid,
							  'cr_amount' => $cramount, 
							  'dr_amount' => $dramount,
							  'invoice_date' => $invoice_date,
							  'description' => $description,
							  'reference_from'	=> ($reference_frm=='')?$reference:$reference_frm,
							  'inv_month' => date('m', strtotime($invoice_date)),
							  'due_date' => $due_date,
							  'cheque_date' => $cheque_date,
							  'cheque_no' => $cheque_no
							  ];

		}
		return $arrSummarry;
	}
	//...JUN3
	
	
	protected function makeSummaryOS($results)
	{
		$arrSummarry = array();
		foreach($results as $rows)
		{
			$dramount = $cramount = 0; $invoice_date = $rvtype = '';
			foreach($rows as $row) {
				$reference = $row->reference;
				$due_date = $row->invoice_date;//$invoice_date = $row->invoice_date;
				$description = $row->description; 
				$reference_frm = $row->reference_from;
				$cheque_date = isset($row->cheque_date)?$row->cheque_date:'';
				$cheque_no = isset($row->cheque_no)?$row->cheque_no:'';
				$rvtype = ($row->rv_type!='')?$row->rv_type:$rvtype;
				
				if($row->transaction_type=='Dr')
					$dramount += ($row->is_fc==1)?$row->fc_amount:$row->amount;
				else
					$cramount += ($row->is_fc==1)?$row->fc_amount:$row->amount;
				
				//if($row->voucher_type=='PI' || $row->voucher_type=='SI')
					$invoice_date = $row->invoice_date;
				
			}
			$arrSummarry[] = ['name' => $reference, 
							  'cr_amount' => $cramount, 
							  'dr_amount' => $dramount,
							  'invoice_date' => $invoice_date,
							  'description' => $description,
							  'reference_from'	=> ($reference_frm=='')?$reference:$reference_frm,
							  'inv_month' => date('m', strtotime($invoice_date)),
							  'due_date' => $due_date,
							  'cheque_date' => $cheque_date,
							  'cheque_no' => $cheque_no,
							  'rv_type' => $rvtype
							  ];

		}
		return $arrSummarry;
	}
	
	protected function makeSummaryPDC($results)
	{
		$arrSummarry = array();
		foreach($results as $rows)
		{
			$dramount = $cramount = 0;
			foreach($rows as $row) {
				$reference = $row->reference;
				$invoice_date = $row->invoice_date;
				$description = $row->description; 
				$reference = $row->reference;
				
				if($row->transaction_type=='Dr')
					$dramount += $row->amount;
				else
					$cramount += $row->amount;
				
			}
			$arrSummarry[] = ['name' => $reference, 
							  'cr_amount' => $cramount, 
							  'dr_amount' => $dramount,
							  'invoice_date' => $invoice_date,
							  'description' => $description,
							  'reference'	=> $reference];

		}
		return $arrSummarry;
	}
	
	protected function makeTreeVchr($result)
	{
		$childs = array();
		foreach($result as $item)
				$childs[$item->voucher_no][] = $item;
			
		return $childs;
	}
	
	
	private function monthly($result)
	{
		$childs = array();
		foreach($result as $item)
			$childs[$item['inv_month']][] = $item;
		
		ksort($childs);
		return $childs;
	}
	
	private function makeConsolidated($result)
	{
		
		$childs = array();
		
		foreach($result as $key => $item)
			$childs[$item->voucher_type][$item->reference][] = $item;
			
			
		foreach($childs as $child) {
		  foreach($child as $rows) {
			$amount = 0;
			foreach($rows as $row) {
				$mastername = $row->master_name;
				$account_id = $row->account_id;
				$id			= $row->id;
				$vouchertype = $row->voucher_type;
				$voucher_type_id = $row->voucher_type_id;
				$account_master_id = $row->account_master_id;
				$transaction_type = $row->transaction_type;
				$description = $row->description;
				$reference = $row->reference;
				$invoice_date = $row->invoice_date;
				$is_paid = $row->is_paid;
				$reference_from = $row->reference_from;
				$tr_for = $row->tr_for;
				
				$amount += $row->amount;
			}
			
			$res[] = (object)(array('master_name' => $mastername,
									'account_id'  => $account_id,
									'id'		 => $id,
									'voucher_type' => $vouchertype,
									'voucher_type_id' => $voucher_type_id,
									'account_master_id' => $account_master_id,
									'transaction_type' => $transaction_type,
								    'amount'	  => $amount,
									'description' 	=> $description,
									'reference' => $reference,
									'invoice_date' => $invoice_date,
									'is_paid'	=> $is_paid,
									'reference_from'	=> $reference_from,
									'tr_for'	=> $tr_for
									));
		  }	
			
			//$res[] = new ArrayObject($re);
		}
		
		return $res;
	}
	
	
	private function makeConsolidatedPdc($result)
	{
		$childs = array();
		foreach($result as $item)
			$childs[$item->reference][] = $item;
		return $childs;	
		/* foreach($childs as $rows) {
			$amount = 0;
			foreach($rows as $row) {
				$mastername = $row->master_name;
				$account_id = $row->account_id;
				$id			= $row->id;
				$vouchertype = $row->voucher_type;
				$voucher_type_id = $row->voucher_type_id;
				$account_master_id = $row->account_master_id;
				$transaction_type = $row->transaction_type;
				$description = $row->description;
				$reference = $row->reference;
				$invoice_date = $row->invoice_date;
				$is_paid = $row->is_paid;
				$reference_from = $row->reference_from;
				$tr_for = $row->tr_for;
				
				$amount += $row->amount;
			}
			
			$res[] = (object)(array('master_name' => $mastername,
									'account_id'  => $account_id,
									'id'		 => $id,
									'voucher_type' => $vouchertype,
									'voucher_type_id' => $voucher_type_id,
									'account_master_id' => $account_master_id,
									'transaction_type' => $transaction_type,
								    'amount'	  => $amount,
									'description' 	=> $description,
									'reference' => $reference,
									'invoice_date' => $invoice_date,
									'is_paid'	=> $is_paid,
									'reference_from'	=> $reference_from,
									'tr_for'	=> $tr_for
									));
		}
		
		return $res;*/
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
	
	public function searchAccount()
	{
		//echo '<pre>';print_r(Input::all());
		$data = $pdctransactions = array(); $opn_balnce = null;
		$frmdate = Input::get('date_from');
		$todate = Input::get('date_to');
		Input::merge(['curr_from_date' => $this->acsettings->from_date]); 
		$job_id = (Input::get('job_id')!='')?Input::get('job_id'):null;
		
		//JUN3....
		$infc = $currency = '';
		if(Input::get('inFC')==1) {
			$currency = DB::table('currency')->where('id',Input::get('currency_id'))->first();
			if(!$currency) 
				$currency = DB::table('currency')->where('status', 1)->where('is_default', 0)->first();
			
			$infc = Input::get('inFC');
		}
			
		$account_id = Input::get('account_id');
		$is_default = Input::get('is_default');
		$headarr = [];
		if($is_default=='1') { //***************NOT IN USE	
			if(Input::get('type')=='statement') {
				$voucher_head = ($infc=='')?'Statement of Account':'Statement of Account in FC';
				if(Input::get('is_con')==1) {
					$transactions = $this->makeConsolidated( $this->accountmaster->getPrintViewByAccount(Input::all()) );
				} else 
					$transactions = $this->accountmaster->getPrintViewByAccount(Input::all());
				
				$pdctransactions = $this->accountmaster->getPDCPrintViewByAccount(Input::all());
				//echo '<pre>';print_r($transactions);exit;
				//echo $this->acsettings->from_date.' '.Input::get('date_from');exit; 01-01-2021 01-10-2020
				if( (date('d-m-Y',strtotime($this->acsettings->from_date))!=Input::get('date_from'))) { // && (date('d-m-Y',strtotime($this->acsettings->to_date))!=Input::get('date_to')) 
					$enddate = date('Y-m-d', strtotime('-1 day', strtotime(Input::get('date_from'))));
				
					$obtrn = DB::table('account_transaction')->where('voucher_type','OB')->where('account_master_id',$account_id)->where('status',1)
													->where('deleted_at','0000-00-00 00:00:00')->select('invoice_date')->first();
													
					Input::merge(['date_from' =>  $obtrn->invoice_date]);
					Input::merge(['date_to' => $enddate]);
					
					if($transactions[0]->voucher_type!='OB')
						$opn_balnce = $this->getOpeningBalance($this->accountmaster->getPrintViewByAccount(Input::all()));
					
					//echo '<pre>'.print_r($opn_balnce);exit;
				} else {
					if(!$transactions || $transactions[0]->voucher_type!='OB') {
						$obtrn = DB::table('account_transaction')->where('voucher_type','OB')->where('account_master_id',$account_id)->where('status',1)
													->where('deleted_at','0000-00-00 00:00:00')->select('invoice_date')->first();
						
						$enddate = date('Y-m-d', strtotime('-1 day', strtotime(Input::get('date_from'))));
						Input::merge(['date_from' => $obtrn->invoice_date]);
						Input::merge(['date_to' => $enddate]);
						$opn_balnce = $this->getOpeningBalance($this->accountmaster->getPrintViewByAccount(Input::all()));
						//echo '<pre>'.print_r($opn_balnce);exit;
					}
				}
				
			} else if(Input::get('type')=='ageing') {
				$accounts = DB::table('account_master')->where('category', $account_id)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id')->get();
				
				foreach($accounts as $row) {
					$results = $this->accountmaster->getAgeingSummary($row->id, Input::all());
					$transactions[] = $this->makeAgeingSummary($this->makeTree($results));
					
				}
				//echo '<pre>';print_r($transactions);exit;
				$voucher_head = ($infc=='')?'Statement of Account - Ageing Summary':'Statement of Account - Ageing Summary in FC';
				$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];
				return view('body.realestatestatement.printgroup')
							->withTransactions($transactions)
							->withVoucherhead($voucher_head)
							->withType(Input::get('type'))
							->withTitles($titles)
							->withFromdate($frmdate)
							->withTodate($todate)
							->withUrl('account_enquiry')
							->withSettings($this->acsettings)
							->withId($account_id)
							->withFc($infc)
							->withCurrency($currency)
							->withData($data);
			} else echo 'Please select at least one account!';
		//***************NOT IN USE		
		} else { 
		
			$resultrow = [];//$this->accountmaster->findDetails($account_id);
			//echo $account_id;
			if(Input::get('type')=='statement') {
				$voucher_head = ($infc=='')?'Statement of Account':'Statement of Account in FC';
				if(Input::get('is_con')==1) {
					$transactions = $this->SortByAccount($this->makeConsolidated($this->accountmaster->getPrintViewByAccount(Input::all())) );
					usort($transactions, array($this, "date_compare_obj"));
				} else 
					$transactions = $this->SortByAccount($this->accountmaster->getPrintViewByAccount(Input::all()));
				
				//echo '<pre>';print_r($transactions);exit;
				$pdctransactions = $this->accountmaster->getPDCPrintViewByAccount(Input::all());
				//echo '<pre>';print_r($transactions);exit;
				
				if((date('d-m-Y',strtotime($this->acsettings->from_date))!=Input::get('date_from'))) { 
					foreach($transactions as $key => $transaction ) {
						$resultrow[$key] = $this->accountmaster->findDetails($transaction[0]->account_master_id);
						if(Input::get('exclude_ob')==1) {
							$opn_balnce[$key] = null;
						} else {
							$enddate = date('Y-m-d', strtotime('-1 day', strtotime(Input::get('date_from'))));
						
							$obtrn = DB::table('account_transaction')->where('voucher_type','OB')->where('account_master_id',$transaction[0]->account_master_id)->where('status',1)
															->where('deleted_at','0000-00-00 00:00:00')->select('invoice_date')->first();
															
							Input::merge(['date_from' =>  $obtrn->invoice_date]);
							Input::merge(['date_to' => $enddate]);
							
							if($transaction[0]->voucher_type!='OB')
								$opn_balnce[$key] = $this->getOpeningBalance($this->accountmaster->getPrintViewByAccount(Input::all()));
						}
					}
				} else { 
					foreach($transactions as $key => $transaction ) {
						$resultrow[$key] = $this->accountmaster->findDetails($transaction[0]->account_master_id);
						
						if(!$transaction || $transaction[0]->voucher_type!='OB') {
							
							$obtrn = DB::table('account_transaction')->where('voucher_type','OB')->where('account_master_id',$transaction[0]->account_master_id)->where('status',1)
														->where('deleted_at','0000-00-00 00:00:00')->select('invoice_date')->first();
							
							$enddate = date('Y-m-d', strtotime('-1 day', strtotime(Input::get('date_from'))));
							Input::merge(['date_from' => $obtrn->invoice_date]);
							Input::merge(['date_to' => $enddate]);
							$opn_balnce[$key] = $this->getOpeningBalance($this->accountmaster->getPrintViewByAccount(Input::all()));
						}
					}
				}
				
			//	echo '<pre>';print_r($resultrow);exit;
				//SORT BY CATEGORY & GETING CATEGORY HEADING...
				if(!empty(Input::get('category_id'))) {
					$transactions = $this->SortByCategory($transactions);
					foreach($transactions as $key => $val) {
						$headarr[$key] = DB::table('account_category')->where('id',$key)->select('name AS heading')->first();
					}
				}
				
				//SORT BY GROUP & GETING GROUP HEADING...
				if(!empty(Input::get('group_id'))) {
					$transactions = $this->SortByGroup($transactions);
					foreach($transactions as $key => $val) {
						$headarr[$key] = DB::table('account_group')->where('id',$key)->select('name AS heading')->first();
					}
				}
				
				//SORT BY TYPE & GETING TYPE HEADING...
				if(!empty(Input::get('type_id'))) {
					$transactions = $this->SortByType($transactions);
					foreach(Input::get('type_id') as $key) {
						$headarr[$key] = (object)array('heading'=>$key);
					}
				}
				
				//DEFAULT SORTING...
				if(Input::get('is_custom')==0) {
					$transactions = $this->SortByCategory($transactions);
					foreach($transactions as $key => $val) {
						$headarr[$key] = DB::table('account_category')->where('id',$key)->select('name AS heading')->first();
					}
				}
				//echo '<pre>';print_r($headarr);
				
			} else if(Input::get('type')=='outstanding') {
				
				$voucher_head = ($infc=='')?'Statement of Account - Outstanding':'Statement of Account - Outstanding in FC';
				$results = $this->accountmaster->getPrintViewByAccount(Input::all());//echo '<pre>';print_r($results);exit;
				$transactions = $this->makeSummary($this->makeTree($results)); 
				usort($transactions, array($this, "date_compare"));
				$transactions = $this->SortByAccountOS($transactions);
				
				foreach($transactions as $key => $transaction ) {
					$resultrow[$key] = $this->accountmaster->findDetails($transaction[0]['account_master_id']);
				}
				/* $results = $this->accountmaster->getPrintViewByAccount(Input::all());  
				$transactions = $this->makeSummary($this->makeTree($results));
				usort($transactions, array($this, "date_compare"));
				echo '<pre>';print_r($resultrow);*/
				//echo '<pre>';print_r($transactions);exit; 
				
				/* $pdcres = $this->accountmaster->getPDCPrintViewByAccountOS(Input::all(), $resultrow->category);
				$pdctransactions = $this->makeSummaryOS($this->makeTree($pdcres)); */
				
				$pdctransactions = $this->accountmaster->getPDCPrintViewByAccount(Input::all(),'OS');
				
			} else if(Input::get('type')=='item-statement') {
				
				$voucher_head = 'Statement with Stock';
				$transactions = $this->makeTreeVchr($this->accountmaster->getItemStatement(Input::all()));
				
			} else if(Input::get('type')=='osmonthly') {
				$voucher_head = 'Statement of Account - Outstanding(Monthly)';
				$resultrow = $this->accountmaster->findDetails($account_id);
				$results = $this->accountmaster->getPrintViewByAccount(Input::all()); 
				$transactions = $this->monthly($this->correction($this->makeSummary($this->makeTree($results)),$resultrow));
			} else {
				$voucher_head = ($infc=='')?'Statement of Account - Ageing':'Statement of Account - Ageing in FC';
				$results = $this->accountmaster->getPrintViewByAccount(Input::all());
				$transactions = $this->makeSummary($this->makeTree($results));
				$transactions = $this->SortByAccountOS($transactions);
				
				foreach($transactions as $key => $transaction ) {
					$resultrow[$key] = $this->accountmaster->findDetails($transaction[0]['account_master_id']);
				}
			}
			
			/* foreach($transactions as $key => $transaction) {
				echo $resultrow[$key]->master_name.'<br>';
			} */
			//exit;
			
			//echo '<pre>';print_r($resultrow);exit;
			//echo '<pre>';print_r($transactions);exit;
			//echo '<pre>';print_r($opn_balnce);exit;
			//echo '<pre>';print_r(Session::get('company'));exit;
			$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];
			return view('body.realestatestatement.newprint')
						->withTransactions($transactions)
						->withResultrow($resultrow)
						->withVoucherhead($voucher_head)
						->withType(Input::get('type'))
						->withTitles($titles)
						->withFromdate($frmdate)
						->withTodate($todate)
						->withUrl('account_enquiry')
						->withSettings($this->acsettings)
						->withId($account_id)
						->withIspdc(Input::get('is_pdc'))
						->withIscon(Input::get('is_con'))
						->withPdcs($pdctransactions)
						->withOpenbalance($opn_balnce)
						->withFc($infc)
						->withCurrency($currency)
						->withJobid($job_id)
						->withHeadarr($headarr)
						->withIscustom(Input::get('is_custom'))
						->withTypeid(!empty(Input::get('type_id'))?implode(',',Input::get('type_id')):'')
						->withCatid(!empty(Input::get('category_id'))?implode(',',Input::get('category_id')):'')
						->withGroupid(!empty(Input::get('group_id'))?implode(',',Input::get('group_id')):'')
						->withData($data);
		}//....JUN3
	}
	
	
	
	public function dataExport()
	{
		
		$data = $pdcreports = array();
		
		//hjjbkjbmnmm
		//Input::merge(['type' => 'export']);
		Input::merge(['curr_from_date' => $this->acsettings->from_date]);
		$datareport[] = [strtoupper(Session::get('company')),'','',''];
		$datareport[] = ['','','','','','',''];
		$account_id = Input::get('account_id');

		$is_default = Input::get('is_default');

		(Input::get('group_id')!='')?(Input::merge(['group_id' => explode(',',Input::get('group_id'))])):null;
		(Input::get('type_id')!='')?(Input::merge(['type_id' => explode(',',Input::get('type_id'))])):null;
		(Input::get('category_id')!='')?(Input::merge(['category_id' => explode(',',Input::get('category_id'))])):null;
	
		
	}
	
	private static function date_compare($a, $b) {
		$t1 = strtotime($a['invoice_date']);
		$t2 = strtotime($b['invoice_date']);
		return $t1 - $t2;
	}
		
	private static function date_compare_obj($a, $b) {
		$t1 = strtotime($a->invoice_date);
		$t2 = strtotime($b->invoice_date);
		return $t1 - $t2;
	}
	
	private static function date_compare_bill($a, $b) {
		$t1 = strtotime($a->tr_date);
		$t2 = strtotime($b->tr_date);
		return $t1 - $t2;
	}
	
	public function addressList() {
		
		$data = array();
		return view('body.realestatestatement.addresssearch')
					->withData($data);
	}
	
	public function searchAddress()
	{
		$data = array();
		$resultrow = $this->accountmaster->searchAddressList(Input::all());
		$heading = Input::get('account_type');
		return view('body.realestatestatement.addresslist')
					->withAddresslist($resultrow)
					->withHeading($heading)
					->withType(Input::get('account_type'))
					->withName(Input::get('account_name'))
					->withData($data);
					
		//echo '<pre>';print_r($resultrow);
	}
	
	public function addressExport()
	{
		$data = array();
		$voucher_head = 'Address List - '.Input::get('account_type');
		$reports = $this->accountmaster->searchAddressList(Input::all());
		$datareport[] = ['Account ID','Account Name','Address','Phone','Email','TRN No'];
		
		foreach($reports as $row) {
			$datareport[] = [ 'account_id' => $row->account_id,
							  'account_name' => $row->master_name,
							  'address' => $row->address,
							  'phone' => $row->phone,
							  'email' => $row->email,
							  'trnno' => $row->vat_no
							];
		}
		
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
	
	//public function outStandingBills($id,$mod=null,$no=null,$ref=null,$rid=null) {
	public function outStandingBills($id,$no=null,$mod=null,$rvid=null) {
		
		Input::merge(['account_id' => $id]);
		Input::merge(['date_from' => date('d-m-Y')]);
		Input::merge(['date_to' => '']);
		Input::merge(['type' => 'outstanding']);
		Input::merge(['is_custom' => 0]);
		
		$account = DB::table('account_master')->where('id',$id)->select('category')->first();
		$results = $this->accountmaster->getPrintViewByAccount(Input::all()); //echo '<pre>';print_r($account);exit;
		$results_edit = [];
		if($mod) {
			$results_edit = $this->getDatas($mod,$rvid);
		}
		//echo '<pre>';print_r($results_edit);exit;
		$merge_results = array_merge($results,$results_edit);  //echo '<pre>';print_r($merge_results);exit;
		//$transactions = $this->makeTree($merge_results); echo '<pre>';print_r($transactions);exit;
		$transactions = $this->makeSummary($this->makeTree($merge_results)); //echo '<pre>';print_r($transactions);exit;
		usort($transactions, array($this, "date_compare"));
		//$transactions = $this->SortByAccountOS($transactions);
		//echo '<pre>';print_r($transactions);exit;
		
		/* if($mod) {
			$transactions_edit = $this->getDatas($mod,$rvid);
		} */
		
		//foreach($transactions as $key => $transaction) {
		$datas = [];
		foreach($transactions as $trans) {
			$balance = bcsub($trans['dr_amount'], $trans['cr_amount'], 2);
			if($mod) {
				if($account->category=='CUSTOMER') {	
					if($balance < 0) {
						$balance = $balance*-1; $type = 'Cr';
					} else
						$type = 'Dr';
					
					if($trans['is_edit']=='E') {
						if($trans['dr_amount']==$trans['cr_amount']) {
							$balance = 0; $asgn_amount = ($type=='Dr')?$trans['dr_amount']:$trans['cr_amount'];
						} else 
							$asgn_amount = ($type=='Dr')?$trans['cr_amount']:$trans['dr_amount'];
					} else
						$asgn_amount = '';
					
						$datas[] = (object)['tr_date' => date('d-m-Y', strtotime($trans['invoice_date'])),
									'reference_no' => $trans['reference_from'],
									'description' => $trans['description'],
									'balance_amount'	=> $balance,
									'tr_type'	=> $type,
									'voucher_type'	=> $trans['voucher_type'],
									'voucher_type_id'	=> $trans['voucher_type_id'],
									'is_edit' 	=> $trans['is_edit'],
									'asgn_amount' 	=> $asgn_amount
									];
									
				} else if($account->category=='SUPPLIER') {	
					if($balance < 0) {
						$balance = $balance*-1; $type = 'Cr';
					} else
						$type = 'Dr';
					
					if($trans['is_edit']=='E') {
						if($trans['dr_amount']==$trans['cr_amount']) {
							$balance = 0; $asgn_amount = ($type=='Dr')?$trans['dr_amount']:$trans['cr_amount'];
						} else 
							$asgn_amount = ($type=='Dr')?$trans['cr_amount']:$trans['dr_amount'];
					} else
						$asgn_amount = '';
					
						$datas[] = (object)['tr_date' => date('d-m-Y', strtotime($trans['invoice_date'])),
									'reference_no' => $trans['reference_from'],
									'description' => $trans['description'],
									'balance_amount'	=> $balance,
									'tr_type'	=> $type,
									'voucher_type'	=> $trans['voucher_type'],
									'voucher_type_id'	=> $trans['voucher_type_id'],
									'is_edit' 	=> $trans['is_edit'],
									'asgn_amount' 	=> $asgn_amount
									];
				}
			} else {
				if($account->category=='CUSTOMER' && $balance !=0) {	
					if($balance < 0) {
						$balance = $balance*-1; $type = 'Cr';
					} else
						$type = 'Dr';
					
						$datas[] = (object)['tr_date' => date('d-m-Y', strtotime($trans['invoice_date'])),
									'reference_no' => $trans['reference_from'],
									'description' => $trans['description'],
									'balance_amount'	=> $balance,
									'tr_type'	=> $type,
									'voucher_type'	=> $trans['voucher_type'],
									'voucher_type_id'	=> $trans['voucher_type_id'],
									'is_edit' 	=> $trans['is_edit'],
									'asgn_amount' 	=> ''
									];
				} else if($account->category=='SUPPLIER' && $balance !=0) {	
				
					if($balance < 0) {
						$balance = $balance*-1; $type = 'Cr';
					} else
						$type = 'Dr';
					
						$datas[] = (object)['tr_date' => date('d-m-Y', strtotime($trans['invoice_date'])),
									'reference_no' => $trans['reference_from'],
									'description' => $trans['description'],
									'balance_amount'	=> $balance,
									'tr_type'	=> $type,
									'voucher_type'	=> $trans['voucher_type'],
									'voucher_type_id'	=> $trans['voucher_type_id'],
									'is_edit' 	=> $trans['is_edit'],
									'asgn_amount' 	=> ''
									];
				}
			}
		}		
		//}
		//echo '<pre>';print_r($datas);exit;
		//$data = array_merge($datas,$transactions_edit);
		usort($datas, array($this, "date_compare_bill"));
		
		//echo '<pre>';print_r($data);exit;
		
		return view('body.realestatestatement.osbills')
					->withOsbills($datas)->withType($account->category)->withNo($no);
		
	}
	
	
	
	private function getDatas($mod,$id) { 
		
		$result = [];
		if($mod=='RV') {
			$RVE1 = DB::table('receipt_voucher') 
						->join('receipt_voucher_entry AS RVE','RVE.receipt_voucher_id','=','receipt_voucher.id')
						->join('receipt_voucher_tr AS RVT','RVT.receipt_voucher_entry_id','=','RVE.id')
						->join('account_transaction AS AT', function($join) {
							$join->on('AT.voucher_type_id','=','RVE.id');
							$join->where('AT.voucher_type','=','RV');
							$join->where('AT.status','=',1);
							$join->where('AT.deleted_at','=','0000-00-00 00:00:00');
						})
						->join('sales_invoice AS SI', function($join) {
							$join->on('SI.id','=','RVT.sales_invoice_id');
							$join->where('RVT.bill_type','=','SI');
							$join->where('RVT.status','=',1);
							$join->where('RVT.deleted_at','=','0000-00-00 00:00:00');
						})
						->where('receipt_voucher.id',$id)
						->select('SI.voucher_date AS invoice_date','SI.voucher_no AS reference','RVE.description','RVE.amount AS amount','AT.reference_from','AT.id',
								'RVE.entry_type AS transaction_type','RVT.bill_type AS voucher_type','RVT.receipt_voucher_entry_id AS voucher_type_id',DB::raw('"E" AS is_edit'));
						
			$RVE2 = DB::table('receipt_voucher')
						->join('receipt_voucher_entry AS RVE','RVE.receipt_voucher_id','=','receipt_voucher.id')
						->join('receipt_voucher_tr AS RVT','RVT.receipt_voucher_entry_id','=','RVE.id')
						->join('account_transaction AS AT', function($join) {
							$join->on('AT.voucher_type_id','=','RVE.id');
							$join->where('AT.voucher_type','=','RV');
							$join->where('AT.status','=',1);
							$join->where('AT.deleted_at','=','0000-00-00 00:00:00');
						})
						->join('sales_split AS SS', function($join) {
							$join->on('SS.id','=','RVT.sales_invoice_id');
							$join->where('RVT.bill_type','=','SS');
							$join->where('RVT.status','=',1);
							$join->where('RVT.deleted_at','=','0000-00-00 00:00:00');
						})
						->where('receipt_voucher.id',$id)
						->select('SS.voucher_date AS invoice_date','SS.voucher_no AS reference','RVE.description','RVE.amount AS amount','AT.reference_from','AT.id',
								'RVE.entry_type AS transaction_type','RVT.bill_type AS voucher_type','RVT.receipt_voucher_entry_id AS voucher_type_id',DB::raw('"E" AS is_edit'));
								
			$RVE3 = DB::table('receipt_voucher')
						->join('receipt_voucher_entry AS RVE','RVE.receipt_voucher_id','=','receipt_voucher.id')
						->join('receipt_voucher_tr AS RVT','RVT.receipt_voucher_entry_id','=','RVE.id')
						->join('account_transaction AS AT', function($join) {
							$join->on('AT.voucher_type_id','=','RVE.id');
							$join->where('AT.voucher_type','=','RV');
							$join->where('AT.status','=',1);
							$join->where('AT.deleted_at','=','0000-00-00 00:00:00');
						})
						->join('sales_return AS SR', function($join) {
							$join->on('SR.id','=','RVT.sales_invoice_id');
							$join->where('RVT.bill_type','=','SR');
							$join->where('RVT.status','=',1);
							$join->where('RVT.deleted_at','=','0000-00-00 00:00:00');
						})
						->where('receipt_voucher.id',$id)
						->select('SR.voucher_date AS invoice_date','SR.voucher_no AS reference','RVE.description','RVE.amount AS amount','AT.reference_from','AT.id',
								'RVE.entry_type AS transaction_type','RVT.bill_type AS voucher_type','RVT.receipt_voucher_entry_id AS voucher_type_id',DB::raw('"E" AS is_edit'));
								
			$result = $RVE1->union($RVE2)->union($RVE3)->get();
						//echo '<pre>';print_r($result);exit;
						
		} else if($mod=='PV') {
			$PVE1 = DB::table('payment_voucher') 
						->join('payment_voucher_entry AS PVE','PVE.payment_voucher_id','=','payment_voucher.id')
						->join('payment_voucher_tr AS PVT','PVT.payment_voucher_entry_id','=','PVE.id')
						->join('account_transaction AS AT', function($join) {
							$join->on('AT.voucher_type_id','=','PVE.id');
							$join->where('AT.voucher_type','=','PV');
							$join->where('AT.status','=',1);
							$join->where('AT.deleted_at','=','0000-00-00 00:00:00');
						})
						->join('purchase_invoice AS PI', function($join) {
							$join->on('PI.id','=','PVT.purchase_invoice_id');
							$join->where('PVT.bill_type','=','PI');
							$join->where('PVT.status','=',1);
							$join->where('PVT.deleted_at','=','0000-00-00 00:00:00');
						})
						->where('payment_voucher.id',$id)
						->select('PI.voucher_date AS invoice_date','PI.voucher_no AS reference','PVE.description','PVE.amount AS amount','AT.reference_from','AT.id',
								'PVE.entry_type AS transaction_type','PVT.bill_type AS voucher_type','PVT.payment_voucher_entry_id AS voucher_type_id',DB::raw('"E" AS is_edit'));
						
			$PVE2 = DB::table('payment_voucher')
						->join('payment_voucher_entry AS PVE','PVE.payment_voucher_id','=','payment_voucher.id')
						->join('payment_voucher_tr AS PVT','PVT.payment_voucher_entry_id','=','PVE.id')
						->join('account_transaction AS AT', function($join) {
							$join->on('AT.voucher_type_id','=','PVE.id');
							$join->where('AT.voucher_type','=','PV');
							$join->where('AT.status','=',1);
							$join->where('AT.deleted_at','=','0000-00-00 00:00:00');
						})
						->join('purchase_split AS PS', function($join) {
							$join->on('PS.id','=','PVT.purchase_invoice_id');
							$join->where('PVT.bill_type','=','PS');
							$join->where('PVT.status','=',1);
							$join->where('PVT.deleted_at','=','0000-00-00 00:00:00');
						})
						->where('payment_voucher.id',$id)
						->select('PS.voucher_date AS invoice_date','PS.voucher_no AS reference','PVE.description','PVE.amount AS amount','AT.reference_from','AT.id',
								'PVE.entry_type AS transaction_type','PVT.bill_type AS voucher_type','PVT.payment_voucher_entry_id AS voucher_type_id',DB::raw('"E" AS is_edit'));
								
			$PVE3 = DB::table('payment_voucher')
						->join('payment_voucher_entry AS PVE','PVE.payment_voucher_id','=','payment_voucher.id')
						->join('payment_voucher_tr AS PVT','PVT.payment_voucher_entry_id','=','PVE.id')
						->join('account_transaction AS AT', function($join) {
							$join->on('AT.voucher_type_id','=','PVE.id');
							$join->where('AT.voucher_type','=','PV');
							$join->where('AT.status','=',1);
							$join->where('AT.deleted_at','=','0000-00-00 00:00:00');
						})
						->join('purchase_return AS PR', function($join) {
							$join->on('PR.id','=','PVT.purchase_invoice_id');
							$join->where('PVT.bill_type','=','PR');
							$join->where('PVT.status','=',1);
							$join->where('PVT.deleted_at','=','0000-00-00 00:00:00');
						})
						->where('payment_voucher.id',$id)
						->select('PR.voucher_date AS invoice_date','PR.voucher_no AS reference','PVE.description','PVE.amount AS amount','AT.reference_from','AT.id',
								'PVE.entry_type AS transaction_type','PVT.bill_type AS voucher_type','PVT.payment_voucher_entry_id AS voucher_type_id',DB::raw('"E" AS is_edit'));
								
			$result = $PVE1->union($PVE2)->union($PVE3)->get();
						//echo '<pre>';print_r($result);exit;
						
		} else if($mod=='JV') { 
			
			$JE1 = DB::table('journal') 
						->join('journal_entry AS JE','JE.journal_id','=','journal.id')
						->join('journal_voucher_tr AS JVT','JVT.journal_entry_id','=','JE.id')
						->join('account_transaction AS AT', function($join) {
							$join->on('AT.voucher_type_id','=','JE.id');
							$join->where('AT.voucher_type','=','JV');
							$join->where('AT.status','=',1);
							$join->where('AT.deleted_at','=','0000-00-00 00:00:00');
						})
						->join('sales_invoice AS SI', function($join) {
							$join->on('SI.id','=','JVT.invoice_id');
							$join->where('JVT.bill_type','=','SI');
							$join->where('JVT.status','=',1);
							$join->where('JVT.deleted_at','=','0000-00-00 00:00:00');
						})
						->where('journal.id',$id)
						->select('SI.voucher_date AS invoice_date','SI.voucher_no AS reference','JE.description','JE.amount AS amount','AT.reference_from','AT.id',
								'JE.entry_type AS transaction_type','JVT.bill_type AS voucher_type','JVT.journal_entry_id AS voucher_type_id',DB::raw('"E" AS is_edit'));
						
			$JE2 = DB::table('journal')
						->join('journal_entry AS JE','JE.journal_id','=','journal.id')
						->join('journal_voucher_tr AS JVT','JVT.journal_entry_id','=','JE.id')
						->join('account_transaction AS AT', function($join) {
							$join->on('AT.voucher_type_id','=','JE.id');
							$join->where('AT.voucher_type','=','JV');
							$join->where('AT.status','=',1);
							$join->where('AT.deleted_at','=','0000-00-00 00:00:00');
						})
						->join('sales_split AS SS', function($join) {
							$join->on('SS.id','=','JVT.invoice_id');
							$join->where('JVT.bill_type','=','SS');
							$join->where('JVT.status','=',1);
							$join->where('JVT.deleted_at','=','0000-00-00 00:00:00');
						})
						->where('journal.id',$id)
						->select('SS.voucher_date AS invoice_date','SS.voucher_no AS reference','JE.description','JE.amount AS amount','AT.reference_from','AT.id',
								'JE.entry_type AS transaction_type','JVT.bill_type AS voucher_type','JVT.journal_entry_id AS voucher_type_id',DB::raw('"E" AS is_edit'));
								
			$JE3 = DB::table('journal')
						->join('journal_entry AS JE','JE.journal_id','=','journal.id')
						->join('journal_voucher_tr AS JVT','JVT.journal_entry_id','=','JE.id')
						->join('account_transaction AS AT', function($join) {
							$join->on('AT.voucher_type_id','=','JE.id');
							$join->where('AT.voucher_type','=','JV');
							$join->where('AT.status','=',1);
							$join->where('AT.deleted_at','=','0000-00-00 00:00:00');
						})
						->join('sales_return AS SR', function($join) {
							$join->on('SR.id','=','JVT.invoice_id');
							$join->where('JVT.bill_type','=','SR');
							$join->where('JVT.status','=',1);
							$join->where('JVT.deleted_at','=','0000-00-00 00:00:00');
						})
						->where('journal.id',$id)
						->select('SR.voucher_date AS invoice_date','SR.voucher_no AS reference','JE.description','JE.amount AS amount','AT.reference_from','AT.id',
								'JE.entry_type AS transaction_type','JVT.bill_type AS voucher_type','JVT.journal_entry_id AS voucher_type_id',DB::raw('"E" AS is_edit'));
								
			$result = $JE1->union($JE2)->union($JE3)->get();
			
		}
		
		return $result;
	}
	
		
}

//SELECT itemmaster.item_code,department.name AS department,sales_invoice.voucher_no,sales_invoice.voucher_date,sales_invoice.lpo_no,sales_invoice.lpo_date,sales_invoice.total,sales_invoice.discount,sales_invoice.vat_amount,sales_invoice.net_total,sales_invoice.total_fc,sales_invoice.discount_fc,sales_invoice.vat_amount_fc,sales_invoice.net_total_fc,sales_invoice.customer_name,sales_invoice.customer_phone,sales_invoice.subtotal,account_master.account_id,account_master.master_name,account_master.address,account_master.phone,account_master.vat_no,terms.description AS terms,salesman.name AS salesman,sales_invoice_item.item_name,sales_invoice_item.quantity,sales_invoice_item.unit_price,sales_invoice_item.vat,sales_invoice_item.vat,sales_invoice_item.vat_amount AS line_vat,sales_invoice_item.line_total,sales_invoice_item.tax_include,sales_invoice_item.item_total,units.unit_name,sales_invoice_item.id AS sii_id,vehicle.reg_no,vehicle.model,vehicle.make,sales_invoice.kilometer FROM sales_invoice JOIN account_master ON(account_master.id=sales_invoice.customer_id) LEFT JOIN terms ON(terms.id=sales_invoice.terms_id) LEFT JOIN salesman ON(salesman.id=sales_invoice.salesman_id) JOIN sales_invoice_item ON(sales_invoice_item.sales_invoice_id=sales_invoice.id) JOIN itemmaster ON(itemmaster.id=sales_invoice_item.item_id) JOIN units ON(units.id=sales_invoice_item.unit_id) LEFT JOIN department ON(department.id=sales_invoice.department_id) LEFT JOIN vehicle ON(vehicle.id=sales_invoice.vehicle_id) WHERE sales_invoice_item.status=1 AND sales_invoice_item.deleted_at='0000-00-00 00:00:00' AND sales_invoice.id={id}