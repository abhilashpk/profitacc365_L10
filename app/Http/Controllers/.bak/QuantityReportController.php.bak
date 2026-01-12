<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Itemmaster\ItemmasterInterface;
use App\Repositories\Location\LocationInterface;

use App\Http\Requests;
use Notification;
use Input;
use Session;
use App;
use DB;
use Excel;

class QuantityReportController extends Controller
{
   
	protected $itemmaster;
	protected $location;

	public function __construct(ItemmasterInterface $itemmaster,LocationInterface $location) {

		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->itemmaster = $itemmaster;
		$this->location = $location;
		$this->middleware('auth');
	}
	
	public function index() {
		$data = array(); 
		
		$location = $this->location->locationListAll();
		$category = DB::table('category')->where('parent_id',0)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		$subcategory = DB::table('category')->where('parent_id',1)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		$group = DB::table('groupcat')->where('parent_id',0)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		$subgroup = DB::table('groupcat')->where('parent_id',1)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		$customers = DB::table('account_master')->where('category','CUSTOMER')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')
					->select('id','master_name')->orderBy('master_name','ASC')->get();
					
		$reports = $this->itemmaster->getStockLedger(Input::all());
		//	echo '<pre>';print_r($customers);exit;		
		return view('body.quantityreport.index')
					->withReports($reports)
					->withType('')
					->withFromdate($this->acsettings->from_date)
					->withTodate('')
					->withLocid('all')
					->withLocation($location)
					->withCategory($category)
					->withSubcategory($subcategory)
					->withGroup($group)
					->withSubgroup($subgroup)
					->withCustomers($customers)
					->withSettings($this->acsettings)
					->withData($data);
	}
	
		
	public function getSearch2()
	{
		$data = array();
		
		if(Input::get('search_type')=='opening_quantity') {
			$voucher_head = 'Opening Quantity Report';
			$reports = $this->itemmaster->getQuantityReport(Input::all()); 
			
		} else if(Input::get('search_type')=='qtyhand_ason_date') {
			$voucher_head = 'Quantity in Hand Report';
			$reports = $this->itemmaster->getQuantityReport(Input::all()); 
			
		} else if(Input::get('search_type')=='qtyhand_ason_priordate') {
			$voucher_head = 'Quantity Report';
			$reports = $this->itemmaster->getQuantityReport(Input::all());
		} 
		
		//echo '<pre>';print_r($reports);exit;
		return view('body.quantityreport.index')
					->withReports($reports)
					->withVoucherhead($voucher_head)
					->withType(Input::get('search_type'))
					->withFromdate(Input::get('date_from'))
					->withTodate(Input::get('date_to'))
					->withData($data);
	}
	
	protected function makeTree($result)
	{
		$childs = array();
		foreach($result as $item)
			$childs[$item['itemmaster_id']][] = $item;

			return $childs;
	}
	
	protected function groupLoc($result)
	{
		$childs = array();
		foreach($result as $item)
			$childs[$item['location_id']][] = $item;

			return $childs;
	}
	
	protected function groupItem($result)
	{
		$childs = array();
		foreach($result as $item)
			$childs[$item['item_id']][] = $item;

			return $childs;
	}


	protected function groupItemLoc($results)
	{
		$childs = array();
		foreach($results as $k => $result)
			foreach($result as $item)
			$childs[$k][$item['item_id']][] = $item;

		return $childs;
	}
	
	protected function makeSummary($results)
	{
		$arrSummarry = array();
		foreach($results as $rows)
		{
			$in = $out = $quantity = 0;
			foreach($rows as $row) {
				$itemcode = $row['item_code'];
				$description = $row['description'];
				$unit = $row['packing']; 
				$cost_avg = $row['cost_avg'];
				$opn_cost = $row['opn_cost'];
				$opn_quantity = $row['opn_quantity'];
				$binloc = $row['bin_location'];
				$mpqty=$row['mpqty'];
				$p1qty=$row['p1_qty'];
				$p2qty=$row['p2_qty'];
				$sell_price = isset($row['sell_price'])?$row['sell_price']:'';
				if($row['trtype']=='0')
					$out += $row['quantity'];
				else
					$in += $row['quantity'];
				
			}
			$quantity = $in - $out;
			$p1 = explode(',', $row['p1_formula']);
			$p2 = explode(',', $row['p2_formula']);
			
			$p1qty = $p2qty = '';
			if(isset($p1[1]) && $p1[1]!='')
			    $p1qty = ($p1[1]=="/")?number_format(($quantity/$p1[0]),2):($quantity*$p1[0]);
			    
			if(isset($p2[1]) && $p2[1]!='')
			    $p2qty = ($p2[1]=="/")?number_format(($quantity/$p2[0]),2):($quantity*$p2[0]);
			    
		/*	$p1qty = ($row['p1_formula'] > 0)?($quantity/$row['p1_formula']):$p1qty;
			$p2qty = ($row['p2_formula'] > 0)?($quantity*$row['p2_formula']):$p2qty; */
			/* if($out < $in)
				$quantity = $in - $out;
			elseif($out > $in)
				$quantity = $out - $in; */
				
			$total = $quantity * $cost_avg;
			
			if(Input::get('quantity_type')=='positive' && $quantity > 0 ) {
			    
    			$arrSummarry[] = ['itemcode' => $itemcode, 
    							  'unit' => $unit,
    							  'quantity' => $quantity, 
    							  'cost_avg' => $cost_avg,
    							  'description' => $description,
    							  'opn_cost' => $opn_cost,
    							  'total' => $total,
    							  'opn_quantity' => $opn_quantity,
    							  'bin_loc' => $binloc,
    							  'sell_price' => $sell_price,
    							  'mpqty' => $mpqty,
    							  'p1qty' => $p1qty,
    							  'p2qty' => $p2qty
    							  ];
			}
			
			if(Input::get('quantity_type')=='minus' && $quantity > 0 ) {
			    
			    $arrSummarry[] = ['itemcode' => $itemcode, 
    							  'unit' => $unit,
    							  'quantity' => $quantity, 
    							  'cost_avg' => $cost_avg,
    							  'description' => $description,
    							  'opn_cost' => $opn_cost,
    							  'total' => $total,
    							  'opn_quantity' => $opn_quantity,
    							  'bin_loc' => $binloc,
    							  'sell_price' => $sell_price,
    							  'mpqty' => $mpqty,
    							  'p1qty' => $p1qty,
    							  'p2qty' => $p2qty
    							  ];
			}
			
			if(Input::get('quantity_type')=='zero' && $quantity > 0 ) {
			    
			    $arrSummarry[] = ['itemcode' => $itemcode, 
    							  'unit' => $unit,
    							  'quantity' => $quantity, 
    							  'cost_avg' => $cost_avg,
    							  'description' => $description,
    							  'opn_cost' => $opn_cost,
    							  'total' => $total,
    							  'opn_quantity' => $opn_quantity,
    							  'bin_loc' => $binloc,
    							  'sell_price' => $sell_price,
    							  'mpqty' => $mpqty,
    							  'p1qty' => $p1qty,
    							  'p2qty' => $p2qty
    							  ];
			}
			
			if(Input::get('quantity_type')=='nonzero' && $quantity > 0 ) {
			    
			    $arrSummarry[] = ['itemcode' => $itemcode, 
    							  'unit' => $unit,
    							  'quantity' => $quantity, 
    							  'cost_avg' => $cost_avg,
    							  'description' => $description,
    							  'opn_cost' => $opn_cost,
    							  'total' => $total,
    							  'opn_quantity' => $opn_quantity,
    							  'bin_loc' => $binloc,
    							  'sell_price' => $sell_price,
    							  'mpqty' => $mpqty,
    							  'p1qty' => $p1qty,
    							  'p2qty' => $p2qty
    							  ];
			}
			
			if(Input::get('quantity_type')=='all' && $quantity > 0 ) {
			    
			    $arrSummarry[] = ['itemcode' => $itemcode, 
    							  'unit' => $unit,
    							  'quantity' => $quantity, 
    							  'cost_avg' => $cost_avg,
    							  'description' => $description,
    							  'opn_cost' => $opn_cost,
    							  'total' => $total,
    							  'opn_quantity' => $opn_quantity,
    							  'bin_loc' => $binloc,
    							  'sell_price' => $sell_price,
    							  'mpqty' => $mpqty,
    							  'p1qty' => $p1qty,
    							  'p2qty' => $p2qty
    							  ];
			}

		}
		return $arrSummarry;
	}
	

	protected function sumLoc($results)
	{
		$arrSummarry = array();
		foreach($results as $result)
		{
			foreach($result as $rows) {
				$in = $out = $quantity = 0;
				foreach($rows as $row) {
					$itemcode = $row['item_code'];
					$description = $row['description'];
					$unit = $row['packing']; 
					$cost_avg = $row['cost_avg'];
					$opn_cost = $row['pur_cost']; //todo
					$opn_quantity = 0;//$row['opn_quantity']; todo
					$location_id = $row['location_id'];
					$lcode = $row['code'];
					$lname = $row['name'];
					if($row['trtype']=='0')
						$out += $row['quantity'];
					else
						$in += $row['quantity'];
					
				}
				$quantity = $in - $out;
				
				$total = $quantity * $cost_avg;
			
			    if(Input::get('quantity_type')=='minus' && $quantity < 0) {
			    
        			$arrSummarry[$location_id][] = ['itemcode' => $itemcode, 
        							  'unit' => $unit,
        							  'quantity' => $quantity, 
        							  'cost_avg' => $cost_avg,
        							  'description' => $description,
        							  'opn_cost' => $opn_cost,
        							  'total' => $total,
        							  'opn_quantity' => $opn_quantity,
        							  'code' => $lcode,
        							  'name' => $lname
        							  ];
			    }
			    
			    if(Input::get('quantity_type')=='positive' && $quantity > 0 ) { 
			        
			        $arrSummarry[$location_id][] = ['itemcode' => $itemcode, 
        							  'unit' => $unit,
        							  'quantity' => $quantity, 
        							  'cost_avg' => $cost_avg,
        							  'description' => $description,
        							  'opn_cost' => $opn_cost,
        							  'total' => $total,
        							  'opn_quantity' => $opn_quantity,
        							  'code' => $lcode,
        							  'name' => $lname
        							  ];
			    }
			    
			    if(Input::get('quantity_type')=='zero' && $quantity == 0 ) {
			        
			        $arrSummarry[$location_id][] = ['itemcode' => $itemcode, 
        							  'unit' => $unit,
        							  'quantity' => $quantity, 
        							  'cost_avg' => $cost_avg,
        							  'description' => $description,
        							  'opn_cost' => $opn_cost,
        							  'total' => $total,
        							  'opn_quantity' => $opn_quantity,
        							  'code' => $lcode,
        							  'name' => $lname
        							  ];
			    }
			    
			    if(Input::get('quantity_type')=='nonzero' && $quantity != 0 ) {
			        
			        $arrSummarry[$location_id][] = ['itemcode' => $itemcode, 
        							  'unit' => $unit,
        							  'quantity' => $quantity, 
        							  'cost_avg' => $cost_avg,
        							  'description' => $description,
        							  'opn_cost' => $opn_cost,
        							  'total' => $total,
        							  'opn_quantity' => $opn_quantity,
        							  'code' => $lcode,
        							  'name' => $lname
        							  ];
			    }
			    
			    if(Input::get('quantity_type')=='all' && $quantity != 0 ) {
			        
			        $arrSummarry[$location_id][] = ['itemcode' => $itemcode, 
        							  'unit' => $unit,
        							  'quantity' => $quantity, 
        							  'cost_avg' => $cost_avg,
        							  'description' => $description,
        							  'opn_cost' => $opn_cost,
        							  'total' => $total,
        							  'opn_quantity' => $opn_quantity,
        							  'code' => $lcode,
        							  'name' => $lname
        							  ];
			    }
			}
				
			

		}
		return $arrSummarry;
	}
	
	/* ->leftJoin('groupcat AS GP', function($join) {
		$join->on('GP.id','=','IM.group_id');
	})
	->leftJoin('groupcat AS SGP', function($join) {
		$join->on('GP.id','=','IM.subgroup_id');
	})
	->leftJoin('category AS CAT', function($join) {
		$join->on('CAT.id','=','IM.category_id');
	})
	->leftJoin('category AS SCAT', function($join) {
		$join->on('CAT.id','=','IM.subcategory_id');
	}) */
	
	public function getSearch() //getPrint
	{ //echo '<pre>';print_r(json_encode(Input::all()));exit;
	//echo '<pre>';print_r(Input::all());exit;
		$data = array();
		$mpqty=(Input::get('mpqty'))?Input::get('mpqty'):'';
		$pqty=(Input::get('pqty'))?Input::get('pqty'):'';
		if(Input::get('search_type')=='opening_quantity') {
			$voucher_head = 'Opening Quantity';
			$result = $this->itemmaster->getQuantityReport(Input::all()); 
		//	echo '<pre>';print_r($result);exit;
			$results = $this->makeSummary( $this->groupItem($result) );
			$titles = ['main_head' => 'Quantity Report','subhead' => 'Opening Quantity'];
			
		} else if(Input::get('search_type')=='qtyhand_ason_date'|| Input::get('search_type')=='price_list_qty') {
			$voucher_head = 'Quantity in Hand';
			$result = $this->itemmaster->getQuantityReport(Input::all()); 
			
			$results = $this->makeSummary( $this->groupItem($result) ); //echo '<pre>';print_r($result);exit;
			$titles = ['main_head' => 'Quantity Report','subhead' => 'Quantity in Hand'];
			
		} else if(Input::get('search_type')=='qtyhand_ason_priordate') {
			$dt = (Input::get('date_to')=='')?date('d-m-Y'):date('d-m-Y', strtotime(Input::get('date_to')));
			$voucher_head = 'Quantity in Hand as on '.$dt;
			$result = $this->itemmaster->getQuantityReport(Input::all());
			$results = $this->makeSummary( $this->groupItem($result) );
			$titles = ['main_head' => 'Quantity Report','subhead' => $voucher_head];
			
		} else if(Input::get('search_type')=='opening_quantity_loc') {
			$voucher_head = 'Opening Quantity in Location';
			$results = $this->groupLoc($this->itemmaster->getOpeningQuantityLocReport(Input::all())); 
			$titles = ['main_head' => 'Quantity Report','subhead' => 'Opening Quantity in Location'];

		} else if(Input::get('search_type')=='qtyhand_ason_date_loc') {
			$voucher_head = 'Quantity in Hand';
			//$results = $this->itemmaster->getQuantityReport(Input::all()); 
			$results = $this->sumLoc($this->groupItemLoc($this->groupLoc($this->itemmaster->getQuantityReport(Input::all())))); 
			$titles = ['main_head' => 'Quantity Report','subhead' => 'Quantity in Hand as on Date Location'];
		
		} else if(Input::get('search_type')=='qtyhand_ason_priordate_loc') {
			$dt = (Input::get('date_to')=='')?date('d-m-Y'):date('d-m-Y', strtotime(Input::get('date_to')));
			$voucher_head = 'Quantity in Hand as on '.$dt;
			$results = $this->sumLoc($this->groupItemLoc($this->groupLoc( $this->itemmaster->getQuantityReport(Input::all())))); 
			$titles = ['main_head' => 'Quantity Report','subhead' => 'Quantity in Hand as on Date Location'];	
		}
		
	//	echo '<pre>';print_r($results);exit;
		return view('body.quantityreport.print')
					->withResults($results)
					->withVoucherhead($voucher_head)
					->withTitles($titles)
					->withUrl('quantity_report')
					->withType(Input::get('search_type'))
					->withMpqty($mpqty)
					->withPqty($pqty)
					->withFromdate(Input::get('date_from'))
					->withTodate(Input::get('date_to'))
					->withItemtype(Input::get('itemtype'))
					->withLocid(Input::get('location_id'))
					->withGrpid(Input::get('group_id'))
					->withSubgrpid(Input::get('subgroup_id'))
					->withCatid(Input::get('category_id'))
					->withSubcatid(Input::get('subcategory_id'))
					->withQtytype(Input::get('quantity_type'))
					->withSettings($this->acsettings)
					->withBinloc((Input::get('is_binloc')==1)?true:false)
					->withSearchval(json_encode(Input::all()))
					->withData($data);
	}	
	
	public function dataExport()
	{
	    $mp=Input::get('mpqty');
	    $pqty=Input::get('pqty');
		$data = json_decode(Input::get('search_val')); //echo '<pre>';print_r($data);exit;
		Input::merge(['date_from' => $data->date_from]);
		Input::merge(['date_to' => $data->date_to]);
		Input::merge(['quantity_type' => $data->quantity_type]);
		Input::merge(['itemtype' => $data->itemtype]);
		Input::merge(['account_id' => (isset($data->account_id))?$data->account_id:'']);
		Input::merge(['search_type' => $data->search_type]);
		Input::merge(['document_id' =>(isset($data->document_id))?$data->document_id:'']);
		Input::merge(['location_id' =>(isset($data->location_id))?$data->location_id:'']);
		Input::merge(['group_id' =>(isset($data->group_id))?$data->group_id:'']);
		Input::merge(['subgroup_id' =>(isset($data->subgroup_id))?$data->subgroup_id:'']);
		Input::merge(['category_id' =>(isset($data->category_id))?$data->category_id:'']);
		Input::merge(['subcategory_id' =>(isset($data->subcategory_id))?$data->subcategory_id:'']);
		//echo $data->search_type;exit;
		//echo '<pre>';print_r(Input::all());exit;
		if($data->search_type=='qtyhand_ason_date_loc' || $data->search_type=='qtyhand_ason_priordate_loc') {
			$voucher_head = 'Quantity in Hand';
			//$results = $this->groupItem( $this->itemmaster->getQuantityReport(Input::all()) ); 
			
			$results = $this->sumLoc($this->groupItemLoc($this->groupLoc($this->itemmaster->getQuantityReport(Input::all())))); 
			//echo '<pre>';print_r($results);exit;
			
			$datareport[] = [strtoupper(Session::get('company')),'','',''];
			$datareport[] = ['','','','','','',''];
			
			$datareport[] = ['','','',strtoupper($voucher_head),'','',''];
			$datareport[] = ['','','','','','',''];
			$datareport[] = ['SI.No.','Item Code','Description','Unit','Quantity','Cost Avg.','Total'];
			$gtotal = $gqtytotal = 0;
			foreach($results as $items) {
				$datareport[] = ['Location Code:',$items[0]['code'],'','Location Name:',$items[0]['name']];
				$i = $total = $qtytotal = 0;
				foreach($items as $item) {
					$i++;
					if($data->search_type=='opening_quantity') {
						$qty = $item['opn_quantity'];
						$cost = $item['opn_cost'];
						$subtotal = $cost * $qty;
						$total += $subtotal;
						$qtytotal += $qty;
					} else {
						$qty = $item['quantity'];
						$cost = $item['cost_avg'];
						$subtotal = $cost * $qty;
						$total += $subtotal;
						$qtytotal += $qty;
					}
					$datareport[] = [$i,$item['itemcode'],$item['description'],$item['unit'],$qty,number_format($cost,2),number_format($subtotal,2)];
				}
				$datareport[] = ['','','','Sub Total:',$qtytotal,'',number_format($total,2),''];
				$gtotal += $total; $gqtytotal += $qtytotal;
			}
			$datareport[] = ['','','','Grand Total:',$gqtytotal,'',number_format($gtotal,2),''];
			
		} else if($data->search_type=='opening_quantity_loc') {
			$voucher_head = 'Opening Quantity in Location';
			$results = $this->groupLoc($this->itemmaster->getOpeningQuantityLocReport(Input::all())); 
			//echo '<pre>';print_r($results);exit;
			
			$datareport[] = [strtoupper(Session::get('company')),'','',''];
			$datareport[] = ['','','','','','',''];
			
			$datareport[] = ['','','',strtoupper($voucher_head),'','',''];
			$datareport[] = ['','','','','','',''];
			
			foreach($results as $result) {
				$datareport[] = ['Location Code:',$result[0]['code'],'','Location Name:',$result[0]['name']];
				$datareport[] = ['SI.No.','Item Code','Description','Unit','Quantity','Cost Avg.','Total'];
				$i = $total = $qtytotal = 0;
				foreach($result as $item) {
					$i++;
					$quantity = $item['opn_qty'];
					$cost = $item['opn_cost'];
					$subtotal = $quantity * $item['opn_cost'];
					$total += $subtotal;
					$qtytotal += $item['opn_qty'];
					$datareport[] = [$i,$item['item_code'],$item['description'],$item['unit'],$quantity,($quantity > 0)?number_format($cost,2):0,number_format($subtotal,2)];
				}
				$datareport[] = ['','','','','Grand Total:',$qtytotal,'',number_format($total,2),''];
			}
			
		} 
		else if($data->search_type=='price_list_qty') {
			
				$voucher_head = 'Price List Quantity';
			
			
			$datareport[] = [strtoupper(Session::get('company')),'','',''];
			$datareport[] = ['','','','','','',''];
			
			$datareport[] = ['','','',strtoupper($voucher_head),'','',''];
			$datareport[] = ['','','','','','',''];
			
			$datareport[] = ['SI.No.','Item Code','Description','Unit','Quantity','Cost Avg','Total','Selling Price'];
			
			$result = $this->itemmaster->getQuantityReport(Input::all()); 
			$results = $this->makeSummary( $this->groupItem($result) );
			//echo '<pre>';print_r($result);exit;
			$i = $total = $qtytotal = 0;
			foreach($results as $result) {
				$i++;
			
					$quantity = $result['quantity'];
					$cost = $result['cost_avg'];
					$subtotal = $result['quantity'] * $result['cost_avg'];
					$total += $subtotal;
					$qtytotal += $quantity;
					
			
				
				$datareport[] = [$i, $result['itemcode'], $result['description'],$result['unit'],
								$quantity, number_format($cost,2),number_format($subtotal,2),number_format($result['sell_price'],2)];
								
			}
			$datareport[] = ['','','','Grand Total:',$qtytotal,'',number_format($total,2),''];
		} 
		
		else {
			if($data->search_type=='opening_quantity')
				$voucher_head = 'Opening Quantity';
			else if($data->search_type=='qtyhand_ason_date')
				$voucher_head = 'Quantity in Hand';
			else if($data->search_type=='qtyhand_ason_priordate') {
				$dt = ($data->search_type=='')?date('d-m-Y'):date('d-m-Y', strtotime(Input::get('date_to')));
				$voucher_head = 'Quantity in Hand as on '.$dt;
			} 
			
			$datareport[] = [strtoupper(Session::get('company')),'','',''];
			$datareport[] = ['','','','','','',''];
			
			$datareport[] = ['','','',strtoupper($voucher_head),'','',''];
			$datareport[] = ['','','','','','',''];
			$cst = (Input::get('search_type')=='opening_quantity')?'Cost Open':'Cost Avg.';
			$mqty=(Input::get('mpqty')==1)?'MPQty':'';
			$p1qty=(Input::get('pqty')==1)?'P1Qty':'';
			$p2qty=(Input::get('pqty')==1)?'P2Qty':'';
			$datareport[] = ['SI.No.','Item Code','Description','Unit','Quantity',$cst,'Total',$mqty,$p1qty,$p2qty];
			
			$result = $this->itemmaster->getQuantityReport(Input::all()); 
			$results = $this->makeSummary( $this->groupItem($result) );
			//echo '<pre>';print_r($result);exit;
			$i = $total = $qtytotal =$m=$p1=$p2=$mtotal=$p1total=$p2total= 0;
			foreach($results as $result) {
				$i++;
				$m=(Input::get('mpqty')==1)?$result['mpqty']:'';
				$p1=(Input::get('pqty')==1)?$result['p1qty']:'';
				$p2=(Input::get('pqty')==1)?$result['p2qty']:'';
				if(Input::get('search_type')=='opening_quantity') {
					$quantity = $result['opn_quantity'];
					$cost = $result['opn_cost'];
					$subtotal = $quantity * $result['opn_cost'];
					$total += $subtotal;
					$qtytotal += $result['quantity'];
				} else {
					$quantity = $result['quantity'];
					$cost = $result['cost_avg'];
					$subtotal = $result['quantity'] * $result['cost_avg'];
					$total += $subtotal;
					$qtytotal += $quantity;
					
				}
				$mtotal+=(Input::get('mpqty')==1)?$m:'';
				$p1total+=(Input::get('pqty')==1)?$p1:'';
				$p2total+=(Input::get('pqty')==1)?$p2:'';
				
				$datareport[] = [$i, $result['itemcode'], $result['description'],$result['unit'],
								$quantity, number_format($cost,2),number_format($subtotal,2),$m,$p1,$p2
								];
								
								
			}
			$datareport[] = ['','','','Grand Total:',$qtytotal,'',number_format($total,2)];
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
}