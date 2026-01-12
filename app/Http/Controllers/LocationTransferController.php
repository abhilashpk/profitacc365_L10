<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Location\LocationInterface;
use App\Repositories\VoucherNo\VoucherNoInterface;
use App\Repositories\LocationTransfer\LocationTransferInterface;

use App\Http\Requests;
use Notification;
use Session;
use DB;
use App;

class LocationTransferController extends Controller
{
   protected $location;
   protected $voucherno;
   protected $location_transfer;
	
	public function __construct(LocationInterface $location, VoucherNoInterface $voucherno, LocationTransferInterface $location_transfer) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->location = $location;
		$this->voucherno = $voucherno;
		$this->location_transfer = $location_transfer;
		$this->middleware('auth');

	}

	
	public function index()
	{
		$data = array();
		$locationtrans = $this->location_transfer->locationTransList();
		return view('body.locationtransfer.index')
					->withLocationtrans($locationtrans)
					->withData($data);
	}
	
	public function add() {

		$data = array();
		$location = $this->location->locationListAll();
		$res = $this->voucherno->getVoucherNo('LT');
		$vno = $res->no;
		$lastid = DB::table('location_transfer')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->orderBy('id','DESC')->select('id')->first();
		return view('body.locationtransfer.add')
					->withLocation($location)
					->withVoucherno($vno)
					->withPrintid($lastid)
					->withData($data);
	}
	
	public function save(Request $request) {
		
		if( $this->location_transfer->create($request->all()) )
			Session::flash('message', 'Location transfered successfully.');
		else
			Session::flash('error', 'Something went wrong, Location failed to transfer!');
		
		return redirect('location_transfer/add');
	}
	
	public function checkRefNo(Request $request) {

		$check = $this->location_transfer->check_reference_no($request->get('reference_no'), $request->get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
	
	public function destroy($id)
	{
		$this->location_transfer->delete($id);
		Session::flash('message', 'Location transfered deleted successfully.');
			
		return redirect('location_transfer');
	}
	
	public function edit($id) { 

		$data = array();
		$location = $this->location->locationList();
		$res = $this->voucherno->getVoucherNo('LT');
		$orderrow = $this->location_transfer->findRow($id);
		$orditems = $this->location_transfer->getItems($id); // echo '<pre>';print_r($orditems);exit;
		return view('body.locationtransfer.edit')
					->withLocation($location)
					->withOrderrow($orderrow)
					->withOrditems($orditems)
					->withData($data);
					
		
	}
	
	public function update($id, Request $request)
	{
		//echo '<pre>';print_r($request->all());exit;
		if($this->location_transfer->update($id, $request->all()))
			Session::flash('message', 'Location transfer updated successfully');
		else
			Session::flash('error', 'Something went wrong, Location failed to update!');
		
		return redirect('location_transfer');
	}
	
	public function getPrint($id)
	{
		$attributes['document_id'] = $id;
		$result = $this->location_transfer->getDoc($attributes); //echo '<pre>';print_r($result);exit;
		$titles = ['main_head' => 'Location Transfer','subhead' => 'Location Transfer'];
		return view('body.locationtransfer.print')
					->withDetails($result['details'])
					->withTitles($titles)
					->withItems($result['items']);
		
	}
}

