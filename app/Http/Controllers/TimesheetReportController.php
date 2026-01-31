<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Employee\EmployeeInterface;
use App\Repositories\WageEntry\WageEntryInterface;
use App\Repositories\Parameter4\Parameter4Interface;

use App\Http\Requests;
use Notification;
use Input;
use Session;
use App;
use DB;

class TimesheetReportController extends Controller
{
   
	protected $employee;
	protected $wageentry;
    protected $parameter4;
	public function __construct(EmployeeInterface $employee, WageEntryInterface $wageentry,Parameter4Interface $parameter4) {

		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->employee = $employee;
		$this->wageentry = $wageentry;
        $this->parameter4 = $parameter4;
		$this->middleware('auth');
	}
	
	public function index() {
		$data = array(); $reports = null;
		$reports = null;
		$employees = $this->employee->activeEmployeeList();
		$jobs = DB::table('jobmaster')->where('is_salary_job',0)->where('status',1)->whereNull('deleted_at')->get();
		return view('body.timesheetreport.index')
					->withReports($reports)
					->withType('')
					->withFromdate('')
					->withTodate('')
					->withEmployees($employees)
					->withSettings($this->acsettings)
					->withJobs($jobs)
					->withData($data);
	}

    public function payroll() {
		$data = array(); 
		$reports = null;
		$employees = $this->employee->activeEmployeeList();
		return view('body.timesheetreport.payroll')
					->withEmployees($employees)
					->withSettings($this->acsettings)
					->withData($data);
	}
	
	
	
	public function getSearch()
	{
        $data = array();
		//echo '<pre>';print_r($request->all());exit;
		if($request->get('search_type')=='daily') {
			$voucher_head = 'Timesheet Employee Wise Daily Report';
			$titles = ['main_head' => 'Timesheet Employee Wise Report','subhead' => 'Timesheet Employee Wise Daily Report'];
			$id=$request->get('employee_id');
		   $employee = $this->employee->find($id);
		   $parameter4 = $this->parameter4->getParameter4();
			$results = $this->wageentry->timesheetdailySearch($request->all()); // echo '<pre>';print_r($results);exit;
			
		} else if($request->get('search_type')=='monthly') {
			$voucher_head = 'Timesheet Employee Wise Report'; 
			$titles = ['main_head' => 'Timesheet Employee Wise Report','subhead' => 'Timesheet Employee Wise Report'];
			$id=$request->get('employee_id');
		   $employee = $this->employee->find($id);
		   $parameter4 = $this->parameter4->getParameter4();
			$results = $this->wageentry->timesheetmonthlySearch($request->all()); 
			//echo '<pre>';print_r($resul);exit;
		 
			//echo '<pre>';print_r($results);exit;
		} 
		
		//echo '<pre>';print_r($results);exit;
		return view('body.timesheetreport.print')
					->withResult($results)
					->withEmployee($employee)
					->withParameter($parameter4)
					->withType($request->get('search_type'))
				    ->withVoucherhead($voucher_head)
					->withTitles($titles)
					->withMonth($request->get('month'));
					
    }			
			
    public function getpayrollSearch(){
        $voucher_head = 'PAYROLL SHEET';
        $titles = ['main_head' => 'PAYROLL SHEET','subhead' => 'PAYROLL SHEET'];
        $id=$request->get('employee_id');
        $month=$request->get('month');
       $employee = $this->employee->find($id);
       $parameter4 = $this->parameter4->getParameter4();
		$wage_entry = $this->wageentry->get_wage_entryts($id,$month);	
		$wage_entry_items = $this->wageentry->get_wage_entryts_items($id,$month);	
        $results = $this->wageentry->getPayslipMonth($id,$month);
		//echo '<pre>';print_r($wage_entry );exit;
       return view('body.timesheetreport.payrollprint')
       ->withEmployee($employee)
       ->withResult($results)
       ->withParameter($parameter4)
       ->withWageentry($wage_entry)
       ->withWitems($wage_entry_items)
       ->withType($request->get('search_type'))
       ->withVoucherhead($voucher_head)
       ->withTitles($titles)
       ->withMonth($month);
       

    }
					
	
	
	
	public function jobSearch()
	{
		$data = $wages = $employee = array();
		
		if($request->get('search_type')=='summary') {
			
			$results = $this->calculate_job_wages( $this->wageentry->get_jobwise($request->all()) );
			$titles = ['main_head' => 'Job Report ','subhead' => 'Job Report - Summary' ];
			
		} else if($request->get('search_type')=='detail') {
			
			$voucher_head = 'Jobwise Detail';
			$titles = ['main_head' => 'Jobwise Detail','subhead' => 'Jobwise Detail'];
			$results = $this->wageentry->get_jobwise($request->all());
			$titles = ['main_head' => 'Job Report ','subhead' => 'Job Report - Detail' ];
		}
		
		//echo '<pre>';print_r($results);exit;
		return view('body.payrollreport.jobprint')
					->withReports($results)
					->withType($request->get('search_type'))
					->withTitles($titles)
					->withUrl('')
					->withFromdate($request->get('date_from'))
					->withTodate($request->get('date_to'))
					->withJobid($request->get('job_id'))
					->withData($data);
	}
}


