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

class EmployeeDocumentController extends Controller
{
	
	public function __construct() {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->middleware('auth');
		
	}
	
	public function index() {
		$data = array(); 
		$docs = DB::table('employee_document')->where('employee_document.status',1)
					->join('employee AS E', function($join) {
							$join->on('E.id','=','employee_document.employee_id');
						})
					->where('employee_document.deleted_at','0000-00-00 00:00:00')
					->select('employee_document.id','employee_document.name AS document','employee_document.file_name','E.name AS employee')
					->orderBy('employee_document.id','DESC')
					->get();
		
		return view('body.employeedocument.index')
					->withDocs($docs)
					->withData($data);
	}
	
	public function add() {

		$data = array();
		$employee = DB::table('employee')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->orderBy('name','ASC')->get();
		return view('body.employeedocument.add')
					->withEmployee($employee)
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
					$destinationPath = public_path() . '/uploads/employee_document/'.$image;
					$destinationPathThumb = public_path() . '/uploads/employee_document/thumb_'.$image;

					// resizing an uploaded file
					Image::make($file->getRealPath())->resize($width, $height, function($constraint) { $constraint->aspectRatio(); })->save($destinationPath);

					// thumb
					Image::make($file->getRealPath())->resize(200, 125, function($constraint) { $constraint->aspectRatio(); })->save($destinationPathThumb);
				} else {
					 $image = time().'.'.$ext;
					 echo $destinationPath = public_path() . '/uploads/employee_document/';
					 $file->move($destinationPath, $image);
				}
			}
			
		
			DB::table('employee_document')
				->insert([
					'employee_id' => Input::get('employee_id'),
					'name' => Input::get('name'),
					'description' => Input::get('description'),
					'file_name' => $image,
					'status' => 1
				]);
			Session::flash('message', 'Employee Document added successfully.'); 
			return redirect('employee_document');
		
		} catch(ValidationException $e) { //echo $e->getLine().' '.$e->getMessage();exit;
			return Redirect::to('employee_document/add')->withErrors($e->getErrors());
		}
	}
	
	public function edit($id) { 

		$data = array();
		$docrow = DB::table('employee_document')->where('id',$id)->first();
		$employee = DB::table('employee')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->orderBy('name','ASC')->get();
		return view('body.employeedocument.edit')
					->withDocrow($docrow)
					->withEmployee($employee)
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
					$destinationPath = public_path() . '/uploads/employee_document/'.$image;
					$destinationPathThumb = public_path() . '/uploads/employee_document/thumb_'.$image;

					// resizing an uploaded file
					Image::make($file->getRealPath())->resize($width, $height, function($constraint) { $constraint->aspectRatio(); })->save($destinationPath);

					// thumb
					Image::make($file->getRealPath())->resize(200, 125, function($constraint) { $constraint->aspectRatio(); })->save($destinationPathThumb);
				} else {
					 $image = time().'.'.$ext;
					 echo $destinationPath = public_path() . '/uploads/employee_document/';
					 $file->move($destinationPath, $image);
				}
			}
			
		
			DB::table('employee_document')->where('id',$id)
				->update([
					'employee_id' => Input::get('employee_id'),
					'name' => Input::get('name'),
					'description' => Input::get('description'),
					'file_name' => $image
				]);
			Session::flash('message', 'Employee Document updated successfully.'); 
			return redirect('employee_document');
			
		} catch(ValidationException $e) { 
			return Redirect::to('employee_document/add')->withErrors($e->getErrors());
		}
	}
	
	public function destroy($id)
	{
		if($id) {
			DB::table('employee_document')->where('id',$id)->update(['status' => 0, 'deleted_at' => date('Y-m-d H:i:s')]);
		}
		Session::flash('message', 'Employee Document deleted successfully.');
		return redirect('employee_document');
	}


	
	private function getReport($attributes)
	{
		$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
		$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';
		
					$query = DB::table('employee_document')
							->join('doc_department AS D', function($join) {
								$join->on('D.id','=','employee_document.department_id');
							})
							->where('employee_document.status',1);
								
						if( $date_from!='' && $date_to!='' ) { 
							$query->whereBetween('employee_document.expiry_date', array($date_from, $date_to));
						}
						
						if($attributes['department_id']!='') { 
							$query->where('employee_document.department_id', $attributes['department_id']);
						}
						
			return $query->select('employee_document.*','D.department_name')
						->orderBy('employee_document.id')->get();
			
	}
	
	public function getSearch()
	{
		$data = array();
		
		$reports = $this->getReport(Input::all());
		//echo '<pre>';print_r($reports);exit;
		
		if(Input::get('department_id')!='') {
			$voucher_head = 'Document Report - Department wise';
		} else {
			$voucher_head = 'Document Report';
		}
		
		return view('body.employeedocument.report')
					->withReports($reports)
					->withVoucherhead($voucher_head)
					->withFromdate(Input::get('date_from'))
					->withTodate(Input::get('date_to'))
					->withDept(Input::get('department_id'))
					->withData($data);
	}
	
}

