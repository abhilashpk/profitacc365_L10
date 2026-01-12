<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Input;
use Session;
use Response;
use DB;
use Excel;
use App;

class MsReportsController extends Controller
{
	
	public function __construct() {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->middleware('auth');
	}
	
	public function index() {
		
		//$technician = DB::table('ms_technician')->where('deleted_at','0000-00-00 00:00:00')->get();
		//$jobs = DB::table('ms_jobmaster')->where('deleted_at','0000-00-00 00:00:00')->get();
		return view('body.msreports.index');
					//->withJobs($jobs)
					//->withTechnician($technician);
	}
	
			
	public function getSearch() {

		$attributes = Input::all();
		
		$date_from = (Input::get('date_from')=='')?date('Y-m-d').' 00:00:00':date('Y-m-d H:i:s', strtotime(Input::get('date_from')));
		$date_to = (Input::get('date_from')=='')?date('Y-m-d').' 23:59:59':date('Y-m-d', strtotime(Input::get('date_to'))).' 23:59:59';
		
		if($attributes['type']=='wo') {
			$reporthead = 'Work Order Report';
			$qry = DB::table('ms_workorder')
						->join('ms_customer', 'ms_customer.id', '=', 'ms_workorder.customer_id')
						//->join('ms_technician', 'ms_technician.id', '=', 'ms_workorder.technician_id')
						->join('ms_worktype', 'ms_worktype.id', '=', 'ms_workorder.type_id')
						->join('ms_jobmaster', 'ms_jobmaster.id', '=', 'ms_workorder.job_id');
			
			if($date_from!='' || $date_to!='')			
				$qry->whereBetween('ms_workorder.creation_datetime', [$date_from, $date_to]);
			
			if($attributes['status']!='')
				$qry->where('ms_workorder.status', $attributes['status']);
			
			$qry->where('ms_workorder.deleted_at','0000-00-00 00:00:00');
			$report = $qry->select('ms_workorder.*','ms_customer.name AS customer','ms_workorder.technician_id AS technician',
									'ms_worktype.name AS wo_type','ms_jobmaster.name AS job')
						  ->get();
		} else {
			
			$reporthead = 'Work Enquiry Report';
			$qry = DB::table('ms_workenquiry')
						->join('ms_customer', 'ms_customer.id', '=', 'ms_workenquiry.customer_id')
						->join('ms_worktype', 'ms_worktype.id', '=', 'ms_workenquiry.type_id');
			
			if($date_from!='' || $date_to!='')			
				$qry->whereBetween('ms_workenquiry.enquiry_datetime', [$date_from, $date_to]);
			
			if($attributes['status']!='')
				$qry->where('ms_workenquiry.status', $attributes['status']);
			
			$qry->where('ms_workenquiry.deleted_at','0000-00-00 00:00:00');
			$report = $qry->select('ms_workenquiry.*','ms_customer.name AS customer',
									'ms_worktype.name AS wo_type')
						  ->get();
			
		}
		
		//echo '<pre>';print_r($report); exit;
		return view('body.msreports.preprint')
				->withReporthead($reporthead)
				->withWtype($attributes['type'])
				->withSts($attributes['status'])
				->withDateto($date_to)
				->withDatefrom($date_from)
				->withI(1)
				->withReport($report);
	}
	
	
	public function dataExport()
	{
		$attributes = Input::all();
		$datareport[] = ['','','',strtoupper(Session::get('company')),'','',''];
		$datareport[] = ['','','','','','',''];
		
			
		if($attributes['type']=='wo') {
			$head = 'Daily Maintenance Report';
			$qry = DB::table('ms_workorder')
						->join('ms_customer', 'ms_customer.id', '=', 'ms_workorder.customer_id')
						//->join('ms_technician', 'ms_technician.id', '=', 'ms_workorder.technician_id')
						->join('ms_worktype', 'ms_worktype.id', '=', 'ms_workorder.type_id')
						->join('ms_jobmaster', 'ms_jobmaster.id', '=', 'ms_workorder.job_id');
			
			$qry->whereBetween('ms_workorder.creation_datetime', [$attributes['date_from'], $attributes['date_to']] );
			
			if($attributes['status']!='')
				$qry->where('ms_workorder.status', $attributes['status']);
			
			$qry->where('ms_workorder.deleted_at','0000-00-00 00:00:00');
			$report = $qry->select('ms_workorder.*','ms_customer.name AS customer','ms_workorder.technician_id AS technician',
									'ms_worktype.name AS wo_type','ms_jobmaster.name AS job')
						  ->get();
						  
			$datareport[] = ['','','',$head,'','',''];
			$datareport[] = ['','','','','','',''];
			$datareport[] = ['Date From:'.date('d-m-Y',strtotime($attributes['date_from'])),'To:'.date('d-m-Y',strtotime($attributes['date_to']))];
			$datareport[] = ['','','','','','',''];
			$datareport[] = ['SI.No.','Date','WO.No.','Cust.Ref.No.','Project Name','Location','WO Requestor','WO Description','WO Type','Status'];
			
			$i = 0;
			foreach($report as $row) {
				$i++;
				$datareport[] = [ 'i' => $i,
								  'date' => date('d-m-Y',strtotime($row->creation_datetime)),
								  'wono' => $row->wo_no,
								  'reference_no' => $row->reference_no,
								  'job' => $row->job,
								  'location' => $row->location,
								  'customer' => $row->technician,
								  'description' => $row->description,
								  'wo_type' => $row->wo_type,
								  'status' => $this->getStatus($row->status)
								];
									
			}
			
		} else {
			
			$head = 'Work Enquiry Report';
			$qry = DB::table('ms_workenquiry')
						->join('ms_customer', 'ms_customer.id', '=', 'ms_workenquiry.customer_id')
						->join('ms_worktype', 'ms_worktype.id', '=', 'ms_workenquiry.type_id');
			
			$qry->whereBetween('ms_workenquiry.enquiry_datetime', [$attributes['date_from'], $attributes['date_to']] );
			
			if($attributes['status']!='')
				$qry->where('ms_workenquiry.status', $attributes['status']);
			
			$qry->where('ms_workenquiry.deleted_at','0000-00-00 00:00:00');
			$report = $qry->select('ms_workenquiry.*','ms_customer.name AS customer',
									'ms_worktype.name AS wo_type')
						  ->get();
						  
			$datareport[] = ['','','',$head,'','',''];
			$datareport[] = ['','','','','','',''];
			$datareport[] = ['Date From:'.date('d-m-Y',strtotime($attributes['date_from'])),'To:'.date('d-m-Y',strtotime($attributes['date_to']))];
			$datareport[] = ['','','','','','',''];
			$datareport[] = ['Enq.No.','Date','WO Type','Customer','Location','Status'];
			
			foreach($report as $row) {
				
				$datareport[] = [ 'wono' => $row->enq_no,
								  'date' => date('d-m-Y',strtotime($row->enquiry_datetime)),
								  'wo_type' => $row->wo_type,
								  'customer' => $row->customer,
								  'location' => $row->location,
								  'status' => $this->getStatusEq($row->status)
								];
									
			}
			
		}
		
		Excel::create($head.' on '.date('d-m-Y'), function($excel) use ($datareport, $head) {

			// Set the spreadsheet title, creator, and description
			$excel->setTitle($head);
			$excel->setCreator('Amazon Delivery Service')->setCompany(Session::get('company'));
			$excel->setDescription($head);

			// Build the spreadsheet, passing in the payments array
			$excel->sheet('sheet1', function($sheet) use ($datareport) {
				$sheet->fromArray($datareport, null, 'A1', false, false);
			});

		})->download('xlsx');
		
		
	}
	
	
	private function getStatus($status) {
		
		switch($status) {
			
			case 0:
			$name = 'Pending';
			break;
			
			case 1:
			$name = 'Hold';
			break;
			
			case 2:
			$name = 'Ongoing';
			break;
			
			case 3:
			$name = 'Completed';
			break;
		}
		
		return $name;
	}
	
	private function getStatusEq($status) {
		
		switch($status) {
			
			case 0:
			$name = 'Open';
			break;
			
			case 1:
			$name = 'Transfered';
			break;
			
			case 2:
			$name = 'Cancelled';
			break;
			
		}
		
		return $name;
	}
	
}
