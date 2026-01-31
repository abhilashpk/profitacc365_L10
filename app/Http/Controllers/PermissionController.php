<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Notification;
use Session;
use DB;
use App;
use Spatie\Permission\PermissionRegistrar;

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
		$permission_role = $this->makeArr( DB::table('role_has_permissions')->where('role_id',$id)->select('permission_id')->get() ); 								
		
		//echo '<pre>'; print_r($permissions);exit;
					
		return view('body.permission.edit')
					->withPermissions($permissions)
					->withPermissionrole($permission_role)
					->withRole($role)
					->withData($data);
	}
	
	public function update(Request $request)
	{
		$role_id = $request->input('role_id');
		$permission_ids = $request->input('permission_id', []);
		if (!is_array($permission_ids)) {
			$permission_ids = [];
		}
		$permission_role = $this->makeArr( DB::table('role_has_permissions')->where('role_id',$role_id)->select('permission_id')->get() );
		//echo '<pre>';print_r($permission_role);exit;
		foreach($permission_ids as $id) {
			if(!in_array($id, $permission_role)) {
				DB::table('role_has_permissions')->insert(['permission_id' => $id, 'role_id' => $role_id]);
				
			}
		}
		
		//DELETE PERMISSION....
		foreach($permission_role as $id) {
			if(!in_array($id, $permission_ids)) {
				DB::table('role_has_permissions')->where('permission_id',$id)->where('role_id',$role_id)->delete();
			}
		}
		app(PermissionRegistrar::class)->forgetCachedPermissions();
		Session::flash('message', 'Permission updated successfully');
		return redirect('permission/edit/'.$role_id);
	}
	
}

