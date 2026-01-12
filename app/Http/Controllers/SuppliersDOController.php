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
use App\Repositories\ItemUnit\ItemUnitInterface;
use App\Repositories\Forms\FormsInterface;
use App\Repositories\UpdateUtility;

use Illuminate\Http\Request;

use App\Http\Requests;
use Session;
use Response;
use Excel;
use App;
use DB;

class SuppliersDOController extends Controller
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
	protected $item_unit;
	protected $forms;
	protected $mod_autocost;
	
	public function __construct(SupplierDoInterface $supplierdo, PurchaseOrderInterface $purchase_order, ItemmasterInterface $itemmaster, TermsInterface $terms, JobmasterInterface $jobmaster, AccountMasterInterface $accountmaster, CurrencyInterface $currency, VoucherNoInterface $voucherno, AreaInterface $area, ItemUnitInterface $item_unit,  LocationInterface $location, FormsInterface $forms) {
		
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
		$this->item_unit = $item_unit;
		$this->forms = $forms;
		$this->formData = $this->forms->getFormData('GRN');
		
		$this->mod_autocost = DB::table('parameter2')->where('keyname', 'mod_autocost_refresh')->where('status',1)->select('is_active')->first();
		$this->objUtility = new UpdateUtility();
	}
	
    public function index() {
		
		$data = array();
		$orders = $this->supplierdo->suppliersDOList();//echo '<pre>';print_r($orders);exit;
		if(Session::get('department')==1) {
			$departments = DB::table('department')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
			$is_dept = true;
		} else {
			$departments = []; $is_dept = false;
		}
		$sup =DB::table('account_master')->where('category','SUPPLIER')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')
		->select('id','master_name')->get(); 
		$supplier = [];//$this->accountmaster->getSupplierList();
		$jobs = $this->jobmaster->activeJobmasterList();
		$prints = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','SDO')
							->select('report_view_detail.name','report_view_detail.id')
							->get();
							
		return view('body.suppliersdo.index')
					->withOrders($orders)
					->withPrints($prints)
					->withSupplier($supplier)
					->withType('')
					->withJobs($jobs)
					->withSup($sup)
					->withSettings($this->acsettings)
					->withData($data);
	}
	
		
	public function add($id=null, $doctype = null) {

		$data = array();
		$itemmaster = $this->itemmaster->activeItemmasterList();
		$terms = $this->terms->activeTermsList();
		$jobs = $this->jobmaster->activeJobmasterList();
		$currency = $this->currency->activeCurrencyList();
		$location = $this->location->locationList();
		$res = $this->voucherno->getVoucherNo('SDO');
		$location = $this->location->locationList();
		$footertxt = DB::table('header_footer')->where('doc','SDO')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->first();
		$lastid = DB::table('supplier_do')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->orderBy('id','DESC')->select('id')->first();
		$print = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','SDO')
							->where('report_view_detail.is_default',1)
							->select('report_view_detail.id')
							->first();
		$bcurrency = DB::table('parameter1')
							 ->join('currency', 'currency.id', '=', 'parameter1.bcurrency_id')
							  ->select('currency.code')
							 ->first();							
		$vno = $res->no;
		if($id) {
			$ids = explode(',', $id);
			$ocrow = [];
			if($doctype == 'SDO'){
				$itemmaster = $this->itemmaster->activeItemmasterList();
				$terms = $this->terms->activeTermsList();
				$jobs = $this->jobmaster->activeJobmasterList();
				$currency = $this->currency->activeCurrencyList();
				$orderrow = $this->supplierdo->findSDOdata($ids[0]);
				$poitems = $this->supplierdo->getSDOitems($ids);
				$ocrow = $this->purchase_order->getOtherCost($ids[0]);
			}else{
    			$itemmaster = $this->itemmaster->activeItemmasterList();
    			$terms = $this->terms->activeTermsList();
    			$jobs = $this->jobmaster->activeJobmasterList();
    			$currency = $this->currency->activeCurrencyList();
    			$orderrow = $this->purchase_order->findPOdata($ids[0]);
    			$poitems = $this->purchase_order->getPOitems($ids);
    			$ocrow = $this->purchase_order->getOtherCost($ids[0]);
			}
			$total = 0; $discount = 0; $nettotal = 0; $vat_total = 0;
			foreach($poitems as $item) {
				$total += $item->total_price;
				$discount += $item->discount;
				$vat_total += $item->vat_amount;
			}
			$nettotal = $total - $discount + $vat_total; //echo '<pre>';print_r($poitems);exit;
			return view('body.suppliersdo.addpo')
						->withItems($itemmaster)
						->withTerms($terms)
						->withJobs($jobs)
						->withCurrency($currency)
						->withOrderrow($orderrow)
						->withVoucherno($vno)
						->withDocitems($poitems)
						->withLocation($location)
						->withPordid($id)
						->withTotal($total)
						->withDiscount($discount)
						->withNettotal($nettotal)
						->withVattotal($vat_total)
						->withFormdata($this->formData)
						->withVatdata($this->vatdata)
						->withSettings($this->acsettings)
						->withDoctype($doctype)
						->withOcrow($ocrow)
						->withPrint($print)
						->withPrintid($lastid)
						->withLocation($location)
						->withBcurrency(($bcurrency!='')?$bcurrency->code:'')
						->withData($data);
		}
		return view('body.suppliersdo.add')
					->withItems($itemmaster)
					->withTerms($terms)
					->withJobs($jobs)
					->withCurrency($currency)
					->withLocation($location)
					->withVoucherno($vno)
					->withFormdata($this->formData)
					->withVatdata($this->vatdata)
					->withSettings($this->acsettings)
					->withLocation($location)
					->withPrint($print)
					->withPrintid($lastid)
					->withFooter(isset($footertxt)?$footertxt->description:'')
					->withBcurrency(($bcurrency!='')?$bcurrency->code:'')
					->withData($data);
	}
	
	public function save(Request $request, $id = null)
	{
		// ✅ Laravel 10 validation (auto redirect on failure)
		$this->validate(
			$request,
			[
				'supplier_name' => 'required',
				'supplier_id'   => 'required',
				'item_code.*'   => 'required',
				'item_id.*'     => 'required',
				'unit_id.*'     => 'required',
				'quantity.*'    => 'required',
				'cost.*'        => 'required',
			],
			[
				'supplier_name.required' => 'Supplier name is required.',
				'supplier_id.required'   => 'Supplier name is invalid.',
				'item_code.*.required'   => 'Item code is required.',
				'item_id.*.required'     => 'Item code is invalid.',
				'unit_id.*.required'     => 'Item unit is required.',
				'quantity.*.required'    => 'Item quantity is required.',
				'cost.*.required'        => 'Item cost is required.',
			]
		);

		if( $this->supplierdo->create($request->all()) ) {
			//AUTO COST REFRESH CHECK ENABLE OR NOT
			if($this->mod_autocost->is_active==1) {
				$this->objUtility->reEvalItemCostQuantity($request->get('item_id'),$this->acsettings);
				app('App\Http\Controllers\UtilityController')->updateItemCurrentQtyByItems($request->get('item_id'));
			}
			Session::flash('message', 'Suppliers Delivery Order added successfully.');
		} else
			Session::flash('error', 'Something went wrong, Invoice failed to add!');


		
		return redirect('suppliers_do/add');
	}

		
	public function destroy($id)
	{
		if( $this->supplierdo->check_order($id) ) {
			if($this->supplierdo->delete($id)) {
				
				//AUTO COST REFRESH CHECK ENABLE OR NOT
				if($this->mod_autocost->is_active==1) {
					$arritems = [];
					$items = DB::table('supplier_do_item')->where('supplier_do_id',$id)->select('item_id')->get();
					foreach($items as $rw) {
						$arritems[] = $rw->item_id;
					}
					$this->objUtility->reEvalItemCostQuantity($arritems,$this->acsettings);
				}
				
				$this->refreshSDO($id);
				
				Session::flash('message', 'Goods receipt note deleted successfully.');
				
			}
		} else
			Session::flash('error', 'Goods receipt note is already in use, you can\'t delete this!');
			
		return redirect('suppliers_do');
	}
	
	public function checkRefNo(Request $request) {

		$check = $this->supplierdo->check_reference_no($request->get('reference_no'), $request->get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
	
	private function makeTreeArr($result) {
		
		$childs = array();
		foreach($result as $k => $item)
			$childs[$item->invoice_id][$k] = $item;
		
		return $childs;
	}
	
	protected function makeTreeLoc($result)
	{
		$childs = array();
		foreach($result as $item)
			$childs[$item->location_id]= $item;
		
		return $childs;
	}
	
	//NOV24
	protected function makeTreeItmLoc($result)
	{
		$childs = array();
		foreach($result as $item)
			$childs[$item->invoice_id][$item->location_id]= $item;
		
		return $childs;
	}
	
	public function edit($id) { 

		$ids[]=$id;
		$itemmaster = $this->itemmaster->activeItemmasterList();
		$terms = $this->terms->activeTermsList();
		$jobs = $this->jobmaster->activeJobmasterList();
		$currency = $this->currency->activeCurrencyList();
		$orderrow = $this->supplierdo->findPOdata($id);
		$orditems = $this->supplierdo->getItems($id); 
		//echo '<pre>';print_r($ids);  exit;
		$ocrow = $this->supplierdo->getOtherCost($ids);
		$location = $this->location->locationList();

		$getItemLocation =  $this->itemmaster->getItemLocation($id,'SDO'); 
		//$itemlocedit = $this->makeTreeArr( $this->makeTreeLoc($this->itemmaster->getItemLocEdit($id,'SDO')) );  
		//$itemlocedit = $this->makeTreeItmLoc($this->itemmaster->getItemLocEdit($id,'SDO')); //NOV24
		$itemlocedit = $this->makeTreeItmLoc($this->itemmaster->getItemLocEdit($id,'SDO'));
		//echo '<pre>';print_r($getItemLocation);exit;
		$print = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','SDO')
							->where('report_view_detail.is_default',1)
							->select('report_view_detail.id')
							->first();
		$bcurrency = DB::table('parameter1')
							 ->join('currency', 'currency.id', '=', 'parameter1.bcurrency_id')
							  ->select('currency.code')
							 ->first();			
							 
		//MAY25 BATCH ENTRY.....	
		$batch_res = $batchs = $batch_items = null;
		$batch_res = DB::table('batch_log')->whereNull('batch_log.deleted_at')
		                    ->Join('item_batch AS IB', function($join) {
                        		$join->on('IB.id','=','batch_log.batch_id');
                        	})
                        	->where('batch_log.document_type', 'SDO')
                        	->where('batch_log.document_id', $id)
                        	->whereNull('IB.deleted_at')
                        	->select('IB.*','batch_log.doc_row_id','batch_log.log_id','batch_log.id AS batch_log_id')
                        	->orderBy('batch_log.doc_row_id','ASC')->get();
                        	
        $batchs = $this->batchGrouping($batch_res);

		foreach($batchs as $key => $batchrow) {
		    $batchArr = $mfgArr = $expArr = $qtyArr = $idArr = '';
    		foreach($batchrow as $ky => $batch) {
    		    $idArr = ($idArr=='')?$batch->id:$idArr.','.$batch->id;
    		    $batchArr = ($batchArr=='')?$batch->batch_no:$batchArr.','.$batch->batch_no;
    		    $mfgArr = ($mfgArr=='')?date('d-m-Y',strtotime($batch->mfg_date)):$mfgArr.','.date('d-m-Y',strtotime($batch->mfg_date));
    		    $expArr = ($expArr=='')?date('d-m-Y',strtotime($batch->exp_date)):$expArr.','.date('d-m-Y',strtotime($batch->exp_date));
    		    $qtyArr = ($qtyArr=='')?$batch->quantity:$qtyArr.','.$batch->quantity;
    		}
    		$batch_items[$key] = ['ids' => $idArr, 'batches' => $batchArr, 'mfgs' => $mfgArr, 'exps' => $expArr, 'qtys' => $qtyArr];
	    }
	//	echo '<pre>';print_r($batch_items);exit;
		return view('body.suppliersdo.edit')
					->withItems($itemmaster)
					->withOrditems($orditems)
					->withTerms($terms)
					->withJobs($jobs)
					->withCurrency($currency)
					->withOrderrow($orderrow)
					->withSettings($this->acsettings)
					->withFormdata($this->formData)
					->withOcrow($ocrow)
					->withVatdata($this->vatdata)
					->withLocation($location)
					->withItemloc($getItemLocation)
					->withItemlocedit($itemlocedit)
					->withPrint($print)
					->withBcurrency(($bcurrency!='')?$bcurrency->code:'')
					->withBatchitems($batch_items) //MAY25
					->withBatchcount(count($batchs));//MAY25

	}
	
	//MAY25
	protected function batchGrouping($result) {
	    
	    $childs = array();
		foreach($result as $item)
		    $childs[$item->doc_row_id][] = $item;
			
		return $childs;
	}
	
	
	protected function makeTreeName($result)
	{
		$childs = array();
		foreach($result as $item)
		$childs[$item->supplier][] = $item;
			
			//echo '<pre>';print_r($childs);exit;
		return $childs;
	}
	
	protected function makeTree($result)
	{
		$childs = array();
		foreach($result as $item)
			$childs[$item['voucher_no']][] = $item;
		
		return $childs;
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
	
		public function viewonly($id) { 

		$ids[]=$id;
		$itemmaster = $this->itemmaster->activeItemmasterList();
		$terms = $this->terms->activeTermsList();
		$jobs = $this->jobmaster->activeJobmasterList();
		$currency = $this->currency->activeCurrencyList();
		$orderrow = $this->supplierdo->findPOdata($id);
		$orditems = $this->supplierdo->getItems($id); 
		//echo '<pre>';print_r($ids);  exit;
		$ocrow = $this->supplierdo->getOtherCost($ids);
		$location = $this->location->locationList();

		$getItemLocation =  $this->itemmaster->getItemLocation($id,'SDO'); 
		//$itemlocedit = $this->makeTreeArr( $this->makeTreeLoc($this->itemmaster->getItemLocEdit($id,'SDO')) );  
		//$itemlocedit = $this->makeTreeItmLoc($this->itemmaster->getItemLocEdit($id,'SDO')); //NOV24
		$itemlocedit = $this->makeTreeItmLoc($this->itemmaster->getItemLocEdit($id,'SDO'));
		//echo '<pre>';print_r($getItemLocation);exit;
		$print = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','SDO')
							->where('report_view_detail.is_default',1)
							->select('report_view_detail.id')
							->first();
		$bcurrency = DB::table('parameter1')
							 ->join('currency', 'currency.id', '=', 'parameter1.bcurrency_id')
							  ->select('currency.code')
							 ->first();			
							 
		//MAY25 BATCH ENTRY.....	
		$batch_res = $batchs = $batch_items = null;
		$batch_res = DB::table('batch_log')->whereNull('batch_log.deleted_at')
		                    ->Join('item_batch AS IB', function($join) {
                        		$join->on('IB.id','=','batch_log.batch_id');
                        	})
                        	->where('batch_log.document_type', 'SDO')
                        	->where('batch_log.document_id', $id)
                        	->whereNull('IB.deleted_at')
                        	->select('IB.*','batch_log.doc_row_id','batch_log.log_id','batch_log.id AS batch_log_id')
                        	->orderBy('batch_log.doc_row_id','ASC')->get();
                        	
        $batchs = $this->batchGrouping($batch_res);

		foreach($batchs as $key => $batchrow) {
		    $batchArr = $mfgArr = $expArr = $qtyArr = $idArr = '';
    		foreach($batchrow as $ky => $batch) {
    		    $idArr = ($idArr=='')?$batch->id:$idArr.','.$batch->id;
    		    $batchArr = ($batchArr=='')?$batch->batch_no:$batchArr.','.$batch->batch_no;
    		    $mfgArr = ($mfgArr=='')?date('d-m-Y',strtotime($batch->mfg_date)):$mfgArr.','.date('d-m-Y',strtotime($batch->mfg_date));
    		    $expArr = ($expArr=='')?date('d-m-Y',strtotime($batch->exp_date)):$expArr.','.date('d-m-Y',strtotime($batch->exp_date));
    		    $qtyArr = ($qtyArr=='')?$batch->quantity:$qtyArr.','.$batch->quantity;
    		}
    		$batch_items[$key] = ['ids' => $idArr, 'batches' => $batchArr, 'mfgs' => $mfgArr, 'exps' => $expArr, 'qtys' => $qtyArr];
	    }
	//	echo '<pre>';print_r($batch_items);exit;
		return view('body.suppliersdo.viewonly')
					->withItems($itemmaster)
					->withOrditems($orditems)
					->withTerms($terms)
					->withJobs($jobs)
					->withCurrency($currency)
					->withOrderrow($orderrow)
					->withSettings($this->acsettings)
					->withFormdata($this->formData)
					->withOcrow($ocrow)
					->withVatdata($this->vatdata)
					->withLocation($location)
					->withItemloc($getItemLocation)
					->withItemlocedit($itemlocedit)
					->withPrint($print)
					->withBcurrency(($bcurrency!='')?$bcurrency->code:'')
					->withBatchitems($batch_items) //MAY25
					->withBatchcount(count($batchs));//MAY25

	}
	
	public function getSearch(Request $request)
	{
		$data = array();$curcode = '';
		if($request->get('currency_id')!='') {
			$cur = DB::table('currency')->where('id',$request->get('currency_id'))->select('code')->first();
			$curcode = ' in '.$cur->code;
		}
		$pending=($request->get('pending'))?$request->get('pending'):0;
		$reports = $this->supplierdo->getReport($request->all());
		
		if($request->get('search_type')=="summary" && $pending==0)
			$voucher_head = 'Goods Receipt Note Summary'.$curcode;
		elseif($request->get('search_type')=="summary" && $pending==1) {

			$voucher_head = 'Goods Receipt Note Pending Summary'.$curcode;
			$reports = $this->makeArrGroup($reports,$curcode);
            $request->merge(['search_type' => 'summary_pending']); 
            
		} elseif($request->get('search_type')=="detail" && $pending==0) {
			$voucher_head = 'Goods Receipt Note Detail'.$curcode;
			$reports = $this->makeTree($reports);
		} else {
		     if($request->get('search_type')=="detail" && $pending==1 ){
			$voucher_head = 'Goods Receipt Note Pending Detail'.$curcode;
			$request->merge(['search_type' => 'detail_pending']); 
		     }
		     else{
		         	$voucher_head = 'Goods Receipt Note Quantity Detail'.$curcode;
		     }
			$reports = $this->makeTree($reports);
		}
		//echo '<pre>';print_r($reports);exit;
		return view('body.suppliersdo.preprint')
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
	
	
	public function getSearchBkp()
	{
		$data = array();
		$dname = '';
		$supid = $itemid = '';
		$voucher_head  = '';
		//echo '<pre>';print_r($request->get('search_type'));exit;
		//$report = $this->supplierdo->getReport($request->all());
		//echo '<pre>';print_r($report);exit;
	
		
		if($request->get('search_type')=="summary")
		{
			$voucher_head = 'Goods Receipt Note Summary';
			$report = $this->supplierdo->getReport($request->all());
			$reports = $this->makeTreeName($report);
			$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];
			if($request->get('supplier_id')!==null)
				$supid = implode(',', $request->get('supplier_id'));
			else
				$supid = '';
			}
		
		else if($request->get('search_type')=="detail") {
			$voucher_head = 'Goods Receipt Note Detail';
			$report = $this->supplierdo->getReport($request->all());
		    $reports = $this->makeTreeName($report);
			$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];
			
		} 
		//echo '<pre>';print_r($reports);exit;
		return view('body.suppliersdo.preprint')
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
		$reports = $this->supplierdo->getReport($request->all());
		
		if($request->get('search_type')=="summary")
			$voucher_head = 'Goods Receipt Note Summary'.$curcode;
		elseif($request->get('search_type')=="summary_pending") {

			$voucher_head = 'Goods Receipt Note Pending Summary'.$curcode;
			$reports = $this->makeArrGroup($reports,$curcode);

		} elseif($request->get('search_type')=="detail") {
			$voucher_head = 'Goods Receipt Note Detail'.$curcode;
			//$reports = $this->makeTree($reports);
		} elseif($request->get('search_type')=="detail_pending") {
			$voucher_head = 'Goods Receipt Note Pending Detail'.$curcode;
			//$reports = $this->makeTree($reports);
		
		}else{
		    $voucher_head = 'Goods Receipt Note Quantity Detail'.$curcode;
		}
		
		//echo '<pre>';print_r($reports);exit;
		
		$datareport[] = ['','','','',strtoupper($voucher_head), '','',''];
		$datareport[] = ['','','','','','',''];
		
		if($request->get('search_type')=='detail' ) {
			
			$datareport[] = ['SI.No.','GNR#', 'GNR.Ref#', 'Job No', 'Supplier','Item Code','Description','GNR.Qty','Rate','Total Amt.'];
			$i=0;
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
								  'net_amount' => number_format($row['net_amount'],2)
								];
			}
		} 	else if( $request->get('search_type')=='detail_pending') {
			
			$datareport[] = ['SI.No.','GNR#', 'GNR.Ref#', 'Job No', 'Supplier','Item Code','Description','GNR.Qty','Rate','Total Amt.','Inv. Qty','Pending Qty','Rate','Total Amt.'];
			$i=$inv_qty=$pending_qty=$pending_amt=0;
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
								  'net_amount' => number_format($row['quantity']*$row['unit_price'],2),
								  'inv_qty' => $inv_qty,
								    'pending' => $pending_qty,
								    'unit_pric' => number_format($row['unit_price'],2),
								    'pending_amount' => number_format($pending_amt,2)
								];
			}
		} 
		else if($request->get('search_type')=='qty_report')  {
			
			$datareport[] = ['SI.No.','GRN#', 'GRN.Ref#', 'Job No', 'Supplier','Item Code','Description','Ordered','Processed','Balance'];
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
		
		
		else if($request->get('search_type')=='summary') {
			
			$datareport[] = ['SI.No.','GNR#', 'GNR.Ref#', 'Job No', 'Supplier','Gross Amt.','Discount','Total Amt','VAT Amt.','Net Total'];
			$i=0;
			$total=0;$gross=0;$vat=$amt=0;	
		    $tot=0;$gs=0;$vt=$namt=$net_amt=0;
			foreach ($reports as $row) {
					$i++;
					$amt=$row['total']-$row['discount'];
					$datareport[] = [ 'si' => $i,
									  'po' => $row['voucher_no'],
									  'ref' => $row['reference_no'],
									  'jobno' => $row['jobcode'],
									  'supplier' => $row['master_name'],
									  'gross' => number_format($row['total'],2),
									  'disc' => number_format($row['discount'],2),
									  'amt' => number_format($amt,2),
									  'vat' => number_format($row['vat_amount'],2),
									  'total' => number_format($row['net_total'],2)
									];
									$total+= $row['net_total'];
							        $tot=number_format($total,2) ;
									$gross+= $row['total'];
							        $gs=number_format($gross,2) ;
									$vat+= $row['vat_amount'];
							        $vt=number_format($vat,2) ;
							        $net_amt+=$amt;
							        $namt=number_format($net_amt,2) ;
							        
			}
			$datareport[] = ['','','','','','',''];			
			$datareport[] = ['','','','','Total:',$gs,'',$namt,$vt,$tot];
		}
		else  {
			
			$datareport[] = ['SI.No.','GNR#', 'GNR.Ref#', 'Job No', 'Supplier','Gross Amt.','Discount','Total Amt','VAT Amt.','Net Total'];
			$i=0;
			$total=0;$gross=0;$vat=$amt=0;	
		    $tot=0;$gs=0;$vt=$namt=$net_amt=0;
			foreach ($reports as $row) {
					$i++;
					$amt=$row['total']-$row['discount'];
					$datareport[] = [ 'si' => $i,
									  'po' => $row['voucher_no'],
									  'ref' => $row['reference_no'],
									  'jobno' => $row['jobcode'],
									  'supplier' => $row['master_name'],
									  'gross' => number_format($row['total'],2),
									  'disc' => number_format($row['discount'],2),
									  'amt' => number_format($amt,2),
									  'vat' => number_format($row['vat_amount'],2),
									  'total' => number_format($row['net_amount'],2)
									];
									$total+= $row['net_amount'];
							        $tot=number_format($total,2) ;
									$gross+= $row['total'];
							        $gs=number_format($gross,2) ;
									$vat+= $row['vat_amount'];
							        $vt=number_format($vat,2) ;
							        $net_amt+=$amt;
							        $namt=number_format($net_amt,2) ;
							        
			}
			$datareport[] = ['','','','','','',''];			
			$datareport[] = ['','','','','Total:',$gs,'',$namt,$vt,$tot];
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
		
	}
	
		
		
	public function update(Request $request, $id)
	{
		// ✅ Laravel 10 validation (auto redirect on failure)
		$this->validate(
			$request,
			[
				'supplier_name' => 'required',
				'supplier_id'   => 'required',
				'item_code.*'   => 'required',
				'item_id.*'     => 'required',
				'unit_id.*'     => 'required',
				'quantity.*'    => 'required',
				'cost.*'        => 'required',
			],
			[
				'supplier_name.required' => 'Supplier name is required.',
				'supplier_id.required'   => 'Supplier name is invalid.',
				'item_code.*.required'   => 'Item code is required.',
				'item_id.*.required'     => 'Item code is invalid.',
				'unit_id.*.required'     => 'Item unit is required.',
				'quantity.*.required'    => 'Item quantity is required.',
				'cost.*.required'        => 'Item cost is required.',
			]
		);
		
		//echo '<pre>';print_r($sup_udate);exit;
		if($this->supplierdo->update($id, $request->all())) {
			//AUTO COST REFRESH CHECK ENABLE OR NOT
			if($this->mod_autocost->is_active==1) {
				$this->objUtility->reEvalItemCostQuantity($request->get('item_id'),$this->acsettings);
				app('App\Http\Controllers\UtilityController')->updateItemCurrentQtyByItems($request->get('item_id'));
			}
			Session::flash('message', 'Goods receipt note updated successfully');
		}
		return redirect('suppliers_do');
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
	
		public function getJob($id)
	{
		$data = array();
	
			$data = DB::table('supplier_do')->where('supplier_do.supplier_id',$id)
			                    ->join('jobmaster', 'jobmaster.id', '=', 'supplier_do.job_id')
			                   ->where('supplier_do.status',1)->where('supplier_do.deleted_at','0000-00-00 00:00:00')
			                   ->select('jobmaster.id','jobmaster.code')->orderBy('jobmaster.id', 'DESC')->get();
			return $data;
		}
	
	
	public function show($id) { 

		$data = array();
		$acmasterrow = $this->accountmaster->accountMasterView($id);
		//echo '<pre>';print_r($acmasterrow);exit;
		return view('body.accountmaster.view')
					->withMasterrow($acmasterrow)
					->withData($data);
	}
	
	public function getSupplier()
	{
		$data = array();
		$suppliers = $this->accountmaster->getSupplierList();//echo '<pre>';print_r($suppliers);exit;
		return view('body.suppliersdo.supplier')
					->withSuppliers($suppliers)
					->withData($data);
	}
	
	public function getItem($num)
	{
		$data = array();
		$itemmaster = $this->itemmaster->getActiveItemmasterList();
		return view('body.suppliersdo.item')
					->withItems($itemmaster)
					->withNum($num)
					->withData($data);
	}
	
	public function getSDO($supplier_id = null, $url = null)
	{
		$data = array();
		$sdodata = $this->supplierdo->getSDOdata($supplier_id);//echo '<pre>';print_r($sdodata);exit; 
		return view('body.suppliersdo.sdodata')
					->withSdodata($sdodata)
					->withUrl($url)
					->withData($data);
	}
	public function getSupplierDo()
	{
		$data = array();
		$sdodata = $this->supplierdo->getSDOdata();//echo '<pre>';print_r($sdodata);exit; getSupplierDo
		return view('body.suppliersdo.sdo')
					->withSdodata($sdodata)
					->withData($data);
	}
	
	public function testdata()
	{
		//return json_encode(array('name' => 'asdf'));
		$data = array();
		$tdata = array( 0 => array('name' => 'asdf'), 1 => array('name' => 'qwer') );
		return view('body.suppliersdo.testdada')
					->withTdata($tdata)
					->withData($data);
	}
	
	public function checkVchrNo(Request $request) {

		$check = $this->supplierdo->check_voucher_no($request->get('voucher_no'), $request->get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
	
	public function getPrint($id,$rid=null)
	{
		//$viewfile = DB::table('report_view')->where('code', 'PI')->where('status',1)->select('view_name')->first();
		$viewfile = DB::table('report_view_detail')->where('id', $rid)->select('print_name')->first();
		if($viewfile->print_name=='') {
			$attributes['document_id'] = $id;
			$attributes['is_fc'] = ($fc)?1:'';
			$result = $this->purchase_invoice->getInvoice($attributes);
			$titles = ['main_head' => 'Purchase Invoice','subhead' => 'Purchase Invoice'];
			return view('body.purchaseinvoice.print')
						->withDetails($result['details'])
						->withTitles($titles)
						->withFc($attributes['is_fc'])
						->withId($id)
						->withItems($result['items']);
		} else {
					
			$path = app_path() . '/stimulsoft/helper.php';
			
			if(env('STIMULSOFT_VER')==2)
		        return view('body.reports')->withPath($path)->withView($viewfile->print_name);
		   else
		        return view('body.suppliersdo.viewer')->withPath($path)->withView($viewfile->print_name);
			
			//return view('body.suppliersdo.viewer')->withPath($path)->withView($viewfile->print_name);
		}
		
	}
	
	public function getItemDetails($id) {
		$data = array();
		$items = $this->supplierdo->getPOitems(array($id));
		return view('body.suppliersdo.itemdetails')
					->withItems($items)
					->withData($data);
	}
	
	public function refreshSDO($id) { //SI id
	    
		//GETTING SALES INVOICE ITEMS WITH TOTAL QUANTITY TRANSFERED...
		$itms = DB::table('supplier_do')->where('supplier_do.id', $id)
					->join('supplier_do_item','supplier_do_item.supplier_do_id','=','supplier_do.id')
					->select('supplier_do_item.id','supplier_do_item.item_id','supplier_do_item.quantity','supplier_do_item.balance_quantity','supplier_do.id AS sdo_id')
					->get();
//echo '<pre>';print_r($itms); exit;

		if($itms) {
			foreach($itms as $row) {
			    
			    $itmsPI = DB::table('purchase_invoice')
					->join('purchase_invoice_item','purchase_invoice_item.purchase_invoice_id','=','purchase_invoice.id')
					//->where('purchase_invoice.document_id', $row->sdo_id)
					->where('purchase_invoice.document_type','SDO')
					->where('purchase_invoice_item.doc_row_id', $row->id)
					->where('purchase_invoice_item.status', 1)
					->where('purchase_invoice_item.deleted_at', '0000-00-00 00:00:00')
					->where('purchase_invoice.deleted_at', '0000-00-00 00:00:00')
					->select(DB::raw('SUM(purchase_invoice_item.quantity) AS pi_quantity'))
					->get();
					
					//echo '<pre>';print_r($itmsPI); exit;
					
				if(isset($itmsPI[0])) {
				    
				    if($itmsPI[0]->pi_quantity == $row->quantity) {
				         DB::table('supplier_do_item')->where('id', $row->id)->update(['balance_quantity' => 0, 'is_transfer' => 1]);
				    } else {
				        if($itmsPI[0]->pi_quantity > 0) {
				            $balqty = $row->quantity - $itmsPI[0]->pi_quantity;
				            DB::table('supplier_do_item')->where('id', $row->id)->update(['balance_quantity' => $balqty, 'is_transfer' => 2]);
				        } else {
				            DB::table('supplier_do_item')->where('id', $row->id)->update(['balance_quantity' => 0, 'is_transfer' => 0]);
				        }
				    }
				}
				
			}
            

			$row1 = DB::table('supplier_do_item')->where('supplier_do_id', $id)->count();
			$row2 = DB::table('supplier_do_item')->where('supplier_do_id', $id)->where('is_transfer',1)->count();
			$row3 = DB::table('supplier_do_item')->where('supplier_do_id', $id)->where('is_transfer',2)->count();
			if($row1==$row2) {
				DB::table('supplier_do')
						->where('id', $id)
						->update(['is_editable' => 1, 'is_transfer' => 1]);
			} else if($row1 > 0 && $row2==0 && $row3==0) {
			    DB::table('supplier_do')
						->where('id', $id)
						->update(['is_editable' => 0, 'is_transfer' => 0]);
			} else if($row3 > 0) {
			    DB::table('supplier_do')
						->where('id', $id)
						->update(['is_editable' => 1, 'is_transfer' => 0]);
			}
		}
		
		return redirect('suppliers_do');
			
	//return true;
	}
	
}

