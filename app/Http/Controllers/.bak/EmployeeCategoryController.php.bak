<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use App\Repositories\Unit\UnitInterface;

use App\Http\Requests;
use Input;
use Session;
use Response;
use App;
use DB;

class EmployeeCategoryController extends Controller
{
    protected $units;
	
	public function __construct() {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->middleware('auth');
	}
	
	public function index() {
		$data = array();
		$empcategory =DB:: table('employee_category')
		->where('employee_category.deleted_at','0000-00-00 00:00:00')->orderBy('employee_category.id','ASC')->get();
		return view('body.empcategory.index')
					->withEmpcategory($empcategory)
					->withData($data);
	}
	
	public function add() {

		$data = array();
		return view('body.empcategory.add')
					->withData($data);
	}
	
	public function save() {
		//print_r(Input::all());
		DB::table('employee_category')
				->insert([
					'category_name' => Input::get('category_name'),
					'description' => Input::get('description'),
					'status'=>1,
				
				]);
		Session::flash('message', 'Employee Category added successfully.');
		return redirect('emp_category/add');
	}
	
	public function edit($id) { 

		$data = array();
		$empcategory = DB::table('employee_category')->where('id',$id)->first();
		return view('body.empcategory.edit')
					->withEmpcategory($empcategory)
					->withData($data);
	}
	
	public function update($id)
	{
		DB::table('employee_category')->where('id',$id)
				->update([
					'category_name' => Input::get('category_name'),
					'description' => Input::get('description'),
					'status'=>1,
				]);//print_r(Input::all());exit;
		Session::flash('message', 'Employee Category updated successfully');
		return redirect('emp_category');
	}
	
	public function destroy($id)
	{
		DB::table('employee_category')->where('id',$id)->update(['deleted_at' => date('Y-m-d H:i:s')]);
		
		Session::flash('message', 'Employee Category deleted successfully.');
		return redirect('emp_category');
	}
	
	public function checkname() {

		$check = $this->check_category_name(Input::get('category_name'), Input::get('id'));
		$isAvailable = ($check) ? false : true;
		//echo '<pre>';print_r($isAvailable);exit;
		echo json_encode(array(
							'valid' => $isAvailable,
					));
	}
	public function check_category_name($name, $id = null) {
		
		if($id){
		$query=DB::table('employee_category')
		->where('category_name',$name)->where('id', '!=', $id)->where('employee_category.deleted_at','0000-00-00 00:00:00')->count();
		return $query;
		}
		else{
		$query=DB::table('employee_category')
		->where('category_name',$name)->where('employee_category.deleted_at','0000-00-00 00:00:00')->count();
		return $query ;
		}
	}
	
	public function destroyGroup()
	{
		$ids = Input::get('ids');
		if($ids) {
			$idarr = explode(',', $ids);
			DB::table('employee_category')->whereIn('id',$idarr)->update(['deleted_at' => date('Y-m-d H:i:s')]);
			Session::flash('message', 'Employee Category deleted successfully.');
		}
		return redirect('empcategory');
	}
}