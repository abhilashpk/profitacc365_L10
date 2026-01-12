<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Repositories\Parameter1\Parameter1Interface;
use App\Repositories\Parameter2\Parameter2Interface;
use App\Repositories\Parameter4\Parameter4Interface;
use App\Repositories\Currency\CurrencyInterface;
use App\Repositories\AccountMaster\AccountMasterInterface;

use App\Http\Requests;
use Session;
use Redirect;
use DB;
use App;
use Auth;

class SysparameterController extends Controller
{
    protected $parameter1;
	protected $currency;
	protected $parameter2;
	protected $parameter4;
	protected $accountmaster;
	
	public function __construct(Parameter1Interface $parameter1, CurrencyInterface $currency, Parameter2Interface $parameter2,AccountMasterInterface $accountmaster,Parameter4Interface $parameter4) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->parameter1 = $parameter1;
		$this->currency = $currency;
		$this->parameter2 = $parameter2;
		$this->parameter4 = $parameter4;
		$this->accountmaster = $accountmaster;
		$this->middleware('auth');
		
	}
	
	public function index() { 
		$data = array();
		$parameter1 = $this->parameter1->getParameter1();
		$currency = $this->currency->activeCurrencyList();
		$parameter2 = $this->parameter2->getParameter2();
		$parameter4 = $this->parameter4->getParameter4();
		$parameter3 = $this->makeTree( DB::table('parameter3')->get() ); //echo '<pre>';print_r($parameter1);exit;
		$parameter5 = DB::table('design_view')->where('id',1)->first();
		$locations = DB::table('location')->where('is_default',0)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		$alllocations = DB::table('location')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		$accounts = $this->accountmaster->activeAccountList();
		$files = Storage::disk('reports')->files(); //echo '<pre>';print_r($files);exit;
		$dftLoc = DB::table('default_loc')->first();
		return view('body.sysparameter.index')
					->withParameter1($parameter1)
					->withCurrency($currency)
					->withParameter2($parameter2)
					->withParameter3($parameter3)
					->withParameter4($parameter4)
					->withParameter5($parameter5)
					->withLocations($locations)
					->withAlllocations($alllocations)
					->withAccounts($accounts)
					->withFiles($files)
					->withDftloc($dftLoc)
					->withData($data);
	}
	
	protected function makeTree($result)
	{
		$childs = array();
		foreach($result as $item)
			$childs[$item->location_id] = $item->account_id;;
		
		return $childs;
	}
	
	public function para1_update(Request $request,$id)
	{ 
		$this->parameter1->update($id, $request->all());
		Session::flash('message', 'Sysparameter details updated successfully');
		return redirect('sysparameter');
	}
	
	public function para2_update(Request $request)
	{ 
		//echo '<pre>';print_r($request->all());exit;
		$this->parameter2->update(null, $request->all());
		
		 #Update Quantity by Delivery Order(Sales).....
	    	$row = DB::table('parameter2')->where('keyname', 'mod_do_qty_update')->where('status',1)->select('is_active')->first();
			if($row->is_active==1) {
				$sdos = DB::table('customer_do')
							->join('customer_do_item','customer_do_item.customer_do_id','=','customer_do.id')
							->where('customer_do.status',1)->where('customer_do.deleted_at','0000-00-00 00:00:00')
							->where('customer_do_item.status',1)->where('customer_do_item.deleted_at','0000-00-00 00:00:00')
							->select('customer_do.voucher_date','customer_do_item.customer_do_id','customer_do_item.item_id','customer_do_item.is_transfer',
									 'customer_do_item.unit_id','customer_do_item.quantity','customer_do_item.unit_price','customer_do_item.id','customer_do_item.balance_quantity')
							->get();
				//echo '<pre>';print_r($sdos);exit;			
				foreach($sdos as $sdo) {
				    if($sdo->is_transfer!=1) {
				        $quantity = $sdo->quantity - $sdo->balance_quantity;
    					$chklog = DB::table('item_log')->where('document_type','CDO')->where('document_id', $sdo->customer_do_id)->where('item_row_id',$sdo->id)->first();
    					if($chklog) {
    						DB::table('item_log')
    						      ->where('id', $chklog->id)
    						      ->update([
        							 'item_id' 	  => $sdo->item_id,
        							 'unit_id'    => $sdo->unit_id,
        							 'quantity'   => $quantity,
        							 'unit_cost'  => $sdo->unit_price,
        							 'trtype'	  => 0,
        							 'cur_quantity' => $sdo->quantity,
        							 'packing' => 1,
        							 'status'     => 1,
        							 'voucher_date' => $sdo->voucher_date,
        							 'sale_reference' => $sdo->quantity,
        							 'deleted_at' => '0000-00-00 00:00:00'
        							]);
    					} else {
    						
    						DB::table('item_log')->insert([
    							 'document_type' => 'CDO',
    							 'document_id'   => $sdo->customer_do_id,
    							 'item_id' 	  => $sdo->item_id,
    							 'unit_id'    => $sdo->unit_id,
    							 'quantity'   => $sdo->quantity,
    							 'unit_cost'  => $sdo->unit_price,
    							 'trtype'	  => 0,
    							 'cur_quantity' => $sdo->quantity,
    							 'packing' => 1,
    							 'status'     => 1,
    							 'created_at' => date('Y-m-d H:i:s'),
    							 'created_by' => Auth::User()->id,
    							 'voucher_date' => $sdo->voucher_date,
    							 'sale_reference' => $sdo->quantity,
    							 'item_row_id'	=> $sdo->id
    							]);
    							
    					}
				    }
				}
				
			} else {
				DB::table('item_log')->where('document_type','CDO')->update(['status' => 0, 'deleted_at' => date('Y-m-d H:i:s')]);
			}
		#Update Quantity by Delivery Order(Sales) ends...
		
		Session::flash('message', 'Sysparameter details updated successfully');
		return redirect('sysparameter');
	}
	
	public function para3_update(Request $request)
	{ 
		//echo '<pre>';print_r($request->all());
		$account = $request->get('account');
		$location = $request->get('location');
		foreach($location as $key => $row) { //echo $row;exit;
			$loc = DB::table('parameter3')->where('location_id', $row)->get();
			if($loc)
				DB::table('parameter3')->where('location_id', $row)->update(['account_id' => $account[$key]]);
			else
				DB::table('parameter3')->insert(['location_id' => $row, 'account_id' => $account[$key]]);
			
		}

		DB::table('default_loc')->where('id',$request->get('dftlocid'))->update(['pur_loc' => $request->get('loc_purchase'), 'sales_loc' => $request->get('loc_sales'), 'mfg_loc' => $request->get('loc_mfg')]); 
		
	   

		Session::flash('message', 'Sysparameter details updated successfully');
		return redirect('sysparameter');
	}
	
	public function para4_update(Request $request,$id)
	{ 
		$this->parameter4->update($id, $request->all());
		Session::flash('message', 'Sysparameter details updated successfully');
		return redirect('sysparameter');
	}
	
	public function para5_update(Request $request)
	{ 
		DB::table('design_view')->where('id',1)->update(['view_name' => $request->get('file_name')]);
		Session::flash('message', 'Sysparameter details updated successfully');
		return redirect('sysparameter');
	}
}

