<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\VoucherType\VoucherTypeInterface;
use App\Repositories\Currency\CurrencyInterface;
use App\Repositories\Department\DepartmentInterface;
use App\Repositories\AccountMaster\AccountMasterInterface;
use App\Repositories\AccountSetting\AccountSettingInterface;
use App\Repositories\OtherAccountSetting\OtherAccountSettingInterface; 
use App\Repositories\VatMaster\VatMasterInterface;

use App\Http\Requests;
use Input;
use Session;
use Redirect;
use App;
use DB;

class AccountSettingController extends Controller
{
    protected $voucher_type;
	protected $currency;
	protected $department;
	protected $accountmaster;
	protected $accountsetting;
	protected $other_account;
	protected $vat_master;
	
	public function __construct(VatMasterInterface $vat_master, VoucherTypeInterface $voucher_type, CurrencyInterface $currency, DepartmentInterface $department, AccountMasterInterface $accountmaster,AccountSettingInterface $accountsetting,OtherAccountSettingInterface $other_account) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		
		$this->middleware('auth');
		$this->voucher_type = $voucher_type;
		$this->currency = $currency;
		$this->department = $department;
		$this->accountmaster = $accountmaster;
		$this->accountsetting = $accountsetting;
		$this->other_account = $other_account;
		$this->vat_master = $vat_master;
		
	}
	
	public function index() { 
		$data = array();
		$account_settings = $this->accountsetting->getAccountSettingsList();
		return view('body.accountsetting.index')
					->withAccsettings($account_settings)
					->withData($data);
	}
	
	public function add() { 
		$data = array();
		$voucher_type = $this->voucher_type->getVoucherType();
		//echo '<pre>';print_r($voucher_type);exit;
		$department = $this->department->activeDepartmentList();
		$accounts = $this->accountmaster->activeAccountList();
		$cashacs = $this->accountmaster->getAccountByGroup('CASH');
		$bankacs = $this->accountmaster->getAccountByGroup('BANK');

		return view('body.accountsetting.add')
					->withVouchertype($voucher_type)
					->withDepartment($department)
					->withAccounts($accounts)
					->withCashacs($cashacs)
					->withBankacs($bankacs)
					->withData($data);
	}
	
	public function save() {
		//echo '<pre>';print_r(Input::all());exit;
		$this->accountsetting->create(Input::all());
		Session::flash('message', 'Account Setting added successfully.');
		return redirect('account_setting');
	}
	
	public function destroy($id,$type)
	{
		$status = $this->accountsetting->check_settings($id,$type);//exit;
		if($status) {
			$this->accountsetting->delete($id);
			Session::flash('message', 'Account Setting deleted successfully.');
		} else {
			Session::flash('error', 'Voucher is already in use, you can\'t delete this!');
		}		
		
		//Session::flash('message', 'Account Setting deleted successfully.');
		return redirect('account_setting');
	}
	
	public function checkname() {

		$check = $this->accountsetting->check_name(Input::get('voucher_name'), Input::get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
	
	public function edit($id) { 

		$data = array(); 
		$voucher_type = $this->voucher_type->getVoucherType();
		$department = $this->department->activeDepartmentList();
		$settings = $this->accountsetting->find($id);
		
		$accounts = $this->accountmaster->activeAccountList();
		$cashacs = $this->accountmaster->getAccountByGroup('CASH');
		$bankacs = $this->accountmaster->getAccountByGroup('BANK');
		
		if($settings->voucher_type_id==9) {
			$pdcs = $this->accountmaster->getAccountByGroup('PDCR');
		} else if($settings->voucher_type_id==10) {
			$pdcs = $this->accountmaster->getAccountByGroup('PDCI');
		} else
			$pdcs = '';
		return view('body.accountsetting.edit')
					->withVouchertype($voucher_type)
					->withDepartment($department)
					->withSettingrow($settings)
					->withAccounts($accounts)
					->withCashacs($cashacs)
					->withBankacs($bankacs)
					->withPdcs($pdcs)
					->withData($data);
	}
	
	public function update($id)
	{ //echo '<pre>';print_r(Input::all());exit;
		$this->accountsetting->update($id, Input::all());
		Session::flash('message', 'Account Setting updated successfully');
		return redirect('account_setting');
	}
	
	public function checkAccounts() {
	    
	    $accounts = $this->other_account->getOtherAccountSettingCheck();
		$cas = DB::table('voucher_account')
					->join('account_master', 'account_master.id', '=', 'voucher_account.account_id')
					->where('account_master.status',1)->where('account_master.deleted_at','0000-00-00 00:00:00')
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
		
		
		$vataccounts = $this->vat_master->getVatAccounts();
		$vatrow = DB::table('vat_master')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id')->first();
		$vatid = $vatrow->id;
		
		if(Session::get('department')==1) {
			$departments = DB::table('department')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
			$deptaccountsvat = DB::table('vat_master')->where('vat_master.id', $vatid)
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
					
			$is_dept = true; $vatRow = $this->vat_master->find($vatid);
			
		} else {
			$departments = $deptaccountsvat = []; $is_dept = false;
			
			$vatRow = DB::table('vat_master')->where('vat_master.id', $vatid)
			                    ->leftJoin('account_master AS M1', function($join) {
        							$join->on('M1.id','=','vat_master.collection_account');
        							$join->where('M1.status','=',1);
        							$join->where('M1.deleted_at','=','0000-00-00 00:00:00');
        						})
								->leftJoin('account_master AS M2', function($join) {
        							$join->on('M2.id','=','vat_master.payment_account');
        							$join->where('M2.status','=',1);
        							$join->where('M2.deleted_at','=','0000-00-00 00:00:00');
        						})
        						->leftJoin('account_master AS M3', function($join) {
        							$join->on('M3.id','=','vat_master.expense_account');
        							$join->where('M3.status','=',1);
        							$join->where('M3.deleted_at','=','0000-00-00 00:00:00');
        						})
        						->leftJoin('account_master AS M4', function($join) {
        							$join->on('M4.id','=','vat_master.vatinput_import');
        							$join->where('M4.status','=',1);
        							$join->where('M4.deleted_at','=','0000-00-00 00:00:00');
        						})
        						->leftJoin('account_master AS M5', function($join) {
        							$join->on('M5.id','=','vat_master.vatoutput_import');
        							$join->where('M5.status','=',1);
        							$join->where('M5.deleted_at','=','0000-00-00 00:00:00');
        						})
								->select('vat_master.*',
										'M1.master_name AS coll_acc_name','M2.master_name AS pymt_acc_name',
										'M3.master_name AS exp_acc_name','M4.master_name AS vatip_imprt_name',
										'M5.master_name AS vatop_imprt_name')
								->first(); 
			
		}
		
		
		//echo '<pre>';print_r($deptaccounts);exit;
		return view('body.accountsetting.checkaccounts')
					->withAccounts($accounts)
					->withCas($cas)
					->withDepartments($departments)
					->withDeptac($deptaccounts)
					->withIsdept($is_dept)
					->withJ(0)
					->withN(0)
					->withVatrow($vatRow)
					->withVataccounts($vataccounts)
					->withDeptacvat($deptaccountsvat);

	}
	

}
