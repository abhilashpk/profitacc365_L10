<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Input;
use Session;
use Response;
use DB;
use Artisan;
use Config;

class SettingsController extends Controller
{
	protected $dbcon;
	public function __construct() {
		$this->dbcon = Config::get('database.connections');
	}
	
	public function index() {
		$loggedin = false; 
		//echo env('DB_DATABASE', 'forge');exit;
		
		//echo '<pre>';print_r($this->dbcon);exit;
		return view('dbswitch')
					->withLoggedin($loggedin);
	}
	
	public function SubmitLogin()
	{
		$loggedin = false;
		if(Input::get('password')=='profit2020') {
			$loggedin = true;
		} else {
			Session::flash('error', 'Invalid password!');
			return redirect('settings/dbswitch');
		}
		return view('dbswitch')
					->withLoggedin($loggedin);
	}
	
	
	public function SubmitDbswitch()
	{
		$year = Input::get('year'); //profitacc365yr2019
		$arr = explode('yr',$this->dbcon['mysql']['database']);
		
		if($year==date('Y')){
			$dbname = $arr[0];
		} else {
			$dbname = $arr[0].'yr'.$year;
		}
		
		$path = base_path('.env');
		if(file_exists($path)) {
            file_put_contents($path, str_replace(
                'DB_DATABASE='.$this->dbcon['mysql']['database'], 'DB_DATABASE='.$dbname, file_get_contents($path)
            ));
			Artisan::call('config:cache');
        }
		
		Session::flash('message', 'Database has been changed successfully.');
		return redirect('settings/dbswitch');
		
	}
}

