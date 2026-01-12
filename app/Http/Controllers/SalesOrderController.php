<?php

namespace App\Http\Controllers;
use App\Repositories\Area\AreaInterface;
use App\Repositories\Itemmaster\ItemmasterInterface;
use App\Repositories\Terms\TermsInterface;
use App\Repositories\Jobmaster\JobmasterInterface;
use App\Repositories\AccountMaster\AccountMasterInterface;
use App\Repositories\Currency\CurrencyInterface;
use App\Repositories\VoucherNo\VoucherNoInterface;
use App\Repositories\SalesOrder\SalesOrderInterface;
use App\Repositories\PurchaseOrder\PurchaseOrderInterface;
use App\Repositories\Salesman\SalesmanInterface;
use App\Repositories\QuotationSales\QuotationSalesInterface;
use App\Repositories\AccountSetting\AccountSettingInterface;
use App\Repositories\Country\CountryInterface;
use App\Repositories\Acgroup\AcgroupInterface;
use App\Repositories\Forms\FormsInterface;
use App\Repositories\Location\LocationInterface;

use Illuminate\Http\Request;

use App\Http\Requests;
use Session;
use Response;
use Excel;
use App;
use DB;
use Mail;
use PDF;

class SalesOrderController extends Controller
{

	protected $itemmaster;
	protected $terms;
	protected $jobmaster;
	protected $accountmaster;
	protected $currency;
	protected $voucherno;
	protected $sales_order;
	protected $purchase_order;
	protected $salesman;
	protected $quotation_sales;
	protected $country;
	protected $group;
	protected $area;
	protected $forms;
	protected $formData;
	protected $location;
	
	public function __construct(SalesOrderInterface $sales_order, AreaInterface $area, QuotationSalesInterface $quotation_sales, ItemmasterInterface $itemmaster, TermsInterface $terms, JobmasterInterface $jobmaster, AccountMasterInterface $accountmaster, CurrencyInterface $currency, VoucherNoInterface $voucherno, SalesmanInterface $salesman, CountryInterface $country,AcgroupInterface $group,FormsInterface $forms,LocationInterface $location,PurchaseOrderInterface $purchase_order) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		
		$this->middleware('auth');
		$this->itemmaster = $itemmaster;
		$this->terms = $terms;
		$this->jobmaster = $jobmaster;
		$this->accountmaster = $accountmaster;
		$this->currency = $currency;
		$this->voucherno = $voucherno;
		$this->sales_order = $sales_order;
		$this->purchase_order = $purchase_order;
		$this->salesman = $salesman;
		$this->quotation_sales = $quotation_sales;
		$this->country = $country;
		$this->group = $group;
		$this->area = $area;
		$this->forms = $forms;
		$this->formData = $this->forms->getFormData('SO');
		$this->location = $location;
		
		$this->mod_consolidate_item = DB::table('parameter2')->where('keyname', 'mod_consolidate_item')->where('status',1)->select('is_active')->first();
		$this->mod_work_order = DB::table('parameter2')->where('keyname', 'mod_workorder')->where('status',1)->select('is_active')->first();
	}
	
    public function index() { 
		
		$data = array();
		$quotations = [];//$this->sales_order->quotationSalesList();
		$salesmans = $this->salesman->getSalesmanList();
		//$jobs = DB::table('jobmaster')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->where('is_salary_job',0)->select('id','code')->get();
		$custs = [];//DB::table('account_master')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->where('category','CUSTOMER')->select('id','master_name')->get();
		$jobs = $this->jobmaster->activeJobmasterList();
		$cus =DB::table('account_master')->where('category','CUSTOMER')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')
		->select('id','master_name')->get(); 
		return view('body.salesorder.index')
					->withQuotations($quotations)
					->withSalesman($salesmans)
					->withJobs($jobs)
					->withCus($cus)
					->withCusts($custs)
					->withSettings($this->acsettings)
					->withData($data);
	}
	
	public function ajaxPaging(Request $request)
	{
		if($this->acsettings->doc_approve==1) {
		    $columns = array( 
                            0 =>'sales_order.id', 
                            1 =>'voucher_no',
                            2=> 'customer',
                            3=> 'voucher_date',
                            4=> 'net_total',
							5=> 'status'
                        );
		} else {
		    $columns = array( 
                            0 =>'sales_order.id', 
                            1 =>'voucher_no',
                            2=> 'customer',
                            3=> 'voucher_date',
                            4=> 'net_total',
							5=> 'reference_no'
                        );
		}
						
		$totalData = $this->sales_order->salesOrderListCount();
            
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = 'sales_order.voucher_no';
        $dir = 'desc';
		$search = (empty($request->input('search.value')))?null:$request->input('search.value');
        
		$invoices = $this->sales_order->salesOrderList('get', $start, $limit, $order, $dir, $search);
		//echo '<pre>';print_r($invoices);exit;
		if($search)
			$totalFiltered =  $this->sales_order->salesOrderList('count', $start, $limit, $order, $dir, $search);
		
		$prints = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','SO')
							->select('report_view_detail.name','report_view_detail.id')
							->get();
							
        $data = array();
        if(!empty($invoices))
        {
           
			foreach ($invoices as $row)
            {
                $edit =  '"'.url('sales_order/edit/'.$row->id).'"';
                $delete =  'funDelete("'.$row->id.'","'.$row->is_editable.'")';
				$print = url('sales_order/print/'.$row->id);
				$revice =  url('sales_order/revice/'.$row->id);
				$settlement =  url('sales_order/settlement/'.$row->id);
				$edits =  url('sales_order/edit/'.$row->id);
				$editd =  '"'.url('sales_order/edit_draft/'.$row->id).'"';
				$viewonly =  url('sales_order/viewonly/'.$row->id);
				
                $nestedData['id'] = $row->id;
                $nestedData['voucher_no'] = $row->voucher_no;
				$nestedData['voucher_date'] = date('d-m-Y', strtotime($row->voucher_date));
				$nestedData['customer'] = ($row->customer=='CASH CUSTOMERS') ? (($row->customer_name!='')?$row->customer.'('.$row->customer_name.')':$row->customer) : $row->customer;
				$nestedData['net_total'] = $row->net_total;
				$nestedData['reference_no'] = $row->reference_no;
				$refresh =  url('sales_order/refresh_so/'.$row->id);
				$nestedData['viewonly'] = "<p><a href='{$viewonly}' class='btn btn-info btn-xs' target='_blank'><i class='glyphicon glyphicon-eye-open'></i></a></p>";

			if($this->mod_work_order->is_active==1 && $row->is_draft==0){
				$nestedData['edit'] = "<div class='btn-group drop_btn' role='group'>
												<button type='button' class='btn btn-primary btn-xs dropdown-toggle m-r-50'
													id='exampleIconDropdown1' data-toggle='dropdown' aria-expanded='false'>
													<i class='fa fa-fw fa-shield'></i><span class='caret'></span>
												</button>
												<ul style='min-width:100px !important;' class='dropdown-menu' aria-labelledby='exampleIconDropdown1' role='menu'>
													<li role='presentation'><a href='{$edits}' role='menuitem'>Edit</a></li>
													<li role='presentation'><a href='{$revice}' role='menuitem'>Work Order</a></li>
												</ul>
											</div>";
					}
					else if($row->is_draft==1) {								
			 $nestedData['edit'] = "<p><button class='btn btn-primary btn-xs' onClick='location.href={$editd}'>
												<span class='glyphicon glyphicon-pencil'></span></button></p>";						
				}					
					else if($row->is_settled==1){
                $nestedData['edit'] = "<p><button class='btn btn-primary btn-xs' onClick='location.href={$edit}'>
												<span class='glyphicon glyphicon-pencil'></span></button></p>";
												
					} 					
												
				else {								
				$nestedData['edit'] = "<div class='btn-group drop_btn' role='group'>
												<button type='button' class='btn btn-primary btn-xs dropdown-toggle m-r-50'
													id='exampleIconDropdown1' data-toggle='dropdown' aria-expanded='false'>
													<i class='fa fa-fw fa-shield'></i><span class='caret'></span>
												</button>
												<ul style='min-width:100px !important;' class='dropdown-menu' aria-labelledby='exampleIconDropdown1' role='menu'>
													<li role='presentation'><a href='{$edits}' role='menuitem'>Edit</a></li>
											        
													<li role='presentation'><a href='{$refresh}' role='menuitem'>Refresh</a></li>
												</ul>
											</div>";
											//<li role='presentation'><a href='{$settlement}' role='menuitem'>Settlement</a></li>
				}								
												
												
				$nestedData['delete'] = "<button class='btn btn-danger btn-xs delete' onClick='{$delete}'>
												<span class='glyphicon glyphicon-trash'></span>";
				
				$apr = ($this->acsettings->doc_approve==1)?[1]:[0,1,2];
				
				$opts = '';					
				foreach($prints as $doc) {
					$opts .= "<li role='presentation'><a href='{$print}/".$doc->id."' target='_blank' role='menuitem'>".$doc->name."</a></li>";
				}
				
				if(in_array($row->doc_status, $apr))	 {
					if($row->is_fc==1) {								
						$nestedData['print'] = "<p><a href='{$print}' target='_blank' class='btn btn-primary btn-xs'><span class='fa fa-fw fa-print'></span></a>
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
					$nestedData['print'] = "";
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
	
	public function add($id = null, $doctype = null) {

		$data = array(); //echo '<pre>';print_r($this->formData);exit;
		$itemmaster = $this->itemmaster->activeItemmasterList();
		$terms = $this->terms->activeTermsList();
		$jobs = $this->jobmaster->activeJobmasterList();
		$currency = $this->currency->activeCurrencyList();
		$res = $this->voucherno->getVoucherNo('SO'); //echo '<pre>';print_r($res);exit;
		//$vno = $res->no;
		$row = DB::table('sales_order')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->orderBy('id','DESC')->select('id','doc_status')->first();
		$apr = ($this->acsettings->doc_approve==1)?[1]:[0,1,2];
		$location = $this->location->locationList();
		
		$footertxt = DB::table('header_footer')->where('doc','SO')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->first();
		if($row && in_array($row->doc_status, $apr))
			$lastid = $row->id;
		else
			$lastid = null;
		
		$print = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','SO')
							->where('report_view_detail.is_default',1)
							->select('report_view_detail.id')
							->first();
							
		$prntjobs = [];/* DB::table('sales_order')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')
						->select('id','voucher_no')
						->offset(0)->limit(10)
						->orderBy('id','DESC')
						->get(); */
		
		if($id) {
			$ids = explode(',', $id);
			if($doctype=='QS') {
				//$quoteRow = $this->sales_order->findPOdata($ids[0]);
				$quoteRow = $this->quotation_sales->findQuoteData($ids[0]);
				$quoteItems = $this->quotation_sales->getQSItems($ids);
				//$quoteItems = $this->sales_order->getSOItems($ids);
				//echo '<pre>';print_r($quoteItems);exit;
			} else if($doctype=='SO') {
				$quoteRow = $this->sales_order->findPOdata($ids[0]);
				$quoteItems = $this->sales_order->getSOItems($ids);
				//echo '<pre>';print_r($quoteItems);exit;
			}
			else{
			$quoteRow = $this->quotation_sales->findQuoteData($ids[0]);
			
			if($this->mod_consolidate_item->is_active==1)
				$quoteItems = $this->consolidateItems( $this->quotation_sales->getQSItems($ids) ); 
			else
				$quoteItems = $this->quotation_sales->getQSItems($ids);
				//echo '<pre>';print_r($quoteRow);exit;
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
			//echo '<pre>';print_r($quoteRow);exit;
			return view('body.salesorder.addquote')
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
						->withLocation($location)
						->withData($data);
		}
		
		return view('body.salesorder.add')
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
					->withPrntjobs($prntjobs)
					->withFooter(isset($footertxt)?$footertxt->description:'')
					->withData($data);
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
	
	public function save(Request $request) { //echo '<pre>';print_r($request->all());exit;
	
		 $this->validate(
			$request, 
			[//'voucher_no' => 'required|unique:sales_order',
			 'customer_name' => 'required','customer_id' => 'required',
			 /* 'item_code.*'  => 'required', 'item_id.*' => 'required',
			 'unit_id.*' => 'required',
			 'quantity.*' => 'required',
			 'cost.*' => 'required' */
			],
			[//'voucher_no' => 'Voucher no should be unique.',
			 'customer_name.required' => 'Customer Name is required.','customer_id.required' => 'Customer name is invalid.',
			 /* 'item_code.*.required'   => 'Item code is required.', 'item_id.*' => 'Item code is invalid.',
			 'unit_id.*' => 'Item unit is required.',
			 'quantity.*' => 'Item quantity is required.',
			 'cost.*' => 'Item cost is required.' */
			]
			);
		
		if($this->sales_order->create($request->all()))
			Session::flash('message', 'Sales Order added successfully.');
		else
			Session::flash('error', 'Something went wrong, Order failed to add!');
		
		return redirect('sales_order/add');
	}
	
	public function Settlement($id){
		    DB::table('sales_order')->where('id',$id)->update(['is_settled' => 1]);
		    Session::flash('message', 'Sales order is settled.');
		    return redirect('sales_order');
       }
       
       	public function saveDraft(Request $request) { //echo '<pre>';print_r($request->all());exit;
	
		 $this->validate(
			$request, 
			[//'voucher_no' => 'required|unique:sales_order',
			 'customer_name' => 'required','customer_id' => 'required',
			 /* 'item_code.*'  => 'required', 'item_id.*' => 'required',
			 'unit_id.*' => 'required',
			 'quantity.*' => 'required',
			 'cost.*' => 'required' */
			],
			[//'voucher_no' => 'Voucher no should be unique.',
			 'customer_name.required' => 'Customer Name is required.','customer_id.required' => 'Customer name is invalid.',
			 /* 'item_code.*.required'   => 'Item code is required.', 'item_id.*' => 'Item code is invalid.',
			 'unit_id.*' => 'Item unit is required.',
			 'quantity.*' => 'Item quantity is required.',
			 'cost.*' => 'Item cost is required.' */
			]
			);
		
		if($this->sales_order->create($request->all()))
			Session::flash('message', 'Sales Order drafted successfully.');
		else
			Session::flash('error', 'Something went wrong, Order failed to add!');
		
		return redirect('sales_order/add');
	}

	
	public function destroy($id)
	{
		$this->sales_order->delete($id);
		//check accountmaster name is already in use.........
		// code here ********************************
		Session::flash('message', 'Sales Order deleted successfully.');
		return redirect('sales_order');
	}
	
	public function checkRefNo(Request $request) {

		$check = $this->sales_order->check_reference_no($request->get('reference_no'), $request->get('id'));
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
		$orderrow = $this->sales_order->findPOdata($id);
		$orditems = $this->sales_order->getItems($id);
		$location = $this->location->locationList();
		$itemdesc = $this->makeTreeArr($this->sales_order->getItemDesc($id));
		
		$apr = ($this->acsettings->doc_approve==1)?[1]:[0,1,2];
		if(in_array($orderrow->doc_status, $apr))
			$isprint = true;
		else
			$isprint = null;
		
		$print = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','SO')
							->where('report_view_detail.is_default',1)
							->select('report_view_detail.id')
							->first();
							
		$infodata = DB::table('sales_order_info')->where('sales_order_id',$id)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->orderBy('id','ASC')->get();					
		//echo '<pre>';print_r($orderrow);exit;
		return view('body.salesorder.edit') //editsp  edit
					->withItems($itemmaster)
					->withTerms($terms)
					->withJobs($jobs)
					->withCurrency($currency)
					->withOrderrow($orderrow)
					->withOrditems($orditems)
					->withVatdata($this->vatdata)
					->withSettings($this->acsettings)
					->withFormdata($this->formData)
					->withIsprint($isprint)
					->withPrint($print)
					->withLocation($location)
					->withInfos($infodata)
					->withItemdesc($itemdesc)
					->withModwork($this->mod_work_order->is_active)
					->withData($data);

	}
	
	public function update(Request $request)
	{
		$id = $request->input('sales_order_id');
		$this->validate(
			$request, 
			[//'reference_no' => 'required',
			 'customer_name' => 'required','customer_id' => 'required',
			 /* 'item_code.*'  => 'required', 'item_id.*' => 'required',
			 'unit_id.*' => 'required',
			 'quantity.*' => 'required',
			 'cost.*' => 'required' */
			],
			[//'reference_no.required' => 'Reference no. is required.',
			 'customer_name.required' => 'Customer Name is required.','customer_id.required' => 'Customer name is invalid.',
			 /* 'item_code.*.required'   => 'Item code is required.', 'item_id.*' => 'Item code is invalid.',
			 'unit_id.*' => 'Item unit is required.',
			 'quantity.*' => 'Item quantity is required.',
			 'cost.*' => 'Item cost is required.' */
			]
			);
		
		$this->sales_order->update($id, $request->all());
		
		########## email script #############
		if($this->acsettings->doc_approve==1 && $request->get('doc_status')==1 && $request->get('chkmail')==1) {
					
			$attributes['document_id'] = $id;
			$attributes['is_fc'] = '';
			$result = $this->sales_order->getOrder($attributes);
			$titles = ['main_head' => 'Sales Order','subhead' => 'Sales Order'];
			$data = array('details'=> $result['details'], 'titles' => $titles, 'fc' => $attributes['is_fc'], 'items' => $result['items']);
			$pdf = PDF::loadView('body.salesorder.pdfprint', $data);
			
			$mailmessage = $request->get('email_message');
			$emails = explode(',', $request->get('email'));
			
			if($emails[0]!='') {
				$data = array('name'=> $request->get('customer_name'), 'mailmessage' => $mailmessage );
				try{
					Mail::send('body.salesorder.email', $data, function($message) use ($emails,$pdf) {
						$message->to($emails[0]);
						
						if(count($emails) > 1) {
							foreach($emails as $k => $row) {
								if($k!=0)
									$cc[] = $row;
							}
							$message->cc($cc);
						}
						
						$message->subject('Sales Order');
						$message->attachData($pdf->output(), "sales_order.pdf");
					});
					
				}catch(JWTException $exception){
					$this->serverstatuscode = "0";
					$this->serverstatusdes = $exception->getMessage();
					
				}
			}
		}
		
		Session::flash('message', 'Sales Order updated successfully');
			if($request->get('is_revice')==1){
			return redirect('sales_order/work_order');
		}
		else{
			return redirect('sales_order');
		}
	}
	
	
	public function editDraft($id) { 

		$data = array();
		$itemmaster = $this->itemmaster->activeItemmasterList();
		$terms = $this->terms->activeTermsList();
		$jobs = $this->jobmaster->activeJobmasterList();
		$currency = $this->currency->activeCurrencyList();
		$orderrow = $this->sales_order->findPOdata($id);
		$orditems = $this->sales_order->getItems($id);
		$location = $this->location->locationList();
		$itemdesc = $this->makeTreeArr($this->sales_order->getItemDesc($id));
		
		$apr = ($this->acsettings->doc_approve==1)?[1]:[0,1,2];
		if(in_array($orderrow->doc_status, $apr))
			$isprint = true;
		else
			$isprint = null;
		
		$print = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','SO')
							->where('report_view_detail.is_default',1)
							->select('report_view_detail.id')
							->first();
							
		$infodata = DB::table('sales_order_info')->where('sales_order_id',$id)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->orderBy('id','ASC')->get();					
	//	echo '<pre>';print_r($orderrow);exit;
		return view('body.salesorder.edit-draft') //editsp  edit
					->withItems($itemmaster)
					->withTerms($terms)
					->withJobs($jobs)
					->withCurrency($currency)
					->withOrderrow($orderrow)
					->withOrditems($orditems)
					->withVatdata($this->vatdata)
					->withSettings($this->acsettings)
					->withFormdata($this->formData)
					->withIsprint($isprint)
					->withPrint($print)
					->withLocation($location)
					->withInfos($infodata)
					->withItemdesc($itemdesc)
					->withModwork($this->mod_work_order->is_active)
					->withData($data);

	}
		public function updateDraft(Request $request)
	{
	    
	    //echo '<pre>';print_r($request->all());exit;
		$id = $request->input('sales_order_id');
		if( $this->validate(
			$request, 
			[//'reference_no' => 'required',
			 'customer_name' => 'required','customer_id' => 'required',
			 /* 'item_code.*'  => 'required', 'item_id.*' => 'required',
			 'unit_id.*' => 'required',
			 'quantity.*' => 'required',
			 'cost.*' => 'required' */
			],
			[//'reference_no.required' => 'Reference no. is required.',
			 'customer_name.required' => 'Customer Name is required.','customer_id.required' => 'Customer name is invalid.',
			 /* 'item_code.*.required'   => 'Item code is required.', 'item_id.*' => 'Item code is invalid.',
			 'unit_id.*' => 'Item unit is required.',
			 'quantity.*' => 'Item quantity is required.',
			 'cost.*' => 'Item cost is required.' */
			]
		)) {
		
			return redirect('sales_order/edit/'.$id)->withInput()->withErrors();
		}
		
		$this->sales_order->update($id, $request->all());
		Session::flash('message', 'Sales Order draft updated successfully');
			if($request->get('is_revice')==1){
			return redirect('sales_order/work_order');
		}
		else{
			return redirect('sales_order');
		}
	}
	
	
	public function viewonly($id) { 

		$data = array();
		$itemmaster = $this->itemmaster->activeItemmasterList();
		$terms = $this->terms->activeTermsList();
		$jobs = $this->jobmaster->activeJobmasterList();
		$currency = $this->currency->activeCurrencyList();
		$orderrow = $this->sales_order->findPOdata($id);
		$orditems = $this->sales_order->getItems($id);
		$location = $this->location->locationList();
		$itemdesc = $this->makeTreeArr($this->sales_order->getItemDesc($id));
		
		$apr = ($this->acsettings->doc_approve==1)?[1]:[0,1,2];
		if(in_array($orderrow->doc_status, $apr))
			$isprint = true;
		else
			$isprint = null;
		
		$print = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','SO')
							->where('report_view_detail.is_default',1)
							->select('report_view_detail.id')
							->first();
							
		$infodata = DB::table('sales_order_info')->where('sales_order_id',$id)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->orderBy('id','ASC')->get();					
		//echo '<pre>';print_r($orderrow);exit;
		return view('body.salesorder.viewonly') //editsp  edit
					->withItems($itemmaster)
					->withTerms($terms)
					->withJobs($jobs)
					->withCurrency($currency)
					->withOrderrow($orderrow)
					->withOrditems($orditems)
					->withVatdata($this->vatdata)
					->withSettings($this->acsettings)
					->withFormdata($this->formData)
					->withIsprint($isprint)
					->withPrint($print)
					->withLocation($location)
					->withInfos($infodata)
					->withItemdesc($itemdesc)
					->withModwork($this->mod_work_order->is_active)
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
	
	public function show($id) { 

		$data = array();
		$acmasterrow = $this->accountmaster->accountMasterView($id);
		//echo '<pre>';print_r($acmasterrow);exit;
		return view('body.accountmaster.view')
					->withMasterrow($acmasterrow)
					->withData($data);
	}

	public function checkVchrNo(Request $request) { 

		$check = $this->sales_order->check_voucher_no($request->get('voucher_no'), $request->get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
	
	public function getCustomer($deptid=null)
	{
		$data = array();
		$customers = [];//$this->accountmaster->getCustomerList($deptid);//
		//print_r($customers);exit;
		$country = $this->country->activeCountryList();
		$area = $this->area->activeAreaList();
		$cus_code = json_decode($this->ajax_getcode($category='CUSTOMER'));
		return view('body.salesorder.customer')//customer
					->withCustomers($customers)
					->withArea($area)
					->withCusid($cus_code->code)
					->withCategory($cus_code->category)
					->withFormdata($this->formData)
					->withCountry($country)
					->withDeptid($deptid)
					->withData($data);
	}
	
	public function getSalesman()
	{
		$data = array();
		$salesmans = $this->salesman->getSalesmanList();
		return view('body.salesorder.salesman')
					->withSalesmans($salesmans)
					->withData($data);
	}
	
	public function getItem($num)
	{
		$data = array();
		$itemmaster = $this->itemmaster->getActiveItemmasterList();
		return view('body.salesorder.item')
					->withItems($itemmaster)
					->withNum($num)
					->withData($data);
	}
	
	public function getOrder($customer_id, $url)
	{
		$data = array();
		if($this->mod_work_order->is_active==1){
		$orders = $this->sales_order->getCustomerWorkOrder($customer_id);
		}else{
		   $orders = $this->sales_order->getCustomerOrder($customer_id); 
		}
		return view('body.salesorder.order')
					->withOrders($orders)
					->withUrl($url)
					->withData($data);
	}
	public function getJob($id)
	{
		$data = array();
	
			$data = DB::table('sales_order')->where('sales_order.is_rental',0)->where('sales_order.job_type',0)->where('sales_order.customer_id',$id)
			                    ->join('jobmaster', 'jobmaster.id', '=', 'sales_order.job_id')
			                   ->where('sales_order.status',1)->where('sales_order.deleted_at','0000-00-00 00:00:00')
			                   ->select('jobmaster.id','jobmaster.code')->orderBy('jobmaster.id', 'DESC')->get();
			return $data;
		}
	
	public function getWorkJob($id)
	{
		$data = array();
	
			$data = DB::table('sales_order')->where('sales_order.is_rental',0)->where('sales_order.job_type',1)->where('sales_order.customer_id',$id)
			                    ->join('jobmaster', 'jobmaster.id', '=', 'sales_order.job_id')
			                   ->where('sales_order.status',1)->where('sales_order.deleted_at','0000-00-00 00:00:00')
			                   ->select('jobmaster.id','jobmaster.code')->orderBy('jobmaster.id', 'DESC')->get();
			return $data;
	    
	}
	
	public function getPrint($id,$rid=null)
	{
		//$viewfile = DB::table('report_view')->where('code', 'SO')->where('status',1)->select('view_name')->first();
		$viewfile = DB::table('report_view_detail')->where('id', $rid)->select('print_name')->first();
		if($viewfile->print_name=='') {
			$attributes['document_id'] = $id;
			$attributes['is_fc'] = ($fc)?1:'';
			$result = $this->sales_order->getOrder($attributes);
			$titles = ['main_head' => 'Sales Order','subhead' => 'Sales Order'];
			return view('body.salesorder.print') //printsp print
						->withDetails($result['details'])
						->withTitles($titles)
						->withFc($attributes['is_fc'])
						->withItems($result['items']);
		} else {
			$path = app_path() . '/stimulsoft/helper.php';
			if(env('STIMULSOFT_VER')==2)
			        return view('body.reports')->withPath($path)->withView($viewfile->print_name);
			   else
			       return view('body.salesorder.viewer')->withPath($path)->withView($viewfile->print_name);
			
		}
		
	}
	
	public function setSessionVal()
	{
		Session::put('voucher_no', $request->get('vchr_no'));
		Session::put('reference_no', $request->get('ref_no'));
		Session::put('voucher_date', $request->get('vchr_dt'));
		Session::put('lpo_date', $request->get('lpo_dt'));
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
					  'total' => $pending_amt,'vat_amount' => $vat_amount, 'net_total' => $net_amount, 'salesman' => $salesman,
					  'jobcode' => $jobcode];
			
		}

		return $arr;
	}

	public function getSearch(Request $request)
	{
		$data = array();
		$pending=($request->get('pending'))?$request->get('pending'):0;
		$reports = $this->sales_order->getPendingReport($request->all());//echo '<pre>';print_r($reports);exit;
		
		if($request->get('search_type')=="summary" && $pending==0)
			$voucher_head = 'Sales Order Summary';
		elseif($request->get('search_type')=="summary" && $pending==1) {
			$voucher_head = 'Sales Order Pending Summary';
			$reports = $this->makeArrGroup($reports);
			$request->merge(['search_type' => 'summary_pending']); 
		} elseif($request->get('search_type')=="detail" && $pending==0) {
			$voucher_head = 'Sales Order Detail';
			$reports = $this->makeTree($reports);
		} elseif($request->get('search_type')=="jobwise") {
			$voucher_head = 'Sales Order - Jobwise';
		} elseif($request->get('search_type')=="customer_wise") {
			$voucher_head = 'Sales Order - Customer Wise';
		} else {
		     if($request->get('search_type')=="detail" && $pending==1){
			$voucher_head = 'Sales Order Pending Detail';
		     }
		     else{
		      $voucher_head = 'Sales Order Quantity Detail';   
		     }
			$reports = $this->makeTree($reports);
			$request->merge(['search_type' => 'detail_pending']);
		}
		
		//echo '<pre>';print_r($reports);exit;
		return view('body.salesorder.preprint') //preprint
					->withReports($reports)
					->withVoucherhead($voucher_head)
					->withType($request->get('search_type'))
					->withFromdate($request->get('date_from'))
					->withTodate($request->get('date_to'))
					->withJobids(json_encode($request->get('job_id')))
					->withSalesman($request->get('salesman'))
					->withCustomerid($request->get('customer_id'))
					->withSettings($this->acsettings)
					->withData($data);
	}
	
	public function dataExport(Request $request)
	{
		$data = array();
		$datareport[] = ['','','','',strtoupper(Session::get('company')),'','',''];
		$datareport[] = ['','','','','','',''];
		
		$request->merge(['type' => 'export']);
		$request->merge(['job_id' => json_decode($request->get('job_id'))]);
		$reports = $this->sales_order->getPendingReport($request->all());
		
		if($request->get('search_type')=="summary")
			$voucher_head = 'Sales Order Summary';
		elseif($request->get('search_type')=="summary_pending") {
			$voucher_head = 'Sales Order Pending Summary';
			$reports = $this->makeArrGroup($reports);
		} elseif($request->get('search_type')=="detail") {
			$voucher_head = 'Sales Order Detail';
		} elseif ($request->get('search_type')=="detail_pending"){
			$voucher_head = 'Sales Order Pending Detail';
		}else{
		    $voucher_head = 'Sales Order Quantity Detail';
		}
		
		$datareport[] = ['','','','',strtoupper($voucher_head), '','',''];
		$datareport[] = ['','','','','','',''];
		
		 //echo '<pre>';print_r($reports);exit;
		
		if($request->get('search_type')=='detail' ) {
			
			$datareport[] = ['SI.No.','SO.#', 'SO.Ref#', 'Job No', 'Customer','Salesman','Item Code','Description','SO.Qty','Rate','Total Amt.','Vat Amt','Net Amt.'];
			$i=$net_amount=0;
			foreach ($reports as $row) {
				$i++;
				$net_amount = $row->unit_vat + $row->line_total;
				$datareport[] = [ 'si' => $i,
								  'po' => $row['voucher_no'], 
								  'ref' => $row['reference_no'],
								  'jobcode' => $row['jobcode'],
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
			
			$datareport[] = ['SI.No.','SO.#', 'SO.Ref#', 'Job No', 'Customer','Salesman','Item Code','Description','SO.Qty','Rate','Total Amt.','Inv.Qty','Pending_qty','Rate','Total Amt'];
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
								  'jobcode' => $row['jobcode'],
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
		} 	else if( $request->get('search_type')=='qty_report') {
			
			$datareport[] = ['SI.No.','SO.#', 'SO.Ref#', 'Job No.', 'Customer','Salesman','Item Code','Description','Ordered','Processed','Balance'];
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
			
			$datareport[] = ['SI.No.','SO.#', 'SO.Ref#', 'Job No', 'Customer','Salesman','Gross Amt.','Discount','Total Amt.','VAT Amt.','Net Total'];
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
	
	public function getNewCustomer($deptid=null)
	{
		$data = array();
		$customers = $this->accountmaster->getCustomerList($deptid);//print_r($customers);exit;
		$country = $this->country->activeCountryList();
		$area = $this->area->activeAreaList();
		$cus_code = json_decode($this->ajax_getcode($category='CUSTOMER'));
		return view('body.salesorder.newcustomer')//customer
					->withCustomers($customers)
					->withArea($area)
					->withCusid($cus_code->code)
					->withCategory($cus_code->category)
					->withCountry($country)
					->withDeptid($deptid)
					->withData($data);
	}
	
	public function getItemDetails($id) 
	{
		$data = array();
		$items = $this->sales_order->getItems(array($id));
		return view('body.salesorder.itemdetails')
					->withItems($items)
					->withData($data);
	}
	public function getSalesOrder()
	{
		$data = array();
		$orders = $this->sales_order->getSOdata();
		//echo '<pre>';print_r($orders);exit;
		return view('body.salesorder.orders')
					->withOrders($orders)
					->withData($data);
	}
	
	
	public function poadd($id) {
		$data = array();
		$itemmaster = $this->itemmaster->activeItemmasterList();
		$terms = $this->terms->activeTermsList();
		$jobs = $this->jobmaster->activeJobmasterList();
		$currency = $this->currency->activeCurrencyList();
		$res = $this->voucherno->getVoucherNo('SO'); //echo '<pre>';print_r($res);exit;
		//$vno = $res->no;
		$row = DB::table('sales_order')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->orderBy('id','DESC')->select('id','doc_status')->first();
		$apr = ($this->acsettings->doc_approve==1)?[1]:[0,1,2];
		$location = $this->location->locationList();
		if($row && in_array($row->doc_status, $apr))
			$lastid = $row->id;
		else
			$lastid = null;
		
		$print = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','SO')
							->where('report_view_detail.is_default',1)
							->select('report_view_detail.id')
							->first();
							
		if($id) {
			$ids = explode(',', $id);
			$quoteRow = $this->purchase_order->findPOdata($ids[0]);
			$quoteItems = $this->purchase_order->getPOitems($ids);//echo '<pre>';print_r($quoteRow);exit;
			
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
			return view('body.salesorder.addquote')
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
						->withLocation($location)
						->withPoso(true)
						->withData($data);
		}
		
	}
	
	public function getOrderNo(Request $request) {
		
		if($request->get('search')=='') {
			$result = DB::table('sales_order')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')
						->where('customer_id',$request->get('cid'))
						->where('job_type',0)
						->select('id','voucher_no as text')
						->offset(0)->limit(10)
						->orderBy('id','DESC')
						->get();
		} else {
			$result = DB::table('sales_order')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')
						->where('customer_id',$request->get('cid'))
						->where('job_type',0)
						->where('voucher_no', 'like', '%' . $request->get('search') . '%')
						->select('id','voucher_no as text')
						->offset(0)->limit(10)
						->orderBy('id','DESC')
						->get();
		}
		
		//$data[] = array("id"=>12, "text"=>"AsD");
		echo json_encode($result);
	}
	
	public function getCounter($id) {
		
		$row = DB::table('sales_order')->where('id',$id)->select('voucher_no','jctype')->first();
		echo json_encode($row);
	}
	
	
		public function revice($id) { 

		$data = array();
		$itemmaster = $this->itemmaster->activeItemmasterList();
		$terms = $this->terms->activeTermsList();
		$jobs = $this->jobmaster->activeJobmasterList();
		$currency = $this->currency->activeCurrencyList();
		
		$orderrow = $this->sales_order->findOrderData($id);
		$orditems = $this->sales_order->getSORItems($id); //echo '<pre>';print_r($orderrow);exit;
		$location = $this->location->locationList();
		
		$apr = ($this->acsettings->doc_approve==1)?[1]:[0,1,2];
		if(in_array($orderrow->doc_status, $apr))
			$isprint = true;
		else
			$isprint = null;
		
		if($orderrow->job_type==1) { 
			$parentid = $orderrow->fabrication;
			$qrow = DB::table('sales_order')->where('id',$orderrow->fabrication)->select('voucher_no','jobnature')->first();
			$count = $qrow->jobnature + 1;
			$voucherno = $qrow->voucher_no.'/'.$count;
		} else { 
			$parentid = $orderrow->id;
			$count = $orderrow->jobnature + 1;
			$voucherno = $orderrow->voucher_no.'/'.$count;
		}
		
		$print = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','SO')
							->where('report_view_detail.is_default',1)
							->select('report_view_detail.id')
							->first();
							
		$infodata = DB::table('sales_order_info')->where('sales_order_id',$id)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->orderBy('id','ASC')->get();
		$row = DB::table('sales_order')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->orderBy('id','DESC')->select('id','doc_status')->first();
		$apr = ($this->acsettings->doc_approve==1)?[1]:[0,1,2];
		$location = $this->location->locationList();
		if($row && in_array($row->doc_status, $apr))
			$lastid = $row->id;
		else
			$lastid = null;
			
		//echo '<pre>';print_r($voucherno);exit;
		return view('body.salesorder.revice') //editsp  edit
					->withItems($itemmaster)
					->withTerms($terms)
					->withJobs($jobs)
					->withCurrency($currency)
					->withOrderrow($orderrow)
					->withOrditems($orditems)
					->withVatdata($this->vatdata)
					->withSettings($this->acsettings)
					->withFormdata($this->formData)
					->withIsprint($isprint)
					->withPrint($print)
					->withLocation($location)
					->withInfos($infodata)
					->withPrintid($lastid)
					->withVoucherno($voucherno)
					->withParentid($parentid)
					->withData($data);

	}
	
		public function windex() { 
		
		$data = array();
		$quotations = [];//$this->sales_order->quotationSalesList();
		$salesmans = $this->salesman->getSalesmanList();
		//$jobs = DB::table('jobmaster')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->where('is_salary_job',0)->select('id','code')->get();
		$custs = [];//DB::table('account_master')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->where('category','CUSTOMER')->select('id','master_name')->get();
		$jobs = $this->jobmaster->activeJobmasterList();
		$cus =DB::table('account_master')->where('category','CUSTOMER')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')
		->select('id','master_name')->get(); 
		return view('body.salesorder.windex')
					->withQuotations($quotations)
					->withSalesman($salesmans)
					->withJobs($jobs)
					->withCus($cus)
					->withCusts($custs)
					->withSettings($this->acsettings)
					->withData($data);
	}
	
	public function ajaxWorkPaging(Request $request)
	{
		if($this->acsettings->doc_approve==1) {
		    $columns = array( 
                            0 =>'sales_order.id', 
                            1 =>'voucher_no',
                            2=> 'customer',
                            3=> 'voucher_date',
                            4=> 'net_total',
							5=> 'status'
                        );
		} else {
		    $columns = array( 
                            0 =>'sales_order.id', 
                            1 =>'voucher_no',
                            2=> 'customer',
                            3=> 'voucher_date',
                            4=> 'net_total',
							5=> 'reference_no'
                        );
		}
						
		$totalData = $this->sales_order->salesOrderWorkListCount();
            
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = 'sales_order.voucher_no';
        $dir = 'desc';
		$search = (empty($request->input('search.value')))?null:$request->input('search.value');
        
		$invoices = $this->sales_order->salesOrderWorkList('get', $start, $limit, $order, $dir, $search);
		//echo '<pre>';print_r($invoices);exit;
		if($search)
			$totalFiltered =  $this->sales_order->salesOrderWorkList('count', $start, $limit, $order, $dir, $search);
		
		$prints = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','WO')
							->select('report_view_detail.name','report_view_detail.id')
							->get();
							
        $data = array();
        if(!empty($invoices))
        {
           
			foreach ($invoices as $row)
            {
                $edit =  '"'.url('sales_order/edit/'.$row->id).'"';
                $delete =  'funDelete("'.$row->id.'","'.$row->is_editable.'")';
				$print = url('sales_order/print/'.$row->id);
			
                $nestedData['id'] = $row->id;
                $nestedData['voucher_no'] = $row->voucher_no;
				$nestedData['voucher_date'] = date('d-m-Y', strtotime($row->voucher_date));
				$nestedData['customer'] = ($row->customer=='CASH CUSTOMERS') ? (($row->customer_name!='')?$row->customer.'('.$row->customer_name.')':$row->customer) : $row->customer;
				$nestedData['net_total'] = $row->net_total;
				$nestedData['reference_no'] = $row->reference_no;
				
                $nestedData['edit'] = "<p><button class='btn btn-primary btn-xs' onClick='location.href={$edit}'>
												<span class='glyphicon glyphicon-pencil'></span></button></p>";
										
		       
																		
				$nestedData['delete'] = "<button class='btn btn-danger btn-xs delete' onClick='{$delete}'>
												<span class='glyphicon glyphicon-trash'></span>";
				
				$apr = ($this->acsettings->doc_approve==1)?[1]:[0,1,2];
				
				$opts = '';					
				foreach($prints as $doc) {
					$opts .= "<li role='presentation'><a href='{$print}/".$doc->id."' target='_blank' role='menuitem'>".$doc->name."</a></li>";
				}
				
				if(in_array($row->doc_status, $apr))	 {
					if($row->is_fc==1) {								
						$nestedData['print'] = "<p><a href='{$print}' target='_blank' class='btn btn-primary btn-xs'><span class='fa fa-fw fa-print'></span></a>
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
					$nestedData['print'] = "";
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
	
	
	public function getReport($id) {
		
		$result = $this->sales_order->getReportById($id);
		echo '<pre>';print_r($result);exit;
	}

	public function ajaxCustomerList(Request $request)
	{
		$columns = array( 
                            0 =>'account_id', 
                            1 =>'master_name',
                            2 =>'category',
                            3=> 'cl_balance'
                           // 4=> 'op_balance'
                        );
		
		$limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
		$search = (empty($request->input('search.value')))?null:$request->input('search.value');
        $dept = $request->input('dept');
		$cat = $request->input('type');
		$ntype = $request->input('ntype');
		$arrtype = ['dept' => $dept, 'cat' => $cat, 'ntype' => $ntype];

		$totalFiltered = $totalData = $this->accountmaster->accountMasterList('count', $start, $limit, $order, $dir, $search, $arrtype);
		
		$acmasters = $this->accountmaster->accountMasterList('get', $start, $limit, $order, $dir, $search, $arrtype);
		
		//if($search)
			//$totalFiltered =  $this->accountmaster->accountMasterList('count', $start, $limit, $order, $dir, $search, $dept);
		
		//echo  $acmasters;exit;
	$data = array();
	if(!empty($acmasters))
	{
		foreach ($acmasters as $row)
		{
			
			$rid = $row->id;
			$nestedData['id'] = $row->id;
			$nestedData['account_id'] = "<a href='' class='custRow' data-id='".$row->id."' data-vatassign='".$row->vat_assign."' data-name='".$row->master_name."' data-duedays='".$row->duedays."' data-clbalance='".number_format($row->cl_balance,2)."' data-pdc='".number_format($row->pdc_amount,2)."' data-crlimit='".number_format($row->credit_limit,2)."' data-groupid='".$row->account_group_id."' data-trnno='".$row->vat_no."' data-group='".$row->category."' data-salesman='".$row->salesman."'data-salesmanid='".$row->salesman_id."' data-term='".$row->terms_id."' data-vat='".$row->vat_percentage."' data-dismiss='modal'>".$row->account_id."</a>";
			$nestedData['master_name'] = "<a href='' class='custRow' data-id='".$row->id."' data-vatassign='".$row->vat_assign."' data-name='".$row->master_name."' data-duedays='".$row->duedays."' data-clbalance='".number_format($row->cl_balance,2)."' data-pdc='".number_format($row->pdc_amount,2)."' data-crlimit='".number_format($row->credit_limit,2)."' data-groupid='".$row->account_group_id."' data-trnno='".$row->vat_no."' data-group='".$row->category."' data-salesman='".$row->salesman."'data-salesmanid='".$row->salesman_id."' data-term='".$row->terms_id."' data-vat='".$row->vat_percentage."' data-dismiss='modal'>".$row->master_name."</a>";
		    $nestedData['category'] = $row->category;
			$nestedData['group'] = $row->group_name;
			$nestedData['cl_balance'] = $row->cl_balance;
			//$nestedData['op_balance'] = $row->op_balance;
			
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

	public function getAccount($type=null)
	{
		$data = array();
		$accounts = $this->accountmaster->getAccountByGroup($type);
		return view('body.salesorder.account')
					->withAccount($accounts)
					->withData($data);
	}
	
	public function refreshSO($id) { //SI id
	    
		$itms = DB::table('sales_order')->where('sales_order.id', $id)
					->join('sales_order_item','sales_order_item.sales_order_id','=','sales_order.id')
					->select('sales_order_item.id','sales_order_item.item_id','sales_order_item.quantity','sales_order_item.balance_quantity','sales_order.id AS do_id')
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
            

			$row1 = DB::table('sales_order_item')->where('sales_order_id', $id)->count();
			$row2 = DB::table('sales_order_item')->where('sales_order_id', $id)->where('is_transfer',1)->count();
			$row3 = DB::table('sales_order_item')->where('sales_order_id', $id)->where('is_transfer',2)->count(); 
			if($row1==$row2) {
				DB::table('sales_order')
						->where('id', $id)
						->update(['is_editable' => 1, 'is_transfer' => 1]);
			} else if($row1 > 0 && $row2==0 && $row3==0) {
			    DB::table('sales_order')
						->where('id', $id)
						->update(['is_editable' => 0, 'is_transfer' => 0]);
			} else if($row3 > 0) {
			    DB::table('sales_order')
						->where('id', $id)
						->update(['is_editable' => 1, 'is_transfer' => 0]);
			}
		}
		
		return redirect('sales_order');
			
	//return true;
	}
}


