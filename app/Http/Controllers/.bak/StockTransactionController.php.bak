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

class StockTransactionController extends Controller
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
		
		return view('body.stocktransaction.index')
					->withType(Input::get('search_type'))
					->withFromdate(Input::get('date_from'))
					->withTodate(Input::get('date_to'))
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
		
		if(Input::get('search_type')=='detail') {
			$voucher_head = 'Stock Transaction Report';
			$results = $this->itemmaster->getStockTransactionReport(Input::all()); 
			$titles = ['main_head' => 'Stock Transaction Report','subhead' => 'Stock Transaction Report'];
			
		} 
		
		//echo '<pre>';print_r($results);exit;
		return view('body.stocktransaction.print')
					->withResults($results)
					->withType(Input::get('search_type'))
					->withVoucherhead($voucher_head)
					->withTitles($titles)
					->withUrl('stock_ledger')
					->withFromdate(Input::get('date_from'))
					->withTodate(Input::get('date_to'))
					//->withLocation($location)
					->withDocid(Input::get('document_id'))
					->withData($data);
	}
	
	public function dataExport()
	{
		$data = array();
		$voucher_head = 'Stock Transaction Report';
		$results = $this->itemmaster->getStockTransactionReport(Input::all()); 
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
}