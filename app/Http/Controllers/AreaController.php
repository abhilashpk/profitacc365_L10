<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Area\AreaInterface;

use App\Http\Requests;
use Session;
use Response;
use App;

class AreaController extends Controller
{
    protected $area;
	
	public function __construct(AreaInterface $area) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		
		$this->area = $area;
		$this->middleware('auth');
		
	}
	
	public function index() { 
		$data = array();
		$areas = $this->area->areaList();
		return view('body.area.index')
					->withAreas($areas)
					->withData($data);
	}
	
	public function add() {

		$data = array();
		return view('body.area.add')
					->withData($data);
	}
	
	public function save(Request $request) {
		$this->area->create($request->all());
		Session::flash('message', 'Area added successfully.');
		return redirect('area/add');
	}
	
	public function edit($id) { 

		$data = array();
		$arearow = $this->area->find($id);
		return view('body.area.edit')
					->withArearow($arearow)
					->withData($data);
	}
	
	public function update($id, Request $request)
	{
		$this->area->update($id, $request->all());//print_r($request->all());exit;
		//Session::flash('message', 'Category updated successfully');
		return redirect('area');
	}
	
	public function destroy($id)
	{
		$this->area->delete($id);
		//check area name is already in use.........
		// code here ********************************
		Session::flash('message', 'Area deleted successfully.');
		return redirect('area');
	}
	
	public function checkcode(Request $request) {

		$check = $this->area->check_area_code($request->get('code'), $request->get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
	
	public function checkname(Request $request) {

		$check = $this->area->check_area_name($request->get('name'), $request->get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
}

