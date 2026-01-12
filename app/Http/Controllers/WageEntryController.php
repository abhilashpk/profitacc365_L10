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
use Auth;
use Config;
use File;
use Excel;

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
						->select('wage_entry.id','wage_entry.month','wage_entry.year','wage_entry.net_total','employee.name','employee.designation')
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
	
	
	public function timesheetadd($eid=null,$cid=null) {

		$data = array();
		$parameter4 = $this->parameter4->getParameter4();
		$category = DB::table('employee_category')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','category_name')->get();
		$emply = DB::table('employee')->where('employee.status', 1)
		           ->where('employee.deleted_at','0000-00-00 00:00:00')->select('employee.name','employee.id')->get();
		if($eid==0 && $cid==0)	{	   
		$employee = DB::table('employee')->where('employee.status', 1)
		           ->where('employee.deleted_at','0000-00-00 00:00:00')->select('employee.name','employee.id')->get();
		}
		else if($eid!=0 && $cid==0){
			$employee = DB::table('employee')->where('employee.status', 1)
		           ->where('employee.deleted_at','0000-00-00 00:00:00')
				    ->where('employee.id',$eid)->select('employee.name','employee.id')->get();
		}
		else if($eid==0 && $cid!=0){
			$employee = DB::table('employee')->where('employee.status', 1)
		           ->where('employee.deleted_at','0000-00-00 00:00:00')
				    ->where('employee.category_id',$cid)->select('employee.name','employee.id')->get();
		}
		else if($eid!=0 && $cid!=0){
			$employee = DB::table('employee')->where('employee.status', 1)
		           ->where('employee.deleted_at','0000-00-00 00:00:00')
				    ->where('employee.category_id',$cid)->where('employee.id',$eid)
					->select('employee.name','employee.id')->get();
		}
				   //echo '<pre>';print_r($employee);exit;
		return view('body.timesheet.add')
					->withSettings($this->acsettings)
					->withParameter($parameter4)
					->withCategory($category)
					->withEmployee($employee)
					->withEmply($emply)
					->withEid($eid)
					->withCid($cid)
					->withData($data);
	}
	
		public function timesheetSave(Request $request) {
		//echo '<pre>';print_r(Input::all());exit;
		DB::beginTransaction();
		try {
		$date=strtotime($request->get('date'));
		$month=date('m', $date);
	    $dt=date('Y-m-d', strtotime($request->get('date')));
		foreach($request->get('employee_id') ??[] as $key => $row) {
		       $id= $request['employee_id'][$key];
		    	$count = DB::table('timesheet_entry')->where('date', $dt)->where('month',$month)->where('employee_id',$id)->count();
		    	//echo '<pre>';print_r($count);exit;
		    if($count==0)	{
			DB::table('timesheet_entry')->insert([
				'date'=>date('Y-m-d', strtotime($request->get('date'))),
				'month'=>$month,
				'day_type'=>$request['day_type'], 
				 'employee_id'  =>$request['employee_id'][$key], 
				'start_time'=>$request['start_time'][$key],
				'end_time'=>$request['end_time'][$key], 
				'break_time'=>$request['break_time'][$key],  
				'twh' => $request['twh'][$key], 
				'nwh' => $request['nwh'][$key], 
				'otg' => $request['otg'][$key], 
				'oth' => $request['oth'][$key],
				'job_id'=>$request['job_id'][$key],
				'leave_type'=>$request['leaves'][$key],
				'subjob_id'=>$request['work_id'][$key],
				'created_at'=>date('Y-m-d H:i:s')
			]);
		    }
	}

	DB::commit();
		Session::flash('message', 'Time Sheetentry added successfully.');
		return redirect('wage_entry/timesheet');
	} catch(ValidationException $e) { 
		return Redirect::to('wage_entry/timesheet')->withErrors($e->getErrors());
	}
		
	}
	
	
	public function subJobTemplate($jid=null,$cum=null,$wid=null) {

		$data = array();

	
									   
		
			$subjob = DB::table('jobmaster')
			                             ->where('jobmaster.status', 1)
			                            ->where('jobmaster.is_salary_job',0)
										->select('jobmaster.id','jobmaster.name','jobmaster.code')->get();
										
			                  
										   
        //echo '<pre>';print_r($subjob);exit;
		return view('body.timesheet.subjobtemplate')
					->withNum($cum)
					->withWid($wid)
					->withJid($jid)
					->withSubjob($subjob)
					->withData($data);


	}

	public function subJobTemplateEdit($jid=null,$cum=null,$wid=null) {

		$data = array();
		$subjob = DB::table('jobmaster')->where('jobmaster.id',$jid)
		                             ->where('jobmaster.status', 1)
			                            ->where('jobmaster.is_salary_job',0)
										->select('jobmaster.id','jobmaster.name','jobmaster.code')->get();
		$subjobd = DB::table('timesheet_subjob')->where('timesheet_subjob.id',$wid)->first();
		$subjobdata = json_decode($subjobd->subjob_value);
		//echo '<pre>';print_r($subjobdata->subjobs); exit;
		return view('body.timesheet.subjobtempedit')
					->withNum($cum)
					->withWid($wid)
					->withJid($jid)
					->withSubjobdata($subjobdata->subjobs)
					->withSubjob($subjob)
					->withId($subjobd->id)
					->withData($data);


	}
	public function subJobTemplateSave(Request $request) {
		//echo '<pre>';print_r($request->all()); exit;

		foreach($request->get('subjob') ??[] as $key => $row) {
			
			$arrVal[] = ['subjob' => $row, 'workhr' => $request['workhr'][$key] ];
		}
		$val['subjobs']=$arrVal;
		//echo '<pre>';print_r($val); exit;
		if($request['wid']=='') {
		     $id = DB::table('timesheet_subjob')
						->insertGetId([
							'subjob_value' => json_encode($val)
						]);
    
		}else{
			DB::table('timesheet_subjob')
			->where('id', $request['wid'])
			->update([
				'subjob_value' => json_encode($val) //$attributes['form_tmplt']
			]);
            $id = $request['wid'];

		}				
		return $id;				
	}
	
	
	public function timesheetEdit($eid=null,$cid=null,$mon=null) {

		$data = array();
		$parameter4 = $this->parameter4->getParameter4();
		$category = DB::table('employee_category')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','category_name')->get();
		$emply = DB::table('employee')->where('employee.status', 1)
		           ->where('employee.deleted_at','0000-00-00 00:00:00')->select('employee.name','employee.id')->get();
	if($eid==0 && $cid==0 && $mon==0)	{
		$timesheet=[];
	}
	else{
	   $timesheet = $this->wageentry->timesheetEntry($eid,$cid,$mon);
         //echo '<pre>';print_r($timesheet);exit;
	}
       return view('body.timesheet.edit')
		
		->withParameter($parameter4)
		->withCategory($category)
		->withTimesheet($timesheet)
		->withEmply($emply)
		->withEid($eid)
		->withCid($cid)
		->withMonth($mon)
		->withData($data);				
	}

	public function timesheetUpdate(Request $request) {
		//echo '<pre>';print_r(Input::all());exit;

		foreach($request->get('id') ??[] as $key => $row) {
		$date=strtotime($request['date'][$key]);
		$month=date('m', $date);
		//echo '<pre>';print_r($month);exit;
			DB::table('timesheet_entry')->where('timesheet_entry.id',$request['id'][$key])
			    ->update([
				'date'=>date('Y-m-d', strtotime($request['date'][$key])),
				'month'=>$month,
				'day_type'=>$request['day_type'][$key], 
				 'employee_id'  =>$request['employee_id'][$key], 
				'start_time'=>$request['start_time'][$key],
				'end_time'=>$request['end_time'][$key], 
				'break_time'=>$request['break_time'][$key],  
				'twh' => $request['twh'][$key], 
				'nwh' => $request['nwh'][$key], 
				'otg' => $request['otg'][$key], 
				'oth' => $request['oth'][$key],
				'job_id'=>$request['job_id'][$key],
				'leave_type'=>$request['leaves'][$key],
				'subjob_id'=>$request['work_id'][$key],
				'modified_at'=>date('Y-m-d H:i:s')
			]);

		}
		Session::flash('message', 'Time Sheet Entry updated successfully.');
		return redirect('wage_entry/timesheet/edit');

	}	
	
		public function timesheetView($eid=null,$cid=null,$mon=null) {

		$data = array();
		$parameter4 = $this->parameter4->getParameter4();
		$category = DB::table('employee_category')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','category_name')->get();
		$emply = DB::table('employee')->where('employee.status', 1)
		           ->where('employee.deleted_at','0000-00-00 00:00:00')->select('employee.name','employee.id')->get();
	if($eid==0 && $cid==0 && $mon==0)	{
		$timesheet=[];
	}
	else{
	   $timesheet = $this->wageentry->timesheetSearch($eid,$cid,$mon);
         //echo '<pre>';print_r($timesheet);exit;
	}
       return view('body.timesheet.view')
		
		->withParameter($parameter4)
		->withCategory($category)
		->withTimesheet($timesheet)
		->withEmply($emply)
		->withEid($eid)
		->withCid($cid)
		->withMonth($mon)
		->withData($data);				
	}
	
	
	public function subJobTemplateView($cum=null,$wid=null,$jid=null) {
        $data = array();
		$subjob = DB::table('timesheet_subjob')->where('timesheet_subjob.id',$wid)->first();
		
		$subjobdata = json_decode($subjob->subjob_value);

		$subjobs = DB::table('jobmaster')->where('jobmaster.id',$jid)
		                            ->where('jobmaster.status', 1)
			                            ->where('jobmaster.is_salary_job',0)
										->select('jobmaster.id','jobmaster.name','jobmaster.code')->get();

		//echo '<pre>';print_r($subjobdata->subjobs); exit;
		return view('body.timesheet.subjobtempview')
					->withNum($cum)
					->withWid($wid)
					->withSubjobdata($subjobdata->subjobs)
					->withId($subjob->id)
					->withSubjobs($subjobs)
					->withData($data);
	}

    public function timesheetApprove(Request $request) {
		//echo '<pre>';print_r(Input::all());exit;

		foreach($request->get('id') ??[] as $key => $row) {
			DB::table('timesheet_entry')->where('timesheet_entry.id',$request['id'][$key])
			                        ->update(['is_approved' => 1  ]);

		}
		Session::flash('message', 'Time Sheet Entry approved successfully.');
		return redirect('wage_entry/timesheet/view');

	}	
	
	public function timesheetLeave() {
		$data = array();
		$employees = [];
		$category = DB::table('employee_category')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','category_name')->get();
		$emply = DB::table('employee')->where('employee.status', 1)
		           ->where('employee.deleted_at','0000-00-00 00:00:00')->select('employee.name','employee.id')->get();						
		return view('body.timesheet.leave')
					->withEmployees($employees)
					->withCategory($category)
		            ->withEmply($emply)
					->withMonth('')
					->withEid('')
					->withCid('')
					->withData($data);
	}
	
	public function timesheetLeaveSearch() {
		//echo '<pre>';print_r(Input::all());exit;
		$category = DB::table('employee_category')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','category_name')->get();
		$emply = DB::table('employee')->where('employee.status', 1)
		           ->where('employee.deleted_at','0000-00-00 00:00:00')->select('employee.name','employee.id')->get();	
		$month=Input::get('month');
		$eid=Input::get('emply_id');
		$cid=Input::get('category_id');
		$data = array();
		$employees = $this->wageentry->timeLeaveSearch($eid,$cid,$month);
					  //echo '<pre>';print_r($employees);exit;
		return view('body.timesheet.leave')
					->withEmployees($employees)
					->withCategory($category)
		            ->withEmply($emply)
					->withMonth($month)
					->withEid($eid)
					->withCid($cid)
					->withData($data);
	}
	
	public function timesheetLeaveEdit($id) {
		$data = array();
        $leave=DB::table('timesheet_entry AS TSE')->where('TSE.id',$id)
		                                    ->join('employee AS E','E.id','=','TSE.employee_id')
		                                     ->leftjoin('employee_category AS EC','EC.id','=','E.category_id')
											 ->select('TSE.id','TSE.date','TSE.leave_status','TSE.leave_reason','E.code','E.name','E.designation','EC.category_name')->first();
											// echo '<pre>';print_r($leave);exit;
		$photos = DB::table('pl_photos')->where('timesheet_id',$id)->get();									
		    return view('body.timesheet.leaveedit')
					->withLeave($leave)
					->withPhotos($photos)
					->withUser(Auth::user()->roles[0]->name)
					->withData($data);

	}
	
	public function timesheetLeaveUpdate($id) {
		$attributes=Input::all();
		//echo '<pre>';print_r($attributes['leave_status']);exit;
		DB::table('timesheet_entry')->where('timesheet_entry.id',$id)
		->update(['leave_status' => $attributes['leave_status'],  
	               'leave_reason' => $attributes['leave_reason']
	
	              ]);
	              
	              
	              
	   if(isset($attributes['photo_id']) && $attributes['photo_id'] !='') {
					
					foreach($attributes['photo_id'] as $key => $val) {
						
						//UPDATE...
						if($val!='') {
							DB::table('pl_photos')
								->where('id', $val)
								->update(['photo' =>  $attributes['photo_name'][$key],
										  'description'	=> $attributes['imgdesc'][$key]
										]);
										
						} else { 
							//ADD NEW..
							DB::table('pl_photos')
								->insert(['timesheet_id' => $id, 
										  'photo' => $attributes['photo_name'][$key],
										  'description'	=> $attributes['imgdesc'][$key]
										]);
						}
					}
				}
				
					else{
					
					if(isset($attributes['photo_name']) && $attributes['photo_name']!='') {
								foreach($attributes['photo_name'] as $key => $val) {
									if($val!='') {
										DB::table('pl_photos')
												->insert(['timesheet_id' => $id, 
														  'photo' => $val,
														  'description'	=> $attributes['imgdesc'][$key]
														]);
									}
								}
							}
				}		
				
				//Remove
				if(isset($attributes['rem_photo_id']) && $attributes['rem_photo_id']!='') {
					$rem_photos = explode(',',$attributes['rem_photo_id']);
					foreach($rem_photos as $id) {
						$rec = DB::table('pl_photos')->find($id);
						DB::table('pl_photos')->where('id', $id)->delete();
						
						
					}
				}           
				  Session::flash('message', 'Leave Entry updated successfully.');
		                  return redirect('wage_entry/timesheet/leave');

	}

	public function uploadSubmit(Request $request)
	{	
		$res = $this->wageentry->ajax_upload($request->photos);
		return response()->json(array('file_name' => $res), 200);
	}
	
	public function timesheetLeaveApprove($id) {

		DB::table('timesheet_entry')->where('timesheet_entry.id',$id)
			                        ->update(['leave_approve' => 1  ]);
			Session::flash('message', 'Leave Approved successfully.');
			return redirect('wage_entry/timesheet/leave');					


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

