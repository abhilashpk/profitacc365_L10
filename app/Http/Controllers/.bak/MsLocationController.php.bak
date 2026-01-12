<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Input;
use Session;
use Response;
use DB;
use App;

class MsLocationController extends Controller
{
	
	public function __construct() {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->middleware('auth');
	}
	
	public function index() {
		$data = array();
		$locs = DB::table('ms_location')->where('deleted_at','0000-00-00 00:00:00')->orderBy('id','DESC')->get();
		return view('body.mslocation.index')
					->withLocation($locs)
					->withData($data);
	}
	
	public function add() {

		return view('body.mslocation.add');
	}
	
	public function save() {
		try {
			$id = DB::table('ms_location')
					->insertGetId([
						'name' => Input::get('name')
					]);
				
			if($id) {
				$code = 100+$id;
				DB::table('ms_location')->where('id',$id)->update(['code' => $code]);
			}
		
			Session::flash('message', 'Location added successfully.');
			return redirect('ms_location');
		} catch(ValidationException $e) { 
			return Redirect::to('ms_location/add')->withErrors($e->getErrors());
		}
	}
	
	public function edit($id) { 

		$data = array();
		$locrow = DB::table('ms_location')->find($id);
						
		return view('body.mslocation.edit')
					->withLocrow($locrow)
					->withData($data);
	}
	
	public function update($id)
	{
		DB::table('ms_location')->where('id',$id)
				->update([
					'name' => Input::get('name')
				]);
		Session::flash('message', 'Location updated successfully');
		return redirect('ms_location');
	}
	
	public function destroy($id)
	{
		DB::table('ms_location')->where('id',$id)->update(['deleted_at' => date('Y-m-d H:i:s')]);
		Session::flash('message', 'Location deleted successfully.');
		return redirect('ms_location');
	}
	
	
	
}
