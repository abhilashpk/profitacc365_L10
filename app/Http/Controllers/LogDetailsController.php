<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\LogDetails\LogDetailsInterface;

use App\Http\Requests;
use Input;
use Session;
use Response;
use App;

class LogDetailsController extends Controller
{
    protected $log_details;
	
	public function __construct(LogDetailsInterface $log_details) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->log_details = $log_details;
		$this->middleware('auth');
		
	}
	
	public function index() {
		$data = $reports = array();
		return view('body.logdetails.index')
					->withReports($reports)
					->withLogtype('')
					->withTrtype('')
					->withFromdate('')
					->withTodate('')
					->withData($data);
	}
	
	public function getSearch()
	{
		$data = array();
		
		$reports = $this->log_details->getLogDetails(Input::all()); 
		
		//echo '<pre>';print_r($reports);exit;
		return view('body.logdetails.index')
					->withReports($reports)
					->withLogtype(Input::get('log_module'))
					->withTrtype(Input::get('tr_type'))
					->withFromdate(Input::get('date_from'))
					->withTodate(Input::get('date_to'))
					->withData($data);
	}
}

