<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Input;
use Session;
use Response;
use DB;
use App;

class BackupController extends Controller
{
	
	public function __construct() {

		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->middleware('auth');
		
	}
	
	public function index() {
		$data = array();   //$year = '2019';
		//exec('D:\xampp\mysql\bin\mysql.exe -uprofitusr -pprofit123 -e "CREATE DATABASE profitacc365_"'.$year);exit;
		/* exec('D:\xampp\mysql\bin\mysql.exe -uprofitusr -pprofit123 -e "create database profit_copy"');
		exec('D:\xampp\mysql\bin\mysqldump.exe  -uprofitusr -pprofit123 profitacc365 > D:\DataBackup\profit.sql');
		$test = 'D:\xampp\mysql\bin\mysql.exe  -uprofitusr -pprofit123 profit_copy < D:\DataBackup\profit.sql';
		$d = exec($test);
		var_dump($d);
		exit;  */
		
		//$test = escapeshellcmd('D:\xampp\mysql\bin\mysql.exe -uabi -pabi123 -e "create database abc2"');
		/* $test = exec('D:\xampp\mysql\bin\mysql.exe -uabi -pabi123 -e "USE abc2_copy"');
		$test = escapeshellcmd('D:\xampp\mysql\bin\mysql.exe -uabi -pabi123 -e "SOURCE abc2.sql"'); */
		
		return view('body.backup.index')
					->withData($data);
	}
	
	public function submit()
	{
		//$file = "\Databackup_on_".date('d-m-Y H:i:s').".sql";
		//OBAID exec("\"D:\\xampp\\mysql\\bin\\mysqldump.exe\" -uobaidroot -pobaid@123  obaiidcv > D:\DataBackup\Databackup.sql");
		
		exec("\"F:\\xampp\\mysql\\bin\\mysqldump.exe\" -u".env('DB_USERNAME')." -p".env('DB_PASSWORD')."  ".env('DB_DATABASE')." > C:\DataBackup\Databackup_".env('DB_DATABASE').".sql");
		Session::flash('message', 'Data backup successfully generated in your system location in "D:\DataBackup\". Please keep in external storage safely.');
		//$this->backupDatabase();
		return redirect('backup');
		
		//$this->backupDatabase();
	}
	
	private function backupDatabase()
	{
		$tables = array();
		$db = DB::select('SELECT DATABASE()');
		$DbName = $db[0]->{'DATABASE()'};
		$result = DB::select('SHOW TABLES');//$this->bank->getTables();
		foreach($result as $row)
		{
		  $key = 'Tables_in_'.$DbName;
		  $tables[] = $row->$key;
		} 
		
	  
		$return = 'SET FOREIGN_KEY_CHECKS=0;' . "\r\n";
		$return.= 'SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";' . "\r\n";
		$return.= 'SET AUTOCOMMIT=0;' . "\r\n";
		$return.= 'START TRANSACTION;' . "\r\n";
	  
		$data = '';
		  foreach($tables as $table)
		  {
			$result = DB::table($table)->get(); 
			$num_fields = (sizeof($result) > 0)?count((array)$result[0]):0; //$num_fields = mysqli_num_fields($result);
			
			$data.= 'DROP TABLE IF EXISTS '.$table.';';
			$row2 = DB::select('SHOW CREATE TABLE '.$table);
			
			$data.= "\n\n".$row2[0]->{'Create Table'}.";\n\n";
			
			for ($i = 0; $i<$num_fields; $i++) 
			{
			  foreach($result as $row)//while($row = mysqli_fetch_row($result))
			  { $row = array_values((array)$row);
				$data.= 'INSERT INTO '.$table.' VALUES('; //$ar =[];
				for($x=0; $x<$num_fields; $x++) 
				{  //$ar[]=[$k,$v];
				  
				  $row[$x] = addslashes($row[$x]);
				  $row[$x] = $this->clean($row[$x]); // CLEAN QUERIES
				  
				  if (isset($row[$x])) { 
					$data.= '"'.$row[$x].'"';
				  } else { 
					$data.= '""';
				  }
				  
				  if ($x<($num_fields-1)) { 
					$data.= ','; 
				  }
				  
				}  //return $ar;// end of the for loop 2
				$data.= ");\n";
			  } // end of the while loop 
			} // end of the for loop 1
			
			$data.="\n\n\n";
		  }  // end of the foreach*/
	  
		$return .= 'SET FOREIGN_KEY_CHECKS=1;' . "\r\n";
		$return.= 'COMMIT;';
	  
	  //SAVE THE BACKUP AS SQL FILE
	  $fileName = strtoupper($DbName).'-Database-Backup-'.date('Y-m-d @ h-i-s').'.sql';
	  $handle = fopen($fileName,'w+'); //fopen($DbName.'-Database-Backup-'.date('Y-m-d @ h-i-s').'.sql','w+');
	  fwrite($handle,$data);
	  fclose($handle);
	   
	   if($data) {
		    header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename='.basename($fileName));
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($fileName));
			readfile($fileName);

			//exit;
			return true;
	   } else
			return false;
	}  // end of the function
	 
	 //  CLEAN THE QUERIES
	private function clean($str) {
		if(@isset($str)){
			$str = @trim($str);
			if(get_magic_quotes_gpc()) {
				$str = stripslashes($str);
			}
			return DB::connection()->getPdo()->quote($str);//mysqli_real_escape_string($str);
		}
		else{
			return 'NULL';
		}
	}
}
//SELECT itemmaster.image,itemmaster.model_no,groupcat.group_name,quotation_sales.voucher_no,quotation_sales.reference_no,quotation_sales.voucher_date,quotation_sales.total,quotation_sales.vat_amount AS total_vat,quotation_sales.discount AS total_discount,quotation_sales.net_total,quotation_sales.subtotal,account_master.account_id,account_master.master_name,account_master.address,account_master.phone,account_master.vat_no,terms.description AS terms,salesman.name AS salesman,quotation_sales_item.item_name,quotation_sales_item.quantity,quotation_sales_item.unit_price,quotation_sales_item.vat,quotation_sales_item.vat_amount,quotation_sales_item.line_total,quotation_sales_item.tax_include,quotation_sales_item.item_total,itemmaster.item_code,units.unit_name,header.description AS header,footer.description AS footer FROM quotation_sales JOIN account_master ON(account_master.id=quotation_sales.customer_id) LEFT JOIN terms ON(terms.id=quotation_sales.terms_id) LEFT JOIN salesman ON(salesman.id=quotation_sales.salesman_id) JOIN quotation_sales_item ON(quotation_sales_item.quotation_sales_id=quotation_sales.id) JOIN itemmaster ON(itemmaster.id=quotation_sales_item.item_id) JOIN units ON(units.id=quotation_sales_item.unit_id) LEFT JOIN header_footer header ON(header.id=quotation_sales.header_id) LEFT JOIN header_footer footer ON(footer.id=quotation_sales.footer_id) LEFT JOIN groupcat ON(groupcat.id=itemmaster.group_id) WHERE quotation_sales_item.status=1 AND quotation_sales_item.deleted_at='0000-00-00 00:00:00' AND quotation_sales.id={id}

//SELECT groupcat.group_name AS brand,customer_do.voucher_no,customer_do.reference_no,customer_do.voucher_date,customer_do.total,customer_do.vat_amount AS total_vat,customer_do.discount AS total_disc,customer_do.net_total,customer_do.subtotal,account_master.account_id,account_master.master_name,account_master.address,account_master.phone,account_master.vat_no,terms.description AS terms,salesman.name AS salesman,customer_do_item.item_name,customer_do_item.quantity,customer_do_item.unit_price,customer_do_item.vat,customer_do_item.vat_amount,customer_do_item.line_total,customer_do_item.item_total,itemmaster.item_code,units.unit_name FROM customer_do JOIN account_master ON(account_master.id=customer_do.customer_id) LEFT JOIN terms ON(terms.id=customer_do.terms_id) LEFT JOIN salesman ON(salesman.id=customer_do.salesman_id) JOIN customer_do_item ON(customer_do_item.customer_do_id=customer_do.id) JOIN itemmaster ON(itemmaster.id=customer_do_item.item_id) JOIN units ON(units.id=customer_do_item.unit_id) LEFT JOIN groupcat ON(groupcat.id=itemmaster.group_id) WHERE customer_do_item.status=1 AND customer_do_item.deleted_at='0000-00-00 00:00:00' AND customer_do.id={id}

//SELECT groupcat.group_name AS brand,sales_invoice.voucher_no,reference_no,sales_invoice.voucher_date,sales_invoice.lpo_no,sales_invoice.total,sales_invoice.discount,sales_invoice.vat_amount,sales_invoice.net_total,sales_invoice.total_fc,sales_invoice.discount_fc,sales_invoice.vat_amount_fc,sales_invoice.net_total_fc,sales_invoice.customer_name,sales_invoice.customer_phone,sales_invoice.subtotal,account_master.account_id,account_master.master_name,account_master.address,account_master.phone,account_master.vat_no,terms.description AS terms,salesman.name AS salesman,sales_invoice_item.item_name,sales_invoice_item.quantity,sales_invoice_item.unit_price,sales_invoice_item.vat,sales_invoice_item.vat,sales_invoice_item.vat_amount AS line_vat,sales_invoice_item.line_total,sales_invoice_item.tax_include,sales_invoice_item.item_total,itemmaster.item_code,units.unit_name,sales_invoice_item.id AS sii_id FROM sales_invoice JOIN account_master ON(account_master.id=sales_invoice.customer_id) LEFT JOIN terms ON(terms.id=sales_invoice.terms_id) LEFT JOIN salesman ON(salesman.id=sales_invoice.salesman_id) JOIN sales_invoice_item ON(sales_invoice_item.sales_invoice_id=sales_invoice.id) JOIN itemmaster ON(itemmaster.id=sales_invoice_item.item_id) JOIN units ON(units.id=sales_invoice_item.unit_id) LEFT JOIN groupcat ON(groupcat.id=itemmaster.group_id) WHERE sales_invoice_item.status=1 AND sales_invoice_item.deleted_at='0000-00-00 00:00:00' AND sales_invoice.id={id} ORDER BY sales_invoice_item.id ASC

//SELECT groupcat.group_name AS brand,sales_order.voucher_no,sales_order.reference_no,sales_order.voucher_date,sales_order.total,sales_order.vat_amount AS total_vat,sales_order.discount AS total_disc,sales_order.net_total,sales_order.subtotal,account_master.account_id,account_master.master_name,account_master.address,account_master.phone,account_master.vat_no,terms.description AS terms,salesman.name AS salesman,sales_order_item.item_name,sales_order_item.quantity,sales_order_item.unit_price,sales_order_item.vat,sales_order_item.vat_amount AS line_vat,sales_order_item.line_total,sales_order_item.tax_include,sales_order_item.item_total,itemmaster.item_code,units.unit_name,V.reg_no AS reg,V.name AS vehicle,V.make AS make,V.color AS color,V.engine_no AS engine,V.chasis_no AS chasis,V.owner AS owner,V.km_done,V.model AS model,V.year AS year,V.issue_plate AS issue,V.code_plate AS code,V.color_code AS color,footer.description AS footer FROM sales_order JOIN account_master ON(account_master.id=sales_order.customer_id) LEFT JOIN terms ON(terms.id=sales_order.terms_id) LEFT JOIN vehicle AS V ON(V.id=sales_order.vehicle_id) LEFT JOIN salesman ON(salesman.id=sales_order.salesman_id) JOIN sales_order_item ON(sales_order_item.sales_order_id=sales_order.id) JOIN itemmaster ON(itemmaster.id=sales_order_item.item_id) JOIN units ON(units.id=sales_order_item.unit_id) LEFT JOIN header_footer footer ON(footer.id=sales_order.footer_id) LEFT JOIN groupcat ON(groupcat.id=itemmaster.group_id) WHERE sales_order_item.status=1 AND sales_order_item.deleted_at='0000-00-00 00:00:00' AND sales_order.id={id}