<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Group\GroupInterface;

use App\Http\Requests;
use Input;
use Session;
use App;
use DB;

class SubgroupController extends Controller
{
    protected $group;
	
	public function __construct(GroupInterface $group) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->group = $group;
		$this->middleware('auth');
	}
	
	public function index() {
		$data = array();
		$subgroups = $this->group->subgroupList();
		return view('body.subgroup.index')
					->withSubgroups($subgroups)
					->withData($data);
	}
	
	public function add() {

		$data = array();
		return view('body.subgroup.add')
					->withData($data);
	}
	
	public function save() {
		//print_r(Input::all());
		$this->group->create(Input::all());
		Session::flash('message', 'Sub Group added successfully.');
		return redirect('subgroup/add');
	}
	
	public function edit($id) { 

		$data = array();
		$grouprow = $this->group->find($id);//print_r($grouprow);
		return view('body.subgroup.edit')
					->withGrouprow($grouprow)
					->withData($data);
	}
	
	public function update($id)
	{
		$this->group->update($id, Input::all());//print_r(Input::all());exit;
		//Session::flash('message', 'Category updated successfully');
		return redirect('subgroup');
	}
	
	public function destroy($id)
	{
		$this->group->delete($id);
		//check group name is already in use.........
		// code here ********************************
		Session::flash('message', 'Sub Group deleted successfully.');
		return redirect('subgroup');
	}
	
	public function checkname() {

		$check = $this->group->check_subgroup_name(Input::get('group_name'), Input::get('id'));
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
			DB::table('groupcat')->whereIn('id',$idarr)->update(['deleted_at' => date('Y-m-d H:i:s')]);
			Session::flash('message', 'Subgroups deleted successfully.');
		}
		return redirect('subgroup');
	}
}
