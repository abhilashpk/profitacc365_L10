<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Input;
use Session;
use Response;
use DB;
use App;

class ContractTypeController extends Controller
{
	
	public function __construct() {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->middleware('auth');
		
	}
	
	public function index() {
		$contract = DB::table('contract_type')->where('deleted_at',null)->get();
		return view('body.contracttype.index')
					->withContract($contract);
	}
	
	public function add() {

		$data = array();
		return view('body.contracttype.add')
					->withData($data);
	}
	
	public function save() {
		try {
			DB::table('contract_type')
				->insert([
					'code' => Input::get('code'),
					'name' => Input::get('name'),
					'description' => Input::get('description')
				]);
				
			Session::flash('message', 'Contract type added successfully.');
			return redirect('contract_type/add');
		} catch(ValidationException $e) { 
			return Redirect::to('contract_type/add')->withErrors($e->getErrors());
		}
	}
	
	public function edit($id) { 

		$contract = DB::table('contract_type')->where('id',$id)->first();
						
		return view('body.contracttype.edit')
					->withProw($contract);
	}
	
	public function update($id)
	{
		DB::table('contract_type')->where('id',$id)
				->update([
					'name' => Input::get('name'),
					'description' => Input::get('description')
				]);
		Session::flash('message', 'Contract type updated successfully');
		return redirect('contract_type');
	}
	
	public function destroy($id)
	{
		DB::table('contract_type')->where('id',$id)->update(['deleted_at' => date('Y-m-d H:i:s')]);
		Session::flash('message', 'Contract type deleted successfully.');
		return redirect('contract_type');
	}
	
	public function checkCode(Request $request) {

		$count = DB::table('contract_type')->where('deleted_at',null)->where('code',$request->get('code'))->count();
		echo json_encode(array('valid' => ($count) ? false : true));
	}
	
}
