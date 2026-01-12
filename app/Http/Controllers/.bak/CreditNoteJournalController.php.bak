<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Bank\BankInterface;
use App\Repositories\Currency\CurrencyInterface;
use App\Repositories\VoucherNo\VoucherNoInterface;
use App\Repositories\Jobmaster\JobmasterInterface;
use App\Repositories\Department\DepartmentInterface;
use App\Repositories\CreditNoteJournal\CreditNoteJournalInterface;
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

class CreditNoteJournalController extends Controller
{
    protected $bank;
	protected $currency;
	protected $voucherno;
	protected $jobmaster;
	protected $department;
	protected $journal;
	protected $accountsetting;
	protected $receipt_voucher;
	protected $payment_voucher;
	
	public function __construct(AccountSettingInterface $accountsetting, CreditNoteJournalInterface $journal, ReceiptVoucherInterface $receipt_voucher, PaymentVoucherInterface $payment_voucher, BankInterface $bank, CurrencyInterface $currency, VoucherNoInterface $voucherno, JobmasterInterface $jobmaster, DepartmentInterface $department) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->middleware('auth');
		$this->bank = $bank;
		$this->currency = $currency;
		$this->voucherno = $voucherno;
		$this->jobmaster = $jobmaster;
		$this->department = $department;
		$this->journal = $journal;
		$this->accountsetting = $accountsetting;
		$this->receipt_voucher = $receipt_voucher;
		$this->payment_voucher = $payment_voucher;
	}
	
	public function index() {
		$data = array();
		$journals = [];////
		//$journals=$this->journal->all();
		//echo '<pre>';print_r($journals);exit;
		/*$prints = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','JV')
							->select('report_view_detail.name','report_view_detail.id')
							->get();*///
		return view('body.creditnotejv.index')
					->withJournals($journals)
					//->withPrints($prints)
					->withData($data);
	}
	
	public function index9() {
		$data = array();
		$journals = [];//$this->journal->journalList();//
		
		/* $prints = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','JV')
							->select('report_view_detail.name','report_view_detail.id')
							->get(); *///echo '<pre>';print_r($prints);exit;
		return view('body.creditnotejv.index')
					->withJournals($journals)
					//->withPrints($prints)
					->withData($data);
	}
	
	public function ajaxPaging(Request $request)
	{
		$columns = array( 
                            0 =>'creditnote_jv.id', 
                            1 =>'voucher_no',
							2 =>'voucher_type',
                            3=> 'voucher_date',
                            4=> 'description',
                            6=> 'amount'
                        );
						
		$totalData = $this->journal->journalListCount();
            
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = 'creditnote_jv.id';//$columns[$request->input('order.0.column')];
        $dir = 'desc';//$request->input('order.0.dir');
		$search = (empty($request->input('search.value')))?null:$request->input('search.value');
        
        if($search)
            $dir = 'asc';
        
       // $totalData =  $this->journal->journalList('count', $start, $limit, $order, $dir, $search);
		//$totalFiltered = $totalData; 
		
		$invoices = $this->journal->journalList('get', $start, $limit, $order, $dir, $search);
		
		if($search)
			$totalFiltered =  $this->journal->journalList('count', $start, $limit, $order, $dir, $search);
	
		$prints = DB::table('report_view_detail')
			->join('report_view','report_view.id','=','report_view_detail.report_view_id')
			->where('report_view.code','CNE')
			->select('report_view_detail.name','report_view_detail.id')
			->get();
			
        $data = array();
        if(!empty($invoices))
        {
			foreach ($invoices as $row)
            {
                $edit =  '"'.url('creditnotejournal/edit/'.$row->id).'"';
                $delete =  'funDelete("'.$row->id.'")';
				$print = url('creditnotejournal/print/'.$row->id.'/'.$prints[0]->id);
                $nestedData['id'] = $row->id;
                $nestedData['voucher_no'] = $row->voucher_no;
				$nestedData['voucher_type'] = ($row->voucher_type==9)?'CASH':$row->voucher_type;
				$nestedData['voucher_date'] = date('d-m-Y', strtotime($row->voucher_date));
				$nestedData['description'] = $row->description;
				$nestedData['amount'] = $row->credit;
				
				$nestedData['edit'] = "<p><button class='btn btn-primary btn-xs' onClick='location.href={$edit}'>
												<span class='glyphicon glyphicon-pencil'></span></button></p>";
												
				$nestedData['delete'] = "<button class='btn btn-danger btn-xs delete' onClick='{$delete}'>
											<span class='glyphicon glyphicon-trash'></span>";
				
				$nestedData['print'] = "<p><a href='{$print}' target='_blank'  role='menuitem' class='btn btn-primary btn-xs'><span class='fa fa-fw fa-print'></span></a></p>";
				
				/* "<div class='btn-group drop_btn' role='group'>
									<button type='button' class='btn btn-primary btn-xs dropdown-toggle m-r-50'
											id='exampleIconDropdown1' data-toggle='dropdown' aria-expanded='false'>
										<i class='fa fa-fw fa-print' aria-hidden='true'></i><span class='caret'></span>
									</button>
									<ul style='min-width:100px !important;' class='dropdown-menu' aria-labelledby='exampleIconDropdown1' role='menu'>
										".$opts."
									</ul>
								</div>"; */
					
				
						
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
	
	public function add($id=null,$rid=null,$vouchertype=null) {
			
		//echo '<pre>';print_r($vouchertype);exit;
		$data = array();
		$currency = $this->currency->activeCurrencyList();
		$banks = $this->bank->activeBankList();
		$jobs = $this->jobmaster->activeJobmasterList();
		//$departments = $this->department->activeDepartmentList();
		$account = $this->accountsetting->getExpenseAccount();
		$lastid = $this->journal->getLastId();
		$prints = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','CNE')
							->select('report_view_detail.name','report_view_detail.id')
							->get();
		$vouchers = $this->accountsetting->getAccountSettingsById($vid=7); //
		$vchrdata = $this->getVoucherJV($id=7,$type='CASH');
		//echo '<pre>';print_r($vouchers);exit;
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
		
		return view('body.creditnotejv.add')
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
					->withSettings($this->acsettings)
					->withData($data);
	}
	public function save(Request $request) {
		//echo '<pre>';print_r(Input::all());exit;
		
			//echo '<pre>';print_r($idarr);exit;
		$validator = Validator::make($request->all(), [
            'voucher_no' => 'required|max:255',
			//'debit' => 'required|same:credit'
        ]);
		
		if ($validator->fails()) {
            return redirect('creditnotejournal/add')
                        ->withErrors($validator)
                        ->withInput();
        }
		
		 if(Input::get('voucher_type')==7) {
			
			if( $this->journal->create(Input::all()) )
				Session::flash('message', 'Credit Note JV added successfully.');
			else 
				Session::flash('error', 'Something went wrong, Purchase voucher failed to add!');
			
			return redirect('creditnotejournal/add'); //return redirect('purchase_voucher');
			
		} 
	}
	
	public function saveold(Request $request) {    // 2021 Sep20
		//echo '<pre>';print_r(Input::all());exit;
		
		$validator = Validator::make($request->all(), [
            'voucher_no' => 'required|max:255',
			'debit' => 'required|same:credit'
        ]);
		
		if ($validator->fails()) {
            return redirect('creditnotejournal/add')
                        ->withErrors($validator)
                        ->withInput();
        }
		
		if(Input::get('voucher_type')==9) {
			
			if( $this->receipt_voucher->create(Input::all()))
			{
				Session::flash('message', 'Customer receipt added successfully.');
				$journals = $this->receipt_voucher->getLastId();
				$prints = DB::table('report_view_detail')
		             	->join('report_view','report_view.id','=','report_view_detail.report_view_id')
		               	->where('report_view.code','RV')
		                 	->select('report_view_detail.name','report_view_detail.id')
			             ->get();
				$id = $journals->id;
				$rid = $prints[0]->id;
				$vouchertype =  Input::get('voucher_type');
                return redirect('creditnotejournal/add/'.$id.'/'.$rid.'/'.$vouchertype);
			}
			else 
				Session::flash('error', 'Something went wrong, Customer receipt failed to add!');
			
			return redirect('creditnotejournal/add'); //return redirect('customer_receipt');
			
		} else if(Input::get('voucher_type')==10) {
			
			if( $this->payment_voucher->create(Input::all()) )

			{
				Session::flash('message', 'Supplier payment added successfully.');
				$journals = $this->payment_voucher->getLastId();
				$prints = DB::table('report_view_detail')
		             	->join('report_view','report_view.id','=','report_view_detail.report_view_id')
		               	->where('report_view.code','PV')
		                 	->select('report_view_detail.name','report_view_detail.id')
			             ->get();
				$id = $journals->id;
				$rid = $prints[0]->id;
				$vouchertype =  Input::get('voucher_type');
                return redirect('creditnotejournal/add/'.$id.'/'.$rid.'/'.$vouchertype);
				
			}
			else 
				Session::flash('error', 'Something went wrong, Supplier payment failed to add!');
			
			return redirect('creditnotejournal/add'); //return redirect('supplier_payment');
			
		} else if(Input::get('voucher_type')==5) {
			
			if( $this->journal->create(Input::all()) )
			{ 
				Session::flash('message', 'Purchase voucher added successfully.');
				$journals = $this->journal->journalList('PIN');
				
				
			    $prints = DB::table('report_view_detail')
				->join('report_view','report_view.id','=','report_view_detail.report_view_id')
				->where('report_view.code','PVR')
				->select('report_view_detail.name','report_view_detail.id')
				->get();
			$id = $journals[0]->id;
			
			$rid = $prints[0]->id;
			$vouchertype =  Input::get('voucher_type');
			return redirect('creditnotejournal/add/'.$id.'/'.$rid.'/'.$vouchertype); 
		  
			
			}
			else
			{ 
				Session::flash('error', 'Something went wrong, Purchase voucher failed to add!');
				
			     return redirect('creditnotejournal/add'); 
			}
				//return redirect('purchase_voucher');
			
		} else if(Input::get('voucher_type')==6) {
			
			if( $this->journal->create(Input::all()) )
			{
				
				
				Session::flash('message', 'Sales voucher added successfully.');
				$journals = $this->journal->journalList('SIN');
				
				
			$prints = DB::table('report_view_detail')
			->join('report_view','report_view.id','=','report_view_detail.report_view_id')
			->where('report_view.code','SVR')
			->select('report_view_detail.name','report_view_detail.id')
			->get();
		    $id = $journals[0]->id;
		
		    $rid = $prints[0]->id;
		    $vouchertype =  Input::get('voucher_type');
		    return redirect('creditnotejournal/add/'.$id.'/'.$rid.'/'.$vouchertype); 
				}
			else 
				Session::flash('error', 'Something went wrong, Sales voucher failed to add!');
			
			return redirect('creditnotejournal/add');//return redirect('sales_voucher');
			
		} else {
			
			if( $this->journal->create(Input::all()) )
				Session::flash('message', 'Journal voucher added successfully.');
			else 
				Session::flash('error', 'Something went wrong, Journal voucher failed to add!');
			return redirect('creditnotejournal/add');
		}
	}
	
	/* public function save() {
		try { //echo '<pre>';print_r(Input::all());exit;
			if(Input::get('voucher_type')==9) {
				$this->receipt_voucher->create(Input::all());
				Session::flash('message', 'Customer receipt added successfully.');
				return redirect('customer_receipt');
			} else if(Input::get('voucher_type')==10) {
				$this->payment_voucher->create(Input::all());
				Session::flash('message', 'Supplier payment added successfully.');
				return redirect('supplier_payment');
			} else if(Input::get('voucher_type')==5) {
				$this->journal->create(Input::all());
				Session::flash('message', 'Purchase voucher added successfully.');
				return redirect('purchase_voucher');
			} else if(Input::get('voucher_type')==6) {
				$this->journal->create(Input::all());
				Session::flash('message', 'Sales voucher added successfully.');
				return redirect('sales_voucher');
			} else {
				$this->journal->create(Input::all());//exit;
				Session::flash('message', 'Journal voucher added successfully.');
				return redirect('journal');
			}
		} catch(ValidationException $e) { 
			return Redirect::to('journal/add')->withErrors($e->getErrors());
		}
	} */
	
	public function edit($id) { 

		$data = array();
		$currency = $this->currency->activeCurrencyList();
		$banks = $this->bank->activeBankList();
		$jobs = $this->jobmaster->activeJobmasterList();
		//$departments = $this->department->activeDepartmentList();
				
		$jrow = $this->journal->find($id);
		$vouchertype = $this->accountsetting->getAccountSettings( $this->getVid($jrow->voucher_type) );
		$jerow = $this->journal->findJEdata($id);
		//echo '<pre>';print_r($vouchertype);exit;
		
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
		
		return view('body.creditnotejv.edit')
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
					->withData($data);
	}
	
	private function getVid($v)
	{
		switch($v)
		{
			case 'CNJV':
				return 7;
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
            return redirect('creditnotejournal/edit/'.$id)
                        ->withErrors($validator)
                        ->withInput();
        }
		
		if(Input::get('voucher_type')==5) {
			
			if( $this->journal->update($id, Input::all()) )
				Session::flash('message', 'Purchase voucher updated successfully.');
			else
				Session::flash('error', 'Something went wrong, Purchase voucher failed to edit!');
			
			return redirect('purchase_voucher');
		} else if(Input::get('voucher_type')==6) {
			
			if( $this->journal->update($id, Input::all()) )
				Session::flash('message', 'Sales voucher updated successfully.');
			else
				Session::flash('error', 'Something went wrong, Sales voucher failed to edit!');
			
			return redirect('sales_voucher');
		} else {
			
			if( $this->journal->update($id,Input::all()) )
				Session::flash('message', 'Journal voucher updated successfully.');
			else
				Session::flash('error', 'Something went wrong, Journal voucher failed to edit!');
			
			return redirect('creditnotejournal');
		}
			
		/* $this->journal->update($id, Input::all());
		Session::flash('message', 'Journal voucher updated successfully');
		return redirect('journal'); */
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
			 }
			 
			 return $result = array('voucher_no' => $voucher,
									'account_name' => $master_name, 
									'vno' => $row->voucher_no, //MY23
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
		$journals = $this->journal->journalListprit($type,$voucher_no);
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
           return redirect('creditnotejournal/print/'.$id.'/'.$rid);
		//return 'true';
	}
	else
	{
        $journal = $this->journal->getLastId(); 
		$prints = DB::table('report_view_detail')
														->join('report_view','report_view.id','=','report_view_detail.report_view_id')
														//->where('report_view.code',$type)
														->select('report_view_detail.name','report_view_detail.id')
														->get(); 
		
		$id = $journal->id;
		
		$rid = $prints[0]->id;
	
           return redirect('creditnotejournal/print/'.$id.'/'.$rid);   

	}
	}
	
	public function checkVchrNo() {

		$check = $this->journal->check_voucher_no(Input::get('voucher_no'), Input::get('vtype'), Input::get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
	public function getPrint($id,$rid=null)
	{ 
		
        if($rid==null) {
			$voucherhead = 'Credit Note JV';
			$jvrow = $this->journal->findJVData($id); 
			$jerow = $this->journal->findJEPdata($id); 

			return view('body.creditnotejv.print')
						->withVoucherhead($voucherhead)
						->withDetails($jvrow)
						->withJerow($jerow);
		} else {
			$viewfile = DB::table('report_view_detail')->where('id', $rid)->select('print_name')->first(); 
			//echo '<pre>';print_r($viewfile);exit;	
			if($viewfile->print_name=='') {
				$fc='';
				$attributes['document_id'] = $id; //echo "892 : ".$this->number_to_word(12495);exit;
				$attributes['is_fc'] = ($fc)?1:'';
				$titles = ['main_head' => 'Payment Voucher','subhead' => 'Payment Voucher'];
				
				$view = 'print';

				$voucherhead = 'Credit Note JV';
				$jvrow = $this->journal->findJVData($id); //echo '<pre>';print_r($jvrow);exit;
				$jerow = $this->journal->findJEdata($id);
			
						
				$words = $this->number_to_word($jvrow->debit);
				$arr = explode('.',number_format($jvrow->debit,2));
				if(sizeof($arr) >1 ) {
					if($arr[1]!=00) {
						$dec = $this->number_to_word($arr[1]);
						$words .= ' and Fils '.$dec.' Only';
					} else 
						$words .= ' Only';
				} else
					$words .= ' Only'; 
				
				return view('body.creditnotejv.'.$view)
							->withVoucherhead($voucherhead)
							->withDetails($jvrow)
						->withJerow($jerow)
							->withAmtwords($words);


			} else {
						
				$path = app_path() . '/stimulsoft/helper.php';
				return view('body.creditnotejv.viewer')->withPath($path)->withView($viewfile->print_name);
			}
		}
		
	}

	public function getPrintold($id)
	{
		$voucherhead = 'Journal Voucher';
		$jvrow = $this->journal->find($id); 
		$jerow = $this->journal->findJEdata($id); //echo '<pre>';print_r($jerow);exit;

		return view('body.creditnotejv.print')
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
		//echo '<pre>';print_r($acdata);exit;
		return view('body.creditnotejv.transactions')
							->withBanks($banks)
							->withJobs($jobs)
							->withIsdept($is_dept)
							->withDepartments($departments)
							->withAcdata($acdata)
							->withNum($n)
							->withType($type);
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
}
