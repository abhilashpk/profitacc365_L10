<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Input;
use Session;
use Response;
use DB;
use Image;
use App;
use Auth;

class DocumentMasterController extends Controller
{
	
	public function __construct() {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->middleware('auth');
		
	}
	
	public function index() {
		$data = array(); 
		$docs = DB::table('document_master')->where('document_master.status',1)
					->leftJoin('division AS D', function($join) {
							$join->on('D.id','=','document_master.division_id');
						})
					->where('document_master.deleted_at','0000-00-00 00:00:00')
					->select('document_master.*','D.div_name')
					->get();
		//$docdept = DB::table('doc_department')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();

		$divisions = DB::table('division')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','div_name')->get();
			if(Session::get('department')==1) { //if active...
			$deptid = Auth::user()->department_id;
			if($deptid!=0)
				$departments = DB::table('department')->where('id',$deptid)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
			else {
				$departments = DB::table('department')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
				//$deptid = $departments[0]->id;
			}
			$is_dept = true;
		} else {
			$is_dept = false;
			$departments = [];
			//$deptid = '';
		}
		
		return view('body.documentmaster.index')
					->withDocs($docs)
					//->withDocdept($docdept)
					->withDepartments($departments)
					->withIsdept($is_dept)
					->withDivisions($divisions)
					->withData($data);
	}
	
	public function add() {

		$data = array();

		if(Session::get('department')==1) {
			$departments = DB::table('department')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
			$is_dept = true;
		} else {
			$departments = []; $is_dept = false;
		}

		$divisions = DB::table('division')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','div_name')->get();

		//$docdept = DB::table('doc_department')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		return view('body.documentmaster.add')
					//->withDocdept($docdept)
		            ->withDepartments($departments)
					->withDivisions($divisions)
					->withData($data);
	}
	
	public function save(Request $request) {
		try {
			//echo '<pre>';print_r(Input::all());exit;
			$image = ''; $width = 730; $height = 290;
			$file = ($request->hasFile('image'))?$request->file('image'):null; //echo '<pre>';print_r($file);exit;
			if($file) {
				$ext = $file->getClientOriginalExtension();
				if($ext=='.jpg' ||$ext=='.JPG' || $ext=='.png' || $ext=='.PNG') {
					$image = time().'.'.$ext;
					$destinationPath = public_path() . '/uploads/document/'.$image;
					$destinationPathThumb = public_path() . '/uploads/document/thumb_'.$image;

					// resizing an uploaded file
					Image::make($file->getRealPath())->resize($width, $height, function($constraint) { $constraint->aspectRatio(); })->save($destinationPath);

					// thumb
					Image::make($file->getRealPath())->resize(200, 125, function($constraint) { $constraint->aspectRatio(); })->save($destinationPathThumb);
				} else {
					 $image = time().'.'.$ext;
					 echo $destinationPath = public_path() . '/uploads/document/';
					 $file->move($destinationPath, $image);
				}
			}
			
		
			DB::table('document_master')
				->insert([
					'department_id' => Input::get('department_id'),
					'division_id' => Input::get('division_id'),
					'name' => Input::get('name'),
					'description' => Input::get('description'),
					'issue_date' => (Input::get('issue_date')!='')?date('Y-m-d', strtotime(Input::get('issue_date'))):'',
					'expiry_date' => (Input::get('expiry_date')!='')?date('Y-m-d', strtotime(Input::get('expiry_date'))):'',
					'image' => $image,
					'status' => 1,
					'code' => Input::get('code'),
					'amount' => Input::get('amount'),
					'department_id' => (Input::get('department_id')!='')?Input::get('department_id'):''
				]);
			Session::flash('message', 'Document added successfully.'); 
			return redirect('document_master');
		} catch(ValidationException $e) { //echo $e->getLine().' '.$e->getMessage();exit;
			return Redirect::to('document_master/add')->withErrors($e->getErrors());
		}
	}
	
	public function edit($id) { 

		$data = array();
		$docrow = DB::table('document_master')->where('id',$id)->first();
		//$docdept = DB::table('doc_department')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		if(Session::get('department')==1) {
			$departments = DB::table('department')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
			$is_dept = true;
		} else {
			$departments = []; $is_dept = false;
		}

		$divisions = DB::table('division')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','div_name')->get();
		return view('body.documentmaster.edit')
					->withDocrow($docrow)
					//->withDocdept($docdept)
					->withDepartments($departments)
					->withDivisions($divisions)
					->withData($data);
	}
	
	public function update(Request $request, $id)
	{
		try {
			//echo '<pre>';print_r(Input::all());exit;
			$image = Input::get('current_image'); $width = 730; $height = 290;
			$file = ($request->hasFile('image'))?$request->file('image'):null; //echo '<pre>';print_r($file);exit;
			if($file) {
				$ext = $file->getClientOriginalExtension();
				if($ext=='.jpg' ||$ext=='.JPG' || $ext=='.png' || $ext=='.PNG') {
					$image = time().'.'.$ext;
					$destinationPath = public_path() . '/uploads/document/'.$image;
					$destinationPathThumb = public_path() . '/uploads/document/thumb_'.$image;

					// resizing an uploaded file
					Image::make($file->getRealPath())->resize($width, $height, function($constraint) { $constraint->aspectRatio(); })->save($destinationPath);

					// thumb
					Image::make($file->getRealPath())->resize(200, 125, function($constraint) { $constraint->aspectRatio(); })->save($destinationPathThumb);
				} else {
					 $image = time().'.'.$ext;
					 echo $destinationPath = public_path() . '/uploads/document/';
					 $file->move($destinationPath, $image);
				}
			}
			
		
			DB::table('document_master')->where('id',$id)
				->update([
					'name' => Input::get('name'),
					'description' => Input::get('description'),
					'issue_date' => (Input::get('issue_date')!='')?date('Y-m-d', strtotime(Input::get('issue_date'))):'',
					'expiry_date' => (Input::get('expiry_date')!='')?date('Y-m-d', strtotime(Input::get('expiry_date'))):'',
					'image' => $image,
					'code' => Input::get('code'),
					'amount' => Input::get('amount'),
					'department_id' => Input::get('department_id'),
					'division_id' => Input::get('division_id')
				]);
			Session::flash('message', 'Document updated successfully.'); 
			return redirect('document_master');
			
		} catch(ValidationException $e) { 
			return Redirect::to('document_master/add')->withErrors($e->getErrors());
		}
	}
	
	public function destroy($id)
	{
		if($id) {
			DB::table('document_master')->where('id',$id)->update(['status' => 0, 'deleted_at' => date('Y-m-d H:i:s')]);
		}
		Session::flash('message', 'Document deleted successfully.');
		return redirect('document_master');
	}
	
	
	public function checkname() {

		$check = DB::table('document_master')->where('name',Input::get('name'))->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->count();
		$isAvailable = ($check==0) ? true : false;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
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
			
			$days = $this->calculateDays($row->expiry_date);
			
			$docarr[] = ['name' => $row->name,'expiry_date' => $row->expiry_date, 'days' => $days];
		}
		
		return $docarr;
	}
	
	public function getExpinfo() {
		$data = [];
		
		$parameter1 = DB::table('parameter1')->first();
		$doc_warndays = $parameter1->doc_warndays;
		$fromdate = date('Y-m-d');
		$todate = date('Y-m-d', strtotime("+".$doc_warndays." days"));
		
		$result = $this->sortDocs( DB::table('document_master')
								->where('status',1)
								->where('deleted_at','0000-00-00 00:00:00')
								->whereBetween('expiry_date', array($fromdate, $todate))
								->get() );
								
		return view('body.documentmaster.expdoc')
					->withDocs($result)
					->withData($data);
	}
	
	public function checkcode() {

		$check = DB::table('document_master')->where('code',Input::get('code'))->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->count();
		$isAvailable = ($check==0) ? true : false;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
	
	private function getReport($attributes)
	{
		$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
		$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';
		
					$query = DB::table('document_master')
							->join('division AS D', function($join) {
								$join->on('D.id','=','document_master.division_id');
							})
							->where('document_master.status',1);

							DB::table('document_master')
							->join('department AS Dept', function($join) {
								$join->on('Dept.id','=','document_master.department_id');
							})
							->where('document_master.status',1);
								
						if( $date_from!='' && $date_to!='' ) { 
							$query->whereBetween('document_master.expiry_date', array($date_from, $date_to));
						}
						
						if($attributes['division_id']!='') { 
							$query->where('document_master.division_id', $attributes['division_id']);
						}
						if(isset($attributes['department_id']) && $attributes['department_id']!='') { 
							$query->where('document_master.department_id', $attributes['department_id']);
						}
						
			return $query->select('document_master.*','D.div_name')
						->orderBy('document_master.id')->get();
			
	}
	
	public function getSearch()
	{
		$data = array();
		
		$reports = $this->getReport(Input::all());
		//echo '<pre>';print_r($reports);exit;
		
		if(Input::get('division_id')!='') {
			$voucher_head = 'Division Report - Division wise';
		} else {
			$voucher_head = 'Division Report';
		}
		if(Input::get('department_id')!='') {
			$voucher_head = 'Department Report - Department wise';
		} 
		
		return view('body.documentmaster.report')
					->withReports($reports)
					->withVoucherhead($voucher_head)
					->withFromdate(Input::get('date_from'))
					->withTodate(Input::get('date_to'))
					->withDept(Input::get('division_id'))
					->withData($data);
	}
	
}
