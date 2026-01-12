<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Group\GroupInterface;

use App\Http\Requests;
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
	
	public function save(Request $request) {
		//print_r($request->all());
		$this->group->create($request->all());
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
	
	public function update($id, Request $request)
	{
		$this->group->update($id, $request->all());//print_r($request->all());exit;
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
	
	public function checkname(Request $request) {

		$check = $this->group->check_group_name($request->get('group_name'), $request->get('id'));
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
			DB::table('groupcat')->whereIn('id',$idarr)->update(['deleted_at' => date('Y-m-d H:i:s')]);
			Session::flash('message', 'Groups deleted successfully.');
		}
		return redirect('group');
	}
}

