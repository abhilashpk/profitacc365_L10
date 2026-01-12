<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Salesman\SalesmanInterface;

use App\Http\Requests;
use Session;
use Response;
use DB;
use App;

class SalesmanController extends Controller
{
    protected $salesman;
	protected $is_workshop;
	
	public function __construct(SalesmanInterface $salesman) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->salesman = $salesman;
		$this->middleware('auth');
		$this->is_workshop = DB::table('parameter2')->where('keyname', 'mod_workshop')->where('status',1)->select('is_active')->first();
	}
	
	public function index() {
		$data = array(); 
		//echo $this->is_workshop->is_active;
		$salesmans = $this->salesman->salesmanList();
		//echo $this->salesman->salesmanList();exit;
		return view('body.salesman.index')
					->withSalesmans($salesmans)
					->withData($data);
	}
	
	public function add() {

		$data = array();
		return view('body.salesman.add')
					->withData($data);
	}
	
	public function save(Request $request) {
		$this->salesman->create($request->all());
		Session::flash('message', 'Salesman added successfully.');
		return redirect('salesman/add');
	}
	
	public function edit($id) { 

		$data = array();
		$salesmanrow = $this->salesman->find($id);
		return view('body.salesman.edit')
					->withSalesmanrow($salesmanrow)
					->withData($data);
	}
	
	public function update($id, Request $request)
	{
		$this->salesman->update($id, $request->all());//print_r($request->all());exit;
		//Session::flash('message', 'Category updated successfully');
		return redirect('salesman');
	}
	
	public function destroy($id)
	{
		$this->salesman->delete($id);
		//check salesman name is already in use.........
		// code here ********************************
		Session::flash('message', 'Salesman deleted successfully.');
		return redirect('salesman');
	}
	
	public function checkid(Request $request) {

		$check = $this->salesman->check_salesman_id($request->get('salesman_id'), $request->get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
	
	public function checkname(Request $request) {

		$check = $this->salesman->check_name($request->get('name'), $request->get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
	public function ajaxSave(Request $request) {
		
		$as = $this->salesman->ajaxCreate($request->all());
		return $as;
			
	}
	

}

