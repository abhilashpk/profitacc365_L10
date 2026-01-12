<?php

namespace App\Http\Controllers;
use App\Repositories\Itemmaster\ItemmasterInterface;
use App\Repositories\Forms\FormsInterface;

use Illuminate\Http\Request;

use App\Http\Requests;
use Session;
use Response;
use Input;
use DB;
use Excel;
use App;

class ItemenquiryController extends Controller
{
	protected $itemmaster;
	protected $formData;

	
	public function __construct(ItemmasterInterface $itemmaster, FormsInterface $forms) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->itemmaster = $itemmaster;
		$this->forms = $forms;
		$this->formData = $this->forms->getFormData('IE');
		$this->middleware('auth');

	}
	
    public function index() {
		
		$data = array(); 
		//$this->itemmaster->ItemLogProcess();
		$items = [];//$this->itemmaster->itemenquiryList();
		$colarr = $this->sortFormData($this->formData);
		//$dat = $this->sortFormData($this->formData);
		//echo'<pre>';print_r($this->formData);exit;
		return view('body.itemenquiry.index')
					->withItems($items)
					->withFormdata($this->formData)
					->withCols($colarr)
					->withData($data); 
	}
	
	private function sortFormData($data)
	{
		$arr = [];
		foreach($data as $key => $val) {
			if($val==1)
				$arr[] = $key;
		}
		return $arr;
	}
	
	public function ajaxPaging(Request $request)
	{
		
		/* $columns = array( 
                            0 => 'itemmaster.id', 
							1 =>'item_code', 
                            2 =>'description',
							3 => 'unit',
							4 => 'group',
                            5=> 'quantity',
                            6=> 'cost_avg',
							7=> 'sell_price',
                            8=> 'last_purchase_cost',
							9=> 'received_qty',
							10=> 'issued_qty'
                        ); */
		
		$colarr = $this->sortFormData($this->formData);
		
		$columns[] = 'itemmaster.id';
		foreach($colarr as $col) {
			$columns[] = $col;
		}
		
		/* $columns = array( 
                            0 => 'itemmaster.id', 
							1 => $colarr[0], 
                            2 => $colarr[1],
							3 => $colarr[2],
							4 => $colarr[3],
                            5=> $colarr[4],
                            6=> $colarr[5],
							7=> $colarr[6],
                            8=> $colarr[7],
							9=> $colarr[8],
							10=> $colarr[9]
                        ); */
						
		/* $columns = array( 
                            0 => 'itemmaster.id', 
							1 =>'item_code', 
                            2 =>'description',
							3 => 'unit',
							4 => 'group',
                            5=> 'modelno',
                            6=> 'cost_avg',
							7=> 'sell_price',
                            8=> 'category',
							9=> 'subcategory',
							10=> 'model'
                        ); */
						
		$totalData = $this->itemmaster->itemmasterListCount();
            
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[1];//$request->input('order.0.column')
        $dir = $request->input('order.0.dir');
		$search = (empty($request->input('search.value')))?null:$request->input('search.value');
        
		$items = $this->itemmaster->itemmasterList('get', $start, $limit, $order, $dir, $search);
		
		if($search)
			$totalFiltered =  $this->itemmaster->itemmasterList('count', $start, $limit, $order, $dir, $search);
		
        $data = array();
        if(!empty($items))
        {
            foreach ($items as $post)
            {
                $opt =  $post->id;
				$edit =  '"'.url('itemmaster/edit/'.$post->id).'"';
                $delete =  'funDelete("'.$post->id.'")';
				$img=url('uploads/item/thumb_'.$post->image);
				$nestedData['opt'] = "<input type='radio' name='item' class='opt-account' value='{$opt}'/>";
                $nestedData['id'] = $post->id;
                $nestedData['item_code'] = $post->item_code;
				$nestedData['unit'] = $post->packing;
				$nestedData['description'] = $post->description;
				$nestedData['model_no'] = $post->model_no;
				$nestedData['unit_price'] = number_format($post->sell_price,2);
				$nestedData['group'] = $post->group_name;
				$nestedData['subgroup'] = $post->subgroup;
				$nestedData['avg_cost'] = number_format($post->cost_avg,2);
				$nestedData['last_pur_cost'] = number_format($post->last_purchase_cost,2);
				$nestedData['rc_qty'] = $post->received_qty;
				$nestedData['is_qty'] = $post->issued_qty;
				$nestedData['category'] = $post->category;
				$nestedData['subcategory'] = $post->subcategory;
				$nestedData['model'] = $post->bin;
				$nestedData['reserve_qty'] = $post->reorder_level;
				$nestedData['qty_in_hand'] = $post->quantity;
				$nestedData['item_width'] = $post->itmWd;
				$nestedData['item_length'] = $post->itmLt;
				$nestedData['mp_quantity'] = $post->mpqty;
				$nestedData['view_image'] = ($post->image!="")?"<img src=$img width='65%'>":"No Image" ;
				/* $nestedData['opt'] = "<input type='radio' name='item' class='opt-account' value='{$opt}'/>";
                $nestedData['id'] = $post->id;
                $nestedData['item_code'] = $post->item_code;
				$nestedData['unit'] = $post->packing;
				$nestedData['description'] = $post->description;
				$nestedData['modelno'] = $post->model_no;
				$nestedData['sell_price'] = $post->sell_price;
				$nestedData['group'] = $post->group_name;
				$nestedData['cost_avg'] = number_format($post->cost_avg,2);
				$nestedData['last_purchase_cost'] = number_format($post->last_purchase_cost,2);
				$nestedData['received_qty'] = $post->received_qty;
				$nestedData['issued_qty'] = $post->issued_qty;
				$nestedData['category'] = $post->category;
				$nestedData['subcategory'] = $post->subcategory;
				$nestedData['model'] = $post->reorder_level; */
				
				$nestedData['edit'] = "<p><button class='btn btn-primary btn-xs' onClick='location.href={$edit}'>
												<span class='glyphicon glyphicon-pencil'></span></button></p>";
												
				$nestedData['delete'] = "<button class='btn btn-danger btn-xs delete' onClick='{$delete}'>
												<span class='glyphicon glyphicon-trash'></span>";
               
											
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
	
	 public function details() {
		
		$data = array();
		$items = $this->itemmaster->getItemEnquiry(Input::all()); //echo '<pre>';print_r($items);exit;
		$heading = $this->getSearchType(Input::get('search_type'));
		return view('body.itemenquiry.details')
					->withItems($items)
					->withItemid(Input::get('item_id'))
					->withCussupid(Input::get('custsupp_id'))
					->withType(Input::get('search_type'))
					->withFromdate(Input::get('date_from'))
					->withTodate(Input::get('date_to'))
					->withHeading($heading)
					->withData($data);
	}	
	
	public function printItem($id, $type) { 

		$data = array();
		$attributes['item_id'] = $id; 
		$attributes['search_type'] = $type;
		$items = $this->itemmaster->getItemEnquiry($attributes); //echo '<pre>';print_r($items);exit;
		$details = $this->itemmaster->find($id); //echo '<pre>';print_r($items);exit;
		$heading = $this->getSearchType($type);
		
		return view('body.itemenquiry.print')
					->withItems($items)
					->withHeading($heading)
					->withDetails($details)
					->withType(Input::get('search_type'))
					->withData($data);

	}
	
	private function getSearchType($type)
	{
		switch($type) {
			case 'SI':
				$result = 'Sales Invoice';
				break;
				
			case 'PI':
				$result = 'Purchase Invoice';
				break;
				
			case 'PR':
				$result = 'Purchase Return';
				break;
				
			case 'SR':
				$result = 'Sales Return';
				break;
			
			case 'SO':
				$result = 'Sales Order';
				break;
				
			case 'PO':
				$result = 'Purchase Order';
				break;
				
			default:
				$result = '';
		}
		return $result;
	}
	
	public function getCustomerSupplier()
	{
		$custsupp = DB::table('account_master')->whereIn('category',['CUSTOMER','SUPPLIER'])
					->where('status',1)->where('deleted_at','0000-00-00 00:00:00')
					->select('id','master_name')
					->orderBy('master_name','ASC')
					->get();
					
		echo json_encode($custsupp);
	}
	
	public function dataExport()
	{
		$data = array();
		$datareport[] = [strtoupper(Session::get('company')),'','',''];
		$datareport[] = ['','','','','','',''];
		
		$items = $this->itemmaster->getItemEnquiry(Input::all()); //echo '<pre>';print_r($items);exit;
		$heading = $this->getSearchType(Input::get('search_type'));
		$ttle = ($heading=='Purchase Invoice')?'Other Cost':'';
		$datareport[] = ['','','',strtoupper($heading.' History'), '','',''];
		$datareport[] = ['','','','','','',''];
		$datareport[] = ['Item Code:',$items[0]->item_code,'','Item Name:',$items[0]->item_name,'',''];
		$datareport[] = ['','','','','','',''];
		$datareport[] = ['Inv.No.','Date','Customer/Supplier','Quantity', 'Rate','Total',$ttle];
		
		$netqty = $net_uprice = $netotal = $net_octotal = 0;
		foreach($items as $item) {
				
				$datareport[] = [ 'invno' => $item->voucher_no,
								  'date' => date('d-m-Y',strtotime($item->voucher_date)),
								  'master_name' => $item->master_name,
								  'quantity' => $item->quantity,
								  'unit_price' => number_format($item->unit_price,2),
								  'total_price' => number_format($item->total_price,2),
								  'other_cost' => ($heading=='Purchase Invoice')?number_format($item->other_cost,2):''
								];
								
				$netqty += $item->quantity;
				$net_uprice += $item->unit_price;
				$netotal += $item->total_price;
				$net_octotal += $item->other_cost;
		}
		
		$datareport[] = ['','','Total:',$netqty, number_format($net_uprice,2), number_format($netotal,2), ($heading=='Purchase Invoice')?number_format($net_octotal,2):''];
		
		 //echo $voucher_head.'<pre>';print_r($datareport);exit;
		Excel::create($heading.' History', function($excel) use ($datareport,$heading) {

        // Set the spreadsheet title, creator, and description
        $excel->setTitle($heading.' History');
        $excel->setCreator('Profit ACC 365 - ERP')->setCompany(Session::get('company'));
        $excel->setDescription($heading.' History');

        // Build the spreadsheet, passing in the payments array
		$excel->sheet('sheet1', function($sheet) use ($datareport) {
			$sheet->fromArray($datareport, null, 'A1', false, false);
		});

		})->download('xlsx');
		
	}
	
	public function getForm($id) {
		
		//$item = $this->itemmaster->find($id);
	$item=	DB::table('itemmaster')->join('item_unit AS IU', 'IU.itemmaster_id', '=', 'itemmaster.id')
	                               ->where('itemmaster.id',$id)->select('itemmaster.*','IU.sell_price')
	                               ->first();
		//echo '<pre>';print_r($item);exit;
		return view('body.itemenquiry.getform')->withItem($item);
	}
	
}
