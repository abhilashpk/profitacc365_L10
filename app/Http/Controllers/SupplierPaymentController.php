<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Bank\BankInterface;
use App\Repositories\Currency\CurrencyInterface;
use App\Repositories\VoucherNo\VoucherNoInterface;
use App\Repositories\Jobmaster\JobmasterInterface;
use App\Repositories\Department\DepartmentInterface;
use App\Repositories\AccountSetting\AccountSettingInterface;
use App\Repositories\PurchaseInvoice\PurchaseInvoiceInterface;
use App\Repositories\PaymentVoucher\PaymentVoucherInterface;
use App\Repositories\Forms\FormsInterface;
use App\Repositories\Salesman\SalesmanInterface;

use App\Http\Requests;
use Session;
use Response;
use Validator;
use Auth;
use DB;
use Excel;
use App;
use Mail;
use PDF;
use Redirect;

class SupplierPaymentController extends Controller
{
    protected $bank;
	protected $currency;
	protected $voucherno;
	protected $jobmaster;
	protected $department;
	protected $accountsetting;
	protected $purchase_invoice;
	protected $payment_voucher;
	protected $forms;
	protected $formData;
	protected $salesman;
	
	
	public function __construct(PaymentVoucherInterface $payment_voucher, BankInterface $bank, CurrencyInterface $currency, VoucherNoInterface $voucherno, JobmasterInterface $jobmaster,SalesmanInterface $salesman, DepartmentInterface $department,AccountSettingInterface $accountsetting,PurchaseInvoiceInterface $purchase_invoice, FormsInterface $forms) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->middleware('auth');
		$this->bank = $bank;
		$this->currency = $currency;
		$this->voucherno = $voucherno;
		$this->jobmaster = $jobmaster;
		$this->department = $department;
		$this->accountsetting = $accountsetting;
		$this->purchase_invoice = $purchase_invoice;
		$this->payment_voucher = $payment_voucher;
		$this->salesman = $salesman;
		$this->forms = $forms;
		$this->formData = $this->forms->getFormData('PV');  
	}
	
	public function index() {
		$data = array();
		
		$salesmans = $this->salesman->getSalesmanList();
			//DEPT CHECK...
		if(Session::get('department')==1) {
			$departments = DB::table('department')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
			$is_dept = true;
		} else {
			$departments = []; $is_dept = false;
		}
		//$receipts = $this->supplier_payment->SupplierPaymentList();
		$receipts = [];//$this->payment_voucher->SupplierPaymentList();
		return view('body.supplierpayment.index')
					->withReceipts($receipts)
					->withSalesman($salesmans)
					->withDepartments($departments)
					->withSettings($this->acsettings)
					->withFormdata($this->formData)
					->withData($data);
	}
	
	public function ajaxPaging(Request $request)
	{
		$columns = array( 
                            0 =>'payment_voucher.id', 
                            1 =>'voucher_no',
							2 =>'voucher_type',
                            3=> 'voucher_date',
                            4=> 'creditor',
							5=> 'debitor',
                            6=> 'amount',
                            7=>'approval',
							8=>'status',
							9=>'description',
							10=>'reference'
                        );
						
		$totalData = $this->payment_voucher->SupplierPaymentListCount();
            
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = 'payment_voucher.id';//$columns[$request->input('order.0.column')];
        $dir = 'desc';//$request->input('order.0.dir');
		$search = (empty($request->input('search.value')))?null:$request->input('search.value');
        
		$invoices = $this->payment_voucher->SupplierPaymentList('get', $start, $limit, $order, $dir, $search);
		//echo '<pre>';print_r($invoices);exit;
		$prints = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','PV')
							->select('report_view_detail.name','report_view_detail.id')
							->get();
							//echo '<pre>';print_r($prints);exit;
		if($search)
			$totalFiltered =  $this->payment_voucher->SupplierPaymentList('count', $start, $limit, $order, $dir, $search);
		
        $data = array();
        if(!empty($invoices))
        {
           
			foreach ($invoices as $row)
            {
                $edit =  '"'.url('supplier_payment/edit/'.$row->id).'"';
                $delete =  'funDelete("'.$row->id.'")';
				$print1 = url('supplier_payment/print/'.$row->id);
			
				$printgrp = url('supplier_payment/printgrp/'.$row->id);
				$editcon =  'funPdcr()';
				
				$view =  url('supplier_payment/views/'.$row->id);
				
				if($row->approval_status==1){
					$status=1;

				}
				else{
					$status=0;
				}
				
                $nestedData['id'] = $row->id;
                $nestedData['voucher_no'] = $row->voucher_no;
				$nestedData['voucher_type'] = ($row->voucher_type==10)?'CASH':$row->voucher_type;
				$nestedData['voucher_date'] = date('d-m-Y', strtotime($row->voucher_date));
				$nestedData['debitor'] = ($row->debitor!='')?$row->debitor:$row->debitor2;
				$nestedData['creditor'] = $row->creditor;
				$nestedData['supplier'] = ($row->voucher_type==10)?'CASH':$row->voucher_type;
				$nestedData['amount'] = $row->amount;
				$nestedData['description'] = $row->description;
				$nestedData['reference'] = $row->reference;
				$nestedData['approval'] = ($row->approval_status==1)?'Approved':'Not Approved';
					if(Auth::user()->roles[0]->name == "Admin"){
			    $nestedData['view'] = "<p><a href='{$view}' class='btn btn-info btn-xs' target='_blank'><i class='fa fa-fw fa-check-square'></i></a></p>";								
			 }else{
			 $nestedData['view']='';
			 }

			 $nestedData['status']=$status;	
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
				$apr = ($this->acsettings->doc_approve==1)?[1]:[0,1,2];
				
				$opts = '';					
				foreach($prints as $doc) {
					$opts .= "<li role='presentation'><a href='{$print1}/".$doc->id."' target='_blank' role='menuitem'>".$doc->name."</a></li>";
				}
				$opts .= "<li role='presentation'><a href='{$printgrp}' target='_blank' role='menuitem'>Print Group</a></li>";
				
				$printfc = url('supplier_payment/printfc/'.$row->id.'/'.$prints[0]->id); //MAR18
                if(in_array($row->doc_status, $apr))	 {							
					if($row->is_fc==1) {
						$nestedData['print'] = "<div class='btn-group drop_btn' role='group'>
											<button type='button' class='btn btn-primary btn-xs dropdown-toggle m-r-50'
													id='exampleIconDropdown1' data-toggle='dropdown' aria-expanded='false'>
												<i class='fa fa-fw fa-print' aria-hidden='true'></i><span class='caret'></span>
											</button>
											<ul style='min-width:100px !important;' class='dropdown-menu' aria-labelledby='exampleIconDropdown1' role='menu'>
												".$opts."
											</ul>
										</div><a href='{}' target='_blank' class='btn btn-primary btn-xs'><span class='fa fa-fw fa-print'></span></a>";
										
						/* $nestedData['print'] = "<p><a href='{$print}' target='_blank' class='btn btn-primary btn-xs'><span class='fa fa-fw fa-print'></span></a>
												<a href='{$printfc}' target='_blank' class='btn btn-primary btn-xs'><span class='fa fa-fw fa-print'></span>FC</a></p>"; */
					}
					else {

				 $nestedData['print'] = "<div class='btn-group drop_btn' role='group'>
				                              <button type='button' class='btn btn-primary btn-xs dropdown-toggle m-r-50'
						                    id='exampleIconDropdown1' data-toggle='dropdown' aria-expanded='false'>
					                      <i class='fa fa-fw fa-print' aria-hidden='true'></i><span class='caret'></span>
				                     </button>
				                         <ul style='min-width:100px !important;' class='dropdown-menu' aria-labelledby='exampleIconDropdown1' role='menu'>
				                           	 ".$opts."
				                               </ul></div>";
					
				/* $nestedData['print'] = "<p><a href='{$printgrp}' target='_blank' class='btn btn-primary btn-xs'><span class='fa fa-fw fa-print'></span></a></p>";*/
					}
				} else {
					$nestedData['print'] = '';
				}						
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
		$res = $this->voucherno->getVoucherNo('SP');
		$jobs = $this->jobmaster->activeJobmasterList();
		$departments = $this->department->activeDepartmentList();
		//$vouchers = $this->accountsetting->getAccountSettingsById($vid=10);
		$vno = $res->no+1;
		$lastid = $this->payment_voucher->getLastId();	
		$vchrdata = $this->getVoucher($id=10,$type='CASH'); //echo '<pre>';print_r($vchrdata);exit;
		
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
		$vouchers = $this->accountsetting->getAccountSettingsById($vid=10,$is_dept,$deptid);
		
		$prints = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','PV')
							->where('report_view_detail.is_default',1)
							->select('report_view_detail.name','report_view_detail.id')
							->get();
		
		return view('body.supplierpayment.add')
					->withCurrency($currency)
					->withVoucherno($vno)
					->withBanks($banks)
					->withJobs($jobs)
					->withDepartments($departments)
					->withVouchers($vouchers)
					->withFormdata($this->formData)
					->withPrintid($lastid)
					->withVchrdata($vchrdata)
					->withIsdept($is_dept)
					->withDepartments($departments)
					->withDeptid($deptid)
					->withSettings($this->acsettings)
					->withPrints($prints)
					->withData($data);
	}
	
	public function save(Request $request) { //echo '<pre>';print_r($request->all());exit;
		
		if( $this->validate(
			$request, 
			['amount' => 'required',
			 'supplier_account' => 'required','supplier_id' => 'required',
			 'cheque_no' => ($request->get('voucher_type')=='PDCI')?'required':'', 
			 'cheque_date' => ($request->get('voucher_type')=='PDCI')?'required':'',
			 'bank_id' => ($request->get('voucher_type')=='PDCI')?'required':'',
			 'tag'  => ($request->get('on_amount')=='')?'array|min:1|required':'',
			 //'line_amount.*' => 'array|min:1|required', on_amount
			 'debit' => 'required|same:credit'
			],
			['amount.required' => 'Amount is required.',
			 'supplier_account.required' => 'Supplier account is required.','supplier_id.required' => 'Supplier account is invalid.',
			 'cheque_no' => 'Cheque no required.',
			 'cheque_date' => 'Cheque date required.',
			 'bank_id' => 'Bank required.',
			 'tag.required'   => 'Select at least one invoice bill.',
			 //'line_amount.*' => 'Invoice assign amount is required.',
			 'debit' => 'Debit and Credit amount should be equal.'
			]
		)) {

			return redirect('supplier_payment/add')->withInput()->withErrors();
		}
		 //echo '<pre>';print_r($request->all());exit;
		/* $validator = Validator::make($request->all(), [
			'debit' => 'required|same:credit'
        ]);
		//($this->formData['reference_no']==1)?'required':'nullable', 
		if ($validator->fails()) {
            return redirect('supplier_payment/add')
                        ->withErrors($validator)
                        ->withInput();
        } */
        $attributes	= $request->all();
		$id=$this->payment_voucher->create($request->all());
		//echo '<pre>';print_r($id);exit;
		if($attributes['send_email']==1) {
		$data['crrow']= DB::table('payment_voucher')
		            ->where('payment_voucher.id',$id)
					->leftjoin('users', function($join) {
						$join->on('users.id','=','payment_voucher.created_by');
						})	
						//->where('payment_voucher.status', 1)
						->where('payment_voucher.deleted_at', '0000-00-00 00:00:00')		 
						->select('payment_voucher.*','users.name')->first();
						//echo '<pre>';print_r($data['crrow']);exit;
		$data['invoicerow'] = $this->payment_voucher->findPVdata($id);
		$email='numaktech@gmail.com';
		$no=$data['crrow']->voucher_no;
		$body='Payment Voucher created with voucher no: %s';
		 $text= sprintf($body,$no);						
			try{
					Mail::send(['html'=>'body.supplierpayment.emailadd'], $data,function($message) use ($email,$text) {
					$message->from(env('MAIL_USERNAME'));	
					$message->to($email);
					$message->subject($text);
					});
				
				}catch(JWTException $exception){
				$this->serverstatuscode = "0";
				$this->serverstatusdes = $exception->getMessage();
				echo '<pre>';print_r($this->serverstatusdes);exit;
			}
		}
		if($id)
			Session::flash('message', 'Supplier payment added successfully.');
		else
			Session::flash('error', 'Payment entry validation error! Please try again.');
		
		return redirect('supplier_payment/add');
		
	}


	// public function ChequeDetails() {

	// 	$data = array();
	// 	return view('body.supplierpayment.cheque_details')
	// 				->withData($data);
	// }
	
	public function edit($id) { 

		$data = array();
		$currency = $this->currency->activeCurrencyList();
		$banks = $this->bank->activeBankList();
		$jobs = $this->jobmaster->activeJobmasterList();
		//$departments = $this->department->activeDepartmentList();
		$vouchers = $this->accountsetting->getAccountSettingsById($vid=10);
		
		$crrow = $this->payment_voucher->find($id); 
		$invoicerow = $this->payment_voucher->findPVEtryData($id);  //echo '<pre>';print_r($invoicerow);exit;
		
		//$invoicerow = $this->payment_voucher->findJEdata($id);
		//$invoicetrrow = $this->payment_voucher->findRVTrdata($id);
		
		$openbalances = $this->purchase_invoice->getOpenBalances(isset($invoicerow[1]) && $invoicerow[1]->account_id,'edit');
		$invoices = $this->purchase_invoice->getSupplierInvoice(isset($invoicerow[1]) && $invoicerow[1]->account_id,'edit');
		
		$vouchertype = $this->accountsetting->getAccountSettings(10);
		//$view = ($crrow->from_jv==0)?'edit':'editjv';
		 //echo '<pre>';print_r($invoices);exit;
		$view = 'edit';//'edit-pv';
		
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
							->where('report_view.code','PV')
							->where('report_view_detail.is_default',1)
							->select('report_view_detail.name','report_view_detail.id')
							->get();
		return view('body.supplierpayment.'.$view)
					->withCurrency($currency)
					->withBanks($banks)
					->withJobs($jobs)
					->withCrrow($crrow)
					->withDepartments($departments)
					->withVouchers($vouchers)
					->withInvoices($invoices)
					->withOpenbalances($openbalances)
					->withInvoicerow($invoicerow)
					//->withInvoicetrrow($invoicetrrow)
					->withVouchertype($vouchertype)
					->withIsdept($is_dept)
					->withDepartments($departments)
					->withDeptid($deptid)
					->withPrints($prints)
					->withSettings($this->acsettings)
					->withFormdata($this->formData)
					->withData($data);
	}
	
	public function update(Request $request, $id)
	{
		$validator = Validator::make($request->all(), [
			'debit' => 'required|same:credit'
        ]);
		
		if ($validator->fails()) {
            return redirect('supplier_payment/edit/'.$id)
                        ->withErrors($validator)
                        ->withInput();
        }
		$attributes	= $request->all();
		if( $this->payment_voucher->update($id, $request->all()) )
       {
		### Mail 
		if($attributes['send_email']==1) {
		$data['crrow']= DB::table('payment_voucher')
		->where('payment_voucher.id',$id)
		->join('users', function($join) {
			$join->on('users.id','=','payment_voucher.modify_by');
			})	
			->where('payment_voucher.status', 1)
			->where('payment_voucher.deleted_at', '0000-00-00 00:00:00')		 
			->select('payment_voucher.*','users.name')->first();		
			$data['invoicerow'] = $this->payment_voucher->findPVdata($id);
			$email='numaktech@gmail.com';
			$no=$data['crrow']->voucher_no;
			$body='Payment Voucher modified with voucher no: %s';
			$text= sprintf($body,$no);						
			try{
				Mail::send(['html'=>'body.supplierpayment.emailupdate'], $data,function($message) use ($email,$text) {
				$message->from(env('MAIL_USERNAME'));	
				$message->to($email);
				$message->subject($text);
				});
			
			}catch(JWTException $exception){
			$this->serverstatuscode = "0";
			$this->serverstatusdes = $exception->getMessage();
			echo '<pre>';print_r($this->serverstatusdes);exit;
		}
		}
		###End
			Session::flash('message', 'Supplier payment updated successfully');
       }else
			Session::flash('error', 'Payment entry validation error! Please try again.');
		if(isset($attributes['from_pv'])) 
		return redirect('payment_voucher');
		else
		return redirect('supplier_payment');
	}
	
	
	public function getVoucherJV($id,$type) {
		
		 $row = $this->accountsetting->getDrVoucherByID2($id);//return $row;//print_r($row);
		// echo '<pre>';print_r($row);exit;
		 if($row) {
			 if($row->voucher_no != '' || $row->voucher_no != null) {
				 if($row->is_prefix==0) {
					 $voucher = $row->voucher_no;
					 $is_prefix = 0;
					 $prefix = '';
					 $no= $row->voucher_no;
				 } else {
					 $no = (int)$row->voucher_no;
					 $voucher = $row->prefix.''.$no;
					 $is_prefix = 1;
					 $prefix = $row->prefix;
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
									'number' => $no,
									'is_prefix' => $is_prefix,
									'prefix' => $prefix,
									'id' => $id);
		 } else
			 return null;
		
	}
	
		public function indexpv() {
		$data = array();
			if(Session::get('department')==1) {
			$departments = DB::table('department')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
			$is_dept = true;
		} else {
			$departments = []; $is_dept = false;
		}
		//$receipts = $this->supplier_payment->SupplierPaymentList();
		$receipts = [];//$this->payment_voucher->SupplierPaymentList();
		return view('body.paymentvoucher.index')
					->withReceipts($receipts)
					->withDepartments($departments)
					->withSettings($this->acsettings)
					->withData($data);
	
		}
		public function ajaxPagingpv(Request $request)
	{
		$columns = array( 
                            0 =>'payment_voucher.id', 
                            1 =>'voucher_no',
							2 =>'voucher_type',
                            3=> 'voucher_date',
                            4=> 'creditor',
							5=> 'debitor',
                            6=> 'amount',
                            7=>'approval',
							8=>'status',
							9=>'description',
							10=>'reference'
                        );
						
		$totalData = $this->payment_voucher->SupplierPaymentListCount();
            
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = 'payment_voucher.id';//$columns[$request->input('order.0.column')];
        $dir = 'desc';//$request->input('order.0.dir');
		$search = (empty($request->input('search.value')))?null:$request->input('search.value');
        
		$invoices = $this->payment_voucher->SupplierPaymentList('get', $start, $limit, $order, $dir, $search);
		//echo '<pre>';print_r($invoices);exit;
		$prints = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','PV')
							->select('report_view_detail.name','report_view_detail.id')
							->get();
		if($search)
			$totalFiltered =  $this->payment_voucher->SupplierPaymentList('count', $start, $limit, $order, $dir, $search);
		
        $data = array();
        if(!empty($invoices))
        {
           
			foreach ($invoices as $row)
            {
                $edit =  '"'.url('payment_voucher/edit/'.$row->id).'"';
                $delete =  'funDelete("'.$row->id.'")';
				$print1 = url('supplier_payment/print/'.$row->id);
			
				$printgrp = url('supplier_payment/printgrp/'.$row->id);
				$editcon =  'funPdcr()';
				
				$view =  url('supplier_payment/views/'.$row->id);
				
				if($row->approval_status==1){
					$status=1;

				}
				else{
					$status=0;
				}
				
                $nestedData['id'] = $row->id;
                $nestedData['voucher_no'] = $row->voucher_no;
				$nestedData['voucher_type'] = ($row->voucher_type==10)?'CASH':$row->voucher_type;
				$nestedData['voucher_date'] = date('d-m-Y', strtotime($row->voucher_date));
				$nestedData['debitor'] = ($row->debitor!='')?$row->debitor:$row->debitor2;
				$nestedData['creditor'] = $row->creditor;
				$nestedData['supplier'] = ($row->voucher_type==10)?'CASH':$row->voucher_type;
				$nestedData['amount'] = $row->amount;
				$nestedData['description'] = $row->description;
				$nestedData['reference'] = $row->reference;
				$nestedData['approval'] = ($row->approval_status==1)?'Approved':'Not Approved';
					if(Auth::user()->roles[0]->name == "Admin"){
			    $nestedData['view'] = "<p><a href='{$view}' class='btn btn-info btn-xs' target='_blank'><i class='fa fa-fw fa-check-square'></i></a></p>";								
			 }else{
			 $nestedData['view']='';
			 }

			 $nestedData['status']=$status;	
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
				$apr = ($this->acsettings->doc_approve==1)?[1]:[0,1,2];
				
				$opts = '';					
				foreach($prints as $doc) {
					$opts .= "<li role='presentation'><a href='{$print1}/".$doc->id."' target='_blank' role='menuitem'>".$doc->name."</a></li>";
				}
				$opts .= "<li role='presentation'><a href='{$printgrp}' target='_blank' role='menuitem'>Print Group</a></li>";
				
				$printfc = url('supplier_payment/printfc/'.$row->id.'/'.$prints[0]->id); //MAR18
                if(in_array($row->doc_status, $apr))	 {							
					if($row->is_fc==1) {
						$nestedData['print'] = "<div class='btn-group drop_btn' role='group'>
											<button type='button' class='btn btn-primary btn-xs dropdown-toggle m-r-50'
													id='exampleIconDropdown1' data-toggle='dropdown' aria-expanded='false'>
												<i class='fa fa-fw fa-print' aria-hidden='true'></i><span class='caret'></span>
											</button>
											<ul style='min-width:100px !important;' class='dropdown-menu' aria-labelledby='exampleIconDropdown1' role='menu'>
												".$opts."
											</ul>
										</div><a href='{}' target='_blank' class='btn btn-primary btn-xs'><span class='fa fa-fw fa-print'></span></a>";
										
						/* $nestedData['print'] = "<p><a href='{$print}' target='_blank' class='btn btn-primary btn-xs'><span class='fa fa-fw fa-print'></span></a>
												<a href='{$printfc}' target='_blank' class='btn btn-primary btn-xs'><span class='fa fa-fw fa-print'></span>FC</a></p>"; */
					}
					else {

			$nestedData['print'] = "<div class='btn-group drop_btn' role='group'>
				                              <button type='button' class='btn btn-primary btn-xs dropdown-toggle m-r-50'
						                    id='exampleIconDropdown1' data-toggle='dropdown' aria-expanded='false'>
					                      <i class='fa fa-fw fa-print' aria-hidden='true'></i><span class='caret'></span>
				                     </button>
				                         <ul style='min-width:100px !important;' class='dropdown-menu' aria-labelledby='exampleIconDropdown1' role='menu'>
				                           	 ".$opts."
				                               </ul></div>";
					
				 /*	 $nestedData['print'] = "<p><a href='{$printgrp}' target='_blank' class='btn btn-primary btn-xs'><span class='fa fa-fw fa-print'></span></a></p>";*/
					}
				} else {
					$nestedData['print'] = '';
				}						
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
		public function addjpv() {
			
		//echo '<pre>';print_r($vouchertype);exit;
		$data = array();
		$currency = $this->currency->activeCurrencyList();
		$banks = $this->bank->activeBankList();
		$jobs = $this->jobmaster->activeJobmasterList();
		//$departments = $this->department->activeDepartmentList();
		$account = $this->accountsetting->getExpenseAccount();
		//$lastid = $this->journal->getLastId();
		$lastid = $this->payment_voucher->getLastId();	
		$prints = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','JV')
							->select('report_view_detail.name','report_view_detail.id')
							->get();
		$isjv = false;
		$vouchers = $this->accountsetting->getAccountSettingsById($vid=10); //echo '<pre>';print_r($vouchers);exit;
		if(sizeof($vouchers)==0)
		    $isjv = true;
		
		$vchrdata = $this->getVoucherJV($id=10,$type='CASH');
		
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
		
		return view('body.paymentvoucher.add')
					->withCurrency($currency)
					->withBanks($banks)
					->withJobs($jobs)
					->withAccount($account)
					->withPrintid($lastid)
					->withPrints($prints)
					->withVouchers($vouchers)
					->withId($id)
				//	->withVouchertype($vouchertype='')
				//	->withRid($rid)
					->withVchrdata($vchrdata)
					->withIsdept($is_dept)
					->withDepartments($departments)
					->withDeptid($deptid)
					->withSettings($this->acsettings)
					->withIsjv($isjv)
					->withData($data);
	}
	
	public function savepv(Request $request) {
		//echo '<pre>';print_r($request->all());exit;

		// --- Validation Rules ---
		/*$rules = [
			'voucher_type'   => 'required',
			'voucher'        => 'required|integer',
			'voucher_no'     => 'required',
			'voucher_date'   => 'required|date_format:d-m-Y',

			'account_id.*'   => 'required|integer|exists:account_master,id',
			'account_type.*' => 'required|in:Dr,Cr',
			'line_amount.*'  => 'required|numeric|min:0.01',
			'reference.*'    => 'required|string|max:50',

			'debit'          => 'required|numeric|min:0',
			'credit'         => 'required|numeric|min:0',

			// Optional fields - remove `nullable`, just keep valid type rules
			'bank_id.*'      => 'integer|exists:bank,id',
			'cheque_no.*'    => 'string|max:50',
			'cheque_date.*'  => 'date_format:d-m-Y',
			'party_name.*'   => 'string|max:100',
		];


        $messages = [
            'voucher_no.required'   => 'Voucher number is required.',
            'voucher_date.required' => 'Voucher date is required.',
            'voucher_date.date_format' => 'Invalid date format (expected dd-mm-yyyy).',
            'account_id.*.required' => 'Account selection is required.',
            'account_type.*.in'     => 'Invalid account type.',
            'line_amount.*.required'=> 'Each line must have an amount.',
            'line_amount.*.numeric' => 'Amount must be numeric.',
            'line_amount.*.min'     => 'Amount must be greater than zero.',
            'reference.*.required'  => 'Reference number is required.',
        ];

		// Laravel 5.2: Apply optional fields only if present
		$validator = Validator::make($request->all(), $rules, $messages);

		$validator->sometimes('bank_id.*', 'integer|exists:bank,id', function($input) {
			return !empty($input->bank_id);
		});
		$validator->sometimes('cheque_no.*', 'string|max:50', function($input) {
			return !empty($input->cheque_no);
		});
		$validator->sometimes('cheque_date.*', 'date_format:d-m-Y', function($input) {
			return !empty($input->cheque_date);
		});

        $validator = Validator::make($request->all(), $rules, $messages);

        // --- Extra Check: Debit and Credit must balance ---
        $validator->after(function($validator) use ($request) {
            $debit  = floatval($request->input('debit', 0));
            $credit = floatval($request->input('credit', 0));
            if (abs($debit - $credit) > 0.001) {
                $validator->errors()->add('credit', 'Debit and Credit must be equal.');
            }
        });

        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        }*/
		
		/*$validator = Validator::make($request->all(), [
            'voucher_no' => 'required|max:255',
			'debit' => 'required|same:credit'
        ]);
		
		if ($validator->fails()) {
            return redirect('payment_voucher/add')
                        ->withErrors($validator)
                        ->withInput();
        }*/

        if( $this->payment_voucher->create($request->all()) )
				Session::flash('message', 'Payment Voucher added successfully.');
			else 
				Session::flash('error', 'Something went wrong, Payment Voucher failed to add!');
			
			return redirect('payment_voucher/add');
        
	}
		public function editpv($id) { 

		$data = array();
		$currency = $this->currency->activeCurrencyList();
		$banks = $this->bank->activeBankList();
		$jobs = $this->jobmaster->activeJobmasterList();
		//$departments = $this->department->activeDepartmentList();
		$vouchers = $this->accountsetting->getAccountSettingsById($vid=10);
		
		$crrow = $this->payment_voucher->find($id); 
		$invoicerow = $this->payment_voucher->findPVEtryData($id);  //echo '<pre>';print_r($invoicerow);exit;
		
		//$invoicerow = $this->payment_voucher->findJEdata($id);
		//$invoicetrrow = $this->payment_voucher->findRVTrdata($id);
		
		$openbalances = $this->purchase_invoice->getOpenBalances(isset($invoicerow[1]) && $invoicerow[1]->account_id,'edit');
		$invoices = $this->purchase_invoice->getSupplierInvoice(isset($invoicerow[1]) && $invoicerow[1]->account_id,'edit');
		
		$vouchertype = $this->accountsetting->getAccountSettings(10);
		//$view = ($crrow->from_jv==0)?'edit':'editjv';
		 //echo '<pre>';print_r($invoices);exit;
		$view = 'edit-pv';
		
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
							->where('report_view.code','PV')
							->where('report_view_detail.is_default',1)
							->select('report_view_detail.name','report_view_detail.id')
							->get();
		return view('body.paymentvoucher.edit')
					->withCurrency($currency)
					->withBanks($banks)
					->withJobs($jobs)
					->withPrints($prints)
					->withCrrow($crrow)
					->withDepartments($departments)
					->withVouchers($vouchers)
					->withInvoices($invoices)
					->withOpenbalances($openbalances)
					->withInvoicerow($invoicerow)
					//->withInvoicetrrow($invoicetrrow)
					->withVouchertype($vouchertype)
					->withIsdept($is_dept)
					->withDepartments($departments)
					->withDeptid($deptid)
					->withSettings($this->acsettings)
					->withFormdata($this->formData)
					->withData($data);
	}
	
	
	public function getViews($id) {
	    
	    	$data = array();
		$currency = $this->currency->activeCurrencyList();
		$banks = $this->bank->activeBankList();
		$jobs = $this->jobmaster->activeJobmasterList();
		//$departments = $this->department->activeDepartmentList();
		$vouchers = $this->accountsetting->getAccountSettingsById($vid=10);
		
		$crrow = $this->payment_voucher->find($id); 
		$invoicerow = $this->payment_voucher->findPVEtryData($id);  //echo '<pre>';print_r($invoicerow);exit;
		
		//$invoicerow = $this->payment_voucher->findJEdata($id);
		//$invoicetrrow = $this->payment_voucher->findRVTrdata($id);
		
		$openbalances = $this->purchase_invoice->getOpenBalances(isset($invoicerow[1]) && $invoicerow[1]->account_id,'edit');
		$invoices = $this->purchase_invoice->getSupplierInvoice(isset($invoicerow[1]) && $invoicerow[1]->account_id,'edit');
		
		$vouchertype = $this->accountsetting->getAccountSettings(10);
		//$view = ($crrow->from_jv==0)?'edit':'editjv';
		 //echo '<pre>';print_r($invoices);exit;
		$view = 'edit';
		
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
		
		return view('body.supplierpayment.viewapproval')
					->withCurrency($currency)
					->withBanks($banks)
					->withJobs($jobs)
					->withCrrow($crrow)
					->withDepartments($departments)
					->withVouchers($vouchers)
					->withInvoices($invoices)
					->withOpenbalances($openbalances)
					->withInvoicerow($invoicerow)
					//->withInvoicetrrow($invoicetrrow)
					->withVouchertype($vouchertype)
					->withIsdept($is_dept)
					->withDepartments($departments)
					->withDeptid($deptid)
					->withSettings($this->acsettings)
					->withData($data);
	    
	}
		public function getApproval($id)
	{
		DB::table('payment_voucher')->where('id',$id)->update(['approval_status' => 1]);
		Session::flash('message', 'Voucher approved successfully.');
		return redirect('supplier_payment');
	}
	
	
	public function destroy($id)
	{
		$status = $this->payment_voucher->check_PV($id);
		if($status) {
			if( $this->payment_voucher->delete($id) ) {
				DB::table('pdc_issued')->where('voucher_id',$id)->where('status',0)->update(['deleted_at' => date('Y-m-d H:i:s')]);
				Session::flash('message', 'Supplier Payment deleted successfully.');
			} else
				Session::flash('error', 'Something went wrong, Supplier payment failed to delete!');
		} else
			Session::flash('error', 'Supplier Payment is already in use, you can\'t delete this!');
		
		return redirect('supplier_payment');
	}
	protected function makeTreeName($result)
	{
		$childs = array();
		foreach($result as $item)
		$childs[$item->debitor][] = $item;
		
		return $childs;
	}	
	
public function getSearch(Request $request)
	{
		$data = array();
		$dname = '';
		$supid = $itemid = '';
		$voucher_head  = '';
		//echo '<pre>';print_r($request->get('search_type'));exit;
	    if($request->get('search_type')=="summary")
		{
			$voucher_head = 'Payment Voucher Summary';
			$report = $this->payment_voucher->getReport($request->all());
		//	echo '<pre>';print_r($reports);exit;
			$reports = $this->makeTreeName($report);
			//echo '<pre>';print_r($reports);exit;
			$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];
			if($request->get('supplier_id')!==null)
				$supid = implode(',', $request->get('supplier_id'));
			else
				$supid = '';
			}
		
		else if($request->get('search_type')=="detail") {
			$voucher_head = 'Payment voucher Detail';
			$report = $this->payment_voucher->getReport($request->all());
		    $reports = $this->makeTreeName($report);
		  //  echo '<pre>';print_r($reports);exit;
			$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];
			
		} else if($request->get('search_type')=="item") {
			$voucher_head = 'Purchase Invoice by Itemwise';
			$report = $this->purchase_invoice->getReport($request->all());
			$reports = $this->groupbyItemwise($report);
			$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];
			//echo '<pre>';print_r($reports);exit;
			if($request->get('item_id')!==null)
				$itemid = implode(',', $request->get('item_id'));
			else
				$itemid = '';
		
		//else if($request->get('search_type')=="tax_code") {
			//$voucher_head = 'Purchase Invoice by Tax Code';
			//$reports = $this->makeTreeTC($reports);
		//}
	}else if($request->get('search_type')=='supplier') {
	//	echo '<pre>';print_r($reports);exit;
			$voucher_head = 'Purchase Invoice by supplierwise';
			
		    $reports = $this->makeTreeSup($report);
			$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];
			if($request->get('supplier_id')!==null)
				$supid = implode(',', $request->get('supplier_id'));
			else
				$supid = '';
		}
		//echo '<pre>';print_r($reports);exit;
		return view('body.supplierpayment.preprint')
					->withReports($reports)
					->withVoucherhead($voucher_head)
					->withType($request->get('search_type'))
					->withFromdate($request->get('date_from'))
					->withTodate($request->get('date_to'))
					->withI(0)
					->withTitles($titles)
					->withIsimport($request->get('isimport'))
					->withSettings($this->acsettings)
					->withSupplier($supid)
					->withItem($itemid)
					->withDname($dname)
					->withData($data);
	}
	
	
public function dataExport(Request $request)
	{
		$data = array();
		$datareport[] = [strtoupper(Session::get('company')),'','',''];
		$datareport[] = ['','','','','','',''];
		
		$request->merge(['type' => 'export']);
	//	$reports = $this->purchase_invoice->getReportExcel($request->all());
		
		if($request->get('search_type')=="summary")
		{
			$voucher_head = 'Payment Voucher Summary';
			$reports = $this->payment_voucher->getReport($request->all());
		
				$datareport[] = ['','','','',strtoupper($voucher_head), '','',''];
		     $datareport[] = ['','','','','','',''];
		
			$datareport[] = [ 'Supplier Account','Net Total'];
			$i=0;
			foreach ($reports as $row) {
					$i++;
					$datareport[] = [ 
									 'sup account ' => $row['debiter'],
									 
									 
									  'total' => $row['amount']
									];
			}
		}
		elseif($request->get('search_type')=="detail") {
			$voucher_head = 'Payment Voucher Detail';
			$reports = $this->payment_voucher->getReport($request->all());
				$datareport[] = ['','','','',strtoupper($voucher_head), '','',''];
		     $datareport[] = ['','','','','','',''];
		
			$datareport[] = ['SI.No.','RV.No','RV','Vchr.Date', 'Debit Account','Customer Account','Net Total'];
			$i=0;
			foreach ($reports as $row) {
					$i++;
					$datareport[] = [ 'si' => $i,
									  'rv' => $row['voucher_no'],
									  'rvtype' => $row['voucher_type'],
									  'rdate' => date('d-m-Y',strtotime($row['voucher_date'])),
									  'daccount' => $row['debiter'],
									  'caccount' => $row['creditor'],
									 
									  'amount' => $row['amount']
									 
									];
			}
			//$reports = $this->makeTree($reports);
		}
		
	
		//echo '<pre>';print_r($reports);exit;
		/* if($request->get('search_type')=='purchase_register') {
			
			$datareport[] = ['SI.No.','PI#','Vchr.Date','PI.Ref#', 'Supplier','TRN No','PI.Qty','Rate','Total Amt.'];
			$i=0;
			foreach ($reports as $row) {
				$i++;
				$datareport[] = [ 'si' => $i,
								  'po' => $row['voucher_no'],
								  'vdate' => date('d-m-Y',strtotime($row['voucher_date'])),
								  'ref' => $row['reference_no'],
								  'supplier' => $row['master_name'],
								  'vat_no' => $row['vat_no'],
								  'item_code' => $row['item_code'],
								  'description' => $row['description'],
								  'quantity' => $row['quantity'],
								  'unit_price' => $row['unit_price'],
								  'net_amount' => $row['net_amount']
								];
			}
		} else { */
			
		
			
		//}
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
			
	public function getVoucher($id,$type) {
		
		 $row = $this->accountsetting->getDrVoucherByID2($id);//return $row;//print_r($row);
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
	
	public function checkVchrNo(Request $request) {

		$check = $this->payment_voucher->check_voucher_no($request->get('voucher_no'), $request->get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
	public function getPrint($id,$rid=null)
	{ 
		$viewfile = DB::table('report_view_detail')->where('id', $rid)->select('print_name')->first(); 
				
		if($viewfile->print_name=='') {
			$fc='';
			$attributes['document_id'] = $id; //echo "892 : ".$this->number_to_word(12495);exit;
			$attributes['is_fc'] = ($fc)?1:'';
			$titles = ['main_head' => 'Payment Voucher','subhead' => 'Payment Voucher'];
			
			$view = 'print';

			$voucherhead = 'Payment Voucher';
			$crrow = $this->payment_voucher->find($id); 
			$invoicerow = $this->payment_voucher->findPVdata($id); 
			//echo '<pre>';print_r($invoicerow);exit;
			
			//CHECK FOR DISCOUNT A/C
			$disArr = null;
			foreach($invoicerow as $row) {
			    if(str_contains($row->master_name, 'DISCOUNT') || str_contains($row->master_name, 'Discount')) { 
			        $disArr = (object)['master_name' => $row->master_name, 'amount' => $row->amount];
			    }
			}
			
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
				$crow = DB::table('parameter1')->join('currency','currency.id','=','parameter1.bcurrency_id')->select('currency.code')->first();
			return view('body.supplierpayment.'.$view)
						->withVoucherhead($voucherhead)
						->withDetails($crrow)
						->withInvoicerow($invoicerow)
						->withDisarr($disArr)
						->withCurrency($crow->code)
						->withAmtwords($words);


		} else {
					
			$path = app_path() . '/stimulsoft/helper.php';
			if(env('STIMULSOFT_VER')==2)
			        return view('body.reports')->withPath($path)->withView($viewfile->print_name);
			   else
			        return view('body.supplierpayment.viewer')->withPath($path)->withView($viewfile->print_name);
			        
			
		}
		
	}
	public function getPrintold($id)
	{
		$voucherhead = 'Payment Voucher';
		$crrow = $this->payment_voucher->find($id); 
		$invoicerow = $this->payment_voucher->findPVdata($id); 
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
		
		return view('body.supplierpayment.print')
					->withVoucherhead($voucherhead)
					->withDetails($crrow)
					->withInvoicerow($invoicerow)
					->withAmtwords($words);
	}
	
	public function getGrpPrint($id)
	{
		$voucherhead = 'Payment Voucher';
		$crrow = $this->payment_voucher->find($id); 
		$invoicerow = $this->payment_voucher->findPVdata($id); //echo '<pre>';print_r($invoicerow);exit;
				
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
		
		return view('body.supplierpayment.printgrp')
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
	
	public function getDeptVoucher($id) {
		
		$type='CASH';
		 $rows = $this->accountsetting->getVoucherByDeptRV($vid=10, $id); //return $row;//print_r($row);
		 foreach($rows as $row) {
			 if($row->voucher_no != '' || $row->voucher_no != null) {
				 if($row->is_prefix==0)
					 $voucher = $row->voucher_no;
				 else {
					 $no = (int)$row->voucher_no;
					 $voucher = $row->prefix.''.$no;
				 }
			 }
			 
			 $voucher_name = $row->voucher_name;
			 $vid = $row->vid;
			 
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
			 
			 $result[] = array('voucher_no' => $voucher,
								'account_name' => $master_name, 
								'id' => $id,
								'voucher_name' => $voucher_name,
								'voucher_id' => $vid
								);
		 }
		 
		return $result;
								
	}

	public function quickAdd() { 

		$data = array();
		$currency = $this->currency->activeCurrencyList();
		$banks = $this->bank->activeBankList();
		$jobs = $this->jobmaster->activeJobmasterList();
		
		
		$vouchertype = $this->accountsetting->getAccountSettings(10);
		
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
		$vouchers = $this->accountsetting->getAccountSettingsById($vid=10,$is_dept,$deptid);
		$vchrdata = $this->getVoucher($id=10,$type='CASH');
		return view('body.supplierpayment.quickadd')
					->withCurrency($currency)
					->withBanks($banks)
					->withJobs($jobs)
					->withDepartments($departments)
					->withVouchers($vouchers)
					->withVouchertype($vouchertype)
					->withIsdept($is_dept)
					->withDeptid($deptid)
					->withSettings($this->acsettings)
					->withVchrdata($vchrdata)
					->withInvoicerow([])
					->withCashac($cashac)
					->withData($data);
	}

    
    //NEW SECTION FEB25
	public function addPV() {

		$currency = $this->currency->activeCurrencyList();
		$banks = $this->bank->activeBankList();
		$res = $this->voucherno->getVoucherNo('SP');
		$jobs = $this->jobmaster->activeJobmasterList();
		$departments = $this->department->activeDepartmentList();
		//$vouchers = $this->accountsetting->getAccountSettingsById($vid=10);
		$vno = $res->no+1;
		$lastid = $this->payment_voucher->getLastId();	
		$vchrdata = $this->getVoucher($id=10,$type='CASH'); //echo '<pre>';print_r($vchrdata);exit;
		
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
		$vouchers = $this->accountsetting->getAccountSettingsById($vid=10,$is_dept,$deptid);
		
		return view('body.supplierpayment.add-pv')
					->withCurrency($currency)
					->withVoucherno($vno)
					->withBanks($banks)
					->withJobs($jobs)
					->withDepartments($departments)
					->withVouchers($vouchers)
					->withFormdata($this->formData)
					->withPrintid($lastid)
					->withVchrdata($vchrdata)
					->withIsdept($is_dept)
					->withDepartments($departments)
					->withDeptid($deptid)
					->withSettings($this->acsettings);
	}

	//FEB25
	public function setTransactions($type,$id,$n,$jeid=null) {
		
		$banks = $this->bank->activeBankList();
		$jobs = $this->jobmaster->activeJobmasterList();
		$cat = ($type=='PDC')?'PDCI':'BANK';
		$accounts = DB::table('account_master')->where('category',$cat)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')
						->select('id','master_name','category')->first();
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
		
		return view('body.supplierpayment.transactions')
							->withBanks($banks)
							->withJobs($jobs)
							->withIsdept($is_dept)
							->withDepartments($departments)
							->withAcdata($acdata)
							->withNum($n)
							->withType($type)
							->withAccounts($accounts)
							->withJeid($jeid);
	}

}

