<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Category\CategoryInterface; 

use App\Http\Requests;
use Notification;
use Input;
use Session;
use App;
use DB;

class CategoryController extends Controller
{
   
	protected $category;

	public function __construct(CategoryInterface $category) {

		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->category = $category;
		$this->middleware('auth');

	}

	protected function index() {
		//echo 'hi';
		//return view('simple_tables');
		$data = array();
		$categories = $this->category->categoryList();
		//Session::flash('message', 'Category added successfully.');
		return view('body.category.index')
					->withCategories($categories)
					->withData($data);
	}

	public function add() {

		$data = array();
		return view('body.category.add')
					->withData($data);
	}
	
	public function save() {
		//print_r(Input::all());
		try {
			$this->category->create(Input::all());
			Session::flash('message', 'Category added successfully.');
			return redirect('category/add');
		} catch(ValidationException $e) { 
			return Redirect::to('category/add')->withErrors($e->getErrors());
		}
	}
	
	public function edit($id) { 

		$data = array();
		$catrow = $this->category->find($id);//print_r($grouprow);
		return view('body.category.edit')
					->withCatrow($catrow)
					->withData($data);
	}
	
	public function update($id)
	{
		$this->category->update($id, Input::all());//print_r(Input::all());exit;
		//Session::flash('message', 'Category updated successfully');
		return redirect('category');
	}
	
	public function destroy($id)
	{
		$this->category->delete($id);
		//check group name is already in use.........
		// code here ********************************
		Session::flash('message', 'Category deleted successfully.');
		return redirect('category');
	}
	
	public function checkname() {

		$check = $this->category->check_category_name(Input::get('category_name'), Input::get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
	public function destroyGroup()
	{
		$ids = Input::get('ids');
		if($ids) {
			$idarr = explode(',', $ids);
			
			DB::table('category')->whereIn('id',$idarr)->update(['deleted_at' => date('Y-m-d H:i:s')]);
			Session::flash('message', 'Categories deleted successfully.');
		}
		return redirect('category');
	}
}
