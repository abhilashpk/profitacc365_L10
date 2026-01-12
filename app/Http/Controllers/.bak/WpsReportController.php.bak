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
use Excel;
use DB;

class WpsReportController extends Controller
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
	//	echo '<pre>';print_r($employees);exit;
		$jobs = DB::table('jobmaster')->where('is_salary_job',0)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		return view('body.empreport.index')
					->withReports($reports)
					->withType('')
					->withFromdate('')
					->withMonth('')
					->withTodate('')
					->withEmployees($employees)
					->withSettings($this->acsettings)
					->withJobs($jobs)
					->withData($data);
	}
	
	public function getSearch()
	{
	//	echo '<pre>';print_r(Input::all());exit;
		$month = Input::get('month');
		$firstday = date('01-' . $month . '-Y');
        $lastday = date(date('t', strtotime($firstday)) .'-' . $month . '-Y');
        $data = '';
		$voucher_head = 'WPS REPORT';
		$reports = $this->employee->getwpsReport(Input::all());
			
		
		//echo '<pre>';print_r($reports);exit;
		return view('body.empreport.print')
			->withFirstday($firstday)	
			->withLastday($lastday)	
			->withMonth($month)	
		->withReports($reports)
			->withVoucherhead($voucher_head)
					->withData($data);
	}
	public function dataExport()
	{
		$data = array();
		$datareport[] = [strtoupper(Session::get('company')),'','',''];
		$datareport[] = ['','','','','','',''];
			$month = Input::get('month');
		$firstday = date('01-' . $month . '-Y');
        $lastday = date(date('t', strtotime($firstday)) .'-' . $month . '-Y');
		Input::merge(['type' => 'export']);
		$reports = $this->employee->getwpsReport(Input::all());
		
		
			$voucher_head = 'WPS Report';
	
		
		$datareport[] = ['','','','',strtoupper($voucher_head), '','',''];
		$datareport[] = ['','','','','','',''];
		

			
			$datareport[] = ['SI.No.','Edr', 'Labour Card', 'Routing code', 'A/c No.','Salary start date','Salary end date','No.of days','Fixed salary','Net payable','No.of days leave','Emp.Name','MOL-File No.','Company name','Nationality'];
			$i=0;
			foreach ($reports as $row) {
				$i++;
				
				$datareport[] = [ 'si' => $i,
								  'edr' => $row['emp_detail_type'], 
								  'lcn' => $row['lc_id'],
								  'rc' => $row['routing_code'],
								  'a/cno' => $row['account_number'],
								  'start' => $firstday,
								  'end' => $lastday,
								  'days' => $row['nwage'],
								  'fixed_salary' => number_format($row['basic_pay'],2),
								  'net_payable' => number_format($row['net_salary'],2),
								  
								  'days_leave' => $row['lev_per_mth'],
								  'name' => $row['name'],
								  'mol' => $row['mol_no'],
								  'comp' => $row['e_company'],
								  'nat' => $row['nationality']
								];
			}
		
		 //echo $voucher_head.'<pre>';print_r($datareport);exit;
		Excel::create($voucher_head, function($excel) use ($datareport,$voucher_head) {

        // Set the spreadsheet title, creator, and description
        $excel->setTitle($voucher_head);
        $excel->setCreator('Profit Acc 365')->setCompany(Session::get('company'));
        $excel->setDescription($voucher_head);

        // Build the spreadsheet, passing in the payments array
		$excel->sheet('sheet1', function($sheet) use ($datareport) {
			$sheet->fromArray($datareport, null, 'A1', false, false);
		});

		})->download('xlsx');
		
	}
	
	


}