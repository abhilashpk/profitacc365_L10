<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Bank\BankInterface;

use App\Http\Requests;
use Session;
use Response;
use DB;
use Auth;

class BankController extends Controller
{
    protected $bank;
	
	public function __construct(BankInterface $bank) {
		
	$this->bank = $bank;
		$this->middleware('auth');
		
	}
	
	public function index() {
		

				
		$data = array();
		$bank = DB::table('bank')->where('deleted_at','0000-00-00 00:00:00')->get();
		//echo '<pre>';print_r($bank);exit;
		//$banks = $this->bank->all();
		
		return view('body.bank.index')
					->withBanks($bank)
					->withData($data);
	}
	
	public function add() {

		$data = array();
		return view('body.bank.add')
					->withData($data);
	}
	
	public function save(Request $request) {
		try {
			$this->bank->create($request->all());
			Session::flash('message', 'Bank added successfully.');
			return redirect('bank/add');
		} catch(ValidationException $e) { 
			return Redirect::to('bank/add')->withErrors($e->getErrors());
		}
	}
	
	public function edit($id) { 

		$data = array();
		$bankrow = $this->bank->find($id);
		return view('body.bank.edit')
					->withBankrow($bankrow)
					->withData($data);
	}
	
	public function update($id, Request $request)
	{
		$this->bank->update($id, $request->all());//print_r($request->all());exit;
		Session::flash('message', 'Bank updated successfully');
		return redirect('bank');
	}
	
	public function destroy($id)
	{
		$this->bank->delete($id);
		//check bank name is already in use.........
		// code here ********************************
		Session::flash('message', 'Bank deleted successfully.');
		return redirect('bank');
	}
	
	public function checkcode(Request $request) {

		$check = $this->bank->check_bank_code($request->get('code'), $request->get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
	
	public function checkname(Request $request) {

		$check = $this->bank->check_bank_name($request->get('name'), $request->get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
}

