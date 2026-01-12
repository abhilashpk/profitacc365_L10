<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Notification;
use Input;
use Session;
use DB;
use App;

class PermissionController extends Controller
{
   
	public function __construct() {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->middleware('auth');

	}

	protected function index() {
		
		$data = array();
		$categories = $this->permission->accategoryList();//echo '<pre>';print_r($categories);exit;
		//Session::flash('message', 'Accategory added successfully.');
		return view('body.accategory.index')
					->withCategories($categories)
					->withData($data);
	}

	public function add() {

		
	}
	
	public function save() {
		
	}
	
	private function makeTreeArr($result) {
		
		$childs = array();
		foreach($result as $item)
			$childs[$item->section][] = $item;
		
		return $childs;
	}
	
	private function makeArr($result){
		$arr = [];
		foreach($result as $res)
			$arr[] = $res->permission_id;
			
		return $arr;
	}
	
	public function edit($id) { 

		$data = array();
		$role = DB::table('roles')->find($id);
		$permissions = $this->makeTreeArr( DB::table('permissions')
											->select('permissions.id','permissions.name','permissions.description','permissions.section')
											->get() );
		$permission_role = $this->makeArr( DB::table('permission_role')->where('role_id',$id)->select('permission_id')->get() ); 								
		
		//echo '<pre>'; print_r($permissions);exit;
					
		return view('body.permission.edit')
					->withPermissions($permissions)
					->withPermissionrole($permission_role)
					->withRole($role)
					->withData($data);
	}
	
	public function update()
	{
		$role_id = Input::get('role_id');
		$permission_role = $this->makeArr( DB::table('permission_role')->where('role_id',$role_id)->select('permission_id')->get() );
		//echo '<pre>';print_r($permission_role);exit;
		foreach(Input::get('permission_id') as $id) {
			if(!in_array($id, $permission_role)) {
				DB::table('permission_role')->insert(['permission_id' => $id, 'role_id' => $role_id]);
				
			}
		}
		
		//DELETE PERMISSION....
		foreach($permission_role as $id) {
			if(!in_array($id, Input::get('permission_id'))) {
				DB::table('permission_role')->where('permission_id',$id)->where('role_id',$role_id)->delete();
			}
		}
		Session::flash('message', 'Permission updated successfully');
		return redirect('permission/edit/'.$role_id);
	}
	
}

