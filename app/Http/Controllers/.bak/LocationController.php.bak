<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Location\LocationInterface;

use App\Http\Requests;
use Input;
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
	
	public function save() {
		$this->location->create(Input::all());
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
	
	public function update($id)
	{
		$this->location->update($id, Input::all());//print_r(Input::all());exit;
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
	
	public function checkcode() {

		$check = $this->location->check_location_code(Input::get('code'), Input::get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
	
	public function checkname() {

		$check = $this->location->check_location_name(Input::get('name'), Input::get('id'));
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

//SELECT itemmaster.item_code,sales_invoice_item.assembly_items,sales_invoice_item.assembly_items_qty,SC.category_name AS subcategory,C.category_name AS category,GC.group_name AS subgroup,groupcat.group_name AS brand,itemmaster.weight ,sales_invoice.voucher_no,reference_no,sales_invoice.voucher_date,sales_invoice.lpo_no,sales_invoice.total,sales_invoice.discount,sales_invoice.vat_amount,sales_invoice.net_total,sales_invoice.total_fc,sales_invoice.discount_fc,sales_invoice.vat_amount_fc,sales_invoice.net_total_fc,sales_invoice.customer_name,sales_invoice.customer_phone,sales_invoice.subtotal,account_master.account_id,account_master.master_name,account_master.address,account_master.phone,account_master.vat_no,terms.description AS terms,salesman.name AS salesman,sales_invoice_item.item_name,sales_invoice_item.quantity,sales_invoice_item.unit_price,sales_invoice_item.vat,sales_invoice_item.vat,sales_invoice_item.vat_amount AS line_vat,sales_invoice_item.line_total,sales_invoice_item.tax_include,sales_invoice_item.item_total,itemmaster.other_info AS cod,units.unit_name,sales_invoice_item.id AS sii_id FROM sales_invoice JOIN account_master ON(account_master.id=sales_invoice.customer_id) LEFT JOIN terms ON(terms.id=sales_invoice.terms_id) LEFT JOIN salesman ON(salesman.id=sales_invoice.salesman_id) JOIN sales_invoice_item ON(sales_invoice_item.sales_invoice_id=sales_invoice.id) JOIN itemmaster ON(itemmaster.id=sales_invoice_item.item_id) JOIN units ON(units.id=sales_invoice_item.unit_id) LEFT JOIN groupcat ON(groupcat.id=itemmaster.group_id) LEFT JOIN groupcat AS GC ON(GC.id=itemmaster.subgroup_id)  LEFT JOIN category AS C ON(C.id=itemmaster.category_id)  LEFT JOIN category AS SC ON(SC.id=itemmaster.subcategory_id) WHERE sales_invoice_item.status=1 AND sales_invoice_item.deleted_at='0000-00-00 00:00:00' AND sales_invoice.id={id}  ORDER BY sales_invoice_item.id ASC