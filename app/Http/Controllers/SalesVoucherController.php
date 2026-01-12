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

use App\Http\Requests;
use Session;
use Response;
use Auth;
use DB;
use App;

class SalesVoucherController extends Controller
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
	}
	
	public function index() {
		$data = array();
		$journals = [];//$this->journal->journalListCommon('SIN');//echo '<pre>';print_r($journals);exit;

		/*$prints = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','SVR')
							->select('report_view_detail.name','report_view_detail.id')
							->get();*/
		return view('body.salesvoucher.index')
					->withJournals($journals)
					//->withPrints($prints)
					->withData($data);
	}
	public function ajaxPaging(Request $request)
	{
		$columns = array( 
                            
                            0 =>'voucher_no',
							1 =>'description',
                            3=> 'voucher_date',
                            4=> 'amount',
                            5=>'reference'
                        );
						
		//$totalData = $this->journal->journalListCount();
            
        //$totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = 'journal.id';//$columns[$request->input('order.0.column')];
        $dir = 'desc';//$request->input('order.0.dir');
		$search = (empty($request->input('search.value')))?null:$request->input('search.value');
        
		$totalData =  $this->journal->journalListSV('count', $start, $limit, $order, $dir, $search);
		$totalFiltered = $totalData; 
		
		$invoices = $this->journal->journalListSV('get', $start, $limit, $order, $dir, $search);
		
		if($search)
			$totalFiltered =  $this->journal->journalListSV('count', $start, $limit, $order, $dir, $search);
		
		
		$prints = DB::table('report_view_detail')
			->join('report_view','report_view.id','=','report_view_detail.report_view_id')
			->where('report_view.code','SVR')
			->select('report_view_detail.name','report_view_detail.id')
			->get();
			
        $data = array();
        if(!empty($invoices))
        {
			foreach ($invoices as $row)
            {
                $edit =  '"'.url('sales_voucher/edit/'.$row->id).'"';
                $delete =  'funDelete("'.$row->id.'")';
				$print = url('sales_voucher/print/'.$row->id.'/'.$prints[0]->id);
                $nestedData['id'] = $row->id;
                $nestedData['voucher_no'] = $row->voucher_no;
				$nestedData['voucher_date'] = date('d-m-Y', strtotime($row->voucher_date));
				$nestedData['description'] = $row->description;
				$nestedData['reference'] = $row->reference;
				$nestedData['amount'] = number_format($row->credit,2);
				
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
	
	
	public function add() {

		$data = array();
		$currency = $this->currency->activeCurrencyList();
		$banks = $this->bank->activeBankList();
		$jobs = $this->jobmaster->activeJobmasterList();
		$account = $this->accountsetting->getIncomeAccount();
		//$vouchers = $this->accountsetting->getAccountSettingsDefault2($vid=6);
		//echo '<pre>';print_r($account);exit;
		$prints = DB::table('report_view_detail')
			->join('report_view','report_view.id','=','report_view_detail.report_view_id')
			->where('report_view.code','SVR')
			->select('report_view_detail.name','report_view_detail.id')
			->get();
		$lastid = DB::table('journal')->where('status',1)->where('voucher_type','SIN')
					->select('id')
					->orderBY('id', 'DESC')
					->first();	
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
		$vouchers = $this->accountsetting->getAccountSettingsDefault2($vid=6,$is_dept,$deptid);
		
		return view('body.salesvoucher.add')
					->withCurrency($currency)
					->withBanks($banks)
					->withJobs($jobs)
					->withAccount($account)
					->withDepartments($departments)
					->withVouchers($vouchers)
					->withIsdept($is_dept)
					->withDepartments($departments)
					->withDeptid($deptid)
					->withPrints($prints)
					->withPrintid($lastid)
					->withData($data);
	}
	
	public function save(Request $request) {
		try { //echo '<pre>';print_r($request->all());exit;
			if($request->get('voucher_type')==9) {
				$this->receipt_voucher->create($request->all());
				Session::flash('message', 'Customer receipt added successfully.');
				return redirect('sales_voucher/add'); //return redirect('customer_receipt');
			} else if($request->get('voucher_type')==10) {
				$this->payment_voucher->create($request->all());
				Session::flash('message', 'Supplier payment added successfully.');
				return redirect('sales_voucher/add'); //return redirect('supplier_payment');
			} else {
				$this->journal->createSIN($request->all());//exit;
				Session::flash('message', 'Sales voucher added successfully.');
				return redirect('sales_voucher/add');
			}
		} catch(ValidationException $e) { 
			return Redirect::to('sales_voucher/add')->withErrors($e->getErrors());
		}
	}
	
	public function edit($id) { 

		$data = array();
		$currency = $this->currency->activeCurrencyList();
		$banks = $this->bank->activeBankList();
		$jobs = $this->jobmaster->activeJobmasterList();
		$departments = $this->department->activeDepartmentList();
				
		$jrow = $this->journal->find($id);
		$vouchertype = $this->accountsetting->getAccountSettings( $this->getVid($jrow->voucher_type) );
		$jerow = $this->journal->findJEdata($id);
		//echo '<pre>';print_r($jrow);
		//echo '<pre>';print_r($vouchertype);exit;
		$prints = DB::table('report_view_detail')
			->join('report_view','report_view.id','=','report_view_detail.report_view_id')
			->where('report_view.code','SVR')
			->select('report_view_detail.name','report_view_detail.id')
			->get();
		//CHECK DEPARTMENT.......
		if(Session::get('department')==1) { //if active...
			$deptid = Auth::user()->department_id;
			if($deptid!=0)
				$departments = DB::table('department')->where('id',$deptid)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
			else {
				$departments = DB::table('department')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
				$deptid = $jrow->department_id;
			}
			$is_dept = true;
		} else {
			$is_dept = false;
			$departments = [];
			$deptid = '';
		}
		$vouchers = $this->accountsetting->getAccountSettingsDefault2($vid=6,$is_dept,$deptid);
		return view('body.salesvoucher.edit')
					->withJrow($jrow)
					->withCurrency($currency)
					->withBanks($banks)
					->withJobs($jobs)
					->withJerow($jerow)
					->withDepartments($departments)
					->withVouchertype($vouchertype)
					->withVouchers($vouchers)
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
	
	public function update(Request $request, $id)
	{
		$this->journal->update($id, $request->all());//echo '<pre>';print_r($request->all());exit;
		Session::flash('message', 'Sales voucher updated successfully');
		return redirect('sales_voucher');
	}
	
	public function destroy($id)
	{
		$this->journal->delete($id);
		
		Session::flash('message', 'Journal voucher deleted successfully.');
		return redirect('journal');
	}
	
	public function getVoucher($id) {
		
		 $row = $this->accountsetting->getDrVoucherByID($id);
		 if($row->voucher_no != '' || $row->voucher_no != null) {
			 if($row->is_prefix==0)
				 $voucher = $row->voucher_no+1;
			 else {
				 $no = (int)$row->voucher_no+1;
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
	
	public function checkVchrNo(Request $request) {

		$check = $this->journal->check_voucher_no($request->get('voucher_no'), $request->get('vtype'), $request->get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
	
	public function getDeptVoucher($id) {
		
		$depts = $this->accountsetting->getVoucherByDeptSN($vid=6, $id); 
		
		foreach($depts as $row) {
			
			 if($row->voucher_no != '' || $row->voucher_no != null) {
				 if($row->is_prefix==0)
					 $voucher = $row->voucher_no;
				 else {
					 $no = (int)$row->voucher_no;
					 $voucher = $row->prefix.''.$no;
				 }
			 }
			 
			  $result[] = array('voucher_no' => $voucher, 
								'voucher_name' => $row->voucher_name,
								'voucher_id' => $row->voucher_id
							);

		}
		
		return $result;
	}
	
	public function getPrint($id,$rid=null)
	{ 
		
        $viewfile = DB::table('report_view_detail')->where('id', $rid)->select('print_name')->first(); 
			
		if($viewfile->print_name=='') {
			$fc='';
			$attributes['document_id'] = $id; //echo "892 : ".$this->number_to_word(12495);exit;
			$attributes['is_fc'] = ($fc)?1:'';
		$voucherhead = 'Sales Voucher(Non-Stock)';
		$jvrow = $this->journal->find($id); 
		$jerow = $this->journal->findJEdata($id); //echo '<pre>';print_r($jerow);exit;

		return view('body.salesvoucher.print')
					->withVoucherhead($voucherhead)
					->withDetails($jvrow)
					->withJerow($jerow);
		}else {
					
			$path = app_path() . '/stimulsoft/helper.php';
			return view('body.salesvoucher.viewer')->withPath($path)->withView($viewfile->print_name);
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
		
		return view('body.salesvoucher.transactions')
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

