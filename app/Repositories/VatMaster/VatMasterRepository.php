<?php
declare(strict_types=1);
namespace App\Repositories\VatMaster;

use App\Models\VatMaster;
use App\Repositories\AbstractValidator;
use App\Exceptions\Validation\ValidationException;
use Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class VatMasterRepository extends AbstractValidator implements VatMasterInterface {
	
	protected $vat_master;
	
	protected static $rules = [
		//'code' => 'required|unique:vat_master',
		//'name' => 'required|unique:vat_master',
	];
	
	public function __construct(VatMaster $vat_master) {
		$this->vat_master = $vat_master;
		
	}
	
	public function all()
	{
		if(Session::get('department')==1)
			return $this->vat_master->where('is_department', 1)->get();
		else
			return $this->vat_master->where('is_department', 0)->get();
	}
	
	public function find($id)
	{
		return $this->vat_master->where('id', $id)->first();
	}
	
	private function setInputValue($attributes)
	{
		$this->vat_master->code 			  = $attributes['code'];
		$this->vat_master->name 			  = $attributes['name'];
		$this->vat_master->percentage 		  = $attributes['percentage'];
		$this->vat_master->vat_cal			  = 1.0500;
		$this->vat_master->collection_account = $attributes['collection_account'];
		$this->vat_master->payment_account 	  = $attributes['payment_account'];
		$this->vat_master->expense_account 	  = $attributes['expense_account'];
		$this->vat_master->vatinput_import 	  = $attributes['vatinput_import'];
		$this->vat_master->vatoutput_import   = $attributes['vatoutput_import'];
		
		return true;
	}
	
	public function create($parameter)
	{
		if($this->isValid($parameter)) {
			
			if( $this->setInputValue($parameter) ){
				
				//Deprtment Accounts update...
				if(Session::get('department')==1) {
					$id = DB::table('vat_master')->insertGetId(['code' => $parameter['code'], 
														'name' => $parameter['name'], 
														'percentage' => $parameter['percentage'],
														'vat_cal'	=> 1.05,
														'is_department' => 1 
													]);
					
					foreach($parameter['did'] as $k => $rw) {
						//add
						if($rw=='') {
								DB::table('vat_department')
											->insert(['vatmaster_id' => $id,
													  'department_id' => $parameter['department_id'][$k],
													  'collection_account' => $parameter['collection_account'][$k],
													  'payment_account' => $parameter['payment_account'][$k],
													  'expense_account' => $parameter['expense_account'][$k],
													  'vatinput_import' => $parameter['vatinput_import'][$k],
													  'vatoutput_import' => $parameter['vatoutput_import'][$k]
													 ]);
						} else { //update
								DB::table('vat_department')
											->where('id', $rw)
											->update(['department_id' => $parameter['department_id'][$k],
													  'collection_account' => $parameter['collection_account'][$k],
													  'payment_account' => $parameter['payment_account'][$k],
													  'expense_account' => $parameter['expense_account'][$k],
													  'vatinput_import' => $parameter['vatinput_import'][$k],
													  'vatoutput_import' => $parameter['vatoutput_import'][$k]
													 ]);
						}
					}
				} else {
			
					$this->vat_master->status = 1;
					$this->vat_master->fill($parameter)->save();
				}
				return true;
			}
		}
		//throw new ValidationException('vat_master validation error12!', $this->getErrors());
	}
	
	public function update($id, $parameter)
	{
		//Deprtment Accounts update...
		if(Session::get('department')==1) {
					DB::table('vat_master')
									->where('id', $parameter['vat_id'])
									->update(['code' => $parameter['code'], 
									   'name' => $parameter['name'], 
									   'percentage' => $parameter['percentage']
									]);
													
			foreach($parameter['did'] as $k => $rw) {
				//add
				if($rw=='') {
						DB::table('vat_department')
									->insert(['vatmaster_id' => $parameter['vat_id'],
											  'department_id' => $parameter['department_id'][$k],
											  'collection_account' => $parameter['collection_account'][$k],
											  'payment_account' => $parameter['payment_account'][$k],
											  'expense_account' => $parameter['expense_account'][$k],
											  'vatinput_import' => $parameter['vatinput_import'][$k],
											  'vatoutput_import' => $parameter['vatoutput_import'][$k]
											 ]);
				} else { //update
					
					if($parameter['id'][$k]=='') {
						DB::table('vat_department')
									->insert(['vatmaster_id' => $parameter['vat_id'],
											  'department_id' => $parameter['department_id'][$k],
											  'collection_account' => $parameter['collection_account'][$k],
											  'payment_account' => $parameter['payment_account'][$k],
											  'expense_account' => $parameter['expense_account'][$k],
											  'vatinput_import' => $parameter['vatinput_import'][$k],
											  'vatoutput_import' => $parameter['vatoutput_import'][$k]
											 ]);
					} else {
						DB::table('vat_department')
									->where('id', $parameter['id'][$k])
									->update(['department_id' => $parameter['department_id'][$k],
											  'collection_account' => $parameter['collection_account'][$k],
											  'payment_account' => $parameter['payment_account'][$k],
											  'expense_account' => $parameter['expense_account'][$k],
											  'vatinput_import' => $parameter['vatinput_import'][$k],
											  'vatoutput_import' => $parameter['vatoutput_import'][$k]
											 ]);
											 
						//RESET VAT OLD ACCOUNT TRANSACTIONS WITH NEW ACCOUNT.... 
						if($parameter['collection_account'][$k]!=$parameter['collection_account_old'][$k]) {
							
							DB::table('account_transaction')->where('voucher_type','!=','OB')
													->where('account_master_id', $parameter['collection_account_old'][$k])
													->update(['account_master_id' => $parameter['collection_account'][$k]]);
						}
						
						if($parameter['payment_account'][$k]!=$parameter['payment_account_old'][$k]) {
							
							DB::table('account_transaction')->where('voucher_type','!=','OB')
													->where('account_master_id', $parameter['payment_account_old'][$k])
													->update(['account_master_id' => $parameter['payment_account'][$k]]);
						}
						
						if($parameter['vatinput_import'][$k]!=$parameter['vatinput_import_old'][$k]) {
							
							DB::table('account_transaction')->where('voucher_type','!=','OB')
													->where('account_master_id', $parameter['vatinput_import_old'][$k])
													->update(['account_master_id' => $parameter['vatinput_import'][$k]]);
						}
						
						if($parameter['vatoutput_import'][$k]!=$parameter['vatoutput_import_old'][$k]) {
							
							DB::table('account_transaction')->where('voucher_type','!=','OB')
													->where('account_master_id', $parameter['vatoutput_import_old'][$k])
													->update(['account_master_id' => $parameter['vatoutput_import'][$k]]);
						}
						
						if($parameter['expense_account'][$k]!=$parameter['expense_account_old'][$k]) {
							
							DB::table('account_transaction')->where('voucher_type','!=','OB')
													->where('account_master_id', $parameter['expense_account_old'][$k])
													->update(['account_master_id' => $parameter['expense_account'][$k]]);
						} 
					}
				}
			}
			
		} else {
			$this->vat_master = $this->find($id);
			$this->vat_master->fill($parameter)->save();
			
			//RESET VAT OLD ACCOUNT TRANSACTIONS WITH NEW ACCOUNT.... 
			if($parameter['collection_account']!=$parameter['collection_account_old']) {
				
				DB::table('account_transaction')->where('voucher_type','!=','OB')
										->where('account_master_id', $parameter['collection_account_old'])
										->update(['account_master_id' => $parameter['collection_account']]);
			}
			
			if($parameter['payment_account']!=$parameter['payment_account_old']) {
				
				DB::table('account_transaction')->where('voucher_type','!=','OB')
										->where('account_master_id', $parameter['payment_account_old'])
										->update(['account_master_id' => $parameter['payment_account']]);
			}
			
			if($parameter['vatinput_import']!=$parameter['vatinput_import_old']) {
				
				DB::table('account_transaction')->where('voucher_type','!=','OB')
										->where('account_master_id', $parameter['vatinput_import_old'])
										->update(['account_master_id' => $parameter['vatinput_import']]);
			}
			
			if($parameter['vatoutput_import']!=$parameter['vatoutput_import_old']) {
				
				DB::table('account_transaction')->where('voucher_type','!=','OB')
										->where('account_master_id', $parameter['vatoutput_import_old'])
										->update(['account_master_id' => $parameter['vatoutput_import']]);
			}
			
			if($parameter['expense_account']!=$parameter['expense_account_old']) {
				
				DB::table('account_transaction')->where('voucher_type','!=','OB')
										->where('account_master_id', $parameter['expense_account_old'])
										->update(['account_master_id' => $parameter['expense_account']]);
			} 
		}
		
		return true;
	}
	
	
	public function delete($id)
	{
		$this->vat_master = $this->vat_master->find($id);
		$this->vat_master->delete();
	}
	
	public function vatMasterList()
	{
		return $this->vat_master
					->join('account_master AS am', function($join) {
						$join->on('am.id', '=', 'vat_master.collection_account');
					})
					->join('account_master AS m', function($join) {
						$join->on('m.id', '=', 'vat_master.payment_account');
					})
					->select('vat_master.*','am.master_name','m.master_name AS mname')
					->get();
	}
	
	public function activeVatMasterList()
	{
		return $this->vat_master->select('id','name','percentage')->where('status', 1)->orderBy('name', 'ASC')->get()->toArray();
	}
	
	public function check_vat_master_code($code, $id = null) {
		
		if($id)
			return $this->vat_master->where('code',$code)->where('id', '!=', $id)->count();
		else
			return $this->vat_master->where('code',$code)->count();
	}
		
	public function check_vat_master_name($name, $id = null) {
		
		if($id)
			return $this->vat_master->where('name',$name)->where('id', '!=', $id)->count();
		else
			return $this->vat_master->where('name',$name)->count();
	}
	
	public function getVatAccounts()
	{
		$result = DB::table('account_master')
						->join('account_group', 'account_group.id', '=', 'account_master.account_group_id')
						->where('account_group.category', 'VAT')
						->where('account_group.status', 1)
						->where('account_master.status', 1)
						->select('account_master.id','account_master.master_name')
						->get();
		return $result;
	}
	
	public function getActiveVatMaster()
	{
		$result = $this->vat_master->where('status', 1)->first();
		if(!$result){
			$result = (object)['percentage' => 0];
		}
		
		return $result;
	}
}

