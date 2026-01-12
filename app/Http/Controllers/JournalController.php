<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Bank\BankInterface;
use App\Repositories\Currency\CurrencyInterface;
use App\Repositories\VoucherNo\VoucherNoInterface;
use App\Repositories\Jobmaster\JobmasterInterface;
use App\Repositories\Department\DepartmentInterface;
use App\Repositories\Journal\JournalInterface;
use App\Repositories\AccountSetting\AccountSettingInterface;
use App\Repositories\ReceiptVoucher\ReceiptVoucherInterface;
use App\Repositories\PaymentVoucher\PaymentVoucherInterface;
use App\Repositories\UpdateUtility;

use App\Http\Requests;
use Session;
use Response;
use Validator;
use DB;
use Auth;
use App;
use Mail;
use PDF;

class JournalController extends Controller
{
    protected $bank;
	protected $currency;
	protected $voucherno;
	protected $jobmaster;
	protected $department;
	protected $journal;
	public $objUtility;
	protected $accountsetting;
	protected $receipt_voucher;
	protected $payment_voucher;
	
	public function __construct(AccountSettingInterface $accountsetting, JournalInterface $journal, ReceiptVoucherInterface $receipt_voucher, PaymentVoucherInterface $payment_voucher, BankInterface $bank, CurrencyInterface $currency, VoucherNoInterface $voucherno, JobmasterInterface $jobmaster, DepartmentInterface $department) {
		
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
		$this->objUtility = new UpdateUtility();
	}
	
	public function index() {
		$data = array();
		$journals = [];//$this->journal->journalList();//
		
		/* $prints = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','JV')
							->select('report_view_detail.name','report_view_detail.id')
							->get(); *///echo '<pre>';print_r($prints);exit;
		return view('body.journal.index')
					->withJournals($journals)
					//->withPrints($prints)
					->withData($data);
	}
	
	public function ajaxPaging(Request $request)
	{
		$columns = array( 
                            0 =>'journal.id', 
                            1 =>'voucher_no',
							2 =>'voucher_type',
                            3=> 'voucher_date',
                            4=> 'description',
                            5=>'reference',
                            6=> 'amount'
                        );
						
		//$totalData = $this->journal->journalListCount();
            
        //$totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = 'journal.id';//$columns[$request->input('order.0.column')];
        $dir = 'desc';//$request->input('order.0.dir');
		$search = (empty($request->input('search.value')))?null:$request->input('search.value');
        
		$totalData =  $this->journal->journalListPara('count', $start, $limit, $order, $dir, $search);
		$totalFiltered = $totalData; 
		
		$invoices = $this->journal->journalListPara('get', $start, $limit, $order, $dir, $search);
		
		if($search)
			$totalFiltered =  $this->journal->journalListPara('count', $start, $limit, $order, $dir, $search);
		
		
		$prints = DB::table('report_view_detail')
			->join('report_view','report_view.id','=','report_view_detail.report_view_id')
			->where('report_view.code','JV')
			->select('report_view_detail.name','report_view_detail.id')
			->get();
			
        $data = array();
        if(!empty($invoices))
        {
			foreach ($invoices as $row)
            {
                $edit =  '"'.url('journal/edit/'.$row->id).'"';
                $delete =  'funDelete("'.$row->id.'")';
				$print = url('journal/print/'.$row->id.'/'.$prints[0]->id);
                $nestedData['id'] = $row->id;
                $nestedData['voucher_no'] = $row->voucher_no;
				$nestedData['voucher_type'] = ($row->voucher_type==9)?'CASH':$row->voucher_type;
				$nestedData['voucher_date'] = date('d-m-Y', strtotime($row->voucher_date));
				$nestedData['description'] = $row->description;
				$nestedData['reference'] = $row->reference;
				$nestedData['amount'] = $row->credit;
				$editcon =  'funPdcr()';
				
				
				if($row->is_transfer==1) {
					$nestedData['edit'] = "<p><button class='btn btn-primary btn-xs' onClick='{$editcon}'>
													<span class='glyphicon glyphicon-pencil'></span></button></p>";
													
					$nestedData['delete'] = "<button class='btn btn-danger btn-xs delete' onClick='{$editcon}'>
												<span class='glyphicon glyphicon-trash'></span>";
												
				} else {
					$nestedData['edit'] = "<p><button class='btn btn-primary btn-xs' onClick='location.href={$edit}'>
													<span class='glyphicon glyphicon-pencil'></span></button></p>";
													
					$nestedData['delete'] = "<button class='btn btn-danger btn-xs delete' onClick='{$delete}'>
												<span class='glyphicon glyphicon-trash'></span>";
				}
				
				
				/*$nestedData['edit'] = "<p><button class='btn btn-primary btn-xs' onClick='location.href={$edit}'>
												<span class='glyphicon glyphicon-pencil'></span></button></p>";
												
				$nestedData['delete'] = "<button class='btn btn-danger btn-xs delete' onClick='{$delete}'>
											<span class='glyphicon glyphicon-trash'></span>";*/
				
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
		$account = $this->accountsetting->getExpenseAccount();
		$account = $this->accountsetting->getExpenseAccount();
		//$departments = $this->department->activeDepartmentList();
		$account = $this->accountsetting->getExpenseAccount();
		$lastid = $this->journal->getLastId();
		$prints = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','JV')
							->select('report_view_detail.name','report_view_detail.id')
							->get();
		$isjv = false;
		$vouchers = $this->accountsetting->getAccountSettingsById($vid=16); //echo '<pre>';print_r($vouchers);exit;
		if(sizeof($vouchers)==0)
		    $isjv = true;
		
		$vchrdata = $this->getVoucherJV($id=16,$type='CASH');
		
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
		
		if(sizeof($vouchers)==0)
		    $vouchers = $this->accountsetting->getAccountSettingsById($vid=9,$is_dept,$deptid);
		if(sizeof($vouchers)==0)
		    $vouchers = $this->accountsetting->getAccountSettingsById($vid=10,$is_dept,$deptid);
		
		return view('body.journal.add')
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
					->withIsjv($isjv)
					->withData($data);
	}
	public function save(Request $request) {
		//echo '<pre>';print_r($request->all());exit;
		
		$validator = Validator::make($request->all(), [
            'voucher_no' => 'required|max:255',
			'debit' => 'required|same:credit'
        ]);
		
		if ($validator->fails()) {
            return redirect('journal/add')
                        ->withErrors($validator)
                        ->withInput();
        }
		
		if($request->get('voucher_type')==9) {
			
			if( $this->receipt_voucher->create($request->all()) )
				Session::flash('message', 'Customer receipt added successfully.');
			else 
				Session::flash('error', 'Something went wrong, Customer receipt failed to add!');
			
			return redirect('journal/add'); //return redirect('customer_receipt');
			
		} else if($request->get('voucher_type')==10) {
			
			if( $this->payment_voucher->create($request->all()) )
				Session::flash('message', 'Supplier payment added successfully.');
			else 
				Session::flash('error', 'Something went wrong, Supplier payment failed to add!');
			
			return redirect('supplier_payment/add'); //return redirect('supplier_payment');
			
		} else if($request->get('voucher_type')==5) {
			$id=$this->journal->create($request->all());
			if($id)
				Session::flash('message', 'Purchase voucher added successfully.');
			else 
				Session::flash('error', 'Something went wrong, Purchase voucher failed to add!');
			
			return redirect('journal/add'); //return redirect('purchase_voucher');
			
		} else if($request->get('voucher_type')==6) {
			$id=$this->journal->create($request->all());
			if($id)
				Session::flash('message', 'Sales voucher added successfully.');
			else 
				Session::flash('error', 'Something went wrong, Sales voucher failed to add!');
			
			return redirect('journal/add');//return redirect('sales_voucher');
			
		} else {
			$id=$this->journal->create($request->all());
			if($id) {
				$attributes = $request->all();
				if(isset($attributes['jvtype']) && $attributes['jvtype']=='RC') {
					$this->saveRecurringJV($request->all());
				}
            
            /*
				### Mail
				$vid=$attributes['voucher_no'];
				$data['jvrow']= DB::table('journal')
		                         ->where('journal.voucher_no',$vid)
		                         ->leftjoin('users', function($join) {
			                   $join->on('users.id','=','journal.created_by');
			                        })	
			                    ->where('journal.status', 1)
			                    ->where('journal.deleted_at', '0000-00-00 00:00:00')		 
			                    ->select('journal.*','users.name')->first();
				$id=$data['jvrow']->id;					
			    $data['jerow'] = $this->journal->findJEdata($id);
				$email='numaktech@gmail.com ';
				$no=$data['jvrow']->voucher_no;
				$body='Journal Voucher created with voucher no: %s';
				 $text= sprintf($body,$no);						
			   try{
				   Mail::send(['html'=>'body.journal.emailadd'], $data,function($message) use ($email,$text) {
				   $message->from(env('MAIL_USERNAME'));	
				   $message->to($email);
				   $message->subject($text);
				   });
			   
			   }catch(JWTException $exception){
			   $this->serverstatuscode = "0";
			   $this->serverstatusdes = $exception->getMessage();
			   //echo '<pre>';print_r($this->serverstatusdes);exit;
		   }

				###END
				*/
				
				Session::flash('message', 'Journal voucher added successfully.');
			} else 
				Session::flash('error', 'Journal entry validation error! Please try again!');
			
			return redirect('journal/add');
		}
	}

	public function quickSave(Request $request) {
		//echo '<pre>';print_r($request->all());exit;
		
		$validator = Validator::make($request->all(), [
            'voucher_no' => 'required|max:255',
			'debit' => 'required|same:credit'
        ]);
		
		if ($validator->fails()) {
            return redirect('journal/add')
                        ->withErrors($validator)
                        ->withInput();
        }
			
		if( $this->payment_voucher->create($request->all()) )
			Session::flash('message', 'Supplier payment added successfully.');
		else 
			Session::flash('error', 'Something went wrong, Supplier payment failed to add!');
		
		return redirect('supplier_payment/quick-add'); //return redirect('supplier_payment');
		
	}
	
	public function saveold(Request $request) {    // 2021 Sep20
		//echo '<pre>';print_r($request->all());exit;
		
		$validator = Validator::make($request->all(), [
            'voucher_no' => 'required|max:255',
			'debit' => 'required|same:credit'
        ]);
		
		if ($validator->fails()) {
            return redirect('journal/add')
                        ->withErrors($validator)
                        ->withInput();
        }
		
		if($request->get('voucher_type')==9) {
			
			if( $this->receipt_voucher->create($request->all()))
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
				$vouchertype =  $request->get('voucher_type');
                return redirect('journal/add/'.$id.'/'.$rid.'/'.$vouchertype);
			}
			else 
				Session::flash('error', 'Something went wrong, Customer receipt failed to add!');
			
			return redirect('journal/add'); //return redirect('customer_receipt');
			
		} else if($request->get('voucher_type')==10) {
			
			if( $this->payment_voucher->create($request->all()) )

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
				$vouchertype =  $request->get('voucher_type');
                return redirect('journal/add/'.$id.'/'.$rid.'/'.$vouchertype);
				
			}
			else 
				Session::flash('error', 'Something went wrong, Supplier payment failed to add!');
			
			return redirect('journal/add'); //return redirect('supplier_payment');
			
		} else if($request->get('voucher_type')==5) {
			$id=$this->journal->create($request->all());
			if($id)
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
			$vouchertype =  $request->get('voucher_type');
			return redirect('journal/add/'.$id.'/'.$rid.'/'.$vouchertype); 
		  
			
			}
			else
			{ 
				Session::flash('error', 'Something went wrong, Purchase voucher failed to add!');
				
			     return redirect('journal/add'); 
			}
				//return redirect('purchase_voucher');
			
		} else if($request->get('voucher_type')==6) {
			$id=$this->journal->create($request->all());
			if($id)
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
		    $vouchertype =  $request->get('voucher_type');
		    return redirect('journal/add/'.$id.'/'.$rid.'/'.$vouchertype); 
				}
			else 
				Session::flash('error', 'Something went wrong, Sales voucher failed to add!');
			
			return redirect('journal/add');//return redirect('sales_voucher');
			
		} else {
			$id=$this->journal->create($request->all());
			if($id)
				Session::flash('message', 'Journal voucher added successfully.');
			else 
				Session::flash('error', 'Something went wrong, Journal voucher failed to add!');
			return redirect('journal/add');
		}
	}
	
	/* public function save() {
		try { //echo '<pre>';print_r($request->all());exit;
			if($request->get('voucher_type')==9) {
				$this->receipt_voucher->create($request->all());
				Session::flash('message', 'Customer receipt added successfully.');
				return redirect('customer_receipt');
			} else if($request->get('voucher_type')==10) {
				$this->payment_voucher->create($request->all());
				Session::flash('message', 'Supplier payment added successfully.');
				return redirect('supplier_payment');
			} else if($request->get('voucher_type')==5) {
				$this->journal->create($request->all());
				Session::flash('message', 'Purchase voucher added successfully.');
				return redirect('purchase_voucher');
			} else if($request->get('voucher_type')==6) {
				$this->journal->create($request->all());
				Session::flash('message', 'Sales voucher added successfully.');
				return redirect('sales_voucher');
			} else {
				$this->journal->create($request->all());//exit;
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
		$account = $this->accountsetting->getExpenseAccount();
		//$departments = $this->department->activeDepartmentList();
				
		$jrow = $this->journal->find($id);
		$vouchertype = $this->accountsetting->getAccountSettings( $this->getVid($jrow->voucher_type) );
		$jerow = $this->journal->findJEdata($id);
		//echo '<pre>';print_r($jrow);exit;
		
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
			->where('report_view.code','JV')
			->select('report_view_detail.name','report_view_detail.id')
			->get();
		
		return view('body.journal.edit')
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
					->withAccount($account)
					->withSettings($this->acsettings)
					->withPrints($prints)
					->withData($data);
	}
	
	private function getVid($v)
	{
		switch($v)
		{
			case 'JV':
				return 16;
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
            return redirect('journal/edit/'.$id)
                        ->withErrors($validator)
                        ->withInput();
        }
		
		if($request->get('voucher_type')==5) {
			
			if( $this->journal->update($id, $request->all()) )
				Session::flash('message', 'Purchase voucher updated successfully.');
			else
				Session::flash('error', 'Something went wrong, Purchase voucher failed to edit!');
			
			return redirect('purchase_voucher');
		} else if($request->get('voucher_type')==6) {
			
			if( $this->journal->update($id, $request->all()) )
				Session::flash('message', 'Sales voucher updated successfully.');
			else
				Session::flash('error', 'Something went wrong, Sales voucher failed to edit!');
			
			return redirect('sales_voucher');
		} else {
			
			if( $this->journal->update($id,$request->all()) ){
            /*
			### Mail
				
			$data['jvrow']= DB::table('journal')
			->where('journal.id',$id)
			->leftjoin('users', function($join) {
		  $join->on('users.id','=','journal.modify_by');
			   })	
		   ->where('journal.status', 1)
		   ->where('journal.deleted_at', '0000-00-00 00:00:00')		 
		   ->select('journal.*','users.name')->first();
		   
          $data['jerow'] = $this->journal->findJEdata($id);
          $email='numaktech@gmail.com ';
          $no=$data['jvrow']->voucher_no;
          $body='Journal Voucher modified with voucher no: %s';
          $text= sprintf($body,$no);						
             try{
                  Mail::send(['html'=>'body.journal.emailupdate'], $data,function($message) use ($email,$text) {
                    $message->from(env('MAIL_USERNAME'));	
                    $message->to($email);
                    $message->subject($text);
                  });

                }catch(JWTException $exception){
                $this->serverstatuscode = "0";
                  $this->serverstatusdes = $exception->getMessage();
                 echo '<pre>';print_r($this->serverstatusdes);exit;
                }

             ###END
             */
				Session::flash('message', 'Journal voucher updated successfully.');
			}else
				Session::flash('error', 'Journal entry validation error! Please try again.');
			
			return redirect('journal');
		}
			
		/* $this->journal->update($id, $request->all());
		Session::flash('message', 'Journal voucher updated successfully');
		return redirect('journal'); */
	}
	
	public function destroy($id, $type)
	{
		$row = $this->journal->find($id);
		if($row->is_transfer==1) {
		    Session::flash('error', 'PDC Received already transfered, you cant delete!');
    		return redirect('journal');
		} else {
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
	
	public function getVoucherprint(Request $request)
	{                
		$type = $request->get('voucher_typeprint');
		//echo '<pre>';print_r($type);exit;
		$voucher_no = $request->get('voucherprnt_no');
		if(($type !=0) &&  (!empty($voucher_no)))
		{
		    $journals = $this->journal->journalListprit($type,$voucher_no);
		
		if($type ==16 ) {
		    $prints = DB::table('report_view_detail')
								->join('report_view','report_view.id','=','report_view_detail.report_view_id')
								->where('report_view.code','JV')
								->select('report_view_detail.name','report_view_detail.id')
								->get();
								
			if(isset($journals[0])) {
			    $id = $journals[0]->id; 
    		    $rid = $prints[0]->id;
               return redirect('journal/print/'.$id.'/'.$rid);
		    } else {
		        echo "<script>alert('Voucher No. not found!');window.close();</script>";
		        return false;
		    }

            
		} elseif($type ==5) {
		    $prints = DB::table('report_view_detail')
		                       ->join('report_view','report_view.id','=','report_view_detail.report_view_id')
		                        ->where('report_view.code','PVR')
		                        ->select('report_view_detail.name','report_view_detail.id')
		                        ->get();
		                        
		    if(isset($journals[0])) {
			    $id = $journals[0]->id; 
    		    $rid = $prints[0]->id;
               return redirect('journal/print/'.$id.'/'.$rid);
		    } else {
		        echo "<script>alert('Voucher No. not found!');window.close();</script>";
		        return false;
		    }
		   
		} elseif($type ==6) {
			$prints = DB::table('report_view_detail')
													  ->join('report_view','report_view.id','=','report_view_detail.report_view_id')
													   ->where('report_view.code','SVR')
												        ->select('report_view_detail.name','report_view_detail.id')
													   ->get();
													   
            if(isset($journals[0])) {
			    $id = $journals[0]->id; 
    		    $rid = $prints[0]->id;
               return redirect('journal/print/'.$id.'/'.$rid);
		    } else {
		        echo "<script>alert('Voucher No. not found!');window.close();</script>";
		        return false;
		    }
		    
		} elseif($type ==9) {
			$prints = DB::table('report_view_detail')
    					 ->join('report_view','report_view.id','=','report_view_detail.report_view_id')
    					  ->where('report_view.code','RV')
    					   ->select('report_view_detail.name','report_view_detail.id')
    					  ->get();
    					  
    		if(isset($journals[0])) {
			    $id = $journals[0]->id; 
    		    $rid = $prints[0]->id;
               return redirect('customer_receipt/print2/'.$id.'/'.$rid);
		    } else {
		        echo "<script>alert('Voucher No. not found!');window.close();</script>";
		        return false;
		    }
    					  
    		
               
		} elseif($type ==10) {
				$prints = DB::table('report_view_detail')->join('report_view','report_view.id','=','report_view_detail.report_view_id')
														->where('report_view.code','PV')
															->where('report_view_detail.is_default',1)
													->select('report_view_detail.name','report_view_detail.id')
																			->get();
																			
			    if(isset($journals[0])) {
    			    $id = $journals[0]->id; 
        		    $rid = $prints[0]->id;
                   return redirect('supplier_payment/print/'.$id.'/'.$rid);
			    } else {
			        echo "<script>alert('Voucher No. not found!');window.close();</script>";
			        return false;
			    }
							   
		}
		
		//return 'true';
		
	}
	
	
    	else
    	{
    	    $journals = $this->journal->journalListpritlast($type);
            if($type ==16) {
        		$prints = DB::table('report_view_detail')
        								->join('report_view','report_view.id','=','report_view_detail.report_view_id')
        								->where('report_view.code','JV')
        								->select('report_view_detail.name','report_view_detail.id')
        								->get();
               
               
               if(isset($journals)) {
    			    $id = $journals->id; 
    		        $rid = $prints[0]->id;
                    return redirect('journal/print/'.$id.'/'.$rid);
			    } else {
			        echo "<script>alert('Voucher entries not found!');window.close();</script>";
			        return false;
			    }
               
            } elseif($type ==9) {
                $prints = DB::table('report_view_detail')
        								->join('report_view','report_view.id','=','report_view_detail.report_view_id')
        								->where('report_view.code','RV')
        								->select('report_view_detail.name','report_view_detail.id')
        								->get();
    
        	   
               
               if(isset($journals)) {
    			    $id = $journals->id; 
    		         $rid = $prints[0]->id;
                     return redirect('customer_receipt/print2/'.$id.'/'.$rid);
			    } else {
			        echo "<script>alert('Voucher entries not found!');window.close();</script>";
			        return false;
			    }
               
            } elseif($type ==10) {
                $prints = DB::table('report_view_detail')
        								->join('report_view','report_view.id','=','report_view_detail.report_view_id')
        								->where('report_view.code','PV')
        								->select('report_view_detail.name','report_view_detail.id')
        								->get();
        								
        	    if(isset($journals)) {
    			    $id = $journals->id; 
    		        $rid = $prints[0]->id;
                    return redirect('supplier_payment/print/'.$id.'/'.$rid);
			    } else {
			        echo "<script>alert('Voucher entries not found!');window.close();</script>";
			        return false;
			    }
    
        	   
               
            } elseif($type ==5) {
                $prints = DB::table('report_view_detail')
        								->join('report_view','report_view.id','=','report_view_detail.report_view_id')
        								->where('report_view.code','PVR')
        								->select('report_view_detail.name','report_view_detail.id')
        								->get();
        								
        		if(isset($journals)) {
    			    $id = $journals->id; 
    		        $rid = $prints[0]->id;
                    return redirect('journal/print/'.$id.'/'.$rid);
			    } else {
			        echo "<script>alert('Voucher entries not found!');window.close();</script>";
			        return false;
			    }
			    
            } elseif($type ==6) {
                $prints = DB::table('report_view_detail')
        								->join('report_view','report_view.id','=','report_view_detail.report_view_id')
        								->where('report_view.code','SVR')
        								->select('report_view_detail.name','report_view_detail.id')
        								->get();
        								
        		if(isset($journals)) {
    			    $id = $journals->id; 
    		        $rid = $prints[0]->id;
                    return redirect('journal/print/'.$id.'/'.$rid);
			    } else {
			        echo "<script>alert('Voucher entries not found!');window.close();</script>";
			        return false;
			    }
			    
    
        	   
            }
               
            
    
        }
	}
	
	public function checkVchrNo(Request $request) {

		$check = $this->journal->check_voucher_no($request->get('voucher_no'), $request->get('vtype'), $request->get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
	
	public function checkVNo(Request $request) {

		$check = $this->journal->check_vno($request->get('voucher_no'), $request->get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
	public function getPrint($id,$rid=null)
	{ 
		
        if($rid==null) {
			$voucherhead = 'Journal Voucher';
			$jvrow = $this->journal->find($id); 
			$jerow = $this->journal->findJEdata($id); //echo '<pre>';print_r($jerow);exit;

			return view('body.journal.print')
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

				$voucherhead = 'Journal Voucher';
				$jvrow = $this->journal->find($id); 
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
				
				return view('body.journal.'.$view)
							->withVoucherhead($voucherhead)
							->withDetails($jvrow)
						->withJerow($jerow)
							->withAmtwords($words);


			} else {
						
				$path = app_path() . '/stimulsoft/helper.php';
				if(env('STIMULSOFT_VER')==2)
			        return view('body.reports')->withPath($path)->withView($viewfile->print_name);
			   else
			        return view('body.journal.viewer')->withPath($path)->withView($viewfile->print_name);
				
			}
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
	
	public function setTransactions($type,$id,$n,$jeid=null) {
		
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
		
		return view('body.journal.transactions')
							->withBanks($banks)
							->withJobs($jobs)
							->withIsdept($is_dept)
							->withDepartments($departments)
							->withAcdata($acdata)
							->withNum($n)
							->withType($type)
							->withJeid($jeid);
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
	
	public function recurringAdd(Request $request) {
		
		//echo '<pre>';print_r($request->all());exit;
		$banks = $this->bank->activeBankList();
		$jobs = $this->jobmaster->activeJobmasterList();
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
		
		$is_pdc = false;
		foreach($request->get('group_id') as $grp) {
			if($grp=='PDCR')
			 $is_pdc = true;
		}
		
		return view('body.journal.recrform')
						->withJobs($jobs)
						->withBanks($banks)
						->withIsdept($is_dept)
						->withDepartments($departments)
						->withIspdc($is_pdc)
						->withData($request->all());
	}
	
	private function saveRecurringJV($attributes) { //echo '<pre>';print_r($attributes);exit;
		
		$jvset = DB::table('account_setting')->where('voucher_type_id', 16)->where('status',1)
											 ->where('deleted_at','0000-00-00 00:00:00')
											 ->where('department_id',0)->select('id','voucher_no')->first();
		
		for ($key = 0; $key < $attributes['rcperiod']; $key++) {
			$input = null;
			$input['from_jv'] = 1;
			$input['voucher'] = $jvset->id; 
			$input['voucher_type'] = 16;
			$input['voucher_date'] = $attributes['voucher_date_rc'][$key];
			$input['voucher_no'] = $attributes['voucher_no_rc'][$key];
			
			//foreach($attributes['account_name_rc'][$attributes['voucher_no_rc'][$key]][$key] as $keyr => $val) {
			foreach($attributes['account_name_rc'][$attributes['voucher_no_rc'][$key]] as $keyr => $val) {
				$input['account_name'][] = $attributes['account_name_rc'][$attributes['voucher_no_rc'][$key]][$keyr];
				$input['account_id'][] = $attributes['account_id_rc'][$attributes['voucher_no_rc'][$key]][$keyr];
				$input['group_id'][] = $attributes['group_id_rc'][$attributes['voucher_no_rc'][$key]][$keyr];
				$input['sales_invoice_id'][] = $attributes['sales_invoice_id_rc'][$attributes['voucher_no_rc'][$key]][$keyr];
				$input['bill_type'][] = $attributes['bill_type_rc'][$attributes['voucher_no_rc'][$key]][$keyr];
				$input['description'][] = $attributes['description_rc'][$attributes['voucher_no_rc'][$key]][$keyr];
				$input['reference'][] = $attributes['reference_rc'][$attributes['voucher_no_rc'][$key]][$keyr];
				$input['inv_id'][] = $attributes['inv_id_rc'][$attributes['voucher_no_rc'][$key]][$keyr];
				$input['actual_amount'][] = $attributes['actual_amount_rc'][$attributes['voucher_no_rc'][$key]][$keyr];
				$input['account_type'][] = $attributes['account_type_rc'][$attributes['voucher_no_rc'][$key]][$keyr];
				$input['line_amount'][] = $attributes['line_amount_rc'][$attributes['voucher_no_rc'][$key]][$keyr];
				$input['job_id'][] = $attributes['job_id_rc'][$attributes['voucher_no_rc'][$key]][$keyr];
				
				$input['cheque_no'][] = $attributes['cheque_no_rc'][$attributes['voucher_no_rc'][$key]][$keyr];
				$input['cheque_date'][] = $attributes['cheque_date_rc'][$attributes['voucher_no_rc'][$key]][$keyr];
				$input['bank_id'][] = $attributes['bank_id_rc'][$attributes['voucher_no_rc'][$key]][$keyr];
				$input['partyac_id'][] = $attributes['partyac_id_rc'][$attributes['voucher_no_rc'][$key]][$keyr];
				$input['party_name'][] = $attributes['party_name_rc'][$attributes['voucher_no_rc'][$key]][$keyr];
			}
			
			$input['difference'] = 0;
			$input['curno'] = '';
			$input['debit'] = $attributes['line_amount'][$key];
			$input['credit'] =  $attributes['line_amount'][$key];
			
			$jvid = $this->createJV($input); 
			
		}
	}
	
	private function createJV($attributes) { //echo '<pre>';print_r($attributes);exit;
		
		//JN13
		do {
			$jvset = DB::table('account_setting')->where('id', $attributes['voucher'])->select('prefix','is_prefix','voucher_no')->first();
			if($jvset) {
				if($jvset->is_prefix==0) {
					$attributes['voucher_no'] = $jvset->voucher_no;
					$attributes['vno'] = $jvset->voucher_no;
				} else {
					$attributes['voucher_no'] = $jvset->prefix.$jvset->voucher_no;
					$attributes['vno'] = $jvset->voucher_no;
				}
			}
			$inv = DB::table('journal')->where('voucher_no',$attributes['voucher_no'])->where('voucher_type','JV')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->count();
		} while ($inv!=0);
		
		$jvid = DB::table('journal')->insertGetId([
					'voucher_type' => 'JV',
					'voucher_no'	=> $attributes['voucher_no'],
					'voucher_date' => date('Y-m-d',strtotime($attributes['voucher_date'])),
					'debit' => $attributes['debit'],
					'credit' => $attributes['credit'],
					'status' => 1,
					'created_at' => date('Y-m-d H:i:s')
				]);
				
		foreach($attributes['line_amount'] as $key => $value) { 
			$jveid = DB::table('journal_entry')->insertGetId([
					'journal_id' => $jvid,
					'account_id' => $attributes['account_id'][$key],
					'description' => $attributes['description'][$key],
					'reference' => $attributes['reference'][$key],
					'entry_type' => $attributes['account_type'][$key],
					'amount' => $value,
					'cheque_no' => $attributes['cheque_no'][$key],
					'cheque_date' => date('Y-m-d', strtotime($attributes['cheque_date'][$key])),
					'bank_id' => $attributes['bank_id'][$key],
					'status' => 1
				]);
				
			if($attributes['group_id'][$key]=='PDCR') {
				
				$acrow = DB::table('account_master')->where('status',1)->where('category','BANK')->select('id')->first();
			
				DB::table('pdc_received')
								->insert([ 'voucher_id' 	=>  $jvid,
											'voucher_type'   => 'DB',
											'dr_account_id' => $acrow->id,
											'cr_account_id' => $attributes['account_id'][$key],
											'reference'  => $attributes['reference'][$key],
											'amount'   			=> $attributes['line_amount'][$key],
											'status' 			=> 0,
											'created_at' 		=> date('Y-m-d H:i:s'),
											'created_by' 		=> Auth::User()->id,
											'voucher_date'		=> ($attributes['voucher_date']!='')?date('Y-m-d', strtotime($attributes['voucher_date'])):date('Y-m-d'),
											'customer_id' => $attributes['partyac_id'][$key],
											'cheque_no' => $attributes['cheque_no'][$key],
											'cheque_date' => date('Y-m-d', strtotime($attributes['cheque_date'][$key])),
											'voucher_no' => $attributes['voucher_no'],
											'description' => $attributes['description'][$key],
											'bank_id' => $attributes['bank_id'][$key]
										]);
			}
			
			$this->setAccountTransaction($attributes, $jveid, $key);
			$this->objUtility->tallyClosingBalance($attributes['account_id'][$key]);
		}
		
				DB::table('account_setting')
					->where('id', $attributes['voucher'])
					->update(['voucher_no' => $attributes['vno'] + 1 ]);		
		return $jvid;
	}
	
	private function setAccountTransaction($attributes, $journal_id, $key)
	{
		DB::table('account_transaction')
				->insert([  'voucher_type' 		=> 'JV',
						    'voucher_type_id'   => $journal_id,
							'account_master_id' => $attributes['account_id'][$key],
							'transaction_type'  => $attributes['account_type'][$key],
							'amount'   			=> $attributes['line_amount'][$key],
							'status' 			=> 1,
							'created_at' 		=> date('Y-m-d H:i:s'),
							'created_by' 		=> Auth::User()->id,
							'description' 		=> $attributes['description'][$key],
							'reference'			=> $attributes['voucher_no'],
							'invoice_date'		=> date('Y-m-d', strtotime($attributes['voucher_date'])),
							'reference_from'	=> $attributes['reference'][$key]
							]);
		
		return true;
	}
}

