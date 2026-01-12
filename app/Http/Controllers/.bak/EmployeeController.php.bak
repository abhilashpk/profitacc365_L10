<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Employee\EmployeeInterface;
use App\Repositories\Forms\FormsInterface;
use App\Repositories\Parameter4\Parameter4Interface;

use App\Http\Requests;
use Input;
use Session;
use Response;
use DB;
use App;

class EmployeeController extends Controller
{
    protected $employee;
	protected $parameter4;
	protected $forms;
	protected $formData;
	
	public function __construct(EmployeeInterface $employee, Parameter4Interface $parameter4,FormsInterface $forms) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->employee = $employee;
		$this->parameter4 = $parameter4;
		$this->forms = $forms;
		$this->formData = $this->forms->getFormData('EM');
		$this->middleware('auth');
		$this->mod_timesheet = DB::table('parameter2')->where('keyname', 'mod_timesheet')->where('status',1)->select('is_active')->first();
		
	}
	
	public function index() {
		$data = array(); 
		$employees = [];//$this->employee->employeeList();
		
		return view('body.employee.index')
					->withEmployees($employees)
					->withData($data);
	}
	
	public function ajaxPaging(Request $request)
	{
		$columns = array( 
                            0 =>'employee.id', 
                            1 =>'code',
                            2=> 'name',
                            3=> 'designation',
                            4=> 'nationality',
							5=> 'phone'
                        );
						
		$totalData = $this->employee->employeeListCount();
            
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = 'employee.id';
        $dir = 'desc';
		$search = (empty($request->input('search.value')))?null:$request->input('search.value');
        
		$invoices = $this->employee->employeeList('get', $start, $limit, $order, $dir, $search);
		
		if($search)
			$totalFiltered =  $this->employee->employeeList('count', $start, $limit, $order, $dir, $search);
		
        $data = array();
        if(!empty($invoices))
        {
			foreach ($invoices as $row)
            {
				$view =  '"'.url('employee/view/'.$row->id).'"';
				$edit =  '"'.url('employee/edit/'.$row->id).'"';
				$payrise =  '"'.url('employee/payrise/'.$row->id).'"';
                $delete =  'funDelete("'.$row->id.'")';
				
                $nestedData['id'] = $row->id;
                $nestedData['code'] = $row->code;
				$nestedData['name'] = $row->name;
				$nestedData['designation'] = $row->designation;
				$nestedData['nationality'] = $row->nationality;
				$nestedData['phone'] = $row->phone;
				
				$nestedData['view'] = "<p><button class='btn btn-primary btn-xs' onClick='location.href={$view}'><span class='glyphicon glyphicon-eye-open'></span></button></p>";
                $nestedData['edit'] = "<p><button class='btn btn-primary btn-xs' onClick='location.href={$edit}'><span class='glyphicon glyphicon-pencil'></span></button></p>";
                $nestedData['payrise'] = "<p><button class='btn btn-primary btn-xs' onClick='location.href={$payrise}'><span class='glyphicon glyphicon-eject'></span></button></p>";
				$nestedData['delete'] = "<button class='btn btn-danger btn-xs delete' onClick='{$delete}'><span class='glyphicon glyphicon-trash'></span>";
				
                $data[] = $nestedData;
            }
        }
          
        $json_data = array(
                    "draw"            => intval($request->input('draw')),  
                    "recordsTotal"    => intval($totalData),  
                    "recordsFiltered" => intval($totalFiltered), 
                    "data"            => $data   
                    );
            
        echo json_encode($json_data);
	}
	
	public function add() {

		$data = array();

		//DEPT CHECK...
		if(Session::get('department')==1) {
			$departments = DB::table('department')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
			$is_dept = true;
		} else {
			$departments = []; $is_dept = false;
		}

		$divisions = DB::table('division')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','div_name')->get();
		 $category = DB::table('employee_category')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','category_name')->get();
			
		//echo '<pre>';print_r($divisions);exit;
		return view('body.employee.add')
					->withData($data)
					->withDepartments($departments)
					->withFormdata($this->formData)
					->withCategory($category)
					->withDivisions($divisions);
	}
	
	public function save() {
	//echo '<pre>';print_r(Input::all());exit;
		//try { //echo '<pre>';print_r(Input::all());//exit;
			$this->employee->create(Input::all());
			Session::flash('message', 'Employee added successfully.');
			return redirect('employee');
		/* } catch(ValidationException $e) { 
			return Redirect::to('employee/add')->withErrors($e->getErrors());
		} */
	}
	
	public function edit($id) { 

		$data = $arrPtotos = array();
		$employeerow = $this->employee->find($id); 
		$photos = $this->makeOrder(DB::table('emp_photos')->where('employee_id',$id)->get()); 
		foreach($photos as $key => $rows) {
			$val = '';
			foreach($rows as $row) {
				$val .= ($val=='')?$row->photo:','.$row->photo;
				$arrPtotos[$key] = $val;
			}
		}
		$departments = DB::table('department')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
         $category = DB::table('employee_category')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','category_name')->get();

		$divisions = DB::table('division')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','div_name')->get();
		//echo '<pre>';print_r($arrPtotos);exit;
		return view('body.employee.edit')
					->withRow($employeerow)
					->withPhotos($arrPtotos)
					->withDepartments($departments)
					->withFormdata($this->formData)
					->withDivisions($divisions)
					->withCategory($category)
					->withData($data);
	}
	
	public function update($id)
	{
		//echo '<pre>';print_r(Input::all());exit;
		$this->employee->update($id, Input::all());//print_r(Input::all());exit;
		Session::flash('message', 'Employee updated successfully');
		return redirect('employee');
	}
	
	public function destroy($id)
	{
	    	$check = DB::table('wage_entry')->where('employee_id',$id)->where('deleted_at','0000-00-00 00:00:00')->count();
	    	//echo '<pre>';print_r($check);exit;
	    	if($check==0){
		$this->employee->delete($id);
		//check employee name is already in use.........
		// code here ********************************
		Session::flash('message', 'Employee deleted successfully.');
	    	}
	    	else{
	    	Session::flash('message', 'Employee cant delete as it has wage entry.');    
	    	}
		return redirect('employee');
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
	
	public function payrise($id)
	{
	    
	    //echo '<pre>';print_r($id);exit;
		$data = array();
		$employee = $this->employee->find($id);
		//echo '<pre>';print_r($employee);exit;
		return view('body.employee.payrise')
					->withErow($employee)
					->withData($data);
	}
	
		public function payriseUpdate(Request $request) {
		    
		   //echo '<pre>';print_r($request->all());exit; 
		   $eid=$request->input('employee_id');
		    DB::table('employee')->where('employee.id',$eid)
		                         ->update([
		                                    	'basic_pay' => $request->input('basicpay_new'),
		                                    	'hra' => $request->input('hra_new'),
		                                    	'transport' => $request->input('transport_new'),
		                                    	'allowance' => $request->input('allowance1_new'),
		                                    	'allowance2' => $request->input('allowance2_new'),
		                                         'net_salary' => $request->input('netsalary_new'),
		                                  ]);
		   
		   DB::table('employee_payrise')->insert([
								'employee_id' => $request->input('employee_id'),
								'basicpay_old' =>$request->input('basicpay_old'),
								'hra_old' =>$request->input('hra_old'),
								'transport_old' =>$request->input('transport_old'),
								'allowance_old' =>$request->input('allowance1_old'),
								'allowance2_old' =>$request->input('allowance2_old'),
								'netsalary_old' =>$request->input('netsalary_old'),
								'basicpay_new' =>$request->input('basicpay_new'),
								'hra_new' =>$request->input('hra_new'),
								'transport_new' =>$request->input('transport_new'),
								'allowance_new' =>$request->input('allowance1_new'),
								'allowance2_new' =>$request->input('allowance2_new'),
								'netsalary_new' =>$request->input('netsalary_new'),
								'remarks' =>$request->input('remarks'),
								'update_date' => date('Y-m-d', strtotime($request->input('update_date')))
								]);
		   Session::flash('message', 'Employee Salary updated successfully');
		    return redirect('employee');
		}
	
	
	public function getEmployeedata()
	{
		$data = array();
		$employees = $this->employee->getEmployees();//print_r($customers);exit;
		return view('body.employee.employeelist')
					->withEmployees($employees)
					->withData($data);
	}
	
	public function getEmployee($id,$type,$year,$month)
	{
		$data = array();
		$employee = $this->employee->find($id);
		$parameter4 = $this->parameter4->getParameter4();//echo '<pre>';print_r($employee);exit;
		$job = DB::table('jobmaster')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->where('is_salary_job',1)->first();
		$count = DB::table('wage_entry')->where('month', $month)->where('year',$year)->where('employee_id',$id)->count();
		$timesheet=DB::table('timesheet_entry AS TSE')->leftjoin('jobmaster AS JM','JM.id','=','TSE.job_id')
		                                  ->where('TSE.month', $month)->where('TSE.employee_id',$id)->where('TSE.is_approved',1)
										  ->select('TSE.*','JM.name AS job_name')
		                                 ->get();
		//echo '<pre>';print_r($parameter4 );exit;
		if($this->mod_timesheet->is_active==1){
			$emp='employeets';
		}
		else{
			$emp='employee';
		}
		$view = ($type=='monthly')?'employeeps':$emp;
		return view('body.employee.'.$view)
					->withEmployee($employee)
					->withTimesheet($timesheet)
					->withParameter($parameter4) 
					->withType($type)
					->withYear($year)
					->withMonth($month)
					->withJob($job)
					->withCount($count)
					->withData($data);
	}
	
	public function getEmpData($id) {
		
		$row = $this->employee->find($id);
		$net_total = $row->basic_pay + $row->hra + $row->transport + $row->allowance+$row->ot_general+$row->ot_holiday;
		return $result = array('salary' => $row->contract_salary,
								'basic' => $row->basic_pay, 
								'hra' => $row->hra, 
								'transport' => $row->transport,
								'allowance' => $row->allowance,
								'otg'=>$row->ot_general,
								'oth'=>$row->ot_holiday,
								'net_total' => $net_total );
	}

	
	public function ajaxSave(Request $request) {
		//echo '<pre>';print_r($request->all());
		$id = $this->employee->ajax_create($request->all());
		echo $id;
	}
	
	public function uploadSubmit(Request $request)
	{	
		$res = $this->employee->ajax_upload($request->photos);
		return response()->json(array('file_name' => $res), 200);
	}
	
	public function puploadSubmit(Request $request)
	{
		$res = $this->employee->ajax_pupload($request->pimage);
		return response()->json(array('file_name' => $res), 200);
	}
	
	public function vuploadSubmit(Request $request)
	{
		$res = $this->employee->ajax_vupload($request->vimage);
		return response()->json(array('file_name' => $res), 200);
	}
	
	public function luploadSubmit(Request $request)
	{
		$res = $this->employee->ajax_lupload($request->limage);
		return response()->json(array('file_name' => $res), 200);
	}
	
	public function huploadSubmit(Request $request)
	{
		$res = $this->employee->ajax_hupload($request->himage);
		return response()->json(array('file_name' => $res), 200);
	}
	
	public function iuploadSubmit(Request $request)
	{
		$res = $this->employee->ajax_iupload($request->iimage);
		return response()->json(array('file_name' => $res), 200);
	}
	
	public function meuploadSubmit(Request $request)
	{
		$res = $this->employee->ajax_meupload($request->meimage);
		return response()->json(array('file_name' => $res), 200);
	}
	public function fuploadSubmit(Request $request)
	{
		$res = $this->employee->ajax_fupload($request->fimage);
		return response()->json(array('file_name' => $res), 200);
	}
	private function getDocType($type) {
		
		switch($type)
		{
			case 1:
			return 'Passport';
			break;
			
			case 2:
			return 'Visa';
			break;
			
			case 3:
			return 'Labour Card';
			break;
			
			case 4:
			return 'Health Card';
			break;
			
			case 5:
			return 'ID Card';
			break;
			
			case 6:
			return 'Medical Exam';
			break;
			
		}
	}
	
	private function calculateDays($date) {
		
		$now = time();
		$your_date = strtotime($date);
		$datediff = $your_date - $now;

		return round($datediff / (60 * 60 * 24));

	}
	
	private function sortDocs($result) {
		
		$docarr = [];
		foreach($result as $row) {
			$doc_type = $this->getDocType($row->doc_type);
			$days = $this->calculateDays($row->expiry_date);
			
			$docarr[] = ['code' => $row->code, 'name' => $row->name,'doc' => $doc_type,'expiry_date' => $row->expiry_date, 'days' => $days];
		}
		
		return $docarr;
	}
	
	public function getExpinfo() {
		$data = [];
		$result = $this->sortDocs($this->employee->getExpiryInfo()); //echo '<pre>';print_r($result);exit;
		return view('body.employee.expdoc')
					->withDocs($result)
					->withData($data);
	}
	
	
	protected function makeOrder($result)
	{
		$childs = array();
		foreach($result as $item)
			$childs[$item->type][] = $item;

		return $childs;
	}
	
	public function show($id) { 

		$data = array();
		$employeerow = $this->employee->find($id);
		$photos = $this->makeOrder(DB::table('emp_photos')->where('employee_id',$id)->get()); //echo '<pre>';print_r($photos);exit;
		//$is_rejoin = DB::table('onleave')->where('employee_id',$id)->where('leave_status',1)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->count();
		//$is_undo = DB::table('onleave')->where('employee_id',$id)->where('leave_status',0)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->count();
		//echo '<pre>';print_r($employeerow);exit;

	$departments = DB::table('department')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();

		$divisions = DB::table('division')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','div_name')->get();

		return view('body.employee.view')
					->withMasterrow($employeerow)
					->withPhotos($photos)
					//->withIsrejoin($is_rejoin)
					//->withIsundo($is_undo)
					->withDepartments($departments)
					->withDivisions($divisions)
					->withData($data);
	}
	
	public function leave($id) { 

		$data = array();
		$employeerow = $this->employee->find($id);
		return view('body.employee.leave')
					->withMasterrow($employeerow)
					->withId($id)
					->withData($data);
	}
	
	public function saveLeave() { 

		if( $this->employee->saveLeave(Input::all()) )
			Session::flash('message', 'Leave process submitted successfully.');
		else
			Session::flash('error', 'Something went wrong, Account failed to submit!');
		
		return redirect('employee/leave/'.Input::get('id'));
	}
	
	public function rejoin($id) { 

		$data = array();
		$employeerow = DB::table('onleave')
							->join('employee AS E', function($join) {
								$join->on('E.id','=','onleave.employee_id');
							})
							->where('onleave.employee_id',$id)
							->where('onleave.leave_status',1)
							->select('E.code','E.name','onleave.start_date','onleave.id')
							->first(); 
							
		return view('body.employee.rejoin')
					->withMasterrow($employeerow)
					->withId($id)
					->withData($data);
	}
	
	public function saveRejoin() { 

		$this->employee->saveRejoin(Input::all());
		Session::flash('message', 'Rejoin details submitted successfully.');
		
		return redirect('employee/view/'.Input::get('employee_id'));
	}
	
	public function resign($id) { 

		$data = array();
		$employeerow = $this->employee->find($id);
		return view('body.employee.resign')
					->withMasterrow($employeerow)
					->withId($id)
					->withData($data);
	}
	
	public function saveResign() { 

		$this->employee->saveResign(Input::all());
		Session::flash('message', 'Termination/Resignation details submitted successfully.');
		
		return redirect('employee/view/'.Input::get('id'));
	}
	
	public function rejoinUndo($id) { 

		$this->employee->undoRejoin($id);
		Session::flash('message', 'Rejoin undo successfully.');
		
		return redirect('employee/view/'.$id);
	}
}
