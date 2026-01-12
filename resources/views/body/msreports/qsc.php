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
use Input;
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
		if(Auth::user()->roles[0]->name=='Salesman') {
		    $srec = DB::table('salesman')->where('name',Auth::user()->name)->select('id')->first();
		    if($srec)
		        Session::set('salesman_id',$srec->id);
		}
		
		
	}
	
    public function index() {
		
		$data = array();
		$quotations = [];//$this->quotation_sales->quotationSalesList();echo '<pre>';print_r($quotations);exit;
		$salesmans = $this->salesman->getSalesmanList();
		$jobs = $this->jobmaster->activeJobmasterList();
		return view('body.quotationsales.index')
					->withQuotations($quotations)
					->withSalesman($salesmans)
					->withSettings($this->acsettings)
					->withJobs($jobs)
					->withData($data);
	}
	
	public function ajaxPaging(Request $request)
	{
		$columns = array( 
                            0 =>'quotation_sales.id', 
                            1 =>'voucher_no',
                            2=> 'voucher_date',
                            3=> 'customer',
                            4=>'project',
							5=> 'net_total',
							6=> 'status'
                        );
						
		$totalData = $this->quotation_sales->salesEstimateListCount();
            
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = 'bin_name1'; //$order = 'quotation_sales.id';
        $dir = 'desc';
		$search = (empty($request->input('search.value')))?null:$request->input('search.value');
        
		$invoices = $this->quotation_sales->salesEstimateList('get', $start, $limit, $order, $dir, $search);
		
		if($search)
			$totalFiltered =  $this->quotation_sales->salesEstimateList('count', $start, $limit, $order, $dir, $search);
		
		$prints = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','QS')
							->select('report_view_detail.name','report_view_detail.id')
							->get();
		
        $data = array();
        //echo '<pre>';print_r($invoices);exit;
        if(!empty($invoices))
        {
           
			foreach ($invoices as $row)
            {
                $edit =  url('quotation_sales/edit/'.$row->id);
                $revice =  url('quotation_sales/revice/'.$row->id);
                $delete =  'funDelete("'.$row->id.'","'.$row->is_editable.'")';
				$print = url('quotation_sales/print/'.$row->id);
				$open =  '"'.url('quotation_sales/doc_open/'.$row->id).'"';
				
				
                $nestedData['id'] = $row->id;
                $nestedData['voucher_no'] = 'EQ/'.$row->voucher_no;
				$nestedData['voucher_date'] = date('d-m-Y', strtotime($row->voucher_date));
				$nestedData['customer'] = ($row->customer=='CASH CUSTOMERS') ? (($row->customer_name!='')?$row->customer.'('.$row->customer_name.')':$row->customer) : $row->customer;
				$nestedData['net_total'] = $row->net_total;
                $nestedData['project'] = $row->description;
                $nestedData['edit'] = "<div class='btn-group drop_btn' role='group'>
											<button type='button' class='btn btn-primary btn-xs dropdown-toggle m-r-50'
													id='exampleIconDropdown1' data-toggle='dropdown' aria-expanded='false'>
												<i class='fa fa-fw fa-shield'></i><span class='caret'></span>
											</button>
											<ul style='min-width:100px !important;' class='dropdown-menu' aria-labelledby='exampleIconDropdown1' role='menu'>
												<li role='presentation'><a href='{$edit}' role='menuitem'>Edit</a></li>
												<li role='presentation'><a href='{$revice}' role='menuitem'>Revise</a></li>
											</ul>
										</div>";
												
				$nestedData['open'] = "<p><button class='btn btn-success btn-xs'  onClick='location.href={$open}' target='_blank'>
												<i class='fa fa-fw fa-folder-open'></i></button></p>";
													
				
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
	public function add($id = null) {

		$data = array();
		$itemmaster = $this->itemmaster->activeItemmasterList();
		$terms = $this->terms->activeTermsList();
		$jobs = $this->jobmaster->activeJobmasterList();
		$currency = $this->currency->activeCurrencyList();
		$res = $this->voucherno->getVoucherNo('QS');
		//$vno = $res->no;//echo '<pre>';print_r($currency);exit;
		$location = $this->location->locationList();
		$row = DB::table('quotation_sales')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->orderBy('id','DESC')->select('id','doc_status')->first();
		$apr = ($this->acsettings->doc_approve==1)?[1]:[0,1,2];
		if($row && in_array($row->doc_status, $apr))
			$lastid = $row->id;
		else
			$lastid = null;
		
		$print = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','QS')
							->where('report_view_detail.is_default',1)
							->select('report_view_detail.id')
							->first();
		
		$fcontent = DB::table('header_footer')->where('doc','QS')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('description')->first();
							
		if($id) {
			$ids = explode(',', $id);
			$quoteRow = $this->customer_enquiry->findQuoteData($ids[0]);
			$quoteItems = $this->customer_enquiry->getCEitems($ids);//echo '<pre>';print_r($quoteRow);exit;
			
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
			return view('body.quotationsales.addce')
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
						->withData($data);
		}
		
		return view('body.quotationsales.add')
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
					->withFcontent($fcontent)
					->withData($data);
	}
	
	public function save(Request $request) { //echo '<pre>';print_r( Input::all() );exit;
		
		/* $this->validate($request, [
        'reference_no' => 'required', 'voucher_date' => 'required','item_code.*' => 'required'
    ]); */
		if( $this->validate(
			$request, 
			[//'reference_no' => 'required',
			 'customer_name' => 'required','customer_id' => 'required',
			 'item_code.*'  => 'required', 'item_id.*' => 'required',
			 //'unit_id.*' => 'required',
			 //'quantity.*' => 'required',
			 //'cost.*' => 'required'
			],
			[//'reference_no.required' => 'Reference no. is required.',
			 'customer_name.required' => 'Customer Name is required.','customer_id.required' => 'Customer name is invalid.',
			 'item_code.*.required'   => 'Item code is required.', 'item_id.*' => 'Item code is invalid.',
			 //'unit_id.*' => 'Item unit is required.',
			 //'quantity.*' => 'Item quantity is required.',
			 //'cost.*' => 'Item cost is required.'
			]
		)) {
			//echo '<pre>';print_r($request->flash());exit;
			return redirect('quotation_sales/add')->withInput()->withErrors();
		}
		
		$id = $this->quotation_sales->create(Input::all());
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
	
	public function checkRefNo() {

		$check = $this->quotation_sales->check_reference_no(Input::get('reference_no'), Input::get('id'));
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
		
		$print = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','QS')
							->where('report_view_detail.is_default',1)
							->select('report_view_detail.id')
							->first();
		
		//DEC22
		$infodata = DB::table('quotation_sales_info')->where('quotation_sales_id',$id)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->orderBy('id','ASC')->get();
		//echo '<pre>';print_r($infoedit);exit;	
		return view('body.quotationsales.edit')
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
		//echo '<pre>';print_r(Input::all());exit;
		$id = $request->input('quotation_order_id');
		if( $this->validate(
			$request, 
			[
			 //'customer_name' => 'required','customer_id' => 'required',
			 'item_code.*'  => 'required', 'item_id.*' => 'required',
			 //'unit_id.*' => 'required',
			 //'quantity.*' => 'required',
			 //'cost.*' => 'required'
			],
			[
			 //'customer_name.required' => 'Customer Name is required.','customer_id.required' => 'Customer name is invalid.',
			 'item_code.*.required'   => 'Item code is required.', 'item_id.*' => 'Item code is invalid.',
			 //'unit_id.*' => 'Item unit is required.',
			 //'quantity.*' => 'Item quantity is required.',
			 //'cost.*' => 'Item cost is required.'
			]
		)) {
			
			return redirect('quotation_sales/edit/'.$id)->withInput()->withErrors();
		}
		
		$this->quotation_sales->update($id, Input::all()); 
		//echo '<pre>';print_r(Input::all());exit;
		
		########## email script #############
		if($this->acsettings->doc_approve==1 && Input::get('doc_status')==1 && Input::get('chkmail')==1) {
					
			$attributes['document_id'] = $id;
			$attributes['is_fc'] = '';//($fc)?1:'';
			$result = $this->quotation_sales->getQuotation($attributes);
			$itemdesc = $this->makeTreeArr($this->quotation_sales->getItemDesc($id));
			$titles = ['main_head' => 'Quotation Sales','subhead' => 'Quotation'];
			$data = array('details'=> $result['details'], 'titles' => $titles, 'fc' => $attributes['is_fc'], 'itemdesc' => $itemdesc, 'items' => $result['items']);
			//echo '<pre>';print_r($pdata);exit;
			$pdf = PDF::loadView('body.quotationsales.pdfprint', $data); //echo $pdf->output(); exit;
			//echo $pdfview = $this->getPrint($id,null,'PDF');exit;
			
			//$cust = DB::table('account_master')->where('id', Input::get('customer_id'))->select('master_name','email','contact_name')->first();
			$mailmessage = Input::get('email_message');
			$emails = explode(',', Input::get('email'));
			
			if($emails[0]!='') {
				$data = array('name'=> Input::get('customer_name'), 'mailmessage' => $mailmessage );
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
	
	public function getQuotation($customer_id, $url)
	{
		$data = array();
		$quotations = $this->quotation_sales->getCustomerQuotation($customer_id);//print_r($quotations);exit;
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
	//	echo '<pre>';print_r($viewfile);exit;
		$prints = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','QS')
							->select('report_view_detail.name','report_view_detail.id')
							->get();
							// echo '<pre>';print_r($prints);exit;
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
						->withId($id)
						->withDoc($prints)
						->withItemdesc($itemdesc)
						->withAmtwords($words)
						->withItems($result['items']);
		} else {
			$path = app_path() . '/stimulsoft/helper.php';
			return view('body.quotationsales.viewer')->withPath($path)->withView($viewfile->print_name);
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
	public function printExport($id)
	{
	    $voucher_head="eqwep Report";
	    $attributes['document_id'] = $id;
		$result = $this->quotation_sales->getQuotation($attributes);
		$detail=$result['details'];
		$item=$result['items'];
							//echo '<pre>';print_r($item);exit;
		$date=date('d-m-Y',strtotime($detail->voucher_date));				
		$datareport[] = ['Customer:',$detail->supplier,'','','','','','',''];
		$datareport[] = ['Attn:',$detail->reference_no,'','','','','','',''];	
		$datareport[] = ['Project:',$detail->description,'','','','','Date:',$date,''];	
		$datareport[] = ['Subject:',$detail->subject,'','','','','Qtn. Ref.:',$detail->voucher_no,''];	
		
		$datareport[] = ['','','','','','','','',''];
		$datareport[] = ['','','','','','','','',''];
		$datareport[] = ['Si No','Item Reference','Brand','Material Description','Code','Qty.','Image','AED Unit Price','AED Total Price'];
		$i=0;
		$total=number_format($detail->net_total,2);
		$net=number_format($detail->total,2);
		$tax=number_format($detail->vat_amount,2);
	$gdImage = asset('uploads/item/');
			foreach ($item as $row) {
				$i++;
					
				$datareport[] = [ 'si' => $i,
								  'ref' => $row['remarks'],
								  'brand' => $row['group_name'],
								  'material' => $row['item_name'],
								  'code' => $row['item_code'],
								  'qty' =>(int)$row['quantity'],
								  'image' =>'',//$gdImage.$row->image.'' ,
								  'unit_price' => number_format((float)$row->unit_price,2),
								  'total_price' => number_format((float)$row->item_total,2)
								];
			}
			
		/*	$drawings = [];
			$drawing = '';
        foreach($item as $key=>$row)
        {
            //$drawing = new Drawing();
            $drawing->setPath(asset('uploads/item/').$row->image.'');
            $drawing->setHeight(50);
            $drawing->setWidth(120);
            $drawing->setCoordinates('K'.($key+1));
            $drawings [] = ($drawing);
        }*/
		$datareport[] = ['','','','','','','','',''];
		$datareport[] = ['','','','','','','','',''];
		
		$datareport[] = ['','','','','','Net Price:','','',$net];
		$datareport[] = ['','','','','','Tax 5%:','','',$tax];
		$datareport[] = ['','','','','','Total Price with Tax:','','',$total];
		
		
		Excel::create($voucher_head, function($excel) use ($datareport,$voucher_head) {

								// Set the spreadsheet title, creator, and description
								$excel->setTitle($voucher_head);
								$excel->setCreator('eqwep')->setCompany(Session::get('logo'));
								$excel->setDescription($voucher_head);
					
								// Build the spreadsheet, passing in the payments array
								$excel->sheet('sheet1', function($sheet) use ($datareport) {
									$sheet->fromArray($datareport, null, 'A1', false, false);
								});
					
							})->download('xlsx');					
							
	}
	public function getSearch()
	{
		$data = array();
		
		$reports = $this->quotation_sales->getPendingReport(Input::all());
		
		if(Input::get('search_type')=="summary")
			$voucher_head = 'Quotation Sales Summary';
		elseif(Input::get('search_type')=="summary_pending") {
			$voucher_head = 'Quotation Sales Pending Summary';
			$reports = $this->makeArrGroup($reports);
		} elseif(Input::get('search_type')=="detail") {
			$voucher_head = 'Quotation Sales Detail';
			$reports = $this->makeTree($reports);
		} else {
			$voucher_head = 'Quotation Sales Pending Detail';
			$reports = $this->makeTree($reports);
		}
		
		//echo '<pre>';print_r($reports);exit;
		return view('body.quotationsales.preprint')
					->withReports($reports)
					->withVoucherhead($voucher_head)
					->withType(Input::get('search_type'))
					->withFromdate(Input::get('date_from'))
					->withTodate(Input::get('date_to'))
					->withSalesman(Input::get('salesman'))
					->withSettings($this->acsettings)
					->withJobids(json_encode(Input::get('job_id')))
					->withData($data);
	}
	
	
	public function dataExport()
	{
		$data = array();
		$datareport[] = ['','','','',strtoupper(Session::get('company')),'','',''];
		$datareport[] = ['','','','','','',''];
		
		Input::merge(['type' => 'export']);
		Input::merge(['job_id' => json_decode(Input::get('job_id'))]);
		$reports = $this->quotation_sales->getPendingReport(Input::all());
		
		if(Input::get('search_type')=="summary")
			$voucher_head = 'Quotation Sales Summary';
		elseif(Input::get('search_type')=="summary_pending") {
			$voucher_head = 'Quotation Sales Pending Summary';
			$reports = $this->makeArrGroup($reports);
		} elseif(Input::get('search_type')=="detail") {
			$voucher_head = 'Quotation Sales Detail';
		} else {
			$voucher_head = 'Quotation Sales Pending Detail';
		}
		
		 //echo '<pre>';print_r($reports);exit;
		
		$datareport[] = ['','','','',strtoupper($voucher_head), '','',''];
		$datareport[] = ['','','','','','',''];
		
		if(Input::get('search_type')=='detail' || Input::get('search_type')=='detail_pending') {
			
			$datareport[] = ['SI.No.','Qtn.#', 'Qtn.Ref#', 'Job No.', 'Customer','Salesman','Item Code','Description','Qtn.Qty','Rate','Total Amt.'];
			$i=0;
			foreach ($reports as $row) {
				$i++;
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
								  'net_amount' => number_format($row['net_amount'],2)
								];
			}
		} else {
			
			$datareport[] = ['SI.No.','Qtn.#', 'Qtn.Ref#', 'Job No.', 'Customer','Salesman','Gross Amt.','VAT Amt.','Net Total'];
			$i=0;
			$total=0;$gross=0;$vat=0;	
		    $tot=0;$gs=0;$vt=0;
			foreach ($reports as $row) {
					$i++;
					$datareport[] = [ 'si' => $i,
									  'po' => $row['voucher_no'],
									  'ref' => $row['reference_no'],
									  'jobcode' => $row['jobcode'],
									  'supplier' => $row['master_name'],
									  'salesman' => $row['salesman'],
									  'gross' => number_format($row['total'],2),
									  'vat' => number_format($row['vat_amount'],2),
									  'total' => number_format($row['net_total'],2)
									  
									];
									$total+= $row['net_total'];
							        $tot=number_format($total,2) ;
									$gross+= $row['total'];
							        $gs=number_format($gross,2) ;
									$vat+= $row['vat_amount'];
							        $vt=number_format($vat,2) ;
			}
			$datareport[] = ['','','','','','',''];			
		    $datareport[] = ['','','','','','Total:',$gs,$vt,$tot];
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
	
	public function checkVchrNo() {

		$check = $this->quotation_sales->check_voucher_no(Input::get('voucher_no'), Input::get('id'));
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
		return view('body.quotationsales.revice-eqwep')
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
		   
			$what   = "\\x00-\\x20";    //all white-spaces and control chars
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
	
}
