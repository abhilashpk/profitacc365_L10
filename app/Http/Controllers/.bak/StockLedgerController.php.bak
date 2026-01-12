<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Itemmaster\ItemmasterInterface;
use App\Repositories\Location\LocationInterface;

use App\Http\Requests;
use Notification;
use Input;
use Session;
use Excel;
use App;
use DB;

class StockLedgerController extends Controller
{
   
	protected $itemmaster;
	protected $location;


	public function __construct(ItemmasterInterface $itemmaster,LocationInterface $location) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->itemmaster = $itemmaster;
		$this->location = $location;
		$this->middleware('auth');
	}
	
	public function index2() {
		$data = array(); 
		$reports = null;
		return view('body.stockledger.index')
					->withReports($reports)
					->withType('')
					->withFromdate('')
					->withTodate('')
					->withData($data);
	}
	
		
	public function index() //search
	{
		$data = array();
		
		/* if(Input::get('search_type')=='quantity') {
			$voucher_head = 'Stock Ledger with Quantity';
			$reports = $this->itemmaster->getStockLedger(Input::all()); 
			
		} else if(Input::get('search_type')=='quantity_cost') { */
			$voucher_head = 'Stock Ledger';
			$reports = $this->itemmaster->getStockLedger(Input::all());
			
		//} 
		$location = $this->location->locationListAll();
		$customers = DB::table('account_master')->where('category','CUSTOMER')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')
					->select('id','master_name')->orderBy('master_name','ASC')->get();
		//echo '<pre>';print_r($reports);exit;
		return view('body.stockledger.index')
					->withReports($reports)
					->withVoucherhead($voucher_head)
					->withType(Input::get('search_type'))
					->withFromdate(Input::get('date_from'))
					->withTodate(Input::get('date_to'))
					->withLocation($location)
					->withCustomers($customers)
					->withSettings($this->acsettings)
					->withData($data);
	}
	
	protected function groupLoc($result)
	{
		$childs = array();
		foreach($result as $item) {
			$childs[$item->location_id][] = $item;
		}
			return $childs;
	}
	
	private function locFilter($locs) {
		foreach($locs as $loc) {
			$arrloc[$loc->id] = (object)['code' => $loc->code, 'name' => $loc->name];
		}
		return $arrloc;
	}
	
	public function getPrint()
	{
		$data = array(); // echo '<pre>';print_r(Input::all());exit;
		
		$res = app('App\Http\Controllers\UtilityController')->reEvalItemCostQuantity(Input::get('document_id'));
		$re = app('App\Http\Controllers\UtilityController')->updateItemCurrentQty(Input::get('document_id')); //OCT24
		
		if(Input::get('search_type')=='quantity') {
			$voucher_head = 'Stock Ledger with Quantity';
			$results = $this->itemmaster->getStockLedgerReport(Input::all()); 
			$itemdata = DB::table('itemmaster')->where('id',Input::get('document_id'))->select('item_code','description')->first();
			$titles = ['main_head' => 'Stock Ledger with Quantity','subhead' => 'Stock Ledger with Quantity'];
			$locdata = [];
		} else if(Input::get('search_type')=='quantity_cost') {
			
			$voucher_head = 'Stock Ledger Quantity with Cost & Value';
			$results = $this->itemmaster->getStockLedgerReport(Input::all()); 
			$titles = ['main_head' => 'Stock Ledger Quantity with Cost', 'subhead' => 'Stock Ledger Quantity with Cost'];
			$itemdata = DB::table('itemmaster')->where('id',Input::get('document_id'))->select('item_code','description')->first();
			$locdata = [];
		} else if(Input::get('search_type')=='quantity_loc') {
			
			$voucher_head = 'Stock Ledger with Location';
			$results = $this->itemmaster->getStockLedgerLocReport(Input::all()); 
			$locdata = $this->locFilter(DB::table('location')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','code','name')->get());
			$itemdata = DB::table('itemmaster')->where('id',Input::get('document_id'))->select('item_code','description')->first();
			$results['pursales'] = $this->groupLoc($results['pursales']);
			$titles = ['main_head' => 'Stock Ledger with Location','subhead' => 'Stock Ledger with Location'];
			
		} else if(Input::get('search_type')=='quantity_loc_cost') {
			
			$voucher_head = 'Stock Ledger Quantity with Cost & Value Location';
			$results = $this->itemmaster->getStockLedgerLocReport(Input::all()); 
			$itemdata = DB::table('itemmaster')->where('id',Input::get('document_id'))->select('item_code','description')->first();
			$results['pursales'] = $this->groupLoc($results['pursales']);
			$locdata = $this->locFilter(DB::table('location')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','code','name')->get());
			$titles = ['main_head' => 'Stock Ledger Quantity with Cost & Value Location','subhead' => 'Stock Ledger Quantity with Cost & Value Location'];
			
		} else if(Input::get('search_type')=='quantity_conloc') { 
			
			$voucher_head = 'Stock Ledger with Consignment Location';
			$results = $this->itemmaster->getStockLedgerLocReport(Input::all()); 
			$itemdata = DB::table('itemmaster')->where('id',Input::get('document_id'))->select('item_code','description')->first();
			$results['pursales'] = $this->groupLoc($results['pursales']);
			$locdata = $this->locFilter(DB::table('location')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','code','name')->get());
			$titles = ['main_head' => 'Stock Ledger with Consignment Location','subhead' => 'Stock Ledger with Consignment Location'];	
		
		} else if(Input::get('search_type')=='quantity_conloc_cost') {
			
			$voucher_head = 'Stock Ledger Quantity with Cost & Value Consignment Location';
			$results = $this->itemmaster->getStockLedgerLocReport(Input::all()); 
			$itemdata = DB::table('itemmaster')->where('id',Input::get('document_id'))->select('item_code','description')->first();
			$results['pursales'] = $this->groupLoc($results['pursales']);
			$locdata = $this->locFilter(DB::table('location')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','code','name')->get());
			$titles = ['main_head' => 'Stock Ledger Quantity with Cost & Value Consignment Location','subhead' => 'Stock Ledger Quantity with Cost & Value Consignment Location'];	
			
		}
		
		//echo '<pre>';print_r($results);exit;
		return view('body.stockledger.print')
					->withResults($results)
					->withType(Input::get('search_type'))
					->withVoucherhead($voucher_head)
					->withTitles($titles)
					->withUrl('stock_ledger')
					->withFromdate(Input::get('date_from'))
					->withTodate(Input::get('date_to'))
					//->withLocation($location)
					->withDocid(Input::get('document_id'))
					->withJobno(Input::get('is_jobno'))
					->withSearchval(json_encode(Input::all()))
					->withItem($itemdata)
					->withLocdata($locdata)
					->withData($data);
	}
	
	public function dataExport()
	{
		$data = array();
		$data = json_decode(Input::get('search_val')); //echo '<pre>';print_r($data);exit;
		Input::merge(['date_from' => $data->date_from]);
		Input::merge(['date_to' => $data->date_to]);
		Input::merge(['search_type' => $data->search_type]);
		Input::merge(['location_id' =>(isset($data->location_id))?$data->location_id:'']);
	    Input::merge(['account_id' =>(isset($data->account_id))?$data->account_id:'']);
	    Input::merge(['document_id' =>(isset($data->document_id))?$data->document_id:'']);
	    Input::merge(['is_jobno' =>(isset($data->is_jobno))?$data->is_jobno:'']);
	   
	   $jobno=Input::get('is_jobno');
	   //echo '<pre>';print_r($jobno);exit;
		$results = $this->itemmaster->getStockLedgerReport(Input::all()); 	
		$datareport[] = [strtoupper(Session::get('company')),'','',''];
		$datareport[] = ['','','','','','',''];
		if(Input::get('search_type')=='quantity') {
			$voucher_head = 'Stock Ledger with Quantity';
			$datareport[] = ['','','',strtoupper($voucher_head),'','',''];
			$datareport[] = ['','','','','','',''];
			$datareport[] = ['Item Code:',$results['opn_details'][0]->item_code,'Item Name:',$results['opn_details'][0]->description,'','','','',''];
			$datareport[] = ['','','','','','',''];
		
			$datareport[] = ['Type','Vchr.No.','Tr.Date','Supp/Cust.',($jobno==1)?'Job No':'','Qty.In','Qty.Out','Balance'];
			
			$qtyin = $qtyout = $bal = 0;
			foreach($results['opn_details'] as $opndetails) {
				$datareport[] = ['OQ','','','Opening Quantity','',$opndetails->opn_quantity,'',$opndetails->opn_quantity];
				$qtyin += $opndetails->opn_quantity; $bal = $qtyin;
			}
			
			foreach($results['pursales'] as $result) {
				
				if($result->type=='PI' || $result->type=='SR' || $result->type=='GR' || $result->type=='TI' || $result->type=='SDO') {
					$qtyin += $result->quantity; $bal += $result->quantity;
			   } elseif($result->type=='SI' || $result->type=='PR' || $result->type=='GI' || $result->type=='TO') {
					$qtyout += $result->quantity; $bal -= $result->quantity;
			   }
			   
				$datareport[] = [$result->type, $result->voucher_no, date('d-m-Y',strtotime($result->voucher_date)),
								$result->master_name, ($jobno==1)?$result->jobno:'',($result->type=='PI' || $result->type=='SR' || $result->type=='GR' || $result->type=='TI' || $result->type=='SDO')?$result->quantity:'',
								($result->type=='SI' || $result->type=='PR' || $result->type=='GI' || $result->type=='TO')?$result->quantity:'', $bal
								];
			}
			
			$balance = $qtyin - $qtyout;
			
			$datareport[] = ['','','','','Total:',$qtyin,$qtyout,$balance];
			
		} else if(Input::get('search_type')=='quantity_cost') {
			$voucher_head = 'Stock Ledger Quantity with Cost ';
			$datareport[] = ['','','','','',strtoupper($voucher_head),'','','','','',''];
			$datareport[] = ['','','','','','',''];
			$datareport[] = ['Item Code:',$results['opn_details'][0]->item_code,'Item Name:',$results['opn_details'][0]->description,'','','','',''];
			$datareport[] = ['','','','','','',''];
			$datareport[] = ['Type','Vchr.No.','Tr.Date','Supp/Cust.',($jobno==1)?'Job No':'','Qty.In','Pur.Cost','Cost Avg.','Sl. Cost','Qty.Out','Sl. Price','Balance'];
			$qtyin = $qtyout = $pcost = $scost = $costavg = $costsi = $bal = 0;
			
			foreach($results['opn_details'] as $opndetails) {
				$datareport[] = ['OQ','','','Opening Quantity','',$opndetails->opn_quantity,'',
								 number_format($opndetails->cost_avg,2),'',$opndetails->opn_quantity,'',$bal];
				$qtyin += $opndetails->opn_quantity; $costavg += $opndetails->cost_avg; $bal = $qtyin;
			}
			
			foreach($results['pursales'] as $result) {
				
				if($result->type=='PI' || $result->type=='SR' || $result->type=='TI' || $result->type=='GR' || $result->type=='SDO') {
						$qtyin += $result->quantity;
						$pcost += $result->unit_cost;
						$costavg += $result->cost_avg;
						
				} elseif($result->type=='SI' || $result->type=='PR' || $result->type=='TO' || $result->type=='GI') {
						 $costsi = $result->sale_cost;
						 $qtyout += $result->quantity; 
						 $scost += $result->unit_cost;		
						 $costsi += $costsi;
						 $costavg += $result->cost_avg;
				} $curbalance = $qtyin - $qtyout;
			   
				$datareport[] = [$result->type, $result->voucher_no, date('d-m-Y',strtotime($result->voucher_date)),
								$result->master_name,($jobno==1)?$result->jobno:'', ($result->type=='PI' || $result->type=='SR' || $result->type=='TI' || $result->type=='GR' || $result->type=='SDO')?$result->quantity:'',
								($result->type=='PI' || $result->type=='SR' || $result->type=='TI' || $result->type=='GR' || $result->type=='SDO')?number_format($result->pur_cost,3):'',
								number_format($result->cost_avg,3),
								($result->type=='SI' || $result->type=='PR' || $result->type=='TO' || $result->type=='GI')?number_format($result->sale_cost,3):'',
								($result->type=='SI' || $result->type=='PR' || $result->type=='TO' || $result->type=='GI')?$result->quantity:'',
								($result->type=='SI' || $result->type=='PR' || $result->type=='TO' || $result->type=='GI')?number_format($result->unit_cost,3):'',
								$curbalance
								];
			}
			
			$balance = $qtyin - $qtyout;
			$datareport[] = ['','','','','Total:',$qtyin,number_format($pcost,2),$costavg,number_format($costsi,2),$qtyout,number_format($scost,2),$balance];
			
		} else if(Input::get('search_type')=='quantity_loc'||Input::get('search_type')=='quantity_conloc') {
		    if(Input::get('search_type')=='quantity_loc'){
		    $voucher_head = 'Stock Ledger with Location';
		    }
		    else if(Input::get('search_type')=='quantity_conloc'){
		     $voucher_head = 'Stock Ledger with Consignment Location';   
		    }
			$results = $this->itemmaster->getStockLedgerLocReport(Input::all()); 
			$locdata = $this->locFilter(DB::table('location')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','code','name')->get());
			$itemdata = DB::table('itemmaster')->where('id',Input::get('document_id'))->select('item_code','description')->first();
			$results['pursales'] = $this->groupLoc($results['pursales']);
			
			$datareport[] = ['','','','','',strtoupper($voucher_head),'','','','','',''];
			$datareport[] = ['','','','','','',''];
			$datareport[] = ['Item Code:',$itemdata->item_code,'Item Name:',$itemdata->description,'','','','',''];
			$datareport[] = ['','','','','','',''];
			foreach($results['pursales'] as $ky => $results) {
			    
			$datareport[] = ['Location Code:',$locdata[$ky]->code,'Location Name:',$locdata[$ky]->name,'','','','',''];  
			$datareport[] = ['','','','','','',''];
			
			$datareport[] = ['Type','Vchr.No.','Tr.Date','Supp/Cust.',($jobno==1)?'Job No':'','Qty.In','Qty.Out','Balance'];
			$qtyout = $qtyin =$balance= 0;
			foreach($results as $result){
			    if($result->type=='OQ' || $result->type=='PI' || $result->type=='SR' || $result->type=='DO' || $result->type=='LT IN' || $result->type=='SDO') 
					$qtyin += $result->lqty;
		     else if($result->type=='CDO' || $result->type=='SI' || $result->type=='PR' || $result->type=='LT OUT') 
			$qtyout += $result->lqty;
			$balance = $qtyin - $qtyout;
			    $datareport[] = [$result->type, 
			                     $result->voucher_no, 
			                     date('d-m-Y',strtotime($result->voucher_date)),
							     $result->master_name,
							     ($jobno==1)?$result->jobno:'',
							     ($result->type=='OQ' || $result->type=='PI' || $result->type=='SR' || $result->type=='DO' || $result->type=='LT IN' || $result->type=='SDO')?$result->lqty:'',
								($result->type=='CDO' || $result->type=='SI' || $result->type=='PR' || $result->type=='LT OUT')?$result->lqty:'',
								$qtyin - $qtyout
								];
			    
			}
			$datareport[] = ['','','','','','',''];
			
			$datareport[] = ['','','','','Total',$qtyin,$qtyout,$balance];
			}
		}else if(Input::get('search_type')=='quantity_loc_cost'||Input::get('search_type')=='quantity_conloc_cost') {
		    if(Input::get('search_type')=='quantity_loc_cost'){
		    $voucher_head = 'Stock Ledger with Cost and Value Location';
		    }
		    else if(Input::get('search_type')=='quantity_conloc_cost'){
		     $voucher_head = 'Stock Ledger with Cost and Value Consignment Location';   
		    }
			$results = $this->itemmaster->getStockLedgerLocReport(Input::all()); 
			$locdata = $this->locFilter(DB::table('location')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','code','name')->get());
			$itemdata = DB::table('itemmaster')->where('id',Input::get('document_id'))->select('item_code','description')->first();
			$results['pursales'] = $this->groupLoc($results['pursales']);
			
			$datareport[] = ['','','','','',strtoupper($voucher_head),'','','','','',''];
			$datareport[] = ['','','','','','',''];
			$datareport[] = ['Item Code:',$itemdata->item_code,'Item Name:',$itemdata->description,'','','','',''];
			$datareport[] = ['','','','','','',''];
			$balance=0;
			foreach($results['pursales'] as $ky => $results) {
			    
			$datareport[] = ['Location Code:',$locdata[$ky]->code,'Location Name:',$locdata[$ky]->name,'','','','',''];  
			$datareport[] = ['','','','','','',''];
			
			['Type','Vchr.No.','Tr.Date','Supp/Cust.',($jobno==1)?'Job No':'','Qty.In','Pur.Cost','Cost Avg.','Sl. Cost','Qty.Out','Sl. Price','Balance'];
			$qtyin = $qtyout = $pcost = $scost = $costavg = $costsi =$curbalance= 0;
			foreach($results as $result){
			  if($result->type=='PI' || $result->type=='SR' || $result->type=='TI' || $result->type=='GR' || $result->type=='SDO' || $result->type=='OQ' || $result->type=='DO' || $result->type=='LT IN') {
					$qtyin += $result->lqty;
					$pcost += $result->pur_cost;
					$costavg += $result->cost_avg;
															
			} elseif($result->type=='SI' || $result->type=='PR' || $result->type=='GI' || $result->type=='TO' || $result->type=='LT OUT' || $result->type=='CDO') {
															 
				 $qtyout += $result->lqty; 
			      $scost += $result->unit_cost;		
				  $costsi += $costsi;
				$costavg += $result->cost_avg;
				} 
				$curbalance = $qtyin - $qtyout;  
				$datareport[] = [$result->type, 
				                 $result->voucher_no, 
				                 date('d-m-Y',strtotime($result->voucher_date)),
								$result->master_name,
								($jobno==1)?$result->jobno:'', 
								($result->type=='OQ' || $result->type=='PI' || $result->type=='SR' || $result->type=='TI' || $result->type=='GR' || $result->type=='SDO' || $result->type=='DO' || $result->type=='LT IN')?$result->lqty:'',
								($result->type=='PI' || $result->type=='SR' || $result->type=='TI' || $result->type=='GR' || $result->type=='SDO')?number_format($result->pur_cost,3):'',
								($result->lqty > 0)?number_format($result->cost_avg,3):'',
								($result->type=='SI' || $result->type=='PR' || $result->type=='TO' || $result->type=='GI')?number_format($result->sale_cost,3):'',
								($result->type=='SI' || $result->type=='PR' || $result->type=='TO' || $result->type=='GI'|| $result->type=='LT OUT')?$result->lqty:'',
								($result->type=='SI' || $result->type=='PR' || $result->type=='TO' || $result->type=='GI')?number_format($result->unit_cost,3):'',
								$curbalance
								];
			    
			}
			$balance = $qtyin - $qtyout;
			$datareport[] = ['','','','','','',''];
			$datareport[] = ['','','','','Total:',$qtyin,number_format($pcost,2),$costavg,number_format($costsi,2),$qtyout,number_format($scost,2),$balance];
			}
		}
		
		// echo '<pre>';print_r($results);exit;
							
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

//SELECT currency.decimal_name,purchase_invoice.voucher_no,purchase_invoice.reference_no,purchase_invoice.voucher_date,purchase_invoice.total,purchase_invoice.vat_amount AS total_vatt,purchase_invoice.discount,purchase_invoice.net_amount,purchase_invoice.subtotal,purchase_invoice.total_fc,purchase_invoice.discount_fc,purchase_invoice.vat_amount_fc,purchase_invoice.net_amount_fc,account_master.account_id,account_master.master_name,account_master.address,account_master.phone,account_master.vat_no,currency.code,terms.description AS terms,purchase_invoice_item.item_name,purchase_invoice_item.quantity,purchase_invoice_item.unit_price,purchase_invoice_item.vat,purchase_invoice_item.vat_amount,purchase_invoice_item.total_price,purchase_invoice_item.tax_include,purchase_invoice_item.item_total,purchase_invoice_item.unit_price_fc,purchase_invoice_item.total_price_fc,purchase_invoice_item.item_total_fc,purchase_invoice_item.vat_amount_fc AS line_vat_fc,itemmaster.item_code,units.unit_name,department.name AS department FROM purchase_invoice JOIN account_master ON(account_master.id=purchase_invoice.supplier_id) LEFT JOIN terms ON(terms.id=purchase_invoice.terms_id) JOIN purchase_invoice_item ON(purchase_invoice_item.purchase_invoice_id=purchase_invoice.id) JOIN itemmaster ON(itemmaster.id=purchase_invoice_item.item_id) JOIN units ON(units.id=purchase_invoice_item.unit_id) LEFT JOIN department ON(department.id=purchase_invoice.department_id) JOIN currency ON(currency.id=purchase_invoice.currency_id)  WHERE purchase_invoice_item.status=1 AND purchase_invoice_item.deleted_at='0000-00-00 00:00:00' AND purchase_invoice.id={id}