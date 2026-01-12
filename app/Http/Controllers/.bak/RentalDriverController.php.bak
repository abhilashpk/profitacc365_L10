<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Input;
use Session;
use Response;
use DB;
use App;

class RentalDriverController extends Controller
{
    protected $cargo_vehicle;
	
	public function __construct() {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->middleware('auth');
		
	}
	
	public function index() {
		$data = array();
		$rvtype = DB:: table('rental_driver')
		                ->where('rental_driver.deleted_at',null)->get();
						//echo '<pre>';print_r($cvtype);exit;
		return view('body.rentaldriver.index')
					->withRvtype($rvtype)
					->withData($data);
	}
	
	public function add() {

		$data = array();
		return view('body.rentaldriver.add')
					->withData($data);
	}
	
	 public function save() {
		try {
			DB::table('rental_driver')
				->insert([
					'code' => Input::get('code'),
					'driver_name' => Input::get('dname'),
					'mobile1' =>Input::get('mb1'),
					'mobile2' =>Input::get('mb2'),
					'driver_type' =>Input::get('driver_type'),
					'account_id' => (Input::get('driver_type')=='supplier')?Input::get('supplier_id'):Input::get('customer_id')
				]);
				
			Session::flash('message', 'Driver added successfully.');
			return redirect('rental_driver/add');
		} catch(ValidationException $e) { 
			return Redirect::to('rental_driver/add')->withErrors($e->getErrors());
		}
	}  

	
	
	public function edit($id) { 

		$rvedit = DB::table('rental_driver')->where('rental_driver.id',$id)
						->join('account_master','account_master.id','=','rental_driver.account_id')
						->select('rental_driver.*','account_master.master_name')
						->first();
		//echo '<pre>';print_r($rvedit);exit;
						
		return view('body.rentaldriver.edit')
					->withRvedit($rvedit);
	}



	public function update($id)
	{
		DB::table('rental_driver')->where('id',$id)
				->update([
					'code' => Input::get('code'),
					'driver_name' => Input::get('dname'),
					'mobile1' =>Input::get('mb1'),
					'mobile2' =>Input::get('mb2'),
					'driver_type' =>Input::get('driver_type'),
					'account_id' => (Input::get('driver_type')=='supplier')?Input::get('supplier_id'):Input::get('customer_id')
				]);
		Session::flash('message', 'Driver updated successfully');
		return redirect('rental_driver');
	}



	public function destroy($id)
	{
		DB::table('rental_driver')->where('id',$id)->update(['deleted_at' => date('Y-m-d H:i:s')]);
		Session::flash('message', 'Driver deleted successfully.');
		return redirect('rental_driver');
	}
	
	
	
	public function checkmobnumber1() {

		$check = $this->check_mobile_number1(Input::get('mb1'), Input::get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
					));
	}
	public function check_mobile_number1($number1, $id = null) {
		
		if($id){
		$query=DB::table('rental_driver')
		->where('mobile1',$number1)->where('id', '!=', $id)->where('rental_driver.deleted_at',null)->count();
		return $query;
		}
		else{
		$query=DB::table('rental_driver')
		->where('mobile1',$number1)->where('rental_driver.deleted_at',null)->count();
		return $query ;
		}
	}

	public function checkmobnumber2() {

		$check = $this->check_mobile_number2(Input::get('mb2'), Input::get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
					));
	}
	public function check_mobile_number2($number2, $id = null) {
		
		if($id){
		$query=DB::table('rental_driver')
		->where('mobile2',$number2)->where('id', '!=', $id)->where('rental_driver.deleted_at',null)->count();
		return $query;
		}
		else{
		$query=DB::table('rental_driver')
		->where('mobile2',$number2)->where('rental_driver.deleted_at',null)->count();
		return $query ;
		}
	}
}
