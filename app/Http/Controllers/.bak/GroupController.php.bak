<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Group\GroupInterface;

use App\Http\Requests;
use Input;
use Session;
use Response;
use App;
use DB;

class GroupController extends Controller
{
    protected $group;
	
	public function __construct(GroupInterface $group) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->group = $group;
		$this->middleware('auth');
		
	}
	
	public function index() {
		$data = array();
		$groups = $this->group->groupList();
		return view('body.group.index')
					->withGroups($groups)
					->withData($data);
	}
	
	public function add() {

		$data = array();
		return view('body.group.add')
					->withData($data);
	}
	
	public function save() {
		//print_r(Input::all());
		$this->group->create(Input::all());
		Session::flash('message', 'Group added successfully.');
		return redirect('group/add');
	}
	
	public function edit($id) { 

		$data = array();
		$grouprow = $this->group->find($id);//print_r($grouprow);
		return view('body.group.edit')
					->withGrouprow($grouprow)
					->withData($data);
	}
	
	public function update($id)
	{
		$this->group->update($id, Input::all());//print_r(Input::all());exit;
		//Session::flash('message', 'Category updated successfully');
		return redirect('group');
	}
	
	public function destroy($id)
	{
		$this->group->delete($id);
		//check group name is already in use.........
		// code here ********************************
		Session::flash('message', 'Group deleted successfully.');
		return redirect('group');
	}
	
	public function checkname() {

		$check = $this->group->check_group_name(Input::get('group_name'), Input::get('id'));
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
			Session::flash('message', 'Groups deleted successfully.');
		}
		return redirect('group');
	}
}
