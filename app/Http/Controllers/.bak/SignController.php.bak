<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;

class SignController extends Controller
{
    
    public function index(Request $request)
    {
        $msg = '';
		if($request->method() == 'POST'){
			
			$signature = $request->input('signature');
			$signatureFileName = $request->input('cust').'_'.date('d-m-Y').'.png';
			$signature = str_replace('data:image/png;base64,', '', $signature);
			$signature = str_replace(' ', '+', $signature);
			$data = base64_decode($signature);
			$file = $destinationPath = public_path().'/uploads/signatures/'.$signatureFileName;
			file_put_contents($file, $data);
			$msg = "<div class='alert alert-success'>Signature Uploaded</div>";

		}
		return view('body.sign.index')->withMsg($msg);
    }
	
}
