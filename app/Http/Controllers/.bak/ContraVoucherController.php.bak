<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Input;
use Session;
use Response;
use App;
use DB;
use Auth;

class ContraVoucherController extends Controller
{

	public function __construct() {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		
		$this->middleware('auth');
		
	}
	
	public function index() {
		$datas = DB::table('contra_voucher')
		         ->join('contra_voucher_details as CVD','CVD.contra_voucher_id','=','contra_voucher.id')
		        ->where('contra_voucher.status',1)->where('contra_voucher.deleted_at','0000-00-00 00:00:00')->where('CVD.deleted_at','0000-00-00 00:00:00')
		        ->select('contra_voucher.voucher_no','contra_voucher.voucher_date','contra_voucher.voucher_type','contra_voucher.id','contra_voucher.amount','CVD.description','CVD.reference')
		        ->groupBy('CVD.contra_voucher_id')->get();
		        
		 	$prints = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','CV')
							->select('report_view_detail.name','report_view_detail.id')
							->get();       
		        //echo '<pre>';print_r($datas);exit;
		return view('body.contravoucher.index')
		            ->withPrints($prints)
					->withDatas($datas);
	}
	
	public function add() {

        $vchrdata = DB::table('account_setting')
                        ->join('account_master as AM','AM.id','=','account_setting.bank_account_id')
                        ->join('account_master as AM2','AM2.id','=','account_setting.cash_account_id')
                        ->where('account_setting.status',1)->where('account_setting.deleted_at','0000-00-00 00:00:00')->where('account_setting.voucher_type_id',27)
                        ->select('AM.master_name as bank','AM2.master_name as cash','account_setting.bank_account_id','account_setting.cash_account_id',
                        'account_setting.voucher_no')->first();
        //echo '<pre>';print_r($vchrdata);exit;
        $bank = DB::table('account_master')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->where('category','BANK')->select('id','account_id','master_name')->get();
        
        $cash = DB::table('account_master')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->where('category','CASH')->select('id','account_id','master_name')->get();
        $lastid= DB::table('contra_voucher')->where('deleted_at','0000-00-00 00:00:00')->orderBy('id','DESC')->first();
        
        $prints = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','CV')
							->select('report_view_detail.name','report_view_detail.id')
							->get();
							
        //echo '<pre>';print_r($lastid);exit;
		return view('body.contravoucher.add')
                    ->withVchrdata($vchrdata)
                    ->withPrintid($lastid)
					->withBank($bank)
					->withPrints($prints)
                    ->withCash($cash);
	}
	
	
	private function voucherNoGenerate($attributes) {

		$cnt = 0;
		do {
			$jvset = DB::table('account_setting')->where('voucher_type_id', 27)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('prefix','is_prefix','voucher_no')->first();//echo '<pre>';print_r($jvset);exit;
			if($jvset) {
				if($jvset->is_prefix==0) {
					$newattributes['voucher_no'] = $jvset->voucher_no + $cnt;
					$newattributes['vno'] = $jvset->voucher_no + $cnt;
				} else {
					$newattributes['voucher_no'] = $jvset->prefix.($jvset->voucher_no + $cnt);
					$newattributes['vno'] = $jvset->voucher_no + $cnt;
				}
				$newattributes['curno'] = $newattributes['voucher_no'];
			}
            //JAN25
			if(isset($attributes['department_id']) && Session::get('department')==1)
				$inv = DB::table('contra_voucher')->where('voucher_no',$newattributes['voucher_no'])->where('department_id', $attributes['department_id'])->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->count();
			else
				$inv = DB::table('contra_voucher')->where('voucher_no',$newattributes['voucher_no'])->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->count();

			$cnt++;
		} while ($inv!=0);

		return $newattributes;
	}
	
	
	public function save(Request $request) {
        //echo '<pre>';print_r($request->all());exit;
		DB::beginTransaction();
		try {
                $input = $request->all(); //echo '<pre>';print_r($input);exit;
                $attributes['voucher_no'] = $input['voucher_no'];
                //VOUCHER NO INCREMENT LOGIC//
                $increment = false;
				if( $input['curno'] == $input['voucher_no'] ) {
					$newattributes = $this->voucherNoGenerate($input); 
					$attributes['voucher_no'] = $newattributes['voucher_no'];
					$attributes['vno'] = $newattributes['vno'];
					$attributes['curno'] = $newattributes['curno'];
					$increment = true;
				} 
				//VOUCHER NO INCREMENT LOGIC//
    				
                $id = DB::table('contra_voucher')
                                ->insertGetId([
                                    'voucher_no' => $attributes['voucher_no'],
                                    'voucher_date' => ($request->get('voucher_date')=='')?date('Y-m-d'):date('Y-m-d', strtotime($request->get('voucher_date'))),
                                    'voucher_type' => $request->get('voucher_type'),
                                    'amount' => $request->get('debit'),
                                    'status' => 1,
                                    'created_at' => date('Y-m-d h:i:s'),
                                    'created_by' => Auth::User()->id,
                                ]);
                                
                foreach($input['account_id'] as $k=>$row) { 
                    $idrow = DB::table('contra_voucher_details')
                                ->insertGetId([
                                    'contra_voucher_id' => $id,
                                    'account_id' => $input['account_id'][$k],
                                    'description' => $input['description'][$k],
                                    'reference' => $input['reference'][$k],
                                    'amount' => $input['line_amount'][$k],
                                    'tr_type' => $input['account_type'][$k]
                                ]);

                    DB::table('account_transaction')
                            ->insert([
                                'voucher_type' => 'CV',
                                'voucher_type_id' => $idrow,
                                'account_master_id' => $input['account_id'][$k],
                                'transaction_type' => $input['account_type'][$k],
                                'amount' => $input['line_amount'][$k],
                                'status' => 1,
                                'created_at' => date('Y-m-d h:i:s'),
                                'created_by' => 1,
                                'description' => $input['description'][$k],
                                'reference' => $input['reference'][$k],
                                'invoice_date' => ($request->get('voucher_date')=='')?date('Y-m-d'):date('Y-m-d', strtotime($request->get('voucher_date'))),
                                'description' => $input['description'][$k]
                            ]);
                }

                if($increment)
                    DB::table('account_setting')->where('voucher_type_id',27)->update(['voucher_no' => DB::raw('voucher_no + 1')]);
                
                DB::commit();
                Session::flash('message', 'Contra voucher added successfully.');
                return redirect('contra_voucher/add');
            
        } catch(\Exception $e) {
            
            DB::rollback(); echo $e->getLine().' '.$e->getMessage();exit;
            return redirect('contra_voucher/add');
        }
		
	}
	
	public function edit($id) { 

		$row = DB::table('contra_voucher')->where('id',$id)->first();

        $items = DB::table('contra_voucher_details')
                    ->join('account_master as AM','AM.id','=','contra_voucher_details.account_id')
                    ->where('contra_voucher_details.contra_voucher_id',$id)
                    ->where('contra_voucher_details.deleted_at','0000-00-00 00:00:00')
                    ->select('contra_voucher_details.*','AM.master_name')
                    ->orderBy('contra_voucher_details.id','ASC')
                    ->get();

        $bank = DB::table('account_master')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->where('category','BANK')->select('id','account_id','master_name')->get();
        $cash = DB::table('account_master')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->where('category','CASH')->select('id','account_id','master_name')->get();
        $prints = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','CV')
							->select('report_view_detail.name','report_view_detail.id')
							->get();    
		return view('body.contravoucher.edit')
					->withItems($items)
                    ->withBank($bank)
                    ->withCash($cash)
                    ->withPrints($prints)
                    ->withRow($row);
	}
	
	public function update(Request $request, $id)
	{
		DB::beginTransaction();
		try {
                $input = $request->all(); //echo '<pre>';print_r($input);exit;
                $id = DB::table('contra_voucher')
                                ->where('id',$id)
                                ->update([
                                    'voucher_date' => ($request->get('voucher_date')=='')?date('Y-m-d'):date('Y-m-d', strtotime($request->get('voucher_date'))),
                                    'amount' => $request->get('debit'),
                                    'modify_at' => date('Y-m-d h:i:s'),
                                    'modify_by' => Auth::User()->id,
                                ]);
                                
                foreach($input['account_id'] as $k=>$row) { 
                    $idrow = DB::table('contra_voucher_details')
                                ->where('id', $input['row_id'][$k])
                                ->update([
                                    'account_id' => $input['account_id'][$k],
                                    'description' => $input['description'][$k],
                                    'reference' => $input['reference'][$k],
                                    'amount' => $input['line_amount'][$k],
                                    'tr_type' => $input['account_type'][$k]
                                ]);

                    DB::table('account_transaction')
                            ->where('voucher_type', 'CV')
                            ->where('voucher_type_id', $input['row_id'][$k])
                            ->where('account_master_id', $input['old_account_id'][$k])
                            ->where('transaction_type', $input['account_type'][$k])
                            ->update([
                                'account_master_id' => $input['account_id'][$k],
                                'amount' => $input['line_amount'][$k],
                                'modify_at' => date('Y-m-d h:i:s'),
                                'modify_by' => Auth::User()->id,
                                'description' => $input['description'][$k],
                                'reference' => $input['reference'][$k],
                                'invoice_date' => date('Y-m-d', strtotime($input['voucher_date'])),
                                'description' => $input['description'][$k]
                            ]);
                }

                
                DB::commit();
                Session::flash('message', 'Contra voucher updated successfully.');
            
        } catch(\Exception $e) {
            
            DB::rollback(); echo $e->getLine().' '.$e->getMessage();exit;
        }

		return redirect('contra_voucher');
	}
	
	
	public function printGrp($id,$rid=null)
	{
	    $viewfile = DB::table('report_view_detail')->where('id', $rid)->select('print_name')->first(); 
	    if($viewfile->print_name=='') {
		$voucherhead = 'Contra Voucher';
		
		$crrow = DB::table('contra_voucher')->where('id',$id)->first();
		
		$invoicerow = DB::table('contra_voucher_details')
                    ->join('account_master as AM','AM.id','=','contra_voucher_details.account_id')
                    ->where('contra_voucher_details.contra_voucher_id',$id)
                    ->select('contra_voucher_details.*','AM.master_name')
                    ->orderBy('contra_voucher_details.id','ASC')
                    ->get(); 
		//echo '<pre>';print_r($invoicerow);exit;		
		$words = $this->number_to_word($crrow->amount);
		$arr = explode('.',number_format($crrow->amount,2));
		if(sizeof($arr) >1 ) {
			if($arr[1]!=00) {
				$dec = $this->number_to_word($arr[1]);
				$words .= ' and Fils '.$dec.' Only';
			} else 
				$words .= ' Only';
		} else
			$words .= ' Only'; 
	   
		return view('body.contravoucher.printgrp')
					->withVoucherhead($voucherhead)
					->withDetails($crrow)
					->withInvoicerow($invoicerow)
					->withAmtwords($words);
	    }
	    else {
					
			$path = app_path() . '/stimulsoft/helper.php';
			return view('body.purchasevoucher.viewer')->withPath($path)->withView($viewfile->print_name);
		}
	}
	
	private function number_to_word( $num = '' )
	{
		$num    = ( string ) ( ( int ) $num );
	   
		if( ( int ) ( $num ) && ctype_digit( $num ) )
		{
			$words  = array( );
		   
			$num    = str_replace( array( ',' , ' ' ) , '' , trim( $num ) );
		   
			$list1  = array('','one','two','three','four','five','six','seven',
				'eight','nine','ten','eleven','twelve','thirteen','fourteen',
				'fifteen','sixteen','seventeen','eighteen','nineteen');
		   
			$list2  = array('','ten','twenty','thirty','forty','fifty','sixty',
				'seventy','eighty','ninety','hundred');
		   
			$list3  = array('','thousand','million','billion','trillion',
				'quadrillion','quintillion','sextillion','septillion',
				'octillion','nonillion','decillion','undecillion',
				'duodecillion','tredecillion','quattuordecillion',
				'quindecillion','sexdecillion','septendecillion',
				'octodecillion','novemdecillion','vigintillion');
		   
			$num_length = strlen( $num );
			$levels = ( int ) ( ( $num_length + 2 ) / 3 );
			$max_length = $levels * 3;
			$num    = substr( '00'.$num , -$max_length );
			$num_levels = str_split( $num , 3 );
		   
			foreach( $num_levels as $num_part )
			{
				$levels--;
				$hundreds   = ( int ) ( $num_part / 100 );
				$hundreds   = ( $hundreds ? ' ' . $list1[$hundreds] . ' Hundred' . ( $hundreds == 1 ? '' : 's' ) . ' ' : '' );
				$tens       = ( int ) ( $num_part % 100 );
				$singles    = '';
			   
				if( $tens < 20 )
				{
					$tens   = ( $tens ? ' ' . $list1[$tens] . ' ' : '' );
				}
				else
				{
					$tens   = ( int ) ( $tens / 10 );
					$tens   = ' ' . $list2[$tens] . ' ';
					$singles    = ( int ) ( $num_part % 10 );
					$singles    = ' ' . $list1[$singles] . ' ';
				}
				$words[]    = $hundreds . $tens . $singles . ( ( $levels && ( int ) ( $num_part ) ) ? ' ' . $list3[$levels] . ' ' : '' );
			}
		   
			$commas = count( $words );
		   
			if( $commas > 1 )
			{
				$commas = $commas - 1;
			}
		   
			$words  = implode( ', ' , $words );
		   
			//Some Finishing Touch
			//Replacing multiples of spaces with one space
			$words  = trim( str_replace( ' ,' , ',' , $this->trim_all( ucwords( $words ) ) ) , ', ' );
			if( $commas )
			{
				$words  = $this->str_replace_last( ',' , ' and' , $words );
			}
		   
			return $words;
		}
		else if( ! ( ( int ) $num ) )
		{
			return 'Zero';
		}
		return '';
	}
	
	private function trim_all( $str , $what = NULL , $with = ' ' )
	{
		if( $what === NULL )
		{
			//  Character      Decimal      Use
			//  "\0"            0           Null Character
			//  "\t"            9           Tab
			//  "\n"           10           New line
			//  "\x0B"         11           Vertical Tab
			//  "\r"           13           New Line in Mac
			//  " "            32           Space
		   
			$what   = "\\x00-\\x20";    //all white-spaces and control chars
		}
	   
		return trim( preg_replace( "/[".$what."]+/" , $with , $str ) , $what );
	}
	
	private function str_replace_last( $search , $replace , $str ) {
		if( ( $pos = strrpos( $str , $search ) ) !== false ) {
			$search_length  = strlen( $search );
			$str    = substr_replace( $str , $replace , $pos , $search_length );
		}
		return $str;
	}
	
	
	public function destroy($id)
	{
		DB::table('contra_voucher')->where('id',$id)->update(['deleted_at' => date('Y-m-d h:i:s'),'deleted_by' => Auth::User()->id ]);
        DB::table('contra_voucher_details')->where('contra_voucher_id',$id)->update(['deleted_at' => date('Y-m-d h:i:s')]);
        $rows = DB::table('contra_voucher_details')->where('contra_voucher_id',$id)->select('id')->get();
        foreach($rows as $row) {
            $ids[] = $row->id;
        }
        DB::table('account_transaction')->where('voucher_type','CV')->whereIn('voucher_type_id',$ids)->update(['status' => 0, 'deleted_at' => date('Y-m-d h:i:s')]);
		Session::flash('message', 'Contra voucher deleted successfully.');
		return redirect('contra_voucher');
	}
	
	public function checkVchrNo() {

		$check = DB::table('contra_voucher')->where('voucher_no', Input::get('voucher_no'))->where('deleted_at','0000-00-00 00:00:00')->count();
		$isAvailable = ($check) ? false : true;
		echo json_encode(array('valid' => $isAvailable));
	}
	
	
}
