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

class PaySlipController extends Controller
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
		$employees = null;						
		return view('body.payslip.index')
					->withEmployees($employees)
					->withMonth('')
					->withYear('')
					->withData($data);
	}
	
	public function add() {

		$data = array();
		$parameter4 = $this->parameter4->getParameter4();
		
		return view('body.payslip.add')
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
		//echo '<pre>';print_r($employee);exit;				
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
	
	public function searchEmp()
	{
		$data = array();
		$employees = $this->employee->getEmployees();
		return view('body.payslip.index')
					->withEmployees($employees)
					->withMonth(Input::get('month'))
					->withYear(Input::get('year'))
					->withData($data);
	}
	
	public function employeeSlip($id,$month,$year)
	{
		$data = array();
		$voucher_head = 'Pay Slip';
		$titles = ['main_head' => 'Pay Slip','subhead' => 'Pay Slip'];
		$employee = $this->employee->find($id);
		$emprise=DB::table('employee_payrise')->where('id',$id)->select('update_date','basicpay_old')->orderBy('id','DESC')->first();
		$results = $this->wageentry->getPayslipMonth($id,$month,$year);  //echo '<pre>';print_r($results);exit;
		$timestamp = strtotime($year.'-'.$month.'-'.'30');
		$d = date('Y-m-d', $timestamp);
		if($emprise !='' && $d < $emprise->update_date){
		    $basic_salary=$emprise->basicpay_old;
		}
		else{
		    $basic_salary=$employee->basic_pay;
		}
		//echo '<pre>';print_r($emprise);exit;
		return view('body.payslip.print')
					->withEmployee($employee)
					->withMonth($month) //Input::get('month')
					->withType('pay_slip')
					->withBasicpay($basic_salary)
					->withResult($results)
					->withTitles($titles)
					->withUrl('')
					->withData($data);
	}
}

