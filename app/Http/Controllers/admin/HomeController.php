<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\AssignStock;
use App\Models\Customer;
use App\Models\Expense;
use App\Models\Gift;
use App\Models\ReturnStock;
use App\Models\Sale;
use App\Models\SalePayment;
use App\Models\SaleProduct;
use App\Models\Salesman;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class HomeController extends Controller
{
    public function index()
    {
        return view('admin.home');
    }

    public function DashboardgetData(Request $request)
    {
        $startdate = date('Y-m-d', strtotime($request->startDate));
        $enddate = date('Y-m-d', strtotime($request->endDate));

        //for customer
        $customer = Customer::whereBetween(DB::raw('DATE(created_at)'), [$startdate, $enddate])->count('id');

        //for total customer
        $total_customer = Customer::count('id');

        //pay amount
        $pay_amount = SalePayment::whereBetween(DB::raw('DATE(sale_payments.pay_date)'), [$startdate, $enddate])->sum('pay_amount');
        //total pay amount
        $total_pay_amount = SalePayment::sum('pay_amount');

        //for due payment
        $total_due_payment = Customer::sum('due_balance');
        $due_payment = Customer::whereBetween(DB::raw('DATE(updated_at)'), [$startdate, $enddate])->sum('due_balance');

        //for  Expense
        $expense = Expense::whereBetween('expense_date', [$startdate, $enddate])->sum('amount');
        $total_expense = Expense::sum('amount');

        //for assign stock
        $assign_stock = AssignStock::leftjoin('products', 'products.id', 'assign_stocks.product_id')
            ->whereBetween('assign_date', [$startdate, $enddate])
            ->sum(DB::raw('assign_stocks.qty*products.weight'));

        $total_assign_stock = AssignStock::leftjoin('products', 'products.id', 'assign_stocks.product_id')->sum(DB::raw('assign_stocks.qty*products.weight'));

        //for sale stock
        $sale_stock = SaleProduct::leftjoin('products', 'products.id', 'sale_products.product_id')
            ->where('sale_products.status', 0)
            ->whereBetween(DB::raw('DATE(sale_products.created_at)'), [$startdate, $enddate])
            ->sum(DB::raw('sale_products.qty*products.weight'));

        $total_sale_stock = SaleProduct::leftjoin('products', 'products.id', 'sale_products.product_id')
            ->where('sale_products.status', 0)
            ->sum(DB::raw('sale_products.qty*products.weight'));

        //for stock return
        $stock_return = ReturnStock::leftjoin('products', 'products.id', 'return_stocks.product_id')
            ->whereBetween('return_date', [$startdate, $enddate])
            ->sum(DB::raw('return_stocks.qty*products.weight'));

        $total_stock_return = ReturnStock::leftjoin('products', 'products.id', 'return_stocks.product_id')
            ->sum(DB::raw('return_stocks.qty*products.weight'));

        //gifts
        $gifts = Gift::whereBetween('gift_date', [$startdate, $enddate])->count('id');

        return view('admin.dashboard_data', compact('customer', 'total_customer', 'pay_amount', 'total_pay_amount', 'total_due_payment', 'due_payment', 'expense', 'total_expense', 'assign_stock', 'total_assign_stock', 'sale_stock', 'total_sale_stock', 'stock_return', 'total_stock_return', 'gifts'));

    }


    public function DashboardSalesmanGetData(Request $request)
    {

        $startdate = date('Y-m-d', strtotime($request->startDate));
        $enddate = date('Y-m-d', strtotime($request->endDate));

        $data = Salesman::get();

        /* dd($data);*/
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn("DT_RowIndex", '')
            ->editColumn('assignStock', function ($datatables) {
                return $datatables->assignStock . " Kg";
            })
            ->editColumn('salary_amount', function ($datatables) {
                if ($datatables->salary_type == 'Monthly') {
                    return $datatables->salary_amount . ' प्रति महीना';
                } else {
                    return $datatables->salary_amount . '  प्रति घंटा';
                }

            })
            ->editColumn('assignStock', function ($datatables) use ($startdate, $enddate) {

                $data = AssignStock::leftjoin('products', 'products.id', 'assign_stocks.product_id')
                    ->where('assign_stocks.salesman_id', $datatables->id)
                    ->whereBetween('assign_date', [$startdate, $enddate])
                    ->sum(DB::raw('assign_stocks.qty*products.weight'));

                return $data . " Kg";
            })
            ->editColumn('SoldStock', function ($datatables) use ($startdate, $enddate) {
                $data = Sale::join('sale_products', 'sale_products.sale_id', 'sales.id')
                    ->leftjoin('products', 'products.id', 'sale_products.product_id')
                    ->where('sales.salesman_id', $datatables->id)
                    ->whereBetween(DB::raw('DATE(sales.sale_date)'), [$startdate, $enddate])
                    ->where('sale_products.status', 0)
                    ->sum(DB::raw('sale_products.qty*products.weight'));
                return $data . " Kg";
            })
            ->editColumn('PaidAmount', function ($datatables) use ($startdate, $enddate) {
                $data = SalePayment::where('salesman_id', $datatables->id)
                    ->whereBetween(DB::raw('DATE(sale_payments.pay_date)'), [$startdate, $enddate])
                    ->sum('pay_amount');
                return $data;
            })
            ->editColumn('Total_amount', function ($datatables) use ($startdate, $enddate) {
                $data = Sale::where('salesman_id', $datatables->id)
                    ->whereBetween(DB::raw('DATE(sales.sale_date)'), [$startdate, $enddate])
                    ->sum('total_amount');
                return $data;
            })
            ->editColumn('payment_commission', function ($datatables) use ($startdate, $enddate) {
                $payment_commission = Sale::where('salesman_id', $datatables->id)
                    ->whereBetween(DB::raw('DATE(sales.sale_date)'), [$startdate, $enddate])
                    ->sum('sale_commission');
                return $payment_commission;
            })
            ->editColumn('balance', function ($datatables) use ($startdate, $enddate) {
                $total_amount = Sale::where('salesman_id', $datatables->id)
                    ->whereBetween(DB::raw('DATE(sales.sale_date)'), [$startdate, $enddate])
                    ->sum('total_amount');

                $pay_amount = SalePayment::where('salesman_id', $datatables->id)
                    ->whereBetween(DB::raw('DATE(sale_payments.pay_date)'), [$startdate, $enddate])
                    ->sum('pay_amount');
                $balance = $pay_amount - $total_amount;
                return $balance;
            })
            ->editColumn('Commission', function ($datatables) use ($startdate, $enddate) {
                $commission = Attendance::where('salesman_id', $datatables->id)
                    ->whereBetween('attendance_date', [$startdate, $enddate])
                    ->sum('commission');

                return $commission;
            })
            ->editColumn('salary_amount', function ($datatables) use ($startdate, $enddate) {
                $commission = Attendance::where('salesman_id', $datatables->id)
                    ->whereBetween('attendance_date', [$startdate, $enddate])
                    ->sum('salary');
                return $commission;
            })
            ->rawColumns(['balance'])->make(true);
    }
}
