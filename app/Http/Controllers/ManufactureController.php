<?php

namespace App\Http\Controllers;
use App\Repositories\AccountSetting\AccountSettingInterface;
use App\Repositories\StockTransferout\StockTransferoutInterface;
use App\Repositories\StockTransferin\StockTransferinInterface;
use App\Repositories\UpdateUtility;
use App\Repositories\Itemmaster\ItemmasterInterface;
use App\Repositories\Production\ProductionInterface;
use App\Repositories\Forms\FormsInterface;

use Illuminate\Http\Request;

use App\Http\Requests;
use Session;
use Response;
use Excel;
use App;
use DB;
use Auth;


class ManufactureController extends Controller
{

	protected $accountsetting;
	protected $stock_transferin;
	protected $stock_transferout;
	protected $mod_autocost;
	protected $itemmaster;
	protected $production;
	protected $forms;
	
	public function __construct(ProductionInterface $production, StockTransferinInterface $stock_transferin, StockTransferoutInterface $stock_transferout, AccountSettingInterface $accountsetting,ItemmasterInterface $itemmaster,FormsInterface $forms) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		
		$this->middleware('auth');
		$this->accountsetting = $accountsetting;
		$this->stock_transferin = $stock_transferin;
		$this->stock_transferout = $stock_transferout;
		$this->itemmaster = $itemmaster;
		$this->production = $production;
		$this->forms = $forms;
		$this->formData = $this->forms->getFormData('MV');
		
		$this->mod_autocost = DB::table('parameter2')->where('keyname', 'mod_autocost_refresh')->where('status',1)->select('is_active')->first();
		$this->modbatch = DB::table('parameter2')->where('keyname','mod_item_batch')->select('is_active')->first();
		$this->objUtility = new UpdateUtility();
	}
	
    public function index() {
		
		$data = array();
		$stocktrans = DB::table('manufacture')
		                ->join('manufacture_item AS MI','MI.manufacture_id','=','manufacture.id')
		                ->join('itemmaster AS IM','IM.id','=','MI.item_id')
		                ->where('MI.deleted_at','0000-00-00 00:00:00')
		                ->where('manufacture.deleted_at','0000-00-00 00:00:00')
		                ->select('manufacture.*','IM.description',DB::raw('SUM(MI.quantity) AS qty'))
		                ->orderBy('manufacture.id','DESC')->groupBy('MI.manufacture_id')
		                ->get();
		 if(Session::get('department')==1) {
			$departments = DB::table('department')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
			$is_dept = true;
		} else {
			$departments = []; $is_dept = false;
		}               
		return view('body.manufacture.index')
					->withStocktrans($stocktrans)
					->withType('')
					->withDepartments($departments)
					->withSettings($this->acsettings)
					->withData($data);
	}
	
	
	
	public function add(Request $request, $id = null, $doctype = null) {

		$data = array();
		$vouchers = $this->accountsetting->getAccountSettingsDefault2($vid=15); //echo '<pre>';print_r($vouchers);exit;
		$lastid = DB::table('manufacture')->where('deleted_at','0000-00-00 00:00:00')->orderBy('id','DESC')->select('id')->first();
		$footertxt = DB::table('header_footer')->where('doc','MV')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->first();
		
		//CHECK DEPARTMENT.......
		if(Session::get('department')==1) { //if active...
			$deptid = Auth::user()->department_id;
			if($deptid!=0)
				$departments = DB::table('department')->where('id',$deptid)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
			else {
				$departments = DB::table('department')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
				$deptid = $departments[0]->id;
			}
			$is_dept = true;
		} else {
			$is_dept = false;
			$departments = [];
			$deptid = '';
		}
		
		$vouchers = $this->accountsetting->getAccountSettingsDefault2($vid=15,$is_dept,$deptid); //echo '<pre>';print_r($vouchers);exit;

		if($id) {
			$ids = explode(',', $id);
			$ocrow = [];
	
			$docRow = $this->production->findPOdata($ids[0]);
			$docItems = $this->production->getItems($ids);
			$itemmaster = $this->itemmaster->activeItemmasterList();
			
		//	echo '<pre>';print_r($docRow);exit;
			$total = 0; $discount = 0; $nettotal = 0; $vat_total = 0;
			foreach($docItems as $item) {
				$total += $item->total_price;
				$discount += $item->discount;
				$vat_total += $item->vat_amount;
			}
			$nettotal = $total - $discount + $vat_total;
			
			return view('body.manufacture.addpo')
						->withItems($itemmaster)
						->withSettings($this->acsettings)
						->withVatdata($this->vatdata)
						->withOrderrow($docRow)
						->withOrditems ($docItems)
						->withPordid($id)
						->withTotal($total)
						->withDiscount($discount)
						->withOcrow($ocrow)
						->withVattotal($vat_total)
						->withPurchaseOrderno(Session::get('voucher_no'))
						->withVoucherdt(Session::get('voucher_date'))
						->withReferenceno(Session::get('reference_no'))
						->withSettings($this->acsettings)
						->withPrintid('')
						->withVouchers($vouchers)
						->withFormdata($this->formData)
						->withIsdept($is_dept)
						->withDocid($id)
						->withData($data);
		}
		
		return view('body.manufacture.add')
					->withVouchers($vouchers)
					->withSettings($this->acsettings)
					->withPrintid($lastid)
					->withIsdept($is_dept)
					->withDepartments($departments)
					->withFormdata($this->formData)
					->withDeptid($deptid)
					->withFooter(isset($footertxt)?$footertxt->description:'')
					->withData($data);
	}
	
	protected function makeTree($result)
	{
		$childs = array();
		foreach($result as $item)
		//echo '<pre>';print_r($item); exit();
		$childs[$item->voucher_no][] = $item;
		
			
		return $childs;
	}
	
    public function getReport($attributes)
	{
		$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
		$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';
		
		$res = DB::table('manufacture')->where('deleted_at','0000-00-00 00:00:00')->orderBy('id','DESC')->get();
		
			
			$query = DB::table('manufacture')
						
						
							->join('account_master AS CR', function($join) {
								$join->on('CR.id','=','manufacture.account_cr');
							})
							->join('stock_transferin AS STI', function($join) {
								$join->on('STI.id','=','manufacture.stock_transferin_id');
							})
							->join('stock_transferin_item AS STIT', function($join) {
								$join->on('STIT.stock_transferin_id','=','STI.id');
							})
							->join('itemmaster AS IM', function($join) {
								$join->on('IM.id','=','STIT.item_id');
							})
							->join('units AS U', function($join) {
								$join->on('U.id','=','STIT.unit_id');
							})
							->join('stock_transferout AS STO', function($join) {
								$join->on('STO.id','=','manufacture.stock_transferout_id');
							}) 
							
							->join('account_master AS DR', function($join) {
								$join->on('DR.id','=','manufacture.account_dr');
							})
						->where('STI.is_mfg', 1)
							->where('STIT.status', 1)
							->where('STIT.deleted_at', '0000-00-00 00:00:00');
					if( $date_from!='' && $date_to!='' ) { 
						$query->whereBetween('manufacture.voucher_date', array($date_from, $date_to));
					}
					if(isset($attributes['dept_id']) && $attributes['dept_id'] !='') { 
					$query->whereIn('manufacture.department_id', $attributes['dept_id']);
				}
						
					
			return $query->select('CR.master_name AS cr_account','STIT.*','IM.item_code','U.unit_name','DR.master_name AS dr_account','manufacture.*','manufacture.voucher_no AS mgno','manufacture.id AS id')
			->groupBy('manufacture.id')->get();
		
	}


	public function getSearch(Request $request)
	{
		$data = $report = $reports =$rawitem= array();
		if($request->get('search_type')=="summary") {
			$voucher_head = 'Manufacture summary (STOCK IN ITEM)';
			$report =$this->getReport($request->all());
			//echo '<pre>';print_r($report); exit();
			$reports = $this->makeTree($report);
			$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];
		}else if($request->get('search_type')=="detail") {
			$voucher_head = 'Production Report ';
			$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];
			$report =$this->getReport($request->all());
            $reports = $this->makeTree($report);
		    foreach ($report as $row)
			 	{
					 $rawitem[$row->item_id]= $this->getRawMaterialsReport($row->item_id);
		            
			 }
		}
	    //	echo '<pre>';print_r($reports); exit();
		return view('body.manufacture.preprint')
					->withReports($reports)
					->withTitles($titles)
				   
					->withRawitem($rawitem)
					->withVoucherhead($voucher_head)
					->withType($request->get('search_type'))
					->withFromdate($request->get('date_from'))
					->withTodate($request->get('date_to'))
					->withI(0)
					->withSettings($this->acsettings)
					->withData($data);
	}
	
	
	public function dataExport(Request $request)
	{
		$data = array();
		
	
		$datareport[] = ['','','','',strtoupper(Session::get('company')),'','','','',''];
		$datareport[] = ['','','','','','','','','',''];
		
	//echo '<pre>';print_r($reports);exit;
	if($request->get('search_type')=="summary") {
	    $reports = $this->getReport($request->all());
	    
			$voucher_head = 'Manufacturing Summary';
		
		$datareport[] = ['','','','',strtoupper($voucher_head), '','','','',''];
		$datareport[] = ['','','','','','','','','',''];
		$datareport[] = ['','','','','','','','','',''];
	
	        $datareport[] = ['SI.No.','Mfg.No:','Date','Item Code', 'Item Name', 'Unit','Qty','Unit Price','Other Cost','Total Amount'];
			$i = $net_total=$net_unit=$net_cost =$oc=$uprice=$total= $octotal=0;
			
			foreach ($reports as $row) {
					$i++;
						$date=date('d-m-Y',strtotime($row->voucher_date));
					$oc = $row->item_total+$row->other_cost;
					$uprice = number_format($row->other_cost,2);
				    
					$price=number_format($row->price,2);
					$tot=number_format($oc,2);
					$datareport[] = [ 'si' => $i,
					                   'mfg' => $row->mgno,
					                   'date' => $date,
									  'ic' => $row->item_code,
									  'des' => $row->item_name,
									  'unit' => $row->unit_name,
									  'qty' => $row->quantity,
									  'unitprice' => $price,
									  'othercost' => $uprice,
									  'total' => $tot
									];
	
		
				  $net_unit += $row->price;	
				  $net_cost += $row->other_cost;	
				  $net_total += $oc;
		
			
			
		}
		$datareport[] = ['','','','','','','Net Total:',number_format($net_unit,2),number_format($net_cost,2),number_format($net_total,2)];
		//echo '<pre>';print_r($datareport);exit;
	}
	
   else	if($request->get('search_type')=="detail") {
       $voucher_head = 'Production Report ';
			$report =$this->getReport($request->all());
            $reports = $this->makeTree($report);
            foreach ($report as $row)
			 	{
					 $rawitem[$row->item_id]= $this->getRawMaterialsReport($row->item_id);
		            
			 }
			 $rawitem=$rawitem;
            $datareport[] = ['','','','',strtoupper($voucher_head), '','','','',''];
	     	$datareport[] = ['','','','','','','','','',''];
		    $datareport[] = ['','','','','','','','','',''];
	        $i = $grand_total=$grand_unit=$grand_cost =0;
	        foreach ($reports as $report) {
			    $mfno=$report[0]->voucher_no;
			    $date=$date=date('d-m-Y',strtotime($report[0]->voucher_date));
			    $datareport[] = ['Mf.No:',$mfno,'','','','','','','',''];
		           $datareport[] = ['Date',$date,'','','','','','','',''];
			    $datareport[] = ['SI.No.','Finishing Product Code', 'Finishing Product Name', 'Unit','Qty','Unit Price','Other Cost','Total Amount'];
			 $net_total=$net_unit=$net_cost =$oc=$uprice=$total= $octotal=0;
			
			
			   foreach ($report as $row) { 
			       $i++;
					$oc = $row->item_total+$row->other_cost;
					$uprice = number_format($row->other_cost,2);
				    
					$price=number_format($row->price,2);
					$tot=number_format($oc,2);
					$datareport[] = [ 'si' => $i,
					                   'ic' => $row->item_code,
									  'des' => $row->item_name,
									  'unit' => $row->unit_name,
									  'qty' => $row->quantity,
									  'unitprice' => $price,
									  'othercost' => $uprice,
									  'total' => $tot
									];
	             $datareport[] = ['','Raw Material','','','','','',''];
			    $datareport[] = ['','Item Code', 'Description', 'Qty','Cost/Unit','Total Amount','',''];
		          $rmtotal=0;
				  foreach ($rawitem[$row->item_id] as  $raw) { 
				      
				      $unit=number_format($raw->unit_price,2);
					$tot=number_format($raw->total,2);
					$datareport[] = [ 'si' =>'' ,
									  'ic' => $raw->item_code,
									  'des' => $raw->description,
									  'qty' => $raw->quantity,
									  'unitprice' => $unit,
									  'total' => $tot,
									  'ex'=>'',
									  'exx'=>''
									];
				      $rmtotal += $raw->total;
				      
				  }
		         $datareport[] = ['','','','','Total',number_format($rmtotal,2),'',''];
			       $net_unit += $row->price;	
				  $net_cost += $row->other_cost;	
				  $net_total += $oc;
			       
			   }
			   $grand_unit +=$net_unit;
			   $grand_cost +=$net_cost;
			   $grand_total +=$net_total;
			   	$datareport[] = ['','','','','','','','','',''];
		        $datareport[] = ['','','','','','','','','',''];
			   $datareport[] = ['Sub Total:','','','','',number_format($net_unit,2),number_format($net_cost,2),number_format($net_total,2)];
			    
			}
			
			$datareport[] = ['','','','','','','','','',''];
		    $datareport[] = ['','','','','','','','','',''];
			$datareport[] = ['Net Total:','','','','',number_format($grand_unit,2),number_format($grand_cost,2),number_format($grand_total,2)];
			//echo '<pre>';print_r($datareport);exit;
       
   }
	
		 
		 //echo $voucher_head.'<pre>';print_r($datareport);exit;
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
		
	}

	private function voucherNoGenerate($attributes) {

		$cnt = 0;
		do {
			$jvset = DB::table('account_setting')->where('id', $attributes['vcher_id'])->select('prefix','is_prefix','voucher_no')->first();
			if($jvset) {
				if($jvset->is_prefix==0) {
					$newattributes['voucher_no'] = $jvset->voucher_no + $cnt;
					$newattributes['vno'] = $jvset->voucher_no + $cnt;
				} else {
					$newattributes['voucher_no'] = $jvset->prefix.($jvset->voucher_no + $cnt);
					$newattributes['vno'] = $jvset->voucher_no + $cnt;
				}
				$newattributes['curno'] = $newattributes['voucher_no'];
			}

			if(Session::get('department')==1)
				$inv = DB::table('manufacture')->where('id','!=',$attributes['rowid'])->where('voucher_no',$newattributes['voucher_no'])->where('department_id', $attributes['department_id'])->where('deleted_at','0000-00-00 00:00:00')->count();
			else
				$inv = DB::table('manufacture')->where('id','!=',$attributes['rowid'])->where('voucher_no',$newattributes['voucher_no'])->where('deleted_at','0000-00-00 00:00:00')->count();
			//echo $inv.' - ';
			$cnt++;
		} while ($inv!=0);

		return $newattributes;
	}
	
	public function save(Request $request) {
		//echo '<pre>';print_r($request->all());exit; 

		DB::beginTransaction();
			try {
			//GET STOCK TRANSFER IN VOUCHER..
			if(Session::get('department')==1)
				$sti = DB::table('account_setting')->where('voucher_type_id', 21)->where('department_id', $request->get('department_id'))->select('id','voucher_no')->first();
			else
				$sti = DB::table('account_setting')->where('voucher_type_id', 21)->select('id','voucher_no')->first();
			$voucher_no = $request->get('voucher_no');
			$voucher_date = ($request->get('voucher_date')=='')?date('Y-m-d'):date('Y-m-d',strtotime($request->get('voucher_date')));
			$amount = $request->get('total_price');
			$voucher_id = $request->get('voucher_id');
			$itemsid = $request->get('item_id');
			if($sti) {
				$request->merge(['voucher_id' => $sti->id]);
				$request->merge(['curno' => $sti->voucher_no]);
				$request->merge(['voucher_no' => $sti->voucher_no]);
				$request->merge(['is_mfg' => 1]);
			}
			//DO STOCK TRANSFER IN AS NEW ITEM..
			$trin = $this->stock_transferin->create($request->all());
			if($trin) {
				
				//GET STOCK TRANSFER OUT VOUCHER..
				if(Session::get('department')==1)
					$sto = DB::table('account_setting')->where('voucher_type_id', 22)->where('department_id', $request->get('department_id'))->select('id','voucher_no')->first();
				else
					$sto = DB::table('account_setting')->where('voucher_type_id', 22)->select('id','voucher_no')->first();
				
				if($sto) {
					$request->merge(['voucher_id' => $sto->id]);
					$request->merge(['curno' => $sto->voucher_no]);
					$request->merge(['voucher_no' => $sto->voucher_no]);
					$request->merge(['is_mfg' => 1]);
				}
				
				//DO STOCK TRANSFER OUT USED RAW MATERIALS... manufacture_item manufacture
				$attributes = $request->all();
				$itemsarr = $attributes['item_id'];
				$qtyarr = $attributes['quantity'];
				$namearr = $attributes['item_name'];
				$untarr = $attributes['unit_id'];
				$cstarr = $attributes['cost'];
				$totarr = $attributes['line_total'];
				//echo '<pre>';print_r($itemsarr);exit;
				
				$is_outentry = false;
				$attributes['item_id'] = $attributes['unit_id'] = $attributes['item_name'] = $attributes['quantity'] = $attributes['cost'] = $attributes['actcost'] = [];
				foreach($itemsarr as $key => $item) { 
					
					$rawitems = DB::table('mfg_items')->where('mfg_items.item_id', $item)
									->join('itemmaster AS IM', 'IM.id', '=', 'mfg_items.subitem_id')
									->join('item_unit AS IU', 'IU.itemmaster_id', '=', 'IM.id')
									->where('mfg_items.deleted_at', '0000-00-00 00:00:00')
									->where('IU.is_baseqty',1)//AUG25
									->select('mfg_items.*','IU.unit_id','IU.cost_avg','IM.description')
									->get();
									
					//echo '<pre>';print_r($rawitems);exit;				
					if(count($rawitems) > 0) {
						
						foreach($rawitems as $ritms) {
							$attributes['item_id'][] =  $ritms->subitem_id;
							$attributes['unit_id'][] =  $ritms->unit_id;
							$attributes['item_name'][] =  $ritms->description;
							$attributes['quantity'][] =  $qtyarr[$key] * $ritms->quantity;
							$attributes['cost'][] =  $ritms->cost_avg;
							$attributes['actcost'][] =  $ritms->cost_avg;
							$attributes['mfg_item_id'][] =  $key;
							
						}
						//echo '<pre>';print_r($attributes);exit;
						
						$is_outentry = true;
						
					}
				}

				//echo '<pre>';print_r($is_outentry);exit;
				//$attributes['is_batch'] = false;
					
				if($this->modbatch->is_active==1)
					$attributes['is_batch'] = true;
			
			    //$is_outentry = false;		    
                //$trout = $this->stock_transferout->create($attributes);//exit;
				if($is_outentry) {
					//DO ACCOUNT REVERSE ENTRY POSTING....
					$attributes['account_dr'] = $request->get('account_dr_to');
					$attributes['account_cr'] = $request->get('account_cr_to');
					//$attributes['is_batch'] = false;

					$trout = $this->stock_transferout->create($attributes);
				}

				if($trout) {
						//Insert into Manufacture table....
						$mfgid = DB::table('manufacture')
										->insertGetId(['voucher_no' => $voucher_no, 
												'stock_transferin_id' => $trin,
												'stock_transferout_id' => $trout,
												'voucher_date'  => $voucher_date,
												'amount'	=> $amount,
												'department_id'	=> isset($attributes['department_id'])?$attributes['department_id']:'',
												'account_dr' => $request->get('account_dr'),
												'account_cr' => $request->get('account_cr'),
												'other_cost' => $request->get('other_cost'),
												'account_dr_to' => $request->get('account_dr_to'),
												'account_cr_to' => $request->get('account_cr_to'),
												]);
						
						$ocamount = array_sum($attributes['oc_amount']); $oc_perunit = $item_oc = 0;			
						foreach($itemsarr as $key => $item) { 
							
							//UPDATING ROWMATERIAL COST...
							if($ocamount > 0) {
								$oc_perunit = ($ocamount * $cstarr[$key]) / $attributes['total_hd'];
								$item_oc = $cstarr[$key] + $oc_perunit;
								$total_pr = $item_oc * $qtyarr[$key];
							}
							
							$mfg_item_id = DB::table('manufacture_item')
												->insertGetId([
													'manufacture_id' => $mfgid,
													'item_id'		=> $item,
													'item_name'		=> $namearr[$key],
													'unit_id'		=> $untarr[$key],
													'quantity'		=> $qtyarr[$key],
													'price'			=> $cstarr[$key],
													'item_total'	=> $totarr[$key],
													'status'		=> 1,
													'other_cost'	=> $oc_perunit,
													'netcost_unit'	=> $item_oc
												]);

							DB::table('stock_transferout_item')->where('stock_transferout_id', $trout)->where('mfg_item_id',$key)->update(['mfg_item_id' => $mfg_item_id]);
						}
						
						$attributes['rowid'] = $mfgid;
						$attributes['vcher_id'] = $voucher_id;
						$newattributes = $this->voucherNoGenerate($attributes);
						$voucher_no = $newattributes['voucher_no'];
						DB::table('manufacture')
										->where('id', $mfgid)
										->update(['voucher_no' => $voucher_no]); 
						
						//UPDATE MFG ACCOUNT TRANSACTION ENTRY.....
						$tridsin = DB::table('account_transaction')
										->where('voucher_type','STI')
										->where('voucher_type_id',$trin)->select('id')->get(); 

						foreach($tridsin as $rw) {
								DB::table('account_transaction')->where('id',$rw->id)
										->update(['voucher_type' => 'MV','voucher_type_id' => $mfgid]);
						}


						$tridsout = DB::table('account_transaction')
										->where('voucher_type','STO')
										->where('voucher_type_id',$trout)->select('id')->get(); 
										
						foreach($tridsout as $rl) {
								DB::table('account_transaction')->where('id',$rl->id)
										->update(['voucher_type' => 'MV','voucher_type_id' => $mfgid]);
						}
                        
                        
                        ### MANUFACTURE WASTAGE ENTRY ###
						if(isset($attributes['wqty'])) { 
							$is_entry = false; $wetotal = 0;
							foreach($attributes['wqty'] as $wk => $wval) {
								if($wval !=''){
									$is_entry = true;
									$wetotal += $attributes['weqtytot'][$wk];
									$weid = DB::table('mfg_wastage')
												->insertGetId([
													'manufacture_id' => $mfgid,
													'item_id' => $attributes['weitem'][$wk],
													'quantity' => $attributes['wqty'][$wk],
													'unit_price'	=> $attributes['uprice'][$wk],
													'total' => $attributes['weqtytot'][$wk],
													'deleted_at' => '0000-00-00 00:00:00'
												]);
												
								}
							}
							if($is_entry) {

								$wedr = DB::table('other_account_setting')->where('account_setting_name','MF Wastage Dr Account')->select('account_id')->first();
								DB::table('account_transaction')
									->insert([  'voucher_type' 		=> 'MV',
												'voucher_type_id'   => $mfgid,
												'account_master_id' => $wedr->account_id,
												'transaction_type'  => 'Dr',
												'amount'   			=> $wetotal,
												'status' 			=> 1,
												'created_at' 		=> date('Y-m-d H:i:s'),
												'created_by' 		=> Auth::User()->id,
												'description' 		=> 'Wastage Entry',
												'reference'			=> $voucher_no,
												'invoice_date'		=> $voucher_date,
												'reference_from'	=> '',
												'tr_for'			=> $weid,
												'other_type'		=> '',
												'department_id'		=> isset($attributes['department_id'])?$attributes['department_id']:''
											]);

								$wecr = DB::table('other_account_setting')->where('account_setting_name','MF Wastage Cr Account')->select('account_id')->first();
								DB::table('account_transaction')
									->insert([  'voucher_type' 		=> 'MV',
												'voucher_type_id'   => $mfgid,
												'account_master_id' => $wecr->account_id,
												'transaction_type'  => 'Cr',
												'amount'   			=> $wetotal,
												'status' 			=> 1,
												'created_at' 		=> date('Y-m-d H:i:s'),
												'created_by' 		=> Auth::User()->id,
												'description' 		=> 'Wastage Entry',
												'reference'			=> $voucher_no,
												'invoice_date'		=> $voucher_date,
												'reference_from'	=> '',
												'tr_for'			=> $weid,
												'other_type'		=> '',
												'department_id'		=> isset($attributes['department_id'])?$attributes['department_id']:''
											]);
							}
						}

						### MANUFACTURE WASTAGE ENTRY ###
						
						
						DB::table('account_setting')
								//->where('voucher_type_id', 15) 
								->where('id', $voucher_id)
								->update(['voucher_no' => DB::raw('voucher_no + 1') ]);
					
					
				}
				
				//AUTO COST REFRESH CHECK ENABLE OR NOT
				if($this->mod_autocost->is_active==1) {
					//$this->objUtility->reEvalItemCostQuantity($itemsid,$this->acsettings);
				}

				if($attributes['document_id']!='') {
					DB::table('production')->where('id',$attributes['document_id'])->update(['is_transfer' => 1]);
				}

				DB::commit();
				Session::flash('message', 'Manufacture vaoucher added successfully.');
				return redirect('manufacture/add');
				
			} else
				Session::flash('error', 'Something went wrong, Stock failed to transfer!');
			
		} catch(\Exception $e) { 
		
			DB::rollback(); echo $e->getLine().'-'.$e->getMessage();exit;
			Session::flash('error', 'Something went wrong.');
			return redirect('manufacture/add');
		}
		
		
	}
	
	
	public function destroy($id)
	{
		$res = DB::table('manufacture')->find($id);
		if($res) {
			DB::table('manufacture')->where('id',$id)->update(['deleted_at' => date('Y-m-d H:i:s')]);
			if($this->stock_transferin->delete($res->stock_transferin_id)) {
				$this->stock_transferout->delete($res->stock_transferout_id);
				Session::flash('message', 'Manufacture vaoucher deleted successfully.');
			}
		}
		return redirect('manufacture');
	}
	
	
	public function edit($id) { 

		$data = array();
		$res = DB::table('manufacture')->find($id);
				
		if($res) {
			
			$orderrow = $this->stock_transferin->findRow($res->stock_transferin_id);
			$orditems = $this->stock_transferin->getItems($res->stock_transferin_id);

			$mfitems = DB::table('manufacture_item')->where('manufacture_item.manufacture_id',$id)
								->join('units AS U', function($join){
									$join->on('U.id','=','manufacture_item.unit_id');
								}) 
								->join('itemmaster AS IM', function($join){
									$join->on('IM.id','=','manufacture_item.item_id');
								})
								->join('item_unit AS IU', function($join){
									$join->on('IU.itemmaster_id','=','IM.id');
								}) 
								->where('manufacture_item.status',1)
								->select('manufacture_item.*','U.unit_name','IM.item_code','IU.is_baseqty')->groupBy('manufacture_item.id')
								->get();
//echo '<pre>';print_r($orditems);print_r($mfitems);exit;
			$ocrow = DB::table('sti_other_cost')
							->join('account_master AS DrAC', 'DrAC.id', '=', 'sti_other_cost.dr_account_id')
							->join('account_master AS CrAC', 'CrAC.id', '=', 'sti_other_cost.cr_account_id')
							->where('sti_other_cost.transfer_id', $res->stock_transferin_id)
							->where('sti_other_cost.deleted_at','0000-00-00 00:00:00')
							->select('sti_other_cost.*','DrAC.master_name AS dr_name','CrAC.master_name AS cr_name')
							->get();
							
			$werow = DB::table('mfg_wastage')
							->join('itemmaster AS IM', 'IM.id', '=', 'mfg_wastage.item_id')
							->where('mfg_wastage.manufacture_id', $id)
							->where('mfg_wastage.deleted_at','0000-00-00 00:00:00')
							->select('mfg_wastage.*','IM.item_code','IM.description')
							->get();
		}
		
		//CHECK DEPARTMENT.......
		if(Session::get('department')==1) { //if active...
			$deptid = Auth::user()->department_id;
			if($deptid!=0)
				$departments = DB::table('department')->where('id',$deptid)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
			else {
				$departments = DB::table('department')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
				$deptid = $departments[0]->id;
			}
			$is_dept = true;
		} else {
			$is_dept = false;
			$departments = [];
			$deptid = '';
		}
		
		$mfgac = DB::table('manufacture')
							->join('account_master AS DrAC', 'DrAC.id', '=', 'manufacture.account_dr')
							->join('account_master AS CrAC', 'CrAC.id', '=', 'manufacture.account_cr')
							->join('account_master AS DrACto', 'DrACto.id', '=', 'manufacture.account_dr_to')
							->join('account_master AS CrACto', 'CrACto.id', '=', 'manufacture.account_cr_to')
							->where('manufacture.id', $id)
							->select('manufacture.account_dr','manufacture.account_cr','DrAC.master_name AS dr_name','CrAC.master_name AS cr_name',
									 'manufacture.account_dr_to','manufacture.account_cr_to','DrACto.master_name AS dr_name_to','CrACto.master_name AS cr_name_to',
									 'manufacture.amount','manufacture.other_cost')
							->first();
							
		$vouchers = $this->accountsetting->getAccountSettingsDefault2($vid=15,$is_dept,$deptid);
		
		$getItemLocation = $this->itemmaster->getItemLocation($res->stock_transferout_id,'TO');
		$itemlocedit = $this->makeTreeArr( $this->itemmaster->getItemLocEdit($res->stock_transferout_id,'TO') );
		
		//echo '<pre>';print_r($getItemLocation);print_r($itemlocedit);exit;
		return view('body.manufacture.edit')
					->withOcrow($ocrow)
					->withOrderrow($orderrow)
					->withWerow($werow)
					->withOrditems($orditems)
					->withMid($id)
					->withIsdept($is_dept)
					->withDepartments($departments)
					->withDeptid($deptid)
					->withVouchers($vouchers)
					->withFormdata($this->formData)
					->withVoucherno($res->voucher_no)
					->withMfgrow($mfgac)
					->withItemlocedit($itemlocedit)
					->withItemloc($getItemLocation)
					->withSettings($this->acsettings)
					->withMfitems($mfitems)
					->withData($data);

	}
	
	public function update(Request $request)
	{ 	//echo '<pre>';print_r($request->all());exit;
		DB::beginTransaction();
		try {
			$res = DB::table('manufacture')->find($request->get('mid')); 
			$voucher_date = ($request->get('voucher_date')=='')?date('Y-m-d'):date('Y-m-d',strtotime($request->get('voucher_date')));
			$amount = $request->get('total_price');
			$request->merge(['transfer_id' => $res->stock_transferin_id]);
			if($this->stock_transferin->update($res->stock_transferin_id, $request->all())) {
				
				$request->merge(['transfer_id' => $res->stock_transferout_id]);
				$attributes = $request->all();
				$qtyarr = $attributes['quantity'];
				$qtyarr_old = $attributes['actual_quantity'];
				$namearr = $attributes['item_name'];
				$untarr = $attributes['unit_id'];
				$cstarr = $attributes['cost'];
				$totarr = $attributes['line_total'];
				
				$stock_transferout_id = $res->stock_transferout_id;
				
				$is_outentry = false;
				$attributes['item_id'] = $attributes['unit_id'] = $attributes['item_name'] = $attributes['quantity'] = $attributes['cost'] = $attributes['actcost'] = $attributes['transfer_item_id'] = [];
				foreach($request->get('item_id') as $key => $item) { 
				
					//GET RAW MATERIALS....
					$rawitems = DB::table('mfg_items')->where('mfg_items.item_id', $item)
										->join('itemmaster AS IM', 'IM.id', '=', 'mfg_items.subitem_id')
										->join('item_unit AS IU', 'IU.itemmaster_id', '=', 'IM.id')
										->leftJoin('stock_transferout_item AS STO', function($join) use ($stock_transferout_id) {
											$join->on('STO.item_id','=','IM.id');
											$join->where('STO.stock_transferout_id','=',$stock_transferout_id);
										})
										->where('mfg_items.deleted_at', '0000-00-00 00:00:00')
										->where('IU.is_baseqty',1)//AUG25
										->where('STO.deleted_at', '0000-00-00 00:00:00')
										->select('mfg_items.*','IU.unit_id','IU.cost_avg','IM.description','STO.id AS transfer_item_id')
										->get();
										
					//echo '<pre>';print_r($rawitems);exit;					
					if(count($rawitems) > 0) {
						foreach($rawitems as $ritms) {
							$attributes['item_id'][] =  $ritms->subitem_id;
							$attributes['unit_id'][] =  $ritms->unit_id;
							$attributes['item_name'][] =  $ritms->description;
							$attributes['quantity'][] =  $qtyarr[$key] * $ritms->quantity;
							$attributes['old_quantity'][] =  $qtyarr_old[$key] * $ritms->quantity;
							$attributes['cost'][] =  $ritms->cost_avg;
							$attributes['actcost'][] =  $ritms->cost_avg;
							$attributes['transfer_item_id'][] =  $ritms->transfer_item_id;
							
							$is_outentry = true;
						}
						
						
					}
					
				}
                
				if($is_outentry) {
				    
				    if($this->modbatch->is_active==1)
					    $attributes['is_batch'] = true;
					
					//REMOVED ITEMS..
					$tids = '';
					if($request->get('remove_item_mf')!='') {
						$arrids = explode(',', $request->get('remove_item_mf'));
						foreach($arrids as $row) { 
							$toarr[] = DB::table('stock_transferout_item')->where('stock_transferout_id', $stock_transferout_id)->where('mfg_item_id',$row)->select('id')->get();
						}
						
						foreach($toarr as $trs) {
							foreach($trs as $tr)
								$tids .= ($tids=='')?$tr->id:','.$tr->id;
						}
					}
					$attributes['remove_item'] = $tids;

					//DO ACCOUNT REVERSE ENTRY POSTING....
					$attributes['account_dr'] = $request->get('account_dr_to');
					$attributes['account_cr'] = $request->get('account_cr_to');
					
					$this->stock_transferout->update($res->stock_transferout_id, $attributes);
					
				}
				
				
				//AUTO COST REFRESH CHECK ENABLE OR NOT
				if($this->mod_autocost->is_active==1) {
					$this->objUtility->reEvalItemCostQuantity($request->get('item_id'),$this->acsettings);
				}
				
				DB::table('manufacture')->where('id', $request->get('mid'))->update(['voucher_date' => $voucher_date, 'amount' => $amount, 'other_cost' => $request->get('other_cost') ]);
				$ocamount = array_sum($attributes['oc_amount']); $oc_perunit = $item_oc = 0;	
				foreach($request->get('item_id') as $key => $value) { 
					
					//UPDATING ROWMATERIAL COST...
					if($ocamount > 0) {
						$oc_perunit = ($ocamount * $cstarr[$key]) / $attributes['total_hd'];
						$item_oc = $cstarr[$key] + $oc_perunit;
						$total_pr = $item_oc * $qtyarr[$key];
					}
					
					if($attributes['transfer_item_id'][$key]!='') {
						
						DB::table('manufacture_item')
									->where('id', $attributes['transfer_item_id'][$key])
									->update([
										'item_id'		=> $value,
										'item_name'		=> $namearr[$key],
										'unit_id'		=> $untarr[$key],
										'quantity'		=> $qtyarr[$key],
										'price'			=> $cstarr[$key],
										'item_total'	=> $totarr[$key],
										'other_cost'	=> $oc_perunit,
										'netcost_unit'	=> $item_oc
									]);
							
					} else {
						
						DB::table('manufacture_item')
								->insert([
									'manufacture_id' => $request->get('mid'),
									'item_id'		=> $value,
									'item_name'		=> $namearr[$key],
									'unit_id'		=> $untarr[$key],
									'quantity'		=> $qtyarr[$key],
									'price'			=> $cstarr[$key],
									'item_total'	=> $totarr[$key],
									'status'		=> 1,
									'other_cost'	=> $oc_perunit,
									'netcost_unit'	=> $item_oc
								]);
					}
				}

				//UPDATE MV ACCOUNT TRANSACTION.....
				DB::table('account_transaction')
									->where('voucher_type', 'MV')
									->where('voucher_type_id', $request->get('mid'))
									->update([ 'amount' => $amount,]);

				
				//MFG REMOVED ITEMS..
				if($request->get('remove_item_mf')!='') {
					$arrids = explode(',', $request->get('remove_item_mf'));
					foreach($arrids as $row) {
						DB::table('manufacture_item')->where('id', $row)->update(['status' => 0, 'deleted_at' => date('Y-m-d H:i:s')]);
					}
				}
				
				### MANUFACTURE WASTAGE ENTRY ###
				if(isset($attributes['wqty'])) { 
					$is_entry = $is_entry_edit = false; $wetotal = 0;
					foreach($attributes['wqty'] as $wk => $wval) {
						if($wval !=''){
							if(isset($attributes['weid']) && ($attributes['weid']!='')) {
								$is_entry_edit = true;
								$wetotal += $attributes['weqtytot'][$wk];
								DB::table('mfg_wastage')
											->where('id', $attributes['weid'][$wk])
											->update([
												'manufacture_id' => $request->get('mid'),
												'item_id' => $attributes['weitem'][$wk],
												'quantity' => $attributes['wqty'][$wk],
												'unit_price'	=> $attributes['uprice'][$wk],
												'total' => $attributes['weqtytot'][$wk],
											]);
							} else {
								$is_entry = true;
								$wetotal += $attributes['weqtytot'][$wk];
								$weid = DB::table('mfg_wastage')
											->insertGetId([
												'manufacture_id' => $request->get('mid'),
												'item_id' => $attributes['weitem'][$wk],
												'quantity' => $attributes['wqty'][$wk],
												'unit_price'	=> $attributes['uprice'][$wk],
												'total' => $attributes['weqtytot'][$wk],
												'deleted_at' => '0000-00-00 00:00:00'
											]);
							}
										
						}
					}

					if($is_entry_edit) {

						$wedr = DB::table('other_account_setting')->where('account_setting_name','MF Wastage Dr Account')->select('account_id')->first();
						DB::table('account_transaction')
							->where('voucher_type', 'MV')
							->where('voucher_type_id', $request->get('mid'))
							->where('account_master_id', $wedr->account_id)
							->where('transaction_type', 'Dr')
							->update([ 'amount' => $wetotal,]);

						$wecr = DB::table('other_account_setting')->where('account_setting_name','MF Wastage Cr Account')->select('account_id')->first();
						DB::table('account_transaction')
							->where('voucher_type', 'MV')
							->where('voucher_type_id', $request->get('mid'))
							->where('account_master_id', $wecr->account_id)
							->where('transaction_type', 'Cr')
							->update([ 'amount' => $wetotal,]);
					}

					if($is_entry) {

						$wedr = DB::table('other_account_setting')->where('account_setting_name','MF Wastage Dr Account')->select('account_id')->first();
						DB::table('account_transaction')
							->insert([  'voucher_type' 		=> 'MV',
										'voucher_type_id'   => $request->get('mid'),
										'account_master_id' => $wedr->account_id,
										'transaction_type'  => 'Dr',
										'amount'   			=> $wetotal,
										'status' 			=> 1,
										'created_at' 		=> date('Y-m-d H:i:s'),
										'created_by' 		=> Auth::User()->id,
										'description' 		=> 'Wastage Entry',
										'reference'			=> $request->get('voucher_no'),
										'invoice_date'		=> $voucher_date,
										'reference_from'	=> '',
										'tr_for'			=> $weid,
										'other_type'		=> '',
										'department_id'		=> isset($attributes['department_id'])?$attributes['department_id']:''
									]);

						$wecr = DB::table('other_account_setting')->where('account_setting_name','MF Wastage Cr Account')->select('account_id')->first();
						DB::table('account_transaction')
							->insert([  'voucher_type' 		=> 'MV',
										'voucher_type_id'   => $request->get('mid'),
										'account_master_id' => $wecr->account_id,
										'transaction_type'  => 'Cr',
										'amount'   			=> $wetotal,
										'status' 			=> 1,
										'created_at' 		=> date('Y-m-d H:i:s'),
										'created_by' 		=> Auth::User()->id,
										'description' 		=> 'Wastage Entry',
										'reference'			=> $request->get('voucher_no'),
										'invoice_date'		=> $voucher_date,
										'reference_from'	=> '',
										'tr_for'			=> $weid,
										'other_type'		=> '',
										'department_id'		=> isset($attributes['department_id'])?$attributes['department_id']:''
									]);
					}
				}

				### MANUFACTURE WASTAGE ENTRY ###

					DB::commit();
					Session::flash('message', 'Manufacture updated successfully');
					return redirect('manufacture');
				
			} else
				Session::flash('error', 'Something went wrong, Manufacture failed to update!');
		
		} catch(\Exception $e) { 
		
			DB::rollback(); echo $e->getLine().'-'.$e->getMessage();exit;
			Session::flash('error', 'Something went wrong.');
			return redirect('manufacture');
		}
	}
	
	
	
		public function viewonly($id) { 

		$data = array();
		$res = DB::table('manufacture')->find($id);
				
		if($res) {
			
			$orderrow = $this->stock_transferin->findRow($res->stock_transferin_id);
			$orditems = $this->stock_transferin->getItems($res->stock_transferin_id);

			$mfitems = DB::table('manufacture_item')->where('manufacture_item.manufacture_id',$id)
								->join('units AS U', function($join){
									$join->on('U.id','=','manufacture_item.unit_id');
								}) 
								->join('itemmaster AS IM', function($join){
									$join->on('IM.id','=','manufacture_item.item_id');
								})
								->join('item_unit AS IU', function($join){
									$join->on('IU.itemmaster_id','=','IM.id');
								}) 
								->where('manufacture_item.status',1)
								->select('manufacture_item.*','U.unit_name','IM.item_code','IU.is_baseqty')->groupBy('manufacture_item.id')
								->get();
//echo '<pre>';print_r($orditems);print_r($mfitems);exit;
			$ocrow = DB::table('sti_other_cost')
							->join('account_master AS DrAC', 'DrAC.id', '=', 'sti_other_cost.dr_account_id')
							->join('account_master AS CrAC', 'CrAC.id', '=', 'sti_other_cost.cr_account_id')
							->where('sti_other_cost.transfer_id', $res->stock_transferin_id)
							->where('sti_other_cost.deleted_at','0000-00-00 00:00:00')
							->select('sti_other_cost.*','DrAC.master_name AS dr_name','CrAC.master_name AS cr_name')
							->get();
							
			$werow = DB::table('mfg_wastage')
							->join('itemmaster AS IM', 'IM.id', '=', 'mfg_wastage.item_id')
							->where('mfg_wastage.manufacture_id', $id)
							->where('mfg_wastage.deleted_at','0000-00-00 00:00:00')
							->select('mfg_wastage.*','IM.item_code','IM.description')
							->get();
		}
		
		//CHECK DEPARTMENT.......
		if(Session::get('department')==1) { //if active...
			$deptid = Auth::user()->department_id;
			if($deptid!=0)
				$departments = DB::table('department')->where('id',$deptid)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
			else {
				$departments = DB::table('department')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
				$deptid = $departments[0]->id;
			}
			$is_dept = true;
		} else {
			$is_dept = false;
			$departments = [];
			$deptid = '';
		}
		
		$mfgac = DB::table('manufacture')
							->join('account_master AS DrAC', 'DrAC.id', '=', 'manufacture.account_dr')
							->join('account_master AS CrAC', 'CrAC.id', '=', 'manufacture.account_cr')
							->join('account_master AS DrACto', 'DrACto.id', '=', 'manufacture.account_dr_to')
							->join('account_master AS CrACto', 'CrACto.id', '=', 'manufacture.account_cr_to')
							->where('manufacture.id', $id)
							->select('manufacture.account_dr','manufacture.account_cr','DrAC.master_name AS dr_name','CrAC.master_name AS cr_name',
									 'manufacture.account_dr_to','manufacture.account_cr_to','DrACto.master_name AS dr_name_to','CrACto.master_name AS cr_name_to',
									 'manufacture.amount','manufacture.other_cost')
							->first();
							
		$vouchers = $this->accountsetting->getAccountSettingsDefault2($vid=15,$is_dept,$deptid);
		
		$getItemLocation = $this->itemmaster->getItemLocation($res->stock_transferout_id,'TO');
		$itemlocedit = $this->makeTreeArr( $this->itemmaster->getItemLocEdit($res->stock_transferout_id,'TO') );
		
		//echo '<pre>';print_r($getItemLocation);print_r($itemlocedit);exit;
		return view('body.manufacture.viewonly')
					->withOcrow($ocrow)
					->withOrderrow($orderrow)
					->withWerow($werow)
					->withOrditems($orditems)
					->withMid($id)
					->withIsdept($is_dept)
					->withDepartments($departments)
					->withDeptid($deptid)
					->withVouchers($vouchers)
					->withFormdata($this->formData)
					->withVoucherno($res->voucher_no)
					->withMfgrow($mfgac)
					->withItemlocedit($itemlocedit)
					->withItemloc($getItemLocation)
					->withSettings($this->acsettings)
					->withMfitems($mfitems)
					->withData($data);

	}
	
	
	public function getPrint($id)
	
	{
	   
		$attributes['document_id'] = $id;
		$res = DB::table('manufacture')->find($id);
		
		$attributes['document_id'] = $res->stock_transferin_id;
		//echo '<pre>';print_r($attributes);exit; 
		$result = $this->stock_transferin->getDoc($attributes); //echo '<pre>';print_r($result['details']);exit;
		foreach($result['items'] as $row) {
			$rawmat[$row->item_id] = $this->getRawMaterials($row->item_id);
		}
		$ocrow = DB::table('sti_other_cost')
							->join('account_master AS DrAC', 'DrAC.id', '=', 'sti_other_cost.dr_account_id')
							->join('account_master AS CrAC', 'CrAC.id', '=', 'sti_other_cost.cr_account_id')
							->where('sti_other_cost.transfer_id', $res->stock_transferin_id)
							->where('sti_other_cost.deleted_at','0000-00-00 00:00:00')
							->select('sti_other_cost.*','DrAC.master_name AS dr_name','CrAC.master_name AS cr_name')
							->get();
							
		//echo '<pre>';print_r($ocrow);exit;
		
		$titles = ['main_head' => 'Manufacture Voucher','subhead' => 'Manufacture Voucher'];
		return view('body.manufacture.print')
					->withDetails($result['details'])
					->withTitles($titles)
					->withRawmat($rawmat)
					->withOcost($ocrow)
					->withMfgno($res->voucher_no)
					->withId($id)
					->withMres($res)
					->withItems($result['items']);
		
	}
	
		public function printExport(Request $request)
	{
	    
	    //echo '<pre>';print_r($request->all());exit;
	    $input=$request->all();
	    $id=$input['id'];
	     //echo '<pre>';print_r($id);exit;
		$data = array();
		$attributes['document_id'] = $id;
		$res = DB::table('manufacture')->find($id);
		
		$attributes['document_id'] = $res->stock_transferin_id;
		//echo '<pre>';print_r($attributes);exit; 
		$result = $this->stock_transferin->getDoc($attributes); //echo '<pre>';print_r($result['details']);exit;
		foreach($result['items'] as $row) {
			$rawmat[$row->item_id] = $this->getRawMaterials($row->item_id);
		}
		$ocrow = DB::table('sti_other_cost')
							->join('account_master AS DrAC', 'DrAC.id', '=', 'sti_other_cost.dr_account_id')
							->join('account_master AS CrAC', 'CrAC.id', '=', 'sti_other_cost.cr_account_id')
							->where('sti_other_cost.transfer_id', $res->stock_transferin_id)
							->where('sti_other_cost.deleted_at','0000-00-00 00:00:00')
							->select('sti_other_cost.*','DrAC.master_name AS dr_name','CrAC.master_name AS cr_name')
							->get();
		$mres=$res;
		$details=	$result['details']	;	
		$items=$result['items'];
		$rawmat=$rawmat;
		//echo '<pre>';print_r($ocrow);exit;
		
		$datareport[] = ['','','',strtoupper(Session::get('company')),'','',''];
		$datareport[] = ['','','','','','',''];
		
		
			$voucher_head = 'Manufacturing Voucher';
		
		$datareport[] = ['','','',strtoupper($voucher_head), '','',''];
		$datareport[] = ['','','','','','',''];
		$datareport[] = ['','','','','','',''];
		$mfno=$res->voucher_no;
		$date=date('d-m-Y',strtotime($details->voucher_date));
		$datareport[] = ['','','','','','MfNo:',$mfno];
		$datareport[] = ['','','','','','Date:',$date];
		$datareport[] = ['','','','','','',''];
		// echo '<pre>';print_r($datareport);exit;
		
		
			
			$datareport[] = ['SI.No.','Item Code', 'Description', 'Unit','Qty','Unit Price','Total Amount'];
			$i = $net_total =$oc=$uprice=$total= $octotal=0;
			
			foreach ($items as $row) {
					$i++;
					$oc = $mres->other_cost/($mres->amount - $mres->other_cost) * $row->price;
					$uprice = $oc + $row->price;
				    $total=	$uprice * $row->quantity;
					$price=number_format($uprice,2);
					$tot=number_format($total,2);
					$datareport[] = [ 'si' => $i,
									  'ic' => $row->item_code,
									  'des' => $row->item_name,
									  'unit' => $row->unit_name,
									  'qty' => $row->quantity,
									  'unitprice' => $price,
									  'total' => $tot
									];
	
		 //echo '<pre>';print_r($datareport);exit;
		 	$voucher = 'Raw materials';
		$datareport[] = ['','','','','','',''];
		$datareport[] = [$voucher,'','','', '','',''];
		 	$datareport[] = ['','','','','','',''];
		$datareport[] = ['','Item Code', 'Description', 'Qty','Cost/Unit','Total Amount',''];
			$i = $net_total =$qty=$prices=$rmtotal= 0;
			
			foreach ($rawmat[$row->item_id] as $raw) {
					$i++;
					$prices = $raw->quantity * $row->quantity * $raw->unit_price;
					$rmtotal += $prices;
					$qty = $raw->quantity*$row->quantity;
				   
					$unit=number_format($raw->unit_price,2);
					$tot=number_format($prices,2);
					$datareport[] = [ 'si' =>'' ,
									  'ic' => $raw->item_code,
									  'des' => $raw->description,
									  'qty' => $qty,
									  'unitprice' => $unit,
									  'total' => $tot,
									  'ex'=>''
									];
									
				  $net_total = $rmtotal;
		
			
			$datareport[] = ['','','','','Total:',number_format($rmtotal,2)];
		}
		//echo '<pre>';print_r($datareport);exit;
		$datareport[] = ['','','','','','',''];
		$datareport[] = ['','','','','','',''];
		foreach ($ocrow as $cost) {
					$i++;
					$amount=number_format($cost->amount,2);
					$total += $cost->amount; 
					$octotal += $cost->amount;
					$datareport[] = [ 'si' =>'' ,
									  'ic' => 'Other Cost',
									  'des' => $cost->description,
									  'qty' => '',
									  'unitprice' => '',
									  'total' => '',
									  'ex'=>$amount
									];
									
				  
		}
		
		
			
			$datareport[] = ['','','','','','Gross Total:',number_format($net_total,2)];
			$datareport[] = ['','','','','','Other Cost Total:',number_format($octotal,2)];
			$datareport[] = ['','','','','','Net Total:',number_format($net_total+$octotal,2)];
		}
			//echo '<pre>';print_r($datareport);exit;
		 //echo $voucher_head.'<pre>';print_r($datareport);exit;
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
		
	}

	
	public function getDeptVoucher($id) {
		
		$depts = $this->accountsetting->getVoucherByDeptSTI($vid=15, $id); 
		
		foreach($depts as $row) {
			
			 if($row->voucher_no != '' || $row->voucher_no != null) {
				 if($row->is_prefix==0)
					 $voucher = $row->voucher_no;
				 else {
					 $no = (int)$row->voucher_no;
					 $voucher = $row->prefix.''.$no;
				 }
			 }
			 
			  $result[] = array('voucher_no' => $voucher, 
								'cr_account_name' => ($row->master_name!='')?$row->master_name:'', 
								'cr_id' => ($row->cr_account_master_id!='')?$row->cr_account_master_id:'',
								'dr_account_name' => ($row->dr_account!='')?$row->dr_account:'', 
								'dr_id' => ($row->dr_account_master_id!='')?$row->dr_account_master_id:'',
								'voucher_name' => $row->voucher_name,
								'voucher_id' => $row->voucher_id
							);

		}
		
		return $result;
	}
	
	private function makeTreeArr($result) {
		
		$childs = array();
		foreach($result as $item)
			$childs[$item->trout_id][] = $item;
		
		return $childs;
	}
	private function getRawMaterialsReport($id) {
		//echo '<pre>';print_r($id);exit;
		$result =  DB::table('mfg_items')->where('mfg_items.item_id', $id)
						->join('itemmaster', 'itemmaster.id', '=', 'mfg_items.subitem_id')
						->where('mfg_items.deleted_at','0000-00-00 00:00:00')
						->select('itemmaster.item_code','itemmaster.description','mfg_items.*')
						->get();
		//echo '<pre>';print_r($result);exit;
		return $result;
						
		
	}
	private function getRawMaterials($id) {
		
		$result =  DB::table('mfg_items')->where('mfg_items.item_id', $id)
						->join('itemmaster', 'itemmaster.id', '=', 'mfg_items.subitem_id')
						->where('mfg_items.deleted_at','0000-00-00 00:00:00')
						->select('itemmaster.item_code','itemmaster.description','mfg_items.*')
						->get();
		return $result;
						
		
	}

	public function getVoucher($id) {
		
		$row = $this->accountsetting->getAccountSettingsDefault2($id);
		if($row->voucher_no != '' || $row->voucher_no != null) {
			if($row->is_prefix==0)
				$voucher = $row->voucher_no;
			else {
				$no = (int)$row->voucher_no;
				$voucher = $row->prefix.''.$no;
			}
		}
		return $result = array('voucher_no' => $voucher, 
							   'account_id' => $row->account_id, 
							   'account_name' => $row->master_name, 
							   'id' => $row->id,
							   'caccount_id' => $row->caccount_id,
							   'caccount_name' => $row->cmaster_name,
							   'cid'	=> $row->cid,
							   'cash_voucher' => $row->is_cash_voucher,
							   'default_account' => $row->default_account,
							   'cash_account' => $row->default_account_id
							   );//print_r($ob);

   }
}


