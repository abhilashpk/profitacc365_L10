<?php
namespace App\Http\Controllers;
use App\Repositories\Area\AreaInterface;
use App\Repositories\AccountMaster\AccountMasterInterface;
use App\Repositories\Country\CountryInterface;
use App\Repositories\Acgroup\AcgroupInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Http\Requests;
use Input;
use Session;
use Response;
use DB;
use App;
use Image;
use PDF;
use Config;
use Auth;

class TenantMasterController extends Controller
{
    protected $buildingmaster;
	protected $accountmaster;
	protected $country;
	protected $area;
	protected $group;
	
	public function __construct(AreaInterface $area,AccountMasterInterface $accountmaster,CountryInterface $country,AcgroupInterface $group) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->middleware('auth');
		$config = Config::get('siteconfig');
		$this->width = $config['modules']['joborder']['image_size']['width'];
        $this->height = $config['modules']['joborder']['image_size']['height'];
        $this->thumbWidth = $config['modules']['joborder']['thumb_size']['width'];
        $this->thumbHeight = $config['modules']['joborder']['thumb_size']['height'];
        $this->imgDir = $config['modules']['joborder']['image_dir'];

		$this->accountmaster = $accountmaster;
		$this->country = $country;
		$this->area = $area;
		$this->group = $group;
	}
	
	public function index() {
		$data = array();
		$tenantmaster = DB::table('account_master')
						->select('account_master.*')->where('account_master.deleted_at','0000-00-00 00:00:00')
						->where('account_master.nationality','IND')
						->get();
		return view('body.tenantmaster.index')
					->withtenantmaster($tenantmaster)
					->withData($data);
	}
	public function ajax_getcode($category)
	{
		$group = $this->group->getGroupCode($category);
		$row = $this->accountmaster->getGroupCode($category);
		if($row)
			$no = intval(preg_replace('/[^0-9]+/', '', $row->account_id), 10);
		else 
			$no = 0;
		
		$no++;
		
		return json_encode(array(
							'code' => strtoupper($group->code).''.$no,
							'category' => $group->category
							));
		//return $code = strtoupper($group->code).''.$no;
		//return $group->account_id;

	}
	public function add() {

		$data = array();
		$country = $this->country->activeCountryList();
		$area = $this->area->activeAreaList();
		$cus_code = json_decode($this->ajax_getcode($category='CUSTOMER'));
		//echo '<pre>';print_r($building_ids);exit;
		return view('body.tenantmaster.add')
		               ->withArea($area)
					   ->withCountry($country)
					   ->withCusid($cus_code->code)
					   ->withCategory($cus_code->category);
	}

	
	private function create_account($attributes)
	{
		$tenant = DB::table('account_master')
						->insertGetId([
					'master_name' => $attributes['customer_name'], 
					'transaction_type' => ($attributes['category']=='CUSTOMER')?'Dr':'Cr',
					'address' => $attributes['address'],
					'country_id' =>$attributes['country_id'],
					'area_id' =>$attributes['area_id'],
					'phone' =>$attributes['phone'],
					'email' =>$attributes['email'],
					'created_at' =>date('Y-m-d H:i:s'),
					'created_by' =>Auth::User()->id,
					'status' =>1,
					'category' =>$attributes['category'],
					'reference' =>$attributes['ejarie'],
					'passport_no' =>$attributes['passport_no'],
					'passport_exp' =>date('Y-m-d', strtotime($attributes['passport_exp'])),
					'nationality' =>$attributes['nationality'],		
						]);

							
						return $tenant;

	}
	public function save(Request $request) {
		//echo '<pre>';print_r(Input::all());exit;
		try {
		   
			$tenant = $this->create_account(Input::all());
			//echo '<pre>';print_r($tenant);exit;
			DB::table('account_transaction')
						->insert([  'voucher_type' 		=> 'OB',
									'voucher_type_id'   => $tenant,
									'account_master_id' => $tenant,
									'transaction_type'  => ($request['category']=='CUSTOMER')?'Dr':'Cr',
									'amount'   			=> 0,
									'status' 			=> 1,
									'created_at' 		=> date('Y-m-d H:i:s'),
									'created_by' 		=> Auth::User()->id,
									'description' 		=> '',
									'reference'			=> '',
									//'invoice_date'		=> $settings->from_date,
									//'department_id'		=> isset($attributes['department_id'])?$attributes['department_id']:''
								]);
								DB::table('account_master')->where('id', $tenant)->update(['account_id' => 'ACM'.$tenant]);

            $phot = Input::get('photo_name');
           if($tenant  && isset($phot)) {
				$photos = explode(',',$phot);
				
				foreach($photos as $photo) {
					if($photo!='')
						DB::table('tenant_docs')->insert(['tenant_id' => $tenant, 'photo' => $photo]);
				}
			}	
			Session::flash('message', 'Tenant master added successfully.');
			return redirect('tenantmaster/add');
		} catch(ValidationException $e) { 
			return Redirect::to('tenantmaster/add')->withErrors($e->getErrors());
		}
	}
	
	public function edit($id) { 

		$data = array();
		$tenant = $tenantmaster = DB::table('account_master')
		->select('account_master.*')->where('account_master.deleted_at','0000-00-00 00:00:00')
		->where('account_master.id',$id)
		->first();
		$country = $this->country->activeCountryList();
		$area = $this->area->activeAreaList();
		$cus_code = json_decode($this->ajax_getcode($category='CUSTOMER'));	
		$photos = DB::table('tenant_docs')->where('tenant_id',$id)->get(); 
		$val = '';
		foreach($photos as $row) {
			$val .= ($val=='')?$row->photo:','.$row->photo;
		}		
		//echo '<pre>';print_r($country);exit;
		return view('body.tenantmaster.edit')
		            ->withArea($area)
		            ->withCountry($country)
		            ->withCusid($cus_code->code)
		            ->withCategory($cus_code->category)
					->withPhotos($val)
					->withTenant($tenant);
	}
	
	public function update(Request $request, $id)
	{
		//echo '<pre>';print_r(Input::all());exit;
		$image = '';
		
		DB::table('account_master')->where('id',$id)
				->update([
					'master_name' => $request->get('customer_name'),
					'transaction_type' => ($request['category']=='CUSTOMER')?'Dr':'Cr',
					'address' => $request->get('address'),
					'phone' => $request->get('phone'),
					'email' => $request->get('email'),
					'country_id' => $request->get('country_id'),
					'area_id' => $request->get('area_id'),
					'created_at' =>date('Y-m-d H:i:s'),
					'created_by' =>Auth::User()->id,
					'status' =>1,
					'category' =>$request->get('category'),
					'reference' =>$attributes['ejarie'],
					'passport_no' =>$request->get('passport_no'),
					'passport_exp' =>date('Y-m-d', strtotime($request->get('passport_exp'))),
					'nationality' =>$request->get('nationality'),	
				]);
				$id=$request->get('id');
				DB::table('account_transaction')->where('id',$id)
						->update([  'voucher_type' 		=> 'OB',
									'voucher_type_id'   => $id,
									'account_master_id' => $id,
									'transaction_type'  => ($request['category']=='CUSTOMER')?'Dr':'Cr',
									'amount'   			=> 0,
									'status' 			=> 1,
									'created_at' 		=> date('Y-m-d H:i:s'),
									'created_by' 		=> Auth::User()->id,
									'description' 		=> '',
									'reference'			=> '',
									//'invoice_date'		=> $settings->from_date,
									//'department_id'		=> isset($attributes['department_id'])?$attributes['department_id']:''
								]);
				$photos = [];
		$phoo =Input::get('photo_name');
		$old_pho = Input::get('old_photo_name');
		$rem_phot = Input::get('rem_photo_name');
				if(isset($phoo)) {
					$photos = explode(',',Input::get('photo_name'));
				}
				
				//Update photos...Input::get('old_photo_name')
				if(isset($old_pho) && $old_pho!='') {
					
					$exi_photos = explode(',',Input::get('old_photo_name'));
					
					foreach($photos as $ky => $val) {
						if(isset($exi_photos[$ky])) {
							if($val!='') {
								DB::table('tenant_docs')
										->where('tenant_id', $id)
										->where('photo', $photos[$ky])
										->update(['photo' => $val]);
							}
						} else {
							DB::table('tenant_docs')->insert(['tenant_id' => $id, 'photo' => $val]);
						}
					}
					
				} else { //Add photos
					foreach($photos as $photo) {
						if($photo!='')
							DB::table('tenant_docs')->insert(['tenant_id' => $id, 'photo' => $photo]);
					}
				}
				
				
				//Remove photos Input::get('rem_photo_name')
				if(isset($rem_phot)) {
					$rem_photos = explode(',',Input::get('rem_photo_name'));
					foreach($rem_photos as $photo) {
						DB::table('tenant_docs')->where('tenant_id',$id)
									->where('photo', $photo)
									->delete();
									
						$fPath = public_path() . $this->imgDir.'/'.$photo;
						File::delete($fPath);
					}
				}
		Session::flash('message', 'Tenant master updated successfully');
		return redirect('tenantmaster');
	}
	
	public function destroy($id)
	{
	   // echo '<pre>';print_r($id );exit;
		DB::table('account_master')->where('id',$id)->update(['deleted_at' => date('Y-m-d H:i:s')]);
		Session::flash('message', 'Tenant master deleted successfully.');
		return redirect('tenantmaster');
	}
	
	
	public function checkcode() {

		if(Input::get('id') != '')
			$check = DB::table('flat_master')->where('flat_no',Input::get('flat_no'))->where('building_id',Input::get('bid'))->where('id', '!=', Input::get('id'))->count();
		else
			$check = DB::table('flat_master')->where('flat_no',Input::get('flat_no'))->where('building_id',Input::get('bid'))->count();
		
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
	
	
}
