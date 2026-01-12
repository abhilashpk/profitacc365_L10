<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\OtherAccountSetting\OtherAccountSettingInterface; 

use App\Http\Requests;
use Session;
use Redirect;
use DB;
use App;

class OtherAccountSettingController extends Controller
{
    protected $other_account;
	
	public function __construct(OtherAccountSettingInterface $other_account) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->other_account = $other_account;
		$this->middleware('auth');
		
	}
	
	public function index() { 
		$data = array();
		$accounts = $this->other_account->getOtherAccountSetting();
		$cas = DB::table('voucher_account')
					->join('account_master', 'account_master.id', '=', 'voucher_account.account_id')
					->select('account_master.account_id as code','account_master.master_name','voucher_account.*')
					->get(); 
					
		if(Session::get('department')==1) {
			$departments = DB::table('department')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
			$deptaccounts = DB::table('department_accounts')
								->leftJoin('account_master AS M1', 'M1.id', '=', 'department_accounts.stock_acid')
								->leftJoin('account_master AS M2', 'M2.id', '=', 'department_accounts.cost_acid')
								->leftJoin('account_master AS M3', 'M3.id', '=', 'department_accounts.costdif_acid')
								->leftJoin('account_master AS M4', 'M4.id', '=', 'department_accounts.purdis_acid')
								->leftJoin('account_master AS M5', 'M5.id', '=', 'department_accounts.saledis_acid')
								->leftJoin('account_master AS M6', 'M6.id', '=', 'department_accounts.stock_excess_acid')
								->leftJoin('account_master AS M7', 'M7.id', '=', 'department_accounts.stock_shortage_acid')
								->select('M1.account_id AS stock_acid','M1.master_name AS stock_acname','M2.master_name AS cost_acname','department_accounts.*',
										'M3.master_name AS costdif_acname','M4.master_name AS purdis_acname','M5.master_name AS saledis_acname',
										'M6.master_name AS stockexcs_acname','M7.master_name AS stockshrtg_acname')
								->get(); 
					
			$is_dept = true;
		} else {
			$departments = $deptaccounts = []; $is_dept = false;
		}
		//echo '<pre>';print_r($deptaccounts);exit;
		return view('body.otheraccountsetting.index')
					->withAccounts($accounts)
					->withCas($cas)
					->withDepartments($departments)
					->withDeptac($deptaccounts)
					->withIsdept($is_dept)
					->withJ(0)
					->withN(0)
					->withData($data);
	}
	
	public function update(Request $request)
	{ 	//echo '<pre>';print_r($request->all());exit;
		$this->other_account->update($id=null,$request->all());
		Session::flash('message', 'Other Account settings updated successfully');
		return redirect('other_account_setting');
	}
}

