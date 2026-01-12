<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Accategory\AccategoryInterface; 

use App\Http\Requests;
use Notification;
use Session;
use App;
use DB;

class AccategoryController extends Controller
{
   
	protected $accategory;

	public function __construct(AccategoryInterface $accategory) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );

		$this->accategory = $accategory;
		$this->middleware('auth');

	}

	protected function index() {
		//echo 'hi';exit;
		//return view('simple_tables');
		$data = array();
		$categories = $this->accategory->accategoryList();
	//	echo '<pre>';print_r($categories);exit;
		//Session::flash('message', 'Accategory added successfully.');
		return view('body.accategory.index')
					->withCategories($categories)
					->withData($data);
	}

	public function add() {

		$data = array();
		$acctype = $this->accategory->accountType();
		return view('body.accategory.add')
					->withAcctype($acctype)
					->withData($data);
	}
	
	public function save(Request $request) {
		//print_r(Input::all());exit;
		try {
			$this->accategory->create($request->all());
			Session::flash('message', 'Category added successfully.');
			return redirect('accategory/add');
		} catch(ValidationException $e) { 
			return Redirect::to('accategory/add')->withErrors($e->getErrors());
		}
	}
	
	public function edit($id) { 

		$data = array();
		$acctype = $this->accategory->accountType();
		$catrow = $this->accategory->find($id);//print_r($grouprow);
		return view('body.accategory.edit')
					->withCatrow($catrow)
					->withAcctype($acctype)
					->withData($data);
	}
	
	public function update($id, Request $request)
	{
		$this->accategory->update($id, $request->all());//print_r(Input::all());exit;
		Session::flash('message', 'Category updated successfully');
		return redirect('accategory');
	}
	
	public function ajax_getcategory($type_id)
	{
		return $categories = $this->accategory->getCategorybyType($type_id);

	}
	
	public function ajax_getParent($type_id)
	{
		return $categories = $this->accategory->find($type_id);

	}
	
	public function destroy($id)
	{
		//echo '<pre>';print_r($id);exit;
		$status = $this->accategory->check_category($id);
		//echo '<pre>';print_r($status);exit;
		if($status) {
			$this->accategory->delete($id);
			Session::flash('message', 'Category deleted successfully.');
		} else
		{
			Session::flash('error', 'Category is already in use, you can\'t delete this!');
		} 
		return redirect('accategory');
	}


	
	public function destroyCate(Request $request)
	{
		$ids = $request->get('ids');
		//echo '<pre>';print_r($ids);exit;

		if($ids) {
			$idarr = explode(',', $ids);
		   $row = DB::table('account_master')->whereIn('account_category_id',$idarr)
		                  ->where('status', 1)
			              ->where('deleted_at', '0000-00-00 00:00:00')
						 ->count();
            if( $row > 0 )
			           Session::flash('errors', 'Category is already in use, you can\'t delete this!');
		    else {
			    DB::table('account_group')->whereIn('category_id', $idarr)->update(['deleted_at' => date('Y-m-d H:i:s')]);
				DB::table('account_category')->whereIn('id',$idarr)->update(['deleted_at' => date('Y-m-d H:i:s')]);
				Session::flash('message', 'Category deleted successfully.');
			
		}	
	}
		return redirect('accategory');
	
	}
	
	public function checkname(Request $request) {

		$check = $this->accategory->check_accategory_name($request->get('name'), $request->get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}


	
}

