<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Bank\BankInterface;

use App\Http\Requests;
use Input;
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
		

		/*$pi = DB::select('SELECT t1.* FROM item_log t1, item_log t2 WHERE t1.id > t2.id AND (t1.document_type = t2.document_type AND t1.document_id = t2.document_id AND t1.item_id = t2.item_id AND t1.status = t2.status AND t1.deleted_at = t2.deleted_at AND t1.document_type="PI")');
		foreach($pi as $p) {
		   $it =  DB::table('purchase_invoice_item')->where('purchase_invoice_id',$p->document_id)->where('item_id',$p->item_id)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','quantity')->get();
		   $nid = [];
		   foreach($it as $i) {
		       $br = DB::table('item_log')->where('document_type','PI')->whereNOTIN('id',$nid)->where('document_id', $p->document_id)->where('item_id', $p->item_id)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->first();
		       $nid[] = $br->id;  
		       $dr[] = $br; 
		       DB::table('item_log')->where('id',$br->id)->update(['quantity' => $i->quantity]);
		   }
		   //echo '<pre>';print_r($dr);exit;
		}*/
		
		$si = DB::select('SELECT t1.* FROM item_log t1, item_log t2 WHERE t1.id > t2.id AND (t1.document_type = t2.document_type AND t1.document_id = t2.document_id AND t1.item_id = t2.item_id AND t1.status = t2.status AND t1.deleted_at = t2.deleted_at AND t1.document_type="SI")');
		foreach($si as $p) {
		   $it =  DB::table('sales_invoice_item')->where('sales_invoice_id',$p->document_id)->where('item_id',$p->item_id)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','quantity')->get();
		   $nid = [];
		   foreach($it as $i) {
		       $br = DB::table('item_log')->where('document_type','SI')->whereNOTIN('id',$nid)->where('document_id', $p->document_id)->where('item_id', $p->item_id)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->first();
		       if($br) {
    		       $nid[] = $br->id;  
    		       $dr[] = $br; 
    		       DB::table('item_log')->where('id',$br->id)->update(['quantity' => $i->quantity]);
		       }
		       //echo '<pre>';print_r($br);exit;
		   }
		   //echo '<pre>';print_r($dr);exit;
		}
		
		//echo '<pre>';print_r($si);exit;
	/*	$siar = DB::table('account_transaction')->where('voucher_type','SI')->where('reference','=','')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		foreach($siar as $ar) {
			$rw = DB::table('sales_invoice')->where('id',$ar->voucher_type_id)->first();
			DB::table('account_transaction')->where('id',$ar->id)->update(['reference'=>$rw->voucher_no]);
		}
		echo '<pre>';print_r($siar);exit;  */
		
		//$at = DB::select('SELECT t1.* FROM sales_invoice t1, sales_invoice t2 WHERE  t1.id > t2.id AND (t1.voucher_no = t2.voucher_no AND t1.voucher_date = t2.voucher_date AND t1.customer_id = t2.customer_id AND t1.net_total = t2.net_total)');
		/* $ar = DB::select('SELECT t1.id FROM sales_invoice t1, sales_invoice t2 WHERE  t1.id > t2.id AND (t1.voucher_no = t2.voucher_no) GROUP BY t1.id');
		foreach($ar as $r) {
			DB::table('sales_invoice')->where('id',$r->id)->update(['status'=>0,'deleted_at'=>date('Y-m-d H:i:s')]);
			DB::table('sales_invoice_item')->where('sales_invoice_id',$r->id)->update(['status'=>0,'deleted_at'=>date('Y-m-d H:i:s')]);
			DB::table('account_transaction')->where('voucher_type','SI')->where('voucher_type_id',$r->id)->update(['status'=>0,'deleted_at'=>date('Y-m-d H:i:s')]);
			
		}
		echo '<pre>';print_r($ar);exit; */
		
		/* $id=426;
		$srr = DB::table('sales_invoice_item')->where('sales_invoice_id',$id)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','item_id','unit_id')->get();
		foreach($srr as $row) {
			$itm = DB::table('item_unit')->where('itemmaster_id',$row->item_id)->where('is_baseqty',1)->select('unit_id')->first();
			
			if($row->unit_id!=$itm->unit_id) {
				DB::table('sales_invoice_item')->where('id',$row->id)->update(['unit_id' => $itm->unit_id]); 2167,2859
				DB::table('item_log')->where('document_type','SI')->where('document_id',$id)->where('item_id',$row->item_id)->update(['unit_id' => $itm->unit_id]);
			}
		}
		
		echo '<pre>';print_r($itm);exit; */
		
		/* $res = DB::table('receipt_voucher')->whereIn('receipt_voucher.id',[42])
				->leftJoin('receipt_voucher_entry AS CE', function($join) {
						$join->on('CE.receipt_voucher_id','=','receipt_voucher.id');
						$join->where('CE.entry_type','=','Dr');
						$join->where('CE.status','=',1);
						$join->where('CE.deleted_at','=','0000-00-00 00:00:00');
					})
				->get();
		//echo '<pre>';print_r($res);exit;		
		foreach($res as $row) {
			$acrow = DB::table('account_master')->where('status',1)->where('category','BANK')->select('id')->first();
				
			DB::table('pdc_received')
						->insert([  'voucher_id' 	=>  $row->receipt_voucher_id,
									'voucher_type'   => 'DB',
									'dr_account_id' => 6,
									'cr_account_id' => 12,
									'reference'  => $row->reference,
									'amount'   			=> $row->amount,
									'status' 			=> 0,
									'created_at' 		=> date('Y-m-d H:i:s'),
									'created_by' 		=> Auth::User()->id,
									'voucher_date'		=> $row->voucher_date,
									'customer_id' => $row->party_account_id,
									'cheque_no' => $row->cheque_no,
									'cheque_date' => $row->cheque_date,
									'voucher_no' => $row->voucher_no,
									'description' => $row->description
								]);
		}								
		exit; */
		
		$data = array();
		$bank = DB::table('bank')->get();
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
	
	public function save() {
		try {
			$this->bank->create(Input::all());
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
	
	public function update($id)
	{
		$this->bank->update($id, Input::all());//print_r(Input::all());exit;
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
	
	public function checkcode() {

		$check = $this->bank->check_bank_code(Input::get('code'), Input::get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
	
	public function checkname() {

		$check = $this->bank->check_bank_name(Input::get('name'), Input::get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
}
