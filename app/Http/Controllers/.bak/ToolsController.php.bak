<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\AccountMaster\AccountMasterInterface;
use App\Repositories\Itemmaster\ItemmasterInterface;

use App\Http\Requests;
use Notification;
use Input;
use Session;
use App;
use DB;
use Excel;
use Auth;

class ToolsController extends Controller
{
   
	protected $accountmaster;
	protected $itemmaster;

	public function __construct(AccountMasterInterface $accountmaster, ItemmasterInterface $itemmaster) {

		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->accountmaster = $accountmaster;
		$this->itemmaster = $itemmaster;
		$this->middleware('auth');

	}
	
	public function index() {
	
		return view('body.tools.index');
	}
	
	
	public function search($type, Request $request) { //print_r($request->all());echo $request['date_from'];exit;
		
		/*$ss = DB::table('sales_split')->where('deleted_at','!=','0000-00-00 00:00:00')->get();
		foreach($ss as $rw) {
		    DB::table('account_transaction')->where('voucher_type','SS')->where('voucher_type_id',$rw->id)->update(['status'=>0,'deleted_at'=>'2021-08-01 05:05:05']);
		}
		echo '<pre>';print_r($ar);exit;*/
		
		$date_from = ($request['date_from']!='')?date('Y-m-d', strtotime($request['date_from'])):'2021-01-01';
		$date_to = ($request['date_to']!='')?date('Y-m-d', strtotime($request['date_to'])):date('Y-m-d');
		$dinc = $dexp = [];
		
		$AcntsLB = DB::table('account_master')
									->join('account_category', 'account_category.id', '=', 'account_master.account_category_id')
									->where('account_category.parent_id',2)
									->where('account_category.status',1)
									->where('account_master.status',1)
									->where('account_master.deleted_at','0000-00-00 00:00:00')
									->select('account_master.master_name','account_master.id')->get();
		
		foreach($AcntsLB as $row) {
			
			$pi = DB::table('purchase_invoice')->where('supplier_id',$row->id)->where('status',1)->whereBetween('voucher_date',[$date_from, $date_to])->where('deleted_at','0000-00-00 00:00:00')->select(DB::raw('SUM(net_amount) AS amount'))->groupBy('supplier_id')->first();
			$pitr = DB::table('account_transaction')->where('voucher_type','PI')->where('status',1)->whereBetween('invoice_date',[$date_from, $date_to])->where('deleted_at','0000-00-00 00:00:00')->where('account_master_id',$row->id)->select(DB::raw('SUM(amount) AS amount'))->groupBy('account_master_id')->first();
			//echo '<pre>';print_r($psi);exit;
			$pi = ($pi)?$pi->amount:0; $pitr = ($pitr)?$pitr->amount:0;
			
			$si = DB::table('sales_invoice')->where('customer_id',$row->id)->where('status',1)->whereBetween('voucher_date',[$date_from, $date_to])->where('deleted_at','0000-00-00 00:00:00')->select(DB::raw('SUM(net_total) AS amount'))->groupBy('customer_id')->first();
			$sitr = DB::table('account_transaction')->where('voucher_type','SI')->where('status',1)->whereBetween('invoice_date',[$date_from, $date_to])->where('deleted_at','0000-00-00 00:00:00')->where('account_master_id',$row->id)->select(DB::raw('SUM(amount) AS amount'))->groupBy('account_master_id')->first();
			$si = ($si)?$si->amount:0; $sitr = ($sitr)?$sitr->amount:0;
			
			$rv = DB::table('receipt_voucher_entry AS RVE')->join('receipt_voucher AS RE','RE.id','=','RVE.receipt_voucher_id')->whereBetween('RE.voucher_date',[$date_from, $date_to])->where('RVE.account_id',$row->id)->where('RVE.status',1)->where('RVE.deleted_at','0000-00-00 00:00:00')->where('RE.status',1)->where('RE.deleted_at','0000-00-00 00:00:00')->select(DB::raw('SUM(RVE.amount) AS amount'))->groupBy('RVE.account_id')->first();
			$rvtr = DB::table('account_transaction')->where('voucher_type','RV')->where('status',1)->whereBetween('invoice_date',[$date_from, $date_to])->where('deleted_at','0000-00-00 00:00:00')->where('account_master_id',$row->id)->select(DB::raw('SUM(amount) AS amount'))->groupBy('account_master_id')->first();
			$rv = ($rv)?$rv->amount:0; $rvtr = ($rvtr)?$rvtr->amount:0;
			
			$pv = DB::table('payment_voucher_entry AS PVE')->join('payment_voucher AS PE','PE.id','=','PVE.payment_voucher_id')->whereBetween('PE.voucher_date',[$date_from, $date_to])->where('PVE.account_id',$row->id)->where('PVE.status',1)->where('PVE.deleted_at','0000-00-00 00:00:00')->where('PE.status',1)->where('PE.deleted_at','0000-00-00 00:00:00')->select(DB::raw('SUM(PVE.amount) AS amount'))->groupBy('PVE.account_id')->first();
			$pvtr = DB::table('account_transaction')->where('voucher_type','PV')->where('status',1)->whereBetween('invoice_date',[$date_from, $date_to])->where('deleted_at','0000-00-00 00:00:00')->where('account_master_id',$row->id)->select(DB::raw('SUM(amount) AS amount'))->groupBy('account_master_id')->first();
			$pv = ($pv)?$pv->amount:0;	$pvtr = ($pvtr)?$pvtr->amount:0;
			
			$jv = DB::table('journal_entry AS JE')->join('journal AS J','J.id','=','JE.journal_id')->whereBetween('J.voucher_date',[$date_from, $date_to])->where('JE.account_id',$row->id)->where('JE.status',1)->where('JE.deleted_at','0000-00-00 00:00:00')->where('J.status',1)->where('J.deleted_at','0000-00-00 00:00:00')->select(DB::raw('SUM(JE.amount) AS amount'))->groupBy('JE.account_id')->first();
			$jvtr = DB::table('account_transaction')->where('voucher_type','JV')->where('status',1)->whereBetween('invoice_date',[$date_from, $date_to])->where('deleted_at','0000-00-00 00:00:00')->where('account_master_id',$row->id)->select(DB::raw('SUM(amount) AS amount'))->groupBy('account_master_id')->first();
			$jv = ($jv)?$jv->amount:0; $jvtr = ($jvtr)?$jvtr->amount:0;
			
			$pc = DB::table('petty_cash_entry AS PCE')->join('petty_cash AS PC','PC.id','=','PCE.petty_cash_id')->whereBetween('PC.voucher_date',[$date_from, $date_to])->where('PCE.account_id',$row->id)->where('PCE.status',1)->where('PCE.deleted_at','0000-00-00 00:00:00')->where('PC.status',1)->where('PC.deleted_at','0000-00-00 00:00:00')->select(DB::raw('SUM(PCE.amount) AS amount'))->groupBy('PCE.account_id')->first();
			$pctr = DB::table('account_transaction')->where('voucher_type','PC')->where('status',1)->whereBetween('invoice_date',[$date_from, $date_to])->where('deleted_at','0000-00-00 00:00:00')->where('account_master_id',$row->id)->select(DB::raw('SUM(amount) AS amount'))->groupBy('account_master_id')->first();
			$pc = ($pc)?$pc->amount:0; $pctr = ($pctr)?$pctr->amount:0;
			
			if($pi==0 && $si==0 && $rv==0 && $pv==0 && $jv==0 && $pc)
				$s=0;
			else
				$lb[] = (object)['id'=>$row->id,'name'=>$row->master_name,'pi'=>(object)['ac'=>$pi,'tr'=>$pitr],'si'=>(object)['ac'=> $si ,'tr'=> $sitr ],'rv'=>(object)['ac'=> $rv,'tr'=> $rvtr],'pv'=>(object)['ac'=> $pv,'tr'=> $pvtr ],'jv'=>(object)['ac'=> $jv,'tr'=> $jvtr],'pc'=>(object)['ac'=> $pc,'tr'=> $pctr]];
		}
		
		return view('body.tools.search')->withLb($lb)->withDexp($dexp)->withDinc($dinc);
		
		//echo '<pre>';print_r($lb);exit;
		$AcntsDE = DB::table('account_master')
									->join('account_category', 'account_category.id', '=', 'account_master.account_category_id')
									->where('account_category.parent_id',6)//direct expense
									->where('account_category.status',1)
									->where('account_master.status',1)
									->where('account_master.deleted_at','0000-00-00 00:00:00')
									->select('account_master.master_name','account_master.id')->get();
									
		foreach($AcntsDE as $row) {
			
			$ps = DB::table('purchase_split')->where('supplier_id',$row->id)->where('status',1)->whereBetween('voucher_date',[$date_from, $date_to])->where('deleted_at','0000-00-00 00:00:00')->select(DB::raw('SUM(net_amount) AS amount'))->groupBy('supplier_id')->first();
			$psi = DB::table('purchase_split_item AS PSI')->join('purchase_split AS PS','PS.id','=','PSI.purchase_split_id')->whereBetween('PS.voucher_date',[$date_from, $date_to])->where('PSI.account_id',$row->id)->where('PSI.status',1)->where('PSI.deleted_at','0000-00-00 00:00:00')->where('PS.status',1)->where('PS.deleted_at','0000-00-00 00:00:00')->select(DB::raw('SUM(PSI.line_total) AS amount'))->groupBy('PSI.account_id')->first();
			$pstr = DB::table('account_transaction')->where('voucher_type','PS')->where('status',1)->whereBetween('invoice_date',[$date_from, $date_to])->where('deleted_at','0000-00-00 00:00:00')->where('account_master_id',$row->id)->select(DB::raw('SUM(amount) AS amount'))->groupBy('account_master_id')->first();
			//echo '<pre>';print_r($psi);exit;

			$ss = DB::table('sales_split')->where('customer_id',$row->id)->where('status',1)->whereBetween('voucher_date',[$date_from, $date_to])->where('deleted_at','0000-00-00 00:00:00')->select(DB::raw('SUM(net_amount) AS amount'))->groupBy('customer_id')->first();
			$ssi = DB::table('sales_split_item AS SSI')->join('sales_split AS SS','SS.id','=','SSI.sales_split_id')->whereBetween('SS.voucher_date',[$date_from, $date_to])->where('SSI.account_id',$row->id)->where('SSI.status',1)->where('SSI.deleted_at','0000-00-00 00:00:00')->where('SS.status',1)->where('SS.deleted_at','0000-00-00 00:00:00')->select(DB::raw('SUM(SSI.line_total) AS amount'))->groupBy('SSI.account_id')->first();
			$sstr = DB::table('account_transaction')->where('voucher_type','SS')->where('status',1)->whereBetween('invoice_date',[$date_from, $date_to])->where('deleted_at','0000-00-00 00:00:00')->where('account_master_id',$row->id)->select(DB::raw('SUM(amount) AS amount'))->groupBy('account_master_id')->first();
			
			$rv = DB::table('receipt_voucher_entry AS RVE')->join('receipt_voucher AS RE','RE.id','=','RVE.receipt_voucher_id')->whereBetween('RE.voucher_date',[$date_from, $date_to])->where('RVE.account_id',$row->id)->where('RVE.status',1)->where('RVE.deleted_at','0000-00-00 00:00:00')->where('RE.status',1)->where('RE.deleted_at','0000-00-00 00:00:00')->select(DB::raw('SUM(RVE.amount) AS amount'))->groupBy('RVE.account_id')->first();
			$rvtr = DB::table('account_transaction')->where('voucher_type','RV')->where('status',1)->whereBetween('invoice_date',[$date_from, $date_to])->where('deleted_at','0000-00-00 00:00:00')->where('account_master_id',$row->id)->select(DB::raw('SUM(amount) AS amount'))->groupBy('account_master_id')->first();
			
			$pv = DB::table('payment_voucher_entry AS PVE')->join('payment_voucher AS PE','PE.id','=','PVE.payment_voucher_id')->whereBetween('PE.voucher_date',[$date_from, $date_to])->where('PVE.account_id',$row->id)->where('PVE.status',1)->where('PVE.deleted_at','0000-00-00 00:00:00')->where('PE.status',1)->where('PE.deleted_at','0000-00-00 00:00:00')->select(DB::raw('SUM(PVE.amount) AS amount'))->groupBy('PVE.account_id')->first();
			$pvtr = DB::table('account_transaction')->where('voucher_type','PV')->where('status',1)->whereBetween('invoice_date',[$date_from, $date_to])->where('deleted_at','0000-00-00 00:00:00')->where('account_master_id',$row->id)->select(DB::raw('SUM(amount) AS amount'))->groupBy('account_master_id')->first();
			
			$jv = DB::table('journal_entry AS JE')->join('journal AS J','J.id','=','JE.journal_id')->whereBetween('J.voucher_date',[$date_from, $date_to])->where('JE.account_id',$row->id)->where('JE.status',1)->where('JE.deleted_at','0000-00-00 00:00:00')->where('J.status',1)->where('J.deleted_at','0000-00-00 00:00:00')->select(DB::raw('SUM(JE.amount) AS amount'))->groupBy('JE.account_id')->first();
			$jvtr = DB::table('account_transaction')->where('voucher_type','JV')->where('status',1)->whereBetween('invoice_date',[$date_from, $date_to])->where('deleted_at','0000-00-00 00:00:00')->where('account_master_id',$row->id)->select(DB::raw('SUM(amount) AS amount'))->groupBy('account_master_id')->first();
			
			$pc = DB::table('petty_cash_entry AS PCE')->join('petty_cash AS PC','PC.id','=','PCE.petty_cash_id')->whereBetween('PC.voucher_date',[$date_from, $date_to])->where('PCE.account_id',$row->id)->where('PCE.status',1)->where('PCE.deleted_at','0000-00-00 00:00:00')->where('PC.status',1)->where('PC.deleted_at','0000-00-00 00:00:00')->select(DB::raw('SUM(PCE.amount) AS amount'))->groupBy('PCE.account_id')->first();
			$pctr = DB::table('account_transaction')->where('voucher_type','PC')->where('status',1)->whereBetween('invoice_date',[$date_from, $date_to])->where('deleted_at','0000-00-00 00:00:00')->where('account_master_id',$row->id)->select(DB::raw('SUM(amount) AS amount'))->groupBy('account_master_id')->first();
			
			
			$ps = ($ps)?$ps->amount:0; $psi = ($psi)?$psi->amount:0; $pstr = ($pstr)?$pstr->amount:0;
			$ss = ($ss)?$ss->amount:0; $ssi = ($ssi)?$ssi->amount:0; $sstr = ($sstr)?$sstr->amount:0;
			$rv = ($rv)?$rv->amount:0; $pv = ($pv)?$pv->amount:0;	$pvtr = ($pvtr)?$pvtr->amount:0; $rvtr = ($rvtr)?$rvtr->amount:0;
			$jv = ($jv)?$jv->amount:0; $pc = ($pc)?$pc->amount:0;	$jvtr = ($jvtr)?$jvtr->amount:0; $pctr = ($pctr)?$pctr->amount:0;
			
			if($ps==0&&$psi==0&&$ss==0&&$ssi==0&&$rv==0&&$pv==0&&$jv==0&&$pc==0)
				$s=0;
			else
				$dexp[] = (object)['id'=>$row->id,'name'=>$row->master_name,'ps'=>(object)['ac'=>$ps+$psi,'tr'=>$pstr],'ss'=>(object)['ac'=>$ss+$ssi,'tr'=>$sstr],'rv'=>(object)['ac'=>$rv,'tr'=>$rvtr],'pv'=>(object)['ac'=>$pv,'tr'=>$pvtr],'jv'=>(object)['ac'=>$jv,'tr'=>$jvtr],'pc'=>(object)['ac'=>$pc,'tr'=>$pctr]];
			
			//$res[] = (object)['id'=>$row->id,'name'=>$row->master_name,'ps'=>$ps,'ss'=>['ac'=>$ss+$ssi,'tr'=>$sstr],'rv'=>['ac'=>$rv,'tr'=>$rvtr],'pv'=>['ac'=>$pv,'tr'=>$pvtr],'jv'=>['ac'=>$jv,'tr'=>$jvtr],'pc'=>['ac'=>$pc,'tr'=>$pctr]];
		}
		
		//echo '<pre>';print_r($dexp);exit; RV.status
		$AcntsDI = DB::table('account_master')
									->join('account_category', 'account_category.id', '=', 'account_master.account_category_id')
									->where('account_category.parent_id',4)//direct income
									->where('account_category.status',1)
									->where('account_master.status',1)
									->where('account_master.deleted_at','0000-00-00 00:00:00')
									->select('account_master.master_name','account_master.id')->get();
		//echo '<pre>';print_r($AcntsDI);exit;	
		
		
		//$ss = DB::table('sales_split')->where('customer_id',1971)->where('status',1)->whereBetween('voucher_date',[$date_from, $date_to])->where('deleted_at','0000-00-00 00:00:00')->get();
	//	$ssi = DB::table('sales_split_item AS SSI')->join('sales_split AS SS','SS.id','=','SSI.sales_split_id')->whereBetween('SS.voucher_date',[$date_from, $date_to])->where('SSI.account_id',1971)->where('SSI.status',1)->where('SSI.deleted_at','0000-00-00 00:00:00')->where('SS.status',1)->where('SS.deleted_at','0000-00-00 00:00:00')->get();
		//echo '<pre>';print_r($ssi);exit;
		
		foreach($AcntsDI as $row) {
			
			$ps = DB::table('purchase_split')->where('supplier_id',$row->id)->where('status',1)->whereBetween('voucher_date',[$date_from, $date_to])->where('deleted_at','0000-00-00 00:00:00')->select(DB::raw('SUM(net_amount) AS amount'))->groupBy('supplier_id')->first();
			$psi = DB::table('purchase_split_item AS PSI')->join('purchase_split AS PS','PS.id','=','PSI.purchase_split_id')->whereBetween('PS.voucher_date',[$date_from, $date_to])->where('PSI.account_id',$row->id)->where('PSI.status',1)->where('PSI.deleted_at','0000-00-00 00:00:00')->where('PS.status',1)->where('PS.deleted_at','0000-00-00 00:00:00')->select(DB::raw('SUM(PSI.line_total) AS amount'))->groupBy('PSI.account_id')->first();
			$pstr = DB::table('account_transaction')->where('voucher_type','PS')->where('status',1)->whereBetween('invoice_date',[$date_from, $date_to])->where('deleted_at','0000-00-00 00:00:00')->where('account_master_id',$row->id)->select(DB::raw('SUM(amount) AS amount'))->groupBy('account_master_id')->first();
			
			$ss = DB::table('sales_split')->where('customer_id',$row->id)->where('status',1)->whereBetween('voucher_date',[$date_from, $date_to])->where('deleted_at','0000-00-00 00:00:00')->select(DB::raw('SUM(net_amount) AS amount'))->groupBy('customer_id')->first();
			$ssi = DB::table('sales_split_item AS SSI')->join('sales_split AS SS','SS.id','=','SSI.sales_split_id')->whereBetween('SS.voucher_date',[$date_from, $date_to])->where('SSI.account_id',$row->id)->where('SSI.status',1)->where('SSI.deleted_at','0000-00-00 00:00:00')->where('SS.status',1)->where('SS.deleted_at','0000-00-00 00:00:00')->select(DB::raw('SUM(SSI.line_total) AS amount'))->groupBy('SSI.account_id')->first();
			$sstr = DB::table('account_transaction')->where('voucher_type','SS')->where('status',1)->whereBetween('invoice_date',[$date_from, $date_to])->where('deleted_at','0000-00-00 00:00:00')->where('account_master_id',$row->id)->select(DB::raw('SUM(amount) AS amount'))->groupBy('account_master_id')->first();
			
			$rv = DB::table('receipt_voucher_entry AS RVE')->join('receipt_voucher AS RE','RE.id','=','RVE.receipt_voucher_id')->whereBetween('RE.voucher_date',[$date_from, $date_to])->where('RVE.account_id',$row->id)->where('RVE.status',1)->where('RVE.deleted_at','0000-00-00 00:00:00')->where('RE.status',1)->where('RE.deleted_at','0000-00-00 00:00:00')->select(DB::raw('SUM(RVE.amount) AS amount'))->groupBy('RVE.account_id')->first();
			$rvtr = DB::table('account_transaction')->where('voucher_type','RV')->where('status',1)->whereBetween('invoice_date',[$date_from, $date_to])->where('deleted_at','0000-00-00 00:00:00')->where('account_master_id',$row->id)->select(DB::raw('SUM(amount) AS amount'))->groupBy('account_master_id')->first();
			
			$pv = DB::table('payment_voucher_entry AS PVE')->join('payment_voucher AS PE','PE.id','=','PVE.payment_voucher_id')->whereBetween('PE.voucher_date',[$date_from, $date_to])->where('PVE.account_id',$row->id)->where('PVE.status',1)->where('PVE.deleted_at','0000-00-00 00:00:00')->where('PE.status',1)->where('PE.deleted_at','0000-00-00 00:00:00')->select(DB::raw('SUM(PVE.amount) AS amount'))->groupBy('PVE.account_id')->first();
			$pvtr = DB::table('account_transaction')->where('voucher_type','PV')->where('status',1)->whereBetween('invoice_date',[$date_from, $date_to])->where('deleted_at','0000-00-00 00:00:00')->where('account_master_id',$row->id)->select(DB::raw('SUM(amount) AS amount'))->groupBy('account_master_id')->first();
			
			$jv = DB::table('journal_entry AS JE')->join('journal AS J','J.id','=','JE.journal_id')->whereBetween('J.voucher_date',[$date_from, $date_to])->where('JE.account_id',$row->id)->where('JE.status',1)->where('JE.deleted_at','0000-00-00 00:00:00')->where('J.status',1)->where('J.deleted_at','0000-00-00 00:00:00')->select(DB::raw('SUM(JE.amount) AS amount'))->groupBy('JE.account_id')->first();
			$jvtr = DB::table('account_transaction')->where('voucher_type','JV')->where('status',1)->whereBetween('invoice_date',[$date_from, $date_to])->where('deleted_at','0000-00-00 00:00:00')->where('account_master_id',$row->id)->select(DB::raw('SUM(amount) AS amount'))->groupBy('account_master_id')->first();
			
			$pc = DB::table('petty_cash_entry AS PCE')->join('petty_cash AS PC','PC.id','=','PCE.petty_cash_id')->whereBetween('PC.voucher_date',[$date_from, $date_to])->where('PCE.account_id',$row->id)->where('PCE.status',1)->where('PC.deleted_at','0000-00-00 00:00:00')->where('PC.status',1)->where('PCE.deleted_at','0000-00-00 00:00:00')->select(DB::raw('SUM(PCE.amount) AS amount'))->groupBy('PCE.account_id')->first();
			$pctr = DB::table('account_transaction')->where('voucher_type','PC')->where('status',1)->whereBetween('invoice_date',[$date_from, $date_to])->where('deleted_at','0000-00-00 00:00:00')->where('account_master_id',$row->id)->select(DB::raw('SUM(amount) AS amount'))->groupBy('account_master_id')->first();
			
			$ps = ($ps)?$ps->amount:0; $psi = ($psi)?$psi->amount:0; $pstr = ($pstr)?$pstr->amount:0;
			$ss = ($ss)?$ss->amount:0; $ssi = ($ssi)?$ssi->amount:0; $sstr = ($sstr)?$sstr->amount:0;
			$rv = ($rv)?$rv->amount:0; $pv = ($pv)?$pv->amount:0;	$pvtr = ($pvtr)?$pvtr->amount:0; $rvtr = ($rvtr)?$rvtr->amount:0;
			$jv = ($jv)?$jv->amount:0; $pc = ($pc)?$pc->amount:0;	$jvtr = ($jvtr)?$jvtr->amount:0; $pctr = ($pctr)?$pctr->amount:0;
			
			if($ps==0&&$psi==0&&$ss==0&&$ssi==0&&$rv==0&&$pv==0&&$jv==0&&$pc==0)
				$s=0;
			else
				$dinc[] = (object)['id'=>$row->id,'name'=>$row->master_name,'ps'=>(object)['ac'=>$ps+$psi,'tr'=>$pstr],'ss'=>(object)['ac'=>$ss+$ssi,'tr'=>$sstr],'rv'=>(object)['ac'=>$rv,'tr'=>$rvtr],'pv'=>(object)['ac'=>$pv,'tr'=>$pvtr],'jv'=>(object)['ac'=>$jv,'tr'=>$jvtr],'pc'=>(object)['ac'=>$pc,'tr'=>$pctr]];
			
		}
									
		//echo '<pre>';print_r($dinc);exit;
		return view('body.tools.search')->withAlltrn($alltrn)->withDexp($dexp)->withDinc($dinc);
	}
	
	private function getSum($results) {
		$arrData = array();
		foreach($results as $result)
		{	
			$dr = $cr = 0;
			foreach($result as $row) {
				
				if($row->transaction_type=='Dr')
					$dr += $row->amount;
				else
					$cr += $row->amount;
				
				$name = $row->master_name;
			}
			
			$arrData[] = (object)['name' => $name, 'dr' => $dr, 'cr' => $cr];
			
		}
		return $arrData;
	}
	
	protected function groupByAccount($result)
	{
		$childs = array();
		foreach($result as $item)
			$childs[$item->id][] = $item;

		return $childs;
	}
}