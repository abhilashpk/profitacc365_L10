<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Bank\BankInterface;
use App\Repositories\Currency\CurrencyInterface;
use App\Repositories\VoucherNo\VoucherNoInterface;
use App\Repositories\Jobmaster\JobmasterInterface;
use App\Repositories\Department\DepartmentInterface;
use App\Repositories\AccountSetting\AccountSettingInterface;
use App\Repositories\SalesInvoice\SalesInvoiceInterface;
use App\Repositories\ReceiptVoucher\ReceiptVoucherInterface;
use App\Repositories\Forms\FormsInterface;
use App\Repositories\Salesman\SalesmanInterface;

use App\Http\Requests;
use Input;
use Session;
use Response;
use Validator;
use Auth;
use Excel;
use DB;
use App;
use Mail;
use PDF;
use Redirect;

class CustomerReceiptController extends Controller
{
    protected $bank;
	protected $currency;
	protected $voucherno;
	protected $jobmaster;
	protected $department;
	protected $accountsetting;
	protected $sales_invoice;
	protected $receipt_voucher;
	protected $forms;
	protected $formData;
	protected $salesman;
	
	
	public function __construct(ReceiptVoucherInterface $receipt_voucher, BankInterface $bank, CurrencyInterface $currency, VoucherNoInterface $voucherno, JobmasterInterface $jobmaster,SalesmanInterface $salesman, DepartmentInterface $department,AccountSettingInterface $accountsetting,SalesInvoiceInterface $sales_invoice, FormsInterface $forms) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->middleware('auth');
		$this->bank = $bank;
		$this->currency = $currency;
		$this->voucherno = $voucherno;
		$this->jobmaster = $jobmaster;
		$this->department = $department;
		$this->accountsetting = $accountsetting;
		$this->sales_invoice = $sales_invoice;
		$this->receipt_voucher = $receipt_voucher;
		$this->salesman = $salesman;
		$this->forms = $forms;
		$this->formData = $this->forms->getFormData('RV');
	}
	
	/* protected function makePDCRBill($result)
	{
		$childs = array();
		foreach($result as $item) {
			if($item->cheque_no!='' && $item->code!='')
				$childs[$item->customer_id][] = $item;
		}
		return $childs;
	} */
	
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
		/* $pdcrs = $this->makePDCRBill( $this->receipt_voucher->PDCReceivedList($this->acsettings) );
		echo '<pre>';print_r($pdcrs);exit; */
		
		//$receipts = $this->customer_receipt->CustomerReceiptList();
		$receipts = [];//$this->receipt_voucher->CustomerReceiptList();
		return view('body.customerreceipt.index')
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
                            0 =>'receipt_voucher.id', 
                            1 =>'voucher_no',
							2 =>'voucher_type',
                            3=> 'voucher_date',
                            4=> 'debiter',
							5=> 'creditor',
                            6=> 'amount',
                            7=>'description',
                            8=>'reference'
                        );
						
		$totalData = $this->receipt_voucher->CustomerReceiptListCount();
            
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = 'receipt_voucher.id';//$columns[$request->input('order.0.column')];
        $dir = 'desc';//$request->input('order.0.dir');
		$search = (empty($request->input('search.value')))?null:$request->input('search.value');
        
		$invoices = $this->receipt_voucher->CustomerReceiptList('get', $start, $limit, $order, $dir, $search);
		
		if($search)
			$totalFiltered =  $this->receipt_voucher->CustomerReceiptList('count', $start, $limit, $order, $dir, $search);
		$prints = DB::table('report_view_detail')
			->join('report_view','report_view.id','=','report_view_detail.report_view_id')
			->where('report_view.code','RV')
			->select('report_view_detail.name','report_view_detail.id')
			->get();
        $data = array();
        if(!empty($invoices))
        {
           
			foreach ($invoices as $row)
            {
                $edit =  '"'.url('customer_receipt/edit/'.$row->id).'"';
                $delete =  'funDelete("'.$row->id.'")';
				$print = url('customer_receipt/print2/'.$row->id);
				//$print2 = url('customer_receipt/printgrp/'.$row->id);
				//$print3 = url('customer_receipt/print/'.$row->id.'/25');
				$printgrp = url('customer_receipt/printgrp/'.$row->id);
				$editcon =  'funPdcr()';
				
                $nestedData['id'] = $row->id;
                $nestedData['voucher_no'] = $row->voucher_no;
				$nestedData['voucher_type'] = ($row->voucher_type==9)?'CASH':$row->voucher_type;
				$nestedData['voucher_date'] = date('d-m-Y', strtotime($row->voucher_date));
				$nestedData['debiter'] = $row->debiter;
				$nestedData['creditor'] = ($row->creditor!='')?$row->creditor:$row->creditor2;
				$nestedData['supplier'] = ($row->voucher_type==9)?'CASH':$row->voucher_type;
				$nestedData['amount'] = $row->amount;
				$nestedData['description'] = $row->description;
				$nestedData['reference'] = $row->reference;
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
				
				$opts = "";					
				foreach($prints as $doc) {
					$opts .= "<li role='presentation'><a href='{$print}/".$doc->id."' target='_blank' role='menuitem'>".$doc->name."</a></li>";
				}
				$opts .= "<li role='presentation'><a href='{$printgrp}' target='_blank' role='menuitem'>Print Group</a></li>";
				
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
										</div><a href='{}' target='_blank' class='btn btn-primary btn-xs'><span class='fa fa-fw fa-print'></span>FC</a>";
										
						/* $nestedData['print'] = "<p><a href='{$print}' target='_blank' class='btn btn-primary btn-xs'><span class='fa fa-fw fa-print'></span></a>
												<a href='{$printfc}' target='_blank' class='btn btn-primary btn-xs'><span class='fa fa-fw fa-print'></span>FC</a></p>"; */
					} else {
						$nestedData['print'] = "<div class='btn-group drop_btn' role='group'>
											<button type='button' class='btn btn-primary btn-xs dropdown-toggle m-r-50'
													id='exampleIconDropdown1' data-toggle='dropdown' aria-expanded='false'>
												<i class='fa fa-fw fa-print' aria-hidden='true'></i><span class='caret'></span>
											</button>
											<ul style='min-width:100px !important;' class='dropdown-menu' aria-labelledby='exampleIconDropdown1' role='menu'>
												".$opts."
											</ul>
										</div>";
						//$nestedData['print'] = "<p><a href='{$print}' target='_blank' class='btn btn-primary btn-xs'><span class='fa fa-fw fa-print'></span></a></p>";
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

		$data = array(); //echo '<pre>';print_r($this->formData);exit;
		$currency = $this->currency->activeCurrencyList();
		$banks = $this->bank->activeBankList();
		$res = $this->voucherno->getVoucherNo('CR');
		$jobs = $this->jobmaster->activeJobmasterList();
		$vno = $res->no;
		
		$vchrdata = $this->getVoucher($id=9,$type='CASH'); //echo '<pre>';print_r($vchrdata);exit;
		$lastid = $this->receipt_voucher->getLastId();	
		
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
		$vouchers = $this->accountsetting->getAccountSettingsById($vid=9,$is_dept,$deptid);
		//$vouchers = $this->accountsetting->getAccountSettingsDefault2($vid=9,$is_dept,$deptid);
		
		return view('body.customerreceipt.add')
					->withCurrency($currency)
					->withVoucherno($vno)
					->withBanks($banks)
					->withJobs($jobs)
					->withDepartments($departments)
					->withVouchers($vouchers)
					->withFormdata($this->formData)
					->withVchrdata($vchrdata)
					->withPrintid($lastid)
					->withIsdept($is_dept)
					->withDepartments($departments)
					->withDeptid($deptid)
					->withSettings($this->acsettings)
					->withData($data);
	}
	
	
	public function addAutoFill($sid) {
		
		$qry1 = DB::table('sales_invoice')->where('sales_invoice.id',$sid)
						->Join('account_transaction AS AT', function($join) {
							$join->on('AT.voucher_type_id','=','sales_invoice.id');
							$join->on('AT.account_master_id','=','sales_invoice.customer_id');
							$join->where('AT.voucher_type','=','SI');
							$join->where('AT.transaction_type','=','Dr');
							$join->where('AT.status','=',1);
							$join->where('AT.deleted_at','=','0000-00-00 00:00:00');
						})
						->select('AT.*');
						
		$qry2 = DB::table('sales_invoice')->where('sales_invoice.id',$sid)
					->join('receipt_voucher_tr AS RVT', function($join) {
						$join->on('RVT.sales_invoice_id','=','sales_invoice.id');
						$join->where('RVT.bill_type','=','SI');
						$join->where('RVT.deleted_at','=','0000-00-00 00:00:00');
					})
					->join('receipt_voucher_entry AS RVE', function($join) {
						$join->on('RVE.id','=','RVT.receipt_voucher_entry_id');
						$join->where('RVT.bill_type','=','SI');
						$join->where('RVT.status','=',1);
						$join->where('RVT.deleted_at','=','0000-00-00 00:00:00');
					})
					->Join('account_transaction AS AT', function($join) {
						$join->on('AT.voucher_type_id','=','RVE.id');
						$join->where('AT.voucher_type','=','RV');
						$join->where('AT.transaction_type','=','Cr');
						$join->where('AT.status','=',1);
						$join->where('AT.deleted_at','=','0000-00-00 00:00:00');
					})
					->select('AT.*');
					
		$trnresult = $qry1->union($qry2)->get();
		
		$balance = 0; $inv_no = $date = $type = '';
		foreach($trnresult as $row) {
			$balance = ($row->amount > $balance)?($row->amount - $balance):($balance - $row->amount);
			if($row->transaction_type=='Dr') {
				$inv_no = $row->reference; $date = date('d-m-Y',strtotime($row->invoice_date));
				$type = $row->transaction_type;
			}
				
		}
		$arrinv[] = (object)['reference_no' => $inv_no, 'tr_date' => $date,'tr_type' => $type, 'balance_amount' => $balance];
		
		$custrow = DB::table('sales_invoice')->where('sales_invoice.id',$sid)
						->join('account_master','account_master.id','=','sales_invoice.customer_id')
						->select('account_master.id','account_master.master_name')
						->first();
		
		$data = array(); //echo '<pre>';print_r($this->formData);exit;
		$currency = $this->currency->activeCurrencyList();
		$banks = $this->bank->activeBankList();
		$res = $this->voucherno->getVoucherNo('CR');
		$jobs = $this->jobmaster->activeJobmasterList();
		$vno = $res->no+1;
		
		$vchrdata = $this->getVoucher($id=9,$type='CASH'); //echo '<pre>';print_r($vchrdata);exit;
		$lastid = $this->receipt_voucher->getLastId();	
		
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
		$vouchers = $this->accountsetting->getAccountSettingsById($vid=9,$is_dept,$deptid);
		//$vouchers = $this->accountsetting->getAccountSettingsDefault2($vid=9,$is_dept,$deptid);
		
		return view('body.customerreceipt.addautofill')
					->withCurrency($currency)
					->withVoucherno($vno)
					->withBanks($banks)
					->withJobs($jobs)
					->withDepartments($departments)
					->withVouchers($vouchers)
					->withFormdata($this->formData)
					->withVchrdata($vchrdata)
					->withPrintid($lastid)
					->withIsdept($is_dept)
					->withDepartments($departments)
					->withDeptid($deptid)
					->withSettings($this->acsettings)
					->withData($data)
					->withCustrow($custrow)
					->withOsbills($arrinv)
					->withSiid($sid)
					->withAmount($balance);
	}
	
	public function save(Request $request) { //echo '<pre>';print_r($request->all());exit;
		
		if( $this->validate(
			$request, 
			['amount' => 'required',
			 'customer_account' => 'required','customer_id' => 'required',
			 'cheque_no' => ($request->get('voucher_type')=='PDCR')?'required':'', 
			 'cheque_date' => ($request->get('voucher_type')=='PDCR')?'required':'',
			 'bank_id' => ($request->get('voucher_type')=='PDCR')?'required':'',
			 'tag'  => ($request->get('on_amount')=='')?'array|min:1|required':'',
			 //'line_amount.*' => 'array|min:1|required',
			 'credit' => 'required|same:debit'
			],
			['amount.required' => 'Amount is required.',
			 'customer_account.required' => ' account is required.','customer_id.required' => 'Customer account is invalid.',
			 'cheque_no' => 'Cheque no required.',
			 'cheque_date' => 'Cheque date required.',
			 'bank_id' => 'Bank required.',
			 'tag.required'   => 'Select at least one invoice bill.',
			 //'line_amount.*' => 'Invoice assign amount is required.',
			 'credit.same' => 'Debit and Credit amount should be equal.'
			]
		)) {

			return redirect('customer_receipt/add')->withInput()->withErrors();
		}
		
		/* $validator = Validator::make($request->all(), [
            'voucher_no' => 'required|max:255',
			'debit' => 'required|same:credit'
        ]);
		
		if ($validator->fails()) {
            return redirect('customer_receipt/add')
                        ->withErrors($validator)
                        ->withInput();
        } */
		$attributes	= Input::all();
		$id=$this->receipt_voucher->create(Input::all());

		if($attributes['send_email']==1) {
			$data['crrow']= DB::table('receipt_voucher')
		            ->where('receipt_voucher.id',$id)
					->join('users', function($join) {
						$join->on('users.id','=','receipt_voucher.created_by');
						})	
						->where('receipt_voucher.status', 1)
						->where('receipt_voucher.deleted_at', '0000-00-00 00:00:00')		 
						->select('receipt_voucher.*','users.name')->first();		
			$data['invoicerow'] = $this->receipt_voucher->findRVdata($id);
		
			$email='numaktech@gmail.com';
			$no=$data['crrow']->voucher_no;
			$body='Receipt Voucher created with voucher no: %s';
			$text= sprintf($body,$no);	

			try{
					Mail::send(['html'=>'body.customerreceipt.emailadd'], $data,function($message) use ($email,$text) {
					$message->from(env('MAIL_USERNAME'));	
					$message->to($email);
					$message->subject($text);
					});
				
			} catch(JWTException $exception) {
				$this->serverstatuscode = "0";
				$this->serverstatusdes = $exception->getMessage();
				echo '<pre>';print_r($this->serverstatusdes);exit;
			}
		}

		if( $id ){
			Session::flash('message', 'Customer receipt added successfully.');
		} else
			Session::flash('error', 'Receipt entry validation error! Please try again.');
		
		return redirect('customer_receipt/add');
		
	}
	
	public function edit($id) { 
		//echo '<pre>';print_r($id);exit;
		$data = array();
		$currency = $this->currency->activeCurrencyList();
		$banks = $this->bank->activeBankList();
		$jobs = $this->jobmaster->activeJobmasterList();
		$departments = $this->department->activeDepartmentList();
		$vouchers = $this->accountsetting->getAccountSettingsById($vid=9);
	    //$csedit = DB::table('salesman')->where('id',$id)->first();
		$crrow = $this->receipt_voucher->find($id);
		$invoicerow = $this->receipt_voucher->findRVEtryData($id); 
		//$invoicerow = $this->receipt_voucher->findRVEtryData($id); 
		//$invoicetrrow = $this->receipt_voucher->findRVTrdata($id); 
		$salesman = DB::table('receipt_voucher')
		          ->leftjoin('salesman AS S', 'S.id', '=', 'receipt_voucher.salesman_id')->select('receipt_voucher.*','S.name AS salesman')
		           ->where('receipt_voucher.id',$id)->first();
		//echo '<pre>';print_r($salesman);exit;
		$invoices = $this->sales_invoice->getCustomerInvoice($invoicerow[1]->account_id,'edit');
		$openbalances = $this->sales_invoice->getOpenBalances($invoicerow[1]->account_id,'edit'); 
		//echo '<pre>';print_r($invoicerow);exit;
		
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
			->where('report_view.code','RV')
			->where('report_view_detail.is_default',1)
			->select('report_view_detail.name','report_view_detail.id')
			->get();
		
		$vouchertype = $this->accountsetting->getAccountSettings(9);
		//$view = ($crrow->from_jv==0)?'edit':'editjv';  $this->formData
		$view ='edit'; //'edit-rv';
		return view('body.customerreceipt.'.$view)
					->withCurrency($currency)
					->withBanks($banks)
					->withJobs($jobs)
					->withSalesman($salesman)
					->withCrrow($crrow)
					->withDepartments($departments)
					->withVouchers($vouchers)
					->withInvoices($invoices)
					->withOpenbalances($openbalances)
					->withInvoicerow($invoicerow)
					//->withInvoicetrrow($invoicetrrow)
					->withVouchertype($vouchertype)
					->withIsdept($is_dept)
					->withFormdata($this->formData)
					->withPrints($prints)
					->withSettings($this->acsettings)
					->withData($data);
	}
	
	public function update(Request $request, $id)
	{
		$validator = Validator::make($request->all(), [
			'debit' => 'required|same:credit'
        ]);
		
		if ($validator->fails()) {
            return redirect('receipt_voucher/edit/'.$id)
                        ->withErrors($validator)
                        ->withInput();
        }
		$attributes	= Input::all();
		//echo '<pre>';print_r(Input::all());exit;
		if( $this->receipt_voucher->update($id, Input::all()) ){
		    
        if($attributes['send_email']==1) {
			### Mail 
			$data['crrow']= DB::table('receipt_voucher')
		            ->where('receipt_voucher.id',$id)
					->join('users', function($join) {
						$join->on('users.id','=','receipt_voucher.modify_by');
						})	
						->where('receipt_voucher.status', 1)
						->where('receipt_voucher.deleted_at', '0000-00-00 00:00:00')		 
						->select('receipt_voucher.*','users.name')->first();		
		$data['invoicerow'] = $this->receipt_voucher->findRVdata($id);
		//$data['words']= $this->number_to_word($crrow->debit);
		//echo '<pre>';print_r($data['crrow']);exit;
		$email='numaktech@gmail.com';
		$no=$data['crrow']->voucher_no;
		$body='Receipt Voucher modified with voucher no: %s';
		 $text= sprintf($body,$no);						
			try{
					Mail::send(['html'=>'body.customerreceipt.emailupdate'], $data,function($message) use ($email,$text) {
					$message->from(env('MAIL_USERNAME'));	
					$message->to($email);
					$message->subject($text);
					});
				
				}catch(JWTException $exception){
				$this->serverstatuscode = "0";
				$this->serverstatusdes = $exception->getMessage();
				echo '<pre>';print_r($this->serverstatusdes);exit;
			}
		###End
        }
			Session::flash('message', 'Customer receipt updated successfully');
		}else
			Session::flash('error', 'Receipt entry validation error! Please try again.');
		if(isset($attributes['from_rv'])) 
		
		return redirect('receipt_voucher');
		else
		return redirect('customer_receipt');
	}
	
	public function getVoucher($id,$type,$dpt=null) {
		
		 $row = $this->accountsetting->getDrVoucherByID2($id,$dpt);//echo '<pre>';print_r($row);exit;
		 if($row) { 
			 if($row->voucher_no != '' || $row->voucher_no != null) {
				 if($row->is_prefix==0) {
					 $voucher = $row->voucher_no;
					 $is_prefix = 0;
					 $prefix = '';
					 $no = (int)$row->voucher_no;
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
									'number' => $no,
									'is_prefix' => $is_prefix,
									'prefix' => $prefix,
									'id' => $id);
		 } else
			 return null;
		
	}
		
	public function getVoucherJV($id,$type) {
		
		 $row = $this->accountsetting->getDrVoucherByID2($id);
		 //echo '<pre>';print_r($row);exit;
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
	
		public function indexrv() {
		$data = array();
		$salesmans = $this->salesman->getSalesmanList();
			//DEPT CHECK...
		if(Session::get('department')==1) {
			$departments = DB::table('department')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
			$is_dept = true;
		} else {
			$departments = []; $is_dept = false;
		}
		$receipts = [];
		return view('body.receiptvoucher.index')
					->withReceipts($receipts)
					->withSalesman($salesmans)
					->withDepartments($departments)
					->withSettings($this->acsettings)
					->withData($data);
	}
	
	public function ajaxPagingrv(Request $request)
	{
		$columns = array( 
                            0 =>'receipt_voucher.id', 
                            1 =>'voucher_no',
							2 =>'voucher_type',
                            3=> 'voucher_date',
                            4=> 'debiter',
							5=> 'creditor',
                            6=> 'amount',
                            7=> 'description',
                            8=>'reference'
                        );
						
		$totalData = $this->receipt_voucher->CustomerReceiptListCount();
            
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = 'receipt_voucher.id';//$columns[$request->input('order.0.column')];
        $dir = 'desc';//$request->input('order.0.dir');
		$search = (empty($request->input('search.value')))?null:$request->input('search.value');
        
		$invoices = $this->receipt_voucher->CustomerReceiptList('get', $start, $limit, $order, $dir, $search);
		
		if($search)
			$totalFiltered =  $this->receipt_voucher->CustomerReceiptList('count', $start, $limit, $order, $dir, $search);
		$prints = DB::table('report_view_detail')
			->join('report_view','report_view.id','=','report_view_detail.report_view_id')
			->where('report_view.code','RV')
			->select('report_view_detail.name','report_view_detail.id')
			->get();
        $data = array();
        if(!empty($invoices))
        {
           
			foreach ($invoices as $row)
            {
                $edit =  '"'.url('receipt_voucher/edit/'.$row->id).'"';
                $delete =  'funDelete("'.$row->id.'")';
				$print = url('customer_receipt/print2/'.$row->id);
				//$print2 = url('customer_receipt/printgrp/'.$row->id);
				//$print3 = url('customer_receipt/print/'.$row->id.'/25');
				$printgrp = url('receipt_voucher/printgrprv/'.$row->id);
				$editcon =  'funPdcr()';
				
                $nestedData['id'] = $row->id;
                $nestedData['voucher_no'] = $row->voucher_no;
				$nestedData['voucher_type'] = ($row->voucher_type==9)?'CASH':$row->voucher_type;
				$nestedData['voucher_date'] = date('d-m-Y', strtotime($row->voucher_date));
				$nestedData['description'] = $row->description;
				$nestedData['debiter'] = $row->debiter;
				$nestedData['creditor'] = ($row->creditor!='')?$row->creditor:$row->creditor2;
				$nestedData['supplier'] = ($row->voucher_type==9)?'CASH':$row->voucher_type;
				$nestedData['amount'] = $row->amount;
				$nestedData['reference'] = $row->reference;
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
				
				$opts = "";					
				foreach($prints as $doc) {
					$opts .= "<li role='presentation'><a href='{$print}/".$doc->id."' target='_blank' role='menuitem'>".$doc->name."</a></li>";
				}
				$opts .= "<li role='presentation'><a href='{$printgrp}' target='_blank' role='menuitem'>Print Group</a></li>";
				
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
										</div><a href='{}' target='_blank' class='btn btn-primary btn-xs'><span class='fa fa-fw fa-print'></span>FC</a>";
										
						/* $nestedData['print'] = "<p><a href='{$print}' target='_blank' class='btn btn-primary btn-xs'><span class='fa fa-fw fa-print'></span></a>
												<a href='{$printfc}' target='_blank' class='btn btn-primary btn-xs'><span class='fa fa-fw fa-print'></span>FC</a></p>"; */
					} else {
						$nestedData['print'] = "<div class='btn-group drop_btn' role='group'>
											<button type='button' class='btn btn-primary btn-xs dropdown-toggle m-r-50'
													id='exampleIconDropdown1' data-toggle='dropdown' aria-expanded='false'>
												<i class='fa fa-fw fa-print' aria-hidden='true'></i><span class='caret'></span>
											</button>
											<ul style='min-width:100px !important;' class='dropdown-menu' aria-labelledby='exampleIconDropdown1' role='menu'>
												".$opts."
											</ul>
										</div>";
						/*$nestedData['print'] = "<p><a href='{$printgrp}' target='_blank' class='btn btn-primary btn-xs'><span class='fa fa-fw fa-print'></span></a></p>";*/
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
	
	
	
	public function addjrv() {
			
		//echo '<pre>';print_r($vouchertype);exit;
		$data = array();
		$currency = $this->currency->activeCurrencyList();
		$banks = $this->bank->activeBankList();
		$jobs = $this->jobmaster->activeJobmasterList();
		//$departments = $this->department->activeDepartmentList();
		$account = $this->accountsetting->getExpenseAccount();
		//$lastid = $this->journal->getLastId();
		$lastid = $this->receipt_voucher->getLastId();	
		$prints = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','JV')
							->select('report_view_detail.name','report_view_detail.id')
							->get();
		$isjv = false;
		$vouchers = $this->accountsetting->getAccountSettingsById($vid=9); 
		if(sizeof($vouchers)==0)
		    $isjv = true;
		
		$vchrdata = $this->getVoucherJV($id=9,$type='CASH');//echo '<pre>';print_r($vchrdata);exit;
		
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
		
		/*if(sizeof($vouchers)==0)
		    $vouchers = $this->accountsetting->getAccountSettingsById($vid=9,$is_dept,$deptid);
		if(sizeof($vouchers)==0)
		    $vouchers = $this->accountsetting->getAccountSettingsById($vid=10,$is_dept,$deptid);*/
		//echo '<pre>';print_r($vchrdata);exit;
		return view('body.receiptvoucher.add')
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
	
	public function saverv(Request $request) {

		

		// --- Validation Rules ---
       $rules = [
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
        }

		//echo '<pre>';print_r(Input::all());exit;

//----------------------------------------------------------
		
		/*$validator = Validator::make($request->all(), [
            'voucher_no' => 'required|max:255',
			'debit' => 'required|same:credit'
        ]);
		
		if ($validator->fails()) {
            return redirect('receipt_voucher/add')
                        ->withErrors($validator)
                        ->withInput();
        }*/

        if( $this->receipt_voucher->create(Input::all()) )
				Session::flash('message', 'Receipt Voucher added successfully.');
			else {
				if (!Session::has('error'))
					Session::flash('error', 'Something went wrong, Payment Voucher failed to add!');
			}
				
			
			return redirect('receipt_voucher/add');
        
	}
	
		public function editrv($id) { 
		//echo '<pre>';print_r($id);exit;
		$data = array();
		$currency = $this->currency->activeCurrencyList();
		$banks = $this->bank->activeBankList();
		$jobs = $this->jobmaster->activeJobmasterList();
		$departments = $this->department->activeDepartmentList();
		$vouchers = $this->accountsetting->getAccountSettingsById($vid=9);
	    //$csedit = DB::table('salesman')->where('id',$id)->first();
		$crrow = $this->receipt_voucher->find($id);
		$invoicerow = $this->receipt_voucher->findRVEtryData($id); 
		//$invoicerow = $this->receipt_voucher->findRVEtryData($id); 
		//$invoicetrrow = $this->receipt_voucher->findRVTrdata($id); 
		$salesman = DB::table('receipt_voucher')
		          ->leftjoin('salesman AS S', 'S.id', '=', 'receipt_voucher.salesman_id')->select('receipt_voucher.*','S.name AS salesman')
		           ->where('receipt_voucher.id',$id)->first();
		//echo '<pre>';print_r($salesman);exit;
		$invoices = $this->sales_invoice->getCustomerInvoice($invoicerow[1]->account_id,'edit');
		$openbalances = $this->sales_invoice->getOpenBalances($invoicerow[1]->account_id,'edit'); 
		//echo '<pre>';print_r($invoicerow);exit;
		
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
			->where('report_view.code','RV')
			->where('report_view_detail.is_default',1)
			->select('report_view_detail.name','report_view_detail.id')
			->get();
		
		$vouchertype = $this->accountsetting->getAccountSettings(9);
		//$view = ($crrow->from_jv==0)?'edit':'editjv';  $this->formData
		$view = 'edit-rv';
		return view('body.receiptvoucher.edit')
					->withCurrency($currency)
					->withBanks($banks)
					->withJobs($jobs)
					->withSalesman($salesman)
					->withCrrow($crrow)
					->withDepartments($departments)
					->withVouchers($vouchers)
					->withInvoices($invoices)
					->withOpenbalances($openbalances)
					->withInvoicerow($invoicerow)
					//->withInvoicetrrow($invoicetrrow)
					->withVouchertype($vouchertype)
					->withIsdept($is_dept)
					->withFormdata($this->formData)
					->withSettings($this->acsettings)
					->withPrints($prints)
					->withData($data);
	}
	
	public function getGrpPrintrv($id)
	{
		$voucherhead = 'Receipt Voucher';
		$crrow = $this->receipt_voucher->find($id); 
		$invoicerow = $this->receipt_voucher->findRVdata($id); //echo '<pre>';print_r($invoicerow);exit;
				
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
		
		return view('body.receiptvoucher.printgrp')
					->withVoucherhead($voucherhead)
					->withDetails($crrow)
					->withInvoicerow($invoicerow)
					->withAmtwords($words);
	}
	
	
	
	public function destroy($id,$from)
	{
		$status = $this->receipt_voucher->check_RV($id); //echo $status;exit;
		if($status) {
			if( $this->receipt_voucher->delete($id) ) {
				DB::table('pdc_received')->where('voucher_id',$id)->where('status',0)->update(['deleted_at' => date('Y-m-d H:i:s')]);
				Session::flash('message', 'Customer Receipt deleted successfully.');
			} else
				Session::flash('error', 'Something went wrong, Customer receipt failed to delete!');
		} else {
			Session::flash('error', 'Customer Receipt is already in use, you can\'t delete this!');
		}
		if($from=='cr')
			return redirect('customer_receipt');
		else
			return redirect('receipt_voucher');
	}
	
		
	public function checkVchrNo() {

		$check = $this->receipt_voucher->check_voucher_no(Input::get('voucher_no'), Input::get('id'));
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
			$titles = ['main_head' => 'Tax Invoice','subhead' => 'Tax Invoice'];//echo '<pre>';print_r($result);exit;
			$crrow = $this->receipt_voucher->find($id); 
			$invoicerow = $this->receipt_voucher->findRVdata($id);
		    $view = 'print';
			$voucherhead = 'Receipt Voucher';
			
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
			return view('body.customerreceipt.'.$view)
						->withVoucherhead($voucherhead)
			            ->withTitles($titles)
			 			->withAmtwords($words)
		                 ->withDetails($crrow)
			 			->withInvoicerow($invoicerow)
			 			->withFormdata($this->formData)
						->withDisarr($disArr)
						->withCurrency($crow->code)
			 			->withId($id);
						
		} else {
					
			$path = app_path() . '/stimulsoft/helper.php';
			if(env('STIMULSOFT_VER')==2)
			        return view('body.reports')->withPath($path)->withView($viewfile->print_name);
			   else
			        return view('body.customerreceipt.viewer')->withPath($path)->withView($viewfile->print_name);
			
		}
		
	}




	public function getPrint2old($id)
	{
		$voucherhead = 'Receipt Voucher';
		$crrow = $this->receipt_voucher->find($id); 
		$invoicerow = $this->receipt_voucher->findRVdata($id); //echo '<pre>';print_r($invoicerow);exit;
				
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
		
		return view('body.customerreceipt.print')
					->withVoucherhead($voucherhead)
					->withDetails($crrow)
					->withInvoicerow($invoicerow)
					->withAmtwords($words);
	}
	
	
	
	public function getGrpPrint($id)
	{
		$voucherhead = 'Receipt Voucher';
		$crrow = $this->receipt_voucher->find($id); 
		$invoicerow = $this->receipt_voucher->findRVdata($id); //echo '<pre>';print_r($invoicerow);exit;
				
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
		
		return view('body.customerreceipt.printgrp')
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
	

	public function getPrintold2($id,$rid=null)
	{ 
		$viewfile = DB::table('report_view_detail')->where('id', $rid)->select('print_name')->first(); 
		
		if($viewfile->print_name=='') {
			$fc='';
			$attributes['document_id'] = $id; //echo "892 : ".$this->number_to_word(12495);exit;
			$attributes['is_fc'] = ($fc)?1:'';
			$titles = ['main_head' => 'Tax Invoice','subhead' => 'Tax Invoice'];//echo '<pre>';print_r($result);exit;
			$crrow = $this->receipt_voucher->find($id); 
			$invoicerow = $this->receipt_voucher->findRVdata($id);
		    $view = 'print';
			$voucherhead = 'Receipt Voucher';
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
			
			
			return view('body.customerreceipt.'.$view)
						->withVoucherhead($voucherhead)
			            ->withTitles($titles)
			 			->withAmtwords($words)
		                 ->withDetails($crrow)
			 			->withInvoicerow($invoicerow)
			 			->withFormdata($this->formData)
			 			->withId($id);
						
		} else {
					
			$path = app_path() . '/stimulsoft/helper.php';
			return view('body.customerreceipt.viewer')->withPath($path)->withView($viewfile->print_name);
		}
		
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
	protected function makeTreeName($result)
	{
		$childs = array();
		foreach($result as $item)
		$childs[$item->creditor][] = $item;
		
		return $childs;
	}	
public function getSearch()
	{
	   // echo '<pre>';print_r(Input::all());exit;
		$data = array();
		$dname = '';
		$supid = $itemid = '';
		$voucher_head  = '';
		//echo '<pre>';print_r(Input::get('search_type'));exit;
	    if(Input::get('search_type')=="summary")
		{
			$voucher_head = 'Customer Receipt Summary';
			$report = $this->receipt_voucher->getReport(Input::all());
		//	echo '<pre>';print_r($reports);exit;
			$reports = $this->makeTreeName($report);
			//echo '<pre>';print_r($reports);exit;
			$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];
			if(Input::get('supplier_id')!==null)
				$supid = implode(',', Input::get('supplier_id'));
			else
				$supid = '';
			}
		
		else if(Input::get('search_type')=="detail") {
			$voucher_head = 'Customer Receipt Detail';
			$report = $this->receipt_voucher->getReport(Input::all());
		    $reports = $this->makeTreeName($report);
		  //  echo '<pre>';print_r($reports);exit;
			$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];
			
		} else if(Input::get('search_type')=="item") {
			$voucher_head = 'Purchase Invoice by Itemwise';
			$report = $this->purchase_invoice->getReport(Input::all());
			$reports = $this->groupbyItemwise($report);
			$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];
			//echo '<pre>';print_r($reports);exit;
			if(Input::get('item_id')!==null)
				$itemid = implode(',', Input::get('item_id'));
			else
				$itemid = '';
		
		//else if(Input::get('search_type')=="tax_code") {
			//$voucher_head = 'Purchase Invoice by Tax Code';
			//$reports = $this->makeTreeTC($reports);
		//}
	}else if(Input::get('search_type')=='supplier') {
	//	echo '<pre>';print_r($reports);exit;
			$voucher_head = 'Purchase Invoice by supplierwise';
			
		    $reports = $this->makeTreeSup($report);
			$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];
			if(Input::get('supplier_id')!==null)
				$supid = implode(',', Input::get('supplier_id'));
			else
				$supid = '';
		}
		//echo '<pre>';print_r($reports);exit;
		return view('body.customerreceipt.preprint')
					->withReports($reports)
					->withVoucherhead($voucher_head)
					->withType(Input::get('search_type'))
					->withFromdate(Input::get('date_from'))
					->withTodate(Input::get('date_to'))
					->withI(0)
					->withTitles($titles)
					->withIsimport(Input::get('isimport'))
					->withSettings($this->acsettings)
					->withSupplier($supid)
					->withItem($itemid)
					->withDname($dname)
					->withData($data);
	}
	
	
public function dataExport()
	{
		$data = array();
		$datareport[] = [strtoupper(Session::get('company')),'','',''];
		$datareport[] = ['','','','','','',''];
		
		Input::merge(['type' => 'export']);
	//	$reports = $this->purchase_invoice->getReportExcel(Input::all());
		
		if(Input::get('search_type')=="summary")
		{
			$voucher_head = 'Customer Receipt Summary';
			$reports = $this->receipt_voucher->getReport(Input::all());
		
				$datareport[] = ['','','','',strtoupper($voucher_head), '','',''];
		     $datareport[] = ['','','','','','',''];
		
			$datareport[] = [ 'Customer Account','Net Total'];
			$i=0;
			foreach ($reports as $row) {
					$i++;
					$datareport[] = [ 
									 'cus account ' => $row['creditor'],
									 
									 
									  'total' => $row['amount']
									];
			}
		}
		elseif(Input::get('search_type')=="detail") {
			$voucher_head = 'Customer Receipt Detail';
			$reports = $this->receipt_voucher->getReport(Input::all());
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
		/* if(Input::get('search_type')=='purchase_register') {
			
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
	
	
	public function getDeptVoucher($id) {
		
		$type='CASH';
		 $rows = $this->accountsetting->getVoucherByDeptRV($vid=9, $id); //return $row;//print_r($row);
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

	public function addRV() {

		$data = array(); //echo '<pre>';print_r($this->formData);exit;
		$currency = $this->currency->activeCurrencyList();
		$banks = $this->bank->activeBankList();
		$res = $this->voucherno->getVoucherNo('CR');//echo '<pre>';print_r($res);exit;
		$jobs = $this->jobmaster->activeJobmasterList();
		$vno = $res->no;
		
		$vchrdata = $this->getVoucher($id=9,$type='CASH'); //echo '<pre>';print_r($vno);exit;
		$lastid = $this->receipt_voucher->getLastId();	
		
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
		$vouchers = $this->accountsetting->getAccountSettingsById($vid=9,$is_dept,$deptid);
		//$vouchers = $this->accountsetting->getAccountSettingsDefault2($vid=9,$is_dept,$deptid);
		
		return view('body.customerreceipt.add-rv')
					->withCurrency($currency)
					->withVoucherno($vno)
					->withBanks($banks)
					->withJobs($jobs)
					->withDepartments($departments)
					->withVouchers($vouchers)
					->withFormdata($this->formData)
					->withVchrdata($vchrdata)
					->withPrintid($lastid)
					->withIsdept($is_dept)
					->withDepartments($departments)
					->withDeptid($deptid)
					->withSettings($this->acsettings)
					->withData($data);
	}

	public function setTransactions($type,$id,$n,$jeid=null) {
		
		$banks = $this->bank->activeBankList();
		$jobs = $this->jobmaster->activeJobmasterList();
		$cat = ($type=='PDC')?'PDCR':'BANK';
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
		
		return view('body.customerreceipt.transactions')
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
