<?php
declare(strict_types=1);
namespace App\Repositories\AccountSetting;

use App\Models\AccountSetting;
use App\Repositories\AbstractValidator;
use App\Exceptions\Validation\ValidationException;
use Image;
use Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class AccountSettingRepository extends AbstractValidator implements AccountSettingInterface {
	
	protected $accountsetting;
	
	protected static $rules = [
		'voucher_type_id' => 'required',
		'voucher_name' => 'required',
		'voucher_no' => 'required'
		//'dr_account_master_id' => 'required'
	];
	
	public function __construct(AccountSetting $accountsetting) {
		$this->accountsetting = $accountsetting;
		
	}
	
	public function all()
	{
		return $this->accountsetting->get();
	}
	
	public function find($id)
	{
		return $this->accountsetting->where('id', $id)->first();
	}
	
	public function create($attributes)
	{
		if($this->isValid($attributes)) { 
			
			$this->accountsetting->voucher_type_id = $attributes['voucher_type_id'];
			$this->accountsetting->voucher_name = $attributes['voucher_name'];
			$this->accountsetting->department_id = $attributes['department_id'];
			$this->accountsetting->prefix = $attributes['prefix'];
			$this->accountsetting->is_prefix = $attributes['is_prefix'];
			$this->accountsetting->voucher_no = $attributes['voucher_no'];
			$this->accountsetting->dr_account_master_id = isset($attributes['dr_account_master_id'])?$attributes['dr_account_master_id']:'';
			$this->accountsetting->cr_account_master_id = isset($attributes['cr_account_master_id'])?$attributes['cr_account_master_id']:'';
			$this->accountsetting->created_at = now();
			$this->accountsetting->status = 1;
			$this->accountsetting->cash_account_id = isset($attributes['drcash_account_master_id'])?$attributes['drcash_account_master_id']:'';
			$this->accountsetting->bank_account_id = isset($attributes['drbank_account_master_id'])?$attributes['drbank_account_master_id']:'';
			$this->accountsetting->pdc_account_id = isset($attributes['drpdc_account_master_id'])?$attributes['drpdc_account_master_id']:'';
			$this->accountsetting->is_cash_voucher = $attributes['is_cash_voucher'];
			$this->accountsetting->default_account_id = isset($attributes['cash_account_id'])?$attributes['cash_account_id']:'';
			$this->accountsetting->description = $attributes['description'];
			$this->accountsetting->dr_account_master_id_to = isset($attributes['dr_account_master_id_TO'])?$attributes['dr_account_master_id_TO']:'';
			$this->accountsetting->cr_account_master_id_to = isset($attributes['cr_account_master_id_TO'])?$attributes['cr_account_master_id_TO']:'';
			$this->accountsetting->fill($attributes)->save();
			return true;
		}
		
		//throw new ValidationException('accountsetting validation error!', $this->getErrors());
	}
	
	private function getVoucherType($no) {
		
		switch($no) {
			
			case 1:
			$type = 'PI';
			break;
			
			case 2:
			$type = 'PR';
			break;
			
			case 3:
			$type = 'SI';
			break;
			
			case 4:
			$type = 'SR';
			break;
			
			case 5:
			$type = 'PIN';
			break;
			
			case 6:
			$type = 'SIN';
			break;
			
			case 7:
			$type = 'CN';
			break;
			
			case 8:
			$type = 'DN';
			break;
			
			case 9:
			$type = 'RV';
			break;
			
			case 10:
			$type = 'PV';
			break;
			
			case 13:
			$type = 'GI';
			break;
			
			case 14:
			$type = 'GR';
			break;
			
			case 15:
			$type = 'MV';
			break;
			
			case 16:
			$type = 'JV';
			break;
			
			case 17:
			$type = 'AV';
			break;
			
			case 18:
			$type = 'PDCR';
			break;
			
			case 19:
			$type = 'PDCI';
			break;
			
			case 20:
			$type = 'PC';
			break;
			
			case 21:
			$type = 'STI';
			break;
			
			case 22:
			$type = 'STO';
			break;
			
			case 23:
			$type = 'PS';
			break;
			
			case 24:
			$type = 'SS';
			break;
			
			default;
			$type='';
			break;
		}
		
		return $type;
	}
	
	public function update($id, $attributes) 
	{	//echo '<pre>';print_r($attributes);exit;
		$this->accountsetting = $this->find($id);
		if($this->isValid($attributes, ['voucher_name' => 'required'])) {
			
			$this->accountsetting->voucher_type_id = $attributes['voucher_type_id'];
			$this->accountsetting->voucher_name = $attributes['voucher_name'];
			$this->accountsetting->department_id = $attributes['department_id'];
			$this->accountsetting->prefix = $attributes['prefix'];
			$this->accountsetting->is_prefix = $attributes['is_prefix'];
			$this->accountsetting->voucher_no = $attributes['voucher_no'];
			$this->accountsetting->dr_account_master_id = isset($attributes['dr_account_master_id'])?$attributes['dr_account_master_id']:'';
			$this->accountsetting->cr_account_master_id = isset($attributes['cr_account_master_id'])?$attributes['cr_account_master_id']:'';
			$this->accountsetting->cash_account_id = isset($attributes['drcash_account_master_id'])?$attributes['drcash_account_master_id']:'';
			$this->accountsetting->bank_account_id = isset($attributes['drbank_account_master_id'])?$attributes['drbank_account_master_id']:'';
			$this->accountsetting->pdc_account_id = isset($attributes['drpdc_account_master_id'])?$attributes['drpdc_account_master_id']:'';
			$this->accountsetting->modified_at = now();
			$this->accountsetting->is_cash_voucher = $attributes['is_cash_voucher'];
			$this->accountsetting->default_account_id = isset($attributes['cash_account_id'])?$attributes['cash_account_id']:'';

			$this->accountsetting->dr_account_master_id_to = isset($attributes['dr_account_master_id_TO'])?$attributes['dr_account_master_id_TO']:'';
			$this->accountsetting->cr_account_master_id_to = isset($attributes['cr_account_master_id_TO'])?$attributes['cr_account_master_id_TO']:'';

			$this->accountsetting->status = $attributes['status']; 
			$this->accountsetting->fill($attributes)->save();
			
			if($attributes['status']==1) {
				
				$type = $this->getVoucherType($attributes['voucher_type_id']);
				//CHECK WHEATHER NOT RV PV ETC.....
				if($attributes['voucher_type_id']!=9) {
					
					if($attributes['voucher_type_id']!=10) {
						if(isset($attributes['dr_account_master_id']) && $attributes['dr_account_master_id']!=$attributes['dr_account_master_id_old']) {
							
							DB::table('account_transaction')->where('voucher_type',$type)
												->where('account_master_id', $attributes['dr_account_master_id_old'])
												->update(['account_master_id' => $attributes['dr_account_master_id']
											]);
						}
						
						if(isset($attributes['cr_account_master_id']) && $attributes['cr_account_master_id']!=$attributes['cr_account_master_id_old']) {
							
							DB::table('account_transaction')->where('voucher_type',$type)
												->where('account_master_id', $attributes['cr_account_master_id_old'])
												->update(['account_master_id' => $attributes['cr_account_master_id']
											]);
						}
						
						if(isset($attributes['cash_account_id']) && $attributes['cash_account_id']!=$attributes['cash_account_id_old']) {
							
							DB::table('account_transaction')->where('voucher_type',$type)
												->where('account_master_id', $attributes['cash_account_id_old'])
												->update(['account_master_id' => $attributes['cash_account_id']
											]);
						}
					}
					
				} else {
					//RV PV JV PC SECTION.....
					if($attributes['drcash_account_master_id']!=$attributes['drcash_account_master_id_old']) {
						
						DB::table('account_transaction')->where('voucher_type',$type)
											->where('account_master_id', $attributes['drcash_account_master_id_old'])
											->update(['account_master_id' => $attributes['drcash_account_master_id']
										]);
					}
					
					if($attributes['drbank_account_master_id']!=$attributes['drbank_account_master_id_old']) {
						
						DB::table('account_transaction')->where('voucher_type',$type)
											->where('account_master_id', $attributes['drbank_account_master_id_old'])
											->update(['account_master_id' => $attributes['drbank_account_master_id']
										]);
					}
					
					if($attributes['drpdc_account_master_id']!=$attributes['drpdc_account_master_id_old']) {
						
						DB::table('account_transaction')->where('voucher_type',$type)
											->where('account_master_id', $attributes['drpdc_account_master_id_old'])
											->update(['account_master_id' => $attributes['drpdc_account_master_id']
										]);
					}
					
				}
			
			}
			return true;
		}
		//throw new ValidationException('AccountSetting validation error!', $this->getErrors());
	}
	
	
	public function delete($id)
	{
		$this->accountsetting = $this->accountsetting->find($id);
		$this->accountsetting->delete();
	}
	
	public function getAccountSettingsList()
	{
		
		//$query = $this->accountsetting->where('account_setting.status',1);
		
		return $this->accountsetting->leftjoin('department AS de', function($join) {
							$join->on('de.id','=','account_setting.department_id');
						} )
					->leftJoin('account_master AS am', function($join) {
							$join->on('am.id','=','account_setting.dr_account_master_id');
						} )
					->leftJoin('account_master AS am2', function($join) {
							$join->on('am2.id', '=', 'account_setting.cr_account_master_id');
					})
					->join('voucher_type AS vt', function($join) {
							$join->on('vt.id', '=', 'account_setting.voucher_type_id');
					})
					->where('account_setting.status',1)
					//->where('account_setting.deleted_at', '0000-00-00 00:00:00')
					->select('account_setting.*','de.name AS department','am.master_name AS dr_master_name','am2.master_name AS cr_master_name','vt.name AS type_name')
					->get(); 
	}
	
	public function accountMasterView($id)
	{
		
		$query = $this->accountsetting->where('account_master.id',$id);
		
		return $query->join('account_category AS ac', function($join) {
							$join->on('ac.id','=','account_master.account_category_id');
						} )
					->join('account_group AS ag', function($join) {
							$join->on('ag.id','=','account_master.account_group_id');
						} )
					->join('account_category AS at', function($join) {
							$join->on('at.id','=','ac.parent_id');
						})
					->leftjoin('currency AS cr', function($join) {
							$join->on('cr.id', '=', 'account_master.currency_id');
						})
					->leftjoin('department AS dp', function($join) {
							$join->on('dp.id', '=', 'account_master.department_id');
						})
					->leftjoin('salesman AS sm', function($join) {
							$join->on('sm.id', '=', 'account_master.salesman_id');
						})
					->leftjoin('terms AS tm', function($join) {
							$join->on('tm.id', '=', 'account_master.terms_id');
						})
					->leftjoin('country AS cn', function($join) {
							$join->on('cn.id', '=', 'account_master.country_id');
						})
					->leftjoin('area AS ar', function($join) {
							$join->on('ar.id', '=', 'account_master.area_id');
						})
					->select('account_master.*','ag.name AS group_name','ac.name AS category_name','at.name AS type_name','cr.name AS currency',
							 'dp.name AS department','sm.name AS salesman','tm.description AS terms','cn.name AS country','ar.name AS area')
					->first(); 
	}
	
	public function getAccountSettingsById($id,$isdept=null,$deptid=null)  //MY23
	{
		$query = $this->accountsetting->where('account_setting.status',1)->where('account_setting.voucher_type_id',$id)
						->join('voucher_type AS vt', function($join) {
								$join->on('vt.id', '=', 'account_setting.voucher_type_id');
							});
					
			if($isdept && $deptid !=0) {
				return $query->where('account_setting.department_id', $deptid)
							->select('account_setting.id','vt.name','account_setting.voucher_name','vt.id AS vid')
							->get(); 
			} else {
				return $query->select('account_setting.id','vt.name','account_setting.voucher_name','vt.id AS vid','account_setting.prefix','account_setting.is_prefix')
							->get(); 
			}
	}

	public function getAccountSettingsPR($id,$deptid=null)
	{
		$query = $this->accountsetting->where('account_setting.status',1)->where('account_setting.voucher_type_id',$id);
		
		 $query->join('voucher_type AS vt', function($join) {
							$join->on('vt.id', '=', 'account_setting.voucher_type_id');
					})
					->leftJoin('account_master AS am', function($join) {
							$join->on('am.id', '=', 'account_setting.cr_account_master_id');
					});
					
				if($deptid) {
					return $query->where('account_setting.department_id', $deptid)
							->select('account_setting.id','account_setting.voucher_no','vt.name','account_setting.voucher_name','vt.id AS vid','am.account_id AS acode','am.master_name AS account','am.id AS acid')
							->get(); 
				} else {
					return $query->select('account_setting.id','account_setting.voucher_no','vt.name','account_setting.voucher_name','vt.id AS vid','am.account_id AS acode','am.master_name AS account','am.id AS acid')
								->orderBy('account_setting.department_id','ASC')->get(); 
				}
	}

	public function getAccountSettingsSR($id,$deptid=null)
	{
		$query = $this->accountsetting->where('account_setting.status',1)->where('account_setting.voucher_type_id',$id);
		
		$query->join('voucher_type AS vt', function($join) {
							$join->on('vt.id', '=', 'account_setting.voucher_type_id');
					})
					->leftJoin('account_master AS am', function($join) {
							$join->on('am.id', '=', 'account_setting.dr_account_master_id');
					});
					
				if($deptid) {	
					return $query->where('account_setting.department_id', $deptid)
					->select('account_setting.id','account_setting.voucher_no','vt.name','account_setting.voucher_name','vt.id AS vid','am.account_id AS acode','am.master_name AS account','am.id AS acid')
					->get(); 
				} else {
					return $query->select('account_setting.department_id','account_setting.id','account_setting.voucher_no','vt.name','account_setting.voucher_name','vt.id AS vid','am.account_id AS acode','am.master_name AS account','am.id AS acid')
					->orderBy('account_setting.department_id','ASC')->get();
				}
	}
	
	public function getAccountSettings($id)
	{
		return $query = $this->accountsetting->where('status',1)
					->where('voucher_type_id',$id)
					->select('id','voucher_name AS name','voucher_type_id')
					->get(); 
	}
	
	public function getVoucherByID($id) {
		$query = $this->accountsetting->where('account_setting.status',1)->where('account_setting.id',$id);
		
		return $query->leftJoin('account_master AS am', function($join) {
							$join->on('am.id', '=', 'account_setting.dr_account_master_id');
					})
					->leftJoin('account_master AS am2', function($join) {
							$join->on('am2.id', '=', 'account_setting.default_account_id'); //cr_account_master_id
					})
					->select('account_setting.prefix','account_setting.is_prefix','account_setting.voucher_no','account_setting.default_account_id',
							 'account_setting.dr_account_master_id','am.master_name','am.id','am.account_id',
							 'am2.id AS cid','am2.account_id AS caccount_id','am2.master_name AS cmaster_name','account_setting.is_cash_voucher','am2.master_name AS default_account')
					->first(); 
	}
	
	public function getCrVoucherByID($id) {
		$query = $this->accountsetting->where('account_setting.status',1)->where('account_setting.id',$id);
		
		return $query->leftJoin('account_master AS am', function($join) {
							$join->on('am.id', '=', 'account_setting.cr_account_master_id');
					})
					->leftJoin('account_master AS am2', function($join) {
							$join->on('am2.id', '=', 'account_setting.default_account_id');
					})
					->select('account_setting.prefix','account_setting.is_prefix','account_setting.voucher_no','account_setting.default_account_id',
							 'account_setting.cr_account_master_id','am.master_name','am.id','am.account_id','account_setting.is_cash_voucher',
							 'am2.master_name AS default_account')
					->first(); 
	}
	
	
	public function getCrVoucherByDept($id) {
		$query = $this->accountsetting->where('account_setting.status',1)->where('account_setting.department_id',$id);
		
		return $query->leftJoin('account_master AS am', function($join) {
							$join->on('am.id', '=', 'account_setting.cr_account_master_id');
					})
					->leftJoin('account_master AS am2', function($join) {
							$join->on('am2.id', '=', 'account_setting.default_account_id');
					})
					->select('account_setting.id AS voucher_id','account_setting.prefix','account_setting.is_prefix','account_setting.voucher_no','account_setting.default_account_id','account_setting.voucher_name',
							 'account_setting.cr_account_master_id','am.master_name','am.id','am.account_id','account_setting.is_cash_voucher','am2.master_name AS default_account')
					->get(); //first()
	}
	
	
	public function getVoucherByDept($vid, $id) {
		
		$query = $this->accountsetting->where('account_setting.status',1)
						->where('account_setting.voucher_type_id',$vid)
						->where('account_setting.department_id',$id);
						
		return $query->join('department AS D', function($join) {
							$join->on('D.id', '=', 'account_setting.department_id');
					})
					->leftJoin('account_master AS am', function($join) {
							$join->on('am.id', '=', 'account_setting.cr_account_master_id');
					})
					->leftJoin('account_master AS am2', function($join) {
							$join->on('am2.id', '=', 'account_setting.default_account_id');
					})
					->select('account_setting.id AS voucher_id','account_setting.prefix','account_setting.is_prefix','account_setting.voucher_no','account_setting.default_account_id','account_setting.voucher_name',
							 'account_setting.cr_account_master_id','am.master_name','am.id','am.account_id','account_setting.is_cash_voucher','am2.master_name AS default_account')
					->get();
	}
	
	public function getVoucherByDeptPI($vid, $id) {
		
		$query = $this->accountsetting->where('account_setting.status',1)
						->where('account_setting.voucher_type_id',$vid)
						->where('account_setting.department_id',$id);
		
		return $query->join('department AS D', function($join) {
							$join->on('D.id', '=', 'account_setting.department_id');
					})
					->leftJoin('account_master AS am', function($join) {
							$join->on('am.id', '=', 'account_setting.dr_account_master_id');
					})
					->leftJoin('account_master AS am2', function($join) {
							$join->on('am2.id', '=', 'account_setting.default_account_id');
					})
					->select('account_setting.id AS voucher_id','account_setting.prefix','account_setting.is_prefix','account_setting.voucher_no','account_setting.default_account_id','account_setting.voucher_name',
							 'account_setting.cr_account_master_id','am.master_name','am.id','am.account_id','account_setting.is_cash_voucher','am2.master_name AS default_account')
					->get();
	}
	
	public function getVoucherByDeptGIR($vid, $id) {
		
		$query = $this->accountsetting->where('account_setting.status',1)
						->where('account_setting.voucher_type_id',$vid)
						->where('account_setting.department_id',$id);
					
		return $query->join('department AS D', function($join) {
						$join->on('D.id', '=', 'account_setting.department_id');
					})
					->leftJoin('account_master AS am', function($join) {
							$join->on('am.id', '=', 'account_setting.cr_account_master_id');
					})
					->leftJoin('account_master AS am2', function($join) {
							$join->on('am2.id', '=', 'account_setting.dr_account_master_id');
					})
					->select('account_setting.id AS voucher_id','account_setting.prefix','account_setting.is_prefix','account_setting.voucher_no','am.master_name','account_setting.voucher_name',
							'am.id','account_setting.cr_account_master_id','am.account_id','account_setting.dr_account_master_id','am2.master_name AS dr_master_name')
					->get();
	}
	
	
	public function getDrVoucherByID($id) {
		$query = $this->accountsetting->where('account_setting.status',1)->where('account_setting.id',$id);
		
		return $query->leftJoin('account_master AS am', function($join) {
							$join->on('am.id', '=', 'account_setting.dr_account_master_id');
					})
					->leftJoin('account_master AS c', function($join) {
							$join->on('c.id', '=', 'account_setting.cash_account_id');
					})
					->leftJoin('account_master AS b', function($join) {
							$join->on('b.id', '=', 'account_setting.bank_account_id');
					})
					->leftJoin('account_master AS p', function($join) {
							$join->on('p.id', '=', 'account_setting.pdc_account_id');
					})
					->select('account_setting.prefix','account_setting.is_prefix','account_setting.voucher_no',
							 'account_setting.dr_account_master_id','am.master_name','am.id','am.account_id',
							 'account_setting.cash_account_id','account_setting.bank_account_id','account_setting.pdc_account_id',
							 'c.master_name AS cashaccount','b.master_name AS bankaccount','p.master_name AS pdcaccount')
					->first(); 
	}
	
	public function getDrVoucherByID2($id,$dpid=null) {
		$query = $this->accountsetting->where('account_setting.status',1)->where('account_setting.voucher_type_id',$id);
		
		if($dpid)
			$query->where('account_setting.department_id',$dpid);
		
		return $query->leftJoin('account_master AS am', function($join) {
							$join->on('am.id', '=', 'account_setting.dr_account_master_id');
					})
					->leftJoin('account_master AS c', function($join) {
							$join->on('c.id', '=', 'account_setting.cash_account_id');
					})
					->leftJoin('account_master AS b', function($join) {
							$join->on('b.id', '=', 'account_setting.bank_account_id');
					})
					->leftJoin('account_master AS p', function($join) {
							$join->on('p.id', '=', 'account_setting.pdc_account_id');
					})
					->select('account_setting.prefix','account_setting.is_prefix','account_setting.voucher_no',
							 'account_setting.dr_account_master_id','am.master_name','am.id','am.account_id','account_setting.id AS asid',
							 'account_setting.cash_account_id','account_setting.bank_account_id','account_setting.pdc_account_id',
							 'c.master_name AS cashaccount','b.master_name AS bankaccount','p.master_name AS pdcaccount')
					->first(); 
	}
	
	public function getVoucherByDeptSTI($vid, $id) {
		
		$query = $this->accountsetting->where('account_setting.status',1)
						->where('account_setting.voucher_type_id',$vid)
						->where('account_setting.department_id',$id);
					
		return $query->join('department AS D', function($join) {
						$join->on('D.id', '=', 'account_setting.department_id');
					})
					->leftJoin('account_master AS am', function($join) {
							$join->on('am.id', '=', 'account_setting.cr_account_master_id');
					})
					->leftJoin('account_master AS am2', function($join) {
							$join->on('am2.id', '=', 'account_setting.dr_account_master_id');
					})
					->select('account_setting.id AS voucher_id','am.master_name','account_setting.voucher_name','account_setting.voucher_no','account_setting.dr_account_master_id','am.account_id',
							'account_setting.is_cash_voucher','account_setting.cr_account_master_id','am2.master_name AS dr_account')
					->get();
	}
	
	public function getVoucherAcById($id) {
		
	  return $query = $this->accountsetting->where('account_setting.status',1)->where('account_setting.id',$id)
											->leftJoin('account_master AS amd', function($join) {
													$join->on('amd.id', '=', 'account_setting.dr_account_master_id');
											})
											->leftJoin('account_master AS amc', function($join) {
													$join->on('amc.id', '=', 'account_setting.cr_account_master_id');
											})
											->select('account_setting.prefix','account_setting.is_prefix','account_setting.voucher_no',
													 'account_setting.dr_account_master_id','amc.master_name AS accountcr','amc.id AS idcr',
													 'amc.account_id AS codecr','amd.master_name AS accountdr','amd.id AS iddr','amd.account_id AS codedr')
											->first(); 
	}
	
	public function check_name($voucher_name, $id = null) {
		
		if($id)
			return $this->accountsetting->where('voucher_name',$voucher_name)->where('id', '!=', $id)->count();
		else
			return $this->accountsetting->where('voucher_name',$voucher_name)->count();
	}
	
	public function getCostAccounts()
	{
		$result = DB::table('voucher_account')->whereIn('account_field', ['stock','cost_of_sale'])->select('account_id','account_field')->get();
		$accounts = array();
		if($result){
			foreach($result as $res) {
				$accounts[$res->account_field] = $res->account_id;
			}
		}
		
		return $accounts;
	}
	
	
	public function getCostAccountsDept($deptid)
	{
		return DB::table('department_accounts')->where('department_id', $deptid)->select('stock_acid','cost_acid')->first();
	}
	
	
	public function getAccountPeriod()
	{
		return $result = DB::table('parameter1')->where('id',1)->first();
	}
	
	public function check_settings($id,$type)
	{ //echo $type;exit;
		switch($type) {
			case 1:
				$count = DB::table('purchase_invoice')->where('voucher_id',$id)->whereNull('deleted_at')->count();
				if($count > 0)
					return false;
				else
					return true;
			break;
			
			case 2:
				$count = DB::table('purchase_return')->where('voucher_id',$id)->whereNull('deleted_at')->count();
				if($count > 0)
					return false;
				else
					return true;
			break;
			
			case 3:
				$count = DB::table('sales_invoice')->where('voucher_id',$id)->whereNull('deleted_at')->count();
				if($count > 0)
					return false;
				else
					return true;
			break;
			
			case 4:
				$count = DB::table('sales_return')->where('voucher_id',$id)->whereNull('deleted_at')->count();
				if($count > 0)
					return false;
				else
					return true;
			break;
			
			case 5:
				$count = DB::table('journal')->where('voucher_type','PIN')->whereNull('deleted_at')->count();
				if($count > 0)
					return false;
				else
					return true;
			break;
			
			case 6:
				$count = DB::table('journal')->where('voucher_type','SIN')->whereNull('deleted_at')->count();
				if($count > 0)
					return false;
				else
					return true;
			break;
				
			case 7:
				$count = DB::table('credit_note')->where('voucher_id',$id)->whereNull('deleted_at')->count();
				if($count > 0)
					return false;
				else
					return true;
			break;
				
			case 8:
				$count = DB::table('debit_note')->where('voucher_id',$id)->whereNull('deleted_at')->count();
				if($count > 0)
					return false;
				else
					return true;
			break;
			
			case 9:
				$count = DB::table('receipt_voucher')->where('voucher_id',$id)->whereNull('deleted_at')->count();
				if($count > 0)
					return false;
				else
					return true;
			break;
			
			case 10:
				$count = DB::table('payment_voucher')->where('voucher_id',$id)->whereNull('deleted_at')->count();
				if($count > 0)
					return false;
				else
					return true;
			break;
				
			case 13:
				$count = DB::table('goods_issued')->where('voucher_id',$id)->whereNull('deleted_at')->count();
				if($count > 0)
					return false;
				else
					return true;
			break;
				
			case 14:
				$count = DB::table('goods_return')->where('voucher_id',$id)->whereNull('deleted_at')->count();
				if($count > 0)
					return false;
				else
					return true;
			break;
				
			case 16:
				$count = DB::table('journal')->where('voucher_type','JV')->whereNull('deleted_at')->count();
				if($count > 0)
					return false;
				else
					return true;
			break;
				
			case 20:
				$count = DB::table('petty_cash')->where('voucher_type','PC')->whereNull('deleted_at')->count();
				if($count > 0)
					return false;
				else
					return true;
			break;
			
			case 21:
				$count = DB::table('stock_transferin')->where('voucher_no',$id)->whereNull('deleted_at')->count();
				if($count > 0)
					return false;
				else
					return true;
			break;
			
			case 22:
				$count = DB::table('stock_transferout')->where('voucher_no',$id)->whereNull('deleted_at')->count();
				if($count > 0)
					return false;
				else
					return true;
			break;
			
			case 15:
				$count = DB::table('manufacture')->whereNull('deleted_at')->count();
				if($count > 0)
					return false;
				else
					return true;
			break;
			
			case 23:
				$count = DB::table('purchase_split')->where('voucher_no',$id)->whereNull('deleted_at')->count();
				if($count > 0)
					return false;
				else
					return true;
			break;
			
			case 24:
				$count = DB::table('sales_split')->where('voucher_no',$id)->whereNull('deleted_at')->count();
				if($count > 0)
					return false;
				else
					return true;
			break;
			
			default:
			    return true;
		}
	}
	public function getAccountSettingsDefault2($id,$isdept=null,$deptid=null)
	{
		$query = $this->accountsetting->where('account_setting.status',1)->where('account_setting.voucher_type_id',$id);
		
		if($id==1) {
			
				$qry = $query->Join('account_master AS AM', function($join) {
							$join->on('AM.id', '=', 'account_setting.dr_account_master_id');
					})
					->leftJoin('account_master AS AM2', function($join) {
							$join->on('AM2.id', '=', 'account_setting.default_account_id');
					});
					
					if($isdept && $deptid !=0) {
						return $qry->where('account_setting.department_id', $deptid)
									->select('account_setting.id','AM.master_name','account_setting.voucher_name','voucher_no','account_setting.dr_account_master_id',
											'AM.account_id','account_setting.is_cash_voucher','account_setting.default_account_id','AM2.master_name AS default_account')
									->orderBy('account_setting.department_id','ASC')->get();
						
					} else {
						return $qry->select('account_setting.id','AM.master_name','account_setting.voucher_name','voucher_no','account_setting.dr_account_master_id','AM.account_id',
								'account_setting.is_cash_voucher','account_setting.default_account_id','AM2.master_name AS default_account')
								->get();
					}
						
		} else if($id==3) {
			
			$qry = $query->join('account_master AS AM', function($join) {
								$join->on('AM.id', '=', 'account_setting.cr_account_master_id');
						})
						->leftJoin('account_master AS AM2', function($join) {
								$join->on('AM2.id', '=', 'account_setting.default_account_id');
						});
						
				if($isdept && $deptid !=0) {
					return $qry->where('account_setting.department_id', $deptid)
									->select('account_setting.id','AM.master_name','account_setting.voucher_name','voucher_no','account_setting.cr_account_master_id',
									'AM.account_id','account_setting.is_cash_voucher','account_setting.default_account_id','AM2.master_name AS default_account')
									->orderBy('account_setting.department_id','ASC')->get();
				/* } elseif($isdept && $deptid == 0) {
					return $qry->where('account_setting.department_id','!=',0)
								->select('account_setting.id','AM.master_name','account_setting.voucher_name','voucher_no','account_setting.cr_account_master_id',
								 'AM.account_id','account_setting.is_cash_voucher','account_setting.default_account_id','AM2.master_name AS default_account')
								->orderBy('account_setting.department_id','ASC')->get(); */
				} else {
					return $qry->select('account_setting.id','AM.master_name','account_setting.voucher_name','voucher_no','account_setting.cr_account_master_id',
								 'AM.account_id','account_setting.is_cash_voucher','account_setting.default_account_id','AM2.master_name AS default_account')
								->orderBy('account_setting.is_cash_voucher','ASC')->get();
				}		
						
						
		} else if($id==21 || $id==22) {
			
			 $query->join('account_master AS AM', function($join) {
								$join->on('AM.id', '=', 'account_setting.cr_account_master_id');
						})
						->leftJoin('account_master AS AM2', function($join) {
								$join->on('AM2.id', '=', 'account_setting.dr_account_master_id');
						});
						
				if($isdept && $deptid !=0) {
					return $query->where('account_setting.department_id', $deptid)
									->select('account_setting.id','AM.master_name','account_setting.voucher_name','voucher_no','account_setting.cr_account_master_id',
											'AM.account_id','account_setting.dr_account_master_id','AM2.master_name AS dr_master_name')
									->orderBy('account_setting.is_cash_voucher','DESC')->get();
				} else {
					return $query->select('account_setting.id','AM.master_name','account_setting.voucher_name','voucher_no','account_setting.cr_account_master_id',
								 'AM.account_id','account_setting.dr_account_master_id','AM2.master_name AS dr_master_name')
								->orderBy('account_setting.is_cash_voucher','DESC')
								->get();
				}
				
		} else if($id==7) {
			
			$query->join('account_master AS AM', function($join) {
							$join->on('AM.id', '=', 'account_setting.dr_account_master_id');
						});
						
			if($isdept && $deptid !=0) {
				return $query->where('account_setting.department_id', $deptid)
							 ->select('account_setting.id','AM.master_name','account_setting.voucher_name','voucher_no','account_setting.dr_account_master_id','AM.account_id')
							 ->get();
			} else {
				return $query->select('account_setting.id','AM.master_name','account_setting.voucher_name','voucher_no','account_setting.dr_account_master_id','AM.account_id')
							->get();
			}
						
		} else if($id==8) {
			
			$query->join('account_master AS AM', function($join) {
							$join->on('AM.id', '=', 'account_setting.cr_account_master_id');
						});
						
			if($isdept && $deptid !=0) {
				return $query->where('account_setting.department_id', $deptid)
							 ->select('account_setting.id','AM.master_name','account_setting.voucher_name','voucher_no','account_setting.cr_account_master_id','AM.account_id')
							 ->get();
			} else {
				return $query->select('account_setting.id','AM.master_name','account_setting.voucher_name','voucher_no','account_setting.cr_account_master_id','AM.account_id')
							->get();
			}
			
						
		} else if($id==13 || $id==14) {
			
			 $query->join('account_master AS AM', function($join) {
							$join->on('AM.id', '=', 'account_setting.cr_account_master_id');
						})
						->leftJoin('account_master AS AM2', function($join) {
								$join->on('AM2.id', '=', 'account_setting.dr_account_master_id');
						});
						
				if($isdept && $deptid !=0) {
					return $query->where('account_setting.department_id', $deptid)
									->select('account_setting.id','AM.master_name','account_setting.voucher_name','voucher_no','account_setting.cr_account_master_id','AM.account_id','account_setting.dr_account_master_id','AM2.master_name AS dr_master_name')
									->orderBy('account_setting.department_id','ASC')->get();
					
				} else {
					return $query->select('account_setting.id','AM.master_name','account_setting.voucher_name','voucher_no',
								'account_setting.cr_account_master_id','AM.account_id','account_setting.dr_account_master_id','AM2.master_name AS dr_master_name')
							->get();
				}
				
							

		} else if($id==5) {
			
			$query->leftjoin('account_master AS AM', function($join) {
								$join->on('AM.id', '=', 'account_setting.dr_account_master_id');
						})
						->leftJoin('account_master AS AM2', function($join) {
								$join->on('AM2.id', '=', 'account_setting.default_account_id');
						});
						
				if($isdept && $deptid !=0) {
					return $query->where('account_setting.department_id', $deptid)
									->select('account_setting.id','AM.master_name','account_setting.voucher_name','voucher_no','account_setting.dr_account_master_id','AM.account_id',
											 'account_setting.is_cash_voucher','account_setting.default_account_id','AM2.master_name AS default_account','account_setting.voucher_type_id')
									->orderBy('account_setting.department_id','ASC')->get();
					
				} else {
					return $query->select('account_setting.id','AM.master_name','account_setting.voucher_name','voucher_no','account_setting.dr_account_master_id','AM.account_id',
											'account_setting.is_cash_voucher','account_setting.default_account_id','AM2.master_name AS default_account','account_setting.voucher_type_id')
									->get();
						
				}
		
		} else if($id==6) {
			
			 $query->leftjoin('account_master AS AM', function($join) {
								$join->on('AM.id', '=', 'account_setting.cr_account_master_id');
						});
				if($isdept && $deptid !=0) {
					return $query->where('account_setting.department_id', $deptid)
								->select('account_setting.id','account_setting.voucher_name','voucher_no','account_setting.voucher_type_id')
								->get();
				} else {
					return $query->select('account_setting.id','account_setting.voucher_name','voucher_no','account_setting.voucher_type_id')
										->get();
				}
				
		} else if($id==15) {
			
			 $query->join('account_master AS AM', function($join) {
							$join->on('AM.id', '=', 'account_setting.cr_account_master_id');
						})
						->leftJoin('account_master AS AM2', function($join) {
								$join->on('AM2.id', '=', 'account_setting.dr_account_master_id');
						})
						->leftjoin('account_master AS AM3', function($join) {
							$join->on('AM3.id', '=', 'account_setting.cr_account_master_id_to');
						})
						->leftJoin('account_master AS AM4', function($join) {
							$join->on('AM4.id', '=', 'account_setting.dr_account_master_id_to');
						});
						
				if($isdept && $deptid !=0) {
					return $query->where('account_setting.department_id', $deptid)
									->select('account_setting.id','AM.master_name','account_setting.voucher_name','voucher_no','account_setting.cr_account_master_id','AM.account_id','account_setting.dr_account_master_id','AM2.master_name AS dr_master_name')
									->orderBy('account_setting.department_id','ASC')->get();
					
				} else {
					return $query->select('account_setting.id','AM.master_name','account_setting.voucher_name','voucher_no',
								'account_setting.cr_account_master_id','AM.account_id','account_setting.dr_account_master_id','AM2.master_name AS dr_master_name',
								'account_setting.cr_account_master_id_to','account_setting.dr_account_master_id_to','AM3.master_name AS cr_master_name_to',
								'AM4.master_name AS dr_master_name_to')
							->get();
				}
			
		} 
	}
	
	public function getAccountSettingsDefault2old($id,$isdept=null,$deptid=null)
	{
		$query = $this->accountsetting->where('account_setting.status',1)->where('account_setting.voucher_type_id',$id);
		
		if($id==1) {
			
				$qry = $query->Join('account_master AS AM', function($join) {
							$join->on('AM.id', '=', 'account_setting.dr_account_master_id');
					})
					->leftJoin('account_master AS AM2', function($join) {
							$join->on('AM2.id', '=', 'account_setting.default_account_id');
					});
					
					if($isdept && $deptid !=0) {
						return $qry->where('account_setting.department_id', $deptid)
									->select('account_setting.id','AM.master_name','account_setting.voucher_name','voucher_no','account_setting.dr_account_master_id',
											'AM.account_id','account_setting.is_cash_voucher','account_setting.default_account_id','AM2.master_name AS default_account')
									->orderBy('account_setting.department_id','ASC')->get();
						
					} else {
						return $qry->select('account_setting.id','AM.master_name','account_setting.voucher_name','voucher_no','account_setting.dr_account_master_id','AM.account_id',
								'account_setting.is_cash_voucher','account_setting.default_account_id','AM2.master_name AS default_account')
								->get();
					}
						
		} else if($id==3) {
			
			$qry = $query->join('account_master AS AM', function($join) {
								$join->on('AM.id', '=', 'account_setting.cr_account_master_id');
						})
						->leftJoin('account_master AS AM2', function($join) {
								$join->on('AM2.id', '=', 'account_setting.default_account_id');
						});
						
				if($isdept && $deptid !=0) {
					return $qry->where('account_setting.department_id', $deptid)
									->select('account_setting.id','AM.master_name','account_setting.voucher_name','voucher_no','account_setting.cr_account_master_id',
									'AM.account_id','account_setting.is_cash_voucher','account_setting.default_account_id','AM2.master_name AS default_account')
									->orderBy('account_setting.department_id','ASC')->get();
				/* } elseif($isdept && $deptid == 0) {
					return $qry->where('account_setting.department_id','!=',0)
								->select('account_setting.id','AM.master_name','account_setting.voucher_name','voucher_no','account_setting.cr_account_master_id',
								 'AM.account_id','account_setting.is_cash_voucher','account_setting.default_account_id','AM2.master_name AS default_account')
								->orderBy('account_setting.department_id','ASC')->get(); */
				} else {
					return $qry->select('account_setting.id','AM.master_name','account_setting.voucher_name','voucher_no','account_setting.cr_account_master_id',
								 'AM.account_id','account_setting.is_cash_voucher','account_setting.default_account_id','AM2.master_name AS default_account')
								->orderBy('account_setting.is_cash_voucher','DESC')->get();
				}		
						
						
		} else if($id==21 || $id==22) {
			
			 $query->join('account_master AS AM', function($join) {
								$join->on('AM.id', '=', 'account_setting.cr_account_master_id');
						})
						->leftJoin('account_master AS AM2', function($join) {
								$join->on('AM2.id', '=', 'account_setting.dr_account_master_id');
						});
						
				if($isdept && $deptid !=0) {
					return $query->where('account_setting.department_id', $deptid)
									->select('account_setting.id','AM.master_name','account_setting.voucher_name','voucher_no','account_setting.cr_account_master_id',
											'AM.account_id','account_setting.dr_account_master_id','AM2.master_name AS dr_master_name')
									->orderBy('account_setting.is_cash_voucher','DESC')->get();
				} else {
					return $query->select('account_setting.id','AM.master_name','account_setting.voucher_name','voucher_no','account_setting.cr_account_master_id',
								 'AM.account_id','account_setting.dr_account_master_id','AM2.master_name AS dr_master_name')
								->orderBy('account_setting.is_cash_voucher','DESC')
								->get();
				}
				
		} else if($id==7) {
			
			$query->join('account_master AS AM', function($join) {
							$join->on('AM.id', '=', 'account_setting.dr_account_master_id');
						});
						
			if($isdept && $deptid !=0) {
				return $query->where('account_setting.department_id', $deptid)
							 ->select('account_setting.id','AM.master_name','account_setting.voucher_name','voucher_no','account_setting.dr_account_master_id','AM.account_id')
							 ->get();
			} else {
				return $query->select('account_setting.id','AM.master_name','account_setting.voucher_name','voucher_no','account_setting.dr_account_master_id','AM.account_id')
							->get();
			}
						
		} else if($id==8) {
			
			$query->join('account_master AS AM', function($join) {
							$join->on('AM.id', '=', 'account_setting.cr_account_master_id');
						});
						
			if($isdept && $deptid !=0) {
				return $query->where('account_setting.department_id', $deptid)
							 ->select('account_setting.id','AM.master_name','account_setting.voucher_name','voucher_no','account_setting.cr_account_master_id','AM.account_id')
							 ->get();
			} else {
				return $query->select('account_setting.id','AM.master_name','account_setting.voucher_name','voucher_no','account_setting.cr_account_master_id','AM.account_id')
							->get();
			}
			
						
		} else if($id==13 || $id==14) {
			
			 $query->join('account_master AS AM', function($join) {
							$join->on('AM.id', '=', 'account_setting.cr_account_master_id');
						})
						->leftJoin('account_master AS AM2', function($join) {
								$join->on('AM2.id', '=', 'account_setting.dr_account_master_id');
						});
						
				if($isdept && $deptid !=0) {
					return $query->where('account_setting.department_id', $deptid)
									->select('account_setting.id','AM.master_name','account_setting.voucher_name','voucher_no','account_setting.dr_account_master_id',
											'AM.account_id','account_setting.is_cash_voucher','account_setting.default_account_id','AM2.master_name AS default_account')
									->orderBy('account_setting.department_id','ASC')->get();
					
				} else {
					return $query->select('account_setting.id','AM.master_name','account_setting.voucher_name','voucher_no','account_setting.dr_account_master_id','AM.account_id',
								'account_setting.is_cash_voucher','account_setting.default_account_id','AM2.master_name AS default_account')
								->get();
				}
				
							

		} else if($id==5) {
			
			$query->join('account_master AS AM', function($join) {
								$join->on('AM.id', '=', 'account_setting.dr_account_master_id');
						})
						->leftJoin('account_master AS AM2', function($join) {
								$join->on('AM2.id', '=', 'account_setting.default_account_id');
						});
						
				if($isdept && $deptid !=0) {
					return $query->where('account_setting.department_id', $deptid)
									->select('account_setting.id','AM.master_name','account_setting.voucher_name','voucher_no','account_setting.dr_account_master_id','AM.account_id',
											 'account_setting.is_cash_voucher','account_setting.default_account_id','AM2.master_name AS default_account','account_setting.voucher_type_id')
									->orderBy('account_setting.department_id','ASC')->get();
					
				} else {
					return $query->select('account_setting.id','AM.master_name','account_setting.voucher_name','voucher_no','account_setting.dr_account_master_id','AM.account_id',
											'account_setting.is_cash_voucher','account_setting.default_account_id','AM2.master_name AS default_account','account_setting.voucher_type_id')
									->get();
						
				}
		
		} else if($id==6) {
			
			 $query->join('account_master AS AM', function($join) {
								$join->on('AM.id', '=', 'account_setting.cr_account_master_id');
						});
				if($isdept && $deptid !=0) {
					return $query->where('account_setting.department_id', $deptid)
								->select('account_setting.id','account_setting.voucher_name','voucher_no','account_setting.voucher_type_id')
								->get();
				} else {
					return $query->select('account_setting.id','account_setting.voucher_name','voucher_no','account_setting.voucher_type_id')
										->get();
				}
				
		} else if($id==15) {
			
			 $query->join('account_master AS AM', function($join) {
							$join->on('AM.id', '=', 'account_setting.cr_account_master_id');
						})
						->leftJoin('account_master AS AM2', function($join) {
								$join->on('AM2.id', '=', 'account_setting.dr_account_master_id');
						});
						
				if($isdept && $deptid !=0) {
					return $query->where('account_setting.department_id', $deptid)
									->select('account_setting.id','AM.master_name','account_setting.voucher_name','voucher_no','account_setting.cr_account_master_id','AM.account_id','account_setting.dr_account_master_id','AM2.master_name AS dr_master_name')
									->orderBy('account_setting.department_id','ASC')->get();
					
				} else {
					return $query->select('account_setting.id','AM.master_name','account_setting.voucher_name','voucher_no',
								'account_setting.cr_account_master_id','AM.account_id','account_setting.dr_account_master_id','AM2.master_name AS dr_master_name')
							->get();
				}
			
		} 
	}
	
	
	public function getExpenseAccount()
	{
		return DB::table('vat_master')
					->join('account_master', 'account_master.id', '=', 'vat_master.expense_account')
					->where('vat_master.status',1)
					->where('vat_master.deleted_at','0000-00-00 00:00:00')
					->select('vat_master.expense_account','account_master.master_name','vat_master.id')
					->first();
	}
	public function getIncomeAccount()
	{
		return DB::table('vat_master')
					->join('account_master', 'account_master.id', '=', 'vat_master.payment_account')
					->where('vat_master.status',1)
					->where('vat_master.deleted_at','0000-00-00 00:00:00')
					->select('vat_master.payment_account','account_master.master_name','vat_master.id')
					->first();
	}
	
	
	public function checkVoucher($id)
	{
		return $this->accountsetting->where('id',$id)->select('is_cash_voucher')->first();
	}
	
	public function getVoucherByDeptCN($vid, $id) {
		
		$query = $this->accountsetting->where('account_setting.status',1)
						->where('account_setting.voucher_type_id',$vid)
						->where('account_setting.department_id',$id);
					
		return $query->join('department AS D', function($join) {
						$join->on('D.id', '=', 'account_setting.department_id');
					})
					->leftJoin('account_master AS am', function($join) {
							$join->on('am.id', '=', 'account_setting.dr_account_master_id');
					})
					->select('account_setting.id AS voucher_id','account_setting.prefix','account_setting.is_prefix','account_setting.voucher_no','am.master_name','account_setting.voucher_name',
							'am.id','am.account_id','account_setting.dr_account_master_id')
					->get();
	}
	
	public function getVoucherByDeptDN($vid, $id) {
		
		$query = $this->accountsetting->where('account_setting.status',1)
						->where('account_setting.voucher_type_id',$vid)
						->where('account_setting.department_id',$id);
					
		return $query->join('department AS D', function($join) {
						$join->on('D.id', '=', 'account_setting.department_id');
					})
					->leftJoin('account_master AS am', function($join) {
							$join->on('am.id', '=', 'account_setting.cr_account_master_id');
					})
					->select('account_setting.id AS voucher_id','account_setting.prefix','account_setting.is_prefix','account_setting.voucher_no','am.master_name','account_setting.voucher_name',
							'am.id','am.account_id','account_setting.cr_account_master_id')
					->get();
	}
	
	public function getVoucherByDeptPN($vid, $id) {
		
		$query = $this->accountsetting->where('account_setting.status',1)
						->where('account_setting.voucher_type_id',$vid)
						->where('account_setting.department_id',$id);
					
		return $query->join('department AS D', function($join) {
						$join->on('D.id', '=', 'account_setting.department_id');
					})
					->leftJoin('account_master AS am', function($join) {
							$join->on('am.id', '=', 'account_setting.dr_account_master_id');
					})
					->select('account_setting.id AS voucher_id','account_setting.prefix','account_setting.is_prefix','account_setting.voucher_no','account_setting.voucher_name')
					->get();
	}
	
	public function getVoucherByDeptSN($vid, $id) {
		
		$query = $this->accountsetting->where('account_setting.status',1)
						->where('account_setting.voucher_type_id',$vid)
						->where('account_setting.department_id',$id);
					
		return $query->join('department AS D', function($join) {
						$join->on('D.id', '=', 'account_setting.department_id');
					})
					->leftJoin('account_master AS am', function($join) {
							$join->on('am.id', '=', 'account_setting.cr_account_master_id');
					})
					->select('account_setting.id AS voucher_id','account_setting.prefix','account_setting.is_prefix','account_setting.voucher_no','account_setting.voucher_name')
					->get();
	}
	
	public function getVoucherByDeptRV($vid, $id) {
		
		$query = $this->accountsetting->where('account_setting.status',1)
									->where('account_setting.voucher_type_id',$vid)
									->where('account_setting.department_id',$id);
		
		return $query->leftJoin('account_master AS am', function($join) {
							$join->on('am.id', '=', 'account_setting.dr_account_master_id');
					})
					->leftJoin('account_master AS c', function($join) {
							$join->on('c.id', '=', 'account_setting.cash_account_id');
					})
					->leftJoin('account_master AS b', function($join) {
							$join->on('b.id', '=', 'account_setting.bank_account_id');
					})
					->leftJoin('account_master AS p', function($join) {
							$join->on('p.id', '=', 'account_setting.pdc_account_id');
					})
					->join('voucher_type AS vt', function($join) {
							$join->on('vt.id', '=', 'account_setting.voucher_type_id');
					})
					->select('account_setting.prefix','account_setting.is_prefix','account_setting.voucher_no',
							 'account_setting.dr_account_master_id','am.master_name','am.id','am.account_id',
							 'account_setting.cash_account_id','account_setting.bank_account_id','account_setting.pdc_account_id',
							 'c.master_name AS cashaccount','b.master_name AS bankaccount','p.master_name AS pdcaccount',
							 'account_setting.voucher_name','vt.id AS vid')
					->get(); 
		
				
	}
	
}

