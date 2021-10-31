<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;

/*Role Admin*/

use App\Http\Controllers\admin\HomeController as AdminHome;
use App\Http\Controllers\admin\ProfileController as AdminProfile;
use App\Http\Controllers\admin\RouteController;
use App\Http\Controllers\admin\LineController;
use App\Http\Controllers\admin\SalesmanController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\CustomerController;
use App\Http\Controllers\admin\PurchaseController;
use App\Http\Controllers\admin\AssignStockController;
use App\Http\Controllers\admin\ReturnStockController;
use App\Http\Controllers\admin\InStockController;
use App\Http\Controllers\admin\SaleController;
use App\Http\Controllers\admin\ExpenseController;
use App\Http\Controllers\admin\PaymentController;
use App\Http\Controllers\admin\LineHisabController;
use App\Http\Controllers\admin\AttendanceController;
use App\Http\Controllers\admin\InvoiceController;
use App\Http\Controllers\admin\ReportControlller;
use App\Http\Controllers\admin\GiftController;
use App\Http\Controllers\admin\ExtraWorkCommissionController;
use App\Http\Controllers\admin\AdvancePaymentController;
use App\Http\Controllers\admin\SaleInterestController;
use App\Http\Controllers\admin\SalesmanSalaryController;
use App\Http\Controllers\admin\SalaryGenerateController;

/*Role Salesman*/

use App\Http\Controllers\salesman\HomeController as SalesmanHome;
use App\Http\Controllers\salesman\ProfileController as SalesmanProfile;
use App\Http\Controllers\salesman\CustomerController as SalesmanCustomer;
use App\Http\Controllers\salesman\SaleController as SalesmanSale;
use App\Http\Controllers\salesman\PaymentController as SalesmanPayment;
use App\Http\Controllers\salesman\InvoiceController as SalesmanInvoice;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('storage', function () {
    Artisan::call('storage:link');
});
Route::get('clearcache', function () {
    Artisan::call('config:cache');
});

Route::get('routeclear', function () {
    Artisan::call('route:clear');
});
Route::get('migseed', function () {
    Artisan::call('migrate');
    Artisan::call('db:seed');
});

Route::get('cronjob',function()
{
 Artisan::call('schedule:run');
});
Route::get('passport',function()
{
    Artisan::call('passport:install');
});
Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();



Route::group(['middleware' => ['role:admin', 'auth'], 'prefix' => 'admin'], function () {

    Route::get('home', [AdminHome::class, 'index'])->name('admin.home');
    Route::get('home/getdata',[AdminHome::class,'DashboardgetData'])->name('admin.DashboardgetData');
    Route::get('home/salesmangetdata',[AdminHome::class,'DashboardSalesmanGetData'])->name('admin.salesmangetData');

    //Profile
    Route::get('profile', [AdminProfile::class, 'index'])->name('admin.profile');
    Route::post('profile/update/{id}', [AdminProfile::class, 'update'])->name('adminProfile.update');
    //route
    Route::get('routes/getdata', [RouteController::class, 'getData'])->name('routes.getData');
    Route::resource('routes', RouteController::class);
    //line
    Route::get('line/getdata', [LineController::class, 'getData'])->name('line.getData');
    Route::resource('line', LineController::class);

    //Salesman
    Route::get('salesman/getdata', [SalesmanController::class, 'getData'])->name('salesman.getData');
    Route::resource('salesman', SalesmanController::class);
    //Product
    Route::get('product/getdata', [ProductController::class, 'getData'])->name('product.getData');
    Route::resource('product', ProductController::class);
    //Customer
    Route::get('customer/getdata', [CustomerController::class, 'getData'])->name('customer.getData');

    //old due amount get data according to customers
    Route::get('customer/getoldDuedata', [CustomerController::class, 'getoldDuedata'])->name('Customer.getOldDue');
    //gift status get data accoding to customers
    Route::get('customer/getGiftData', [CustomerController::class, 'getGiftData'])->name('Customer.getGiftData');
    //Gift status update
    Route::get('giftStatusUpdate',[CustomerController::class,'GiftStatusUpdate'])->name('giftStatus.update');

    Route::get('customer/getdata', [CustomerController::class, 'getData'])->name('customer.getData');
    Route::resource('customer', CustomerController::class);
    Route::get('customersale/{id}', [CustomerController::class, 'customerSale'])->name('customersale');
    //Purchase
    Route::get('purchase/getdata', [PurchaseController::class, 'getData'])->name('purchase.getData');
    Route::resource('purchase', PurchaseController::class);
    //AssignStock
    Route::post('instockStore', [AssignStockController::class, 'instockStore']);
    Route::post('instockUpdate', [AssignStockController::class, 'instockUpdate']);
    Route::get('assignstock/getdata', [AssignStockController::class, 'getData'])->name('assignstock.getData');
    Route::get('assignstock/maxassigngetdata', [AssignStockController::class, 'maxAssignStock'])->name('maxAssignStock.getData');
    Route::resource('assignstock', AssignStockController::class);
    //ReturnStock
    Route::get('returnstock/getdata', [ReturnStockController::class, 'getData'])->name('returnstock.getData');
    Route::resource('returnstock', ReturnStockController::class);

    //InStock
    Route::get('instock/getdata', [InStockController::class, 'getData'])->name('instock.getData');
    Route::resource('instock', InStockController::class);

    //product get data
    Route::get('sale/getProductData', [SaleController::class, 'getProductData'])->name('product.getProductData');
    Route::get('saleproductDelete/{id}', [SaleController::class, 'SaleProductDelete'])->name('saleproduct.delete');
    //Sale
    Route::get('sale/getdata', [SaleController::class, 'getData'])->name('sale.getData');
    Route::resource('sale', SaleController::class);
    Route::get('saleproductDelete/{id}', [SaleController::class, 'SaleProductDelete'])->name('saleproduct.delete');

    //sale view product store
    Route::post('saleproductstore', [SaleController::class, 'SaleProductStore'])->name('SaleProductStore');

    //sale view product edit
    Route::get('saleproductedit', [SaleController::class, 'EditSaleProduct'])->name('saleproductedit.getData');

    //sale view product update
    Route::post('saleproductupdate', [SaleController::class, 'saleproductupdate'])->name('saleproductupdate');

    //sale view add payment
    Route::post('addpayment', [SaleController::class, 'Saleaddpayment'])->name('addpayment');

    //sale view payment edit
    Route::get('editpayment', [SaleController::class, 'EditSalePayment'])->name('editpayment.getData');

    //sale view payment update
    Route::post('updatepayment', [SaleController::class, 'updatepayment'])->name('updatepayment');
    //sale view produt delete
    Route::get('deletepayment/{id}', [SaleController::class, 'deletepayment'])->name('deletepayment');

    //Paid Payment
    Route::get('paidpayment/getdata', [PaymentController::class, 'getPaidPaymentData'])->name('paidpayment.getData');
    Route::get('paidpayment', [PaymentController::class, 'paidpaymentindex'])->name('paidpayment.index');

    //Due Payment
    Route::get('duepayment/getdata', [PaymentController::class, 'getduePaymentData'])->name('duepayment.getData');
    Route::get('duepayment', [PaymentController::class, 'duepaymentindex'])->name('duepayment.index');

    //Expenses
    Route::get('expense/getdata', [ExpenseController::class, 'getExpenseData'])->name('expense.getData');
    Route::resource('expense', ExpenseController::class);

    //line Hisab
    Route::get('linehisab/getdata', [LineHisabController::class, 'getLineHisabData'])->name('linehisab.getData');
    Route::get('linehisab', [LineHisabController::class, 'index'])->name('linehisab');

    //attendance
    Route::get('attendance/getdata', [AttendanceController::class, 'attendanceData'])->name('attendance.getData');
    Route::get('attendance/viewgetdata', [AttendanceController::class, 'attendanceviewData'])->name('attendanceview.getData');
    Route::get('attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('attendanceStore', [AttendanceController::class, 'store'])->name('attendance.store');

    //invoice
    Route::get('invoice/{id}', [InvoiceController::class, 'index'])->name('invoice');
    //payment receipt
    Route::get('paymentreceipt/{id}', [SaleController::class, 'paymentReceipt'])->name('paymentReceipt');
    //product item report
    Route::get('productreport/getdata', [ReportControlller::class, 'productReportGetdata'])->name('productReportGetdata');
    Route::get('productreport', [ReportControlller::class, 'productReport'])->name('productReport');
    //salesman password update
    Route::post('SalesmanPasswordUpdate/{id}', [SalesmanController::class, 'SalesmanPasswordUpdate'])->name('SalesmanPasswordUpdate');

    //Payment Report Export To Excel
    Route::get('PaidPaymentExporttToExcel', [PaymentController::class, 'PaidPaymentExporttToExcel'])->name('PaidPaymentExporttToExcel');
    //DuePayment Report Export To Excel
    Route::get('DuePaymentExporttToExcel', [PaymentController::class, 'DuePaymentExporttToExcel'])->name('DuePaymentExporttToExcel');

    //Product Report Export To Excel
    Route::get('ProductReportExporttToExcel', [ReportControlller::class, 'ProductReportExporttToExcel'])->name('ProductReportExporttToExcel');

    //sale return
    Route::post('saleReturn/{id}',[SaleController::class,'saleReturn'])->name('salereturn');

    //submit sale interest
    Route::post('InterestSubmit/{id}',[SaleInterestController::class,'InterestSubmit'])->name('InterestSubmit');

   //gift
    Route::get('giftCustomer/getdata', [GiftController::class, 'giftCustomer'])->name('giftCustomer.getData');
    Route::get('giftCustomer', [GiftController::class, 'index'])->name('GiftCustomerData');

    //gift product update
    Route::post('giftProductUpdate',[GiftController::class,'giftProdcutUpdate'])->name('giftProduct.update');

    //for extra work commission get salesamn data
    Route::get('extraworkSalesman',[ExtraWorkCommissionController::class,'extraWorkSalesmanGetData']);

    //for olddueSubmit
    Route::get('olddueGet',[CustomerController::class,'olddueGet']);
    Route::post('olddueAdd',[CustomerController::class,'olddueAdd'])->name('olddueAdd');



    //advance payment
    Route::get('advancepayment/getData',[AdvancePaymentController::class,'getData'])->name('advancepayment.getData');
    Route::resource('advancepayment',AdvancePaymentController::class);

    //for olddueSubmit
    Route::get('maxPayCustomer',[CustomerController::class,'maxPayCustomer']);

    //payment receipt
    Route::get('ReturnReceipt/{id}', [SaleController::class, 'ReturnReceipt'])->name('ReturnReceipt');
    //product item report

    Route::get('saleinterest/getdata', [SaleInterestController::class, 'SaleInterestData'])->name('SaleInterest.getData');
    Route::get('saleinterest', [SaleInterestController::class, 'index'])->name('saleinterest.index');

    //Sale Interest update
    Route::post('saleinterestSettingUpdate',[SaleInterestController::class,'saleinterestSettingUpdate'])->name('saleInterestSetting.update');

    //sale iterest Receipt
    Route::get('saleinterestReceipt/{id}',[SaleInterestController::class,'SaleInterestReceipt'])->name('SaleInterestReceipt');

    //salary
    Route::get('Salary/getData',[SalesmanSalaryController::class,'SalaryGetdata'])->name('SalaryGetdata');
    Route::get('salary',[SalesmanSalaryController::class,'index'])->name('salary.index');

    //salesman salary generated
    Route::get('salesmanSalary/getdata',[SalaryGenerateController::class,'salesmanSalaryGetdata'])->name('generatedSalary.getData');
    Route::get('salesmanSalary/getSalaryData',[SalaryGenerateController::class,'getSalarayData'])->name('getSalaryData');
    Route::get('salesmanSalary',[SalaryGenerateController::class,'salesmanSalary'])->name('salesmanSalary.index');
    //Route for Salaray store
    Route::post('salary-store',[SalaryGenerateController::class,'salaryStore'])->name('salaryStore');

    //generated Salary
    //salary pay
    Route::post('generated-salary/{id}',[SalaryGenerateController::class,'salarypay'])->name('salaryPay');


    //for testing purpose
    //gift assign to customer
    Route::get('giftAssign',[GiftController::class,'giftAssign']);
    //add sale Interest
    Route::get('AddSaleInterest',[SaleInterestController::class,'AddSaleInterest']);
    Route::get('AddSaleInterest',[SaleInterestController::class,'AddSaleInterestRecursive']);

    //
});


Route::group(['middleware' => ['role:salesman', 'auth'], 'prefix' => 'salesman'], function () {
    //salesman home
    Route::get('home', [SalesmanHome::class, 'index'])->name('salesman.home');
    //salesman profile
    Route::get('profile', [SalesmanProfile::class, 'index'])->name('salesman.profile');
    Route::post('profile/update/{id}', [SalesmanProfile::class, 'update'])->name('salesmanProfile.update');
    //salesman Customer
    Route::get('salesmancustomer/getdata', [SalesmanCustomer::class, 'getData'])->name('salesmancustomer.getData');
    Route::resource('salesmancustomer', SalesmanCustomer::class);

    //product get data
    Route::get('sale/getProductData', [SalesmanSale::class, 'getProductData'])->name('salesmanproduct.getProductData');
    //salesman sale
    Route::get('salesmansale/getdata', [SalesmanSale::class, 'getData'])->name('salesmansale.getData');
    Route::resource('salesmansale', SalesmanSale::class);

    //sale view product store
    Route::post('saleproductstore', [SalesmanSale::class, 'SaleProductStore'])->name('salesmanSaleProductStore');

    //sale view product index
    Route::get('salesmansale/productgetdata', [SalesmanSale::class, 'saleviewProductget'])->name('salesmansale.productgetData');

    //sale view product edit
    Route::get('salesmansaleproductedit', [SalesmanSale::class, 'EditSaleProduct'])->name('salesmansaleproductedit.getData');

    //sale view product update
    Route::post('saleproductupdate', [SalesmanSale::class, 'saleproductupdate'])->name('salesmansaleproductupdate');

    //sale view add payment
    Route::post('addpayment', [SalesmanSale::class, 'Saleaddpayment'])->name('salesmanaddpayment');

    //sale view payment edit
    Route::get('editpayment', [SalesmanSale::class, 'EditSalePayment'])->name('salesmaneditpayment.getData');

    //sale view payment update
    Route::post('updatepayment', [SalesmanSale::class, 'updatepayment'])->name('salesmanupdatepayment');
    //sale view produt delete
    Route::get('deletepayment/{id}', [SalesmanSale::class, 'deletepayment'])->name('salesmandeletepayment');
    //Paid Payment
    Route::get('salesmanpaidpayment/getdata', [SalesmanPayment::class, 'getPaidPaymentData'])->name('salesmanpaidpayment.getData');
    Route::get('salesmanpaidpayment', [SalesmanPayment::class, 'paidpaymentindex'])->name('salesmanpaidpayment.index');

    //Due Payment
    Route::get('salesmanduepayment/getdata', [SalesmanPayment::class, 'getduePaymentData'])->name('salesmanduepayment.getData');
    Route::get('salesmanduepayment', [SalesmanPayment::class, 'duepaymentindex'])->name('salesmanduepayment.index');

    //customer view to sale
    Route::get('customersales/{id}', [SalesmanCustomer::class, 'customerSales'])->name('customersales');
    //invoice
    Route::get('invoices/{id}', [SalesmanInvoice::class, 'index'])->name('invoices');
    //payment receipt
    Route::get('paymentreceipts/{id}', [SalesmanSale::class, 'paymentReceipts'])->name('paymentReceipts');
});

