<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Bank\BankInterface;
use App\Repositories\Currency\CurrencyInterface;
use App\Repositories\VoucherNo\VoucherNoInterface;
use App\Repositories\Jobmaster\JobmasterInterface;
use App\Repositories\Department\DepartmentInterface;
use App\Repositories\ManualJournal\ManualJournalInterface;
use App\Repositories\AccountSetting\AccountSettingInterface;
use App\Repositories\ReceiptVoucher\ReceiptVoucherInterface;
use App\Repositories\PaymentVoucher\PaymentVoucherInterface;

use App\Http\Requests;
use Input;
use Session;
use Response;
use Validator;
use DB;
use Auth;
use App;

class ManualJournalController extends Controller
{
    protected $bank;
	protected $currency;
	protected $voucherno;
	protected $jobmaster;
	protected $department;
	protected $manual_journal;
	protected $accountsetting;
	protected $receipt_voucher;
	protected $payment_voucher;
	
	public function __construct(AccountSettingInterface $accountsetting,  ReceiptVoucherInterface $receipt_voucher, PaymentVoucherInterface $payment_voucher, BankInterface $bank, CurrencyInterface $currency, VoucherNoInterface $voucherno, JobmasterInterface $jobmaster, DepartmentInterface $department,ManualJournalInterface $manual_journal) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->middleware('auth');
		$this->bank = $bank;
		$this->currency = $currency;
		$this->voucherno = $voucherno;
		$this->jobmaster = $jobmaster;
		$this->department = $department;
		$this->manual_journal = $manual_journal;
		$this->accountsetting = $accountsetting;
		$this->receipt_voucher = $receipt_voucher;
		$this->payment_voucher = $payment_voucher;
	}
	
	public function index() {
		$data = array();
		$journals = $this->manual_journal->journalList();//
	//	echo '<pre>';print_r($journals);exit;
		$prints = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','MJV')
							->select('report_view_detail.name','report_view_detail.id')
							->get();
							
		return view('body.manualjournal.index')
					->withJournals($journals)
					->withPrints($prints)
					->withData($data);
	}
	
	public function add($id=null,$rid=null,$vouchertype=null) {
       // echo '<pre>';print_r($id);
		//echo '<pre>';print_r($rid);
		//echo '<pre>';print_r($vouchertype);exit;
		$data = array();
		$currency = $this->currency->activeCurrencyList();
		$banks = $this->bank->activeBankList();
		$jobs = $this->jobmaster->activeJobmasterList();
		//$departments = $this->department->activeDepartmentList();
		$account = $this->accountsetting->getExpenseAccount();
		$lastid = $this->manual_journal->getLastId();
		$prints = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','JV')
							->select('report_view_detail.name','report_view_detail.id')
							->get();
		$vouchers = $this->accountsetting->getAccountSettingsById($vid=28);
		$vchrdata = $this->getVoucherJV($id=28,$type='CASH');
		//echo '<pre>';print_r($vchrdata);exit;
		//CHECK DEPARTMENT.......
		if(Session::get('department')==1) { //if active...
			$deptid = Auth::user()->department_id;
			if($deptid!=0)
				$departments = DB::table('department')->where('id',$deptid)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
			else {
				$departments = DB::table('department')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
				$deptid = $departments[0]->id;
			}
			$is_dept = true;
		} else {
			$is_dept = false;
			$departments = [];
			$deptid = '';
		}
		
		return view('body.manualjournal.add')
					->withCurrency($currency)
					->withBanks($banks)
					->withJobs($jobs)
					->withAccount($account)
					->withPrintid($lastid)
					->withPrints($prints)
					->withVouchers($vouchers)
					->withId($id)
					->withVouchertype($vouchertype)
					->withRid($rid)
					->withVchrdata($vchrdata)
					->withIsdept($is_dept)
					->withDepartments($departments)
					->withDeptid($deptid)
					->withData($data);
	}
	
	
	public function save(Request $request) {
	//echo '<pre>';print_r($request->all());exit;
		
		$validator = Validator::make($request->all(), [
            'voucher_no' => 'required|max:255',
			'debit' => 'required|same:credit'
        ]);
		
		if ($validator->fails()) {
            return redirect('manual_journal/add')
                        ->withErrors($validator)
                        ->withInput();
        }
		
		//echo '<pre>';print_r($request->all());exit;
			
		if( $this->manual_journal->create($request->all()) )
			Session::flash('message', 'Manual journal voucher added successfully.');
		else 
			Session::flash('error', 'Manual journal entry validation error! Please try again!');
		
		return redirect('manual_journal/add');
	}
	
	
		
	public function edit($id) { 

		$data = array();
		$currency = $this->currency->activeCurrencyList();
		$banks = $this->bank->activeBankList();
		$jobs = $this->jobmaster->activeJobmasterList();
		//$departments = $this->department->activeDepartmentList();
				
		$jrow = $this->manual_journal->find($id);//echo '<pre>';print_r($jrow);exit;
		$vouchertype = $this->accountsetting->getAccountSettings( $jrow->voucher_type ); //$this->getVid(
		$jerow = $this->manual_journal->findJEdata($id);
	//	echo '<pre>';print_r($jerow);exit;
		$prints = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','MJV')
							->select('report_view_detail.name','report_view_detail.id')
							->get();
		//CHECK DEPARTMENT.......
		if(Session::get('department')==1) { //if active...
			$deptid = Auth::user()->department_id;
			if($deptid!=0)
				$departments = DB::table('department')->where('id',$deptid)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
			else {
				$departments = DB::table('department')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
				$deptid = $departments[0]->id;
			}
			$is_dept = true;
		} else {
			$is_dept = false;
			$departments = [];
			$deptid = '';
		}
		
		return view('body.manualjournal.edit')
					->withJrow($jrow)
					->withCurrency($currency)
					->withBanks($banks)
					->withJobs($jobs)
					->withJerow($jerow)
					->withDepartments($departments)
					->withVouchertype($vouchertype)
					->withIsdept($is_dept)
					->withDepartments($departments)
					->withDeptid($deptid)
					->withPrints($prints)
					->withData($data);
	}
	
	private function getVid($v)
	{
		switch($v)
		{
			case 'MJV':
				return 25;
			break;
			
			case 'PV':
				return 10;
			break;
			
			case 'RV':
				return 9;
			break;
			
			case 'SIN':
				return 6;
			break;
			
			case 'PIN':
				return 5;
			break;
		}
	}
	
	public function update(Request $request,$id)
	{
		$validator = Validator::make($request->all(), [
			'debit' => 'required|same:credit'
        ]);
		
		if ($validator->fails()) {
            return redirect('manual_journal/edit/'.$id)
                        ->withErrors($validator)
                        ->withInput();
        }
			
		if( $this->manual_journal->update($id,Input::all()) )
			Session::flash('message', 'Journal voucher updated successfully.');
		else
			Session::flash('error', 'Something went wrong, Journal voucher failed to edit!');
		
		return redirect('manual_journal');
		
			
		/* $this->journal->update($id, Input::all());
		Session::flash('message', 'Journal voucher updated successfully');
		return redirect('journal'); */
	}
	
	public function getprintGrp($id)
	{
		$voucherhead = 'Manual Journal';
		
		$crrow = $this->manual_journal->find($id);
		
		$invoicerow =$this->manual_journal->findJEdata($id);
		//echo '<pre>';print_r($invoicerow);exit;		
		$words = $this->number_to_word($crrow->debit);
		$arr = explode('.',number_format($crrow->debit,2));
		if(sizeof($arr) >1 ) {
			if($arr[1]!=00) {
				$dec = $this->number_to_word($arr[1]);
				$words .= ' and Fils '.$dec.' Only';
			} else 
				$words .= ' Only';
		} else
			$words .= ' Only'; 
		
		return view('body.manualjournal.printgrp')
					->withVoucherhead($voucherhead)
					->withDetails($crrow)
					->withInvoicerow($invoicerow)
					->withAmtwords($words);
	}
	
	
	private function number_to_word( $num = '' )
	{
		$num    = ( string ) ( ( int ) $num );
	   
		if( ( int ) ( $num ) && ctype_digit( $num ) )
		{
			$words  = array( );
		   
			$num    = str_replace( array( ',' , ' ' ) , '' , trim( $num ) );
		   
			$list1  = array('','one','two','three','four','five','six','seven',
				'eight','nine','ten','eleven','twelve','thirteen','fourteen',
				'fifteen','sixteen','seventeen','eighteen','nineteen');
		   
			$list2  = array('','ten','twenty','thirty','forty','fifty','sixty',
				'seventy','eighty','ninety','hundred');
		   
			$list3  = array('','thousand','million','billion','trillion',
				'quadrillion','quintillion','sextillion','septillion',
				'octillion','nonillion','decillion','undecillion',
				'duodecillion','tredecillion','quattuordecillion',
				'quindecillion','sexdecillion','septendecillion',
				'octodecillion','novemdecillion','vigintillion');
		   
			$num_length = strlen( $num );
			$levels = ( int ) ( ( $num_length + 2 ) / 3 );
			$max_length = $levels * 3;
			$num    = substr( '00'.$num , -$max_length );
			$num_levels = str_split( $num , 3 );
		   
			foreach( $num_levels as $num_part )
			{
				$levels--;
				$hundreds   = ( int ) ( $num_part / 100 );
				$hundreds   = ( $hundreds ? ' ' . $list1[$hundreds] . ' Hundred' . ( $hundreds == 1 ? '' : 's' ) . ' ' : '' );
				$tens       = ( int ) ( $num_part % 100 );
				$singles    = '';
			   
				if( $tens < 20 )
				{
					$tens   = ( $tens ? ' ' . $list1[$tens] . ' ' : '' );
				}
				else
				{
					$tens   = ( int ) ( $tens / 10 );
					$tens   = ' ' . $list2[$tens] . ' ';
					$singles    = ( int ) ( $num_part % 10 );
					$singles    = ' ' . $list1[$singles] . ' ';
				}
				$words[]    = $hundreds . $tens . $singles . ( ( $levels && ( int ) ( $num_part ) ) ? ' ' . $list3[$levels] . ' ' : '' );
			}
		   
			$commas = count( $words );
		   
			if( $commas > 1 )
			{
				$commas = $commas - 1;
			}
		   
			$words  = implode( ', ' , $words );
		   
			//Some Finishing Touch
			//Replacing multiples of spaces with one space
			$words  = trim( str_replace( ' ,' , ',' , $this->trim_all( ucwords( $words ) ) ) , ', ' );
			if( $commas )
			{
				$words  = $this->str_replace_last( ',' , ' and' , $words );
			}
		   
			return $words;
		}
		else if( ! ( ( int ) $num ) )
		{
			return 'Zero';
		}
		return '';
	}
	
	private function trim_all( $str , $what = NULL , $with = ' ' )
	{
		if( $what === NULL )
		{
			//  Character      Decimal      Use
			//  "\0"            0           Null Character
			//  "\t"            9           Tab
			//  "\n"           10           New line
			//  "\x0B"         11           Vertical Tab
			//  "\r"           13           New Line in Mac
			//  " "            32           Space
		   
			$what   = "\\x00-\\x20";    //all white-spaces and control chars
		}
	   
		return trim( preg_replace( "/[".$what."]+/" , $with , $str ) , $what );
	}
	
	private function str_replace_last( $search , $replace , $str ) {
		if( ( $pos = strrpos( $str , $search ) ) !== false ) {
			$search_length  = strlen( $search );
			$str    = substr_replace( $str , $replace , $pos , $search_length );
		}
		return $str;
	}
	
	
	public function destroy($id, $type)
	{
		if( $this->journal->delete($id) ) { 
			if($type=='PI') {
				Session::flash('message', 'Purchase voucher deleted successfully.');
				return redirect('purchase_voucher');
			} if($type=='SI') {
				Session::flash('message', 'Sales voucher deleted successfully.');
				return redirect('sales_voucher');
			} else if($type=='JV') {
				Session::flash('message', 'Journal voucher deleted successfully.');
				return redirect('journal');
			}
		} else {
			if($type=='PI') {
				Session::flash('error', 'Something went wrong, Purchase voucher failed to delete!');
				return redirect('purchase_voucher');
			} if($type=='SI') {
				Session::flash('error', 'Something went wrong, Sales voucher failed to delete!');
				return redirect('sales_voucher');
			} else if($type=='JV') {
				Session::flash('error', 'Something went wrong, Journal voucher failed to delete!');
				return redirect('journal');
			}
		}
	}
	
	public function getVoucherJV($id,$type) {
		
		 $row = $this->accountsetting->getDrVoucherByID2($id);//return $row;//print_r($row);
		// echo '<pre>';print_r($row);exit;
		 if($row) {
			 if($row->voucher_no != '' || $row->voucher_no != null) {
				 if($row->is_prefix==0)
					 $voucher = $row->voucher_no;
				 else {
					 $no = (int)$row->voucher_no;
					 $voucher = $row->prefix.''.$no;
				 }
			 }
			 
			 if($type=='CASH') {
				 $master_name = $row->cashaccount;
				 $id = $row->cash_account_id;
			 } else if($type=='BANK') {
				 $master_name = $row->bankaccount;
				 $id = $row->bank_account_id;
			 } else if($type=='PDCR') {
				 $master_name = $row->pdcaccount;
				 $id = $row->pdc_account_id;
			} else if($type=='PDCI') {
				 $master_name = $row->pdcaccount;
				 $id = $row->pdc_account_id;
			 }else if($type=='MJV') {
				$master_name = $row->pdcaccount;
				$id = $row->pdc_account_id;
			}
			
			 return $result = array('voucher_no' => $voucher,
									'account_name' => $master_name, 
									'id' => $id);
		 } else
			 return null;
		
	}
	
	public function getVoucher($id) {
		
		 $row = $this->accountsetting->getDrVoucherByID($id);
		 if($row->voucher_no != '' || $row->voucher_no != null) {
			 if($row->is_prefix==0)
				 $voucher = $row->voucher_no;
			 else {
				 $no = (int)$row->voucher_no;
				 $voucher = $row->prefix.''.$no;
			 }
			 echo $voucher;
		 }
		 
		/*  $row = $this->voucherno->getVoucherNo($id);
		 if($row['no'] != '' || $row['no'] != null) {
			echo $no = $row['no']+1;
		 } else if($row['no'] == 0) {
			echo $no = 1;
		 } */

	}
	
	public function getVoucherType($id) {
		
		return $row = $this->accountsetting->getAccountSettings($id);
		 
	}
	
	public function getVoucherprint()
	{                
		$type = Input::get('voucher_typeprint');
		//echo '<pre>';print_r($type);exit;
		$voucher_no = Input::get('voucherprnt_no');
		if(($type !=0) &&  (!empty($voucher_no)))
		{
		$journals = $this->manual_journal->journalListprit($type,$voucher_no);
		//echo '<pre>';print_r($journals);,,,PV
		if($type ==16 )
		$prints = DB::table('report_view_detail')
														->join('report_view','report_view.id','=','report_view_detail.report_view_id')
														->where('report_view.code','JV')
														->select('report_view_detail.name','report_view_detail.id')
														->get();
		elseif($type ==5)
		 $prints = DB::table('report_view_detail')
		                       ->join('report_view','report_view.id','=','report_view_detail.report_view_id')
		                        ->where('report_view.code','PVR')
		                    ->select('report_view_detail.name','report_view_detail.id')
		                        ->get();
		elseif($type ==6)
			$prints = DB::table('report_view_detail')
													  ->join('report_view','report_view.id','=','report_view_detail.report_view_id')
													   ->where('report_view.code','SVR')
												        ->select('report_view_detail.name','report_view_detail.id')
													   ->get();
		elseif($type ==9)
			$prints = DB::table('report_view_detail')
																			 ->join('report_view','report_view.id','=','report_view_detail.report_view_id')
																			  ->where('report_view.code','RV')
																			   ->select('report_view_detail.name','report_view_detail.id')
																			  ->get();
		elseif($type ==10)
				$prints = DB::table('report_view_detail')->join('report_view','report_view.id','=','report_view_detail.report_view_id')
														->where('report_view.code','PV')
													->select('report_view_detail.name','report_view_detail.id')
																			->get();
							   
		
		$id = $journals[0]->id; 
		//echo '<pre>';print_r($id);
		$rid = $prints[0]->id;
		//echo '<pre>';print_r($rid);exit;
           return redirect('manual_journal/print/'.$id.'/'.$rid);
		//return 'true';
	}
	else
	{
        $journal = $this->manual_journal->getLastId(); 
		$prints = DB::table('report_view_detail')
														->join('report_view','report_view.id','=','report_view_detail.report_view_id')
														//->where('report_view.code',$type)
														->select('report_view_detail.name','report_view_detail.id')
														->get(); 
		
		$id = $journal->id;
		
		$rid = $prints[0]->id;
	
           return redirect('manual_journal/print/'.$id.'/'.$rid);   

	}
	}
	
	public function checkVchrNo() {

		$check = $this->manual_journal->check_voucher_no(Input::get('voucher_no'), Input::get('vtype'), Input::get('id'));
	    echo '<pre>';print_r($check);exit;	
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
	public function getPrint($id,$rid=null)
	{ 
		
        $viewfile = DB::table('report_view_detail')->where('id', $rid)->select('print_name')->first(); 
		//echo '<pre>';print_r($viewfile);exit;	
		if($viewfile->print_name=='') {
			$fc='';
			$attributes['document_id'] = $id; //echo "892 : ".$this->number_to_word(12495);exit;
			$attributes['is_fc'] = ($fc)?1:'';
			$titles = ['main_head' => 'Payment Voucher','subhead' => 'Payment Voucher'];
			
			$view = 'printgrp';

			$voucherhead = 'Manual Journal Voucher';
			$jvrow = $this->journal->find($id); 
		     $jerow = $this->journal->findJEdata($id);
		
					
			$words = $this->number_to_word($crrow->debit);
			$arr = explode('.',number_format($crrow->debit,2));
			if(sizeof($arr) >1 ) {
				if($arr[1]!=00) {
					$dec = $this->number_to_word($arr[1]);
					$words .= ' and Fils '.$dec.' Only';
				} else 
					$words .= ' Only';
			} else
				$words .= ' Only'; 
			
			return view('body.manualjournal.'.$view)
						->withVoucherhead($voucherhead)
						->withDetails($jvrow)
					->withJerow($jerow)
						->withAmtwords($words);


		} else {
					
			$path = app_path() . '/stimulsoft/helper.php';
			
			if(env('STIMULSOFT_VER')==2)
			        return view('body.reports')->withPath($path)->withView($viewfile->print_name);
			   else
			        return view('body.manualjournal.viewer')->withPath($path)->withView($viewfile->print_name);
			        
			
		}
		
	}

	public function getPrintold($id)
	{
		$voucherhead = 'Journal Voucher';
		$jvrow = $this->journal->find($id); 
		$jerow = $this->journal->findJEdata($id); //echo '<pre>';print_r($jerow);exit;

		return view('body.journal.print')
					->withVoucherhead($voucherhead)
					->withDetails($jvrow)
					->withJerow($jerow);
	}
	public function setTransactions($type,$id,$n) {
		
		$banks = $this->bank->activeBankList();
		$jobs = $this->jobmaster->activeJobmasterList();
		$acdata = DB::table('account_master')->where('id',$id)->select('id','master_name','vat_assign','category','vat_percentage')->first();
		//CHECK DEPARTMENT.......
		if(Session::get('department')==1) { //if active...
			$deptid = Auth::user()->department_id;
			if($deptid!=0)
				$departments = DB::table('department')->where('id',$deptid)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
			else {
				$departments = DB::table('department')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
				$deptid = $departments[0]->id;
			}
			$is_dept = true;
		} else {
			$is_dept = false;
			$departments = [];
			$deptid = '';
		}
		
		return view('body.manualjournal.transactions')
							->withBanks($banks)
							->withJobs($jobs)
							->withIsdept($is_dept)
							->withDepartments($departments)
							->withAcdata($acdata)
							->withNum($n)
							->withType($type);
							//->withDescr($descr);
	}
	
}
