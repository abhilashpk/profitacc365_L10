<?php

namespace App\Http\Controllers;
use App\Repositories\Area\AreaInterface;
use App\Repositories\Itemmaster\ItemmasterInterface;
use App\Repositories\Terms\TermsInterface;
use App\Repositories\Jobmaster\JobmasterInterface;
use App\Repositories\AccountMaster\AccountMasterInterface;
use App\Repositories\Currency\CurrencyInterface;
use App\Repositories\VoucherNo\VoucherNoInterface;
use App\Repositories\QuotationSales\QuotationSalesInterface;
use App\Repositories\Salesman\SalesmanInterface;
use App\Repositories\Forms\FormsInterface;
use App\Repositories\CustomerEnquiry\CustomerEnquiryInterface;
use App\Repositories\Location\LocationInterface;

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

class QuotationSalesController extends Controller
{

	protected $area;
	protected $itemmaster;
	protected $terms;
	protected $jobmaster;
	protected $accountmaster;
	protected $currency;
	protected $voucherno;
	protected $quotation_sales;
	protected $salesman;
	protected $forms;
	protected $formData;
	protected $customer_enquiry;
	protected $location;
	
	public function __construct(CustomerEnquiryInterface $customer_enquiry, QuotationSalesInterface $quotation_sales, ItemmasterInterface $itemmaster, TermsInterface $terms, JobmasterInterface $jobmaster, AccountMasterInterface $accountmaster, CurrencyInterface $currency, VoucherNoInterface $voucherno, AreaInterface $area, SalesmanInterface $salesman,FormsInterface $forms,LocationInterface $location) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		
		$this->middleware('auth');
		$this->area = $area;
		$this->itemmaster = $itemmaster;
		$this->terms = $terms;
		$this->jobmaster = $jobmaster;
		$this->accountmaster = $accountmaster;
		$this->currency = $currency;
		$this->voucherno = $voucherno;
		$this->quotation_sales = $quotation_sales;
		$this->salesman = $salesman;
		$this->forms = $forms;
		$this->formData = $this->forms->getFormData('QS');
		$this->customer_enquiry = $customer_enquiry;
		$this->location = $location;
		if (Auth::check() && Auth::user()->hasRole('Salesman')) {
		    $srec = DB::table('salesman')->where('name',Auth::user()->name)->select('id')->first();
		    if($srec)
		        Session::put('salesman_id',$srec->id);
		}
		
		
	}
	
    public function index() {
		
		$data = array();
		$quotations = [];//$this->quotation_sales->quotationSalesList();//echo '<pre>';print_r($quotations);exit;
		$salesmans = $this->salesman->getSalesmanList();
		$jobs = $this->jobmaster->activeJobmasterList();
		$cus =DB::table('account_master')->where('category','CUSTOMER')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')
		->select('id','master_name')->get(); 
		return view('body.quotationsales.index')
					->withQuotations($quotations)
					->withSalesman($salesmans)
					->withSettings($this->acsettings)
					->withJobs($jobs)
					->withCus($cus)
					->withData($data);
	}
	
	public function ajaxPaging(Request $request)
	{
		$columns = array( 
                            0 =>'quotation_sales.id', 
                            1 =>'bin_name1',
                            2=> 'voucher_date',
                            3=> 'customer',
							4=> 'net_total',
							5=> 'status',
							6=>'approval',
							7=>'approval_status'
                        );
						
		$totalData = $this->quotation_sales->salesEstimateListCount();
            
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        //$order = 'bin_name1'; //$order = 'quotation_sales.id';
        $order ='quotation_sales.voucher_no'; //$columns[$request->input('order.0.column')];
        //$dir = $request->input('order.0.dir');
        $dir = 'desc';
		$search = (empty($request->input('search.value')))?null:$request->input('search.value');
        
		$invoices = $this->quotation_sales->salesEstimateList('get', $start, $limit, $order, $dir, $search);
		//echo '<pre>';print_r($invoices);exit;  
		if($search)
			$totalFiltered =  $this->quotation_sales->salesEstimateList('count', $start, $limit, $order, $dir, $search);
		
		$prints = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','QS')
							->select('report_view_detail.name','report_view_detail.id')
							->get();
		
        $data = array();
        if(!empty($invoices))
        {
           
			foreach ($invoices as $row)
            {
                $edit =  url('quotation_sales/edit/'.$row->id);
                $revice =  url('quotation_sales/revice/'.$row->id);
                $delete =  'funDelete("'.$row->id.'","'.$row->is_editable.'")';
				$print = url('quotation_sales/print/'.$row->id);
				$open =  '"'.url('quotation_sales/doc_open/'.$row->id).'"';
				$view =  url('quotation_sales/views/'.$row->id);
				$refresh =  url('quotation_sales/refresh_qs/'.$row->id);
				$viewonly =  url('quotation_sales/viewonly/'.$row->id);
				if($row->approval_status==1){
					$appstatus=1;

				}
				else{
					$appstatus=0;
				}
				
                $nestedData['id'] = $row->id;
                $nestedData['bin_name1'] = $row->voucher_no;
				$nestedData['voucher_date'] = date('d-m-Y', strtotime($row->voucher_date));
				$nestedData['customer'] = ($row->customer=='CASH CUSTOMERS') ? (($row->customer_name!='')?$row->customer.'('.$row->customer_name.')':$row->customer) : $row->customer;
				$nestedData['net_total'] = $row->net_total;
				$nestedData['approval'] = ($row->approval_status==1)?'Approved':'Not Approved';
                $nestedData['edit2'] = "<p><button class='btn btn-primary btn-xs' onClick='location.href={$edit}'>
												<span class='glyphicon glyphicon-pencil'></span></button></p>";
												
				$nestedData['edit'] = "<div class='btn-group drop_btn' role='group'>
											<button type='button' class='btn btn-primary btn-xs dropdown-toggle m-r-50'
													id='exampleIconDropdown1' data-toggle='dropdown' aria-expanded='false'>
												<i class='fa fa-fw fa-shield'></i><span class='caret'></span>
											</button>
											<ul style='min-width:100px !important;' class='dropdown-menu' aria-labelledby='exampleIconDropdown1' role='menu'>
												<li role='presentation'><a href='{$edit}' role='menuitem'>Edit</a></li>
												<li role='presentation'><a href='{$revice}' role='menuitem'>Revice</a></li>
												<li role='presentation'><a href='{$refresh}' role='menuitem'>Refresh</a></li>
											</ul>
										</div>";
												
				$nestedData['open'] = "<p><button class='btn btn-success btn-xs'  onClick='location.href={$open}' target='_blank'>
												<i class='fa fa-fw fa-folder-open'></i></button></p>";
				
				if(Auth::user()->roles[0]->name == "Admin"){
			    $nestedData['view'] = "<p><a href='{$view}' class='btn btn-info btn-xs' target='_blank'><i class='fa fa-fw fa-check-square'></i></a></p>";								
			 }else{
			 $nestedData['view']='';
			 }

			 $nestedData['approval_status']=$appstatus;										
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
	public function docopen($id) { 
		$data = array();
		$orderrow = $this->quotation_sales->findPOdata($id);
		$orditems = $this->quotation_sales->getItems($id);
		$photos = DB::table('quot_fotos')->where('quot_id',$id)->get(); 
		$val = '';
		foreach($photos as $row) {
			$val .= ($val=='')?$row->photo:','.$row->photo;
		}
		return view('body.quotation_sales.open')
					->withPhotos($val)
					->withFormdata($this->formData)
					->withOrderrow($orderrow)
					->withOrditems($orditems)
				->withData($data);

	}	
	public function add($id = null, $doctype = null) {

		$data = array();
		$itemmaster = $this->itemmaster->activeItemmasterList();
		$terms = $this->terms->activeTermsList();
		$jobs = $this->jobmaster->activeJobmasterList();
		$currency = $this->currency->activeCurrencyList();
		$res = $this->voucherno->getVoucherNo('QS'); //echo '<pre>';print_r($res);exit;
		//$vno = $res->no;//echo '<pre>';print_r($currency);exit;
		$location = $this->location->locationList();
		$row = DB::table('quotation_sales')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->orderBy('id','DESC')->select('id','doc_status')->first();
		$apr = ($this->acsettings->doc_approve==1)?[1]:[0,1,2];
		if($row && in_array($row->doc_status, $apr))
			$lastid = $row->id;
		else
			$lastid = null;
		
		$hdr = DB::table('header_footer')->where('doc','QS')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('description')->first();
		$print = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','QS')
							->where('report_view_detail.is_default',1)
							->select('report_view_detail.id')
							->first();
							
		if($id) {
			$ids = explode(',', $id);
			if($doctype=='QS') {
				$quoteRow = $this->quotation_sales->findQuoteData($ids[0]);
				$quoteItems = $this->quotation_sales->getQSItems($ids);//echo '<pre>';print_r($quoteRow);exit;
			} else {
				$quoteRow = $this->customer_enquiry->findQuoteData($ids[0]);
				$quoteItems = $this->customer_enquiry->getCEitems($ids);//echo '<pre>';print_r($quoteRow);exit;
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
			return view('body.quotationsales.addce') //addce addce-eqwep
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
						->withPrintid($lastid)
						->withLocation($location)
						->withHeaderdata(isset($hdr)?$hdr->description:'')
						->withData($data);
		}
		
		return view('body.quotationsales.add') //add-eqwep
					->withItems($itemmaster)
					->withTerms($terms)
					->withJobs($jobs)
					->withCurrency($currency)
					->withVoucherno($res)
					->withVatdata($this->vatdata)
					->withSettings($this->acsettings)
					->withPrintid($lastid)
					->withFormdata($this->formData)
					->withPrint($print)
					->withLocation($location)
					->withHeaderdata(isset($hdr)?$hdr->description:'')
					->withData($data);
	}
	
	public function save(Request $request) { 
		
		//echo '<pre>';print_r( $request->all() );exit;
		/* $this->validate($request, [
        'reference_no' => 'required', 'voucher_date' => 'required','item_code.*' => 'required'
    ]); */
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

		/*$request['voucher_no']='';
		$res = $this->voucherno->getVoucherNo('QS');
		$request['voucher_no']=$res->no;*/
		
		$id = $this->quotation_sales->create($request->all());
		if($id) {
			Session::flash('message', 'Quotation added successfully.'); 
			return redirect('quotation_sales/add');
		} else {
			Session::flash('error', 'Something went wrong, Order failed to add!');
			return redirect('quotation_sales/add');
		}
		
		
	}
public function destroy($id)
	{
		$this->quotation_sales->delete($id);
		//check accountmaster name is already in use.........
		// code here ********************************
		Session::flash('message', 'Quotation deleted successfully.');
		return redirect('quotation_sales');
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
		$voucherno = $this->voucherno->getVoucherNo('QS');
		$quoterow = (object)['voucher_id' => $request->input('voucher_id'),
						   'voucher_no' => $request->input('voucher_no'),
						   'curno' => $request->input('curno'),
						   'reference_no' => $request->input('reference_no'),
						   'voucher_date' => ($request->input('voucher_date')=='')?date('d-m-Y'):$request->input('voucher_date'),
						   'lpo_no' => $request->input('lpo_no'),
						   'lpo_date' => $request->input('lpo_date'),
						   'customer_name' => $request->input('customer_name'),
						   'customer_id' => $request->input('customer_id'),
						   'salesman_id' => $request->input('salesman_id'),
						   'description' => $request->input('description'),
						   'terms_id' => $request->input('terms_id'),
						   'job_id' => $request->input('job_id'),
						   'is_fc' => $request->input('is_fc'),
						   'currency_id' => $request->input('currency_id'),
						   'currency_rate' => $request->input('currency_rate'),
						   'location_id' => $request->input('location_id'),
						   //'po_no' => $request->input('po_no')
						  ];
						  
		//echo '<pre>';print_r($items);exit;
		
		return view('body.quotationsales.additem')
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
	
	public function checkRefNo(Request $request) {

		$check = $this->quotation_sales->check_reference_no($request->get('reference_no'), $request->get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
	
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
		$orderrow = $this->quotation_sales->findPOdata($id);
		$orditems = $this->quotation_sales->getItems($id);
		$itemdesc = $this->makeTreeArr($this->quotation_sales->getItemDesc($id)); //echo '<pre>';print_r($orditems);exit;
		$photos = DB::table('quot_fotos')->where('quot_id',$id)->get(); 
		$val = '';
		foreach($photos as $row) {
			$val .= ($val=='')?$row->photo:','.$row->photo;
		}		
		$apr = ($this->acsettings->doc_approve==1)?[1]:[0,1,2];
		if(in_array($orderrow->doc_status, $apr))
			$isprint = true;
		else
			$isprint = null;
		
		$print = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','QS')
							->where('report_view_detail.is_default',1)
							->select('report_view_detail.id')
							->first();
		
		//DEC22
		$infodata = DB::table('quotation_sales_info')->where('quotation_sales_id',$id)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->orderBy('id','ASC')->get();
		//echo '<pre>';print_r($infoedit);exit;	
		return view('body.quotationsales.edit') //edit-eqwep
					->withItems($itemmaster)
					->withTerms($terms)
					->withJobs($jobs)
					->withCurrency($currency)
					->withOrderrow($orderrow)
					->withOrditems($orditems)
					->withItemdesc($itemdesc)
					->withPhotos($val)
					->withVatdata($this->vatdata)
					->withSettings($this->acsettings)
					->withFormdata($this->formData)
					->withIsprint($isprint)
					->withPrint($print)
					->withInfos($infodata)//DEC22
					->withData($data);

	}
	
	public function update(Request $request)
	{	
		//echo '<pre>';print_r($request->all());exit;
		$id = $request->input('quotation_order_id');
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
		
		$this->quotation_sales->update($id, $request->all()); 
		//echo '<pre>';print_r($request->all());exit;
		
		########## email script #############
		if($this->acsettings->doc_approve==1 && $request->get('doc_status')==1 && $request->get('chkmail')==1) {
					
			$attributes['document_id'] = $id;
			$attributes['is_fc'] = '';//($fc)?1:'';
			$result = $this->quotation_sales->getQuotation($attributes);
			$itemdesc = $this->makeTreeArr($this->quotation_sales->getItemDesc($id));
			$titles = ['main_head' => 'Quotation Sales','subhead' => 'Quotation'];
			$data = array('details'=> $result['details'], 'titles' => $titles, 'fc' => $attributes['is_fc'], 'itemdesc' => $itemdesc, 'items' => $result['items']);
			//echo '<pre>';print_r($pdata);exit;
			$pdf = PDF::loadView('body.quotationsales.pdfprint', $data); //echo $pdf->output(); exit;
			//echo $pdfview = $this->getPrint($id,null,'PDF');exit;
			
			//$cust = DB::table('account_master')->where('id', $request->get('customer_id'))->select('master_name','email','contact_name')->first();
			$mailmessage = $request->get('email_message');
			$emails = explode(',', $request->get('email'));
			
			if($emails[0]!='') {
				$data = array('name'=> $request->get('customer_name'), 'mailmessage' => $mailmessage );
				try{
					Mail::send('body.quotationsales.email', $data, function($message) use ($emails,$pdf) {
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
		
		Session::flash('message', 'Quotation sales updated successfully');
		return redirect('quotation_sales');
	}
	
	
		public function viewonly($id) { 

		$data = array();
		$itemmaster = $this->itemmaster->activeItemmasterList();
		$terms = $this->terms->activeTermsList();
		$jobs = $this->jobmaster->activeJobmasterList();
		$currency = $this->currency->activeCurrencyList();
		$orderrow = $this->quotation_sales->findPOdata($id);
		$orditems = $this->quotation_sales->getItems($id);
		$itemdesc = $this->makeTreeArr($this->quotation_sales->getItemDesc($id)); //echo '<pre>';print_r($orditems);exit;
		$photos = DB::table('quot_fotos')->where('quot_id',$id)->get(); 
		$val = '';
		foreach($photos as $row) {
			$val .= ($val=='')?$row->photo:','.$row->photo;
		}		
		$apr = ($this->acsettings->doc_approve==1)?[1]:[0,1,2];
		if(in_array($orderrow->doc_status, $apr))
			$isprint = true;
		else
			$isprint = null;
		
		$print = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','QS')
							->where('report_view_detail.is_default',1)
							->select('report_view_detail.id')
							->first();
		
		//DEC22
		$infodata = DB::table('quotation_sales_info')->where('quotation_sales_id',$id)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->orderBy('id','ASC')->get();
		//echo '<pre>';print_r($infoedit);exit;	
		return view('body.quotationsales.viewonly') //edit-eqwep
					->withItems($itemmaster)
					->withTerms($terms)
					->withJobs($jobs)
					->withCurrency($currency)
					->withOrderrow($orderrow)
					->withOrditems($orditems)
					->withItemdesc($itemdesc)
					->withPhotos($val)
					->withVatdata($this->vatdata)
					->withSettings($this->acsettings)
					->withFormdata($this->formData)
					->withIsprint($isprint)
					->withPrint($print)
					->withInfos($infodata)//DEC22
					->withData($data);

	}
	
	
	
		public function getViews($id) { 
	    
	    $data = array();
		$itemmaster = $this->itemmaster->activeItemmasterList();
		$terms = $this->terms->activeTermsList();
		$jobs = $this->jobmaster->activeJobmasterList();
		$currency = $this->currency->activeCurrencyList();
		$orderrow = $this->quotation_sales->findPOdata($id);
		$orditems = $this->quotation_sales->getItems($id);
		$itemdesc = $this->makeTreeArr($this->quotation_sales->getItemDesc($id)); //echo '<pre>';print_r($orditems);exit;
		$photos = DB::table('quot_fotos')->where('quot_id',$id)->get(); 
		$val = '';
		foreach($photos as $row) {
			$val .= ($val=='')?$row->photo:','.$row->photo;
		}		
		$apr = ($this->acsettings->doc_approve==1)?[1]:[0,1,2];
		if(in_array($orderrow->doc_status, $apr))
			$isprint = true;
		else
			$isprint = null;
		
		$print = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','QS')
							->where('report_view_detail.is_default',1)
							->select('report_view_detail.id')
							->first();
		
		//DEC22
		$infodata = DB::table('quotation_sales_info')->where('quotation_sales_id',$id)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->orderBy('id','ASC')->get();
		//echo '<pre>';print_r($infoedit);exit;	
		return view('body.quotationsales.viewapproval') //edit-eqwep
					->withItems($itemmaster)
					->withTerms($terms)
					->withJobs($jobs)
					->withCurrency($currency)
					->withOrderrow($orderrow)
					->withOrditems($orditems)
					->withItemdesc($itemdesc)
					->withPhotos($val)
					->withVatdata($this->vatdata)
					->withSettings($this->acsettings)
					->withFormdata($this->formData)
					->withIsprint($isprint)
					->withPrint($print)
					->withInfos($infodata)//DEC22
					->withData($data);

	    
	    
	}
	
	public function getApproval($id)
	{
		DB::table('quotation_sales')->where('id',$id)->update(['approval_status' => 1]);
		Session::flash('message', 'Quotation Sales approved successfully.');
		return redirect('quotation_sales');
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
	
	public function show($id) { 

		$data = array();
		$acmasterrow = $this->accountmaster->accountMasterView($id);
		//echo '<pre>';print_r($acmasterrow);exit;
		return view('body.accountmaster.view')
					->withMasterrow($acmasterrow)
					->withData($data);
	}
	
	public function getCustomer()
	{
		$data = array();
		$customers = $this->accountmaster->getCustomerList();//print_r($customers);exit;
		return view('body.quotationsales.customer')
					->withCustomers($customers)
					->withData($data);
	}
	
	public function getSalesman()
	{
		$data = array();
		$salesmans = $this->salesman->getSalesmanList();
		return view('body.quotationsales.salesman')
					->withSalesmans($salesmans)
					->withData($data);
	}
	
	public function getItem($num)
	{
		$data = array();
		$itemmaster = $this->itemmaster->getActiveItemmasterList();
		return view('body.quotationsales.item')
					->withItems($itemmaster)
					->withNum($num)
					->withData($data);
	}
	
	public function getJob($id)
	{
		$data = array();
	
			$data = DB::table('quotation_sales')->where('quotation_sales.customer_id',$id)
			                    ->join('jobmaster', 'jobmaster.id', '=', 'quotation_sales.job_id')
			                   ->where('quotation_sales.status',1)->where('quotation_sales.deleted_at','0000-00-00 00:00:00')
			                   ->select('jobmaster.id','jobmaster.code')->orderBy('jobmaster.id', 'DESC')->get();
			return $data;
		}
	
	
	public function getQuotation($customer_id, $url)
	{
		$data = array();
		$quotations = $this->quotation_sales->getCustomerQuotation($customer_id);//echo '<pre>';print_r($quotations);exit;
		return view('body.quotationsales.quotation')
					->withQuotations($quotations)
					->withUrl($url)
					->withData($data);
	}
	
	public function getItemDetails($id) 
	{
		$data = array();
		$items = $this->quotation_sales->getItems(array($id));
		return view('body.quotationsales.itemdetails')
					->withItems($items)
					->withData($data);
	}
	
	public function getPrint($id,$rid=null,$pdf=null)
	{
		//$viewfile = DB::table('report_view')->where('code', 'QS')->where('status',1)->select('view_name')->first();
		$viewfile = DB::table('report_view_detail')->where('id', $rid)->select('print_name')->first(); 
		// echo '<pre>';print_r($viewfile);exit;
		if($viewfile->print_name=='') {
			$attributes['document_id'] = $id;
			$attributes['is_fc'] = '';
			$result = $this->quotation_sales->getQuotation($attributes);
			$itemdesc = $this->makeTreeArr($this->quotation_sales->getItemDesc($id));//echo '<pre>';print_r($result['details']);exit;
			$titles = ['main_head' => 'Quotation Sales','subhead' => 'Quotation'];
			$view = ($pdf=='PDF')?'pdfprint':'print';
			$words = $this->number_to_word($result['details']->net_total);
			$amount = $result['details']->net_total;
			$arr = explode('.',number_format($amount,2));
			if(sizeof($arr) >1 ) {
				if($arr[1]!=00) {
					$dec = $this->number_to_word($arr[1]);
					$words .= ' and Fils '.$dec.' Only';
				} else 
					$words .= ' Only';
			} else
				$words .= ' Only';
			
			return view('body.quotationsales.'.$view)
						->withDetails($result['details'])
						->withTitles($titles)
						->withFc($attributes['is_fc'])
						->withItemdesc($itemdesc)
						->withAmtwords($words)
						->withItems($result['items']);
		} else {
			$path = app_path() . '/stimulsoft/helper.php';
			if(env('STIMULSOFT_VER')==2)
		        return view('body.reports')->withPath($path)->withView($viewfile->print_name);
		    else
		        return view('body.quotationsales.viewer')->withPath($path)->withView($viewfile->print_name);
			        
			//return view('body.quotationsales.viewer')->withPath($path)->withView($viewfile->print_name);
		}
		
	}
	
	protected function makeTreeItm($result)
	{
		$childs = array();
		foreach($result as $item)
			$childs[$item['item_id']][] = $item;
		
		return $childs;
	}
	
	protected function makeTree($result)
	{
		$childs = array();
		foreach($result as $item)
			$childs[$item['voucher_no']][] = $item;
		
		return $childs;
	}
	
	protected function makeArrGroup($result)
	{
		$childs = array();
		foreach($result as $item)
			$childs[$item['voucher_no']][] = $item;
		
		$arr = array();
		foreach($childs as $child) {
			$pending_qty = $pending_amt = $vat_amount = $net_amount = $discount = 0;
			foreach($child as $row) {
			    $pending_qty = ($row->balance_quantity==0)?$row->quantity:$row->balance_quantity;
			    $pending_amt += $pending_qty * $row->unit_price;
				$vat = $row->unit_vat / $row->quantity;
				$vat_amount += $vat * $pending_qty;
				$net_amount = $vat_amount + $pending_amt;
				$voucher_no = $row->voucher_no;
				$refno = $row->reference_no;
				$suppname = $row->master_name;
				$salesman = $row->salesman;
				$discount = $row->discount;
				$jobcode = $row->jobcode;
			}
			$arr[] = ['voucher_no' => $voucher_no,'reference_no' => $refno, 'master_name' => $suppname, 'discount' => $discount, 
					  'total' => $pending_amt,'vat_amount' => $vat_amount, 'net_total' => $net_amount, 'salesman' => $salesman,'jobcode' => $jobcode];
			
		}

		return $arr;
	}

	public function getSearch(Request $request)
	{
		$data = array();
		//	echo '<pre>';print_r($request->all());exit;
			$pending=($request->get('pending'))?$request->get('pending'):0;
		$reports = $this->quotation_sales->getPendingReport($request->all());
		
		if($request->get('search_type')=="summary" && $pending==0)
			$voucher_head = 'Quotation Sales Summary';
		elseif($request->get('search_type')=="summary" && $pending==1 ) {
			$voucher_head = 'Quotation Sales Pending Summary';
			$reports = $this->makeArrGroup($reports);
			$request->merge(['search_type' => 'summary_pending']); 
		} elseif($request->get('search_type')=="detail" && $pending==0) {
			$voucher_head = 'Quotation Sales Detail';
			$reports = $this->makeTree($reports);
		} else {
		     if($request->get('search_type')=="detail" && $pending==1){
			$voucher_head = 'Quotation Sales Pending Detail';
		     }
		     else{
		      $voucher_head = 'Quotation Sales Quantity Detail';
		     }
			$reports = $this->makeTree($reports);
			$request->merge(['search_type' => 'detail_pending']);
		}
		
		//echo '<pre>';print_r($reports);exit;
		return view('body.quotationsales.preprint')
					->withReports($reports)
					->withVoucherhead($voucher_head)
					->withType($request->get('search_type'))
					->withFromdate($request->get('date_from'))
					->withTodate($request->get('date_to'))
					->withSalesman($request->get('salesman'))
					->withCustomerid($request->get('customer_id'))
					->withSettings($this->acsettings)
					->withJobids(json_encode($request->get('job_id')))
					->withData($data);
	}
	
	
	public function dataExport(Request $request)
	{
		$data = array();
	//	echo '<pre>';print_r($request->all());exit;
		$datareport[] = ['','','','',strtoupper(Session::get('company')),'','',''];
		$datareport[] = ['','','','','','',''];
		
		$request->merge(['type' => 'export']);
		$request->merge(['job_id' => json_decode($request->get('job_id'))]);
		$reports = $this->quotation_sales->getPendingReport($request->all());
		
		if($request->get('search_type')=="summary")
			$voucher_head = 'Quotation Sales Summary';
		elseif($request->get('search_type')=="summary_pending") {
			$voucher_head = 'Quotation Sales Pending Summary';
			$reports = $this->makeArrGroup($reports);
		} elseif($request->get('search_type')=="detail") {
			$voucher_head = 'Quotation Sales Detail';
		} elseif($request->get('search_type')=="detail_pending") {
			$voucher_head = 'Quotation Sales Pending Detail';
		}else{
		    $voucher_head = 'Quotation Sales Quantity Detail';
		}
		
		 //echo '<pre>';print_r($reports);exit;
		
		$datareport[] = ['','','','',strtoupper($voucher_head), '','',''];
		$datareport[] = ['','','','','','',''];
		
		if($request->get('search_type')=='detail' ) {
			
			$datareport[] = ['SI.No.','Qtn.#', 'Qtn.Ref#', 'Job No.', 'Customer','Salesman','Item Code','Description','Qtn.Qty','Rate','Total Amt.','Vat Amt','Net Amt'];
			$i=$net_amount=0;
			foreach ($reports as $row) {
				$i++;
				$net_amount = $row->unit_vat + $row->line_total;
				$datareport[] = [ 'si' => $i,
								  'po' => $row['voucher_no'], 
								  'ref' => $row['reference_no'],
								  'jobno' => $row['jobcode'],
								  'supplier' => $row['master_name'],
								  'salesman' => $row['salesman'],
								  'item_code' => $row['item_code'],
								  'description' => $row['description'],
								  'quantity' => $row['quantity'],
								  'unit_price' => number_format($row['unit_price'],2),
								  'total_amt' => number_format($row['line_total'],2),
								  'vat_amt' => number_format($row['unit_vat'],2),
								  'net_amount' => number_format($net_amount,2)
								];
			}
		} 
			else if( $request->get('search_type')=='detail_pending') {
			
			$datareport[] = ['SI.No.','Qtn.#', 'Qtn.Ref#', 'Job No.', 'Customer','Salesman','Item Code','Description','Qtn.Qty','Rate','Total Amt.','Inv.Qty','Pending_qty','Rate','Total Amt'];
			$i=$total_amt=$inv_qty=$pending_qty=$pending_amt=0;
			foreach ($reports as $row) {
				$i++;
				$total_amt = $row['quantity'] * $row['unit_price'];
				$inv_qty = ($row['balance_quantity']==0)?0:$row['quantity'] - $row['balance_quantity'];
				$pending_qty = ($row['balance_quantity']==0)?$row['quantity']:$row['balance_quantity'];
				$pending_amt = $pending_qty * $row['unit_price'];
			
				$datareport[] = [ 'si' => $i,
								  'po' => $row['voucher_no'], 
								  'ref' => $row['reference_no'],
								  'jobno' => $row['jobcode'],
								  'supplier' => $row['master_name'],
								  'salesman' => $row['salesman'],
								  'item_code' => $row['item_code'],
								  'description' => $row['description'],
								  'quantity' => $row['quantity'],
								  'unit_price' => number_format($row['unit_price'],2),
								  'total_amt' => number_format($total_amt,2),
								    'inv_qty' => $inv_qty,
								    'pending_qty' => $pending_qty,
								  'unit_amt' => number_format($row['unit_price'],2),
								  'pend_amount' => number_format($pending_amt,2)
								];
			}
		} 
		else if( $request->get('search_type')=='qty_report') {
			
			$datareport[] = ['SI.No.','Qtn.#', 'Qtn.Ref#', 'Job No.', 'Customer','Salesman','Item Code','Description','Ordered','Processed','Balance'];
			$i=$total_amt=$inv_qty=$pending_qty=$pending_amt=0;
			foreach ($reports as $row) {
				$i++;
				$total_amt = $row['quantity'] * $row['unit_price'];
				$inv_qty = ($row['balance_quantity']==0)?0:$row['quantity'] - $row['balance_quantity'];
				$pending_qty = ($row['balance_quantity']==0)?$row['quantity']:$row['balance_quantity'];
				$pending_amt = $pending_qty * $row['unit_price'];
			
				$datareport[] = [ 'si' => $i,
								  'po' => $row['voucher_no'], 
								  'ref' => $row['reference_no'],
								  'jobno' => $row['jobcode'],
								  'supplier' => $row['master_name'],
								  'salesman' => $row['salesman'],
								  'item_code' => $row['item_code'],
								  'description' => $row['description'],
								  'quantity' => $row['quantity'],
								   'inv_qty' => $inv_qty,
								   'pending_qty' => $pending_qty,
								  
								];
			}
		} 
		
		else {
			
			$datareport[] = ['SI.No.','Qtn.#', 'Qtn.Ref#', 'Job No.', 'Customer','Salesman','Gross Amt.','Discount','Total_Amt','VAT Amt.','Net Total'];
			$i=0;
			$total=0;$gross=0;$vat=$total_amt=$discount=0;	
		    $tot=0;$gs=0;$vt=$tam=$tot_amt=$dis=0;
			foreach ($reports as $row) {
					$i++;
					$total_amt=$row['total']-$row['discount'];
					$datareport[] = [ 'si' => $i,
									  'po' => $row['voucher_no'],
									  'ref' => $row['reference_no'],
									  'jobcode' => $row['jobcode'],
									  'supplier' => $row['master_name'],
									  'salesman' => $row['salesman'],
									  'gross' => number_format($row['total'],2),
									  'disc' => number_format($row['discount'],2),
									  'tot_amt' => number_format($total_amt,2),
									  'vat' => number_format($row['vat_amount'],2),
									  'total' => number_format($row['net_total'],2)
									  
									];
									$total+= $row['net_total'];
							        $tot=number_format($total,2) ;
									$gross+= $row['total'];
							        $gs=number_format($gross,2) ;
									$vat+= $row['vat_amount'];
							        $vt=number_format($vat,2) ;
							        $tot_amt+=$total_amt;
							        $tam=number_format($tot_amt,2);
							        $discount+=$row['discount'];
							        $dis=number_format($discount,2);
			}
			$datareport[] = ['','','','','','',''];			
		    $datareport[] = ['','','','','','Total:',$gs,$dis,$tam,$vt,$tot];
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
	
	public function checkVchrNo(Request $request) {

		$check = $this->quotation_sales->check_voucher_no($request->get('voucher_no'), $request->get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
	
	public function revice($id) { 

		$data = array();
		$itemmaster = $this->itemmaster->activeItemmasterList();
		$terms = $this->terms->activeTermsList();
		$jobs = $this->jobmaster->activeJobmasterList();
		$currency = $this->currency->activeCurrencyList();
		$orderrow = $this->quotation_sales->findPOdata($id);
		$orditems = $this->quotation_sales->getItems($id);
		$itemdesc = $this->makeTreeArr($this->quotation_sales->getItemDesc($id)); //echo '<pre>';print_r($orderrow);exit;
		$photos = DB::table('quot_fotos')->where('quot_id',$id)->get(); 
		$val = '';
		foreach($photos as $row) {
			$val .= ($val=='')?$row->photo:','.$row->photo;
		}		
		$apr = ($this->acsettings->doc_approve==1)?[1]:[0,1,2];
		if(in_array($orderrow->doc_status, $apr))
			$isprint = true;
		else
			$isprint = null;
		
		if($orderrow->job_type==1) { 
			$parentid = $orderrow->fabrication;
			$qrow = DB::table('quotation_sales')->where('id',$orderrow->fabrication)->select('voucher_no','jobnature')->first();
			$count = $qrow->jobnature + 1;
			$voucherno = $qrow->voucher_no.'/R-'.$count;
		} else { 
			$parentid = $orderrow->id;
			$count = $orderrow->jobnature + 1;
			$voucherno = $orderrow->voucher_no.'/R-'.$count;
		}
		
		
		$print = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','QS')
							->where('report_view_detail.is_default',1)
							->select('report_view_detail.id')
							->first();
		
		//DEC22
		$infodata = DB::table('quotation_sales_info')->where('quotation_sales_id',$id)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->orderBy('id','ASC')->get();
		//echo '<pre>';print_r($infoedit);exit;	
		return view('body.quotationsales.revice')
					->withItems($itemmaster)
					->withTerms($terms)
					->withJobs($jobs)
					->withCurrency($currency)
					->withOrderrow($orderrow)
					->withOrditems($orditems)
					->withItemdesc($itemdesc)
					->withPhotos($val)
					->withVatdata($this->vatdata)
					->withSettings($this->acsettings)
					->withFormdata($this->formData)
					->withIsprint($isprint)
					->withPrint($print)
					->withInfos($infodata)//DEC22
					->withParentid($parentid)
					->withVoucherno($voucherno)
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
	
	public function getQuotations()
	{
		$quotations = $this->quotation_sales->getCustomerQuotation();//print_r($quotations);exit;
		return view('body.quotationsales.quotations')
					->withQuotations($quotations);
	}
	
	public function refreshQS($id) { //SI id
	    
		$itms = DB::table('quotation_sales')->where('quotation_sales.id', $id)
					->join('quotation_sales_item','quotation_sales_item.quotation_sales_id','=','quotation_sales.id')
					->select('quotation_sales_item.id','quotation_sales_item.item_id','quotation_sales_item.quantity','quotation_sales_item.balance_quantity','quotation_sales.id AS do_id')
					->get();

		if($itms) {
			foreach($itms as $row) {
			    
			    $itmsPI = DB::table('sales_order')
					->join('sales_order_item','sales_order_item.sales_order_id','=','sales_order.id')
					->where('sales_order.quotation_id', '>', 0)
					->where('sales_order_item.status', 1)
					->where('sales_order_item.deleted_at', '0000-00-00 00:00:00')
					->where('sales_order.deleted_at', '0000-00-00 00:00:00')
					->select(DB::raw('SUM(sales_order_item.quantity) AS si_quantity'))
					->get();
					
					//echo '<pre>';print_r($itmsPI); exit; 
					
				if(isset($itmsPI[0])) {
				    
				    if($itmsPI[0]->si_quantity == $row->quantity) {
				         DB::table('sales_order_item')->where('id', $row->id)->update(['balance_quantity' => 0, 'is_transfer' => 1]);
				    } else {
				        if($itmsPI[0]->si_quantity > 0) {
				            $balqty = $row->quantity - $itmsPI[0]->si_quantity;
				            DB::table('sales_order_item')->where('id', $row->id)->update(['balance_quantity' => $balqty, 'is_transfer' => 2]);
				        } else {
				            DB::table('sales_order_item')->where('id', $row->id)->update(['balance_quantity' => 0, 'is_transfer' => 0]);
				        }
				    }
				}
				
			}
            

			$row1 = DB::table('quotation_sales_item')->where('quotation_sales_id', $id)->count();
			$row2 = DB::table('quotation_sales_item')->where('quotation_sales_id', $id)->where('is_transfer',1)->count();
			$row3 = DB::table('quotation_sales_item')->where('quotation_sales_id', $id)->where('is_transfer',2)->count(); 
			if($row1==$row2) {
				DB::table('quotation_sales')
						->where('id', $id)
						->update(['is_editable' => 1, 'is_transfer' => 1]);
			} else if($row1 > 0 && $row2==0 && $row3==0) {
			    DB::table('quotation_sales')
						->where('id', $id)
						->update(['is_editable' => 0, 'is_transfer' => 0]);
			} else if($row3 > 0) {
			    DB::table('quotation_sales')
						->where('id', $id)
						->update(['is_editable' => 1, 'is_transfer' => 0]);
			}
		}
		
		return redirect('quotation_sales');
			
	//return true;
	}
	
}


