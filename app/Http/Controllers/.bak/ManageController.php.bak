<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Input;
use Response;
use DB;

class ManageController extends Controller
{
	
	public function index() {
			
		return view('body.manage.index');
	}
	
	
	public function getDisable()
	{
		DB::table('company')->where('id',1)->update(['status' => 0]);
		echo '<h1 style="color:red;">Account has been disabled successfully!</h1>';
	}
	
	public function getEnable()
	{
		DB::table('company')->where('id',1)->update(['status' => 1]);
		echo '<h1 style="color:green;">Account has been enabled successfully!</h1>';
	}
	
}