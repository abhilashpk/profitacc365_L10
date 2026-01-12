<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Input;
use Session;
use Response;
use DB;
use App;
use Config;
use Auth;
use File;
use Excel;

class ItemTemplateController extends Controller
{

	public function __construct() {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->middleware('auth');
		$config = Config::get('siteconfig');
		$this->widthC = $config['modules']['sojob']['image_size']['width'];
        $this->heightC = $config['modules']['sojob']['image_size']['height'];
        $this->thumbWidthC = $config['modules']['sojob']['thumb_size']['width'];
        $this->thumbHeightC = $config['modules']['sojob']['thumb_size']['height'];
        $this->imgDirC = $config['modules']['sojob']['image_dir'];
		
	}
	
	public function index() {
				
		$temlates = DB::table('item_template')
						->join('itemmaster','itemmaster.id','=','item_template.item_id')
						->where('item_template.deleted_at',null)
						->select('itemmaster.description','itemmaster.item_code','item_template.item_id')
						->groupBy('item_template.item_id')
						->get();
				
		return view('body.itemtemplate.index')->withTemplates($temlates);
	}
	
	
	public function add() {

		$items = DB::table('itemmaster')->where('status',1)
						->where('deleted_at','0000-00-00 00:00:00')
						->where('class_id',2)
						//->whereNotIn('id', DB::table('item_template')->where('deleted_at',null)->pluck('item_id')->get())
						->whereNotIn('id', function($query){
						   $query->select('item_id')->from('item_template')->where('deleted_at',null);
						})
						->select('id','item_code','description')
						->get();
						
		$group = DB::table('groupcat')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','description')->get();
		$units = DB::table('units')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','unit_name')->get();
		//echo '<pre>';print_r($lastid);exit;
		return view('body.itemtemplate.add')
					->withGroup($group)
					->withUnits($units)
					->withItems($items);
	}
	
	public function save(Request $request) {
		$attributes = $request->all(); //echo '<pre>';print_r($attributes);//exit;
		DB::beginTransaction();
		try {
			foreach($attributes['input_type'] as $key => $val) {
				DB::table('item_template')
					->insert([
						'item_id' => $attributes['item_id'],
						'type_name' => $attributes['type_name'][$key],
						'input_type'	=> $attributes['input_type'][$key],
						'is_stock'	=> isset($attributes['is_stockitm'][$key])?$attributes['is_stockitm'][$key]:0,
						'unit_id'	=> isset($attributes['unit'][$key])?$attributes['unit'][$key]:0,
						'group_id'	=> isset($attributes['group'][$key])?$attributes['group'][$key]:0,
						'is_dimension'	=> isset($attributes['dimension'][$key])?$attributes['dimension'][$key]:0,
						'order_no'	=> $attributes['ordno'][$key],
						'is_required'	=> $attributes['required'][$key]
						//'frame_no'	=> $attributes['frameno'][$key]
					]);
				
			}
				
			DB::commit();
			Session::flash('message', 'Item Template added successfully.');
			return redirect('item_template/add');
			
		} catch(\Exception $e) {
			DB::rollback(); echo $e->getLine().' '.$e->getMessage();exit;
			return Redirect::to('item_template/add')->withErrors($e->getErrors());
		}
	}
	
	public function getTemplate($id,$no) {
		
		$template = DB::table('item_template')->where('item_template.item_id',$id)
					->where('item_template.deleted_at',null)
					->leftJoin('units','units.id','=','item_template.unit_id')
					->select('item_template.*','units.unit_name')
					->orderBy('item_template.order_no','ASC')->get(); //echo '<pre>';print_r($template);exit;
		return view('body.itemtemplate.template')
					->withTemplate($template)->withNo($no);
	}
	
	public function getTemplateEdit($id,$jid,$no) {
		
		$template = DB::table('item_template')->where('item_template.item_id',$id)
							->where('item_template.deleted_at',null)
							->leftJoin('units','units.id','=','item_template.unit_id')
							->select('item_template.*','units.unit_name')
							->orderBy('item_template.order_no','ASC')
							->get(); //echo '<pre>';print_r($template);exit;
		$row = DB::table('so_joborder')->where('id',$jid)->first();
		$jobdata = json_decode($row->job_value);  //echo '<pre>';print_r($jobdata);exit;
		
		foreach($template as $k => $trow) {
			if($trow->input_type=="item") {
				$items[$k] = DB::table('itemmaster')->where('group_id',$trow->group_id)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','description AS text')->get();
			}
		}
		return view('body.itemtemplate.edittemplate')
					->withTemplate($template)
					->withJobdata($jobdata->jobvals)
					->withItems($items)
					->withJid($jid)
					->withNo($no);
	}
	
	public function getItems($gid) { //print_r(Input::all());
		/* if(Input::get('searchTerm')!='')
			$items = DB::table('itemmaster')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->where('description','like','%'.Input::get('searchTerm').'%')->select('id','description AS text')->get();
		else */
			$items = DB::table('itemmaster')->where('group_id',$gid)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','description AS text')->get();
		
		return $items;
	}
	
	public function saveJoborder(Request $request) {
		
		$attributes = $request->all();
		echo '<pre>';print_r($request->all()); exit;
		foreach($attributes['input_type'] as $key => $val) {
			$other_value = '';
			if($val == 'item' && $attributes['is_dimension'][$key]==1) {
				$other_value = ['height' => $attributes['height'][$key], 'width' => $attributes['width'][$key], 'quantity' => $attributes['quantity'][$key]];
			} elseif($val == 'item' && $attributes['is_dimension'][$key]==0) {
				$other_value = ['quantity' => $attributes['quantity'][$key]];
			}
			$subitem = [];
			if(isset($attributes['input_itemsub'][$key])) {
				foreach($attributes['input_itemsub'][$key] as $k => $sub) {
					if($sub!='' && $attributes['quantity_sub'][$key][$k]!='')
						$subitem[] = ['dm_tem' => $sub, 'qty' => $attributes['quantity_sub'][$key][$k]];
				}
			} else
				$subitem = null;
			$arrVal[] = ['input_type' => $val, 'input_value' => $attributes['input_item'][$key], 'other_value' => $other_value, 'dmond' => $subitem];
			
			$it_id = DB::table('item_template_edit')
						->insertGetId([
							'so_item_id' => '',
							'item_id' => $attributes['item_id'],
							'type_name' => $attributes['type_name'][$key],
							'input_type'	=> $attributes['input_type'][$key],
							'is_stock'	=> isset($attributes['is_stockitm'][$key])?$attributes['is_stockitm'][$key]:0,
							'unit_id'	=> isset($attributes['unit'][$key])?$attributes['unit'][$key]:0,
							'group_id'	=> isset($attributes['group'][$key])?$attributes['group'][$key]:0,
							'is_dimension'	=> isset($attributes['dimension'][$key])?$attributes['dimension'][$key]:0,
							'order_no'	=> $attributes['ordno'][$key],
							'is_required'	=> $attributes['required'][$key]
						]);
		}
		
		$vals['jobvals'] = $arrVal;
		//print_r(json_encode($vals));
		if($attributes['jid']=='') {
			$id = DB::table('so_joborder')
						->insertGetId([
							'job_value' => json_encode($vals)
						]);
						
			DB::table('item_template_edit')
						->where('id', $it_id)
						->update(['so_item_id' => $id]);
		} else {
			DB::table('so_joborder')
						->where('id', $attributes['jid'])
						->update([
							'job_value' => json_encode($vals)
						]);
			$id = $attributes['jid'];
		}
		return $id;
	}
	
	public function uploadAttachment(Request $request)
	{	
		$res = $this->ajax_upload_attachment($request->attachment);
		return response()->json(array('file_name' => $res), 200);
	}
	
	public function ajax_upload_attachment($file)
	{ 
		$photo = '';
		$fname = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
		
		if($file) {
			$ext = $file->getClientOriginalExtension();
			if($ext=='.jpg' ||$ext=='.JPG' || $ext=='.png' || $ext=='.PNG') {
				$photo = $fname.'_'.rand(1, 999).'.'.$ext;
				$destinationPath = public_path() . $this->imgDirC.'/'.$photo;
				$destinationPathThumb = public_path() . $this->imgDirC.'/thumb_'.$photo;

				// resizing an uploaded file
				Image::make($file->getRealPath())->resize($this->widthC, $this->heightC, function($constraint) { $constraint->aspectRatio(); })->save($destinationPath);

				// thumb
				Image::make($file->getRealPath())->resize($this->thumbWidthC, $this->thumbHeightC, function($constraint) { $constraint->aspectRatio(); })->save($destinationPathThumb);
			} else {
				 $photo = $fname.'_'.rand(1, 999).'.'.$ext;
				 $destinationPath = public_path() . $this->imgDirC;
				 $file->move($destinationPath,$photo);
			}
		}
		
		return $photo;

	}
	
	public function edit($id) {

		$items = DB::table('itemmaster')
						->where('id',$id)
						->select('id','item_code','description')
						->get();
						
		$group = DB::table('groupcat')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','description')->get();
		
		$templates = DB::table('item_template')->where('item_id',$id)->where('deleted_at',null)->get();
		$units = DB::table('units')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','unit_name')->get();
		//echo '<pre>';print_r($templates);exit;
		return view('body.itemtemplate.edit')
					->withGroup($group)
					->withTemplates($templates)
					->withId($id)
					->withUnits($units)
					->withItems($items);
	}
	
	
	public function update(Request $request) {
		$attributes = $request->all(); //echo '<pre>';print_r($attributes);exit;
		DB::beginTransaction();
		try {
			foreach($attributes['input_type'] as $key => $val) {
				if($attributes['id'][$key]=='') {
					DB::table('item_template')
						->insert([
							'item_id' => $attributes['item_id'],
							'type_name' => $attributes['type_name'][$key],
							'input_type'	=> $attributes['input_type'][$key],
							'is_stock'	=> isset($attributes['is_stockitm'][$key])?$attributes['is_stockitm'][$key]:0,
							'unit_id'	=> isset($attributes['unit'][$key])?$attributes['unit'][$key]:0,
							'group_id'	=> isset($attributes['group'][$key])?$attributes['group'][$key]:0,
							'is_dimension'	=> isset($attributes['dimension'][$key])?$attributes['dimension'][$key]:0,
							'order_no'	=> $attributes['ordno'][$key],
							'is_required'	=> $attributes['required'][$key]
							//'frame_no'	=> $attributes['frameno'][$key]
						]);
				} else {
					DB::table('item_template')
						->where('id',$attributes['id'][$key])
						->update([
							'item_id' => $attributes['item_id'],
							'type_name' => $attributes['type_name'][$key],
							'input_type'	=> $attributes['input_type'][$key],
							'is_stock'	=> isset($attributes['is_stockitm'][$key])?$attributes['is_stockitm'][$key]:0,
							'unit_id'	=> isset($attributes['unit'][$key])?$attributes['unit'][$key]:0,
							'group_id'	=> isset($attributes['group'][$key])?$attributes['group'][$key]:0,
							'is_dimension'	=> isset($attributes['dimension'][$key])?$attributes['dimension'][$key]:0,
							'order_no'	=> $attributes['ordno'][$key],
							'is_required'	=> $attributes['required'][$key]
							//'frame_no'	=> $attributes['frameno'][$key]
						]);
				}
			}
			
			if($attributes['remove_item']!='')
			{
				$arrids = explode(',', $attributes['remove_item']);
				foreach($arrids as $row) {
					DB::table('item_template')->where('id', $row)->update(['deleted_at' => date('Y-m-d H:i:s')]);
				}
			}
				
			DB::commit();
			Session::flash('message', 'Item Template updated successfully.');
			return redirect('item_template');
			
		} catch(\Exception $e) {
			DB::rollback(); echo $e->getLine().' '.$e->getMessage();exit;
			return Redirect::to('item_template/add')->withErrors($e->getErrors());
		}
	}
	
	public function destroy($id) {
		
		DB::table('item_template')->where('item_id',$id)->update(['deleted_at' => date('Y-m-d H:i:s')]);
		
		Session::flash('message', 'Item Template deleted successfully.');
		return redirect('item_template');
	}
	
}