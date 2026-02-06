<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Bank\BankInterface;
use App\Repositories\Currency\CurrencyInterface;
use App\Repositories\VoucherNo\VoucherNoInterface;
use App\Repositories\Jobmaster\JobmasterInterface;
use App\Repositories\Department\DepartmentInterface;
use App\Repositories\PettyCash\PettyCashInterface;
use App\Repositories\AccountSetting\AccountSettingInterface;

use App\Http\Requests;
use Session;
use Response;
use Validator;
use Auth;
use DB;
use App;

class PettyCashController extends Controller
{
    protected $bank;
	protected $currency;
	protected $voucherno;
	protected $jobmaster;
	protected $department;
	protected $pettycash;
	protected $accountsetting;
	
	public function __construct(AccountSettingInterface $accountsetting, PettyCashInterface $pettycash, BankInterface $bank, CurrencyInterface $currency, VoucherNoInterface $voucherno, JobmasterInterface $jobmaster, DepartmentInterface $department) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->middleware('auth');
		$this->bank = $bank;
		$this->currency = $currency;
		$this->voucherno = $voucherno;
		$this->jobmaster = $jobmaster;
		$this->department = $department;
		$this->pettycash = $pettycash;
		$this->accountsetting = $accountsetting;
	}
	
	public function index() {
		$data = array();
		$pettycashs =[]; //$this->pettycash->pettycashListOld();//echo '<pre>';print_r($pettycashs);exit;
		$prints = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','PC')
							->select('report_view_detail.name','report_view_detail.id')
							->get();
							//echo '<pre>';print_r($pettycashs);exit;
		return view('body.pettycash.index')
					->withPettycashs($pettycashs)
					->withPrints($prints)
					->withData($data);
					
	}
	
		public function ajaxPaging(Request $request)
	{
		$columns = array( 
                            0 =>'petty_cash.id', 
                            1 =>'voucher_no',
                            2=> 'voucher_date',
                            3=>'description',
							4=>'reference',
                            5=> 'amount',
                           
						
                        );
						
		$totalData = $this->pettycash->pettycashListCount();
            
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = 'petty_cash.id';//$columns[$request->input('order.0.column')];
        $dir = 'desc';//$request->input('order.0.dir');
		$search = (empty($request->input('search.value')))?null:$request->input('search.value');
        
		$invoices = $this->pettycash->pettycashList('get', $start, $limit, $order, $dir, $search);
		//echo '<pre>';print_r($invoices);exit;
		$prints = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','PC')
							->select('report_view_detail.name','report_view_detail.id')
							->get();
		if($search)
			$totalFiltered =  $this->pettycash->pettycashList('count', $start, $limit, $order, $dir, $search);
		
        $data = array();
        if(!empty($invoices))
        {
           
			foreach ($invoices as $row)
            {
                $edit =  '"'.url('pettycash/edit-pc/'.$row->id).'"';
                $delete =  'funDelete("'.$row->id.'")';
				$print = url('pettycash/print/'.$row->id);
		
                $nestedData['id'] = $row->id;
                $nestedData['voucher_no'] = $row->voucher_no;
				$nestedData['voucher_date'] = date('d-m-Y', strtotime($row->voucher_date));
				$nestedData['amount'] = number_format($row->credit,2);
				$nestedData['description'] = $row->description;
				$nestedData['reference'] = $row->reference;
				
				
					$nestedData['edit'] = "<p><button class='btn btn-primary btn-xs' onClick='location.href={$edit}'>
													<span class='glyphicon glyphicon-pencil'></span></button></p>";
													
					$nestedData['delete'] = "<button class='btn btn-danger btn-xs delete' onClick='{$delete}'>
												<span class='glyphicon glyphicon-trash'></span>";
				
				
				$opts = '';					
				foreach($prints as $doc) {
					$opts .= "<li role='presentation'><a href='{$print}/".$doc->id."' target='_blank' role='menuitem'>".$doc->name."</a></li>";
				}
				
				

			$nestedData['print'] = "<div class='btn-group drop_btn' role='group'>
				                              <button type='button' class='btn btn-primary btn-xs dropdown-toggle m-r-50'
						                    id='exampleIconDropdown1' data-toggle='dropdown' aria-expanded='false'>
					                      <i class='fa fa-fw fa-print' aria-hidden='true'></i><span class='caret'></span>
				                     </button>
				                         <ul style='min-width:100px !important;' class='dropdown-menu' aria-labelledby='exampleIconDropdown1' role='menu'>
				                           	 ".$opts."
				                               </ul></div>";
					
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
	
	public function add() {

		$data = array();
		$currency = $this->currency->activeCurrencyList();
		$banks = $this->bank->activeBankList();
		$jobs = $this->jobmaster->activeJobmasterList();
		$departments = $this->department->activeDepartmentList();
		$vouchers = $this->accountsetting->getAccountSettingsById($vid=20);
		$account = $this->accountsetting->getExpenseAccount();
		$lastid = $this->pettycash->getLastId();
		$vchrdata = $this->getVoucher($id=20,$type='CASH'); //
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
		
		$prints = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','PC')
							->select('report_view_detail.name','report_view_detail.id')
							->get();
							echo '<pre>';print_r($prints);exit;
		
		return view('body.pettycash.add')
					->withCurrency($currency)
					->withBanks($banks)
					->withJobs($jobs)
					->withDepartments($departments)					
					->withVouchers($vouchers)
					->withAccount($account)
					->withPrintid($lastid)
					->withVchrdata($vchrdata)
					->withIsdept($is_dept)
					->withDepartments($departments)
					->withDeptid($deptid)
					->withSettings($this->acsettings)
					->withPrints($prints)
					->withData($data);
	}
	
	public function save(Request $request) {
		
		$validator = Validator::make($request->all(), [
			'debit' => 'required|same:credit'
        ]);
		
		if ($validator->fails()) {
            return redirect('pettycash/add')
                        ->withErrors($validator)
                        ->withInput();
        }
		
		if( $this->pettycash->create($request->all()) ) {
			Session::flash('message', 'Petty Cash voucher added successfully.');
		} else
			Session::flash('error', 'Petty cash entry validation error! Please try again.');
			
		
		if($request->get('isquick==0'))
			return redirect('pettycash/add');
		else
			return redirect('pettycash/add-pc');
			
		
	}
	
	public function edit($id) { 

		$data = array();
		$currency = $this->currency->activeCurrencyList();
		$banks = $this->bank->activeBankList();
		$jobs = $this->jobmaster->activeJobmasterList();
		$departments = $this->department->activeDepartmentList();
				
		$prow = $this->pettycash->find($id);
		$vouchertype = $this->accountsetting->getAccountSettingsById($vid=20);
		$perow = $this->pettycash->findPEdata($id);
		$data_cr = DB::table('petty_cash_entry')
					->join('account_master','account_master.id','=','petty_cash_entry.account_id')
					->where('petty_cash_entry.petty_cash_id',$id)->where('petty_cash_entry.status',1)
					->where('petty_cash_entry.deleted_at','0000-00-00 00:00:00')->where('petty_cash_entry.entry_type','Cr')
					->select('account_master.master_name','petty_cash_entry.id','petty_cash_entry.account_id','petty_cash_entry.description',
					'petty_cash_entry.reference','petty_cash_entry.amount')->first();
		//echo '<pre>';print_r($data_cr);exit;
		$prints = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','PC')
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

		$cashac = DB::table('account_master')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->where('category','CASH')->select('id','master_name','category')->first();
		$cash = DB::table('account_master')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->where('category','CASH')->select('id','account_id','master_name')->get();
		$banks = $this->bank->activeBankList();
		
		return view('body.pettycash.edit-pc')
					->withProw($prow)
					->withCurrency($currency)
					->withBanks($banks)
					->withJobs($jobs)
					->withPerow($perow)
					->withDepartments($departments)
					->withVouchertype($vouchertype)
					->withIsdept($is_dept)
					->withDepartments($departments)
					->withDeptid($deptid)
					->withSettings($this->acsettings)
					->withCashac($cashac)
					->withCash($cash)
					->withBanks($banks)
					->withDatacr($data_cr)
					->withPrints($prints)
					->withData($data);
	}
	
	
	public function update(Request $request, $id)
	{
		$validator = Validator::make($request->all(), [
			'debit' => 'required|same:credit'
        ]);
		
		if ($validator->fails()) {
            return redirect('pettycash/edit/'.$id)
                        ->withErrors($validator)
                        ->withInput();
        }
		//echo '<pre>';print_r($request->all());exit;
		if( $this->pettycash->update($id, $request->all()) )
			Session::flash('message', 'Petty Cash voucher updated successfully');
		else
			Session::flash('error', 'Petty cash entry validation error! Please try again.');
		
		return redirect('pettycash');
	}
	
	public function destroy($id)
	{
		if( $this->pettycash->delete($id) )
			Session::flash('message', 'PettyCash voucher deleted successfully.');
		else
			Session::flash('error', 'Something went wrong, Petty cash voucher failed to delete!');
		
		return redirect('pettycash');
	}
	
	public function getVoucher($id,$type) {
		
		 $row = $this->accountsetting->getDrVoucherByID2($id);//return $row;//print_r($row);
		 //echo '<pre>';print_r($row);exit;
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
			 }
			 
			 return $result = array('voucher_no' => $voucher,
									'account_name' => $master_name, 
									'id' => $id);
		 } else
			 return null;
		
	}
	
	public function getVoucher2($id) {
		
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

	}
	
	public function getVoucherType($id) {
		
		return $row = $this->accountsetting->getAccountSettings($id);
		 
	}
	
	public function checkVchrNo(Request $request) {

		$check = $this->pettycash->check_voucher_no($request->get('voucher_no'), $request->get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
		public function getGrpPrint($id)
	{
	    $voucherhead = 'Petty Cash Voucher';
				$crrow = $this->pettycash->find($id); 
			$invoicerow = $this->pettycash->findPEdata($id);
			$data_cr = DB::table('petty_cash_entry')
					->join('account_master','account_master.id','=','petty_cash_entry.account_id')
					->where('petty_cash_entry.petty_cash_id',$id)->where('petty_cash_entry.status',1)
					->where('petty_cash_entry.deleted_at','0000-00-00 00:00:00')->where('petty_cash_entry.entry_type','Cr')
					->select('account_master.master_name','petty_cash_entry.id','petty_cash_entry.account_id','petty_cash_entry.description',
					'petty_cash_entry.reference','petty_cash_entry.amount')->first();
					//	echo '<pre>';print_r($data_cr);exit;
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
				
				return view('body.pettycash.printgrp')
							->withVoucherhead($voucherhead)
							->withDetails($crrow)
						->withInvoicerow($invoicerow)
						->withCrdata($data_cr)
							->withAmtwords($words);


	    
	}
	public function getPrint($id,$rid=null)
	{ 

		if(!$rid) {
			$voucherhead = 'Petty Cash Voucher';
			$crrow = $this->pettycash->find($id); 
			$invoicerow = $this->pettycash->findPEdata($id); //echo '<pre>';print_r($invoicerow);exit;
					
			return view('body.pettycash.print')
						->withVoucherhead($voucherhead)
						->withDetails($crrow)
						->withInvoicerow($invoicerow);
		} else {
			$viewfile = DB::table('report_view_detail')->where('id', $rid)->select('print_name')->first(); 
				
			if($viewfile->print_name=='') {
				$fc='';
				$attributes['document_id'] = $id; //echo "892 : ".$this->number_to_word(12495);exit;
				$attributes['is_fc'] = ($fc)?1:'';
				$titles = ['main_head' => 'Payment Voucher','subhead' => 'Payment Voucher'];
				
				$view = 'printgrp';

				$voucherhead = 'Petty Cash Voucher';
				$crrow = $this->pettycash->find($id); 
			$invoicerow = $this->pettycash->findPEdata($id);
			
						
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
				
				return view('body.pettycash.'.$view)
							->withVoucherhead($voucherhead)
							->withDetails($crrow)
						->withInvoicerow($invoicerow)
							->withAmtwords($words);


			} else {
						
				$path = app_path() . '/stimulsoft/helper.php';
				
				if(env('STIMULSOFT_VER')==2)
			        return view('body.reports')->withPath($path)->withView($viewfile->print_name);
			   else
			        	return view('body.pettycash.viewer')->withPath($path)->withView($viewfile->print_name);
			        
			
			}
		}
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
		
		return view('body.pettycash.transactions')
							->withBanks($banks)
							->withJobs($jobs)
							->withIsdept($is_dept)
							->withDepartments($departments)
							->withAcdata($acdata)
							->withNum($n)
							->withType($type);
							//->withDescr($descr);
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
	
	public function getPrintold($id)
	{
		$voucherhead = 'Petty Cash Voucher';
		$crrow = $this->pettycash->find($id); 
		$invoicerow = $this->pettycash->findPEdata($id); //echo '<pre>';print_r($invoicerow);exit;
				
		return view('body.pettycash.print')
					->withVoucherhead($voucherhead)
					->withDetails($crrow)
					->withInvoicerow($invoicerow);
	}

	public function quickAdd() { 

		$data = array();
		$currency = $this->currency->activeCurrencyList();
		$jobs = $this->jobmaster->activeJobmasterList();
		$departments = $this->department->activeDepartmentList();
		$vouchers = $this->accountsetting->getAccountSettingsById($vid=20);
		$account = $this->accountsetting->getExpenseAccount();
		$lastid = $this->pettycash->getLastId();
		$vchrdata = $this->getVoucher($id=20,$type='CASH'); //
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

		
		$cashac = null;
		$cashac = DB::table('account_master')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->where('category','CASH')->select('id','master_name','category')->first();
		$banks = $this->bank->activeBankList();
		return view('body.pettycash.quickadd')
					->withCurrency($currency)
					->withJobs($jobs)
					->withDepartments($departments)					
					->withVouchers($vouchers)
					->withAccount($account)
					->withPrintid($lastid)
					->withVchrdata($vchrdata)
					->withIsdept($is_dept)
					->withDepartments($departments)
					->withDeptid($deptid)
					->withSettings($this->acsettings)
					->withCashac($cashac)
					->withBanks($banks)
					->withData($data);

	}


	public function addPC() { 

		$data = array();
		$currency = $this->currency->activeCurrencyList();
		$jobs = $this->jobmaster->activeJobmasterList();
		$departments = $this->department->activeDepartmentList();
		$vouchers = $this->accountsetting->getAccountSettingsById($vid=20);
		$account = $this->accountsetting->getExpenseAccount();
		$lastid = $this->pettycash->getLastId();
		$vchrdata = $this->getVoucher($id=20,$type='CASH'); //
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

		
		$cashac = DB::table('account_master')->where('status',1)->whereNull('deleted_at')->where('category','CASH')->select('id','master_name','category')->first();
		$cash = DB::table('account_master')->where('status',1)->whereNull('deleted_at')->where('category','CASH')->select('id','account_id','master_name')->get();
		$banks = $this->bank->activeBankList();
		
		$prints = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','PC')
							->select('report_view_detail.name','report_view_detail.id')
							->get();
						//	echo '<pre>';print_r($prints);exit;
							
		return view('body.pettycash.add-pc')
					->withCurrency($currency)
					->withJobs($jobs)
					->withDepartments($departments)					
					->withVouchers($vouchers)
					->withAccount($account)
					->withPrintid($lastid)
					->withVchrdata($vchrdata)
					->withIsdept($is_dept)
					->withDepartments($departments)
					->withDeptid($deptid)
					->withSettings($this->acsettings)
					->withCashac($cashac)
					->withCash($cash)
					->withBanks($banks)
					->withPrints($prints)
					->withData($data);

	}

	
	}

