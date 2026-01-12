<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Input;
use Session;
use Response;
use DB;
use App;

class MachineController extends Controller
{
    protected $machines;
	
	public function __construct() {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->middleware('auth');
		
	}
	
	public function index() {
		$data = array();
		$machine = DB::table('machine')->where('deleted_at',null)->get();
		return view('body.machine.index')
					->withMachine($machine)
					->withData($data);
	}
	
	public function add() {

		$data = array();
		return view('body.machine.add')
					->withData($data);
	}
	
	public function save() {
		try {
			DB::table('machine')
				->insert([
					'name' => Input::get('name'),
					'model' => Input::get('model'),
					'serialno' => Input::get('serialno'),
					'brand' => Input::get('brand'),
					'media' => Input::get('media'),
					'type' => Input::get('type')
				]);
			Session::flash('message', 'Machine added successfully.');
			return redirect('machine/add');
		} catch(ValidationException $e) { 
			return Redirect::to('machine/add')->withErrors($e->getErrors());
		}
	}
	
	public function edit($id) { 

		$data = array();
		$row = DB::table('machine')->where('machine.id',$id)->first();
						
		return view('body.machine.edit')
					->withMrow($row)
					->withData($data);
	}
	
	public function update($id)
	{
		DB::table('machine')->where('id',$id)
				->update([
					'name' => Input::get('name'),
					'model' => Input::get('model'),
					'serialno' => Input::get('serialno'),
					'brand' => Input::get('brand'),
					'media' => Input::get('media'),
					'type' => Input::get('type')
				]);
		Session::flash('message', 'Machine updated successfully');
		return redirect('machine');
	}
	
	public function destroy($id)
	{
		DB::table('machine')->where('id',$id)->update(['deleted_at' => date('Y-m-d H:i:s')]);
		Session::flash('message', 'Machine deleted successfully.');
		return redirect('machine');
	}
	
	public function checkregno() {

		/* if(Input::get('id') != '')
			$check = DB::table('machine')->where('reg_no',Input::get('reg_no'))->where('id', '!=', Input::get('id'))->count();
		else
			$check = DB::table('machine')->where('reg_no',Input::get('reg_no'))->count(); */
		if(Input::get('id') != '')
			$check = DB::table('machine')->where('chasis_no',Input::get('chasis_no'))->where('id', '!=', Input::get('id'))->count();
		else
			$check = DB::table('machine')->where('chasis_no',Input::get('chasis_no'))->count();
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
	
	
}

