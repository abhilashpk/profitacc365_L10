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

class CargoUnitController extends Controller
{
    protected $units;
	
	public function __construct() {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->middleware('auth');
	}
	
	public function index() {
		$data = array();
		$units =DB:: table('units')
		->where('units.deleted_at',null)->orderBy('units.unit_name','ASC')->get();
		return view('body.cargounit.index')
					->withUnits($units)
					->withData($data);
	}
	
	public function add() {

		$data = array();
		return view('body.cargounit.add')
					->withData($data);
	}
	
	public function save() {
		//print_r(Input::all());
		DB::table('units')
				->insert([
					'unit_name' => Input::get('unit_name'),
					'description' => Input::get('description'),
					'status'=>1,
				
				]);
		Session::flash('message', 'Unit added successfully.');
		return redirect('cargounit/add');
	}
	
	public function edit($id) { 

		$data = array();
		$unitrow = DB::table('units')->where('id',$id)->first();
		return view('body.cargounit.edit')
					->withUnitrow($unitrow)
					->withData($data);
	}
	
	public function update($id)
	{
		DB::table('units')->where('id',$id)
				->update([
					'unit_name' => Input::get('unit_name'),
					'description' => Input::get('description'),
					'status'=>1,
				]);//print_r(Input::all());exit;
		Session::flash('message', 'Unit updated successfully');
		return redirect('cargounit');
	}
	
	public function destroy($id)
	{
		DB::table('units')->where('id',$id)->update(['deleted_at' => date('Y-m-d H:i:s')]);
		
		Session::flash('message', 'Unit deleted successfully.');
		return redirect('cargounit');
	}
	
	public function checkname() {

		$check = $this->check_unit_name(Input::get('unit_name'), Input::get('id'));
		$isAvailable = ($check) ? false : true;
		//echo '<pre>';print_r($isAvailable);exit;
		echo json_encode(array(
							'valid' => $isAvailable,
					));
	}
	public function check_unit_name($name, $id = null) {
		
		if($id){
		$query=DB::table('units')
		->where('unit_name',$name)->where('id', '!=', $id)->where('units.deleted_at',null)->count();
		return $query;
		}
		else{
		$query=DB::table('units')
		->where('unit_name',$name)->where('units.deleted_at',null)->count();
		return $query ;
		}
	}
	
	public function destroyGroup()
	{
		$ids = Input::get('ids');
		if($ids) {
			$idarr = explode(',', $ids);
			DB::table('units')->whereIn('id',$idarr)->update(['deleted_at' => date('Y-m-d H:i:s')]);
			Session::flash('message', 'Units deleted successfully.');
		}
		return redirect('cargounit');
	}
}