<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Department\DepartmentInterface;

use App\Http\Requests;
use Session;
use Response;
use App;

class DepartmentController extends Controller
{
    protected $department;
	
	public function __construct(DepartmentInterface $department) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->department = $department;
		$this->middleware('auth');
		
	}
	
	public function index() {
		$data = array();
		$departments = $this->department->departmentList();
		return view('body.department.index')
					->withDepartments($departments)
					->withData($data);
	}
	
	public function add() {

		$data = array();
		return view('body.department.add')
					->withData($data);
	}
	
	public function save(Request $request) {
		$this->department->create($request->all());
		Session::flash('message', 'Department added successfully.');
		return redirect('department/add');
	}
	
	public function edit($id) { 

		$data = array();
		$departmentrow = $this->department->find($id);
		return view('body.department.edit')
					->withDepartmentrow($departmentrow)
					->withData($data);
	}
	
	public function update($id, Request $request)
	{
		$this->department->update($id, $request->all());//print_r($request->all());exit;
		//Session::flash('message', 'Category updated successfully');
		return redirect('department');
	}
	
	public function destroy($id)
	{
		$this->department->delete($id);
		//check department name is already in use.........
		// code here ********************************
		Session::flash('message', 'Department deleted successfully.');
		return redirect('department');
	}
	
	public function checkcode(Request $request) {

		$check = $this->department->check_department_code($request->get('code'), $request->get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
	
	public function checkname(Request $request) {

		$check = $this->department->check_department_name($request->get('name'), $request->get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
}

