<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\TemplateName\TemplateNameInterface;

use App\Http\Requests;
use Input;
use Session;
use Response;
use App;

class TemplateNameController extends Controller
{
    protected $template_name;
	
	public function __construct(TemplateNameInterface $template_name) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->template_name = $template_name;
		$this->middleware('auth');
		
	}
	
	public function index() {
		$data = array();
		$templatenames = $this->template_name->template_nameList();
		return view('body.templatename.index')
					->withTemplatenames($templatenames)
					->withData($data);
	}
	
	public function add() {

		$data = array();
		return view('body.templatename.add')
					->withData($data);
	}
	
	public function save() {
		//try {
			$this->template_name->create(Input::all());
			Session::flash('message', 'Email Template added successfully.');
			return redirect('template_name/add');
		/* } catch(ValidationException $e) { 
			return Redirect::to('header_footer/add')->withErrors($e->getErrors());
		} */
	}
	
	public function edit($id) { 

		$data = array();
		$tnrow = $this->template_name->find($id);
		return view('body.templatename.edit')
					->withTnrow($tnrow)
					->withData($data);
	}
	
	public function update($id)
	{
		$this->template_name->update($id, Input::all());//print_r(Input::all());exit;
		Session::flash('message', 'Email Template updated successfully');
		return redirect('template_name');
	}
	
	public function destroy($id)
	{
		$this->template_name->delete($id);
		//check header_footer name is already in use.........
		// code here ********************************
		Session::flash('message', 'Email Template deleted successfully.');
		return redirect('template_name');
	}
	
	/*public function getHeader()
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
	}*/
	
}
