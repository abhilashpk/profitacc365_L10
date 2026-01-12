<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Currency\CurrencyInterface;

use App\Http\Requests;
use Input;
use Session;
use Response;
use App;
use Auth;
use DB;

class CurrencyController extends Controller
{
    protected $currency;
	
	public function __construct(CurrencyInterface $currency) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->currency = $currency;
		$this->middleware('auth');
	}
	
	public function index() {
		$data = array();
		$currencys = $this->currency->currencyList();
		return view('body.currency.index')
					->withCurrencys($currencys)
					->withData($data);
	}
	
	public function add() {

		$data = array();
		return view('body.currency.add')
					->withData($data);
	}
	
	public function save() {
		$this->currency->create(Input::all());
		Session::flash('message', 'Currency added successfully.');
		return redirect('currency/add');
	}
	
	public function edit($id) { 

		$data = array();
		$currencyrow = $this->currency->find($id);
		return view('body.currency.edit')
					->withCurrencyrow($currencyrow)
					->withData($data);
	}
	
	public function update($id)
	{
		$this->currency->update($id, Input::all());//print_r(Input::all());exit;
		Session::flash('message', 'Currency updated successfully');
		return redirect('currency');
	}
	
	public function destroy($id)
	{
		$this->currency->delete($id);
		//check currency name is already in use.........
		// code here ********************************
		Session::flash('message', 'Currency deleted successfully.');
		return redirect('currency');
	}
	public function getCurrency($id){
	$bcurrency=DB::table('currency')->where('id','=',$id)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name','code')->get();
	return $bcurrency;
	}
	public function checkcode() {

		$check = $this->currency->check_currency_code(Input::get('code'), Input::get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
	
	public function checkname() {

		$check = $this->currency->check_currency_name(Input::get('name'), Input::get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
	
	public function ajax_getrate($curr_id)
	{
		$row = $this->currency->find($curr_id);
		if($row)
			return $row->rate;
		else 
			return 0;
		

	}
}
