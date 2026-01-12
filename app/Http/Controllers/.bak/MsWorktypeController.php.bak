<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Input;
use Session;
use Response;
use DB;
use App;

class MsWorktypeController extends Controller
{
	
	public function __construct() {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->middleware('auth');
	}
	
	public function index() {
		$data = array();
		$locs = DB::table('ms_worktype')->where('deleted_at','0000-00-00 00:00:00')->orderBy('id','DESC')->get();
		return view('body.msworktype.index')
					->withWorktype($locs)
					->withData($data);
	}
	
	public function add() {

		return view('body.msworktype.add');
	}
	
	public function save() {
		try {
			$id = DB::table('ms_worktype')
					->insertGetId([
						'name' => Input::get('name')
					]);
				
			if($id) {
				$code = 100+$id;
				DB::table('ms_worktype')->where('id',$id)->update(['code' => $code]);
			}
		
			Session::flash('message', 'Work Type added successfully.');
			return redirect('ms_worktype/add');
		} catch(ValidationException $e) { 
			return Redirect::to('ms_worktype/add')->withErrors($e->getErrors());
		}
	}
	
	public function edit($id) { 

		$data = array();
		$locrow = DB::table('ms_worktype')->find($id);
						
		return view('body.msworktype.edit')
					->withLocrow($locrow)
					->withData($data);
	}
	
	public function update($id)
	{
		DB::table('ms_worktype')->where('id',$id)
				->update([
					'name' => Input::get('name')
				]);
		Session::flash('message', 'Work Type updated successfully');
		return redirect('ms_worktype');
	}
	
	public function destroy($id)
	{
		DB::table('ms_worktype')->where('id',$id)->update(['deleted_at' => date('Y-m-d H:i:s')]);
		Session::flash('message', 'Work Type deleted successfully.');
		return redirect('ms_worktype');
	}
	
	
	
}
