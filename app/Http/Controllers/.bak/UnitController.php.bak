<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Unit\UnitInterface;

use App\Http\Requests;
use Input;
use Session;
use Response;
use App;
use DB;

class UnitController extends Controller
{
    protected $unit;
	
	public function __construct(UnitInterface $unit) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->unit = $unit;
		$this->middleware('auth');
		
		$this->mod_assembly_item = DB::table('parameter2')->where('keyname', 'mod_assembly_item')->where('status',1)->select('is_active')->first();
	}
	
	public function index() {
		$data = array();
		$aitem=$this->mod_assembly_item->is_active;
		$units = $this->unit->unitList();
		//echo '<pre>';print_r($aitem);exit;
		return view('body.unit.index')
					->withUnits($units)
					->withAitem($aitem)
					->withData($data);
	}
	
	public function add() {

		$data = array();
		return view('body.unit.add')
					->withData($data);
	}
	
	public function save() {
		//print_r(Input::all());
		$this->unit->create(Input::all());
		Session::flash('message', 'Unit added successfully.');
		return redirect('unit/add');
	}
	
	public function edit($id) { 

		$data = array();
		$unitrow = $this->unit->find($id);//print_r($unitrow);
		return view('body.unit.edit')
					->withUnitrow($unitrow)
					->withData($data);
	}
	
	public function update($id)
	{
		$this->unit->update($id, Input::all());//print_r(Input::all());exit;
		//Session::flash('message', 'Category updated successfully');
		return redirect('unit');
	}
	
	public function destroy($id)
	{
		if($id > 2)
			$this->unit->delete($id);
		//check unit name is already in use.........
		// code here ********************************
		Session::flash('message', 'Unit deleted successfully.');
		return redirect('unit');
	}
	
	public function checkname() {

		$check = $this->unit->check_unit_name(Input::get('unit_name'), Input::get('id'));
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
			DB::table('units')->whereIn('id',$idarr)->update(['deleted_at' => date('Y-m-d H:i:s')]);
			Session::flash('message', 'Units deleted successfully.');
		}
		return redirect('unit');
	}
}