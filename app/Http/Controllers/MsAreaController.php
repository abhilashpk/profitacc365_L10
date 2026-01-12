<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Input;
use Session;
use Response;
use DB;
use App;

class MsAreaController extends Controller
{
	
	public function __construct() {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->middleware('auth');
	}
	
	public function index() {
		$data = array();
		$area = DB::table('ms_area')->where('deleted_at','0000-00-00 00:00:00')->orderBy('id','DESC')->get();
		return view('body.msarea.index')
					->withArea($area)
					->withData($data);
	}
	
	public function add() {

		return view('body.msarea.add');
	}
	
	public function save() {
		try {
			 DB::table('ms_area')
					->insert([
						'name' => Input::get('name')
					]);
				
			Session::flash('message', 'Area added successfully.');
			return redirect('ms_area/add');
		} catch(ValidationException $e) { 
			return Redirect::to('ms_area/add')->withErrors($e->getErrors());
		}
	}
	
	public function edit($id) { 

		$data = array();
		$locrow = DB::table('ms_area')->find($id);
						
		return view('body.msarea.edit')
					->withLocrow($locrow)
					->withData($data);
	}
	
	public function update($id)
	{
		DB::table('ms_area')->where('id',$id)
				->update([
					'name' => Input::get('name')
				]);
		Session::flash('message', 'Area updated successfully');
		return redirect('ms_area');
	}
	
	public function destroy($id)
	{
		DB::table('ms_area')->where('id',$id)->update(['deleted_at' => date('Y-m-d H:i:s')]);
		Session::flash('message', 'Area deleted successfully.');
		return redirect('ms_area');
	}
	
	
	
}

