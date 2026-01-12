<?php
namespace App\Http\Controllers;
use App\Repositories\SalesInvoice\SalesInvoiceInterface;
use Illuminate\Http\Request;

use App\Http\Requests;
use Session;
use Response;
use DB;
use App;

class VehicleController extends Controller
{
    protected $vehicles;
	protected $sales_invoice;
	public function __construct() {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->middleware('auth');
		
	}
	
	public function index($n=null) {
		$data = array();
		$vehicles = DB::table('vehicle')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		return view('body.vehicle.index')
					->withVehicles($vehicles)
					->withData($data);
	}
	
	public function add() {

		$data = array();
		return view('body.vehicle.add')
					->withData($data);
	}
	
	public function save(Request $request) {
		try {
			DB::table('vehicle')
				->insert([
					'name' => $request->get('vehicle_name'),
					'reg_no' => $request->get('reg_no'),
					'make' => $request->get('make'),
					'model' => $request->get('model'),
					'color' => $request->get('color'),
					'engine_no' => $request->get('engine_no'),
					'chasis_no' => $request->get('chasis_no'),
					'status' => 1,
					'customer_id' => $request->get('customer_id'),
					'plate_type' => $request->get('plate_type'),
					'issue_plate' => $request->get('issue_plate'),
					'code_plate' => $request->get('code_plate'),
					'color_code' => $request->get('color_code')
				]);
			Session::flash('message', 'Vehicle added successfully.');
			return redirect('vehicle/add');
		} catch(ValidationException $e) { 
			return Redirect::to('vehicle/add')->withErrors($e->getErrors());
		}
	}
	
	public function edit($id) { 

		$data = array();
		$vehrow = DB::table('vehicle')
						->leftjoin('account_master AS AM', function($join) {
							$join->on('AM.id', '=', 'vehicle.customer_id');
						})
						->where('vehicle.id',$id)->select('vehicle.*','AM.master_name AS customer')->first();
						
		return view('body.vehicle.edit')
					->withVehrow($vehrow)
					->withData($data);
	}
	
	public function update($id,Request $request)
	{
		DB::table('vehicle')->where('id',$id)
				->update([
					'name' => $request->get('vehicle_name'),
					'reg_no' => $request->get('reg_no'),
					'make' => $request->get('make'),
					'model' => $request->get('model'),
					'color' => $request->get('color'),
					'engine_no' => $request->get('engine_no'),
					'chasis_no' => $request->get('chasis_no'),
					'customer_id' => $request->get('customer_id'),
					'issue_plate' => $request->get('issue_plate'),
					'issue_plate' => $request->get('issue_plate'),
					'code_plate' => $request->get('code_plate'),
					'color_code' => $request->get('color_code')
				]);
		Session::flash('message', 'Vehicle updated successfully');
		return redirect('vehicle');
	}
	
	public function destroy($id)
	{
		DB::table('vehicle')->where('id',$id)->update(['status' => 0, 'deleted_at' => date('Y-m-d H:i:s')]);
		Session::flash('message', 'Vehicle deleted successfully.');
		return redirect('vehicle');
	}
	public function getEnquiry() 
	{
		$data = array();
		$vehrow = DB::table('vehicle')
						->join('account_master AS AM', function($join) {
							$join->on('AM.id', '=', 'vehicle.customer_id');
						})
						->where('vehicle.deleted_at','0000-00-00 00:00-00')->select('vehicle.*','AM.master_name AS customer')->get();
						
		return view('body.vehicle.vehenquiry')
					->withVehrow($vehrow)
					->withData($data);
	
				

	}
	public function getvehicleHistory($vehicle_id)
	{
        $data = array();
		$items = 	DB::table('sales_invoice')
		->join('sales_invoice_item AS poi', function($join) {
			$join->on('poi.sales_invoice_id','=','sales_invoice.id');
		} )
	  ->join('units AS u', function($join){
		  $join->on('u.id','=','poi.unit_id');
	  }) 
	  ->join('itemmaster AS im', function($join){
		  $join->on('im.id','=','poi.item_id');
	  })->where('poi.status',1)
	  ->where('sales_invoice.vehicle_id',$vehicle_id)->where('sales_invoice.status',1)
	  ->select('poi.*','u.unit_name','im.item_code','sales_invoice.voucher_date','sales_invoice.voucher_no','sales_invoice.kilometer')->get();
	return view('body.vehicle.vehiclehistory')
					->withItems($items)
					->withData($data);
	}
	public function checkregno(Request $request) {

		/* if($request->get('id') != '')
			$check = DB::table('vehicle')->where('reg_no',$request->get('reg_no'))->where('id', '!=', $request->get('id'))->count();
		else
			$check = DB::table('vehicle')->where('reg_no',$request->get('reg_no'))->count(); */
		if($request->get('id') != '')
			$check = DB::table('vehicle')->where('chasis_no',$request->get('chasis_no'))->where('id', '!=', $request->get('id'))->count();
		else
			$check = DB::table('vehicle')->where('chasis_no',$request->get('chasis_no'))->count();
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
	
	
}

