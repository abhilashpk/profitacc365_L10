<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Input;
use Session;
use Response;
use DB;
use App;

class ChequeDetailsController extends Controller
{
    protected $cheque_details;
	protected $bank;
	public function __construct() {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->middleware('auth');
		
	}
	
	public function index() {
		$data = array();
		
		$machine = DB::table('cheque_details')->join('account_master AS am', function($join) {
			$join->on('am.id','=','cheque_details.customer_id');
		} )->select('cheque_details.*','am.master_name AS customer')->where('cheque_details.deleted_at','0000-00-00 00:00:00')->get();

        $prints = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','CD')
							->select('report_view_detail.name','report_view_detail.id')
							->get();
		
		//echo '<pre>';print_r($machine);exit;
		$banks = DB::table('bank')->get();
					
		return view('body.chequedetails.index')
					->withMachine($machine)
					->withBanks($banks)
                    ->withPrints($prints)
					->withData($data);
	}


	public function salesInvoiceListCount()
	{
		//CHECK DEPARTMENT.......
	
		$query =DB::table('cheque_details')->where('cheque_details.deleted_at','0000-00-00 00:00:00');
	
		return $query->join('account_master AS am', function($join) {
							$join->on('am.id','=','cheque_details.customer_id');
						} )->join('bank AS bnk', function($join) {
							$join->on('bnk.id','=','cheque_details.bank_id');
						} )
					->count();
	}



	public function salesInvoiceList($type,$start,$limit,$order,$dir,$search,$dept=null)
	{
		//CHECK DEPARTMENT.......
	//	$deptid = (Session::get('department')==1)?Auth::user()->department_id:0;
		
		$query =DB::table('cheque_details')->where('cheque_details.deleted_at','0000-00-00 00:00:00')
		                     ->join('account_master AS am', function($join) {
		                    	$join->on('am.id','=','cheque_details.customer_id');
		               } )->join('bank AS bnk', function($join) {
		                	$join->on('bnk.id','=','cheque_details.bank_id');
		           } );
				
				//if($deptid!=0) //dept chk
				//	$query->where('sales_invoice.department_id', $deptid);
			//	else {
				//	if($dept!='' && $dept!=0) {
				//		$query->where('sales_invoice.department_id', $dept);
				//	}
			//	}
			
				if($search) {
					
					$query->where(function($qry) use($search) {
						$date = date('Y-m-d', strtotime($search));
						$qry->where('cheque_details.cheque_no','LIKE',"%{$search}%")
							->orWhere('am.master_name', 'LIKE',"%{$search}%")
							->orWhere('cheque_details.cheque_date','LIKE',"%{$search}%")
							->orWhere('cheque_details.amount_number','LIKE',"%{$search}%")
							->orWhere('cheque_details.amount_words','LIKE',"%{$search}%");
					});
				}
				
				$query->select('cheque_details.*','am.master_name AS customer','bnk.name AS bank')
					->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir);
					
				if($type=='get')
					return $query->get();
				else
					return $query->count();
	}
	


	public function ajaxPaging(Request $request)
	{
		$columns = array( 
                            0 =>'cheque_details.id', 
                            1 =>'cheque_no',
                            2=> 'cheque_date',
                            3=> 'customer',
							4=> 'bank',
						    5=> 'amount_number'
							
                        );
						
		$totalData = $this->salesInvoiceListCount();
            
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')]; //'sales_invoice.id';
        $dir = $request->input('order.0.dir'); //'desc';
		$search = (empty($request->input('search.value')))?null:$request->input('search.value');
		//$dept = $request->input('dept');
        

		$invoices = $this->salesInvoiceList('get', $start, $limit, $order, $dir, $search);
		
		if($search)
			$totalFiltered =  $this->salesInvoiceList('count', $start, $limit, $order, $dir, $search);
		
		$prints = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','CD')
							->select('report_view_detail.name','report_view_detail.id')
							->get();
		
        $data = array();
        if(!empty($invoices))
        {
           
			foreach ($invoices as $row)
            {
                $edit =  '"'.url('cheque_details/edit/'.$row->id).'"';
                $delete =  'funDelete("'.$row->id.'")';
				$print = url('cheque_details/print/'.$row->id);
			//	$printfc = url('cheque_details/printfc/'.$row->id);
				//$printdo = url('cheque_details/printdo/'.$row->id);
				
				
                $nestedData['id'] = $row->id;
                $nestedData['cheque_no'] = $row->cheque_no;
				$nestedData['cheque_date'] = date('d-m-Y', strtotime($row->cheque_date));
				$nestedData['customer'] = $row->customer;
				$nestedData['bank'] = $row->bank;
				$nestedData['amount_number'] = number_format($row->amount_number,2);
                $nestedData['edit'] = "<p><button class='btn btn-primary btn-xs' onClick='location.href={$edit}'>
												<span class='glyphicon glyphicon-pencil'></span></button></p>";
												
				$nestedData['delete'] = "<button class='btn btn-danger btn-xs delete' onClick='{$delete}'>
												<span class='glyphicon glyphicon-trash'></span>";
				
				$apr = ($this->acsettings->doc_approve==1)?[1]:[0,1,2];
				
				$opts = '';					
				foreach($prints as $doc) {
					$opts .= "<li role='presentation'><a href='{$print}/".$doc->id."' target='_blank' role='menuitem'>".$doc->name."</a></li>";
				}
				$printfc = url('cheque_details/printfc/'.$row->id.'/'.$prints[0]->id); //MAR18
				if(in_array($row->doc_status, $apr))	 {							
					if($row->bank_id!=0) {
						$nestedData['print'] = "<div class='btn-group drop_btn' role='group'>
						<button type='button' class='btn btn-primary btn-xs dropdown-toggle m-r-50'
								id='exampleIconDropdown1' data-toggle='dropdown' aria-expanded='false'>
							<i class='fa fa-fw fa-print' aria-hidden='true'></i><span class='caret'></span>
						</button>
						<ul style='min-width:100px !important;' class='dropdown-menu' aria-labelledby='exampleIconDropdown1' role='menu'>
							".$opts."
						</ul>
					</div>";
					} else {
					
						//$nestedData['print'] = "<p><a href='{$print}' target='_blank' class='btn btn-primary btn-xs'><span class='fa fa-fw fa-print'></span></a></p>";
					}
					
				} else {
					$nestedData['print'] = '';
				}
				
				
				
				/* if($row->is_fc==1) {								
					$nestedData['print'] = "<p><a href='{$print}' target='_blank' class='btn btn-primary btn-xs'><span class='fa fa-fw fa-print'></span></a>
											<a href='{$print}/FC' target='_blank' class='btn btn-primary btn-xs'><span class='fa fa-fw fa-print'></span>FC</a></p>";
				} else {
					$nestedData['print'] = "<p><a href='{$print}' target='_blank' class='btn btn-primary btn-xs'><span class='fa fa-fw fa-print'></span></a></p>";
				} */
				// $nestedData['printdo'] = "<p><a href='{$printdo}' target='_blank' class='btn btn-primary btn-xs'><span class='fa fa-fw fa-print'></span>DO</a></p>";
				$nestedData['printdo']='';							
                $data[] = $nestedData;

            }
        }
          
        $json_data = array(
                    "draw"            => intval($request->input('draw')),  
                    "recordsTotal"    => intval($totalData),  
                    "recordsFiltered" => intval($totalFiltered), 
                    "data"            => $data   
                    );
            
        echo json_encode($json_data);
	}	
	public function add() {

		$data = array();
		$banks = DB::table('bank')->get();
		$customers = DB::table('account_master')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->where('category','CUSTOMER')->orderBy('master_name','ASC')->get();
		
		return view('body.chequedetails.add')
		->withBanks($banks)
		->withCustomers($customers)
					->withData($data);
	}
	
	public function save() {
	//	echo '<pre>';print_r(Input::all());exit;
		try {
		    if (Input::get('is_payee') == '')
		    {
		    $ac_payee = 0;
		    }else
		    {
		        $ac_payee = Input::get('is_payee');
		    }
		    
		   
			DB::table('cheque_details')
				->insert([
					'cheque_no' => Input::get('cheque_no'),
                    'cheque_date' => date('Y-m-d', strtotime(Input::get('cheque_date'))),
                    'created_date' => date('Y-m-d H:i:s'),
					'ac_payee' =>$ac_payee,							
					'bank_id' => Input::get('bank_id'),
					'amount_words' => Input::get('amount_words'),
					'amount_number' => Input::get('amount_number'),
					'customer_id' => Input::get('customer_id')
				
				]);
			Session::flash('message', 'cheque details added successfully.');
			return redirect('cheque_details/add');
		} catch(ValidationException $e) { 
			return Redirect::to('cheque_details/add')->withErrors($e->getErrors());
		}
	}
	public function findcheque($id)
	{
		$query = DB::table('cheque_details')->where('cheque_details.id', $id);
		return $query->join('account_master AS am', function($join) {
							$join->on('am.id','=','cheque_details.customer_id');
						} )->join('bank AS bk', function($join) {
							$join->on('bk.id','=','cheque_details.bank_id');
						} )
				
					->select('cheque_details.*','am.master_name AS customer','bk.name AS bank')
					->orderBY('cheque_details.id', 'ASC')
					->first();
	}
	public function edit($id) { 
		//echo '<pre>';print_r($id);exit;
		$data = array();
	//	$row = DB::table('cheque_details')->where('cheque_details.id',$id)->first();
	//	echo '<pre>';print_r($row);exit;
	$row = $this->findcheque($id);	
	 //echo '<pre>';print_r($row);exit;	
	 $banks = DB::table('bank')->get();
		return view('body.chequedetails.edit')
		->withBanks($banks)
					->withMrow($row)
					->withData($data);
	}
	
	public function update($id)
	{
	     if (Input::get('is_payee') == '')
		    {
		    $ac_payee = 0;
		    }else
		    {
		        $ac_payee = Input::get('is_payee');
		    }
		    
		DB::table('cheque_details')->where('id',$id)
				->update([
					'cheque_no' => Input::get('cheque_no'),
                    'cheque_date' => date('Y-m-d', strtotime(Input::get('cheque_date'))),
                    'created_date' => date('Y-m-d H:i:s'),
					'bank_id' => Input::get('bank_id'),	
						'ac_payee' =>$ac_payee,
				//	'cheque_date' => Input::get('cheque_date'),
					'amount_words' => Input::get('amount_words'),
					'amount_number' => Input::get('amount_number'),
					'customer_id' => Input::get('customer_id')
				]);
		Session::flash('message', 'Cheque updated successfully');
		return redirect('cheque_details');
	}
    public function getPrint($id,$rid=null)
	{ 
		
        $viewfile = DB::table('report_view_detail')->where('id', $rid)->select('print_name')->first(); 
			
		if($viewfile->print_name=='') {
			$fc='';
			$attributes['document_id'] = $id; //echo "892 : ".$this->number_to_word(12495);exit;
			$attributes['is_fc'] = ($fc)?1:'';
		// $voucherhead = 'Sales Voucher(Non-Stock)';
		// $jvrow = $this->journal->find($id); 
		// $jerow = $this->journal->findJEdata($id); //echo '<pre>';print_r($jerow);exit;

		// return view('body.salesvoucher.print')
		// 			->withVoucherhead($voucherhead)
		// 			->withDetails($jvrow)
		// 			->withJerow($jerow);
		}else {
					
			$path = app_path() . '/stimulsoft/helper.php';
			return view('body.chequedetails.viewer')->withPath($path)->withView($viewfile->print_name);
		}
	}

	public function getPrintFc($id,$rid=null)
	{
		//$viewfile = DB::table('report_view')->where('code', 'SI')->where('status',1)->select('print_name')->first();
		$viewfile = DB::table('report_view_detail')->where('id', $rid)->select('print_name')->first(); 
		
		if($viewfile->print_name=='') {
			
			
		} else {
					
			$path = app_path() . '/stimulsoft/helper.php';
			$arr = explode('.',$viewfile->print_name);
			$viewname = $arr[0].'FC.mrt';
			
			return view('body.chequedetails.viewer')->withPath($path)->withView($viewname);
		}
		
	}
	public function destroy($id)
	{
		DB::table('cheque_details')->where('id',$id)->update(['deleted_at' => date('Y-m-d H:i:s')]);
		Session::flash('message', 'Cheque deleted successfully.');
		return redirect('cheque_details');
	}
	// public function getBank($deptid=null)
	// {
	// 	$data = array();
	// 	$banks = $this->bank->bankList();
	// 	return view('body.chequedetails.bank')
	// 				->withBanks($banks)
	// 				->withData($data);

	// }
	public function checkregno() {

		/* if(Input::get('id') != '')
			$check = DB::table('machine')->where('reg_no',Input::get('reg_no'))->where('id', '!=', Input::get('id'))->count();
		else
			$check = DB::table('machine')->where('reg_no',Input::get('reg_no'))->count(); */
		if(Input::get('id') != '')
			$check = DB::table('machine')->where('chasis_no',Input::get('chasis_no'))->where('id', '!=', Input::get('id'))->count();
		else
			$check = DB::table('machine')->where('chasis_no',Input::get('chasis_no'))->count();
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
	
	
}


