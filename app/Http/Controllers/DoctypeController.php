<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Input;
use Session;
use Response;
use DB;
use App;

class DoctypeController extends Controller
{
	
	public function __construct() {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->middleware('auth');
		
	}
	
	public function index() {
		$data = array();
		$doctype = DB::table('doc_department')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		return view('body.doctype.index')
					->withDoctype($doctype)
					->withData($data);
	}
	
	public function add() {

		$data = array();
		return view('body.doctype.add')
					->withData($data);
	}
	
	public function save() {
		try {
			DB::table('doc_department')
				->insert([
					'department_name' => Input::get('name'),
					'status' => 1
				]);
			Session::flash('message', 'Department added successfully.');
			return redirect('doctype/add');
		} catch(ValidationException $e) { 
			return Redirect::to('doctype/add')->withErrors($e->getErrors());
		}
	}
	
	public function edit($id) { 

		$data = array();
		$doctype = DB::table('doc_department')->where('id',$id)->first();
						
		return view('body.doctype.edit')
					->withDocrow($doctype)
					->withData($data);
	}
	
	public function update($id)
	{
		DB::table('doc_department')->where('id',$id)
				->update([
					'department_name' => Input::get('name'),
				]);
		Session::flash('message', 'Department updated successfully');
		return redirect('doctype');
	}
	
	public function destroy($id)
	{
		DB::table('doc_department')->where('id',$id)->update(['status' => 0, 'deleted_at' => date('Y-m-d H:i:s')]);
		Session::flash('message', 'Department deleted successfully.');
		return redirect('doctype');
	}
	
	
}

