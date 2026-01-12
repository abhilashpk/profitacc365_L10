<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Input;
use Session;
use Response;
use DB;
use App;

class PaperController extends Controller
{
	
	public function __construct() {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->middleware('auth');
		
	}
	
	public function index() {
		$data = array();
		$paper = DB::table('paper')->where('deleted_at',null)->get();
		return view('body.paper.index')
					->withPaper($paper)
					->withData($data);
	}
	
	public function add() {

		$data = array();
		return view('body.paper.add')
					->withData($data);
	}
	
	public function save() {
		try {
			DB::table('paper')
				->insert([
					'name' => Input::get('name'),
					'rate' => Input::get('rate')
				]);
				
			Session::flash('message', 'Paper added successfully.');
			return redirect('paper/add');
		} catch(ValidationException $e) { 
			return Redirect::to('paper/add')->withErrors($e->getErrors());
		}
	}
	
	public function edit($id) { 

		$data = array();
		$paper = DB::table('paper')->where('id',$id)->first();
						
		return view('body.paper.edit')
					->withProw($paper)
					->withData($data);
	}
	
	public function update($id)
	{
		DB::table('paper')->where('id',$id)
				->update([
					'name' => Input::get('name'),
					'rate' => Input::get('rate')
				]);
		Session::flash('message', 'Paper updated successfully');
		return redirect('paper');
	}
	
	public function destroy($id)
	{
		DB::table('paper')->where('id',$id)->update(['deleted_at' => date('Y-m-d H:i:s')]);
		Session::flash('message', 'Paper deleted successfully.');
		return redirect('paper');
	}
	
	
}

