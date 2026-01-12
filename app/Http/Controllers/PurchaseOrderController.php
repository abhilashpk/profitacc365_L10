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
use App\Repositories\AccountSetting\AccountSettingInterface;
use App\Repositories\Country\CountryInterface;
use App\Repositories\Acgroup\AcgroupInterface;
use App\Repositories\Forms\FormsInterface;
use App\Repositories\MaterialRequisition\MaterialRequisitionInterface;
use App\Repositories\Location\LocationInterface;
use App\Repositories\SalesOrder\SalesOrderInterface;
use Illuminate\Support\Facades\Session;

use Illuminate\Http\Request;

use App\Http\Requests;
//use Session;
use Response;
use Excel;
use App;
use DB;
use Auth;
class PurchaseOrderController extends Controller
{

	protected $area;
	protected $itemmaster;
	protected $terms;
	protected $jobmaster;
	protected $accountmaster;
	protected $currency;
	protected $voucherno;
	protected $purchase_order;
	protected $country;
	protected $group;
	protected $forms;
	protected $formData;
	protected $material_requisition;
	protected $location;
	protected $sales_order;
	
	public function __construct(SalesOrderInterface $sales_order, PurchaseOrderInterface $purchase_order, ItemmasterInterface $itemmaster, TermsInterface $terms, JobmasterInterface $jobmaster, AccountMasterInterface $accountmaster, CurrencyInterface $currency, VoucherNoInterface $voucherno, AreaInterface $area,CountryInterface $country,AcgroupInterface $group,FormsInterface $forms,LocationInterface $location,MaterialRequisitionInterface $material_requisition) {
		
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
		$this->country = $country;
		$this->group = $group;
		$this->forms = $forms; 
		$this->formData = $this->forms->getFormData('PO');
		$this->material_requisition = $material_requisition;
		$this->location = $location;
		$this->sales_order = $sales_order;
		
		$this->mod_unit_serviceitem = DB::table('parameter2')->where('keyname', 'mod_unit_serviceitem')->where('status',1)->select('is_active')->first();
		
	}
	
    public function index() {
		
		$data = array();
		$orders = [];//$this->purchase_order->purchaseOrderList1();
		$jobs = $this->jobmaster->activeJobmasterList();
		//$sup = $this->accountmaster->supplierList();
		$sup =DB::table('account_master')->where('category','SUPPLIER')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')
		->select('id','master_name')->get(); 
		//echo '<pre>';print_r($sup);exit;
		return view('body.purchaseorder.index')
					->withOrders($orders)
					->withJobs($jobs)
					->withSup($sup)
					->withSettings($this->acsettings)
					->withData($data);
	}
	
	public function ajaxPaging(Request $request)
	{
		$columns = array( 
                            0 =>'purchase_order.id', 
                            1 =>'voucher_no',
							2 =>'reference_no',
                            3 => 'voucher_date',
                            4 => 'supplier',
                            5 => 'net_amount',
                            6=>'approval',
							7=>'status'
                        );
						
		$totalData = $this->purchase_order->purchaseOrderListCount();
            
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = 'purchase_order.id';
        $dir = 'desc';
		$search = (empty($request->input('search.value')))?null:$request->input('search.value');
        
		$invoices = $this->purchase_order->purchaseOrderList('get', $start, $limit, $order, $dir, $search);
		
		if($search)
			$totalFiltered =  $this->purchase_order->purchaseOrderList('count', $start, $limit, $order, $dir, $search);
		
		$prints = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','PO')
							->select('report_view_detail.name','report_view_detail.id')
							->get();
							
        $data = array();
        if(!empty($invoices))
        {
           
			foreach ($invoices as $row)
            {
                $edit =  '"'.url('purchase_order/edit/'.$row->id).'"';
                $edits =  url('purchase_order/edit/'.$row->id);
                $editd =  '"'.url('purchase_order/edit_draft/'.$row->id).'"';
                $delete =  'funDelete("'.$row->id.'","'.$row->is_editable.'")'; //("'.$row->id.','.$row->is_editable.'")';
				$print = url('purchase_order/print/'.$row->id);
				$view =  url('purchase_order/views/'.$row->id);
				$viewonly =  url('purchase_order/viewonly/'.$row->id);
				$refresh =  url('purchase_order/refresh_po/'.$row->id);
				
				$settlement =  url('purchase_order/settlement/'.$row->id);
					if($row->approval_status==1){
					$status=1;

				}
				else{
					$status=0;
				}
				
                $nestedData['id'] = $row->id;
                $nestedData['voucher_no'] = $row->voucher_no;
				$nestedData['voucher_date'] = date('d-m-Y', strtotime($row->voucher_date));
				$nestedData['supplier'] = ($row->supplier=='CASH CUSTOMERS') ? (($row->supplier_name!='')?$row->supplier.'('.$row->supplier_name.')':$row->supplier) : $row->supplier;
				$nestedData['net_total'] = $row->net_amount;
				$nestedData['approval'] = ($row->approval_status==1)?'Approved':'Not Approved';
               
				if($row->is_draft==1) {								
			 $nestedData['edit'] = "<p><button class='btn btn-primary btn-xs' onClick='location.href={$editd}'>
												<span class='glyphicon glyphicon-pencil'></span></button></p>";						
				}													
				else if($row->is_settled==1){
				$nestedData['edit'] = "<p><button class='btn btn-primary btn-xs' onClick='location.href={$edit}'>
												<span class='glyphicon glyphicon-pencil'></span></button></p>";
				}else{								
				$nestedData['edit'] = "<div class='btn-group drop_btn' role='group'>
												<button type='button' class='btn btn-primary btn-xs dropdown-toggle m-r-50'
													id='exampleIconDropdown1' data-toggle='dropdown' aria-expanded='false'>
													<i class='fa fa-fw fa-shield'></i><span class='caret'></span>
												</button>
												<ul style='min-width:100px !important;' class='dropdown-menu' aria-labelledby='exampleIconDropdown1' role='menu'>
													<li role='presentation'><a href='{$edits}' role='menuitem'>Edit</a></li>
													<li role='presentation'><a href='{$refresh}' role='menuitem'>Refresh</a></li>
													<li role='presentation'><a href='{$settlement}' role='menuitem'>Settlement</a></li>
												</ul>
											</div>";								
				}							
				if(Auth::user()->roles[0]->name == "Admin"){
			    $nestedData['view'] = "<p><a href='{$view}' class='btn btn-info btn-xs' target='_blank'><i class='fa fa-fw fa-check-square'></i></a></p>";								
			 }else{
			 $nestedData['view']='';
			 }
               $nestedData['viewonly'] = "<p><a href='{$viewonly}' class='btn btn-info btn-xs' target='_blank'><i class='glyphicon glyphicon-eye-open'></i></a></p>";
			 $nestedData['status']=$status;	
												
				$nestedData['delete'] = "<button class='btn btn-danger btn-xs delete' onClick='{$delete}'>
												<span class='glyphicon glyphicon-trash'></span>";
				
				$opts = '';					
				foreach($prints as $doc) {
					$opts .= "<li role='presentation'><a href='{$print}/".$doc->id."' target='_blank' role='menuitem'>".$doc->name."</a></li>";
				}
				$printfc = url('purchase_order/printfc/'.$row->id.'/'.$prints[0]->id);
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
	
	
	public function add($id = null, $doctype = null) {

		$data = array();
		$itemmaster = $this->itemmaster->activeItemmasterList();
		$terms = $this->terms->activeTermsList();
		$jobs = $this->jobmaster->activeJobmasterList();
		$currency = $this->currency->activeCurrencyList();
		$res = $this->voucherno->getVoucherNo('PO');
		$vno = $res->no;
		$lastid = DB::table('purchase_order')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->orderBy('id','DESC')->select('id')->first();
	    //echo '<pre>';print_r($res);exit;
		$location = $this->location->locationList();
		
		$footertxt = DB::table('header_footer')->where('doc','PO')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->first();
		$bcurrency = DB::table('parameter1')
							 ->join('currency', 'currency.id', '=', 'parameter1.bcurrency_id')
							  ->select('currency.code')
							 ->first();					 
					 
		
		$print = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','PO')
							->where('report_view_detail.is_default',1)
							->select('report_view_detail.id')
							->first();
		
		if($id) {
			$ids = explode(',', $id);
			$ocrow = [];
			if($doctype=='PO') {
			$docRow = $this->purchase_order->findOrderData($ids[0]);
			$docItems = $this->purchase_order->getPOitems($ids);//echo '<pre>';print_r($quoteRow);exit;
			$itemmaster = $this->itemmaster->activeItemmasterList();
			$terms = $this->terms->activeTermsList();
			$jobs = $this->jobmaster->activeJobmasterList();
			$currency = $this->currency->activeCurrencyList();
			$ocrow = $this->purchase_order->getOtherCost($ids[0]);
			} else {
			$itemmaster = $this->itemmaster->activeItemmasterList();
			$terms = $this->terms->activeTermsList();
			$jobs = $this->jobmaster->activeJobmasterList();
			$currency = $this->currency->activeCurrencyList();
			$docRow = $this->material_requisition->findMRdata($ids[0]);
			$docItems = $this->material_requisition->getMRitems($ids);
			$ocrow = $this->purchase_order->getOtherCost($ids[0]);
			}
			//echo '<pre>';print_r($docItems);exit;
			$total = 0; $discount = 0; $nettotal = 0; $vat_total = 0;
			foreach($docItems as $item) {
				$total += $item->total_price;
				$discount += $item->discount;
				$vat_total += $item->vat_amount;
			}
			$nettotal = $total - $discount + $vat_total;
			
			return view('body.purchaseorder.addmr')
						->withItems($itemmaster)
						->withTerms($terms)
						->withJobs($jobs)
						->withCurrency($currency)
						->withVoucherno($res)
						->withVoucherno($vno)
						->withSettings($this->acsettings)
						->withVatdata($this->vatdata)
						->withPrintid($lastid)
						->withFormdata($this->formData)
						->withPrint($print)
						->withDocrow($docRow)
						->withDocitems($docItems)
						->withPordid($id)
						->withTotal($total)
						->withDiscount($discount)
						->withOcrow($ocrow)
						->withVattotal($vat_total)
						->withDoctype($doctype)
						->withPurchaseOrderno(Session::get('voucher_no'))
						->withVoucherdt(Session::get('voucher_date'))
						->withReferenceno(Session::get('reference_no'))
						->withSettings($this->acsettings)
						->withLocation($location)
						->withBcurrency(($bcurrency!='')?$bcurrency->code:'')
						->withData($data);
		}
		
		return view('body.purchaseorder.add')
					->withItems($itemmaster)
					->withTerms($terms)
					->withJobs($jobs)
					->withCurrency($currency)
					->withVoucherno($res)
					->withSettings($this->acsettings)
					->withVatdata($this->vatdata)
					->withPrintid($lastid)
					->withFormdata($this->formData)
					->withPrint($print)
					->withLocation($location)
					->withData($data)
					->withBcurrency(($bcurrency!='')?$bcurrency->code:'')
					->withFooter(isset($footertxt)?$footertxt->description:'');
	}
	
	public function save(Request $request)
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

		// ✅ Only runs if validation PASSES
		if ($this->purchase_order->create($request->all())) {
			Session::flash('message', 'Purchase order added successfully.');
		} else {
			Session::flash('error', 'Something went wrong, Invoice failed to add!');
		}

		return redirect('purchase_order/add');
	}

	
	public function Settlement($id){
		    DB::table('purchase_order')->where('id',$id)->update(['is_settled' => 1]);
		    Session::flash('message', 'Purchase order is settled.');
		    return redirect('purchase_order');
       }
       
       public function saveDraft(Request $request) {
		
		//echo '<pre>';print_r($request->all());exit;
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
			 'supplier_name.required' => 'Supplier name is required.','supplier_id.required' => 'Supplier name is invalid.',
			 'item_code.*.required'   => 'Item code is required.', 'item_id.*' => 'Item code is invalid.',
			 'unit_id.*' => 'Item unit is required.',
			 'quantity.*' => 'Item quantity is required.',
			 'cost.*' => 'Item cost is required.'
			]
		)) {

			return redirect('purchase_order/add')->withInput()->withErrors();
		}
		
		if( $this->purchase_order->create($request->all()) )
			Session::flash('message', 'Purchase order draft added successfully.');
		else
			Session::flash('error', 'Something went wrong, Invoice failed to add!');
		
		return redirect('purchase_order/add');
	}
	
	public function destroy($id)
	{
		if( $this->purchase_order->check_order($id) ) {
			$this->purchase_order->delete($id);
			Session::flash('message', 'Purchase order deleted successfully.');
		} else
			Session::flash('error', 'Purchase order is already in use, you can\'t delete this!');
			
		return redirect('purchase_order');
	}
	
	public function checkRefNo(Request $request) {

		$check = $this->purchase_order->check_reference_no($request->get('reference_no'), $request->get('id'));
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
		$orderrow = $this->purchase_order->findPOdata($id);
		$orditems = $this->purchase_order->getItems($id); //echo '<pre>';print_r($orderrow);exit;
		$location = $this->location->locationList();
		$ocrow = $this->purchase_order->getOtherCost($id);
		$itemdesc = $this->makeTreeArr($this->purchase_order->getItemDesc($id));
		//echo '<pre>';print_r(	$orderrow);exit;
		
		$bcurrency = DB::table('parameter1')
							 ->join('currency', 'currency.id', '=', 'parameter1.bcurrency_id')
							  ->select('currency.code')
							 ->first();					 
					 
		$print = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','PO')
							->where('report_view_detail.is_default',1)
							->select('report_view_detail.id')
							->first();
							
		return view('body.purchaseorder.edit')
					->withItems($itemmaster)
					->withTerms($terms)
					->withOrditems($orditems)
					->withJobs($jobs)
					->withOcrow($ocrow)
					->withCurrency($currency)
					->withOrderrow($orderrow)
					->withVatdata($this->vatdata)
					->withFormdata($this->formData)
					->withPrint($print)
					->withLocation($location)
					->withSettings($this->acsettings)
					->withItemdesc($itemdesc)
					->withBcurrency(($bcurrency!='')?$bcurrency->code:'')
					->withData($data);

	}
	
	public function update(Request $request)
{
    // Get ID
    $id = $request->input('purchase_order_id');

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

    // ✅ Only executes if validation PASSES
    if ($this->purchase_order->update($id, $request->all())) {
        Session::flash('message', 'Purchase Order updated successfully');
    } else {
        Session::flash('error', 'Something went wrong, Order failed to update!');
    }

    return redirect('purchase_order');
}

	
	
	public function editDraft($id) { 

		$data = array();
		$itemmaster = $this->itemmaster->activeItemmasterList();
		$terms = $this->terms->activeTermsList();
		$jobs = $this->jobmaster->activeJobmasterList();
		$currency = $this->currency->activeCurrencyList();
		$orderrow = $this->purchase_order->findPOdata($id);
		$orditems = $this->purchase_order->getItems($id); //echo '<pre>';print_r($orderrow);exit;
		$location = $this->location->locationList();
		$ocrow = $this->purchase_order->getOtherCost($id);
		$itemdesc = $this->makeTreeArr($this->purchase_order->getItemDesc($id));
		//echo '<pre>';print_r(	$orderrow);exit;
		
		$bcurrency = DB::table('parameter1')
							 ->join('currency', 'currency.id', '=', 'parameter1.bcurrency_id')
							  ->select('currency.code')
							 ->first();					 
					 
		$print = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','PO')
							->where('report_view_detail.is_default',1)
							->select('report_view_detail.id')
							->first();
							
		return view('body.purchaseorder.edit-draft')
					->withItems($itemmaster)
					->withTerms($terms)
					->withOrditems($orditems)
					->withJobs($jobs)
					->withOcrow($ocrow)
					->withCurrency($currency)
					->withOrderrow($orderrow)
					->withVatdata($this->vatdata)
					->withFormdata($this->formData)
					->withPrint($print)
					->withLocation($location)
					->withSettings($this->acsettings)
					->withItemdesc($itemdesc)
					->withBcurrency(($bcurrency!='')?$bcurrency->code:'')
					->withData($data);

	}
	
	public function updateDraft(Request $request)
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
			 'supplier_name.required' => 'Supplier name is required.','supplier_id.required' => 'Supplier name is invalid.',
			 'item_code.*.required'   => 'Item code is required.', 'item_id.*' => 'Item code is invalid.',
			 'unit_id.*' => 'Item unit is required.',
			 'quantity.*' => 'Item quantity is required.',
			 'cost.*' => 'Item cost is required.'
			]
		)) {

			return redirect('purchase_order/edit/'.$id)->withInput()->withErrors();
		}
		
		if($this->purchase_order->update($id, $request->all()))
			Session::flash('message', 'Purchase Order draft updated successfully');
		else
			Session::flash('error', 'Something went wrong, Order failed to update!');
		
		return redirect('purchase_order');
	}
	
	public function viewonly($id) { 
		$data = array();
		$itemmaster = $this->itemmaster->activeItemmasterList();
		$terms = $this->terms->activeTermsList();
		$jobs = $this->jobmaster->activeJobmasterList();
		$currency = $this->currency->activeCurrencyList();
		$orderrow = $this->purchase_order->findPOdata($id);
		$orditems = $this->purchase_order->getItems($id); //echo '<pre>';print_r($orderrow);exit;
		$location = $this->location->locationList();
		$ocrow = $this->purchase_order->getOtherCost($id);
		$itemdesc = $this->makeTreeArr($this->purchase_order->getItemDesc($id));
		//echo '<pre>';print_r(	$orderrow);exit;
		
		$bcurrency = DB::table('parameter1')
							 ->join('currency', 'currency.id', '=', 'parameter1.bcurrency_id')
							  ->select('currency.code')
							 ->first();					 
					 
		$print = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','PO')
							->where('report_view_detail.is_default',1)
							->select('report_view_detail.id')
							->first();
							
		return view('body.purchaseorder.viewonly')
					->withItems($itemmaster)
					->withTerms($terms)
					->withOrditems($orditems)
					->withJobs($jobs)
					->withOcrow($ocrow)
					->withCurrency($currency)
					->withOrderrow($orderrow)
					->withVatdata($this->vatdata)
					->withFormdata($this->formData)
					->withPrint($print)
					->withLocation($location)
					->withSettings($this->acsettings)
					->withItemdesc($itemdesc)
					->withBcurrency(($bcurrency!='')?$bcurrency->code:'')
					->withData($data);



	}
	
		public function getViews($id) { 
		$data = array();
		$itemmaster = $this->itemmaster->activeItemmasterList();
		$terms = $this->terms->activeTermsList();
		$jobs = $this->jobmaster->activeJobmasterList();
		$currency = $this->currency->activeCurrencyList();
		$orderrow = $this->purchase_order->findPOdata($id);
		$orditems = $this->purchase_order->getItems($id); //echo '<pre>';print_r($orderrow);exit;
		$location = $this->location->locationList();
		$ocrow = $this->purchase_order->getOtherCost($id);
		$itemdesc = $this->makeTreeArr($this->purchase_order->getItemDesc($id));
		//echo '<pre>';print_r(	$orderrow);exit;
		
		$bcurrency = DB::table('parameter1')
							 ->join('currency', 'currency.id', '=', 'parameter1.bcurrency_id')
							  ->select('currency.code')
							 ->first();					 
					 
		$print = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','PO')
							->where('report_view_detail.is_default',1)
							->select('report_view_detail.id')
							->first();
							
		return view('body.purchaseorder.viewapproval')
					->withItems($itemmaster)
					->withTerms($terms)
					->withOrditems($orditems)
					->withJobs($jobs)
					->withOcrow($ocrow)
					->withCurrency($currency)
					->withOrderrow($orderrow)
					->withVatdata($this->vatdata)
					->withFormdata($this->formData)
					->withPrint($print)
					->withLocation($location)
					->withSettings($this->acsettings)
					->withItemdesc($itemdesc)
					->withBcurrency(($bcurrency!='')?$bcurrency->code:'')
					->withData($data);



	}
	public function getApproval($id)
	{
		DB::table('purchase_order')->where('id',$id)->update(['approval_status' => 1]);
		Session::flash('message', 'Purchase Order approved successfully.');
		return redirect('purchase_order');
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
	
	public function show($id) { 

		$data = array();
		$acmasterrow = $this->accountmaster->accountMasterView($id);
		//echo '<pre>';print_r($acmasterrow);exit;
		return view('body.accountmaster.view')
					->withMasterrow($acmasterrow)
					->withData($data);
	}
	
	public function getSupplier($txt=null)
	{
		$data = array();
		$suppliers = $this->accountmaster->getSupplierList($txt);//echo '<pre>';print_r($suppliers);exit;
		$country = $this->country->activeCountryList();
		$area = $this->area->activeAreaList();
		$sup_code = json_decode($this->ajax_getcode($category='SUPPLIER'));
		return view('body.purchaseorder.supplier')
					->withSuppliers($suppliers)
					->withArea($area)
					->withSupid($sup_code->code)
					->withCategory($sup_code->category)
					->withCountry($country)
					->withDeptid(0)
					->withFormdata($this->formData)
					->withData($data);
	}
	
	
	public function getSupplierDept($did=null)
	{
		$data = array();
		$suppliers = $this->accountmaster->getSupplierListDept($did);//echo '<pre>';print_r($suppliers);exit;
		$country = $this->country->activeCountryList();
		$area = $this->area->activeAreaList();
		$sup_code = json_decode($this->ajax_getcode($category='SUPPLIER'));
		return view('body.purchaseorder.supplier')
					->withSuppliers($suppliers)
					->withArea($area)
					->withSupid($sup_code->code)
					->withCategory($sup_code->category)
					->withCountry($country)
					->withDeptid($did)
					->withData($data);
	}
	
	
	public function getItem($num)
	{
		$data = array();
		$itemmaster = $this->itemmaster->getActiveItemmasterList();
		return view('body.purchaseorder.item')
					->withItems($itemmaster)
					->withNum($num)
					->withData($data);
	}
	
	public function getPO($supplier_id = null, $url = null)
	{
		$data = array();
		$podata = $this->purchase_order->getPOdata($supplier_id);
		return view('body.purchaseorder.podata')
					->withPodata($podata)
					->withUrl($url)
					->withData($data);
	}
	
	public function getPOt($supplier_id = null, $url = null)
	{
		$data = array();
		$podata = $this->purchase_order->getPOdata($supplier_id);
		return view('body.purchaseorder.podatatrans')
					->withPodata($podata)
					->withUrl($url)
					->withData($data);
	}
	public function getMR($url = null)
	{
		$data = array();
		if($url=='MRO') {
		    $mrdata = $this->material_requisition->getMRdata(); 
		    $view = 'mrdata';
		} else {
		    $mrdata = DB::table('sales_order')
		            ->join('account_master', 'account_master.id','=','sales_order.customer_id')
		             ->where('sales_order.status', 1)
					 ->where('sales_order.is_transfer_po', 0)
                     ->select('account_master.master_name','sales_order.id','sales_order.voucher_no','sales_order.voucher_date','sales_order.net_total')->get();
                     
		    $view = 'sodata';
		}   
	//	echo '<pre>';print_r($mrdata);exit;
		return view('body.purchaseorder.'.$view)
					->withMrdata($mrdata)
					->withUrl($url)
					->withData($data);
	}
	
	
	
	
	public function checkVchrNo(Request $request) { 

		$check = $this->purchase_order->check_voucher_no($request->get('voucher_no'), $request->get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
	public function getItemDetails($id) {
		$data = array();
		$items = $this->purchase_order->getPOitems(array($id));
		return view('body.purchaseorder.itemdetails')
					->withItems($items)
					->withData($data);
	}
	public function getOrder($supplier_id, $url)
	{
		$data = array();
		$orders = $this->purchase_order->getCustomerOrder($supplier_id);
		return view('body.purchaseorder.order')
					->withOrders($orders)
					->withUrl($url)
					->withData($data);
	}
	public function getOrderHistory($supplier_id)
	{
		$data = array();
		$items = $this->purchase_order->getOrderHistory($supplier_id);//echo '<pre>';print_r($items);exit;
		return view('body.purchaseorder.history')
					->withItems($items)
					->withData($data);
	}
	
	public function getUnit($id=null)
	{
		$data = array();
		if($id) {
			$row = DB::table('itemmaster')->where('id',$id)->select('class_id')->first();
			if($row->class_id==2) {
			   $data = DB::table('units')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','unit_name')->get();
			   return $data;
			} else {
				$data = $this->itemmaster->getUnits($id); 
				return $data;
				
			}
		} else {
			$data = DB::table('units')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','unit_name')->get();
			return $data;
		}
		
		/* if($data) {
			$unit = array();
			foreach($data as $val) {
				$unit[$val['id']] = $val['unit_name'];
			}
			return $unit;
		} else 
			return null; */
		
		//echo '<pre>';print_r($unit);
		//return $tdata = array( 'as9' => 'test1', 'dg6' => 'test2', 'bd8' => 'test3' );
		
	}
	public function getPurchaseOrder()
	{
		$data = array();
		$orders = $this->purchase_order->getPOdata();
		//echo '<pre>';print_r($orders);exit;
		return view('body.purchaseorder.orders')
					->withOrders($orders)
					->withData($data);
	}
	
	
	public function getPrint($id,$rid=null)
	{
		//$viewfile = DB::table('report_view')->where('code', 'PO')->where('status',1)->select('view_name')->first();
		$viewfile = DB::table('report_view_detail')->where('id', $rid)->select('print_name')->first();
		//echo '<pre>';print_r($viewfile);
		if($viewfile->print_name=='') {
			$attributes['document_id'] = $id;
			$attributes['is_fc'] = ($fc)?1:'';
			$result = $this->purchase_order->getOrder($attributes);
			$titles = ['main_head' => 'Purchase Order','subhead' => 'Purchase Order'];
			return view('body.purchaseorder.print')
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
			        return view('body.purchaseorder.viewer')->withPath($path)->withView($viewfile->print_name);
		}
	}
	
	
	public function getPrintFc($id,$rid=null)
	{
		$viewfile = DB::table('report_view_detail')->where('id', $rid)->select('print_name')->first();
		if($viewfile->print_name=='') {
			$attributes['document_id'] = $id;
			$attributes['is_fc'] = ($fc)?1:'';
			$result = $this->purchase_order->getOrder($attributes);
			$titles = ['main_head' => 'Purchase Order','subhead' => 'Purchase Order'];
			return view('body.purchaseorder.print')
						->withDetails($result['details'])
						->withTitles($titles)
						->withFc($attributes['is_fc'])
						->withId($id)
						->withItems($result['items']);
		} else {
					
			$path = app_path() . '/stimulsoft/helper.php';
			$arr = explode('.',$viewfile->print_name);
			$viewname = $arr[0].'FC.mrt';
			
			return view('body.purchaseorder.viewer')->withPath($path)->withView($viewname);
		}
		
	}
	
	
	public function report()
	{
		$data = array(); $reports = null;
		$voucher_head = 'Purchase Order Report';
		return view('body.purchaseorder.report')
					->withReports($reports)
					->withVoucherhead($voucher_head)
					->withType('')
					->withFromdate('')
					->withTodate('')
					->withData($data);
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
	
	public function getSearch(Request $request)
	{
		$data = array();//echo '<pre>';print_r($request->all());exit;
		$curcode = '';
		if($request->get('currency_id')!='') {
			$cur = DB::table('currency')->where('id',$request->get('currency_id'))->select('code')->first();
			$curcode = ' in '.$cur->code;
		}
		$pending=($request->get('pending'))?$request->get('pending'):0;
		$reports = $this->purchase_order->getPendingReport($request->all());
	//echo '<pre>';print_r($reports);exit;
		if($request->get('search_type')=="summary"  && $pending==0)
			$voucher_head = 'Purchase Order Summary'.$curcode;
		elseif($request->get('search_type')=="summary"  && $pending==1) {
            $request->merge(['search_type' => 'summary_pending']);
			$voucher_head = 'Purchase Order Pending Summary'.$curcode;
			$reports = $this->makeArrGroup($reports,$curcode);

		} elseif($request->get('search_type')=="detail"  && $pending==0) {
			$voucher_head = 'Purchase Order Detail'.$curcode;
			
			$reports = $this->makeTree($reports);
		} else {
		    if($request->get('search_type')=="detail"  && $pending==1){
			$voucher_head = 'Purchase Order Pending Detail'.$curcode;
		    }
		    else{
		       $voucher_head = 'Purchase Order Quantity Detail'.$curcode; 
		    }
			$reports = $this->makeTree($reports);
			$request->merge(['search_type' => 'detail_pending']); 
		}
		//	echo '<pre>';print_r($reports);exit;
		return view('body.purchaseorder.preprint')
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
	
	public function getSearch2(Request $request)
	{
		$data = array();$curcode = '';
		if($request->get('currency_id')!='') {
			$cur = DB::table('currency')->where('id',$request->get('currency_id'))->select('code')->first();
			$curcode = ' in '.$cur->code;
		}
		
		//$report = $this->purchase_order->getPendingReport($request->all());
		
		if($request->get('search_type')=="summary")
		{
			$report = $this->purchase_order->getPendingReport($request->all());
			$voucher_head = 'Purchase Order Summary'.$curcode;
			$reports = $this->makeTree($report);
		
		}
			
		elseif($request->get('search_type')=="summary_pending") {
			$report = $this->purchase_order->getPendingReport($request->all());
			$voucher_head = 'Purchase Order Pending Summary'.$curcode;
			$reports = $this->makeArrGroup($report,$curcode);

		} elseif($request->get('search_type')=="detail") {
			$report = $this->purchase_order->getPendingReport($request->all());
			$voucher_head = 'Purchase Order Detail'.$curcode;
			$reports = $this->makeTree($report);
		} else {
			$report = $this->purchase_order->getPendingReport($request->all());
			$voucher_head = 'Purchase Order Pending Detail'.$curcode;
			$reports = $this->makeTree($report);
		}
		$titles = ['main_head' => 'Purchase','subhead' => $voucher_head ];
		//echo '<pre>';print_r($reports);exit;
		return view('body.purchaseorder.preprint')
					->withReports($reports)
					->withVoucherhead($voucher_head)
					->withType($request->get('search_type'))
					->withFromdate($request->get('date_from'))
					->withTodate($request->get('date_to'))
					->withTitles($titles)
					->withI(0)
					->withSettings($this->acsettings)
					->withCur($curcode)
					->withCurid($request->get('currency_id'))
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
		$reports = $this->purchase_order->getPendingReport($request->all());
		
		if($request->get('search_type')=="summary")
			$voucher_head = 'Purchase Order Summary'.$curcode;
		elseif($request->get('search_type')=="summary_pending") {
			$voucher_head = 'Purchase Order Pending Summary'.$curcode;
			$reports = $this->makeArrGroup($reports,$curcode);
		} elseif($request->get('search_type')=="detail") {
			$voucher_head = 'Purchase Order Detail'.$curcode;
			//$reports = $this->makeTree($reports);
			//echo '<pre>';print_r($reports);exit;
		} elseif($request->get('search_type')=="detail_pending") {
			$voucher_head = 'Purchase Order Pending Detail'.$curcode;
			//$reports = $this->makeTree($reports);
			
		}else {
			$voucher_head = 'Purchase Order Quantity Detail'.$curcode;
			//$reports = $this->makeTree($reports);
			
		}
		
		//echo '<pre>';print_r($reports);exit;
		
		$datareport[] = ['','','','',strtoupper($voucher_head), '','',''];
		$datareport[] = ['','','','','','',''];
		
		if($request->get('search_type')=='detail' ) {
			
			$datareport[] = ['SI.No.','PO#', 'PO.Ref#', 'Job No', 'Supplier','Item Code','Description','PO.Qty','Rate','Vat Amt.','Total Amt.'];
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
								  'vat' => number_format($row['unit_vat'],2),
								  'net_amount' => number_format($row['unit_vat']+$row['total_price'],2)
								];
			}
		} else if( $request->get('search_type')=='detail_pending') {
			
			$datareport[] = ['SI.No.','PO#', 'PO.Ref#', 'Job No', 'Supplier','Item Code','Description','PO.Qty','Rate','Total Amt.','Inv. Qty','Pending Qty','Rate','Total Amt.'];
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
			
			$datareport[] = ['SI.No.','PO#', 'PO.Ref#', 'Job No', 'Supplier','Item Code','Description','Ordered','Processed','Balance'];
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
			
			$datareport[] = ['SI.No.','PO#', 'PO.Ref#', 'Job No', 'Supplier','Gross Amt.','Discount','Total Amt','VAT Amt.','Net Total'];
			$i=0;
			$total=0;$gross=0;$vat=$amt=$net_amt=$netamt=0;	
		    $tot=0;$gs=0;$vt=0;
		    //echo '<pre>';print_r($reports);exit;
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
							        $netamt=number_format($net_amt,2) ;
			}
			$datareport[] = ['','','','','','',''];			
		    $datareport[] = ['','','','','Total:',$gs,'',$netamt,$vt,$tot];
		}
		// echo $voucher_head.'<pre>';print_r($datareport);exit;
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
	
	public function dataExportPo(Request $request)
	{
		$data = array();
		$datareport[] = [strtoupper(Session::get('company')),'','',''];
		$datareport[] = ['','','','','','',''];
		
		$attributes['document_id'] = $request->get('id');
		$attributes['is_fc'] = $request->get('fc');
		$result = $this->purchase_order->getOrder($attributes);
		
		$voucher_head = 'PURCHASE ORDER';
		
		 //echo '<pre>';print_r($result);exit;
		
		$datareport[] = ['','', strtoupper('PURCHASE ORDER'), '','','',''];	
		$datareport[] = ['','','','','','',''];
		
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
	
	public function getJobPo($job_id)
	{
		$data = array();
		$jobpos = $this->purchase_order->getJobPurOrd($job_id);
		return view('body.purchaseorder.jobpos')
					->withJobpos($jobpos)
					->withData($data);
	}
	
		public function getJob($id)
	{
		$data = array();
	
			$data = DB::table('purchase_order')->where('purchase_order.supplier_id',$id)
			                    ->join('jobmaster', 'jobmaster.id', '=', 'purchase_order.job_id')
			                   ->where('purchase_order.status',1)->where('purchase_order.deleted_at','0000-00-00 00:00:00')
			                   ->select('jobmaster.id','jobmaster.code')->orderBy('jobmaster.id', 'DESC')->get();
			return $data;
		}
	
	
	public function addFromSo($id = null, $doctype = null) {

		$data = array();
		$itemmaster = $this->itemmaster->activeItemmasterList();
		$terms = $this->terms->activeTermsList();
		$jobs = $this->jobmaster->activeJobmasterList();
		$currency = $this->currency->activeCurrencyList();
		$res = $this->voucherno->getVoucherNo('PO');
		$vno = $res->no;
		$lastid = DB::table('purchase_order')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->orderBy('id','DESC')->select('id')->first();
	    //echo '<pre>';print_r($res);exit;
		$location = $this->location->locationList();
		
		$footertxt = DB::table('header_footer')->where('doc','PO')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->first();
		$bcurrency = DB::table('parameter1')
							 ->join('currency', 'currency.id', '=', 'parameter1.bcurrency_id')
							  ->select('currency.code')
							 ->first();					 
					 
		
		$print = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','PO')
							->where('report_view_detail.is_default',1)
							->select('report_view_detail.id')
							->first();
		

			$ids = explode(',', $id);
			$ocrow = [];
			if($doctype=='SO') {
    			$docRow = $this->sales_order->findOrderDataPO($ids[0]);
    			$docItems = $this->sales_order->getSOItemsPO($ids);//echo '<pre>';print_r($quoteRow);exit;
    			$ocrow = [];
			} 
			//echo '<pre>';print_r($docItems);exit;
			$total = 0; $discount = 0; $nettotal = 0; $vat_total = 0;
			foreach($docItems as $item) {
				$total += $item->total_price;
				$discount += $item->discount;
				$vat_total += $item->vat_amount;
			}
			$nettotal = $total - $discount + $vat_total;
			
			return view('body.purchaseorder.addso')
						->withItems($itemmaster)
						->withTerms($terms)
						->withJobs($jobs)
						->withCurrency($currency)
						->withVoucherno($res)
						//->withVoucherno($vno)
						->withSettings($this->acsettings)
						->withVatdata($this->vatdata)
						->withPrintid($lastid)
						->withFormdata($this->formData)
						->withPrint($print)
						->withDocrow($docRow)
						->withDocitems($docItems)
						->withPordid($id)
						->withTotal($total)
						->withDiscount($discount)
						->withOcrow($ocrow)
						->withVattotal($vat_total)
						->withDoctype($doctype)
						->withPurchaseOrderno(Session::get('voucher_no'))
						->withVoucherdt(Session::get('voucher_date'))
						->withReferenceno(Session::get('reference_no'))
						->withSettings($this->acsettings)
						->withLocation($location)
						->withBcurrency(($bcurrency!='')?$bcurrency->code:'')
						->withData($data);
		
		
		
	}
	
	public function refreshPO($id) { //SI id
	    
		$itms = DB::table('purchase_order')->where('purchase_order.id', $id)
					->join('purchase_order_item','purchase_order_item.purchase_order_id','=','purchase_order.id')
					->select('purchase_order_item.id','purchase_order_item.item_id','purchase_order_item.quantity','purchase_order_item.balance_quantity','purchase_order.id AS do_id')
					->get();

		if($itms) {
			foreach($itms as $row) {
			    
			    $itmsPI = DB::table('supplier_do')
					->join('supplier_do_item','supplier_do_item.supplier_do_id','=','supplier_do.id')
					//->where('sales_invoice.document_id', $row->do_id)
					->where('supplier_do.document_type','PO')
					->where('supplier_do_item.doc_row_id', $row->id)
					->where('supplier_do_item.status', 1)
					->where('supplier_do_item.deleted_at', '0000-00-00 00:00:00')
					->where('supplier_do.deleted_at', '0000-00-00 00:00:00')
					->select(DB::raw('SUM(supplier_do_item.quantity) AS si_quantity'))
					->get();
					
					//echo '<pre>';print_r($itmsPI); exit; 
					
				if(isset($itmsPI[0])) {
				    
				    if($itmsPI[0]->si_quantity == $row->quantity) {
				         DB::table('purchase_order_item')->where('id', $row->id)->update(['balance_quantity' => 0, 'is_transfer' => 1]);
				    } else {
				        if($itmsPI[0]->si_quantity > 0) {
				            $balqty = $row->quantity - $itmsPI[0]->si_quantity;
				            DB::table('purchase_order_item')->where('id', $row->id)->update(['balance_quantity' => $balqty, 'is_transfer' => 2]);
				        } else {
				            DB::table('purchase_order_item')->where('id', $row->id)->update(['balance_quantity' => 0, 'is_transfer' => 0]);
				        }
				    }
				}
				
			}
            

			$row1 = DB::table('purchase_order_item')->where('purchase_order_id', $id)->count();
			$row2 = DB::table('purchase_order_item')->where('purchase_order_id', $id)->where('is_transfer',1)->count();
			$row3 = DB::table('purchase_order_item')->where('purchase_order_id', $id)->where('is_transfer',2)->count(); 
			if($row1==$row2) {
				DB::table('purchase_order')
						->where('id', $id)
						->update(['is_editable' => 1, 'is_transfer' => 1]);
			} else if($row1 > 0 && $row2==0 && $row3==0) {
			    DB::table('purchase_order')
						->where('id', $id)
						->update(['is_editable' => 0, 'is_transfer' => 0]);
			} else if($row3 > 0) {
			    DB::table('purchase_order')
						->where('id', $id)
						->update(['is_editable' => 1, 'is_transfer' => 0]);
			}
		}
		
		return redirect('purchase_order');
			
	//return true;
	}
	
}


