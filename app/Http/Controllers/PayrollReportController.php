<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Employee\EmployeeInterface;
use App\Repositories\WageEntry\WageEntryInterface;

use App\Http\Requests;
use Notification;
use Input;
use Session;
use App;
use DB;

class PayrollReportController extends Controller
{
   
	protected $employee;
	protected $wageentry;

	public function __construct(EmployeeInterface $employee, WageEntryInterface $wageentry) {

		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->employee = $employee;
		$this->wageentry = $wageentry;
		$this->middleware('auth');
	}
	
	public function index() {
		$data = array(); $reports = null;
		$reports = null;
		$employees = $this->employee->activeEmployeeList();
		$jobs = DB::table('jobmaster')->where('is_salary_job',0)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		return view('body.payrollreport.index')
					->withReports($reports)
					->withType('')
					->withFromdate('')
					->withTodate('')
					->withEmployees($employees)
					->withSettings($this->acsettings)
					->withJobs($jobs)
					->withData($data);
	}
	
	private function summary_wage($result)
	{
		if(count($result) > 0) {
			$otg_hr = $oth_hr = $nwhr = $otg_wg = $oth_wg = 0;
			foreach($result as $row) {
				$otg_hr += $row->otg;
				$oth_hr += $row->oth;
				$nwhr += $row->nwh;
			}
			
			$otg_wg = ( ($result[0]->net_otg > 0) && ($otg_hr > 0) )?$result[0]->net_otg / $otg_hr:0;
			$oth_wg = (($result[0]->net_oth > 0) && ($oth_hr > 0) )?$result[0]->net_oth / $oth_hr:0;
			
			$res = array( 'month' => $result[0]->month,
						  'nhr' => $nwhr, 
						  'nhr_wg' => $result[0]->wage,
						  'otg_hr' => $otg_hr,
						  'otg_wg' => $otg_wg,
						  'oth_hr' => $oth_hr,
						  'oth_wg' => $oth_wg,
						  'salary' => $result[0]->net_basic,
						  'hra' => $result[0]->net_hra,
						  'net_allw' => $result[0]->net_allowance,
						  'net_total' => $result[0]->net_total,
						  'wage_entry_id' => $result[0]->wage_entry_id
						  );
			return $res;
		} else return [];
	}
	
	private function group_jobs($jobs)
	{
		$childs = array();
		foreach($jobs as $job)
			$childs[$job->job_id][] = $job;
		
		return $childs;
	}
	
	private function calculate_job_wages($jobs)
	{
		$jobs = $this->group_jobs($jobs);
		$res = [];
		foreach($jobs as $job)
		{
			$job_hr=$job_wg=$job_ot_hr=$job_ot_wg=$job_oth_hr=$job_oth_wg=$allowance=0;
			foreach($job as $row) {
				
				$job_hr += $row->nwh;
				$job_wg = $row->wage;
				
			
				$job_ot_hr += $row->otg;
				$job_ot_wg = $row->otg_wage;
				
			
				$job_oth_hr += $row->oth;
				$job_oth_wg = $row->oth_wage;
				
			}
			
			$job_wg = $job_wg * $job_hr;
			$job_ot_wg = $job_ot_wg * $job_ot_hr;
			$job_oth_wg = $job_oth_wg * $job_oth_hr;
			
			$total = $job_wg + $job_ot_wg + $job_oth_wg;
			
			$res[] = 	[ 'job_code' => $job[0]->job_code,
					  'job_name' => $job[0]->job_name,
					  'job_hr' 	 => $job_hr,
					  'job_wg'	 => $job_wg,
					  'job_ot_hr'=> $job_ot_hr,
					  'job_ot_wg'=> $job_ot_wg,
					  'job_oth_hr'=> $job_oth_hr,
					  'job_oth_wg'=> $job_oth_wg,
					  'job_allw'  => $allowance,
					  'total'	  => $total
					];
			
		}
		
		return $res;
	}
	
	private function group_emp($emp)
	{
		$childs = array();
		foreach($emp as $row)
			$childs[$row->id][] = $row;
		
		return $childs;
	}
	
	public function getSearch()
	{
		$data = $wages = $employee = array();
		$we_others = '';
		if(Input::get('search_type')=='pay_slip') {
			$voucher_head = 'Pay Slip';
			$titles = ['main_head' => 'Pay Slip','subhead' => 'Pay Slip'];
			$employee = $this->employee->find(Input::get('employee_id'));
			$results = $this->wageentry->getPayslip(Input::all());  //echo '<pre>';print_r($employee);exit;
			
		} else if(Input::get('search_type')=='pay_slip_summery') {
			$voucher_head = 'Pay Slip(Summery)'; $we_others = [];
			$titles = ['main_head' => 'Pay Slip(Summery)','subhead' => 'Pay Slip(Summery)'];
			$results = $this->employee->find(Input::get('employee_id')); 
			$wages = $this->summary_wage( $this->wageentry->getPayslip_summery(Input::all()) );//echo '<pre>';print_r($wages);exit;
			if($wages)
				$we_others = DB::table('wage_entry_others')->where('wage_entry_id',$wages['wage_entry_id'])->first();
			//echo '<pre>';print_r($wages);exit;
		} else if(Input::get('search_type')=='jobwise_summery') {
			$voucher_head = 'Payroll Jobwise - Summery'; $wages = [];
			$titles = ['main_head' => 'Payroll Jobwise - Summery','subhead' => 'Payroll Jobwise - Summery'];
			$wages = $this->wageentry->get_jobwise_summery(Input::all());//echo '<pre>';print_r($wages);exit;
			if($wages)
				$results = $this->calculate_job_wages($wages);
			
		} else if(Input::get('search_type')=='payroll_summery') {
			$voucher_head = 'Payroll - Summery';
			$titles = ['main_head' => 'Payroll - Summery','subhead' => 'Payroll - Summery'];
			$results = $this->wageentry->payslip_summery(Input::all());
			//echo '<pre>';print_r($results);exit;
		} else if(Input::get('search_type')=='attendance') {
			$voucher_head = 'Employee Attendance';
			$titles = ['main_head' => 'Employee Attendance','subhead' => 'Employee Attendance'];
			$results = $this->group_emp( $this->wageentry->get_attendance(Input::all()) );
			//echo '<pre>';print_r($results);exit;
		} else if(Input::get('search_type')=='jobwise') {
			$voucher_head = 'Jobwise Detail';
			$titles = ['main_head' => 'Jobwise Detail','subhead' => 'Jobwise Detail'];
			$results = $this->wageentry->get_jobwise2(Input::all());
			//echo '<pre>';print_r($results);exit;
		} 
		
		//echo '<pre>';print_r($results);exit;
		return view('body.payrollreport.print')
					->withResult($results)
					->withVoucherhead($voucher_head)
					->withType(Input::get('search_type'))
					->withFromdate(Input::get('date_from'))
					->withTodate(Input::get('date_to'))
					->withVoucherhead($voucher_head)
					->withTitles($titles)
					->withMonth(Input::get('month'))
					->withUrl('payroll_report')
					->withWages($wages)
					->withEmployee($employee)
					->withWeothers($we_others)
					->withData($data);
	}
	
	
	public function getPrint()
	{
		$data = array();
		$voucher_head = 'Pay Slip';
		$results = $this->employee->find(Input::get('employee_id'));  //echo '<pre>';print_r($results);exit;//getPayslip
		$titles = ['main_head' => 'Purchase Order','subhead' => 'Purchase Order'];
		
		
		return view('body.payrollreport.print')
					->withResults($results)
					->withType(Input::get('search_type'))
					->withVoucherhead($voucher_head)
					->withTitles($titles)
					->withUrl('quantity_report')
					->withData($data);
	}	
	
	public function jobForm()
	{
		$data = array();
		$results = null;
		$jobs = DB::table('jobmaster')->where('is_salary_job',0)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		return view('body.payrollreport.jobsearch')
					->withReports($results)
					->withFromdate('')
					->withTodate('')
					->withType('')
					->withJobmasters($jobs)
					->withData($data);
	}
	
	public function jobSearch()
	{
		$data = $wages = $employee = array();
		
		if(Input::get('search_type')=='summary') {
			
			$results = $this->calculate_job_wages( $this->wageentry->get_jobwise(Input::all()) );
			$titles = ['main_head' => 'Job Report ','subhead' => 'Job Report - Summary' ];
			
		} else if(Input::get('search_type')=='detail') {
			
			$voucher_head = 'Jobwise Detail';
			$titles = ['main_head' => 'Jobwise Detail','subhead' => 'Jobwise Detail'];
			$results = $this->wageentry->get_jobwise(Input::all());
			$titles = ['main_head' => 'Job Report ','subhead' => 'Job Report - Detail' ];
		}
		
		//echo '<pre>';print_r($results);exit;
		return view('body.payrollreport.jobprint')
					->withReports($results)
					->withType(Input::get('search_type'))
					->withTitles($titles)
					->withUrl('')
					->withFromdate(Input::get('date_from'))
					->withTodate(Input::get('date_to'))
					->withJobid(Input::get('job_id'))
					->withData($data);
	}
}
