<?php

namespace App\Http\Controllers;
use App\Repositories\AccountMaster\AccountMasterInterface;
use App\Exports\SimpleArrayExport;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Http\Request;

use App\Http\Requests;
use Session;
use Response;
use Input;
use App;
//use Excel;
use DB;
use Date;
use DateTime;
use Auth;
use Mail;


class AccountEnquiryController extends Controller
{

	protected $accountmaster;
	protected $statement;
	
	public function __construct(AccountMasterInterface $accountmaster) {

		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		
		$this->accountmaster = $accountmaster;
		$this->middleware('auth');
		$this->statement = DB::table('parameter2')->where('keyname', 'mod_statement_det')->where('status',1)->select('is_active')->first();
		//print_r($this->statement);exit;
	}
	
    public function index() {
		
		/* $actransactions = $this->accountmaster->getSelectedAccountsCbUlitily('get', 0, 10, 0, 'ASC', '', '');
		$this->makeSummaryAcTrans($this->makeTreeTrans($actransactions));
		echo '<pre>';print_r($actransactions);exit; */
		
		$data = array();
		$acmasters = [];//$this->accountmaster->accountMasterList();
		$jobs = DB::table('jobmaster')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->where('is_salary_job',0)->get();
		$currency = DB::table('currency')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();//echo '<pre>';print_r($currency);exit;
		
		
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
		
		$category = DB::table('account_category')->where('parent_id','!=',0)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		$groups = DB::table('account_group')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		$salesman = DB::table('salesman')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
		
		return view('body.accountenquiry.index')
					->withAcmasters($acmasters)
					->withSettings($this->acsettings)
					->withJobs($jobs)
					->withCurrency($currency)
					->withDepartment($department)
					->withDeptid($deptid)
					->withIsdept($is_dept)
					->withGroups($groups)
					->withCategory($category)
					->withSalesman($salesman)
					->withData($data);
	}
	
	public function ajaxPaging(Request $request)
	{
		if(Session::get('department')==1) {
			$columns = array( 
								0 =>'account_master.id',
								1 => 'account_id', 
								2 => 'master_name',
								3 => 'group_name',
								4 => 'category_name',
								5 => 'department',
								6 => 'cl_balance',
								7 => 'op_balance'
							);
		} else {
			$columns = array( 
								0 =>'account_master.id',
								1 => 'account_id', 
								2 => 'master_name',
								3 => 'group_name',
								4 => 'category_name',
								5 => 'cl_balance',
								6 => 'op_balance'
							);
		}
						
		$totalData = $this->accountmaster->accountMasterListCount();
            
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
		$search = (empty($request->input('search.value')))?null:$request->input('search.value');
		$dept = $request->input('dept');
		
		//RUNING CLOSING BALANCE UTILITY....
		$this->makeSummaryAcTrans($this->makeTreeTrans( $this->accountmaster->updateUtility('CB')) );
		//$actransactions = $this->accountmaster->getSelectedAccountsCbUlitily('get', $start, $limit, $order, $dir, $search, $dept);
		//$this->makeSummaryAcTrans($this->makeTreeTrans($actransactions));
		//echo '<pre>';print_r($actransactions);exit;
		
		$acmasters = $this->accountmaster->accountMasterList('get', $start, $limit, $order, $dir, $search, $dept);
		
		if($search)
			$totalFiltered =  $this->accountmaster->accountMasterList('count', $start, $limit, $order, $dir, $search, $dept);
		
        $data = array();
        if(!empty($acmasters))
        {
            foreach ($acmasters as $row)
            {
                $view =  '"'.url('account_master/view/'.$row->id).'"';
				$opt =  $row->id;
				
				$nestedData['opt'] = "<input type='radio' name='account' class='opt-account' value='{$opt}'/>";
                
				$nestedData['id'] = $row->id;
                $nestedData['account_id'] = $row->account_id;
				$nestedData['master_name'] = $row->master_name;
				$nestedData['group_name'] = $row->group_name;
				$nestedData['category_name'] = $row->category_name;
				$nestedData['department'] = $row->department;
				$nestedData['cl_balance'] = $row->cl_balance;
				$nestedData['op_balance'] = $row->op_balance;
				//$nestedData['issued_qty'] = $row->issued_qty;
				$nestedData['view'] = "<p><button class='btn btn-primary btn-xs' onClick='location.href={$view}'>
												<span class='glyphicon glyphicon-eye-open'></span></button></p>";
												
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
	
	public function indexCus() {
		
		/* $actransactions = $this->accountmaster->getSelectedAccountsCbUlitily('get', 0, 10, 0, 'ASC', '', '');
		$this->makeSummaryAcTrans($this->makeTreeTrans($actransactions));
		echo '<pre>';print_r($actransactions);exit; */
		
		$data = array();
		$acmasters = [];//$this->accountmaster->accountMasterList();
		$jobs = DB::table('jobmaster')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->where('is_salary_job',0)->get();
		$currency = DB::table('currency')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();//echo '<pre>';print_r($currency);exit;
		
		
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
		
		$category = DB::table('account_category')->where('parent_id','!=',0)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		$groups = DB::table('account_group')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		$salesman = DB::table('salesman')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
		
		return view('body.accountenquiry.indexcus')
					->withAcmasters($acmasters)
					->withSettings($this->acsettings)
					->withJobs($jobs)
					->withCurrency($currency)
					->withDepartment($department)
					->withDeptid($deptid)
					->withIsdept($is_dept)
					->withGroups($groups)
					->withCategory($category)
					->withSalesman($salesman)
					->withData($data);
	}

	public function ajaxCusPaging(Request $request)
	{
		if(Session::get('department')==1) {
			$columns = array( 
								0 =>'account_master.id',
								1 => 'account_id', 
								2 => 'master_name',
								3 => 'group_name',
								4 => 'category_name',
								5 => 'department',
								6 => 'cl_balance',
								7 => 'op_balance'
							);
		} else {
			$columns = array( 
								0 =>'account_master.id',
								1 => 'account_id', 
								2 => 'master_name',
								3 => 'group_name',
								4 => 'category_name',
								5 => 'cl_balance',
								6 => 'op_balance'
							);
		}
						
		$totalData = $this->accountmaster->accountMasterCusListCount();
            
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
		$search = (empty($request->input('search.value')))?null:$request->input('search.value');
		$dept = $request->input('dept');
		
		//RUNING CLOSING BALANCE UTILITY....
		$this->makeSummaryAcTrans($this->makeTreeTrans( $this->accountmaster->updateUtility('CB')) );
		//$actransactions = $this->accountmaster->getSelectedAccountsCbUlitily('get', $start, $limit, $order, $dir, $search, $dept);
		//$this->makeSummaryAcTrans($this->makeTreeTrans($actransactions));
		//echo '<pre>';print_r($actransactions);exit;
		
		$acmasters = $this->accountmaster->accountMasterCusList('get', $start, $limit, $order, $dir, $search, $dept);
		
		if($search)
			$totalFiltered =  $this->accountmaster->accountMasterCusList('count', $start, $limit, $order, $dir, $search, $dept);
		
        $data = array();
        if(!empty($acmasters))
        {
            foreach ($acmasters as $row)
            {
                $view =  '"'.url('account_master/view/'.$row->id).'"';
				$opt =  $row->id;
				
				$nestedData['opt'] = "<input type='radio' name='account' class='opt-account' value='{$opt}'/>";
                
				$nestedData['id'] = $row->id;
                $nestedData['account_id'] = $row->account_id;
				$nestedData['master_name'] = $row->master_name;
				$nestedData['group_name'] = $row->group_name;
				$nestedData['category_name'] = $row->category_name;
				$nestedData['department'] = $row->department;
				$nestedData['cl_balance'] = $row->cl_balance;
				$nestedData['op_balance'] = $row->op_balance;
				$nestedData['issued_qty'] = $row->issued_qty;
				$nestedData['view'] = "<p><button class='btn btn-primary btn-xs' onClick='location.href={$view}'>
												<span class='glyphicon glyphicon-eye-open'></span></button></p>";
												
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
	public function indexSup() {
		
		/* $actransactions = $this->accountmaster->getSelectedAccountsCbUlitily('get', 0, 10, 0, 'ASC', '', '');
		$this->makeSummaryAcTrans($this->makeTreeTrans($actransactions));
		echo '<pre>';print_r($actransactions);exit; */
		
		$data = array();
		$acmasters = [];//$this->accountmaster->accountMasterList();
		$jobs = DB::table('jobmaster')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->where('is_salary_job',0)->get();
		$currency = DB::table('currency')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();//echo '<pre>';print_r($currency);exit;
		
		
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
		
		$category = DB::table('account_category')->where('parent_id','!=',0)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		$groups = DB::table('account_group')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		$salesman = DB::table('salesman')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
		
		return view('body.accountenquiry.indexsup')
					->withAcmasters($acmasters)
					->withSettings($this->acsettings)
					->withJobs($jobs)
					->withCurrency($currency)
					->withDepartment($department)
					->withDeptid($deptid)
					->withIsdept($is_dept)
					->withGroups($groups)
					->withCategory($category)
					->withSalesman($salesman)
					->withData($data);
	}

	public function ajaxSupPaging(Request $request)
	{
		if(Session::get('department')==1) {
			$columns = array( 
								0 =>'account_master.id',
								1 => 'account_id', 
								2 => 'master_name',
								3 => 'group_name',
								4 => 'category_name',
								5 => 'department',
								6 => 'cl_balance',
								7 => 'op_balance'
							);
		} else {
			$columns = array( 
								0 =>'account_master.id',
								1 => 'account_id', 
								2 => 'master_name',
								3 => 'group_name',
								4 => 'category_name',
								5 => 'cl_balance',
								6 => 'op_balance'
							);
		}
						
		$totalData = $this->accountmaster->accountMasterSupListCount();
            
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
		$search = (empty($request->input('search.value')))?null:$request->input('search.value');
		$dept = $request->input('dept');
		
		//RUNING CLOSING BALANCE UTILITY....
		$this->makeSummaryAcTrans($this->makeTreeTrans( $this->accountmaster->updateUtility('CB')) );
		//$actransactions = $this->accountmaster->getSelectedAccountsCbUlitily('get', $start, $limit, $order, $dir, $search, $dept);
		//$this->makeSummaryAcTrans($this->makeTreeTrans($actransactions));
		//echo '<pre>';print_r($actransactions);exit;
		
		$acmasters = $this->accountmaster->accountMasterSupList('get', $start, $limit, $order, $dir, $search, $dept);
		
		if($search)
			$totalFiltered =  $this->accountmaster->accountMasterSupList('count', $start, $limit, $order, $dir, $search, $dept);
		
        $data = array();
        if(!empty($acmasters))
        {
            foreach ($acmasters as $row)
            {
                $view =  '"'.url('account_master/view/'.$row->id).'"';
				$opt =  $row->id;
				
				$nestedData['opt'] = "<input type='radio' name='account' class='opt-account' value='{$opt}'/>";
                
				$nestedData['id'] = $row->id;
                $nestedData['account_id'] = $row->account_id;
				$nestedData['master_name'] = $row->master_name;
				$nestedData['group_name'] = $row->group_name;
				$nestedData['category_name'] = $row->category_name;
				$nestedData['department'] = $row->department;
				$nestedData['cl_balance'] = $row->cl_balance;
				$nestedData['op_balance'] = $row->op_balance;
				$nestedData['issued_qty'] = $row->issued_qty;
				$nestedData['view'] = "<p><button class='btn btn-primary btn-xs' onClick='location.href={$view}'>
												<span class='glyphicon glyphicon-eye-open'></span></button></p>";
												
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


	public function indexBank() {
		
		/* $actransactions = $this->accountmaster->getSelectedAccountsCbUlitily('get', 0, 10, 0, 'ASC', '', '');
		$this->makeSummaryAcTrans($this->makeTreeTrans($actransactions));
		echo '<pre>';print_r($actransactions);exit; */
		
		$data = array();
		$acmasters = [];//$this->accountmaster->accountMasterList();
		$jobs = DB::table('jobmaster')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->where('is_salary_job',0)->get();
		$currency = DB::table('currency')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();//echo '<pre>';print_r($currency);exit;
		
		
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
		
		$category = DB::table('account_category')->where('parent_id','!=',0)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		$groups = DB::table('account_group')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		$salesman = DB::table('salesman')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
		
		return view('body.accountenquiry.indexbank')
					->withAcmasters($acmasters)
					->withSettings($this->acsettings)
					->withJobs($jobs)
					->withCurrency($currency)
					->withDepartment($department)
					->withDeptid($deptid)
					->withIsdept($is_dept)
					->withGroups($groups)
					->withCategory($category)
					->withSalesman($salesman)
					->withData($data);
	}

	public function ajaxBankPaging(Request $request)
	{
		if(Session::get('department')==1) {
			$columns = array( 
								0 =>'account_master.id',
								1 => 'account_id', 
								2 => 'master_name',
								3 => 'group_name',
								4 => 'category_name',
								5 => 'department',
								6 => 'cl_balance',
								7 => 'op_balance'
							);
		} else {
			$columns = array( 
								0 =>'account_master.id',
								1 => 'account_id', 
								2 => 'master_name',
								3 => 'group_name',
								4 => 'category_name',
								5 => 'cl_balance',
								6 => 'op_balance'
							);
		}
						
		$totalData = $this->accountmaster->accountMasterBankListCount();
            
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
		$search = (empty($request->input('search.value')))?null:$request->input('search.value');
		$dept = $request->input('dept');
		
		//RUNING CLOSING BALANCE UTILITY....
		$this->makeSummaryAcTrans($this->makeTreeTrans( $this->accountmaster->updateUtility('CB')) );
		//$actransactions = $this->accountmaster->getSelectedAccountsCbUlitily('get', $start, $limit, $order, $dir, $search, $dept);
		//$this->makeSummaryAcTrans($this->makeTreeTrans($actransactions));
		//echo '<pre>';print_r($actransactions);exit;
		
		$acmasters = $this->accountmaster->accountMasterBankList('get', $start, $limit, $order, $dir, $search, $dept);
		
		if($search)
			$totalFiltered =  $this->accountmaster->accountMasterBankList('count', $start, $limit, $order, $dir, $search, $dept);
		
        $data = array();
        if(!empty($acmasters))
        {
            foreach ($acmasters as $row)
            {
                $view =  '"'.url('account_master/view/'.$row->id).'"';
				$opt =  $row->id;
				
				$nestedData['opt'] = "<input type='radio' name='account' class='opt-account' value='{$opt}'/>";
                
				$nestedData['id'] = $row->id;
                $nestedData['account_id'] = $row->account_id;
				$nestedData['master_name'] = $row->master_name;
				$nestedData['group_name'] = $row->group_name;
				$nestedData['category_name'] = $row->category_name;
				$nestedData['department'] = $row->department;
				$nestedData['cl_balance'] = $row->cl_balance;
				$nestedData['op_balance'] = $row->op_balance;
				$nestedData['issued_qty'] = $row->issued_qty;
				$nestedData['view'] = "<p><button class='btn btn-primary btn-xs' onClick='location.href={$view}'>
												<span class='glyphicon glyphicon-eye-open'></span></button></p>";
												
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

	public function indexCash() {
		
		/* $actransactions = $this->accountmaster->getSelectedAccountsCbUlitily('get', 0, 10, 0, 'ASC', '', '');
		$this->makeSummaryAcTrans($this->makeTreeTrans($actransactions));
		echo '<pre>';print_r($actransactions);exit; */
		
		$data = array();
		$acmasters = [];//$this->accountmaster->accountMasterList();
		$jobs = DB::table('jobmaster')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->where('is_salary_job',0)->get();
		$currency = DB::table('currency')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();//echo '<pre>';print_r($currency);exit;
		
		
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
		
		$category = DB::table('account_category')->where('parent_id','!=',0)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		$groups = DB::table('account_group')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		$salesman = DB::table('salesman')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
		
		return view('body.accountenquiry.indexcash')
					->withAcmasters($acmasters)
					->withSettings($this->acsettings)
					->withJobs($jobs)
					->withCurrency($currency)
					->withDepartment($department)
					->withDeptid($deptid)
					->withIsdept($is_dept)
					->withGroups($groups)
					->withCategory($category)
					->withSalesman($salesman)
					->withData($data);
	}

	public function ajaxCashPaging(Request $request)
	{
		if(Session::get('department')==1) {
			$columns = array( 
								0 =>'account_master.id',
								1 => 'account_id', 
								2 => 'master_name',
								3 => 'group_name',
								4 => 'category_name',
								5 => 'department',
								6 => 'cl_balance',
								7 => 'op_balance'
							);
		} else {
			$columns = array( 
								0 =>'account_master.id',
								1 => 'account_id', 
								2 => 'master_name',
								3 => 'group_name',
								4 => 'category_name',
								5 => 'cl_balance',
								6 => 'op_balance'
							);
		}
						
		$totalData = $this->accountmaster->accountMasterCashListCount();
            
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
		$search = (empty($request->input('search.value')))?null:$request->input('search.value');
		$dept = $request->input('dept');
		
		//RUNING CLOSING BALANCE UTILITY....
		$this->makeSummaryAcTrans($this->makeTreeTrans( $this->accountmaster->updateUtility('CB')) );
		//$actransactions = $this->accountmaster->getSelectedAccountsCbUlitily('get', $start, $limit, $order, $dir, $search, $dept);
		//$this->makeSummaryAcTrans($this->makeTreeTrans($actransactions));
		//echo '<pre>';print_r($actransactions);exit;
		
		$acmasters = $this->accountmaster->accountMasterCashList('get', $start, $limit, $order, $dir, $search, $dept);
		
		if($search)
			$totalFiltered =  $this->accountmaster->accountMasterCashList('count', $start, $limit, $order, $dir, $search, $dept);
		
        $data = array();
        if(!empty($acmasters))
        {
            foreach ($acmasters as $row)
            {
                $view =  '"'.url('account_master/view/'.$row->id).'"';
				$opt =  $row->id;
				
				$nestedData['opt'] = "<input type='radio' name='account' class='opt-account' value='{$opt}'/>";
                
				$nestedData['id'] = $row->id;
                $nestedData['account_id'] = $row->account_id;
				$nestedData['master_name'] = $row->master_name;
				$nestedData['group_name'] = $row->group_name;
				$nestedData['category_name'] = $row->category_name;
				$nestedData['department'] = $row->department;
				$nestedData['cl_balance'] = $row->cl_balance;
				$nestedData['op_balance'] = $row->op_balance;
				$nestedData['issued_qty'] = $row->issued_qty;
				$nestedData['view'] = "<p><button class='btn btn-primary btn-xs' onClick='location.href={$view}'>
												<span class='glyphicon glyphicon-eye-open'></span></button></p>";
												
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
	
	protected function makeTreeTrans($result)
	{
		$childs = array();
		foreach($result as $item)
			$childs[$item->id][] = $item;

		return $childs;
	}
	
	protected function makeSummaryAcTrans($results)
	{
		$arrSummarry = array();
		foreach($results as $result)
		{
			$arraccount = array(); 
			$dramount = $cramount = 0;
			foreach($result as $row) {
				$cl_balance = $row->cl_balance;
				$account_id = $row->id;
				if($row->transaction_type=='Dr') {
					$amountD = ($row->amount < 0)?(-1*$row->amount):$row->amount;
					$dramount += $amountD;
				} else {
					$amountC = ($row->amount < 0)?(-1*$row->amount):$row->amount;
					$cramount += $amountC;
				}
			}
			
			$amount = $dramount - $cramount;
			//$amount = ($amount < 0)?(-1*$amount):$amount;
			if($amount != $cl_balance) {
				//update the closing balance as amount.....
				$this->accountmaster->updateClosingBalance($account_id, $amount);
			}
				
		}
		return true;
	}
	
	protected function makeTree3($results)
	{
		$childs = array();
		foreach($results as $key => $result) {
    		foreach($result as $item)
    			if($item->reference_from=='')
    				$childs[$key][$item->reference][] = $item;
    			else
    				$childs[$key][$item->reference_from][] = $item;
    
    		foreach($result as $item) if (isset($childs[$item->id]))
    			$item->childs = $childs[$item->id];
	   }
		return $childs;
	}
	
	protected function makeTree2($result)
	{
		$childs = array();
		foreach($result as $item)
			$childs[$item->account_master_id][] = $item;
		return $childs;
	}
	
	
	protected function makeTree($result)
	{
		$childs = array();
		foreach($result as $item)
			if($item->reference_from=='')
				$childs[$item->reference][] = $item;
			else
				$childs[$item->reference_from][] = $item;

		foreach($result as $item) if (isset($childs[$item->id]))
			$item->childs = $childs[$item->id];
		
		return $childs;
	}
	
	protected function makeSummary($results)
	{
		$arrSummarry = array();
		foreach($results as $rows)
		{
			$dramount = $cramount = 0; $invoice_date = $voucher_typeid = $voucher_type = $edit = '';
			$vtype = ['SI','PI','OBD','PIN','SIN','SIR','PIR'];
			foreach($rows as $row) {
				$reference = $row->reference;
				$due_date = $row->invoice_date;
				$description = $row->description; 
				$jobno = isset($row->jobno)?$row->jobno:''; 
				$reference_frm = isset($row->reference_from)?$row->reference_from:'';
				$cheque_date = isset($row->cheque_date)?$row->cheque_date:'';
				$cheque_no = isset($row->cheque_no)?$row->cheque_no:'';
				$account_id = isset($row->account_master_id)?$row->account_master_id:'';
				
				if(!isset($row->is_edit)) {
					if($row->transaction_type=='Dr') {
						$dramount += $row->amount; //COMMENTED 22 DEC 2021 (isset($row->is_fc))?(($row->is_fc==1)?$row->fc_amount:$row->amount):$row->amount;
					} else {
						$cramount += $row->amount; //COMMENTED 22 DEC 2021 (isset($row->is_fc))?(($row->is_fc==1)?$row->fc_amount:$row->amount):$row->amount;
					}
				}
				if($row->voucher_type=='PI' || $row->voucher_type=='SI' || $row->voucher_type=='OBD' || $row->voucher_type=='PIN' || $row->voucher_type=='SIN' || $row->voucher_type=='SR' || $row->voucher_type=='PR' || $row->voucher_type=='PS' || $row->voucher_type=='SS' || $row->voucher_type=='SIR' || $row->voucher_type=='PIR') {
					$voucher_typeid = $row->voucher_type_id;
					$voucher_type = $row->voucher_type;
				}
					
				if(in_array($row->voucher_type, $vtype)) {
					$invoice_date = $row->invoice_date;
				}
				
				if($edit=='')
					$edit = (isset($row->is_edit))?$row->is_edit:'';
				
				//if($row->voucher_type=='PI' || $row->voucher_type=='SI' || $row->voucher_type=='OBD' || $row->voucher_type=='CN' || $row->voucher_type=='PIN' || $row->voucher_type=='SR' || $row->voucher_type=='PR' || $row->voucher_type=='JV' || $row->voucher_type=='RV' || $row->voucher_type=='PV')
				/* if($row->voucher_type=='PI' || $row->voucher_type=='SI' || $row->voucher_type=='OBD' || $row->voucher_type=='CN' || $row->voucher_type=='PIN' || $row->voucher_type=='SR' || $row->voucher_type=='PR' || $row->voucher_type=='JV' || $row->voucher_type=='RV' || $row->voucher_type=='PV' || $row->voucher_type=='DB' || $row->voucher_type=='CB')
					$invoice_date = $row->invoice_date; */
				/*  IF RV NOT IN IF CONDITION TAKING THE INVOICE DATE CORRECTLY IN THE OUTSTANDIN BILL OTHERWISE INVOICE DATE IS 01-01-1970 ??? */
			}
			
			$invoice_date = ($invoice_date=='')?$row->invoice_date:$invoice_date; /* ABOVE SOLUTION HERE ON 5 APR 2021 */
			$arrSummarry[] = ['name' => $reference, 
							  'cr_amount' => $cramount, 
							  'dr_amount' => $dramount,
							  'invoice_date' => $invoice_date,
							  'description' => $description,
							  'jobno' => $jobno,
							  'reference_from'	=> ($reference_frm=='')?$reference:$reference_frm,
							  'inv_month' => date('m', strtotime($invoice_date)),
							  'due_date' => $due_date,
							  'cheque_date' => $cheque_date,
							  'cheque_no' => $cheque_no,
							  'account_master_id' => $account_id,
							  'voucher_type'	=> $voucher_type,
							  'voucher_type_id'	=> $voucher_typeid,
							  'is_edit'		=> $edit
							 ];

		}
		return $arrSummarry;
	}
	
	protected function makeSummaryOsbill($results)
	{
		$arrSummarry = array();
		foreach($results as $rows)
		{
			$dramount = $cramount = 0; $invoice_date = $voucher_typeid = $voucher_type = $tr_doctype = $edit = $tr_doctype_voucher_typeid = $trdoctype = '';
			$vtype = ['SI','PI','OBD','PIN','SIN','SIR','PIR'];
			foreach($rows as $row) {
				$reference = $row->reference;
				$due_date = $row->invoice_date;
				$description = $row->description; 
				$jobno = isset($row->jobno)?$row->jobno:''; 
				$reference_frm = isset($row->reference_from)?$row->reference_from:'';
				$cheque_date = isset($row->cheque_date)?$row->cheque_date:'';
				$cheque_no = isset($row->cheque_no)?$row->cheque_no:'';
				$account_id = isset($row->account_master_id)?$row->account_master_id:'';
				
				if(!isset($row->is_edit)) {
					if($row->transaction_type=='Dr') {
						$dramount += $row->amount; //COMMENTED 22 DEC 2021 (isset($row->is_fc))?(($row->is_fc==1)?$row->fc_amount:$row->amount):$row->amount;
					} else {
						$cramount += $row->amount; //COMMENTED 22 DEC 2021 (isset($row->is_fc))?(($row->is_fc==1)?$row->fc_amount:$row->amount):$row->amount;
					}
				}
				
				if($row->voucher_type=='PI' || $row->voucher_type=='SI' || $row->voucher_type=='OBD' || $row->voucher_type=='PIN' || $row->voucher_type=='SIN' || $row->voucher_type=='SR' || $row->voucher_type=='PR' || $row->voucher_type=='PS' || $row->voucher_type=='SS' || $row->voucher_type=='SIR' || $row->voucher_type=='PIR') { // || $row->voucher_type=='JV' || $row->voucher_type=='RV' || $row->voucher_type=='PV'
					$voucher_typeid = $row->voucher_type_id;
					$voucher_type = $row->voucher_type;
				}
					
				if($row->voucher_type=='JV' || $row->voucher_type=='RV' || $row->voucher_type=='PV') {
				    $tr_doctype = $row->voucher_type;
				    $tr_doctype_voucher_typeid = $row->voucher_type_id;
				}
				
				if(in_array($row->voucher_type, $vtype)) {
					$invoice_date = $row->invoice_date;
				}
				
				if($edit=='')
					$edit = (isset($row->is_edit))?$row->is_edit:'';
				
				//if($row->voucher_type=='PI' || $row->voucher_type=='SI' || $row->voucher_type=='OBD' || $row->voucher_type=='CN' || $row->voucher_type=='PIN' || $row->voucher_type=='SR' || $row->voucher_type=='PR' || $row->voucher_type=='JV' || $row->voucher_type=='RV' || $row->voucher_type=='PV')
				/* if($row->voucher_type=='PI' || $row->voucher_type=='SI' || $row->voucher_type=='OBD' || $row->voucher_type=='CN' || $row->voucher_type=='PIN' || $row->voucher_type=='SR' || $row->voucher_type=='PR' || $row->voucher_type=='JV' || $row->voucher_type=='RV' || $row->voucher_type=='PV' || $row->voucher_type=='DB' || $row->voucher_type=='CB')
					$invoice_date = $row->invoice_date; */
				/*  IF RV NOT IN IF CONDITION TAKING THE INVOICE DATE CORRECTLY IN THE OUTSTANDIN BILL OTHERWISE INVOICE DATE IS 01-01-1970 ??? */
			}
			
			$invoice_date = ($invoice_date=='')?$row->invoice_date:$invoice_date; /* ABOVE SOLUTION HERE ON 5 APR 2021 */
			$arrSummarry[] = ['name' => $reference, 
							  'cr_amount' => $cramount, 
							  'dr_amount' => $dramount,
							  'invoice_date' => $invoice_date,
							  'description' => $description,
							  'jobno' => $jobno,
							  'reference_from'	=> ($reference_frm=='')?$reference:$reference_frm,
							  'inv_month' => date('m', strtotime($invoice_date)),
							  'due_date' => $due_date,
							  'cheque_date' => $cheque_date,
							  'cheque_no' => $cheque_no,
							  'account_master_id' => $account_id,
							  'voucher_type'	=> ($voucher_type=='')?$trdoctype:$voucher_type,
							  'voucher_type_id'	=> ($voucher_typeid=='')?$tr_doctype_voucher_typeid:$voucher_typeid,
							  'is_edit'		=> $edit
							 ];

		}
		return $arrSummarry;
	}
	
	protected function makeSummaryAdv($results)
	{
		$arrSummarry = array();
		foreach($results as $key => $rows)
		{
			if($key!='Adv.1') {
    			$dramount = $cramount = 0; $invoice_date = $voucher_typeid = $voucher_type = $edit = $tr_doctype = $tr_doctype_voucher_typeid = '';
    			$vtype = ['SI','PI','OBD','PIN','SIN','SIR','PIR'];
    			foreach($rows as $row) {
    				$reference = $row->reference;
    				$due_date = $row->invoice_date;
    				$description = $row->description; 
    				$jobno = isset($row->jobno)?$row->jobno:''; 
    				$reference_frm = isset($row->reference_from)?$row->reference_from:'';
    				$cheque_date = isset($row->cheque_date)?$row->cheque_date:'';
    				$cheque_no = isset($row->cheque_no)?$row->cheque_no:'';
    				$account_id = isset($row->account_master_id)?$row->account_master_id:'';
    				
    				
				    if(!isset($row->is_edit)) {
    					if($row->transaction_type=='Dr') {
    						$dramount += $row->amount; //COMMENTED 22 DEC 2021 (isset($row->is_fc))?(($row->is_fc==1)?$row->fc_amount:$row->amount):$row->amount;
    					} else {
    						$cramount += $row->amount; //COMMENTED 22 DEC 2021 (isset($row->is_fc))?(($row->is_fc==1)?$row->fc_amount:$row->amount):$row->amount;
    					}
    				}
    				if($row->voucher_type=='PI' || $row->voucher_type=='SI' || $row->voucher_type=='OBD' || $row->voucher_type=='PIN' || $row->voucher_type=='SIN' || $row->voucher_type=='PR' || $row->voucher_type=='PS' || $row->voucher_type=='SS' || $row->voucher_type=='SIR' || $row->voucher_type=='PIR') { //|| $row->voucher_type=='SR' || $row->voucher_type=='JV' || $row->voucher_type=='RV' || $row->voucher_type=='PV'
    					$voucher_typeid = $row->voucher_type_id;
    					$voucher_type = $row->voucher_type;
    				}
    				
    				if($row->voucher_type=='JV' || $row->voucher_type=='RV' || $row->voucher_type=='PV') {
				        $tr_doctype = $row->voucher_type;
				        $tr_doctype_voucher_typeid = $row->voucher_type_id;
    				}
    				
    				if(in_array($row->voucher_type, $vtype)) {
    					$invoice_date = $row->invoice_date;
    					
    				} else {
    				    $reference = $reference_frm;
    				}

    				if($edit=='')
    					$edit = (isset($row->is_edit))?$row->is_edit:'';
    				
    				
    				//if($row->voucher_type=='PI' || $row->voucher_type=='SI' || $row->voucher_type=='OBD' || $row->voucher_type=='CN' || $row->voucher_type=='PIN' || $row->voucher_type=='SR' || $row->voucher_type=='PR' || $row->voucher_type=='JV' || $row->voucher_type=='RV' || $row->voucher_type=='PV')
    				/* if($row->voucher_type=='PI' || $row->voucher_type=='SI' || $row->voucher_type=='OBD' || $row->voucher_type=='CN' || $row->voucher_type=='PIN' || $row->voucher_type=='SR' || $row->voucher_type=='PR' || $row->voucher_type=='JV' || $row->voucher_type=='RV' || $row->voucher_type=='PV' || $row->voucher_type=='DB' || $row->voucher_type=='CB')
    					$invoice_date = $row->invoice_date; */
    				/*  IF RV NOT IN IF CONDITION TAKING THE INVOICE DATE CORRECTLY IN THE OUTSTANDIN BILL OTHERWISE INVOICE DATE IS 01-01-1970 ??? */
    			}
    			
    			$invoice_date = ($invoice_date=='')?$row->invoice_date:$invoice_date; /* ABOVE SOLUTION HERE ON 5 APR 2021 */
    			$arrSummarry[] = ['name' => $reference, 
    							  'cr_amount' => $cramount, 
    							  'dr_amount' => $dramount,
    							  'invoice_date' => $invoice_date,
    							  'description' => $description,
    							  'jobno' => $jobno,
    							  'reference_from'	=> ($reference_frm=='')?$reference:$reference_frm,
    							  'inv_month' => date('m', strtotime($invoice_date)),
    							  'due_date' => $due_date,
    							  'cheque_date' => $cheque_date,
    							  'cheque_no' => $cheque_no,
    							  'account_master_id' => $account_id,
    							  'voucher_type'	=> ($voucher_type=='')?$tr_doctype:$voucher_type,
    							  'voucher_type_id'	=> ($voucher_typeid=='')?$tr_doctype_voucher_typeid:$voucher_typeid,
    							  'is_edit'		=> $edit
    							 ];
    			
			} else {
			    
			    $dramount = $cramount = 0; $invoice_date = $voucher_typeid = $voucher_type = $edit = '';
			    foreach($rows as $row) {
			        if($row->category=='CUSTOMER')
			            $cramount = $row->amount;
			        else if($row->category=='SUPPLIER')
			            $dramount = $row->amount;
			        
			        $reference = $row->reference;
    				$due_date = $row->invoice_date;
			        $description = $row->description; 
    				$jobno = isset($row->jobno)?$row->jobno:''; 
    				$reference_frm = isset($row->reference_from)?$row->reference_from:'';
    				$cheque_date = isset($row->cheque_date)?$row->cheque_date:'';
    				$cheque_no = isset($row->cheque_no)?$row->cheque_no:'';
    				$account_id = isset($row->account_master_id)?$row->account_master_id:'';
    				$voucher_typeid = $row->voucher_type_id;
    				$voucher_type = ($row->voucher_type=='OBD')?'OB':$row->voucher_type;
    				
    				$invoice_date = ($invoice_date=='')?$row->invoice_date:$invoice_date; /* ABOVE SOLUTION HERE ON 5 APR 2021 */
        			$arrSummarry[] = ['name' => $reference, 
        							  'cr_amount' => $cramount, 
        							  'dr_amount' => $dramount,
        							  'invoice_date' => $invoice_date,
        							  'description' => $description,
        							  'jobno' => $jobno,
        							  'reference_from'	=> ($reference_frm=='')?$reference:$reference_frm,
        							  'inv_month' => date('m', strtotime($invoice_date)),
        							  'due_date' => $due_date,
        							  'cheque_date' => $cheque_date,
        							  'cheque_no' => $cheque_no,
        							  'account_master_id' => $account_id,
        							  'voucher_type'	=> $voucher_type,
        							  'voucher_type_id'	=> $voucher_typeid,
        							  'is_edit'		=> $edit
        							 ];
			    }
			}
			
			

		}
		return $arrSummarry;
	}
	
	protected function makeSummary2($results)
	{
		$arrSummarry = array();
		foreach($results as $key => $result) {
		  foreach($result as $rows) 
		  {
			$dramount = $cramount = 0; $invoice_date = $voucher_typeid = $voucher_type = $edit = $description = '';
			$vtype = ['SI','PI','OBD','PIN','SIN','SIR','PIR'];
			foreach($rows as $row) {
				$reference = $row->reference;
				$due_date = $row->invoice_date;
				
				$jobno = isset($row->jobno)?$row->jobno:''; 
				$reference_frm = isset($row->reference_from)?$row->reference_from:'';
				$cheque_date = isset($row->cheque_date)?$row->cheque_date:'';
				$cheque_no = isset($row->cheque_no)?$row->cheque_no:'';
				$account_id = isset($row->account_master_id)?$row->account_master_id:'';
				
				//if($row->transaction_type=='Dr')
				   $description = $row->description; 
				   $loc_proj = $row->loc_proj;
				   $eqp_type = $row->eqp_type;
				   $lpo_no = $row->lpo_no;
				
				if(!isset($row->is_edit)) {
					if($row->transaction_type=='Dr') {
						$dramount += $row->amount; //COMMENTED 22 DEC 2021 (isset($row->is_fc))?(($row->is_fc==1)?$row->fc_amount:$row->amount):$row->amount;
					} else {
						$cramount += $row->amount; //COMMENTED 22 DEC 2021 (isset($row->is_fc))?(($row->is_fc==1)?$row->fc_amount:$row->amount):$row->amount;
					}
				}
				if($row->voucher_type=='PIR' || $row->voucher_type=='SIR' || $row->voucher_type=='PI' || $row->voucher_type=='SI' || $row->voucher_type=='OBD' || $row->voucher_type=='PIN' || $row->voucher_type=='SIN' || $row->voucher_type=='SR' || $row->voucher_type=='PR' || $row->voucher_type=='PS' || $row->voucher_type=='SS') {
					$voucher_typeid = $row->voucher_type_id;
					$voucher_type = $row->voucher_type;
				}
					
				if(in_array($row->voucher_type, $vtype)) {
					$invoice_date = $row->invoice_date;
				}
				
				if($edit=='')
					$edit = (isset($row->is_edit))?$row->is_edit:'';
				
				//if($row->voucher_type=='PI' || $row->voucher_type=='SI' || $row->voucher_type=='OBD' || $row->voucher_type=='CN' || $row->voucher_type=='PIN' || $row->voucher_type=='SR' || $row->voucher_type=='PR' || $row->voucher_type=='JV' || $row->voucher_type=='RV' || $row->voucher_type=='PV')
				/* if($row->voucher_type=='PI' || $row->voucher_type=='SI' || $row->voucher_type=='OBD' || $row->voucher_type=='CN' || $row->voucher_type=='PIN' || $row->voucher_type=='SR' || $row->voucher_type=='PR' || $row->voucher_type=='JV' || $row->voucher_type=='RV' || $row->voucher_type=='PV' || $row->voucher_type=='DB' || $row->voucher_type=='CB')
					$invoice_date = $row->invoice_date; */
				/*  IF RV NOT IN IF CONDITION TAKING THE INVOICE DATE CORRECTLY IN THE OUTSTANDIN BILL OTHERWISE INVOICE DATE IS 01-01-1970 ??? */
			}
			
			$invoice_date = ($invoice_date=='')?$row->invoice_date:$invoice_date; /* ABOVE SOLUTION HERE ON 5 APR 2021 */
			$arrSummarry[$key][] = ['name' => $reference, 
							  'cr_amount' => $cramount, 
							  'dr_amount' => $dramount,
							  'invoice_date' => $invoice_date,
							  'description' => $description,
							  'jobno' => $jobno,
							  'reference_from'	=> ($reference_frm=='')?$reference:$reference_frm,
							  'inv_month' => date('m', strtotime($invoice_date)),
							  'due_date' => $due_date,
							  'cheque_date' => $cheque_date,
							  'cheque_no' => $cheque_no,
							  'account_master_id' => $account_id,
							  'voucher_type'	=> $voucher_type,
							  'voucher_type_id'	=> $voucher_typeid,
							  'is_edit'		=> $edit,
							  'loc_proj'	=> $loc_proj,
							  'eqp_type'	=> $eqp_type,
							  'lpo_no'	=> $lpo_no
							 ];

		  }
		  usort($arrSummarry[$key], array($this, "date_compare"));
		}
		
		return $arrSummarry;
		
	}
	
	//JUN3....
	protected function makeAgeingSummary($results)
	{
		$arrSummarry = array();
		foreach($results as $rows)
		{
			$dramount = $cramount = 0; $invoice_date = '';
			foreach($rows as $row) {
				$reference = $row->reference;
				$due_date = $row->invoice_date;//$invoice_date = $row->invoice_date;
				$description = $row->description; 
				$reference_frm = $row->reference_from;
				$cheque_date = isset($row->cheque_date)?$row->cheque_date:'';
				$cheque_no = isset($row->cheque_no)?$row->cheque_no:'';
				$master_name = $row->master_name;
				$acid = $row->account_id;
				
				if($row->transaction_type=='Dr')
					$dramount += ($row->is_fc==1)?$row->fc_amount:$row->amount;
				else
					$cramount += ($row->is_fc==1)?$row->fc_amount:$row->amount;
				
				if($row->voucher_type=='PI' || $row->voucher_type=='SI' || $row->voucher_type=='OBD')
					$invoice_date = $row->invoice_date;
				
			}
			$arrSummarry[] = ['name' => $reference, 
							  'acname' => $master_name,
							  'acid'	=> $acid,
							  'cr_amount' => $cramount, 
							  'dr_amount' => $dramount,
							  'invoice_date' => $invoice_date,
							  'description' => $description,
							  'reference_from'	=> ($reference_frm=='')?$reference:$reference_frm,
							  'inv_month' => date('m', strtotime($invoice_date)),
							  'due_date' => $due_date,
							  'cheque_date' => $cheque_date,
							  'cheque_no' => $cheque_no
							  ];

		}
		return $arrSummarry;
	}
	//...JUN3
	
	
	protected function makeSummaryOS($results)
	{
		$arrSummarry = array();
		foreach($results as $rows)
		{
			$dramount = $cramount = 0; $invoice_date = $rvtype = '';
			foreach($rows as $row) {
				$reference = $row->reference;
				$due_date = $row->invoice_date;//$invoice_date = $row->invoice_date;
				$description = $row->description; 
				$reference_frm = $row->reference_from;
				$cheque_date = isset($row->cheque_date)?$row->cheque_date:'';
				$cheque_no = isset($row->cheque_no)?$row->cheque_no:'';
				$rvtype = ($row->rv_type!='')?$row->rv_type:$rvtype;
				
				if($row->transaction_type=='Dr')
					$dramount += ($row->is_fc==1)?$row->fc_amount:$row->amount;
				else
					$cramount += ($row->is_fc==1)?$row->fc_amount:$row->amount;
				
				//if($row->voucher_type=='PI' || $row->voucher_type=='SI')
					$invoice_date = $row->invoice_date;
				
			}
			$arrSummarry[] = ['name' => $reference, 
							  'cr_amount' => $cramount, 
							  'dr_amount' => $dramount,
							  'invoice_date' => $invoice_date,
							  'description' => $description,
							  'reference_from'	=> ($reference_frm=='')?$reference:$reference_frm,
							  'inv_month' => date('m', strtotime($invoice_date)),
							  'due_date' => $due_date,
							  'cheque_date' => $cheque_date,
							  'cheque_no' => $cheque_no,
							  'rv_type' => $rvtype
							  ];

		}
		return $arrSummarry;
	}
	
	protected function makeSummaryPDC($results)
	{
		$arrSummarry = array();
		foreach($results as $rows)
		{
			$dramount = $cramount = 0;
			foreach($rows as $row) {
				$reference = $row->reference;
				$invoice_date = $row->invoice_date;
				$description = $row->description; 
				$reference = $row->reference;
				
				if($row->transaction_type=='Dr')
					$dramount += $row->amount;
				else
					$cramount += $row->amount;
				
			}
			$arrSummarry[] = ['name' => $reference, 
							  'cr_amount' => $cramount, 
							  'dr_amount' => $dramount,
							  'invoice_date' => $invoice_date,
							  'description' => $description,
							  'reference'	=> $reference];

		}
		return $arrSummarry;
	}
	
	protected function makeTreeVchr($result)
	{
		$childs = array();
		foreach($result as $item)
				$childs[$item->voucher_no][] = $item;
			
		return $childs;
	}
	
	
	private function monthly($result)
	{
		$childs = array();
		foreach($result as $item)
			$childs[$item['inv_month']][] = $item;
		
		ksort($childs);
		return $childs;
	}
	
	private function makeConsolidated($result)
	{
		$res = [];
		$childs = array();
		
		foreach($result as $key => $item)
			$childs[$item->voucher_type][$item->reference][] = $item;
			
			
		foreach($childs as $child) {
		  foreach($child as $rows) {
			$amount = $cramt = $dramt = 0;
			foreach($rows as $row) {
				$mastername = $row->master_name;
				$account_id = $row->account_id;
				$id			= $row->id;
				$vouchertype = $row->voucher_type;
				$voucher_type_id = $row->voucher_type_id;
				$account_master_id = $row->account_master_id;
				$transaction_type = $row->transaction_type;
				$description = $row->description;
				$reference = $row->reference;
				$invoice_date = $row->invoice_date;
				$is_paid = $row->is_paid;
				$reference_from = $row->reference_from;
				$tr_for = $row->tr_for;
				$ac_category = $row->account_category_id;
				$ac_group = $row->account_group_id;
				$lpo_sino=$row->lpo_sino;
				$doc_nos=$row->doc_nos;
				if($row->transaction_type=='Dr')
				    $dramt += $row->amount;
				else
				    $cramt += $row->amount;
				    
				
			}
			
			$amount = ($dramt > $cramt)?($dramt-$cramt):($cramt-$dramt);
			$res[] = (object)(array('master_name' => $mastername,
									'account_id'  => $account_id,
									'id'		 => $id,
									'voucher_type' => $vouchertype,
									'voucher_type_id' => $voucher_type_id,
									'account_master_id' => $account_master_id,
									'transaction_type' => $transaction_type,
								    'amount'	  => $amount,
									'description' 	=> $description,
									'reference' => $reference,
									'invoice_date' => $invoice_date,
									'is_paid'	=> $is_paid,
									'reference_from'	=> $reference_from,
									'tr_for'	=> $tr_for,
									'account_category_id' => $ac_category,
									'account_group_id' => $ac_group,
									'lpo_sino'=>$lpo_sino,
									'doc_nos'=>$doc_nos
									));
		  }	
			
			//$res[] = new ArrayObject($re);
		}
		
		return $res;
	}
	
	
	private function makeConsolidatedPdc($result)
	{
		$childs = array();
		foreach($result as $item)
			$childs[$item->reference][] = $item;
		return $childs;	
		/* foreach($childs as $rows) {
			$amount = 0;
			foreach($rows as $row) {
				$mastername = $row->master_name;
				$account_id = $row->account_id;
				$id			= $row->id;
				$vouchertype = $row->voucher_type;
				$voucher_type_id = $row->voucher_type_id;
				$account_master_id = $row->account_master_id;
				$transaction_type = $row->transaction_type;
				$description = $row->description;
				$reference = $row->reference;
				$invoice_date = $row->invoice_date;
				$is_paid = $row->is_paid;
				$reference_from = $row->reference_from;
				$tr_for = $row->tr_for;
				
				$amount += $row->amount;
			}
			
			$res[] = (object)(array('master_name' => $mastername,
									'account_id'  => $account_id,
									'id'		 => $id,
									'voucher_type' => $vouchertype,
									'voucher_type_id' => $voucher_type_id,
									'account_master_id' => $account_master_id,
									'transaction_type' => $transaction_type,
								    'amount'	  => $amount,
									'description' 	=> $description,
									'reference' => $reference,
									'invoice_date' => $invoice_date,
									'is_paid'	=> $is_paid,
									'reference_from'	=> $reference_from,
									'tr_for'	=> $tr_for
									));
		}
		
		return $res;*/
	} 
	
	
	private function correction($transactions, $resultrow) {
		
		$cr_total = 0; $dr_total = 0; $balance = 0; $res = [];
		foreach($transactions as $transaction) {
			$balance_prnt = $transaction['dr_amount'] - $transaction['cr_amount'];
			
			if(isset($resultrow->category) && $resultrow->category=='CUSTOMER') {
				//$balance_prnt = $transaction['dr_amount'] - $transaction['cr_amount'];	
				$dr_total += $transaction['dr_amount'];
				$cr_total += $transaction['cr_amount'];
				
				$balance += $balance_prnt;
				
				if($transaction['dr_amount'] > 0)
					$dr_amount = number_format($transaction['dr_amount'],2);
				else if($transaction['dr_amount'] < 0)
					$dr_amount = '('.number_format($transaction['dr_amount']*-1,2).')';
				else $dr_amount = '';
				
				if($transaction['cr_amount'] > 0)
					$cr_amount = number_format($transaction['cr_amount'],2);
				else if($transaction['dr_amount'] < 0)
					$cr_amount = '('.number_format($transaction['cr_amount']*-1,2).')';
				else $cr_amount = '';
				
				if($balance_prnt > 0)
					$balance_prnt = number_format($balance_prnt,2);
				else if($balance_prnt < 0)
					$balance_prnt = '('.number_format(($balance_prnt*-1),2).')';
				else $balance_prnt = '';
			
				$res[] = ['invoice_date' => $transaction['invoice_date'],
						 'reference_from' => $transaction['reference_from'],
						 'dr_amount' => $transaction['dr_amount'],
						 'cr_amount' => $transaction['cr_amount'],
						 'balance' => $balance,
						 'inv_month' => date('m', strtotime($transaction['invoice_date'])),
						 'due_date' => $transaction['due_date']
						 ];
						 
			} else if(isset($resultrow->category) && $resultrow->category=='SUPPLIER') {
				
				$dr_total += $transaction['dr_amount'];
				$cr_total += $transaction['cr_amount'];
				
				$balance += $balance_prnt;
				
				if($transaction['dr_amount'] > 0)
					$dr_amount = number_format($transaction['dr_amount'],2);
				else if($transaction['dr_amount'] < 0)
					$dr_amount = '('.number_format($transaction['dr_amount']*-1,2).')';
				else $dr_amount = '';
				
				if($transaction['cr_amount'] > 0)
					$cr_amount = number_format($transaction['cr_amount'],2);
				else if($transaction['dr_amount'] < 0)
					$cr_amount = '('.number_format($transaction['cr_amount']*-1,2).')';
				else $cr_amount = '';
				
				if($balance_prnt > 0)
					$balance_prnt = number_format($balance_prnt,2);
				else if($balance_prnt < 0)
					$balance_prnt = '('.number_format(($balance_prnt*-1),2).')';
				else $balance_prnt = '';
			
				$res[] = ['invoice_date' => $transaction['invoice_date'],
						 'reference_from' => $transaction['reference_from'],
						 'dr_amount' => $transaction['dr_amount'],
						 'cr_amount' => $transaction['cr_amount'],
						 'balance' => $balance,
						 'inv_month' => date('m', strtotime($transaction['invoice_date'])),
						 'due_date' => $transaction['due_date'] ];
			} 
			
		} 
		
		return $res;
	}
	

	private function getOpeningBalance($results) {
		
		$dramount = $cramount = 0;
		foreach($results as $row) {
			
			if($row->transaction_type=='Dr')
				$dramount += $row->amount;
			else
				$cramount += $row->amount;
			
		}
		
		$balance = $dramount - $cramount;
		$type = ($balance > 0)?'Dr':'Cr';
		
		$arrSummarry = ['type' => 'OB', 
						  'amount' => $balance,
						  'transaction_type' => $type
						 ];

		return $arrSummarry;
		
	}
	
	private function SortByAccount($results) {
		$childs = array();
		foreach($results as $item)
			$childs[$item->account_master_id][] = $item;
		return $childs;
	}
	
	private function SortByAccountOS($results) {
		$childs = array();
		foreach($results as $item)
			$childs[$item['account_master_id']][] = $item;
		return $childs;
	}
	
	private function SortByCategory($results) {
		
		$childs = array();
		foreach($results as $items)
		  foreach($items as $item)
			$childs[$item->account_category_id][$item->account_master_id][] = $item;
			
		return $childs;
	}
	
	private function SortByGroup($results) {
		
		$childs = array();
		foreach($results as $items)
		  foreach($items as $item)
			$childs[$item->account_group_id][$item->account_master_id][] = $item;
			
		return $childs;
	}
	
	private function SortByType($results) {
		
		$childs = array();
		foreach($results as $items)
		  foreach($items as $item)
			$childs[$item->category][$item->account_master_id][] = $item;
			
		return $childs;
		
	}
	
	public function searchAccount(Request $request)
	{
		//echo '<pre>';print_r($request->all());exit;
		$data = $pdctransactions = array(); $opn_balnce = null;
		$frmdate = $request->get('date_from');
		$todate = $request->get('date_to');
		$request->merge(['curr_from_date' => $this->acsettings->from_date]); 
		$job_id = ($request->get('job_id')!='')?$request->get('job_id'):null;
		
		//JUN3....
		$infc = $currency = '';
		if($request->get('inFC')==1) {
			$currency = DB::table('currency')->where('id',$request->get('currency_id'))->first();
			if(!$currency) 
				$currency = DB::table('currency')->where('status', 1)->where('is_default', 0)->first();
			
			$infc = $request->get('inFC');
		}
			
		$account_id = $request->get('account_id');
		$is_default = $request->get('is_default');
		
		$obt = DB::table('account_transaction')->where('account_master_id',$account_id)->where('voucher_type','OB')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->where('amount','<',0)->select('id','amount')->first();
		if($obt) {
		    DB::table('account_transaction')->where('id',$obt->id)->update(['amount' => ($obt->amount * -1)]);
		}
		
		$headarr = [];
		if($is_default=='1') { //***************NOT IN USE	
			if($request->get('type')=='statement') {
				$voucher_head = ($infc=='')?'Statement of Account':'Statement of Account in FC';
				if($request->get('is_con')==1) {
					$transactions = $this->makeConsolidated( $this->accountmaster->getPrintViewByAccount($request->all()) );
				} else 
					$transactions = $this->accountmaster->getPrintViewByAccount($request->all());
				
				$pdctransactions = $this->accountmaster->getPDCPrintViewByAccount($request->all());
				//echo '<pre>';print_r($transactions);exit;
				//echo $this->acsettings->from_date.' '.$request->get('date_from');exit; 01-01-2021 01-10-2020
				if( (date('d-m-Y',strtotime($this->acsettings->from_date))!=$request->get('date_from'))) { // && (date('d-m-Y',strtotime($this->acsettings->to_date))!=$request->get('date_to')) 
					$enddate = date('Y-m-d', strtotime('-1 day', strtotime($request->get('date_from'))));
				
					$obtrn = DB::table('account_transaction')->where('voucher_type','OB')->where('account_master_id',$account_id)->where('status',1)
													->where('deleted_at','0000-00-00 00:00:00')->select('invoice_date')->first();
													
					$request->merge(['date_from' =>  $obtrn->invoice_date]);
					$request->merge(['date_to' => $enddate]);
					
					if($transactions[0]->voucher_type!='OB')
						$opn_balnce = $this->getOpeningBalance($this->accountmaster->getPrintViewByAccount($request->all()));
					
					//echo '<pre>'.print_r($opn_balnce);exit;
				} else {
					if(!$transactions || $transactions[0]->voucher_type!='OB') {
						$obtrn = DB::table('account_transaction')->where('voucher_type','OB')->where('account_master_id',$account_id)->where('status',1)
													->where('deleted_at','0000-00-00 00:00:00')->select('invoice_date')->first();
						
						$enddate = date('Y-m-d', strtotime('-1 day', strtotime($request->get('date_from'))));
						$request->merge(['date_from' => $obtrn->invoice_date]);
						$request->merge(['date_to' => $enddate]);
						$opn_balnce = $this->getOpeningBalance($this->accountmaster->getPrintViewByAccount($request->all()));
						//echo '<pre>'.print_r($opn_balnce);exit;
					}
				}
				
			} else if($request->get('type')=='ageing') {
				$accounts = DB::table('account_master')->where('category', $account_id)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id')->get();
				
				foreach($accounts as $row) {
					$results = $this->accountmaster->getAgeingSummary($row->id, $request->all());
					$transactions[] = $this->makeAgeingSummary($this->makeTree($results));
					
				}
				//echo '<pre>';print_r($transactions);exit;
				$voucher_head = ($infc=='')?'Statement of Account - Ageing Summary':'Statement of Account - Ageing Summary in FC';
				$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];
				return view('body.accountenquiry.printgroup')
							->withTransactions($transactions)
							->withVoucherhead($voucher_head)
							->withType($request->get('type'))
							->withTitles($titles)
							->withFromdate($frmdate)
							->withTodate($todate)
							->withUrl('account_enquiry')
							->withSettings($this->acsettings)
							->withId($account_id)
							->withFc($infc)
							->withCurrency($currency)
							->withData($data);
			} else echo 'Please select at least one account!';
		//***************NOT IN USE		
		} else { 
		
			$resultrow = [];//$this->accountmaster->findDetails($account_id);
			$sarr = explode('|',$request->get('salesman_id')); 
			$saleman = isset($sarr[1])?$sarr[1]:null;
			$resultPdc = null;
			//echo $account_id;
			if($request->get('type')=='statement') {
				$voucher_head = ($infc=='')?'Statement of Account':'Statement of Account in FC';
				if($request->get('is_con')==1) {
					//$transactions = $this->SortByAccount($this->makeConsolidated($this->accountmaster->getPrintViewByAccount($request->all())) );
					$transactions1 = $this->makeConsolidated($this->accountmaster->getPrintViewByAccount($request->all()));
					usort($transactions1, array($this, "date_compare_obj"));
					$transactions = $this->SortByAccount($transactions1);
				} else 
					$transactions = $this->SortByAccount($this->accountmaster->getPrintViewByAccount($request->all()));
				
				//echo '<pre>';print_r($transactions);exit;
				$pdctransactions = $this->accountmaster->getPDCPrintViewByAccount($request->all());
				//echo '<pre>';print_r($pdctransactions);exit;
				
				if((date('d-m-Y',strtotime($this->acsettings->from_date))!=$request->get('date_from'))) { 
					foreach($transactions as $key => $transaction ) {
						$resultrow[$key] = $this->accountmaster->findDetails($transaction[0]->account_master_id);
						if($request->get('exclude_ob')==1) {
							$opn_balnce[$key] = null;
						} else {
							$enddate = date('Y-m-d', strtotime('-1 day', strtotime($request->get('date_from'))));
						
							$obtrn = DB::table('account_transaction')->where('voucher_type','OB')->where('account_master_id',$transaction[0]->account_master_id)->where('status',1)
															->where('deleted_at','0000-00-00 00:00:00')->select('invoice_date')->first();
															
							$request->merge(['date_from' =>  $obtrn->invoice_date]);
							$request->merge(['date_to' => $enddate]);
							
							if($transaction[0]->voucher_type!='OB')
								$opn_balnce[$key] = $this->getOpeningBalance($this->accountmaster->getPrintViewByAccount($request->all()));
						}
					}
				} else { 
					foreach($transactions as $key => $transaction ) {
						$resultrow[$key] = $this->accountmaster->findDetails($transaction[0]->account_master_id);
						
						if(!$transaction || $transaction[0]->voucher_type!='OB') {
							
							$obtrn = DB::table('account_transaction')->where('voucher_type','OB')->where('account_master_id',$transaction[0]->account_master_id)->where('status',1)
														->where('deleted_at','0000-00-00 00:00:00')->select('invoice_date')->first();
							
							$enddate = date('Y-m-d', strtotime('-1 day', strtotime($request->get('date_from'))));
							$request->merge(['date_from' => $obtrn->invoice_date]);
							$request->merge(['date_to' => $enddate]);
							$opn_balnce[$key] = $this->getOpeningBalance($this->accountmaster->getPrintViewByAccount($request->all()));
						}
					}
				}
				
			//	echo '<pre>';print_r($resultrow);exit;
				//SORT BY CATEGORY & GETING CATEGORY HEADING...
				if(!empty($request->get('category_id'))) {
					$transactions = $this->SortByCategory($transactions);
					foreach($transactions as $key => $val) {
						$headarr[$key] = DB::table('account_category')->where('id',$key)->select('name AS heading')->first();
					}
				}
				
				//SORT BY GROUP & GETING GROUP HEADING...
				if(!empty($request->get('group_id'))) {
					$transactions = $this->SortByGroup($transactions);
					foreach($transactions as $key => $val) {
						$headarr[$key] = DB::table('account_group')->where('id',$key)->select('name AS heading')->first();
					}
				}
				
				//SORT BY TYPE & GETING TYPE HEADING...
				if(!empty($request->get('type_id'))) {
					$transactions = $this->SortByType($transactions);
					foreach($request->get('type_id') as $key) {
						$headarr[$key] = (object)array('heading'=>$key);
					}
				}
				
				//DEFAULT SORTING...
				if($request->get('is_custom')==0) {
					$transactions = $this->SortByCategory($transactions);
					foreach($transactions as $key => $val) {
						$headarr[$key] = DB::table('account_category')->where('id',$key)->select('name AS heading')->first();
					}
				}

				if($request->get('is_custom')==1 && !empty($request->get('salesman_id'))) {
					$transactions = $this->SortByCategory($transactions);
					foreach($transactions as $key => $val) {
						$headarr[$key] = DB::table('account_category')->where('id',$key)->select('name AS heading')->first();
					}
				}

				
				//echo '<pre>';print_r($headarr);
				
			} else if($request->get('type')=='outstanding') {
				
				$voucher_head = ($infc=='')?'Statement of Account - Outstanding':'Statement of Account - Outstanding in FC';
				$results = $this->accountmaster->getPrintViewByAccount($request->all());//echo '<pre>';print_r($results);exit;
				$transactions = $this->makeSummary2($this->makeTree3($this->makeTree2($results)));
				/* $transactions = $this->makeSummary($this->makeTree($results)); 
				usort($transactions, array($this, "date_compare"));
				$transactions = $this->SortByAccountOS($transactions); */
				
				foreach($transactions as $key => $transaction ) {
					$resultrow[$key] = $dat = $this->accountmaster->findDetails($transaction[0]['account_master_id']);
					
					if($dat->category=='PDCR' || $dat->category=='PDCI') {
						 $osdat = $this->accountmaster->getPDCs($dat, $request->all());
						 $resdat = null;
						 foreach($osdat as $os) {

							if($os->status==0) {
								$resdat[] = $os;
							} 

							if($os->invoice_date > date('Y-m-d', strtotime($request->get('date_to')))) {
								$resdat[] = $os;
							} 
						 }

						 $resultPdc[$key] = $resdat;
					}
					
				}

				//echo '<pre>';print_r($resultPdc);exit;
				/* $results = $this->accountmaster->getPrintViewByAccount($request->all());  
				$transactions = $this->makeSummary($this->makeTree($results));
				usort($transactions, array($this, "date_compare"));
				echo '<pre>';print_r($resultrow);*/
				//echo '<pre>';print_r($transactions);exit; 
				
				/* $pdcres = $this->accountmaster->getPDCPrintViewByAccountOS($request->all(), $resultrow->category);
				$pdctransactions = $this->makeSummaryOS($this->makeTree($pdcres)); */
				
				$pdctransactions = $this->accountmaster->getPDCPrintViewByAccount($request->all(),'OS');
				
			} else if($request->get('type')=='item-statement') {
				
				$voucher_head = 'Statement with Stock';
				$transactions = $this->makeTreeVchr($this->accountmaster->getItemStatement($request->all()));
				
			} else if($request->get('type')=='osmonthly') {
				$voucher_head = 'Statement of Account - Outstanding(Monthly)';
				$resultrow = $this->accountmaster->findDetails($account_id);
				$results = $this->accountmaster->getPrintViewByAccount($request->all()); 
				$transactions = $this->monthly($this->correction($this->makeSummary($this->makeTree($results)),$resultrow));
			
			} else if($request->get('type')=='ageing'){ //AGEING......
				$voucher_head = ($infc=='')?'Statement of Account - Ageing':'Statement of Account - Ageing in FC';
				$results = $this->accountmaster->getPrintViewByAccount($request->all());
				$transactions = $this->makeSummary2($this->makeTree3($this->makeTree2($results)));
				/* $transactions = $this->makeSummary($this->makeTree($results));
				$transactions = $this->SortByAccountOS($transactions); */
				
				foreach($transactions as $key => $transaction ) {
					$resultrow[$key] = $dat = $this->accountmaster->findDetails($transaction[0]['account_master_id']);
					
					if($dat->category=='PDCR' || $dat->category=='PDCI') {
						 $osdat = $this->accountmaster->getPDCs($dat, $request->all());
						 $resdat = null;
						 foreach($osdat as $os) {

							if($os->status==0) {
								$resdat[] = $os;
							} 

							if($os->invoice_date > date('Y-m-d', strtotime($request->get('date_to')))) {
								$resdat[] = $os;
							} 
						 }

						 $resultPdc[$key] = $resdat;
					}
					
				}
				
			} else if($request->get('type')=='ageing_summary'){ //AGEING......	
				//AGEING SUMMARY.....
				$voucher_head = ($infc=='')?'Statement of Account - Ageing Summary':'Statement of Account - Ageing Summary in FC';
				$query = DB::table('account_master')->where('status',1)->where('deleted_at','0000-00-00 00:00:00');
				$inputs = $request->all(); //echo '<pre>';print_r($request->all());exit;
				if($inputs['account_id']=='CUSTOM') {
					if(isset($inputs['type_id']) && $inputs['type_id']!=null)
						$query->whereIn('category', $inputs['type_id']);
					
					if(isset($inputs['group_id']) && $inputs['group_id']!=null)
						$query->whereIn('account_group_id', $inputs['group_id']);

					if(isset($inputs['category_id']) && $inputs['category_id']!=null)
						$query->whereIn('account_category_id', $inputs['category_id']);

				} else {
					$query->where('id', $inputs['account_id']);
				}
					
				$accounts = $query->select('id','category')->get();
				foreach($accounts as $row) {
					$results = $this->accountmaster->getAgeingSummary($row->id, $request->all());
					$transactions[$row->category][] = $this->makeAgeingSummary($this->makeTree($results));
				}
				$resultrow = null;
				//.....AGEING SUMMARY
				
				//echo '<pre>';print_r($transactions);exit;
			}
			
			/* foreach($transactions as $key => $transaction) {
				echo $resultrow[$key]->master_name.'<br>';
			} */
			//exit;
			
			//echo '<pre>';print_r($headarr);exit; 
			//echo '<pre>';print_r($resultrow); exit;
			//echo '<pre>';print_r($transactions);exit;
			//echo '<pre>';print_r($opn_balnce);exit;
			//echo '<pre>';print_r(Session::get('company'));exit;
			$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];
			
			$view = ($this->statement->is_active==1)?'newprint-det':'newprint';
				
			return view('body.accountenquiry.'.$view)
						->withTransactions($transactions)
						->withResultrow($resultrow)
						->withVoucherhead($voucher_head)
						->withType($request->get('type'))
						->withTitles($titles)
						->withFromdate($frmdate)
						->withTodate($todate)
						->withUrl('account_enquiry')
						->withSettings($this->acsettings)
						->withId($account_id)
						->withIspdc($request->get('is_pdc'))
						->withIscon($request->get('is_con'))
						->withPdcs($pdctransactions)
						->withOpenbalance($opn_balnce)
						->withFc($infc)
						->withCurrency($currency)
						->withJobid($job_id)
						->withHeadarr($headarr)
						->withIscustom($request->get('is_custom'))
						->withTypeid(!empty($request->get('type_id'))?implode(',',$request->get('type_id')):'')
						->withCatid(!empty($request->get('category_id'))?implode(',',$request->get('category_id')):'')
						->withGroupid(!empty($request->get('group_id'))?implode(',',$request->get('group_id')):'')
						->withPdclist($resultPdc)
						->withSaleman($saleman)
						->withData($data);
		}//....JUN3
	}
	
	
	public function dataExport(Request $request)
	{
		$data = $pdcreports = array();
		//$request->merge(['type' => 'export']);
		$request->merge(['curr_from_date' => $this->acsettings->from_date]);
		$datareport[] = [strtoupper(Session::get('company')),'','',''];
		$datareport[] = ['','','','','','',''];
		$account_id = $request->get('account_id');
		$is_default = $request->get('is_default');

		($request->get('group_id')!='')?($request->merge(['group_id' => explode(',',$request->get('group_id'))])):null;
		($request->get('type_id')!='')?($request->merge(['type_id' => explode(',',$request->get('type_id'))])):null;
		($request->get('category_id')!='')?($request->merge(['category_id' => explode(',',$request->get('category_id'))])):null;
		if($is_default==1) {
			
			if($request->get('type')=='ageing') {
				$accounts = DB::table('account_master')->where('category', $account_id)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id')->get();
				
				foreach($accounts as $row) {
					$results = $this->accountmaster->getAgeingSummary($row->id, $request->all());
					$transactions[] = $this->makeAgeingSummary($this->makeTree($results));
					
				}
				//echo '<pre>';print_r($transactions);exit;
				$voucher_head = 'Statement of Account - Ageing Summary';
				$datareport[] = ['','','',strtoupper($voucher_head),'',''];	
				$datareport[] = ['','','','','','',''];
				$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];
				
				$datareport[] = ['Account ID','Account Name','0-30','31-60','61-90','91-120','Above 121','Total'];
				$cr_total = 0; $dr_total = 0; $balance = 0; $balance_prnt = $amt1T = $amt2T = $amt3T = $amt4T = $amt5T = 0;
				
				foreach($transactions as $results) {
					if(count($results) > 0) {
						$amt1 = $amt2 = $amt3 = $amt4 = $amt5 = $lntotal = 0;
						foreach($results as $transaction) {
							
							$cr_amount = ''; $dr_amount = '';
							$balance_prnt = $transaction['dr_amount'] - $transaction['cr_amount'];	
							$balance += $balance_prnt;
						
							$nodays = date_diff(date_create($transaction['invoice_date']),date_create(date('Y-m-d')));
							
							if($nodays->format("%a%") < 30) {
								$amt1 += $balance_prnt;
								$amt1T += $balance_prnt;
							} else if($nodays->format("%a%") > 30 && $nodays->format("%a%") < 60) {
								$amt2 += $balance_prnt;
								$amt2T += $balance_prnt;
							} else if($nodays->format("%a%") > 60 && $nodays->format("%a%") < 90) {
								$amt3 += $balance_prnt;
								$amt3T += $balance_prnt;
							} else if($nodays->format("%a%") > 90 && $nodays->format("%a%") < 120) {
								$amt4 += $balance_prnt;
								$amt4T += $balance_prnt;
							} else if($nodays->format("%a%") > 120) {
								$amt5 += $balance_prnt;
								$amt5T += $balance_prnt;
							}
							$lntotal = $amt1+$amt2+$amt3+$amt4+$amt5;
							
						}
						
						if($balance_prnt != 0) { 
														
							if($balance_prnt > 0)
								$balance_prnt = number_format($balance_prnt,2);
							else if($balance_prnt < 0)
								$balance_prnt = '('.number_format(($balance_prnt*-1),2).')';
							else $balance_prnt = '';
							
							if($amt1 > 0)
								$amt1 = number_format($amt1,2);
							else if($amt1 < 0)
								$amt1 = '('.number_format(($amt1*-1),2).')';
							else $amt1 = '';
							
							if($amt2 > 0)
								$amt2= number_format($amt2,2);
							else if($amt2 < 0)
								$amt2 = '('.number_format(($amt2*-1),2).')';
							else $amt2 = '';
							
							if($amt3 > 0)
								$amt3= number_format($amt3,2);
							else if($amt3 < 0)
								$amt3 = '('.number_format(($amt3*-1),2).')';
							else $amt3 = '';
							
							if($amt4 > 0)
								$amt4= number_format($amt4,2);
							else if($amt4 < 0)
								$amt4 = '('.number_format(($amt4*-1),2).')';
							else $amt4 = '';
							
							if($amt5 > 0)
								$amt5= number_format($amt5,2);
							else if($amt5 < 0)
								$amt5 = '('.number_format(($amt5*-1),2).')';
							else $amt5 = '';
							
							if($lntotal > 0)
								$lntotal= number_format($lntotal,2);
							else if($lntotal < 0)
								$lntotal = '('.number_format(($lntotal*-1),2).')';
							else $lntotal = '';
						 }
						 
						 $datareport[] = ['acid' => $transaction['acid'],
										  'acname'	=> $transaction['acname'],
										  'amt1'	=> $amt1,
										  'amt2'	=> $amt2,
										  'amt3'	=> $amt3,
										  'amt4'	=> $amt4,
										  'amt5'	=> $amt5,
										  'lntotal'	=> $lntotal
											];
					}
				}
				
				if($balance > 0)
					$balance = number_format($balance,2);
				else if($balance < 0)
					$balance = '('.number_format(($balance*-1),2).')';
				
				if($amt1T > 0)
					$amt1T = number_format($amt1T,2);
				else if($amt1T < 0)
					$amt1T = '('.number_format(($amt1T*-1),2).')';
				
				if($amt2T > 0)
					$amt2T = number_format($amt2T,2);
				else if($amt2T < 0)
					$amt2T = '('.number_format(($amt2T*-1),2).')';
				else $amt2T = '';
				
				if($amt3T > 0)
					$amt3T = number_format($amt3T,2);
				else if($amt3T < 0)
					$amt3T = '('.number_format(($amt3T*-1),2).')';
				else $amt3T = '';
				
				if($amt4T > 0)
					$amt4T = number_format($amt4T,2);
				else if($amt4T < 0)
					$amt4T = '('.number_format(($amt4T*-1),2).')';
				else $amt4T = '';
				
				if($amt5T > 0)
					$amt5T = number_format($amt5T,2);
				else if($amt5T < 0)
					$amt5T = '('.number_format(($amt5T*-1),2).')';
				else $amt5T = '';
				
				$datareport[] = ['','Total',$amt1T,$amt2T,$amt3T,$amt4T,$amt5T,$balance];
			} 
			
		} else { 
		
				$resultrow = [];
				$opn_balnce = [];
				if($request->get('type')=='statement') {
					$voucher_head = 'Statement of Account';
					$datareport[] = ['','','',strtoupper($voucher_head),'',''];	
					$datareport[] = ['','','','','','',''];
					
					if($request->get('is_con')==1) {
						$reports = $this->SortByAccount($this->makeConsolidated( $this->accountmaster->getPrintViewByAccount($request->all())) );
					} else 
						$reports = $this->SortByAccount($this->accountmaster->getPrintViewByAccount($request->all()));
					
					//echo '<pre>'; print_r($request->all());exit;
					//GETTING OPENING BALANCE.....
					if( (date('d-m-Y',strtotime($this->acsettings->from_date))!=$request->get('date_from'))) { // && (date('d-m-Y',strtotime($this->acsettings->to_date))!=$request->get('date_to')) 
						foreach($reports as $key => $report ) {
							$resultrow[$key] = $this->accountmaster->findDetails($report[0]->account_master_id);
							
							$enddate = date('Y-m-d', strtotime('-1 day', strtotime($request->get('date_from'))));
						
							$obtrn = DB::table('account_transaction')->where('voucher_type','OB')->where('account_master_id',$report[0]->account_master_id)->where('status',1)
															->where('deleted_at','0000-00-00 00:00:00')->select('invoice_date')->first();
															
							$request->merge(['date_from' =>  $obtrn->invoice_date]);
							$request->merge(['date_to' => $enddate]);
							$fromdate = $obtrn->invoice_date;
							
							if($report[0]->voucher_type!='OB')
								$opn_balnce[$key] = $this->getOpeningBalance($this->accountmaster->getPrintViewByAccount($request->all()));
					
						}
						//echo '<pre>'.print_r($reports);exit;
					} else {
						foreach($reports as $key => $report ) {
							$resultrow[$key] = $this->accountmaster->findDetails($report[0]->account_master_id);
							if(!$report || $report[0]->voucher_type!='OB') {
								$obtrn = DB::table('account_transaction')->where('voucher_type','OB')->where('account_master_id',$report[0]->account_master_id)->where('status',1)
															->where('deleted_at','0000-00-00 00:00:00')->select('invoice_date')->first();
								
								$enddate = date('Y-m-d', strtotime('-1 day', strtotime($request->get('date_from'))));
								$request->merge(['date_from' => $obtrn->invoice_date]);
								$request->merge(['date_to' => $enddate]);
								$fromdate = $obtrn->invoice_date;
								
								$opn_balnce[$key] = $this->getOpeningBalance($this->accountmaster->getPrintViewByAccount($request->all()));
								//echo '<pre>'.print_r($opn_balnce);exit;
							}
						}
					}
					
					//echo '<pre>';print_r($opn_balnce);
					//echo '<pre>';print_r($reports);exit;
						
					$pdcreports = $this->accountmaster->getPDCPrintViewByAccount($request->all());
					
					$i=0;
					
				$Sbalance_prnt = $Sdr_total = $Scr_total = 0;	//NOV2
				$Gbalance_prnt = $Gdr_total = $Gcr_total = 0; //NOV2
				foreach($reports as $key => $report) {
					
					$datareport[] = ['Acc. Name:'.$resultrow[$key]->master_name];
					$datareport[] = ['Acc. ID:'.$resultrow[$key]->account_id,($resultrow[$key]->phone!='')?' Ph:'.$resultrow[$key]->phone:'','TRN No:'.$resultrow[$key]->vat_no];
					$datareport[] = ['','','','','','',''];
					$datareport[] = ['Type','No','Date','Description', 'Reference','Debit','Credit','Balance'];
					
					$cr_total = 0; $dr_total = 0; $balance = 0; //NOV2
					//OPENING BALANCE ENTRY...
					if(isset($opn_balnce[$key])) {
						
						if($opn_balnce[$key]['transaction_type']=='Dr') { 
							$balance = $opn_balnce[$key]['amount'];
							$dr_total = ($balance < 0)?($balance*-1):$balance;
						}
						
						if($opn_balnce[$key]['transaction_type']=='Cr') { 
							$balance = $opn_balnce[$key]['amount'];
							$cr_total = ($balance < 0)?($balance*-1):$balance;
						}
						
						if($balance < 0) {
							$arr = explode('-', $balance);
							$balance_prnt = $arr[1];
						} else 
							$balance_prnt = $balance;
													
						$datareport[] = [ 'type' => $opn_balnce[$key]['type'],
										  'no' => '',
										  'date' => date('d-m-Y', strtotime($fromdate)),
										  'desc' => '',
										  'ref' => 'Total',
										  'debit' => (float)$dr_total,
										  'credit' => (float)$cr_total,
										  'bal' => (float)$balance_prnt
										];
					}
					
					foreach ($report as $row) {
							$i++;
							$date = ($row->invoice_date=='0000-00-00' || $row->invoice_date=='01-01-1970')?date('d-m-Y', strtotime($this->acsettings->from_date)):date('d-m-Y', strtotime($row->invoice_date));
							$desc = ($row->voucher_type=="OB")?'Opening Balance':$row->description;
							$ref = ($row->reference_from=="")?$row->reference:$row->reference_from;
							
							$cr_amount = ''; $dr_amount = '';
							if($row->transaction_type=='Cr') {
								$cr_amount = $row->amount;
								if($row->amount >= 0) {
									$cr_total += $row->amount;
									$balance -= $row->amount;
								} else {
									$cr_total -= $row->amount;
									$balance += $row->amount;
								}
							} else if($row->transaction_type=='Dr') {
								$dr_amount = $row->amount;
								$dr_total += $row->amount;
								$balance += $row->amount;
							}
							
							if($balance < 0) {
								$arr = explode('-', $balance);
								$balance_prnt = $arr[1];
							} else 
								$balance_prnt = $balance;
												
							$datareport[] = [ 'type' => $row->voucher_type,
											  'no' => $row->reference,
											  'date' => $date,
											  'desc' => $desc,
											  'ref' => $ref,
											  'debit' => (float)$dr_amount,
											  'credit' => (float)$cr_amount,
											  'bal' => (float)$balance_prnt
											];
											
						
					}
					$Gdr_total += $dr_total; $Gcr_total += $cr_total;//NOV2
					
					$datareport[] = [ 'type' => '',
											  'no' => '',
											  'date' => '',
											  'desc' => '',
											  'ref' => 'Total',
											  'debit' => (float)$dr_total,
											  'credit' => (float)$cr_total,
											  'bal' => (float)$balance_prnt
											];
											
					if($request->get('is_pdc')==1) { 
						if(count($pdcreports) > 0) {
							$datareport[] = ['','','','','','',''];
							$datareport[] = ['','','',strtoupper('Statement with PDC Details'),'',''];	
							$datareport[] = ['Type','No','Date','Cheque No','Cheque Date','Description','Reference','PDC Issued','PDC Received','Balance'];
							
							$cr_total = 0; $dr_total = 0; $balance = 0;
							foreach($pdcreports as $transaction) {
								$received = ''; $issued = '';
								if($transaction->voucher_type=='PDCR') {
									$received = number_format($transaction->amount,2);
									if($transaction->amount >= 0) {
										$cr_total += $transaction->amount;
										$balance = bcsub($balance, $transaction->amount, 2);
									} else {
										$cr_total -= $transaction->amount;
										$balance += $transaction->amount;
									}
								} else if($transaction->voucher_type=='PDCI') {
									$issued = number_format($transaction->amount,2);
									$dr_total += $transaction->amount;
									$balance += $transaction->amount;
								}
								
								if($balance < 0) {
									$arr = explode('-', $balance);
									$balance_prnt = '('.number_format($arr[1],2).')';
								} else 
									$balance_prnt = number_format($balance,2);
								
								$datareport[] = ['voucher_type' => ($transaction->voucher_type=='PDCR')?'RV':'PV',
																				 'voucher_no'			=> $transaction->voucher_no,
																				 'vdate'	=> ($transaction->voucher_date=='0000-00-00' || $transaction->voucher_date=='01-01-1970')?date('d-m-Y', strtotime($this->acsettings->from_date)):date('d-m-Y', strtotime($transaction->voucher_date)),
																				 'cheque_no'	=> $transaction->cheque_no,
																				 'cheque_date'	=> ($transaction->cheque_date=='0000-00-00' || $transaction->cheque_date=='01-01-1970')?'':date('d-m-Y', strtotime($transaction->cheque_date)),
																				 'description'	=> $transaction->description.' '.$transaction->master_name,
																				 'reference'	=> $transaction->reference,
																				 'issued'	=> $issued,
																				 'received'	=> $received,
																				 'balance_prnt'	=> $balance_prnt
																				];
							}
							
							$Gdr_total += $dr_total; $Gcr_total += $cr_total;//NOV2
							$datareport[] = ['','','','','','','Total',(float)$dr_total,(float)$cr_total,(float)$balance_prnt];
						}
					}
					//NOV2
					$Sdr_total += $Gdr_total; $Scr_total += $Gcr_total;
					if($request->get('is_custom')==1) {
						$Gbalance_prnt = $Gdr_total-$Gcr_total;
						if($Gbalance_prnt < 0) {
							if($Sbalance_prnt != 0)
								$Gbalance_prnt = $Gbalance_prnt;
						
							$arr = explode('-', $Gbalance_prnt);
							$Gbalance_prnt = $arr[1];
						} else {
							if($Gbalance_prnt > 0)
								$Gbalance_prnt = $Gbalance_prnt;
							$Gbalance_prnt = $Gbalance_prnt;
						}
					}
				}
				
				//NOV2
				$datareport[] = ['','','','','','','',''];
				$datareport[] = ['','','','','Grand Total',$Gdr_total,$Gcr_total,$Gbalance_prnt]; 
				
					
				//NOV2
				if($request->get('is_custom')==1) {
					$Sdr_total='';$Scr_total='';
					$Sbalance_prnt = (int)$Sdr_total-(int)$Scr_total;
					
					if($Sbalance_prnt < 0) {
						if($Sbalance_prnt != 0)
							$Sbalance_prnt = $Sbalance_prnt;
					
						$arr = explode('-', $Sbalance_prnt);
						$Sbalance_prnt = '('.number_format($arr[1],2).')';
					} else {
						if($Sbalance_prnt > 0)
							$Sbalance_prnt = $Sbalance_prnt;
						$Sbalance_prnt = number_format($Sbalance_prnt,2);
					}
				}
				//$datareport[] = ['','','','','Grand Total',$Sdr_total,$Scr_total,$Sbalance_prnt];
											
				} else if($request->get('type')=='outstanding') {
					
					$voucher_head = 'Statement of Account - Outstanding';
					$datareport[] = ['','','',strtoupper($voucher_head),'',''];	
					$datareport[] = ['','','','','','',''];
					
					$results = $this->accountmaster->getPrintViewByAccount($request->all());
					$reports = $this->makeSummary2($this->makeTree3($this->makeTree2($results))); 
					/* $reports = $this->makeSummary($this->makeTree($results)); 
					usort($reports, array($this, "date_compare"));
					$reports = $this->SortByAccountOS($reports); */
					$grandTotal = 0; //NOV2
					foreach($reports as $key => $report ) {
						$resultrow[$key] = $dat = $this->accountmaster->findDetails($report[0]['account_master_id']);
						
						if($dat->category=='PDCR' || $dat->category=='PDCI') {
							$osdat = $this->accountmaster->getPDCs($dat, $request->all());
							$resdat = null;
							foreach($osdat as $os) {

								if($os->status==0) {
									$resdat[] = $os;
								} 

								if($os->invoice_date > date('Y-m-d', strtotime($request->get('date_to')))) {
									$resdat[] = $os;
								} 
							}

							$resultPdc[$key] = $resdat;
						}
					}
					
					$pdcreports = $this->accountmaster->getPDCPrintViewByAccount($request->all(),'OS');
					
				foreach($reports as $key => $report) {
						
					//$datareport[] = ['Invoice Date','Reference','Description','Debit', 'Credit','Balance'];
					$cr_total = 0; $dr_total = 0; $balance = $balance1 = 0;
					$amtdate1 = $amtdate2 = $amtdate3 = $amtdate4 = $amtdate5 = 0; //echo '<pre>';print_r($reports);exit;
					
					foreach($report as $trans) {
						$balance_prnt1 = bcsub($trans['dr_amount'], $trans['cr_amount'], 2);
						if($resultrow[$key]->category=='CUSTOMER' && $balance_prnt1 !=0)
							$balance1 += $balance_prnt1;
						else if($resultrow[$key]->category=='SUPPLIER' && $balance_prnt1 !=0)
							$balance1 += $balance_prnt1;
					} 
					
					if($balance1 != 0) {
						$datareport[] = ['Acc. Name:'.$resultrow[$key]->master_name];
						$datareport[] = ['Acc. ID:'.$resultrow[$key]->account_id,($resultrow[$key]->phone!='')?' Ph:'.$resultrow[$key]->phone:'','TRN No:'.$resultrow[$key]->vat_no];
						$datareport[] = ['','','','','','',''];
						$datareport[] = ['Invoice Date','Reference','Description','Debit', 'Credit','Balance'];
						
						foreach ($report as $row) {
							$balance_prnt = $amtdate = bcsub($row['dr_amount'], $row['cr_amount'], 2); //$balance_prnt = $row['dr_amount'] - $row['cr_amount'];	
							//chng strt
							if(($resultrow[$key]->category=='CUSTOMER' && $balance_prnt !=0) || ($resultrow[$key]->category=='VATIN' && $balance_prnt !=0)) {
								$dr_total += $row['dr_amount'];
								$cr_total += $row['cr_amount'];
								$balance += $balance_prnt;
								
								if($row['dr_amount'] > 0)
									$dr_amount = $row['dr_amount'];
								else if($row['dr_amount'] < 0)
									$dr_amount = $row['dr_amount']*-1;
								else $dr_amount = '';
								
								if($row['cr_amount'] > 0)
									$cr_amount = $row['cr_amount'];
								else if($row['dr_amount'] < 0)
									$cr_amount = $row['cr_amount']*-1;
								else $cr_amount = '';
								
								if($balance_prnt > 0)
									$balance_prnt = $balance_prnt;
								else if($balance_prnt < 0)
									$balance_prnt = ($balance_prnt*-1);
								else $balance_prnt = '';
								
								$nodays = date_diff(date_create($row['invoice_date']), date_create(date('Y-m-d')));
								
								if($row['dr_amount'] > 0)
									$dr_amount = $row['dr_amount'];
								else if($row['dr_amount'] < 0)
									$dr_amount = $row['dr_amount']*-1;
								else $dr_amount = '';
								
								if($row['cr_amount'] > 0)
									$cr_amount = $row['cr_amount'];
								else if($row['dr_amount'] < 0)
									$cr_amount = $row['cr_amount']*-1;
								else $cr_amount = '';
								
								$datareport[] = [ 'date' => date('d-m-Y', strtotime($row['invoice_date'])), //Date::dateTimeToExcel($row['invoice_date']),
												  'ref' => $row['reference_from'],
												  'descption' => $row['description'],
												  'dueamt' => (float)$dr_amount,
												  'credit' => (float)$cr_amount,
												  'debit' => (float)$balance_prnt
												];
							
							} else if(($resultrow[$key]->category=='SUPPLIER' && $balance_prnt !=0) || ($resultrow[$key]->category=='VATOUT' && $balance_prnt !=0)){
								
								$dr_total += $row['dr_amount'];
								$cr_total += $row['cr_amount'];
								
								$balance += $balance_prnt;
								
								if($row['dr_amount'] > 0)
									$dr_amount = $row['dr_amount'];
								else if($row['dr_amount'] < 0)
									$dr_amount = $row['dr_amount']*-1;
								else $dr_amount = '';
								
								if($row['cr_amount'] > 0)
									$cr_amount = $row['cr_amount'];
								else if($row['dr_amount'] < 0)
									$cr_amount = $row['cr_amount']*-1;
								else $cr_amount = '';
								
								if($balance_prnt > 0)
									$balance_prnt = $balance_prnt;
								else if($balance_prnt < 0)
									$balance_prnt = ($balance_prnt*-1);
								else $balance_prnt = '';
								
								$nodays = date_diff(date_create($row['invoice_date']), date_create(date('Y-m-d')));
								
								if($row['dr_amount'] > 0)
									$dr_amount = $row['dr_amount'];
								else if($row['dr_amount'] < 0)
									$dr_amount = $row['dr_amount']*-1;
								else $dr_amount = '';
								
								if($row['cr_amount'] > 0)
									$cr_amount = $row['cr_amount'];
								else if($row['dr_amount'] < 0)
									$cr_amount = $row['cr_amount']*-1;
								else $cr_amount = '';
								
								$datareport[] = [ 'date' => date('d-m-Y', strtotime($row['invoice_date'])),
												  'ref' => $row['reference_from'],
												  'descption' => $row['description'],
												  'dueamt' => (float)$dr_amount,
												  'credit' => (float)$cr_amount,
												  'debit' => (float)$balance_prnt
												];
								
							}
							// chan end
							
						}
						
						$datareport[] = [ 'date' => '',
										  'ref' => '',
										  'descption' => 'Total',
										  'dueamt' => (float)$dr_total,
										  'credit' => (float)$cr_total,
										  'debit' => (float)$balance
										];
					}				
					if($request->get('is_pdc')==1) { 
						if(count($pdcreports) > 0) {
							$datareport[] = ['','','','','','',''];
							$datareport[] = ['','','',strtoupper('Outstanding with PDC Details'),'',''];	
							$datareport[] = ['Invoice Date','Reference','Cheque No','Cheque Date','Debit','Credit','Balance'];
							$cr_total = 0; $dr_total = 0; $balance = 0;
							foreach($pdcreports as $transaction) {
								$received = ''; $issued = '';
								if($transaction->voucher_type=='PDCR') {
									$received = $transaction->amount;
									if($transaction->amount >= 0) {
										$cr_total += $transaction->amount;
										$balance = bcsub($balance, $transaction->amount, 2);
									} else {
										$cr_total -= $transaction->amount;
										$balance += $transaction->amount;
									}
								} else if($transaction->voucher_type=='PDCI') {
									$issued = $transaction->amount;
									$dr_total += $transaction->amount;
									$balance += $transaction->amount;
								}
								
								if($balance < 0) {
									$arr = explode('-', $balance);
									$balance_prnt = $arr[1];
								} else 
									$balance_prnt = $balance;
								
								$datareport[] = [ 'date' => ($transaction->voucher_date=='0000-00-00' || $transaction->voucher_date=='01-01-1970')?date('d-m-Y', strtotime($this->acsettings->from_date)):date('d-m-Y', strtotime($transaction->voucher_date)),
										  'ref' => $transaction->reference,
										  'duedate' => $transaction->cheque_no,
										  'dueamt' => ($transaction->cheque_date=='0000-00-00' || $transaction->cheque_date=='01-01-1970')?'':date('d-m-Y', strtotime($transaction->cheque_date)),
										  'credit' => (float)$issued,
										  'debit' => (float)$received,
										  'balance_prnt'	=> (float)$balance_prnt
										];
							}
							
							$datareport[] = ['','','','Total', (float)$dr_total, (float)$cr_total, (float)$balance_prnt];
						}
						
					}
					
						//NOV2
						$grandTotal += $balance;
				  }
				  
				  //NOV2
				  if($request->get('is_custom')==1)
						$datareport[] = ['','','Grand Total','','',$grandTotal]; 
					
				} else if($request->get('type')=='ageinggroup') {
					
					$accounts = DB::table('account_master')->where('category', $request->get('account_id'))->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id')->get();
						
					foreach($accounts as $row) {
						$results = $this->accountmaster->getAgeingSummary($row->id, $request->all());
						$transactions[] = $this->makeAgeingSummary($this->makeTree($results));
						
					}
					
					$datareport[] = ['Account ID','Account Name','0-30', '31-60','61-90', '91-120', 'Above 121','Total'];
					$cr_total = 0; $dr_total = 0; $balance = 0; $balance_prnt = 0;
					$amt1T = $amt2T = $amt3T = $amt4T = $amt5T = 0;
					
					$voucher_head = 'Statement of Account - Ageing Summary';
					$datareport[] = ['','','',strtoupper($voucher_head),'',''];	
					$datareport[] = ['','','','','','',''];
					$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];
					
					foreach($transactions as $results) {
						
						if(count($results) > 0) { 
							$amt1 = $amt2 = $amt3 = $amt4 = $amt5 = $lntotal = 0;
							
							foreach($results as $transaction) {
								
								$cr_amount = ''; $dr_amount = '';
								$balance_prnt = $transaction['dr_amount'] - $transaction['cr_amount'];	
								$balance += $balance_prnt;
								$nodays = date_diff(date_create($transaction['invoice_date']),date_create(date('Y-m-d')));
								
								if($nodays->format("%a%") <= 30) {
									$amt1 += $balance_prnt;
									$amt1T += $balance_prnt;
								} else if($nodays->format("%a%") > 30 && $nodays->format("%a%") <= 60) {
									$amt2 += $balance_prnt;
									$amt2T += $balance_prnt;
								} else if($nodays->format("%a%") > 60 && $nodays->format("%a%") <= 90) {
									$amt3 += $balance_prnt;
									$amt3T += $balance_prnt;
								} else if($nodays->format("%a%") > 90 && $nodays->format("%a%") <= 120) {
									$amt4 += $balance_prnt;
									$amt4T += $balance_prnt;
								} else if($nodays->format("%a%") > 120) {
									$amt5 += $balance_prnt;
									$amt5T += $balance_prnt;
								}
								$lntotal = $amt1+$amt2+$amt3+$amt4+$amt5;
							}
							
							if($balance_prnt != 0) { 
							
								if($balance_prnt > 0)
									$balance_prnt = number_format($balance_prnt,2);
								else if($balance_prnt < 0)
									$balance_prnt = '('.number_format(($balance_prnt*-1),2).')';
								else $balance_prnt = '';
								
								if($amt1 > 0)
									$amt1 = number_format($amt1,2);
								else if($amt1 < 0)
									$amt1 = '('.number_format(($amt1*-1),2).')';
								else $amt1 = '';
								
								if($amt2 > 0)
									$amt2= number_format($amt2,2);
								else if($amt2 < 0)
									$amt2 = '('.number_format(($amt2*-1),2).')';
								else $amt2 = '';
								
								if($amt3 > 0)
									$amt3= number_format($amt3,2);
								else if($amt3 < 0)
									$amt3 = '('.number_format(($amt3*-1),2).')';
								else $amt3 = '';
								
								if($amt4 > 0)
									$amt4= number_format($amt4,2);
								else if($amt4 < 0)
									$amt4 = '('.number_format(($amt4*-1),2).')';
								else $amt4 = '';
								
								if($amt5 > 0)
									$amt5= number_format($amt5,2);
								else if($amt5 < 0)
									$amt5 = '('.number_format(($amt5*-1),2).')';
								else $amt5 = '';
								
								if($lntotal > 0)
									$lntotal= number_format($lntotal,2);
								else if($lntotal < 0)
									$lntotal = '('.number_format(($lntotal*-1),2).')';
								else $lntotal = '';
							}
							
							$datareport[] = [ 'acid' => $transaction['acid'],
											  'acname' => $transaction['acname'],
											  'd1' => $amt1,
											  'd2' => $amt2,
											  'd3' => $amt3,
											  'd4' => $amt4,
											  'd5' => $amt5,
											  'lntotal' => $lntotal
										];
						}
					}
					
					if($balance > 0)
						$balance = number_format($balance,2);
					else if($balance < 0)
						$balance = '('.number_format(($balance*-1),2).')';
					
					if($amt1T > 0)
						$amt1T = number_format($amt1T,2);
					else if($amt1T < 0)
						$amt1T = '('.number_format(($amt1T*-1),2).')';
					
					if($amt2T > 0)
						$amt2T = number_format($amt2T,2);
					else if($amt2T < 0)
						$amt2T = '('.number_format(($amt2T*-1),2).')';
					else $amt2T = '';
					
					if($amt3T > 0)
						$amt3T = number_format($amt3T,2);
					else if($amt3T < 0)
						$amt3T = '('.number_format(($amt3T*-1),2).')';
					else $amt3T = '';
					
					if($amt4T > 0)
						$amt4T = number_format($amt4T,2);
					else if($amt4T < 0)
						$amt4T = '('.number_format(($amt4T*-1),2).')';
					else $amt4T = '';
					
					if($amt5T > 0)
						$amt5T = number_format($amt5T,2);
					else if($amt5T < 0)
						$amt5T = '('.number_format(($amt5T*-1),2).')';
					else $amt5T = '';
					
					$datareport[] = ['','Total:',$amt1T,$amt2T,$amt3T,$amt4T,$amt5T,$balance];
					
					Excel::create($voucher_head, function($excel) use ($datareport,$voucher_head) {

						// Set the spreadsheet title, creator, and description
						$excel->setTitle($voucher_head);
						$excel->setCreator('Profit ACC 365')->setCompany(Session::get('company'));
						$excel->setDescription($voucher_head);

						// Build the spreadsheet, passing in the payments array
						$excel->sheet('sheet1', function($sheet) use ($datareport) {
							$sheet->fromArray($datareport, null, 'A1', false, false);
						});

					})->download('xlsx');
					
				//....JUN3	
				} else if($request->get('type')=='osmonthly') {
					
						$voucher_head = 'Statement of Account - Outstanding(Monthly)';
						$datareport[] = ['','','',strtoupper($voucher_head),'',''];	
						$datareport[] = ['','','','','','',''];
						$results = $this->accountmaster->getPrintViewByAccount($request->all()); 
						$transactions = $this->monthly($this->correction($this->makeSummary($this->makeTree($results)),$resultrow));
						$datareport[] = [$resultrow->master_name.' ('.$resultrow->account_id.')','','','','','',''];
						$datareport[] = [$resultrow->address,($resultrow->phone!='')?' Ph:'.$resultrow->phone:'','TRN No:'.$resultrow->vat_no];
						$datareport[] = ['','','','','','',''];
						$datareport[] = ['Inv.Date','Reference','Due Date','Debit','Credit','Balance'];
						
						foreach($transactions as $key => $trans) {
							
							  $cr_total = 0; $dr_total = 0; $balance = 0; $osbaltot = 0;
							  //usort($trans, 'date_compare');
							  usort($trans, array($this, "date_compare"));
							  $dateObj   = DateTime::createFromFormat('!m', $key);
							  $monthName = $dateObj->format('F'); 
							  $datareport[] = [$monthName,'','',''];
							  
							  foreach($trans as $transaction) {
								  
								$dr_total += $transaction['dr_amount'];
								$cr_total += $transaction['cr_amount'];
								$balance += $transaction['balance'];
								$osbalance = $transaction['dr_amount'] - $transaction['cr_amount'];
								$osbaltot += $osbalance;
								
								if($osbalance > 0)
									$osbalance = number_format($osbalance,2);
								else if($osbalance < 0)
									$osbalance = '('.number_format(($osbalance*-1),2).')';
								
								 $datareport[] = ['invoice_date' => date('d-m-Y', strtotime($transaction['invoice_date'])),
												  'reference_from'	=> $transaction['reference_from'],
												  'due_date'		=> date('d-m-Y', strtotime($transaction['due_date'])),
												  'dr_amount'	=> ($transaction['dr_amount']!=0)?$transaction['dr_amount']:'',
												  'cr_amount'	=> ($transaction['cr_amount']!=0)?$transaction['cr_amount']:'',
												  'osbalance'		=> $osbalance
												 ];
								
							  }
							  
							if($dr_total > 0)
								$dr_total = number_format($dr_total,2);
							else if($dr_total < 0)
								$dr_total = '('.number_format(($dr_total*-1),2).')';
								
							if($cr_total > 0)
								$cr_total = number_format($cr_total,2);
							else if($cr_total < 0)
								$cr_total = '('.number_format(($cr_total*-1),2).')';
							
							if($balance > 0)
								$balance = number_format($balance,2);
							else if($balance < 0)
								$balance = '('.number_format(($balance*-1),2).')';
							
							if($osbaltot > 0)
								$osbaltot = number_format($osbaltot,2);
							else if($osbaltot < 0)
								$osbaltot = '('.number_format(($osbaltot*-1),2).')';
							
							$datareport[] = ['','','','','Total:',$osbaltot];
						}
						
				} else {
					
					$voucher_head = 'Statement of Account - Ageing';
					$datareport[] = ['','','',strtoupper($voucher_head),'',''];	
					$datareport[] = ['','','','','','',''];
					
					$results = $this->accountmaster->getPrintViewByAccount($request->all());
					$reports = $this->makeSummary2($this->makeTree3($this->makeTree2($results))); //number_format
					/* $reports = $this->makeSummary($this->makeTree($results));
					$reports = $this->SortByAccountOS($reports); */
				
					foreach($reports as $key => $report ) {
						$resultrow[$key] = $this->accountmaster->findDetails($report[0]['account_master_id']);
					}
					
				foreach($reports as $key => $report) {
					
					$datareport[] = [$resultrow[$key]->master_name,'Acc. ID:'.$resultrow[$key]->account_id,($resultrow[$key]->phone!='')?' Ph:'.$resultrow[$key]->phone:'','TRN No:'.$resultrow[$key]->vat_no];
					$datareport[] = ['','','','','','',''];
					
					$datareport[] = ['Date','Reference','Description','Due Amount','0-30', '31-60','61-90', '91-120', 'Above 121'];
					$cr_total = 0; $dr_total = 0; $balance = 0; $balance_prnt = 0;
					$amt1T = $amt2T = $amt3T = $amt4T = $amt5T = 0;
					
					foreach ($report as $row) {
						$cr_amount = ''; $dr_amount = '';
						$balance_prnt = $row['dr_amount'] - $row['cr_amount'];	
						$balance += $balance_prnt;
					
						$nodays = date_diff(date_create($row['invoice_date']),date_create(date('Y-m-d')));
						$amt1 = $amt2 = $amt3 = $amt4 = $amt5 = '';
						if($nodays->format("%a%") <= 30) {
							$amt1 = $balance_prnt;
							$amt1T += $amt1;
						} else if($nodays->format("%a%") > 30 && $nodays->format("%a%") <= 60) {
							$amt2 = $balance_prnt;
							$amt2T += $amt2;
						} else if($nodays->format("%a%") > 60 && $nodays->format("%a%") <= 90) {
							$amt3 = $balance_prnt;
							$amt3T += $amt3;
						} else if($nodays->format("%a%") > 90 && $nodays->format("%a%") <= 120) {
							$amt4 = $balance_prnt;
							$amt4T += $amt4;
						} else if($nodays->format("%a%") > 120) {
							$amt5 = $balance_prnt;
							$amt5T += $amt5;
						}
						
						$datareport[] = [ 'date' => date('d-m-Y', strtotime($row['invoice_date'])),
										  'ref' => $row['reference_from'],
										  'desc' => $row['description'],
										  'd1' => $balance_prnt,
										  'd2' => $amt1,
										  'd3' => $amt2,
										  'd4' => $amt3,
										  'd5' => $amt4,
										  'd6' => $amt5
										];
					}
					$datareport[] = [ 'date' => '',
									  'ref' => '',
									  'desc' => 'Total',
									  'd1' => $balance,
									  'd2' => $amt1T,
									  'd3' => $amt2T,
									  'd4' => $amt3T,
									  'd5' => $amt4T,
									  'd6' => $amt5T
									];
				}
			 }
		}		
		//echo '<pre>';print_r($reports);exit;
			
		 //echo '<pre>';print_r($datareport);exit;
		/*Excel::create($voucher_head, function($excel) use ($datareport,$voucher_head) {

			// Set the spreadsheet title, creator, and description
			$excel->setTitle($voucher_head);
			$excel->setCreator('NumakPro ERP')->setCompany(Session::get('company'));
			$excel->setDescription($voucher_head);

			// Build the spreadsheet, passing in the payments array
			$excel->sheet('sheet1', function($sheet) use ($datareport) {
				$sheet->fromArray($datareport, null, 'A1', false, false);
			});

		})->download('xlsx');*/

		return Excel::download(
			new SimpleArrayExport(
				$datareport,
				$voucher_head,
				Session::get('company')
			),
			$voucher_head . '.xlsx'
		);
		
	}
	
	
	public function dataExportnew(Request $request)
	{
		
		$data = $pdcreports = array();
		
		//hjjbkjbmnmm
		//$request->merge(['type' => 'export']);
		$request->merge(['curr_from_date' => $this->acsettings->from_date]);
		$datareport[] = [strtoupper(Session::get('company')),'','',''];
		$datareport[] = ['','','','','','',''];
		$account_id = $request->get('account_id');

		$is_default = $request->get('is_default');

		($request->get('group_id')!='')?($request->merge(['group_id' => explode(',',$request->get('group_id'))])):null;
		($request->get('type_id')!='')?($request->merge(['type_id' => explode(',',$request->get('type_id'))])):null;
		($request->get('category_id')!='')?($request->merge(['category_id' => explode(',',$request->get('category_id'))])):null;
	
		
	}
	
	private static function date_compare($a, $b) {
		$t1 = strtotime($a['invoice_date']);
		$t2 = strtotime($b['invoice_date']);
		return $t1 - $t2;
	}
		
	private static function date_compare_obj($a, $b) {
		$t1 = strtotime($a->invoice_date);
		$t2 = strtotime($b->invoice_date);
		return $t1 - $t2;
	}
	
	private static function date_compare_bill($a, $b) {
		$t1 = strtotime($a->tr_date);
		$t2 = strtotime($b->tr_date);
		return $t1 - $t2;
	}
	
	public function addressList() {
		
		$data = array();
		return view('body.accountenquiry.addresssearch')
					->withData($data);
	}
	
	public function searchAddress(Request $request)
	{
		$data = array();
		$resultrow = $this->accountmaster->searchAddressList($request->all());
		$heading = $request->get('account_type');
		return view('body.accountenquiry.addresslist')
					->withAddresslist($resultrow)
					->withHeading($heading)
					->withType($request->get('account_type'))
					->withName($request->get('account_name'))
					->withData($data);
					
		//echo '<pre>';print_r($resultrow);
	}
	
	public function addressExport(Request $request)
	{
		$data = array();
		$voucher_head = 'Address List - '.$request->get('account_type');
		$reports = $this->accountmaster->searchAddressList($request->all());
		$datareport[] = ['Account ID','Account Name','Address','TRN No','Phone','Email','Fax'];
		
		foreach($reports as $row) {
			$datareport[] = [ 'account_id' => $row->account_id,
							  'account_name' => $row->master_name,
							  'address' => $row->address,
							  'trnno' => $row->vat_no,
							  'phone' => $row->phone,
							  'email' => $row->email,
							  'fax' => $row->fax
							  
							];
		}
		//echo $voucher_head.'<pre>';print_r($datareport);exit;
		Excel::create($voucher_head.' on '.date('d-m-Y'), function($excel) use ($datareport,$voucher_head) {

			// Set the spreadsheet title, creator, and description
			$excel->setTitle($voucher_head);
			$excel->setCreator('Profit ACC 365 - ERP')->setCompany(Session::get('company'));
			$excel->setDescription($voucher_head);

			// Build the spreadsheet, passing in the payments array
			$excel->sheet('sheet1', function($sheet) use ($datareport) {
				$sheet->fromArray($datareport, null, 'A1', false, false);
			});

		})->download('xlsx');
		
	}	
	
	//public function outStandingBills($id,$mod=null,$no=null,$ref=null,$rid=null) {
	public function outStandingBills(Request $request, $id,$no=null,$mod=null,$rvid=null) {
		
		$request->merge(['account_id' => $id]);
		$request->merge(['date_from' => date('d-m-Y')]);
		$request->merge(['date_to' => '']);
		$request->merge(['type' => 'outstanding']);
		$request->merge(['is_custom' => 0]);
		
		$account = DB::table('account_master')->where('id',$id)->select('category')->first();
		$results = $this->accountmaster->getPrintViewByAccount($request->all()); //echo '<pre>';print_r($account);exit;
		$results_edit = [];
		if($mod) {
			$results_edit = $this->getDatas($mod,$rvid);
		}
		//echo '<pre>';print_r($results_edit);exit;
		//$merge_results = array_merge($results,$results_edit);  
		$merge_results = array_merge(
			is_array($results) ? $results : $results->toArray(),
			is_array($results_edit) ? $results_edit : (array) $results_edit
		);
//echo '<pre>';print_r($merge_results);exit;

		//$transactions = $this->makeTree($merge_results); echo '<pre>';print_r($transactions);exit;
		//$transactions = $this->makeSummaryOsbill($this->makeTree($merge_results)); //echo '<pre>';print_r($transactions);exit;
	$transactions = $this->makeSummaryAdv($this->makeTree($merge_results)); //echo '<pre>';print_r($transactions);exit;
		usort($transactions, array($this, "date_compare"));
		//$transactions = $this->SortByAccountOS($transactions);
		//echo '<pre>';print_r($transactions);exit;
		
		/* if($mod) {
			$transactions_edit = $this->getDatas($mod,$rvid);
		} */
		
		//foreach($transactions as $key => $transaction) {
		$datas = [];
		foreach($transactions as $trans) {
			$balance = bcsub($trans['dr_amount'], $trans['cr_amount'], 2);
			if($mod) {
				if($account->category=='CUSTOMER' || $account->category=='VATIN') {	
					if($balance < 0) {
						$balance = $balance*-1; $type = 'Cr';
					} else
						$type = 'Dr';
					
					if($trans['is_edit']=='E') {
						if($trans['dr_amount']==$trans['cr_amount']) {
							$balance = 0; $asgn_amount = ($type=='Dr')?$trans['dr_amount']:$trans['cr_amount'];
						} else 
							$asgn_amount = ($type=='Dr')?$trans['cr_amount']:$trans['dr_amount'];
					} else
						$asgn_amount = '';
					
						$datas[] = (object)[
						            'voucher_type' => $trans['voucher_type'],
						            'voucher_no' => $trans['name'],
						            'tr_date' => date('d-m-Y', strtotime($trans['invoice_date'])),
									'reference_no' => $trans['reference_from'],
									'description' => $trans['description'],
									'balance_amount'	=> $balance,
									'tr_type'	=> $type,
									'voucher_type'	=> $trans['voucher_type'],
									'voucher_type_id'	=> $trans['voucher_type_id'],
									'is_edit' 	=> $trans['is_edit'],
									'asgn_amount' 	=> $asgn_amount
									];
									
				} else if($account->category=='SUPPLIER' || $account->category=='VATOUT') {	
					if($balance < 0) {
						$balance = $balance*-1; $type = 'Cr';
					} else
						$type = 'Dr';
					
					if($trans['is_edit']=='E') {
						if($trans['dr_amount']==$trans['cr_amount']) {
							$balance = 0; $asgn_amount = ($type=='Dr')?$trans['dr_amount']:$trans['cr_amount'];
						} else 
							$asgn_amount = ($type=='Dr')?$trans['cr_amount']:$trans['dr_amount'];
					} else
						$asgn_amount = '';
					
						$datas[] = (object)[
						            'voucher_type' => $trans['voucher_type'],
						            'voucher_no' => $trans['name'],
						            'tr_date' => date('d-m-Y', strtotime($trans['invoice_date'])),
									'reference_no' => $trans['reference_from'],
									'description' => $trans['description'],
									'balance_amount'	=> $balance,
									'tr_type'	=> $type,
									'voucher_type'	=> $trans['voucher_type'],
									'voucher_type_id'	=> $trans['voucher_type_id'],
									'is_edit' 	=> $trans['is_edit'],
									'asgn_amount' 	=> $asgn_amount
									];
				}
			} else {
				if(($account->category=='CUSTOMER' && $balance !=0) || ($account->category=='VATIN' && $balance !=0)) {	
					if($balance < 0) {
						$balance = $balance*-1; $type = 'Cr';
					} else
						$type = 'Dr';
					
						$datas[] = (object)[
						            'voucher_type' => $trans['voucher_type'],
						            'voucher_no' => $trans['name'],
						            'tr_date' => date('d-m-Y', strtotime($trans['invoice_date'])),
									'reference_no' => $trans['reference_from'],
									'description' => $trans['description'],
									'balance_amount'	=> $balance,
									'tr_type'	=> $type,
									'voucher_type'	=> $trans['voucher_type'],
									'voucher_type_id'	=> $trans['voucher_type_id'],
									'is_edit' 	=> $trans['is_edit'],
									'asgn_amount' 	=> ''
									];
				} else if(($account->category=='SUPPLIER' && $balance !=0) || ($account->category=='VATOUT' && $balance !=0)) {	
				
					if($balance < 0) {
						$balance = $balance*-1; $type = 'Cr';
					} else
						$type = 'Dr';
					
						$datas[] = (object)[
						            'voucher_type' => $trans['voucher_type'],
						            'voucher_no' => $trans['name'],
						            'tr_date' => date('d-m-Y', strtotime($trans['invoice_date'])),
									'reference_no' => $trans['reference_from'],
									'description' => $trans['description'],
									'balance_amount'	=> $balance,
									'tr_type'	=> $type,
									'voucher_type'	=> $trans['voucher_type'],
									'voucher_type_id'	=> $trans['voucher_type_id'],
									'is_edit' 	=> $trans['is_edit'],
									'asgn_amount' 	=> ''
									];
				} 
			}
		}		
		//}
		//echo '<pre>';print_r($datas);exit;
		//$data = array_merge($datas,$transactions_edit);
		usort($datas, array($this, "date_compare_bill"));
		
		//echo '<pre>';print_r($data);exit;
		
		return view('body.accountenquiry.osbills')
					->withOsbills($datas)->withType($account->category)->withNo($no);
		
	}
	
	
	public function outStandingBillsAdv($id,$mod=null,Request $request) {
		
		$request->merge(['account_id' => $id]);
		$request->merge(['date_from' => date('d-m-Y')]);
		$request->merge(['date_to' => '']);
		$request->merge(['type' => 'outstanding']);
		$request->merge(['is_custom' => 0]);
		
		$account = DB::table('account_master')->where('id',$id)->select('category')->first();
		$results = $this->accountmaster->getPrintViewByAccount($request->all()); //echo '<pre>';print_r($results);exit;
		$results_edit = [];
		if($mod) {
			$results_edit = $this->getDatas($mod,$rvid);
		}
		//echo '<pre>';print_r($results);exit;
		$merge_results = array_merge($results,$results_edit);  //echo '<pre>';print_r($this->makeTree($merge_results));exit; 
		//$transactions = $this->makeTree($merge_results); echo '<pre>';print_r($transactions);exit;
		$transactions = $this->makeSummaryAdv($this->makeTree($merge_results)); //echo '<pre>';print_r($transactions);exit;
		usort($transactions, array($this, "date_compare"));  //$transactions = $this->SortByAccountOS($transactions);
		//echo '<pre>';print_r($transactions);exit;
		
		/* if($mod) {
			$transactions_edit = $this->getDatas($mod,$rvid);
		} */
		
		//foreach($transactions as $key => $transaction) {
		$datas = [];
		foreach($transactions as $trans) {
			$balance = bcsub($trans['dr_amount'], $trans['cr_amount'], 2);
			if($mod) {
				if($account->category=='CUSTOMER' || $account->category=='VATIN') {	
					if($balance < 0) {
						$balance = $balance*-1; $type = 'Cr';
					} else
						$type = 'Dr';
					
					if($trans['is_edit']=='E') {
						if($trans['dr_amount']==$trans['cr_amount']) {
							$balance = 0; $asgn_amount = ($type=='Dr')?$trans['dr_amount']:$trans['cr_amount'];
						} else 
							$asgn_amount = ($type=='Dr')?$trans['cr_amount']:$trans['dr_amount'];
					} else
						$asgn_amount = '';
					
						$datas[] = (object)[
						            'voucher_type' => $trans['voucher_type'],
						            'voucher_no' => $trans['name'],
						            'tr_date' => date('d-m-Y', strtotime($trans['invoice_date'])),
									'reference_no' => $trans['reference_from'],
									'description' => $trans['description'],
									'balance_amount'	=> $balance,
									'tr_type'	=> $type,
									'voucher_type'	=> $trans['voucher_type'],
									'voucher_type_id'	=> $trans['voucher_type_id'],
									'is_edit' 	=> $trans['is_edit'],
									'asgn_amount' 	=> $asgn_amount,
									'category' => $account->category
									];
									
				} else if($account->category=='SUPPLIER' || $account->category=='VATOUT') {	
					if($balance < 0) {
						$balance = $balance*-1; $type = 'Cr';
					} else
						$type = 'Dr';
					
					if($trans['is_edit']=='E') {
						if($trans['dr_amount']==$trans['cr_amount']) {
							$balance = 0; $asgn_amount = ($type=='Dr')?$trans['dr_amount']:$trans['cr_amount'];
						} else 
							$asgn_amount = ($type=='Dr')?$trans['cr_amount']:$trans['dr_amount'];
					} else
						$asgn_amount = '';
					
						$datas[] = (object)[
						            'voucher_type' => $trans['voucher_type'],
						            'voucher_no' => $trans['name'],
						            'tr_date' => date('d-m-Y', strtotime($trans['invoice_date'])),
									'reference_no' => $trans['reference_from'],
									'description' => $trans['description'],
									'balance_amount'	=> $balance,
									'tr_type'	=> $type,
									'voucher_type'	=> $trans['voucher_type'],
									'voucher_type_id'	=> $trans['voucher_type_id'],
									'is_edit' 	=> $trans['is_edit'],
									'asgn_amount' 	=> $asgn_amount,
									'category' => $account->category
									];
				}
			} else {
				if(($account->category=='CUSTOMER' && $balance !=0) || ($account->category=='VATIN' && $balance !=0)) {	
					if($balance < 0) {
						$balance = $balance*-1; $type = 'Cr';
					} else
						$type = 'Dr';
					
						$datas[] = (object)[
						            'voucher_type' => $trans['voucher_type'],
						            'voucher_no' => $trans['name'],
						            'tr_date' => date('d-m-Y', strtotime($trans['invoice_date'])),
									'reference_no' => $trans['reference_from'],
									'description' => $trans['description'],
									'balance_amount'	=> $balance,
									'tr_type'	=> $type,
									'voucher_type'	=> $trans['voucher_type'],
									'voucher_type_id'	=> $trans['voucher_type_id'],
									'is_edit' 	=> $trans['is_edit'],
									'asgn_amount' 	=> '',
									'category' => $account->category
									];
				} else if(($account->category=='SUPPLIER' && $balance !=0) || ($account->category=='VATOUT' && $balance !=0)) {	
				
					if($balance < 0) {
						$balance = $balance*-1; $type = 'Cr';
					} else
						$type = 'Dr';
					
						$datas[] = (object)[
						            'voucher_type' => $trans['voucher_type'],
						            'voucher_no' => $trans['name'],
						            'tr_date' => date('d-m-Y', strtotime($trans['invoice_date'])),
									'reference_no' => $trans['reference_from'],
									'description' => $trans['description'],
									'balance_amount'	=> $balance,
									'tr_type'	=> $type,
									'voucher_type'	=> $trans['voucher_type'],
									'voucher_type_id'	=> $trans['voucher_type_id'],
									'is_edit' 	=> $trans['is_edit'],
									'asgn_amount' 	=> '',
									'category' => $account->category
									];
				} 
			}
		}		
		//}
		//echo '<pre>';print_r($datas);exit;
		//$data = array_merge($datas,$transactions_edit);
		usort($datas, array($this, "date_compare_bill"));
		$opndata = []; $billdata = [];
		foreach($datas as $key => $dat) {
		    
		    /*if(strtolower($dat->reference_no)=='adv.' || strtolower($dat->reference_no)=='adv') {
		        $opndata[] = $dat;
		    } else {
		        $billdata[] = $dat; 
		    }*/
		    
		    if($dat->category=='CUSTOMER' && $dat->tr_type=='Cr')
		        $opndata[] = $dat;
		    else if($dat->category=='CUSTOMER' && $dat->tr_type=='Dr') 
		        $billdata[] = $dat;
		    else if($dat->category=='SUPPLIER' && $dat->tr_type=='Dr')
		        $opndata[] = $dat;
		    else if($dat->category=='SUPPLIER' && $dat->tr_type=='Cr')  
		        $billdata[] = $dat;
		}
		
		//echo '<pre>';print_r($opndata);
		
		//echo '<pre>';print_r($billdata);exit;
		
		return view('body.accountenquiry.osbills-adv')
					->withObbills($opndata)->withInvoices($billdata)->withType($account->category);
		
	}
	
	
	private function getDatas($mod,$id) { 
		
		$result = [];
		if($mod=='RV') {
			$RVE1 = DB::table('receipt_voucher') 
						->join('receipt_voucher_entry AS RVE','RVE.receipt_voucher_id','=','receipt_voucher.id')
						->join('receipt_voucher_tr AS RVT','RVT.receipt_voucher_entry_id','=','RVE.id')
						->join('account_transaction AS AT', function($join) {
							$join->on('AT.voucher_type_id','=','RVE.id');
							$join->where('AT.voucher_type','=','RV');
							$join->where('AT.status','=',1);
							$join->where('AT.deleted_at','=','0000-00-00 00:00:00');
						})
						->join('sales_invoice AS SI', function($join) {
							$join->on('SI.id','=','RVT.sales_invoice_id');
							$join->where('RVT.bill_type','=','SI');
							$join->where('RVT.status','=',1);
							$join->where('RVT.deleted_at','=','0000-00-00 00:00:00');
						})
						->where('receipt_voucher.id',$id)
						->select('SI.voucher_date AS invoice_date','SI.voucher_no AS reference','RVE.description','RVE.amount AS amount','AT.reference_from','AT.id',
								'RVE.entry_type AS transaction_type','RVT.bill_type AS voucher_type','RVT.receipt_voucher_entry_id AS voucher_type_id',DB::raw('"E" AS is_edit'));
						
			$RVE2 = DB::table('receipt_voucher')
						->join('receipt_voucher_entry AS RVE','RVE.receipt_voucher_id','=','receipt_voucher.id')
						->join('receipt_voucher_tr AS RVT','RVT.receipt_voucher_entry_id','=','RVE.id')
						->join('account_transaction AS AT', function($join) {
							$join->on('AT.voucher_type_id','=','RVE.id');
							$join->where('AT.voucher_type','=','RV');
							$join->where('AT.status','=',1);
							$join->where('AT.deleted_at','=','0000-00-00 00:00:00');
						})
						->join('sales_split AS SS', function($join) {
							$join->on('SS.id','=','RVT.sales_invoice_id');
							$join->where('RVT.bill_type','=','SS');
							$join->where('RVT.status','=',1);
							$join->where('RVT.deleted_at','=','0000-00-00 00:00:00');
						})
						->where('receipt_voucher.id',$id)
						->select('SS.voucher_date AS invoice_date','SS.voucher_no AS reference','RVE.description','RVE.amount AS amount','AT.reference_from','AT.id',
								'RVE.entry_type AS transaction_type','RVT.bill_type AS voucher_type','RVT.receipt_voucher_entry_id AS voucher_type_id',DB::raw('"E" AS is_edit'));
								
			$RVE3 = DB::table('receipt_voucher')
						->join('receipt_voucher_entry AS RVE','RVE.receipt_voucher_id','=','receipt_voucher.id')
						->join('receipt_voucher_tr AS RVT','RVT.receipt_voucher_entry_id','=','RVE.id')
						->join('account_transaction AS AT', function($join) {
							$join->on('AT.voucher_type_id','=','RVE.id');
							$join->where('AT.voucher_type','=','RV');
							$join->where('AT.status','=',1);
							$join->where('AT.deleted_at','=','0000-00-00 00:00:00');
						})
						->join('sales_return AS SR', function($join) {
							$join->on('SR.id','=','RVT.sales_invoice_id');
							$join->where('RVT.bill_type','=','SR');
							$join->where('RVT.status','=',1);
							$join->where('RVT.deleted_at','=','0000-00-00 00:00:00');
						})
						->where('receipt_voucher.id',$id)
						->select('SR.voucher_date AS invoice_date','SR.voucher_no AS reference','RVE.description','RVE.amount AS amount','AT.reference_from','AT.id',
								'RVE.entry_type AS transaction_type','RVT.bill_type AS voucher_type','RVT.receipt_voucher_entry_id AS voucher_type_id',DB::raw('"E" AS is_edit'));
								
			$result = $RVE1->union($RVE2)->union($RVE3)->get();
						//echo '<pre>';print_r($result);exit;
						
		} else if($mod=='PV') {
			$PVE1 = DB::table('payment_voucher') 
						->join('payment_voucher_entry AS PVE','PVE.payment_voucher_id','=','payment_voucher.id')
						->join('payment_voucher_tr AS PVT','PVT.payment_voucher_entry_id','=','PVE.id')
						->join('account_transaction AS AT', function($join) {
							$join->on('AT.voucher_type_id','=','PVE.id');
							$join->where('AT.voucher_type','=','PV');
							$join->where('AT.status','=',1);
							$join->where('AT.deleted_at','=','0000-00-00 00:00:00');
						})
						->join('purchase_invoice AS PI', function($join) {
							$join->on('PI.id','=','PVT.purchase_invoice_id');
							$join->where('PVT.bill_type','=','PI');
							$join->where('PVT.status','=',1);
							$join->where('PVT.deleted_at','=','0000-00-00 00:00:00');
						})
						->where('payment_voucher.id',$id)
						->select('PI.voucher_date AS invoice_date','PI.voucher_no AS reference','PVE.description','PVE.amount AS amount','AT.reference_from','AT.id',
								'PVE.entry_type AS transaction_type','PVT.bill_type AS voucher_type','PVT.payment_voucher_entry_id AS voucher_type_id',DB::raw('"E" AS is_edit'));
						
			$PVE2 = DB::table('payment_voucher')
						->join('payment_voucher_entry AS PVE','PVE.payment_voucher_id','=','payment_voucher.id')
						->join('payment_voucher_tr AS PVT','PVT.payment_voucher_entry_id','=','PVE.id')
						->join('account_transaction AS AT', function($join) {
							$join->on('AT.voucher_type_id','=','PVE.id');
							$join->where('AT.voucher_type','=','PV');
							$join->where('AT.status','=',1);
							$join->where('AT.deleted_at','=','0000-00-00 00:00:00');
						})
						->join('purchase_split AS PS', function($join) {
							$join->on('PS.id','=','PVT.purchase_invoice_id');
							$join->where('PVT.bill_type','=','PS');
							$join->where('PVT.status','=',1);
							$join->where('PVT.deleted_at','=','0000-00-00 00:00:00');
						})
						->where('payment_voucher.id',$id)
						->select('PS.voucher_date AS invoice_date','PS.voucher_no AS reference','PVE.description','PVE.amount AS amount','AT.reference_from','AT.id',
								'PVE.entry_type AS transaction_type','PVT.bill_type AS voucher_type','PVT.payment_voucher_entry_id AS voucher_type_id',DB::raw('"E" AS is_edit'));
								
			$PVE3 = DB::table('payment_voucher')
						->join('payment_voucher_entry AS PVE','PVE.payment_voucher_id','=','payment_voucher.id')
						->join('payment_voucher_tr AS PVT','PVT.payment_voucher_entry_id','=','PVE.id')
						->join('account_transaction AS AT', function($join) {
							$join->on('AT.voucher_type_id','=','PVE.id');
							$join->where('AT.voucher_type','=','PV');
							$join->where('AT.status','=',1);
							$join->where('AT.deleted_at','=','0000-00-00 00:00:00');
						})
						->join('purchase_return AS PR', function($join) {
							$join->on('PR.id','=','PVT.purchase_invoice_id');
							$join->where('PVT.bill_type','=','PR');
							$join->where('PVT.status','=',1);
							$join->where('PVT.deleted_at','=','0000-00-00 00:00:00');
						})
						->where('payment_voucher.id',$id)
						->select('PR.voucher_date AS invoice_date','PR.voucher_no AS reference','PVE.description','PVE.amount AS amount','AT.reference_from','AT.id',
								'PVE.entry_type AS transaction_type','PVT.bill_type AS voucher_type','PVT.payment_voucher_entry_id AS voucher_type_id',DB::raw('"E" AS is_edit'));
								
			$result = $PVE1->union($PVE2)->union($PVE3)->get();
						//echo '<pre>';print_r($result);exit;
						
		} else if($mod=='JV') { 
			
			$JE1 = DB::table('journal') 
						->join('journal_entry AS JE','JE.journal_id','=','journal.id')
						->join('journal_voucher_tr AS JVT','JVT.journal_entry_id','=','JE.id')
						->join('account_transaction AS AT', function($join) {
							$join->on('AT.voucher_type_id','=','JE.id');
							$join->where('AT.voucher_type','=','JV');
							$join->where('AT.status','=',1);
							$join->where('AT.deleted_at','=','0000-00-00 00:00:00');
						})
						->join('sales_invoice AS SI', function($join) {
							$join->on('SI.id','=','JVT.invoice_id');
							$join->where('JVT.bill_type','=','SI');
							$join->where('JVT.status','=',1);
							$join->where('JVT.deleted_at','=','0000-00-00 00:00:00');
						})
						->where('journal.id',$id)
						->select('SI.voucher_date AS invoice_date','SI.voucher_no AS reference','JE.description','JE.amount AS amount','AT.reference_from','AT.id',
								'JE.entry_type AS transaction_type','JVT.bill_type AS voucher_type','JVT.journal_entry_id AS voucher_type_id',DB::raw('"E" AS is_edit'));
						
			$JE2 = DB::table('journal')
						->join('journal_entry AS JE','JE.journal_id','=','journal.id')
						->join('journal_voucher_tr AS JVT','JVT.journal_entry_id','=','JE.id')
						->join('account_transaction AS AT', function($join) {
							$join->on('AT.voucher_type_id','=','JE.id');
							$join->where('AT.voucher_type','=','JV');
							$join->where('AT.status','=',1);
							$join->where('AT.deleted_at','=','0000-00-00 00:00:00');
						})
						->join('sales_split AS SS', function($join) {
							$join->on('SS.id','=','JVT.invoice_id');
							$join->where('JVT.bill_type','=','SS');
							$join->where('JVT.status','=',1);
							$join->where('JVT.deleted_at','=','0000-00-00 00:00:00');
						})
						->where('journal.id',$id)
						->select('SS.voucher_date AS invoice_date','SS.voucher_no AS reference','JE.description','JE.amount AS amount','AT.reference_from','AT.id',
								'JE.entry_type AS transaction_type','JVT.bill_type AS voucher_type','JVT.journal_entry_id AS voucher_type_id',DB::raw('"E" AS is_edit'));
								
			$JE3 = DB::table('journal')
						->join('journal_entry AS JE','JE.journal_id','=','journal.id')
						->join('journal_voucher_tr AS JVT','JVT.journal_entry_id','=','JE.id')
						->join('account_transaction AS AT', function($join) {
							$join->on('AT.voucher_type_id','=','JE.id');
							$join->where('AT.voucher_type','=','JV');
							$join->where('AT.status','=',1);
							$join->where('AT.deleted_at','=','0000-00-00 00:00:00');
						})
						->join('sales_return AS SR', function($join) {
							$join->on('SR.id','=','JVT.invoice_id');
							$join->where('JVT.bill_type','=','SR');
							$join->where('JVT.status','=',1);
							$join->where('JVT.deleted_at','=','0000-00-00 00:00:00');
						})
						->where('journal.id',$id)
						->select('SR.voucher_date AS invoice_date','SR.voucher_no AS reference','JE.description','JE.amount AS amount','AT.reference_from','AT.id',
								'JE.entry_type AS transaction_type','JVT.bill_type AS voucher_type','JVT.journal_entry_id AS voucher_type_id',DB::raw('"E" AS is_edit'));
								
			$result = $JE1->union($JE2)->union($JE3)->get();
			
		}
		
		return $result;
	}
	
	
	public function Reconciliation() {
		
		$results = DB::table('account_master')
						->join('account_group AS AG','AG.id','=','account_master.account_group_id')
						->where('account_master.status',1)->where('account_master.deleted_at','0000-00-00 00:00:00')
						->where('AG.status',1)->where('AG.deleted_at','0000-00-00 00:00:00')
						->whereIn('AG.category',['CASH','BANK'])
						->select('account_master.*')->get();
		//echo '<pre>';print_r($results);exit;
		
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
		//print_r($this->acsettings);exit;
		return view('body.accountenquiry.reconciliation')
					->withIsdept($is_dept)
					->withSettings($this->acsettings)
					->withResults($results);
					
	}
	
	function searchAccountAR(Request $request)
	{
		//echo '<pre>';print_r($request->all());exit;
		$data = $pdctransactions = array(); $opn_balnce = null;
		$frmdate = $request->get('date_from');
		$todate = $request->get('date_to');
		$request->merge(['curr_from_date' => $this->acsettings->from_date]); 
		$job_id = ($request->get('job_id')!='')?$request->get('job_id'):null;
		
		//JUN3....
		$infc = $currency = '';
		if($request->get('inFC')==1) {
			$currency = DB::table('currency')->where('id',$request->get('currency_id'))->first();
			if(!$currency) 
				$currency = DB::table('currency')->where('status', 1)->where('is_default', 0)->first();
			
			$infc = $request->get('inFC');
		}
			
		$account_id = $request->get('account_id');
		$is_default = $request->get('is_default');
		$headarr = [];
		
			$resultrow = [];
			$resultPdc = $uncleared = null;
			//echo $account_id;
			$actrnsdata = $this->sortTrnArr( DB::table('reconciliation')->where('account_id',$account_id)->get() );
			if($request->get('type')=='statement') {
				$voucher_head = 'Account Reconciliation';
				if($request->get('is_con')==1) {
					//$transactions = $this->SortByAccount($this->makeConsolidated($this->accountmaster->getPrintViewByAccount($request->all())) );
					$transactions1 = $this->makeConsolidated($this->accountmaster->getPrintViewByAccount($request->all()));
					usort($transactions1, array($this, "date_compare_obj"));
					$transactions = $this->SortByAccount($transactions1);
				} else 
					$transactions = $this->SortByAccount($this->accountmaster->getPrintViewByAccount($request->all()));
				
				//echo '<pre>';print_r($transactions);exit;
				$pdctransactions = $this->accountmaster->getPDCPrintViewByAccount($request->all());
				//echo '<pre>';print_r($pdctransactions);exit;
				
				if((date('d-m-Y',strtotime($this->acsettings->from_date))!=$request->get('date_from'))) { 
					foreach($transactions as $key => $transaction ) {
						$resultrow[$key] = $this->accountmaster->findDetails($transaction[0]->account_master_id);
						if($request->get('exclude_ob')==1) {
							$opn_balnce[$key] = null;
						} else {
							$enddate = date('Y-m-d', strtotime('-1 day', strtotime($request->get('date_from'))));
						
							$obtrn = DB::table('account_transaction')->where('voucher_type','OB')->where('account_master_id',$transaction[0]->account_master_id)->where('status',1)
															->where('deleted_at','0000-00-00 00:00:00')->select('invoice_date')->first();
															
							$request->merge(['date_from' =>  $obtrn->invoice_date]);
							$request->merge(['date_to' => $enddate]);
							
							if($transaction[0]->voucher_type!='OB') {
								$obArr = $this->getOpeningBalanceAR($this->accountmaster->getPrintViewByAccount($request->all()), $actrnsdata, $frmdate, $todate);
								$opn_balnce[$key] = $obArr['ar_summary'];
								$uncleared[$key] = $obArr['ar_uncleared'];
							}
						}
					}
				} else { 
					foreach($transactions as $key => $transaction ) {
						$resultrow[$key] = $this->accountmaster->findDetails($transaction[0]->account_master_id);
						
						if(!$transaction || $transaction[0]->voucher_type!='OB') {
							
							$obtrn = DB::table('account_transaction')->where('voucher_type','OB')->where('account_master_id',$transaction[0]->account_master_id)->where('status',1)
														->where('deleted_at','0000-00-00 00:00:00')->select('invoice_date')->first();
							
							$enddate = date('Y-m-d', strtotime('-1 day', strtotime($request->get('date_from'))));
							$request->merge(['date_from' => $obtrn->invoice_date]);
							$request->merge(['date_to' => $enddate]);
							$obArr = $this->getOpeningBalanceAR($this->accountmaster->getPrintViewByAccount($request->all()), $actrnsdata, $frmdate, $todate);
							$opn_balnce[$key] = $obArr['ar_summary'];
							$uncleared[$key] = $obArr['ar_uncleared'];
						}
					}
				}
				
				if(!empty($request->get('category_id'))) {
					$transactions = $this->SortByCategory($transactions);
					foreach($transactions as $key => $val) {
						$headarr[$key] = DB::table('account_category')->where('id',$key)->select('name AS heading')->first();
					}
				}
				
				//SORT BY GROUP & GETING GROUP HEADING...
				if(!empty($request->get('group_id'))) {
					$transactions = $this->SortByGroup($transactions);
					foreach($transactions as $key => $val) {
						$headarr[$key] = DB::table('account_group')->where('id',$key)->select('name AS heading')->first();
					}
				}
				
				//SORT BY TYPE & GETING TYPE HEADING...
				if(!empty($request->get('type_id'))) {
					$transactions = $this->SortByType($transactions);
					foreach($request->get('type_id') as $key) {
						$headarr[$key] = (object)array('heading'=>$key);
					}
				}
				
				//DEFAULT SORTING...
				if($request->get('is_custom')==0) {
					$transactions = $this->SortByCategory($transactions);
					foreach($transactions as $key => $val) {
						$headarr[$key] = DB::table('account_category')->where('id',$key)->select('name AS heading')->first();
					}
				}
			
			}
			
						
			//echo '<pre>';print_r($out_standing);exit;
			//echo '<pre>';print_r($resultrow);exit;
			//echo '<pre>';print_r($transactions);exit;
			//echo '<pre>';print_r($opn_balnce);exit;
			//echo '<pre>';print_r(Session::get('company'));exit;
			$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];
			return view('body.accountenquiry.newprintar')
						->withTransactions($transactions)
						->withResultrow($resultrow)
						->withVoucherhead($voucher_head)
						->withType($request->get('type'))
						->withTitles($titles)
						->withFromdate($frmdate)
						->withTodate($todate)
						->withUrl('account_enquiry')
						->withSettings($this->acsettings)
						->withId($account_id)
						->withIspdc($request->get('is_pdc'))
						->withIscon($request->get('is_con'))
						->withPdcs($pdctransactions)
						->withOpenbalance($opn_balnce)
						->withFc($infc)
						->withCurrency($currency)
						->withJobid($job_id)
						->withHeadarr($headarr)
						->withIscustom($request->get('is_custom'))
						->withTypeid(!empty($request->get('type_id'))?implode(',',$request->get('type_id')):'')
						->withCatid(!empty($request->get('category_id'))?implode(',',$request->get('category_id')):'')
						->withGroupid(!empty($request->get('group_id'))?implode(',',$request->get('group_id')):'')
						->withPdclist($resultPdc)
						->withActrndata($actrnsdata)
						->withUncleared($uncleared)
						->withData($data);
	}
	
	protected function sortTrnArr($result)
	{
		$childs = array();
		foreach($result as $item)
			$childs[$item->trid] = $item->bank_date;
		return $childs;
	}
	
	private function getOpeningBalanceAR($results, $actrnsdata, $fromdate, $todate) {
		
		$dramount = $cramount = 0; $arr = []; //echo $fromdate.', '.$todate;exit;
		foreach($results as $row) {
			
			$trdate = $fdate = $tdate = '';
			if(isset($actrnsdata[$row->id])) {
				$trdate = strtotime($actrnsdata[$row->id]);
				$fdate = strtotime($fromdate);
				$tdate = strtotime($todate);
			}
			
			if($row->transaction_type=='Dr') {
				
				if($row->voucher_type=='OB') {
					$dramount = $row->amount;
					
				} else if(isset($actrnsdata[$row->id])) { 
					if($trdate >= $fdate && $trdate <= $tdate) {
						
						$arr[] = (object)['id' => $row->id,
							  'voucher_type' => $row->voucher_type,
							  'transaction_type' => $row->transaction_type,
							  'reference' => $row->reference,
							  'invoice_date' => date('d-m-Y', strtotime($row->invoice_date)),
							  'description' => $row->description,
							  'reference_from' => $row->reference_from,
							  'amount' => $row->amount
							  ];
							  
						$dramount += 0;
					} else
						$dramount += $row->amount;
				} else {
					$arr[] = (object)['id' => $row->id,
							  'voucher_type' => $row->voucher_type,
							  'transaction_type' => $row->transaction_type,
							  'reference' => $row->reference,
							  'invoice_date' => date('d-m-Y', strtotime($row->invoice_date)),
							  'description' => $row->description,
							  'reference_from' => $row->reference_from,
							  'amount' => $row->amount
							  ];
				}
												
				//$dramount += $row->amount;
			} else {
				if($row->voucher_type=='OB') {
					$cramount = $row->amount;
					
				} else if(isset($actrnsdata[$row->id])) { 
					if($trdate >= $fdate && $trdate <= $tdate) {
						
						$arr[] = (object)['id' => $row->id,
							  'voucher_type' => $row->voucher_type,
							  'transaction_type' => $row->transaction_type,
							  'reference' => $row->reference,
							  'invoice_date' => date('d-m-Y', strtotime($row->invoice_date)),
							  'description' => $row->description,
							  'reference_from' => $row->reference_from,
							  'amount' => $row->amount
							  ];
							  
						$cramount += 0;
					} else
						$cramount += $row->amount;
				} else {
					$arr[] = (object)['id' => $row->id,
							  'voucher_type' => $row->voucher_type,
							  'transaction_type' => $row->transaction_type,
							  'reference' => $row->reference,
							  'invoice_date' => date('d-m-Y', strtotime($row->invoice_date)),
							  'description' => $row->description,
							  'reference_from' => $row->reference_from,
							  'amount' => $row->amount
							  ];
				}
				
				//$cramount += $row->amount;
			}
		}
		
		$balance = $dramount - $cramount;
		$type = ($balance > 0)?'Dr':'Cr';
		
		$arrSummarry = ['type' => 'OB', 
						  'amount' => $balance,
						  'transaction_type' => $type
						 ];

		return ['ar_summary' => $arrSummarry, 'ar_uncleared' => $arr];
		
	}
	
	public function saveReconciliation(Request $request) {
		
		//echo '<pre>';print_r($request->all());exit;
		$hd_bdate = $request->get('bank_date');
		foreach($request->get('bank_date') as $key => $val) {
			
			if($val != '' && $hd_bdate[$key] !='') {
				$date = $val;
			} else if($val == '' && $hd_bdate[$key] !='') {
				$date = $hd_bdate[$key];
			} else if($val != '' && $hd_bdate[$key] =='') {
				$date = $val;
			} else if($val == '' && $hd_bdate[$key] =='') {
				$date = null;
			}
			
			if($date!='') {
				$krow = DB::table('reconciliation')->where('trid', $key)->first();
				if($krow) {
					DB::table('reconciliation')->where('trid', $key)->update(['bank_date' => date('Y-m-d', strtotime($date))]);
				} else {
					DB::table('reconciliation')->insert(['trid' => $key, 'account_id' => $request->get('account_id'), 'bank_date' => date('Y-m-d', strtotime($date))]);
				}
			} else {
				DB::table('reconciliation')->where('trid', $key)->update(['bank_date' => null ]);
			}
		}
		
		$data = $pdctransactions = array(); $opn_balnce = null;
		$frmdate = $request->get('date_from');
		$todate = $request->get('date_to');
		$request->merge(['curr_from_date' => $this->acsettings->from_date]); 
		$job_id = ($request->get('job_id')!='')?$request->get('job_id'):null;
		
		//JUN3....
		$infc = $currency = '';
		if($request->get('inFC')==1) {
			$currency = DB::table('currency')->where('id',$request->get('currency_id'))->first();
			if(!$currency) 
				$currency = DB::table('currency')->where('status', 1)->where('is_default', 0)->first();
			
			$infc = $request->get('inFC');
		}
			
		$account_id = $request->get('account_id');
		$is_default = $request->get('is_default');
		$headarr = [];
		
			$resultrow = [];
			$resultPdc = $uncleared = null;
			//echo $account_id;
			$actrnsdata = $this->sortTrnArr( DB::table('reconciliation')->where('account_id',$account_id)->get() );
			if($request->get('type')=='statement') {
				$voucher_head = 'Account Reconciliation';
				if($request->get('is_con')==1) {
					//$transactions = $this->SortByAccount($this->makeConsolidated($this->accountmaster->getPrintViewByAccount($request->all())) );
					$transactions1 = $this->makeConsolidated($this->accountmaster->getPrintViewByAccount($request->all()));
					usort($transactions1, array($this, "date_compare_obj"));
					$transactions = $this->SortByAccount($transactions1);
				} else 
					$transactions = $this->SortByAccount($this->accountmaster->getPrintViewByAccount($request->all()));
				
				//echo '<pre>';print_r($transactions);exit;
				$pdctransactions = $this->accountmaster->getPDCPrintViewByAccount($request->all());
				//echo '<pre>';print_r($pdctransactions);exit;
				
				if((date('d-m-Y',strtotime($this->acsettings->from_date))!=$request->get('date_from'))) { 
					foreach($transactions as $key => $transaction ) {
						$resultrow[$key] = $this->accountmaster->findDetails($transaction[0]->account_master_id);
						if($request->get('exclude_ob')==1) {
							$opn_balnce[$key] = null;
						} else {
							$enddate = date('Y-m-d', strtotime('-1 day', strtotime($request->get('date_from'))));
						
							$obtrn = DB::table('account_transaction')->where('voucher_type','OB')->where('account_master_id',$transaction[0]->account_master_id)->where('status',1)
															->where('deleted_at','0000-00-00 00:00:00')->select('invoice_date')->first();
															
							$request->merge(['date_from' =>  $obtrn->invoice_date]);
							$request->merge(['date_to' => $enddate]);
							
							if($transaction[0]->voucher_type!='OB') {
								$obArr = $this->getOpeningBalanceAR($this->accountmaster->getPrintViewByAccount($request->all()), $actrnsdata, $frmdate, $todate);
								$opn_balnce[$key] = $obArr['ar_summary'];
								$uncleared[$key] = $obArr['ar_uncleared'];
							}
						}
					}
				} else { 
					foreach($transactions as $key => $transaction ) {
						$resultrow[$key] = $this->accountmaster->findDetails($transaction[0]->account_master_id);
						
						if(!$transaction || $transaction[0]->voucher_type!='OB') {
							
							$obtrn = DB::table('account_transaction')->where('voucher_type','OB')->where('account_master_id',$transaction[0]->account_master_id)->where('status',1)
														->where('deleted_at','0000-00-00 00:00:00')->select('invoice_date')->first();
							
							$enddate = date('Y-m-d', strtotime('-1 day', strtotime($request->get('date_from'))));
							$request->merge(['date_from' => $obtrn->invoice_date]);
							$request->merge(['date_to' => $enddate]);
							$obArr = $this->getOpeningBalanceAR($this->accountmaster->getPrintViewByAccount($request->all()), $actrnsdata, $frmdate, $todate);
							$opn_balnce[$key] = $obArr['ar_summary'];
							$uncleared[$key] = $obArr['ar_uncleared'];
						}
					}
				}
				
				if(!empty($request->get('category_id'))) {
					$transactions = $this->SortByCategory($transactions);
					foreach($transactions as $key => $val) {
						$headarr[$key] = DB::table('account_category')->where('id',$key)->select('name AS heading')->first();
					}
				}
				
				//SORT BY GROUP & GETING GROUP HEADING...
				if(!empty($request->get('group_id'))) {
					$transactions = $this->SortByGroup($transactions);
					foreach($transactions as $key => $val) {
						$headarr[$key] = DB::table('account_group')->where('id',$key)->select('name AS heading')->first();
					}
				}
				
				//SORT BY TYPE & GETING TYPE HEADING...
				if(!empty($request->get('type_id'))) {
					$transactions = $this->SortByType($transactions);
					foreach($request->get('type_id') as $key) {
						$headarr[$key] = (object)array('heading'=>$key);
					}
				}
				
				//DEFAULT SORTING...
				if($request->get('is_custom')==0) {
					$transactions = $this->SortByCategory($transactions);
					foreach($transactions as $key => $val) {
						$headarr[$key] = DB::table('account_category')->where('id',$key)->select('name AS heading')->first();
					}
				}
			
			}
			
			

			$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];
			return view('body.accountenquiry.newprintar2')
						->withTransactions($transactions)
						->withResultrow($resultrow)
						->withVoucherhead($voucher_head)
						->withType($request->get('type'))
						->withTitles($titles)
						->withFromdate($frmdate)
						->withTodate($todate)
						->withUrl('account_enquiry')
						->withSettings($this->acsettings)
						->withId($account_id)
						->withIspdc($request->get('is_pdc'))
						->withIscon($request->get('is_con'))
						->withPdcs($pdctransactions)
						->withOpenbalance($opn_balnce)
						->withFc($infc)
						->withCurrency($currency)
						->withJobid($job_id)
						->withHeadarr($headarr)
						->withIscustom($request->get('is_custom'))
						->withTypeid(!empty($request->get('type_id'))?implode(',',$request->get('type_id')):'')
						->withCatid(!empty($request->get('category_id'))?implode(',',$request->get('category_id')):'')
						->withGroupid(!empty($request->get('group_id'))?implode(',',$request->get('group_id')):'')
						->withPdclist($resultPdc)
						->withActrndata($actrnsdata)
						->withUncleared($uncleared)
						->withData($data);
	}
	

public function dataSend(Request $request)
	{
		$data = $pdcreports = array();
		//$request->merge(['type' => 'export']);
		$request->merge(['curr_from_date' => $this->acsettings->from_date]);
		$datareport[] = [strtoupper(Session::get('company')),'','',''];
		$datareport[] = ['','','','','','',''];
		$account_id = $request->get('account_id');
		$is_default = $request->get('is_default');

		($request->get('group_id')!='')?($request->merge(['group_id' => explode(',',$request->get('group_id'))])):null;
		($request->get('type_id')!='')?($request->merge(['type_id' => explode(',',$request->get('type_id'))])):null;
		($request->get('category_id')!='')?($request->merge(['category_id' => explode(',',$request->get('category_id'))])):null;
		if($is_default==1) {
			
			if($request->get('type')=='ageing') {
				$accounts = DB::table('account_master')->where('category', $account_id)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id')->get();
				
				foreach($accounts as $row) {
					$results = $this->accountmaster->getAgeingSummary($row->id, $request->all());
					$transactions[] = $this->makeAgeingSummary($this->makeTree($results));
					
				}
				//echo '<pre>';print_r($transactions);exit;
				$voucher_head = 'Statement of Account - Ageing Summary';
				$datareport[] = ['','','',strtoupper($voucher_head),'',''];	
				$datareport[] = ['','','','','','',''];
				$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];
				
				$datareport[] = ['Account ID','Account Name','0-30','31-60','61-90','91-120','Above 121','Total'];
				$cr_total = 0; $dr_total = 0; $balance = 0; $balance_prnt = $amt1T = $amt2T = $amt3T = $amt4T = $amt5T = 0;
				
				foreach($transactions as $results) {
					if(count($results) > 0) {
						$amt1 = $amt2 = $amt3 = $amt4 = $amt5 = $lntotal = 0;
						foreach($results as $transaction) {
							
							$cr_amount = ''; $dr_amount = '';
							$balance_prnt = $transaction['dr_amount'] - $transaction['cr_amount'];	
							$balance += $balance_prnt;
						
							$nodays = date_diff(date_create($transaction['invoice_date']),date_create(date('Y-m-d')));
							
							if($nodays->format("%a%") < 30) {
								$amt1 += $balance_prnt;
								$amt1T += $balance_prnt;
							} else if($nodays->format("%a%") > 30 && $nodays->format("%a%") < 60) {
								$amt2 += $balance_prnt;
								$amt2T += $balance_prnt;
							} else if($nodays->format("%a%") > 60 && $nodays->format("%a%") < 90) {
								$amt3 += $balance_prnt;
								$amt3T += $balance_prnt;
							} else if($nodays->format("%a%") > 90 && $nodays->format("%a%") < 120) {
								$amt4 += $balance_prnt;
								$amt4T += $balance_prnt;
							} else if($nodays->format("%a%") > 120) {
								$amt5 += $balance_prnt;
								$amt5T += $balance_prnt;
							}
							$lntotal = $amt1+$amt2+$amt3+$amt4+$amt5;
							
						}
						
						if($balance_prnt != 0) { 
														
							if($balance_prnt > 0)
								$balance_prnt = number_format($balance_prnt,2);
							else if($balance_prnt < 0)
								$balance_prnt = '('.number_format(($balance_prnt*-1),2).')';
							else $balance_prnt = '';
							
							if($amt1 > 0)
								$amt1 = number_format($amt1,2);
							else if($amt1 < 0)
								$amt1 = '('.number_format(($amt1*-1),2).')';
							else $amt1 = '';
							
							if($amt2 > 0)
								$amt2= number_format($amt2,2);
							else if($amt2 < 0)
								$amt2 = '('.number_format(($amt2*-1),2).')';
							else $amt2 = '';
							
							if($amt3 > 0)
								$amt3= number_format($amt3,2);
							else if($amt3 < 0)
								$amt3 = '('.number_format(($amt3*-1),2).')';
							else $amt3 = '';
							
							if($amt4 > 0)
								$amt4= number_format($amt4,2);
							else if($amt4 < 0)
								$amt4 = '('.number_format(($amt4*-1),2).')';
							else $amt4 = '';
							
							if($amt5 > 0)
								$amt5= number_format($amt5,2);
							else if($amt5 < 0)
								$amt5 = '('.number_format(($amt5*-1),2).')';
							else $amt5 = '';
							
							if($lntotal > 0)
								$lntotal= number_format($lntotal,2);
							else if($lntotal < 0)
								$lntotal = '('.number_format(($lntotal*-1),2).')';
							else $lntotal = '';
						 }
						 
						 $datareport[] = ['acid' => $transaction['acid'],
										  'acname'	=> $transaction['acname'],
										  'amt1'	=> $amt1,
										  'amt2'	=> $amt2,
										  'amt3'	=> $amt3,
										  'amt4'	=> $amt4,
										  'amt5'	=> $amt5,
										  'lntotal'	=> $lntotal
											];
					}
				}
				
				if($balance > 0)
					$balance = number_format($balance,2);
				else if($balance < 0)
					$balance = '('.number_format(($balance*-1),2).')';
				
				if($amt1T > 0)
					$amt1T = number_format($amt1T,2);
				else if($amt1T < 0)
					$amt1T = '('.number_format(($amt1T*-1),2).')';
				
				if($amt2T > 0)
					$amt2T = number_format($amt2T,2);
				else if($amt2T < 0)
					$amt2T = '('.number_format(($amt2T*-1),2).')';
				else $amt2T = '';
				
				if($amt3T > 0)
					$amt3T = number_format($amt3T,2);
				else if($amt3T < 0)
					$amt3T = '('.number_format(($amt3T*-1),2).')';
				else $amt3T = '';
				
				if($amt4T > 0)
					$amt4T = number_format($amt4T,2);
				else if($amt4T < 0)
					$amt4T = '('.number_format(($amt4T*-1),2).')';
				else $amt4T = '';
				
				if($amt5T > 0)
					$amt5T = number_format($amt5T,2);
				else if($amt5T < 0)
					$amt5T = '('.number_format(($amt5T*-1),2).')';
				else $amt5T = '';
				
				$datareport[] = ['','Total',$amt1T,$amt2T,$amt3T,$amt4T,$amt5T,$balance];
			} 
			
		} else { 
		
				$resultrow = [];
				$opn_balnce = [];
				if($request->get('type')=='statement') {
					$voucher_head = 'Statement of Account';
					$datareport[] = ['','','',strtoupper($voucher_head),'',''];	
					$datareport[] = ['','','','','','',''];
					
					if($request->get('is_con')==1) {
						$reports = $this->SortByAccount($this->makeConsolidated( $this->accountmaster->getPrintViewByAccount($request->all())) );
					} else 
						$reports = $this->SortByAccount($this->accountmaster->getPrintViewByAccount($request->all()));
					//echo '<pre>'.print_r($reports);exit;
					//echo '<pre>'; print_r($request->all());exit;
					//GETTING OPENING BALANCE.....
					if( (date('d-m-Y',strtotime($this->acsettings->from_date))!=$request->get('date_from'))) { // && (date('d-m-Y',strtotime($this->acsettings->to_date))!=$request->get('date_to')) 
						foreach($reports as $key => $report ) {
							$resultrow[$key] = $this->accountmaster->findDetails($report[0]->account_master_id);
							
							$enddate = date('Y-m-d', strtotime('-1 day', strtotime($request->get('date_from'))));
						
							$obtrn = DB::table('account_transaction')->where('voucher_type','OB')->where('account_master_id',$report[0]->account_master_id)->where('status',1)
															->where('deleted_at','0000-00-00 00:00:00')->select('invoice_date')->first();
															
							$request->merge(['date_from' =>  $obtrn->invoice_date]);
							$request->merge(['date_to' => $enddate]);
							$fromdate = $obtrn->invoice_date;
							
							if($report[0]->voucher_type!='OB')
								$opn_balnce[$key] = $this->getOpeningBalance($this->accountmaster->getPrintViewByAccount($request->all()));
					
						}
						//echo '<pre>'.print_r($reports);exit;
					} else {
						foreach($reports as $key => $report ) {
							$resultrow[$key] = $this->accountmaster->findDetails($report[0]->account_master_id);
							if(!$report || $report[0]->voucher_type!='OB') {
								$obtrn = DB::table('account_transaction')->where('voucher_type','OB')->where('account_master_id',$report[0]->account_master_id)->where('status',1)
															->where('deleted_at','0000-00-00 00:00:00')->select('invoice_date')->first();
								
								$enddate = date('Y-m-d', strtotime('-1 day', strtotime($request->get('date_from'))));
								$request->merge(['date_from' => $obtrn->invoice_date]);
								$request->merge(['date_to' => $enddate]);
								$fromdate = $obtrn->invoice_date;
								
								$opn_balnce[$key] = $this->getOpeningBalance($this->accountmaster->getPrintViewByAccount($request->all()));
								//echo '<pre>'.print_r($opn_balnce);exit;
							}
						}
					}
					
					//echo '<pre>';print_r($opn_balnce);
					//echo '<pre>';print_r($resultrow);exit;
						
					$pdcreports = $this->accountmaster->getPDCPrintViewByAccount($request->all());
					
					$cr_total = 0; $dr_total = 0; $balance = 0; $i=0;
					
					
				foreach($reports as $key => $report) {
					
					$datareport[] = ['Acc. Name:'.$resultrow[$key]->master_name];
					$datareport[] = ['Acc. ID:'.$resultrow[$key]->account_id,($resultrow[$key]->phone!='')?' Ph:'.$resultrow[$key]->phone:'','TRN No:'.$resultrow[$key]->vat_no];
					$datareport[] = ['','','','','','',''];
					$datareport[] = ['Type','No','Date','Description', 'Reference','Debit','Credit','Balance'];
					
					//OPENING BALANCE ENTRY...
					if(isset($opn_balnce[$key])) {
						
						if($opn_balnce[$key]['transaction_type']=='Dr') { 
							$balance = $opn_balnce[$key]['amount'];
							$dr_total = ($balance < 0)?($balance*-1):$balance;
						}
						
						if($opn_balnce[$key]['transaction_type']=='Cr') { 
							$balance = $opn_balnce[$key]['amount'];
							$cr_total = ($balance < 0)?($balance*-1):$balance;
						}
						
						if($balance < 0) {
							$arr = explode('-', $balance);
							$balance_prnt = '('.number_format($arr[1],2).')';
						} else 
							$balance_prnt = number_format($balance,2);
													
						$datareport[] = [ 'type' => $opn_balnce[$key]['type'],
										  'no' => '',
										  'date' => date('d-m-Y', strtotime($fromdate)),
										  'desc' => '',
										  'ref' => 'Total',
										  'debit' => number_format( $dr_total, 2),
										  'credit' => number_format( $cr_total, 2),
										  'bal' => $balance_prnt
										];
					}
					
					foreach ($report as $row) {
							$i++;
							$date = ($row->invoice_date=='0000-00-00' || $row->invoice_date=='01-01-1970')?date('d-m-Y', strtotime($settings->from_date)):date('d-m-Y', strtotime($row->invoice_date));
							$desc = ($row->voucher_type=="OB")?'Opening Balance':$row->description;
							$ref = ($row->reference_from=="")?$row->reference:$row->reference_from;
							
							$cr_amount = ''; $dr_amount = '';
							if($row->transaction_type=='Cr') {
								$cr_amount = number_format($row->amount,2);
								if($row->amount >= 0) {
									$cr_total += $row->amount;
									$balance -= $row->amount;
								} else {
									$cr_total -= $row->amount;
									$balance += $row->amount;
								}
							} else if($row->transaction_type=='Dr') {
								$dr_amount = number_format($row->amount,2);
								$dr_total += $row->amount;
								$balance += $row->amount;
							}
							
							if($balance < 0) {
								$arr = explode('-', $balance);
								$balance_prnt = '('.number_format($arr[1],2).')';
							} else 
								$balance_prnt = number_format($balance,2);
												
							$datareport[] = [ 'type' => $row->voucher_type,
											  'no' => $row->reference,
											  'date' => $date,
											  'desc' => $desc,
											  'ref' => $ref,
											  'debit' => $dr_amount,
											  'credit' => $cr_amount,
											  'bal' => $balance_prnt
											];
					}
					$datareport[] = [ 'type' => '',
											  'no' => '',
											  'date' => '',
											  'desc' => '',
											  'ref' => 'Total',
											  'debit' => $dr_total,
											  'credit' => $cr_total,
											  'bal' => $balance_prnt
											];
											
					if($request->get('is_pdc')==1) { 
						if(count($pdcreports) > 0) {
							$datareport[] = ['','','','','','',''];
							$datareport[] = ['','','',strtoupper('Statement with PDC Details'),'',''];	
							$datareport[] = ['Type','No','Date','Cheque No','Cheque Date','Description','Reference','PDC Issued','PDC Received','Balance'];
							
							$cr_total = 0; $dr_total = 0; $balance = 0;
							foreach($pdcreports as $transaction) {
								$received = ''; $issued = '';
								if($transaction->voucher_type=='PDCR') {
									$received = number_format($transaction->amount,2);
									if($transaction->amount >= 0) {
										$cr_total += $transaction->amount;
										$balance = bcsub($balance, $transaction->amount, 2);
									} else {
										$cr_total -= $transaction->amount;
										$balance += $transaction->amount;
									}
								} else if($transaction->voucher_type=='PDCI') {
									$issued = number_format($transaction->amount,2);
									$dr_total += $transaction->amount;
									$balance += $transaction->amount;
								}
								
								if($balance < 0) {
									$arr = explode('-', $balance);
									$balance_prnt = '('.number_format($arr[1],2).')';
								} else 
									$balance_prnt = number_format($balance,2);
								
								$datareport[] = ['voucher_type' => ($transaction->voucher_type=='PDCR')?'RV':'PV',
												 'voucher_no'			=> $transaction->voucher_no,
												 'vdate'	=> ($transaction->voucher_date=='0000-00-00' || $transaction->voucher_date=='01-01-1970')?date('d-m-Y', strtotime($this->acsettings->from_date)):date('d-m-Y', strtotime($transaction->voucher_date)),
												 'cheque_no'	=> $transaction->cheque_no,
												 'cheque_date'	=> ($transaction->cheque_date=='0000-00-00' || $transaction->cheque_date=='01-01-1970')?'':date('d-m-Y', strtotime($transaction->cheque_date)),
												 'description'	=> $transaction->description.' '.$transaction->master_name,
												 'reference'	=> $transaction->reference,
												 'issued'	=> $issued,
												 'received'	=> $received,
												 'balance_prnt'	=> $balance_prnt
												];
							}
							
							$datareport[] = ['','','','','','','Total',number_format($dr_total,2),number_format($cr_total,2),$balance_prnt];
						}
					}
					
					$datareport[] = ['','','','','','','',''];
					$datareport[] = ['','','','','','','',''];
				}
											
				} else if($request->get('type')=='outstanding') {
					
					$voucher_head = 'Statement of Account - Outstanding';
					$datareport[] = ['','','',strtoupper($voucher_head),'',''];	
					$datareport[] = ['','','','','','',''];
					
					$results = $this->accountmaster->getPrintViewByAccount($request->all());
					$reports = $this->makeSummary2($this->makeTree3($this->makeTree2($results))); 
					/* $reports = $this->makeSummary($this->makeTree($results)); 
					usort($reports, array($this, "date_compare"));
					$reports = $this->SortByAccountOS($reports); */
					foreach($reports as $key => $report ) {
						$resultrow[$key] = $dat = $this->accountmaster->findDetails($report[0]['account_master_id']);
						
						if($dat->category=='PDCR' || $dat->category=='PDCI') {
							$osdat = $this->accountmaster->getPDCs($dat, $request->all());
							$resdat = null;
							foreach($osdat as $os) {

								if($os->status==0) {
									$resdat[] = $os;
								} 

								if($os->invoice_date > date('Y-m-d', strtotime($request->get('date_to')))) {
									$resdat[] = $os;
								} 
							}

							$resultPdc[$key] = $resdat;
						}
					}
					//echo '<pre>';print_r($resultrow);exit;
						
					$pdcreports = $this->accountmaster->getPDCPrintViewByAccount($request->all(),'OS');
					
				foreach($reports as $key => $report) {
						
					//$datareport[] = ['Invoice Date','Reference','Description','Debit', 'Credit','Balance'];
					$cr_total = 0; $dr_total = 0; $balance = 0;
					$amtdate1 = $amtdate2 = $amtdate3 = $amtdate4 = $amtdate5 = 0; //echo '<pre>';print_r($reports);exit;
					
					$datareport[] = ['Acc. Name:'.$resultrow[$key]->master_name];
					$datareport[] = ['Acc. ID:'.$resultrow[$key]->account_id,($resultrow[$key]->phone!='')?' Ph:'.$resultrow[$key]->phone:'','TRN No:'.$resultrow[$key]->vat_no];
					$datareport[] = ['','','','','','',''];
					
					foreach ($report as $row) {
						$balance_prnt = $amtdate = bcsub($row['dr_amount'], $row['cr_amount'], 2); //$balance_prnt = $row['dr_amount'] - $row['cr_amount'];	
						//chng strt
						if(($resultrow[$key]->category=='CUSTOMER' && $balance_prnt !=0) || ($resultrow[$key]->category=='VATIN' && $balance_prnt !=0)) {
							$dr_total += $row['dr_amount'];
							$cr_total += $row['cr_amount'];
							$balance += $balance_prnt;
							
							if($row['dr_amount'] > 0)
								$dr_amount = number_format($row['dr_amount'],2);
							else if($row['dr_amount'] < 0)
								$dr_amount = '('.number_format($row['dr_amount']*-1,2).')';
							else $dr_amount = '';
							
							if($row['cr_amount'] > 0)
								$cr_amount = number_format($row['cr_amount'],2);
							else if($row['dr_amount'] < 0)
								$cr_amount = '('.number_format($row['cr_amount']*-1,2).')';
							else $cr_amount = '';
							
							if($balance_prnt > 0)
								$balance_prnt = number_format($balance_prnt,2);
							else if($balance_prnt < 0)
								$balance_prnt = '('.number_format(($balance_prnt*-1),2).')';
							else $balance_prnt = '';
							
							$nodays = date_diff(date_create($row['invoice_date']), date_create(date('Y-m-d')));
							
							if($row['dr_amount'] > 0)
								$dr_amount = number_format($row['dr_amount'],2);
							else if($row['dr_amount'] < 0)
								$dr_amount = '('.number_format($row['dr_amount']*-1,2).')';
							else $dr_amount = '';
							
							if($row['cr_amount'] > 0)
								$cr_amount = number_format($row['cr_amount'],2);
							else if($row['dr_amount'] < 0)
								$cr_amount = '('.number_format($row['cr_amount']*-1,2).')';
							else $cr_amount = '';
							
							$datareport[] = [ 'date' => Date::dateTimeToExcel($row['invoice_date']),   //date('d-m-Y', strtotime($row['invoice_date'])),
											  'ref' => $row['reference_from'],
											  'descption' => $row['description'],
											  'dueamt' => $dr_amount,
											  'credit' => $cr_amount,
											  'debit' => $balance_prnt
											];
						
						} else if(($resultrow[$key]->category=='SUPPLIER' && $balance_prnt !=0) || ($resultrow[$key]->category=='VATOUT' && $balance_prnt !=0)){
							
							$dr_total += $row['dr_amount'];
							$cr_total += $row['cr_amount'];
							
							$balance += $balance_prnt;
							
							if($row['dr_amount'] > 0)
								$dr_amount = number_format($row['dr_amount'],2);
							else if($row['dr_amount'] < 0)
								$dr_amount = '('.number_format($row['dr_amount']*-1,2).')';
							else $dr_amount = '';
							
							if($row['cr_amount'] > 0)
								$cr_amount = number_format($row['cr_amount'],2);
							else if($row['dr_amount'] < 0)
								$cr_amount = '('.number_format($row['cr_amount']*-1,2).')';
							else $cr_amount = '';
							
							if($balance_prnt > 0)
								$balance_prnt = number_format($balance_prnt,2);
							else if($balance_prnt < 0)
								$balance_prnt = '('.number_format(($balance_prnt*-1),2).')';
							else $balance_prnt = '';
							
							$nodays = date_diff(date_create($row['invoice_date']), date_create(date('Y-m-d')));
							
							if($row['dr_amount'] > 0)
								$dr_amount = number_format($row['dr_amount'],2);
							else if($row['dr_amount'] < 0)
								$dr_amount = '('.number_format($row['dr_amount']*-1,2).')';
							else $dr_amount = '';
							
							if($row['cr_amount'] > 0)
								$cr_amount = number_format($row['cr_amount'],2);
							else if($row['dr_amount'] < 0)
								$cr_amount = '('.number_format($row['cr_amount']*-1,2).')';
							else $cr_amount = '';
							
							$datareport[] = [ 'date' => date('d-m-Y', strtotime($row['invoice_date'])),
											  'ref' => $row['reference_from'],
											  'descption' => $row['description'],
											  'dueamt' => $dr_amount,
											  'credit' => $cr_amount,
											  'debit' => $balance_prnt
											];
							
						}
						// chan end
						
					}
					
					$datareport[] = [ 'date' => '',
									  'ref' => '',
									  'descption' => 'Total',
									  'dueamt' => $dr_total,
									  'credit' => $cr_total,
									  'debit' => ($balance > 0)?number_format($balance,2):'('.number_format(($balance*-1),2).')'
									];
									
					if($request->get('is_pdc')==1) { 
						if(count($pdcreports) > 0) {
							$datareport[] = ['','','','','','',''];
							$datareport[] = ['','','',strtoupper('Outstanding with PDC Details'),'',''];	
							$datareport[] = ['Invoice Date','Reference','Cheque No','Cheque Date','Debit','Credit','Balance'];
							$cr_total = 0; $dr_total = 0; $balance = 0;
							foreach($pdcreports as $transaction) {
								$received = ''; $issued = '';
								if($transaction->voucher_type=='PDCR') {
									$received = number_format($transaction->amount,2);
									if($transaction->amount >= 0) {
										$cr_total += $transaction->amount;
										$balance = bcsub($balance, $transaction->amount, 2);
									} else {
										$cr_total -= $transaction->amount;
										$balance += $transaction->amount;
									}
								} else if($transaction->voucher_type=='PDCI') {
									$issued = number_format($transaction->amount,2);
									$dr_total += $transaction->amount;
									$balance += $transaction->amount;
								}
								
								if($balance < 0) {
									$arr = explode('-', $balance);
									$balance_prnt = '('.number_format($arr[1],2).')';
								} else 
									$balance_prnt = number_format($balance,2);
								
								$datareport[] = [ 'date' => ($transaction->voucher_date=='0000-00-00' || $transaction->voucher_date=='01-01-1970')?date('d-m-Y', strtotime($this->acsettings->from_date)):date('d-m-Y', strtotime($transaction->voucher_date)),
										  'ref' => $transaction->reference,
										  'duedate' => $transaction->cheque_no,
										  'dueamt' => ($transaction->cheque_date=='0000-00-00' || $transaction->cheque_date=='01-01-1970')?'':date('d-m-Y', strtotime($transaction->cheque_date)),
										  'credit' => $issued,
										  'debit' => $received,
										  'balance_prnt'	=> $balance_prnt
										];
							}
							
							$datareport[] = ['','','','Total',number_format($dr_total,2),number_format($cr_total,2),$balance_prnt];
						}
						
					}
				  }
					
				} else if($request->get('type')=='ageinggroup') {
					
					$accounts = DB::table('account_master')->where('category', $request->get('account_id'))->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id')->get();
						
					foreach($accounts as $row) {
						$results = $this->accountmaster->getAgeingSummary($row->id, $request->all());
						$transactions[] = $this->makeAgeingSummary($this->makeTree($results));
						
					}
					
					$datareport[] = ['Account ID','Account Name','0-30', '31-60','61-90', '91-120', 'Above 121','Total'];
					$cr_total = 0; $dr_total = 0; $balance = 0; $balance_prnt = 0;
					$amt1T = $amt2T = $amt3T = $amt4T = $amt5T = 0;
					
					$voucher_head = 'Statement of Account - Ageing Summary';
					$datareport[] = ['','','',strtoupper($voucher_head),'',''];	
					$datareport[] = ['','','','','','',''];
					$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];
					
					foreach($transactions as $results) {
						
						if(count($results) > 0) { 
							$amt1 = $amt2 = $amt3 = $amt4 = $amt5 = $lntotal = 0;
							
							foreach($results as $transaction) {
								
								$cr_amount = ''; $dr_amount = '';
								$balance_prnt = $transaction['dr_amount'] - $transaction['cr_amount'];	
								$balance += $balance_prnt;
								$nodays = date_diff(date_create($transaction['invoice_date']),date_create(date('Y-m-d')));
								
								if($nodays->format("%a%") <= 30) {
									$amt1 += $balance_prnt;
									$amt1T += $balance_prnt;
								} else if($nodays->format("%a%") > 30 && $nodays->format("%a%") <= 60) {
									$amt2 += $balance_prnt;
									$amt2T += $balance_prnt;
								} else if($nodays->format("%a%") > 60 && $nodays->format("%a%") <= 90) {
									$amt3 += $balance_prnt;
									$amt3T += $balance_prnt;
								} else if($nodays->format("%a%") > 90 && $nodays->format("%a%") <= 120) {
									$amt4 += $balance_prnt;
									$amt4T += $balance_prnt;
								} else if($nodays->format("%a%") > 120) {
									$amt5 += $balance_prnt;
									$amt5T += $balance_prnt;
								}
								$lntotal = $amt1+$amt2+$amt3+$amt4+$amt5;
							}
							
							if($balance_prnt != 0) { 
							
								if($balance_prnt > 0)
									$balance_prnt = number_format($balance_prnt,2);
								else if($balance_prnt < 0)
									$balance_prnt = '('.number_format(($balance_prnt*-1),2).')';
								else $balance_prnt = '';
								
								if($amt1 > 0)
									$amt1 = number_format($amt1,2);
								else if($amt1 < 0)
									$amt1 = '('.number_format(($amt1*-1),2).')';
								else $amt1 = '';
								
								if($amt2 > 0)
									$amt2= number_format($amt2,2);
								else if($amt2 < 0)
									$amt2 = '('.number_format(($amt2*-1),2).')';
								else $amt2 = '';
								
								if($amt3 > 0)
									$amt3= number_format($amt3,2);
								else if($amt3 < 0)
									$amt3 = '('.number_format(($amt3*-1),2).')';
								else $amt3 = '';
								
								if($amt4 > 0)
									$amt4= number_format($amt4,2);
								else if($amt4 < 0)
									$amt4 = '('.number_format(($amt4*-1),2).')';
								else $amt4 = '';
								
								if($amt5 > 0)
									$amt5= number_format($amt5,2);
								else if($amt5 < 0)
									$amt5 = '('.number_format(($amt5*-1),2).')';
								else $amt5 = '';
								
								if($lntotal > 0)
									$lntotal= number_format($lntotal,2);
								else if($lntotal < 0)
									$lntotal = '('.number_format(($lntotal*-1),2).')';
								else $lntotal = '';
							}
							
							$datareport[] = [ 'acid' => $transaction['acid'],
											  'acname' => $transaction['acname'],
											  'd1' => $amt1,
											  'd2' => $amt2,
											  'd3' => $amt3,
											  'd4' => $amt4,
											  'd5' => $amt5,
											  'lntotal' => $lntotal
										];
						}
					}
					
					if($balance > 0)
						$balance = number_format($balance,2);
					else if($balance < 0)
						$balance = '('.number_format(($balance*-1),2).')';
					
					if($amt1T > 0)
						$amt1T = number_format($amt1T,2);
					else if($amt1T < 0)
						$amt1T = '('.number_format(($amt1T*-1),2).')';
					
					if($amt2T > 0)
						$amt2T = number_format($amt2T,2);
					else if($amt2T < 0)
						$amt2T = '('.number_format(($amt2T*-1),2).')';
					else $amt2T = '';
					
					if($amt3T > 0)
						$amt3T = number_format($amt3T,2);
					else if($amt3T < 0)
						$amt3T = '('.number_format(($amt3T*-1),2).')';
					else $amt3T = '';
					
					if($amt4T > 0)
						$amt4T = number_format($amt4T,2);
					else if($amt4T < 0)
						$amt4T = '('.number_format(($amt4T*-1),2).')';
					else $amt4T = '';
					
					if($amt5T > 0)
						$amt5T = number_format($amt5T,2);
					else if($amt5T < 0)
						$amt5T = '('.number_format(($amt5T*-1),2).')';
					else $amt5T = '';
					
					$datareport[] = ['','Total:',$amt1T,$amt2T,$amt3T,$amt4T,$amt5T,$balance];
					
					Excel::create($voucher_head, function($excel) use ($datareport,$voucher_head) {

						// Set the spreadsheet title, creator, and description
						$excel->setTitle($voucher_head);
						$excel->setCreator('Profit ACC 365')->setCompany(Session::get('company'));
						$excel->setDescription($voucher_head);

						// Build the spreadsheet, passing in the payments array
						$excel->sheet('sheet1', function($sheet) use ($datareport) {
							$sheet->fromArray($datareport, null, 'A1', false, false);
						});

					})->store('xlsx',storage_path('public\uploads\statement'));
					
				//....JUN3	
				} else if($request->get('type')=='osmonthly') {
					
						$voucher_head = 'Statement of Account - Outstanding(Monthly)';
						$datareport[] = ['','','',strtoupper($voucher_head),'',''];	
						$datareport[] = ['','','','','','',''];
						$results = $this->accountmaster->getPrintViewByAccount($request->all()); 
						$transactions = $this->monthly($this->correction($this->makeSummary($this->makeTree($results)),$resultrow));
						$datareport[] = [$resultrow->master_name.' ('.$resultrow->account_id.')','','','','','',''];
						$datareport[] = [$resultrow->address,($resultrow->phone!='')?' Ph:'.$resultrow->phone:'','TRN No:'.$resultrow->vat_no];
						$datareport[] = ['','','','','','',''];
						$datareport[] = ['Inv.Date','Reference','Due Date','Debit','Credit','Balance'];
						
						foreach($transactions as $key => $trans) {
							
							  $cr_total = 0; $dr_total = 0; $balance = 0; $osbaltot = 0;
							  //usort($trans, 'date_compare');
							  usort($trans, array($this, "date_compare"));
							  $dateObj   = DateTime::createFromFormat('!m', $key);
							  $monthName = $dateObj->format('F'); 
							  $datareport[] = [$monthName,'','',''];
							  
							  foreach($trans as $transaction) {
								  
								$dr_total += $transaction['dr_amount'];
								$cr_total += $transaction['cr_amount'];
								$balance += $transaction['balance'];
								$osbalance = $transaction['dr_amount'] - $transaction['cr_amount'];
								$osbaltot += $osbalance;
								
								if($osbalance > 0)
									$osbalance = number_format($osbalance,2);
								else if($osbalance < 0)
									$osbalance = '('.number_format(($osbalance*-1),2).')';
								
								 $datareport[] = ['invoice_date' => date('d-m-Y', strtotime($transaction['invoice_date'])),
												  'reference_from'	=> $transaction['reference_from'],
												  'due_date'		=> date('d-m-Y', strtotime($transaction['due_date'])),
												  'dr_amount'	=> ($transaction['dr_amount']!=0)?$transaction['dr_amount']:'',
												  'cr_amount'	=> ($transaction['cr_amount']!=0)?$transaction['cr_amount']:'',
												  'osbalance'		=> $osbalance
												 ];
								
							  }
							  
							if($dr_total > 0)
								$dr_total = number_format($dr_total,2);
							else if($dr_total < 0)
								$dr_total = '('.number_format(($dr_total*-1),2).')';
								
							if($cr_total > 0)
								$cr_total = number_format($cr_total,2);
							else if($cr_total < 0)
								$cr_total = '('.number_format(($cr_total*-1),2).')';
							
							if($balance > 0)
								$balance = number_format($balance,2);
							else if($balance < 0)
								$balance = '('.number_format(($balance*-1),2).')';
							
							if($osbaltot > 0)
								$osbaltot = number_format($osbaltot,2);
							else if($osbaltot < 0)
								$osbaltot = '('.number_format(($osbaltot*-1),2).')';
							
							$datareport[] = ['','','','','Total:',$osbaltot];
						}
						
				} else {
					
					$voucher_head = 'Statement of Account - Ageing';
					$datareport[] = ['','','',strtoupper($voucher_head),'',''];	
					$datareport[] = ['','','','','','',''];
					
					$results = $this->accountmaster->getPrintViewByAccount($request->all());
					$reports = $this->makeSummary2($this->makeTree3($this->makeTree2($results))); //number_format
					/* $reports = $this->makeSummary($this->makeTree($results));
					$reports = $this->SortByAccountOS($reports); */
				
					foreach($reports as $key => $report ) {
						$resultrow[$key] = $this->accountmaster->findDetails($report[0]['account_master_id']);
					}
					
				foreach($reports as $key => $report) {
					
					$datareport[] = [$resultrow[$key]->master_name,'Acc. ID:'.$resultrow[$key]->account_id,($resultrow[$key]->phone!='')?' Ph:'.$resultrow[$key]->phone:'','TRN No:'.$resultrow[$key]->vat_no];
					$datareport[] = ['','','','','','',''];
					
					$datareport[] = ['Date','Reference','Description','Due Amount','0-30', '31-60','61-90', '91-120', 'Above 121'];
					$cr_total = 0; $dr_total = 0; $balance = 0; $balance_prnt = 0;
					$amt1T = $amt2T = $amt3T = $amt4T = $amt5T = 0;
					
					foreach ($report as $row) {
						$cr_amount = ''; $dr_amount = '';
						$balance_prnt = $row['dr_amount'] - $row['cr_amount'];	
						$balance += $balance_prnt;
					
						$nodays = date_diff(date_create($row['invoice_date']),date_create(date('Y-m-d')));
						$amt1 = $amt2 = $amt3 = $amt4 = $amt5 = '';
						if($nodays->format("%a%") <= 30) {
							$amt1 = $balance_prnt;
							$amt1T += $amt1;
						} else if($nodays->format("%a%") > 30 && $nodays->format("%a%") <= 60) {
							$amt2 = $balance_prnt;
							$amt2T += $amt2;
						} else if($nodays->format("%a%") > 60 && $nodays->format("%a%") <= 90) {
							$amt3 = $balance_prnt;
							$amt3T += $amt3;
						} else if($nodays->format("%a%") > 90 && $nodays->format("%a%") <= 120) {
							$amt4 = $balance_prnt;
							$amt4T += $amt4;
						} else if($nodays->format("%a%") > 120) {
							$amt5 = $balance_prnt;
							$amt5T += $amt5;
						}
						
						$datareport[] = [ 'date' => date('d-m-Y', strtotime($row['invoice_date'])),
										  'ref' => $row['reference_from'],
										  'desc' => $row['description'],
										  'd1' => $balance_prnt,
										  'd2' => $amt1,
										  'd3' => $amt2,
										  'd4' => $amt3,
										  'd5' => $amt4,
										  'd6' => $amt5
										];
					}
					$datareport[] = [ 'date' => '',
									  'ref' => '',
									  'desc' => 'Total',
									  'd1' => $balance,
									  'd2' => $amt1T,
									  'd3' => $amt2T,
									  'd4' => $amt3T,
									  'd5' => $amt4T,
									  'd6' => $amt5T
									];
				}
			 }
		}		
		//echo '<pre>';print_r($reports);exit;
			
	//	echo '<pre>';print_r($datareport);exit;
		Excel::create($voucher_head, function($excel) use ($datareport,$voucher_head) {

			// Set the spreadsheet title, creator, and description
			$excel->setTitle($voucher_head);
			$excel->setCreator('NumakPro ERP')->setCompany(Session::get('company'));
			$excel->setDescription($voucher_head);

			// Build the spreadsheet, passing in the payments array
			$excel->sheet('sheet1', function($sheet) use ($datareport) {
				$sheet->fromArray($datareport, null, 'A1', false, false);
			});

			
	})->store('xlsx',storage_path('emailfiles')); //public\uploads\statement
	
	//echo '<pre>';print_r($request->all());exit;
	
		
		
			/*$email = DB::table('account_master')->where('id',$account_id)->select('email')->first();
			foreach($email as $x=>$value){
				$_POST['mailid']=$value;
				
			}*/
			
			
			
			//echo '<pre>';print_r($_POST['mailid']);exit;
		if($request->get('type')=='statement'){
		        
        		Mail::raw('Please find the below attachment', function ($message){
        		$emails = $request->get('mailid');
        		$message->from(env('MAIL_USERNAME'));
        		$message->to(explode(',',$emails)); //explode("," , $_POST['mailid'])
        	    $message->subject('Statement of Account');
        	    $message->attach(storage_path('emailfiles/Statement of Account.xlsx'));//public\uploads\statement\Statement of Account.xlsx
        	});

		}
		else if($request->get('type')=='outstanding') {
			Mail::raw('Please find the below attachment', function ($message){
			$emails = $request->get('mailid');
			$message->from(env('MAIL_USERNAME'));
			$message->to(explode(',',$emails));
			$message->subject('Statement of Account-Outstanding');
			$message->attach(storage_path('emailfiles/Statement of Account - Outstanding.xlsx'));
			});
		}
		else {
			Mail::raw('Please find the below attachment', function ($message){
			$emails = $request->get('mailid');
			$message->from(env('MAIL_USERNAME'));
			$message->to(explode(',',$emails));
			$message->subject('Statement of Account-Ageing');
			$message->attach(storage_path('emailfiles/Statement of Account - Ageing.xlsx'));
			});
		}
		//$files = \File::allFiles(storage_path().'/public\uploads\filestore');
		//$path='storage/public/uploads/statement/Statement of Account.xlsx';
		//echo basename($path);
		//$file = basename($path);
		//$url= asset('storage/app/public/statement/Statement of Account.xlsx');
		//$url = env('APP_URL').'/profitacc365/storage/public/uploads/statement/Statement of Account.xlsx';
		//$mobile=$_POST['mobile'];
		//$email=$_POST['mailid'];
		//$path=storage_path();
		//echo '<pre>';print_r($email);exit;

	/*$curl = curl_init();

	curl_setopt_array($curl, array(
	CURLOPT_URL => "https://api.ultramsg.com/instance14510/messages/chat",
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 30,
	CURLOPT_SSL_VERIFYHOST => 0,
	CURLOPT_SSL_VERIFYPEER => 0,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => "POST",
	CURLOPT_POSTFIELDS => "token=zv55gjpj82ty367i&to=$mobile&body=heyy&priority=1&referenceId=",
	CURLOPT_HTTPHEADER => array(
		"content-type: application/x-www-form-urlencoded"
	),
	));

	$response = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);

	if ($err) {
	echo "cURL Error #:" . $err;
	} else {
	echo $response;
}*/
	
/*

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.ultramsg.com/instance14510/messages/document",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_SSL_VERIFYHOST => 0,
  CURLOPT_SSL_VERIFYPEER => 0,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "token=zv55gjpj82ty367i&to={$mobile}&filename=Statement of Account.xlsx&document={$url}&referenceId=&nocache=",
  CURLOPT_HTTPHEADER => array(
    "content-type: application/x-www-form-urlencoded"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
}  
*/
	echo '<script>alert("Email sent successfully");window.close();</script>';

	}	
}


