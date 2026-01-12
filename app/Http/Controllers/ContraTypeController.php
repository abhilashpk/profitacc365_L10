<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
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
		$ctype = DB::table('parameter1')->where('id',1)->select('daily_rent')->first();
		$results = DB::table('contra_type')->join('buildingmaster','buildingmaster.id','=','contra_type.buildingid')
						->select('contra_type.*','buildingmaster.buildingcode','buildingmaster.buildingname')->whereNull('contra_type.deleted_at')->get();
						
		return view('body.contratype.index')
					->withResults($results)
					->withCtype(($ctype)?$ctype->daily_rent:0);
	}
	
	public function add() {

		$buildingmaster = DB::table('buildingmaster')->where('deleted_at',null)->get();
		$heads = DB::table('contra_type_head')->pluck('head_text', 'head');
		//print_r($heads);exit;
		return view('body.contratype.add')
	            	->withBuildingmaster($buildingmaster)
					->withHeads($heads);
	}
	
	public function save(Request $request) {

		try {

			$ctype = DB::table('parameter1')->where('id',1)->select('daily_rent')->first();

			DB::table('contra_type')
				->insert([
					'buildingid' => $request->get('buildingid'),
					'type' => $request->get('type'),
					'increment_no' => ($request->get('increment_no')=='')?100:$request->get('increment_no'),
					'prepaid_income'	=> $request->get('prepaid_income'),
					'rental_income'	=> $request->get('rental_income'),
					'deposit'	=> $request->get('deposit'),
					'water_ecty'	=> $request->get('water_ecty'),
					'other_deposit'	=> $request->get('other_deposit'),
					'commission'	=> $request->get('commission'),
					'parking'	=> $request->get('parking'),
					'cancellation'	=> $request->get('cancellation'),
					'repair'	=> $request->get('repair'),
					'water_ecty_bill'	=> $request->get('water_ecty_bill'),
					'closing_oth'	=> $request->get('closing_oth'),
					'booking_oth'	=> $request->get('booking_oth'),
					'chq_charge'	=> $request->get('chq_charge'),
					'ejarie_fee'	=> $request->get('ejarie_fee'),
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
					'ef_tax'	=> $request->get('ef_tax'),
					'daily_rent' => ($ctype)?$ctype->daily_rent:0
				]);

			DB::table('contra_type_head')->where('head','prepaid_income')->update(['head_text'	=> $request->get('txt_prepaid_income')]);
			DB::table('contra_type_head')->where('head','rental_income')->update(['head_text'	=> $request->get('txt_rental_income')]);
			DB::table('contra_type_head')->where('head','commission')->update(['head_text'	=> $request->get('txt_commission')]);
			DB::table('contra_type_head')->where('head','parking')->update(['head_text'	=> $request->get('txt_parking')]);
			DB::table('contra_type_head')->where('head','cancellation')->update(['head_text'	=> $request->get('txt_cancellation')]);
			DB::table('contra_type_head')->where('head','repair')->update(['head_text'	=> $request->get('txt_repair')]);
			DB::table('contra_type_head')->where('head','water_ecty_bill')->update(['head_text'	=> $request->get('txt_water_ecty_bill')]);
			DB::table('contra_type_head')->where('head','closing_oth')->update(['head_text'	=> $request->get('txt_closing_oth')]);
			DB::table('contra_type_head')->where('head','booking_oth')->update(['head_text'	=> $request->get('txt_booking_oth')]);
			DB::table('contra_type_head')->where('head','chq_charge')->update(['head_text'	=> $request->get('txt_chq_charge')]);
			DB::table('contra_type_head')->where('head','ejarie_fee')->update(['head_text'	=> $request->get('txt_ejarie_fee')]);
			DB::table('contra_type_head')->where('head','deposit')->update(['head_text'	=> $request->get('txt_deposit')]);
			DB::table('contra_type_head')->where('head','water_ecty')->update(['head_text'	=> $request->get('txt_water_ecty')]);
			DB::table('contra_type_head')->where('head','other_deposit')->update(['head_text'	=> $request->get('txt_other_deposit')]);


			Session::flash('message', 'Contract type added successfully.');
			return redirect('contra_type/add');
		} catch(ValidationException $e) { 
			return Redirect::to('contra_type/add')->withErrors($e->getErrors());
		}
	}
	
	public function edit($id) { 

		$buildingmaster = DB::table('buildingmaster')->where('deleted_at',null)->get();
		$row = DB::table('contra_type')->where('id',$id)->first();
		$heads = DB::table('contra_type_head')->pluck('head_text', 'head');
						
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
					->withHeads($heads)
					->withCrow($row);
	}
	
	public function update(Request $request, $id)
	{
		$ctype = DB::table('parameter1')->where('id',1)->select('daily_rent')->first();
		DB::table('contra_type')->where('id',$id)
				->update([
					'buildingid' => $request->get('buildingid'),
					'type' => $request->get('type'),
					'increment_no' => ($request->get('increment_no')=='')?100:$request->get('increment_no'),
					'prepaid_income'	=> $request->get('prepaid_income'),
					'rental_income'	=> $request->get('rental_income'),
					'deposit'	=> $request->get('deposit'),
					'water_ecty'	=> $request->get('water_ecty'),
					'other_deposit'	=> $request->get('other_deposit'),
					'commission'	=> $request->get('commission'),
					'parking'	=> $request->get('parking'),
					'cancellation'	=> $request->get('cancellation'),
					'repair'	=> $request->get('repair'),
					'water_ecty_bill'	=> $request->get('water_ecty_bill'),
					'closing_oth'	=> $request->get('closing_oth'),
					'booking_oth'	=> $request->get('booking_oth'),
					'chq_charge'	=> $request->get('chq_charge'),
					'ejarie_fee'	=> $request->get('ejarie_fee'),
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
					'ef_tax'	=> $request->get('ef_tax'),
					'daily_rent' => ($ctype)?$ctype->daily_rent:0
				]);

			DB::table('contra_type_head')->where('head','prepaid_income')->update(['head_text'	=> $request->get('txt_prepaid_income')]);
			DB::table('contra_type_head')->where('head','rental_income')->update(['head_text'	=> $request->get('txt_rental_income')]);
			DB::table('contra_type_head')->where('head','commission')->update(['head_text'	=> $request->get('txt_commission')]);
			DB::table('contra_type_head')->where('head','parking')->update(['head_text'	=> $request->get('txt_parking')]);
			DB::table('contra_type_head')->where('head','cancellation')->update(['head_text'	=> $request->get('txt_cancellation')]);
			DB::table('contra_type_head')->where('head','repair')->update(['head_text'	=> $request->get('txt_repair')]);
			DB::table('contra_type_head')->where('head','water_ecty_bill')->update(['head_text'	=> $request->get('txt_water_ecty_bill')]);
			DB::table('contra_type_head')->where('head','closing_oth')->update(['head_text'	=> $request->get('txt_closing_oth')]);
			DB::table('contra_type_head')->where('head','booking_oth')->update(['head_text'	=> $request->get('txt_booking_oth')]);
			DB::table('contra_type_head')->where('head','chq_charge')->update(['head_text'	=> $request->get('txt_chq_charge')]);
			DB::table('contra_type_head')->where('head','ejarie_fee')->update(['head_text'	=> $request->get('txt_ejarie_fee')]);
			DB::table('contra_type_head')->where('head','deposit')->update(['head_text'	=> $request->get('txt_deposit')]);
			DB::table('contra_type_head')->where('head','water_ecty')->update(['head_text'	=> $request->get('txt_water_ecty')]);
			DB::table('contra_type_head')->where('head','other_deposit')->update(['head_text'	=> $request->get('txt_other_deposit')]);

		Session::flash('message', 'Contract type updated successfully');
		return redirect('contra_type');
	}
	
	public function destroy($id)
	{ //echo $id;exit;
		$res = DB::table('contra_type')->where('id',$id)->select('buildingid')->first();
		if($res) {
			$con = DB::table('contract_building')->where('building_id',$res->buildingid)->whereNull('deleted_at')->select('id')->first();
			if($con) {
				Session::flash('error', 'This contract type is already in use and cannot be deleted.');
			} else {
				DB::table('contra_type')->where('id',$id)->update(['deleted_at' => date('Y-m-d H:i:s')]);
				Session::flash('message', 'Contract type deleted successfully.');
			}
		}
		return redirect('contra_type');
	}
	
	public function checkType(Request $request) {

		$query = DB::table('contra_type')->where('buildingid', $request->query('buildingid'))->whereNull('deleted_at');

		// 👇 ignore current record during edit
		if ($request->filled('id')) {
			$query->where('id', '!=', $request->query('id'));
		}

		if ($query->exists()) {
			// MUST return string for jQuery Validate
			return response()->json("Contract type of this building is already created");
		}
		
		return response()->json(true);
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
										'B.location','B.mobno','B.plot_no','contra_type.*','B.prefix')
								->where('contra_type.buildingid',$bid)->whereNull('contra_type.deleted_at')->first();
								
		echo json_encode($row);
	}
	
	
	public function getFlat($bid) {
		
		$flats = DB::table('flat_master')->where('building_id',$bid)->where('deleted_at',null)
						->whereNotIn('id', DB::table('contract_building')->where('status',1)->where('is_close',0)->where('deleted_at',null)->pluck('flat_no'))
						->select('id','flat_no')->get(); //echo '<pre>';print_r($flats);exit;
		echo json_encode($flats);
	}

	public function updateOptDailyRent(Request $request)
	{
		$optRent = $request->input('opt_rent');

		DB::table('parameter1')->where('id',1)
			->update([
				'daily_rent' => $optRent,
			]);

		return response()->json([
			'status' => 'success'
		]);
	}
	
}

