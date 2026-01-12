<?php

namespace App\Http\Controllers;
use App\Repositories\Group\GroupInterface;
use App\Repositories\Category\CategoryInterface;
use App\Repositories\Unit\UnitInterface;
use App\Repositories\Itemmaster\ItemmasterInterface;
use App\Repositories\VatMaster\VatMasterInterface;
use App\Repositories\Forms\FormsInterface;
//use App\Repositories\ItemUnit\ItemUnitInterface;
use Ixudra\Curl\Facades\Curl;


use Illuminate\Http\Request;

use App\Http\Requests;
use Session;
use Response;
use Input;
use DB;
use DNS1D;
use App;

class ItemmasterController extends Controller
{
	protected $group;
	protected $category;
	protected $unit;
	protected $itemmaster;
	protected $vatmaster;
	protected $forms;
	protected $formData;
	//protected $itemunit;
	
	public function __construct(GroupInterface $group, CategoryInterface $category, UnitInterface $unit, ItemmasterInterface $itemmaster, VatMasterInterface $vatmaster,FormsInterface $forms) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->middleware('auth');
		$this->group = $group;
		$this->category = $category;
		$this->unit = $unit;
		$this->itemmaster = $itemmaster;
		$this->vatmaster = $vatmaster;
		$this->forms = $forms;
		$this->formData = $this->forms->getFormData('ITMAD');
		
		//$this->itemunit = $itemunit;
		
		
	}
	
	/* private function setItemlogs() {
		$items = DB::table('itemmaster')->where('itemmaster.status',1)->where('itemmaster.deleted_at','0000-00-00 00:00:00')
					->join('item_unit', 'item_unit.itemmaster_id', '=', 'itemmaster.id')
					->where('item_unit.is_baseqty',1)
					->select('itemmaster.id','item_unit.unit_id')->get();
		//echo '<pre>';print_r($items);exit;
		foreach($items as $item) {
			DB::table('item_log')->insert([
								'document_type' => 'OQ',
								'document_id' => 0,
								'item_id' => $item->id,
								'unit_id' => $item->unit_id,
								'trtype' => 1,
								'packing' => 1,
								'status' => 1,
								'created_at' => '2019-11-12 02:07:10',
								'voucher_date' => '2019-11-12'
								]);
		}
	} */
	
    public function index() {
		
		/* $imageurl = 'https://urban-vision.crm.elateapps.com/assets/uploads/products/OLX 611-86.jpg';
		$ar1 = explode('products/',$imageurl);
		$ex = explode('.',$ar1[1]);
		
		echo $content = $ar1[0].'products/'.rawurlencode($ar1[1]);exit; */
								
								
		$data = array(); 
		//$this->itemmaster->ItemLogProcess();
		$items = [];//$this->itemmaster->itemmasterList();
		$arrData = $this->getGroupCategory();
		$vats = $this->vatmaster->activeVatMasterList();
		$colarr = $this->sortFormData($this->forms->getFormData('IE'));

		return view('body.itemmaster.index')
					->withItems($items)
					->withUnits($arrData['units'])
					->withVats($vats)
					->withFormdata($this->forms->getFormData('IE'))
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
	public function ajaxgetItem(Request $request)
	{
		$columns = array( 
                            0 =>'item_code', 
                            1 =>'description',
                            2=> 'quantity',
                            3=> 'cost_avg',
                            4=> 'sale_price'
                        );
		$mod = $request->input('mod');	
		$totalData = $this->itemmaster->getActiveItemListCount($mod);
            
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
		$search = (empty($request->input('search.value')))?null:$request->input('search.value');
		
		$items = $this->itemmaster->getActiveItemList($mod, 'get', $start, $limit, $order, $dir, $search);
		
		if($search)
			$totalFiltered =  $this->itemmaster->getActiveItemList($mod, 'count', $start, $limit, $order, $dir, $search);
		
		$data = array();
        if(!empty($items))
        {
            foreach ($items as $post)
            {
                $nestedData['id'] = $post->id;
                $nestedData['item_code'] = "<a href='' class='itemRow' data-id='{$post->id}' data-code='{$post->item_code}' data-name='{$post->description}' data-unit='{$post->unit_name}' data-vat='{$post->vat}' data-costavg='{$post->cost_avg}' data-purcost='{$post->pur_cost}' data-type='{$post->class_id}' data-cost='{$post->sell_price}' data-dismiss='modal'>{$post->item_code}</a>";
				$nestedData['description'] = "<a href='' class='itemRow' data-id='{$post->id}' data-code='{$post->item_code}' data-name='{$post->description}' data-unit='{$post->unit_name}' data-vat='{$post->vat}' data-costavg='{$post->cost_avg}' data-purcost='{$post->pur_cost}' data-type='{$post->class_id}' data-cost='{$post->sell_price}' data-dismiss='modal'>{$post->description}</a>";
				$nestedData['quantity'] = $post->cur_quantity;
				$nestedData['cost_avg'] = number_format($post->cost_avg,2);
				$nestedData['sale_price'] = number_format($post->sell_price,2);		
				
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
		
		/* $data = array();
		$itemmaster = $this->itemmaster->getActiveItemmasterList($mod);
		$arrData = $this->getGroupCategory();
		$vats = $this->vatmaster->activeVatMasterList();
		$view = ($mod=='ser')?'service':'item'; */
	}
	
	/*public function ajaxPaging(Request $request)
	{
		$columns = array( 
                            0=>'item_code', 
                            1=>'description',
                            2=> 'quantity',
                            3=> 'cost_avg',
                            4=> 'last_purchase_cost',
							5=> 'other_cost',
							6=> 'received_qty',
							7=> 'issued_qty'
                        );
						
		$totalData = $this->itemmaster->itemmasterListCount();
            
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
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
                $edit =  '"'.url('itemmaster/edit/'.$post->id).'"';
                $delete =  'funDelete("'.$post->id.'")';
				
                $nestedData['id'] = $post->id;
                $nestedData['item_code'] = $post->item_code;
				$nestedData['description'] = $post->description;
				$nestedData['quantity'] = "<span style='background-color:#6ee448 !important;'>".$post->quantity."</span>";
				$nestedData['cost_avg'] = number_format($post->cost_avg,2);
				$nestedData['last_purchase_cost'] = number_format($post->last_purchase_cost,2);
				$nestedData['other_cost'] = number_format($post->other_cost,2);
				$nestedData['received_qty'] = $post->received_qty;
				$nestedData['issued_qty'] = $post->issued_qty;
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
	}*/

	public function ajaxPaging(Request $request)
	{
		
		
		$colarr = $this->sortFormData($this->forms->getFormData('IE'));
		
		$columns[] = 'itemmaster.id';
		foreach($colarr as $col) {
			$columns[] = $col;
		}
		
		
						
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
	
	
	public function getGroupCategory() {
		
		$arrData = array();
		$result = $this->group->activeGroupList();
		$arrData['groups'] = array_filter( array_map( function($result) {
								if($result['parent_id']==0) {
									$groups['id'] = $result['id'];
									$groups['name'] = $result['group_name'];
									return $groups;
								} 
							}, $result));
		$arrData['subgroups'] = array_filter( array_map( function($result) {
								if($result['parent_id']==1) {
									$groups['id'] = $result['id'];
									$groups['name'] = $result['group_name'];
									return $groups;
								} 
							}, $result));
							
		$catresult = $this->category->activeCategoryList(); 
		$arrData['category'] = array_filter( array_map( function($result) {
								if($result['parent_id']==0) {
									$category['id'] = $result['id'];
									$category['name'] = $result['category_name'];
									return $category;
								} 
							}, $catresult));
		$arrData['subcategory'] = array_filter( array_map( function($result) {
								if($result['parent_id']==1) {
									$category['id'] = $result['id'];
									$category['name'] = $result['category_name'];
									return $category;
								} 
							}, $catresult));
							
		$arrData['units'] = $this->unit->activeUnitList();
		
		return $arrData;
		
	}
	
	public function add() {

		$data = array();
		$arrData = $this->getGroupCategory();
		$vats = $this->vatmaster->activeVatMasterList();
		$location = DB::table('location')->where('is_default',1)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id')->first();
		$items = $this->itemmaster->activeItemmasterList();
		//echo '<pre>';print_r($vats);exit;
		return view('body.itemmaster.add')
					->withGroups($arrData['groups'])
					->withSubgroups($arrData['subgroups'])
					->withCategory($arrData['category'])
					->withSubcategory($arrData['subcategory'])
					->withUnits($arrData['units'])
					->withVats($vats)
					->withLocation($location)
					->withFormdata($this->formData)
					->withItems($items)
					->withData($data);
	}
	
	public function save() {
		//echo '<pre>';print_r(json_encode(Input::all()));exit;
		$this->itemmaster->create(Input::all());
		Session::flash('message', 'Item added successfully.');
		return redirect('itemmaster/add');
	}
	
	public function destroy($id)
	{
		$status = $this->itemmaster->check_item($id);
		if($status) {
			$this->itemmaster->delete($id);
			Session::flash('message', 'Item deleted successfully.');
		} else 
			Session::flash('error', 'Item is already in use, you can\'t delete this!');
		
		return redirect('itemmaster');
	}
	
	public function checkcode() {

		$check = $this->itemmaster->check_item_code(trim(Input::get('item_code')), Input::get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
	
	public function checkdesc() {

		$check = $this->itemmaster->check_item_description(trim(Input::get('description')), Input::get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
	
	public function edit($id) { 

		$data = array();
		$url = (str_replace(url('/'), '', url()->previous())=='/itemenquiry')?'itemenquiry':'itemmaster';
		$itemrow = $this->itemmaster->find($id);
		$item_unit = $this->itemmaster->getItemUnit($id);
		$arrData = $this->getGroupCategory();;
		$vats = $this->vatmaster->activeVatMasterList();
		$loc = $this->itemmaster->getLocation();
		$stockloc = $this->itemmaster->getStockLocation($id); //echo '<pre>';print_r($loc);  echo '<pre>';print_r($stockloc); exit;
		$items = $this->itemmaster->activeItemmasterList();
		$rowmaterials = DB::table('mfg_items')->where('mfg_items.item_id',$id)
								->join('itemmaster AS IM', function($join) {
									$join->on('IM.id','=','mfg_items.subitem_id');
								})
								->where('mfg_items.deleted_at','0000-00-00 00:00:00')
								->select('mfg_items.*','IM.item_code','IM.description')
								->get();
		
		return view('body.itemmaster.edit')
					->withItemrow($itemrow)
					->withGroups($arrData['groups'])
					->withSubgroups($arrData['subgroups'])
					->withCategory($arrData['category'])
					->withSubcategory($arrData['subcategory'])
					->withUnits($arrData['units'])
					->withItemunits($item_unit)
					->withVats($vats)
					->withLocations($loc)
					->withStockloc($stockloc)
					->withFromurl($url)
					->withFormdata($this->formData)
					->withItems($items)
					->withRowmaterials($rowmaterials)
					->withData($data);
	}
	
	public function update($id)
	{
		//echo '<pre>';print_r(Input::all());
		$this->itemmaster->update($id, Input::all());//exit;
		Session::flash('message', 'Item Master updated successfully');
		return redirect(Input::get('fromurl'));
		//return redirect('itemmaster');
	}
	
	public function getVat($id,$item=null) 
	{
		$result = $this->itemmaster->getVatByUnit($id,$item);
		//echo $result->vat; //print_r($result);
		
		return array('vat' => $result->vat,
					  'packing' => ($result->is_baseqty==1)?1:$result->packing,
					  'price' => $result->sell_price
					);
	}
	
	public function getInfo($id)
	{
		$info = $this->itemmaster->getItemInfo($id);
		return view('body.itemmaster.iteminfo')
					->withInfo($info);
	}
	
	public function getRawmat($id)
	{
		$info = $this->itemmaster->getRawmat($id);
		return view('body.itemmaster.rawmat')
					->withInfo($info);
	}
	
	public function getPurchaseCost()
	{
		//print_r(Input::all());
		$result = $this->itemmaster->getLastPurchaseCost(Input::all());
		if($result) {
			$cr = (Input::get('cr')!='' && Input::get('cr') > 0)?Input::get('cr'):1;
			echo number_format(($result->unit_price/$cr),2);
		} else
			echo '';
	}
	
	public function getSaleCost()
	{
		//echo '<pre>';print_r(Input::all());
		$result = $this->itemmaster->getLastSaleCost(Input::all());
		if($result) {
			if(Input::get('crate')!='' && Input::get('crate') > 0) {
				$unit_price = $result->unit_price / Input::get('crate');
			} else 
				$unit_price = $result->unit_price;
			
			echo $unit_price;
		} else
			echo '';
	}
	
	public function getSaleCostAvg()
	{
		//print_r(Input::all());
		$result = $this->itemmaster->getSaleCostAvg(Input::all()); //echo '<pre>';print_r($result);exit;
		if($result)
			echo $result->unit_price;
		else
			echo '';
	}
	
	public function getItemCostAvg()
	{
		$result = $this->itemmaster->getItemCostAvg(Input::all());
		if($result)
			echo $result->unit_price;
		else
			echo '';
	}
	
	public function getItem($num,$mod=null)
	{
		$data = array();
		$itemmaster = [];//$this->itemmaster->getActiveItemmasterList($mod);
		$arrData = $this->getGroupCategory();
		$vats = $this->vatmaster->activeVatMasterList();
		$view = ($mod=='ser')?'service':'item';
		
		return view('body.itemmaster.'.$view)
					->withItems($itemmaster)
					->withNum($num)
					->withUnits($arrData['units'])
					->withVats($vats)
					->withMod($mod)
					->withData($data);
	}
	
	public function getItemRm($num,$mod=null)
	{
		$data = array();
		$itemmaster = [];//$this->itemmaster->getActiveItemmasterList($mod);
		$arrData = $this->getGroupCategory();
		$vats = $this->vatmaster->activeVatMasterList();
		$view = 'rmitem';
		
		return view('body.itemmaster.'.$view)
					->withItems($itemmaster)
					->withNum($num)
					->withUnits($arrData['units'])
					->withVats($vats)
					->withMod($mod)
					->withData($data);
	}
	
	public function getItemRw()
	{
		$data = array();
		$itemmaster = [];
				
		return view('body.itemmaster.itemrw')
					->withItems($itemmaster)
					->withNum(1)
					->withMod('item')
					->withData($data);
	}
		
	public function getItemLoad($code)
	{
		$row = $this->itemmaster->getItemByCode($code);
		
		if($row) {
			return $result = array('id' => $row->id,
									'description' => $row->description,
									'vat' => $row->vat,
									'unit_id' =>$row->unit_id,
									'unit' => $row->packing,
									'cost_avg' => $row->cost_avg,
									'pur_cost' => $row->last_purchase_cost);
		} else 
			return null;
	}
	
	public function getCostAvg()
	{
		//print_r(Input::all());
		$result = $this->itemmaster->getCostAvg(Input::all());
		if($result) {
			$cr = (Input::get('cr')!='' && Input::get('cr') > 0)?Input::get('cr'):1;
			echo number_format(($result->cost_avg/$cr),2);
		} else
			echo '';
	}
	
	
	public function getCostAvgMfg()
	{
		$result = $this->itemmaster->getCostAvgMfg(Input::all());
		if($result) {
			$cr = (Input::get('cr')!='' && Input::get('cr') > 0)?Input::get('cr'):1;
			echo number_format(($result->cost_avg/$cr),2);
		} else
			echo '';
	}
	
	
	public function getCostSale()
	{
		//print_r(Input::all());
		$result = $this->itemmaster->getCostSale(Input::all());
		if($result)
			echo $result->cost_avg;
		else
			echo '';
	}
	
	public function ajaxSave() {
		
		$as = $this->itemmaster->ajaxCreate(Input::all());
		return $as;
			
	}
	
	public function getLocInfo($id,$n,$inv_id=null,$type=null)
	{
		$info = $this->itemmaster->getStockLocInfo($id,$inv_id,$type); //echo '<pre>';print_r($info);exit;
		return view('body.itemmaster.itemlocinfo')
					->withNum($n)
					->withInfo($info);
	}
	
	public function getcnLocInfo($id,$n,$cst_id,$inv_id=null)
	{
		$info = $this->itemmaster->getStockcnLocInfo($id,$inv_id,$cst_id);
		return view('body.itemmaster.itemcnlocinfo')
					->withNum($n)
					->withInfo($info);
	}
	
	public function viewLocInfo($id,$n)
	{
		$info = $this->itemmaster->getStockLocInfo($id,$inv_id=null,$type=null);
		return view('body.itemmaster.viewlocinfo')
					->withNum($n)
					->withInfo($info);
	}
	
	
	public function StockLocation($item_id)
	{
		$data = array();
		$items = $this->itemmaster->StockLocation($item_id);//echo '<pre>';print_r($items);exit;
		return view('body.itemenquiry.stockloc')
					->withItems($items)
					->withData($data);
	}
	
	public function getItemLocation()
	{
		
		$data = array();
		$items = $this->makeTree( json_decode($this->itemmaster->getItemsinLocation()) );
		
		//echo '<pre>';print_r($items);exit;
		return view('body.itemmaster.itemlocation')
					->withItems($items)
					->withData($data);
	}
	
	protected function makeTree($result)
	{
		$childs = array();
		foreach($result as $item)
			$childs[$item->location_id][] = $item;
		
		return $childs;
	}
	
	public function ajaxSearch($type, Request $request) {
	//echo '<pre>';print_r($request);exit;
		
		$search = $request->get('term','');
		$products = $this->itemmaster->getItemmasterSearch($search, $type);
		//echo '<pre>';print_r($products);exit;
		//$products=DB::table('itemmaster')->where('description','LIKE','%'.$query.'%')->get();
		
		$data=array();
        foreach ($products as $product) {
            //$data[]=array('value'=>($type=='C')?$product->item_code.' - '.$product->description:$product->description, 'id'=>$product->id, 'code' => $product->item_code, 'name'=>($type=='C')?$product->description:$product->item_code, 'unit'=>$product->unit,'vat'=>$product->vat);
			$data[]=array('value'=>($type=='C')?$product->item_code:$product->description, 'id'=>$product->id, 'name'=>($type=='C')?$product->description:$product->item_code, 'unit'=>$product->unit,'vat'=>$product->vat);
        }
        if(count($data))
             return $data;
        else
            return ['value'=>'No Result Found','id'=>''];
	}
	
	public function ajaxSearch2($type, Request $request) {
		
		$search = $request->get('term','');
		$products = $this->itemmaster->getItemmasterSearch($search, $type);
		
		//$products=DB::table('itemmaster')->where('description','LIKE','%'.$query.'%')->get();
		
		$data=array();
        foreach ($products as $product) {
            $data[]=array('value'=>($type=='C')?$product->description:$product->description, 'id'=>$product->id, 'name'=>($type=='C')?$product->description:$product->item_code, 'code' => $product->item_code, 'unit'=>$product->unit,'vat'=>$product->vat);
        }
        if(count($data))
             return $data;
        else
            return ['value'=>'No Result Found','id'=>''];
	}
	

	public function gerBarcode($id)
	{
		$data = array();
		$item = $this->itemmaster->find($id);
		return view('body.itemmaster.barcode')
					->withItem($item)
					->withData($data);
	}
		
	public function item_apiadd()
	{
		$item = $this->itemmaster->addIteminAPI();
		//echo '<pre>';print_r($item);exit;
	}
	
	public function getPurchaseInfo($id)
	{
		$info = $this->itemmaster->getPurchaseInfo($id); //echo '<pre>';print_r($info);
		return view('body.itemmaster.itempurinfo')
					->withInfo($info);
	}
	
	public function getSalesInfo($id)
	{
		$info = $this->itemmaster->getSalesInfo($id); //echo '<pre>';print_r($info);
		return view('body.itemmaster.itempurinfo')
					->withInfo($info);
	}
	
	public function checkQuantity($id)
	{
		$result = DB::table('item_unit')->where('itemmaster_id', $id)->where('is_baseqty',1)->select('cur_quantity','min_quantity')->first();
		if($result)
			return json_encode($result);
		else
			echo '';
	}
	
	
	public function getUnit()
	{
		$data = array();
		$data = $this->itemmaster->getallUnits();
		if($data) {
			$unit = array();
			foreach($data as $val) {
				$unit[$val->id] = $val->unit_name;
			}
			return $unit;
		} else 
			return null;
		
	}
	
	public function getSedeInfo($id,$n=null)
	{
		$items = $this->itemmaster->getSupersedeInfo($id);//print_r($items);exit;
		return view('body.itemmaster.itemsedeinfo')
					->withNum($n)
					->withItems($items);
	}
	
	public function getLocqty($id)
	{
		/* $itemLogs = $this->sumLoc($this->groupItemLoc($this->groupLoc( $this->itemmaster->ItemLogLocation($id) )));
		foreach($itemLogs as $loc => $rows) {
		   foreach($rows as $row) {
			DB::table('item_location')->where('location_id',$loc)->where('item_id',$row['item_id'])->where('unit_id',$row['unit'])
					->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->update(['quantity' => $row['quantity'] ]);
		   }
		} */
		
		$items = $this->itemmaster->getLocQuantity($id); //echo '<pre>';print_r($items);exit;
		return view('body.itemmaster.locqty')
					->withItems($items);
	}
	
	public function getCustSalesInfo($id,$uid)
	{
		$info = $this->itemmaster->getCustSalesInfo($id,$uid); //echo '<pre>';print_r($info);
		return view('body.itemmaster.itempurinfo')
					->withInfo($info);
	}
	
	public function getDesc()
	{
		//print_r(Input::all());
		$result = DB::table('itemmaster')->where('id',Input::get('id'))->select('other_info')->first();
		if($result)
			echo $result->other_info; //json_encode($result);
		else
			echo '';
	}
		
	public function getMargin($id,$cost)
	{
		$info = $this->itemmaster->getMargine($id,$cost);
		echo '&nbsp; &nbsp; <b>Margine: '.$info.'</b>';
	}
	
	public function addRawMaterial() {
		
		DB::table('mfg_items')
				->insert([
					'item_id'	=> Input::get('item_id'),
					'subitem_id'	=> Input::get('sitem_id'),
					'quantity'	=> Input::get('qty'),
					'unit_price'	=> Input::get('cost'),
					'total'	=> Input::get('qty') * Input::get('cost')
					]);
		
	}
	
	public function getAsmItem($num,$mod=null)
	{
		$data = array();
		$itemmaster = [];
		$arrData = $this->getGroupCategory();
		$vats = $this->vatmaster->activeVatMasterList();
		
		return view('body.itemmaster.asmitem')
					->withItems($itemmaster)
					->withNum($num)
					->withUnits($arrData['units'])
					->withVats($vats)
					->withMod($mod)
					->withData($data);
	}
	
	public function ajaxgetAsmItem(Request $request)
	{
		$columns = array( 
                            0 => 'itemmaster.id', 
                            1 => 'item_code',
                            2 => 'description',
                            3 => 'quantity',
                            4 => 'req_qty'
                        );
		$mod = $request->input('mod');	
		$totalData = $this->itemmaster->getActiveItemListCount($mod);
            
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
		$search = (empty($request->input('search.value')))?null:$request->input('search.value');
		
		$items = $this->itemmaster->getActiveItemList($mod, 'get', $start, $limit, $order, $dir, $search);
		
		if($search)
			$totalFiltered =  $this->itemmaster->getActiveItemList($mod, 'count', $start, $limit, $order, $dir, $search);
		
		$data = array();
        if(!empty($items))
        {
            foreach ($items as $post)
            {
                //$nestedData['id'] = $post->id;
				$opt =  $post->id;
				$nestedData['opt'] = "<input type='checkbox' name='itmid[]' id='chk_{$opt}' class='chk-itmid' value='{$opt}'/>";
                $nestedData['item_code'] = $post->item_code;
				$nestedData['description'] = $post->description;
				$nestedData['quantity'] = $post->cur_quantity;
				$nestedData['req_qty'] = "<input type='texbox' size='5' id='rqty_{$opt}' name='qtyreq[]' class='req-qty' value='' disabled />";		
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
	
	
	public function getAssemblyItems($items,$qty,$no)
	{
		$data = array();
		$arr = explode(',', $items);
		$qtyar = explode(',', $qty);
		$items = DB::table('itemmaster')->whereIn('id',$arr)->select('id','item_code','description')->get();
		//echo '<pre>';print_r($items);exit;
		return view('body.itemmaster.viewasmitem')
					->withItems($items)
					->withQty($qtyar)
					->withNo($no)
					->withData($data);
	}

	public function getConLocation($num,$cust_id,$item_id,$row=null)
	{
		$data = array();
		
		return view('body.itemmaster.conlocations')
					->withNum($num)
					->withRow($row)
					->withCust($cust_id)
					->withItemid($item_id)
					->withData($data);
	}
	
	public function ajaxgetConLocation(Request $request)
	{
		$columns = array( 
                            0 => 'location.id', 
                            1 => 'code',
                            2 => 'name',
                            3 => 'stock',
                            4 => 'req_qty'
                        );
		$rowid = $request->input('rowid');	
		$custid = $request->input('custid');
		$itemid = $request->input('itemid');

		$totalData = $this->itemmaster->getConLocListCount($custid,$itemid);
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
		$search = (empty($request->input('search.value')))?null:$request->input('search.value');
		
		
		$items = $this->itemmaster->getConLocList('get', $start, $limit, $order, $dir, $search, $custid, $itemid);
		
		if($search)
			$totalFiltered =  $this->itemmaster->getConLocList('count', $start, $limit, $order, $dir, $search, $custid, $itemid);
		//echo '<pre>';print_r($items);exit;
		
		$qtyData = [];
		if($rowid!='') {
			$idarr = explode('-',$rowid);
			if($idarr[1]!='') {
				if($idarr[0]=='DO') {
					$qtyData = DB::table('customer_do_item')->where('id',$idarr[1])->select('conloc_id','conloc_qty')->first();
				} else if($idarr[0]=='SI') {
					$qtyData = DB::table('sales_invoice_item')->where('id',$idarr[1])->select('conloc_id','conloc_qty')->first();
				}
			}
		}
		
		$data = array();
        if(!empty($items))
        {
			$qtyarr = ($qtyData)?explode(',',$qtyData->conloc_qty):'';
			$locarr = ($qtyData)?explode(',',$qtyData->conloc_id):''; $i=0;
            foreach ($items as $k => $post)
            {	
				if(!empty($locarr) && in_array($post->id, $locarr)) {
					 $qval = isset($qtyarr[$i])?$qtyarr[$i]:'';
					 $i++;
					 $chk = 'checked';
				} else {
					$qval = $chk = '';
				}
				$opt =  $post->id;
				$nestedData['opt'] = "<input type='checkbox' name='lcid[]' class='chk-locid' value='{$opt}' />";
                $nestedData['code'] = $post->code;
				$nestedData['name'] = $post->name;
				$nestedData['stock'] = $post->quantity;
				$nestedData['req_qty'] = "<input type='texbox' size='5' id='clqty_{$opt}' name='qtyreq[]' autocomplete='off' class='req-qty' value='{$qval}'/>";		
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


	public function viewConlocItems($loc,$qty,$t,$no,$itmid)
	{
		$data = array();
		$arr = explode(',', $loc);
		$qtyar = explode(',', $qty);
		if($t=='DO') {
			$items = DB::table('con_location')
				->join('location','location.id','=','con_location.location_id')
				->whereIn('con_location.location_id',$arr)
				->where('con_location.invoice_id',$itmid)
				->where('con_location.is_do',1)
				->select('con_location.id','location.code','location.name','location.id AS location_id')->get();

		} else if($t=='SI') {
			$items = DB::table('con_location')
				->join('location','location.id','=','con_location.location_id')
				->whereIn('con_location.location_id',$arr)
				->where('con_location.invoice_id',$itmid)
				->where('con_location.is_do',0)
				->select('con_location.id','location.code','location.name','location.id AS location_id')->get();

		} else if($t=='SR') {
			$items = DB::table('con_location_sr')
				->join('location','location.id','=','con_location_sr.location_id')
				->whereIn('con_location_sr.location_id',$arr)
				->where('con_location_sr.invoice_id',$itmid)
				->select('con_location_sr.id','location.code','location.name','location.id AS location_id')->get();

		}
		//echo '<pre>';print_r($items);exit; viewasmitem
		return view('body.itemmaster.viewconloc')
					->withItems($items)
					->withQty($qtyar)
					->withNo($no)
					->withData($data);
	}
	
	protected function groupLoc($result)
	{
		$childs = array();
		foreach($result as $item)
			$childs[$item['location_id']][] = $item;

			return $childs;
	}
	
	protected function groupItemLoc($results)
	{
		$childs = array();
		foreach($results as $k => $result)
			foreach($result as $item)
			$childs[$k][$item['item_id']][] = $item;

		return $childs;
	}
	
	protected function sumLoc($results)
	{
		$arrSummarry = array();
		foreach($results as $result)
		{
			foreach($result as $rows) {
				$in = $out = $quantity = 0;
				foreach($rows as $row) {
					$item_id = $row['id'];
					$itemcode = $row['item_code'];
					$description = $row['description'];
					$unit = $row['unit_id']; 
					$cost_avg = $row['cost_avg'];
					$opn_cost = $row['pur_cost']; //todo
					$opn_quantity = 0;//$row['opn_quantity']; todo
					$location_id = $row['location_id'];
					$lcode = $row['code'];
					$lname = $row['name'];
					if($row['trtype']=='0')
						$out += $row['quantity'];
					else
						$in += $row['quantity'];
					
				}
				$quantity = $in - $out;
				
				$total = $quantity * $cost_avg;
			
			$arrSummarry[$location_id][] = ['itemcode' => $itemcode, 
							  'unit' => $unit,
							  'quantity' => $quantity, 
							  'cost_avg' => $cost_avg,
							  'description' => $description,
							  'opn_cost' => $opn_cost,
							  'total' => $total,
							  'opn_quantity' => $opn_quantity,
							  'code' => $lcode,
							  'name' => $lname,
							  'item_id' => $item_id
							  ];
			}
				
			

		}
		return $arrSummarry;
	}
}
