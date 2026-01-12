<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Employee\EmployeeInterface;
use App\Repositories\Parameter4\Parameter4Interface;
use App\Repositories\WageEntry\WageEntryInterface;

use App\Http\Requests;
use Input;
use Session;
use Response;
use App;
use DB;

class WageEntryController extends Controller
{
    protected $employee;
	protected $parameter4;
	protected $wageentry;
	
	public function __construct(EmployeeInterface $employee, Parameter4Interface $parameter4, WageEntryInterface $wageentry) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->employee = $employee;
		$this->parameter4 = $parameter4;
		$this->wageentry = $wageentry;
		$this->middleware('auth');
	}
	
	public function index() {
		$data = array();
		$result = DB::table('wage_entry')
						->join('employee', 'employee.id', '=', 'wage_entry.employee_id')
						->where('wage_entry.status', 1)
						->where('wage_entry.deleted_at','0000-00-00 00:00:00')
						->select('wage_entry.id','wage_entry.month','wage_entry.net_total','employee.name','employee.designation')
						->get();
						
		return view('body.wageentry.index')
					->withWageentrys($result)
					->withData($data);
	}
	
	public function add() {

		$data = array();
		$parameter4 = $this->parameter4->getParameter4();
		
		return view('body.wageentry.add')
					->withSettings($this->acsettings)
					->withParameter($parameter4)
					->withData($data);
	}
	
	public function save(Request $request) {
		$this->wageentry->create($request->all());
		Session::flash('message', 'Wage entry added successfully.');
		return redirect('wage_entry');
		
	}
	
	public function edit($id) { 

		$data = array();
		$parameter4 = $this->parameter4->getParameter4();
		$wage_entry = $this->wageentry->get_wage_entry($id);	
		$wage_entry_items = $this->wageentry->get_wage_entry_items($id);	
		$employee = $this->employee->find($wage_entry->employee_id);
		//echo '<pre>';print_r($wage_entry_items);exit;				
		return view('body.wageentry.edit')
					->withWagerow($wage_entry)
					->withItems($wage_entry_items)
					->withSettings($this->acsettings)
					->withParameter($parameter4)
					->withEmployee($employee)
					->withData($data);
	}
	
	public function update($id)
	{
		$this->wageentry->update($id,Input::all());//print_r(Input::all());exit;
		Session::flash('message', 'Entry updated successfully');
		return redirect('wage_entry');
	}
	
	public function destroy($id)
	{
		DB::table('wage_entry')->where('id',$id)->delete();
		DB::table('wage_entry_items')->where('wage_entry_id',$id)->delete();
		
		Session::flash('message', 'Employee deleted successfully.');
		return redirect('wage_entry');
	}
	
	public function checkcode() {

		$check = $this->employee->check_employee_code(Input::get('code'), Input::get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
	
	public function checkname() {

		$check = $this->employee->check_employee_name(Input::get('name'), Input::get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
}

