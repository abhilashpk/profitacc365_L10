<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Itemmaster\ItemmasterInterface;

use App\Http\Requests;
use Notification;
use Input;
use Session;
use Excel;
use App;
use DB;

class StockMovementController extends Controller
{
	protected $itemmaster;
	
	public function __construct(ItemmasterInterface $itemmaster) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->middleware('auth');
		$this->itemmaster = $itemmaster;
	}
	
	
	public function index() 
	{
		$data = array();
		$category = DB::table('category')->where('parent_id',0)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		$subcategory = DB::table('category')->where('parent_id',1)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		$group = DB::table('groupcat')->where('parent_id',0)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		$subgroup = DB::table('groupcat')->where('parent_id',1)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		
		return view('body.stockmovement.index')
					->withType(Input::get('search_type'))
					->withFromdate(Input::get('date_from'))
					->withTodate(Input::get('date_to'))
					->withCategory($category)
					->withSubcategory($subcategory)
					->withGroup($group)
					->withSubgroup($subgroup)
					->withSettings($this->acsettings)
					->withData($data);
	}
	
	protected function groupLoc($result)
	{
		$childs = array();
		foreach($result as $item)
			$childs[$item->location_id][] = $item;

			return $childs;
	}
	
	public function getSearch()
	{
		$data = array();
		
		if(Input::get('search_type')=='Movement_Summary') {
			$voucher_head = 'Stock Movement Report';
		$res=	$this->itemmaster->getStockMovementSummaryReport(Input::all());
			$results = $this->doSummary( $this->itemmaster->getStockMovementSummaryReport(Input::all()) ); 
			$titles = ['main_head' => 'Stock Movement Report','subhead' => 'Stock Movement Report'];
			
		} else if(Input::get('search_type')=='Movement') {
			$voucher_head = 'Stock Movement Report';
			$results = $this->itemmaster->getStockMovementReport(Input::all()); 
			$titles = ['main_head' => 'Stock Movement Report','subhead' => 'Stock Movement Report'];
			
		} else if(Input::get('search_type')=='nonMovement') {
			$voucher_head = 'Stock Movement Report';
			$results = $this->itemmaster->getStocknonMovementReport(Input::all()); 
			$titles = ['main_head' => 'Stock Non Movement Report','subhead' => 'Stock Non Movement Report'];
		}
		
	//	echo '<pre>';print_r($results);exit;
		
		return view('body.stockmovement.print')
					->withResults($results)
					->withType(Input::get('search_type'))
					->withVoucherhead($voucher_head)
					->withTitles($titles)
					->withUrl('stock_ledger')
					->withFromdate(Input::get('date_from'))
					->withTodate(Input::get('date_to'))
					->withDocid(Input::get('document_id'))
					->withData($data);
	}
	public function dataExport()
	{
		$data = array();
		$datareport[] = [strtoupper(Session::get('company')),'','',''];
		$datareport[] = ['','','','','','',''];
		$voucher_head = 'Stock Movement  Report';
		Input::merge(['type' => 'export']);
	//	$reports = $this->purchase_invoice->getReportExcel(Input::all());
		
	if(Input::get('search_type')=='Movement_Summary') 
		{
			$voucher_head = 'Stock Movement Summary Report';
			$reports = $this->doSummary( $this->itemmaster->getStockMovementSummaryReport(Input::all()) ); 
			//echo '<pre>';print_r($results);exit;
				$datareport[] = ['','','','',strtoupper($voucher_head), '','',''];
		     $datareport[] = ['','','','','','',''];
		
			$datareport[] = [ 'Item Code','Description','Quantity In','Quantity Out','Quantity Balance','Cost Avg.','Net Total'];
			$i=0;
			foreach ($reports as $row) {
					$i++;
					$datareport[] = [ 
									 //'item code' => $row['item_code'],
									 'item code' =>$row->item_code,
									 'descripton' =>$row->description,
									
									
									  'qty in' => $row->qty_in,  

									  'qty out' => $row->qty_out,  
									  'qty bal' => $row->qty_bal,  
									  'cost' =>$row->cost_avg,   
									  'total' =>$row->net_value,  
									];
			}
		}
		elseif(Input::get('search_type')=="detail") {
		
			$reports = $this->purchase_invoice->getReportExcel(Input::all());
				$datareport[] = ['','','','',strtoupper($voucher_head), '','',''];
		     $datareport[] = ['','','','','','',''];
		
			$datareport[] = ['SI.No.','PI#','Vchr.Date','PI.Ref#', 'Supplier','Gross Amt.','VAT Amt.','Net Total'];
			$i=0;
			foreach ($reports as $row) {
					$i++;
					$datareport[] = [ 'si' => $i,
									  'po' => $row['voucher_no'],
									  'vdate' => date('d-m-Y',strtotime($row['voucher_date'])),
									  'ref' => $row['reference_no'],
									  'supplier' => $row['master_name'],
									 
									  'gross' => $row['unit_price'],
									 'vat' => $row['vat_amount'],
									  'total' => $row['total_price']
									];
			}
			//$reports = $this->makeTree($reports);
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
	
	public function dataExportold()
	{
		$data = array();
		$voucher_head = 'Stock Transaction Report';
		$results = $this->itemmaster->getStockMovementSummaryReport(Input::all()); 
		$titles = ['main_head' => 'Stock Transaction Report','subhead' => 'Stock Transaction Report'];
			
		$datareport[] = [strtoupper(Session::get('company')),'','',''];
		$datareport[] = ['','','','','','',''];
		
		$datareport[] = ['','','',strtoupper($voucher_head),'','',''];
		$datareport[] = ['','','','','','',''];
		$datareport[] = ['Ref.No.','Item Code','Description','Tr.Date','Party Name','Qty','Unit Cost','Balance Qty'];
		
		foreach($results as $key => $result) {
			if(count($result) > 0) {
				$ctotal = 0;
				$datareport[] = [strtoupper($key),'','',''];
				foreach($result as $row) {
					$ctotal += $row->sale_reference;
					$datareport[] = [$row->voucher_no, $row->item_code, $row->description, date('d-m-Y',strtotime($row->voucher_date)),
									$row->master_name, $row->quantity, $row->unit_cost, $row->sale_reference
									];
				}
				$datareport[] = ['','','','','Total','','',$ctotal];
			}
		}
		
		// echo '<pre>';print_r($results);exit;
							
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
	
	private function doSummary($result) {
		
		$childs = array();
		foreach($result as $item)
			$childs[$item->item_id][] = $item;
		
		
		foreach($childs as $rows) {
			
			$qtyin = $qtyout = $qtybal =$inqty= 0;
			foreach($rows as $row) {
				$itemcode = $row->item_code;
				$description = $row->description;
				$costavg = $row->cost_avg;
				$opnqty=$row->opn_quantity;
				$opncost=$row->opn_cost;
			
				if($row->trtype==1)
					$qtyin += $row->quantity;
				else
					$qtyout += $row->quantity;
				 
			}
			$inqty=$qtyin-$opnqty;
			$unitcost=$opnqty*$opncost;
			$purcost=$inqty*$costavg;
			$salecost=$qtyout*$costavg;
			$qtybal = $qtyin - $qtyout;
			$netvalue = $costavg * $qtybal;
			$results[] = (object)['item_code' => $itemcode,
							  'description'	=> $description,
							  'opn_qty'=>$opnqty,
							  'opn_cost'=>$opncost,
							  'unit_cost'=>$unitcost,
							  'pur_cost'=>$purcost,
							  'sale_cost'=>$salecost,
							  'qty_in'	=> $inqty,
							  'qty_out'	=> $qtyout,
							  'qty_bal'	=> $qtybal,
							  'cost_avg' => $costavg,
							  'net_value'	=> $netvalue
							 ];
		}
		
		return $results;
	}
}