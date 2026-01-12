<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Acgroup\AcgroupInterface; 
use App\Repositories\Accategory\AccategoryInterface;

use App\Http\Requests;
use Notification;
use Session;
use DB;
use App;

class AcgroupController extends Controller
{
   
	protected $acgroup;

	public function __construct(AccategoryInterface $accategory, AcgroupInterface $acgroup) {

		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->middleware('auth');
		$this->acgroup = $acgroup;
		$this->accategory = $accategory;

	}

	protected function index() {
		//echo 'hi';
		//return view('simple_tables');
		$data = array();
		$groups = $this->acgroup->acgroupList();//echo '<pre>';print_r($categories);exit;
		//Session::flash('message', 'Acgroup added successfully.');
		return view('body.acgroup.index')
					->withGroups($groups)
					->withData($data);
	}

	public function add() {

		$data = array();
		$acg = DB::table('account_group')->select('id')->orderBy('id','DESC')->first(); //print_r($acctype);exit;
		$accategory = $this->accategory->activeAccategoryList();
		return view('body.acgroup.add')
					->withAcg($acg->id)
					->withCategory($accategory)
					->withData($data);
	}
	
	public function save(Request $request) {
		//print_r(Input::all());exit;
		try {
			$this->acgroup->create($request->all());
			Session::flash('message', 'Acgroup added successfully.');
			return redirect('acgroup/add');
		} catch(ValidationException $e) { 
			return Redirect::to('acgroup/add')->withErrors($e->getErrors());
		}
	}
	
	public function edit($id) { 

		$data = array();
		$accategory = $this->accategory->activeAccategoryList();
		$catrow = $this->acgroup->find($id);
		$type = $this->accategory->find($catrow->category_id);
		return view('body.acgroup.edit')
					->withCatrow($catrow)
					->withTypeid($type->parent_id)
					->withCategory($accategory)
					->withData($data);
	}
	
	public function update($id, Request $request)
	{
		$this->acgroup->update($id, $request->all());//print_r(Input::all());exit;
		//Session::flash('message', 'Acgroup updated successfully');
		return redirect('acgroup');
	}
	
	public function destroy($id)
	{
		$status = $this->acgroup->check_group($id);
		if($status) {
			$this->acgroup->delete($id);
			Session::flash('message', 'Group deleted successfully.');
		} else 
			Session::flash('error', 'Group is already in use, you can\'t delete this!');
		
		return redirect('acgroup');
	}
	
	public function checkname(Request $request) {

		$check = $this->acgroup->check_group_name($request->get('name'), $request->get('category_id'), $request->get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
	
	public function ajax_getgroup($category_id)
	{
		return $groups = $this->acgroup->getGroupbyCategory($category_id);

	}
	
	public function ajax_getcode($group_id)
	{
		return $groups = $this->acgroup->getGroupCode($group_id);

	}
	
	public function checkcode(Request $request) {

		$check = $this->acgroup->check_group_code($request->get('code'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
}

