<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Input;
use Session;
use Response;
use DB;
use App;

class MsCustomerController extends Controller
{
	
	public function __construct() {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->middleware('auth');
	}
	
	public function index() {
		$data = array();
		$customers = DB::table('ms_customer')->where('deleted_at','0000-00-00 00:00:00')->get();
		return view('body.mscustomer.index')
					->withCustomers($customers)
					->withData($data);
	}
	
	public function add() {

		$area = DB::table('ms_area')->where('deleted_at', '0000-00-00 00:00:00')->get();
		return view('body.mscustomer.add')->withArea($area);
	}
	
	public function save() {
		try {
			$id = DB::table('ms_customer')
					->insertGetId([
						'name' => Input::get('name'),
						'phone' => Input::get('phone'),
						'address' => Input::get('address'),
						'city' => Input::get('city'),
						'area' => Input::get('area'),
						'fax' => Input::get('fax')
					]);
				
			if($id) {
				$cst_no = 100+$id;
				DB::table('ms_customer')->where('id',$id)->update(['customer_no' => $cst_no]);
			}
		
			Session::flash('message', 'Customer added successfully.');
			return redirect('ms_customer/add');
		} catch(ValidationException $e) { 
			return Redirect::to('ms_customer/add')->withErrors($e->getErrors());
		}
	}
	
	public function edit($id) { 

		$area = DB::table('ms_area')->where('deleted_at', '0000-00-00 00:00:00')->get();
		$cstrow = DB::table('ms_customer')->find($id);
						/* ->join('account_master AS AM', function($join) {
							$join->on('AM.id', '=', 'vehicle.customer_id');
						})
						->where('vehicle.id',$id)->select('vehicle.*','AM.master_name AS customer')->first(); */
						
		return view('body.mscustomer.edit')
					->withCstrow($cstrow)
					->withArea($area);
	}
	
	public function update($id)
	{	//echo '<pre>';print_r(Input::all());exit;
		DB::table('ms_customer')->where('id',$id)
				->update([
					'name' => Input::get('name'),
					'phone' => Input::get('phone'),
					'address' => Input::get('address'),
					'city' => Input::get('city'),
					'area' => Input::get('area'),
					'fax' => Input::get('fax')
				]);
		Session::flash('message', 'Customer updated successfully');
		return redirect('ms_customer');
	}
	
	public function destroy($id)
	{
		DB::table('ms_customer')->where('id',$id)->update(['deleted_at' => date('Y-m-d H:i:s')]);
		Session::flash('message', 'Customer deleted successfully.');
		return redirect('ms_customer');
	}
	
	public function getCustomer() {
		
		$customers = DB::table('ms_customer')
							->leftJoin('ms_area', 'ms_area.id', '=', 'ms_customer.area')
							->where('ms_customer.deleted_at', '0000-00-00 00:00:00')
							->select('ms_customer.name','ms_customer.phone','ms_customer.id','ms_customer.address',
									'ms_customer.city','ms_customer.customer_no','ms_area.name AS area')
							->orderBy('ms_customer.name', 'ASC')->get();
		return view('body.mscustomer.customers')
					->withCustomers($customers);
		
	}
	
}

