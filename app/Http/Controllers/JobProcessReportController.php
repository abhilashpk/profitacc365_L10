<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Jobmaster\JobmasterInterface;

use App\Http\Requests;
use Notification;
use Input;
use Session;
use Excel;
use App;
use DB;

class JobProcessReportController extends Controller
{
   
	protected $jobmaster;

	public function __construct(JobmasterInterface $jobmaster) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->jobmaster = $jobmaster;
		$this->middleware('auth');
	}
	
	public function index() {
		$jobmasters = $this->jobmaster->activeJobmasterList();
		return view('body.jobprocessreport.index')
					->withJobmasters($jobmasters);
	}
	
	protected function sortbyType($result)
	{

		$childs = array();
		foreach($result as $key => $items)
			foreach($items as $item)
				$childs[$key][$item->type][] = $item;
		
		return $childs;
		
	}
	
	public function getSearch(Request $request)
	{		
		if(Input::get('search_type')=='summary') {
			$voucher_head = 'Job Order Processing Report - Summary';
			$reports = $this->sortbyType( $this->jobmaster->getJobProcessReport($request->all()) ); 
		} else if(Input::get('search_type')=='detail') {
			$voucher_head = 'Job Order Processing Report - Detail';
			$reports = $this->sortbyType( $this->jobmaster->getJobProcessReportDetail(Input::all()) );
		}	
		//echo '<pre>';print_r($reports);exit;		
		return view('body.jobprocessreport.report')
					->withStype($request->get('search_type'))
					->withVoucherhead($voucher_head)
					->withReports($reports);
	}
	
}
