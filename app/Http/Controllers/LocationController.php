<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Location\LocationInterface;

use App\Http\Requests;
use Session;
use Response;
use App;
use DB;

class LocationController extends Controller
{
    protected $location;
	
	public function __construct(LocationInterface $location) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->location = $location;
		$this->middleware('auth');
	}
	
	public function index() {
		$data = array();
		$locations = $this->location->allLoc();
		return view('body.location.index')
					->withLocations($locations)
					->withData($data);
	}
	
	public function add() {

		$data = array();
		$customers = DB::table('account_master')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->where('category','CUSTOMER')->get();
		return view('body.location.add')
					->withCustomers($customers)
					->withData($data);
	}
	
	public function save(Request $request) {
		$this->location->create($request->all());
		Session::flash('message', 'Location added successfully.');
		return redirect('location/add');
	}
	
	public function edit($id) { 

		$data = array();
		$locationrow = $this->location->find($id);
		$customers = DB::table('account_master')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->where('category','CUSTOMER')->get();
		return view('body.location.edit')
					->withLocationrow($locationrow)
					->withCustomers($customers)
					->withData($data);
	}
	
	public function update($id, Request $request)
	{
		$this->location->update($id, $request->all());//print_r($request->all());exit;
		//Session::flash('message', 'Category updated successfully');
		return redirect('location');
	}
	
	public function destroy($id)
	{
		$this->location->delete($id);
		//check location name is already in use.........
		// code here ********************************
		Session::flash('message', 'Location deleted successfully.');
		return redirect('location');
	}
	
	public function checkcode(Request $request) {

		$check = $this->location->check_location_code($request->get('code'), $request->get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
	
	public function checkname(Request $request) {

		$check = $this->location->check_location_name($request->get('name'), $request->get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
	
	public function getLocation($id=null)
	{
		$info = $this->location->locationList();
		return view('body.location.locinfo')
					->withId($id)
					->withInfo($info);
	}

	public function getBin($num,$mod=null)
	{
		$binloc = DB::table('bin_location')->where('deleted_at',null)->get();
		return view('body.location.binloc')
					->withBinloc($binloc)
					->withNum($num);
	}

	public function ajaxSave(Request $request) {
		
		$check1 = DB::table('bin_location')->where('code', trim($request->get('bin_code')))->where('deleted_at',null)->count();
		if(($check1 > 0))
			return 0;
		
		$check2 = DB::table('bin_location')->where('name', trim($request->get('name')))->where('deleted_at',null)->count();
		if(($check2 > 0))
			return -1;
		
		$id = DB::table('bin_location')
				->insertGetId([
					'code' => trim($request->get('bin_code')),
					'name' => trim($request->get('name'))
				]);
			
		return $id;
			
	}

}

