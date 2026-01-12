<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Notification;
use Input;
use Session;
use DB;
use App;

class DesignController extends Controller
{
   
	
	public function __construct() {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->middleware('auth');

	}

	protected function index2() {
		
		$data = array();
		$categories = $this->accategory->accategoryList();//echo '<pre>';print_r($categories);exit;
		//Session::flash('message', 'Accategory added successfully.');
		return view('body.accategory.index')
					->withCategories($categories)
					->withData($data);
	}

	
	public function index($id=null)
	{  
		$stimulsoft_v = config('app.stimulsoft_ver');
		$path = app_path() . '/stimulsoft/helper.php';
		$view = DB::table('design_view')->where('id',1)->first();

		if($stimulsoft_v==2)
			return view('body.design.designer')->withView($view->view_name);
		else
			return view('body.design.design')->withPath($path)->withView($view->view_name);
	}
	
	public function viewer()
	{
		//require_once(app_path() . '\stimulsoft\helper.php');
		$path = app_path() . '/stimulsoft/helper.php';
		$view = DB::table('design_view')->where('id',1)->first();
		//$options = getPrices();
		//$options = createOptions();
		//$ar = initialize($options);
		//echo '<pre>';print_r($ar);
		return view('body.design.viewer')->withPath($path)->withView($view->view_name);
	}
	
}

