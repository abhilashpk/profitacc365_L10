<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Http\Requests;
use Session;
use Response;
use DB;
use App;
use Image;
use PDF;
use Config;

class FlatMasterController extends Controller
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
		$data = array();
		$flatmaster = DB::table('flat_master')->join('buildingmaster','buildingmaster.id','=','flat_master.building_id')
						->select('flat_master.*','buildingmaster.buildingcode','buildingmaster.buildingname')->where('flat_master.deleted_at',null)->get();
		return view('body.flatmaster.index')
					->withFlatmaster($flatmaster)
					->withData($data);
	}
	
	public function add() {

		$data = array();
		
		$buildingmaster = DB::table('buildingmaster')->where('deleted_at',null)->get();
		
			$building_ids = (Session::has('building'))?Session::put('building'):'';
		//echo '<pre>';print_r($building_ids);exit;
		return view('body.flatmaster.add')
		            ->withBuid($building_ids)
		            ->withBuild(Session::put('building'))
	            	->withBuildingmaster($buildingmaster);
	}
	private function create_account($attributes)
	{
		$buid_id = DB::table('flat_master')
						->insertGetId([
							'building_id' => $attributes['building_id'], 
					'flat_no' => $attributes['flat_no'],
					'flat_name' => $attributes['flat_name'],
					'description' =>$attributes['description'],
							
						]);

							
						return $buid_id;

	}

	public function save(Request $request) {
		try {
		    Session::put('building',$request->get('building_id'));
			$buidid = $this->create_account($request->all());
            $phot = $request->get('photo_name');
           if($buidid  && isset($phot)) {
				$photos = explode(',',$phot);
				
				foreach($photos as $photo) {
					if($photo!='')
						DB::table('flat_photos')->insert(['flat_id' => $buidid, 'photo' => $photo]);
				}
			}	
			Session::flash('message', 'Flat master added successfully.');
			return redirect('flatmaster/add');
		} catch(ValidationException $e) { 
			return Redirect::to('flatmaster/add')->withErrors($e->getErrors());
		}
	}
	
	public function edit($id) { 

		$data = array();
		$frow = DB::table('flat_master')->join('buildingmaster','buildingmaster.id','=','flat_master.building_id')->where('flat_master.id',$id)
					->select('flat_master.*','buildingmaster.buildingcode','buildingmaster.buildingname')->first();
		$buildingmaster = DB::table('buildingmaster')->where('deleted_at',null)->get();	
		$photos = DB::table('flat_photos')->where('flat_id',$id)->get(); 
		$val = '';// echo '<pre>';print_r($row);exit;
		foreach($photos as $row) {
			$val .= ($val=='')?$row->photo:','.$row->photo;
		}		
		return view('body.flatmaster.edit')
					->withBuildingmaster($buildingmaster)
					->withPhotos($val)
					->withFrow($frow);
	}
	
	public function update(Request $request, $id)
	{
		$image = '';
		//echo '<pre>';print_r($request['docname']);exit;
		// $docname = '';
		
        //        	$image = $request->get('current_image'); $width = 730; $height = 290;
	    //  		$file = ($request->hasFile('image'))?$request->file('image'):null;
		// 	if($file) {
		// 		$ext = $file->getClientOriginalExtension();
		// 		if($ext=='.jpg' ||$ext=='.JPG' || $ext=='.png' || $ext=='.PNG') {
		// 			$image = time().'.'.$ext;
		// 			$destinationPath = public_path() . '/uploads/employee_document/'.$image;
		// 			$destinationPathThumb = public_path() . '/uploads/employee_document/thumb_'.$image;

		// 			// resizing an uploaded file
		// 			Image::make($file->getRealPath())->resize($width, $height, function($constraint) { $constraint->aspectRatio(); })->save($destinationPath);

		// 			// thumb
		// 			Image::make($file->getRealPath())->resize(200, 125, function($constraint) { $constraint->aspectRatio(); })->save($destinationPathThumb);
		// 		} else {
		// 			 $image = time().'.'.$ext;
		// 			 echo $destinationPath = public_path() . '/uploads/employee_document/';
		// 			 $file->move($destinationPath, $image);
		// 		}
		// 	}
		DB::table('flat_master')->where('id',$id)
				->update([
					'building_id' => $request->get('building_id'),
					'flat_no' => $request->get('flat_no'),
					'flat_name' => $request->get('flat_name'),
					'description' => $request->get('description'),
					'docname' => $image
				]);
				$photos = [];
		$phoo =$request->get('photo_name');
		$old_pho = $request->get('old_photo_name');
		$rem_phot = $request->get('rem_photo_name');
				if(isset($phoo)) {
					$photos = explode(',',$request->get('photo_name'));
				}
				
				//Update photos...$request->get('old_photo_name')
				if(isset($old_pho) && $old_pho!='') {
					
					$exi_photos = explode(',',$request->get('old_photo_name'));
					
					foreach($photos as $ky => $val) {
						if(isset($exi_photos[$ky])) {
							if($val!='') {
								DB::table('flat_photos')
										->where('flat_id', $id)
										->where('photo', $photos[$ky])
										->update(['photo' => $val]);
							}
						} else {
							DB::table('flat_photos')->insert(['flat_id' => $id, 'photo' => $val]);
						}
					}
					
				} else { //Add photos
					foreach($photos as $photo) {
						if($photo!='')
							DB::table('flat_photos')->insert(['flat_id' => $id, 'photo' => $photo]);
					}
				}
				
				
				//Remove photos $request->get('rem_photo_name')
				if(isset($rem_phot)) {
					$rem_photos = explode(',',$request->get('rem_photo_name'));
					foreach($rem_photos as $photo) {
						DB::table('flat_photos')->where('flat_id',$id)
									->where('photo', $photo)
									->delete();
									
						$fPath = public_path() . $this->imgDir.'/'.$photo;
						File::delete($fPath);
					}
				}
		Session::flash('message', 'Flat master updated successfully');
		return redirect('flatmaster');
	}
	
	public function destroy($id)
	{
	    $isFlat = DB::table('contract_building')->where('flat_no',$id)->whereNull('deleted_at')->select('id')->first();
		if($isFlat) {
			Session::flash('error', 'This flat has associated with contract, therefore it cannot be deleted.');
			return redirect('flatmaster');
		}

		DB::table('flat_master')->where('id',$id)->update(['deleted_at' => date('Y-m-d H:i:s')]);
		Session::flash('message', 'Flat master deleted successfully.');
		return redirect('flatmaster');
	}
	
	
	public function checkcode(Request $request) {

		$query = DB::table('flat_master')->where('flat_no', $request->query('flat_no'))->where('building_id', $request->query('bid'))->whereNull('deleted_at');

		// 👇 ignore current record while editing (optional)
		if ($request->filled('id')) {
			$query->where('id', '!=', $request->query('id'));
		}

		if ($query->exists()) {
			return response()->json("Flat no is not available");
		}

		return response()->json(true);

		/*if($request->get('id') != '')
			$check = DB::table('flat_master')->where('flat_no',$request->get('flat_no'))->whereNull('deleted_at')->where('building_id',$request->get('bid'))->where('id', '!=', $request->get('id'))->count();
		else
			$check = DB::table('flat_master')->where('flat_no',$request->get('flat_no'))->whereNull('deleted_at')->where('building_id',$request->get('bid'))->count();
		
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));*/
	}
	
	
}
