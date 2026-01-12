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
use App\Repositories\AccountSetting\AccountSettingInterface;
use App\Repositories\Forms\FormsInterface;
use App\Repositories\SalesOrder\SalesOrderInterface;


use Illuminate\Http\Request;

use App\Http\Requests;
use Session;
use Response;
use Excel;
use App;
use DB;

class JobEstimateController extends Controller
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
	protected $accountsetting;
	protected $forms;
	protected $formData;
	protected $matservice;
	protected $sales_order;
	
	public function __construct(SalesOrderInterface $sales_order, QuotationSalesInterface $quotation_sales, ItemmasterInterface $itemmaster, TermsInterface $terms, JobmasterInterface $jobmaster, AccountMasterInterface $accountmaster, CurrencyInterface $currency, VoucherNoInterface $voucherno, AreaInterface $area, SalesmanInterface $salesman,AccountSettingInterface $accountsetting,FormsInterface $forms) {
		
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
		$this->accountsetting = $accountsetting;
		$this->forms = $forms;
		$this->formData = $this->forms->getFormData('JE');
		$this->sales_order = $sales_order;
		
		$this->matservice = DB::table('parameter2')->where('keyname', 'mod_material_service')->where('status',1)->select('is_active')->first();
	}
	
    public function index() {
		
		$data = array();
		$quotations = [];//$this->quotation_sales->quotationSalesList();//echo '<pre>';print_r($quotations);exit;
		
        $jobs = $this->jobmaster->activeJobmasterList();
		$salesmans = $this->salesman->getSalesmanList();
		$cus =DB::table('account_master')->where('category','CUSTOMER')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','master_name')->get(); 
		return view('body.jobestimate.index')
					->withQuotations($quotations)
					->withSalesman($salesmans)
					->withCus($cus)
					->withJobs($jobs)
					->withSettings($this->acsettings)
					->withData($data);
	}
	
	public function ajaxPaging(Request $request)
	{
		$columns = array( 
                            0 =>'quotation_sales.id', 
                            1 =>'voucher_no',
                            2=> 'voucher_date',
                            3=> 'customer',
							4=> 'vehicle',
							5=> 'reg_no',
							6=>'chasis_no'
                        );
						
		$totalData = $this->quotation_sales->jobEstimateListCount();
            
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        //$order = 'quotation_sales.id';
        //$dir = 'desc';
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
		$search = (empty($request->input('search.value')))?null:$request->input('search.value');
        
		$invoices = $this->quotation_sales->jobEstimateList('get', $start, $limit, $order, $dir, $search);
	//echo '<pre>';print_r($invoices);exit;
		if($search)
			$totalFiltered =  $this->quotation_sales->jobEstimateList('count', $start, $limit, $order, $dir, $search);
		
		$prints = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','JE')
							->select('report_view_detail.name','report_view_detail.id')
							->get();
							
        $data = array();
        if(!empty($invoices))
        {
           
			foreach ($invoices as $row)
            {
                //$edit =  '"'.url('job_estimate/edit/'.$row->id).'"';
                $delete =  'funDelete("'.$row->id.'")';
				$print = url('job_estimate/print/'.$row->id);
				
				$edit =  url('job_estimate/edit/'.$row->id);
				$docs =  url('job_estimate/docs/'.$row->id);
				$view =  url('job_estimate/views/'.$row->id);
				
                $nestedData['id'] = $row->id;
                $nestedData['voucher_no'] = $row->voucher_no;
				$nestedData['voucher_date'] = date('d-m-Y', strtotime($row->voucher_date));
				$nestedData['customer'] = ($row->customer=='CASH CUSTOMERS') ? (($row->customer_name!='')?$row->customer.'('.$row->customer_name.')':$row->customer) : $row->customer;
				$nestedData['vehicle'] = $row->vehicle;
				$nestedData['reg_no'] = $row->reg_no;
				$nestedData['chasis_no'] = $row->chasis_no;
                /*$nestedData['edit'] = "<p><button class='btn btn-primary btn-xs' onClick='location.href={$edit}'>
												<span class='glyphicon glyphicon-pencil'></span></button></p>";*/
				$nestedData['view'] = "<p><a href='{$view}' class='btn btn-info btn-xs' target='_blank'><i class='fa fa-fw fa-eye'></i></a></p>";								
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
	
	public function add($id=null) {

		$data = array(); $vehicle_data = $orderrow = $photos = $orditems = null;
		$itemmaster = $this->itemmaster->activeItemmasterList();
		$terms = $this->terms->activeTermsList();
		$jobs = $this->jobmaster->getOpenJobs();
		$currency = $this->currency->activeCurrencyList();
		$res = $this->voucherno->getVoucherNo('JE'); //echo '<pre>';print_r($this->formData);exit;
		//$vno = $res->no;//echo sizeof($vehicle_data);exit;//'<pre>';print_r($vehicle_data);exit;
		$lastid = DB::table('quotation_sales')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->orderBy('id','DESC')->select('id')->first();
		$jobtype = DB::table('jobtype')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		$view = ($this->matservice->is_active==1)?'addms':'add';
		$footertxt = DB::table('header_footer')->where('doc','JE')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->first();
		$print = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','JE')
							->where('report_view_detail.is_default',1)
							->select('report_view_detail.id')
							->first(); 
							
		if($id) {
		    $ids = explode(',', $id);
		    $orderrow = $this->sales_order->findPOdata($ids[0]);
			$orditems = $this->sales_order->getSOItems($ids);
			$photos = DB::table('job_photos')->where('job_order_id',$ids[0])->get(); 
			$view = 'add-jo';
		} 
							
		return view('body.jobestimate.'.$view)
					->withItems($itemmaster)
					->withTerms($terms)
					->withJobs($jobs)
					->withCurrency($currency)
					->withVoucherno($res)
					->withVatdata($this->vatdata)
					->withSettings($this->acsettings)
					->withVehicledata($vehicle_data)
					->withSearch(false)
					->withPrintid($lastid)
					->withFormdata($this->formData)
					->withJobtype($jobtype)
					->withPrint($print)
					->withFooter(isset($footertxt)?$footertxt->description:'')
					->withOrderrow($orderrow)
					->withOrditems($orditems)
					->withPhotos($photos)
					->withData($data);
	}
	
	public function save(Request $request) { //echo '<pre>';print_r( $request->all() );exit;
		
		if( $this->validate(
			$request, 
			[//'reference_no' => 'required',
			 'customer_name' => 'required','customer_id' => 'required',
			 //'vehicle_name' => 'required','vehicle_id' => 'required',
			 /* 'item_code.*'  => 'required', 'item_id.*' => 'required',
			 'unit_id.*' => 'required',
			 'quantity.*' => 'required',
			 'cost.*' => 'required' */
			],
			[//'reference_no.required' => 'Reference no. is required.',
			 'customer_name.required' => 'Customer Name is required.','customer_id.required' => 'Customer name is invalid.',
			 //'vehicle_name.required' => 'Vehicle Name is required.','vehicle_id.required' => 'Vehicle name is invalid.',
			/*  'item_code.*.required'   => 'Item code is required.', 'item_id.*' => 'Item code is invalid.',
			 'unit_id.*' => 'Item unit is required.',
			 'quantity.*' => 'Item quantity is required.',
			 'cost.*' => 'Item cost is required.' */
			]
		)) {
			//echo '<pre>';print_r($request->flash());exit;
			return redirect('job_estimate/add')->withInput()->withErrors();
		}
		//echo '<pre>';print_r($this->quotation_sales->create($request->all()));exit;
		$id = $this->quotation_sales->create($request->all());
		if($id) {
			Session::flash('message', 'Estimate added successfully.'); 
			//return redirect('job_estimate/print/'.$id);
			return redirect('job_estimate/add');
		} else {
			Session::flash('error', 'Something went wrong, estimate failed to add!');
			return redirect('job_estimate/add');
		}

	}
	
	
	public function destroy($id)
	{
		$this->quotation_sales->delete($id);
		//check accountmaster name is already in use.........
		// code here ********************************
		Session::flash('message', 'Estimate deleted successfully.');
		return redirect('job_estimate');
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
		
		$itemdesc = $this->makeTreeArr($this->quotation_sales->getItemDesc($id));//echo '<pre>';print_r($orderrow);exit;
		$jobdesc = $this->quotation_sales->getjobDescription($id);
		$jobtype = DB::table('jobtype')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		
		if($this->matservice->is_active==1) {
			$orditems = $this->quotation_sales->getItems($id,'itm');
			$seritems = $this->quotation_sales->getItems($id,'ser');
			$view = 'editms';
		} else {
			$seritems = null;
			$orditems = $this->quotation_sales->getItems($id);
			$view = 'edit';
		}
		
		$print = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','JE')
							->where('report_view_detail.is_default',1)
							->select('report_view_detail.id')
							->first();
			$photos = DB::table('quot_fotos')->where('quot_id',$id)->get(); 
		//	echo '<pre>';print_r($orderrow);exit;
		return view('body.jobestimate.'.$view)
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
					->withJobdesc($jobdesc)
					->withSeritems($seritems)
					->withJobtype($jobtype)
					->withPrint($print)
					->withPhotos($photos)
					->withData($data);

	}
	
	public function update(Request $request)
	{	
		//echo '<pre>';print_r($request->all());exit;
		$id = $request->input('quotation_order_id');
		if( $this->validate(
			$request, 
			[//'reference_no' => 'required',
			 'customer_name' => 'required','customer_id' => 'required',
			 //'vehicle_name' => 'required','vehicle_id' => 'required',
			 /* 'item_code.*'  => 'required', 'item_id.*' => 'required',
			 'unit_id.*' => 'required',
			 'quantity.*' => 'required',
			 'cost.*' => 'required' */
			],
			[//'reference_no.required' => 'Reference no. is required.',
			 'customer_name.required' => 'Customer Name is required.','customer_id.required' => 'Customer name is invalid.',
			 //'vehicle_name.required' => 'Vehicle Name is required.','vehicle_id.required' => 'Vehicle name is invalid.',
			 /* 'item_code.*.required'   => 'Item code is required.', 'item_id.*' => 'Item code is invalid.',
			 'unit_id.*' => 'Item unit is required.',
			 'quantity.*' => 'Item quantity is required.',
			 'cost.*' => 'Item cost is required.' */
			]
		)) {
			//echo '<pre>';print_r($request->flash());exit;
			return redirect('job_estimate/edit/'.$id)->withInput()->withErrors();
		}
		
		$this->quotation_sales->update($id, $request->all()); 
		Session::flash('message', 'Quotation sales updated successfully');
		return redirect('job_estimate');
	}
	
	
		public function getViews($id) { 

		$data = array();
		$itemmaster = $this->itemmaster->activeItemmasterList();
		$terms = $this->terms->activeTermsList();
		$jobs = $this->jobmaster->activeJobmasterList();
		$currency = $this->currency->activeCurrencyList();
		$orderrow = $this->quotation_sales->findPOdata($id);
		
		$itemdesc = $this->makeTreeArr($this->quotation_sales->getItemDesc($id));//echo '<pre>';print_r($orderrow);exit;
		$jobdesc = $this->quotation_sales->getjobDescription($id);
		$jobtype = DB::table('jobtype')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		
		if($this->matservice->is_active==1) {
			$orditems = $this->quotation_sales->getItems($id,'itm');
			$seritems = $this->quotation_sales->getItems($id,'ser');
			$view = 'editms';
		} else {
			$seritems = null;
			$orditems = $this->quotation_sales->getItems($id);
			$view = 'edit';
		}
		
		
			$photos = DB::table('quot_fotos')->where('quot_id',$id)->get(); 
		//	echo '<pre>';print_r($orderrow);exit;
		return view('body.jobestimate.viewonly')
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
					->withJobdesc($jobdesc)
					->withSeritems($seritems)
					->withJobtype($jobtype)
					->withPhotos($photos)
					->withData($data);

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
		return view('body.jobestimate.view')
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
	
	public function getQuotation($customer_id, $url)
	{
		$data = array();
		$quotations = $this->quotation_sales->getCustomerJobQuotation($customer_id);//print_r($quotations);exit;
		return view('body.jobestimate.quotation')
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
			$attributes['document_id'] = $id;
			$attributes['is_fc'] = ($fc)?1:'';
			$result = $this->quotation_sales->getQuotation($attributes);
			$itemdesc = $this->makeTreeArr($this->quotation_sales->getItemDesc($id));
			
			$titles = ['main_head' => 'Job Estimate','subhead' => 'Job Estimate'];
			
			$jobdesc = DB::table('jobestimate_details')->where('jobestimate_id',$id)
							->where('status',1)->where('deleted_at','0000-00-00 00:00:00')
							->select('description','comment')->orderBy('id','ASC')->get();
			//split item and service
			$items = null;
			if($this->matservice->is_active==1) {
				$items = $this->splitItems($result['items']);
			}
			
			//echo '<pre>';print_r($items);exit;
			return view('body.jobestimate.print')  //newprint  print
						->withDetails($result['details'])
						->withTitles($titles)
						->withFc($attributes['is_fc'])
						->withItemdesc($itemdesc)
						->withJobdesc($jobdesc)
						->withEstitems($items)
						->withItems($result['items']);
		} else {
			$path = app_path() . '/stimulsoft/helper.php';
			
			if(env('STIMULSOFT_VER')==2)
			        return view('body.reports')->withPath($path)->withView($viewfile->print_name);
			   else
			        return view('body.jobestimate.viewer')->withPath($path)->withView($viewfile->print_name);
			
			
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
				$vat = ($row->quantity > 0)?($row->unit_vat / $row->quantity):0;
				$vat_amount += $vat * $pending_qty;
				$net_amount = $vat_amount + $pending_amt;
				$voucher_no = $row->voucher_no;
				$refno = $row->reference_no;
				$suppname = $row->master_name;
				$salesman = $row->salesman;
				$discount = $row->discount;
			}
			$arr[] = ['voucher_no' => $voucher_no,'reference_no' => $refno, 'master_name' => $suppname, 'discount' => $discount, 
					  'total' => $pending_amt,'vat_amount' => $vat_amount, 'net_total' => $net_amount, 'salesman' => $salesman];
			
		}

		return $arr;
	}

	public function getSearch(Request $request)
	{
		$data = array();
		$pending=($request->get('pending'))?$request->get('pending'):0;
		$reports = $this->quotation_sales->getPendingReportJob($request->all());
		
		if($request->get('search_type')=="summary" && $pending==0)
			$voucher_head = 'Job Estimate Summary';
		elseif($request->get('search_type')=="summary" && $pending==1) {
			$voucher_head = 'Job Estimate Pending Summary';
			$reports = $this->makeArrGroup($reports);
		} elseif($request->get('search_type')=="detail" && $pending==0) {
			$voucher_head = 'Job Estimate Detail';
			$reports = $this->makeTree($reports);
		} else {
		    if($request->get('search_type')=="detail" && $pending==1){
			$voucher_head = 'Job Estimate Pending Detail';
		    }
		    else{
		       $voucher_head = 'Job Estimate Quantity Detail'; 
		    }
		    
			$reports = $this->makeTree($reports);
		}
		
		//echo '<pre>';print_r($reports);exit;
		return view('body.jobestimate.preprint')
					->withReports($reports)
					->withVoucherhead($voucher_head)
					->withType($request->get('search_type'))
					->withFromdate($request->get('date_from'))
					->withTodate($request->get('date_to'))
					->withSalesman($request->get('salesman'))
					->withCustomerid($request->get('customer_id'))
					->withSettings($this->acsettings)
					->withData($data);
	}
	
	
	public function dataExport(Request $request)
	{
		$data = array();
		$request->merge(['type' => 'export']);
		$datareport[] = ['','','','',strtoupper(Session::get('company')),'','',''];
		$datareport[] = ['','','','','','',''];
		$reports = $this->quotation_sales->getPendingReportJob($request->all());
		
		if($request->get('search_type')=="summary")
			$voucher_head = 'Job Estimate Summary';
		elseif($request->get('search_type')=="summary_pending") {
			$voucher_head = 'Job Estimate Pending Summary';
			$reports = $this->makeArrGroup($reports);
		} elseif($request->get('search_type')=="detail") {
			$voucher_head = 'Job Estimate Detail';
		} elseif($request->get('search_type')=="detail_pending") {
			$voucher_head = 'Job Estimate Pending Detail';
		}else{
		    $voucher_head = 'Job Estimate Quantity Detail';
		}
		
			$datareport[] = ['','','','',strtoupper($voucher_head), '','',''];
		$datareport[] = ['','','','','','',''];
		
		if($request->get('search_type')=='detail' ) {
			
			$datareport[] = ['SI.No.','JE.#', 'JE.Ref#', 'Customer','Salesman','Item Code','Description','Qtn.Qty','Rate','Total Amt.','Vat Amt','Net Amt'];
			$i=$net_amount=0;
			foreach ($reports as $row) {
				//echo '<pre>';print_r($row);exit;
				$i++;
				$net_amount = $row->unit_vat + $row->line_total;
				$datareport[] = [ 'si' => $i,
								  'po' => $row['voucher_no'], 
								  'ref' => $row['reference_no'],
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
		} 	else if( $request->get('search_type')=='detail_pending') {
			
			$datareport[] = ['SI.No.','JE.#', 'JE.Ref#', 'Customer','Salesman','Item Code','Description','Qtn.Qty','Rate','Total Amt.','Inv.Qty','Pending_qty','Rate','Total Amt'];
			$i=$total_amt=$inv_qty=$pending_qty=$pending_amt=0;
			foreach ($reports as $row) {
				//echo '<pre>';print_r($row);exit;
				$i++;
				$total_amt = $row['quantity'] * $row['unit_price'];
				$inv_qty = ($row['balance_quantity']==0)?0:$row['quantity'] - $row['balance_quantity'];
				$pending_qty = ($row['balance_quantity']==0)?$row['quantity']:$row['balance_quantity'];
				$pending_amt = $pending_qty * $row['unit_price'];
				$datareport[] = [ 'si' => $i,
								  'po' => $row['voucher_no'], 
								  'ref' => $row['reference_no'],
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
			
			$datareport[] = ['SI.No.','JE.#', 'JE.Ref#', 'Job No.', 'Customer','Salesman','Item Code','Description','Ordered','Processed','Balance'];
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
			
			$datareport[] = ['SI.No.','JE.#', 'JE.Ref#', 'Customer','Salesman','Gross Amt.','Discount','Total Amt','VAT Amt.','Net Total'];
			$i=0;
			$total=0;$gross=0;$vat=$total_amt=$discount=0;	
		    $tot=0;$gs=0;$vt=$tam=$tot_amt=$dis=0;
			foreach ($reports as $row) {
					$i++;
					$total_amt=$row['total']-$row['discount'];
					$datareport[] = [ 'si' => $i,
									  'po' => $row['voucher_no'],
									  'ref' => $row['reference_no'],
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
		    $datareport[] = ['','','','','Total:',$gs,$dis,$tam,$vt,$tot];
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
	
	public function ajaxCreate(Request $request)
	{
		
		DB::beginTransaction();
		try { 
			$attributes = $request->all();
			//$check = DB::table('vehicle')->where('reg_no', $attributes['reg_no'])->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->count();
			if($attributes['chasis_no']!='') {
				$check = DB::table('vehicle')->where('chasis_no', $attributes['chasis_no'])->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->count();
				if($check > 0)
					return 0;
			}
			
			$id = DB::table('vehicle')
						->insertGetId([ 'customer_id' => $attributes['customer_id'],
										'name'   => $attributes['name'],
										'reg_no' => $attributes['reg_no'],
										'make'  => $attributes['make'],
										//'color'  => $attributes['color'],
										'engine_no' => $attributes['engine_no'],
										'chasis_no' => $attributes['chasis_no'],
										'owner' => $attributes['owner'],
										'km_done' => $attributes['km_done'],
										'status' => 1,
										'model' => $attributes['model'],
										'issue_plate' => $attributes['issue_plate'],
										'code_plate' => $attributes['code_plate'],
										'color_code' => $attributes['color_code'],
										'plate_type' => $attributes['plate_type']
									]);
									
			DB::commit();
			return $id;
			
		} catch(\Exception $e) {
				
			DB::rollback();
			return -1;
		}
	}
	
	public function uploadSubmit(Request $request)
	{	
		$res = $this->quotation_sales->ajax_upload($request->photos);
		return response()->json(array('file_name' => $res), 200);
	}
	
	
	public function getvehSearch(Request $request)
	{
		$data = array();
		
		$vehicle_data = $this->jobmaster->getVehicleDetails($request->all());
		//echo '<pre>';print_r($request->all());exit;
		$itemmaster = $this->itemmaster->activeItemmasterList();
		$terms = $this->terms->activeTermsList();
		$jobs = $this->jobmaster->activeJobmasterList();
		$currency = $this->currency->activeCurrencyList();
		$res = $this->voucherno->getVoucherNo('QS');
		$vouchers = $this->accountsetting->getAccountSettingsDefault2($vid=3);
		$vno = $res->no; //echo '<pre>';print_r($vehicle_data);exit;
		return view('body.jobestimate.add')
					->withItems($itemmaster)
					->withTerms($terms)
					->withJobs($jobs)
					->withCurrency($currency)
					->withVoucherno($vno)
					->withVatdata($this->vatdata)
					->withSettings($this->acsettings)
					->withVehicledata($vehicle_data)
					->withSearch(true)
					->withVouchers($vouchers)
					->withData($data);
		
	}
	
	public function getDocs($id) {
	    
	    $docs = DB::table('quot_fotos')->where('quot_id',$id)->get();
	    $job = $this->quotation_sales->find($id);
	    
	    return view('body.jobestimate.docsview')
					->withJobdata($job)
					->withDocs($docs);
					
	}
}

