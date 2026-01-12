<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Input;
use Session;
use Response;
use DB;
use App;

class AssetsIssuedController extends Controller
{
	
	public function __construct() {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->middleware('auth');
		
	}
	
	public function index() {
		$data = array();
		$assetsissued = DB::table('assets_issued')->where('assets_issued.status',1)
						->join('employee AS E', function($join) {
							$join->on('E.id','=','assets_issued.employee_id');
						})
						->where('assets_issued.deleted_at','0000-00-00 00:00:00')
						->select('assets_issued.*','E.name AS employee')
						->get();
		return view('body.assetsissued.index')
					->withDoctype($assetsissued)
					->withData($data);
	}
	
	
	
	public function add() {

		$data = array();
		$employee = DB::table('employee')->where('status',1)->where('duty_status','!=',-1)->where('deleted_at','0000-00-00 00:00:00')->get();
		return view('body.assetsissued.add')
					->withEmployee($employee)
					->withData($data);
	}
	
	public function save() {
		try {
			DB::table('assets_issued')
				->insert([
					'employee_id' => Input::get('employee_id'),
					'name' => Input::get('name'),
					'description' => Input::get('description'),
					'issue_date' => (Input::get('issue_date')!='')?date('Y-m-d', strtotime(Input::get('issue_date'))):'',
					'asset_status' => 1,
					'status' => 1
				]);
			Session::flash('message', 'Asset issued successfully.');
			return redirect('assets_issued');
		} catch(ValidationException $e) { 
			return Redirect::to('assets_issued/add')->withErrors($e->getErrors());
		}
	}
	
	public function edit($id) { 

		$data = array();
		$assetsissued = DB::table('assets_issued')->where('id',$id)->first();
		$employee = DB::table('employee')->where('status',1)->where('duty_status','!=',-1)->where('deleted_at','0000-00-00 00:00:00')->get();
		
		return view('body.assetsissued.edit')
					->withDocrow($assetsissued)
					->withEmployee($employee)
					->withData($data);
	}
	
	public function update($id)
	{
		DB::table('assets_issued')->where('id',$id)
				->update([
					'employee_id' => Input::get('employee_id'),
					'name' => Input::get('name'),
					'description' => Input::get('description'),
					'issue_date' => (Input::get('issue_date')!='')?date('Y-m-d', strtotime(Input::get('issue_date'))):'',
					'asset_status' => Input::get('asset_status'),
					'received_date' => (Input::get('received_date')!='')?date('Y-m-d', strtotime(Input::get('received_date'))):'',
					'othr_description' => Input::get('othr_description')
				]);
		Session::flash('message', 'Asset issued updated successfully');
		return redirect('assets_issued');
	}
	
	public function destroy($id)
	{
		DB::table('assets_issued')->where('id',$id)->update(['status' => 0, 'deleted_at' => date('Y-m-d H:i:s')]);
		Session::flash('message', 'Asset issued deleted successfully.');
		return redirect('assets_issued');
	}
	
	
}

