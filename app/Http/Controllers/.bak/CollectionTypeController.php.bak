<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Input;
use Session;
use Response;
use DB;
use App;

class CollectionTypeController extends Controller
{
    protected $collection_type;
	
	public function __construct() {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->middleware('auth');
		
	}
	
	public function index() {
		$data = array();
		$ctype = DB:: table('collection_type')
		                ->where('collection_type.deleted_at',null)->orderBy('collection_type.code','ASC')->get();
						//echo '<pre>';print_r($ctype);exit;
		return view('body.collectiontype.index')
					->withCtype($ctype)
					->withData($data);
	}
	
	public function add() {

		$data = array();
		return view('body.collectiontype.add')
					->withData($data);
	}
	
	 public function save() {
		try {
			DB::table('collection_type')
				->insert([
					'code' => Input::get('code'),
					'description' => Input::get('description')
				]);
				
			Session::flash('message', 'Collection Type added successfully.');
			return redirect('collection_type/add');
		} catch(ValidationException $e) { 
			return Redirect::to('collection_type/add')->withErrors($e->getErrors());
		}
	}  

	
	
	public function edit($id) { 

		$ctedit = DB::table('collection_type')->where('id',$id)->first();
		//echo '<pre>';print_r($cedit);exit;
						
		return view('body.collectiontype.edit')
					->withCtedit($ctedit);
	}



	public function update($id)
	{
		DB::table('collection_type')->where('id',$id)
				->update([
					'code' => Input::get('code'),
					'description' => Input::get('description'),
				]);
		Session::flash('message', 'Collection Type updated successfully');
		return redirect('collection_type');
	}

    public function checkcode() {

		$check = $this->check_collection_code(Input::get('code'), Input::get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
					));
	}
	public function check_collection_code($code, $id = null) {
		
		if($id){
		$query=DB::table('collection_type')
		->where('code',$code)->where('id', '!=', $id)->where('collection_type.deleted_at',null)->count();
		return $query;
		}
		else{
		$query=DB::table('collection_type')
		->where('code',$code)->where('collection_type.deleted_at',null)->count();
		return $query ;
		}
	}

	public function destroy($id)
	{
		DB::table('collection_type')->where('id',$id)->update(['deleted_at' => date('Y-m-d H:i:s')]);
		Session::flash('message', 'Collection Type deleted successfully.');
		return redirect('collection_type');
	}
	
	
	
	
}
