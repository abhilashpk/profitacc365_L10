<?php

namespace App\Http\Controllers;
use App\Repositories\AccountSetting\AccountSettingInterface;
use App\Repositories\StockTransferout\StockTransferoutInterface;
use App\Repositories\StockTransferin\StockTransferinInterface;
use App\Repositories\UpdateUtility;
use App\Repositories\Itemmaster\ItemmasterInterface;
use App\Repositories\Production\ProductionInterface;

use Illuminate\Http\Request;

use App\Http\Requests;
use Session;
use Response;
use Input;
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
	
	public function __construct(ProductionInterface $production, StockTransferinInterface $stock_transferin, StockTransferoutInterface $stock_transferout, AccountSettingInterface $accountsetting,ItemmasterInterface $itemmaster) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		
		$this->middleware('auth');
		$this->accountsetting = $accountsetting;
		$this->stock_transferin = $stock_transferin;
		$this->stock_transferout = $stock_transferout;
		$this->itemmaster = $itemmaster;
		$this->production = $production;
		
		$this->mod_autocost = DB::table('parameter2')->where('keyname', 'mod_autocost_refresh')->where('status',1)->select('is_active')->first();
		$this->objUtility = new UpdateUtility();
	}
	
    public function index() {
		
		$data = array();
		$stocktrans = DB::table('manufacture')->where('deleted_at','0000-00-00 00:00:00')->orderBy('id','DESC')->get();
		return view('body.manufacture.index')
					->withStocktrans($stocktrans)
					->withType('')
					->withSettings($this->acsettings)
					->withData($data);
	}
	
	
	
	public function add(Request $request, $id = null, $doctype = null) {

		$data = array();
		$vouchers = $this->accountsetting->getAccountSettingsDefault2($vid=15); //echo '<pre>';print_r($vouchers);exit;
		$lastid = DB::table('manufacture')->where('deleted_at','0000-00-00 00:00:00')->orderBy('id','DESC')->select('id')->first();
		
		
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
			
			//echo '<pre>';print_r($docItems);exit;
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
					->withDeptid($deptid)
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
						
					
			return $query->select('CR.master_name AS cr_account','STIT.*','IM.item_code','U.unit_name','DR.master_name AS dr_account','manufacture.*','manufacture.voucher_no AS mgno','manufacture.id AS id')
			->groupBy('manufacture.id')->get();
		
	}


	public function getSearch()
	{
		$data = $report = $reports =$rawitem= array();
		if(Input::get('search_type')=="summary") {
			$voucher_head = 'Manufacture summary (STOCK IN ITEM)';
			$report =$this->getReport(Input::all());
			//echo '<pre>';print_r($report); exit();
			$reports = $this->makeTree($report);
			$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];
		}else if(Input::get('search_type')=="detail") {
			$voucher_head = 'Manufacture Detail ';
			$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];
			$report =$this->getReport(Input::all());
            $reports = $this->makeTree($report);
		    foreach ($report as $row)
			 	{
					 $rawitem[$row->item_id]= $this->getRawMaterialsReport($row->item_id);
		            
			 }
		}
	    	
		return view('body.manufacture.preprint')
					->withReports($reports)
					->withTitles($titles)
				   
					->withRawitem($rawitem)
					->withVoucherhead($voucher_head)
					->withType(Input::get('search_type'))
					->withFromdate(Input::get('date_from'))
					->withTodate(Input::get('date_to'))
					->withI(0)
					->withSettings($this->acsettings)
					->withData($data);
	}
	
	
	public function dataExport()
	{
		$data = array();
		$reports = $this->getReport(Input::all());
		
		$datareport[] = ['','','',strtoupper(Session::get('company')),'','',''];
		$datareport[] = ['','','','','','',''];
		
		if(Input::get('search_type')=="summary")
			$voucher_head = 'Manufacture  Summary';
		
		$datareport[] = ['','','',strtoupper($voucher_head), '','',''];
		$datareport[] = ['','','','','','',''];
		
		// echo '<pre>';print_r($reports);exit;
		
		
			
			$datareport[] = ['SI.No.','GI.No', 'GI.Date', 'Job Code','Job Name','Description','Total Amount'];
			$i = $net_total = 0;
			
			foreach ($reports as $row) {
					$i++;
					$datareport[] = [ 'si' => $i,
									  'po' => $row->voucher_no,
									  'vchr_dt' => date('d-m-Y',strtotime($row->voucher_date)),
									  'jobcode' => $row->code,
									  'name' => $row->jobname,
									  'description' => $row->description,
									  'total' => $row->net_amount
									];
									
				  $net_total += $row->net_amount;
		
			
			$datareport[] = ['','','','','','Total:',number_format($net_total,2)];
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
	
	public function save() {
		//echo '<pre>';print_r(Input::all());exit; 

		DB::beginTransaction();
			try {
			//GET STOCK TRANSFER IN VOUCHER..
			if(Session::get('department')==1)
				$sti = DB::table('account_setting')->where('voucher_type_id', 21)->where('department_id', Input::get('department_id'))->select('id','voucher_no')->first();
			else
				$sti = DB::table('account_setting')->where('voucher_type_id', 21)->select('id','voucher_no')->first();
			$voucher_no = Input::get('voucher_no');
			$voucher_date = (Input::get('voucher_date')=='')?date('Y-m-d'):date('Y-m-d',strtotime(Input::get('voucher_date')));
			$amount = Input::get('total_price');
			$voucher_id = Input::get('voucher_id');
			$itemsid = Input::get('item_id');
			if($sti) {
				Input::merge(['voucher_id' => $sti->id]);
				Input::merge(['curno' => $sti->voucher_no]);
				Input::merge(['voucher_no' => $sti->voucher_no]);
				Input::merge(['is_mfg' => 1]);
			}
			//DO STOCK TRANSFER IN AS NEW ITEM..
			$trin = $this->stock_transferin->create(Input::all());
			if($trin) {
				
				//GET STOCK TRANSFER OUT VOUCHER..
				if(Session::get('department')==1)
					$sto = DB::table('account_setting')->where('voucher_type_id', 22)->where('department_id', Input::get('department_id'))->select('id','voucher_no')->first();
				else
					$sto = DB::table('account_setting')->where('voucher_type_id', 22)->select('id','voucher_no')->first();
				
				if($sto) {
					Input::merge(['voucher_id' => $sto->id]);
					Input::merge(['curno' => $sto->voucher_no]);
					Input::merge(['voucher_no' => $sto->voucher_no]);
					Input::merge(['is_mfg' => 1]);
				}
				
				//DO STOCK TRANSFER OUT USED RAW MATERIALS... manufacture_item manufacture
				$attributes = Input::all();
				$itemsarr = $attributes['item_id'];
				$qtyarr = $attributes['quantity'];
				$namearr = $attributes['item_name'];
				$untarr = $attributes['unit_id'];
				$cstarr = $attributes['cost'];
				$totarr = $attributes['line_total'];
				//echo '<pre>';print_r($attributes);exit;
				
				foreach($itemsarr as $key => $item) { 
					$attributes['item_id'] = $attributes['unit_id'] = $attributes['item_name'] = $attributes['quantity'] = $attributes['cost'] = $attributes['actcost'] = [];
					
					$rawitems = DB::table('mfg_items')->where('mfg_items.item_id', $item)
									->join('itemmaster AS IM', 'IM.id', '=', 'mfg_items.subitem_id')
									->join('item_unit AS IU', 'IU.itemmaster_id', '=', 'IM.id')
									->where('mfg_items.deleted_at', '0000-00-00 00:00:00')
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
							$attributes['packing'][] =  1;
							
						}
						//echo '<pre>';print_r($attributes);exit;
						
						//DO ACCOUNT REVERSE ENTRY POSTING....
						$attributes['account_dr'] = Input::get('account_dr_to');
						$attributes['account_cr'] = Input::get('account_cr_to');
						
						$trout = $this->stock_transferout->create($attributes);
						
					}
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
												'account_dr' => Input::get('account_dr'),
												'account_cr' => Input::get('account_cr'),
												'other_cost' => Input::get('other_cost'),
												'account_dr_to' => Input::get('account_dr_to'),
												'account_cr_to' => Input::get('account_cr_to'),
												]);
						
						$ocamount = array_sum($attributes['oc_amount']); $oc_perunit = $item_oc = 0;			
						foreach($itemsarr as $key => $item) { 
							
							//UPDATING ROWMATERIAL COST...
							if($ocamount > 0) {
								$oc_perunit = ($ocamount * $cstarr[$key]) / $attributes['total_hd'];
								$item_oc = $cstarr[$key] + $oc_perunit;
								$total_pr = $item_oc * $qtyarr[$key];
							}
							
							DB::table('manufacture_item')
									->insert([
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
					->withWerow($werow)
					->withOrderrow($orderrow)
					->withOrditems($orditems)
					->withMid($id)
					->withIsdept($is_dept)
					->withDepartments($departments)
					->withDeptid($deptid)
					->withVouchers($vouchers)
					->withVoucherno($res->voucher_no)
					->withMfgrow($mfgac)
					->withItemlocedit($itemlocedit)
					->withItemloc($getItemLocation)
					->withSettings($this->acsettings)
					->withData($data);

	}
	
	public function update(Request $request)
	{ 	
		DB::beginTransaction();
		try {
			$res = DB::table('manufacture')->find(Input::get('mid')); //echo '<pre>';print_r($request->all());exit;
			$voucher_date = (Input::get('voucher_date')=='')?date('Y-m-d'):date('Y-m-d',strtotime(Input::get('voucher_date')));
			$amount = Input::get('total_price');
			Input::merge(['transfer_id' => $res->stock_transferin_id]);
			if($this->stock_transferin->update($res->stock_transferin_id, Input::all())) {
				
				Input::merge(['transfer_id' => $res->stock_transferout_id]);
				$attributes = Input::all();
				$qtyarr = $attributes['quantity'];
				$namearr = $attributes['item_name'];
				$untarr = $attributes['unit_id'];
				$cstarr = $attributes['cost'];
				$totarr = $attributes['line_total'];
				
				$stock_transferout_id = $res->stock_transferout_id;
				
				foreach(Input::get('item_id') as $key => $item) { 
				
					$attributes['item_id'] = $attributes['unit_id'] = $attributes['item_name'] = $attributes['quantity'] = $attributes['cost'] = $attributes['actcost'] = $attributes['transfer_item_id'] = [];
					
					//GET RAW MATERIALS....
					$rawitems = DB::table('mfg_items')->where('mfg_items.item_id', $item)
										->join('itemmaster AS IM', 'IM.id', '=', 'mfg_items.subitem_id')
										->join('item_unit AS IU', 'IU.itemmaster_id', '=', 'IM.id')
										->leftJoin('stock_transferout_item AS STO', function($join) use ($stock_transferout_id) {
											$join->on('STO.item_id','=','IM.id');
											$join->where('STO.stock_transferout_id','=',$stock_transferout_id);
										})
										->where('mfg_items.deleted_at', '0000-00-00 00:00:00')
										->select('mfg_items.*','IU.unit_id','IU.cost_avg','IM.description','STO.id AS transfer_item_id')
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
							$attributes['transfer_item_id'][] =  $ritms->transfer_item_id;
							
						}
						
						//DO ACCOUNT REVERSE ENTRY POSTING....
						$attributes['account_dr'] = Input::get('account_dr_to');
						$attributes['account_cr'] = Input::get('account_cr_to');
						
						$this->stock_transferout->update($res->stock_transferout_id, $attributes);
					}
					
				}
				
				//AUTO COST REFRESH CHECK ENABLE OR NOT
				if($this->mod_autocost->is_active==1) {
					$this->objUtility->reEvalItemCostQuantity(Input::get('item_id'),$this->acsettings);
				}
				
				DB::table('manufacture')->where('id', Input::get('mid'))->update(['voucher_date' => $voucher_date, 'amount' => $amount, 'other_cost' => Input::get('other_cost') ]);
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
									'manufacture_id' => Input::get('mid'),
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
												'manufacture_id' => Input::get('mid'),
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
												'manufacture_id' => Input::get('mid'),
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
							->where('voucher_type_id', Input::get('mid'))
							->where('account_master_id', $wedr->account_id)
							->where('transaction_type', 'Dr')
							->update([ 'amount' => $wetotal,]);

						$wecr = DB::table('other_account_setting')->where('account_setting_name','MF Wastage Cr Account')->select('account_id')->first();
						DB::table('account_transaction')
							->where('voucher_type', 'MV')
							->where('voucher_type_id', Input::get('mid'))
							->where('account_master_id', $wecr->account_id)
							->where('transaction_type', 'Cr')
							->update([ 'amount' => $wetotal,]);
					}

					if($is_entry) {

						$wedr = DB::table('other_account_setting')->where('account_setting_name','MF Wastage Dr Account')->select('account_id')->first();
						DB::table('account_transaction')
							->insert([  'voucher_type' 		=> 'MV',
										'voucher_type_id'   => Input::get('mid'),
										'account_master_id' => $wedr->account_id,
										'transaction_type'  => 'Dr',
										'amount'   			=> $wetotal,
										'status' 			=> 1,
										'created_at' 		=> date('Y-m-d H:i:s'),
										'created_by' 		=> Auth::User()->id,
										'description' 		=> 'Wastage Entry',
										'reference'			=> Input::get('voucher_no'),
										'invoice_date'		=> $voucher_date,
										'reference_from'	=> '',
										'tr_for'			=> $weid,
										'other_type'		=> '',
										'department_id'		=> isset($attributes['department_id'])?$attributes['department_id']:''
									]);

						$wecr = DB::table('other_account_setting')->where('account_setting_name','MF Wastage Cr Account')->select('account_id')->first();
						DB::table('account_transaction')
							->insert([  'voucher_type' 		=> 'MV',
										'voucher_type_id'   => Input::get('mid'),
										'account_master_id' => $wecr->account_id,
										'transaction_type'  => 'Cr',
										'amount'   			=> $wetotal,
										'status' 			=> 1,
										'created_at' 		=> date('Y-m-d H:i:s'),
										'created_by' 		=> Auth::User()->id,
										'description' 		=> 'Wastage Entry',
										'reference'			=> Input::get('voucher_no'),
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
	
	
	public function getPrint($id)
	{
		$attributes['document_id'] = $id;
		$res = DB::table('manufacture')->find($id);
		
		$attributes['document_id'] = $res->stock_transferin_id;
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
					->withMres($res)
					->withItems($result['items']);
		
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

