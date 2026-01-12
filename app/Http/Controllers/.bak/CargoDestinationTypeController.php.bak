<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Input;
use Session;
use Response;
use DB;
use App;

class CargoDestinationTypeController extends Controller
{
    protected $collection_type;
	
	public function __construct() {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->middleware('auth');
		
	}
	
	public function index() {
		$data = array();
		$cdtype = DB:: table('cargo_destination')
		                ->where('cargo_destination.deleted_at',null)->orderBy('cargo_destination.code','ASC')->get();
						//echo '<pre>';print_r($ctype);exit;
		return view('body.cargodestinationtype.index')
					->withCdtype($cdtype)
					->withData($data);
	}
	
	public function add() {

		$data = array();
		return view('body.cargodestinationtype.add')
					->withData($data);
	}
	
	 public function save() {
		try {
			DB::table('cargo_destination')
				->insert([
					'code' => Input::get('code'),
					'name' => Input::get('name')
				]);
				
			Session::flash('message', 'Destination Type added successfully.');
			return redirect('destination_type/add');
		} catch(ValidationException $e) { 
			return Redirect::to('destination_type/add')->withErrors($e->getErrors());
		}
	}  

	
	
	public function edit($id) { 

		$cdedit = DB::table('cargo_destination')->where('id',$id)->first();
		//echo '<pre>';print_r($cedit);exit;
						
		return view('body.cargodestinationtype.edit')
					->withCdedit($cdedit);
	}



	public function update($id)
	{
		DB::table('cargo_destination')->where('id',$id)
				->update([
					'code' => Input::get('code'),
					'name' => Input::get('name'),
				]);
		Session::flash('message', 'Destination Type updated successfully');
		return redirect('destination_type');
	}

    public function checkname() {

		$check = $this->check_destination_name(Input::get('name'), Input::get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
					));
	}
	public function check_destination_name($name, $id = null) {
		
		if($id){
		$query=DB::table('cargo_destination')
		->where('name',$name)->where('id', '!=', $id)->where('cargo_destination.deleted_at',null)->count();
		return $query;
		}
		else{
		$query=DB::table('cargo_destination')
		->where('name',$name)->where('cargo_destination.deleted_at',null)->count();
		return $query ;
		}
	}

	public function destroy($id)
	{
		DB::table('cargo_destination')->where('id',$id)->update(['deleted_at' => date('Y-m-d H:i:s')]);
		Session::flash('message', 'Destination Type deleted successfully.');
		return redirect('destination_type');
	}
	
	
	
	
}
