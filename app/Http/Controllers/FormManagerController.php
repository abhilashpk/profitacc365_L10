<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Forms\FormsInterface;

use App\Http\Requests;
use Session;
use Redirect;
use DB;
use App;

class FormManagerController extends Controller
{
    protected $forms;
	
	public function __construct(FormsInterface $forms) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->forms = $forms;
		$this->middleware('auth');
	}
	
	public function index() { 
		$data = array();
		$forms = $this->forms->activeFormsList();
		//echo '<pre>';print_r($forms);exit;
		return view('body.forms.index')
					->withForms($forms)
					->withData($data);
	}
	
	public function detail($type) { 
		$data = array();
		if($type === 'ITMAD') {
			$form = DB::table('forms')->where('code', $type)->first();
			if($form) {
				$maxOrd = (int) DB::table('form_details')->where('form_id', $form->id)->max('list_ord');
				$maxOrd = $maxOrd > 0 ? $maxOrd : 0;
				$missing = [
					'dimension' => 'Dimension Required',
					'batch_req' => 'Batch Required',
				];
				foreach($missing as $code => $name) {
					$exists = DB::table('form_details')
						->where('form_id', $form->id)
						->where('field_code', $code)
						->exists();
					if(!$exists) {
						$maxOrd++;
						DB::table('form_details')->insert([
							'form_id' => $form->id,
							'field_name' => $name,
							'field_code' => $code,
							'active' => 1,
							'status' => 1,
							'list_ord' => $maxOrd,
						]);
					}
				}
			}
		}
		if($type === 'PI') {
			$form = DB::table('forms')->where('code', $type)->first();
			if($form) {
				$maxOrd = (int) DB::table('form_details')->where('form_id', $form->id)->max('list_ord');
				$maxOrd = $maxOrd > 0 ? $maxOrd : 0;
				$missing = [
					'due_days' => 'Day',
					'due_date' => 'Due Date',
					'import' => 'Import',
					'item_import' => 'Items Import',
					'batch_req' => 'Batch',
				];
				foreach($missing as $code => $name) {
					$exists = DB::table('form_details')
						->where('form_id', $form->id)
						->where('field_code', $code)
						->exists();
					if(!$exists) {
						$maxOrd++;
						DB::table('form_details')->insert([
							'form_id' => $form->id,
							'field_name' => $name,
							'field_code' => $code,
							'active' => 1,
							'status' => 1,
							'list_ord' => $maxOrd,
						]);
					}
				}
			}
		}
		if($type === 'SI') {
			$form = DB::table('forms')->where('code', $type)->first();
			if($form) {
				$maxOrd = (int) DB::table('form_details')->where('form_id', $form->id)->max('list_ord');
				$maxOrd = $maxOrd > 0 ? $maxOrd : 0;
				$missing = [
					'due_days' => 'Day',
					'due_date' => 'Due Date',
					'export' => 'Export',
				];
				foreach($missing as $code => $name) {
					$exists = DB::table('form_details')
						->where('form_id', $form->id)
						->where('field_code', $code)
						->exists();
					if(!$exists) {
						$maxOrd++;
						DB::table('form_details')->insert([
							'form_id' => $form->id,
							'field_name' => $name,
							'field_code' => $code,
							'active' => 1,
							'status' => 1,
							'list_ord' => $maxOrd,
						]);
					}
				}
			}
		}
		if($type === 'SR') {
			$form = DB::table('forms')->where('code', $type)->first();
			if($form) {
				$maxOrd = (int) DB::table('form_details')->where('form_id', $form->id)->max('list_ord');
				$maxOrd = $maxOrd > 0 ? $maxOrd : 0;
				$missing = [
					'export' => 'Export',
				];
				foreach($missing as $code => $name) {
					$exists = DB::table('form_details')
						->where('form_id', $form->id)
						->where('field_code', $code)
						->exists();
					if(!$exists) {
						$maxOrd++;
						DB::table('form_details')->insert([
							'form_id' => $form->id,
							'field_name' => $name,
							'field_code' => $code,
							'active' => 1,
							'status' => 1,
							'list_ord' => $maxOrd,
						]);
					}
				}
			}
		}
		if($type === 'PR') {
			$form = DB::table('forms')->where('code', $type)->first();
			if($form) {
				$maxOrd = (int) DB::table('form_details')->where('form_id', $form->id)->max('list_ord');
				$maxOrd = $maxOrd > 0 ? $maxOrd : 0;
				$missing = [
					'export' => 'Export',
				];
				foreach($missing as $code => $name) {
					$exists = DB::table('form_details')
						->where('form_id', $form->id)
						->where('field_code', $code)
						->exists();
					if(!$exists) {
						$maxOrd++;
						DB::table('form_details')->insert([
							'form_id' => $form->id,
							'field_name' => $name,
							'field_code' => $code,
							'active' => 1,
							'status' => 1,
							'list_ord' => $maxOrd,
						]);
					}
				}
			}
		}
		$forms = $this->forms->getForm($type);
		if($forms->isEmpty()) {
			Session::flash('message', 'Form not found for code: '.$type);
			return redirect('forms');
		}
		return view('body.forms.detail')
					->withForms($forms)
					->withData($data);
	}
	
	public function update(Request $request) { 
		//echo '<pre>';print_r($request->all());exit;
		$this->forms->update($id=null,$request->all());
		Session::flash('message', 'Form settings updated successfully.');
		return redirect('forms');
		
	}
}

