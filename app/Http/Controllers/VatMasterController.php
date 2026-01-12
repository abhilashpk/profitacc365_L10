<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\VatMaster\VatMasterInterface;

use App\Http\Requests;
use Session;
use Response;
use DB;
use App;

class VatMasterController extends Controller
{
    protected $vat_master;
	
	public function __construct(VatMasterInterface $vat_master) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->vat_master = $vat_master;
		$this->middleware('auth');
	}
	
	public function index() {
		$data = array();
		$vat_masters = $this->vat_master->all();
		return view('body.vatmaster.index')
					->withVatmasters($vat_masters)
					->withData($data);
	}
	
	public function add() {

		$data = array();
		
		if(Session::get('department')==1) {
			$departments = DB::table('department')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
			$is_dept = true;
		} else {
			$departments = $deptaccounts = []; $is_dept = false;
		}
		
		$accounts = $this->vat_master->getVatAccounts(); //echo '<pre>';print_r($accounts);exit;
		return view('body.vatmaster.add')
					->withAccounts($accounts)
					->withDepartments($departments)
					->withIsdept($is_dept)
					->withJ(0)
					->withN(0)
					->withData($data);
	}
	
	public function save(Request $request) {
		try { 
			$this->vat_master->create($request->all());
			Session::flash('message', 'Vat Master added successfully.');
			return redirect('vat_master');
		} catch(ValidationException $e) { 
			return Redirect::to('vat_master/add')->withErrors($e->getErrors());
		}
	}
	
	public function edit($id) { 

		$data = array();
		$accounts = $this->vat_master->getVatAccounts();
		
		if(Session::get('department')==1) {
			$departments = DB::table('department')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
			$deptaccounts = DB::table('vat_master')->where('vat_master.id', $id)
								->join('vat_department', 'vat_department.vatmaster_id', '=', 'vat_master.id')
								->leftJoin('account_master AS M1', 'M1.id', '=', 'vat_department.collection_account')
								->leftJoin('account_master AS M2', 'M2.id', '=', 'vat_department.payment_account')
								->leftJoin('account_master AS M3', 'M3.id', '=', 'vat_department.expense_account')
								->leftJoin('account_master AS M4', 'M4.id', '=', 'vat_department.vatinput_import')
								->leftJoin('account_master AS M5', 'M5.id', '=', 'vat_department.vatoutput_import')
								->select('vat_master.code','vat_master.name','vat_master.percentage',
										'M1.master_name AS coll_acc_name','M2.master_name AS pymt_acc_name',
										'M3.master_name AS exp_acc_name','M4.master_name AS vatip_imprt_name',
										'M5.master_name AS vatop_imprt_name','vat_department.*')
								->get(); 
					
			$is_dept = true; $vatRow = $this->vat_master->find($id);
			
		} else {
			$departments = $deptaccounts = []; $is_dept = false;
			
			$vatRow = DB::table('vat_master')->where('vat_master.id', $id)
								->leftJoin('account_master AS M1', 'M1.id', '=', 'vat_master.collection_account')
								->leftJoin('account_master AS M2', 'M2.id', '=', 'vat_master.payment_account')
								->leftJoin('account_master AS M3', 'M3.id', '=', 'vat_master.expense_account')
								->leftJoin('account_master AS M4', 'M4.id', '=', 'vat_master.vatinput_import')
								->leftJoin('account_master AS M5', 'M5.id', '=', 'vat_master.vatoutput_import')
								->select('vat_master.*',
										'M1.master_name AS coll_acc_name','M2.master_name AS pymt_acc_name',
										'M3.master_name AS exp_acc_name','M4.master_name AS vatip_imprt_name',
										'M5.master_name AS vatop_imprt_name')
								->first(); 
			
		}
		//echo '<pre>';print_r($deptaccounts);exit;
		return view('body.vatmaster.edit')
					->withVatrow($vatRow)
					->withAccounts($accounts)
					->withDepartments($departments)
					->withDeptac($deptaccounts)
					->withIsdept($is_dept)
					->withJ(0)
					->withN(0)
					->withData($data);
	}
	
	public function update($id, Request $request)
	{	//echo '<pre>';print_r($request->all());exit;
		$this->vat_master->update($id, $request->all());
		Session::flash('message', 'Vat Master updated successfully');
		return redirect('vat_master');
	}
	
	public function destroy($id)
	{
		$this->vat_master->delete($id);
		//check vat_master name is already in use.........
		// code here ********************************
		Session::flash('message', 'Vat Master deleted successfully.');
		return redirect('vat_master');
	}
	
	public function checkcode(Request $request) {

		$check = $this->vat_master->check_vat_master_code($request->get('code'), $request->get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
	
	public function checkname(Request $request) {

		$check = $this->vat_master->check_vat_master_name($request->get('name'), $request->get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
}

