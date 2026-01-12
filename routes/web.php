<?php
use Illuminate\Support\Facades\Route;

// ================================================
//  Migrated from Laravel 5.2 routes.php
// ================================================

use \App\Http\Controllers\ReportController;
use \App\Http\Controllers\SettingsController;
use \App\Http\Controllers\DashboardController;
use \App\Http\Controllers\HomeController;
use \App\Http\Controllers\RoleController;
use \App\Http\Controllers\UserController;
use \App\Http\Controllers\CategoryController;
use \App\Http\Controllers\SubcategoryController;
use \App\Http\Controllers\CompanyController;
use \App\Http\Controllers\SysparameterController;
use \App\Http\Controllers\GroupController;
use \App\Http\Controllers\SubgroupController;
use \App\Http\Controllers\UnitController;
use \App\Http\Controllers\ItemmasterController;
use \App\Http\Controllers\ItemenquiryController;
use \App\Http\Controllers\BankController;
use \App\Http\Controllers\CurrencyController;
use \App\Http\Controllers\AreaController;
use \App\Http\Controllers\LocationController;
use \App\Http\Controllers\CountryController;
use \App\Http\Controllers\DepartmentController;
use \App\Http\Controllers\TermsController;
use \App\Http\Controllers\TemplateNameController;
use \App\Http\Controllers\ConsigneeController;
use \App\Http\Controllers\ShipperController;
use \App\Http\Controllers\CollectionTypeController;
use \App\Http\Controllers\DeliveryTypeController;
use \App\Http\Controllers\CargoUnitController;
use \App\Http\Controllers\CargoVehicleController;
use \App\Http\Controllers\CargoDestinationTypeController;
use \App\Http\Controllers\CargoSalesmanController;
use \App\Http\Controllers\CargoStatusController;
use \App\Http\Controllers\SalesmanController;
use \App\Http\Controllers\JobmasterController;
use \App\Http\Controllers\AccategoryController;
use \App\Http\Controllers\AcgroupController;
use \App\Http\Controllers\AccountMasterController;
use \App\Http\Controllers\AccountEnquiryController;
use \App\Http\Controllers\AccountSettingController;
use \App\Http\Controllers\HeaderFooterController;
use \App\Http\Controllers\PurchaseOrderController;
use \App\Http\Controllers\DivisionController;
use \App\Http\Controllers\EmployeeController;
use \App\Http\Controllers\EmployeeCategoryController;
use \App\Http\Controllers\VatMasterController;
use \App\Http\Controllers\QuotationController;
use \App\Http\Controllers\SuppliersDOController;
use \App\Http\Controllers\PurchaseInvoiceController;
use \App\Http\Controllers\PurchaseReturnController;
use \App\Http\Controllers\QuotationSalesController;
use \App\Http\Controllers\SalesRentalController;
use \App\Http\Controllers\SalesOrderController;
use \App\Http\Controllers\CustomersDOController;
use \App\Http\Controllers\SalesInvoiceController;
use \App\Http\Controllers\SalesReturnController;
use \App\Http\Controllers\CustomerReceiptController;
use \App\Http\Controllers\OtherReceiptController;
use \App\Http\Controllers\SupplierPaymentController;
use \App\Http\Controllers\ContraVoucherController;
use \App\Http\Controllers\OtherPaymentController;
use \App\Http\Controllers\PdcReceivedController;
use \App\Http\Controllers\PdcIssuedController;
use \App\Http\Controllers\JournalController;
use \App\Http\Controllers\VoucherwiseReportController;
use \App\Http\Controllers\TrialBalanceController;
use \App\Http\Controllers\CashInhandController;
use \App\Http\Controllers\ProfitLossController;
use \App\Http\Controllers\BalanceSheetController;
use \App\Http\Controllers\PurchaseReportController;
use \App\Http\Controllers\SalesReportController;
use \App\Http\Controllers\QuantityReportController;
use \App\Http\Controllers\StockLedgerController;
use \App\Http\Controllers\StockTransactionController;
use \App\Http\Controllers\StockMovementController;
use \App\Http\Controllers\BatchReportController;
use \App\Http\Controllers\ProfitAnalysisController;
use \App\Http\Controllers\DailyReportController;
use \App\Http\Controllers\DailySettingController;
use \App\Http\Controllers\VatReportController;
use \App\Http\Controllers\GoodsIssuedController;
use \App\Http\Controllers\GoodsReturnController;
use \App\Http\Controllers\JobReportController;
use \App\Http\Controllers\UtilityController;
use \App\Http\Controllers\PettyCashController;
use \App\Http\Controllers\AdvanceSetController;
use \App\Http\Controllers\LogDetailsController;
use \App\Http\Controllers\PurchaseVoucherController;
use \App\Http\Controllers\SalesVoucherController;
use \App\Http\Controllers\LedgerMomentsController;
use \App\Http\Controllers\PdcReportController;
use \App\Http\Controllers\DocumentReportController;
use \App\Http\Controllers\BackupController;
use \App\Http\Controllers\OtherAccountSettingController;
use \App\Http\Controllers\VoucherNumbersController;
use \App\Http\Controllers\PermissionController;
use \App\Http\Controllers\YearendingController;
use \App\Http\Controllers\YearendingquickController;
use \App\Http\Controllers\JobEstimateController;
use \App\Http\Controllers\JobOrderController;
use \App\Http\Controllers\JobInvoiceController;
use \App\Http\Controllers\LocationTransferController;
use \App\Http\Controllers\PackageMasterController;
use \App\Http\Controllers\StockTransferinController;
use \App\Http\Controllers\StockTransferoutController;
use \App\Http\Controllers\ImportDataController;
use \App\Http\Controllers\FormManagerController;
use \App\Http\Controllers\CreditNoteController;
use \App\Http\Controllers\DebitNoteController;
use \App\Http\Controllers\QuotationRentalController;
use \App\Http\Controllers\WageEntryController;
use \App\Http\Controllers\PaySlipController;
use \App\Http\Controllers\TimesheetReportController;
use \App\Http\Controllers\PayrollReportController;
use \App\Http\Controllers\WpsReportController;
use \App\Http\Controllers\DesignController;
use \App\Http\Controllers\UpdateController;
use \App\Http\Controllers\VehicleController;
use \App\Http\Controllers\JobtypeController;
use \App\Http\Controllers\DocumentMasterController;
use \App\Http\Controllers\DoctypeController;
use \App\Http\Controllers\AssetsIssuedController;
use \App\Http\Controllers\CustomerEnquiryController;
use \App\Http\Controllers\CustomerLeadsController;
use \App\Http\Controllers\LeadsController;
use \App\Http\Controllers\ProductionController;
use \App\Http\Controllers\AccountsReportController;
use \App\Http\Controllers\DataRemoveController;
use \App\Http\Controllers\TransactionListController;
use \App\Http\Controllers\EmployeeDocumentController;
use \App\Http\Controllers\EmployeeReportController;
use \App\Http\Controllers\SetReportController;
use \App\Http\Controllers\ManufactureController;
use \App\Http\Controllers\MaterialRequisitionController;
use \App\Http\Controllers\MsCustomerController;
use \App\Http\Controllers\MsLocationController;
use \App\Http\Controllers\MsTechnicianController;
use \App\Http\Controllers\MsAreaController;
use \App\Http\Controllers\MsWorktypeController;
use \App\Http\Controllers\MsJobmasterController;
use \App\Http\Controllers\MsWorkorderController;
use \App\Http\Controllers\MsReportsController;
use \App\Http\Controllers\MsWorkenquiryController;
use \App\Http\Controllers\PurchaseSplitController;
use \App\Http\Controllers\PurchaseSplitReturnController;
use \App\Http\Controllers\SalesSplitController;
use \App\Http\Controllers\SalesSplitReturnController;
use \App\Http\Controllers\ToolsController;
use \App\Http\Controllers\BuildingMasterController;
use \App\Http\Controllers\FlatMasterController;
use \App\Http\Controllers\ContractBuildingController;
use \App\Http\Controllers\ContractExpiryController;
use \App\Http\Controllers\ManualJournalController;
use \App\Http\Controllers\RealestateStatementController;
use \App\Http\Controllers\DurationMasterController;
use \App\Http\Controllers\ChequeDetailsController;
use \App\Http\Controllers\MachineController;
use \App\Http\Controllers\PaperController;
use \App\Http\Controllers\ContractTypeController;
use \App\Http\Controllers\ContractController;
use \App\Http\Controllers\ContraTypeController;
use \App\Http\Controllers\CargoReceiptController;
use \App\Http\Controllers\CargoWayBillController;
use \App\Http\Controllers\CargoDespatchBillController;
use \App\Http\Controllers\PurchaseRentalController;
use \App\Http\Controllers\RentalSalesController;
use \App\Http\Controllers\RentalDriverController;
use \App\Http\Controllers\RentalSupplierDriverController;
use \App\Http\Controllers\RentalCustomerDriverController;
use \App\Http\Controllers\RentalReportController;
use \App\Http\Controllers\SalesOrderBookingController;
use \App\Http\Controllers\ProformaInvoiceController;
use \App\Http\Controllers\ItemTemplateController;
use \App\Http\Controllers\JobProcessReportController;
use \App\Http\Controllers\TenantMasterController;
use \App\Http\Controllers\TenantEnquiryController;
use \App\Http\Controllers\CrmTemplateController;
use \App\Http\Controllers\DashboardDesignController;
use \App\Http\Controllers\ContractConnectionController;
use \App\Http\Controllers\CreditNoteJournalController;
use \App\Http\Controllers\PackingListController;
use \App\Http\Controllers\TrialBalanceController2;
use \App\Http\Controllers\BalanceSheetController2;
use \App\Http\Controllers\ProfitLossController2;
use \App\Http\Controllers\ManageController;
use \App\Http\Controllers\SignController;
use \App\Http\Controllers\MyOrderController;
use \App\Http\Controllers\ApicallController;
use App\Http\Controllers\Auth\LoginController;

// ===== Migrated Routes =====

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.customer_receipt/printgrp/
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::middleware(['web'])->group(function () {

    // ALWAYS redirect root to login
    Route::get('/', function () {
        return redirect('/login');
    });

    // SHOW LOGIN PAGE
    Route::get('/login', [LoginController::class, 'showLoginForm'])
        ->name('login')
        ->middleware('guest');

    // LOGIN SUBMIT
    Route::post('/login', [LoginController::class, 'login'])
        ->name('login.submit')
        ->middleware('guest');

    // LOGOUT
	Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


	
	//STIMULSOFT ROUTES....
Route::get('/report', [ReportController::class, 'showReport']);
	Route::any('/stimulsoftV2/handler', function () {
		require public_path('stimulsoftV2/handler.php');
	});

	Route::get('/stimulsoftV2/license', function () {
		// Only allow authenticated users (optional)
		// if (!auth()->check()) abort(403);

		$path = public_path('stimulsoftV2/license.key');
		if (!file_exists($path)) abort(404);

		return response()->file($path, [
			'Content-Type' => 'text/plain',
			// Force no caching if needed
			'Cache-Control' => 'no-store'
		]);
	});

	Route::get('/designer', function () {
		$view = DB::table('design_view')->where('id',1)->first();
		return view('body.designer')->withView($view->view_name); // Blade file we’ll create
	});

	Route::post('/designer/save', function (\Illuminate\Http\Request $request) {
		$json = $request->input('report');

		// Save to file (be careful with paths)
		file_put_contents(public_path('stimulsoftV2/reports/your-report.mrt'), $json);

		return response()->json(['success' => true]);
	});

	//END HERE STIMULSOFT...
	
Route::get('/settings/dbswitch', [SettingsController::class, 'index']);
Route::post('/settings/login', [SettingsController::class, 'SubmitLogin']);
Route::post('/settings/submit_dbswitch', [SettingsController::class, 'SubmitDbswitch']);
	
	Route::get('/config-cache', function() {
     $exitCode = Artisan::call('config:cache');
	 //return redirect('/login');
     return 'Config cache cleared';
	}); 
 
// TODO: Laravel 10 removed Route::auth() → use Breeze/Jetstream.
	
Route::group(['middleware' => ['auth']], function() { 
Route::get('/index', [DashboardController::class, 'index']);
Route::get('/dashboard', [DashboardController::class, 'index']);
Route::get('/index', [DashboardController::class, 'index']);
Route::get('/dashboard/get_pdcr_alert', [DashboardController::class, 'getPdcrAlert']);
Route::get('/dashboard/get_pdci_alert', [DashboardController::class, 'getPdciAlert']);
Route::get('/dashboard/get_docexpinfo', [DashboardController::class, 'getDocExpinfo']);
Route::get('/dashboard/get_vehicle_alert', [DashboardController::class, 'getVehiExpinfo']);
Route::get('/dashboard/get_crminfo', [DashboardController::class, 'getCrmInfo']);
Route::get('/dashboard/get_settings', [DashboardController::class, 'getSettings']);
Route::get('/dashboard/setting_update', [DashboardController::class, 'settingUpdate']);
Route::get('/dashboard/setting_delete/{id}', [DashboardController::class, 'settingDelete']);
Route::get('/dashboard/get_advsettings', [DashboardController::class, 'getadvSettings']);
Route::get('/dashboard/advsetting_update', [DashboardController::class, 'advsettingUpdate']);
Route::get('/dashboard/advsetting_delete/{id}', [DashboardController::class, 'advsettingDelete']);
Route::get('/dashboard/get_contract_expiry', [DashboardController::class, 'getContractExpiry']);
Route::get('/dashboard/pv_approve/{id}', [DashboardController::class, 'pvApprove']);
Route::get('/dashboard/approval_alert', [DashboardController::class, 'approvalAlert']);
		
Route::get('/home', [HomeController::class, 'index']);
		
		Route::resource('users','UserController');
		
Route::get('roles',		  ['as'=>'roles.index', 'uses'=>RoleController::class.'@index', 'middleware' => ['permission:role-list|role-create|role-edit|role-delete']]);
Route::get('roles/create',['as'=>'roles.create','uses'=>RoleController::class.'@create','middleware' => ['permission:role-create']]);
Route::post('roles/create',['as'=>'roles.store','uses'=>RoleController::class.'@store','middleware' => ['permission:role-create']]);
Route::get('roles/{id}',['as'=>'roles.show','uses'=>RoleController::class.'@show']);
Route::get('roles/{id}/edit',['as'=>'roles.edit','uses'=>RoleController::class.'@edit','middleware' => ['permission:role-edit']]);
Route::patch('roles/{id}',['as'=>'roles.update','uses'=>RoleController::class.'@update','middleware' => ['permission:role-edit']]);
Route::delete('roles/{id}',['as'=>'roles.destroy','uses'=>RoleController::class.'@destroy','middleware' => ['permission:role-delete']]);
		
Route::get('/users/{id}/delete', [UserController::class, 'deluser']);
Route::get('/users/{id}/password', [UserController::class, 'changePassword']);
Route::post('/users/{id}/password', [UserController::class, 'updatePassword']);
Route::post('/users/{id}/edit', [UserController::class, 'update']);

Route::get('/category', [CategoryController::class, 'index']);
Route::get('/category/add', [CategoryController::class, 'add']);
Route::post('/category/save', [CategoryController::class, 'save']);
Route::get('/category/edit/{id}', [CategoryController::class, 'edit']);
Route::post('/category/update/{id}', [CategoryController::class, 'update']);
Route::get('/category/delete/{id}', [CategoryController::class, 'destroy']);
Route::post('/category/group_delete', ['uses' => CategoryController::class.'@destroyGroup']);
Route::get('/category/checkname', [CategoryController::class, 'checkname']);

Route::get('/subcategory', [SubcategoryController::class, 'index']);
Route::get('/subcategory/add', [SubcategoryController::class, 'add']);
Route::post('/subcategory/save', [SubcategoryController::class, 'save']);
Route::get('/subcategory/edit/{id}', [SubcategoryController::class, 'edit']);
Route::post('/subcategory/update/{id}', [SubcategoryController::class, 'update']);
Route::get('/subcategory/delete/{id}', [SubcategoryController::class, 'destroy']);
Route::post('/subcategory/group_delete', ['uses' => SubcategoryController::class.'@destroyGroup']);
Route::get('/subcategory/checkname', [SubcategoryController::class, 'checkname']);

Route::get('/company', [CompanyController::class, 'index']);
Route::post('/company/update/{id}', [CompanyController::class, 'update']);


Route::get('/sysparameter', [SysparameterController::class, 'index']);
Route::post('/sysparameter/para1_update/{id}', [SysparameterController::class, 'para1_update']);
Route::post('/sysparameter/para2_update', [SysparameterController::class, 'para2_update']);
Route::post('/sysparameter/para3_update', [SysparameterController::class, 'para3_update']);
Route::post('/sysparameter/para4_update/{n}', [SysparameterController::class, 'para4_update']);
Route::post('/sysparameter/para5_update', [SysparameterController::class, 'para5_update']);
		

Route::get('/group', [GroupController::class, 'index']);
Route::get('/group/add', [GroupController::class, 'add']);
Route::post('/group/save', [GroupController::class, 'save']);
Route::get('/group/edit/{id}', [GroupController::class, 'edit']);
Route::post('/group/update/{id}', [GroupController::class, 'update']);
Route::get('/group/delete/{id}', [GroupController::class, 'destroy']);
Route::post('/group/group_delete', ['uses' => GroupController::class.'@destroyGroup']);
Route::get('/group/checkname', [GroupController::class, 'checkname']);

Route::get('/subgroup', [SubgroupController::class, 'index']);
Route::get('/subgroup/add', [SubgroupController::class, 'add']);
Route::post('/subgroup/save', [SubgroupController::class, 'save']);
Route::get('/subgroup/edit/{id}', [SubgroupController::class, 'edit']);
Route::post('/subgroup/update/{id}', [SubgroupController::class, 'update']);
Route::get('/subgroup/delete/{id}', [SubgroupController::class, 'destroy']);
Route::post('/subgroup/group_delete', ['uses' => SubgroupController::class.'@destroyGroup']);
Route::get('/subgroup/checkname', [SubgroupController::class, 'checkname']);

Route::get('/unit', [UnitController::class, 'index']);
Route::get('/unit/add', [UnitController::class, 'add']);
Route::post('/unit/save', [UnitController::class, 'save']);
Route::get('/unit/edit/{id}', [UnitController::class, 'edit']);
Route::post('/unit/update/{id}', [UnitController::class, 'update']);
Route::get('/unit/delete/{id}', [UnitController::class, 'destroy']);
Route::post('/unit/group_delete', ['uses' => UnitController::class.'@destroyGroup']);
Route::get('/unit/checkname', [UnitController::class, 'checkname']);

Route::get('/itemmaster', [ItemmasterController::class, 'index']);
Route::get('/itemmaster/add', [ItemmasterController::class, 'add']);
Route::post('/itemmaster/save', [ItemmasterController::class, 'save']);
Route::get('/itemmaster/edit/{id}', [ItemmasterController::class, 'edit']);
Route::post('/itemmaster/update/{id}', [ItemmasterController::class, 'update']);
Route::get('/itemmaster/delete/{id}', [ItemmasterController::class, 'destroy']);
Route::get('/itemmaster/checkcode', [ItemmasterController::class, 'checkcode']);
Route::get('/itemmaster/checkdesc', [ItemmasterController::class, 'checkdesc']);
Route::get('/itemmaster/get_vat/{id}', [ItemmasterController::class, 'getVat']);
Route::get('/itemmaster/get_vat/{id}/{it}', [ItemmasterController::class, 'getVat']);
Route::get('/itemmaster/get_info/{id}', [ItemmasterController::class, 'getInfo']);
Route::get('/itemmaster/get_purchase_cost', [ItemmasterController::class, 'getPurchaseCost']);
Route::get('/itemmaster/get_sale_cost', [ItemmasterController::class, 'getSaleCost']);
Route::get('/itemmaster/item_data/{n}', [ItemmasterController::class, 'getItem']);
Route::get('/itemmaster/get_cost_avg', [ItemmasterController::class, 'getCostAvg']);
Route::get('/itemmaster/get_cost_sale', [ItemmasterController::class, 'getCostSale']);
Route::get('/itemmaster/ajax_create', [ItemmasterController::class, 'ajaxSave']);
Route::get('/itemmaster/get_locinfo/{id}', [ItemmasterController::class, 'getLocInfo']);
Route::get('/itemmaster/get_locinfo/{id}/{n}', [ItemmasterController::class, 'getLocInfo']);
Route::get('/itemmaster/get_locinfo/{id}/{n}/{piid}/{iv}', [ItemmasterController::class, 'getLocInfo']);
Route::get('/itemmaster/stock_location/{id}', [ItemmasterController::class, 'StockLocation']);
Route::get('/itemmaster/item_location', [ItemmasterController::class, 'getItemLocation']);
Route::get('/itemmaster/ajax_search/{c}', [ItemmasterController::class, 'ajaxSearch']);
Route::get('/itemmaster/ajax_search2/{c}', [ItemmasterController::class, 'ajaxSearch2']);
Route::get('/itemmaster/get_dimn_info/{id}', [ItemmasterController::class, 'getDimnInfo']);
Route::get('/itemmaster/batch-view', [ItemmasterController::class, 'getBatchView']);
Route::get('/itemmaster/check_batchno', [ItemmasterController::class, 'checkBatchno']);
Route::get('/itemmaster/batch-get', [ItemmasterController::class, 'getBatchGet']);
Route::get('/itemmaster/get_batch/{id}', [ItemmasterController::class, 'getBatch']);

			
Route::post('/itemmaster/paging', [ItemmasterController::class, 'ajaxPaging']);
Route::get('/itemmaster/item_load/{code}', [ItemmasterController::class, 'getItemLoad']);
Route::get('/itemmaster/barcode/{id}', [ItemmasterController::class, 'gerBarcode']);
Route::get('/itemmaster/get_purchase_info/{id}', [ItemmasterController::class, 'getPurchaseInfo']);
Route::get('/itemmaster/get_sales_info/{id}', [ItemmasterController::class, 'getSalesInfo']);
Route::get('/itemmaster/checkqty/{id}', [ItemmasterController::class, 'checkQuantity']);
Route::get('/itemmaster/item_data/{n}/{m}', [ItemmasterController::class, 'getItem']);
Route::get('/itemmaster/getunit', [ItemmasterController::class, 'getUnit']);
Route::post('/itemmaster/item_data', [ItemmasterController::class, 'ajaxgetItem']);
Route::get('/itemmaster/get_custsales_info/{id}/{uid}', [ItemmasterController::class, 'getCustSalesInfo']);
Route::get('/itemmaster/get_desc', [ItemmasterController::class, 'getDesc']);
Route::get('/itemmaster/get_sale_cost_avg', [ItemmasterController::class, 'getSaleCostAvg']);
Route::get('/itemmaster/get_sedeinfo/{id}/{n}', [ItemmasterController::class, 'getSedeInfo']);
Route::get('/itemmaster/get_sedeinfo/{id}', [ItemmasterController::class, 'getSedeInfo']);
Route::get('/itemmaster/get_item_cost_avg', [ItemmasterController::class, 'getItemCostAvg']);
Route::get('/itemmaster/get_cost_avg_mfg', [ItemmasterController::class, 'getCostAvgMfg']);
Route::get('/itemmaster/get_rawmat/{id}', [ItemmasterController::class, 'getRawmat']);
Route::get('/itemmaster/item_data_rw', [ItemmasterController::class, 'getItemRw']);
Route::get('/itemmaster/get_margin/{id}/{cost}', [ItemmasterController::class, 'getMargin']);
Route::get('/itemmaster/rmitem_data/{n}', [ItemmasterController::class, 'getItemRm']);
Route::post('/itemmaster/add_rawmaterial', [ItemmasterController::class, 'addRawMaterial']);
Route::get('/itemmaster/asmitem_data/{n}', [ItemmasterController::class, 'getAsmItem']);
Route::post('/itemmaster/asmitem_data', [ItemmasterController::class, 'ajaxgetAsmItem']);
Route::get('/itemmaster/get_assembly_items/{id}/{qty}/{n}', [ItemmasterController::class, 'getAssemblyItems']);
Route::get('/itemmaster/sts', [ItemmasterController::class, 'status_chk']);
Route::get('/itemmaster/view_locinfo/{id}/{n}', [ItemmasterController::class, 'viewLocInfo']);
Route::get('/itemmaster/get_locqty/{id}', [ItemmasterController::class, 'getLocqty']);
Route::get('/itemmaster/get_langtrans', [ItemmasterController::class, 'getLangTrans']);
		
Route::get('/itemmaster/get_cnlocinfo/{id}', [ItemmasterController::class, 'getcnLocInfo']);
Route::get('/itemmaster/get_cnlocinfo/{id}/{n}/{g}', [ItemmasterController::class, 'getcnLocInfo']);
Route::get('/itemmaster/get_cnlocinfo/{id}/{n}/{piid}/{iv}', [ItemmasterController::class, 'getcnLocInfo']);

Route::get('/itemmaster/conloc_data/{n}/{c}/{i}', [ItemmasterController::class, 'getConLocation']);
Route::get('/itemmaster/conloc_data/{n}/{c}/{i}/{r}', [ItemmasterController::class, 'getConLocation']);
Route::post('/itemmaster/conloc_data', [ItemmasterController::class, 'ajaxgetConLocation']);
Route::get('/itemmaster/view_conloc_items/{id}/{qty}/{n}/{t}/{r}', [ItemmasterController::class, 'viewConlocItems']);

Route::get('/itemmaster/get_rawmatwe/{id}', [ItemmasterController::class, 'getRawmatWe']);

		
//Route::get('/itemmaster/sts', [ItemmasterController::class, 'status_chk']);

		
Route::get('/itemenquiry', [ItemenquiryController::class, 'index']);
Route::post('/itemenquiry/details', [ItemenquiryController::class, 'details']);
Route::get('/itemenquiry/print/{id}/{n}', [ItemenquiryController::class, 'printItem']);
Route::post('/itemenquiry/paging', [ItemenquiryController::class, 'ajaxPaging']);
Route::get('/itemenquiry/get_custsupp', [ItemenquiryController::class, 'getCustomerSupplier']);
Route::post('/itemenquiry/export', [ItemenquiryController::class, 'dataExport']);
Route::get('/itemenquiry/openform/{id}', [ItemenquiryController::class, 'getForm']);
		

Route::get('/bank', [BankController::class, 'index']);
Route::get('/bank/add', [BankController::class, 'add']);
Route::post('/bank/save', [BankController::class, 'save']);
Route::get('/bank/edit/{id}', [BankController::class, 'edit']);
Route::post('/bank/update/{id}', [BankController::class, 'update']);
Route::get('/bank/delete/{id}', [BankController::class, 'destroy']);
Route::get('/bank/checkcode', [BankController::class, 'checkcode']);
Route::get('/bank/checkname', [BankController::class, 'checkname']);
		
Route::get('/currency', [CurrencyController::class, 'index']);
Route::get('/currency/add', [CurrencyController::class, 'add']);
Route::post('/currency/save', [CurrencyController::class, 'save']);
Route::get('/currency/edit/{id}', [CurrencyController::class, 'edit']);
Route::post('/currency/update/{id}', [CurrencyController::class, 'update']);
Route::get('/currency/delete/{id}', [CurrencyController::class, 'destroy']);
Route::get('/currency/checkcode', [CurrencyController::class, 'checkcode']);
Route::get('/currency/checkname', [CurrencyController::class, 'checkname']);
Route::get('/currency/getrate/{id}', [CurrencyController::class, 'ajax_getrate']);
Route::get('/currency/getcurrency/{id}', [CurrencyController::class, 'getCurrency']);
		
Route::get('/area', [AreaController::class, 'index']);
Route::get('/area/add', [AreaController::class, 'add']);
Route::post('/area/save', [AreaController::class, 'save']);
Route::get('/area/edit/{id}', [AreaController::class, 'edit']);
Route::post('/area/update/{id}', [AreaController::class, 'update']);
Route::get('/area/delete/{id}', [AreaController::class, 'destroy']);
Route::get('/area/checkcode', [AreaController::class, 'checkcode']);
Route::get('/area/checkname', [AreaController::class, 'checkname']);

Route::get('/location', [LocationController::class, 'index']);
Route::get('/location/add', [LocationController::class, 'add']);
Route::post('/location/save', [LocationController::class, 'save']);
Route::get('/location/edit/{id}', [LocationController::class, 'edit']);
Route::post('/location/update/{id}', [LocationController::class, 'update']);
Route::get('/location/delete/{id}', [LocationController::class, 'destroy']);
Route::get('/location/checkcode', [LocationController::class, 'checkcode']);
Route::get('/location/checkname', [LocationController::class, 'checkname']);
Route::get('/location/get_loc/{id}', [LocationController::class, 'getLocation']);
Route::get('/location/get_loc', [LocationController::class, 'getLocation']);
Route::get('/location/bin_data/{n}', [LocationController::class, 'getBin']);
Route::get('/location/ajax_create', [LocationController::class, 'ajaxSave']);

Route::get('/country', [CountryController::class, 'index']);
Route::get('/country/add', [CountryController::class, 'add']);
Route::post('/country/save', [CountryController::class, 'save']);
Route::get('/country/edit/{id}', [CountryController::class, 'edit']);
Route::post('/country/update/{id}', [CountryController::class, 'update']);
Route::get('/country/delete/{id}', [CountryController::class, 'destroy']);
Route::get('/country/checkcode', [CountryController::class, 'checkcode']);
Route::get('/country/checkname', [CountryController::class, 'checkname']);

Route::get('/department', [DepartmentController::class, 'index']);
Route::get('/department/add', [DepartmentController::class, 'add']);
Route::post('/department/save', [DepartmentController::class, 'save']);
Route::get('/department/edit/{id}', [DepartmentController::class, 'edit']);
Route::post('/department/update/{id}', [DepartmentController::class, 'update']);
Route::get('/department/delete/{id}', [DepartmentController::class, 'destroy']);
Route::get('/department/checkcode', [DepartmentController::class, 'checkcode']);
Route::get('/department/checkname', [DepartmentController::class, 'checkname']);

Route::get('/terms', [TermsController::class, 'index']);
Route::get('/terms/add', [TermsController::class, 'add']);
Route::post('/terms/save', [TermsController::class, 'save']);
Route::get('/terms/edit/{id}', [TermsController::class, 'edit']);
Route::post('/terms/update/{id}', [TermsController::class, 'update']);
Route::get('/terms/delete/{id}', [TermsController::class, 'destroy']);
Route::get('/terms/checkcode', [TermsController::class, 'checkcode']);
        
Route::get('/template_name', [TemplateNameController::class, 'index']);
Route::get('/template_name/add', [TemplateNameController::class, 'add']);
Route::post('/template_name/save', [TemplateNameController::class, 'save']);
Route::get('/template_name/edit/{id}', [TemplateNameController::class, 'edit']);
Route::post('/template_name/update/{id}', [TemplateNameController::class, 'update']);
Route::get('/template_name/delete/{id}', [TemplateNameController::class, 'destroy']);

Route::get('/consignee', [ConsigneeController::class, 'index']);
Route::get('/consignee/add', [ConsigneeController::class, 'add']);
Route::post('/consignee/save', [ConsigneeController::class, 'save']);
Route::get('/consignee/edit/{id}', [ConsigneeController::class, 'edit']);
Route::post('/consignee/update/{id}', [ConsigneeController::class, 'update']);
Route::get('/consignee/delete/{id}', [ConsigneeController::class, 'destroy']);
Route::get('/consignee/ajax_create', [ConsigneeController::class, 'ajaxSave']);
Route::get('/consignee/checkname', [ConsigneeController::class, 'checkname']);
Route::get('/consignee/checkphone', [ConsigneeController::class, 'checkphone']);
Route::get('/consignee/checkphone1', [ConsigneeController::class, 'checkphone']);


Route::get('/shipper', [ShipperController::class, 'index']);
Route::get('/shipper/add', [ShipperController::class, 'add']);
Route::post('/shipper/save', [ShipperController::class, 'save']);
Route::get('/shipper/edit/{id}', [ShipperController::class, 'edit']);
Route::post('/shipper/update/{id}', [ShipperController::class, 'update']);
Route::get('/shipper/delete/{id}', [ShipperController::class, 'destroy']);
Route::get('/shipper/ajax_create', [ShipperController::class, 'ajaxSave']);
Route::get('/shipper/checkname', [ShipperController::class, 'checkname']);
Route::get('/shipper/checkphone', [ShipperController::class, 'checkphone']);

Route::get('/collection_type', [CollectionTypeController::class, 'index']);
Route::get('/collection_type/add', [CollectionTypeController::class, 'add']);
Route::post('/collection_type/save', [CollectionTypeController::class, 'save']);
Route::get('/collection_type/edit/{id}', [CollectionTypeController::class, 'edit']);
Route::post('/collection_type/update/{id}', [CollectionTypeController::class, 'update']);
Route::get('/collection_type/delete/{id}', [CollectionTypeController::class, 'destroy']);
Route::get('/collection_type/checkcode', [CollectionTypeController::class, 'checkcode']);

Route::get('/delivery_type', [DeliveryTypeController::class, 'index']);
Route::get('/delivery_type/add', [DeliveryTypeController::class, 'add']);
Route::post('/delivery_type/save', [DeliveryTypeController::class, 'save']);
Route::get('/delivery_type/edit/{id}', [DeliveryTypeController::class, 'edit']);
Route::post('/delivery_type/update/{id}', [DeliveryTypeController::class, 'update']);
Route::get('/delivery_type/delete/{id}', [DeliveryTypeController::class, 'destroy']);
Route::get('/delivery_type/checkcode', [DeliveryTypeController::class, 'checkcode']);


Route::get('/cargounit', [CargoUnitController::class, 'index']);
Route::get('/cargounit/add', [CargoUnitController::class, 'add']);
Route::post('/cargounit/save', [CargoUnitController::class, 'save']);
Route::get('/cargounit/edit/{id}', [CargoUnitController::class, 'edit']);
Route::post('/cargounit/update/{id}', [CargoUnitController::class, 'update']);
Route::get('/cargounit/delete/{id}', [CargoUnitController::class, 'destroy']);
Route::post('/cargounit/group_delete', ['uses' => CargoUnitController::class.'@destroyGroup']);
Route::get('/cargounit/checkname', [CargoUnitController::class, 'checkname']);

Route::get('/cargo_vehicle', [CargoVehicleController::class, 'index']);
Route::get('/cargo_vehicle/add', [CargoVehicleController::class, 'add']);
Route::post('/cargo_vehicle/save', [CargoVehicleController::class, 'save']);
Route::get('/cargo_vehicle/edit/{id}', [CargoVehicleController::class, 'edit']);
Route::post('/cargo_vehicle/update/{id}', [CargoVehicleController::class, 'update']);
Route::get('/cargo_vehicle/delete/{id}', [CargoVehicleController::class, 'destroy']);
Route::get('/cargo_vehicle/checknumber', [CargoVehicleController::class, 'checknumber']);
Route::get('/cargo_vehicle/ajax_create', [CargoVehicleController::class, 'ajaxSave']);
        
Route::get('/destination_type', [CargoDestinationTypeController::class, 'index']);
Route::get('/destination_type/add', [CargoDestinationTypeController::class, 'add']);
Route::post('/destination_type/save', [CargoDestinationTypeController::class, 'save']);
Route::get('/destination_type/edit/{id}', [CargoDestinationTypeController::class, 'edit']);
Route::post('/destination_type/update/{id}', [CargoDestinationTypeController::class, 'update']);
Route::get('/destination_type/delete/{id}', [CargoDestinationTypeController::class, 'destroy']);
Route::get('/destination_type/checkname', [CargoDestinationTypeController::class, 'checkname']);

Route::get('/cargo_salesman', [CargoSalesmanController::class, 'index']);
Route::get('/cargo_salesman/add', [CargoSalesmanController::class, 'add']);
Route::post('/cargo_salesman/save', [CargoSalesmanController::class, 'save']);
Route::get('/cargo_salesman/edit/{id}', [CargoSalesmanController::class, 'edit']);
Route::post('/cargo_salesman/update/{id}', [CargoSalesmanController::class, 'update']);
Route::get('/cargo_salesman/delete/{id}', [CargoSalesmanController::class, 'destroy']);
Route::get('/cargo_salesman/checkid', [CargoSalesmanController::class, 'checkid']);
Route::get('/cargo_salesman/checkname', [CargoSalesmanController::class, 'checkname']);
Route::get('/cargo_salesman/ajax_create', [CargoSalesmanController::class, 'ajaxSave']);

Route::get('/cargo_status', [CargoStatusController::class, 'index']);
Route::get('/cargo_status/add', [CargoStatusController::class, 'add']);
Route::post('/cargo_status/save', [CargoStatusController::class, 'save']);
Route::get('/cargo_status/edit/{id}', [CargoStatusController::class, 'edit']);
Route::post('/cargo_status/update/{id}', [CargoStatusController::class, 'update']);
Route::get('/cargo_status/delete/{id}', [CargoStatusController::class, 'destroy']);
		


Route::get('/salesman', [SalesmanController::class, 'index']);
Route::get('/salesman/add', [SalesmanController::class, 'add']);
Route::post('/salesman/save', [SalesmanController::class, 'save']);
Route::get('/salesman/edit/{id}', [SalesmanController::class, 'edit']);
Route::post('/salesman/update/{id}', [SalesmanController::class, 'update']);
Route::get('/salesman/delete/{id}', [SalesmanController::class, 'destroy']);
Route::get('/salesman/checkid', [SalesmanController::class, 'checkid']);
Route::get('/salesman/checkname', [SalesmanController::class, 'checkname']);
Route::get('/salesman/ajax_create', [SalesmanController::class, 'ajaxSave']);

Route::get('/jobmaster', [JobmasterController::class, 'index']);
Route::get('/jobmaster/add', [JobmasterController::class, 'add']);
Route::post('/jobmaster/save', [JobmasterController::class, 'save']);
	       
Route::get('/jobmaster/edit/{id}', [JobmasterController::class, 'edit']);
Route::get('/jobmaster/history/{id}', [JobmasterController::class, 'VehiHistory']);
Route::post('/jobmaster/update/{id}', [JobmasterController::class, 'update']);
Route::get('/jobmaster/delete/{id}', [JobmasterController::class, 'destroy']);
Route::get('/jobmaster/checkcode', [JobmasterController::class, 'checkcode']);
Route::get('/jobmaster/checkname', [JobmasterController::class, 'checkname']);
Route::get('/jobmaster/job_data', [JobmasterController::class, 'getJobdata']);
Route::get('/jobmaster/jobb_data', [JobmasterController::class, 'getJobbdata']);
Route::get('/jobmaster/job_assign/{n}', [JobmasterController::class, 'getJobAssign']);
Route::post('/jobmaster/paging', [JobmasterController::class, 'ajaxPaging']);
Route::get('/jobmaster/ajax_create', [JobmasterController::class, 'ajaxSave']);
Route::get('/jobmaster/job_data/{n}', [JobmasterController::class, 'getJobdata']);
Route::get('/jobmaster/budget', [JobmasterController::class, 'budget']);
Route::post('/jobmaster/budgsave', [JobmasterController::class, 'budgetsave']);
Route::get('/jobmaster/viewbudget', [JobmasterController::class, 'viewbudget']);
Route::get('/jobmaster/budget_detail/{id}', [JobmasterController::class, 'budgetdetail']);
Route::post('/jobmaster/updatebudget/{id}', [JobmasterController::class, 'updatebudget']);
Route::get('/jobmaster/print/{id}', [JobmasterController::class, 'getPrint']);
Route::get('/jobmaster/buddelete/{id}',[JobmasterController::class, 'budDestroy']);
Route::get('/jobmaster/preprint/{id}', [JobmasterController::class, 'getprePrint']);

Route::get('/accategory', [AccategoryController::class, 'index']);
Route::get('/accategory/add', [AccategoryController::class, 'add']);
Route::post('/accategory/save', [AccategoryController::class, 'save']);
Route::get('/accategory/edit/{id}', [AccategoryController::class, 'edit']);
Route::post('/accategory/update/{id}', [AccategoryController::class, 'update']);
Route::get('/accategory/delete/{id}', [AccategoryController::class, 'destroy']);
Route::get('/accategory/checkname', [AccategoryController::class, 'checkname']);
Route::get('/accategory/getcategory/{id}', [AccategoryController::class, 'ajax_getcategory']);
Route::get('/accategory/getparent/{id}', [AccategoryController::class, 'ajax_getParent']);
Route::post('/accategory/destroy', ['uses' => AccategoryController::class.'@destroyCate']);
//Route::post('/accategory/destroy', [AccategoryController::class, 'destroy']);


Route::get('/acgroup', [AcgroupController::class, 'index']);
Route::get('/acgroup/add', [AcgroupController::class, 'add']);
Route::post('/acgroup/save', [AcgroupController::class, 'save']);
Route::get('/acgroup/edit/{id}', [AcgroupController::class, 'edit']);
Route::post('/acgroup/update/{id}', [AcgroupController::class, 'update']);
Route::get('/acgroup/delete/{id}', [AcgroupController::class, 'destroy']);
Route::get('/acgroup/checkname', [AcgroupController::class, 'checkname']);
Route::get('/acgroup/checkcode', [AcgroupController::class, 'checkcode']);
Route::get('/acgroup/getgroup/{id}', [AcgroupController::class, 'ajax_getgroup']);
Route::get('/acgroup/getcode/{id}', [AcgroupController::class, 'ajax_getcode']);
//Route::get('/acgroup/getcat/{id}', [AcgroupController::class, 'ajax_getcategory']);
		
Route::get('/account_master', [AccountMasterController::class, 'index']);
Route::get('/account_master/add', [AccountMasterController::class, 'add']);
Route::post('/account_master/save', [AccountMasterController::class, 'save']);
Route::get('/account_master/edit/{id}', [AccountMasterController::class, 'edit']);
Route::post('/account_master/update/{id}', [AccountMasterController::class, 'update']);
Route::get('/account_master/delete/{id}', [AccountMasterController::class, 'destroy']);
Route::get('/account_master/checkcode', [AccountMasterController::class, 'checkcode']);
Route::get('/account_master/checkdesc', [AccountMasterController::class, 'checkdesc']);
Route::get('/account_master/getcode/{id}', [AccountMasterController::class, 'ajax_getcode']);
Route::get('/account_master/view/{id}', [AccountMasterController::class, 'show']);
Route::get('/account_master/get_account/{code}', [AccountMasterController::class, 'getAccount']);
Route::get('/account_master/get_account_list/{code}', [AccountMasterController::class, 'getAccountList']);
Route::get('/account_master/get_account_list/{code}/{n}', [AccountMasterController::class, 'getAccountList']);
Route::get('/account_master/get_all_account/{no}', [AccountMasterController::class, 'getAllAccount']);
Route::get('/account_master/custom_account/{no}', [AccountMasterController::class, 'getCustomAccount']);
Route::get('/account_master/check_refno', [AccountMasterController::class, 'checkRefno']);
Route::get('/account_master/check_trndate', [AccountMasterController::class, 'checkTrndate']);
Route::get('/account_master/check_chequeno', [AccountMasterController::class, 'checkChequeno']);
Route::get('/account_master/get_accountbudg/{no}', [AccountMasterController::class, 'getbudgAccounts']);
Route::get('/account_master/get_accountbudginc/{no}', [AccountMasterController::class, 'getbudgincAccounts']);
Route::get('/account_master/get_accounts/{no}', [AccountMasterController::class, 'getAccounts']);
Route::get('/account_master/expenseac_data/{no}', [AccountMasterController::class, 'getExpenseac']);
Route::get('/account_master/get_account_all/{no}', [AccountMasterController::class, 'getAccountAll']);
Route::get('/account_master/get_account_all', [AccountMasterController::class, 'getAccountAll']);
Route::get('/account_master/ajax_create', [AccountMasterController::class, 'ajaxSave']);
Route::get('/account_master/ajax_create_acc', [AccountMasterController::class, 'ajaxSaveacc']);
Route::post('/account_master/paging', [AccountMasterController::class, 'ajaxPaging']);
Route::post('/account_master/cuspaging', [AccountMasterController::class, 'ajaxCusPaging']);
Route::post('/account_master/suppaging', [AccountMasterController::class, 'ajaxSupPaging']);
Route::get('/account_master/ajax_account', [AccountMasterController::class, 'getAjaxAccount']);
Route::get('/account_master/get_accounts/{no}/{dp}', [AccountMasterController::class, 'getAccounts']);
Route::get('/account_master/get_accounts', [AccountMasterController::class, 'getAccounts']);
Route::get('/account_master/checkname', [AccountMasterController::class, 'checkname']);
Route::post('/account_master/destroy',  [AccountMasterController::class, 'destroymaster']);
Route::post('/account_master/show_account',  [AccountMasterController::class, 'showAccount']);
Route::post('/account_master/hide_account',  [AccountMasterController::class, 'hideAccount']);
Route::get('/account_master/budget_entry', [AccountMasterController::class, 'budgetEntry']);
Route::post('/account_master/budget_entry', [AccountMasterController::class, 'budgetEntrySave']);
Route::get('/account_master/checkacno', [AccountMasterController::class, 'checkacno']);
		
			
Route::get('/account_enquiry', [AccountEnquiryController::class, 'index']);
Route::get('/account_enquiry/cus', [AccountEnquiryController::class, 'indexCus']);
Route::get('/account_enquiry/sup', [AccountEnquiryController::class, 'indexSup']);
Route::get('/account_enquiry/bank', [AccountEnquiryController::class, 'indexBank']);
Route::get('/account_enquiry/cash', [AccountEnquiryController::class, 'indexCash']);
Route::post('/account_enquiry/search_account', [AccountEnquiryController::class, 'searchAccount']);
Route::post('/account_enquiry/paging', [AccountEnquiryController::class, 'ajaxPaging']);
Route::post('/account_enquiry/cuspaging', [AccountEnquiryController::class, 'ajaxCusPaging']);
Route::post('/account_enquiry/suppaging', [AccountEnquiryController::class, 'ajaxSupPaging']);
Route::post('/account_enquiry/bankpaging', [AccountEnquiryController::class, 'ajaxBankPaging']);
Route::post('/account_enquiry/cashpaging', [AccountEnquiryController::class, 'ajaxCashPaging']);
Route::post('/account_enquiry/export', [AccountEnquiryController::class, 'dataExport']);
Route::get('/account_enquiry/address', [AccountEnquiryController::class, 'addressList']);
Route::post('/account_enquiry/search', [AccountEnquiryController::class, 'searchAddress']);
Route::post('/account_enquiry/address_export', [AccountEnquiryController::class, 'addressExport']);
Route::get('/account_enquiry/os_bills/{id}', [AccountEnquiryController::class, 'outStandingBills']);
Route::get('/account_enquiry/os_bills/{id}/{no}/{mod}/{rid}', [AccountEnquiryController::class, 'outStandingBills']);
Route::get('/account_enquiry/os_bills/{id}/{no}', [AccountEnquiryController::class, 'outStandingBills']);
//Route::get('/account_enquiry/os_bills/{id}/{no}/{mod}/{rid}', [AccountEnquiryController::class, 'outStandingBills']);
//Route::get('/account_enquiry/os_bills/{id}/{mod}/{no}/{ref}/{rid}', [AccountEnquiryController::class, 'outStandingBills']);
Route::get('/account_enquiry/reconciliation', [AccountEnquiryController::class, 'Reconciliation']);
Route::post('/account_enquiry/search_account_ar', [AccountEnquiryController::class, 'searchAccountAR']);
Route::post('/account_enquiry/save_reconciliation', [AccountEnquiryController::class, 'saveReconciliation']);
Route::post('/account_enquiry/send', [AccountEnquiryController::class, 'dataSend']);
Route::get('/account_enquiry/os_bills_adv/{id}', [AccountEnquiryController::class, 'outStandingBillsAdv']);
		
		
Route::get('/account_setting', [AccountSettingController::class, 'index']);
Route::get('/account_setting/add', [AccountSettingController::class, 'add']);
Route::post('/account_setting/save', [AccountSettingController::class, 'save']);
Route::get('/account_setting/checkname', [AccountSettingController::class, 'checkname']);
Route::get('/account_setting/delete/{id}/{type}', [AccountSettingController::class, 'destroy']);
Route::get('/account_setting/edit/{id}', [AccountSettingController::class, 'edit']);
Route::post('/account_setting/update/{id}', [AccountSettingController::class, 'update']);
Route::get('/account_setting/check-accounts', [AccountSettingController::class, 'checkAccounts']);
		
Route::get('/header_footer', [HeaderFooterController::class, 'index']);
Route::get('/header_footer/add', [HeaderFooterController::class, 'add']);
Route::post('/header_footer/save', [HeaderFooterController::class, 'save']);
Route::get('/header_footer/edit/{id}', [HeaderFooterController::class, 'edit']);
Route::post('/header_footer/update/{id}', [HeaderFooterController::class, 'update']);
Route::get('/header_footer/delete/{id}', [HeaderFooterController::class, 'destroy']);
Route::get('/header_footer/header_data', [HeaderFooterController::class, 'getHeader']);
Route::get('/header_footer/footer_data', [HeaderFooterController::class, 'getFooter']);
		
		
Route::get('/purchase_order', ['as'=>'purchase_order.index','uses'=>PurchaseOrderController::class.'@index','middleware' => ['permission:po-list|po-create|po-edit|po-delete']]);
Route::get('/purchase_order/add', ['as'=>'purchase_order.add','uses'=>PurchaseOrderController::class.'@add','middleware' => ['permission:po-create']]);
Route::post('/purchase_order/save', ['as' => 'purchase_order.save', 'uses' => PurchaseOrderController::class.'@save', 'middleware' => ['permission:po-create']]);
Route::get('/purchase_order/edit/{id}', ['as' => 'purchase_order.edit', 'uses' => PurchaseOrderController::class.'@edit', 'middleware' => ['permission:po-edit']]);
Route::post('/purchase_order/update/{id}', ['as' => 'purchase_order.update', 'uses' => PurchaseOrderController::class.'@update', 'middleware' => ['permission:po-edit']]);
Route::get('/purchase_order/viewonly/{id}', ['as' => 'purchase_order.viewonly', 'uses' => PurchaseOrderController::class.'@viewonly', 'middleware' => ['permission:po-view']]);
Route::get('/purchase_order/supplier_data', [PurchaseOrderController::class, 'getSupplier']);
Route::get('/purchase_order/item_data/{id}', [PurchaseOrderController::class, 'getItem']);
Route::get('/purchase_order/checkrefno', [PurchaseOrderController::class, 'checkRefNo']);
Route::get('/purchase_order/delete/{id}', ['as' => 'purchase_order.destroy', 'uses' => PurchaseOrderController::class.'@destroy', 'middleware' => ['permission:po-delete']]);
Route::get('/purchase_order/po_data', [PurchaseOrderController::class, 'getPO']);
Route::get('/purchase_order/po_data/{id}', [PurchaseOrderController::class, 'getPO']);
Route::get('/purchase_order/po_data/{id}/{n}', [PurchaseOrderController::class, 'getPO']);
Route::get('/purchase_order/po_datatrans/{id}/{n}', [PurchaseOrderController::class, 'getPOt']);
Route::get('/purchase_order/item_details/{id}', [PurchaseOrderController::class, 'getItemDetails']);
Route::get('/purchase_order/getunit/{id}', [PurchaseOrderController::class, 'getUnit']);
Route::get('/purchase_order/order_history/{id}', [PurchaseOrderController::class, 'getOrderHistory']);
Route::get('/purchase_order/print/{id}', ['as' => 'purchase_order.getPrint', 'uses' => PurchaseOrderController::class.'@getPrint', 'middleware' => ['permission:po-print']]);
Route::get('/purchase_order/supplier_data/{txt}', [PurchaseOrderController::class, 'getSupplier']);
Route::get('/purchase_order/report', [PurchaseOrderController::class, 'report']);
Route::post('/purchase_order/search', [PurchaseOrderController::class, 'getSearch']);
Route::get('/purchase_order/print/{id}/{fc}', ['as' => 'purchase_order.getPrint', 'uses' => PurchaseOrderController::class.'@getPrint', 'middleware' => ['permission:po-print']]);
Route::post('/purchase_order/export', ['as' => 'purchase_order.dataExport', 'uses' => PurchaseOrderController::class.'@dataExport', 'middleware' => ['permission:po-print']]);
Route::post('/purchase_order/export_po', [PurchaseOrderController::class, 'dataExportPo']);
Route::post('/purchase_order/paging', [PurchaseOrderController::class, 'ajaxPaging']);
Route::get('/purchase_order/mr_data/{n}', [PurchaseOrderController::class, 'getMR']);
Route::get('/purchase_order/add/{id}/{n}', [PurchaseOrderController::class, 'add']);
Route::get('/purchase_order/get_purchaseorder', [PurchaseOrderController::class, 'getPurchaseOrder']);
Route::get('/purchase_order/supplier_datadept/{did}', [PurchaseOrderController::class, 'getSupplierDept']);
Route::get('/purchase_order/printfc/{id}/{rid}', [PurchaseOrderController::class, 'getPrintFc']);
Route::get('/purchase_order/get_jobpo/{jobid}', [PurchaseOrderController::class, 'getJobPo']);
Route::get('/purchase_order/getunit', [PurchaseOrderController::class, 'getUnit']);
Route::get('/purchase_order/checkvchrno', [PurchaseOrderController::class, 'checkVchrNo']);
Route::get('/purchase_order/add-from-so/{id}/{n}', [PurchaseOrderController::class, 'addFromSo']);
Route::get('/purchase_order/views/{id}',[PurchaseOrderController::class, 'getViews']);
Route::get('/purchase_order/approve/{id}', [PurchaseOrderController::class, 'getApproval']);
Route::get('/purchase_order/settlement/{id}', [PurchaseOrderController::class, 'Settlement']);//April 2025
Route::get('/purchase_order/getjob/{id}', [PurchaseOrderController::class, 'getJob']);
Route::get('/purchase_order/refresh_po/{id}', [PurchaseOrderController::class, 'refreshPO']);
        
Route::post('/purchase_order/save_draft', [PurchaseOrderController::class, 'saveDraft']);//Draft
Route::get('/purchase_order/edit_draft/{id}', [PurchaseOrderController::class, 'editDraft']);
Route::post('/purchase_order/update_draft/{id}', [PurchaseOrderController::class, 'updateDraft']);
        
        

Route::get('/division', [DivisionController::class, 'index']);
Route::get('/division/add', [DivisionController::class, 'add']);
Route::post('/division/save', [DivisionController::class, 'save']);
Route::get('/division/edit/{id}', [DivisionController::class, 'edit']);
Route::post('/division/update/{id}', [DivisionController::class, 'update']);
Route::get('/division/delete/{id}', [DivisionController::class, 'destroy']);
Route::get('/division/checkcode', [DivisionController::class, 'checkcode']);
Route::get('/division/checkname', [DivisionController::class, 'checkname']);
		
		
Route::get('/employee', [EmployeeController::class, 'index']);
Route::get('/employee/add', [EmployeeController::class, 'add']);
Route::post('/employee/save', [EmployeeController::class, 'save']);
Route::get('/employee/edit/{id}', [EmployeeController::class, 'edit']);
Route::post('/employee/update/{id}', [EmployeeController::class, 'update']);
Route::get('/employee/delete/{id}', [EmployeeController::class, 'destroy']);
Route::get('/employee/checkcode', [EmployeeController::class, 'checkcode']);
Route::get('/employee/checkname', [EmployeeController::class, 'checkname']);
Route::get('/employee/employee_data', [EmployeeController::class, 'getEmployeedata']);
Route::get('/employee/get_employee/{id}/{n}/{y}/{m}', [EmployeeController::class, 'getEmployee']);
Route::get('/employee/get_empdata/{id}', [EmployeeController::class, 'getEmpData']);
Route::post('/employee/ajax_save', [EmployeeController::class, 'ajaxSave']);
Route::post('/employee/upload', [EmployeeController::class, 'uploadSubmit']);
Route::post('/employee/pupload', [EmployeeController::class, 'puploadSubmit']);
Route::post('/employee/vupload', [EmployeeController::class, 'vuploadSubmit']);
Route::post('/employee/lupload', [EmployeeController::class, 'luploadSubmit']);
Route::post('/employee/hupload', [EmployeeController::class, 'huploadSubmit']);
Route::post('/employee/fupload', [EmployeeController::class, 'fuploadSubmit']);
Route::post('/employee/iupload', [EmployeeController::class, 'iuploadSubmit']);
Route::post('/employee/meupload', [EmployeeController::class, 'meuploadSubmit']);
Route::get('/employee/get_expinfo', [EmployeeController::class, 'getExpinfo']);
Route::get('/employee/view/{id}', [EmployeeController::class, 'show']);
Route::get('/employee/leave/{id}', [EmployeeController::class, 'leave']);
Route::post('/employee/save_leave', [EmployeeController::class, 'saveLeave']);
Route::get('/employee/rejoin/{id}', [EmployeeController::class, 'rejoin']);
Route::post('/employee/save_rejoin', [EmployeeController::class, 'saveRejoin']);
Route::get('/employee/resign/{id}', [EmployeeController::class, 'resign']);
Route::post('/employee/save_resign', [EmployeeController::class, 'saveResign']);
Route::get('/employee/rejoin-undo/{id}', [EmployeeController::class, 'rejoinUndo']);
Route::post('/employee/paging', [EmployeeController::class, 'ajaxPaging']);
Route::get('/employee/payrise/{id}', [EmployeeController::class, 'payrise']);
Route::post('/employee/payrise/update', [EmployeeController::class, 'payriseUpdate']);
		
		
Route::get('/emp_category', [EmployeeCategoryController::class, 'index']);
Route::get('/emp_category/add', [EmployeeCategoryController::class, 'add']);
Route::post('/emp_category/save', [EmployeeCategoryController::class, 'save']);
Route::get('/emp_category/edit/{id}', [EmployeeCategoryController::class, 'edit']);
Route::post('/emp_category/update/{id}', [EmployeeCategoryController::class, 'update']);
Route::get('/emp_category/delete/{id}', [EmployeeCategoryController::class, 'destroy']);
Route::post('/emp_category/group_delete', ['uses' => EmployeeCategoryController::class.'@destroyGroup']);
Route::get('/emp_category/checkname', [EmployeeCategoryController::class, 'checkname']);
		
		
		
Route::get('/vat_master', [VatMasterController::class, 'index']);
Route::get('/vat_master/add', [VatMasterController::class, 'add']);
Route::post('/vat_master/save', [VatMasterController::class, 'save']);
Route::get('/vat_master/edit/{id}', [VatMasterController::class, 'edit']);
Route::post('/vat_master/update/{id}', [VatMasterController::class, 'update']);
Route::get('/vat_master/delete/{id}', [VatMasterController::class, 'destroy']);
Route::get('/vat_master/checkcode', [VatMasterController::class, 'checkcode']);
Route::get('/vat_master/checkname', [VatMasterController::class, 'checkname']);
		
Route::get('/quotation', [QuotationController::class, 'index']);
Route::get('/quotation/add', [QuotationController::class, 'add']);
Route::get('/quotation/add/{id}/{n}',[QuotationController::class, 'add']);
Route::post('/quotation/save', [QuotationController::class, 'save']);
Route::get('/quotation/edit/{id}', [QuotationController::class, 'edit']);
Route::post('/quotation/update/{id}', [QuotationController::class, 'update']);
Route::get('/quotation/viewonly/{id}', [QuotationController::class, 'viewonly']);
Route::get('/quotation/supplier_data', [QuotationController::class, 'getSupplier']);
Route::get('/quotation/item_data/{id}', [QuotationController::class, 'getItem']);
Route::get('/quotation/item_details/{id}', [QuotationController::class, 'getItemDetails']);
Route::get('/quotation/checkrefno', [QuotationController::class, 'checkRefNo']);
Route::get('/quotation/delete/{id}', [QuotationController::class, 'destroy']);
Route::get('/quotation/print/{id}', [QuotationController::class, 'print']);
Route::post('/quotation/paging', [QuotationController::class, 'ajaxPaging']);
Route::get('/quotation/print/{id}', [QuotationController::class, 'getPrint']);
Route::post('/quotation/search', [QuotationController::class, 'getSearch']);
Route::get('/quotation/print/{id}/{fc}', [QuotationController::class, 'getPrint']);
Route::post('/quotation/export', [QuotationController::class, 'dataExport']);
Route::get('/quotation/get_quotations', [QuotationController::class, 'getQuotations']);
Route::get('/quotation/checkvchrno', [QuotationController::class, 'checkVchrNo']);
Route::post('/quotation/import', [QuotationController::class, 'getImport']);
		
		
		
		
Route::get('/suppliers_do', [SuppliersDOController::class, 'index']);
Route::get('/suppliers_do/add', [SuppliersDOController::class, 'add']);
Route::post('/suppliers_do/save/{id}', [SuppliersDOController::class, 'save']);
Route::post('/suppliers_do/save', [SuppliersDOController::class, 'save']);
Route::get('/suppliers_do/edit/{id}', [SuppliersDOController::class, 'edit']);
Route::post('/suppliers_do/update/{id}', [SuppliersDOController::class, 'update']);
Route::get('/suppliers_do/viewonly/{id}', [SuppliersDOController::class, 'viewonly']);
Route::get('/suppliers_do/add/{id}', [SuppliersDOController::class, 'add']);
Route::get('/suppliers_do/supplier_data', [SuppliersDOController::class, 'getSupplier']);
Route::get('/suppliers_do/item_data/{id}', [SuppliersDOController::class, 'getItem']);
Route::get('/suppliers_do/checkrefno', [SuppliersDOController::class, 'checkRefNo']);
Route::get('/suppliers_do/delete/{id}', [SuppliersDOController::class, 'destroy']);
Route::get('/suppliers_do/sdo_data', [SuppliersDOController::class, 'getSDO']);
Route::get('/suppliers_do/sdo_data/{id}', [SuppliersDOController::class, 'getSDO']);
Route::get('/suppliers_do/sdo_data/{id}/{n}', [SuppliersDOController::class, 'getSDO']);
Route::get('/suppliers_do/checkvchrno', [SuppliersDOController::class, 'checkVchrNo']);
Route::get('/suppliers_do/checkrefno', [SuppliersDOController::class, 'checkRefNo']);
Route::get('/suppliers_do/print/{id}/{rid}', [SuppliersDOController::class, 'getPrint']);
Route::get('/suppliers_do/add/{id}/{n}', [SuppliersDOController::class, 'add']);
Route::get('/suppliers_do/item_details/{id}', [SuppliersDOController::class, 'getItemDetails']);
Route::get('/suppliers_do/get_supplierdo', [SuppliersDOController::class, 'getSupplierDo']);
Route::post('/suppliers_do/search', [SuppliersDOController::class, 'getSearch']);
Route::post('/suppliers_do/export', [SuppliersDOController::class, 'dataExport']);
Route::get('/suppliers_do/getjob/{id}', [SuppliersDOController::class, 'getJob']);
Route::get('/suppliers_do/refresh_sdo/{id}', [SuppliersDOController::class, 'refreshSDO']);
		
		
Route::get('/purchase_invoice', ['as' => 'purchase_invoice.index', 'uses' => PurchaseInvoiceController::class.'@index', 'middleware' => ['permission:pi-list|pi-create|pi-edit|pi-delete']]);
Route::get('/purchase_invoice/add', ['as'=>'purchase_invoice.add','uses'=>PurchaseInvoiceController::class.'@add','middleware' => ['permission:pi-create']]);
Route::post('/purchase_invoice/save/{id}', ['as' => 'purchase_invoice.save', 'uses' => PurchaseInvoiceController::class.'@save', 'middleware' => ['permission:pi-create']] );
Route::post('/purchase_invoice/save', ['as' => 'purchase_invoice.save', 'uses' => PurchaseInvoiceController::class.'@save', 'middleware' => ['permission:pi-create']] );
Route::get('/purchase_invoice/edit/{id}', ['as' => 'purchase_invoice.edit', 'uses' => PurchaseInvoiceController::class.'@edit', 'middleware' => ['permission:pi-edit']]);
Route::post('/purchase_invoice/update/{id}', ['as' => 'purchase_invoice.update', 'uses' => PurchaseInvoiceController::class.'@update', 'middleware' => ['permission:pi-edit']]);
Route::get('/purchase_invoice/viewonly/{id}', ['as' => 'purchase_invoice.viewonly', 'uses' => PurchaseInvoiceController::class.'@viewonly', 'middleware' => ['permission:pi-view']]);
Route::get('/purchase_invoice/add/{id}/{n}', ['as'=>'purchase_invoice.add','uses'=>PurchaseInvoiceController::class.'@add','middleware' => ['permission:pi-create']]);
Route::post('/purchase_invoice/set_session', [PurchaseInvoiceController::class, 'setSessionVal']);
Route::get('/purchase_invoice/supplier_data', [PurchaseInvoiceController::class, 'getSupplier']);
Route::get('/purchase_invoice/checkrefno', [PurchaseInvoiceController::class, 'checkRefNo']);
Route::get('/purchase_invoice/delete/{id}', ['as' => 'purchase_invoice.destroy', 'uses' => PurchaseInvoiceController::class.'@destroy', 'middleware' => ['permission:pi-delete']]);
Route::get('/purchase_invoice/getvoucher/{id}', [PurchaseInvoiceController::class, 'getVoucher']);
Route::get('/purchase_invoice/account_data/{id}', [PurchaseInvoiceController::class, 'getAccount']);
Route::get('/purchase_invoice/account_data/{id}/{cr}', [PurchaseInvoiceController::class, 'getAccount']);
Route::get('/purchase_invoice/pi_data', [PurchaseInvoiceController::class, 'getPI']);
Route::get('/purchase_invoice/check_invoice', [PurchaseInvoiceController::class, 'checkInvoice']);
Route::get('/purchase_invoice/supplier_data/{no}', [PurchaseInvoiceController::class, 'getSupplier']);
Route::get('/purchase_invoice/get_invoice/{id}', [PurchaseInvoiceController::class, 'getInvoiceBySupplier']);
Route::get('/purchase_invoice/print/{id}', [PurchaseInvoiceController::class, 'getPrint']);
Route::get('/purchase_invoice/get_invoice/{id}/{n}', [PurchaseInvoiceController::class, 'getInvoiceBySupplier']);
Route::get('/purchase_invoice/order_history/{id}', [PurchaseInvoiceController::class, 'getOrderHistory']);
Route::get('/purchase_invoice/checkvchrno', [PurchaseInvoiceController::class, 'checkVchrNo']);
Route::get('/purchase_invoice/get_invoiceset/{id}', [PurchaseInvoiceController::class, 'getInvoiceSetBySupplier']);
Route::get('/purchase_invoice/print/{id}/{rid}', [PurchaseInvoiceController::class, 'getPrint']);
Route::post('/purchase_invoice/search', [PurchaseInvoiceController::class, 'getSearch']);
Route::post('/purchase_invoice/export', [PurchaseInvoiceController::class, 'dataExport']);
Route::post('/purchase_invoice/paging', [PurchaseInvoiceController::class, 'ajaxPaging']);
Route::get('/purchase_invoice/get_invoice/{id}/{n}/{val}/{rid}', [PurchaseInvoiceController::class, 'getInvoiceBySupplier']);//ED12
Route::get('/purchase_invoice/get_invoice/{id}/{n}/{pvid}', [PurchaseInvoiceController::class, 'getInvoiceBySupplierEdit']);//ED12
Route::post('/purchase_invoice/import', [PurchaseInvoiceController::class, 'getImport']);
Route::post('/purchase_invoice/export_po', [PurchaseInvoiceController::class, 'dataExportPo']);
Route::get('/purchase_invoice/item_details/{id}', [PurchaseInvoiceController::class, 'getItemDetails']);
Route::get('/purchase_invoice/getdeptvoucher/{id}', [PurchaseInvoiceController::class, 'getDeptVoucher']);
Route::get('/purchase_invoice/pi_data/{did}', [PurchaseInvoiceController::class, 'getPI']);
Route::get('/purchase_invoice/supplier_datadpt/{dpt}', [PurchaseInvoiceController::class, 'getSupplierDpt']);
Route::get('/purchase_invoice/printfc/{id}/{rid}', [PurchaseInvoiceController::class, 'getPrintFc']);
Route::get('/purchase_invoice/getcustomer', [PurchaseInvoiceController::class, 'getCustomer']);
Route::get('/purchase_invoice/getitems', [PurchaseInvoiceController::class, 'getItems']);
Route::get('/purchase_invoice/get_purchaseinvoice', [PurchaseInvoiceController::class, 'getPurchaseInvoice']);
Route::get('/purchase_invoice/getjob/{id}', [PurchaseInvoiceController::class, 'getJob']);

		
Route::get('/purchase_return', [PurchaseReturnController::class, 'index']);
Route::get('/purchase_return/add', [PurchaseReturnController::class, 'add']);
Route::post('/purchase_return/save/{id}', [PurchaseReturnController::class, 'save']);
Route::post('/purchase_return/save', [PurchaseReturnController::class, 'save']);
Route::get('/purchase_return/edit/{id}', [PurchaseReturnController::class, 'edit']);
Route::get('/purchase_return/add/{id}', [PurchaseReturnController::class, 'add']);
Route::get('/purchase_return/delete/{id}', [PurchaseReturnController::class, 'destroy']);
Route::get('/purchase_return/checkrefno', [PurchaseReturnController::class, 'checkRefNo']);
Route::get('/purchase_return/set_session', [PurchaseReturnController::class, 'setSessionVal']);
Route::get('/purchase_return/print/{id}', [PurchaseReturnController::class, 'getPrint']);
Route::get('/purchase_return/checkvchrno', [PurchaseReturnController::class, 'checkVchrNo']);
Route::post('/purchase_return/search', [PurchaseReturnController::class, 'getSearch']);
Route::post('/purchase_return/export', [PurchaseReturnController::class, 'dataExport']);
Route::post('/purchase_return/update/{id}', [PurchaseReturnController::class, 'update']);
Route::get('/purchase_return/viewonly/{id}', [PurchaseReturnController::class, 'viewonly']);
Route::get('/purchase_return/print/{id}/{fc}', [PurchaseReturnController::class, 'getPrint']);
Route::get('/purchase_return/getvoucher/{id}', [PurchaseReturnController::class, 'getVoucher']);
Route::post('/purchase_return/paging', [PurchaseReturnController::class, 'ajaxPaging']);
Route::get('/purchase_return/getcustomer', [PurchaseReturnController::class, 'getCustomer']);
Route::get('/purchase_return/getitems', [PurchaseReturnController::class, 'getItems']);
Route::get('/purchase_return/getdeptvoucher/{id}', [PurchaseReturnController::class, 'getDeptVoucher']);
Route::get('/purchase_return/getjob/{id}', [PurchaseReturnController::class, 'getJob']);
		
		
Route::get('/quotation_sales', ['as' => 'quotation_sales.index', 'uses' => QuotationSalesController::class.'@index', 'middleware' => ['permission:pi-list|qs-create|qs-edit|qs-delete']]);
Route::get('/quotation_sales/add', ['as'=>'quotation_sales.add','uses'=>QuotationSalesController::class.'@add','middleware' => ['permission:qs-create']]);
Route::get('/quotation_sales/add/{id}/{n}', ['as'=>'quotation_sales.add','uses'=>QuotationSalesController::class.'@add','middleware' => ['permission:qs-create']]);
Route::post('/quotation_sales/save', ['as' => 'quotation_sales.save', 'uses' => QuotationSalesController::class.'@save', 'middleware' => ['permission:qs-create']] );
Route::get('/quotation_sales/edit/{id}', ['as' => 'quotation_sales.edit', 'uses' => QuotationSalesController::class.'@edit', 'middleware' => ['permission:qs-edit']]);
Route::post('/quotation_sales/update/{id}', ['as' => 'quotation_sales.update', 'uses' => QuotationSalesController::class.'@update', 'middleware' => ['permission:qs-edit']]);
Route::get('/quotation_sales/viewonly/{id}', ['as' => 'quotation_sales.viewonly', 'uses' => QuotationSalesController::class.'@viewonly', 'middleware' => ['permission:qs-view']]);
Route::get('/quotation_sales/customer_data', [QuotationSalesController::class, 'getCustomer']);
Route::get('/quotation_sales/salesman_data', [QuotationSalesController::class, 'getSalesman']);
Route::get('/quotation_sales/item_data/{id}', [QuotationSalesController::class, 'getItem']);
Route::get('/quotation_sales/checkrefno', [QuotationSalesController::class, 'checkRefNo']);
Route::get('/quotation_sales/delete/{id}', ['as' => 'quotation_sales.destroy', 'uses' => QuotationSalesController::class.'@destroy', 'middleware' => ['permission:qs-delete']]);
Route::get('/quotation_sales/get_quotation/{id}/{url}', [QuotationSalesController::class, 'getQuotation']);
Route::get('/quotation_sales/item_details/{id}', [QuotationSalesController::class, 'getItemDetails']);
Route::get('/quotation_sales/print/{id}', ['as' => 'quotation_sales.getPrint', 'uses' => QuotationSalesController::class.'@getPrint', 'middleware' => ['permission:qs-print']]);
Route::post('/quotation_sales/search', [QuotationSalesController::class, 'getSearch']);
Route::get('/quotation_sales/print/{id}/{fc}', [QuotationSalesController::class, 'getPrint']);
Route::post('/quotation_sales/export', [QuotationSalesController::class, 'dataExport']);
Route::get('/quotation_sales/checkvchrno', [QuotationSalesController::class, 'checkVchrNo']);
Route::post('/quotation_sales/paging', [QuotationSalesController::class, 'ajaxPaging']);
Route::get('/quotation_sales/doc_open/{id}', [QuotationSalesController::class, 'docopen']);
Route::get('/quotation_sales/revice/{id}', [QuotationSalesController::class, 'revice']);
Route::get('/quotation_sales/print/{id}/{fc}/{d}', [QuotationSalesController::class, 'getPrint']);
Route::get('/quotation_sales/get_quotations', [QuotationSalesController::class, 'getQuotations']);
Route::post('/quotation_sales/import', [QuotationSalesController::class, 'getImport']);
Route::get('/quotation_sales/views/{id}',[QuotationSalesController::class, 'getViews']);
Route::get('/quotation_sales/approve/{id}', [QuotationSalesController::class, 'getApproval']);
Route::get('/quotation_sales/getjob/{id}', [QuotationSalesController::class, 'getJob']);
Route::get('/quotation_sales/refresh_qs/{id}', [QuotationSalesController::class, 'refreshQS']);

	
		
		
Route::get('/sales_rental', [SalesRentalController::class, 'index']);
Route::get('/sales_rental/add', [SalesRentalController::class, 'add']);
Route::get('/sales_rental/add/{id}', [SalesRentalController::class, 'add']);
Route::get('/sales_rental/add/{id}/{n}', [SalesRentalController::class, 'add']);
Route::post('/sales_rental/save', [SalesRentalController::class, 'save']);
Route::get('/sales_rental/edit/{id}', [SalesRentalController::class, 'edit']);
Route::post('/sales_rental/update/{id}', [SalesRentalController::class, 'update']);
Route::get('/sales_rental/delete/{id}', [SalesRentalController::class, 'destroy']);
Route::post('/sales_rental/paging', [SalesRentalController::class, 'ajaxPaging']);
Route::get('/sales_rental/print/{id}', [SalesRentalController::class, 'getPrint']);
Route::get('/sales_rental/print/{id}/{fc}', [SalesRentalController::class, 'getPrint']);
Route::get('/sales_rental/customer_data', [SalesRentalController::class, 'getCustomer']);
Route::get('/sales_rental/customer_data/{no}', [SalesRentalController::class, 'getCustomer']);
Route::get('/sales_rental/salesman_data', [SalesRentalController::class, 'getSalesman']);
Route::get('/sales_rental/item_data/{id}', [SalesRentalController::class, 'getItem']);
Route::get('/sales_rental/checkrefno', [SalesRentalController::class, 'checkRefNo']);
Route::get('/sales_rental/getvoucher/{id}', [SalesRentalController::class, 'getVoucher']);
Route::get('/sales_rental/invoice_data', [SalesRentalController::class, 'getInvoice']);
Route::get('/sales_rental/item_details/{id}', [SalesRentalController::class, 'getItemDetails']);
Route::get('/sales_rental/get_invoice/{id}', [SalesRentalController::class, 'getInvoiceByCustomer']);
Route::get('/sales_rental/check_invoice', [SalesRentalController::class, 'checkInvoice']);
Route::post('/sales_rental/set_session', [SalesRentalController::class, 'setSessionVal']);
Route::get('/sales_rental/printdo/{id}', [SalesRentalController::class, 'getPrintdo']);
Route::get('/sales_rental/tstprint', [SalesRentalController::class, 'tstprint']);
Route::get('/sales_rental/get_invoice/{id}/{n}', [SalesRentalController::class, 'getInvoiceByCustomer']);
Route::get('/sales_rental/order_history/{id}', [SalesRentalController::class, 'getOrderHistory']);
Route::get('/sales_rental/checkvchrno', [SalesRentalController::class, 'checkVchrNo']);
Route::get('/sales_rental/get_invoiceset/{id}', [SalesRentalController::class, 'getInvoiceSetByCustomer']);
Route::post('/sales_rental/search', [SalesRentalController::class, 'getSearch']);
Route::post('/sales_rental/export', [SalesRentalController::class, 'dataExport']);
Route::get('/sales_rental/getsaleloc/{id}', [SalesRentalController::class, 'getSaleLocation']);
Route::get('/sales_rental/get_trnno/{name}', [SalesRentalController::class, 'getTrnno']);
Route::get('/sales_rental/cust_history/{id}', [SalesRentalController::class, 'getCustHistory']);
Route::get('/sales_rental/ajax_customer', [SalesRentalController::class, 'getAjaxCust']);
        
		
Route::get('/sales_order', ['as' => 'sales_order.index', 'uses' => SalesOrderController::class.'@index', 'middleware' => ['permission:pi-list|so-create|so-edit|so-delete']]);
Route::get('/sales_order/add', ['as'=>'sales_order.add','uses'=>SalesOrderController::class.'@add','middleware' => ['permission:so-create']]);
Route::get('/sales_order/add/{id}', ['as'=>'sales_order.add','uses'=>SalesOrderController::class.'@add','middleware' => ['permission:so-create']]);
Route::get('/sales_order/add/{id}/{n}', ['as'=>'sales_order.add','uses'=>SalesOrderController::class.'@add','middleware' => ['permission:so-create']]);
Route::post('/sales_order/save', ['as' => 'sales_order.save', 'uses' => SalesOrderController::class.'@save', 'middleware' => ['permission:so-create']] );
Route::get('/sales_order/edit/{id}', ['as' => 'sales_order.edit', 'uses' => SalesOrderController::class.'@edit', 'middleware' => ['permission:so-edit']]);
Route::post('/sales_order/update/{id}', ['as' => 'sales_order.update', 'uses' => SalesOrderController::class.'@update', 'middleware' => ['permission:so-edit']]);
Route::get('/sales_order/viewonly/{id}', ['as' => 'sales_order.viewonly', 'uses' => SalesOrderController::class.'@viewonly', 'middleware' => ['permission:so-view']]);
Route::get('/sales_order/customer_data', [SalesOrderController::class, 'getCustomer']);
Route::get('/sales_order/salesman_data', [SalesOrderController::class, 'getSalesman']);
Route::get('/sales_order/item_data/{id}', [SalesOrderController::class, 'getItem']);
Route::get('/sales_order/checkrefno', [SalesOrderController::class, 'checkRefNo']);
Route::get('/sales_order/delete/{id}', ['as' => 'sales_order.destroy', 'uses' => SalesOrderController::class.'@destroy', 'middleware' => ['permission:so-delete']]);
Route::get('/sales_order/get_order/{id}/{n}', [SalesOrderController::class, 'getOrder']);
Route::get('/sales_order/item_details/{id}', [SalesOrderController::class, 'getItemDetails']);
Route::get('/sales_order/print/{id}', ['as' => 'sales_order.getPrint', 'uses' => SalesOrderController::class.'@getPrint', 'middleware' => ['permission:so-print']]);
Route::get('/sales_order/set_session', [SalesOrderController::class, 'setSessionVal']);
Route::post('/sales_order/search', [SalesOrderController::class, 'getSearch']);
Route::get('/sales_order/print/{id}/{fc}', ['as' => 'sales_order.getPrint', 'uses' => SalesOrderController::class.'@getPrint', 'middleware' => ['permission:so-print']]);
Route::post('/sales_order/export', [SalesOrderController::class, 'dataExport']);
Route::get('/sales_order/newcustomer_data', [SalesOrderController::class, 'getNewCustomer']);
Route::get('/sales_order/checkvchrno', [SalesOrderController::class, 'checkVchrNo']);
Route::post('/sales_order/paging', [SalesOrderController::class, 'ajaxPaging']);
Route::get('/sales_order/customer_data/{did}', [SalesOrderController::class, 'getCustomer']);
Route::get('/sales_order/newcustomer_data/{did}', [SalesOrderController::class, 'getNewCustomer']);
Route::get('/sales_order/poadd/{id}', ['as'=>'sales_order.add','uses'=>SalesOrderController::class.'@poadd','middleware' => ['permission:so-create']]);
Route::post('/sales_order/get_orderno', [SalesOrderController::class, 'getOrderNo']);
Route::get('/sales_order/getcounter/{id}', [SalesOrderController::class, 'getCounter']);
Route::get('/sales_order/get_report/{id}', [SalesOrderController::class, 'getReport']);
Route::get('/sales_order/get_salesorder', [SalesOrderController::class, 'getSalesOrder']);
Route::post('/sales_order/customer_list', [SalesOrderController::class, 'ajaxCustomerList']);
Route::get('/sales_order/account_data/{type}', [SalesOrderController::class, 'getAccount']);
		//work order
Route::get('/sales_order/revice/{id}', [SalesOrderController::class, 'revice']);
Route::get('/sales_order/work_order', [SalesOrderController::class, 'windex']);
Route::post('/sales_order/work_paging', [SalesOrderController::class, 'ajaxWorkPaging']);
		
Route::get('/sales_order/settlement/{id}', [SalesOrderController::class, 'Settlement']);//April 2025
Route::get('/sales_order/getjob/{id}', [SalesOrderController::class, 'getJob']);
Route::get('/sales_order/getworkjob/{id}', [SalesOrderController::class, 'getWorkJob']);
		
		
Route::post('/sales_order/save_draft', [SalesOrderController::class, 'saveDraft']);//Draft
Route::get('/sales_order/edit_draft/{id}', [SalesOrderController::class, 'editDraft']);
Route::post('/sales_order/update_draft/{id}', [SalesOrderController::class, 'updateDraft']);
		
Route::get('/sales_order/refresh_so/{id}', [SalesOrderController::class, 'refreshSO']);
		
		
Route::get('/customers_do', ['as' => 'customers_do.index', 'uses' => CustomersDOController::class.'@index', 'middleware' => ['permission:do-list|do-create|do-edit|do-delete']]);
Route::get('/customers_do/add', ['as'=>'customers_do.add','uses'=>CustomersDOController::class.'@add','middleware' => ['permission:do-create']]);
Route::post('/customers_do/save/{id}', ['as' => 'customers_do.save', 'uses' => CustomersDOController::class.'@save', 'middleware' => ['permission:do-create']] );
Route::post('/customers_do/save', ['as' => 'customers_do.save', 'uses' => CustomersDOController::class.'@save', 'middleware' => ['permission:do-create']] );
Route::get('/customers_do/edit/{id}', ['as' => 'customers_do.edit', 'uses' => CustomersDOController::class.'@edit', 'middleware' => ['permission:do-edit']]);
Route::get('/customers_do/add/{id}/{n}', ['as'=>'customers_do.add','uses'=>CustomersDOController::class.'@add','middleware' => ['permission:do-create']]);
Route::get('/customers_do/viewonly/{id}', ['as' => 'customers_do.viewonly', 'uses' => CustomersDOController::class.'@viewonly', 'middleware' => ['permission:do-view']]);
Route::get('/customers_do/supplier_data', [CustomersDOController::class, 'getSupplier']);
Route::get('/customers_do/item_data/{id}', [CustomersDOController::class, 'getItem']);
Route::get('/customers_do/checkrefno', [CustomersDOController::class, 'checkRefNo']);
Route::get('/customers_do/delete/{id}', ['as' => 'customers_do.destroy', 'uses' => CustomersDOController::class.'@destroy', 'middleware' => ['permission:do-delete']]);
Route::get('/customers_do/sdo_data', [CustomersDOController::class, 'getSDO']);
Route::get('/customers_do/sdo_data/{id}', [CustomersDOController::class, 'getSDO']);
Route::get('/customers_do/get_order/{id}/{n}', [CustomersDOController::class, 'getOrder']);
Route::get('/customers_do/print/{id}', ['as' => 'customers_do.getPrint', 'uses' => CustomersDOController::class.'@getPrint', 'middleware' => ['permission:do-print']]);
Route::get('/customers_do/set_session', [CustomersDOController::class, 'setSessionVal']);
Route::post('/customers_do/update/{id}', [CustomersDOController::class, 'update']);
Route::post('/customers_do/search', [CustomersDOController::class, 'getSearch']);
Route::get('/customers_do/print/{id}/{fc}', ['as' => 'customers_do.getPrint', 'uses' => CustomersDOController::class.'@getPrint', 'middleware' => ['permission:do-print']]);
Route::post('/customers_do/export', [CustomersDOController::class, 'dataExport']);
Route::get('/customers_do/checkvchrno', [CustomersDOController::class, 'checkVchrNo']);
Route::post('/customers_do/paging', [CustomersDOController::class, 'ajaxPaging']);
Route::get('/customers_do/item_details/{id}', [CustomersDOController::class, 'getItemDetails']);
Route::get('/customers_do/get_customerdo', [CustomersDOController::class, 'getCustomerDo']);
Route::get('/customers_do/get_pending', [CustomersDOController::class, 'getPending']);
Route::get('/customers_do/edit_force/{id}', [CustomersDOController::class, 'editForce']); //JUL7
Route::get('/customers_do/get_order/{id}/{n}/{sid}', [CustomersDOController::class, 'getOrder']);//JUL7
Route::get('/customers_do/getjob/{id}', [CustomersDOController::class, 'getJob']);
Route::get('/customers_do/refresh_do/{id}', [CustomersDOController::class, 'refreshDO']);
		
Route::get('/sales_invoice', ['as' => 'sales_invoice.index', 'uses' => SalesInvoiceController::class.'@index', 'middleware' => ['permission:si-list|si-create|si-edit|si-delete']]);
Route::get('/sales_invoice/add', ['as'=>'sales_invoice.add','uses'=>SalesInvoiceController::class.'@add','middleware' => ['permission:si-create']]);
Route::get('/sales_invoice/add/{id}/{n}', ['as'=>'sales_invoice.add','uses'=>SalesInvoiceController::class.'@add','middleware' => ['permission:si-create']]);
Route::post('/sales_invoice/save', ['as' => 'sales_invoice.save', 'uses' => SalesInvoiceController::class.'@save', 'middleware' => ['permission:si-create']]);
Route::get('/sales_invoice/edit/{id}', ['as' => 'sales_invoice.edit', 'uses' => SalesInvoiceController::class.'@edit', 'middleware' => ['permission:si-edit']]);
Route::post('/sales_invoice/update/{id}', ['as' => 'sales_invoice.update', 'uses' => SalesInvoiceController::class.'@update', 'middleware' => ['permission:si-edit']]);
Route::get('/sales_invoice/viewonly/{id}', ['as' => 'sales_invoice.viewonly', 'uses' => SalesInvoiceController::class.'@viewonly', 'middleware' => ['permission:si-view']]);
Route::get('/sales_invoice/customer_data', [SalesInvoiceController::class, 'getCustomer']);
Route::get('/sales_invoice/customer_data/{no}', [SalesInvoiceController::class, 'getCustomer']);
Route::get('/sales_invoice/salesman_data', [SalesInvoiceController::class, 'getSalesman']);
Route::get('/sales_invoice/salesman_data/{n}', [SalesInvoiceController::class, 'getSalesman']);
Route::get('/sales_invoice/item_data/{id}', [SalesInvoiceController::class, 'getItem']);
Route::get('/sales_invoice/checkrefno', [SalesInvoiceController::class, 'checkRefNo']);
Route::get('/sales_invoice/delete/{id}', ['as' => 'sales_invoice.destroy', 'uses' => SalesInvoiceController::class.'@destroy', 'middleware' => ['permission:si-delete']]);
Route::get('/sales_invoice/getvoucher/{id}', [SalesInvoiceController::class, 'getVoucher']);
Route::get('/sales_invoice/invoice_data', [SalesInvoiceController::class, 'getInvoice']);
Route::get('/sales_invoice/item_details/{id}', [SalesInvoiceController::class, 'getItemDetails']);
Route::get('/sales_invoice/get_invoice/{id}', [SalesInvoiceController::class, 'getInvoiceByCustomer']);
Route::get('/sales_invoice/check_invoice', [SalesInvoiceController::class, 'checkInvoice']);
Route::post('/sales_invoice/set_session', [SalesInvoiceController::class, 'setSessionVal']);
Route::get('/sales_invoice/print/{id}', ['as' => 'sales_invoice.getPrint', 'uses' => SalesInvoiceController::class.'@getPrint', 'middleware' => ['permission:si-print']]);
Route::get('/sales_invoice/printdo/{id}', [SalesInvoiceController::class, 'getPrintdo']);
Route::get('/sales_invoice/tstprint', [SalesInvoiceController::class, 'tstprint']);
Route::get('/sales_invoice/get_invoice/{id}/{n}', [SalesInvoiceController::class, 'getInvoiceByCustomer']);
Route::get('/sales_invoice/order_history/{id}', [SalesInvoiceController::class, 'getOrderHistory']);
Route::get('/sales_invoice/checkvchrno', [SalesInvoiceController::class, 'checkVchrNo']);
Route::get('/sales_invoice/get_invoiceset/{id}', [SalesInvoiceController::class, 'getInvoiceSetByCustomer']);
Route::post('/sales_invoice/search', [SalesInvoiceController::class, 'getSearch']);
Route::get('/sales_invoice/print/{id}/{rid}', ['as' => 'sales_invoice.getPrint', 'uses' => SalesInvoiceController::class.'@getPrint', 'middleware' => ['permission:si-print']]);
Route::post('/sales_invoice/export', [SalesInvoiceController::class, 'dataExport']);
//Route::post('/sales_invoice/export', ['as' => 'sales_invoice.dataExport', 'uses' => SalesInvoiceController::class.'@dataExport', 'middleware' => ['permission:si-export']]);
Route::get('/sales_invoice/getsaleloc/{id}', [SalesInvoiceController::class, 'getSaleLocation']);
Route::get('/sales_invoice/get_trnno/{name}', [SalesInvoiceController::class, 'getTrnno']);
Route::get('/sales_invoice/cust_history/{id}', [SalesInvoiceController::class, 'getCustHistory']);
Route::get('/sales_invoice/index_history/{id}', [SalesInvoiceController::class, 'getHistory']);
Route::get('/sales_invoice/ajax_customer', [SalesInvoiceController::class, 'getAjaxCust']);
Route::get('/sales_invoice/cust_history_phone/{id}', [SalesInvoiceController::class, 'getCustHistoryPhone']);
Route::post('/sales_invoice/paging', [SalesInvoiceController::class, 'ajaxPaging']);
Route::post('/sales_invoice/paging_invoice_data', [SalesInvoiceController::class, 'ajaxPagingInvoiceData']);
Route::get('/sales_invoice/get_invoice/{id}/{n}/{val}/{rid}', [SalesInvoiceController::class, 'getInvoiceByCustomer']);//ED12
Route::get('/sales_invoice/get_invoice/{id}/{n}/{rvid}', [SalesInvoiceController::class, 'getInvoiceByCustomerEdit']);//ED12
Route::get('/sales_invoice/get_invoicecn/{id}/{n}/{val}', [SalesInvoiceController::class, 'getInvoiceByCustomerCn']);//ED12
Route::post('/sales_invoice/export_po', [SalesInvoiceController::class, 'dataExportPo']);
Route::get('/sales_invoice/vehicle_history/{id}', [SalesInvoiceController::class, 'getvehicleHistory']);
Route::get('/sales_invoice/printfc/{id}/{rid}', ['as' => 'sales_invoice.getPrintFc', 'uses' => SalesInvoiceController::class.'@getPrintFc', 'middleware' => ['permission:si-print']]);
Route::get('/sales_invoice/getdeptvoucher/{id}', [SalesInvoiceController::class, 'getDeptVoucher']);
Route::get('/sales_invoice/invoice_data/{did}', [SalesInvoiceController::class, 'getInvoice']);
Route::get('/sales_invoice/customer_datadpt/{dpt}', [SalesInvoiceController::class, 'getCustomerDpt']);
Route::get('/sales_invoice/getcustomerselect', [SalesInvoiceController::class, 'getCustomerMultiselect']);
Route::get('/sales_invoice/getitems', [SalesInvoiceController::class, 'getItems']);
Route::get('/sales_invoice/edit/{ids}/{m}/{id}', [SalesInvoiceController::class, 'editTransfer']);//JUL7
Route::get('/sales_invoice/get_billdata/{id}', [SalesInvoiceController::class, 'getBilldata']);
Route::post('/sales_invoice/upload', [SalesInvoiceController::class, 'uploadSubmit']);
Route::get('/sales_invoice/photo-view/{id}', [SalesInvoiceController::class, 'photoView']);
Route::get('/sales_invoice/get_salesinvoice', [SalesInvoiceController::class, 'getSalesInvoice']);
Route::get('/sales_invoice/refresh_do/{id}', [SalesInvoiceController::class, 'refreshDO']);
Route::get('/sales_invoice/getjob/{id}', [SalesInvoiceController::class, 'getJob']);

		//sales_invoice/edit/'.$sirow->id.'/SO/'.$id
		
		
Route::get('/sales_return', ['as' => 'sales_return.index', 'uses' => SalesReturnController::class.'@index', 'middleware' => ['permission:sr-list|sr-create|sr-edit|sr-delete']]);
Route::get('/sales_return/add', ['as'=>'sales_return.add','uses'=>SalesReturnController::class.'@add','middleware' => ['permission:sr-create']]);
Route::post('/sales_return/save/{id}', [SalesReturnController::class, 'save']);
Route::post('/sales_return/save', [SalesReturnController::class, 'save']);
Route::get('/sales_return/edit/{id}', ['as' => 'sales_return.edit', 'uses' => SalesReturnController::class.'@edit', 'middleware' => ['permission:sr-edit']]);
Route::get('/sales_return/add/{id}', ['as'=>'sales_return.add','uses'=>SalesReturnController::class.'@add','middleware' => ['permission:sr-create']]);
Route::get('/sales_return/delete/{id}', ['as' => 'sales_return.destroy', 'uses' => SalesReturnController::class.'@destroy', 'middleware' => ['permission:sr-delete']]);
Route::get('/sales_return/getvoucher/{id}', [SalesReturnController::class, 'getVoucher']);
Route::get('/sales_return/print/{id}', ['as' => 'sales_return.getPrint', 'uses' => SalesReturnController::class.'@getPrint', 'middleware' => ['permission:sr-print']]);
Route::get('/sales_return/viewonly/{id}', ['as' => 'sales_return.viewonly', 'uses' => SalesReturnController::class.'@viewonly', 'middleware' => ['permission:sr-view']]);
Route::get('/sales_return/set_session', [SalesReturnController::class, 'setSessionVal']);
Route::get('/sales_return/checkrefno', [SalesReturnController::class, 'checkRefNo']);
Route::get('/sales_return/checkvchrno', [SalesReturnController::class, 'checkVchrNo']);
Route::post('/sales_return/search', [SalesReturnController::class, 'getSearch']);
Route::post('/sales_return/export', ['as' => 'sales_return.dataExport', 'uses' => SalesReturnController::class.'@dataExport', 'middleware' => ['permission:sr-print']]);
Route::post('/sales_return/update/{id}', [SalesReturnController::class, 'update']);
Route::post('/sales_return/paging', [SalesReturnController::class, 'ajaxPaging']);
Route::get('/sales_return/print/{id}/{rid}', [SalesReturnController::class, 'getPrint']);
Route::get('/sales_return/getcustomerselect', [SalesReturnController::class, 'getCustomerMultiselect']);
Route::get('/sales_return/getitems', [SalesReturnController::class, 'getItems']);
Route::get('/sales_return/getdeptvoucher/{id}', [SalesReturnController::class, 'getDeptVoucher']);
Route::get('/sales_return/getjob/{id}', [SalesReturnController::class, 'getJob']);

		
		
Route::get('/customer_receipt', ['as' => 'customer_receipt.index', 'uses' => CustomerReceiptController::class.'@index', 'middleware' => ['permission:rv-list|rv-create|rv-edit|rv-delete']]);
Route::get('/customer_receipt/add', ['as'=>'customer_receipt.add','uses'=>CustomerReceiptController::class.'@add','middleware' => ['permission:rv-create']]);
Route::post('/customer_receipt/save', [CustomerReceiptController::class, 'save']);
Route::get('/customer_receipt/getvoucher/{id}/{type}', [CustomerReceiptController::class, 'getVoucher']);
Route::get('/customer_receipt/edit/{id}', ['as' => 'customer_receipt.edit', 'uses' => CustomerReceiptController::class.'@edit', 'middleware' => ['permission:rv-edit']]);
Route::post('/customer_receipt/update/{id}', [CustomerReceiptController::class, 'update']);
Route::get('/customer_receipt/delete/{id}/{n}', ['as' => 'customer_receipt.destroy', 'uses' => CustomerReceiptController::class.'@destroy', 'middleware' => ['permission:rv-delete']]);
Route::get('/customer_receipt/checkvchrno', [CustomerReceiptController::class, 'checkVchrNo']);
//Route::get('/customer_receipt/print2/{id}', ['as' => 'customer_receipt.getPrint2', 'uses' => CustomerReceiptController::class.'@getPrint2', 'middleware' => ['permission:rv-print']]);
Route::post('/customer_receipt/paging', [CustomerReceiptController::class, 'ajaxPaging']);
Route::get('/customer_receipt/getdeptvoucher/{id}', [CustomerReceiptController::class, 'getDeptVoucher']);
Route::get('/customer_receipt/getvoucher/{id}/{type}/{dpt}', [CustomerReceiptController::class, 'getVoucher']);
Route::get('/customer_receipt/printgrp/{id}', ['as' => 'customer_receipt.getGrpPrint', 'uses' => CustomerReceiptController::class.'@getGrpPrint', 'middleware' => ['permission:rv-print']]);
Route::get('/customer_receipt/print/{id}', ['as' => 'customer_receipt.getPrint', 'uses' => CustomerReceiptController::class.'@getPrint', 'middleware' => ['permission:rv-print']]);
Route::get('/customer_receipt/print2/{id}/{rid}', ['as' => 'customer_receipt.getPrint', 'uses' => CustomerReceiptController::class.'@getPrint', 'middleware' => ['permission:rv-print']]);
Route::post('/customer_receipt/search', [CustomerReceiptController::class, 'getSearch']);
Route::post('/customer_receipt/export', [CustomerReceiptController::class, 'dataExport']);
Route::get('/customer_receipt/add/{id}', [CustomerReceiptController::class, 'addAutoFill']);
		
Route::get('/customer_receipt/add-rv', ['as'=>'customer_receipt.add','uses'=>CustomerReceiptController::class.'@addRV','middleware' => ['permission:rv-create']]);
Route::get('/customer_receipt/set_transactions/{type}/{id}/{n}', [CustomerReceiptController::class, 'setTransactions']);
		
Route::get('/other_receipt', [OtherReceiptController::class, 'index']);
Route::get('/other_receipt/add', [OtherReceiptController::class, 'add']);
Route::post('/other_receipt/save', [OtherReceiptController::class, 'save']);
		
		
		
Route::get('/supplier_payment', ['as' => 'supplier_payment.index', 'uses' => SupplierPaymentController::class.'@index', 'middleware' => ['permission:si-list|pv-create|pv-edit|pv-delete']]);
Route::get('/supplier_payment/add', ['as'=>'supplier_payment.add','uses'=>SupplierPaymentController::class.'@add','middleware' => ['permission:pv-create']]);
Route::post('/supplier_payment/save', [SupplierPaymentController::class, 'save']);
Route::get('/supplier_payment/edit/{id}', ['as' => 'supplier_payment.edit', 'uses' => SupplierPaymentController::class.'@edit', 'middleware' => ['permission:pv-edit']]);
Route::post('/supplier_payment/update/{id}', [SupplierPaymentController::class, 'update']);
Route::get('/supplier_payment/delete/{id}', ['as' => 'supplier_payment.destroy', 'uses' => SupplierPaymentController::class.'@destroy', 'middleware' => ['permission:pv-delete']]);
Route::get('/supplier_payment/checkvchrno', [SupplierPaymentController::class, 'checkVchrNo']);
Route::get('/supplier_payment/print/{id}', ['as' => 'supplier_payment.getPrint', 'uses' => SupplierPaymentController::class.'@getPrint', 'middleware' => ['permission:pv-print']]);
Route::get('/supplier_payment/getvoucher/{id}/{type}', [SupplierPaymentController::class, 'getVoucher']);
Route::post('/supplier_payment/paging', [SupplierPaymentController::class, 'ajaxPaging']);
Route::get('/supplier_payment/getdeptvoucher/{id}', [SupplierPaymentController::class, 'getDeptVoucher']);
Route::get('/supplier_payment/getvoucher/{id}/{type}/{dpt}', [SupplierPaymentController::class, 'getVoucher']);
Route::get('/supplier_payment/printgrp/{id}', ['as' => 'supplier_payment.getGrpPrint', 'uses' => SupplierPaymentController::class.'@getGrpPrint', 'middleware' => ['permission:pv-print']]);
Route::get('/supplier_payment/print/{id}/{rid}', ['as' => 'supplier_payment.getPrint', 'uses' => SupplierPaymentController::class.'@getPrint', 'middleware' => ['permission:pv-print']]);
Route::get('/supplier_payment/cheque_details/{id}', [SupplierPaymentController::class, 'ChequeDetails']);
Route::post('/supplier_payment/che_save', [SupplierPaymentController::class, 'Chequesave']);
Route::post('/supplier_payment/search', [SupplierPaymentController::class, 'getSearch']);
Route::post('/supplier_payment/export', [SupplierPaymentController::class, 'dataExport']);
Route::get('/supplier_payment/views/{id}',[SupplierPaymentController::class, 'getViews']);
Route::get('/supplier_payment/approve/{id}', [SupplierPaymentController::class, 'getApproval']);

Route::get('/supplier_payment/quick-add', ['as'=>'supplier_payment.add','uses'=>SupplierPaymentController::class.'@quickAdd','middleware' => ['permission:pv-create']]);
		
		//NEW SECTION FEB25
Route::get('/supplier_payment/add-pv', ['as'=>'supplier_payment.add-pv','uses'=>SupplierPaymentController::class.'@addPV','middleware' => ['permission:pv-create']]);
Route::get('/supplier_payment/set_transactions/{type}/{id}/{n}', [SupplierPaymentController::class, 'setTransactions']);
		
		
		//New feb25
Route::get('/receipt_voucher', ['as' => 'receipt_voucher.index', 'uses' => CustomerReceiptController::class.'@indexrv', 'middleware' => ['permission:rv-list|rv-create|rv-edit|rv-delete']]);
Route::get('/receipt_voucher/add', ['as'=>'receipt_voucher.add','uses'=>CustomerReceiptController::class.'@addjrv','middleware' => ['permission:rv-create']]);
Route::post('/receipt_voucher/save', [CustomerReceiptController::class, 'saverv']);
Route::post('/receipt_voucher/paging', [CustomerReceiptController::class, 'ajaxPagingrv']);
Route::get('/receipt_voucher/edit/{id}', ['as' => 'receipt_voucher.edit', 'uses' => CustomerReceiptController::class.'@editrv', 'middleware' => ['permission:rv-edit']]);
Route::get('/receipt_voucher/printgrprv/{id}', ['as' => 'customer_receipt.getGrpPrintrv', 'uses' => CustomerReceiptController::class.'@getGrpPrintrv', 'middleware' => ['permission:rv-print']]);

		
Route::get('/payment_voucher', ['as' => 'payment_voucher.index', 'uses' => SupplierPaymentController::class.'@indexpv', 'middleware' => ['permission:si-list|pv-create|pv-edit|pv-delete']]);
Route::get('/payment_voucher/add', ['as'=>'payment_voucher.add','uses'=>SupplierPaymentController::class.'@addjpv','middleware' => ['permission:pv-create']]);
Route::post('/payment_voucher/save', [SupplierPaymentController::class, 'savepv']);
Route::post('/payment_voucher/paging', [SupplierPaymentController::class, 'ajaxPagingpv']);
Route::get('/payment_voucher/edit/{id}', ['as' => 'payment_voucher.edit', 'uses' => SupplierPaymentController::class.'@editpv', 'middleware' => ['permission:pv-edit']]);

Route::get('/contra_voucher', ['as' => 'contra_voucher.index', 'uses' => ContraVoucherController::class.'@index']);
Route::get('/contra_voucher/add', ['as'=>'contra_voucher.add','uses'=>ContraVoucherController::class.'@add']);
Route::post('/contra_voucher/save', [ContraVoucherController::class, 'save']);
Route::get('/contra_voucher/delete/{id}', ['as' => 'ContraVoucherController.destroy', 'uses' => ContraVoucherController::class.'@destroy']);
Route::get('/contra_voucher/edit/{id}', ['as'=>'contra_voucher.edit','uses'=>ContraVoucherController::class.'@edit']);
Route::post('/contra_voucher/update/{id}', [ContraVoucherController::class, 'update']);
Route::get('/contra_voucher/printgrp/{id}/{rid}', ['as'=>'contra_voucher.printgrp','uses'=>ContraVoucherController::class.'@printGrp']);
Route::get('/contra_voucher/checkvchrno', [ContraVoucherController::class, 'checkVchrNo']);

Route::get('/other_payment', [OtherPaymentController::class, 'index']);
Route::get('/other_payment/add', [OtherPaymentController::class, 'add']);
Route::post('/other_payment/save', [OtherPaymentController::class, 'save']);
		
		
Route::get('/pdc_received', ['as' => 'pdc_received.index', 'uses' => PdcReceivedController::class.'@index', 'middleware' => ['permission:pdr-list|pdr-submit|pdr-undo|pdr-print']]);
Route::post('/pdc_received/save', [PdcReceivedController::class, 'save']);
Route::post('/pdc_received/print', ['as' => 'pdc_received.getPrint', 'uses' => PdcReceivedController::class.'@getPrint', 'middleware' => ['permission:pdr-print']]);
Route::post('/pdc_received/undo', ['as' => 'pdc_received.undo', 'uses' => PdcReceivedController::class.'@undo', 'middleware' => ['permission:pdr-undo']]);
Route::get('/pdc_received/undo_list', [PdcReceivedController::class, 'UndoList']);
Route::post('/pdc_received/cheque_submit', [PdcReceivedController::class, 'chequeSubmit']);
Route::get('/pdc_received/delete/{id}', [PdcReceivedController::class, 'delete']);
		
		
Route::get('/pdc_issued', ['as' => 'pdc_issued.index', 'uses' => PdcIssuedController::class.'@index', 'middleware' => ['permission:pdi-list|pdi-submit|pdi-undo|pdi-print']]);
Route::post('/pdc_issued/save', [PdcIssuedController::class, 'save']);
Route::post('/pdc_issued/print', ['as' => 'pdc_issued.getPrint', 'uses' => PdcIssuedController::class.'@getPrint', 'middleware' => ['permission:pdi-print']]);
Route::post('/pdc_issued/undo', ['as' => 'pdc_issued.undo', 'uses' => PdcIssuedController::class.'@undo', 'middleware' => ['permission:pdi-undo']]);
Route::get('/pdc_issued/undo_list', [PdcIssuedController::class, 'UndoList']);
Route::get('/pdc_issued/delete/{id}', [PdcIssuedController::class, 'delete']);
		
		
Route::get('/journal', ['as' => 'journal.index', 'uses' => JournalController::class.'@index', 'middleware' => ['permission:jv-list|jv-create|jv-edit|jv-delete']]);
Route::get('/journal/add', ['as'=>'journal.add','uses'=>JournalController::class.'@add','middleware' => ['permission:jv-create']]);
Route::post('/journal/save', [JournalController::class, 'save']);
Route::get('/journal/getvoucher/{id}', [JournalController::class, 'getVoucher']);
Route::get('/journal/delete/{id}/{n}', ['as' => 'journal.destroy', 'uses' => JournalController::class.'@destroy', 'middleware' => ['permission:jv-delete']]);
Route::get('/journal/getvouchertype/{id}', [JournalController::class, 'getVoucherType']);
Route::get('/journal/edit/{id}', ['as' => 'journal.edit', 'uses' => JournalController::class.'@edit', 'middleware' => ['permission:jv-edit']]);
Route::post('/journal/update/{id}', [JournalController::class, 'update']);
Route::get('/journal/checkvchrno', [JournalController::class, 'checkVchrNo']);
Route::get('/journal/checkvno', [JournalController::class, 'checkVNo']);
Route::get('/journal/print/{id}', ['as' => 'journal.getPrint', 'uses' => JournalController::class.'@getPrint', 'middleware' => ['permission:jv-print']]);
Route::get('/journal/print/{id}/{rid}', ['as' => 'journal.getPrint', 'uses' => JournalController::class.'@getPrint', 'middleware' => ['permission:jv-print']]);
Route::get('/journal/add/{id}/{rid}/{vouchertype}', ['as'=>'journal.add','uses'=>JournalController::class.'@add','middleware' => ['permission:jv-create']]);
Route::get('/journal/getvoucherprint', [JournalController::class, 'getVoucherprint']);
Route::get('/journal/set_transactions/{type}/{id}/{n}', [JournalController::class, 'setTransactions']);
Route::get('/journal/set_transactions/{type}/{id}/{n}/{j}', [JournalController::class, 'setTransactions']);
Route::post('/journal/paging', [JournalController::class, 'ajaxPaging']);
Route::post('/journal/recurring_add', [JournalController::class, 'recurringAdd']);
Route::post('/journal/quicksave', [JournalController::class, 'quickSave']);
Route::get('/journal/add-jv', ['as'=>'journal.add','uses'=>JournalController::class.'@addJV','middleware' => ['permission:jv-create']]);


Route::get('/voucherwise_report', [VoucherwiseReportController::class, 'index']);
Route::post('/voucherwise_report', [VoucherwiseReportController::class, 'index']);
Route::get('/voucherwise_report/print/{id}/{n}', [VoucherwiseReportController::class, 'printReport']);
Route::post('/voucherwise_report/print', [VoucherwiseReportController::class, 'getPrint']);
Route::get('/voucherwise_report/pisi_report', [VoucherwiseReportController::class, 'pisiReport']);
Route::post('/voucherwise_report/pisi_print', [VoucherwiseReportController::class, 'getPisiPrint']);
Route::get('/voucherwise_report/pisi_jobwise', [VoucherwiseReportController::class, 'pisijobReport']);
Route::post('/voucherwise_report/export', [VoucherwiseReportController::class, 'dataExport']);
Route::post('/voucherwise_report/pisiexport', [VoucherwiseReportController::class, 'datapisiExport']);
Route::get('/voucherwise_report/pisirtn_report', [VoucherwiseReportController::class, 'pisirtnReport']);
Route::post('/voucherwise_report/pisirtn_print', [VoucherwiseReportController::class, 'getPisirtnPrint']);
Route::get('/voucherwise_report/pisirtn_jobwise', [VoucherwiseReportController::class, 'pisirtnjobReport']);
Route::post('/voucherwise_report/pisirtnexport', [VoucherwiseReportController::class, 'datapisirtnExport']);
		
Route::get('/voucherwise_report/pisirv_report', [VoucherwiseReportController::class, 'pisirvReport']);
Route::post('/voucherwise_report/pisirv_print', [VoucherwiseReportController::class, 'getPisirvPrint']);
Route::post('/voucherwise_report/pisirvexport', [VoucherwiseReportController::class, 'datapisirvExport']);
Route::get('/voucherwise_report/pisirv_jobwise', [VoucherwiseReportController::class, 'pisirvjobReport']);
		
Route::get('/voucherwise_report/pisi_summary', [VoucherwiseReportController::class, 'pisiSummary']);
Route::post('/voucherwise_report/pisi_summary_print', [VoucherwiseReportController::class, 'pisiSummaryPrint']);
Route::post('/voucherwise_report/pisisummary_export', [VoucherwiseReportController::class, 'datapisisummaryExport']);
		
		
Route::get('/trial_balance', [TrialBalanceController::class, 'index']);
Route::post('/trial_balance/search', [TrialBalanceController::class, 'getSearch']);
Route::post('/trial_balance/export', [TrialBalanceController::class, 'dataExport']);
		
Route::get('/cash_inhand', [CashInhandController::class, 'index']);
Route::post('/cash_inhand/search', [CashInhandController::class, 'getSearch']);
Route::post('/cash_inhand/export', [CashInhandController::class, 'dataExport']);
		
Route::get('/profit_loss', [ProfitLossController::class, 'index']);
Route::post('/profit_loss/search', [ProfitLossController::class, 'getSearch']);
Route::post('/profit_loss/export', [ProfitLossController::class, 'dataExport']);
		
		
Route::get('/balance_sheet', [BalanceSheetController::class, 'index']);
Route::post('/balance_sheet/search', [BalanceSheetController::class, 'getSearch']);
Route::post('/balance_sheet/export', [BalanceSheetController::class, 'dataExport']);
		
Route::get('/purchase_report', [PurchaseReportController::class, 'index']);
Route::post('/purchase_report/search', [PurchaseReportController::class, 'getSearch']);
Route::post('/purchase_report/summary', [PurchaseReportController::class, 'getSummary']);
Route::post('/purchase_report/print', [PurchaseReportController::class, 'getPrint']);
		
Route::get('/sales_report', [SalesReportController::class, 'index']);
Route::post('/sales_report/search', [SalesReportController::class, 'getSearch']);
Route::post('/sales_report/summary', [SalesReportController::class, 'getSummary']);
Route::post('/sales_report/print', [SalesReportController::class, 'getPrint']);
		
Route::get('/quantity_report', [QuantityReportController::class, 'index']);
Route::post('/quantity_report/search', [QuantityReportController::class, 'getSearch']);
Route::post('/quantity_report/print', [QuantityReportController::class, 'getPrint']);
Route::post('/quantity_report/export', [QuantityReportController::class, 'dataExport']);
		
		
Route::get('/stock_ledger', [StockLedgerController::class, 'index']);
Route::post('/stock_ledger/search', [StockLedgerController::class, 'getSearch']);
Route::post('/stock_ledger/print', [StockLedgerController::class, 'getPrint']);
Route::post('/stock_ledger/export', [StockLedgerController::class, 'dataExport']);
		
Route::get('/stock_transaction', [StockTransactionController::class, 'index']);
Route::post('/stock_transaction/search', [StockTransactionController::class, 'getSearch']);
//Route::post('/stock_transaction/print', [StockTransactionController::class, 'getPrint']);
Route::post('/stock_transaction/export', [StockTransactionController::class, 'dataExport']);
		
Route::get('/stock_movement', [StockMovementController::class, 'index']);
Route::post('/stock_movement/search', [StockMovementController::class, 'getSearch']);
Route::post('/stock_movement/export', [StockMovementController::class, 'dataExport']);
		
Route::get('/batch_report', [BatchReportController::class, 'index']);
Route::post('/batch_report/search', [BatchReportController::class, 'getSearch']);
Route::post('/batch_report/export', [BatchReportController::class, 'dataExport']);
		
Route::get('/profit_analysis', [ProfitAnalysisController::class, 'index']);
Route::post('/profit_analysis/search', [ProfitAnalysisController::class, 'getSearch']);
Route::post('/profit_analysis/print', [ProfitAnalysisController::class, 'getPrint']);
Route::get('/profit_analysis/getcustomer', [ProfitAnalysisController::class, 'getCustomer']);
Route::get('/profit_analysis/getitems', [ProfitAnalysisController::class, 'getItems']);
Route::get('/profit_analysis/getsalesman', [ProfitAnalysisController::class, 'getSalesman']);
Route::get('/profit_analysis/getArea', [ProfitAnalysisController::class, 'getArea']);
Route::get('/profit_analysis/getgroup', [ProfitAnalysisController::class, 'getgroup']);
Route::get('/profit_analysis/getSubGroup', [ProfitAnalysisController::class, 'getSubGroup']);
Route::post('/profit_analysis/export', [ProfitAnalysisController::class, 'dataExport']);
		
		
Route::get('/daily_report', [DailyReportController::class, 'index']);
Route::post('/daily_report/search', [DailyReportController::class, 'getSearch']);
Route::post('/daily_report/print', [DailyReportController::class, 'getPrint']);
Route::post('/daily_report/export', [DailyReportController::class, 'dataExport']);
Route::post('/daily_report/search_account', [DailyReportController::class, 'searchAccount']);



Route::get('/daily_report_setting', [DailySettingController::class, 'index']);
Route::get('/daily_report_setting/detail', [DailySettingController::class, 'detail']);
Route::post('/daily_report_setting/update', [DailySettingController::class, 'update']);

Route::get('/vat_report', [VatReportController::class, 'index']);
Route::post('/vat_report/search', [VatReportController::class, 'getSearch']);
Route::post('/vat_report/print', [VatReportController::class, 'getPrint']);
Route::post('/vat_report/export', [VatReportController::class, 'dataExport']);
		
Route::get('/goods_issued', [GoodsIssuedController::class, 'index']);
Route::get('/goods_issued/add', [GoodsIssuedController::class, 'add']);
Route::post('/goods_issued/save/{id}', [GoodsIssuedController::class, 'save']);
Route::post('/goods_issued/save', [GoodsIssuedController::class, 'save']);
Route::get('/goods_issued/delete/{id}', [GoodsIssuedController::class, 'destroy']);
Route::get('/goods_issued/print/{id}', [GoodsIssuedController::class, 'getPrint']);
Route::get('/goods_issued/edit/{id}', [GoodsIssuedController::class, 'edit']);
Route::post('/goods_issued/update/{id}', [GoodsIssuedController::class, 'update']);
Route::get('/goods_issued/viewonly/{id}', [GoodsIssuedController::class, 'viewonly']);
Route::get('/goods_issued/getvoucher/{id}', [GoodsIssuedController::class, 'getVoucher']);
Route::post('/goods_issued/search', [GoodsIssuedController::class, 'getSearch']);
Route::post('/goods_issued/export', [GoodsIssuedController::class, 'dataExport']);
Route::post('/goods_issued/paging', [GoodsIssuedController::class, 'ajaxPaging']);
Route::get('/goods_issued/getdeptvoucher/{id}', [GoodsIssuedController::class, 'getDeptVoucher']);
Route::get('/goods_issued/print/{id}/{rid}', [GoodsIssuedController::class, 'getPrint']);
Route::get('/goods_issued/checkvchrno', [GoodsIssuedController::class, 'checkVchrNo']);
		
		
Route::get('/goods_return', [GoodsReturnController::class, 'index']);
Route::get('/goods_return/add', [GoodsReturnController::class, 'add']);
Route::post('/goods_return/save/{id}', [GoodsReturnController::class, 'save']);
Route::post('/goods_return/save', [GoodsReturnController::class, 'save']);
Route::get('/goods_return/edit/{id}', [GoodsReturnController::class, 'edit']);
Route::get('/goods_return/add/{id}', [GoodsReturnController::class, 'add']);
Route::get('/goods_return/viewonly/{id}', [GoodsReturnController::class, 'viewonly']);
Route::get('/goods_return/delete/{id}', [GoodsReturnController::class, 'destroy']);
Route::get('/goods_return/set_session', [GoodsReturnController::class, 'setSessionVal']);
Route::get('/goods_return/print/{id}', [GoodsReturnController::class, 'getPrint']);
Route::post('/goods_return/update/{id}', [GoodsReturnController::class, 'update']);
Route::post('/goods_return/search', [GoodsReturnController::class, 'getSearch']);
Route::post('/goods_return/export', [GoodsReturnController::class, 'dataExport']);
Route::post('/goods_return/paging', [GoodsReturnController::class, 'ajaxPaging']);
Route::get('/goods_return/getdeptvoucher/{id}', [GoodsReturnController::class, 'getDeptVoucher']);
		
		
Route::get('/job_report', [JobReportController::class, 'index']);
Route::post('/job_report/search', [JobReportController::class, 'getSearch']);
Route::post('/job_report/print', [JobReportController::class, 'getPrint']);
Route::post('/job_report/export', [JobReportController::class, 'dataExport']);
Route::get('/job_report/vehicleindex', [JobReportController::class, 'vehicleIndex']);
Route::get('/job_report/vehicle_search/{val}', [JobReportController::class, 'vehicleSearch']);
Route::get('/job_report/job_details/{id}', [JobReportController::class, 'jobDetails']);
		
		//work order report
Route::get('/job_report/jobindex', [JobReportController::class, 'jobIndex']);
Route::get('/job_report/sojob_search/{val}', [JobReportController::class, 'sojobSearch']);
Route::get('/job_report/workjob_details/{id}', [JobReportController::class, 'workjobDetails']);
		
		
Route::get('/utilities', [UtilityController::class, 'index']);
Route::post('/utilities/update/{type}', [UtilityController::class, 'update']);
Route::post('/utilities/updateAccMaster/{type}', [UtilityController::class, 'updateAccMaster']);
Route::post('/utilities/updateItemMasterStock/{type}', [UtilityController::class, 'updateItemMasterStock']);
Route::get('/utilities/item_log_add', [UtilityController::class, 'itemLogOBAdd']);
Route::get('/utilities/item_log_invadd', [UtilityController::class, 'itemLogInvAdd']);
Route::get('/utilities/item_log_unit_reset', [UtilityController::class, 'itemLogUnitReset']);
Route::get('/utilities/update_stmt', [UtilityController::class, 'statementUpdate']);
Route::get('/utilities/item_log_entry', [UtilityController::class, 'item_log_entry']);
Route::get('/utilities/update_pi_ref', [UtilityController::class, 'update_pi_ref']);
Route::get('/utilities/update_pv_ref', [UtilityController::class, 'update_pv_ref']);
Route::get('/utilities/ob_date_active', [UtilityController::class, 'ob_date_active']);
Route::post('/utilities/check-accounts', [UtilityController::class, 'checkAccounts']);
Route::post('/utilities/inventory-log-update/{doc}', [UtilityController::class, 'inventoryLogUpdate']);
Route::post('/utilities/update-cos/{doc}', [UtilityController::class, 'UpdateCOS']);
Route::post('/utilities/update-sih/{doc}', [UtilityController::class, 'UpdateSIH']);
		
		
Route::get('/pettycash', ['as' => 'pettycash.index', 'uses' => PettyCashController::class.'@index', 'middleware' => ['permission:pc-list|pc-create|pc-edit|pc-delete']]);
Route::post('/pettycash/paging', [PettyCashController::class, 'ajaxPaging']);
Route::get('/pettycash/add', ['as'=>'pettycash.add','uses'=>PettyCashController::class.'@add','middleware' => ['permission:pc-create']]);
Route::post('/pettycash/save', [PettyCashController::class, 'save']);
Route::get('/pettycash/getvoucher/{id}', [PettyCashController::class, 'getVoucher']);
Route::get('/pettycash/delete/{id}', ['as' => 'pettycash.destroy', 'uses' => PettyCashController::class.'@destroy', 'middleware' => ['permission:pc-delete']]);
Route::get('/pettycash/getvouchertype/{id}', [PettyCashController::class, 'getVoucherType']);
Route::get('/pettycash/edit/{id}', ['as' => 'pettycash.edit', 'uses' => PettyCashController::class.'@edit', 'middleware' => ['permission:pc-edit']]);
Route::post('/pettycash/update/{id}', [PettyCashController::class, 'update']);
Route::get('/pettycash/checkvchrno', [PettyCashController::class, 'checkVchrNo']);
Route::get('/pettycash/print/{id}', ['as' => 'pettycash.getPrint', 'uses' => PettyCashController::class.'@getPrint', 'middleware' => ['permission:pc-print']]);
Route::get('/pettycash/print/{id}/{rid}', ['as' => 'pettycash.getPrint', 'uses' => PettyCashController::class.'@getPrint', 'middleware' => ['permission:pc-print']]);
Route::get('/pettycash/printgrp/{id}', ['as' => 'pettycash.getGrpPrint', 'uses' => PettyCashController::class.'@getGrpPrint', 'middleware' => ['permission:pc-print']]);
Route::get('/pettycash/set_transactions/{type}/{id}/{n}', [PettyCashController::class, 'setTransactions']);
//Route::get('/pettycash/set_transactions/{type}/{id}/{n}/{descr}/{ref}/{tamt}', [PettyCashController::class, 'setTransactions']);

Route::get('/pettycash/quick-add', ['as'=>'pettycash.add','uses'=>PettyCashController::class.'@quickAdd','middleware' => ['permission:pc-create']]);

Route::get('/pettycash/add-pc', ['as'=>'pettycash.add','uses'=>PettyCashController::class.'@addPC','middleware' => ['permission:pc-create']]);
Route::get('/pettycash/edit-pc/{id}', ['as' => 'pettycash.edit', 'uses' => PettyCashController::class.'@edit', 'middleware' => ['permission:pc-edit']]);
		
Route::get('/advance_set/add', ['as'=>'advance_set.add','uses'=>AdvanceSetController::class.'@add','middleware' => ['permission:as-list|as-create']]);
Route::post('/advance_set/save', [AdvanceSetController::class, 'save']);
		
		
Route::get('/logdetails', [LogDetailsController::class, 'index']);
Route::post('/logdetails/search', [LogDetailsController::class, 'getSearch']);

		
Route::get('/purchase_voucher', ['as' => 'purchase_voucher.index', 'uses' => PurchaseVoucherController::class.'@index', 'middleware' => ['permission:vp-list|si-create|vp-edit|vp-delete']]);
Route::get('/purchase_voucher/add', ['as'=>'purchase_voucher.add','uses'=>PurchaseVoucherController::class.'@add','middleware' => ['permission:vp-create']]);
Route::post('/purchase_voucher/save', [PurchaseVoucherController::class, 'save']);
Route::get('/purchase_voucher/delete/{id}', ['as' => 'purchase_voucher.destroy', 'uses' => PurchaseVoucherController::class.'@destroy', 'middleware' => ['permission:vp-delete']]);
Route::get('/purchase_voucher/edit/{id}', ['as' => 'purchase_voucher.edit', 'uses' => PurchaseVoucherController::class.'@edit', 'middleware' => ['permission:vp-edit']]);
Route::post('/purchase_voucher/update/{id}', [PurchaseVoucherController::class, 'update']);
Route::get('/purchase_voucher/getdeptvoucher/{id}', [PurchaseVoucherController::class, 'getDeptVoucher']);
//Route::get('/purchase_voucher/print/{id}', [PurchaseVoucherController::class, 'getPrint']);
Route::get('/purchase_voucher/print/{id}/{rid}', [PurchaseVoucherController::class, 'getPrint']);
Route::get('/purchase_voucher/set_transactions/{type}/{id}/{n}', [PurchaseVoucherController::class, 'setTransactions']);
		
		
Route::get('/sales_voucher', ['as' => 'sales_voucher.index', 'uses' => SalesVoucherController::class.'@index', 'middleware' => ['permission:vs-list|vs-create|vs-edit|vs-delete']]);
Route::get('/sales_voucher/add', ['as'=>'sales_voucher.add','uses'=>SalesVoucherController::class.'@add','middleware' => ['permission:vs-create']]);
Route::post('/sales_voucher/save', [SalesVoucherController::class, 'save']);
Route::get('/sales_voucher/delete/{id}', ['as' => 'sales_voucher.destroy', 'uses' => SalesVoucherController::class.'@destroy', 'middleware' => ['permission:vs-delete']]);
Route::get('/sales_voucher/edit/{id}', ['as' => 'sales_voucher.edit', 'uses' => SalesVoucherController::class.'@edit', 'middleware' => ['permission:vs-edit']]);
Route::post('/sales_voucher/update/{id}', [SalesVoucherController::class, 'update']);
Route::get('/sales_voucher/getdeptvoucher/{id}', [SalesVoucherController::class, 'getDeptVoucher']);
//Route::get('/sales_voucher/print/{id}', [SalesVoucherController::class, 'getPrint']);
Route::get('/sales_voucher/print/{id}/{rid}', [SalesVoucherController::class, 'getPrint']);
Route::post('/sales_voucher/paging', [SalesVoucherController::class, 'ajaxPaging']);
Route::get('/sales_voucher/set_transactions/{type}/{id}/{n}', [SalesVoucherController::class, 'setTransactions']);
		
		
Route::get('/ledger_moments', [LedgerMomentsController::class, 'index']);
Route::post('/ledger_moments/search', [LedgerMomentsController::class, 'getSearch']);
Route::post('/ledger_moments/print', [LedgerMomentsController::class, 'getPrint']);
Route::post('/ledger_moments/export', [LedgerMomentsController::class, 'dataExport']);
		
Route::get('/pdc_report', [PdcReportController::class, 'index']);
Route::post('/pdc_report/search', [PdcReportController::class, 'getSearch']);
Route::post('/pdc_report/print', [PdcReportController::class, 'getPrint']);
Route::post('/pdc_report/export', [PdcReportController::class, 'dataExport']);
		
Route::get('/document_report', [DocumentReportController::class, 'index']);
Route::post('/document_report/search', [DocumentReportController::class, 'getSearch']);
Route::post('/document_report/print', [DocumentReportController::class, 'getPrint']);
Route::post('/document_report/export', [DocumentReportController::class, 'dataExport']);
		
Route::get('/backup', [BackupController::class, 'index']);
Route::post('/backup/submit', [BackupController::class, 'submit']);
		
Route::get('/other_account_setting', [OtherAccountSettingController::class, 'index']);
Route::post('/other_account_setting/update', [OtherAccountSettingController::class, 'update']);
		
Route::get('/voucher_numbers', [VoucherNumbersController::class, 'index']);
Route::post('/voucher_numbers/update', [VoucherNumbersController::class, 'update']);
		
Route::get('/permission/edit/{id}', [PermissionController::class, 'edit']);
Route::post('/permission/update', [PermissionController::class, 'update']);
		
Route::get('/year_ending', [YearendingController::class, 'index']);
Route::post('/year_ending/backup', [YearendingController::class, 'backup']);
Route::get('/year_ending/step1', [YearendingController::class, 'step1']);
Route::get('/year_ending/step2', [YearendingController::class, 'step2']);
Route::post('/year_ending/step2_submit', [YearendingController::class, 'step2Submit']);
Route::get('/year_ending/step3/{id}', [YearendingController::class, 'step3']);
Route::post('/year_ending/step3_submit', [YearendingController::class, 'step3Submit']);
Route::get('/year_ending/step4', [YearendingController::class, 'step4']);
Route::post('/year_ending/step4_submit', [YearendingController::class, 'step4Submit']);
		
		
Route::get('/year_endingquick', [YearendingquickController::class, 'index']);
Route::post('/year_ending/quickbackup', [YearendingquickController::class, 'backup']);
Route::get('/year_endingquick/step2', [YearendingquickController::class, 'step2']);
Route::post('/year_endingquick/step2_quicksubmit', [YearendingquickController::class, 'step2Submit']);
		
Route::get('/job_estimate', ['as' => 'job_estimate.index', 'uses' => JobEstimateController::class.'@index', 'middleware' => ['permission:qs-list|qs-create|qs-edit|qs-delete']]);
Route::get('/job_estimate/add', ['as'=>'job_estimate.add','uses'=>JobEstimateController::class.'@add','middleware' => ['permission:qs-create']]);
Route::get('/job_estimate/add/{id}/{n}',['as'=>'job_estimate.add','uses'=>JobEstimateController::class.'@add','middleware' => ['permission:qs-create']]);
Route::post('/job_estimate/save', ['as' => 'job_estimate.save', 'uses' => JobEstimateController::class.'@save', 'middleware' => ['permission:qs-create']] );
Route::get('/job_estimate/edit/{id}', ['as' => 'job_estimate.edit', 'uses' => JobEstimateController::class.'@edit', 'middleware' => ['permission:qs-edit']]);
Route::post('/job_estimate/update/{id}', ['as' => 'job_estimate.update', 'uses' => JobEstimateController::class.'@update', 'middleware' => ['permission:qs-edit']]);
Route::get('/job_estimate/customer_data', [JobEstimateController::class, 'getCustomer']);
Route::get('/job_estimate/salesman_data', [JobEstimateController::class, 'getSalesman']);
Route::get('/job_estimate/item_data/{id}', [JobEstimateController::class, 'getItem']);
Route::get('/job_estimate/checkrefno', [JobEstimateController::class, 'checkRefNo']);
Route::get('/job_estimate/delete/{id}', ['as' => 'job_estimate.destroy', 'uses' => JobEstimateController::class.'@destroy', 'middleware' => ['permission:qs-delete']]);
Route::get('/job_estimate/get_quotation/{id}/{url}', [JobEstimateController::class, 'getQuotation']);
Route::get('/job_estimate/item_details/{id}', [JobEstimateController::class, 'getItemDetails']);
Route::get('/job_estimate/get_estimate', [JobEstimateController::class, 'getEstimate']);
Route::post('/job_estimate/upload', [JobEstimateController::class, 'uploadSubmit']);

Route::get('/job_estimate/print/{id}', ['as' => 'job_estimate.getPrint', 'uses' => JobEstimateController::class.'@getPrint', 'middleware' => ['permission:qs-print']]);
Route::post('/job_estimate/search', [JobEstimateController::class, 'getSearch']);
Route::get('/job_estimate/print/{id}/{fc}', [JobEstimateController::class, 'getPrint']);
Route::post('/job_estimate/export', ['as' => 'job_estimate.dataExport', 'uses' => JobEstimateController::class.'@dataExport', 'middleware' => ['permission:qs-print']]);
Route::get('/job_estimate/ajax_create', [JobEstimateController::class, 'ajaxCreate']);
Route::post('/job_estimate/vehsearch', [JobEstimateController::class, 'getvehSearch']);
Route::post('/job_estimate/paging', [JobEstimateController::class, 'ajaxPaging']);
		
Route::get('/job_estimate/docs/{id}', [JobEstimateController::class, 'getDocs']); //OCT24
Route::get('/job_estimate/views/{id}',[JobEstimateController::class, 'getViews']);
Route::get('/job_estimate/add/{id}', [JobEstimateController::class, 'add']);
		
		
Route::get('/job_order', ['as' => 'job_order.index', 'uses' => JobOrderController::class.'@index', 'middleware' => ['permission:job-order-list|job-order-create|job-order-edit|job-order-delete']]);
Route::get('/job_order/add', ['as'=>'job_order.add','uses'=>JobOrderController::class.'@add','middleware' => ['permission:job-order-create']]);
Route::get('/job_order/add/{id}', ['as'=>'job_order.add','uses'=>JobOrderController::class.'@add','middleware' => ['permission:job-order-create']]);
Route::get('/job_order/add/{id}/{n}', ['as'=>'job_order.add','uses'=>JobOrderController::class.'@add','middleware' => ['permission:job-order-create']]);
Route::post('/job_order/save', ['as' => 'job_order.save', 'uses' => JobOrderController::class.'@save', 'middleware' => ['permission:job-order-create']] );
Route::get('/job_order/edit/{id}', ['as' => 'job_order.edit', 'uses' => JobOrderController::class.'@edit', 'middleware' => ['permission:job-order-edit']]);
Route::post('/job_order/update/{id}', ['as' => 'job_order.update', 'uses' => JobOrderController::class.'@update', 'middleware' => ['permission:job-order-edit']]);
Route::get('/job_order/viewonly/{id}', ['as' => 'job_order.viewonly', 'uses' => JobOrderController::class.'@viewonly', 'middleware' => ['permission:job-order-view']]);
Route::get('/job_order/customer_data', [JobOrderController::class, 'getCustomer']);
Route::get('/job_order/salesman_data', [JobOrderController::class, 'getSalesman']);
Route::get('/job_order/item_data/{id}', [JobOrderController::class, 'getItem']);
Route::get('/job_order/checkrefno', [JobOrderController::class, 'checkRefNo']);
Route::get('/job_order/delete/{id}', ['as' => 'job_order.destroy', 'uses' => JobOrderController::class.'@destroy', 'middleware' => ['permission:job-order-delete']]);
Route::get('/job_order/get_order/{id}/{n}', [JobOrderController::class, 'getOrder']);
Route::get('/job_order/item_details/{id}', [JobOrderController::class, 'getItemDetails']);
Route::get('/job_order/getjo', [JobOrderController::class, 'getJobOrder']);
Route::get('/job_order/print/{id}', ['as' => 'job_order.getPrint', 'uses' => JobOrderController::class.'@getPrint', 'middleware' => ['permission:job-order-print']]);
Route::get('/job_order/set_session', [JobOrderController::class, 'setSessionVal']);
Route::post('/job_order/search', [JobOrderController::class, 'getSearch']);
Route::post('/job_order/export', [JobOrderController::class, 'dataExport']);
Route::get('/job_order/print/{id}/{fc}', ['as' => 'job_order.getPrint', 'uses' => JobOrderController::class.'@getPrint', 'middleware' => ['permission:job-order-print']]);
//Route::post('/job_order/export', ['as' => 'job_order.dataExport', 'uses' => JobOrderController::class.'@dataExport', 'middleware' => ['permission:job-order-export']]);
Route::get('/job_order/vehicle_data/{id}', [JobOrderController::class, 'getVehicle']);
Route::post('/job_order/vehsearch', [JobOrderController::class, 'getvehSearch']);
Route::get('/job_order/vehicle_form', [JobOrderController::class, 'getVehicleForm']);
Route::get('/job_order/ajax_create', [JobOrderController::class, 'ajaxCreate']);
Route::post('/job_order/paging', [JobOrderController::class, 'ajaxPaging']);
Route::get('/job_order/all_vehicle', [JobOrderController::class, 'getAllVehicle']);
Route::post('/job_order/upload', [JobOrderController::class, 'uploadSubmit']);
Route::get('/job_order/set_technician', [JobOrderController::class, 'setTechnician']);
Route::post('/job_order/get_fileform', [JobOrderController::class, 'getFileform']);
Route::get('/job_order/report', [JobOrderController::class, 'getReport']);
Route::post('/job_order/report', [JobOrderController::class, 'getJobSearch']);
Route::get('/job_order/{type}', [JobOrderController::class, 'getTechnicianJob']);
Route::post('/job_order/update_status', [JobOrderController::class, 'updateStatus']);
Route::get('/job_order/jobsearch/{val}/{type}', [JobOrderController::class, 'SearchJob']);
Route::get('/job_order/getvehicle/{id}', [JobOrderController::class, 'getVehicleData']);
Route::get('/job_order/vehiclejob/{id}', [JobOrderController::class, 'getVehicleJob']);
Route::get('/job_order/jobsearch_tech/{val}/{type}', [JobOrderController::class, 'SearchJobTech']);
Route::post('/job_order/get_fileform/{si}', [JobOrderController::class, 'getFileform']);
		
Route::get('/job_order/docs/{id}', [JobOrderController::class, 'getDocs']); //OCT24
		
		
		
Route::get('/job_invoice', ['as' => 'job_invoice.index', 'uses' => JobInvoiceController::class.'@index', 'middleware' => ['permission:job-invoice-list|job-invoice-create|job-invoice-edit|job-invoice-delete']]);
Route::get('/job_invoice/add', ['as'=>'job_invoice.add','uses'=>JobInvoiceController::class.'@add','middleware' => ['permission:job-invoice-create']]);
Route::get('/job_invoice/add/{id}/{n}', ['as'=>'job_invoice.add','uses'=>JobInvoiceController::class.'@add','middleware' => ['permission:job-invoice-create']]);
Route::post('/job_invoice/save', ['as' => 'job_invoice.save', 'uses' => JobInvoiceController::class.'@save', 'middleware' => ['permission:job-invoice-create']]);
Route::get('/job_invoice/edit/{id}', ['as' => 'job_invoice.edit', 'uses' => JobInvoiceController::class.'@edit', 'middleware' => ['permission:job-invoice-edit']]);
Route::post('/job_invoice/update/{id}', ['as' => 'job_invoice.update', 'uses' => JobInvoiceController::class.'@update', 'middleware' => ['permission:job-invoice-edit']]);
Route::get('/job_invoice/viewonly/{id}', ['as' => 'job_invoice.viewonly', 'uses' => JobInvoiceController::class.'@viewonly', 'middleware' => ['permission:job-invoice-view']]);
Route::get('/job_invoice/item_info/{id}', [JobInvoiceController::class, 'getIteminfo']);
Route::post('/job_invoice/info_save/{id}',  [JobInvoiceController::class, 'getSaveinfo']);
		
Route::get('/job_invoice/customer_data', [JobInvoiceController::class, 'getCustomer']);
Route::get('/job_invoice/customer_data/{no}', [JobInvoiceController::class, 'getCustomer']);
Route::get('/job_invoice/salesman_data', [JobInvoiceController::class, 'getSalesman']);
Route::get('/job_invoice/item_data/{id}', [JobInvoiceController::class, 'getItem']);
Route::get('/job_invoice/checkrefno', [JobInvoiceController::class, 'checkRefNo']);
Route::get('/job_invoice/delete/{id}', ['as' => 'job_invoice.destroy', 'uses' => JobInvoiceController::class.'@destroy', 'middleware' => ['permission:job-invoice-delete']]);
Route::get('/job_invoice/getvoucher/{id}', [JobInvoiceController::class, 'getVoucher']);
Route::get('/job_invoice/invoice_data', [JobInvoiceController::class, 'getInvoice']);
Route::get('/job_invoice/item_details/{id}', [JobInvoiceController::class, 'getItemDetails']);
Route::get('/job_invoice/get_jobinvoice', [JobInvoiceController::class, 'getJobInvoice']);
Route::get('/job_invoice/get_invoice/{id}', [JobInvoiceController::class, 'getInvoiceByCustomer']);
Route::get('/job_invoice/check_invoice', [JobInvoiceController::class, 'checkInvoice']);
Route::get('/job_invoice/set_session', [JobInvoiceController::class, 'setSessionVal']);
Route::get('/job_invoice/print/{id}', ['as' => 'job_invoice.getPrint', 'uses' => JobInvoiceController::class.'@getPrint', 'middleware' => ['permission:job-invoice-print']]);
Route::get('/job_invoice/printdo/{id}', [JobInvoiceController::class, 'getPrintdo']);
Route::get('/job_invoice/tstprint', [JobInvoiceController::class, 'tstprint']);
Route::get('/job_invoice/get_invoice/{id}/{n}', [JobInvoiceController::class, 'getInvoiceByCustomer']);
Route::get('/job_invoice/order_history/{id}', [JobInvoiceController::class, 'getOrderHistory']);
Route::get('/job_invoice/checkvchrno', [JobInvoiceController::class, 'checkVchrNo']);
Route::get('/job_invoice/get_invoiceset/{id}', [JobInvoiceController::class, 'getInvoiceSetByCustomer']);
Route::post('/job_invoice/search', [JobInvoiceController::class, 'getSearch']);
Route::get('/job_invoice/print/{id}/{fc}', ['as' => 'job_invoice.getPrint', 'uses' => JobInvoiceController::class.'@getPrint', 'middleware' => ['permission:job-invoice-print']]);
//Route::post('/job_invoice/export', ['as' => 'job_invoice.dataExport', 'uses' => JobInvoiceController::class.'@dataExport', 'middleware' => ['permission:pi-export']]);
Route::get('/job_invoice/getdeptvoucher/{id}', [JobInvoiceController::class, 'getDeptVoucher']);
Route::post('/job_invoice/paging', [JobInvoiceController::class, 'ajaxPaging']);
Route::post('/job_invoice/export', [JobInvoiceController::class, 'dataExport']);
Route::get('/job_invoice/docs/{id}', [JobInvoiceController::class, 'getDocs']); //OCT24
		
Route::get('/location_transfer', [LocationTransferController::class, 'index']);
Route::get('/location_transfer/add', [LocationTransferController::class, 'add']);
Route::post('/location_transfer/save', [LocationTransferController::class, 'save']);
Route::get('/location_transfer/checkrefno', [LocationTransferController::class, 'checkRefNo']);
Route::get('/location_transfer/delete/{id}', [LocationTransferController::class, 'destroy']);
Route::get('/location_transfer/edit/{id}', [LocationTransferController::class, 'edit']);
Route::post('/location_transfer/update/{id}', [LocationTransferController::class, 'update']);
Route::get('/location_transfer/print/{id}', [LocationTransferController::class, 'getPrint']);
		
Route::get('/package_master', [PackageMasterController::class, 'index']);
Route::get('/package_master/add', [PackageMasterController::class, 'add']);
Route::post('/package_master/save', [PackageMasterController::class, 'save']);
Route::get('/package_master/edit/{id}', [PackageMasterController::class, 'edit']);
Route::post('/package_master/update/{id}', [PackageMasterController::class, 'update']);
Route::get('/package_master/delete/{id}', [PackageMasterController::class, 'destroy']);
		
		
Route::get('/stock_transferin', [StockTransferinController::class, 'index']);
Route::get('/stock_transferin/add', [StockTransferinController::class, 'add']);
Route::post('/stock_transferin/save', [StockTransferinController::class, 'save']);
Route::get('/stock_transferin/checkrefno', [StockTransferinController::class, 'checkRefNo']);
Route::get('/stock_transferin/delete/{id}', [StockTransferinController::class, 'destroy']);
Route::get('/stock_transferin/edit/{id}', [StockTransferinController::class, 'edit']);
Route::post('/stock_transferin/update/{id}', [StockTransferinController::class, 'update']);
Route::get('/stock_transferin/viewonly/{id}', [StockTransferinController::class, 'viewonly']);
Route::get('/stock_transferin/print/{id}', [StockTransferinController::class, 'getPrint']);
Route::get('/stock_transferin/getdeptvoucher/{id}', [StockTransferinController::class, 'getDeptVoucher']);
Route::post('/stock_transferin/search', [StockTransferinController::class, 'getSearch']);
Route::post('/stock_transferin/export', [StockTransferinController::class, 'dataExport']);
Route::get('/stock_transferin/checkvchrno', [StockTransferinController::class, 'checkVchrNo']);
		
Route::get('/stock_transferout', [StockTransferoutController::class, 'index']);
Route::get('/stock_transferout/add', [StockTransferoutController::class, 'add']);
Route::post('/stock_transferout/save', [StockTransferoutController::class, 'save']);
Route::get('/stock_transferout/checkrefno', [StockTransferoutController::class, 'checkRefNo']);
Route::get('/stock_transferout/delete/{id}', [StockTransferoutController::class, 'destroy']);
Route::get('/stock_transferout/edit/{id}', [StockTransferoutController::class, 'edit']);
Route::post('/stock_transferout/update/{id}', [StockTransferoutController::class, 'update']);
Route::get('/stock_transferout/viewonly/{id}', [StockTransferoutController::class, 'viewonly']);
Route::get('/stock_transferout/print/{id}', [StockTransferoutController::class, 'getPrint']);
Route::get('/stock_transferout/getdeptvoucher/{id}', [StockTransferoutController::class, 'getDeptVoucher']);
Route::post('/stock_transferout/search', [StockTransferoutController::class, 'getSearch']);
Route::post('/stock_transferout/export', [StockTransferoutController::class, 'dataExport']);
Route::get('/stock_transferout/checkvchrno', [StockTransferoutController::class, 'checkVchrNo']);
		
		
Route::get('/importdata/items', [ImportDataController::class, 'importItems']);
Route::post('/importdata/save', [ImportDataController::class, 'save']);
Route::get('/importdata/accounts', [ImportDataController::class, 'importAccounts']);
Route::get('/importdata/accounts_master', [ImportDataController::class, 'importAccountMaster']);
Route::get('/importdata/con-loc-stock', [ImportDataController::class, 'importConLocStock']);
Route::get('/importdata/opn-balance', [ImportDataController::class, 'importOpnBalance']);
Route::get('/importdata/opn-balance-sup', [ImportDataController::class, 'importOpnBalanceSup']);
Route::get('/importdata/cust-vehicle', [ImportDataController::class, 'importCustVehicle']);
Route::get('/importdata/jobmaster', [ImportDataController::class, 'importJobmaster']);
Route::get('/importdata/joborder', [ImportDataController::class, 'importJoborder']);
Route::get('/importdata/tallyitems', [ImportDataController::class, 'importTallyItems']);
		
//Route::get('/forms/{n}', [FormManagerController::class, 'index']);
Route::get('/forms', [FormManagerController::class, 'index']);
Route::get('/forms/detail/{n}', [FormManagerController::class, 'detail']);
Route::post('/forms/update', [FormManagerController::class, 'update']);
		
Route::get('itemmaster/item_apiadd', [ItemmasterController::class, 'item_apiadd']);
		
Route::get('/credit_note', [CreditNoteController::class, 'index']);
Route::get('/credit_note/add', [CreditNoteController::class, 'add']);
Route::post('/credit_note/save', [CreditNoteController::class, 'save']);
Route::get('/credit_note/delete/{id}', [CreditNoteController::class, 'destroy']);
Route::get('/credit_note/edit/{id}', [CreditNoteController::class, 'edit']);
Route::post('/credit_note/update/{id}', [CreditNoteController::class, 'update']);
Route::get('/credit_note/print/{id}', [CreditNoteController::class, 'getPrint']);
Route::get('/credit_note/getdeptvoucher/{id}', [CreditNoteController::class, 'getDeptVoucher']);
Route::get('/credit_note/print/{id}/{rid}', [CreditNoteController::class, 'getPrint']);



		
Route::get('/debit_note', [DebitNoteController::class, 'index']);
Route::get('/debit_note/add', [DebitNoteController::class, 'add']);
Route::post('/debit_note/save', [DebitNoteController::class, 'save']);
Route::get('/debit_note/delete/{id}', [DebitNoteController::class, 'destroy']);
Route::get('/debit_note/edit/{id}', [DebitNoteController::class, 'edit']);
Route::post('/debit_note/update/{id}', [DebitNoteController::class, 'update']);
//Route::get('/debit_note/print/{id}', [DebitNoteController::class, 'getPrint']);
Route::get('/debit_note/getdeptvoucher/{id}', [DebitNoteController::class, 'getDeptVoucher']);
Route::get('/debit_note/print/{id}/{rid}', [DebitNoteController::class, 'getPrint']);


Route::get('/sales_rental', [SalesRentalController::class, 'index']);
Route::get('/sales_rental/add', [SalesRentalController::class, 'add']);
Route::get('/sales_rental/add/{id}', [SalesRentalController::class, 'add']);
Route::get('/sales_rental/add/{id}/{n}', [SalesRentalController::class, 'add']);
Route::post('/sales_rental/save', [SalesRentalController::class, 'save']);
Route::get('/sales_rental/edit/{id}', [SalesRentalController::class, 'edit']);
Route::post('/sales_rental/update/{id}', [SalesRentalController::class, 'update']);
Route::get('/sales_rental/delete/{id}', [SalesRentalController::class, 'destroy']);
Route::post('/sales_rental/paging', [SalesRentalController::class, 'ajaxPaging']);
Route::get('/sales_rental/print/{id}', [SalesRentalController::class, 'getPrint']);
Route::get('/sales_rental/print/{id}/{fc}', [SalesRentalController::class, 'getPrint']);
Route::get('/sales_rental/customer_data', [SalesRentalController::class, 'getCustomer']);
Route::get('/sales_rental/customer_data/{no}', [SalesRentalController::class, 'getCustomer']);
Route::get('/sales_rental/salesman_data', [SalesRentalController::class, 'getSalesman']);
Route::get('/sales_rental/item_data/{id}', [SalesRentalController::class, 'getItem']);
Route::get('/sales_rental/checkrefno', [SalesRentalController::class, 'checkRefNo']);
Route::get('/sales_rental/getvoucher/{id}', [SalesRentalController::class, 'getVoucher']);
Route::get('/sales_rental/invoice_data', [SalesRentalController::class, 'getInvoice']);
Route::get('/sales_rental/item_details/{id}', [SalesRentalController::class, 'getItemDetails']);
Route::get('/sales_rental/get_invoice/{id}', [SalesRentalController::class, 'getInvoiceByCustomer']);
Route::get('/sales_rental/check_invoice', [SalesRentalController::class, 'checkInvoice']);
Route::post('/sales_rental/set_session', [SalesRentalController::class, 'setSessionVal']);
Route::get('/sales_rental/printdo/{id}', [SalesRentalController::class, 'getPrintdo']);
Route::get('/sales_rental/tstprint', [SalesRentalController::class, 'tstprint']);
Route::get('/sales_rental/get_invoice/{id}/{n}', [SalesRentalController::class, 'getInvoiceByCustomer']);
Route::get('/sales_rental/order_history/{id}', [SalesRentalController::class, 'getOrderHistory']);
Route::get('/sales_rental/checkvchrno', [SalesRentalController::class, 'checkVchrNo']);
Route::get('/sales_rental/get_invoiceset/{id}', [SalesRentalController::class, 'getInvoiceSetByCustomer']);
Route::post('/sales_rental/search', [SalesRentalController::class, 'getSearch']);
Route::post('/sales_rental/export', [SalesRentalController::class, 'dataExport']);
Route::get('/sales_rental/getsaleloc/{id}', [SalesRentalController::class, 'getSaleLocation']);
Route::get('/sales_rental/get_trnno/{name}', [SalesRentalController::class, 'getTrnno']);
Route::get('/sales_rental/cust_history/{id}', [SalesRentalController::class, 'getCustHistory']);
Route::get('/sales_rental/ajax_customer', [SalesRentalController::class, 'getAjaxCust']);
Route::get('/sales_rental/getdeptvoucher/{id}', [SalesRentalController::class, 'getDeptVoucher']);
Route::get('/sales_rental/invoice_data/{did}', [SalesRentalController::class, 'getInvoice']);
Route::get('/sales_rental/customer_datadpt/{dpt}', [SalesRentalController::class, 'getCustomerDpt']);

		
Route::get('/quotation_rental', [QuotationRentalController::class, 'index']);
Route::get('/quotation_rental/add', [QuotationRentalController::class, 'add']);
Route::get('/quotation_rental/add/{id}/{n}', [QuotationRentalController::class, 'add']);
Route::post('/quotation_rental/save', [QuotationRentalController::class, 'save']);
Route::get('/quotation_rental/edit/{id}', [QuotationRentalController::class, 'edit']);
Route::post('/quotation_rental/update/{id}', [QuotationRentalController::class, 'update']);
Route::get('/quotation_rental/delete/{id}', [QuotationRentalController::class, 'destroy']);
Route::get('/quotation_rental/customer_data', [QuotationRentalController::class, 'getCustomer']);
Route::get('/quotation_rental/salesman_data', [QuotationRentalController::class, 'getSalesman']);
Route::get('/quotation_rental/item_data/{id}', [QuotationRentalController::class, 'getItem']);
Route::get('/quotation_rental/checkrefno', [QuotationRentalController::class, 'checkRefNo']);
Route::get('/quotation_rental/get_quotation/{id}/{url}', [QuotationRentalController::class, 'getQuotation']);
Route::get('/quotation_rental/item_details/{id}', [QuotationRentalController::class, 'getItemDetails']);
Route::get('/quotation_rental/print/{id}', [QuotationRentalController::class, 'getPrint']);
Route::get('/quotation_rental/print/{id}/{fc}', [QuotationRentalController::class, 'getPrint']);
Route::post('/quotation_rental/search', [QuotationRentalController::class, 'getSearch']);
Route::get('/quotation_rental/checkvchrno', [QuotationRentalController::class, 'checkVchrNo']);
Route::post('/quotation_rental/paging', [QuotationRentalController::class, 'ajaxPaging']);
		
		

Route::get('/wage_entry', [WageEntryController::class, 'index']);
Route::get('/wage_entry/add', [WageEntryController::class, 'add']);
Route::post('/wage_entry/save', [WageEntryController::class, 'save']);
Route::get('/wage_entry/delete/{id}', [WageEntryController::class, 'destroy']);
Route::get('/wage_entry/edit/{id}', [WageEntryController::class, 'edit']);
Route::post('/wage_entry/update/{id}', [WageEntryController::class, 'update']);
Route::get('/pay_slip', [PaySlipController::class, 'index']);
Route::get('/pay_slip/add', [PaySlipController::class, 'add']);
Route::post('/pay_slip/search', [PaySlipController::class, 'searchEmp']);
Route::get('/pay_slip/employee/{id}/{m}/{y}', [PaySlipController::class, 'employeeSlip']);
		
		
Route::get('/wage_entry/timesheet', [WageEntryController::class, 'timesheetadd']);
Route::get('/wage_entry/timesheet/{eid}/{cid}', [WageEntryController::class, 'timesheetadd']);
Route::post('/wage_entry/timesheet/save', [WageEntryController::class, 'timesheetSave']);
Route::get('/wage_entry/subjob_template/{jid}/{n}', [WageEntryController::class, 'subJobTemplate']);
Route::post('/wage_entry/subjob_template/save', [WageEntryController::class, 'subJobTemplateSave']);
Route::get('/wage_entry/subjob_template/edit/{jid}/{n}/{wid}', [WageEntryController::class, 'subJobTemplateEdit']);
Route::get('/wage_entry/subjob_template/view/{n}/{wid}/{tid}', [WageEntryController::class, 'subJobTemplateView']);
Route::get('/wage_entry/timesheet/edit', [WageEntryController::class, 'timesheetEdit']);
Route::get('/wage_entry/timesheet/edit/{eid}/{cid}/{m}', [WageEntryController::class, 'timesheetEdit']);
Route::post('/wage_entry/timesheet/update', [WageEntryController::class, 'timesheetUpdate']);
Route::get('/wage_entry/timesheet/view', [WageEntryController::class, 'timesheetView']);
Route::get('/wage_entry/timesheet/view/{eid}/{cid}/{m}', [WageEntryController::class, 'timesheetView']);
Route::post('/wage_entry/timesheet/approve', [WageEntryController::class, 'timesheetApprove']);
		
Route::get('/wage_entry/timesheet/leave', [WageEntryController::class, 'timesheetLeave']);
Route::post('/wage_entry/timesheet/leave_search', [WageEntryController::class, 'timesheetLeaveSearch']);
Route::get('/wage_entry/time/leave_edit/{id}', [WageEntryController::class, 'timesheetLeaveEdit']);
Route::post('/wage_entry/time/leave_update/{id}', [WageEntryController::class, 'timesheetLeaveUpdate']);
Route::post('/wage_entry/leave/upload', [WageEntryController::class, 'uploadSubmit']);
Route::get('/wage_entry/leave/approve/{id}', [WageEntryController::class, 'timesheetLeaveApprove']);
		
		
Route::get('/timesheet_report', [TimesheetReportController::class, 'index']);
Route::post('/timesheet_report/search', [TimesheetReportController::class, 'getSearch']);
Route::get('/timesheet_report/payroll', [TimesheetReportController::class, 'payroll']);
Route::post('/timesheet_payrollreport/search', [TimesheetReportController::class, 'getpayrollSearch']);
		
		
		
Route::get('/payroll_report', [PayrollReportController::class, 'index']);
Route::post('/payroll_report/search', [PayrollReportController::class, 'getSearch']);
Route::get('/payroll_report/job', [PayrollReportController::class, 'jobForm']);
Route::post('/payroll_report/jobsearch', [PayrollReportController::class, 'jobSearch']);
		

Route::get('/emp_report', [WpsReportController::class, 'index']);
Route::post('/empreport/search',[WpsReportController::class, 'getSearch']);
Route::post('/empreport/export',[WpsReportController::class, 'dataExport']);


Route::get('/document_report/search_form', [DocumentReportController::class, 'searchForm']);
Route::post('/document_report/search_result', [DocumentReportController::class, 'searchResult']);
		
Route::get('/design', [DesignController::class, 'index']);
//Route::get('/design/view', [DesignController::class, 'viewer']);
//Route::get('/design/view/{id}', [DesignController::class, 'viewer']);
Route::get('/design/{id}', [DesignController::class, 'index']);
		
Route::get('/update_app/rv_modificaton', [UpdateController::class, 'RVmodificationFix']);
Route::get('/update_app/pv_modificaton', [UpdateController::class, 'PVmodificationFix']);
		
Route::get('/vehicle', [VehicleController::class, 'index']);
Route::get('/vehicle/add', [VehicleController::class, 'add']);
		
Route::post('/vehicle/save', [VehicleController::class, 'save']);
Route::get('/vehicle/edit/{id}', [VehicleController::class, 'edit']);
Route::post('/vehicle/update/{id}', [VehicleController::class, 'update']);
Route::get('/vehicle/delete/{id}', [VehicleController::class, 'destroy']);
Route::get('/vehicle/getenquiry', [VehicleController::class, 'getEnquiry']);
Route::get('/vehicle/gethistory/{id}', [VehicleController::class, 'getvehicleHistory']);
Route::get('/vehicle/checkregno', [VehicleController::class, 'checkregno']);
		
Route::get('/jobtype', [JobtypeController::class, 'index']);
Route::get('/jobtype/add', [JobtypeController::class, 'add']);
Route::post('/jobtype/save', [JobtypeController::class, 'save']);
Route::get('/jobtype/edit/{id}', [JobtypeController::class, 'edit']);
Route::post('/jobtype/update/{id}', [JobtypeController::class, 'update']);
Route::get('/jobtype/delete/{id}', [JobtypeController::class, 'destroy']);
Route::get('/jobtype/getjobno/{id}', [JobtypeController::class, 'getJobNo']);
		
Route::get('/document_master', [DocumentMasterController::class, 'index']);
Route::get('/document_master/add', [DocumentMasterController::class, 'add']);
Route::post('/document_master/save', [DocumentMasterController::class, 'save']);
Route::get('/document_master/delete/{id}', [DocumentMasterController::class, 'destroy']);
Route::get('/document_master/edit/{id}', [DocumentMasterController::class, 'edit']);
Route::post('/document_master/update/{id}', [DocumentMasterController::class, 'update']);
Route::get('/document_master/checkname', [DocumentMasterController::class, 'checkname']);
Route::get('/document_master/get_expinfo', [DocumentMasterController::class, 'getExpinfo']);
Route::get('/document_master/checkcode', [DocumentMasterController::class, 'checkcode']);
Route::post('/document_master/search', [DocumentMasterController::class, 'getSearch']);
		
Route::get('/doctype', [DoctypeController::class, 'index']);
Route::get('/doctype/add', [DoctypeController::class, 'add']);
Route::post('/doctype/save', [DoctypeController::class, 'save']);
Route::get('/doctype/edit/{id}', [DoctypeController::class, 'edit']);
Route::post('/doctype/update/{id}', [DoctypeController::class, 'update']);
Route::get('/doctype/delete/{id}', [DoctypeController::class, 'destroy']);
		
Route::get('/assets_issued', [AssetsIssuedController::class, 'index']);
Route::get('/assets_issued/add', [AssetsIssuedController::class, 'add']);
Route::post('/assets_issued/save', [AssetsIssuedController::class, 'save']);
Route::get('/assets_issued/edit/{id}', [AssetsIssuedController::class, 'edit']);
Route::post('/assets_issued/update/{id}', [AssetsIssuedController::class, 'update']);
Route::get('/assets_issued/delete/{id}', [AssetsIssuedController::class, 'destroy']);
		
		
Route::get('/customer_enquiry', ['as' => 'customer_enquiry.index', 'uses' => CustomerEnquiryController::class.'@index', 'middleware' => ['permission:pi-list|qs-create|qs-edit|qs-delete']]);
Route::get('/customer_enquiry/add', ['as'=>'customer_enquiry.add','uses'=>CustomerEnquiryController::class.'@add','middleware' => ['permission:qs-create']]);
Route::get('/customer_enquiry/add/{id}', ['as'=>'customer_enquiry.add','uses'=>CustomerEnquiryController::class.'@add','middleware' => ['permission:qs-create']]);
Route::post('/customer_enquiry/save', ['as' => 'customer_enquiry.save', 'uses' => CustomerEnquiryController::class.'@save', 'middleware' => ['permission:qs-create']] );
Route::get('/customer_enquiry/edit/{id}', ['as' => 'customer_enquiry.edit', 'uses' => CustomerEnquiryController::class.'@edit', 'middleware' => ['permission:qs-edit']]);
Route::post('/customer_enquiry/update/{id}', ['as' => 'customer_enquiry.update', 'uses' => CustomerEnquiryController::class.'@update', 'middleware' => ['permission:qs-edit']]);
Route::get('/customer_enquiry/customer_data', [CustomerEnquiryController::class, 'getCustomer']);
Route::get('/customer_enquiry/salesman_data', [CustomerEnquiryController::class, 'getSalesman']);
Route::get('/customer_enquiry/item_data/{id}', [CustomerEnquiryController::class, 'getItem']);
Route::get('/customer_enquiry/checkrefno', [CustomerEnquiryController::class, 'checkRefNo']);
Route::get('/customer_enquiry/delete/{id}', ['as' => 'customer_enquiry.destroy', 'uses' => CustomerEnquiryController::class.'@destroy', 'middleware' => ['permission:qs-delete']]);
Route::get('/customer_enquiry/get_enquiry/{id}/{url}', [CustomerEnquiryController::class, 'getEnquiry']);
Route::get('/customer_enquiry/item_details/{id}', [CustomerEnquiryController::class, 'getItemDetails']);
Route::get('/customer_enquiry/print/{id}', ['as' => 'customer_enquiry.getPrint', 'uses' => CustomerEnquiryController::class.'@getPrint', 'middleware' => ['permission:qs-print']]);
Route::post('/customer_enquiry/search', [CustomerEnquiryController::class, 'getSearch']);
Route::get('/customer_enquiry/doc_open/{id}', [CustomerEnquiryController::class, 'docopen']);
Route::get('/customer_enquiry/print/{id}/{fc}', [CustomerEnquiryController::class, 'getPrint']);
Route::post('/customer_enquiry/export', ['as' => 'customer_enquiry.dataExport', 'uses' => CustomerEnquiryController::class.'@dataExport', 'middleware' => ['permission:qs-export']]);
Route::get('/customer_enquiry/checkvchrno', [CustomerEnquiryController::class, 'checkVchrNo']);
Route::post('/customer_enquiry/paging', [CustomerEnquiryController::class, 'ajaxPaging']);
Route::post('/customer_enquiry/import', [CustomerEnquiryController::class, 'getImport']);
Route::get('/customer_enquiry/getjob/{id}', [CustomerEnquiryController::class, 'getJob']);
		
Route::get('/customerleads', [CustomerLeadsController::class, 'index']);
Route::get('/customerleads/add', [CustomerLeadsController::class, 'add']);
Route::get('/customerleads/add/{id}', [CustomerLeadsController::class, 'add']);
Route::post('/customerleads/save', [CustomerLeadsController::class, 'save']);
Route::get('/customerleads/edit/{id}', [CustomerLeadsController::class, 'edit']);
Route::get('/customerleads/editadd/{id}', [CustomerLeadsController::class, 'edit']);
Route::post('/customerleads/updates/{id}', [CustomerLeadsController::class, 'updateAdd']);
Route::post('/customerleads/update/{id}', [CustomerLeadsController::class, 'update']);
Route::get('/customerleads/delete/{id}', [CustomerLeadsController::class, 'destroy']);
Route::post('/customerleads/paging', [CustomerLeadsController::class, 'ajaxPaging']);
Route::get('/customerleads/followup/{id}', [CustomerLeadsController::class, 'getFollowup']);
Route::get('/customerleads/new_followup', [CustomerLeadsController::class, 'ajaxSaveFollowup']);
Route::get('/customerleads/delete_folo/{id}/{lid}', [CustomerLeadsController::class, 'destroyFollowup']);
Route::get('/customerleads/load_followup/{id}', [CustomerLeadsController::class, 'loadFollowup']);
Route::get('/customerleads/edit_followup', [CustomerLeadsController::class, 'ajaxUpdateFollowup']);
Route::get('/customerleads/enquiry/{id}', [CustomerLeadsController::class, 'getEnquiry']);
Route::get('/customerleads/set_enquiry/{id}', [CustomerLeadsController::class, 'setEnquiry']);
Route::get('/customerleads/check_phone', [CustomerLeadsController::class, 'checkPhone']);
Route::get('/customerleads/check_email', [CustomerLeadsController::class, 'checkEmail']);
Route::get('/customerleads/followups/{date}', [CustomerLeadsController::class, 'getFollowups']);
Route::get('/customerleads/getfollowup/{id}', [CustomerLeadsController::class, 'getFollowup']);
Route::get('/customerleads/ajax_save/', [CustomerLeadsController::class, 'ajaxCreate']);
Route::get('/customerleads/customertype/', [CustomerLeadsController::class, 'customerType']);
Route::get('/customerleads/dophone/', [CustomerLeadsController::class, 'doPhone']);
Route::post('/customerlead/paging', [CustomerLeadsController::class, 'ajaxPagingleads']);
Route::post('/customerleads/saveedit/{id}', [CustomerLeadsController::class, 'saveedit']);
Route::get('/customerleads/editdatefollow/{id}/{date}', [CustomerLeadsController::class, 'editdateFollow']);
Route::post('/customerleads/updatefollowup/{id}', [CustomerLeadsController::class, 'updateFollowup']);
Route::get('/customerleads/customer/', [CustomerLeadsController::class, 'CustomerStatus']);
Route::get('/customerleads/enquirystatus/', [CustomerLeadsController::class, 'EnquiryStatus']);
Route::get('/customerleads/prospective/', [CustomerLeadsController::class, 'ProspectiveStatus']);
Route::get('/customerleads/archive/', [CustomerLeadsController::class, 'ArchiveStatus']);
Route::get('/customerleads/editFollowup/{id}/{date}', [CustomerLeadsController::class, 'editFollowup']);
Route::get('/customerleads/set_status', [CustomerLeadsController::class, 'setStatus']);
Route::get('/customerleads/edit/{id}/{sid}', [CustomerLeadsController::class, 'edit']);
Route::get('/customerleads/data_transfer', [CustomerLeadsController::class, 'getTransfer']);
Route::post('/customerleads/transfersave', [CustomerLeadsController::class, 'TransferSave']);
        

Route::get('/leads', [LeadsController::class, 'index']);
Route::get('/leads/add', [LeadsController::class, 'add']);
Route::post('/leads/save', [LeadsController::class, 'save']);
Route::get('/leads/edit/{id}', [LeadsController::class, 'edit']);
Route::post('/leads/update/{id}', [LeadsController::class, 'update']);
Route::get('/leads/delete/{id}', [LeadsController::class, 'destroy']);
Route::post('/leads/paging', [LeadsController::class, 'ajaxPaging']);
Route::get('/leads/followup/{id}', [LeadsController::class, 'getFollowup']);
Route::get('/leads/new_followup', [LeadsController::class, 'ajaxSaveFollowup']);
Route::get('/leads/delete_folo/{id}/{lid}', [LeadsController::class, 'destroyFollowup']);
Route::get('/leads/load_followup/{id}', [LeadsController::class, 'loadFollowup']);
Route::get('/leads/edit_followup', [LeadsController::class, 'ajaxUpdateFollowup']);
Route::get('/leads/set_enquiry/{id}', [LeadsController::class, 'setEnquiry']);
		
		
Route::get('/production', ['as' => 'production.index', 'uses' => ProductionController::class.'@index', 'middleware' => ['permission:do-list|do-create|do-edit|do-delete']]);
Route::get('/production/add', ['as'=>'production.add','uses'=>ProductionController::class.'@add','middleware' => ['permission:do-create']]);
Route::post('/production/save/{id}', ['as' => 'production.save', 'uses' => ProductionController::class.'@save', 'middleware' => ['permission:do-create']] );
Route::post('/production/save', ['as' => 'production.save', 'uses' => ProductionController::class.'@save', 'middleware' => ['permission:do-create']] );
Route::get('/production/edit/{id}', ['as' => 'production.edit', 'uses' => ProductionController::class.'@edit', 'middleware' => ['permission:do-edit']]);
Route::get('/production/add/{id}/{n}', ['as'=>'production.add','uses'=>ProductionController::class.'@add','middleware' => ['permission:do-create']]);
Route::get('/production/supplier_data', [ProductionController::class, 'getSupplier']);
Route::get('/production/item_data/{id}', [ProductionController::class, 'getItem']);
Route::get('/production/checkrefno', [ProductionController::class, 'checkRefNo']);
Route::get('/production/delete/{id}', ['as' => 'production.destroy', 'uses' => ProductionController::class.'@destroy', 'middleware' => ['permission:do-delete']]);
Route::get('/production/sdo_data', [ProductionController::class, 'getSDO']);
Route::get('/production/sdo_data/{id}', [ProductionController::class, 'getSDO']);
Route::get('/production/get_order/{id}/{n}', [ProductionController::class, 'getOrder']);
Route::get('/production/print/{id}', ['as' => 'production.getPrint', 'uses' => ProductionController::class.'@getPrint', 'middleware' => ['permission:do-print']]);
Route::get('/production/set_session', [ProductionController::class, 'setSessionVal']);
Route::post('/production/update/{id}', [ProductionController::class, 'update']);
Route::post('/production/search', [ProductionController::class, 'getSearch']);
Route::get('/production/print/{id}/{fc}', ['as' => 'production.getPrint', 'uses' => ProductionController::class.'@getPrint', 'middleware' => ['permission:do-print']]);
Route::post('/production/export', ['as' => 'production.dataExport', 'uses' => ProductionController::class.'@dataExport', 'middleware' => ['permission:do-print']]);
Route::get('/production/checkvchrno', [ProductionController::class, 'checkVchrNo']);
Route::post('/production/paging', [ProductionController::class, 'ajaxPaging']);
Route::get('/production/planning/{id}', ['as' => 'production.planning', 'uses' => ProductionController::class.'@planning', 'middleware' => ['permission:do-edit']]);
Route::get('/production/get_data', [ProductionController::class, 'getProdata']);
Route::get('/production/item_details/{id}', [ProductionController::class, 'getItemDetails']);
Route::get('/production/getjob/{id}', [ProductionController::class, 'getJob']);

		
Route::get('/account_reports', [AccountsReportController::class, 'index']);
Route::post('/account_reports/paging', [AccountsReportController::class, 'ajaxPaging']);
Route::post('/account_reports/search', [AccountsReportController::class, 'getSearch']);
Route::post('/account_reports/export', [AccountsReportController::class, 'dataExport']);
		
Route::get('/data_remove', [DataRemoveController::class, 'index']);
Route::post('/data_remove/cleardb', [DataRemoveController::class, 'clearDB']);
Route::post('/data_remove/cleardb_custom', [DataRemoveController::class, 'clearDBcustom']);
		
Route::get('/transaction_list', [TransactionListController::class, 'index']);
Route::post('/transaction_list/search', [TransactionListController::class, 'getSearch']);
Route::post('/transaction_list/export', [TransactionListController::class, 'dataExport']);
		
		
Route::get('/employee_document', [EmployeeDocumentController::class, 'index']);
Route::get('/employee_document/add', [EmployeeDocumentController::class, 'add']);
Route::post('/employee_document/save', [EmployeeDocumentController::class, 'save']);
Route::get('/employee_document/edit/{id}', [EmployeeDocumentController::class, 'edit']);
Route::post('/employee_document/update/{id}', [EmployeeDocumentController::class, 'update']);
Route::get('/employee_document/delete/{id}', [EmployeeDocumentController::class, 'destroy']);
		
		
Route::get('/employee_report', [EmployeeReportController::class, 'index']);
Route::post('/employee_report/search', [EmployeeReportController::class, 'getSearch']);
Route::post('/employee_report/export', [EmployeeReportController::class, 'dataExport']);
		
Route::get('/set_report', [SetReportController::class, 'index']);
Route::get('/set_report/update', [SetReportController::class, 'update']);
Route::get('/set_report/{id}', [SetReportController::class, 'assignPrint']);
Route::get('/set_report/delete/{id}', [SetReportController::class, 'delete']);
Route::get('/set_report/save/{id}', [SetReportController::class, 'save']);
Route::get('/set_infotemplate/{code}', [SetReportController::class, 'getInfoTemplate']);
		
		
Route::get('/manufacture', ['as' => 'manufacture.index', 'uses' => ManufactureController::class.'@index', 'middleware' => ['permission:pi-list|pi-create|pi-edit|pi-delete']]);
Route::get('/manufacture/add', ['as'=>'manufacture.add','uses'=>ManufactureController::class.'@add','middleware' => ['permission:pi-create']]);
Route::post('/manufacture/save', [ManufactureController::class, 'save']);
Route::get('/manufacture/delete/{id}', ['as' => 'manufacture.destroy', 'uses' => ManufactureController::class.'@destroy', 'middleware' => ['permission:pi-delete']]);
Route::get('/manufacture/edit/{id}', ['as' => 'manufacture.edit', 'uses' => ManufactureController::class.'@edit', 'middleware' => ['permission:pi-edit']]);
Route::post('/manufacture/update/{id}', [ManufactureController::class, 'update']);
Route::get('/manufacture/viewonly/{id}', ['as' => 'manufacture.viewonly', 'uses' => ManufactureController::class.'@viewonly', 'middleware' => ['permission:pi-view']]);
Route::get('/manufacture/print/{id}', [ManufactureController::class, 'getPrint']);
Route::post('/manufacture/printexport', [ManufactureController::class, 'printExport']);
Route::get('/manufacture/getdeptvoucher/{id}', [ManufactureController::class, 'getDeptVoucher']);
Route::post('/manufacture/search', [ManufactureController::class, 'getSearch']);
Route::post('/manufacture/export', [ManufactureController::class, 'dataExport']);
Route::get('/manufacture/search/{id}', [ManufactureController::class, 'getSearch']);
Route::get('/manufacture/getvoucher/{id}', [ManufactureController::class, 'getVoucher']);
Route::get('/manufacture/add/{id}', ['as' => 'manufacture.add', 'uses' => ManufactureController::class.'@add', 'middleware' => ['permission:pi-create']]);




Route::get('/material_requisition', [MaterialRequisitionController::class, 'index']);
Route::get('/material_requisition/add', [MaterialRequisitionController::class, 'add']);
Route::post('/material_requisition/save', [MaterialRequisitionController::class, 'save']);
Route::post('/material_requisition/save/{id}',[MaterialRequisitionController::class, 'save']);
	
Route::get('/material_requisition/edit/{id}', [MaterialRequisitionController::class, 'edit']);
Route::post('/material_requisition/update/{id}', [MaterialRequisitionController::class, 'update']);
Route::get('/material_requisition/delete/{id}', [MaterialRequisitionController::class, 'destroy']);
Route::get('/material_requisition/print/{id}', [MaterialRequisitionController::class, 'getPrint']);
Route::get('/material_requisition/item_details/{id}', [MaterialRequisitionController::class, 'getItemDetails']);
Route::post('/material_requisition/search', [MaterialRequisitionController::class, 'getSearch']);
Route::post('/material_requisition/export', [MaterialRequisitionController::class, 'dataExport']);
Route::post('/material_requisition/paging', [MaterialRequisitionController::class, 'ajaxPaging']);
Route::post('/material_requisition/set_session', [MaterialRequisitionController::class, 'setSessionVal']);
Route::get('/material_requisition/add/{id}/{n}', [MaterialRequisitionController::class, 'add']);
//Route::get('/material_requisition/item_details/{id}', [MaterialRequisitionController::class, 'getItemDetails']);
Route::get('/material_requisition/get_enquiry/{id}/{url}', [MaterialRequisitionController::class, 'getEnquiry']);
Route::get('/material_requisition/views/{id}',[MaterialRequisitionController::class, 'getViews']);
Route::get('/material_requisition/approve/{id}', [MaterialRequisitionController::class, 'getApproval']);
Route::get('/material_requisition/reject/{id}', [MaterialRequisitionController::class, 'getReject']);
Route::get('/material_requisition/print/{id}/{n}', [MaterialRequisitionController::class, 'getPrint']);
		
Route::get('/ms_customer', [MsCustomerController::class, 'index']);
Route::get('/ms_customer/add', [MsCustomerController::class, 'add']);
Route::post('/ms_customer/save', [MsCustomerController::class, 'save']);
Route::get('/ms_customer/edit/{id}', [MsCustomerController::class, 'edit']);
Route::post('/ms_customer/update/{id}', [MsCustomerController::class, 'update']);
Route::get('/ms_customer/delete/{id}', [MsCustomerController::class, 'destroy']);
Route::get('/ms_customer/get_customer', [MsCustomerController::class, 'getCustomer']);
		
		
Route::get('/ms_location', [MsLocationController::class, 'index']);
Route::get('/ms_location/add', [MsLocationController::class, 'add']);
Route::post('/ms_location/save', [MsLocationController::class, 'save']);
Route::get('/ms_location/edit/{id}', [MsLocationController::class, 'edit']);
Route::post('/ms_location/update/{id}', [MsLocationController::class, 'update']);
Route::get('/ms_location/delete/{id}', [MsLocationController::class, 'destroy']);
		
		
		
Route::get('/ms_technician', [MsTechnicianController::class, 'index']);
Route::get('/ms_technician/add', [MsTechnicianController::class, 'add']);
Route::post('/ms_technician/save', [MsTechnicianController::class, 'save']);
Route::get('/ms_technician/edit/{id}', [MsTechnicianController::class, 'edit']);
Route::post('/ms_technician/update/{id}', [MsTechnicianController::class, 'update']);
Route::get('/ms_technician/delete/{id}', [MsTechnicianController::class, 'destroy']);
		
		
Route::get('/ms_area', [MsAreaController::class, 'index']);
Route::get('/ms_area/add', [MsAreaController::class, 'add']);
Route::post('/ms_area/save', [MsAreaController::class, 'save']);
Route::get('/ms_area/edit/{id}', [MsAreaController::class, 'edit']);
Route::post('/ms_area/update/{id}', [MsAreaController::class, 'update']);
Route::get('/ms_area/delete/{id}', [MsAreaController::class, 'destroy']);

		
Route::get('/ms_worktype', [MsWorktypeController::class, 'index']);
Route::get('/ms_worktype/add', [MsWorktypeController::class, 'add']);
Route::post('/ms_worktype/save', [MsWorktypeController::class, 'save']);
Route::get('/ms_worktype/edit/{id}', [MsWorktypeController::class, 'edit']);
Route::post('/ms_worktype/update/{id}', [MsWorktypeController::class, 'update']);
Route::get('/ms_worktype/delete/{id}', [MsWorktypeController::class, 'destroy']);
		
		
Route::get('/ms_jobmaster', [MsJobmasterController::class, 'index']);
Route::get('/ms_jobmaster/add', [MsJobmasterController::class, 'add']);
Route::post('/ms_jobmaster/save', [MsJobmasterController::class, 'save']);
Route::get('/ms_jobmaster/edit/{id}', [MsJobmasterController::class, 'edit']);
Route::post('/ms_jobmaster/update/{id}', [MsJobmasterController::class, 'update']);
Route::get('/ms_jobmaster/delete/{id}', [MsJobmasterController::class, 'destroy']);
Route::get('/ms_jobmaster/get_jobs', [MsJobmasterController::class, 'getJobs']);
		
		
Route::get('/ms_workorder', [MsWorkorderController::class, 'index']);
Route::get('/ms_workorder/add', [MsWorkorderController::class, 'add']);
Route::post('/ms_workorder/save', [MsWorkorderController::class, 'save']);
Route::get('/ms_workorder/edit/{id}', [MsWorkorderController::class, 'edit']);
Route::post('/ms_workorder/update/{id}', [MsWorkorderController::class, 'update']);
Route::get('/ms_workorder/delete/{id}', [MsWorkorderController::class, 'destroy']);
Route::post('/ms_workorder/paging', [MsWorkorderController::class, 'ajaxPaging']);
Route::get('/ms_workorder/add/{id}', [MsWorkorderController::class, 'add']);
		
Route::get('/ms_reports', [MsReportsController::class, 'index']);
Route::post('/ms_reports/search', [MsReportsController::class, 'getSearch']);
Route::post('/ms_reports/export', [MsReportsController::class, 'dataExport']);
		
Route::get('/ms_workenquiry', [MsWorkenquiryController::class, 'index']);
Route::get('/ms_workenquiry/add', [MsWorkenquiryController::class, 'add']);
Route::post('/ms_workenquiry/save', [MsWorkenquiryController::class, 'save']);
Route::get('/ms_workenquiry/edit/{id}', [MsWorkenquiryController::class, 'edit']);
Route::post('/ms_workenquiry/update/{id}', [MsWorkenquiryController::class, 'update']);
Route::get('/ms_workenquiry/delete/{id}', [MsWorkenquiryController::class, 'destroy']);
Route::post('/ms_workenquiry/paging', [MsWorkenquiryController::class, 'ajaxPaging']);
Route::get('/ms_workenquiry/enquiry_list', [MsWorkenquiryController::class, 'getEnquiry']);
Route::post('/ms_workenquiry/ajax_enquiry_list', [MsWorkenquiryController::class, 'ajaxGetEnquiry']);

		
Route::get('/purchase_split', [PurchaseSplitController::class, 'index']);
Route::get('/purchase_split/add', [PurchaseSplitController::class, 'add']);
Route::get('/purchase_split/add/{id}', [PurchaseSplitController::class, 'add']);
Route::post('/purchase_split/save/{id}', [PurchaseSplitController::class, 'save']);
Route::post('/purchase_split/save', [PurchaseSplitController::class, 'save']);
Route::get('/purchase_split/edit/{id}', [PurchaseSplitController::class, 'edit']);
Route::post('/purchase_split/search', [PurchaseSplitController::class, 'getSearch']);

Route::get('/purchase_split/delete/{id}', [PurchaseSplitController::class, 'destroy']);
Route::get('/purchase_split/checkrefno', [PurchaseSplitController::class, 'checkRefNo']);
Route::get('/purchase_split/print/{id}', [PurchaseSplitController::class, 'getPrint']);
Route::get('/purchase_split/checkvchrno', [PurchaseSplitController::class, 'checkVchrNo']);
Route::post('/purchase_split/paging', [PurchaseSplitController::class, 'ajaxPaging']);
Route::post('/purchase_split/update/{id}', [PurchaseSplitController::class, 'update']);
Route::get('/purchase_split/print/{id}/{rid}', [PurchaseSplitController::class, 'getPrint']);
Route::post('/purchase_split/export', [PurchaseSplitController::class, 'dataExport']);
Route::get('/purchase_split/getcustomer', [PurchaseSplitController::class, 'getCustomer']);
Route::get('/purchase_split/cash_data/{cum}', [PurchaseSplitController::class, 'getCash']);
Route::get('/purchase_split/ps_data', [PurchaseSplitController::class, 'getPS']);
Route::get('/purchase_split/ps_data/{did}', [PurchaseSplitController::class, 'getPS']);
Route::get('/purchase_split/item_details/{id}', [PurchaseSplitController::class, 'getItemDetails']);
		
		
Route::get('/purchase_split_return', [PurchaseSplitReturnController::class, 'index']);
Route::get('/purchase_split_return/add', [PurchaseSplitReturnController::class, 'add']);
Route::get('/purchase_split_return/add/{id}', [PurchaseSplitReturnController::class, 'add']);
Route::post('/purchase_split_return/save/{id}', [PurchaseSplitReturnController::class, 'save']);
Route::post('/purchase_split_return/save', [PurchaseSplitReturnController::class, 'save']);
Route::get('/purchase_split_return/edit/{id}', [PurchaseSplitReturnController::class, 'edit']);
Route::post('/purchase_split_return/search', [PurchaseSplitReturnController::class, 'getSearch']);

Route::get('/purchase_split_return/delete/{id}', [PurchaseSplitReturnController::class, 'destroy']);
Route::get('/purchase_split_return/checkrefno', [PurchaseSplitReturnController::class, 'checkRefNo']);
Route::get('/purchase_split_return/print/{id}', [PurchaseSplitReturnController::class, 'getPrint']);
Route::get('/purchase_split_return/checkvchrno', [PurchaseSplitReturnController::class, 'checkVchrNo']);
Route::post('/purchase_split_return/paging', [PurchaseSplitReturnController::class, 'ajaxPaging']);
Route::post('/purchase_split_return/update/{id}', [PurchaseSplitReturnController::class, 'update']);
Route::get('/purchase_split_return/print/{id}/{rid}', [PurchaseSplitReturnController::class, 'getPrint']);
Route::post('/purchase_split_return/export', [PurchaseSplitReturnController::class, 'dataExport']);
Route::get('/purchase_split_return/getcustomer', [PurchaseSplitReturnController::class, 'getCustomer']);
Route::get('/purchase_split_return/cash_data/{cum}', [PurchaseSplitReturnController::class, 'getCash']);
		
		
Route::get('/sales_split', [SalesSplitController::class, 'index']);
Route::get('/sales_split/add', [SalesSplitController::class, 'add']);
Route::get('/sales_split/add/{id}', [SalesSplitController::class, 'add']);
Route::post('/sales_split/save/{id}', [SalesSplitController::class, 'save']);
Route::post('/sales_split/save', [SalesSplitController::class, 'save']);
Route::get('/sales_split/edit/{id}', [SalesSplitController::class, 'edit']);
Route::get('/sales_split/delete/{id}', [SalesSplitController::class, 'destroy']);
Route::get('/sales_split/checkrefno', [SalesSplitController::class, 'checkRefNo']);
Route::get('/sales_split/print/{id}', [SalesSplitController::class, 'getPrint']);
Route::get('/sales_split/checkvchrno', [SalesSplitController::class, 'checkVchrNo']);
Route::post('/sales_split/paging', [SalesSplitController::class, 'ajaxPaging']);
Route::post('/sales_split/update/{id}', [SalesSplitController::class, 'update']);
Route::get('/sales_split/print/{id}/{rid}', [SalesSplitController::class, 'getPrint']);
Route::post('/sales_split/export', [SalesSplitController::class, 'dataExport']);
Route::post('/sales_split/search', [SalesSplitController::class, 'getSearch']);
Route::get('/sales_split/split_data', [SalesSplitController::class, 'getSplit']);
Route::get('/sales_split/split_data/{did}', [SalesSplitController::class, 'getSplit']);
Route::post('/sales_split/paging_split_data', [SalesSplitController::class, 'ajaxPagingSplitData']);
Route::get('/sales_split/item_details/{id}', [SalesSplitController::class, 'getItemDetails']);
Route::post('/sales_split/export', [SalesSplitController::class, 'dataExport']);
Route::post('/sales_split/search', [SalesSplitController::class, 'getSearch']);
Route::get('/sales_split/getcustomer', [SalesSplitController::class, 'getCustomer']);
		
		
		//New May2025
		
Route::get('/sales_split_return', [SalesSplitReturnController::class, 'index']);
Route::get('/sales_split_return/add', [SalesSplitReturnController::class, 'add']);
Route::get('/sales_split_return/add/{id}', [SalesSplitReturnController::class, 'add']);
Route::post('/sales_split_return/save/{id}', [SalesSplitReturnController::class, 'save']);
Route::post('/sales_split_return/save', [SalesSplitReturnController::class, 'save']);
Route::get('/sales_split_return/edit/{id}', [SalesSplitReturnController::class, 'edit']);
Route::get('/sales_split_return/delete/{id}', [SalesSplitReturnController::class, 'destroy']);
Route::get('/sales_split_return/checkrefno', [SalesSplitReturnController::class, 'checkRefNo']);
Route::get('/sales_split_return/print/{id}', [SalesSplitReturnController::class, 'getPrint']);
Route::get('/sales_split_return/checkvchrno', [SalesSplitReturnController::class, 'checkVchrNo']);
Route::post('/sales_split_return/paging', [SalesSplitReturnController::class, 'ajaxPaging']);
Route::post('/sales_split_return/update/{id}', [SalesSplitReturnController::class, 'update']);
Route::get('/sales_split_return/print/{id}/{rid}', [SalesSplitReturnController::class, 'getPrint']);
Route::post('/sales_split_return/export', [SalesSplitReturnController::class, 'dataExport']);
Route::post('/sales_split_return/search', [SalesSplitReturnController::class, 'getSearch']);
Route::post('/sales_split_return/export', [SalesSplitReturnController::class, 'dataExport']);
Route::post('/sales_split_return/search', [SalesSplitReturnController::class, 'getSearch']);
Route::get('/sales_split_return/getcustomer', [SalesSplitReturnController::class, 'getCustomer']);
		//
Route::get('/tools', [ToolsController::class, 'index']);
Route::post('/tools/search/{type}', [ToolsController::class, 'search']);
		

		 //


Route::get('/buildingmaster', [BuildingMasterController::class, 'index']);
Route::get('/buildingmaster/add', [BuildingMasterController::class, 'add']);
Route::post('/buildingmaster/save', [BuildingMasterController::class, 'save']);
Route::get('/buildingmaster/edit/{id}', [BuildingMasterController::class, 'edit']);
Route::get('/buildingmaster/checkcode', [BuildingMasterController::class, 'checkCode']);
Route::post('/buildingmaster/update/{id}', [BuildingMasterController::class, 'update']);
Route::get('/buildingmaster/delete/{id}', [BuildingMasterController::class, 'destroy']);
Route::get('/buildingmaster/getprefix/{id}', [BuildingMasterController::class, 'getPrefix']);
Route::post('/buildingmaster/upload', [BuildingMasterController::class, 'uploadss']);
Route::get('/buildingmaster/getvals/{id}', [BuildingMasterController::class, 'getValues']);
Route::get('/buildingmaster/get_flat/{id}', [BuildingMasterController::class, 'getFlat']);

Route::get('/flatmaster', [FlatMasterController::class, 'index']);
Route::get('/flatmaster/flat_list/{val}', [FlatMasterController::class, 'flatList']);
Route::get('/flatmaster/flat_list/{val}/{f}', [FlatMasterController::class, 'flatList']);
Route::post('/flatmaster/save', [FlatMasterController::class, 'save']);
Route::get('/flatmaster/add', [FlatMasterController::class, 'add']);
Route::post('/flatmaster/save', [FlatMasterController::class, 'save']);
Route::get('/flatmaster/edit/{id}', [FlatMasterController::class, 'edit']);
Route::post('/flatmaster/update/{id}', [FlatMasterController::class, 'update']);
Route::get('/flatmaster/delete/{id}', [FlatMasterController::class, 'destroy']);
Route::get('/flatmaster/checkcode', [FlatMasterController::class, 'checkCode']);
		

Route::get('/contractbuilding', [ContractBuildingController::class, 'index']);
Route::get('/contractbuilding/add', [ContractBuildingController::class, 'add']);
Route::get('/contractbuilding/add/{id}', [ContractBuildingController::class, 'add']);
Route::post('/contractbuilding/save', [ContractBuildingController::class, 'save']);


Route::get('/contractbuilding/oreceipt_add', [ContractBuildingController::class, 'ajaxoReceiptAdd']);
Route::post('/contractbuilding/save_rentallo', [ContractBuildingController::class, 'saveRentAllocation']);
Route::get('/contractbuilding/edit/{id}', [ContractBuildingController::class, 'edit']);
	
Route::get('/contractbuilding/rent_allocate', [ContractBuildingController::class, 'ajaxAllocate']);
Route::get('/contractbuilding/receipt_add', [ContractBuildingController::class, 'ajaxReceiptAdd']);
Route::post('/contractbuilding/save_receipt', [ContractBuildingController::class, 'saveReceipt']);
Route::post('/contractbuilding/save_deposit', [ContractBuildingController::class, 'saveDeposit']);
Route::post('/contractbuilding/save_otherrv', [ContractBuildingController::class, 'saveOtherRv']);
Route::get('/contractbuilding/printrv/{n}/{id}', [ContractBuildingController::class, 'printRv']);
Route::post('/contractbuilding/paging', [ContractBuildingController::class, 'ajaxPaging']);
Route::get('/contractbuilding/enquiry', [ContractBuildingController::class, 'enquiry']);
Route::post('/contractbuilding/ajax-enquiry', [ContractBuildingController::class, 'ajaxEnquiry']);
Route::get('/contractbuilding/renew/{id}', [ContractBuildingController::class, 'renew']);
Route::post('/contractbuilding/search', [ContractBuildingController::class, 'getSearch']);
Route::get('/contractbuilding/printjv/{id}', [ContractBuildingController::class, 'printJv']);
Route::get('/contractbuilding/mail/{id}', [ContractBuildingController::class, 'sendmail']);
Route::get('/contractbuilding/printcontr/{id}/{rid}', [ContractBuildingController::class, 'printcontract']);
Route::get('/contractbuilding/printinvo/{id}/{rid}', [ContractBuildingController::class, 'printinvo']);
//Route::get('/contractbuilding/os_rvs/{id}/{n}', [ContractBuildingController::class, 'osRvs']);
Route::post('/contractbuilding/update/{id}', [ContractBuildingController::class, 'update']);
//Route::get('/contractbuilding/os_rvs/{id}/{n}', [ContractBuildingController::class, 'osRvs']);
Route::post('/contractbuilding/update/{id}', [ContractBuildingController::class, 'update']);
//Route::get('/contractbuilding/os_rvs/{id}/{n}', [ContractBuildingController::class, 'osRvs']);
Route::post('/contractbuilding/update/{id}', [ContractBuildingController::class, 'update']);
Route::get('/contractbuilding/mail/{id}', [ContractBuildingController::class, 'sendmail']);
//Route::get('/contractbuilding/os_rvs/{id}/{n}', [ContractBuildingController::class, 'osRvs']);
Route::post('/contractbuilding/update/{id}', [ContractBuildingController::class, 'update']);
Route::get('/contractbuilding/mail/{id}', [ContractBuildingController::class, 'sendmail']);
Route::get('/contractbuilding/os_rvs/{id}/{n}', [ContractBuildingController::class, 'osRvs']);
Route::post('/contractbuilding/update/{id}', [ContractBuildingController::class, 'update']);
Route::get('/contractbuilding/delete/{id}', [ContractBuildingController::class, 'destroy']);
Route::get('/contractbuilding/os_rvs/{id}/{n}/{rid}', [ContractBuildingController::class, 'osRvs']);
Route::get('/contractbuilding/printsi/{id}', [ContractBuildingController::class, 'printSi']);
Route::get('/contractbuilding/close/{id}', [ContractBuildingController::class, 'doClose']);
Route::post('/contractbuilding/close/{id}', [ContractBuildingController::class, 'submitClose']);
Route::post('/contractbuilding/renew_save', [ContractBuildingController::class, 'renewSave']);
Route::get('/contractbuilding/settle/{id}', [ContractBuildingController::class, 'settlement']);
Route::post('/contractbuilding/save_settlement', [ContractBuildingController::class, 'saveSettlement']);
Route::get('/contractbuilding/get_enddate/{d}/{m}', [ContractBuildingController::class, 'getEnddate']);
Route::get('/contractbuilding/closed', [ContractBuildingController::class, 'closed']);
Route::post('/contractbuilding/ajax-closed', [ContractBuildingController::class, 'ajaxClosed']);
Route::post('/contractbuilding/save_payment', [ContractBuildingController::class, 'savePayment']);
Route::get('/contractbuilding/rent_reallocate', [ContractBuildingController::class, 'ajaxReallocate']);
Route::get('/contractbuilding/receipt_readd', [ContractBuildingController::class, 'ajaxReceiptReAdd']);
Route::get('/contractbuilding/os_rvs/{id}/{n}/{rid}/{m}', [ContractBuildingController::class, 'osRvs']); //NOV24
Route::get('/contractbuilding/get_orvdetails/{rid}/{id}', [ContractBuildingController::class, 'getOrvDetails']); //NOV24
Route::get('/contractbuilding/printpv/{id}', [ContractBuildingController::class, 'printPv']);
Route::post('/contractbuilding/upload', [ContractBuildingController::class, 'uploadSubmit']);
Route::get('/contractbuilding/history', [ContractBuildingController::class, 'history']);
Route::post('/contractbuilding/ajax-history', [ContractBuildingController::class, 'ajaxHistory']);
Route::get('/contractbuilding/rent_calculate', [ContractBuildingController::class, 'ajaxRentCalculate']);
Route::post('/contractbuilding/update_renew/{id}', [ContractBuildingController::class, 'updateRenew']);
Route::get('/contractbuilding/print_all/{id}', [ContractBuildingController::class, 'printAll']);
Route::get('/contractbuilding/attach/{id}', [ContractBuildingController::class, 'attachment']);
Route::post('/contractbuilding/get_fileform', [ContractBuildingController::class, 'getFileform']);
Route::post('/contractbuilding/attach_save', [ContractBuildingController::class, 'attachmentSave']);
Route::post('/contractbuilding/upload-contract', [ContractBuildingController::class, 'uploadContract']);

Route::get('/contractbuilding/expiry', [ContractExpiryController::class, 'expiry']);
Route::post('/contractbuilding/expirysearch', [ContractExpiryController::class, 'getSearch']);
Route::post('/contractbuilding/expirytemplate', [ContractExpiryController::class, 'expiryTemplate']);
Route::post('/contractbuilding/expiryemail', [ContractExpiryController::class, 'expiryEmail']);
Route::get('/contractbuilding/getmessage/{id}', [ContractExpiryController::class, 'getMessage']);



Route::get('/manual_journal',[ManualJournalController::class, 'index']);
Route::get('/manual_journal/add', [ManualJournalController::class, 'add']);
Route::post('/manual_journal/save', [ManualJournalController::class, 'save']);
Route::get('/manual_journal/getvoucher/{id}',[ManualJournalController::class, 'getVoucher']);
Route::get('/manual_journal/delete/{id}/{n}', [ManualJournalController::class, 'destroy']);
Route::get('/manual_journal/getvouchertype/{id}', [ManualJournalController::class, 'getVoucherType']);
Route::get('/manual_journal/edit/{id}', [ManualJournalController::class, 'edit']);
Route::post('/manual_journal/update/{id}', [ManualJournalController::class, 'update']);
Route::get('/manual_journal/checkvchrno', [ManualJournalController::class, 'checkVchrNo']);
Route::get('/manual_journal/printgrp/{id}', [ManualJournalController::class, 'getPrintgrp']);
Route::get('/manual_journal/print/{id}/{rid}', [ManualJournalController::class, 'getPrint']);
Route::post('/manual_journal/add/{id}/{rid}/{vouchertype}', [ManualJournalController::class, 'add']);
Route::post('/manual_journal/getvoucherprint}', [ManualJournalController::class, 'getVoucherprint']);
Route::get('/manual_journal/set_transactions/{type}/{id}/{n}', [ManualJournalController::class, 'setTransactions']);



Route::get('/realestate', [RealestateStatementController::class, 'index']);
Route::post('/realestate/search_account', [RealestateStatementController::class, 'searchAccount']);
Route::post('/realestate/paging', [RealestateStatementController::class, 'ajaxPaging']);
Route::post('/realestate/export', [RealestateStatementController::class, 'dataExport']);
Route::get('/realestate/address', [RealestateStatementController::class, 'addressList']);
Route::post('/realestate/search', [RealestateStatementController::class, 'searchAddress']);
Route::post('/realestate/address_export', [RealestateStatementController::class, 'addressExport']);
Route::get('/realestate/os_bills/{id}', [RealestateStatementController::class, 'outStandingBills']);
Route::get('/realestate/os_bills/{id}/{no}/{mod}/{rid}', [RealestateStatementController::class, 'outStandingBills']);
Route::get('/realestate/os_bills/{id}/{no}', [RealestateStatementController::class, 'outStandingBills']);

Route::get('/duration', [DurationMasterController::class, 'index']);
Route::get('/duration/add', [DurationMasterController::class, 'add']);
Route::get('/duration/add/{id}', [DurationMasterController::class, 'add']);
Route::post('/duration/save', [DurationMasterController::class, 'save']);
Route::get('/duration/edit/{id}', [DurationMasterController::class, 'edit']);
Route::post('/duration/update', [DurationMasterController::class, 'Update']);
Route::post('/duration/update/{id}', [DurationMasterController::class, 'update']);
Route::get('/duration/delete/{id}', [DurationMasterController::class, 'destroy']);
Route::get('/duration/checkdays', [DurationMasterController::class, 'CalculateDays']);
Route::get('/duration/checkmonth', [DurationMasterController::class, 'checkCode']);
//	Route::get('/duration/checkdays/{checkmon}', [DurationMasterController::class, 'CalculateDays']);


Route::get('/cheque_details', [ChequeDetailsController::class, 'index']);
Route::post('/cheque_details/paging', [ChequeDetailsController::class, 'ajaxPaging']);
Route::get('/cheque_details/add', [ChequeDetailsController::class, 'add']);
Route::post('/cheque_details/save', [ChequeDetailsController::class, 'save']);
Route::get('/cheque_details/edit/{id}', [ChequeDetailsController::class, 'edit']);
Route::post('/cheque_details/update/{id}', [ChequeDetailsController::class, 'update']);
Route::get('/cheque_details/delete/{id}', [ChequeDetailsController::class, 'destroy']);
Route::get('/cheque_details/print/{id}/{rid}', [ChequeDetailsController::class, 'getPrint']);
Route::get('/cheque_details/printfc/{id}/{rid}', [ChequeDetailsController::class, 'getPrintFc']);
//Route::get('/cheque_details/bank_data', [SalesOrderController::class, 'getBank']);



Route::get('/machine', [MachineController::class, 'index']);
Route::get('/machine/add', [MachineController::class, 'add']);
Route::post('/machine/save', [MachineController::class, 'save']);
Route::get('/machine/edit/{id}', [MachineController::class, 'edit']);
Route::post('/machine/update/{id}', [MachineController::class, 'update']);
Route::get('/machine/delete/{id}', [MachineController::class, 'destroy']);
		
Route::get('/paper', [PaperController::class, 'index']);
Route::get('/paper/add', [PaperController::class, 'add']);
Route::post('/paper/save', [PaperController::class, 'save']);
Route::get('/paper/edit/{id}', [PaperController::class, 'edit']);
Route::post('/paper/update/{id}', [PaperController::class, 'update']);
Route::get('/paper/delete/{id}', [PaperController::class, 'destroy']);
		
Route::get('/contract_type', [ContractTypeController::class, 'index']);
Route::get('/contract_type/add', [ContractTypeController::class, 'add']);
Route::post('/contract_type/save', [ContractTypeController::class, 'save']);
Route::get('/contract_type/edit/{id}', [ContractTypeController::class, 'edit']);
Route::post('/contract_type/update/{id}', [ContractTypeController::class, 'update']);
Route::get('/contract_type/delete/{id}', [ContractTypeController::class, 'destroy']);
Route::get('/contract_type/check_code', [ContractTypeController::class, 'checkCode']);
		
		
Route::get('/contract', [ContractController::class, 'index']);
Route::get('/contract/add', [ContractController::class, 'add']);
Route::post('/contract/save', [ContractController::class, 'save']);
Route::get('/contract/edit/{id}', [ContractController::class, 'edit']);
Route::post('/contract/update/{id}', [ContractController::class, 'update']);
Route::get('/contract/delete/{id}', [ContractController::class, 'destroy']);
Route::get('/contract/check_code', [ContractController::class, 'checkCode']);
Route::post('/contract/paging', [ContractController::class, 'ajaxPaging']);
Route::get('/contract/read/{id}', [ContractController::class, 'machineRead']);
Route::post('/contract/readSave/{id}', [ContractController::class, 'machineReadSave']);
Route::get('/contract/read-delete/{id}/{rid}', [ContractController::class, 'machineReadDelete']);
Route::get('/contract/read-edit/{id}/{rid}', [ContractController::class, 'machineReadEdit']);
Route::post('/contract/readeditSave/{id}/{rid}', [ContractController::class, 'machineReadEditSave']);
		
Route::get('/contra_type', [ContraTypeController::class, 'index']);
Route::get('/contra_type/add', [ContraTypeController::class, 'add']);
Route::post('/contra_type/save', [ContraTypeController::class, 'save']);
Route::get('/contra_type/edit/{id}', [ContraTypeController::class, 'edit']);
Route::post('/contra_type/update/{id}', [ContraTypeController::class, 'update']);
Route::get('/contra_type/delete/{id}', [ContraTypeController::class, 'destroy']);
Route::get('/contra_type/check_type', [ContraTypeController::class, 'checkType']);
Route::get('/contra_type/get_details/{id}', [ContraTypeController::class, 'getDetails']);
Route::get('/contra_type/get_flat/{id}', [ContraTypeController::class, 'getFlat']);
		
Route::get('/contra_type/add-settings', [ContraTypeController::class, 'addSettings']);
Route::post('/contra_type/save-settings', [ContraTypeController::class, 'saveSettings']);
Route::get('/contra_type/edit-settings/{id}', [ContraTypeController::class, 'editSettings']);
Route::post('/contra_type/update-settings/{id}', [ContraTypeController::class, 'updateSettings']);
Route::get('/contra_type/list-settings', [ContraTypeController::class, 'listSettings']);
Route::get('/contra_type/delete-settings/{id}', [ContraTypeController::class, 'destroySettings']);
		
Route::get('/cargo_receipt', [CargoReceiptController::class, 'index']);
Route::get('/cargo_receipt/add', [CargoReceiptController::class, 'add']);
Route::post('/cargo_receipt/save', [CargoReceiptController::class, 'save']);
Route::get('/cargo_receipt/edit/{id}', [CargoReceiptController::class, 'edit']);
Route::post('/cargo_receipt/update/{id}', [CargoReceiptController::class, 'update']);
Route::post('/cargo_receipt/upload-attachment', [CargoReceiptController::class, 'uploadAttachment']);
Route::post('/cargo_receipt/get_fileform', [CargoReceiptController::class, 'getFileform']);
Route::get('/cargo_receipt/get_consignee', [CargoReceiptController::class, 'getConsignee']);
Route::get('/cargo_receipt/create_consignee', [CargoReceiptController::class, 'createConsignee']);
Route::get('/cargo_receipt/create_shipper', [CargoReceiptController::class, 'createShipper']);
Route::get('/cargo_receipt/get_shipper', [CargoReceiptController::class, 'getShipper']);
Route::get('/cargo_receipt/delete/{id}', [CargoReceiptController::class, 'destroy']);
Route::get('/cargo_receipt/return/{id}', [CargoReceiptController::class, 'return']);
Route::post('/cargo_receipt/paging', [CargoReceiptController::class, 'ajaxPaging']);
Route::get('/cargo_receipt/print/{id}', [CargoReceiptController::class, 'getPrint']);
Route::get('/cargo_receipt/preprint/{id}/{rid}', [CargoReceiptController::class, 'getPreprint']);
Route::get('/cargo_receipt/print_receipt/{id}', [CargoReceiptController::class, 'getPrintRecepit']);
Route::get('/cargo_receipt/get_destination', [CargoReceiptController::class, 'getDestination']);
Route::get('/cargo_receipt/get_salesman', [CargoReceiptController::class, 'getSalesman']);
Route::get('/cargo_receipt/get_rate', [CargoReceiptController::class, 'getRate']);
Route::get('/cargo_receipt/rate_history/{id}', [CargoReceiptController::class, 'getRateHistory']);
Route::get('/cargo_receipt/get_status/{i}', [CargoReceiptController::class, 'getStatus']);
Route::post('/cargo_receipt/report', [CargoReceiptController::class, 'report']);
Route::post('/cargo_receipt/export', [CargoReceiptController::class, 'dataExport']);
		
		
Route::get('/cargo_waybill', [CargoWayBillController::class, 'index']);
Route::get('/cargo_waybill/add', [CargoWayBillController::class, 'add']);
Route::post('/cargo_waybill/save', [CargoWayBillController::class, 'save']);
Route::get('/cargo_waybill/get_conjobs/{id}', [CargoWayBillController::class, 'getConsigneeJobs']);
Route::get('/cargo_waybill/get_con/{id}', [CargoWayBillController::class, 'getConsignee']);
Route::get('/cargo_waybill/get_vehicle', [CargoWayBillController::class, 'getVehicle']);
Route::post('/cargo_waybill/paging', [CargoWayBillController::class, 'ajaxPaging']);
Route::get('/cargo_waybill/delete/{id}', [CargoWayBillController::class, 'destroy']);
Route::get('/cargo_waybill/edit/{id}', [CargoWayBillController::class, 'edit']);
Route::post('/cargo_waybill/update/{id}', [CargoWayBillController::class, 'update']);
Route::get('/cargo_waybill/print/{id}', [CargoWayBillController::class, 'getPrint']);
		
Route::get('/cargo_despatchbill', [CargoDespatchBillController::class, 'index']);
Route::get('/cargo_despatchbill/add', [CargoDespatchBillController::class, 'add']);
Route::post('/cargo_despatchbill/save', [CargoDespatchBillController::class, 'save']);
//Route::get('/cargo_despatchbill/get_waybills/{id}', [CargoDespatchBillController::class, 'getWaybills']);
Route::post('/cargo_despatchbill/paging', [CargoDespatchBillController::class, 'ajaxPaging']);
Route::get('/cargo_despatchbill/delete/{id}', [CargoDespatchBillController::class, 'destroy']);
Route::get('/cargo_despatchbill/edit/{id}', [CargoDespatchBillController::class, 'edit']);
Route::post('/cargo_despatchbill/update/{id}', [CargoDespatchBillController::class, 'update']);
Route::get('/cargo_despatchbill/report', [CargoDespatchBillController::class, 'report']);
Route::post('/cargo_despatchbill/search', [CargoDespatchBillController::class, 'searchReport']);
Route::post('/cargo_despatchbill/export', [CargoDespatchBillController::class, 'dataExport']);
Route::get('/cargo_despatchbill/get_waybills', [CargoDespatchBillController::class, 'getWaybills']);
Route::get('/cargo_despatchbill/report_search/{val}', [CargoDespatchBillController::class, 'reportSearch']);
Route::get('/cargo_despatchbill/list', [CargoDespatchBillController::class, 'despatchList']);
Route::post('/cargo_despatchbill/paging-list', [CargoDespatchBillController::class, 'ajaxPagingList']);
Route::get('/cargo_despatchbill/get_statusform/{id}/{type}', [CargoDespatchBillController::class, 'getStatusForm']);
Route::post('/cargo_despatchbill/upload-attachment', [CargoDespatchBillController::class, 'uploadAttachment']);
Route::post('/cargo_despatchbill/save-status', [CargoDespatchBillController::class, 'saveStatus']);
Route::get('/cargo_despatchbill/view/{id}', [CargoDespatchBillController::class, 'view']);
Route::get('/cargo_despatchbill/waybills', [CargoDespatchBillController::class, 'waybillList']);
Route::post('/cargo_despatchbill/paging-wblist', [CargoDespatchBillController::class, 'ajaxPagingWbillList']);
Route::get('/cargo_despatchbill/print/{id}', [CargoDespatchBillController::class, 'getPrint']);
		
		
Route::get('/purchase_rental', [PurchaseRentalController::class, 'index']);
Route::get('/purchase_rental/add', [PurchaseRentalController::class, 'add']);
Route::post('/purchase_rental/save', [PurchaseRentalController::class, 'save']);
Route::get('/purchase_rental/edit/{id}', [PurchaseRentalController::class, 'edit']);
Route::post('/purchase_rental/update/{id}', [PurchaseRentalController::class, 'update']);
Route::get('/purchase_rental/delete/{id}', [PurchaseRentalController::class, 'destroy']);
Route::post('/purchase_rental/paging', [PurchaseRentalController::class, 'ajaxPaging']);
Route::get('/purchase_rental/get_driver/{no}/{id}', [PurchaseRentalController::class, 'getDriver']);
Route::get('/purchase_rental/print/{id}/{rid}', [PurchaseRentalController::class, 'getPrint']);
Route::post('/purchase_rental/search', [PurchaseRentalController::class, 'searchReport']);
Route::post('/purchase_rental/export', [PurchaseRentalController::class, 'dataExport']);
		
Route::get('/rental_sales', [RentalSalesController::class, 'index']);
Route::get('/rental_sales/add', [RentalSalesController::class, 'add']);
Route::post('/rental_sales/save', [RentalSalesController::class, 'save']);
Route::get('/rental_sales/edit/{id}', [RentalSalesController::class, 'edit']);
Route::post('/rental_sales/update/{id}', [RentalSalesController::class, 'update']);
Route::get('/rental_sales/delete/{id}', [RentalSalesController::class, 'destroy']);
Route::post('/rental_sales/paging', [RentalSalesController::class, 'ajaxPaging']);
Route::get('/rental_sales/get_driver/{no}/{id}', [RentalSalesController::class, 'getDriver']);
Route::get('/rental_sales/print/{id}/{rid}', [RentalSalesController::class, 'getPrint']);
Route::post('/rental_sales/search', [RentalSalesController::class, 'searchReport']);
Route::post('/rental_sales/export', [RentalSalesController::class, 'dataExport']);
		
Route::get('/rental_driver', [RentalDriverController::class, 'index']);
Route::get('/rental_driver/add', [RentalDriverController::class, 'add']);
Route::post('/rental_driver/save', [RentalDriverController::class, 'save']);
Route::get('/rental_driver/edit/{id}', [RentalDriverController::class, 'edit']);
Route::post('/rental_driver/update/{id}', [RentalDriverController::class, 'update']);
Route::get('/rental_driver/delete/{id}', [RentalDriverController::class, 'destroy']);
Route::get('/rental_driver/checknumber', [RentalDriverController::class, 'checknumber']);
Route::get('/rental_driver/checkmobnumber1', [RentalDriverController::class, 'checkmobnumber1']);
Route::get('/rental_driver/checkmobnumber2', [RentalDriverController::class, 'checkmobnumber2']);
		
		
Route::get('/rental_supplierdriver', [RentalSupplierDriverController::class, 'index']);
Route::get('/rental_supplierdriver/add', [RentalSupplierDriverController::class, 'add']);
Route::post('/rental_supplierdriver/save', [RentalSupplierDriverController::class, 'save']);
Route::get('/rental_supplierdriver/edit/{id}', [RentalSupplierDriverController::class, 'edit']);
Route::post('/rental_supplierdriver/update/{id}', [RentalSupplierDriverController::class, 'update']);
Route::get('/rental_supplierdriver/delete/{id}', [RentalSupplierDriverController::class, 'destroy']);
		
Route::get('/rental_customerdriver', [RentalCustomerDriverController::class, 'index']);
Route::get('/rental_customerdriver/add', [RentalCustomerDriverController::class, 'add']);
Route::post('/rental_customerdriver/save', [RentalCustomerDriverController::class, 'save']);
Route::get('/rental_customerdriver/edit/{id}', [RentalCustomerDriverController::class, 'edit']);
Route::post('/rental_customerdriver/update/{id}', [RentalCustomerDriverController::class, 'update']);
Route::get('/rental_customerdriver/delete/{id}', [RentalCustomerDriverController::class, 'destroy']);
		
Route::get('/rental_report', [RentalReportController::class, 'index']);
Route::post('/rental_report/search', [RentalReportController::class, 'searchReport']);

Route::get('/sales_order_booking', [SalesOrderBookingController::class, 'index']);
Route::get('/sales_order_booking/add', [SalesOrderBookingController::class, 'add']);
Route::post('/sales_order_booking/save', [SalesOrderBookingController::class, 'save']);
Route::get('/sales_order_booking/customer_data', [SalesOrderBookingController::class, 'getCustomer']);
Route::get('/sales_order_booking/salesman_data', [SalesOrderBookingController::class, 'getSalesman']);
Route::post('/sales_order_booking/paging', [SalesOrderBookingController::class, 'ajaxPaging']);
Route::get('/sales_order_booking/edit/{id}', [SalesOrderBookingController::class, 'edit']);
Route::post('/sales_order_booking/update/{id}', [SalesOrderBookingController::class, 'update']);
Route::get('/sales_order_booking/delete/{id}', [SalesOrderBookingController::class, 'destroy']);
Route::get('/sales_order_booking/list', [SalesOrderBookingController::class, 'listing']);
Route::post('/sales_order_booking/paging_list', [SalesOrderBookingController::class, 'ajaxPagingList']);
Route::post('/sales_order_booking/paging_list_com', [SalesOrderBookingController::class, 'ajaxPagingListCom']);
Route::get('/sales_order_booking/view/{id}', [SalesOrderBookingController::class, 'view']);
Route::post('/sales_order_booking/view/{id}', [SalesOrderBookingController::class, 'submit']);
Route::get('/sales_order_booking/transfer/{id}', [SalesOrderBookingController::class, 'Transfer']);
Route::get('/sales_order_booking/print/{id}/{fc}', [SalesOrderBookingController::class, 'getPrint']);
		
Route::get('/sales_order_booking/edit_force/{id}', [SalesOrderBookingController::class, 'editForce']); //JUL7
//Route::get('/sales_order_booking/get_order/{id}/{n}/{sid}', [CustomersDOController::class, 'getOrder']);//JUL7
		
Route::get('/sales_order_booking/assign', [SalesOrderBookingController::class, 'assignDriver']);
Route::post('/sales_order_booking/paging_ordlist', [SalesOrderBookingController::class, 'ajaxPagingOrderList']);
Route::post('/sales_order_booking/driver_assign', [SalesOrderBookingController::class, 'driverAssign']);
		
Route::get('/proforma_invoice', ['as' => 'proforma_invoice.index', 'uses' => ProformaInvoiceController::class.'@index', 'middleware' => ['permission:pfi-list|pfi-create|pfi-edit|pfi-delete']]);
Route::get('/proforma_invoice/add', ['as'=>'proforma_invoice.add','uses'=>ProformaInvoiceController::class.'@add','middleware' => ['permission:pfi-create']]);
Route::get('/proforma_invoice/add/{id}', ['as'=>'proforma_invoice.add','uses'=>ProformaInvoiceController::class.'@add','middleware' => ['permission:pfi-create']]);
Route::get('/proforma_invoice/add/{id}/{n}', ['as'=>'proforma_invoice.add','uses'=>ProformaInvoiceController::class.'@add','middleware' => ['permission:pfi-create']]);
Route::post('/proforma_invoice/save', ['as' => 'proforma_invoice.save', 'uses' => ProformaInvoiceController::class.'@save', 'middleware' => ['permission:pfi-create']] );
Route::get('/proforma_invoice/edit/{id}', ['as' => 'proforma_invoice.edit', 'uses' => ProformaInvoiceController::class.'@edit', 'middleware' => ['permission:pfi-edit']]);
Route::post('/proforma_invoice/update/{id}', ['as' => 'proforma_invoice.update', 'uses' => ProformaInvoiceController::class.'@update', 'middleware' => ['permission:pfi-edit']]);
Route::get('/proforma_invoice/customer_data', [ProformaInvoiceController::class, 'getCustomer']);
//Route::get('/proforma_invoice', [ProformaInvoiceController::class, 'index']);
//Route::get('/proforma_invoice/add', [ProformaInvoiceController::class, 'add']);
Route::get('/proforma_invoice/salesman_data', [ProformaInvoiceController::class, 'getSalesman']);
Route::get('/proforma_invoice/item_data/{id}', [ProformaInvoiceController::class, 'getItem']);
Route::get('/proforma_invoice/checkrefno', [ProformaInvoiceController::class, 'checkRefNo']);
Route::get('/proforma_invoice/delete/{id}', ['as' => 'proforma_invoice.destroy', 'uses' => ProformaInvoiceController::class.'@destroy', 'middleware' => ['permission:pfi-delete']]);
Route::get('/proforma_invoice/get_order/{id}/{n}', [ProformaInvoiceController::class, 'getOrder']);
Route::get('/proforma_invoice/item_details/{id}', [ProformaInvoiceController::class, 'getItemDetails']);
Route::get('/proforma_invoice/print/{id}', ['as' => 'proforma_invoice.getPrint', 'uses' => ProformaInvoiceController::class.'@getPrint', 'middleware' => ['permission:pfi-print']]);
Route::get('/proforma_invoice/set_session', [ProformaInvoiceController::class, 'setSessionVal']);
Route::post('/proforma_invoice/search', [ProformaInvoiceController::class, 'getSearch']);
Route::get('/proforma_invoice/print/{id}/{fc}', ['as' => 'proforma_invoice.getPrint', 'uses' => ProformaInvoiceController::class.'@getPrint', 'middleware' => ['permission:pfi-print']]);
Route::post('/proforma_invoice/export', [ProformaInvoiceController::class, 'dataExport']);
Route::get('/proforma_invoice/newcustomer_data', [ProformaInvoiceController::class, 'getNewCustomer']);
Route::get('/proforma_invoice/checkvchrno', [ProformaInvoiceController::class, 'checkVchrNo']);
Route::post('/proforma_invoice/paging', [ProformaInvoiceController::class, 'ajaxPaging']);
Route::get('/proforma_invoice/customer_data/{did}', [ProformaInvoiceController::class, 'getCustomer']);
Route::get('/proforma_invoice/newcustomer_data/{did}', [ProformaInvoiceController::class, 'getNewCustomer']);
Route::get('/proforma_invoice/poadd/{id}', ['as'=>'proforma_invoice.add','uses'=>ProformaInvoiceController::class.'@poadd','middleware' => ['permission:pfi-create']]);
Route::post('/proforma_invoice/get_orderno', [ProformaInvoiceController::class, 'getOrderNo']);
Route::get('/proforma_invoice/getcounter/{id}', [ProformaInvoiceController::class, 'getCounter']);
Route::get('/proforma_invoice/get_report/{id}', [ProformaInvoiceController::class, 'getReport']);
		
		
Route::get('/item_template', [ItemTemplateController::class, 'index']);
Route::get('/item_template/add', [ItemTemplateController::class, 'add']);
Route::post('/item_template/save', [ItemTemplateController::class, 'save']);
Route::get('/item_template/get_template/{id}/{n}', [ItemTemplateController::class, 'getTemplate']);
Route::get('/item_template/get_items/{id}', [ItemTemplateController::class, 'getItems']);
Route::post('/item_template/save_joborder', [ItemTemplateController::class, 'saveJoborder']);
Route::post('/item_template/upload-attachment', [ItemTemplateController::class, 'uploadAttachment']);
Route::get('/item_template/get_template_edit/{id}/{jid}/{n}', [ItemTemplateController::class, 'getTemplateEdit']);
Route::get('/item_template/edit/{id}', [ItemTemplateController::class, 'edit']);
Route::post('/item_template/update', [ItemTemplateController::class, 'update']);
Route::get('/item_template/delete/{id}', [ItemTemplateController::class, 'destroy']);
		
Route::get('/jobprocess_report', [JobProcessReportController::class, 'index']);
Route::post('/jobprocess_report/search', [JobProcessReportController::class, 'getSearch']);
//Route::post('/jobprocess_report/print', [JobReportController::class, 'getPrint']);
//Route::post('/jobprocess_report/export', [JobReportController::class, 'dataExport']);

Route::get('/tenantmaster', [TenantMasterController::class, 'index']);
Route::get('/tenantmaster/tenant_list/{val}', [TenantMasterController::class, 'tenantList']);
Route::post('/tenantmaster/save', [TenantMasterController::class, 'save']);
Route::get('/tenantmaster/add', [TenantMasterController::class, 'add']);
Route::get('/tenantmaster/edit/{id}', [TenantMasterController::class, 'edit']);
Route::post('/tenantmaster/update/{id}', [TenantMasterController::class, 'update']);
Route::get('/tenantmaster/delete/{id}', [TenantMasterController::class, 'destroy']);
//Route::get('/flatmaster/checkcode', [FlatMasterController::class, 'checkCode']);
		
Route::get('/tenantenquiry', [TenantEnquiryController::class, 'index']);
Route::get('/tenantenquiry/enquiry_list/{val}', [TenantEnquiryController::class, 'enquiryList']);
Route::get('/tenantenquiry/enquiry_list/{val}/{f}', [TenantEnquiryController::class, 'enquiryList']);
Route::post('/tenantenquiry/save', [TenantEnquiryController::class, 'save']);
Route::get('/tenantenquiry/add', [TenantEnquiryController::class, 'add']);
Route::get('/tenantenquiry/edit/{id}', [TenantEnquiryController::class, 'edit']);
Route::post('/tenantenquiry/update/{id}', [TenantEnquiryController::class, 'update']);
Route::get('/tenantenquiry/delete/{id}', [TenantEnquiryController::class, 'destroy']);


Route::get('/crm_template', [CrmTemplateController::class, 'index']);
Route::post('/crm_template/save', [CrmTemplateController::class, 'save']);
		
Route::get('/dashboard_design', [DashboardDesignController::class, 'index']);
		
Route::get('/contract-connection', [ContractConnectionController::class, 'index']);
Route::get('/contract-connection/add', [ContractConnectionController::class, 'add']);
Route::post('/contract-connection/save', [ContractConnectionController::class, 'save']);
Route::get('/contract-connection/edit/{id}', [ContractConnectionController::class, 'edit']);
Route::post('/contract-connection/update/{id}', [ContractConnectionController::class, 'update']);
Route::get('/contract-connection/delete/{id}', [ContractConnectionController::class, 'destroy']);
Route::get('/contract-connection/print/{id}', [ContractConnectionController::class, 'getPrint']);
Route::get('/contract-connection/reading/{id}', [ContractConnectionController::class, 'reading']);
Route::post('/contract-connection/readsave', [ContractConnectionController::class, 'readUpdate']);
Route::get('/contract-connection/reading-list', [ContractConnectionController::class, 'readingList']);
Route::get('/contract-connection/transfer/{id}', [ContractConnectionController::class, 'add']);
Route::get('/contract-connection/print/{id}/{rid}', [ContractConnectionController::class, 'getPrint']);
Route::get('/contract-connection/reading-add', [ContractConnectionController::class, 'readingAdd']);
Route::get('/contract-connection/getreading/{cid}/{fid}/{type}', [ContractConnectionController::class, 'getReading']);
Route::get('/contract-connection/disconnection-add', [ContractConnectionController::class, 'disconnectAdd']);
Route::post('/contract-connection/disconsave', [ContractConnectionController::class, 'disconUpdate']);
Route::get('/contract-connection/disconnection-list', [ContractConnectionController::class, 'disconnectionList']);
Route::get('/contract-connection/print_all/{id}', [ContractConnectionController::class, 'printAll']);
Route::get('/contract-connection/building_read', [ContractConnectionController::class, 'buildingRead']);
Route::get('/contract-connection/get_billdata/{id}', [ContractConnectionController::class, 'getBilldata']);
Route::get('/contract-connection/print-read/{id}/{rid}', [ContractConnectionController::class, 'getPrintRead']);

Route::get('/creditnotejournal', ['as' => 'creditnotejournal.index', 'uses' => CreditNoteJournalController::class.'@index', 'middleware' => ['permission:jv-list|jv-create|jv-edit|jv-delete']]);
Route::get('/creditnotejournal/add', ['as'=>'creditnotejournal.add','uses'=>CreditNoteJournalController::class.'@add','middleware' => ['permission:jv-create']]);
Route::post('/creditnotejournal/save', [CreditNoteJournalController::class, 'save']);
Route::get('/creditnotejournal/getvoucher/{id}', [CreditNoteJournalController::class, 'getVoucher']);
Route::get('/creditnotejournal/delete/{id}/{n}', ['as' => 'creditnotejournal.destroy', 'uses' => CreditNoteJournalController::class.'@destroy', 'middleware' => ['permission:jv-delete']]);
Route::get('/creditnotejournal/getvouchertype/{id}', [CreditNoteJournalController::class, 'getVoucherType']);
Route::get('/creditnotejournal/edit/{id}', ['as' => 'creditnotejournal.edit', 'uses' => CreditNoteJournalController::class.'@edit', 'middleware' => ['permission:jv-edit']]);
Route::post('/creditnotejournal/update/{id}', [CreditNoteJournalController::class, 'update']);
Route::get('/creditnotejournal/checkvchrno', [CreditNoteJournalController::class, 'checkVchrNo']);
Route::get('/creditnotejournal/print/{id}', ['as' => 'creditnotejournal.getPrint', 'uses' => CreditNoteJournalController::class.'@getPrint', 'middleware' => ['permission:jv-print']]);
Route::get('/creditnotejournal/print/{id}/{rid}', ['as' => 'creditnotejournal.getPrint', 'uses' => CreditNoteJournalController::class.'@getPrint', 'middleware' => ['permission:jv-print']]);
Route::get('/creditnotejournal/add/{id}/{rid}/{vouchertype}', ['as'=>'creditnotejournal.add','uses'=>CreditNoteJournalController::class.'@add','middleware' => ['permission:jv-create']]);
Route::get('/creditnotejournal/getvoucherprint', [CreditNoteJournalController::class, 'getVoucherprint']);
Route::get('/creditnotejournal/set_transactions/{type}/{id}/{n}', [CreditNoteJournalController::class, 'setTransactions']);
Route::post('/creditnotejournal/paging', [CreditNoteJournalController::class, 'ajaxPaging']);
Route::post('/creditnotejournal/recurring_add', [CreditNoteJournalController::class, 'recurringAdd']);

Route::get('/packing_list', [PackingListController::class, 'index']);
Route::get('/packing_list/add', [PackingListController::class, 'add'],'middleware');
Route::get('/packing_list/add/{id}', [PackingListController::class, 'add']);
Route::get('/packing_list/add/{id}/{n}', [PackingListController::class, 'add']);
Route::post('/packing_list/save', [PackingListController::class, 'save']);
Route::get('/packing_list/edit/{id}', [PackingListController::class, 'edit']);
Route::post('/packing_list/update/{id}', [PackingListController::class, 'update']);
Route::get('/packing_list/customer_data', [PackingListController::class, 'getCustomer']);
Route::get('/packing_list/salesman_data', [PackingListController::class, 'getSalesman']);
Route::get('/packing_list/item_data/{id}', [PackingListController::class, 'getItem']);
Route::get('/packing_list/checkrefno', [PackingListController::class, 'checkRefNo']);
Route::get('/packing_list/delete/{id}', [PackingListController::class, 'destroy']);
Route::get('/packing_list/get_invoice/{id}/{n}', [PackingListController::class, 'getInvoice']);
Route::get('/packing_list/item_details/{id}', [PackingListController::class, 'getItemDetails']);
Route::get('/packing_list/print/{id}', [PackingListController::class, 'getPrint']);
Route::get('/packing_list/set_session', [PackingListController::class, 'setSessionVal']);
Route::post('/packing_list/search', [PackingListController::class, 'getSearch']);
Route::get('/packing_list/print/{id}/{fc}', [PackingListController::class, 'getPrint']);
Route::post('/packing_list/export', [PackingListController::class, 'dataExport']);
Route::get('/packing_list/newcustomer_data', [PackingListController::class, 'getNewCustomer']);
Route::get('/packing_list/checkvchrno', [PackingListController::class, 'checkVchrNo']);
Route::post('/packing_list/paging', [PackingListController::class, 'ajaxPaging']);
Route::get('/packing_list/customer_data/{did}', [PackingListController::class, 'getCustomer']);
Route::get('/packing_list/newcustomer_data/{did}', [PackingListController::class, 'getNewCustomer']);
Route::get('/packing_list/poadd/{id}', [PackingListController::class, 'poadd']);
Route::post('/packing_list/get_orderno', [PackingListController::class, 'getOrderNo']);
Route::get('/packing_list/getcounter/{id}', [PackingListController::class, 'getCounter']);
Route::get('/packing_list/get_report/{id}', [PackingListController::class, 'getReport']);
Route::get('/packing_list/get_packinglist', [PackingListController::class, 'getPackingList']);
Route::post('/packing_list/customer_list', [PackingListController::class, 'ajaxCustomerList']);
Route::get('/packing_list/account_data/{type}', [PackingListController::class, 'getAccount']);

		//TRIAL BALANCE...
Route::get('/trial_balance2', [TrialBalanceController2::class, 'index']);
Route::get('/trial_balance2/search', [TrialBalanceController2::class, 'searchReport']);
Route::get('/trial_balance2/sb', [TrialBalanceController2::class, 'indexsubmit']);
		
Route::get('balancesheet2', [BalanceSheetController2::class, 'index'])->name('balancesheet2.index');
Route::get('balancesheet2/search', [BalanceSheetController2::class, 'report'])->name('balancesheet2.report');
//Route::get('/balancesheet2/export', [BalanceSheetController2::class, 'export'])->name('balancesheet2.export');


Route::get('/profit_loss2', [ProfitLossController2::class, 'index']);
Route::get('/profit_loss2/search', [ProfitLossController2::class, 'profitLoss']);
		
	});
	
Route::get('/manage', [ManageController::class, 'index']);
Route::get('/disable', [ManageController::class, 'getDisable']);
Route::get('/enable', [ManageController::class, 'getEnable']);
	 
Route::get('/sign', [SignController::class, 'index']);
Route::post('/sign', [SignController::class, 'index']);
	 
Route::get('/myorder', [MyOrderController::class, 'index']);
Route::post('/myorder/login', [MyOrderController::class, 'login']);
Route::get('/myorder/list', [MyOrderController::class, 'myList']);
Route::get('/myorder/set_status', [MyOrderController::class, 'setStatus']);
Route::get('/myorder/logout', [MyOrderController::class, 'Logout']);
Route::get('/myorder/dashboard', [MyOrderController::class, 'dashboard']);
Route::get('/myorder/report', [MyOrderController::class, 'report']);
Route::get('/myorder/ajax_search', [MyOrderController::class, 'ajaxSearch']);
Route::get('/myorder/pickup', [MyOrderController::class, 'getPickup']);
Route::get('/myorder/set_pkpstatus', [MyOrderController::class, 'setPkpStatus']);
Route::get('/myorder/pending', [MyOrderController::class, 'pendingList']);//MAY28
});

Route::get('/apicall', [ApicallController::class, 'index']);
Route::post('/apicall/sts', [ApicallController::class, 'status_chk']);



