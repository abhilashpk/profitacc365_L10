<?php

namespace App\Http\Controllers;
use App\Repositories\Bank\BankInterface;
use App\Repositories\AccountMaster\AccountMasterInterface;
use App\Repositories\Acgroup\AcgroupInterface; 
use App\Repositories\Accategory\AccategoryInterface;
use App\Repositories\Currency\CurrencyInterface;
use App\Repositories\Department\DepartmentInterface;
use App\Repositories\Salesman\SalesmanInterface;
use App\Repositories\Terms\TermsInterface;
use App\Repositories\Country\CountryInterface;
use App\Repositories\Area\AreaInterface;
use App\Repositories\Forms\FormsInterface;


use Illuminate\Http\Request;

use App\Http\Requests;
use Session;
use Response;
use Input;
use App;
use Auth;
use DB;

class AccountMasterController extends Controller
{
	protected $group;
	protected $category;
	protected $accountmaster;
	protected $currency;
	protected $department;
	protected $salesman;
	protected $terms;
	protected $country;
	protected $area;
	protected $bank;
	protected $forms;
	protected $formData;
	
	public function __construct(AcgroupInterface $group, AccategoryInterface $category, BankInterface $bank, AccountMasterInterface $accountmaster, CurrencyInterface $currency, DepartmentInterface $department, SalesmanInterface $salesman, TermsInterface $terms, CountryInterface $country, AreaInterface $area,FormsInterface $forms) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		
		$this->middleware('auth');
		$this->group = $group;
		$this->category = $category;
		$this->accountmaster = $accountmaster;
		$this->currency = $currency;
		$this->department = $department;
		$this->salesman = $salesman;
		$this->terms = $terms;
		$this->country = $country;
		$this->area = $area;
		$this->bank = $bank;
		$this->forms = $forms; 
		$this->formData = $this->forms->getFormData('AM');
		
	}
	
	
    public function index() {
		//echo '<pre>';print_r($this->vatdata);exit;
		$data = array();
		$acmasters = [];//$this->accountmaster->accountMasterList();
		
		$country = $this->country->activeCountryList();
		$area = $this->area->activeAreaList();
		
		$cus_code = json_decode($this->ajax_getcode_cat($category='CUSTOMER'));
		$sup_code = json_decode($this->ajax_getcode_cat($category='SUPPLIER'));
		$cusid = $cuscat = $supid = $supcat = '';
		if($cus_code) {
			$cusid = $cus_code->code;
			$cuscat = $cus_code->category;
		}
		if($sup_code) {
			$supid = $sup_code->code;
			$supcat = $sup_code->category;
		}
		
		
		//CHECK DEPARTMENT.......
		if(Session::get('department')==1) { //if active...
			$deptid = Auth::user()->department_id;
			if($deptid!=0)
				$department = DB::table('department')->where('id',$deptid)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
			else {
				$department = DB::table('department')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
				$deptid = '';
			}
			$is_dept = true;
		} else {
			$is_dept = false;
			$department = [];
			$deptid = '';
		}
		
		
		//echo '<pre>';print_r($cus_code);exit;
		return view('body.accountmaster.index')
					->withAcmasters($acmasters)
					->withCountry($country)
					->withArea($area)
					->withCusid($cusid)
					->withSupid($supid)
					->withCcategory($cuscat)
					->withScategory($supcat)
					->withDepartment($department)
					->withDeptid($deptid)
					->withIsdept($is_dept)
					->withFormdata($this->formData)
					->withData($data);
	}
	
	public function ajaxPaging(Request $request)
	{
		$columns = array( 
                            0 =>'account_id', 
                            1 =>'master_name',
                            2=> 'group_name',
                            3=> 'category_name',
                            4=> 'cl_balance',
							5=> 'op_balance'
                        );
						
		$totalData = $this->accountmaster->accountMasterListCount();
            
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
		$search = (empty($request->input('search.value')))?null:$request->input('search.value');
        $dept = $request->input('dept');
		
		$acmasters = $this->accountmaster->accountMasterList('get', $start, $limit, $order, $dir, $search, $dept);
		
		if($search)
			$totalFiltered =  $this->accountmaster->accountMasterList('count', $start, $limit, $order, $dir, $search, $dept);
		
        $data = array();
        if(!empty($acmasters))
        {
            foreach ($acmasters as $row)
            {
                $view =  '"'.url('account_master/view/'.$row->id).'"';
				$edit =  '"'.url('account_master/edit/'.$row->id).'"';
               // $delete =  'funDelete("'.$row->id.'")';
			   $ishide=DB::table('account_master')->where('account_master.id',$row->id)->select('is_hide')->get();
				$res=$ishide[0]->is_hide;
				$hide=$res==1?'Hide':'Show';

			   $rid = $row->id;
                $nestedData['id'] = $row->id;
                $nestedData['account_id'] = $row->account_id;
				$nestedData['master_name'] = $row->master_name;
				$nestedData['group_name'] = $row->group_name;
				$nestedData['category_name'] = $row->category_name;
				$nestedData['cl_balance'] = $row->cl_balance;
				$nestedData['op_balance'] = $row->op_balance;
				$nestedData['issued_qty'] = $row->issued_qty;
				$nestedData['ishide'] = "<button class='btn btn-primary btn-xs getSts' >$hide</button>";
				$nestedData['view'] = "<p><button class='btn btn-primary btn-xs' onClick='location.href={$view}'>
												<span class='glyphicon glyphicon-eye-open'></span></button></p>";
												
                $nestedData['edit'] = "<p><button class='btn btn-primary btn-xs' onClick='location.href={$edit}'>
												<span class='glyphicon glyphicon-pencil'></span></button></p>";
				$nestedData['select'] = "<input type='checkbox' name='categoryid[]' class='chk-categoryid' value='{$rid}' />";	
											
                $data[] = $nestedData;

            }
        }
          
        $json_data = array(
                    "draw"            => intval($request->input('draw')),  
                    "recordsTotal"    => intval($totalData),  
                    "recordsFiltered" => intval($totalFiltered), 
                    "data"            => $data   
                    );
            
        echo json_encode($json_data);
	}
		
	public function add() {

		$data = $groups = array(); $accode = '';
		$acctype = $this->category->accountType();
	//	echo '<pre>';print_r($acctype);exit;
		$accategory = $this->category->activeAccategoryList();
		$currency = $this->currency->activeCurrencyList();
	$cid=$this->acsettings->bcurrency_id;
	$bcurrency=DB::table('currency')->where('id','!=',$cid)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name','code')->get();
		//$department = $this->department->activeDepartmentList();
		$salesman = $this->salesman->activeSalesmanList();
		$terms = $this->terms->activeTermsList();
		$country = $this->country->activeCountryList();
		$area = $this->area->activeAreaList();
		$banks = $this->bank->activeBankList();
		$obfrom_date = date('Y-m-d', strtotime($this->acsettings->from_date.' -1 day'));
		$obto_date = date('Y-m-d', strtotime($this->acsettings->to_date.' -1 day'));
		$category_id = (Session::has('category_id'))?Session::get('category_id'):'';
		$group_id = (Session::has('group_id'))?Session::get('group_id'):'';
		$actype_id = (Session::has('actype_id'))?Session::get('actype_id'):'';
		//echo '<pre>';print_r($bcurrency);exit;
		if($actype_id)
			$accategory = $this->category->getCategorybyType($actype_id);
		
		if($category_id)
			$groups = $this->group->getGroupbyCategory($category_id);
		
		if($group_id) {
			$codarr = json_decode( $this->ajax_getcode($group_id) );
			$accode = $codarr->code;
		}
		
		//CHECK DEPARTMENT.......
		if(Session::get('department')==1) { //if active...
			$deptid = Auth::user()->department_id;
			if($deptid!=0)
				$department = DB::table('department')->where('id',$deptid)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
			else {
				$department = DB::table('department')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
				$deptid = '';
			}
			$is_dept = true;
		} else {
			$is_dept = false;
			$department = [];
			$deptid = '';
		}
		
		return view('body.accountmaster.add')
					->withAcctype($acctype)
					->withCategory($accategory)
					->withCurrency($currency)
					->withBcurrency($bcurrency)
					->withDepartment($department)
					->withSalesman($salesman)
					->withTerms($terms)
					->withCountry($country)
					->withArea($area)
					->withBanks($banks)
					->withSettings($this->acsettings)
					->withObfrom($obfrom_date)
					->withObto($obto_date)
					->withActypid($actype_id)
					->withCatid($category_id)
					->withGpid($group_id)
					->withGroups($groups)
					->withAccode($accode)
					->withCatgy(Session::get('category'))
					->withActype(Session::get('actype'))
					->withTypetr(Session::get('typetr'))
					->withDeptid($deptid)
					->withIsdept($is_dept)
					->withData($data)
					->withFormdata($this->formData);
					
	}
	
	public function save() {
		//echo '<pre>';print_r(Input::all() );exit;
		Input::merge(['invoice_date' => $this->acsettings->from_date]);
		if( $this->accountmaster->create(Input::all()) )
			Session::flash('message', 'Account Master added successfully.');
		else
			Session::flash('error', 'Something went wrong, Account failed to add!');
		
		return redirect('account_master/add');
	}
	
	public function destroy($id)
	{
		$status = $this->accountmaster->check_account($id); 
		if($status) {
			$this->accountmaster->delete($id);
			Session::flash('message', 'Account Master deleted successfully.');
		} else {
			Session::flash('error', 'Account Master is already in use, you can\'t delete this!');
		}
		
		return redirect('account_master');
	}
		
	public function destroymaster()
	{
	    $ids = Input::get('ids');
	   // echo '<pre>';print_r($ids );
	    $idarr = explode(',', $ids);
	   // echo '<pre>';print_r($idarr);exit;
	    
	    foreach ($idarr as $id)
            {
	   	$status = $this->accountmaster->check_account($id); 
		if($status) {
			$this->accountmaster->delete($id);
			Session::flash('message', 'Account Master deleted successfully.');
		} else {
			Session::flash('error', 'Account Master is already in use, you can\'t delete this!');
		}}
		
		return redirect('account_master');
	}

    public function showAccount()
	{
		$ids = Input::get('ids');
	    //echo '<pre>';print_r($ids );
	    $idarr = explode(',', $ids);
	   // echo '<pre>';print_r($idarr);exit;
	    
	    foreach ($idarr as $id)
            {
	   // echo '<pre>';print_r($id );exit;
		DB::table('account_master')->where('id',$id)->update(['is_hide' => 0]);
		Session::flash('message', 'Account master show successfully.');
		return redirect('account_master');
			}
	}
	
	public function hideAccount()
	{
		$ids = Input::get('ids');
	   // echo '<pre>';print_r($ids );
	    $idarr = explode(',', $ids);
	   // echo '<pre>';print_r($idarr);exit;
	    
	    foreach ($idarr as $id)
            {
	   // echo '<pre>';print_r($id );exit;
		DB::table('account_master')->where('id',$id)->update(['is_hide' => 1]);
		Session::flash('message', 'Account master hide successfully.');
		return redirect('account_master');
			}
	}
	
	public function checkcode() {

		$check = $this->accountmaster->check_item_code(Input::get('item_code'), Input::get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
	
	public function checkdesc() {

		$check = $this->accountmaster->check_item_description(Input::get('description'), Input::get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
	
	public function checkname() {

		$check = $this->accountmaster->check_name(Input::get('master_name'), Input::get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
	
	public function checkacno() {

		$check = $this->accountmaster->check_acno(Input::get('account_no'), Input::get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
	
	public function edit($id) { 

		$data = array();
		$acmasterrow = $this->accountmaster->find($id);
		$acctype = $this->category->accountType();
		$currency = $this->currency->activeCurrencyList();
		$cid=$this->acsettings->bcurrency_id;
	    $bcurrency=DB::table('currency')->where('id','!=',$cid)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name','code')->get();
        $department = $this->department->activeDepartmentList();
		$salesman = $this->salesman->activeSalesmanList();
		$terms = $this->terms->activeTermsList();
		$country = $this->country->activeCountryList();
		$area = $this->area->activeAreaList();
		$type = $this->category->find($acmasterrow->account_category_id);
		$accategory = $this->category->getCategorybyType($type->parent_id);
		$groups = $this->group->getGroupbyCategory($acmasterrow->account_category_id);
		$opening_bals = $this->accountmaster->getOpeningBalance($id);	//echo '<pre>';print_r($opening_bals);exit;
		$banks = $this->bank->activeBankList();
		$obfrom_date = date('Y-m-d', strtotime($this->acsettings->from_date.' -1 day'));
		$obto_date = date('Y-m-d', strtotime($this->acsettings->to_date.' -1 day'));
		return view('body.accountmaster.edit')
					->withMasterrow($acmasterrow)
					->withAcctype($acctype)
					->withCategory($accategory)
					->withCurrency($currency)
					->withBcurrency($bcurrency)
					->withDepartment($department)
					->withSalesman($salesman)
					->withTerms($terms)
					->withTypeid($type->parent_id)
					->withCountry($country)
					->withArea($area)
					->withGroups($groups)
					->withOpnbalance($opening_bals)
					->withBanks($banks)
					->withSettings($this->acsettings)
					->withObfrom($obfrom_date)
					->withObto($obto_date)
					->withData($data);
	}
	
	public function update($id)
	{
		Input::merge(['invoice_date' => $this->acsettings->from_date]);
		if( $this->accountmaster->update($id, Input::all()) )
			Session::flash('message', 'Account Master updated successfully');
		else
			Session::flash('error', 'Something went wrong, Account failed to update!');
		
		return redirect('account_master');
	}
	
	public function ajax_getcode_cat($category)
	{
		$group = $this->group->getGroupCode($category);
		if($group) {
			$row = $this->accountmaster->getGroupCode($category);
			if($row)
				$no = intval(preg_replace('/[^0-9]+/', '', $row->account_id), 10);
			else 
				$no = 0;
			
			$no++;
			return json_encode(array(
								'code' => strtoupper($group->code).''.$no,
								'category' => $group->category
							));
		} else
			return json_encode(array());
		//return $code = strtoupper($group->code).''.$no;

	}
	
	public function ajax_getcode($group_id)
	{
		$group = $this->group->getGroupCodeById($group_id);
		$row = $this->accountmaster->getLastId($group_id);
		$id = ($row)?$row->id+1:1;
		return json_encode(array(
							'code' => 'ACM'.($id),
							'category' => $group->category,
							'trtype' => $group->trtype
						));
		//return $code = strtoupper($group->code).''.$no;

	}
	
	public function ajax_getcodeOld($group_id)
	{
		$group = $this->group->getGroupCodeById($group_id);
		$row = $this->accountmaster->getGroupCodeById($group_id);
		if($row)
			$no = intval(preg_replace('/[^1-9]+/', '', $row->account_id), 10);
		else 
			$no = 0;
		
		$no++;
		return json_encode(array(
							'code' => strtoupper($group->code).''.$no,
							'category' => $group->category,
							'trtype' => $group->trtype
						));
		//return $code = strtoupper($group->code).''.$no;

	}
	
	public function show($id) { 

		$data = array();
		$acmasterrow = $this->accountmaster->accountMasterView($id);
		//echo '<pre>';print_r($acmasterrow);exit;
		return view('body.accountmaster.view')
					->withMasterrow($acmasterrow)
					->withData($data);
	}
	
	public function getAccount($code) {
		
		 return $this->accountmaster->getAccountByGroup($code);

	}
	
	public function getAccountList($code,$num=null) {
		
		$accounts = $this->accountmaster->getAccountByGroup($code);
		return view('body.accountmaster.accountlist')
					->withNum($num)
					->withAccounts($accounts);

	}
	
	public function getAllAccount($num) {
		
		$accounts = $this->accountmaster->activeAccountList();
		return view('body.accountmaster.accountlist')
					->withNum($num)
					->withAccounts($accounts);
					
	}
	
	public function getAccountAll($num=null) {
		
		$accounts = [];//$this->accountmaster->activeAccountList();
		$country = $this->country->activeCountryList();
		$area = $this->area->activeAreaList();
		$acctype = $this->category->accountType();
		
		$accounts = $this->accountmaster->activeAccountList();
		return view('body.accountmaster.accountlistall')
					->withNum($num)
					->withCountry($country)
					->withAcctype($acctype)
					->withArea($area)
					->withNtype(Input::get('type'))
					->withAccounts($accounts);
					
	}
	
	public function getCustomAccount($num) {
		
		$accounts = $this->accountmaster->getCustomAccountList();
		return view('body.accountmaster.accountlist')
					->withNum($num)
					->withAccounts($accounts);
					
	}
	
	public function checkRefno() {

		$check = $this->accountmaster->check_refno(Input::get('refno'),Input::get('acid'));
		echo $isAvailable = ($check) ? false : true;
	}
	
	public function checkTrndate() {

		//$check = $this->accountmaster->check_refno(Input::get('refno'));
		//echo $isAvailable = ($check) ? false : true;
	//	echo $this->acsettings->from_date.' <= '.date('Y-m-d',strtotime(Input::get('trndate')));exit;
		if($this->acsettings->from_date <= date('Y-m-d',strtotime(Input::get('trndate'))))
		    echo false;
		else
		    echo true;
	}
	
	public function checkChequeno() {

		$check = $this->accountmaster->check_chequeno(Input::get('chqno'), Input::get('bank_id'), Input::get('ac_id'), Input::get('type')); 
		echo $isAvailable = ($check) ? false : true;
	}
	public function getbudgincAccounts($num,$dpt=null) {
		
		$accounts = $this->accountmaster->activeAccountList($dpt); //$this->accountmaster->activebudgIncAccountList($dpt);
	//	echo '<pre>';print_r($accounts);exit;
		$country = $this->country->activeCountryList();
		$area = $this->area->activeAreaList();
		$acctype = $this->category->accountType();
		//$accategory = $this->category->activeAccategoryList();
		
		//CHECK DEPARTMENT.......
		if(Session::get('department')==1) { //if active...
			$deptid = Auth::user()->department_id;
			if($deptid!=0)
				$departments = DB::table('department')->where('id',$deptid)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
			else {
				$departments = DB::table('department')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
				$deptid = $departments[0]->id;
			}
			$is_dept = true;
		} else {
			$is_dept = false;
			$departments = [];
			$deptid = '';
		}
		
		return view('body.accountmaster.accountsbudgetinc')
					->withNum($num)
					->withCountry($country)
					->withAcctype($acctype)
					->withArea($area)
					->withDepartments($departments)
					->withIsdept($is_dept)
					->withAccounts($accounts);
					
							
	}
	
	
	public function getbudgAccounts($num,$dpt=null) {
		
		$accounts = $this->accountmaster->activeAccountList($dpt);//$this->accountmaster->activebudgAccountList($dpt);
		$country = $this->country->activeCountryList();
		$area = $this->area->activeAreaList();
		$acctype = $this->category->accountType();
		//$accategory = $this->category->activeAccategoryList();
		
		//CHECK DEPARTMENT.......
		if(Session::get('department')==1) { //if active...
			$deptid = Auth::user()->department_id;
			if($deptid!=0)
				$departments = DB::table('department')->where('id',$deptid)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
			else {
				$departments = DB::table('department')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
				$deptid = $departments[0]->id;
			}
			$is_dept = true;
		} else {
			$is_dept = false;
			$departments = [];
			$deptid = '';
		}
		
		return view('body.accountmaster.accountsbudget')
					->withNum($num)
					->withCountry($country)
					->withAcctype($acctype)
					->withArea($area)
					->withDepartments($departments)
					->withIsdept($is_dept)
					->withAccounts($accounts);
					
	}
	public function getAccounts($num=null,$dpt=null) {
		
		$accounts = [];//$this->accountmaster->activeAccountList($dpt);
		$country = $this->country->activeCountryList();
		$area = $this->area->activeAreaList();
		$acctype = $this->category->accountType();
		//$accategory = $this->category->activeAccategoryList();
		
		//CHECK DEPARTMENT.......
		if(Session::get('department')==1) { //if active...
			$deptid = Auth::user()->department_id;
			if($deptid!=0)
				$departments = DB::table('department')->where('id',$deptid)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
			else {
				$departments = DB::table('department')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
				$deptid = $departments[0]->id;
			}
			$is_dept = true;
		} else {
			$is_dept = false;
			$departments = [];
			$deptid = '';
		}
		
		return view('body.accountmaster.accounts')
					->withNum($num)
					->withCountry($country)
					->withAcctype($acctype)
					->withArea($area)
					->withDepartments($departments)
					->withIsdept($is_dept)
					->withFormdata($this->formData)
					->withNtype(Input::get('type'))
					->withAccounts($accounts);
					
	}

	public function getExpenseac($num) {
		
		$accounts = $this->accountmaster->getExpenseAccount();
		return view('body.accountmaster.stockac')
					->withNum($num)
					->withAccounts($accounts);
					
	}
	
	public function ajaxSave() {
		
		$as = $this->accountmaster->ajaxCreate(Input::all());
		return $as;
			
	}
	
	public function ajaxSaveacc() {
		
		$as = $this->accountmaster->ajaxCreateAcc(Input::all());
		return $as;
	}
	
	public function getAjaxAccount(Request $request) {
		
		
		$search = $request->get('term','');
		$category = $request->get('category');
		
		$accounts = $this->accountmaster->getAccountSearch($search,$category);
		
		$data=array();
        foreach ($accounts as $row) {
            $data[]=array('value'=>$row->master_name, 'id' => $row->id);
        }
        if(count($data))
             return $data;
        else
            return ['value'=>'Account not found','id'=>''];
	}
	
	
	public function budgetEntry() {
		
		$resultrow = DB::table('account_master')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','account_id','master_name')->get();
		//echo '<pre>';print_r($resultrow);exit;
		return view('body.accountmaster.budgetentry')
					->withAccounts($resultrow);
	}
	
	public function budgetEntrySave(Request $request) {
		
		echo '<pre>';print_r($request->all());exit;
	}
	
	
}
