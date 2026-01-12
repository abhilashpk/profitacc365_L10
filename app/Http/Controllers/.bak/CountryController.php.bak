<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Country\CountryInterface;

use App\Http\Requests;
use Input;
use Session;
use Response;
use App;

class CountryController extends Controller
{
    protected $country;
	
	public function __construct(CountryInterface $country) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->country = $country;
		$this->middleware('auth');
		
	}
	
	public function index() {
		$data = array();
		$countrys = $this->country->countryList();
		return view('body.country.index')
					->withCountrys($countrys)
					->withData($data);
	}
	
	public function add() {

		$data = array();
		return view('body.country.add')
					->withData($data);
	}
	
	public function save() {
		$this->country->create(Input::all());
		Session::flash('message', 'Country added successfully.');
		return redirect('country/add');
	}
	
	public function edit($id) { 

		$data = array();
		$countryrow = $this->country->find($id);
		return view('body.country.edit')
					->withCountryrow($countryrow)
					->withData($data);
	}
	
	public function update($id)
	{
		$this->country->update($id, Input::all());//print_r(Input::all());exit;
		Session::flash('message', 'Country updated successfully');
		return redirect('country');
	}
	
	public function destroy($id)
	{
		$this->country->delete($id);
		//check country name is already in use.........
		// code here ********************************
		Session::flash('message', 'Country deleted successfully.');
		return redirect('country');
	}
	
	public function checkcode() {

		$check = $this->country->check_country_code(Input::get('code'), Input::get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
	
	public function checkname() {

		$check = $this->country->check_country_name(Input::get('name'), Input::get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
}
