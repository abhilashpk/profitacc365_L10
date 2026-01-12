<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Http\Requests;
use Session;
use Response;
use Input;
use Excel;
use App;
use DB;
use Auth;

class RentalReportController extends Controller
{
	
	public function __construct() {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->middleware('auth');
	}
	
	public function index() {
		
		$vehicle = DB::table('itemmaster')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','description')->get();
		$custsup = DB::table('account_master')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->whereIn('category',['CUSTOMER','SUPPLIER'])
				->select('id','master_name')->get();
		return view('body.rentalreport.index')->withVehicle($vehicle)->withCustsup($custsup);
	}
	
	
	private function groupItem($result) {
		
		$childs = array();
		foreach($result as $item)
			$childs[$item->item_id][] = $item;
		
		return $childs;
	}
	
	public function searchReport(Request $request) {
		
		$date_from = date('Y-m-d',strtotime($request->get('date_from')));
		$date_to = date('Y-m-d',strtotime($request->get('date_to')));
		
		$query1 = DB::table('rental_itemlog')
							 ->join('purchase_rental AS PR','PR.id','=','rental_itemlog.doc_id')
							 ->join('account_master AS AM','AM.id','=','PR.supplier_id')
							 ->join('itemmaster AS IM','IM.id','=','rental_itemlog.item_id')
							 ->where('rental_itemlog.doc_type','=','PIR')
							 ->where('rental_itemlog.deleted_at',null)
							 ->whereBetween('rental_itemlog.voucher_date', [$date_from, $date_to]);
							 
		if($request->get('search_type')!='')
			$query1->where('rental_itemlog.trtype',$request->get('search_type'));
		
		if($request->get('account')!='')
			$query1->where('PR.supplier_id',$request->get('account'));
		
		if($request->get('vehicle')!='')
			$query1->where('rental_itemlog.item_id',$request->get('vehicle'));
		
		$query1->select('rental_itemlog.*','AM.master_name','IM.item_code','IM.description','PR.voucher_no','PR.voucher_date AS vdate');
		
							 
		$query2 = DB::table('rental_itemlog')
							 ->join('rental_sales AS SR','SR.id','=','rental_itemlog.doc_id')
							 ->join('account_master AS AM','AM.id','=','SR.customer_id')
							 ->join('itemmaster AS IM','IM.id','=','rental_itemlog.item_id')
							 ->where('rental_itemlog.doc_type','=','SIR')
							 ->where('rental_itemlog.deleted_at',null)
							 ->whereBetween('rental_itemlog.voucher_date', [$date_from, $date_to]);
							 
		if($request->get('search_type')!='')
			$query2->where('rental_itemlog.trtype',$request->get('search_type'));
		
		if($request->get('account')!='')
			$query2->where('SR.customer_id',$request->get('account'));
		
		if($request->get('vehicle')!='')
			$query2->where('rental_itemlog.item_id',$request->get('vehicle'));
		
		$query2->select('rental_itemlog.*','AM.master_name','IM.item_code','IM.description','SR.voucher_no','SR.voucher_date AS vdate');
							 
		
							 
		$result = $this->groupItem( $query1->union($query2)->orderBy('vdate')->get() );
		
		//echo '<pre>';print_r($result);exit;  
		return view('body.rentalreport.report')->withReport($result)->withFromdate($date_from)->withTodate($date_to);
	}
	
}
