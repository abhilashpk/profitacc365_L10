<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\HeaderFooter\HeaderFooterInterface;

use App\Http\Requests;
use Input;
use Session;
use Response;
use App;
use DB;

class HeaderFooterController extends Controller
{
    protected $header_footer;
	
	public function __construct(HeaderFooterInterface $header_footer) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->header_footer = $header_footer;
		$this->middleware('auth');
		
	}
	
	public function index() {
		$data = array();
		$header_footers = $this->header_footer->header_footerList();
		return view('body.headerfooter.index')
					->withHeaderfooters($header_footers)
					->withData($data);
	}
	
	public function add() {

		$data = DB::table('voucher_no')->where('status',1)->select('voucher_type','name')->get();
		return view('body.headerfooter.add')
					->withData($data);
	}
	
	public function save() {
		//try {
			$this->header_footer->create(Input::all());
			Session::flash('message', 'Header/Footer added successfully.');
			return redirect('header_footer/add');
		/* } catch(ValidationException $e) { 
			return Redirect::to('header_footer/add')->withErrors($e->getErrors());
		} */
	}
	
	public function edit($id) { 

		$data = DB::table('voucher_no')->where('status',1)->select('voucher_type','name')->get();
		$hfrow = $this->header_footer->find($id);
		return view('body.headerfooter.edit')
					->withHfrow($hfrow)
					->withData($data);
	}
	
	public function update($id)
	{
		$this->header_footer->update($id, Input::all());//print_r(Input::all());exit;
		Session::flash('message', 'HeaderFooter updated successfully');
		return redirect('header_footer');
	}
	
	public function destroy($id)
	{
		$this->header_footer->delete($id);
		//check header_footer name is already in use.........
		// code here ********************************
		Session::flash('message', 'Header/Footer deleted successfully.');
		return redirect('header_footer');
	}
	
	public function getHeader()
	{
		$data = array();
		$headers = $this->header_footer->header_or_footerList(1);//echo '<pre>';print_r($suppliers);exit;
		return view('body.headerfooter.header')
					->withHeaders($headers)
					->withData($data);
	}
	
	public function getFooter()
	{
		$data = array();
		$footers = $this->header_footer->header_or_footerList(0);//echo '<pre>';print_r($suppliers);exit;
		return view('body.headerfooter.footer')
					->withFooters($footers)
					->withData($data);
	}
	
}

