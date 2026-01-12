<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\VoucherNo\VoucherNoInterface; 

use App\Http\Requests;
use Input;
use Session;
use Redirect;
use App;

class VoucherNumbersController extends Controller
{
    protected $voucherno;
	
	public function __construct(VoucherNoInterface $voucherno) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->voucherno = $voucherno;
		$this->middleware('auth');
		
	}
	
	public function index() { 
		$data = array();
		$vouchers = $this->voucherno->getVoucherNoSetting();
		return view('body.vouchernumbers.index')
					->withVouchers($vouchers)
					->withData($data);
	}
	
	public function update()
	{ 
		$this->voucherno->update($id=null,Input::all());
		Session::flash('message', 'Voucher No. updated successfully');
		return redirect('voucher_numbers');
	}
}
