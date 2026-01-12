<?php

namespace App\Http\Controllers;
use App\Repositories\Area\AreaInterface;
use App\Repositories\Itemmaster\ItemmasterInterface;
use App\Repositories\Terms\TermsInterface;
use App\Repositories\Jobmaster\JobmasterInterface;
use App\Repositories\AccountMaster\AccountMasterInterface;
use App\Repositories\Currency\CurrencyInterface;
use App\Repositories\VoucherNo\VoucherNoInterface;
use App\Repositories\PurchaseOrder\PurchaseOrderInterface;
use App\Repositories\SupplierDo\SupplierDoInterface;
use App\Repositories\Location\LocationInterface;

use App\Repositories\AccountSetting\AccountSettingInterface;
use App\Repositories\Country\CountryInterface;
use App\Repositories\Acgroup\AcgroupInterface;
use App\Repositories\Forms\FormsInterface;
use App\Repositories\MaterialRequisition\MaterialRequisitionInterface;
use App\Repositories\UpdateUtility;

use Illuminate\Http\Request;

use App\Http\Requests;
use Session;
use Response;
use Input;
use Excel;
use App;
use DB;
use Auth;

class ViewBudgetController extends Controller
{

	protected $area;
	protected $itemmaster;
	protected $terms;
	protected $jobmaster;
	protected $accountmaster;
	protected $currency;
	protected $voucherno;
	protected $purchase_order;
	protected $supplierdo;
	protected $location;

	protected $accountsetting;
	protected $country;
	protected $group;
	protected $forms;
	protected $formData;
	protected $material_requisition;
	protected $mod_autocost;
	
	public function __construct( SupplierDOInterface $supplierdo, PurchaseOrderInterface $purchase_order, ItemmasterInterface $itemmaster, TermsInterface $terms, JobmasterInterface $jobmaster, AccountMasterInterface $accountmaster, CurrencyInterface $currency, VoucherNoInterface $voucherno, AreaInterface $area,CountryInterface $country,AcgroupInterface $group, LocationInterface $location, AccountSettingInterface $accountsetting, FormsInterface $forms, MaterialRequisitionInterface $material_requisition) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		
		$this->middleware('auth');
		$this->area = $area;
		$this->itemmaster = $itemmaster;
		$this->terms = $terms;
		$this->jobmaster = $jobmaster;
		$this->accountmaster = $accountmaster;
		$this->currency = $currency;
		$this->voucherno = $voucherno;
		$this->purchase_order = $purchase_order;
		$this->supplierdo = $supplierdo;
		$this->location = $location;
		$this->jobmas = $jobmaster;
		$this->accountsetting = $accountsetting;
		$this->country = $country;
		$this->group = $group;
		$this->forms = $forms;
		//$this->formData = $this->forms->getFormData('PI');
		$this->material_requisition = $material_requisition;
		
		$this->mod_autocost = DB::table('parameter2')->where('keyname', 'mod_autocost_refresh')->where('status',1)->select('is_active')->first();
		$this->objUtility = new UpdateUtility();
	}
	
    public function index() {
		
		//Session::put('cost_accounting', 0);
		$data = array();
		//$this->purchase_split->InvoiceLogProcess();
		$orders = [];//$this->purchase_split->purchaseInvoiceList();
		//$customers = [];
		$suppliers =[];
		//$salesmans = $this->salesman->getSalesmanList();
		//echo '<pre>';print_r($customer);exit;
		//DEPT CHECK...
		if(Session::get('department')==1) {
			$departments = DB::table('department')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
			$is_dept = true;
		} else {
			$departments = []; $is_dept = false;
		}
		
		return view('body.viewbudget.index')
					->withOrders($orders)
					->withSupplier($suppliers)
					//->withCustomers($customers)
					->withDepartments($departments)
					->withSettings($this->acsettings)
					->withIsdept($is_dept)
					->withType('')
					->withData($data);
	}
	
	public function ajaxPaging(Request $request)
	{
		$columns = array( 
                            0 => 'purchase_split.id', 
                            1 => 'voucher_no',
							2 => 'voucher_date',
                            3 => 'jobno',
                            4 => 'supplier',
                            5 => 'net_amount'
                        );
						
		$totalData = $this->purchase_split->purchaseInvoiceListCount();
            
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')]; //'purchase_split.id';
        $dir = $request->input('order.0.dir'); //'desc';
		$search = (empty($request->input('search.value')))?null:$request->input('search.value');
		$dept = $request->input('dept');
        
		$invoices = $this->purchase_split->purchaseInvoiceList('get', $start, $limit, $order, $dir, $search, $dept);
		
		if($search)
			$totalFiltered =  $this->purchase_split->purchaseInvoiceList('count', $start, $limit, $order, $dir, $search, $dept);
		
		$prints = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','PS')
							->select('report_view_detail.name','report_view_detail.id')
							->get();
							
        $data = array();
        if(!empty($invoices))
        {
           
			foreach ($invoices as $row)
            {
                $edit =  '"'.url('purchase_split/edit/'.$row->id).'"';
                $delete =  'funDelete("'.$row->id.'")';
				$print = url('purchase_split/print/'.$row->id);
				
				
                $nestedData['id'] = $row->id;
                $nestedData['voucher_no'] = $row->voucher_no;
                $nestedData['jobno'] = $row->jobno;
				$nestedData['reference_no'] = $row->reference_no;
				$nestedData['voucher_date'] = date('d-m-Y', strtotime($row->voucher_date));
				$nestedData['supplier'] = ($row->supplier=='CASH CUSTOMERS') ? (($row->supplier_name!='')?$row->supplier.'('.$row->supplier_name.')':$row->supplier) : $row->supplier;
				$nestedData['net_total'] = $row->net_amount;
                $nestedData['edit'] = "<p><button class='btn btn-primary btn-xs' onClick='location.href={$edit}'>
												<span class='glyphicon glyphicon-pencil'></span></button></p>";
												
				$nestedData['delete'] = "<button class='btn btn-danger btn-xs delete' onClick='{$delete}'>
												<span class='glyphicon glyphicon-trash'></span>";
				
				$opts = '';					
				foreach($prints as $doc) {
					$opts .= "<li role='presentation'><a href='{$print}/".$doc->id."' target='_blank' role='menuitem'>".$doc->name."</a></li>";
				}
				$printfc = url('purchase_split/printfc/'.$row->id.'/'.$prints[0]->id);
				if($row->is_fc==1) {		
					$nestedData['print'] = "<div class='btn-group drop_btn' role='group'>
											<button type='button' class='btn btn-primary btn-xs dropdown-toggle m-r-50'
													id='exampleIconDropdown1' data-toggle='dropdown' aria-expanded='false'>
												<i class='fa fa-fw fa-print' aria-hidden='true'></i><span class='caret'></span>
											</button>
											<ul style='min-width:100px !important;' class='dropdown-menu' aria-labelledby='exampleIconDropdown1' role='menu'>
												".$opts."
											</ul>
										</div><a href='{$printfc}' target='_blank' class='btn btn-primary btn-xs'><span class='fa fa-fw fa-print'></span>FC</a>";
										
					/* $nestedData['print'] = "<p><a href='{$print}' target='_blank' class='btn btn-primary btn-xs'><span class='fa fa-fw fa-print'></span></a>
											<a href='{$print}/FC' target='_blank' class='btn btn-primary btn-xs'><span class='fa fa-fw fa-print'></span>FC</a></p>"; */
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
	
	public function add(Request $request, $id = null, $doctype = null) {
			
		$data = array();
		$itemmaster = $this->itemmaster->activeItemmasterList();
		$units = DB::table('units')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		
		$terms = $this->terms->activeTermsList();
		$jobs = $this->jobmaster->activeJobmasterList();
		$currency = $this->currency->activeCurrencyList();
		$location = $this->location->locationList();
		//$vouchers = $this->accountsetting->getAccountSettingsDefault2($vid=1);//'Purchase Stock' voucher from account settings...
		$locdefault = DB::table('location')->where('is_default',1)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id')->first();
		$pur_location = DB::table('parameter3')
							 ->join('location', 'location.id', '=', 'parameter3.location_id')
							 ->join('account_master', 'account_master.id', '=', 'parameter3.account_id')
							 ->select('location.name','location.id','account_master.master_name','account_master.id AS account_id')
							 ->get();
					 
		$lastid = DB::table('purchase_split')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->orderBy('id','DESC')->select('id')->first();
		
		$print = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','PS')
							->where('report_view_detail.is_default',1)
							->select('report_view_detail.id')
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
		
		$did = ($deptid=='')?0:$deptid;
		$vouchers = DB::table('account_setting')->where('voucher_type_id',23)->where('status',1)->where('department_id',$did)->where('deleted_at','0000-00-00 00:00:00')->get(); //'Purchase Stock' voucher from account settings...
		//echo '<pre>';print_r($vouchers);exit;
		if($id!=null) {
		    $jobmaster = DB::table('jobmaster')
							->join('account_master','account_master.id','=','jobmaster.customer_id')
							->where('jobmaster.id',$id)
							->select('jobmaster.id','jobmaster.name','jobmaster.code','jobmaster.tranSport_type','account_master.id AS account_id','account_master.master_name')
							->get(); //echo '<Pre>';print_r($jobmaster);exit;

			return view('body.purchasesplit.addjm')
					->withItems($itemmaster)
					->withUnits($units)
					->withTerms($terms)
					->withJobs($jobs)
					->withJobmaster($jobmaster)
					->withCurrency($currency)
					->withLocation($location)
					->withVouchers($vouchers)
					->withSettings($this->acsettings)
					->withVatdata($this->vatdata)
					->withSettings($this->acsettings)
					->withLocdefault($locdefault)
					->withPurlocation($pur_location)
					->withPrintid($lastid)
					->withFormdata($this->formData)
					->withPrint($print)
					->withIsdept($is_dept)
					->withDepartments($departments)
					->withDeptid($deptid)
					->withData($data);
		}
		return view('body.purchasesplit.add')
					->withItems($itemmaster)
					->withUnits($units)
					->withTerms($terms)
					->withJobs($jobs)
					->withCurrency($currency)
					->withLocation($location)
					->withVouchers($vouchers)
					->withSettings($this->acsettings)
					->withVatdata($this->vatdata)
					->withSettings($this->acsettings)
					->withLocdefault($locdefault)
					->withPurlocation($pur_location)
					->withPrintid($lastid)
					->withFormdata($this->formData)
					->withPrint($print)
					->withIsdept($is_dept)
					->withDepartments($departments)
					->withDeptid($deptid)
					->withData($data);
	}
	
		
	public function getVoucher($id) {
		
		 $row = $this->accountsetting->getVoucherByID($id);
		 if($row->voucher_no != '' || $row->voucher_no != null) {
			 if($row->is_prefix==0)
				 $voucher = $row->voucher_no;
			 else {
				 $no = (int)$row->voucher_no;
				 $voucher = $row->prefix.''.$no;
			 }
		 }
		 return $result = array('voucher_no' => $voucher, 
								'account_id' => $row->account_id, 
								'account_name' => $row->master_name, 
								'id' => $row->id,
								'caccount_id' => $row->caccount_id,
								'caccount_name' => $row->cmaster_name,
								'cid'	=> $row->cid,
								'cash_voucher' => $row->is_cash_voucher,
								'default_account' => $row->default_account,
								'cash_account' => $row->default_account_id
								);//print_r($ob);

	}
	
	public function save(Request $request) {
		//echo '<pre>';print_r($request->input());exit;
		
		if( $this->validate(
			$request, 
			[//'reference_no' => ($this->formData['reference_no']==1)?'required':'nullable',
			 'supplier_name' => 'required','supplier_id' => 'required',
			 //'item_code.*'  => 'required', 'item_id.*' => 'required',
			 //'unit_id.*' => 'required',
			 'quantity.*' => 'required',
			 'cost.*' => 'required',
			 //'dr_acnt' => 'sometimes|required'
			],
			[//'reference_no.required' => 'Reference no. is required.',
			 'supplier_name.required' => 'Supplier name is required.','supplier_id.required' => 'Supplier name is invalid.',
			 //'item_code.*.required'   => 'Item code is required.', 'item_id.*' => 'Item code is invalid.',
			 //'unit_id.*' => 'Item unit is required.',
			 'quantity.*' => 'Item quantity is required.',
			 'cost.*' => 'Item cost is required.',
			 //'dr_acnt.required' => 'Debit a/c. is required.',
			]
		)) {

			return redirect('purchase_split/add')->withInput()->withErrors();
		  }
		
		if(Session::has('dpt_id')) 
			Session::forget('dpt_id');
		if(Session::has('voucher_id'))
			Session::forget('voucher_id');
		if(Session::has('voucher_no'))
			Session::forget('voucher_no');
		if(Session::has('reference_no'))
			Session::forget('reference_no');
		if(Session::has('voucher_date'))
			Session::forget('voucher_date');
		if(Session::has('lpo_date'))
			Session::forget('lpo_date');
		if(Session::has('purchase_acnt'))
			Session::forget('purchase_acnt');
		if(Session::has('acnt_master'))
			Session::forget('acnt_master');
		
		if($this->purchase_split->create(Input::all())) {
			Session::flash('message', 'Purchase Split added successfully.');
		} else {
			Session::flash('error', 'Something went wrong, Invoice failed to add!');
		}
		return redirect('purchase_split/add');
	}
	
	public function destroy($id)
	{
			
		if($this->purchase_split->delete($id)) {
			Session::flash('message', 'Purchase split deleted successfully.');
		} else
			Session::flash('error', 'Something went wrong, split failed to delete!');
		
		return redirect('purchase_split');
	}
	
	public function checkRefNo() {

		$check = $this->purchase_split->check_reference_no(Input::get('reference_no'), Input::get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
	
	public function checkInvoice() {

		$check = $this->purchase_split->check_invoice_id( Input::get('purchase_split_id') );
		$isAvailable = ($check) ? false : true;
		echo $isAvailable;
	}
	
	public function edit($id) { 

		$data = array();
		$itemmaster = $this->itemmaster->activeItemmasterList();
		$jobs = $this->jobmaster->activeJobmasterList();
		$currency = $this->currency->activeCurrencyList();
		$orderrow = $this->purchase_split->findPOdata($id);
		$orditems = $this->purchase_split->getItems($id); 

		//echo '<pre>';print_r($orditems);exit;
		$voucher = $this->accountsetting->find($orderrow->voucher_id); 
		$units = DB::table('units')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		
		$print = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','PI')
							->where('report_view_detail.is_default',1)
							->select('report_view_detail.id')
							->first();
		
		//CHECK DEPARTMENT.......
		if(Session::get('department')==1) { //if active...
			$deptid = Auth::user()->department_id;
			if($deptid!=0) {
				$departments = DB::table('department')->where('id',$deptid)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
			} else {
				$departments = DB::table('department')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
				$deptid = $departments[0]->id;
			}
			$is_dept = true;
		} else {
			$is_dept = false;
			$departments = [];
			$deptid = '';
		}
		
		$did = ($deptid=='')?0:$deptid;
		$vouchers = DB::table('account_setting')->where('voucher_type_id',23)->where('status',1)->where('department_id',$did)->where('deleted_at','0000-00-00 00:00:00')->get(); //'Purchase Stock' voucher from account settings...
		//echo '<pre>';print_r($orditems);exit;
		return view('body.purchasesplit.edit')
					->withItems($itemmaster)
					->withOrditems($orditems)
					->withJobs($jobs)
					->withCurrency($currency)
					->withOrderrow($orderrow)
					->withVatdata($this->vatdata)
					->withSettings($this->acsettings)
					->withFormdata($this->formData)
					->withVouchers($vouchers)
					->withVoucher($voucher)
					->withPrint($print)
					->withIsdept($is_dept)
					->withDepartments($departments)
					->withDeptid($deptid)
					->withUnits($units)
					->withData($data);

	}
	
	private function makeTreeArr($result) {
		
		$childs = array();
		foreach($result as $item)
			$childs[$item->invoice_id][] = $item;
		
		return $childs;
	}
		
	public function update(Request $request)
	{ 	 //echo '<pre>';print_r($request->input());exit;
		$id = $request->input('purchase_split_id');
		if( $this->validate(
			$request, 
			[//'reference_no' => ($this->formData['reference_no']==1)?'required':'nullable',
			 'supplier_name' => 'required','supplier_id' => 'required',
			 //'item_code.*'  => 'required', 'item_id.*' => 'required',
			 //'unit_id.*' => 'required',
			 'quantity.*' => 'required',
			 'cost.*' => 'required'
			],
			[//'reference_no.required' => 'Reference no. is required.',
			 'supplier_name.required' => 'Supplier name is required.','supplier_id.required' => 'Supplier name is invalid.',
			 //'item_code.*.required'   => 'Item code is required.', 'item_id.*' => 'Item code is invalid.',
			 //'unit_id.*' => 'Item unit is required.',
			 'quantity.*' => 'Item quantity is required.',
			 'cost.*' => 'Item cost is required.'
			]
		)) {

			return redirect('purchase_split/edit/'.$id)->withInput()->withErrors();
		}
		
		if( $this->purchase_split->update($id, Input::all()) ) {
			Session::flash('message', 'Purchase Split updated successfully');
		} else
			Session::flash('error', 'Something went wrong, Invoice failed to update!');
		
		return redirect('purchase_split');
	}
	
	/* public function ajax_getcode($group_id)
	{
		$group = $this->group->getGroupCode($group_id);
		$row = $this->accountmaster->getGroupCode($group_id);
		if($row)
			$no = intval(preg_replace('/[^0-9]+/', '', $row->account_id), 10);
		else 
			$no = 0;
		
		$no++;
		return $code = strtoupper($group->code).''.$no;
		//return $group->account_id;

	} */
	
	public function ajax_getcode($category)
	{
		$group = $this->group->getGroupCode($category);
		$row = $this->accountmaster->getGroupCode($category);
		if($row)
			$no = intval(preg_replace('/[^0-9]+/', '', $row->account_id), 10);
		else 
			$no = 0;
		
		$no++;
		return json_encode(array(
							'code' => strtoupper($group->code).''.$no,
							'category' => $group->category
						));
	}
	
	public function show($id) { 

		$data = array();
		$acmasterrow = $this->accountmaster->accountMasterView($id);
		//echo '<pre>';print_r($acmasterrow);exit;
		return view('body.accountmaster.view')
					->withMasterrow($acmasterrow)
					->withData($data);
	}
	
	public function getSupplier($num=null)
	{
		$data = array();
		$suppliers = $this->accountmaster->getSupplierList();
		$country = $this->country->activeCountryList();
		$area = $this->area->activeAreaList();
		$sup_code = json_decode($this->ajax_getcode($category='SUPPLIER'));
		return view('body.purchasesplit.supplier')
					->withSuppliers($suppliers)
					->withArea($area)
					->withSupid($sup_code->code)
					->withCategory($sup_code->category)
					->withCountry($country)
					->withNum($num)
					->withData($data);
	}
	
	public function getAccount($num,$cr=null)
	{
		$data = array();
		$accounts = $this->accountmaster->activeAccountList();
		return view('body.purchasesplit.account')
					->withAccounts($accounts)
					->withNum($num)
					->withCr($cr)
					->withData($data);
	}
	
	public function getPI($did=null)
	{
		$data = array();
		$pidata = $this->purchase_split->getPIdata($did);//echo '<pre>';print_r($pidata);exit;
		return view('body.purchasesplit.pidata')
					->withPidata($pidata)
					->withDid($did)
					->withData($data);
	}
	
	//ED12
	public function getInvoiceBySupplier($supplier_id,$no=null,$ref=null,$pvid=null)
	{
		$pvdat = ''; $pvrefdat = $pvarr = [];
		$invoices = $this->purchase_split->getSupplierInvoice($supplier_id,null,$pvid);
		$openbalances = $this->purchase_split->getOpenBalances($supplier_id);
		$ocbills = $this->purchase_split->getOtherCostBills($supplier_id,null,$pvid); //echo '<pre>';print_r($ocbills);exit;
		$pinbills = [];//$this->purchase_split->getPINbills($supplier_id,null,$pvid); 
		$otbills = $this->purchase_split->getOthrBills($supplier_id,null,$pvid); //May 15
		
		if($pvid) {
			$pvdat = DB::table('payment_voucher_entry')->where('id', $pvid)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->first();
			
			if($pvdat) {
				$pvref = DB::table('payment_voucher_entry')->where('entry_type', 'Cr')->where('payment_voucher_id',$pvdat->payment_voucher_id)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('reference')->first();
				$pvrefdat = ($pvref)?explode(',',$pvref->reference):[];
				
				$pvarr = $this->makeArr(DB::table('payment_voucher_entry')->where('entry_type', 'Dr')->where('payment_voucher_id',$pvdat->payment_voucher_id)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('reference','amount')->get());
			}
			
		}
		
		return view('body.purchasesplit.supinvoice')
					->withNum($no)
					->withOpenbalances($openbalances)
					->withPinbills($pinbills)
					->withOcbills($ocbills)
					->withInvoices($invoices)
					->withPvdata($pvdat)
					->withPvref($pvrefdat)
					->withPvarr($pvarr)
					->withRefno($ref)
					->withOtbills($otbills); //May 15
	}
	
	public function getInvoiceBySupplierEdit($supplier_id,$no,$pvid)
	{
		$ref = $pvdat = ''; $pvrefdat = $pvarr = [];
		$invoices = $this->purchase_split->getSupplierInvoice($supplier_id,null,null);
		$openbalances = $this->purchase_split->getOpenBalances($supplier_id);
		$pinbills = $this->purchase_split->getPINbills($supplier_id,null,null);
		$ocbills = $this->purchase_split->getOtherCostBills($supplier_id,null,null);
		
		$pvref = DB::table('payment_voucher_entry')->where('entry_type', 'Cr')->where('payment_voucher_id',$pvid)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('reference')->first();
		$pvrefdat = ($pvref)?explode(',',$pvref->reference):[];
		
		$pvarr = $this->makeArr(DB::table('payment_voucher_entry')->where('entry_type', 'Dr')->where('payment_voucher_id',$pvid)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('reference','amount')->get());
		
		return view('body.purchasesplit.supinvoiceedit')
					->withNum($no)
					->withOpenbalances($openbalances)
					->withPinbills($pinbills)
					->withOcbills($ocbills)
					->withInvoices($invoices)
					->withPvdata($pvdat)
					->withPvref($pvrefdat)
					->withPvarr($pvarr)
					->withRefno($ref);
	}
	
	private function makeArr($res)
	{	$ar = [];
		foreach($res as $val) {
			$ar[$val->reference][] = $val->amount;
			
		}
		return $ar;
	}
	
	public function setSessionVal()
	{
		//print_r(Input::all());
		Session::put('voucher_id', Input::get('vchr_id'));
		Session::put('voucher_no', Input::get('vchr_no'));
		Session::put('reference_no', Input::get('ref_no'));
		Session::put('voucher_date', Input::get('vchr_dt'));
		Session::put('lpo_date', Input::get('lpo_dt'));
		Session::put('purchase_acnt', Input::get('pur_ac'));
		Session::put('acnt_master', Input::get('ac_mstr'));
		Session::put('dpt_id', Input::get('dpt_id'));

	}
	
	public function getPrint($id,$rid=null)
	{
		//$viewfile = DB::table('report_view')->where('code', 'PI')->where('status',1)->select('view_name')->first();
		$viewfile = DB::table('report_view_detail')->where('id', $rid)->select('print_name')->first();
		if($viewfile->print_name=='') {
			$attributes['document_id'] = $id;
			$attributes['is_fc'] = ($fc)?1:'';
			$result = $this->purchase_split->getInvoice($attributes);
			$titles = ['main_head' => 'Purchase Invoice','subhead' => 'Purchase Invoice'];
			return view('body.purchasesplit.print')
						->withDetails($result['details'])
						->withTitles($titles)
						->withFc($attributes['is_fc'])
						->withId($id)
						->withItems($result['items']);
		} else {
					
			$path = app_path() . '/stimulsoft/helper.php';
			
			return view('body.purchasesplit.viewer')->withPath($path)->withView($viewfile->print_name);
		}
		
	}
	
	public function getPrintFc($id,$rid=null)
	{
		$viewfile = DB::table('report_view_detail')->where('id', $rid)->select('print_name')->first();
		if($viewfile->print_name=='') {
			$attributes['document_id'] = $id;
			$attributes['is_fc'] = ($fc)?1:'';
			$result = $this->purchase_split->getInvoice($attributes);
			$titles = ['main_head' => 'Purchase Invoice','subhead' => 'Purchase Invoice'];
			return view('body.purchasesplit.print')
						->withDetails($result['details'])
						->withTitles($titles)
						->withFc($attributes['is_fc'])
						->withId($id)
						->withItems($result['items']);
		} else {
					
			$path = app_path() . '/stimulsoft/helper.php';
			$arr = explode('.',$viewfile->print_name);
			$viewname = $arr[0].'FC.mrt';
			
			return view('body.salesinvoice.viewer')->withPath($path)->withView($viewname);
		}
		
	}
	
	public function getOrderHistory($supplier_id)
	{
		$data = array();
		$items = $this->purchase_split->getOrderHistory($supplier_id);//echo '<pre>';print_r($items);exit;
		return view('body.purchasesplit.history')
					->withItems($items)
					->withData($data);
	}
	
	public function checkVchrNo() {

		$check = $this->purchase_split->check_voucher_no(Input::get('voucher_no'), Input::get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
	
	public function getInvoiceSetBySupplier($supplier_id,$no=null)
	{
		$invoices = $this->purchase_split->getSupplierInvoice($supplier_id);
		$openbalances = $this->purchase_split->getOpenBalances($supplier_id);
		$advance = $this->purchase_split->getAdvance($supplier_id); //echo '<pre>';print_r($advance);exit;
		$otbills = $this->purchase_split->getOthrBills($supplier_id,null,null); //May 15
		return view('body.purchasesplit.supinvoiceset')
					->withNum($no)
					->withOpenbalances($openbalances)
					->withInvoices($invoices)
					->withAdvance($advance)
					->withOtbills($otbills); //May 15
	}
	
	protected function makeTree($result)
	{
		$childs = array();
		foreach($result as $item)
			$childs[$item->amount_transfer][] = $item;
		
		return $childs;
	}
	
	protected function makeTreeTC($result)
	{
		$childs = array();
		foreach($result as $item)
			$childs[$item->tax_code][] = $item;
		
		return $childs;
	}
	protected function makeTreeSumm($result)
	{
		$childs = array();
		foreach($result as $item)
			$childs[$item['amount_transfer']][] = $item;
	   
		return $childs;
	}
	// protected function makeTreeCus($result)
	// {
	// 	$childs = array();
	// 	foreach($result as $item)
	// 	//echo '<pre>';print_r($item); exit();
	// 		$childs[$item['supplier_id']][] = $item;
	// 		//$childs[$item->sid][] = $item;
	// 		echo '<pre>';print_r($childs); exit();
	// 		//$childs[$item->cid][] = $item;
	// 	return $childs;
	// }
	protected function makeTreeSup($result)
	{
		$childs = array();
		foreach($result as $item)
		//echo '<pre>';print_r($item); exit();
			$childs[$item['supplier_id']][] = $item;
			//$childs[$item->cid][] = $item;
		return $childs;
	}
	// public function getSearch()
	// {
	// 	$data = array();
	// 	$dname = '';
	// 	//echo '<pre>';print_r(Input::all());exit;
	// 	$reports = $this->purchase_split->getReport(Input::all());
	// 	//echo '<pre>';print_r([$reports]);exit;
	// 	if(Session::get('department')==1) {
	// 		if(Input::get('department_id')!='') {
	// 			$rec = DB::table('department')->where('id', Input::get('department_id'))->select('name')->first();
	// 			$dname = $rec->name;
	// 		}
	// 	}
		
	// 	if(Input::get('search_type')=="summary")
	// 		$voucher_head = 'Purchase Split Summary';
	// 	elseif(Input::get('search_type')=="purchase_register") {
	// 		$voucher_head = 'Purchase Splitt Summary';
	// 		$reports = $this->makeTree($reports);
	// 	} else if(Input::get('search_type')=="tax_code") {
	// 		$voucher_head = 'Purchase split by Tax Code';
	// 		$reports = $this->makeTreeTC($reports);
	// 	}
		
	// 	return view('body.purchasesplit.preprint')
	// 				->withReports($reports)
	// 				->withVoucherhead($voucher_head)
	// 				->withType(Input::get('search_type'))
	// 				->withFromdate(Input::get('date_from'))
	// 				->withTodate(Input::get('date_to'))
	// 				->withI(0)
	// 				->withIsimport(Input::get('isimport'))
	// 				->withSettings($this->acsettings)
	// 				->withDname($dname)
	// 				->withData($data);
	// }
	public function getSearch()
	{
		$dname = '';
		$data = array();
		$supid = $itemid = '';
		$reports = $this->purchase_split->customerWiseSummary(Input::all());
		//echo '<pre>';print_r($reports); exit();
		if(Session::get('department')==1) {
			 		if(Input::get('department_id')!='') {
			 			$rec = DB::table('department')->where('id', Input::get('department_id'))->select('name')->first();
			 			$dname = $rec->name;
			 		}
			 	}
		if(Input::get('search_type')=='summary') {
			$voucher_head = 'Purchase Split  by Summary';
			$reports =( $this->makeTreeSumm($this->purchase_split->customerWiseSummary(Input::all())));
			//echo '<pre>';print_r($reports); exit();
			
		}  else if(Input::get('search_type')=='supplier') {
			$voucher_head = 'Purchase Split by supplierwise';
		    $reports =( $this->makeTreeSup($this->purchase_split->customerWiseSummary(Input::all())));
		//$reports =	$this->makeTreeCus( $this->makeTreeSumm( $this->sales_split->customerWiseSummary(Input::all())));
	     	//echo '<pre>';print_r($reports); exit();
			
			if(Input::get('supplier_id')!==null)
				$supid = implode(',', Input::get('supplier_id'));
			else
				$supid = '';
		}    

		

		
		//echo '<pre>';print_r($custid);exit;
		return view('body.purchasesplit.preprint')
					->withReports($reports)
					->withVoucherhead($voucher_head)
					->withType(Input::get('search_type'))
					->withFromdate(Input::get('date_from'))
					->withTodate(Input::get('date_to'))
					->withSupplier($supid)
					//->withCustomer($custid)
					->withItem($itemid)
					->withIsimport(Input::get('isimport'))
					->withDname($dname)
					->withSettings($this->acsettings)
					->withI(0)
					//->withTitles($titles)
					->withData($data);
	}
	public function getAccountByGroup($code)
	{
		return $this->accountmaster
						->where('account_master.status',1)
						->where('category',$code)
						->select('account_master.id','account_master.master_name','account_master.account_id','account_master.account_group_id')
						->get();
						/* ->join('account_group AS ag', function($join) {
							$join->on('ag.id','=','account_master.account_group_id');
						})
						->where('ag.category', $code) */
						
	}
	
	public function getCustomer()
	{
		$data = array();
		//$customer = $this->accountmaster->getAccountByGroup('CUSTOMER');
		$suppliers = $this->accountmaster->getSupplierList();
		return view('body.purchasesplit.multiselect')
		            ->withSuppliers($suppliers) 
					//->withCustomers($customer)
					->withType('CUST')
					->withData($data);
					
		/* $data = array();
		$data = $this->accountmaster->getAccountByGroup('CUSTOMER');
		if($data) {
			$result = array();
			foreach($data as $val) {
				$result[$val['id']] = $val['master_name'];
			}
			return $result;
		} else 
			return null; */
		
		//echo '<pre>';print_r($unit);
		//return $tdata = array( 1 => 'test1', 2 => 'test2' );
		
	}
	public function getsalesman()
	{
		$data = array();
		$salesman = $this->salesman->getSalesmanList(); 
		return view('body.purchasesplit.multiselect')
					->withSalesman($salesman)
					->withType('SALE')
					->withData($data);
					
		/* $data = array();
		$data = $this->accountmaster->getAccountByGroup('CUSTOMER');
		if($data) {
			$result = array();
			foreach($data as $val) {
				$result[$val['id']] = $val['master_name'];
			}
			return $result;
		} else 
			return null; */
		
		//echo '<pre>';print_r($unit);
		//return $tdata = array( 1 => 'test1', 2 => 'test2' );
		
	}
	public function dataExport()
	{
		$data = array();
		$datareport[] = [strtoupper(Session::get('company')),'','',''];
		$datareport[] = ['','','','','','',''];
		
		Input::merge(['type' => 'export']);
		//echo '<pre>';print_r(Input::all());exit;
		
		
		if(Input::get('search_type')=="summary")
		{
			$reports = $this->purchase_split->customerWiseSummary(Input::all());
			$voucher_head = 'Purchase Split Summary';

			$datareport[] = ['','','','',strtoupper($voucher_head), '','',''];
			$datareport[] = ['','','','','','',''];
			
		
				$datareport[] = ['SI.No.','PI#','Vchr.Date','PI.Ref#', 'Supplier','TRN No','Gross Amt.','VAT Amt.','Net Total'];
				$i=0;
				foreach ($reports as $row) {
						$i++;
						$datareport[] = [ 'si' => $i,
										  'po' => $row['voucher_no'],
										  'vdate' => date('d-m-Y',strtotime($row['voucher_date'])),
										  'ref' => $row['reference_no'],
										  'supplier' => $row['master_name'],
										  'vat_no' => $row['vat_no'],
										  'gross' => $row['total'],
										  'vat' => $row['vat_amount'],
										  'total' => $row['net_amount']
										];
				}
		}
			
		elseif(Input::get('search_type')=="purchase_register") {
			$voucher_head = 'Purchase Register Summary';
			//$reports = $this->makeTree($reports);
		}else if(Input::get('search_type')=='supplier') {
			$voucher_head = 'Purchase Split by supplierwise';
			$reports = $this->purchase_split->customerWiseSummary(Input::all());
		   // $reports =( $this->makeTreeSup($this->purchase_split->customerWiseSummary(Input::all())));
		//	echo '<pre>';print_r($reports );exit;
			$voucher_head = 'Purchase Split Summary';

			$datareport[] = ['','','','',strtoupper($voucher_head), '','',''];
			$datareport[] = ['','','','','','',''];
			
		
				$datareport[] = ['SI.No.','PI#','Vchr.Date','PI.Ref#', 'Supplier','TRN No','Gross Amt.','VAT Amt.','Net Total'];
				$i=0;
				foreach ($reports as $row) {
						$i++;
						$datareport[] = [ 'si' => $i,
										  'po' => $row['voucher_no'],
										  'vdate' => date('d-m-Y',strtotime($row['voucher_date'])),
										  'ref' => $row['reference_no'],
										  'supplier' => $row['master_name'],
										  'vat_no' => $row['vat_no'],
										  'gross' => $row['total'],
										  'vat' => $row['vat_amount'],
										  'total' => $row['net_amount']
										];
				}
		//$reports =	$this->makeTreeCus( $this->makeTreeSumm( $this->sales_split->customerWiseSummary(Input::all())));
	     	//echo '<pre>';print_r($reports); exit();
			
			if(Input::get('supplier_id')!==null)
				$supid = implode(',', Input::get('supplier_id'));
			else
				$supid = '';
				$voucher_head = 'Purchase Split Summary';
		}    
		
	
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
	
	
	private function createItem($row) {
		
	  DB::beginTransaction();
	  try {
		
		//GET category
		if($row->category!='') {
			$cat = DB::table('category')->where( function ($query) use($row) {
											$query->where('category_name', '=', $row->category)
											  ->orWhere('description', '=', $row->category);
									})->select('id')->first();
							   
			if(!$cat) { //IF category NOT EXIST...
				$category_id = DB::table('category')->insertGetId(['category_name' => strtoupper($row->category),'description' => strtoupper($row->category),'status' => 1]);
			} else
				$category_id = $cat->id;
		} else 
			$category_id = '';
		
		//GET subcategory
		if($row->subcategory!='') {
			$subcat = DB::table('category')->where( function ($query) use($row) {
											$query->where('category_name', '=', $row->subcategory)
											  ->orWhere('description', '=', $row->subcategory);
									})->select('id')->first();
							   
			if(!$subcat) { //IF category NOT EXIST...
				$subcategory_id = DB::table('category')->insertGetId(['category_name' => strtoupper($row->subcategory),'description' => strtoupper($row->subcategory), 'parent_id' => $category_id, 'status' => 1]);
			} else
				$subcategory_id = $subcat->id;
		} else 
			$subcategory_id = '';
		
		
		//GET group
		if($row->group!='') {
			$grp = DB::table('groupcat')->where( function ($query) use($row) {
											$query->where('group_name', '=', $row->group)
											  ->orWhere('description', '=', $row->group);
									})->select('id')->first();
							   
			if(!$grp) { //IF groupcat NOT EXIST...
				$group_id = DB::table('groupcat')->insertGetId(['group_name' => strtoupper($row->group),'description' => strtoupper($row->group),'status' => 1]);
			} else
				$group_id = $grp->id;
		} else 
			$group_id = '';
		
		
		//GET subgroup
		if($row->subgroup!='') {
			$subgrp = DB::table('groupcat')->where( function ($query) use($row) {
											$query->where('group_name', '=', $row->subgroup)
											  ->orWhere('description', '=', $row->subgroup);
									})->select('id')->first();
							   
			if(!$subgrp) { //IF subgroupcat NOT EXIST...
				$subgroup_id = DB::table('groupcat')->insertGetId(['group_name' => strtoupper($row->subgroup),'description' => strtoupper($row->subgroup), 'parent_id' => $group_id, 'status' => 1]);
			} else
				$subgroup_id = $subgrp->id;
		} else 
			$subgroup_id = '';
		
		$insert = ['item_code' => $row->item_code, 
									 'description' => $row->description,
									 'class_id' => 1,
									 'group_id' => $group_id,
									 'subgroup_id' => $subgroup_id,
									 'category_id' => $category_id,
									 'subcategory_id' => $subcategory_id,
									 'status'   => 1,
									 'created_at' => date('Y-m-d H:i:s')
								  ];
								  
		
		//GET UNIT ID
		if($row->unit!='') {
			$unit = DB::table('units')->where('unit_name', strtoupper($row->unit))->select('id','unit_name')->first();
			if(!$unit) { //IF UNIT NOT EXIST...
				$unit_id = DB::table('units')->insertGetId(['unit_name' => strtoupper($row->unit),'description' => strtoupper($row->unit),'status' => 1]);
				$unit_name = strtoupper($row->unit);
			} else {
				$unit_id = $unit->id;
				$unit_name = $unit->unit_name;
			}
		} else {
			$unit = DB::table('units')->where('unit_name', 'NOS')->select('id','unit_name')->first();
			$unit_id = $unit->id;
			$unit_name = $unit->unit_name;
		}
		//echo '<pre>';print_r($insert);exit;
		$item_id = DB::table('itemmaster')->insertGetId($insert);
		DB::table('item_unit')->insert(['itemmaster_id' => $item_id,
										'unit_id' => $unit_id,
										'packing' => strtoupper($unit_name),
										//'opn_quantity' => ($row->quantity=='')?0:$row->quantity,
										//'opn_cost' => ($row->cost_avg=='')?0:$row->cost_avg,
										//'sell_price' => ($row->sales_price=='')?0:$row->sales_price,
										'vat' => 5,
										'status' => 1,
										//'cur_quantity' => ($row->quantity=='')?0:$row->quantity,
										'is_baseqty' => 1
										//'received_qty' => ($row->quantity=='')?0:$row->quantity
										//'cost_avg' => ($row->cost_avg=='')?0:$row->cost_avg
										]);
										
		DB::table('item_log')->insert([
								'document_type' => 'OQ',
								'document_id' => 0,
								'item_id' => $item_id,
								'unit_id' => $unit_id,
								'trtype' => 1,
								'packing' => 1,
								'status' => 1,
								'created_at' => date('Y-m-d H:i:s'),
								'voucher_date' => date('Y-m-d')
								]);
		 DB::commit();
		 return $item = (object)['item_id' => $item_id, 'item_code' => $row->item_code, 'item_name' => $row->description, 'unit_id' => $unit_id, 'unit_name' => $unit_name, 'vat' => 5, 'quantity' => $row->quantity, 'cost' => $row->rate,'opn_cost' => $row->rate];
		
	  } catch(\Exception $e) { 
		
			DB::rollback(); echo $e->getLine().' - '.$e->getMessage();exit;
			return false;
		}
						
						
	}
	
	//EXCEL FORMAT:   Item Code|Description|Unit|Quantity|Rate
	public function getImport(Request $request) {
		  
		if(Input::hasFile('import_file')){
			
			$path = Input::file('import_file')->getRealPath();
			$data = Excel::load($path, function($reader) { })->get();
			//echo '<pre>';print_r($data);exit;
			//$items = array();
			foreach ($data as $row) {
				 if($row->item_code!='' && $row->description!='') {
					 //CHECK ITEM EXIST OR NOT
					$cost = ($row->rate!='')?DB::raw($row->rate.' AS cost'):0;
					 $item = DB::table('itemmaster')
									->where( function ($query) use($row) {
										$query->where('itemmaster.item_code', '=', $row->item_code)
											  ->orWhere('itemmaster.description', '=', $row->description);
								   })
							   ->join('item_unit', 'item_unit.itemmaster_id', '=', 'itemmaster.id')
							   ->where('item_unit.is_baseqty',1)
							   ->select('itemmaster.id AS item_id','itemmaster.item_code','itemmaster.description AS item_name',
										'item_unit.unit_id','item_unit.packing AS unit_name','item_unit.vat','item_unit.opn_cost',
										DB::raw($row->quantity.' AS quantity'),'item_unit.last_purchase_cost AS cost')
							   ->first(); //echo '<pre>';print_r($item);exit;
						//$item->quantity = $row->quantity;	
						
						//$items = array_push($item,$itm);
						   
					//echo '<pre>';print_r($item);exit;
					 
					 if(!$item) {
						 $item = $this->createItem($row);
					 } else {
						 //if($item->cost==0 && $row->rate!='')
							$item->cost = $row->rate;
					 }
					 
					 $items[] = $item;
				 }
			}
			
		}
		
		$terms = $this->terms->activeTermsList();
		$jobs = $this->jobmaster->activeJobmasterList();
		$currency = $this->currency->activeCurrencyList();
		$location = $this->location->locationList();
		$vouchers = $this->accountsetting->getAccountSettingsDefault2($vid=1);
		
		$docRow = (object)['voucher_id' => $request->input('voucher_id'),
						   'voucher_no' => $request->input('voucher_no'),
						   'curno' => $request->input('curno'),
						   'reference_no' => $request->input('reference_no'),
						   'voucher_date' => ($request->input('voucher_date')=='')?date('d-m-Y'):$request->input('voucher_date'),
						   'lpo_no' => $request->input('lpo_no'),
						   'lpo_date' => $request->input('lpo_date'),
						   'purchase_account' => $request->input('purchase_account'),
						   'account_master_id' => $request->input('account_master_id'),
						   'supplier_name' => $request->input('supplier_name'),
						   'supplier_id' => $request->input('supplier_id'),
						   'description' => $request->input('description'),
						   'terms_id' => $request->input('terms_id'),
						   'job_id' => $request->input('job_id'),
						   'is_fc' => $request->input('is_fc'),
						   'currency_id' => $request->input('currency_id'),
						   'currency_rate' => $request->input('currency_rate'),
						   'po_no' => $request->input('po_no')
						  ];
						  
		//echo '<pre>';print_r($items);exit;
		
		return view('body.purchasesplit.additms')
						->withTerms($terms)
						->withJobs($jobs)
						->withCurrency($currency)
						->withDocrow($docRow)
						->withDocitems($items)
						->withLocation($location)
						->withVatdata($this->vatdata)
						->withSettings($this->acsettings)
						->withFormdata($this->formData)
						->withVouchers($vouchers)
						->withData($data);
		
		
	}
	
	public function dataExportPo()
	{
		$data = array();
		$datareport[] = [strtoupper(Session::get('company')),'','',''];
		$datareport[] = ['','','','','','',''];
		
		$attributes['document_id'] = Input::get('id');
		$attributes['is_fc'] = Input::get('fc');
		$result = $this->purchase_split->getInvoice($attributes);
		
		$voucher_head = 'PURCHASE INVOICE';
		
		 //echo '<pre>';print_r($result);exit;
		
		$datareport[] = ['','', 'PURCHASE INVOICE', '','','',''];	
		$details = $result['details'];
		$supname = ($details->supplier=='CASH CUSTOMER' || $details->supplier=='Cash Customer')?'CASH CUSTOMER:'.$details->customer_name:$details->supplier;
		$vat_no = ($details->vat_no=='')?$details->customer_trn:$details->vat_no;
		
		$datareport[] = ['Supplier No:',$details->account_id, '', '','','','PO.No:',$details->voucher_no];
		$datareport[] = ['Supplier Name:',$supname, '', '','','','Date:',date('d-m-Y',strtotime($details->voucher_date))];
		$datareport[] = ['Address:',$details->address, '', '','','','Contact Person:',$details->contact_name];
		$datareport[] = ['Telephone No:',$details->phone, '', '','','','',''];
		$datareport[] = ['Supplier TRN:',$vat_no, '', '','','','',''];
		$datareport[] = ['','', '', '','','','',''];
		
		$datareport[] = ['Si.#.','Item Code', 'Description', 'Unit','Quantity','Unit Price','VAT','Total'];
		
		$i=0;
		foreach ($result['items'] as $row) {
				$i++;
				
				if($attributes['is_fc']==1) {
					$unit_price = $row->unit_price / $details->currency_rate;
					$vat_amount = $row->vat_amount / $details->currency_rate;
					$total_price = $row->total_price / $details->currency_rate;
				} else {
					$unit_price = $row->unit_price;
					$vat_amount = $row->vat_amount;
					$total_price = $row->total_price;
				}
				
				$datareport[] = [ 'si' => $i,
								  'code' => $row->item_code,
								  'description' => $row->item_name,
								  'unit' => $row->unit_name,
								  'qty' => $row->quantity,
								  'price' => number_format($unit_price,2),
								  'vat' => number_format($vat_amount,2),
								  'total' => number_format($total_price,2)
								];
		}
		
		if($attributes['is_fc']==1) {
			$total = $details->total / $details->currency_rate;
			$vat_amount_net = $details->vat_amount / $details->currency_rate;
			$net_amount = $details->net_amount / $details->currency_rate;
		} else {
			$total = $details->total;
			$vat_amount_net = $details->vat_amount;
			$net_amount = $details->net_amount;
		}
		$cur = ($attributes['is_fc']==1)?' ('.$details->currency.')':':';
		$datareport[] = ['','', '', '','','','',''];									
		$datareport[] = ['','', 'Gross Total'.$cur, '','','','',number_format($total,2)];
		$datareport[] = ['','', 'Vat Total'.$cur, '','','','',number_format($vat_amount_net,2)];
		$datareport[] = ['','', 'Total Inclusive VAT'.$cur, '','','','',number_format($net_amount,2)];
			
		 //echo $voucher_head.'<pre>';print_r($datareport);exit;
		Excel::create($voucher_head, function($excel) use ($datareport,$voucher_head) {

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
	
	public function getItemDetails($id) 
	{
		$data = array();
		$items = $this->purchase_split->getItems(array($id));
		return view('body.purchasesplit.itemdetails')
					->withItems($items)
					->withData($data);
	}
	
	public function getDeptVoucher($id) {
		
		$depts = $this->accountsetting->getVoucherByDeptPI($vid=1,$id); 
		
		foreach($depts as $row) {
			
			 if($row->voucher_no != '' || $row->voucher_no != null) {
				 if($row->is_prefix==0)
					 $voucher = $row->voucher_no;
				 else {
					 $no = (int)$row->voucher_no;
					 $voucher = $row->prefix.''.$no;
				 }
			 }
			
			  $result[] = array('voucher_name' => $row->voucher_name,
								'voucher_id' => $row->voucher_id,
								'voucher_no' => $voucher,
								'account_id' => $row->account_id, 
								'account_name' => $row->master_name, 
								'id' => $row->id,
								'cash_voucher' => $row->is_cash_voucher,
								'cash_account' => $row->default_account_id,
								'default_account' => $row->default_account );
		}
		
		return $result;
	}
	
	public function getSupplierDpt($deptid=null)
	{
		$data = array();
		$suppliers = $this->accountmaster->getSupplierListDept($deptid);
		$country = $this->country->activeCountryList();
		$area = $this->area->activeAreaList();
		$sup_code = json_decode($this->ajax_getcode($category='SUPPLIER'));
		return view('body.purchasesplit.supplier')
					->withSuppliers($suppliers)
					->withArea($area)
					->withSupid($sup_code->code)
					->withCategory($sup_code->category)
					->withCountry($country)
					->withNum('')
					->withData($data);
	}
	
}


