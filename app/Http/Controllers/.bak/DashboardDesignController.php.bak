<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Notification;
use Input;
use Session;
use DB;
use App;

class DashboardDesignController extends Controller
{
   
	
	public function __construct() {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->middleware('auth');

	}

	
	public function index($id=null)
	{  
		
		return view('body.dashboarddesign.index');
	}
	
}
