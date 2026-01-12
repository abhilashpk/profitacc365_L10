<?php

namespace App\Http\Controllers;
use App\Repositories\Area\AreaInterface;
use App\Repositories\Itemmaster\ItemmasterInterface;
use App\Repositories\Terms\TermsInterface;
use App\Repositories\Jobmaster\JobmasterInterface;
use App\Repositories\AccountMaster\AccountMasterInterface;
use App\Repositories\Currency\CurrencyInterface;
use App\Repositories\VoucherNo\VoucherNoInterface;
use App\Repositories\Salesman\SalesmanInterface;
use App\Repositories\QuotationSales\QuotationSalesInterface;
use App\Repositories\AccountSetting\AccountSettingInterface;
use App\Repositories\SalesInvoice\SalesInvoiceInterface;
use App\Repositories\SalesOrder\SalesOrderInterface;
use App\Repositories\CustomerDo\CustomerDoInterface;
use App\Repositories\Department\DepartmentInterface;
use App\Repositories\Forms\FormsInterface;
use App\Repositories\Bank\BankInterface;
use App\Repositories\ReceiptVoucher\ReceiptVoucherInterface;

use Illuminate\Http\Request;

use App\Http\Requests;
use Session;
use Response;
use Excel;
use App;
use DB;
use Auth;

class JobInvoiceController extends Controller
{

	protected $itemmaster;
	protected $terms;
	protected $jobmaster;
	protected $accountmaster;
	protected $currency;
	protected $voucherno;
	protected $salesman;
	protected $quotation_sales;
	protected $accountsetting;
	protected $sales_invoice;
	protected $sales_order;
	protected $customerdo;
	protected $cost_accounts;
	protected $department;
	protected $forms;
	protected $formData;
	protected $matservice;
	protected $bank;
	protected $receipt_voucher;
	public function __construct(CustomerDOInterface $customerdo, SalesInvoiceInterface $sales_invoice, DepartmentInterface $department, SalesOrderInterface $sales_order, QuotationSalesInterface $quotation_sales, ItemmasterInterface $itemmaster, TermsInterface $terms, JobmasterInterface $jobmaster, AccountMasterInterface $accountmaster, CurrencyInterface $currency, VoucherNoInterface $voucherno, SalesmanInterface $salesman, AccountSettingInterface $accountsetting,FormsInterface $forms,BankInterface $bank, ReceiptVoucherInterface $receipt_voucher) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		
		$this->middleware('auth');
		$this->itemmaster = $itemmaster;
		$this->terms = $terms;
		$this->jobmaster = $jobmaster;
		$this->accountmaster = $accountmaster;
		$this->currency = $currency;
		$this->voucherno = $voucherno;
		$this->salesman = $salesman;
		$this->quotation_sales = $quotation_sales;
		$this->accountsetting = $accountsetting;
		$this->sales_invoice = $sales_invoice;
		$this->sales_order = $sales_order;
		$this->customerdo = $customerdo;
		$this->department = $department;
		$this->forms = $forms;
		$this->formData = $this->forms->getFormData('JI');
		$this->bank = $bank;
		$this->receipt_voucher = $receipt_voucher;
		
		if(Session::get('cost_accounting')==1) {
			$this->cost_accounts = $this->accountsetting->getCostAccounts();
			Session::put('stock', $this->cost_accounts['stock']);
			Session::put('cost_of_sale', $this->cost_accounts['cost_of_sale']);
		}
		
		$this->matservice = DB::table('parameter2')->where('keyname', 'mod_material_service')->where('status',1)->select('is_active')->first();
		$this->mod_si_roundoff = DB::table('parameter2')->where('keyname', 'mod_si_roundoff')->where('status',1)->select('is_active')->first();
		$this->mod_autocost = DB::table('parameter2')->where('keyname', 'mod_autocost_refresh')->where('status',1)->select('is_active')->first();
	}
	
    public function index() {
		
		//Session::put('cost_accounting', 0);
		$data = array();
		$invoices = [];//$this->sales_invoice->salesInvoiceList();//echo '<pre>';print_r($quotations);exit;
		$salesmans = $this->salesman->getSalesmanList();
		$item = DB::table('itemmaster')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		$customer = $this->accountmaster->getCustomerList();
		$category = DB::table('category')->where('parent_id',0)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		$subcategory = DB::table('category')->where('parent_id',1)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		$group = DB::table('groupcat')->where('parent_id',0)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		$subgroup = DB::table('groupcat')->where('parent_id',1)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		$jobs = $this->jobmaster->activeJobmasterList();
		return view('body.jobinvoice.index')
					->withInvoices($invoices)
					->withSalesman($salesmans)
					->withCategory($category)
					->withItem($item)
					->withType('')
					->withCustomer($customer)
					->withSubcategory($subcategory)
					->withGroup($group)
					->withSubgroup($subgroup)
					->withJobs($jobs)
					->withSettings($this->acsettings)
					->withData($data);
	}
	
	public function ajaxPaging(Request $request)
	{
		$columns = array( 
                            0 =>'sales_invoice.id', 
                            1 =>'voucher_no',
                            2=> 'voucher_date',
							3=> 'jo_no',
                            4=> 'customer',
							5=> 'vehicle',
							6=> 'reg_no',
                            7=> 'net_total',
                            8=>'chasis_no'
                        );
						
		$totalData = $this->sales_invoice->jobInvoiceListCount();
            
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = 'sales_invoice.id';//$columns[$request->input('order.0.column')];
        $dir = 'desc';//$request->input('order.0.dir');
		$search = (empty($request->input('search.value')))?null:$request->input('search.value');
        
		$invoices = $this->sales_invoice->jobInvoiceList('get', $start, $limit, $order, $dir, $search);
		
		if($search)
			$totalFiltered =  $this->sales_invoice->jobInvoiceList('count', $start, $limit, $order, $dir, $search);
		
		$prints = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','JI')
							->select('report_view_detail.name','report_view_detail.id')
							->get();
							
        $data = array();
        if(!empty($invoices))
        {
           
			foreach ($invoices as $row)
            {
                //$edit =  '"'.url('job_invoice/edit/'.$row->id).'"';
                $delete =  'funDelete("'.$row->id.'")';
				$print = url('job_invoice/print/'.$row->id);
				$item_info =  '"'.url('job_invoice/item_info/'.$row->id).'"';
				
				$edit =  url('job_invoice/edit/'.$row->id);
				$docs =  url('job_invoice/docs/'.$row->id);
                	$viewonly =  url('job_invoice/viewonly/'.$row->id);  
                $nestedData['id'] = $row->id;
                $nestedData['voucher_no'] = $row->voucher_no;
				$nestedData['voucher_date'] = date('d-m-Y', strtotime($row->voucher_date));
				$nestedData['customer'] = ($row->customer=='CASH CUSTOMERS') ? (($row->customer_name!='')?$row->customer.'('.$row->customer_name.')':$row->customer) : $row->customer;
				$nestedData['jo_no'] = $row->jo_no;
				$nestedData['vehicle'] = $row->vehicle;
				$nestedData['reg_no'] = $row->reg_no;
				$nestedData['chasis_no'] = $row->chasis_no;
				$nestedData['net_total'] = $row->net_total;
                /*$nestedData['edit'] = "<p><button class='btn btn-primary btn-xs' onClick='location.href={$edit}'>
												<span class='glyphicon glyphicon-pencil'></span></button></p>";*/
				$nestedData['viewonly'] = "<p><a href='{$viewonly}' class='btn btn-info btn-xs' target='_blank'><i class='glyphicon glyphicon-eye-open'></i></a></p>";
																
				$nestedData['edit'] = "<div class='btn-group drop_btn' role='group'>
											<button type='button' class='btn btn-primary btn-xs dropdown-toggle m-r-50'
													id='exampleIconDropdown1' data-toggle='dropdown' aria-expanded='false'>
												<i class='fa fa-fw fa-shield'></i><span class='caret'></span>
											</button>
											<ul style='min-width:100px !important;' class='dropdown-menu' aria-labelledby='exampleIconDropdown1' role='menu'>
												<li role='presentation'><a href='{$edit}' role='menuitem'>Edit</a></li>
												<li role='presentation'><a href='{$docs}' role='menuitem' target='_blank'>Docs</a></li>
											</ul>
										</div>";
												
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
											</ul>
										</div>";
				$nestedData['item_info'] = "<p><button class='btn btn-warning btn-xs' onClick='location.href={$item_info}'>
										<i class='fa fa-fw fa-bookmark'></i></button></p>";
										
								
				//$nestedData['print'] = "<p><a href='{$print}' target='_blank' class='btn btn-primary btn-xs'><span class='fa fa-fw fa-print'></span></a></p>";
											
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
	
	public function add($id = null, $doctype = null) {

		$data = array(); $vehicle_data = null;//echo Session::get('stock');exit;//print_r($this->cost_accounts);exit;
		$itemmaster = $this->itemmaster->activeItemmasterList();
		$terms = $this->terms->activeTermsList();
		$jobs = $this->jobmaster->getOpenJobs();
		$currency = $this->currency->activeCurrencyList();
		$vouchers = $this->accountsetting->getAccountSettingsDefault2($vid=3); //'Sales Stock' voucher from account settings...
		$department = $this->department->activeDepartmentList();
		$lastid = $this->sales_invoice->getLastId();
		$locdefault = DB::table('location')->where('is_default',1)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id')->first();
		$jobtype = DB::table('jobtype')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		$sales_location = DB::table('parameter3')
							 ->join('location', 'location.id', '=', 'parameter3.location_id')
							 ->join('account_master', 'account_master.id', '=', 'parameter3.account_id')
							 ->select('location.name','location.id','account_master.master_name','account_master.id AS account_id')
							 ->get();
		$footertxt = DB::table('header_footer')->where('doc','JI')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->first();					 
		$print = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','JI')
							->where('report_view_detail.is_default',1)
							->select('report_view_detail.id')
							->first();
		$banks = $this->bank->activeBankList();
		$vchrdata = $this->getVoucherRV(9,'CASH');
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
		
		$vouchers = $this->accountsetting->getAccountSettingsDefault2($vid=3,$is_dept,$deptid); //'Sales Stock' voucher from account settings...
		
		if($this->mod_si_roundoff->is_active==1)
			$round_off = true;
		else
			$round_off = false;
			
		//echo sizeof($vouchers);exit;
		if($id) {
			$ids = explode(',', $id); $jobid = '';
			if($doctype=='QS') {
				
				$docRow = $this->quotation_sales->findQuoteData($ids[0]);
				
				$itemdesc = $this->makeTreeArr($this->quotation_sales->getItemDesc($ids));
				$jobdesc = $this->quotation_sales->getjobDescription($ids);
				
				if($this->matservice->is_active==1) {
					$docItems = $this->quotation_sales->getItems($ids,'itm');
					$seritems = $this->quotation_sales->getItems($ids,'ser');
				} else {
					$seritems = null;
					$docItems = $this->quotation_sales->getItems($ids);
				}
				
			}else if($doctype=='JI') {
				$docRow = $this->sales_invoice->findPOdata($ids[0]);
				$docItems = $this->sales_invoice->getItems($ids);
				$itemdesc = $this->makeTreeArr($this->sales_invoice->getItemDesc($ids));
				$jobdesc = $this->sales_invoice->getjobDescription($ids);
				$seritems = null;
			} 
			else if($doctype=='SO') {
				$docRow = $this->sales_order->findOrderData($ids[0]);
				
				$itemdesc = $this->makeTreeArr($this->sales_order->getItemDesc($ids));
				/* $jobdat = DB::table('jobmaster')->where('code',$docRow->voucher_no)->select('id')->first();
				$jobid = ($jobdat)?$jobdat->id:''; */
				
				$jobdesc = $this->sales_order->getjobDescription($ids);
				
				if($this->matservice->is_active==1) {
					$docItems = $this->sales_order->getItems($ids,'itm');
					$seritems = $this->sales_order->getItems($ids,'ser');
				} else {
					$seritems = null;
					$docItems = $this->sales_order->getItems($ids);
				}
				
			} else if($doctype=='CDO') {
				$docRow = $this->customerdo->findOrderData($ids[0]);
				$docItems = $this->customerdo->getItems($ids);
				$itemdesc = $this->makeTreeArr($this->customerdo->getItemDesc($ids));
			} 
			
		
			$total = 0; $discount = 0; $nettotal = 0;
			foreach($docItems as $item) {
				$total += $item->line_total;
				$discount += $item->discount;
			}
			
			$nettotal = $total - $discount;
			
			$view = ($this->matservice->is_active==1)?'addpims':'addpi';
			$photos = DB::table('job_photos')->where('job_order_id',$id)->get(); 
			$vouchers = $this->accountsetting->getAccountSettingsDefault2($vid=3,$is_dept,Session::get('dpt_id'));
			//echo '<pre>';print_r($docRow);exit;
			return view('body.jobinvoice.'.$view)
						->withItems($itemmaster)
						->withTerms($terms)
						->withJobs($jobs)
						->withCurrency($currency)
						->withDocrow($docRow)
						->withDocitems($docItems)
						->withDocid($id)
						->withTotal($total)
						->withDiscount($discount)
						->withNettotal($nettotal)
						->withDoctype($doctype)
						->withVouchers($vouchers)
						->withVoucherid(Session::get('voucher_id'))
						->withVoucherno(Session::get('voucher_no'))
						->withReferenceno(Session::get('reference_no'))
						->withVoucherdt(Session::get('voucher_date'))
						->withLpodt(Session::get('lpo_date'))
						->withAccountmstr(Session::get('acnt_master'))
						->withSalesac(Session::get('sales_acnt'))
						->withDptid(Session::get('dpt_id'))
						->withItemdesc($itemdesc)
						->withVatdata($this->vatdata)
						->withSettings($this->acsettings)
						->withVehicledata($vehicle_data)
						->withSearch(false)
						->withFormdata($this->formData)
						//->withJobid($jobid)
						->withMatservice($this->matservice->is_active)
						->withJobdesc($jobdesc)
						->withSeritems($seritems)
						->withJobtype($jobtype)
						->withDptid(Session::get('dpt_id'))
						->withIsdept($is_dept)
						->withDepartments($departments)
						->withPhotos($photos)
						->withRoundoff($round_off)
						->withData($data);
		}
	//	echo '<pre>';print_r($vouchers);exit;
		$view = ($this->matservice->is_active==1)?'addms':'add';
		return view('body.jobinvoice.'.$view)
					->withItems($itemmaster)
					->withTerms($terms)
					->withJobs($jobs)
					->withCurrency($currency)
					->withVouchers($vouchers)
					->withVatdata($this->vatdata)
					->withSettings($this->acsettings)
					->withVehicledata($vehicle_data)
					->withSearch(false)
					->withDepartment($department)
					->withPrintid($lastid)
					->withFormdata($this->formData)
					->withLocdefault($locdefault)
					->withSaleslocation($sales_location)
					->withBanks($banks)
					->withRvvoucher($vchrdata)
					->withRvid($rvid=9)
					->withMatservice($this->matservice->is_active)
					->withJobtype($jobtype)
					->withPrint($print)
					->withIsdept($is_dept)
					->withDepartments($departments)
					->withDeptid($deptid)
					->withFooter(isset($footertxt)?$footertxt->description:'')
					->withRoundoff($round_off)
					->withData($data);
	}
	
	public function save(Request $request) { //echo '<pre>';print_r($request->all());exit;
		
		if(Session::get('department')==1) {
			if( $this->validate(
				$request, 
				[ 'customer_name' => 'required','customer_id' => 'required',
				 //'vehicle_name' => 'required','vehicle_id' => 'required',
				 'job_id' => ($this->formData['job']==1)?'required':'nullable',   //'required',
				 /* 'item_code.*'  => 'required', 'item_id.*' => 'required',
				 'unit_id.*' => 'required',
				 'quantity.*' => 'required',
				 'cost.*' => 'required' */
				],
				['customer_name.required' => 'Customer Name is required.','customer_id.required' => 'Customer name is invalid.',
				 //'vehicle_name.required' => 'Vehicle Name is required.','vehicle_id.required' => 'Vehicle name is invalid.',
				 'job_id' => 'Job is required.',
				 /* 'item_code.*.required'   => 'Item code is required.', 'item_id.*' => 'Item code is invalid.',
				 'unit_id.*' => 'Item unit is required.',
				 'quantity.*' => 'Item quantity is required.',
				 'cost.*' => 'Item cost is required.' */
				]
			)) {

				return redirect('job_invoice/add')->withInput()->withErrors();
			}
		} else {
			
			if( $this->validate(
				$request, 
				[ //'reference_no' => ($this->formData['reference_no']==1)?'required':'nullable', 
				 'voucher_no' => 'required|unique:sales_invoice,voucher_no,NULL,id,deleted_at,NULL',
				 'customer_name' => 'required','customer_id' => 'required',
				 //'vehicle_name' => 'required','vehicle_id' => 'required',
				 'job_id' => ($this->formData['job']==1)?'required':'nullable',   //'required',
				 /* 'item_code.*'  => 'required', 'item_id.*' => 'required',
				 'unit_id.*' => 'required',
				 'quantity.*' => 'required',
				 'cost.*' => 'required' */
				],
				[//'reference_no' => 'Reference no. is required.',
				 'voucher_no' => 'Voucher no should be unique.',
				 'customer_name.required' => 'Customer Name is required.','customer_id.required' => 'Customer name is invalid.',
				 //'vehicle_name.required' => 'Vehicle Name is required.','vehicle_id.required' => 'Vehicle name is invalid.',
				 'job_id' => 'Job is required.',
				 /* 'item_code.*.required'   => 'Item code is required.', 'item_id.*' => 'Item code is invalid.',
				 'unit_id.*' => 'Item unit is required.',
				 'quantity.*' => 'Item quantity is required.',
				 'cost.*' => 'Item cost is required.' */
				]
			)) {

				return redirect('job_invoice/add')->withInput()->withErrors();
			}
			
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
		if(Session::has('sales_acnt'))
			Session::forget('sales_acnt');
		if(Session::has('acnt_master'))
			Session::forget('acnt_master');
		if(Session::has('lpo_no'))
			Session::forget('lpo_no');
			
		$id = $this->sales_invoice->create($request->all());
		if( $id ){ 
		    //AUTO COST REFRESH CHECK ENABLE OR NOT
			if($this->mod_autocost->is_active==1) {
				$this->objUtility->reEvalItemCostQuantity($request->get('item_id'),$this->acsettings);
				app('App\Http\Controllers\UtilityController')->updateItemCurrentQty($request->get('item_id')); //OCT24
			}
			
		    
			$attributes = $request->all();
			
			if( isset($attributes['is_rv']) && $attributes['is_rv']==1 ) { 
				$this->RVformSet($attributes, $id);
				$this->receipt_voucher->create($request->all());
			}
			Session::flash('message', 'Job Invoice added successfully.');
	}	else{
			Session::flash('error', 'Something went wrong, Invoice failed to add!');
	}
		return redirect('job_invoice/add');
	}
	
		private function RVformSet($attributes,$id) {
		
		$ispdc = false; 
		$ar = [1,2]; $rv_amount = 0; $voucherno = '';
		$remrv = (isset($attributes['remove_rv']))?$attributes['remove_rv']:'';
		$vt = DB::table('account_setting')->where('id',$attributes['voucher_id'])->select('voucher_name')->first();
		foreach($ar as $val) {
			if($val==1) {
				foreach($attributes['voucher_type'] as $rkey => $rval) {
					$acname[] = $attributes['rv_dr_account'][$rkey];
					$acid[] = $attributes['rv_dr_account_id'][$rkey];
					$grparr[] = $attributes['voucher_type'][$rkey];
					if($attributes['voucher_type'][$rkey]=='CASH') {
						$pryarr[] = ''; $vtype = 'CASH'; $pmode[] = 0;
					} else if($attributes['voucher_type'][$rkey]=='PDCR') {
						$ispdc = true; $vtype = 'PDCR';
						$pryarr[] = $attributes['dr_account_id']; $pmode[] = 2;
					} else if($attributes['voucher_type'][$rkey]=='BANK') {
						$ispdc = true; $vtype = 'BANK';
						$pryarr[] = $attributes['dr_account_id']; $pmode[] = 1;
					}
					$siarr[] = $id; $btarr[] = 'SI'; $invarr[] = $id; $actypearr[] = 'Dr';
					$desarr[] = isset($attributes['description'])?(($attributes['description']=='')?$vt->voucher_name:$attributes['description']):$vt->voucher_name;
					$refarr[] = $attributes['voucher_no']; $voucherno .= ($voucherno=='')?$attributes['voucher_no']:','.$attributes['voucher_no'];
					$lnarr[] = $attributes['rv_amount'][$rkey];
					$bnkarr[] = (isset($attributes['bank_id'][$rkey]))?$attributes['bank_id'][$rkey]:'';
					$chqarr[] = (isset($attributes['cheque_no'][$rkey]))?$attributes['cheque_no'][$rkey]:'';
					$chqdtarr[] = (isset($attributes['cheque_date'][$rkey]) && $attributes['cheque_date'][$rkey]!='')?date('Y-m-d', strtotime($attributes['cheque_date'][$rkey])):'';
					$jearr[] = (isset($attributes['rowid'][$rkey]))?$attributes['rowid'][$rkey]:'';
					$vatamt[] = ''; $actarr[] = ''; $jbarr[] = ''; $dptarr[] = ''; $prtnarr[] = ''; $trarr[] = ''; 
					$rv_amount += $attributes['rv_amount'][$rkey];
				}
			} else {
				//$ispdc = false;
				$acname[] = $attributes['customer_name'];
				$acid[] = $attributes['dr_account_id'];
				$grparr[] = 'CUSTOMER'; //$vtype = 9;
				$siarr[] = $id; $btarr[] = 'SI'; $invarr[] = $id; $actypearr[] = 'Cr';
				$desarr[] = isset($attributes['description'])?(($attributes['description']=='')?$vt->voucher_name:$attributes['description']):$vt->voucher_name;
				$jearr[] = (isset($attributes['rowidcr']))?$attributes['rowidcr']:'';
				$refarr[] = $voucherno; 
				$lnarr[] = $rv_amount;
				$pryarr[] = ''; $vatamt[] = ''; $actarr[] = ''; $jbarr[] = ''; $dptarr[] = ''; $prtnarr[] = ''; $trarr[] = ''; $pmode[] = '';
				$bnkarr[] = ''; $chqarr[] = ''; $chqdtarr[] = '';
			}
		}
		
		$request->merge(['from_jv' => 1]);
		$request->merge(['chktype' => ($ispdc)?'PDCR':'']);
		$request->merge(['is_onaccount' => 1]);
		$request->merge(['voucher' => $attributes['rv_voucher'][0] ]);
		$request->merge(['voucher_type' => $vtype]);
		$request->merge(['voucher_date' => ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date'])) ]);
		$request->merge(['voucher_no' => $attributes['rv_voucher_no'][0] ]); 
		$request->merge(['account_name' => $acname]);
		$request->merge(['account_id' => $acid]);
		$request->merge(['group_id' => $grparr]);
		$request->merge(['vatamt' => $vatamt]);
		$request->merge(['sales_invoice_id' => $siarr]);
		$request->merge(['bill_type' => $btarr]);
		$request->merge(['description' => $desarr]);
		$request->merge(['reference' => $refarr]);
		$request->merge(['je_id' => $jearr]);
		$request->merge(['inv_id' => $invarr]);
		$request->merge(['actual_amount' => $actarr]);
		$request->merge(['account_type' => $actypearr]);
		$request->merge(['line_amount' => $lnarr]);
		$request->merge(['job_id' => $jbarr]);
		$request->merge(['bank_id' => $bnkarr]);
		$request->merge(['cheque_no' => $chqarr]);
		$request->merge(['cheque_date' => $chqdtarr]);
		$request->merge(['department' => $dptarr]);
		$request->merge(['partyac_id' => $pryarr]);
		$request->merge(['party_name' => $prtnarr]);
		$request->merge(['tr_id' => $trarr]);
		$request->merge(['difference' => 0]);
		$request->merge(['remove_item' => $remrv]);
		$request->merge(['trn_no' => '']);
		$request->merge(['curno' => '']);
		$request->merge(['debit' => $rv_amount]);
		$request->merge(['credit' => $rv_amount]);
		$request->merge(['currency_id' => $pmode]);
		
		DB::table('sales_invoice')->where('id',$id)->update(['advance' => $rv_amount,
							'balance' => DB::raw('net_total - '.$rv_amount) ]);
							
		/* DB::table('sales_invoice')->where('id',$id)->update(['advance' => DB::raw('advance + '.$rv_amount),
							'balance' => (DB::raw('balance' > 0)?DB::raw('balance - '.$rv_amount):DB::raw('net_total - '.$rv_amount) ]); */
		
		return true;
	}
	
	
	public function destroy($id)
	{
		if( $this->sales_invoice->check_invoice($id) ) {
			$this->sales_invoice->delete($id);
			Session::flash('message', 'Job invoice deleted successfully.');
		} else {
			Session::flash('error', 'Job invoice is already in use, you can\'t delete this!');
		}
		
		return redirect('job_invoice');
	}
	
	public function checkRefNo(Request $request) {

		$check = $this->sales_invoice->check_reference_no($request->get('reference_no'), $request->get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
	
	public function edit($id) { 

		$data = array();
		$itemmaster = $this->itemmaster->activeItemmasterList();
		$terms = $this->terms->activeTermsList();
		$jobs = $this->jobmaster->activeJobmasterList();
		$currency = $this->currency->activeCurrencyList();
		$orderrow = $this->sales_invoice->findPOdata($id);
	    //echo '<pre>';print_r($orderrow);exit;
		$itemdesc = $this->makeTreeArr($this->sales_invoice->getItemDesc($id));
		$custdata = DB::table('account_master')->where('id',$orderrow->customer_id)->select('cl_balance','credit_limit','pdc_amount')->first();
		$vouchers = $this->accountsetting->find($orderrow->voucher_id);
		$jobdesc = $this->sales_invoice->getjobDescription($id);
		$jobtype = DB::table('jobtype')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		
		//$vouchers = $this->accountsetting->getAccountSettingsDefault2($vid=3);
		//$optvouchers = $this->accountsetting->getAccountSettingsDefault2($vid=3);
		//echo '<pre>';print_r($optvouchers);exit;
		
		$print = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','JI')
							->where('report_view_detail.is_default',1)
							->select('report_view_detail.id')
							->first();
							
		if($this->matservice->is_active==1) {
			$orditems = $this->sales_invoice->getItems($id,'itm');
			$seritems = $this->sales_invoice->getItems($id,'ser');
			$view = 'editms';
		} else {
			$seritems = null;
			$orditems = $this->sales_invoice->getItems($id);
			$view = 'edit';
		}
		
		if($this->mod_si_roundoff->is_active==1)
			$round_off = true;
		else
			$round_off = false;
		
		//RV FORM ENTRY............
		$rventry = [];
		$banks = $this->bank->activeBankList();
		$vchrdata = $this->getVoucherRV(9,'CASH');
		if($orderrow->is_rventry==1) {
			$rventry = DB::table('receipt_voucher')->where('receipt_voucher.sales_invoice_id',$orderrow->id)
							->join('receipt_voucher_entry', 'receipt_voucher_entry.receipt_voucher_id', '=', 'receipt_voucher.id')
							->join('account_master', 'account_master.id', '=', 'receipt_voucher_entry.account_id')
							//->where('receipt_voucher_entry.entry_type','Dr')
							->select('receipt_voucher_entry.*','account_master.master_name','receipt_voucher.voucher_type','receipt_voucher.id AS rvid',
									'receipt_voucher.voucher_no')
							->get(); //echo '<pre>';print_r($rventry);exit;
		}
		
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
		//$vouchers = $this->accountsetting->getAccountSettingsDefault2($vid=3,$is_dept,$deptid); //
	//	echo '<pre>';print_r($rventry);exit;
			$photos = DB::table('si_photos')->where('invoice_id',$id)->get(); 
		return view('body.jobinvoice.'.$view)
					->withItems($itemmaster)
					->withTerms($terms)
					->withJobs($jobs)
					->withCurrency($currency)
					->withOrderrow($orderrow)
					->withOrditems($orditems)
					->withItemdesc($itemdesc)
					->withVatdata($this->vatdata)
					->withCustdata($custdata)
					->withSettings($this->acsettings)
					->withFormdata($this->formData)
					->withItemlocedit([''])
					->withVouchers($vouchers)
					->withBanks($banks)
					->withRvvoucher($vchrdata)
					->withRvid($rvid=9)
					->withRventry($rventry)
					->withSeritems($seritems)
					->withJobdesc($jobdesc)
					->withJobtype($jobtype)
					//->withOptvouchers($optvouchers)
					->withPrint($print)
					->withIsdept($is_dept)
					->withDepartments($departments)
					->withDeptid($deptid)
					->withPhotos($photos)
					->withRoundoff($round_off)
					->withData($data);

	}
	
	public function update(Request $request)
	{
	    //echo '<pre>';print_r($request->all());exit;
		$id = $request->input('sales_invoice_id');
		if( $this->validate(
			$request, 
			[//'reference_no' => 'required',
			 'customer_name' => 'required','customer_id' => 'required',
			 //'vehicle_name' => 'required','vehicle_id' => 'required',
			 'job_id' => ($this->formData['job']==1)?'required':'nullable',
			/*  'item_code.*'  => 'required', 'item_id.*' => 'required',
			 'unit_id.*' => 'required',
			 'quantity.*' => 'required',
			 'cost.*' => 'required' */
			],
			[//'reference_no.required' => 'Reference no. is required.',
			 'customer_name.required' => 'Customer Name is required.','customer_id.required' => 'Customer name is invalid.',
			 //'vehicle_name.required' => 'Vehicle Name is required.','vehicle_id.required' => 'Vehicle name is invalid.',
			 'job_id' => 'Job is required.',
			 /* 'item_code.*.required'   => 'Item code is required.', 'item_id.*' => 'Item code is invalid.',
			 'unit_id.*' => 'Item unit is required.',
			 'quantity.*' => 'Item quantity is required.',
			 'cost.*' => 'Item cost is required.' */
			]
		)) {
			//echo '<pre>';print_r($request->flash());exit;
			return redirect('job_invoice/edit/'.$id)->withInput()->withErrors();
		}
		
		
		
		if( $this->sales_invoice->update($id, $request->all()) ){
		    //AUTO COST REFRESH CHECK ENABLE OR NOT
			if($this->mod_autocost->is_active==1) {
				$this->objUtility->reEvalItemCostQuantity($request->get('item_id'),$this->acsettings);
				app('App\Http\Controllers\UtilityController')->updateItemCurrentQty($request->get('item_id')); //OCT24
			}
		    $attributes = $request->all();
		if( isset($attributes['is_rv']) && $attributes['is_rv']==1 ) { 
				$sirv = DB::table('receipt_voucher')->where('sales_invoice_id',$id)->select('id')->first();
				//echo '<pre>';print_r($sirv);exit;
				if($sirv) {
					$this->RVformSet($attributes, $id);
					$this->receipt_voucher->update($request->get('rvedit_id'), $request->all());
				} else {
					$this->RVformSet($attributes, $id);
					$this->receipt_voucher->create($request->all());
				}
			}
		
			Session::flash('message', 'Job invoice updated successfully');
	}	else
			Session::flash('error', 'Something went wrong, Invoice failed to update!');
		
		return redirect('job_invoice');
	}
	
	
	public function viewonly($id) { 

		$data = array();
		$itemmaster = $this->itemmaster->activeItemmasterList();
		$terms = $this->terms->activeTermsList();
		$jobs = $this->jobmaster->activeJobmasterList();
		$currency = $this->currency->activeCurrencyList();
		$orderrow = $this->sales_invoice->findPOdata($id);
	    //echo '<pre>';print_r($orderrow);exit;
		$itemdesc = $this->makeTreeArr($this->sales_invoice->getItemDesc($id));
		$custdata = DB::table('account_master')->where('id',$orderrow->customer_id)->select('cl_balance','credit_limit','pdc_amount')->first();
		$vouchers = $this->accountsetting->find($orderrow->voucher_id);
		$jobdesc = $this->sales_invoice->getjobDescription($id);
		$jobtype = DB::table('jobtype')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		
		//$vouchers = $this->accountsetting->getAccountSettingsDefault2($vid=3);
		//$optvouchers = $this->accountsetting->getAccountSettingsDefault2($vid=3);
		//echo '<pre>';print_r($optvouchers);exit;
		
		$print = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','JI')
							->where('report_view_detail.is_default',1)
							->select('report_view_detail.id')
							->first();
							
		if($this->matservice->is_active==1) {
			$orditems = $this->sales_invoice->getItems($id,'itm');
			$seritems = $this->sales_invoice->getItems($id,'ser');
			$view = 'editms';
		} else {
			$seritems = null;
			$orditems = $this->sales_invoice->getItems($id);
			$view = 'edit';
		}
		
		if($this->mod_si_roundoff->is_active==1)
			$round_off = true;
		else
			$round_off = false;
		
		//RV FORM ENTRY............
		$rventry = [];
		$banks = $this->bank->activeBankList();
		$vchrdata = $this->getVoucherRV(9,'CASH');
		if($orderrow->is_rventry==1) {
			$rventry = DB::table('receipt_voucher')->where('receipt_voucher.sales_invoice_id',$orderrow->id)
							->join('receipt_voucher_entry', 'receipt_voucher_entry.receipt_voucher_id', '=', 'receipt_voucher.id')
							->join('account_master', 'account_master.id', '=', 'receipt_voucher_entry.account_id')
							//->where('receipt_voucher_entry.entry_type','Dr')
							->select('receipt_voucher_entry.*','account_master.master_name','receipt_voucher.voucher_type','receipt_voucher.id AS rvid',
									'receipt_voucher.voucher_no')
							->get(); //echo '<pre>';print_r($rventry);exit;
		}
		
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
		//$vouchers = $this->accountsetting->getAccountSettingsDefault2($vid=3,$is_dept,$deptid); //
	//	echo '<pre>';print_r($rventry);exit;
			$photos = DB::table('si_photos')->where('invoice_id',$id)->get(); 
		return view('body.jobinvoice.viewonly')
					->withItems($itemmaster)
					->withTerms($terms)
					->withJobs($jobs)
					->withCurrency($currency)
					->withOrderrow($orderrow)
					->withOrditems($orditems)
					->withItemdesc($itemdesc)
					->withVatdata($this->vatdata)
					->withCustdata($custdata)
					->withSettings($this->acsettings)
					->withFormdata($this->formData)
					->withItemlocedit([''])
					->withVouchers($vouchers)
					->withBanks($banks)
					->withRvvoucher($vchrdata)
					->withRvid($rvid=9)
					->withRventry($rventry)
					->withSeritems($seritems)
					->withJobdesc($jobdesc)
					->withJobtype($jobtype)
					//->withOptvouchers($optvouchers)
					->withPrint($print)
					->withIsdept($is_dept)
					->withDepartments($departments)
					->withDeptid($deptid)
					->withPhotos($photos)
					->withRoundoff($round_off)
					->withData($data);

	}
	
	
	public function getIteminfo($id) { 
	//	echo '<pre>';print_r($id);exit;
		$data = array();
		$itemmaster = $this->itemmaster->activeItemmasterList();
	
		$jobs = $this->jobmaster->activeJobmasterList();
	
		$orderrow = $this->sales_invoice->findPOdata($id);
	  //  echo '<pre>';print_r($orderrow);exit;
		$itemdesc = $this->makeTreeArr($this->sales_invoice->getItemDesc($id));
		$custdata = DB::table('account_master')->where('id',$orderrow->customer_id)->select('cl_balance','credit_limit','pdc_amount')->first();
		$vouchers = $this->accountsetting->find($orderrow->voucher_id);
		$info = DB::table('item_info')->where('si_no',$id)->select('in_out')->get();
		//echo '<pre>';print_r($info);exit;
			$seritems = null;
			$orditems = $this->sales_invoice->getItems($id);
			
		
		
	
		return view('body.jobinvoice.item_info')
					->withItems($itemmaster)
				
					->withJobs($jobs)
					->withInfo($info)
					->withOrderrow($orderrow)
					->withOrditems($orditems)
					->withItemdesc($itemdesc)
					->withVatdata($this->vatdata)
					->withCustdata($custdata)
					->withSettings($this->acsettings)
				
				
					->withVouchers($vouchers)
				
					
				
					->withData($data);

	}
	public function getSaveinfo(Request $request)
	{
		$id = $request->input('sales_invoice_id');

		
		if( $this->sales_invoice->item_update($id, $request->all()) )
	
			Session::flash('message', 'Item In/Out updated successfully');
	
		else
			Session::flash('error', 'Something went wrong, Invoice failed to update!');
		
		return redirect('job_invoice');
	}
	public function ajax_getcode($group_id)
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

	}
	
	
	public function getCustomer($num=null)
	{
		$data = array();
		$customers = $this->accountmaster->getCustomerList();
		return view('body.salesinvoice.customer')
					->withCustomers($customers)
					->withNum($num)
					->withData($data);
	}
	
	public function getSalesman()
	{
		$data = array();
		$salesmans = $this->salesman->getSalesmanList();
		return view('body.salesinvoice.salesman')
					->withSalesmans($salesmans)
					->withData($data);
	}
	
	public function getItem($num)
	{
		$data = array();
		$itemmaster = $this->itemmaster->getActiveItemmasterList();
		return view('body.salesinvoice.item')
					->withItems($itemmaster)
					->withNum($num)
					->withData($data);
	}
	
	public function getVoucher($id) {
		
		 $row = $this->accountsetting->getCrVoucherByID($id);//print_r($row);
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
								'id' => $row->id);
		
	}
	
	public function getDeptVoucher($id) {
		
		 $row = $this->accountsetting->getCrVoucherByDept($id);//print_r($row);
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
								'voucher_id' => $row->voucher_id,
								'voucher_name' => $row->voucher_name);
		
	}
	
	public function checkInvoice() {

		$check = $this->sales_invoice->check_invoice_id( $request->get('purchase_invoice_id') );
		$isAvailable = ($check) ? false : true;
		echo $isAvailable;
	}
	
	public function getInvoice()
	{
		$data = array();
		$invoices = $this->sales_invoice->getInvoice();//echo '<pre>';print_r($pidata);exit;
		return view('body.salesinvoice.invoice')
					->withInvoices($invoices)
					->withData($data);
	}
	
	public function getInvoiceByCustomer($customer_id,$no=null)
	{
		$invoices = $this->sales_invoice->getCustomerInvoice($customer_id);
		$openbalances = $this->sales_invoice->getOpenBalances($customer_id);
		//$advance = $this->sales_invoice->getAdvance($customer_id); 	//echo '<pre>';print_r($advance);exit;
		return view('body.salesinvoice.custinvoice')
					->withNum($no)
					->withOpenbalances($openbalances)
					->withInvoices($invoices);
					//->withAdvance($advance);
	}
	
	public function setSessionVal(Request $request)
	{
		//print_r($request->all());
		Session::put('voucher_id', $request->get('vchr_id'));
		Session::put('voucher_no', $request->get('vchr_no'));
		Session::put('reference_no', $request->get('ref_no'));
		Session::put('voucher_date', $request->get('vchr_dt'));
		Session::put('lpo_date', $request->get('lpo_dt'));
		Session::put('sales_acnt', $request->get('sales_ac'));
		Session::put('acnt_master', $request->get('ac_mstr'));

	}
	
	private function splitItems($items)
	{
		$ar = [];
		foreach($items as $val) {
			if($val->item_type==0)
				$ar['items'][] = $val;
			else
				$ar['service'][] = $val;
			
		}
		return $ar;
	}
	
	public function getPrint($id,$rid=null)
	{
		$viewfile = DB::table('report_view_detail')->where('id', $rid)->select('print_name')->first(); 
		
		if($viewfile->print_name=='') {
			$attributes['document_id'] = $id; //echo "892 : ".$this->number_to_word(12495);exit;
			$attributes['is_fc'] = ($fc)?1:'';
			$result = $this->sales_invoice->getInvoiceById($attributes);
			$titles = ['main_head' => 'Job Invoice','subhead' => 'Job Invoice'];//echo '<pre>';print_r($result['details']);exit;
			$itemdesc = $this->makeTreeArr($this->sales_invoice->getItemDesc($id));
			$words = $this->number_to_word($result['details']->net_total);
			$arr = explode('.',number_format($result['details']->net_total,2));
			if(sizeof($arr) >1 ) {
				if($arr[1]!=00) {
					$dec = $this->number_to_word($arr[1]);
					$words .= ' and Fils '.$dec.' Only';
				} else 
					$words .= ' Only';
			} else
				$words .= ' Only';
			
			
			$jobdesc = DB::table('jobinvoice_details')->where('jobinvoice_id',$id)
							->where('status',1)->where('deleted_at','0000-00-00 00:00:00')
							->select('description','comment')->orderBy('id','ASC')->get();
			//split item and service
			$items = null;
			if($this->matservice->is_active==1) {
				$items = $this->splitItems($result['items']);
			}
			
			return view('body.jobinvoice.newprint') //newprint1s  newprint testpt
						->withDetails($result['details'])
						->withTitles($titles)
						->withAmtwords($words)
						->withFc($attributes['is_fc'])
						->withItemdesc($itemdesc)
						->withEstitems($items)
						->withJobdesc($jobdesc)
						->withItems($result['items']);
		} else {
			$path = app_path() . '/stimulsoft/helper.php';
			
			if(env('STIMULSOFT_VER')==2)
			        return view('body.reports')->withPath($path)->withView($viewfile->print_name);
			   else
			        return view('body.jobinvoice.viewer')->withPath($path)->withView($viewfile->print_name);
			        
			
		}
		
	}
	
	private function makeTreeArr($result) {
		
		$childs = array();
		foreach($result as $item)
			$childs[$item->item_detail_id][] = $item;
		
		return $childs;
	}
	
	public function getPrintdo($id)
	{
		
		$attributes['document_id'] = $id;
		$result = $this->sales_invoice->getInvoiceById($attributes);
		$titles = ['main_head' => 'Delivery Order','subhead' => 'Delivery Order'];//echo '<pre>';print_r($result);exit;
		return view('body.salesinvoice.printdo')
					->withDetails($result['details'])
					->withTitles($titles)
					->withItems($result['items']);
		
	}
	
	public function getOrderHistory($customer_id)
	{
		$data = array();
		$items = $this->sales_invoice->getOrderHistory($customer_id);//echo '<pre>';print_r($items);exit;
		return view('body.salesinvoice.history')
					->withItems($items)
					->withData($data);
	}
	
	public function checkVchrNo(Request $request) {

		$check = $this->sales_invoice->check_voucher_no($request->get('voucher_no'), $request->get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
	
	public function getInvoiceSetByCustomer($customer_id,$no=null)
	{
		$invoices = $this->sales_invoice->getCustomerInvoice($customer_id);
		$openbalances = $this->sales_invoice->getOpenBalances($customer_id);
		$advance = $this->sales_invoice->getAdvance($customer_id); 	//echo '<pre>';print_r($advance);exit;
		return view('body.salesinvoice.custinvoiceset')
					->withNum($no)
					->withOpenbalances($openbalances)
					->withInvoices($invoices)
					->withAdvance($advance);
	}
	public function getJobInvoice()
	{
		$data = array();
		$invoices = $this->sales_invoice->getInvoice();
		
		//echo '<pre>';print_r($orders);exit;
		return view('body.jobinvoice.ji')
					->withInvoices($invoices)
					->withData($data);
	}
	
	
	protected function makeTree($result)
	{
		$childs = array();
		foreach($result as $item)
			$childs[$item->voucher_name][] = $item;
		
		return $childs;
	}
	
	protected function groupbyVoucherNo($result)
	{
		$childs = array();
		foreach($result as $items)
			foreach($items as $item)
				$childs[$item->voucher_name][$item->voucher_no][] = $item;
		
		return $childs;
	}
	
	protected function groupbyItemwise($result)
	{
		$childs = array();
		//foreach($result as $items)
			foreach($result as $item)
				$childs[$item->item_id][] = $item;
		
		return $childs;
	}
	
	protected function makeTreeTC($result)
	{
		$childs = array();
		foreach($result as $item)
			$childs[$item->tax_code][] = $item;
		
		return $childs;
	}
	
	public function getSearch_old()
	{
		$data = array();
		
		$reports = $this->sales_invoice->getReport($request->all());
		
		if($request->get('search_type')=="summary")
			$voucher_head = 'Job Invoice Summary';
		else if($request->get('search_type')=="sales_register") {
			$voucher_head = 'Job Register Summary';
			$reports = $this->makeTree($reports);
		} else if($request->get('search_type')=="tax_code") {
			$voucher_head = 'Job Invoice by Tax Code';
			$reports = $this->makeTreeTC($reports);
		}
		
		//echo '<pre>';print_r($reports);exit;
		return view('body.jobinvoice.preprint')
					->withReports($reports)
					->withVoucherhead($voucher_head)
					->withType($request->get('search_type'))
					->withFromdate($request->get('date_from'))
					->withTodate($request->get('date_to'))
					->withI(0)
					->withSalesman($request->get('salesman'))
					->withSettings($this->acsettings)
					->withData($data);
	}
	
	public function dataExport_old()
	{
		$data = array();
		$request->merge(['type' => 'export']);
		$reports = $this->sales_invoice->getReport($request->all());
		
		if($request->get('search_type')=="summary")
			$voucher_head = 'Job Invoice Summary';
		else if($request->get('search_type')=="sales_register") {
			$voucher_head = 'Job Register Summary';
		} 
		
		 //echo '<pre>';print_r($reports);exit;
		
		if($request->get('search_type')=='sales_register') {
			
			$datareport[] = ['SI.No.','SI.#', 'Vchr.Date', 'Customer','TRN No','Salesman','Gross Amt.','VAT Amt.','Net Total'];
			$i=0;
		
			foreach ($reports as $row) {
				//echo '<pre>';print_r($row);exit;
				$i++;
				$datareport[] = [ 'si' => $i,
								  'po' => $row['voucher_no'], 
								  'vchr_dt' => date('d-m-Y',strtotime($row['voucher_date'])),
								  'customer' => $row['master_name'],
								  'trn' => $row['vat_no'],
								  'salesman' => $row['salesman'],
								  'gross' => $row['total'],
								  'vat' => $row['vat_amount'],
								  'total' => $row['net_total']
								];
			}
		} else {
			
			$datareport[] = ['SI.No.','SI.#', 'Vchr.Date', 'Customer','TRN No','Salesman','Gross Amt.','VAT Amt.','Net Total'];
			$i=0;
			foreach ($reports as $row) {
					$i++;
					$datareport[] = [ 'si' => $i,
									  'po' => $row['voucher_no'],
									  'vchr_dt' => date('d-m-Y',strtotime($row['voucher_date'])),
									  'customer' => $row['master_name'],
									  'trn' => $row['vat_no'],
									  'salesman' => $row['salesman'],
									  'gross' => $row['total'],
									  'vat' => $row['vat_amount'],
									  'total' => $row['net_total']
									];
			}
		}
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
		   
			$what   = "\x00-\x20";    //all white-spaces and control chars
		}
	   
		return trim( preg_replace( "/[".$what."]+/" , $with , $str ) , $what );
	}
	protected function makeTreeid($result)
	{
		$childs = array();
		foreach($result as $item)
		$childs[$item->id][] = $item;
		//	$childs[$item['id']][] = $item;
		
		return $childs;
	}
	private function str_replace_last( $search , $replace , $str ) {
		if( ( $pos = strrpos( $str , $search ) ) !== false ) {
			$search_length  = strlen( $search );
			$str    = substr_replace( $str , $replace , $pos , $search_length );
		}
		return $str;
	}
	
	public function getVoucherRV($id,$type) {
		
		 $row = $this->accountsetting->getDrVoucherByID2($id);//return $row;//print_r($row);
		 $voucher = $master_name = $id = null;
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
			$rid = $row->asid;
		 }
		 
		 return $result = array('voucher_no' => $voucher,
								'account_name' => $master_name, 
								'id' => $id,
								'rid' => $rid);
		
	}
	
	
	
	
	public function getvehSearch(Request $request)
	{
		$data = array();
		
		$vehicle_data = $this->jobmaster->getVehicleDetails($request->all());//echo '<pre>';print_r($vehicle_data);exit;
		$itemmaster = $this->itemmaster->activeItemmasterList();
		$terms = $this->terms->activeTermsList();
		$jobs = $this->jobmaster->activeJobmasterList();
		$currency = $this->currency->activeCurrencyList();
		$vouchers = $this->accountsetting->getAccountSettingsDefault2($vid=3);
		$department = $this->department->activeDepartmentList();
		return view('body.jobinvoice.add')
					->withItems($itemmaster)
					->withTerms($terms)
					->withJobs($jobs)
					->withCurrency($currency)
					->withVouchers($vouchers)
					->withVatdata($this->vatdata)
					->withSettings($this->acsettings)
					->withVehicledata($vehicle_data)
					->withSearch(true)
					->withDepartment($department)
					->withData($data);
		
	}
	protected function makeTreeSup($result)
	{
		$childs = array();
		foreach($result as $item)
		//echo '<pre>';print_r($item); exit();
		$childs[$item->customer][] = $item;
		
			
		return $childs;
	}
	public function getSearch(Request $request)
	{
		$data = array();
		$cusid = $itemid = '';
		
		if($request->get('search_type')=="summary") {
			$report = $this->sales_invoice->getReportVehicle($request->all());
			$reports = $this->makeTreeSup($report);
            //echo '<pre>';print_r($report);exit;
			$voucher_head = 'Job Invoice Summary';
		}else if($request->get('search_type')=="detail") {
			$voucher_head = 'Job Invoice Detail';
			$report = $this->sales_invoice->getReportVehicle($request->all());
		    $reports = $this->makeTreeSup($report);
		//	$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];
			
		}else if($request->get('search_type')=="sales_register") {
			$reports = $this->sales_invoice->getReportVehicle($request->all());
			$voucher_head = 'Job Invoice Summary';//'Job Register Summary';
			$reports = $this->makeTree($reports);
		} else if($request->get('search_type')=="details") {
			$reports = $this->makeTree($this->sales_invoice->getReportVehicle($request->all()));
			$voucher_head = 'Job Invoice Detail';
			//$reports = $this->groupbyVoucherNo($reports);
		} else if($request->get('search_type')=="job_orderclosed") {
			$report = $this->sales_invoice->getclosedJobs($request->all());
			$voucher_head = 'Job Invoice by Closed Jobs';
			$reports = $this->makeTreeSup($report);
		} else if($request->get('search_type')=="vehicle") {
			$reports = $this->sales_invoice->getReportVehicle($request->all());
			$voucher_head = 'Job Invoice by Vehiclewise';
		} else if($request->get('search_type')=="job_order") {
			$report = $this->sales_invoice->getPendingJobs($request->all());
			$reports = $this->makeTreeSup($report);
			//$reports = $this->makeTree($report);
			$voucher_head = 'Job Order(Pending)';
			//$reports = $this->getPendingOrder($reports);
		}else if($request->get('search_type')=='customer') {
			$voucher_head = 'Job Invoice  by Customerwise';
			$report = $this->sales_invoice->getReportVehicle($request->all());
			$reports = $this->makeTreeSup($report);
			//echo '<pre>';print_r($reports); exit();
			$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];
		
		}
			if($request->get('customer_id')!==null)
				$custid = implode(',', $request->get('customer_id'));
			else
				$custid = '';
			$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];
		//echo '<pre>';print_r($reports);exit;
		return view('body.jobinvoice.preprint')
					->withReport($report)
					->withVoucherhead($voucher_head)
					->withType($request->get('search_type'))
					->withFromdate($request->get('date_from'))
					->withTodate($request->get('date_to'))
					->withI(0)
					->withTitles($titles)
					->withCustomer($custid)
					->withItem($itemid)
					->withVehicleno($request->get('vehicle_no'))
					->withSalesman($request->get('salesman'))
					->withCustomerid($request->get('customer_id'))
					->withSettings($this->acsettings)
					->withData($data);
	}
	
	public function dataExport(Request $request)
	{
		$data = array();
		//$request->merge(['type' => 'export']);
		
		$datareport[] = ['','','','',strtoupper(Session::get('company')),'','',''];
		$datareport[] = ['','','','','','',''];
		$reports = $this->sales_invoice->getReportVehicle($request->all());
		
		 //echo '<pre>';print_r($reports);exit;
		
		if($request->get('search_type')=='summary') {
			$voucher_head = 'Job Invoice Summary';
			$report = $this->makeTreeSup($reports);
			//echo '<pre>';print_r($report);exit;
			$datareport[] = ['','','','',strtoupper($voucher_head), '','',''];
		  $datareport[] = ['','','','','','',''];
			
			foreach ($report as $key => $report) {
				
				
				$datareport[] = ['JI.No.','JI.Date', 'Customer','Gross Amt.','Discount','Total Amt','VAT Amt.','Net Total'];
				$nettotal=$netdiscount=$netvat_amount=$net_amount_total=$total_amt=0;
				
				foreach ($report as $row) {
					
				  $nettotal += $row->total;
				  $netdiscount += $row->discount;
				  $netvat_amount += $row->vat_amount;
				  $net_amount_total += $row->net_total;
				  $total_amt+=$row->total-$row->discount;
											  
					$datareport[] = [ 'jino' => $row->voucher_no, 
									  'vchr_dt' => date('d-m-Y',strtotime($row->voucher_date)),
									  'customer' => $row->master_name,
									  'gross' => number_format($row->total,2),
									  'discount' => number_format($row->discount,2),
									  'amt'=>number_format($row->total-$row->discount,2),
									  'vat' => number_format($row->vat_amount,2),
									  'total' => number_format($row->net_total,2)
									];
				}
				$datareport[] = ['','','','','','',''];
				$datareport[] = ['','','Total:',number_format($nettotal,2),number_format($netdiscount,2),number_format($total_amt,2),number_format($netvat_amount,2),number_format($net_amount_total,2)];
			}
			
		} else if($request->get('search_type')=='detail') {
			
			$voucher_head = 'Job Invoice Detail';
			$reports = $this->makeTreeSup($reports);
			$datareport[] = ['','','','',strtoupper($voucher_head), '','',''];
		  $datareport[] = ['','','','','','',''];
			$datareport[] = ['JI.No.','JI.Date', 'Customer','Item Name','Salesman','Vehicle No','Qty','Rate','Gross Amt.','VAT Amt.','Net Total'];
			$nettotal=$netdiscount=$netvat_amount=$net_amount_total=0;
				
			foreach ($reports as $key => $report) {
				$gtotal=0;
				
				
				foreach ($report as $row) {
					
				  $nettotal += $row->total;
				  $netdiscount += $row->discount;
				  $netvat_amount += $row->vat_amount;
				  $net_amount_total += $row->net_total;
					 $gtotal = $row->line_total -  $row->linediscount + $row->linevat;						  
					$datareport[] = [ 
						//'jono' => $row->document_id, 
									  'jino' => $row->voucher_no, 
									  'vchr_dt' => date('d-m-Y',strtotime($row->voucher_date)),
									  'customer' => $row->master_name,
									  'item' => $row->description,
									  'sales' => $row->salesman,
									  'regno' => $row->reg_no,
									  'qty' => number_format($row->quantity,2),
									  'unit' => number_format($row->unit_price,2),
									  'gross' => number_format($row->line_total,2),
									
									  'vat' => number_format($row->linevat,2),
									  'total' => number_format($gtotal,2)
									];
				}
				
			}
			//$datareport[] = ['','','','','','',''];
			//	$datareport[] = ['','','','Total:','','',number_format($nettotal,2),number_format($netdiscount,2),number_format($netvat_amount,2),number_format($net_amount_total,2)];
		
		} else if($request->get('search_type')=='itemwise') {	
		
			$reports = $this->makeTree($reports);
			$reports = $this->groupbyItemwise($reports);
			$voucher_head = 'Job Invoice by Itemwise';
			$datareport[] = ['','','','',strtoupper($voucher_head), '','',''];
		  $datareport[] = ['','','','','','',''];
			foreach($reports as $keyname => $report)
			{
				
				
				foreach($report as $items) {
					$datareport[] = ['Item Name:'.$items[0]->description,'','','','','','',''];
					$nettotal=$quantity=0;
					$datareport[] = ['JO.NO','JI.No.','JI.Date','Customer','Qty.','Price','Net Total'];
					foreach($items as $row) {
						$datareport[] = [ 'jono' => $row->document_id, 
										  'jino' => $row->voucher_no, 
										  'vchr_dt' => date('d-m-Y',strtotime($row->voucher_date)),
										  'customer' => $row->master_name,
										  'qty' => $row->quantity,
										  'price' => number_format($row->unit_price,2),
										  'total' => number_format($row->total,2)
									];
						$quantity += $row->total;
						$nettotal += $row->net_total;
					}
					$datareport[] = ['','','','','','',''];
					$datareport[] = ['','','','Total:','',$quantity,'',number_format($nettotal,2)];
				}
			}
		}
		
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
	public function getJobReportnew($attributes)   // 26may22 
	{
		$is_jobsplit = (isset($attributes['is_jobsplit']))?true:false;

		$is_workshopsplit = (isset($attributes['is_workshopsplit']))?true:false; 
        
		if($is_workshopsplit) 
		{
			switch($attributes['search_type']) 
			{
			//echo '<pre>';print_r($attributes);exit;	is_workshopsplit
			case 'summary':
				$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
				$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';
				$job_id = isset($attributes['job_id'])?$attributes['job_id']:''; 
				

				//purchase split

				$qry1 = DB::table('jobmaster')->where('jobmaster.status', 1)
							->join('purchase_split AS PS', function($join) {
									$join->on('PS.job_id','=','jobmaster.id');
								} )
							->leftJoin('account_master AS AM','AM.id', '=', 'jobmaster.customer_id' )
							->where('PS.status', 1)->where('PS.deleted_at', '0000-00-00 00:00:00');
				if($job_id)
					$qry1->where('jobmaster.id', $job_id);
					
					
			if(isset($attributes['job_id']) && $attributes['job_id']!='')
				$qry1->whereIn('jobmaster.id', $attributes['job_id']);	

				if($date_from!='' && $date_to!='')
					$qry1->whereBetween('PS.voucher_date', array($date_from, $date_to));
				
				$qry1->select('jobmaster.*','PS.net_amount AS amount','jobmaster.incexp AS income','AM.master_name AS customer');
				
				
				//sales split

				$qry2 = DB::table('jobmaster')->where('jobmaster.status', 1)
							->join('sales_split AS PS', function($join) {
									$join->on('PS.job_id','=','jobmaster.id');
								} )
							->leftJoin('account_master AS AM','AM.id', '=', 'jobmaster.customer_id' )
							->where('PS.status', 1)->where('PS.deleted_at', '0000-00-00 00:00:00');
				if($job_id)
					$qry2->where('jobmaster.id', $job_id);
				
				if($date_from!='' && $date_to!='')
					$qry2->whereBetween('PS.voucher_date', array($date_from, $date_to));
				
				$qry2->select('jobmaster.*',DB::raw('"0" AS amount'),'PS.net_amount AS income','AM.master_name AS customer');
				
			$qry16 = DB::table('jobmaster')->where('jobmaster.status', 1)
				->join('sales_invoice AS SI', function($join) {
						$join->on('SI.job_id','=','jobmaster.id');
					} )
				->join('sales_invoice_item AS SIM', function($join) {
						$join->on('SIM.sales_invoice_id','=','SI.id');
					} )
				->join('account_master AS AC', function($join) {
						$join->on('AC.id','=','SI.cr_account_id');
					} )
			
				->where('SI.status', 1)->where('SI.deleted_at', '0000-00-00 00:00:00');
	         if($job_id)
			 
			 {
				$qry16 = $qry16->where('jobmaster.id', $job_id);

					}
	        if($date_from!='' && $date_to!=''){
				$qry16 = $qry16->whereBetween('SI.voucher_date', array($date_from, $date_to));
			}
	
	        $qry16 = $qry16->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name', 'SI.net_total AS income',
				'jobmaster.incexp AS amount','AC.account_id','SI.less_description AS vehiclemodel',
				'SIM.quantity','SIM.unit_price','SI.vehicle_no AS vehicleno','SI.less_description2 AS vehiclemake','SI.less_description3 AS nextservice','SI.previnv_description AS servicedby','SI.kilometer AS kilometer');
				$results2 = $qry16->get();
		
		
				$results1 =$qry1->union($qry2)->get();
			
			return array_merge($results1,$results2);
           // echo '<pre>';print_r($results);exit;	

			case 'detail': //journal
				$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
				$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';
			   $job_id = isset($attributes['job_id'])?$attributes['job_id']:''; 
				
				$qry1 = DB::table('jobmaster')->where('jobmaster.status', 1)
							->join('purchase_split AS PS', function($join) {
									$join->on('PS.job_id','=','jobmaster.id');
								} )
							->join('purchase_split_item AS PIM', function($join) {
									$join->on('PIM.purchase_split_id','=','PS.id');
								} )
							->join('account_master AS AC', function($join) {
								$join->on('AC.id','=','PIM.account_id');
							} )
							->leftJoin('account_master AS AM','AM.id', '=', 'jobmaster.customer_id' )
							->where('PIM.status', 1)->where('PIM.deleted_at', '0000-00-00 00:00:00')
							->where('PS.status', 1)->where('PS.deleted_at', '0000-00-00 00:00:00');
				if($job_id)
					$qry1->where('jobmaster.id', $job_id);
				
				if($date_from!='' && $date_to!='')
					$qry1->whereBetween('PS.voucher_date', array($date_from, $date_to));
				
				$qry1->select('jobmaster.*','PIM.item_total AS amount', //DB::raw('SUM(PI.net_amount) AS amount'),
							'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','PS.voucher_no',DB::raw('"PS" AS type'),
							'PIM.quantity','PIM.unit_price','PIM.account_id AS item_code','PIM.item_description AS description','PS.voucher_date',
							'PS.description AS jdesc','AM.master_name AS customer');
							
				$qry2 = DB::table('jobmaster')->where('jobmaster.status', 1)
							->join('sales_split AS PS', function($join) {
									$join->on('PS.job_id','=','jobmaster.id');
								} )
							->join('sales_split_item AS PIM', function($join) {
									$join->on('PIM.sales_split_id','=','PS.id');
								} )
							->join('account_master AS AC', function($join) {
								$join->on('AC.id','=','PIM.account_id');
							} )
							->leftJoin('account_master AS AM','AM.id', '=', 'jobmaster.customer_id' )
							->where('PIM.status', 1)->where('PIM.deleted_at', '0000-00-00 00:00:00')
							->where('PS.status', 1)->where('PS.deleted_at', '0000-00-00 00:00:00');
				if($job_id)
					$qry2->where('jobmaster.id', $job_id);
				
				if($date_from!='' && $date_to!='')
					$qry2->whereBetween('PS.voucher_date', array($date_from, $date_to));
				
				$qry2->select('jobmaster.*',DB::raw('"0" AS amount'), //DB::raw('SUM(PI.net_amount) AS amount'),
							'PIM.item_total AS income','AC.account_id','AC.master_name','AC.id AS acid','PS.voucher_no',DB::raw('"SS" AS type'),
							'PIM.quantity','PIM.unit_price','PIM.account_id AS item_code','PIM.item_description AS description','PS.voucher_date',
							'PS.description AS jdesc','AM.master_name AS customer');
							
				
				$qry3 = DB::table('jobmaster')->where('jobmaster.status', 1)
							->join('payment_voucher_entry AS PVE', function($join) {
									$join->on('PVE.job_id','=','jobmaster.id');
								} )
							->join('payment_voucher AS PV', function($join) {
									$join->on('PV.id','=','PVE.payment_voucher_id');
								} )
							->join('account_master AS AC', function($join) {
								$join->on('AC.id','=','PVE.account_id');
								} )
							->leftJoin('account_master AS AM','AM.id', '=', 'jobmaster.customer_id' )
							->where('PVE.status', 1)
							->where('PVE.entry_type','Dr')
							->where('PVE.deleted_at', '0000-00-00 00:00:00');
				if($job_id)
					$qry3->where('jobmaster.id', $job_id);
				
				if($date_from!='' && $date_to!='')
					$qry3->whereBetween('PV.voucher_date', array($date_from, $date_to));
				
				$qry3->select('jobmaster.*',DB::raw('SUM(PVE.amount) AS amount'),//'PVE.amount AS amount',//'jobmaster.code','jobmaster.name', //,
							'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','PV.voucher_no',DB::raw('"PV" AS type'),
							DB::raw('"0" AS quantity'), DB::raw('"0" AS unit_price'),DB::raw('"" AS item_code'),DB::raw('"" AS description'),
							'PV.voucher_date','PVE.description AS jdesc','AM.master_name AS customer')
							->groupBy('PV.voucher_no');
							
				$qry4 = DB::table('jobmaster')->where('jobmaster.status', 1)
							->join('petty_cash_entry AS PVE', function($join) {
									$join->on('PVE.job_id','=','jobmaster.id');
								} )
							->join('petty_cash AS PV', function($join) {
									$join->on('PV.id','=','PVE.petty_cash_id');
								} )
							->join('account_master AS AC', function($join) {
								$join->on('AC.id','=','PVE.account_id');
								})
							->leftJoin('account_master AS AM','AM.id', '=', 'jobmaster.customer_id' )
							->where('PVE.status', 1)
							->where('PVE.entry_type','Dr')
							->where('PVE.deleted_at', '0000-00-00 00:00:00');
				if($job_id)
					$qry4->where('jobmaster.id', $job_id);
				
				if($date_from!='' && $date_to!='')
					$qry4->whereBetween('PV.voucher_date', array($date_from, $date_to));
				
				$qry4->select('jobmaster.*',DB::raw('SUM(PVE.amount) AS amount'),//'PVE.amount AS amount',//'jobmaster.code','jobmaster.name', //,
							'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','PV.voucher_no',DB::raw('"PC" AS type'),
							DB::raw('"0" AS quantity'), DB::raw('"0" AS unit_price'),DB::raw('"" AS item_code'),DB::raw('"" AS description'),
							'PV.voucher_date','PVE.description AS jdesc','AM.master_name AS customer')
							->groupBy('PV.voucher_no');
							
				$qry5 = DB::table('jobmaster')->where('jobmaster.status', 1)
							->join('journal_entry AS PVE', function($join) {
									$join->on('PVE.job_id','=','jobmaster.id');
								} )
							->join('journal AS PV', function($join) {
									$join->on('PV.id','=','PVE.journal_id');
								} )
							->join('account_master AS AC', function($join) {
								$join->on('AC.id','=','PVE.account_id');
								} )
							->join('account_category', 'account_category.id', '=', 'AC.account_category_id')
							->leftJoin('account_master AS AM','AM.id', '=', 'jobmaster.customer_id' )
							->where('PVE.status', 1)->where('PVE.entry_type','Dr')->where('PVE.deleted_at', '0000-00-00 00:00:00')
							->where('account_category.parent_id',4);
				if($job_id)
					$qry5->where('jobmaster.id', $job_id);
				
				if($date_from!='' && $date_to!='')
					$qry5->whereBetween('PV.voucher_date', array($date_from, $date_to));
				
				$qry5->select('jobmaster.*',DB::raw('SUM(PVE.amount) AS amount'),//'PVE.amount AS amount',//'jobmaster.code','jobmaster.name', 
							'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','PV.voucher_no',DB::raw('"JV" AS type'),
							DB::raw('"0" AS quantity'), DB::raw('"0" AS unit_price'),DB::raw('"" AS item_code'),DB::raw('"" AS description'),
							'PV.voucher_date','PVE.description AS jdesc','AM.master_name AS customer')
							->groupBy('PV.voucher_no');
							
							
							$qry16 = DB::table('jobmaster')->where('jobmaster.status', 1)
							->join('sales_invoice AS SI', function($join) {
									$join->on('SI.job_id','=','jobmaster.id');
								} )
							->join('sales_invoice_item AS SIM', function($join) {
									$join->on('SIM.sales_invoice_id','=','SI.id');
								} )
							->join('account_master AS AC', function($join) {
									$join->on('AC.id','=','SI.cr_account_id');
								} )
						
							->where('SI.status', 1)->where('SI.deleted_at', '0000-00-00 00:00:00');
							
						 if($job_id)
						 
						 {
							$qry16 = $qry16->where('jobmaster.id', $job_id);
			
								}
						if($date_from!='' && $date_to!=''){
							$qry16 = $qry16->whereBetween('SI.voucher_date', array($date_from, $date_to));
						}
				
						$qry16 = $qry16->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name', 
							'jobmaster.incexp AS amount','AC.account_id','SI.less_description AS vehiclemodel','AC.id AS acid',DB::raw('"" AS item_code'),
							'SIM.quantity','SIM.unit_price','SI.vehicle_no AS vehicleno','SI.less_description2 AS vehiclemake','SI.less_description3 AS nextservice','SI.previnv_description AS servicedby','SI.kilometer AS kilometer');
							$results2 = $qry16->get();
								
			$results1 = $qry1->union($qry2)->union($qry3)->union($qry4)->union($qry5)->orderBy('type','ASC')->get();	
			return array_merge($results1,$results2);	
	   

			}
		}
		
		
		 if($is_jobsplit) {
			
			switch($attributes['search_type']) 
			{
				case 'summary':
					$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
					$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';
					$job_id = isset($attributes['job_id'])?$attributes['job_id']:''; 
					

					//purchase split

					$qry1 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('purchase_split AS PS', function($join) {
										$join->on('PS.job_id','=','jobmaster.id');
									} )
								->leftJoin('account_master AS AM','AM.id', '=', 'jobmaster.customer_id' )
								->where('PS.status', 1)->where('PS.deleted_at', '0000-00-00 00:00:00');
				//	if($job_id)
						//$qry1->where('jobmaster.id', $job_id);
						
						
				if(isset($attributes['job_id']) && $attributes['job_id']!='')
	                $qry1->whereIn('jobmaster.id', $attributes['job_id']);	
 
	
	
					if($date_from!='' && $date_to!='')
						$qry1->whereBetween('PS.voucher_date', array($date_from, $date_to));
					
					$qry1->select('jobmaster.*','PS.net_amount AS amount','jobmaster.incexp AS income','AM.master_name AS customer');
					
					
					//sales split

					$qry2 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('sales_split AS PS', function($join) {
										$join->on('PS.job_id','=','jobmaster.id');
									} )
								->leftJoin('account_master AS AM','AM.id', '=', 'jobmaster.customer_id' )
								->where('PS.status', 1)->where('PS.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$qry2->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$qry2->whereBetween('PS.voucher_date', array($date_from, $date_to));
					
					$qry2->select('jobmaster.*',DB::raw('"0" AS amount'),'PS.net_amount AS income','AM.master_name AS customer');
					
				$results = $qry1->union($qry2)->get();
					
				case 'detail': //journal
					$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
					$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';
				   $job_id = isset($attributes['job_id'])?$attributes['job_id']:''; 
					
					$qry1 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('purchase_split AS PS', function($join) {
										$join->on('PS.job_id','=','jobmaster.id');
									} )
								->join('purchase_split_item AS PIM', function($join) {
										$join->on('PIM.purchase_split_id','=','PS.id');
									} )
								->join('account_master AS AC', function($join) {
									$join->on('AC.id','=','PIM.account_id');
								} )
								->leftJoin('account_master AS AM','AM.id', '=', 'jobmaster.customer_id' )
								->where('PIM.status', 1)->where('PIM.deleted_at', '0000-00-00 00:00:00')
								->where('PS.status', 1)->where('PS.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$qry1->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$qry1->whereBetween('PS.voucher_date', array($date_from, $date_to));
					
					$qry1->select('jobmaster.*','PIM.item_total AS amount', //DB::raw('SUM(PI.net_amount) AS amount'),
								'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','PS.voucher_no',DB::raw('"PS" AS type'),
								'PIM.quantity','PIM.unit_price','PIM.account_id AS item_code','PIM.item_description AS description','PS.voucher_date',
								'PS.description AS jdesc','AM.master_name AS customer');
								
					$qry2 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('sales_split AS PS', function($join) {
										$join->on('PS.job_id','=','jobmaster.id');
									} )
								->join('sales_split_item AS PIM', function($join) {
										$join->on('PIM.sales_split_id','=','PS.id');
									} )
								->join('account_master AS AC', function($join) {
									$join->on('AC.id','=','PIM.account_id');
								} )
								->leftJoin('account_master AS AM','AM.id', '=', 'jobmaster.customer_id' )
								->where('PIM.status', 1)->where('PIM.deleted_at', '0000-00-00 00:00:00')
								->where('PS.status', 1)->where('PS.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$qry2->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$qry2->whereBetween('PS.voucher_date', array($date_from, $date_to));
					
					$qry2->select('jobmaster.*',DB::raw('"0" AS amount'), //DB::raw('SUM(PI.net_amount) AS amount'),
								'PIM.item_total AS income','AC.account_id','AC.master_name','AC.id AS acid','PS.voucher_no',DB::raw('"SS" AS type'),
								'PIM.quantity','PIM.unit_price','PIM.account_id AS item_code','PIM.item_description AS description','PS.voucher_date',
								'PS.description AS jdesc','AM.master_name AS customer');
								
					
					$qry3 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('payment_voucher_entry AS PVE', function($join) {
										$join->on('PVE.job_id','=','jobmaster.id');
									} )
								->join('payment_voucher AS PV', function($join) {
										$join->on('PV.id','=','PVE.payment_voucher_id');
									} )
								->join('account_master AS AC', function($join) {
									$join->on('AC.id','=','PVE.account_id');
									} )
								->leftJoin('account_master AS AM','AM.id', '=', 'jobmaster.customer_id' )
								->where('PVE.status', 1)
								->where('PVE.entry_type','Dr')
								->where('PVE.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$qry3->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$qry3->whereBetween('PV.voucher_date', array($date_from, $date_to));
					
					$qry3->select('jobmaster.*',DB::raw('SUM(PVE.amount) AS amount'),//'PVE.amount AS amount',//'jobmaster.code','jobmaster.name', //,
								'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','PV.voucher_no',DB::raw('"PV" AS type'),
								DB::raw('"0" AS quantity'), DB::raw('"0" AS unit_price'),DB::raw('"" AS item_code'),DB::raw('"" AS description'),
								'PV.voucher_date','PVE.description AS jdesc','AM.master_name AS customer')
								->groupBy('PV.voucher_no');
								
					$qry4 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('petty_cash_entry AS PVE', function($join) {
										$join->on('PVE.job_id','=','jobmaster.id');
									} )
								->join('petty_cash AS PV', function($join) {
										$join->on('PV.id','=','PVE.petty_cash_id');
									} )
								->join('account_master AS AC', function($join) {
									$join->on('AC.id','=','PVE.account_id');
									})
								->leftJoin('account_master AS AM','AM.id', '=', 'jobmaster.customer_id' )
								->where('PVE.status', 1)
								->where('PVE.entry_type','Dr')
								->where('PVE.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$qry4->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$qry4->whereBetween('PV.voucher_date', array($date_from, $date_to));
					
					$qry4->select('jobmaster.*',DB::raw('SUM(PVE.amount) AS amount'),//'PVE.amount AS amount',//'jobmaster.code','jobmaster.name', //,
								'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','PV.voucher_no',DB::raw('"PC" AS type'),
								DB::raw('"0" AS quantity'), DB::raw('"0" AS unit_price'),DB::raw('"" AS item_code'),DB::raw('"" AS description'),
								'PV.voucher_date','PVE.description AS jdesc','AM.master_name AS customer')
								->groupBy('PV.voucher_no');
								
					$qry5 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('journal_entry AS PVE', function($join) {
										$join->on('PVE.job_id','=','jobmaster.id');
									} )
								->join('journal AS PV', function($join) {
										$join->on('PV.id','=','PVE.journal_id');
									} )
								->join('account_master AS AC', function($join) {
									$join->on('AC.id','=','PVE.account_id');
									} )
								->join('account_category', 'account_category.id', '=', 'AC.account_category_id')
								->leftJoin('account_master AS AM','AM.id', '=', 'jobmaster.customer_id' )
								->where('PVE.status', 1)->where('PVE.entry_type','Dr')->where('PVE.deleted_at', '0000-00-00 00:00:00')
								->where('account_category.parent_id',4);
					if($job_id)
						$qry5->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$qry5->whereBetween('PV.voucher_date', array($date_from, $date_to));
					
					$qry5->select('jobmaster.*',DB::raw('SUM(PVE.amount) AS amount'),//'PVE.amount AS amount',//'jobmaster.code','jobmaster.name', //,
								'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','PV.voucher_no',DB::raw('"JV" AS type'),
								DB::raw('"0" AS quantity'), DB::raw('"0" AS unit_price'),DB::raw('"" AS item_code'),DB::raw('"" AS description'),
								'PV.voucher_date','PVE.description AS jdesc','AM.master_name AS customer')
								->groupBy('PV.voucher_no');
								
				$results = $qry1->union($qry2)->union($qry3)->union($qry4)->union($qry5)->orderBy('type','ASC')->get();	
				
								//->groupBy('PS.voucher_no')
			}
			
		}
		 else
		{
		
			switch($attributes['search_type']) 
			{
				case 'summary':
					$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
					$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';
					$job_id = isset($attributes['job_id'])?$attributes['job_id']:''; 
					
					//GETTING ASSETS,LIABILITY,EQUITY CATEGORY ACCOUNTS FOR EXCLUDING RV,PV,JV,PC JOBREPORT...
					$exarr = DB::table('account_category')->whereIn('parent_id',[1,2,3])->select('id')->get();
					$excludearr = [];
					foreach($exarr as $arr) {
						$excludearr[] = $arr->id;
					}
					
					$query1 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('purchase_invoice AS PI', function($join) {
										$join->on('PI.job_id','=','jobmaster.id');
									} )
								->join('account_master AS AC', function($join) {
										$join->on('AC.id','=','PI.account_master_id');
									} )
								->whereNotIn('AC.account_category_id',$excludearr)
								->where('PI.status', 1)
								->where('PI.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$query1->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$query1->whereBetween('PI.voucher_date', array($date_from, $date_to));
					
					$query1->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name',DB::raw('SUM(PI.subtotal) AS amount'),'jobmaster.incexp AS income')
								->groupBy('jobmaster.id');
								
					$query2 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('goods_issued AS GI', function($join) {
										$join->on('GI.job_id','=','jobmaster.id');
									} )
								
								->where('GI.status', 1)
								->where('GI.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$query2->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$query2->whereBetween('GI.voucher_date', array($date_from, $date_to));
					
					$query2->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name',DB::raw('SUM(GI.total) AS amount'),'jobmaster.incexp AS income')
								->groupBy('jobmaster.id');
					
					$query3 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('journal_entry AS JE', function($join) {
										$join->on('JE.job_id','=','jobmaster.id');
									} )
								->where('JE.status', 1)->where('JE.entry_type','Dr')
								->where('JE.deleted_at', '0000-00-00 00:00:00')
								->whereNotIn('JE.account_id', function($qury) use($excludearr) {
									$qury->select('id')->from('account_master')->whereIn('account_category_id', $excludearr); 
								});
					if($job_id)
						$query3->where('jobmaster.id', $job_id);
					
					//if($date_from!='' && $date_to!='')
						//$query3->whereBetween('JE.voucher_date', array($date_from, $date_to));
					
					$query3->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name',DB::raw('SUM(JE.amount) AS amount'),'jobmaster.incexp AS income')
								->groupBy('jobmaster.id');
					
					$query4 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('payment_voucher_entry AS PVE', function($join) {
										$join->on('PVE.job_id','=','jobmaster.id');
									} )
								->where('PVE.status', 1)->where('PVE.entry_type','Dr')
								->where('PVE.deleted_at', '0000-00-00 00:00:00')
								->whereNotIn('PVE.account_id', function($qury) use($excludearr) {
									$qury->select('id')->from('account_master')->whereIn('account_category_id', $excludearr); 
								});
					if($job_id)
						$query4->where('jobmaster.id', $job_id);
					
					$query4->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name',DB::raw('SUM(PVE.amount) AS amount'),'jobmaster.incexp AS income')
								->groupBy('jobmaster.id');
					
					$query5 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('petty_cash_entry AS PCE', function($join) {
										$join->on('PCE.job_id','=','jobmaster.id');
									} )
								->where('PCE.status', 1)->where('PCE.entry_type','Dr')
								->where('PCE.deleted_at', '0000-00-00 00:00:00')
								->whereNotIn('PCE.account_id', function($qury) use($excludearr) {
									$qury->select('id')->from('account_master')->whereIn('account_category_id', $excludearr); 
								});
					if($job_id)
						$query5->where('jobmaster.id', $job_id);
					
					//if($date_from!='' && $date_to!='')
						//$query3->whereBetween('PCE.voucher_date', array($date_from, $date_to));
					
					$query5->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name',DB::raw('SUM(PCE.amount) AS amount'),'jobmaster.incexp AS income')
								->groupBy('jobmaster.id');
								
					$results1 = $query1->union($query2)->union($query3)->union($query4)->union($query5)->get();
					
					$qry1 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('sales_invoice AS SI', function($join) {
										$join->on('SI.job_id','=','jobmaster.id');
									} )
								->join('account_master AS AC', function($join) {
										$join->on('AC.id','=','SI.cr_account_id');
									} )
								->whereNotIn('AC.account_category_id',$excludearr)		
								->where('SI.status', 1)->where('SI.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$qry1->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$qry1->whereBetween('SI.voucher_date', array($date_from, $date_to));
								
					$qry1->select('jobmaster.id','jobmaster.code','jobmaster.name',DB::raw('SUM(SI.subtotal) AS income'),'jobmaster.incexp AS amount')
								->groupBy('jobmaster.id');
								
					$qry2 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('goods_return AS GR', function($join) {
										$join->on('GR.job_id','=','jobmaster.id');
									} )
								->join('account_master AS AC', function($join) {
										$join->on('AC.id','=','GR.account_master_id');
									} )
								->whereNotIn('AC.account_category_id',$excludearr)
								->where('GR.status', 1)->where('GR.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$qry2->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$qry2->whereBetween('GR.voucher_date', array($date_from, $date_to));
								
					$qry2->select('jobmaster.id','jobmaster.code','jobmaster.name',DB::raw('SUM(GR.total) AS income'),'jobmaster.incexp AS amount')
								->groupBy('jobmaster.id');
					
					$qry3 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('journal_entry AS JE', function($join) {
										$join->on('JE.job_id','=','jobmaster.id');
									} )
								->where('JE.status', 1)->where('JE.entry_type','Cr')->where('JE.deleted_at', '0000-00-00 00:00:00')
								->whereNotIn('JE.account_id', function($qury) use($excludearr) {
									$qury->select('id')->from('account_master')->whereIn('account_category_id', $excludearr); 
								});
					if($job_id)
						$qry3->where('jobmaster.id', $job_id);
					
					$qry3->select('jobmaster.id','jobmaster.code','jobmaster.name',DB::raw('SUM(JE.amount) AS amount'),'jobmaster.incexp AS income')
								->groupBy('jobmaster.id');
					
					$qry4 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('receipt_voucher_entry AS RVE', function($join) {
										$join->on('RVE.job_id','=','jobmaster.id');
									} )
								->where('RVE.status', 1)->where('RVE.entry_type','Cr')->where('RVE.deleted_at', '0000-00-00 00:00:00')
								->whereNotIn('RVE.account_id', function($qury) use($excludearr) {
									$qury->select('id')->from('account_master')->whereIn('account_category_id', $excludearr); 
								});
					if($job_id)
						$qry4->where('jobmaster.id', $job_id);
								
					$qry4->select('jobmaster.id','jobmaster.code','jobmaster.name',DB::raw('SUM(RVE.amount) AS amount'),'jobmaster.incexp AS income')
								->groupBy('jobmaster.id');
					
					$qry5 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('petty_cash_entry AS PCE', function($join) {
										$join->on('PCE.job_id','=','jobmaster.id');
									} )
								->where('PCE.status', 1)->where('PCE.entry_type','Cr')->where('PCE.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$qry5->where('jobmaster.id', $job_id);
					
					$qry5->select('jobmaster.id','jobmaster.code','jobmaster.name',DB::raw('SUM(PCE.amount) AS amount'),'jobmaster.incexp AS income')
								->groupBy('jobmaster.id');
								
					$results2 = $qry1->union($qry2)->union($qry3)->union($qry4)->union($qry5)->get();
					
					return array_merge($results1,$results2);
					
				break;
				
					case 'budgetin':
				
							
				$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
					$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';
					$job_id = isset($attributes['job_id'])?$attributes['job_id']:''; 
					
					$query1 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('purchase_invoice AS PI', function($join) {
										$join->on('PI.job_id','=','jobmaster.id');
									} )
								->join('account_master AS AC', function($join) {
										$join->on('AC.id','=','PI.account_master_id');
									} )->join('budgeting AS PIS', function($join) {
										$join->on('PIS.job_id','=','jobmaster.id');
									} )
										->join('project_budget AS PB', function($join) {
										$join->on('PB.budgeting_id','=','PIS.id');
									} )
									->leftJoin('account_master AS AB','AB.id', '=', 'PB.ac_id' )
									->leftJoin('project_budget AS ABB','ABB.ac_id', '=', 'PI.account_master_id' )
							//	->join('account_master AS AC', function($join) {
									//	$join->on('AC.id','=','PB.ac_id');
								//	} )
								->where('PI.status', 1)->where('PI.deleted_at', '0000-00-00 00:00:00');
				
					
				
					$query1->select('jobmaster.id','jobmaster.code AS jcode','jobmaster.name',DB::raw('SUM(PI.subtotal) AS amount'),'ABB.amount AS estimate','PI.subtotal AS amounttest',
										 'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','PI.voucher_no',DB::raw('"PI" AS type') )
								->groupBy('jobmaster.id');
					
					$query2 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('goods_issued AS GI', function($join) {
										$join->on('GI.job_id','=','jobmaster.id');
									} )
								->join('account_master AS AC', function($join) {
										$join->on('AC.id','=','GI.job_account_id');
									} )->join('budgeting AS PI', function($join) {
										$join->on('PI.job_id','=','jobmaster.id');
									} )
										->join('project_budget AS PB', function($join) {
										$join->on('PB.budgeting_id','=','PI.id');
									} )
									->leftJoin('account_master AS AB','AB.id', '=', 'PB.ac_id' )
									->leftJoin('project_budget AS ABB','ABB.ac_id', '=', 'ABB.id' )
								->where('GI.status', 1)->where('GI.deleted_at', '0000-00-00 00:00:00');
					
					$query2->select('jobmaster.id','jobmaster.code AS jcode','jobmaster.name',DB::raw('SUM(GI.net_amount) AS amount'),'ABB.amount AS estimate','GI.net_amount AS amounttest',
										 'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','GI.voucher_no',DB::raw('"GI" AS type'))
								->groupBy('jobmaster.id');
					
					$query3 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('journal_entry AS JE', function($join) {
										$join->on('JE.job_id','=','jobmaster.id');
									} )
								->join('journal AS J', function($join) {
										$join->on('J.id','=','JE.journal_id');
									} )
								->join('account_master AS AC', function($join) {
									$join->on('AC.id','=','JE.account_id');
									} )->join('budgeting AS PI', function($join) {
										$join->on('PI.job_id','=','jobmaster.id');
									} )
										->join('project_budget AS PB', function($join) {
										$join->on('PB.budgeting_id','=','PI.id');
									} )
									->leftJoin('account_master AS AB','AB.id', '=', 'PB.ac_id' )
									->leftJoin('project_budget AS ABB','ABB.ac_id', '=', 'JE.account_id' )
								->where('JE.status', 1)->where('JE.entry_type','Dr')->where('JE.deleted_at', '0000-00-00 00:00:00');
				
				
					$query3->select('jobmaster.id','jobmaster.code AS jcode','jobmaster.name',DB::raw('SUM(JE.amount) AS amount'),'ABB.amount AS estimate','JE.amount AS amounttest',
										 'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','J.voucher_no',DB::raw('"JVS" AS type'))
								->groupBy('jobmaster.id');
					
					$query4 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('payment_voucher_entry AS PVE', function($join) {
										$join->on('PVE.job_id','=','jobmaster.id');
									} )
								->join('payment_voucher AS PV', function($join) {
										$join->on('PV.id','=','PVE.payment_voucher_id');
									} )
								->join('account_master AS AC', function($join) {
									$join->on('AC.id','=','PVE.account_id');
									} )->join('budgeting AS PI', function($join) {
										$join->on('PI.job_id','=','jobmaster.id');
									} )
										->join('project_budget AS PB', function($join) {
										$join->on('PB.budgeting_id','=','PI.id');
									} )
									->leftJoin('account_master AS AB','AB.id', '=', 'PB.ac_id' )
									->leftJoin('project_budget AS ABB','ABB.ac_id', '=', 'PVE.account_id' )
								->where('PVE.status', 1)->where('PVE.entry_type','Dr')->where('PVE.deleted_at', '0000-00-00 00:00:00');
					
					
					
					$query4->select('jobmaster.id','jobmaster.code AS jcode','jobmaster.name',DB::raw('SUM(PVE.amount) AS amount'),'ABB.amount AS estimate','PVE.amount AS amounttest',
										 'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','PV.voucher_no',DB::raw('"SP" AS type'))
								->groupBy('jobmaster.id');
								
					$results1 = $query1->union($query2)->union($query3)->union($query4)->get();
					
					$qry1 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('sales_invoice AS SI', function($join) {
										$join->on('SI.job_id','=','jobmaster.id');
									} )
								->join('account_master AS AC', function($join) {
										$join->on('AC.id','=','SI.cr_account_id');
									} )->join('budgeting AS PIS', function($join) {
										$join->on('PIS.job_id','=','jobmaster.id');
									} )
										->join('project_budget AS PB', function($join) {
										$join->on('PB.budgeting_id','=','PB.id');
									} )
									->leftJoin('account_master AS AB','AB.id', '=', 'PB.ac_id' )
									->leftJoin('project_budget AS ABB','ABB.ac_id', '=', 'SI.cr_account_id' )
								->where('SI.status', 1)->where('SI.deleted_at', '0000-00-00 00:00:00');
					
					
					$qry1->select('jobmaster.id','jobmaster.code AS jcode','jobmaster.name',DB::raw('SUM(SI.subtotal) AS income'),'ABB.amount AS estimate','SI.subtotal AS amounttest',
										 'jobmaster.incexp AS amount','AC.account_id','AC.master_name','AC.id AS acid','SI.voucher_no',DB::raw('"SI" AS type'))
								->groupBy('jobmaster.id');
								
					$qry2 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('goods_return AS GR', function($join) {
										$join->on('GR.job_id','=','jobmaster.id');
									} )
								->join('account_master AS AC', function($join) {
										$join->on('AC.id','=','GR.account_master_id');
									} )->join('budgeting AS PIS', function($join) {
										$join->on('PIS.job_id','=','jobmaster.id');
									} )
										->join('project_budget AS PB', function($join) {
										$join->on('PB.budgeting_id','=','PIS.id');
									} )
									->leftJoin('account_master AS AB','AB.id', '=', 'PB.ac_id' )
									->leftJoin('project_budget AS ABB','ABB.ac_id', '=', 'GR.account_master_id' )
								->where('GR.status', 1)->where('GR.deleted_at', '0000-00-00 00:00:00');
				
					$qry2->select('jobmaster.id','jobmaster.code AS jcode','jobmaster.name',DB::raw('SUM(GR.net_amount) AS income'),'ABB.amount AS estimate','GR.net_amount AS amounttest',
										 'jobmaster.incexp AS amount','AC.account_id','AC.master_name','AC.id AS acid','GR.voucher_no',DB::raw('"GR" AS type'))
								->groupBy('jobmaster.id');
					
					$qry3 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('journal_entry AS JE', function($join) {
										$join->on('JE.job_id','=','jobmaster.id');
									} )
								->join('journal AS J', function($join) {
										$join->on('J.id','=','JE.journal_id');
									} )
								->join('account_master AS AC', function($join) {
										$join->on('AC.id','=','JE.account_id');
									} )->join('budgeting AS PIS', function($join) {
										$join->on('PIS.job_id','=','jobmaster.id');
									} )
										->join('project_budget AS PB', function($join) {
										$join->on('PB.budgeting_id','=','PIS.id');
									} )
									->leftJoin('account_master AS AB','AB.id', '=', 'PB.ac_id' )
									->leftJoin('project_budget AS ABB','ABB.ac_id', '=', 'JE.account_id' )
								->where('JE.status', 1)->where('JE.entry_type','Cr')->where('JE.deleted_at', '0000-00-00 00:00:00');
				
					$qry3->select('jobmaster.id','jobmaster.code AS jcode','jobmaster.name',DB::raw('SUM(JE.amount) AS amount'),'ABB.amount AS estimate','JE.amount AS amounttest',
										 'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','J.voucher_no',DB::raw('"JVCredit" AS type'))
								->groupBy('jobmaster.id');
						$query5 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('petty_cash_entry AS PVE', function($join) {
										$join->on('PVE.job_id','=','jobmaster.id');
									} )
								->join('petty_cash AS PV', function($join) {
										$join->on('PV.id','=','PVE.petty_cash_id');
									} )
								->join('account_master AS AC', function($join) {
									$join->on('AC.id','=','PVE.account_id');
									} )
										->join('budgeting AS PIS', function($join) {
										$join->on('PIS.job_id','=','jobmaster.id');
									} )
										->join('project_budget AS PB', function($join) {
										$join->on('PB.budgeting_id','=','PIS.id');
									} )
									->leftJoin('account_master AS AB','AB.id', '=', 'PB.ac_id' )
									->leftJoin('project_budget AS ABB','ABB.ac_id', '=', 'PVE.account_id' )
								->where('PVE.status', 1)->where('PVE.entry_type','Dr')
								->where('PVE.deleted_at', '0000-00-00 00:00:00');
					
						$query5->select('jobmaster.id','jobmaster.code AS jcode','jobmaster.name',DB::raw('SUM(PVE.amount) AS amount'),'ABB.amount AS estimate','PVE.amount AS amounttest',
										 'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','PV.voucher_no',DB::raw('"PC" AS type'))		->groupBy('jobmaster.id');
								
								
								
								
					$qry4 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('receipt_voucher_entry AS RVE', function($join) {
										$join->on('RVE.job_id','=','jobmaster.id');
									} )
								->join('receipt_voucher AS RV', function($join) {
										$join->on('RV.id','=','RVE.receipt_voucher_id');
									} )
								->join('account_master AS AC', function($join) {
										$join->on('AC.id','=','RVE.account_id');
									} )
									->join('budgeting AS PIS', function($join) {
										$join->on('PIS.job_id','=','jobmaster.id');
									} )
										->join('project_budget AS PB', function($join) {
										$join->on('PB.budgeting_id','=','PIS.id');
									} )
									->leftJoin('account_master AS AB','AB.id', '=', 'PB.ac_id' )
									->leftJoin('project_budget AS ABB','ABB.ac_id', '=', 'RVE.account_id' )
								->where('RVE.status', 1)->where('RVE.entry_type','Cr')->where('RVE.deleted_at', '0000-00-00 00:00:00');
				
				
					$qry4->select('jobmaster.id','jobmaster.code AS jcode','jobmaster.name',DB::raw('SUM(RVE.amount) AS amount'),'ABB.amount AS estimate','RVE.amount AS amounttest',
										 'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','RV.voucher_no',DB::raw('"CR" AS type'))
								->groupBy('jobmaster.id');
									
				
								
								
					$results2 = $qry1->union($qry2)->union($qry3)->union($qry4)->union($query5)->get();
					
					return array_merge($results1,$results2);
			
								
				
					
				//	return array_merge($results1);
					
				break;
				
				
				
				
				case 'summary_ac':
					$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
					$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';
					$job_id = isset($attributes['job_id'])?$attributes['job_id']:''; 
					
					$query1 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('purchase_invoice AS PI', function($join) {
										$join->on('PI.job_id','=','jobmaster.id');
									} )
								->join('account_master AS AC', function($join) {
										$join->on('AC.id','=','PI.account_master_id');
									} )
								->where('PI.status', 1)->where('PI.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$query1->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$query1->whereBetween('PI.voucher_date', array($date_from, $date_to));
					
					$query1->select('jobmaster.id','jobmaster.code','jobmaster.name',DB::raw('SUM(PI.subtotal) AS amount'),
										 'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','PI.voucher_no',DB::raw('"PI" AS type') )
								->groupBy('jobmaster.id');
					
					$query2 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('goods_issued AS GI', function($join) {
										$join->on('GI.job_id','=','jobmaster.id');
									} )
								->join('account_master AS AC', function($join) {
										$join->on('AC.id','=','GI.job_account_id');
									} )
								->where('GI.status', 1)->where('GI.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$query2->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$query2->whereBetween('GI.voucher_date', array($date_from, $date_to));
					
					$query2->select('jobmaster.id','jobmaster.code','jobmaster.name',DB::raw('SUM(GI.net_amount) AS amount'),
										 'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','GI.voucher_no',DB::raw('"GI" AS type'))
								->groupBy('jobmaster.id');
					
					$query3 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('journal_entry AS JE', function($join) {
										$join->on('JE.job_id','=','jobmaster.id');
									} )
								->join('journal AS J', function($join) {
										$join->on('J.id','=','JE.journal_id');
									} )
								->join('account_master AS AC', function($join) {
									$join->on('AC.id','=','JE.account_id');
									} )
								->where('JE.status', 1)->where('JE.entry_type','Dr')->where('JE.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$query3->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$query3->whereBetween('J.voucher_date', array($date_from, $date_to));
					
					$query3->select('jobmaster.id','jobmaster.code','jobmaster.name',DB::raw('SUM(JE.amount) AS amount'),
										 'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','J.voucher_no',DB::raw('"JV" AS type'))
								->groupBy('jobmaster.id');
					
					$query4 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('payment_voucher_entry AS PVE', function($join) {
										$join->on('PVE.job_id','=','jobmaster.id');
									} )
								->join('payment_voucher AS PV', function($join) {
										$join->on('PV.id','=','PVE.payment_voucher_id');
									} )
								->join('account_master AS AC', function($join) {
									$join->on('AC.id','=','PVE.account_id');
									} )
								->where('PVE.status', 1)->where('PVE.entry_type','Dr')->where('PVE.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$query4->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$query4->whereBetween('PV.voucher_date', array($date_from, $date_to));
					
					$query4->select('jobmaster.id','jobmaster.code','jobmaster.name',DB::raw('SUM(PVE.amount) AS amount'),
										 'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','PV.voucher_no',DB::raw('"SP" AS type'))
								->groupBy('jobmaster.id');
								
					$results1 = $query1->union($query2)->union($query3)->union($query4)->get();
					
					$qry1 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('sales_invoice AS SI', function($join) {
										$join->on('SI.job_id','=','jobmaster.id');
									} )
								->join('account_master AS AC', function($join) {
										$join->on('AC.id','=','SI.cr_account_id');
									} )
								->where('SI.status', 1)->where('SI.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$qry1->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$qry1->whereBetween('SI.voucher_date', array($date_from, $date_to));
					
					$qry1->select('jobmaster.id','jobmaster.code','jobmaster.name',DB::raw('SUM(SI.subtotal) AS income'),
										 'jobmaster.incexp AS amount','AC.account_id','AC.master_name','AC.id AS acid','SI.voucher_no',DB::raw('"SI" AS type'))
								->groupBy('jobmaster.id');
								
					$qry2 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('goods_return AS GR', function($join) {
										$join->on('GR.job_id','=','jobmaster.id');
									} )
								->join('account_master AS AC', function($join) {
										$join->on('AC.id','=','GR.account_master_id');
									} )
								->where('GR.status', 1)->where('GR.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$qry2->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$qry2->whereBetween('GR.voucher_date', array($date_from, $date_to));
					
					$qry2->select('jobmaster.id','jobmaster.code','jobmaster.name',DB::raw('SUM(GR.net_amount) AS income'),
										 'jobmaster.incexp AS amount','AC.account_id','AC.master_name','AC.id AS acid','GR.voucher_no',DB::raw('"GR" AS type'))
								->groupBy('jobmaster.id');
					
					$qry3 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('journal_entry AS JE', function($join) {
										$join->on('JE.job_id','=','jobmaster.id');
									} )
								->join('journal AS J', function($join) {
										$join->on('J.id','=','JE.journal_id');
									} )
								->join('account_master AS AC', function($join) {
										$join->on('AC.id','=','JE.account_id');
									} )
								->where('JE.status', 1)->where('JE.entry_type','Cr')->where('JE.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$qry3->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$qry3->whereBetween('J.voucher_date', array($date_from, $date_to));
					
					$qry3->select('jobmaster.id','jobmaster.code','jobmaster.name',DB::raw('SUM(JE.amount) AS amount'),
										 'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','J.voucher_no',DB::raw('"JV" AS type'))
								->groupBy('jobmaster.id');
					
					$qry4 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('receipt_voucher_entry AS RVE', function($join) {
										$join->on('RVE.job_id','=','jobmaster.id');
									} )
								->join('receipt_voucher AS RV', function($join) {
										$join->on('RV.id','=','RVE.receipt_voucher_id');
									} )
								->join('account_master AS AC', function($join) {
										$join->on('AC.id','=','RVE.account_id');
									} )
								->where('RVE.status', 1)->where('RVE.entry_type','Cr')->where('RVE.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$qry4->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$qry4->whereBetween('RV.voucher_date', array($date_from, $date_to));
					
					$qry4->select('jobmaster.id','jobmaster.code','jobmaster.name',DB::raw('SUM(RVE.amount) AS amount'),
										 'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','RV.voucher_no',DB::raw('"CR" AS type'))
								->groupBy('jobmaster.id');
								
					$results2 = $qry1->union($qry2)->union($qry3)->union($qry4)->get();
					
					return array_merge($results1,$results2);
					
				break;
				
				case 'detail':
					$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
					$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';
					$job_id = isset($attributes['job_id'])?$attributes['job_id']:''; 
					
					//GETTING ASSETS,LIABILITY,EQUITY CATEGORY ACCOUNTS FOR EXCLUDING RV,PV,JV,PC JOBREPORT...
					$exarr = DB::table('account_category')->whereIn('parent_id',[1,2,3])->select('id')->get();
					$excludearr = [];
					foreach($exarr as $arr) {
						$excludearr[] = $arr->id;
					}
					
					$query1 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('purchase_invoice AS PI', function($join) {
										$join->on('PI.job_id','=','jobmaster.id');
									} )
								->join('purchase_invoice_item AS PIM', function($join) {
										$join->on('PIM.purchase_invoice_id','=','PI.id');
									} )
								->join('account_master AS AC', function($join) {
										$join->on('AC.id','=','PI.account_master_id');
									} )
								->join('itemmaster AS IM', function($join) {
									$join->on('IM.id','=','PIM.item_id');
								} )
								->whereNotIn('AC.account_category_id',$excludearr)
								->where('PI.status', 1)->where('PI.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$query1->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$query1->whereBetween('PI.voucher_date', array($date_from, $date_to));
					
					$query1->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name','PI.subtotal AS amount', //DB::raw('SUM(PI.net_amount) AS amount'),
								'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','PI.voucher_no',DB::raw('"PI" AS type'),
								'PIM.quantity','PIM.unit_price','IM.item_code','IM.description','PI.voucher_date','PI.description AS jdesc');
							//	->groupBy('PI.id');
					
					$query2 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('goods_issued AS GI', function($join) {
										$join->on('GI.job_id','=','jobmaster.id');
									} )
								->join('goods_issued_item AS GIM', function($join) {
										$join->on('GIM.goods_issued_id','=','GI.id');
									} )
								->join('account_master AS AC', function($join) {
										$join->on('AC.id','=','GI.job_account_id');
									} )
								->join('itemmaster AS IM', function($join) {
									$join->on('IM.id','=','GIM.item_id');
								} )
								->whereNotIn('AC.account_category_id',$excludearr)
								->where('GI.status', 1)->where('GI.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$query2->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$query2->whereBetween('GI.voucher_date', array($date_from, $date_to));
					
					$query2->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name','GI.net_amount AS amount', //DB::raw('SUM(GI.net_amount) AS amount'),
								'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','GI.voucher_no',DB::raw('"GI" AS type'),
								'GIM.quantity','GIM.unit_price','IM.item_code','IM.description','GI.voucher_date','GI.description AS jdesc');
							//	->groupBy('GI.id');
					
					$query3 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('journal_entry AS JE', function($join) {
										$join->on('JE.job_id','=','jobmaster.id');
									} )
								->join('journal AS J', function($join) {
										$join->on('J.id','=','JE.journal_id');
									} )
								->join('account_master AS AC', function($join) {
									$join->on('AC.id','=','JE.account_id');
									} )
								->where('JE.status', 1)->where('J.voucher_type','JV')->where('JE.entry_type','Dr')
								->whereNotIn('AC.account_category_id',$excludearr)->where('JE.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$query3->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$query3->whereBetween('J.voucher_date', array($date_from, $date_to));
					
					$query3->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name','JE.amount AS amount', //DB::raw('SUM(JE.amount) AS amount'),
								'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','J.voucher_no',DB::raw('"JV" AS type'),
								DB::raw('"0" AS quantity'), DB::raw('"0" AS unit_price'),DB::raw('"" AS item_code'),DB::raw('"" AS description'),
								'J.voucher_date','JE.description AS jdesc');
								//->groupBy('J.id');
			
					$query4 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('payment_voucher_entry AS PVE', function($join) {
										$join->on('PVE.job_id','=','jobmaster.id');
									} )
								->join('payment_voucher AS PV', function($join) {
										$join->on('PV.id','=','PVE.payment_voucher_id');
									} )
								->join('account_master AS AC', function($join) {
									$join->on('AC.id','=','PVE.account_id');
									} )
								->where('PVE.status', 1)->where('PVE.entry_type','Dr')
								->whereNotIn('AC.account_category_id',$excludearr)->where('PVE.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$query4->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$query4->whereBetween('PV.voucher_date', array($date_from, $date_to));
					
					$query4->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name','PVE.amount AS amount', //DB::raw('SUM(PVE.amount) AS amount'),
								'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','PV.voucher_no',DB::raw('"SP" AS type'),
								DB::raw('"0" AS quantity'), DB::raw('"0" AS unit_price'),DB::raw('"" AS item_code'),DB::raw('"" AS description'),
								'PV.voucher_date','PVE.description AS jdesc');
							//->groupBy('PV.id');
			
			$query5 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('petty_cash_entry AS PVE', function($join) {
										$join->on('PVE.job_id','=','jobmaster.id');
									} )
								->join('petty_cash AS PV', function($join) {
										$join->on('PV.id','=','PVE.petty_cash_id');
									} )
								->join('account_master AS AC', function($join) {
									$join->on('AC.id','=','PVE.account_id');
									} )
								->where('PVE.status', 1)->where('PVE.entry_type','Dr')
								->whereNotIn('AC.account_category_id',$excludearr)->where('PVE.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$query5->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$query5->whereBetween('PV.voucher_date', array($date_from, $date_to));	
						
					$query5->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name','PVE.amount AS amount', //DB::raw('SUM(PVE.amount) AS amount'),
								'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','PV.voucher_no',DB::raw('"PC" AS type'),
								DB::raw('"0" AS quantity'), DB::raw('"0" AS unit_price'),DB::raw('"" AS item_code'),DB::raw('"" AS description'),
								'PV.voucher_date','PVE.description AS jdesc');
								//->groupBy('PV.id');
					//	$results1 = $query5	->get();
					
					$query6 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('journal_entry AS JE', function($join) {
										$join->on('JE.job_id','=','jobmaster.id');
									} )
								->join('journal AS J', function($join) {
										$join->on('J.id','=','JE.journal_id');
									} )
								->join('account_master AS AC', function($join) {
									$join->on('AC.id','=','JE.account_id');
									} )
								->where('JE.status', 1)->where('J.voucher_type','PIN')->where('JE.entry_type','Dr')
								->whereNotIn('AC.account_category_id',$excludearr)->where('JE.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$query6->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$query6->whereBetween('J.voucher_date', array($date_from, $date_to));
					
					$query6->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name','JE.amount AS amount', //DB::raw('SUM(JE.amount) AS amount'),
								'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','J.voucher_no',DB::raw('"PIN" AS type'),
								DB::raw('"0" AS quantity'), DB::raw('"0" AS unit_price'),DB::raw('"" AS item_code'),DB::raw('"" AS description'),
								'J.voucher_date','JE.description AS jdesc');
								//->groupBy('J.id');
								
								
								
				$query7 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('journal_entry AS JE', function($join) {
										$join->on('JE.job_id','=','jobmaster.id');
									} )
								->join('journal AS J', function($join) {
										$join->on('J.id','=','JE.journal_id');
									} )
								->join('account_master AS AC', function($join) {
									$join->on('AC.id','=','JE.account_id');
									} )
								->where('JE.status', 1)->where('J.voucher_type','SIN')->where('JE.entry_type','Dr')
								->whereNotIn('AC.account_category_id',$excludearr)->where('JE.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$query7->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$query7->whereBetween('J.voucher_date', array($date_from, $date_to));
					
					$query7->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name','JE.amount AS amount', //DB::raw('SUM(JE.amount) AS amount'),
								'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','J.voucher_no',DB::raw('"SIN" AS type'),
								DB::raw('"0" AS quantity'), DB::raw('"0" AS unit_price'),DB::raw('"" AS item_code'),DB::raw('"" AS description'),
								'J.voucher_date','JE.description AS jdesc');
					//	->groupBy('J.id');
					
					
					
					
					
					$results1 = $query1->union($query2)->union($query3)->union($query4)->union($query5)->union($query6)->union($query7)->get();
				//	echo '<pre>';print_r($results1);exit;
				
					//SALES INVO;
				
				$qry1 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('sales_invoice AS SI', function($join) {
										$join->on('SI.job_id','=','jobmaster.id');
									} )
								->join('sales_invoice_item AS SIM', function($join) {
										$join->on('SIM.sales_invoice_id','=','SI.id');
									} )
								->join('account_master AS AC', function($join) {
										$join->on('AC.id','=','SI.cr_account_id');
									} )
								->join('itemmaster AS IM', function($join) {
									$join->on('IM.id','=','SIM.item_id');
								} )
								->where('SI.status', 1)->where('SI.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$qry1->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$qry1->whereBetween('SI.voucher_date', array($date_from, $date_to));
					
					$qry1->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name','SI.subtotal AS income', //DB::raw('SUM(SI.net_total) AS income'),
								'jobmaster.incexp AS amount','AC.account_id','AC.master_name','AC.id AS acid','SI.voucher_no',DB::raw('"SI" AS type'),
								'SIM.quantity','SIM.unit_price','IM.item_code','IM.description','SI.voucher_date','SI.description AS jdesc')
								->groupBy('SI.id');
					
					
						//Goods Return;			
					$qry2 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('goods_return AS GR', function($join) {
										$join->on('GR.job_id','=','jobmaster.id');
									} )
								->join('goods_return_item AS GRM', function($join) {
										$join->on('GRM.goods_return_id','=','GR.id');
									} )
								->join('account_master AS AC', function($join) {
										$join->on('AC.id','=','GR.account_master_id');
									} )
								->join('itemmaster AS IM', function($join) {
									$join->on('IM.id','=','GRM.item_id');
								} )
								->where('GR.status', 1)->where('GR.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$qry2->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$qry2->whereBetween('GR.voucher_date', array($date_from, $date_to));
					
					$qry2->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name','GR.net_amount AS income',//DB::raw('SUM(GR.net_amount) AS income'),
								'jobmaster.incexp AS amount','AC.account_id','AC.master_name','AC.id AS acid','GR.voucher_no',DB::raw('"GR" AS type'),
								'GRM.quantity','GRM.unit_price','IM.item_code','IM.description','GR.voucher_date','GR.description AS jdesc')
								->groupBy('GR.id');
					
						//Journal;		
					$qry3 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('journal_entry AS JE', function($join) {
										$join->on('JE.job_id','=','jobmaster.id');
									} )
								->join('journal AS J', function($join) {
										$join->on('J.id','=','JE.journal_id');
									} )
								->join('account_master AS AC', function($join) {
										$join->on('AC.id','=','JE.account_id');
									} )
								->where('JE.status', 1)->where('J.voucher_type','JV')->where('JE.entry_type','Cr')
								->whereNotIn('AC.account_category_id',$excludearr)->where('JE.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$qry3->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$qry3->whereBetween('J.voucher_date', array($date_from, $date_to));
					
					$qry3->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name','JE.amount AS amount',//DB::raw('SUM(JE.amount) AS amount'),
								'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','J.voucher_no',DB::raw('"JV" AS type'),
								DB::raw('"0" AS quantity'), DB::raw('"0" AS unit_price'),DB::raw('"" AS item_code'),DB::raw('"" AS description'),
								'J.voucher_date','JE.description AS jdesc')
								->groupBy('J.id');
								
					
					//Receipt Voucher;	
					$qry4 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('receipt_voucher_entry AS RVE', function($join) {
										$join->on('RVE.job_id','=','jobmaster.id');
									} )
								->join('receipt_voucher AS RV', function($join) {
										$join->on('RV.id','=','RVE.receipt_voucher_id');
									} )
								->join('account_master AS AC', function($join) {
										$join->on('AC.id','=','RVE.account_id');
									} )
								->where('RVE.status', 1)->where('RVE.entry_type','Cr')
								->whereNotIn('AC.account_category_id',$excludearr)->where('RVE.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$qry4->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$qry4->whereBetween('RV.voucher_date', array($date_from, $date_to));
					
					$qry4->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name','RVE.amount AS amount',//DB::raw('SUM(RVE.amount) AS amount'),
								'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','RV.voucher_no',DB::raw('"CR" AS type'),
								DB::raw('"0" AS quantity'), DB::raw('"0" AS unit_price'),DB::raw('"" AS item_code'),DB::raw('"" AS description'),
								'RV.voucher_date','RVE.description AS jdesc')
								->groupBy('RV.id');
								
					$qry5 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('petty_cash_entry AS RVE', function($join) {
										$join->on('RVE.job_id','=','jobmaster.id');
									} )
								->join('petty_cash AS RV', function($join) {
										$join->on('RV.id','=','RVE.petty_cash_id');
									} )
								->join('account_master AS AC', function($join) {
										$join->on('AC.id','=','RVE.account_id');
									} )
								->where('RVE.status', 1)->where('RVE.entry_type','Cr')
								->whereNotIn('AC.account_category_id',$excludearr)->where('RVE.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$qry5->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$qry5->whereBetween('RV.voucher_date', array($date_from, $date_to));
					
					$qry5->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name','RVE.amount AS amount',//DB::raw('SUM(RVE.amount) AS amount'),
								'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','RV.voucher_no',DB::raw('"PC" AS type'),
								DB::raw('"0" AS quantity'), DB::raw('"0" AS unit_price'),DB::raw('"" AS item_code'),DB::raw('"" AS description'),
								'RV.voucher_date','RVE.description AS jdesc')
								->groupBy('RV.id');
						
				
				
				
				
				$qry6 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('journal_entry AS JE', function($join) {
										$join->on('JE.job_id','=','jobmaster.id');
									} )
								->join('journal AS J', function($join) {
										$join->on('J.id','=','JE.journal_id');
									} )
								->join('account_master AS AC', function($join) {
										$join->on('AC.id','=','JE.account_id');
									} )
								->where('JE.status', 1)->where('J.voucher_type','PIN')->where('JE.entry_type','Cr')
								->whereNotIn('AC.account_category_id',$excludearr)->where('JE.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$qry6->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$qry6->whereBetween('J.voucher_date', array($date_from, $date_to));
					
					$qry6->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name','JE.amount AS amount',//DB::raw('SUM(JE.amount) AS amount'),
								'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','J.voucher_no',DB::raw('"PIN" AS type'),
								DB::raw('"0" AS quantity'), DB::raw('"0" AS unit_price'),DB::raw('"" AS item_code'),DB::raw('"" AS description'),
								'J.voucher_date','JE.description AS jdesc');
							//	->groupBy('J.id');
								
								
					$qry7 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('journal_entry AS JE', function($join) {
										$join->on('JE.job_id','=','jobmaster.id');
									} )
								->join('journal AS J', function($join) {
										$join->on('J.id','=','JE.journal_id');
									} )
								->join('account_master AS AC', function($join) {
										$join->on('AC.id','=','JE.account_id');
									} )
								->where('JE.status', 1)->where('J.voucher_type','SIN')->where('JE.entry_type','Cr')
								->whereNotIn('AC.account_category_id',$excludearr)->where('JE.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$qry7->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$qry7->whereBetween('J.voucher_date', array($date_from, $date_to));
					
					$qry7->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name','JE.amount AS amount',//DB::raw('SUM(JE.amount) AS amount'),
								'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','J.voucher_no',DB::raw('"SIN" AS type'),
								DB::raw('"0" AS quantity'), DB::raw('"0" AS unit_price'),DB::raw('"" AS item_code'),DB::raw('"" AS description'),
								'J.voucher_date','JE.description AS jdesc');
								//->groupBy('J.id');			
								
								
					$results2 = $qry1->union($qry2)->union($qry3)->union($qry4)->union($qry5)->union($qry6)->union($qry7)->get();
					
					return array_merge($results1,$results2);
					
				break;
					
				case 'stockin':
					$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
					$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';
					$job_id = isset($attributes['job_id'])?$attributes['job_id']:''; 
					
					//GETTING ASSETS,LIABILITY,EQUITY CATEGORY ACCOUNTS FOR EXCLUDING RV,PV,JV,PC JOBREPORT...
					$exarr = DB::table('account_category')->whereIn('parent_id',[1,2,3])->select('id')->get();
					$excludearr = [];
					foreach($exarr as $arr) {
						$excludearr[] = $arr->id;
					}
					
					$query = DB::table('jobmaster')->where('jobmaster.status', 1)
												->join('purchase_invoice AS PI', function($join) {
														$join->on('PI.job_id','=','jobmaster.id');
													} )
												->join('account_master AS AC', function($join) {
														$join->on('AC.id','=','PI.supplier_id');
													} )
												->join('purchase_invoice_item AS PIM', function($join) {
														$join->on('PIM.purchase_invoice_id','=','PI.id');
													} )
												->join('itemmaster AS IM', function($join) {
														$join->on('IM.id','=','PIM.item_id');
													} )
												->whereNotIn('AC.account_category_id',$excludearr)
												->where('PI.status', 1)
												->where('PI.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$query->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$query->whereBetween('PI.voucher_date', array($date_from, $date_to));
					
					$query->select('jobmaster.id','jobmaster.code','jobmaster.name','PI.id AS piid','IM.id AS item_id',DB::raw('"PI" AS type'),
														 'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','PI.reference_no',
														 'IM.item_code','IM.description','PIM.quantity','PIM.unit_price','PI.voucher_date','PI.voucher_no');
					
					$query2 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('goods_return AS GR', function($join) {
										$join->on('GR.job_id','=','jobmaster.id');
									} )
								->join('account_master AS AC', function($join) {
										$join->on('AC.id','=','GR.job_account_id');
									} )
								->join('goods_return_item AS GRM', function($join) {
										$join->on('GRM.goods_return_id','=','GR.id');
									} )
								->join('itemmaster AS IM', function($join) {
										$join->on('IM.id','=','GRM.item_id');
									} )
								->where('GR.status', 1)
								->where('GR.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$query2->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$query2->whereBetween('GR.voucher_date', array($date_from, $date_to));
					
					$query2->select('jobmaster.id','jobmaster.code','jobmaster.name','GR.id AS siid','IM.id AS item_id',DB::raw('"GR" AS type'),
									'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','GR.voucher_no AS reference_no',
									'IM.item_code','IM.description','GRM.quantity','GRM.unit_price','GR.voucher_date','GR.voucher_no');
					
					
					//$results = $query2->get();
					$results = $query->union($query2)->get();
					
					return $results;			
				
				case 'stockout':
					$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
					$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';
				$job_id = isset($attributes['job_id'])?$attributes['job_id']:''; 
					
					$query1 = DB::table('jobmaster')->where('jobmaster.status', 1)
												->join('sales_invoice AS SI', function($join) {
														$join->on('SI.job_id','=','jobmaster.id');
													} )
												->join('account_master AS AC', function($join) {
														$join->on('AC.id','=','SI.customer_id');
													} )
												->join('sales_invoice_item AS SIM', function($join) {
														$join->on('SIM.sales_invoice_id','=','SI.id');
													} )
												->join('itemmaster AS IM', function($join) {
														$join->on('IM.id','=','SIM.item_id');
													} )
												->where('IM.class_id',1)
												->where('SI.status', 1)
												->where('SI.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$query1->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$query1->whereBetween('SI.voucher_date', array($date_from, $date_to));
					
					$query1->select('jobmaster.id','jobmaster.code','jobmaster.name','SI.id AS siid','IM.id AS item_id',DB::raw('"SI" AS type'),
														 'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','SI.reference_no',
														 'IM.item_code','IM.description','SIM.quantity','SIM.unit_price','SI.voucher_date','SI.voucher_no');
												
					
					$query2 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('goods_issued AS GI', function($join) {
										$join->on('GI.job_id','=','jobmaster.id');
									} )
								->join('account_master AS AC', function($join) {
										$join->on('AC.id','=','GI.job_account_id');
									} )
								->join('goods_issued_item AS GIM', function($join) {
										$join->on('GIM.goods_issued_id','=','GI.id');
									} )
								->join('itemmaster AS IM', function($join) {
										$join->on('IM.id','=','GIM.item_id');
									} )
								->where('GI.status', 1)
								->where('GI.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$query2->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$query2->whereBetween('GI.voucher_date', array($date_from, $date_to));
					
					$query2->select('jobmaster.id','jobmaster.code','jobmaster.name','GI.id AS siid','IM.id AS item_id',DB::raw('"GI" AS type'),
									'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','GI.voucher_no AS reference_no',
									'IM.item_code','IM.description','GIM.quantity','GIM.unit_price','GI.voucher_date','GI.voucher_no');
									//->groupBy('GI.id');
					
					//$results = $query2->get(); 
					$results = $query1->union($query2)->get();//['invoice']
					
					return $results;	
					
				default;
					$results = array();
						
			}
		}
		return $results;
	}
	
	public function getSearchOld()
	{
		$data = array();
		
		
		if($request->get('search_type')=="summary") {
			$reports = $this->sales_invoice->getReportVehicle($request->all());
			$voucher_head = 'Job Invoice Summary';
		} else if($request->get('search_type')=="sales_register") {
			$reports = $this->sales_invoice->getReportVehicle($request->all());
			$voucher_head = 'Job Invoice Summary';//'Job Register Summary';
			$reports = $this->makeTree($reports);
		} else if($request->get('search_type')=="detail") {
			$reports = $this->makeTree($this->sales_invoice->getReportVehicle($request->all()));
			$voucher_head = 'Job Invoice Detail';
			//$reports = $this->groupbyVoucherNo($reports);
		} else if($request->get('search_type')=="itemwise") {
			$reports = $this->sales_invoice->getReportVehicle($request->all()); //$this->makeTree(
			$voucher_head = 'Job Invoice by Itemwise';
			$reports = $this->groupbyItemwise($reports);
		} else if($request->get('search_type')=="vehicle") {
			$reports = $this->sales_invoice->getReportVehicle($request->all());
			$voucher_head = 'Job Invoice by Vehiclewise';
		} else if($request->get('search_type')=="job_order") {
			$reports = $this->sales_invoice->getPendingJobs($request->all());
			$voucher_head = 'Job Order(Pending)';
			$reports = $this->getPendingOrder($reports);
		}
		
		//echo '<pre>';print_r($reports);exit;
		return view('body.jobinvoice.preprint')
					->withReports($reports)
					->withVoucherhead($voucher_head)
					->withType($request->get('search_type'))
					->withFromdate($request->get('date_from'))
					->withTodate($request->get('date_to'))
					->withI(0)
					->withVehicleno($request->get('vehicle_no'))
					->withSettings($this->acsettings)
					->withData($data);
	}
	
	public function dataExportOld()
	{
		$data = array();
		//$request->merge(['type' => 'export']);
		$reports = $this->sales_invoice->getReportVehicle($request->all());
		
		 //echo '<pre>';print_r($reports);exit;
		
		if($request->get('search_type')=='sales_register') {
			$voucher_head = 'Job Invoice Summary';
			$reports = $this->makeTree($reports);
			$datareport[] = ['JI.No.','JI.Date', 'Customer','Gross Amt.','Discount','VAT Amt.','Net Total'];
			
			foreach ($reports as $key => $report) {
				
				$datareport[] = [$key,'','','','','',''];
				$nettotal=$netdiscount=$netvat_amount=$net_amount_total=0;
				
				foreach ($report as $row) {
					
				  $nettotal += $row->total;
				  $netdiscount += $row->discount;
				  $netvat_amount += $row->vat_amount;
				  $net_amount_total += $row->net_total;
											  
					$datareport[] = [ 'jino' => $row->voucher_no, 
									  'vchr_dt' => date('d-m-Y',strtotime($row->voucher_date)),
									  'customer' => $row->master_name,
									  'gross' => number_format($row->total,2),
									  'discount' => number_format($row->discount,2),
									  'vat' => number_format($row->vat_amount,2),
									  'total' => number_format($row->net_total,2)
									];
				}
				$datareport[] = ['','','Total:',number_format($nettotal,2),number_format($netdiscount,2),number_format($netvat_amount,2),number_format($net_amount_total,2)];
			}
			
		} else if($request->get('search_type')=='detail') {
			
			$voucher_head = 'Job Invoice Detail';
			$reports = $this->makeTree($reports);
			$datareport[] = ['JO.NO','JI.No.','JI.Date', 'Customer','TRN No','Vehicle No','Gross Amt.','Discount','VAT Amt.','Net Total'];
			
			foreach ($reports as $key => $report) {
				
				$datareport[] = [$key,'','','','','','','','',''];
				$nettotal=$netdiscount=$netvat_amount=$net_amount_total=0;
				
				foreach ($report as $row) {
					
				  $nettotal += $row->total;
				  $netdiscount += $row->discount;
				  $netvat_amount += $row->vat_amount;
				  $net_amount_total += $row->net_total;
											  
					$datareport[] = [ 'jono' => $row->document_id, 
									  'jino' => $row->voucher_no, 
									  'vchr_dt' => date('d-m-Y',strtotime($row->voucher_date)),
									  'customer' => $row->master_name,
									  'trnno' => $row->vat_no,
									  'regno' => $row->reg_no,
									  'gross' => number_format($row->total,2),
									  'discount' => number_format($row->discount,2),
									  'vat' => number_format($row->vat_amount,2),
									  'total' => number_format($row->net_total,2)
									];
				}
				$datareport[] = ['','','','Total:','','',number_format($nettotal,2),number_format($netdiscount,2),number_format($netvat_amount,2),number_format($net_amount_total,2)];
			}
		
		} else if($request->get('search_type')=='itemwise') {	
		
			$reports = $this->makeTree($reports);
			$reports = $this->groupbyItemwise($reports);
			$voucher_head = 'Job Invoice by Itemwise';
			
			foreach($reports as $keyname => $report)
			{
				$datareport[] = [$keyname,'','','','','','',''];
				$datareport[] = ['JO.NO','JI.No.','JI.Date','Customer','TRN No','Qty.','Price','Net Total'];
				foreach($report as $items) {
					$datareport[] = ['Item Name:'.$items[0]->description,'','','','','','',''];
					$nettotal=$quantity=0;
					
					foreach($items as $row) {
						$datareport[] = [ 'jono' => $row->document_id, 
										  'jino' => $row->voucher_no, 
										  'vchr_dt' => date('d-m-Y',strtotime($row->voucher_date)),
										  'customer' => $row->master_name,
										  'trnno' => $row->vat_no,
										  'qty' => $row->quantity,
										  'price' => number_format($row->unit_price,2),
										  'total' => number_format($row->total,2)
									];
						$quantity += $row->total;
						$nettotal += $row->net_total;
					}
					
					$datareport[] = ['','','','Total:','',$quantity,'',number_format($nettotal,2)];
				}
			}
		}
		
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
	
	public function getDocs($id) {
	    
	    $docs = DB::table('si_photos')->where('invoice_id',$id)->get();
	    $job = $this->sales_invoice->find($id);
	    
	    return view('body.jobinvoice.docsview')
					->withJobdata($job)
					->withDocs($docs);
					
	}
}


