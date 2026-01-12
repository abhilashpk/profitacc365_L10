<?php
namespace App\Http\Controllers;
use App\Repositories\Itemmaster\ItemmasterInterface;
use App\Repositories\AccountMaster\AccountMasterInterface;
use App\Repositories\Accategory\AccategoryInterface;
use App\Repositories\Acgroup\AcgroupInterface; 
use App\Repositories\CustomerDo\CustomerDoInterface;
use App\Models\OpeningBalanceTr;
use App\Repositories\StockTransferout\StockTransferoutInterface;
use App\Repositories\StockTransferin\StockTransferinInterface;

use Illuminate\Http\Request;

use App\Http\Requests;
use Input;
use Session;
use Response;
use DB;
use Excel;
use App;
use Auth;
use DateTime;

class ImportDataController extends Controller
{
	protected $accountmaster;
	protected $customerdo;
	protected $group;
	protected $category;
	protected $stock_transferin;
	protected $stock_transferout;
	
	public function __construct(CustomerDOInterface $customerdo,ItemmasterInterface $itemmaster,AccountMasterInterface $accountmaster,AccategoryInterface $category,AcgroupInterface $group,StockTransferinInterface $stock_transferin, StockTransferoutInterface $stock_transferout) {

		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->middleware('auth');
		$this->itemmaster = $itemmaster;
		$this->category = $category;
		$this->group = $group;
		$this->accountmaster = $accountmaster;
		$this->customerdo = $customerdo;
		$this->stock_transferin = $stock_transferin;
		$this->stock_transferout = $stock_transferout;
	}
	
	public function importItems() { 
		$data = array(); 
		return view('body.importdata.items')
					->withType('items')
					->withData($data);
	}
	
	public function importTallyItems() { 
		$data = array(); 
		return view('body.importdata.items')
					->withType('tallyitems')
					->withData($data);
	}
	
	public function importAccounts() { 
		$data = array(); 
		return view('body.importdata.items')
					->withType('accounts')
					->withData($data);
	}
	
	public function importConLocStock() { 
		$data = array(); 
		return view('body.importdata.items')
					->withType('clstock')
					->withData($data);
	}
	
	public function importOpnBalance() { 
		$data = array(); 
		$customers = DB::table('account_master')->where('category','CUSTOMER')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','master_name')->get();
		return view('body.importdata.items')
					->withType('opn-balance')
					->withCustomers($customers)
					->withData($data);
	}

	public function importOpnBalanceSup() { 
		$data = array(); 
		$suppliers = DB::table('account_master')->where('category','SUPPLIER')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','master_name')->get();
		return view('body.importdata.items')
					->withType('opn-balance-sup')
					->withSuppliers($suppliers)
					->withData($data);
	}
	
	public function importCustVehicle() { 
		$data = array(); 
		return view('body.importdata.items')
					->withType('vehicle')
					->withData($data);
	}
	
	public function importJobmaster() { 
		$data = array(); 
		return view('body.importdata.items')
					->withType('jobmaster')
					->withData($data);
	}
	public function importJoborder() { 
		$data = array(); 
		return view('body.importdata.items')
					->withType('joborder')
					->withData($data);
	}
	
	//JAN25
	public function importAccountMaster() { 
		$data =$groups= array(); 
		$acctype = $this->category->accountType();
		$accategory = $this->category->activeAccategoryList();
		$category_id =''; //(Session::has('category_id'))?Session::get('category_id'):'';
		$actype_id =''; //(Session::has('actype_id'))?Session::get('actype_id'):'';
		if($actype_id)
			$accategory = $this->category->getCategorybyType($actype_id);
		
		if($category_id)
			$groups = $this->group->getGroupbyCategory($category_id);
		return view('body.importdata.items')
					->withType('accountmaster')
					->withAcctype($acctype)
					->withCategory($accategory)
					->withActypid($actype_id)
					->withCatid($category_id)
					->withGroups($groups)
					->withData($data);
	}
	
	//JAN25
	public function save() { //cost_avg
		
		if(Input::hasFile('import_file')){
			
			$path = Input::file('import_file')->getRealPath();
			$data = Excel::load($path, function($reader) { })->get();
			//echo '<pre>';print_r($data);exit;
			if(!empty($data) && $data->count()) {
				
				//EXCEL FORMAT ITEM:   Item Code|Description|Unit|Quantity|Rate|Sales Price|Item Class
				if(Input::get('type')=='items') {
					
					if($this->itemmaster->ImportItems($data))  //$this->itemmaster->ImportItemsUpdate($data)
						Session::flash('message', 'Items have been imported successfully.');
					else
						Session::flash('error', 'Something went wrong, Items import process is failed!');
					
					return redirect('importdata/items');
					
				}  else if(Input::get('type')=='tallyitems') {
				//EXCEL FORMAT ITEM:   Item Code|Description|Unit|Cost|TO Qty|QI Qty
				
				$vat = DB::table('vat_master')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('percentage')->first();
				$dtrow = DB::table('parameter1')->select('from_date')->first();
				
				//echo '<pre>';print_r($data);exit;								  
				foreach ($data as $row) {
				    
				    if($row->item_code!='') {
					//CHECK ITEM EXIST OR NOT
					$item = DB::table('itemmaster')->where( function ($query) use($row) {
														$query->where('item_code', '=', $row->item_code);
															  
												   })->select('id','description')->get();
												    
												    
				    }
				    
				    if(!$item) {
				       $item_id=$this->itemmaster->ImportItems($data);
				    }
				    
				        
				        
				   
				    if(isset($row->ti_quantity) && $row->ti_quantity!='') {
				        
				        $sti = DB::table('account_setting')->where('voucher_type_id', 21)->select('id','voucher_no','dr_account_master_id','cr_account_master_id')->first();
				        $itemcost = DB::table('itemmaster')->join('item_unit', 'item_unit.itemmaster_id', '=', 'itemmaster.id')
				                                            ->where('itemmaster.item_code', '=', $row->item_code)
				                                            ->select('itemmaster.id','itemmaster.description','item_unit.cost_avg','item_unit.unit_id')->first();
				        $item_id=$itemcost->id;
				        $description=$itemcost->description; 
				        $cost=$itemcost->cost_avg;
				        $itemunit=$itemcost->unit_id;
				        if(isset($row->unit)) {
							//GET UNIT ID
							$unit = DB::table('units')->where('unit_name', strtoupper($row->unit))->select('id')->first();
							//echo '<pre>';print_r($unit);exit;
							if(!$unit) { //IF UNIT NOT EXIST...
								if($row->unit!='')
									$unit_id = DB::table('units')->insertGetId(['unit_name' => strtoupper($row->unit),'description' => strtoupper($row->unit),'status' => 1]);
								else {
									$unit_id = 2; $row->unit = 'PCS';
								}
							} else
								$unit_id = $unit->id;
						} else
							$unit_id = $itemunit;
						 //echo '<pre>';print_r($unit_id);exit;	
				        $itemid[]=$item_id;
				        $itemname[]=($row->description!='')?$row->description:$description;
				        $qty[]=$row->ti_quantity;
				        $price[]=($row->cost !='')?$row->cost:$cost;
				        $units[]=$unit_id;
				        $dr_account_id[]='';
				        $oc_amount[]='';
				        
    				    if($sti) {
            				Input::merge(['voucher_id' => $sti->id]);
            				Input::merge(['curno' => $sti->voucher_no]);
            				Input::merge(['voucher_no' => $sti->voucher_no]);
            				Input::merge(['voucher_date' => '']);
            				Input::merge(['is_mfg' => 0]);
            				Input::merge(['account_dr' => $sti->dr_account_master_id]);
            				Input::merge(['account_cr' => $sti->cr_account_master_id]);
            				Input::merge(['item_id' => $itemid]);
            				Input::merge(['item_name' => $itemname]);
            				Input::merge(['unit_id' => $units]);
            				Input::merge(['quantity' => $qty]);
            				Input::merge(['cost' => $price]);
            				Input::merge(['dr_acnt_id' =>$dr_account_id]);
            				Input::merge(['oc_amount' =>$oc_amount]);
    			       }
			       //echo '<pre>';print_r(Input::all());exit;
			       
				    }
				}
				
				if(isset($sti) && $sti!='' ) {
				    $trin = $this->stock_transferin->create(Input::all());
				}
					foreach ($data as $row) {
				    if(isset($row->to_quantity) && $row->to_quantity!='') {
				        
				        $sto = DB::table('account_setting')->where('voucher_type_id', 22)->select('id','voucher_no','dr_account_master_id','cr_account_master_id')->first();
				        $itemcost = DB::table('itemmaster')->join('item_unit', 'item_unit.itemmaster_id', '=', 'itemmaster.id')
				                                            ->where('itemmaster.item_code', '=', $row->item_code)
				                                            ->select('itemmaster.id','itemmaster.description','item_unit.cost_avg','item_unit.unit_id')->first();
				                                           // echo '<pre>';print_r($itemcost);exit;
				        $item_id=$itemcost->id;
				        $description=$itemcost->description; 
				        $cost=$itemcost->cost_avg;
				        $itemunit=$itemcost->unit_id;
				        
				        if(isset($row->unit)) {
							//GET UNIT ID
							$unit = DB::table('units')->where('unit_name', strtoupper($row->unit))->select('id')->first();
							if(!$unit) { //IF UNIT NOT EXIST...
								if($row->unit!='')
									$unit_id = DB::table('units')->insertGetId(['unit_name' => strtoupper($row->unit),'description' => strtoupper($row->unit),'status' => 1]);
								else {
									$unit_id = 2; $row->unit = 'PCS';
								}
							} else
								$unit_id = $unit->id;
						} else
							$unit_id = $itemunit;
							
				        $itemidd[]=$item_id;
				        $itemnamee[]=($row->description !='')?$row->description:$description;
				        $qtyy[]=$row->to_quantity;
				        $pricee[]=($row->cost !='')?$row->cost:$cost;
				        $unitt[]=$unit_id;
				        $actcost[]='';
				    if($sto) {
				Input::merge(['voucher_id' => $sto->id]);
				Input::merge(['curno' => $sto->voucher_no]);
				Input::merge(['voucher_no' => $sto->voucher_no]);
				Input::merge(['voucher_date' => '']);
				Input::merge(['is_mfg' => 0]);
				Input::merge(['account_dr' => $sto->dr_account_master_id]);
				Input::merge(['account_cr' => $sto->cr_account_master_id]);
				Input::merge(['item_id' => $itemidd]);
				Input::merge(['item_name' => $itemnamee]);
				Input::merge(['unit_id' => $unitt]);
				Input::merge(['quantity' => $qtyy]);
				Input::merge(['cost' => $pricee]);
				Input::merge(['actcost' => $actcost]);
			       }
			       //echo '<pre>';print_r(Input::all());exit;
			       
				    
				    }
				}
				if(isset($sto) && $sto!='' ){
				$trout = $this->stock_transferout->create(Input::all());
				}
				Session::flash('message', 'Items have been uploaded successfully.');
					
					return redirect('importdata/tallyitems');
				
				}
				else if(Input::get('type')=='accounts') {
					
					 ################## CUSTOMER/SUPPLIER EXCEL IMPORT FORMAT:   Account Name|address_1|address_2|State|Phone|TRN No|Fax|Email  ######################
					if($this->accountmaster->ImportAccounts($data, Input::get('actype')))
						Session::flash('message', 'Accounts have been imported successfully.');
					else
						Session::flash('error', 'Something went wrong, Accounts import process is failed!');
					
					return redirect('importdata/accounts');
					
				} else if(Input::get('type')=='accountmaster') { //JAN25
					
					 ################## OTHER ACCOUNTS EXCEL IMPORT FORMAT:   Account Name | Type | Amount  ######################
					if($this->accountmaster->ImportAccountMaster($data, Input::all()))
						Session::flash('message', 'Accounts have been imported successfully.');
					else
						Session::flash('error', 'Something went wrong, Accounts import process is failed!');
					
					return redirect('importdata/accounts');
				
				} else if(Input::get('type')=='clstock') { //CONSIGNMENT LOCATION STOCK...
					$notfound = [];
					$defaultloc = DB::table('location')->where('is_default',1)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->where('is_conloc',0)->select('id')->first();
					$customerid = 1793; $custname = 'ENOC'; $no = 0;
					$noitm = $noloc = []; 
					foreach ($data as $dkey => $row) { //echo '<br/><pre>';print_r($row);grand_total
						//if($dkey!='product_name' && $dkey!='grand_total')
						$item = DB::table('itemmaster')->join('item_unit','item_unit.itemmaster_id','=','itemmaster.id')->where('itemmaster.item_code',$row->product_name)->where('item_unit.is_baseqty',1)->select('itemmaster.*','item_unit.unit_id')->first();
						if($item) {
							//INSERT AS OPENING STOCK IN DEFAULT LOCATION AND OQ LOG quantity
							DB::table('item_unit')->where('itemmaster_id',$item->id)->update(['opn_quantity' => DB::raw('opn_quantity + '.$row->grand_total),'cur_quantity' => DB::raw('opn_quantity + '.$row->grand_total)]);
							DB::table('item_log')->where('document_type','OQ')->where('item_id',$item->id)->update(['quantity'=>DB::raw('quantity + '.$row->grand_total),'cur_quantity'=>DB::raw('cur_quantity + '.$row->grand_total)]);
							DB::table('item_location')->where('item_id',$item->id)->where('location_id',$defaultloc->id)->update(['quantity'=>DB::raw('quantity + '.$row->grand_total),'opn_qty'=>DB::raw('opn_qty + '.$row->grand_total)]);
							$items[] = $item->id;
							$itemscode[] = $item->item_code;
							$itemsname[] = $item->description;
							$unitid[] = $item->unit_id;
							$itemsqty[] = $row->grand_total;
							$txcod[] = 'SR';
							$itemscost[] = $tinc[] = $hdu[] = $vat[] = $vtln[] = $vtamt[] = $vtds[] = $itmt = $lnt = 0;
							$pkng[] = 1;
							
							$no++;
							$locarr = $locqty = [];
							foreach($row as $key => $val) { 
								if($key!='product_name') {
								  if($key!='grand_total') {
									$loc = DB::table('location')->where('code',$key)->first();
									if($loc) {
										if($val!='') {
											$locarr[] = $loc->id;
											$locqty[] = $val;
										}
									} else { $noloc[]=$key;
										if($val!='') {
											//INSERT NEW CON LOCATION  AUTOPRO   QOC
											$lid = DB::table('location')->insertGetId(['code' => $key, 'name' => 'ENOC-'.$key,'is_default'=>0,'status'=>1,'is_conloc'=>1,'customer_id'=>$customerid]);
											$locarr[] = $lid;
											$locqty[] = $val;
											//...............ITEM LOCATION ADD........
											$itemsnw = DB::table('item_unit')->where('status',1)->where('is_baseqty',1)->where('deleted_at','0000-00-00 00:00:00')->select('itemmaster_id','unit_id')->get();
											if($itemsnw){
												foreach($itemsnw as $rw) {
													DB::table('item_location')->insert(['location_id'=>$lid,'item_id'=>$rw->itemmaster_id,'unit_id'=>$rw->unit_id,'status'=>1]);
												}
											}
										}
									}
								  }
								}
							}
							//echo '<pre>';print_r($locarr);exit;
							$itemsloc[] = implode(',',$locarr);
							$itemslocqty[] = implode(',',$locqty);
						} else {
							$noitm[] = $row->product_name;
						}
						$notfound[] = ['item' => $noitm, 'loc' => $noloc];
					}
					
					//DO POSTING...
					$vdata = DB::table('voucher_no')->where('voucher_type','CDO')->select('no')->first();
					Input::merge(['curno' => $vdata->no]);
					Input::merge(['default_location' => 0 ]);
					Input::merge(['voucher_no' => $vdata->no]);
					Input::merge(['reference_no' => '']);
					Input::merge(['voucher_date' => '']);
					Input::merge(['lpo_date' => '']);
					Input::merge(['customer_name' => $custname]);
					Input::merge(['customer_id' => $customerid]);
					Input::merge(['salesman' => '']);
					Input::merge(['salesman_id' => '']);
					Input::merge(['document_type' => 'QS']);
					Input::merge(['document_id' => '']);
					Input::merge(['description' => '']);
					Input::merge(['terms_id' => '']);
					Input::merge(['job_id' => '']);
					Input::merge(['location_id' => 1]);
					Input::merge(['total' => 0]);
					Input::merge(['total_fc' => 0]);
					Input::merge(['discount' => 0]);
					Input::merge(['discount_fc' => 0]);
					Input::merge(['subtotal' => 0]);
					Input::merge(['subtotal_fc' => 0]);
					Input::merge(['vat' => 0]);
					Input::merge(['vat_fc' => '']);
					Input::merge(['net_amount_hid' => 0]);
					Input::merge(['net_amount' => 0 ]);
					Input::merge(['net_amount_fc' => '']);
					Input::merge(['footer_id' => '']);
					Input::merge(['footer' => '']);
					
					Input::merge(['title' => []]);
					Input::merge(['desc' => []]);
					Input::merge(['num' => $no]);
					Input::merge(['clocid' => '']);
					Input::merge(['clocqty' => '']);
					Input::merge(['tblConLoc_length' => '']);
					Input::merge(['lcid' => $locarr]);
					Input::merge(['qtyreq' => $locqty]);
					Input::merge(['posts_length' => '']);
					
					Input::merge(['item_id' => $items]);
					Input::merge(['item_code' => $itemscode]);
					Input::merge(['item_name' => $itemsname]);
					Input::merge(['unit_id' => $unitid]);
					Input::merge(['quantity' => $itemsqty]);
					Input::merge(['cost' => $itemscost]);
					Input::merge(['tax_code' => $txcod]);
					Input::merge(['tax_include' => $tinc]);
					Input::merge(['hidunit' => $hdu]);
					Input::merge(['packing' => $pkng]);
					Input::merge(['vatdiv' => $vat]);
					Input::merge(['line_vat' => $vtln]);
					Input::merge(['vatline_amt' => $vtamt]);
					Input::merge(['line_discount' => $vtds]);
					Input::merge(['item_total' => $itmt]);
					Input::merge(['line_total' => $lnt]);
					Input::merge(['conloc_id' => $itemsloc]);
					Input::merge(['conloc_qty' => $itemsqty]); //itemslocqty exit;
					
					//echo '<pre>';print_r($notfound);
					$id = $this->customerdo->create(Input::all());
					
					
				} else if(Input::get('type')=='opn-balance' || Input::get('type')=='opn-balance-sup') { 
					########## EXCEL Invoice No | Invoice Date | Description | Type | Amount #######
					 $amount_dr = $amount_cr = 0; $OBDupdate = false;
					foreach ($data as $dkey => $row) { 
						$trtype = $row->type; $description = $row->description;
						if($row->invoice_no!='') {
						    if($trtype=='Dr')
							    $amount_dr += (float)$row->amount;
							if($trtype=='Cr')
							    $amount_cr += (float)$row->amount;
						    $row->invoice_date.' '.$invoice_date = ($row->invoice_date!='')?date('Y-m-d',strtotime( $row->invoice_date )):date('Y-m-d');
							$openingBalance = new OpeningBalanceTr;
							$openingBalance->tr_type = $trtype;
							$openingBalance->tr_date = $invoice_date;
							$openingBalance->reference_no = $row->invoice_no;
							$openingBalance->description = ($row->description=='')?$row->invoice_no:'';
							$openingBalance->amount = (float)$row->amount;
							$openingBalance->account_master_id = Input::get('account_id');
							$openingBalance->cheque_no = '';
							$openingBalance->cheque_date = '';
							$openingBalance->bank_id = '';
							$openingBalance->frmaccount_id = '';
							$openingBalance->currency_id = '';
							$openingBalance->rate = '';
							$openingBalance->fc_amount = (float)$row->amount;
							$openingBalance->loc_proj = $row->location_project;
							$openingBalance->eqp_type = $row->equipment_type;
							$openingBalance->lpo_no = $row->lpo_no;
							$openingBalance->status = 1; 
							$openingBalance->save();
							$OBDupdate = true;
							$refno = $row->invoice_no;
							DB::table('account_transaction')
									->insert([  'voucher_type' 		=> 'OBD',
												'voucher_type_id'   => $openingBalance->id,
												'account_master_id' => Input::get('account_id'),
												'transaction_type'  => $trtype,
												'amount'   			=> (float)$row->amount,
												'status' 			=> 1,
												'created_at' 		=> date('Y-m-d H:i:s'),
												'created_by' 		=> Auth::User()->id,
												'description' 		=> $row->description,
												'reference'			=> $row->invoice_no, 
												'invoice_date'		=> $invoice_date,
												'fc_amount'			=> $row->amount,
												'is_fc'				=> 0,
												'department_id'		=> '',
												'loc_proj'			=> '',//$row->location_project,
												'eqp_type'			=> '',//$row->equipment_type,
												'lpo_no'			=> ''//$row->lpo_no
											]);
						}
					}
					
					$amount = $amount_dr - $amount_cr;
					$trtype = ($amount > 0)?'Dr':'Cr';
					if($OBDupdate) {
						DB::table('account_transaction')
							->where('voucher_type','OB')
							->where('voucher_type_id',Input::get('account_id'))
							->where('account_master_id',Input::get('account_id'))
							->update([  'transaction_type'  => $trtype,
										'amount'   			=> $amount,
										'description' 		=> $description,
										'reference'			=> $refno,
										'invoice_date'		=> date('Y').'-01-01',
										'fc_amount'			=> $amount,
										'loc_proj'			=> '',//$row->location_project,
										'eqp_type'			=> '',//$row->equipment_type,
										'lpo_no'			=> ''//$row->lpo_no
									]);
									
						DB::table('account_master')->where('id',Input::get('account_id'))->update(['op_balance' => $amount]);
					}
					
					Session::flash('message', 'Opening Transactions have been updated successfully.');
					
					return redirect('importdata/opn-balance');
					
				} else if(Input::get('type')=='vehicle') { //VEHICLE IMPORT....
				    
				    foreach ($data as $dkey => $row) { 
				        
				        $cust = DB::table('account_master')->where('account_id', $row->customer_id)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id')->first();
				        if($cust) {
				            
				            $veh = DB::table('vehicle')->where('reg_no',$row->reg_no)->where('deleted_at','0000-00-00 00:00:00')->first();
				            if(!$veh) {
    				            DB::table('vehicle')
    				                    ->insert([
    				                        'customer_id' => $cust->id,
    				                        'name' => $row->make.' '.$row->model,
    				                        'reg_no' => $row->reg_no,
    				                        'make' => $row->make,
    				                        'color' => $row->color,
    				                        'engine_no' => $row->engine_no,
    				                        'chasis_no' => $row->chassis_no,
    				                        'model' => $row->model,
    				                        'year' =>$row->year,
    				                        'plate_type' => $row->plate_type
    				                    ]);
				            }
				            
				        }  else {
				            
				            Session::flash('error', 'Customer not found!');
					        return redirect('importdata/cust-vehicle');
				        }
				        
				    }
				    
				    Session::flash('message', 'Customer vehicles have been uploaded successfully.');
					
					return redirect('importdata/cust-vehicle');
					
				} else if(Input::get('type')=='jobmaster') { //JOBMASTER IMPORT....
				    
				    foreach ($data as $dkey => $row) {    
				        
				         DB::table('jobmaster')
							->insert([
								'code'  => $row->jobcode,
								'name'  => 'Job - '.$row->jobcode,
								'customer_id'	=> $customer_id,
								'start_date' 	=> date('Y-m-d'),
								'end_date' 	=> date('Y-m-d'),
								'status'		=> 1,
								//'vehicle_id'	=> isset($attributes['vehicle_id'])?$attributes['vehicle_id']:'',
								'date' 	=> date('Y-m-d')
							]);
				    }
				}
				
				else if(Input::get('type')=='joborder') { //JOBORDER IMPORT....
				    
				    foreach ($data as $dkey => $row) {  
				        $cust = DB::table('account_master')->where('account_id', $row->customer)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id')->first();
				        //echo '<pre>';print_r($cust);exit;
				        if($cust) {
				         $jobid=DB::table('jobmaster')
							->insertGetId([
								'code'  => $row->job_no,
								'name'  => $row->job_description,
								'customer_id'	=> $cust->id,
								//'start_date' 	=> date('Y-m-d'),
							//	'end_date' 	=> date('Y-m-d'),
								'status'		=> 1,
								//'vehicle_id'	=> isset($attributes['vehicle_id'])?$attributes['vehicle_id']:'',
								'date' 	=> date('Y-m-d',strtotime( $row->job_date ))
							]);
				   
				   
				    
				    
				         DB::table('sales_order')
							->insert([
								'voucher_no'  => $row->job_no,
								'description'  => 'Job - '.$row->job_description,
								'customer_id'	=> $cust->id,
							'voucher_date' 	=> date('Y-m-d',strtotime( $row->job_date )),
							'reference_no'  => $row->lpo_no,
								'lpo_date' 	=> date('Y-m-d',strtotime( $row->lpo_date )),
								'job_id'   =>$jobid,
								'status'		=> 1,
								'is_rental'   =>2
								//'vehicle_id'	=> isset($attributes['vehicle_id'])?$attributes['vehicle_id']:'',
								
							]);
				        }
				        
				        
				        
				        
				    }
				    
				    Session::flash('message', 'Job Orders have been uploaded successfully.');
					
					return redirect('importdata/joborder');
				}
			}
		}
		
	}
	
}
