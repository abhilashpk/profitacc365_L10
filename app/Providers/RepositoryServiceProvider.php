<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(\App\Repositories\Accategory\AccategoryInterface::class, \App\Repositories\Accategory\AccategoryRepository::class);
        $this->app->bind(\App\Repositories\AccountMaster\AccountMasterInterface::class, \App\Repositories\AccountMaster\AccountMasterRepository::class);
        $this->app->bind(\App\Repositories\AccountSetting\AccountSettingInterface::class, \App\Repositories\AccountSetting\AccountSettingRepository::class);
        $this->app->bind(\App\Repositories\Acgroup\AcgroupInterface::class, \App\Repositories\Acgroup\AcgroupRepository::class);
        $this->app->bind(\App\Repositories\Area\AreaInterface::class, \App\Repositories\Area\AreaRepository::class);
        $this->app->bind(\App\Repositories\Bank\BankInterface::class, \App\Repositories\Bank\BankRepository::class);
        $this->app->bind(\App\Repositories\Category\CategoryInterface::class, \App\Repositories\Category\CategoryRepository::class);
        $this->app->bind(\App\Repositories\Company\CompanyInterface::class, \App\Repositories\Company\CompanyRepository::class);
        $this->app->bind(\App\Repositories\Country\CountryInterface::class, \App\Repositories\Country\CountryRepository::class);
        $this->app->bind(\App\Repositories\CreditNote\CreditNoteInterface::class, \App\Repositories\CreditNote\CreditNoteRepository::class);
        $this->app->bind(\App\Repositories\CreditNoteJournal\CreditNoteJournalInterface::class, \App\Repositories\CreditNoteJournal\CreditNoteJournalRepository::class);
        $this->app->bind(\App\Repositories\Currency\CurrencyInterface::class, \App\Repositories\Currency\CurrencyRepository::class);
        $this->app->bind(\App\Repositories\CustomerDo\CustomerDoInterface::class, \App\Repositories\CustomerDo\CustomerDoRepository::class);
        $this->app->bind(\App\Repositories\CustomerEnquiry\CustomerEnquiryInterface::class, \App\Repositories\CustomerEnquiry\CustomerEnquiryRepository::class);
        $this->app->bind(\App\Repositories\CustomerReceipt\CustomerReceiptInterface::class, \App\Repositories\CustomerReceipt\CustomerReceiptRepository::class);
        $this->app->bind(\App\Repositories\DebitNote\DebitNoteInterface::class, \App\Repositories\DebitNote\DebitNoteRepository::class);
        $this->app->bind(\App\Repositories\Department\DepartmentInterface::class, \App\Repositories\Department\DepartmentRepository::class);
        $this->app->bind(\App\Repositories\Division\DivisionInterface::class, \App\Repositories\Division\DivisionRepository::class);
        $this->app->bind(\App\Repositories\Employee\EmployeeInterface::class, \App\Repositories\Employee\EmployeeRepository::class);
        $this->app->bind(\App\Repositories\Forms\FormsInterface::class, \App\Repositories\Forms\FormsRepository::class);
        $this->app->bind(\App\Repositories\GoodsIssued\GoodsIssuedInterface::class, \App\Repositories\GoodsIssued\GoodsIssuedRepository::class);
        $this->app->bind(\App\Repositories\GoodsReturn\GoodsReturnInterface::class, \App\Repositories\GoodsReturn\GoodsReturnRepository::class);
        $this->app->bind(\App\Repositories\Group\GroupInterface::class, \App\Repositories\Group\GroupRepository::class);
        $this->app->bind(\App\Repositories\HeaderFooter\HeaderFooterInterface::class, \App\Repositories\HeaderFooter\HeaderFooterRepository::class);
        $this->app->bind(\App\Repositories\Itemmaster\ItemmasterInterface::class, \App\Repositories\Itemmaster\ItemmasterRepository::class);
        $this->app->bind(\App\Repositories\ItemUnit\ItemUnitInterface::class, \App\Repositories\ItemUnit\ItemUnitRepository::class);
        $this->app->bind(\App\Repositories\Jobmaster\JobmasterInterface::class, \App\Repositories\Jobmaster\JobmasterRepository::class);
        $this->app->bind(\App\Repositories\Journal\JournalInterface::class, \App\Repositories\Journal\JournalRepository::class);
        $this->app->bind(\App\Repositories\Location\LocationInterface::class, \App\Repositories\Location\LocationRepository::class);
        $this->app->bind(\App\Repositories\LocationTransfer\LocationTransferInterface::class, \App\Repositories\LocationTransfer\LocationTransferRepository::class);
        $this->app->bind(\App\Repositories\LogDetails\LogDetailsInterface::class, \App\Repositories\LogDetails\LogDetailsRepository::class);
        $this->app->bind(\App\Repositories\ManualJournal\ManualJournalInterface::class, \App\Repositories\ManualJournal\ManualJournalRepository::class);
        $this->app->bind(\App\Repositories\Manufacture\ManufactureInterface::class, \App\Repositories\Manufacture\ManufactureRepository::class);
        $this->app->bind(\App\Repositories\MaterialRequisition\MaterialRequisitionInterface::class, \App\Repositories\MaterialRequisition\MaterialRequisitionRepository::class);
        $this->app->bind(\App\Repositories\OtherAccountSetting\OtherAccountSettingInterface::class, \App\Repositories\OtherAccountSetting\OtherAccountSettingRepository::class);
        $this->app->bind(\App\Repositories\OtherPayment\OtherPaymentInterface::class, \App\Repositories\OtherPayment\OtherPaymentRepository::class);
        $this->app->bind(\App\Repositories\OtherReceipt\OtherReceiptInterface::class, \App\Repositories\OtherReceipt\OtherReceiptRepository::class);
        $this->app->bind(\App\Repositories\PackingList\PackingListInterface::class, \App\Repositories\PackingList\PackingListRepository::class);
        $this->app->bind(\App\Repositories\Parameter1\Parameter1Interface::class, \App\Repositories\Parameter1\Parameter1Repository::class);
        $this->app->bind(\App\Repositories\Parameter2\Parameter2Interface::class, \App\Repositories\Parameter2\Parameter2Repository::class);
        $this->app->bind(\App\Repositories\Parameter4\Parameter4Interface::class, \App\Repositories\Parameter4\Parameter4Repository::class);
        $this->app->bind(\App\Repositories\PaymentVoucher\PaymentVoucherInterface::class, \App\Repositories\PaymentVoucher\PaymentVoucherRepository::class);
        $this->app->bind(\App\Repositories\PettyCash\PettyCashInterface::class, \App\Repositories\PettyCash\PettyCashRepository::class);
        $this->app->bind(\App\Repositories\Production\ProductionInterface::class, \App\Repositories\Production\ProductionRepository::class);
        $this->app->bind(\App\Repositories\ProformaInvoice\ProformaInvoiceInterface::class, \App\Repositories\ProformaInvoice\ProformaInvoiceRepository::class);
        $this->app->bind(\App\Repositories\PurchaseInvoice\PurchaseInvoiceInterface::class, \App\Repositories\PurchaseInvoice\PurchaseInvoiceRepository::class);
        $this->app->bind(\App\Repositories\PurchaseOrder\PurchaseOrderInterface::class, \App\Repositories\PurchaseOrder\PurchaseOrderRepository::class);
        $this->app->bind(\App\Repositories\PurchaseRental\PurchaseRentalInterface::class, \App\Repositories\PurchaseRental\PurchaseRentalRepository::class);
        $this->app->bind(\App\Repositories\PurchaseReturn\PurchaseReturnInterface::class, \App\Repositories\PurchaseReturn\PurchaseReturnRepository::class);
        $this->app->bind(\App\Repositories\PurchaseSplit\PurchaseSplitInterface::class, \App\Repositories\PurchaseSplit\PurchaseSplitRepository::class);
        $this->app->bind(\App\Repositories\PurchaseSplitReturn\PurchaseSplitReturnInterface::class, \App\Repositories\PurchaseSplitReturn\PurchaseSplitReturnRepository::class);
        $this->app->bind(\App\Repositories\Quotation\QuotationInterface::class, \App\Repositories\Quotation\QuotationRepository::class);
        $this->app->bind(\App\Repositories\QuotationSales\QuotationSalesInterface::class, \App\Repositories\QuotationSales\QuotationSalesRepository::class);
        $this->app->bind(\App\Repositories\ReceiptVoucher\ReceiptVoucherInterface::class, \App\Repositories\ReceiptVoucher\ReceiptVoucherRepository::class);
        $this->app->bind(\App\Repositories\RentalSales\RentalSalesInterface::class, \App\Repositories\RentalSales\RentalSalesRepository::class);
        $this->app->bind(\App\Repositories\SalesInvoice\SalesInvoiceInterface::class, \App\Repositories\SalesInvoice\SalesInvoiceRepository::class);
        $this->app->bind(\App\Repositories\Salesman\SalesmanInterface::class, \App\Repositories\Salesman\SalesmanRepository::class);
        $this->app->bind(\App\Repositories\SalesOrder\SalesOrderInterface::class, \App\Repositories\SalesOrder\SalesOrderRepository::class);
        $this->app->bind(\App\Repositories\SalesReturn\SalesReturnInterface::class, \App\Repositories\SalesReturn\SalesReturnRepository::class);
        $this->app->bind(\App\Repositories\SalesSplit\SalesSplitInterface::class, \App\Repositories\SalesSplit\SalesSplitRepository::class);
        $this->app->bind(\App\Repositories\SalesSplitReturn\SalesSplitReturnInterface::class, \App\Repositories\SalesSplitReturn\SalesSplitReturnRepository::class);
        $this->app->bind(\App\Repositories\StockTransferin\StockTransferinInterface::class, \App\Repositories\StockTransferin\StockTransferinRepository::class);
        $this->app->bind(\App\Repositories\StockTransferout\StockTransferoutInterface::class, \App\Repositories\StockTransferout\StockTransferoutRepository::class);
        $this->app->bind(\App\Repositories\SupplierDo\SupplierDoInterface::class, \App\Repositories\SupplierDo\SupplierDoRepository::class);
        $this->app->bind(\App\Repositories\SupplierPayment\SupplierPaymentInterface::class, \App\Repositories\SupplierPayment\SupplierPaymentRepository::class);
        $this->app->bind(\App\Repositories\TemplateName\TemplateNameInterface::class, \App\Repositories\TemplateName\TemplateNameRepository::class);
        $this->app->bind(\App\Repositories\Terms\TermsInterface::class, \App\Repositories\Terms\TermsRepository::class);
        $this->app->bind(\App\Repositories\Unit\UnitInterface::class, \App\Repositories\Unit\UnitRepository::class);
        $this->app->bind(\App\Repositories\Users\UsersInterface::class, \App\Repositories\Users\UsersRepository::class);
        $this->app->bind(\App\Repositories\VatMaster\VatMasterInterface::class, \App\Repositories\VatMaster\VatMasterRepository::class);
        $this->app->bind(\App\Repositories\VoucherNo\VoucherNoInterface::class, \App\Repositories\VoucherNo\VoucherNoRepository::class);
        $this->app->bind(\App\Repositories\VoucherType\VoucherTypeInterface::class, \App\Repositories\VoucherType\VoucherTypeRepository::class);
        $this->app->bind(\App\Repositories\VoucherwiseReport\VoucherwiseReportInterface::class, \App\Repositories\VoucherwiseReport\VoucherwiseReportRepository::class);
        $this->app->bind(\App\Repositories\WageEntry\WageEntryInterface::class, \App\Repositories\WageEntry\WageEntryRepository::class);
    }

    public function boot(): void
    {
        //
    }
}

