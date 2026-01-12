<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Input;
use Session;
use Response;
use DB;
use App;

class MsTechnicianController extends Controller
{
	
	public function __construct() {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->middleware('auth');
	}
	
	public function index() {
		$data = array();
		$tec = DB::table('ms_technician')->where('deleted_at','0000-00-00 00:00:00')->orderBy('id','DESC')->get();
		return view('body.mstechnician.index')
					->withTec($tec)
					->withData($data);
	}
	
	public function add() {

		return view('body.mstechnician.add');
	}
	
	public function save() {
		try {
			$id = DB::table('ms_technician')
					->insertGetId([
						'name' => Input::get('name')
					]);
				
			if($id) {
				$code = 100+$id;
				DB::table('ms_technician')->where('id',$id)->update(['code' => $code]);
			}
		
			Session::flash('message', 'Technician added successfully.');
			return redirect('ms_technician/add');
		} catch(ValidationException $e) { 
			return Redirect::to('ms_technician/add')->withErrors($e->getErrors());
		}
	}
	
	public function edit($id) { 

		$data = array();
		$locrow = DB::table('ms_technician')->find($id);
						
		return view('body.mstechnician.edit')
					->withLocrow($locrow)
					->withData($data);
	}
	
	public function update($id)
	{
		DB::table('ms_technician')->where('id',$id)
				->update([
					'name' => Input::get('name')
				]);
		Session::flash('message', 'Technician updated successfully');
		return redirect('ms_technician');
	}
	
	public function destroy($id)
	{
		DB::table('ms_technician')->where('id',$id)->update(['deleted_at' => date('Y-m-d H:i:s')]);
		Session::flash('message', 'Technician deleted successfully.');
		return redirect('ms_technician');
	}
	
	
	
}

