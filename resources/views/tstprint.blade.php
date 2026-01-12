<?php$result['purchase'] = DB::table('purchase_invoice')
											->join('account_master', 'account_master.id', '=', 'purchase_invoice.supplier_id')
											->leftJoin('pi_other_cost', 'pi_other_cost.purchase_invoice_id', '=', 'purchase_invoice.id')
											->leftJoin('area', 'area.id', '=', 'account_master.area_id')
											->where('account_master.status',1)
											->where('purchase_invoice.status',1)
											//->where('purchase_invoice.is_import',0)
											->where('purchase_invoice.deleted_at','0000-00-00 00:00:00')
											->whereBetween('purchase_invoice.voucher_date', array($date_from, $date_to))
											->select('account_master.master_name','account_master.vat_no','purchase_invoice.voucher_no','purchase_invoice.voucher_date','account_master.vat_no','pi_other_cost.oc_vatamt',
													 'area.code','account_master.id','purchase_invoice.total','purchase_invoice.vat_amount','purchase_invoice.net_amount',DB::raw('"PI" AS type'),'purchase_invoice.is_import')
											->orderBy('purchase_invoice.id','ASC')
											->get(); 

				$result['sales'] = DB::table('sales_invoice')
											->join('account_master', 'account_master.id', '=', 'sales_invoice.customer_id')
											->leftJoin('area', 'area.id', '=', 'account_master.area_id')
											->where('account_master.status',1)
											->where('sales_invoice.status',1)
											->where('sales_invoice.deleted_at','0000-00-00 00:00:00')
											->whereBetween('sales_invoice.voucher_date', array($date_from, $date_to))
											->select('account_master.master_name','account_master.vat_no','sales_invoice.voucher_no','sales_invoice.voucher_date','account_master.vat_no',
													 'area.code','account_master.id','sales_invoice.total','sales_invoice.vat_amount','sales_invoice.net_total',DB::raw('"SI" AS type'))
											->orderBy('sales_invoice.id','ASC')
											->get(); //echo '<pre>';//print_r($result);exit; 

				$result['purchase_ret'] = DB::table('purchase_return')
											->join('account_master', 'account_master.id', '=', 'purchase_return.supplier_id')
											->join('purchase_invoice', 'purchase_invoice.id', '=', 'purchase_return.purchase_invoice_id')
											->leftJoin('area', 'area.id', '=', 'account_master.area_id')
											->where('account_master.status',1)
											->where('purchase_return.status',1)
											->where('purchase_invoice.is_import',0)
											->where('purchase_return.deleted_at','0000-00-00 00:00:00')
											->whereBetween('purchase_return.voucher_date', array($date_from, $date_to))
											->select('account_master.master_name','account_master.vat_no','purchase_return.voucher_no','purchase_return.voucher_date','account_master.vat_no',
													 'area.code','account_master.id','purchase_return.total','purchase_return.vat_amount','purchase_return.net_amount AS net_total',DB::raw('"PR" AS type'))
											->orderBy('purchase_return.id','ASC')
											->get(); 
				
				$result['sales_ret'] = DB::table('sales_return')
											->join('account_master', 'account_master.id', '=', 'sales_return.customer_id')
											->leftJoin('area', 'area.id', '=', 'account_master.area_id')
											->where('account_master.status',1)
											->where('sales_return.status',1)
											->where('sales_return.deleted_at','0000-00-00 00:00:00')
											->whereBetween('sales_return.voucher_date', array($date_from, $date_to))
											->select('account_master.master_name','account_master.vat_no','sales_return.voucher_no','sales_return.voucher_date','account_master.vat_no',DB::raw('"0" AS is_import'),
													 'area.code','account_master.id','sales_return.total','sales_return.vat_amount','sales_return.net_amount',DB::raw('"SR" AS type'),DB::raw('"0" AS oc_vatamt'))
											->orderBy('sales_return.id','ASC')
											->get();
											
				

						$qry1 = DB::table('journal')
											->join('journal_entry', 'journal_entry.journal_id', '=', 'journal.id')
											->where('journal_entry.account_id', $vatmaster->expense_account)
											->where('journal_entry.status', 1)
											->where('journal_entry.deleted_at','0000-00-00 00:00:00')
											->where('journal.status',1)
											->where('journal.deleted_at','0000-00-00 00:00:00')
											//->where('journal.group_id', 33)
											->whereBetween('journal.voucher_date', array($date_from, $date_to))
											->select('journal.supplier_name AS master_name','journal.trn_no AS vat_no','journal.voucher_no','journal.voucher_date',
													 'journal.debit AS total','journal_entry.amount AS vat_amount','journal.credit AS net_amount',DB::raw('"JV" AS type'))
											->orderBy('journal.id','ASC');
											
						$qry2 = DB::table('payment_voucher')
											->join('payment_voucher_entry', 'payment_voucher_entry.payment_voucher_id', '=', 'payment_voucher.id')
											->where('payment_voucher_entry.account_id', $vatmaster->expense_account)
											//->where('payment_voucher.group_id', 33)
											->where('payment_voucher_entry.status', 1)
											->where('payment_voucher_entry.deleted_at','0000-00-00 00:00:00')
											->where('payment_voucher.status',1)
											->where('payment_voucher.deleted_at','0000-00-00 00:00:00')
											->whereBetween('payment_voucher.voucher_date', array($date_from, $date_to))
											->select('payment_voucher.supplier_name AS master_name','payment_voucher.trn_no AS vat_no','payment_voucher.voucher_no','payment_voucher.voucher_date',
													 'payment_voucher.debit AS total','payment_voucher_entry.amount AS vat_amount','payment_voucher.credit AS net_amount',DB::raw('"PV" AS type'))
											->orderBy('payment_voucher.id','ASC');
											
						$qry3 = DB::table('petty_cash')
											->join('petty_cash_entry', 'petty_cash_entry.petty_cash_id', '=', 'petty_cash.id')
											->where('petty_cash_entry.account_id', $vatmaster->expense_account)
											->where('petty_cash.status',1)
											//->where('petty_cash.group_id', 33)
											->where('petty_cash_entry.status', 1)
											->where('petty_cash_entry.deleted_at','0000-00-00 00:00:00')
											->where('petty_cash.deleted_at','0000-00-00 00:00:00')
											->whereBetween('petty_cash.voucher_date', array($date_from, $date_to))
											->select('petty_cash.supplier_name AS master_name','petty_cash.trn_no AS vat_no','petty_cash.voucher_no','petty_cash.voucher_date',
													 'petty_cash.debit AS total','petty_cash_entry.amount AS vat_amount','petty_cash.credit AS net_amount',DB::raw('"PC" AS type'))
											->orderBy('petty_cash.id','ASC');
										
											
				$result['inputexp'] = $qry1->union($qry2)->union($qry3)->get();