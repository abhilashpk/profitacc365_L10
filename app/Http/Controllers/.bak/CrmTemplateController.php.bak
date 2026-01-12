<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Input;
use Session;
use Response;
use DB;
use App;
use Auth;

class CrmTemplateController extends Controller
{

	public function __construct() {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->middleware('auth');
		
	}
	
	public function index() {
				
		$items = DB::table('crm_template')
						->where('deleted_at',null)
						->where('doc_type','SOB')
						->select('*')
						->get();
		
		return view('body.crmtemplate.index')->withItems($items);
		
	}

	
	public function save(Request $request) {
		$attributes = $request->all(); //echo '<pre>';print_r($attributes);//exit;
		DB::beginTransaction();
		try {
			foreach($attributes['label'] as $key => $val) {
				if($attributes['id'][$key]=='') {
					DB::table('crm_template')
						->insert([
							'doc_type' => 'SOB',
							'label' => $attributes['label'][$key],
							'text_no'	=> $attributes['text_no'][$key],
							'label2' => $attributes['label2'][$key],
							'reqd1' => $attributes['req1'][$key],
							'reqd2' => $attributes['req2'][$key],
							'values1' => $attributes['values1'][$key],
							'values2' => $attributes['values2'][$key]
						]);
				} else {
					DB::table('crm_template')
						->where('id', $attributes['id'][$key])
						->update([
							'label' => $attributes['label'][$key],
							'text_no'	=> $attributes['text_no'][$key],
							'label2' => $attributes['label2'][$key],
							'reqd1' => $attributes['req1'][$key],
							'reqd2' => $attributes['req2'][$key],
							'values1' => $attributes['values1'][$key],
							'values2' => $attributes['values2'][$key]
						]);
				}
				
			}
			
			if($attributes['remove_item']!='')
			{
				$arrids = explode(',', $attributes['remove_item']);
				foreach($arrids as $row) {
					DB::table('crm_template')->where('id', $row)->update(['deleted_at' => date('Y-m-d H:i:s')]);
				}
			}
				
			DB::commit();
			Session::flash('message', 'CRM Template updated successfully.');
			return redirect('crm_template');
			
		} catch(\Exception $e) {
			DB::rollback(); echo $e->getLine().' '.$e->getMessage();exit;
			return Redirect::to('crm_template')->withErrors($e->getErrors());
		}
	}
}