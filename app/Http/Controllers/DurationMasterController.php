<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Session;
use Response;
use DB;
use App;

class DurationMasterController extends Controller
{
    protected $duration;
	
	public function __construct() {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->middleware('auth');
		
	}
	
	public function index() {
		$data = array();
		$duration = DB::table('duration')->where('deleted_at',null)->get();
		return view('body.duration.index')
					->withDuration($duration)
					->withData($data);
	}
	
	public function add() {

		$data = array();
		return view('body.duration.add')
					->withData($data);
	}
	
	public function save(Request $request) {
		//echo '<pre>';print_r($request->all());exit;
		try {
			DB::table('duration')
				->insert([
					'duration_month' => $request->get('duration_month'),
					'duration_days' => $request->get('duration_days')
					
				]);
			Session::flash('message', 'Duration added successfully.');
			return redirect('duration/add');
		} catch(ValidationException $e) { 
			return Redirect::to('duration/add')->withErrors($e->getErrors());
		}
	}
	
	public function edit($id) { 

		$data = array();
		$row = DB::table('duration')->where('duration.id',$id)->first();
						
		return view('body.duration.edit')
					->withMrow($row)
					->withData($data);
	}
	
	public function update(Request $request, $id)
	{
		DB::table('duration')->where('id',$id)
				->update([
					'duration_month' => $request->get('duration_month'),
					'duration_days' => $request->get('duration_days')
				]);
		Session::flash('message', 'Duration updated successfully');
		return redirect('duration');
	}
	
	public function destroy($id)
	{
		$dm = DB::table('duration')->where('id',$id)->first();
		$check = DB::table('contract_building')->where('contract_building.duration','=',$dm->duration_month)->whereNull('deleted_at')->first();
		if(!$check) {
			DB::table('duration')->where('id',$id)->update(['deleted_at' => date('Y-m-d H:i:s')]);
			Session::flash('message', 'Duration deleted successfully.');
		} else
			Session::flash('error', 'Duration already in use.');
		
		return redirect('duration');
	}
   

   public function CalculateDays(Request $request) {  
      $convert = $request->get('duration_month'); // days you want to convert

      $years = ($convert / 365) ; // days / 365 days
      $years = floor($years); // Remove all decimals

      $month = ($convert % 365) / 30.5; // I choose 30.5 for Month (30,31) ;)
      $month = floor($month); // Remove all decimals

      // $days = ($convert % 365) % 30.5; // the rest of days
     // $days = ($convert % 365) % 30.5;
     $days = ($convert * 30.44 ) ;
     $days = floor($days);
     return  $days;
    
		
	}

	public function checkcode(Request $request) {

		$query = DB::table('duration')->where('duration_month', $request->query('duration_month'))->whereNull('deleted_at');

		// 👇 ignore current record during edit
		if ($request->filled('id')) {
			$query->where('id', '!=', $request->query('id'));
		}

		if ($query->exists()) {
			// MUST return string for jQuery Validate
			return response()->json("This Duration is already here!");
		}

		return response()->json(true);
	}
	
	
}
