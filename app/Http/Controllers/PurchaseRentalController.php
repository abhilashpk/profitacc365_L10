<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Repositories\PurchaseRental\PurchaseRentalInterface;

use App\Http\Requests;
use Session;
use Response;
use Input;
use Excel;
use App;
use DB;
use Auth;

class PurchaseRentalController extends Controller
{
	protected $purchase_invoice;
	
	public function __construct(PurchaseRentalInterface $purchase_invoice) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->purchase_invoice = $purchase_invoice;
		$this->middleware('auth');
	}
	
	public function index() {
		$supplier = DB::table('account_master')
			->where('category','SUPPLIER')
			->where('deleted_at','0000-00-00 00:00:00')
			->where('status',1)
			->select('id','master_name')->get();
		//echo '<pre>';print_r($supplier);exit;
		return view('body.purchaserental.index')
		   ->withSupplier($supplier);
	}
	
	public function ajaxPaging(Request $request)
	{
		$columns = array( 
                            0 => 'purchase_rental.id', 
                            1 => 'prno',
                            2 => 'pr_date',
							3 => 'supplier',
                            4 => 'amount'
                        );
						
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')]; 
        $dir = $request->input('order.0.dir'); 
		$search = (empty($request->input('search.value')))?null:$request->input('search.value');
        
		$totalData = $this->purchase_invoice->getPurchaseRentalList('count', $start, $limit, $order, $dir, $search);
        $totalFiltered = $totalData;
		
		if($search)
			$totalFiltered = $totalData = $this->purchase_invoice->getPurchaseRentalList('count', $start, $limit, $order, $dir, $search);
			
		$invoices = $this->purchase_invoice->getPurchaseRentalList('get', $start, $limit, $order, $dir, $search);
		
		$prints = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','PIR')
							->select('report_view_detail.name','report_view_detail.id','report_view_detail.print_name')
							->get();
		
        $data = array();
        if(!empty($invoices))
        {
			foreach ($invoices as $row)
            {
                $edit =  '"'.url('purchase_rental/edit/'.$row->id).'"';
                $delete =  'funDelete("'.$row->id.'")';
				$print = url('purchase_rental/print/'.$row->id);
				
                $nestedData['id'] = $row->id;
                $nestedData['voucher_no'] = $row->voucher_no;
				$nestedData['voucher_date'] = date('d-m-Y', strtotime($row->voucher_date));
				$nestedData['supplier'] = $row->master_name;
				$nestedData['amount'] = $row->net_amount;
				//$nestedData['print'] = "<p><a href='{$print}' target='_blank'  role='menuitem' class='btn btn-primary btn-xs'><span class='fa fa-fw fa-print'></span></a></p>";
				
				$nestedData['edit'] = "<p><button class='btn btn-primary btn-xs' onClick='location.href={$edit}'>
												<span class='glyphicon glyphicon-pencil'></span></button></p>";
												
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
	
	
	public function add() {
	
		$print = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','PIR')
							->where('report_view_detail.is_default',1)
							->select('report_view_detail.name','report_view_detail.id','report_view_detail.print_name')
							->first();
							
		$vouchers = DB::table('account_setting')->where('account_setting.voucher_type_id',25)
								->join('account_master','account_master.id','=','account_setting.dr_account_master_id')
								->select('account_setting.id AS vid','account_setting.voucher_name','account_setting.voucher_no',
										'account_master.id','account_master.master_name')
								->first();
								
		$lastid = DB::table('purchase_rental')->where('status',1)->where('deleted_at',null)->orderBy('id','DESC')->select('id')->first();
		
		$units = DB::table('units')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		return view('body.purchaserental.add')->withUnits($units)->withVouchers($vouchers)->withPrint($print)->withPrintid($lastid);
					
	}
	
	public function save(Request $request) {
		//echo '<pre>';print_r($request->all());exit;
		
		if($this->purchase_invoice->create($request->all())) {
			Session::flash('message', 'Purchase rental added successfully.');
		} else {
			Session::flash('error', 'Something went wrong, Puchase failed to add!');
		}
		return redirect('purchase_rental/add');
	}
	
	public function edit($id) {
	
		$row = $this->purchase_invoice->findPRdata($id);
		$items = $this->purchase_invoice->getItems($id); 
		//echo '<pre>';print_r($items);exit;					
		$units = DB::table('units')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		
		$print = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','PIR')
							->where('report_view_detail.is_default',1)
							->select('report_view_detail.name','report_view_detail.id','report_view_detail.print_name')
							->first();
							
		return view('body.purchaserental.edit')->withUnits($units)
					->withRow($row)
					->withPrint($print)
					->withItems($items);
					
	}
	
	public function update(Request $request, $id)
	{ 	//echo '<pre>';print_r($request->all());exit;
		if( $this->purchase_invoice->update($id, $request->all()) ) {
			Session::flash('message', 'Purchase updated successfully');
		} else
			Session::flash('error', 'Something went wrong, Purchase failed to update!');
		
		return redirect('purchase_rental');
	}
	
	public function destroy($id)
	{
		if($this->purchase_invoice->delete($id)) {
			
			Session::flash('message', 'Purchase deleted successfully.');
		} else
			Session::flash('error', 'Something went wrong, Purchase failed to delete!');
		
		return redirect('purchase_rental');
	}
	
	public function getDriver($no,$id) {
		$drivers = DB::table('rental_driver')->where('account_id',$id)->get(); 
		
		return view('body.purchaserental.driver')->withDrivers($drivers)->withNum($no);
	}
	
	public function getPrint($id,$rid=null)
	{ 
		$viewfile = DB::table('report_view_detail')->where('id', $rid)->select('print_name')->first(); 
			
		if(isset($viewfile) && $viewfile->print_name=='') {
			$voucherhead = 'PURCHASE RENTAL';
			$row = $this->purchase_invoice->findRSdata($id);
			$items = $this->purchase_invoice->getItems($id); 
							//echo '<pre>';print_r($items);exit;
			return view('body.rentalsales.print')
						->withVoucherhead($voucherhead)
						->withRow($row)
						->withItems($items);
		} else {
					
			$path = app_path() . '/stimulsoft/helper.php';
			if(isset($viewfile))
				return view('body.purchaserental.viewer')->withPath($path)->withView($viewfile->print_name);
		}
		
	}
	
	protected function makeSummary($result)
	{
		$childs = array();
		foreach($result as $item)
			$childs[$item->voucher_no][] = $item;
		
		return $childs;
	}
	
	public function searchReport(Request $request) {
		//echo '<pre>';print_r($request->all());exit;
		$voucherhead='Purchase Retal Summary Report';
		$voucherheadd='Purchase Retal Detail Report';
		if($request->get('search_type')=='summary')
			$reports = $this->purchase_invoice->getReport($request->all());
		else
			$reports = $this->purchase_invoice->getReport($request->all());
		//echo '<pre>';print_r($reports);exit;
		return view('body.purchaserental.report')
							->withReports($reports)
							->withFromdate($request->get('date_from'))
							->withTodate($request->get('date_to'))
							->withType($request->get('search_type'))
							->withSupplier($request->get('supplier'))
							->withVoucherhead($voucherhead)
							->withVoucherheadd($voucherheadd);
	}              
	
	public function dataExport(Request $request){
		$data = array();
		if($request->get('search_type')=='summary'){
			$reports = $this->purchase_invoice->getReport($request->all());
		 
			//echo '<pre>';print_r($reports);exit;
			$voucherhead='Purchase Retal Summary Report';
			$fromdate=$request->get('date_from');
			$todate=$request->get('date_to');	
			$datareport[] = ['','','','',strtoupper(Session::get('company')),'','',''];	
			$datareport[] = ['','','','','','',''];
			$datareport[] = ['','','','',strtoupper($voucherhead),'','',''];	
			$datareport[] = ['','','','','','',''];
			$datareport[] = ['Date.  From:',$fromdate,'To:',$todate,'',''];
			$datareport[] = ['','','','','','',''];
			$datareport[] = ['','','','','','',''];
			$datareport[] = ['PR#','Vchr.Date','Supplier Name','Gross Amt.','Discount','VAT Amt.','Net Total'];	
			$total=0;	
			$tot=0;
			$gross_total = $discount_total = $vat_total = $net_total = 0;
			foreach($reports as $row) {
				$gross_total += $row->total;
				$discount_total += $row->discount;
				$vat_total += $row->vat_amount;
				$net_total += $row->net_amount;
				$datareport[] = [ 'PR#' => $row->voucher_no  ,
								  'Vchr.Date' => date('d-m-Y',strtotime($row->voucher_date)),
								  'Supplier Name' => $row->master_name,
								  'Gross Amt.' => number_format($row->total,2),
								  'Discount' => number_format($row->discount,2),
								  'VAT Amt.'=>number_format($row->vat_amount,2),
								  'Net Total'=>number_format($row->net_amount,2)
								];
								$gt=number_format($gross_total,2);
								$dt=number_format($discount_total,2);
								$vt=number_format($vat_total,2);
								$nt=number_format($net_total,2);
								
							}
				$datareport[] = ['','','','','','',''];			
				$datareport[] = ['','','Total:',$gt,$dt,$vt,$nt];
	
			Excel::create($voucherhead, function($excel) use ($datareport,$voucherhead) {
	
									// Set the spreadsheet title, creator, and description
									$excel->setTitle($voucherhead);
									$excel->setCreator('Profit ACC 365 - ERP')->setCompany(Session::get('company'));
									$excel->setDescription($voucherhead);
						
									// Build the spreadsheet, passing in the payments array
									$excel->sheet('sheet1', function($sheet) use ($datareport) {
										$sheet->fromArray($datareport, null, 'A1', false, false);
									});
						
								})->download('xlsx');					
							}		
		else{
			$reports = $this->purchase_invoice->getReport($request->all());
			$voucherhead='Purchase Retal Detail Report';
			$fromdate=$request->get('date_from');
			$todate=$request->get('date_to');	
			$datareport[] = ['','','','',strtoupper(Session::get('company')),'','',''];	
			$datareport[] = ['','','','','','',''];
			$datareport[] = ['','','','',strtoupper($voucherhead),'','',''];	
			$datareport[] = ['','','','','','',''];
			$datareport[] = ['Date.  From:',$fromdate,'To:',$todate,'',''];
			$datareport[] = ['','','','','','',''];
			$datareport[] = ['','','','','','',''];
			$datareport[] = ['PR#','Supplier Name','Date','Description','Driver','Type','Duration','Rate','Extra.Hrs','Extra.Hr.Rate','Gross Amt.','VAT Amt.','Net Total'];	
			$gross_total = $discount_total = $vat_total = $net_total = 0;
			foreach($reports as $row) {
				$gross_total+=$row->total;
				$vat_total += $row->vat_amount;
				$net_total += $row->net_amount;
				$datareport[] = [ 'PR#' => $row->voucher_no  ,
								  'Supplier Name' => $row->master_name,
								  'Date' => date('d-m-Y',strtotime($row->voucher_date)),
								  'Description' =>$row->description,
								  'Driver'=>$row->driver_name,
								  'Type'=>$row->unit_description,
								  'Duration'=>$row->quantity,
								  'Rate'=>$row->rate,
								  'Extra.Hrs'=>$row->extra_hr,
								  'Extra.Hr.Rate'=>$row->extra_rate,
								  'Gross Amt.'=>number_format($row->total,2),
								  'VAT Amt.'=>number_format($row->vat_amount,2),
								  'Net Total'=>number_format($row->net_amount,2)
								];
								$gt=number_format($gross_total,2);
								$vt=number_format($vat_total,2);
								$nt=number_format($net_total,2);
								
							}
				$datareport[] = ['','','','','','',''];			
				$datareport[] = ['','','','','','','','','','Total:',$gt,$vt,$nt];
	
						
			
			Excel::create($voucherhead, function($excel) use ($datareport,$voucherhead) {
	
									// Set the spreadsheet title, creator, and description
									$excel->setTitle($voucherhead);
									$excel->setCreator('Profit ACC 365 - ERP')->setCompany(Session::get('company'));
									$excel->setDescription($voucherhead);
						
									// Build the spreadsheet, passing in the payments array
									$excel->sheet('sheet1', function($sheet) use ($datareport) {
										$sheet->fromArray($datareport, null, 'A1', false, false);
									});
						
								})->download('xlsx');		
			}
	}
}
	
//SELECT purchase_rental.voucher_no,purchase_rental.voucher_date,purchase_rental.total,purchase_rental.discount,purchase_rental.vat_amount,purchase_rental.net_amount,account_master.account_id,account_master.master_name,account_master.address,account_master.phone,account_master.vat_no,purchase_rental_item.service_date,purchase_rental_item.quantity,purchase_rental_item.rate,purchase_rental_item.vat,purchase_rental_item.vat_amount AS line_vat,purchase_rental_item.line_total,purchase_rental_item.extra_hr,purchase_rental_item.extra_rate,itemmaster.description,units.unit_name,purchase_rental_item.id AS sii_id,rental_driver.driver_name FROM purchase_rental JOIN account_master ON(account_master.id=purchase_rental.supplier_id) JOIN purchase_rental_item ON(purchase_rental_item.purchase_rental_id=purchase_rental.id) JOIN itemmaster ON(itemmaster.id=purchase_rental_item.item_id) JOIN units ON(units.id=purchase_rental_item.unit_id) LEFT JOIN rental_driver ON(rental_driver.id=purchase_rental_item.driver_id) WHERE purchase_rental_item.deleted_at IS NULL AND purchase_rental.id=2 ORDER BY purchase_rental_item.id ASC
	
