<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Input;
use Session;
use Response;
use DB;
use App;

class DeliveryTypeController extends Controller
{
    protected $delivery_type;
	
	public function __construct() {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->middleware('auth');
		
	}
	
	public function index() {
		$data = array();
		$dtype = DB:: table('delivery_type')
		                ->where('delivery_type.deleted_at',null)->orderBy('delivery_type.code','ASC')->get();
						//echo '<pre>';print_r($ctype);exit;
		return view('body.deliverytype.index')
					->withDtype($dtype)
					->withData($data);
	}
	
	public function add() {

		$data = array();
		return view('body.deliverytype.add')
					->withData($data);
	}
	
	 public function save() {
		try {
			DB::table('delivery_type')
				->insert([
					'code' => Input::get('code'),
					'description' => Input::get('description')
				]);
				
			Session::flash('message', 'Delivery Type added successfully.');
			return redirect('delivery_type/add');
		} catch(ValidationException $e) { 
			return Redirect::to('delivery_type/add')->withErrors($e->getErrors());
		}
	}  

	
	
	public function edit($id) { 

		$dtedit = DB::table('delivery_type')->where('id',$id)->first();
		//echo '<pre>';print_r($cedit);exit;
						
		return view('body.deliverytype.edit')
					->withDtedit($dtedit);
	}



	public function update($id)
	{
		DB::table('delivery_type')->where('id',$id)
				->update([
					'code' => Input::get('code'),
					'description' => Input::get('description'),
				]);
		Session::flash('message', 'Delivery Type updated successfully');
		return redirect('delivery_type');
	}

    public function checkcode() {

		$check = $this->check_delivery_code(Input::get('code'), Input::get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
					));
	}
	public function check_delivery_code($code, $id = null) {
		
		if($id){
		$query=DB::table('delivery_type')
		->where('code',$code)->where('id', '!=', $id)->where('delivery_type.deleted_at',null)->count();
		return $query;
		}
		else{
		$query=DB::table('delivery_type')
		->where('code',$code)->where('delivery_type.deleted_at',null)->count();
		return $query ;
		}
	}

	public function destroy($id)
	{
		DB::table('delivery_type')->where('id',$id)->update(['deleted_at' => date('Y-m-d H:i:s')]);
		Session::flash('message', 'Delivery Type deleted successfully.');
		return redirect('delivery_type');
	}
	
	
	
	
}

