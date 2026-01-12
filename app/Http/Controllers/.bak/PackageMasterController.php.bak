<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Input;
use Session;
use Response;
use DB;

class PackageMasterController extends Controller
{
	
	public function __construct() {
		
		$this->middleware('auth');
	}
	
	public function index() {
		$data = array();
		$packages = DB::table('package_master')->where('deleted_at','0000-00-00 00:00:00')->orderBy('id','DESC')->get();
		return view('body.packagemaster.index')
					->withPackages($packages)
					->withData($data);
	}
	
	public function add() {

		return view('body.packagemaster.add');
	}
	
	public function save() {
		try {
			DB::table('package_master')
					->insert([
						'name' => Input::get('name'),
						'description' => Input::get('description'),
						'amount' => Input::get('amount'),
						'status'	=> 1
					]);
				
			Session::flash('message', 'Package Master added successfully.');
			return redirect('package_master');
		} catch(ValidationException $e) { 
			return Redirect::to('package_master/add')->withErrors($e->getErrors());
		}
	}
	
	public function edit($id) { 

		$data = array();
		$pkgrow = DB::table('package_master')->find($id);
						
		return view('body.packagemaster.edit')
					->withPkgrow($pkgrow)
					->withData($data);
	}
	
	public function update($id)
	{
		DB::table('package_master')->where('id',$id)
				->update([
					'name' => Input::get('name'),
					'description' => Input::get('description'),
					'amount' => Input::get('amount')
				]);
				
		Session::flash('message', 'Package Master updated successfully');
		return redirect('package_master');
	}
	
	public function destroy($id)
	{
		DB::table('package_master')->where('id',$id)->update(['deleted_at' => date('Y-m-d H:i:s')]);
		Session::flash('message', 'Package Master deleted successfully.');
		return redirect('package_master');
	}
	
	
	
}
