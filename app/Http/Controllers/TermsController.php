<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Terms\TermsInterface;

use App\Http\Requests;
use Session;
use Response;
use App;

class TermsController extends Controller
{
    protected $terms;
	
	public function __construct(TermsInterface $terms) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->terms = $terms;
		$this->middleware('auth');
	}
	
	public function index() {
		$data = array();
		$termss = $this->terms->termsList();
		return view('body.terms.index')
					->withTermss($termss)
					->withData($data);
	}
	
	public function add() {

		$data = array();
		return view('body.terms.add')
					->withData($data);
	}
	
	public function save(Request $request) {
		
		
		$img= $_FILES['userfile']['name'];
		$this->terms->create($request->all());
		//echo '<pre>';print_r($img);exit;
		$img= $_FILES['userfile']['name'];
		$img_loc=$_FILES['userfile']['tmp_name'];
		$img_folder="uploads/file/";
		move_uploaded_file($img_loc,$img_folder.$img);
		
		Session::flash('message', 'Terms added successfully.');
		return redirect('terms/add');
	}
	
	public function edit($id) { 

		$data = array();
		$termsrow = $this->terms->find($id);
		return view('body.terms.edit')
					->withTermsrow($termsrow)
					->withData($data);
	}
	
	public function update($id, Request $request)
	{
		$this->terms->update($id, $request->all());//print_r($request->all());exit;
		//Session::flash('message', 'Category updated successfully');
		return redirect('terms');
	}
	
	public function destroy($id)
	{
		$this->terms->delete($id);
		//check terms name is already in use.........
		// code here ********************************
		Session::flash('message', 'Terms deleted successfully.');
		return redirect('terms');
	}
	
	public function checkcode(Request $request) {

		$check = $this->terms->check_terms_code($request->get('code'), $request->get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
	
	public function checkname(Request $request) {

		$check = $this->terms->check_terms_name($request->get('name'), $request->get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
}

