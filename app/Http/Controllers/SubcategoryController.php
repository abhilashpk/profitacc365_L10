<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Category\CategoryInterface; 

use App\Http\Requests;
use Notification;
use Session;
use App;
use DB;

class SubcategoryController extends Controller
{
   
	protected $category;

	public function __construct(CategoryInterface $category) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->category = $category;
		$this->middleware('auth');
	}

	protected function index() {

		$data = array();
		$subcategories = $this->category->subcategoryList();
		//Session::flash('message', 'Category added successfully.');
		return view('body.subcategory.index')
					->withSubcategories($subcategories)
					->withData($data);
	}

	public function add() {

		$data = array();
		return view('body.subcategory.add')
					->withData($data);
	}
	
	public function save(Request $request) {
		//print_r($request->all());
		try {
			$this->category->create($request->all());
			Session::flash('message', 'Sub Category added successfully.');
			return redirect('subcategory/add');
		} catch(ValidationException $e) { 
			return Redirect::to('subcategory/add')->withErrors($e->getErrors());
		}
	}
	
	public function edit($id) { 

		$data = array();
		$catrow = $this->category->find($id);//print_r($grouprow);
		return view('body.subcategory.edit')
					->withCatrow($catrow)
					->withData($data);
	}
	
	public function update($id, Request $request)
	{
		$this->category->update($id, $request->all());//print_r($request->all());exit;
		//Session::flash('message', 'Category updated successfully');
		return redirect('subcategory');
	}
	
	public function destroy($id)
	{
		$this->category->delete($id);
		//check group name is already in use.........
		// code here ********************************
		Session::flash('message', 'Sub Category deleted successfully.');
		return redirect('subcategory');
	}
	
	public function checkname(Request $request) {

		$check = $this->category->check_subcategory_name($request->get('category_name'), $request->get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
	public function destroyGroup(Request $request)
	{
		$ids = $request->get('ids');
		if($ids) {
			$idarr = explode(',', $ids);
			DB::table('category')->whereIn('id',$idarr)->update(['deleted_at' => date('Y-m-d H:i:s')]);
			Session::flash('message', 'subcategories deleted successfully.');
		}
		return redirect('subcategory');
	}
}

