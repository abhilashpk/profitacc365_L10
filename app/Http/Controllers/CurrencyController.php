<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Currency\CurrencyInterface;

use App\Http\Requests;
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
	
	public function save(Request $request) {
		$this->currency->create($request->all());
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
	
	public function update($id, Request $request)
	{
		$this->currency->update($id, $request->all());//print_r($request->all());exit;
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
	public function checkcode(Request $request) {

		$check = $this->currency->check_currency_code($request->get('code'), $request->get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
	
	public function checkname(Request $request) {

		$check = $this->currency->check_currency_name($request->get('name'), $request->get('id'));
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

