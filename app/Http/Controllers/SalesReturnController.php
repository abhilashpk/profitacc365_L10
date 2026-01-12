<?php

namespace App\Http\Controllers;
use App\Repositories\Itemmaster\ItemmasterInterface;
use App\Repositories\Jobmaster\JobmasterInterface;
use App\Repositories\AccountMaster\AccountMasterInterface;
use App\Repositories\Currency\CurrencyInterface;
use App\Repositories\VoucherNo\VoucherNoInterface;
use App\Repositories\SalesOrder\SalesOrderInterface;
use App\Repositories\SalesInvoice\SalesInvoiceInterface;
use App\Repositories\SalesReturn\SalesReturnInterface;
use App\Repositories\AccountSetting\AccountSettingInterface;
use App\Repositories\Salesman\SalesmanInterface;
use App\Repositories\Location\LocationInterface;
use App\Repositories\Forms\FormsInterface;
use App\Repositories\UpdateUtility;

use Illuminate\Http\Request;

use App\Http\Requests;
use Session;
use Response;
use Excel;
use App;
use DB;
use Auth;
use Mail;
use PDF;

class SalesReturnController extends Controller
{

	protected $itemmaster;
	protected $jobmaster;
	protected $accountmaster;
	protected $currency;
	protected $voucherno;
	protected $salesman;
	protected $purchase_order;
	protected $sales_invoice;
	protected $sales_return;
	protected $accountsetting;
	protected $cost_accounts;
	protected $location;
	protected $forms;
	protected $formData;
	protected $mod_autocost;
	
	public function __construct(SalesReturnInterface $sales_return, SalesInvoiceInterface $sales_invoice, SalesOrderInterface $purchase_order, ItemmasterInterface $itemmaster, JobmasterInterface $jobmaster, AccountMasterInterface $accountmaster, CurrencyInterface $currency, VoucherNoInterface $voucherno, AccountSettingInterface $accountsetting,SalesmanInterface $salesman,LocationInterface $location,FormsInterface $forms) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		
		$this->middleware('auth');
		$this->itemmaster = $itemmaster;
		$this->jobmaster = $jobmaster;
		$this->accountmaster = $accountmaster;
		$this->currency = $currency;
		$this->voucherno = $voucherno;
		$this->salesman = $salesman;
		$this->purchase_order = $purchase_order;
		$this->sales_invoice = $sales_invoice;
		$this->sales_return = $sales_return;
		$this->accountsetting = $accountsetting;
		$this->location = $location;
		$this->forms = $forms;
		$this->formData = $this->forms->getFormData('SR');
		
		if(Session::get('cost_accounting')==1) {
			$this->cost_accounts = $this->accountsetting->getCostAccounts();
			Session::put('stock', $this->cost_accounts['stock']);
			Session::put('cost_of_sale', $this->cost_accounts['cost_of_sale']);
		}
		
		$this->mod_autocost = DB::table('parameter2')->where('keyname', 'mod_autocost_refresh')->where('status',1)->select('is_active')->first();
		$this->mod_si_roundoff = DB::table('parameter2')->where('keyname', 'mod_si_roundoff')->where('status',1)->select('is_active')->first();
		$this->mod_mpqty = DB::table('parameter2')->where('keyname', 'mod_mp_qty')->where('status',1)->select('is_active')->first();
		$this->objUtility = new UpdateUtility();

		$this->mod_con_loc = DB::table('parameter2')->where('keyname', 'mod_con_location')->where('status',1)->select('is_active')->first();
		$this->mod_sr_with_cat = DB::table('parameter2')->where('keyname', 'mod_sr_with_cat')->where('status',1)->select('is_active')->first();
		
	}
	
    public function index() {
		
		$data = array();
		$orders = [];//$this->sales_return->salesReturnList();//echo '<pre>';print_r($orders);exit;
		$salesmans = $this->salesman->getSalesmanList();
		
		$customer =[];
		
		$item = [];//	$this->itemmaster->activeItemmasterList();
		$group=[];//$this->itemmaster->activeGroupList();
		$subgroup=[];
		$category=[];
		$subcategory =[];
		$item = DB::table('itemmaster')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		$cus =DB::table('account_master')->where('category','CUSTOMER')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')
		->select('id','master_name')->get(); 
		$category = DB::table('category')->where('parent_id',0)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		$subcategory = DB::table('category')->where('parent_id',1)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		$group = DB::table('groupcat')->where('parent_id',0)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		$subgroup = DB::table('groupcat')->where('parent_id',1)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		if(Session::get('department')==1) {
			$departments = DB::table('department')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
			$is_dept = true;
		} else {
			$departments = []; $is_dept = false;
		}
		$jobs = $this->jobmaster->activeJobmasterList();
		return view('body.salesreturn.index')
					->withOrders($orders)
					->withCategory($category)
		            ->withSubcategory($subcategory)
		            ->withGroup($group)
					->withCus($cus)
		            ->withSubgroup($subgroup)
	                ->withItem($item)
					->withDepartments($departments)
					->withCustomer($customer)
					->withType('')
					->withSalesman($salesmans)
					->withJobs($jobs)
					->withSettings($this->acsettings)
					->withData($data);
	}
	
	public function ajaxPaging(Request $request)
	{
		$columns = array( 
                            0 =>'sales_return.id', 
                            1 =>'voucher_no',
                            2=> 'voucher_date',
                            3=> 'customer',
                            4=> 'net_amount'
                        );
						
		$totalData = $this->sales_return->salesReturnListCount();
            
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = 'sales_return.id';//$columns[$request->input('order.0.column')];
        $dir = 'desc';//$request->input('order.0.dir');
		$search = (empty($request->input('search.value')))?null:$request->input('search.value');
        
		$invoices = $this->sales_return->salesReturnList('get', $start, $limit, $order, $dir, $search);
		
		if($search)
			$totalFiltered =  $this->sales_return->salesReturnList('count', $start, $limit, $order, $dir, $search);
		
		$prints = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','SR')
							->select('report_view_detail.name','report_view_detail.id')
							->get();
							
        $data = array();
        if(!empty($invoices))
        {
           
			foreach ($invoices as $row)
            {
                $edit =  '"'.url('sales_return/edit/'.$row->id).'"';
                $delete =  'funDelete("'.$row->id.'")';
				$print = url('sales_return/print/'.$row->id);
				$viewonly =  url('sales_return/viewonly/'.$row->id);
				$opts = '';					
				foreach($prints as $doc) {
					$opts .= "<li role='presentation'><a href='{$print}/".$doc->id."' target='_blank' role='menuitem'>".$doc->name."</a></li>";
				}
				
                $nestedData['id'] = $row->id;
                $nestedData['voucher_no'] = $row->voucher_no;
				$nestedData['voucher_date'] = date('d-m-Y', strtotime($row->voucher_date));
				$nestedData['customer'] = ($row->customer=='CASH CUSTOMERS') ? (($row->customer_name!='')?$row->customer.'('.$row->customer_name.')':$row->customer) : $row->customer;
				$nestedData['net_amount'] = $row->net_amount;
                $nestedData['edit'] = "<p><button class='btn btn-primary btn-xs' onClick='location.href={$edit}'>
												<span class='glyphicon glyphicon-pencil'></span></button></p>";
				$nestedData['viewonly'] = "<p><a href='{$viewonly}' class='btn btn-info btn-xs' target='_blank'><i class='glyphicon glyphicon-eye-open'></i></a></p>";
																
				
				$nestedData['delete'] = "<button class='btn btn-danger btn-xs delete' onClick='{$delete}'>
												<span class='glyphicon glyphicon-trash'></span>";
												
				//$nestedData['print'] = "<p><a href='{$print}' class='btn btn-primary btn-xs'><span class='fa fa-fw fa-print'></span></a></p>";
				$nestedData['print'] = "<div class='btn-group drop_btn' role='group'>
											<button type='button' class='btn btn-primary btn-xs dropdown-toggle m-r-50'
													id='exampleIconDropdown1' data-toggle='dropdown' aria-expanded='false'>
												<i class='fa fa-fw fa-print' aria-hidden='true'></i><span class='caret'></span>
											</button>
											<ul style='min-width:100px !important;' class='dropdown-menu' aria-labelledby='exampleIconDropdown1' role='menu'>
												".$opts."
											</ul>
										</div>";
											
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
	
	public function add($id=null) {

		$data = array();
		$itemmaster = $this->itemmaster->activeItemmasterList();
		$jobs = $this->jobmaster->activeJobmasterList();
		$currency = $this->currency->activeCurrencyList();
		$vouchers = $this->accountsetting->getAccountSettingsSR($vid=4); //echo '<pre>';print_r($vouchers);exit;
		$location = $this->location->locationList();
		$lastid = DB::table('sales_return')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->orderBy('id','DESC')->select('id')->first();
		$footertxt = DB::table('header_footer')->where('doc','SR')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->first();
		$print = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','SR')
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
			
			if($this->mod_si_roundoff->is_active==1)
				$round_off = true;
			else
				$round_off = false;
			
			$itemcat = null;	
			if($this->mod_sr_with_cat->is_active==1) {
				
			    $itemcat = DB::table('category')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
			    $view = 'addsi-batch';
			} else
			    $view = 'addsi';
			
			
		
		if($id) {
			$itemmaster = $this->itemmaster->activeItemmasterList();
			$jobs = $this->jobmaster->activeJobmasterList();
			$currency = $this->currency->activeCurrencyList();
			$invoicerow = $this->sales_invoice->findInvoiceData($id);
			$items = $this->sales_invoice->getInvoiceItems($id);
			
			$getItemLocation = $this->itemmaster->getItemLocation($id,'SI');
			$itemlocedit = $this->makeTreeArrLoc( $this->itemmaster->getItemLocEdit($id,'SI') );
			
			$cngetItemLocation = $this->itemmaster->getcnItemLocations();
			$cnitemlocedit = $this->makeTreeArrLoc( $this->itemmaster->getcnItemLocEdit($id,'SI') );

			$total = 0; $discount = 0; $nettotal = 0; $vat_total = 0;
			foreach($items as $item) {
				$total += $item->line_total;
				$discount += $item->discount;
				$vat_total += $item->vat_amount;
			}
			$nettotal = $total - $discount + $vat_total;
			
			
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
			return view('body.salesreturn.'.$view) //addsi  addsi-batch
						->withItems($itemmaster)
						->withJobs($jobs)
						->withCurrency($currency)
						->withOrderrow($invoicerow)
						->withPitems($items)
						->withPordid($id)
						->withTotal($total)
						->withDiscount($discount)
						->withNettotal($nettotal)
						->withVouchers($vouchers)
						->withVattotal($vat_total)
						->withVoucherid(Session::get('sl_voucher_id'))
						->withVoucherno(Session::get('sl_voucher_no'))
						->withAcmaster(Session::get('sl_ac_master'))
						->withStockac(Session::get('sl_stock_ac'))
						->withVatdata($this->vatdata)
						->withSettings($this->acsettings)
						->withLocation($location)
						->withItemloc($getItemLocation)
						->withItemlocedit($itemlocedit)
						->withFormdata($this->formData)
						->withIsdept($is_dept)
						->withDepartments($departments)
						->withDeptid($deptid)
						->withIsconloc(($this->mod_con_loc->is_active==1)?true:false)
						->withCnitemloc($cngetItemLocation)
						->withCnitemlocedit($cnitemlocedit)
						->withIsmpqty($this->mod_mpqty->is_active)
						->withBatchitems($batch_items) //MAY25
						->withItemcat($itemcat)
						->withData($data);
		}
		return view('body.salesreturn.add')
					->withItems($itemmaster)
					->withJobs($jobs)
					->withCurrency($currency)
					->withVouchers($vouchers)
					->withVatdata($this->vatdata)
					->withSettings($this->acsettings)
					->withLocation($location)
					->withPrintid($lastid)
					->withFormdata($this->formData)
					->withPrint($print)
					->withIsdept($is_dept)
					->withDepartments($departments)
					->withDeptid($deptid)
					->withRoundoff($round_off)
					->withIsconloc(($this->mod_con_loc->is_active==1)?true:false)
					->withIsmpqty($this->mod_mpqty->is_active)
					->withFooter(isset($footertxt)?$footertxt->description:'')
					->withItemcat($itemcat)
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
	protected function groupbyItemwise($result)
	{
		$childs = array();
		foreach($result as $items)
			foreach($result as $item)
				$childs[$item->item_id][] = $item;
		
		return $childs;
	}
	// protected function makeTree($result)
	// {
	// 	$childs = array();
	// 	foreach($result as $item)
	// 		$childs[$item->amount_transfer][] = $item;
		
	// 	return $childs;
	// }
	protected function makeTreeVoucher($result)
	{
		$childs = array();
		foreach($result as $item)
		//$childs[$item['voucher_name']][] = $item;
			$childs[$item->voucher_no][] = $item;
		
		return $childs;
	}
	//
	public function save(Request $request) {
		
		//echo '<pre>';print_r($request->all());exit;
		
		
		
		$this->validate(
			$request, 
			['sales_invoice_id' => 'required',
			 //'reference_no' => ($this->formData['reference_no']==1)?'required':'nullable', 
			 //'customer_name' => 'required','customer_id' => 'required',
			 'item_code.*'  => 'required', 'item_id.*' => 'required',
			 'unit_id.*' => 'required',
			 'quantity.*' => 'required',
			 'cost.*' => 'required'
			],
			['sales_invoice_id' => 'Sales Invoice no. is required.',
			 //'reference_no' => 'Reference no. is required.',
			 //'customer_name.required' => 'Customer Name is required.','customer_id.required' => 'Customer name is invalid.',
			 'item_code.*.required'   => 'Item code is required.', 'item_id.*' => 'Item code is invalid.',
			 'unit_id.*' => 'Item unit is required.',
			 'quantity.*' => 'Item quantity is required.',
			 'cost.*' => 'Item cost is required.'
			]
		);
		
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
		$attributes	= $request->all();
		
				
		if($this->sales_return->create($attributes)) {
			//AUTO COST REFRESH CHECK ENABLE OR NOT
			if($this->mod_autocost->is_active==1) {
				$this->objUtility->reEvalItemCostQuantity($request->get('item_id'),$this->acsettings);
			}

			#### mail
			if(isset($attributes['send_email']) && $attributes['send_email']==1) {
				$vid=$attributes['voucher_no'];
				$amount=$attributes['net_amount'];
				$data['words'] = $this->number_to_word($amount);
				$data['salesitems'] = DB::table('sales_return')
				->where('sales_return.voucher_no',$vid)
				->join('sales_return_item AS SRI', function($join) {
					$join->on('SRI.sales_return_id','=','sales_return.id');
						})
				->leftjoin('users', function($join) {
					$join->on('users.id','=','sales_return.created_by');
							})				 
				->join('itemmaster AS IM', function($join) {
							$join->on('IM.id','=','SRI.item_id');
							})
				->join('units AS U', function($join) {
						$join->on('U.id','=','SRI.unit_id');
						})
					
				->where('SRI.status', 1)
				->where('SRI.deleted_at', '0000-00-00 00:00:00')		 
				->select('sales_return.voucher_no','sales_return.total','sales_return.vat_amount','sales_return.net_amount','sales_return.created_at','SRI.*','IM.item_code','U.unit_name','users.name')
				->orderBY('SRI.id','ASC')->get();
		
				   //echo '<pre>';print_r($data['salesitems']);exit;
				   //$pdfnew = PDF::loadView('body.salesinvoice.pdfupdateprintnw',$data);
				   //return view('body.salesinvoice.pdfupdateprintnw')->withSalesitems($data['salesitems'])->withWords($data['words']);

				   $email='numaktech@gmail.com';
				   $no=$data['salesitems'][0]->voucher_no;
				   $body='Sales Return created with voucher no: %s';
					$text= sprintf($body,$no);						
					   try{
							   Mail::send(['html'=>'body.salesreturn.pdfaddprint'], $data,function($message) use ($email,$text) {
							   $message->from(env('MAIL_USERNAME'));	
							   $message->to($email);
							   $message->subject($text);
							   });
						   
						   }catch(JWTException $exception){
						   $this->serverstatuscode = "0";
						   $this->serverstatusdes = $exception->getMessage();
						   //echo '<pre>';print_r($this->serverstatusdes);exit;
					   }

			}
   			#### End 

			Session::flash('message', 'Sales Return added successfully.');
		} else
			Session::flash('error', 'Something went wrong, Return failed to add!');
		
		return redirect('sales_return/add');
	}
	
	public function destroy($id)
	{
		
		if( $this->sales_return->check_order($id) ) {
			$this->sales_return->delete($id);
			//AUTO COST REFRESH CHECK ENABLE OR NOT
			if($this->mod_autocost->is_active==1) {
				$arritems = [];
				$items = DB::table('sales_return_item')->where('sales_return_id',$id)->select('item_id')->get();
				foreach($items as $rw) {
					$arritems[] = $rw->item_id;
				}
				$this->objUtility->reEvalItemCostQuantity($arritems,$this->acsettings);
			}
			Session::flash('message', 'Sales return deleted successfully.');
		} else {
			Session::flash('error', 'Sales return is already in use, you can\'t delete this!');
		}
		
		return redirect('sales_return');
	}
	
		
	public function edit($id) { 

		$data = array();
		$itemmaster = $this->itemmaster->activeItemmasterList();
		$jobs = $this->jobmaster->activeJobmasterList();
		$currency = $this->currency->activeCurrencyList();
		$vouchers = $this->accountsetting->getAccountSettingsSR($vid=4);
		$settings = $this->accountsetting->getAccountPeriod();
		$orderrow = $this->sales_return->findSRdata($id);
		$orditems = $this->sales_return->getItems($id);
		
//echo '<pre>';print_r($orderrow);exit;
		$location = $this->location->locationList();
		$getItemLocation = $this->itemmaster->getItemLocation($id,'SR');
		$itemlocedit = $this->makeTreeArrLoc( $this->itemmaster->getItemLocEdit($id,'SR') );
		

		$cngetItemLocation = $this->itemmaster->getcnItemLocations();
		$cnitemlocedit = $this->makeTreeArrLoc( $this->itemmaster->getcnItemLocEdit($id,'SR') );

		$lastid = DB::table('sales_return')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->orderBy('id','DESC')->select('id')->first();
		
		$itemcat = null;
		if($this->mod_sr_with_cat->is_active==1) {
				
			    $itemcat = DB::table('category')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
			    $view = 'editsi-batch';
			} else
			    $view = 'edit';

		$print = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','SR')
							->where('report_view_detail.is_default',1)
							->select('report_view_detail.id')
							->first();
							
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
							
		return view('body.salesreturn.'.$view) //edit  editsi-blade
					->withItems($itemmaster)
					->withJobs($jobs)
					->withCurrency($currency)
					->withOrderrow($orderrow)
					->withOrditems($orditems)
					->withVouchers($vouchers)
					->withVatdata($this->vatdata)
					->withSettings($this->acsettings)
					->withLocation($location)
					->withItemloc($getItemLocation)
					->withItemlocedit($itemlocedit)
					->withFormdata($this->formData)
					->withPrint($print)
					->withPrintid($lastid)
					->withIsconloc(($this->mod_con_loc->is_active==1)?true:false)
					->withCnitemloc($cngetItemLocation)
					->withCnitemlocedit($cnitemlocedit)
					->withIsmpqty($this->mod_mpqty->is_active)
					->withBatchitems($batch_items) //MAY25
					->withItemcat($itemcat);
	}
	
		
	public function update(Request $request)
	{  
		$id = $request->input('sales_return_id');
		$this->validate(
			$request, 
			['sales_invoice_id' => 'required',
			 //'reference_no' => ($this->formData['reference_no']==1)?'required':'nullable', 
			 'customer_name' => 'required','customer_id' => 'required',
			 'item_code.*'  => 'required', 'item_id.*' => 'required',
			 'unit_id.*' => 'required',
			 'quantity.*' => 'required',
			 'cost.*' => 'required'
			],
			['sales_invoice_id' => 'Sales Invoice no. is required.',
			 //'reference_no' => 'Reference no. is required.',
			 'customer_name.required' => 'Customer Name is required.','customer_id.required' => 'Customer name is invalid.',
			 'item_code.*.required'   => 'Item code is required.', 'item_id.*' => 'Item code is invalid.',
			 'unit_id.*' => 'Item unit is required.',
			 'quantity.*' => 'Item quantity is required.',
			 'cost.*' => 'Item cost is required.'
			]
	);
		$attributes=$request->all();
		if( $this->sales_return->update($id, $attributes) ){
        #### mail
		if(isset($attributes['send_email']) && $attributes['send_email']==1) {
		$amount=$attributes['net_amount'];
		$data['words'] = $this->number_to_word($amount);
		$data['salesitems'] = DB::table('sales_return')
		->where('sales_return.id',$id)
		->join('sales_return_item AS SRI', function($join) {
			$join->on('SRI.sales_return_id','=','sales_return.id');
				 })
		->leftjoin('users', function($join) {
			$join->on('users.id','=','sales_return.modify_by');
					 })				 
	     ->join('itemmaster AS IM', function($join) {
					$join->on('IM.id','=','SRI.item_id');
					})
		->join('units AS U', function($join) {
				$join->on('U.id','=','SRI.unit_id');
				 })
			 
		->where('SRI.status', 1)
		->where('SRI.deleted_at', '0000-00-00 00:00:00')		 
		->select('sales_return.voucher_no','sales_return.total','sales_return.vat_amount','sales_return.net_amount','sales_return.modify_at','SRI.*','IM.item_code','U.unit_name','users.name')
		->orderBY('SRI.id','ASC')->get();
		
				   //echo '<pre>';print_r($data['salesitems']);exit;
				   //$pdfnew = PDF::loadView('body.salesinvoice.pdfupdateprintnw',$data);
				   //return view('body.salesinvoice.pdfupdateprintnw')->withSalesitems($data['salesitems'])->withWords($data['words']);

				   $email='numaktech@gmail.com';
				   $no=$data['salesitems'][0]->voucher_no;
				   $body='Sales Return modified with voucher no: %s';
					$text= sprintf($body,$no);						
					   try{
							   Mail::send(['html'=>'body.salesreturn.pdfupdateprint'], $data,function($message) use ($email,$text) {
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

			Session::flash('message', 'Sales return updated successfully');
					}else
			Session::flash('error', 'Something went wrong, Sales return failed to update!');
		
		return redirect('sales_return');
	}
	
		public function viewonly($id) { 

		$data = array();
		$itemmaster = $this->itemmaster->activeItemmasterList();
		$jobs = $this->jobmaster->activeJobmasterList();
		$currency = $this->currency->activeCurrencyList();
		$vouchers = $this->accountsetting->getAccountSettingsSR($vid=4);
		$settings = $this->accountsetting->getAccountPeriod();
		$orderrow = $this->sales_return->findSRdata($id);
		$orditems = $this->sales_return->getItems($id);
		
//echo '<pre>';print_r($orderrow);exit;
		$location = $this->location->locationList();
		$getItemLocation = $this->itemmaster->getItemLocation($id,'SR');
		$itemlocedit = $this->makeTreeArrLoc( $this->itemmaster->getItemLocEdit($id,'SR') );
		

		$cngetItemLocation = $this->itemmaster->getcnItemLocations();
		$cnitemlocedit = $this->makeTreeArrLoc( $this->itemmaster->getcnItemLocEdit($id,'SR') );

		$lastid = DB::table('sales_return')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->orderBy('id','DESC')->select('id')->first();
		
		$itemcat = null;
		if($this->mod_sr_with_cat->is_active==1) {
				
			    $itemcat = DB::table('category')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
			    $view = 'editsi-batch';
			} else
			    $view = 'edit';

		$print = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','SR')
							->where('report_view_detail.is_default',1)
							->select('report_view_detail.id')
							->first();
							
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
							
		return view('body.salesreturn.viewonly') //edit  editsi-blade
					->withItems($itemmaster)
					->withJobs($jobs)
					->withCurrency($currency)
					->withOrderrow($orderrow)
					->withOrditems($orditems)
					->withVouchers($vouchers)
					->withVatdata($this->vatdata)
					->withSettings($this->acsettings)
					->withLocation($location)
					->withItemloc($getItemLocation)
					->withItemlocedit($itemlocedit)
					->withFormdata($this->formData)
					->withPrint($print)
					->withPrintid($lastid)
					->withIsconloc(($this->mod_con_loc->is_active==1)?true:false)
					->withCnitemloc($cngetItemLocation)
					->withCnitemlocedit($cnitemlocedit)
					->withIsmpqty($this->mod_mpqty->is_active)
					->withBatchitems($batch_items) //MAY25
					->withItemcat($itemcat);
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
	
	public function show($id) { 

		$data = array();
		$acmasterrow = $this->accountmaster->accountMasterView($id);
		//echo '<pre>';print_r($acmasterrow);exit;
		return view('body.accountmaster.view')
					->withMasterrow($acmasterrow)
					->withData($data);
	}
	
	
	public function getVoucher($id) {
		
		 $row = $this->accountsetting->getVoucherByID($id);//print_r($row);
		 if($row->voucher_no != '' || $row->voucher_no != null) {
			 if($row->is_prefix==0)
				 $voucher = $row->voucher_no;
			 else {
				 $no = (int)$row->voucher_no;
				 $voucher = $row->prefix.''.$no;
			 }
			 
			 //Session::put('sl_voucher_id', $request->get('vchr_id'));
			Session::put('sl_voucher_no', $voucher);
			//Session::put('sl_stock_ac', $request->get('acnt'));
			//Session::put('sl_ac_master', $request->get('ac_mstr'));
		
			 return $result = array('voucher_no' => $voucher,
								'account_id' => $row->account_id, 
								'account_name' => $row->master_name, 
								'id' => $row->id,
								'cr_id' => $row->cr_id,
								'cr_master' => $row->cr_master,
								'cr_account' => $row->cr_account
								);//print_r($ob);
		 }
		 

	}
	
	public function getPrint($id,$rid=null)
	{
		$viewfile = DB::table('report_view_detail')->where('id', $rid)->select('print_name')->first(); 
		if($viewfile->print_name=='') {
			$attributes['document_id'] = $id;
			$attributes['is_fc'] = ($fc)?1:'';
			$result = $this->sales_return->getReturn($attributes);
			$titles = ['main_head' => 'Sales Return','subhead' => 'Sales Return'];
			return view('body.salesreturn.print')
						->withDetails($result['details'])
						->withTitles($titles)
						->withFc($attributes['is_fc'])
						->withItems($result['items']);
			//echo '<pre>';print_r($result);exit;
		} else {
			$path = app_path() . '/stimulsoft/helper.php';
			if(env('STIMULSOFT_VER')==2)
			        return view('body.reports')->withPath($path)->withView($viewfile->print_name);
			   else
			        	return view('body.salesreturn.viewer')->withPath($path)->withView($viewfile->print_name);
		
		}
	}
	
	public function setSessionVal()
	{
		Session::put('sl_voucher_id', $request->get('vchr_id'));
		Session::put('sl_voucher_no', $request->get('vchr_no'));
		Session::put('sl_stock_ac', $request->get('acnt'));
		Session::put('sl_ac_master', $request->get('ac_mstr'));
	}
	
	public function checkRefNo(Request $request) {

		$check = $this->sales_return->check_reference_no($request->get('reference_no'), $request->get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
	
	public function checkVchrNo(Request $request) {

		$check = $this->sales_return->check_voucher_no($request->get('voucher_no'),$request->get('deptid'), $request->get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
	public function getCustomerMultiselect()
	{
		$data = array();
		$customers = $this->accountmaster->getCustomerList();
		return view('body.salesreturn.multiselects')
		            ->withCustomer($customers) 
					
					->withType('CUSTS')
					->withData($data);
					
		
		
	}
	public function getItems()
	{
		$data = array();
	
		$item = $this->itemmaster->activeItemmasterList();
		//echo '<pre>';print_r($item);exit;
		//$group= $this->group->groupList();
		//echo '<pre>';print_r($group);exit;
		//$subgroup= $this->group->subgroupList();
	//	echo '<pre>';print_r($subgroup);exit;
		//$category= $this->category->categoryList();
		//echo '<pre>';print_r($category);exit;
		 //$category = DB::table('category')->where('parent_id',0)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		 //$subcategory = DB::table('category')->where('parent_id',1)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		// $group = DB::table('groupcat')->where('parent_id',0)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		 //echo '<pre>';print_r($group);exit;
	//	 $subgroup = DB::table('groupcat')->where('parent_id',1)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		
		return view('body.salesreturn.multiselect')
		               // ->withCategory($category)
		               //->withSubcategory($subcategory)
		               // ->withGroup($group)
		               // ->withSubgroup($subgroup)
					->withItem($item)
					->withType('ITEM')
					->withData($data);

	}
	protected function makeTreeSup($result)
	{
		$childs = array();
		foreach($result as $item)
		//echo '<pre>';print_r($$item); exit();
		$childs[$item->customer_id][] = $item;
		
			//$childs[$item['supplier_id']][] = $item;
			//$childs[$item->cid][] = $item;
		return $childs;
	}
	protected function makeTree($result)
	{
		$childs = array();
		foreach($result as $item)
			$childs[$item['voucher_no']][] = $item;
		
		return $childs;
	}
	
		public function getJob($id)
	{
		$data = array();
	
			$data = DB::table('sales_return')->where('sales_return.customer_id',$id)
			                    ->join('jobmaster', 'jobmaster.id', '=', 'sales_return.job_id')
			                   ->where('sales_return.status',1)->where('sales_return.deleted_at','0000-00-00 00:00:00')
			                   ->select('jobmaster.id','jobmaster.code')->orderBy('jobmaster.id', 'DESC')->get();
			return $data;
		}
	
	public function getSearch(Request $request)
	{
		$data = array();
	//	echo '<pre>';print_r($request->all());exit;
		$dname = '';
		$dname = '';
		$cusid = $itemid = '';
		$voucher_head  = '';
		$report = $this->sales_return->getReportsales($request->all());
		if($request->get('search_type')=="summary")
		{
			$voucher_head = 'Sales Return Summary';
			$reports = $this->sales_return->getReportsales($request->all());
			$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];
			//$reports = ($report);
			//echo '<pre>';print_r($reports);exit;
            
		}
		
		else if($request->get('search_type')=="detail") {
			$voucher_head = 'Sales Return Detail';
			$report = $this->sales_return->getReportsales($request->all());
			$reports = $this->makeTreeVoucher($report);
			//echo '<pre>';print_r($reports);exit;
			$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];
			//$reports = $this->groupbyVoucherNo($reports);
		} else if($request->get('search_type')=="item") {
			$voucher_head = 'Sales Return by Itemwise';
			$report = $this->sales_return->getReportsales($request->all());
			$reports = $this->groupbyItemwise($report);
			$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];
			//echo '<pre>';print_r($reports);exit;
			if($request->get('item_id')!==null)
				$itemid = implode(',', $request->get('item_id'));
			else
				$itemid = '';
		
		
	}else if($request->get('search_type')=='customer') {
	//	
			$voucher_head = 'Sales Return by customerwise';
			
		    $reports = $this->makeTreeSup($report);
			$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];
			if($request->get('supplier_id')!==null)
				$cusid = implode(',', $request->get('supplier_id'));
			else
				$cusid = '';
		}
		// if($request->get('search_type')=="summary")
		// 	$voucher_head = 'Sales Return Summary';
		// else if($request->get('search_type')=="detail") {
		// 	$voucher_head = 'Sales Return Detail';
		// 	$reports = $this->makeTree($reports);
		// } 
		
		//echo '<pre>';print_r($reports);exit;
		return view('body.salesreturn.preprint')
					->withReports($reports)
					->withVoucherhead($voucher_head)
					->withType($request->get('search_type'))
					->withFromdate($request->get('date_from'))
					->withTodate($request->get('date_to'))
					->withI(0)
					->withCustomer($cusid)
					->withItem($itemid)
					->withTitles($titles)
					->withSettings($this->acsettings)
					->withData($data);
	}
	
	public function dataExport(Request $request)
	{
		$data = array();
		//echo '<pre>';print_r($request->all());exit;
		$request->merge(['type' => 'export']);
		// $reports = $this->sales_return->getReport($request->all());
		
		// if($request->get('search_type')=="summary")
		// 	$voucher_head = 'Sales Return Summary';
		// else if($request->get('search_type')=="detail") {
		// 	$voucher_head = 'Sales Return Detail';
		// 	//$reports = $this->makeTree($reports);
		// } 
		if($request->get('search_type')=="summary")
		{
			$voucher_head = 'Sales Return Summary';
			$reports = $this->sales_return->getReportsales($request->all());
			$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];
			//$reports = ($report);
		//	echo '<pre>';print_r($reports);exit;
            
		}else if($request->get('search_type')=="detail") {
			$voucher_head = 'Sales Return Detail';
			$report = $this->sales_return->getReportsales($request->all());
			$reports = $this->makeTreeVoucher($report);
		//	echo '<pre>';print_r($reports);exit;
			$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];
			//$reports = $this->groupbyVoucherNo($reports);
		}else if($request->get('search_type')=="item") {
			$voucher_head = 'Sales Return by Itemwise';
			$report = $this->sales_return->getReportsales($request->all());
			$reports = $this->groupbyItemwise($report);
			$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];
			//echo '<pre>';print_r($reports);exit;
			if($request->get('item_id')!==null)
				$itemid = implode(',', $request->get('item_id'));
			else
				$itemid = '';
		
		
	}else if($request->get('search_type')=='customer') {
	//	
			$voucher_head = 'Sales Return by customerwise';
			
		    $reports = $this->makeTreeSup($report);
			$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];
			if($request->get('supplier_id')!==null)
				$cusid = implode(',', $request->get('supplier_id'));
			else
				$cusid = '';
	 } //echo '<pre>';print_r($reports);exit;
		if($request->get('search_type')=='detail') {
		    $qty_total = $net_total = $vat_total = $gross_total = $i = 0;
			foreach ($reports as $report) {
			    $datareport[] = ['SINo.:'.$i,'SR.Date:'.date('d-m-Y', strtotime($report[0]->voucher_date)),'SR No.:'.$report[0]->voucher_no,'','','','','Customer:'.$report[0]->customer,''];
			//$datareport[] = ['SI.No.','SR#', 'SR.Ref#', 'Customer','Item Code','Description','SR.Qty','Rate','Total Amt.'];
			$datareport[] = ['Item Code','Description','SR.Qty','Rate','Gross Amt','Total Amt.','Vat Amt','Net Amt'];
			
			$gtotal =$namount=$namount_total=$vtotal=$qtotal =0;
			foreach ($report as $row) {
				$qtotal += $row->quantity;
				$qty_total += $row->quantity;
				 if($row->tax_include==1 && $row->discount==0){
					$total_amount=$row->total_price-$row->item_vat;
					$namount=$row->total_price;
												  
					}
					else if($row->tax_include==1 && $row->discount>0){
						$total_amount=$row->total_price-$row->discount;
						$namount=($row->total_price-$row->discount)-$row->item_vat;
					}
					else{
						$total_amount=$row->total_price-$row->discount;
						$namount=($row->total_price-$row->discount)+$row->item_vat;
						 }
						$gtotal+=$total_amount;
						$namount_total+=$namount;
						 $vtotal+=$row->item_vat;
						 $net_amount = $row->vat_amount + $row->subtotal;
				$datareport[] = [ 
								  
								  'item_code' => $row->item_code,
								  'description' => $row->description,
								  'quantity' => $row->quantity,
								  'unit_price' => number_format($row->unit_price,2),
								  'gross' => number_format($row->total_price,2),
								  'total_amount' => number_format($total_amount,2),
								  'vat_amount' => number_format($row->item_vat,2),
								  'net_amount' => number_format($namount,2)
								];
			}
			    $datareport[] = ['','','','','','',''];	
				$datareport[] = ['','Sub Total',$qtotal,'','',number_format($gtotal,2),number_format($vtotal,2),number_format($namount_total,2)];
				$gross_total += $row->subtotal; 
				$vat_total += $row->vat_amount;
				$net_total += $row->net_amount; 
			
			}
			
			$datareport[] = ['','','','','','',''];	
				$datareport[] = ['','Total',$qty_total,'','',number_format($gross_total,2),number_format($vat_total,2),number_format($net_total,2)];
			
			
		} else {
		
			$datareport[] = ['SI.No.','SR.#', 'Vchr.Date', 'Customer','Gross Amt.','Discount','Total Amt','VAT Amt.','Net Total'];
			$i=0;
			$total=0;$gross=0;$vat=$disc=$sale=0;	
		    $tot=0;$gs=0;$vt=$dis=$ns=0;
			foreach ($reports as $row) {
					$i++;
					$datareport[] = [ 'si' => $i,
									  'po' => $row->voucher_no,
									  'vchr_dt' => date('d-m-Y',strtotime($row->voucher_date)),
									  'customer' => $row->master_name,
									  'gross' => number_format($row->total,2),
									  'disc' => number_format($row->total_discount,2),
									  'sale' => number_format($row->subtotal,2),
									  'vat' => number_format($row->vat_amount,2),
									  'total' => number_format($row->net_amount,2)
									  
									];
									$total+= $row->net_amount;
							        $tot=number_format($total,2) ;
									$gross+= $row->total;
							        $gs=number_format($gross,2) ;
							        $disc+= $row->total_discount;
							        $dis=number_format($disc,2) ;
							        $sale+= $row->subtotal;
							        $ns=number_format($sale,2) ;
									$vat+= $row->vat_amount;
							        $vt=number_format($vat,2) ;
			}
			$datareport[] = ['','','','','','',''];			
		    $datareport[] = ['','','','Total:',$gs,$dis,$ns,$vt,$tot];
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
	
	public function getDeptVoucher($id) {
		
		$depts = $this->accountsetting->getAccountSettingsSR($vid=4,$id); 
		//echo '<pre>';print_r($depts);exit;
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
								'voucher_id' => $row->id,
								'voucher_no' => $voucher,
								'account_id' => $row->acode, 
								'account_name' => $row->account, 
								'id' => $row->acid );
								
		}
		
		return $result;
	}
	
	
}


