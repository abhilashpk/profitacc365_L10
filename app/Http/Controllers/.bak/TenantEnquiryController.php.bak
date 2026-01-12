<?php
namespace App\Http\Controllers;
use App\Repositories\VoucherNo\VoucherNoInterface;
use App\Repositories\AccountMaster\AccountMasterInterface;
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

class TenantEnquiryController extends Controller
{
    protected $buildingmaster;
	protected $accountmaster;
	protected $voucherno;
	
	
	public function __construct(AccountMasterInterface $accountmaster,VoucherNoInterface $voucherno) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->middleware('auth');
		$config = Config::get('siteconfig');
		
		$this->accountmaster = $accountmaster;
		$this->voucherno = $voucherno;
		
	}
	
	public function index() {
		$data = array();
		$tenantenquiry = DB::table('tenant_enquiry')
		                ->join('buildingmaster AS B','B.id','=','tenant_enquiry.building_id')
						->leftjoin('flat_master AS F','F.id','=','tenant_enquiry.flat_no')
						->where('tenant_enquiry.status',0)
						->select('tenant_enquiry.*','B.buildingcode','F.flat_no as flatno')->where('tenant_enquiry.deleted_at',NULL)
						->orderBy('tenant_enquiry.id','desc')
						->get();
						//echo '<pre>';print_r($tenantenquiry);exit;
						
		$tenantenquirycom = DB::table('tenant_enquiry')
		                ->join('buildingmaster AS B','B.id','=','tenant_enquiry.building_id')
						->leftjoin('flat_master AS F','F.id','=','tenant_enquiry.flat_no')
						->where('tenant_enquiry.status',1)
						->select('tenant_enquiry.*','B.buildingcode','F.flat_no as flatno')->where('tenant_enquiry.deleted_at',NULL)
						->orderBy('tenant_enquiry.id','desc')
						->get();
						
		return view('body.tenantenquiry.index')
					->withPendingenq($tenantenquiry)
					->withCompleted($tenantenquirycom)
					->withData($data);
	}
		
	public function add() {

		$data = array();
		$building = DB::table('buildingmaster')->where('deleted_at',null)->select('buildingcode','id')->get();
		$flat = DB::table('flat_master')->where('deleted_at',null)->select('flat_no','id')->get();
		//$tenants = DB::table('account_master')->where('category','CUSTOMER')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('master_name','id')->get();
		$res = $this->voucherno->getVoucherNo('CE');
		$vno = $res->no;
		//echo '<pre>';print_r($building_ids);exit;
		return view('body.tenantenquiry.add')
					   ->withBuilding($building)
					   ->withVoucherno($vno)
					   ->withFlat($flat);
	}

	
	
	public function save(Request $request) {
		//echo '<pre>';print_r(Input::all());exit;
		try {
		$id=DB::table('tenant_enquiry')
			->insertGetId([
				'enquiry_no' => Input::get('voucher_no'),
				'enquiry_date' => date('Y-m-d', strtotime(Input::get('enq_date'))),
				'building_id' => Input::get('building_id'),
				'flat_no'     => isset($request['flat_no'])?$request['flat_no']:'',
				'tenant'     => Input::get('customer_account'),
				'description'     => Input::get('description'),
				'tenant_id'     => Input::get('customer_id')
			]);
			
			//echo '<pre>';print_r($id);exit;

			if($request['photo_name']!='') {
				foreach($request['photo_name'] as $key => $val) {
					if($val!='') {
						DB::table('enquiry_photos')
								->insert(['enquiry_id' => $id, 
										  'photo' => $val,
										  'description'	=> $request['imgdesc'][$key]
										]);
					}
				}
			}

			DB::table('voucher_no')
			->where('voucher_type', 'CE')
			->update(['no' => DB::raw('no + 1')]);
           
			Session::flash('message', 'Enquiry added successfully.');
			return redirect('tenantenquiry/add');
		} catch(ValidationException $e) { 
			return Redirect::to('tenantenquiry/add')->withErrors($e->getErrors());
		}
	}
	
	public function edit($id) { 

		$data = array();
		$tenantenquiry = DB::table('tenant_enquiry')
		                ->join('buildingmaster AS B','B.id','=','tenant_enquiry.building_id')
						->leftjoin('flat_master AS F','F.id','=','tenant_enquiry.flat_no')
						->select('tenant_enquiry.*','B.buildingcode','F.flat_no as flatno')->where('tenant_enquiry.id',$id)
						->first();	
		$building = DB::table('buildingmaster')->where('deleted_at',null)->select('buildingcode','id')->get();
		$flat = DB::table('flat_master')->where('deleted_at',null)->select('flat_no','id')->get();	
		$photos = DB::table('enquiry_photos')->where('enquiry_id',$id)->get(); 				
		//echo '<pre>';print_r($photos);exit;
		return view('body.tenantenquiry.edit')
		            ->withEnquiry($tenantenquiry)
					->withBuilding($building)
					->withFlat($flat)
					->withPhotos($photos);
	}
	
	public function update(Request $request, $id)
	{
		//echo '<pre>';print_r(Input::all());exit;
		$ids=$request->get('id');
		//echo '<pre>';print_r($ids);exit;
		DB::table('tenant_enquiry')->where('id',$id)
				->update([
					'enquiry_no' => $request->get('voucher_no'),
					'enquiry_date' => date('Y-m-d', strtotime($request->get('enq_date'))),
					'building_id' => $request->get('building_id'),
					'flat_no' => isset($request['flat_no'])?$request['flat_no']:'',
					'tenant' => $request->get('customer_account'),
					'description' => $request->get('description'),
					'tenant_id'     => Input::get('customer_id')	
				]);
				if(isset($request['photo_id'])) {
					
					foreach($request['photo_id'] as $key => $val) {
						
						//UPDATE...
						if($val!='') {
							DB::table('enquiry_photos')
								->where('id', $val)
								->update(['photo' =>  $request['photo_name'][$key],
										  'description'	=> $request['imgdesc'][$key]
										]);
										
						} else { 
							//ADD NEW..
							DB::table('enquiry_photos')
								->insert(['enquiry_id' => $ids, 
										  'photo' => $request['photo_name'][$key],
										  'description'	=> $request['imgdesc'][$key]
										]);
						}
					}
				}
						
		Session::flash('message', 'Enquiry updated successfully');
		return redirect('tenantenquiry');
	}
	
	public function destroy($id)
	{
	   // echo '<pre>';print_r($id );exit;
		DB::table('tenant_enquiry')->where('id',$id)->update(['deleted_at' => date('Y-m-d H:i:s')]);
		Session::flash('message', 'Enquiry deleted successfully.');
		return redirect('tenantenquiry');
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

