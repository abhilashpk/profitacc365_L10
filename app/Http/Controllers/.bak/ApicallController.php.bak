<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Ixudra\Curl\Facades\Curl;
use App\Http\Requests;
use Input;
use Session;
use Response;

class ApicallController extends Controller
{
	
	public function index()
	{
		$data = array();
		return view('body.apicall.index')
					->withData($data);
	}
	
	public function status_chk() {
		
		$response =   Curl::to('http://amazonapi.numaktech.com/order-status') //Curl::to('http://hom4me.co.com/order-status')
						->withData( array('reference_no' => [Input::get('refno')])) //JCT0204799137WS,JCT2280831906WS  ['JCT0513032995IN'])) JCT0518008339IN
						->asJson()
						->post();
						
		echo '<pre>';print_r($response);exit;				
	}
}