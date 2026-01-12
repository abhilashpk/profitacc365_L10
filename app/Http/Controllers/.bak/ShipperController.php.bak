<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Input;
use Session;
use Response;
use DB;
use App;

class ShipperController extends Controller
{
    protected $shipper;
	
	public function __construct() {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->middleware('auth');
		
	}
	
	public function index() {
		$data = array();
		$shipper = DB:: table('shipper')
		                ->where('shipper.deleted_at',null)->orderBy('shipper.shipper_name','ASC')->get();
						//echo '<pre>';print_r($consignee);exit;
		return view('body.shipper.index')
					->withShipper($shipper)
					->withData($data);
	}
	
	public function add() {

		$data = array();
		return view('body.shipper.add')
					->withData($data);
	}
	
	 public function save() {
		try {
			DB::table('shipper')
				->insert([
					'shipper_name' => Input::get('name'),
					'phone' => Input::get('phone'),
					'address' => Input::get('address')
				]);
				
			Session::flash('message', 'Shipper added successfully.');
			return redirect('shipper/add');
		} catch(ValidationException $e) { 
			return Redirect::to('shipper/add')->withErrors($e->getErrors());
		}
	}  

	
	
	public function edit($id) { 

		$sedit = DB::table('shipper')->where('id',$id)->first();
		//echo '<pre>';print_r($cedit);exit;
						
		return view('body.shipper.edit')
					->withSedit($sedit);
	}



	public function update($id)
	{
		DB::table('shipper')->where('id',$id)
				->update([
					'shipper_name' => Input::get('name'),
					'phone' => Input::get('phone'),
					'address' => Input::get('address'),
				]);
		Session::flash('message', 'Shipper updated successfully');
		return redirect('shipper');
	}

	public function checkphone() {

		$check = $this->check_shipper_phone(Input::get('phone'), Input::get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
	public function check_shipper_phone($phone, $id = null) {
		
		if($id){
			$query=DB::table('shipper')->where('phone',$phone)->where('id', '!=', $id)->where('shipper.deleted_at',null)->count();
			return $query;
		}
		else{
		$query=DB::table('shipper')->where('phone',$phone)->where('shipper.deleted_at',null)->count();
		return $query;
		}
	}
	
	public function checkname() {

		$check = $this->check_shipper_name(Input::get('name'), Input::get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}

	public function check_shipper_name($name, $id = null) {
		
		if($id){
		$query=DB::table('shipper')->where('shipper_name',$name)->where('id', '!=', $id)->where('shipper.deleted_at',null)->count();
		return $query;
		}
		else{
         $query=DB::table('shipper')->where('shipper_name',$name)->where('shipper.deleted_at',null)->count();
		 return $query;
		}
	}
	

	
	

	public function destroy($id)
	{
		DB::table('shipper')->where('id',$id)->update(['deleted_at' => date('Y-m-d H:i:s')]);
		Session::flash('message', 'Shipper deleted successfully.');
		return redirect('shipper');
	}
	
	public function ajaxSave(Request $request) {
		
		$check1 = DB::table('shipper')->where('shipper_name', trim($request->get('name')))->where('deleted_at',null)->count();
		if(($check1 > 0))
			return 0;
		
		$check2 = DB::table('shipper')->where('phone', trim($request->get('phone')))->where('deleted_at',null)->count();
		if(($check2 > 0))
			return -1;
			
		$id = DB::table('shipper')
				->insertGetId([
					'shipper_name' => trim($request->get('name')),
					'address' => trim($request->get('address')),
					'phone'	=> trim($request->get('phone'))
				]);
			
		return $id;
			
	}
	
	
}
