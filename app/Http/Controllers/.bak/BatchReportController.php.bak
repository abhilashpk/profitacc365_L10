<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Itemmaster\ItemmasterInterface;

use App\Http\Requests;
use Notification;
use Input;
use Session;
use App;
use DB;
use Excel;

class BatchReportController extends Controller
{
   
	protected $itemmaster;
	protected $location;

	public function __construct(ItemmasterInterface $itemmaster) {

		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->itemmaster = $itemmaster;
		$this->middleware('auth');
	}
	
	public function index() {

		$category = DB::table('category')->where('parent_id',0)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		$subcategory = DB::table('category')->where('parent_id',1)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();

		$reports = null; //$this->itemmaster->getStockLedger(Input::all());
		//	echo '<pre>';print_r($customers);exit;		
		return view('body.batchreport.index')
					->withReports($reports)
					->withCategory($category)
					->withSubcategory($subcategory)
					->withSettings($this->acsettings);
	}
	
	
	public function getSearch() //getPrint
	{ 
	//echo '<pre>';print_r(Input::all());exit;
		$data = array();
		if(Input::get('search_type')=='batch_expiry') {
			$voucher_head = 'Item Batch Expiry';
			$results = $this->itemmaster->getBatchReport(Input::all()); 
			//echo '<pre>';print_r($result);exit;
			$titles = ['main_head' => 'Item Batch Expiry Report','subhead' => 'Item Batch Expiry Report'];
			
		} 
		
	//	echo '<pre>';print_r($results);exit;
		return view('body.batchreport.print')
					->withResults($results)
					->withVoucherhead($voucher_head)
					->withTitles($titles)
					->withType(Input::get('search_type'))
					->withFromdate(Input::get('date_from'))
					->withTodate(Input::get('date_to'))
					->withCatid(Input::get('category_id'))
					->withSubcatid(Input::get('subcategory_id'))
					->withSettings($this->acsettings)
					->withSearchval(json_encode(Input::all()))
					->withData($data);
	}		
	
	
	public function dataExport()
	{
		$data = json_decode(Input::get('search_val')); //echo '<pre>';print_r($data);exit;
		Input::merge(['date_from' => $data->date_from]);
		Input::merge(['date_to' => $data->date_to]);
		Input::merge(['search_type' => $data->search_type]);
		Input::merge(['document_id' =>(isset($data->document_id))?$data->document_id:'']);
		Input::merge(['category_id' =>(isset($data->category_id))?$data->category_id:'']);
		Input::merge(['subcategory_id' =>(isset($data->subcategory_id))?$data->subcategory_id:'']);
		
		//echo $data->search_type;exit;
		//echo '<pre>';print_r(Input::all());exit;
		if($data->search_type=='batch_expiry') {
			$voucher_head = 'Item Batch Expiry';
			
            $results = $this->itemmaster->getBatchReport(Input::all()); 
            
		//echo '<pre>';print_r($results);exit;
			
			$datareport[] = [strtoupper(Session::get('company')),'','',''];
			$datareport[] = ['','','','','','',''];
			
			$datareport[] = ['','','',strtoupper($voucher_head),'','',''];
			$datareport[] = ['','','','','','',''];
			$datareport[] = ['SI.No.','Item Code','Description','Category','Batch No.','Mfg. Date','Exp. Date','Exp.Days','Quantity','Cost','Total'];
				$i = $qtytotal = $totalcost = 0;
				foreach($results as $item) {
					$i++;
					$subtotal = $item['cost_avg'] * $item['quantity'];
											
					$now = time(); 
                    $your_date = strtotime($item['exp_date']);
                    $datediff = $your_date - $now;
                    $days = round($datediff / (60 * 60 * 24));
                    
                    $qtytotal += $item['quantity'];
                    $totalcost += $subtotal;
                                            
					$datareport[] = [$i,$item['item_code'],$item['description'],$item['category_name'],$item['batch_no'],date('d-m-Y', strtotime($item['mfg_date'])),
					        date('d-m-Y', strtotime($item['exp_date'])),$days,$item['quantity'],number_format($item['cost_avg'],2),number_format($subtotal,2)];
				}
				
			$datareport[] = ['','','','','','','Grand Total:','',$qtytotal,'',number_format($totalcost,2),''];
			
		} 
		
	//	echo $voucher_head.'<pre>';print_r($datareport);exit;

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
	
	protected function makeTree($result)
	{
		$childs = array();
		foreach($result as $item)
			$childs[$item['itemmaster_id']][] = $item;

			return $childs;
	}
	

	
}