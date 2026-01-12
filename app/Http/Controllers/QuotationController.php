<?php

namespace App\Http\Controllers;
use App\Repositories\Area\AreaInterface;
use App\Repositories\Itemmaster\ItemmasterInterface;
use App\Repositories\Terms\TermsInterface;
use App\Repositories\Jobmaster\JobmasterInterface;
use App\Repositories\AccountMaster\AccountMasterInterface;

use App\Repositories\Currency\CurrencyInterface;
use App\Repositories\VoucherNo\VoucherNoInterface;
use App\Repositories\Quotation\QuotationInterface;
use App\Repositories\Salesman\SalesmanInterface;
use App\Repositories\Forms\FormsInterface;
use App\Repositories\MaterialRequisition\MaterialRequisitionInterface;
use App\Repositories\Location\LocationInterface;
use Illuminate\Http\Request;

use App\Http\Requests;
use Session;
use Response;
use App;
use DB;
use Mail;
use Excel;


class QuotationController extends Controller
{

	protected $area;
	protected $itemmaster;
	protected $terms;
	protected $jobmaster;
	protected $accountmaster;
	protected $currency;
	protected $voucherno;
	protected $quotation;
	protected $salesman;
	protected $forms;
	protected $formData;
	protected $material_requisition;
	protected $customer_enquiry;
	protected $location;
	
	public function __construct(QuotationInterface $quotation,MaterialRequisitionInterface $material_requisition,  ItemmasterInterface $itemmaster, FormsInterface $forms,TermsInterface $terms, JobmasterInterface $jobmaster, AccountMasterInterface $accountmaster, CurrencyInterface $currency, VoucherNoInterface $voucherno, SalesmanInterface $salesman,LocationInterface $location,AreaInterface $area) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->middleware('auth');
		$this->area = $area;
		$this->itemmaster = $itemmaster;
		$this->terms = $terms;
		$this->jobmaster = $jobmaster;
		$this->accountmaster = $accountmaster;
		$this->currency = $currency;
		$this->material_requisition = $material_requisition;
		$this->voucherno = $voucherno;
		$this->quotation = $quotation;
		$this->salesman = $salesman;
		$this->forms = $forms;
		$this->formData = $this->forms->getFormData('QP');
		
		$this->location = $location;


	
		
	}
	
    public function index() {
		
		$data = array(); //echo Session::get('cost_accounting'); //Session::get('cr_id');//echo $request->session()->get('cr_id');
		$orders = $this->quotation->purchaseOrderList();//echo '<pre>';print_r($orders);exit;
		$jobs = $this->jobmaster->activeJobmasterList();
		$sup =DB::table('account_master')->where('category','SUPPLIER')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')
		->select('id','master_name')->get(); 
		return view('body.quotation.index')
					->withOrders($orders)
					->withSettings($this->acsettings)
					->withJobs($jobs)
					->withSup($sup)
					->withData($data);
	}
	
	public function ajaxPaging(Request $request)
	{
		$columns = array( 
                            0 =>'quotation_sales.id', 
                            1 =>'voucher_no',
                            2=> 'voucher_date',
                            3=> 'supplier',
							4=> 'net_total',
							5=> 'status'
                        );
						
		$totalData = $this->quotation->salesEstimateListCount();
            
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = 'quotation.id';
        $dir = 'desc';
		$search = (empty($request->input('search.value')))?null:$request->input('search.value');
        
		$invoices = $this->quotation->salesEstimateList('get', $start, $limit, $order, $dir, $search);
		
		if($search)
			$totalFiltered =  $this->quotation->salesEstimateList('count', $start, $limit, $order, $dir, $search);
		
		 $prints = DB::table('report_view_detail')
		 					->join('report_view','report_view.id','=','report_view_detail.report_view_id')
		 					->where('report_view.code','QP')
		 					->select('report_view_detail.name','report_view_detail.id')
		 					->get();
		
        $data = array();
        if(!empty($invoices))
        {
           
			foreach ($invoices as $row)
            {
                $edit =  '"'.url('quotation/edit/'.$row->id).'"';
                $delete =  'funDelete("'.$row->id.'","'.$row->is_editable.'")';
				$print = url('quotation/print/'.$row->id);
				$viewonly =  url('quotation/viewonly/'.$row->id);
				
                $nestedData['id'] = $row->id;
                $nestedData['voucher_no'] = $row->voucher_no;
				$nestedData['voucher_date'] = date('d-m-Y', strtotime($row->voucher_date));
				$nestedData['supplier'] = ($row->customer=='SUPPLIER') ? (($row->supplier_name!='')?$row->supplier.'('.$row->supplier_name.')':$row->supplier) : $row->supplier;
				$nestedData['net_amount'] = $row->net_amount;
                $nestedData['edit'] = "<p><button class='btn btn-primary btn-xs' onClick='location.href={$edit}'>
												<span class='glyphicon glyphicon-pencil'></span></button></p>";
				
				$nestedData['viewonly'] = "<p><a href='{$viewonly}' class='btn btn-info btn-xs' target='_blank'><i class='glyphicon glyphicon-eye-open'></i></a></p>";
								
				$nestedData['delete'] = "<button class='btn btn-danger btn-xs delete' onClick='{$delete}'>
												<span class='glyphicon glyphicon-trash'></span>";
				
				$apr = ($this->acsettings->doc_approve==1)?[1]:[0,1,2];
				
				$opts = '';					
				foreach($prints as $doc) {
					$opts .= "<li role='presentation'><a href='{$print}/".$doc->id."' target='_blank' role='menuitem'>".$doc->name."</a></li>";
				}
				
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
										</div>
										<a href='{$print}/FC' target='_blank' class='btn btn-primary btn-xs'><span class='fa fa-fw fa-print'></span>FC</a></p>";
					} else {
						//$nestedData['print'] = "<p><a href='{$print}' target='_blank' class='btn btn-primary btn-xs'><span class='fa fa-fw fa-print'></span></a></p>";
						
						$nestedData['print'] = "<div class='btn-group drop_btn' role='group'>
											<button type='button' class='btn btn-primary btn-xs dropdown-toggle m-r-50'
													id='exampleIconDropdown1' data-toggle='dropdown' aria-expanded='false'>
												<i class='fa fa-fw fa-print' aria-hidden='true'></i><span class='caret'></span>
											</button>
											<ul style='min-width:100px !important;' class='dropdown-menu' aria-labelledby='exampleIconDropdown1' role='menu'>
												".$opts."
											</ul>
										</div>";
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
	// public function add() {

	// 	$data = array();
	// 	$itemmaster = $this->itemmaster->activeItemmasterList();
	// 	$terms = $this->terms->activeTermsList();
	// 	$jobs = $this->jobmaster->activeJobmasterList();
	// 	$currency = $this->currency->activeCurrencyList();
	// 	$res = $this->voucherno->getVoucherNo('QP');
	// 	$vno = $res->no+1;//echo '<pre>';print_r($currency);exit;
	// 	return view('body.quotation.add')
	// 				->withItems($itemmaster)
	// 				->withTerms($terms)
	// 				->withJobs($jobs)
	// 				->withCurrency($currency)
	// 				->withVoucherno($vno)
	// 				->withData($data);
	// }
	
	public function add($id = null,$doctype = null) {

		$data = array();
		$itemmaster = $this->itemmaster->activeItemmasterList();
		$terms = $this->terms->activeTermsList();
		$jobs = $this->jobmaster->activeJobmasterList();
		$currency = $this->currency->activeCurrencyList();
		$res = $this->voucherno->getVoucherNo('QP');
		//$vno = $res->no;//echo '<pre>';print_r($currency);exit;
		
		//$row = DB::table('quotation')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->orderBy('id','DESC')->select('id','doc_status')->first();
		$apr = ($this->acsettings->doc_approve==1)?[1]:[0,1,2];
		// if($row && in_array($row->doc_status, $apr))
		// 	$lastid = $row->id;
		// else
		// 	$lastid = null;
		
		// $print = DB::table('report_view_detail')
		// 					->join('report_view','report_view.id','=','report_view_detail.report_view_id')
		// 					->where('report_view.code','QS')
		// 					->where('report_view_detail.is_default',1)
		// 					->select('report_view_detail.id')
		// 					->first();
		$footertxt = DB::table('header_footer')->where('doc','QP')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->first();					
		if($id) {
			$ids = explode(',', $id);
			if($doctype=='QP') {
				$quoteRow = $this->quotation->findQuoteData($ids[0]); //echo '<pre>';print_r($quoteRow);exit;
			$quoteItems = $this->quotation->getItems($ids);
			}
			else{
			$quoteRow = $this->material_requisition->findQuoteData($ids[0]);
			$quoteItems = $this->material_requisition->getCEitems($ids);//echo '<pre>';print_r($quoteRow);exit;
			}
			$total = 0; $vat_amount = 0; $nettotal = 0;
			foreach($quoteItems as $item) {
				if($item->balance_quantity==0)
					$quantity = $item->quantity;
				else
					$quantity = $item->balance_quantity;
				
				$total 		+= ($quantity * $item->unit_price) - $item->discount;
				$vat_amount += ($total * $item->vat) / 100;
			}
			$nettotal = $total + $vat_amount;
			return view('body.quotation.addce')
						->withItems($itemmaster)
						->withTerms($terms)
						->withJobs($jobs)
						->withCurrency($currency)
						->withQuoterow($quoteRow)
						->withVoucherno($res)
						->withQuoteitems($quoteItems)
						->withQuoteid($id)
						->withTotal($total)
						->withVatamount($vat_amount)
						->withNettotal($nettotal)
						//->withVoucherno(Session::get('voucher_no'))
						->withReferenceno(Session::get('reference_no'))
						->withVoucherdt(Session::get('voucher_date'))
						->withLpodt(Session::get('lpo_date'))
						->withVatdata($this->vatdata)
						->withSettings($this->acsettings)
						->withFormdata($this->formData)
						->withDoctype($doctype)
						->withFooter(isset($footertxt)?$footertxt->description:'')
					//	->withLocation($location)
						->withData($data);
		}
		//echo '<pre>';print_r($this->formData);exit;
		return view('body.quotation.add')
					->withItems($itemmaster)
					->withTerms($terms)
					->withJobs($jobs)
					->withCurrency($currency)
					->withVoucherno($res)
					->withVatdata($this->vatdata)
					->withSettings($this->acsettings)
				//	->withPrintid($lastid)
					->withFormdata($this->formData)
				//	->withPrint($print)
				    ->withFooter(isset($footertxt)?$footertxt->description:'')
					->withData($data);
	}
	public function save(Request $request) {
				
		$this->quotation->create($request->all());
		Session::flash('message', 'quotation added successfully.');
		return redirect('quotation');
	}
	
	public function destroy($id)
	{
		$this->quotation->delete($id);
		//check accountmaster name is already in use.........
		// code here ********************************
		Session::flash('message', 'quotation deleted successfully.');
		return redirect('quotation');
	}
	
	public function checkRefNo(Request $request) {

		$check = $this->quotation->check_reference_no($request->get('reference_no'), $request->get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
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
		  //echo '<pre>';print_r($request->all());exit;
		if($request->hasFile('import_file')){
			
			
				$locdefault = DB::table('location')->where('is_default',1)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id')->first();

			$path = $request->file('import_file')->getRealPath();
			$data = Excel::load($path, function($reader) { })->get();
			//echo '<pre>';print_r($data);exit;
			//$items = array();
			foreach ($data as $row) {
				 if($row->item_code!='' && $row->description!='') {
					 //CHECK ITEM EXIST OR NOT
					$cost = ($row->rate!='')?DB::raw($row->rate.' AS cost'):0;
					 $item = DB::table('itemmaster')->where('itemmaster.item_code', '=', $row->item_code)
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
							$item->cost = ($row->rate !='')?$row->rate:0;
					 }
					 
					 $items[] = $item;
				 }
			}
			
		}
		
		$terms = $this->terms->activeTermsList();
		$jobs = $this->jobmaster->activeJobmasterList();
		$currency = $this->currency->activeCurrencyList();
		$location = $this->location->locationList();
		//$vouchers = $this->accountsetting->getAccountSettingsDefault2($vid=1);
		$voucherno = $this->voucherno->getVoucherNo('QP');
		$quoterow = (object)['voucher_id' => $request->input('voucher_id'),
						   'voucher_no' => $request->input('voucher_no'),
						   'curno' => $request->input('curno'),
						   'reference_no' => $request->input('reference_no'),
						   'voucher_date' => ($request->input('voucher_date')=='')?date('d-m-Y'):$request->input('voucher_date'),
						   //'lpo_no' => $request->input('lpo_no'),
						   //'lpo_date' => $request->input('lpo_date'),
						   'supplier_name' => $request->input('supplier_name'),
						   'supplier_id' => $request->input('supplier_id'),
						   'salesman_id' => $request->input('salesman_id'),
						   'description' => $request->input('description'),
						   'terms_id' => $request->input('terms_id'),
						   'job_id' => $request->input('job_id'),
						   'is_fc' => $request->input('is_fc'),
						   'currency_id' => $request->input('currency_id'),
						   'currency_rate' => $request->input('currency_rate'),
						  // 'location_id' => $request->input('location_id'),
						   //'po_no' => $request->input('po_no')
						  ];
						  
		//echo '<pre>';print_r($items);exit;
		
		return view('body.quotation.additem')
						->withTerms($terms)
						->withJobs($jobs)
						->withCurrency($currency)
						->withQuoterow($quoterow)
						->withQuoteitems($items)
						->withLocation($location)
						->withVatdata($this->vatdata)
						->withSettings($this->acsettings)
						->withFormdata($this->formData)
						->withVoucherno($voucherno)
						->withLocdefault($locdefault)
						->withData($data);
		
		
	}
	
	// public function edit($id) { 

	// 	$data = array();
	// 	$itemmaster = $this->itemmaster->activeItemmasterList();
	// 	$terms = $this->terms->activeTermsList();
	// 	$jobs = $this->jobmaster->activeJobmasterList();
	// 	$currency = $this->currency->activeCurrencyList();
	// 	$orderrow = $this->quotation->findQuotation($id);
	// 	$items = $this->quotation->getQuotationItems($id);
	// 	//$infos = $this->quotation->getInfos($id);
		
	// 	$total = 0; $discount = 0; $nettotal = 0; $vat_total = 0;
	// 	foreach($items as $item) {
	// 		$total += $item->line_total;
	// 		$discount += $item->discount;
	// 		$vat_total += $item->vat_amount;
	// 	}
	// 	$nettotal = $total - $discount + $vat_total;
		
	// 	return view('body.quotation.edit')
	// 				->withItems($itemmaster)
	// 				->withTerms($terms)
	// 				->withItems($items)
	// 				->withJobs($jobs)
	// 				->withTotal($total)
	// 				->withDiscount($discount)
	// 				->withNettotal($nettotal)
	// 				->withVattotal($vat_total)
	// 				->withCurrency($currency)
	// 				->withOrderrow($orderrow)
	// 				->withData($data);

	// }
	
	// public function update($id)
	// {
	// 	$this->accountmaster->update($id, $request->all());
	// 	Session::flash('message', 'quotation updated successfully');
	// 	return redirect('account_master');
	// }
	private function makeTreeArr($result) {
		
		$childs = array();
		foreach($result as $item)
			$childs[$item->item_detail_id][] = $item;
		
		return $childs;
	}
	public function edit($id) { 

		$data = array();
		$itemmaster = $this->itemmaster->activeItemmasterList();
		$terms = $this->terms->activeTermsList();
		$jobs = $this->jobmaster->activeJobmasterList();
		$currency = $this->currency->activeCurrencyList();
		$orderrow = $this->quotation->findQuotation($id);
		$orditems = $this->quotation->getQuotationItems($id);
		$itemdesc = $this->makeTreeArr($this->quotation->getItemDesc($id)); //
		//echo '<pre>';print_r($orderrow);exit;
		
		$apr = ($this->acsettings->doc_approve==1)?[1]:[0,1,2];
		if(in_array($orderrow->doc_status, $apr))
			$isprint = true;
		else
			$isprint = null;
		
		// $print = DB::table('report_view_detail')
		// 					->join('report_view','report_view.id','=','report_view_detail.report_view_id')
		// 					->where('report_view.code','QS')
		// 					->where('report_view_detail.is_default',1)
		// 					->select('report_view_detail.id')
		// 					->first();
							
		return view('body.quotation.edit')
					->withItems($itemmaster)
					->withTerms($terms)
					->withJobs($jobs)
					->withCurrency($currency)
					->withOrderrow($orderrow)
					->withOrditems($orditems)
					->withItemdesc($itemdesc)
					->withVatdata($this->vatdata)
					->withSettings($this->acsettings)
					->withFormdata($this->formData)
					->withIsprint($isprint)
					//->withPrint($print)
					->withData($data);

	}
	
	public function update(Request $request)
	{	
		//echo '<pre>';print_r($request->all());exit;
		$id = $request->input('purchase_order_id');
		if( $this->validate(
			$request, 
			[//'reference_no' => 'required',
			 'supplier_name' => 'required','supplier_id' => 'required',
			 'item_code.*'  => 'required', 'item_id.*' => 'required',
			 'unit_id.*' => 'required',
			 'quantity.*' => 'required',
			 'cost.*' => 'required'
			],
			[//'reference_no.required' => 'Reference no. is required.',
			 'supplier_name.required' => 'supplier Name is required.','supplier_id.required' => 'supplier name is invalid.',
			 'item_code.*.required'   => 'Item code is required.', 'item_id.*' => 'Item code is invalid.',
			 'unit_id.*' => 'Item unit is required.',
			 'quantity.*' => 'Item quantity is required.',
			 'cost.*' => 'Item cost is required.'
			]
		)) {
			//echo '<pre>';print_r($request->flash());exit;
			//return redirect('quotation/edit/'.$id)->withInput()->withErrors();
			//return redirect('quotation/edit/'.$id)
						//->withInput()
						//->withErrors(['error' => 'Something went wrong while saving the quotation']);

		}
		
		$this->quotation->update($id, $request->all()); 
		//echo '<pre>';print_r($request->all());exit;
		
		########## email script #############
		if($this->acsettings->doc_approve==1 && $request->get('doc_status')==1 && $request->get('chkmail')==1) {
					
			$attributes['document_id'] = $id;
			$attributes['is_fc'] = '';//($fc)?1:'';
			$result = $this->quotation->getQuotation($attributes);
			$itemdesc = $this->makeTreeArr($this->quotation->getItemDesc($id));
			$titles = ['main_head' => 'Quotation','subhead' => 'Quotation'];
			$data = array('details'=> $result['details'], 'titles' => $titles, 'fc' => $attributes['is_fc'], 'itemdesc' => $itemdesc, 'items' => $result['items']);
			//echo '<pre>';print_r($pdata);exit;
			$pdf = PDF::loadView('body.quotation.pdfprint', $data); //echo $pdf->output(); exit;
			//echo $pdfview = $this->getPrint($id,null,'PDF');exit;
			
			//$cust = DB::table('account_master')->where('id', $request->get('customer_id'))->select('master_name','email','contact_name')->first();
			$mailmessage = $request->get('email_message');
			$emails = explode(',', $request->get('email'));
			
			if($emails[0]!='') {
				$data = array('name'=> $request->get('customer_name'), 'mailmessage' => $mailmessage );
				try{
					Mail::send('body.quotation.email', $data, function($message) use ($emails,$pdf) {
						$message->to($emails[0]);
						
						if(count($emails) > 1) {
							foreach($emails as $k => $row) {
								if($k!=0)
									$cc[] = $row;
							}
							$message->cc($cc);
						}
						
						$message->subject('Sales Quotation');
						$message->attachData($pdf->output(), "quotation.pdf");
					});
					
				}catch(JWTException $exception){
					$this->serverstatuscode = "0";
					$this->serverstatusdes = $exception->getMessage();
					echo '<pre>';print_r($this->serverstatusdes);exit;
				}
			}
		}
		
		Session::flash('message', 'Quotation  updated successfully');
		return redirect('quotation');
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
	
	public function viewonly($id) { 

		$data = array();
		$itemmaster = $this->itemmaster->activeItemmasterList();
		$terms = $this->terms->activeTermsList();
		$jobs = $this->jobmaster->activeJobmasterList();
		$currency = $this->currency->activeCurrencyList();
		$orderrow = $this->quotation->findQuotation($id);
		$orditems = $this->quotation->getQuotationItems($id);
		$itemdesc = $this->makeTreeArr($this->quotation->getItemDesc($id)); //
		//echo '<pre>';print_r($orderrow);exit;
		
		$apr = ($this->acsettings->doc_approve==1)?[1]:[0,1,2];
		if(in_array($orderrow->doc_status, $apr))
			$isprint = true;
		else
			$isprint = null;
		
		// $print = DB::table('report_view_detail')
		// 					->join('report_view','report_view.id','=','report_view_detail.report_view_id')
		// 					->where('report_view.code','QS')
		// 					->where('report_view_detail.is_default',1)
		// 					->select('report_view_detail.id')
		// 					->first();
							
		return view('body.quotation.viewonly')
					->withItems($itemmaster)
					->withTerms($terms)
					->withJobs($jobs)
					->withCurrency($currency)
					->withOrderrow($orderrow)
					->withOrditems($orditems)
					->withItemdesc($itemdesc)
					->withVatdata($this->vatdata)
					->withSettings($this->acsettings)
					->withFormdata($this->formData)
					->withIsprint($isprint)
					//->withPrint($print)
					->withData($data);

	}
	
	
	public function getPrint($id,$rid=null,$pdf=null)
	{
		//$viewfile = DB::table('report_view')->where('code', 'QS')->where('status',1)->select('view_name')->first();
		$viewfile = DB::table('report_view_detail')->where('id', $rid)->select('print_name')->first(); 
		//echo '<pre>';print_r($viewfile);exit;
		if($viewfile->print_name=='') {
			$attributes['document_id'] = $id;
			$attributes['is_fc'] = ($fc)?1:'';
			$result = $this->quotation_sales->getQuotation($attributes);
			$itemdesc = $this->makeTreeArr($this->quotation_sales->getItemDesc($id));//echo '<pre>';print_r($result['details']);exit;
			$titles = ['main_head' => 'Quotation ','subhead' => 'Quotation'];
			$view = ($pdf=='PDF')?'pdfprint':'print';
			return view('body.quotation.'.$view)
						->withDetails($result['details'])
						->withTitles($titles)
						->withFc($attributes['is_fc'])
						->withItemdesc($itemdesc)
						->withItems($result['items']);
		} else {
			$path = app_path() . '/stimulsoft/helper.php';
			if(env('STIMULSOFT_VER')==2)
			        return view('body.reports')->withPath($path)->withView($viewfile->print_name);
			   else
			        return view('body.quotation.viewer')->withPath($path)->withView($viewfile->print_name);
			
			//return view('body.quotation.viewer')->withPath($path)->withView($viewfile->print_name);
		}
		
	}
	
	public function show($id) { 

		$data = array();
		$acmasterrow = $this->accountmaster->accountMasterView($id);
		//echo '<pre>';print_r($acmasterrow);exit;
		return view('body.accountmaster.view')
					->withMasterrow($acmasterrow)
					->withData($data);
	}
	public function checkVchrNo(Request $request) { 

		$check = $this->quotation->check_voucher_no($request->get('voucher_no'), $request->get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
	
	public function getSupplier()
	{
		$data = array();
		$suppliers = $this->accountmaster->getSupplierList();//echo '<pre>';print_r($suppliers);exit;
		return view('body.quotation.supplier')
					->withSuppliers($suppliers)
					->withData($data);
	}
	
	public function getItem($num)
	{
		$data = array();
		$itemmaster = $this->itemmaster->getActiveItemmasterList();
		return view('body.quotation.item')
					->withItems($itemmaster)
					->withNum($num)
					->withData($data);

	}

    public function getItemDetails($id) 
	{
		$data = array();
		$items = $this->quotation->getItems(array($id));
       // echo '<pre>';print_r($items);exit;
		return view('body.quotation.itemdetails')
					->withItems($items)
					->withData($data);
	}
	
	public function print2($id) { 

		$data = array();
		$itemmaster = $this->itemmaster->activeItemmasterList();
		$terms = $this->terms->activeTermsList();
		$jobs = $this->jobmaster->activeJobmasterList();
		$currency = $this->currency->activeCurrencyList();
		$orderrow = $this->quotation->find($id);
		return view('body.quotation.print')
					->withItems($itemmaster)
					->withTerms($terms)
					->withJobs($jobs)
					->withCurrency($currency)
					->withOrderrow($orderrow)
					->withData($data);

	}
	
	public function testdata()
	{
		//return json_encode(array('name' => 'asdf'));
		$data = array();
		$tdata = array( 0 => array('name' => 'asdf'), 1 => array('name' => 'qwer') );
		return view('body.quotation.testdada')
					->withTdata($tdata)
					->withData($data);
	}
	
	protected function makeArrGroup($result,$cur)
	{
		$childs = array();
		foreach($result as $item)
			$childs[$item['voucher_no']][] = $item;
		
		$arr = array();
		foreach($childs as $child) {
			$pending_qty = $pending_amt = $vat_amount = $net_amount = $discount = 0;
			foreach($child as $row) {
			    $pending_qty = ($row->balance_quantity==0)?$row->quantity:$row->balance_quantity;
				$unit_price = ($cur=='')?$row->unit_price:($row->unit_price / $row->currency_rate);
				$unit_vat = ($cur=='')?$row->unit_vat:($row->unit_vat / $row->currency_rate);
				
			    $pending_amt += $pending_qty * $unit_price;
				$vat = $unit_vat / $row->quantity;
				$vat_amount += $vat * $pending_qty;
				$net_amount = $vat_amount + $pending_amt;
				$voucher_no = $row->voucher_no;
				$discount = $row->discount;
				$refno = $row->reference_no;
				$suppname = $row->master_name;
				$jobcode = $row->jobcode;
			}
			$arr[] = ['voucher_no' => $voucher_no,'reference_no' => $refno, 'jobcode' => $jobcode, 'master_name' => $suppname, 'total' => $pending_amt,'discount' => $discount, 'vat_amount' => $vat_amount, 'net_amount' => $net_amount];
			
		}

		return $arr;
	}
	
	protected function makeTree($result)
	{
		$childs = array();
		foreach($result as $item)
			$childs[$item['voucher_no']][] = $item;
		
		return $childs;
	}

	public function getQuotations()
	{
		$quotations = $this->quotation->getCustomerQuotation();
		//echo '<pre>';print_r($quotations );exit;
		return view('body.quotation.quotations')
					->withQuotations($quotations);
	}
	
	public function getSearch(Request $request)
	{
	   // echo '<pre>';print_r($request->all());exit;
		$data = array();$curcode = '';
		if($request->get('currency_id')!='') {
			$cur = DB::table('currency')->where('id',$request->get('currency_id'))->select('code')->first();
			$curcode = ' in '.$cur->code;
		}
		
		$reports = $this->quotation->getPendingReport($request->all());
		
		if($request->get('search_type')=="summary")
			$voucher_head = 'Quotation Summary'.$curcode;
		elseif($request->get('search_type')=="summary_pending") {

			$voucher_head = 'Quotation Pending Summary'.$curcode;
			$reports = $this->makeArrGroup($reports,$curcode);

		} elseif($request->get('search_type')=="detail") {
			$voucher_head = 'Quotation Detail'.$curcode;
			$reports = $this->makeTree($reports);
		} else {
		     if($request->get('search_type')=="detail_pending"){
			$voucher_head = 'Quotation Pending Detail'.$curcode;
		     }
		     else{
		       $voucher_head = 'Quotation Quantity Detail'.$curcode;  
		     }
			$reports = $this->makeTree($reports);
		}
		//echo '<pre>';print_r($reports);exit;
		return view('body.quotation.preprint')
					->withReports($reports)
					->withVoucherhead($voucher_head)
					->withType($request->get('search_type'))
					->withFromdate($request->get('date_from'))
					->withTodate($request->get('date_to'))
					->withI(0)
					->withSettings($this->acsettings)
					->withCur($curcode)
					->withCurid($request->get('currency_id'))
					->withJobids($request->get('job_id'))
					->withSupplierid($request->get('supplier_id'))
					->withData($data);
	}
	
	public function dataExport(Request $request)
	{
	    
	    //echo '<pre>';print_r($request->all());exit;
		$data = array();
		$datareport[] = ['','','','',strtoupper(Session::get('company')),'','',''];
		$datareport[] = ['','','','','','',''];
		
		$curcode = '';
		if($request->get('currency_id')!='') {
			$cur = DB::table('currency')->where('id',$request->get('currency_id'))->select('code')->first();
			$curcode = ' in '.$cur->code;
		}
		
		$request->merge(['type' => 'export']);
		$request->merge(['job_id' => $request->get('job_id')]);
		$reports = $this->quotation->getPendingReport($request->all());
		
		if($request->get('search_type')=="summary")
			$voucher_head = 'Quotation Summary'.$curcode;
		elseif($request->get('search_type')=="summary_pending") {
			$voucher_head = 'Quotation Pending Summary'.$curcode;
			$reports = $this->makeArrGroup($reports,$curcode);
		} elseif($request->get('search_type')=="detail") {
			$voucher_head = 'Quotation Detail'.$curcode;
		} elseif($request->get('search_type')=="detail_pending") {
			$voucher_head = 'Quotation Pending Detail'.$curcode;
		}else{
		    $voucher_head = 'Quotation Quantity Detail'.$curcode;
		}
		
		$datareport[] = ['','','','',strtoupper($voucher_head), '','',''];
		$datareport[] = ['','','','','','',''];
		
		if($request->get('search_type')=='detail' ) {
			
			$datareport[] = ['SI.No.','QP#', 'QP.Ref#', 'Job No', 'Supplier','Item Code','Description','QP.Qty','Rate','Total Amt.'];
			$i=0;
			$up=0;$net=0;
			foreach ($reports as $row) {
				$i++;
				$datareport[] = [ 'si' => $i,
								  'po' => $row['voucher_no'], 
								  'ref' => $row['reference_no'],
								  'jobno' => $row['jobcode'],
								  'supplier' => $row['master_name'],
								  'item_code' => $row['item_code'],
								  'description' => $row['description'],
								  'quantity' => $row['quantity'],
								  'unit_price' => number_format($row['unit_price'],2),
								  'net_amount' => number_format($row['quantity'] * $row['unit_price'],2)
								];
								$net += number_format($row['quantity'] * $row['unit_price'],2);
								$up += $row['quantity'];
			}

			                  $datareport[] = ['','','','','','',''];			
		                    $datareport[] = ['','','','','','','Total:',$up,'',$net];
			                    
		} 	else if($request->get('search_type')=='detail_pending') {
			
			$datareport[] = ['SI.No.','QP#', 'QP.Ref#', 'Job No', 'Supplier','Item Code','Description','QP.Qty','Rate','Total Amt.','Inv.Qty','Pending Qty','Rate','TotalAmt.'];
			$i=$inv_qty=$pending_qty=$pending_amt=0;
			$up=0;$net=$inv=$pen=$netpen=0;
			foreach ($reports as $row) {
				$i++;
					$inv_qty = ($row['balance_quantity']==0)?0:$row['quantity']- $row['balance_quantity'];
					$pending_qty = ($row['balance_quantity']==0)?$row['quantity']:$row['balance_quantity'];
						$pending_amt = $pending_qty * $row['unit_price'];
				$datareport[] = [ 'si' => $i,
								  'po' => $row['voucher_no'], 
								  'ref' => $row['reference_no'],
								  'jobno' => $row['jobcode'],
								  'supplier' => $row['master_name'],
								  'item_code' => $row['item_code'],
								  'description' => $row['description'],
								  'quantity' => $row['quantity'],
								  'unit_price' => number_format($row['unit_price'],2),
								  'net_amount' => number_format($row['quantity'] * $row['unit_price'],2),
								   'inv_qty' => $inv_qty,
								    'pending' => $pending_qty,
								    'unit_pric' => number_format($row['unit_price'],2),
								    'pending_amount' => number_format($pending_amt,2)

								];
								$net += number_format($row['quantity'] * $row['unit_price'],2);
								$up += $row['quantity'];
								$inv += $inv_qty;
								$pen +=$pending_qty;
								$netpen += number_format($pending_amt,2);
								
			}

			                  $datareport[] = ['','','','','','',''];			
		                    $datareport[] = ['','','','','','','Total:',$up,'',$net,$inv,$pen,'',$netpen];
			                    
		} 	
		
		
		else if($request->get('search_type')=='qty_report')  {
			
			$datareport[] = ['SI.No.','QP#', 'QP.Ref#', 'Job No', 'Supplier','Item Code','Description','Ordered','Processed','Balance'];
			$i=$inv_qty=$pending_qty=0;
			foreach ($reports as $row) {
				$i++;
					$inv_qty = ($row['balance_quantity']==0)?0:$row['quantity']- $row['balance_quantity'];
					$pending_qty = ($row['balance_quantity']==0)?$row['quantity']:$row['balance_quantity'];
				$datareport[] = [ 'si' => $i,
								  'po' => $row['voucher_no'], 
								  'ref' => $row['reference_no'],
								  'jobno' => $row['jobcode'],
								  'supplier' => $row['master_name'],
								  'item_code' => $row['item_code'],
								  'description' => $row['description'],
								  'quantity' => $row['quantity'],
								  'processed' => $inv_qty,
								  'bal' => $pending_qty
								];
			}
		} 
		
		
		
		
		else {
			
			$datareport[] = ['SI.No.','QP#', 'QP.Ref#', 'Job No', 'Supplier','Gross Amt.','Discount','Total Amt.','VAT Amt.','Net Total'];
			$i=0;
			$total=0;$gross=0;$vat=$amount=$net_amount=0;	
		    $tot=0;$gs=0;$vt=$amt=0;
			foreach ($reports as $row) {
					$i++;
					$amount=$row['total']-$row['discount'];
					$datareport[] = [ 'si' => $i,
									  'po' => $row['voucher_no'],
									  'ref' => $row['reference_no'],
									  'jobno' => $row['jobcode'],
									  'supplier' => $row['master_name'],
									  'gross' => number_format($row['total'],2),
									  'disc' => number_format($row['discount'],2),
									  'total' => number_format($amount,2),
									  'vat' => number_format($row['vat_amount'],2),
									  'nettotal' => number_format($row['net_amount'],2)
									];

									$total+= $row['net_amount'];
							        $tot=number_format($total,2) ;
									$gross+= $row['total'];
							        $gs=number_format($gross,2) ;
									$vat+= $row['vat_amount'];
							        $vt=number_format($vat,2) ;
							        $net_amount+=$amount ;
							        $amt=number_format($net_amount,2) ;
			}
			$datareport[] = ['','','','','','',''];			
		    $datareport[] = ['','','','','Total:',$gs,'',$amt,$vt,$tot];
		}
		//echo $voucher_head.'<pre>';print_r($datareport);exit;
		Excel::create($voucher_head, function($excel) use ($datareport,$voucher_head) {
			// Set the spreadsheet title, creator, and description
			$excel->setTitle($voucher_head);
			$excel->setCreator('Profit Acc 365')->setCompany(Session::get('company'));
			$excel->setDescription($voucher_head);

			// Build the spreadsheet, passing in the payments array
			$excel->sheet('sheet1', function($sheet) use ($datareport) {
				$sheet->fromArray($datareport, null, 'A1', false, false);
			});

		})->download('xlsx');
		
		//echo '<pre>';print_r($reports);exit;
		
	}
	
}

