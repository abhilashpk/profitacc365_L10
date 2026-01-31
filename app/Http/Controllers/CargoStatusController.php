<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Input;
use Session;
use Response;
use DB;
use App;

class CargoStatusController extends Controller
{
    protected $cargo_status;
	
	public function __construct() {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->middleware('auth');
		
	}
	
	public function index() {
		$data = array();
		$cstatus = DB:: table('cargo_status')
		                ->where('cargo_status.deleted_at',null)->orderBy('cargo_status.id','ASC')->get();
						//echo '<pre>';print_r($cstatus);exit;
		return view('body.cargostatus.index')
					->withCstatus($cstatus)
					->withData($data);
	}
	
	public function add() {

		$data = array();
		return view('body.cargostatus.add')
					->withData($data);
	}
	
	 public function save() {
		try {
			DB::table('cargo_status')
				->insert([	
					'name' => $request->get('name'),
					'type' =>$request->get('status_type'),
					'is_reached'=>($request->get('name')=='REACHED DESTINATION')?1:0,
				]);
				
			Session::flash('message', 'Status added successfully.');
			return redirect('cargo_status/add');
		} catch(ValidationException $e) { 
			return Redirect::to('cargo_status/add')->withErrors($e->getErrors());
		}
	}  

	
	
	public function edit($id) { 

		$csedit = DB::table('cargo_status')->where('id',$id)->first();
		//echo '<pre>';print_r($cedit);exit;
						
		return view('body.cargostatus.edit')
					->withCsedit($csedit);
	}



	public function update($id)
	{
		DB::table('cargo_status')->where('id',$id)
				->update([
					
					'name' => $request->get('name'),
					'is_reached'=>($request->get('name')=='REACHED DESTINATION')?1:0,
				]);
				if($request->get('is_reached')==0){
					DB::table('cargo_status')->where('id',$id)
					->update([
						'type' =>$request->get('status_type'),
					]);	
				}
		Session::flash('message', 'Status updated successfully');
		return redirect('cargo_status');
	}

  
	
	public function destroy($id)
	{
		
		DB::table('cargo_status')->where('id',$id)->where('is_reached',0)->update(['deleted_at' => date('Y-m-d H:i:s')]);

		Session::flash('message', 'Status deleted successfully.');
		return redirect('cargo_status');
		
	}
	
	
	
	
}

