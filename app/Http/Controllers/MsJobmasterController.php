<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Input;
use Session;
use Response;
use DB;
use App;

class MsJobmasterController extends Controller
{
	
	public function __construct() {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->middleware('auth');
	}
	
	public function index() {
		$data = array();
		$jobs = DB::table('ms_jobmaster')->where('deleted_at','0000-00-00 00:00:00')->orderBy('id','DESC')->get();
		return view('body.msjobmaster.index')
					->withJobs($jobs)
					->withData($data);
	}
	
	public function add() {

		return view('body.msjobmaster.add');
	}
	
	public function save() {
		try {
			$id = DB::table('ms_jobmaster')
					->insertGetId([
						'name' => Input::get('name')
					]);
				
			if($id) {
				$code = 100+$id;
				DB::table('ms_jobmaster')->where('id',$id)->update(['code' => 'JB'.$code]);
			}
		
			Session::flash('message', 'Job Master added successfully.');
			return redirect('ms_jobmaster/add');
		} catch(ValidationException $e) { 
			return Redirect::to('ms_jobmaster/add')->withErrors($e->getErrors());
		}
	}
	
	public function edit($id) { 

		$data = array();
		$locrow = DB::table('ms_jobmaster')->find($id);
						
		return view('body.msjobmaster.edit')
					->withLocrow($locrow)
					->withData($data);
	}
	
	public function update($id)
	{
		DB::table('ms_jobmaster')->where('id',$id)
				->update([
					'name' => Input::get('name')
				]);
		Session::flash('message', 'Job Master updated successfully');
		return redirect('ms_jobmaster');
	}
	
	public function destroy($id)
	{
		DB::table('ms_jobmaster')->where('id',$id)->update(['deleted_at' => date('Y-m-d H:i:s')]);
		Session::flash('message', 'Job Master deleted successfully.');
		return redirect('ms_jobmaster');
	}
	
	public function getJobs() {
		
		$jobs = DB::table('ms_jobmaster')->where('deleted_at', '0000-00-00 00:00:00')->orderBy('code', 'ASC')->get();
		return view('body.msjobmaster.jobs')
					->withJobs($jobs);
		
	}
	
}

