<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use App\Repositories\Unit\UnitInterface;

use App\Http\Requests;
use Input;
use Session;
use Response;
use App;
use DB;

class RentalCustomerDriverController extends Controller
{
    protected $units;
	
	public function __construct() {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->middleware('auth');
	}
	
	public function index() {
		$data = array();
		$customer =DB:: table('rental_customerdriver')
		->join('account_master AS AM','AM.id','=','rental_customerdriver.customer_id')
		->where('AM.category','CUSTOMER')
		->where('rental_customerdriver.deleted_by',null)
		->select('AM.master_name','rental_customerdriver.id','rental_customerdriver.customer_id','rental_customerdriver.driver_id')->get();
		//echo '<pre>';print_r($supplier);exit;
		$rcdriver=null;
		foreach($customer as $cd) {
		$cdriver=unserialize($cd->driver_id);
		$res = DB::table('rental_driver')->whereIn('id',$cdriver)->where('driver_type','customer')->where('deleted_at',null)->select('driver_name')->get();
		$drivers = $this->sortDriver($res);
		$rcdriver[] = (object)[
			'id' => $cd->id,
			'master_name' => $cd->master_name,
			'customer_id'	=> $cd->customer_id,
			
			'driver_name'	=> $drivers
			];

		}
		//echo '<pre>';print_r($rcdriver);exit;
	   return view('body.rentalcustomerdriver.index')
					->withCustomer($customer)
					->withRcdriver($rcdriver)
					->withData($data);
			
	} 

	private function sortDriver($drivers) {
		$driver = '';
		foreach($drivers as $pt) {
			$driver .= ($driver=='')?$pt->driver_name:','.$pt->driver_name;
		}  
		return $driver;
	}
	
	
	public function add() {

		$data = array();
		$customer = DB::table('account_master')
		          ->where('category','CUSTOMER')
				  ->where('deleted_at','0000-00-00 00:00:00')
				  ->where('status',1)
				  ->select('id','master_name')->get();
				  //echo '<pre>';print_r($supplier);exit;
		$driver = DB::table('rental_driver')->where('driver_type','customer')->where('deleted_at',null)->select('id','driver_name')->get();		  
		return view('body.rentalcustomerdriver.add')
					->withData($data)
					->withCustomer($customer)
					->withDriver($driver);
	}
	
	public function save() {
		//print_r(Input::all());
		DB::table('rental_customerdriver')
				->insert([
					'customer_id' => Input::get('customer'),
					'driver_id' => serialize(Input::get('driver'))
				]);
		Session::flash('message', 'Driver selected successfully.');
		return redirect('rental_customerdriver/add');
	}
	
	public function edit($id) { 

		$data = array();
		$rcdriver = DB::table('rental_customerdriver')
		            ->join('account_master AS AM','AM.id','=','rental_customerdriver.customer_id')
		            ->where('AM.category','CUSTOMER')
		            ->where('rental_customerdriver.id',$id)
					->select('rental_customerdriver.*','AM.master_name')
					->first();
		//echo '<pre>';print_r($rsdriver);exit;
		$driver = DB::table('rental_driver')->where('driver_type','customer')->where('deleted_at',null)->select('id','driver_name')->get();
		$customer = DB::table('account_master')
		          ->where('category','CUSTOMER')
				  ->where('deleted_at','0000-00-00 00:00:00')
				  ->where('status',1)
				  ->select('id','master_name')->get();
				  
				  //echo '<pre>';print_r($driver);exit;
		return view('body.rentalcustomerdriver.edit')
					->withRcdriver($rcdriver)
					->withDriver($driver)
					->withCustomer($customer)
					->withData($data);
	}
	
	public function update($id)
	{
		DB::table('rental_customerdriver')->where('id',$id)
				->update([
					'customer_id' => Input::get('customer'),
					'driver_id' => serialize(Input::get('driver'))
				]);//print_r(Input::all());exit;
		Session::flash('message', 'Driver updated successfully');
		return redirect('rental_customerdriver');
	}
	
	public function destroy($id)
	{
		DB::table('rental_customerdriver')->where('id',$id)->update(['deleted_by' => date('Y-m-d H:i:s')]);
		
		Session::flash('message', 'Driver deleted successfully.');
		return redirect('rental_customerdriver');
	}
	
	
}