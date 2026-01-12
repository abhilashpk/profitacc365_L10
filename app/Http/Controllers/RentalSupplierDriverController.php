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

class RentalSupplierDriverController extends Controller
{
    protected $units;
	
	public function __construct() {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->middleware('auth');
	}
	
	public function index() {
		$data = array();
		$supplier =DB:: table('rental_supplierdriver')
		->join('account_master AS AM','AM.id','=','rental_supplierdriver.supplier_id')
		->where('AM.category','SUPPLIER')
		->where('rental_supplierdriver.deleted_by',null)
		->select('AM.master_name','rental_supplierdriver.id','rental_supplierdriver.supplier_id','rental_supplierdriver.driver_id')->get();
		//echo '<pre>';print_r($supplier);exit;
		$rsdriver=null;
		foreach($supplier as $sd) {
		$sdriver=unserialize($sd->driver_id);
		$res = DB::table('rental_driver')->whereIn('id',$sdriver)->where('driver_type','supplier')->where('deleted_at',null)->select('driver_name')->get();
		$drivers = $this->sortDriver($res);
		$rsdriver[] = (object)[
			'id' => $sd->id,
			'master_name' => $sd->master_name,
			'supplier_id'	=> $sd->supplier_id,
			
			'driver_name'	=> $drivers
			];

		}
		
		//echo '<pre>';print_r($rsdr);exit;
	   return view('body.rentalsupplierdriver.index')
					->withSupplier($supplier)
					->withRsdriver($rsdriver)
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
		$supplier = DB::table('account_master')
		          ->where('category','SUPPLIER')
				  ->where('deleted_at','0000-00-00 00:00:00')
				  ->where('status',1)
				  ->select('id','master_name')->get();
				  //echo '<pre>';print_r($supplier);exit;
		$driver = DB::table('rental_driver')->where('driver_type','supplier')->where('deleted_at',null)->select('id','driver_name')->get();		  
		return view('body.rentalsupplierdriver.add')
					->withData($data)
					->withSupplier($supplier)
					->withDriver($driver);
	}
	
	public function save() {
		//print_r(Input::all());
		DB::table('rental_supplierdriver')
				->insert([
					'supplier_id' => Input::get('supplier'),
					'driver_id' => serialize(Input::get('driver'))
				]);
		Session::flash('message', 'Driver selected successfully.');
		return redirect('rental_supplierdriver/add');
	}
	
	public function edit($id) { 

		$data = array();
		$rsdriver = DB::table('rental_supplierdriver')
		            ->join('account_master AS AM','AM.id','=','rental_supplierdriver.supplier_id')
		            ->where('AM.category','SUPPLIER')
		            ->where('rental_supplierdriver.id',$id)
					->select('rental_supplierdriver.*','AM.master_name')
					->first();
		//echo '<pre>';print_r($rsdriver);exit;
		$driver = DB::table('rental_driver')->where('driver_type','supplier')->where('deleted_at',null)->select('id','driver_name')->get();
		$supplier = DB::table('account_master')
		          ->where('category','SUPPLIER')
				  ->where('deleted_at','0000-00-00 00:00:00')
				  ->where('status',1)
				  ->select('id','master_name')->get();
				  
				  //echo '<pre>';print_r($driver);exit;
		return view('body.rentalsupplierdriver.edit')
					->withRsdriver($rsdriver)
					->withDriver($driver)
					->withSupplier($supplier)
					->withData($data);
	}
	
	public function update($id)
	{
		DB::table('rental_supplierdriver')->where('id',$id)
				->update([
					'supplier_id' => Input::get('supplier'),
					'driver_id' => serialize(Input::get('driver'))
				]);//print_r(Input::all());exit;
		Session::flash('message', 'Driver updated successfully');
		return redirect('rental_supplierdriver');
	}
	
	public function destroy($id)
	{
		DB::table('rental_supplierdriver')->where('id',$id)->update(['deleted_by' => date('Y-m-d H:i:s')]);
		
		Session::flash('message', 'Driver deleted successfully.');
		return redirect('rental_supplierdriver');
	}
	
	
	
}
