<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Input;
use Session;
use Response;
use DB;
use App;

class ConsigneeController extends Controller
{
    protected $consignee;
	
	public function __construct() {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->middleware('auth');
		
	}
	
	public function index() {
		$data = array();
		$consignee = DB:: table('consignee')
		                ->where('consignee.deleted_at',null)->orderBy('consignee.consignee_name','ASC')->get();
						//echo '<pre>';print_r($consignee);exit;
		return view('body.consignee.index')
					->withConsignee($consignee)
					->withData($data);
	}
	
	public function add() {

		$data = array();
		return view('body.consignee.add')
					->withData($data);
	}
	
	 public function save() {
		try {
			DB::table('consignee')
				->insert([
					'consignee_name' => Input::get('name'),
					'phone' => Input::get('phone'),
					'alter_phone' => Input::get('phone1'),
					'address' => Input::get('address')
				]);
				
			Session::flash('message', 'Consignee added successfully.');
			return redirect('consignee/add');
		} catch(ValidationException $e) { 
			return Redirect::to('consignee/add')->withErrors($e->getErrors());
		}
	}  

	
	
	public function edit($id) { 

		$cedit = DB::table('consignee')->where('id',$id)->first();
		//echo '<pre>';print_r($cedit);exit;
						
		return view('body.consignee.edit')
					->withCedit($cedit);
	}



	public function update($id)
	{
		DB::table('consignee')->where('id',$id)
				->update([
					'consignee_name' => Input::get('name'),
					'phone' => Input::get('phone'),
					'alter_phone' => Input::get('phone1'),
					'address' => Input::get('address'),
				]);
		Session::flash('message', 'Consignee updated successfully');
		return redirect('consignee');
	}

    public function checkphone() {

		$check = $this->check_consignee_phone(Input::get('phone'), Input::get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
	public function check_consignee_phone($phone, $id = null) {
		
		if($id){
			$query=DB::table('consignee')->where('phone',$phone)->where('id', '!=', $id)->where('consignee.deleted_at',null)->count();
			return $query;
		}
		else{
		$query=DB::table('consignee')->where('phone',$phone)->where('consignee.deleted_at',null)->count();
		return $query;
		}
	}
	public function checkphone1() {

		$check = $this->check_consignee_phone1(Input::get('phone1'), Input::get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
	public function check_consignee_phone1($phone1, $id = null) {
		
		if($id){
			$query=DB::table('consignee')->where('alter_phone',$phone1)->where('id', '!=', $id)->where('consignee.deleted_at',null)->count();
			return $query;
		}
		else{
		$query=DB::table('consignee')->where('alter_phone',$phone1)->where('consignee.deleted_at',null)->count();
		return $query;
		}
	}
	
	public function checkname() {

		$check = $this->check_consignee_name(Input::get('name'), Input::get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}

	public function check_consignee_name($name, $id = null) {
		
		if($id){
		$query=DB::table('consignee')->where('consignee_name',$name)->where('id', '!=', $id)->where('consignee.deleted_at',null)->count();
		return $query;
		}
		else{
         $query=DB::table('consignee')->where('consignee_name',$name)->where('consignee.deleted_at',null)->count();
		 return $query;
		}
	}
	


	public function destroy($id)
	{
		DB::table('consignee')->where('id',$id)->update(['deleted_at' => date('Y-m-d H:i:s')]);
		Session::flash('message', 'Consignee deleted successfully.');
		return redirect('consignee');
	}
	
	public function ajaxSave(Request $request) {
		
		$check1 = DB::table('consignee')->where('consignee_name', trim($request->get('name')))->where('deleted_at',null)->count();
		if(($check1 > 0))
			return 0;
		
		$check2 = DB::table('consignee')->where('phone', trim($request->get('phone')))->where('deleted_at',null)->count();
		if(($check2 > 0))
			return -1;
		$check3 = DB::table('consignee')->where('alter_phone', trim($request->get('phone1')))->where('deleted_at',null)->count();
			if($request->get('phone1')!='' && ($check3 > 0))
				return -2;	
		$id = DB::table('consignee')
				->insertGetId([
					'consignee_name' => trim($request->get('name')),
					'address' => trim($request->get('address')),
					'phone'	=> trim($request->get('phone')),
					'alter_phone'	=> trim($request->get('phone1')),
					'consignee_code'	=> trim($request->get('code'))
				]);
			
		return $id;
		return redirect('cargo_receipt/add');
			
	}
	
	
}

