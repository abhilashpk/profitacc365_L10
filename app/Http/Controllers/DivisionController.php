<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Division\DivisionInterface;

use App\Http\Requests;
use Input;
use Session;
use Response;
use App;

class DivisionController extends Controller
{
    protected $division;
	
	public function __construct(DivisionInterface $division) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->division = $division;
		$this->middleware('auth');
		
	}
	
	public function index() {
		$data = array();
		$division = $this->division->divisionList();
		return view('body.division.index')
					->withDivision($division)
					->withData($data);
	}
	
	public function add() {

		$data = array();
		return view('body.division.add')
					->withData($data);
	}
	
	public function save() {
		$this->division->create(Input::all());
		Session::flash('message', 'division added successfully.');
		return redirect('division/add');
	}
	
	public function edit($id) { 

		$data = array();
		$divisionrow = $this->division->find($id);
		return view('body.division.edit')
					->withDivisionrow($divisionrow)
					->withData($data);
	}
	
	public function update($id)
	{
		$this->division->update($id, Input::all());//print_r(Input::all());exit;
		//Session::flash('message', 'Category updated successfully');
		return redirect('division');
	}
	
	public function destroy($id)
	{
		$this->division->delete($id);
		//check division name is already in use.........
		// code here ********************************
		Session::flash('message', 'division deleted successfully.');
		return redirect('division');
	}
	
	public function checkcode() {

		$check = $this->division->check_division_code(Input::get('div_code'), Input::get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
	
	public function checkname() {

		$check = $this->division->check_division_name(Input::get('div_name'), Input::get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
}

