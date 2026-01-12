<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Input;
use Session;
use Response;
use DB;
use App;

class JobtypeController extends Controller
{
    protected $vehicles;
	
	public function __construct() {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->middleware('auth');
		
	}
	
	public function index() {
		$data = array();
		$jobtype = DB::table('jobtype')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		return view('body.jobtype.index')
					->withJobtype($jobtype)
					->withData($data);
	}
	
	public function add() {

		$data = array();
		return view('body.jobtype.add')
					->withData($data);
	}
	
	public function save() {
		try {
			DB::table('jobtype')
				->insert([
					'name' => Input::get('name'),
					'job_no' => Input::get('job_no'),
					'status' => 1
				]);
			Session::flash('message', 'Job type added successfully.');
			return redirect('jobtype/add');
		} catch(ValidationException $e) { 
			return Redirect::to('jobtype/add')->withErrors($e->getErrors());
		}
	}
	
	public function edit($id) { 

		$data = array();
		$jobtype = DB::table('jobtype')->where('id',$id)->first();
						
		return view('body.jobtype.edit')
					->withJobrow($jobtype)
					->withData($data);
	}
	
	public function update($id)
	{
		DB::table('jobtype')->where('id',$id)
				->update([
					'name' => Input::get('name'),
					'job_no' => Input::get('job_no')
				]);
		Session::flash('message', 'Job type updated successfully');
		return redirect('jobtype');
	}
	
	public function destroy($id)
	{
		DB::table('jobtype')->where('id',$id)->update(['status' => 0, 'deleted_at' => date('Y-m-d H:i:s')]);
		Session::flash('message', 'jobtype deleted successfully.');
		return redirect('jobtype');
	}
	
	public function getJobNo($id) {
		
		$result = DB::table('jobtype')->where('id',$id)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('name','job_no')->first();
		echo json_encode($result);
	}
	
}
