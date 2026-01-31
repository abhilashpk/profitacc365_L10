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
						->whereNull('deleted_at')
						->select('assets_issued.*','E.name AS employee')
						->get();
		return view('body.assetsissued.index')
					->withDoctype($assetsissued)
					->withData($data);
	}
	
	
	
	public function add() {

		$data = array();
		$employee = DB::table('employee')->where('status',1)->where('duty_status','!=',-1)->whereNull('deleted_at')->get();
		return view('body.assetsissued.add')
					->withEmployee($employee)
					->withData($data);
	}
	
	public function save() {
		try {
			DB::table('assets_issued')
				->insert([
					'employee_id' => $request->get('employee_id'),
					'name' => $request->get('name'),
					'description' => $request->get('description'),
					'issue_date' => ($request->get('issue_date')!='')?date('Y-m-d', strtotime($request->get('issue_date'))):'',
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
		$employee = DB::table('employee')->where('status',1)->where('duty_status','!=',-1)->whereNull('deleted_at')->get();
		
		return view('body.assetsissued.edit')
					->withDocrow($assetsissued)
					->withEmployee($employee)
					->withData($data);
	}
	
	public function update($id)
	{
		DB::table('assets_issued')->where('id',$id)
				->update([
					'employee_id' => $request->get('employee_id'),
					'name' => $request->get('name'),
					'description' => $request->get('description'),
					'issue_date' => ($request->get('issue_date')!='')?date('Y-m-d', strtotime($request->get('issue_date'))):'',
					'asset_status' => $request->get('asset_status'),
					'received_date' => ($request->get('received_date')!='')?date('Y-m-d', strtotime($request->get('received_date'))):'',
					'othr_description' => $request->get('othr_description')
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



