<?php

namespace App\Http\Controllers;
use App\Repositories\Area\AreaInterface;
use App\Repositories\Itemmaster\ItemmasterInterface;
use App\Repositories\Terms\TermsInterface;
use App\Repositories\Jobmaster\JobmasterInterface;

use App\Repositories\Currency\CurrencyInterface;
use App\Repositories\VoucherNo\VoucherNoInterface;
use App\Repositories\Salesman\SalesmanInterface;
use App\Repositories\QuotationSales\QuotationSalesInterface;
use App\Repositories\AccountMaster\AccountMasterInterface;
use App\Repositories\AccountSetting\AccountSettingInterface;
use App\Repositories\SalesInvoice\SalesInvoiceInterface;
use App\Repositories\SalesOrder\SalesOrderInterface;
use App\Repositories\CustomerDo\CustomerDoInterface;
use App\Repositories\Location\LocationInterface;
use App\Repositories\Country\CountryInterface;
use App\Repositories\Acgroup\AcgroupInterface;
use App\Repositories\PurchaseInvoice\PurchaseInvoiceInterface;
use App\Repositories\Forms\FormsInterface;
use App\Repositories\Bank\BankInterface;
use App\Repositories\UpdateUtility;
use App\Repositories\ReceiptVoucher\ReceiptVoucherInterface;

use Illuminate\Http\Request;

use App\Http\Requests;
use Session;
use Response;
use Excel;
use App;
use DB;
use Mail;
use PDF;
use Auth;

class SalesInvoiceController extends Controller
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
	protected $location;
	protected $country;
	protected $area;
	protected $group;
	protected $purchase_invoice;
	protected $forms;
	protected $formData;
	protected $bank;
	protected $mod_autocost;
	protected $receipt_voucher;
	
	
	public function __construct(CustomerDOInterface $customerdo, AreaInterface $area, AcgroupInterface $group, SalesInvoiceInterface $sales_invoice, SalesOrderInterface $sales_order, QuotationSalesInterface $quotation_sales, ItemmasterInterface $itemmaster, TermsInterface $terms, JobmasterInterface $jobmaster, AccountMasterInterface $accountmaster, CurrencyInterface $currency, VoucherNoInterface $voucherno, SalesmanInterface $salesman, AccountSettingInterface $accountsetting,LocationInterface $location,CountryInterface $country, PurchaseInvoiceInterface $purchase_invoice, FormsInterface $forms, BankInterface $bank, ReceiptVoucherInterface $receipt_voucher) {
		
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
		$this->location = $location;
		$this->country = $country;
		$this->area = $area;
		$this->group = $group;
		$this->purchase_invoice = $purchase_invoice;
		$this->forms = $forms;
		$this->formData = $this->forms->getFormData('SI');
		$this->bank = $bank;
		$this->receipt_voucher = $receipt_voucher;
		
		if(Session::get('cost_accounting')==1) {
			$this->cost_accounts = $this->accountsetting->getCostAccounts(); //print_r($this->cost_accounts);
			Session::put('stock', $this->cost_accounts['stock']);
			Session::put('cost_of_sale', $this->cost_accounts['cost_of_sale']);
		}
		
		$this->mod_autocost = DB::table('parameter2')->where('keyname', 'mod_autocost_refresh')->where('status',1)->select('is_active')->first();
		$this->mod_si_roundoff = DB::table('parameter2')->where('keyname', 'mod_si_roundoff')->where('status',1)->select('is_active')->first();
		$this->objUtility = new UpdateUtility();

		$this->mod_con_loc = DB::table('parameter2')->where('keyname', 'mod_con_location')->where('status',1)->select('is_active')->first();
		$this->mod_consolidate_item = DB::table('parameter2')->where('keyname', 'mod_consolidate_item')->where('status',1)->select('is_active')->first();
		$this->mod_mpqty = DB::table('parameter2')->where('keyname', 'mod_mp_qty')->where('status',1)->select('is_active')->first();
		$this->mod_mnsqty_location = DB::table('parameter2')->where('keyname', 'mod_mnsqty_location')->where('status',1)->select('is_active')->first();
	}
	
    public function index() {
		
		//Session::put('cost_accounting', 0);
		$data = array();
		//$this->sales_invoice->InvoiceLogProcess();
		$invoices = [];
		$salesmans = $this->salesman->getSalesmanList();
		
		$customer = $this->accountmaster->getCustomerList();
		$jobs = $this->jobmaster->activeJobmasterList();
	
        $item = DB::table('itemmaster')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		
		$category = DB::table('category')->where('parent_id',0)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		$subcategory = DB::table('category')->where('parent_id',1)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		$group = DB::table('groupcat')->where('parent_id',0)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		$subgroup = DB::table('groupcat')->where('parent_id',1)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		
		//DEPT CHECK...
		if(Session::get('department')==1) {
			$departments = DB::table('department')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
			$is_dept = true;
		} else {
			$departments = []; $is_dept = false;
		}
		return view('body.salesinvoice.index')
					->withInvoices($invoices)
					->withCategory($category)
		            ->withSubcategory($subcategory)
		            ->withGroup($group)
		            ->withSubgroup($subgroup)
	                ->withItem($item)
					->withDepartments($departments)
					->withCustomer($customer)
					->withType('')
					->withSalesman($salesmans)
					->withDepartments($departments)
					->withSettings($this->acsettings)
					->withIsdept($is_dept)
					->withJobs($jobs)
					->withData($data);
	}
	
	public function ajaxPaging(Request $request)
	{
		$columns = array( 
                            0 =>'sales_invoice.id', 
                            1 =>'voucher_no',
                            2=> 'voucher_date',
							3 => 'job',
                            4=> 'customer',
                            5=> 'net_total',
							6=> 'status',
							7=>'history'
                        );
						
		$totalData = $this->sales_invoice->salesInvoiceListCount();
            
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = 'sales_invoice.id'; //$columns[$request->input('order.0.column')]; //'sales_invoice.voucher_no';
        $dir = $request->input('order.0.dir'); //'desc';
		$search = (empty($request->input('search.value')))?null:$request->input('search.value');
		$dept = $request->input('dept');
        

		$invoices = $this->sales_invoice->salesInvoiceList('get', $start, $limit, $order, $dir, $search, $dept);
		
		if($search)
			$totalFiltered =  $this->sales_invoice->salesInvoiceList('count', $start, $limit, $order, $dir, $search, $dept);
		
		$prints = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','SI')
							->select('report_view_detail.name','report_view_detail.id','report_view_detail.print_name')
							->get();

	//	echo '<pre>';print_r($prints);exit;
		
        $data = array();
        if(!empty($invoices))
        {
           
			foreach ($invoices as $row)
            {
                $edit =  '"'.url('sales_invoice/edit/'.$row->id).'"';
                //$delete =  'funDelete("'.$row->id.'")';
                $delete =  'funDelete("'.$row->id.'","'.$row->is_editable.'")';
				$print = url('sales_invoice/print/'.$row->id);
			//	$print =	url('sales_invoice/print/'.$row->id.'/'.$prints[0]->id);
				//$printfc = url('sales_invoice/printfc/'.$row->id);
				$printdo = url('sales_invoice/printdo/'.$row->id);
					$viewonly =  url('sales_invoice/viewonly/'.$row->id);
				
                $nestedData['id'] = $row->id;
                $nestedData['voucher_no'] = $row->voucher_no;
				$nestedData['voucher_date'] = date('d-m-Y', strtotime($row->voucher_date));
				$nestedData['job'] = $row->job;
				$nestedData['customer'] = ($row->customer=='CASH CUSTOMERS') ? (($row->customer_name!='')?$row->customer.'('.$row->customer_name.')':$row->customer) : $row->customer;
				$nestedData['net_total'] = number_format($row->net_total - $row->roundoff,2);
				$nestedData['history']  =  "<button class='btn btn-primary btn-xs order-history' data-toggle='modal' data-target='#history_modal' data-id='{$row->id}'>Item History</button>";
                $nestedData['edit'] = "<p><button class='btn btn-primary btn-xs' onClick='location.href={$edit}'>
												<span class='glyphicon glyphicon-pencil'></span></button></p>";
				$nestedData['viewonly'] = "<p><a href='{$viewonly}' class='btn btn-info btn-xs' target='_blank'><i class='glyphicon glyphicon-eye-open'></i></a></p>";
								
												
				$nestedData['delete'] = "<button class='btn btn-danger btn-xs delete' onClick='{$delete}'>
												<span class='glyphicon glyphicon-trash'></span>";
				
				$apr = ($this->acsettings->doc_approve==1)?[1]:[0,1,2];
				
				##RV BALANCE VIEW,.......
				//$nestedData['amtview']  =  "<button class='btn btn-primary btn-xs getBill' data-toggle='modal' data-target='#amtview_modal' data-id='{$row->id}'><i class='fa fa-fw fa-eye'></i></button>";
				
				##INVOICE PHOTOS VIEW......
				$view =  url('sales_invoice/photo-view/'.$row->id);
				$nestedData['amtview'] = "<p><a href='{$view}' target='_blank' class='btn btn-primary btn-xs'><i class='fa fa-fw fa-eye'></i></a></p>";
				
				$opts = '';					
				foreach($prints as $doc) {
				//	echo '<pre>';print_r($doc);exit;
					$opts .= "<li role='presentation'><a href='{$print}/".$doc->id."' target='_blank' role='menuitem'>".$doc->name."</a></li>";
				}
				//$print=48;

				//$printfc = url('sales_invoice/printfc/'.$row->id.'/'.$prints[1]->id); //MAR18

				$printfc = url('sales_invoice/printfc/'.$row->id.'/'.$prints[0]->id); //MAR18
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
										</div><a href='{$print}' target='_blank' class='btn btn-primary btn-xs'><span class='fa fa-fw fa-print'></span>FC</a>";
										
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
				
				if($this->acsettings->doc_approve==1) {
					if($row->doc_status==1)
						$status = '<span class="label label-sm label-success">Approved</span>';
					else if($row->doc_status==0)
						$status = '<span class="label label-sm label-warning">Pending</span>';
					else if($row->doc_status==2)
						$status = '<span class="label label-sm label-danger">Rejected</span>';
					
					$nestedData['status'] = $status;
				} else 
					$nestedData['status'] = '';
				
				/* if($row->is_fc==1) {								
					$nestedData['print'] = "<p><a href='{$print}' target='_blank' class='btn btn-primary btn-xs'><span class='fa fa-fw fa-print'></span></a>
											<a href='{$print}/FC' target='_blank' class='btn btn-primary btn-xs'><span class='fa fa-fw fa-print'></span>FC</a></p>";
				} else {
					$nestedData['print'] = "<p><a href='{$print}' target='_blank' class='btn btn-primary btn-xs'><span class='fa fa-fw fa-print'></span></a></p>";
				} */
				// $nestedData['printdo'] = "<p><a href='{$printdo}' target='_blank' class='btn btn-primary btn-xs'><span class='fa fa-fw fa-print'></span>DO</a></p>";
				$nestedData['printdo']='';							
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
	public function getCustomerMultiselect()
	{
		$data = array();
		$customers = $this->accountmaster->getCustomerList();
		return view('body.salesinvoice.multiselect')
		            ->withCustomer($customers) 
					
					->withType('CUST')
					->withData($data);
					
		
		
	}

	public function add($id = null, $doctype = null) {
		//$this->objUtility->reEvalItemCostQuantity([200],$this->acsettings);

		$acarr = ['Discount in Sales', 'Stock Account', 'Stock Excess', 'Cost Difference', 'Cost of Sales'];
		$acounts = DB::table('other_account_setting')->where('other_account_setting.status', 1)
							->leftJoin('account_master AS am', function($join) {
								$join->on('am.id','=','other_account_setting.account_id');
								$join->where('am.status','=',1);
								$join->where(function ($q) {
									$q->whereNull('am.deleted_at')
									->orWhere('am.deleted_at', '0000-00-00 00:00:00');
								});
							} )
							->select('other_account_setting.*','am.master_name','am.account_id as code')
							->orderBy('other_account_setting.id','ASC')->get();
		//echo '<pre>';print_r($acounts);exit;
		$status_acc = true;
		foreach($acounts as $acc) {
			if(in_array($acc->account_setting_name, $acarr)) {
				if($acc->master_name=='')
					$status_acc = false;
			}
		}
		//echo $status_acc;exit;
		$data = array();//echo $inv); print_r($inv);exit;
		$itemmaster = $this->itemmaster->activeItemmasterList();
		$terms = $this->terms->activeTermsList();
		$jobs = $this->jobmaster->activeJobmasterList();
		$currency = $this->currency->activeCurrencyList();
		$location = $this->location->locationList();
		
		$sideptid = (Session::has('SI_deptid'))?Session::get('SI_deptid'):'';
		$sivchrid = (Session::has('SI_vchrid'))?Session::get('SI_vchrid'):'';
		/* $sivchrno = (Session::has('SI_vchrno'))?Session::get('SI_vchrno'):'';
		$sicurno = (Session::has('SI_curno'))?Session::get('SI_curno'):''; */
		$sisalesac = (Session::has('SI_salesac'))?Session::get('SI_salesac'):'';
		$sicracid = (Session::has('SI_cracid'))?Session::get('SI_cracid'):'';
		
		//echo '<pre>';print_r($vouchers);exit;
		$lastid = $this->sales_invoice->getLastId();
		$locdefault = DB::table('location')->where('is_default',1)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id')->first();
		$sales_location = DB::table('parameter3')
							 ->join('location', 'location.id', '=', 'parameter3.location_id')
							 ->join('account_master', 'account_master.id', '=', 'parameter3.account_id')
							 ->select('location.name','location.id','account_master.master_name','account_master.id AS account_id')
							 ->get();
		
		//RV FORM ENTRY............
		$banks = $this->bank->activeBankList();
		$vchrdata = $this->getVoucherRV(9,'CASH');
		$footertxt = DB::table('header_footer')->where('doc','SI')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->first();
		$print = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','SI')
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
			$deptid = ($sideptid!='')?$sideptid:$deptid;
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
		
		//echo '<pre>';print_r($departments);exit;
		
		$crmtem = DB::table('crm_template')->where('doc_type','SOB')->where('deleted_at',null)->get();
		//echo '<pre>';print_r($crmtem);exit;
		
		if($id) {
		    $docnos = ''; $batch_items = null;
			$ids = explode(',', $id); $getItemLocation = $itemlocedit = $cnitemlocedit = [];
			if($doctype=='QS') {
				$docRow = $this->quotation_sales->findQuoteData($ids[0]);
				$docItems = $this->quotation_sales->getQSItems($ids);
				$itemdesc = $this->makeTreeArr($this->quotation_sales->getItemDesc($ids));
				
				//AUG24
				$resdo = DB::table('quotation_sales')->whereIn('id',$ids)->select('voucher_no')->get();
				foreach($resdo as $rw) {
					$docnos = ($docnos=='')?$rw->voucher_no:$docnos.','.$rw->voucher_no;
				}
			} else if($doctype=='SI') {
				$docRow = $this->sales_invoice->findPOdata($ids[0]);
				$docItems = $this->sales_invoice->getItems($ids);
				$itemdesc = $this->makeTreeArr($this->sales_invoice->getItemDesc($ids));
			}
			else if($doctype=='SO') {
				$docRow = $this->sales_order->findOrderData($ids[0]);
				$docItems = $this->sales_order->getSOItems($ids);
				$itemdesc = $this->makeTreeArr($this->sales_order->getItemDesc($ids));
				$sid = $ids[0];
				$crmtem = DB::table('crm_template')
					->leftJoin('crm_info', function($join) use($sid){
							$join->on('crm_info.temp_id','=','crm_template.id');
							$join->where('crm_info.doc_id','=',$sid);
					})
					->where('crm_template.doc_type','SOB')
					->where('crm_template.deleted_at',null)->get();
					
				//AUG24
				$resdo = DB::table('sales_order')->whereIn('id',$ids)->select('voucher_no')->get();
				foreach($resdo as $rw) {
					$docnos = ($docnos=='')?$rw->voucher_no:$docnos.','.$rw->voucher_no;
				}
					
			} else if($doctype=='CDO') {
				$docRow = $this->customerdo->findOrderData($ids[0]);
				if($this->mod_consolidate_item->is_active==1)
					$docItems = $this->consolidateItems( $this->customerdo->getDOItems($ids) ); //echo '<pre>';print_r($docItems);exit;
				else
					$docItems = $this->customerdo->getDOItems($ids);
				$itemdesc = $this->makeTreeArr($this->customerdo->getItemDesc($ids));
				$getItemLocation = $this->itemmaster->getItemLocation($id,'CDO'); //SI
				$itemlocedit = $this->makeTreeItmLoc( $this->itemmaster->getItemLocEdit($id,'CDO') ); //SI
				
				//AUG24
				$resdo = DB::table('customer_do')->whereIn('id',$ids)->select('voucher_no')->get();
				foreach($resdo as $rw) {
					$docnos = ($docnos=='')?$rw->voucher_no:$docnos.','.$rw->voucher_no;
				}
				
				//MAY25 BATCH ENTRY.....	
        		$batch_res = $batchs = $batch_items = null;
        		$batch_res = DB::table('batch_log')->whereNull('batch_log.deleted_at')
        		                    ->Join('item_batch AS IB', function($join) {
                                		$join->on('IB.id','=','batch_log.batch_id');
                                	})
                                	->where('batch_log.document_type', 'CDO')
                                	->where('batch_log.document_id', $ids[0])
                                	->whereNull('IB.deleted_at')
                                	->select('IB.*','batch_log.doc_row_id','batch_log.log_id','batch_log.id AS batch_log_id','batch_log.quantity AS log_qty')
                                	->orderBy('batch_log.doc_row_id','ASC')->get();
                                	
                $batchs = $this->batchGrouping($batch_res);
        			
        		foreach($batchs as $key => $batchrow) {
        		    $batchArr = $qtyArr = $idArr = '';
            		foreach($batchrow as $ky => $batch) {
            		    $idArr = ($idArr=='')?$batch->id:$idArr.','.$batch->id;
            		    $batchArr = ($batchArr=='')?$batch->batch_no:$batchArr.','.$batch->batch_no;
            		    $qtyArr = ($qtyArr=='')?$batch->log_qty:$qtyArr.','.$batch->log_qty;
            		}
            		$batch_items[$key] = ['ids' => $idArr, 'batches' => $batchArr, 'qtys' => $qtyArr];
        	    }
			}
			//echo '<pre>';print_r($itemlocedit);exit;
			$total = 0; $discount = 0; $nettotal = 0;
			foreach($docItems as $item) {
				$total += $item->line_total;
				$discount += $item->discount;
			}
			$nettotal = $total - $discount;
			
			$vouchers = $this->accountsetting->getAccountSettingsDefault2($vid=3,$is_dept,Session::get('dpt_id'));

			//echo '<pre>';print_r($docItems);exit;
			return view('body.salesinvoice.addpi') //addpi  addpisp
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
						->withLocation($location)
						->withFormdata($this->formData)
						->withIsdept($is_dept)
						->withDepartments($departments)
						//->withDeptid($deptid)
						->withIsconloc(($this->mod_con_loc->is_active==1)?true:false)
						->withIsmqloc(($this->mod_mnsqty_location->is_active==1)?true:false)
						->withItemloc($getItemLocation)
						->withItemlocedit($itemlocedit)
						->withCnitemlocedit($cnitemlocedit)
						->withCrm($crmtem)
						->withDocnos($docnos) //AUG24
						->withRoundoff($round_off)
						->withAccstatus($status_acc)
						->withBatchitems($batch_items); //MAY25
		}
		//echo $vouchers[0]->is_prefix;exit;
		//echo '<pre>';print_r($vouchers[0]);exit;

		if($vouchers) { 
			$prefix = $vouchers[0]->prefix;
			$isprefix = $vouchers[0]->is_prefix;
			$dept = Session::get('dpt_id');
			$vtype = $vouchers[0]->voucher_type_id;

			$usedNos = DB::table('sales_invoice')
                //->where('voucher_type', $voucherType)
                //->where('department_id', $departmentId)
                ->where('deleted_at', '0000-00-00 00:00:00')
                ->pluck('voucher_no');

			$voucherNo = $this->objUtility->previewVoucherNo($vtype, $isprefix, $prefix, $dept);

			//echo $voucherNo = $this->objUtility->generateVoucherNo($vtype, $usedNos, $isprefix, $prefix, $dept);exit;
		}
		//echo '<pre>';print_r($vouchers);exit;
		return view('body.salesinvoice.add')
					->withItems($itemmaster)
					->withTerms($terms)
					->withJobs($jobs)
					->withCurrency($currency)
					->withVouchers($vouchers)
					->withVatdata($this->vatdata)
					->withSettings($this->acsettings)
					->withLocation($location)
					->withLocdefault($locdefault)
					->withPrintid($lastid)
					->withSaleslocation($sales_location)
					->withFormdata($this->formData)
					->withBanks($banks)
					->withRvvoucher($vchrdata)
					->withRvid($rvid=9)
					->withPrint($print)
					->withSvchrid(Session::get('voucher_id'))
					->withSslsacnt(Session::get('sales_account'))
					->withScrid(Session::get('cr_account_id'))
					->withScstname(Session::get('customer_name'))
					->withScstid(Session::get('customer_id'))
					->withSdrid(Session::get('dr_account_id'))
					->withSiscsh(Session::get('is_cash'))
					->withIsdept($is_dept)
					->withDepartments($departments)
					->withDeptid($deptid)
					->withRoundoff($round_off) 
					->withIsconloc(($this->mod_con_loc->is_active==1)?true:false)
					->withIsmqloc(($this->mod_mnsqty_location->is_active==1)?true:false)
					->withSideptid($sideptid)
					->withSivchrid($sivchrid)
					//->withSivchrno($sivchrno)
					//->withSicurno($sicurno)
					->withSisalesac($sisalesac)
					->withSicracid($sicracid)
					->withCrm($crmtem)
					->withIsmpqty($this->mod_mpqty->is_active)
					->withFooter(isset($footertxt)?$footertxt->description:'')
					->withAccstatus($status_acc)
					->withData($data);
					
	}
	
	
	//NOV24
	protected function makeTreeItmLoc($result)
	{
		$childs = array();
		foreach($result as $item)
			$childs[$item->invoice_id][$item->location_id]= $item;
		
		return $childs;
	}

	private function consolidateItems($array) {
		
		$combined = array();
		foreach( $array as $values )  {
		  if( ( $key = array_search( $values->item_id, array_column( $combined, 'item_id') ) ) !== false )  {
			$combined[$key]['quantity'] += $values->quantity;
		  } else {
			$combined[] = $values;
		  }
		}
		return $combined;
	}

	public function save(Request $request) {// echo '<pre>';print_r($request->all());exit;
		
		if(Session::get('department')==1) {
			$this->validate(
				$request, 
				[ 'customer_name' => 'required','customer_id' => 'required',
				 'item_code.*'  => 'required', 'item_id.*' => 'required',
				 'unit_id.*' => 'required',
				 'quantity.*' => 'required',
				 'cost.*' => 'required'
				],
				['customer_name.required' => 'Customer Name is required.','customer_id.required' => 'Customer name is invalid.',
				 'item_code.*.required'   => 'Item code is required.', 'item_id.*' => 'Item code is invalid.',
				 'unit_id.*' => 'Item unit is required.',
				 'quantity.*' => 'Item quantity is required.',
				 'cost.*' => 'Item cost is required.'
				]
			);
			
		} else {
			$this->validate(
				$request, 
				[ //'reference_no' => ($this->formData['reference_no']==1)?'required':'nullable', 
				 //'voucher_no' => 'required|unique:sales_invoice,voucher_no,NULL,id,deleted_at,NULL',
				 'customer_name' => 'required','customer_id' => 'required',
				  'item_code.*'  => 'required', 'item_id.*' => 'required',
				 'unit_id.*' => 'required',
				 'quantity.*' => 'required',
				 'cost.*' => 'required' 
				],
				[
				//'reference_no' => 'Reference no. is required.',
				 //'voucher_no' => 'Voucher no should be unique.',
				 'customer_name.required' => 'Customer Name is required.','customer_id.required' => 'Customer name is invalid.',
				 'item_code.*.required'   => 'Item code is required.', 'item_id.*' => 'Item code is invalid.',
				 'unit_id.*' => 'Item unit is required.',
				 'quantity.*' => 'Item quantity is required.',
				 'cost.*' => 'Item cost is required.' 
				]
				);
		}
		
		//if dept active... set department cost and stock account...
		if(Session::get('department')==1 && Session::get('cost_accounting')==1) { 
			
			$dept_accounts = $this->accountsetting->getCostAccountsDept($request->get('department_id'));
			if($dept_accounts) {
				Session::put('stock', $dept_accounts->stock_acid);
				Session::put('cost_of_sale', $dept_accounts->cost_acid);
			} else {
				Session::put('stock', $this->cost_accounts['stock']);
				Session::put('cost_of_sale', $this->cost_accounts['cost_of_sale']);
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
		
		$attributes	= $request->all();
		//$attributes['voucher_no']='';
		//$vid=DB::table('account_setting')->where('voucher_type_id',3)->where('status',1)->select('voucher_no')->first();
        //$attributes['voucher_no']=$vid->voucher_no;
		//echo '<pre>';print_r($attributes);exit;
		$id = $this->sales_invoice->create($attributes);
		//echo '<pre>';print_r($id);exit;
		if($id) { 
			//AUTO COST REFRESH CHECK ENABLE OR NOT
			if($this->mod_autocost->is_active==1) {
				$this->objUtility->reEvalItemCostQuantity($request->get('item_id'),$this->acsettings);
				app('App\Http\Controllers\UtilityController')->updateItemsCurrentQty($request->get('item_id')); //OCT24
			}
			
			if( isset($attributes['is_rv']) && $attributes['is_rv']==1 ) { 
			    //echo '<pre>';print_r($request->all());exit;
				$this->RVformSet($attributes, $id);
				$this->receipt_voucher->create($request->all());
			}
			#### mail
			if($attributes['send_email']==1) {
		        $amount=$attributes['net_amount'];
		         $data['words'] = $this->number_to_word($amount);
                 $data['salesitems'] = DB::table('sales_invoice')
		                     ->where('sales_invoice.id', $id)
		                     ->join('sales_invoice_item AS PI', function($join) {
			                $join->on('PI.sales_invoice_id','=','sales_invoice.id');
		                         })
		                     ->join('itemmaster AS IM', function($join) {
			                $join->on('IM.id','=','PI.item_id');
		                        })
		                    ->join('units AS U', function($join) {
			                $join->on('U.id','=','PI.unit_id');
		                     })
							 ->join('users', function($join) {
								$join->on('users.id','=','sales_invoice.created_by');
								 })
		                     ->where('PI.status', 1)
		                     ->where('PI.deleted_at', '0000-00-00 00:00:00')
		                     ->select('PI.*','IM.item_code','U.unit_name','sales_invoice.voucher_no','sales_invoice.total','sales_invoice.vat_amount','sales_invoice.net_total','sales_invoice.created_at','users.name')
		                      ->orderBY('PI.id','ASC')->get();
		                    //echo '<pre>';print_r($data['salesitems']);exit;
							//$pdfnew = PDF::loadView('body.salesinvoice.pdfupdateprintnw',$data);
							//return view('body.salesinvoice.pdfupdateprintnw')->withSalesitems($data['salesitems'])->withWords($data['words']);

				$email='numaktech@gmail.com';
				$no=$data['salesitems'][0]->voucher_no;
				$body='Sales Invoice created with voucher no: %s';
					$text= sprintf($body,$no);						
					try{
							Mail::send(['html'=>'body.salesinvoice.pdfaddprint'], $data,function($message) use ($email,$text) {
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
			#### End 

			Session::flash('message', 'Sales Invoice added successfully.');
		} else
			Session::flash('error', 'Something went wrong, Invoice failed to add!');
		return redirect('sales_invoice/add');
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
			
			//AUTO COST REFRESH CHECK ENABLE OR NOT
			if($this->mod_autocost->is_active==1) {
				$arritems = [];
				$items = DB::table('sales_invoice_item')->where('sales_invoice_id',$id)->select('item_id')->get();
				foreach($items as $rw) {
					$arritems[] = $rw->item_id;
				}
				$this->objUtility->reEvalItemCostQuantity($arritems,$this->acsettings);
			}
			
			$this->refreshDO($id);
			
			Session::flash('message', 'Sales invoice deleted successfully.');
		} else {
			Session::flash('error', 'Sales invoice is already in use, you can\'t delete this!');
		}
		
		return redirect('sales_invoice');
	}
	
	public function checkRefNo(Request $request) {

		$check = $this->sales_invoice->check_reference_no($request->get('reference_no'), $request->get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
	
	public function edit($id) { 
	    
	    /*$drow = DB::table('account_transaction')->where('voucher_type','SI')->where('voucher_type_id', $id)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->where('transaction_type','Dr')->select(DB::raw("SUM(account_transaction.amount) AS dr_amount"))->first();
	    
	    $crow = DB::table('account_transaction')->where('voucher_type','SI')->where('voucher_type_id', $id)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->where('transaction_type','Cr')->select(DB::raw("SUM(account_transaction.amount) AS cr_amount"))->first();
	    
	    if($drow->dr_amount!=$drow->cr_amount)
	        throw new ValidationException('Payment entry validation error! Please try again.',$this->getErrors());*/
	    
        /*$query = DB::table('sales_invoice');
      $sr = $query->select('sales_invoice.*',
       DB::raw("(SELECT COUNT(*) FROM sales_invoice AS SI WHERE SI.customer_id=sales_invoice.customer_id AND SI.status=1 AND SI.deleted_at='0000-00-00 00:00:00') AS si_count"))->get();
       
       $query = DB::table('account_transaction')->where('voucher_type','SI')->where('voucher_type_id', $id)->where('status',1)->where('deleted_at','0000-00-00 00:00:00');
      $sr = $query->select('sales_invoice.*',
       DB::raw("(SELECT SUM(account_transaction.amount) FROM account_transaction WHERE SI.customer_id=sales_invoice.customer_id AND SI.status=1 AND SI.deleted_at='0000-00-00 00:00:00') AS si_count"))->get();*/

			                       
		/*$sr = DB::table('account_transaction')->where('voucher_type','SI')->where('voucher_type_id', $id)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')
			            ->select(DB::raw("SUM(account_transaction.amount) AS dr_aount WHERE account_transaction.transaction_type='Dr'"),
			                       DB::raw("SUM(account_transaction.amount) AS cr_amount WHERE account_transaction.transaction_type='Cr'"),
			                       DB::raw("SUM(dr_amount) - SUM(cr_amount) AS balance"))->first();*/
			                       
			           //  echo '<pre>';print_r($sr);exit;
		
		$data = array();
		$itemmaster = $this->itemmaster->activeItemmasterList();
		$terms = $this->terms->activeTermsList();
		$jobs = $this->jobmaster->activeJobmasterList();
		$currency = $this->currency->activeCurrencyList();
		$orderrow = $this->sales_invoice->findPOdata($id);
		$orditems = $this->sales_invoice->getItems($id); //
		//echo '<pre>';print_r($orderrow);exit;
		$itemdesc = $this->makeTreeArr($this->sales_invoice->getItemDesc($id));
		$custdata = DB::table('account_master')->where('id',$orderrow->customer_id)->select('cl_balance','credit_limit','pdc_amount')->first();
		$location = $this->location->locationList();
		$getItemLocation = $this->itemmaster->getItemLocation($id,'SI');
		$itemlocedit = $this->makeTreeArrLoc( $this->itemmaster->getItemLocEdit($id,'SI') );
		$voucher = $this->accountsetting->find($orderrow->voucher_id); 
		$vouchers = $this->accountsetting->getAccountSettingsDefault2($vid=3,false,Session::get('dpt_id')); //echo '<pre>';print_r($vouchers);exit;
		
		$cngetItemLocation = $this->itemmaster->getcnItemLocations();
		$cnitemlocedit = $this->makeTreeArrLoc( $this->itemmaster->getcnItemLocEdit($id,'SI') );
		
		$serow = $this->sales_invoice->getSellExp($id);//echo '<pre>';print_r($ocrow);exit;
		
		$crmtem = DB::table('crm_template')
					->leftJoin('crm_info_si', function($join) use($id){
							$join->on('crm_info_si.temp_id','=','crm_template.id');
							$join->where('crm_info_si.doc_id','=',$id);
					})
					->where('crm_template.doc_type','SOB')
					->where('crm_template.deleted_at',null)->get();
					
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
		
		$apr = ($this->acsettings->doc_approve==1)?[1]:[0,1,2];
		if(in_array($orderrow->doc_status, $apr))
			$isprint = true;
		else
			$isprint = null;
		
		$print = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','SI')
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
		
		$photos = DB::table('si_photos')->where('invoice_id',$id)->get(); 
		/* $val = '';
		foreach($photos as $row) {
			$val .= ($val=='')?$row->photo:','.$row->photo;
		} */
		
		//MAY25 BATCH ENTRY.....	
		$batch_res = $batchs = $batch_items = null;
		$batch_res = DB::table('batch_log')->whereNull('batch_log.deleted_at')
		                    ->Join('item_batch AS IB', function($join) {
                        		$join->on('IB.id','=','batch_log.batch_id');
                        	})
                        	->where('batch_log.document_type', 'SI')
                        	->where('batch_log.document_id', $id)
                        	->whereNull('IB.deleted_at')
                        	->select('IB.*','batch_log.doc_row_id','batch_log.log_id','batch_log.id AS batch_log_id','batch_log.quantity AS log_qty')
                        	->orderBy('batch_log.doc_row_id','ASC')->get();
                        	
        $batchs = $this->batchGrouping($batch_res);
			
		foreach($batchs as $key => $batchrow) {
		    $batchArr = $qtyArr = $idArr = '';
    		foreach($batchrow as $ky => $batch) {
    		    $idArr = ($idArr=='')?$batch->id:$idArr.','.$batch->id;
    		    $batchArr = ($batchArr=='')?$batch->batch_no:$batchArr.','.$batch->batch_no;
    		    $qtyArr = ($qtyArr=='')?$batch->log_qty:$qtyArr.','.$batch->log_qty;
    		}
    		$batch_items[$key] = ['ids' => $idArr, 'batches' => $batchArr, 'qtys' => $qtyArr];
	    }
			//echo '<pre>';print_r($batchs);print_r($batch_items);exit;
			
		return view('body.salesinvoice.edit') //edit  editsp
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
					->withLocation($location)
					->withItemloc($getItemLocation)
					->withItemlocedit($itemlocedit)
					->withFormdata($this->formData)
					->withVouchers($vouchers)
					->withVoucher($voucher)
					->withBanks($banks)
					->withRvvoucher($vchrdata)
					->withRvid($rvid=9)
					->withRventry($rventry)
					->withIsprint($isprint)
					->withPrint($print)
					->withIsdept($is_dept)
					->withDepartments($departments)
					->withDeptid($deptid)
					->withRoundoff($round_off)
					->withCnitemloc($cngetItemLocation)
					->withCnitemlocedit($cnitemlocedit)
					->withIsconloc(($this->mod_con_loc->is_active==1)?true:false)
					->withIsmqloc(($this->mod_mnsqty_location->is_active==1)?true:false)
					->withSerow($serow)
					->withCrm($crmtem)
					->withPhotos($photos)
					->withIsmpqty($this->mod_mpqty->is_active)
					->withBatchitems($batch_items) //MAY25
					->withData($data);

	}
	
	//MAY25
	protected function batchGrouping($result) {
	    
	    $childs = array();
		foreach($result as $item)
		    $childs[$item->doc_row_id][] = $item;
			
		return $childs;
	}
	
	private function makeTreeArrLoc($result) {
		
		$childs = array();
		foreach($result as $item)
			$childs[$item->invoice_id][] = $item;
		
		return $childs;
	}
	
	public function update(Request $request)
	{ //echo '<pre>';print_r($request->all());exit;
		$id = $request->input('sales_invoice_id');
		$this->validate(
			$request, 
			[//'reference_no' => 'required',
			 'customer_name' => 'required','customer_id' => 'required',
			 'item_code.*'  => 'required', 'item_id.*' => 'required',
			 'unit_id.*' => 'required',
			 'quantity.*' => 'required',
			 'cost.*' => 'required'
			],
			[//'reference_no.required' => 'Reference no. is required.',
			 'customer_name.required' => 'Customer Name is required.','customer_id.required' => 'Customer name is invalid.',
			 'item_code.*.required'   => 'Item code is required.', 'item_id.*' => 'Item code is invalid.',
			 'unit_id.*' => 'Item unit is required.',
			 'quantity.*' => 'Item quantity is required.',
			 'cost.*' => 'Item cost is required.'
			]
			);
		
		if( $this->sales_invoice->update($id, $request->all()) ) {
			
			//AUTO COST REFRESH CHECK ENABLE OR NOT
			if($this->mod_autocost->is_active==1) {
				$this->objUtility->reEvalItemCostQuantity($request->get('item_id'),$this->acsettings);
				app('App\Http\Controllers\UtilityController')->updateItemCurrentQty($request->get('item_id')); //OCT24
			}
			
		//	$this->refreshDO($id);
			
			$attributes = $request->all();
			if( isset($attributes['is_rv']) && $attributes['is_rv']==1 ) { 
				$sirv = DB::table('receipt_voucher')->where('sales_invoice_id',$id)->select('id')->first();//echo '<pre>';print_r($attributes);exit;
				if($sirv) {
				    
					$this->RVformSet($attributes, $id);
					$this->receipt_voucher->update($request->get('rvedit_id'), $request->all());
				} else {
					$this->RVformSet($attributes, $id);
					$this->receipt_voucher->create($request->all());
				}
			}
			
			########## email script #############
			//echo '<pre>';print_r($attributes['send_email']);exit;
			if( $attributes['send_email']==1){	
			if($this->acsettings->doc_approve==1 && $request->get('doc_status')==1 ) {
				
				$attributes['document_id'] = $id; //echo "892 : ".$this->number_to_word(12495);exit;
				$attributes['is_fc'] = $fc = '';
				$result = $this->sales_invoice->getInvoiceById($attributes);
				$titles = ['main_head' => 'Tax Invoice','subhead' => 'Tax Invoice'];//echo '<pre>';print_r($result);exit;
				$itemdesc = $this->makeTreeArr($this->sales_invoice->getItemDesc($id));
				$words = ($fc)?$this->number_to_word($result['details']->net_total_fc):$this->number_to_word($result['details']->net_total);
				$vat_words = ($fc)?$this->number_to_word($result['details']->vat_amount_fc):$this->number_to_word($result['details']->vat_amount);
				$amount = ($fc)?$result['details']->net_total_fc:$result['details']->net_total;
				$vatamount = ($fc)?$result['details']->vat_amount_fc:$result['details']->vat_amount;
				$vtype = $this->accountsetting->checkVoucher( $result['details']['voucher_id'] );
				
				/* if($vtype->is_cash_voucher == 1)
					$view = 'newprint';
				else */
					$view = 'print';//printsp  print  f1logo
				
				
				$arr = explode('.',number_format($amount,2));
				if(sizeof($arr) >1 ) {
					if($arr[1]!=00) {
						$dec = $this->number_to_word($arr[1]);
						$words .= ' and Fils '.$dec.' Only';
					} else 
						$words .= ' Only';
				} else
					$words .= ' Only';
				
				$arrv = explode('.',number_format($vatamount,2));
				if(sizeof($arrv) >1 ) {
					if($arrv[1]!=00) {
						$dec = $this->number_to_word($arrv[1]);
						$vat_words .= ' and Fils '.$dec.' Only';
					} else 
						$vat_words .= ' Only';
				} else
					$vat_words .= ' Only';
				
				$data = array('details'=> $result['details'], 'titles' => $titles, 'amtwords' => $words,'fc' => $attributes['is_fc'],
									'vatamtwords' => $vat_words, 'itemdesc' => $itemdesc, 'id' => $id, 'items' => $result['items']);
				$pdf = PDF::loadView('body.salesinvoice.pdfprint', $data); 
				
				$mailmessage = $request->get('email_message');
				$emails = explode(',', $request->get('email'));
				
				if($emails[0]!='') {
					$data = array('name'=> $request->get('customer_name'), 'mailmessage' => $mailmessage );
					try{
						Mail::send('body.salesinvoice.email', $data, function($message) use ($emails,$pdf) {
							$message->to($emails[0]);
							
							if(count($emails) > 1) {
								foreach($emails as $k => $row) {
									if($k!=0)
										$cc[] = $row;
								}
								$message->cc($cc);
							}
							
							$message->subject('Tax Invoice');
							$message->attachData($pdf->output(), "taxinvoice.pdf");
						});
						
					}catch(JWTException $exception){
						$this->serverstatuscode = "0";
						$this->serverstatusdes = $exception->getMessage();
						echo '<pre>';print_r($this->serverstatusdes);exit;
					}
				}
				
			}

			#### update email
          /*  $sid =$attributes['sales_invoice_id'];
			//echo '<pre>';print_r($sid);exit;
		$amount=$attributes['net_amount'];
		$data['codeold']=$attributes['item_codeold'];
		$data['nameold']=$attributes['item_nameold'];
		$data['unitold']=$attributes['item_unitold'];
        $data['qtyold']=$attributes['quantityold'];
		$data['costold']=$attributes['costold'];
		$data['vatold']=$attributes['line_vatold'];
		$data['totalold']=$attributes['line_totalold'];
		$data['grandtotalold']=$attributes['totalold'];
		$data['totvatold']=$attributes['vatold'];
		$data['netold']=$attributes['net_amountold'];
		$data['words'] = $this->number_to_word($amount);
		
		//$pdfold = PDF::loadView('body.salesinvoice.pdfupdateprintold',$data);
		//return view('body.salesinvoice.pdfupdateprintold')->withData($data);*/
		$sid =$attributes['sales_invoice_id'];
		$amount=$attributes['net_amount'];
		$data['words'] = $this->number_to_word($amount);
        $data['salesitems'] = DB::table('sales_invoice')
		                       ->where('sales_invoice.id', $sid)
		                     ->join('sales_invoice_item AS PI', function($join) {
			                $join->on('PI.sales_invoice_id','=','sales_invoice.id');
		                         })
		                     ->join('itemmaster AS IM', function($join) {
			                $join->on('IM.id','=','PI.item_id');
		                        })
		                    ->join('units AS U', function($join) {
			                $join->on('U.id','=','PI.unit_id');
		                     })
							 ->join('users', function($join) {
								$join->on('users.id','=','sales_invoice.modify_by');
								 })
		                     ->where('PI.status', 1)
		                    ->where('PI.deleted_at', '0000-00-00 00:00:00')
		                     ->select('PI.*','IM.item_code','U.unit_name','sales_invoice.voucher_no','sales_invoice.total','sales_invoice.vat_amount','sales_invoice.net_total','sales_invoice.modify_at','users.name')
		                      ->orderBY('PI.id','ASC')->get();
		                    //echo '<pre>';print_r($data['salesitems']);exit;
							//$pdfnew = PDF::loadView('body.salesinvoice.pdfupdateprintnw',$data);
							//return view('body.salesinvoice.pdfupdateprintnw')->withSalesitems($data['salesitems'])->withWords($data['words']);

							$email='veenababu1990@gmail.com';
							$no=$data['salesitems'][0]->voucher_no;
		                    $body='Sales Invoice modified with voucher no: %s';
		                     $text= sprintf($body,$no);						
								try{
										Mail::send(['html'=>'body.salesinvoice.pdfupdateprintnw'], $data,function($message) use ($email,$text) {
										$message->from(env('MAIL_USERNAME'));	
										$message->to($email);
										$message->subject($text);
										//$message->attachData($pdfold->output(), "Sales Invoice.pdf", ['mime' => 'MIME']);
										//$message->attachData($pdfnew->output(), "SalesInvoice.pdf", ['mime' => 'MIME']);
										});
									
									}catch(JWTException $exception){
									$this->serverstatuscode = "0";
									$this->serverstatusdes = $exception->getMessage();
									echo '<pre>';print_r($this->serverstatusdes);exit;
								}
							

}

			#### End 

		
			Session::flash('message', 'Sales invoice updated successfully');
		} else
			Session::flash('error', 'Something went wrong, Invoice failed to update!');
		
		return redirect('sales_invoice');
	}
	
	public function viewonly($id) { 
	    	$data = array();
		$itemmaster = $this->itemmaster->activeItemmasterList();
		$terms = $this->terms->activeTermsList();
		$jobs = $this->jobmaster->activeJobmasterList();
		$currency = $this->currency->activeCurrencyList();
		$orderrow = $this->sales_invoice->findPOdata($id);
		$orditems = $this->sales_invoice->getItems($id); //
		//echo '<pre>';print_r($orderrow);exit;
		$itemdesc = $this->makeTreeArr($this->sales_invoice->getItemDesc($id));
		$custdata = DB::table('account_master')->where('id',$orderrow->customer_id)->select('cl_balance','credit_limit','pdc_amount')->first();
		$location = $this->location->locationList();
		$getItemLocation = $this->itemmaster->getItemLocation($id,'SI');
		$itemlocedit = $this->makeTreeArrLoc( $this->itemmaster->getItemLocEdit($id,'SI') );
		$voucher = $this->accountsetting->find($orderrow->voucher_id); 
		$vouchers = $this->accountsetting->getAccountSettingsDefault2($vid=3,false,Session::get('dpt_id')); //echo '<pre>';print_r($vouchers);exit;
		
		$cngetItemLocation = $this->itemmaster->getcnItemLocations();
		$cnitemlocedit = $this->makeTreeArrLoc( $this->itemmaster->getcnItemLocEdit($id,'SI') );
		
		$serow = $this->sales_invoice->getSellExp($id);//echo '<pre>';print_r($ocrow);exit;
		
		$crmtem = DB::table('crm_template')
					->leftJoin('crm_info_si', function($join) use($id){
							$join->on('crm_info_si.temp_id','=','crm_template.id');
							$join->where('crm_info_si.doc_id','=',$id);
					})
					->where('crm_template.doc_type','SOB')
					->where('crm_template.deleted_at',null)->get();
					
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
		
		$apr = ($this->acsettings->doc_approve==1)?[1]:[0,1,2];
		if(in_array($orderrow->doc_status, $apr))
			$isprint = true;
		else
			$isprint = null;
		
		$print = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','SI')
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
		
		$photos = DB::table('si_photos')->where('invoice_id',$id)->get(); 
		/* $val = '';
		foreach($photos as $row) {
			$val .= ($val=='')?$row->photo:','.$row->photo;
		} */
		
		//MAY25 BATCH ENTRY.....	
		$batch_res = $batchs = $batch_items = null;
		$batch_res = DB::table('batch_log')->whereNull('batch_log.deleted_at')
		                    ->Join('item_batch AS IB', function($join) {
                        		$join->on('IB.id','=','batch_log.batch_id');
                        	})
                        	->where('batch_log.document_type', 'SI')
                        	->where('batch_log.document_id', $id)
                        	->whereNull('IB.deleted_at')
                        	->select('IB.*','batch_log.doc_row_id','batch_log.log_id','batch_log.id AS batch_log_id','batch_log.quantity AS log_qty')
                        	->orderBy('batch_log.doc_row_id','ASC')->get();
                        	
        $batchs = $this->batchGrouping($batch_res);
			
		foreach($batchs as $key => $batchrow) {
		    $batchArr = $qtyArr = $idArr = '';
    		foreach($batchrow as $ky => $batch) {
    		    $idArr = ($idArr=='')?$batch->id:$idArr.','.$batch->id;
    		    $batchArr = ($batchArr=='')?$batch->batch_no:$batchArr.','.$batch->batch_no;
    		    $qtyArr = ($qtyArr=='')?$batch->log_qty:$qtyArr.','.$batch->log_qty;
    		}
    		$batch_items[$key] = ['ids' => $idArr, 'batches' => $batchArr, 'qtys' => $qtyArr];
	    }
			//echo '<pre>';print_r($batchs);print_r($batch_items);exit;
			
		return view('body.salesinvoice.viewonly') //edit  editsp
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
					->withLocation($location)
					->withItemloc($getItemLocation)
					->withItemlocedit($itemlocedit)
					->withFormdata($this->formData)
					->withVouchers($vouchers)
					->withVoucher($voucher)
					->withBanks($banks)
					->withRvvoucher($vchrdata)
					->withRvid($rvid=9)
					->withRventry($rventry)
					->withIsprint($isprint)
					->withPrint($print)
					->withIsdept($is_dept)
					->withDepartments($departments)
					->withDeptid($deptid)
					->withRoundoff($round_off)
					->withCnitemloc($cngetItemLocation)
					->withCnitemlocedit($cnitemlocedit)
					->withIsconloc(($this->mod_con_loc->is_active==1)?true:false)
					->withIsmqloc(($this->mod_mnsqty_location->is_active==1)?true:false)
					->withSerow($serow)
					->withCrm($crmtem)
					->withPhotos($photos)
					->withIsmpqty($this->mod_mpqty->is_active)
					->withBatchitems($batch_items) //MAY25
					->withData($data);

	}
	
	
	
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
		//return $code = strtoupper($group->code).''.$no;
		//return $group->account_id;

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
	
	public function show($id) { 

		$data = array();
		$acmasterrow = $this->accountmaster->accountMasterView($id);
		return view('body.accountmaster.view')
					->withMasterrow($acmasterrow)
					->withData($data);
	}
	
	public function getCustomer($num=null)
	{
		$data = array();
		$customers = $this->accountmaster->getCustomerList();
		$country = $this->country->activeCountryList();
		$area = $this->area->activeAreaList();
		$cus_code = json_decode($this->ajax_getcode($category='CUSTOMER'));
		return view('body.salesinvoice.customer')
					->withCustomers($customers)
					->withArea($area)
					->withCusid($cus_code->code)
					->withFormdata($this->formData)
					->withCategory($cus_code->category)
					->withCountry($country)
					->withNum($num)
					->withData($data);
	}
	
	public function getSalesman($num=null)
	{
		$data = array();
		$salesmans = $this->salesman->getSalesmanList();
		return view('body.salesinvoice.salesman')
					->withSalesmans($salesmans)
					->withNum($num)
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
	public function getSalesInvoice()
	{
		$data = array();
		$invoices = $this->sales_invoice->getInvoice();
		
		//echo '<pre>';print_r($orders);exit;
		return view('body.salesinvoice.si')
					->withInvoices($invoices)
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
								'id' => $row->id,
								'cash_voucher' => $row->is_cash_voucher,
								'cash_account' => $row->default_account_id,
								'default_account' => $row->default_account );
		
	}
	
	public function getDeptVoucher($id) {
		
		$depts = $this->accountsetting->getVoucherByDept($vid=3, $id); 
		
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
	
	public function getSaleLocation($id) {
		
		$row = DB::table('parameter3')->where('location_id', $id)
							 ->join('account_master', 'account_master.id', '=', 'parameter3.account_id')
							 ->select('account_master.master_name','account_master.id AS account_id')
							 ->first();
		
		 return $result = array('customer_name' => $row->master_name, 
								'account_id' => $row->account_id );
		
	}
	
	public function checkInvoice(Request $request) {

		$check = $this->sales_invoice->check_invoice_id( $request->get('purchase_invoice_id') );
		$isAvailable = ($check) ? false : true;
		echo $isAvailable;
	}
	
	public function getInvoice($did=null)
	{
		$data = array();
		$invoices = [];//$this->sales_invoice->getInvoice();//echo '<pre>';print_r($pidata);exit;
		return view('body.salesinvoice.invoice')
					->withInvoices($invoices)
					->withDid($did)
					->withData($data);
	}
	
	
	public function ajaxPagingInvoiceData(Request $request)
	{
		$columns = array( 
                            0 =>'sales_invoice.id', 
                            1 =>'voucher_no',
                            2=> 'voucher_date',
                            3=> 'customer',
                            4=> 'net_total'
                        );
		
		$dept = $request->input('dept');		
		$totalData = $this->sales_invoice->salesInvoiceSRListCount($dept);
            
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
		$search = (empty($request->input('search.value')))?null:$request->input('search.value');
        
		$invoices = $this->sales_invoice->salesInvoiceSRList('get', $start, $limit, $order, $dir, $search, $dept);
		
		if($search)
			$totalFiltered =  $this->sales_invoice->salesInvoiceSRList('count', $start, $limit, $order, $dir, $search, $dept);
		
        $data = array();
        if(!empty($invoices))
        {
           
			foreach ($invoices as $row)
            {
				
				$opt =  $row->id;
				$nestedData['opt'] = "<input type='radio' name='salesDO' value='{$opt}'/>";
				
                $nestedData['id'] = $row->id;
                $nestedData['voucher_no'] = $row->voucher_no;
				$nestedData['voucher_date'] = date('d-m-Y',strtotime($row->voucher_date));
				$nestedData['customer'] = $row->customer;
				$nestedData['net_total'] = $row->net_total;
							
				$nestedData['view'] = "<a href='' class='poclk' data-id='{$opt}' data-toggle='modal' data-target='#item_modal'>View Items</a>";
												
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
	
	//ED12
	public function getInvoiceByCustomer($customer_id,$no=null,$ref=null,$rvid=null)
	{
		$rvdat = ''; $rvrefdat = $rvarr = [];
		$invoices = $this->sales_invoice->getCustomerInvoice($customer_id,null,$rvid);
		$openbalances = $this->sales_invoice->getOpenBalances($customer_id);// echo '<pre>';print_r($invoices);exit;
		$sinbills = [];//$this->sales_invoice->getSINbills($customer_id,null,$rvid);
		$otbills = $this->sales_invoice->getOthrBills($customer_id,null,$rvid); //May 15
		$splitbills = $this->sales_invoice->getSplitBills($customer_id);
		//echo '<pre>';print_r($splitbills);exit;
		if($rvid) {
			$rvdat = DB::table('receipt_voucher_entry')->where('id', $rvid)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->first();
			if($rvdat) {
				$rvref = DB::table('receipt_voucher_entry')->where('entry_type', 'Dr')->where('receipt_voucher_id',$rvdat->receipt_voucher_id)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('reference')->first();
				$rvrefdat = ($rvref)?explode(',',$rvref->reference):[];
				
				$rvarr = $this->makeArr(DB::table('receipt_voucher_entry')->where('entry_type', 'Cr')->where('receipt_voucher_id',$rvdat->receipt_voucher_id)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('reference','amount')->get());
				
			}
		}
		//$advance = $this->sales_invoice->getAdvance($customer_id); 	
		return view('body.salesinvoice.custinvoice1')
					->withNum($no)
					->withOpenbalances($openbalances)
					->withSinbills($sinbills)
					->withInvoices($invoices)
					->withRvdata($rvdat)
					->withRvref($rvrefdat)
					->withRvarr($rvarr)
					->withRefno($ref)
					->withSbills($splitbills)
					->withOtbills($otbills);
	}
	
	public function getInvoiceByCustomerEdit($customer_id,$no,$rvid)
	{
		$ref = $rvdat = ''; $rvrefdat = $rvarr = [];
		$invoices = $this->sales_invoice->getCustomerInvoice($customer_id,null,null);
		$openbalances = $this->sales_invoice->getOpenBalances($customer_id);
		$sinbills = $this->sales_invoice->getSINbills($customer_id,null,null);
		
		$rvref = DB::table('receipt_voucher_entry')->where('entry_type', 'Dr')->where('receipt_voucher_id',$rvid)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('reference')->first();
		$rvrefdat = ($rvref)?explode(',',$rvref->reference):[];
		
		$rvarr = $this->makeArr(DB::table('receipt_voucher_entry')->where('entry_type', 'Cr')->where('receipt_voucher_id',$rvid)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('reference','amount')->get());
		
		return view('body.salesinvoice.custinvoiceedit')
					->withNum($no)
					->withOpenbalances($openbalances)
					->withSinbills($sinbills)
					->withInvoices($invoices)
					->withRvdata($rvdat)
					->withRvref($rvrefdat)
					->withRvarr($rvarr)
					->withRefno($ref);
	}
	
	public function getInvoiceByCustomerCn($customer_id,$no=null,$ref=null,$rvid=null)
	{
		$rvdat = ''; $rvrefdat = $rvarr = [];
		$invoices = $this->sales_invoice->getCustomerInvoice($customer_id,null,$rvid);
		$openbalances = $this->sales_invoice->getOpenBalances($customer_id); //echo '<pre>';print_r($invoices);exit;
		$sinbills = $this->sales_invoice->getSINbills($customer_id,null,$rvid);
		$otbills = $this->sales_invoice->getOthrBills($customer_id,null,$rvid); //May 15
		if($rvid) {
			$crros = DB::table('credit_note_entry')->where('id',$rvid)->select('credit_note_id')->first();
			if($crros) {
				$rvrefdat = DB::table('credit_note')->where('id',$crros->credit_note_id)->select('cr_reference')->get();
				print_r($rvrefdat);
			}
			
			$rvarr = $this->makeArr(DB::table('receipt_voucher_entry')->where('entry_type', 'Cr')->where('receipt_voucher_id',$rvdat->receipt_voucher_id)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('reference','amount')->get());
		}
		//$advance = $this->sales_invoice->getAdvance($customer_id); 	
		return view('body.salesinvoice.custinvoice')
					->withNum($no)
					->withOpenbalances($openbalances)
					->withSinbills($sinbills)
					->withInvoices($invoices)
					->withRvdata($rvdat)
					->withRvref($rvrefdat)
					->withRvarr($rvarr)
					->withRefno($ref)
					->withOtbills($otbills); //May 15
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
		//print_r($request->all());
		Session::put('voucher_id', $request->get('vchr_id'));
		Session::put('voucher_no', $request->get('vchr_no'));
		Session::put('reference_no', $request->get('ref_no'));
		Session::put('voucher_date', $request->get('vchr_dt'));
		Session::put('lpo_date', $request->get('lpo_dt'));
		Session::put('sales_acnt', $request->get('sales_ac'));
		Session::put('acnt_master', $request->get('ac_mstr'));
		Session::put('lpo_no', $request->get('ref_no'));
		Session::put('dpt_id', $request->get('dpt_id'));

	}
	
	public function getPrint($id,$rid=null)
	{ 
		$viewfile = DB::table('report_view_detail')->where('id', $rid)->select('print_name')->first(); 
			
		if(isset($viewfile) && $viewfile->print_name=='') {
			$fc='';
			$attributes['document_id'] = $id; //echo "892 : ".$this->number_to_word(12495);exit;
			$attributes['is_fc'] = ($fc)?1:'';
			$result = $this->sales_invoice->getInvoiceById($attributes);
			$titles = ['main_head' => 'Tax Invoice','subhead' => 'Tax Invoice'];//echo '<pre>';print_r($result);exit;
			$itemdesc = $this->makeTreeArr($this->sales_invoice->getItemDesc($id));
			$words = ($fc)?$this->number_to_word($result['details']->net_total_fc):$this->number_to_word($result['details']->net_total);
			$vat_words = ($fc)?$this->number_to_word($result['details']->vat_amount_fc):$this->number_to_word($result['details']->vat_amount);
			$amount = ($fc)?$result['details']->net_total_fc:$result['details']->net_total;
			$vatamount = ($fc)?$result['details']->vat_amount_fc:$result['details']->vat_amount;
			$vtype = $this->accountsetting->checkVoucher( $result['details']['voucher_id'] );
		
			$view = 'printnew';
			$arr = explode('.',number_format($amount,2));
			if(sizeof($arr) >1 ) {
				if($arr[1]!=00) {
					$dec = $this->number_to_word($arr[1]);
					$words .= ' and Fils '.$dec.' Only';
				} else 
					$words .= ' Only';
			} else
				$words .= ' Only';
			
			$arrv = explode('.',number_format($vatamount,2));
			if(sizeof($arrv) >1 ) {
				if($arrv[1]!=00) {
					$dec = $this->number_to_word($arrv[1]);
					$vat_words .= ' and Fils '.$dec.' Only';
				} else 
					$vat_words .= ' Only';
			} else
				$vat_words .= ' Only';
			
			//echo '<pre>';print_r($result['details']);exit;
			return view('body.salesinvoice.'.$view)
						->withDetails($result['details'])
						->withTitles($titles)
						->withAmtwords($words)
						->withVatamtwords($vat_words)
						->withFc($attributes['is_fc'])
						->withItemdesc($itemdesc)
						->withFormdata($this->formData)
						->withId($id)
						->withItems($result['items']);
		} else {
					
			$path = app_path() . '/stimulsoft/helper.php';
			if(isset($viewfile))
			
				if(env('STIMULSOFT_VER')==2)
			        return view('body.reports')->withPath($path)->withView($viewfile->print_name);
			   else
			        return view('body.salesinvoice.viewer')->withPath($path)->withView($viewfile->print_name);
				
				//return view('body.salesinvoice.viewer')->withPath($path)->withView($viewfile->print_name);
				
				//return view('body.reports')->withPath($path)->withView($viewfile->print_name);
		}
		
	}
	
	public function getPrintFc($id,$rid=null)
	{
		//$viewfile = DB::table('report_view')->where('code', 'SI')->where('status',1)->select('print_name')->first();
		$viewfile = DB::table('report_view_detail')->where('id', $rid)->select('print_name')->first(); 
		
		if($viewfile->print_name=='') {
			
			$attributes['document_id'] = $id; //echo "892 : ".$this->number_to_word(12495);exit;
			$attributes['is_fc'] = 1;
			$result = $this->sales_invoice->getInvoiceById($attributes);
			$titles = ['main_head' => 'Tax Invoice','subhead' => 'Tax Invoice'];//echo '<pre>';print_r($result);exit;
			$itemdesc = $this->makeTreeArr($this->sales_invoice->getItemDesc($id));
			$words = $this->number_to_word($result['details']->net_total_fc);
			$vat_words = $this->number_to_word($result['details']->vat_amount_fc);
			$amount = $result['details']->net_total_fc;
			$vatamount = $result['details']->vat_amount_fc;
			$vtype = $this->accountsetting->checkVoucher( $result['details']['voucher_id'] );
		
			$view = 'print';
			$arr = explode('.',number_format($amount,2));
			if(sizeof($arr) >1 ) {
				if($arr[1]!=00) {
					$dec = $this->number_to_word($arr[1]);
					$words .= ' and Fils '.$dec.' Only';
				} else 
					$words .= ' Only';
			} else
				$words .= ' Only';
			
			$arrv = explode('.',number_format($vatamount,2));
			if(sizeof($arrv) >1 ) {
				if($arrv[1]!=00) {
					$dec = $this->number_to_word($arrv[1]);
					$vat_words .= ' and Fils '.$dec.' Only';
				} else 
					$vat_words .= ' Only';
			} else
				$vat_words .= ' Only';
			
			return view('body.salesinvoice.'.$view)
						->withDetails($result['details'])
						->withTitles($titles)
						->withAmtwords($words)
						->withVatamtwords($vat_words)
						->withFc($attributes['is_fc'])
						->withItemdesc($itemdesc)
						->withFormdata($this->formData)
						->withId($id)
						->withItems($result['items']);
		} else {
					
			$path = app_path() . '/stimulsoft/helper.php';
			$arr = explode('.',$viewfile->print_name);
			$viewname = $arr[0].'FC.mrt';
			
			return view('body.salesinvoice.viewer')->withPath($path)->withView($viewname);
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
					->withFc('')
					->withItems($result['items']);
		
	}
	
	public function getOrderHistory($customer_id)
	{
		$data = array();
		$items = $this->sales_invoice->getOrderHistory($customer_id);
		return view('body.salesinvoice.history')
					->withItems($items)
					->withData($data);
	}
	
	public function checkVchrNo(Request $request) {

		//$check = $this->sales_invoice->check_voucher_no($request->get('voucher_no'), $request->get('id'));
		$check = $this->sales_invoice->check_voucher_no($request->get('voucher_no'), $request->get('deptid'), $request->get('id'));
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
		$otbills = $this->sales_invoice->getOthrBills($customer_id,null,null); 
		$sreturn = $this->sales_invoice->getSRbills($customer_id); //JN23
		//echo '<pre>';print_r($sreturn);exit;
		return view('body.salesinvoice.custinvoiceset')
					->withNum($no)
					->withOpenbalances($openbalances)
					->withInvoices($invoices)
					->withAdvance($advance)
					->withOtbills($otbills)
					->withSrbills($sreturn); 
	}
	
	protected function makeTree($result)
	{
		$childs = array();
		foreach($result as $item)
			$childs[$item['amount_transfer']][] = $item;
		
		return $childs;
	}
	
	protected function OrderByVchr($result)
	{
		$childs = array();
		foreach($result as $item)
			$childs[$item['voucher_no']][] = $item;
		
		return $childs;
	}
	
	protected function makeTreeTC($result)
	{
		$childs = array();
		foreach($result as $item)
			$childs[$item->tax_code][] = $item;
		
		return $childs;
	}

	// protected function makeTree($result)
	// {
	// 	$childs = array();
	// 	foreach($result as $item)
	// 		$childs[$item->amount_transfer][] = $item;
		
	// 	return $childs;
	// }
	protected function groupbyItemwise($result)
	{
		$childs = array();
		foreach($result as $items)
			foreach($result as $item)
				$childs[$item->item_id][] = $item;
		
		return $childs;
	}
	protected function makeTreeVoucher($result)
	{
		$childs = array();
		foreach($result as $item)
		
			$childs[$item->voucher_no][] = $item;
		
		return $childs;
	}

	protected function sortByVoucher($result)
	{
	 	$childs = array();
		foreach($result as $item)
			$childs[$item['voucher_id']][] = $item;
		
		return $childs;
	}	
	
	protected function makeTreeSup($result)
	{
		$childs = array();
		foreach($result as $item)
		//echo '<pre>';print_r($$item); exit();
		$childs[$item->voucher_no][] = $item;
		
			
		return $childs;
	}	
	
	protected function sortbyCustomer($result)
	{
		$childs = array();
		foreach($result as $item)
			$childs[$item->account_id][] = $item;
		
		return $childs;
	}
		public function getJob($id)
	{
		$data = array();
	
			$data = DB::table('sales_invoice')->where('sales_invoice.customer_id',$id)
			                    ->join('jobmaster', 'jobmaster.id', '=', 'sales_invoice.job_id')
			                   ->where('sales_invoice.status',1)->where('sales_invoice.deleted_at','0000-00-00 00:00:00')
			                   ->select('jobmaster.id','jobmaster.code')->orderBy('jobmaster.id', 'DESC')->get();
			return $data;
		}
	
	public function getSearch(Request $request)
	{
		//echo '<pre>';print_r($request->all());exit;
		$data = array();
		$dname = '';
		
		$cusid = $itemid = '';
		$voucher_head  = '';
		
		//echo '<pre>';print_r($report);exit;
		if(Session::get('department')==1) {
			if($request->get('department_id')!='') {
				$rec = DB::table('department')->where('id', $request->get('department_id'))->select('name')->first();
				$dname = $rec->name;
			}
		}
		//echo '<pre>';print_r($reports);exit;
		if($request->get('search_type')=="summary")
		{
			$voucher_head = 'Sales Invoice Summary';
			$reports = $this->sales_invoice->getReportsalesinvo($request->all()); //echo '<pre>';print_r($reports);exit;
			//$reports = $this->makeTreeSup($report); 
			$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];
			if($request->get('supplier_id')!==null)
				$supid = implode(',', $request->get('supplier_id'));
			else
				$supid = '';
			}
		
		else if($request->get('search_type')=="detail") {
			$voucher_head = 'Sales Invoice Detail';
			$report = $this->sales_invoice->getReportsalesinvo($request->all());
		    $reports = $this->makeTreeSup($report);
			$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];
			//echo '<pre>';print_r($reports);exit;
		} else if($request->get('search_type')=="item") {
			$voucher_head = 'Sales Invoice by Itemwise';
			$report = $this->sales_invoice->getReportsales($request->all());
			$reports = $this->groupbyItemwise($report);
			$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];
			//echo '<pre>';print_r($reports);exit;
			if($request->get('item_id')!==null)
				$itemid = implode(',', $request->get('item_id'));
			else
				$itemid = '';
		
		
		} else if($request->get('search_type')=='customer') {
	//	
			$voucher_head = 'Sales Invoice by customerwise';
			$report = $this->sales_invoice->getReportsales($request->all());
		    $reports = $this->makeTreeSup($report);
			$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];
			if($request->get('supplier_id')!==null)
				$cusid = implode(',', $request->get('supplier_id'));
			else
				$cusid = '';
		} else if($request->get('search_type')=='summary_pmode') {
			$voucher_head = 'Summary with Payment Mode';
			$titles = ['main_head' => 'Sales Summary','subhead' => $voucher_head ];
			$reports = $this->sortByVoucher( $this->sales_invoice->getReportPmode($request->all()) ); 
		}
		
				
	//	echo '<pre>';print_r($report);exit;
		return view('body.salesinvoice.preprint')
					->withReports($reports)
					->withVoucherhead($voucher_head)
					->withType($request->get('search_type'))
					->withFromdate($request->get('date_from'))
					->withTodate($request->get('date_to'))
					->withI(0)
					->withCustomer($cusid)
					->withItem($itemid)
					->withTitles($titles)
					->withSalesman($request->get('salesman'))
					->withSettings($this->acsettings)
					->withDname($dname)
					->withData($data);
	}
	
public function dataExport(Request $request)
	{ //echo '<pre>';print_r($request->all());exit; type
		$data = array();
		$datareport[] = ['','',strtoupper(Session::get('company')),'','',''];
		$datareport[] = ['','','','','','',''];
		
		//$request->merge(['type' => 'export']);
		//$reports = $this->purchase_invoice->getReportExcel($request->all());
		
		if($request->get('search_type')=="summary")
		{
			$voucher_head = 'Sales Invoice Summary';
			$report = $this->sales_invoice->getReportsalesinvo($request->all());
			$reports = $this->makeTreeSup($report);
			//echo '<pre>';print_r($report);exit;
			$datareport[] = ['','',strtoupper($voucher_head), '','',''];
		     $datareport[] = ['','','','','','',''];
		
			$datareport[] = [ 'SI#','PI.No','PI.Date','Customer','Gross Amt.','Discount','Net Sale','VAT Amt.','Net Total'];
			$nettotal=$netdiscount=$netvat_amount=$net_amount_total=$i=0;
			//foreach($reports as $key => $report) {
				$total=$discount=$vat=$gross=$sale=0;
				$tot=$dis=$gs=$vt=$ns=0;
				foreach ($report as $row) {
				$i++;		
				
						
				$datareport[] = [ 'si' => $i,
				                'po' => $row->voucher_no,
				               'vchr_dt' => date('d-m-Y',strtotime($row->voucher_date)),
								 'supplier' => $row->master_name,
								 'gross' => number_format(($row->disc > 0)?($row->subtotal+$row->disc):$row->subtotal,2),
								  'dis' => number_format($row->disc,2),
								   'sale' => number_format($row->subtotal,2),
								  'vat' => number_format($row->vat_total,2),
								  'total' => number_format($row->net_total,2)
								];
								$total+= $row->net_total;
							    $tot=number_format($total,2) ;
								$discount+=$row->disc;
								$dis=number_format($discount,2) ;
								$gross+= ($row->disc > 0)?($row->subtotal+$row->disc):$row->subtotal;
							    $gs=number_format($gross,2) ;
								$vat+= $row->vat_total;
							     $vt=number_format($vat,2) ;
							     $sale+= $row->subtotal;
							     $ns=number_format($sale,2) ;
				
			}
			$datareport[] = ['','','','','','',''];		
			$datareport[] = ['','','','Total',$gs,$dis,$ns,$vt,$tot];
		}
		elseif($request->get('search_type')=="detail") {
			$voucher_head = 'Sales Invoice Detail';
			$report = $this->sales_invoice->getReportsalesinvo($request->all());
		    $reports = $this->makeTreeSup($report);
				$datareport[] = ['','','','',strtoupper($voucher_head), '','',''];
		     $datareport[] = ['','','','','','',''];
		//echo '<pre>';print_r($reports);exit;
			
			$qty_total = $net_total = $vat_total = $gross_total=$discount =$gtotal= $i = 0;
			foreach ($reports as $report) {
					$i++;
					$datareport[] = ['SI.No.:'.$i,'SI#:'.$report[0]->voucher_no,'Vchr.Date:'.date('d-m-Y', strtotime($report[0]->voucher_date)),'Customer:'.$report[0]->master_name,'Salesman:'.$report[0]->salesman];
					$datareport[] =['Item Code','Description','SI.Qty.','Rate','Total Amt.','VAT Amt.','Net Total'];
			foreach ($report as $row) {	
			    $qty_total += $row->quantity;
			    if($row->tax_include==0){
				$net_amount = ($row->vat_amount + $row->line_total)-$row->discount;
				$gtotal=$row->line_total-$discount;
				}else{
				$net_amount = ( $row->line_total)-$row->discount;
				$gtotal=$row->line_total-$discount-$row->vat_amount;
				 }
				$vat_total += $row->vat_amount;
				$net_total += $net_amount;
					$datareport[] = [ 
									  
									  'code' => $row->item_code,
									 'ref' => $row->description,
									  'qty' => $row->quantity,
									  'unit' => number_format($row->unit_price,2),
									  'gross' => number_format($gtotal,2),
									 'vat' => number_format($row->vat_amount,2),
									  'total' => number_format($net_amount,2)
									];
								
			}
			$gross_total +=$report[0]->subtotal ; 
			
			}
			$datareport[] = ['','','','','','',''];
			$datareport[] = ['','Total',$qty_total,'',number_format($gross_total,2),number_format($vat_total,2),number_format($net_total,2)];
			//$reports = $this->makeTree($reports);
			
		} else if($request->get('search_type')=="summary_pmode") {
			
			$voucher_head = 'Summary with Payment Mode';
			$datareport[] = ['','','','',strtoupper($voucher_head), '','',''];
		    $datareport[] = ['','','','','','',''];
			$reports = $this->sortByVoucher( $this->sales_invoice->getReportPmode($request->all()) ); 
			$datareport[] = ['SI#','Vchr.Date','Customer','TRN No.','Salesman','Gross Amt.','Discount','VAT Amt.','Net Total'];
			
			foreach($reports as $report) {
				$nettotal=$netdiscount=$netvat_amount=$net_amount_total=0;
				$datareport[] = [$report[0]->voucher_name,''];
				foreach($report as $row) {
					$datareport[] = [ 'si' => $row->voucher_no,
									  'vdate' => date('d-m-Y',strtotime($row->voucher_date)),
									  'customer' => $row->master_name,
									  'vat_no' => $row->vat_no,
									  'salesman' => $row->salesman,
									  'gross' => ($row->discount > 0)?($row->subtotal+$row->discount):$row->subtotal,
									  'discount' => $row->discount,
									  'vat' => $row->vat_amount,
									  'total' => $row->net_total
									];
									
					$nettotal += ($row->discount > 0)?($row->subtotal+$row->discount):$row->subtotal;
					$netdiscount += $row->discount;
					$netvat_amount += $row->vat_amount;
					$net_amount_total += $row->net_total;
				}
				$datareport[] = ['','','','','Total',number_format($nettotal,2),number_format($netdiscount,2),number_format($netvat_amount,2),number_format($net_amount_total,2)];
			}
		}
	
	//	echo $voucher_head.'<pre>';print_r($datareport);exit;
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
	
	
	
	public function getItems()
	{
		$data = array();
	
		$item = $this->itemmaster->activeItemmasterList();
				
		return view('body.salesinvoice.multiselect')
		               // ->withCategory($category)
		               //->withSubcategory($subcategory)
		               // ->withGroup($group)
		               // ->withSubgroup($subgroup)
					->withItem($item)
					->withType('ITEM')
					->withData($data);

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
	
	private function str_replace_last( $search , $replace , $str ) {
		if( ( $pos = strrpos( $str , $search ) ) !== false ) {
			$search_length  = strlen( $search );
			$str    = substr_replace( $str , $replace , $pos , $search_length );
		}
		return $str;
	}
	
	public function getTrnno($name) {

		$res = $this->sales_invoice->get_trnno( $name );
		if($res)
			return array('customer_trn' => $res->customer_trn, 'customer_phone' => $res->customer_phone);
		else
			return null;
	}
	
	public function getCustHistory($customer)
	{
		$data = array();
		$items = $this->sales_invoice->getCustHistory($customer);//echo '<pre>';print_r($items);exit;
		return view('body.salesinvoice.history')
					->withItems($items)
					->withData($data);
	}

	public function getHistory($id)
	{
		$data = array();
		$items = $this->sales_invoice->getHistory($id);//echo '<pre>';print_r($items);exit;
		return view('body.salesinvoice.history')
					->withItems($items)
					->withData($data);
	}
	
	
	public function getAjaxCust(Request $request) {
		
		$search = $request->get('term','');
		$customers = $this->sales_invoice->getCustomerSearch($search);
		if($customers) {
			$data=array();
			foreach ($customers as $row) {
				$data[]=array('value'=>$row->customer, 'trnno'=>$row->trnno, 'phone'=>$row->phone);
			}
			if(count($data))
				 return $data;
			else
				return ['value'=>'No Result Found','id'=>''];
		} else 
			return ['value'=>'No Result Found','id'=>''];
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
	
	public function getCustHistoryPhone($phone)
	{
		$data = array();
		$items = $this->sales_invoice->getCustHistoryPhone($phone);//echo '<pre>';print_r($items);exit;
		return view('body.salesinvoice.history')
					->withItems($items)
					->withData($data);
	}
	
	public function dataExportPo(Request $request)
	{
		$data = array();
		
		$attributes['document_id'] = $request->get('id');
		$attributes['is_fc'] = $request->get('fc');
		$result = $this->sales_invoice->getInvoiceById($attributes);
		
		$voucher_head = 'SALES INVOICE';
		
		 //echo '<pre>';print_r($result);exit;
		
		$datareport[] = ['','', 'SALES INVOICE', '','','',''];	
		$details = $result['details'];
		$supname = ($details->supplier=='CASH CUSTOMER' || $details->supplier=='CASH CUSTOMERS' || $details->supplier=='Cash Customers' || $details->supplier=='Cash Customer')?'CASH CUSTOMER:'.$details->cash_customer:$details->supplier;
		$vat_no = ($details->vat_no=='')?$details->customer_trn:$details->vat_no;
		
		$datareport[] = ['Customer No:',$details->account_id, '', '','','','SI.No:',$details->voucher_no];
		$datareport[] = ['Customer Name:',$supname, '', '','','','Date:',date('d-m-Y',strtotime($details->voucher_date))];
		$datareport[] = ['Address:',$details->address, '', '','','','LPO No:',$details->lpo_no];
		$datareport[] = ['Telephone No:',$details->phone, '', '','','','Sales Person:',$details->salesman];
		$datareport[] = ['Customer TRN:',$vat_no, '', '','','','Payment Terms:',$details->terms];
		$datareport[] = ['','', '', '','','','',''];
		
		$datareport[] = ['Si.#.','Item Code', 'Description', 'Unit','Quantity','Unit Price','VAT','Total'];
		
		$i=0;
		foreach ($result['items'] as $row) {
				$i++;
				
				if($attributes['is_fc']==1) {
					$unit_price = $row->unit_price / $details->currency_rate;
					$vat_amount = $row->vat_amount / $details->currency_rate;
					$total_price = $row->line_total / $details->currency_rate;
				} else {
					$unit_price = $row->unit_price;
					$vat_amount = $row->vat_amount;
					$total_price = $row->line_total;
					
					if($details->discount!=0) {
						$total_price = ($item->unit_price * $item->quantity) + $item->vat_amount;
					}
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
			$net_amount = $details->net_total / $details->currency_rate;
		} else {
			$total = $details->total;
			$vat_amount_net = $details->vat_amount;
			$net_amount = $details->net_total;
		}
		$cur = ($attributes['is_fc']==1)?' ('.$details->currency.')':':';
		$datareport[] = ['','', '', '','','','',''];									
		$datareport[] = ['','', 'Gross Total'.$cur, '','','','',number_format($total,2)];
		$datareport[] = ['','', 'Vat Total'.$cur, '','','','',number_format($vat_amount_net,2)];
		$datareport[] = ['','', 'Total Inclusive VAT'.$cur, '','','','',number_format($net_amount,2)];
		$datareport[] = ['Amount in words:',$request->get('amtwrds'), '', '','','','',''];
			
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
	
	public function getvehicleHistory($vehicle_id)
	{
	//	echo '<pre>';print_r($vehicle_id);exit;
		$data = array();
		$items = $this->sales_invoice->getVehHistory($vehicle_id);//echo '<pre>';print_r($items);exit;
		return view('body.salesinvoice.vehiclehistory')
					->withItems($items)
					->withData($data);
	}
	
	public function getItemDetails($id) 
	{
		$data = array();
		$items = $this->sales_invoice->getItems(array($id));
		return view('body.salesinvoice.itemdetails')
					->withItems($items)
					->withData($data);
	}
	
	public function getCustomerDpt($deptid=null)
	{
		$data = array();
		$customers = $this->accountmaster->getCustomerList($deptid);
		$country = $this->country->activeCountryList();
		$area = $this->area->activeAreaList();
		$cus_code = json_decode($this->ajax_getcode($category='CUSTOMER'));
		return view('body.salesinvoice.customer')
					->withCustomers($customers)
					->withArea($area)
					->withCusid($cus_code->code)
					->withFormdata($this->formData)
					->withCategory($cus_code->category)
					->withCountry($country)
					->withNum('')
					->withData($data);
	}
	
	//JUL7...
	public function editTransfer($sid, $doctype, $id) { 

		$data = array();
		$itemmaster = $this->itemmaster->activeItemmasterList();
		$terms = $this->terms->activeTermsList();
		$jobs = $this->jobmaster->activeJobmasterList();
		$currency = $this->currency->activeCurrencyList();
		$orderrow = $this->sales_invoice->findPOdata($sid);
		$orditems = $this->sales_invoice->getItems($sid); //
		//echo '<pre>';print_r($orderrow);exit;
		$itemdesc = $this->makeTreeArr($this->sales_invoice->getItemDesc($sid));
		$custdata = DB::table('account_master')->where('id',$orderrow->customer_id)->select('cl_balance','credit_limit','pdc_amount')->first();
		$location = $this->location->locationList();
		$getItemLocation = $this->itemmaster->getItemLocation($sid,'SI');
		$itemlocedit = $this->makeTreeArrLoc( $this->itemmaster->getItemLocEdit($sid,'SI') );
		$vouchers = $this->accountsetting->find($orderrow->voucher_id); //echo '<pre>';print_r($vouchers);exit;
		
		$cngetItemLocation = $this->itemmaster->getcnItemLocations();
		$cnitemlocedit = $this->makeTreeArrLoc( $this->itemmaster->getcnItemLocEdit($sid,'SI') );
		
		
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
							->where('receipt_voucher_entry.entry_type','Dr')
							->select('receipt_voucher_entry.*','account_master.master_name','receipt_voucher.voucher_type','receipt_voucher.id AS rvid')
							->first(); //echo '<pre>';print_r($rventry);exit;
		}
		
		$apr = ($this->acsettings->doc_approve==1)?[1]:[0,1,2];
		if(in_array($orderrow->doc_status, $apr))
			$isprint = true;
		else
			$isprint = null;
		
		$print = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','SI')
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
		
		
		if($id) {
			$ids = explode(',', $id); $getItemLocation = $itemlocedit = $cnitemlocedit = [];
			$docnos = '';
			if($doctype=='QS') {
				$docRow = $this->quotation_sales->findQuoteData($ids[0]);
				$docItems = $this->quotation_sales->getQSItems($ids);
				$itemdesc = $this->makeTreeArr($this->quotation_sales->getItemDesc($ids));
				
				//AUG24
				$resdo = DB::table('quotation_sales')->whereIn('id',$ids)->select('voucher_no')->get();
				foreach($resdo as $rw) {
					$docnos = ($docnos=='')?$rw->voucher_no:$docnos.','.$rw->voucher_no;
				}
			} else if($doctype=='SO') {
				$docRow = $this->sales_order->findOrderData($ids[0]);
				$docItems = $this->sales_order->getSOItems($ids);
				$itemdesc = $this->makeTreeArr($this->sales_order->getItemDesc($ids));
				
				//AUG24
				$resdo = DB::table('sales_order')->whereIn('id',$ids)->select('voucher_no')->get();
				foreach($resdo as $rw) {
					$docnos = ($docnos=='')?$rw->voucher_no:$docnos.','.$rw->voucher_no;
				}
			} else if($doctype=='CDO') {
				$docRow = $this->customerdo->findOrderData($ids[0]);
				$docItems = $this->customerdo->getDOItems($ids);
				$itemdesc = $this->makeTreeArr($this->customerdo->getItemDesc($ids));
				$getItemLocation = $this->itemmaster->getItemLocation($id,'SI');
				$itemlocedit = $this->makeTreeArrLoc( $this->itemmaster->getItemLocEdit($id,'SI') );
				
				//AUG24
				$resdo = DB::table('customer_do')->whereIn('id',$ids)->select('voucher_no')->get();
				foreach($resdo as $rw) {
					$docnos = ($docnos=='')?$rw->voucher_no:$docnos.','.$rw->voucher_no;
				}
			}
			//echo '<pre>';print_r($vouchers);exit;
			$total = 0; $discount = 0; $nettotal = 0;
			foreach($docItems as $item) {
				$total += $item->line_total;
				$discount += $item->discount;
			}
			$nettotal = $total - $discount;
			
			
				/* ->withVoucherid(Session::get('voucher_id'))
				->withVoucherno(Session::get('voucher_no'))
				->withReferenceno(Session::get('reference_no'))
				->withVoucherdt(Session::get('voucher_date'))
				->withLpodt(Session::get('lpo_date'))
				->withAccountmstr(Session::get('acnt_master'))
				->withSalesac(Session::get('sales_acnt'))
				->withDptid(Session::get('dpt_id')) */
						
		}
		//echo '<pre>';print_r($docItems); print_r($orditems);exit;
		return view('body.salesinvoice.edittransfer') //edit  editsp
					->withItems($itemmaster)
					->withTerms($terms)
					->withJobs($jobs)
					->withTotal($total)
					->withDocid($sid)
					->withDocrow($docRow)
					->withNettotal($nettotal)
					->withDiscount($discount)
					->withDocitems($docItems)
					->withCurrency($currency)
					->withOrderrow($orderrow)
					->withOrditems($orditems)
					->withItemdesc($itemdesc)
					->withVatdata($this->vatdata)
					->withCustdata($custdata)
					->withSettings($this->acsettings)
					->withLocation($location)
					->withItemloc($getItemLocation)
					->withItemlocedit($itemlocedit)
					->withFormdata($this->formData)
					->withVouchers($vouchers)
					->withBanks($banks)
					->withRvvoucher($vchrdata)
					->withRvid($rvid=9)
					->withRventry($rventry)
					->withIsprint($isprint)
					->withPrint($print)
					->withIsdept($is_dept)
					->withDepartments($departments)
					->withDeptid($deptid)
					->withRoundoff($round_off)
					->withCnitemloc($cngetItemLocation)
					->withCnitemlocedit($cnitemlocedit)
					->withIsconloc(($this->mod_con_loc->is_active==1)?true:false)
					->withDoctype($doctype)
					->withDocnos($docnos)
					->withData($data);

	}
	
	public function getBilldata($id) {
		
		$qry1 = DB::table('sales_invoice')->where('sales_invoice.id',$id)
						->Join('account_transaction AS AT', function($join) {
							$join->on('AT.voucher_type_id','=','sales_invoice.id');
							$join->on('AT.account_master_id','=','sales_invoice.customer_id');
							$join->where('AT.voucher_type','=','SI');
							$join->where('AT.transaction_type','=','Dr');
							$join->where('AT.status','=',1);
							$join->where('AT.deleted_at','=','0000-00-00 00:00:00');
						})
						->select('AT.*');
						
		$qry2 = DB::table('sales_invoice')->where('sales_invoice.id',$id)
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
					
		$result = $qry1->union($qry2)->get();
					
		//echo '<pre>';print_r($result);exit;		
						
		return view('body.salesinvoice.billdata')->withResult($result)->withId($id);
	}
	
	public function uploadSubmit(Request $request)
	{	
		$res = $this->sales_invoice->ajax_upload($request->photos);
		return response()->json(array('file_name' => $res), 200);
	}
	
	public function photoView($id) { 

		$photos = DB::table('si_photos')->where('invoice_id',$id)->get(); 
		$orderrow = $this->sales_invoice->findPOdata($id);
	//echo '<pre>';print_r($orderrow);exit;
		return view('body.salesinvoice.photoview') 
					->withOrderrow($orderrow)
					->withPhotos($photos);
					

	}
	
	public function refreshDO($siId) { //SI id
	    
		//GETTING SALES INVOICE ITEMS WITH TOTAL QUANTITY TRANSFERED...
		$itms = DB::table('sales_invoice')->where('sales_invoice.id', $siId)
					->join('sales_invoice_item','sales_invoice_item.sales_invoice_id','=','sales_invoice.id')
					->select('sales_invoice_item.item_id', 'sales_invoice_item.quantity','sales_invoice_item.doc_row_id','sales_invoice.document_id','sales_invoice.document_type')
					->get();
//echo '<pre>';print_r($itms);
		if($itms) {
			foreach($itms as $row) {
			    
			    //$id = $row->document_id;
			    if($row->document_type=='CDO' && $row->doc_row_id != 0) {
			        
    			    $doRow = DB::table('customer_do_item')->where('id',$row->doc_row_id)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','quantity','balance_quantity')->first();
    			    //echo '<pre>';print_r($doRow);
    			    if($doRow) {
    			        
    			        if($row->quantity==$doRow->quantity) {
    			            DB::table('customer_do_item')
    								->where('id',$doRow->id)
    								->update(['is_transfer' => 0, 'balance_quantity' => 0]);
    			        } else {
    			            
    			            $balqty = ($doRow->balance_quantity > $row->quantity)?($doRow->balance_quantity - $row->quantity):($row->quantity - $doRow->balance_quantity);
    						
    					    //CHECKE ANY MORE SI IS CREATED AGAINST THIS DO IEM....
    					    $siRow = DB::table('sales_invoice_item')->where('doc_row_id',$doRow->id)->where('sales_invoice_id','!=',$siId)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->count();
    					    if($siRow==0) {
    					        DB::table('customer_do_item')->where('id',$doRow->id)
    									->update(['is_transfer' => 0, 'balance_quantity' => 0]);
    					    } else {
    					        DB::table('customer_do_item')->where('id',$doRow->id)
    									->update(['is_transfer' => 2, 'balance_quantity' => $balqty]);
    					    }
    			        }
    			        
    			    }
			    }

			}
            
            $doIdArr = explode(',', $itms[0]->document_id);

            foreach($doIdArr as $id) {
    			$row1 = DB::table('customer_do_item')->where('customer_do_id', $id)->count();
    			$row2 = DB::table('customer_do_item')->where('customer_do_id', $id)->where('is_transfer',1)->count();
    			$row3 = DB::table('customer_do_item')->where('customer_do_id', $id)->where('is_transfer',2)->count();
    			if($row1==$row2) {
    				DB::table('customer_do')
    						->where('id', $id)
    						->update(['is_editable' => 1, 'is_transfer' => 1]);
    			} else if($row1 > 0 && $row2==0 && $row3==0) {
    			    DB::table('customer_do')
    						->where('id', $id)
    						->update(['is_editable' => 0, 'is_transfer' => 0]);
    			} else if($row3 > 0) {
    			    DB::table('customer_do')
    						->where('id', $id)
    						->update(['is_editable' => 1, 'is_transfer' => 0]);
    			}
            }
		}
			
	//return true;
	}

	
}



