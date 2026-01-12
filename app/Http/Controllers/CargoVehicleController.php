<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Input;
use Session;
use Response;
use DB;
use App;

class CargoVehicleController extends Controller
{
    protected $cargo_vehicle;
	
	public function __construct() {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->middleware('auth');
		
	}
	
	public function index() {
		$data = array();
		$cvtype = DB:: table('cargo_vehicle')
		                ->where('cargo_vehicle.deleted_at',null)->orderBy('cargo_vehicle.vehicle_name','ASC')->get();
						//echo '<pre>';print_r($cvtype);exit;
		return view('body.cargovehicle.index')
					->withCvtype($cvtype)
					->withData($data);
	}
	
	public function add() {

		$data = array();
		return view('body.cargovehicle.add')
					->withData($data);
	}
	
	 public function save() {
		try {
			DB::table('cargo_vehicle')
				->insert([
					'vehicle_no' => Input::get('vnumber'),
					'vehicle_name' => Input::get('vname'),
					'driver_name' => Input::get('dname'),
					'company'     => Input::get('company'),
					'driver_id'     => Input::get('idno'),
					'passport_no'     => Input::get('passport'),
					'mobile_uae' =>Input::get('mbuae'),
					'mobile_ksa' =>Input::get('mbksa'),
					'watsapp' =>Input::get('watsapp'),
					'expiry_date'=>date('Y-m-d', strtotime(Input::get('expiry_date'))),
				]);
				
			Session::flash('message', 'Vehicle added successfully.');
			return redirect('cargo_vehicle/add');
		} catch(ValidationException $e) { 
			return Redirect::to('cargo_vehicle/add')->withErrors($e->getErrors());
		}
	}  

	
	
	public function edit($id) { 

		$cvedit = DB::table('cargo_vehicle')->where('id',$id)->first();
		//echo '<pre>';print_r($cvedit);exit;
						
		return view('body.cargovehicle.edit')
					->withCvedit($cvedit);
	}



	public function update($id)
	{
		DB::table('cargo_vehicle')->where('id',$id)
				->update([
					'vehicle_no' => Input::get('vnumber'),
					'vehicle_name' => Input::get('vname'),
					'driver_name' => Input::get('dname'),
					'company'     => Input::get('company'),
					'driver_id'     => Input::get('idno'),
					'passport_no'     => Input::get('passport'),
					'mobile_uae' =>Input::get('mbuae'),
					'mobile_ksa' =>Input::get('mbksa'),
					'watsapp' =>Input::get('watsapp'),
					'expiry_date'=>date('Y-m-d', strtotime(Input::get('expiry_date'))),
				]);
		Session::flash('message', 'vehicle updated successfully');
		return redirect('cargo_vehicle');
	}



	public function destroy($id)
	{
		DB::table('cargo_vehicle')->where('id',$id)->update(['deleted_at' => date('Y-m-d H:i:s')]);
		Session::flash('message', 'Vehicle deleted successfully.');
		return redirect('cargo_vehicle');
	}
	
	public function checknumber() {

		$check = $this->check_vehicle_number(Input::get('vnumber'), Input::get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
					));
	}
	public function check_vehicle_number($number, $id = null) {
		
		if($id){
		$query=DB::table('cargo_vehicle')
		->where('vehicle_no',$number)->where('id', '!=', $id)->where('cargo_vehicle.deleted_at',null)->count();
		return $query;
		}
		else{
		$query=DB::table('cargo_vehicle')
		->where('vehicle_no',$number)->where('cargo_vehicle.deleted_at',null)->count();
		return $query ;
		}
	}
	
	public function ajaxSave(Request $request) {
		
		$check1 = DB::table('cargo_vehicle')->where('vehicle_no', trim($request->get('vno')))->where('deleted_at',null)->count();
		if(($check1 > 0))
			return 0;
		
			
		$id = DB::table('cargo_vehicle')
				->insertGetId([
					'vehicle_name' => trim($request->get('vtype')),
					'vehicle_no' => trim($request->get('vno')),
					'driver_name' => trim($request->get('dname')),
					'mobile_uae'	=> trim($request->get('mobu')),
					'mobile_ksa'	=> trim($request->get('mobk')),
					'expiry_date'	=> trim(date('Y-m-d',strtotime($request->get('edate'))))
				]);
			
		return $id;
			
	}
	
}

