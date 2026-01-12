<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Salesman\SalesmanInterface;

use App\Http\Requests;
use Input;
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
	
	public function save() {
		$this->salesman->create(Input::all());
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
	
	public function update($id)
	{
		$this->salesman->update($id, Input::all());//print_r(Input::all());exit;
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
	
	public function checkid() {

		$check = $this->salesman->check_salesman_id(Input::get('salesman_id'), Input::get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
	
	public function checkname() {

		$check = $this->salesman->check_name(Input::get('name'), Input::get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
	public function ajaxSave() {
		
		$as = $this->salesman->ajaxCreate(Input::all());
		return $as;
			
	}
	

}
