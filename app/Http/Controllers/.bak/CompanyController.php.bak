<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Company\CompanyInterface; 

use App\Http\Requests;
use Input;
use Session;
use Redirect;
use App;

class CompanyController extends Controller
{
    protected $company;
	
	public function __construct(CompanyInterface $company) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->company = $company;
		$this->middleware('auth');
		
	}
	
	public function index() { 
		$data = array();
		$company = $this->company->getCompany();
		
		return view('body.company.index')
					->withCompany($company)
					->withData($data);
	}

	public function update($id)
	{ 
	    //echo '<pre>';print_r(Input::all());exit;
		$this->company->update($id, Input::all());
		//echo '<pre>';print_r($id);exit;
		Session::flash('message', 'Company details updated successfully');
		return redirect('company');
	}
}
