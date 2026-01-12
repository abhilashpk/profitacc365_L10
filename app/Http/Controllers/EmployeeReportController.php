<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Employee\EmployeeInterface;


use App\Http\Requests;
use Notification;
use Input;
use Session;
use App;
use Excel;
use Auth;
use DB;

class EmployeeReportController extends Controller
{
	protected $employee;

	public function __construct(EmployeeInterface $employee) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->employee = $employee;
		$this->middleware('auth');
	}
	
	public function index() {

		if(Session::get('department')==1) { //if active...
			$deptid = Auth::user()->department_id;
			if($deptid!=0)
				$departments = DB::table('department')->where('id',$deptid)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
			else {
				$departments = DB::table('department')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
				//$deptid = $departments[0]->id;
			}
			$is_dept = true;
		} else {
			$is_dept = false;
			$departments = [];
			//$deptid = '';
		}
		
		$data = array();
		return view('body.employeereport.index')
					->withDepartments($departments)
					->withIsdept($is_dept)
					->withData($data);
	}
	
	
	public function getSearch()
	{
		$data = array();
		$reports = $this->employee->getReport(Input::all()); 
		$voucher_head = 'Employee Report';
		$titles = ['main_head' => 'Employee Report','subhead' => 'Employee Report'];
		//echo '<pre>';print_r($reports);exit;
		return view('body.employeereport.preprint')
					->withReports($reports)
					->withTitles($titles)
					->withNationality(Input::get('nationality'))
					->withFromdate(Input::get('date_from'))
					->withTodate(Input::get('date_to'))
					->withDesignation(Input::get('designation'))
					->withUrl('employee_report')
					->withVoucherhead($voucher_head)
					->withData($data);
	}
	
	public function dataExport()
	{
		$data = array();
		
		$reports = $this->employee->getReport(Input::all()); 
		$voucher_head = 'Employee Report';
				
		$datareport[] = ['','','',$voucher_head,'','',''];	
		$datareport[] = ['Code','Name','Designation','Department','Nationality','Date of Birth','Gender','Residance Address','Residance Phone No',
						'Home Address','Home Phone No','Email','Join Date','Rejoin Date','Duty Status','Passport ID','Issue Date','Expiry Date',
						'Issued Place','Visa Designation','Visa ID','Issue Date','Expiry Date','Labour Card ID','Issue Date','Expiry Date','Health Card ID',
						'Issue Date','Expiry Date','Health Card Info','ID Card','Issue Date','Expiry Date','Medical Exam ID','Issue Date',
						'Expiry Date','Contract Status','Basic Pay','HRA','Transport','Allowance1','Allowance2','Net Salary','Normal Workign Hr.','Normal Wage by',
						'OT Wage by','Leave/Month for AL','Alloted Anual ML','Air Ticket Allotment after','Alloted Anual CL','Remarks','Other Info'];	
		
		foreach($reports as $row) {
			
			if($row->duty_status==0) 
				$duty_status = 'on Leave'; 
			else if($row->duty_status==1) 
				$duty_status = 'on Duty'; 
			else 
				$duty_status = 'Resigned/Terminated';
			
			if($row->nwage==30) 
				$nwage = '30 Days'; 
			elseif($row->nwage==365) 
				$nwage = '365 Days'; 
			else $nwage ='Monthly';
			
			if($row->otwage==30) 
				$otwage = '30 Days';
			elseif($row->otwage==365) 
				$otwage = '365 Days'; 
			else $otwage = 'Monthly';
			
		  $datareport[] = [ 'code' => $row->code,
						  'name' => $row->name,
						  'designation' => $row->designation,
						  'department' => $row->department,
						  'nationality' => $row->nationality,
						  'dob' => ($row->dob=='0000-00-00')?'':date('d-m-Y', strtotime($row->dob)),
						  'gender' => ($row->gender==1)?'Male':'Female',
						  'address1' => $row->address1,
						  'phone' => $row->phone,
						  'address2' => $row->address2,
						  'phone2' => $row->phone2,
						  'email' => $row->email,
						  'join_date' => ($row->join_date=='0000-00-00')?'':date('d-m-Y', strtotime($row->join_date)),
						  'rejoin_date' => ($row->rejoin_date=='0000-00-00')?'':date('d-m-Y', strtotime($row->rejoin_date)),
						  'duty_status' => $duty_status,
						  'pp_id' => $row->pp_id,
						  'pp_issue_date' => ($row->pp_issue_date=='0000-00-00')?'':date('d-m-Y', strtotime($row->pp_issue_date)),
						  'pp_expiry_date' => ($row->pp_expiry_date=='0000-00-00')?'':date('d-m-Y', strtotime($row->pp_expiry_date)),
						  'pp_issue_place' => $row->pp_issue_place,
						  'v_designation' => $row->v_designation,
						  'v_id' => $row->v_id,
						  'v_issue_date' => ($row->v_issue_date=='0000-00-00')?'':date('d-m-Y', strtotime($row->v_issue_date)),
						  'v_expiry_date' => ($row->v_expiry_date=='0000-00-00')?'':date('d-m-Y', strtotime($row->v_expiry_date)),
						  'lc_id' => $row->lc_id,
						  'lc_issue_date' => ($row->lc_issue_date=='0000-00-00')?'':date('d-m-Y', strtotime($row->lc_issue_date)),
						  'lc_expiry_date' => ($row->lc_expiry_date=='0000-00-00')?'':date('d-m-Y', strtotime($row->lc_expiry_date)),
						  'hc_id' => $row->hc_id,
						  'hc_issue_date' => ($row->hc_issue_date=='0000-00-00')?'':date('d-m-Y', strtotime($row->hc_issue_date)),
						  'hc_expiry_date' => ($row->hc_expiry_date=='0000-00-00')?'':date('d-m-Y', strtotime($row->hc_expiry_date)),
						  'hc_info' => $row->hc_info,
						  'ic_id' => $row->ic_id,
						  'ic_issue_date' => ($row->ic_issue_date=='0000-00-00')?'':date('d-m-Y', strtotime($row->ic_issue_date)),
						  'ic_expiry_date' => ($row->ic_expiry_date=='0000-00-00')?'':date('d-m-Y', strtotime($row->ic_expiry_date)),
						  'me_id' => $row->me_id,
						  'me_issue_date' => ($row->me_issue_date=='0000-00-00')?'':date('d-m-Y', strtotime($row->me_issue_date)),
						  'me_expiry_date' => ($row->me_expiry_date=='0000-00-00')?'':date('d-m-Y', strtotime($row->me_expiry_date)),
						  'contract_status' => ($row->contract_status==1)?'Limited':'Unlimited',
						  'basic_pay' => number_format($row->basic_pay,2),
						  'hra' => number_format($row->hra,2),
						  'transport' => number_format($row->transport,2),
						  'allowance' => number_format($row->allowance,2),
						  'allowance2' => number_format($row->allowance2,2),
						  'net_salary' => number_format($row->net_salary,2),
						  'nwh' => $row->nwh,
						  'nwage' => $nwage,
						  'otwage' => $otwage,
						  'lev_per_mth' => $row->lev_per_mth,
						  'anual_ml' => $row->anual_ml,
						  'air_tkt' => ($row->air_tkt > 0)?$row->air_tkt:'',
						  'anual_cl' => $row->anual_cl,
						  'remarks' => $row->remarks,
						  'other_info' => $row->other_info
						];
		}
		
		Excel::create($voucher_head, function($excel) use ($datareport, $voucher_head) {

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
}


