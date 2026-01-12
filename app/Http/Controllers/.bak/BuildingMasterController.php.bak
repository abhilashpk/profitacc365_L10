<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File; 
use App\Http\Requests;
use Input;
use Session;
use Response;
use DB;
use App;
use Image;
use Config;
use Cache;

class BuildingMasterController extends Controller
{
    protected $buildingmaster;
	
	public function __construct() {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->middleware('auth');
		$config = Config::get('siteconfig');
		$this->width = $config['modules']['joborder']['image_size']['width'];
        $this->height = $config['modules']['joborder']['image_size']['height'];
        $this->thumbWidth = $config['modules']['joborder']['thumb_size']['width'];
        $this->thumbHeight = $config['modules']['joborder']['thumb_size']['height'];
        $this->imgDir = $config['modules']['joborder']['image_dir'];
	}
	
	public function index() {
		$buildingmaster = DB::table('buildingmaster')->where('deleted_at',null)->get(); //echo '<pre>';print_r($buildingmaster);exit;
		return view('body.buildingmaster.index')
					->withBuildingmaster($buildingmaster);
	}
	
	public function add() {

		$data = array();
		return view('body.buildingmaster.add')
					->withData($data);
	}
	public function ajax_upload($file)
	{ 
		$photo = '';
		$fname = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
		
		if($file) {
			$ext = $file->getClientOriginalExtension();
			if($ext=='.jpg' ||$ext=='.JPG' || $ext=='.png' || $ext=='.PNG') {
				$photo = rand(1, 999).$fname.'.'.$ext;
				$destinationPath = public_path() . $this->imgDir.'/'.$photo;
				$destinationPathThumb = public_path() . $this->imgDir.'/thumb_'.$photo;

				// resizing an uploaded file
				Image::make($file->getRealPath())->resize($this->width, $this->height, function($constraint) { $constraint->aspectRatio(); })->save($destinationPath);

				// thumb
				Image::make($file->getRealPath())->resize($this->thumbWidth, $this->thumbHeight, function($constraint) { $constraint->aspectRatio(); })->save($destinationPathThumb);
			} else {
				 $photo = rand(1, 999).$fname.'.'.$ext;
				 $destinationPath = public_path() . $this->imgDir;
				 $file->move($destinationPath,$photo);
			}
		}
		
		return $photo;

	}
	public function uploadss(Request $request)
	{	
		$res = $this->ajax_upload($request->photos);
		return response()->json(array('file_name' => $res), 200);
	}
	private function create_account($attributes)
	{
		$buid_id = DB::table('buildingmaster')
						->insertGetId([
							'buildingcode' => $attributes['buildingcode'],
							'buildingname' => $attributes['buildingname'],
							'ownername' => $attributes['ownername'],
							'location' => $attributes['location'],
							'area'	=> $attributes['area'],
							'mobno' => $attributes['mobno'],
							'type' =>(isset($attributes['type']))?$attributes['type']:'', 
							'description'  => $attributes['description'],
							'prefix' => $attributes['prefix'],
							'unit_price' => $attributes['unit_price'],
							'security_deposit' => $attributes['security_deposit'],
							'connection_charge' => $attributes['connection_charge'],
							'other_charge' => $attributes['other_charge'],
							'disconnection_charge' => $attributes['disconnection_charge'],
							'other_charge_dis' => $attributes['other_charge_dis'],
							'other_charge_con' => $attributes['other_charge_con']
						]);

							
						return $buid_id;

	}
	public function save(Request $request) { //
		
	//	echo '<pre>';print_r(Input::all());exit;
		try {
			$buidid = $this->create_account(Input::all());
            $phot = Input::get('photo_name');
           if($buidid  && isset($phot)) {
				$photos = explode(',',$phot);
				
				foreach($photos as $photo) {
					if($photo!='')
						DB::table('bud_photos')->insert(['building_id' => $buidid, 'photo' => $photo]);
				}
			}	
	     Session::flash('message', 'Building master added successfully.');
			return redirect('buildingmaster/add');
		} catch(ValidationException $e) { 
			return Redirect::to('buildingmaster/add')->withErrors($e->getErrors());
		}
	}
	
	public function edit($id) { 

		$data = array();
		$brow = DB::table('buildingmaster')->where('id',$id)->first();
		//echo '<pre>';print_r(docname);exit;
		$photos = DB::table('bud_photos')->where('building_id',$id)->get(); 
		$val = '';
		foreach($photos as $row) {
			$val .= ($val=='')?$row->photo:','.$row->photo;
		}				
		return view('body.buildingmaster.edit')
		             ->withPhotos($val)
					->withBrow($brow);
	}

	public function update(Request $request, $id)
	{	
		
		DB::table('buildingmaster')->where('id',$id)
		               ->update([
			'buildingcode' =>  Input::get('buildingcode'),
			'buildingname' =>   Input::get('buildingname'),
			'ownername' =>  Input::get('ownername'), 
			'location' =>  Input::get('location'),
			'area' =>  Input::get('area'),
			'mobno' => Input::get('mobno'),  
			'type' => Input::get('type'),  
			'description' => Input::get('description'), 
			//'docname' => $image,
			'unit_price' =>  Input::get('unit_price'),
			'prefix' =>  Input::get('prefix'),
			'security_deposit' => Input::get('security_deposit'),
			'connection_charge' => Input::get('connection_charge'),
			'other_charge' => Input::get('other_charge'),
			'disconnection_charge' => Input::get('disconnection_charge'),
			'other_charge_dis' => Input::get('other_charge_dis'),
			'other_charge_con' => Input::get('other_charge_con')
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
								DB::table('bud_photos')
										->where('building_id', $id)
										->where('photo', $photos[$ky])
										->update(['photo' => $val]);
							}
						} else {
							DB::table('bud_photos')->insert(['building_id' => $id, 'photo' => $val]);
						}
					}
					
				} else { //Add photos
					foreach($photos as $photo) {
						if($photo!='')
							DB::table('bud_photos')->insert(['building_id' => $id, 'photo' => $photo]);
					}
				}
				
				
				//Remove photos Input::get('rem_photo_name')
				if(isset($rem_phot)) {
					$rem_photos = explode(',',Input::get('rem_photo_name'));
					foreach($rem_photos as $photo) {
						DB::table('bud_photos')->where('building_id',$id)
									->where('photo', $photo)
									->delete();
									
						$fPath = public_path() . $this->imgDir.'/'.$photo;
						File::delete($fPath);
					}
				}
		Session::flash('messages', 'Building master updated successfully');
		return redirect('buildingmaster');
	}
	
	public function destroy($id)
	{
		DB::table('buildingmaster')->where('id',$id)->update(['deleted_at' => date('Y-m-d H:i:s')]);
		Session::flash('message', 'Building master deleted successfully.');
		return redirect('buildingmaster');
	}
	
	public function checkcode() {

		if(Input::get('id') != '')
			$check = DB::table('buildingmaster')->where('buildingcode',Input::get('buildingcode'))->where('id', '!=', Input::get('id'))->count();
		else
			$check = DB::table('buildingmaster')->where('buildingcode',Input::get('buildingcode'))->count();
		
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
	
	
	public function getPrefix($id) {
		
		$data = DB::table('buildingmaster')->where('id',$id)->where('deleted_at',null)->select('prefix')->first();
		echo ($data)?json_encode(['val' => $data->prefix]):json_encode(['']);
	}
	
	public function getValues($bid) {
		
		$data = DB::table('buildingmaster')->where('id',$bid)->select('security_deposit','connection_charge','unit_price','other_charge','disconnection_charge','other_charge_dis','other_charge_con')->first();
		
		echo json_encode($data);
	}
	
	public function getFlat($bid) {
		
		$flats = DB::table('flat_master')->where('building_id',$bid)->where('deleted_at',null)
						->whereNotIn('id', DB::table('contract_connection')->where('status',1)->where('is_close',0)->where('deleted_at',null)->pluck('flat_id'))
						->select('id','flat_no')->get();
		echo json_encode($flats);
	}
}
