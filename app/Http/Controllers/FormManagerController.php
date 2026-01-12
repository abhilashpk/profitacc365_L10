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
		$forms = $this->forms->getForm($type);
		
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

