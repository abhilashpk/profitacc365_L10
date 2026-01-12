<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Input;
use Session;
use Response;
use DB;
use App;

class CargoSalesmanController extends Controller
{
    protected $collection_type;
	
	public function __construct() {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->middleware('auth');
		
	}
	
	public function index() {
		$data = array();
		$cstype = DB:: table('salesman')
		                ->where('salesman.deleted_at','0000-00-00 00:00:00')->orderBy('salesman.name','ASC')->get();
						//echo '<pre>';print_r($ctype);exit;
		return view('body.cargosalesman.index')
					->withCstype($cstype)
					->withData($data);
	}
	
	public function add() {

		$data = array();
		return view('body.cargosalesman.add')
					->withData($data);
	}
	
	 public function save() {
		try {
			DB::table('salesman')
				->insert([
					'salesman_id' => Input::get('saleid'),
					'name' => Input::get('name'),
					'status' => 1
				]);
				
			Session::flash('message', 'Salesman added successfully.');
			return redirect('cargo_salesman/add');
		} catch(ValidationException $e) { 
			return Redirect::to('cargo_salesman/add')->withErrors($e->getErrors());
		}
	}  

	
	
	public function edit($id) { 

		$csedit = DB::table('salesman')->where('id',$id)->first();
		//echo '<pre>';print_r($cedit);exit;
						
		return view('body.cargosalesman.edit')
					->withCsedit($csedit);
	}



	public function update($id)
	{
		DB::table('salesman')->where('id',$id)
				->update([
					'salesman_id' => Input::get('saleid'),
					'name' => Input::get('name')
				]);
		Session::flash('message', 'Salesman updated successfully');
		return redirect('cargo_salesman');
	}

	public function checkid() {

		$check = $this->check_salesman_id(Input::get('saleid'), Input::get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
					));
	}
	public function check_salesman_id($sid, $id = null) {
		
		if($id){
		$query=DB::table('salesman')
		->where('salesman_id',$sid)->where('id', '!=', $id)->count();
		return $query;
		}
		else{
		$query=DB::table('salesman')
		->where('salesman_id',$sid)->count();
		return $query ;
		}
	}

    public function checkname() {

		$check = $this->check_salesman_name(Input::get('name'), Input::get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
					));
	}
	public function check_salesman_name($name, $id = null) {
		
		if($id){
		$query=DB::table('salesman')
		->where('name',$name)->where('id', '!=', $id)->where('salesman.deleted_at','0000-00-00 00:00:00')->count();
		return $query;
		}
		else{
		$query=DB::table('salesman')
		->where('name',$name)->where('salesman.deleted_at','0000-00-00 00:00:00')->count();
		return $query ;
		}
	}

	public function destroy($id)
	{
		DB::table('salesman')->where('id',$id)->update(['deleted_at' => date('Y-m-d H:i:s')]);
		Session::flash('message', 'Salesman deleted successfully.');
		return redirect('cargo_salesman');
	}
	
	public function ajaxSave(Request $request) {
		
		$check1 = DB::table('salesman')->where('salesman_id', trim($request->get('sid')))->where('deleted_at','0000-00-00 00:00:00')->count();
		if(($check1 > 0))
			return 0;
			
		$id = DB::table('salesman')
				->insertGetId([
					'salesman_id' => trim($request->get('sid')),
					'name' => trim($request->get('name')),
					'status'	=> 1
				]);
			
		return $id;
			
	}
	
	
}

