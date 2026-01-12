<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Input;
use Session;
use Response;
use DB;
use App;

class ContraTypeController extends Controller
{
    protected $contra_type;
	
	public function __construct() {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->middleware('auth');
		
	}
	
	public function index() {
		$results = DB::table('contra_type')->join('buildingmaster','buildingmaster.id','=','contra_type.buildingid')
						->select('contra_type.*','buildingmaster.buildingcode','buildingmaster.buildingname')->get();
						
		return view('body.contratype.index')
					->withResults($results);
	}
	
	public function add() {

		$data = array();
		$buildingmaster = DB::table('buildingmaster')->where('deleted_at',null)->get();
		//print_r($buildingmaster);exit;
		return view('body.contratype.add')
	            	->withBuildingmaster($buildingmaster)
					->withData($data);
	}
	
	public function save(Request $request) {
		try {
			DB::table('contra_type')
				->insert([
					'buildingid' => $request->get('buildingid'),
					'type' => $request->get('type'),
					'increment_no' => ($request->get('increment_no')=='')?100:$request->get('increment_no'),
					'prepaid_income'	=> ($request->get('acname_1')!='')?$request->get('prepaid_income'):'',
					'rental_income'	=> ($request->get('acname_2')!='')?$request->get('rental_income'):'',
					'deposit'	=> ($request->get('acname_3')!='')?$request->get('deposit'):'',
					'water_ecty'	=> ($request->get('acname_4')!='')?$request->get('water_ecty'):'',
					'other_deposit'	=> ($request->get('acname_5')!='')?$request->get('other_deposit'):'',
					'commission'	=> ($request->get('acname_6')!='')?$request->get('commission'):'',
					'parking'	=> ($request->get('acname_7')!='')?$request->get('parking'):'',
					'cancellation'	=> ($request->get('acname_8')!='')?$request->get('cancellation'):'',
					'repair'	=> ($request->get('acname_9')!='')?$request->get('repair'):'',
					'water_ecty_bill'	=> ($request->get('acname_10')!='')?$request->get('water_ecty_bill'):'',
					'closing_oth'	=> ($request->get('acname_11')!='')?$request->get('closing_oth'):'',
					'booking_oth'	=> ($request->get('acname_12')!='')?$request->get('booking_oth'):'',
					'chq_charge'	=> ($request->get('acname_13')!='')?$request->get('chq_charge'):'',
					'ejarie_fee'	=> ($request->get('acname_14')!='')?$request->get('ejarie_fee'):'',
					'pi_tax'	=> $request->get('pi_tax'),
					'ri_tax'	=> $request->get('ri_tax'),
					'd_tax'	=> $request->get('d_tax'),
					'we_tax'	=> $request->get('we_tax'),
					'od_tax'	=> $request->get('od_tax'),
					'c_tax'	=> $request->get('c_tax'),
					'p_tax'	=> $request->get('p_tax'),
					'cl_tax'	=> $request->get('cl_tax'),
					'r_tax'	=> $request->get('r_tax'),
					'web_tax'	=> $request->get('web_tax'),
					'co_tax'	=> $request->get('co_tax'),
					'bo_tax'	=> $request->get('bo_tax'),
					'cc_tax'	=> $request->get('cc_tax'),
					'ef_tax'	=> $request->get('ef_tax')
				]);
			Session::flash('message', 'Contract type added successfully.');
			return redirect('contra_type/add');
		} catch(ValidationException $e) { 
			return Redirect::to('contra_type/add')->withErrors($e->getErrors());
		}
	}
	
	public function edit($id) { 

		$buildingmaster = DB::table('buildingmaster')->where('deleted_at',null)->get();
		$row = DB::table('contra_type')->where('id',$id)->first();
						
		$row = DB::table('contra_type')
								->leftJoin('account_master AS M1', 'M1.id', '=', 'contra_type.prepaid_income')
								->leftJoin('account_master AS M2', 'M2.id', '=', 'contra_type.rental_income')
								->leftJoin('account_master AS M3', 'M3.id', '=', 'contra_type.deposit')
								->leftJoin('account_master AS M4', 'M4.id', '=', 'contra_type.water_ecty')
								->leftJoin('account_master AS M5', 'M5.id', '=', 'contra_type.other_deposit')
								->leftJoin('account_master AS M6', 'M6.id', '=', 'contra_type.commission')
								->leftJoin('account_master AS M7', 'M7.id', '=', 'contra_type.parking')
								->leftJoin('account_master AS M8', 'M8.id', '=', 'contra_type.cancellation')
								->leftJoin('account_master AS M9', 'M9.id', '=', 'contra_type.repair')
								->leftJoin('account_master AS M10', 'M10.id', '=', 'contra_type.water_ecty_bill')
								->leftJoin('account_master AS M11', 'M11.id', '=', 'contra_type.closing_oth')
								->leftJoin('account_master AS M12', 'M12.id', '=', 'contra_type.booking_oth')
								->leftJoin('account_master AS M13', 'M13.id', '=', 'contra_type.chq_charge')
								->leftJoin('account_master AS M14', 'M14.id', '=', 'contra_type.ejarie_fee')
								->select('M1.master_name AS acname1','M2.master_name AS acname2','M3.master_name AS acname3','M4.master_name AS acname4',
										'M5.master_name AS acname5','M6.master_name AS acname6','M7.master_name AS acname7','M8.master_name AS acname8',
										'M9.master_name AS acname9','M10.master_name AS acname10','M11.master_name AS acname11','M12.master_name AS acname12',
										'M13.master_name AS acname13','M14.master_name AS acname14','contra_type.*')
								->where('contra_type.id',$id)->first();
								
			//echo '<pre>';print_r($row);exit;					
		return view('body.contratype.edit')
					->withBuildingmaster($buildingmaster)
					->withCrow($row);
	}
	
	public function update(Request $request, $id)
	{
		DB::table('contra_type')->where('id',$id)
				->update([
					'buildingid' => $request->get('buildingid'),
					'type' => $request->get('type'),
					'increment_no' => ($request->get('increment_no')=='')?100:$request->get('increment_no'),
					'prepaid_income'	=> ($request->get('acname_1')!='')?$request->get('prepaid_income'):'',
					'rental_income'	=> ($request->get('acname_2')!='')?$request->get('rental_income'):'',
					'deposit'	=> ($request->get('acname_3')!='')?$request->get('deposit'):'',
					'water_ecty'	=> ($request->get('acname_4')!='')?$request->get('water_ecty'):'',
					'other_deposit'	=> ($request->get('acname_5')!='')?$request->get('other_deposit'):'',
					'commission'	=> ($request->get('acname_6')!='')?$request->get('commission'):'',
					'parking'	=> ($request->get('acname_7')!='')?$request->get('parking'):'',
					'cancellation'	=> ($request->get('acname_8')!='')?$request->get('cancellation'):'',
					'repair'	=> ($request->get('acname_9')!='')?$request->get('repair'):'',
					'water_ecty_bill'	=> ($request->get('acname_10')!='')?$request->get('water_ecty_bill'):'',
					'closing_oth'	=> ($request->get('acname_11')!='')?$request->get('closing_oth'):'',
					'booking_oth'	=> ($request->get('acname_12')!='')?$request->get('booking_oth'):'',
					'chq_charge'	=> ($request->get('acname_13')!='')?$request->get('chq_charge'):'',
					'ejarie_fee'	=> ($request->get('acname_14')!='')?$request->get('ejarie_fee'):'',
					'pi_tax'	=> $request->get('pi_tax'),
					'ri_tax'	=> $request->get('ri_tax'),
					'd_tax'	=> $request->get('d_tax'),
					'we_tax'	=> $request->get('we_tax'),
					'od_tax'	=> $request->get('od_tax'),
					'c_tax'	=> $request->get('c_tax'),
					'p_tax'	=> $request->get('p_tax'),
					'cl_tax'	=> $request->get('cl_tax'),
					'r_tax'	=> $request->get('r_tax'),
					'web_tax'	=> $request->get('web_tax'),
					'co_tax'	=> $request->get('co_tax'),
					'bo_tax'	=> $request->get('bo_tax'),
					'cc_tax'	=> $request->get('cc_tax'),
					'ef_tax'	=> $request->get('ef_tax')
				]);
		Session::flash('message', 'Contract type updated successfully');
		return redirect('contra_type');
	}
	
	public function destroy($id)
	{
		DB::table('contra_type')->where('id',$id)->update(['deleted_at' => date('Y-m-d H:i:s')]);
		Session::flash('message', 'Contract type deleted successfully.');
		return redirect('contra_type');
	}
	
	public function checkType() {

		if(Input::get('id') != '')
			$check = DB::table('contra_type')->where('buildingid',Input::get('buildingid'))->where('id', '!=', Input::get('id'))->count();
		else
			$check = DB::table('contra_type')->where('buildingid',Input::get('buildingid'))->count();
		
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
	
	public function getDetails($bid) {
		
		$row = DB::table('contra_type')
								->join('buildingmaster AS B', 'B.id', '=', 'contra_type.buildingid')
								->leftJoin('account_master AS M1', 'M1.id', '=', 'contra_type.prepaid_income')
								->leftJoin('account_master AS M2', 'M2.id', '=', 'contra_type.deposit')
								->leftJoin('account_master AS M3', 'M3.id', '=', 'contra_type.water_ecty')
								->leftJoin('account_master AS M4', 'M4.id', '=', 'contra_type.commission')
								->leftJoin('account_master AS M5', 'M5.id', '=', 'contra_type.other_deposit')
								->leftJoin('account_master AS M6', 'M6.id', '=', 'contra_type.parking')
								->leftJoin('account_master AS M7', 'M7.id', '=', 'contra_type.ejarie_fee')
								->leftJoin('account_master AS M8', 'M8.id', '=', 'contra_type.cancellation')
								->leftJoin('account_master AS M9', 'M9.id', '=', 'contra_type.repair')
								->leftJoin('account_master AS M10', 'M10.id', '=', 'contra_type.water_ecty_bill')
								->leftJoin('account_master AS M11', 'M11.id', '=', 'contra_type.closing_oth')
								->leftJoin('account_master AS M12', 'M12.id', '=', 'contra_type.booking_oth')
								->leftJoin('account_master AS M13', 'M13.id', '=', 'contra_type.chq_charge')
								->leftJoin('account_master AS M14', 'M14.id', '=', 'contra_type.rental_income')
								->select('M1.master_name AS acname1','M2.master_name AS acname2','M3.master_name AS acname3','M4.master_name AS acname4',
										'M5.master_name AS acname5','M6.master_name AS acname6','M7.master_name AS acname7','M8.master_name AS acname8',
										'M9.master_name AS acname9','M10.master_name AS acname10','M11.master_name AS acname11','M12.master_name AS acname12',
										'M13.master_name AS acname13','M14.master_name AS acname14','contra_type.type','contra_type.increment_no','B.ownername',
										'B.location','B.mobno','B.plot_no','contra_type.*','B.prefix') //JN1
								->where('contra_type.buildingid',$bid)->first();
								
		echo json_encode($row);
	}
	
	
	public function getFlat($bid) {
		
		$flats = DB::table('flat_master')->where('building_id',$bid)->where('deleted_at',null)
						->whereNotIn('id', DB::table('contract_building')->where('status',1)->where('is_close',0)->where('deleted_at',null)->pluck('flat_no'))
						->select('id','flat_no')->get();
		echo json_encode($flats);
	}
	
	public function addSettings() {

		$data = array();
		$buildingmaster = DB::table('buildingmaster')->where('deleted_at',null)->get();
		
		return view('body.contratype.add-settings')
	            	->withBuildingmaster($buildingmaster)
					->withData($data);
	}
	
	public function saveSettings(Request $request) {
		//echo '<pre>';print_r(Input::all());exit;
		try {
			$input = $request->all();
			$id = DB::table('contract_type_re')
					->insertGetId([
						'building_id' => $request->get('buildingid'),
						'type' => $request->get('type'),
						'increment_no' => ($request->get('increment_no')=='')?100:$request->get('increment_no'),
						'created_at' => date('Y-m-d H:i:s')
					]);
					
			foreach($input['title'] as $k => $val) {
				DB::table('contract_type_settings')
					->insert([
						'contract_type_re_id' => $id,
						'title' => $input['title'][$k],
						'account_id' => $input['accountid'][$k],
						'is_tax' => $input['istax'][$k]
					]);
			}
			
			Session::flash('message', 'Contract type added successfully.');
			return redirect('contra_type/add-settings');
		} catch(ValidationException $e) { 
			return Redirect::to('contra_type/list-settings')->withErrors($e->getErrors());
		}
	}

	public function editSettings($id) {

		$data = array();
		$cotrare = DB::table('contract_type_re')->join('buildingmaster AS B', 'B.id', '=', 'contract_type_re.building_id')
		             ->join('contract_type_settings AS CTS', 'CTS.contract_type_re_id', '=', 'contract_type_re.id')
		             ->join('account_master AS AM', 'AM.id', '=', 'CTS.account_id')
					 ->where('CTS.deleted_at',null)
		             ->select('contract_type_re.*','B.buildingcode','CTS.contract_type_re_id','CTS.id As rid','CTS.title','CTS.account_id','CTS.is_tax','AM.master_name')
					 ->where('contract_type_re.id',$id)
					 ->get();
		$buildingmaster = DB::table('buildingmaster')->where('deleted_at',null)->get();
		//echo '<pre>';print_r($cotrare);exit;
		return view('body.contratype.edit-settings')
	            	->withBuildingmaster($buildingmaster)
					->withContrare($cotrare)
					->withData($data);
	}

	public function updateSettings(Request $request,$id) {
		//echo '<pre>';print_r(Input::all());exit;
			$input = $request->all();
			$id=$request->get('idr');
			//echo '<pre>';print_r($input);exit;
			$ids = DB::table('contract_type_re')->where('id',$id)
					->update([
						'building_id' => $request->get('buildingid'),
						'type' => $request->get('type'),
						'increment_no' => ($request->get('increment_no')=='')?100:$request->get('increment_no'),
						'created_at' => date('Y-m-d H:i:s')
					]);
					
			foreach($input['settings_id'] as $k => $val) {
				if($val!='') {
				DB::table('contract_type_settings')->where('id',$val)
					->update([
						'contract_type_re_id' => $id,
						'title' => $input['title'][$k],
						'account_id' => $input['accountid'][$k],
						'is_tax' => $input['istax'][$k]
					]);
			} else{
				DB::table('contract_type_settings')
				->insert([
					'contract_type_re_id' => $id,
					'title' => $input['title'][$k],
					'account_id' => $input['accountid'][$k],
					'is_tax' => $input['istax'][$k]
				]);

			}
			
			if($input['remitem']!='') {
				$exar = explode(',',$input['remitem']);
				DB::table('contract_type_settings')->whereIn('id',$exar)->update(['deleted_at' => date('Y-m-d h:i:s')]);
			}
		}
			
			Session::flash('message', 'Contract type updated successfully.');
			return redirect('contra_type/list-settings');
		
	}
	public function listSettings() {

		$data = array();
		$cotrare = DB::table('contract_type_re')->join('buildingmaster AS B', 'B.id', '=', 'contract_type_re.building_id')
		             //->join('contract_type_settings AS CTS', 'CTS.contract_type_re_id', '=', 'contract_type_re.id')
		             ->select('contract_type_re.*','B.buildingcode')
					 ->where('contract_type_re.deleted_at',null)
					 ->get();
		//$buildingmaster = DB::table('buildingmaster')->where('deleted_at',null)->get();
		//echo '<pre>';print_r($cotrare);exit;
		return view('body.contratype.list-settings')
	            	//->withBuildingmaster($buildingmaster)
					->withContrare($cotrare)
					->withData($data);
	}

	public function destroySettings($id)
	{
	   //echo '<pre>';print_r($id );exit;
		DB::table('contract_type_re')->where('id',$id)->update(['deleted_at' => date('Y-m-d H:i:s')]);
		Session::flash('message', 'Contract Type deleted successfully.');
		return redirect('contra_type/list-settings');
	}
}

